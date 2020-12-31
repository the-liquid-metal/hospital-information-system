<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 * @see - (none)
 */
class JaminanKeluargaController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @see - (none)
     * @noinspection PhpMethodMayBeStaticInspection
     */
    public function actionSelect1Data(): string
    {
        return json_encode([
            ["value" => 0, "label" => "Pribadi"],
            ["value" => 1, "label" => "Suami/Istri"],
            ["value" => 2, "label" => "Anak"],
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @see - (none)
     * @noinspection PhpMethodMayBeStaticInspection
     */
    public function actionSelect2Data(): string
    {
        return json_encode([
            ["value" => "", "label" => "Semua Jaminan"],
            ["value" => 0,  "label" => "Pribadi"],
            ["value" => 1,  "label" => "Suami/Istri"],
            ["value" => 2,  "label" => "Anak"],
        ]);
    }
}
