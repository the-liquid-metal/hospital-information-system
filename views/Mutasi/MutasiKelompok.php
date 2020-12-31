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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/mutasikelompok.php the original file
 * @deprecated clone into 2 files below
 * @see \tlm\his\FatmaPharmacy\views\LaporanMutasi\ReportTriwulanDefault    the replacement
 * @see \tlm\his\FatmaPharmacy\views\LaporanMutasi\ReportBulanKelompok    the replacement
 */
final class MutasiKelompok
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
#<?= $pageId ?> table.table_laporan tr td:nth-child(2) {
    text-align: left !important;
}

#<?= $pageId ?> table.table_laporan tbody tr td {
    font-size: 11px;
}

#<?= $pageId ?> table.table_laporan tbody tr.kelompok_barang td {
    font-weight: bold;
}

#<?= $pageId ?> table.table_laporan tr.total td {
    font-weight: bold;
    font-size: 11px;
}
</style>

<?php foreach ($daftarHalaman as $kpage => $halaman): ?>
<div class="halaman">
    <div class="page-title">
        REKAPITULASI MUTASI PERSEDIAAN BARANG FARMASI<br/>
        PER KELOMPOK BARANG<br/>
        <?= strtoupper($triwulan ?? $bulan), " ", $tahun ?><br/>
        Tanggal Cetak: <?= $nowValUser ?>
    </div>

    <table class="table_laporan">
        <thead>
            <tr class="row-header">
                <th rowspan="2">No.</th>
                <th rowspan="2">Kelompok Barang</th>
                <th rowspan="2">Saldo Awal</th>
                <th colspan="3">Pengadaan</th>
                <th colspan="7">Pemakaian</th>
                <th rowspan="2">Saldo Akhir</th>
            </tr>
            <tr class="row-header">
                <th>Pembelian</th>
                <th>Hasil Produksi</th>
                <th>Koreksi</th>
                <th>Penjualan</th>
                <th>Floor Stock</th>
                <th>Bahan Produksi</th>
                <th>Rusak</th>
                <th>Kadaluarsa</th>
                <th>Retur Pembelian</th>
                <th>Adjustment</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i, "no" => $no]): ?>
            <?php $lMutasi = $daftarLaporanMutasi[$i] ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $lMutasi->namaKelompok ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiAwal) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiHasilProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiKoreksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiPenjualan) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiFloorStok) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiBahanProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiRusak) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiKadaluarsa) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiReturPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiAdjustment) ?></td>
                <td class="text-right"><?= $toUserFloat($lMutasi->nilaiAkhir) ?></td>
            </tr>
        <?php endforeach ?>

        <?php if ($kpage + 1 == $jumlahHalaman): ?>
            <tr class="total">
                <td></td>
                <td>TOTAL</td>
                <td class="text-right"><?= $toUserFloat($grandTotalSaldoAwal) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalHasilProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalKoreksi) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalPenjualan) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalFloorstock) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalBahanProduksi) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalRusak) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalExpired) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalReturPembelian) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalAdjustment) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalSaldoAkhir) ?></td>
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
