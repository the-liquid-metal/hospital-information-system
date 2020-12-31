<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PrinterDepo;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/printerdepo/result.php the original file
 */
final class Result
{
    private string $output;

    public function __construct(string $setPrinterWidgetId, string $result)
    {
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<div class="splPlainPage" id="<?= $pageId ?>">
    <div class="col-xs-12">
        <p><?= $result ?></p>
    </div>

    <div class="col-xs-12">
        <a href="<?= $setPrinterWidgetId ?>" class="btn btn-warning">Kembali ke pengaturan printer</a>
    </div>
</div>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
