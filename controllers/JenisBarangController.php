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
class JenisBarangController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/jenisbarang.php#index    the original method
     */
    public function actionTableData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                JOB.id           AS id,
                JOB.kode         AS kode,
                JOB.jenis_obat   AS jenisObat,
                JOB.sysdate_updt AS sysdateUpdate,
                USR.name         AS updatedBy,
                JOBT.jenis_obat  AS jenisObat1
            FROM db1.masterf_jenisobat AS JOB
            LEFT JOIN db1.user AS USR ON JOB.userid_updt = USR.id
            LEFT JOIN db1.masterf_jenisobat AS JOBT ON JOBT.kode = JOB.kode_group
            ORDER BY JOB.jenis_obat
        ";
        $daftarJenisObat = $connection->createCommand($sql)->queryAll();

        return json_encode($daftarJenisObat);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/jenisbarang.php#add    the original method
     */
    public function actionSave(): void
    {
        [   "id" => $id,
            "kode" => $kode,
            "kodeGroup" => $kodeGroup,
            "jenisObat" => $jenisObat,
            "action" => $action,
        ] = Yii::$app->request->post();

        // edit fragment
        if ($kode) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.masterf_jenisobat
                WHERE id = :id
                LIMIT 1
            ";
            $params = [":id" => $kode];
            $cekJenisObat = $connection->createCommand($sql, $params)->queryScalar();
            if (!$cekJenisObat) throw new DataNotExistException($kode);
        }

        $dataJenisBarang = [
            "kode" => $kode,
            "kode_group" => $kodeGroup,
            "jenis_obat" => $jenisObat,
            "userid_updt" => Yii::$app->userFatma->id,
        ];
        $daftarField = array_keys($dataJenisBarang);

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $fm2->saveData("masterf_jenisobat", $daftarField, $dataJenisBarang);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_jenisobat", $daftarField, $dataJenisBarang, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/jenisbarang.php#edit    the original method
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
                kode_group   AS kodeGroup,
                jenis_obat   AS jenisObat,
                kode_farmasi AS kodeFarmasi,
                nama_farmasi AS namaFarmasi,
                nama_ulp     AS namaUlp,
                kode_temp    AS kodeTemp,
                sts_hapus    AS statusHapus,
                no_urut      AS noUrut,
                userid_updt  AS useridUpdate,
                sysdate_updt AS sysdateUpdate
            FROM db1.masterf_jenisobat
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $kode];
        $jenisObat = $connection->createCommand($sql, $params)->queryOne();
        if (!$jenisObat) throw new DataNotExistException($kode);

        return json_encode($jenisObat);
    }

    /**
     * @author Hendra Gunawan
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/jenisbarang.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        $daftarField = [
            "id",
            "kode",
            "kode_group",
            "jenis_obat",
            "kode_farmasi",
            "nama_farmasi",
            "nama_ulp",
            "kode_temp",
            "sts_hapus",
            "no_urut",
        ];

        if (!in_array($field, $daftarField)) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_jenisobat
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/jenisbarang.php#select    the original method
     */
    public function actionSelect(): string
    {
        ["q" => $val] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                id         AS id,
                kode       AS kode,
                jenis_obat AS nama
            FROM db1.masterf_jenisobat
            WHERE
                kode LIKE :val
                OR jenis_obat LIKE :val
            ORDER BY jenis_obat ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarJenisObat = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarJenisObat);
    }

    /**
     * @author Hendra Gunawan
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/jenisbarang.php#export    the original method
     */
    public function actionExport(): void
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                JOB.id           AS id,
                JOB.kode         AS kode,
                JOB.kode_group   AS kodeGroup,
                JOB.jenis_obat   AS jenisObat,
                JOB.kode_farmasi AS kodeFarmasi,
                JOB.nama_farmasi AS namaFarmasi,
                JOB.nama_ulp     AS namaUlp,
                JOB.kode_temp    AS kodeTemp,
                JOB.sts_hapus    AS statusHapus,
                JOB.no_urut      AS noUrut,
                JOB.userid_updt  AS useridUpdate,
                JOB.sysdate_updt AS sysdateUpdate,
                USR.name         AS namaUserUbah
            FROM db1.masterf_jenisobat AS JOB
            LEFT JOIN db1.user AS USR ON JOB.userid_updt = USR.id
            ORDER BY JOB.id
        ";
        $daftarJenisObat = $connection->createCommand($sql)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost")
            ->setCategory("Approve by ");

        $object->setActiveSheetIndex()
            ->setCellValue("A1", "id")
            ->setCellValue("B1", "kode")
            ->setCellValue("C1", "kode_group")
            ->setCellValue("D1", "jenis_obat")
            ->setCellValue("E1", "kode_farmasi")
            ->setCellValue("F1", "nama_farmasi")
            ->setCellValue("G1", "nama_ulp")
            ->setCellValue("H1", "kode_temp")
            ->setCellValue("I1", "sts_hapus")
            ->setCellValue("J1", "no_urut")
            ->setCellValue("K1", "userid_updt")
            ->setCellValue("L1", "sysdate_updt")
            ->setCellValue("M1", "name");

        $no = 2;
        foreach ($daftarJenisObat as $row) {
            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $row->id)
                ->setCellValue("B" . $no, $row->kode)
                ->setCellValue("C" . $no, $row->kodeGroup)
                ->setCellValue("D" . $no, $row->jenisObat)
                ->setCellValue("E" . $no, $row->kodeFarmasi)
                ->setCellValue("F" . $no, $row->namaFarmasi)
                ->setCellValue("G" . $no, $row->namaUlp)
                ->setCellValue("H" . $no, $row->kodeTemp)
                ->setCellValue("I" . $no, $row->statusHapus)
                ->setCellValue("J" . $no, $row->noUrut)
                ->setCellValue("K" . $no, $row->useridUpdate)
                ->setCellValue("L" . $no, $row->sysdateUpdate)
                ->setCellValue("M" . $no, $row->namaUserUbah);
            $no++;
        }

        $object->getActiveSheet()->setTitle("JenisObat");
        $object->setActiveSheetIndex();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="jenisobat.xlsx"');
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
        if (!in_array($field, ["kode", "jenis_obat"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id         AS id,
                kode       AS kode,
                jenis_obat AS jenisObat
            FROM db1.masterf_jenisobat
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
