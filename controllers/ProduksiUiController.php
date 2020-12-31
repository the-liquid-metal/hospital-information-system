<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\ProduksiController as Pair;
use tlm\his\FatmaPharmacy\views\ProduksiUi\{Form, Table};
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
class ProduksiUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeTable = Yii::$app->canAccess([Pair::class, "actionTableData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Produksi";
        $index->tabs = [
            ["title" => "Form",  "canSee" => $canSeeForm,  "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Table", "canSee" => $canSeeTable, "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/produksi.php#index    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:           Yii::$app->actionToId(__METHOD__),
            actionUrl:            Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            obatAcplUrl:          Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:             Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            pembungkusAcplUrl:    Yii::$app->actionToUrl([Katalog3Controller::class, "actionPembungkus"]),
            stokDataUrl:          Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            rekamMedisAcplUrl:    Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            registrasiAjaxUrl:    Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            cekResepUrl:          Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            tableWidgetId:        Yii::$app->actionToUrl([self::class, "actionTable"]),
            barangProduksiSelect: Yii::$app->actionToUrl([BarangProduksiController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/produksi.php#listproduksi    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:    Yii::$app->actionToId(__METHOD__),
            dataUrl:       Yii::$app->actionToId([Pair::class, "actionTableData"]),
            formWidgetId:  Yii::$app->actionToId([self::class, "actionForm"]),
            printWidgetId: Yii::$app->actionToId([Pair::class, "actionPrint"]),
        );
        return $view->__toString();
    }
}
