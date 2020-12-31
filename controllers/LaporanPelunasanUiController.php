<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanPelunasanController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanPelunasanUi\FormPelunasan;
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
class LaporanPelunasanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormPelunasan = Yii::$app->canAccess([Pair::class, "actionReportPelunasan"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Pelunasan";
        $index->tabs = [
            ["title" => "Form Pelunasan", "canSee" => $canSeeFormPelunasan, "registerId" => Yii::$app->actionToId([self::class, "actionFormPelunasan"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#pelunasan    the original method
     * last exist of actionPelunasan: commit-e37d34f4
     */
    public function actionFormPelunasan(): string
    {
        $view = new FormPelunasan(
            registerId:       Yii::$app->actionToId(__METHOD__),
            actionUrl:        Yii::$app->actionToUrl([Pair::class, "actionReportPelunasan"]),
            depoSelect:       Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
            ruangRawatSelect: Yii::$app->actionToUrl([RuangRawatController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }
}
