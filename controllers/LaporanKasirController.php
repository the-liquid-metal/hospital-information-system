<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use stdClass;
use tlm\his\FatmaPharmacy\views\LaporanKasir\ReportKasir;
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
class LaporanKasirController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#kasir2    the original method
     */
    public function actionReportKasir(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        ["tanggal" => $tanggal, "kasir" => $kasir] = Yii::$app->request->post();
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
                f.namaDepo              AS namaDepo
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            LEFT JOIN db1.masterf_pembayaran AS d ON d.id_cara = c.CARA_PEMBAYARAN
            LEFT JOIN db1.masterf_pembayaran_detail AS e ON e.id_pembayarandetail = c.CARA_PEMBAYARAN_DETAIL
            LEFT JOIN db1.masterf_depo AS f on f.kode = a.kode_depo
            WHERE
                a.bayar = :nama
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
            ":nama" => $kasir,
            ":tanggalAwal" => $toSystemDate($tanggal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggal) . " 23:59:59",
        ];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $daftarPenjualan2 = [];
        $daftarSubtotalJenisResep = [];
        $daftarSubtotalCaraBayar = [];
        $totalHargaJual = 0;
        $totalDiskon = 0;
        $totalJasaPelayanan = 0;
        $totalPembulatan = 0;
        $totalJumlah = 0;
        $temp = [];

        foreach ($daftarPenjualan as $i => $penjualan) {
            $data1 = $daftarPenjualan[$i-1] ?? new stdClass;  // data sebelumnya
            $data2 = $daftarPenjualan[$i-2] ?? new stdClass;  // data sesudahnya

            if ($penjualan->cara == "" || $penjualan->cara == "Cash") {
                $penjualan->cara = "Cash";
                $penjualan->NM_BANK = "";
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT no_resep
                FROM db1.masterf_penjualan
                WHERE
                    no_resep = :noResep
                    AND id_racik != ''
                    AND id_racik != 0
                GROUP BY id_racik
            ";
            $params = [":noResep" => $penjualan->no_resep];
            $daftarResepRacik = $connection->createCommand($sql, $params)->queryAll();

            if (strtotime($penjualan->tglPenjualan) >= strtotime("2016-10-01")) {
                $racikantotal = count($daftarResepRacik) * 1000;
                $pembelianBebas = 500;
            } else {
                $racikantotal = count($daftarResepRacik) * 500;
                $pembelianBebas = 300;
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT
                    SUM(b.jasapelayanan) + :racikanTotal AS totalJasaPelayanan,
                    SUM(b.totalharga)                    AS totalJual,
                    totaldiskon                          AS totalDiskon
                FROM (
                    SELECT
                        CASE
                            WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                            WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN :pembelianBebas
                            WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                            ELSE 0
                        END AS jasapelayanan,
                        a.totalharga,
                        a.no_resep,
                        a.totaldiskon
                    FROM (
                        SELECT
                            SUM(jlhPenjualan)         AS total,
                            id_racik                  AS id_racik,
                            SUM(jlhPenjualan * harga) AS totalharga,
                            mp.no_resep               AS no_resep,
                            jenisResep                AS jenisResep,
                            mpd.totaldiskon           AS totaldiskon
                        FROM db1.masterf_penjualan mp
                        INNER JOIN db1.masterf_penjualandetail mpd ON mpd.no_resep = mp.no_resep
                        WHERE mp.no_resep = :noResep
                        GROUP BY kodeObat
                        ORDER BY kodeObat ASC
                    ) AS a
                ) AS b
                GROUP BY b.no_resep
            ";
            $params = [":racikanTotal" => $racikantotal, ":pembelianBebas" => $pembelianBebas, ":noResep" => $penjualan->no_resep];
            $jasaPelayanan = $connection->createCommand($sql, $params)->queryOne();

            $isHead = $penjualan->jenisResep != $data1->jenisResep;
            $isTail = $penjualan->jenisResep != $data2->jenisResep;

            $head = ($penjualan->jenisResep == "Pembelian Bebas" || $penjualan->jenisResep == "Pembelian Langsung") ? "Pembelian Bebas" : "Ruang: {$penjualan->namaDepo}";

            $caraBayar = $penjualan->cara . " " . $penjualan->NM_BANK;
            $hargaJual = $jasaPelayanan->totalJual + $penjualan->totalpembungkus;
            $diskon = $jasaPelayanan->totalDiskon;
            $floorJasaPelayanan = floor($jasaPelayanan->totalJasaPelayanan / 100) * 100;
            $pembulatan = $jasaPelayanan->totalJasaPelayanan - $floorJasaPelayanan;
            $jumlah = $jasaPelayanan->totalJual - $jasaPelayanan->totalDiskon + $penjualan->totalPembungkus + $jasaPelayanan->totalJasaPelayanan;

            $daftarPenjualan2[$i] = [
                "head" => $isHead ? $head : "",
                "isHead" => $isHead,
                "isTail" => $isTail,
                "noPembayaran" => $penjualan->no_resep,
                "kodeRekamMedis" => $penjualan->no_resep ."/". $penjualan->kode_rm  ."/". $penjualan->nama_pasien,
                "caraBayar" => $caraBayar,
                "hargaJual" => $hargaJual,
                "diskon" => $diskon,
                "jasaPelayanan" => $floorJasaPelayanan,
                "pembulatan" => $pembulatan,
                "jumlah" => $jumlah,
            ];

            $temp["hargaJual"] += $hargaJual;
            $temp["diskon"] += $diskon;
            $temp["jasaPelayanan"] += $floorJasaPelayanan;
            $temp["pembulatan"] += $pembulatan;
            $temp["jumlah"] += $jumlah;

            if ($isTail) {
                $daftarSubtotalJenisResep[$i] = [...$temp];
                $temp = [];
            }

            $daftarSubtotalCaraBayar[$caraBayar]["hargaJual"] ??= 0;
            $daftarSubtotalCaraBayar[$caraBayar]["hargaJual"] += $hargaJual;
            $daftarSubtotalCaraBayar[$caraBayar]["diskon"] ??= 0;
            $daftarSubtotalCaraBayar[$caraBayar]["diskondiskon"] += $diskon;
            $daftarSubtotalCaraBayar[$caraBayar]["jasaPelayanan"] ??= 0;
            $daftarSubtotalCaraBayar[$caraBayar]["jasaPelayanan"] += $floorJasaPelayanan;
            $daftarSubtotalCaraBayar[$caraBayar]["pembulatan"] ??= 0;
            $daftarSubtotalCaraBayar[$caraBayar]["pembulatan"] += $pembulatan;
            $daftarSubtotalCaraBayar[$caraBayar]["jumlah"] ??= 0;
            $daftarSubtotalCaraBayar[$caraBayar]["jumlah"] += $jumlah;

            $totalHargaJual += $hargaJual;
            $totalDiskon += $diskon;
            $totalJasaPelayanan += $floorJasaPelayanan;
            $totalPembulatan += $pembulatan;
            $totalJumlah += $jumlah;
        }
        $view = new ReportKasir(
            username:                 $kasir,
            daftarPenjualan:          $daftarPenjualan2, // truely correct assignment: $daftarPenjualan2
            daftarSubtotalJenisResep: $daftarSubtotalJenisResep,
            daftarSubtotalCaraBayar:  $daftarSubtotalCaraBayar,
            totalHargaJual:           $totalHargaJual,
            totalDiskon:              $totalDiskon,
            totalJasaPelayanan:       $totalJasaPelayanan,
            totalPembulatan:          $totalPembulatan,
            totalJumlah:              $totalJumlah,
            tanggalAwal:              $tanggal,
        );
        return $view->__toString();
    }
}
