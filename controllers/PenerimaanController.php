<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\{DataNotExistException, FailToInsertException, FailToUpdateException, FarmasiModel};
use tlm\his\FatmaPharmacy\views\Penerimaan\{
    PrintV01,
    PrintV01b,
    PrintV02,
    PrintV02b,
    PrintV03,
    PrintV04,
    PrintV05,
    PrintV06,
    PrintV07,
    PrintV08,
    ReportBukuInduk,
    ReportRekapitulasi,
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
class PenerimaanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#index    the original method
     */
    public function actionTableData(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;
        [   "bulanAnggaran" => $bulanAnggaran,
            "tahunAnggaran" => $tahunAnggaran,
            "tanggalDokumen" => $tanggalDokumen,
            "verTerima" => $verTerima,
            "verGudang" => $verGudang,
            "verAkuntansi" => $verAkuntansi,
            "noDokumen" => $noDokumen,
            "fakturSuratJalan" => $fakturSuratJalan,
            "noPo" => $noPo,
            "noSpk" => $noSpk,
            "namaPemasok" => $namaPemasok,
            "caraBayar" => $caraBayar,
            "kodeJenis" => $kodeJenis,
            "kodepl" => $kodePl,
            "filter" => $filter,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_penerimaan AS TRM
            LEFT JOIN db1.transaksif_pembelian AS BLI ON BLI.kode = TRM.kode_reffpl
            LEFT JOIN db1.transaksif_pemesanan AS PSN ON PSN.kode = TRM.kode_reffpo
            LEFT JOIN db1.masterf_pbf AS PBF ON PBF.id = TRM.id_pbf
            LEFT JOIN db1.masterf_carabayar AS CBY ON CBY.id = TRM.id_carabayar
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON TRM.id_jenisanggaran = SJA.id
            LEFT JOIN db1.user AS UPDT ON TRM.userid_updt = UPDT.id
            LEFT JOIN db1.user AS UTRM ON ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON ver_usrakuntansi = UAKN.id
            WHERE
                TRM.sts_deleted = 0
                AND (:bulanAnggaran = '' OR TRM.blnawal_anggaran = :bulanAnggaran OR TRM.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR TRM.thn_anggaran = :tahunAnggaran)
                AND (:tanggalDokumen = '' OR TRM.tgl_doc = :tanggalDokumen)
                AND (:verTerima = '' OR TRM.ver_terima = :verTerima)
                AND (:verGudang = '' OR TRM.ver_gudang = :verGudang)
                AND (:verAkuntansi = '' OR TRM.ver_akuntansi = :verAkuntansi)
                AND (:noDokumen = '' OR TRM.no_doc LIKE :noDokumen)
                AND (:fakturSuratJalan = '' OR TRM.no_faktur LIKE :fakturSuratJalan OR TRM.no_suratjalan LIKE :fakturSuratJalan)
                AND (:noPo = '' OR PSN.no_doc LIKE :noPo)
                AND (:noSpk = '' OR BLI.no_doc LIKE :noSpk)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:caraBayar = '' OR CBY.cara_bayar = :caraBayar)
                AND (:kodeJenis = '' OR SJA.kode = :kodeJenis)
                AND (
                    (:prefix = 'K' AND TRM.kode_reffkons = :kode)
                    OR (:prefix = 'S' AND TRM.kode_reffpo = :kode)
                    OR (:prefix = 'P' AND TRM.kode_reffpl = :kode)
                    OR (:prefix = 'R' AND TRM.kode_reffrenc = :kode)
                    OR (:prefix = 'O' AND TRM.kode_reffro = :kode)
                    OR TRUE
                )
                AND (:kodeRefPl = '' OR TRM.kode_reffpl = :kodeRefPl)
        ";
        $params = [
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
            ":tanggalDokumen" => $tanggalDokumen ? $toSystemDate($tanggalDokumen) : "",
            ":verTerima" => $verTerima,
            ":verGudang" => $verGudang,
            ":verAkuntansi" => $verAkuntansi,
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":fakturSuratJalan" => $fakturSuratJalan ? "%$fakturSuratJalan%" : "",
            ":noPo" => $noPo ? "%$noPo%" : "",
            ":noSpk" => $noSpk ? "%$noSpk%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":caraBayar" => $caraBayar,
            ":kodeJenis" => $kodeJenis,
            ":prefix" => substr($filter, 0, 1),
            ":kode" => substr($filter, 2, 17),
            ":kodeRefPl" => $kodePl,
        ];
        $jumlahPenerimaan = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                TRM.kode                     AS kode,
                IFNULL(TRM.kode_reffkons, 0) AS kodeRefKons,
                TRM.no_doc                   AS noDokumen,
                TRM.tgl_doc                  AS tanggalDokumen,
                TRM.no_faktur                AS noFaktur,
                TRM.no_suratjalan            AS noSuratJalan,
                TRM.nilai_akhir              AS nilaiAkhir,
                TRM.ver_terima               AS verTerima,
                TRM.ver_gudang               AS verGudang,
                TRM.ver_akuntansi            AS verAkuntansi,
                TRM.revisike                 AS revisiKe,
                TRM.sts_izinrevisi           AS statusIzinRevisi,
                TRM.tipe_doc                 AS tipeDokumen,
                TRM.sts_revisi               AS statusRevisi,
                TRM.blnawal_anggaran         AS bulanAwalAnggaran,
                TRM.blnakhir_anggaran        AS bulanAkhirAnggaran,
                TRM.thn_anggaran             AS tahunAnggaran,
                TRM.sysdate_updt             AS updatedTime,
                TRM.ver_tglterima            AS verTanggalTerima,
                TRM.ver_tglgudang            AS verTanggalGudang,
                TRM.ver_tglakuntansi         AS verTanggalAkuntansi,
                TRM.ver_revisi               AS verRevisi,
                TRM.ver_revterima            AS verRevTerima,
                BLI.no_doc                   AS noSpk,
                PSN.no_doc                   AS noPo,
                PBF.nama_pbf                 AS namaPemasok,
                UTRM.name                    AS namaUserTerima,
                UPDT.name                    AS updatedBy,
                UGDG.name                    AS namaUserGudang,
                UAKN.name                    AS namaUserAkuntansi,
                CBY.cara_bayar               AS caraBayar,
                SJA.kode                     AS kodeJenis
            FROM db1.transaksif_penerimaan AS TRM
            LEFT JOIN db1.transaksif_pembelian AS BLI ON BLI.kode = TRM.kode_reffpl
            LEFT JOIN db1.transaksif_pemesanan AS PSN ON PSN.kode = TRM.kode_reffpo
            LEFT JOIN db1.masterf_pbf AS PBF ON PBF.id = TRM.id_pbf
            LEFT JOIN db1.masterf_carabayar AS CBY ON CBY.id = TRM.id_carabayar
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON TRM.id_jenisanggaran = SJA.id
            LEFT JOIN db1.user AS UPDT ON TRM.userid_updt = UPDT.id
            LEFT JOIN db1.user AS UTRM ON ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON ver_usrakuntansi = UAKN.id
            WHERE
                TRM.sts_deleted = 0
                AND (:bulanAnggaran = '' OR TRM.blnawal_anggaran = :bulanAnggaran OR TRM.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR TRM.thn_anggaran = :tahunAnggaran)
                AND (:tanggalDokumen = '' OR TRM.tgl_doc = :tanggalDokumen)
                AND (:verTerima = '' OR TRM.ver_terima = :verTerima)
                AND (:verGudang = '' OR TRM.ver_gudang = :verGudang)
                AND (:verAkuntansi = '' OR TRM.ver_akuntansi = :verAkuntansi)
                AND (:noDokumen = '' OR TRM.no_doc LIKE :noDokumen)
                AND (:fakturSuratJalan = '' OR TRM.no_faktur LIKE :fakturSuratJalan OR TRM.no_suratjalan LIKE :fakturSuratJalan)
                AND (:noPo = '' OR PSN.no_doc LIKE :noPo)
                AND (:noSpk = '' OR BLI.no_doc LIKE :noSpk)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:caraBayar = '' OR CBY.cara_bayar = :caraBayar)
                AND (:kodeJenis = '' OR SJA.kode = :kodeJenis)
                AND (
                    (:prefix = 'K' AND TRM.kode_reffkons = :kode)
                    OR (:prefix = 'S' AND TRM.kode_reffpo = :kode)
                    OR (:prefix = 'P' AND TRM.kode_reffpl = :kode)
                    OR (:prefix = 'R' AND TRM.kode_reffrenc = :kode)
                    OR (:prefix = 'O' AND TRM.kode_reffro = :kode)
                    OR TRUE
                )
                AND (:kodeRefPl = '' OR TRM.kode_reffpl = :kodeRefPl)
            ORDER BY TRM.tgl_doc DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "recordsFiltered" => $jumlahPenerimaan,
            "data" => $daftarPenerimaan
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#revisi    the original method
     */
    public function actionTableRevisiData(): string
    {
        [   // "kode" is from table row button
            "kode" => $kode,

            // the others are from form.
            "revisiKe" => $revisiKe,
            "noDokumenBaru" => $noDokumenBaru,
            "noDokumenLama" => $noDokumenLama,
            "tanggalDokumen" => $tanggalDokumen,
            "fakturSuratJalan" => $fakturSuratJalan,
            "noRefDoPl" => $noRefDoPl,
            "kodeJenis" => $kodeJenis,
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
                A.revisike                 AS revisiKe,
                B.no_doc                   AS noDokumenBaru,
                A.tgl_doc                  AS tanggalDokumen,
                A.no_doc                   AS noDokumenLama,
                A.no_faktur                AS noFaktur,
                A.no_suratjalan            AS noSuratJalan,
                IFNULL(C.no_doc, D.no_doc) AS noRefDoPl,
                E.nama_pbf                 AS namaPemasok,
                G.kode                     AS kodeJenis,
                A.nilai_akhir              AS nilaiAkhir,
                A.keterangan               AS keterangan
            FROM db1.transaksif_revpenerimaan AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode = B.kode
            LEFT JOIN db1.transaksif_pemesanan AS C ON A.kode_reffpo = C.kode
            LEFt JOIN db1.transaksif_pembelian AS D ON A.kode_reffpl = D.kode
            LEFt JOIN db1.masterf_pbf AS E ON A.id_pbf = E.id
            LEFt JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFt JOIN db1.masterf_subjenisanggaran AS G ON A.id_jenisanggaran = G.id
            LEFt JOIN db1.user AS U ON A.userid_updt = U.id
            WHERE
                A.sts_deleted = 0
                AND (:kode = '' OR A.kode = :kode)
                AND (:revisiKe = '' OR A.revisike LIKE :revisiKe)
                AND (:noDokumenBaru = '' OR B.no_doc LIKE :noDokumenBaru)
                AND (:noDokumenLama = '' OR A.no_doc LIKE :noDokumenLama)
                AND (:tanggalDokumen = '' OR A.tgl_doc = :tanggalDokumen)
                AND (:fakturSuratJalan = '' OR A.no_faktur LIKE :fakturSuratJalan OR A.no_suratjalan LIKE :fakturSuratJalan)
                AND (:noRefDoPl = '' OR C.no_doc LIKE :noRefDoPl OR D.no_doc LIKE :noRefDoPl)
                AND (:kodeJenis = '' OR G.kode LIKE :kodeJenis)
            ORDER BY 
                A.kode DESC,
                A.no_doc ASC,
                A.revisike DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":kode" => $kode ?? "",
            ":revisiKe" => $revisiKe ? "%$revisiKe%" : "",
            ":noDokumenBaru" => $noDokumenBaru ? "%$noDokumenBaru%" : "",
            ":noDokumenLama" => $noDokumenLama ? "%$noDokumenLama%" : "",
            ":tanggalDokumen" => $tanggalDokumen ? $toSystemDate($tanggalDokumen) : "",
            ":fakturSuratJalan" => $fakturSuratJalan ? "%$fakturSuratJalan%" : "",
            ":noRefDoPl" => $noRefDoPl ? "%$noRefDoPl%" : "",
            ":kodeJenis" => $kodeJenis ? "%$kodeJenis%" : "",
        ];
        $daftarRevisiPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_revpenerimaan AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode = B.kode
            LEFT JOIN db1.transaksif_pemesanan AS C ON A.kode_reffpo = C.kode
            LEFt JOIN db1.transaksif_pembelian AS D ON A.kode_reffpl = D.kode
            LEFt JOIN db1.masterf_pbf AS E ON A.id_pbf = E.id
            LEFt JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFt JOIN db1.masterf_subjenisanggaran AS G ON A.id_jenisanggaran = G.id
            LEFt JOIN db1.user AS U ON A.userid_updt = U.id
            WHERE
                A.sts_deleted = 0
                AND (:kode = '' OR A.kode = :kode)
                AND (:revisiKe = '' OR A.revisike LIKE :revisiKe)
                AND (:noDokumenBaru = '' OR B.no_doc LIKE :noDokumenBaru)
                AND (:noDokumenLama = '' OR A.no_doc LIKE :noDokumenLama)
                AND (:tanggalDokumen = '' OR A.tgl_doc = :tanggalDokumen)
                AND (:fakturSuratJalan = '' OR A.no_faktur LIKE :fakturSuratJalan OR A.no_suratjalan LIKE :fakturSuratJalan)
                AND (:noRefDoPl = '' OR C.no_doc LIKE :noRefDoPl OR D.no_doc LIKE :noRefDoPl)
                AND (:kodeJenis = '' OR G.kode LIKE :kodeJenis)
        ";
        $jumlahRevisiPenerimaan = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode([
            "recordsFiltered" => $jumlahRevisiPenerimaan,
            "data" => $daftarRevisiPenerimaan
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#add    the original method
     */
    public function actionSaveForm(): void
    {
        [   "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
            "no_faktur" => $noFaktur,
            "no_suratjalan" => $noSuratJalan,
            "id_pbf" => $idPemasok,
            "id_gudangpenyimpanan" => $idGudangPenyimpanan,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "ppn" => $ppn,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "sts_tabunggm" => $statusTabungGasMedis,
            "action" => $action,
            "ver_terima" => $verTerima,
            "kode_reffpl" => $daftarKodeRefPl, // TODO: php: uncategorized: refactor form to a single var
            "kode_reffpo" => $daftarKodeRefPo, // TODO: php: uncategorized: refactor form to a single var
            "kode_reffro" => $daftarKodeRefRo, // TODO: php: uncategorized: refactor form to a single var
            "kode_reffrenc" => $daftarKodeRefRencana, // TODO: php: uncategorized: refactor form to a single var
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "diskon_item" => $daftarDiskonItem,
            "harga_item" => $daftarHargaItem,
            "harga_kemasan" => $daftarHargaKemasan,
            "j_beli" => $daftarJumlahBeli,
            "j_bonus" => $daftarJumlahBonus,
            "id_reffkatalog" => $daftarIdRefKatalog,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "jumlah_itembonus" => $daftarJumlahItemBonus,
            "no_batch" => $daftarNoBatch,
            "no_urut" => $daftarNoUrut,
            "tgl_expired" => $daftarTanggalKadaluarsa,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $kodeRefPl = $daftarKodeRefPl[0];
        $kodeRefPo = $daftarKodeRefPo[0];
        $kodeRefRo = $daftarKodeRefRo[0];
        $kodeRefRencana = $daftarKodeRefRencana[0];
        $ppn ??= 0;

        $dataPenerimaan = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "kode_reffpl" => $kodeRefPl,
            "kode_reffpo" => $kodeRefPo,
            "kode_reffro" => $kodeRefRo,
            "kode_reffrenc" => $kodeRefRencana,
            "no_faktur" => $noFaktur,
            "no_suratjalan" => $noSuratJalan,
            "id_pbf" => $idPemasok,
            "id_gudangpenyimpanan" => $idGudangPenyimpanan,
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

        isset($statusTabungGasMedis) ? $dataPenerimaan["sts_tabunggm"] = $statusTabungGasMedis : null;

        // setting data untuk penerimaan bonus
        if ($action == "addbonus") {
            $dataPenerimaan = [
                ...$dataPenerimaan,
                "tipe_doc" => 1,
                "id_carabayar" => 18,
            ];
        }

        // set no Transaksi
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();
        $fm = new FarmasiModel;

        if ($action == "add" || $action == "addbonus") {
            $counter = $this->getUpdateTrn([
                "initial" => "T",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Penerimaan Bulan " . date("n") . " Tahun " . date("Y"),
                "userid_updt" => $idUser
            ]);
            $kode = "T00" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);

            $dataPenerimaan = [
                ...$dataPenerimaan,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        if ($verTerima == 1) {
            $dataPenerimaan = [
                ...$dataPenerimaan,
                "ver_terima" => 1,
                "ver_tglterima" => $nowValSystem,
                "ver_usrterima" => $idUser,
            ];
        }

        $dataDetailPenerimaan = [];
        $dataRincianDetailPenerimaan = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            if (!$daftarJumlahKemasan[$i]) continue;
            if ($dataDetailPenerimaan[$idKatalog]) {
                $dataDetailPenerimaan[$idKatalog]["jumlah_item"] += $toSystemNumber($daftarJumlahItem[$i]);
                $dataDetailPenerimaan[$idKatalog]["jumlah_kemasan"] += $toSystemNumber($daftarJumlahKemasan[$i]);

            } else {
                $diskonItem = $toSystemNumber($daftarDiskonItem[$n]);
                $hargaItem = $toSystemNumber($daftarHargaItem[$n]);
                $hargaKemasan = $toSystemNumber($daftarHargaKemasan[$n]);

                $hna = $ppn ? $hargaItem  :  $hargaItem / 1.1;
                $hnaPpn = $ppn ? $hargaItem * 1.1  :  $hargaItem;

                $diskonHarga = $hargaItem * $diskonItem / 100;
                $hargaSetelahDiskon = $hargaItem - $diskonHarga;
                $hargaPpn = $hargaSetelahDiskon * $ppn / 100;
                $hargaPerolehan = $hargaSetelahDiskon + $hargaPpn;

                // persentase hja
                $map = [
                    [        0,     50_000, 28],
                    [   50_000,    250_000, 26],
                    [  250_000,    500_000, 21],
                    [  500_000,  1_000_000, 16],
                    [1_000_000,  5_000_000, 11],
                    [5_000_000, 10_000_000,  9],
                ];

                $phja = 7;
                foreach ($map as [$batasBawah, $batasAtas, $angka]) {
                    $phja = ($hnaPpn >= $batasBawah and $hnaPpn < $batasAtas) ? $angka : $phja;
                }

                $hja = $hnaPpn + ($hnaPpn * $phja / 100);

                // setting HNA, HP, HJA untuk barang berbonus
                $pembagi = $daftarJumlahBeli[$n] + $daftarJumlahBonus[$n];
                if (($action == "addbonus" || $action == "editbonus") && $daftarJumlahBonus[$n] != 0) {
                    $hargaItem = 0;
                    $hargaKemasan = 0;
                    $diskonItem = 0;
                    $diskonHarga = 0;
                }
                $hpJaminan = $hargaPerolehan / $pembagi;
                $hjaJaminan = $hja / $pembagi;

                $dataDetailPenerimaan[$idKatalog] = [
                    "kode_reff" => $kode,
                    "kode_reffpl" => $kodeRefPl,
                    "kode_reffpo" => $kodeRefPo,
                    "kode_reffro" => $kodeRefRo,
                    "kode_reffrenc" => $kodeRefRencana,
                    "id_katalog" => $idKatalog,
                    "id_reffkatalog" => $daftarIdRefKatalog[$n],
                    "kemasan" => $daftarKemasan[$n],
                    "id_pabrik" => $daftarIdPabrik[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$n]),
                    "id_kemasandepo" => $daftarIdKemasanDepo[$n],
                    "jumlah_item" => $toSystemNumber($daftarJumlahItem[$i]),
                    "jumlah_itembonus" => $daftarJumlahItemBonus[$n],
                    "jumlah_kemasan" => $toSystemNumber($daftarJumlahKemasan[$i]),
                    "harga_item" => $hargaItem,
                    "harga_kemasan" => $hargaKemasan,
                    "diskon_item" => $diskonItem,
                    "diskon_harga" => $diskonHarga,
                    "hna_item" => $hna,
                    "hp_item" => $hpJaminan,
                    "hppb_item" => $hargaPerolehan,
                    "phja_item" => $phja,
                    "phjapb_item" => $phja,
                    "hja_item" => $hjaJaminan,
                    "hjapb_item" => $hja,
                    "userid_updt" => $idUser
                ];
                $n++;
            }

            $dataRincianDetailPenerimaan[$k++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_batch" => $daftarNoBatch[$i],
                "tgl_expired" => null,
                "no_urut" => $daftarNoUrut[$i],
                "jumlah_item" => $toSystemNumber($daftarJumlahItem[$i]),
                "jumlah_kemasan" => $toSystemNumber($daftarJumlahKemasan[$i])
            ];

            // set expired
            if ($daftarTanggalKadaluarsa[$k - 1]) {
                $dataRincianDetailPenerimaan[$k - 1]["tgl_expired"] = $toSystemDate($daftarTanggalKadaluarsa[$i]);
            }
        }

        $daftarField = array_keys($dataPenerimaan);
        if ($action == "add" || $action == "addbonus") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*)
                FROM db1.transaksif_penerimaan
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $jumlahPenerimaan = $connection->createCommand($sql, $params)->queryScalar();
            if (!$jumlahPenerimaan) throw new DataNotExistException($kode, $noDokumen, $transaction);

            $berhasilTambah = $fm->saveData("transaksif_penerimaan", $daftarField, $dataPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Penerimaan", $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_pembelian
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefPl];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kodeRefPl, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_pemesanan
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefPo];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kodeRefPo, $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_penerimaanrinc", $dataRincianDetailPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Penerimaan", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_penerimaan", $dataDetailPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Penerimaan", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_penerimaan", $daftarField, $dataPenerimaan, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "kode", $kode, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_pembelian
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefPl];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kodeRefPl, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_pemesanan
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefPo];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kodeRefPo, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_penerimaanrinc", $dataRincianDetailPenerimaan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Rincian Penerimaan", "Kode Ref", $kode, $transaction);

            $berhasilUbah = $fm->saveBatch("tdetailf_penerimaan", $dataDetailPenerimaan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Penerimaan", "Kode Ref", $kode, $transaction);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @see - (none)
     */
    public function actionSaveFormGas(): void
    {
        $this->actionSaveForm();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see - (none)
     */
    public function actionFormGasData(): string
    {
        return $this->actionFormData();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#edit    the original method
     */
    public function actionFormData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                     AS kode,
                A.revisike                                 AS revisiKe,
                A.keterangan                               AS keterangan,
                A.no_doc                                   AS noDokumen,
                A.tgl_doc                                  AS tanggalDokumen,
                A.tipe_doc                                 AS tipeDokumen,
                A.kode_refftrm                             AS kodeRefTerima,
                A.kode_reffpl                              AS kodeRefPl,
                A.kode_reffpo                              AS kodeRefPo,
                A.kode_reffro                              AS kodeRefRo,
                A.kode_reffrenc                            AS kodeRefRencana,
                A.kode_reffkons                            AS kodeRefKons,
                A.no_faktur                                AS noFaktur,
                A.no_suratjalan                            AS noSuratJalan,
                A.terimake                                 AS terimaKe,
                A.id_pbf                                   AS idPemasok,
                A.id_gudangpenyimpanan                     AS idGudangPenyimpanan,
                A.id_jenisanggaran                         AS idJenisAnggaran,
                A.id_sumberdana                            AS idSumberDana,
                A.id_subsumberdana                         AS idSubsumberDana,
                A.id_carabayar                             AS idCaraBayar,
                A.id_jenisharga                            AS idJenisHarga,
                A.thn_anggaran                             AS tahunAnggaran,
                A.blnawal_anggaran                         AS bulanAwalAnggaran,
                A.blnakhir_anggaran                        AS bulanAkhirAnggaran,
                A.ppn                                      AS ppn,
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_tabunggm                             AS statusTabungGasMedis,
                A.sts_linked                               AS statusLinked,
                A.sts_revisi                               AS statusRevisi,
                A.sysdate_rev                              AS sysdateRevisi,
                A.keterangan_rev                           AS keteranganRevisi,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_updatekartu                          AS statusUpdateKartu,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revterima                            AS verRevTerima,
                A.ver_revtglterima                         AS verRevTanggalTerima,
                A.ver_revusrterima                         AS verRevUserTerima,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalRevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.sts_testing                              AS statusTesting,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                IFNULL(B.ppn, C.ppn)                       AS ppnBonus,
                B.no_doc                                   AS noPo,
                IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim,
                B.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                B.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                B.thn_anggaran                             AS tahunAnggaranPo,
                C.tipe_doc                                 AS tipeDokumen,
                C.no_doc                                   AS noSpk,
                C.tipe_doc                                 AS tipeSpk,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                F.no_doc                                   AS noRencana,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi, 
                A.sts_tabunggm                             AS statusTabungGasMedis, -- in use
                A.id_jenisanggaran                         AS idJenisAnggaran,
                A.id_jenisharga                            AS idJenisHarga,
                A.id_carabayar                             AS idCaraBayar,
                A.id_pbf                                   AS idPemasok,
                A.id_sumberdana                            AS idSumberDana
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.transaksif_perencanaan AS F ON A.kode_reffrenc = F.kode
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
                AND A.ver_gudang = 0
                AND A.sts_deleted = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$penerimaan) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                                                                         AS kodeRef,
                B.id_katalog                                                                        AS idKatalog,
                B.kode_reffrenc                                                                     AS kodeRefRencana,
                B.kode_reffpo                                                                       AS kodeRefPo,
                B.kode_reffro                                                                       AS kodeRefRo,
                B.kode_reffpl                                                                       AS kodeRefPl,
                B.kode_refftrm                                                                      AS kodeRefTerima,
                B.kode_reffkons                                                                     AS kodeRefKons,
                B.id_reffkatalog                                                                    AS idRefKatalog,
                B.kemasan                                                                           AS kemasan,
                B.id_pabrik                                                                         AS idPabrik,
                B.id_kemasan                                                                        AS idKemasan,
                B.isi_kemasan                                                                       AS isiKemasan,
                B.id_kemasandepo                                                                    AS idKemasanDepo,
                B.jumlah_item                                                                       AS jumlahItem,
                B.jumlah_kemasan                                                                    AS jumlahKemasan,
                B.jumlah_itembonus                                                                  AS jumlahItemBonus,
                B.harga_item                                                                        AS hargaItem,
                B.harga_kemasan                                                                     AS hargaKemasan,
                B.diskon_item                                                                       AS diskonItem,
                B.diskon_harga                                                                      AS diskonHarga,
                B.hna_item                                                                          AS hnaItem,
                B.hp_item                                                                           AS hpItem,
                B.hppb_item                                                                         AS hpPbItem,
                B.phja_item                                                                         AS phjaItem,
                B.phjapb_item                                                                       AS phjaPbItem,
                B.hja_item                                                                          AS hjaItem,
                B.hjapb_item                                                                        AS hjaPbItem,
                B.sts_revisiitem                                                                    AS statusRevisiItem,
                B.keterangan                                                                        AS keterangan,
                B.userid_updt                                                                       AS useridUpdate,
                B.sysdate_updt                                                                      AS sysdateUpdate,
                A.kode_reff                                                                         AS kodeRef,
                A.id_katalog                                                                        AS idKatalog,
                A.no_reffbatch                                                                      AS noRefBatch,
                A.no_batch                                                                          AS noBatch,
                A.tgl_expired                                                                       AS tanggalKadaluarsa,
                A.no_urut                                                                           AS noUrut,
                A.jumlah_item                                                                       AS jumlahItem,
                A.jumlah_kemasan                                                                    AS jumlahKemasan,
                A.id                                                                                AS id,
                IFNULL(A.tgl_expired, '')                                                           AS tanggalKadaluarsa,
                K.nama_sediaan                                                                      AS namaSediaan,
                PBK.nama_pabrik                                                                     AS namaPabrik,
                KSAR.kode                                                                           AS satuanJual,
                KCIL.kode                                                                           AS satuan,    -- satuan kecil
                K1.kode                                                                             AS satuanJualKat,
                K2.kode                                                                             AS satuanKat,
                K.jumlah_itembeli                                                                   AS jumlahItemBeli,
                K.jumlah_itembonus                                                                  AS jumlahItemBonus,
                K.kemasan                                                                           AS kemasanKat,
                K.isi_kemasan                                                                       AS isiKemasanKat,
                K.id_kemasanbesar                                                                   AS idKemasanKat,
                K.id_kemasankecil                                                                   AS idKemasanDepoKat,
                K.harga_beli                                                                        AS hargaItemKat,
                K.harga_beli * K.isi_kemasan                                                        AS hargaKemasanKat,
                IFNULL(tR.jumlah_item, 0)                                                           AS jumlahRencana,
                IFNULL(tH.jumlah_item, 0)                                                           AS jumlahHps,
                C.jumlah_item                                                                       AS jumlahPl,
                IFNULL(tRo.jumlah_item, 0)                                                          AS jumlahRo,
                IFNULL(D.jumlah_item, 0)                                                            AS jumlahDo,
                IFNULL(T_pl.jumlah_item, 0)                                                         AS jumlahTerimaPl,
                IFNULL(T_po.jumlah_item, 0)                                                         AS jumlahTerimaPo,
                IF(IFNULL(B.kode_reffpo, 0) = 0, IFNULL(T_pl.jumlah_item, 0), T_po.jumlah_item)     AS jumlahTerima,
                IF(IFNULL(Rt_po.kode_reffpo, 0)=0, IFNULL(Rt_pl.jumlah_item, 0), Rt_po.jumlah_item) AS jumlahRetur,
                IFNULL(Tb_po.jumlah_item, IFNULL(Tb_pl.jumlah_item, 0))                             AS jumlahTBonus,
                IFNULL(Rtb.jumlah_item, 0)                                                          AS jumlahReturBonus,
                IFNULL(D.harga_item, C.harga_item)                                                  AS bHargaItem,
                IFNULL(D.harga_kemasan, C.harga_kemasan)                                            AS bHargaKemasan,
                IFNULL(D.diskon_item, C.diskon_item)                                                AS bDiskonItem
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = B.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS K1 ON K1.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS K2 ON K2.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS C ON B.kode_reffpl = C.kode_reff
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON C.id_reffkatalog = tH.id_reffkatalog
            LEFT JOIN db1.tdetailf_perencanaan AS tR ON C.id_reffkatalog = tR.id_katalog
            LEFT JOIN db1.tdetailf_pemesanan AS D ON B.kode_reffpo = D.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS tRo ON B.id_katalog = tRo.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS T_po ON D.kode_reff = T_po.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS T_pl ON C.kode_reff = T_pl.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND B.tipe_doc = 1
                    AND A.kode_reff != :kode
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS Tb_pl ON B.kode_reffpl = Tb_pl.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                    AND B.tipe_doc = 1
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Tb_po ON B.kode_reffpo = Tb_po.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Rt_po ON D.kode_reff = Rt_po.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                INNER JOIN db1.transaksif_penerimaan AS T ON A.kode_refftrm = T.kode
                WHERE
                    B.sts_deleted = 0
                    AND B.tipe_doc = 1
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS Rtb ON B.kode_reff = Rtb.kode_refftrm
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS Rt_pl ON C.kode_reff = Rt_pl.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND B.id_katalog = Rt_pl.id_katalog
                AND B.id_katalog = Rtb.id_katalog
                AND B.id_katalog = Rt_po.id_katalog
                AND B.id_katalog = Tb_po.id_katalog
                AND B.id_katalog = Tb_pl.id_katalog
                AND B.id_katalog = T_pl.id_katalog
                AND B.id_katalog = T_po.id_katalog
                AND B.kode_reffro = tRo.kode_reff
                AND B.id_katalog = D.id_katalog
                AND C.kode_reffrenc = tR.kode_reff
                AND C.kode_reffhps = tH.kode_reff
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
        ";
        $params = [":kode" => $kode];
        $daftarRincianDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode(["penerimaan" => $penerimaan, "daftarRincianDetailPenerimaan" => $daftarRincianDetailPenerimaan]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addOthers    the original method
     */
    public function actionSaveFormLainnya(): void
    {
        [   "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
            "no_faktur" => $noFaktur,
            "no_suratjalan" => $noSuratJalan,
            "id_pbf" => $idPemasok,
            "id_gudangpenyimpanan" => $idGudangPenyimpanan,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "ppn" => $ppn,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "sts_tabunggm" => $statusTabungGasMedis,
            "action" => $action,
            "ver_terima" => $verTerima,
            "tipe_doc" => $tipeDokumen,
            "keterangan" => $keterangan,
            "userid_updt" => $idUserUpdate,
            "kode_reffpl" => $daftarKodeRefPl, // TODO: php: uncategorized: refactor form to a single var
            "kode_reffpo" => $daftarKodeRefPo, // TODO: php: uncategorized: refactor form to a single var
            "id_katalog" => $daftarIdKatalog,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "diskon_item" => $daftarDiskonItem,
            "harga_item" => $daftarHargaItem,
            "harga_kemasan" => $daftarHargaKemasan,
            "j_beli" => $daftarJumlahBeli,
            "j_bonus" => $daftarJumlahBonus,
            "id_reffkatalog" => $daftarIdRefKatalog,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "jumlah_itembonus" => $daftarJumlahItemBonus,
            "no_batch" => $daftarNoBatch,
            "no_urut" => $daftarNoUrut,
            "tgl_expired" => $daftarTanggalKadaluarsa,
            "diskon_harga" => $daftarDiskonHarga,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        $kodeRefPl = $daftarKodeRefPl[0];
        $kodeRefPo = $daftarKodeRefPo[0];

        $dataPenerimaan = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tipe_doc" => $tipeDokumen,
            "kode_reffpl" => $kodeRefPl,
            "kode_reffpo" => $kodeRefPo,
            "no_faktur" => $noFaktur,
            "no_suratjalan" => $noSuratJalan,
            "id_pbf" => $idPemasok,
            "id_gudangpenyimpanan" => $idGudangPenyimpanan,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_carabayar" => $idCaraBayar,
            "id_jenisharga" => $idJenisHarga,
            "thn_anggaran" => $tahunAnggaran,
            "blnawal_anggaran" => $bulanAwalAnggaran,
            "blnakhir_anggaran" => $bulanAkhirAnggaran,
            "ppn" => $toSystemNumber($ppn),
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
            "keterangan" => $keterangan,
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        isset($statusTabungGasMedis) ? $dataPenerimaan["sts_tabunggm"] = $statusTabungGasMedis : null;

        switch ($idCaraBayar) {
            case 16: $dataPenerimaan["tipe_doc"] = 4; break;
            case 17: $dataPenerimaan["tipe_doc"] = 2; break;
            case 18: $dataPenerimaan["tipe_doc"] = 1; break;
            default: $dataPenerimaan["tipe_doc"] = 3;
        }

        // set no Transaksi
        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "T",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Penerimaan Bulan " . date("n") . " Tahun " . date("Y"),
                "userid_updt" => $idUserUpdate,
            ]);
            $kode = "T00" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);

            $dataPenerimaan = [
                ...$dataPenerimaan,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        if ($verTerima == 1) {
            $dataPenerimaan = [
                ...$dataPenerimaan,
                "ver_terima" => 1,
                "ver_tglterima" => $nowValSystem,
                "ver_usrterima" => $idUser,
            ];
        }

        $dataDetailPenerimaan = [];
        $dataRincianDetailPenerimaan = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            if (!$jumlahKemasan) continue;

            if ($dataDetailPenerimaan[$idKatalog]) {
                [   "jumlah_kemasan" => $jumlah,
                    "harga_kemasan" => $harga,
                    "diskon_item" => $diskon,
                ] = $dataDetailPenerimaan[$idKatalog];

                $dataDetailPenerimaan[$idKatalog]["jumlah_item"] += $jumlahItem;
                $dataDetailPenerimaan[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;
                $dataDetailPenerimaan[$idKatalog]["diskon_harga"] = $jumlah * $harga * $diskon / 100;

            } else {
                $diskonItem = $toSystemNumber($daftarDiskonItem[$n]);
                $hargaItem = $toSystemNumber($daftarHargaItem[$n]);
                $hargaKemasan = $toSystemNumber($daftarHargaKemasan[$n]);

                $hna = $ppn ? $hargaItem  :  $hargaItem / 1.1;
                $hnaPpn = $ppn ? $hargaItem * 1.1  :  $hargaItem;

                $diskonHarga = $hargaItem * $diskonItem / 100;
                $hargaSetelahDiskon = $hargaItem - $diskonHarga;
                $hargaPpn = $hargaSetelahDiskon * $ppn / 100;
                $hargaPerolehan = $hargaSetelahDiskon + $hargaPpn;

                // persentase hja
                $map = [
                    [        0,     50_000, 28],
                    [   50_000,    250_000, 26],
                    [  250_000,    500_000, 21],
                    [  500_000,  1_000_000, 16],
                    [1_000_000,  5_000_000, 11],
                    [5_000_000, 10_000_000,  9],
                ];

                $phja = 7;
                foreach ($map as [$batasBawah, $batasAtas, $angka]) {
                    $phja = ($hnaPpn >= $batasBawah and $hnaPpn < $batasAtas) ? $angka : $phja;
                }

                $hja = $hnaPpn + ($hnaPpn * $phja / 100);

                // setting HNA, HP, HJA untuk barang berbonus
                $pembagi = $daftarJumlahItemBonus[$n] ? $daftarJumlahBeli[$n] + $daftarJumlahBonus[$n] : 1;

                $hpJaminan = $hargaPerolehan / $pembagi;
                $hjaJaminan = $hja / $pembagi;

                $dataDetailPenerimaan[$idKatalog] = [
                    "kode_reff" => $kode,
                    "id_katalog" => $idKatalog,
                    "id_reffkatalog" => $daftarIdRefKatalog[$n],
                    "kemasan" => $daftarKemasan[$n],
                    "id_pabrik" => $daftarIdPabrik[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$n]),
                    "id_kemasandepo" => $daftarIdKemasanDepo[$n],
                    "jumlah_item" => $jumlahItem,
                    "jumlah_kemasan" => $jumlahKemasan,
                    "harga_item" => $hargaItem,
                    "harga_kemasan" => $hargaKemasan,
                    "diskon_item" => $diskonItem,
                    "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$n]),
                    "hna_item" => $hna,
                    "hp_item" => $hpJaminan,
                    "hppb_item" => $hargaPerolehan,
                    "phja_item" => $phja,
                    "phjapb_item" => $phja,
                    "hja_item" => $hjaJaminan,
                    "hjapb_item" => $hja,
                    "userid_updt" => $idUserUpdate
                ];
                $n++;
            }

            $dataRincianDetailPenerimaan[$k++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_batch" => $daftarNoBatch[$i],
                "tgl_expired" => null,
                "no_urut" => $daftarNoUrut[$i],
                "jumlah_item" => $jumlahItem,
                "jumlah_kemasan" => $jumlahKemasan
            ];
            // set expired
            if ($daftarTanggalKadaluarsa[$k - 1]) {
                $dataRincianDetailPenerimaan[$k - 1]["tgl_expired"] = $toSystemDate($daftarTanggalKadaluarsa[$i]);
            }
        }

        $daftarField = array_keys($dataPenerimaan);
        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*)
                FROM db1.transaksif_penerimaan
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $jumlahPenerimaan = $connection->createCommand($sql, $params)->queryScalar();
            if (!$jumlahPenerimaan) throw new DataNotExistException($kode, $noDokumen, $transaction);

            $berhasilTambah = $fm->saveData("transaksif_penerimaan", $daftarField, $dataPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Penerimaan", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_penerimaanrinc", $dataRincianDetailPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Penerimaan", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_penerimaan", $dataDetailPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Penerimaan", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_penerimaan", $daftarField, $dataPenerimaan, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "kode", $kode, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_pembelian
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefPl];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Pembelian", "Kode", $kodeRefPl, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_pemesanan
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefPo];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Pemesanan", "Kode", $kodeRefPo, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_penerimaanrinc", $dataRincianDetailPenerimaan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Rincian Penerimaan", "Kode Ref", $kode, $transaction);

            $berhasilUbah = $fm->saveBatch("tdetailf_penerimaan", $dataDetailPenerimaan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Penerimaan", "Kode Ref", $kode, $transaction);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#editOthers    the original method
     */
    public function actionFormLainnyaData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                 AS kode,
                A.revisike             AS revisiKe,
                A.keterangan           AS keterangan,
                A.no_doc               AS noDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.tipe_doc             AS tipeDokumen,
                A.kode_refftrm         AS kodeRefTerima,
                A.kode_reffpl          AS kodeRefPl,
                A.kode_reffpo          AS kodeRefPo,
                A.kode_reffro          AS kodeRefRo,
                A.kode_reffrenc        AS kodeRefRencana,
                A.kode_reffkons        AS kodeRefKons,
                A.no_faktur            AS noFaktur,
                A.no_suratjalan        AS noSuratJalan,
                A.terimake             AS terimaKe,
                A.id_pbf               AS idPemasok,
                A.id_gudangpenyimpanan AS idGudangPenyimpanan,
                A.id_jenisanggaran     AS idJenisAnggaran,
                A.id_sumberdana        AS idSumberDana,
                A.id_subsumberdana     AS idSubsumberDana,
                A.id_carabayar         AS idCaraBayar,
                A.id_jenisharga        AS idJenisHarga,
                A.thn_anggaran         AS tahunAnggaran,
                A.blnawal_anggaran     AS bulanAwalAnggaran,
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                A.ppn                  AS ppn,
                A.nilai_total          AS nilaiTotal,
                A.nilai_diskon         AS nilaiDiskon,
                A.nilai_ppn            AS nilaiPpn,
                A.nilai_pembulatan     AS nilaiPembulatan,
                A.nilai_akhir          AS nilaiAkhir,
                A.sts_tabunggm         AS statusTabungGasMedis,
                A.sts_linked           AS statusLinked,
                A.sts_revisi           AS statusRevisi,
                A.sysdate_rev          AS sysdateRevisi,
                A.keterangan_rev       AS keteranganRevisi,
                A.sts_deleted          AS statusDeleted,
                A.sysdate_del          AS sysdateDeleted,
                A.sts_updatekartu      AS statusUpdateKartu,
                A.sts_izinrevisi       AS statusIzinRevisi,
                A.ver_tglizinrevisi    AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi    AS verUserIzinRevisi,
                A.ver_revterima        AS verRevTerima,
                A.ver_revtglterima     AS verRevTanggalTerima,
                A.ver_revusrterima     AS verRevUserTerima,
                A.ver_revisi           AS verRevisi,
                A.ver_tglrevisi        AS verTanggalRevisi,
                A.ver_usrrevisi        AS verUserRevisi,
                A.ver_terima           AS verTerima,
                A.ver_tglterima        AS verTanggalTerima,
                A.ver_usrterima        AS verUserTerima,
                A.ver_gudang           AS verGudang,
                A.ver_tglgudang        AS verTanggalGudang,
                A.ver_usrgudang        AS verUserGudang,
                A.ver_akuntansi        AS verAkuntansi,
                A.ver_tglakuntansi     AS verTanggalAkuntansi,
                A.ver_usrakuntansi     AS verUserAkuntansi,
                A.sts_testing          AS statusTesting,
                A.userid_in            AS useridInput,
                A.sysdate_in           AS sysdateInput,
                A.userid_updt          AS useridUpdate,
                A.sysdate_updt         AS sysdateUpdate,
                B.no_doc               AS noTerima,
                B.blnawal_anggaran     AS bulanAwalAnggaranTerima,
                B.blnakhir_anggaran    AS bulanAkhirAnggaranTerima,
                B.thn_anggaran         AS tahunAnggaranTerima,
                D.kode                 AS kodePemasok,
                D.nama_pbf             AS namaPemasok,
                E.subjenis_anggaran    AS subjenisAnggaran,
                G.jenis_harga          AS jenisHarga,
                IFNULL(UTRM.name, '-') AS namaUserTerima,
                IFNULL(UGDG.name, '-') AS namaUserGudang,
                IFNULL(UAKN.name, '-') AS namaUserAkuntansi,
                C.no_doc               AS noSpk,
                F.no_doc               AS noPo,
                C.blnawal_anggaran     AS bulanAwalAnggaranPl,
                C.blnakhir_anggaran    AS bulanAkhirAnggaranPl,
                C.thn_anggaran         AS tahunAnggaranPl,
                F.blnawal_anggaran     AS bulanAwalAnggaranPo,
                F.blnakhir_anggaran    AS bulanAkhirAnggaranPo,
                F.thn_anggaran         AS tahunAnggaranPo,
                A.sts_tabunggm         AS statusTabungGasMedis
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_refftrm = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.transaksif_pemesanan AS F ON A.kode_reffpo = F.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
                AND A.ver_gudang = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$penerimaan) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                   AS kodeRef,
                B.id_katalog                  AS idKatalog,
                B.kode_reffrenc               AS kodeRefRencana,
                B.kode_reffpo                 AS kodeRefPo,
                B.kode_reffro                 AS kodeRefRo,
                B.kode_reffpl                 AS kodeRefPl,
                B.kode_refftrm                AS kodeRefTerima,
                B.kode_reffkons               AS kodeRefKons,
                B.id_reffkatalog              AS idRefKatalog,
                B.kemasan                     AS kemasan,
                B.id_pabrik                   AS idPabrik,
                B.id_kemasan                  AS idKemasan,
                B.isi_kemasan                 AS isiKemasan,
                B.id_kemasandepo              AS idKemasanDepo,
                B.jumlah_item                 AS jumlahItem,
                B.jumlah_kemasan              AS jumlahKemasan,
                B.jumlah_itembonus            AS jumlahItemBonus,
                B.harga_item                  AS hargaItem,
                B.harga_kemasan               AS hargaKemasan,
                B.diskon_item                 AS diskonItem,
                B.diskon_harga                AS diskonHarga,
                B.hna_item                    AS hnaItem,
                B.hp_item                     AS hpItem,
                B.hppb_item                   AS hpPbItem,
                B.phja_item                   AS phjaItem,
                B.phjapb_item                 AS phjaPbItem,
                B.hja_item                    AS hjaItem,
                B.hjapb_item                  AS hjaPbItem,
                B.sts_revisiitem              AS statusRevisiItem,
                B.keterangan                  AS keterangan,
                B.userid_updt                 AS useridUpdate,
                B.sysdate_updt                AS sysdateUpdate,
                A.kode_reff                   AS kodeRef,
                A.id_katalog                  AS idKatalog,
                A.no_reffbatch                AS noRefBatch,
                A.no_batch                    AS noBatch,
                A.tgl_expired                 AS tanggalKadaluarsa,
                A.no_urut                     AS noUrut,
                A.jumlah_item                 AS jumlahItem,
                A.jumlah_kemasan              AS jumlahKemasan,
                A.id                          AS id,
                IFNULL(A.tgl_expired, '')     AS tanggalKadaluarsa,
                KAT.nama_sediaan              AS namaSediaan,
                KAT.jumlah_itembeli           AS jumlahItemBeli,
                KAT.jumlah_itembonus          AS jumlahItemBonus,
                PBK.nama_pabrik               AS namaPabrik,
                KSAR.kode                     AS satuanJual,
                KCIL.kode                     AS satuan,  -- satuan kecil
                IFNULL(C.jumlah_itembonus, 0) AS jumlahBonus,
                IFNULL(trm.jumlah_item, 0)    AS jumlahTerima,
                IFNULL(D.jumlah_item, 0)      AS jumlahPo,
                IFNULL(E.jumlah_item, 0)      AS jumlahPl
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS C ON B.kode_refftrm = C.kode_reff
            LEFT JOIN db1.tdetailf_pemesanan AS D ON B.kode_reffpo = D.kode_reff
            LEFT JOIN db1.tdetailf_pembelian AS E ON B.kode_reffpl = E.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS trm ON B.kode_refftrm = trm.kode_refftrm
            WHERE
                A.kode_reff = :kode
                AND B.id_katalog = trm.id_katalog
                AND B.id_katalog = E.id_katalog
                AND B.id_katalog = D.id_katalog
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kode" => $kode];
        $daftarRincianDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "heading_title" => "Edit Penerimaan Donasi/COD/Konsinyasi",
            "penerimaan" => $penerimaan,
            "idata" => $daftarRincianDetailPenerimaan,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addrevisi    the original method
     */
    public function actionSaveAddRevisiPl(): void
    {
        [   "kode" => $kode,
            "keterangan" => $keterangan,
            "ver_revterima" => $verRevisiTerima,
            "no_doc" => $noDokumen,
            "revisike" => $revisiKe,
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_jenisharga" => $idJenisHarga,
            "id_carabayar" => $idCaraBayar,
            "ppn" => $ppn,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "id_katalog" => $daftarIdKatalog,
            "harga_item" => $daftarHargaItem,
            "diskon_item" => $daftarDiskonItem,
            "jumlah_itembonus" => $daftarJumlahItemBonus,
            "no_batch" => $daftarNoBatch,
            "kemasan" => $daftarKemasan,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "harga_kemasan" => $daftarHargaKemasan,
            "no_urut" => $daftarNoUrut,
            "j_beli" => $daftarJumlahBeli,
            "j_bonus" => $daftarJumlahBonus,
            "tgl_expired" => $daftarTanggalKadaluarsa,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $ppn ??= 0;

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        // backup tdetailf_pembelian
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpenerimaanrinc
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpenerimaan
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_penerimaan AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Detail Revisi Penerimaan", $transaction);

        // backup transaksif_pembelian
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_revpenerimaan
            SELECT *
            FROM db1.transaksif_penerimaan
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Revisi Penerimaan", $transaction);

        // saat revisi, diset close sementara, dan update revisi ke
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_penerimaan AS A
            LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reffpl = B.kode
            SET
                A.revisike = A.revisike + 1,
                A.keterangan = :keterangan,
                A.ver_revisi = 0,
                A.ver_usrrevisi = NULL,
                A.ver_tglrevisi = NULL,
                A.userid_updt = :idUser,
                A.sts_revisi = 1,
                A.sysdate_rev = :tanggalRev,
                A.keterangan_rev = 'Sedang dilakukan Revisi. Lakukan verifikasi revisi untuk bisa menggunakan Dokumen ini.',
                A.sts_updatekartu = IF(B.id_pbf = A.id_pbf, 0, 1)
            WHERE
                A.kode = :kode
                AND A.sts_deleted = 0
        ";
        $params = [
            ":keterangan" => $keterangan,
            ":idUser" => $idUser,
            ":tanggalRev" => $nowValSystem,
            ":kode" => $kode,
        ];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kode, $transaction);

        // Untuk Cek Item
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff        AS kodeRef,
                A.id_katalog       AS idKatalog,        -- in use
                A.kode_reffrenc    AS kodeRefRencana,
                A.kode_reffpo      AS kodeRefPo,
                A.kode_reffro      AS kodeRefRo,
                A.kode_reffpl      AS kodeRefPl,
                A.kode_refftrm     AS kodeRefTerima,
                A.kode_reffkons    AS kodeRefKons,
                A.id_reffkatalog   AS idRefKatalog,
                A.kemasan          AS kemasan,
                A.id_pabrik        AS idPabrik,
                A.id_kemasan       AS idKemasan,
                A.isi_kemasan      AS isiKemasan,       -- in use
                A.id_kemasandepo   AS idKemasanDepo,
                A.jumlah_item      AS jumlahItem,
                A.jumlah_kemasan   AS jumlahKemasan,
                A.jumlah_itembonus AS jumlahItemBonus,
                A.harga_item       AS hargaItem,        -- in use
                A.harga_kemasan    AS hargaKemasan,     -- in use
                A.diskon_item      AS diskonItem,       -- in use
                A.diskon_harga     AS diskonHarga,
                A.hna_item         AS hnaItem,
                A.hp_item          AS hpItem,
                A.hppb_item        AS hpPbItem,
                A.phja_item        AS phjaItem,
                A.phjapb_item      AS phjaPbItem,
                A.hja_item         AS hjaItem,
                A.hjapb_item       AS hjaPbItem,
                A.sts_revisiitem   AS statusRevisiItem,
                A.keterangan       AS keterangan,
                A.userid_updt      AS useridUpdate,
                A.sysdate_updt     AS sysdateUpdate,
                B.nama_sediaan     AS namaSediaan,      -- in use
                T.ppn              AS ppn               -- in use
            FROM db1.tdetailf_penerimaan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            INNER JOIN db1.transaksif_penerimaan AS T ON A.kode_reff = T.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $tempDetailPenerimaan = [];
        $dataRincianDetailPenerimaan = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            if (!$daftarJumlahKemasan[$i]) continue;
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            $hargaItem = $toSystemNumber($daftarHargaItem[$i]);
            $diskonItem = $toSystemNumber($daftarDiskonItem[$i]);

            $hna = $ppn ? $hargaItem  :  $hargaItem / 1.1;
            $hnaPpn = $ppn ? $hargaItem * 1.1  :  $hargaItem;

            $diskonHarga = $hargaItem * $diskonItem / 100;
            $hargaSetelahDiskon = $hargaItem - $diskonHarga;
            $hargaPpn = $hargaSetelahDiskon * $ppn / 100;
            $hargaPerolehan = $hargaSetelahDiskon + $hargaPpn;

            // persentase hja
            $map = [
                [        0,     50_000, 28],
                [   50_000,    250_000, 26],
                [  250_000,    500_000, 21],
                [  500_000,  1_000_000, 16],
                [1_000_000,  5_000_000, 11],
                [5_000_000, 10_000_000,  9],
            ];

            $phja = 7;
            foreach ($map as [$batasBawah, $batasAtas, $angka]) {
                $phja = ($hnaPpn >= $batasBawah and $hnaPpn < $batasAtas) ? $angka : $phja;
            }

            $hja = $hnaPpn + ($hnaPpn * $phja / 100);

            // setting HNA, HP, HJA untuk barang berbonus
            $pembagi = $daftarJumlahItemBonus[$n]  ?  $daftarJumlahBeli[$n] + $daftarJumlahBonus[$n]  :  1;

            $hpJaminan = $hargaPerolehan / $pembagi;
            $hjaJaminan = $hja / $pembagi;

            if ($tempDetailPenerimaan[$idKatalog]) {
                $tempDetailPenerimaan[$idKatalog]["jumlah_item"] += $jumlahItem;
                $tempDetailPenerimaan[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;

            } else {
                $k = 0;
                $tempDetailPenerimaan[$idKatalog] = [
                    "kemasan" => $daftarKemasan[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$i]),
                    "id_kemasandepo" => $daftarIdKemasanDepo[$n],
                    "jumlah_item" => $jumlahItem,
                    "jumlah_kemasan" => $jumlahKemasan,
                    "harga_item" => $hargaItem,
                    "harga_kemasan" => $toSystemNumber($daftarHargaKemasan[$i]),
                    "diskon_item" => $diskonItem,
                    "diskon_harga" => $diskonHarga,
                    "hna_item" => $hna,
                    "hp_item" => $hpJaminan,
                    "hppb_item" => $hargaPerolehan,
                    "phja_item" => $phja,
                    "phjapb_item" => $phja,
                    "hja_item" => $hjaJaminan,
                    "hjapb_item" => $hja
                ];
                $n++;
            }

            $dataRincianDetailPenerimaan[$idKatalog][$k++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_batch" => $daftarNoBatch[$i],
                "tgl_expired" => null,
                "no_urut" => $daftarNoUrut[$i],
                "jumlah_item" => $jumlahItem,
                "jumlah_kemasan" => $jumlahKemasan
            ];
            // set expired
            if ($daftarTanggalKadaluarsa[$k - 1]) {
                $dataRincianDetailPenerimaan[$idKatalog][$k - 1]["tgl_expired"] = $toSystemDate($daftarTanggalKadaluarsa[$i]);
            }
        }

        $arrket = []; // untuk penanda jika nilai berubah
        foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
            $temp = $tempDetailPenerimaan[$dPenerimaan->idKatalog];
            $ket = [];
            $dataDetailPenerimaan["sts_revisiitem"] = 1;

            // perubahan isi kemasan
            $harga = false;
            if ($temp["isi_kemasan"] != $dPenerimaan->isiKemasan) {
                $harga = true;
                $ket[] = "isi {$dPenerimaan->isiKemasan} -> {$temp['isi_kemasan']}";
                $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                    "kemasan" => $temp["kemasan"],
                    "id_kemasan" => $temp["id_kemasan"],
                    "id_kemasandepo" => $temp["id_kemasandepo"],
                    "isi_kemasan" => $temp["isi_kemasan"],
                    "jumlah_kemasan" => $temp["jumlah_kemasan"],
                    "jumlah_item" => $temp["jumlah_item"],
                    "harga_kemasan" => $temp["harga_kemasan"],
                    "harga_item" => $temp["harga_item"],
                    "diskon_harga" => $temp["diskon_harga"],
                    "hna_item" => $temp["hna_item"],
                    "hp_item" => $temp["hp_item"],
                    "hppb_item" => $temp["hppb_item"],
                    "phja_item" => $temp["phja_item"],
                    "phjapb_item" => $temp["phjapb_item"],
                    "hja_item" => $temp["hja_item"],
                    "hjapb_item" => $temp["hjapb_item"],
                ]);
            }

            // perubahan harga
            if ($temp["harga_kemasan"] != $dPenerimaan->hargaKemasan || $temp["harga_item"] != $dPenerimaan->hargaItem) {
                $ket[] = "harga item {$dPenerimaan->hargaItem} -> {$temp['harga_item']}";
                $ket[] = "harga_kemasan {$dPenerimaan->hargaKemasan} -> {$temp['harga_kemasan']}";
                $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                    "harga_kemasan" => $temp["harga_kemasan"],
                    "harga_item" => $temp["harga_item"],
                    "diskon_harga" => $temp["diskon_harga"],
                ]);

                if ($harga == false) {
                    $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                        "harga_kemasan" => $temp["harga_kemasan"],
                        "hna_item" => $temp["hna_item"],
                        "hp_item" => $temp["hp_item"],
                        "hppb_item" => $temp["hppb_item"],
                        "phja_item" => $temp["phja_item"],
                        "phjapb_item" => $temp["phjapb_item"],
                        "hja_item" => $temp["hja_item"],
                        "hjapb_item" => $temp["hjapb_item"],
                    ]);
                }
                $harga = true;
            }

            // perubahan diskon
            if ($temp["diskon_item"] != $dPenerimaan->diskonItem) {
                $harga = true;
                $ket[] = "diskon {$dPenerimaan->diskonItem} -> {$temp['diskon_item']}";
                $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                    "diskon_item" => $temp["diskon_item"],
                    "diskon_harga" => $temp["diskon_harga"],
                ]);

                if ($harga == false) {
                    $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                        "hna_item" => $temp["hna_item"],
                        "hp_item" => $temp["hp_item"],
                        "hppb_item" => $temp["hppb_item"],
                        "phja_item" => $temp["phja_item"],
                        "phjapb_item" => $temp["phjapb_item"],
                        "hja_item" => $temp["hja_item"],
                        "hjapb_item" => $temp["hjapb_item"],
                    ]);
                }
            }

            // perubahan ppn
            if ($ppn != $dPenerimaan->ppn) {
                $ket[] = "ppn {$dPenerimaan->ppn} -> {$ppn}";
                if ($harga == false) {
                    $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                        "hna_item" => $temp["hna_item"],
                        "hp_item" => $temp["hp_item"],
                        "hppb_item" => $temp["hppb_item"],
                        "phja_item" => $temp["phja_item"],
                        "phjapb_item" => $temp["phjapb_item"],
                        "hja_item" => $temp["hja_item"],
                        "hjapb_item" => $temp["hjapb_item"],
                    ]);
                }
            }

            $fieldDetailPenerimaan = array_keys($dataDetailPenerimaan);
            $iwhere = ["kode_reff" => $kode, "id_katalog" => $dPenerimaan->idKatalog];
            $berhasilUbah = $fm->saveData("tdetailf_penerimaan", $fieldDetailPenerimaan, $dataDetailPenerimaan, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Penerimaan", "Kode Ref", $kode, $transaction);

            $berhasilUbah = $fm->saveBatch("tdetailf_penerimaanrinc", $dataRincianDetailPenerimaan[$dPenerimaan->idKatalog], $iwhere);

            if ($berhasilUbah) {
                $arrket[] = "{$dPenerimaan->namaSediaan}: " . implode(" | ", $ket);
            }
        }

        if ($arrket) {
            $keterangan = addslashes($keterangan . " || Perubahan Nilai: " . implode("|", $arrket));
        }

        // ==================untuk cek data
        $dataPenerimaan = [
            "id_pbf" => $idPemasok,
            "id_jenisanggaran" => $idJenisAnggaran,
            "id_sumberdana" => $idSumberDana,
            "id_jenisharga" => $idJenisHarga,
            "id_carabayar" => $idCaraBayar,
            "ppn" => $ppn,
            "keterangan" => $keterangan,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
        ];

        if ($verRevisiTerima == 1) {
            $dataPenerimaan = [
                ...$dataPenerimaan,
                "ver_revterima" => 1,
                "ver_revusrterima" => $idUser,
                "ver_revtglterima" => $nowValSystem,
            ];
        }

        // penyimpanan
        $daftarFieldPenerimaan = array_keys($dataPenerimaan);
        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_penerimaan", $daftarFieldPenerimaan, $dataPenerimaan, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "kode", $kode, $transaction);

        // Simpan Ke Notification
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.transaksi_notification (
                id_notif,                            -- 1
                id_fromSatker,                       -- 2
                id_toUser,                           -- 3
                tgl_notif,                           -- 4
                tipe_notif,                          -- 5
                kode_reff,                           -- 6
                nodoc_reff,                          -- 7
                modul_reff,                          -- 8
                info_reff,                           -- 9
                description_reff,                    -- 10
                point_ofview,                        -- 11
                verif_reff, modul_exec, action_exec, -- 12
                userid_in                            -- 13
            )
            SELECT
                IFNULL(MAX(id_notif), 0) + 1,   -- 1
                '0003',                         -- 2
                0,                              -- 3
                :tanggalNotifikasi,             -- 4
                'R',                            -- 5
                :kodeRef,                       -- 6
                :noDokumenRef,                  -- 7
                'penerimaan',                   -- 8
                :infoRef,                       -- 9
                :deskripsiRef,                  -- 10
                :pointOfView,                   -- 11
                1, 'verRevgudang', 'addrevisi', -- 12
                :idUser                         -- 13
            FROM db1.transaksi_notification
        ";
        $params = [
            ":tanggalNotifikasi" => $nowValSystem,
            ":kodeRef" => $kode,
            ":noDokumenRef" => $noDokumen,
            ":infoRef" => $revisiKe,
            ":deskripsiRef" => $keterangan,
            // TODO: php: truely missing var: $retval
            ":pointOfView" => "ULP - Revisi Penerimaan " . $retval[0]["no_doc"],
            ":idUser" => $idUser,
        ];

        if ($verRevisiTerima == 1) {
            // nothing to replace
        } else {
            $sql = str_replace("verif_reff, modul_exec, action_exec, -- 12", "", $sql);
            $sql = str_replace("1, 'verRevgudang', 'addrevisi', -- 12", "", $sql);
        }
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
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addrevisi    the original method
     */
    public function actionSaveAddRevisiDokumen(): void
    {
        [   "kode" => $kode,
            "keterangan" => $keterangan,
            "no_doc" => $noDokumen,
            "revisike" => $revisiKe,
            "ver_revterima" => $verRevisiTerima,
            "tgl_doc" => $tanggalDokumen,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $post = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        // backup tdetailf_pembelian
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpenerimaanrinc
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpenerimaan
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_penerimaan AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Detail Revisi Penerimaan", $transaction);

        // backup transaksif_pembelian
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_revpenerimaan
            SELECT *
            FROM db1.transaksif_penerimaan
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Revisi Penerimaan", $transaction);

        // saat revisi, diset close sementara, dan update revisi ke
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_penerimaan AS A
            SET
                A.revisike = A.revisike + 1,
                A.keterangan = :keterangan,
                A.ver_revisi = 0,
                A.ver_usrrevisi = NULL,
                A.ver_tglrevisi = NULL,
                A.ver_revterima = 0,
                A.ver_revusrterima = NULL,
                A.ver_revtglterima = NULL,
                A.userid_updt = :idUser,
                A.sts_revisi = 1,
                A.sts_updatekartu = IF(A.no_doc = :noDokumen, 0, 1),
                A.sysdate_rev = :tanggalRevisi,
                A.keterangan_rev = 'Sedang dilakukan Revisi. Lakukan verifikasi revisi untuk bisa menggunakan Dokumen ini.'
            WHERE
                A.kode = :kode
                AND A.sts_deleted = 0
        ";
        $params = [
            ":keterangan" => $keterangan,
            ":idUser" => $idUser,
            ":noDokumen" => $noDokumen,
            ":tanggalRevisi" => $nowValSystem,
            ":kode" => $kode
        ];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kode, $transaction);

        $post["tgl_doc"] = $toSystemDate($tanggalDokumen);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.no_doc            AS noDokumen,
                A.no_faktur         AS noFaktur,
                A.no_suratjalan     AS noSuratJalan,
                A.id_pbf            AS idPemasok,
                A.tgl_doc           AS tanggalDokumen,
                A.id_jenisanggaran  AS idJenisAnggaran,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.thn_anggaran      AS tahunAnggaran,
                A.id_sumberdana     AS idSumberDana,
                A.id_jenisharga     AS idJenisHarga,
                A.id_carabayar      AS idCaraBayar,
                A.keterangan        AS keterangan
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();

        $dataPenerimaan = [];
        foreach ($penerimaan as $key => $val) {
            if ($val != $post[$key] && $post[$key]) {
                $dataPenerimaan[$key] = $post[$key];
            }
        }

        // set Verifikasi Revisi
        if ($verRevisiTerima == 1) {
            $dataPenerimaan = [
                ...$dataPenerimaan,
                "ver_revterima" => 1,
                "ver_revusrterima" => $idUser,
                "ver_revtglterima" => $nowValSystem,
            ];
        }

        // save data, baik itu diverifikasi atau tidak, selama terjadi perubahan data, akan disimpan perubahannya dan notifikasinya
        if (!$dataPenerimaan) throw new \Exception("Invalid DB param");

        $dataPenerimaan["keterangan"] = $keterangan;

        $daftarField = array_keys($dataPenerimaan);
        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_penerimaan", $daftarField, $dataPenerimaan, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kode, $transaction);

        // TODO: php: truely missing var: $verif_reff
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksi_notification
            SET
                description_reff = :deskripsi
                $verif_reff
            WHERE
                tipe_notif = 'R'
                AND kode_reff = :kode
                AND modul_reff = 'pembelian'
                AND info_reff = :info
        ";
        $params = [":deskripsi" => $keterangan, ":kode" => $kode, ":info" => $revisiKe];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Notifikasi", "Kode Ref", $kode, $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addrevisi    the original method
     */
    public function actionSaveAddRevisiItem(): void
    {
        [   "kode" => $kode,
            "ppn" => $ppn,
            "nilai_total" => $nilaiTotal,
            "nilai_diskon" => $nilaiDiskon,
            "nilai_ppn" => $nilaiPpn,
            "nilai_pembulatan" => $nilaiPembulatan,
            "nilai_akhir" => $nilaiAkhir,
            "keterangan" => $keterangan,
            "no_doc" => $noDokumen,
            "ver_revterima" => $verRevisiTerima,
            "revisike" => $revisiKe,
            "id_katalog" => $daftarIdKatalog,
            "id_kemasan" => $daftarIdKemasan,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "jumlah_item" => $daftarJumlahItem,
            "diskon_item" => $daftarDiskonItem,
            "harga_item" => $daftarHargaItem,
            "harga_kemasan" => $daftarHargaKemasan,
            "kemasan" => $daftarKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "no_batch" => $daftarNoBatch,
            "no_urut" => $daftarNoUrut,
            "tgl_expired" => $daftarTanggalKadaluarsa,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $ppn ??= 0;

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        // backup tdetailf_pembelian
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpenerimaanrinc
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.tdetailf_revpenerimaan
            SELECT
                B.revisike,
                A.*
            FROM db1.tdetailf_penerimaan AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Detail Revisi Penerimaan", $transaction);

        // backup transaksif_pembelian
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_revpenerimaan
            SELECT *
            FROM db1.transaksif_penerimaan
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Revisi Penerimaan", $transaction);

        // saat revisi, diset close sementara, dan update revisi ke
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_penerimaan AS A
            SET
                A.revisike = A.revisike + 1,
                A.keterangan = :keterangan,
                A.ver_revisi = 0,
                A.ver_usrrevisi = NULL,
                A.ver_tglrevisi = NULL,
                A.userid_updt = :idUser,
                A.sts_revisi = 1,
                A.sysdate_rev = :tanggalRevisi,
                A.keterangan_rev = 'Sedang dilakukan Revisi. Lakukan verifikasi revisi untuk bisa menggunakan Dokumen ini.'
            WHERE
                A.kode = :kode
                AND A.sts_deleted = 0
        ";
        $params = [
            ":keterangan" => $keterangan,
            ":idUser" => $idUser,
            ":tanggalRevisi" => $nowValSystem,
            ":kode" => $kode
        ];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Penerimaan", $transaction);

        // Untuk Cek Item
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff        AS kodeRef,
                A.id_katalog       AS idKatalog,        -- in use
                A.kode_reffrenc    AS kodeRefRencana,
                A.kode_reffpo      AS kodeRefPo,
                A.kode_reffro      AS kodeRefRo,
                A.kode_reffpl      AS kodeRefPl,
                A.kode_refftrm     AS kodeRefTerima,
                A.kode_reffkons    AS kodeRefKons,
                A.id_reffkatalog   AS idRefKatalog,
                A.kemasan          AS kemasan,
                A.id_pabrik        AS idPabrik,
                A.id_kemasan       AS idKemasan,
                A.isi_kemasan      AS isiKemasan,       -- in use
                A.id_kemasandepo   AS idKemasanDepo,
                A.jumlah_item      AS jumlahItem,       -- in use
                A.jumlah_kemasan   AS jumlahKemasan,    -- in use
                A.jumlah_itembonus AS jumlahItemBonus,
                A.harga_item       AS hargaItem,        -- in use
                A.harga_kemasan    AS hargaKemasan,     -- in use
                A.diskon_item      AS diskonItem,       -- in use
                A.diskon_harga     AS diskonHarga,
                A.hna_item         AS hnaItem,
                A.hp_item          AS hpItem,
                A.hppb_item        AS hpPbItem,
                A.phja_item        AS phjaItem,
                A.phjapb_item      AS phjaPbItem,
                A.hja_item         AS hjaItem,
                A.hjapb_item       AS hjaPbItem,
                A.sts_revisiitem   AS statusRevisiItem,
                A.keterangan       AS keterangan,
                A.userid_updt      AS useridUpdate,
                A.sysdate_updt     AS sysdateUpdate,
                B.nama_sediaan     AS namaSediaan,      -- in use
                C.ppn              AS ppn
            FROM db1.tdetailf_penerimaan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.transaksif_penerimaan AS C ON A.kode_reff = C.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $tempDetailPenerimaan = [];
        $dataRincianDetailPenerimaan = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            if (!$daftarJumlahKemasan[$i]) continue;
            $isiKemasan = $toSystemNumber($daftarIsiKemasan[$i]);
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            $hargaKemasan = $toSystemNumber($daftarHargaKemasan[$i]);
            $hargaItem = $toSystemNumber($daftarHargaItem[$i]);
            $diskonItem = $toSystemNumber($daftarDiskonItem[$i]);

            $hna = $ppn ? $hargaItem  :  $hargaItem / 1.1;

            $diskonHarga = $hargaItem * $diskonItem / 100;
            $hargaSetelahDiskon = $hargaItem - $diskonHarga;
            $hargaPpn = $hargaSetelahDiskon * $ppn / 100;
            $hargaPerolehan = $hargaSetelahDiskon + $hargaPpn;

            // persentase hja
            $map = [
                [        0,     50_000, 28],
                [   50_000,    250_000, 26],
                [  250_000,    500_000, 21],
                [  500_000,  1_000_000, 16],
                [1_000_000,  5_000_000, 11],
                [5_000_000, 10_000_000,  9],
            ];

            $phja = 7;
            foreach ($map as [$batasBawah, $batasAtas, $angka]) {
                $phja =($hargaPerolehan >= $batasBawah and $hargaPerolehan < $batasAtas) ? $angka : $phja;
            }

            $hja = $hargaPerolehan + ($hargaPerolehan * $phja / 100);

            if ($tempDetailPenerimaan[$idKatalog]) {
                $tempDetailPenerimaan[$idKatalog]["jumlah_item"] += $jumlahItem;
                $tempDetailPenerimaan[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;

            } else {
                $k = 0;
                $tempDetailPenerimaan[$idKatalog] = [
                    "kemasan" => $daftarKemasan[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $isiKemasan,
                    "id_kemasandepo" => $daftarIdKemasanDepo[$n],
                    "jumlah_item" => $jumlahItem,
                    "jumlah_kemasan" => $jumlahKemasan,
                    "harga_item" => $hargaItem,
                    "harga_kemasan" => $hargaKemasan,
                    "diskon_item" => $diskonItem,
                    "diskon_harga" => $diskonHarga,
                    "hna_item" => $hna,
                    "hp_item" => $hargaPerolehan,
                    "hppb_item" => $hargaPerolehan,
                    "phja_item" => $phja,
                    "phjapb_item" => $phja,
                    "hja_item" => $hja,
                    "hjapb_item" => $hja
                ];
                $n++;
            }

            $dataRincianDetailPenerimaan[$idKatalog][$k++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_batch" => $daftarNoBatch[$i],
                "tgl_expired" => null,
                "no_urut" => $daftarNoUrut[$i],
                "jumlah_item" => $jumlahItem,
                "jumlah_kemasan" => $jumlahKemasan
            ];
            // set expired
            if ($daftarTanggalKadaluarsa[$k - 1]) {
                $dataRincianDetailPenerimaan[$idKatalog][$k - 1]["tgl_expired"] = $toSystemDate($daftarTanggalKadaluarsa[$i]);
            }
        }

        $update = false;
        $arrket = []; // untuk penanda jika nilai berubah
        if ($tempDetailPenerimaan) {
            foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
                if ($dPenerimaan->ppn != $ppn) {
                    $update = true; // jika ada perubahan ppn
                }
                $temp = $tempDetailPenerimaan[$dPenerimaan->idKatalog];
                $ket = [];
                $dataDetailPenerimaan["sts_revisiitem"] = 1;

                // perubahan isi kemasan
                $harga = false;
                if ($temp["isi_kemasan"] != $dPenerimaan->isiKemasan) {
                    $harga = true;
                    $ket[] = "isi {$dPenerimaan->isiKemasan} -> {$temp['isi_kemasan']}";
                    $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                        "kemasan" => $temp["kemasan"],
                        "id_kemasan" => $temp["id_kemasan"],
                        "id_kemasandepo" => $temp["id_kemasandepo"],
                        "isi_kemasan" => $temp["isi_kemasan"],
                        "jumlah_kemasan" => $temp["jumlah_kemasan"],
                        "jumlah_item" => $temp["jumlah_item"],
                        "harga_kemasan" => $temp["harga_kemasan"],
                        "harga_item" => $temp["harga_item"],
                        "diskon_harga" => $temp["diskon_harga"],
                        "hna_item" => $temp["hna_item"],
                        "hp_item" => $temp["hp_item"],
                        "hppb_item" => $temp["hppb_item"],
                        "phja_item" => $temp["phja_item"],
                        "phjapb_item" => $temp["phjapb_item"],
                        "hja_item" => $temp["hja_item"],
                        "hjapb_item" => $temp["hjapb_item"],
                    ]);
                }

                // perubahan harga
                if ($temp["harga_kemasan"] != $dPenerimaan->hargaKemasan || $temp["harga_item"] != $dPenerimaan->hargaItem) {
                    $ket[] = "harga item {$dPenerimaan->hargaItem} -> {$temp['harga_item']}";
                    $ket[] = "harga_kemasan {$dPenerimaan->hargaKemasan} -> {$temp['harga_kemasan']}";
                    $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                        "harga_item" => $temp["harga_item"],
                        "diskon_harga" => $temp["diskon_harga"],
                    ]);

                    if ($harga == false) {
                        $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                            "harga_kemasan" => $temp["harga_kemasan"],
                            "hna_item" => $temp["hna_item"],
                            "hp_item" => $temp["hp_item"],
                            "hppb_item" => $temp["hppb_item"],
                            "phja_item" => $temp["phja_item"],
                            "phjapb_item" => $temp["phjapb_item"],
                            "hja_item" => $temp["hja_item"],
                            "hjapb_item" => $temp["hjapb_item"],
                        ]);
                    }
                    $harga = true;
                }

                // perubahan diskon
                if ($temp["diskon_item"] != $dPenerimaan->diskonItem) {
                    $ket[] = "diskon {$dPenerimaan->diskonItem} -> {$dataDetailPenerimaan['diskon_item']}";
                    $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                        "diskon_item" => $temp["diskon_item"],
                        "diskon_harga" => $temp["diskon_harga"],
                    ]);

                    if ($harga == false) {
                        $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                            "hna_item" => $temp["hna_item"],
                            "hp_item" => $temp["hp_item"],
                            "hppb_item" => $temp["hppb_item"],
                            "phja_item" => $temp["phja_item"],
                            "phjapb_item" => $temp["phjapb_item"],
                            "hja_item" => $temp["hja_item"],
                            "hjapb_item" => $temp["hjapb_item"],
                        ]);
                    }
                }

                // perubahan jumlah
                if ($temp["jumlah_kemasan"] != $dPenerimaan->jumlahKemasan || $temp["jumlah_item"] != $dPenerimaan->jumlahItem) {
                    $ket[] = "Jumlah {$dPenerimaan->jumlahKemasan} -> {$temp['jumlah_kemasan']}";
                    $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                        "jumlah_kemasan" => $temp["jumlah_kemasan"],
                        "jumlah_item" => $temp["jumlah_item"],
                    ]);
                }

                if ($update == true) {
                    $dataDetailPenerimaan = array_merge($dataDetailPenerimaan, [
                        "hna_item" => $temp["hna_item"],
                        "hp_item" => $temp["hp_item"],
                        "hppb_item" => $temp["hppb_item"],
                        "phja_item" => $temp["phja_item"],
                        "phjapb_item" => $temp["phjapb_item"],
                        "hja_item" => $temp["hja_item"],
                        "hjapb_item" => $temp["hjapb_item"],
                    ]);
                }

                $fieldDetailPenerimaan = array_keys($dataDetailPenerimaan);
                $iwhere = ["kode_reff" => $kode, "id_katalog" => $dPenerimaan->idKatalog];
                $berhasilUbah = $fm->saveData("tdetailf_penerimaan", $fieldDetailPenerimaan, $dataDetailPenerimaan, $iwhere);
                if (!$berhasilUbah) throw new FailToUpdateException("Detail Penerimaan", "Kode Ref", $kode, $transaction);

                $berhasilUbah = $fm->saveBatch("tdetailf_penerimaanrinc", $dataRincianDetailPenerimaan[$dPenerimaan->idKatalog], $iwhere);

                if ($berhasilUbah) {
                    $update = true;
                    $arrket[] = $dPenerimaan->namaSediaan . ": " . implode(" | ", $ket);
                }
            }
        } // end of jika ada nilai item yang berubah

        if ($arrket) {
            $keterangan .= " || Perubahan Nilai: " . implode("|", $arrket);
        }

        $dataPenerimaan = [];
        if ($update == true) {
            // ==================untuk cek data
            $dataPenerimaan = [
                "ppn" => $ppn,
                "keterangan" => $keterangan,
                "nilai_total" => $toSystemNumber($nilaiTotal),
                "nilai_diskon" => $toSystemNumber($nilaiDiskon),
                "nilai_ppn" => $toSystemNumber($nilaiPpn),
                "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
                "nilai_akhir" => $toSystemNumber($nilaiAkhir),
            ];
        }

        if ($verRevisiTerima == 1) {
            $dataPenerimaan = [
                ...$dataPenerimaan,
                "ver_revterima" => 1,
                "ver_revusrterima" => $idUser,
                "ver_revtglterima" => $nowValSystem,
            ];
        }

        // penyimpanan
        $daftarField = array_keys($dataPenerimaan);
        $where = ["kode" => $kode];
        $berhasilUbah = $fm->saveData("transaksif_penerimaan", $daftarField, $dataPenerimaan, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kode, $transaction);

        // Simpan Ke Notification
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.transaksi_notification (
                id_notif,                            -- 1
                id_fromSatker,                       -- 2
                id_toUser,                           -- 3
                tgl_notif,                           -- 4
                tipe_notif,                          -- 5
                kode_reff,                           -- 6
                nodoc_reff,                          -- 7
                modul_reff,                          -- 8
                info_reff,                           -- 9
                description_reff,                    -- 10
                point_ofview,                        -- 11
                verif_reff, modul_exec, action_exec, -- 12
                userid_in                            -- 13
            )
            SELECT
                IFNULL(MAX(id_notif), 0) + 1,   -- 1
                '0003',                         -- 2
                0,                              -- 3
                :tanggalNotifikasi,             -- 4
                'R',                            -- 5
                :kodeRef,                       -- 6
                :noDokumenRef,                  -- 7
                'penerimaan',                   -- 8
                :infoRef,                       -- 9
                :deskripsiRef,                  -- 10
                :pointOfView,                   -- 11
                1, 'verRevgudang', 'addrevisi', -- 12
                :idUser                         -- 13
            FROM db1.transaksi_notification
        ";
        $params = [
            ":tanggalNotifikasi" => $nowValSystem,
            ":kodeRef" => $kode,
            ":noDokumenRef" => $noDokumen,
            ":infoRef" => $revisiKe,
            ":deskripsiRef" => $keterangan,
            // TODO: php: truely missing var: $retval
            ":pointOfView" => "ULP - Revisi Penerimaan " . $retval[0]["no_doc"],
            ":idUser" => $idUser,
        ];
        $sql = ($verRevisiTerima == 1) ? $sql : preg_replace("/^.+ -- 12 */m", "", $sql);

        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Notifikasi", $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#addrevisi    the original method
     */
    public function actionFormRevisiData(): string
    {
        ["kode" => $kode, "t" => $t] = Yii::$app->request->post();
        $map = [1 => "revisi_dokumen", 2 => "revisi_item", 3 => "revisi_pl"];
        $action = $map[$t ?? 3];
        $connection = Yii::$app->dbFatma;

        if ($action == "revisi_pl") {
            $sql = /** @lang SQL */ "
                -- FILE: ". __FILE__ ." 
                -- LINE: ". __LINE__ ." 
                SELECT
                    A.kode                                         AS kode,
                    A.revisike + 1                                 AS revisiKe,
                    A.no_doc                                       AS noDokumen,
                    A.no_faktur                                    AS noFaktur,
                    A.no_suratjalan                                AS noSuratJalan,
                    A.kode_reffpl                                  AS kodeRefPl,
                    A.kode_reffpo                                  AS kodeRefPo,
                    A.tgl_doc                                      AS tanggalDokumen,
                    A.tipe_doc                                     AS tipeDokumen,
                    A.blnawal_anggaran                             AS bulanAwalAnggaran,
                    A.blnakhir_anggaran                            AS bulanAkhirAnggaran,
                    A.thn_anggaran                                 AS tahunAnggaran,
                    A.id_gudangpenyimpanan                         AS idGudangPenyimpanan,
                    A.sts_tabunggm                                 AS statusTabungGasMedis,
                    A.ver_revterima                                AS verRevTerima,
                    A.ver_revtglterima                             AS verRevTanggalTerima,
                    A.ver_revisi                                   AS verRevisi,
                    A.ver_tglrevisi                                AS verTanggalRevisi,
                    B.no_doc                                       AS noPo,
                    IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo)     AS tanggalTempoKirim,
                    C.no_doc                                       AS noSpk,
                    IFNULL(B.id_pbf, C.id_pbf)                     AS idPemasok,
                    A.id_pbf                                       AS idPemasokOri,
                    A.id_jenisanggaran                             AS idJenisAnggaranOri,
                    IFNULL(B.id_jenisanggaran, C.id_jenisanggaran) AS idJenisAnggaran,
                    A.id_sumberdana                                AS idSumberDanaOri,
                    IFNULL(B.id_sumberdana, C.id_sumberdana)       AS idSumberDana,
                    A.id_jenisharga                                AS idJenisHargaOri,
                    IFNULL(B.id_jenisharga, C.id_jenisharga)       AS idJenisHarga,
                    A.id_carabayar                                 AS idCaraBayarOri,
                    IFNULL(B.id_carabayar, C.id_carabayar)         AS idCaraBayar,
                    A.ppn                                          AS ppnOri,
                    IFNULL(B.ppn, C.ppn)                           AS ppn,
                    B.ppn                                          AS ppnDo,
                    C.ppn                                          AS ppnPl,
                    D.kode                                         AS kodePemasok,
                    D.nama_pbf                                     AS namaPemasok,                                    
                    Da.nama_pbf                                    AS namaPemasokOri,
                    E.subjenis_anggaran                            AS subjenisAnggaran,
                    G.jenis_harga                                  AS jenisHarga,
                    H.sumber_dana                                  AS sumberDana,
                    I.cara_bayar                                   AS caraBayar,
                    IFNULL(UTRM.name, '-')                         AS namaUserTerima,
                    IFNULL(UGDG.name, '-')                         AS namaUserGudang,
                    A.sts_tabunggm                                 AS statusTabungGasMedis,                                 
                    IFNULL(B.ver_revisi, 0)                        AS verRevisiPo,
                    IFNULL(C.ver_revisi, 0)                        AS verRevisiPl
                FROM db1.transaksif_penerimaan AS A
                LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
                LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
                LEFT JOIN db1.masterf_pbf AS D ON D.id = IFNULL(B.id_pbf, C.id_pbf)
                LEFT JOIN db1.masterf_pbf AS Da ON Da.id = A.id_pbf
                LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
                LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
                LEFT JOIN db1.masterf_sumberdana AS H ON A.id_sumberdana = H.id
                LEFT JOIN db1.masterf_carabayar AS I ON A.id_carabayar = I.id
                LEFT JOIN db1.user AS UTRM ON A.ver_revusrterima = UTRM.id
                LEFT JOIN db1.user AS UGDG ON A.ver_usrrevisi = UGDG.id
                WHERE
                    A.kode = :kode
                    AND A.ver_gudang = 1
                    AND A.ver_akuntansi = 0
                    AND A.sts_revisi = 1
            ";
        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode                                     AS kode,
                    A.revisike                                 AS revisiKe,
                    A.keterangan                               AS keterangan,
                    A.no_doc                                   AS noDokumen,
                    A.tgl_doc                                  AS tanggalDokumen,
                    A.tipe_doc                                 AS tipeDokumen,         -- in use
                    A.kode_refftrm                             AS kodeRefTerima,
                    A.kode_reffpl                              AS kodeRefPl,           -- in use
                    A.kode_reffpo                              AS kodeRefPo,           -- in use
                    A.kode_reffro                              AS kodeRefRo,
                    A.kode_reffrenc                            AS kodeRefRencana,
                    A.kode_reffkons                            AS kodeRefKons,
                    A.no_faktur                                AS noFaktur,
                    A.no_suratjalan                            AS noSuratJalan,
                    A.terimake                                 AS terimaKe,
                    A.id_pbf                                   AS idPemasok,
                    A.id_gudangpenyimpanan                     AS idGudangPenyimpanan,
                    A.id_jenisanggaran                         AS idJenisAnggaran,
                    A.id_sumberdana                            AS idSumberDana,
                    A.id_subsumberdana                         AS idSubsumberDana,
                    A.id_carabayar                             AS idCaraBayar,
                    A.id_jenisharga                            AS idJenisHarga,
                    A.thn_anggaran                             AS tahunAnggaran,
                    A.blnawal_anggaran                         AS bulanAwalAnggaran,
                    A.blnakhir_anggaran                        AS bulanAkhirAnggaran,
                    A.ppn                                      AS ppn,
                    A.nilai_total                              AS nilaiTotal,
                    A.nilai_diskon                             AS nilaiDiskon,
                    A.nilai_ppn                                AS nilaiPpn,
                    A.nilai_pembulatan                         AS nilaiPembulatan,
                    A.nilai_akhir                              AS nilaiAkhir,
                    A.sts_tabunggm                             AS statusTabungGasMedis,
                    A.sts_linked                               AS statusLinked,
                    A.sts_revisi                               AS statusRevisi,
                    A.sysdate_rev                              AS sysdateRevisi,
                    A.keterangan_rev                           AS keteranganRevisi,
                    A.sts_deleted                              AS statusDeleted,
                    A.sysdate_del                              AS sysdateDeleted,
                    A.sts_updatekartu                          AS statusUpdateKartu,
                    A.sts_izinrevisi                           AS statusIzinRevisi,
                    A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                    A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                    A.ver_revterima                            AS verRevTerima,
                    A.ver_revtglterima                         AS verRevTanggalTerima,
                    A.ver_revusrterima                         AS verRevUserTerima,
                    A.ver_revisi                               AS verRevisi,
                    A.ver_tglrevisi                            AS verTanggalRevisi,
                    A.ver_usrrevisi                            AS verUserRevisi,
                    A.ver_terima                               AS verTerima,
                    A.ver_tglterima                            AS verTanggalTerima,
                    A.ver_usrterima                            AS verUserTerima,
                    A.ver_gudang                               AS verGudang,
                    A.ver_tglgudang                            AS verTanggalGudang,
                    A.ver_usrgudang                            AS verUserGudang,
                    A.ver_akuntansi                            AS verAkuntansi,
                    A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                    A.ver_usrakuntansi                         AS verUserAkuntansi,
                    A.sts_testing                              AS statusTesting,
                    A.userid_in                                AS useridInput,
                    A.sysdate_in                               AS sysdateInput,
                    A.userid_updt                              AS useridUpdate,
                    A.sysdate_updt                             AS sysdateUpdate,
                    A.revisike + 1                             AS revisiKe,
                    B.no_doc                                   AS noPo,
                    IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim,
                    C.no_doc                                   AS noSpk,
                    D.kode                                     AS kodePemasok,
                    D.nama_pbf                                 AS namaPemasok,
                    E.subjenis_anggaran                        AS subjenisAnggaran,
                    G.jenis_harga                              AS jenisHarga,
                    H.sumber_dana                              AS sumberDana,
                    I.cara_bayar                               AS caraBayar,
                    IFNULL(UTRM.name, '-')                     AS namaUserTerima,
                    IFNULL(UGDG.name, '-')                     AS namaUserGudang,
                    A.sts_tabunggm                             AS statusTabungGasMedis,
                    IFNULL(B.ver_revisi, 0)                    AS verRevisiPo,          -- in use
                    IFNULL(C.ver_revisi, 0)                    AS verRevisiPl,          -- in use
                    Dp.namaDepo                                AS gudangSimpan,
                    A.ppn                                      AS ppnOri,
                    IFNULL(B.ppn, C.ppn)                       AS ppn,
                    B.ppn                                      AS ppnDo,
                    C.ppn                                      AS ppnPl
                FROM db1.transaksif_penerimaan AS A
                LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
                LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
                LEFT JOIN db1.masterf_pbf AS D ON D.id = A.id_pbf
                LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
                LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
                LEFT JOIN db1.masterf_sumberdana AS H ON A.id_sumberdana = H.id
                LEFT JOIN db1.masterf_carabayar AS I ON A.id_carabayar = I.id
                LEFT JOIN db1.masterf_depo AS Dp ON A.id_gudangpenyimpanan = Dp.id
                LEFT JOIN db1.user AS UTRM ON A.ver_revusrterima = UTRM.id
                LEFT JOIN db1.user AS UGDG ON A.ver_usrrevisi = UGDG.id
                WHERE
                    A.kode = :kode
                    AND A.ver_gudang = 1
                    AND A.ver_akuntansi = 0
                    AND A.sts_revisi = 1
                LIMIT 1
            ";
        }
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$penerimaan) throw new DataNotExistException($kode);

        if ($action == "revisi_pl" && $penerimaan->tipeDokumen == "0" && $penerimaan->verRevisiPo == 0 && $penerimaan->kodeRefPo) {
            throw new Exception("Revisi Penerimaan tidak bisa dilakukan karna DO/PO Referensi belum di verifikasi revisi");

        } elseif ($action == "revisi_pl" && $penerimaan->tipeDokumen == "0" && $penerimaan->verRevisiPl == 0 && $penerimaan->kodeRefPl) {
            throw new Exception("Revisi Penerimaan tidak bisa dilakukan karna SP/SPK/Kontrak Referensi belum di verifikasi revisi");
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                                                                           AS kodeRef,
                B.id_katalog                                                                          AS idKatalog,
                B.kode_reffrenc                                                                       AS kodeRefRencana,
                B.kode_reffpo                                                                         AS kodeRefPo,
                B.kode_reffro                                                                         AS kodeRefRo,
                B.kode_reffpl                                                                         AS kodeRefPl,
                B.kode_refftrm                                                                        AS kodeRefTerima,
                B.kode_reffkons                                                                       AS kodeRefKons,
                B.id_reffkatalog                                                                      AS idRefKatalog,
                B.kemasan                                                                             AS kemasan,
                B.id_pabrik                                                                           AS idPabrik,
                B.id_kemasan                                                                          AS idKemasan,
                B.isi_kemasan                                                                         AS isiKemasan,
                B.id_kemasandepo                                                                      AS idKemasanDepo,
                B.jumlah_item                                                                         AS jumlahItem,
                B.jumlah_kemasan                                                                      AS jumlahKemasan,
                B.jumlah_itembonus                                                                    AS jumlahItemBonus,
                B.harga_item                                                                          AS hargaItem,
                B.harga_kemasan                                                                       AS hargaKemasan,
                B.diskon_item                                                                         AS diskonItem,
                B.diskon_harga                                                                        AS diskonHarga,
                B.hna_item                                                                            AS hnaItem,
                B.hp_item                                                                             AS hpItem,
                B.hppb_item                                                                           AS hpPbItem,
                B.phja_item                                                                           AS phjaItem,
                B.phjapb_item                                                                         AS phjaPbItem,
                B.hja_item                                                                            AS hjaItem,
                B.hjapb_item                                                                          AS hjaPbItem,
                B.sts_revisiitem                                                                      AS statusRevisiItem,
                B.keterangan                                                                          AS keterangan,
                B.userid_updt                                                                         AS useridUpdate,
                B.sysdate_updt                                                                        AS sysdateUpdate,
                A.kode_reff                                                                           AS kodeRef,
                A.id_katalog                                                                          AS idKatalog,
                A.no_reffbatch                                                                        AS noRefBatch,
                A.no_batch                                                                            AS noBatch,
                IFNULL(A.tgl_expired, '')                                                             AS tanggalKadaluarsa,
                A.no_urut                                                                             AS noUrut,
                A.jumlah_item                                                                         AS jumlahItem,
                A.jumlah_kemasan                                                                      AS jumlahKemasan,
                A.id                                                                                  AS id,
                K.nama_sediaan                                                                        AS namaSediaan,
                PBK.nama_pabrik                                                                       AS namaPabrik,
                KSAR.kode                                                                             AS satuanJual,
                KCIL.kode                                                                             AS satuan,  -- satuan kecil
                K1.kode                                                                               AS satuanJualKat,
                K2.kode                                                                               AS satuanKat,
                K.kemasan                                                                             AS kemasanKat,
                K.isi_kemasan                                                                         AS isiKemasanKat,
                K.id_kemasanbesar                                                                     AS idKemasanKat,
                K.id_kemasankecil                                                                     AS idKemasanDepoKat,
                K.harga_beli                                                                          AS hargaItemKat,
                K.harga_beli * K.isi_kemasan                                                          AS hargaKemasanKat,
                IFNULL(tR.jumlah_item, 0)                                                             AS jumlahRencana,
                IFNULL(tH.jumlah_item, 0)                                                             AS jumlahHps,
                C.jumlah_item                                                                         AS jumlahPl,
                IFNULL(tRo.jumlah_item, 0)                                                            AS jumlahRo,
                IFNULL(D.jumlah_item, 0)                                                              AS jumlahDo,
                IFNULL(T_pl.jumlah_item, 0)                                                           AS jumlahTerimaPl,
                IFNULL(T_po.jumlah_item, 0)                                                           AS jumlahTerimaPo,
                IF(IFNULL(B.kode_reffpo, 0) = 0, IFNULL(T_pl.jumlah_item, 0), T_po.jumlah_item)       AS jumlahTerima,
                IF(IFNULL(Rt_po.kode_reffpo, 0) = 0, IFNULL(Rt_pl.jumlah_item, 0), Rt_po.jumlah_item) AS jumlahRetur
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = B.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS K1 ON K1.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS K2 ON K2.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS C ON B.kode_reffpl = C.kode_reff
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON C.id_reffkatalog = tH.id_reffkatalog
            LEFT JOIN db1.tdetailf_perencanaan AS tR ON C.id_reffkatalog = tR.id_katalog
            LEFT JOIN db1.tdetailf_pemesanan AS D ON B.kode_reffpo = D.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS tRo ON B.id_katalog = tRo.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS T_po ON D.kode_reff = T_po.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS T_pl ON C.kode_reff = T_pl.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Rt_po ON D.kode_reff = Rt_po.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS Rt_pl ON C.kode_reff = Rt_pl.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND B.id_katalog = Rt_pl.id_katalog
                AND B.id_katalog = Rt_po.id_katalog
                AND B.id_katalog = T_pl.id_katalog
                AND B.id_katalog = T_po.id_katalog
                AND B.kode_reffro = tRo.kode_reff
                AND B.id_katalog = D.id_katalog
                AND C.kode_reffrenc = tR.kode_reff
                AND C.kode_reffhps = tH.kode_reff
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
        ";
        $params = [":kode" => $kode];
        $daftarRincianDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        // Jika Penerimaan bukan dari Tarik data dari SP/SPK/Kontrak
        if ($penerimaan->tipeDokumen != 0) {
            // view: AddRevisiOthers
            return json_encode([
                "action" => $action,
                "judulHeading" => ($action == "revisi_dokumen") ? "Revisi Dokumen Penerimaan" : "Revisi Jumlah/Harga/Diskon/PPN Penerimaan",
                "penerimaan2" => $penerimaan,
                "daftarRincianDetailPenerimaan" => $daftarRincianDetailPenerimaan,
            ]);

        } else {
            if ($penerimaan->kodeRefPo) {
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
                        A.isi_kemasan      AS isiKemasan,
                        A.id_kemasandepo   AS idKemasanDepo,
                        A.jumlah_item      AS jumlahItem,
                        A.jumlah_kemasan   AS jumlahKemasan,
                        A.jumlah_realisasi AS jumlahRealisasi,
                        A.harga_item       AS hargaItem,
                        A.harga_kemasan    AS hargaKemasan,
                        A.diskon_item      AS diskonItem,
                        A.diskon_harga     AS diskonHarga,
                        A.keterangan       AS keterangan,
                        A.userid_updt      AS useridUpdate,
                        A.sysdate_updt     AS sysdateUpdate,
                        Sj.kode            AS satuanJual,
                        S.kode             AS satuan
                    FROM db1.tdetailf_pemesanan AS A
                    LEFT JOIN db1.masterf_kemasan AS Sj ON A.id_kemasan = Sj.id
                    LEFT JOIN db1.masterf_kemasan AS S ON A.id_kemasandepo = S.id
                    WHERE A.kode_reff = :kodeRef
                ";
                $params = [":kodeRef" => $penerimaan->kodeRefPo];

            } else {
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
                $params = [":kodeRef" => $penerimaan->kodeRefPl];
            }
            $daftarDetailX = $connection->createCommand($sql, $params)->queryAll();

            $daftarDetailX2 = [];
            foreach ($daftarDetailX as $d) {
                $daftarDetailX2[$d->idKatalog] = $d;
            }

            // view: AddRevisi
            return json_encode([
                "penerimaan2" => $penerimaan,
                "daftarRincianDetailPenerimaan" => $daftarRincianDetailPenerimaan,
                "daftarDetailX2" => $daftarDetailX2,
                "action" => $action,
            ]);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#views    the original method
     */
    public function actionViewData(): string
    {
        assert($_POST["kode"], new MissingPostParamException("kode"));
        ["kode" => $kode, "d" => $d] = Yii::$app->request->post();
        ["revisike" => $revisiKe] = Yii::$app->request->get();

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.blnakhir_anggaran                        AS bulanAkhirAnggaran, -- all
                A.blnawal_anggaran                         AS bulanAwalAnggaran, -- all
                A.id_pbf                                   AS idPemasok, -- in use
                A.keterangan                               AS keterangan, -- all
                A.kode                                     AS kodeTransaksi, -- all
                A.nilai_pembulatan                         AS nilaiPembulatan, -- all
                A.no_doc                                   AS noDokumenPenerimaan, -- all
                A.no_faktur                                AS noFaktur, -- all
                A.no_suratjalan                            AS noSuratJalan, -- all
                A.ppn                                      AS ppn, -- all
                A.sts_revisi                               AS statusRevisi, -- View
                A.tgl_doc                                  AS tanggalDokumenPenerimaan, -- all
                A.thn_anggaran                             AS tahunAnggaran, -- all
                A.tipe_doc                                 AS tipeDokumen, -- ViewOthers, ViewRinciOthers, in use
                A.ver_akuntansi                            AS verifikasiAkuntansi, -- all
                A.ver_gudang                               AS verifikasiGudang, -- all
                A.ver_terima                               AS verifikasiTerima, -- all
                A.ver_tglakuntansi                         AS verTanggalAkuntansi, -- all
                A.ver_tglgudang                            AS verTanggalGudang, -- all
                A.ver_tglterima                            AS verTanggalTerima, -- all
                B.subjenis_anggaran                        AS subjenisAnggaran, -- all
                C.sumber_dana                              AS sumberDana, -- all
                D.subsumber_dana                           AS subsumberDana, -- all
                E.jenis_harga                              AS jenisHarga, -- all
                F.cara_bayar                               AS caraBayar, -- all
                I.no_doc                                   AS noDokumenPerencanaan, -- View, ViewRinci
                IFNULL(A.kode_reffkons, 0)                 AS kodeRefKonsinyasi, -- ViewOthers, ViewRinciOthers
                IFNULL(G.no_doc, '-')                      AS noSpk, -- all
                IFNULL(H.nama_pbf, '')                     AS namaPemasok, -- all
                IFNULL(J.no_doc, '-')                      AS noPo, -- all
                IFNULL(J.tgl_tempokirim, G.tgl_jatuhtempo) AS tanggalTempoKirim, -- View, ViewRinci
                K.namaDepo                                 AS gudang, -- all
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi, -- all
                IFNULL(UGDG.name, '-')                     AS namaUserGudang, -- all
                IFNULL(UTRM.name, '-')                     AS namaUserTerima, -- all
                A.keterangan_rev                           AS keteranganRevisi, -- View
                A.revisike                                 AS revisiPenerimaanKe, -- View
                A.ver_revisi                               AS verRevisi, -- View
                A.ver_revterima                            AS verRevTerima -- View
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.transaksif_pembelian AS G ON A.kode_reffpl = G.kode
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.transaksif_perencanaan AS I ON A.kode_reffrenc = I.kode
            LEFT JOIN db1.transaksif_pemesanan AS J ON A.kode_reffpo = J.kode
            LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
            LEFT JOIN db1.transaksif_penerimaan AS L ON A.kode_refftrm = L.kode
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND (:revisiKe = '' OR A.revisike = :revisiKe)
            LIMIT 1
        ";
        $params = [":kode" => $kode, ":revisiKe" => $revisiKe];
        if ($revisiKe) {
            $sql = str_replace("db1.transaksif_penerimaan", "FROM rsupf_revisi.transaksif_revpenerimaan", $sql);
        }
        $xPenerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$xPenerimaan) throw new DataNotExistException($kode);

        if ($revisiKe) {
            $itablename = "tdetailf_revpenerimaan";
            $itablenamerinc = "tdetailf_revpenerimaanrinc";
        } else {
            $itablename = "tdetailf_penerimaan";
            $itablenamerinc = "tdetailf_penerimaanrinc";
        }

        if ($d == 1) {
            $kolomTambahan = "
                B.no_batch    AS noBatch,           -- ViewRinci, ViewRinciOthers
                B.tgl_expired AS tanggalKadaluarsa, -- ViewRinci, ViewRinciOthers
                B.no_urut     AS noUrut,            -- ViewRinci, ViewRinciOthers
                B.jumlah_item AS jumlahItem         -- ViewRinci, ViewRinciOthers
            ";
            $joinTambahan = "LEFT JOIN $itablenamerinc AS B ON A.kode_reff = B.kode_reff AND A.id_katalog = B.id_katalog";
            $orderTambahan = ", B.no_urut";

        } else {
            $kolomTambahan = "";
            $joinTambahan = "";
            $orderTambahan = "";
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog                AS idKatalog, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                A.kemasan                   AS kemasan, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                A.isi_kemasan               AS isiKemasan, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                A.jumlah_kemasan            AS jumlahKemasan, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                A.jumlah_itembonus          AS jumlahItemBonus, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                A.harga_item                AS hargaItem, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                A.harga_kemasan             AS hargaKemasan, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                A.diskon_item               AS diskonItem, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                IFNULL(
                    IF(trmpo.jumlah_item = 0, trmpl.jumlah_item, trmpo.jumlah_item),
                    trmpl.jumlah_item
                )                           AS jumlahTerima, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                IFNULL(pl.jumlah_item, 0)   AS jumlahPl, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                IFNULL(po.jumlah_item, 0)   AS jumlahPo, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                IFNULL(renc.jumlah_item, 0) AS jumlahRencana, -- View, ViewRinci
                KAT.jumlah_itembeli         AS jumlahItemBeli, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                KAT.nama_sediaan            AS namaSediaan, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                PBK.nama_pabrik             AS namaPabrik, -- ViewOthers, ViewRinciOthers, View, ViewRinci
                $kolomTambahan
            FROM db1.tdetailf_revpenerimaan AS A                                              -- hardcoded to prevent sql error
            LEFT JOIN db1.tdetailf_revpenerimaan AS FAKE ON FAKE.id_katalog = A.id_katalog    -- hardcoded to prevent sql error
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS pl ON A.kode_reffpl = pl.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS renc ON A.kode_reffrenc = renc.kode_reff
            LEFT JOIN db1.tdetailf_pemesanan AS po ON A.kode_reffpo = po.kode_reff
            LEFT JOIN db1.transaksif_penerimaan AS T ON A.kode_reff = T.kode
            LEFT JOIN (
                SELECT
                    A.kode_reffpo                 AS kode_reffpo,
                    A.id_katalog                  AS id_katalog,
                    IFNULL(SUM(A.jumlah_item), 0) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND (:tipeDokumen != 1 OR B.tipe_doc = 1)
                GROUP BY
                    A.kode_reffpo,
                    A.id_katalog
            ) AS trmpo ON po.kode_reff = trmpo.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpl                 AS kode_reffpl,
                    A.id_katalog                  AS id_katalog,
                    IFNULL(SUM(A.jumlah_item), 0) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND (:tipeDokumen != 1 OR B.tipe_doc = 1)
                GROUP BY
                    A.kode_reffpl,
                    A.id_katalog
            ) AS trmpl ON pl.kode_reff = trmpl.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND (:revisiKe = '' OR A.revisike = :revisiKe)
                AND A.id_katalog = trmpl.id_katalog
                AND A.id_katalog = trmpo.id_katalog
                AND A.id_katalog = po.id_katalog
                AND A.id_katalog = renc.id_katalog
                AND A.id_katalog = pl.id_katalog
            ORDER BY
                nama_sediaan
                $orderTambahan
        ";
        $sql = str_replace("tdetailf_penerimaan", $itablename, $sql);
        $sql = str_replace("LEFT JOIN db1.tdetailf_revpenerimaan AS FAKE ON FAKE.id_katalog = A.id_katalog", $joinTambahan, $sql);
        $params = [":kode" => $kode, ":revisiKe" => $revisiKe, ":tipeDokumen" => $xPenerimaan->tipeDokumen];
        $daftarRevisiDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.no_doc               AS noDokumen, -- ViewOthers, View
                A.tgl_doc              AS tanggalDokumen, -- ViewOthers, View
                A.nilai_akhir          AS nilaiAkhir, -- ViewOthers, View
                A.ver_terima           AS verTerima, -- ViewOthers, View
                A.ver_gudang           AS verGudang, -- ViewOthers, View
                D.nama_pbf             AS namaPemasok, -- ViewOthers, View
                E.subjenis_anggaran    AS subjenisAnggaran, -- ViewOthers, View
                B.no_doc               AS noTerima, -- ViewOthers, View
                C.no_doc               AS noSpk, -- ViewOthers, View
                F.no_doc               AS noPo -- ViewOthers, View
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_refftrm = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.transaksif_pemesanan AS F ON A.kode_reffpo = F.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            WHERE
                A.id_pbf = :idPemasok
                AND A.sts_deleted = 0
                AND A.ver_akuntansi = 0
                AND A.tipe_doc = 0
        ";
        $params = [":idPemasok" => $xPenerimaan->idPemasok];
        $daftarRetur = $connection->createCommand($sql, $params)->queryAll();

        $xPenerimaan->daftarDetailPenerimaan = $daftarRevisiDetailPenerimaan;
        $xPenerimaan->daftarRetur = $daftarRetur;
        return json_encode($xPenerimaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @throws LogicBranchException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#prints    the original method
     */
    public function actionPrint(): string
    {
        ["kode" => $kode, "versi" => $versi] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.blnakhir_anggaran                        AS bulanAkhirAnggaran,
                A.blnawal_anggaran                         AS bulanAwalAnggaran,
                A.id_pbf                                   AS idPemasok,
                A.keterangan                               AS keterangan,
                A.kode                                     AS kode,
                A.nilai_akhir                              AS nilaiAkhir,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_total                              AS nilaiTotal,
                A.no_doc                                   AS noDokumen,
                A.no_faktur                                AS noFaktur,
                A.no_suratjalan                            AS noSuratJalan,
                A.ppn                                      AS ppn,
                A.sts_deleted                              AS statusDeleted,
                A.sts_linked                               AS statusLinked,
                A.sts_revisi                               AS statusRevisi,
                A.sysdate_del                              AS sysdateDeleted,
                A.sysdate_updt                             AS sysdateUpdate,
                A.tgl_doc                                  AS tanggalDokumen,
                A.thn_anggaran                             AS tahunAnggaran,
                A.tipe_doc                                 AS tipeDokumen,         -- in use
                A.userid_updt                              AS useridUpdate,
                A.ver_akuntansi                            AS verAkuntansi,        -- in use
                A.ver_gudang                               AS verGudang,           -- in use
                A.ver_terima                               AS verTerima,           -- in use
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_tglterima                            AS verTanggalTerima,
                B.subjenis_anggaran                        AS subjenisAnggaran,
                C.sumber_dana                              AS sumberDana,
                D.subsumber_dana                           AS subsumberDana,
                E.jenis_harga                              AS jenisHarga,
                F.cara_bayar                               AS caraBayar,
                G.tgl_doc                                  AS tanggalMulai,
                I.no_doc                                   AS noRencana,
                IFNULL(G.no_doc, '-')                      AS noSpk,
                IFNULL(H.nama_pbf, '')                     AS namaPemasok,
                IFNULL(J.no_doc, '-')                      AS noPo,
                IFNULL(J.tgl_doc, '0000-00-00')            AS tanggalMulai,
                IFNULL(J.tgl_tempokirim, G.tgl_jatuhtempo) AS tanggalTempoKirim,
                K.namaDepo                                 AS gudang,
                L.no_doc                                   AS noTerima,
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi,   -- in use
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,      -- in use
                IFNULL(UTRM.name, '-')                     AS namaUserTerima       -- in use
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.transaksif_pembelian AS G ON A.kode_reffpl = G.kode
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.transaksif_perencanaan AS I ON A.kode_reffrenc = I.kode
            LEFT JOIN db1.transaksif_pemesanan AS J ON A.kode_reffpo = J.kode
            LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
            LEFT JOIN db1.transaksif_penerimaan AS L ON A.kode_refftrm = L.kode
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$penerimaan) throw new DataNotExistException($kode);

        // TODO: php: uncategorized: move logic to view
        $penerimaan2 = $penerimaan->getArrayCopy();
        $penerimaan2["checkedppn"] = ($penerimaan->ppn == 10) ? "checked" : "";

        if ($penerimaan->verTerima == 1) {
            $penerimaan2["verterima"] = "checked";
        } else {
            $penerimaan2["verterima"] = "";
            $penerimaan->namaUserTerima = "------";
        }

        if ($penerimaan->verGudang == 1) {
            $penerimaan2["vergudang"] = "checked";
            $penerimaan2["stokgudang"] = "checked";
        } else {
            $penerimaan2["vergudang"] = "";
            $penerimaan2["stokgudang"] = "";
            $penerimaan->namaUserGudang = "------";
        }

        if ($penerimaan->verAkuntansi == 1) {
            $penerimaan2["verakuntansi"] = "checked";
        } else {
            $penerimaan2["verakuntansi"] = "";
            $penerimaan->namaUserAkuntansi = "------";
        }

        if ($versi == 6) {
            $kolomTambahan = "
                B.no_batch       AS noBatch,
                B.tgl_expired    AS tanggalKadaluarsa,
                B.no_urut        AS noUrut,
                B.jumlah_item    AS jumlahItem,
                B.jumlah_kemasan AS jumlahKemasan
            ";
            $joinTambahan = "LEFT JOIN db1.tdetailf_penerimaanrinc AS B ON A.kode_reff = B.kode_reff AND A.id_katalog = B.id_katalog";
            $orderTambahan = ", B.no_urut";

        } else {
            $kolomTambahan = "
                'not-used' as notUsed
            ";
            $joinTambahan = "";
            $orderTambahan = "";
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                                              AS kodeRef,
                A.id_katalog                                             AS idKatalog,
                A.kode_reffrenc                                          AS kodeRefRencana,
                A.kode_reffpo                                            AS kodeRefPo,
                A.kode_reffro                                            AS kodeRefRo,
                A.kode_reffpl                                            AS kodeRefPl,
                A.kode_refftrm                                           AS kodeRefTerima,
                A.kode_reffkons                                          AS kodeRefKons,
                A.id_reffkatalog                                         AS idRefKatalog,
                A.kemasan                                                AS kemasan,
                A.id_pabrik                                              AS idPabrik,
                A.id_kemasan                                             AS idKemasan,
                A.isi_kemasan                                            AS isiKemasan,
                A.id_kemasandepo                                         AS idKemasanDepo,
                A.jumlah_item                                            AS jumlahItem,
                A.jumlah_kemasan                                         AS jumlahKemasan,     -- in use
                A.jumlah_itembonus                                       AS jumlahItemBonus,
                A.harga_item                                             AS hargaItem,
                A.harga_kemasan                                          AS hargaKemasan,      -- in use
                A.diskon_item                                            AS diskonItem,        -- in use
                A.diskon_harga                                           AS diskonHarga,
                A.hna_item                                               AS hnaItem,
                A.hp_item                                                AS hpItem,
                A.hppb_item                                              AS hpPbItem,
                A.phja_item                                              AS phjaItem,
                A.phjapb_item                                            AS phjaPbItem,
                A.hja_item                                               AS hjaItem,
                A.hjapb_item                                             AS hjaPbItem,
                A.sts_revisiitem                                         AS statusRevisiItem,
                A.keterangan                                             AS keterangan,
                A.userid_updt                                            AS useridUpdate,
                A.sysdate_updt                                           AS sysdateUpdate,
                IF(T.tipe_doc = 3, trmpl.jumlah_item, trmpo.jumlah_item) AS jumlahTerima,
                IFNULL(pl.jumlah_item, 0)                                AS jumlahPl,
                IFNULL(po.jumlah_item, 0)                                AS jumlahPo,
                IFNULL(renc.jumlah_item, 0)                              AS jumlahRencana,
                KAT.jumlah_itembeli                                      AS jumlahItemBeli,
                KAT.jumlah_itembonus                                     AS jumlahItemBonus,
                KAT.nama_sediaan                                         AS namaSediaan,
                KSAR.kode                                                AS satuanJual,
                KCIL.kode                                                AS satuan,  -- satuan kecil
                PBK.nama_pabrik                                          AS namaPabrik,
                trmpl.jumlah_item                                        AS jumlahTerimaPl,
                trmpo.jumlah_item                                        AS jumlahTerimaPo,
                $kolomTambahan
            FROM db1.tdetailf_penerimaan AS A
            $joinTambahan
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS pl ON A.kode_reffpl = pl.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS renc ON A.kode_reffrenc = renc.kode_reff
            LEFT JOIN db1.tdetailf_pemesanan AS po ON A.kode_reffpo = po.kode_reff
            LEFT JOIN db1.transaksif_penerimaan AS T ON A.kode_reff = T.kode
            LEFT JOIN (
                SELECT
                    A.kode_reffpl                 AS kode_reffpl,
                    A.kode_reffpo                 AS kode_reffpo,
                    A.id_katalog                  AS id_katalog,
                    IFNULL(SUM(A.jumlah_item), 0) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND (:tipeDokumen != 1 OR B.tipe_doc = 1)
                GROUP BY
                    A.kode_reffpo,
                    A.id_katalog
            ) AS trmpo ON A.kode_reffpo = trmpo.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpl                 AS kode_reffpl,
                    A.id_katalog                  AS id_katalog,
                    IFNULL(SUM(A.jumlah_item), 0) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND (:tipeDokumen != 1 OR B.tipe_doc = 1)
                GROUP BY
                    A.kode_reffpl,
                    A.id_katalog
            ) AS trmpl ON A.kode_reffpl = trmpl.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = trmpl.id_katalog
                AND A.id_katalog = trmpo.id_katalog
                AND A.id_katalog = po.id_katalog
                AND A.id_katalog = renc.id_katalog
                AND A.id_katalog = pl.id_katalog
            ORDER BY
                nama_sediaan
                $orderTambahan
        ";
        $params = [":kode" => $kode, ":tipeDokumen" => $penerimaan->tipeDokumen];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        if ($versi == 1 && $penerimaan->tanggalDokumen >= "2016-07-25") {
            $view = new PrintV01b(penerimaan: $penerimaan);
            return $view->__toString();

        } elseif ($versi == 2 && $penerimaan->tanggalDokumen >= "2016-07-25") {
            $view = new PrintV02b(penerimaan: $penerimaan);
            return $view->__toString();

        } elseif ($versi == 1) {
            $view = new PrintV01(penerimaan: $penerimaan);
            return $view->__toString();

        } elseif ($versi == 2) {
            $view = new PrintV02(penerimaan: $penerimaan);
            return $view->__toString();

        } elseif ($versi == 3) {
            $view = new PrintV03(penerimaan: $penerimaan, daftarDetailPenerimaan: $daftarDetailPenerimaan);
            return $view->__toString();

        } elseif ($versi == 4) {
            $daftarHalaman = [];

            $h = 0; // index halaman
            $b = 0; // index baris
            $total = 0;
            $barisPerHalaman = 29;

            foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
                $nilaiKemasan = $dPenerimaan->jumlahKemasan * $dPenerimaan->hargaKemasan;
                $subtotal = $nilaiKemasan - ($nilaiKemasan * $dPenerimaan->diskonItem / 100);

                $daftarHalaman[$h][$b] = [
                    "i" => $i,
                    "subtotal" => $subtotal,
                ];
                $total += $subtotal;

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $view = new PrintV04(
                penerimaan:             $penerimaan,
                daftarHalaman:          $daftarHalaman,
                daftarDetailPenerimaan: $daftarDetailPenerimaan,
                jumlahHalaman:          count($daftarHalaman),
                total:                  $total,
            );
            return $view->__toString();

        } elseif ($versi == 5) {
            $view = new PrintV05(penerimaan: $penerimaan,daftarDetailPenerimaan: $daftarDetailPenerimaan);
            return $view->__toString();

        } elseif ($versi == 6) {
            $view = new PrintV06(
                penerimaan:             $penerimaan,
                daftarDetailPenerimaan: $daftarDetailPenerimaan,
                tabung:                 [],
            );
            return $view->__toString();

        } elseif ($versi == 7) {
            $view = new PrintV07(penerimaan: $penerimaan, daftarDetailPenerimaan: $daftarDetailPenerimaan);
            return $view->__toString();

        } elseif ($versi == 8) {
            $daftarHalaman = [];

            $h = 0; // index halaman
            $b = 0; // index baris
            $barisPerHalaman = 29;

            foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
                $daftarHalaman[$h][$b] = ["i" => $i];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $view = new PrintV08(
                daftarHalaman:          $daftarHalaman,
                penerimaan:             $penerimaan,
                daftarDetailPenerimaan: $daftarDetailPenerimaan,
                jumlahHalaman:          count($daftarHalaman),
            );
            return $view->__toString();

        } else {
            throw new LogicBranchException;
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#verGudang    the original method
     */
    public function actionSaveVerGudang(): void
    {
        [   "kode" => $kode,
            "id_carabayar" => $idCaraBayar,
            "ver_gudang" => $verGudang,
            "ver_tglgudang" => $verTanggalGudang,
            "ver_usrgudang" => $verUserGudang,
            "sts_tabunggm" => $statusTabungGasMedis,
            // there are another vars!
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        if ($verGudang != 1) throw new Exception("Verifikasi gudang gagal. Anda belum melakukan checklist verifikasi.");

        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        // update status ver gudang n update stock => transaksif_penerimaan
        $data = ["ver_gudang" => 1, "ver_tglgudang" => $verTanggalGudang, "ver_usrgudang" => $verUserGudang];
        $daftarField = array_keys($data);
        $where = ["kode" => $kode];
        $berhasilUbah = (new FarmasiModel)->saveData("transaksif_penerimaan", $daftarField, $data, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kode, $transaction);

        switch ($idCaraBayar) {
            case 16: $jenisTerima = "konsinyasi"; break;
            case 14: $jenisTerima = "tunai"; break;
            case 17: $jenisTerima = "sumbangan"; break;
            case 18: $jenisTerima = "bonus"; break;
            default:   $jenisTerima = "pembelian";
        }

        if ($idCaraBayar != 18 && $idCaraBayar != 17) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.relasif_hargaperolehan
                SET
                    sts_hja = 0,
                    sts_hjapb = 0,
                    userid_updt = :idUserUbah,
                    sysdate_updt = :tanggalUbah
                WHERE id_katalog IN (
                    SELECT id_katalog
                    FROM db1.tdetailf_penerimaan
                    WHERE kode_reff = :kode
                )
            ";
            $params = [":idUserUbah" => $idUser, ":tanggalUbah" => $nowValSystem, ":kode" => $kode];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Harga Perolehan", "Kode Ref", $kode, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_hargaperolehan (
                    kode_reff,     -- 1
                    id_katalog,    -- 2
                    jns_terima,    -- 3
                    tgl_hp,        -- 4
                    stok_hp,       -- 5
                    tgl_aktifhp,   -- 6
                    stokakum_hp,   -- 7
                    hna_item,      -- 8
                    diskon_item,   -- 9
                    hp_item,       -- 10
                    phja,          -- 11
                    phjapb,        -- 12
                    hja_item,      -- 13
                    hjapb_item,    -- 14
                    hja_setting,   -- 15
                    hjapb_setting, -- 16
                    sts_hja,       -- 17
                    sts_hjapb,     -- 18
                    userid_updt,   -- 19
                    sysdate_updt,  -- 20
                    keterangan     -- 21
                )
                SELECT DISTINCT
                    A.kode_reff,                                          -- 1
                    A.id_katalog,                                         -- 2
                    :jenis,                                               -- 3
                    B.ver_tglgudang,                                      -- 4
                    A.jumlah_item,                                        -- 5
                    B.ver_tglgudang,                                      -- 6
                    A.jumlah_item,                                        -- 7
                    A.hna_item,                                           -- 8
                    A.diskon_item,                                        -- 9
                    A.hp_item,                                            -- 10
                    A.phja_item,                                          -- 11
                    A.phja_item,                                          -- 12
                    A.hja_item,                                           -- 13
                    A.hja_item,                                           -- 14
                    A.hja_item,                                           -- 15
                    A.hja_item,                                           -- 16
                    1,                                                    -- 17
                    1,                                                    -- 18
                    B.userid_updt,                                        -- 19
                    :tanggalUbah,                                         -- 20
                    CONCAT('Terima pembelian dari Supplier ', C.nama_pbf) -- 21
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
                WHERE
                    A.kode_reff = :kode
                    AND B.ver_gudang = 1
            ";
        } else {
            // merubah harga ke katalog
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_katalog AS A
                INNER JOIN (
                    SELECT DISTINCT
                        A.id_katalog,
                        A.harga_item,
                        A.diskon_item,
                        A.hja_item
                    FROM db1.tdetailf_penerimaan AS A
                    INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        A.kode_reff = :kodeRef
                        AND B.ver_gudang = 1
                ) AS B ON B.id_katalog = A.kode
                SET
                    A.harga_beli = B.harga_item,
                    A.diskon_beli = B.diskon_item,
                    A.harga_jual = B.hja_item
                WHERE TRUE
            ";
            $params = [":kodeRef" => $kode];
            $connection->createCommand($sql, $params)->execute();

            // query insert relasif_hargaperolehan jika status = 0 (bonus atau sumbangan)
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_hargaperolehan (
                    kode_reff,      -- 1
                    id_katalog,     -- 2
                    jns_terima,     -- 3
                    tgl_hp,         -- 4
                    stok_hp,        -- 5
                    stokakum_hp,    -- 6
                    hna_item,       -- 7
                    diskon_item,    -- 8
                    hp_item,        -- 9
                    phja,           -- 10
                    phjapb,         -- 11
                    hja_item,       -- 12
                    hjapb_item,     -- 13
                    disjual_item,   -- 14
                    disjualpb_item, -- 15
                    hja_setting,    -- 16
                    hjapb_setting,  -- 17
                    sts_hja,        -- 18
                    sts_hjapb,      -- 19
                    userid_updt,    -- 20
                    sysdate_updt,   -- 21
                    keterangan      -- 22
                )
                SELECT DISTINCT
                    A.kode_reff,                                          -- 1
                    A.id_katalog,                                         -- 2
                    :jenis,                                               -- 3
                    B.ver_tglgudang,                                      -- 4
                    A.jumlah_item,                                        -- 5
                    A.jumlah_item,                                        -- 6
                    A.hna_item,                                           -- 7
                    A.diskon_item,                                        -- 8
                    A.hp_item,                                            -- 9
                    A.phja_item,                                          -- 10
                    A.phjapb_item,                                        -- 11
                    A.hja_item,                                           -- 12
                    A.hjapb_item,                                         -- 13
                    0,                                                    -- 14
                    0,                                                    -- 15
                    A.hja_item,                                           -- 16
                    A.hjapb_item,                                         -- 17
                    0,                                                    -- 18
                    0,                                                    -- 19
                    B.userid_updt,                                        -- 20
                    :tanggalUbah,                                         -- 21
                    CONCAT('Terima pembelian dari Supplier ', C.nama_pbf) -- 22
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
                WHERE
                    A.kode_reff = :kode
                    AND B.ver_gudang = 1
            ";
        }
        $params = [":jenis" => $jenisTerima, ":tanggalUbah" => $nowValSystem, ":kode" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Harga Perolehan", $transaction);

        // dapatkan semua barang yang diterima
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff            AS kodeRef,
                B.id_katalog           AS idKatalog,
                B.kode_reffrenc        AS kodeRefRencana,
                B.kode_reffpo          AS kodeRefPo,
                B.kode_reffro          AS kodeRefRo,
                B.kode_reffpl          AS kodeRefPl,
                B.kode_refftrm         AS kodeRefTerima,
                B.kode_reffkons        AS kodeRefKons,
                B.id_reffkatalog       AS idRefKatalog,
                B.kemasan              AS kemasan,
                B.id_pabrik            AS idPabrik,          -- in use
                B.id_kemasan           AS idKemasan,         -- in use
                B.isi_kemasan          AS isiKemasan,        -- in use
                B.id_kemasandepo       AS idKemasanDepo,     -- in use
                B.jumlah_item          AS jumlahItem,        -- in use
                B.jumlah_kemasan       AS jumlahKemasan,
                B.jumlah_itembonus     AS jumlahItemBonus,
                B.harga_item           AS hargaItem,         -- in use
                B.harga_kemasan        AS hargaKemasan,      -- in use
                B.diskon_item          AS diskonItem,        -- in use
                B.diskon_harga         AS diskonHarga,
                B.hna_item             AS hnaItem,           -- in use
                B.hp_item              AS hpItem,            -- in use
                B.hppb_item            AS hpPbItem,
                B.phja_item            AS phjaItem,          -- in use
                B.phjapb_item          AS phjaPbItem,
                B.hja_item             AS hjaItem,           -- in use
                B.hjapb_item           AS hjaPbItem,
                B.sts_revisiitem       AS statusRevisiItem,
                B.keterangan           AS keterangan,
                B.userid_updt          AS useridUpdate,
                B.sysdate_updt         AS sysdateUpdate,
                A.kode_reff            AS kodeRef,
                A.id_katalog           AS idKatalog,         -- in use
                A.no_reffbatch         AS noRefBatch,
                A.no_batch             AS noBatch,           -- in use
                A.tgl_expired          AS tanggalKadaluarsa, -- in use
                A.no_urut              AS noUrut,            -- in use
                A.jumlah_item          AS jumlahItem,
                A.jumlah_kemasan       AS jumlahKemasan,
                A.id                   AS id,
                C.no_doc               AS noDokumen,         -- in use
                C.ppn                  AS ppn,
                C.id_gudangpenyimpanan AS idDepo,            -- in use
                D.kode                 AS kodeSo,            -- in use
                D.tgl_adm              AS tanggalAdm,        -- in use
                E.kd_unit              AS kodeStore,         -- in use
                F.nama_pbf             AS namaPemasok,       -- in use
                C.tgl_doc              AS tanggalDokumen     -- in use
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.transaksif_penerimaan AS C ON A.kode_reff = C.kode
            LEFT JOIN db1.transaksif_stokopname AS D ON C.id_gudangpenyimpanan = D.id_depo
            LEFT JOIN db1.masterf_depo AS E ON C.id_gudangpenyimpanan = E.id
            LEFT JOIN db1.masterf_pbf AS F ON C.id_pbf = F.id
            WHERE
                A.kode_reff = :kodeRef
                AND C.ver_gudang = 1
                AND D.sts_aktif = 1
                AND A.id_katalog = B.id_katalog
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailRincianPenerimaan = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarDetailRincianPenerimaan) throw new DataNotExistException($kode, $transaction);

        foreach ($daftarDetailRincianPenerimaan as $drPenerimaan) {
            $tglterima = $toUserDate($drPenerimaan->tanggalDokumen);
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_ketersediaan (
                    id_depo,          -- 1
                    kode_reff,        -- 2
                    no_doc,           -- 3
                    ppn,              -- 4
                    kode_stokopname,  -- 5
                    tgl_adm,          -- 6
                    tgl_transaksi,    -- 7
                    bln_transaksi,    -- 8
                    thn_transaksi,    -- 9
                    kode_transaksi,   -- 10
                    kode_store,       -- 11
                    tipe_tersedia,    -- 12
                    tgl_tersedia,     -- 13
                    no_batch,         -- 14
                    tgl_expired,      -- 15
                    id_katalog,       -- 16
                    id_pabrik,        -- 17
                    id_kemasan,       -- 18
                    isi_kemasan,      -- 19
                    jumlah_sebelum,   -- 20
                    jumlah_masuk,     -- 21
                    jumlah_keluar,    -- 22
                    jumlah_tersedia,  -- 23
                    harga_netoapotik, -- 24
                    harga_perolehan,  -- 25
                    phja,             -- 26
                    phja_pb,          -- 27
                    harga_jualapotik, -- 28
                    jumlah_item,      -- 29
                    jumlah_kemasan,   -- 30
                    harga_item,       -- 31
                    harga_kemasan,    -- 32
                    diskon_item,      -- 33
                    status,           -- 34
                    keterangan,       -- 35
                    userid_last,      -- 36
                    sysdate_last,     -- 37
                    id_reff           -- not exist in original source (error supressor)
                )
                SELECT
                    :idDepo,                       -- 1
                    A.kode_reff,                   -- 2
                    :noDokumen,                    -- 3
                    :ppn,                          -- 4
                    :kodeStokopname,               -- 5
                    :tanggalAdm,                   -- 6
                    :tanggalTransaksi,             -- 7
                    :bulanTransaksi,               -- 8
                    :tahunTransaksi,               -- 9
                    'T',                           -- 10
                    :kodeStore,                    -- 11
                    'penerimaan',                  -- 12
                    :tanggalTersedia,              -- 13
                    A.no_batch,                    -- 14
                    A.tgl_expired,                 -- 15
                    A.id_katalog,                  -- 16
                    :idPabrik,                     -- 17
                    :idKemasan,                    -- 18
                    :isiKemasan,                   -- 19
                    IFNULL(B.jumlah_stokfisik, 0), -- 20
                    A.jumlah_item,                 -- 21
                    0,                             -- 22
                    IFNULL(B.jumlah_stokfisik, 0) + A.jumlah_item,
                    :hargaNettoApotik,             -- 24
                    :hargaPerolehan,               -- 25
                    :phja,                         -- 26
                    :phjaPb,                       -- 27
                    :hargaJualApotik,              -- 28
                    A.jumlah_item,                 -- 29
                    (A.jumlah_item / :isiKemasan), -- 30
                    :hargaItem,                    -- 31
                    :hargaKemasan,                 -- 32
                    :diskonItem,                   -- 33
                    1,                             -- 34
                    :keterangan,                   -- 35
                    :idUserUbah,                   -- 36
                    :tanggalUbah,                  -- 37
                    ''                             -- not exist in original source (error supressor)
                FROM db1.tdetailf_penerimaanrinc AS A
                LEFT JOIN db1.transaksif_stokkatalog AS B ON B.id_depo = :idDepo
                WHERE
                    A.kode_reff = :kodeRef
                    AND A.id_katalog = :idKatalog
                    AND A.no_urut = :noUrut
                    AND A.id_katalog = B.id_katalog
            ";
            $params = [
                ":idDepo"           => $drPenerimaan->idDepo,
                ":noDokumen"        => $drPenerimaan->noDokumen,
                ":ppn"              => $drPenerimaan->ppn,
                ":kodeStokopname"   => $drPenerimaan->kodeSo,
                ":tanggalAdm"       => $drPenerimaan->tanggalAdm,
                ":tanggalTransaksi" => $nowValSystem,
                ":bulanTransaksi"   => date("m", strtotime($nowValSystem)),
                ":tahunTransaksi"   => date("Y", strtotime($nowValSystem)),
                ":kodeStore"        => $drPenerimaan->kodeStore,
                ":tanggalTersedia"  => $nowValSystem,
                ":idPabrik"         => $drPenerimaan->idPabrik,
                ":idKemasan"        => $drPenerimaan->idKemasan,
                ":isiKemasan"       => $drPenerimaan->isiKemasan,
                ":hargaNettoApotik" => $drPenerimaan->hnaItem,
                ":hargaPerolehan"   => $drPenerimaan->hpItem,
                ":phja"             => $drPenerimaan->phjaItem,
                ":phjaPb"           => $drPenerimaan->phjaItem,
                ":hargaJualApotik"  => $drPenerimaan->hjaItem,
                ":hargaItem"        => $drPenerimaan->hargaItem,
                ":hargaKemasan"     => $drPenerimaan->hargaKemasan,
                ":diskonItem"       => $drPenerimaan->diskonItem,
                ":keterangan"       => "Terima pembelian dari Pemasok {$drPenerimaan->namaPemasok}, pada tanggal $tglterima",
                ":idUserUbah"       => $idUser,
                ":tanggalUbah"      => $nowValSystem,
                ":kodeRef"          => $kode,
                ":idKatalog"        => $drPenerimaan->idKatalog,
                ":noUrut"           => $drPenerimaan->noUrut,
            ];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilTambah) throw new FailToInsertException("Ketersediaan", $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.transaksif_stokkatalog
                SET
                    id_depo = :idDepo,
                    id_katalog = :idKatalog,
                    id_kemasan = :idKemasan,
                    jumlah_stokfisik = :jumlahItem,
                    jumlah_stokadm = :jumlahItem,
                    status = 1,
                    userid_in = :verifikatorGudang,
                    sysdate_in = :tanggalVerGudang,
                    userid_updt = :verifikatorGudang,
                    sysdate_updt = :tanggalVerGudang,
                    keterangan = :keterangan
                ON DUPLICATE KEY UPDATE
                    jumlah_stokfisik = jumlah_stokfisik + :jumlahItem,
                    jumlah_stokadm = jumlah_stokadm + :jumlahItem,
                    userid_updt = :verifikatorGudang,
                    sysdate_updt = :tanggalVerGudang,
                    keterangan = :keterangan
            ";
            $params = [
                ":idDepo"            => $drPenerimaan->idDepo,
                ":idKatalog"         => $drPenerimaan->idKatalog,
                ":idKemasan"         => $drPenerimaan->idKemasanDepo,
                ":jumlahItem"        => $drPenerimaan->jumlahItem,
                ":verifikatorGudang" => $idUser,
                ":tanggalVerGudang"  => $nowValSystem,
                ":keterangan"        => "Penerimaan pembelian dari Pemasok " . $drPenerimaan->namaPemasok,
            ];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilTambah) throw new FailToInsertException("Stok Katalog", $transaction);

            // jika berhasil update stokkatalog
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.transaksif_stokkatalogrinc
                SET
                    id_unit = :idUnit,
                    id_katalog = :idKatalog,
                    no_batch = :noBatch,
                    tgl_expired = :tanggalKadaluarsa,
                    jumlah_fisik = :jumlahItem,
                    jumlah_adm = :jumlahItem,
                    sts_aktif = 1,
                    keterangan = :keterangan1,
                    userid_in = :verifikatorGudang,
                    sysdate_in = :tanggalVerGudang,
                    userid_updt = :verifikatorGudang,
                    sysdate_updt = :tanggalVerGudang
                ON DUPLICATE KEY UPDATE
                    jumlah_fisik = jumlah_fisik + :jumlahItem,
                    userid_updt = :verifikatorGudang,
                    sysdate_updt = :tanggalVerGudang,
                    keterangan = :keterangan2
            ";
            $params = [
                ":idUnit"            => $drPenerimaan->idDepo,
                ":idKatalog"         => $drPenerimaan->idKatalog,
                ":noBatch"           => $drPenerimaan->noBatch,
                ":tanggalKadaluarsa" => $drPenerimaan->tanggalKadaluarsa,
                ":jumlahItem"        => $drPenerimaan->jumlahItem,
                ":keterangan1"       => "Terima pembelian dari Distributor {$drPenerimaan->namaPemasok}",
                ":verifikatorGudang" => $idUser,
                ":tanggalVerGudang"  => $nowValSystem,
                ":keterangan2"       => "Terima pembelian dari Supplier {$drPenerimaan->namaPemasok}, pada tanggal $tglterima",
            ];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilTambah) throw new FailToInsertException("Rincian Stok Katalog", $transaction);

            if ($statusTabungGasMedis != 1) continue;

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
                    id_unitpemilik = :idDepo,
                    kd_unitposisi = 0,
                    id_unitposisi = :idDepo,
                    keterangan = :keterangan1,
                    sts_tersedia = :statusTersedia,
                    sts_aktif = 1,
                    userid_in = :verifikatorGudang,
                    sysdate_in = :tanggalVerGudang,
                    userid_updt = :verifikatorGudang,
                    sysdate_updt = :tanggalVerGudang
                ON DUPLICATE KEY UPDATE
                    tgl_expired = :tanggalKadaluarsa,
                    isi_kemasan = :isiKemasan,
                    id_kemasan = :idKemasan,
                    id_kemasandepo = :idKemasanDepo,
                    kd_unitposisi = 0,
                    id_unitposisi = 60,
                    keterangan = :keterangan2,
                    sts_tersedia = 1,
                    sts_aktif = 1,
                    userid_updt = :verifikatorGudang,
                    sysdate_updt = :tanggalVerGudang
            ";
            $params = [
                ":idKatalog"         => $drPenerimaan->idKatalog,
                ":noBatch"           => $drPenerimaan->noBatch,
                ":tanggalKadaluarsa" => $drPenerimaan->tanggalKadaluarsa,
                ":isiKemasan"        => $drPenerimaan->isiKemasan,
                ":idKemasan"         => $drPenerimaan->idKemasan,
                ":idKemasanDepo"     => $drPenerimaan->idKemasanDepo,
                ":idDepo"            => $drPenerimaan->idDepo,
                ":keterangan1"       => "Terima pembelian dari Distributor {$drPenerimaan->namaPemasok}, pada tanggal $tglterima",
                ":statusTersedia"    => $verGudang,
                ":verifikatorGudang" => $idUser,
                ":tanggalVerGudang"  => $nowValSystem,
                ":keterangan2"       => "Terima pembelian dari Distributor {$drPenerimaan->namaPemasok}, pada tanggal $tglterima",
            ];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilTambah) throw new FailToInsertException("Seri Tabung", $transaction);

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
                    K.id,                   -- 1
                    A.id_katalog,           -- 2
                    A.no_batch,             -- 3
                    A.kode_reff,            -- 4
                    C.no_doc,               -- 5
                    'T',                    -- 6
                    1,                      -- 7
                    C.id_pbf,               -- 8
                    0,                      -- 9
                    C.id_gudangpenyimpanan, -- 10
                    A.tgl_expired,          -- 11
                    1,                      -- 12
                    0,                      -- 13
                    1,                      -- 14
                    1,                      -- 15
                    :keterangan,            -- 16
                    1,                      -- 17
                    C.ver_usrgudang,        -- 18
                    C.ver_tglgudang,        -- 19
                    C.ver_usrgudang,        -- 20
                    C.ver_tglgudang,        -- 21
                    '',                     -- not exist in original source (error supressor)
                    ''                      -- idem
                FROM db1.tdetailf_penerimaanrinc AS A
                LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
                LEFT JOIN db1.transaksif_penerimaan AS C ON A.kode_reff = C.kode
                JOIN (
                    SELECT IFNULL(MAX(id), 0) + 1 AS id
                    FROM db1.transaksif_kartugasmedis
                ) AS K
                WHERE
                    A.id_katalog = :idKatalog
                    AND A.no_batch = :noBatch
                    AND A.kode_reff = :kodeRef
                    AND A.id_katalog = B.id_katalog
            ";
            $params = [
                ":keterangan" => "Terima pembelian dari Distributor {$drPenerimaan->namaPemasok}, pada tanggal $tglterima",
                ":idKatalog" => $drPenerimaan->idKatalog,
                ":noBatch" => $drPenerimaan->noBatch,
                ":kodeRef" => $kode,
            ];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilTambah) throw new FailToInsertException("Kartu Gas Medis", $transaction);
        }

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#verGudang    the original method
     */
    public function actionVerGudangData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                     AS kode,
                A.revisike                                 AS revisiKe,
                A.keterangan                               AS keterangan,
                A.no_doc                                   AS noDokumen,
                A.tgl_doc                                  AS tanggalDokumen,
                A.tipe_doc                                 AS tipeDokumen,          -- in use
                A.kode_refftrm                             AS kodeRefTerima,
                A.kode_reffpl                              AS kodeRefPl,
                A.kode_reffpo                              AS kodeRefPo,
                A.kode_reffro                              AS kodeRefRo,
                A.kode_reffrenc                            AS kodeRefRencana,
                A.kode_reffkons                            AS kodeRefKons,
                A.no_faktur                                AS noFaktur,
                A.no_suratjalan                            AS noSuratJalan,
                A.terimake                                 AS terimaKe,
                A.id_pbf                                   AS idPemasok,
                A.id_gudangpenyimpanan                     AS idGudangPenyimpanan,
                A.id_jenisanggaran                         AS idJenisAnggaran,
                A.id_sumberdana                            AS idSumberDana,
                A.id_subsumberdana                         AS idSubsumberDana,
                A.id_carabayar                             AS idCaraBayar,
                A.id_jenisharga                            AS idJenisHarga,
                A.thn_anggaran                             AS tahunAnggaran,
                A.blnawal_anggaran                         AS bulanAwalAnggaran,
                A.blnakhir_anggaran                        AS bulanAkhirAnggaran,
                A.ppn                                      AS ppn,
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_tabunggm                             AS statusTabungGasMedis, -- in use
                A.sts_linked                               AS statusLinked,
                A.sts_revisi                               AS statusRevisi,
                A.sysdate_rev                              AS sysdateRevisi,
                A.keterangan_rev                           AS keteranganRevisi,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_updatekartu                          AS statusUpdateKartu,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revterima                            AS verRevTerima,
                A.ver_revtglterima                         AS verRevTanggalTerima,
                A.ver_revusrterima                         AS verRevUserTerima,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalRevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.sts_testing                              AS statusTesting,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi,
                C.no_doc                                   AS noSpk,
                F.no_doc                                   AS noPo,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                F.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                F.thn_anggaran                             AS tahunAnggaranPo,
                IFNULL(F.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim,
                ''                                         AS detailPenerimaan
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.transaksif_pemesanan AS F ON A.kode_reffpo = F.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
                AND ver_terima = 1
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$penerimaan) throw new DataNotExistException($kode);

        if ($penerimaan->tipeDokumen != 0) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    B.kode_reff                AS kodeRef,
                    B.id_katalog               AS idKatalog,
                    B.kode_reffrenc            AS kodeRefRencana,
                    B.kode_reffpo              AS kodeRefPo,
                    B.kode_reffro              AS kodeRefRo,
                    B.kode_reffpl              AS kodeRefPl,
                    B.kode_refftrm             AS kodeRefTerima,
                    B.kode_reffkons            AS kodeRefKons,
                    B.id_reffkatalog           AS idRefKatalog,
                    B.kemasan                  AS kemasan,
                    B.id_pabrik                AS idPabrik,
                    B.id_kemasan               AS idKemasan,
                    B.isi_kemasan              AS isiKemasan,
                    B.id_kemasandepo           AS idKemasanDepo,
                    B.jumlah_item              AS jumlahItem,
                    B.jumlah_kemasan           AS jumlahKemasan,
                    B.jumlah_itembonus         AS jumlahItemBonus,
                    B.harga_item               AS hargaItem,
                    B.harga_kemasan            AS hargaKemasan,
                    B.diskon_item              AS diskonItem,
                    B.diskon_harga             AS diskonHarga,
                    B.hna_item                 AS hnaItem,
                    B.hp_item                  AS hpItem,
                    B.hppb_item                AS hpPbItem,
                    B.phja_item                AS phjaItem,
                    B.phjapb_item              AS phjaPbItem,
                    B.hja_item                 AS hjaItem,
                    B.hjapb_item               AS hjaPbItem,
                    B.sts_revisiitem           AS statusRevisiItem,
                    B.keterangan               AS keterangan,
                    B.userid_updt              AS useridUpdate,
                    B.sysdate_updt             AS sysdateUpdate,
                    A.kode_reff                AS kodeRef,
                    A.id_katalog               AS idKatalog,
                    A.no_reffbatch             AS noRefBatch,
                    A.no_batch                 AS noBatch,
                    IFNULL(A.tgl_expired, '')  AS tanggalKadaluarsa,
                    A.no_urut                  AS noUrut,
                    A.jumlah_item              AS jumlahItem,
                    A.jumlah_kemasan           AS jumlahKemasan,
                    A.id                       AS id,
                    K.jumlah_itembeli          AS jumlahItemBeli,
                    K.jumlah_itembonus         AS jumlahItemBonus,
                    K.nama_sediaan             AS namaSediaan,
                    K.kemasan                  AS kemasanKat,
                    K.id_kemasanbesar          AS idKemasanKat,
                    K.id_kemasankecil          AS idKemasanDepoKat,
                    K.isi_kemasan              AS isiKemasanKat,
                    K.harga_beli               AS hargaItemKat,
                    K.harga_beli * diskon_beli AS hargaKemasanKat,
                    K.diskon_beli              AS diskonItemKat,
                    J.kode                     AS satuanJual,
                    S.kode                     AS satuan,
                    Jk.kode                    AS satuanJualKat,
                    Sk.kode                    AS satuanKat,
                    PBK.nama_pabrik            AS namaPabrik,
                    IFNULL(C.jumlah_item, 0)   AS jumlahPl,
                    IFNULL(trm.jumlah_item, 0) AS jumlahTerima,
                    IFNULL(D.jumlah_item, 0)   AS jumlahPo,
                    K.jumlah_itembeli          AS jumlahItemBeliKat,
                    K.jumlah_itembonus         AS jumlahItemBonusKat
                FROM db1.tdetailf_penerimaanrinc AS A
                LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
                LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS J ON J.id = B.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS S ON S.id = B.id_kemasandepo
                LEFT JOIN db1.masterf_kemasan AS Jk ON Jk.id = K.id_kemasanbesar
                LEFT JOIN db1.masterf_kemasan AS Sk ON Sk.id = K.id_kemasankecil
                LEFT JOIN db1.tdetailf_pembelian AS C ON B.kode_reffpl = C.kode_reff
                LEFT JOIN db1.tdetailf_pemesanan AS D ON B.kode_reffpo = D.kode_reff
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
                        AND A.kode_reff != :kode
                    GROUP BY A.kode_reffpo, A.id_katalog
                ) AS trm ON B.kode_reffpo = trm.kode_reffpo
                WHERE
                    A.kode_reff = :kode
                    AND B.id_katalog = trm.id_katalog
                    AND B.id_katalog = D.id_katalog
                    AND B.id_katalog = C.id_katalog
                    AND A.id_katalog = B.id_katalog
                ORDER BY nama_sediaan, no_urut
            ";
        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    B.kode_reff                  AS kodeRef,
                    B.id_katalog                 AS idKatalog,
                    B.kode_reffrenc              AS kodeRefRencana,
                    B.kode_reffpo                AS kodeRefPo,
                    B.kode_reffro                AS kodeRefRo,
                    B.kode_reffpl                AS kodeRefPl,
                    B.kode_refftrm               AS kodeRefTerima,
                    B.kode_reffkons              AS kodeRefKons,
                    B.id_reffkatalog             AS idRefKatalog,
                    B.kemasan                    AS kemasan,
                    B.id_pabrik                  AS idPabrik,
                    B.id_kemasan                 AS idKemasan,
                    B.isi_kemasan                AS isiKemasan,
                    B.id_kemasandepo             AS idKemasanDepo,
                    B.jumlah_item                AS jumlahItem,
                    B.jumlah_kemasan             AS jumlahKemasan,
                    B.jumlah_itembonus           AS jumlahItemBonus,
                    B.harga_item                 AS hargaItem,
                    B.harga_kemasan              AS hargaKemasan,
                    B.diskon_item                AS diskonItem,
                    B.diskon_harga               AS diskonHarga,
                    B.hna_item                   AS hnaItem,
                    B.hp_item                    AS hpItem,
                    B.hppb_item                  AS hpPbItem,
                    B.phja_item                  AS phjaItem,
                    B.phjapb_item                AS phjaPbItem,
                    B.hja_item                   AS hjaItem,
                    B.hjapb_item                 AS hjaPbItem,
                    B.sts_revisiitem             AS statusRevisiItem,
                    B.keterangan                 AS keterangan,
                    B.userid_updt                AS useridUpdate,
                    B.sysdate_updt               AS sysdateUpdate,
                    A.kode_reff                  AS kodeRef,
                    A.id_katalog                 AS idKatalog,
                    A.no_reffbatch               AS noRefBatch,
                    A.no_batch                   AS noBatch,
                    IFNULL(A.tgl_expired, '')    AS tanggalKadaluarsa,
                    A.no_urut                    AS noUrut,
                    A.jumlah_item                AS jumlahItem,
                    A.jumlah_kemasan             AS jumlahKemasan,
                    A.id                         AS id,
                    K.jumlah_itembeli            AS jumlahItemBeli,
                    K.jumlah_itembonus           AS jumlahItemBonus,
                    K.nama_sediaan               AS namaSediaan,
                    PBK.nama_pabrik              AS namaPabrik,
                    KSAR.kode                    AS satuanJual,
                    KCIL.kode                    AS satuan,  -- satuan kecil
                    K1.kode                      AS satuanJualKat,
                    K2.kode                      AS satuanKat,
                    K.kemasan                    AS kemasanKat,
                    K.isi_kemasan                AS isiKemasanKat,
                    K.id_kemasanbesar            AS idKemasanKat,
                    K.id_kemasankecil            AS idKemasanDepoKat,
                    K.harga_beli                 AS hargaItemKat,
                    K.harga_beli * K.isi_kemasan AS hargaKemasanKat,
                    IFNULL(tR.jumlah_item, 0)    AS jumlahRencana,
                    IFNULL(tH.jumlah_item, 0)    AS jumlahHps,
                    C.jumlah_item                AS jumlahPl,
                    IFNULL(tRo.jumlah_item, 0)   AS jumlahRo,
                    IFNULL(D.jumlah_item, 0)     AS jumlahDo,
                    IFNULL(T_pl.jumlah_item, 0)  AS jumlahTerimaPl,
                    IFNULL(T_po.jumlah_item, 0)  AS jumlahTerimaPo,
                    IF(IFNULL(T_po.jumlah_item, 0) = 0, IFNULL(T_pl.jumlah_item, 0), T_po.jumlah_item)    AS jumlahTerima,
                    IF(IFNULL(Rt_po.jumlah_item, 0) = 0, IFNULL(Rt_pl.jumlah_item, 0), Rt_po.jumlah_item) AS jumlahRetur
                FROM db1.tdetailf_penerimaanrinc AS A
                LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
                LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = B.id_kemasandepo
                LEFT JOIN db1.masterf_kemasan AS K1 ON K1.id = K.id_kemasanbesar
                LEFT JOIN db1.masterf_kemasan AS K2 ON K2.id = K.id_kemasankecil
                LEFT JOIN db1.tdetailf_pembelian AS C ON B.kode_reffpl = C.kode_reff
                LEFT JOIN db1.tdetailf_pengadaan AS tH ON C.id_reffkatalog = tH.id_reffkatalog
                LEFT JOIN db1.tdetailf_perencanaan AS tR ON C.id_reffkatalog = tR.id_katalog
                LEFT JOIN db1.tdetailf_pemesanan AS D ON B.kode_reffpo = D.kode_reff
                LEFT JOIN db1.tdetailf_perencanaan AS tRo ON B.id_katalog = tRo.id_katalog
                LEFT JOIN (
                    SELECT
                        A.kode_reffpo      AS kode_reffpo,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reff != :kode
                    GROUP BY A.kode_reffpo, A.id_katalog
                ) AS T_po ON D.kode_reff = T_po.kode_reffpo
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reff != :kode
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS T_pl ON C.kode_reff = T_pl.kode_reffpl
                LEFT JOIN (
                    SELECT
                        A.kode_reffpo      AS kode_reffpo,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_return AS A
                    LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpo, A.id_katalog
                ) AS Rt_po ON D.kode_reff = Rt_po.kode_reffpo
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_return AS A
                    LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS Rt_pl ON C.kode_reff = Rt_pl.kode_reffpl
                WHERE
                    A.kode_reff = :kode
                    AND B.id_katalog = Rt_pl.id_katalog
                    AND B.id_katalog = Rt_po.id_katalog
                    AND B.id_katalog = T_pl.id_katalog
                    AND B.id_katalog = T_po.id_katalog
                    AND B.kode_reffro = tRo.kode_reff
                    AND B.id_katalog = D.id_katalog
                    AND C.kode_reffrenc = tR.kode_reff
                    AND C.kode_reffhps = tH.kode_reff
                    AND B.id_katalog = C.id_katalog
                    AND A.id_katalog = B.id_katalog
            ";
        }
        $params = [":kode" => $kode];
        $penerimaan->detailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($penerimaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#verAkuntansi    the original method
     */
    public function actionSaveVerAkuntansi(): void
    {
        ["kode" => $kode, "ver_akuntansi" => $verAkuntansi] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        if ($verAkuntansi != 1) throw new Exception("Verifikasi akuntansi gagal. Anda belum melakukan checklist verifikasi.");

        $data = ["ver_akuntansi" => 1, "ver_tglakuntansi" => $nowValSystem, "ver_usrakuntansi" => $idUser];
        $daftarField = array_keys($data);
        $where = ["kode" => $kode];
        (new FarmasiModel)->saveData("transaksif_penerimaan", $daftarField, $data, $where);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#verAkuntansi    the original method
     */
    public function actionVerAkuntansiData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                     AS kode,
                A.revisike                                 AS revisiKe,
                A.keterangan                               AS keterangan,
                A.no_doc                                   AS noDokumen,
                A.tgl_doc                                  AS tanggalDokumen,
                A.tipe_doc                                 AS tipeDokumen,          -- in use
                A.kode_refftrm                             AS kodeRefTerima,
                A.kode_reffpl                              AS kodeRefPl,
                A.kode_reffpo                              AS kodeRefPo,
                A.kode_reffro                              AS kodeRefRo,
                A.kode_reffrenc                            AS kodeRefRencana,
                A.kode_reffkons                            AS kodeRefKons,
                A.no_faktur                                AS noFaktur,
                A.no_suratjalan                            AS noSuratJalan,
                A.terimake                                 AS terimaKe,
                A.id_pbf                                   AS idPemasok,            -- in use
                A.id_gudangpenyimpanan                     AS idGudangPenyimpanan,
                A.id_jenisanggaran                         AS idJenisAnggaran,
                A.id_sumberdana                            AS idSumberDana,
                A.id_subsumberdana                         AS idSubsumberDana,
                A.id_carabayar                             AS idCaraBayar,
                A.id_jenisharga                            AS idJenisHarga,
                A.thn_anggaran                             AS tahunAnggaran,
                A.blnawal_anggaran                         AS bulanAwalAnggaran,
                A.blnakhir_anggaran                        AS bulanAkhirAnggaran,
                A.ppn                                      AS ppn,
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_tabunggm                             AS statusTabungGasMedis,
                A.sts_linked                               AS statusLinked,
                A.sts_revisi                               AS statusRevisi,
                A.sysdate_rev                              AS sysdateRevisi,
                A.keterangan_rev                           AS keteranganRevisi,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_updatekartu                          AS statusUpdateKartu,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revterima                            AS verRevTerima,
                A.ver_revtglterima                         AS verRevTanggalTerima,
                A.ver_revusrterima                         AS verRevUserTerima,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalRevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.sts_testing                              AS statusTesting,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi,
                C.no_doc                                   AS noSpk,
                F.no_doc                                   AS noPo,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                F.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                F.thn_anggaran                             AS tahunAnggaranPo,
                IFNULL(F.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.transaksif_pemesanan AS F ON A.kode_reffpo = F.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND ver_terima = 1
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$penerimaan) throw new DataNotExistException($kode);

        if ($penerimaan->tipeDokumen != 0) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    B.kode_reff                AS kodeRef,
                    B.id_katalog               AS idKatalog,
                    B.kode_reffrenc            AS kodeRefRencana,
                    B.kode_reffpo              AS kodeRefPo,
                    B.kode_reffro              AS kodeRefRo,
                    B.kode_reffpl              AS kodeRefPl,
                    B.kode_refftrm             AS kodeRefTerima,
                    B.kode_reffkons            AS kodeRefKons,
                    B.id_reffkatalog           AS idRefKatalog,
                    B.kemasan                  AS kemasan,
                    B.id_pabrik                AS idPabrik,
                    B.id_kemasan               AS idKemasan,
                    B.isi_kemasan              AS isiKemasan,
                    B.id_kemasandepo           AS idKemasanDepo,
                    B.jumlah_item              AS jumlahItem,
                    B.jumlah_kemasan           AS jumlahKemasan,
                    B.jumlah_itembonus         AS jumlahItemBonus,
                    B.harga_item               AS hargaItem,
                    B.harga_kemasan            AS hargaKemasan,
                    B.diskon_item              AS diskonItem,
                    B.diskon_harga             AS diskonHarga,
                    B.hna_item                 AS hnaItem,
                    B.hp_item                  AS hpItem,
                    B.hppb_item                AS hpPbItem,
                    B.phja_item                AS phjaItem,
                    B.phjapb_item              AS phjaPbItem,
                    B.hja_item                 AS hjaItem,
                    B.hjapb_item               AS hjaPbItem,
                    B.sts_revisiitem           AS statusRevisiItem,
                    B.keterangan               AS keterangan,
                    B.userid_updt              AS useridUpdate,
                    B.sysdate_updt             AS sysdateUpdate,
                    A.kode_reff                AS kodeRef,
                    A.id_katalog               AS idKatalog,
                    A.no_reffbatch             AS noRefBatch,
                    A.no_batch                 AS noBatch,
                    IFNULL(A.tgl_expired, '')  AS tanggalKadaluarsa,
                    A.no_urut                  AS noUrut,
                    A.jumlah_item              AS jumlahItem,
                    A.jumlah_kemasan           AS jumlahKemasan,
                    A.id                       AS id,
                    K.jumlah_itembeli          AS jumlahItemBeli,
                    K.jumlah_itembonus         AS jumlahItemBonus,
                    K.nama_sediaan             AS namaSediaan,
                    K.kemasan                  AS kemasanKat,
                    K.id_kemasanbesar          AS idKemasanKat,
                    K.id_kemasankecil          AS idKemasanDepoKat,
                    K.isi_kemasan              AS isiKemasanKat,
                    K.harga_beli               AS hargaItemKat,
                    K.harga_beli * diskon_beli AS hargaKemasanKat,
                    K.diskon_beli              AS diskonItemKat,
                    J.kode                     AS satuanJual,
                    S.kode                     AS satuan,
                    Jk.kode                    AS satuanJualKat,
                    Sk.kode                    AS satuanKat,
                    PBK.nama_pabrik            AS namaPabrik,
                    K.jumlah_itembeli          AS jumlahItemBeliKat,
                    K.jumlah_itembonus         AS jumlahItemBonusKat
                FROM db1.tdetailf_penerimaanrinc AS A
                LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
                LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS J ON J.id = B.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS S ON S.id = B.id_kemasandepo
                LEFT JOIN db1.masterf_kemasan AS Jk ON Jk.id = K.id_kemasanbesar
                LEFT JOIN db1.masterf_kemasan AS Sk ON Sk.id = K.id_kemasankecil
                LEFT JOIN (
                    SELECT
                        A.kode_refftrm     AS kode_refftrm,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reff != :kode
                    GROUP BY A.kode_refftrm, A.id_katalog
                ) AS trm ON B.kode_reff = trm.kode_refftrm
                WHERE
                    A.kode_reff = :kode
                    AND B.id_katalog = trm.id_katalog
                    AND A.id_katalog = B.id_katalog
                ORDER BY nama_sediaan, no_urut
            ";
        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    B.kode_reff                  AS kodeRef,
                    B.id_katalog                 AS idKatalog,
                    B.kode_reffrenc              AS kodeRefRencana,
                    B.kode_reffpo                AS kodeRefPo,
                    B.kode_reffro                AS kodeRefRo,
                    B.kode_reffpl                AS kodeRefPl,
                    B.kode_refftrm               AS kodeRefTerima,
                    B.kode_reffkons              AS kodeRefKons,
                    B.id_reffkatalog             AS idRefKatalog,
                    B.kemasan                    AS kemasan,
                    B.id_pabrik                  AS idPabrik,
                    B.id_kemasan                 AS idKemasan,
                    B.isi_kemasan                AS isiKemasan,
                    B.id_kemasandepo             AS idKemasanDepo,
                    B.jumlah_item                AS jumlahItem,
                    B.jumlah_kemasan             AS jumlahKemasan,
                    B.jumlah_itembonus           AS jumlahItemBonus,
                    B.harga_item                 AS hargaItem,
                    B.harga_kemasan              AS hargaKemasan,
                    B.diskon_item                AS diskonItem,
                    B.diskon_harga               AS diskonHarga,
                    B.hna_item                   AS hnaItem,
                    B.hp_item                    AS hpItem,
                    B.hppb_item                  AS hpPbItem,
                    B.phja_item                  AS phjaItem,
                    B.phjapb_item                AS phjaPbItem,
                    B.hja_item                   AS hjaItem,
                    B.hjapb_item                 AS hjaPbItem,
                    B.sts_revisiitem             AS statusRevisiItem,
                    B.keterangan                 AS keterangan,
                    B.userid_updt                AS useridUpdate,
                    B.sysdate_updt               AS sysdateUpdate,
                    A.kode_reff                  AS kodeRef,
                    A.id_katalog                 AS idKatalog,
                    A.no_reffbatch               AS noRefBatch,
                    A.no_batch                   AS noBatch,
                    IFNULL(A.tgl_expired, '')    AS tanggalKadaluarsa,
                    A.no_urut                    AS noUrut,
                    A.jumlah_item                AS jumlahItem,
                    A.jumlah_kemasan             AS jumlahKemasan,
                    A.id                         AS id,
                    K.jumlah_itembeli            AS jumlahItemBeli,
                    K.jumlah_itembonus           AS jumlahItemBonus,
                    K.nama_sediaan               AS namaSediaan,
                    PBK.nama_pabrik              AS namaPabrik,
                    KEM1.kode                    AS satuanJual,
                    KEM2.kode                    AS satuan,
                    K1.kode                      AS satuanJualKat,
                    K2.kode                      AS satuanKat,
                    K.kemasan                    AS kemasanKat,
                    K.isi_kemasan                AS isiKemasanKat,
                    K.id_kemasanbesar            AS idKemasanKat,
                    K.id_kemasankecil            AS idKemasanDepoKat,
                    K.harga_beli                 AS hargaItemKat,
                    K.harga_beli * K.isi_kemasan AS hargaKemasanKat,
                    IFNULL(tR.jumlah_item, 0)    AS jumlahRencana,
                    IFNULL(tH.jumlah_item, 0)    AS jumlahHps,
                    C.jumlah_item                AS jumlahPl,
                    IFNULL(tRo.jumlah_item, 0)   AS jumlahRo,
                    IFNULL(D.jumlah_item, 0)     AS jumlahDo,
                    IFNULL(T_pl.jumlah_item, 0)  AS jumlahTerimaPl,
                    IFNULL(T_po.jumlah_item, 0)  AS jumlahTerimaPo,
                    IF(IFNULL(T_po.jumlah_item, 0) = 0, IFNULL(T_pl.jumlah_item, 0), T_po.jumlah_item)    AS jumlahTerima,
                    IF(IFNULL(Rt_po.jumlah_item, 0) = 0, IFNULL(Rt_pl.jumlah_item, 0), Rt_po.jumlah_item) AS jumlahRetur
                FROM db1.tdetailf_penerimaanrinc AS A
                LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
                LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS KEM1 ON KEM1.id = B.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS KEM2 ON KEM2.id = B.id_kemasandepo
                LEFT JOIN db1.masterf_kemasan AS K1 ON K1.id = K.id_kemasanbesar
                LEFT JOIN db1.masterf_kemasan AS K2 ON K2.id = K.id_kemasankecil
                LEFT JOIN db1.tdetailf_pembelian AS C ON B.kode_reffpl = C.kode_reff
                LEFT JOIN db1.tdetailf_pengadaan AS tH ON C.id_reffkatalog = tH.id_reffkatalog
                LEFT JOIN db1.tdetailf_perencanaan AS tR ON C.id_reffkatalog = tR.id_katalog
                LEFT JOIN db1.tdetailf_pemesanan AS D ON B.kode_reffpo = D.kode_reff
                LEFT JOIN db1.tdetailf_perencanaan AS tRo ON B.id_katalog = tRo.id_katalog
                LEFT JOIN (
                    SELECT
                        A.kode_reffpo      AS kode_reffpo,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reff != :kode
                    GROUP BY A.kode_reffpo, A.id_katalog
                ) AS T_po ON D.kode_reff = T_po.kode_reffpo
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reff != :kode
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS T_pl ON C.kode_reff = T_pl.kode_reffpl
                LEFT JOIN (
                    SELECT
                        A.kode_reffpo      AS kode_reffpo,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_return AS A
                    LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpo, A.id_katalog
                ) AS Rt_po ON D.kode_reff = Rt_po.kode_reffpo
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_return AS A
                    LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                    WHERE B.sts_deleted = 0
                    GROUP BY A.kode_reffpl, A.id_katalog
                ) AS Rt_pl ON C.kode_reff = Rt_pl.kode_reffpl
                WHERE
                    A.kode_reff = :kode
                    AND B.id_katalog = Rt_pl.id_katalog
                    AND B.id_katalog = Rt_po.id_katalog
                    AND B.id_katalog = T_pl.id_katalog
                    AND B.id_katalog = T_po.id_katalog
                    AND B.kode_reffro = tRo.kode_reff
                    AND B.id_katalog = D.id_katalog
                    AND C.kode_reffrenc = tR.kode_reff
                    AND C.kode_reffhps = tH.kode_reff
                    AND B.id_katalog = C.id_katalog
                    AND A.id_katalog = B.id_katalog
            ";
        }
        $params = [":kode" => $kode];
        $detailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                 AS kode,
                A.revisike             AS revisiKe,
                A.keterangan           AS keterangan,
                A.no_doc               AS noDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.tipe_doc             AS tipeDokumen,
                A.kode_refftrm         AS kodeRefTerima,
                A.kode_reffpl          AS kodeRefPl,
                A.kode_reffpo          AS kodeRefPo,
                A.kode_reffro          AS kodeRefRo,
                A.kode_reffrenc        AS kodeRefRencana,
                A.kode_reffkons        AS kodeRefKons,
                A.id_pbf               AS idPemasok,
                A.id_gudangpenyimpanan AS idGudangPenyimpanan,
                A.id_jenisanggaran     AS idJenisAnggaran,
                A.id_sumberdana        AS idSumberDana,
                A.id_subsumberdana     AS idSubsumberDana,
                A.id_carabayar         AS idCaraBayar,
                A.id_jenisharga        AS idJenisHarga,
                A.thn_anggaran         AS tahunAnggaran,
                A.blnawal_anggaran     AS bulanAwalAnggaran,
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                A.ppn                  AS ppn,
                A.nilai_total          AS nilaiTotal,
                A.nilai_diskon         AS nilaiDiskon,
                A.nilai_ppn            AS nilaiPpn,
                A.nilai_pembulatan     AS nilaiPembulatan,
                A.nilai_akhir          AS nilaiAkhir,
                A.sts_linked           AS statusLinked,
                A.sts_deleted          AS statusDeleted,
                A.sysdate_del          AS sysdateDeleted,
                A.sts_izinrevisi       AS statusIzinRevisi,
                A.ver_tglizinrevisi    AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi    AS verUserIzinRevisi,
                A.ver_revisi           AS verRevisi,
                A.ver_tglrevisi        AS verTanggalRevisi,
                A.ver_usrrevisi        AS verUserRevisi,
                A.ver_terima           AS verTerima,
                A.ver_tglterima        AS verTanggalTerima,
                A.ver_usrterima        AS verUserTerima,
                A.ver_gudang           AS verGudang,
                A.ver_tglgudang        AS verTanggalGudang,
                A.ver_usrgudang        AS verUserGudang,
                A.ver_akuntansi        AS verAkuntansi,
                A.ver_tglakuntansi     AS verTanggalAkuntansi,
                A.ver_usrakuntansi     AS verUserAkuntansi,
                A.userid_in            AS useridInput,
                A.sysdate_in           AS sysdateInput,
                A.userid_updt          AS useridUpdate,
                A.sysdate_updt         AS sysdateUpdate,
                D.nama_pbf             AS namaPemasok,
                E.subjenis_anggaran    AS subjenisAnggaran,
                B.no_doc               AS noTerima,
                C.no_doc               AS noSpk,
                F.no_doc               AS noPo
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_refftrm = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.transaksif_pemesanan AS F ON A.kode_reffpo = F.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            WHERE
                A.id_pbf = :idPemasok
                AND A.sts_deleted = 0
                AND A.ver_akuntansi = 0
                AND A.tipe_doc = 0
        ";
        $params = [":idPemasok" => $penerimaan->idPemasok];
        $daftarRetur = $connection->createCommand($sql, $params)->queryAll();

        return json_encode(["penerimaan" => $penerimaan, "detailPenerimaan" => $detailPenerimaan, "daftarRetur" => $daftarRetur]);
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#doVerRevisigudang    the original method
     */
    public function actionSaveVerRevisiGudang(): void
    {
        ["kode" => $kode, "action" => $action, "ver_revisi" => $verRevisi] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $todayValSystem = Yii::$app->dateTime->todayVal("system");
        $bulan = date("m");
        $tahun = date("Y");

        if ($action != "verif_gudang") throw new Exception("'action' must be 'verif_gudang'");
        if ($verRevisi != 1) throw new Exception("'verRevisi' must be '1'");

        $data = [
            "sts_revisi" => 0,
            "sysdate_rev" => null,
            "keterangan_rev" => null,
            "ver_revisi" => 1,
            "ver_tglrevisi" => $todayValSystem,
            "ver_usrrevisi" => $idUser,
            "sts_izinrevisi" => 0,
            "ver_tglizinrevisi" => null,
            "ver_usrizinrevisi" => null,
        ];

        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        // update status ver gudang n update stock => transaksif_penerimaan
        $daftarField = array_keys($data);
        $where = ["kode" => $kode];
        $berhasilUbah = (new FarmasiModel)->saveData("transaksif_penerimaan", $daftarField, $data, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT IFNULL(MAX(A.tgl_hp), '-')
            FROM db1.relasif_hargaperolehan AS A
            WHERE
                A.kode_reff = :kodeRef
                AND A.sts_hja = 1
            LIMIT 1
        ";
        $params = [":kodeRef" => $kode];
        $tanggalMaks = $connection->createCommand($sql, $params)->queryScalar();

        // update keterangan pada relasif_hargaperolehan dan kartu ketersediaan
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.relasif_hargaperolehan AS A
            LEFT JOIN db1.transaksif_penerimaan AS T ON A.kode_reff = T.kode
            LEFT JOIN db1.masterf_pbf AS C ON T.id_pbf = C.id
            SET A.keterangan = CONCAT('Rev:', T.revisike, ' || Terima Pembelian dari Supplier ', C.nama_pbf)
            WHERE
                A.kode_reff = :kodeRef
                AND T.sts_updatekartu = 1
                AND A.tgl_hp = :tanggalHp
        ";
        $params = [":kodeRef" => $kode, ":tanggalHp" => $tanggalMaks];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Harga Perolehan", "Kode Reff", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.relasif_ketersediaan AS A
            LEFT JOIN db1.transaksif_penerimaan AS T ON A.kode_reff = T.kode
            LEFT JOIN db1.masterf_pbf AS C ON T.id_pbf = C.id
            SET
                A.no_doc = T.no_doc,
                A.keterangan = CONCAT('Rev:', T.revisike, ' || Terima Pembelian dari Supplier ', C.nama_pbf, ', pada tanggal ', T.tgl_doc)
            WHERE
                A.kode_reff = :kodeRef
                AND T.sts_updatekartu = 1
                AND A.kode_transaksi = 'T'
                AND A.tipe_tersedia = 'penerimaan'
        ";
        $params = [":kodeRef" => $kode];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Ketersediaan", "Kode Reff", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.relasif_hargaperolehan (
                kode_reff,      -- 1
                id_katalog,     -- 2
                jns_terima,     -- 3
                tgl_hp,         -- 4
                stok_hp,        -- 5
                tgl_aktifhp,    -- 6
                stokakum_hp,    -- 7
                hna_item,       -- 8
                diskon_item,    -- 9
                hp_item,        -- 10
                phja,           -- 11
                phjapb,         -- 12
                hja_item,       -- 13
                hjapb_item,     -- 14
                disjual_item,   -- 15
                disjualpb_item, -- 16
                hja_setting,    -- 17
                hjapb_setting,  -- 18
                sts_hja,        -- 19
                sts_hjapb,      -- 20
                sts_close,      -- 21
                userid_updt,    -- 22
                sysdate_updt,   -- 23
                keterangan      -- 24
            )
            SELECT
                A.kode_reff,                                      -- 1
                A.id_katalog,                                     -- 2
                A.jns_terima,                                     -- 3
                T.ver_tglrevisi,                                  -- 4
                B.jumlah_item,                                    -- 5
                tgl_aktifhp,                                      -- 6
                stokakum_hp,                                      -- 7
                B.hna_item,                                       -- 8
                B.diskon_item,                                    -- 9
                B.hp_item,                                        -- 10
                B.phja_item,                                      -- 11
                B.phjapb_item,                                    -- 12
                B.hja_item,                                       -- 13
                B.hjapb_item,                                     -- 14
                A.disjual_item,                                   -- 15
                A.disjualpb_item,                                 -- 16
                B.hja_item,                                       -- 17
                B.hjapb_item,                                     -- 18
                A.sts_hja,                                        -- 19
                A.sts_hjapb,                                      -- 20
                A.sts_close,                                      -- 21
                T.ver_usrrevisi,                                  -- 22
                T.ver_tglrevisi,                                  -- 23
                CONCAT('Revisi Harga dari Supplier ', C.nama_pbf) -- 24
            FROM db1.relasif_hargaperolehan AS A
            INNER JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.transaksif_penerimaan AS T ON A.kode_reff = T.kode
            LEFT JOIN db1.masterf_pbf AS C ON T.id_pbf = C.id
            WHERE
                A.kode_reff = :kodeRef
                AND A.id_katalog = B.id_katalog
                AND B.sts_revisiitem = 1
                AND A.tgl_hp = (
                    SELECT MAX(A.tgl_hp)
                    FROM db1.relasif_hargaperolehan AS A
                    WHERE A.kode_reff = :kodeRef
                )
        ";
        $params = [":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Harga Perolehan", $transaction);

        // jika harga berhasil dibackup dan diinputkan harga yang baru
        // ubah harga yang lama yg aktif jadi ga aktif. yang diaktifin yg hasil revisi
        if ($tanggalMaks != "-") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.relasif_hargaperolehan AS A
                SET
                    A.sts_hja = 0,
                    A.sts_hjapb = 0
                WHERE
                    A.kode_reff = :kodeRef
                    AND A.tgl_hp = :tanggalHp
                    AND A.sts_hja = 1
            ";
            $params = [":kodeRef" => $kode, ":tanggalHp" => $tanggalMaks];
            $connection->createCommand($sql, $params)->execute();
        }

        // LakukanInput Koreksi Ke Kartu Ketersediaan
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.relasif_ketersediaan (
                id_depo,          -- 1
                kode_reff,        -- 2
                no_doc,           -- 3
                ppn,              -- 4
                kode_stokopname,  -- 5
                tgl_adm,          -- 6
                tgl_transaksi,    -- 7
                bln_transaksi,    -- 8
                thn_transaksi,    -- 9
                kode_transaksi,   -- 10
                kode_store,       -- 11
                tipe_tersedia,    -- 12
                tgl_tersedia,     -- 13
                no_batch,         -- 14
                tgl_expired,      -- 15
                id_katalog,       -- 16
                id_pabrik,        -- 17
                id_kemasan,       -- 18
                isi_kemasan,      -- 19
                jumlah_sebelum,   -- 20
                jumlah_masuk,     -- 21
                jumlah_keluar,    -- 22
                jumlah_tersedia,  -- 23
                harga_netoapotik, -- 24
                harga_perolehan,  -- 25
                phja,             -- 26
                phja_pb,          -- 27
                harga_jualapotik, -- 28
                jumlah_item,      -- 29
                jumlah_kemasan,   -- 30
                harga_item,       -- 31
                harga_kemasan,    -- 32
                diskon_item,      -- 33
                status,           -- 34
                keterangan,       -- 35
                userid_last,      -- 36
                sysdate_last,     -- 37
                id_reff           -- not exist in original source (error supressor)
            )
            SELECT
                T.id_gudangpenyimpanan,             -- 1
                A.kode_reff,                        -- 2
                T.no_doc,                           -- 3
                T.ppn,                              -- 4
                C.kode,                             -- 5
                C.tgl_adm,                          -- 6
                :tanggalTransaksi,                  -- 7
                :bulan,                             -- 8
                :tahun,                             -- 9
                'K',                                -- 10
                D.kd_unit,                          -- 11
                'penerimaan',                       -- 12
                T.ver_tglrevisi,                    -- 13
                A.no_batch,                         -- 14
                A.tgl_expired,                      -- 15
                A.id_katalog,                       -- 16
                tT.id_pabrik,                       -- 17
                tT.id_kemasan,                      -- 18
                tT.isi_kemasan,                     -- 19
                B.jumlah_stokfisik,                 -- 20
                A.jumlah_item,                      -- 21
                0,                                  -- 22
                B.jumlah_stokfisik + A.jumlah_item, -- 23
                tT.hna_item,                        -- 24
                tT.hp_item,                         -- 25
                tT.phja_item,                       -- 26
                tT.phjapb_item,                     -- 27
                tT.hja_item,                        -- 28
                A.jumlah_item,                      -- 29
                A.jumlah_kemasan,                   -- 30
                tT.harga_item,                      -- 31
                tT.harga_kemasan,                   -- 32
                tT.diskon_item,                     -- 33
                1,                                  -- 34
                CONCAT('Koreksi Penerimaan no ', T.no_doc, ' dari Supplier ', E.nama_pbf, ', pada tanggal ', T.ver_tglrevisi),
                T.ver_usrrevisi,                    -- 36
                T.ver_tglrevisi,                    -- 37
                ''                                  -- not exist in original source (error supressor)
            FROM db1.tdetailf_penerimaanrinc AS A
            INNER JOIN db1.tdetailf_penerimaan AS tT ON A.kode_reff = tT.kode_reff
            INNER JOIN db1.transaksif_penerimaan AS T ON A.kode_reff = T.kode
            LEFT JOIN db1.transaksif_stokkatalog AS B ON B.id_depo = T.id_gudangpenyimpanan
            LEFT JOIN db1.transaksif_stokopname AS C ON T.id_gudangpenyimpanan = C.id_depo
            LEFT JOIN db1.masterf_depo AS D ON T.id_gudangpenyimpanan = D.id
            LEFT JOIN db1.masterf_pbf AS E ON T.id_pbf = E.id
            WHERE
                A.kode_reff = :kodeRef
                AND C.sts_aktif = 1
                AND A.id_katalog = B.id_katalog
                AND T.ver_revisi = 1
                AND tT.id_katalog = A.id_katalog
                AND tT.sts_revisiitem = 1
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":kodeRef" => $kode,
            ":tanggalTransaksi" => $todayValSystem,
        ];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Ketersediaan", $transaction);

        // lakukan revisi terhadap barang tersebut
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_stokkatalog (
                id_depo,          -- 1
                id_katalog,       -- 2
                jumlah_stokfisik, -- 3
                jumlah_stokadm,   -- 4
                status,           -- 5
                userid_in,        -- 6
                sysdate_in,       -- 7
                userid_updt,      -- 8
                keterangan,       -- 9
                check_sync,       -- not exist in original source (error supressor)
                id_kemasan        -- idem
            )
            SELECT
                T.id_gudangpenyimpanan,    -- 1
                tT.id_katalog,             -- 2
                tT.jumlah_item,            -- 3
                tT.jumlah_item,            -- 4
                1,                         -- 5
                :idVerifikator,            -- 6
                :tanggalVerifikasi,        -- 7
                :idVerifikator,            -- 8
                'Koreksi Stok Penerimaan', -- 9
                '',                        -- not exist in original source (error supressor)
                ''                         -- idem
            FROM db1.tdetailf_penerimaan AS tT
            INNER JOIN db1.transaksif_penerimaan AS T ON tT.kode_reff = T.kode
            WHERE
                tT.kode_reff = :kodeRef
                AND tT.sts_revisiitem = 1
            ON DUPLICATE KEY UPDATE
                jumlah_stokfisik = jumlah_stokfisik + tT.jumlah_item,
                jumlah_stokadm = jumlah_stokadm + tT.jumlah_item,
                userid_updt = :idVerifikator
        ";
        $params = [":idVerifikator" => $idUser, ":tanggalVerifikasi" => $todayValSystem, ":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Stok Katalog", $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.relasif_ketersediaan (
                id_depo,          -- 1
                kode_reff,        -- 2
                no_doc,           -- 3
                ppn,              -- 4
                kode_stokopname,  -- 5
                tgl_adm,          -- 6
                tgl_transaksi,    -- 7
                bln_transaksi,    -- 8
                thn_transaksi,    -- 9
                kode_transaksi,   -- 10
                kode_store,       -- 11
                tipe_tersedia,    -- 12
                tgl_tersedia,     -- 13
                no_batch,         -- 14
                tgl_expired,      -- 15
                id_katalog,       -- 16
                id_pabrik,        -- 17
                id_kemasan,       -- 18
                isi_kemasan,      -- 19
                jumlah_sebelum,   -- 20
                jumlah_masuk,     -- 21
                jumlah_keluar,    -- 22
                jumlah_tersedia,  -- 23
                harga_netoapotik, -- 24
                harga_perolehan,  -- 25
                phja,             -- 26
                phja_pb,          -- 27
                harga_jualapotik, -- 28
                jumlah_item,      -- 29
                jumlah_kemasan,   -- 30
                harga_item,       -- 31
                harga_kemasan,    -- 32
                diskon_item,      -- 33
                status,           -- 34
                keterangan,       -- 35
                userid_last,      -- 36
                sysdate_last,     -- 37
                id_reff           -- not exist in original source (error supressor)
            )
            SELECT
                T.id_gudangpenyimpanan,             -- 1
                A.kode_reff,                        -- 2
                T.no_doc,                           -- 3
                T.ppn,                              -- 4
                C.kode,                             -- 5
                C.tgl_adm,                          -- 6
                :tanggalTransaksi,                  -- 7
                :bulan,                             -- 8
                :tahun,                             -- 9
                'R',                                -- 10
                D.kd_unit,                          -- 11
                'penerimaan',                       -- 12
                T.ver_tglrevisi,                    -- 13
                A.no_batch,                         -- 14
                A.tgl_expired,                      -- 15
                A.id_katalog,                       -- 16
                tT.id_pabrik,                       -- 17
                tT.id_kemasan,                      -- 18
                tT.isi_kemasan,                     -- 19
                B.jumlah_stokfisik,                 -- 20
                0,                                  -- 21
                A.jumlah_item,                      -- 22
                B.jumlah_stokfisik - A.jumlah_item, -- 23
                tT.hna_item,                        -- 24
                tT.hp_item,                         -- 25
                tT.phja_item,                       -- 26
                tT.phjapb_item,                     -- 27
                tT.hja_item,                        -- 28
                A.jumlah_item,                      -- 29
                A.jumlah_kemasan,                   -- 30
                tT.harga_item,                      -- 31
                tT.harga_kemasan,                   -- 32
                tT.diskon_item,                     -- 33
                1,                                  -- 34
                CONCAT('Revisi Penerimaan no ', T.no_doc, ' dari Supplier ', E.nama_pbf, ', pada tanggal ', T.ver_tglrevisi),
                T.ver_usrrevisi,                    -- 36
                T.ver_tglrevisi,                    -- 37
                ''                                  -- not exist in original source (error supressor)
            FROM db1.tdetailf_revpenerimaanrinc AS A
            INNER JOIN db1.tdetailf_revpenerimaan AS tT ON A.kode_reff = tT.kode_reff
            INNER JOIN db1.transaksif_penerimaan AS T ON A.kode_reff = T.kode
            INNER JOIN db1.tdetailf_penerimaan AS tR ON A.kode_reff = tR.kode_reff
            LEFT JOIN db1.transaksif_stokkatalog AS B ON B.id_depo = T.id_gudangpenyimpanan
            LEFT JOIN db1.transaksif_stokopname AS C ON T.id_gudangpenyimpanan = C.id_depo
            LEFT JOIN db1.masterf_depo AS D ON T.id_gudangpenyimpanan = D.id
            LEFT JOIN db1.masterf_pbf AS E ON T.id_pbf = E.id
            WHERE
                A.kode_reff = :kodeRef
                AND C.sts_aktif = 1
                AND A.id_katalog = B.id_katalog
                AND tR.id_katalog = A.id_katalog
                AND tR.sts_revisiitem = 1
                AND T.ver_revisi = 1
                AND A.revisike = T.revisike - 1
                AND tT.id_katalog = A.id_katalog
                AND A.revisike = tT.revisike
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":kodeRef" => $kode,
            ":tanggalTransaksi" => $todayValSystem,
        ];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Ketersediaan", $transaction);

        // jika berhasil koreksi-revisi lakukan maka hapus status revisi di item nya
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.transaksif_stokkatalog (
                id_depo,          -- 1
                id_katalog,       -- 2
                jumlah_stokfisik, -- 3
                jumlah_stokadm,   -- 4
                status,           -- 5
                userid_in,        -- 6
                sysdate_in,       -- 7
                userid_updt,      -- 8
                keterangan,       -- 9
                check_sync,       -- not exist in original source (error supressor)
                id_kemasan        -- idem
            )
            SELECT
                T.id_gudangpenyimpanan,   -- 1
                tT.id_katalog,            -- 2
                -tT.jumlah_item,          -- 3
                -tT.jumlah_item,          -- 4
                1,                        -- 5
                :idVerifikator,           -- 6
                :tanggalVerifikasi,       -- 7
                :idVerifikator,           -- 8
                'Revisi Stok Penerimaan', -- 9
                '',                       -- not exist in original source (error supressor)
                ''                        -- idem
            FROM db1.tdetailf_revpenerimaan AS tT
            INNER JOIN db1.transaksif_penerimaan AS R ON tT.kode_reff = R.kode
            INNER JOIN db1.transaksif_revpenerimaan AS T ON tT.kode_reff = T.kode
            INNER JOIN db1.tdetailf_penerimaan AS tR ON tT.kode_reff = tR.kode_reff
            WHERE
                tT.kode_reff = :kodeRef
                AND tT.revisike = R.revisike - 1
                AND tT.id_katalog = tR.id_katalog
                AND tR.sts_revisiitem = 1
                AND tT.revisike = T.revisike
            ON DUPLICATE KEY UPDATE
                jumlah_stokfisik = jumlah_stokfisik - tT.jumlah_item,
                jumlah_stokadm = jumlah_stokadm - tT.jumlah_item,
                userid_updt = :idVerifikator
        ";
        $params = [":idVerifikator" => $idUser, ":tanggalVerifikasi" => $todayValSystem, ":kodeRef" => $kode];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilTambah) throw new FailToInsertException("Stok Katalog", $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.tdetailf_penerimaan
            SET sts_revisiitem = 0
            WHERE kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();
        if (!$berhasilUbah) throw new FailToUpdateException("Detail Penerimaan", "Kode Reff", $kode, $transaction);

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#doVerRevisigudang    the original method
     */
    public function actionVerRevisiGudangData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                         AS kode,
                A.revisike + 1                                 AS revisiKe,
                A.no_doc                                       AS noDokumen,
                A.no_faktur                                    AS noFaktur,
                A.no_suratjalan                                AS noSuratJalan,
                A.kode_reffpl                                  AS kodeRefPl,            -- in use
                A.kode_reffpo                                  AS kodeRefPo,            -- in use
                A.tgl_doc                                      AS tanggalDokumen,
                A.tipe_doc                                     AS tipeDokumen,          -- in use
                A.blnawal_anggaran                             AS bulanAwalAnggaran,
                A.blnakhir_anggaran                            AS bulanAkhirAnggaran,
                A.thn_anggaran                                 AS tahunAnggaran,
                A.id_gudangpenyimpanan                         AS idGudangPenyimpanan,
                A.sts_tabunggm                                 AS statusTabungGasMedis,
                A.ver_revterima                                AS verRevTerima,
                A.ver_revtglterima                             AS verRevTanggalTerima,
                A.ver_revisi                                   AS verRevisi,
                A.ver_tglrevisi                                AS verTanggalRevisi,
                B.no_doc                                       AS noPo,
                IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo)     AS tanggalTempoKirim,
                C.no_doc                                       AS noSpk,
                IFNULL(B.id_pbf, C.id_pbf)                     AS idPemasok,
                A.id_pbf                                       AS idPemasokOri,
                A.id_jenisanggaran                             AS idJenisAnggaranOri,
                IFNULL(B.id_jenisanggaran, C.id_jenisanggaran) AS idJenisAnggaran,
                A.id_sumberdana                                AS idSumberDanaOri,
                IFNULL(B.id_sumberdana, C.id_sumberdana)       AS idSumberDana,
                A.id_jenisharga                                AS idJenisHargaOri,
                IFNULL(B.id_jenisharga, C.id_jenisharga)       AS idJenisHarga,
                A.id_carabayar                                 AS idCaraBayarOri,
                IFNULL(B.id_carabayar, C.id_carabayar)         AS idCaraBayar,
                A.ppn                                          AS ppnOri,
                IFNULL(B.ppn, C.ppn)                           AS ppn,                  -- in use
                D.kode                                         AS kodePemasok,
                D.nama_pbf                                     AS namaPemasok,                                    
                Da.nama_pbf                                    AS namaPemasokOri,
                E.subjenis_anggaran                            AS subjenisAnggaran,
                G.jenis_harga                                  AS jenisHarga,
                H.sumber_dana                                  AS sumberDana,
                I.cara_bayar                                   AS caraBayar,
                IFNULL(UTRM.name, '-')                         AS namaUserTerima,
                IFNULL(UGDG.name, '-')                         AS namaUserGudang,
                A.sts_tabunggm                                 AS statusTabungGasMedis,                                
                IFNULL(B.ver_revisi, 0)                        AS verRevisiPo,
                IFNULL(C.ver_revisi, 0)                        AS verRevisiPl,
                C.sts_revisi                                   AS statusRevisiPl
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON D.id = IFNULL(B.id_pbf, C.id_pbf)
            LEFT JOIN db1.masterf_pbf AS Da ON Da.id = A.id_pbf
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.masterf_sumberdana AS H ON A.id_sumberdana = H.id
            LEFT JOIN db1.masterf_carabayar AS I ON A.id_carabayar = I.id
            LEFT JOIN db1.user AS UTRM ON A.ver_revusrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrrevisi = UGDG.id
            WHERE
                A.kode = :kode
                AND A.ver_gudang = 1
                AND A.ver_akuntansi = 0
                AND A.sts_revisi = 1
                AND A.ver_revterima = 1
                AND A.ver_revisi = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$penerimaan) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.kode_reffrenc              AS kodeRefRencana,
                B.kode_reffpo                AS kodeRefPo,
                B.kode_reffro                AS kodeRefRo,
                B.kode_reffpl                AS kodeRefPl,
                B.kode_refftrm               AS kodeRefTerima,
                B.kode_reffkons              AS kodeRefKons,
                B.id_reffkatalog             AS idRefKatalog,
                B.kemasan                    AS kemasan,
                B.id_pabrik                  AS idPabrik,
                B.id_kemasan                 AS idKemasan,
                B.isi_kemasan                AS isiKemasan,
                B.id_kemasandepo             AS idKemasanDepo,
                B.jumlah_item                AS jumlahItem,
                B.jumlah_kemasan             AS jumlahKemasan,
                B.jumlah_itembonus           AS jumlahItemBonus,
                B.harga_item                 AS hargaItem,
                B.harga_kemasan              AS hargaKemasan,
                B.diskon_item                AS diskonItem,
                B.diskon_harga               AS diskonHarga,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.hppb_item                  AS hpPbItem,
                B.phja_item                  AS phjaItem,
                B.phjapb_item                AS phjaPbItem,
                B.hja_item                   AS hjaItem,
                B.hjapb_item                 AS hjaPbItem,
                B.sts_revisiitem             AS statusRevisiItem,
                B.keterangan                 AS keterangan,
                B.userid_updt                AS useridUpdate,
                B.sysdate_updt               AS sysdateUpdate,
                A.kode_reff                  AS kodeRef,
                A.id_katalog                 AS idKatalog,
                A.no_reffbatch               AS noRefBatch,
                A.no_batch                   AS noBatch,
                IFNULL(A.tgl_expired, '')    AS tanggalKadaluarsa,
                A.no_urut                    AS noUrut,
                A.jumlah_item                AS jumlahItem,
                A.jumlah_kemasan             AS jumlahKemasan,
                A.id                         AS id,
                K.nama_sediaan               AS namaSediaan,
                PBK.nama_pabrik              AS namaPabrik,
                KEM1.kode                    AS satuanJual,
                KEM2.kode                    AS satuan,
                K1.kode                      AS satuanJualKat,
                K2.kode                      AS satuanKat,
                K.kemasan                    AS kemasanKat,
                K.isi_kemasan                AS isiKemasanKat,
                K.id_kemasanbesar            AS idKemasanKat,
                K.id_kemasankecil            AS idKemasanDepoKat,
                K.harga_beli                 AS hargaItemKat,
                K.harga_beli * K.isi_kemasan AS hargaKemasanKat,
                IFNULL(tR.jumlah_item, 0)    AS jumlahRencana,
                IFNULL(tH.jumlah_item, 0)    AS jumlahHps,
                C.jumlah_item                AS jumlahPl,
                IFNULL(tRo.jumlah_item, 0)   AS jumlahRo,
                IFNULL(D.jumlah_item, 0)     AS jumlahDo,
                IFNULL(T_pl.jumlah_item, 0)  AS jumlahTerimaPl,
                IFNULL(T_po.jumlah_item, 0)  AS jumlahTerimaPo,
                IF(IFNULL(B.kode_reffpo, 0) = 0, IFNULL(T_pl.jumlah_item, 0), T_po.jumlah_item)       AS jumlahTerima,
                IF(IFNULL(Rt_po.kode_reffpo, 0) = 0, IFNULL(Rt_pl.jumlah_item, 0), Rt_po.jumlah_item) AS jumlahRetur
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KEM1 ON KEM1.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KEM2 ON KEM2.id = B.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS K1 ON K1.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS K2 ON K2.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_pembelian AS C ON B.kode_reffpl = C.kode_reff
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON C.id_reffkatalog = tH.id_reffkatalog
            LEFT JOIN db1.tdetailf_perencanaan AS tR ON C.id_reffkatalog = tR.id_katalog
            LEFT JOIN db1.tdetailf_pemesanan AS D ON B.kode_reffpo = D.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS tRo ON B.id_katalog = tRo.id_katalog
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS T_po ON D.kode_reff = T_po.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS T_pl ON C.kode_reff = T_pl.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpo      AS kode_reffpo,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpo, A.id_katalog
            ) AS Rt_po ON D.kode_reff = Rt_po.kode_reffpo
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS Rt_pl ON C.kode_reff = Rt_pl.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND B.id_katalog = Rt_pl.id_katalog
                AND B.id_katalog = Rt_po.id_katalog
                AND B.id_katalog = T_pl.id_katalog
                AND B.id_katalog = T_po.id_katalog
                AND B.kode_reffro = tRo.kode_reff
                AND B.id_katalog = D.id_katalog
                AND C.kode_reffrenc = tR.kode_reff
                AND C.kode_reffhps = tH.kode_reff
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
        ";
        $params = [":kode" => $kode];
        $daftarRincianDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        // Jika Penerimaan bukan dari Tarik data dari SP/SPK/Kontrak
        if ($penerimaan->tipeDokumen != 0) {
            // TODO: php: uncategorized: move logic to view
            $penerimaan2 = $penerimaan->getArrayCopy();
            $penerimaan2["sisa_anggaran"] = 0;
            $penerimaan2["checkedppn"] = ($penerimaan->ppn == 10) ? "checked" : "";

            return json_encode([
                "daftarRincianDetailPenerimaan" => $daftarRincianDetailPenerimaan,
                "action" => "verif_gudang",
                "judulHeading" => "Revisi Jumlah/Harga/Diskon/PPN Penerimaan",
                "penerimaan2" => $penerimaan,
            ]);

        } else {
            if ($penerimaan->kodeRefPo) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        A.kode_reff        AS kodeRef,
                        A.id_katalog       AS idKatalog,
                        A.kode_reffrenc    AS kodeRefRencana,
                        A.kode_reffpl      AS kodeRefPl,
                        A.kode_reffro      AS kodeRefRo,
                        A.id_reffkatalog   AS idRefKatalog,
                        A.kemasan          AS kemasan,
                        A.id_pabrik        AS idPabrik,
                        A.id_kemasan       AS idKemasan,
                        A.isi_kemasan      AS isiKemasan,
                        A.id_kemasandepo   AS idKemasanDepo,
                        A.jumlah_item      AS jumlahItem,
                        A.jumlah_kemasan   AS jumlahKemasan,
                        A.jumlah_realisasi AS jumlahRealisasi,
                        A.harga_item       AS hargaItem,
                        A.harga_kemasan    AS hargaKemasan,
                        A.diskon_item      AS diskonItem,
                        A.diskon_harga     AS diskonHarga,
                        A.keterangan       AS keterangan,
                        A.userid_updt      AS useridUpdate,
                        A.sysdate_updt     AS sysdateUpdate,
                        Sj.kode            AS satuanJual,
                        S.kode             AS satuan
                    FROM db1.tdetailf_pemesanan AS A
                    LEFT JOIN db1.masterf_kemasan AS Sj ON A.id_kemasan = Sj.id
                    LEFT JOIN db1.masterf_kemasan AS S ON A.id_kemasandepo = S.id
                    WHERE A.kode_reff = :kodeRef
                ";
                $params = [":kodeRef" => $penerimaan->kodeRefPo];

            } else {
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
                $params = [":kodeRef" => $penerimaan->kodeRefPl];
            }
            $daftarDetailX = $connection->createCommand($sql, $params)->queryAll();

            $daftarDetailX2 = [];
            array_walk($daftarDetailX, fn($item) => $daftarDetailX2[$item->idKatalog] = $item);

            return json_encode([
                "judulHeading" => "Verifikasi Revisi Gudang",
                "daftarRincianDetailPenerimaan" => $daftarRincianDetailPenerimaan,
                "daftarDetailX2" => $daftarDetailX2,
                "action" => "verif_gudang",
                "penerimaan3" => $penerimaan3,
                "stsTabungGasMedis" => $stsTabungGasMedis,
            ]);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#reports    the original method
     */
    public function actionReportRekap(): string
    {
        [   "filter_tanggal" => $fieldTanggal,
            "tgl_awal" => $tanggalAwal,
            "tgl_akhir" => $tanggalAkhir,
            "format" => $format,
            "verifikasi" => $verifikasi, // NOTE: change "-" to ""
        ] = Yii::$app->request->post();
        $fieldVerif = Yii::$app->request->post($format);

        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                                                 AS idKatalog,
                C.nama_sediaan                                                               AS namaSediaan,      -- in use
                D.nama_pabrik                                                                AS namaPabrik,       -- in use
                E.kode                                                                       AS satuan,
                F.subjenis_anggaran                                                          AS subjenisAnggaran,
                G.kode                                                                       AS kodeKelompok,     -- in use
                G.kelompok_barang                                                            AS kelompokBarang,   -- in use
                SUM(A.jumlah_item)                                                           AS totalItem,
                ROUND(SUM(A.harga_setelahdiskon), 2)                                         AS totalHarga,       -- in use
                ROUND(SUM(A.harga_setelahdiskon * A.ppn / 100), 2)                           AS totalPpn,         -- in use
                ROUND(SUM(A.harga_setelahdiskon + (A.harga_setelahdiskon * A.ppn / 100)), 2) AS totalHargaAkhir
            FROM (
                SELECT
                    A.id_katalog,
                    A.jumlah_item,
                    ((A.jumlah_item * A.harga_item) - (A.jumlah_item * A.harga_item * A.diskon_item / 100)) AS harga_setelahdiskon,
                    B.ppn
                FROM db1.tdetailf_penerimaan AS A
                INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND B.sts_testing = 0
                    AND $fieldTanggal >= :tanggalAwal
                    AND $fieldTanggal <= :tanggalAkhir
                    AND (:verifikasi = '' OR $fieldVerif = :verifikasi)
            ) AS A
            LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
            LEFT JOIN db1.masterf_pabrik AS D ON C.id_pabrik = D.id
            LEFT JOIN db1.masterf_kemasan AS E ON C.id_kemasankecil = E.id
            LEFT JOIN db1.masterf_subjenisanggaran AS F ON C.id_jenisbarang = F.id
            LEFT JOIN db1.masterf_kelompokbarang AS G ON C.id_kelompokbarang = G.id
            GROUP BY A.id_katalog
            ORDER BY
                C.id_kelompokbarang,
                A.id_katalog
        ";
        $params = [
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
            ":verifikasi" => $verifikasi,
        ];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $totalJumlah = 0;
        $totalPpn = 0;
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $kodeSaatIni = "";
        $barisPerHalaman = 50;

        foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
            $kode = $dPenerimaan->kodeKelompok;

            if ($kodeSaatIni != $kode) {
                $kodeSaatIni = $kode;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_kelompok" => $dPenerimaan->kelompokBarang,
                    "subtotal_jumlah" => 0,
                    "subtotal_ppn" => 0,
                    "subtotal_nilai" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul][$bJudul]["subtotal_jumlah"] += $dPenerimaan->totalHarga;
            $daftarHalaman[$hJudul][$bJudul]["subtotal_ppn"] += $dPenerimaan->totalPpn;
            $daftarHalaman[$hJudul][$bJudul]["subtotal_nilai"] += $dPenerimaan->totalHarga + $dPenerimaan->totalPpn;

            $totalJumlah += $dPenerimaan->totalHarga;
            $totalPpn += $dPenerimaan->totalPpn;
            $totalNilai += $dPenerimaan->totalHarga + $dPenerimaan->totalPpn;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportRekapitulasi(
            daftarHalaman:          $daftarHalaman,
            tanggalAwal:            $tanggalAwal,
            tanggalAkhir:           $tanggalAkhir,
            daftarDetailPenerimaan: $daftarDetailPenerimaan,
            jumlahHalaman:          count($daftarHalaman),
            totalJumlah:            $totalJumlah,
            totalPpn:               $totalPpn,
            totalNilai:             $totalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#reports    the original method
     */
    public function actionReportBukuInduk(): string
    {
        [   "filter_tanggal" => $fieldTanggal,
            "tgl_awal" => $tanggalAwal,
            "tgl_akhir" => $tanggalAkhir,
            "format" => $format,
            "verifikasi" => $verifikasi, // NOTE: change "-" to ""
        ] = Yii::$app->request->post();
        $fieldVerif = Yii::$app->request->post($format);

        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode             AS kodeTerima,       -- in use
                B.no_doc           AS noDokumen,        -- in use
                B.tgl_doc          AS tanggalDokumen,
                B.ver_terima       AS verTerima,
                B.ver_tglterima    AS verTanggalTerima, -- in use
                B.ver_gudang       AS verGudang,
                B.ver_tglgudang    AS verTanggalGudang, -- in use
                B.ppn              AS ppn,
                B.nilai_total      AS nilaiTotal,
                B.nilai_diskon     AS nilaiDiskon,
                B.nilai_ppn        AS nilaiPpn,
                B.nilai_pembulatan AS nilaiPembulatan,
                B.nilai_akhir      AS nilaiAkhir,
                C.nama_pbf         AS namaPemasok,      -- in use
                A.id_katalog       AS idKatalog,
                D.nama_sediaan     AS namaSediaan,
                E.nama_pabrik      AS namaPabrik,
                A.jumlah_item      AS jumlahItem,       -- in use
                A.jumlah_kemasan   AS jumlahKemasan,
                A.harga_kemasan    AS hargaKemasan,
                F.kode             AS satuan,
                A.harga_item       AS hargaItem,        -- in use
                A.diskon_item      AS diskonItem        -- in use
            FROM db1.tdetailf_penerimaan AS A
            INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_katalog AS D ON A.id_katalog = D.kode
            LEFT JOIN db1.masterf_pabrik AS E ON D.id_pabrik = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON D.id_kemasankecil = F.id
            WHERE
                B.sts_deleted = 0
                AND B.sts_testing = 0
                AND $fieldTanggal >= :tanggalAwal
                AND $fieldTanggal <= :tanggalAkhir
                AND (:verifikasi = '' OR $fieldVerif = :verifikasi)
            ORDER BY B.ver_tglterima ASC, B.tgl_doc ASC
        ";
        $params = [
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
            ":verifikasi" => $verifikasi,
        ];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalJumlah = 0;
        $grandTotalPpn = 0;
        $grandTotalNilai = 0;

        $jumlahData = count($daftarDetailPenerimaan);
        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $kodeTerimaSaatIni = "";
        $barisPerHalaman = 44;
        $maksHurufBarang = 36;
        $maksHurufPabrik = 16;

        foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
            $kodeTerima = $dPenerimaan->kodeTerima;

            if ($kodeTerimaSaatIni != $kodeTerima) {
                $kodeTerimaSaatIni = $kodeTerima;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "kode_terima" => $dPenerimaan->kodeTerima,
                    "no_doc" => $dPenerimaan->noDokumen,
                    "ver_tglterima" => $dPenerimaan->verTanggalTerima,
                    "ver_tglgudang" => $dPenerimaan->verTanggalGudang,
                    "nama_pbf" => $dPenerimaan->namaPemasok,
                    "total_jumlah" => 0,
                    "total_ppn" => 0,
                    "total_nilai" => 0,
                ];

                $jumlahBarisBarang = ceil(strlen($dPenerimaan->namaSediaan) / $maksHurufBarang);
                $jumlahBarisPabrik = ceil(strlen($dPenerimaan->namaPabrik) / $maksHurufPabrik);
                $butuhBaris = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

                if (($b + $butuhBaris) > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $jum = $dPenerimaan->hargaItem * $dPenerimaan->jumlahItem;
            $jumlah = $jum - ($dPenerimaan->diskonItem * $jum / 100);
            $ppn = $jumlah * $dPenerimaan->ppn / 100;

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
                "subtotal_jumlah" => $jumlah,
                "subtotal_ppn" => $ppn,
                "subtotal_nilai" => $jumlah + $ppn,
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_jumlah"] += $jumlah;
            $daftarHalaman[$hJudul][$bJudul]["total_ppn"] += $ppn;
            $daftarHalaman[$hJudul][$bJudul]["total_nilai"] += $jumlah + $ppn;

            $grandTotalJumlah += $jumlah;
            $grandTotalPpn += $ppn;
            $grandTotalNilai += $jumlah + $ppn;

            if ($i + 1 == $jumlahData) break;
            $dataBerikutnya = $daftarDetailPenerimaan[$i + 1];
            $bedaJudul = $kodeTerima != $dataBerikutnya->kodeTerima;

            $jumlahBarisBarang = ceil(strlen($dataBerikutnya->namaSediaan) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($dataBerikutnya->namaPabrik) / $maksHurufPabrik);
            $butuhBaris = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

            if ($bedaJudul and $b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } elseif (($b + $butuhBaris) > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportBukuInduk(
            penerimaanReportWidgetId: Yii::$app->actionToId([LaporanPenerimaanUiController::class, "actionFormPenerimaan"]),
            daftarHalaman:            $daftarHalaman,
            tanggalAwal:              $tanggalAwal,
            tanggalAkhir:             $tanggalAkhir,
            daftarDetailPenerimaan:   $daftarDetailPenerimaan,
            jumlahHalaman:            count($daftarHalaman),
            grandTotalJumlah:         $grandTotalJumlah,
            grandTotalPpn:            $grandTotalPpn,
            grandTotalNilai:          $grandTotalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-cf94ce6
     */
    public function actionSearchJsonDetailRetur(): string
    {
        ["kode_reff_not" => $kodeRefNot, "kode_reff" => $kodeRef] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                B.id_katalog                 AS idKatalog, -- form, add2
                B.kode_reffpo                AS kodeRefPo, -- form
                B.kode_reffpl                AS kodeRefPl, -- form
                B.kode_refftrm               AS kodeRefTerima, -- form
                B.id_reffkatalog             AS idRefKatalog, -- form
                B.kemasan                    AS kemasan, -- form
                B.id_pabrik                  AS idPabrik, -- form
                B.id_kemasan                 AS idKemasan, -- form
                B.isi_kemasan                AS isiKemasan, -- form, add2
                B.id_kemasandepo             AS idKemasanDepo, -- form
                B.jumlah_item                AS jumlahItem, -- form, add2
                B.harga_item                 AS hargaItem, -- form, add2
                B.harga_kemasan              AS hargaKemasan, -- form, add2
                B.diskon_item                AS diskonItem, -- form, add2
                A.no_batch                   AS noBatch, -- form
                IFNULL(A.tgl_expired, '')    AS tanggalKadaluarsa, -- form
                A.no_urut                    AS noUrut, -- form, add2
                B.jumlah_item                AS jumlahItemTotal, -- form, add2
                K.nama_sediaan               AS namaSediaan, -- form
                K.isi_kemasan                AS isiKemasanKat, -- form
                K.id_kemasanbesar            AS idKemasanKat, -- form
                K.id_kemasankecil            AS idKemasanDepoKat, -- form
                K.kemasan                    AS kemasanKat, -- form
                K.harga_beli * K.isi_kemasan AS hargaKemasanKat, -- form
                PBK.nama_pabrik              AS namaPabrik, -- form
                Sj.kode                      AS satuanJual, -- form, add2
                S.kode                       AS satuan, -- form, add2
                Sj2.kode                     AS satuanJualKat, -- form
                S2.kode                      AS satuanKat, -- form
                IFNULL(C.jumlah_item, 0)     AS jumlahRetur -- form
            FROM db1.tdetailf_penerimaanrinc AS A
            LEFT JOIN db1.tdetailf_penerimaan AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS Sj ON Sj.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS S ON S.id = B.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS Sj2 ON Sj2.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS S2 ON S2.id = K.id_kemasankecil
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND (:kodeRefNot = '' OR A.kode_reff != :kodeRefNot)
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS C ON A.kode_reff = C.kode_refftrm
            WHERE
                A.kode_reff = :kodeRef
                AND A.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kodeRef" => $kodeRef, ":kodeRefNot" => $kodeRefNot];
        $daftarXDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarXDetailPenerimaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-cf94ce6
     */
    public function actionSearchJsonPbf(): string
    {
        ["nama_pbf" => $namaPemasok] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                PBF.nama_pbf                       AS namaPemasok,
                PBF.kode                           AS kodePemasok,
                IFNULL(TRM.kode, '-')              AS kode,
                IFNULL(TRM.no_doc, '-')            AS noDokumen,
                IFNULL(TRM.blnawal_anggaran, '-')  AS bulanAwalAnggaran,
                IFNULL(TRM.blnakhir_anggaran, '-') AS bulanAkhirAnggaran,
                IFNULL(TRM.thn_anggaran, '-')      AS tahunAnggaran,
                TRM.id_jenisanggaran               AS idJenisAnggaran,
                TRM.tipe_doc                       AS tipeDokumen,
                IFNULL(BLI.kode, '-')              AS kodeRefPl,
                IFNULL(PSN.kode, '-')              AS kodeRefPo,
                TRM.id_sumberdana                  AS idSumberDana,
                TRM.id_subsumberdana               AS idSubsumberDana,
                TRM.id_jenisharga                  AS idJenisHarga,
                TRM.id_carabayar                   AS idCaraBayar,
                TRM.ppn                            AS ppn,
                TRM.ver_gudang                     AS verGudang,
                TRM.id_gudangpenyimpanan           AS idGudangPenyimpanan,
                BLI.no_doc                         AS noSpk,
                BLI.tipe_doc                       AS tipeSpk,
                BLI.blnawal_anggaran               AS bulanAwalAnggaranPl,
                BLI.blnakhir_anggaran              AS bulanAkhirAnggaranPl,
                BLI.thn_anggaran                   AS tahunAnggaranPl,
                PSN.no_doc                         AS noPo,
                PSN.blnawal_anggaran               AS bulanAwalAnggaranPo,
                PSN.blnakhir_anggaran              AS bulanAkhirAnggaranPo,
                PSN.thn_anggaran                   AS tahunAnggaranPo,
                IFNULL(SJA.subjenis_anggaran, '-') AS subjenisAnggaran,
                IFNULL(JHG.jenis_harga, '-')       AS jenisHarga
            FROM db1.transaksif_penerimaan AS TRM
            LEFT JOIN db1.transaksif_pembelian AS BLI ON TRM.kode_reffpl = BLI.kode
            LEFT JOIN db1.transaksif_pemesanan AS PSN ON TRM.kode_reffpo = PSN.kode
            LEFT JOIN db1.masterf_pbf AS PBF ON TRM.id_pbf = PBF.id
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON TRM.id_jenisanggaran = SJA.id
            LEFT JOIN db1.masterf_jenisharga AS JHG ON TRM.id_jenisharga = JHG.id
            WHERE
                PBF.nama_pbf = :namaPemasok
                AND TRM.sts_deleted = 0
                AND TRM.ver_gudang = 1
            ORDER BY PBF.nama_pbf ASC
            LIMIT 30
        ";
        $params = [":namaPemasok" => $namaPemasok];
        $daftarPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPenerimaan);
    }

    /**
     * TODO: php: uncategorized: to be deleted
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-cf94ce6
     */
    public function actionGetJsonLainnya(): string
    {
        [
            "no_faktur" => $noFaktur,
            "no_suratjalan" => $noSuratJalan,
            "kode_not" => $kodeNot
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_penerimaan AS TRM
            WHERE
                (:noFaktur = '' OR TRM.no_faktur = :noFaktur)
                AND (:noSuratJalan = '' OR TRM.no_suratjalan = :noSuratJalan)
                AND (:kode = '' OR TRM.kode != :kode)
        ";
        $params = [":noFaktur" => $noFaktur, ":noSuratJalan" => $noSuratJalan, ":kode" => $kodeNot];
        $totalPenerimaan = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode($totalPenerimaan);
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-cf94ce6
     */
    public function actionCekUnik(): string
    {
        // TODO: php: uncategorized: finish this
        return "";
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-cf94ce6
     */
    public function actionSearchJsonLainnya(): string
    {
        [
            "ver_terima" => $verTerima,
            "ver_gudang" => $verGudang,
            "ver_akuntansi" => $verAkuntansi,
            "sts_revisi" => $statusRevisi,
            "no_doc" => $noDokumen,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                TRM.kode                 AS kode, -- form, add2
                TRM.kode_reffpl          AS kodeRefPl, -- form, add2
                TRM.kode_reffpo          AS kodeRefPo, -- from, add2
                TRM.tipe_doc             AS tipeDokumen, -- form
                TRM.no_doc               AS noDokumen, -- form, add2
                TRM.blnawal_anggaran     AS bulanAwalAnggaran, -- form, add2
                TRM.blnakhir_anggaran    AS bulanAkhirAnggaran, -- form, add2
                TRM.thn_anggaran         AS tahunAnggaran, -- form, add2
                TRM.id_jenisanggaran     AS idJenisAnggaran, -- form, add2
                TRM.id_sumberdana        AS idSumberDana, -- form, add2
                TRM.id_jenisharga        AS idJenisHarga, -- form, add2
                TRM.id_carabayar         AS idCaraBayar, -- form, add2
                TRM.ppn                  AS ppn, -- form, add2
                TRM.id_pbf               AS idPemasok, -- form, add2
                IFNULL(BLI.no_doc, '-')  AS noSpk, -- form, add2
                IFNULL(PSN.no_doc, '-')  AS noPo, -- form, add2
                BLI.blnawal_anggaran     AS bulanAwalAnggaranPl, -- form, add2
                BLI.blnakhir_anggaran    AS bulanAkhirAnggaranPl, -- form, add2
                BLI.thn_anggaran         AS tahunAnggaranPl, -- form, add2
                PSN.blnawal_anggaran     AS bulanAwalAnggaranPo, -- form, add2
                PSN.blnakhir_anggaran    AS bulanAkhirAnggaranPo, -- form, add2
                PSN.thn_anggaran         AS tahunAnggaranPo, -- form, add2
                TRM.nilai_akhir          AS nilaiAkhir, -- add2
                TRM.ver_gudang           AS verGudang, -- form
                PBF.nama_pbf             AS namaPemasok, -- form, add2
                PBF.kode                 AS kodePemasok, -- form, add2
                SJA.subjenis_anggaran    AS subjenisAnggaran, -- form, add2
                JHR.jenis_harga          AS jenisHarga, -- form
                TRM.id_gudangpenyimpanan AS idGudangPenyimpanan, -- form
                TRM.kode_refftrm         AS kodeRefTerima, -- add2
                BLI.tgl_jatuhtempo       AS tanggalJatuhTempo -- add2 
            FROM db1.transaksif_penerimaan AS TRM
            LEFT JOIN db1.transaksif_pembelian AS BLI ON TRM.kode_reffpl = BLI.kode
            LEFT JOIN db1.transaksif_pemesanan AS PSN ON TRM.kode_reffpo = PSN.kode
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON TRM.id_jenisanggaran = SJA.id
            LEFT JOIN db1.masterf_jenisharga AS JHR ON TRM.id_jenisharga = JHR.id
            LEFT JOIN db1.masterf_pbf AS PBF ON TRM.id_pbf = PBF.id
            WHERE
                TRM.no_doc LIKE :noDokumen
                AND (:verTerima = '' OR TRM.ver_terima = :verTerima)
                AND (:verGudang = '' OR TRM.ver_gudang = :verGudang)
                AND (:verAkuntansi = '' OR TRM.ver_akuntansi = :verAkuntansi)
                AND (:statusRevisi = '' OR  TRM.sts_revisi = :statusRevisi)
                AND TRM.sts_deleted = 0
            ORDER BY TRM.no_doc ASC
            LIMIT 30
        ";
        $params = [
            ":noDokumen" => "%$noDokumen%",
            ":verTerima" => $verTerima,
            ":verGudang" => $verGudang,
            ":verAkuntansi" => $verAkuntansi,
            ":statusRevisi" => $statusRevisi,
        ];
        $daftarPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPenerimaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#getUpdateTrn the original method
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
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#ajaxDelete    the original method
     */
    public function actionAjaxDelete(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        ["keterangan" => $keterangan, "kode" => $kode] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_penerimaan
            SET
                no_doc = kode,
                keterangan = CONCAT('Hapus Penerimaan dengan No: ', :keterangan),
                sts_deleted = 1,
                sysdate_del = :tanggal
            WHERE
                kode = :kode
                AND sts_deleted = 0
                AND sts_linked = 0
        ";
        $params = [":keterangan" => $keterangan, ":tanggal" => $nowValSystem, ":kode" => $kode];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilUbah);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#ajaxPermission    the original method
     */
    public function actionAjaxPermission(): string
    {
        ["keterangan_rev" => $keterangan, "kode" => $kode] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_penerimaan
            SET
                keterangan_rev = :keterangan,
                sts_izinrevisi = 1,
                ver_tglizinrevisi = :tanggal,
                ver_usrizinrevisi = :idUser,
                sts_revisi = 1,
                sysdate_rev = :tanggal
            WHERE
                kode = :kode
                AND sts_deleted = 0
                AND ver_terima = 1
                AND sts_izinrevisi = 0
                AND sts_revisi = 0
                AND ver_gudang = 1
                AND ver_akuntansi = 0
        ";
        $params = [
            ":keterangan" => "Izin Revisi oleh Gudang: " . $keterangan,
            ":kode" => $kode,
            ":tanggal" => Yii::$app->dateTime->nowVal("system"),
            ":idUser" => Yii::$app->userFatma->id
        ];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilUbah);
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
                FROM db1.transaksif_penerimaan
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
                FROM db1.transaksif_penerimaan
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
