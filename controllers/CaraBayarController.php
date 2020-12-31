<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class CaraBayarController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/carabayar.php#dropdown    the original method
     */
    public function actionDropdown(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kd_bayar          AS kode,
                cara_bayar        AS nama,
                klp_bayar         AS kelompokBayar,
                sts_tarif_jaminan AS statusTarifJaminan,
                status            AS status
            FROM db1.master_cara_bayar
            WHERE klp_bayar = :kelompokBayar
        ";
        $params = [":kelompokBayar" => $_POST["kelompokBayar"]];
        $daftarCaraBayar = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarCaraBayar);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPembelianData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id         AS value,
                cara_bayar AS label
            FROM db1.masterf_carabayar
            WHERE
                pembelian = 1
                AND sts_aktif = 1
            ORDER BY cara_bayar
        ";
        $daftarCaraBayar = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarCaraBayar);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPembelian2Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id         AS value,
                cara_bayar AS label
            FROM db1.masterf_carabayar
            WHERE
                pembelian = 1
                AND sts_aktif = 1
                AND id = 16         -- seriously?
            ORDER BY cara_bayar
        ";
        $daftarCaraBayar = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarCaraBayar);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPembelian3Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                cara_bayar AS value,
                cara_bayar AS label
            FROM db1.masterf_carabayar
            WHERE
                pembelian = 1
                AND sts_aktif = 1
            ORDER BY cara_bayar
        ";
        $daftarCaraBayar = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarCaraBayar);
    }


    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPenjualanData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id         AS value,
                cara_bayar AS label
            FROM db1.masterf_carabayar
            WHERE
                penjualan = 1
                AND sts_aktif = 1
            ORDER BY cara_bayar
        ";
        $daftarCaraBayar = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarCaraBayar);
    }
}
