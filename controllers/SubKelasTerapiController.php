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
class SubKelasTerapiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/subkelasterapi.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "kode" => $kode,
            "kelasTerapi" => $kelasTerapi,
            "subkelasTerapi" => $subkelasTerapi,
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
                SKT.id               AS id, 
                SKT.kode             AS kode,
                SKT.subkelas_terapi  AS subkelasTerapi,
                KT.kelas_terapi      AS kelasTerapi,
                USR.name             AS updatedBy,
                SKT.sysdate_updt     AS updatedTime
            FROM db1.masterf_subkelasterapi AS SKT
            LEFT JOIN db1.masterf_kelasterapi AS KT ON KT.id = SKT.id_kelas
            LEFT JOIN db1.user AS USR ON SKT.userid_updt = USR.id
            WHERE
                (:kode = '' OR SKT.kode LIKE :kode)
                AND (:kelasTerapi = '' OR KT.kelas_terapi LIKE :kelasTerapi)
                AND (:subkelasTerapi = '' OR SKT.subkelas_terapi LIKE :subkelasTerapi)
            ORDER BY SKT.id
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":kode" => $kode ? "%$kode%" : "",
            ":kelasTerapi" => $kelasTerapi ? "%$kelasTerapi%" : "",
            ":subkelasTerapi" => $subkelasTerapi ? "%$subkelasTerapi%" : "",
        ];
        $daftarSubkelasTerapi = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarSubkelasTerapi);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/subkelasterapi.php#add    the original method
     */
    public function actionSave(): void
    {
        [   "id" => $id,
            "kode" => $kode,
            "idKelasTerapi" => $idKelasTerapi,
            "subkelasTerapi" => $subkelasTerapi,
            "action" => $action,
        ] = Yii::$app->request->post();

        if ($kode) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*)
                FROM db1.masterf_subkelasterapi AS SKT
                WHERE SKT.id = :kode
            ";
            $params = [":kode" => $kode];
            $jumlahSubkelasTerapi = $connection->createCommand($sql, $params)->queryScalar();
            if (!$jumlahSubkelasTerapi) throw new DataNotExistException($kode);
        }

        $dataSubkelasTerapi = [
            "kode" => $kode,
            "id_kelas" => $idKelasTerapi,
            "subkelas_terapi" => $subkelasTerapi,
            "userid_updt" => Yii::$app->userFatma->id,
        ];

        $fm2 = new FarmasiModel2;
        $daftarField = array_keys($dataSubkelasTerapi);
        if ($action == "add") {
            $fm2->saveData("masterf_subkelasterapi", $daftarField, $dataSubkelasTerapi);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_subkelasterapi", $daftarField, $dataSubkelasTerapi, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/subkelasterapi.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? new MissingPostParamException("kode");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SKT.id               AS id,
                SKT.kode             AS kode,
                SKT.id_kelas         AS idKelas,
                SKT.subkelas_terapi  AS subkelasTerapi,
                SKT.kode_kelasterapi AS kodeKelasTerapi,
                SKT.userid_updt      AS useridUpdate,
                SKT.sysdate_updt     AS sysdateUpdate,
                KT.kelas_terapi      AS kelasTerapi
            FROM db1.masterf_subkelasterapi AS SKT
            LEFT JOIN db1.masterf_kelasterapi AS KT ON KT.id = skt.id_kelas
            WHERE skt.id = :id
            LIMIT 1
        ";
        $params = [":id" => $kode];
        $subkelasTerapi = $connection->createCommand($sql, $params)->queryOne();
        if (!$subkelasTerapi) throw new DataNotExistException($kode);

        return json_encode($subkelasTerapi);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/subkelasterapi.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        if (!in_array($field, ["id", "kode", "id_kelas", "subkelas_terapi", "kode_kelasterapi"])) {
            throw new FieldNotExistException($field);
        }

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_subkelasterapi
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $berhasilHapus = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilHapus) throw new DataNotExistException($val);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM rsupf.`masterf_subkelas-generik`
            WHERE id_subkelasterapi = :idSubkelasTerapi
        ";
        $params = [":idSubkelasTerapi" => $val];
        $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/subkelasterapi.php#select    the original method
     */
    public function actionSelect(): string
    {
        $connection = Yii::$app->dbFatma;
        ["q" => $val] = Yii::$app->request->post();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                id              AS id,
                kode            AS kode,
                subkelas_terapi AS nama
            FROM db1.masterf_subkelasterapi AS SKT
            WHERE
                kode LIKE :val
                OR subkelas_terapi LIKE :val
            ORDER BY SKT.kode ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarSubkelasTerapi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarSubkelasTerapi);
    }

    /**
     * @author Hendra Gunawan
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/subkelasterapi.php#export    the original method
     */
    public function actionExport(): void
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                SKT.id               AS id,
                SKT.kode             AS kode,
                SKT.id_kelas         AS idKelas,
                SKT.subkelas_terapi  AS subkelasTerapi,
                SKT.kode_kelasterapi AS kodeKelasTerapi,
                SKT.userid_updt      AS useridUpdate,
                SKT.sysdate_updt     AS sysdateUpdate,
                USR.name             AS namaUserUbah,
                KT.kelas_terapi      AS kelasTerapi
            FROM db1.masterf_subkelasterapi AS SKT
            LEFT JOIN db1.user AS USR ON SKT.userid_updt = USR.id
            LEFT JOIN db1.masterf_kelasterapi AS KT ON SKT.id_kelas = KT.id
            ORDER BY SKT.id
        ";
        $daftarSubkelasTerapi = $connection->createCommand($sql)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost");

        $object->setActiveSheetIndex()
            ->setCellValue("A1", "id")
            ->setCellValue("B1", "kode")
            ->setCellValue("C1", "subkelas_terapi")
            ->setCellValue("D1", "id_kelas")
            ->setCellValue("E1", "kelas_terapi")
            ->setCellValue("F1", "kode_kelasterapi")
            ->setCellValue("G1", "userid_updt")
            ->setCellValue("H1", "sysdate_updt")
            ->setCellValue("I1", "name");

        $no = 2;

        foreach ($daftarSubkelasTerapi as $row) {
            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $row->id)
                ->setCellValue("B" . $no, $row->kode)
                ->setCellValue("C" . $no, $row->subkelasTerapi)
                ->setCellValue("D" . $no, $row->idKelas)
                ->setCellValue("E" . $no, $row->kelasTerapi)
                ->setCellValue("F" . $no, $row->kodeKelasTerapi)
                ->setCellValue("G" . $no, $row->useridUpdate)
                ->setCellValue("H" . $no, $row->sysdateUpdate)
                ->setCellValue("I" . $no, $row->namaUserUbah);
            $no++;
        }

        $object->getActiveSheet()->setTitle("SubKelasTerapi");
        $object->setActiveSheetIndex();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="subkelasterapi.xlsx"');
        header("Cache-Control: max-age = 0");

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
        if (!in_array($field, ["kode", "subkelas_terapi"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id              AS id,
                kode            AS kode,
                subkelas_terapi AS subkelasTerapi
            FROM db1.masterf_subkelasterapi
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
