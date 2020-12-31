<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanUi\{
    LaporanDepoJualIndexNew,
    LaporanIki,
    LaporanIkiIrna,
    PrintRekapitulasiSetoranHarian,
};
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
class LaporanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeDepoJual = Yii::$app->canAccess([LaporanPenjualanController::class, "actionReportTanpaHarga"]);
        $canSeeIki = Yii::$app->canAccess([Pair::class, "actionIki2"]);
        $canSeeIkiIrna = Yii::$app->canAccess([Pair::class, "actionIki2irna"]);
        $canSeeKasir = Yii::$app->canAccess([Pair::class, "actionKasir2"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan";
        $index->tabs = [
            ["title" => "Depo Jual", "canSee" => $canSeeDepoJual, "registerId" => Yii::$app->actionToId([self::class, "actionDepoJualIndex"])],
            ["title" => "Iki",       "canSee" => $canSeeIki,      "registerId" => Yii::$app->actionToId([self::class, "actionIki"])],
            ["title" => "Iki Irna",  "canSee" => $canSeeIkiIrna,  "registerId" => Yii::$app->actionToId([self::class, "actionIkiIrna"])],
            ["title" => "Kasir",     "canSee" => $canSeeKasir,    "registerId" => Yii::$app->actionToId([self::class, "actionKasir"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#depojualindex    the original method
     */
    public function actionDepoJualIndex(): string
    {
        $view = new LaporanDepoJualIndexNew(
            registerId:       Yii::$app->actionToId(__METHOD__),
            actionUrl:        Yii::$app->actionToUrl([LaporanPenjualanController::class, "actionReportTanpaHarga"]),
            depoSelect:       Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
            ruangRawatSelect: Yii::$app->actionToUrl([RuangRawatController::class, "actionSelect2Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#iki    the original method
     */
    public function actionIki(): string
    {
        $view = new LaporanIki(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionIki2"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#ikiirna    the original method
     */
    public function actionIkiIrna(): string
    {
        $view = new LaporanIkiIrna(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionIki2irna"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#kasir    the original method
     */
    public function actionKasir(): string
    {
        $view = new PrintRekapitulasiSetoranHarian(
            registerId:          Yii::$app->actionToId(__METHOD__),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionKasir2"]),
            operatorKasirSelect: Yii::$app->actionToUrl([KasirController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }
}
