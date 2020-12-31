<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\{DataNotExistException, FarmasiModel2, FieldNotExistException};
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
class KemasanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kemasan.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "kode" => $kode,
            "namaKemasan" => $namaKemasan,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                KEM.id           AS id,
                KEM.kode         AS kode,
                KEM.nama_kemasan AS namaKemasan,
                USR.name         AS updatedBy,
                KEM.sysdate_updt AS updatedTime
            FROM db1.masterf_kemasan AS KEM
            LEFT JOIN db1.user AS USR ON userid_updt = USR.id
            WHERE
                (:kode = '' OR KEM.kode LIKE :kode)
                AND (:namaKemasan = '' OR KEM.nama_kemasan LIKE :namaKemasan)
            ORDER BY KEM.nama_kemasan
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":kode" => $kode ? "%$kode%" : "",
            ":namaKemasan" => $namaKemasan ? "%$namaKemasan%" : "",
        ];
        $daftarKemasan = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarKemasan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kemasan.php#add    the original method
     */
    public function actionSave(): void
    {
        ["kode" => $kode, "namaKemasan" => $namaKemasan, "action" => $action, "id" => $id] = Yii::$app->request->post();

        if ($kode) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.masterf_kemasan
                WHERE id = :id
                LIMIT 1
            ";
            $params = [":id" => $kode];
            $jumlahKemasan = $connection->createCommand($sql, $params)->queryScalar();
            if (!$jumlahKemasan) throw new DataNotExistException($kode);
        }

        $dataKemasan = [
            "kode" => $kode,
            "nama_kemasan" => $namaKemasan,
            "userid_updt" => Yii::$app->userFatma->id,
        ];
        $daftarField = array_keys($dataKemasan);

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $fm2->saveData("masterf_kemasan", $daftarField, $dataKemasan);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_kemasan", $daftarField, $dataKemasan, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kemasan.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                kode         AS kode,
                kode_med     AS kodeMed,
                nama_kemasan AS namaKemasan,
                sts_aktif    AS statusAktif,
                userid_updt  AS useridUpdate,
                sysdate_updt AS sysdateUpdate
            FROM db1.masterf_kemasan
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $id];
        $kemasan = $connection->createCommand($sql, $params)->queryOne();
        if (!$kemasan) throw new DataNotExistException($id);

        return json_encode($kemasan);
    }

    /**
     * @author Hendra Gunawan
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kemasan.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        if (!in_array($field, ["id", "kode", "kode_med", "nama_kemasan", "sts_aktif"])) {
            throw new FieldNotExistException($field);
        }

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_kemasan
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $jumlahRowHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($jumlahRowHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kemasan.php#select    the original method
     */
    public function actionSelect(): string
    {
        ["q" => $val] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                id           AS id,
                kode         AS kode,
                nama_kemasan AS nama
            FROM db1.masterf_kemasan
            WHERE
                kode LIKE :val
                OR nama_kemasan LIKE :val
            ORDER BY nama_kemasan ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarKemasan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKemasan);
    }

    /**
     * @author Hendra Gunawan
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/masterdata.php#checkinput as the source of copied text
     *
     * Designed for widget validation mechanism. To simplify code, this method always returns a success HTTP status. the
     * response body can be json "false" or object.
     */
    public function actionCekUnik(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();
        if (!in_array($field, ["kode", "nama_kemasan"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                kode         AS kode,
                nama_kemasan AS namaKemasan
            FROM db1.masterf_kemasan
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
