<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use PHPExcel;
use PHPExcel_Exception;
use PHPExcel_IOFactory;
use PHPExcel_Reader_Exception;
use PHPExcel_Writer_Exception;
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
class KelasTerapiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelasterapi.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "kode" => $kode,
            "kelasTerapi" => $kelasTerapi,
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
                KTR.id           AS id,
                KTR.kode         AS kode,
                KTR.kelas_terapi AS kelasTerapi,
                USR.name         AS updatedBy,
                KTR.sysdate_updt AS updatedTime
            FROM db1.masterf_kelasterapi AS KTR
            LEFT JOIN db1.user AS USR ON KTR.userid_updt = USR.id
            WHERE
                (:kode = '' OR KTR.kode LIKE :kode)
                AND (:kelasTerapi = '' OR KTR.kelas_terapi LIKE :kelasTerapi)
            ORDER BY KTR.id
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [":kode" => $kode, ":kelasTerapi" => $kelasTerapi];
        $daftarKelasTerapi = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarKelasTerapi);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelasterapi.php#add    the original method
     */
    public function actionSave(): void
    {
        ["kode" => $kode, "kelasTerapi" => $kelasTerapi, "action" => $action, "id" => $id] = Yii::$app->request->post();

        // edit fragment
        if ($kode) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.masterf_kelasterapi
                WHERE id = :id
                LIMIT 1
            ";
            $params = [":id" => $kode];
            $jumlahKelasTerapi = $connection->createCommand($sql, $params)->queryScalar();
            if (!$jumlahKelasTerapi) throw new DataNotExistException($kode);
        }

        $dataKelasTerapi = [
            "kode" => $kode,
            "kelas_terapi" => $kelasTerapi,
            "userid_updt" => Yii::$app->userFatma->id,
        ];
        $daftarField = array_keys($dataKelasTerapi);

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $fm2->saveData("masterf_kelasterapi", $daftarField, $dataKelasTerapi);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_kelasterapi", $daftarField, $dataKelasTerapi, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelasterapi.php#edit    the original method
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
                kelas_terapi AS kelasTerapi,
                userid_updt  AS useridUpdate,
                sysdate_updt AS sysdateUpdate
            FROM db1.masterf_kelasterapi
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $id];
        $kelasTerapi = $connection->createCommand($sql, $params)->queryOne();
        if (!$kelasTerapi) throw new DataNotExistException($id);

        return json_encode($kelasTerapi);
    }

    /**
     * @author Hendra Gunawan
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelasterapi.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();
        if (!in_array($field, ["id", "kode", "kelas_terapi"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_kelasterapi
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $jumlahRowHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($jumlahRowHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelasterapi.php#select    the original method
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
                kelas_terapi AS nama
            FROM db1.masterf_kelasterapi
            WHERE
                kode LIKE :val
                OR kelas_terapi LIKE :val
            ORDER BY kode ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarKelasTerapi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKelasTerapi);
    }

    /**
     * @author Hendra Gunawan
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelasterapi.php#export    the original method
     */
    public function actionExport(): void
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                KTP.id           AS id,
                KTP.kode         AS kode,
                KTP.kelas_terapi AS kelasTerapi,
                KTP.userid_updt  AS useridUpdate,
                KTP.sysdate_updt AS sysdateUpdate,
                USR.name         AS namaUser
            FROM db1.masterf_kelasterapi AS KTP
            LEFT JOIN db1.user AS USR ON KTP.userid_updt = USR.id
            ORDER BY KTP.id
        ";
        $daftarKelasTerapi = $connection->createCommand($sql)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost");

        $object->setActiveSheetIndex()
            ->setCellValue("A1", "id")
            ->setCellValue("B1", "kode")
            ->setCellValue("C1", "kelas_terapi")
            ->setCellValue("D1", "userid_updt")
            ->setCellValue("E1", "sysdate_updt")
            ->setCellValue("F1", "name");

        $no = 2;
        foreach ($daftarKelasTerapi as $row) {
            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $row->id)
                ->setCellValue("B" . $no, $row->kode)
                ->setCellValue("C" . $no, $row->kelasTerapi)
                ->setCellValue("D" . $no, $row->useridUpdate)
                ->setCellValue("E" . $no, $row->sysdateUpdate)
                ->setCellValue("F" . $no, $row->namaUser);
            $no++;
        }

        $object->getActiveSheet()->setTitle("KelasTerapi");
        $object->setActiveSheetIndex();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="kelasterapi.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($object, "Excel2007");
        $objWriter->save("php://output");
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
        if (!in_array($field, ["kode", "kelas_terapi"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                kode         AS kode,
                kelas_terapi AS kelasTerapi
            FROM db1.masterf_kelasterapi
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
