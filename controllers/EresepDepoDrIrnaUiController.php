<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\EresepDepoDrIrnaController as Pair;
use tlm\his\FatmaPharmacy\views\EresepDepoDrIrnaUi\{Form, PrintEtiket, TableResep, ViewStruk};
use tlm\libs\LowEnd\components\DateTimeException;
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
class EresepDepoDrIrnaUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm            = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeTableResep      = Yii::$app->canAccess([Pair::class, "actionListResepData"]);
        $canSeeFormPrintEtiket = Yii::$app->canAccess([Pair::class, "actionEtiket"]);
        $canSeeViewStruk       = Yii::$app->canAccess([Pair::class, "actionViewStrukData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Eresep Depo Dr Irna";
        $index->tabs = [
            ["title" => "Form",              "canSee" => $canSeeForm,            "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Table Resep",       "canSee" => $canSeeTableResep,      "registerId" => Yii::$app->actionToId([self::class, "actionTableResep"])],
            ["title" => "Form Print Etiket", "canSee" => $canSeeFormPrintEtiket, "registerId" => Yii::$app->actionToId([self::class, "actionFormPrintEtiket"])],
            ["title" => "View Struk",        "canSee" => $canSeeViewStruk,       "registerId" => Yii::$app->actionToId([self::class, "actionViewStruk"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#index    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:             Yii::$app->actionToId(__METHOD__),
            addAccess:              [true],
            actionUrl:              Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            rekamMedisAcplUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            registrasiAjaxUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            namaAcplUrl:            Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge33"]),
            cekResepUrl:            Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            tempatTidurDataUrl:     Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeBaruRRawat"]),
            poliAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionTypeaheadPoli"]),
            obatAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:               Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            dokterAcplUrl:          Yii::$app->actionToUrl([DokterController::class, "actionTypeahead2"]),
            signaAcplUrl:           Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:            Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            testBridgeCekKeluarUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeCekKeluar"]),
            viewStrukWidgetId:      Yii::$app->actionToId([self::class, "actionViewStruk"]),
            cariRekamMedisWidgetId: Yii::$app->actionToId([PasienUiController::class, "actionCariRekamMedisForm"]),
            jenisResepSelect:       Yii::$app->actionToUrl([JenisResepController::class, "actionSelect1Data"]),
            caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#listresep    the original method
     */
    public function actionTableResep(): string
    {
        $view = new TableResep(
            registerId:        Yii::$app->actionToId(__METHOD__),
            dataUrl:           Yii::$app->actionToUrl([Pair::class, "actionListResepData"]),
            verifikasiUrl:     Yii::$app->actionToUrl([EresepDepoDrController::class, "actionVerifikasi"]),
            transferUrl:       Yii::$app->actionToUrl([EresepDepoDrController::class, "actionTransfer"]),
            deleteUrl:         Yii::$app->actionToUrl([EresepDepoDrController::class, "actionDelete"]),
            formEditWidgetId:  Yii::$app->actionToId([EresepDepoDrUiController::class, "actionFormEdit"]),
            formReturWidgetId: Yii::$app->actionToId([ReturDepoUiController::class, "actionFormEdit"]),
            viewWidgetId:      Yii::$app->actionToId([EresepDepoDrUiController::class, "actionViewStruk"]),
            resepWidgetId:     Yii::$app->actionToId([EresepDepoDrController::class, "actionAntrian2"]),
            depoSelect:        Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#etiket    the original method
     */
    public function actionFormPrintEtiket(): string
    {
        $view = new PrintEtiket(
            registerId:    Yii::$app->actionToId(__METHOD__),
            printAccess:   [true],
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionEtiket"]),
            printerSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelectPrinter1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#cetakstruk    the original method
     */
    public function actionViewStruk(): string
    {
        $view = new ViewStruk(
            registerId:          Yii::$app->actionToId(__METHOD__),
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionViewStrukData"]),
            verifikasiUrl:       Yii::$app->actionToUrl([Pair::class, "actionVerifikasi"]),
            transferUrl:         Yii::$app->actionToUrl([Pair::class, "actionTransfer"]),
            formWidgetId:        Yii::$app->actionToId([EresepDepoDrUiController::class, "actionFormEdit"]),
            printWidgetId:       Yii::$app->actionToId([Pair::class, "actionCetakStruk3"]),
            tableWidgetId:       Yii::$app->actionToId([self::class, "actionForm"]),
            printEtiketWidgetId: Yii::$app->actionToId([self::class, "actionFormPrintEtiket"]),
            tableResepWidgetId:  Yii::$app->actionToId([ResepGabunganUiController::class, "actionTableResep"]),
            printStrukWidgetId:  Yii::$app->actionToId([EresepDepoController::class, "actionCetakStrukLq"]),
        );
        return $view->__toString();
    }
}
