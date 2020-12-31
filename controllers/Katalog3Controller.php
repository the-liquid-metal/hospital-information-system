<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

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
class Katalog3Controller extends BaseController
{
    /**
     * untuk menampilkan no batch
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/masterfkatalog.php#getbatch    the original method
     */
    public function actionGetBatch(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                no_batch        AS noBatch,
                jumlah_tersedia AS jumlahTersedia
            FROM db1.relasif_ketersediaan
            WHERE
                id_katalog = :idKatalog
                AND id_depo = :idDepo
                AND no_batch != ''
                AND no_batch != '-'
                AND status = 1
            ORDER BY no_batch ASC
        ";
        $params = [":idKatalog" => $_POST["q"], ":idDepo" => Yii::$app->userFatma->idDepo];
        $daftarKetersediaan = $connection->createCommand($sql, $params)->queryAll();

        $html = "";
        foreach ($daftarKetersediaan as $st) {
            $html .= "<option value='" . $st->noBatch . "'>" . $st->noBatch . " - " . $st->jumlahTersedia . "</option>";
        }
        return json_encode($html);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/masterfkatalog.php#typeaheadpoli    the original method
     */
    public function actionTypeaheadPoli(): string
    {
        ["val" => $val] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                id_poli AS id,
                nama_poli AS nama
            FROM db1.master_poli
            WHERE nama_poli LIKE :nama
        ";
        $params = [":nama" => $val];
        $rsData = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($rsData);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/masterfkatalog.php#getharga    the original method
     */
    public function actionGetHarga(): string
    {
        assert($_POST["kode"] && $_POST["jenis"], new MissingPostParamException("kode", "jenis"));
        ["kode" => $kode, "jenis" => $jenis] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.hja_setting   AS hjaSetting,
                B.hjapb_setting AS hjaPbSetting
            FROM db1.masterf_katalog AS A
            LEFT JOIN db1.relasif_hargaperolehan AS B ON B.id_katalog = A.kode
            WHERE
                A.kode = :kode
                AND B.sts_hja != 0
                AND B.sts_hjapb != 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $hargaDb = $connection->createCommand($sql, $params)->queryOne();

        if ($jenis == "Pembelian Bebas" && $hargaDb->hjaPbSetting){
            $harga = $hargaDb->hjaPbSetting;

        } elseif ($hargaDb->hjaSetting){
            $harga = $hargaDb->hjaSetting;

        } else {
            $harga = 0;
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT jumlah_stokfisik
            FROM db1.transaksif_stokkatalog
            WHERE
                id_katalog = :idKatalog
                AND id_depo = :idDepo
                AND status = 1
            LIMIT 1
        ";
        $params = [":idKatalog" => $kode, ":idDepo" => Yii::$app->userFatma->idDepo];
        $jumlahStokFisik = $connection->createCommand($sql, $params)->queryScalar() ?? 0;

        return json_encode(["stok" => ceil($jumlahStokFisik), "harga" => $harga]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/masterfkatalog.php#pembungkus    the original method
     */
    public function actionPembungkus(): string
    {
        $val = Yii::$app->request->post("val");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                id_pembungkus AS id,
                KD_PEMBUNGKUS AS kode,
                NM_PEMBUNGKUS AS nama,
                TARIF         AS tarif
            FROM db1.masterf_pembungkus
            WHERE
                KD_PEMBUNGKUS LIKE :val
                OR NM_PEMBUNGKUS LIKE :val
            ORDER BY NM_PEMBUNGKUS
        ";
        $params = [":val" => "%$val%"];
        $daftarPembungkus = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarPembungkus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/masterfkatalog.php#carisigna    the original method
     */
    public function actionCariSigna(): string
    {
        $connection = Yii::$app->dbFatma;

        ["nama" => $nama, "kategori" => $kategori] = Yii::$app->request->post();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_listsigna   AS id,
                signa_name     AS nama
            FROM db1.masterf_listsigna
            WHERE
                kategori_signa = :kategori
                AND signa_name LIKE :qTengah
            GROUP BY signa_name
            ORDER BY
                CASE
                    WHEN signa_name LIKE :qKiri THEN 1
                    WHEN signa_name LIKE :qKanan THEN 3
                    ELSE 2
                END
        ";
        $params = [":qTengah" => "%$nama%", ":qKiri" => "$nama%", ":qKanan" => "%$nama", ":kategori" => $kategori];
        $daftarSigna = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarSigna);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/masterfkatalog.php#typeahead22    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/masterfkatalog.php#typeaheadtanpadepo    the original method
     */
    public function actionSearchJsonObat(): string
    {
        ["q" => $val, "idDepo" => $idDepo] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                a.formularium_rs    AS formulariumRs,
                a.formularium_nas   AS formulariumNas,
                a.kode              AS kode,
                a.nama_barang       AS namaBarang,
                b.nama_kemasan      AS satuanKecil,
                c.nama_pabrik       AS namaPabrik,
                aa.jumlah_stokfisik AS stokFisik
            FROM db1.masterf_katalog AS a
            LEFT JOIN db1.masterf_kemasan AS b ON b.id = a.id_kemasankecil
            LEFT JOIN db1.masterf_pabrik AS c ON c.id = a.id_pabrik
            INNER JOIN db1.transaksif_stokkatalog AS aa on a.kode = aa.id_katalog
            WHERE
                a.sts_aktif = 1
                AND (a.kode LIKE :qTengah OR a.nama_barang LIKE :qTengah)
                AND (:idDepo = '' OR aa.id_depo = :idDepo)
            ORDER BY
                CASE
                    WHEN a.kode LIKE :qKanan THEN 1
                    WHEN a.nama_barang LIKE :qKanan THEN 1
                    WHEN a.kode LIKE :qKiri THEN 3
                    WHEN a.nama_barang LIKE :qKiri THEN 3
                    ELSE 2
                END ASC
            LIMIT 50
        ";
        $params = [
            ":qKiri" => "%$val",
            ":qKanan" => "$val%",
            ":qTengah" => "%$val%",
            ":idDepo" => $idDepo,
        ];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarKatalog);
    }
}
