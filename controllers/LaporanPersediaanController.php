<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\libs\LowEnd\components\DateTimeException;
use tlm\libs\LowEnd\controllers\BaseController;
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
class LaporanPersediaanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstok    the original method
     * last exist of actionJumlahStokData: commit-e37d34f4
     */
    public function actionReport30juni(): string
    {
        ["idDepo" => $idDepo, "tampilan" => $tampilan, "dump" => $dump] = Yii::$app->request->post();
        $defaultSystemDatetime = Yii::$app->dateTime->defaultVal("system");
        $connection = Yii::$app->dbFatma;

        if ($idDepo) {
            // TODO: sql: ambiguous column name:
            // TODO: php: convert date.
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog       AS idKatalog,
                    A.nama_barang      AS namaBarang,
                    B.nama_pabrik      AS namaPabrik,
                    C.kode             AS satuan,
                    D.jenis_obat       AS jenisObat,
                    D.kode_group       AS kodeJenis,
                    E.kelompok_barang  AS kelompokBarang,
                    E.kode             AS kodeKelompok,
                    X.jumlah_tersedia  AS jumlah,
                    X.id               AS id,
                    X.id_depo          AS idDepo,
                    X.kode_reff        AS kodeRef,
                    X.no_doc           AS noDokumen,
                    X.ppn              AS ppn,
                    X.id_reff          AS idRef,
                    X.kode_stokopname  AS kodeStokopname,
                    X.tgl_adm          AS tanggalAdm,
                    X.tgl_transaksi    AS tanggalTransaksi,
                    X.bln_transaksi    AS bulanTransaksi,
                    X.thn_transaksi    AS tahunTransaksi,
                    X.kode_transaksi   AS kodeTransaksi,
                    X.kode_store       AS kodeStore,
                    X.tipe_tersedia    AS tipeTersedia,
                    X.tgl_tersedia     AS tanggalTersedia,
                    X.no_batch         AS noBatch,
                    X.tgl_expired      AS tanggalKadaluarsa,
                    X.id_katalog       AS idKatalog,
                    X.id_pabrik        AS idPabrik,
                    X.id_kemasan       AS idKemasan,
                    X.isi_kemasan      AS isiKemasan,
                    X.jumlah_sebelum   AS jumlahSebelum,
                    X.jumlah_masuk     AS jumlahMasuk,
                    X.jumlah_keluar    AS jumlahKeluar,
                    X.jumlah_tersedia  AS jumlahTersedia,
                    X.harga_netoapotik AS hargaNetoApotik,
                    X.harga_perolehan  AS hargaPerolehan,
                    X.phja             AS phja,
                    X.phja_pb          AS phjaPb,
                    X.harga_jualapotik AS hargaJualApotik,
                    X.jumlah_item      AS jumlahItem,
                    X.jumlah_kemasan   AS jumlahKemasan,
                    X.jumlah_spk       AS jumlahSpk,
                    X.jumlah_do        AS jumlahDo,
                    X.jumlah_terima    AS jumlahTerima,
                    X.harga_item       AS hargaItem,
                    X.harga_kemasan    AS hargaKemasan,
                    X.diskon_item      AS diskonItem,
                    X.diskonjual_item  AS diskonJualItem,
                    X.diskon_harga     AS diskonHarga,
                    X.status           AS status,
                    X.keterangan       AS keterangan,
                    X.userid_last      AS useridLast,
                    X.sysdate_last     AS sysdateLast
                FROM db1.relasif_ketersediaan AS X
                INNER JOIN (
                    SELECT
                        A.id_depo           AS id_depo,
                        A.id_katalog        AS id_katalog,
                        MAX(A.tgl_tersedia) AS tgl_max
                    FROM db1.relasif_ketersediaan AS A
                    WHERE
                        A.id_depo = :idDepo
                        AND A.tgl_tersedia <= '2015-06-30 23:59:59'
                        AND A.status = 1
                    GROUP BY
                        A.id_depo,
                        A.id_katalog
                ) AS Y ON X.id_depo = Y.id_depo
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.id_depo = :idDepo
                    AND X.status = 1
                    AND X.id_katalog = Y.id_katalog
                    AND X.tgl_tersedia = Y.tgl_max
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog,
                    X.tgl_tersedia
            ";
            $params = [":idDepo" => $idDepo];

        } else {
            // TODO: sql: uncategorized: column ambiguity
            // TODO: php: convert date.
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog                      AS idKatalog,
                    A.nama_barang                     AS namaBarang,
                    B.nama_pabrik                     AS namaPabrik,
                    C.kode                            AS satuan,
                    D.jenis_obat                      AS jenisObat,
                    D.kode_group                      AS kodeJenis,
                    E.kelompok_barang                 AS kelompokBarang,
                    E.kode                            AS kodeKelompok,
                    IFNULL(SUM(X.jumlah_tersedia), 0) AS jumlah,
                    X.id                              AS id,
                    X.id_depo                         AS idDepo,
                    X.kode_reff                       AS kodeRef,
                    X.no_doc                          AS noDokumen,
                    X.ppn                             AS ppn,
                    X.id_reff                         AS idRef,
                    X.kode_stokopname                 AS kodeStokopname,
                    X.tgl_adm                         AS tanggalAdm,
                    X.tgl_transaksi                   AS tanggalTransaksi,
                    X.bln_transaksi                   AS bulanTransaksi,
                    X.thn_transaksi                   AS tahunTransaksi,
                    X.kode_transaksi                  AS kodeTransaksi,
                    X.kode_store                      AS kodeStore,
                    X.tipe_tersedia                   AS tipeTersedia,
                    X.tgl_tersedia                    AS tanggalTersedia,
                    X.no_batch                        AS noBatch,
                    X.tgl_expired                     AS tanggalKadaluarsa,
                    X.id_katalog                      AS idKatalog,
                    X.id_pabrik                       AS idPabrik,
                    X.id_kemasan                      AS idKemasan,
                    X.isi_kemasan                     AS isiKemasan,
                    X.jumlah_sebelum                  AS jumlahSebelum,
                    X.jumlah_masuk                    AS jumlahMasuk,
                    X.jumlah_keluar                   AS jumlahKeluar,
                    X.jumlah_tersedia                 AS jumlahTersedia,
                    X.harga_netoapotik                AS hargaNetoApotik,
                    X.harga_perolehan                 AS hargaPerolehan,
                    X.phja                            AS phja,
                    X.phja_pb                         AS phjaPb,
                    X.harga_jualapotik                AS hargaJualApotik,
                    X.jumlah_item                     AS jumlahItem,
                    X.jumlah_kemasan                  AS jumlahKemasan,
                    X.jumlah_spk                      AS jumlahSpk,
                    X.jumlah_do                       AS jumlahDo,
                    X.jumlah_terima                   AS jumlahTerima,
                    X.harga_item                      AS hargaItem,
                    X.harga_kemasan                   AS hargaKemasan,
                    X.diskon_item                     AS diskonItem,
                    X.diskonjual_item                 AS diskonJualItem,
                    X.diskon_harga                    AS diskonHarga,
                    X.status                          AS status,
                    X.keterangan                      AS keterangan,
                    X.userid_last                     AS useridLast,
                    X.sysdate_last                    AS sysdateLast,
                    Z.hp_item                         AS hargaPerolehan
                FROM db1.relasif_ketersediaan AS X
                INNER JOIN (
                    SELECT
                        A.id_depo      AS id_depo,
                        A.id_katalog   AS id_katalog,
                        A.tgl_tersedia AS tgl_tersedia,
                        MAX(id)        AS id
                    FROM db1.relasif_ketersediaan AS A
                    INNER JOIN (
                        SELECT
                            id_depo           AS id_depo,
                            id_katalog        AS id_katalog,
                            MAX(tgl_tersedia) AS tgl_max
                        FROM db1.relasif_ketersediaan
                        WHERE
                            tgl_tersedia > :tanggalAwal
                            AND tgl_tersedia <= '2015-06-30 23:59:59'
                            AND relasif_ketersediaan.status = 1
                            AND id_depo IN (SELECT id FROM db1.masterf_depo WHERE keterangan = 'depo')
                        GROUP BY
                            id_depo,
                            id_katalog
                    ) AS B ON A.id_depo = B.id_depo
                    WHERE
                        A.id_katalog = B.id_katalog
                        AND A.tgl_tersedia = B.tgl_max
                    GROUP BY A.id_depo, A.id_katalog, A.tgl_tersedia
                ) AS Y ON X.id_depo = Y.id_depo
                LEFT JOIN (
                    SELECT A.*
                    FROM db1.relasif_hargaperolehan AS A
                    INNER JOIN (
                        SELECT
                            id_katalog  AS id_katalog,
                            MAX(tgl_hp) AS tgl_max
                        FROM db1.relasif_hargaperolehan
                        WHERE tgl_hp <= '2015-06-30 23:59:59'
                        GROUP BY id_katalog
                    ) AS B ON A.id_katalog = B.id_katalog
                    WHERE A.tgl_hp = B.tgl_max
                    GROUP BY A.id_katalog
                    ORDER BY A.id_katalog
                ) AS Z ON X.id_katalog = Z.id_katalog
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.status = 1
                    AND Y.id = X.id
                    AND X.id_katalog = Y.id_katalog
                GROUP BY X.id_katalog
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog,
                    X.tgl_tersedia
            ";
            $params = [":tanggalAwal" => $defaultSystemDatetime];
        }
        $daftarKetersediaan = $connection->createCommand($sql, $params)->queryAll();

        $dataView = [
            "depoJson" => Yii::$app->actionToUrl([DepoController::class, "actionSelect5Data"]),
            "data" => $daftarKetersediaan,
        ];

        if ($dump == 1) {
            return $this->renderPartial("dump-stok", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($dump == "print") {
            return $this->renderPartial("jumlah-stok-print", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($tampilan == 2) {
            return $this->renderPartial("jumlah-stok-kelompok", $dataView); // TODO: php: uncategorized: restore deleted file

        } else {
            return $this->renderPartial("jumlah-stok", $dataView); // TODO: php: uncategorized: restore deleted file
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstokseptbu    the original method
     * last exist of actionJumlahStokSeptBuData: commit-e37d34f4
     */
    public function actionReport30sept(): string
    {
        ["idDepo" => $idDepo, "tampilan" => $tampilan, "dump" => $dump] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($idDepo) {
            // TODO: sql: ambiguous column name:
            // TODO: php: convert date.
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog       AS idKatalog,
                    A.nama_barang      AS namaBarang,
                    B.nama_pabrik      AS namaPabrik,
                    C.kode             AS satuan,
                    D.jenis_obat       AS jenisObat,
                    D.kode_group       AS kodeJenis,
                    E.kelompok_barang  AS kelompokBarang,
                    E.kode             AS kodeKelompok,
                    X.jumlah_tersedia  AS jumlah,
                    X.id               AS id,
                    X.id_depo          AS idDepo,
                    X.kode_reff        AS kodeRef,
                    X.no_doc           AS noDokumen,
                    X.ppn              AS ppn,
                    X.id_reff          AS idRef,
                    X.kode_stokopname  AS kodeStokopname,
                    X.tgl_adm          AS tanggalAdm,
                    X.tgl_transaksi    AS tanggalTransaksi,
                    X.bln_transaksi    AS bulanTransaksi,
                    X.thn_transaksi    AS tahunTransaksi,
                    X.kode_transaksi   AS kodeTransaksi,
                    X.kode_store       AS kodeStore,
                    X.tipe_tersedia    AS tipeTersedia,
                    X.tgl_tersedia     AS tanggalTersedia,
                    X.no_batch         AS noBatch,
                    X.tgl_expired      AS tanggalKadaluarsa,
                    X.id_katalog       AS idKatalog,
                    X.id_pabrik        AS idPabrik,
                    X.id_kemasan       AS idKemasan,
                    X.isi_kemasan      AS isiKemasan,
                    X.jumlah_sebelum   AS jumlahSebelum,
                    X.jumlah_masuk     AS jumlahMasuk,
                    X.jumlah_keluar    AS jumlahKeluar,
                    X.jumlah_tersedia  AS jumlahTersedia,
                    X.harga_netoapotik AS hargaNetoApotik,
                    X.harga_perolehan  AS hargaPerolehan,
                    X.phja             AS phja,
                    X.phja_pb          AS phjaPb,
                    X.harga_jualapotik AS hargaJualApotik,
                    X.jumlah_item      AS jumlahItem,
                    X.jumlah_kemasan   AS jumlahKemasan,
                    X.jumlah_spk       AS jumlahSpk,
                    X.jumlah_do        AS jumlahDo,
                    X.jumlah_terima    AS jumlahTerima,
                    X.harga_item       AS hargaItem,
                    X.harga_kemasan    AS hargaKemasan,
                    X.diskon_item      AS diskonItem,
                    X.diskonjual_item  AS diskonJualItem,
                    X.diskon_harga     AS diskonHarga,
                    X.status           AS status,
                    X.keterangan       AS keterangan,
                    X.userid_last      AS useridLast,
                    X.sysdate_last     AS sysdateLast
                FROM db1.relasif_ketersediaan AS X
                INNER JOIN (
                    SELECT
                        A.id_depo           AS id_depo,
                        A.id_katalog        AS id_katalog,
                        MAX(A.tgl_tersedia) AS tgl_max
                    FROM db1.relasif_ketersediaan AS A
                    WHERE
                        A.id_depo = :idDepo
                        AND A.tgl_tersedia <= '2015-09-30 23:59:59'
                        AND A.status = 1
                    GROUP BY
                        A.id_depo,
                        A.id_katalog
                ) AS Y ON X.id_depo = Y.id_depo
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.id_depo = :idDepo
                    AND X.status = 1
                    AND X.id_katalog = Y.id_katalog
                    AND X.tgl_tersedia = Y.tgl_max
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog,
                    X.tgl_tersedia
            ";
            $params = [":idDepo", $idDepo];

        } else {
            // TODO: php: convert date.
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog                     AS idKatalog,
                    A.nama_barang                    AS namaBarang,
                    B.nama_pabrik                    AS namaPabrik,
                    C.kode                           AS satuan,
                    D.jenis_obat                     AS jenisObat,
                    D.kode_group                     AS kodeJenis,
                    E.kelompok_barang                AS kelompokBarang,
                    E.kode                           AS kodeKelompok,
                    IFNULL(SUM(X.jumlah_stokadm), 0) AS jumlah,
                    X.hp_item                        AS hargaPerolehan
                FROM db1.masterf_backupstok_so_close AS X
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.status = 1
                    AND tgl = '2015-09-30 23:59:59'
                GROUP BY id_katalog
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog
            ";
            $params = [];
        }
        $daftarData = $connection->createCommand($sql, $params)->queryAll();

        $dataView = [
            "depoJson" => Yii::$app->actionToUrl([DepoController::class, "actionSelect5Data"]),
            "data" => $daftarData,
        ];

        if ($dump == 1) {
            return $this->renderPartial("dump-stok-sept-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($dump == "print") {
            return $this->renderPartial("jumlah-stok-print-sept-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($tampilan == 2) {
            return $this->renderPartial("jumlah-stok-kelompok-sept-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } else {
            return $this->renderPartial("jumlah-stok-sept-bu", $dataView); // TODO: php: uncategorized: restore deleted file
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstokdesbu    the original method
     * last exist of actionJumlahStokDesBuData: commit-e37d34f4
     */
    public function actionReport31des(): string
    {
        ["idDepo" => $idDepo, "tampilan" => $tampilan, "dump" => $dump] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($idDepo) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog       AS idKatalog,
                    A.nama_barang      AS namaBarang,
                    B.nama_pabrik      AS namaPabrik,
                    C.kode             AS satuan,
                    D.jenis_obat       AS jenisObat,
                    D.kode_group       AS kodeJenis,
                    E.kelompok_barang  AS kelompokBarang,
                    E.kode             AS kodeKelompok,
                    X.jumlah_tersedia  AS jumlah,
                    X.id               AS id,
                    X.id_depo          AS idDepo,
                    X.kode_reff        AS kodeRef,
                    X.no_doc           AS noDokumen,
                    X.ppn              AS ppn,
                    X.id_reff          AS idRef,
                    X.kode_stokopname  AS kodeStokopname,
                    X.tgl_adm          AS tanggalAdm,
                    X.tgl_transaksi    AS tanggalTransaksi,
                    X.bln_transaksi    AS bulanTransaksi,
                    X.thn_transaksi    AS tahunTransaksi,
                    X.kode_transaksi   AS kodeTransaksi,
                    X.kode_store       AS kodeStore,
                    X.tipe_tersedia    AS tipeTersedia,
                    X.tgl_tersedia     AS tanggalTersedia,
                    X.no_batch         AS noBatch,
                    X.tgl_expired      AS tanggalKadaluarsa,
                    X.id_katalog       AS idKatalog,
                    X.id_pabrik        AS idPabrik,
                    X.id_kemasan       AS idKemasan,
                    X.isi_kemasan      AS isiKemasan,
                    X.jumlah_sebelum   AS jumlahSebelum,
                    X.jumlah_masuk     AS jumlahMasuk,
                    X.jumlah_keluar    AS jumlahKeluar,
                    X.jumlah_tersedia  AS jumlahTersedia,
                    X.harga_netoapotik AS hargaNetoApotik,
                    X.harga_perolehan  AS hargaPerolehan,
                    X.phja             AS phja,
                    X.phja_pb          AS phjaPb,
                    X.harga_jualapotik AS hargaJualApotik,
                    X.jumlah_item      AS jumlahItem,
                    X.jumlah_kemasan   AS jumlahKemasan,
                    X.jumlah_spk       AS jumlahSpk,
                    X.jumlah_do        AS jumlahDo,
                    X.jumlah_terima    AS jumlahTerima,
                    X.harga_item       AS hargaItem,
                    X.harga_kemasan    AS hargaKemasan,
                    X.diskon_item      AS diskonItem,
                    X.diskonjual_item  AS diskonJualItem,
                    X.diskon_harga     AS diskonHarga,
                    X.status           AS status,
                    X.keterangan       AS keterangan,
                    X.userid_last      AS useridLast,
                    X.sysdate_last     AS sysdateLast
                FROM db1.relasif_ketersediaan AS X
                INNER JOIN (
                    SELECT
                        A.id_depo           AS id_depo,
                        A.id_katalog        AS id_katalog,
                        MAX(A.tgl_tersedia) AS tgl_max
                    FROM db1.relasif_ketersediaan AS A
                    WHERE
                        A.id_depo = :idDepo
                        AND A.tgl_tersedia <= '2015-12-31 23:59:59'
                        AND A.status = 1
                    GROUP BY
                        A.id_depo,
                        A.id_katalog
                ) AS Y ON X.id_depo = Y.id_depo
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.id_depo = :idDepo
                    AND X.id_katalog = Y.id_katalog
                    AND X.tgl_tersedia = Y.tgl_max
                ORDER BY kodejenis, kodekelompok, X.id_katalog, X.tgl_tersedia
            ";
            $params = [":idDepo" => $idDepo];

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog                       AS idKatalog,
                    A.nama_barang                      AS namaBarang,
                    B.nama_pabrik                      AS namaPabrik,
                    C.kode                             AS satuan,
                    D.jenis_obat                       AS jenisObat,
                    D.kode_group                       AS kodeJenis,
                    E.kelompok_barang                  AS kelompokBarang,
                    E.kode                             AS kodeKelompok,
                    IFNULL(SUM(X.jumlah_stokfisik), 0) AS jumlah,
                    X.hp_item                          AS hargaPerolehan
                FROM db1.masterf_backupstok_so_close AS X
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.status = 1
                    AND X.tgl = '2015-12-31 23:59:59'
                GROUP BY id_katalog
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog
            ";
            $params = [];
        }
        $daftarData = $connection->createCommand($sql, $params)->queryAll();

        $dataView = [
            "depoJson" => Yii::$app->actionToUrl([DepoController::class, "actionSelect5Data"]),
            "data" => $daftarData,
        ];

        if ($dump == 1) {
            return $this->renderPartial("dump-stok-des-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($dump == "print") {
            return $this->renderPartial("jumlah-stok-print-dest-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($tampilan == 2) {
            return $this->renderPartial("jumlah-stok-kelompok-des-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } else {
            return $this->renderPartial("jumlah-stok-des-bu", $dataView); // TODO: php: uncategorized: restore deleted file
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstokmaret16bu    the original method
     * last exist of actionJumlahStokMaret16BuData: commit-e37d34f4
     */
    public function actionReport31maret2016(): string
    {
        ["idDepo" => $idDepo, "tampilan" => $tampilan, "dump" => $dump] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($idDepo) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog       AS idKatalog,
                    A.nama_barang      AS namaBarang,
                    B.nama_pabrik      AS namaPabrik,
                    C.kode             AS satuan,
                    D.jenis_obat       AS jenisObat,
                    D.kode_group       AS kodeJenis,
                    E.kelompok_barang  AS kelompokBarang,
                    E.kode             AS kodeKelompok,
                    X.jumlah_tersedia  AS jumlah,
                    X.id               AS id,
                    X.id_depo          AS idDepo,
                    X.kode_reff        AS kodeRef,
                    X.no_doc           AS noDokumen,
                    X.ppn              AS ppn,
                    X.id_reff          AS idRef,
                    X.kode_stokopname  AS kodeStokopname,
                    X.tgl_adm          AS tanggalAdm,
                    X.tgl_transaksi    AS tanggalTransaksi,
                    X.bln_transaksi    AS bulanTransaksi,
                    X.thn_transaksi    AS tahunTransaksi,
                    X.kode_transaksi   AS kodeTransaksi,
                    X.kode_store       AS kodeStore,
                    X.tipe_tersedia    AS tipeTersedia,
                    X.tgl_tersedia     AS tanggalTersedia,
                    X.no_batch         AS noBatch,
                    X.tgl_expired      AS tanggalKadaluarsa,
                    X.id_katalog       AS idKatalog,
                    X.id_pabrik        AS idPabrik,
                    X.id_kemasan       AS idKemasan,
                    X.isi_kemasan      AS isiKemasan,
                    X.jumlah_sebelum   AS jumlahSebelum,
                    X.jumlah_masuk     AS jumlahMasuk,
                    X.jumlah_keluar    AS jumlahKeluar,
                    X.jumlah_tersedia  AS jumlahTersedia,
                    X.harga_netoapotik AS hargaNetoApotik,
                    X.harga_perolehan  AS hargaPerolehan,
                    X.phja             AS phja,
                    X.phja_pb          AS phjaPb,
                    X.harga_jualapotik AS hargaJualApotik,
                    X.jumlah_item      AS jumlahItem,
                    X.jumlah_kemasan   AS jumlahKemasan,
                    X.jumlah_spk       AS jumlahSpk,
                    X.jumlah_do        AS jumlahDo,
                    X.jumlah_terima    AS jumlahTerima,
                    X.harga_item       AS hargaItem,
                    X.harga_kemasan    AS hargaKemasan,
                    X.diskon_item      AS diskonItem,
                    X.diskonjual_item  AS diskonJualItem,
                    X.diskon_harga     AS diskonHarga,
                    X.status           AS status,
                    X.keterangan       AS keterangan,
                    X.userid_last      AS useridLast,
                    X.sysdate_last     AS sysdateLast
                FROM db1.relasif_ketersediaan AS X
                INNER JOIN (
                    SELECT
                        A.id_depo           AS id_depo,
                        A.id_katalog        AS id_katalog,
                        MAX(A.tgl_tersedia) AS tgl_max
                    FROM db1.relasif_ketersediaan AS A
                    WHERE
                        A.id_depo = :idDepo
                        AND A.tgl_tersedia <= '2016-03-31 23:59:59'
                        AND A.status = 1
                    GROUP BY
                        A.id_depo,
                        A.id_katalog
                ) AS Y ON X.id_depo = Y.id_depo
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.id_depo = :idDepo
                    AND X.id_katalog = Y.id_katalog
                    AND X.tgl_tersedia = Y.tgl_max
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog,
                    X.tgl_tersedia
            ";
            $params = [":idDepo" => $idDepo];

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog                       AS idKatalog,
                    A.nama_barang                      AS namaBarang,
                    B.nama_pabrik                      AS namaPabrik,
                    C.kode                             AS satuan,
                    D.jenis_obat                       AS jenisObat,
                    D.kode_group                       AS kodeJenis,
                    E.kelompok_barang                  AS kelompokBarang,
                    E.kode                             AS kodeKelompok,
                    IFNULL(SUM(X.jumlah_stokfisik), 0) AS jumlah,
                    X.hp_item                          AS hargaPerolehan
                FROM db1.masterf_backupstok_so_close AS X
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE X.tgl = '2016-03-31 23:59:59'
                GROUP BY id_katalog
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog
            ";
            $params = [];
        }
        $daftarData = $connection->createCommand($sql, $params)->queryAll();

        $dataView = [
            "depoJson" => Yii::$app->actionToUrl([DepoController::class, "actionSelect5Data"]),
            "data" => $daftarData,
        ];

        if ($dump == 1) {
            return $this->renderPartial("dump-stok-maret16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($dump == "print") {
            return $this->renderPartial("jumlah-stok-print-maret-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($tampilan == 2) {
            return $this->renderPartial("jumlah-stok-kelompok-maret-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } else {
            return $this->renderPartial("jumlah-stok-maret-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstokjuni16bu    the original method
     * last exist of actionJumlahStokJuni16BuData: commit-e37d34f4
     */
    public function actionReportJuni2016(): string
    {
        ["idDepo" => $idDepo, "tampilan" => $tampilan, "dump" => $dump] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($idDepo) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog       AS idKatalog,
                    A.nama_barang      AS namaBarang,
                    B.nama_pabrik      AS namaPabrik,
                    C.kode             AS satuan,
                    D.jenis_obat       AS jenisObat,
                    D.kode_group       AS kodeJenis,
                    E.kelompok_barang  AS kelompokBarang,
                    E.kode             AS kodeKelompok,
                    X.jumlah_tersedia  AS jumlah,
                    X.id               AS id,
                    X.id_depo          AS idDepo,
                    X.kode_reff        AS kodeRef,
                    X.no_doc           AS noDokumen,
                    X.ppn              AS ppn,
                    X.id_reff          AS idRef,
                    X.kode_stokopname  AS kodeStokopname,
                    X.tgl_adm          AS tanggalAdm,
                    X.tgl_transaksi    AS tanggalTransaksi,
                    X.bln_transaksi    AS bulanTransaksi,
                    X.thn_transaksi    AS tahunTransaksi,
                    X.kode_transaksi   AS kodeTransaksi,
                    X.kode_store       AS kodeStore,
                    X.tipe_tersedia    AS tipeTersedia,
                    X.tgl_tersedia     AS tanggalTersedia,
                    X.no_batch         AS noBatch,
                    X.tgl_expired      AS tanggalKadaluarsa,
                    X.id_katalog       AS idKatalog,
                    X.id_pabrik        AS idPabrik,
                    X.id_kemasan       AS idKemasan,
                    X.isi_kemasan      AS isiKemasan,
                    X.jumlah_sebelum   AS jumlahSebelum,
                    X.jumlah_masuk     AS jumlahMasuk,
                    X.jumlah_keluar    AS jumlahKeluar,
                    X.jumlah_tersedia  AS jumlahTersedia,
                    X.harga_netoapotik AS hargaNetoApotik,
                    X.harga_perolehan  AS hargaPerolehan,
                    X.phja             AS phja,
                    X.phja_pb          AS phjaPb,
                    X.harga_jualapotik AS hargaJualApotik,
                    X.jumlah_item      AS jumlahItem,
                    X.jumlah_kemasan   AS jumlahKemasan,
                    X.jumlah_spk       AS jumlahSpk,
                    X.jumlah_do        AS jumlahDo,
                    X.jumlah_terima    AS jumlahTerima,
                    X.harga_item       AS hargaItem,
                    X.harga_kemasan    AS hargaKemasan,
                    X.diskon_item      AS diskonItem,
                    X.diskonjual_item  AS diskonJualItem,
                    X.diskon_harga     AS diskonHarga,
                    X.status           AS status,
                    X.keterangan       AS keterangan,
                    X.userid_last      AS useridLast,
                    X.sysdate_last     AS sysdateLast
                FROM db1.relasif_ketersediaan AS X
                INNER JOIN (
                    SELECT
                        A.id_depo           AS id_depo,
                        A.id_katalog        AS id_katalog,
                        MAX(A.tgl_tersedia) AS tgl_max
                    FROM db1.relasif_ketersediaan AS A
                    WHERE
                        A.id_depo = :idDepo
                        AND A.tgl_tersedia <= '2016-06-30 23:59:59'
                        AND A.status = 1
                    GROUP BY
                        A.id_depo,
                        A.id_katalog
                ) AS Y ON X.id_depo = Y.id_depo
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.id_depo = :idDepo
                    AND X.id_katalog = Y.id_katalog
                    AND X.tgl_tersedia = Y.tgl_max
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog,
                    X.tgl_tersedia
            ";
            $params = [":idDepo" => $idDepo];

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog                       AS idKatalog,
                    A.nama_barang                      AS namaBarang,
                    B.nama_pabrik                      AS namaPabrik,
                    C.kode                             AS satuan,
                    D.jenis_obat                       AS jenisObat,
                    D.kode_group                       AS kodeJenis,
                    E.kelompok_barang                  AS kelompokBarang,
                    E.kode                             AS kodeKelompok,
                    IFNULL(SUM(X.jumlah_stokfisik), 0) AS jumlah,
                    X.hp_item                          AS hargaPerolehan
                FROM db1.masterf_backupstok_so_close AS X
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE  X.tgl = '2016-06-30 23:59:59'
                GROUP BY id_katalog
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog
            ";
            $params = [];
        }
        $daftarData = $connection->createCommand($sql, $params)->queryAll();

        $dataView = [
            "depoJson" => Yii::$app->actionToUrl([DepoController::class, "actionSelect5Data"]),
            "data" => $daftarData,
        ];

        if ($dump == 1) {
            return $this->renderPartial("dump-stok-juni-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($dump == "print") {
            return $this->renderPartial("jumlah-stok-print-juni-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($tampilan == 2) {
            return $this->renderPartial("jumlah-stok-kelompok-juni-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } else {
            return $this->renderPartial("jumlah-stok-juni-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#jumlahstoksept16bu    the original method
     * last exist of actionJumlahStokSept16BuData: commit-e37d34f4
     */
    public function actionReportSept2016(): string
    {
        ["idDepo" => $idDepo, "tampilan" => $tampilan, "dump" => $dump] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($idDepo) {
            // TODO: php: convert date.
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog       AS idKatalog,
                    A.nama_barang      AS namaBarang,
                    B.nama_pabrik      AS namaPabrik,
                    C.kode             AS satuan,
                    D.jenis_obat       AS jenisObat,
                    D.kode_group       AS kodeJenis,
                    E.kelompok_barang  AS kelompokBarang,
                    E.kode             AS kodeKelompok,
                    X.jumlah_tersedia  AS jumlah,
                    X.id               AS id,
                    X.id_depo          AS idDepo,
                    X.kode_reff        AS kodeRef,
                    X.no_doc           AS noDokumen,
                    X.ppn              AS ppn,
                    X.id_reff          AS idRef,
                    X.kode_stokopname  AS kodeStokopname,
                    X.tgl_adm          AS tanggalAdm,
                    X.tgl_transaksi    AS tanggalTransaksi,
                    X.bln_transaksi    AS bulanTransaksi,
                    X.thn_transaksi    AS tahunTransaksi,
                    X.kode_transaksi   AS kodeTransaksi,
                    X.kode_store       AS kodeStore,
                    X.tipe_tersedia    AS tipeTersedia,
                    X.tgl_tersedia     AS tanggalTersedia,
                    X.no_batch         AS noBatch,
                    X.tgl_expired      AS tanggalKadaluarsa,
                    X.id_katalog       AS idKatalog,
                    X.id_pabrik        AS idPabrik,
                    X.id_kemasan       AS idKemasan,
                    X.isi_kemasan      AS isiKemasan,
                    X.jumlah_sebelum   AS jumlahSebelum,
                    X.jumlah_masuk     AS jumlahMasuk,
                    X.jumlah_keluar    AS jumlahKeluar,
                    X.jumlah_tersedia  AS jumlahTersedia,
                    X.harga_netoapotik AS hargaNetoApotik,
                    X.harga_perolehan  AS hargaPerolehan,
                    X.phja             AS phja,
                    X.phja_pb          AS phjaPb,
                    X.harga_jualapotik AS hargaJualApotik,
                    X.jumlah_item      AS jumlahItem,
                    X.jumlah_kemasan   AS jumlahKemasan,
                    X.jumlah_spk       AS jumlahSpk,
                    X.jumlah_do        AS jumlahDo,
                    X.jumlah_terima    AS jumlahTerima,
                    X.harga_item       AS hargaItem,
                    X.harga_kemasan    AS hargaKemasan,
                    X.diskon_item      AS diskonItem,
                    X.diskonjual_item  AS diskonJualItem,
                    X.diskon_harga     AS diskonHarga,
                    X.status           AS status,
                    X.keterangan       AS keterangan,
                    X.userid_last      AS useridLast,
                    X.sysdate_last     AS sysdateLast
                FROM db1.relasif_ketersediaan AS X
                INNER JOIN (
                    SELECT
                        A.id_depo           AS id_depo,
                        A.id_katalog        AS id_katalog,
                        MAX(A.tgl_tersedia) AS tgl_max
                    FROM db1.relasif_ketersediaan AS A
                    WHERE
                        A.id_depo = :idDepo
                        AND A.tgl_tersedia <= '2016-09-30 23:59:59'
                        AND A.status = 1
                    GROUP BY
                        A.id_depo,
                        A.id_katalog
                ) AS Y ON X.id_depo = Y.id_depo
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE
                    X.id_depo = :idDepo
                    AND X.id_katalog = Y.id_katalog
                    AND X.tgl_tersedia = Y.tgl_max
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog,
                    X.tgl_tersedia
            ";
            $params = [":idDepo" => $idDepo];

        } else {
            // TODO: php: convert date.
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    X.id_katalog                       AS idKatalog,
                    A.nama_barang                      AS namaBarang,
                    B.nama_pabrik                      AS namaPabrik,
                    C.kode                             AS satuan,
                    D.jenis_obat                       AS jenisObat,
                    D.kode_group                       AS kodeJenis,
                    E.kelompok_barang                  AS kelompokBarang,
                    E.kode                             AS kodeKelompok,
                    IFNULL(SUM(X.jumlah_stokfisik), 0) AS jumlah,
                    X.hp_item                          AS hargaPerolehan
                FROM db1.masterf_backupstok_so_close AS X
                LEFT JOIN db1.masterf_katalog AS A ON X.id_katalog = A.kode
                LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
                LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
                LEFT JOIN db1.masterf_jenisobat AS D ON A.id_jenisbarang = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS E ON A.id_kelompokbarang = E.id
                WHERE  X.tgl = '2016-09-30 23:59:59'
                GROUP BY id_katalog
                ORDER BY
                    kodejenis,
                    kodekelompok,
                    X.id_katalog
            ";
            $params = [];
        }
        $daftarData = $connection->createCommand($sql, $params)->queryAll();

        $dataView = [
            "depoJson" => Yii::$app->actionToUrl([DepoController::class, "actionSelect5Data"]),
            "data" => $daftarData,
        ];

        if ($dump == 1) {
            return $this->renderPartial("dump-stok-sept16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($dump == "print") {
            return $this->renderPartial("jumlah-stok-print-sept-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } elseif ($tampilan == 2) {
            return $this->renderPartial("jumlah-stok-kelompok-sept-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file

        } else {
            return $this->renderPartial("jumlah-stok-sept-16-bu", $dataView); // TODO: php: uncategorized: restore deleted file
        }
    }
}
