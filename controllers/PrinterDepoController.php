<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\PrinterDepo\{Result, SetPrinter};
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
class PrinterDepoController extends BaseController
{

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/printerdepo.php#setprinter    the original method
     */
    public function actionSetPrinter(): string
    {
        $kodeDepo = Yii::$app->userFatma->idDepo;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id         AS id,
                namaDepo   AS namaDepo,
                lokasiDepo AS lokasiDepo
            FROM db1.masterf_depo
            WHERE kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kodeDepo];
        $depo = $connection->createCommand($sql, $params)->queryOne();

        ["tipe_nama_printer" => $tipeNamaPrinter, "no_printer" => $noPrinter] = Yii::$app->request->post();
        $ip = $this->input->ip_address();

        if ($tipeNamaPrinter) {
            // TODO: php: uncategorized: to be deleted
            $this->render("xxx", ["ip" => $ip, "depo" => $depo]);

            $view = new SetPrinter(
                registerId:        Yii::$app->actionToId(__METHOD__),
                actionUrl:         Yii::$app->actionToUrl([PrinterDepoController::class, "actionSetPrinter"]),
                printerDepoSelect: Yii::$app->actionToUrl([DepoController::class, "actionSelectPrinter3Data"]),
            );
            return $view->__toString();

        } else {
            $inputPrinter = explode("|||", $tipeNamaPrinter);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.printer_depo
                SET
                    id_depo = :idDepo,
                    kode_depo = :kodeDepo,
                    ip_address = :ipAddress,
                    no_printer = :noPrinter,
                    tipe_printer = :tipePrinter,
                    nama_printer = :namaPrinter
                ON DUPLICATE KEY UPDATE ip_address = :ipAddress
            ";
            $params = [
                ":idDepo"      => $depo->id,
                ":kodeDepo"    => $kodeDepo,
                ":ipAddress"   => $ip,
                ":noPrinter"   => $noPrinter,
                ":tipePrinter" => $inputPrinter[0],
                ":namaPrinter" => $inputPrinter[1],
            ];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();

            $result = ($berhasilTambah)
                ? "Berhasil mengupdate IP address printer"
                : "Gagal mengupdate IP address printer. Printer masih ada di IP yang sama atau terjadi kegagalan pada server";

            $view = new Result(
                setPrinterWidgetId: Yii::$app->actionToId([self::class, "actionSetPrinter"]),
                result:             $result
            );
            return $view->__toString();
        }
    }
}
