<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Laporan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporaniki2.php the original file
 */
final class LaporanIki2
{
    private string $output;

    public function __construct(string $namaDepo, string $tanggalAwal, string $tanggalAkhir, iterable $daftarPenjualan)
    {
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";
        $obj5 = 0;
        $obj6 = 0;
        $obj7 = 0;
        ?>


<style>
#<?= $pageId ?> td {
    vertical-align: top;
    font-size: 11px;
}

#<?= $pageId ?> thead tr td {
    border: thin solid black;
    padding: 3px;
}

#<?= $pageId ?> td {
    padding: 2px;
}

#<?= $pageId ?> .topborder td {
    border-top: thin solid black;
}
</style>

<div>PERSENTASE KEPATUHAN DOKTER</div>
<div>TERHADAP PENGGUNAAN OBAT FORMULARIUM NASIONAL</div>
<div>Unit Kerja: <?= $namaDepo ?></div>
<div>Tanggal: <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>

<table>
    <thead>
        <tr>
            <td>Nama Dokter</td>
            <td>Jumlah Obat Formularium Nasional</td>
            <td>Jumlah Obat Formularium RS</td>
            <td>Jumlah Obat Non Formularium Nasional</td>
            <td>Jumlah R/</td>
            <td>Formularium Nasional (%)</td>
            <td>Formularium RS (%)</td>
            <td>Non Formularium Nasional (%)</td>
            <td>Satuan Kerja</td>
            <td>Formularium Nasional Per SMF (%)</td>
            <td>Non Formularium Nasional Per SMF (%)</td>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarPenjualan as $penjualan): ?>
        <tr>
            <td><?= $penjualan->dokter ?></td>
            <?php $totalr = $penjualan->nonFormulariumNas + $penjualan->formulariumNas + $penjualan->formulariumRs ?>
            <td class="text-right"><?= $toUserInt($penjualan->formulariumNas) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->formulariumRs) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->nonFormulariumNas) ?></td>
            <td class="text-right"><?= $toUserInt($totalr) ?></td>
            <td class="text-right"><?= $toUserInt(round((($penjualan->formulariumNas / $totalr) * 100), 2)) ?> %</td>
            <td class="text-right"><?= $toUserInt(round((($penjualan->formulariumRs / $totalr) * 100), 2)) ?> %</td>
            <td class="text-right"><?= $toUserInt(round((($penjualan->nonFormulariumNas / $totalr) * 100), 2)) ?> %</td>
            <td class="text-right"><?= $toUserInt($penjualan->namaSmf) ?></td>
        </tr>
        <tr>
            <td colspan="11" class="topborder text-right" style="border-top:thin solid black"></td>
        </tr>
        <?php
        $obj5 += $penjualan->formulariumNas;
        $obj6 += $penjualan->nonFormulariumNas;
        $obj7 += $penjualan->formulariumRs;
        ?>
    <?php endforeach ?>
    <tr>
        <td></td>
        <?php $totalAll = $obj5 + $obj6 + $obj7 ?>
        <td class="text-right"><?= $toUserFloat($obj5) ?> Formularium Nasional</td>
        <td class="text-right"><?= $toUserFloat($obj7) ?> Formularium RS</td>
        <td class="text-right"><?= $toUserFloat($obj6) ?> Non Formularium Nasional</td>
        <td class="text-right"><?= $toUserFloat($totalAll) ?> R</td>
        <td class="text-right"><?= $toUserInt(round(($obj5 / $totalAll) * 100, 2)) ?>%</td>
        <td class="text-right"><?= $toUserInt(round(($obj7 / $totalAll) * 100, 2)) ?>%</td>
        <td class="text-right"><?= $toUserInt(round(($obj6 / $totalAll) * 100, 2)) ?>%</td>
    </tr>
    </tbody>
</table>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
