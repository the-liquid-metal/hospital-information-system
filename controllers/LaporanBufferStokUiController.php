<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanBufferStokController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanBufferStokUi\{FormDepo, FormFarmasi};
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
class LaporanBufferStokUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormFarmasi = Yii::$app->canAccess([Pair::class, "actionBufferFarmasiData"]);
        $canSeeFormDepo = Yii::$app->canAccess([Pair::class, "actionBufferDepoData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Buffer Stok";
        $index->tabs = [
            ["title" => "Form Farmasi", "canSee" => $canSeeFormFarmasi, "registerId" => Yii::$app->actionToId([self::class, "actionFormFarmasi"])],
            ["title" => "Form Depo",    "canSee" => $canSeeFormDepo,    "registerId" => Yii::$app->actionToId([self::class, "actionFormDepo"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#bufferfarmasi    the original method
     */
    public function actionFormFarmasi(): string
    {
        $view = new FormFarmasi(
            registerId:              Yii::$app->actionToId(__METHOD__),
            auditAccess:             [true],
            dataUrl:                 Yii::$app->actionToUrl([Pair::class, "actionBufferFarmasiData"]),
            exportUrl:               Yii::$app->actionToUrl([Pair::class, "actionExportBufferFarmasi"]),
            riwayatPenjualanDataUrl: Yii::$app->actionToUrl([MutasiController::class, "actionRiwayatPenjualan"]),
            generikAcplUrl:          Yii::$app->actionToUrl([GenerikController::class, "actionSelect"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#bufferdepo    the original method
     */
    public function actionFormDepo(): string
    {
        $view = new FormDepo(
            registerId:               Yii::$app->actionToId(__METHOD__),
            auditAccess:              [true],
            dataUrl:                  Yii::$app->actionToUrl([Pair::class, "actionBufferDepoData"]),
            exportUrl:                Yii::$app->actionToUrl([Pair::class, "actionExportBufferDepo"]),
            riwayatPenjualanWidgetId: Yii::$app->actionToId([MutasiController::class, "actionRiwayatPenjualanDepo"]),
            depoSelect:               Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }
}
