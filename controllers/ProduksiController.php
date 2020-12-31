<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\Produksi\StrukBaru;
use tlm\libs\LowEnd\components\DateTimeException;
use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class ProduksiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/produksi.php#cetak    the original method
     */
    public function actionCetak(): void
    {
        [   "edit_resep" => $editResep,
            "no_produksi" => $noRekamMedis,
            "kode_obat" => $daftarKodeObat,
            "qty" => $daftarJumlah,
            "produksi" => $idKatalog,
            "jumlahproduksi" => $jumlahProduksi,
        ] = Yii::$app->request->post();
        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;
        $idDepo = Yii::$app->userFatma->idDepo;

        $resep = "";
        if (!$editResep) {
            $fresep = "PR12" . date("Ymd");
            // changes on db-column, $produksi, and $resep is guess improvised.
            // there is no column named "no_resep".
            // "kode_produksi" is chosen because it match with next $resep statement.
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode_produksi
                FROM db1.masterf_produksi
                WHERE kode_produksi LIKE :kodeProduksi
                ORDER BY kode_produksi DESC
                LIMIT 1
            ";
            $params = [":kodeProduksi" => "$fresep%"];
            $kodeProduksi = $connection->createCommand($sql, $params)->queryScalar() ?? "0000";
            $resep = $fresep . sprintf("%04d", substr($kodeProduksi, - 4) + 1);
        }

        if ($noRekamMedis) {
            foreach ($daftarKodeObat as $key => $kodeObat) {
                if (!$kodeObat) continue;
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.masterf_produksi_komposisi
                    SET
                        kode_produksi = :kodeProduksi,
                        kode_katalog = :kodeKatalog,
                        jumlah = :jumlah
                ";
                $params = [":kodeProduksi" => $resep, ":kodeKatalog" => $kodeObat, ":jumlah" => $daftarJumlah[$key]];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_tersedia
                    FROM db1.relasif_ketersediaan
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                    ORDER BY id DESC
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                $jumlahTersedia = $connection->createCommand($sql, $params)->queryScalar() ?: 0;

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        id_depo = :idDepo,
                        kode_reff = :kodeRef,
                        tipe_tersedia = 'produksi2',
                        id_katalog = :idKatalog,
                        jumlah_keluar = :jumlahKeluar,
                        jumlah_tersedia = :jumlahTersedia,
                        keterangan = 'bahan produksi',
                        tgl_tersedia = :tanggalTersedia,
                        status = 1
                ";
                $params = [
                    ":idDepo" => $idDepo,
                    ":kodeRef" => $noRekamMedis,
                    ":idKatalog" => $kodeObat,
                    ":jumlahKeluar" => $daftarJumlah[$key],
                    ":jumlahTersedia" => $jumlahTersedia - $daftarJumlah[$key],
                    ":tanggalTersedia" => $nowValSystem,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = jumlah_stokfisik - :jumlah,
                        jumlah_stokadm = jumlah_stokadm - :jumlah
                    WHERE
                        id_katalog = :idKatalog
                        AND id_depo = :idDepo
                        AND status = 1
                ";
                $params = [":jumlah" => $daftarJumlah[$key], ":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                $connection->createCommand($sql, $params)->execute();
            }
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT jumlah_tersedia
            FROM db1.relasif_ketersediaan
            WHERE
                id_depo = :idDepo
                AND id_katalog = :idKatalog
            ORDER BY id DESC
            LIMIT 1
        ";
        $params = [":idDepo" => $idDepo, ":idKatalog" => $idKatalog];
        $jumlahTersedia = $connection->createCommand($sql, $params)->queryScalar() ?: 0;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.relasif_ketersediaan
            SET
                id_depo = :idDepo,
                kode_reff = :kodeRef,
                kode_transaksi = 'R',
                kode_store = '000000',
                tipe_tersedia = 'produksi1',
                tgl_tersedia = :tanggalTersedia,
                no_batch = '-',
                tgl_expired = :tanggalKadaluarsa,
                id_katalog = :idKatalog,
                jumlah_masuk = :jumlahMasuk,
                jumlah_keluar = 0,
                jumlah_tersedia = :jumlahTersedia,
                status = 1,
                keterangan = 'produksi1'
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":kodeRef" => $noRekamMedis,
            ":tanggalTersedia" => $nowValSystem,
            ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
            ":idKatalog" => $idKatalog,
            ":jumlahMasuk" => $jumlahProduksi,
            ":jumlahTersedia" => $jumlahTersedia + $jumlahProduksi,
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_stokkatalog
            SET
                jumlah_stokfisik = jumlah_stokfisik + :jumlahProduksi,
                jumlah_stokadm = jumlah_stokadm + :jumlahProduksi
            WHERE
                id_katalog = :idKatalog
                AND id_depo = :idDepo
                AND status = 1
        ";
        $params = [":jumlahProduksi" => $jumlahProduksi, ":idKatalog" => $idKatalog, ":idDepo" => $idDepo];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/produksi.php#print_produksi    the original method
     */
    public function actionPrint(): string
    {
        $kodeRef = Yii::$app->request->post("kodeRef") ?? throw new MissingPostParamException("kodeRef");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.harga_jual    AS hargaJual,
                a.jumlah_keluar AS jumlahKeluar,
                b.nama_barang   AS namaBarang
            FROM db1.relasif_ketersediaan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            WHERE
                a.tipe_tersedia = 'produksi2'
                AND kode_reff = :kodeRef
            ORDER BY a.id ASC
        ";
        $params = [":kodeRef" => $kodeRef];
        $daftarKomposisi = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.nama_barang  AS namaBarang,
                a.jumlah_masuk AS jumlahMasuk,
                a.tgl_tersedia AS tanggalTersedia
            FROM db1.relasif_ketersediaan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            WHERE
                a.tipe_tersedia = 'produksi1'
                AND kode_reff = :kodeRef
            ORDER BY a.id ASC
            LIMIT 1
        ";
        $params = [":kodeRef" => $kodeRef];
        $hasil = $connection->createCommand($sql, $params)->queryOne();

        $view = new StrukBaru(hasil: $hasil, daftarKomposisi: $daftarKomposisi);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/produksi.php#listproduksi    the original method
     */
    public function actionTableData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode_reff     AS kodeRef,
                tgl_tersedia  AS tanggalTersedia
            FROM db1.relasif_ketersediaan
            WHERE tipe_tersedia = 'produksi1'
            GROUP BY kode_reff
            ORDER BY kode_reff DESC
        ";
        $daftarProduksi = $connection->createCommand($sql)->queryAll();

        return json_encode($daftarProduksi);
    }
}
