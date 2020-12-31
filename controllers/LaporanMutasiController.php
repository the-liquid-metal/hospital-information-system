<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use Jaspersoft\Client\Client as JasperClient;
use tlm\his\FatmaPharmacy\views\LaporanMutasi\{
    ReportBulanJenisKelompok,
    ReportBulanKatalogDetailJenisKelompok,
    ReportBulanKatalogDetailKelompok,
    ReportBulanKatalogJenisKelompok,
    ReportBulanKatalogKelompok,
    ReportBulanKelompok,
    ReportTriwulanDefault,
    ReportTriwulanKatalogDetailJenisKelompok,
    ReportTriwulanKatalogDetailKelompok,
    ReportTriwulanKatalogJenisKelompok,
    ReportTriwulanKatalogKelompok,
    ReportTriwulanKelompok
};
use tlm\libs\LowEnd\components\{DateTimeException, DateTimeRangeException};
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
class LaporanMutasiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasi    the original method
     * last exist of actionLaporanWebBulanKatalogDetailJenisKelompok: commit-e37d34f4
     */
    public function actionReportBulanKatalogDetailJenisKelompok(): string
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog            AS idKatalog,
                nama_barang           AS namaBarang,
                id_jenisbarang        AS idJenisBarang,        -- in use
                kode_jenis            AS kodeJenis,
                nama_jenis            AS namaJenis,            -- in use
                id_kelompokbarang     AS idKelompokBarang,     -- in use
                kode_kelompok         AS kodeKelompok,
                nama_kelompok         AS namaKelompok,         -- in use
                jumlah_awal           AS jumlahAwal,
                harga_awal            AS hargaAwal,
                nilai_awal            AS nilaiAwal,            -- in use
                jumlah_pembelian      AS jumlahPembelian,
                nilai_pembelian       AS nilaiPembelian,       -- in use
                jumlah_hasilproduksi  AS jumlahHasilProduksi,
                nilai_hasilproduksi   AS nilaiHasilProduksi,   -- in use
                jumlah_koreksi        AS jumlahKoreksi,
                nilai_koreksi         AS nilaiKoreksi,         -- in use
                jumlah_penjualan      AS jumlahPenjualan,
                nilai_penjualan       AS nilaiPenjualan,       -- in use
                jumlah_floorstok      AS jumlahFloorstok,
                nilai_floorstok       AS nilaiFloorstok,       -- in use
                jumlah_bahanproduksi  AS jumlahBahanProduksi,
                nilai_bahanproduksi   AS nilaiBahanProduksi,   -- in use
                jumlah_rusak          AS jumlahRusak,
                nilai_rusak           AS nilaiRusak,           -- in use
                jumlah_expired        AS jumlahKadaluarsa,
                nilai_expired         AS nilaiKadaluarsa,      -- in use
                jumlah_returpembelian AS jumlahReturPembelian,
                nilai_returpembelian  AS nilaiReturPembelian,  -- in use
                jumlah_adjustment     AS jumlahAdjustment,
                nilai_adjustment      AS nilaiAdjustment,      -- in use
                jumlah_akhir          AS jumlahAkhir,
                harga_akhir           AS hargaAkhir,
                nilai_akhir           AS nilaiAkhir            -- in use
            FROM db1.laporan_mutasi_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 36;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idJenis = $lMutasi->idJenisBarang;
            $idKelompok = $lMutasi->idKelompokBarang;

            if ($idJenisSaatIni != $idJenis) {
                $idJenisSaatIni = $idJenis;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul2 = $h;
                $bJudul2 = $b;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = ["i" => $i];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportBulanKatalogDetailJenisKelompok(
            bulan:                    Yii::$app->dateTime->numToMonthName($bulan),
            triwulan:                 null,
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasi    the original method
     * last exist of actionLaporanWebBulanKatalogDetailKelompok: commit-e37d34f4
     */
    public function actionReportBulanKatalogDetailKelompok(): string
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog            AS idKatalog,
                nama_barang           AS namaBarang,
                id_kelompokbarang     AS idKelompokBarang,     -- in use
                kode_kelompok         AS kodeKelompok,
                nama_kelompok         AS namaKelompok,         -- in use
                jumlah_awal           AS jumlahAwal,
                harga_awal            AS hargaAwal,
                nilai_awal            AS nilaiAwal,            -- in use
                jumlah_pembelian      AS jumlahPembelian,
                nilai_pembelian       AS nilaiPembelian,       -- in use
                jumlah_hasilproduksi  AS jumlahHasilProduksi,
                nilai_hasilproduksi   AS nilaiHasilProduksi,   -- in use
                jumlah_koreksi        AS jumlahKoreksi,
                nilai_koreksi         AS nilaiKoreksi,         -- in use
                jumlah_penjualan      AS jumlahPenjualan,
                nilai_penjualan       AS nilaiPenjualan,       -- in use
                jumlah_floorstok      AS jumlahFloorstok,
                nilai_floorstok       AS nilaiFloorstok,       -- in use
                jumlah_bahanproduksi  AS jumlahBahanProduksi,
                nilai_bahanproduksi   AS nilaiBahanProduksi,   -- in use
                jumlah_rusak          AS jumlahRusak,
                nilai_rusak           AS nilaiRusak,           -- in use
                jumlah_expired        AS jumlahKadaluarsa,
                nilai_expired         AS nilaiKadaluarsa,      -- in use
                jumlah_returpembelian AS jumlahReturPembelian,
                nilai_returpembelian  AS nilaiReturPembelian,  -- in use
                jumlah_adjustment     AS jumlahAdjustment,
                nilai_adjustment      AS nilaiAdjustment,      -- in use
                jumlah_akhir          AS jumlahAkhir,
                harga_akhir           AS hargaAkhir,
                nilai_akhir           AS nilaiAkhir            -- in use
            FROM db1.laporan_mutasi_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            ORDER BY id_kelompokbarang
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $barisPerHalaman = 36;
        $noJudul = 1;
        $noData = 1;
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idKelompok = $lMutasi->idKelompokBarang;

            //apakah kelompok barang berubah?
            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++,
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
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

            $daftarHalaman[$hJudul][$bJudul]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul][$bJudul]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul][$bJudul]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul][$bJudul]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul][$bJudul]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul][$bJudul]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul][$bJudul]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul][$bJudul]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul][$bJudul]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul][$bJudul]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul][$bJudul]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul][$bJudul]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportBulanKatalogDetailKelompok(
            bulan:                    Yii::$app->dateTime->numToMonthName($bulan),
            triwulan:                 null,
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasi    the original method
     * last exist of actionLaporanWebBulanKatalogJenisKelompok: commit-e37d34f4
     */
    public function actionReportBulanKatalogJenisKelompok(): string
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog           AS idKatalog,
                nama_barang          AS namaBarang,
                id_jenisbarang       AS idJenisBarang,       -- in use
                kode_jenis           AS kodeJenis,           -- in use
                nama_jenis           AS namaJenis,           -- in use
                id_kelompokbarang    AS idKelompokBarang,    -- in use
                kode_kelompok        AS kodeKelompok,        -- in use
                nama_kelompok        AS namaKelompok,        -- in use
                jumlah_awal          AS jumlahAwal,
                harga_awal           AS hargaAwal,
                nilai_awal           AS nilaiAwal,           -- in use
                nilai_pembelian      AS nilaiPembelian,      -- in use
                nilai_hasilproduksi  AS nilaiHasilProduksi,  -- in use
                nilai_koreksi        AS nilaiKoreksi,        -- in use
                nilai_penjualan      AS nilaiPenjualan,      -- in use
                nilai_floorstok      AS nilaiFloorstok,      -- in use
                nilai_bahanproduksi  AS nilaiBahanProduksi,  -- in use
                nilai_rusak          AS nilaiRusak,          -- in use
                nilai_expired        AS nilaiKadaluarsa,     -- in use
                nilai_returpembelian AS nilaiReturPembelian, -- in use
                nilai_adjustment     AS nilaiAdjustment,     -- in use
                jumlah_akhir         AS jumlahAkhir,
                harga_akhir          AS hargaAkhir,
                nilai_akhir          AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 40;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idJenis = $lMutasi->idJenisBarang;
            $idKelompok = $lMutasi->idKelompokBarang;

            if ($idJenisSaatIni != $idJenis) {
                $idJenisSaatIni = $idJenis;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++,
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "kode_jenis" => $lMutasi->kodeJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul2 = $h;
                $bJudul2 = $b;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "row_kelompok_barang" => true,
                    "id_jenisbarang" => $lMutasi->idJenisBarang,
                    "id_kelompokbarang" => $lMutasi->idKelompokBarang,
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "kode_kelompok" => $lMutasi->kodeKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
                // TODO: php: uncategorized: fix these (missing value)
                "jumlah_pembelian" => 0,
                "jumlah_hasilproduksi" => 0,
                "jumlah_koreksi" => 0,
                "jumlah_penjualan" => 0,
                "jumlah_floorstok" => 0,
                "jumlah_bahanproduksi" => 0,
                "jumlah_rusak" => 0,
                "jumlah_expired" => 0,
                "jumlah_returpembelian" => 0,
                "jumlah_adjustment" => 0,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportBulanKatalogJenisKelompok(
            bulan:                    Yii::$app->dateTime->numToMonthName($bulan),
            triwulan:                 null,
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasi    the original method
     * last exist of actionLaporanWebBulanKatalogKelompok: commit-e37d34f4
     */
    public function actionReportBulanKatalogKelompok(): string
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog           AS idKatalog,
                nama_barang          AS namaBarang,
                id_jenisbarang       AS idJenisBarang,       -- in use
                kode_jenis           AS kodeJenis,
                nama_jenis           AS namaJenis,           -- in use
                id_kelompokbarang    AS idKelompokBarang,    -- in use
                kode_kelompok        AS kodeKelompok,
                nama_kelompok        AS namaKelompok,        -- in use
                jumlah_awal          AS jumlahAwal,
                harga_awal           AS hargaAwal,
                nilai_awal           AS nilaiAwal,           -- in use
                nilai_pembelian      AS nilaiPembelian,      -- in use
                nilai_hasilproduksi  AS nilaiHasilProduksi,  -- in use
                nilai_koreksi        AS nilaiKoreksi,        -- in use
                nilai_penjualan      AS nilaiPenjualan,      -- in use
                nilai_floorstok      AS nilaiFloorstok,      -- in use
                nilai_bahanproduksi  AS nilaiBahanProduksi,  -- in use
                nilai_rusak          AS nilaiRusak,          -- in use
                nilai_expired        AS nilaiKadaluarsa,     -- in use
                nilai_returpembelian AS nilaiReturPembelian, -- in use
                nilai_adjustment     AS nilaiAdjustment,     -- in use
                jumlah_akhir         AS jumlahAkhir,
                harga_akhir          AS hargaAkhir,
                nilai_akhir          AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            ORDER BY id_kelompokbarang
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 40;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idJenis = $lMutasi->idJenisBarang;
            $idKelompok = $lMutasi->idKelompokBarang;

            if ($idJenisSaatIni != $idJenis) {
                $idJenisSaatIni = $idJenis;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
                // TODO: php: uncategorized: fix these (missing value)
                "jumlah_pembelian" => 0,
                "jumlah_hasilproduksi" => 0,
                "jumlah_koreksi" => 0,
                "jumlah_penjualan" => 0,
                "jumlah_floorstok" => 0,
                "jumlah_bahanproduksi" => 0,
                "jumlah_rusak" => 0,
                "jumlah_expired" => 0,
                "jumlah_returpembelian" => 0,
                "jumlah_adjustment" => 0,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportBulanKatalogKelompok(
            bulan:                    Yii::$app->dateTime->numToMonthName($bulan),
            triwulan:                 null,
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasi    the original method
     * last exist of actionLaporanWebBulanJenisKelompok: commit-e37d34f4
     */
    public function actionReportBulanJenisKelompok(): string
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_jenisbarang            AS idJenisBarang,          -- in use
                kode_jenis                AS kodeJenis,
                nama_jenis                AS namaJenis,              -- in use
                id_kelompokbarang         AS idKelompokBarang,       -- in use
                kode_kelompok             AS kodeKelompok,
                nama_kelompok             AS namaKelompok,           -- in use
                SUM(nilai_awal)           AS sumNilaiAwal,           -- in use
                SUM(nilai_pembelian)      AS sumNilaiPembelian,      -- in use
                SUM(nilai_hasilproduksi)  AS sumNilaiHasilProduksi,  -- in use
                SUM(nilai_koreksi)        AS sumNilaiKoreksi,        -- in use
                SUM(nilai_penjualan)      AS sumNilaiPenjualan,      -- in use
                SUM(nilai_floorstok)      AS sumNilaiFloorstok,      -- in use
                SUM(nilai_bahanproduksi)  AS sumNilaiBahanProduksi,  -- in use
                SUM(nilai_rusak)          AS sumNilaiRusak,          -- in use
                SUM(nilai_expired)        AS sumNilaiKadaluarsa,     -- in use
                SUM(nilai_returpembelian) AS sumNilaiReturPembelian, -- in use
                SUM(nilai_adjustment)     AS sumNilaiAdjustment,     -- in use
                SUM(nilai_akhir)          AS sumNilaiAkhir           -- in use
            FROM db1.laporan_mutasi_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            GROUP BY
                id_jenisbarang,
                id_kelompokbarang
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 40;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idJenis = $lMutasi->idJenisBarang;
            $idKelompok = $lMutasi->idKelompokBarang;

            //apakah jenis barang berubah?
            if ($idJenisSaatIni != $idJenis) {
                $idJenisSaatIni = $idJenis;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            //apakah kelompok barang berubah?
            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul2 = $h;
                $bJudul2 = $b;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                // TODO: php: uncategorized: fix these (missing value)
                "id_katalog" => 0,
                "nama_barang" => 0,
                "jumlah_awal" => 0,
                "harga_awal" => 0,
                "jumlah_pembelian" => 0,
                "jumlah_hasilproduksi" => 0,
                "jumlah_koreksi" => 0,
                "jumlah_penjualan" => 0,
                "jumlah_floorstok" => 0,
                "jumlah_bahanproduksi" => 0,
                "jumlah_rusak" => 0,
                "jumlah_expired" => 0,
                "jumlah_returpembelian" => 0,
                "jumlah_adjustment" => 0,
                "jumlah_akhir" => 0,
                "harga_akhir" => 0,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->sumNilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->sumNilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->sumNilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->sumNilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->sumNilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->sumNilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->sumNilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->sumNilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->sumNilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->sumNilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->sumNilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->sumNilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->sumNilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->sumNilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->sumNilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->sumNilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->sumNilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->sumNilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->sumNilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->sumNilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->sumNilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->sumNilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->sumNilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->sumNilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->sumNilaiAwal;
            $grandTotalPembelian += $lMutasi->sumNilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->sumNilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->sumNilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->sumNilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->sumNilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->sumNilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->sumNilaiRusak;
            $grandTotalExpired += $lMutasi->sumNilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->sumNilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->sumNilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->sumNilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportBulanJenisKelompok(
            bulan:                    Yii::$app->dateTime->numToMonthName($bulan),
            triwulan:                 null,
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasi    the original method
     * last exist of actionLaporanWebBulanKelompok: commit-e37d34f4
     */
    public function actionReportBulanKelompok(): string
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_kelompokbarang         AS idKelompokBarang,
                kode_kelompok             AS kodeKelompok,
                nama_kelompok             AS namaKelompok,
                SUM(nilai_awal)           AS sumNilaiAwal,
                SUM(nilai_pembelian)      AS sumNilaiPembelian,
                SUM(nilai_hasilproduksi)  AS sumNilaiHasilProduksi,
                SUM(nilai_koreksi)        AS sumNilaiKoreksi,
                SUM(nilai_penjualan)      AS sumNilaiPenjualan,
                SUM(nilai_floorstok)      AS sumNilaiFloorstok,
                SUM(nilai_bahanproduksi)  AS sumNilaiBahanProduksi,
                SUM(nilai_rusak)          AS sumNilaiRusak,
                SUM(nilai_expired)        AS sumNilaiKadaluarsa,
                SUM(nilai_returpembelian) AS sumNilaiReturPembelian,
                SUM(nilai_adjustment)     AS sumNilaiAdjustment,
                SUM(nilai_akhir)          AS sumNilaiAkhir
            FROM db1.laporan_mutasi_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            GROUP BY id_kelompokbarang
            ORDER BY id_kelompokbarang
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarLaporanMutasi) return "tidak ada data";

        $daftarHalaman = [];

        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;
        $no = 1;

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $no++,
            ];

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportBulanKelompok(
            bulan:                    Yii::$app->dateTime->numToMonthName($bulan),
            triwulan:                 null,
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasi    the original method
     * last exist of laporanMutasiData7: commit-e37d34f4
     */
    public function reportBulanElse(): string
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog            AS idKatalog,
                nama_barang           AS namaBarang,
                id_jenisbarang        AS idJenisBarang,
                kode_jenis            AS kodeJenis,
                nama_jenis            AS namaJenis,
                id_kelompokbarang     AS idKelompokBarang,
                kode_kelompok         AS kodeKelompok,
                nama_kelompok         AS namaKelompok,
                jumlah_awal           AS jumlahAwal,
                harga_awal            AS hargaAwal,
                nilai_awal            AS nilaiAwal,            -- in use
                jumlah_pembelian      AS jumlahPembelian,
                nilai_pembelian       AS nilaiPembelian,       -- in use
                jumlah_hasilproduksi  AS jumlahHasilProduksi,
                nilai_hasilproduksi   AS nilaiHasilProduksi,   -- in use
                jumlah_koreksi        AS jumlahKoreksi,
                nilai_koreksi         AS nilaiKoreksi,         -- in use
                jumlah_penjualan      AS jumlahPenjualan,
                nilai_penjualan       AS nilaiPenjualan,       -- in use
                jumlah_floorstok      AS jumlahFloorstok,
                nilai_floorstok       AS nilaiFloorstok,       -- in use
                jumlah_bahanproduksi  AS jumlahBahanProduksi,
                nilai_bahanproduksi   AS nilaiBahanProduksi,   -- in use
                jumlah_rusak          AS jumlahRusak,
                nilai_rusak           AS nilaiRusak,           -- in use
                jumlah_expired        AS jumlahKadaluarsa,
                nilai_expired         AS nilaiKadaluarsa,      -- in use
                jumlah_returpembelian AS jumlahReturPembelian,
                nilai_returpembelian  AS nilaiReturPembelian,  -- in use
                jumlah_adjustment     AS jumlahAdjustment,
                nilai_adjustment      AS nilaiAdjustment,      -- in use
                jumlah_akhir          AS jumlahAkhir,
                harga_akhir           AS hargaAkhir,
                nilai_akhir           AS nilaiAkhir            -- in use
            FROM db1.laporan_mutasi_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;
        $no = 1;

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $no++,
            ];

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportBulanKelompok(
            bulan:                    Yii::$app->dateTime->numToMonthName($bulan),
            triwulan:                 null,
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of laporanMutasiTriwulanPdf: commit-e37d34f4
     */
    private function reportTriwulanPdf(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun, "jenisLaporan" => $jenis] = Yii::$app->request->post();
        $triwulan = (int) $triwulan;
        $tahun = (int) $tahun;

        $parameter = [
            "triwulan" => $triwulan,
            "tahun" => $tahun
        ];

        $c = new JasperClient("http://192.168.3.29:8080/jasperserver", "jasperadmin", "jasperadmin");

        switch ($jenis) {
            case 2: $cetakan = "/reports/Farmasi/lap_mutasi_triwulan_kel"; break;
            case 6: $cetakan = "/reports/Farmasi/lap_mutasi_triwulan_kel_subtotal"; break;
            default:  $cetakan = "";
        }
        $report = $c->reportService()->runReport($cetakan, "pdf", null, null, $parameter);
        return $this->renderPdf($report, "report");
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanWebTriwulanKatalogDetailJenisKelompok: commit-e37d34f4
     */
    public function actionReportWebTriwulanKatalogDetailJenisKelompok(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();
        $triwulan = (int) $triwulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog            AS idKatalog,
                nama_barang           AS namaBarang,
                id_jenisbarang        AS idJenisBarang,        -- in use
                kode_jenis            AS kodeJenis,
                nama_jenis            AS namaJenis,            -- in use
                id_kelompokbarang     AS idKelompokBarang,     -- in use
                kode_kelompok         AS kodeKelompok,
                nama_kelompok         AS namaKelompok,         -- in use
                jumlah_awal           AS jumlahAwal,
                harga_awal            AS hargaAwal,
                nilai_awal            AS nilaiAwal,            -- in use
                jumlah_pembelian      AS jumlahPembelian,
                nilai_pembelian       AS nilaiPembelian,       -- in use
                jumlah_hasilproduksi  AS jumlahHasilProduksi,
                nilai_hasilproduksi   AS nilaiHasilProduksi,   -- in use
                jumlah_koreksi        AS jumlahKoreksi,
                nilai_koreksi         AS nilaiKoreksi,         -- in use
                jumlah_penjualan      AS jumlahPenjualan,
                nilai_penjualan       AS nilaiPenjualan,       -- in use
                jumlah_floorstok      AS jumlahFloorstok,
                nilai_floorstok       AS nilaiFloorstok,       -- in use
                jumlah_bahanproduksi  AS jumlahBahanProduksi,
                nilai_bahanproduksi   AS nilaiBahanProduksi,   -- in use
                jumlah_rusak          AS jumlahRusak,
                nilai_rusak           AS nilaiRusak,           -- in use
                jumlah_expired        AS jumlahKadaluarsa,
                nilai_expired         AS nilaiKadaluarsa,      -- in use
                jumlah_returpembelian AS jumlahReturPembelian,
                nilai_returpembelian  AS nilaiReturPembelian,  -- in use
                jumlah_adjustment     AS jumlahAdjustment,
                nilai_adjustment      AS nilaiAdjustment,      -- in use
                jumlah_akhir          AS jumlahAkhir,
                harga_akhir           AS hargaAkhir,
                nilai_akhir           AS nilaiAkhir            -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 36;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idJenis = $lMutasi->idJenisBarang;
            $idKelompok = $lMutasi->idKelompokBarang;

            if ($idJenisSaatIni != $idJenis) {
                $idJenisSaatIni = $idJenis;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_penjualan" => 0,
                    "total_koreksi" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportTriwulanKatalogDetailJenisKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanWebTriwulanKatalogDetailKelompok: commit-e37d34f4
     */
    public function actionReportWebTriwulanKatalogDetailKelompok(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();
        $triwulan = (int) $triwulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog                             AS idKatalog,
                nama_barang                            AS namaBarang,
                id_kelompokbarang                      AS idKelompokBarang,        -- in use
                kode_kelompok                          AS kodeKelompok,
                nama_kelompok                          AS namaKelompok,            -- in use
                jumlah_awal                            AS jumlahAwal,
                harga_awal                             AS hargaAwal,
                nilai_awal                             AS nilaiAwal,               -- in use
                jumlah_pembelian                       AS jumlahPembelian,         -- in use
                nilai_pembelian                        AS nilaiPembelian,          -- in use
                jumlah_hasilproduksi                   AS jumlahHasilProduksi,
                nilai_hasilproduksi                    AS nilaiHasilProduksi,      -- in use
                jumlah_koreksi                         AS jumlahKoreksi,           -- in use
                nilai_koreksi                          AS nilaiKoreksi,            -- in use
                (jumlah_penjualan - jumlah_adjustment) AS jumlahPenjualan,
                (nilai_penjualan - nilai_adjustment)   AS nilaiPenjualan,
                jumlah_floorstok                       AS jumlahFloorstok,
                nilai_floorstok                        AS nilaiFloorstok,          -- in use
                jumlah_bahanproduksi                   AS jumlahBahanProduksi,
                nilai_bahanproduksi                    AS nilaiBahanProduksi,      -- in use
                jumlah_rusak                           AS jumlahRusak,
                nilai_rusak                            AS nilaiRusak,              -- in use
                jumlah_expired                         AS jumlahKadaluarsa,
                nilai_expired                          AS nilaiKadaluarsa,         -- in use
                jumlah_koreksipenerimaan               AS jumlahKoreksiPenerimaan, -- in use
                nilai_koreksipenerimaan                AS nilaiKoreksiPenerimaan,  -- in use
                jumlah_returpembelian                  AS jumlahReturPembelian,    -- in use
                nilai_returpembelian                   AS nilaiReturPembelian,     -- in use
                jumlah_akhir                           AS jumlahAkhir,
                harga_akhir                            AS hargaAkhir,
                nilai_akhir                            AS nilaiAkhir,              -- in use
                ''                                     AS nilaiPenjualan_,         -- in use
                ''                                     AS jumlahPenjualan_         -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $barisPerHalaman = 29;
        $noJudul = 1;
        $noData = 1;
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idKelompok = $lMutasi->idKelompokBarang;

            // apakah kelompok barang berubah?
            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($lMutasi->jumlahKoreksi > 0) {
                $lMutasi->jumlahPembelian += $lMutasi->jumlahKoreksi;
                $lMutasi->nilaiPembelian += $lMutasi->nilaiKoreksi;
            } else {
                $lMutasi->jumlahPenjualan_ -= $lMutasi->jumlahKoreksi;
                $lMutasi->nilaiPenjualan_ -= $lMutasi->nilaiKoreksi;
            }

            if ($lMutasi->jumlahKoreksiPenerimaan > 0) {
                $lMutasi->jumlahPembelian += $lMutasi->jumlahKoreksiPenerimaan;
                $lMutasi->nilaiPembelian += $lMutasi->nilaiKoreksiPenerimaan;
            } else {
                $lMutasi->jumlahReturPembelian -= $lMutasi->jumlahKoreksiPenerimaan;
                $lMutasi->nilaiReturPembelian -= $lMutasi->nilaiKoreksiPenerimaan;
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul][$bJudul]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul][$bJudul]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul][$bJudul]["total_penjualan"] += $lMutasi->nilaiPenjualan_;
            $daftarHalaman[$hJudul][$bJudul]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul][$bJudul]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul][$bJudul]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul][$bJudul]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul][$bJudul]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul][$bJudul]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan_;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportTriwulanKatalogDetailKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanWebTriwulanKatalogJenisKelompok: commit-e37d34f4
     */
    public function actionReportWebTriwulanKatalogJenisKelompok(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();
        $triwulan = (int) $triwulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog           AS idKatalog,
                nama_barang          AS namaBarang,
                id_jenisbarang       AS idJenisBarang,       -- in use
                kode_jenis           AS kodeJenis,
                nama_jenis           AS namaJenis,           -- in use
                id_kelompokbarang    AS idKelompokBarang,    -- in use
                kode_kelompok        AS kodeKelompok,
                nama_kelompok        AS namaKelompok,        -- in use
                jumlah_awal          AS jumlahAwal,
                harga_awal           AS hargaAwal,
                nilai_awal           AS nilaiAwal,           -- in use
                nilai_pembelian      AS nilaiPembelian,      -- in use
                nilai_hasilproduksi  AS nilaiHasilProduksi,  -- in use
                nilai_koreksi        AS nilaiKoreksi,        -- in use
                nilai_penjualan      AS nilaiPenjualan,      -- in use
                nilai_floorstok      AS nilaiFloorstok,      -- in use
                nilai_bahanproduksi  AS nilaiBahanProduksi,  -- in use
                nilai_rusak          AS nilaiRusak,          -- in use
                nilai_expired        AS nilaiKadaluarsa,     -- in use
                nilai_returpembelian AS nilaiReturPembelian, -- in use
                nilai_adjustment     AS nilaiAdjustment,     -- in use
                jumlah_akhir         AS jumlahAkhir,
                harga_akhir          AS hargaAkhir,
                nilai_akhir          AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 40;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idJenis = $lMutasi->idJenisBarang;
            $idKelompok = $lMutasi->idKelompokBarang;

            if ($idJenisSaatIni != $idJenis) {
                $idJenisSaatIni = $idJenis;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
                "jumlah_pembelian" => 0,
                "jumlah_hasilproduksi" => 0,
                "jumlah_koreksi" => 0,
                "jumlah_penjualan" => 0,
                "jumlah_floorstok" => 0,
                "jumlah_bahanproduksi" => 0,
                "jumlah_rusak" => 0,
                "jumlah_expired" => 0,
                "jumlah_returpembelian" => 0,
                "jumlah_adjustment" => 0,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view =  new ReportTriwulanKatalogJenisKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanWebTriwulanKatalogKelompok: commit-e37d34f4
     */
    public function actionReportWebTriwulanKatalogKelompok(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();
        $triwulan = (int) $triwulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog           AS idKatalog,
                nama_barang          AS namaBarang,
                id_jenisbarang       AS idJenisBarang,       -- in use
                kode_jenis           AS kodeJenis,
                nama_jenis           AS namaJenis,           -- in use
                id_kelompokbarang    AS idKelompokBarang,    -- in use
                kode_kelompok        AS kodeKelompok,
                nama_kelompok        AS namaKelompok,        -- in use
                jumlah_awal          AS jumlahAwal,
                harga_awal           AS hargaAwal,
                nilai_awal           AS nilaiAwal,           -- in use
                nilai_pembelian      AS nilaiPembelian,      -- in use
                nilai_hasilproduksi  AS nilaiHasilProduksi,  -- in use
                nilai_koreksi        AS nilaiKoreksi,        -- in use
                nilai_penjualan      AS nilaiPenjualan,      -- in use
                nilai_floorstok      AS nilaiFloorstok,      -- in use
                nilai_bahanproduksi  AS nilaiBahanProduksi,  -- in use
                nilai_rusak          AS nilaiRusak,          -- in use
                nilai_expired        AS nilaiKadaluarsa,     -- in use
                nilai_returpembelian AS nilaiReturPembelian, -- in use
                nilai_adjustment     AS nilaiAdjustment,     -- in use
                jumlah_akhir         AS jumlahAkhir,
                harga_akhir          AS hargaAkhir,
                nilai_akhir          AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 40;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idJenis = $lMutasi->idJenisBarang;
            $idKelompok = $lMutasi->idKelompokBarang;

            if ($idJenisSaatIni != $idJenis) {
                $idJenisSaatIni = $idJenis;
                $idKelompokSaatIni = "";
                $hJudul1 = 0;
                $bJudul1 = 0;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul2 = 0;
                $bJudul2 = 0;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
                "jumlah_pembelian" => 0,
                "jumlah_hasilproduksi" => 0,
                "jumlah_koreksi" => 0,
                "jumlah_penjualan" => 0,
                "jumlah_floorstok" => 0,
                "jumlah_bahanproduksi" => 0,
                "jumlah_rusak" => 0,
                "jumlah_expired" => 0,
                "jumlah_returpembelian" => 0,
                "jumlah_adjustment" => 0,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportTriwulanKatalogKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanWebTriwulanJenisKelompok: commit-e37d34f4
     */
    public function actionReportWebTriwulanJenisKelompok(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();
        $triwulan = (int) $triwulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_jenisbarang            AS idJenisBarang,
                kode_jenis                AS kodeJenis,
                nama_jenis                AS namaJenis,
                id_kelompokbarang         AS idKelompokBarang,
                kode_kelompok             AS kodeKelompok,
                nama_kelompok             AS namaKelompok,
                SUM(nilai_awal)           AS sumNilaiAwal,
                SUM(nilai_pembelian)      AS sumNilaiPembelian,
                SUM(nilai_hasilproduksi)  AS sumNilaiHasilProduksi,
                SUM(nilai_koreksi)        AS sumNilaiKoreksi,
                SUM(nilai_penjualan)      AS sumNilaiPenjualan,
                SUM(nilai_floorstok)      AS sumNilaiFloorstok,
                SUM(nilai_bahanproduksi)  AS sumNilaiBahanProduksi,
                SUM(nilai_rusak)          AS sumNilaiRusak,
                SUM(nilai_expired)        AS sumNilaiKadaluarsa,
                SUM(nilai_returpembelian) AS sumNilaiReturPembelian,
                SUM(nilai_adjustment)     AS sumNilaiAdjustment,
                SUM(nilai_akhir)          AS sumNilaiAkhir
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            GROUP BY
                id_jenisbarang,
                id_kelompokbarang
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        return $this->renderPartial("_report-triwulan-jenis-kelompok", [ // TODO: php: uncategorized: TRUELY MISSING view file ("mutasi-triwulan-jenis-kelompok")
            "daftarLaporanMutasi" => $daftarLaporanMutasi,
            "triwulan" => Yii::$app->dateTime->numToQuarterly($triwulan),
            "tahun" => $tahun,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanWebTriwulanKelompok: commit-e37d34f4
     */
    public function actionReportWebTriwulanKelompok(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();
        $triwulan = (int) $triwulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog                             AS idKatalog,
                nama_barang                            AS namaBarang,
                id_kelompokbarang                      AS idKelompokBarang,        -- in use
                kode_kelompok                          AS kodeKelompok,
                nama_kelompok                          AS namaKelompok,            -- in use
                jumlah_awal                            AS jumlahAwal,
                harga_awal                             AS hargaAwal,
                nilai_awal                             AS nilaiAwal,               -- in use
                jumlah_pembelian                       AS jumlahPembelian,         -- in use
                nilai_pembelian                        AS nilaiPembelian,          -- in use
                jumlah_hasilproduksi                   AS jumlahHasilProduksi,
                nilai_hasilproduksi                    AS nilaiHasilProduksi,      -- in use
                jumlah_koreksi                         AS jumlahKoreksi,           -- in use
                nilai_koreksi                          AS nilaiKoreksi,            -- in use
                (jumlah_penjualan - jumlah_adjustment) AS jumlahPenjualan,
                (nilai_penjualan - nilai_adjustment)   AS nilaiPenjualan,
                jumlah_floorstok                       AS jumlahFloorstok,
                nilai_floorstok                        AS nilaiFloorstok,          -- in use
                jumlah_bahanproduksi                   AS jumlahBahanProduksi,
                nilai_bahanproduksi                    AS nilaiBahanProduksi,      -- in use
                jumlah_rusak                           AS jumlahRusak,
                nilai_rusak                            AS nilaiRusak,              -- in use
                jumlah_expired                         AS jumlahKadaluarsa,
                nilai_expired                          AS nilaiKadaluarsa,         -- in use
                jumlah_koreksipenerimaan               AS jumlahKoreksiPenerimaan, -- in use
                nilai_koreksipenerimaan                AS nilaiKoreksiPenerimaan,  -- in use
                jumlah_returpembelian                  AS jumlahReturPembelian,    -- in use
                nilai_returpembelian                   AS nilaiReturPembelian,     -- in use
                jumlah_akhir                           AS jumlahAkhir,
                harga_akhir                            AS hargaAkhir,
                nilai_akhir                            AS nilaiAkhir,              -- in use
                ''                                     AS nilaiPenjualan_,         -- in use
                ''                                     AS jumlahPenjualan_         -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $barisPerHalaman = 30;
        $noJudul = 1;
        $noData = 1;
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $idKelompok = $lMutasi->idKelompokBarang;

            if ($idKelompokSaatIni != $idKelompok) {
                $idKelompokSaatIni = $idKelompok;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
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

            if ($lMutasi->jumlahKoreksi > 0) {
                $lMutasi->jumlahPembelian += $lMutasi->jumlahKoreksi;
                $lMutasi->nilaiPembelian += $lMutasi->nilaiKoreksi;
            } else {
                $lMutasi->jumlahPenjualan_ -= $lMutasi->jumlahKoreksi;
                $lMutasi->nilaiPenjualan_ -= $lMutasi->nilaiKoreksi;
            }

            if ($lMutasi->jumlahKoreksiPenerimaan > 0) {
                $lMutasi->jumlahPembelian += $lMutasi->jumlahKoreksiPenerimaan;
                $lMutasi->nilaiPembelian += $lMutasi->nilaiKoreksiPenerimaan;
            } else {
                $lMutasi->jumlahReturPembelian -= $lMutasi->jumlahKoreksiPenerimaan;
                $lMutasi->nilaiReturPembelian -= $lMutasi->nilaiKoreksiPenerimaan;
            }

            $daftarHalaman[$hJudul][$bJudul]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul][$bJudul]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul][$bJudul]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul][$bJudul]["total_penjualan"] += $lMutasi->nilaiPenjualan_;
            $daftarHalaman[$hJudul][$bJudul]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul][$bJudul]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul][$bJudul]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul][$bJudul]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul][$bJudul]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul][$bJudul]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan_;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportTriwulanKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanMutasiTriwulanData7: commit-e37d34f4
     */
    public function reportTriwulanElse(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();
        $triwulan = (int) $triwulan;
        $tahun = (int) $tahun;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog            AS idKatalog,
                nama_barang           AS namaBarang,
                id_jenisbarang        AS idJenisBarang,
                kode_jenis            AS kodeJenis,
                nama_jenis            AS namaJenis,
                id_kelompokbarang     AS idKelompokBarang,
                kode_kelompok         AS kodeKelompok,
                nama_kelompok         AS namaKelompok,
                jumlah_awal           AS jumlahAwal,
                harga_awal            AS hargaAwal,
                nilai_awal            AS nilaiAwal,            -- in use
                jumlah_pembelian      AS jumlahPembelian,
                nilai_pembelian       AS nilaiPembelian,       -- in use
                jumlah_hasilproduksi  AS jumlahHasilProduksi,
                nilai_hasilproduksi   AS nilaiHasilProduksi,   -- in use
                jumlah_koreksi        AS jumlahKoreksi,
                nilai_koreksi         AS nilaiKoreksi,         -- in use
                jumlah_penjualan      AS jumlahPenjualan,
                nilai_penjualan       AS nilaiPenjualan,       -- in use
                jumlah_floorstok      AS jumlahFloorstok,
                nilai_floorstok       AS nilaiFloorstok,       -- in use
                jumlah_bahanproduksi  AS jumlahBahanProduksi,
                nilai_bahanproduksi   AS nilaiBahanProduksi,   -- in use
                jumlah_rusak          AS jumlahRusak,
                nilai_rusak           AS nilaiRusak,           -- in use
                jumlah_expired        AS jumlahKadaluarsa,
                nilai_expired         AS nilaiKadaluarsa,      -- in use
                jumlah_returpembelian AS jumlahReturPembelian,
                nilai_returpembelian  AS nilaiReturPembelian,  -- in use
                jumlah_adjustment     AS jumlahAdjustment,
                nilai_adjustment      AS nilaiAdjustment,      -- in use
                jumlah_akhir          AS jumlahAkhir,
                harga_akhir           AS hargaAkhir,
                nilai_akhir           AS nilaiAkhir            -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;
        $no = 1;

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $no++,
            ];

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportTriwulanDefault(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanPdfTriwulanKatalogDetailJenisKelompok: commit-e37d34f4
     */
    public function actionReportPdfTriwulanKatalogDetailJenisKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanPdfTriwulanKatalogDetailKelompok: commit-e37d34f4
     */
    public function actionReportPdfTriwulanKatalogDetailKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanPdfTriwulanKatalogJenisKelompok: commit-e37d34f4
     */
    public function actionReportPdfTriwulanKatalogJenisKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanPdfTriwulanKatalogKelompok: commit-e37d34f4
     */
    public function actionReportPdfTriwulanKatalogKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanPdfTriwulanJenisKelompok: commit-e37d34f4
     */
    public function actionReportPdfTriwulanJenisKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanPdfTriwulanKelompok: commit-e37d34f4
     */
    public function actionReportPdfTriwulanKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanExcelTriwulanKatalogDetailJenisKelompok: commit-e37d34f4
     */
    public function actionReportExcelTriwulanKatalogDetailJenisKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanExcelTriwulanKatalogDetailKelompok: commit-e37d34f4
     */
    public function actionReportExcelTriwulanKatalogDetailKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanExcelTriwulanKatalogJenisKelompok: commit-e37d34f4
     */
    public function actionReportExcelTriwulanKatalogJenisKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanExcelTriwulanKatalogKelompok: commit-e37d34f4
     */
    public function actionReportExcelTriwulanKatalogKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanExcelTriwulanJenisKelompok: commit-e37d34f4
     */
    public function actionReportExcelTriwulanJenisKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanExcelTriwulanKelompok: commit-e37d34f4
     */
    public function actionReportExcelTriwulanKelompok(): string
    {
        return $this->reportTriwulanPdf();
    }
}
