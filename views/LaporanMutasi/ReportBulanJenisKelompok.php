<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanMutasi;

use tlm\libs\LowEnd\components\DateTimeException;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/mutasijeniskelompok.php the original file
 */
final class ReportBulanJenisKelompok
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string   $bulan,
        ?string  $triwulan,
        int      $tahun,
        iterable $daftarHalaman,
        iterable $daftarLaporanMutasi,
        float    $grandTotalSaldoAwal,
        float    $grandTotalPembelian,
        float    $grandTotalHasilProduksi,
        float    $grandTotalKoreksi,
        float    $grandTotalPenjualan,
        float    $grandTotalFloorStock,
        float    $grandTotalBahanProduksi,
        float    $grandTotalRusak,
        float    $grandTotalExpired,
        float    $grandTotalReturPembelian,
        float    $grandTotalAdjustment,
        float    $grandTotalSaldoAkhir
    ) {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $jumlahHalaman = count($daftarHalaman);
        $noData = 1;
        $noJudul1 = 1;
        $noJudul2 = 1;
        ?>


<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page-title">
    REKAPITULASI MUTASI PERSEDIAAN BARANG FARMASI<br/>
    PER KATALOG BARANG<br/>
    <?= strtoupper($triwulan ?? $bulan), " ", $tahun ?><br/>
    Tanggal Cetak: <?= $nowValUser ?>
</div>

<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr class="row-header">
            <th rowspan="3">No.</th>
            <th rowspan="3">Kode</th>
            <th rowspan="3">Nama Barang</th>
            <th rowspan="2" colspan="3">Saldo Awal</th>
            <th colspan="6">Pengadaan</th>
            <th colspan="14">Pemakaian</th>
            <th rowspan="2" colspan="3">Saldo Akhir</th>
        </tr>
        <tr class="row-header">
            <th colspan="2">Pembelian</th>
            <th colspan="2">Hasil Produksi</th>
            <th colspan="2">Koreksi</th>
            <th colspan="2">Penjualan</th>
            <th colspan="2">Floor Stock</th>
            <th colspan="2">Bahan Produksi</th>
            <th colspan="2">Rusak</th>
            <th colspan="2">Kadaluarsa</th>
            <th colspan="2">Retur Pembelian</th>
            <th colspan="2">Adjustment</th>
        </tr>
        <tr class="row-header">
            <th>Qty</th>
            <th>Harga</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Jml</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Jml</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($halaman as $baris): ?>
        <?php if (isset($baris->namaJenisBarang)): ?>
            <?php $noJudul2 = 1 ?>
            <tr class="jenis_barang">
                <td colspan="3"><?= $noJudul1++ ?>. JENIS: <?= $baris->namaJenisBarang ?></td>
                <td class="text-right" colspan="3"><?= $toUserFloat($baris->totalSaldoAwal) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalPembelian) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalHasilProduksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalKoreksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalPenjualan) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalFloorStock) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalBahanProduksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalRusak) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalKadaluarsa) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalReturPembelian) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalAdjustment) ?></td>
                <td class="text-right" colspan="3"><?= $toUserFloat($baris->totalSaldoAkhir) ?></td>
            </tr>

        <?php elseif (isset($baris->namaKelompokBarang)): ?>
            <?php $noData = 1 ?>
            <tr class="kelompok_barang">
                <td colspan="3"><?= $noJudul1.".".$noJudul2++ ?>. KELOMPOK: <?= $baris->namaKelompokBarang ?></td>
                <td class="text-right" colspan="3"><?= $toUserFloat($baris->subtotalSaldoAwal) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalPembelian) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalHasilProduksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalKoreksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalPenjualan) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalFloorStock) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalBahanProduksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalRusak) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalKadaluarsa) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalReturPembelian) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotalAdjustment) ?></td>
                <td class="text-right" colspan="3"><?= $toUserFloat($baris->subtotalSaldoAkhir) ?></td>
            </tr>

        <?php else: ?>
            <?php $lMutasi = $daftarLaporanMutasi[$baris->i] ?>
            <tr>
                <td><?= $noJudul1.".".$noJudul2.".".$noData++ ?></td>
                <td><?= $baris->idKatalog ?></td>
                <td><?= $baris->namaBarang ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahAwal) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->hargaAwal) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiAwal) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahHasilProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiHasilProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahKoreksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiKoreksi) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahPenjualan) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiPenjualan) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahFloorStok) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiFloorStok) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahBahanProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiBahanProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahRusak) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiRusak) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahKadaluarsa) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiKadaluarsa) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahReturPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiReturPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahAdjustment) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiAdjustment) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahAkhir) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->hargaAkhir) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiAkhir) ?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>

    <?php if ($h + 1 == $jumlahHalaman): ?>
        <tr class="total">
            <td></td>
            <td></td>
            <td>TOTAL</td>
            <td class="text-right" colspan="3"><?= $toUserFloat($grandTotalSaldoAwal) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalPembelian) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalHasilProduksi) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalKoreksi) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalPenjualan) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalFloorStock) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalBahanProduksi) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalRusak) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalExpired) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalReturPembelian) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalAdjustment) ?></td>
            <td class="text-right" colspan="3"><?= $toUserFloat($grandTotalSaldoAkhir) ?></td>
        </tr>
    <?php endif ?>
    </tbody>
</table>
<?php endforeach ?>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
