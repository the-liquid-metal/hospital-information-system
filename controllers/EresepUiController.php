<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\EresepController as Pair;
use tlm\his\FatmaPharmacy\views\EresepUi\{Form, FormEdit, ViewAntrian};
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
class EresepUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeFormEdit = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeViewAntrian = Yii::$app->canAccess([Pair::class, "actionViewAntrianData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Eresep";
        $index->tabs = [
            ["title" => "Form",         "canSee" => $canSeeForm,        "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Edit",    "canSee" => $canSeeFormEdit,    "registerId" => Yii::$app->actionToId([self::class, "actionFormEdit"])],
            ["title" => "View Antrian", "canSee" => $canSeeViewAntrian, "registerId" => Yii::$app->actionToId([self::class, "actionViewAntrian"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#index    the original method
     */
    public function actionForm(): string
    {
        $iterArr = [];
        for ($i = 0; $i <= 10; $i++) {
            $iterArr[] = ["value" => $i, "label" => $i];
        }

        $view = new Form(
            registerId:             Yii::$app->actionToId(__METHOD__),
            addAccess:              [true],
            actionUrl:              Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            tempatTidurDataUrl:     Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeBaruRRawat"]),
            poliAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionTypeaheadPoli"]),
            dokterAcplUrl:          Yii::$app->actionToUrl([DokterController::class, "actionTypeahead2"]),
            namaDokter2Url:         Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeCekKeluar"]),
            namaAcplUrl:            Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge33"]),
            registrasiAjaxUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            nama3Url:               Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            rekamMedisAcplUrl:      Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            noRekamMedis3Url:       Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            obatAcplUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:               Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            signaAcplUrl:           Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:            Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            viewAntrianWidgetId:    Yii::$app->actionToId([self::class, "actionViewAntrian"]),
            cariRekamMedisWidgetId: Yii::$app->actionToId([PasienUiController::class, "actionCariRekamMedisForm"]),
            jenisResepSelect:       Yii::$app->actionToUrl([JenisResepController::class, "actionSelect3Data"]),
            caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian3Data"]),
            iterSelect:             json_encode($iterArr),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#edit    the original method
     */
    public function actionFormEdit(): string
    {
        $view = new FormEdit(
            registerId:          Yii::$app->actionToId(__METHOD__),
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            dokterUrl:           Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeCekKeluar"]),
            rekamMedisAcplUrl:   Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            registrasiAjaxUrl:   Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            noRekamMedis3Url:    Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            namaAcplUrl:         Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge33"]),
            nama3Url:            Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            obatAcplUrl:         Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:            Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            signaAcplUrl:        Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:         Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            viewAntrianWidgetId: Yii::$app->actionToId([self::class, "actionViewAntrian"]),
            jenisResepSelect:    Yii::$app->actionToUrl([JenisResepController::class, "actionSelect3Data"]),
            caraBayarSelect:     Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#antrian    the original method
     */
    public function actionViewAntrian(): string
    {
        $view = new ViewAntrian(
            dataUrl:           Yii::$app->actionToUrl([Pair::class, "actionViewAntrianData"]),
            eresepFormUrl:     Yii::$app->actionToUrl([self::class, "actionFormEdit"]),
            eresepAntrian2Url: Yii::$app->actionToUrl([Pair::class, "actionAntrian2"]),
            eresepTableUrl:    Yii::$app->actionToUrl([self::class, "actionForm"]),
        );
        return $view->__toString();
    }
}
