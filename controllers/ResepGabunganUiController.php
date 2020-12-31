<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\ResepGabunganController as Pair;
use tlm\his\FatmaPharmacy\views\ResepGabunganUi\{TableKumpulanResep, TableResep};
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
class ResepGabunganUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTableResep         = Yii::$app->canAccess([Pair::class, "actionListGabung2"]);
        $canSeeTableKumpulanResep = Yii::$app->canAccess([Pair::class, "actionKumpulanResepTableData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Resep Gabungan";
        $index->tabs = [
            ["title" => "Table Resep",          "canSee" => $canSeeTableResep,         "registerId" => Yii::$app->actionToId([self::class, "actionTableResep"])],
            ["title" => "Table Kumpulan Resep", "canSee" => $canSeeTableKumpulanResep, "registerId" => Yii::$app->actionToId([self::class, "actionTableKumpulanResep"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#listresep2    the original method
     */
    public function actionTableResep(): string
    {
        $view = new TableResep(
            registerId:            Yii::$app->actionToId(__METHOD__),
            dataUrl:               Yii::$app->actionToUrl([Pair::class, "actionTableResepData"]),
            actionUrl:             Yii::$app->actionToUrl([Pair::class, "actionListGabung2"]),
            deleteGabunganUrl:     Yii::$app->actionToUrl([Pair::class, "actionDeleteGabungan"]),
            transferUrl:           Yii::$app->actionToUrl([Pair::class, "actionTransfer"]),
            kodeRekamMedisAcplUrl: Yii::$app->actionToUrl([Pair::class, "actionCariPasien"]),
            cariRekamMedisUrl:     Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeBaru"]),
            cetak1WidgetId:        Yii::$app->actionToId([Pair::class, "actionPrintGabungan2"]),
            cetak2WidgetId:        Yii::$app->actionToId([Pair::class, "actionPrintGabungan"]),
            lihatWidgetId:         Yii::$app->actionToId([EresepDepoDrUiController::class, "actionViewStruk"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#listkumpulanresep    the original method
     */
    public function actionTableKumpulanResep(): string
    {
        $view = new TableKumpulanResep(
            registerId:             Yii::$app->actionToId(__METHOD__),
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionKumpulanResepTableData"]),
            printItemUrl:           Yii::$app->actionToUrl([Pair::class, "actionPrintKumpulanResep"]),
            cetakObatWidgetId:      Yii::$app->actionToId([Pair::class, "actionCetakObat"]),
            cetakObatLqWidgetId:    Yii::$app->actionToId([Pair::class, "actionCetakObatLq"]),
            cetakNoResepWidgetId:   Yii::$app->actionToId([Pair::class, "actionCetakNoResep"]),
            cetakNoResepLqWidgetId: Yii::$app->actionToId([Pair::class, "actionCetakNoResepLq"]),
        );
        return $view->__toString();
    }
}
