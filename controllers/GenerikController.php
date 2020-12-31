<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use PHPExcel;
use PHPExcel_Exception;
use PHPExcel_IOFactory;
use PHPExcel_Reader_Exception;
use PHPExcel_Writer_Exception;
use tlm\his\FatmaPharmacy\models\{
    DataNotExistException,
    FailToDeleteException,
    FailToUpdateException,
    FarmasiModel2,
    FieldNotExistException
};
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
class GenerikController extends BaseController
{

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/generik.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "kode" => $kode,
            "kelasTerapi" => $kelasTerapi,
            "subkelasTerapi" => $subkelasTerapi,
            "namaGenerik" => $namaGenerik,
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
                GEN.id              AS id,
                GEN.kode            AS kode,
                GEN.nama_generik    AS namaGenerik,
                GEN.restriksi       AS restriksi,
                KTP.kelas_terapi    AS kelasTerapi,
                SKT.subkelas_terapi AS subkelasTerapi,
                USR.name            AS updatedBy,
                GEN.sysdate_updt    AS updatedTime
            FROM db1.masterf_generik AS GEN
            LEFT JOIN db1.user AS USR ON GEN.userid_updt = USR.id
            LEFT JOIN rsupf.`masterf_subkelas-generik` AS SKG ON GEN.id = SKG.id_generik
            LEFT JOIN db1.masterf_subkelasterapi AS SKT ON SKT.id = SKG.id_subkelasterapi
            LEFT JOIN db1.masterf_kelasterapi AS KTP ON SKT.id_kelas = KTP.id
            WHERE
                (:kode = '' OR GEN.kode LIKE :kode)
                AND (:kelasTerapi = '' OR KTP.kelas_terapi LIKE :kelasTerapi)
                AND (:subkelasTerapi = '' OR SKT.subkelas_terapi LIKE :subkelasTerapi)
                AND (:namaGenerik = '' OR GEN.nama_generik LIKE :namaGenerik)
            ORDER BY GEN.id
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":kode" => $kode ? "%$kode%" : "",
            ":kelasTerapi" => $kelasTerapi ? "%$kelasTerapi%" : "",
            ":subkelasTerapi" => $subkelasTerapi ? "%$subkelasTerapi%" : "",
            ":namaGenerik" => $namaGenerik ? "%$namaGenerik%" : "",
        ];
        $daftarGenerik = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarGenerik);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws FailToUpdateException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/generik.php#add    the original method
     */
    public function actionSave(): void
    {
        [   "id" => $id,
            "kode" => $kode,
            "namaGenerik" => $namaGenerik,
            "idSubkelasRerapi" => $daftarIdSubkelasTerapi,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $fm2 = new FarmasiModel2;

        $dataGenerik = [
            "kode" => $kode,
            "nama_generik" => $namaGenerik,
            "userid_updt" => $idUser,
        ];
        $daftarField = array_keys($dataGenerik);

        if ($id) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.masterf_generik
                WHERE id = :id
                LIMIT 1
            ";
            $params = [":id" => $id];
            $cekGenerik = $connection->createCommand($sql, $params)->queryScalar();
            if (!$cekGenerik) throw new DataNotExistException($id);

            $where = ["id" => $id];
            $berhasilUbah = $fm2->saveData("masterf_generik", $daftarField, $dataGenerik, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Generik", "id", $id);

        } else {
            $id = $fm2->saveInsertid("masterf_generik", $daftarField, $dataGenerik);
        }

        $dataSubkelasGenerik = [];
        foreach ($daftarIdSubkelasTerapi as $i => $idSubkelasTerapi) {
            if (!$idSubkelasTerapi) continue;
            $dataSubkelasGenerik[$i] = [
                "id_subkelasterapi" => $idSubkelasTerapi,
                "id_generik" => $id,
                "userid_updt" => $idUser,
            ];
        }

        if ($id) {
            $iwhere = ["id_generik" => $id];
            $fm2->saveBatch("masterf_subkelas-generik", $dataSubkelasGenerik, $iwhere);

        } else {
            $fm2->saveBatch("masterf_subkelas-generik", $dataSubkelasGenerik);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws FailToUpdateException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/generik.php#edit    the original method
     */
    public function actionEdit(): void
    {
        $id = $_POST["id"];
        assert($id, new MissingPostParamException("id"));

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.id               AS id,
                B.kode             AS kode,
                B.id_kelas         AS idKelas,
                B.subkelas_terapi  AS subkelasTerapi,
                B.kode_kelasterapi AS kodeKelasTerapi,
                B.userid_updt      AS useridUpdate,
                B.sysdate_updt     AS sysdateUpdate,
                C.kelas_terapi     AS kelasTerapi
            FROM rsupf.`masterf_subkelas-generik` A
            LEFT JOIN db1.masterf_subkelasterapi AS B ON A.id_subkelasterapi = B.id
            LEFT JOIN db1.masterf_kelasterapi AS C ON B.id_kelas = C.id
            WHERE id_generik = :idGenerik
        ";
        $params = [":idGenerik" => $id];
        $daftarSubkelasGenerik = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarSubkelasGenerik) throw new DataNotExistException($id);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                kode         AS kode,
                nama_generik AS nama,
                restriksi    AS restriksi,
                userid_updt  AS updatedBy,
                sysdate_updt AS updatedTime
            FROM db1.masterf_generik
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $id];
        $generik = $connection->createCommand($sql, $params)->queryOne();
        if (!$generik) throw new DataNotExistException($id);

        $this->setDataView("data", $generik);
        $this->setDataView("subkelas", $daftarSubkelasGenerik);
        $this->setDataView("action", "edit");

        $this->actionSave();
    }

    /**
     * TODO: php: refactor: flip delete "generik" and "subgenerik". fix delete "subgenerik" algorithm
     * @author Hendra Gunawan
     * @throws FailToDeleteException
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/generik.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();
        if (!in_array($field, ["id", "kode", "nama_generik", "restriksi"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_generik
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $berhasilHapus = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilHapus) throw new FailToDeleteException($val);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM rsupf.`masterf_subkelas-generik`
            WHERE id_generik = :idGenerik
        ";
        $params = [":idGenerik" => $val];
        $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/generik.php#select    the original method
     */
    public function actionSelect(): string
    {
        ["q" => $val] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                id           AS id,
                kode         AS kode,
                nama_generik AS nama
            FROM db1.masterf_generik
            WHERE
                kode LIKE :val
                OR nama_generik LIKE :val
            ORDER BY nama_generik ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarGenerik = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarGenerik);
    }

    /**
     * @author Hendra Gunawan
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/generik.php#export    the original method
     */
    public function actionExport(): void
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                GEN.id                AS id,
                GEN.kode              AS kode,
                GEN.nama_generik      AS namaGenerik,
                GEN.userid_updt       AS useridUpdate,
                GEN.sysdate_updt      AS sysdateUpdate,
                USR.name              AS namaUserUbah,
                SKG.id_subkelasterapi AS idSubkelasTerapi,
                SKT.id_kelas          AS idKelas,
                KT.kelas_terapi       AS kelasTerapi,
                SKT.subkelas_terapi   AS subkelasTerapi
            FROM db1.masterf_generik AS GEN
            LEFT JOIN db1.user AS USR ON GEN.userid_updt = USR.id
            LEFT JOIN rsupf.`masterf_subkelas-generik` AS SKG ON GEN.id = SKG.id_generik
            LEFT JOIN db1.masterf_subkelasterapi AS SKT ON SKT.id = SKG.id_subkelasterapi
            LEFT JOIN db1.masterf_kelasterapi AS KT ON SKT.id_kelas = KT.id
            ORDER BY GEN.id
        ";
        $daftarGenerik = $connection->createCommand($sql)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost");

        $object->setActiveSheetIndex()
            ->setCellValue("A1", "id")
            ->setCellValue("B1", "kode")
            ->setCellValue("C1", "nama_generik")
            ->setCellValue("D1", "id_subkelasterapi")
            ->setCellValue("E1", "subkelas_terapi")
            ->setCellValue("F1", "id_kelas")
            ->setCellValue("G1", "kelas_terapi")
            ->setCellValue("H1", "userid_updt")
            ->setCellValue("I1", "sysdate_updt")
            ->setCellValue("J1", "name");

        $no = 2;
        foreach ($daftarGenerik as $row) {
            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $row->id)
                ->setCellValue("B" . $no, $row->kode)
                ->setCellValue("C" . $no, $row->namaGenerik)
                ->setCellValue("D" . $no, $row->idSubkelasTerapi)
                ->setCellValue("E" . $no, $row->subkelasTerapi)
                ->setCellValue("F" . $no, $row->idKelas)
                ->setCellValue("G" . $no, $row->kelasTerapi)
                ->setCellValue("H" . $no, $row->useridUpdate)
                ->setCellValue("I" . $no, $row->sysdateUpdate)
                ->setCellValue("J" . $no, $row->namaUserUbah);
            $no++;
        }

        $object->getActiveSheet()->setTitle("Generik");
        $object->setActiveSheetIndex();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="generik.xlsx"');
        header("Cache-Control: max-age=0");

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
        if (!in_array($field, ["kode", "nama_generik"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                kode         AS kode,
                nama_generik AS namaGenerik
            FROM db1.masterf_generik
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
