<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\HargaController as Pair;
use tlm\his\FatmaPharmacy\views\HargaUi\{HargaJualBarangTable, HargaPerolehanTable, HargaPembelianUlpTable};
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
class HargaUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTableHargaJualBarang = Yii::$app->canAccess([Pair::class, "actionHargaJualTableData"]);
        $canSeeTableHargaPerolehan = Yii::$app->canAccess([Pair::class, "actionHargaPerolehanTableData"]);
        $canSeeTableMaster = Yii::$app->canAccess([Pair::class, "actionHargaPembelianUlpTableData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Harga";
        $index->tabs = [
            ["title" => "Harga Jual",      "canSee" => $canSeeTableHargaJualBarang, "registerId" => Yii::$app->actionToId([self::class, "actionHargaJualBarangTable"])],
            ["title" => "Harga Perolehan", "canSee" => $canSeeTableHargaPerolehan,  "registerId" => Yii::$app->actionToId([self::class, "actionHargaPerolehanTable"])],
            ["title" => "Master",          "canSee" => $canSeeTableMaster,          "registerId" => Yii::$app->actionToId([self::class, "actionHargaPembelianUlpTable"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/masterharga.php#hargajual    the original method
     */
    public function actionHargaJualBarangTable(): string
    {
        $view = new HargaJualBarangTable(
            registerId:  Yii::$app->actionToId(__METHOD__),
            auditAccess: [true],
            dataUrl:     Yii::$app->actionToUrl([Pair::class, "actionHargaJualTableData"]),
            hargaUrl:    Yii::$app->actionToUrl([Pair::class, "actionAktifasi"]), // TODO: php: missing method: COMMENTED Pair::actionAktifasi()
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/masterharga.php#hargaperolehan    the original method
     */
    public function actionHargaPerolehanTable(): string
    {
        $view = new HargaPerolehanTable(
            registerId:  Yii::$app->actionToId(__METHOD__),
            auditAccess: [true],
            dataUrl:     Yii::$app->actionToUrl([Pair::class, "actionHargaPerolehanTableData"]),
            aktivasiUrl: Yii::$app->actionToUrl([Pair::class, "actionAktifasi"]), // TODO: php: missing method: COMMENTED Pair::actionAktifasi()
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/masterharga.php#index    the original method
     */
    public function actionHargaPembelianUlpTable(): string
    {
        $view = new HargaPembelianUlpTable(
            registerId:  Yii::$app->actionToId(__METHOD__),
            auditAccess: [true],
            dataUrl:     Yii::$app->actionToUrl([Pair::class, "actionHargaPembelianUlpTableData"]),
            aktivasiUrl: Yii::$app->actionToUrl([Pair::class, "actionAktifasi"]), // TODO: php: missing method: COMMENTED Pair::actionAktifasi()
        );
        return $view->__toString();
    }
}
