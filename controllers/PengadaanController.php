<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use Jaspersoft\Client\Client as JasperClient;
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
class PengadaanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "tanggalDokumen" => $tanggalDokumen,
            "noDokumen" => $noDokumen,
            "noDokumenReferensi" => $noDokumenReferensi,
            "namaPemasok" => $namaPemasok,
            "subjenisAnggaran" => $subjenisAnggaran,
            "bulanAwalAnggaran" => $bulanAnggaran,
            "tahunAnggaran" => $tahunAnggaran,
            "kodeRefRencana" => $kodeRefRencana,
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
            FROM db1.transaksif_pengadaan AS ADA
            LEFT JOIN db1.masterf_subjenisanggaran AS SAG ON ADA.id_jenisanggaran = SAG.id
            LEFT JOIN db1.masterf_pbf AS PBF ON ADA.id_pbf = PBF.id
            LEFT JOIN db1.user AS USR ON ADA.userid_updt = USR.id
            WHERE
                ADA.sts_deleted = 0
                AND (:tanggalDokumen = '' OR ADA.tgl_doc = :tanggalDokumen)
                AND (:noDokumen = '' OR ADA.no_doc LIKE :noDokumen)
                AND (:noDokumenReferensi = '' OR ADA.no_docreff LIKE :noDokumenReferensi)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:subjenisAnggaran = '' OR SAG.subjenis_anggaran = :subjenisAnggaran)
                AND (:bulanAnggaran = '' OR ADA.blnawal_anggaran = :bulanAnggaran OR ADA.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR ADA.thn_anggaran = :tahunAnggaran)
                AND (:kodeRefRencana = '' OR ADA.kode_reffrenc = :kodeRefRencana)
        ";
        $params = [
            ":tanggalDokumen" => $tanggalDokumen ? $toSystemDate($tanggalDokumen) : "",
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":noDokumenReferensi" => $noDokumenReferensi ? "%$noDokumenReferensi%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":subjenisAnggaran" => $subjenisAnggaran,
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
            ":kodeRefRencana" => $kodeRefRencana,
        ];
        $jumlahPengadaan = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                ADA.kode              AS kode,
                ADA.no_doc            AS noDokumen,
                ADA.tgl_doc           AS tanggalDokumen,
                ADA.no_docreff        AS noDokumenReferensi,
                ADA.thn_anggaran      AS tahunAnggaran,
                ADA.blnawal_anggaran  AS bulanAwalAnggaran,
                ADA.blnakhir_anggaran AS bulanAkhirAnggaran,
                ADA.nilai_akhir       AS nilaiAkhir,
                ADA.sts_saved         AS statusSaved,
                ADA.sts_linked        AS statusLinked,
                ADA.sts_revisi        AS statusRevisi,
                ADA.sysdate_updt      AS updatedTime,
                SAG.subjenis_anggaran AS subjenisAnggaran,
                PBF.nama_pbf          AS namaPemasok,
                USR.name              AS updatedBy
            FROM db1.transaksif_pengadaan AS ADA
            LEFT JOIN db1.masterf_subjenisanggaran AS SAG ON ADA.id_jenisanggaran = SAG.id
            LEFT JOIN db1.masterf_pbf AS PBF ON ADA.id_pbf = PBF.id
            LEFT JOIN db1.user AS USR ON ADA.userid_updt = USR.id
            WHERE
                ADA.sts_deleted = 0
                AND (:tanggalDokumen = '' OR ADA.tgl_doc = :tanggalDokumen)
                AND (:noDokumen = '' OR ADA.no_doc LIKE :noDokumen)
                AND (:noDokumenReferensi = '' OR ADA.no_docreff LIKE :noDokumenReferensi)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:subjenisAnggaran = '' OR SAG.subjenis_anggaran = :subjenisAnggaran)
                AND (:bulanAnggaran = '' OR ADA.blnawal_anggaran = :bulanAnggaran OR ADA.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR ADA.thn_anggaran = :tahunAnggaran)
                AND (:kodeRefRencana = '' OR ADA.kode_reffrenc = :kodeRefRencana)
            ORDER BY tgl_doc ASC
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarPengadaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "recordsFiltered" => $jumlahPengadaan,
            "data" => $daftarPengadaan
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#add    the original method
     */
    public function actionSaveAdd(): string
    {
        [   "action" => $action,
            "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "ppn" => $ppn,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "id_reffkatalog" => $daftarIdRefKatalog,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "isi_kemasan" => $daftarIsiKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "harga_item" => $daftarHargaItem,
            "harga_kemasan" => $daftarHargaKemasan,
            "diskon_item" => $daftarDiskonItem,
            "diskon_harga" => $daftarDiskonHarga,
            "no_docrenc" => $daftarNoDokumenRencana,
            "kode_reffrenc" => $daftarKodeRefRencana,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $dataPengadaan = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "kode_reffrenc" => implode(",", $daftarKodeRefRencana),
            "no_docreff" => implode(",", $daftarNoDokumenRencana),
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "ppn" => $ppn ?? 0,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
            "sts_saved" => 1,
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "H",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode HPS Pengadaan PPK Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUser
            ]);
            $kode = "H00" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);

            $dataPengadaan = [
                ...$dataPengadaan,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        $dataDetailPengadaan = [];

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            if (!$daftarJumlahKemasan[$i]) continue;
            $dataDetailPengadaan[$idKatalog] = [
                "kode_reff" => $kode,
                "kode_reffrenc" => $daftarKodeRefRencana[$i],
                "id_katalog" => $idKatalog,
                "id_reffkatalog" => $daftarIdRefKatalog[$i],
                "kemasan" => $daftarKemasan[$i],
                "id_pabrik" => $daftarIdPabrik[$i],
                "id_kemasan" => $daftarIdKemasan[$i],
                "id_kemasandepo" => $daftarIdKemasanDepo[$i],
                "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$i]),
                "jumlah_item" => $toSystemNumber($daftarJumlahItem[$i]),
                "jumlah_kemasan" => $toSystemNumber($daftarJumlahKemasan[$i]),
                "harga_item" => $toSystemNumber($daftarHargaItem[$i]),
                "harga_kemasan" => $toSystemNumber($daftarHargaKemasan[$i]),
                "diskon_item" => $toSystemNumber($daftarDiskonItem[$i]),
                "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$i]),
                "userid_updt" => $idUser,
            ];
        }

        $kodeRefRencana = "'". implode("','", $daftarKodeRefRencana) ."'";

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        $daftarField = array_keys($dataPengadaan);
        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_pengadaan
                WHERE
                    kode = :kode
                    OR no_doc = :noDokumen
                LIMIT 1    
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $adaPengadaan = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaPengadaan) throw new DataAlreadyExistException($kode, $noDokumen, $transaction);

            $berhasilTambah = $fm->saveData("transaksif_pengadaan", $daftarField, $dataPengadaan);
            if (!$berhasilTambah) throw new FailToInsertException("Pengadaan", $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_perencanaan
                SET sts_linked = 1
                WHERE kode IN ($kodeRefRencana)
            ";
            $berhasilUbah = $connection->createCommand($sql)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Perencanaan", "Kode", $kodeRefRencana, $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_pengadaan", $dataDetailPengadaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Pengadaan", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_pengadaan", $daftarField, $dataPengadaan, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Pengadaan", "Kode", $kode, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_perencanaan
                SET sts_linked = 1
                WHERE kode IN ($kodeRefRencana)
            ";
            $berhasilUbah = $connection->createCommand($sql)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Perencanaan", "Kode", $kodeRefRencana, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_pengadaan", $dataDetailPengadaan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Pengadaan", "Kode Ref", $kode, $transaction);
        }
        $transaction->commit();

        return json_encode(["kode" => $kode]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#edit    the original method
     */
    public function actionFormData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode              AS kode,
                A.revisike          AS revisiKe,
                A.keterangan        AS keterangan,
                A.no_doc            AS noDokumen,
                A.tgl_doc           AS tanggalDokumen,
                A.no_doc_spph       AS noDokumenSpph,
                A.tgl_pengajuan     AS tanggalPengajuan,
                A.tgl_mulai_spph    AS tanggalMulaiSpph,
                A.tgl_akhir_spph    AS tanggalAkhirSpph,
                A.jumlah_hari_spph  AS jumlahHariSpph,
                A.kode_reffrenc     AS kodeRefRencana,
                A.no_docreff        AS noDokumenRef,
                A.id_pbf            AS idPemasok,
                A.id_jenisanggaran  AS idJenisAnggaran,
                A.id_sumberdana     AS idSumberDana,
                A.id_subsumberdana  AS idSubsumberDana,
                A.id_carabayar      AS idCaraBayar,
                A.id_jenisharga     AS idJenisHarga,
                A.thn_anggaran      AS tahunAnggaran,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.ppn               AS ppn,
                A.nilai_total       AS nilaiTotal,
                A.nilai_diskon      AS nilaiDiskon,
                A.nilai_ppn         AS nilaiPpn,
                A.nilai_pembulatan  AS nilaiPembulatan,
                A.nilai_akhir       AS nilaiAkhir,
                A.ver_revisi        AS verRevisi,
                A.ver_usrrevisi     AS verUserRevisi,
                A.ver_tglrevisi     AS verTanggalRevisi,
                A.sts_saved         AS statusSaved,
                A.sts_linked        AS statusLinked,
                A.sts_closed        AS statusClosed,
                A.sysdate_cls       AS sysdateClosed,
                A.sts_deleted       AS statusDeleted,
                A.sysdate_del       AS sysdateDeleted,
                A.sts_revisi        AS statusRevisi,
                A.sysdate_rev       AS sysdateRevisi,
                A.userid_in         AS useridInput,
                A.sysdate_in        AS sysdateInput,
                A.userid_updt       AS useridUpdate,
                A.sysdate_updt      AS sysdateUpdate,
                B.kode              AS kodePemasok,
                B.nama_pbf          AS namaPemasok,
                NULL                AS daftarDetailPengadaan,
                NULL                AS daftarPerencanaan
            FROM db1.transaksif_pengadaan AS A
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
                AND A.sts_closed = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pengadaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$pengadaan) throw new DataNotExistException($kode);

        // ambil data perencanaan
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT DISTINCT
                A.kode                     AS kode,
                A.no_doc                   AS noDokumen,
                1                          AS act,
                A.blnawal_anggaran         AS bulanAwalAnggaran,
                A.blnakhir_anggaran        AS bulanAkhirAnggaran,
                A.thn_anggaran             AS tahunAnggaran,
                A.id_jenisanggaran         AS idJenisAnggaran,
                A.id_sumberdana            AS idSumberDana,
                A.id_subsumberdana         AS idSubsumberDana,
                A.id_jenisharga            AS idJenisHarga,
                A.id_carabayar             AS idCaraBayar,
                A.ppn                      AS ppn,
                A.nilai_akhir              AS nilaiAkhir,
                Ba.subjenis_anggaran       AS subjenisAnggaran
            FROM db1.transaksif_perencanaan AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS Ba ON A.id_jenisanggaran = Ba.id
            INNER JOIN db1.tdetailf_pengadaan AS B ON A.kode = B.kode_reffrenc
            WHERE B.kode_reff = :kodeRef
            GROUP BY A.kode
        ";
        $params = [":kodeRef" => $kode];
        $pengadaan->daftarPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                          AS kodeRef,
                A.kode_reffrenc                      AS kodeRefRencana,
                A.id_katalog                         AS idKatalog,
                A.id_reffkatalog                     AS idRefKatalog,
                A.id_pabrik                          AS idPabrik,
                A.id_kemasan                         AS idKemasan,
                A.isi_kemasan                        AS isiKemasan,
                A.id_kemasandepo                     AS idKemasanDepo,
                A.kemasan                            AS kemasan,
                A.jumlah_item                        AS jumlahItem,
                A.jumlah_kemasan                     AS jumlahKemasan,
                A.harga_item                         AS hargaItem,
                A.harga_kemasan                      AS hargaKemasan,
                A.diskon_item                        AS diskonItem,
                A.diskon_harga                       AS diskonHarga,
                B.id_pbf                             AS idPemasok,
                C.nama_sediaan                       AS namaSediaan,
                C.id_kemasanbesar                    AS idKemasanKat,
                C.id_kemasankecil                    AS idKemasanDepoKat,
                C.kemasan                            AS kemasanKat,
                C.isi_kemasan                        AS isiKemasanKat,
                C.harga_beli                         AS hargaItemKat,
                C.harga_beli * C.isi_kemasan         AS hargaKemasanKat,
                C.diskon_beli                        AS diskonItemKat,
                D.nama_pabrik                        AS namaPabrik,                     
                E.kode                               AS satuanJual,
                G.kode                               AS satuanKat,
                Gg.kode                              AS satuan,
                F.kode                               AS satuanJualKat,
                IFNULL(H.harga_item, A.harga_item)   AS hargaItemPemasok,
                IFNULL(H.diskon_item, A.diskon_item) AS diskonItemPemasok,
                Rd.jumlah_item                       AS jumlahRencana,
                Rt.no_doc                            AS noDokumenRencana,
                0                                    AS jumlahTerima,
                0                                    AS jumlahRetur
            FROM db1.tdetailf_pengadaan AS A
            LEFT JOIN db1.transaksif_pengadaan AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.tdetailf_perencanaan AS Rd ON Rd.id_katalog = A.id_katalog
            LEFT JOIN db1.transaksif_perencanaan AS Rt ON Rt.kode = A.kode_reffrenc
            LEFT JOIN db1.masterf_katalog AS C ON C.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS D ON D.id = C.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS E ON E.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS F ON F.id = C.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS G ON G.id = C.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS Gg ON Gg.id = A.id_kemasandepo
            LEFT JOIN db1.relasif_katalogpbf AS H ON A.id_katalog = H.id_katalog
            WHERE
                A.kode_reff = :kodeRef
                AND H.id_pbf = B.id_pbf
                AND H.id_jenisharga = B.id_jenisharga
                AND Rd.kode_reff = A.kode_reffrenc
            ORDER BY nama_sediaan
        ";
        $params = [":kodeRef" => $kode];
        $pengadaan->daftarDetailPengadaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($pengadaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#views    the original method
     */
    public function actionViewData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                  AS kodePengadaan,
                A.no_doc                AS noDokumenPengadaan,
                A.no_docreff            AS noDokumenRef,
                A.tgl_doc               AS tanggalDokumenPengadaan,
                A.sts_linked            AS statusLinked,
                A.sts_closed            AS statusClosed,
                A.blnawal_anggaran      AS bulanAwalAnggaran,
                A.blnakhir_anggaran     AS bulanAkhirAnggaran,
                A.thn_anggaran          AS tahunAnggaran,
                A.ppn                   AS ppn,
                B.subjenis_anggaran     AS subjenisAnggaran,
                C.sumber_dana           AS sumberDana,
                D.subsumber_dana        AS subsumberDana,
                E.jenis_harga           AS jenisHarga,
                F.cara_bayar            AS caraBayar,
                IFNULL(G.nama_pbf, '-') AS namaPemasok,
                A.nilai_pembulatan      AS nilaiPembulatan,
                NULL                    AS daftarDetailPengadaan
            FROM db1.transaksif_pengadaan AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.masterf_pbf AS G ON A.id_pbf = G.id
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pengadaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$pengadaan) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode_reff                 AS kodePengadaan,
                A.id_katalog                AS idKatalog,
                A.kode_reffrenc             AS kodeRefRencana,
                A.kemasan                   AS kemasan,
                A.isi_kemasan               AS isiKemasan,
                A.jumlah_kemasan            AS jumlahKemasan,
                A.harga_item                AS hargaItem,
                A.harga_kemasan             AS hargaKemasan,
                A.diskon_item               AS diskonItem,
                A.diskon_harga              AS diskonHarga,
                KAT.nama_sediaan            AS namaSediaan,
                PBK.nama_pabrik             AS namaPabrik,
                IFNULL(pl.jumlah_item, 0)   AS jumlahPl,
                IFNULL(renc.jumlah_item, 0) AS jumlahRencana,
                IFNULL(A.jumlah_item, 0)    AS jumlahHps,
                pl.jumlah_trm               AS jumlahTerima,
                pl.jumlah_ret               AS jumlahRetur
            FROM db1.tdetailf_pengadaan AS A
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_perencanaan AS renc ON A.kode_reffrenc = renc.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_reffhps     AS kode_reffhps,
                    A.id_reffkatalog   AS id_reffkatalog,
                    SUM(A.jumlah_item) AS jumlah_item,
                    tS.jumlah_item     AS jumlah_do,
                    T.jumlah_item      AS jumlah_trm,
                    tRet.jumlah_item   AS jumlah_ret
                FROM db1.tdetailf_pembelian AS A
                LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_pemesanan AS A
                    LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS tS ON A.kode_reff = tS.kode_reffpl
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS T ON A.kode_reff = T.kode_reffpl
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_return AS A
                    LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS tRet ON A.kode_reff = tRet.kode_reffpl
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffhps = :kode
                    AND A.id_katalog = tRet.id_katalog
                    AND A.id_katalog = T.id_katalog
                    AND A.id_katalog = tS.id_katalog
                GROUP BY A.kode_reffhps, A.id_reffkatalog
            ) AS pl ON A.kode_reff = pl.kode_reffhps
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = pl.id_reffkatalog
                AND A.id_katalog = renc.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $pengadaan->daftarDetailPengadaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($pengadaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws LogicBranchException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#prints    the original method
     */
    public function actionPrint(): string
    {
        ["kode" => $kode, "versi" => $versi] = Yii::$app->request->post();
        $c = new JasperClient("http://192.168.3.34:8080/jasperserver", "jasperadmin", "jasperadmin");
        $parameter = ["kode_reff" => $kode];

        switch ($versi ?? "v_01") {
            case "v_01": $laporan = "/reports/Farmasi/lap_revisi_pengadaan"; break;
            case "v_02": $laporan = "/reports/Farmasi/lap_revisi_pengadaan_v2"; break;
            default: throw new LogicBranchException;
        }

        $report = $c->reportService()->runReport($laporan, "pdf", null, null, $parameter);
        return $this->renderPdf($report, "report");
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-a412577
     */
    public function actionSearchJsonDetailHarga(): string
    {
        [   "sts_revisi" => $statusRevisi,
            "kode_reff" => $kodeRef,
            "kode_reff_not" => $kodeRefNot,
            "id_pbf" => $idPemasok,
            "id_jenisharga" => $idJenisHarga
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                             AS idKatalog,
                A.kode_reff                              AS kodeRefHps,
                A.kode_reffrenc                          AS kodeRefRencana,
                A.id_reffkatalog                         AS idRefKatalog,
                A.kemasan                                AS kemasan,
                A.id_kemasan                             AS idKemasan,
                A.id_kemasandepo                         AS idKemasanDepo,
                A.jumlah_item                            AS jumlahItem,
                A.jumlah_kemasan                         AS jumlahKemasan,
                IFNULL(B.harga_item, A.harga_item)       AS hargaItem,
                IFNULL(B.harga_kemasan, A.harga_kemasan) AS hargaKemasan,
                A.harga_kemasan                          AS hargaKemasan,
                IFNULL(B.isi_kemasan, A.isi_kemasan)     AS isiKemasan,
                IFNULL(B.diskon_item, A.diskon_item)     AS diskonItem,
                C.nama_sediaan                           AS namaSediaan,
                IFNULL(C.kemasan, 0)                     AS kemasanKat,
                C.id_kemasanbesar                        AS idKemasanKat,
                C.id_kemasankecil                        AS idKemasanDepoKat,
                C.isi_kemasan                            AS isiKemasanKat,
                C.harga_beli                             AS hargaItemKat,
                (C.harga_beli * C.isi_kemasan)           AS hargaKemasanKat,
                C.diskon_beli                            AS diskonItemKat,
                C.id_pabrik                              AS idPabrik,
                E.kode                                   AS satuanJual,
                F.kode                                   AS satuanJualKat,
                G.kode                                   AS satuan,
                H.kode                                   AS satuanKat,
                R.jumlah_item                            AS jumlahRencana,
                IFNULL(P.jumlah_item, 0)                 AS jumlahPl,
                IFNULL(P.jumlah_terima, 0)               AS jumlahTerima,
                D.nama_pabrik                            AS namaPabrik,
                Tr.no_doc                                AS noDokumenRencana,
                0                                        AS jumlahRetur,
                0                                        AS jumlahBonus,
                0                                        AS jumlahDo,
                A.jumlah_item                            AS jumlahHps
            FROM db1.tdetailf_pengadaan AS A
            INNER JOIN db1.transaksif_pengadaan AS S ON A.kode_reff = S.kode
            INNER JOIN db1.transaksif_perencanaan AS Tr ON A.kode_reffrenc = Tr.kode
            LEFT JOIN db1.relasif_katalogpbf AS B ON
                A.id_katalog = B.id_katalog
                AND sts_actived = 1
                AND A.id_kemasandepo = B.id_kemasandepo
                AND B.id_pbf = :idPemasok
                AND B.id_jenisharga = :idJenisHarga
            LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
            LEFT JOIN db1.masterf_pabrik AS D ON C.id_pabrik = D.id
            LEFT JOIN db1.masterf_kemasan AS E ON A.id_kemasan = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON C.id_kemasanbesar = F.id
            LEFT JOIN db1.masterf_kemasan AS G ON A.id_kemasandepo = G.id
            LEFT JOIN db1.masterf_kemasan AS H ON C.id_kemasankecil = H.id
            LEFT JOIN db1.tdetailf_perencanaan AS R ON A.id_reffkatalog = R.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffrenc               AS kode_reffrenc,
                    A.id_reffkatalog              AS id_reffkatalog,
                    A.id_katalog                  AS id_katalog,
                    SUM(A.jumlah_item)            AS jumlah_item,
                    IFNULL(SUM(C.jumlah_item), 0) AS jumlah_terima
                FROM db1.tdetailf_pembelian AS A
                LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        X.kode_reffpl      AS kode_reffpl,
                        X.id_reffkatalog   AS id_reffkatalog,
                        SUM(X.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS X
                    LEFT JOIN db1.transaksif_penerimaan AS Y ON X.kode_reff = Y.kode
                    WHERE Y.sts_deleted = 0
                    GROUP BY X.kode_reffpl, X.id_reffkatalog
                ) AS C ON A.kode_reff = C.kode_reffpl
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffrenc IS NOT NULL
                    AND A.kode_reffrenc != ''
                    AND A.kode_reff != :kodeRefNot
                    AND A.id_katalog = C.id_reffkatalog
                GROUP BY A.kode_reffrenc, A.id_reffkatalog
            ) AS P ON A.kode_reffrenc = P.kode_reffrenc
            WHERE
                A.kode_reff = :kodeRef
                AND (:statusRevisi = '' OR S.sts_revisi = :statusRevisi)
                AND S.sts_deleted = 0
                AND S.sts_closed = 0
                AND A.id_katalog = P.id_reffkatalog
                AND A.kode_reffrenc = R.kode_reff
            ORDER BY C.nama_sediaan
        ";
        $params = [
            ":kodeRef" => $kodeRef,
            ":kodeRefNot" => $kodeRefNot,
            ":idPemasok" => $idPemasok,
            ":idJenisHarga" => $idJenisHarga,
            ":statusRevisi" => $statusRevisi,
        ];
        $daftarData = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarData);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-a412577
     */
    public function actionSearchJsonLainnya(): string
    {
        $noDoc = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                ADA.kode              AS kode,
                ADA.no_doc            AS noDokumen,
                ADA.kode_reffrenc     AS kodeRefRencana,
                ADA.no_docreff        AS noDokumenRef,
                ADA.id_pbf            AS idPemasok,
                ADA.blnawal_anggaran  AS bulanAwalAnggaran,
                ADA.blnakhir_anggaran AS bulanAkhirAnggaran,
                ADA.thn_anggaran      AS tahunAnggaran,
                ADA.id_jenisanggaran  AS idJenisAnggaran,
                ADA.id_sumberdana     AS idSumberDana,
                ADA.id_subsumberdana  AS idSubsumberDana,
                ADA.id_jenisharga     AS idJenisHarga,
                ADA.id_carabayar      AS idCaraBayar,
                ADA.ppn               AS ppn,
                ADA.nilai_akhir       AS nilaiAkhir,
                SAG.subjenis_anggaran AS subjenisAnggaran,
                DNA.sumber_dana       AS sumberDana,
                SDN.subsumber_dana    AS subsumberDana,
                JHG.jenis_harga       AS jenisHarga,
                CBY.cara_bayar        AS caraBayar,
                PBF.nama_pbf          AS namaPemasok,
                PBF.kode              AS kodePemasok
            FROM db1.transaksif_pengadaan AS ADA
            LEFT JOIN db1.masterf_subjenisanggaran AS SAG ON ADA.id_jenisanggaran = SAG.id
            LEFT JOIN db1.masterf_sumberdana AS DNA ON ADA.id_sumberdana = DNA.id
            LEFT JOIN db1.masterf_subsumberdana AS SDN ON ADA.id_subsumberdana = SDN.id
            LEFT JOIN db1.masterf_jenisharga AS JHG ON ADA.id_jenisharga = JHG.id
            LEFT JOIN db1.masterf_carabayar AS CBY ON ADA.id_carabayar = CBY.id
            LEFT JOIN db1.masterf_pbf AS PBF ON ADA.id_pbf = PBF.id
            WHERE
                ADA.no_doc = :noDokumen
                AND ADA.sts_deleted = 0
                AND ADA.sts_closed = 0
            ORDER BY ADA.no_doc ASC
            LIMIT 30
        ";
        $params = [":noDokumen" => "%$noDoc%"];
        $daftarPengadaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPengadaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#ajaxDelete    the original method
     */
    public function actionAjaxDelete(): string
    {
        ["keterangan" => $keterangan, "kode" => $kode] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pengadaan
            SET
                no_doc = kode,
                keterangan = CONCAT('Hapus HPS Pengadaan dengan No: ', :keterangan),
                sts_deleted = 1,
                sysdate_del = :tanggal
            WHERE
                kode = :kode
                AND sts_deleted = 0
                AND sts_closed = 0
                AND sts_linked = 0
        ";
        $params = [":keterangan" => $keterangan, ":tanggal" => $nowValSystem, ":kode" => $kode];
        $berhasilHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pengadaan.php#getUpdateTrn the original method
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/Masterdata#nodokumen as the source of copied text
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
                FROM db1.transaksif_pengadaan
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
                FROM db1.transaksif_pengadaan
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
