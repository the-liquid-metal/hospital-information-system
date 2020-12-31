<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanMutasiController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanMutasiUi\{FormBulan, FormTriwulan};
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
class LaporanMutasiUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormBulan = Yii::$app->canAccess([Pair::class, "actionReportBulanKatalogDetailJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportBulanKatalogDetailKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportBulanKatalogJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportBulanKatalogKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportBulanJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportBulanKelompok"]);
        $canSeeFormTriwulan = Yii::$app->canAccess([Pair::class, "actionReportWebTriwulanKatalogDetailJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportWebTriwulanKatalogDetailKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportWebTriwulanKatalogJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportWebTriwulanKatalogKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportWebTriwulanJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportWebTriwulanKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportPdfTriwulanKatalogDetailJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportPdfTriwulanKatalogDetailKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportPdfTriwulanKatalogJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportPdfTriwulanKatalogKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportPdfTriwulanJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportPdfTriwulanKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportExcelTriwulanKatalogDetailJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportExcelTriwulanKatalogDetailKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportExcelTriwulanKatalogJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportExcelTriwulanKatalogKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportExcelTriwulanJenisKelompok"])
            || Yii::$app->canAccess([Pair::class, "actionReportExcelTriwulanKelompok"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Mutasi";
        $index->tabs = [
            ["title" => "Form Bulan",    "canSee" => $canSeeFormBulan,    "registerId" => Yii::$app->actionToId([self::class, "actionFormBulan"])],
            ["title" => "Form Triwulan", "canSee" => $canSeeFormTriwulan, "registerId" => Yii::$app->actionToId([self::class, "actionFormTriwulan"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasi    the original method
     * last exist of actionLaporanMutasi: commit-e37d34f4
     */
    public function actionFormBulan(): string
    {
        $view = new FormBulan(
            registerId:                             Yii::$app->actionToId(__METHOD__),
            webKatalogDetailJenisKelompokActionUrl: Yii::$app->actionToUrl([Pair::class, "actionReportBulanKatalogDetailJenisKelompok"]),
            webKatalogDetailKelompokActionUrl:      Yii::$app->actionToUrl([Pair::class, "actionReportBulanKatalogDetailKelompok"]),
            webKatalogJenisKelompokActionUrl:       Yii::$app->actionToUrl([Pair::class, "actionReportBulanKatalogJenisKelompok"]),
            webKatalogKelompokActionUrl:            Yii::$app->actionToUrl([Pair::class, "actionReportBulanKatalogKelompok"]),
            webJenisKelompokActionUrl:              Yii::$app->actionToUrl([Pair::class, "actionReportBulanJenisKelompok"]),
            webKelompokActionUrl:                   Yii::$app->actionToUrl([Pair::class, "actionReportBulanKelompok"]),
            tahunSelect:                            Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            bulanSelect:                            Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulan    the original method
     * last exist of actionLaporanMutasiTriwulan: commit-e37d34f4
     */
    public function actionFormTriwulan(): string
    {
        $view = new FormTriwulan(
            registerId:     Yii::$app->actionToId(__METHOD__),
            webKatalogDetailJenisKelompokActionUrl:   Yii::$app->actionToUrl([Pair::class, "actionReportWebTriwulanKatalogDetailJenisKelompok"]),
            webKatalogDetailKelompokActionUrl:        Yii::$app->actionToUrl([Pair::class, "actionReportWebTriwulanKatalogDetailKelompok"]),
            webKatalogJenisKelompokActionUrl:         Yii::$app->actionToUrl([Pair::class, "actionReportWebTriwulanKatalogJenisKelompok"]),
            webKatalogKelompokActionUrl:              Yii::$app->actionToUrl([Pair::class, "actionReportWebTriwulanKatalogKelompok"]),
            webJenisKelompokActionUrl:                Yii::$app->actionToUrl([Pair::class, "actionReportWebTriwulanJenisKelompok"]),
            webKelompokActionUrl:                     Yii::$app->actionToUrl([Pair::class, "actionReportWebTriwulanKelompok"]),
         // web7ActionUrl:   Yii::$app->actionToUrl([Pair::class, "actionLaporanMutasiTriwulanData"]),
            pdfKatalogDetailJenisKelompokActionUrl:   Yii::$app->actionToUrl([Pair::class, "actionReportPdfTriwulanKatalogDetailJenisKelompok"]),
            pdfKatalogDetailKelompokActionUrl:        Yii::$app->actionToUrl([Pair::class, "actionReportPdfTriwulanKatalogDetailKelompok"]),
            pdfKatalogJenisKelompokActionUrl:         Yii::$app->actionToUrl([Pair::class, "actionReportPdfTriwulanKatalogJenisKelompok"]),
            pdfKatalogKelompokActionUrl:              Yii::$app->actionToUrl([Pair::class, "actionReportPdfTriwulanKatalogKelompok"]),
            pdfJenisKelompokActionUrl:                Yii::$app->actionToUrl([Pair::class, "actionReportPdfTriwulanJenisKelompok"]),
            pdfKelompokActionUrl:                     Yii::$app->actionToUrl([Pair::class, "actionReportPdfTriwulanKelompok"]),
         // pdf7ActionUrl:   Yii::$app->actionToUrl([Pair::class, "actionLaporanMutasiTriwulanData"]),
            excelKatalogDetailJenisKelompokActionUrl: Yii::$app->actionToUrl([Pair::class, "actionReportExcelTriwulanKatalogDetailJenisKelompok"]),
            excelKatalogDetailKelompokActionUrl:      Yii::$app->actionToUrl([Pair::class, "actionReportExcelTriwulanKatalogDetailKelompok"]),
            excelKatalogJenisKelompokActionUrl:       Yii::$app->actionToUrl([Pair::class, "actionReportExcelTriwulanKatalogJenisKelompok"]),
            excelKatalogKelompokActionUrl:            Yii::$app->actionToUrl([Pair::class, "actionReportExcelTriwulanKatalogKelompok"]),
            excelJenisKelompokActionUrl:              Yii::$app->actionToUrl([Pair::class, "actionReportExcelTriwulanJenisKelompok"]),
            excelKelompokActionUrl:                   Yii::$app->actionToUrl([Pair::class, "actionReportExcelTriwulanKelompok"]),
         // excel7ActionUrl: Yii::$app->actionToUrl([Pair::class, "actionLaporanMutasiTriwulanData"]),
            tahunSelect:                              Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            triwulanSelect:                           Yii::$app->actionToUrl([WaktuController::class, "actionSelectTriwulan1Data"]),
        );
        return $view->__toString();
    }
}
