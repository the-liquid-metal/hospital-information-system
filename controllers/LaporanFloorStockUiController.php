<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanFloorStockController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanFloorStockUi\{FormTriwulan2nd2016, FormTriwulan3, FormTriwulan4};
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
class LaporanFloorStockUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormTriwulan3 = Yii::$app->canAccess([Pair::class, "actionReportTriwulan3"]);
        $canSeeFormTriwulan4 = Yii::$app->canAccess([Pair::class, "actionReportTriwulan4"]);
        $canSeeFormTriwulan2 = Yii::$app->canAccess([Pair::class, "actionReportTriwulan2nd2016"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Floor Stock";
        $index->tabs = [
            ["title" => "Form Triwulan3",      "canSee" => $canSeeFormTriwulan3, "registerId" => Yii::$app->actionToId([self::class, "actionFormTriwulan3"])],
            ["title" => "Form Triwulan4",      "canSee" => $canSeeFormTriwulan4, "registerId" => Yii::$app->actionToId([self::class, "actionFormTriwulan4"])],
            ["title" => "Form Triwulan2 2016", "canSee" => $canSeeFormTriwulan2, "registerId" => Yii::$app->actionToId([self::class, "actionFormTriwulan2nd2016"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#laporanfloorstock    the original method
     * last exist of actionLaporanFloorStock: commit-e37d34f4
     */
    public function actionFormTriwulan3(): string
    {
        $view = new FormTriwulan3(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportTriwulan3"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#laporanfloorstockdes    the original method
     * last exist of actionLaporanFloorStockDes: commit-e37d34f4
     */
    public function actionFormTriwulan4(): string
    {
        $view = new FormTriwulan4(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportTriwulan4"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#laporanfloorstockjuni16    the original method
     * last exist of actionLaporanFloorStockJuni16: commit-e37d34f4
     */
    public function actionFormTriwulan2nd2016(): string
    {
        $view = new FormTriwulan2nd2016(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportTriwulan2nd2016"]),
        );
        return $view->__toString();
    }
}
