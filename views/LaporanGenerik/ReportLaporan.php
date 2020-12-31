<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanGenerik;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporangenerikrawat2.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanGenerikRawat2: commit-e37d34f4
 */
final class ReportLaporan
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string   $namaDepo,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarPenjualan,
        int      $obj1,
        int      $obj2,
        int      $obj3,
        int      $obj4,
        int      $obj5,
        int      $obj6,
        int      $obj7,
        int      $obj8,
        int      $obj9,
        int      $all2,
        int      $all3,
        int      $all4,
    ) {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $ruangRawat = "";
        ?>


<div>
    <div>INSTALASI FARMASI</div>
    <div>Tanggal Cetak: <?= $nowValUser ?></div>
    <div>DAFTAR GENERIK (PER RUANG RAWAT)</div>
    <div>Unit Kerja: <?= $namaDepo ?></div>
    <div>Tanggal: <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>
</div>

<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <td>No.</td>
            <td>No. Resep</td>
            <td>Cara Bayar</td>
            <td>Jenis Resep</td>
            <td>Obat Jadi</td>
            <td>Racikan</td>
            <td>Generik</td>
            <td>Non Gen</td>
            <td>Formularium Nasional</td>
            <td>Non Formularium Nasional</td>
            <td>ARV</td>
            <td>FDC</td>
            <td>MDT</td>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarPenjualan as $i => $penjualan): ?>
        <?php if ($penjualan->namaRuangRawat != $ruangRawat): ?>
        <tr>
            <td colspan='13' style='border:thin solid black;background:lightgrey'><?= $penjualan->namaRuangRawat ?></td>
        </tr>
        <?php endif ?>
        <?php $ruangRawat = $penjualan->namaRuangRawat ?>

        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= $penjualan->noResep ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->pembayaran) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->jenisResep) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->obatJadi) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->obatRacik) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->generik) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->nonGenerik) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->formulariumNas) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->nonFormulariumNas) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->arv) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->fdc) ?></td>
            <td class="text-right"><?= $toUserInt($penjualan->mdt) ?></td>
        </tr>
    <?php endforeach ?>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right"><?= $toUserFloat($obj1) ?> Obat Jadi</td>
            <td class="text-right"><?= $toUserFloat($obj2) ?> Racikan</td>
            <td class="text-right"><?= $toUserFloat($obj3) ?> Generik</td>
            <td class="text-right"><?= $toUserFloat($obj4) ?> Non</td>
            <td class="text-right"><?= $toUserFloat($obj5) ?> Formularium Nasional</td>
            <td class="text-right"><?= $toUserFloat($obj6) ?> Non Formularium Nasional</td>
            <td class="text-right"><?= $toUserFloat($obj7) ?> ARV</td>
            <td class="text-right"><?= $toUserFloat($obj8) ?> FDC</td>
            <td class="text-right"><?= $toUserFloat($obj9) ?> MDT</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right"><?= $toUserFloat($obj1 / $all2 * 100) ?> % Obat Jadi</td>
            <td class="text-right"><?= $toUserFloat($obj2 / $all2 * 100) ?> % Racikan</td>
            <td class="text-right"><?= $toUserFloat($obj3 / $all3 * 100) ?> % Generik</td>
            <td class="text-right"><?= $toUserFloat($obj4 / $all3 * 100) ?> % Non</td>
            <td class="text-right"><?= $toUserFloat($obj5 / $all4 * 100) ?> % Formularium Nasional</td>
            <td class="text-right"><?= $toUserFloat($obj6 / $all4 * 100) ?> % Non Formularium Nasional</td>
            <td class="text-right"><?= $toUserFloat($obj7 / $all2 * 100) ?> % ARV</td>
            <td class="text-right"><?= $toUserFloat($obj8 / $all2 * 100) ?> % FDC</td>
            <td class="text-right"><?= $toUserFloat($obj9 / $all2 * 100) ?> % MDT</td>
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
