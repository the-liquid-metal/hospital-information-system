<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\BillingController as Pair;
use tlm\his\FatmaPharmacy\views\BillingUi\Table;
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
class BillingUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable = Yii::$app->canAccess([Pair::class, "actionSearchJsonPasien"])
            || Yii::$app->canAccess([Pair::class, "actionSearchJsonHanyaResepTransfer"])
            || Yii::$app->canAccess([Pair::class, "actionSearchJsonSemuaResep"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Billing";
        $index->tabs = [
            ["title" => "Table", "canSee" => $canSeeTable, "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/billing.php#listbilling    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:                Yii::$app->actionToId(__METHOD__),
            verifikasiBillingUrl:      Yii::$app->actionToUrl([Pair::class, "actionVerifikasiBilling"]),
            unverifikasiBillingUrl:    Yii::$app->actionToUrl([Pair::class, "actionUnverifikasiBilling"]),
            dataPendaftaranUrl:        Yii::$app->actionToUrl([Pair::class, "actionSearchJsonPasien"]),
            dataHanyaResepTransferUrl: Yii::$app->actionToUrl([Pair::class, "actionSearchJsonHanyaResepTransfer"]),
            dataSemuaResepUrl:         Yii::$app->actionToUrl([Pair::class, "actionSearchJsonSemuaResep"]),
        );
        return $view->__toString();
    }
}
