<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\SignaUi\RekamMedisOp;
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
class SignaUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeRekamMedis = Yii::$app->canAccess([PenjualanController::class, "actionTestBridgeBaru"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Signa";
        $index->tabs = [
            ["title" => "Rekam Medis", "canSee" => $canSeeRekamMedis, "registerId" => Yii::$app->actionToId([self::class, "actionCekRmOp"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/signa.php#cek_rm_op    the original method
     */
    public function actionCekRmOp(): string
    {
        $view = new RekamMedisOp(
            registerId: Yii::$app->actionToId(__METHOD__),
            dataUrl:    Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeBaru"]),
        );
        return $view->__toString();
    }
}
