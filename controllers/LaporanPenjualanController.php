<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\LaporanPenjualan\{ReportPenjualan, ReportTanpaHarga};
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
class LaporanPenjualanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#depo2    the original method
     * last exist of actionDepo2: commit-e37d34f4
     */
    public function actionReportPenjualan(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "idDepo" => $idDepo,
            "idRuangRawat" => $kodeRuangRawat,
        ] = Yii::$app->request->post();
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
        $namaDepo = $connection->createCommand($sql, $params)->queryScalar() ?? "Semua Depo";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                                                        AS kode,
                a.no_resep                                                    AS noResep,
                a.no_penjualan                                                AS noPenjualan,
                a.diskon                                                      AS diskon,
                a.jasa                                                        AS jasa,
                a.kodePenjualan                                               AS kodePenjualan,
                a.kode_rm                                                     AS kodeRekamMedis,
                a.no_daftar                                                   AS noPendaftaran,
                a.nama_pasien                                                 AS namaPasien,
                a.kodeObat                                                    AS kodeObat,
                a.kodeObatdr                                                  AS kodeObatDokter,
                a.nama_obatdr                                                 AS namaObatDokter,
                a.urutan                                                      AS urutan,
                a.jlhPenjualan                                                AS jumlahPenjualan,
                a.jlhPenjualandr                                              AS jumlahPenjualanDokter,
                a.signa                                                       AS signa,
                a.hna                                                         AS hna,
                a.hp                                                          AS hp,
                a.harga                                                       AS harga,
                a.id_racik                                                    AS idRacik,
                a.kode_racik                                                  AS kodeRacik,
                a.nama_racik                                                  AS namaRacik,
                a.no_racik                                                    AS noRacik,
                a.ketjumlah                                                   AS keteranganJumlah,
                a.keterangan_obat                                             AS keteranganObat,
                a.kode_depo                                                   AS kodeDepo,
                a.ranap                                                       AS rawatInap,
                a.tglPenjualan                                                AS tanggalPenjualan,
                a.lunas                                                       AS lunas,
                a.verifikasi                                                  AS verifikasi,
                a.transfer                                                    AS transfer,
                a.resep                                                       AS resep,
                a.tglverifikasi                                               AS tanggalVerifikasi,
                a.tgltransfer                                                 AS tanggalTransfer,
                a.operator                                                    AS operator,
                a.tglbuat                                                     AS tanggalBuat,
                a.signa1                                                      AS signa1,
                a.signa2                                                      AS signa2,
                a.signa3                                                      AS signa3,
                a.dokter_perobat                                              AS dokterPerObat,
                a.bayar                                                       AS bayar,
                a.tglbayar                                                    AS tanggalBayar,
                a.checking_ketersediaan                                       AS cekKetersediaan,
                a.keteranganobat                                              AS keteranganObat,
                a.kode_drperobat                                              AS kodeDokterPerObat,
                a.kode_operator                                               AS kodeOperator,
                a.kode_verifikasi                                             AS kodeVerifikasi,
                a.kode_transfer                                               AS kodeTransfer,
                g.jenis_obat                                                  AS namaJenis,         -- in use
                c.id_jenisbarang                                              AS kodeJenis,         -- in use
                d.nama_pabrik                                                 AS namaPabrik,
                c.id_kelompokbarang                                           AS kodeKelompok,      -- in use
                kp.kelompok_barang                                            AS kelompokBarang,    -- in use
                SUM(a.jlhPenjualan)                                           AS totalJumlah,       -- in use
                ROUND(SUM(a.jlhPenjualan * a.hp), 2) / SUM(a.jlhPenjualan)    AS hpItem,            -- in use
                ROUND(SUM(a.jlhPenjualan * a.harga), 2) / SUM(a.jlhPenjualan) AS hjaSetting         -- in use
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kode_depo
            LEFT JOIN db1.masterf_katalog AS c ON c.kode = a.kodeObat
            LEFT JOIN db1.masterf_pabrik AS d ON d.id = c.id_pabrik
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = c.id_jenisbarang
            LEFT JOIN db1.masterf_kelompokbarang AS kp ON c.id_kelompokbarang = kp.id
            WHERE
                (:idDepo = '' OR a.kode_depo = :idDepo)
                AND (:kodeRuangRawat = '' OR mp.KD_RRAWAT = :kodeRuangRawat)  -- ERROR! caused by 'mp'
                AND tglPenjualan >= :tanggalAwal
                AND tglPenjualan <= :tanggalAkhir
                AND verifikasi != ''
            GROUP BY c.nama_barang
            ORDER BY g.jenis_obat, c.kode, c.nama_barang
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":kodeRuangRawat" => $kodeRuangRawat,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarPenjualan) return "tidak ada data";

        $daftarHalaman = [];
        $grandTotalJumlah =  0;
        $grandTotalNilai = 0;
        $grandTotalLaba = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $barisPerHalaman = 28;
        $kodeJenisSaatIni = "";
        $kodeKelompokSaatIni = "";

        foreach ($daftarPenjualan as $i => $penjualan) {
            if ($kodeJenisSaatIni != $penjualan->kodeJenis) {
                $kodeJenisSaatIni = $penjualan->kodeJenis;
                $kodeKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis" => $penjualan->namaJenis ?? "Lain - Lain",
                    "total_jumlah" => 0,
                    "total_nilai" => 0,
                    "total_laba" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($kodeKelompokSaatIni != $penjualan->kodeKelompok) {
                $kodeKelompokSaatIni = $penjualan->kodeKelompok;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok" => $penjualan->kelompokBarang,
                    "subtotal_jumlah" => 0,
                    "subtotal_nilai" => 0,
                    "subtotal_laba" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $nilai = $penjualan->totalJumlah * $penjualan->hjaSetting;
            $laba = $nilai - ($penjualan->totalJumlah * $penjualan->hpItem);

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
                "nilai" => $nilai,
                "laba" => $laba,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_jumlah"] += $penjualan->totalJumlah;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_nilai"] += $nilai;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_laba"] += $laba;

            $daftarHalaman[$hJudul1][$bJudul1]["total_jumlah"] += $penjualan->totalJumlah;
            $daftarHalaman[$hJudul1][$bJudul1]["total_nilai"] += $nilai;
            $daftarHalaman[$hJudul1][$bJudul1]["total_laba"] += $laba;

            $grandTotalJumlah += $penjualan->totalJumlah;
            $grandTotalNilai += $nilai;
            $grandTotalLaba += $laba;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportPenjualan(
            daftarHalaman:    $daftarHalaman,
            namaDepo:         $namaDepo,
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
            daftarPenjualan:  $daftarPenjualan,
            jumlahHalaman:    count($daftarHalaman),
            grandTotalJumlah: $grandTotalJumlah,
            grandTotalNilai:  $grandTotalNilai,
            grandTotalLaba:   $grandTotalLaba,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#depojualnew    the original method
     * last exist of actionDepoJualNew: commit-e37d34f4
     */
    public function actionReportTanpaHarga(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "idDepo" => $idDepo,
            "idRuang" => $kodeRuangRawat,
        ] = Yii::$app->request->post();
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
        $namaDepo = $connection->createCommand($sql, $params)->queryScalar() ?? "Semua Depo";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_RRAWAT
            FROM db1.masterf_kode_rrawat
            WHERE kd_rrawat = :kodeRuangRawat
        ";
        $params = [":kodeRuangRawat" => $kodeRuangRawat];
        $namaRuangRawat = (string) $connection->createCommand($sql, $params)->queryScalar() ?? "Semua Ruang Rawat";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id_katalog     AS kodeObat,
                SUM(a.totaljlh)  AS totalJumlah,    -- in use
                g.jenis_obat     AS namaJenis,      -- in use
                c.id_jenisbarang AS kodeJenis,      -- in use
                c.nama_barang    AS namaBarang,
                e.nama_pabrik    AS namaPabrik,
                a.id_depo        AS idDepo,
                a.tgl_tersedia   AS tanggalTersedia
            FROM (
                SELECT
                    id_katalog                        AS id_katalog,
                    SUM(jumlah_keluar - jumlah_masuk) AS totaljlh,
                    id_depo                           AS id_depo,
                    tgl_tersedia                      AS tgl_tersedia
                FROM db1.relasif_ketersediaan
                WHERE
                    tipe_tersedia = 'penjualan'
                    AND kode_transaksi = 'R'
                    AND status = 1
                    AND tgl_tersedia >= :tanggalAwal
                    AND tgl_tersedia <= :tanggalAkhir
                    AND (:idDepo = '' OR id_depo = :idDepo)
                    AND (:kodeRuangRawat = '' OR mp.KD_RRAWAT = :kodeRuangRawat)
                GROUP BY
                    kode_reff,
                    id_katalog
            ) AS a
            LEFT JOIN db1.masterf_katalog AS c ON c.kode = a.id_katalog
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = c.id_pabrik
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = c.id_jenisbarang
            GROUP BY
                a.id_katalog,
                c.nama_barang
            ORDER BY g.jenis_obat, c.kode, c.nama_barang
        ";
        $params = [
            ":idDepo" => $idDepo,
            ":kodeRuangRawat" => $kodeRuangRawat,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarKetersediaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotal = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 49;
        $kodeJenisSaatIni = "";

        foreach ($daftarKetersediaan as $i => $ketersediaan) {
            if ($kodeJenisSaatIni != $ketersediaan->kodeJenis) {
                $kodeJenisSaatIni = $ketersediaan->kodeJenis;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_jenis" => $ketersediaan->namaJenis,
                    "total" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_jumlah"] += $ketersediaan->totalJumlah;
            $grandTotal += $ketersediaan->totalJumlah;

            if ($b > $barisPerHalaman) {
                $b++;
            } else {
                $h++;
                $b = 0;
            }
        }

        $view = new ReportTanpaHarga(
            daftarHalaman:      $daftarHalaman,
            namaDepo:           $namaDepo,
            namaRuangRawat:     $namaRuangRawat,
            tanggalAwal:        $tanggalAwal,
            tanggalAkhir:       $tanggalAkhir,
            daftarKetersediaan: $daftarKetersediaan,
            jumlahHalaman:      count($daftarHalaman),
            grandTotal:         $grandTotal,
        );
        return $view->__toString();
    }
}
