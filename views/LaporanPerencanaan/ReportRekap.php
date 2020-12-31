<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPerencanaan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/perencanaan/rekapitulasi.php the original file
 */
final class ReportRekap
{
    private string $output;

    public function __construct(iterable $daftarHalaman, int $tahunAnggaran, string $periodeBulan, iterable $daftarDetailPerencanaan)
    {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $halaman): ?>
<div class="page">
    <div>REKAPITULASI RENCANA DAN REALISASI PENGADAAN BARANG FARMASI</div>
    <div>TAHUN ANGGARAN <?= $tahunAnggaran ?></div>
    <div>PERIODE BULAN <?= $periodeBulan ?></div>

    <table>
        <thead>
            <tr>
                <td rowspan="2">NO.</td>
                <td rowspan="2">KODE</td>
                <td rowspan="2">NAMA BARANG</td>
                <td rowspan="2">PABRIK</td>
                <td colspan="2">RENCANA</td>
                <td colspan="2">REALISASI</td>
                <td colspan="2">RENCANA - REALISASI</td>
            </tr>
            <tr>
                <td>Kuantitas</td>
                <td>Jumlah (Rp.)</td>
                <td>Kuantitas</td>
                <td>Jumlah (Rp.)</td>
                <td>Kuantitas</td>
                <td>Jumlah (Rp.)</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->jenisObat)): ?>
                <tr class="jenis_anggaran">
                    <td colspan="4"><?= $baris->no . $baris->jenisObat ?></td>
                    <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalRencana) ?></td>
                    <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalRealisasi) ?></td>
                    <td class="text-right" colspan="2"><?= $toUserFloat($baris->totalSelisih) ?></td>
                </tr>

            <?php else: ?>
                <?php $dPerencanaan = $daftarDetailPerencanaan[$baris->i] ?>
                <tr>
                    <td><?= $baris->no ?></td>
                    <td><?= $dPerencanaan->idKatalog ?></td>
                    <td><?= $dPerencanaan->namaSediaan ?></td>
                    <td><?= $dPerencanaan->namaPabrik ?></td>
                    <td class="text-right"><?= $toUserFloat($dPerencanaan->jumlahItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalRencana) ?></td>
                    <td class="text-right"><?= $toUserFloat($dPerencanaan->jumlahItemR) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalRealisasi) ?></td>
                    <td class="text-right"><?= $toUserFloat($dPerencanaan->jumlahItem - $dPerencanaan->jumlahItemR) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalSelisih) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
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
