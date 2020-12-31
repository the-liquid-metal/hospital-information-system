<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\LaporanGenerik\ReportLaporan;
use tlm\libs\LowEnd\components\DateTimeException;
use tlm\libs\LowEnd\controllers\BaseController;
use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 * @see - (none)
 */
class LaporanGenerikController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#generikrawat2    the original method
     * last exist of actionGenerikRawat2: commit-e37d34f4
     */
    public function actionReportLaporan(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;
        [   "idDepo" => $idDepo,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir
        ] = Yii::$app->request->post();

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
                a.kode                                                            AS kode,
                a.no_resep                                                        AS noResep,
                a.no_penjualan                                                    AS noPenjualan,
                a.diskon                                                          AS diskon,
                a.jasa                                                            AS jasa,
                a.kodePenjualan                                                   AS kodePenjualan,
                a.kode_rm                                                         AS kodeRekamMedis,
                a.no_daftar                                                       AS noPendaftaran,
                a.nama_pasien                                                     AS namaPasien,
                a.kodeObat                                                        AS kodeObat,
                a.kodeObatdr                                                      AS kodeObatDokter,
                a.nama_obatdr                                                     AS namaObatDokter,
                a.urutan                                                          AS urutan,
                a.jlhPenjualan                                                    AS jumlahPenjualan,
                a.jlhPenjualandr                                                  AS jumlahPenjualanDokter,
                a.signa                                                           AS signa,
                a.hna                                                             AS hna,
                a.hp                                                              AS hp,
                a.harga                                                           AS harga,
                a.id_racik                                                        AS idRacik,
                a.kode_racik                                                      AS kodeRacik,
                a.nama_racik                                                      AS namaRacik,
                a.no_racik                                                        AS noRacik,
                a.ketjumlah                                                       AS keteranganJumlah,
                a.keterangan_obat                                                 AS keteranganObat,
                a.kode_depo                                                       AS kodeDepo,
                a.ranap                                                           AS rawatInap,
                a.tglPenjualan                                                    AS tanggalPenjualan,
                a.lunas                                                           AS lunas,
                a.verifikasi                                                      AS verifikasi,
                a.transfer                                                        AS transfer,
                a.resep                                                           AS resep,
                a.tglverifikasi                                                   AS tanggalVerifikasi,
                a.tgltransfer                                                     AS tanggalTransfer,
                a.operator                                                        AS operator,
                a.tglbuat                                                         AS tanggalBuat,
                a.signa1                                                          AS signa1,
                a.signa2                                                          AS signa2,
                a.signa3                                                          AS signa3,
                a.dokter_perobat                                                  AS dokterPerObat,
                a.bayar                                                           AS bayar,
                a.tglbayar                                                        AS tanggalBayar,
                a.checking_ketersediaan                                           AS cekKetersediaan,
                a.keteranganobat                                                  AS keteranganObat,
                a.kode_drperobat                                                  AS kodeDokterPerObat,
                a.kode_operator                                                   AS kodeOperator,
                a.kode_verifikasi                                                 AS kodeVerifikasi,
                a.kode_transfer                                                   AS kodeTransfer,
                SUM(IF(a.id_racik = 0 OR a.id_racik = '', 1, 0))                  AS obatJadi,               -- in use
                SUM(DISTINCT IF(a.id_racik > 0, 1, 0))                            AS obatRacik,              -- in use
                SUM(IF(c.generik = 1, 1, 0))                                      AS generik,                -- in use
                SUM(IF(c.generik = 0, 1, 0))                                      AS nonGenerik,             -- in use
                SUM(IF(c.kode IN (
                    '14A003', '14A008', '14a012', '14d001', '14d003', '14e001',
                    '14e002', '14h001', '14l002', '14l003', '14l006', '14n002',
                    '14n003', '14r001', '14s001', '14t003', '14z001'), 1, 0))     AS arv,                    -- in use
                SUM(IF(c.kode IN ('14f001', '14f002', '14f005'), 1, 0))           AS fdc,                    -- in use
                SUM(IF(c.kode IN ('14m007', '14m008', '14m009', '14m010'), 1, 0)) AS mdt,                    -- in use
                SUM(IF((c.formularium_rs = 1 OR c.formularium_nas = 1), 1, 0))    AS fornas,                 -- in use
                SUM(IF((c.formularium_rs = 0 AND c.formularium_nas = 0), 1, 0))   AS nonFornas,              -- in use
                c.id_jenisbarang
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kode_depo
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeObat
            LEFT JOIN db1.masterf_penjualandetail AS d ON d.no_resep = a.no_resep
            LEFT JOIN db1.masterf_kode_rrawat AS e ON e.kd_rrawat = d.kd_rrawat
            WHERE
                (:idDepo = '' OR b.id = :idDepo)
                AND a.tglverifikasi >= :tanggalAwal
                AND a.tglverifikasi <= :tanggalAkhir
            GROUP BY a.no_resep
            ORDER BY
                e.nm_rrawat,
                d.jenisResep,
                a.no_resep ASC
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $sumObatJadi = 0;
        $sumObatRacik = 0;
        $sumGenerik = 0;
        $sumNonGenerik = 0;
        $sumFornas = 0;
        $sumNonFornas = 0;
        $sumArv = 0;
        $sumFdc = 0;
        $sumMdt = 0;

        foreach ($daftarPenjualan as $penjualan) {
            $sumObatJadi += $penjualan->obatJadi;
            $sumObatRacik += $penjualan->obatRacik;
            $sumGenerik += $penjualan->generik;
            $sumNonGenerik += $penjualan->nonGenerik;
            $sumFornas += $penjualan->fornas;
            $sumNonFornas += $penjualan->nonFornas;
            $sumArv += $penjualan->arv;
            $sumFdc += $penjualan->fdc;
            $sumMdt += $penjualan->mdt;
        }

        $view = new ReportLaporan(
            namaDepo:        $namaDepo,
            tanggalAwal:     $tanggalAwal,
            tanggalAkhir:    $tanggalAkhir,
            daftarPenjualan: $daftarPenjualan,
            obj1:            $sumObatJadi,
            obj2:            $sumObatRacik,
            obj3:            $sumGenerik,
            obj4:            $sumNonGenerik,
            obj5:            $sumFornas,
            obj6:            $sumNonFornas,
            obj7:            $sumArv,
            obj8:            $sumFdc,
            obj9:            $sumMdt,
            all2:            $sumObatJadi + $sumObatRacik,
            all3:            $sumGenerik + $sumNonGenerik,
            all4:            $sumFornas + $sumNonFornas,
        );
        return $view->__toString();
    }
}
