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
class BrandController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/brand.php#index    the original method
     */
    public function actionTableData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                BRN.id             AS id,
                BRN.kode           AS kode,
                GEN.nama_generik   AS namaGenerik,
                BRN.nama_dagang    AS namaDagang,
                USR.name           AS updatedBy,
                BRN.sysdate_updt   AS updatedTime
            FROM db1.masterf_brand AS BRN
            LEFT JOIN db1.masterf_generik AS GEN ON GEN.id = BRN.id_generik
            LEFT JOIN db1.user AS USR ON BRN.userid_updt = USR.id
            ORDER BY BRN.nama_dagang
        ";
        $daftarBrand = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarBrand);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/brand.php#add    the original method
     */
    public function actionSave(): void
    {
        [   "id" => $id,
            "action" => $action,
            "kode" => $kode,
            "idGenerik" => $idGenerik,
            "namaDagang" => $namaDagang,
        ] = Yii::$app->request->post();

        // edit fragment
        if ($kode) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.masterf_brand AS BRN
                WHERE BRN.id = :id
                LIMIT 1
            ";
            $params = [":id" => $kode];
            $cekBrand = $connection->createCommand($sql, $params)->queryScalar();
            if (!$cekBrand) throw new DataNotExistException($kode);
        }

        $dataBrand = [
            "kode" => $kode,
            "id_generik" => $idGenerik,
            "nama_dagang" => $namaDagang,
            "userid_updt" => Yii::$app->userFatma->id,
        ];
        $daftarField = array_keys($dataBrand);

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $fm2->saveData("masterf_brand", $daftarField, $dataBrand);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_brand", $daftarField, $dataBrand, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/brand.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                BRN.id             AS id,
                BRN.kode           AS kode,
                BRN.id_generik     AS idGenerik,
                BRN.id_jenisbarang AS idJenisBarang,
                BRN.nama_dagang    AS namaDagang,
                BRN.userid_updt    AS updatedById,
                BRN.sysdate_updt   AS updatedTime,
                USR.name           AS updatedBy,
                GEN.nama_generik   AS namaGenerik
            FROM db1.masterf_brand AS BRN
            LEFT JOIN db1.masterf_generik AS GEN ON GEN.id = BRN.id_generik
            LEFT JOIN db1.user AS USR ON BRN.userid_updt = USR.id
            WHERE BRN.id = :id
        ";
        $params = [":id" => $id];
        $brand = $connection->createCommand($sql, $params)->queryOne();
        if (!$brand) throw new DataNotExistException("Brand", "id", $id);

        return json_encode($brand);
    }

    /**
     * @author Hendra Gunawan
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/brand.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        $daftarField = ["id", "kode", "id_generik", "id_jenisbarang", "nama_dagang"];
        if (!in_array($field, $daftarField)) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_brand
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $jumlahRowHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($jumlahRowHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @deprecated
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/brand.php#select    the original method
     */
    public function actionSelect(): string
    {
        $connection = Yii::$app->dbFatma;
        ["q" => $val] = Yii::$app->request->post();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                BRN.id           AS id,
                BRN.kode         AS kode,
                BRN.nama_dagang  AS nama
            FROM db1.masterf_brand AS BRN
            WHERE
                BRN.kode LIKE :val
                OR BRN.nama_dagang LIKE :val
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarBrand = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarBrand);
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
        if (!in_array($field, ["kode", "nama_dagang"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id          AS id,
                kode        AS kode,
                nama_dagang AS namaDagang
            FROM db1.masterf_brand
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
