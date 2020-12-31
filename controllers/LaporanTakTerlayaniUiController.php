<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanTakTerlayaniController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanTakTerlayaniUi\{
    FormRekapTakTerlayani,
    FormRekapTakTerlayani0,
    FormTakTerlayani,
    FormTakTerlayani0
};
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
class LaporanTakTerlayaniUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormRekapTakTerlayani = Yii::$app->canAccess([Pair::class, "actionReportRekapTakTerlayani"]);
        $canSeeFormRekapTakTerlayani0 = Yii::$app->canAccess([Pair::class, "actionReportRekapTakTerlayani0"]);
        $canSeeFormTakTerlayani = Yii::$app->canAccess([Pair::class, "actionReportTakTerlayani"]);
        $canSeeFormTakTerlayani0 = Yii::$app->canAccess([Pair::class, "actionReportTakTerlayani0"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Tak Terlayani";
        $index->tabs = [
            ["title" => "Rekap Tak Terlayani",  "canSee" => $canSeeFormRekapTakTerlayani,  "registerId" => Yii::$app->actionToId([self::class, "actionFormRekapTakTerlayani"])],
            ["title" => "Rekap Tak Terlayani0", "canSee" => $canSeeFormRekapTakTerlayani0, "registerId" => Yii::$app->actionToId([self::class, "actionFormRekapTakTerlayani0"])],
            ["title" => "Tak Terlayani",        "canSee" => $canSeeFormTakTerlayani,       "registerId" => Yii::$app->actionToId([self::class, "actionFormTakTerlayani"])],
            ["title" => "Tak Terlayani0",       "canSee" => $canSeeFormTakTerlayani0,      "registerId" => Yii::$app->actionToId([self::class, "actionFormTakTerlayani0"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#rekaptdkterlayani_harian    the original method
     * last exist of actionRekapTdkTerlayaniHarian: commit-e37d34f4
     */
    public function actionFormRekapTakTerlayani(): string
    {
        $view = new FormRekapTakTerlayani(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportRekapTakTerlayani"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#rekaptdkterlayani_harian_0    the original method
     * last exist of actionRekapTdkTerlayaniHarian0: commit-e37d34f4
     */
    public function actionFormRekapTakTerlayani0(): string
    {
        $view = new FormRekapTakTerlayani0(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportRekapTakTerlayani0"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#TdkTerlayaniHarian    the original method (not exist)
     * last exist of actionTdkTerlayaniHarian: commit-e37d34f4
     */
    public function actionFormTakTerlayani(): string
    {
        $view = new FormTakTerlayani(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportTakTerlayani"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#TdkTerlayaniHarian0    the original method (not exist)
     * last exist of actionTdkTerlayaniHarian0: commit-e37d34f4
     */
    public function actionFormTakTerlayani0(): string
    {
        $view = new FormTakTerlayani0(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportTakTerlayani0"]),
        );
        return $view->__toString();
    }
}
