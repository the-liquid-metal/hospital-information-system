<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\TransaksiController as Pair;
use tlm\his\FatmaPharmacy\views\TransaksiUi\{
    Form,
    FormPenerimaan2,
    FormPengeluaran2,
    FormPengeluaran22,
    FormPengiriman7,
    FormPermintaan2,
    LaporanPp,
    TablePenerimaan4,
    TablePengeluaran5,
    TablePengeluaran5Tp,
};
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
class TransaksiUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormPermintaan2   = Yii::$app->canAccess([Pair::class, "actionSavePermintaan2"]);
        $canSeeFormPengirimanTp  = Yii::$app->canAccess([Pair::class, "actionSavePengirimanTp"]);
        $canSeeTablePengiriman   = Yii::$app->canAccess([Pair::class, "actionTablePengirimanData1"])
                                || Yii::$app->canAccess([Pair::class, "actionTablePengirimanData2"]);
        $canSeeTablePengirimanTp = Yii::$app->canAccess([Pair::class, "actionListPengirimanTpData1"])
                                || Yii::$app->canAccess([Pair::class, "actionListPengirimanTpData2"]);
        $canSeeTablePenerimaan   = Yii::$app->canAccess([Pair::class, "actionTablePenerimaanData"]);
        $canSeeFormPengeluaran2  = Yii::$app->canAccess([Pair::class, "actionFormPengeluaran2Data"]);
        $canSeeFormPengeluaran22 = Yii::$app->canAccess([Pair::class, "actionPengeluaran22Data"]);
        $canSeeFormPenerimaan2   = Yii::$app->canAccess([Pair::class, "actionPenerimaan2Data"]);
        $canSeeLaporan           = Yii::$app->canAccess([Pair::class, "actionLaporanData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Transaksi";
        $index->tabs = [
            ["title" => "Table Pengiriman",    "canSee" => $canSeeTablePengiriman,   "registerId" => Yii::$app->actionToId([self::class, "actionTablePengiriman"])],
            ["title" => "Table Pengiriman TP", "canSee" => $canSeeTablePengirimanTp, "registerId" => Yii::$app->actionToId([self::class, "actionTablePengirimanTp"])],
            ["title" => "Table Penerimaan",    "canSee" => $canSeeTablePenerimaan,   "registerId" => Yii::$app->actionToId([self::class, "actionTablePenerimaan"])],
            ["title" => "Form Permintaan2",    "canSee" => $canSeeFormPermintaan2,   "registerId" => Yii::$app->actionToId([self::class, "actionFormPermintaan2"])],
            ["title" => "Form Pengiriman TP",  "canSee" => $canSeeFormPengirimanTp,  "registerId" => Yii::$app->actionToId([self::class, "actionFormPengirimanTp"])],
            ["title" => "Form Pengeluaran2",   "canSee" => $canSeeFormPengeluaran2,  "registerId" => Yii::$app->actionToId([self::class, "actionFormPengeluaran2"])],
            ["title" => "Form Pengeluaran22",  "canSee" => $canSeeFormPengeluaran22, "registerId" => Yii::$app->actionToId([self::class, "actionFormPengeluaran22"])],
            ["title" => "Form Penerimaan2",    "canSee" => $canSeeFormPenerimaan2,   "registerId" => Yii::$app->actionToId([self::class, "actionFormPenerimaan2"])],
            ["title" => "Laporan",             "canSee" => $canSeeLaporan,           "registerId" => Yii::$app->actionToId([self::class, "actionLaporan"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#add    the original method
     *
     * NOTE: malformed action. there is TRUELY no form submission prosess.
     */
    public function actionAdd(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                KAT.kode        AS kode,
                KAT.namaKatalog AS namaKatalog,
                SAT.kemasan     AS kemasan,
                SAT.satuan      AS satuan
            FROM db1.master_katalog AS KAT
            LEFT JOIN aplous.master_satuan AS SAT ON KAT.kodeSatuan = SAT.kode
            ORDER BY namaKatalog ASC
        ";
        $daftarKatalog = $connection->createCommand($sql)->queryAll();

        // TODO: php: uncategorized: to be deleted
        $this->render("xxx", [
            "user" => Yii::$app->userFatma,
            "iTotal" => count($daftarKatalog), // TODO: php: to be deleted
            "data" => $daftarKatalog,
            "gridName" => $gridName,
        ]);

        $view = new Form(
            registerId:    Yii::$app->actionToId(__METHOD__),
            actionUrl:     Yii::$app->actionToUrl([self::class, "actionAdd"]),
            transaksiUrl:  Yii::$app->actionToUrl([self::class, "actionSearch"]), // MISSING!
            batalWidgetId: Yii::$app->actionToId([self::class, "actionBooking"]), // MISSING!
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#permintaan2    the original method
     */
    public function actionFormPermintaan2(): string
    {
        $view = new FormPermintaan2(
            registerId:    Yii::$app->actionToId(__METHOD__),
            addAccess:     [true],
            addActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSavePermintaan2"]),
            obatAcplUrl:   Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            hargaUrl:      Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            stokDataUrl:   Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            noDokumenUrl:  Yii::$app->actionToUrl([Pair::class, "actionNoDokumenTemporer"]),
            printWidgetId: Yii::$app->actionToId([Pair::class, "actionPrintPengambilan2"]),
            depoSelect:    Yii::$app->actionToUrl([DepoController::class, "actionSelect6Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengirimantp    the original method
     */
    public function actionFormPengirimanTp(): string
    {
        $view = new FormPengiriman7(
            registerId:        Yii::$app->actionToId(__METHOD__),
            addAccess:         [true],
            addActionUrl:      Yii::$app->actionToUrl([Pair::class, "actionSavePengirimanTp"]),
            obatAcplUrl:       Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            batchUrl:          Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetBatch"]),
            hargaUrl:          Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
            pembungkusAcplUrl: Yii::$app->actionToUrl([Katalog3Controller::class, "actionPembungkus"]),
            kodeTransaksiUrl:  Yii::$app->actionToUrl([Pair::class, "actionKodeTransaksiTemporer"]),
            printWidgetId:     Yii::$app->actionToId([Pair::class, "actionPrintPengirimanTp"]),
            depoSelect:        Yii::$app->actionToUrl([DepoController::class, "actionSelect6Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengiriman    the original method
     */
    public function actionTablePengiriman(): string
    {
        $view = new TablePengeluaran5(
            registerId:              Yii::$app->actionToId(__METHOD__),
            data1Url:                Yii::$app->actionToUrl([Pair::class, "actionTablePengirimanData1"]),
            data2Url:                Yii::$app->actionToUrl([Pair::class, "actionTablePengirimanData2"]),
            viewPengeluaranWidgetId: Yii::$app->actionToId([self::class, "actionFormPengeluaran2"]),
            printPengirimanWidgetId: Yii::$app->actionToId([Pair::class, "actionPrintPengiriman"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#list_pengirimantp    the original method
     */
    public function actionTablePengirimanTp(): string
    {
        $view = new TablePengeluaran5Tp(
            registerId:               Yii::$app->actionToId(__METHOD__),
            data1Url:                 Yii::$app->actionToUrl([Pair::class, "actionListPengirimanTpData1"]),
            data2Url:                 Yii::$app->actionToUrl([Pair::class, "actionListPengirimanTpData2"]),
            viewPengeluaranWidgetId:  Yii::$app->actionToId([self::class, "actionFormPengeluaran22"]),
            formPengirimanTpWidgetId: Yii::$app->actionToId([self::class, "actionFormPengirimanTp"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#list_penerimaan    the original method
     */
    public function actionTablePenerimaan(): string
    {
        $view = new TablePenerimaan4(
            registerId:             Yii::$app->actionToId(__METHOD__),
            dataUrl:                Yii::$app->actionToId([Pair::class, "actionTablePenerimaanData"]),
            formPenerimaanWidgetId: Yii::$app->actionToId([self::class, "actionFormPenerimaan2"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengeluaran2    the original method
     */
    public function actionFormPengeluaran2(): string
    {
        ["id_trn" => $idTransaksi] = Yii::$app->request->post();
        if ($idTransaksi) {
            $view = new FormPengeluaran2(
                registerId:        Yii::$app->actionToId(__METHOD__),
                addAccess:         [true],
                dataUrl:           Yii::$app->actionToUrl([Pair::class, "actionFormPengeluaran2Data"]),
                actionUrl:         Yii::$app->actionToUrl([Pair::class, "actionSavePengeluaran2"]),
                obatAcplUrl:       Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
                hargaUrl:          Yii::$app->actionToUrl([Katalog3Controller::class, "actionGetHarga"]),
                hidePermintaanUrl: Yii::$app->actionToUrl([Pair::class, "actionHidePermintaan"]),
                stokDataUrl:       Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
                pembungkusAcplUrl: Yii::$app->actionToUrl([Katalog3Controller::class, "actionPembungkus"]),
                printWidgetId:     Yii::$app->actionToId([Pair::class, "actionPrintPengiriman"]),
                depoSelect:        Yii::$app->actionToUrl([DepoController::class, "actionSelect6Data"]),
            );
            return $view->__toString();

        } else {
            return $this->renderPartial("pengeluaran3");
        }
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengeluaran22    the original method
     */
    public function actionFormPengeluaran22(): string
    {
        $view = new FormPengeluaran22(
            registerId:    Yii::$app->actionToId(__METHOD__),
            addAccess:     [true],
            dataUrl:       Yii::$app->actionToUrl([Pair::class, "actionPengeluaran22Data"]),
            addActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSavePengeluaran22"]),
            stokDataUrl:   Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
            printWidgetId: Yii::$app->actionToId([Pair::class, "actionPrintPengirimanTp"]),
            depoSelect:    Yii::$app->actionToUrl([DepoController::class, "actionSelect6Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#penerimaan2    the original method
     */
    public function actionFormPenerimaan2(): string
    {
        $view = new FormPenerimaan2(
            registerId:   Yii::$app->actionToId(__METHOD__),
            addAccess:    [true],
            dataUrl:      Yii::$app->actionToUrl([Pair::class, "actionPenerimaan2Data"]),
            addActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSavePenerimaan2"]),
            obatAcplUrl:  Yii::$app->actionToUrl([Katalog3Controller::class, "actionSearchJsonObat"]),
            stokDataUrl:  Yii::$app->actionToUrl([SignaController::class, "actionStokTableData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#laporan    the original method
     */
    public function actionLaporan(): string
    {
        $view = new LaporanPp(
            registerId:                Yii::$app->actionToId(__METHOD__),
            dataUrl:                   Yii::$app->actionToUrl([Pair::class, "actionLaporanData"]),
            historyPengirimanWidgetId: Yii::$app->actionToId([Pair::class, "actionHistoryPengiriman"]),
        );
        return $view->__toString();
    }
}
