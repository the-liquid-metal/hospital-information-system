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
class JenisHargaController extends BaseController
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
                id          AS value,
                jenis_harga AS label
            FROM db1.masterf_jenisharga
            WHERE sts_aktif = 1
            ORDER BY jenis_harga
        ";
        $daftarJenisHarga = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarJenisHarga);
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
                id          AS value,
                jenis_harga AS label
            FROM db1.masterf_jenisharga
            WHERE sts_aktif = 1
            ORDER BY jenis_harga
        ";
        $daftarJenisHarga = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarDepo, ["value" => "", "label" => "Semua Jenis Harga"]);
        return json_encode($daftarJenisHarga);
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
                id          AS value,
                jenis_harga AS label
            FROM db1.masterf_jenisharga
            WHERE id = :id
            ORDER BY jenis_harga
        ";
        $params = [":id" => $_POST["id"]];
        $daftarJenisHarga = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarJenisHarga);
    }
}
