<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\EresepDepoDrController as Pair;
use tlm\his\FatmaPharmacy\views\EresepDepoDrUi\{Form, FormEdit, FormPrintEtiket, TableResep, ViewStruk};
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
class EresepDepoDrUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormEdit        = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeForm            = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeFormPrintEtiket = Yii::$app->canAccess([Pair::class, "actionEtiket"]);
        $canSeeTableResep      = Yii::$app->canAccess([Pair::class, "actionTableResepData"]);
        $canSeeViewStruk       = Yii::$app->canAccess([Pair::class, "actionViewStrukData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Eresep Depo Dr";
        $index->tabs = [
            ["title" => "Form Edit",         "canSee" => $canSeeFormEdit,        "registerId" => Yii::$app->actionToId([self::class, "actionFormEdit"])],
            ["title" => "Form",              "canSee" => $canSeeForm,            "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Print Etiket", "canSee" => $canSeeFormPrintEtiket, "registerId" => Yii::$app->actionToId([self::class, "actionFormPrintEtiket"])],
            ["title" => "Table Resep",       "canSee" => $canSeeTableResep,      "registerId" => Yii::$app->actionToId([self::class, "actionTableResep"])],
            ["title" => "View Struk",        "canSee" => $canSeeViewStruk,       "registerId" => Yii::$app->actionToId([self::class, "actionViewStruk"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#edit    the original method
     */
    public function actionFormEdit(): string
    {
        $view = new FormEdit(
            registerId:             Yii::$app->actionToId(__METHOD__),
            addAccess:              [true],
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:              Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            rekamMedisAcplUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            registrasiAjaxUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            cekResepUrl:            Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            namaAcplUrl:            Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge33"]),
            obatAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:               Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            signaAcplUrl:           Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:            Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            testBridgeCekKeluarUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeCekKeluar"]),
            jenisResepSelect:       Yii::$app->actionToUrl([JenisResepController::class, "actionSelect3Data"]),
            caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#index    the original method
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
            dokterAcplUrl:          Yii::$app->actionToUrl([DokterController::class, "actionTypeahead2"]),
            testBridgeCekKeluarUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeCekKeluar"]),
            poliAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionTypeaheadPoli"]),
            obatAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:               Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            signaAcplUrl:           Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:            Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            viewStrukWidgetId:      Yii::$app->actionToUrl([self::class, "actionViewStruk"]),
            cariRekamMedisWidgetId: Yii::$app->actionToId([PasienUiController::class, "actionCariRekamMedisForm"]),
            jenisResepSelect:       Yii::$app->actionToUrl([JenisResepController::class, "actionSelect1Data"]),
            caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#etiket    the original method
     */
    public function actionFormPrintEtiket(): string
    {
        $view = new FormPrintEtiket(
            registerId:    Yii::$app->actionToId(__METHOD__),
            printAccess:   [true],
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionEtiket"]),
            printerSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelectPrinter1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#listresep    the original method
     */
    public function actionTableResep(): string
    {
        $view = new TableResep(
            registerId:          Yii::$app->actionToId(__METHOD__),
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionTableResepData"]),
            verifikasiUrl:       Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"]),
            transferUrl:         Yii::$app->actionToUrl([Pair::class, "actionTransfer"]),
            deleteUrl:           Yii::$app->actionToUrl([Pair::class, "actionDelete"]),
            formEditWidgetId:    Yii::$app->actionToId([self::class, "actionFormEdit"]),
            formReturWidgetId:   Yii::$app->actionToId([ReturDepoUiController::class, "actionFormEdit"]),
            viewWidgetId:        Yii::$app->actionToId([self::class, "actionViewStruk"]),
            resepIghWidgetId:    Yii::$app->actionToId([Pair::class, "actionCetakResep"]),
            resepDokterWidgetId: Yii::$app->actionToId([Pair::class, "actionCetakResepDr"]),
            depoSelect:          Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#cetakstruk    the original method
     */
    public function actionViewStruk(): string
    {
        $view = new ViewStruk(
            registerId:              Yii::$app->actionToId(__METHOD__),
            dataUrl:                 Yii::$app->actionToUrl([Pair::class, "actionViewStrukData"]),
            actionUrl:               Yii::$app->actionToUrl([Pair::class, "___"]), // MISSING form processing in original action: "eresepdepodr.php#cetakstruk"
            verifikasiUrl:           Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"]),
            ceklisResepUrl:          Yii::$app->actionToUrl([EresepDepoController::class, "actionCeklisResep"]),
            transferUrl:             Yii::$app->actionToUrl([EresepDepoController::class, "actionTransfer"]),
            formEditWidgetId:        Yii::$app->actionToId([self::class, "actionFormEdit"]),
            formPrintEtiketWidgetId: Yii::$app->actionToId([self::class, "actionFormPrintEtiket"]),
            tableWidgetId:           Yii::$app->actionToId([self::class, "actionForm"]),
            tableResepWidgetId:      Yii::$app->actionToId([ResepGabunganUiController::class, "actionTableResep"]),
            cetakStrukWidgetId:      Yii::$app->actionToId([Pair::class, "actionCetakStruk3"]),
            cetakStrukLqWidgetId:    Yii::$app->actionToId([EresepDepoController::class, "actionCetakStrukLq"]),
        );
        return $view->__toString();
    }
}
