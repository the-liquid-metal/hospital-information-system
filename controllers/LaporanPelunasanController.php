<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\LaporanPelunasan\ReportPelunasan;
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
class LaporanPelunasanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#pelunasan2    the original method
     * last exist of actionPelunasan2: commit-e37d34f4
     * last exist of pelunasanWithPrinterLx300: commit-e37d34f4
     */
    public function actionReportPelunasan(): string
    {
        [   "idDepo" => $idDepo,
            "idRuangRawat" => $kodeRuangRawat,
            "tanggalAwal" => $dariTanggal,
            "tanggalAkhir" => $sampaiTanggal
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_RRAWAT
            FROM db1.masterf_kode_rrawat
            WHERE kd_rrawat = :kodeRuangRawat
            LIMIT 1
        ";
        $params = [":kodeRuangRawat" => $kodeRuangRawat];
        $namaRuangRawat = (string) $connection->createCommand($sql, $params)->queryScalar() ?? "Semua Ruangan";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT namaDepo
            FROM db1.masterf_depo
            WHERE id = :idDepo
            LIMIT 1
        ";
        $params = [":idDepo" => $idDepo];
        $namaDepo = (string) $connection->createCommand($sql, $params)->queryScalar() ?? "Semua Depo";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.nama_pasien                                                           AS namaPasien,
                a.kode_rm                                                               AS kodeRekamMedis,
                a.no_resep                                                              AS noResep,
                b.namaPoli                                                              AS namaPoli,
                a.tglPenjualan                                                          AS tanggalPenjualan,
                CASE
                    WHEN b.jenisResep = 'Pembelian Bebas' THEN 'Pembelian Langsung / Bebas'
                    WHEN b.jenisResep = 'Pembelian Langsung' THEN 'Pembelian Langsung / Bebas'
                    WHEN b.jenisResep = 'Rawat Jalan' AND b.pembayaran = 'tunai' THEN 'Pembelian Langsung / Bebas'
                    WHEN b.jenisResep = 'Rawat Inap' AND b.pembayaran = 'tunai' THEN  IF(d.nm_rrawat IS NULL OR d.nm_rrawat = 'null' OR d.nm_rrawat = '', 'Pembelian Langsung / Bebas', d.nm_rrawat)
                    WHEN b.jenisResep = 'Unit Emergensi' THEN 'Unit Emergensi'
                    WHEN b.jenisResep = 'Rawat Inap' THEN  IF(d.nm_rrawat IS NULL OR d.nm_rrawat = 'null' OR d.nm_rrawat = '', b.jenisResep, d.nm_rrawat)
                    ELSE b.jenisResep
                END                                                                     AS jenisResep,
                b.pembayaran                                                            AS caraBayar,
                b.jns_carabayar                                                         AS statusBayar,
                a.bayar                                                                 AS bayarUser,
                b.total                                                                 AS total,
                ROUND(b.totaldiskon, 2)                                                 AS diskon,
                FLOOR(b.jasapelayanan / 100) * 100                                      AS jasaPelayanan,
                b.jasapelayanan - (FLOOR(b.jasapelayanan / 100) * 100)                  AS pembulatan,
                ROUND(b.total + b.totaldiskon - b.totalpembungkus - b.jasapelayanan, 2) AS hargaJual,
                b.kd_rrawat                                                             AS kodeRuangRawat,
                d.nm_rrawat                                                             AS namaRuangRawat
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_penjualandetail AS b ON a.no_resep = b.no_resep
            INNER JOIN db1.masterf_depo AS c ON a.kode_depo = c.kode
            LEFT JOIN db1.masterf_kode_rrawat AS d ON b.kd_rrawat = d.kd_rrawat
            WHERE
                (:kodeRuangRawat = '' OR b.KD_RRAWAT = :kodeRuangRawat)
                AND (:idDepo = '' OR c.id = :idDepo)
                AND a.tglPenjualan >= :dariTanggal
                AND a.tglPenjualan <= :sampaiTanggal
                -- IF (b.pembayaran = 'tunai', a.bayar, a.verifikasi) != ''
                AND a.verifikasi != ''
            GROUP BY a.no_resep
            ORDER BY
                jenisResep,
                no_resep ASC
        ";
        $params = [
            ":kodeRuangRawat" => $kodeRuangRawat,
            ":idDepo" => $idDepo,
            ":dariTanggal" => $toSystemDate($dariTanggal),
            ":sampaiTanggal" => $toSystemDate($sampaiTanggal),
        ];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $daftarPenjualan2 = [];
        foreach ($daftarPenjualan as $penjualan) {
            $daftarPenjualan2[$penjualan->jenisResep][] = $penjualan;
        }

        $daftarSemuaHalaman = [];
        $daftarSemuaSubtotal = [];
        $daftarSemuaTotal = [];
        $daftarJenisResep = [];
        $grandTotalHargaJual = 0;
        $grandTotalDiskon = 0;
        $grandTotalJasaPelayanan = 0;
        $grandTotalPembulatan = 0;
        $grandTotalTotal = 0;

        foreach ($daftarPenjualan2 as $jenisResep => $dataJenisResep) {
            $daftarJenisResep[] = $jenisResep;
            $daftarHalaman = [];
            $daftarTotal = [];

            $subtotalHargaJual = 0;
            $subtotalDiskon = 0;
            $subtotalJasaPelayanan = 0;
            $subtotalPembulatan = 0;
            $subtotalTotal = 0;

            $h = 0; // index halaman
            $b = 0; // index baris
            $posisi = 0;
            $barisPerHalaman = 30;
            $butuhBaris = 2;

            foreach ($dataJenisResep as $r) {
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
                $params = [":noResep" => $r->no_resep];
                $jumlahRacik = $connection->createCommand($sql, $params)->queryScalar();

                if (strtotime($r->tglPenjualan) >= strtotime("2016-10-01")) {
                    $racikantotal = $jumlahRacik * 1000;
                    $pembelianBebas = 500;
                } else {
                    $racikantotal = $jumlahRacik * 500;
                    $pembelianBebas = 300;
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        (SUM(b.jasapelayanan) + $racikantotal) AS totaljp,
                        SUM(b.totalharga)                      AS totaljual
                    FROM (
                        SELECT
                            CASE
                                WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                                WHEN a.total > 0 AND (a.id_racik = '' OR a.id_racik = 0) AND jenisResep != 'Pembelian Bebas' THEN $pembelianBebas
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
                            FROM db1.masterf_penjualan mp
                            INNER JOIN db1.masterf_penjualandetail mpd ON mpd.no_resep = mp.no_resep
                            WHERE mp.no_resep = :noResep
                            GROUP BY kodeObat
                            ORDER BY kodeObat ASC
                        ) AS a
                    ) AS b
                    GROUP BY b.no_resep
                ";
                $params = [":noResep" => $r->no_resep];
                $getjp = $connection->createCommand($sql, $params)->queryOne();

                $r->harga_jual = $getjp->totaljual;
                $r["jasa_pelayanan"] = $getjp->totaljp;
                $r->total = ceil(($getjp->totaljual + $getjp->totaljp - $r->diskon) / 100) * 100;
                $daftarHalaman[$h][$b] = $r;

                $caraBayar = $r->cara_bayar;
                $daftarTotal[$caraBayar] ??= [
                    "hargaJual" => 0,
                    "diskon" => 0,
                    "jasaPelayanan" => 0,
                    "pembulatan" => 0,
                    "total" => 0,
                ];

                $daftarTotal[$caraBayar]["hargaJual"] += $r->harga_jual;
                $daftarTotal[$caraBayar]["diskon"] += $r->diskon;
                $daftarTotal[$caraBayar]["jasaPelayanan"] += $r->jasa_pelayanan;
                $daftarTotal[$caraBayar]["pembulatan"] += $r->pembulatan;
                $daftarTotal[$caraBayar]["total"] += $r->total;

                $subtotalHargaJual += $r->harga_jual;
                $subtotalDiskon += $r->diskon;
                $subtotalJasaPelayanan += $r->jasa_pelayanan;
                $subtotalPembulatan += $r->pembulatan;
                $subtotalTotal += $r->total;

                $grandTotalHargaJual += $r->harga_jual;
                $grandTotalDiskon += $r->diskon;
                $grandTotalJasaPelayanan += $r->jasa_pelayanan;
                $grandTotalPembulatan += $r->pembulatan;
                $grandTotalTotal += $r->total;

                if ($posisi > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                    $posisi = 0;
                } elseif (($posisi + $butuhBaris) > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                    $posisi = 0;
                } else {
                    $b++;
                    $posisi++;
                }
            }

            $daftarSemuaHalaman[$jenisResep] = $daftarHalaman;
            $daftarSemuaSubtotal[$jenisResep] = [
                "hargaJual" => $subtotalHargaJual,
                "diskon" => $subtotalDiskon,
                "jasaPelayanan" => $subtotalJasaPelayanan,
                "pembulatan" => $subtotalPembulatan,
                "total" => $subtotalTotal,
            ];
            $daftarSemuaTotal[$jenisResep] = $daftarTotal;
        }

        $view = new ReportPelunasan(
            namaDepo:                $namaDepo,
            dariTanggal:             $dariTanggal,
            sampaiTanggal:           $sampaiTanggal,
            namaRuangRawat:          $namaRuangRawat,
            daftarJenisResep:        $daftarJenisResep,
            daftarSemuaHalaman:      $daftarSemuaHalaman,
            daftarSemuaSubtotal:     $daftarSemuaSubtotal,
            daftarSemuaTotal:        $daftarSemuaTotal,
            grandTotalHargaJual:     $grandTotalHargaJual,
            grandTotalDiskon:        $grandTotalDiskon,
            grandTotalJasaPelayanan: $grandTotalJasaPelayanan,
            grandTotalPembulatan:    $grandTotalPembulatan,
            grandTotalTotal:         $grandTotalTotal,
        );
        return $view->__toString();
    }
}
