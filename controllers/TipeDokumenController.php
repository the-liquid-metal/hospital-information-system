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
class TipeDokumenController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectBulan1Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS value,
                tipe_doc AS label
            FROM db1.masterf_tipedoc
            WHERE modul = 'bulan'
            ORDER BY id ASC
        ";
        $daftarTipeDokumenBulan = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarTipeDokumenBulan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectBulan2Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS value,
                tipe_doc AS label
            FROM db1.masterf_tipedoc
            WHERE modul = 'bulan'
            ORDER BY id ASC
        ";
        $daftarTipeDokumenBulan = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarTipeDokumenBulan, ["value" => "", "label" => "Semua Dokumen Bulan"]);
        return json_encode($daftarTipeDokumenBulan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPembelian1Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS value,
                tipe_doc AS label
            FROM db1.masterf_tipedoc
            WHERE modul = 'pembelian'
            ORDER BY kode ASC
        ";
        $daftarTipeDokumenPembelian = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarTipeDokumenPembelian);
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
                kode     AS value,
                tipe_doc AS label
            FROM db1.masterf_tipedoc
            WHERE modul = 'pembelian'
            ORDER BY kode ASC
        ";
        $daftarTipeDokumenPembelian = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarTipeDokumenPembelian, ["value" => "", "label" => "Semua Dokumen Pembelian"]);
        return json_encode($daftarTipeDokumenPembelian);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectDistribusi1Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS value,
                tipe_doc AS label
            FROM db1.masterf_tipedoc
            WHERE modul = 'distribusi'
            ORDER BY kode ASC
        ";
        $daftarTipeDokumenDistribusi = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarTipeDokumenDistribusi);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectDistribusi2Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS value,
                tipe_doc AS label
            FROM db1.masterf_tipedoc
            WHERE modul = 'distribusi'
            ORDER BY kode ASC
        ";
        $daftarTipeDokumenDistribusi = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarTipeDokumenDistribusi, ["value" => "", "label" => "Semua Dokumen Distribusi"]);
        return json_encode($daftarTipeDokumenDistribusi);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPerencanaan1Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS value,
                tipe_doc AS label
            FROM db1.masterf_tipedoc
            WHERE modul = 'perencanaan'
            ORDER BY kode ASC
        ";
        $daftarTipeDokumenPerencanaan = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarTipeDokumenPerencanaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     */
    public function actionSelectPerencanaan2Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS value,
                tipe_doc AS label
            FROM db1.masterf_tipedoc
            WHERE modul = 'perencanaan'
            ORDER BY kode ASC
        ";
        $daftarTipeDokumenPerencanaan = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarTipeDokumenPerencanaan, ["value" => "", "label" => "Semua Dokumen Perencanaan"]);
        return json_encode($daftarTipeDokumenPerencanaan);
    }
}
