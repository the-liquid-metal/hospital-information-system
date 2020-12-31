<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenerimaan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/penerimaan/laporanperpbf2.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Penerimaan\LaporanPerPbf2: commit-e37d34f4
 */
final class ReportPerPbf
{
    private string $output;

    public function __construct(
        iterable $daftarHalaman,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        int      $jumlahHalaman,
        float    $grandTotalNilai
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page">
    <div class="header">
        <div>LAPORAN PENERIMAAN BARANG FARMASI</div>
        <div>TIM PENERIMA HASIL PEKERJAAN BARANG MEDIK</div>
        <div>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>
    </div>

    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>No.</th>
                <th>Pemasok</th>
                <th>No. SPK</th>
                <th>No. BA</th>
                <th>No. Faktur</th>
                <th>No. Surat Jalan</th>
                <th>Rincian (Rp.)</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->jenisPenerimaan)): ?>
                <tr class="t_head">
                    <td colspan="6"><strong><?= $baris->no . $baris->jenisPenerimaan ?></strong></td>
                    <td class="text-right"><strong><?= $toUserFloat($baris->totalNilai) ?></strong></td>
                </tr>

            <?php else: ?>
                <tr>
                    <td><?= $baris->no ?></td>
                    <td><?= $baris->namaPemasok ?></td>
                    <td><?= $baris->noSpk ?></td>
                    <td><?= $baris->noBa ?></td>
                    <td><?= $baris->noFaktur ?></td>
                    <td><?= $baris->noSuratJalan ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->nilaiAkhir) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalNilai) ?></td>
            </tr>
        </tfoot>
        <?php endif ?>
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
