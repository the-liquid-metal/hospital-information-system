<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\{
    DataAlreadyExistException,
    DataNotExistException,
    FailToInsertException,
    FailToUpdateException,
    FarmasiModel,
};
use tlm\his\FatmaPharmacy\views\Pemesanan\{Cetak, ListPo};
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
class PemesananController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "statusClosed" => $statusClosed,
            "tanggalTempoKirim" => $tanggalTempoKirim,
            "noDokumen" => $noDokumen,
            "noRencana" => $noRencana,
            "noSpk" => $noSpk,
            "namaPemasok" => $namaPemasok,
            "kodeJenis" => $kodeJenis,
            "bulanAwalAnggaran" => $bulanAnggaran,
            "tahunAnggaran" => $tahunAnggaran,
            "kode" => $kode,
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
            FROM db1.transaksif_pemesanan AS PSN
            LEFT JOIN db1.transaksif_pembelian AS PBN ON PBN.kode = PSN.kode_reffpl
            LEFT JOIN db1.transaksif_perencanaan AS RCN ON RCN.kode = PSN.kode_reffro
            LEFT JOIN db1.masterf_pbf AS PBF ON PSN.id_pbf = PBF.id
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON PSN.id_jenisanggaran = SJA.id
            LEFT JOIN db1.user AS USR ON PSN.userid_updt = USR.id
            WHERE
                PSN.sts_deleted = 0
                AND (:statusClosed = '' OR PSN.sts_closed = :statusClosed)
                AND (:tanggalTempoKirim = '' OR PSN.tgl_tempokirim = :tanggalTempoKirim)
                AND (:noDokumen = '' OR PSN.no_doc LIKE :noDokumen)
                AND (:noRencana = '' OR RCN.no_doc LIKE :noRencana)
                AND (:noSpk = '' OR PBN.no_doc LIKE :noSpk)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:kodeJenis = '' OR SJA.kode = :kodeJenis)
                AND (:bulanAnggaran = '' OR PSN.blnawal_anggaran = :bulanAnggaran OR PSN.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR PSN.thn_anggaran = :tahunAnggaran)
                AND (:kode = '' OR PSN.kode = :kode)
        ";
        $params = [
            ":statusClosed" => $statusClosed,
            ":tanggalTempoKirim" => $tanggalTempoKirim ? $toSystemDate($tanggalTempoKirim) : "",
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":noRencana" => $noRencana ? "%$noRencana%" : "",
            ":noSpk" => $noSpk ? "%$noSpk%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":kodeJenis" => $kodeJenis,
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
            ":kode" => $kode,
        ];
        $jumlahPemesanan = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                PSN.kode              AS kode,
                PSN.revisike          AS revisiKe,
                PSN.no_doc            AS noDokumen,
                PSN.tgl_tempokirim    AS tanggalTempoKirim,
                PSN.thn_anggaran      AS tahunAnggaran,
                PSN.blnawal_anggaran  AS bulanAwalAnggaran,
                PSN.blnakhir_anggaran AS bulanAkhirAnggaran,
                PSN.nilai_akhir       AS nilaiAkhir,
                PSN.sts_linked        AS statusLinked,
                PSN.sts_revisi        AS statusRevisi,
                PSN.sts_closed        AS statusClosed,
                PSN.sysdate_updt      AS updatedTime,
                RCN.no_doc            AS noRencana,
                RCN.ver_revisi        AS verRevisiRo,
                RCN.sts_revisi        AS statusRevisiRo,
                PBN.no_doc            AS noSpk,
                PBN.sts_revisi        AS statusRevisiPl,
                SJA.kode              AS kodeJenis,
                PBF.nama_pbf          AS namaPemasok,
                USR.name              AS updatedBy
            FROM db1.transaksif_pemesanan AS PSN
            LEFT JOIN db1.transaksif_pembelian AS PBN ON PBN.kode = PSN.kode_reffpl
            LEFT JOIN db1.transaksif_perencanaan AS RCN ON RCN.kode = PSN.kode_reffro
            LEFT JOIN db1.masterf_pbf AS PBF ON PSN.id_pbf = PBF.id
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON PSN.id_jenisanggaran = SJA.id
            LEFT JOIN db1.user AS USR ON PSN.userid_updt = USR.id
            WHERE
                PSN.sts_deleted = 0
                AND (:statusClosed = '' OR PSN.sts_closed = :statusClosed)
                AND (:tanggalTempoKirim = '' OR PSN.tgl_tempokirim = :tanggalTempoKirim)
                AND (:noDokumen = '' OR PSN.no_doc LIKE :noDokumen)
                AND (:noRencana = '' OR RCN.no_doc LIKE :noRencana)
                AND (:noSpk = '' OR PBN.no_doc LIKE :noSpk)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:kodeJenis = '' OR SJA.kode = :kodeJenis)
                AND (:bulanAnggaran = '' OR PSN.blnawal_anggaran = :bulanAnggaran OR PSN.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR PSN.thn_anggaran = :tahunAnggaran)
                AND (:kode = '' OR PSN.kode = :kode)
            ORDER BY PSN.tgl_doc DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarPemesanan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "recordsFiltered" => $jumlahPemesanan,
            "data" => $daftarPemesanan
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#add    the original method
     */
    public function actionSaveAdd(): void
    {
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        [   "action" => $action,
            "no_doc" => $noDokumen,
            "kode" => $kode,
            "tgl_doc" => $tanggalDokumen,
            "tgl_tempokirim" => $tanggalTempoKirim,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "id_pbf" => $idPemasok,
            "ppn" => $ppn,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "ver_revisi" => $verRevisi,
            "kode_reffro" => $daftarKodeRefRo,
            "kode_reffpl" => $daftarKodeRefPl,
            "kode_reffrenc" => $daftarKodeRefRencana,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "id_reffkatalog" => $daftarIdRefKatalog,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "diskon_harga" => $daftarDiskonHarga,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "harga_kemasan" => $daftarHargaKemasan,
            "harga_item" => $daftarHargaItem,
            "diskon_item" => $daftarDiskonItem,
        ] = Yii::$app->request->post();

        $kodeRefRo = $daftarKodeRefRo[0];

        $dataPemesanan = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tgl_tempokirim" => $toSystemDate($tanggalTempoKirim),
            "kode_reffro" => $kodeRefRo,
            "kode_reffpl" => $daftarKodeRefPl[0],
            "kode_reffrenc" => $daftarKodeRefRencana[0],
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "id_pbf" => $idPemasok,
            "ppn" => $ppn ?? 0,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        if ($verRevisi == 1) {
            $dataPemesanan = [
                ...$dataPemesanan,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $idUser,
                "ver_tglrevisi" => $nowValSystem,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => "Revisi sudah disesuaikan",
            ];
        }

        // set no Transaksi
        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "S",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Purchase Order Bulan " . date("n") . " Tahun " . date("Y"),
                "userid_updt" => $idUser
            ]);
            $kode = "S0000" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);

            $dataPemesanan = [
                ...$dataPemesanan,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        $dataDetailPemesanan = [];
        foreach ($daftarIdKatalog as $i => $idKatalog) {
            if (!$daftarJumlahKemasan[$i]) continue;
            $dataDetailPemesanan[$idKatalog] = [
                "kode_reff" => $kode,
                "kode_reffro" => $daftarKodeRefRo[$i],
                "kode_reffpl" => $daftarKodeRefPl[$i],
                "kode_reffrenc" => $daftarKodeRefRencana[$i],
                "id_katalog" => $idKatalog,
                "id_reffkatalog" => $daftarIdRefKatalog[$i],
                "kemasan" => $daftarKemasan[$i],
                "id_pabrik" => $daftarIdPabrik[$i],
                "id_kemasan" => $daftarIdKemasan[$i],
                "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$i]),
                "id_kemasandepo" => $daftarIdKemasanDepo[$i],
                "jumlah_item" => $toSystemNumber($daftarJumlahItem[$i]),
                "jumlah_kemasan" => $toSystemNumber($daftarJumlahKemasan[$i]),
                "harga_item" => $toSystemNumber($daftarHargaItem[$i]),
                "harga_kemasan" => $toSystemNumber($daftarHargaKemasan[$i]),
                "diskon_item" => $toSystemNumber($daftarDiskonItem[$i]),
                "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$i]),
                "userid_updt" => $idUser,
            ];
        }

        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        $daftarField = array_keys($dataPemesanan);
        $fm = new FarmasiModel;
        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_pemesanan
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $kode];
            $adaPemesanan = $connection->createCommand($sql, $params)->queryScalar();
            if (!$adaPemesanan) throw new DataAlreadyExistException("Pemesanan", "Kode", $kode, $transaction);

            $berhasilTambah = $fm->saveData("transaksif_pemesanan", $daftarField, $dataPemesanan);
            if (!$berhasilTambah) throw new FailToInsertException("Pemesanan", $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_perencanaan
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefRo];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Perencanaan", "Kode", $kodeRefRo, $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_pemesanan", $dataDetailPemesanan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Pemesanan", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_pemesanan", $daftarField, $dataPemesanan, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kode, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_perencanaan
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefRo];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Perencanaan", "kode", $kodeRefRo, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_pemesanan", $dataDetailPemesanan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Pemesanan", "Kode Ref", $kode, $transaction);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#edit    the original method
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
                A.tgl_tempokirim    AS tanggalTempoKirim,
                A.kode_reffro       AS kodeRefRo,
                A.kode_reffpl       AS kodeRefPl,
                A.kode_reffrenc     AS kodeRefRencana,
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
                A.sts_saved         AS statusSaved,
                A.sts_linked        AS statusLinked,
                A.sts_revisi        AS statusRevisi,            -- in use
                A.sysdate_rev       AS sysdateRevisi,
                A.keterangan_rev    AS keteranganRevisi,
                A.sts_closed        AS statusClosed,
                A.sysdate_cls       AS sysdateClosed,
                A.sts_deleted       AS statusDeleted,
                A.sysdate_del       AS sysdateDeleted,
                A.ver_revisi        AS verRevisi,
                A.ver_usrrevisi     AS verUserRevisi,
                A.ver_tglrevisi     AS verTanggalRevisi,
                A.userid_in         AS useridInput,
                A.sysdate_in        AS sysdateInput,
                A.userid_updt       AS useridUpdate,
                A.sysdate_updt      AS sysdateUpdate,
                B.no_doc            AS noSpk,
                B.tgl_doc           AS tanggalMulai,
                B.tgl_jatuhtempo    AS tanggalJatuhTempo,
                C.nama_pbf          AS namaPemasok,
                C.kode              AS kodePemasok,
                B.blnakhir_anggaran AS bulanAkhirAnggaranPl,
                B.blnawal_anggaran  AS bulanAwalAnggaranPl,
                B.thn_anggaran      AS tahunAnggaranPl,
                D.subjenis_anggaran AS subjenisAnggaran,
                E.no_doc            AS noRencana,
                F.jenis_harga       AS jenisHarga,
                E.blnawal_anggaran  AS bulanAwalAnggaranRo,
                E.blnakhir_anggaran AS bulanAkhirAnggaranRo,
                E.thn_anggaran      AS tahunAnggaranRo,
                NULL                AS daftarDetailPemesanan
            FROM db1.transaksif_pemesanan AS A
            LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reffpl = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_subjenisanggaran AS D ON B.id_jenisanggaran = D.id
            LEFT JOIN db1.transaksif_perencanaan AS E ON A.kode_reffro = E.kode
            LEFT JOIN db1.masterf_jenisharga AS F ON A.id_jenisharga = F.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pemesanan = $connection->createCommand($sql, $params)->queryOne();
        if (!$pemesanan) throw new DataNotExistException($kode);
        if ($pemesanan->statusRevisi == 1) throw new \Exception("Ada Revisi Pada PL / Perencanaan Repeate Order. Silahkan periksa perubahan terhadap SP/SPK/Kontrak atau perencanaan tersebut, dan lakukan verifikasi Revisi");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                  AS kodeRef,
                A.id_katalog                 AS idKatalog,
                A.kode_reffrenc              AS kodeRefRencana,
                A.kode_reffpl                AS kodeRefPl,
                A.kode_reffro                AS kodeRefRo,
                A.id_reffkatalog             AS idRefKatalog,
                A.kemasan                    AS kemasan,
                A.id_pabrik                  AS idPabrik,
                A.id_kemasan                 AS idKemasan,
                A.isi_kemasan                AS isiKemasan,
                A.id_kemasandepo             AS idKemasanDepo,
                A.jumlah_item                AS jumlahItem,
                A.jumlah_kemasan             AS jumlahKemasan,
                A.jumlah_realisasi           AS jumlahRealisasi,
                A.harga_item                 AS hargaItem,
                A.harga_kemasan              AS hargaKemasan,
                A.diskon_item                AS diskonItem,
                A.diskon_harga               AS diskonHarga,
                A.keterangan                 AS keterangan,
                A.userid_updt                AS useridUpdate,
                A.sysdate_updt               AS sysdateUpdate,
                K.nama_sediaan               AS namaSediaan,
                K.id_pabrik                  AS idPabrik,
                K.kemasan                    AS kemasanKat,
                K.id_kemasanbesar            AS idKemasanKat,
                K.isi_kemasan                AS isiKemasanKat,
                K.id_kemasankecil            AS idKemasanDepoKat,
                K.harga_beli                 AS hargaItemKat,
                K.harga_beli * K.isi_kemasan AS hargaKemasanKat,
                K.diskon_beli                AS diskonItemKat,
                PBK.nama_pabrik              AS namaPabrik,
                K1.kode                      AS satuanJual,
                K2.kode                      AS satuan,
                K3.kode                      AS satuanJualKat,
                K4.kode                      AS satuanKat,
                IFNULL(B.jumlah_item, 0)     AS jumlahPl,
                IFNULL(trm.jumlah_item, 0)   AS jumlahTerima,
                A.jumlah_item                AS jumlahDo,
                IFNULL(R.jumlah_item, 0)     AS jumlahRencana,
                0                            AS jumlahRetur,
                IFNULL(Ro.jumlah_item, 0)    AS jumlahRo,
                H.jumlah_item                AS jumlahHps,
                Rt.jumlah_item               AS jumlahRetur
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS K1 ON K1.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS K2 ON K2.id = A.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS K3 ON K3.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS K4 ON K4.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_perencanaan AS Ro ON A.kode_reffro = Ro.kode_reff
            LEFT JOIN db1.tdetailf_pembelian AS B ON A.kode_reffpl = B.kode_reff
            LEFT JOIN db1.tdetailf_pengadaan AS H ON B.kode_reffhps = H.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS R ON B.kode_reffrenc = R.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpo = :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS trm ON A.kode_reff = trm.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpo = :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Rt ON A.kode_reff = Rt.kode_reffpo
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = Rt.id_katalog
                AND A.id_katalog = trm.id_katalog
                AND B.id_reffkatalog = R.id_katalog
                AND B.id_reffkatalog = H.id_reffkatalog
                AND A.id_katalog = B.id_katalog
                AND A.id_katalog = Ro.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $pemesanan->daftarDetailPemesanan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($pemesanan);
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#addrevisi    the original method
     */
    public function actionSaveRevisiPl(): void
    {
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        [   "no_doc" => $noDokumen,
            "kode" => $kode,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "id_pbf" => $idPemasok,
            "ppn" => $ppn,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "keterangan" => $keterangan,
            "ver_revisi" => $verRevisi,
            "ver_usrrevisi" => $verUserRevisi,
            "ver_tglrevisi" => $verTanggalRevisi,
            "revisike" => $revisiKe,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "kemasan" => $daftarKemasan,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "diskon_harga" => $daftarDiskonHarga,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "harga_kemasan" => $daftarHargaKemasan,
            "harga_item" => $daftarHargaItem,
            "diskon_item" => $daftarDiskonItem,
        ] = Yii::$app->request->post();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpemesanan
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Detail Revisi Pemesanan", $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_revpemesanan
            SELECT *
            FROM db1.transaksif_pemesanan
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Revisi Pemesanan", $transaction);

        // saat revisi, diset close sementara, dan update revisi ke
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pemesanan
            SET
                revisike = revisike + 1,
                keterangan = :keterangan,
                ver_revisi = 0,
                ver_usrrevisi = NULL,
                ver_tglrevisi = NULL,
                userid_updt = :idUser,
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi. Lakukan verifikasi revisi untuk bisa menggunakan Dokumen ini.'
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":keterangan" => $keterangan, ":idUser" => $idUser, ":tanggalRevisi" => $nowValSystem, ":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Pemesanan", $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_penerimaan
            SET
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi DO/PO Pemesanan. Cek Revisi Penerimaan dan Lakukan verifikasi revisi Penerimaan untuk bisa menggunakan Dokumen ini kembali.'
            WHERE
                kode_reffpo = :kode
                AND sts_deleted = 0
        ";
        $params = [":tanggalRevisi" => $nowValSystem, ":kode" => $kode];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff        AS kodeRef,
                A.id_katalog       AS idKatalog,      -- in use
                A.kode_reffrenc    AS kodeRefRencana,
                A.kode_reffpl      AS kodeRefPl,
                A.kode_reffro      AS kodeRefRo,
                A.id_reffkatalog   AS idRefKatalog,
                A.kemasan          AS kemasan,
                A.id_pabrik        AS idPabrik,
                A.id_kemasan       AS idKemasan,
                A.isi_kemasan      AS isiKemasan,      -- in use
                A.id_kemasandepo   AS idKemasanDepo,
                A.jumlah_item      AS jumlahItem,
                A.jumlah_kemasan   AS jumlahKemasan,
                A.jumlah_realisasi AS jumlahRealisasi,
                A.harga_item       AS hargaItem,       -- in use
                A.harga_kemasan    AS hargaKemasan,    -- in use
                A.diskon_item      AS diskonItem,      -- in use
                A.diskon_harga     AS diskonHarga,
                A.keterangan       AS keterangan,
                A.userid_updt      AS useridUpdate,
                A.sysdate_updt     AS sysdateUpdate,
                B.nama_sediaan     AS namaSediaan      -- in use
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailPemesanan = $connection->createCommand($sql, $params)->queryAll();

        $tempDetailPemesanan = [];
        foreach ($daftarIdKatalog as $i => $idkatalog) {
            $tempDetailPemesanan[$idkatalog] = [
                "kemasan" => $daftarKemasan[$i],
                "id_kemasan" => $daftarIdKemasan[$i],
                "id_kemasandepo" => $daftarIdKemasanDepo[$i],
                "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$i]),
                "jumlah_kemasan" => $toSystemNumber($daftarJumlahKemasan[$i]),
                "jumlah_item" => $toSystemNumber($daftarJumlahItem[$i]),
                "harga_kemasan" => $toSystemNumber($daftarHargaKemasan[$i]),
                "harga_item" => $toSystemNumber($daftarHargaItem[$i]),
                "diskon_item" => $toSystemNumber($daftarDiskonItem[$i]),
                "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$i]),
            ];
        }
        $fieldDetailPemesanan = array_keys($tempDetailPemesanan[0] ?? []);

        $allDelta = "";
        if ($tempDetailPemesanan) {
            foreach ($daftarDetailPemesanan as $i => $d) {
                $dataDetailPemesanan = $tempDetailPemesanan[$d->idKatalog];
                [   "isi_kemasan" => $xIsiKemasan,
                    "harga_kemasan" => $xHargaKemasan,
                    "harga_item" => $xHargaItem,
                    "diskon_item" => $xDiskonItem,
                ] = $dataDetailPemesanan;

                $kondisi1 = $xIsiKemasan == $d->isiKemasan;
                $kondisi2 = $xHargaKemasan == $d->hargaKemasan;
                $kondisi3 = $xHargaItem == $d->hargaItem;
                $kondisi4 = $xDiskonItem != $d->diskonItem;
                if ($kondisi1 && $kondisi2 && $kondisi3 && $kondisi4) continue;

                $delta = "";
                $delta .= $kondisi1 ? "" : "isi {$d->isiKemasan} -> $xIsiKemasan, ";
                $delta .= $kondisi2 ? "" : "harga kemasan {$d->hargaKemasan} -> $xHargaKemasan, ";
                $delta .= $kondisi3 ? "" : "harga item {$d->hargaItem} -> $xHargaItem, ";
                $delta .= $kondisi4 ? "" : "diskon {$d->diskonItem} -> $xDiskonItem, ";

                $iwhere = ["kode_reff" => $kode, "id_katalog" => $d->idKatalog];
                $berhasilUbah = $fm->saveData("tdetailf_pemesanan", $fieldDetailPemesanan, $dataDetailPemesanan, $iwhere);
                if ($berhasilUbah) throw new FailToUpdateException("Detail Pemesanan", "Kode Ref", $kode, $transaction);

                $delta = "{$d->namaSediaan}: $delta |";
                $allDelta .= $delta;
            }
        }
        $keterangan = addslashes($keterangan . " || Perubahan Nilai: " . $allDelta);

        $dataPemesanan = [
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_jenisharga" => $idJenisHarga,
            "id_carabayar" => $idCaraBayar,
            "ppn" => $ppn ?? 0,
            "keterangan" => $keterangan,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
        ];

        if ($verRevisi == 1) {
            $dataPemesanan = [
                ...$dataPemesanan,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => null,
                "ver_revisi" => $verRevisi,
                "ver_usrrevisi" => $idUser,
                "ver_tglrevisi" => $nowValSystem,
            ];
        }

        // penyimpanan
        $daftarFieldPemesanan = array_keys($dataPemesanan);
        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_pemesanan", $daftarFieldPemesanan, $dataPemesanan, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.transaksi_notification (
                id_notif,         -- 1
                id_fromSatker,    -- 2
                id_toUser,        -- 3
                tgl_notif,        -- 4
                tipe_notif,       -- 5
                kode_reff,        -- 6
                nodoc_reff,       -- 7
                modul_reff,       -- 8
                info_reff,        -- 9
                description_reff, -- 10
                point_ofview,     -- 11
                verif_reff, modul_exec, action_exec, ver_execution, ver_usrexecution, ver_tglexecution, -- 12
                userid_in         -- 13
            )
            SELECT
                IFNULL(MAX(id_notif), 0) + 1, -- 1
                '0003',                       -- 2
                0,                            -- 3
                :tanggalNotifikasi,           -- 4
                'R',                          -- 5
                :kodeRef,                     -- 6
                :noDokumenRef,                -- 7
                'pemesanan',                  -- 8
                :infoRef,                     -- 9
                :descriptionRef,              -- 10
                :pointOfView,                 -- 11
                1, 'pemesanan', 'addrevisi', 1, :verUserRevisi, :verTanggalRevisi, -- 12
                :idUser                       -- 13
            FROM db1.transaksi_notification
        ";
        $params = [
            ":tanggalNotifikasi" => $nowValSystem,
            ":kodeRef" => $kode,
            ":noDokumenRef" => $noDokumen,
            ":infoRef" => $revisiKe,
            ":descriptionRef" => $keterangan,
            // TODO: php: truely missing var: $retval
            ":pointOfView" => "ULP - Revisi DO/PO " . $retval[0]["no_doc"],
            ":idUser" => $idUser,
        ];

        ($verRevisi == 1)
            ? $params = [...$params, ":verUserRevisi" => $verUserRevisi, ":verTanggalRevisi" => $verTanggalRevisi]
            : $sql = preg_replace("/^.+ -- 12 */m", "", $sql);

        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Notifikasi", $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#addrevisi    the original method
     */
    public function actionSaveRevisiDokumen(): void
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        [   "no_doc" => $noDokumen,
            "kode" => $kode,
            "tgl_doc" => $tanggalDokumen,
            "tgl_tempokirim" => $tanggalTempoKirim,
            "keterangan" => $keterangan,
            "ver_revisi" => $verRevisi,
            "ver_usrrevisi" => $verUserRevisi,
            "ver_tglrevisi" => $verTanggalRevisi,
            "revisike" => $revisiKe,
        ] = Yii::$app->request->post();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpemesanan
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Detail Revisi Pemesanan", $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_revpemesanan
            SELECT *
            FROM db1.transaksif_pemesanan
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Revisi Pemesanan", $transaction);

        // saat revisi, diset close sementara, dan update revisi ke
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pemesanan
            SET
                revisike = revisike + 1,
                keterangan = :keterangan,
                ver_revisi = 0,
                ver_usrrevisi = NULL,
                ver_tglrevisi = NULL,
                userid_updt = :idUser,
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi. Lakukan verifikasi revisi untuk bisa menggunakan Dokumen ini.'
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":keterangan" => $keterangan, ":idUser" => $idUser, ":tanggalRevisi" => $nowValSystem, ":kode" => $kode];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kode, $transaction);

        $tanggalDokumen = $toSystemDate($tanggalDokumen);
        $tanggalTempoKirim = $toSystemDate($tanggalTempoKirim);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                no_doc         AS noDokumen,
                tgl_doc        AS tanggalDokumen,
                tgl_tempokirim AS tanggalTempoKirim
            FROM db1.transaksif_pemesanan
            WHERE kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pemesanan = $connection->createCommand($sql, $params)->queryOne();

        $kondisi1 = $noDokumen == $pemesanan->noDokumen;
        $kondisi2 = $tanggalDokumen == $pemesanan->tanggalDokumen;
        $kondisi3 = $tanggalTempoKirim == $pemesanan->tanggalTempoKirim;
        if ($kondisi1 && $kondisi2 && $kondisi3) return;

        $dataPemesanan =[
            "tgl_tempokirim" => $tanggalTempoKirim,
            "tgl_doc" => $tanggalDokumen,
            "no_doc" => $noDokumen,
        ];

        $delta = "";
        $delta .= $kondisi3 ? "" : "Tanggal Tempo Kirim DO: $tanggalTempoKirim => {$pemesanan->tanggalTempoKirim} | ";
        $delta .= $kondisi2 ? "" : "Tanggal DO: $tanggalDokumen => {$pemesanan->tanggalDokumen} | ";
        $delta .= $kondisi1 ? "" : "No Dokumen DO: $noDokumen => {$pemesanan->noDokumen}";
        $keterangan = " Revisi DO/PO Perubahan Dokumen: " . $delta;

        if ($verRevisi == 1) {
            $dataPemesanan = [
                ...$dataPemesanan,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => null,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $idUser,
                "ver_tglrevisi" => $nowValSystem,
            ];
        }

        // penyimpanan
        $daftarFieldPemesanan = array_keys($dataPemesanan);
        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_pemesanan", $daftarFieldPemesanan, $dataPemesanan, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.transaksi_notification (
                id_notif,         -- 1
                id_fromSatker,    -- 2
                id_toUser,        -- 3
                tgl_notif,        -- 4
                tipe_notif,       -- 5
                kode_reff,        -- 6
                nodoc_reff,       -- 7
                modul_reff,       -- 8
                info_reff,        -- 9
                description_reff, -- 10
                point_ofview,     -- 11
                verif_reff, modul_exec, action_exec, ver_execution, ver_usrexecution, ver_tglexecution, -- 12
                userid_in         -- 13
            )
            SELECT
                IFNULL(MAX(id_notif), 0) + 1, -- 1
                '0003',                       -- 2
                0,                            -- 3
                :tanggalNotifikasi,           -- 4
                'R',                          -- 5
                :kodeRef,                     -- 6
                :noDokumenRef,                -- 7
                'pemesanan',                  -- 8
                :infoRef,                     -- 9
                :descriptionRef,              -- 10
                :pointOfView,                 -- 11
                1, 'pemesanan', 'addrevisi', 1, :verUserRevisi, :verTanggalRevisi, -- 12
                :idUser                       -- 13
            FROM db1.transaksi_notification
        ";
        $params = [
            ":tanggalNotifikasi" => $nowValSystem,
            ":kodeRef" => $kode,
            ":noDokumenRef" => $noDokumen,
            ":infoRef" => $revisiKe,
            ":descriptionRef" => $keterangan,
            // TODO: php: truely missing var: $retval
            ":pointOfView" => "ULP - Revisi DO/PO " . $retval[0]["no_doc"],
            ":idUser" => $idUser,
        ];

        ($verRevisi == 1)
            ? $params = [...$params, ":verUserRevisi" => $verUserRevisi, ":verTanggalRevisi" => $verTanggalRevisi]
            : $sql = preg_replace("/^.+ -- 12 *$/m", "", $sql);

        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Notifikasi", $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#addrevisi    the original method
     */
    public function actionSaveRevisiJumlah(): void
    {
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        [   "no_doc" => $noDokumen,
            "kode" => $kode,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "keterangan" => $keterangan,
            "ver_revisi" => $verRevisi,
            "ver_usrrevisi" => $verUserRevisi,
            "ver_tglrevisi" => $verTanggalRevisi,
            "revisike" => $revisiKe,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "diskon_harga" => $daftarDiskonHarga,
        ] = Yii::$app->request->post();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpemesanan
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Detail Revisi Pemesanan", $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_revpemesanan
            SELECT *
            FROM db1.transaksif_pemesanan
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Revisi Pemesanan", $transaction);

        // saat revisi, diset close sementara, dan update revisi ke
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pemesanan
            SET
                revisike = revisike + 1,
                keterangan = :keterangan,
                ver_revisi = 0,
                ver_usrrevisi = NULL,
                ver_tglrevisi = NULL,
                userid_updt = :idUser,
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi. Lakukan verifikasi revisi untuk bisa menggunakan Dokumen ini.'
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":keterangan" => $keterangan, ":idUser" => $idUser, ":tanggalRevisi" => $nowValSystem, ":kode" => $kode];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog     AS idKatalog,     -- in use
                A.kode_reff      AS kodeRef,
                A.jumlah_item    AS jumlahItem,    -- in use
                A.jumlah_kemasan AS jumlahKemasan, -- in use
                A.diskon_harga   AS diskonHarga,
                B.nama_sediaan   AS namaSediaan    -- in use
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            WHERE A.kode_reff = :kode
        ";
        $params = [":kode" => $kode];
        $daftarDetailPemesanan = $connection->createCommand($sql, $params)->queryAll();

        $tempDetailPemesanan = [];
        foreach ($daftarIdKatalog as $i => $idkatalog) {
            $tempDetailPemesanan[$idkatalog] = [
                "jumlah_kemasan" => $toSystemNumber($daftarJumlahKemasan[$i]),
                "jumlah_item" => $toSystemNumber($daftarJumlahItem[$i]),
                "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$i]),
            ];
        }
        $fieldDetailPemesanan = array_keys($tempDetailPemesanan[0] ?? []);

        foreach ($daftarDetailPemesanan as $i => $d) {
            $dataDetailPemesanan = $tempDetailPemesanan[$d->idKatalog];
            ["jumlah_kemasan" => $jKemasan, "jumlah_item" => $jItem] = $dataDetailPemesanan;
            if ($d->jumlahKemasan == $jKemasan && $d->jumlahItem == $jItem) continue;

            $iwhere = ["kode_reff" => $kode, "id_katalog" => $d->idKatalog];
            $berhasilUbah = $fm->saveData("tdetailf_pemesanan", $fieldDetailPemesanan, $dataDetailPemesanan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Pemesanan", "Kode Ref", $kode, $transaction);

            $delta = $d->jumlahKemasan == $jKemasan  ?  "{$d->jumlahItem} -> $jItem"  :  "{$d->jumlahKemasan} -> $jKemasan";
            $keterangan .= " {$d->namaSediaan}: $delta | ";
        }

        $dataPemesanan = [
            "keterangan" => $keterangan,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
        ];

        // set Verifikasi Revisi
        if ($verRevisi == 1) {
            $dataPemesanan = [
                ...$dataPemesanan,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => null,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $idUser,
                "ver_tglrevisi" => $nowValSystem,
            ];
        }

        // penyimpanan
        $daftarFieldPemesanan = array_keys($dataPemesanan);
        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_pemesanan", $daftarFieldPemesanan, $dataPemesanan, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kode, $transaction);

        // Simpan Ke Notification
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.transaksi_notification (
                id_notif,           -- 1
                id_fromSatker,      -- 2
                id_toUser,          -- 3
                tgl_notif,          -- 4
                tipe_notif,         -- 5
                kode_reff,          -- 6
                nodoc_reff,         -- 7
                modul_reff,         -- 8
                info_reff,          -- 9
                description_reff,   -- 10
                point_ofview,       -- 11
                verif_reff, modul_exec, action_exec, ver_execution, ver_usrexecution, ver_tglexecution, -- 12
                userid_in           -- 13
            )
            SELECT
                IFNULL(MAX(id_notif), 0) + 1, -- 1
                '0003',                       -- 2
                0,                            -- 3
                :tanggalNotifikasi,           -- 4
                'R',                          -- 5
                :kodeRef,                     -- 6
                :noDokumenRef,                -- 7
                'pemesanan',                  -- 8
                :infoRef,                     -- 9
                :descriptionRef,              -- 10
                :pointOfView,                 -- 11
                1, 'pemesanan', 'addrevisi', 1, :verUserRevisi, :verTanggalRevisi, -- 12
                :idUser                       -- 13
            FROM db1.transaksi_notification
        ";
        $params = [
            ":tanggalNotifikasi" => $nowValSystem,
            ":kodeRef" => $kode,
            ":noDokumenRef" => $noDokumen,
            ":infoRef" => $revisiKe,
            ":descriptionRef" => $keterangan,
            // TODO: php: truely missing var: $retval
            ":pointOfView" => "ULP - Revisi DO/PO " . $retval[0]["no_doc"],
            ":idUser" => $idUser,
        ];

        ($verRevisi == 1)
            ? $params = [...$params, ":verUserRevisi" => $verUserRevisi, ":verTanggalRevisi" => $verTanggalRevisi]
            : $sql = preg_replace("/^.+ -- 12 *$/m", "", $sql);

        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Notifikasi", $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#addrevisi    the original method
     */
    public function actionFormRevisiData(): string
    {
        ["kode" => $kode, "tipe" => $revisi] = Yii::$app->request->post();
        // $revisi: "revisi_pl", "revisi_jumlah", or "revisi_dokumen"
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode              AS kode,
                A.revisike          AS revisiKe,
                A.no_doc            AS noDokumen,
                A.tgl_doc           AS tanggalDokumen,
                A.tgl_tempokirim    AS tanggalTempoKirim,
                A.kode_reffpl       AS kodeRefPl,          -- in use
                A.kode_reffro       AS kodeRefRo,          -- in use
                A.id_pbf            AS idPemasokOri,
                A.id_jenisanggaran  AS idJenisAnggaranOri,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.thn_anggaran      AS tahunAnggaran,
                A.id_sumberdana     AS idSumberDanaOri,
                A.id_jenisharga     AS idJenisHargaOri,
                A.id_carabayar      AS idCaraBayarOri,
                A.ppn               AS ppnOri,
                B.no_doc            AS noSpk,
                B.tgl_doc           AS tanggalMulai,
                B.tgl_jatuhtempo    AS tanggalJatuhTempo,
                B.id_pbf            AS idPemasok,
                B.id_jenisanggaran  AS idJenisAnggaran,
                B.id_sumberdana     AS idSumberDana,
                B.id_jenisharga     AS idJenisHarga,
                B.id_carabayar      AS idCaraBayar,
                B.ppn               AS ppn,
                C.nama_pbf          AS namaPemasok,
                C.kode              AS kodePemasok,
                D.subjenis_anggaran AS subjenisAnggaran,
                E.no_doc            AS noRencana,
                F.jenis_harga       AS jenisHarga,
                Ca.nama_pbf         AS namaPemasokOri,
                G.cara_bayar        AS caraBayar,
                H.sumber_dana       AS sumberDana
            FROM db1.transaksif_pemesanan AS A
            LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reffpl = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_pbf AS Ca ON A.id_pbf = Ca.id
            LEFT JOIN db1.masterf_subjenisanggaran AS D ON B.id_jenisanggaran = D.id
            LEFT JOIN db1.transaksif_perencanaan AS E ON A.kode_reffro = E.kode
            LEFT JOIN db1.masterf_sumberdana AS H ON A.id_sumberdana = H.id
            LEFT JOIN db1.masterf_jenisharga AS F ON B.id_jenisharga = F.id
            LEFT JOIN db1.masterf_carabayar AS G ON B.id_carabayar = G.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 1
                AND A.sts_deleted = 0
                AND (
                    :revisi = 'revisi_jumlah'
                    OR :revisi = 'revisi_dokumen'
                    OR (:revisi = 'revisi_pl' AND A.sts_revisi = 1 AND E.ver_revisi = 1)
                )
            LIMIT 1
        ";
        $params = [":kode" => $kode, ":revisi" => $revisi];
        $pemesanan = $connection->createCommand($sql, $params)->queryOne();
        if (!$pemesanan) throw new Exception($kode, $revisi);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                  AS kodeRef,
                A.id_katalog                 AS idKatalog,
                A.kode_reffrenc              AS kodeRefRencana,
                A.kode_reffpl                AS kodeRefPl,
                A.kode_reffro                AS kodeRefRo,
                A.id_reffkatalog             AS idRefKatalog,
                A.kemasan                    AS kemasan,
                A.id_pabrik                  AS idPabrik,
                A.id_kemasan                 AS idKemasan,
                A.isi_kemasan                AS isiKemasan,
                A.id_kemasandepo             AS idKemasanDepo,
                A.jumlah_item                AS jumlahItem,
                A.jumlah_kemasan             AS jumlahKemasan,
                A.jumlah_realisasi           AS jumlahRealisasi,
                A.harga_item                 AS hargaItem,
                A.harga_kemasan              AS hargaKemasan,
                A.diskon_item                AS diskonItem,
                A.diskon_harga               AS diskonHarga,
                A.keterangan                 AS keterangan,
                A.userid_updt                AS useridUpdate,
                A.sysdate_updt               AS sysdateUpdate,
                K.nama_sediaan               AS namaSediaan,
                K.id_pabrik                  AS idPabrik,
                K.kemasan                    AS kemasanKat,
                K.id_kemasanbesar            AS idKemasanKat,
                K.isi_kemasan                AS isiKemasanKat,
                K.id_kemasankecil            AS idKemasanDepoKat,
                K.harga_beli                 AS hargaItemKat,
                K.harga_beli * K.isi_kemasan AS hargaKemasanKat,
                K.diskon_beli                AS diskonItemKat,
                PBK.nama_pabrik              AS namaPabrik,
                K1.kode                      AS satuanJual,
                K2.kode                      AS satuan,
                K3.kode                      AS satuanJualKat,
                K4.kode                      AS satuanKat,
                IFNULL(B.jumlah_item, 0)     AS jumlahPl,
                IFNULL(trm.jumlah_item, 0)   AS jumlahTerima,
                A.jumlah_item                AS jumlahDo,
                IFNULL(R.jumlah_item, 0)     AS jumlahRencana,
                0                            AS jumlahRetur,
                IFNULL(Ro.jumlah_item, 0)    AS jumlahRo,
                H.jumlah_item                AS jumlahHps,
                Rt.jumlah_item               AS jumlahRetur
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS K1 ON K1.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS K2 ON K2.id = A.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS K3 ON K3.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS K4 ON K4.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_perencanaan AS Ro ON A.kode_reffro = Ro.kode_reff
            LEFT JOIN db1.tdetailf_pembelian AS B ON A.kode_reffpl = B.kode_reff
            LEFT JOIN db1.tdetailf_pengadaan AS H ON B.kode_reffhps = H.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS R ON B.kode_reffrenc = R.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpo = :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS trm ON A.kode_reff = trm.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpo = :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Rt ON A.kode_reff = Rt.kode_reffpo
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = Rt.id_katalog
                AND A.id_katalog = trm.id_katalog
                AND B.id_reffkatalog = R.id_katalog
                AND B.id_reffkatalog = H.id_reffkatalog
                AND A.id_katalog = B.id_katalog
                AND A.id_katalog = Ro.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $daftarDetailPemesanan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff      AS kodeRef,
                A.id_katalog     AS idKatalog,
                A.nama_generik   AS namaGenerik,
                A.kode_reffpl    AS kodeRefPl,
                A.kode_reffrenc  AS kodeRefRencana,
                A.id_reffkatalog AS idRefKatalog,
                A.kemasan        AS kemasan,
                A.id_pabrik      AS idPabrik,
                A.id_kemasan     AS idKemasan,
                A.isi_kemasan    AS isiKemasan,
                A.id_kemasandepo AS idKemasanDepo,
                A.jumlah_item    AS jumlahItem,
                A.jumlah_kemasan AS jumlahKemasan,
                A.harga_item     AS hargaItem,
                A.harga_kemasan  AS hargaKemasan,
                A.diskon_item    AS diskonItem,
                A.diskon_harga   AS diskonHarga,
                A.keterangan     AS keterangan,
                A.userid_updt    AS useridUpdate,
                A.sysdate_updt   AS sysdateUpdate,
                Sj.kode          AS satuanJual,
                S.kode           AS satuan
            FROM db1.tdetailf_perencanaan AS A
            LEFT JOIN db1.masterf_kemasan AS Sj ON A.id_kemasan = Sj.id
            LEFT JOIN db1.masterf_kemasan AS S ON A.id_kemasandepo = S.id
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $pemesanan->kodeRefRo];
        $daftarDetailX1 = $connection->createCommand($sql, $params)->queryAll();

        if (!$daftarDetailX1) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode_reff      AS kodeRef,
                    A.id_katalog     AS idKatalog,      -- in use
                    A.kode_reffrenc  AS kodeRefRencana,
                    A.kode_reffhps   AS kodeRefHps,
                    A.id_reffkatalog AS idRefKatalog,
                    A.no_urut        AS noUrut,
                    A.kemasan        AS kemasan,
                    A.id_pabrik      AS idPabrik,
                    A.id_kemasan     AS idKemasan,
                    A.isi_kemasan    AS isiKemasan,
                    A.id_kemasandepo AS idKemasanDepo,
                    A.jumlah_item    AS jumlahItem,
                    A.jumlah_kemasan AS jumlahKemasan,
                    A.harga_item     AS hargaItem,
                    A.harga_kemasan  AS hargaKemasan,
                    A.diskon_item    AS diskonItem,
                    A.diskon_harga   AS diskonHarga,
                    A.sts_iclosed    AS statusIclosed,
                    A.sysdate_icls   AS sysdateIclosed,
                    A.keterangan     AS keterangan,
                    A.userid_updt    AS useridUpdate,
                    A.sysdate_updt   AS sysdateUpdate,
                    Sj.kode          AS satuanJual,
                    S.kode           AS satuan
                FROM db1.tdetailf_pembelian AS A
                LEFT JOIN db1.masterf_kemasan AS Sj ON A.id_kemasan = Sj.id
                LEFT JOIN db1.masterf_kemasan AS S ON A.id_kemasandepo = S.id
                WHERE A.kode_reff = :kodeRef
            ";
            $params = [":kodeRef" => $pemesanan->kodeRefPl];
            $daftarDetailX1 = $connection->createCommand($sql, $params)->queryAll();
        }

        $daftarDetailX2 = [];
        array_walk($daftarDetailX1, fn($item) => $daftarDetailX2[$item->idKatalog] = $item);

        return json_encode([
            "action" => $revisi,
            "pemesanan2" => $pemesanan,
            "daftarDetailPemesanan2" => $daftarDetailPemesanan,
            "daftarDetailPembelian2" => $daftarDetailX2,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#views    the original method
     */
    public function actionViewData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kodePemesanan,
                A.no_doc               AS noDokumenPemesanan,
                A.tgl_doc              AS tanggalDokumenPemesanan,
                A.tgl_tempokirim       AS tanggalTempoKirim,
                A.sts_linked           AS statusLinked,
                A.sts_closed           AS statusClosed,
                A.blnawal_anggaran     AS bulanAwalAnggaran,
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                A.thn_anggaran         AS tahunAnggaran,
                A.ppn                  AS ppn,
                B.subjenis_anggaran    AS subjenisAnggaran,
                C.sumber_dana          AS sumberDana,
                D.subsumber_dana       AS subsumberDana,
                E.jenis_harga          AS jenisHarga,
                F.cara_bayar           AS caraBayar,
                IFNULL(G.no_doc, '-')  AS noSpk,
                IFNULL(H.nama_pbf, '') AS namaPemasok,
                I.no_doc               AS noDokumenPerencanaan,
                A.nilai_pembulatan     AS nilaiPembulatan,
                A.sts_revisi           AS statusRevisi,
                NULL                   AS daftarDetailPemesanan
            FROM db1.transaksif_pemesanan AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.transaksif_pembelian AS G ON A.kode_reffpl = G.kode
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.transaksif_perencanaan AS I ON A.kode_reffrenc = I.kode
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pemesanan = $connection->createCommand($sql, $params)->queryOne();
        if (!$pemesanan) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog              AS idKatalog,
                A.kode_reffrenc           AS kodeRefRencana,
                A.kemasan                 AS kemasan,
                A.isi_kemasan             AS isiKemasan,
                A.jumlah_kemasan          AS jumlahKemasan,
                A.harga_item              AS hargaItem,
                A.harga_kemasan           AS hargaKemasan,
                A.diskon_item             AS diskonItem,
                A.diskon_harga            AS diskonHarga,
                KAT.nama_sediaan          AS namaSediaan,
                KAT.jumlah_itembeli       AS jumlahItemBeli,
                KAT.jumlah_itembonus      AS jumlahItemBonus,
                PBK.nama_pabrik           AS namaPabrik,
                IFNULL(P.jumlah_item, 0)  AS jumlahPl,
                IFNULL(A.jumlah_item, 0)  AS jumlahPo,
                IFNULL(T.jumlah_item, 0)  AS jumlahTerima,
                IFNULL(R.jumlah_item, 0)  AS jumlahRencana,
                IFNULL(H.jumlah_item, 0)  AS jumlahHps,
                IFNULL(Ro.jumlah_item, 0) AS jumlahRo,
                IFNULL(Rt.jumlah_item, 0) AS jumlahRetur,
                P.kode_reffhps            AS kodeRefHps
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS P ON A.kode_reffpl = P.kode_reff
            LEFT JOIN db1.tdetailf_pengadaan AS H ON P.kode_reffhps = H.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS R ON P.kode_reffrenc = R.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS Ro ON A.kode_reffro = Ro.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpo = :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS T ON A.kode_reff = T.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpo = :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Rt ON A.kode_reff = Rt.kode_reffpo
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = Rt.id_katalog
                AND A.id_katalog = T.id_katalog
                AND A.id_katalog = Ro.id_katalog
                AND P.id_reffkatalog = R.id_katalog
                AND P.id_reffkatalog = H.id_reffkatalog
                AND A.id_katalog = P.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $pemesanan->daftarDetailPemesanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($pemesanan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#prints    the original method
     */
    public function actionPrint(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                 AS kode,
                A.no_doc                               AS noDokumen,
                A.tgl_doc                              AS tanggalDokumen,
                A.tgl_tempokirim                       AS tanggalTempoKirim,
                A.sts_saved                            AS statusSaved,
                A.sts_linked                           AS statusLinked,
                A.sts_closed                           AS statusClosed,
                A.sts_deleted                          AS statusDeleted,
                A.sysdate_del                          AS sysdateDeleted,
                A.userid_updt                          AS useridUpdate,
                A.sysdate_updt                         AS sysdateUpdate,
                A.blnawal_anggaran                     AS bulanAwalAnggaran,
                A.blnakhir_anggaran                    AS bulanAkhirAnggaran,
                A.thn_anggaran                         AS tahunAnggaran,
                A.ppn                                  AS ppn,
                B.subjenis_anggaran                    AS subjenisAnggaran,
                C.sumber_dana                          AS sumberDana,
                D.subsumber_dana                       AS subsumberDana,
                E.jenis_harga                          AS jenisHarga,
                F.cara_bayar                           AS caraBayar,
                IFNULL(G.no_doc, '-')                  AS noSpk,
                IFNULL(G.tgl_doc, '0000-00-00')        AS tanggalMulai,
                IFNULL(G.tgl_jatuhtempo, '0000-00-00') AS tanggalJatuhTempo,
                IFNULL(H.nama_pbf, '')                 AS namaPemasok,
                I.no_doc                               AS noRencana,
                A.nilai_total                          AS nilaiTotal,
                A.nilai_diskon                         AS nilaiDiskon,
                A.nilai_akhir                          AS nilaiAkhir,
                A.nilai_ppn                            AS nilaiPpn,
                A.nilai_pembulatan                     AS nilaiPembulatan,
                A.sts_revisi                           AS statusRevisi,
                H.alamat                               AS alamat,
                H.fax                                  AS fax,
                H.telp                                 AS noTelefon
            FROM db1.transaksif_pemesanan AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.transaksif_pembelian AS G ON A.kode_reffpl = G.kode
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.transaksif_perencanaan AS I ON A.kode_reffrenc = I.kode
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pemesanan = $connection->createCommand($sql, $params)->queryOne();
        if (!$pemesanan) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                 AS kodeRef,
                A.id_katalog                AS idKatalog,
                A.kode_reffrenc             AS kodeRefRencana,
                A.kode_reffpl               AS kodeRefPl,
                A.kode_reffro               AS kodeRefRo,
                A.id_reffkatalog            AS idRefKatalog,
                A.kemasan                   AS kemasan,
                A.id_pabrik                 AS idPabrik,
                A.id_kemasan                AS idKemasan,
                A.isi_kemasan               AS isiKemasan,
                A.id_kemasandepo            AS idKemasanDepo,
                A.jumlah_item               AS jumlahItem,
                A.jumlah_kemasan            AS jumlahKemasan,   -- in use
                A.jumlah_realisasi          AS jumlahRealisasi,
                A.harga_item                AS hargaItem,
                A.harga_kemasan             AS hargaKemasan,    -- in use
                A.diskon_item               AS diskonItem,      -- in use
                A.diskon_harga              AS diskonHarga,
                A.keterangan                AS keterangan,
                A.userid_updt               AS useridUpdate,
                A.sysdate_updt              AS sysdateUpdate,
                KAT.nama_sediaan            AS namaSediaan,
                PBK.nama_pabrik             AS namaPabrik,
                KSAR.kode                   AS kodeKemasanBesar,  -- prev: satuanjual
                KCIL.kode                   AS kodeKemasanKecil,  -- prev: satuan
                IFNULL(pl.jumlah_item, 0)   AS jumlahPl,
                IFNULL(A.jumlah_item, 0)    AS jumlahPo,
                IFNULL(trm.jumlah_item, 0)  AS jumlahTerima,
                IFNULL(renc.jumlah_item, 0) AS jumlahRencana
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS pl ON A.kode_reffpl = pl.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS renc ON A.kode_reffrenc = renc.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpo = :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS trm ON A.kode_reff = trm.kode_reffpo
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = trm.id_katalog
                AND A.id_katalog = renc.id_katalog
                AND A.id_katalog = pl.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $daftarDetailPemesanan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 17;

        foreach ($daftarDetailPemesanan as $i => $dPemesanan) {
            $nilai = $dPemesanan->jumlahKemasan * $dPemesanan->hargaKemasan;

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "nilai" => $nilai - ($nilai * $dPemesanan->diskonItem / 100)
            ];

            $totalNilai += $nilai;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new Cetak(
            daftarHalaman:         $daftarHalaman,
            pemesanan:             $pemesanan,
            daftarDetailPemesanan: $daftarDetailPemesanan,
            jumlahHalaman:         count($daftarHalaman),
            totalNilai:            $totalNilai,
            totalPpn:              $totalNilai * $pemesanan->ppn / 100,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#reports    the original method
     */
    public function actionTablePoData(): string
    {
        ["idKatalog" => $idKatalog, "kodeRefPl" => $kodeRefPl] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT DISTINCT -- all are in use.
                A.kode_reff        AS kodeRef,
                A.kode_reffpl      AS kodeRefPl,
                A.kemasan          AS kemasan,
                A.jumlah_item      AS jumlahItem,
                A.jumlah_kemasan   AS jumlahKemasan,
                A.harga_item       AS hargaItem,
                A.harga_kemasan    AS hargaKemasan,
                A.diskon_item      AS diskonItem,
                A.diskon_harga     AS diskonHarga,
                B.sts_closed       AS statusClosed,
                B.no_doc           AS noDokumen,
                B.tgl_tempokirim   AS tanggalTempoKirim,
                C.nama_pbf         AS namaPemasok,
                E.nama_pabrik      AS namaPabrik,
                P.no_doc           AS noSpk
            FROM db1.tdetailf_pemesanan AS A
            INNER JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
            INNER JOIN db1.transaksif_pembelian AS P ON B.kode_reffpl = P.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_katalog AS D ON A.id_katalog = D.kode
            LEFT JOIN db1.masterf_pabrik AS E ON A.id_pabrik = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON A.id_kemasan = F.id
            LEFT JOIN db1.masterf_kemasan AS G ON A.id_kemasandepo = G.id
            WHERE
                B.sts_deleted = 0
                AND (:idKatalog = '' OR A.id_katalog = :idKatalog)
                AND (:kodeRefPl = '' OR A.kode_reffpl = :kodeRefPl)
            ORDER BY
                B.kode_reffpl DESC,
                B.no_doc ASC,
                B.sts_closed DESC,
                B.no_doc ASC
        ";
        $params = [":idKatalog" => $idKatalog, ":kodeRefPl" => $kodeRefPl];
        $daftarDetailPemesanan = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarDetailPemesanan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-bd0fc67
     */
    public function actionSearchJsonDetailTerima(): string
    {
        ["kode_reff_not" => $kodeRefNot, "kode_reff" => $kodeRef] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                  AS kodeRefPo,
                A.kode_reffro                AS kodeRefRo,
                A.kode_reffpl                AS kodeRefPl,
                A.kode_reffrenc              AS kodeRefRencana,
                A.id_katalog                 AS idKatalog,
                A.id_reffkatalog             AS idRefKatalog,
                '0000-00-00'                 AS tanggalKadaluarsa,
                ''                           AS noBatch,
                1                            AS noUrut,
                IFNULL(A.kemasan, 0)         AS kemasan,
                A.id_pabrik                  AS idPabrikPl,
                A.id_kemasan                 AS idKemasan,
                A.isi_kemasan                AS isiKemasan,
                A.id_kemasandepo             AS idKemasanDepo,
                A.jumlah_item                AS jumlahItem,
                A.jumlah_kemasan             AS jumlahKemasan,
                A.harga_item                 AS hargaItem,
                A.harga_kemasan              AS hargaKemasan,
                A.diskon_item                AS diskonItem,
                A.diskon_harga               AS diskonHarga,
                B.nama_sediaan               AS namaSediaan,
                B.id_pabrik                  AS idPabrik,
                B.id_kemasanbesar            AS idKemasanKat,
                B.id_kemasankecil            AS idKemasanDepoKat,
                B.kemasan                    AS kemasanKat,
                B.isi_kemasan                AS isiKemasanKat,
                B.harga_beli                 AS hargaItemKat,
                B.harga_beli * B.diskon_beli AS hargaKemasanKat,
                B.diskon_beli                AS diskonItemKat,
                B.jumlah_itembeli            AS jumlahItemBeli,
                B.jumlah_itembonus           AS jumlahItemBonus,
                C.nama_pabrik                AS namaPabrik,
                D.kode                       AS satuanJual,
                E.kode                       AS satuan,
                IFNULL(F.jumlah_item, 0)     AS jumlahPl,
                A.jumlah_item                AS jumlahPo,
                A.jumlah_item                AS jumlahDo,
                IFNULL(T.jumlah_item, 0)     AS jumlahTerima,
                Dk.kode                      AS satuanJualKat,
                Ek.kode                      AS satuanKat,
                tH.jumlah_item               AS jumlahHps,
                tR.jumlah_item               AS jumlahRencana,
                IFNULL(tRo.jumlah_item, 0)   AS jumlahRo,
                IFNULL(Rt.jumlah_item, 0)    AS jumlahRetur,
                IFNULL(Tb.jumlah_item, 0)    AS jumlahTbonus,
                IFNULL(Rtb.jumlah_item, 0)   AS jumlahReturBonus
            FROM db1.tdetailf_pemesanan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
            LEFT JOIN db1.masterf_kemasan AS E ON A.id_kemasandepo = E.id
            LEFT JOIN db1.masterf_kemasan AS Dk ON B.id_kemasanbesar = Dk.id
            LEFT JOIN db1.masterf_kemasan AS Ek ON B.id_kemasankecil = Ek.id
            LEFT JOIN db1.tdetailf_perencanaan AS tRo ON A.id_katalog = tRo.id_katalog
            LEFT JOIN db1.tdetailf_pembelian AS F ON F.id_katalog = A.id_katalog
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON F.id_reffkatalog = tH.id_reffkatalog
            LEFT JOIN db1.tdetailf_perencanaan AS tR ON F.id_reffkatalog = tR.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND B.tipe_doc = 0
                    AND (:kodeRefNot = '' OR kode_reff != :kodeRefNot)
                GROUP BY
                    A.kode_reffpo,
                    A.id_katalog
            ) AS T ON T.id_katalog = A.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND (:kodeRefNot = '' OR kode_reff != :kodeRefNot)
                    AND B.tipe_doc = 1
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Tb ON Tb.id_katalog = A.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Rt ON Rt.id_katalog = A.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Rtb ON Rtb.id_katalog = A.id_katalog
            WHERE
                A.kode_reff = :kodeRef
                AND A.kode_reff = Rtb.kode_reffpo
                AND A.kode_reff = Rt.kode_reffpo
                AND A.kode_reff = Tb.kode_reffpo
                AND A.kode_reff = T.kode_reffpo
                AND F.kode_reffrenc = tR.kode_reff
                AND F.kode_reffhps = tH.kode_reff
                AND A.kode_reffpl = F.kode_reff
                AND A.kode_reffro = tRo.kode_reff
        ";
        $params = [":kodeRef" => $kodeRef, ":kodeRefNot" => $kodeRefNot];
        $daftarDetailPemesanan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarDetailPemesanan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-bd0fc67
     */
    public function fetchTerima(): array
    {
        [   "noDokumen" => $noDokumen,
            "idJenisAnggaran" => $idJenisAnggaran,
            "kode" => $kodePemesanan, // not used in views
            "kodeRefPl" => $kodeRefPl,
            "statusSaved" => $statusSaved,
            "statusClosed" => $statusClosed,
            "statusRevisi" => $statusRevisi,
            "verRevisi" => $verRevisi
        ] = Yii::$app->request->post();

        // TODO: php: uncategorized: insert to SQL statement (pick FROM actionSearchJsonTerima!)
        // $limit = 30;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                PSN.kode              AS kode,
                PSN.tgl_tempokirim    AS tanggalTempoKirim,
                PSN.no_doc            AS noDokumen,
                PSN.blnawal_anggaran  AS bulanAwalAnggaran,
                PSN.blnakhir_anggaran AS bulanAkhirAnggaran,
                PSN.thn_anggaran      AS tahunAnggaran,
                PSN.nilai_akhir       AS nilaiAkhir,
                PBF.nama_pbf          AS namaPemasok
            FROM db1.transaksif_pemesanan AS PSN
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON PSN.id_jenisanggaran = SJA.id
            LEFT JOIN db1.masterf_jenisharga AS JHG ON PSN.id_jenisharga = JHG.id
            LEFT JOIN db1.masterf_pbf AS PBF ON PSN.id_pbf = PBF.id
            LEFT JOIN db1.transaksif_pembelian AS BLN ON BLN.kode = PSN.kode_reffpl
            WHERE
                (:noDokumen = '' OR PSN.no_doc LIKE :noDokumen)
                AND (:idJenisAnggaran = '' OR PSN.id_jenisanggaran = :idJenisAnggaran)
                AND (:kodePemesanan = '' OR PSN.kode = :kodePemesanan)
                AND (:kodeRefPl = '' OR PSN.kode_reffpl = :kodeRefPl)
                AND (:statusSaved = '' OR PSN.sts_saved = :statusSaved)
                AND (:statusClosed = '' OR PSN.sts_closed = :statusClosed)
                AND (:statusRevisi = '' OR PSN.sts_revisi = :statusRevisi)
                AND (:verRevisi = '' OR PSN.ver_revisi = :verRevisi)
                AND PSN.sts_deleted = 0
            ORDER BY PSN.no_doc ASC
        ";
        $params = [
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":idJenisAnggaran" => $idJenisAnggaran,
            ":kodePemesanan" => $kodePemesanan,
            ":kodeRefPl" => $kodeRefPl,
            ":statusSaved" => $statusSaved,
            ":statusClosed" => $statusClosed,
            ":statusRevisi" => $statusRevisi,
            ":verRevisi" => $verRevisi,
        ];
        return $connection->createCommand($sql, $params)->queryAll();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-bd0fc67
     */
    public function actionSearchJsonTerima(): string
    {
        return json_encode($this->fetchTerima());
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-bd0fc67
     */
    public function actionSearchHtmlTerima(): string
    {
        $view = new ListPo(
            registerId: Yii::$app->actionToId(__METHOD__),
            dataUrl:    Yii::$app->actionToUrl([self::class, "fetchTerima"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#ajaxDelete    the original method
     */
    public function actionAjaxDelete(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        ["keterangan" => $keterangan, "kode" => $kode] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pemesanan
            SET
                no_doc = kode,
                keterangan = CONCAT('Hapus DO/PO dengan No: ', :keterangan),
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pemesanan.php#getUpdateTrn    the original method
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
            ":initial" => $data["initial"],
            ":unit" => $data["unit"],
            ":subunit" => $data["subunit"],
            ":kode" => $data["kode"],
            ":subkode" => $data["subkode"],
            ":detailKode" => $data["detailkode"],
        ];
        return $connection->createCommand($sql, $params)->queryScalar();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/Masterdata.php#nodokumen() as the source of copied text
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
                FROM db1.transaksif_pemesanan
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
                FROM db1.transaksif_pemesanan
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
