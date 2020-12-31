<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\PemesananController as Pair;
use tlm\his\FatmaPharmacy\views\PemesananUi\{Form, FormRevisi, Table, TablePo, View};
use tlm\libs\LowEnd\components\DateTimeException;
use tlm\libs\LowEnd\views\CustomIndexScript;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class PemesananUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable      = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeTablePo    = Yii::$app->canAccess([Pair::class, "actionTablePoData"]);
        $canSeeForm       = Yii::$app->canAccess([Pair::class, "actionSaveAdd"]);
        $canSeeFormRevisi = Yii::$app->canAccess([Pair::class, "actionSaveRevisiJumlah"])
                         || Yii::$app->canAccess([Pair::class, "actionSaveRevisiPl"])
                         || Yii::$app->canAccess([Pair::class, "actionSaveRevisiDokumen"]);
        $canSeeView       = Yii::$app->canAccess([Pair::class, "actionViewData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Pemesanan";
        $index->tabs = [
            ["title" => "Table",       "canSee" => $canSeeTable,      "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "Table PO",    "canSee" => $canSeeTablePo,    "registerId" => Yii::$app->actionToId([self::class, "actionTablePo"])],
            ["title" => "Form",        "canSee" => $canSeeForm,       "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Revisi", "canSee" => $canSeeFormRevisi, "registerId" => Yii::$app->actionToId([self::class, "actionFormRevisi"])],
            ["title" => "View",        "canSee" => $canSeeView,       "registerId" => Yii::$app->actionToId([self::class, "actionView"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#index    the original method
     */
    public function actionTable(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $todayValSystem = Yii::$app->dateTime->todayVal("system");

        $connection = Yii::$app->dbFatma;

        // Query Update Status PL menjadi Closed untuk semua PL diluar jatuh tempo
        // =================================================================//
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pemesanan
            SET
                sts_closed = IF(tgl_tempokirim < :tanggal1, 1, 0),
                sysdate_cls = IF(tgl_tempokirim < :tanggal1, :tanggal2, NULL)
            WHERE TRUE
        ";
        $params = [":tanggal1" => $todayValSystem, ":tanggal2" => $nowValSystem];
        $connection->createCommand($sql, $params)->execute();
        // =================================================================//

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
            formRevisiWidgetId:     Yii::$app->actionToId([self::class, "actionFormRevisi"]),
            subjenisAnggaranSelect: Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect1Data"]),
            tipeDokumenBulanSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:          Yii::$app->actionToId(__METHOD__),
            addAccess:           [true],
            editAccess:          [true],
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionFormData"]),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionSaveAdd"]),
            cekUnikNoDokumenUrl: Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            pembelianAcplUrl:    Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
            perencanaanUrl:      Yii::$app->actionToUrl([PerencanaanController::class, "actionSearchJsonDo"]),
            pemasokAcplUrl:      Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            detailDoUrl:         Yii::$app->actionToUrl([PerencanaanController::class, "actionSearchJsonDetailDo"]),
            printWidgetId:       Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:       Yii::$app->actionToId([self::class, "actionTable"]),
            viewWidgetId:        Yii::$app->actionToId([self::class, "actionView"]),
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#addrevisi    the original method
     */
    public function actionFormRevisi(): string
    {
        $view = new FormRevisi(
            registerId:             Yii::$app->actionToId(__METHOD__),
            editAccess:             [true],
            revisiJumlahAccess:     [true],
            revisiPlAccess:         [true],
            revisiDokumenAccess:    [true],
            revisiElseAccess:       [true],
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionFormRevisiData"]),
            editActionUrl:          Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiJumlahActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSaveRevisiJumlah"]),
            revisiPlActionUrl:      Yii::$app->actionToUrl([Pair::class, "actionSaveRevisiPl"]),
            revisiDokumenActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveRevisiDokumen"]),
            printWidgetId:          Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:          Yii::$app->actionToId([self::class, "actionTable"]),
            viewWidgetId:           Yii::$app->actionToId([self::class, "actionView"]),
            jenisAnggaranSelect:    Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            bulanSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunAnggaranSelect:    Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:       Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:       Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#views    the original method
     */
    public function actionView(): string
    {
        $view = new View(
            registerId:              Yii::$app->actionToId(__METHOD__),
            dataUrl:                 Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            viewPerencanaanWidgetId: Yii::$app->actionToId([PerencanaanUiController::class, "actionView"]),
            viewPengadaanWidgetId:   Yii::$app->actionToId([PengadaanUiController::class, "actionView"]),
            tablePenerimaanWidgetId: Yii::$app->actionToId([PenerimaanUiController::class, "actionTable"]),
            tablePemesananWidgetId:  Yii::$app->actionToId([self::class, "actionTable"]),
            cetakPemesananWidgetId:  Yii::$app->actionToId([Pair::class, "actionPrint"]),
            editPemesananWidgetId:   Yii::$app->actionToId([self::class, "actionForm"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#reports    the original method
     */
    public function actionTablePo(): string
    {
        $view = new TablePo(
            registerId:            Yii::$app->actionToId(__METHOD__),
            dataUrl:               Yii::$app->actionToUrl([Pair::class, "actionTablePoData"]),
            pembelianAcplUrl:      Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
            katalogAcplUrl:        Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSearch"]),
            pembelianViewWidgetId: Yii::$app->actionToId([PembelianUiController::class, "actionView"]),
            pemesananViewWidgetId: Yii::$app->actionToId([self::class, "actionView"]),
            jenisAnggaranSelect:   Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),

            // unused
            // pemesananReportWidgetId: Yii::$app->actionToUrl([self::class, "actionReports"]),
        );
        return $view->__toString();
    }
}
