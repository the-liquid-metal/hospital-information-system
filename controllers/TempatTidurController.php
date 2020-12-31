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
class TempatTidurController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/tempattidur.php#view    the original method
     */
    public function actionView(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id       AS id,
                id_kamar AS idKamar,
                nomor    AS nomor,
                nama     AS nama
            FROM simrs_bayangan.tempat_tidur
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $_POST["id"]];
        $tempatTidur = $connection->createCommand($sql, $params)->queryOne();
        return json_encode($tempatTidur);
    }
}
