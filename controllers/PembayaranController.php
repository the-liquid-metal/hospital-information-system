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
class PembayaranController extends BaseController
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
                id_cara AS value,
                cara    AS label
            FROM db1.masterf_pembayaran
        ";
        $daftarPembayaran = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarPembayaran);
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
                id_cara AS value,
                cara    AS label
            FROM db1.masterf_pembayaran
        ";
        $daftarPembayaran = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarPembayaran, ["value" => "all", "label" => "Semua Pembayaran"]);
        return json_encode($daftarPembayaran);
    }
}
