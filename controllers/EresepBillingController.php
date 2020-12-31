<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\EresepBilling\{StrukKasir, StrukKasirBri};
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
class EresepBillingController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepbilling.php#editbayar    the original method
     */
    public function actionFormData(): string
    {
        assert($_POST["noResep"], new MissingPostParamException("noResep"));
        $noResep = Yii::$app->request->post("noResep");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kodePenjualan          AS kodePenjualanSebelumnya,
                e.KD_BAYAR               AS kodeBayar,
                e.KD_INST                AS kodeInstalasi,
                e.KD_POLI                AS kodePoli,
                e.KD_JENIS_CARABAYAR     AS kodeJenisCaraBayar,
                e.KD_RRAWAT              AS kodeRuangRawat,
                a.kode_rm                AS kodeRekamMedis,
                d.no_antrian             AS noAntrian,
                a.nama_pasien            AS namaPasien,
                b.jenis_kelamin          AS kelamin,
                b.tanggal_lahir          AS tanggalLahir,
                b.alamat_jalan           AS alamat,
                b.no_telpon              AS noTelefon,
                e.tglResep1              AS tanggalAwalResep,
                e.tglResep2              AS tanggalAkhirResep,
                e.jenisResep             AS jenisResep,
                e.dokter                 AS dokter,
                e.namaInstansi           AS namaInstalasi,
                e.CARA_PEMBAYARAN        AS pembayaran,
                e.CARA_PEMBAYARAN_DETAIL AS detailPembayaran,
                e.NOMOR_KARTU            AS noKartu,
                e.namaPoli               AS namaPoli,
                e.totaldiskon            AS totalDiskon,
                e.jasapelayanan          AS jasaPelayanan,
                e.total                  AS grandTotal,
                a.no_resep               AS noResep,
                e.pembayaran             AS caraBayar,
                a.no_daftar              AS noPendaftaran,
                NULL                     AS daftarObat
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
            SELECT -- all are in use.
                b.nama_barang  AS namaObat,
                a.kodeObat     AS kodeObat,
                a.jlhPenjualan AS kuantitas,
                a.id_racik     AS idRacik,
                a.signa1       AS namaSigna1,
                a.signa2       AS namaSigna2,
                a.signa3       AS namaSigna3,
                c.nama_kemasan AS namaKemasan,
                a.harga        AS hargaJual
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            WHERE
                a.no_resep = :noResep
                AND a.kode_racik = ''
            ORDER BY a.no_resep DESC
        ";
        $params = [":noResep" => $noResep];
        $resep->daftarObat = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($resep);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepbilling.php#caridetail    the original method
     */
    public function actionCariDetail(): string
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_pembayarandetail AS idPembayaranDetail,
                NM_BANK             AS namaBank
            FROM db1.masterf_pembayaran_detail
            WHERE id_cara = :idCara
        ";
        $params = [":idCara" => $_POST["q"]];
        $daftarDetailPembayaran = $connection->createCommand($sql, $params)->queryAll();

        $html = "";
        foreach ($daftarDetailPembayaran as $dp) {
            $html .= "<option value = '" . $dp->idPembayaranDetail . "'>" . $dp->namaBank . "</option>";
        }
        return json_encode($html);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepbilling.php#cetak    the original method
     */
    public function actionCetak(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? new MissingPostParamException("noResep");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualandetail
            SET
                bayar = :bayar,
                kembali = :kembali,
                cara_pembayaran = :caraPembayaran,
                cara_pembayaran_detail = :caraPembayaranDetail,
                nomor_kartu = :nomorKartu,
                atasnama = :atasNama
            WHERE no_resep = :noResep
        ";
        $params = [
            ":bayar" => $_POST["bayar"],
            ":kembali" => $_POST["kembali"],
            ":caraPembayaran" => $_POST["CARA_PEMBAYARAN"],
            ":caraPembayaranDetail" => $_POST["CARA_PEMBAYARAN_DETAIL"],
            ":nomorKartu" => $_POST["NOMOR_KARTU"],
            ":atasNama" => $_POST["atasnama"],
            ":noResep" => $noResep,
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualan
            SET
                bayar = :bayar,
                tglbayar = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":bayar" => Yii::$app->userFatma->username, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        return $noResep;
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepbilling.php#listresep    the original method
     */
    public function actionTableData(): string
    {
        [   "dariTanggal" => $tanggalAwal,
            "sampaiTanggal" => $tanggalAkhir,
            "idDepo" => $idDepo,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noResep" => $noResep,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode
            FROM db1.masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idDepo];
        $kodeDepo = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $kodeRekamMedis = $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : $kodeRekamMedis;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.no_resep                    AS noResep,
                a.kode_rm                     AS kodeRekamMedis,
                a.nama_pasien                 AS namaPasien,
                a.tglPenjualan                AS tanggalPenjualan,
                a.bayar                       AS bayar,
                a.verifikasi                  AS verifikasi,
                a.transfer                    AS transfer,
                c.totaldiskon                 AS totalDiskon,
                ''                            AS totalCeil
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            WHERE
                a.no_resep != ''
                AND (:kodeDepo = '' OR a.kode_depo = :kodeDepo)
                AND (:kodeRekamMedis = '' OR a.kode_rm = :kodeRekamMedis)
                AND (:noResep = '' OR a.no_resep = :noResep)
                AND a.tglPenjualan >= :tanggalAwal
                AND a.tglPenjualan <= :tanggalAkhir
            GROUP BY a.no_resep
            ORDER BY a.tglPenjualan DESC
        ";
        $params = [
            ":kodeDepo" => $kodeDepo,
            ":kodeRekamMedis" => $kodeRekamMedis,
            ":noResep" => $noResep,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep as $i => $resep) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*)
                FROM db1.masterf_penjualan
                WHERE
                    no_resep = :noResep
                    AND id_racik != ''
                    AND id_racik != 0
                GROUP BY id_racik
            ";
            $params = [":noResep" => $resep->noResep];
            $jumlahPenjualan = $connection->createCommand($sql, $params)->queryScalar();
            $totalRacikan = $jumlahPenjualan * 1000;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    SUM(b.jasapelayanan)+ :totalRacikan AS totalJasaPelayanan,
                    SUM(b.totalharga)                   AS totalJual
                FROM (
                    SELECT
                        CASE
                            WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                            WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 500
                            WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                            ELSE 0
                        END AS jasapelayanan,
                        a.totalharga,
                        a.no_resep
                    FROM (
                        SELECT
                            SUM(jlhPenjualan)         AS total,
                            id_racik                  AS id_racik,
                            SUM(jlhPenjualan * harga) AS totalharga,
                            mp.no_resep               AS no_resep,
                            jenisResep                AS jenisResep
                        FROM db1.masterf_penjualan AS mp
                        INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                        WHERE mp.no_resep = :noResep
                        GROUP BY kodeObat
                        ORDER BY kodeObat ASC
                    ) AS a
                ) AS b
                GROUP BY b.no_resep
            ";
            $params = [":totalRacikan" => $totalRacikan, ":noResep" => $resep->noResep];
            $penjualan = $connection->createCommand($sql, $params)->queryOne();

            $resep->totalCeil = ceil(($penjualan->totalJual + $penjualan->totalJasaPelayanan - $resep->totalDiskon) / 100) * 100;
        }

        return json_encode(["daftarResep" => $daftarResep, "total" => count($daftarResep)]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepbilling.php#cetakstruk    the original method
     */
    public function actionCetakStruk(): string
    {
        $noResep = Yii::$app->request->post("id");
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.atasnama    AS atasNama,
                b.nama_pasien AS namaPasien,
                a.no_resep    AS noResep,
                b.kode_rm     AS kodeRekamMedis,
                a.totaldiskon AS totalDiskon
            FROM db1.masterf_penjualandetail AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                name     AS nama,
                username AS namaUser
            FROM db1.user
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idUser];
        $user = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND id_racik != ''
                AND id_racik != 0
            GROUP BY id_racik
        ";
        $params = [":noResep" => $noResep];
        $jumlahPenjualan = $connection->createCommand($sql, $params)->queryScalar();
        $totalRacikan = $jumlahPenjualan * 1000;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                SUM(b.totalharga)                    AS totalJual
            FROM (
                SELECT
                    CASE
                        WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 500
                        WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                        ELSE 0
                    END AS jasapelayanan,
                    a.totalharga,
                    a.no_resep
                FROM (
                    SELECT
                        SUM(jlhPenjualan)         AS total,
                        id_racik                  AS id_racik,
                        SUM(jlhPenjualan * harga) AS totalharga,
                        mp.no_resep               AS no_resep,
                        jenisResep                AS jenisResep
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                    WHERE mp.no_resep = :noResep
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.no_resep
        ";
        $params = [":totalRacikan" => $totalRacikan, ":noResep" => $noResep];
        $penjualan = $connection->createCommand($sql, $params)->queryOne();

        $totalCeil = ceil(($penjualan->totalJual + $penjualan->totalJasaPelayanan - $pasien->totalDiskon) / 100) * 100;

        $view = new StrukKasir(pasien: $pasien, totalCeil: $totalCeil, user: $user);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepbilling.php#cetakstrukbri    the original method
     */
    public function actionCetakStrukBri(): string
    {
        $noResep = Yii::$app->request->post("id");
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.nama_pasien AS namaPasien,
                a.atasnama    AS atasNama,
                a.no_resep    AS noResep,
                b.kode_rm     AS kodeRekamMedis,
                a.totaldiskon AS totalDiskon
            FROM db1.masterf_penjualandetail AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                name     AS nama,
                username AS namaUser
            FROM db1.user
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idUser];
        $user = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND id_racik != ''
                AND id_racik != 0
            GROUP BY id_racik
        ";
        $params = [":noResep" => $noResep];
        $jumlahPenjualan = $connection->createCommand($sql, $params)->queryScalar();
        $totalRacikan = $jumlahPenjualan * 1000;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                SUM(b.totalharga)                    AS totalJual
            FROM (
                SELECT
                    CASE
                        WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 500
                        WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                        ELSE 0
                    END AS jasapelayanan,
                    a.totalharga,
                    a.no_resep
                FROM (
                    SELECT
                        SUM(jlhPenjualan)         AS total,
                        id_racik                  AS id_racik,
                        SUM(jlhPenjualan * harga) AS totalharga,
                        mp.no_resep               AS no_resep,
                        jenisResep                AS jenisResep
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                    WHERE mp.no_resep = :noResep
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.no_resep
        ";
        $params = [":totalRacikan" => $totalRacikan, ":noResep" => $noResep];
        $penjualan = $connection->createCommand($sql, $params)->queryOne();

        $totalCeil = ceil(($penjualan->totalJual + $penjualan->totalJasaPelayanan - $pasien->totalDiskon) / 100) * 100;

        $view = new StrukKasirBri(pasien: $pasien, totalCeil: $totalCeil, user: $user);
        return $view->__toString();
    }
}
