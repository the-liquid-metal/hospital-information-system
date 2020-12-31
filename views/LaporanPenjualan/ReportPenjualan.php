<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenjualan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporandepotes.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanDepoTest: commit-e37d34f4
 */
final class ReportPenjualan
{
    private string $output;

    public function __construct(
        iterable $daftarHalaman,
        string   $namaDepo,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarPenjualan,
        int      $jumlahHalaman,
        float    $grandTotalJumlah,
        float    $grandTotalNilai,
        float    $grandTotalLaba
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="print_area page">
    <div>LAPORAN ANALISA PENJUALAN</div>
    <div>Unit Kerja: <?= $namaDepo ?></div>
    <div>Tanggal: <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <td>Kode</td>
                <td>Nama Obat</td>
                <td>Nama Pabrik</td>
                <td>Harga Perolehan</td>
                <td>Harga Jual</td>
                <td>Jumlah Jual</td>
                <td>Total Nilai Jual</td>
                <td>Laba</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->namaJenis)): ?>
                <tr style="background-color:lightgrey">
                    <td colspan="5">-- <?= $baris->no . $baris->namaJenis ?> --</td>
                    <td class="text-right"><?= $toUserFloat($baris->totalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->totalNilai) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->totalLaba) ?></td>
                </tr>

            <?php elseif (isset($baris->namaKelompok)): ?>
                <tr>
                    <td colspan="5">-- <?= $baris->no . $baris->namaKelompok ?> --</td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalNilai) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalLaba) ?></td>
                </tr>

            <?php else: ?>
                <?php $penjualan = $daftarPenjualan[$baris->i] ?>
                <tr>
                    <td><?= $penjualan->kodeObat ?></td>
                    <td><?= $penjualan->namaBarang ?></td>
                    <td><?= $penjualan->namaPabrik ?></td>
                    <td class="text-right"><?= $toUserFloat($penjualan->hpItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($penjualan->hjaSetting) ?></td>
                    <td class="text-right"><?= $toUserFloat($penjualan->totalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->nilai) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->laba) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td class="text-right" colspan="5" rowspan="3">
                    <b><span style="text-decoration:underline">Total</span></b>
                </td>
                <td colspan="2">Jumlah Penjualan</td>
                <td class="text-right"><?= $toUserInt($grandTotalJumlah) ?></td>
            </tr>
            <tr>
                <td colspan="2">Nilai Penjualan</td>
                <td class="text-right"><?= $toUserInt($grandTotalNilai) ?></td>
            </tr>
            <tr>
                <td colspan="2">Laba</td>
                <td class="text-right"><?= $toUserInt($grandTotalLaba) ?></td>
            </tr>
        </tfoot>
        <?php endif ?>
    </table>

    <table class="hal">
        <tr>
            <td class="text-right"><?= "Hal " . $toUserInt($h + 1) . " dari " . $toUserInt($jumlahHalaman) ?></td>
        </tr>
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
