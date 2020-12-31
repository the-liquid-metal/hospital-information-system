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
class PabrikController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pabrik.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "kode" => $kode,
            "namaPabrik" => $namaPabrik,
            "npwp" => $npwp,
            "alamat" => $alamat,
            "telefon" => $telefon,
            "fax" => $fax,
            "email" => $email,
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
                PBK.id           AS id,
                PBK.kode         AS kode,
                PBK.nama_pabrik  AS namaPabrik,
                PBK.npwp         AS npwp,
                PBK.alamat       AS alamat,
                PBK.telp         AS telefon,
                PBK.fax          AS fax,
                PBK.email        AS email,
                USR.name         AS updatedBy,
                PBK.sysdate_updt AS updatedTime
            FROM db1.masterf_pabrik AS PBK
            LEFT JOIN db1.user AS USR ON userid_updt = USR.id
            WHERE
                (:kode = '' OR PBK.kode LIKE :kode)
                AND (:namaPabrik = '' OR PBK.nama_pabrik LIKE :namaPabrik)
                AND (:npwp = '' OR PBK.npwp LIKE :npwp)
                AND (:alamat = '' OR PBK.alamat LIKE :alamat)
                AND (:telefon = '' OR PBK.telp LIKE :telefon)
                AND (:fax = '' OR PBK.fax LIKE :fax)
                AND (:email = '' OR PBK.email LIKE :email)
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":kode" => $kode ? "%$kode%" : "",
            ":namaPabrik" => $namaPabrik ? "%$namaPabrik%" : "",
            ":npwp" => $npwp ? "%$npwp%" : "",
            ":alamat" => $alamat ? "%$alamat%" : "",
            ":telefon" => $telefon ? "%$telefon%" : "",
            ":fax" => $fax ? "%$fax%" : "",
            ":email" => $email ? "%$email%" : "",
        ];
        $daftarPabrik = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarPabrik);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pabrik.php#add    the original method
     */
    public function actionSave(): void
    {
        [   "kode" => $kode,
            "namaPabrik" => $namaPabrik,
            "npwp" => $npwp,
            "alamat" => $alamat,
            "kota" => $kota,
            "kodepos" => $kodePos,
            "telefon" => $telefon,
            "fax" => $fax,
            "email" => $email,
            "namaKontak" => $namaKontak,
            "telefonKontak" => $telefonKontak,
            "action" => $action,
            "id" => $id,
        ] = Yii::$app->request->post();

        if ($kode) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*)
                FROM db1.masterf_pabrik
                WHERE id = :id
            ";
            $params = [":id" => $kode];
            $jumlahPabrik = $connection->createCommand($sql, $params)->queryScalar();
            if (!$jumlahPabrik) throw new DataNotExistException($kode);
        }

        $dataPabrik = [
            "kode" => $kode,
            "nama_pabrik" => $namaPabrik,
            "npwp" => $npwp,
            "alamat" => $alamat,
            "kota" => $kota,
            "kodepos" => $kodePos,
            "telp" => $telefon,
            "fax" => $fax,
            "email" => $email,
            "cp_name" => $namaKontak,
            "cp_telp" => $telefonKontak,
            "userid_updt" => Yii::$app->userFatma->id,
        ];
        $daftarField = array_keys($dataPabrik);

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $fm2->saveData("masterf_pabrik", $daftarField, $dataPabrik);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_pabrik", $daftarField, $dataPabrik, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pabrik.php#edit    the original method
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
                nama_pabrik  AS namaPabrik,
                npwp         AS npwp,
                alamat       AS alamat,
                kota         AS kota,
                kodepos      AS kodePos,
                telp         AS telefon,
                fax          AS fax,
                email        AS email,
                cp_name      AS namaKontak,
                cp_telp      AS telefonKontak,
                sts_aktif    AS statusAktif,
                userid_updt  AS useridUpdate,
                sysdate_updt AS sysdateUpdate
            FROM db1.masterf_pabrik
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $kode];
        $pabrik = $connection->createCommand($sql, $params)->queryOne();
        if (!$pabrik) throw new DataNotExistException("Data Not Exist Exception");

        return json_encode($pabrik);
    }

    /**
     * @author Hendra Gunawan
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pabrik.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        $daftarField = [
            "id",
            "kode",
            "nama_pabrik",
            "npwp",
            "alamat",
            "kota",
            "kodepos",
            "telp",
            "fax",
            "email",
            "cp_name",
            "cp_telp",
            "sts_aktif",
        ];

        if (!in_array($field, $daftarField)) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_pabrik
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $jumlahRowHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($jumlahRowHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pabrik.php#select    the original method
     */
    public function actionSelect(): string
    {
        ["q" => $val] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id          AS id,
                kode        AS kode,
                nama_pabrik AS nama
            FROM db1.masterf_pabrik
            WHERE
                kode LIKE :val
                OR nama_pabrik LIKE :val
            ORDER BY nama_pabrik ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarPabrik = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPabrik);
    }

    /**
     * @author Hendra Gunawan
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pabrik.php#export    the original method
     */
    public function actionExport(): void
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                PBK.id           AS id,
                PBK.kode         AS kode,
                PBK.nama_pabrik  AS namaPabrik,
                PBK.npwp         AS npwp,
                PBK.alamat       AS alamat,
                PBK.kota         AS kota,
                PBK.kodepos      AS kodePos,
                PBK.telp         AS telefon,
                PBK.fax          AS fax,
                PBK.email        AS email,
                PBK.cp_name      AS namaKontak,
                PBK.cp_telp      AS telefonKontak,
                PBK.userid_updt  AS useridUpdate,
                PBK.sysdate_updt AS sysdateUpdate,
                USR.name         AS namaUserUbah
            FROM db1.masterf_pabrik AS PBK
            LEFT JOIN db1.user AS USR ON userid_updt = USR.id
        ";
        $daftarPabrik = $connection->createCommand($sql)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost");

        $object->setActiveSheetIndex()
            ->setCellValue("A1", "id")
            ->setCellValue("B1", "kode")
            ->setCellValue("C1", "nama_pabrik")
            ->setCellValue("D1", "npwp")
            ->setCellValue("E1", "alamat")
            ->setCellValue("F1", "kota")
            ->setCellValue("G1", "kodepos")
            ->setCellValue("H1", "telp")
            ->setCellValue("I1", "fax")
            ->setCellValue("J1", "email")
            ->setCellValue("K1", "cp_name")
            ->setCellValue("L1", "cp_telp")
            ->setCellValue("M1", "userid_updt")
            ->setCellValue("N1", "sysdate_updt")
            ->setCellValue("O1", "name");
        $no = 2;

        foreach ($daftarPabrik as $row) {
            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $row->id)
                ->setCellValue("B" . $no, $row->kode)
                ->setCellValue("C" . $no, $row->namaPabrik)
                ->setCellValue("D" . $no, $row->npwp)
                ->setCellValue("E" . $no, $row->alamat)
                ->setCellValue("F" . $no, $row->kota)
                ->setCellValue("G" . $no, $row->kodePos)
                ->setCellValue("H" . $no, $row->telefon)
                ->setCellValue("I" . $no, $row->fax)
                ->setCellValue("J" . $no, $row->email)
                ->setCellValue("K" . $no, $row->namaKontak)
                ->setCellValue("L" . $no, $row->telefonKontak)
                ->setCellValue("M" . $no, $row->useridUpdate)
                ->setCellValue("N" . $no, $row->sysdateUpdate)
                ->setCellValue("O" . $no, $row->namaUserUbah);
            $no++;
        }

        $object->getActiveSheet()->setTitle("Pabrik");
        $object->setActiveSheetIndex();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="pabrik.xlsx"');
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
        if (!in_array($field, ["kode", "nama_pabrik"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id          AS id,
                kode        AS kode,
                nama_pabrik AS namaPabrik
            FROM db1.masterf_pabrik
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
