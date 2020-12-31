<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanPengeluaranController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanPengeluaranUi\{FormGasMedis, FormPengeluaran, FormPerTujuan};
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
class LaporanPengeluaranUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormPengeluaran = Yii::$app->canAccess([Pair::class, "actionReportPengeluaran"]);
        $canSeeFormPerTujuan = Yii::$app->canAccess([Pair::class, "actionReportPerTujuan"]);
        $canSeeFormGasMedis = Yii::$app->canAccess([Pair::class, "actionReportGasMedis"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Pengeluaran";
        $index->tabs = [
            ["title" => "Pengeluaran", "canSee" => $canSeeFormPengeluaran, "registerId" => Yii::$app->actionToId([self::class, "actionFormPengeluaran"])],
            ["title" => "Per Tujuan",  "canSee" => $canSeeFormPerTujuan,   "registerId" => Yii::$app->actionToId([self::class, "actionFormPerTujuan"])],
            ["title" => "Gas Medis",   "canSee" => $canSeeFormGasMedis,    "registerId" => Yii::$app->actionToId([self::class, "actionFormGasMedis"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#rekap_harian    the original method
     * last exist of actionRekapHarian: commit-e37d34f4
     */
    public function actionFormPengeluaran(): string
    {
        $view = new FormPengeluaran(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportPengeluaran"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#rekap    the original method
     * last exist of actionRekap: commit-e37d34f4
     */
    public function actionFormPerTujuan(): string
    {
        $view = new FormPerTujuan(
            registerId:       Yii::$app->actionToId(__METHOD__),
            actionUrl:        Yii::$app->actionToUrl([Pair::class, "actionReportPerTujuan"]),
            depoAsalSelect:   Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
            depoTujuanSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#rekapgm    the original method
     * last exist of actionRekapGm: commit-e37d34f4
     */
    public function actionFormGasMedis(): string
    {
        $view = new FormGasMedis(
            registerId:       Yii::$app->actionToId(__METHOD__),
            actionUrl:        Yii::$app->actionToUrl([Pair::class, "actionReportGasMedis"]),
            depoAsalSelect:   Yii::$app->actionToUrl([DepoController::class, "actionSelect7Data"]),
            depoTujuanSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect2Data"]),
        );
        return $view->__toString();
    }
}
