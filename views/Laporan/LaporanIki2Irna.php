<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Laporan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporaniki2.php the original file
 */
final class LaporanIki2Irna
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @param string $namaDepo
     * @param iterable $daftarPenjualan
     * @throws DateTimeException
     */
    public function __construct(string $namaDepo, iterable $daftarPenjualan)
    {
        $nowValUser = Yii::$app->dateTime->todayVal("user");
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        $obj5 = 0;
        $obj6 = 0;
        $totalall = 0;
        ?>


<style>
    td {
        vertical-align: top;
        font-size: 11px;
    }
    thead tr td {
        border: thin solid black;
        padding: 3px;
    }
    td {
        padding: 2px;
    }
    .topborder td {
        border-top: thin solid black;
    }
</style>


<h3>INSTALASI FARMASI</h3>
<div>Tanggal cetak: <?= $nowValUser ?></div>
<div>PERSENTASE KEPATUHAN DOKTER</div>
<div>TERHADAP PENGGUNAAN OBAT FORMULARIUM NASIONAL</div>
<div>Unit Kerja: <?= $namaDepo ?></div>
<div>Tanggal: <?= $_POST["fromdate"], " s.d. ", $_POST["enddate"] ?></div>

<table>
    <thead>
        <tr>
            <td>Nama Dokter</td>
            <td>Jumlah Obat Formularium Nasional</td>
            <td>Jumlah Obat Non Formularium Nasional</td>
            <td>Jumlah R/</td>
            <td>Formularium Nasional (%)</td>
            <td>Non Formularium Nasional (%)</td>
            <td>Satuan Kerja</td>
            <td>Formularium Nasional Per SMF (%)</td>
            <td>Non Formularium Nasional Per SMF (%)</td>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarPenjualan as $penjualan): ?>
        <?php
        $obj5 += $penjualan->formulariumNas;
        $obj6 += $penjualan->nonFormulariumNas;
        $totalr = $penjualan->nonFormulariumNas + $penjualan->formulariumNas;
        $totalall += $totalr;
        ?>
        <tr>
            <td><?= $penjualan->dokterPerObat ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->formulariumNas) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->nonFormulariumNas) ?></td>
            <td class="text-right"><?= $toUserInt($totalr) ?></td>
            <td class="text-right"><?= $toUserInt(round((($penjualan->formulariumNas / $totalr) * 100), 2)) ?></td>
            <td class="text-right"><?= $toUserInt(round((($penjualan->nonFormulariumNas / $totalr) * 100), 2)) ?></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="9" class="topborder text-right" style="border-top:thin solid black"></td>
        </tr>
    <?php endforeach ?>
    <tr>
        <td></td>
        <td class="text-right"><?= $toUserFloat($obj5) ?> Formularium Nasional</td>
        <td class="text-right"><?= $toUserFloat($obj6) ?> Non Formularium Nasional</td>
        <td class="text-right"><?= $toUserFloat($totalall) ?> R</td>
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
