<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\DashboardEksekutifController as Pair;
use tlm\his\FatmaPharmacy\views\DashboardEksekutifUi\{KonsumsiObat, Table};
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
class DashboardEksekutifUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable = Yii::$app->canAccess([SignaController::class, "actionSearchStok"])
            || Yii::$app->canAccess([Pair::class, "actionCekRencana"])
            || Yii::$app->canAccess([Pair::class, "actionCekRealisasi"])
            || Yii::$app->canAccess([Pair::class, "actionCekPlDetailPemesanan"]);
        $canSeeGrafikObat = Yii::$app->canAccess([Pair::class, "actionGrafikObatData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Dashboard Eksekutif";
        $index->tabs = [
            ["title" => "Table",       "canSee" => $canSeeTable,      "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "Grafik Obat", "canSee" => $canSeeGrafikObat, "registerId" => Yii::$app->actionToId([self::class, "actionGrafikObat"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/dashboardeksekutif.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:             Yii::$app->actionToId(__METHOD__),
            actionUrl:              Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            stokDataUrl:            Yii::$app->actionToUrl([SignaController::class, "actionSearchStok"]),
            rencanaDataUrl:         Yii::$app->actionToUrl([Pair::class, "actionCekRencana"]),
            realisasiDataUrl:       Yii::$app->actionToUrl([Pair::class, "actionCekRealisasi"]),
            pemesananDataUrl:       Yii::$app->actionToUrl([Pair::class, "actionCekPlPemesanan"]),
            detailPemesananDataUrl: Yii::$app->actionToUrl([Pair::class, "actionCekPlDetailPemesanan"]),
            depoSelect:             Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
            bulanSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/dashboardeksekutif.php#grafikobat    the original method
     */
    public function actionGrafikObat(): string
    {
        $view = new KonsumsiObat(
            registerId: Yii::$app->actionToId(__METHOD__),
            dataUrl:    Yii::$app->actionToUrl([Pair::class, "actionGrafikObatData"]),
        );
        return $view->__toString();
    }
}
