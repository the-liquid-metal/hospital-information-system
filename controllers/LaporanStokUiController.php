<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanStokController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanStokUi\{FormKetersediaan, FormMonitor};
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
class LaporanStokUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormMonitor = Yii::$app->canAccess([Pair::class, "actionReportMonitor"]);
        $canSeeFormKetersediaan = Yii::$app->canAccess([Pair::class, "actionReportKetersediaan"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Stok";
        $index->tabs = [
            ["title" => "Monitor",      "canSee" => $canSeeFormMonitor,      "registerId" => Yii::$app->actionToId([self::class, "actionFormMonitor"])],
            ["title" => "Ketersediaan", "canSee" => $canSeeFormKetersediaan, "registerId" => Yii::$app->actionToId([self::class, "actionFormKetersediaan"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#index    the original method
     */
    public function actionFormMonitor(): string
    {
        $view = new FormMonitor(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportMonitor"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#warn    the original method
     * last exist of actionWarn: commit-e37d34f4
     */
    public function actionFormKetersediaan(): string
    {
        $view = new FormKetersediaan(
            registerId:  Yii::$app->actionToId(__METHOD__),
            actionUrl:   Yii::$app->actionToUrl([Pair::class, "actionReportKetersediaan"]),
            obatAcplUrl: Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            depoSelect:  Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }
}
