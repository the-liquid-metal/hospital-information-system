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
class DepoController extends BaseController
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
                id       AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE sts_aktif = 1
            ORDER BY kode_depo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarDepo);
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
                id       AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE sts_aktif = 1
            ORDER BY kode_depo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarDepo, ["value" => "", "label" => "Semua Depo"]);
        return json_encode($daftarDepo);
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
                id       AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE
                keterangan = 'depo'
                AND sts_aktif = 1
            ORDER BY kode_depo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarDepo);
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
                id       AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE
                keterangan = 'depo'
                AND sts_aktif = 1
            ORDER BY kode_depo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarDepo, ["value" => "", "label" => "Semua Depo"]);
        return json_encode($daftarDepo);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect5Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id       AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE
                keterangan = 'FS'
                OR id = 60
            ORDER BY namaDepo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarDepo);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect6Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE sts_aktif = 1
            ORDER BY namaDepo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarDepo);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect7Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id       AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE id = 60
            ORDER BY namaDepo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarDepo);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect8Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id       AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE keterangan = 'FS'
            ORDER BY namaDepo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarDepo);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelect9Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id       AS value,
                namaDepo AS label
            FROM db1.masterf_depo
            WHERE tipe_unit = 0
            ORDER BY KD_SUB_UNIT ASC
        ";
        $daftarPenyimpanan = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarPenyimpanan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPrinter1Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                PRD.no_printer                                                                AS value,
                CONCAT(PRD.no_printer, ' (', PRD.nama_printer, ', IP: ', PRD.ip_address, ')') AS label
            FROM db1.masterf_depo AS DPO
            LEFT JOIN db1.printer_depo AS PRD ON DPO.kode = PRD.kode_depo
            WHERE DPO.kode = :kode
            ORDER BY PRD.no_printer
        ";
        $params = [":kode" => Yii::$app->userFatma->idDepo];
        $daftarPrinter = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarPrinter);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPrinter2Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                PRD.no_printer                                                                AS value,
                CONCAT(PRD.no_printer, ' (', PRD.nama_printer, ', IP: ', PRD.ip_address, ')') AS label
            FROM db1.masterf_depo AS DPO
            LEFT JOIN db1.printer_depo AS PRD ON DPO.kode = PRD.kode_depo
            WHERE DPO.kode = :kode
            ORDER BY PRD.no_printer
        ";
        $params = [":kode" => Yii::$app->userFatma->idDepo];
        $daftarPrinter = $connection->createCommand($sql, $params)->queryAll();
        array_unshift($daftarPrinter, ["value" => "", "label" => "Semua Printer"]);
        return json_encode($daftarPrinter);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPrinter3Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    CONCAT(tipe_printer, '|||', nama_printer)     AS value,
                    CONCAT(tipe_printer, ' (', nama_printer, ')') AS label
                FROM db1.printer_depo
                GROUP BY tipe_printer
            ";
        $daftarPrinter = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarPrinter);
    }
}
