<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

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
class ReturDepoController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/returndepo.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                  AS kode,
                a.no_resep              AS noResep,
                a.no_penjualan          AS noPenjualan,
                a.diskon                AS diskon,
                a.jasa                  AS jasa,
                a.kodePenjualan         AS kodePenjualan,
                a.kode_rm               AS kodeRekamMedis,
                a.no_daftar             AS noPendaftaran,
                a.nama_pasien           AS namaPasien,
                a.kodeObat              AS kodeObat,
                a.kodeObatdr            AS kodeObatDokter,
                a.nama_obatdr           AS namaObatDokter,
                a.urutan                AS urutan,
                a.jlhPenjualan          AS jumlahPenjualan,
                a.jlhPenjualandr        AS jumlahPenjualanDokter,
                a.signa                 AS signa,
                a.hna                   AS hna,
                a.hp                    AS hp,
                a.harga                 AS harga,
                a.id_racik              AS idRacik,
                a.kode_racik            AS kodeRacik,
                a.nama_racik            AS namaRacik,
                a.no_racik              AS noRacik,
                a.ketjumlah             AS keteranganJumlah,
                a.keterangan_obat       AS keteranganObat,
                a.kode_depo             AS kodeDepo,
                a.ranap                 AS rawatInap,
                a.tglPenjualan          AS tanggalPenjualan,
                a.lunas                 AS lunas,
                a.verifikasi            AS verifikasi,
                a.transfer              AS transfer,
                a.resep                 AS resep,
                a.tglverifikasi         AS tanggalVerifikasi,
                a.tgltransfer           AS tanggalTransfer,
                a.operator              AS operator,
                a.tglbuat               AS tanggalBuat,
                a.signa1                AS signa1,
                a.signa2                AS signa2,
                a.signa3                AS signa3,
                a.dokter_perobat        AS dokterPerObat,
                a.bayar                 AS bayar,
                a.tglbayar              AS tanggalBayar,
                a.checking_ketersediaan AS cekKetersediaan,
                a.keteranganobat        AS keteranganObat,
                a.kode_drperobat        AS kodeDokterPerObat,
                a.kode_operator         AS kodeOperator,
                a.kode_verifikasi       AS kodeVerifikasi,
                a.kode_transfer         AS kodeTransfer,
                a.no_resep              AS noResep,
                c.name                  AS namaDokter
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.user AS c ON c.id = e.dokter
            LEFT JOIN db1.masterf_antrian AS d ON d.kode_penjualan = a.kodePenjualan
            WHERE a.no_resep = :noResep
            ORDER BY a.no_resep DESC
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $resep = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                  AS kode,
                a.no_resep              AS noResep,
                a.no_penjualan          AS noPenjualan,
                a.diskon                AS diskon,
                a.jasa                  AS jasa,
                a.kodePenjualan         AS kodePenjualan,
                a.kode_rm               AS kodeRekamMedis,
                a.no_daftar             AS noPendaftaran,
                a.nama_pasien           AS namaPasien,
                a.kodeObat              AS kodeObat,
                a.kodeObatdr            AS kodeObatDokter,
                a.nama_obatdr           AS namaObatDokter,
                a.urutan                AS urutan,
                a.jlhPenjualan          AS jumlahPenjualan,
                a.jlhPenjualandr        AS jumlahPenjualanDokter,
                a.signa                 AS signa,
                a.hna                   AS hna,
                a.hp                    AS hp,
                a.harga                 AS harga,
                a.id_racik              AS idRacik,
                a.kode_racik            AS kodeRacik,
                a.nama_racik            AS namaRacik,
                a.no_racik              AS noRacik,
                a.ketjumlah             AS keteranganJumlah,
                a.keterangan_obat       AS keteranganObat,
                a.kode_depo             AS kodeDepo,
                a.ranap                 AS rawatInap,
                a.tglPenjualan          AS tanggalPenjualan,
                a.lunas                 AS lunas,
                a.verifikasi            AS verifikasi,
                a.transfer              AS transfer,
                a.resep                 AS resep,
                a.tglverifikasi         AS tanggalVerifikasi,
                a.tgltransfer           AS tanggalTransfer,
                a.operator              AS operator,
                a.tglbuat               AS tanggalBuat,
                a.signa1                AS signa1,
                a.signa2                AS signa2,
                a.signa3                AS signa3,
                a.dokter_perobat        AS dokterPerObat,
                a.bayar                 AS bayar,
                a.tglbayar              AS tanggalBayar,
                a.checking_ketersediaan AS cekKetersediaan,
                a.keteranganobat        AS keteranganObat,
                a.kode_drperobat        AS kodeDokterPerObat,
                a.kode_operator         AS kodeOperator,
                a.kode_verifikasi       AS kodeVerifikasi,
                a.kode_transfer         AS kodeTransfer,
                d.nama                  AS namaSigna
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode=a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id=b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode=a.signa
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep=a.no_resep
            WHERE
                a.no_resep = :noResep
                AND a.kode_racik = ''
            ORDER BY a.no_resep DESC
        ";
        $params = [":noResep" => $noResep];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                  AS kode,
                a.no_resep              AS noResep,
                a.no_penjualan          AS noPenjualan,
                a.diskon                AS diskon,
                a.jasa                  AS jasa,
                a.kodePenjualan         AS kodePenjualan,
                a.kode_rm               AS kodeRekamMedis,
                a.no_daftar             AS noPendaftaran,
                a.nama_pasien           AS namaPasien,
                a.kodeObat              AS kodeObat,
                a.kodeObatdr            AS kodeObatDokter,
                a.nama_obatdr           AS namaObatDokter,
                a.urutan                AS urutan,
                a.jlhPenjualan          AS jumlahPenjualan,
                a.jlhPenjualandr        AS jumlahPenjualanDokter,
                a.signa                 AS signa,
                a.hna                   AS hna,
                a.hp                    AS hp,
                a.harga                 AS harga,
                a.id_racik              AS idRacik,
                a.kode_racik            AS kodeRacik,
                a.nama_racik            AS namaRacik,
                a.no_racik              AS noRacik,
                a.ketjumlah             AS keteranganJumlah,
                a.keterangan_obat       AS keteranganObat,
                a.kode_depo             AS kodeDepo,
                a.ranap                 AS rawatInap,
                a.tglPenjualan          AS tanggalPenjualan,
                a.lunas                 AS lunas,
                a.verifikasi            AS verifikasi,
                a.transfer              AS transfer,
                a.resep                 AS resep,
                a.tglverifikasi         AS tanggalVerifikasi,
                a.tgltransfer           AS tanggalTransfer,
                a.operator              AS operator,
                a.tglbuat               AS tanggalBuat,
                a.signa1                AS signa1,
                a.signa2                AS signa2,
                a.signa3                AS signa3,
                a.dokter_perobat        AS dokterPerObat,
                a.bayar                 AS bayar,
                a.tglbayar              AS tanggalBayar,
                a.checking_ketersediaan AS cekKetersediaan,
                a.keteranganobat        AS keteranganObat,
                a.kode_drperobat        AS kodeDokterPerObat,
                a.kode_operator         AS kodeOperator,
                a.kode_verifikasi       AS kodeVerifikasi,
                a.kode_transfer         AS kodeTransfer,
                d.nama                  AS namaSigna
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            WHERE
                kode_racik != ''
                AND a.kodePenjualan = :noResep
            ORDER BY a.kode ASC
        ";
        $params = [":noResep" => $noResep];
        $daftarRacik = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "kodePenjualan" => $noResep,
            "idDepo" => Yii::$app->userFatma->idDepo,
            "resep" => $resep,
            "daftarObat" => $daftarObat,
            "daftarRacik" => $daftarRacik,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/returndepo.php#cetak    the original method
     */
    public function actionCetak(): string
    {
        [   "submit" => $submit,
            "no_resep" => $noResep,
            "kode_obat" => $daftarKodeObat,
            "hargajual" => $daftarHargaJual,
            "qty" => $daftarJumlah,
            "no_rm" => $noRekamMedis,
            "no_daftar" => $noPendaftaran,
            "nama" => $namaPasien,
            "kodePenjualansebelumnya" => $kodePenjualanSebelumnya,
        ] = Yii::$app->request->post();
        $namaUser = Yii::$app->userFatma->nama;
        $idDepo = Yii::$app->userFatma->idDepo;
        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");

        if ($submit) {
            $this->actionBridging5([
                "fungsi" => "hapus_TBIL_TAGIHAN_LYN2",
                "NO_RESEP" => $noResep
            ]);

            $connection = Yii::$app->dbFatma;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT total
                FROM db1.masterf_penjualandetail
                WHERE no_resep = :noResep
                LIMIT 1
            ";
            $params = [":noResep" => $noResep];
            $total = $connection->createCommand($sql, $params)->queryScalar();

            if ($total >= 0) {
                foreach ($daftarKodeObat as $key => $kodeObat) {
                    $harga1 = $daftarHargaJual[$key];
                    $jumlah = abs($daftarJumlah[$key]) * (- 1);
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT -- all are in use, no view file.
                            b.hna_item AS hnaItem,
                            b.hp_item  AS hpItem
                        FROM db1.masterf_katalog AS a
                        LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                        WHERE
                            a.kode = :kode
                            AND (b.sts_hja != 0 OR b.sts_hjapb != 0)
                        LIMIT 1
                    ";
                    $params = [":kode" => $kodeObat];
                    $harga2 = $connection->createCommand($sql, $params)->queryOne();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.masterf_penjualan
                        SET
                            kode_rm = :kodeRekamMedis,
                            no_daftar = :noPendaftaran,
                            nama_pasien = :namaPasien,
                            harga = :harga,
                            jlhPenjualan = :jumlahPenjualan,
                            kodePenjualan = :kodePenjualan,
                            kodeObat = :kodeObat,
                            tglPenjualan = :tanggalPenjualan,
                            kode_depo = :kodeDepo,
                            verifikasi = :verifikasi,
                            operator = :operator,
                            no_resep = :noResep,
                            hna = :hna,
                            hp = :hp
                    ";
                    $params = [
                        ":kodeRekamMedis" => $noRekamMedis,
                        ":noPendaftaran" => $noPendaftaran,
                        ":namaPasien" => $namaPasien,
                        ":harga" => $harga1,
                        ":jumlahPenjualan" => $jumlah,
                        ":kodePenjualan" => $kodePenjualanSebelumnya,
                        ":kodeObat" => $kodeObat,
                        ":tanggalPenjualan" => $todayValSystem,
                        ":kodeDepo" => $idDepo,
                        ":verifikasi" => $namaUser,
                        ":operator" => $namaUser,
                        ":noResep" => $noResep,
                        ":hna" => $harga2->hnaItem,
                        ":hp" => $harga2->hpItem,
                    ];
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
                    $jumlahTersediaBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT c.kode_stokopname
                        FROM db1.relasif_ketersediaan AS c
                        INNER JOIN db1.transaksif_stokopname AS a ON a.kode = c.kode_stokopname
                        INNER JOIN db1.masterf_aktifasiso AS b ON b.kode = a.kode_reff
                        WHERE
                            c.id_depo = :idDepo
                            AND c.id_katalog = :idKatalog
                            AND b.status = 1
                        LIMIT 1
                    ";
                    $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                    $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar() ?? "";

                    $jumlah2 = abs($jumlah);
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.relasif_ketersediaan
                        SET
                            id_depo = :idDepo,
                            kode_reff = :kodeRef,
                            kode_stokopname = :kodeStokopname,
                            kode_transaksi = 'R',
                            kode_store = '000000',
                            tipe_tersedia = 'penjualan',
                            tgl_tersedia = :tanggalTersedia,
                            no_batch = '-',
                            tgl_expired = :tanggalKadaluarsa,
                            id_katalog = :idKatalog,
                            harga_item = :hargaItem,
                            harga_perolehan = :hargaPerolehan,
                            jumlah_masuk = :jumlahMasuk,
                            jumlah_keluar = 0,
                            jumlah_tersedia = :jumlahTersedia,
                            status = 1,
                            keterangan = :keterangan
                    ";
                    $params = [
                        ":idDepo" => $idDepo,
                        ":kodeRef" => $noResep,
                        ":kodeStokopname" => $kodeStokOpname,
                        ":tanggalTersedia" => $nowValSystem,
                        ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                        ":idKatalog" => $kodeObat,
                        ":hargaItem" => $harga1,
                        ":hargaPerolehan" => $harga2->hpItem,
                        ":jumlahMasuk" => $jumlah2,
                        ":jumlahTersedia" => $jumlahTersediaBefore + $jumlah2,
                        ":keterangan" => "Retur pemakaian pasien $namaPasien",
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
                    ";
                    $params = [":jumlah" => $jumlah, ":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                    $connection->createCommand($sql, $params)->execute();
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.masterf_penjualandetail
                    SET
                        jasapelayanan = 0,
                        total = 0
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();
            }
        }

        return $noResep;
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/returndepo.php#bridging5    the original method
     */
    private function actionBridging5(array $data): array
    {
        $url = "http://202.137.25.13:5555/bridging/Bridging_latihan.php";
        $dataString = "";
        foreach ($data as $key => $value) {
            $dataString .= $key . '=' . $value . '&';
        }

        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($post, CURLOPT_HEADER, 0);
        $result = curl_exec($post);
        curl_close($post);

        return json_decode($result);
    }
}
