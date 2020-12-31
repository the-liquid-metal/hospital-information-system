<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanGenerikController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanGenerikUi\FormLaporan;
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
class LaporanGenerikUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormLaporan = Yii::$app->canAccess([Pair::class, "actionReportLaporan"]);
        $canSeeFormRekap = Yii::$app->canAccess([self::class, "actionFormRekap"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Generik";
        $index->tabs = [
            ["title" => "Form Laporan", "canSee" => $canSeeFormLaporan, "registerId" => Yii::$app->actionToId([self::class, "actionFormLaporan"])],
            ["title" => "Form Rekap",   "canSee" => $canSeeFormRekap,   "registerId" => Yii::$app->actionToId([self::class, "actionFormRekap"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#generik    the original method
     * last exist of actionGenerik: commit-e37d34f4
     */
    public function actionFormLaporan(): string
    {
        $view = new FormLaporan(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportLaporan"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#generikrekap    the original method
     * last exist of actionGenerikRekap: commit-e37d34f4
     */
    public function actionFormRekap(): string
    {
        return $this->renderPartial("laporan-generik-rekap");
    }
}
