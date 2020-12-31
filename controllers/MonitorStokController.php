<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\MonitorStok\{MonitorStok1, MonitorStok2, MonitorStok3, MonitorStokLaserjet};
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
class MonitorStokController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#index    the original method
     */
    public function actionTable(): string
    {
        ["submit" => $submit, "id_depo" => $idDepo, "pilihan_print" => $pilihanPrint] = Yii::$app->request->post();

        if ($submit == "monitor") {
            $connection = Yii::$app->dbFatma;

            if ($idDepo != "-") {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        SKAT.jumlah_stokadm    AS jumlahStokAdm,
                        SKAT.jumlah_stokfisik  AS jumlahStokFisik,
                        DPO.namaDepo           AS namaDepo,
                        DPO.id                 AS idDepo,
                        KAT.kode               AS kodeBarang,
                        KAT.nama_barang        AS namaBarang,
                        KBG.kode               AS kodeKelompok,
                        KBG.kelompok_barang    AS kelompokBarang,
                        PBK.nama_pabrik        AS namaPabrik,
                        KEM.kode               AS kodeKemasan,
                        HPR.hp_item            AS hja
                    FROM db1.transaksif_stokkatalog AS SKAT
                    LEFT JOIN db1.masterf_depo AS DPO ON SKAT.id_depo = DPO.id
                    LEFT JOIN db1.masterf_katalog AS KAT ON SKAT.id_katalog = KAT.kode
                    LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
                    LEFT JOIN db1.masterf_pabrik AS PBK ON KAT.id_pabrik = PBK.id
                    LEFT JOIN db1.masterf_kemasan AS KEM ON KAT.id_kemasankecil = KEM.id
                    LEFT JOIN db1.relasif_hargaperolehan AS HPR ON SKAT.id_katalog = HPR.id_katalog
                    WHERE
                        SKAT.id_depo = :idDepo
                        AND HPR.sts_hja = 1
                    ORDER BY KAT.kode ASC
                ";
                $params = [":idDepo" => $idDepo];
                $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        SO.kode     AS kode,
                        SO.id_depo  AS idDepo,
                        SO.tgl_adm  AS tanggalAdm,
                        DP.namaDepo AS namaDepo
                    FROM db1.transaksif_stokopname AS SO
                    LEFT JOIN db1.masterf_depo AS DP ON SO.id_depo = DP.id
                    WHERE SO.id_depo = :idDepo
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepo];
                $adm = $connection->createCommand($sql, $params)->queryOne();

            } else {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        IFNULL(SUM(jumlah_stokadm), 0)   AS jumlahStokAdm,
                        IFNULL(SUM(jumlah_stokfisik), 0) AS jumlahStokFisik,
                        'Semua Gudang'                   AS namaDepo,
                        0                                AS idDepo,
                        C.kode                           AS kodeBarang,
                        C.nama_barang                    AS namaBarang,
                        D.kode                           AS kodeKelompok,
                        D.kelompok_barang                AS kelompokBarang,
                        G.nama_pabrik                    AS namaPabrik,
                        E.kode                           AS kodeKemasan,
                        F.hp_item AS hja
                    FROM db1.transaksif_stokkatalog AS A
                    LEFT JOIN db1.masterf_depo AS B ON A.id_depo = B.id
                    LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
                    LEFT JOIN db1.masterf_kelompokbarang AS D ON C.id_kelompokbarang = D.id
                    LEFT JOIN db1.masterf_kemasan AS E ON C.id_kemasankecil = E.id
                    LEFT JOIN db1.relasif_hargaperolehan AS F ON A.id_katalog = F.id_katalog
                    LEFT JOIN db1.masterf_pabrik AS G ON C.id_pabrik = G.id
                    WHERE
                        A.status = 1
                        AND F.sts_hja = 1
                        AND B.id NOT IN (319, 320, 321, 68)
                    GROUP BY A.id_katalog
                    ORDER BY C.kode ASC
                ";
                $daftarStokKatalog = $connection->createCommand($sql)->queryAll();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        AKSO.kode           AS kode,
                        59                  AS ___,
                        AKSO.tgl_adm        AS tanggalAdm,
                        'Instalasi Farmasi' AS namaDepo
                    FROM db1.masterf_aktifasiso AS AKSO
                    WHERE AKSO.status = 1
                    LIMIT 1
                ";
                $adm = $connection->createCommand($sql)->queryOne();
            }

            $pilihanPrint ??= 1;
            if ($pilihanPrint == 1) {
                $view = new MonitorStok2(
                    tableWidgetId:     Yii::$app->actionToId([self::class, "actionTable"]),
                    dokumenWidgetId:   Yii::$app->actionToId([self::class, "actionDocuments"]), // TODO: php: missing method: TRUELY MISSING self::actionDocuments
                    daftarStokKatalog: $daftarStokKatalog,
                    adm:               $adm,
                );
                return $view->__toString();

            } else {
                $view = new MonitorStok3(
                    tableWidgetId:     Yii::$app->actionToId([self::class, "actionTable"]),
                    dokumenWidgetId:   Yii::$app->actionToId([self::class, "actionDocuments"]), // TODO: php: missing method: TRUELY MISSING self::actionDocuments
                    daftarStokKatalog: $daftarStokKatalog,
                    adm:               $adm,
                );
                return $view->__toString();
            }

        } else {
            return $this->renderPartial("_table", [ // TODO: php: uncategorized: restore deleted file
                "detail" => "monitorfisik",
                "tablename" => "relasif_ketersediaan",
            ]);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#monitoradm    the original method
     */
    public function actionMonitorAdm(): string
    {
        ["submit" => $submit, "id_depo" => $idDepo, "pilihan_print" => $pilihanPrint] = Yii::$app->request->post();

        if ($submit == "monitor") {
            $connection = Yii::$app->dbFatma;

            if ($idDepo != "-") {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        SKAT.jumlah_stokadm     AS jumlahStokAdm,
                        SKAT.jumlah_stokfisik   AS jumlahStokFisik,
                        DPO.namaDepo            AS namaDepo,
                        DPO.id                  AS idDepo,
                        KAT.kode                AS kodeBarang,
                        KAT.nama_barang         AS namaBarang,
                        KBG.kode                AS kodeKelompok,
                        KBG.kelompok_barang     AS kelompokBarang,
                        PBK.nama_pabrik         AS namaPabrik,
                        KEM.kode                AS kodeKemasan,
                        HPR.hp_item             AS hja
                    FROM db1.transaksif_stokkatalog AS SKAT
                    LEFT JOIN db1.masterf_depo AS DPO ON SKAT.id_depo = DPO.id
                    LEFT JOIN db1.masterf_katalog AS KAT ON SKAT.id_katalog = KAT.kode
                    LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
                    LEFT JOIN db1.masterf_pabrik AS PBK ON KAT.id_pabrik = PBK.id
                    LEFT JOIN db1.masterf_kemasan AS KEM ON KAT.id_kemasankecil = KEM.id
                    LEFT JOIN db1.relasif_hargaperolehan AS HPR ON SKAT.id_katalog = HPR.id_katalog
                    WHERE
                        SKAT.id_depo = :idDepo
                        AND HPR.sts_hja = 1
                ";
                $params = [":idDepo" => $idDepo];
                $jumlahStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        SKAT.jumlah_stokadm     AS jumlahStokAdm,
                        SKAT.jumlah_stokfisik   AS jumlahStokFisik,
                        DPO.namaDepo            AS namaDepo,
                        DPO.id                  AS idDepo,
                        KAT.kode                AS kodeBarang,
                        KAT.nama_barang         AS namaBarang,
                        KBG.kode                AS kodeKelompok,
                        KBG.kelompok_barang     AS kelompokBarang,
                        PBK.nama_pabrik         AS namaPabrik,
                        KEM.kode                AS kodeKemasan,
                        HPR.hp_item             AS hja
                    FROM db1.transaksif_stokkatalog AS SKAT
                    LEFT JOIN db1.masterf_depo AS DPO ON SKAT.id_depo = DPO.id
                    LEFT JOIN db1.masterf_katalog AS KAT ON SKAT.id_katalog = KAT.kode
                    LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
                    LEFT JOIN db1.masterf_pabrik AS PBK ON KAT.id_pabrik = PBK.id
                    LEFT JOIN db1.masterf_kemasan AS KEM ON KAT.id_kemasankecil = KEM.id
                    LEFT JOIN db1.relasif_hargaperolehan AS HPR ON SKAT.id_katalog = HPR.id_katalog
                    WHERE
                        SKAT.id_depo = :idDepo
                        AND HPR.sts_hja = 1
                ";
                $params = [":idDepo" => $idDepo];
                $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        SO.kode     AS kode,
                        SO.id_depo  AS idDepo,
                        SO.tgl_adm  AS tanggalAdm,
                        DP.namaDepo AS namaDepo
                    FROM db1.transaksif_stokopname AS SO
                    LEFT JOIN db1.masterf_depo AS DP ON SO.id_depo = DP.id
                    WHERE
                        SO.id_depo = :idDepo
                        AND SO.sts_aktif = 1
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepo];
                $adm = $connection->createCommand($sql, $params)->queryOne();

            } else {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        IFNULL(SUM(jumlah_stokadm), 0)   AS jumlahStokAdm,
                        IFNULL(SUM(jumlah_stokfisik), 0) AS jumlahStokFisik,
                        'Semua Gudang'                   AS namaDepo,
                        0                                AS idDepo,
                        C.kode                           AS kodeBarang,
                        C.nama_barang                    AS namaBarang,
                        D.kode                           AS kodeKelompok,
                        D.kelompok_barang                AS kelompokBarang,
                        G.nama_pabrik                    AS namaPabrik,
                        E.kode                           AS kodeKemasan,
                        F.hp_item                        AS hja
                    FROM db1.transaksif_stokkatalog AS A
                    LEFT JOIN db1.masterf_depo AS B ON A.id_depo = B.id
                    LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
                    LEFT JOIN db1.masterf_kelompokbarang AS D ON C.id_kelompokbarang = D.id
                    LEFT JOIN db1.masterf_kemasan AS E ON C.id_kemasankecil = E.id
                    LEFT JOIN db1.relasif_hargaperolehan AS F ON A.id_katalog = F.id_katalog
                    LEFT JOIN db1.masterf_pabrik AS G ON C.id_pabrik = G.id
                    WHERE
                        A.status = 1
                        AND F.sts_hja = 1
                    GROUP BY A.id_katalog
                    ORDER BY C.kode ASC
                ";
                $daftarStokKatalog = $connection->createCommand($sql)->queryAll();
                $jumlahStokKatalog = count($daftarStokKatalog);

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        AKSO.kode           AS kode,
                        59                  AS ___,
                        AKSO.tgl_adm        AS tanggalAdm,
                        'Instalasi Farmasi' AS namaDepo
                    FROM db1.masterf_aktifasiso AS AKSO
                    WHERE AKSO.status = 1
                    LIMIT 1
                ";
                $adm = $connection->createCommand($sql)->queryOne();
            }

            if ($pilihanPrint == 1) {
                $view = new MonitorStok1(
                    tableWidgetId:     Yii::$app->actionToId([self::class, "actionTable"]),
                    dokumenWidgetId:   Yii::$app->actionToId([self::class, "actionDocuments"]), // TODO: php: missing method: TRUELY MISSING self::actionDocuments
                    daftarStokKatalog: $daftarStokKatalog,
                    adm:               $adm,
                );
                return $view->__toString();

            } elseif ($pilihanPrint == 2) {
                $view = new MonitorStokLaserjet(
                    tableWidgetId:     Yii::$app->actionToId([self::class, "actionTable"]),
                    dokumenWidgetId:   Yii::$app->actionToId([self::class, "actionDocuments"]), // TODO: php: missing method: TRUELY MISSING self::actionDocuments
                    daftarStokKatalog: $daftarStokKatalog,
                    adm:               $adm,
                );
                return $view->__toString();

            } else {
                return $this->renderPartial("monitor-stok-lq", [ // TODO: php: uncategorized: restore deleted file
                    "detail" => "monitoradm",
                    "tablename" => "relasif_ketersediaan",
                    "adm" => $adm,
                    "jumlahStokKatalog" => $jumlahStokKatalog,
                    "daftarStokKatalog" => $daftarStokKatalog,
                ]);
            }
        } else {
            return $this->renderPartial("search", [ // TODO: php: uncategorized: restore deleted file
                "detail" => "monitoradm",
                "tablename" => "relasif_ketersediaan",
            ]);
        }
    }
}
