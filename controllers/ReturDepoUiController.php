<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\ReturDepoController as Pair;
use tlm\his\FatmaPharmacy\views\ReturDepoUi\FormEdit;
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
class ReturDepoUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormEdit = Yii::$app->canAccess([Pair::class, "actionCetak"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Retur Depo";
        $index->tabs = [
            ["title" => "Form Edit", "canSee" => $canSeeFormEdit, "registerId" => Yii::$app->actionToId([self::class, "actionFormEdit"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/returndepo.php#edit    the original method
     */
    public function actionFormEdit(): string
    {
        $view = new FormEdit(
            registerId:             Yii::$app->actionToId(__METHOD__),
            editAccess:             [true],
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:              Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            obatAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:               Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            pembungkusAcplUrl:      Yii::$app->actionToUrl([Katalog3Controller::class, "actionPembungkus"]),
            signaAcplUrl:           Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:            Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            rekamMedisAcplUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            registrasiAjaxUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            cekResepUrl:            Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            namaAcplUrl:            Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge33"]),
            testBridgeCekKeluarUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeCekKeluar"]),
            returDepoPrintWidgetId: Yii::$app->actionToId([Pair::class, "actionCetak"]),
            viewStrukWidgetId:      Yii::$app->actionToId([EresepDepoUiController::class, "actionViewStruk"]),
        );
        return $view->__toString();
    }
}
