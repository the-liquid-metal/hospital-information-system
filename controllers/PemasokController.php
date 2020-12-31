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
class PemasokController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pbf.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "kode" => $kode,
            "namaPemasok" => $namaPemasok,
            "npwp" => $npwp,
            "alamat" => $alamat,
            "telefon" => $telefon,
            "fax" => $fax,
            "email" => $email,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                PBF.id            AS id,
                PBF.kode          AS kode,
                PBF.nama_pbf      AS namaPemasok,
                PBF.npwp          AS npwp,
                PBF.alamat        AS alamat,
                PBF.telp          AS telefon,
                PBF.fax           AS fax,
                PBF.email         AS email,
                PBF.sysdate_updt  AS updatedTime,
                USR.name          AS updatedBy 
            FROM db1.masterf_pbf AS PBF
            LEFT JOIN db1.user AS USR ON userid_updt = USR.id
            WHERE
                (:kode = '' OR PBF.kode LIKE :kode)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:npwp = '' OR PBF.npwp LIKE :npwp)
                AND (:alamat = '' OR PBF.alamat LIKE :alamat)
                AND (:telefon = '' OR PBF.telp LIKE :telefon)
                AND (:fax = '' OR PBF.fax LIKE :fax)
                AND (:email = '' OR PBF.email LIKE :email)
            ORDER BY PBF.nama_pbf
        ";
        $params = [
            ":kode" => $kode ? "%$kode%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":npwp" => $npwp ? "%$npwp%" : "",
            ":alamat" => $alamat ? "%$alamat%" : "",
            ":telefon" => $telefon ? "%$telefon%" : "",
            ":fax" => $fax ? "%$fax%" : "",
            ":email" => $email ? "%$email%" : "",
        ];
        $daftarPemasok = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarPemasok);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pbf.php#add    the original method
     */
    public function actionSave(): void
    {
        [   "kode" => $kode,
            "namaPemasok" => $namaPemasok,
            "kepalaCabang" => $kepalaCabang,
            "npwp" => $npwp,
            "alamat" => $alamat,
            "kota" => $kota,
            "kodePos" => $kodePos,
            "telefonKantor" => $telefonKantor,
            "faxKantor" => $faxKantor,
            "emailKantor" => $emailKantor,
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
                FROM db1.masterf_pbf
                WHERE id = :id
            ";
            $params = [":id" => $kode];
            $jumlahPbf = $connection->createCommand($sql, $params)->queryScalar();
            if (!$jumlahPbf) throw new DataNotExistException($kode);
        }

        $dataPemasok = [
            "kode" => $kode,
            "nama_pbf" => $namaPemasok,
            "kepala_cabang" => $kepalaCabang,
            "npwp" => $npwp,
            "alamat" => $alamat,
            "kota" => $kota,
            "kodepos" => $kodePos,
            "telp" => $telefonKantor,
            "fax" => $faxKantor,
            "email" => $emailKantor,
            "cp_name" => $namaKontak,
            "cp_telp" => $telefonKontak,
            "userid_updt" => Yii::$app->userFatma->id,
        ];
        $daftarField = array_keys($dataPemasok);

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $fm2->saveData("masterf_pbf", $daftarField, $dataPemasok);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_pbf", $daftarField, $dataPemasok, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pbf.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id            AS id,
                kode          AS kode,
                nama_pbf      AS namaPemasok,
                npwp          AS npwp,
                alamat        AS alamat,
                kota          AS kota,
                kodepos       AS kodePos,
                telp          AS telefon,
                fax           AS fax,
                email         AS email,
                kepala_cabang AS kepalaCabang,
                cp_name       AS namaKontak,
                cp_telp       AS telefonKontak,
                userid_updt   AS useridUpdate,
                sysdate_updt  AS sysdateUpdate
            FROM db1.masterf_pbf
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $kode];
        $pemasok = $connection->createCommand($sql, $params)->queryOne();
        if (!$pemasok) throw new DataNotExistException($kode);

        return json_encode($pemasok);
    }

    /**
     * @author Hendra Gunawan
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pbf.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        $daftarField = [
            "id",
            "kode",
            "nama_pbf",
            "npwp",
            "alamat",
            "kota",
            "kodepos",
            "telp",
            "fax",
            "email",
            "kepala_cabang",
            "cp_name",
            "cp_telp",
        ];

        if (!in_array($field, $daftarField)) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_pbf
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $jumlahRowHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($jumlahRowHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pbf.php#select    the original method
     */
    public function actionSelect(): string
    {
        ["q" => $val] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                id       AS id,
                kode     AS kode,
                nama_pbf AS nama
            FROM db1.masterf_pbf
            WHERE
                kode LIKE :val
                OR nama_pbf LIKE :val
            ORDER BY nama_pbf ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarPemasok = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPemasok);
    }

    /**
     * @author Hendra Gunawan
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pbf.php#export    the original method
     */
    public function actionExport(): void
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                PBF.id            AS id,
                PBF.kode          AS kode,
                PBF.nama_pbf      AS namaPemasok,
                PBF.npwp          AS npwp,
                PBF.alamat        AS alamat,
                PBF.kota          AS kota,
                PBF.kodepos       AS kodePos,
                PBF.telp          AS telefon,
                PBF.fax           AS fax,
                PBF.email         AS email,
                PBF.kepala_cabang AS kepalaCabang,
                PBF.cp_name       AS namaKontak,
                PBF.cp_telp       AS telefonKontak,
                PBF.userid_updt   AS useridUpdate,
                PBF.sysdate_updt  AS sysdateUpdate,
                USR.name          AS namaUserUbah
            FROM db1.masterf_pbf AS PBF
            LEFT JOIN db1.user AS USR ON userid_updt = USR.id
            ORDER BY PBF.id
        ";
        $daftarPemasok = $connection->createCommand($sql)->queryAll();


        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost");

        $object->setActiveSheetIndex()
            ->setCellValue("A1", "id")
            ->setCellValue("B1", "kode")
            ->setCellValue("C1", "nama_pbf")
            ->setCellValue("D1", "npwp")
            ->setCellValue("E1", "alamat")
            ->setCellValue("F1", "kota")
            ->setCellValue("G1", "kodepos")
            ->setCellValue("H1", "telp")
            ->setCellValue("I1", "fax")
            ->setCellValue("J1", "email")
            ->setCellValue("K1", "kepala_cabang")
            ->setCellValue("L1", "cp_name")
            ->setCellValue("M1", "cp_telp")
            ->setCellValue("N1", "userid_updt")
            ->setCellValue("O1", "sysdate_updt")
            ->setCellValue("P1", "name");
        $no = 2;

        foreach ($daftarPemasok as $row) {
            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $row->id)
                ->setCellValue("B" . $no, $row->kode)
                ->setCellValue("C" . $no, $row->namaPemasok)
                ->setCellValue("D" . $no, $row->npwp)
                ->setCellValue("E" . $no, $row->alamat)
                ->setCellValue("F" . $no, $row->kota)
                ->setCellValue("G" . $no, $row->kodePos)
                ->setCellValue("H" . $no, $row->telefon)
                ->setCellValue("I" . $no, $row->fax)
                ->setCellValue("J" . $no, $row->email)
                ->setCellValue("K" . $no, $row->kepalaCabang)
                ->setCellValue("L" . $no, $row->namaKontak)
                ->setCellValue("M" . $no, $row->telefonKontak)
                ->setCellValue("N" . $no, $row->useridUpdate)
                ->setCellValue("O" . $no, $row->sysdateUpdate)
                ->setCellValue("P" . $no, $row->namaUserUbah);
            $no++;
        }

        $object->getActiveSheet()->setTitle("PBF");
        $object->setActiveSheetIndex();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="pbf.xlsx"');
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($object, "Excel2007");
        $objWriter->save("php://output");
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pbf.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-133e2ffef
     */
    public function actionSearchJson(): string
    {
        $val = Yii::$app->request->post("q");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id       AS id,
                A.nama_pbf AS nama,
                A.kode     AS kode
            FROM db1.masterf_pbf AS A
            WHERE
                A.nama_pbf LIKE :val
                OR A.kode LIKE :val
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarPemasok = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPemasok);
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
        if (!in_array($field, ["kode", "nama_pbf"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id       AS id,
                kode     AS kode,
                nama_pbf AS namaPemasok
            FROM db1.masterf_pbf
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
