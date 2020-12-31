<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\IkiDokterController as Pair;
use tlm\his\FatmaPharmacy\views\IkiDokterUi\TableLaporan;
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
class IkiDokterUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTableLaporan = Yii::$app->canAccess([Pair::class, "actionTableLaporanData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Iki Dokter";
        $index->tabs = [
            ["title" => "Table Laporan", "canSee" => $canSeeTableLaporan, "registerId" => Yii::$app->actionToId([self::class, "actionTableLaporan"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/ikidokter.php#print_laporan    the original method
     */
    public function actionTableLaporan(): string
    {
        $view = new TableLaporan(
            registerId:     Yii::$app->actionToId(__METHOD__),
            actionUrl:      Yii::$app->actionToUrl([Pair::class, "actionTableLaporanData"]),
            dokterBySmfUrl: Yii::$app->actionToUrl([DokterController::class, "actionGetDokterBySmf"]), // TODO: php: missing method: TRUELY MISSING DokterController::actionGetDokterBySmf()
            dokterSelect:   Yii::$app->actionToUrl([DokterController::class, "actionSelect2Data"]),
            smfSelect:      Yii::$app->actionToUrl([SmfController::class, "actionSelect2Data"]),
        );
        return $view->__toString();
    }
}
