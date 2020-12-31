<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\BrandController as Pair;
use tlm\his\FatmaPharmacy\views\BrandUi\{Form, Table};
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
class BrandUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm = Yii::$app->canAccess([Pair::class, "actionSave"], "add")
            || Yii::$app->canAccess([Pair::class, "actionSave"], "edit");
        $canSeeTable = Yii::$app->canAccess([Pair::class, "actionTableData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Brand";
        $index->tabs = [
            ["title" => "Form",  "canSee" => $canSeeForm,  "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Table", "canSee" => $canSeeTable, "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/brand.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:     Yii::$app->actionToId(__METHOD__),
            addAccess:      [true],
            editAccess:     [true],
            dataUrl:        Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            addActionUrl:   Yii::$app->actionToUrl([Pair::class, "actionSave"]),
            editActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSave"]),
            generikAcplUrl: Yii::$app->actionToUrl([GenerikController::class, "actionSelect"]),
            cekUnikKodeUrl: Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            cekUnikNamaUrl: Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/brand.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:      Yii::$app->actionToId(__METHOD__),
            editAccess:      Yii::$app->canAccess([Pair::class, "actionSave"], "edit"),
            deleteAccess:    Yii::$app->canAccess([Pair::class, "actionDelete"]),
            auditAccess:     [false],
            dataUrl:         Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            deleteUrl:       Yii::$app->actionToUrl([Pair::class, "actionDelete"]),
            companionFormId: Yii::$app->actionToId([self::class, "actionForm"]),
        );
        return $view->__toString();
    }
}
