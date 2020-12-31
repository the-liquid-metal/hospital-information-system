<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepBilling;

use tlm\libs\LowEnd\components\{DateTimeException, FormHelper as FH, GenericData};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepbilling/struk-kasir-bri.php the original file
 *
 * TODO: php: uncategorized: tidy-in-progress, better-if-rewrite
 */
final class StrukKasirBri
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(GenericData $pasien, float $totalCeil, GenericData $user)
    {
        $toUserFloat = Yii::$app->number->toUserFloat();
        $todayValUser = Yii::$app->dateTime->todayVal("user");
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> .print-area {
    letter-spacing: 1px;
    color: #000000;
    font-weight: normal;
    font-style: normal;
}

#<?= $pageId ?> tr {
    height: 10px;
}

#<?= $pageId ?> td {
    vertical-align: top;
    font-family: "lucida console", Helvetica, sans-serif;
    font-size: 10px !important;
    padding: 0;
}
</style>

<div>BUKTI PEMBAYARAN</div>
<div>PT Affordable App</div>

<table class="print-area">
    <tr>
        <td colspan="2" style="width:20%">Telah terima dari</td>
        <td style="width:50%">: <?= $pasien->namaPasien ?></td>
        <td style="width:30%"></td>
    </tr>
    <tr>
        <td colspan="2">Banyaknya Uang</td>
        <td>: <?= FH::terbilang($totalCeil) ?> rupiah</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Untuk pembayaran biaya</td>
        <td>: Obat-obatan Farmasi</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Nama Penderita</td>
        <td>: <?= $pasien->atasNama ?: $pasien->namaPasien ?></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">No. Reg. / Kode Rekam Medis</td>
        <td>: <?= $pasien->noResep ?> / <?= $pasien->kodeRekamMedis ?></td>
        <td></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width:10%">Jumlah (Rp.):</td>
        <td colspan="2" style="width:60%; font-size:0.82em"><?= $toUserFloat($totalCeil) ?></td>
        <td style="width:30%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jakarta, <?= $todayValUser ?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width:15%"></td>
        <td colspan="2" style="width:55%"></td>
        <td style="width:30%"><?= $user->name ?> (<?= $user->username ?>)</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2"></td>
        <td style="border-top:1px solid black; text-align:center">Kasir</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width:15%"></td>
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HARAP TANDA BUKTI INI DISIMPAN SEBAGAI BUKTI PEMBAYARAN YANG SAH</td>
    </tr>
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
