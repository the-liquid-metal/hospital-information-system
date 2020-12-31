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
class PemetaanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/pemetaan.php#typeahead    the original method
     */
    public function actionTypeahead(): string
    {
        $max = Yii::$app->request->post("max_rows") ?? 20;
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kd_icd9cm AS kodeIcd9cm,
                kd_icd9cm AS id,
                nm_icd9cm AS namaIcd9cm
            FROM db1.icd9
            WHERE
                nm_icd9cm LIKE :q
                OR kd_icd9cm LIKE :q
            LIMIT $max
        ";
        $params = [":q" => "%".Yii::$app->request->post("q")."%"];
        $daftarIcd9 = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarIcd9);
    }
}
