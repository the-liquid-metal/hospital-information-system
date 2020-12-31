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
class DokterController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/masterfdokter.php#typeahead2    the original method
     */
    public function actionTypeahead2(): string
    {
        ["nama" => $namaPenerima, "max_rows" => $limit, "offset" => $offset] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__."
            SELECT -- all are in use, confirmed with view file.
                a.nm_penerima AS nama,
                a.kd_penerima AS id
            FROM db1.mmas_penerima a
            WHERE
                a.nm_penerima LIKE :namaPenerima
                AND sts_aktif = 1
                AND kd_penerima REGEXP'^0|^16'
                AND kd_penerima NOT REGEXP '^024|^013'
                AND nm_penerima NOT LIKE 'tim%'
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [":namaPenerima" => "%$namaPenerima%"];
        $data = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($data);
    }

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
                id   AS value,
                name AS label
            FROM db1.user
            WHERE name LIKE :name
        ";
        $params = [":name" => "%Dr%"];
        $daftarDokter = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarDokter);
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
                id   AS value,
                name AS label
            FROM db1.user
            WHERE name LIKE :name
        ";
        $params = [":name" => "%Dr%"];
        $daftarDokter = $connection->createCommand($sql, $params)->queryAll();
        array_unshift($daftarDokter, ["value" => "", "label" => "Semua Dokter"]);
        return json_encode($daftarDokter);
    }
}
