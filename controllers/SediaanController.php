<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\{DataNotExistException, FarmasiModel2};
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
class SediaanController extends BaseController
{

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/sediaan.php#index    the original method
     */
    public function actionTableData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SDN.id           AS id,
                SDN.kode         AS kode,
                SDN.nama_sediaan AS nama,
                SDN.sysdate_updt AS updatedTime,
                user.name        AS updatedBy
            FROM db1.masterf_sediaan AS SDN
            LEFT JOIN db1.user ON userid_updt = user.id
        ";
        $daftarSediaan = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarSediaan);
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/sediaan.php#add    the original method
     */
    public function actionSave(): void
    {
        [   "kode" => $kode,
            "nama_sediaan" => $namaSediaan,
            "action" => $action,
            "id" => $id
        ] = Yii::$app->request->post();

        $daftarField = [
            "kode",
            "nama_sediaan",
            "userid_updt",
        ];
        $dataSediaan = [
            "kode" => $kode,
            "nama_sediaan" => $namaSediaan,
            "userid_updt" => Yii::$app->userFatma->id,
        ];

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $fm2->saveData("masterf_sediaan", $daftarField, $dataSediaan);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_sediaan", $daftarField, $dataSediaan, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/sediaan.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                kode         AS kode,
                nama_sediaan AS namaSediaan,
                userid_updt  AS useridUpdate,
                sysdate_updt AS sysdateUpdate
            FROM db1.masterf_sediaan
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $kode];
        $sediaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$sediaan) throw new DataNotExistException($kode);

        return json_encode($sediaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/sediaan.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_sediaan
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $berhasilHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/sediaan.php#select    the original method
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
                nama_sediaan AS nama
            FROM db1.masterf_sediaan
            WHERE
                kode LIKE :val
                OR nama_sediaan LIKE :val
            ORDER BY nama_sediaan ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarSediaan = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarSediaan);
    }

    /**
     * @author Hendra Gunawan
     * @see - (none)
     */
    public function actionCekUnik()
    {
        // TODO: php: uncategorized: finish this.
    }
}
