<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\PerencanaanController as Pair;
use tlm\his\FatmaPharmacy\views\PerencanaanUi\{Form, FormBulanan, FormReport, Table, TableRevisi, View, ViewRevisi};
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
class PerencanaanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable       = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeTableRevisi = Yii::$app->canAccess([Pair::class, "actionTableRevisiData"]);
        $canSeeForm        = Yii::$app->canAccess([Pair::class, "actionSaveAdd"]);
        $canSeeFormBulanan = Yii::$app->canAccess([Pair::class, "actionSaveBulanan"]);
        $canSeeFormRevisi  = Yii::$app->canAccess([Pair::class, "actionSaveRevisi"]);
        $canSeeView        = Yii::$app->canAccess([Pair::class, "actionViewData"]);
        $canSeeViewRevisi  = Yii::$app->canAccess([Pair::class, "actionViewRevisiData"]);
        $canSeeReport      = Yii::$app->canAccess([Pair::class, "actionSearchNoDocData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Perencanaan";
        $index->tabs = [
            ["title" => "Table",        "canSee" => $canSeeTable,       "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "Table Revisi", "canSee" => $canSeeTableRevisi, "registerId" => Yii::$app->actionToId([self::class, "actionTableRevisi"])],
            ["title" => "Form",         "canSee" => $canSeeForm,        "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Bulanan", "canSee" => $canSeeFormBulanan, "registerId" => Yii::$app->actionToId([self::class, "actionFormBulanan"])],
            ["title" => "Form Revisi",  "canSee" => $canSeeFormRevisi,  "registerId" => Yii::$app->actionToId([self::class, "actionFormRevisi"])],
            ["title" => "View",         "canSee" => $canSeeView,        "registerId" => Yii::$app->actionToId([self::class, "actionView"])],
            ["title" => "View Revisi",  "canSee" => $canSeeViewRevisi,  "registerId" => Yii::$app->actionToId([self::class, "actionViewRevisi"])],
            ["title" => "Report",       "canSee" => $canSeeReport,      "registerId" => Yii::$app->actionToId([self::class, "actionFormReport"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:                   Yii::$app->actionToId(__METHOD__),
            editAccess:                   [true],
            deleteAccess:                 [false],
            auditAccess:                  [false],
            dataUrl:                      Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            deleteUrl:                    Yii::$app->actionToUrl([Pair::class, "actionAjaxDelete"]),
            viewWidgetId:                 Yii::$app->actionToId([self::class, "actionView"]),
            printWidgetId:                Yii::$app->actionToId([Pair::class, "actionPrint"]),
            formBulananWidgetId:          Yii::$app->actionToId([self::class, "actionFormBulanan"]),
            formWidgetId:                 Yii::$app->actionToId([self::class, "actionForm"]),
            formRevisiWidgetId:           Yii::$app->actionToId([self::class, "actionFormRevisi"]),
            anggaranSelect:               Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            tipeDokumenPerencanaanSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectPerencanaan1Data"]),
            tipeDokumenBulanSelect:       Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:                  Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#revisi    the original method
     */
    public function actionTableRevisi(): string
    {
        $view = new TableRevisi(
            registerId:             Yii::$app->actionToId(__METHOD__),
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionTableRevisiData"]),
            viewWidgetId:           Yii::$app->actionToId([self::class, "actionView"]),
            subjenisAnggaranSelect: Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect1Data"]),
            tipeDokumenBulanSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#addmonthly    the original method
     */
    public function actionFormBulanan(): string
    {
        $view = new FormBulanan(
            registerId:          Yii::$app->actionToId(__METHOD__),
            editAccess:          [true],
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionEditMonthlyData"]),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionSaveBulanan"]),
            pembelianAcplUrl:    Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
            pemasokAcplUrl:      Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            cekUnikNoDokumenUrl: Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            cekStokUrl:          Yii::$app->actionToUrl([Pair::class, "actionCheckStock"]),
            detailSpbUrl:        Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonDetailSpb"]),
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#views    the original method
     */
    public function actionView(): string
    {
        $view = new View(
            registerId:              Yii::$app->actionToId(__METHOD__),
            dataUrl:                 Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            formWidgetId:            Yii::$app->actionToId([self::class, "actionForm"]),
            formBulananWidgetId:     Yii::$app->actionToId([self::class, "actionFormBulanan"]),
            printWidgetId:           Yii::$app->actionToId([Pair::class, "actionPrint"]),
            viewWidgetId:            Yii::$app->actionToId([self::class, "actionView"]),
            tableWidgetId:           Yii::$app->actionToId([self::class, "actionTable"]),
            viewPengadaanWidgetId:   Yii::$app->actionToId([PengadaanUiController::class, "actionView"]),
            tablePengadaanWidgetId:  Yii::$app->actionToId([PengadaanUiController::class, "actionTable"]),
            viewPembelianWidgetId:   Yii::$app->actionToId([PembelianUiController::class, "actionView"]),
            tablePembelianWidgetId:  Yii::$app->actionToId([PembelianUiController::class, "actionTable"]),
            tablePenerimaanWidgetId: Yii::$app->actionToId([PenerimaanUiController::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#views    the original method
     */
    public function actionViewRevisi(): string
    {
        $view = new ViewRevisi(
            registerId: Yii::$app->actionToId(__METHOD__),
            dataUrl:    Yii::$app->actionToUrl([Pair::class, "actionViewRevisiData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#reports    the original method
     */
    public function actionFormReport(): string
    {
        $view = new FormReport(
            registerId:     Yii::$app->actionToId(__METHOD__),
            dataUrl:        Yii::$app->actionToUrl([Pair::class, "actionSearchNoDocData"]),
            reportUrl:      Yii::$app->actionToUrl([Pair::class, "actionReportsData"]),
            katalogAcplUrl: Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSearch"]),
            viewWidgetId:   Yii::$app->actionToId([self::class, "actionView"]),
            bulanSelect:    Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:    Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#add    the original method
     */
    public function actionForm (): string
    {
        $view = new Form(
            registerId:          Yii::$app->actionToId(__METHOD__),
            addAccess:           [true],
            editAccess:          [true],
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionSaveAdd"]),
            cekUnikNoDokumenUrl: Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            cekStokUrl:          Yii::$app->actionToUrl([Pair::class, "actionCheckStock"]),
            subjenisAcplUrl:     Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSubjenis"]),
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#addrevisi    the original method
     */
    public function actionFormRevisi()
    {
        $this->render("addrevisi");
    }
}
