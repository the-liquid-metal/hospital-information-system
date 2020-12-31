<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPerencanaan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/perencanaan/search_nodoc.php the original file
 */
final class ReportItem
{
    private string $output;

    /**
     * ReportItem constructor.
     * @param iterable $daftarDetailPerencanaan
     * @throws DateTimeException
     */
    public function __construct(iterable $daftarDetailPerencanaan) {
        $nToM = Yii::$app->dateTime->numToMonthNameFunc();
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarDetailPerencanaan as $i => $dPerencanaan): ?>
    <?php
    $awal = $dPerencanaan->bulanAwalAnggaran;
    $akhir = $dPerencanaan->bulanAkhirAnggaran;
    $tahun = $dPerencanaan->tahunAnggaran;
    ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><a href="<?= $dPerencanaan->kodeRef ?>"><?= $dPerencanaan->noDokumen ?></a></td>
        <td><?= $toUserDate($dPerencanaan->tanggalDokumen) ?></td>
        <td><?= $dPerencanaan->noPl ?></td>
        <td><?= $dPerencanaan->namaPemasok ?></td>
        <td><?= $dPerencanaan->subjenisAnggaran ?></td>
        <td><?= $nToM($awal) . ($awal == $akhir ? "" : "-".$nToM($akhir)) ." ".$tahun ?></td>
        <td class="text-right"><?= $toUserFloat($dPerencanaan->nilaiAkhir) ?></td>
    </tr>
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
