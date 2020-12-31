<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\PenerimaanController as Pair;
use tlm\his\FatmaPharmacy\views\PenerimaanUi\{
    Form,
    FormGas,
    FormLainnya,
    FormRevisi,
    FormRevisiLainnya,
    Table,
    TableRevisi,
    View,
    ViewLainnya,
    ViewRinci,
    ViewRinciLainnya,
};
use tlm\libs\LowEnd\components\{DateTimeException, MasterHelper as MH};
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
class PenerimaanUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm              = Yii::$app->canAccess([Pair::class, "actionSaveForm"]);
        $canSeeTable             = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeTableRevisi       = Yii::$app->canAccess([Pair::class, "actionTableRevisiData"]);
        $canSeeFormLainnya       = Yii::$app->canAccess([Pair::class, "actionSaveFormLainnya"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveVerGudang"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveVerAkuntansi"]);
        $canSeeView              = Yii::$app->canAccess([Pair::class, "actionViewData"]);
        $canSeeViewLainnya       = Yii::$app->canAccess([Pair::class, "actionViewData"]);
        $canSeeViewRinci         = Yii::$app->canAccess([Pair::class, "actionViewData"]);
        $canSeeViewRinciLainnya  = Yii::$app->canAccess([Pair::class, "actionViewData"]);
        $canSeeFormRevisi        = Yii::$app->canAccess([Pair::class, "actionSaveAddRevisiPl"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveAddRevisiDokumen"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveAddRevisiItem"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveVerRevisiGudang"]);
        $canSeeFormRevisiLainnya = Yii::$app->canAccess([Pair::class, "actionSaveAddRevisiPl"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveAddRevisiDokumen"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveAddRevisiItem"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveVerRevisiGudang"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Penerimaan";
        $index->tabs = [
            ["title" => "Form",                "canSee" => $canSeeForm,              "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Gas",            "canSee" => $canSeeForm,              "registerId" => Yii::$app->actionToId([self::class, "actionFormGas"])],
            ["title" => "Form Lainnya",        "canSee" => $canSeeFormLainnya,       "registerId" => Yii::$app->actionToId([self::class, "actionFormLainnya"])],
            ["title" => "Form Revisi",         "canSee" => $canSeeFormRevisi,        "registerId" => Yii::$app->actionToId([self::class, "actionFormRevisi"])],
            ["title" => "Form Revisi Lainnya", "canSee" => $canSeeFormRevisiLainnya, "registerId" => Yii::$app->actionToId([self::class, "actionFormRevisiLainnya"])],
            ["title" => "Table",               "canSee" => $canSeeTable,             "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "Table Revisi",        "canSee" => $canSeeTableRevisi,       "registerId" => Yii::$app->actionToId([self::class, "actionTableRevisi"])],
            ["title" => "View",                "canSee" => $canSeeView,              "registerId" => Yii::$app->actionToId([self::class, "actionView"])],
            ["title" => "View Lainnya",        "canSee" => $canSeeViewLainnya,       "registerId" => Yii::$app->actionToId([self::class, "actionViewLainnya"])],
            ["title" => "View Rinci",          "canSee" => $canSeeViewRinci,         "registerId" => Yii::$app->actionToId([self::class, "actionViewRinci"])],
            ["title" => "View Rinci Lainnya",  "canSee" => $canSeeViewRinciLainnya,  "registerId" => Yii::$app->actionToId([self::class, "actionViewRinciLainnya"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:                   Yii::$app->actionToId(__METHOD__),
            addAccess:                    [true],
            addBonusAccess:               [true],
            editAccess:                   [true],
            editBonusAccess:              [true],
            verifikasiTerimaAccess:       [true],
            verifikasiGudangAccess:       [true],
            verifikasiAkuntansiAccess:    [true],
            editDataUrl:                  Yii::$app->actionToUrl([Pair::class, "actionFormData"]),
            editBonusDataUrl:             Yii::$app->actionToUrl([Pair::class, "actionFormData"]),
            verifikasiTerimaDataUrl:      Yii::$app->actionToUrl([Pair::class, "actionFormData"]),
            verifikasiGudangDataUrl:      Yii::$app->actionToUrl([Pair::class, "actionVerGudangData"]),
            verifikasiAkuntansiDataUrl:   Yii::$app->actionToUrl([Pair::class, "actionVerAkuntansiData"]),
            addActionUrl:                 Yii::$app->actionToUrl([Pair::class, "actionSaveForm"]),
            addBonusActionUrl:            Yii::$app->actionToUrl([Pair::class, "actionSaveForm"]),
            editActionUrl:                Yii::$app->actionToUrl([Pair::class, "actionSaveForm"]),
            editBonusActionUrl:           Yii::$app->actionToUrl([Pair::class, "actionSaveForm"]),
            verifikasiTerimaActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionSaveForm"]),
            verifikasiGudangActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionSaveVerGudang"]),
            verifikasiAkuntansiActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerAkuntansi"]),
            cekUnikNoFakturUrl:           Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            cekUnikNoSuratJalanUrl:       Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            pembelianAcplUrl:             Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
            refPlPembelianUrl:            Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonReffPl"]),
            penerimaanUrl:                Yii::$app->actionToUrl([PemesananController::class, "actionSearchHtmlTerima"]),
            pemesananAcplUrl:             Yii::$app->actionToUrl([PemesananController::class, "actionSearchJsonTerima"]),
            cekUnikNoDokumenUrl:          Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            cekStokUrl:                   Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            detailTerimaUrl:              Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonDetailTerima"]),
            detailTerimaPemesananUrl:     Yii::$app->actionToUrl([PemesananController::class, "actionSearchJsonDetailTerima"]),
            viewWidgetId:                 Yii::$app->actionToId([self::class, "actionView"]),
            jenisAnggaranSelect:          Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            bulanSelect:                  Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:                  Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:             Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:             Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:              Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addgas    the original method
     */
    public function actionFormGas(): string
    {
        $view = new FormGas(
            registerId:                   Yii::$app->actionToId(__METHOD__),
            addAccess:                    [true],
            editAccess:                   [true],
            verifikasiTerimaAccess:       [true],
            verifikasiGudangAccess:       [true],
            verifikasiAkuntansiAccess:    [true],
            editDataUrl:                  Yii::$app->actionToUrl([Pair::class, "actionFormGasData"]),
            verifikasiTerimaDataUrl:      Yii::$app->actionToUrl([Pair::class, "actionFormGasData"]),
            verifikasiGudangDataUrl:      Yii::$app->actionToUrl([Pair::class, "actionVerGudangData"]),
            verifikasiAkuntansiDataUrl:   Yii::$app->actionToUrl([Pair::class, "actionVerAkuntansiData"]), // hypothesis (not exist in actionVerAkuntansiData)
            addActionUrl:                 Yii::$app->actionToUrl([Pair::class, "actionSaveFormGas"]),
            editActionUrl:                Yii::$app->actionToUrl([Pair::class, "actionSaveFormGas"]),
            verifikasiTerimaActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionSaveFormGas"]),
            verifikasiGudangActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionSaveVerGudang"]),
            verifikasiAkuntansiActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerAkuntansi"]), // hypothesis (not exist in actionVerAkuntansiData)
            jenisAnggaranSelect:          json_encode(MH::dropdown_jenisanggaran("id, kode, jenis_anggaran", "WHERE sts_aktif = 1")),
            subjenisAnggaranSelect:       json_encode(MH::dropdown_subjenisanggaran("id, kode, subjenis_anggaran, id_jenis", "WHERE A.sts_aktif = 1")),
            sumberDanaSelect:             json_encode(MH::dropdown_sumberdana("WHERE sts_aktif = 1")),
            jenisHargaSelect:             json_encode(MH::dropdown_jenisharga("WHERE sts_aktif = 1")),
            caraBayarSelect:              json_encode(MH::dropdown_carabayar("WHERE pembelian = 1 AND sts_aktif = 1")),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:                          Yii::$app->actionToId(__METHOD__),
            editAccess:                          [true],
            deleteAccess:                        [true],
            auditAccess:                         [true],
            dataUrl:                             Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            deleteUrl:                           Yii::$app->actionToUrl([Pair::class, "actionAjaxDelete"]),
            permissionUrl:                       Yii::$app->actionToUrl([Pair::class, "actionAjaxPermission"]),
            viewWidgetId:                        Yii::$app->actionToId([self::class, "actionView"]),
            viewLainnyaWidgetId:                 Yii::$app->actionToId([self::class, "actionViewLainnya"]),
            printWidgetId:                       Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableRevisiWidgetId:                 Yii::$app->actionToId([self::class, "actionTableRevisi"]),
            formKonsinyasiWidgetId:              Yii::$app->actionToId([KonsinyasiUiController::class, "actionFormProceed"]),

            formPenerimaanWidgetId:              Yii::$app->actionToId([self::class, "actionForm"]),
            formPenerimaanBonusWidgetId:         Yii::$app->actionToId([self::class, "actionForm"]),
            formPenerimaanLainnyaWidgetId:       Yii::$app->actionToId([self::class, "actionFormLainnya"]),

            formRevisiPenerimaanWidgetId:        Yii::$app->actionToId([self::class, "actionFormRevisi"]),
            formRevisiPenerimaanLainnyaWidgetId: Yii::$app->actionToId([self::class, "actionFormRevisiLainnya"]),

            formVerPenerimaanWidgetId:           Yii::$app->actionToId([self::class, "actionForm"]),
            formVerPenerimaanLainnyaWidgetId:    Yii::$app->actionToId([self::class, "actionFormLainnya"]),

            formVerGudangWidgetId:               Yii::$app->actionToId([self::class, "actionForm"]),
            formVerGudangGasMedisWidgetId:       Yii::$app->actionToId([self::class, "actionFormGas"]),
            formVerGudangLainnyaWidgetId:        Yii::$app->actionToId([self::class, "actionFormLainnya"]),

            formVerRevisiGudangWidgetId:         Yii::$app->actionToId([self::class, "actionFormLainnya"]),
            formVerRevisiGudangLainnyaWidgetId:  Yii::$app->actionToId([self::class, "actionFormRevisiLainnya"]),

            formVerAkuntansiWidgetId:            Yii::$app->actionToId([self::class, "actionForm"]),
            formVerAkuntansiLainnyaWidgetId:     Yii::$app->actionToId([self::class, "actionFormLainnya"]),

            subjenisAnggaranSelect:              Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect1Data"]),
            caraBayarSelect:                     Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
            tipeDokumenBulanSelect:              Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:                         Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#revisi    the original method
     */
    public function actionTableRevisi(): string
    {
        $view = new TableRevisi(
            registerId:             Yii::$app->actionToId(__METHOD__),
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionTableRevisiData"]),
            viewWidgetId:           Yii::$app->actionToId([self::class, "actionView"]),
            viewLainnyaWidgetId:    Yii::$app->actionToId([self::class, "actionViewLainnya"]),
            printWidgetId:          Yii::$app->actionToId([Pair::class, "actionPrint"]),
            subjenisAnggaranSelect: Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect1Data"]),

            // unused
            // editUrl:         Yii::$app->actionToUrl([Pair::class, "actionEdit"]),
            // deleteUrl:       Yii::$app->actionToUrl([Pair::class, "actionAjaxDelete"]),
            // permissionUrl:   Yii::$app->actionToUrl([Pair::class, "actionAjaxPermission"]),
            // verGudangUrl:    Yii::$app->actionToUrl([self::class, "actionVerGudang"]),
            // verAkuntansiUrl: Yii::$app->actionToUrl([self::class, "actionVerAkuntansi"]),
            // verTerimaUrl:    Yii::$app->actionToUrl([Pair::class, "actionVerTerima"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addOthers    the original method
     */
    public function actionFormLainnya(): string
    {
        $view = new FormLainnya(
            registerId:                   Yii::$app->actionToId(__METHOD__),
            addAccess:                    [true],
            addBonusAccess:               [true],
            editAccess:                   [true],
            verifikasiTerimaAccess:       [true],
            verifikasiGudangAccess:       [true],
            verifikasiAkuntansiAccess:    [true],
            editDataUrl:                  Yii::$app->actionToUrl([Pair::class, "actionFormLainnyaData"]),
            verifikasiTerimaDataUrl:      Yii::$app->actionToUrl([Pair::class, "actionFormLainnyaData"]),
            verifikasiGudangDataUrl:      Yii::$app->actionToUrl([Pair::class, "actionVerGudangData"]),
            verifikasiAkuntansiDataUrl:   Yii::$app->actionToUrl([Pair::class, "actionVerAkuntansiData"]),
            addActionUrl:                 Yii::$app->actionToUrl([Pair::class, "actionSaveFormLainnya"]),
            addBonusActionUrl:            Yii::$app->actionToUrl([Pair::class, "actionSaveFormLainnya"]),
            editActionUrl:                Yii::$app->actionToUrl([Pair::class, "actionSaveFormLainnya"]),
            verifikasiTerimaActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionSaveFormLainnya"]),
            verifikasiGudangActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionSaveVerGudang"]),
            verifikasiAkuntansiActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerAkuntansi"]),
            cekUnikNoFakturUrl:           Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            cekUnikNoSuratJalanUrl:       Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            cekUnikNoDokumenUrl:          Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            pemasokAcplUrl:               Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            cekStokUrl:                   Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            subjenisAcplUrl:              Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSubjenis"]),
            viewLainnyaWidgetId:          Yii::$app->actionToId([self::class, "actionViewLainnya"]),
            caraBayarSelect:              Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
            bulanSelect:                  Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:                  Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:             Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:             Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            jenisAnggaranSelect:          Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#views    the original method
     */
    public function actionView(): string
    {
        $view = new View(
            registerId:        Yii::$app->actionToId(__METHOD__),
            dataUrl:           Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            formWidgetId:      Yii::$app->actionToId([self::class, "actionForm"]),
            viewRinciWidgetId: Yii::$app->actionToId([self::class, "actionViewRinci"]),
            cetakWidgetId:     Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:     Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#views    the original method
     */
    public function actionViewLainnya(): string
    {
        $view = new ViewLainnya(
            registerId:               Yii::$app->actionToId(__METHOD__),
            dataUrl:                  Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            formBonusWidgetId:        Yii::$app->actionToId([self::class, "actionForm"]),
            formLainnyaWidgetId:      Yii::$app->actionToId([self::class, "actionFormLainnya"]),
            formKonsinyasiWidgetId:   Yii::$app->actionToId([KonsinyasiUiController::class, "actionFormProceed"]),
            viewRinciLainnyaWidgetId: Yii::$app->actionToId([self::class, "actionViewRinciLainnya"]),
            cetakWidgetId:            Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:            Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#views    the original method
     */
    public function actionViewRinci(): string
    {
        $view = new ViewRinci(
            registerId:    Yii::$app->actionToId(__METHOD__),
            dataUrl:       Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            formWidgetId:  Yii::$app->actionToId([self::class, "actionForm"]),
            viewWidgetId:  Yii::$app->actionToId([self::class, "actionView"]),
            cetakWidgetId: Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId: Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#views    the original method
     */
    public function actionViewRinciLainnya(): string
    {
        $view = new ViewRinciLainnya(
            registerId:             Yii::$app->actionToId(__METHOD__),
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            formBonusWidgetId:      Yii::$app->actionToId([self::class, "actionForm"]),
            formLainnyaWidgetId:    Yii::$app->actionToId([self::class, "actionFormLainnya"]),
            formKonsinyasiWidgetId: Yii::$app->actionToId([KonsinyasiUiController::class, "actionFormProceed"]),
            viewLainnyaWidgetId:    Yii::$app->actionToId([self::class, "actionViewLainnya"]),
            cetakWidgetId:          Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:          Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#doVerRevisigudang    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addrevisi    the original method
     */
    public function actionFormRevisi(): string
    {
        $view = new FormRevisi(
            registerId:                      Yii::$app->actionToId(__METHOD__),
            editAccess:                      [true],
            revisiPlAccess:                  [true],
            revisiDokumenAccess:             [true],
            revisiItemAccess:                [true],
            verifikasiRevisiGudangAccess:    [true],
            addDataUrl:                      Yii::$app->actionToUrl([Pair::class, "actionFormRevisiData"]),
            editDataUrl:                     Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiPlDataUrl:                 Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiDokumenDataUrl:            Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiItemDataUrl:               Yii::$app->actionToUrl([Pair::class, "___"]),
            verifikasiGudangDataUrl:         Yii::$app->actionToUrl([Pair::class, "___"]),
            verifikasiRevisiGudangDataUrl:   Yii::$app->actionToUrl([Pair::class, "actionVerRevisiGudangData"]),
            addActionUrl:                    Yii::$app->actionToUrl([Pair::class, "___"]),
            editActionUrl:                   Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiPlActionUrl:               Yii::$app->actionToUrl([Pair::class, "actionSaveAddRevisiPl"]),
            revisiDokumenActionUrl:          Yii::$app->actionToUrl([Pair::class, "actionSaveAddRevisiDokumen"]),
            revisiItemActionUrl:             Yii::$app->actionToUrl([Pair::class, "actionSaveAddRevisiItem"]),
            verifikasiGudangActionUrl:       Yii::$app->actionToUrl([Pair::class, "___"]),
            verifikasiRevisiGudangActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerRevisiGudang"]),
            cekUnikNoDokumenUrl:             Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            cekUnikNoFakturUrl:              Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            cekUnikNoSuratJalanUrl:          Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            cekStokUrl:                      Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            printWidgetId:                   Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:                   Yii::$app->actionToId([self::class, "actionTable"]),
            viewWidgetId:                    Yii::$app->actionToId([self::class, "actionView"]),
            jenisAnggaranSelect:             Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            bulanSelect:                     Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:                     Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:                Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:                Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:                 Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#doVerRevisigudang    the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addrevisi    the original method
     */
    public function actionFormRevisiLainnya(): string
    {
        $view = new FormRevisiLainnya(
            registerId:                      Yii::$app->actionToId(__METHOD__),
            editAccess:                      [true],
            revisiPlAccess:                  [true],
            revisiDokumenAccess:             [true],
            revisiItemAccess:                [true],
            verifikasiRevisiGudangAccess:    [true],
            editDataUrl:                     Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiPlDataUrl:                 Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiDokumenDataUrl:            Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiItemDataUrl:               Yii::$app->actionToUrl([Pair::class, "___"]),
            verifikasiGudangDataUrl:         Yii::$app->actionToUrl([Pair::class, "___"]),
            verifikasiRevisiGudangDataUrl:   Yii::$app->actionToUrl([Pair::class, "actionVerRevisiGudangData"]),
            editActionUrl:                   Yii::$app->actionToUrl([Pair::class, "___"]),
            revisiPlActionUrl:               Yii::$app->actionToUrl([Pair::class, "actionSaveAddRevisiPl"]),
            revisiDokumenActionUrl:          Yii::$app->actionToUrl([Pair::class, "actionSaveAddRevisiDokumen"]),
            revisiItemActionUrl:             Yii::$app->actionToUrl([Pair::class, "actionSaveAddRevisiItem"]),
            verifikasiGudangActionUrl:       Yii::$app->actionToUrl([Pair::class, "___"]),
            verifikasiRevisiGudangActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveVerRevisiGudang"]),
            cekUnikNoFakturUrl:              Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            cekUnikNoSuratJalanUrl:          Yii::$app->actionToUrl([Pair::class, "actionCekUnik"]),
            pemasokAcplUrl:                  Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            cekUnikNoDokumenUrl:             Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            cekStokUrl:                      Yii::$app->actionToUrl([PerencanaanController::class, "actionCheckStock"]),
            printWidgetId:                   Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:                   Yii::$app->actionToId([self::class, "actionTable"]),
            viewLainnyaWidgetId:             Yii::$app->actionToId([self::class, "actionViewLainnya"]),
            bulanSelect:                     Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:                     Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            jenisAnggaranSelect:             Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            sumberDanaSelect:                Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:                Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:                 Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
        );
        return $view->__toString();
    }
}
