<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\Katalog1Controller as Pair;
use tlm\his\FatmaPharmacy\views\KatalogUi\{Form, Table1, Table2};
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
class KatalogUi1Controller extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable1 = Yii::$app->canAccess([Pair::class, "actionTable1Data"]);
        $canSeeTable2 = Yii::$app->canAccess([Katalog2Controller::class, "actionTable2Data"]);
        $canSeeForm   = Yii::$app->canAccess([Pair::class, "actionSave"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Katalog1";
        $index->tabs = [
            ["title" => "Table1", "canSee" => $canSeeTable1, "registerId" => Yii::$app->actionToId([self::class, "actionTable1"])],
            ["title" => "Table2", "canSee" => $canSeeTable2, "registerId" => Yii::$app->actionToId([self::class, "actionTable2"])],
            ["title" => "Form",   "canSee" => $canSeeForm,   "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#index    the original method
     */
    public function actionTable1(): string
    {
        $view = new Table1(
            registerId:           Yii::$app->actionToId(__METHOD__),
            editAccess:           [true],
            deleteAccess:         [true],
            auditAccess:          [true],
            dataUrl:              Yii::$app->actionToUrl([Pair::class, "actionTable1Data"]),
            deleteUrl:            Yii::$app->actionToUrl([Pair::class, "actionDelete"]),
            formWidgetId:         Yii::$app->actionToId([self::class, "actionForm"]),
            jenisObatSelect:      Yii::$app->actionToUrl([JenisObatController::class, "actionSelect1Data"]),
            kelompokBarangSelect: Yii::$app->actionToUrl([KelompokBarangController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalogtest.php#index    the original method
     */
    public function actionTable2(): string
    {
        $view = new Table2(
            registerId:           Yii::$app->actionToId(__METHOD__),
            editAccess:           [true],
            deleteAccess:         [true],
            exportAccess:         [true],
            dataUrl:              Yii::$app->actionToUrl([Katalog2Controller::class, "actionTable2Data"]),
            deleteUrl:            Yii::$app->actionToUrl([Pair::class, "actionDelete"]),
            brandAcplUrl:         Yii::$app->actionToUrl([BrandController::class, "actionSelect"]),
            generikAcplUrl:       Yii::$app->actionToUrl([GenerikController::class, "actionSelect"]),
            pabrikAcplUrl:        Yii::$app->actionToUrl([PabrikController::class, "actionSelect"]),
            formWidgetId:         Yii::$app->actionToId([self::class, "actionForm"]),
            exportWidgetId:       Yii::$app->actionToId([Katalog2Controller::class, "actionExport"]),
            jenisObatSelect:      Yii::$app->actionToUrl([JenisObatController::class, "actionSelect1Data"]),
            kelompokBarangSelect: Yii::$app->actionToUrl([KelompokBarangController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:          Yii::$app->actionToId(__METHOD__),
            addAccess:           [true],
            editAccess:          [true],
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            addActionUrl:        Yii::$app->actionToUrl([Pair::class, "actionSave"]),
            editActionUrl:       Yii::$app->actionToUrl([Pair::class, "actionSave"]),
            jenisObatAcplUrl:    Yii::$app->actionToUrl([JenisBarangController::class, "actionSelect"]),
            brandAcplUrl:        Yii::$app->actionToUrl([BrandController::class, "actionSelect"]),
            generikAcplUrl:      Yii::$app->actionToUrl([GenerikController::class, "actionSelect"]),
            pemasokAcplUrl:      Yii::$app->actionToUrl([PemasokController::class, "actionSelect"]),
            pabrikAcplUrl:       Yii::$app->actionToUrl([PabrikController::class, "actionSelect"]),
            cekUnikKodeUrl:      Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            cekUnikNamaUrl:      Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            kemasanBesarAcplUrl: Yii::$app->actionToUrl([KemasanController::class, "actionSelect"]),
            kemasanKecilAcplUrl: Yii::$app->actionToUrl([KemasanController::class, "actionSelect"]),
            sediaanAcplUrl:      Yii::$app->actionToUrl([SediaanController::class, "actionSelect"]),
        );
        return $view->__toString();
    }
}
