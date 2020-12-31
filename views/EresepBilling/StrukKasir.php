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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepbilling/struk-kasir.php the original file
 */
final class StrukKasir
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
#<?= $pageId ?> tr {
    height: 10px;
}

#<?= $pageId ?> td {
    vertical-align: top;
    font-family: "lucida console", Helvetica, sans-serif;
    font-size: 10px !important;
    padding: 0;
}

#<?= $pageId ?> .print-area {
    letter-spacing: 1px;
    color: #000000;
    font-weight: normal;
    font-style: normal;
}
</style>

<table class="print-area">
    <tr>
        <td style="width:20%" colspan="2"></td>
        <td style="width:50%"><?= $pasien->atasNama ?: $pasien->namaPasien ?></td>
        <td style="width:30%"></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td><?= FH::terbilang($totalCeil) ?> rupiah</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td>Obat-obatan Farmasi</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td><?= $pasien->namaPasien ?></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td><?= $pasien->noResep ?> / <?= $pasien->kodeRekamMedis ?></td>
        <td></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width:10%"></td>
        <td colspan="2" style="width:60%; font-size:0.82em"><?= $toUserFloat($totalCeil) ?></td>
        <td style="width:30%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $todayValUser ?></td>
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
