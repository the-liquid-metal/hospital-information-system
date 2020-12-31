<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\KonsinyasiController as Pair;
use tlm\his\FatmaPharmacy\views\KonsinyasiUi\{Form, FormProceed, FormResep, Table, View};
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
class KonsinyasiUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable       = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeForm        = Yii::$app->canAccess([Pair::class, "actionSaveAdd"]);
        $canSeeFormProceed = Yii::$app->canAccess([Pair::class, "actionSaveProceed"]);
        $canSeeFormResep   = Yii::$app->canAccess([Pair::class, "actionSaveResep"]);
        $canSeeView        = Yii::$app->canAccess([Pair::class, "actionViewData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Konsinyasi";
        $index->tabs = [
            ["title" => "Table Penerimaan", "canSee" => $canSeeTable,       "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "Form",             "canSee" => $canSeeForm,        "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Proceed",     "canSee" => $canSeeFormProceed, "registerId" => Yii::$app->actionToId([self::class, "actionFormProceed"])],
            ["title" => "Form Resep",       "canSee" => $canSeeFormResep,   "registerId" => Yii::$app->actionToId([self::class, "actionFormResep"])],
            ["title" => "View",             "canSee" => $canSeeView,        "registerId" => Yii::$app->actionToId([self::class, "actionView"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#menu    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:             Yii::$app->actionToId(__METHOD__),
            editAccess:             [true],
            deleteAccess:           [true],
            viewDetailAccess:       [true],
            printAccess:            [true],
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            deleteUrl:              Yii::$app->actionToUrl([Pair::class, "actionAjaxDelete"]),
            formWidgetId:           Yii::$app->actionToId([self::class, "actionForm"]),
            viewWidgetId:           Yii::$app->actionToId([self::class, "actionView"]),
            printWidgetId:          Yii::$app->actionToId([Pair::class, "actionPrint"]),
            formRutinWidgetId:      Yii::$app->actionToId([self::class, "actionForm"]),
            formNonRutinWidgetId:   Yii::$app->actionToId([self::class, "actionForm"]) ."/addNonrutin",
            kartuWidgetId:          Yii::$app->actionToId([Pair::class, "actionKartu"]),
            stokWidgetId:           Yii::$app->actionToId([Pair::class, "actionStok"]),
            reportsWidgetId:        Yii::$app->actionToId([Pair::class, "actionReports"]),
            subjenisAnggaranSelect: Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect1Data"]),
            caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
            tipeDokumenBulanSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),

            // unused
            // prosesKonsinyasiUrl:     Yii::$app->actionToUrl([self::class, "actionProceed"]),
            // verKendaliKonsinyasiUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerKendali"]),
            // verKendaliDataUrl:       Yii::$app->actionToUrl([Pair::class, "actionVerKendaliData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:             Yii::$app->actionToId(__METHOD__),
            addAccess:              [true],
            editAccess:             [true],
            kendaliAccess:          [true],
            otherAccess:            [true],
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:              Yii::$app->actionToUrl([Pair::class, "actionSaveAdd"]),
            cekUnikNoDokumenUrl:    Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            cekUnikNoFakturUrl:     Yii::$app->actionToUrl([PenerimaanController::class, "actionCekUnik"]),
            cekUnikNoSuratJalanUrl: Yii::$app->actionToUrl([PenerimaanController::class, "actionCekUnik"]),
            pemasokAcplUrl:         Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            stockUrl:               Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            katalogAcplUrl:         Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSubjenis"]),
            viewWidgetId:           Yii::$app->actionToId([self::class, "actionView"]),
            jenisAnggaranSelect:    Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            bulanSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunAnggaranSelect:    Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:       Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:       Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian2Data"]),
        );
        return $view->__toString();

        // edit data:
        // TODO: php: uncategorized: suspicious error (all are $dataView["data"])
        // $dataKonsinyasi = $dataView["data"];
        // $dataDetailKonsinyasi = $dataView["data"];
        // $action = $dataView["data"];

        // add data:
        // $dataKonsinyasi = [];
        // $dataDetailKonsinyasi = [];
        // $action = "add";

        // $act == "add"
        // $judulHeading = "Penerimaan Rutin Barang Konsinyasi";
        // $tipeDokumen = 0;

        // $act == "addNonrutin"
        // $judulHeading = "Penerimaan Non Rutin Barang Konsinyasi";
        // $tipeDokumen = 1;

        // $view->data = $dataKonsinyasi;
        // $view->idata = $dataDetailKonsinyasi; // TODO: php: to be deleted.
        // $view->judulHeading = $judulHeading ?? "";
        // $view->tipeDokumen = $tipeDokumen ?? "";
        // $view->action = $action;
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#proceed    the original method
     */
    public function actionFormProceed(): string
    {
        $view = new FormProceed(
            registerId:                   Yii::$app->actionToId(__METHOD__),
            addAccess:                    [true],
            editAccess:                   [true],
            verifikasiTerimaAccess:       [true],
            verifikasiGudangAccess:       [true],
            verifikasiAkuntansiAccess:    [true],
            otherAccess:                  [true],
            dataUrl:                      Yii::$app->actionToUrl([Pair::class, "actionProceedData"]),
            addActionUrl:                 Yii::$app->actionToUrl([Pair::class, "actionSaveProceed"]),
            editActionUrl:                Yii::$app->actionToUrl([Pair::class, "actionSaveProceed"]),
            verifikasiTerimaActionUrl:    Yii::$app->actionToUrl([Pair::class, "___"]), // TODO: php: uncategorized: adjust to actionSaveProceedAdd or actionSaveProceedVerGudang (TRUELY missing statement)
            verifikasiGudangActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionSaveProceedVerGudang"]),
            verifikasiAkuntansiActionUrl: Yii::$app->actionToUrl([Pair::class, "___"]), // TODO: php: uncategorized: idem
            otherActionUrl:               Yii::$app->actionToUrl([Pair::class, "___"]), // TODO: php: uncategorized: idem
            konsinyasiUrl:                Yii::$app->actionToUrl([self::class, "actionTable"]),
            cekUnikNoFakturUrl:           Yii::$app->actionToUrl([PenerimaanController::class, "actionCekUnik"]),
            cekUnikNoSuratJalanUrl:       Yii::$app->actionToUrl([PenerimaanController::class, "actionCekUnik"]),
            cekUnikNoDokumenUrl:          Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            pemasokAcplUrl:               Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            checkStockUrl:                Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            viewWidgetId:                 Yii::$app->actionToId([PenerimaanUiController::class, "actionView"]),        // ??? really PenerimaanUiController::actionView        not self::actionView?
            viewLainnyaWidgetId:          Yii::$app->actionToId([PenerimaanUiController::class, "actionViewLainnya"]), // ??? really PenerimaanUiController::actionViewLainnya not self::actionView?
            subjenisAnggaranSelect:       Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect3Data"]), // $penerimaan->idJenisAnggaran ?? -1
            jenisHargaSelect:             Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect3Data"]),       // $penerimaan->idJenisHarga ?? -1
            sumberDanaSelect:             Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect3Data"]),       // $penerimaan->idSumberDana ?? -1
            bulanSelect:                  Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunAnggaranSelect:          Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            caraBayarSelect:              Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelian2Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#addresep    the original method
     */
    public function actionFormResep(): string
    {
        $view = new FormResep(
            registerId:          Yii::$app->actionToId(__METHOD__),
            editAccess:          [true],
            dataUrl:             "",
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionSaveResep"]),
            cekUnikNoDokumenUrl: Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            pemasokAcplUrl:      Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            katalogAcplUrl:      Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSubjenis"]),
            perencanaanUrl:      Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            caraBayarSelect:     Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPenjualanData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#views    the original method
     */
    public function actionView(): string
    {
        $view = new View(
            registerId:                Yii::$app->actionToId(__METHOD__),
            dataUrl:                   Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            tablePenerimaanWidgetId:   Yii::$app->actionToId([PenerimaanUiController::class, "actionTable"]),
            tableReturFarmasiWidgetId: Yii::$app->actionToId([ReturFarmasiUiController::class, "actionTable"]),
            formWidgetId:              Yii::$app->actionToId([self::class, "actionForm"]),
            cetakWidgetId:             Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:             Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }
}
