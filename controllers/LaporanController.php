<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\Laporan\{
    LaporanHarian2TakTerlayani,
    LaporanHarian2TakTerlayani0,
    LaporanIki2,
    LaporanIki2Irna,
    LaporanRekapHarian2TakTerlayani,
    PrintRekapitulasiSetoranHarian2
};
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
class LaporanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#index    the original method
     */
    public function actionTable(): string
    {
        return $this->renderPartial("_table");
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception {@see \tlm\his\FatmaPharmacy\controllers\LaporanTakTerlayaniController::actionReportRekapTakTerlayani}    the replacement
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#print_rekap_harian_tdkterlayani    the original method
     */
    public function actionPrintRekapHarianTdkTerlayani(): string
    {
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir, "idDepo" => $idDepo] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $sysTanggalAwal = $toSystemDate($tanggalAwal);
        $sysTanggalAkhir = $toSystemDate($tanggalAkhir);
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kodeItem         AS kodeItem,
                c.nama_barang      AS namaBarang,
                SUM(a.jumlah1)     AS sumJumlah1,
                SUM(a.jumlah2)     AS sumJumlah2,
                g.kelompok_barang  AS namaKelompok,
                i.jumlah_stokfisik AS gudang,
                j.jumlah_stokfisik AS irj1,
                k.jumlah_stokfisik AS irj2,
                l.jumlah_stokfisik AS irj3,
                m.jumlah_stokfisik AS igh,
                n.jumlah_stokfisik AS ibs,
                o.jumlah_stokfisik AS igd,
                p.jumlah_stokfisik AS produksi,
                q.jumlah_stokfisik AS teratai,
                r.jumlah_stokfisik AS bougenvil
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_kelompokbarang AS g ON g.id = c.id_kelompokbarang
            LEFT JOIN db1.transaksif_stokkatalog AS i on i.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS j on j.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS k on k.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS l on l.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS m on m.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS n on n.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS o on o.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS p on p.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS q on q.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS r on r.id_katalog = a.kodeItem
            WHERE
                (
                    (
                        a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir
                        AND a.noPermintaan LIKE 'M%'
                        AND a.noPengeluaran != ''
                        AND a.jumlah2 < a.jumlah1
                    ) OR (
                        a.checking_pengiriman = 0
                        AND a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir
                        AND a.jumlah2 < a.jumlah1
                    )
                )
                AND i.id_depo = 59
                AND j.id_depo = 23
                AND k.id_depo = 64
                AND l.id_depo = 61
                AND m.id_depo = 25
                AND n.id_depo = 26
                AND o.id_depo = 27
                AND p.id_depo = 28
                AND q.id_depo = 30
                AND r.id_depo = 129
            GROUP BY c.nama_barang
            ORDER BY c.id_kelompokbarang, c.nama_barang
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":tanggalAwal" => $sysTanggalAwal . " 00:00:00",
            ":tanggalAkhir" => $sysTanggalAkhir . " 23:59:59",
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        $view = new LaporanRekapHarian2TakTerlayani(
            namaDepo:         Yii::$app->userFatma->namaDepo,
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
            daftarPeringatan: $daftarPeringatan,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#iki2    the original method
     */
    public function actionIki2(): string
    {
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir, "idDepo" => $idDepo] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT namaDepo
            FROM db1.masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idDepo];
        $namaDepo = (string) $connection->createCommand($sql, $params)->queryScalar() ?? "Semua Depo";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                                                          AS kode,
                a.no_resep                                                      AS noResep,
                a.no_penjualan                                                  AS noPenjualan,
                a.diskon                                                        AS diskon,
                a.jasa                                                          AS jasa,
                a.kodePenjualan                                                 AS kodePenjualan,
                a.kode_rm                                                       AS kodeRekamMedis,
                a.no_daftar                                                     AS noPendaftaran,
                a.nama_pasien                                                   AS namaPasien,
                a.kodeObat                                                      AS kodeObat,
                a.kodeObatdr                                                    AS kodeObatDokter,
                a.nama_obatdr                                                   AS namaObatDokter,
                a.urutan                                                        AS urutan,
                a.jlhPenjualan                                                  AS jumlahPenjualan,
                a.jlhPenjualandr                                                AS jumlahPenjualanDokter,
                a.signa                                                         AS signa,
                a.hna                                                           AS hna,
                a.hp                                                            AS hp,
                a.harga                                                         AS harga,
                a.id_racik                                                      AS idRacik,
                a.kode_racik                                                    AS kodeRacik,
                a.nama_racik                                                    AS namaRacik,
                a.no_racik                                                      AS noRacik,
                a.ketjumlah                                                     AS keteranganJumlah,
                a.keterangan_obat                                               AS keteranganObat,
                a.kode_depo                                                     AS kodeDepo,
                a.ranap                                                         AS rawatInap,
                a.tglPenjualan                                                  AS tanggalPenjualan,
                a.lunas                                                         AS lunas,
                a.verifikasi                                                    AS verifikasi,
                a.transfer                                                      AS transfer,
                a.resep                                                         AS resep,
                a.tglverifikasi                                                 AS tanggalVerifikasi,
                a.tgltransfer                                                   AS tanggalTransfer,
                a.operator                                                      AS operator,
                a.tglbuat                                                       AS tanggalBuat,
                a.signa1                                                        AS signa1,
                a.signa2                                                        AS signa2,
                a.signa3                                                        AS signa3,
                a.dokter_perobat                                                AS dokterPerObat,
                a.bayar                                                         AS bayar,
                a.tglbayar                                                      AS tanggalBayar,
                a.checking_ketersediaan                                         AS cekKetersediaan,
                a.keteranganobat                                                AS keteranganObat,
                a.kode_drperobat                                                AS kodeDokterPerObat,
                a.kode_operator                                                 AS kodeOperator,
                a.kode_verifikasi                                               AS kodeVerifikasi,
                a.kode_transfer                                                 AS kodeTransfer,
                SUM(IF((c.formularium_nas = 1 OR (c.formularium_rs = 1 AND c.formularium_nas = 1)), 1, 0)) AS fornas,
                SUM(IF((c.formularium_rs = 1 AND c.formularium_nas = 0), 1, 0)) AS frs,
                SUM(IF((c.formularium_rs = 0 AND c.formularium_nas = 0), 1, 0)) AS nonFornas,
                c.id_jenisbarang                                                AS idJenisBarang
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kode_depo
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeObatdr
            LEFT JOIN db1.masterf_penjualandetail AS d ON d.no_resep = a.no_resep
            LEFT JOIN db1.mmas_penerima AS f ON f.nm_penerima = d.dokter
            LEFT JOIN db1.master_smf AS g ON g.kodesmf = f.kd_smf
            WHERE
                ((:idDepo = '' AND b.id NOT IN (68, 320, 321)) OR b.id = :idDepo)
                AND a.tglverifikasi >= :tanggalAwal
                AND a.tglverifikasi <= :tanggalAkhir
                AND c.id_jenisbarang IN (8, 1, 9, 10, 11, 16, 17, 18, 21, 22, 23, 25, 26, 28)
                AND d.dokter != ''
                AND f.sts_aktif = 1
            GROUP BY d.dokter
            ORDER BY g.nama_smf, d.dokter ASC
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59"
        ];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $view = new LaporanIki2(
            namaDepo:        $namaDepo,
            tanggalAwal:     $tanggalAwal,
            tanggalAkhir:    $tanggalAkhir,
            daftarPenjualan: $daftarPenjualan,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#iki2irna    the original method
     */
    public function actionIki2Irna(): string
    {
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir, "idDepo" => $idDepo] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT namaDepo
            FROM db1.masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idDepo];
        $namaDepo = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. " 
            SELECT
                a.kode                                                          AS kode,
                a.no_resep                                                      AS noResep,
                a.no_penjualan                                                  AS noPenjualan,
                a.diskon                                                        AS diskon,
                a.jasa                                                          AS jasa,
                a.kodePenjualan                                                 AS kodePenjualan,
                a.kode_rm                                                       AS kodeRekamMedis,
                a.no_daftar                                                     AS noPendaftaran,
                a.nama_pasien                                                   AS namaPasien,
                a.kodeObat                                                      AS kodeObat,
                a.kodeObatdr                                                    AS kodeObatDokter,
                a.nama_obatdr                                                   AS namaObatDokter,
                a.urutan                                                        AS urutan,
                a.jlhPenjualan                                                  AS jumlahPenjualan,
                a.jlhPenjualandr                                                AS jumlahPenjualanDokter,
                a.signa                                                         AS signa,
                a.hna                                                           AS hna,
                a.hp                                                            AS hp,
                a.harga                                                         AS harga,
                a.id_racik                                                      AS idRacik,
                a.kode_racik                                                    AS kodeRacik,
                a.nama_racik                                                    AS namaRacik,
                a.no_racik                                                      AS noRacik,
                a.ketjumlah                                                     AS keteranganJumlah,
                a.keterangan_obat                                               AS keteranganObat,
                a.kode_depo                                                     AS kodeDepo,
                a.ranap                                                         AS rawatInap,
                a.tglPenjualan                                                  AS tanggalPenjualan,
                a.lunas                                                         AS lunas,
                a.verifikasi                                                    AS verifikasi,
                a.transfer                                                      AS transfer,
                a.resep                                                         AS resep,
                a.tglverifikasi                                                 AS tanggalVerifikasi,
                a.tgltransfer                                                   AS tanggalTransfer,
                a.operator                                                      AS operator,
                a.tglbuat                                                       AS tanggalBuat,
                a.signa1                                                        AS signa1,
                a.signa2                                                        AS signa2,
                a.signa3                                                        AS signa3,
                a.dokter_perobat                                                AS dokterPerObat,
                a.bayar                                                         AS bayar,
                a.tglbayar                                                      AS tanggalBayar,
                a.checking_ketersediaan                                         AS cekKetersediaan,
                a.keteranganobat                                                AS keteranganObat,
                a.kode_drperobat                                                AS kodeDokterPerObat,
                a.kode_operator                                                 AS kodeOperator,
                a.kode_verifikasi                                               AS kodeVerifikasi,
                a.kode_transfer                                                 AS kodeTransfer,
                SUM(IF((c.formularium_rs = 1 OR c.formularium_nas = 1), 1, 0))  AS fornas,
                SUM(IF((c.formularium_rs = 0 AND c.formularium_nas = 0), 1, 0)) AS nonFornas,
                c.id_jenisbarang                                                AS idJenisBarang
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kode_depo
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeObatdr
            WHERE
                (:idDepo = '' OR b.id = :idDepo)
                AND a.tglverifikasi >= :tanggalAwal
                AND a.tglverifikasi <= :tanggalAkhir
                AND c.id_jenisbarang IN ('08', 16)
                AND a.dokter_perobat != ''
            GROUP BY a.dokter_perobat
            ORDER BY a.dokter_perobat ASC
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $view = new LaporanIki2Irna(namaDepo: $namaDepo, daftarPenjualan: $daftarPenjualan);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#kasir2    the original method
     */
    public function actionKasir2(): string
    {
        ["fromdate" => $tanggalAwal, "enddate" => $tanggalAkhir] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;
        $namaUser = Yii::$app->userFatma->username;

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
                a.no_daftar             AS noDaftar,
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
                a.kode_transfer         AS kodeTransfer
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            LEFT JOIN db1.masterf_pembayaran AS d ON d.id_cara = c.CARA_PEMBAYARAN
            LEFT JOIN db1.masterf_pembayaran_detail AS e ON e.id_pembayarandetail = c.CARA_PEMBAYARAN_DETAIL
            WHERE
                a.bayar = :namaUser
                AND a.tglbayar >= :tanggalAwal
                AND a.tglbayar <= :tanggalAkhir
                AND a.bayar != ''
                AND e.id_cara = d.id_cara
            GROUP BY a.no_resep
            ORDER BY
                CASE
                    WHEN c.jenisResep IN ('Pembelian Bebas', 'Pembelian Langsung') THEN 2
                    WHEN c.jenisResep = 'Unit Emergensi' THEN 1
                    ELSE 0
                END DESC
        ";
        $params = [
            ":namaUser" => $namaUser,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $view = new PrintRekapitulasiSetoranHarian2(namaUser: $namaUser, daftarPenjualan: $daftarPenjualan, tanggalAwal: $tanggalAwal);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception {@see \tlm\his\FatmaPharmacy\controllers\LaporanTakTerlayaniController::actionReportTakTerlayani}    the replacement
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#PrintHarianTdkTerlayani    the original method (not exist)
     */
    public function actionPrintHarianTdkTerlayani(): string
    {
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir, "idDepo" => $idDepo] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc           AS noDokumen,
                b.namaDepo         AS namaDepo,
                a.kodeItem         AS kodeItem,
                c.nama_barang      AS namaBarang,
                a.jumlah1          AS jumlah1,
                a.jumlah2          AS jumlah2,
                g.kelompok_barang  AS namaKelompok,
                h.jumlah_stokfisik AS stokMinta,
                i.jumlah_stokfisik AS stokBeri
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            LEFT JOIN db1.masterf_depo AS j ON j.kode = a.kodeDepo
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_kelompokbarang AS g ON g.id = c.id_kelompokbarang
            LEFT JOIN db1.transaksif_stokkatalog AS h ON h.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS i ON i.id_katalog = a.kodeItem
            WHERE
                (
                    (
                        a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir
                        AND a.noPermintaan LIKE 'M%'
                        AND a.noPengeluaran != ''
                        AND a.jumlah2 < a.jumlah1
                    )  OR (
                        a.checking_pengiriman = 0
                        AND a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir
                        AND a.jumlah2 < a.jumlah1
                    )
                )
                AND h.id_depo = b.id
                AND i.id_depo = j.id
            ORDER BY c.id_kelompokbarang, c.nama_barang
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        $view = new LaporanHarian2TakTerlayani(
            depo:             Yii::$app->userFatma,
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
            daftarPeringatan: $daftarPeringatan,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception {@see \tlm\his\FatmaPharmacy\controllers\LaporanTakTerlayaniController::actionReportTakTerlayani0}    the replacement
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#PrintHarianTdkTerlayani0    the original method (not exist)
     */
    public function actionPrintHarianTdkTerlayani0(): string
    {
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir, "idDepo" => $idDepo] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc           AS noDokumen,
                b.namaDepo         AS namaDepo,
                a.kodeItem         AS kodeItem,
                c.nama_barang      AS namaBarang,
                SUM(a.jumlah1)     AS sumJumlah1,
                SUM(a.jumlah2)     AS sumJumlah2,
                g.kelompok_barang  AS namaKelompok,
                h.jumlah_stokfisik AS stokMinta,
                i.jumlah_stokfisik AS stokBeri
            FROM db1.master_warning AS a
            LEFT JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            LEFT JOIN db1.masterf_depo AS j ON j.kode = a.kodeDepo
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_kelompokbarang AS g ON g.id = c.id_kelompokbarang
            LEFT JOIN db1.transaksif_stokkatalog AS h ON h.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS i ON i.id_katalog = a.kodeItem
            WHERE
                (
                    (
                        a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir
                        AND a.noPermintaan LIKE 'M%'
                        AND a.noPengeluaran != ''
                        AND a.jumlah2 = 0
                    ) OR (
                        a.checking_pengiriman = 0
                        AND a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir
                        AND a.jumlah2 = 0
                    )
                )
                AND h.id_depo = b.id
                AND i.id_depo = j.id
            GROUP BY
                a.depoPeminta,
                c.nama_barang,
                a.noPermintaan
            ORDER BY c.id_kelompokbarang, c.nama_barang
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        $view = new LaporanHarian2TakTerlayani0(
            depo:             Yii::$app->userFatma,
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
            daftarPeringatan: $daftarPeringatan,
        );
        return $view->__toString();
    }
}
