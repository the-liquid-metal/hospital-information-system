<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\DistribusiController as Pair;
use tlm\his\FatmaPharmacy\views\DistribusiUi\{FormGasMedis, FormReturGasMedis, Table, View, ViewDetail};
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
class DistribusiUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeForm = Yii::$app->canAccess([Pair::class, "actionSaveGasMedis"]);
        $canSeeFormReturGasMedis = Yii::$app->canAccess([Pair::class, "actionSaveReturGasMedis"]);
        $canSeeTable = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeView = Yii::$app->canAccess([Pair::class, "actionViewData"]);
        $canSeeViewDetail = Yii::$app->canAccess([Pair::class, "actionViewDetailData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Distribusi";
        $index->tabs = [
            ["title" => "Form",                 "canSee" => $canSeeForm,              "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Retur Gas Medis", "canSee" => $canSeeFormReturGasMedis, "registerId" => Yii::$app->actionToId([self::class, "actionFormReturGasMedis"])],
            ["title" => "Table",                "canSee" => $canSeeTable,             "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "View",                 "canSee" => $canSeeView,              "registerId" => Yii::$app->actionToId([self::class, "actionView"])],
            ["title" => "View Detail",          "canSee" => $canSeeViewDetail,        "registerId" => Yii::$app->actionToId([self::class, "actionViewDetail"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#addGM    the original method
     */
    public function actionForm(): string
    {
        $view = new FormGasMedis(
            registerId:          Yii::$app->actionToId(__METHOD__),
            editAccess:          [true],
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionEditGasMedisData"]),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionSaveGasMedis"]),
            cekUnikNoDokumenUrl: Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            cariKatalog1Url:     Yii::$app->actionToUrl([Pair::class, "actionSearchJsonKatalogDist"]),
            cariKatalog2Url:     Yii::$app->actionToUrl([Pair::class, "actionSearchJsonBatchTabung"]),
            viewWidgetId:        Yii::$app->actionToUrl([self::class, "actionView"]),
            penyimpanan1Select:  Yii::$app->actionToUrl([DepoController::class, "actionSelect7Data"]),
            penyimpanan2Select:  Yii::$app->actionToUrl([DepoController::class, "actionSelect8Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#returGM    the original method
     */
    public function actionFormReturGasMedis(): string
    {
        $data = [
            "kode" => "NO TRANSAKSI",
            "no_doc" => "",
            "tipe_doc" => 3,
            "tgl_doc" => Yii::$app->dateTime->todayVal("user"),
            "id_pengirim" => 60,
            "unit_pengirim" => "GUDANG GAS MEDIS",
            "id_penerima" => 7,
            "unit_penerima" => "Ruang VIP",
            "sts_priority" => 0,
            "nilai_total" => 0,
            "verkirim" => "",
            "ver_kirim" => 0,
            "user_kirim" => "------",
            "ver_tglkirim" => NULL,
            "stokgudang" => "disabled",
            "nilai_akhir" => 0,
            "action" => "add"
        ];

        $batch = [];
        foreach ($nobatch ?? [] as $bch) {
            $batch[$bch->id_katalog][] = $bch;
        }

        // TODO: php: uncategorized: to be deleted
        $this->render("xxx", [
            "data" => $data,
            "batch" => $batch,
            "disabled" => ($data["ver_kirim"] == "1") ? "disabled" : "",
        ]);

        $view = new FormReturGasMedis(
            registerId:         Yii::$app->actionToId(__METHOD__),
            editAccess:         [true],
            dataUrl:            Yii::$app->actionToUrl([Pair::class, "actionEditReturGasMedisData"]),
            actionUrl:          Yii::$app->actionToUrl([Pair::class, "actionSaveReturGasMedis"]),
            cekUnikNoKirimUrl:  Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            stockUrl:           Yii::$app->actionToUrl([Pair::class, "actionSearchJsonCheckStock"]),
            katalogAcplUrl:     Yii::$app->actionToUrl([Pair::class, "actionSearchJsonTabungReturn"]),
            cariKatalog2Url:    Yii::$app->actionToUrl([Pair::class, "actionSearchJsonBatchTabung"]),
            viewVidgetId:       Yii::$app->actionToUrl([self::class, "actionView"]),
            penyimpanan1Select: Yii::$app->actionToUrl([DepoController::class, "actionSelect8Data"]),
            penyimpanan2Select: Yii::$app->actionToUrl([DepoController::class, "actionSelect7Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId:                  Yii::$app->actionToId(__METHOD__),
            dataUrl:                     Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            infoWidgetId:                Yii::$app->actionToId([self::class, "actionView"]),
            printWidgetId:               Yii::$app->actionToId([Pair::class, "actionPrints"]), // TODO: php: missing method: TRUELY MISSING Pair::actionPrints()
            formReturGasMedisWidgetId:   Yii::$app->actionToId([self::class, "actionFormReturGasMedis"]),
            formGasMedisWidgetId:        Yii::$app->actionToId([self::class, "actionForm"]),
            deleteUrl:                   Yii::$app->actionToUrl([Pair::class, "actionAjaxDelete"]),
            tipeDokumenDistribusiSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectDistribusi1Data"]),
            depoSelect:                  Yii::$app->actionToUrl([DepoController::class, "actionSelect5Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#views    the original method
     */
    public function actionView(): string
    {
        $view = new View(
            registerId:                Yii::$app->actionToId(__METHOD__),
            dataUrl:                   Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            formGasMedisWidgetId:      Yii::$app->actionToId([self::class, "actionForm"]),
            formReturGasMedisWidgetId: Yii::$app->actionToId([self::class, "actionFormReturGasMedis"]),
            formWidgetId:              Yii::$app->actionToId([Pair::class, "actionEdit"]), // TODO: php: missing method: TRUELY MISSING static::actionEdit
            viewWidgetId:              Yii::$app->actionToId([self::class, "actionViewDetail"]),
            cetakWidgetId:             Yii::$app->actionToId([Pair::class, "actionDocuments"]), // TODO: php: missing method: TRUELY MISSING static::actionDocuments
            tableWidgetId:             Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#views    the original method
     */
    public function actionViewDetail(): string
    {
        $view = new ViewDetail(
            registerId:                Yii::$app->actionToId(__METHOD__),
            dataUrl:                   Yii::$app->actionToUrl([Pair::class, "actionViewDetailData"]),
            formGasMedisWidgetId:      Yii::$app->actionToId([self::class, "actionForm"]),
            formReturGasMedisWidgetId: Yii::$app->actionToId([self::class, "actionFormReturGasMedis"]),
            formWidgetId:              Yii::$app->actionToId([Pair::class, "actionEdit"]), // TODO: php: missing method: TRUELY MISSING static::actionEdit
            viewWidgetId:              Yii::$app->actionToId([self::class, "actionView"]),
            cetakWidgetId:             Yii::$app->actionToId([Pair::class, "actionDocuments"]), // TODO: php: missing method: TRUELY MISSING static::actionDocuments
            tableWidgetId:             Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }
}
