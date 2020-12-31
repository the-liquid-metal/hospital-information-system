<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\Information\Search;
use tlm\libs\LowEnd\components\DateTimeException;
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
class InformasiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/information.php#index    the original method
     */
    public function actionTable(): string
    {
        [   "hide" => $statusHide,
            "reff" => $kodeRef,
            "t" => $modulExec        // NOTE change "warning" to ""
        ] = Yii::$app->request->get();
        $idUser = Yii::$app->userFatma->id;

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_notif            AS idNotif,
                A.tipe_notif          AS tipeNotif,
                A.id_fromSatker       AS idDariSatker,
                A.modul_reff          AS modulRef,
                A.modul_exec          AS modulExec,
                A.action_exec         AS actionExec,
                A.tgl_notif           AS tanggalNotif,
                A.kode_reff           AS kode,
                A.info_reff           AS infoRef,
                A.point_ofview        AS pointOfView,
                A.description_reff    AS deskripsiRef,
                A.nodoc_reff          AS noDokumenRef,
                A.ver_execution       AS verEksekusi,
                A.ver_tglexecution    AS verTanggalEksekusi,
                A.verif_reff          AS verifRef,
                FR.unit_kerja         AS dariSatker,
                IFNULL(U.sts_hide, 0) AS statusHide,
                IFNULL(sts_read, 0)   AS statusRead,
                Uexc.name             AS namaUserEksekusi
            FROM db1.transaksi_notification AS A
            LEFT JOIN db1.master_unitkerja AS FR ON A.id_fromSatker = FR.kode
            LEFT JOIN db1.transaksi_usernotif AS U ON A.id_notif = U.id_notif
            LEFT JOIN db1.user AS Uexc ON Uexc.id = A.ver_usrexecution
            INNER JOIN (
                SELECT
                    kode_reff      AS kode_reff,
                    MAX(info_reff) AS info_reff
                FROM db1.transaksi_notification AS A
                WHERE A.id_toUser IN (0, :idUser)
                GROUP BY kode_reff
            ) AS Aa ON A.kode_reff = Aa.kode_reff
            WHERE
                A.id_toUser IN (0, :idUser)
                AND (:statusHide != '' AND :statusHide != 2 AND U.sts_hide = :statusHide)
                AND (:statusHide = '' AND (U.sts_hide IS NULL OR U.sts_hide = 0))
                AND (:kodeRef = '' OR A.kode_reff = :kodeRef)
                AND (:modulExec = 'warning' AND A.modul_exec IS NOT NULL)
                AND A.info_reff = Aa.info_reff
                AND U.id_user = :idUser
                AND FR.sts_aktif = 1
            ORDER BY tgl_notif DESC
        ";
        $params = [":statusHide" => $statusHide, ":kodeRef" => $kodeRef, ":modulExec" => $modulExec, ":idUser" => $idUser];
        $daftarNotifikasi = $connection->createCommand($sql, $params)->queryAll();

        $data = [];
        foreach ($daftarNotifikasi as $r) {
            // if ($t == 't = warning' && $r->modul_exec != null && is_auth($r->modul_exec, $r->action_exec)) {
                array_push($data, $r);
            // } elseif ($t == 't = info' && is_auth($r->modul_reff, "views") && ! is_auth($r->modul_exec, $r->action_exec)) {
            //     array_push($data, $r);
            // }
        }

        return $this->renderPartial("_table", [
            "detail" => "information",
            "tablename" => "transaksi_notification",
            "t" => $modulExec ? "t = $modulExec" : "",
            "fields" => [ // perencanaan
                "tgl_notif" => "Tgl Notif",
                "nodoc_reff" => "No. Dokumen",
                "point_ofview" => "Point of View",
                "description_reff" => "Keterangan",
                "info_reff" => "Revisi/Adendum ke-",
                "ver_execution" => "Acc Revisi/Adendum",
                "verif_reff" => "Status Revisi/Adendum",
            ],
            "iTotal" => count($data),
            "data" => $data,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/information.php#hide    the original method
     */
    public function actionHide(): string
    {
        $post = Yii::$app->request->post();
        $id = $post["dataFilter"]["id_notif"] ?? $post["id"];
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksi_usernotif
            SET
                id_user = :idUser,
                id_notif = :idNotifikasi,
                sts_read = 1,
                sysdate_read = :tanggal,
                sts_hide = 1,
                sysdate_hide = :tanggal
            ON DUPLICATE KEY UPDATE
                sysdate_hide = IF(sts_hide = 1, sysdate_hide, :tanggal),
                sts_hide = 1
        ";
        $params = [":idUser" => $idUser, ":tanggal" => $nowValSystem, ":idNotifikasi" => $id];
        $jumlahRowTambah = $connection->createCommand($sql, $params)->execute();

        return json_encode($jumlahRowTambah);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/information.php#helps    the original method
     */
    public function actionHelpData(): string
    {
        ["id_katalog" => $idKatalog, "sts_closed" => $stsClosed] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                  AS idKatalog,
                A.nama_sediaan          AS namaSediaan,
                'perencanaan'           AS linkRencana,
                IFNULL(tR.kode, '-')    AS kodeRencana,
                IFNULL(tR.no_doc, '-')  AS noRencana,
                'pengadaan'             AS linkHps,
                IFNULL(tH.kode, '-')    AS kodeHps,
                IFNULL(tH.no_doc, '-')  AS noHps,
                'pembelian'             AS linkSpk,
                IFNULL(tP.kode, '-')    AS kodeSpk,
                IFNULL(tP.no_doc, '-')  AS noSpk,
                'perencanaan'           AS linkRo,
                IFNULL(tRO.kode, '-')   AS kodeRo,
                IFNULL(tRO.no_doc, '-') AS noRo,
                'pemesanan'             AS linkDo,
                IFNULL(tS.kode, '-')    AS kodeDo,
                IFNULL(tS.no_doc, '-')  AS noDo,
                'penerimaan'            As linkTerima1,
                IFNULL(tT.kode, '-')    AS kodeTerima1,
                IFNULL(tT.no_doc, '-')  AS noTerima1,
                'penerimaan'            AS linkTerima2,
                IFNULL(tT2.kode, '-')   AS kodeTerima2,
                IFNULL(tT2.no_doc, '-') AS noTerima2,
                'penerimaan'            AS linkTerima3,
                IFNULL(tT3.kode, '-')   AS kodeTerima3,
                IFNULL(tT3.no_doc, '-') AS noTerima3,
                'returnfarmasi'         AS linkRetur,
                IFNULL(tRT.kode, '-')   AS kodeRetur,
                IFNULL(tRT.no_doc, '-') AS noRetur
            FROM db1.masterf_katalog AS A
            LEFT JOIN db1.tdetailf_perencanaan AS dR ON A.kode = dR.id_katalog
            LEFT JOIN db1.transaksif_perencanaan AS tR ON dR.kode_reff = tR.kode
            LEFT JOIN db1.tdetailf_pengadaan AS dH ON dR.id_katalog = dH.id_reffkatalog
            LEFT JOIN db1.transaksif_pengadaan AS tH ON dH.kode_reff = tH.kode
            LEFT JOIN db1.tdetailf_pembelian AS dP ON dH.id_katalog = dP.id_reffkatalog
            LEFT JOIN db1.transaksif_pembelian AS tP ON dP.kode_reff = tP.kode
            LEFT JOIN db1.tdetailf_perencanaan AS dRO ON dP.id_katalog = dRO.id_reffkatalog
            LEFT JOIN db1.transaksif_perencanaan AS tRO ON dRO.kode_reff = tRO.kode
            LEFT JOIN db1.tdetailf_pemesanan AS dS ON dRO.id_katalog = dS.id_reffkatalog
            LEFT JOIN db1.transaksif_pemesanan AS tS ON dS.kode_reff = tS.kode
            LEFT JOIN db1.tdetailf_penerimaan AS dT ON dS.id_katalog = dT.id_reffkatalog
            LEFT JOIN db1.transaksif_penerimaan AS tT ON dT.kode_reff = tT.kode
            LEFT JOIN db1.tdetailf_penerimaan AS dT2 ON dP.id_katalog = dT2.id_reffkatalog
            LEFT JOIN db1.transaksif_penerimaan AS tT2 ON dT2.kode_reff = tT2.kode
            LEFT JOIN db1.tdetailf_penerimaan AS dT3 ON A.kode = dT3.id_katalog
            LEFT JOIN db1.transaksif_penerimaan AS tT3 ON dT3.kode_reff = tT3.kode
            LEFT JOIN db1.tdetailf_return AS dRT ON A.kode = dRT.id_katalog
            LEFT JOIN db1.transaksif_return AS tRT ON dRT.kode_reff = tRT.kode
            WHERE
                A.kode = :idKatalog
                AND tRT.sts_deleted = 0
                AND tT3.sts_deleted = 0
                AND tT3.tipe_doc != 0
                AND tT2.sts_deleted = 0
                AND dT2.kode_reffpl = dP.kode_reff
                AND tT.sts_deleted = 0
                AND dT.kode_reffpo = dS.kode_reff
                AND dT.kode_reffpl = dS.kode_reffpl
                AND tS.sts_deleted = 0
                AND tS.sts_closed = :statusClosed
                AND dS.kode_reffro = dRO.kode_reff
                AND dS.kode_reffpl = dRO.kode_reffpl
                AND tRO.sts_deleted = 0
                AND tRO.sts_closed = :statusClosed
                AND dRO.kode_reffpl = dP.kode_reff
                AND dRO.kode_reffrenc = dP.kode_reffrenc
                AND tP.sts_deleted = 0
                AND tP.sts_closed = :statusClosed
                AND dP.kode_reffhps = dH.kode_reff
                AND dP.kode_reffrenc = dH.kode_reffrenc
                AND tH.sts_deleted = 0
                AND tH.sts_closed = :statusClosed
                AND dH.kode_reffrenc = dR.kode_reff
                AND tR.sts_deleted = 0
                AND tR.sts_closed = :statusClosed
        ";
        $params = [":statusClosed" => $stsClosed, ":idKatalog" => $idKatalog];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        return $this->renderPartial("itemin", [
            "iTotal" => count($daftarKatalog),
            "fields" => [
                "nama_sediaan" => "Nama Barang",
                "renc" => "No Perencanaan",
                "hps" => "HPS Pengadaan",
                "spk" => "PL Pembelian",
                "ro" => "No Repeate Order",
                "do" => "Delivery Order",
                "trm" => "Penerimaan By DO",
                "trm2" => "Penerimaan dr PL",
                "trm3" => "Penerimaan diluar PL",
                "ret" => "No Return",
            ],
            "idata" => $daftarKatalog,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/information.php#helps    the original method
     */
    public function actionHelps(): string
    {
        $view = new Search(
            registerId:       Yii::$app->actionToId(__METHOD__),
            action1Url:       Yii::$app->actionToUrl([self::class, "actionHelpData"]),
            action2Url:       Yii::$app->actionToUrl([self::class, "actionHelpData"]),
            pembelianAcplUrl: Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
            pemesananAcplUrl: Yii::$app->actionToUrl([PemesananController::class, "actionSearchJsonTerima"]),
            pemasokAcplUrl:   Yii::$app->actionToUrl([PemasokController::class, "actionSelect"]),
            katalogAcplUrl:   Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSearch"]),
            batchUrl:         Yii::$app->actionToUrl([DistribusiController::class, "actionSearchJsonBatch"]),
            unitUrl:          Yii::$app->actionToUrl([DistribusiController::class, "actionSearchJsonUnit"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/information.php#Bugs    the original method (not exist)
     */
    public function actionBugs(): void
    {
        // sync it
    }
}
