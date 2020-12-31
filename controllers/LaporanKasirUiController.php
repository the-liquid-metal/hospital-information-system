<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanKasirController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanKasirUi\FormKasir;
use tlm\libs\LowEnd\views\CustomIndexScript;
use Yii;
use yii\web\Controller;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class LaporanKasirUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormKasir = Yii::$app->canAccess([Pair::class, "actionReportKasir"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Kasir";
        $index->tabs = [
            ["title" => "Form Kasir", "canSee" => $canSeeFormKasir, "registerId" => Yii::$app->actionToId([self::class, "actionFormKasir"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#kasir    the original method
     */
    public function actionFormKasir(): string
    {
        $view = new FormKasir(
            registerId:  Yii::$app->actionToId(__METHOD__),
            actionUrl:   Yii::$app->actionToUrl([Pair::class, "actionReportKasir"]),
            kasirSelect: Yii::$app->actionToUrl([KasirController::class, "actionSelect2Data"]),
        );
        return $view->__toString();
    }
}
