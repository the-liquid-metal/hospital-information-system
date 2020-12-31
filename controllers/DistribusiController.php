<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\{
    DataAlreadyExistException,
    DataNotExistException,
    FailToInsertException,
    FailToUpdateException,
    FarmasiModel
};
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
class DistribusiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "tanggalDistribusi" => $tanggalDistribusi,
            "noDistribusi" => $noDistribusi,
            "jenisDistribusi" => $jenisDistribusi,
            "noPermintaan" => $noPermintaan,
            "noPenerimaan" => $noPenerimaan,
            "statusPrioritas" => $statusPrioritas,
            "namaDepoPengirim" => $namaDepoPengirim,
            "namaDepoPenerima" => $namaDepoPenerima,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_distribusi AS DST
            LEFT JOIN db1.masterf_depo AS DKRM ON DST.id_pengirim = DKRM.id
            LEFT JOIN db1.masterf_depo AS DTRM ON DST.id_penerima = DTRM.id
            LEFT JOIN db1.user AS UTRM ON DST.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UKRM ON DST.ver_usrkirim = UKRM.id
            LEFT JOIN db1.user AS UINP ON DST.userid_in = UINP.id
            LEFT JOIN db1.masterf_tipedoc AS TDOC ON DST.tipe_doc = TDOC.kode
            WHERE
                DST.sts_deleted = 0
                AND (:tanggalDistribusi = '' OR DST.tgl_doc = :tanggalDistribusi)
                AND (:noDistribusi = '' OR DST.no_doc LIKE :noDistribusi)
                AND (:jenisDistribusi = '' OR TDOC.tipe_doc = :jenisDistribusi)
                AND (:noPermintaan = '' OR DST.no_docmnt LIKE :noPermintaan)
                AND (:noPenerimaan = '' OR DST.no_doctrm LIKE :noPenerimaan)
                AND (:statusPrioritas = '' OR DST.sts_priority = :statusPrioritas)
                AND (:namaDepoPengirim = '' OR DKRM.namaDepo = :namaDepoPengirim)
                AND (:namaDepoPenerima = '' OR DTRM.namaDepo = :namaDepoPenerima)
                AND TDOC.modul = 'distribusi'
        ";
        $params = [
            ":tanggalDistribusi" => $tanggalDistribusi ? $toSystemDate($tanggalDistribusi) : "",
            ":noDistribusi" => $noDistribusi ? "%$noDistribusi%" : "",
            ":jenisDistribusi" => $jenisDistribusi,
            ":noPermintaan" => $noPermintaan ? "%$noPermintaan%" : "",
            ":noPenerimaan" => $noPenerimaan ? "%$noPenerimaan%" : "",
            ":statusPrioritas" => $statusPrioritas,
            ":namaDepoPengirim" => $namaDepoPengirim,
            ":namaDepoPenerima" => $namaDepoPenerima,
        ];
        $jumlahDistribusi = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                DST.kode          AS id,
                DST.kode          AS kode,
                DST.no_doc        AS noDistribusi,
                DST.tgl_doc       AS tanggalDistribusi,
                DST.tipe_doc      AS tipeDistribusi,
                DST.no_docmnt     AS noPermintaan,
                DST.no_doctrm     AS noPenerimaan,
                DST.sts_priority  AS statusPrioritas,
                DST.ver_kirim     AS verKirim,
                DST.ver_terima    AS verTerima,
                DST.ver_tglterima AS verTanggalTerima,
                DST.ver_tglkirim  AS verTanggalKirim,
                DST.sysdate_in    AS inputTime,
                TDOC.tipe_doc     AS jenisDistribusi,
                DST.nilai_akhir   AS nilaiAkhir,
                DKRM.namaDepo     AS namaDepoPengirim,
                DTRM.namaDepo     AS namaDepoPenerima,
                UTRM.name         AS namaUserTerima,
                UKRM.name         AS namaUserKirim,
                UINP.name         AS inputBy
            FROM db1.transaksif_distribusi AS DST
            LEFT JOIN db1.masterf_depo AS DKRM ON DST.id_pengirim = DKRM.id
            LEFT JOIN db1.masterf_depo AS DTRM ON DST.id_penerima = DTRM.id
            LEFT JOIN db1.user AS UTRM ON DST.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UKRM ON DST.ver_usrkirim = UKRM.id
            LEFT JOIN db1.user AS UINP ON DST.userid_in = UINP.id
            LEFT JOIN db1.masterf_tipedoc AS TDOC ON DST.tipe_doc = TDOC.kode
            WHERE
                DST.sts_deleted = 0
                AND (:tanggalDistribusi = '' OR DST.tgl_doc = :tanggalDistribusi)
                AND (:noDistribusi = '' OR DST.no_doc LIKE :noDistribusi)
                AND (:jenisDistribusi = '' OR TDOC.tipe_doc = :jenisDistribusi)
                AND (:noPermintaan = '' OR DST.no_docmnt LIKE :noPermintaan)
                AND (:noPenerimaan = '' OR DST.no_doctrm LIKE :noPenerimaan)
                AND (:statusPrioritas = '' OR DST.sts_priority = :statusPrioritas)
                AND (:namaDepoPengirim = '' OR DKRM.namaDepo = :namaDepoPengirim)
                AND (:namaDepoPenerima = '' OR DTRM.namaDepo = :namaDepoPenerima)
                AND TDOC.modul = 'distribusi'
            ORDER BY DST.tgl_doc DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarDistribusi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "recordsFiltered" => $jumlahDistribusi,
            "data" => $daftarDistribusi
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#addGM    the original method
     */
    public function actionSaveGasMedis(): string
    {
        $toSystemNumber = Yii::$app->number->toSystemNumber();

        $dateTime = Yii::$app->dateTime;
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $nowValSystem = $dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        [   "noDokumen" => $noDokumen,
            "kode" => $kode,
            "idKatalog" => $daftarIdKatalog,
            "tipeDokumen" => $tipeDokumen,
            "tanggalDokumen" => $tanggalDokumen,
            "idPengirim" => $idPengirim,
            "unitPengirim" => $unitPengirim,
            "idPenerima" => $idPenerima,
            "unitPenerima" => $unitPenerima,
            "statusPrioritas" => $statusPrioritas,
            "verifikasiKirim" => $verifikasiKirim,
            "jumlahKemasan" => $daftarJumlahKemasan,
            "isiKemasan" => $daftarIsiKemasan,
            "hpItem" => $daftarHpItem,
            "noBatch" => $daftarNoBatch,
            "idPabrik" => $daftarIdPabrik,
            "idKemasan" => $daftarIdKemasan,
            "idSatuan" => $daftarIdSatuan,
            "hnaItem" => $daftarHnaItem,
            "phjaItem" => $daftarPhjaItem,
            "hjaItem" => $daftarHjaItem,
            "noUrut" => $daftarNoUrut,
            "statusKetersediaan" => $daftarStatusKetersediaan,
        ] = Yii::$app->request->post();
        $isNewData = is_null($kode);

        $dataDistribusi = [
            "no_doc" => $noDokumen,
            "tipe_doc" => $tipeDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "id_pengirim" => $idPengirim,
            "unit_pengirim" => $unitPengirim,
            "id_penerima" => $idPenerima,
            "unit_penerima" => $unitPenerima,
            "sts_priority" => $statusPrioritas,
            "nilai_akhir" => 0, // pending to foreach statement
            "keterangan" => "Pengeluaran Tabung Gas Medis dari $unitPengirim ke $unitPenerima",
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        // set no Transaksi
        if ($isNewData) {
            $counter = $this->getUpdateTrn([
                "initial" => "D",
                "unit" => "0000",
                "subunit" => 15,
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => date("d"),
                "counter" => 1,
                "keterangan" => "Kode Distribusi Bulan " . date("n") . " Tahun " . date("Y"),
                "userid_updt" => $idUser
            ]);

            // in add action, previously, $kode must be NULL
            $kode = "D15" . date("Ymd") . str_pad($counter, 4, "0", STR_PAD_LEFT);
            $dataDistribusi = [
                ...$dataDistribusi,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        if ($verifikasiKirim == 1) {
            $dataDistribusi = [
                ...$dataDistribusi,
                "ver_kirim" => 1,
                "ver_tglkirim" => $nowValSystem,
                "ver_usrkirim" => $idUser,
            ];
        }

        $daftarField = array_keys($dataDistribusi);

        $daftarDetailDistribusi = [];
        $daftarRincianDetailDistribusi = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            $isiKemasan = $toSystemNumber($daftarIsiKemasan[$i]);
            $hargaPokokItem = $toSystemNumber($daftarHpItem[$i]);
            $jumlahItem = $jumlahKemasan * $isiKemasan;
            $dataDistribusi["nilai_akhir"] += $jumlahItem * $hargaPokokItem;
            $noBatch = trim($daftarNoBatch[$i]);
            if (!$jumlahItem || !$noBatch) continue;

            if ($daftarDetailDistribusi[$idKatalog]) {
                $daftarDetailDistribusi[$idKatalog]["jumlah_item"] += $jumlahItem;
                $daftarDetailDistribusi[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;

            } else {
                $daftarDetailDistribusi[$idKatalog] = [
                    "kode_reff" => $kode,
                    "id_katalog" => $idKatalog,
                    "id_pabrik" => $daftarIdPabrik[$n],
                    "isi_kemasan" => $isiKemasan,
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "id_satuan" => $daftarIdSatuan[$n],
                    "jumlah_kemasan" => $jumlahKemasan,
                    "jumlah_item" => $jumlahItem,
                    "hna_item" => $daftarHnaItem[$n],
                    "hp_item" => $daftarHpItem[$n],
                    "phja_item" => $daftarPhjaItem[$n],
                    "hja_item" => $daftarHjaItem[$n]
                ];
                $n++;
            }

            $daftarRincianDetailDistribusi[$k++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_batch" => $noBatch,
                "no_urut" => $daftarNoUrut[$i],
                "sts_ketersediaan" => $daftarStatusKetersediaan[$i],
                "jumlah_kemasan" => $jumlahKemasan,
                "jumlah_item" => $jumlahItem
            ];
        }

        $fm = new FarmasiModel;

        if ($isNewData) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_distribusi
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $adaDistribusi = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaDistribusi) throw new DataAlreadyExistException($kode, $noDokumen, $transaction);

            $berhasilTambah = $fm->saveData("transaksif_distribusi", $daftarField, $dataDistribusi);
            if (!$berhasilTambah) throw new FailToInsertException("Distribusi", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_distribusirinc", $daftarRincianDetailDistribusi);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Distribusi", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_distribusi", $daftarDetailDistribusi);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Distribusi", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_distribusi", $daftarField, $dataDistribusi, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Distribusi", "Kode", $kode, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_distribusirinc", $daftarRincianDetailDistribusi, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Rincian Distribusi", "Kode Ref", $kode, $transaction);

            $berhasilUbah = $fm->saveBatch("tdetailf_distribusi", $daftarDetailDistribusi, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Distribusi", "Kode Ref", $kode, $transaction);
        }

        $transaction->commit();
        return $kode;
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#editGM    the original method
     */
    public function actionEditGasMedisData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode          AS kode,
                A.no_doc        AS noDokumen,
                A.tipe_doc      AS tipeDokumen,
                A.tgl_doc       AS tanggalDokumen,
                A.sts_priority  AS statusPrioritas,
                A.id_pengirim   AS idPengirim,
                A.unit_pengirim AS unitPengirim,
                A.id_penerima   AS idPenerima,
                A.unit_penerima AS unitPenerima,
                A.ver_kirim     AS verifikasiKirim,
                A.ver_tglkirim  AS verTanggalKirim,
                UKRM.name       AS namaUserKirim,
                NULL            AS daftarRincian
            FROM db1.transaksif_distribusi AS A
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UKRM ON A.ver_usrkirim = UKRM.id
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $distribusi = $connection->createCommand($sql, $params)->queryOne();
        if (!$distribusi) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.id_katalog                   AS idKatalog,         -- in use+
                B.id_pabrik                    AS idPabrik,          -- in use+
                B.isi_kemasan                  AS isiKemasan,        -- in use+
                B.id_kemasan                   AS idKemasan,         -- in use+
                B.id_satuan                    AS idSatuan,          -- in use+
                B.jumlah_kemasan               AS jumlahKemasan,     -- in use+
                B.hna_item                     AS hnaItem,           -- in use+
                B.hp_item                      AS hpItem,            -- in use+
                B.phja_item                    AS phjaItem,          -- in use+
                B.hja_item                     AS hjaItem,           -- in use+
                A.no_batch                     AS noBatch,           -- in use+
                A.no_urut                      AS noUrut,            -- in use+
                A.sts_ketersediaan             AS statusKetersediaan,-- in use+
                KAT.nama_sediaan               AS namaSediaan,       -- in use+
                PBK.nama_pabrik                AS namaPabrik,        -- in use+
                IFNULL(A.tgl_expired, '')      AS tanggalKadaluarsa,
                A.kode_reffbatch               AS kodeRefBatch,
                B.kode_reff                    AS kodeRef,
                B.jumlah_item                  AS jumlahItem,
                B.jumlah_itemminta             AS jumlahItemMinta,
                KCIL.kode                      AS kodeKemasanKecil,  -- prev: satuanjual
                KSAR.kode                      AS kodeKemasanBesar,  -- prev: satuan
                C.jumlah_kemasan               AS stokAdm,           -- in use+
                NULL                           AS katalogOpt,        -- in use+
                NULL                           AS batchOpt           -- in use+
            FROM db1.tdetailf_distribusirinc AS A
            LEFT JOIN db1.tdetailf_distribusi AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = KAT.id_kemasanbesar
            LEFT JOIN (
                SELECT
                    id_unitposisi                  AS id_unitposisi,
                    id_katalog                     AS id_katalog,
                    IFNULL(COUNT(C.id_katalog), 0) AS jumlah_kemasan
                FROM db1.transaksif_seritabung AS C
                WHERE
                    C.id_unitposisi = :idUnitPosisi
                    AND C.sts_tersedia = 1
                    AND C.sts_aktif = 1
                GROUP BY id_unitposisi, id_katalog
            ) AS C ON A.id_katalog = C.id_katalog
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kode" => $kode, ":idUnitPosisi" => 60];
        $daftarRincianDetailDistribusi = $connection->createCommand($sql, $params)->queryAll();
        $distribusi->daftarRincian = $daftarRincianDetailDistribusi;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                     AS idKatalog,
                A.id_unitposisi                                  AS idUnit,
                A.no_batch                                       AS noBatch,
                A.tgl_expired                                    AS tanggalKadaluarsa,
                1                                                AS jumlahFisik,
                1                                                AS jumlahAdm,
                IF(A.kd_unitpemilik = 0, D.namaDepo, C.nama_pbf) AS unitPemilik,
                C.nama_pbf                                       AS namaPemasok,
                D.namaDepo                                       AS namaDepo,
                A.kd_unitpemilik                                 AS kodeUnitPemilik
            FROM db1.transaksif_seritabung AS A
            LEFT JOIN db1.masterf_pbf AS C ON A.id_unitpemilik = C.id
            LEFT JOIN db1.masterf_depo AS D ON A.id_unitpemilik = D.id
            WHERE
                A.id_unitposisi = :idUnitPosisi
                AND A.sts_aktif = 1
                AND A.sts_tersedia = 1
                AND A.id_katalog IN (
                    SELECT id_katalog
                    FROM db1.tdetailf_distribusi
                    WHERE kode_reff = :kode
                )
        ";
        $params = [":kode" => $kode, ":idUnitPosisi" => 60];
        $daftarSeriTabung = $connection->createCommand($sql, $params)->queryAll();

        $daftarSeriTabung2 = [];
        foreach ($daftarSeriTabung as $i => $seriTabung) {
            $daftarSeriTabung2[$seriTabung->idKatalog] = $seriTabung;
        }

        foreach ($daftarRincianDetailDistribusi as $i => $rincian) {
            $rincian->batchOpt = $daftarSeriTabung2[$rincian->idKatalog];
        }

        return json_encode($distribusi);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws DataNotExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#returGM    the original method
     */
    public function actionSaveReturGasMedis(): string
    {
        [   "action" => $action,
            "kode" => $kode,
            "no_doc" => $noDokumen,
            "tipe_doc" => $tipeDokumen,
            "tgl_doc" => $tanggalDokumen,
            "id_pengirim" => $idPengirim,
            "unit_pengirim" => $unitPengirim,
            "id_penerima" => $idPenerima,
            "unit_penerima" => $unitPenerima,
            "sts_priority" => $statusPrioritas,
            "nilai_akhir" => $nilaiAkhir,
            "ver_kirim" => $verKirim,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "no_batch" => $daftarNoBatch,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_satuan" => $daftarIdSatuan,
            "hna_item" => $daftarHnaItem,
            "hp_item" => $daftarHpItem,
            "phja_item" => $daftarPhjaItem,
            "hja_item" => $daftarHjaItem,
            "no_urut" => $daftarNoUrut,
            "sts_ketersediaan" => $daftarStatusKetersediaan,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $dateTime = Yii::$app->dateTime;
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $nowValSystem = $dateTime->nowVal("system");

        $dataDistribusi = [
            "no_doc" => $noDokumen,
            "tipe_doc" => $tipeDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "id_pengirim" => $idPengirim,
            "unit_pengirim" => $unitPengirim,
            "id_penerima" => $idPenerima,
            "unit_penerima" => $unitPenerima,
            "sts_priority" => $statusPrioritas,
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
            "keterangan" => "Pengembalian Tabung Gas Medis dari $unitPenerima ke $unitPengirim",
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        // set no Transaksi
        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "D",
                "unit" => "0000",
                "subunit" => 15,
                "kode" => date("Y"),
                "subkode" => date("m"),
                "detailkode" => date("d"),
                "counter" => 1,
                "keterangan" => "Kode Distribusi Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUser
            ]);
            $kode = "D15" . date("Ym") . date("d") . str_pad($counter, 4, "0", STR_PAD_LEFT);

            $dataDistribusi = [
                ...$dataDistribusi,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];

        } elseif ($action == "edit") {
            $dataDistribusi = [
                ...$dataDistribusi,
                "userid_updt" => $idUser,
                "sysdate_updt" => $nowValSystem,
            ];
        }

        if ($verKirim == 1) {
            $dataDistribusi = [
                ...$dataDistribusi,
                "ver_kirim" => 1,
                "ver_tglkirim" => $nowValSystem,
                "ver_usrkirim" => $idUser,
            ];
        }

        $daftarField = array_keys($dataDistribusi);

        $dataDetailDistribusi = [];
        $dataRincianDetailDistribusi = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            $noBatch = trim($daftarNoBatch[$i]);
            if (!$jumlahItem || !$noBatch) continue;

            if ($dataDetailDistribusi[$idKatalog]) {
                $dataDetailDistribusi[$idKatalog]["jumlah_item"] += $jumlahItem;
                $dataDetailDistribusi[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;

            } else {
                $dataDetailDistribusi[$idKatalog] = [
                    "kode_reff" => $kode,
                    "id_katalog" => $idKatalog,
                    "id_pabrik" => $daftarIdPabrik[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $daftarIsiKemasan[$n],
                    "id_satuan" => $daftarIdSatuan[$n],
                    "jumlah_item" => $jumlahItem,
                    "jumlah_kemasan" => $jumlahKemasan,
                    "hna_item" => $daftarHnaItem[$n],
                    "hp_item" => $daftarHpItem[$n],
                    "phja_item" => $daftarPhjaItem[$n],
                    "hja_item" => $daftarHjaItem[$n]
                ];
                $n++;
            }

            $dataRincianDetailDistribusi[$k ++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_batch" => $noBatch,
                "no_urut" => $daftarNoUrut[$i],
                "sts_ketersediaan" => $daftarStatusKetersediaan[$i],
                "jumlah_item" => $jumlahItem,
                "jumlah_kemasan" => $jumlahKemasan
            ];
        }

        $fm = new FarmasiModel;

        if ($action == "add") {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_distribusi
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $adaDistribusi = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaDistribusi) throw new DataAlreadyExistException($kode, $noDokumen);

            $berhasilTambah = $fm->saveData("transaksif_distribusi", $daftarField, $dataDistribusi);
            if (!$berhasilTambah) throw new FailToInsertException("Distribusi");

            $berhasilTambah = $fm->saveBatch("tdetailf_distribusirinc", $dataRincianDetailDistribusi);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Distribusi");

            $berhasilTambah = $fm->saveBatch("tdetailf_distribusi", $dataDetailDistribusi);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Distribusi");

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_distribusi", $daftarField, $dataDistribusi, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Distribusi", "Kode", $kode);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_distribusirinc", $dataRincianDetailDistribusi, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Rincian Distribusi", "Kode Reff", $kode);

            $berhasilUbah = $fm->saveBatch("tdetailf_distribusi", $dataDetailDistribusi, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Distribusi", "Kode Reff", $kode);
        }
        assert($verKirim == 1, new MissingPostParamException("ver_kirim"));
        $this->doReturnGm($_POST);
        return $kode;
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#editReturGM    the original method
     */
    public function actionEditReturGasMedisData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                 AS kode,
                A.no_doc               AS noDokumen,
                A.tipe_doc             AS tipeDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.kode_reffmnt         AS kodeRefMinta,
                A.no_docmnt            AS noDokumenMinta,
                A.kode_refftrm         AS kodeRefTerima,
                A.no_doctrm            AS noDokumenTerima,
                A.tgl_doctrm           AS tanggalDokumenTerima,
                A.sts_priority         AS statusPrioritas,
                A.id_pengirim          AS idPengirim,           -- in use
                A.unit_pengirim        AS unitPengirim,
                A.id_penerima          AS idPenerima,
                A.unit_penerima        AS unitPenerima,
                A.nilai_akhir          AS nilaiAkhir,
                A.ver_kirim            AS verKirim,             -- in use
                A.ver_tglkirim         AS verTanggalKirim,
                A.ver_usrkirim         AS verUserKirim,
                A.ver_terima           AS verTerima,            -- in use
                A.ver_tglterima        AS verTanggalTerima,
                A.ver_usrterima        AS verUserTerima,
                A.sts_deleted          AS statusDeleted,
                A.sysdate_del          AS sysdateDeleted,
                A.keterangan           AS keterangan,
                A.userid_in            AS useridInput,
                A.sysdate_in           AS sysdateInput,
                A.userid_updt          AS useridUpdate,
                A.sysdate_updt         AS sysdateUpdate,
                IFNULL(UTRM.name, '-') AS namaUserTerima,       -- in use
                IFNULL(UKRM.name, '-') AS namaUserKirim,
                ''                     AS namaUserGudang        -- in use
            FROM db1.transaksif_distribusi AS A
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UKRM ON A.ver_usrkirim = UKRM.id
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $distribusi = $connection->createCommand($sql, $params)->queryOne();
        if (!$distribusi) throw new DataNotExistException($kode);

        // TODO: php: uncategorized: move logic to view
        $distribusi2 = $distribusi->getArrayCopy();
        $distribusi2["action"] = "edit";

        if ($distribusi->verKirim != 1) {
            $distribusi2["stokgudang"] = "";
            $distribusi2["verkirim"] = "";
            $distribusi->namaUserTerima = "------";
        } else {
            $distribusi2["stokgudang"] = "checked";
            $distribusi2["verkirim"] = "checked";
        }

        if ($distribusi->verTerima != 1) {
            $distribusi->namaUserGudang = "------";
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.id_pabrik                  AS idPabrik,
                B.isi_kemasan                AS isiKemasan,
                B.id_kemasan                 AS idKemasan,
                B.id_satuan                  AS idSatuan,
                B.jumlah_itemminta           AS jumlahItemMinta,
                B.jumlah_kemasan             AS jumlahKemasan,
                B.jumlah_item                AS jumlahItem,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.phja_item                  AS phjaItem,
                B.hja_item                   AS hjaItem,
                A.kode_reff                  AS kodeRef,
                A.id_katalog                 AS idKatalog,
                A.kode_reffbatch             AS kodeRefBatch,
                A.no_batch                   AS noBatch,
                IFNULL(A.tgl_expired, '')    AS tanggalKadaluarsa,
                A.no_urut                    AS noUrut,
                A.sts_ketersediaan           AS statusKetersediaan,
                A.jumlah_kemasan             AS jumlahKemasan,
                A.jumlah_item                AS jumlahItem,
                KAT.nama_sediaan             AS namaSediaan,
                PBK.nama_pabrik              AS namaPabrik,
                KCIL.kode                    AS satuanJual,
                KSAR.kode                    AS satuan,
                IFNULL(C.jumlah_kemasan, 0)  AS stokAdm
            FROM db1.tdetailf_distribusirinc AS A
            LEFT JOIN db1.tdetailf_distribusi AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.transaksif_distribusi AS T ON A.kode_reff = T.kode
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = KAT.id_kemasanbesar
            LEFT JOIN (
                SELECT
                    id_unitposisi                  AS id_unitposisi,
                    id_katalog                     AS id_katalog,
                    IFNULL(COUNT(C.id_katalog), 0) AS jumlah_kemasan
                FROM db1.transaksif_seritabung AS C
                WHERE C.sts_aktif = 1
                GROUP BY id_unitposisi, id_katalog
            ) AS C ON A.id_katalog = C.id_katalog
            WHERE
                A.kode_reff = :kode
                AND C.id_unitposisi = T.id_pengirim
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kode" => $kode];
        $daftarRincianDetailDistribusi = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                     AS idKatalog,
                A.id_unitposisi                                  AS idUnit,
                A.no_batch                                       AS noBatch,
                A.tgl_expired                                    AS tanggalKadaluarsa,
                1                                                AS jumlahFisik,
                1                                                AS jumlahAdm,
                IF(A.kd_unitpemilik = 0, D.namaDepo, C.nama_pbf) AS unitPemilik,
                C.nama_pbf                                       AS namaPemasok,
                D.namaDepo                                       AS namaDepo,
                A.kd_unitpemilik                                 AS kodeUnitPemilik
            FROM db1.transaksif_seritabung AS A
            LEFT JOIN db1.masterf_pbf AS C ON A.id_unitpemilik = C.id
            LEFT JOIN db1.masterf_depo AS D ON A.id_unitpemilik = D.id
            WHERE
                A.id_unitposisi = :idUnitPosisi
                AND A.sts_aktif = 1
                AND A.id_katalog IN (
                    SELECT id_katalog
                    FROM db1.tdetailf_distribusi
                    WHERE kode_reff = :kode
                )
        ";
        $params = [":kode" => $kode, ":idUnitPosisi" => $distribusi->idPengirim];
        $daftarSeriTabung = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "distribusi" => $distribusi,
            "distribusi2" => $distribusi2,
            "daftarRincianDetailDistribusi" => $daftarRincianDetailDistribusi,
            "daftarSeriTabung" => $daftarSeriTabung,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#views    the original method
     */
    public function actionViewData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kodeDistribusi,
                A.no_doc               AS noDokumenDistribusi,
                A.tgl_doc              AS tanggalDokumenDistribusi,
                A.sts_priority         AS statusPrioritas,
                A.unit_pengirim        AS unitPengirim,
                A.unit_penerima        AS unitPenerima,
                A.ver_kirim            AS verifikasiKirim,
                IFNULL(UKRM.name, '-') AS namaUserKirim,
                ver_tglkirim           AS verTanggalKirim,
                T.tipe_doc             AS jenisPengiriman,
                A.tipe_doc             AS tipe
            FROM db1.transaksif_distribusi AS A
            LEFT JOIN db1.user AS UKRM ON A.ver_usrkirim = UKRM.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.masterf_tipedoc AS T ON A.tipe_doc = T.kode
            WHERE
                A.kode = :kode
                AND T.modul = 'distribusi'
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $distribusi = $connection->createCommand($sql, $params)->queryOne();
        if (!$distribusi) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog       AS idKatalog,
                A.jumlah_kemasan   AS jumlahKemasan,
                A.jumlah_item      AS jumlahItem,
                A.hp_item          AS hpItem,
                KAT.nama_sediaan   AS namaSediaan,
                PBK.nama_pabrik    AS namaPabrik,
                KSAR.kode          AS kodeSatuanBesar,
                T.item_tersedia    AS itemTersedia
            FROM db1.tdetailf_distribusi AS A
            LEFT JOIN (
                SELECT
                    kode_reff,
                    id_katalog,
                    SUM(jumlah_kemasan) AS kemasan_tersedia,
                    SUM(jumlah_item)    AS item_tersedia
                FROM db1.tdetailf_distribusirinc
                WHERE sts_ketersediaan = 1
                GROUP BY kode_reff, id_katalog
            ) AS T ON A.kode_reff = T.kode_reff
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = KAT.id_kemasanbesar
            LEFT JOIN db1.transaksif_stokkatalog AS C ON A.id_katalog = C.id_katalog
            WHERE
                A.kode_reff = :kode
                AND C.id_depo = 60
                AND A.id_katalog = T.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $distribusi->daftarDetailDistribusi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($distribusi);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#views    the original method
     */
    public function actionViewDetailData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kodeDistribusi,
                A.no_doc               AS noDokumenDistribusi,
                A.tgl_doc              AS tanggalDokumenDistribusi,
                A.sts_priority         AS statusPrioritas,
                A.unit_pengirim        AS unitPengirim,
                A.unit_penerima        AS unitPenerima,
                A.ver_kirim            AS verifikasiKirim,
                IFNULL(UKRM.name, '-') AS namaUserKirim,
                ver_tglkirim           AS verTanggalKirim,
                T.tipe_doc             AS jenisPengiriman,
                A.tipe_doc             AS tipe
            FROM db1.transaksif_distribusi AS A
            LEFT JOIN db1.user AS UKRM ON A.ver_usrkirim = UKRM.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.masterf_tipedoc AS T ON A.tipe_doc = T.kode
            WHERE
                A.kode = :kode
                AND T.modul = 'distribusi'
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $distribusi = $connection->createCommand($sql, $params)->queryOne();
        if (!$distribusi) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog       AS idKatalog,
                A.no_batch         AS noBatch,
                A.tgl_expired      AS tanggalKadaluarsa,
                A.no_urut          AS noUrut,
                A.sts_ketersediaan AS statusKetersediaan,
                A.jumlah_kemasan   AS jumlahKemasan,
                A.jumlah_item      AS jumlahItem,
                B.hp_item          AS hpItem,
                KAT.nama_sediaan   AS namaSediaan,
                PBK.nama_pabrik    AS namaPabrik,
                KSAR.kode          AS kodeSatuanBesar
            FROM db1.tdetailf_distribusirinc AS A
            LEFT JOIN db1.tdetailf_distribusi AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = KAT.id_kemasanbesar
            LEFT JOIN db1.transaksif_stokkatalog AS C ON A.id_katalog = C.id_katalog
            WHERE
                A.kode_reff = :kode
                AND C.id_depo = 60
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, A.no_urut
        ";
        $params = [":kode" => $kode];
        $distribusi->daftarDetailDistribusi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($distribusi);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-1d9012b
     */
    public function actionSearchJsonUnit(): string
    {
        $namaDepo = Yii::$app->request->post("namaDepo");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode       AS kode,
                id         AS id,
                namaDepo   AS namaDepo,
                keterangan AS keterangan,
                lokasiDepo AS lokasiDepo
            FROM db1.masterf_depo
            WHERE
                namaDepo LIKE :namaDepo
                AND sts_aktif = 1
            LIMIT 30
        ";
        $params = [":namaDepo" => "%$namaDepo%"];
        $daftarDepo = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarDepo);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-1d9012b
     */
    public function actionSearchJsonBatch(): string
    {
        [
            "no_batch" => $noBatch,
            "id_katalog" => $idKatalog,
            "id_unitposisi" => $idUnitPosisi,
            "sts_aktif" => $stsAktif
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                       AS idKatalog,
                A.no_batch                                         AS noBatch,
                1                                                  AS kemasanAdm,
                1                                                  AS jumlahAdm,
                A.tgl_expired                                      AS tanggalKadaluarsa,
                IF(A.kd_unitpemilik = 1, C.nama_pbf, D.namaDepo) AS unitPemilik,
                K.nama_sediaan                                     AS namaSediaan,
                A.sts_tersedia                                     AS statusTersedia
            FROM db1.transaksif_seritabung AS A
            LEFT JOIN db1.masterf_katalog AS K ON A.id_katalog = K.kode
            LEFT JOIN db1.masterf_pbf AS C ON A.id_unitpemilik = C.id
            LEFT JOIN db1.masterf_depo AS D ON A.id_unitpemilik = D.id
            WHERE
                (:noBatch = '' OR A.no_batch LIKE :noBatch)
                AND (:idKatalog = '' OR A.id_katalog = :idKatalog)
                AND (:idUnitPosisi = '' OR A.id_unitposisi = :idUnitPosisi)
                AND (:statusAktif = '' OR A.sts_aktif = :statusAktif)
            LIMIT 30
        ";
        $params = [
            ":noBatch" => $noBatch ? "%$noBatch%" : "",
            ":idKatalog" => $idKatalog,
            ":idUnitPosisi" => $idUnitPosisi,
            ":statusAktif" => $stsAktif,
        ];
        $daftarSeriTabung = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarSeriTabung);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-1d9012b
     */
    public function actionSearchJsonTabungReturn(): string
    {
        ["statusTersedia" => $statusTersedia, "query" => $query, "idUnitPosisi" => $idUnitPosisi] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode            AS idKatalog,
                A.kemasan         AS kemasan,
                A.nama_sediaan    AS namaSediaan,
                A.id_pabrik       AS idPabrik,
                A.id_kemasanbesar AS idKemasan,
                A.isi_kemasan     AS isiKemasan,
                A.id_kemasankecil AS idSatuan,
                B.nama_pabrik     AS namaPabrik,
                C.kode            AS satuan,
                G.kode            AS satuanJual,
                D.jumlah_kemasan  AS stokAdm
            FROM db1.masterf_katalog AS A
            LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
            LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
            LEFT JOIN db1.masterf_kemasan AS G ON A.id_kemasanbesar = G.id
            LEFT JOIN (
                SELECT
                    A.id_katalog                   AS id_katalog,
                    A.kd_unitposisi                AS kd_unitposisi,
                    A.id_unitposisi                AS id_unitposisi,
                    IFNULL(COUNT(A.id_katalog), 0) AS jumlah_kemasan
                FROM db1.transaksif_seritabung AS A
                WHERE
                    A.sts_aktif = 1
                    AND A.kd_unitposisi = 0
                    AND A.id_unitposisi = :idUnitPosisi
                    AND (:statusTersedia = '' OR A.sts_tersedia = :statusTersedia)
                GROUP BY
                    A.kd_unitposisi,
                    A.id_unitposisi,
                    A.id_katalog
            ) AS D ON A.kode = D.id_katalog
            LEFT JOIN db1.relasif_hargaperolehan AS E ON A.kode = E.id_katalog
            WHERE
                (A.nama_sediaan LIKE :query OR A.kode LIKE :query)
                AND A.id_jenisbarang = 6
                AND A.sts_aktif = 1
                AND D.id_unitposisi = :idUnitPosisi
                AND E.sts_hja = 1
            LIMIT 30
        ";
        $params = [":query" => "%$query%", ":idUnitPosisi" => $idUnitPosisi, ":statusTersedia" => $statusTersedia];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKatalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-1d9012b
     */
    public function actionSearchJsonCheckStock(): string
    {
        ["id_katalog" => $idKatalog, "id_unit" => $idUnit] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                     AS idKatalog,
                A.id_unit                                        AS idUnit,
                A.no_batch                                       AS noBatch,
                A.tgl_expired                                    AS tanggalKadaluarsa,
                A.jumlah_fisik                                   AS jumlahFisik,
                A.kemasan_fisik                                  AS kemasanFisik,
                A.kemasan_adm                                    AS kemasanAdm,
                A.jumlah_adm                                     AS jumlahAdm,
                A.sts_pengembalian                               AS statusPengembalian,
                A.sts_ketersediaan                               AS statusKetersediaan,
                IF(B.kd_unitpemilik = 1, C.nama_pbf, D.namaDepo) AS unitPemilik
            FROM rsupf_latihan.transaksif_stokkatalogrinc AS A
            LEFT JOIN rsupf_latihan.masterf_batchtabung AS B ON A.id_katalog = B.id_katalog
            LEFT JOIN db1.masterf_pbf AS C ON B.id_unitpemilik = C.id
            LEFT JOIN db1.masterf_depo AS D ON B.id_unitpemilik = D.id
            WHERE
                A.id_katalog = :idKatalog
                AND A.id_unit = :idUnit
                AND A.sts_aktif = 1
                AND A.sts_pengembalian = 0
                AND B.id_katalog = :idKatalog
                AND B.sts_aktif = 1
                AND A.no_batch = B.no_batch
        ";
        $params = [":idKatalog" => $idKatalog, ":idUnit" => $idUnit];
        $daftarRincianStokKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarRincianStokKatalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-1d9012b
     */
    public function actionSearchJsonBatchTabung(): string
    {
        ["sts_tersedia" => $statusTersedia, "id_katalog" => $idKatalog, "id_unitposisi" => $idUnitPosisi] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                     AS idKatalog,
                A.id_unitposisi                                  AS idUnitPosisi,
                A.no_batch                                       AS noBatch,
                A.tgl_expired                                    AS tanggalKadaluarsa,
                1                                                AS jumlahFisik,
                1                                                AS jumlahAdm,
                IF(A.kd_unitpemilik = 1, C.nama_pbf, D.namaDepo) AS unitPemilik,
                C.nama_pbf                                       AS namaPemasok,
                D.namaDepo                                       AS namaDepo,
                A.kd_unitpemilik                                 AS kodeUnitPemilik
            FROM db1.transaksif_seritabung AS A
            LEFT JOIN db1.masterf_pbf AS C ON A.id_unitpemilik = C.id
            LEFT JOIN db1.masterf_depo AS D ON A.id_unitpemilik = D.id
            WHERE
                A.id_katalog = :idKatalog
                AND A.id_unitposisi = :idUnitPosisi
                AND A.kd_unitposisi = 0
                AND A.sts_aktif = 1
                AND (:statusTersedia = '' OR A.sts_tersedia = :statusTersedia)
        ";
        $params = [":idKatalog" => $idKatalog, ":idUnitPosisi" => $idUnitPosisi, ":statusTersedia" => $statusTersedia];
        $daftarSeriTabung = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarSeriTabung);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-1d9012b
     */
    public function actionSearchJsonKatalogDist(): string
    {
        ["query" => $val, "idDepo" => $idDepo] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode            AS idKatalog,
                A.nama_sediaan    AS namaSediaan,
                A.id_pabrik       AS idPabrik,
                A.id_kemasanbesar AS idKemasan,
                A.isi_kemasan     AS isiKemasan,
                A.id_kemasankecil AS idSatuan,
                B.nama_pabrik     AS namaPabrik,
                C.kode            AS satuan,
                G.kode            AS satuanJual,
                D.jumlah_kemasan  AS stokAdm,
                D.id_unitposisi   AS idUnit,
                E.hp_item         AS hpItem,
                E.hna_item        AS hnaItem,
                E.phja            AS phjaItem,
                E.hja_item        AS hjaItem
            FROM db1.masterf_katalog AS A
            LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
            LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
            LEFT JOIN db1.masterf_kemasan AS G ON A.id_kemasanbesar = G.id
            INNER JOIN (
                SELECT
                    A.id_katalog,
                    A.kd_unitposisi,
                    A.id_unitposisi,
                    IFNULL(COUNT(A.id_katalog), 0) jumlah_kemasan
                FROM db1.transaksif_seritabung AS A
                WHERE
                    A.sts_aktif = 1
                    AND A.sts_tersedia = 1
                    AND A.kd_unitposisi = 0
                    AND A.id_unitposisi = :idUnitPosisi
                GROUP BY
                    A.kd_unitposisi,
                    A.id_unitposisi,
                    A.id_katalog
            ) AS D ON A.kode = D.id_katalog
            LEFT JOIN db1.relasif_hargaperolehan AS E ON A.kode = E.id_katalog
            WHERE
                A.id_jenisbarang = 6
                AND A.sts_aktif = 1
                AND (A.nama_sediaan LIKE :val OR A.kode LIKE :val)
                AND D.id_unitposisi = :idUnitPosisi
                AND D.kd_unitposisi = 0
                AND E.sts_hja = 1
            LIMIT 30
        ";
        $params = [":val" => "%$val%", ":idUnitPosisi" => $idDepo];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKatalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#ajaxDelete    the original method
     */
    public function actionAjaxDelete(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        ["keterangan" => $keterangan, "kode" => $kode] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_distribusi
            SET
                no_doc = kode,
                keterangan = CONCAT('Hapus Distribusi Gas Medis dengan No: ', :keterangan),
                sts_deleted = 1,
                sysdate_del = :tanggalHapus
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":keterangan" => $keterangan, ":tanggalHapus" => $nowValSystem, ":kode" => $kode];
        $berhasilHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws FailToInsertException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#doReturnGM the original method
     */
    private function doReturnGm(array $data): void
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;
        $tanggal = $toSystemDate($data["ver_tglkirim"]);
        $bulan = date("m", strtotime($data["ver_tglkirim"]));
        $tahun = date("Y", strtotime($data["ver_tglkirim"]));

        // dapatkan semua barang yang diterima
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff        AS kodeRef,
                A.id_katalog       AS idKatalog,
                A.no_batch         AS noBatch,
                A.tgl_expired      AS tanggalKadaluarsa,
                A.sts_ketersediaan AS statusKetersediaan,
                A.jumlah_item      AS jumlahItem,
                A.jumlah_kemasan   AS jumlahKemasan,
                B.isi_kemasan      AS isiKemasan,
                B.id_kemasan       AS idKemasan,
                B.id_satuan        AS idSatuan,
                C.id_pengirim      AS idPengirim,
                C.ver_usrkirim     AS verUserKirim,
                C.ver_tglkirim     AS verTanggalKirim,
                C.keterangan       AS keterangan,
                C.userid_updt      AS useridUpdate,
                C.sysdate_updt     AS sysdateUpdate,
                C.no_doc           AS noDokumen,
                C.unit_pengirim    AS unitPengirim,
                C.id_penerima      AS idPenerima,
                C.unit_penerima    AS unitPenerima,
                C.ver_kirim        AS verKirim,
                D.kode             AS kodeSo,
                D.tgl_adm          AS tanggalAdm,
                E.nama_sediaan     AS namaSediaan,
                F.kode             AS kodePenerima,
                G.kode             AS kodePengirim
            FROM db1.tdetailf_distribusirinc AS A
            LEFT JOIN db1.tdetailf_distribusi AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.transaksif_distribusi AS C ON A.kode_reff = C.kode
            LEFT JOIN db1.transaksif_stokopname AS D ON C.id_pengirim = D.id_depo
            LEFT JOIN db1.masterf_katalog AS E ON A.id_katalog = E.kode
            LEFT JOIN db1.masterf_depo AS F ON C.id_penerima = F.id
            LEFT JOIN db1.masterf_depo AS G ON C.id_pengirim = G.id
            WHERE
                A.kode_reff = :kodeRef
                AND C.ver_kirim = 1
                AND D.sts_aktif = 1
                AND A.id_katalog = B.id_katalog
            ORDER BY
                A.id_katalog ASC,
                A.no_urut
        ";
        $params = [":kodeRef" => $data["kode"]];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarKatalog) throw new DataNotExistException($data["kode"]);

        foreach ($daftarKatalog as $katalog) {
            $oIdKatalog = $katalog->idKatalog;
            $oTanggalKadaluarsa = $katalog->tanggalKadaluarsa;
            $oIsiKemasan = $katalog->isiKemasan;
            $oIdKemasan = $katalog->idKemasan;
            $oIdSatuan = $katalog->idSatuan;
            $oKeterangan = $katalog->keterangan;
            $oStatusKetersediaan = $katalog->statusKetersediaan;
            $oIdPengirim = $katalog->idPengirim;
            $oNoBatch = $katalog->noBatch;
            $oKodeReferensi = $katalog->kodeRef;
            $oIdPenerima = $katalog->idPenerima;
            $oJumlahItem = $katalog->jumlahItem;
            $oVerifikasiUserPengirim = $katalog->verUserKirim;
            $oVerifikasiTangalKirim = $katalog->verTanggalKirim;

            // Lakukan hanya jika mengirimkan tabung isi, sehingga memotong stok
            if ($oStatusKetersediaan == 1) {

                // insert ke kartu kalau barang sudah dikirim, hanya tabung yang berisi yang memotong stok
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan (
                        id_depo,          -- 1
                        kode_reff,        -- 2
                        no_doc,           -- 3
                        kode_stokopname,  -- 4
                        tgl_adm,          -- 5
                        tgl_transaksi,    -- 6
                        bln_transaksi,    -- 7
                        thn_transaksi,    -- 8
                        kode_transaksi,   -- 9
                        tipe_tersedia,    -- 10
                        tgl_tersedia,     -- 11
                        no_batch,         -- 12
                        tgl_expired,      -- 13
                        id_katalog,       -- 14
                        jumlah_sebelum,   -- 15
                        jumlah_masuk,     -- 16
                        jumlah_keluar,    -- 17
                        jumlah_tersedia,  -- 18
                        harga_netoapotik, -- 19
                        harga_perolehan,  -- 20
                        phja,             -- 21
                        harga_jualapotik, -- 22
                        status,           -- 23
                        keterangan,       -- 24
                        userid_last,      -- 25
                        sysdate_last,     -- 26
                        id_reff,          -- not exist in original source (error supressor)
                        id_pabrik,        -- idem
                        id_kemasan        -- idem
                    )
                    SELECT
                        C.id_penerima,                    -- 1
                        A.kode_reff,                      -- 2
                        C.no_doc,                         -- 3
                        D.kode,                           -- 4
                        D.tgl_adm,                        -- 5
                        :tanggal,                         -- 6
                        :bulan,                           -- 7
                        :tahun,                           -- 8
                        'D',                              -- 9
                        'distribusi',                     -- 10
                        C.ver_tglkirim,                   -- 11
                        A.no_batch,                       -- 12
                        A.tgl_expired,                    -- 13
                        A.id_katalog,                     -- 14
                        E.jumlah_stokadm,                 -- 15
                        A.jumlah_item,                    -- 16
                        0,                                -- 17
                        E.jumlah_stokadm + A.jumlah_item, -- 18
                        B.hna_item,                       -- 19
                        B.hp_item,                        -- 20
                        B.phja_item,                      -- 21
                        B.hja_item,                       -- 22
                        C.ver_kirim,                      -- 23
                        C.keterangan,                     -- 24
                        C.userid_updt,                    -- 25
                        C.sysdate_updt,                   -- 26
                        '',                               -- not exist in original source (error supressor)
                        '',                               -- idem
                        ''                                -- idem
                    FROM db1.tdetailf_distribusirinc AS A
                    LEFT JOIN db1.tdetailf_distribusi AS B ON A.kode_reff = B.kode_reff
                    LEFT JOIN db1.transaksif_distribusi AS C ON A.kode_reff = C.kode
                    LEFT JOIN db1.transaksif_stokopname AS D ON C.id_pengirim = D.id_depo
                    LEFT JOIN db1.transaksif_stokkatalog AS E ON C.id_pengirim = E.id_depo
                    WHERE
                        A.kode_reff = :kodeRef
                        AND A.sts_ketersediaan = 1
                        AND C.sts_deleted = 0
                        AND A.id_katalog = :idKatalog
                        AND A.no_batch = :noBatch
                        AND A.tgl_expired = :tanggalKadaluarsa
                        AND A.id_katalog = E.id_katalog
                        AND D.sts_aktif = 1
                        AND A.id_katalog = B.id_katalog
                ";
                $params = [
                    ":tanggal" => $tanggal,
                    ":bulan" => $bulan,
                    ":tahun" => $tahun,
                    ":kodeRef" => $oKodeReferensi,
                    ":idKatalog" => $oIdKatalog,
                    ":noBatch" => $oNoBatch,
                    ":tanggalKadaluarsa" => $oTanggalKadaluarsa,
                ];
                $berhasilTambah = $connection->createCommand($sql, $params)->execute();
                if (!$berhasilTambah) throw new FailToInsertException("Ketersediaan");

                // jika sukses mengupdate kartu, update stokkatalog
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.transaksif_stokkatalog
                    SET
                        id_depo = :idDepo,
                        id_katalog = :idKatalog,
                        jumlah_stokfisik = :jumlahItem,
                        jumlah_stokadm = :jumlahStokAdm,
                        status = 1,
                        userid_in = :idUserInput,
                        sysdate_in = :tanggalInput,
                        userid_updt = :idUserInput,
                        sysdate_updt = :tanggalInput,
                        keterangan = :keterangan
                    ON DUPLICATE KEY UPDATE
                        jumlah_stokfisik = jumlah_stokfisik + :jumlahItem,
                        jumlah_stokadm = jumlah_stokadm + :jumlahItem,
                        userid_updt = :idUserInput,
                        sysdate_updt = :tanggalInput,
                        keterangan = :keterangan
                ";
                $params = [
                    ":idDepo" => $oIdPenerima,
                    ":idKatalog" => $oIdKatalog,
                    ":jumlahItem" => $oJumlahItem,
                    ":jumlahStokAdm" => $oJumlahItem,
                    ":idUser" => $oVerifikasiUserPengirim,
                    ":tanggalInput" => $oVerifikasiTangalKirim,
                    ":keterangan" => $oKeterangan,
                ];
                $berhasilTambah = $connection->createCommand($sql, $params)->execute();
                if (!$berhasilTambah) throw new FailToInsertException("Stok Katalog");

                // jika sudah update stok, insert/update juga ke stokkatalogrinci
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT IGNORE INTO db1.transaksif_kartugasmedis (
                        id,              -- 1
                        id_katalog,      -- 2
                        no_batch,        -- 3
                        kode_reff,       -- 4
                        no_doc,          -- 5
                        tipe_doc,        -- 6
                        kd_pengirim,     -- 7
                        id_pengirim,     -- 8
                        kd_penerima,     -- 9
                        id_penerima,     -- 10
                        tgl_expired,     -- 11
                        jumlah_masuk,    -- 12
                        jumlah_keluar,   -- 13
                        jumlah_tersedia, -- 14
                        sts_tersedia,    -- 15
                        keterangan,      -- 16
                        sts_transaksi,   -- 17
                        userid_in,       -- 18
                        sysdate_in,      -- 19
                        userid_updt,     -- 20
                        sysdate_updt,    -- 21
                        thn_transaksi,   -- not exist in original source (error supressor)
                        bln_transaksi    -- idem
                    )
                    SELECT
                        K.id,           -- 1
                        A.id_katalog,   -- 2
                        A.no_batch,     -- 3
                        A.kode_reff,    -- 4
                        C.no_doc,       -- 5
                        'D',            -- 6
                        0,              -- 7
                        C.id_pengirim,  -- 8
                        0,              -- 9
                        C.id_penerima,  -- 10
                        A.tgl_expired,  -- 11
                        1,              -- 12
                        0,              -- 13
                        1,              -- 14
                        1,              -- 15
                        C.keterangan,   -- 16
                        1,              -- 17
                        C.ver_usrkirim, -- 18
                        C.ver_tglkirim, -- 19
                        C.ver_usrkirim, -- 20
                        C.ver_tglkirim, -- 21
                        '',             -- not exist in original source (error supressor)
                        ''              -- idem
                    FROM db1.tdetailf_distribusirinc AS A
                    LEFT JOIN db1.tdetailf_distribusi AS B ON A.kode_reff = B.kode_reff
                    LEFT JOIN db1.transaksif_distribusi AS C ON A.kode_reff = C.kode
                    LEFT JOIN (
                        SELECT IFNULL(MAX(id), 0) + 1 AS id
                        FROM db1.transaksif_kartugasmedis
                    ) AS K
                    WHERE
                        A.id_katalog = :idKatalog
                        AND A.no_batch = :noBatch
                        AND A.kode_reff = :kodeRef
                        AND A.id_katalog = B.id_katalog
                ";
                $params = [":idKatalog" => $oIdKatalog, ":noBatch" => $oNoBatch, ":kodeRef" => $oKodeReferensi];
                $berhasilTambah = $connection->createCommand($sql, $params)->execute();
                if (!$berhasilTambah) throw new FailToInsertException("Kartu Gas Medis");

            // jika tabung nya isi doank end
            } else {
                // insert ke kartu kalau barang sudah dikirim, hanya tabung yang berisi yang memotong stok
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT IGNORE INTO db1.transaksif_kartugasmedis (
                        id,              -- 1
                        id_katalog,      -- 2
                        no_batch,        -- 3
                        kode_reff,       -- 4
                        no_doc,          -- 5
                        tipe_doc,        -- 6
                        kd_pengirim,     -- 7
                        id_pengirim,     -- 8
                        kd_penerima,     -- 9
                        id_penerima,     -- 10
                        tgl_expired,     -- 11
                        jumlah_masuk,    -- 12
                        jumlah_keluar,   -- 13
                        jumlah_tersedia, -- 14
                        sts_tersedia,    -- 15
                        keterangan,      -- 16
                        sts_transaksi,   -- 17
                        userid_in,       -- 18
                        sysdate_in,      -- 19
                        userid_updt,     -- 20
                        sysdate_updt,    -- 21
                        thn_transaksi,   -- not exist in original source (error supressor)
                        bln_transaksi    -- idem
                    )
                    SELECT
                        K.id,           -- 1
                        A.id_katalog,   -- 2
                        A.no_batch,     -- 3
                        A.kode_reff,    -- 4
                        C.no_doc,       -- 5
                        'B',            -- 6
                        0,              -- 7
                        C.id_pengirim,  -- 8
                        0,              -- 9
                        C.id_penerima,  -- 10
                        A.tgl_expired,  -- 11
                        1,              -- 12
                        0,              -- 13
                        1,              -- 14
                        0,              -- 15
                        C.keterangan,   -- 16
                        1,              -- 17
                        C.ver_usrkirim, -- 18
                        C.ver_tglkirim, -- 19
                        C.ver_usrkirim, -- 20
                        C.ver_tglkirim, -- 21
                        '',             -- not exist in original source (error supressor)
                        ''              -- idem
                    FROM db1.tdetailf_distribusirinc AS A
                    LEFT JOIN db1.tdetailf_distribusi AS B ON A.kode_reff = B.kode_reff
                    LEFT JOIN db1.transaksif_distribusi AS C ON A.kode_reff = C.kode
                    JOIN (
                        SELECT IFNULL(MAX(id), 0) + 1 AS id
                        FROM db1.transaksif_kartugasmedis
                    ) AS K
                    WHERE
                        A.id_katalog = :idKatalog
                        AND A.no_batch = :noBatch
                        AND A.id_katalog = B.id_katalog
                ";
                $params = [":idKatalog" => $oIdKatalog, ":noBatch" => $oNoBatch];
                $berhasilTambah = $connection->createCommand($sql, $params)->execute();
                if (!$berhasilTambah) throw new FailToInsertException("Kartu Gas Medis");
            }

            // input ketersediaan tabung di depo/floor pengirim

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.transaksif_seritabung
                SET
                    id_katalog = :idKatalog,
                    no_batch = :noBatch,
                    tgl_expired = :tanggalKadaluarsa,
                    isi_kemasan = :isiKemasan,
                    id_kemasan = :idKemasan,
                    id_kemasandepo = :idKemasanDepo,
                    kd_unitpemilik = 0,
                    id_unitpemilik = :idUnitPemilik,
                    kd_unitposisi = 0,
                    id_unitposisi = :idUnitPemilik,
                    keterangan = :keterangan,
                    sts_tersedia = :verKirim,
                    sts_aktif = 1,
                    userid_in = :verUserKirim,
                    sysdate_in = :verTanggalKirim,
                    userid_updt = :verUserKirim,
                    sysdate_updt = :verTanggalKirim 
                ON DUPLICATE KEY UPDATE
                    tgl_expired = :tanggalKadaluarsa,
                    isi_kemasan = :isiKemasan,
                    id_kemasan = :idKemasan,
                    id_kemasandepo = :idKemasanDepo,
                    kd_unitposisi = 0,
                    id_unitposisi = 60,
                    keterangan = :keterangan2,
                    sts_tersedia = :statusTersedia,
                    sts_aktif = 1,
                    userid_updt = :verUserKirim,
                    sysdate_updt = :verTanggalKirim
            ";
            $params = [
                ":idKatalog" => $oIdKatalog,
                ":noBatch" => $oNoBatch,
                ":tanggalKadaluarsa" => $oTanggalKadaluarsa,
                ":isiKemasan" => $oIsiKemasan,
                ":idKemasan" => $oIdKemasan,
                ":idKemasanDepo" => $oIdSatuan,
                ":idUnitPemilik" => $oIdPengirim,
                ":verUserKirim" => $data["ver_usrkirim"],
                ":verTanggalKirim" => $data["ver_tglkirim"],
                ":verKirim" => $data["ver_kirim"],
                ":keterangan" => $data["keterangan"],
                ":keterangan2" => $oKeterangan,
                ":statusTersedia" => $oStatusKetersediaan,
            ];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilTambah) throw new FailToInsertException("Seri Tabung");
        } // end looping penyimpanan ke stokkartu
        // -------------------------------------------------------------------

        // inputkan ke masterf warning untuk tabung isi yang dikirimkan ke depo
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.master_warning (
                kode,               -- 1
                tipe_pengiriman,    -- 2
                noPermintaan,       -- 3
                noPengeluaran,      -- 4
                verifikasi_user,    -- 5
                tanggal_verifikasi, -- 6
                depoPeminta,        -- 7
                kodeDepo,           -- 8
                kodeItem,           -- 9
                namaItem,           -- 10
                jumlah2,            -- 11
                detail,             -- 12
                status,             -- 13
                tanggal,            -- 14
                no_doc,             -- 15
                no_doc_pengiriman,  -- 16
                noPermintaan,        -- not exist in original source (error supressor)
                noPenerimaan,        -- idem
                kodeKetersediaan,    -- idem
                verifikasi_terima,   -- idem
                noDistribusi,        -- idem
                checking_double,     -- idem
                tipe,                -- idem
                nomor_batch,         -- idem
                checking_penerimaan, -- idem
                jumlah1,             -- idem
                jumlah3,             -- idem
                harga_perolehan,     -- idem
                tanggal_terima,      -- idem
                prioritas,           -- idem
                no_doc_penerimaan    -- idem
            )
            SELECT
                A.kode_reff,                   -- 1
                'Pengiriman Tanpa Permintaan', -- 2
                CONCAT('GMTP', A.kode_reff),   -- 3
                CONCAT('GM', A.kode_reff),     -- 4
                B.ver_usrkirim,                -- 5
                B.ver_tglkirim,                -- 6
                D.kode,                        -- 7
                C.kode,                        -- 8
                A.id_katalog,                  -- 9
                E.nama_sediaan,                -- 10
                dt.jumlah_item,                -- 11
                B.keterangan,                  -- 12
                'dikirim',                     -- 13
                B.tgl_doc,                     -- 14
                B.no_doc,                      -- 15
                B.no_doc,                      -- 16
                '',                            -- not exist in original source (error supressor)
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                '',                            -- idem
                ''                             -- idem
            FROM db1.tdetailf_distribusi AS A
            LEFT JOIN (
                SELECT
                    A.kode_reff,
                    A.id_katalog,
                    SUM(A.jumlah_kemasan) AS jumlah_kemasan,
                    SUM(A.jumlah_item)    AS jumlah_item
                FROM db1.tdetailf_distribusirinc AS A
                WHERE
                    A.kode_reff = :kodeRef
                    AND A.sts_ketersediaan = 1
                GROUP BY
                    A.kode_reff,
                    A.id_katalog
            ) dt ON A.kode_reff = dt.kode_reff
            LEFT JOIN db1.transaksif_distribusi AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_depo AS C ON B.id_pengirim = C.id
            LEFT JOIN db1.masterf_depo AS D ON B.id_penerima = D.id
            LEFT JOIN db1.masterf_katalog AS E ON A.id_katalog = E.kode
            WHERE
                B.sts_deleted = 0
                AND A.kode_reff = :kodeRef
                AND A.id_katalog = dt.id_katalog
        ";
        $params = [":kodeRef" => $data["kode"]];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Warning");
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/distribusi.php#getUpdateTrn the original method
     */
    private function getUpdateTrn(array $data): string
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.masterf_counter
            SET
                initial = :initial,
                unit = :unit,
                subunit = :subunit,
                kode = :kode,
                subkode = :subkode,
                detailkode = :detailKode,
                counter = :counter,
                keterangan = :keterangan,
                userid_updt = :idUserUbah
            ON DUPLICATE KEY UPDATE
                counter = counter + 1,
                userid_updt = :idUserUbah
        ";
        $params = [
            ":initial"    => $data["initial"],
            ":unit"       => $data["unit"],
            ":subunit"    => $data["subunit"],
            ":kode"       => $data["kode"],
            ":subkode"    => $data["subkode"],
            ":detailKode" => $data["detailkode"],
            ":counter"    => $data["counter"],
            ":keterangan" => $data["keterangan"],
            ":idUserUbah" => $data["userid_updt"],
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT counter
            FROM db1.masterf_counter
            WHERE
                initial = :initial
                AND unit = :unit
                AND subunit = :subunit
                AND kode = :kode
                AND subkode = :subkode
                AND detailkode = :detailKode
            LIMIT 1
        ";
        $params = [
            ":initial"    => $data["initial"],
            ":unit"       => $data["unit"],
            ":subunit"    => $data["subunit"],
            ":kode"       => $data["kode"],
            ":subkode"    => $data["subkode"],
            ":detailKode" => $data["detailkode"],
        ];
        return $connection->createCommand($sql, $params)->queryScalar();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/Masterdata.php#nodokumen as the source of copied text
     *
     * Designed for widget validation mechanism. To simplify code, this method always returns a success HTTP status. the
     * response body can be json "false" or object.
     */
    public function actionCekNoDokumen(): string
    {
        ["kode" => $kode, "no_doc" => $noDokumen] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        // "$kode < 10" is originated from view files
        // opinion1: "$kode < 10" doesn't make sense
        // opinion2: "kode = :kode" doesn't make sense too
        if (strlen($kode) < 10) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    kode   AS kode,
                    no_doc AS noDokumen
                FROM db1.transaksif_distribusi
                WHERE no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":noDokumen" => $noDokumen];

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    kode   AS kode,
                    no_doc AS noDokumen
                FROM db1.transaksif_distribusi
                WHERE
                    (kode = :kode AND no_doc = :noDokumen)
                    OR no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
        }
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
