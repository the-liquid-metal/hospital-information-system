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
class KelompokBarangController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelompokbarang.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "kode" => $kode,
            "kelompokBarang" => $kelompokBarang,
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
                KBG.id              AS id,
                KBG.kode            AS kode,
                KBG.kelompok_barang AS kelompokBarang,
                USR.name            AS updatedBy,
                KBG.sysdate_updt    AS updatedTime
            FROM db1.masterf_kelompokbarang AS KBG
            LEFT JOIN db1.user AS USR ON KBG.userid_updt = USR.id
            WHERE
                (:kode = '' OR KBG.kode LIKE :kode)
                AND (:kelompokBarang = '' OR KBG.kelompok_barang LIKE :kelompokBarang)
            ORDER BY KBG.id
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":kode" => $kode ? "%$kode%" : "",
            ":kelompokBarang" => $kelompokBarang ? "%$kelompokBarang%" : "",
        ];
        $daftarKelompokBarang = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarKelompokBarang);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelompokbarang.php#add    the original method
     */
    public function actionSave(): void
    {
        ["kode" => $kode, "kelompokBarang" => $kelompokBarang, "action" => $action, "id" => $id] = Yii::$app->request->post();

        if ($kode) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.masterf_kelompokbarang
                WHERE id = :id
                LIMIT 1
            ";
            $params = [":id" => $kode];
            $jumlahKelompokBarang = $connection->createCommand($sql, $params)->queryScalar();
            if (!$jumlahKelompokBarang) throw new DataNotExistException($kode);
        }

        $dataKelompokBarang = [
            "kode" => $kode,
            "kelompok_barang" => $kelompokBarang,
            "userid_updt" => Yii::$app->userFatma->id,
        ];
        $daftarField = array_keys($dataKelompokBarang);

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $fm2->saveData("masterf_kelompokbarang", $daftarField, $dataKelompokBarang);

        } elseif ($action == "edit") {
            $where = ["id" => $id];
            $fm2->saveData("masterf_kelompokbarang", $daftarField, $dataKelompokBarang, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelompokbarang.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id              AS id,
                kode            AS kode,
                kelompok_barang AS kelompokBarang,
                kode_temp       AS kodeTemp,
                no_urut         AS noUrut,
                userid_updt     AS useridUpdate,
                sysdate_updt    AS sysdateUpdate
            FROM db1.masterf_kelompokbarang
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $id];
        $kelompokBarang = $connection->createCommand($sql, $params)->queryOne();
        if (!$kelompokBarang) throw new DataNotExistException($id);

        return json_encode($kelompokBarang);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @throws FieldNotExistException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelompokbarang.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        if (!in_array($field, ["id", "kode", "kelompok_barang", "kode_temp", "no_urut"])) {
            throw new FieldNotExistException($field);
        }

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_kelompokbarang
            WHERE $field = :value
        ";
        $params = [":value" => $val];
        $jumlahRowHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($jumlahRowHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelompokbarang.php#select    the original method
     */
    public function actionSelect(): string
    {
        ["type" => $tipe, "q" => $val, "max_rows" => $limit] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($tipe == "search") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*)
                FROM db1.masterf_kelompokbarang
            ";
            $jumlahKelompokBarang = $connection->createCommand($sql)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    id              AS id,
                    kode            AS kode,
                    kelompok_barang AS kelompokBarang,
                    kode_temp       AS kodeTemp,
                    no_urut         AS noUrut,
                    userid_updt     AS useridUpdate,
                    sysdate_updt    AS sysdateUpdate
                FROM db1.masterf_kelompokbarang
                ORDER BY kelompok_barang ASC
            ";
            $daftarKelompokBarang = $connection->createCommand($sql)->queryAll();

            return $this->renderPartial("search", [
                "fields" => [
                    "kode" => "Kode",
                    "kelompok_barang" => "Kelompok Barang"
                ],
                "iTotal" => $jumlahKelompokBarang,
                "data" => $daftarKelompokBarang,
            ]);

        } elseif ($val && $limit) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT masterf_kelompokbarang.*
                FROM db1.masterf_kelompokbarang
                WHERE
                    kode LIKE :val
                    OR kelompok_barang LIKE :val
                ORDER BY kelompok_barang ASC
                LIMIT $limit
            ";
            $params = [":val" => "%$val%"];
            $daftarKelompokBarang = $connection->createCommand($sql, $params)->queryAll();

            return json_encode($daftarKelompokBarang);

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*)
                FROM db1.masterf_kelompokbarang
            ";
            $jumlahKelompokBarang = $connection->createCommand($sql)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT masterf_kelompokbarang.*
                FROM db1.masterf_kelompokbarang
                ORDER BY kelompok_barang ASC
            ";
            $daftarKelompokBarang = $connection->createCommand($sql)->queryAll();

            return json_encode([$jumlahKelompokBarang, $daftarKelompokBarang]);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/kelompokbarang.php#export    the original method
     */
    public function actionExport(): void
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                KBG.id              AS id,
                KBG.kode            AS kode,
                KBG.kelompok_barang AS kelompokBarang,
                KBG.kode_temp       AS kodeTemp,
                KBG.no_urut         AS noUrut,
                KBG.userid_updt     AS useridUpdate,
                KBG.sysdate_updt    AS sysdateUpdate,
                USR.name            AS namaUser
            FROM db1.masterf_kelompokbarang AS KBG
            LEFT JOIN db1.user AS USR ON KBG.userid_updt = USR.id
            ORDER BY KBG.id
        ";
        $daftarKelompokBarang = $connection->createCommand($sql)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost")
            ->setCategory("Approve by ");

        $object->setActiveSheetIndex()
            ->setCellValue("A1", "id")
            ->setCellValue("B1", "kode")
            ->setCellValue("C1", "kelompok_barang")
            ->setCellValue("D1", "kode_temp")
            ->setCellValue("E1", "no_urut")
            ->setCellValue("F1", "userid_updt")
            ->setCellValue("G1", "sysdate_updt")
            ->setCellValue("H1", "name");

        $no = 2;

        foreach ($daftarKelompokBarang as $row) {
            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $row->id)
                ->setCellValue("B" . $no, $row->kode)
                ->setCellValue("C" . $no, $row->kelompokBarang)
                ->setCellValue("D" . $no, $row->kodeTemp)
                ->setCellValue("E" . $no, $row->noUrut)
                ->setCellValue("F" . $no, $row->useridUpdate)
                ->setCellValue("G" . $no, $row->sysdateUpdate)
                ->setCellValue("H" . $no, $row->namaUser);
            $no++;
        }

        $object->getActiveSheet()->setTitle("KelompokBarang");
        $object->setActiveSheetIndex();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="kelompokbarang.xlsx"');
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
        if (!in_array($field, ["kode", "kelompok_barang"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id              AS id,
                kode            AS kode,
                kelompok_barang AS kelompokBarang
            FROM db1.masterf_kelompokbarang
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

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
                id              AS value,
                kelompok_barang AS label
            FROM db1.masterf_kelompokbarang
            ORDER BY kelompok_barang
        ";
        $daftarKelompokBarang = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarKelompokBarang);
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
                id              AS value,
                kelompok_barang AS label
            FROM db1.masterf_kelompokbarang
            ORDER BY kelompok_barang
        ";
        $daftarKelompokBarang = $connection->createCommand($sql)->queryAll();
        array_unshift($daftarDepo, ["value" => "", "label" => "Semua Kelompok Barang"]);
        return json_encode($daftarKelompokBarang);
    }
}
