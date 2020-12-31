<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Mutasi;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/mutasitriwulankatalogdetailjeniskelompok.php the original file
 * @deprecated {@see \tlm\his\FatmaPharmacy\views\LaporanMutasi\ReportTriwulanKatalogDetailJenisKelompok}    the replacement
 */
final class MutasiTriwulanKatalogDetailJenisKelompok
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        ?string  $bulan,
        string   $triwulan,
        int      $tahun,
        iterable $daftarHalaman,
        iterable $daftarLaporanMutasi,
        float    $grandTotalSaldoAwal,
        float    $grandTotalPembelian,
        float    $grandTotalHasilProduksi,
        float    $grandTotalKoreksi,
        float    $grandTotalPenjualan,
        float    $grandTotalFloorstock,
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
        $pageId = "";
        $jumlahHalaman = count($daftarHalaman);
        ?>


<style>
#<?= $pageId ?> .halaman {
    page-break-inside: avoid;
    page-break-after: auto;
    margin: 10px;
    padding: 10px;
}

#<?= $pageId ?> .halaman .header-area p {
    font-size: 14px;
    font-weight: bold;
    margin: 0;
}

#<?= $pageId ?> table.table_laporan {
    margin-top: 10px;
    border-bottom: 1px solid black;
    width: 100%;
    border-collapse: collapse;
}

#<?= $pageId ?> table.table_laporan thead tr th {
    font-size: 12px;
}

#<?= $pageId ?> table.table_laporan tr.row-header th,
#<?= $pageId ?> table.table_laporan tr td {
    border: 1px solid black;
}

#<?= $pageId ?> table.table_laporan tr td {
    text-align: right !important;
}

#<?= $pageId ?> table.table_laporan tr td:nth-child(1),
#<?= $pageId ?> table.table_laporan tr td:nth-child(2),
#<?= $pageId ?> table.table_laporan tr td:nth-child(3) {
    text-align: left !important;
}

#<?= $pageId ?> table.table_laporan tbody tr td {
    font-size: 11px;
}

#<?= $pageId ?> table.table_laporan tbody tr.kelompok_barang td {
    font-weight: bold;
}

#<?= $pageId ?> table.table_laporan tbody tr.jenis_barang td {
    font-weight: bold;
}

#<?= $pageId ?> table.table_laporan tr.jenis_barang td {
    font-weight: bold;
}

#<?= $pageId ?> table.table_laporan tr.total td {
    font-weight: bold;
    font-size: 11px;
}
</style>

<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="halaman">
    <div class="page-title">
        REKAPITULASI MUTASI PERSEDIAAN BARANG FARMASI<br/>
        PER KATALOG BARANG<br/>
        <?= strtoupper($triwulan ?? $bulan), " ", $tahun ?><br/>
        Tanggal Cetak: <?= $nowValUser ?>
    </div>

    <table class="table_laporan">
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
                <tr class="jenis_barang">
                    <td colspan="3"><?= $baris->no ?> JENIS: <?= $baris->namaJenisBarang ?></td>
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
                <tr class="kelompok_barang">
                    <td colspan="3"><?= $baris->no ?> KELOMPOK: <?= $baris->namaKelompokBarang ?></td>
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
                    <td class="text-right"><?= $toUserFloat($lMutasi->jumlahKoreksi) ?></td>
                    <td class="text-right"><?= $toUserFloat($lMutasi->nilaiKoreksi) ?></td>
                    <td class="text-right"><?= $toUserFloat($lMutasi->jumlahPenjualan) ?></td>
                    <td class="text-right"><?= $toUserFloat($lMutasi->nilaiPenjualan) ?></td>
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
                    <td class="text-right"><?= $toUserFloat($lMutasi->jumlahAdjustment) ?></td>
                    <td class="text-right"><?= $toUserFloat($lMutasi->nilaiAdjustment) ?></td>
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
                <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalKoreksi) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalPenjualan) ?></td>
                <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalFloorstock) ?></td>
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
</div>
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
