<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\PasienController as Pair;
use tlm\his\FatmaPharmacy\views\PasienUi\{CariRekamMedisForm, Form};
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
class PasienUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm = Yii::$app->canAccess([Pair::class, "actionAdd"]);
        $canSeeFormRekamMedis = Yii::$app->canAccess([PenjualanController::class, "actionTestBridgeBaru"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Pasien";
        $index->tabs = [
            ["title" => "Form",             "canSee" => $canSeeForm,           "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Rekam Medis", "canSee" => $canSeeFormRekamMedis, "registerId" => Yii::$app->actionToId([self::class, "actionCariRekamMedisForm"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/pasien.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:       Yii::$app->actionToId(__METHOD__),
            addAccess:        [true],
            addActionUrl:     Yii::$app->actionToUrl([Pair::class, "actionAdd"]),
            addPasienUrl:     Yii::$app->actionToUrl([Pair::class, "actionAdd"]), // TODO: php: uncategorized: confirm this with old revision
            kotaUrl:          Yii::$app->actionToUrl([KotaController::class, "actionDropdown"]),
            kecamatanUrl:     Yii::$app->actionToUrl([KecamatanController::class, "actionDropdown"]),
            kelurahanUrl:     Yii::$app->actionToUrl([KelurahanController::class, "actionDropdown"]),
            propinsiSelect:   Yii::$app->actionToUrl([ProvinsiController::class, "actionSelect1Data"]),
            pekerjaanSelect:  Yii::$app->actionToUrl([PekerjaanController::class, "actionSelect1Data"]),
            agamaSelect:      Yii::$app->actionToUrl([AgamaController::class, "actionSelect1Data"]),
            pendidikanSelect: Yii::$app->actionToUrl([PendidikanController::class, "actionSelect1Data"]),
            negaraSelect:     Yii::$app->actionToUrl([NegaraController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/signa.php#cek_rm    the original method
     */
    public function actionCariRekamMedisForm(): string
    {
        $view = new CariRekamMedisForm(
            registerId:        Yii::$app->actionToId(__METHOD__),
            cariRekamMedisUrl: Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridgeBaru"]),
        );
        return $view->__toString();
    }
}
