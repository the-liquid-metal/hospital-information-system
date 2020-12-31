<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanMutasi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/mutasitriwulankatalogdetailkelompok-2.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Mutasi\MutasiTriwulanKatalogDetailKelompok2: commit-e37d34f4
 */
final class ReportTriwulanKatalogDetailKelompok
{
    private string $output;

    public function __construct(
        ?string  $bulan,
        string   $triwulan,
        int      $tahun,
        iterable $daftarHalaman,
        iterable $daftarLaporanMutasi,
        float    $grandTotalSaldoAwal,
        float    $grandTotalPembelian,
        float    $grandTotalHasilProduksi,
        float    $grandTotalPenjualan,
        float    $grandTotalFloorStock,
        float    $grandTotalBahanProduksi,
        float    $grandTotalRusak,
        float    $grandTotalExpired,
        float    $grandTotalReturPembelian,
        float    $grandTotalSaldoAkhir
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $jumlahHalaman = count($daftarHalaman);
        ?>


<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page-title">
    REKAPITULASI MUTASI PERSEDIAAN BARANG FARMASI<br/>
    PER KATALOG BARANG<br/>
    <?= strtoupper($triwulan ?? $bulan), " ", $tahun ?>
</div>

<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr class="row-header">
            <th rowspan="3">No.</th>
            <th rowspan="3">Kode</th>
            <th rowspan="3">Nama Barang</th>
            <th rowspan="2" colspan="3">Saldo Awal</th>
            <th colspan="4">Pengadaan</th>
            <th colspan="12">Pemakaian</th>
            <th rowspan="2" colspan="3">Saldo Akhir</th>
        </tr>
        <tr class="row-header">
            <th colspan="2">Pembelian</th>
            <th colspan="2">Hasil Produksi</th>
            <th colspan="2">Penjualan</th>
            <th colspan="2">Floor Stock</th>
            <th colspan="2">Bahan Produksi</th>
            <th colspan="2">Rusak</th>
            <th colspan="2">Kadaluarsa</th>
            <th colspan="2">Retur Pembelian</th>
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
            <th>Harga</th>
            <th>Jml</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($halaman as $baris): ?>
        <?php if (isset($baris->namaKelompokBarang)): ?>
            <tr class="kelompok_barang">
                <td colspan="3"><?= $baris->no ?> KELOMPOK: <?= $baris->namaKelompokBarang ?></td>
                <td class="text-right" colspan="3"><?= $toUserFloat($baris->totalSaldoAwal) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalPembelian) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalHasilProduksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalPenjualan) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalFloorStock) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalBahanProduksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalRusak) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalKadaluarsa) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalReturPembelian) ?></td>
                <td class="text-right" colspan="3"><?= $toUserFloat($baris->totalSaldoAkhir) ?></td>
            </tr>

        <?php else: ?>
            <?php $lMutasi = $daftarLaporanMutasi[$baris->i] ?>
            <tr class="item">
                <td><?= $baris->no ?></td>
                <td><?= $lMutasi->idKatalog ?></td>
                <td><?= $lMutasi->namaBarang ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahAwal) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->hargaAwal) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiAwal) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahHasilProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiHasilProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahPenjualan_) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiPenjualan_) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahFloorStok) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiFloorStok) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahBahanProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiBahanProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahRusak) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiRusak) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahKadaluarsa) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiKadaluarsa) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahReturPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiReturPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->jumlahAkhir) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->hargaAkhir) ?></td>
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
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalPenjualan) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalFloorStock) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalBahanProduksi) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalRusak) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalExpired) ?></td>
            <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalReturPembelian) ?></td>
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
