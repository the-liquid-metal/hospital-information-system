<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use Jaspersoft\Client\Client as JasperClient;
use tlm\his\FatmaPharmacy\models\{
    DataAlreadyExistException,
    DataNotExistException,
    FailToInsertException,
    FailToUpdateException,
    FarmasiModel,
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
class PembelianController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "jenisDokumen" => $jenisDokumen,
            "statusClosed" => $statusClosed,
            "tanggalJatuhTempo" => $tanggalJatuhTempo,
            "noDokumen" => $noDokumen,
            "namaPemasok" => $namaPemasok,
            "noHps" => $noHps,
            "kodeJenis" => $kodeJenis,
            "bulanAnggaran" => $bulanAnggaran,
            "tahunAnggaran" => $tahunAnggaran,
            "jenisHarga" => $jenisHarga,
            "kodeRefHps" => $kodeRefHps,
            "kodeRefRencana" => $kodeRefRencana,
            "kodePl" => $kodePl,
            "limit" => $limit,
            "offset" => $offset,
        ] =  Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                A.kode              AS kode,
                T.tipe_doc          AS jenisDokumen,
                UPDT.name           AS updatedBy,
                A.sysdate_updt      AS updatedTime,
                A.revisike          AS revisiKe,
                A.adendumke         AS adendumKe,
                A.keterangan        AS keterangan,
                A.no_doc            AS noDokumen,
                A.tgl_jatuhtempo    AS tanggalJatuhTempo,
                A.thn_anggaran      AS tahunAnggaran,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.nilai_akhir       AS nilaiAkhir,
                A.sts_saved         AS statusSaved,
                A.sts_linked        AS statusLinked,
                A.sts_revisi        AS statusRevisi,
                A.sts_closed        AS statusClosed,
                A.ver_revisi        AS verRevisi,
                D.nama_pbf          AS namaPemasok,
                tb_join.jumlah_do   AS jumlahDo,
                M.kode              AS kodeJenis,
                G.jenis_harga       AS jenisHarga,
                H.no_doc            AS noHps
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN db1.masterf_tipedoc AS T ON A.tipe_doc = T.kode
            LEFT JOIN db1.transaksif_pengadaan AS H ON H.kode = A.kode_reffhps
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS M ON A.id_jenisanggaran = M.id
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN (
                SELECT
                    kode_reffpl        AS kode_reffpl,
                    COUNT(kode_reffpl) AS jumlah_do
                FROM db1.transaksif_pemesanan
                WHERE sts_deleted = 0
                GROUP BY kode_reffpl
            ) AS tb_join ON A.kode = tb_join.kode_reffpl
            LEFT JOIN db1.user AS UPDT ON A.userid_updt = UPDT.id
            WHERE
                A.sts_deleted = 0
                AND (:jenisDokumen = '' OR T.tipe_doc = :jenisDokumen)
                AND (:statusClosed = '' OR A.sts_closed = :statusClosed)
                AND (:tanggalJatuhTempo = '' OR A.tgl_jatuhtempo = :tanggalJatuhTempo)
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:namaPemasok = '' OR D.nama_pbf LIKE :namaPemasok)
                AND (:noHps = '' OR H.no_doc LIKE :noHps)
                AND (:kodeJenis = '' OR M.kode = :kodeJenis)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR  A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
                AND (:jenisHarga = '' OR G.jenis_harga = :jenisHarga)
                AND (:kodeRefHps = '' OR A.kode_reffhps = :kodeRefHps)
                AND (:kodeRefRencana = '' OR H.kode_reffrenc = :kodeRefRencana)
                AND T.modul = 'pembelian'
            ORDER BY tgl_doc DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":jenisDokumen" => $jenisDokumen,
            ":statusClosed" => $statusClosed,
            ":tanggalJatuhTempo" => $tanggalJatuhTempo ? $toSystemDate($tanggalJatuhTempo) : "",
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":noHps" => $noHps ? "%$noHps%" : "",
            ":kodeJenis" => $kodeJenis,
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
            ":jenisHarga" => $jenisHarga,
            ":kodeRefHps" => $kodeRefHps,
            ":kodeRefRencana" => $kodeRefRencana,
            ":kodePl" => $kodePl, // missing table column named "kodepl"
        ];
        $daftarPembelian = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN db1.masterf_tipedoc AS T ON A.tipe_doc = T.kode
            LEFT JOIN db1.transaksif_pengadaan AS H ON H.kode = A.kode_reffhps
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS M ON A.id_jenisanggaran = M.id
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN (
                SELECT
                    kode_reffpl        AS kode_reffpl,
                    COUNT(kode_reffpl) AS jumlah_do
                FROM db1.transaksif_pemesanan
                WHERE sts_deleted = 0
                GROUP BY kode_reffpl
            ) AS tb_join ON A.kode = tb_join.kode_reffpl
            LEFT JOIN db1.user AS UPDT ON A.userid_updt = UPDT.id
            WHERE
                A.sts_deleted = 0
                AND (:jenisDokumen = '' OR T.tipe_doc = :jenisDokumen)
                AND (:statusClosed = '' OR A.sts_closed = :statusClosed)
                AND (:tanggalJatuhTempo = '' OR A.tgl_jatuhtempo = :tanggalJatuhTempo)
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:namaPemasok = '' OR D.nama_pbf LIKE :namaPemasok)
                AND (:noHps = '' OR H.no_doc LIKE :noHps)
                AND (:kodeJenis = '' OR M.kode = :kodeJenis)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR  A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
                AND (:jenisHarga = '' OR G.jenis_harga = :jenisHarga)
                AND (:kodeRefHps = '' OR A.kode_reffhps = :kodeRefHps)
                AND (:kodeRefRencana = '' OR H.kode_reffrenc = :kodeRefRencana)
                AND T.modul = 'pembelian'
        ";
        $jumlahPembelian = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode([
            "recordsFiltered" => $jumlahPembelian,
            "data" => $daftarPembelian
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#revisi    the original method
     */
    public function actionTableRevisiData(): string
    {
        [   "noDokumen" => $noDokumen,
            "tanggalJatuhTempo" => $tanggalJatuhTempo,
            "namaPemasok" => $namaPemasok,
            "kodeJenis" => $kodeJenis,
            "bulanAwalAnggaran" => $bulanAnggaran,
            "tahunAnggaran" => $tahunAnggaran,
            "jenisHarga" => $jenisHarga,
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
            SELECT -- all are in use, confirmed with view file.
                A.kode              AS kodeRevPembelian,
                A.adendumke         AS adendumKe,
                A.revisike          AS revisiKe,
                A.keterangan        AS keterangan,
                A.no_doc            AS noDokumen,
                A.tgl_jatuhtempo    AS tanggalJatuhTempo,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.thn_anggaran      AS tahunAnggaran,
                A.nilai_akhir       AS nilaiAkhir,
                A.ver_tglrevisi     AS verTanggalRevisi,
                B.nama_pbf          AS namaPemasok,
                C.kode              AS kodeJenis,
                D.jenis_harga       AS jenisHarga,
                UREV.name           AS namaUserRevisi
            FROM db1.transaksif_revpembelian AS A
            LEFT JOIN db1.transaksif_pembelian AS X ON A.kode = X.kode
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.masterf_subjenisanggaran AS C ON A.id_jenisanggaran = C.id
            LEFT JOIN db1.masterf_jenisharga AS D ON A.id_jenisharga = D.id
            LEFT JOIN db1.user AS UREV ON A.ver_usrrevisi = UREV.id
            WHERE
                A.sts_deleted = 0
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:tanggalJatuhTempo = '' OR A.tgl_jatuhtempo = :tanggalJatuhTempo)
                AND (:namaPemasok = '' OR B.nama_pbf LIKE :namaPemasok)
                AND (:kodeJenis = '' OR C.kode = :kodeJenis)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
                AND (:jenisHarga = '' OR D.jenis_harga = :jenisHarga)
                AND (:kode = '' OR A.kode = :kode)
            ORDER BY kode ASC, revisike DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":tanggalJatuhTempo" => $tanggalJatuhTempo ? $toSystemDate($tanggalJatuhTempo) : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":kodeJenis" => $kodeJenis,
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
            ":jenisHarga" => $jenisHarga,
            ":kode" => $kode,
        ];
        $daftarRevisiPembelian = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_revpembelian AS A
            LEFT JOIN db1.transaksif_pembelian AS X ON A.kode = X.kode
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.masterf_subjenisanggaran AS C ON A.id_jenisanggaran = C.id
            LEFT JOIN db1.masterf_jenisharga AS D ON A.id_jenisharga = D.id
            LEFT JOIN db1.user AS UREV ON A.ver_usrrevisi = UREV.id
            WHERE
                A.sts_deleted = 0
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:tanggalJatuhTempo = '' OR A.tgl_jatuhtempo = :tanggalJatuhTempo)
                AND (:namaPemasok = '' OR B.nama_pbf LIKE :namaPemasok)
                AND (:kodeJenis = '' OR C.kode = :kodeJenis)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
                AND (:jenisHarga = '' OR D.jenis_harga = :jenisHarga)
                AND (:kode = '' OR A.kode = :kode)
        ";
        $jumlahRevisiPembelian = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode([
            "recordsFiltered" => $jumlahRevisiPembelian,
            "data" => $daftarRevisiPembelian
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#adendum    the original method
     */
    public function actionTableAdendumData(): string
    {
        [   "noDokumen" => $noDokumen,
            "tanggalJatuhTempo" => $tanggalJatuhTempo,
            "namaPemasok" => $namaPemasok,
            "kodeJenis" => $kodeJenis,
            "bulanAwalAnggaran" => $bulanAnggaran,
            "tahunAnggaran" => $tahunAnggaran,
            "jenisHarga" => $jenisHarga,
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
            SELECT -- all are in use, confirmed with view file.
                A.kode              AS kodeRevPembelian,
                A.adendumke         AS adendumKe,
                A.revisike          AS revisiKe,
                A.keterangan        AS keterangan,
                A.no_doc            AS noDokumen,
                A.tgl_jatuhtempo    AS tanggalJatuhTempo,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.thn_anggaran      AS tahunAnggaran,
                A.nilai_akhir       AS nilaiAkhir,
                A.ver_tgladendum    AS verTanggalAdendum,
                B.nama_pbf          AS namaPemasok,
                C.kode              AS kodeJenis,
                D.jenis_harga       AS jenisHarga,
                UADN.name           AS namaUserAdendum
            FROM db1.transaksif_revpembelian AS A
            LEFT JOIN db1.transaksif_pembelian AS X ON A.kode = X.kode
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.masterf_subjenisanggaran AS C ON A.id_jenisanggaran = C.id
            LEFT JOIN db1.masterf_jenisharga AS D ON A.id_jenisharga = D.id
            LEFT JOIN db1.user AS UADN ON A.ver_usradendum = UADN.id
            WHERE
                A.sts_deleted = 0
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:tanggalJatuhTempo = '' OR A.tgl_jatuhtempo = :tanggalJatuhTempo)
                AND (:namaPemasok = '' OR B.nama_pbf LIKE :namaPemasok)
                AND (:kodeJenis = '' OR C.kode = :kodeJenis)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
                AND (:jenisHarga = '' OR D.jenis_harga = :jenisHarga)
            ORDER BY kode ASC, revisike DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":tanggalJatuhTempo" => $tanggalJatuhTempo ? $toSystemDate($tanggalJatuhTempo) : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":kodeJenis" => $kodeJenis,
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
            ":jenisHarga" => $jenisHarga,
        ];
        $daftarRevisiPembelian = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_revpembelian AS A
            LEFT JOIN db1.transaksif_pembelian AS X ON A.kode = X.kode
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.masterf_subjenisanggaran AS C ON A.id_jenisanggaran = C.id
            LEFT JOIN db1.masterf_jenisharga AS D ON A.id_jenisharga = D.id
            LEFT JOIN db1.user AS U ON A.ver_usradendum = U.id
            WHERE
                A.sts_deleted = 0
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:tanggalJatuhTempo = '' OR A.tgl_jatuhtempo = :tanggalJatuhTempo)
                AND (:namaPemasok = '' OR B.nama_pbf LIKE :namaPemasok)
                AND (:kodeJenis = '' OR C.kode = :kodeJenis)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
                AND (:jenisHarga = '' OR D.jenis_harga = :jenisHarga)
        ";
        $jumlahRevisiPembelian = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode([
            "recordsFiltered" => $jumlahRevisiPembelian,
            "data" => $daftarRevisiPembelian
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#add    the original method
     */
    public function actionSaveAdd(): void
    {
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        [   "action" => $action,
            "kode" => $kode,
            "no_doc" => $noDokumen,
            "tipe_doc" => $tipeDokumen,
            "tgl_doc" => $tanggalDokumen,
            "tgl_jatuhtempo" => $tanggalJatuhTempo,
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "nilai_total" => $nilaiTotal,
            "ppn" => $ppn,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "create_new" => $createNew,
            "kode_reffhps" => $daftarKodeRefHps, // array or single?
            "kode_reffrenc" => $daftarKodeRefRencana,
            "no_docrenc" => $daftarNoDokumenRencana,
            "kodereff_hps" => $daftarKodeRefHps2,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "harga_kemasan" => $daftarHargaKemasan,
            "diskon_item" => $daftarDiskonItem,
            "diskon_harga" => $daftarDiskonHarga,
            "no_urut" => $daftarNoUrut,
            "id_reffkatalog" => $daftarIdRefKatalog,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "jumlah_item" => $daftarJumlahItem,
            "harga_item" => $daftarHargaItem,
        ] = Yii::$app->request->post();

        $ppn ??= 0;

        $dataPembelian = [
            "no_doc" => $noDokumen,
            "tipe_doc" => $tipeDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tgl_jatuhtempo" => $toSystemDate($tanggalJatuhTempo),
            "kode_reffhps" => $daftarKodeRefHps,
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "ppn" => $ppn,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        // create data HPS baru
        if ($createNew == 1) {
            $counter = $this->getUpdateTrn([
                "initial" => "H",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode HPS Pengadaan PPK Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUser,
            ]);

            $daftarKodeRencana = [];
            $daftarNoRencana = [];
            foreach ($daftarKodeRefRencana as $i => $kode) {
                $daftarKodeRencana[$kode] = $kode;
                $daftarNoRencana[$kode] = $daftarNoDokumenRencana[$i];
            }

            $kodeHps = "H00" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);
            $dataHps = [
                "kode" => $kodeHps,
                "no_doc" => $daftarKodeRefHps2,
                "tgl_doc" => $toSystemDate($tanggalDokumen),
                "kode_reffrenc" => implode(",", $daftarKodeRencana),
                "no_docreff" => implode(",", $daftarNoRencana),
                "thn_anggaran" => $tahunAnggaran,
                "blnawal_anggaran" => $bulanAwalAnggaran,
                "blnakhir_anggaran" => $bulanAkhirAnggaran,
                "id_pbf" => $idPemasok,
                "id_jenisanggaran" => $idJenisAnggaran,
                "id_sumberdana" => $idSumberDana,
                "id_carabayar" => $idCaraBayar,
                "id_jenisharga" => $idJenisHarga,
                "ppn" => $ppn,
                "nilai_total" => 0,      // set later
                "nilai_diskon" => 0,     // set later
                "nilai_ppn" => 0,        // set later
                "nilai_pembulatan" => 0, // set later
                "nilai_akhir" => 0,      // set later
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "userid_updt" => $idUser,
                "sysdate_updt" => $nowValSystem,
            ];

            $dataDetailPengadaan = [];

            $nilaiTotal = 0;
            $nilaiDiskon = 0;
            foreach ($daftarIdKatalog as $i => $idKatalog) {
                if ($daftarNoUrut[$i] != 1) continue;
                $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
                $hargaKemasan = $toSystemNumber($daftarHargaKemasan[$i]);
                $diskonItem = $toSystemNumber($daftarDiskonItem[$i]);

                $dataDetailPengadaan[$idKatalog] = [
                    "kode_reff" => $kodeHps,
                    "kode_reffrenc" => $daftarKodeRefRencana[$i],
                    "id_katalog" => $idKatalog,
                    "id_reffkatalog" => $daftarIdRefKatalog[$i],
                    "kemasan" => $daftarKemasan[$i],
                    "id_pabrik" => $daftarIdPabrik[$i],
                    "id_kemasan" => $daftarIdKemasan[$i],
                    "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$i]),
                    "id_kemasandepo" => $daftarIdKemasanDepo[$i],
                    "jumlah_item" => $toSystemNumber($daftarJumlahItem[$i]),
                    "jumlah_kemasan" => $jumlahKemasan,
                    "harga_item" => $toSystemNumber($daftarHargaItem[$i]),
                    "harga_kemasan" => $hargaKemasan,
                    "diskon_item" => $diskonItem,
                    "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$i]),
                    "userid_updt" => $idUser,
                ];
                $hargaTotal = $jumlahKemasan * $hargaKemasan;
                $diskonHarga = $hargaTotal * $diskonItem / 100;
                $nilaiTotal += $hargaTotal;
                $nilaiDiskon += $diskonHarga;
            }
            $nilaiPpn2 = ($nilaiTotal - $nilaiDiskon) * $ppn / 100;
            $nilaiAkhir2 = $nilaiTotal - $nilaiDiskon + $nilaiPpn2;

            $dataHps["nilai_total"] = $nilaiTotal;
            $dataHps["nilai_diskon"] = $nilaiDiskon;
            $dataHps["nilai_ppn"] = $nilaiPpn2;
            $dataHps["nilai_akhir"] = floor($nilaiAkhir2);
            $dataHps["nilai_pembulatan"] = $nilaiAkhir2 - floor($nilaiAkhir2);

            $dataPembelian["kode_reffhps"] = $kodeHps;
        } // end create data hps

        // set no Transaksi untuk no PL
        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "P",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode PL Pembelian PPK Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUser,
            ]);
            $kode = "P00" . date("Yn") . str_pad($counter, 6, "0", STR_PAD_LEFT);

            $dataPembelian = [
                ...$dataPembelian,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        $dataDetailPembelian = [];
        $daftarKodeReferensiHps = [];
        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $kondisi = ($tipeDokumen == "0") || ($tipeDokumen != "0" && ($daftarJumlahKemasan[$i] != 0 || $daftarNoUrut[$i] == 1));
            if (!$kondisi) continue;

            $dataDetailPembelian[$idKatalog] = [
                "kode_reff" => $kode,
                "kode_reffhps" => $daftarKodeRefHps[$i],
                "kode_reffrenc" => $daftarKodeRefRencana[$i],
                "id_katalog" => $idKatalog,
                "id_reffkatalog" => $daftarIdRefKatalog[$i],
                "no_urut" => $daftarNoUrut[$i],
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
            $daftarKodeReferensiHps[] = $daftarKodeRefHps[$i];
        }

        if ($createNew == 0) {
            $dataPembelian["kode_reffhps"] = implode(",", $daftarKodeReferensiHps);
        }

        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();
        $fm = new FarmasiModel;

        // jika hps baru , lakukan penyimpanan ke databases
        if ($createNew == 1) {
            /** @noinspection PhpUndefinedVariableInspection for $datahps */
            $daftarFieldHps = array_keys($dataHps);
            $berhasilTambah = $fm->saveData("transaksif_pengadaan", $daftarFieldHps, $dataHps);

            /** @noinspection PhpUndefinedVariableInspection for $kodeRencana */
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_perencanaan
                SET sts_linked = 1
                WHERE kode IN ('" . implode("','", $daftarKodeRencana) . "')
            ";
            $berhasilUbah = $connection->createCommand($sql)->execute();

            if ($berhasilTambah && $berhasilUbah) {
                /** @noinspection PhpUndefinedVariableInspection for $idatahps */
                $fm->saveBatch("tdetailf_pengadaan", $dataDetailPengadaan);
            }
        }

        // simpan harga ke relasif_katalogpbf
        foreach ($dataDetailPembelian as $dPembelian) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_katalogpbf
                SET
                    id_katalog = :idKatalog,
                    id_pbf = :idPemasok,
                    id_jenisharga = :idJenisHarga,
                    id_kemasan = :idKemasan,
                    id_kemasandepo = :idKemasanDepo,
                    harga_item = :hargaItem,
                    harga_kemasan = :hargaKemasan,
                    diskon_item = :diskonItem,
                    sts_actived = :statusAktif,
                    userid_updt = :idUser
                ON DUPLICATE KEY UPDATE
                    id_kemasan = :idKemasan,
                    id_kemasandepo = :idKemasanDepo,
                    harga_item = :hargaItem,
                    harga_kemasan = :hargaKemasan,
                    diskon_item = :diskonItem,
                    userid_updt = :idUser
            ";
            $params = [
                ":idKatalog"     => $dPembelian["id_katalog"],
                ":idPemasok"     => $idPemasok,
                ":idJenisHarga"  => $idJenisHarga,
                ":idKemasan"     => $dPembelian["id_kemasan"],
                ":idKemasanDepo" => $dPembelian["id_kemasandepo"],
                ":hargaItem"     => $dPembelian["harga_item"],
                ":hargaKemasan"  => $dPembelian["harga_kemasan"],
                ":diskonItem"    => $dPembelian["diskon_item"],
                ":statusAktif"   => 1,
                ":idUser"        => $idUser
            ];
            $connection->createCommand($sql, $params)->execute();
        }

        $daftarField = array_keys($dataPembelian);

        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_pembelian
                WHERE
                    kode = :kode
                    OR no_doc = :noDokumen
                LIMIT 1    
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $adaPembelian = $connection->createCommand($sql, $params)->queryScalar();
            if (!$adaPembelian) throw new DataAlreadyExistException("Pembelian", "Kode, No. Dokumen", "$kode, $noDokumen", $transaction);

            $berhasilTambah = $fm->saveData("transaksif_pembelian", $daftarField, $dataPembelian);
            if (!$berhasilTambah) throw new FailToInsertException("Pembelian", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_pembelian", $dataDetailPembelian);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Pembelian", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_pembelian", $daftarField, $dataPembelian, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kode, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_pembelian", $dataDetailPembelian, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Pembelian", "Kode Ref", $kode, $transaction);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode              AS kode,
                A.revisike          AS revisiKe,
                A.adendumke         AS adendumKe,
                A.keterangan        AS keterangan,
                A.edittype          AS editType,
                A.no_doc            AS noDokumen,
                A.tipe_doc          AS tipeDokumen,
                A.tgl_doc           AS tanggalDokumen,
                A.tgl_jatuhtempo    AS tanggalJatuhTempo,
                A.kode_reffhps      AS kodeRefHps,          -- in use
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
                A.sts_revisi        AS statusRevisi,
                A.sysdate_rev       AS sysdateClosed,
                A.keterangan_rev    AS keteranganClosed,
                A.sts_closed        AS statusClosed,
                A.sysdate_cls       AS sysdateClosed,
                A.sts_deleted       AS statusDeleted,
                A.sysdate_del       AS sysdateDeleted,
                A.ver_revisi        AS verRevisi,
                A.ver_usrrevisi     AS verUserRevisi,
                A.ver_tglrevisi     AS verTanggalRevisi,
                A.ver_adendum       AS verAdendum,
                A.ver_usradendum    AS verUserAdendum,
                A.ver_tgladendum    AS verTanggalAdendum,
                A.userid_in         AS useridInput,
                A.sysdate_in        AS sysdateInput,
                A.userid_updt       AS useridUpdate,
                A.sysdate_updt      AS sysdateUpdate,
                B.kode              AS kodePemasok,
                B.nama_pbf          AS namaPemasok,
                C.no_doc            AS noHps,
                C.no_docreff        AS noDokumenRef,
                C.blnawal_anggaran  AS bulanAwalAnggaranHps,
                C.blnakhir_anggaran AS bulanAkhirAnggaranHps,
                C.thn_anggaran      AS tahunAnggaranHps,
                D.subjenis_anggaran AS subjenisAnggaranHps,
                E.jenis_harga       AS jenisHargaHps,
                F.nama_pbf          AS namaPemasokHps,
                NULL                AS daftarDetailPembelian,
                NULL                AS daftarPerencanaan
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.transaksif_pengadaan AS C ON A.kode_reffhps = C.kode
            LEFT JOIN db1.masterf_subjenisanggaran AS D ON C.id_jenisanggaran = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON C.id_jenisharga = E.id
            LEFT JOIN db1.masterf_pbf AS F ON C.id_pbf = F.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
                AND A.sts_deleted = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pembelian = $connection->createCommand($sql, $params)->queryOne();
        if (!$pembelian) throw new DataNotExistException("Pembelian", "Kode", $kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                          AS kodeRef,
                A.kode_reffhps                       AS kodeRefHps,
                A.kode_reffrenc                      AS kodeRefRencana,
                A.id_katalog                         AS idKatalog,
                A.id_reffkatalog                     AS idRefKatalog,
                A.no_urut                            AS noUrut,
                A.id_kemasan                         AS idKemasan,
                A.id_kemasandepo                     AS idKemasanDepo,
                A.isi_kemasan                        AS isiKemasan,
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
                C.kemasan                            AS kemasanKat,
                C.id_kemasankecil                    AS idKemasanDepoKat,
                C.isi_kemasan                        AS isiKemasanKat,
                C.harga_beli                         AS hargaItemKat,
                C.harga_beli * diskon_beli           AS hargaKemasanKat,
                D.nama_pabrik                        AS namaPabrik,
                E.kode                               AS satuanJual,
                F.kode                               AS satuan,
                Gg.kode                              AS satuanJualKat,
                G.kode                               AS satuanKat,
                IFNULL(H.harga_item, A.harga_item)   AS hargaItemPemasok,
                IFNULL(H.diskon_item, A.diskon_item) AS diskonItemPemasok,
                IFNULL(I.jumlah_item, 0)             AS jumlahRencana,
                IFNULL(pl.jumlah_item, 0)            AS jumlahPl,
                IFNULL(trm.jumlah_item, 0)           AS jumlahTerima,
                C.id_pabrik                          AS idPabrik,
                It.no_doc                            AS noDokumenRencana,
                IFNULL(pl.jumlah_ret, 0)             AS jumlahRetur,
                tH.jumlah_item                       AS jumlahHps
            FROM db1.tdetailf_pembelian AS A
            LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_katalog AS C ON C.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS D ON D.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS E ON E.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS F ON F.id = A.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS Gg ON Gg.id = C.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS G ON G.id = C.id_kemasankecil
            LEFT JOIN db1.relasif_katalogpbf AS H ON A.id_katalog = H.id_katalog
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON A.kode_reffhps = tH.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS I ON I.kode_reff = A.kode_reffrenc
            LEFT JOIN db1.transaksif_perencanaan AS It ON It.kode = A.kode_reffrenc
            LEFT JOIN (
                SELECT
                    A.kode_reffrenc    AS kode_reffrenc,
                    A.id_reffkatalog   AS id_reffkatalog,
                    SUM(A.jumlah_item) AS jumlah_item,
                    tRet.jumlah_item   AS jumlah_ret
                FROM db1.tdetailf_pembelian AS A
                LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_reffkatalog   AS id_reffkatalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_return AS A
                    LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY
                        A.kode_reffpl,
                        A.id_reffkatalog
                ) AS tRet ON A.kode_reff = tRet.kode_reffpl
                WHERE
                    B.sts_deleted = 0
                    AND B.kode != :kode
                    AND A.id_katalog = A.id_reffkatalog
                GROUP BY
                    A.kode_reffrenc,
                    A.id_reffkatalog
            ) AS pl ON A.kode_reffrenc = pl.kode_reffrenc
            LEFT JOIN (
                SELECT
                    A.kode_reffrenc    AS kode_reffrenc,
                    A.id_reffkatalog   AS id_reffkatalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY
                    A.kode_reffrenc,
                    A.id_reffkatalog
            ) AS trm ON A.kode_reffrenc = trm.kode_reffrenc
            WHERE
                A.kode_reff = :kode
                AND A.id_reffkatalog = trm.id_reffkatalog
                AND A.id_reffkatalog = pl.id_reffkatalog
                AND I.id_katalog = A.id_reffkatalog
                AND A.id_reffkatalog = tH.id_reffkatalog
                AND H.id_pbf = B.id_pbf
                AND H.id_jenisharga = B.id_jenisharga
            ORDER BY id_reffkatalog, no_urut
        ";
        $params = [":kode" => $kode];
        $pembelian->daftarDetailPembelian = $connection->createCommand($sql, $params)->queryAll();

        // TODO: sql: uncategorized: make sure this statement. it seems to be useless
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode   AS kode,
                A.no_doc AS noDokumen
            FROM db1.transaksif_perencanaan AS A
            LEFT JOIN db1.tdetailf_pengadaan AS B ON A.kode = B.kode_reffrenc
            WHERE B.kode_reff = :kodeRef
            GROUP BY A.kode
        ";
        $params = [":kodeRef" => $pembelian->kodeRefHps];
        $pembelian->daftarPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        // edit data:
        // $judulHeading = "Edit SPK/Kontrak/SP Pembelian";
        // $action = "edit";
        // $dataPembelian = $dataEdit["data"];
        // $dataDetailPembelian = $dataEdit["idata"];
        // $referensiRencana = $dataEdit["reffrenc"];

        // add data:
        // $judulHeading = "Tambah SPK/Kontrak/SP Pembelian";
        // $action = "add";
        // $dataPembelian = [];
        // $dataDetailPembelian = [];
        // $referensiRencana = [];

        return json_encode($pembelian);
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#addrevisi    the original method
     */
    public function actionSaveRevisiOpen(): void
    {
        [   "kode" => $kode,
            "keterangan" => $keterangan,
        ] = Yii::$app->request->post();

        $idUser = Yii::$app->userFatma->id;
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        // backup tdetailf_pembelian
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpembelian
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_pembelian AS A
            LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Detail Revisi Pembelian", $transaction);

        // set keterangan
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.tdetailf_pembelian
            SET keterangan = ''
            WHERE kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $connection->createCommand($sql, $params)->execute();

        // backup transaksif_pembelian
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_revpembelian
            SELECT *
            FROM db1.transaksif_pembelian
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Revisi Pembelian", $transaction);

        // kunci data dengan no PL terkait
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_perencanaan
            SET
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi PL. Cek Revisi dan Lakukan verifikasi revisi perencanaan untuk bisa menggunakan Dokumen ini kembali.'
            WHERE
                kode_reffpl = :kode
                AND sts_deleted = 0
        ";
        $params = [":tanggalRevisi" => $nowValSystem, ":kode" => $kode];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pemesanan
            SET
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi PL. Cek Revisi Perencanaan dan Lakukan verifikasi revisi PO/DO untuk bisa menggunakan Dokumen ini kembali.'
            WHERE
                kode_reffpl = :kode
                AND sts_deleted = 0
        ";
        $params = [":tanggalRevisi" => $nowValSystem, ":kode" => $kode];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_penerimaan
            SET
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi PL. Cek Revisi PL atau PO/DO dan Lakukan verifikasi revisi Penerimaan untuk bisa menggunakan Dokumen ini kembali.'
            WHERE
                kode_reffpl = :kode
                AND sts_deleted = 0
        ";
        $params = [":tanggalRevisi" => $nowValSystem, ":kode" => $kode];
        $connection->createCommand($sql, $params)->execute();

        // saat revisi, diset close sementara, dan update revisi ke
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pembelian
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
        if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kode, $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#addrevisi    the original method
     */
    public function actionSaveRevisiDokumen(): void
    {
        [   "kode" => $kode,
            "keterangan" => $keterangan,
            "revisike" => $revisiKe,
            "tipe_doc" => $tipeDokumen,
            "tgl_doc" => $tanggalDokumen,
            "tgl_jatuhtempo" => $tanggalJatuhTempo,
            "ver_revisi" => $verRevisi,
            "ver_usrrevisi" => $verUserRevisi,
            "ver_tglrevisi" => $verTanggalRevisi,
            "no_doc" => $noDokumen,
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "thn_anggaran" => $tahunAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_jenisharga" => $idJenisHarga,
            "id_carabayar" => $idCaraBayar,
        ] = Yii::$app->request->post();

        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();
        $fm = new FarmasiModel;

        $tanggalDokumen = $toSystemDate($tanggalDokumen);
        $tanggalJatuhTempo = $toSystemDate($tanggalJatuhTempo);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.no_doc            AS noDokumen,
                A.tipe_doc          AS tipeDokumen,
                A.id_pbf            AS idPemasok,
                A.tgl_doc           AS tanggalDokumen,
                A.tgl_jatuhtempo    AS tanggalJatuhTempo,
                A.id_jenisanggaran  AS idJenisAnggaran,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.thn_anggaran      AS tahunAnggaran,
                A.id_sumberdana     AS idSumberDana,
                A.id_jenisharga     AS idJenisHarga,
                A.id_carabayar      AS idCaraBayar,
                A.keterangan        AS keterangan
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.transaksif_pengadaan AS C ON A.kode_reffhps = C.kode
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pembelian = $connection->createCommand($sql, $params)->queryOne();

        $dataPembelian = [
            "no_doc" =>            $noDokumen          ?: $pembelian->noDokumen,
            "tipe_doc" =>          $tipeDokumen        ?: $pembelian->tipeDokumen,
            "id_pbf" =>            $idPemasok          ?: $pembelian->idPemasok,
            "tgl_doc" =>           $tanggalDokumen     ?: $pembelian->tanggalDokumen,
            "tgl_jatuhtempo" =>    $tanggalJatuhTempo  ?: $pembelian->tanggalJatuhTempo,
            "id_jenisanggaran" =>  $idJenisAnggaran    ?: $pembelian->idJenisAnggaran,
            "blnawal_anggaran" =>  $bulanAwalAnggaran  ?: $pembelian->bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran ?: $pembelian->bulanAkhirAnggaran,
            "thn_anggaran" =>      $tahunAnggaran      ?: $pembelian->tahunAnggaran,
            "id_sumberdana" =>     $idSumberDana       ?: $pembelian->idSumberDana,
            "id_jenisharga" =>     $idJenisHarga       ?: $pembelian->idJenisHarga,
            "id_carabayar" =>      $idCaraBayar        ?: $pembelian->idCaraBayar,
            "keterangan" =>        $keterangan         ?: $pembelian->keterangan,
        ];

        $kondisi3  = $pembelian->idPemasok       != $idPemasok       && $idPemasok != "";
        $kondisi6  = $pembelian->idJenisAnggaran != $idJenisAnggaran && $idJenisAnggaran != "";
        $kondisi10 = $pembelian->idSumberDana    != $idSumberDana    && $idSumberDana != "";
        $kondisi11 = $pembelian->idJenisHarga    != $idJenisHarga    && $idJenisHarga != "";
        $kondisi12 = $pembelian->idCaraBayar     != $idCaraBayar     && $idCaraBayar != "";

        $update = ($kondisi3 || $kondisi6 || $kondisi10 || $kondisi11 || $kondisi12); // untuk penanda jika pbf/mata anggaran

        // set Verifikasi Revisi
        $verifikasiReferensi = "";
        if ($verRevisi == 1) {
            $dataPembelian = [
                ...$dataPembelian,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $verUserRevisi,
                "ver_tglrevisi" => $verTanggalRevisi,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => null,
            ];

            $verifikasiReferensi .= ",
                verif_reff = 1,
                modul_exec = 'penerimaan',
                action_exec = 'addrevisi'
            ";

            $verifikasiReferensi .= $update ? "" : ",
                ver_execution = 1,
                ver_usrexecution = '$idUser',
                ver_tglexecution = '$nowValSystem'
            ";
        }
        $daftarField = array_keys($dataPembelian);

        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_pembelian", $daftarField, $dataPembelian, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksi_notification
            SET
                description_reff = :deskrisiRef
                $verifikasiReferensi
            WHERE
                tipe_notif = 'R'
                AND kode_reff = :kode
                AND modul_reff = 'pembelian'
                AND info_reff = :infoRef
        ";
        $params = [":deskrisiRef" => $keterangan, ":kode" => $kode, ":infoRef" => $revisiKe];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Notifikasi", "Kode, Info Ref", "$kode, $revisiKe", $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#addrevisi    the original method
     */
    public function actionSaveRevisiJumlah(): void
    {
        [   "kode" => $kode,
            "keterangan" => $keterangan,
            "revisike" => $revisiKe,
            "tipe_doc" => $tipeDokumen,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "ver_revisi" => $verRevisi,
            "ver_usrrevisi" => $verUserRevisi,
            "ver_tglrevisi" => $verTanggalRevisi,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "diskon_harga" => $daftarDiskonHarga,
            "jumlah_item" => $daftarJumlahItem,
        ] = Yii::$app->request->post();

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();
        $fm = new FarmasiModel;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog     AS idKatalog,     -- in use
                A.jumlah_kemasan AS jumlahKemasan,
                A.jumlah_item    AS jumlahItem,    -- in use
                A.diskon_harga   AS diskonHarga,
                B.nama_sediaan   AS namaSediaan    -- in use
            FROM db1.tdetailf_pembelian AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailPembelian = $connection->createCommand($sql, $params)->queryAll();

        $dataDetailPembelian = [];
        foreach ($daftarIdKatalog as $i => $idkatalog) {
            $dataDetailPembelian[$idkatalog] = [
                "jumlah_kemasan" => $toSystemNumber($daftarJumlahKemasan[$i]),
                "jumlah_item" => $toSystemNumber($daftarJumlahItem[$i]),
                "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$i])
            ];
        }
        $daftarFieldDetail = array_keys($dataDetailPembelian[$idkatalog ?? 0] ?? []);

        $update = false;
        $keteranganDetail = ""; // untuk penanda jika pbf/mata anggaran
        if ($dataDetailPembelian) {
            foreach ($daftarDetailPembelian as $i => $d) {
                $jumlahItem = $dataDetailPembelian[$d->idKatalog]["jumlah_item"];
                if ($d->jumlahItem == $jumlahItem) continue;
                $update = true;

                $iwhere = ["kode_reff" => $kode, "id_katalog" => $d->idKatalog];
                $berhasilUbah = $fm->saveData("tdetailf_pembelian", $daftarFieldDetail, $dataDetailPembelian[$d->idKatalog], $iwhere);
                if (!$berhasilUbah) throw new FailToUpdateException("Detail Pembelian", "Kode Ref, Id Katalog", "$kode, {$d->idKatalog}", $transaction);

                $keteranganDetail .= "{$d->namaSediaan}: {$d->jumlahItem} => {$jumlahItem}, ";
            }
        }

        $keterangan .= " || Perubahan Jumlah Item: " . ($keteranganDetail ?: "-");
        $dataPembelian = [
            "keterangan" => $keterangan,
            "tipe_doc" => $tipeDokumen,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
        ];

        // set Verifikasi Revisi
        $verifikasiReferensi = "";
        if ($verRevisi == 1) {
            $dataPembelian = [
                ...$dataPembelian,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $verUserRevisi,
                "ver_tglrevisi" => $verTanggalRevisi,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => null,
            ];

            $verifikasiReferensi .= ",
                verif_reff = 1,
                modul_exec = 'penerimaan',
                action_exec = 'addrevisi'
            ";

            $verifikasiReferensi .= $update ? "" : ",
                ver_execution = 1,
                ver_usrexecution = '$idUser',
                ver_tglexecution = '$nowValSystem'
            ";
        }
        $daftarField = array_keys($dataPembelian);

        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_pembelian", $daftarField, $dataPembelian, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksi_notification
            SET
                description_reff = CONCAT(description_reff, :keterangan)
                $verifikasiReferensi
            WHERE
                tipe_notif = 'R'
                AND kode_reff = :kodeRef
                AND modul_reff = 'pembelian'
                AND info_reff = :infoRef
        ";
        $params = [":keterangan" => $keterangan, ":kodeRef" => $kode, ":infoRef" => $revisiKe];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Notifikasi", "Kode Ref", $kode, $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#addrevisi    the original method
     */
    public function actionSaveRevisiNilai(): void
    {
        [   "kode" => $kode,
            "keterangan" => $keterangan,
            "revisike" => $revisiKe,
            "tipe_doc" => $tipeDokumen,
            "nilai_total" => $nilaiTotal,
            "ppn" => $ppn,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "ver_revisi" => $verRevisi,
            "ver_usrrevisi" => $verUserRevisi,
            "ver_tglrevisi" => $verTanggalRevisi,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "harga_kemasan" => $daftarHargaKemasan,
            "diskon_item" => $daftarDiskonItem,
            "diskon_harga" => $daftarDiskonHarga,
            "kemasan" => $daftarKemasan,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "jumlah_item" => $daftarJumlahItem,
            "harga_item" => $daftarHargaItem,
        ] = Yii::$app->request->post();

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();
        $fm = new FarmasiModel;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog     AS idKatalog,     -- in use
                A.kemasan        AS kemasan,       -- in use
                A.id_kemasan     AS idKemasan,
                A.isi_kemasan    AS isiKemasan,    -- in use
                A.id_kemasandepo AS idKemasanDepo,
                A.jumlah_kemasan AS jumlahKemasan,
                A.jumlah_item    AS jumlahItem,
                A.harga_kemasan  AS hargaKemasan,  -- in use
                A.harga_item     AS hargaItem,
                A.diskon_item    AS diskonItem,    -- in use
                A.diskon_harga   AS diskonHarga,
                B.nama_sediaan   AS namaSediaan    -- in use
            FROM db1.tdetailf_pembelian AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailPembelian = $connection->createCommand($sql, $params)->queryAll();

        $daftarDetailPembelian2 = [];
        foreach ($daftarIdKatalog as $i => $idkatalog) {
            $daftarDetailPembelian2[$idkatalog] = [
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
        $daftarFieldDetail = array_keys($daftarDetailPembelian2[$idkatalog ?? 0] ?? []);

        $update = false;
        $keteranganDetail = ""; // untuk penanda jika pbf/mata anggaran
        foreach ($daftarDetailPembelian as $i => $d) {
            $idKatalog = $d->idKatalog;
            $detailPembelian = $daftarDetailPembelian2[$idKatalog];
            [   "isi_kemasan" => $isi,
                "harga_kemasan" => $harga,
                "diskon_item" => $diskon,
                "kemasan" => $kemasan,
            ] = $detailPembelian;

            if ($d->isiKemasan == $isi && $d->hargaKemasan == $harga && $d->diskonItem == $diskon) continue;
            $update = true;

            $iwhere = ["kode_reff" => $kode, "id_katalog" => $idKatalog];
            $berhasilUbah = $fm->saveData("tdetailf_pembelian", $daftarFieldDetail, $detailPembelian, $iwhere);
            if ($berhasilUbah) throw new FailToUpdateException("Detail Pembelian", "Kode Ref, Id Katalog", "$kode, $idKatalog", $transaction);

            $temp  = $d->isiKemasan == $isi     ? "" : "{$d->kemasan} => $kemasan, ";
            $temp .= $d->hargaKemasan == $harga ? "" : "{$d->hargaKemasan} => $harga, ";
            $temp .= $d->diskonItem == $diskon  ? "" : "{$d->diskonItem} => $diskon ";
            $keteranganDetail .= "{$d->namaSediaan}: $temp | ";
        }

        $keterangan .= " || Perubahan Nilai: " . ($keteranganDetail ?: "-");
        $dataPembelian = [
            "keterangan" => $keterangan,
            "tipe_doc" => $tipeDokumen,
            "ppn" => $ppn ?? 0,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
        ];

        // set Verifikasi Revisi
        $verifikasiReferensi = "";
        if ($verRevisi == 1) {
            $dataPembelian = [
                ...$dataPembelian,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $verUserRevisi,
                "ver_tglrevisi" => $verTanggalRevisi,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => null,
            ];

            $verifikasiReferensi .= ",
                verif_reff = 1,
                modul_exec = 'penerimaan',
                action_exec = 'addrevisi'
            ";

            $verifikasiReferensi .= $update ? "" : ",
                ver_execution = 1,
                ver_usrexecution = '$idUser',
                ver_tglexecution = '$nowValSystem'
            ";
        }
        $daftarField = array_keys($dataPembelian);

        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_pembelian", $daftarField, $dataPembelian, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksi_notification
            SET
                description_reff = CONCAT(description_reff, :keterangan)
                $verifikasiReferensi
            WHERE
                tipe_notif = 'R'
                AND kode_reff = :kodeRef
                AND modul_reff = 'pembelian'
                AND info_reff = :infoRef
        ";
        $params = [":keterangan" => $keterangan, ":kodeRef" => $kode, ":infoRef" => $revisiKe];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Notifikasi", "Kode Ref", $kode, $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#addrevisi    the original method
     */
    public function actionSaveRevisiKatalog(): void
    {
        [   "kode" => $kode,
            "keterangan" => $keterangan,
            "revisike" => $revisiKe,
            "tipe_doc" => $tipeDokumen,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "ver_revisi" => $verRevisi,
            "ver_usrrevisi" => $verUserRevisi,
            "ver_tglrevisi" => $verTanggalRevisi,
            "kode_reffhps" => $daftarKodeRefHps, // array or single?
            "kode_reffrenc" => $daftarKodeRefRencana,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "harga_kemasan" => $daftarHargaKemasan,
            "diskon_item" => $daftarDiskonItem,
            "diskon_harga" => $daftarDiskonHarga,
            "no_urut" => $daftarNoUrut,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "jumlah_item" => $daftarJumlahItem,
            "harga_item" => $daftarHargaItem,
            "id_reffkatalog" => $daftarIdRefKatalog,
        ] = Yii::$app->request->post();

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();
        $fm = new FarmasiModel;

        $update = false;
        $dataDetailPembelian = [];
        foreach ($daftarIdKatalog as $i => $idKatalog) {
            if ($tipeDokumen != 0 && $daftarJumlahKemasan[$i] == 0 && $daftarNoUrut[$i] != 1) continue;
            $update = true; // hypothesis solution based on others

            $dataDetailPembelian[$idKatalog] = [
                "kode_reff" => $kode,
                "kode_reffhps" => $daftarKodeRefHps[$i],
                "kode_reffrenc" => $daftarKodeRefRencana[$i],
                "id_katalog" => $idKatalog,
                "id_reffkatalog" => $daftarIdRefKatalog[$i],
                "no_urut" => $daftarNoUrut[$i],
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
                "userid_updt" => $idUser
            ];
        }

        $keterangan .= " || Perubahan Katalog: menghapus yang lama dan menginputkan yang baru";
        $dataPembelian = [
            "keterangan" => $keterangan,
            "tipe_doc" => $tipeDokumen,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
        ];

        // set Verifikasi Revisi
        $verifikasiReferensi = "";
        if ($verRevisi == 1) {
            $dataPembelian = [
                ...$dataPembelian,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $verUserRevisi,
                "ver_tglrevisi" => $verTanggalRevisi,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => null,
            ];

            $verifikasiReferensi .= ",
                verif_reff = 1,
                modul_exec = 'penerimaan',
                action_exec = 'addrevisi'
            ";

            $verifikasiReferensi .= $update ? "" : ",
                ver_execution = 1,
                ver_usrexecution = '$idUser',
                ver_tglexecution = '$nowValSystem'
            ";
        }
        $daftarField = array_keys($dataPembelian);

        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_pembelian", $daftarField, $dataPembelian, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksi_notification
            SET
                description_reff = CONCAT(description_reff, :keterangan)
                $verifikasiReferensi
            WHERE
                tipe_notif = 'R'
                AND kode_reff = :kodeRef
                AND modul_reff = 'pembelian'
                AND info_reff = :infoRef
        ";
        $params = [":keterangan" => $keterangan, ":kodeRef" => $kode, ":infoRef" => $revisiKe];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Notifikasi", "Kode Ref", $kode, $transaction);

        $iwhere = ["kode_reff" => $kode];
        $berhasilUbah = $fm->saveBatch("tdetailf_pembelian", $dataDetailPembelian, $iwhere);
        if (!$berhasilUbah) throw new FailToUpdateException("Detail Pembelian", "Kode Ref", $kode, $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#getDatarevisi the original method
     */
    public function actionDataRevisi(): array
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                                              AS kode,
                A.revisike                                                          AS revisiKe,
                A.adendumke                                                         AS adendumKe,
                A.keterangan                                                        AS keterangan,
                A.edittype                                                          AS editType,
                A.no_doc                                                            AS noDokumen,
                A.tipe_doc                                                          AS tipeDokumen,
                A.tgl_doc                                                           AS tanggalDokumen,
                A.tgl_jatuhtempo                                                    AS tanggalJatuhTempo,
                A.kode_reffhps                                                      AS kodeRefHps,        -- in use
                A.id_pbf                                                            AS idPemasok,
                A.id_jenisanggaran                                                  AS idJenisAnggaran,
                A.id_sumberdana                                                     AS idSumberDana,
                A.id_subsumberdana                                                  AS idSubsumberDana,
                A.id_carabayar                                                      AS idCaraBayar,
                A.id_jenisharga                                                     AS idJenisHarga,
                A.thn_anggaran                                                      AS tahunAnggaran,
                A.blnawal_anggaran                                                  AS bulanAwalAnggaran,
                A.blnakhir_anggaran                                                 AS bulanAkhirAnggaran,
                A.ppn                                                               AS ppn,
                A.nilai_total                                                       AS nilaiTotal,
                A.nilai_diskon                                                      AS nilaiDiskon,
                A.nilai_ppn                                                         AS nilaiPpn,
                A.nilai_pembulatan                                                  AS nilaiPembulatan,
                A.nilai_akhir                                                       AS nilaiAkhir,
                A.sts_saved                                                         AS statusSaved,
                A.sts_linked                                                        AS statusLinked,
                A.sts_revisi                                                        AS statusRevisi,
                A.sysdate_rev                                                       AS sysdateRevisi,
                A.keterangan_rev                                                    AS keteranganRevisi,
                A.sts_closed                                                        AS statusClosed,
                A.sysdate_cls                                                       AS sysdateClosed,
                A.sts_deleted                                                       AS statusDeleted,
                A.sysdate_del                                                       AS sysdateDeleted,
                A.ver_revisi                                                        AS verRevisi,
                A.ver_usrrevisi                                                     AS verUserRevisi,
                A.ver_tglrevisi                                                     AS verTanggalRevisi,
                A.ver_adendum                                                       AS verAdendum,
                A.ver_usradendum                                                    AS verUserAdendum,
                A.ver_tgladendum                                                    AS verTanggalAdendum,
                A.userid_in                                                         AS useridInput,
                A.sysdate_in                                                        AS sysdateInput,
                A.userid_updt                                                       AS useridUpdate,
                A.sysdate_updt                                                      AS sysdateUpdate,
                B.kode                                                              AS kodePemasok,
                B.nama_pbf                                                          AS namaPemasok,
                C.no_doc                                                            AS noHps,
                C.no_docreff                                                        AS noDokumenRef,
                C.kode_reffrenc                                                     AS kodeRefRencana,
                H.blnawal_anggaran                                                  AS bulanAwalAnggaranHps,
                H.blnakhir_anggaran                                                 AS bulanAkhirAnggaranHps,
                H.thn_anggaran                                                      AS tahunAnggaranHps,
                hB.nama_pbf                                                         AS namaPemasokHps,
                (H.nilai_total - H.nilai_diskon + H.nilai_ppn + H.nilai_pembulatan) AS nilaiHps,
                D.subjenis_anggaran                                                 AS subjenisAnggaran,
                E.sumber_dana                                                       AS sumberDana,
                F.jenis_harga                                                       AS jenisHarga,
                G.cara_bayar                                                        AS caraBayar,
                I.tipe_doc                                                          AS tipePembelian
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN db1.transaksif_pengadaan AS H ON A.kode_reffhps = H.kode
            LEFT JOIN db1.masterf_pbf AS hB ON H.id_pbf = hB.id
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.transaksif_pengadaan AS C ON A.kode_reffhps = C.kode
            LEFT JOIN db1.masterf_subjenisanggaran AS D ON A.id_jenisanggaran = D.id
            LEFT JOIN db1.masterf_sumberdana AS E ON A.id_sumberdana = E.id
            LEFT JOIN db1.masterf_jenisharga AS F ON A.id_jenisharga = F.id
            LEFT JOIN db1.masterf_carabayar AS G ON A.id_carabayar = G.id
            LEFT JOIN db1.masterf_tipedoc AS I ON A.tipe_doc = I.kode
            WHERE
                A.kode = :kode
                AND I.modul = 'pembelian'
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pembelian = $connection->createCommand($sql, $params)->queryOne();
        if (!$pembelian) throw new DataNotExistException("Pembelian", "Kode", $kode);

        // ambil data detail spk
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                                              AS kodeRef,
                A.kode_reffhps                                           AS kodeRefHps,
                A.kode_reffrenc                                          AS kodeRefRencana,
                A.id_katalog                                             AS idKatalog,
                A.id_reffkatalog                                         AS idRefKatalog,
                A.no_urut                                                AS noUrut,
                A.id_kemasan                                             AS idKemasan,
                A.id_kemasandepo                                         AS idKemasanDepo,
                A.isi_kemasan                                            AS isiKemasan,
                A.kemasan                                                AS kemasan,
                A.jumlah_item                                            AS jumlahItem,
                A.jumlah_kemasan                                         AS jumlahKemasan,
                A.harga_item                                             AS hargaItem,
                A.harga_kemasan                                          AS hargaKemasan,
                A.diskon_item                                            AS diskonItem,
                A.diskon_harga                                           AS diskonHarga,
                B.id_pbf                                                 AS idPemasok,
                C.nama_sediaan                                           AS namaSediaan,
                C.id_kemasanbesar                                        AS idKemasanKat,
                C.kemasan                                                AS kemasanKat,
                C.id_kemasankecil                                        AS idKemasanDepoKat,
                C.isi_kemasan                                            AS isiKemasanKat,
                C.harga_beli                                             AS hargaItemKat,
                C.harga_beli * diskon_beli                               AS hargaKemasanKat,
                D.nama_pabrik                                            AS namaPabrik,
                E.kode                                                   AS satuanJual,
                F.kode                                                   AS satuan,
                Gg.kode                                                  AS satuanJualKat,
                G.kode                                                   AS satuanKat,
                IFNULL(H.harga_item, A.harga_item)                       AS hargaItemPemasok,
                IFNULL(H.diskon_item, A.diskon_item)                     AS diskonItemPemasok,
                IFNULL(I.jumlah_item, 0)                                 AS jumlahRencana,
                A.jumlah_item                                            AS jumlahPl,
                IFNULL(T.jumlah_item, 0)                                 AS jumlahTerima,
                C.id_pabrik                                              AS idPabrik,
                It.no_doc                                                AS noDokumenRencana,
                IFNULL(Rt.jumlah_item, 0)                                AS jumlahRetur,
                tH.jumlah_item                                           AS jumlahHps,
                IFNULL(Ro.jumlah_item, 0)                                AS jumlahRo,
                IFNULL(S.jumlah_item, 0)                                 AS jumlahDo,
                (A.jumlah_item / C.jumlah_itembeli * C.jumlah_itembonus) AS jumlahBonus
            FROM db1.tdetailf_pembelian AS A
            LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_katalog AS C ON C.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS D ON D.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS E ON E.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS F ON F.id = A.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS Gg ON Gg.id = C.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS G ON G.id = C.id_kemasankecil
            LEFT JOIN db1.relasif_katalogpbf AS H ON A.id_katalog = H.id_katalog
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON A.kode_reffhps = tH.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS I ON I.kode_reff = A.kode_reffrenc
            LEFT JOIN db1.transaksif_perencanaan AS It ON It.kode = A.kode_reffrenc
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_perencanaan AS A
                LEFT JOIN db1.transaksif_perencanaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpl = :kode
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS Ro ON A.kode_reff = Ro.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_pemesanan AS A
                LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpl = :kode
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS S ON A.kode_reff = S.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpl = :kode
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS T ON A.kode_reff = T.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reffpl = :kode
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS Rt ON A.kode_reff = Rt.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = Rt.id_katalog
                AND A.id_katalog = T.id_katalog
                AND A.id_katalog = S.id_katalog
                AND A.id_katalog = Ro.id_katalog
                AND I.id_katalog = A.id_reffkatalog
                AND A.id_reffkatalog = tH.id_reffkatalog
                AND H.id_pbf = B.id_pbf
                AND H.id_jenisharga = B.id_jenisharga
            ORDER BY id_reffkatalog, no_urut
        ";
        $params = [":kode" => $kode];
        $daftarDetailPembelian = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode   AS kode,
                A.no_doc AS noDokumen
            FROM db1.transaksif_perencanaan AS A
            LEFT JOIN db1.tdetailf_pengadaan AS B ON A.kode = B.kode_reffrenc
            WHERE B.kode_reff = :kodeRef
            GROUP BY A.kode
        ";
        $params = [":kodeRef" => $pembelian->kodeRefHps];
        $daftarPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return [$pembelian, $daftarDetailPembelian, $daftarPerencanaan];
    }

    /**
     * TODO: need more attention
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#views    the original method
     */
    public function actionViewData(): string
    {
        [   "kode" => $kode,
            "revisiKe" => $revisiKe,
            "addke" => $addKe,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                  AS kodePembelian,
                A.no_doc                AS noDokumenPembelian,
                I.no_doc                AS noHps,
                I.no_docreff            AS noDokumenRef,
                A.tgl_doc               AS tanggalDokumenPembelian,
                A.tgl_jatuhtempo        AS tanggalJatuhTempo,
                A.tipe_doc              AS tipeDokumen,
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
                A.id_jenisharga         AS idJenisHarga,
                F.cara_bayar            AS caraBayar,
                IFNULL(G.nama_pbf, '-') AS namaPemasok,
                A.nilai_pembulatan      AS nilaiPembulatan,
                A.revisike              AS revisiKe,
                A.keterangan            AS keterangan,
                A.keterangan_rev        AS keteranganRevisi,
                A.ver_revisi            AS verRevisi,
                NULL                    AS daftarRevisiPembelian
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.masterf_pbf AS G ON A.id_pbf = G.id
            LEFT JOIN db1.transaksif_pengadaan AS I ON I.kode = A.kode_reffhps
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pembelian = $connection->createCommand($sql, $params)->queryOne();
        if (!$pembelian) throw new DataNotExistException("Pembelian", "Kode", $kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog                AS idKatalog,
                A.kemasan                   AS kemasan,
                A.isi_kemasan               AS isiKemasan,
                A.jumlah_kemasan            AS jumlahKemasan,
                A.harga_kemasan             AS hargaKemasan,
                A.harga_item                AS hargaItem,
                A.diskon_item               AS diskonItem,
                A.diskon_harga              AS diskonHarga,
                A.kode_reffrenc             AS kodeRefRencana,
                A.kode_reffhps              AS kodeRefHps,
                KAT.nama_sediaan            AS namaSediaan,
                KAT.jumlah_itembeli         AS jumlahItemBeli,
                KAT.jumlah_itembonus        AS jumlahItemBonus,
                PBK.nama_pabrik             AS namaPabrik,
                IFNULL(A.jumlah_item, 0)    AS jumlahPl,
                IFNULL(renc.jumlah_item, 0) AS jumlahRencana,
                IFNULL(tH.jumlah_item, 0)   AS jumlahHps,
                IFNULL(tRet.jumlah_item, 0) AS jumlahRetur
            FROM db1.tdetailf_revpembelian AS A
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KBSR ON KBSR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KKCL ON KKCL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON A.kode_reffhps = tH.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS renc ON A.kode_reffrenc = renc.kode_reff
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
            ) AS trm ON A.kode_reff = trm.kode_reffpl
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
                A.kode_reff = :kode
                AND A.revisike = :val
                AND A.id_katalog = tRet.id_katalog
                AND A.id_katalog = trm.id_katalog
                AND A.id_katalog = tS.id_katalog
                AND A.id_reffkatalog = renc.id_katalog
                AND A.id_reffkatalog = tH.id_reffkatalog
            ORDER BY A.id_reffkatalog, A.no_urut
        ";
        if (isset($addKe)) {
            $sql = str_replace("db1.tdetailf_revpembelian", "rsupf_revisi.tdetailf_revaddpembelian", $sql);
            $sql = str_replace("AND A.revisike = :val", "AND A.adendumke = :val", $sql);
        } elseif (isset($revisiKe)) {
            // nothing to replace
        } else {
            $sql = str_replace("db1.tdetailf_revpembelian", "db1.tdetailf_pembelian", $sql);
            $sql = str_replace("AND A.revisike = :val", "", $sql);
        }

        $params = [
            ":kode" => $kode,
            ":val" => $revisiKe || $addKe,
        ];
        $pembelian->daftarRevisiPembelian = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($pembelian);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#prints    the original method
     */
    public function actionPrint(): string
    {
        ["kode" => $kode, "tipeDokumen" => $tipeDokumen] = Yii::$app->request->post();

        $c = new JasperClient("http://192.168.3.34:8080/jasperserver", "jasperadmin", "jasperadmin");
        $connection = Yii::$app->dbFatma;

        if ($tipeDokumen) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT A.id_jenisharga
                FROM db1.transaksif_pembelian AS A
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $kode];
            $idJenisHarga = $connection->createCommand($sql, $params)->queryScalar();
            if (!$idJenisHarga) throw new DataNotExistException("Pembelian", "Kode", $kode);

            $harga = ($idJenisHarga == 2) ? "ekatalog" : "umum";

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    nilai_akhir   AS nilaiAkhir,
                    id_jenisharga AS idJenisHarga
                FROM db1.transaksif_pembelian
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $kode];
            $pembelian = $connection->createCommand($sql, $params)->queryOne();
            if (!$pembelian) throw new DataNotExistException("Pembelian", "Kode", $kode);

            $mapHarga = [
                [          0,  50_000_000, "sp"],
                [ 50_000_000, 200_000_000, "spk"],
                [200_000_000, PHP_INT_MAX, "kontrak"],
            ];
            foreach ($mapHarga as $item) {
                [$batasBawah, $batasAtas, $tipe] = $item;
                if ($batasBawah < $pembelian->nilaiAkhir && $pembelian->nilaiAkhir <= $batasAtas) {
                    $tipeDokumen = $tipe;
                }
            }

            $harga = ($pembelian->idJenisHarga == 2) ? "ekatalog" : "umum";
        }

        $parameter = ["kode_reff" => $kode];

        if ($tipeDokumen == "sp" and $harga == "ekatalog") {
            $laporan = "/reports/Farmasi/lap_revisi_pembelian_ekatalog";

        } elseif ($tipeDokumen == "sp") {
            $laporan = "/reports/Farmasi/lap_revisi_pembelian";

        } elseif ($harga == "ekatalog") {
            $laporan = "/reports/Farmasi/lap_pembelian_ekatalog";

        } else {
            $laporan = "/reports/Farmasi/lap_pembelian";
        }

        $report = $c->reportService()->runReport($laporan, "pdf", null, null, $parameter);
        return $this->renderPdf($report, "report");
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionReportlaporanAkhir(): string
    {
        [   "kode" => $kode,
            "format" => $format,
            "id_jenisanggaran" => $idJenisAnggaran,
            "tgl_mulai" => $tanggalMulai,
            "tgl_akhir" => $tanggalAkhir,
            "subjenis_anggaran" => $subjenisAnggaran,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $kode = $kode ? "'" . str_replace(",", "','", $kode) . "'" : $kode;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                                AS kode,
                A.no_doc                                              AS noDokumen,
                ((iA.Ht - iA.Hd) * A.ppn)                             AS hPpn,
                ROUND((iA.Ht - iA.Hd + ((iA.Ht - iA.Hd) * A.ppn)), 2) AS nilaiAkhir,
                B.kode_reff                                           AS kodeDo,
                B.no_doc                                              AS noDo,
                ROUND((B.Ht - B.Hd + B.H_ppn), 0)                     AS nilaiAkhirDo,
                D.nama_pbf                                            AS namaPemasok
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN (
                SELECT
                    A.kode_reff                                                 AS kode_reff,
                    SUM(A.jumlah_kemasan * A.harga_kemasan)                     AS Ht,
                    SUM(A.jumlah_kemasan * A.harga_kemasan * A.diskon_item/100) AS Hd
                FROM db1.tdetailf_pembelian AS A
                GROUP BY A.kode_reff
            ) AS iA ON A.kode = iA.kode_reff
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN (
                SELECT
                    A.kode_reffpl                                                 AS kode_reffpl,
                    A.kode_reff                                                   AS kode_reff,
                    B.no_doc                                                      AS no_doc,
                    SUM(A.jumlah_kemasan * A.harga_kemasan)                       AS Ht,
                    SUM(A.jumlah_kemasan * A.harga_kemasan * A.diskon_item / 100) AS Hd,
                    (SUM(A.jumlah_kemasan * A.harga_kemasan) - SUM(A.jumlah_kemasan * A.harga_kemasan * A.diskon_item/100)) * B.ppn / 100 AS H_ppn
                FROM db1.tdetailf_pemesanan AS A
                INNER JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reff
            ) AS B ON A.kode = B.kode_reffpl
            WHERE
                A.sts_deleted = 0
                AND ('$kode' = '' OR A.kode IN ($kode))
                AND (:idJenisAnggaran = '' OR A.id_jenisanggaran = :idJenisAnggaran)
                AND (:tanggalMulai = '' OR A.tgl_doc >= :tanggalMulai)
                AND (:tanggalAkhir = '' OR A.tgl_doc <= :tanggalAkhir)
        ";
        $params = [
            ":idJenisAnggaran" => $idJenisAnggaran,
            ":tanggalMulai" => $tanggalMulai ? $toSystemDate($tanggalMulai) : "",
            ":tanggalAkhir" => $tanggalAkhir ? $toSystemDate($tanggalAkhir) : "",
        ];
        $daftarPembelian = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 29;

        foreach ($daftarPembelian as $pembelian) {
            $daftarHalaman[$h][$b] = $pembelian;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        return $this->renderPartial($format, [
            "iTotal" => count($daftarPembelian),    // TODO: php: to be deleted.
            "subjenisAnggaran" => $subjenisAnggaran,
            "daftarHalaman" => $daftarHalaman,
            "tanggalAwal" => $tanggalMulai,
            "tanggalAkhir" => $tanggalAkhir,
            "toUserFloat" => Yii::$app->number->toUserFloat(),
            "toUserDate" => Yii::$app->dateTime->transformFunc("toUserDate"),
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionItemPlTableData(): string
    {
        ["id_katalog" => $idKatalog] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff      AS kodeRef,
                A.kemasan        AS kemasan,
                A.jumlah_item    AS jumlahItem,
                A.jumlah_kemasan AS jumlahKemasan,
                A.harga_item     AS hargaItem,
                A.harga_kemasan  AS hargaKemasan,
                A.diskon_item    AS diskonItem,
                A.diskon_harga   AS diskonHarga,
                B.sts_closed     AS statusClosed,
                B.no_doc         AS noDokumen,
                B.tgl_jatuhtempo AS tanggalJatuhTempo,
                C.nama_pbf       AS namaPemasok,
                E.nama_pabrik    AS namaPabrik
            FROM db1.tdetailf_pembelian AS A
            INNER JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_katalog AS D ON A.id_katalog = D.kode
            LEFT JOIN db1.masterf_pabrik AS E ON A.id_pabrik = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON A.id_kemasan = F.id
            LEFT JOIN db1.masterf_kemasan AS G ON A.id_kemasandepo = G.id
            WHERE
                B.sts_deleted = 0
                AND (:idKatalog = '' OR A.id_katalog = :idKatalog)
            ORDER BY B.sts_closed DESC, B.no_doc ASC
        ";
        $params = [":idKatalog" => $idKatalog];
        $daftarDetailPembelian = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarDetailPembelian);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionRealisasiPlTableData(): string
    {
        ["kode" => $kode] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $arrKode = explode(",", $kode);
        if (count($arrKode)) {
            $arrKode = implode("', '", $arrKode);
            $kodeRefPl = "A.kode_reffpl IN ('$arrKode')";
            $kodeRef = "A.kode_reff IN ('$arrKode')";
        } else {
            $kodeRefPl = "TRUE";
            $kodeRef = "TRUE";
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                B.no_doc                                           AS noDokumen,
                B.tgl_jatuhtempo                                   AS tanggalJatuhTempo,
                C.nama_pbf                                         AS namaPemasok,
                D.nama_sediaan                                     AS namaSediaan,
                E.nama_pabrik                                      AS namaPabrik,
                A.kemasan                                          AS kemasan,
                A.jumlah_item                                      AS jumlahPl,
                A.harga_item                                       AS hargaPl,
                F.kode                                             AS satuan,
                IFNULL(G.jumlah_item, 0)                           AS jumlahTerima,
                IFNULL(G.harga_item, 0)                            AS hargaTerima,
                A.jumlah_item - IFNULL(G.jumlah_item, 0)           AS jumlahSisa,
                H.jumlah_item                                      AS jumlahRencana,
                A.id_reffkatalog                                   AS idRefKatalog,
                IF(A.jumlah_item = 0, H.jumlah_item * A.harga_item, A.jumlah_item * A.harga_item) AS hargaAnggaran, -- export-to-excel only
                IFNULL(G.jumlah_item, 0) * IFNULL(G.harga_item, 0) AS hargaRealisasi -- export-to-excel only
            FROM db1.tdetailf_pembelian AS A
            INNER JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_katalog AS D ON A.id_katalog = D.kode
            LEFT JOIN db1.masterf_pabrik AS E ON D.id_pabrik = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON D.id_kemasankecil = F.id
            LEFT JOIN (
                SELECT
                    A.kode_reffpl,
                    A.id_katalog,
                    IFNULL(SUM(A.jumlah_item), 0) jumlah_item,
                    IFNULL(AVG(A.harga_item), 0) harga_item
                FROM db1.tdetailf_penerimaan AS A
                INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND $kodeRefPl
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS G ON A.kode_reff = G.kode_reffpl
            LEFT JOIN db1.tdetailf_perencanaan AS H ON A.kode_reffrenc = H.kode_reff
            WHERE
                B.sts_deleted = 0
                AND A.id_katalog = H.id_katalog
                AND A.id_katalog = G.id_katalog
                AND $kodeRef
            ORDER BY B.no_doc ASC, D.nama_barang ASC
        ";
        $daftarDetailPembelian = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarDetailPembelian);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-fb26efd
     */
    public function actionSearchJsonLainnya(): string
    {
        [   "noDokumen" => $noDokumen,
            "idJenisAnggaran" => $idJenisAnggaran,
            "statusRevisi" => $statusRevisi,
            "nama_pbf" => $namaPemasok, // not used in views
            "kode" => $kode, // not used in views
            "sts_closed" => $statusClosed, // not used in views
            "ver_revisi" => $verRevisi // not used in views
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                BLI.kode              AS kode, -- 1 2 3 4 5 6 7 8
                BLI.no_doc            AS noDokumen, -- 1 2 3 4 5 6 7 8
                BLI.revisike          AS revisiKe,
                BLI.adendumke         AS adendumKe,
                BLI.blnawal_anggaran  AS bulanAwalAnggaran, -- 1 2 3 4 5 6 7 8
                BLI.blnakhir_anggaran AS bulanAkhirAnggaran, -- 1 2 3 4 5 6 7 8
                BLI.thn_anggaran      AS tahunAnggaran, -- 1 2 3 4 5 6 7 8
                BLI.id_jenisanggaran  AS idJenisAnggaran,
                BLI.id_sumberdana     AS idSumberDana,
                BLI.id_subsumberdana  AS idSubsumberDana,
                BLI.id_jenisharga     AS idJenisHarga, -- 1 2 3 4 5 6 7 8
                BLI.id_carabayar      AS idCaraBayar,
                BLI.ppn               AS ppn,
                BLI.id_pbf            AS idPemasok, -- 1 2 3 4 5 6 7 8
                BLI.tipe_doc          AS tipeDokumen, -- 1 2 3 4 5 6 7 8
                BLI.tgl_jatuhtempo    AS tanggalJatuhTempo,
                BLI.sts_closed        AS statusClosed,
                BLI.tgl_doc           AS tanggalDokumen,
                BLI.nilai_akhir       AS nilaiAkhir,
                PBF.nama_pbf          AS namaPemasok, -- 1 2 3 4 5 6 7 8
                PBF.kode              AS kodePemasok,
                SJA.subjenis_anggaran AS subjenisAnggaran, -- 1 2 3 4 5 6 7 8
                JH.jenis_harga        AS jenisHarga
            FROM db1.transaksif_pembelian AS BLI
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON BLI.id_jenisanggaran = SJA.id
            LEFT JOIN db1.masterf_jenisharga AS JH ON BLI.id_jenisharga = JH.id
            LEFT JOIN db1.masterf_pbf AS PBF ON BLI.id_pbf = PBF.id
            WHERE
                (:noDokumen = '' OR BLI.no_doc LIKE :noDokumen)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:kode = '' OR BLI.kode = :kode)
                AND (:statusRevisi = '' OR BLI.sts_revisi = :statusRevisi)
                AND (:statusClosed = '' OR BLI.sts_closed = :statusClosed)
                AND (:verRevisi = '' OR BLI.ver_revisi = :verRevisi)
                AND (:idJenisAnggaran = '' OR BLI.id_jenisanggaran = :idJenisAnggaran)
                AND BLI.sts_deleted = 0
            ORDER BY BLI.no_doc ASC
            LIMIT 30
        ";
        $params = [
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":kode" => $kode,
            ":idJenisAnggaran" => $idJenisAnggaran,
            ":statusRevisi" => $statusRevisi,
            ":statusClosed" => $statusClosed,
            ":verRevisi" => $verRevisi,
        ];
        $daftarPembelian = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPembelian);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-fb26efd
     */
    public function actionSearchJsonDetailTerima(): string
    {
        ["kode_reff_not" => $kodeRefNot, "kode_reff" => $kodeRef] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                   AS kodeRefPl,
                A.kode_reffrenc               AS kodeRefRencana,
                A.kode_reffhps                AS kodeRefHps,
                ''                            AS kodeRefRo,
                ''                            AS kodeRefPo,
                A.id_reffkatalog              AS idRefKatalog,
                A.id_katalog                  AS idKatalog,
                IFNULL(A.kemasan, 0)          AS kemasan,
                B.id_pabrik                   AS idPabrik,
                A.id_kemasan                  AS idKemasan,
                A.isi_kemasan                 AS isiKemasan,
                A.id_kemasandepo              AS idKemasanDepo,
                A.jumlah_item                 AS jumlahItem,
                A.jumlah_kemasan              AS jumlahKemasan,
                A.harga_item                  AS hargaItem,
                A.harga_kemasan               AS hargaKemasan,
                A.diskon_item                 AS diskonItem,
                A.diskon_harga                AS diskonHarga,
                B.nama_sediaan                AS namaSediaan,
                B.kemasan                     AS kemasanKat,
                B.id_kemasanbesar             AS idKemasanKat,
                B.id_kemasankecil             AS idKemasanDepoKat,
                B.isi_kemasan                 AS isiKemasanKat,
                B.harga_beli                  AS hargaItemKat,
                B.harga_beli * B.isi_kemasan  AS hargaKemasanKat,
                B.diskon_beli                 AS diskonItemKat,
                C.nama_pabrik                 AS namaPabrik,
                D.kode                        AS satuanJual,
                E.kode                        AS satuan,
                Dk.kode                       AS satuanJualKat,
                Ek.kode                       AS satuanKat,
                A.jumlah_item                 AS jumlahPl,
                0                             AS jumlahPo,
                IFNULL(H.jumlah_item, 0)      AS jumlahTerima,
                IFNULL(F.terimake, 0)         AS terimaKe,
                B.jumlah_itembeli             AS jumlahItemBeli,
                B.jumlah_itembonus            AS jumlahItemBonus,
                IFNULL(Tb.jumlah_item, 0)     AS jumlahTBonus,
                IFNULL(Rt.jumlah_item, 0)     AS jumlahRetur,
                ROUND((A.jumlah_item / B.jumlah_itembeli) * B.jumlah_itembonus, 2) AS jumlahBonus,
                IFNULL(Tb.jumlah_retbonus, 0) AS jumlahReturBonus
            FROM db1.tdetailf_pembelian AS A
            LEFT JOIN (
                SELECT
                    A.kode_reffpl        AS kode_reffpl,
                    COUNT(A.kode_reffpl) AS terimake
                FROM db1.transaksif_penerimaan AS A
                WHERE A.sts_deleted = 0
                GROUP BY A.kode_reffpl
            ) AS F ON A.kode_reff = F.kode_reffpl
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
            LEFT JOIN db1.masterf_kemasan AS E ON A.id_kemasandepo = E.id
            LEFT JOIN db1.masterf_kemasan AS Dk ON B.id_kemasanbesar = Dk.id
            LEFT JOIN db1.masterf_kemasan AS Ek ON B.id_kemasankecil = Ek.id
            LEFT JOIN (
                SELECT
                    A.kode_reffpl            AS kode_reffpl,
                    A.id_katalog             AS id_katalog,
                    SUM(A.jumlah_item)       AS jumlah_item,
                    SUM(Rtb.jumlah_retbonus) AS jumlah_retbonus
                FROM db1.tdetailf_penerimaan AS A
                INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.kode_refftrm     AS kode_refftrm,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_retbonus
                    FROM db1.tdetailf_return AS A
                    INNER JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_refftrm, A.id_katalog
                ) AS Rtb ON A.kode_reffpl = Rtb.kode_reffpl
                WHERE
                    B.sts_deleted = 0
                    AND (:kodeRefNot = '' OR kode_reff != :kodeRefNot)
                    AND A.id_katalog = Rtb.id_katalog
                    AND B.tipe_doc = 1
                    AND A.kode_reff = Rtb.kode_refftrm
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS Tb ON Tb.id_katalog = A.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS Rt ON Rt.id_katalog = A.id_katalog
            LEFT JOIN (
                SELECT
                    X.kode_reffpl    AS kode_reffpl,
                    id_katalog       AS id_katalog,
                    SUM(jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS X
                LEFT JOIN db1.transaksif_penerimaan AS Y ON X.kode_reff = Y.kode
                WHERE
                    sts_deleted = 0
                    AND (:kodeRefNot = '' OR kode_reff != :kodeRefNot)
                GROUP BY X.kode_reffpl, id_katalog
            ) AS H ON H.id_katalog = A.id_katalog
            WHERE
                A.kode_reff = :kodeRef
                AND A.kode_reff = H.kode_reffpl
                AND A.kode_reff = Rt.kode_reffpl
                AND A.kode_reff = Tb.kode_reffpl
        ";
        $params = [":kodeRef" => $kodeRef, ":kodeRefNot" => $kodeRefNot];
        $iData = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($iData);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-fb26efd
     */
    public function actionSearchJsonDetailSpb(): string
    {
        ["kode_reff" => $kodeRef] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                  AS kodeRef,
                A.kode_reff                  AS kodeRefPl,
                A.kode_reffhps               AS kodeRefHps,
                A.kode_reffrenc              AS kodeRefRencana,
                A.id_reffkatalog             AS idRefKatalog,
                A.id_katalog                 AS idKatalog,
                IFNULL(A.kemasan, 0)         AS kemasan,
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
                B.isi_kemasan                AS isiKemasanKat,
                B.id_kemasankecil            AS idKemasanDepoKat,
                B.harga_beli                 AS hargaItemKat,
                B.harga_beli * B.isi_kemasan AS hargaKemasanKat,
                B.diskon_beli                AS diskonItemKat,
                B.kemasan                    AS kemasanKat,
                B.jumlah_itembeli            AS jumlahItemBeli,
                B.jumlah_itembonus           AS jumlahItemBonus,
                C.nama_pabrik                AS namaPabrik,
                D.kode                       AS satuanJual,
                E.kode                       AS satuan,
                Dk.kode                      AS satuanJualKat,
                Ek.kode                      AS satuanKat,
                IFNULL(F.jumlah_item, 0)     AS jumlahDo,
                A.jumlah_item                AS jumlahPl,
                IFNULL(H.jumlah_item, 0)     AS jumlahTerima,
                G.tipe_doc                   AS tipePl,
                IFNULL(I.jumlah_item, 0)     AS jumlahRencana,
                G.tgl_doc                    AS tanggalDokumen,
                G.tgl_jatuhtempo             AS tanggalJatuhTempo,
                0                            AS jumlahRetur,
                0                            AS jumlahBonus,
                IFNULL(tH.jumlah_item, 0)    AS jumlahHps
            FROM db1.tdetailf_pembelian AS A
            LEFT JOIN db1.transaksif_pembelian AS G ON A.kode_reff = G.kode
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
            LEFT JOIN db1.masterf_kemasan AS E ON A.id_kemasandepo = E.id
            LEFT JOIN db1.masterf_kemasan AS Dk ON B.id_kemasanbesar = Dk.id
            LEFT JOIN db1.masterf_kemasan AS Ek ON B.id_kemasankecil = Ek.id
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON A.kode_reffhps = tH.kode_reff
            LEFT JOIN (
                SELECT
                    X.kode_reffpl    AS kode_reffpl,
                    id_katalog       AS id_katalog,
                    SUM(jumlah_item) AS jumlah_item
                FROM db1.tdetailf_perencanaan AS X
                LEFT JOIN db1.transaksif_perencanaan AS Y ON X.kode_reff = Y.kode
                WHERE sts_deleted = 0
                GROUP BY X.kode_reffpl, id_katalog
            ) AS F ON F.id_katalog = A.id_katalog
            LEFT JOIN db1.tdetailf_perencanaan AS I ON A.id_reffkatalog = I.id_katalog
            LEFT JOIN (
                SELECT
                    X.kode_reffpl    AS kode_reffpl,
                    id_katalog       AS id_katalog,
                    SUM(jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS X
                LEFT JOIN db1.transaksif_penerimaan AS Y ON X.kode_reff = Y.kode
                WHERE sts_deleted = 0
                GROUP BY X.kode_reffpl, id_katalog
            ) AS H ON H.id_katalog = A.id_katalog
            WHERE
                A.kode_reff = :kodeRef
                AND G.sts_closed = 0
                AND (G.tipe_doc = 0 OR (G.tipe_doc != 0 AND A.jumlah_kemasan != 0))
                AND A.kode_reff = H.kode_reffpl
                AND A.kode_reffrenc = I.kode_reff
                AND A.id_reffkatalog = tH.id_reffkatalog
                AND A.kode_reff = F.kode_reffpl
            ORDER BY B.nama_sediaan
        ";
        $params = [":kodeRef" => $kodeRef];
        $iData = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($iData);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-fb26efd
     */
    public function actionSearchJsonReffPl(): string
    {
        ["kode" => $kode] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode              AS kode,
                IFNULL(R.j_spb, 0)  AS jSpb,                -- in use
                O.kode_reffpo       AS kodeRefPo,
                O.kode_reffpl       AS kodeRefPl,
                O.no_doc            AS noDokumen,
                O.tgl_doc           AS tanggalDokumen,
                O.tgl_tempokirim    AS tanggalTempoKirim,
                O.thn_anggaran      AS tahunAnggaran,
                O.blnawal_anggaran  AS bulanAwalAnggaran,
                O.blnakhir_anggaran AS bulanAkhirAnggaran,
                O.nilai_akhir       AS nilaiAkhir,
                O.keterangan        AS keterangan,
                O.keterangan_rev    AS keteranganRevisi
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN (
                SELECT
                    A.kode_reffpl        AS kode_reffpl,
                    COUNT(A.kode_reffpl) AS j_spb
                FROM db1.transaksif_perencanaan AS A
                WHERE A.sts_deleted = 0
                GROUP BY A.kode_reffpl
            ) AS R ON A.kode = R.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode AS kode_reffpo,
                    A.kode_reffpl,
                    A.no_doc,
                    A.tgl_doc,
                    A.tgl_tempokirim,
                    A.thn_anggaran,
                    A.blnawal_anggaran,
                    A.blnakhir_anggaran,
                    A.nilai_akhir,
                    A.keterangan,
                    A.keterangan_rev
                FROM db1.transaksif_pemesanan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode = B.kode_reffpo
                WHERE
                    A.sts_deleted = 0
                    AND A.sts_closed = 0
                    AND A.nilai_akhir != IFNULL(B.nilai_akhir, 0)
                    AND B.sts_deleted = 0
            ) AS O ON A.kode = O.kode_reffpl
            WHERE
                A.kode = :kode
                AND A.sts_closed = 0
                AND A.sts_revisi = 0
            ORDER BY O.tgl_doc DESC
        ";
        $params = [":kode" => $kode];
        $iData = $connection->createCommand($sql, $params)->queryAll();
        $iTotal = $iData ? $iData[0]->jSpb : count($iData);

        return json_encode([$iTotal, $iData]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#ajaxDelete    the original method
     */
    public function actionAjaxDelete(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        ["keterangan" => $keterangan, "kode" => $kode] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pembelian
            SET
                no_doc = kode,
                keterangan = CONCAT('Hapus PL Pembelian dengan No: ', :keterangan),
                sts_deleted = 1,
                sysdate_del = :tanggalHapus
            WHERE
                kode = :kode
                AND sts_deleted = 0
                AND sts_closed = 0
                AND sts_linked = 0
        ";
        $params = [":keterangan" => $keterangan, ":tanggalHapus" => $nowValSystem, ":kode" => $kode];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilUbah);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#getUpdateTrn the original method
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
                FROM db1.transaksif_pembelian
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
                FROM db1.transaksif_pembelian
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
