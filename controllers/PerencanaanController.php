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
use tlm\his\FatmaPharmacy\views\Perencanaan\{ListItem, Rekapitulasi};
use tlm\libs\LowEnd\components\DateTimeException;
use Yii;
use yii\db\Exception;
use yii\web\Response;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class PerencanaanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "jenisDokumen" => $jenisDokumen,
            "tanggalDokumen" => $tanggalDokumen,
            "noDokumen" => $noDokumen,
            "noSpk" => $noSpk,
            "namaPemasok" => $namaPemasok,
            "subjenisAnggaran" => $subjenisAnggaran,
            "bulanAwalAnggaran" => $bulanAnggaran,
            "tahunAnggaran" => $tahunAnggaran,
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
            FROM db1.transaksif_perencanaan AS RCN
            LEFT JOIN db1.transaksif_pembelian AS BL ON RCN.kode_reffpl = BL.kode
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON RCN.id_jenisanggaran = SJA.id
            LEFT JOIN db1.masterf_jenisharga AS JH ON RCN.id_jenisharga = JH.id
            LEFT JOIN db1.masterf_pbf AS PBF ON RCN.id_pbf = PBF.id
            LEFT JOIN db1.masterf_carabayar AS BYR ON RCN.id_carabayar = BYR.id
            LEFT JOIN db1.masterf_tipedoc AS TDC ON RCN.tipe_doc = TDC.kode
            LEFT JOIN db1.user AS USR ON RCN.userid_updt = USR.id
            WHERE
                RCN.sts_deleted = 0
                AND (:jenisDokumen = '' OR TDC.tipe_doc = :jenisDokumen)
                AND (:tanggalDokumen = '' OR RCN.tgl_doc = :tanggalDokumen)
                AND (:noDokumen = '' OR RCN.no_doc LIKE :noDokumen)
                AND (:noSpk = '' OR BL.no_doc LIKE :noSpk)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:subjenisAnggaran = '' OR SJA.subjenis_anggaran = :subjenisAnggaran)
                AND (:bulanAnggaran = '' OR RCN.blnawal_anggaran = :bulanAnggaran OR RCN.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR RCN.thn_anggaran = :tahunAnggaran)
                AND TDC.modul = 'perencanaan'
        ";
        $params = [
            ":jenisDokumen" => $jenisDokumen,
            ":tanggalDokumen" => $tanggalDokumen ? $toSystemDate($tanggalDokumen) : "",
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":noSpk" => $noSpk ? "%$noSpk%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":subjenisAnggaran" => $subjenisAnggaran,
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
        ];
        $jumlahPerencanaan = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                RCN.kode              AS kode,
                RCN.no_doc            AS noDokumen,
                RCN.tgl_doc           AS tanggalDokumen,
                RCN.tipe_doc          AS tipeDokumen,
                RCN.thn_anggaran      AS tahunAnggaran,
                RCN.blnawal_anggaran  AS bulanAwalAnggaran,
                RCN.blnakhir_anggaran AS bulanAkhirAnggaran,
                RCN.nilai_akhir       AS nilaiAkhir,
                RCN.sts_linked        AS statusLinked,
                RCN.sts_revisi        AS statusRevisi,
                RCN.sysdate_updt      AS updatedTime,
                BL.no_doc             AS noSpk,
                BL.sts_revisi         AS statusRevisiPl,
                BL.ver_revisi         AS verRevisiPl,
                SJA.subjenis_anggaran AS subjenisAnggaran,
                PBF.nama_pbf          AS namaPemasok,
                TDC.tipe_doc          AS jenisDokumen,
                USR.name              AS updatedBy
            FROM db1.transaksif_perencanaan AS RCN
            LEFT JOIN db1.transaksif_pembelian AS BL ON RCN.kode_reffpl = BL.kode
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON RCN.id_jenisanggaran = SJA.id
            LEFT JOIN db1.masterf_jenisharga AS JH ON RCN.id_jenisharga = JH.id
            LEFT JOIN db1.masterf_pbf AS PBF ON RCN.id_pbf = PBF.id
            LEFT JOIN db1.masterf_carabayar AS BYR ON RCN.id_carabayar = BYR.id
            LEFT JOIN db1.masterf_tipedoc AS TDC ON RCN.tipe_doc = TDC.kode
            LEFT JOIN db1.user AS USR ON RCN.userid_updt = USR.id
            WHERE
                RCN.sts_deleted = 0
                AND (:jenisDokumen = '' OR TDC.tipe_doc = :jenisDokumen)
                AND (:tanggalDokumen = '' OR RCN.tgl_doc = :tanggalDokumen)
                AND (:noDokumen = '' OR RCN.no_doc LIKE :noDokumen)
                AND (:noSpk = '' OR BL.no_doc LIKE :noSpk)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:subjenisAnggaran = '' OR SJA.subjenis_anggaran = :subjenisAnggaran)
                AND (:bulanAnggaran = '' OR RCN.blnawal_anggaran = :bulanAnggaran OR RCN.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR RCN.thn_anggaran = :tahunAnggaran)
                AND TDC.modul = 'perencanaan'
            ORDER BY tgl_doc ASC
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "recordsFiltered" => $jumlahPerencanaan,
            "data" => $daftarPerencanaan,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#revisi    the original method
     */
    public function actionTableRevisiData(): string
    {
        [   "noDokumen" => $noDokumen,
            "noSpk" => $noSpk,
            "namaPemasok" => $namaPemasok,
            "kodeJenis" => $idSubjenisAnggaran,
            "bulanAnggaran" => $bulanAnggaran,
            "tahunAnggaran" => $tahunAnggaran,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                A.kode              AS kodeRevPerencanaan,
                A.revisike          AS revisiKe,
                A.keterangan        AS keterangan,
                A.no_doc            AS noDokumen,
                Y.no_doc            AS noSpk,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.thn_anggaran      AS tahunAnggaran,
                A.nilai_akhir       AS nilaiAkhir,
                A.ver_tglrevisi     AS verTanggalRevisi,
                B.nama_pbf          AS namaPemasok,
                C.kode              AS kodeJenis,
                D.jenis_harga       AS jenisHarga,
                UREV.name           AS namaUserRevisi
            FROM db1.transaksif_revperencanaan AS A
            LEFT JOIN db1.transaksif_perencanaan AS X ON A.kode = X.kode
            LEFT JOIN db1.transaksif_pembelian AS Y ON A.kode_reffpl = Y.kode
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.masterf_subjenisanggaran AS C ON A.id_jenisanggaran = C.id
            LEFT JOIN db1.masterf_jenisharga AS D ON A.id_jenisharga = D.id
            LEFT JOIN db1.user AS UREV ON A.ver_usrrevisi = UREV.id
            WHERE
                A.sts_deleted = 0
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:noSpk = '' OR Y.no_doc LIKE :noSpk)
                AND (:namaPemasok = '' OR B.nama_pbf LIKE :namaPemasok)
                AND (:idSubjenisAnggaran = '' OR C.id = :idSubjenisAnggaran)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
            ORDER BY A.kode ASC, A.revisike DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":noSpk" => $noSpk ? "%$noSpk%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":idSubjenisAnggaran" => $idSubjenisAnggaran ? "%$idSubjenisAnggaran%" : "",
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
        ];
        $daftarRevisiPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_revperencanaan AS A
            LEFT JOIN db1.transaksif_perencanaan AS X ON A.kode = X.kode
            LEFT JOIN db1.transaksif_pembelian AS Y ON A.kode_reffpl = Y.kode
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            LEFT JOIN db1.masterf_subjenisanggaran AS C ON A.id_jenisanggaran = C.id
            LEFT JOIN db1.masterf_jenisharga AS D ON A.id_jenisharga = D.id
            LEFT JOIN db1.user AS U ON A.ver_usrrevisi = U.id
            WHERE
                A.sts_deleted = 0
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:noSpk = '' OR Y.no_doc LIKE :noSpk)
                AND (:namaPemasok = '' OR B.nama_pbf LIKE :namaPemasok)
                AND (:idSubjenisAnggaran = '' OR C.id = :idSubjenisAnggaran)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
        ";
        $jumlahRevisiPerencanaan = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode([
            "recordsFiltered" => $jumlahRevisiPerencanaan,
            "data" => $daftarRevisiPerencanaan
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @throws DataNotExistException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#addrevisi    the original method
     */
    public function actionFormRevisiData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = "
            SELECT
                A.kode                               AS kode,
                A.revisike                           AS revisiKe,
                A.keterangan                         AS keterangan,
                A.no_doc                             AS noDokumen,
                A.tgl_doc                            AS tanggalDokumen,
                A.tipe_doc                           AS tipeDokumen,
                A.tgl_tempokirim                     AS tanggalTempoKirim,
                A.kode_reffpl                        AS kodeRefPl,
                A.id_pbf                             AS idPemasok,
                A.id_jenisanggaran                   AS idJenisAnggaran,
                A.id_sumberdana                      AS idSumberDana,
                A.id_subsumberdana                   AS idSubsumberDana,
                A.id_carabayar                       AS idCaraBayar,
                A.id_jenisharga                      AS idJenisHarga,
                A.thn_anggaran                       AS tahunAnggaran,
                A.blnawal_anggaran                   AS bulanAwalAnggaran,
                A.blnakhir_anggaran                  AS bulanAkhirAnggaran,
                A.ppn                                AS ppn,
                A.nilai_total                        AS nilaiTotal,
                A.nilai_diskon                       AS nilaiDiskon,
                A.nilai_ppn                          AS nilaiPpn,
                A.nilai_pembulatan                   AS nilaiPembulatan,
                A.nilai_akhir                        AS nilaiAkhir,
                A.sts_saved                          AS statusSaved,
                A.sts_linked                         AS statusLinked,
                A.sts_revisi                         AS statusRevisi,
                A.sysdate_rev                        AS sysdateRevisi,
                A.keterangan_rev                     AS keteranganRevisi,
                A.sts_closed                         AS statusClosed,
                A.sysdate_cls                        AS sysdateClosed,
                A.sts_deleted                        AS statusDeleted,
                A.sysdate_del                        AS sysdateDeleted,
                A.ver_revisi                         AS verRevisi,
                A.ver_usrrevisi                      AS verUserRevisi,
                A.ver_tglrevisi                      AS verTanggalRevisi,
                A.userid_in                          AS inputById,
                A.sysdate_in                         AS inputTime,
                A.userid_updt                        AS updatedById,
                A.sysdate_updt                       AS updatedTime,
                B.no_doc                             AS noSpk,
                B.tgl_doc                            AS tanggalMulai,
                B.tgl_jatuhtempo                     AS tanggalJatuhTempo,
                C.nama_pbf                           AS namaPemasok,
                B.blnakhir_anggaran                  AS bulanAkhirAnggaranPl,
                B.blnawal_anggaran                   AS bulanAwalAnggaranPl,
                B.thn_anggaran                       AS tahunAnggaranPl,
                B.tipe_doc                           AS tipeSpk,
                B.sts_closed                         AS statusPl,
                E.total_anggaran - E.total_realisasi AS sisaAnggaran,
                B.nilai_akhir                        AS nilaiAkhirPl,
                C.kode                               AS kodePemasok,
                B.id_pbf                             AS idPemasok,
                B.id_jenisanggaran                   AS idJenisAnggaran,
                B.id_sumberdana                      AS idSumberDana,
                B.id_jenisharga                      AS idJenisHarga,
                B.id_carabayar                       AS idCaraBayar,
                B.ppn                                AS ppn,
                A.id_pbf                             AS idPemasokOri,
                A.id_jenisanggaran                   AS idJenisAnggaranOri,
                A.id_sumberdana                      AS idSumberDanaOri,
                A.id_jenisharga                      AS idJenisHargaOri,
                A.id_carabayar                       AS idCaraBayarOri,
                A.ppn                                AS ppnOri,
                Co.nama_pbf                          AS namaPemasokOri,
                D.subjenis_anggaran                  AS subjenisAnggaran,
                Sd.sumber_dana                       AS sumberDana,
                F.jenis_harga                        AS jenisHarga,
                G.cara_bayar                         AS caraBayar,
                B.sts_revisi                         AS statusRevisiPl,
                B.ver_revisi                         AS verRevisiPl
            FROM db1.transaksif_perencanaan A
            LEFT JOIN db1.transaksif_pembelian B ON A.kode_reffpl = B.kode
            LEFT JOIN db1.masterf_pbf C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_pbf Co ON A.id_pbf = Co.id
            LEFT JOIN db1.masterf_subjenisanggaran D ON A.id_jenisanggaran = D.id
            LEFT JOIN db1.masterf_sumberdana Sd ON A.id_sumberdana = Sd.id
            LEFT JOIN db1.masterf_jenisharga F ON A.id_jenisharga = F.id
            LEFT JOIN db1.masterf_carabayar G ON A.id_carabayar = G.id
            LEFT JOIN (
                SELECT
                    A.kode                      AS kode,
                    A.no_doc                    AS no_doc,
                    IFNULL(SUM(B.anggaran), 0)  AS total_anggaran,
                    IFNULL(SUM(B.realisasi), 0) AS total_realisasi
                FROM db1.transaksif_pembelian A 
                LEFT JOIN ( 
                    SELECT
                        X.kode_reff                             AS kode_reffpl,
                        X.id_reffkatalog                        AS id_reffkatalog,
                        Y.jumlah_item                           AS jumlah_renc,
                        IFNULL(Z.jumlah_item, 0)                AS jumlah_terima,
                        X.harga_item                            AS harga_item,
                        Y.jumlah_item * X.harga_item            AS anggaran,
                        IFNULL(Z.jumlah_item, 0) * X.harga_item AS realisasi 
                    FROM db1.tdetailf_pembelian X 
                    LEFT JOIN db1.tdetailf_perencanaan Y ON X.kode_reffrenc = Y.kode_reff 
                    LEFT JOIN (
                        SELECT
                            Y.kode_reffpl                 AS kode_reffpl,
                            Y.id_katalog                  AS id_katalog,
                            IFNULL(SUM(Y.jumlah_item), 0) AS jumlah_item
                        FROM db1.tdetailf_penerimaan Y
                        GROUP BY Y.kode_reffpl, Y.id_katalog 
                    ) Z ON X.kode_reff = Z.kode_reffpl
                    WHERE
                        X.id_reffkatalog = Y.id_katalog
                        AND X.id_katalog = Z.id_katalog
                ) B ON A.kode = B.kode_reffpl 
                GROUP BY A.kode
            ) E ON A.kode_reffpl = E.kode
            WHERE
                A.kode = :kode
                AND A.sts_linked = 1
                AND A.sts_revisi = 1
                AND B.ver_revisi = 1
        ";
        $params = [":kode" => $kode];
        $perencanaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$perencanaan) throw new DataNotExistException($kode);

        $sql = "
            SELECT
                A.kode_reff                  AS kodeRef,
                A.id_katalog                 AS idKatalog,
                A.nama_generik               AS namaGenerik,
                A.kode_reffpl                AS kodeRefPl,
                A.kode_reffrenc              AS kodeRefRencana,
                A.id_reffkatalog             AS idRefKatalog,
                A.kemasan                    AS kemasan,
                A.id_pabrik                  AS idPabrik,
                A.id_kemasan                 AS idKemasan,
                A.isi_kemasan                AS isiKemasan,
                A.id_kemasandepo             AS idKemasanDepo,
                A.jumlah_item                AS jumlahItem,
                A.jumlah_kemasan             AS jumlahKemasan,
                A.harga_item                 AS hargaItem,
                A.harga_kemasan              AS hargaKemasan,
                A.diskon_item                AS diskonItem,
                A.diskon_harga               AS diskonHarga,
                A.keterangan                 AS keterangan,
                A.userid_updt                AS updatedById,
                A.sysdate_updt               AS updatedTime,
                K.nama_sediaan               AS namaSediaan,
                K.kemasan                    AS kemasanKat,
                K.id_kemasanbesar            AS idKemasanKat,
                K.id_kemasankecil            AS idKemasanDepoKat,
                K.isi_kemasan                AS isiKemasanKat,
                K.harga_beli                 AS hargaItemKat,
                K.harga_beli * K.isi_kemasan AS hargaKemasanKat,
                K.diskon_beli                AS diskonItemKat,
                masterf_pabrik.nama_pabrik   AS namaPabrik,
                Kaj.kode                     AS satuanJual,
                Kk.kode                      AS satuanKat,
                IFNULL(P.jumlah_item, 0)     AS jumlahPl,
                IFNULL(H.jumlah_item, 0)     AS jumlahHps,
                R.jumlah_item                AS jumlahRencana,
                IFNULL(S.jumlah_item, 0)     AS jumlahDo,
                A.jumlah_item                AS jumlahRo,
                0                            AS jumlahBonus,
                IFNULL(S.jumlah_trm, 0)      AS jumlahTerima,
                IFNULL(S.jumlah_ret, 0)      AS jumlahRetur,
                KM.kode                      AS satuanJualKat,
                Ka.kode                      AS satuan
            FROM db1.tdetailf_perencanaan A
            LEFT JOIN db1.masterf_katalog K ON K.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik ON masterf_pabrik.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan Kaj ON Kaj.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan Ka ON Ka.id = A.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan KM ON KM.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS Kk ON Kk.id = K.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS masterf_kemasankecil ON masterf_kemasankecil.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS P ON A.kode_reffpl = P.kode_reff
            LEFT JOIN db1.tdetailf_pengadaan AS H ON P.kode_reffhps = H.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan R ON P.kode_reffrenc = R.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_reffro                  AS kode_reffro,
                    A.id_katalog                   AS id_katalog,
                    SUM(A.jumlah_item)             AS jumlah_item,
                    IFNULL(SUM(T.jumlah_item), 0)  AS jumlah_trm,
                    IFNULL(SUM(Rt.jumlah_item), 0) AS jumlah_ret
                FROM db1.tdetailf_pemesanan A
                LEFT JOIN db1.transaksif_pemesanan B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        A.kode_reffpo      AS kode_reffpo,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan A
                    LEFT JOIN db1.transaksif_penerimaan B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpo, A.id_katalog
                ) T ON A.kode_reff = T.kode_reffpo AND A.id_katalog = T.id_katalog
                LEFT JOIN (
                    SELECT
                        A.kode_reffpo      AS kode_reffpo,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan A
                    LEFT JOIN db1.transaksif_penerimaan B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpo, A.id_katalog
                ) Rt ON A.kode_reff = Rt.kode_reffpo
                WHERE
                    B.sts_deleted = 0
                    AND A.id_katalog = Rt.id_katalog
                GROUP BY A.kode_reffro, A.id_katalog
            ) S ON A.kode_reff = S.kode_reffro
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = P.id_katalog
                AND A.id_katalog = S.id_katalog
                AND P.id_reffkatalog = H.id_katalog
                AND P.id_reffkatalog = R.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarDetailPembelian = [];
        $sql = "
            SELECT
                A.kode_reff      AS kodeRef,
                A.id_katalog     AS idKatalog,
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
                A.sts_iclosed    AS statusInputClosed,
                A.sysdate_icls   AS sysdateInputClosed,
                A.keterangan     AS keterangan,
                A.userid_updt    AS updatedById,
                A.sysdate_updt   AS updatedTime,
                Sj.kode          AS satuanJual,
                S.kode           AS satuan
            FROM db1.tdetailf_pembelian A 
            LEFT JOIN db1.masterf_kemasan Sj ON A.id_kemasan = Sj.id
            LEFT JOIN db1.masterf_kemasan S ON A.id_kemasandepo = S.id
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $perencanaan->kode_reffpl];
        $tempArr = $connection->createCommand($sql, $params)->queryAll();
        foreach($tempArr as $item) {
            $daftarDetailPembelian[$item->id_katalog] = $item;
        }

        // $this->set('heading_title', 'Revisi Repeate Order '.$perencanaan->no_doc);
        return json_encode(["data" => $perencanaan, "idata" => $daftarDetailPerencanaan, "pdata" => $daftarDetailPembelian]);
    }

    /**
     * @throws DateTimeException
     * @throws Exception
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#addrevisi    the original method
     */
    public function actionSaveRevisi(): Response
    {
        [   "kode" => $kode,
            "keterangan" => $keterangan,
            "ppn" => $ppn,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "ver_revisi" => $verRevisi,
            "revisike" => $revisiKe,
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_jenisharga" => $idJenisHarga,
            "id_carabayar" => $idCaraBayar,

            "id_katalog" => $daftarIdKatalog,
            "kemasan" => $daftarKemasan,
            "id_kemasan" => $daftarIdKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "isi_kemasan" => $daftarIsiKemasan,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "harga_kemasan" => $daftarHargaKemasan,
            "harga_item" => $daftarHargaItem,
            "diskon_item" => $daftarDiskonItem,
            "diskon_harga" => $daftarDiskonHarga,
        ] = $this->input->post();
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $idUser = Yii::$app->userFatma->id;
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        $fm = new FarmasiModel;

        $sql = "
            INSERT INTO db1.tdetailf_revperencanaan
            SELECT
                B.revisike,
                A.* 
            FROM db1.tdetailf_perencanaan A
            LEFT JOIN db1.transaksif_perencanaan B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Detail Revisi Perencanaan", $transaction);

        $sql = "
            INSERT INTO db1.transaksif_revperencanaan
            SELECT *
            FROM db1.transaksif_perencanaan
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Perencanaan", $transaction);

        $tgl = date('Y-m-d H:i:s');

        // saat revisi, diset close sementara, dan update revisi ke
        $sql = "
            UPDATE db1.transaksif_perencanaan 
            SET
                revisike = revisike + 1,
                keterangan = :keterangan,
                ver_revisi = 0,
                ver_usrrevisi = NULL,
                ver_tglrevisi = NULL,
                userid_updt = :updatedBy,
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi. Lakukan verifikasi revisi untuk bisa menggunakan Dokumen ini.'
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [
            ":kode" => $kode,
            ":keterangan" => $keterangan,
            ":updatedBy" => $this->user['id'],
            ":tanggalRevisi" => $tgl,
        ];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Perencanaan", $transaction);

        $sql = "
            UPDATE db1.transaksif_pemesanan 
            SET
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi Perencanaan Repeate Order. Cek Revisi Pemesanan dan Lakukan verifikasi revisi PO/DO untuk bisa menggunakan Dokumen ini kembali.'
            WHERE
                kode_reffro = :kodeRefRo
                AND sts_deleted = 0
        ";
        $params = [":tanggalRevisi" => $tgl, ":kodeRefRo" => $kode];
        $connection->createCommand($sql, $params)->execute();

        $sql = "
            UPDATE db1.transaksif_penerimaan 
            SET
                sts_revisi = 1,
                sysdate_rev = :tanggalRevisi,
                keterangan_rev = 'Sedang dilakukan Revisi Perencanaan Repeate Order. Cek Revisi PO/DO dan Penerimaan dan Lakukan verifikasi revisi Penerimaan untuk bisa menggunakan Dokumen ini kembali.'
            WHERE
                kode_reffro = :kodeRefRo
                AND sts_deleted = 0
        ";
        $params = [":tanggalRevisi" => $tgl, ":kodeRefRo" => $kode];
        $connection->createCommand($sql, $params)->execute();

        $sql = "
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
                A.userid_updt    AS updatedById,
                A.sysdate_updt   AS updatedTime,
                B.nama_sediaan   AS namaSediaan
            FROM db1.tdetailf_perencanaan A
            LEFT JOIN db1.masterf_katalog B ON A.id_katalog = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        $idata = [];
        foreach($daftarIdKatalog as $i => $idkatalog) {
            $idata[$idkatalog] = [
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

        $updt = false;
        $arrket = []; // untuk penanda jika pbf/mata anggaran
        if (count($idata) > 0) {
            foreach ($daftarDetailPerencanaan as $i => $d) {
                $idt = $idata[$d->id_katalog];
                $ket = [];
                $ifieldsAdd = [];

                //perubahan isi kemasan
                if ($idt['isi_kemasan'] != $d->isi_kemasan) {
                    $ket[] = "isi {$d->isi_kemasan} -> {$idt['isi_kemasan']}";
                    $ifieldsAdd = [
                        ...$ifieldsAdd,
                        "kemasan",
                        "id_kemasan",
                        "id_kemasandepo",
                        "isi_kemasan",
                        "jumlah_kemasan",
                        "jumlah_item",
                        "harga_kemasan",
                        "harga_item",
                        "diskon_harga",
                    ];
                }

                //perubahan harga
                if ($idt['harga_kemasan'] != $d->harga_kemasan || $idt['harga_item'] != $d->harga_item) {
                    $ket[] = "harga item {$d->harga_item} -> {$idt['harga_item']}";
                    $ket[] = "harga_kemasan {$d->harga_kemasan} -> {$idt['harga_kemasan']}";
                    $ifieldsAdd = [
                        ...$ifieldsAdd,
                        "harga_kemasan",
                        "harga_item",
                        "diskon_harga",
                    ];
                }
                //perubahan diskon
                if ($idt['diskon_item'] != $d->diskon_item) {
                    $ket[] = "diskon {$d->diskon_item} -> {$idt['diskon_item']}";
                    $ifieldsAdd = [
                        ...$ifieldsAdd,
                        "diskon_item",
                        "diskon_harga",
                    ];
                }

                if (count($ifieldsAdd) > 0) {
                    $updt = true;
                    $iwhere = ["kode_reff" => $kode, "id_katalog" => $d->id_katalog];
                    if ($fm->saveData("tdetailf_perencanaan", $ifieldsAdd, $idt, $iwhere)) {
                        $arrket[] = "{$d->nama_sediaan}: ". implode(" | ", $ket);
                    }
                }
            }
        }
        if (count($arrket) > 0) $keterangan .= " || Perubahan Nilai: ". implode("|", $arrket);

        $data = [
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

        // set Verifikasi Revisi
        $verifRef = '';
        if ($verRevisi == 1) {
            $data = [
                ...$data,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => null,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $idUser,
                "ver_tglrevisi" => $nowValSystem,
            ];

            if($updt) {
                $verifRef = "";
            } else {
                $verifRef = ", ver_execution = '1', ver_usrexecution = '$idUser', ver_tglexecution = '$nowValSystem' ";
            }
        }

        $fields = array_keys($data);

        // penyimpanan
        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_perencanaan", $fields, $data, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Notifikasi", $transaction);

        $sql = "
            UPDATE db1.transaksi_notification 
            SET
                description_reff = :deskripsi,
                verif_reff = 1,
                modul_exec = 'penerimaan',
                action_exec = 'addrevisi' 
                $verifRef
            WHERE
                tipe_notif = 'R'
                AND kode_reff = :kodeRef
                AND modul_reff = 'pembelian'
                AND info_reff = :info
        ";
        $params = [":kodeRef" => $kode, ":deskripsi" => $keterangan, ":info" => $revisiKe];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Notifikasi", $transaction);

        $transaction->commit();
        return $this->redirect($this->directory.strtolower($this->name).'/views/'.$kode.'?mf=113');
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#add    the original method
     */
    public function actionSaveAdd(): string
    {
        [   "action" => $action,
            "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
            "tipe_doc" => $tipeDokumen,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "ppn" => $ppn,
            "userid_updt" => $idUserUpdate,
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
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $dataPerencanaan = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tipe_doc" => $tipeDokumen,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
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
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
            "sts_saved" => 1,
        ];

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "R",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Perencanaan Farmasi Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUser
            ]);
            $kode = "R00" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);

            $dataPerencanaan = [
                ...$dataPerencanaan,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        $dataDetailPerencanaan = [];
        foreach ($daftarIdKatalog as $i => $idKatalog) {
            if (!$daftarJumlahKemasan[$i]) continue;
            $dataDetailPerencanaan[$idKatalog] = [
                "kode_reff" => $kode,
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
                "userid_updt" => $idUserUpdate,
            ];
        }

        $daftarField = array_keys($dataPerencanaan);
        if ($action == "add") {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_perencanaan
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $kode];
            $adaPerencanaan = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaPerencanaan) throw new DataAlreadyExistException("Perencanaan", "Kode", $kode, $transaction);

            $berhasilTambah = $fm->saveData("transaksif_perencanaan", $daftarField, $dataPerencanaan);
            if (!$berhasilTambah) throw new FailToInsertException("Perencanaan", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_perencanaan", $dataDetailPerencanaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Perencanaan", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_perencanaan", $daftarField, $dataPerencanaan, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Perencanaan", "Kode", $kode, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_perencanaan", $dataDetailPerencanaan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Perencanaan", "Kode Reff", $kode, $transaction);
        }
        $transaction->commit();

        return json_encode(["kode" => $kode]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode              AS kode,
                revisike          AS revisiKe,
                keterangan        AS keterangan,
                no_doc            AS noDokumen,
                tgl_doc           AS tanggalDokumen,
                tipe_doc          AS tipeDokumen,
                tgl_tempokirim    AS tanggalTempoKirim,
                kode_reffpl       AS kodeRefPl,
                id_pbf            AS idPemasok,
                id_jenisanggaran  AS idJenisAnggaran,
                id_sumberdana     AS idSumberDana,
                id_subsumberdana  AS idSubsumberDana,
                id_carabayar      AS idCaraBayar,
                id_jenisharga     AS idJenisHarga,
                thn_anggaran      AS tahunAnggaran,
                blnawal_anggaran  AS bulanAwalAnggaran,
                blnakhir_anggaran AS bulanAkhirAnggaran,
                ppn               AS ppn,
                nilai_total       AS nilaiTotal,
                nilai_diskon      AS nilaiDiskon,
                nilai_ppn         AS nilaiPpn,
                nilai_pembulatan  AS nilaiPembulatan,
                nilai_akhir       AS nilaiAkhir,
                sts_saved         AS statusSaved,
                sts_linked        AS statusLinked,
                sts_revisi        AS statusRevisi,
                sysdate_rev       AS sysdateRevisi,
                keterangan_rev    AS keteranganRevisi,
                sts_closed        AS statusClosed,
                sysdate_cls       AS sysdateClosed,
                sts_deleted       AS statusDeleted,
                sysdate_del       AS sysdateDeleted,
                ver_revisi        AS verRevisi,
                ver_usrrevisi     AS verUserRevisi,
                ver_tglrevisi     AS verTanggalRevisi,
                userid_in         AS useridInput,
                sysdate_in        AS sysdateInput,
                userid_updt       AS useridUpdate,
                sysdate_updt      AS sysdateUpdate,
                NULL              AS daftarDetail
            FROM db1.transaksif_perencanaan
            WHERE
                kode = :kode
                AND sts_linked = 0
                AND sts_closed = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $perencanaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$perencanaan) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                  AS kodeRef,
                A.id_katalog                 AS idKatalog,
                A.nama_generik               AS namaGenerik,
                A.kode_reffpl                AS kodeRefPl,
                A.kode_reffrenc              AS kodeRefRencana,
                A.id_reffkatalog             AS idRefKatalog,
                A.kemasan                    AS kemasan,
                A.id_pabrik                  AS idPabrik,
                A.id_kemasan                 AS idKemasan,
                A.isi_kemasan                AS isiKemasan,
                A.id_kemasandepo             AS idKemasanDepo,
                A.jumlah_item                AS jumlahItem,
                A.jumlah_kemasan             AS jumlahKemasan,
                A.harga_item                 AS hargaItem,
                A.harga_kemasan              AS hargaKemasan,
                A.diskon_item                AS diskonItem,
                A.diskon_harga               AS diskonHarga,
                A.keterangan                 AS keterangan,
                A.userid_updt                AS useridUpdate,
                A.sysdate_updt               AS sysdateUpdate,
                B.nama_sediaan               AS namaSediaan,
                B.kemasan                    AS kemasanKat,
                B.id_kemasanbesar            AS idKemasanKat,
                B.id_kemasankecil            AS idKemasanDepoKat,
                B.harga_beli                 AS hargaItemKat,
                B.harga_beli * B.isi_kemasan AS hargaKemasanKat,
                B.isi_kemasan                AS isiKemasanKat,
                PBK.nama_pabrik              AS namaPabrik,
                B.id_pabrik                  AS idPabrikKat,
                E.nama_pabrik                AS namaPabrikKat,
                C.kode                       AS satuanJualKat,
                D.kode                       AS satuanKat,
                KSAR.kode                    AS satuanJual,
                KCIL.kode                    AS satuan  -- satuan kecil
            FROM db1.tdetailf_perencanaan AS A
            LEFT JOIN db1.masterf_katalog AS B ON B.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_pabrik AS  E ON E.id = B.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS C ON C.id = B.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS D ON D.id = B.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = A.id_kemasandepo
            WHERE A.kode_reff = :kode
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $perencanaan->daftarDetail = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($perencanaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#addmonthly    the original method
     */
    public function actionSaveBulanan(): void
    {
        [   "action" => $action,
            "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "id_pbf" => $idPemasok,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "ppn" => $ppn,
            "userid_updt" => $idUserUpdate,
            "ver_revisi" => $verRevisi,
            "kode_reffpl" => $kodeRefPl, // ??? array?
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
            "kode_reffrenc" => $daftarKodeRefRencana,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $dataPerencanaan = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tipe_doc" => 3,
            "kode_reffpl" => $kodeRefPl,
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

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        if ($verRevisi == 1) {
            $dataPerencanaan = [
                ...$dataPerencanaan,
                "ver_revisi" => 1,
                "ver_usrrevisi" => $idUser,
                "ver_tglrevisi" => $nowValSystem,
                "sts_revisi" => 0,
                "sysdate_rev" => null,
                "keterangan_rev" => "Revisi sudah disesuaikan",
            ];
        }

        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "R",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Perencanaan Farmasi Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUserUpdate
            ]);
            $kode = "R00" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);

            $dataPerencanaan = [
                ...$dataPerencanaan,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        $dataDetailPerencanaan = [];
        foreach ($daftarIdKatalog as $i => $idKatalog) {
            if (!$daftarJumlahKemasan[$i]) continue;
            $dataDetailPerencanaan[$idKatalog] = [
                "kode_reff" => $kode,
                "kode_reffpl" => $kodeRefPl[$i], // ??? array?
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
                "userid_updt" => $idUserUpdate,
            ];
        }

        $daftarField = array_keys($dataPerencanaan);
        if ($action == "add") {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_perencanaan
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $kode];
            $adaPerencanaan = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaPerencanaan) throw new DataAlreadyExistException("Perencanaan", "Kode", $kode, $transaction);

            $berhasilTambah = $fm->saveData("transaksif_perencanaan", $daftarField, $dataPerencanaan);
            if (!$berhasilTambah) throw new FailToInsertException("Perencanaan", $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_pembelian
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefPl[0]]; // ??? $kodeRefPl[0] array?
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kodeRefPl[0], $transaction); // ??? $kodeRefPl[0] array?

            $berhasilTambah = $fm->saveBatch("tdetailf_perencanaan", $dataDetailPerencanaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Perencanaan", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_perencanaan", $daftarField, $dataPerencanaan, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Perencanaan", "Kode", $kode, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_perencanaan", $dataDetailPerencanaan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Perencanaan", "Kode Ref", $kode, $transaction);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#editmonthly    the original method
     */
    public function actionEditMonthlyData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                               AS kode,
                A.revisike                           AS revisiKe,
                A.keterangan                         AS keterangan,
                A.no_doc                             AS noDokumen,
                A.tgl_doc                            AS tanggalDokumen,
                A.tipe_doc                           AS tipeDokumen,
                A.tgl_tempokirim                     AS tanggalTempoKirim,
                A.kode_reffpl                        AS kodeRefPl,
                A.id_pbf                             AS idPemasok,
                A.id_jenisanggaran                   AS idJenisAnggaran,
                A.id_sumberdana                      AS idSumberDana,
                A.id_subsumberdana                   AS idSubsumberDana,
                A.id_carabayar                       AS idCaraBayar,
                A.id_jenisharga                      AS idJenisHarga,
                A.thn_anggaran                       AS tanggalAnggaran,
                A.blnawal_anggaran                   AS bulanAwalAnggaran,
                A.blnakhir_anggaran                  AS bulanAkhirAnggaran,
                A.ppn                                AS ppn,
                A.nilai_total                        AS nilaiTotal,
                A.nilai_diskon                       AS nilaiDiskon,
                A.nilai_ppn                          AS nilaiPpn,
                A.nilai_pembulatan                   AS nilaiPembulatan,
                A.nilai_akhir                        AS nilaiAkhir,
                A.sts_saved                          AS statusSaved,
                A.sts_linked                         AS statusLinked,
                A.sts_revisi                         AS statusRevisi,         -- in use
                A.sysdate_rev                        AS sysdateRevisi,
                A.keterangan_rev                     AS keteranganRevisi,
                A.sts_closed                         AS statusClosed,
                A.sysdate_cls                        AS sysdateClosed,
                A.sts_deleted                        AS statusDeleted,
                A.sysdate_del                        AS sysdateDeleted,
                A.ver_revisi                         AS verRevisi,
                A.ver_usrrevisi                      AS verUserRevisi,
                A.ver_tglrevisi                      AS verTanggalRevisi,
                A.userid_in                          AS useridInput,
                A.sysdate_in                         AS sysdateInput,
                A.userid_updt                        AS useridUpdate,
                A.sysdate_updt                       AS sysdateUpdate,
                B.no_doc                             AS noSpk,
                B.tgl_doc                            AS tanggalMulai,
                B.tgl_jatuhtempo                     AS tanggalJatuhTempo,
                C.nama_pbf                           AS namaPemasok,
                B.blnakhir_anggaran                  AS bulanAkhirAnggaranPl,
                B.blnawal_anggaran                   AS bulanAwalAnggaranPl,
                B.thn_anggaran                       AS tahunAnggaranPl,
                D.subjenis_anggaran                  AS subjenisAnggaran,
                B.tipe_doc                           AS tipeSpk,
                B.sts_closed                         AS statusPl,
                E.total_anggaran - E.total_realisasi AS sisaAnggaran,
                B.nilai_akhir                        AS nilaiAkhirPl
            FROM db1.transaksif_perencanaan AS A
            LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reffpl = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_subjenisanggaran AS D ON B.id_jenisanggaran = D.id
            LEFT JOIN (
                SELECT
                    A.kode                      AS kode,
                    A.no_doc                    AS no_doc,
                    IFNULL(SUM(B.anggaran), 0)  AS total_anggaran,
                    IFNULL(SUM(B.realisasi), 0) AS total_realisasi
                FROM db1.transaksif_pembelian AS A
                LEFT JOIN (
                    SELECT
                        X.kode_reff                             AS kode_reffpl,
                        X.id_reffkatalog                        AS id_reffkatalog,
                        Y.jumlah_item                           AS jumlah_renc,
                        IFNULL(Z.jumlah_item, 0)                AS jumlah_terima,
                        X.harga_item                            AS harga_item,
                        Y.jumlah_item * X.harga_item            AS anggaran,
                        IFNULL(Z.jumlah_item, 0) * X.harga_item AS realisasi
                    FROM db1.tdetailf_pembelian AS X
                    LEFT JOIN db1.tdetailf_perencanaan AS Y ON X.kode_reffrenc = Y.kode_reff
                    LEFT JOIN (
                        SELECT
                            Y.kode_reffpl                 AS kode_reffpl,
                            Y.id_katalog                  AS id_katalog,
                            IFNULL(SUM(Y.jumlah_item), 0) AS jumlah_item
                        FROM db1.tdetailf_penerimaan AS Y
                        GROUP BY Y.kode_reffpl, Y.id_katalog
                    ) AS Z ON X.kode_reff = Z.kode_reffpl
                    WHERE
                        X.id_katalog = Z.id_katalog
                        AND X.id_reffkatalog = Y.id_katalog
                ) AS B ON A.kode = B.kode_reffpl
                GROUP BY A.kode
            ) AS E ON A.kode_reffpl = E.kode
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
                AND A.sts_closed = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $perencanaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$perencanaan) throw new DataNotExistException($kode);

        if ($perencanaan->statusRevisi == 1) {
            throw new \Exception("Ada Revisi Pada PL. Silahkan periksa perubahan terhadap SP/SPK/Kontrak tersebut, dan lakukan verifikasi Revisi");
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                  AS kodeRef,
                A.id_katalog                 AS idKatalog,
                A.nama_generik               AS namaGenerik,
                A.kode_reffpl                AS kodeRefPl,
                A.kode_reffrenc              AS kodeRefRencana,
                A.id_reffkatalog             AS idRefKatalog,
                A.kemasan                    AS kemasan,
                A.id_pabrik                  AS idPabrik,
                A.id_kemasan                 AS idKemasan,
                A.isi_kemasan                AS isiKemasan,
                A.id_kemasandepo             AS idKemasanDepo,
                A.jumlah_item                AS jumlahItem,
                A.jumlah_kemasan             AS jumlahKemasan,
                A.harga_item                 AS hargaItem,
                A.harga_kemasan              AS hargaKemasan,
                A.diskon_item                AS diskonItem,
                A.diskon_harga               AS diskonHarga,
                A.keterangan                 AS keterangan,
                A.userid_updt                AS useridUpdate,
                A.sysdate_updt               AS sysdateUpdate,
                K.nama_sediaan               AS namaSediaan,
                K.kemasan                    AS kemasanKat,
                K.id_kemasanbesar            AS idKemasanKat,
                K.id_kemasankecil            AS idKemasanDepoKat,
                K.isi_kemasan                AS isiKemasanKat,
                K.harga_beli                 AS hargaItemKat,
                K.harga_beli * K.isi_kemasan AS hargaKemasanKat,
                K.diskon_beli                AS diskonItemKat,
                PBK.nama_pabrik              AS namaPabrik,
                Kaj.kode                     AS satuanJual,
                Kk.kode                      AS satuanKat,
                IFNULL(P.jumlah_item, 0)     AS jumlahPl,
                IFNULL(R.jumlah_item, 0)     AS jumlahSpb,
                IFNULL(T.jumlah_item, 0)     AS jumlahTerima,
                Q.jumlah_item                AS jumlahRencana,
                KM.kode                      AS satuanJualKat,
                Ka.kode                      AS satuan,
                0                            AS jumlahRetur,
                0                            AS jumlahRencana
            FROM db1.tdetailf_perencanaan AS A
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS Kaj ON Kaj.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS Ka ON Ka.id = A.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS KM ON KM.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS Kk ON Kk.id = K.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS masterf_kemasankecil ON masterf_kemasankecil.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS P ON A.kode_reffpl = P.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS Q ON A.kode_reffrenc = Q.kode_reff
            LEFT JOIN (
                SELECT
                    X.kode_reffpl      AS kode_reffpl,
                    X.id_katalog       AS id_katalog,
                    SUM(X.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_perencanaan AS X
                LEFT JOIN db1.transaksif_perencanaan AS P ON X.kode_reff = P.kode
                WHERE
                    P.sts_deleted = 0
                    AND X.kode_reff != :kode
                GROUP BY X.kode_reffpl, X.id_katalog
            ) AS R ON A.kode_reffpl = R.kode_reffpl
            LEFT JOIN (
                SELECT
                    X.kode_reffpl      AS kode_reffpl,
                    X.id_katalog       AS id_katalog,
                    SUM(X.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS X
                LEFT JOIN db1.transaksif_penerimaan AS P ON X.kode_reff = P.kode
                WHERE P.sts_deleted = 0
                GROUP BY X.kode_reffpl, X.id_katalog
            ) AS T ON A.kode_reffpl = T.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = T.id_katalog
                AND A.id_katalog = R.id_katalog
                AND A.id_reffkatalog = Q.id_katalog
                AND A.id_katalog = P.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode(["data" => $perencanaan, "idata" => $daftarDetailPerencanaan]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#views    the original method
     */
    public function actionViewRevisiData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode              AS kode,
                A.no_doc            AS noDokumen,
                A.tgl_doc           AS tanggalDokumen,
                A.id_jenisanggaran  AS idJenisAnggaran,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.thn_anggaran      AS tahunAnggaran,
                A.id_sumberdana     AS idSumberDana,
                A.id_subsumberdana  AS idSubsumberDana,
                A.id_jenisharga     AS idJenisHarga,
                A.id_carabayar      AS idCaraBayar,
                A.id_pbf            AS idPemasok,
                A.ppn               AS ppn,
                A.sts_revisi        AS statusRevisi,
                B.subjenis_anggaran AS subjenisAnggaran,
                C.sumber_dana       AS sumberDana,
                D.subsumber_dana    AS subsumberDana,
                E.jenis_harga       AS jenisHarga,
                F.cara_bayar        AS caraBayar,
                H.nama_pbf          AS namaPemasok,
                P.id_jenisanggaran  AS idJenisAnggaranPl,
                P.blnawal_anggaran  AS bulanAwalAnggaranPl,
                P.blnakhir_anggaran AS bulanAkhirAnggaranPl,
                P.thn_anggaran      AS tahunAnggaranPl,
                P.id_sumberdana     AS idSumberDanaPl,
                P.id_subsumberdana  AS idSubsumberDanaPl,
                P.id_jenisharga     AS idJenisHargaPl,
                P.id_carabayar      AS idCaraBayarPl,
                P.id_pbf            AS idPemasokPl,
                P.ppn               AS ppnPl,
                P.keterangan        AS keterangan,
                P.no_doc            AS noSpk,
                null                AS detailPerencanaan
            FROM db1.transaksif_perencanaan AS A
            LEFT JOIN db1.transaksif_pembelian AS P ON A.kode_reffpl = P.kode
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.transaksif_pembelian AS G ON A.kode_reffpl = G.kode
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            WHERE
                A.kode = :kode
                AND A.sts_revisi = 1
                AND P.ver_revisi = 1
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $perencanaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$perencanaan) throw new DataNotExistException($kode);

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
                B.nama_sediaan   AS namaSediaan,
                C.nama_pabrik    AS namaPabrik,
                D.kode           AS satuanJual,
                E.kode           AS satuan,
                P.id_pabrik      AS idPabrikPl,
                P.kemasan        AS kemasanPl,
                P.id_kemasan     AS idKemasanPl,
                P.isi_kemasan    AS isiKemasanPl,
                P.id_kemasandepo AS idKemasanDepoPl,
                P.harga_item     AS hargaItemPl,
                P.harga_kemasan  AS hargaKemasanPl,
                P.diskon_item    AS diskonItemPl
            FROM db1.tdetailf_perencanaan AS A
            LEFT JOIN db1.masterf_katalog AS B ON B.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS C ON C.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS D ON D.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS E ON E.id = B.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS P ON P.kode_reff = A.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND P.id_katalog = A.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $perencanaan->detailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($perencanaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#views    the original method
     */
    public function actionViewData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kodePerencanaan,
                A.no_doc               AS noDokumenPerencanaan,
                A.tgl_doc              AS tanggalDokumenPerencanaan,
                A.tipe_doc             AS tipeDokumen,
                A.sts_linked           AS statusLinked,
                A.sts_closed           AS statusClosed,
                A.blnawal_anggaran     AS bulanAwalAnggaran,
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                A.thn_anggaran         AS tahunAnggaran,
                A.ppn                  AS ppn,
                A.sts_revisi           AS statusRevisi,
                B.subjenis_anggaran    AS subjenisAnggaran,
                C.sumber_dana          AS sumberDana,
                D.subsumber_dana       AS subsumberDana,
                E.jenis_harga          AS jenisHarga,
                F.cara_bayar           AS caraBayar,
                IFNULL(G.no_doc, '-')  AS noSpk,
                IFNULL(H.nama_pbf, '') AS namaPemasok,
                A.nilai_pembulatan     AS pembulatan,
                NULL                   AS daftarDetailPerencanaan
            FROM db1.transaksif_perencanaan AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.transaksif_pembelian AS G ON A.kode_reffpl = G.kode
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $perencanaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$perencanaan) throw new DataNotExistException($kode);

        if ($perencanaan->tipeDokumen == 3) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use.
                    A.kode_reff              AS kodeRef,
                    A.id_katalog             AS idKatalog,
                    A.kode_reffpl            AS kodeRefPl,
                    A.kode_reffrenc          AS kodeRefRencana,
                    A.kemasan                AS kemasan,
                    A.isi_kemasan            AS isiKemasan,
                    A.jumlah_kemasan         AS jumlahKemasan,
                    A.harga_item             AS hargaItem,
                    A.harga_kemasan          AS hargaKemasan,
                    A.diskon_item            AS diskonItem,
                    A.diskon_harga           AS diskonHarga,
                    KAT.nama_sediaan         AS namaSediaan,
                    PBK.nama_pabrik          AS namaPabrik,
                    IFNULL(P.jumlah_item, 0) AS jumlahPl,
                    IFNULL(R.jumlah_item, 0) AS jumlahPo,
                    IFNULL(T.jumlah_item, 0) AS jumlahTerima,
                    Q.jumlah_item            AS jumlahRencana,
                    tH.jumlah_item           AS jumlahHps,
                    P.kode_reffhps           AS kodeRefHps
                FROM db1.tdetailf_perencanaan AS A
                LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
                LEFT JOIN db1.tdetailf_pembelian AS P ON A.kode_reffpl = P.kode_reff
                LEFT JOIN db1.tdetailf_pengadaan AS tH ON P.kode_reffhps = tH.kode_reff
                LEFT JOIN db1.tdetailf_perencanaan AS Q ON A.kode_reffrenc = Q.kode_reff
                LEFT JOIN (
                    SELECT
                        X.kode_reffpl      AS kode_reffpl,
                        X.id_katalog       AS id_katalog,
                        SUM(X.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_perencanaan AS X
                    LEFT JOIN db1.transaksif_perencanaan AS P ON X.kode_reff = P.kode
                    WHERE P.sts_deleted = 0
                    GROUP BY X.kode_reffpl, X.id_katalog
                ) AS R ON A.kode_reffpl = R.kode_reffpl
                LEFT JOIN (
                    SELECT
                        X.kode_reffpl      AS kode_reffpl,
                        X.id_katalog       AS id_katalog,
                        SUM(X.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS X
                    LEFT JOIN db1.transaksif_penerimaan AS P ON X.kode_reff = P.kode
                    WHERE P.sts_deleted = 0
                    GROUP BY X.kode_reffpl, X.id_katalog
                ) AS T ON A.kode_reffpl = T.kode_reffpl
                WHERE
                    A.kode_reff = :kode
                    AND A.id_katalog = T.id_katalog
                    AND A.id_katalog = R.id_katalog
                    AND A.id_reffkatalog = Q.id_katalog
                    AND P.id_reffkatalog = tH.id_reffkatalog
                    AND A.id_katalog = P.id_katalog
                ORDER BY nama_sediaan
            ";
            $params = [":kode" => $kode];

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use.
                    A.kode_reff                AS kodeRef,
                    A.id_katalog               AS idKatalog,
                    A.kode_reffpl              AS kodeRefPl,
                    A.kode_reffrenc            AS kodeRefRencana,
                    A.kemasan                  AS kemasan,
                    A.isi_kemasan              AS isiKemasan,
                    A.jumlah_kemasan           AS jumlahKemasan,
                    A.harga_item               AS hargaItem,
                    A.harga_kemasan            AS hargaKemasan,
                    A.diskon_item              AS diskonItem,
                    A.diskon_harga             AS diskonHarga,
                    KAT.nama_sediaan           AS namaSediaan,
                    PBK.nama_pabrik            AS namaPabrik,
                    IFNULL(pl.jumlah_item, 0)  AS jumlahPl,
                    IFNULL(po.jumlah_item, 0)  AS jumlahPo,
                    IFNULL(trm.jumlah_item, 0) AS jumlahTerima,
                    A.jumlah_item              AS jumlahRencana,
                    IFNULL(tH.jumlah_item, 0)  AS jumlahHps,
                    P.kode_reffhps             AS kodeRefHps
                FROM db1.tdetailf_perencanaan AS A
                LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
                LEFT JOIN db1.tdetailf_pembelian AS P ON A.kode_reffpl = P.kode_reff
                LEFT JOIN (
                    SELECT
                        A.kode_reffrenc    AS kode_reffrenc,
                        A.id_reffkatalog   AS id_reffkatalog,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_pengadaan AS A
                    LEFT JOIN db1.transaksif_pengadaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffrenc = :kode
                    GROUP BY A.kode_reffrenc, A.id_reffkatalog
                ) AS tH ON A.kode_reff = tH.kode_reffrenc
                LEFT JOIN (
                    SELECT
                        A.kode_reff        AS kode_reffpl,
                        A.kode_reffrenc    AS kode_reffrenc,
                        A.id_reffkatalog   AS id_reffkatalog,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_pembelian AS A
                    LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffrenc = :kode
                    GROUP BY A.kode_reffrenc, A.id_reffkatalog
                ) AS pl ON A.kode_reff = pl.kode_reffrenc
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.kode_reffrenc    AS kode_reffrenc,
                        A.id_reffkatalog   AS id_reffkatalog,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_pemesanan AS A
                    LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffrenc = :kode
                    GROUP BY A.kode_reffrenc, A.id_reffkatalog
                ) AS po ON A.kode_reff = po.kode_reffrenc
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.kode_reffrenc    AS kode_reffrenc,
                        A.id_reffkatalog   AS id_reffkatalog,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffrenc = :kode
                    GROUP BY A.kode_reffrenc, A.id_katalog
                ) AS trm ON A.kode_reff = trm.kode_reffrenc
                WHERE
                    A.kode_reff = :kode
                    AND A.id_katalog = trm.id_reffkatalog
                    AND A.id_katalog = po.id_reffkatalog
                    AND A.id_katalog = pl.id_reffkatalog
                    AND A.id_katalog = tH.id_reffkatalog
                    AND A.id_katalog = P.id_katalog
                ORDER BY nama_sediaan
            ";
            $params = [":kode" => $kode];
        }
        $perencanaan->daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($perencanaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws LogicBranchException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#prints    the original method
     */
    public function actionPrint(): string
    {
        ["kode" => $kodeRef, "versi" => $versi] = Yii::$app->request->post();
        $c = new JasperClient("http://192.168.3.34:8080/jasperserver", "jasperadmin", "jasperadmin");

        $parameter = ["kode_reff" => $kodeRef];
        switch ($versi) {
            case "v-01": $laporan = "/reports/Farmasi/lap_revisi_perencanaan_default"; break;
            case "v-02": $laporan = "/reports/Farmasi/lap_revisi_perencanaan_v1"; break;
            case "v-03": $laporan = "/reports/Farmasi/lap_revisi_perencanaan_v2"; break;
            case "v-04": $laporan = "/reports/Farmasi/lap_revisi_perencanaan_v3"; break;
            default: throw new LogicBranchException();
        }

        $report = $c->reportService()->runReport($laporan, "pdf", null, null, $parameter);

        return $this->renderPdf($report, "report");
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#reports    the original method
     */
    public function actionSearchNoDocData(): string
    {
        [   "bulanAwalAnggaran" => $bulanAwal,
            "bulanAkhirAnggaran" => $bulanAkhir,
            "tahunAnggaran" => $tahun,
            "idKatalog" => $idKatalog,
        ] = Yii::$app->request->post();
        if (!$idKatalog) throw new \Exception("Harus ada");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode_reff         AS kodeRef,
                B.no_doc            AS noDokumenPerencanaan,
                B.tgl_doc           AS tanggalDokumen,
                D.no_doc            AS noPl,
                E.nama_pbf          AS namaPemasok,
                F.subjenis_anggaran AS subjenisAnggaran,
                B.blnawal_anggaran  AS bulanAwalAnggaran,
                B.blnakhir_anggaran AS bulanAkhirAnggaran,
                B.thn_anggaran      AS tahunAnggaran,
                B.nilai_akhir       AS nilaiAkhir
            FROM db1.tdetailf_perencanaan AS A
            INNER JOIN db1.transaksif_perencanaan AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
            LEFT JOIN db1.transaksif_pembelian AS D ON B.kode_reffpl = D.kode
            LEFT JOIN db1.masterf_pbf AS E ON D.id_pbf = E.id
            LEFT JOIN db1.masterf_subjenisanggaran AS F ON B.id_jenisanggaran = F.id
            WHERE
                A.id_katalog = :idKatalog
                AND (:bulanAwalAnggaran = '' OR B.blnawal_anggaran = :bulanAwalAnggaran)
                AND (:bulanAkhirAnggaran = '' OR B.blnakhir_anggaran = :bulanAkhirAnggaran)
                AND (:tahunAnggaran = '' OR B.thn_anggaran = :tahunAnggaran)
                AND B.sts_deleted = 0
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":bulanAwalAnggaran" => $bulanAwal,
            ":bulanAkhirAnggaran" => $bulanAkhir,
            ":tahunAnggaran" => $tahun,
        ];
        $daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarDetailPerencanaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws LogicBranchException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#reports    the original method
     */
    public function actionReportsData(): string
    {
        $connection = Yii::$app->dbFatma;
        [   "bulanAwalAnggaran" => $bulanAwal,
            "bulanAkhirAnggaran" => $bulanAkhir,
            "tahunAnggaran" => $tahun,
            "statusKontrak" => $statusKontrak,
            "format" => $format,
        ] = Yii::$app->request->post();

        assert($bulanAwal && $bulanAkhir && $tahun, new MissingPostParamException("bulanAwalAnggaran", "bulanAkhirAnggaran", "tahunAnggaran"));
        if ($format != "rekap") throw new LogicBranchException;

        if ($statusKontrak == 1) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use.
                    B.id_jenisanggaran       AS idJenisAnggaran,
                    E.subjenis_anggaran      AS subjenisAnggaran,
                    A.id_katalog             AS idKatalog,
                    C.nama_sediaan           AS namaSediaan,
                    D.nama_pabrik            AS namaPabrik,
                    SUM(A.jumlah_item)       AS jumlahItem,
                    AVG(A.harga_item)        AS hargaItem,
                    IFNULL(R.jumlah_item, 0) AS jumlahItemR,
                    IFNULL(R.harga_item, 0)  AS hargaItemR
                FROM db1.tdetailf_perencanaan AS A
                INNER JOIN db1.transaksif_perencanaan AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        dP.kode_reffro     AS kode_reffro,
                        dP.id_katalog      AS id_katalog,
                        SUM(T.jumlah_item) AS jumlah_item,
                        AVG(dP.harga_item) AS harga_item
                    FROM db1.tdetailf_pemesanan AS dP
                    INNER JOIN db1.transaksif_pemesanan AS P ON dP.kode_reff = P.kode
                    LEFT JOIN (
                        SELECT
                            dT.kode_reffpo      AS kode_reffpo,
                            dT.id_katalog       AS id_katalog,
                            SUM(dT.jumlah_item) AS jumlah_item
                        FROM db1.tdetailf_penerimaan AS dT
                        INNER JOIN db1.transaksif_penerimaan AS T ON dT.kode_reff = T.kode
                        WHERE
                            T.sts_testing = 0
                            AND T.sts_deleted = 0
                            AND T.ver_gudang = 1
                        GROUP BY
                            dT.kode_reffpo,
                            dT.id_katalog
                    ) AS T ON dP.kode_reff = T.kode_reffpo
                    WHERE
                        P.sts_deleted = 0
                        AND dP.id_katalog = T.id_katalog
                    GROUP BY
                        dP.kode_reffro,
                        dP.id_katalog
                ) AS R ON A.kode_reff = R.kode_reffro
                LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
                LEFT JOIN db1.masterf_pabrik AS D ON A.id_pabrik = D.id
                LEFT JOIN db1.masterf_subjenisanggaran AS E ON B.id_jenisanggaran = E.id
                WHERE
                    B.sts_deleted = 0
                    AND B.thn_anggaran = :tahunAnggaran
                    AND B.blnawal_anggaran >= :bulanAwalAnggaran
                    AND B.blnakhir_anggaran <= :bulanAkhirAnggaran
                    AND A.id_katalog = R.id_katalog
                GROUP BY A.id_katalog
            ";
            $params = [":tahunAnggaran" => $tahun, ":bulanAwalAnggaran" => $bulanAwal, ":bulanAkhirAnggaran" => $bulanAkhir];

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use.
                    B.id_jenisanggaran       AS idJenisAnggaran,
                    E.subjenis_anggaran      AS subjenisAnggaran,
                    A.id_katalog             AS idKatalog,
                    C.nama_sediaan           AS namaSediaan,
                    D.nama_pabrik            AS namaPabrik,
                    SUM(A.jumlah_item)       AS jumlahItem,
                    AVG(A.harga_item)        AS hargaItem,
                    IFNULL(R.jumlah_item, 0) AS jumlahItemR,
                    IFNULL(R.harga_item, 0)  AS hargaItemR
                FROM db1.tdetailf_perencanaan AS A
                INNER JOIN db1.transaksif_perencanaan AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        dP.kode_reffrenc   AS kode_reffrenc,
                        dP.id_reffkatalog  AS id_reffkatalog,
                        SUM(T.jumlah_item) AS jumlah_item,
                        AVG(dP.harga_item) AS harga_item
                    FROM db1.tdetailf_pembelian AS dP
                    INNER JOIN db1.transaksif_pembelian AS P ON dP.kode_reff = P.kode
                    LEFT JOIN (
                        SELECT
                            dT.kode_reffpl      AS kode_reffpl,
                            dT.id_katalog       AS id_katalog,
                            SUM(dT.jumlah_item) AS jumlah_item
                        FROM db1.tdetailf_penerimaan AS dT
                        INNER JOIN db1.transaksif_penerimaan AS T ON dT.kode_reff = T.kode
                        WHERE
                            T.sts_testing = 0
                            AND T.sts_deleted = 0
                            AND T.ver_gudang = 1
                        GROUP BY
                            dT.kode_reffpl,
                            dT.id_katalog
                    ) AS T ON dP.kode_reff = T.kode_reffpl
                    WHERE
                        P.sts_deleted = 0
                        AND dP.id_katalog = T.id_katalog
                    GROUP BY
                        dP.kode_reffrenc,
                        dP.id_reffkatalog
                ) AS R ON A.kode_reff = R.kode_reffrenc
                LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
                LEFT JOIN db1.masterf_pabrik AS D ON A.id_pabrik = D.id
                LEFT JOIN db1.masterf_subjenisanggaran AS E ON B.id_jenisanggaran = E.id
                WHERE
                    B.sts_deleted = 0
                    AND B.thn_anggaran = :tahun
                    AND B.blnawal_anggaran >= :bulanAwal
                    AND B.blnakhir_anggaran <= :bulanAkhir
                    AND A.id_katalog = R.id_reffkatalog
                GROUP BY A.id_katalog
            ";
            $params = [":tahun" => $tahun, ":bulanAwal" => $bulanAwal, ":bulanAkhir" => $bulanAkhir];
        }
        $daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 44;
        $idJenisSaatIni = "";

        foreach ($daftarDetailPerencanaan as $i => $dPerencanaan) {
            $idJenis = $dPerencanaan->idJenisAnggaran;

            if ($idJenisSaatIni != $idJenis) {
                $idJenisSaatIni = $idJenis;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = (object) [
                    "no" => $noJudul++ .".",
                    "idJenisAnggaran" => $dPerencanaan->idJenisAnggaran,
                    "jenisObat" => $dPerencanaan->subjenisAnggaran,
                    "totalRencana" => 0,
                    "totalRealisasi" => 0,
                    "totalSelisih" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $subtotalRencana = $dPerencanaan->jumlahItem * $dPerencanaan->hargaItem;
            $subtotalRealisasi = $dPerencanaan->jumlahItemR * $dPerencanaan->hargaItemR;
            $subtotalSelisih = $subtotalRencana - $subtotalRealisasi;

            $daftarHalaman[$h][$b] = (object) [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
                "subtotalRencana" => $subtotalRencana,
                "subtotalRealisasi" => $subtotalRealisasi,
                "subtotalSelisih" => $subtotalSelisih,
            ];

            $daftarHalaman[$hJudul][$bJudul]["totalRencana"] += $subtotalRencana;
            $daftarHalaman[$hJudul][$bJudul]["totalRealisasi"] += $subtotalRealisasi;
            $daftarHalaman[$hJudul][$bJudul]["totalSelisih"] += $subtotalSelisih;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        $periodeBulan = ($bulanAwal == $bulanAkhir)
            ? $numToMonthName($bulanAwal)
            : $numToMonthName($bulanAwal) . "-" . $numToMonthName($bulanAkhir);

        $view = new Rekapitulasi(
            daftarHalaman:           $daftarHalaman,
            tahunAnggaran:           $tahun,
            periodeBulan:            $periodeBulan,
            daftarDetailPerencanaan: $daftarDetailPerencanaan,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#checkStock    the original method
     */
    public function actionCheckStock(): string
    {
        assert($_POST["id_katalog"] && $_POST["id_depo"], new MissingPostParamException("id_katalog", "id_depo"));
        ["id_katalog" => $idKatalog, "id_depo" => $idDepo] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                SKT.jumlah_stokadm AS jumlahStokAdm,
                DPO.namaDepo       AS namaDepo
            FROM db1.transaksif_stokkatalog AS SKT
            LEFT JOIN db1.masterf_depo AS DPO ON SKT.id_depo = DPO.id
            WHERE
                SKT.id_katalog = :idKatalog
                AND (:idDepo = '' OR SKT.id_depo = :idDepo)
            ORDER BY DPO.namaDepo ASC
        ";
        $params = [":idKatalog" => $idKatalog, ":idDepo" => $idDepo];
        $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarStokKatalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-87511bb
     */
    public function actionSearchJsonDetailDo(): string
    {
        ["kode_reff" => $kodeReff] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                  AS kodeRef,
                A.kode_reffrenc              AS kodeRefRencana,
                A.kode_reffpl                AS kodeRefPl,
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
                B.id_kemasankecil            AS idKemasanDepoKat,
                B.kemasan                    AS kemasanKat,
                B.isi_kemasan                AS isiKemasanKat,
                B.harga_beli                 AS hargaItemKat,
                B.harga_beli * B.diskon_beli AS hargaKemasanKat,
                B.diskon_beli                AS diskonItemKat,
                C.nama_pabrik                AS namaPabrik,
                D.kode                       AS satuanJual,
                Ds.kode                      AS satuan,
                E.kode                       AS satuanJualKat,
                Es.kode                      AS satuanKat,
                IFNULL(F.jumlah_item, 0)     AS jumlahPl,
                IFNULL(G.jumlah_item, 0)     AS jumlahDo,
                IFNULL(H.jumlah_item, 0)     AS jumlahTerima,
                0                            AS jumlahRetur,
                tR.jumlah_item               AS jumlahRencana,
                A.jumlah_item                AS jumlahRo,
                tH.jumlah_item               AS jumlahHps
            FROM db1.tdetailf_perencanaan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
            LEFT JOIN db1.masterf_kemasan AS Ds ON A.id_kemasandepo = Ds.id
            LEFT JOIN db1.masterf_kemasan AS E ON B.id_kemasanbesar = E.id
            LEFT JOIN db1.masterf_kemasan AS Es ON B.id_kemasankecil = Es.id
            LEFT JOIN db1.tdetailf_pembelian AS F ON A.kode_reffpl = F.kode_reff
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON F.kode_reffhps = tH.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS tR ON F.kode_reffrenc = tR.kode_reff
            LEFT JOIN (
                SELECT
                    kode_reffpl      AS kode_reffpl,
                    kode_reffrenc    AS kode_reffrenc,
                    id_katalog       AS id_katalog,
                    SUM(jumlah_item) AS jumlah_item
                FROM db1.tdetailf_pemesanan
                GROUP BY kode_reffrenc, id_katalog
            ) AS G ON G.id_katalog = A.id_katalog
            LEFT JOIN (
                SELECT
                    kode_reffpl      AS kode_reffpl,
                    kode_reffrenc    AS kode_reffrenc,
                    id_katalog       AS id_katalog,
                    SUM(jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan
                GROUP BY kode_reffpl, kode_reffrenc, id_katalog
            ) AS H ON H.id_katalog = A.id_katalog
            WHERE
                A.kode_reff = :kodeRef
                AND A.kode_reff = H.kode_reffrenc
                AND A.kode_reff = G.kode_reffrenc
                AND F.id_reffkatalog = tR.id_katalog
                AND F.id_reffkatalog = tH.id_reffkatalog
                AND A.id_katalog = F.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kodeRef" => "%$kodeReff%"];
        $iData = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($iData);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-87511bb
     */
    public function actionSearchJsonDo(): string
    {
        ["noDokumen" => $noDokumen] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                RCN.kode              AS kodePerencanaan,
                RCN.no_doc            AS noDokumenPerencanaan,
                RCN.blnawal_anggaran  AS bulanAwalAnggaran,
                RCN.blnakhir_anggaran AS bulanAkhirAnggaran,
                RCN.thn_anggaran      AS tahunAnggaran,
                RCN.id_jenisanggaran  AS idJenisAnggaran,
                RCN.id_sumberdana     AS idSumberDana,
                RCN.id_jenisharga     AS idJenisHarga,
                RCN.id_carabayar      AS idCaraBayar,
                RCN.ppn               AS ppn,
                RCN.nilai_akhir       AS nilaiAkhir,
                SJA.subjenis_anggaran AS subjenisAnggaran,
                PBF.nama_pbf          AS namaPemasok,
                RCN.kode_reffpl       AS kodeRefPl,
                RCN.id_pbf            AS idPemasok,
                BLI.no_doc            AS noSpk,
                BLI.tgl_doc           AS tanggalMulai,
                BLI.tgl_jatuhtempo    AS tanggalJatuhTempo,
                BLI.blnawal_anggaran  AS bulanAwalAnggaranPl,
                BLI.blnakhir_anggaran AS bulanAkhirAnggaranPl,
                BLI.thn_anggaran      AS tahunAnggaranPl
            FROM db1.transaksif_perencanaan AS RCN
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON RCN.id_jenisanggaran = SJA.id
            LEFT JOIN db1.masterf_sumberdana AS SDN ON RCN.id_sumberdana = SDN.id
            LEFT JOIN db1.masterf_subsumberdana AS SSDN ON RCN.id_subsumberdana = SSDN.id
            LEFT JOIN db1.masterf_jenisharga AS JHG ON RCN.id_jenisharga = JHG.id
            LEFT JOIN db1.masterf_carabayar AS CBY ON RCN.id_carabayar = CBY.id
            LEFT JOIN db1.masterf_pbf AS PBF ON PBF.id = RCN.id_pbf
            LEFT JOIN db1.transaksif_pembelian AS BLI ON BLI.kode = RCN.kode_reffpl
            WHERE
                RCN.no_doc LIKE :noDokumen
                AND RCN.sts_saved = 1
                AND RCN.sts_deleted = 0
                AND RCN.sts_closed = 0
                AND RCN.sts_revisi = 0
                AND RCN.tipe_doc = 3
            ORDER BY RCN.tgl_doc DESC
            LIMIT 30
        ";
        $params = [":noDokumen" => "%$noDokumen%"];
        $daftarPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPerencanaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-87511bb
     */
    private function fetchDetailHps(): array
    {
        ["kode_reff" => $kodeRef, "id_pbf" => $idPemasok, "id_jenisharga" => $idJenisHarga] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                                        AS idKatalog,
                ''                                                                  AS kodeRef,
                A.kode_reff                                                         AS kodeRefRencana,
                ''                                                                  AS kodeRefHps,
                A.id_katalog                                                        AS idRefKatalog,
                A.kemasan                                                           AS kemasan,
                A.id_kemasan                                                        AS idKemasan,
                A.id_kemasandepo                                                    AS idKemasanDepo,
                A.jumlah_item                                                       AS jumlahItem,
                A.jumlah_kemasan                                                    AS jumlahKemasan,
                IFNULL(B.harga_item, A.harga_item)                                  AS hargaItem,
                IF(A.isi_kemasan = B.isi_kemasan, B.harga_kemasan, A.harga_kemasan) AS hargaKemasan,
                A.isi_kemasan                                                       AS isiKemasan,
                IFNULL(B.diskon_item, A.diskon_item)                                AS diskonItem,
                C.nama_sediaan                                                      AS namaSediaan,
                IFNULL(C.kemasan, 0)                                                AS kemasanKat,
                C.id_kemasanbesar                                                   AS idKemasanKat,
                C.id_kemasankecil                                                   AS idKemasanDepoKat,
                C.isi_kemasan                                                       AS isiKemasanKat,
                C.harga_beli                                                        AS hargaItemKat,
                (C.harga_beli * C.isi_kemasan)                                      AS hargaKemasanKat,
                C.diskon_beli                                                       AS diskonItemKat,
                C.id_pabrik                                                         AS idPabrik,
                E.kode                                                              AS satuanJual,
                F.kode                                                              AS satuanJualKat,
                G.kode                                                              AS satuan,
                H.kode                                                              AS satuanKat,
                A.jumlah_item                                                       AS jumlahRencana,
                IFNULL(P.jumlah_pl, 0)                                              AS jumlahPl,
                IFNULL(P.jumlah_do, 0)                                              AS jumlahDo,
                IFNULL(P.jumlah_terima, 0)                                          AS jumlahTerima,
                D.nama_pabrik                                                       AS namaPabrik,
                R.no_doc                                                            AS noDokumen,
                R.no_doc                                                            AS noDokumenRencana,
                IF(C.jumlah_itembonus = 0, 0, IF(P.jumlah_do = 0, P.jumlah_pl / C.jumlah_itembeli * C.jumlah_itembonus, P.jumlah_do / C.jumlah_itembeli * C.jumlah_itembonus)) AS jumlahBonus,
                IFNULL(P.jumlah_ret, 0)                                             AS jumlahRetur,
                IFNULL(I.jumlah_hps, 0)                                             AS jumlahHps
            FROM db1.tdetailf_perencanaan AS A
            LEFT JOIN db1.transaksif_perencanaan AS R ON A.kode_reff = R.kode
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
            LEFT JOIN (
                SELECT
                    A.kode_reffrenc               AS kode_reffrenc,
                    A.id_katalog                  AS id_katalog,
                    IFNULL(SUM(A.jumlah_item), 0) AS jumlah_hps
                FROM db1.tdetailf_pengadaan AS A
                LEFT JOIN db1.transaksif_pengadaan AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffrenc, A.id_katalog
            ) AS I ON A.kode_reff = I.kode_reffrenc
            LEFT JOIN (
                SELECT
                    A.kode_reffrenc               AS kode_reffrenc,
                    A.id_reffkatalog              AS id_reffkatalog,
                    A.id_katalog                  AS id_katalog,
                    IFNULL(SUM(A.jumlah_item), 0) AS jumlah_pl,
                    IFNULL(D.jumlah_do, 0)        AS jumlah_do,
                    IFNULL(SUM(C.jumlah_item), 0) AS jumlah_terima,
                    IFNULL(E.jumlah_item, 0)      AS jumlah_ret
                FROM db1.tdetailf_pembelian AS A
                LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl                 AS kode_reffpl,
                        A.id_katalog                  AS id_katalog,
                        IFNULL(SUM(A.jumlah_item), 0) AS jumlah_do
                    FROM db1.tdetailf_pemesanan AS A
                    LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffpl != ''
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS D ON A.kode_reff = D.kode_reffpl
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffpl != ''
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS C ON A.kode_reff = C.kode_reffpl
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl                 AS kode_reffpl,
                        A.id_katalog                  AS id_katalog,
                        IFNULL(SUM(A.jumlah_item), 0) AS jumlah_item
                    FROM db1.tdetailf_return AS A
                    LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffpl != ''
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS E ON A.kode_reff = E.kode_reffpl
                WHERE
                    B.sts_deleted = 0
                    AND A.id_katalog = E.id_katalog
                    AND A.id_katalog = C.id_katalog
                    AND A.id_katalog = D.id_katalog
                GROUP BY A.kode_reffrenc, A.id_reffkatalog
            ) AS P ON A.kode_reff = P.kode_reffrenc
            WHERE
                R.sts_deleted = 0
                AND R.sts_closed = 0
                AND R.tipe_doc IN (1,2,3)
                AND A.kode_reff IN ($kodeRef)
                AND A.id_katalog = P.id_reffkatalog
                AND A.id_katalog = I.id_katalog
            ORDER BY C.nama_sediaan
        ";
        $params = [":idPemasok" => $idPemasok, ":idJenisHarga" => $idJenisHarga];
        return $connection->createCommand($sql, $params)->queryAll();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-87511bb
     */
    public function actionSearchJsonDetailHps(): string
    {
        $iData = $this->fetchDetailHps();
        return json_encode($iData);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-87511bb
     */
    public function actionSearchHtmlDetailHps(): string
    {
        $post = Yii::$app->request->post();
        $post["dataUrl"] = "farmasi/Perencanaan/";

        $data = $this->fetchDetailHps();

        if (!$data) return "tidak ada data";

        $view = new ListItem(data: $data);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-87511bb
     */
    public function actionSearchJsonLainnya(): string
    {
        [   "no_doc" => $noDokumen,
            "sts_deleted" => $statusDeleted,
            "sts_closed" => $statusClosed,
            "tipe_doc" => $tipeDokumen,
            "kode" => $kode,
            "id_jenisanggaran" => $idJenisAnggaran,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                RCN.kode              AS kode,
                RCN.no_doc            AS noDokumen,
                RCN.blnawal_anggaran  AS bulanAwalAnggaran,
                RCN.blnakhir_anggaran AS bulanAkhirAnggaran,
                RCN.thn_anggaran      AS tahunAnggaran,
                RCN.id_jenisanggaran  AS idJenisAnggaran,
                RCN.id_sumberdana     AS idSumberDana,
                RCN.id_subsumberdana  AS idSubsumberDana,
                RCN.id_jenisharga     AS idJenisHarga,
                RCN.id_carabayar      AS idCaraBayar,
                RCN.ppn               AS ppn,
                RCN.nilai_akhir       AS nilaiAkhir,
                SJA.subjenis_anggaran AS subjenisAnggaran,
                SDN.sumber_dana       AS sumberDana,
                SSDN.subsumber_dana   AS subsumberDana,
                JHG.jenis_harga       AS jenisHarga,
                CBY.cara_bayar        AS caraBayar
            FROM db1.transaksif_perencanaan AS RCN
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON RCN.id_jenisanggaran = SJA.id
            LEFT JOIN db1.masterf_sumberdana AS SDN ON RCN.id_sumberdana = SDN.id
            LEFT JOIN db1.masterf_subsumberdana AS SSDN ON RCN.id_subsumberdana = SSDN.id
            LEFT JOIN db1.masterf_jenisharga AS JHG ON RCN.id_jenisharga = JHG.id
            LEFT JOIN db1.masterf_carabayar AS CBY ON RCN.id_carabayar = CBY.id
            WHERE
                (:noDokumen = '' OR RCN.no_doc LIKE :noDokumen)
                AND (:statusDeleted = '' OR RCN.sts_deleted = :statusDeleted)
                AND (:statusClosed = '' OR RCN.sts_closed = :statusClosed)
                AND ('$tipeDokumen' = '' OR RCN.tipe_doc IN ($tipeDokumen))
                AND ('$kode' = '' OR RCN.kode IN ($kode))
                AND (:idJenisAnggaran = '' OR RCN.id_jenisanggaran = :idJenisAnggaran)
            ORDER BY RCN.no_doc ASC
            LIMIT 30
        ";
        $params = [
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":statusDeleted" => $statusDeleted,
            ":statusClosed" => $statusClosed,
            ":idJenisAnggaran" => $idJenisAnggaran,
        ];
        $daftarPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPerencanaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#ajaxDelete    the original method
     */
    public function actionAjaxDelete(): string
    {
        ["keterangan" => $keterangan, "kode" => $kode] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_perencanaan
            SET
                no_doc = kode,
                keterangan = CONCAT('Hapus Perencanaan Farmasi dengan No: ', :keterangan),
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#getUpdateTrn the original method
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi.Masterdata.php#nodokumen as the source of copied text
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
                FROM db1.transaksif_perencanaan
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
                FROM db1.transaksif_perencanaan
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
