<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanPenjualanController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanPenjualanUi\{FormPenjualan, FormTanpaHarga};
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
class LaporanPenjualanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormPenjualan = Yii::$app->canAccess([Pair::class, "actionReportPenjualan"]);
        $canSeeFormTanpaHarga = Yii::$app->canAccess([Pair::class, "actionReportTanpaHarga"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Penjualan";
        $index->tabs = [
            ["title" => "Penjualan",   "canSee" => $canSeeFormPenjualan,  "registerId" => Yii::$app->actionToId([self::class, "actionFormPenjualan"])],
            ["title" => "Tanpa Harga", "canSee" => $canSeeFormTanpaHarga, "registerId" => Yii::$app->actionToId([self::class, "actionFormTanpaHarga"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#depo    the original method
     * last exist of actionDepo: commit-e37d34f4
     */
    public function actionFormPenjualan(): string
    {
        $view = new FormPenjualan(
            registerId:       Yii::$app->actionToId(__METHOD__),
            actionUrl:        Yii::$app->actionToUrl([Pair::class, "actionReportPenjualan"]),
            depoSelect:       Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
            ruangRawatSelect: Yii::$app->actionToUrl([RuangRawatController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#depojualindex    the original method
     */
    public function actionFormTanpaHarga(): string
    {
        $view = new FormTanpaHarga(
            registerId:       Yii::$app->actionToId(__METHOD__),
            actionUrl:        Yii::$app->actionToUrl([Pair::class, "actionReportTanpaHarga"]),
            depoSelect:       Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
            ruangRawatSelect: Yii::$app->actionToUrl([RuangRawatController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }
}
