<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\JadwalOperasiController as Pair;
use tlm\his\FatmaPharmacy\views\JadwalOperasiUi\{
    Form,
    FormApprove,
    FormEdit,
    FormEditRuangRawat,
    Table,
    TableBooking,
    TableJadwal,
    TableReport,
    ViewDetail,
};
use tlm\libs\LowEnd\components\{DateTimeException, MasterHelper};
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
class JadwalOperasiUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable              = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeTableJadwal        = Yii::$app->canAccess([Pair::class, "actionTableJadwalData"]);
        $canSeeForm               = Yii::$app->canAccess([Pair::class, "actionSaveAdd"]);
        $canSeeFormEdit           = Yii::$app->canAccess([Pair::class, "actionSaveEdit"]);
        $canSeeFormEditRuangRawat = Yii::$app->canAccess([Pair::class, "actionSaveEditRuangRawat"]);
        $canSeeTableReport        = Yii::$app->canAccess([Pair::class, "actionTableReportData"]);
        $canSeeTableBooking       = Yii::$app->canAccess([Pair::class, "actionTableBookingData"]);
        $canSeeViewDetail         = Yii::$app->canAccess([Pair::class, "actionViewDetailData"]);
        $canSeeFormApprove        = Yii::$app->canAccess([Pair::class, "actionSaveApprove"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Jadwal Operasi";
        $index->tabs = [
            ["title" => "Table",                 "canSee" => $canSeeTable,              "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "Table Jadwal",          "canSee" => $canSeeTableJadwal,        "registerId" => Yii::$app->actionToId([self::class, "actionTableJadwal"])],
            ["title" => "Table Report",          "canSee" => $canSeeTableReport,        "registerId" => Yii::$app->actionToId([self::class, "actionTableReport"])],
            ["title" => "Table Booking",         "canSee" => $canSeeTableBooking,       "registerId" => Yii::$app->actionToId([self::class, "actionTableBooking"])],
            ["title" => "Form",                  "canSee" => $canSeeForm,               "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Edit",             "canSee" => $canSeeFormEdit,           "registerId" => Yii::$app->actionToId([self::class, "actionFormEdit"])],
            ["title" => "Form Edit Ruang Rawat", "canSee" => $canSeeFormEditRuangRawat, "registerId" => Yii::$app->actionToId([self::class, "actionFormEditRuangRawat"])],
            ["title" => "Form Approve",          "canSee" => $canSeeFormApprove,        "registerId" => Yii::$app->actionToId([self::class, "actionFormApprove"])],
            ["title" => "View Detail",           "canSee" => $canSeeViewDetail,         "registerId" => Yii::$app->actionToId([self::class, "actionViewDetail"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:           Yii::$app->actionToId(__METHOD__),
            dataUrl:              Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            operatorUrl:          Yii::$app->actionToUrl([Pair::class, "actionFindOperator"]),
            viewDetailWidgetId:   Yii::$app->actionToId([self::class, "actionViewDetail"]),
            tableWidgetId:        Yii::$app->actionToId([self::class, "actionTable"]),
            tableJadwalWidgetId:  Yii::$app->actionToId([self::class, "actionTableJadwal"]),
            tableBookingWidgetId: Yii::$app->actionToId([self::class, "actionTableBooking"]),
            formWidgetId:         Yii::$app->actionToId([self::class, "actionForm"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#listjadwal    the original method
     */
    public function actionTableJadwal(): string
    {
        $view = new TableJadwal(
            registerId:         Yii::$app->actionToId(__METHOD__),
            detailAccess:       [true],
            deleteAccess:       [true],
            dataUrl:            Yii::$app->actionToUrl([Pair::class, "actionTableJadwalData"]),
            instalasiUrl:       Yii::$app->actionToUrl([PoliController::class, "actionDropdown"]),
            operatorUrl:        Yii::$app->actionToUrl([Pair::class, "actionFindOperator"]),
            deleteUrl:          Yii::$app->actionToUrl([Pair::class, "actionDelete"]),
            tableWidgetId:      Yii::$app->actionToId([self::class, "actionTable"]),
            viewDetailWidgetId: Yii::$app->actionToId([self::class, "actionViewDetail"]),
            instalasiSelect:    Yii::$app->actionToId([MasterHelper::class, "dropdown_instalasi"]),
            poliSelect:         Yii::$app->actionToId([MasterHelper::class, "dropdown_poli"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:           Yii::$app->actionToId(__METHOD__),
            addAccess:            [true],
            addActionUrl:         Yii::$app->actionToUrl([Pair::class, "actionSaveAdd"]),
            caraBayarUrl:         Yii::$app->actionToUrl([CaraBayarController::class, "actionDropdown"]),
            registrasiAjaxUrl:    Yii::$app->actionToUrl([PenjualanController::class, "actionTestBridge"]),
            operatorUrl:          Yii::$app->actionToUrl([Pair::class, "actionFindOperatorAdd"]),
            instalasiUrl:         Yii::$app->actionToUrl([PoliController::class, "actionDropdown"]),
            cekNoRekamMedisUrl:   Yii::$app->actionToUrl([SignaUiController::class, "actionCekRmOp"]),
            diagnosaUrl:          Yii::$app->actionToUrl([PemetaanDiagnosaController::class, "actionTypeahead"]),
            cariTindakanUrl:      Yii::$app->actionToUrl([PemetaanController::class, "actionTypeahead"]),
            cariAlatUrl:          Yii::$app->actionToUrl([Pair::class, "actionSelect"]),
            addUrl:               Yii::$app->actionToUrl([PasienController::class, "actionAdd"]),
            viewNoRekamMedisUrl:  Yii::$app->actionToUrl([PasienController::class, "actionViewData"]),
            tableBookingWidgetId: Yii::$app->actionToId([self::class, "actionTableBooking"]),
            caraBayarSelect:      Yii::$app->actionToId([MasterHelper::class, "dropdown_cara_bayar"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#edit    the original method
     */
    public function actionFormEdit(): string
    {
        $view = new FormEdit(
            registerId:                     Yii::$app->actionToId(__METHOD__),
            editAccess:                     [true],
            dataUrl:                        Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:                      Yii::$app->actionToUrl([Pair::class, "actionSaveEdit"]),
            viewTempatTidurUrl:             Yii::$app->actionToUrl([TempatTidurController::class, "actionView"]),
            caraBayarUrl:                   Yii::$app->actionToUrl([CaraBayarController::class, "actionDropdown"]),
            pasienDataUrl:                  Yii::$app->actionToUrl([PasienController::class, "actionViewData"]),
            operatorUrl:                    Yii::$app->actionToUrl([Pair::class, "actionFindOperatorAdd"]),
            instalasiUrl:                   Yii::$app->actionToUrl([PoliController::class, "actionDropdown"]),
            cariDiagnosaUrl:                Yii::$app->actionToUrl([PemetaanDiagnosaController::class, "actionTypeahead"]),
            cariTindakanUrl:                Yii::$app->actionToUrl([PemetaanController::class, "actionTypeahead"]),
            cariAlatUrl:                    Yii::$app->actionToUrl([Pair::class, "actionSelect"]),
            deleteUrl:                      Yii::$app->actionToUrl([Pair::class, "actionDelete"]),
            tableBookingWidgetId:           Yii::$app->actionToId([self::class, "actionTableBooking"]),
            hubunganKeluargaPenjaminSelect: Yii::$app->actionToId([JaminanKeluargaController::class, "actionSelect1Data"]),
            instalasiSelect:                Yii::$app->actionToId([MasterHelper::class, "dropdown_instalasi"]),
            poliSelect:                     Yii::$app->actionToId([MasterHelper::class, "dropdown_poli"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#editruangrawat    the original method
     */
    public function actionFormEditRuangRawat(): string
    {
        $view = new FormEditRuangRawat(
            registerId:           Yii::$app->actionToId(__METHOD__),
            dataUrl:              Yii::$app->actionToUrl([Pair::class, "actionEditRuangRawatData"]),
            actionUrl:            Yii::$app->actionToUrl([Pair::class, "actionSaveEditRuangRawat"]),
            tableBookingWidgetId: Yii::$app->actionToId([self::class, "actionTableBooking"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#laporan    the original method
     */
    public function actionTableReport(): string
    {
        $view = new TableReport(
            registerId:    Yii::$app->actionToId(__METHOD__),
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionTableReportData"]),
            namaDokterUrl: Yii::$app->actionToUrl([Pair::class, "actionFindOperator2"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#booking    the original method
     */
    public function actionTableBooking(): string
    {
        $view = new TableBooking(
            registerId:              Yii::$app->actionToId(__METHOD__),
            approveAccess:           [true],
            editRoomAccess:          [true],
            editAccess:              [true],
            deleteAccess:            [true],
            dataUrl:                 Yii::$app->actionToUrl([Pair::class, "actionTableBookingData"]),
            deleteUrl:               Yii::$app->actionToUrl([Pair::class, "actionDelete"]),
            viewDetailWidgetId:      Yii::$app->actionToId([self::class, "actionViewDetail"]),
            tableWidgetId:           Yii::$app->actionToId([self::class, "actionTable"]),
            tableBookingWidgetId:    Yii::$app->actionToId([self::class, "actionTableBooking"]),
            formWidgetId:            Yii::$app->actionToId([self::class, "actionForm"]),
            formApproveWidgetId:     Yii::$app->actionToId([self::class, "actionFormApprove"]),
            formRuangRawatWidgetId:  Yii::$app->actionToId([self::class, "actionFormEditRuangRawat"]),
            formEditWidgetId:        Yii::$app->actionToId([self::class, "actionFormEdit"]),
            formJadwalUlangWidgetId: Yii::$app->actionToId([self::class, "actionFormEdit"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#detail    the original method
     */
    public function actionViewDetail(): string
    {
        $view = new ViewDetail(
            registerId:   Yii::$app->actionToId(__METHOD__),
            dataUrl:      Yii::$app->actionToUrl([Pair::class, "actionViewDetailData"]),
            formWidgetId: Yii::$app->actionToId([self::class, "actionFormEdit"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#approved    the original method
     */
    public function actionFormApprove(): string
    {
        $view = new FormApprove(
            registerId:           Yii::$app->actionToId(__METHOD__),
            dataUrl:              Yii::$app->actionToUrl([Pair::class, "actionApproveData"]),
            actionUrl:            Yii::$app->actionToUrl([Pair::class, "actionSaveApprove"]),
            ruangOperasiUrl:      Yii::$app->actionToUrl([Pair::class, "actionGetRuangOperasi"]),
            tableBookingWidgetId: Yii::$app->actionToUrl([self::class, "actionTableBooking"]),
        );
        return $view->__toString();
    }
}
