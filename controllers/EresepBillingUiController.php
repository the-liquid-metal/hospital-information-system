<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\EresepBillingController as Pair;
use tlm\his\FatmaPharmacy\views\EresepBillingUi\{FormBayar, TableResep};
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
class EresepBillingUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormBayar = Yii::$app->canAccess([Pair::class, "actionCetak"]);
        $canSeeTableResep = Yii::$app->canAccess([Pair::class, "actionTableData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Eresep Billing";
        $index->tabs = [
            ["title" => "Form Bayar", "canSee" => $canSeeFormBayar, "registerId" => Yii::$app->actionToId([self::class, "actionFormBayar"])],
            ["title" => "Table Resep", "canSee" => $canSeeTableResep, "registerId" => Yii::$app->actionToId([self::class, "actionTableResep"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepbilling.php#editbayar    the original method
     */
    public function actionFormBayar(): string
    {
        $view = new FormBayar(
            registerId:        Yii::$app->actionToId(__METHOD__),
            addAccess:         [true],
            dataUrl:           Yii::$app->actionToUrl([Pair::class, "actionFormData"]),
            actionUrl:         Yii::$app->actionToUrl([Pair::class, "actionCetak"]),
            rekamMedisAcplUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge3"]),
            noRekamMedis3Url:  Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            pembayaranUrl:     Yii::$app->actionToUrl([Pair::class, "actionCariDetail"]),
            namaAcplUrl:       Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge33"]),
            registrasiAjaxUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            nama3Url:          Yii::$app->actionToUrl([SignaController::class, "actionCekResep"]),
            obatAcplUrl:       Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:          Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            signaAcplUrl:      Yii::$app->actionToUrl([Katalog3Controller::class, "actionCariSigna"]),
            stokDataUrl:       Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            viewStrukWidgetId: Yii::$app->actionToUrl([EresepDepoUiController::class, "actionViewStruk"]),
            jenisResepSelect:  Yii::$app->actionToUrl([JenisResepController::class, "actionSelect3Data"]),
            caraBayarSelect:   Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian3Data"]),
            pembayaranSelect:  Yii::$app->actionToUrl([PembayaranController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepbilling.php#listresep    the original method
     */
    public function actionTableResep(): string
    {
        $view = new TableResep(
            registerId:            Yii::$app->actionToId(__METHOD__),
            dataUrl:               Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            transferUrl:           Yii::$app->actionToUrl([EresepDepoController::class, "actionBayar"]),
            batalBayarUrl:         Yii::$app->actionToUrl([EresepDepoController::class, "actionBatalBayar"]),
            deleteUrl:             Yii::$app->actionToUrl([EresepDepoController::class, "actionDelete"]),
            formBayarWidgetId:     Yii::$app->actionToId([self::class, "actionFormBayar"]),
            cetakStrukWidgetId:    Yii::$app->actionToId([Pair::class, "actionCetakStruk"]),
            cetakStrukBriWidgetId: Yii::$app->actionToId([Pair::class, "actionCetakStrukBri"]),
            depoSelect:            Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),

            // unused
            // verifikasiUrl:      Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"]),
            // cetakStrukUrl:      Yii::$app->actionToUrl([Pair::class, "actionCetakStruk"]),
            // cetakStrukBriUrl:   Yii::$app->actionToUrl([Pair::class, "actionCetakStrukBri"]),
        );
        return $view->__toString();
    }
}
