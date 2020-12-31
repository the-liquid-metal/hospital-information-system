<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanPenerimaanController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanPenerimaanUi\{FormDepo, FormGasMedis, FormPenerimaan, FormPerPemasok};
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
class LaporanPenerimaanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormPenerimaan = Yii::$app->canAccess([Pair::class, "actionReportPenerimaanRekap"])
                             || Yii::$app->canAccess([Pair::class, "actionReportPenerimaanBukuInduk"]);
        $canSeeFormPerPemasok = Yii::$app->canAccess([Pair::class, "actionReportPerPemasok"]);
        $canSeeFormGasMedis   = Yii::$app->canAccess([Pair::class, "actionReportGasMedisPerJenisBarang"])
                             || Yii::$app->canAccess([Pair::class, "actionReportGasMedisPerItemBarang"])
                             || Yii::$app->canAccess([Pair::class, "actionReportGasMedisBukuInduk"]);
        $canSeeFormDepo       = Yii::$app->canAccess([Pair::class, "actionReportDepo"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Penerimaan";
        $index->tabs = [
            ["title" => "Penerimaan",  "canSee" => $canSeeFormPenerimaan, "registerId" => Yii::$app->actionToId([self::class, "actionFormPenerimaan"])],
            ["title" => "Per Pemasok", "canSee" => $canSeeFormPerPemasok, "registerId" => Yii::$app->actionToId([self::class, "actionFormPerPemasok"])],
            ["title" => "Gas Medis",   "canSee" => $canSeeFormGasMedis,   "registerId" => Yii::$app->actionToId([self::class, "actionFormGasMedis"])],
            ["title" => "Depo",        "canSee" => $canSeeFormDepo,       "registerId" => Yii::$app->actionToId([self::class, "actionFormDepo"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#reports    the original method
     * last exist of actionReports: commit-e37d34f4
     */
    public function actionFormPenerimaan(): string
    {
        $view = new FormPenerimaan(
            registerId:         Yii::$app->actionToId(__METHOD__),
            rekapActionUrl:     Yii::$app->actionToUrl([Pair::class, "actionReportPenerimaanRekap"]),
            bukuIndukActionUrl: Yii::$app->actionToUrl([Pair::class, "actionReportPenerimaanBukuInduk"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#reports    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#LaporanPerPbf    the original method (not exist)
     * last exist of actionReports: commit-e37d34f4
     * last exist of actionLaporanPerPbf: commit-e37d34f4
     */
    public function actionFormPerPemasok(): string
    {
        $view = new FormPerPemasok(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportPerPemasok"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#reports    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#RekapPenerimaanGas    the original method
     * first exist of actionRekapPenerimaanGas: commit-75b46456
     * last exist of actionReports: commit-e37d34f4
     * last exist of actionRekapPenerimaanGas: commit-e37d34f4
     */
    public function actionFormGasMedis(): string
    {
        $view = new FormGasMedis(
            registerId:              Yii::$app->actionToId(__METHOD__),
            jenisBarangActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionReportGasMedisPerJenisBarang"]),
            itemBarangActionUrl:     Yii::$app->actionToUrl([Pair::class, "actionReportGasMedisPerItemBarang"]),
            bukuIndukActionUrl:      Yii::$app->actionToUrl([Pair::class, "actionReportGasMedisBukuInduk"]),
            gudangPenyimpananSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect7Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#rekappenerimaan    the original method (not exist)
     * last exist of actionRekapPenerimaan: commit-e37d34f4
     */
    public function actionFormDepo(): string
    {
        $view = new FormDepo(
            registerId:         Yii::$app->actionToId(__METHOD__),
            actionUrl:          Yii::$app->actionToUrl([Pair::class, "actionReportDepo"]),
            depoPenerimaSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
            depoAsalSelect:     Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }
}
