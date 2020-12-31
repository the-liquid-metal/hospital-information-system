<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 * @see - (none)
 */
class WaktuController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @see - (none)
     */
    public function actionSelectBulan1Data(): string
    {
        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        $bulanArr = [];
        for ($i = 1; $i <= 12; $i++) { // OR Yii::$app->dateTime->monthName
            $bulanArr[] = ["value" => $i, "label" => $numToMonthName($i)];
        }
        return json_encode($bulanArr);
    }

    /**
     * @author Hendra Gunawan
     * @see - (none)
     */
    public function actionSelectBulan2Data(): string
    {
        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        $bulanArr = [0 => ["value" => "", "label" => "Semua Bulan"]];
        for ($i = 1; $i <= 12; $i++) { // OR Yii::$app->dateTime->monthName
            $bulanArr[] = ["value" => $i, "label" => $numToMonthName($i)];
        }
        return json_encode($bulanArr);
    }

    /**
     * @author Hendra Gunawan
     * @see - (none)
     * @noinspection PhpMethodMayBeStaticInspection
     */
    public function actionSelectTriwulan1Data(): string
    {
        $triwulanArr = [];
        foreach (Yii::$app->dateTime->quarterStr as $i => $tri) {
            $triwulanArr[] = ["value" => $i, "label" => $tri];
        }
        return json_encode($triwulanArr);
    }

    /**
     * @author Hendra Gunawan
     * @see - (none)
     * @noinspection PhpMethodMayBeStaticInspection
     */
    public function actionSelectTriwulan2Data(): string
    {
        $triwulanArr = [0 => ["value" => "", "label" => "Semua Triwulan"]];
        foreach (Yii::$app->dateTime->quarterStr as $i => $tri) {
            $triwulanArr[] = ["value" => $i, "label" => $tri];
        }
        return json_encode($triwulanArr);
    }

    /**
     * @author Hendra Gunawan
     * @see - (none)
     * @noinspection PhpMethodMayBeStaticInspection
     */
    public function actionSelectTahun1Data(): string
    {
        $tahunArr = [];
        for ($i = date("Y") + 2; $i >= date("Y") - 3; $i--) {
            $tahunArr[] = ["value" => $i, "label" => $i];
        }
        return json_encode($tahunArr);
    }

    /**
     * @author Hendra Gunawan
     * @see - (none)
     * @noinspection PhpMethodMayBeStaticInspection
     */
    public function actionSelectTahun2Data(): string
    {
        $tahunArr = [0 => ["value" => "", "label" => "Semua Tahun"]];
        for ($i = date("Y") + 2; $i >= date("Y") - 3; $i--) {
            $tahunArr[] = ["value" => $i, "label" => $i];
        }
        return json_encode($tahunArr);
    }
}
