<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 * @see - (none)
 */
class JenisAnggaranController extends BaseController
{
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
                id             AS id,
                kode           AS kode,
                jenis_anggaran AS jenisAnggaran
            FROM db1.masterf_jenisanggaran
            WHERE sts_aktif = 1
            ORDER BY id ASC
        ";
        $daftarJenisAnggaran = $connection->createCommand($sql)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.id                AS id,               -- in use
                B.kode              AS kode,
                B.subjenis_anggaran AS subjenisAnggaran, -- in use
                A.id_jenis          AS idJenis           -- in use
            FROM db1.relasif_anggaran AS A
            INNER JOIN db1.masterf_subjenisanggaran AS B ON A.id_subjenis = B.id
            WHERE A.sts_aktif = 1
            ORDER BY id ASC
        ";
        $daftarSubjenisAnggaran = $connection->createCommand($sql)->queryAll();

        $daftarJenisAnggaran2 = [];
        foreach ($daftarJenisAnggaran as $optgroup) {
            $daftarJenisAnggaran2[$optgroup->id] = [
                "value" => $optgroup->id,
                "label" => $optgroup->jenisAnggaran,
                "children" => []
            ];
        }

        foreach ($daftarSubjenisAnggaran as $option) {
            $daftarJenisAnggaran2[$option->idJenis]["children"][] = ["value" => $option->id, "label" => $option->subjenisAnggaran];
        }

        return json_encode($daftarJenisAnggaran2);
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
                id             AS id,
                kode           AS kode,
                jenis_anggaran AS jenisAnggaran
            FROM db1.masterf_jenisanggaran
            WHERE sts_aktif = 1
            ORDER BY id ASC
        ";
        $daftarJenisAnggaran = $connection->createCommand($sql)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.id                AS id,               -- in use
                B.kode              AS kode,
                B.subjenis_anggaran AS subjenisAnggaran, -- in use
                A.id_jenis          AS idJenis           -- in use
            FROM db1.relasif_anggaran AS A
            INNER JOIN db1.masterf_subjenisanggaran AS B ON A.id_subjenis = B.id
            WHERE A.sts_aktif = 1
            ORDER BY id ASC
        ";
        $daftarSubjenisAnggaran = $connection->createCommand($sql)->queryAll();

        $daftarJenisAnggaran2 = [];
        foreach ($daftarJenisAnggaran as $optgroup) {
            $daftarJenisAnggaran2[$optgroup->id] = [
                "value" => $optgroup->id,
                "label" => $optgroup->jenisAnggaran,
                "children" => []
            ];
        }

        foreach ($daftarSubjenisAnggaran as $option) {
            $daftarJenisAnggaran2[$option->idJenis]["children"][] = ["value" => $option->id, "label" => $option->subjenisAnggaran];
        }

        array_unshift($daftarJenisAnggaran2, ["value" => "", "label" => "Semua Jenis Anggaran"]);
        return json_encode($daftarJenisAnggaran2);
    }
}
