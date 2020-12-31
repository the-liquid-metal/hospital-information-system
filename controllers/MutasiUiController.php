<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\MutasiController as Pair;
use tlm\his\FatmaPharmacy\views\MutasiUi\{BufferDepo, BufferFarmasi, Ringkasan};
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
class MutasiUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeRingkasan = Yii::$app->canAccess([Pair::class, "actionRingkasanData"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertSaldoAwal"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertPembelian"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertHasilProduksi"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertKoreksi"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertPenjualan"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertFloorStok"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertBahanProduksi"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertRusak"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertExpired"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertReturPembelian"])
            ||  Yii::$app->canAccess([Pair::class, "actionInsertKoreksiPenerimaan"]);
        $canSeeBufferFarmasi = Yii::$app->canAccess([Pair::class, "actionUpdateBufferFarmasi"]);
        $canSeeBufferDepo = Yii::$app->canAccess([Pair::class, "actionUpdateBufferDepo"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Mutasi";
        $index->tabs = [
            ["title" => "Ringkasan",      "canSee" => $canSeeRingkasan,     "registerId" => Yii::$app->actionToId([self::class, "actionRingkasan"])],
            ["title" => "Buffer Farmasi", "canSee" => $canSeeBufferFarmasi, "registerId" => Yii::$app->actionToId([self::class, "actionBufferFarmasi"])],
            ["title" => "Buffer Depo",    "canSee" => $canSeeBufferDepo,    "registerId" => Yii::$app->actionToId([self::class, "actionBufferDepo"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#ringkasan    the original method
     */
    public function actionRingkasan(): string
    {
        $view = new Ringkasan(
            registerId:                       Yii::$app->actionToId(__METHOD__),
            dataUrl:                          Yii::$app->actionToUrl([Pair::class, "actionRingkasanData"]),
            insertInsertSaldoAwalUrl:         Yii::$app->actionToUrl([Pair::class, "actionInsertSaldoAwal"]),
            insertInsertPembelianUrl:         Yii::$app->actionToUrl([Pair::class, "actionInsertPembelian"]),
            insertInsertHasilProduksiUrl:     Yii::$app->actionToUrl([Pair::class, "actionInsertHasilProduksi"]),
            insertInsertKoreksiUrl:           Yii::$app->actionToUrl([Pair::class, "actionInsertKoreksi"]),
            insertInsertPenjualanUrl:         Yii::$app->actionToUrl([Pair::class, "actionInsertPenjualan"]),
            insertInsertFloorStokUrl:         Yii::$app->actionToUrl([Pair::class, "actionInsertFloorStok"]),
            insertInsertBahanProduksiUrl:     Yii::$app->actionToUrl([Pair::class, "actionInsertBahanProduksi"]),
            insertInsertRusakUrl:             Yii::$app->actionToUrl([Pair::class, "actionInsertRusak"]),
            insertInsertExpiredUrl:           Yii::$app->actionToUrl([Pair::class, "actionInsertExpired"]),
            insertInsertReturPembelianUrl:    Yii::$app->actionToUrl([Pair::class, "actionInsertReturPembelian"]),
            insertInsertKoreksiPenerimaanUrl: Yii::$app->actionToUrl([Pair::class, "actionInsertKoreksiPenerimaan"]),
            insertInsertTidakTerlayaniUrl:    Yii::$app->actionToUrl([Pair::class, "actionInsertTidakTerlayani"]),
            bulanSelect:                      Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunSelect:                      Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#bufferfarmasi    the original method
     * split into 2 part:
     * - part-1 (leadtime form): stay in this action and view
     * - part-2 (saring form & item table): {@see \tlm\his\FatmaPharmacy\controllers\LaporanBufferStokUiController::actionFormFarmasi}
     */
    public function actionBufferFarmasi(): string
    {
        $view = new BufferFarmasi(
            registerId:    Yii::$app->actionToId(__METHOD__),
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionUpdateBufferFarmasi"]),
            tableWidgetId: Yii::$app->actionToUrl([LaporanBufferStokUiController::class, "actionFormFarmasi"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#bufferdepo    the original method
     * split into 2 part:
     * - part-1 (leadtime form): stay in this action and view
     * - part-2 (saring form & item table): {@see \tlm\his\FatmaPharmacy\controllers\LaporanBufferStokUiController::actionFormDepo}
     */
    public function actionBufferDepo(): string
    {
        $view = new BufferDepo(
            registerId:    Yii::$app->actionToId(__METHOD__),
            actionUrl:     Yii::$app->actionToUrl([Pair::class, "actionUpdateBufferDepo"]),
            tableWidgetId: Yii::$app->actionToUrl([LaporanBufferStokUiController::class, "actionFormDepo"]),
        );
        return $view->__toString();
    }
}
