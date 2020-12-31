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
class SubjenisAnggaranController extends BaseController
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
                B.id                                           AS value,
                CONCAT(B.subjenis_anggaran, ' (', B.kode, ')') AS label
            FROM db1.relasif_anggaran AS A
            INNER JOIN db1.masterf_subjenisanggaran AS B ON A.id_subjenis = B.id
            WHERE A.sts_aktif = 1
            ORDER BY id ASC
        ";
        $daftarSubjenisAnggaran = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarSubjenisAnggaran);
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
                B.id                                           AS value,
                CONCAT(B.subjenis_anggaran, ' (', B.kode, ')') AS label
            FROM db1.relasif_anggaran AS A
            INNER JOIN db1.masterf_subjenisanggaran AS B ON A.id_subjenis = B.id
            WHERE A.sts_aktif = 1
            ORDER BY id ASC
        ";
        $daftarSubjenisAnggaran = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarDepo, ["value" => "", "label" => "Semua Subjenis Anggaran"]);
        return json_encode($daftarSubjenisAnggaran);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see - (none)
     * @deprecated {@see \tlm\his\FatmaPharmacy\controllers\SubjenisAnggaranController::actionSelect1Data}    potential replacement
     */
    public function actionSelect3Data(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.id                AS value,
                B.subjenis_anggaran AS label,
                A.id_jenis          AS idJenis
            FROM db1.relasif_anggaran AS A
            INNER JOIN db1.masterf_subjenisanggaran AS B ON A.id_subjenis = B.id
            WHERE A.id_subjenis = :idSubjenis
            ORDER BY id ASC
        ";
        $params = [":idSubjenis" => $_POST["id_subjenis"]];
        $daftarSubjenisAnggaran = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarSubjenisAnggaran);
    }
}
