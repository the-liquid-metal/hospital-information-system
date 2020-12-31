<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPembelian;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/pembelian/laporan_akhir.php the original file
 */
final class ReportLaporanAkhir
{
    private string $output;

    public function __construct(
        string   $tanggalAwal,
        string   $tanggalAkhir,
        string   $subjenisAnggaran,
        iterable $daftarHalaman,
        iterable $daftarPembelian,
        float    $totalPl,
        float    $totalDo,
        float    $totalSelisih
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $halaman): ?>
<div class="page">
    <div>
        LAPORAN KONTRAK/SPK/SP<br/>
        Periode: <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?><br/>
        <?= $subjenisAnggaran ?>
    </div>

    <br/>

    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th rowspan="2">NO.</th>
                <th colspan="2">PL PEMBELIAN</th>
                <th colspan="2">DO (DELIVERY ORDER)</th>
                <th rowspan="2">PENYEDIA BARANG</th>
            </tr>
            <tr>
                <th>NO. PL</th>
                <th>JUMLAH</th>
                <th>NO. DO</th>
                <th>JUMLAH</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i, "no" => $no, "no_do" => $noDo, "nilai_akhir_do" => $nilaiAkhirDo]): ?>
            <?php if ($noDo): ?>
                <tr>
                    <td colspan="3"><?= $no ?></td>
                    <td><?= $noDo ?></td>
                    <td class="text-right"><?= $toUserFloat($nilaiAkhirDo) ?></td>
                    <td></td>
                </tr>

            <?php else: ?>
                <?php $pembelian = $daftarPembelian[$i] ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $pembelian->noDokumen ?></td>
                    <td class="text-right"><?= $toUserFloat($pembelian->nilaiAkhir) ?></td>
                    <td><?= $pembelian->noDo ?></td>
                    <td class="text-right"><?= $toUserFloat($pembelian->nilaiAkhirDo) ?></td>
                    <td><?= $pembelian->namaPemasok ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <tfoot>
            <tr>
                <td class="text-right" colspan="2">TOTAL</td>
                <td class="text-right"><?= $toUserFloat($totalPl) ?></td>
                <td class="text-right">TOTAL DO</td>
                <td class="text-right"><?= $toUserFloat($totalDo) ?></td>
                <td class="text-right"><?= $toUserFloat($totalSelisih) ?></td>
            </tr>
        </tfoot>
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
