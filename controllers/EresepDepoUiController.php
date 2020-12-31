<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\EresepDepoController as Pair;
use tlm\his\FatmaPharmacy\views\EresepDepoUi\{Form, FormEdit, PrintEtiket, TableResep, ViewStruk};
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
class EresepDepoUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormEdit   = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeTableResep = Yii::$app->canAccess([Pair::class, "actionTableResepData"]);
        $canSeeViewStruk  = Yii::$app->canAccess([Pair::class, "actionViewStrukData"]);
        $canSeeForm       = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeFormEtiket = Yii::$app->canAccess([Pair::class, "actionEtiket"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Eresep Depo";
        $index->tabs = [
            ["title" => "Form Edit",   "canSee" => $canSeeFormEdit,   "registerId" => Yii::$app->actionToId([self::class, "actionFormEdit"])],
            ["title" => "Table Resep", "canSee" => $canSeeTableResep, "registerId" => Yii::$app->actionToId([self::class, "actionTableResep"])],
            ["title" => "View Struk",  "canSee" => $canSeeViewStruk,  "registerId" => Yii::$app->actionToId([self::class, "actionViewStruk"])],
            ["title" => "Form",        "canSee" => $canSeeForm,       "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Etiket", "canSee" => $canSeeFormEtiket, "registerId" => Yii::$app->actionToId([self::class, "actionFormEtiket"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#edit    the original method
     */
    public function actionFormEdit(): string
    {
        $view = new FormEdit(
            registerId:        Yii::$app->actionToId(__METHOD__),
            addAccess:         [true],
            dataUrl:           Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:         Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            rekamMedisAcplUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            registrasiAjaxUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            noRekamMedis3Url:  Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            dokterUrl:         Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeCekKeluar"]),
            namaAcplUrl:       Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge33"]),
            nama3Url:          Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            obatAcplUrl:       Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:          Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            signaAcplUrl:      Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:       Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            viewStrukWidgetId: Yii::$app->actionToId([self::class, "actionViewStruk"]),
            jenisResepSelect:  Yii::$app->actionToUrl([JenisResepController::class, "actionSelect3Data"]),
            caraBayarSelect:   Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#listresep    the original method
     */
    public function actionTableResep(): string
    {
        $view = new TableResep(
            registerId:        Yii::$app->actionToId(__METHOD__),
            dataUrl:           Yii::$app->actionToUrl([Pair::class, "actionTableResepData"]),
            verifikasiUrl:     Yii::$app->actionToUrl([Pair::class, "actionVerifikasi"]),
            transferUrl:       Yii::$app->actionToUrl([Pair::class, "actionTransfer"]),
            deleteUrl:         Yii::$app->actionToUrl([Pair::class, "actionDelete"]),
            formEditWidgetId:  Yii::$app->actionToId([self::class, "actionFormEdit"]),
            formReturWidgetId: Yii::$app->actionToId([ReturDepoUiController::class, "actionFormEdit"]),
            viewStrukWidgetId: Yii::$app->actionToId([self::class, "actionViewStruk"]),
            antrianWidgetId:   Yii::$app->actionToId([Pair::class, "actionAntrian2"]),
            depoSelect:        Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#cetakstruk    the original method
     */
    public function actionViewStruk(): string
    {
        $view = new ViewStruk(
            registerId:           Yii::$app->actionToId(__METHOD__),
            dataUrl:              Yii::$app->actionToUrl([Pair::class, "actionViewStrukData"]),
            actionUrl:            Yii::$app->actionToUrl([Pair::class, "___"]), // MISSING
            verifikasiUrl:        Yii::$app->actionToUrl([Pair::class, "actionVerifikasi"]),
            ceklisResepUrl:       Yii::$app->actionToUrl([Pair::class, "actionCeklisResep"]),
            transferUrl:          Yii::$app->actionToUrl([Pair::class, "actionTransfer"]),
            editFormWidgetId:     Yii::$app->actionToId([self::class, "actionFormEdit"]),
            cetakStruk3WidgetId:  Yii::$app->actionToId([Pair::class, "actionCetakStruk3"]),
            formWidgetId:         Yii::$app->actionToId([self::class, "actionForm"]),
            formEtiketWidgetId:   Yii::$app->actionToId([self::class, "actionFormEtiket"]),
            tableResepWidgetId:   Yii::$app->actionToId([ResepGabunganUiController::class, "actionTableResep"]),
            cetakStrukLqWidgetId: Yii::$app->actionToId([Pair::class, "actionCetakStrukLq"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#index    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:             Yii::$app->actionToId(__METHOD__),
            addAccess:              [true],
            actionUrl:              Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            rekamMedisAcplUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            registrasiAjaxUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            cekResepUrl:            Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            tempatTidurDataUrl:     Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeBaruRRawat"]),
            namaAcplUrl:            Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge33"]),
            poliAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionTypeaheadPoli"]),
            dokterAcplUrl:          Yii::$app->actionToUrl([DokterController::class, "actionTypeahead2"]),
            testBridgeCekKeluarUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeCekKeluar"]),
            obatAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:               Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            signaAcplUrl:           Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:            Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            viewStrukWidgetId:      Yii::$app->actionToId([self::class, "actionViewStruk"]),
            cariRekamMedisWidgetId: Yii::$app->actionToId([PasienUiController::class, "actionCariRekamMedisForm"]),
            jenisResepSelect:       Yii::$app->actionToUrl([JenisResepController::class, "actionSelect1Data"]),
            caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#etiket    the original method
     */
    public function actionFormEtiket(): string
    {
        $view = new PrintEtiket(
            registerId:    Yii::$app->actionToId(__METHOD__),
            printAccess:   [true],
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionEtiket"]),
            printerSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelectPrinter1Data"]),
        );
        return $view->__toString();
    }
}
