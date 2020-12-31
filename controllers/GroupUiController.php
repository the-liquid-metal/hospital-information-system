<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\GroupController as Pair;
use tlm\his\FatmaPharmacy\views\GroupUi\{Form, FormEdit, Table};
use tlm\libs\LowEnd\components\DateTimeException;
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
class GroupUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm = Yii::$app->canAccess([Pair::class, "actionAdd"]);
        $canSeeFormEdit = Yii::$app->canAccess([Pair::class, "actionEdit"]);
        $canSeeTable = Yii::$app->canAccess([Pair::class, "actionTableData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Group";
        $index->tabs = [
            ["title" => "Form",      "canSee" => $canSeeForm,     "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Edit", "canSee" => $canSeeFormEdit, "registerId" => Yii::$app->actionToId([self::class, "actionFormEdit"])],
            ["title" => "Table",     "canSee" => $canSeeTable,    "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/group.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:    Yii::$app->actionToId(__METHOD__),
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionAdd"]),
            instalasiUrl:  Yii::$app->actionToUrl([PoliController::class, "actionDropdown"]),
            tableWidgetId: Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/group.php#edit    the original method
     */
    public function actionFormEdit(): string
    {
        $view = new FormEdit(
            registerId:    Yii::$app->actionToId(__METHOD__),
            dataUrl:       Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionEdit"]),
            instalasiUrl:  Yii::$app->actionToUrl([PoliController::class, "actionDropdown"]),
            tableWidgetId: Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/group.php#index    the original method
     */
    public function actionTable(): string
    {
        $view =  new Table(
            registerId:      Yii::$app->actionToId(__METHOD__),
            editAccess:      Yii::$app->canAccess([Pair::class, "actionEdit"]),
            deleteAccess:    Yii::$app->actionToUrl([Pair::class, "actionDelete"]), // TODO: php: missing method: TRUELY MISSING GroupController::actionDelete
            dataUrl:         Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            deleteUrl:       Yii::$app->actionToUrl([Pair::class, "actionDelete"]), // TODO: php: missing method: TRUELY MISSING GroupController::actionDelete
            companionFormId: Yii::$app->actionToId([self::class, "actionFormEdit"]),
            formWidgetId:    Yii::$app->actionToId([self::class, "actionForm"]),
        );
        return $view->__toString();
    }
}
