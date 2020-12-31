<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\MonitoringDb\{Sample, Table};
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
class MonitoringDbController extends BaseController
{

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/monitoringdb.php#index    the original method
     */
    public function actionTable(): string
    {
        $view = new Table(
            registerId: Yii::$app->actionToId(__METHOD__),
            module:     $module, // TODO: php: uncategorized: deleted (?) while refactor
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/monitoringdb.php#getajax    the original method
     */
    public function actionGetAjax(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT *
            FROM inf_schema.processlist
            WHERE
                Command = 'Query'
                AND info <> 'SHOW FULL PROCESSLIST'
            ORDER BY time DESC
        ";
        $daftarProcessList = $connection->createCommand($sql)->queryAll();

        $view = new Sample(
            registerId:    Yii::$app->actionToId(__METHOD__),
            dataUrl:       json_encode($daftarProcessList),
            killProsesUrl: Yii::$app->actionToUrl([self::class, "actionKillProses"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/monitoringdb.php#killproses    the original method
     */
    public function actionKillProses(): string
    {
        ["idproses" => $idproses] = Yii::$app->request->post();
        if ($idproses) {
            $connection = Yii::$app->dbFatma;
            $connection->createCommand("KILL :id", [":id" => $idproses])->execute();
        }
        return $this->renderPartial("_table");
    }
}
