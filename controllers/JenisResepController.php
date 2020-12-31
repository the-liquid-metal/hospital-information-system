<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 * @see - (none)
 */
class JenisResepController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect1Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_jenisresep AS idJenisResep,
                kd_jenisresep AS value,
                jenisresep    AS label
            FROM db1.masterf_jenisresep
            ORDER BY id_jenisresep ASC
        ";
        $daftarJenisResep = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarJenisResep);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect2Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_jenisresep AS idJenisResep,
                kd_jenisresep AS value,
                jenisresep    AS label
            FROM db1.masterf_jenisresep
            ORDER BY id_jenisresep ASC
        ";
        $daftarJenisResep = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarJenisResep, ["value" => "", "label" => "Semua Jenis Resep"]);
        return json_encode($daftarJenisResep);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect3Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                jenisresep    AS label,
                kd_jenisresep AS value
            FROM db1.masterf_jenisresep
            ORDER BY id_jenisresep ASC
        ";
        $daftarJenisResep = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarJenisResep);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect4Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                jenisresep    AS label,
                kd_jenisresep AS value
            FROM db1.masterf_jenisresep
            ORDER BY id_jenisresep ASC
        ";
        $daftarJenisResep = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarJenisResep, ["value" => "", "label" => "Semua Jenis Resep"]);
        return json_encode($daftarJenisResep);
    }
}
