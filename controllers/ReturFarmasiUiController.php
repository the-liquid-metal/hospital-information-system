<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\ReturFarmasiController as Pair;
use tlm\his\FatmaPharmacy\views\ReturFarmasiUi\{
    Form,
    FormBarangLain,
    FormGasMedis,
    FormObat2,
    FormReport,
    Table,
    View,
    ViewRinci,
    ViewTabung,
};
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
class ReturFarmasiUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm = Yii::$app->canAccess([Pair::class, "actionSave"]);
        $canSeeFormObat = Yii::$app->canAccess([Pair::class, "actionSave"])
            ||  Yii::$app->canAccess([Pair::class, "actionSaveVerTerima"])
            ||  Yii::$app->canAccess([Pair::class, "actionSaveVerAkuntansi"]);
        $canSeeFormGasMedis = Yii::$app->canAccess([Pair::class, "actionSaveAddGasMedis"])
            || Yii::$app->canAccess([Pair::class, "actionSaveVerTerimaGasMedis"])
            || Yii::$app->canAccess([Pair::class, "actionSaveVerAkuntansi"]);
        $canSeeFormBarangLain = Yii::$app->canAccess([Pair::class, "actionSaveAddOthers"])
            || Yii::$app->canAccess([Pair::class, "actionSaveVerTerima"])
            || Yii::$app->canAccess([Pair::class, "actionSaveVerAkuntansi"]);
        $canSeeTable = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeViewBarang = Yii::$app->canAccess([Pair::class, "actionDataViewBarang"]);
        $canSeeViewGasMedis = Yii::$app->canAccess([Pair::class, "actionDataViewGasMedis"]);
        $canSeeViewRincian = Yii::$app->canAccess([Pair::class, "actionDataViewRincian"]);
        $canSeeReport = Yii::$app->canAccess([Pair::class, "actionReportBukuInduk"])
            || Yii::$app->canAccess([Pair::class, "actionReportRekapPemasok"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Retur Farmasi";
        $index->tabs = [
            ["title" => "Form",             "canSee" => $canSeeForm,           "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Obat",        "canSee" => $canSeeFormObat,       "registerId" => Yii::$app->actionToId([self::class, "actionFormObat"])],
            ["title" => "Form Gas Medis",   "canSee" => $canSeeFormGasMedis,   "registerId" => Yii::$app->actionToId([self::class, "actionFormGasMedis"])],
            ["title" => "Form Barang Lain", "canSee" => $canSeeFormBarangLain, "registerId" => Yii::$app->actionToId([self::class, "actionFormBarangLain"])],
            ["title" => "Table",            "canSee" => $canSeeTable,          "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "View Barang",      "canSee" => $canSeeViewBarang,     "registerId" => Yii::$app->actionToId([self::class, "actionViewBarang"])],
            ["title" => "View Gas Medis",   "canSee" => $canSeeViewGasMedis,   "registerId" => Yii::$app->actionToId([self::class, "actionViewGasMedis"])],
            ["title" => "View Rincian",     "canSee" => $canSeeViewRincian,    "registerId" => Yii::$app->actionToId([self::class, "actionViewRincian"])],
            ["title" => "Report",           "canSee" => $canSeeReport,         "registerId" => Yii::$app->actionToId([self::class, "actionFormReport"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:                 Yii::$app->actionToId(__METHOD__),
            addAccess:                  [true],
            editAccess:                 [true],
            verifikasiPenerimaanAccess: [true],
            verifikasiAkuntansiAccess:  [true],
            dataUrl:                    Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:                  Yii::$app->actionToUrl([Pair::class, "actionSave"]),
            cekUnikNoDokumenUrl:        Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            penerimaanUrl:              Yii::$app->actionToUrl([PenerimaanController::class, "actionSearchJsonLainnya"]),
            pemasokUrl:                 Yii::$app->actionToUrl([PenerimaanController::class, "actionSearchJsonPbf"]),
            cekStokUrl:                 Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            detailReturUrl:             Yii::$app->actionToUrl([PenerimaanController::class, "actionSearchJsonDetailRetur"]),
            penyimpananSelect:          Yii::$app->actionToUrl([DepoController::class, "actionSelect9Data"]),
            bulanSelect:                Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:                Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:           Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:           Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:            Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
            jenisAnggaranSelect:        Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verTerima    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verAkuntansi    the original method
     */
    public function actionFormObat(): string
    {
        $view = new FormObat2(
            registerId:                    Yii::$app->actionToId(__METHOD__),
            addAccess:                     [true],
            addBonusAccess:                [true],
            editAccess:                    [true],
            verifikasiPenerimaanAccess:    [true],
            verifikasiAkuntansiAccess:     [true],
            addActionUrl:                  Yii::$app->actionToUrl([Pair::class, "actionSave"]),
            addBonusActionUrl:             Yii::$app->actionToUrl([Pair::class, "actionSave"]),
            editActionUrl:                 Yii::$app->actionToUrl([Pair::class, "actionSave"]),
            verifikasiPenerimaanActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerTerima"]),
            verifikasiAkuntansiActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSaveVerAkuntansi"]),
            editDataUrl:                   Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            verifikasiPenerimaanDataUrl:   Yii::$app->actionToUrl([Pair::class, "actionDataVerTerimaObat"]),
            verifikasiAkuntansiDataUrl:    Yii::$app->actionToUrl([Pair::class, "actionDataVerAkuntansiObat"]),
            pembelianAcplUrl:              Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
            pemesananAcplUrl:              Yii::$app->actionToUrl([PemesananController::class, "actionSearchJsonTerima"]),
            pembelian2AcplUrl:             Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
            refPlUrl:                      Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonReffPl"]),
            terimaUrl:                     Yii::$app->actionToUrl([PemesananController::class, "actionSearchHtmlTerima"]),
            penerimaanUrl:                 Yii::$app->actionToUrl([PenerimaanController::class, "actionSearchJsonLainnya"]),
            detailReturUrl:                Yii::$app->actionToUrl([PenerimaanController::class, "actionSearchJsonDetailRetur"]),
            cekUnikNoDokumenUrl:           Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            cekStokUrl:                    Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            detailTerimaPembelianUrl:      Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonDetailTerima"]),
            detailTerimaPemesananUrl:      Yii::$app->actionToUrl([PemesananController::class, "actionSearchJsonDetailTerima"]),
            printWidgetId:                 Yii::$app->actionToId([___::class, "___"]),
            tableWidgetId:                 Yii::$app->actionToId([self::class, "actionTable"]),
            viewWidgetId:                  Yii::$app->actionToId([self::class, "actionViewBarang"]),
            jenisAnggaranSelect:           Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            bulanSelect:                   Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:                   Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:              Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:              Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:               Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verTerima    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verAkuntansi    the original method
     */
    public function actionFormGasMedis(): string
    {
        $view = new FormGasMedis(
            registerId:                    Yii::$app->actionToId(__METHOD__),
            addAccess:                     [true],
            editAccess:                    [true],
            verifikasiPenerimaanAccess:    [true],
            verifikasiAkuntansiAccess:     [true],
            editDataUrl:                   Yii::$app->actionToUrl([Pair::class, "actionDataEditGasMedis"]),
            verifikasiPenerimaanDataUrl:   Yii::$app->actionToUrl([Pair::class, "actionDataVerTerimaGasMedis"]),
            verifikasiAkuntansiDataUrl:    Yii::$app->actionToUrl([Pair::class, "actionDataVerAkuntansiGasMedis"]),
            addActionUrl:                  Yii::$app->actionToUrl([Pair::class, "actionSaveAddGasMedis"]),
            editActionUrl:                 Yii::$app->actionToUrl([Pair::class, "actionSaveAddGasMedis"]),
            verifikasiPenerimaanActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerTerimaGasMedis"]),
            verifikasiAkuntansiActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSaveVerAkuntansi"]),
            pemasokAcplUrl:                Yii::$app->actionToUrl([PemasokController::class, "actionSelect"]),
            katalogAcplUrl:                Yii::$app->actionToUrl([DistribusiController::class, "actionSearchJsonTabungReturn"]),
            cekUnikNoDokumenUrl:           Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            batchUrl:                      Yii::$app->actionToUrl([DistribusiController::class, "actionSearchJsonBatch"]),
            batchTabungUrl:                Yii::$app->actionToUrl([DistribusiController::class, "actionSearchJsonBatchTabung"]),
            viewWidgetId:                  Yii::$app->actionToId([self::class, "actionViewGasMedis"]),
            penyimpananSelect:             Yii::$app->actionToUrl([DepoController::class, "actionSelect7Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verTerima    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verAkuntansi    the original method
     */
    public function actionFormBarangLain(): string
    {
        $view = new FormBarangLain(
            registerId:                    Yii::$app->actionToId(__METHOD__),
            addAccess:                     [true],
            editAccess:                    [true],
            verifikasiPenerimaanAccess:    [true],
            verifikasiAkuntansiAccess:     [true],
            addActionUrl:                  Yii::$app->actionToUrl([Pair::class, "actionSaveAddOthers"]),
            editActionUrl:                 Yii::$app->actionToUrl([Pair::class, "actionSaveAddOthers"]),
            verifikasiPenerimaanActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerTerima"]),
            verifikasiAkuntansiActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSaveVerAkuntansi"]),
            editDataUrl:                   Yii::$app->actionToUrl([Pair::class, "actionEditOthersData"]),
            verifikasiPenerimaanDataUrl:   Yii::$app->actionToUrl([Pair::class, "actionDataVerTerimaLainnya"]),
            verifikasiAkuntansiDataUrl:    Yii::$app->actionToUrl([Pair::class, "actionDataVerAkuntansiLainnya"]),
            pemasokAcplUrl:                Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            subjenisAcplUrl:               Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSubjenis"]),
            cekUnikNoDokumenUrl:           Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            stockUrl:                      Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            viewWidgetId:                  Yii::$app->actionToId([self::class, "actionViewBarang"]),
            jenisAnggaranSelect:           Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            bulanSelect:                   Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:                   Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:              Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:              Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:               Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:                       Yii::$app->actionToId(__METHOD__),
            dataUrl:                          Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            deleteUrl:                        Yii::$app->actionToUrl([Pair::class, "actionSearchJsonDeleted"]),
            viewBarangWidgetId:               Yii::$app->actionToId([self::class, "actionViewBarang"]),
            viewGasMedisWidgetId:             Yii::$app->actionToId([self::class, "actionViewGasMedis"]),
            printWidgetId:                    Yii::$app->actionToId([Pair::class, "actionPrint"]),
            formObatWidgetId:                 Yii::$app->actionToId([self::class, "actionForm"]),
            formGasMedisWidgetId:             Yii::$app->actionToId([self::class, "actionFormGasMedis"]),
            formLainnyaWidgetId:              Yii::$app->actionToId([self::class, "actionForm"]),
            formRevisiWidgetId:               Yii::$app->actionToId([Pair::class, "actionAddRevisi"]), // TODO: php: missing method: TRUELY MISSING Pair::actionAddRevisi
            formVerGudangWidgetId:            Yii::$app->actionToId([self::class, "actionForm"]),
            formVerTerimaObatWidgetId:        Yii::$app->actionToId([self::class, "actionFormObat"]),
            formVerTerimaGasMedisWidgetId:    Yii::$app->actionToId([self::class, "actionFormGasMedis"]),
            formVerTerimaLainnyaWidgetId:     Yii::$app->actionToId([self::class, "actionFormBarangLain"]),
            formVerAkuntansiObatWidgetId:     Yii::$app->actionToId([self::class, "actionFormObat"]),
            formVerAkuntansiGasMedisWidgetId: Yii::$app->actionToId([self::class, "actionFormGasMedis"]),
            formVerAkuntansiLainnyaWidgetId:  Yii::$app->actionToId([self::class, "actionFormBarangLain"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#views    the original method
     */
    public function actionViewBarang(): string
    {
        $view = new View(
            registerId:          Yii::$app->actionToId(__METHOD__),
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionDataViewBarang"]),
            formWidgetId:        Yii::$app->actionToId([self::class, "actionForm"]),
            formLainnyaWidgetId: Yii::$app->actionToId([self::class, "actionFormBarangLain"]),
            viewRincianWidgetId: Yii::$app->actionToId([self::class, "actionViewRincian"]),
            cetakWidgetId:       Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:       Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#views    the original method
     */
    public function actionViewGasMedis(): string
    {
        $view = new ViewTabung(
            registerId:          Yii::$app->actionToId(__METHOD__),
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionDataViewGasMedis"]),
            formWidgetId:        Yii::$app->actionToId([self::class, "actionFormGasMedis"]),
            viewRincianWidgetId: Yii::$app->actionToId([self::class, "actionViewRincian"]),
            cetakWidgetId:       Yii::$app->actionToId([Pair::class, "actionDocuments"]), // TODO: php: missing method: TRUELY MISSING Pair::actionDocuments
            tableWidgetId:       Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#views    the original method
     */
    public function actionViewRincian(): string
    {
        $view = new ViewRinci(
            registerId:           Yii::$app->actionToId(__METHOD__),
            dataUrl:              Yii::$app->actionToUrl([Pair::class, "actionDataViewRincian"]),
            formObatWidgetId:     Yii::$app->actionToId([self::class, "actionFormObat"]),
            formGasMedisWidgetId: Yii::$app->actionToId([self::class, "actionFormGasMedis"]),
            formLainnyaWidgetId:  Yii::$app->actionToId([self::class, "actionFormBarangLain"]),
            viewBarangWidgetId:   Yii::$app->actionToId([self::class, "actionViewBarang"]),
            viewGasMedisWidgetId: Yii::$app->actionToId([self::class, "actionViewGasMedis"]),
            cetakWidgetId:        Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:        Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#Reports    the original method
     * first exist of actionReports: commit-75b46456
     */
    public function actionFormReport(): string
    {
        $view = new FormReport(
            registerId:             Yii::$app->actionToId(__METHOD__),
            bukuIndukActionUrl:     Yii::$app->actionToUrl([Pair::class, "actionReportBukuInduk"]),
            rekapPemasokActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionReportRekapPemasok"]),
        );
        return $view->__toString();
    }
}
