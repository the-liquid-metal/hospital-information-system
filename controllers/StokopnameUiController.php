<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\StokopnameController as Pair;
use tlm\his\FatmaPharmacy\views\StokopnameUi\{
    FormAktivasi,
    FormDetailOpname,
    FormFloorStock,
    FormKoreksiOpname,
    FormStokKadaluarsa,
    FormStokRusak,
    FormStokopname,
    SearchLaporanDepo,
    TableFloorStock,
    TableFloorStockDesember,
    TableFloorStockMaret,
    TableHistory,
    TableKoreksi,
    TableOpnameUser,
    TableStokKadaluarsa,
    TableStokRusak,
    TableStokopname,
    ViewOpnameDepo,
    ViewOpnameUserGas,
};
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
class StokopnameUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTableStokopname         = Yii::$app->canAccess([Pair::class, "actionTableStokopnameData"]);
        $canSeeTableHistory            = Yii::$app->canAccess([Pair::class, "actionTableHistoryData"]);
        $canSeeTableOpnameUser         = Yii::$app->canAccess([Pair::class, "actionTableOpnameUserData"]);
        $canSeeTableFloorStock         = Yii::$app->canAccess([Pair::class, "actionTableFloorStockData"]);
        $canSeeTableFloorStockDesember = Yii::$app->canAccess([Pair::class, "actionTableFloorStockDesemberData"]);
        $canSeeTableFloorStockMaret    = Yii::$app->canAccess([Pair::class, "actionTableFloorStockMaretData"]);
        $canSeeTableStokKadaluarsa     = Yii::$app->canAccess([Pair::class, "actionTableStokKadaluarsaData"]);
        $canSeeTableStokRusak          = Yii::$app->canAccess([Pair::class, "actionTableStokRusakData"]);
        $canSeeTableKoreksi            = Yii::$app->canAccess([Pair::class, "actionTableKoreksiData"]);
        $canSeeFormStokopname          = Yii::$app->canAccess([Pair::class, "actionSaveStokopname"]);
        $canSeeFormAktivasi            = Yii::$app->canAccess([Pair::class, "actionSaveAktivasi"]);
        $canSeeFormDetailOpname        = Yii::$app->canAccess([Pair::class, "actionSaveDetailOpname"]);
        $canSeeFormFloorStock          = Yii::$app->canAccess([Pair::class, "actionSaveFloorStock"]);
        $canSeeFormStokKadaluarsa      = Yii::$app->canAccess([Pair::class, "actionSaveStokKadaluarsa"]);
        $canSeeFormKoreksiOpname       = Yii::$app->canAccess([Pair::class, "actionSaveKoreksiOpname"]);
        $canSeeFormStokRusak           = Yii::$app->canAccess([Pair::class, "actionSaveStokRusak"]);
        $canSeeViewOpnameDepo          = Yii::$app->canAccess([Pair::class, "actionViewOpnameDepoData"]);
        $canSeeViewOpnameUserGas       = Yii::$app->canAccess([Pair::class, "actionViewOpnameUserGasData"]);
        $canSeeLaporanSemuaDepo        = Yii::$app->canAccess([Pair::class, "actionLaporanSemuaDepoData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Stokopname";
        $index->tabs = [
            ["title" => "Table Stokopname",           "canSee" => $canSeeTableStokopname,         "registerId" => Yii::$app->actionToId([self::class, "actionTableStokopname"])],
            ["title" => "Table History",              "canSee" => $canSeeTableHistory,            "registerId" => Yii::$app->actionToId([self::class, "actionTableHistory"])],
            ["title" => "Table Opname User",          "canSee" => $canSeeTableOpnameUser,         "registerId" => Yii::$app->actionToId([self::class, "actionTableOpnameUser"])],
            ["title" => "Table Floor Stock",          "canSee" => $canSeeTableFloorStock,         "registerId" => Yii::$app->actionToId([self::class, "actionTableFloorStock"])],
            ["title" => "Table Floor Stock Desember", "canSee" => $canSeeTableFloorStockDesember, "registerId" => Yii::$app->actionToId([self::class, "actionTableFloorStockDesember"])],
            ["title" => "Table Floor Stock Maret",    "canSee" => $canSeeTableFloorStockMaret,    "registerId" => Yii::$app->actionToId([self::class, "actionTableFloorStockMaret"])],
            ["title" => "Table Stok Kadaluarsa",      "canSee" => $canSeeTableStokKadaluarsa,     "registerId" => Yii::$app->actionToId([self::class, "actionTableStokKadaluarsa"])],
            ["title" => "Table Stok Rusak",           "canSee" => $canSeeTableStokRusak,          "registerId" => Yii::$app->actionToId([self::class, "actionTableStokRusak"])],
            ["title" => "Table Koreksi",              "canSee" => $canSeeTableKoreksi,            "registerId" => Yii::$app->actionToId([self::class, "actionTableKoreksi"])],
            ["title" => "Form Stokopname",            "canSee" => $canSeeFormStokopname,          "registerId" => Yii::$app->actionToId([self::class, "actionFormStokopname"])],
            ["title" => "Form Aktivasi",              "canSee" => $canSeeFormAktivasi,            "registerId" => Yii::$app->actionToId([self::class, "actionFormAktivasi"])],
            ["title" => "Form Detail Opname",         "canSee" => $canSeeFormDetailOpname,        "registerId" => Yii::$app->actionToId([self::class, "actionFormDetailOpname"])],
            ["title" => "Form Floor Stock",           "canSee" => $canSeeFormFloorStock,          "registerId" => Yii::$app->actionToId([self::class, "actionFormFloorStock"])],
            ["title" => "Form Stok Kadaluarsa",       "canSee" => $canSeeFormStokKadaluarsa,      "registerId" => Yii::$app->actionToId([self::class, "actionFormStokKadaluarsa"])],
            ["title" => "Form Koreksi Opname",        "canSee" => $canSeeFormKoreksiOpname,       "registerId" => Yii::$app->actionToId([self::class, "actionFormKoreksiOpname"])],
            ["title" => "Form Stok Rusak",            "canSee" => $canSeeFormStokRusak,           "registerId" => Yii::$app->actionToId([self::class, "actionFormStokRusak"])],
            ["title" => "View Opname Depo",           "canSee" => $canSeeViewOpnameDepo,          "registerId" => Yii::$app->actionToId([self::class, "actionViewOpnameDepo"])],
            ["title" => "View Opname User Gas",       "canSee" => $canSeeViewOpnameUserGas,       "registerId" => Yii::$app->actionToId([self::class, "actionViewOpnameUserGas"])],
            ["title" => "Laporan Semua Depo",         "canSee" => $canSeeLaporanSemuaDepo,        "registerId" => Yii::$app->actionToId([self::class, "actionLaporanSemuaDepo"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#index    the original method
     */
    public function actionTableStokopname(): string
    {
        $view = new TableStokopname(
            registerId:               Yii::$app->actionToId(__METHOD__),
            auditAccess:              [false],
            dataUrl:                  Yii::$app->actionToUrl([Pair::class, "actionTableStokopnameData"]),
            openOpnameUrl:            Yii::$app->actionToUrl([Pair::class, "actionOpenOpname"]),
            belumInputViewWidgetId:   Yii::$app->actionToId([Pair::class, "actionViewBelumInput"]),
            detailOpnameViewWidgetId: Yii::$app->actionToId([self::class, "actionFormDetailOpname"]),
            opnameDepoViewWidgetId:   Yii::$app->actionToId([self::class, "actionViewOpnameDepo"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#add    the original method
     */
    public function actionFormStokopname(): string
    {
        $view = new FormStokopname(
            registerId:     Yii::$app->actionToId(__METHOD__),
            addAccess:      [true],
            editAccess:     [true],
            dataUrl:        Yii::$app->actionToUrl([Pair::class, "actionFormStokopnameData"]),
            actionUrl:      Yii::$app->actionToUrl([Pair::class, "actionSaveStokopname"]),
            stokAdmUrl:     Yii::$app->actionToUrl([Katalog1Controller::class, "actionSelectStokAdm"]),
            katalogDepoUrl: Yii::$app->actionToUrl([Pair::class, "actionKatalogDepo"]),
            tableWidgetId:  Yii::$app->actionToId([self::class, "actionTableOpnameUser"]),
            depoSelect:     Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#history    the original method
     */
    public function actionTableHistory(): string
    {
        $view = new TableHistory(
            registerId:       Yii::$app->actionToId(__METHOD__),
            editAccess:       [false],
            auditAccess:      [false],
            dataUrl:          Yii::$app->actionToUrl([Pair::class, "actionTableHistoryData"]),
            editFormWidgetId: Yii::$app->actionToId([self::class, "actionFormStokopname"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#opnameuser    the original method
     */
    public function actionTableOpnameUser(): string
    {
        $view = new TableOpnameUser(
            registerId:                Yii::$app->actionToId(__METHOD__),
            dataUrl:                   Yii::$app->actionToUrl([Pair::class, "actionTableOpnameUserData"]),
            opnameUserGasViewWidgetId: Yii::$app->actionToId([self::class, "actionViewOpnameUserGas"]),
            opnameUserViewWidgetId:    Yii::$app->actionToId([Pair::class, "actionViewOpnameUser"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#activation    the original method
     */
    public function actionFormAktivasi(): string
    {
        $view = new FormAktivasi(
            registerId: Yii::$app->actionToId(__METHOD__),
            addAccess:  [true],
            editAccess: [true],
            dataUrl:    Yii::$app->actionToUrl([Pair::class, "actionFormAktivasiData"]),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSaveAktivasi"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#detailopname    the original method
     */
    public function actionFormDetailOpname(): string
    {
        $view = new FormDetailOpname(
            registerId:    Yii::$app->actionToId(__METHOD__),
            addAccess:     [true],
            dataUrl:       Yii::$app->actionToUrl([Pair::class, "actionFormDetailOpnameData"]),
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionSaveDetailOpname"]),
            tableWidgetId: Yii::$app->actionToId([StokopnameUiController::class, "actionTableStokopname"]),
            depoSelect:    Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#floorstock    the original method
     */
    public function actionFormFloorStock(): string
    {
        $view = new FormFloorStock(
            registerId:         Yii::$app->actionToId(__METHOD__),
            addAccess:          [true],
            dataUrl:            Yii::$app->actionToUrl([Pair::class, "actionFormFloorStockData"]),
            actionUrl:          Yii::$app->actionToUrl([Pair::class, "actionSaveFloorStock"]),
            katalogAcplUrl:     Yii::$app->actionToUrl([Katalog1Controller::class, "actionSelect"]),
            katalogFloorUrl:    Yii::$app->actionToUrl([Pair::class, "actionKatalogFloor"]),
            printFloorStockUrl: Yii::$app->actionToUrl([Pair::class, "actionPrintFloorStock"]),
            kembaliWidgetId:    Yii::$app->actionToId([self::class, "actionTableStokopname"]),
            tableWidgetId:      Yii::$app->actionToId([self::class, "actionTableFloorStock"]),
            unitSelect:         Yii::$app->actionToUrl([DepoController::class, "actionSelect8Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listfloor    the original method
     */
    public function actionTableFloorStock(): string
    {
        $view = new TableFloorStock(
            registerId:            Yii::$app->actionToId(__METHOD__),
            dataUrl:               Yii::$app->actionToId([Pair::class, "actionTableFloorStockData"]),
            floorDesemberWidgetId: Yii::$app->actionToId([self::class, "actionTableFloorStockDesember"]),
            floorMaretWidgetId:    Yii::$app->actionToId([self::class, "actionTableFloorStockMaret"]),
            formWidgetId:          Yii::$app->actionToId([self::class, "actionFormFloorStock"]),
            printWidgetId:         Yii::$app->actionToId([Pair::class, "actionPrintFloorStock"]),

            // unused
            // verifikasiUrl: Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"]),
            // transferUrl:   Yii::$app->actionToUrl([EresepDepoController::class, "actionTransfer"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listfloorsept    the original method
     */
    public function actionTableFloorStockDesember(): string
    {
        $view = new TableFloorStockDesember(
            registerId:    Yii::$app->actionToId(__METHOD__),
            dataUrl:       Yii::$app->actionToUrl([Pair::class, "actionTableFloorStockDesemberData"]),
            formWidgetId:  Yii::$app->actionToId([self::class, "actionFormFloorStock"]),
            printWidgetId: Yii::$app->actionToId([Pair::class, "actionPrintFloorStock"]),

            // unused
            // verifikasiUrl: Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"])
            // transferUrl:   Yii::$app->actionToUrl([EresepDepoController::class, "actionTransfer"])
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listfloormaret    the original method
     */
    public function actionTableFloorStockMaret(): string
    {
        $view = new TableFloorStockMaret(
            registerId:       Yii::$app->actionToId(__METHOD__),
            dataUrl:          Yii::$app->actionToUrl([Pair::class, "actionTableFloorStockMaretData"]),
            editFormWidgetId: Yii::$app->actionToUrl([self::class, "actionFormFloorStock"]),
            printWidgetId:    Yii::$app->actionToUrl([Pair::class, "actionPrintFloorStock"]),

            // unused
            // verifikasiUrl: Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"])
            // transferUrl:   Yii::$app->actionToUrl([EresepDepoController::class, "actionTransfer"])
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#expired    the original method
     */
    public function actionFormStokKadaluarsa(): string
    {
        $view = new FormStokKadaluarsa(
            registerId:      Yii::$app->actionToId(__METHOD__),
            addAccess:       [true],
            dataUrl:         Yii::$app->actionToUrl([Pair::class, "actionFormStokKadaluarsaData"]),
            actionUrl:       Yii::$app->actionToUrl([Pair::class, "actionSaveStokKadaluarsa"]),
            stokAdmUrl:      Yii::$app->actionToUrl([Katalog1Controller::class, "actionSelectStokAdm"]),
            katalogFloorUrl: Yii::$app->actionToUrl([Pair::class, "actionKatalogFloor"]),
            printExpiredUrl: Yii::$app->actionToUrl([Pair::class, "actionPrintExpired"]),
            tableWidgetId:   Yii::$app->actionToId([self::class, "actionTableStokKadaluarsa"]),
            depoSelect:      Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listexpired    the original method
     */
    public function actionTableStokKadaluarsa(): string
    {
        $view = new TableStokKadaluarsa(
            registerId:       Yii::$app->actionToId(__METHOD__),
            dataUrl:          Yii::$app->actionToId([Pair::class, "actionTableStokKadaluarsaData"]),
            addFormWidgetId:  Yii::$app->actionToId([self::class, "actionFormStokKadaluarsa"]),
            editFormWidgetId: Yii::$app->actionToId([self::class, "actionFormStokKadaluarsa"]),
            printWidgetId:    Yii::$app->actionToId([Pair::class, "actionPrintExpired"]),

            // unused
            // verifikasiUrl: Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"]),
            // transferUrl:   Yii::$app->actionToUrl([EresepDepoController::class, "actionTransfer"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listrusak    the original method
     */
    public function actionTableStokRusak(): string
    {
        $view = new TableStokRusak(
            registerId:    Yii::$app->actionToId(__METHOD__),
            dataUrl:       Yii::$app->actionToId([Pair::class, "actionTableStokRusakData"]),
            formWidgetId:  Yii::$app->actionToId([self::class, "actionFormStokRusak"]),
            printRekapUrl: Yii::$app->actionToUrl([Pair::class, "actionPrintRusakRekap"]),
            printItemUrl:  Yii::$app->actionToUrl([Pair::class, "actionPrintRusak"]),

            // unused
            // verifikasiUrl: Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"]),
            // transferUrl:   Yii::$app->actionToUrl([EresepDepoController::class, "actionTransfer"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#koreksiopname    the original method
     */
    public function actionFormKoreksiOpname(): string
    {
        $view = new FormKoreksiOpname(
            registerId:      Yii::$app->actionToId(__METHOD__),
            addAccess:       [true],
            dataUrl:         Yii::$app->actionToUrl([Pair::class, "actionFormKoreksiOpnameData"]),
            actionUrl:       Yii::$app->actionToUrl([Pair::class, "actionSaveKoreksiOpname"]),
            katalogAcplUrl:  Yii::$app->actionToUrl([Katalog1Controller::class, "actionSelect"]),
            katalogFloorUrl: Yii::$app->actionToUrl([Pair::class, "actionKatalogFloor"]),
            printWidgetId:   Yii::$app->actionToId([Pair::class, "actionPrintKoreksi"]),
            kembaliWidgetId: Yii::$app->actionToId([self::class, "actionTableStokopname"]),
            tableWidgetId:   Yii::$app->actionToId([self::class, "actionTableKoreksi"]),
            depoSelect:      Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#rusak    the original method
     */
    public function actionFormStokRusak(): string
    {
        $view = new FormStokRusak(
            registerId:      Yii::$app->actionToId(__METHOD__),
            addAccess:       [true],
            dataUrl:         Yii::$app->actionToUrl([Pair::class, "actionFormStokRusakData"]),
            actionUrl:       Yii::$app->actionToUrl([Pair::class, "actionSaveStokRusak"]),
            namaBarangUrl:   Yii::$app->actionToUrl([Katalog1Controller::class, "actionSelectStokAdm"]),
            katalogFloorUrl: Yii::$app->actionToUrl([Pair::class, "actionKatalogFloor"]),
            printRusakUrl:   Yii::$app->actionToUrl([Pair::class, "actionPrintRusak"]),
            kembaliWidgetId: Yii::$app->actionToId([self::class, "actionTableStokRusak"]),
            depoSelect:      Yii::$app->actionToUrl([DepoController::class, "actionSelect3Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listkoreksi    the original method
     */
    public function actionTableKoreksi(): string
    {
        $view = new TableKoreksi(
            registerId:    Yii::$app->actionToId(__METHOD__),
            dataUrl:       Yii::$app->actionToId([Pair::class, "actionTableKoreksiData"]),
            formWidgetId:  Yii::$app->actionToId([self::class, "actionFormKoreksiOpname"]),
            printWidgetId: Yii::$app->actionToId([Pair::class, "actionPrintKoreksi"]),

            // unused
            // verifikasiUrl: Yii::$app->actionToUrl([EresepDepoController::class, "actionVerifikasi"]),
            // transferUrl:   Yii::$app->actionToUrl([EresepDepoController::class, "actionTransfer"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#laporansemuadepo    the original method
     */
    public function actionLaporanSemuaDepo(): string
    {
        $view = new SearchLaporanDepo(
            registerId: Yii::$app->actionToId(__METHOD__),
            actionUrl:  Yii::$app->actionToUrl([Pair::class, "actionLaporanSemuaDepoData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#viewopnamedepo    the original method
     */
    public function actionViewOpnameDepo(): string
    {
        $view = new ViewOpnameDepo(
            registerId: Yii::$app->actionToId(__METHOD__),
            dataUrl:    Yii::$app->actionToUrl([Pair::class, "actionViewOpnameDepoData"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#viewopnameusergas    the original method
     */
    public function actionViewOpnameUserGas(): string
    {
        $view = new ViewOpnameUserGas(
            registerId: Yii::$app->actionToId(__METHOD__),
            dataUrl:    Yii::$app->actionToUrl([Pair::class, "actionViewOpnameUserGasData"]),
        );
        return $view->__toString();
    }
}
