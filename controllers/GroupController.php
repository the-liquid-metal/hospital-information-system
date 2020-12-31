<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\GroupUiController as Pair;
use tlm\his\FatmaPharmacy\models\GroupModel;
use Yii;
use yii\db\Exception;
use yii\web\Response;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class GroupController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/group.php#index    the original method
     */
    public function actionTableData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                name         AS nama,
                description  AS deskripsi,
                id_instalasi AS idInstalasi,
                id_poli      AS idPoli
            FROM rsupf.`group`
        ";
        $daftarGroup = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarGroup);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/group.php#index    the original method
     */
    public function actionIndexData(): string
    {
        // $daftarField = [
        //     "name" => "Nama Group",
        //     "description" => "Keterangan"
        // ];
        // $action = '
        //     <a class="btn btn-mini" href="' . $baseUrl.'/'.$this->name . '/edit/%1$s">
        //         <i class="icon-pencil"></i> Edit
        //     </a>
        //     <a class="btn btn-mini btn-danger lnk-delete" data-toggle="modal" href="#delete-confirm" data-url="' . $baseUrl.'/'.$this->name . '/delete/%1$s">
        //         <i class="icon-remove icon-white"></i> Delete
        //     </a>';

        [   "nama" => $nama,
            "deskripsi" => $deskripsi,
            "idInstalasi" => $idInstalasi,
            "idPoli" => $idPoli,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                name         AS nama,
                description  AS deskripsi,
                id_instalasi AS idInstalasi,
                id_poli      AS idPoli
            FROM rsupf.`group`
            WHERE
                (:nama = '' OR name LIKE :nama)
                AND (:deskripsi = '' OR description LIKE :deskripsi)
                AND (:idInstalasi = '' OR id_instalasi = :idInstalasi)
                AND (:idPoli = '' OR id_poli = :idPoli)
        ";
        $params = [
            ":nama" => $nama ? "%$nama%" : "",
            ":deskripsi" => $deskripsi ? "%$deskripsi%" : "",
            ":idInstalasi" => $idInstalasi ?? "",
            ":idPoli" => $idPoli ?? "",
        ];
        $daftarGroup = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarGroup);
    }

    /**
     * @author Hendra Gunawan
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/group.php#add    the original method
     */
    public function actionAdd(): Response
    {
        $formFields = [
            "name" => [
                "title" => "Nama Group",
                "rule" => "required"
            ],
            "description" => [
                "title" => "Keterangan",
                "type" => "textarea"
            ]
        ];
        $isValidate = true;

        $post = Yii::$app->request->post();
        if ($post["id_instalasi"] == 9999) {
            unset($post["id_instalasi"]);
        }
        if ($post["id_poli"] == 9999) {
            unset($post["id_poli"]);
        }

        $validator = new \CI_Form_validation;
        foreach ($formFields as $key => $field) {
            if (!$field["rule"]) continue;
            $title = $field["title"] ?? $key;
            $validator->set_rules($key, $title, $field["rule"]);
        }
        $validator->set_error_delimiters('<p class="help-inline">');

        $gm = new GroupModel;
        if (!$formFields) {
            $isValidate = false;
        }

        if (!$isValidate || $validator->run()) {
            if ($gm->save($post)) {
                $post["id"] = $this->db->insert_id();
                $gm->saveModule($post);
                return new Response;
            } else {
                throw new \Exception("Gagal menambah data");
            }
        } else {
            // TODO: php: uncategorized: modify this to proper Response
            return new Response;
        }
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/group.php#edit    the original method
     */
    public function actionEdit(): string|Response
    {
        $formFields = [
            "name" => [
                "title" => "Nama Group",
                "rule" => "required"
            ],
            "description" => [
                "title" => "Keterangan",
                "type" => "textarea"
            ]
        ];
        $isValidate = true;

        $post = Yii::$app->request->post();
        if ($post["id_instalasi"] == 9999) {
            unset($post["id_instalasi"]);
        }
        if ($post["id_poli"] == 9999) {
            unset($post["id_poli"]);
        }

        $validator = new \CI_Form_validation;
        foreach ($formFields as $key => $field) {
            if (!$field["rule"]) continue;
            $title = $field["title"] ?? $key;
            $validator->set_rules($key, $title, $field["rule"]);
        }
        $validator->set_error_delimiters('<p class="help-inline">');

        $group = new GroupModel;
        if (!$formFields) {
            $isValidate = false;
        }

        if (!$isValidate || $validator->run()) {
            if ($group->save($post)) {
                $group->saveModule($post);
                // sukses
            } else {
                // gagal
            }
            return $this->redirect(Yii::$app->actionToUrl([Pair::class, "actionTable"]));

        } else {
            // TODO: php: uncategorized: modify this to proper Response
            return new Response;
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/group.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $id = Yii::$app->request->post("id");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                name         AS nama,
                description  AS deskripsi,
                id_instalasi AS idInstalasi,
                id_poli      AS idPoli
            FROM rsupf.`group`
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $id];
        $group = $connection->createCommand($sql, $params)->queryOne();

        return json_encode([
            "group" => $group,
            "moduleName" => (new GroupModel)->findModule($id),
        ]);
    }
}
