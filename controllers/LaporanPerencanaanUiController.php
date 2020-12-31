<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanPerencanaanController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanPerencanaanUi\{FormItem, FormRekapitulasi};
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
class LaporanPerencanaanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormRekapitulasi = Yii::$app->canAccess([Pair::class, "actionReportRekap"]);
        $canSeeFormItem = Yii::$app->canAccess([Pair::class, "actionReportItem"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Perencanaan";
        $index->tabs = [
            ["title" => "Rekapitulasi", "canSee" => $canSeeFormRekapitulasi, "registerId" => Yii::$app->actionToId([self::class, "actionFormRekapitulasi"])],
            ["title" => "Item",         "canSee" => $canSeeFormItem,         "registerId" => Yii::$app->actionToId([self::class, "actionFormItem"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#reports the original method
     */
    public function actionFormRekapitulasi(): string
    {
        $view = new FormRekapitulasi(
            registerId:  Yii::$app->actionToId(__METHOD__),
            actionUrl:   Yii::$app->actionToUrl([Pair::class, "actionReportRekap"]),
            bulanSelect: Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect: Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#reports the original method
     */
    public function actionFormItem(): string
    {
        $view = new FormItem(
            registerId:     Yii::$app->actionToId(__METHOD__),
            actionUrl:      Yii::$app->actionToUrl([Pair::class, "actionReportItem"]),
            katalogAcplUrl: Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSearch"]),
            itemSrcUrl:     Yii::$app->actionToUrl([Pair::class, "actionViewItem"]),
            bulanSelect:    Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:    Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }
}
