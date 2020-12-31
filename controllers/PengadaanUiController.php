<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\PengadaanController as Pair;
use tlm\his\FatmaPharmacy\views\PengadaanUi\{Form, Report, Table, View};
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
class PengadaanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeForm  = Yii::$app->canAccess([Pair::class, "actionSaveAdd"]);
        $canSeeView  = Yii::$app->canAccess([Pair::class, "actionViewData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Pengadaan";
        $index->tabs = [
            ["title" => "Table", "canSee" => $canSeeTable, "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "Form",  "canSee" => $canSeeForm,  "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "View",  "canSee" => $canSeeView,  "registerId" => Yii::$app->actionToId([self::class, "actionView"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:             Yii::$app->actionToId(__METHOD__),
            editAccess:             [true],
            deleteAccess:           [true],
            auditAccess:            [true],
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            deleteUrl:              Yii::$app->actionToUrl([Pair::class, "actionAjaxDelete"]),
            viewWidgetId:           Yii::$app->actionToId([self::class, "actionView"]),
            printWidgetId:          Yii::$app->actionToId([Pair::class, "actionPrint"]),
            formWidgetId:           Yii::$app->actionToId([self::class, "actionForm"]),
            anggaranSelect:         Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            tipeDokumenBulanSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:          Yii::$app->actionToId(__METHOD__),
            editAccess:          [true],
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionFormData"]),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionSaveAdd"]),
            cekUnikNoDokumenUrl: Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            pemasokAcplUrl:      Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            perencanaanUrl:      Yii::$app->actionToUrl([PerencanaanController::class, "actionSearchJsonLainnya"]),
            detailHpsUrl:        Yii::$app->actionToUrl([PerencanaanController::class, "actionSearchHtmlDetailHps"]),
            viewWidgetId:        Yii::$app->actionToId([self::class, "actionView"]),
            printWidgetId:       Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:       Yii::$app->actionToId([self::class, "actionTable"]),
            jenisAnggaranSelect: Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            bulanSelect:         Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:         Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:    Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:    Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:     Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#views    the original method
     */
    public function actionView(): string
    {
        $view = new View(
            registerId:              Yii::$app->actionToId(__METHOD__),
            dataUrl:                 Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            formWidgetId:            Yii::$app->actionToId([self::class, "actionForm"]),
            printWidgetId:           Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:           Yii::$app->actionToId([self::class, "actionTable"]),
            viewPerencanaanWidgetId: Yii::$app->actionToId([PerencanaanUiController::class, "actionView"]),
            tablePembelianWidgetId:  Yii::$app->actionToId([PembelianUiController::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#Reports    the original method (not exist)
     *
     * NOTE: malformed action. there is TRUELY no form submission prosess.
     */
    public function actionReports(): string
    {
        $post = Yii::$app->request->post();
        if ($post["submit"]) {
            // TODO: php: to be deleted.
            return print_r($post, true);
        }

        $view = new Report(
            registerId:          Yii::$app->actionToId(__METHOD__),
            actionUrl:           Yii::$app->actionToUrl([self::class, "actionReports"]),
            jenisAnggaranSelect: Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }
}
