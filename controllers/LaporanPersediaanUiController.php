<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanPersediaanController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanPersediaanUi\{
    Form30Juni,
    Form30Sept,
    Form31Des,
    Form31Maret2016,
    FormJuni2016,
    FormSept2016
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
class LaporanPersediaanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm30juni = Yii::$app->canAccess([Pair::class, "actionReport30juni"]);
        $canSeeForm30sept = Yii::$app->canAccess([Pair::class, "actionReport30sept"]);
        $canSeeForm31des = Yii::$app->canAccess([Pair::class, "actionReport31des"]);
        $canSeeForm31maret2016 = Yii::$app->canAccess([Pair::class, "actionReport31maret2016"]);
        $canSeeFormJuni2016 = Yii::$app->canAccess([Pair::class, "actionReportJuni2016"]);
        $canSeeFormSept2016 = Yii::$app->canAccess([Pair::class, "actionReportSept2016"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Persediaan";
        $index->tabs = [
            ["title" => "30 juni",       "canSee" => $canSeeForm30juni,      "registerId" => Yii::$app->actionToId([self::class, "actionForm30juni"])],
            ["title" => "30 sept",       "canSee" => $canSeeForm30sept,      "registerId" => Yii::$app->actionToId([self::class, "actionForm30sept"])],
            ["title" => "31 des",        "canSee" => $canSeeForm31des,       "registerId" => Yii::$app->actionToId([self::class, "actionForm31des"])],
            ["title" => "31 maret 2016", "canSee" => $canSeeForm31maret2016, "registerId" => Yii::$app->actionToId([self::class, "actionForm31maret2016"])],
            ["title" => "Juni 2016",     "canSee" => $canSeeFormJuni2016,    "registerId" => Yii::$app->actionToId([self::class, "actionFormJuni2016"])],
            ["title" => "Sept 2016",     "canSee" => $canSeeFormSept2016,    "registerId" => Yii::$app->actionToId([self::class, "actionFormSept2016"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstok    the original method
     * last exist of actionJumlahStok: commit-e37d34f4
     */
    public function actionForm30juni(): string
    {
        $view = new Form30Juni(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReport30juni"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstokseptbu    the original method
     * last exist of actionJumlahStokSeptBu: commit-e37d34f4
     */
    public function actionForm30sept(): string
    {
        $view = new Form30Sept(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReport30sept"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstokdesbu    the original method
     * last exist of actionJumlahStokDesBu: commit-e37d34f4
     */
    public function actionForm31des(): string
    {
        $view = new Form31Des(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReport31des"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstokmaret16bu    the original method
     * last exist of actionJumlahStokMaret16Bu: commit-e37d34f4
     */
    public function actionForm31maret2016(): string
    {
        $view = new Form31Maret2016(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReport31maret2016"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstokjuni16bu    the original method
     * last exist of actionJumlahStokJuni16Bu: commit-e37d34f4
     */
    public function actionFormJuni2016(): string
    {
        $view = new FormJuni2016(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportJuni2016"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstoksept16bu    the original method
     * last exist of actionJumlahStokSept16Bu: commit-e37d34f4
     */
    public function actionFormSept2016(): string
    {
        $view = new FormSept2016(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportSept2016"]),
            depoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelect4Data"]),
        );
        return $view->__toString();
    }
}
