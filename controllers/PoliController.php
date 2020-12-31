<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

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
class PoliController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/poli.php#dropdown    the original method
     */
    public function actionDropdown(): string
    {
        $instalasi = Yii::$app->request->post("instalasi");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_poli   AS idPoli,
                nama_poli AS namaPoli
            FROM db1.master_poli
            WHERE id_instalasi = :idInstalasi
        ";
        $params = [":idInstalasi" => $instalasi];
        $daftarPoli = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarPoli);
    }
}
