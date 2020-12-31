<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\KonsinyasiUiController as Pair;
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
use yii\web\Response;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class KonsinyasiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "proceed" => $proceed,
            "noDokumen" => $noDokumen,
            "tanggalDokumen" => $tanggalDokumen,
            "fakturSuratJalan" => $fakturSuratJalan,
            "namaPemasok" => $namaPemasok,
            "caraBayar" => $caraBayar,
            "kodeJenis" => $kodeJenis,
            "bulanAnggaran" => $bulanAnggaran,
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
            SELECT -- all are in use, confirmed with view file.
                A.kode              AS kodeKonsinyasi,
                A.no_doc            AS noDokumen,
                A.tgl_doc           AS tanggalDokumen,
                A.sts_closed        AS statusClosed,
                A.blnawal_anggaran  AS bulanAwalAnggaran,
                A.blnakhir_anggaran AS bulanAkhirAnggaran,
                A.thn_anggaran      AS tahunAnggaran,
                F.cara_bayar        AS caraBayar,
                H.nama_pbf          AS namaPemasok,
                A.no_faktur         AS noFaktur,
                A.no_suratjalan     AS noSuratJalan,
                A.nilai_akhir       AS nilaiAkhir,
                A.sysdate_in        AS sysdateInput,
                A.ver_tglkendali    AS verTanggalKendali,
                A.ver_kendali       AS verKendali,
                UINP.name           AS namaUserInput,
                UKDL.name           AS namaUserKendali,
                B.kode              AS kodeJenis
            FROM db1.transaksif_konsinyasi AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
            LEFT JOIN db1.user AS UINP ON A.userid_in = UINP.id
            LEFT JOIN db1.user AS UKDL ON A.ver_usrkendali = UKDL.id
            WHERE
                A.sts_deleted = 0
                AND (:proceed = '' OR A.ver_kendali LIKE :proceed OR A.sts_closed LIKE :proceed)
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:tanggalDokumen = '' OR A.tgl_doc = :tanggalDokumen)
                AND (:fakturSuratJalan = '' OR A.no_faktur LIKE :fakturSuratJalan OR A.no_suratjalan LIKE :fakturSuratJalan)
                AND (:namaPemasok = '' OR H.nama_pbf LIKE :namaPemasok)
                AND (:caraBayar = '' OR F.cara_bayar LIKE :caraBayar)
                AND (:kodeJenis = '' OR B.kode LIKE :kodeJenis)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
            ORDER BY A.no_doc DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":proceed" => $proceed ? "%$proceed%" : "",
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":tanggalDokumen" => $tanggalDokumen ? $toSystemDate($tanggalDokumen) : "",
            ":fakturSuratJalan" => $fakturSuratJalan ? "%$fakturSuratJalan%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
            ":caraBayar" => $caraBayar ? "%$caraBayar%" : "",
            ":kodeJenis" => $kodeJenis ? "%$kodeJenis%" : "",
            ":bulanAnggaran" => $bulanAnggaran,
            ":tahunAnggaran" => $tahunAnggaran,
        ];
        $daftarKonsinyasi = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_konsinyasi AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
            LEFT JOIN db1.user AS UINP ON A.userid_in = UINP.id
            LEFT JOIN db1.user AS UKDL ON A.ver_usrkendali = UKDL.id
            WHERE
                A.sts_deleted = 0
                AND (:proceed = '' OR A.ver_kendali LIKE :proceed OR A.sts_closed LIKE :proceed)
                AND (:noDokumen = '' OR A.no_doc LIKE :noDokumen)
                AND (:tanggalDokumen = '' OR A.tgl_doc = :tanggalDokumen)
                AND (:fakturSuratJalan = '' OR A.no_faktur LIKE :fakturSuratJalan OR A.no_suratjalan LIKE :fakturSuratJalan)
                AND (:namaPemasok = '' OR H.nama_pbf LIKE :namaPemasok)
                AND (:caraBayar = '' OR F.cara_bayar LIKE :caraBayar)
                AND (:kodeJenis = '' OR B.kode LIKE :kodeJenis)
                AND (:bulanAnggaran = '' OR A.blnawal_anggaran = :bulanAnggaran OR A.blnakhir_anggaran = :bulanAnggaran)
                AND (:tahunAnggaran = '' OR A.thn_anggaran = :tahunAnggaran)
        ";
        $jumlahKonsinyasi = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode([
            "recordsFiltered" => $jumlahKonsinyasi,
            "data" => $daftarKonsinyasi
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#add    the original method
     */
    public function actionSaveAdd(): void
    {
        [   "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
            "tipe_doc" => $tipeDokumen,
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
            "id_depotujuan" => $idDepoTujuan,
            "keterangan" => $keterangan,
            "action" => $action,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_item" => $daftarJumlahItem,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "diskon_item" => $daftarDiskonItem,
            "harga_item" => $daftarHargaItem,
            "harga_kemasan" => $daftarHargaKemasan,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "no_batch" => $daftarNoBatch,
            "no_urut" => $daftarNoUrut,
            "tgl_expired" => $daftarTanggalKadaluarsa,
        ] = Yii::$app->request->post();

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        $ppn ??= 0;

        $dataKonsinyasi = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tipe_doc" => $tipeDokumen,
            "no_faktur" => $noFaktur,
            "no_suratjalan" => $noSuratJalan,
            "id_pbf" => $idPemasok,
            "id_gudangpenyimpanan" => $idGudangPenyimpanan,
            "id_depotujuan" => $idDepoTujuan,
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
            "keterangan" => $keterangan,
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "K",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Penerimaan Konsinyasi Bulan " . date("n") . " Tahun " . date("Y"),
                "userid_updt" => $idUser,
            ]);
            $kode = "K00" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);

            $dataKonsinyasi = [
                ...$dataKonsinyasi,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        $daftarField = array_keys($dataKonsinyasi);

        $dataDetailKonsinyasi = [];
        $dataRincianDetailKonsinyasi = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            if (!$jumlahKemasan) continue;

            if ($dataDetailKonsinyasi[$idKatalog]) {
                $dataDetailKonsinyasi[$idKatalog]["jumlah_item"] += $jumlahItem;
                $dataDetailKonsinyasi[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;

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
                    if ($hnaPpn >= $batasBawah and $hnaPpn < $batasAtas) {
                        $phja = $angka;
                    }
                }

                $hja = $hnaPpn + ($hnaPpn * $phja / 100);

                $dataDetailKonsinyasi[$idKatalog] = [
                    "kode_reff" => $kode,
                    "id_katalog" => $idKatalog,
                    "kemasan" => $daftarKemasan[$n],
                    "id_pabrik" => $daftarIdPabrik[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $daftarIsiKemasan[$n],
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
                    "hjapb_item" => $hja,
                    "userid_updt" => $idUser,
                ];
                $n++;
            }

            $dataRincianDetailKonsinyasi[$k++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_batch" => $daftarNoBatch[$i],
                "no_reffbatch" => $kode . "_" . $idKatalog . "_" . $daftarNoUrut[$i],
                "tgl_expired" => null,
                "no_urut" => $daftarNoUrut[$i],
                "jumlah_item" => $jumlahItem,
                "jumlah_kemasan" => $jumlahKemasan
            ];
            // set expired
            if ($daftarTanggalKadaluarsa[$k - 1]) {
                $dataRincianDetailKonsinyasi[$k - 1]["tgl_expired"] = $toSystemDate($daftarTanggalKadaluarsa[$i]);
            }
        }

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        if ($action == "add") {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_konsinyasi
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $adaKonsinyasi = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaKonsinyasi) throw new DataAlreadyExistException("Konsinyasi", "kode, No. Dokumen", "$kode, $noDokumen", $transaction);

            $berhasilTambah = $fm->saveData("transaksif_konsinyasi", $daftarField, $dataKonsinyasi);
            if (!$berhasilTambah) throw new FailToInsertException("Konsinyasi", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_konsinyasirinc", $dataRincianDetailKonsinyasi);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Konsinyasi", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_konsinyasi", $dataDetailKonsinyasi);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Konsinyasi", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_konsinyasi", $daftarField, $dataKonsinyasi, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Konsinyasi", $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_konsinyasirinc", $dataRincianDetailKonsinyasi, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Rincian Konsinyasi", "Kode Ref", $kode, $transaction);

            $berhasilUbah = $fm->saveBatch("tdetailf_konsinyasi", $dataDetailKonsinyasi, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Konsinyasi", "Kode Ref", $kode, $transaction);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                 AS kode,
                A.id_pbf               AS idPemasok,
                A.no_doc               AS noDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.tipe_doc             AS tipeDokumen,         -- in use
                A.sts_linked           AS statusLinked,
                A.sts_deleted          AS statusDeleted,
                A.sysdate_del          AS sysdateDeleted,
                A.userid_updt          AS useridUpdate,
                A.sysdate_updt         AS sysdateUpdate,
                A.blnawal_anggaran     AS bulanAwalAnggaran,
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                A.thn_anggaran         AS tahunAnggaran,
                A.id_sumberdana        AS idSumberDana,
                A.ppn                  AS ppn,
                B.subjenis_anggaran    AS subjenisAnggaran,
                C.sumber_dana          AS sumberDana,
                D.subsumber_dana       AS subsumberDana,
                E.jenis_harga          AS jenisHarga,
                F.cara_bayar           AS caraBayar,
                IFNULL(H.nama_pbf, '') AS namaPemasok,
                A.no_faktur            AS noFaktur,
                A.no_suratjalan        AS noSuratJalan,
                K.namaDepo             AS gudang,
                Kt.kd_sub_unit         AS subunitDepoTujuan,
                A.nilai_total          AS nilaiTotal,
                A.nilai_diskon         AS nilaiDiskon,
                A.nilai_ppn            AS nilaiPpn,
                A.nilai_pembulatan     AS nilaiPembulatan,
                A.nilai_akhir          AS nilaiAkhir,
                A.sysdate_in           AS sysdateInput,
                A.ver_tglkendali       AS verTanggalKendali,
                A.ver_kendali          AS verKendali,
                IFNULL(UTRM.name, '-') AS namaUserTerima,
                IFNULL(UKDL.name, '-') AS namaUserKendali,
                A.keterangan           AS keterangan
            FROM db1.transaksif_konsinyasi AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
            LEFT JOIN db1.masterf_depo AS Kt ON A.id_depotujuan = Kt.id
            LEFT JOIN db1.user AS UTRM ON A.userid_in = UTRM.id
            LEFT JOIN db1.user AS UKDL ON A.ver_usrkendali = UKDL.id
            WHERE
                A.kode = :kode
                AND A.ver_kendali = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $konsinyasi = $connection->createCommand($sql, $params)->queryOne();
        if (!$konsinyasi) throw new DataNotExistException($kode);

        // TODO: sql: ambiguous column name:
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                 AS kode,
                A.no_doc               AS noDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.tipe_doc             AS tipeDokumen,
                A.no_faktur            AS noFaktur,
                A.no_suratjalan        AS noSuratJalan,
                A.id_pbf               AS idPemasok,
                A.id_depotujuan        AS idDepoTujuan,
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
                A.sts_updtstok         AS statusUpdateStok,
                A.sts_linked           AS statusLinked,
                A.sysdate_link         AS sysdateLink,
                A.sts_closed           AS statusClosed,
                A.sysdate_cls          AS sysdateClosed,
                A.sts_deleted          AS statusDeleted,
                A.sysdate_del          AS sysdateDeleted,
                A.ver_kendali          AS verKendali,
                A.ver_usrkendali       AS verUserKendali,
                A.ver_tglkendali       AS verTanggalKendali,
                A.keterangan           AS keterangan,
                A.userid_in            AS useridInput,
                A.sysdate_in           AS sysdateInput,
                A.userid_updt          AS useridUpdate,
                A.sysdate_updt         AS sysdateUpdate,
                KAT.nama_sediaan       AS namaSediaan,
                KAT.jumlah_itembeli    AS jumlahItemBeli,
                KAT.jumlah_itembonus   AS jumlahItemBonus,
                P.nama_pabrik          AS namaPabrik,
                KEM1.kode              AS satuanJual,
                KEM2.kode              AS satuan,
                B.no_batch             AS noBatch,
                B.tgl_expired          AS tanggalKadaluarsa,
                B.no_urut              AS noUrut,
                B.jumlah_item          AS jumlahItem,
                B.jumlah_kemasan       AS jumlahKemasan
            FROM db1.transaksif_konsinyasi AS A
            LEFT JOIN db1.tdetailf_konsinyasi AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS P ON P.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KEM1 ON KEM1.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KEM2 ON KEM2.id = A.id_kemasandepo
            WHERE
                A.kode_reff = :kodeRef
                AND A.id_katalog = B.id_katalog
            ORDER BY
                nama_sediaan,
                B.no_urut
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailKonsinyasi = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarDetailKonsinyasi) throw new DataNotExistException($kode);

        return json_encode([
            "actionEdit" => ($konsinyasi->tipeDokumen == 1) ? "addNonrutin" : "add",
            "action" => "edit",
            "idata" => $daftarDetailKonsinyasi,
            "data" => $konsinyasi,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#verKendali    the original method
     */
    public function actionSaveVerKendali(): Response
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        ["kode" => $kode, "ver_kendali" => $verKendali] = Yii::$app->request->post();
        $data = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();
        if ($verKendali != 1) throw new \Exception('"ver_kendali" must be "1"');

        $data["ver_usrkendali"] = $this->user["id"];
        $data["ver_tglkendali"] = $nowValSystem;

        $sql = "CALL db1.process_verifKendali('$kode', '{$this->user['id']}', @out_status);";
        $connection->createCommand($sql)->execute();

        $sql = "SELECT @out_status AS out_param";
        $result = $connection->createCommand($sql)->queryScalar();
        if ($result) throw new FailToUpdateException("Ver Kendali", "kode", $kode, $transaction);

        $transaction->commit();

        return $this->redirect(Yii::$app->actionToUrl([Pair::class, "actionView"]) ."/".$kode."?mf=T011");
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#verKendali    the original method
     */
    public function actionVerKendaliData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                 AS kode,
                A.id_pbf               AS idPemasok,
                A.no_doc               AS noDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.id_depotujuan        AS idDepoTujuan,
                A.tipe_doc             AS tipeDokumen,           -- in use
                A.sts_linked           AS statusLinked,
                A.sts_deleted          AS statusDeleted,
                A.sysdate_del          AS sysdateDeleted,
                A.userid_updt          AS useridUpdate,
                A.sysdate_updt         AS sysdateUpdate,
                A.blnawal_anggaran     AS bulanAwalAnggaran,
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                A.thn_anggaran         AS tahunAnggaran,
                A.ppn                  AS ppn,
                B.subjenis_anggaran    AS subjenisAnggaran,
                C.sumber_dana          AS sumberDana,
                D.subsumber_dana       AS subsumberDana,
                E.jenis_harga          AS jenisHarga,
                F.cara_bayar           AS caraBayar,
                IFNULL(H.nama_pbf, '') AS namaPemasok,
                A.no_faktur            AS noFaktur,
                A.no_suratjalan        AS noSuratJalan,
                K.namaDepo             AS gudang,
                Kt.KD_SUB_UNIT         AS subunitDepoTujuan,
                A.nilai_total          AS nilaiTotal,
                A.nilai_diskon         AS nilaiDiskon,
                A.nilai_ppn            AS nilaiPpn,
                A.nilai_pembulatan     AS nilaiPembulatan,
                A.nilai_akhir          AS nilaiAkhir,
                A.sysdate_in           AS sysdateInput,
                A.ver_tglkendali       AS verTanggalKendali,
                A.ver_kendali          AS verKendali,
                IFNULL(UTRM.name, '-') AS namaUserTerima,
                IFNULL(UKDL.name, '-') AS namaUserKendali,
                A.keterangan           AS keterangan
            FROM db1.transaksif_konsinyasi AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
            LEFT JOIN db1.masterf_depo AS Kt ON A.id_depotujuan = Kt.id
            LEFT JOIN db1.user AS UTRM ON A.userid_in = UTRM.id
            LEFT JOIN db1.user AS UKDL ON A.ver_usrkendali = UKDL.id
            WHERE
                A.kode = :kodeRef
                AND A.ver_kendali = 0
            LIMIT 1
        ";
        $params = [":kodeRef" => $kode];
        $konsinyasi = $connection->createCommand($sql, $params)->queryOne();
        if (!$konsinyasi) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff          AS kodeRef,
                A.id_katalog         AS idKatalog,
                A.kemasan            AS kemasan,
                A.id_pabrik          AS idPabrik,
                A.id_kemasan         AS idKemasan,
                A.isi_kemasan        AS isiKemasan,
                A.id_kemasandepo     AS idKemasanDepo,
                A.jumlah_item        AS jumlahItem,
                A.jumlah_kemasan     AS jumlahKemasan,
                A.harga_item         AS hargaItem,
                A.harga_kemasan      AS hargaKemasan,
                A.diskon_item        AS diskonItem,
                A.diskon_harga       AS diskonHarga,
                A.hna_item           AS hnaItem,
                A.hp_item            AS hpItem,
                A.hppb_item          AS hpPbItem,
                A.phja_item          AS phjaItem,
                A.phjapb_item        AS phjaPbItem,
                A.hja_item           AS hjaItem,
                A.hjapb_item         AS hjaPbItem,
                A.keterangan         AS keterangan,
                A.userid_updt        AS useridUpdate,
                A.sysdate_updt       AS sysdateUpdate,
                KAT.nama_sediaan     AS namaSediaan,
                KAT.jumlah_itembeli  AS jumlahItemBeli,
                KAT.jumlah_itembonus AS jumlahItemBonus,
                PBK.nama_pabrik      AS namaPabrik,
                KEM1.kode            AS satuanJual,
                KEM2.kode            AS satuan,
                B.no_batch           AS noBatch,
                B.tgl_expired        AS tanggalKadaluarsa,
                B.no_urut            AS noUrut,
                B.jumlah_item        AS jumlahItem,
                B.jumlah_kemasan     AS jumlahKemasan
            FROM db1.tdetailf_konsinyasi AS A
            LEFT JOIN db1.tdetailf_konsinyasirinc AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KEM1 ON KEM1.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KEM2 ON KEM2.id = A.id_kemasandepo
            WHERE
                A.kode_reff = :kodeRef
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, B.no_urut
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailKonsinyasi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "action" => "kendali", // OR may be: ($konsinyasi->tipeDokumen == "1") ? "addNonrutin" : "add"
            "idata" => $daftarDetailKonsinyasi,
            "data" => $konsinyasi,
        ]);
        // return $this->actionAdd($action);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#proceed    the original method
     */
    public function actionSaveProceedVerGudang(): string
    {
        [   "kode" => $kode,
            "ver_gudang" => $verGudang,
        ] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;
        if (!$verGudang) throw new \Exception('"ver_gudang" must be set');

        $verUserGudang = $idUser;
        $verTanggalGudang = $nowValSystem;
        // return print_r($post, true);

        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        // belum hitung jumlah yang diretur oleh peresepan
        $sql = /** @lang SQL */ "CALL db1.process_VerTerimaKonsinyasi('$kode', @sts_verGudang);";
        $connection->createCommand($sql)->execute();

        $sql = /** @lang SQL */ "SELECT @sts_verGudang AS out_param";
        $result = $connection->createCommand($sql)->queryScalar();
        if (!$result) throw new FailToUpdateException("Ver Terima Konsinyasi", "kode", $kode, $transaction);

        $transaction->commit();

        return json_encode(["kode" => $kode]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws DataNotExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#proceed    the original method
     */
    public function actionSaveProceed(): void
    {
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        [   "action" => $action,
            "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
            "tipe_doc" => $tipeDokumen,
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
            "userid_updt" => $idUserUpdate,
            "kode_reffkons" => $kodeRefKonsinyasi, // TODO: php: uncategorized: determine whether this is array or single var?
            "ver_terima" => $verTerima,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_item" => $daftarJumlahItem,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "diskon_item" => $daftarDiskonItem,
            "harga_item" => $daftarHargaItem,
            "harga_kemasan" => $daftarHargaKemasan,
            "diskon_harga" => $daftarDiskonHarga,
            "hna_item" => $daftarHnaItem,
            "hp_item" => $daftarHpItem,
            "phja_item" => $daftarPhjaItem,
            "hja_item" => $daftarHjaItem,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "no_batch" => $daftarNoBatch,
            "no_reffbatch" => $daftarNoRefBatch,
            "no_urut" => $daftarNoUrut,
            "tgl_expired" => $daftarTanggalKadaluarsa,
        ] = Yii::$app->request->post();

        $dataPenerimaan = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tipe_doc" => $tipeDokumen,
            "kode_reffkons" => $kodeRefKonsinyasi,
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
            "ppn" => $ppn ?? 0,
            "nilai_total" => $toSystemNumber($nilaiTotal),
            "nilai_diskon" => $toSystemNumber($nilaiDiskon),
            "nilai_ppn" => $toSystemNumber($nilaiPpn),
            "nilai_pembulatan" => $toSystemNumber($nilaiPembulatan),
            "nilai_akhir" => $toSystemNumber($nilaiAkhir),
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "T",
                "unit" => "0000",
                "subunit" => 19,
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Penerimaan Konsinyasi Bulan " . date("n") . " Tahun " . date("Y"),
                "userid_updt" => $idUserUpdate,
            ]);
            $kode = "T19" . date("Ym") . str_pad($counter, 6, "0", STR_PAD_LEFT);

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

        $daftarField = array_keys($dataPenerimaan);

        $dataDetailPenerimaan = [];
        $dataRincianDetailPenerimaan = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            if (!$jumlahKemasan) continue;

            if ($dataDetailPenerimaan[$idKatalog]) {
                $dataDetailPenerimaan[$idKatalog]["jumlah_item"] += $jumlahItem;
                $dataDetailPenerimaan[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;

            } else {
                $dataDetailPenerimaan[$idKatalog] = [
                    "kode_reff" => $kode,
                    "kode_reffkons" => $kodeRefKonsinyasi,
                    "id_katalog" => $idKatalog,
                    "kemasan" => $daftarKemasan[$n],
                    "id_pabrik" => $daftarIdPabrik[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $daftarIsiKemasan[$n],
                    "id_kemasandepo" => $daftarIdKemasanDepo[$n],
                    "jumlah_item" => $jumlahItem,
                    "jumlah_kemasan" => $jumlahKemasan,
                    "harga_item" => $toSystemNumber($daftarHargaItem[$n]),
                    "harga_kemasan" => $toSystemNumber($daftarHargaKemasan[$n]),
                    "diskon_item" => $toSystemNumber($daftarDiskonItem[$n]),
                    "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$n]),
                    "hna_item" => $toSystemNumber($daftarHnaItem[$n]),
                    "hp_item" => $toSystemNumber($daftarHpItem[$n]),
                    "hppb_item" => $toSystemNumber($daftarHpItem[$n]),
                    "phja_item" => $toSystemNumber($daftarPhjaItem[$n]),
                    "phjapb_item" => $toSystemNumber($daftarPhjaItem[$n]),
                    "hja_item" => $toSystemNumber($daftarHjaItem[$n]),
                    "hjapb_item" => $toSystemNumber($daftarHjaItem[$n]),
                    "userid_updt" => $idUserUpdate
                ];
                $n++;
            }

            $dataRincianDetailPenerimaan[$k++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_reffbatch" => $daftarNoRefBatch[$i],
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

        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_penerimaan
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $cekPenerimaan = $connection->createCommand($sql, $params)->queryScalar();
            if (!$cekPenerimaan) throw new DataNotExistException("Penerimaan", "Kode, No. Dokumen", "$kode, $noDokumen", $transaction);

            $berhasilTambah = $fm->saveData("transaksif_penerimaan", $daftarField, $dataPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Penerimaan", $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_konsinyasi
                SET
                    sts_linked = 1,
                    sysdate_link = :sysdateLink
                WHERE kode = :kode
            ";
            $params = [":sysdateLink" => $idUserUpdate, ":kode" => $kodeRefKonsinyasi];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Konsinyasi", "Kode", $kodeRefKonsinyasi, $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_penerimaanrinc", $dataRincianDetailPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Penerimaan", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_penerimaan", $dataDetailPenerimaan);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Penerimaan", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_penerimaan", $daftarField, $dataPenerimaan, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "kode", $kode, $transaction);

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
     * @throws Exception
     * @throws DataNotExistException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#proceed    the original method
     */
    public function actionProceedData(): string
    {
        [   "action" => $action,
            "kode" => $kode,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode                 AS kodeRefKons,
                    A.id_pbf               AS idPemasok,
                    A.no_doc               AS noDokumen,
                    A.no_doc               AS noDokumenRef,
                    A.tgl_doc              AS tanggalDokumen,
                    A.tgl_doc              AS tanggalRef,
                    A.tipe_doc             AS tipeKonsinyasi,
                    A.sts_linked           AS statusLinked,
                    A.sts_deleted          AS statusDeleted,
                    A.sysdate_del          AS sysdateDeleted,
                    A.userid_updt          AS useridUpdate,
                    A.sysdate_updt         AS sysdateUpdate,
                    A.blnawal_anggaran     AS bulanAwalAnggaran,
                    A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                    A.thn_anggaran         AS tahunAnggaran,
                    A.id_jenisanggaran     AS idJenisAnggaran,       -- in use
                    A.id_jenisharga        AS idJenisHarga,          -- in use
                    A.id_sumberdana        AS idSumberDana,          -- in use
                    A.ppn                  AS ppn,
                    A.userid_in            AS useridInput,
                    IFNULL(UINP.name, '-') AS namaUserInput,
                    B.subjenis_anggaran    AS subjenisAnggaran,
                    C.sumber_dana          AS sumberDana,
                    D.subsumber_dana       AS subsumberDana,
                    E.jenis_harga          AS jenisHarga,
                    F.cara_bayar           AS caraBayar,
                    IFNULL(H.nama_pbf, '') AS namaPemasok,
                    A.no_faktur            AS noFaktur,
                    A.no_suratjalan        AS noSuratJalan,
                    K.namaDepo             AS gudang,
                    Kt.kd_sub_unit         AS subunitDepoTujuan,
                    A.nilai_total          AS nilaiTotal,
                    A.nilai_diskon         AS nilaiDiskon,
                    A.nilai_ppn            AS nilaiPpn,
                    A.nilai_pembulatan     AS nilaiPembulatan,
                    A.nilai_akhir          AS nilaiAkhir,
                    A.sysdate_in           AS sysdateInput,
                    A.ver_tglkendali       AS verTanggalKendali,
                    A.ver_kendali          AS verKendali,
                    IFNULL(UKDL.name, '-') AS namaUserKendali,
                    A.keterangan           AS keterangan,
                    0                      AS statusNoFaktur,
                    0                      AS statusNoSuratJalan,
                    0                      AS statusIdPemasok,
                    0                      AS statusIdSumberDana,
                    0                      AS statusIdJenisAnggaran,
                    0                      AS statusIdJenisHarga,
                    0                      AS statusIdCaraBayar,
                    0                      AS statusPpn,
                    NULL                   AS daftarRincianDetailKonsinyasi
                FROM db1.transaksif_konsinyasi AS A
                LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
                LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
                LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
                LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
                LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
                LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
                LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
                LEFT JOIN db1.masterf_depo AS Kt ON A.id_depotujuan = Kt.id
                LEFT JOIN db1.user AS UINP ON A.userid_in = UINP.id
                LEFT JOIN db1.user AS UKDL ON A.ver_usrkendali = UKDL.id
                WHERE
                    A.kode = :kode
                    AND A.ver_kendali = 1
                    AND A.sts_linked = 0
                    AND A.sts_closed = 0
                    AND A.sts_deleted = 0
            ";
        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode                                             AS kode,
                    A.kode_reffkons                                    AS kodeRefKons,
                    A.no_doc                                           AS noDokumen,
                    A.tgl_doc                                          AS tanggalDokumen,
                    A.tipe_doc                                         AS tipeDokumen,
                    A.sts_linked                                       AS statusLinked,
                    A.sts_deleted                                      AS statusDeleted,
                    A.sysdate_del                                      AS sysdateDeleted,
                    A.userid_updt                                      AS useridUpdate,
                    A.sysdate_updt                                     AS sysdateUpdate,
                    B.id_pbf                                           AS idPemasok,
                    A.blnawal_anggaran                                 AS bulanAwalAnggaran,
                    A.blnakhir_anggaran                                AS bulanAkhirAnggaran,
                    A.thn_anggaran                                     AS tahunAnggaran,
                    B.id_sumberdana                                    AS idSumberDana,            -- in use
                    B.id_jenisanggaran                                 AS idJenisAnggaran,         -- in use
                    B.id_jenisharga                                    AS idJenisHarga,            -- in use
                    B.ppn                                              AS ppn,
                    A.userid_in                                        AS useridInput,
                    IFNULL(UINP.name, '-')                             AS namaUserInput,
                    IFNULL(H.nama_pbf, '')                             AS namaPemasok,
                    K.namaDepo                                         AS gudang,
                    Kt.kd_sub_unit                                     AS subunitDepoTujuan,
                    A.nilai_total                                      AS nilaiTotal,
                    A.nilai_diskon                                     AS nilaiDiskon,
                    A.nilai_ppn                                        AS nilaiPpn,
                    A.nilai_pembulatan                                 AS nilaiPembulatan,
                    A.nilai_akhir                                      AS nilaiAkhir,
                    B.no_faktur                                        AS noFaktur,
                    B.no_suratjalan                                    AS noSuratJalan,
                    IF(B.no_faktur != A.no_faktur, 1, 0)               AS statusNoFaktur,
                    IF(B.no_suratjalan != A.no_suratjalan, 1, 0)       AS statusNoSuratJalan,
                    IF(B.id_pbf != A.id_pbf, 1, 0)                     AS statusIdPemasok,
                    IF(B.id_sumberdana!= A.id_sumberdana, 1, 0)        AS statusIdSumberDana,
                    IF(B.id_jenisanggaran != A.id_jenisanggaran, 1, 0) AS statusIdJenisAnggaran,
                    IF(B.id_jenisharga != A.id_jenisharga, 1, 0)       AS statusIdJenisHarga,
                    IF(B.id_carabayar != A.id_carabayar, 1, 0)         AS statusIdCaraBayar,
                    IF(B.ppn != A.ppn, 1, 0)                           AS statusPpn,
                    B.sysdate_in                                       AS sysdateInput,
                    B.ver_tglkendali                                   AS verTanggalKendali,
                    B.ver_kendali                                      AS verKendali,
                    A.ver_terima                                       AS verTerima,
                    IF(A.ver_terima = 1, 'checked', '')                AS verTerima,
                    A.ver_tglterima                                    AS verTanggalTerima,
                    IFNULL(UTRM.name, '-')                             AS namaUserTerima,
                    A.ver_gudang                                       AS verGudang,
                    A.ver_tglgudang                                    AS verTanggalGudang,
                    IFNULL(UGDG.name, '-')                             AS namaUserGudang,
                    A.ver_akuntansi                                    AS verAkuntansi,
                    A.ver_tglakuntansi                                 AS verTanggalAkuntansi,
                    IFNULL(UAKN.name, '-')                             AS namaUserAkuntansi,
                    IFNULL(UKDL.name, '-')                             AS namaUserKendali,
                    B.keterangan                                       AS keterangan,
                    B.no_doc                                           AS noDokumenRef,
                    B.tgl_doc                                          AS tanggalRef,
                    B.tipe_doc                                         AS tipeKonsinyasi,
                    NULL                                               AS daftarRincianDetailKonsinyasi
                FROM db1.transaksif_penerimaan AS A
                INNER JOIN db1.transaksif_konsinyasi AS B ON A.kode_reffkons = B.kode
                LEFT JOIN db1.masterf_pbf AS H ON B.id_pbf = H.id
                LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
                LEFT JOIN db1.masterf_depo AS Kt ON B.id_depotujuan = Kt.id
                LEFT JOIN db1.user AS UINP ON B.userid_in = UINP.id
                LEFT JOIN db1.user AS UKDL ON B.ver_usrkendali = UKDL.id
                LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
                LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
                LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
                WHERE
                    A.kode = :kode
                    AND A.sts_deleted = 0
                    AND A.ver_gudang = 0
                    AND A.ver_akuntansi = 0
                LIMIT 1
            ";
        }
        $params = [":kode" => $kode];
        $penerimaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$penerimaan) throw new DataNotExistException($kode);

        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode_reff                                            AS kodeRef,
                    A.id_katalog                                           AS idKatalog,
                    A.kemasan                                              AS kemasan,
                    A.id_pabrik                                            AS idPabrik,
                    A.id_kemasan                                           AS idKemasan,
                    A.isi_kemasan                                          AS isiKemasan,
                    A.id_kemasandepo                                       AS idKemasanDepo,
                    A.jumlah_item                                          AS jumlahItem,
                    A.jumlah_kemasan                                       AS jumlahKemasan,
                    A.harga_item                                           AS hargaItem,
                    A.harga_kemasan                                        AS hargaKemasan,
                    A.diskon_item                                          AS diskonItem,
                    A.diskon_harga                                         AS diskonHarga,
                    A.hna_item                                             AS hnaItem,
                    A.hp_item                                              AS hpItem,
                    A.hppb_item                                            AS hpPbItem,
                    A.phja_item                                            AS phjaItem,
                    A.phjapb_item                                          AS phjaPbItem,
                    A.hja_item                                             AS hjaItem,
                    A.hjapb_item                                           AS hjaPbItem,
                    A.keterangan                                           AS keterangan,
                    A.userid_updt                                          AS useridUpdate,
                    A.sysdate_updt                                         AS sysdateUpdate,
                    A.jumlah_item                                          AS jumlahKonsinyasi,
                    A.kode_reff                                            AS kodeRefKons,
                    B.no_reffbatch                                         AS noRefBatch,
                    KAT.nama_sediaan                                       AS namaSediaan,
                    KAT.jumlah_itembeli                                    AS jumlahItemBeli,
                    KAT.jumlah_itembonus                                   AS jumlahItemBonus,
                    PAB.nama_pabrik                                        AS namaPabrik,
                    KEM1.kode                                              AS satuanJual,
                    KEM2.kode                                              AS satuan,
                    B.no_batch                                             AS noBatch,
                    B.tgl_expired                                          AS tanggalKadaluarsa,
                    B.no_urut                                              AS noUrut,
                    B.jumlah_item                                          AS jumlahItem,
                    B.jumlah_kemasan                                       AS jumlahKemasan,
                    IFNULL(R.jumlah_keluar, 0) - IFNULL(R.jumlah_masuk, 0) AS jumlahResep,
                    0                                                      AS statusItem
                FROM db1.tdetailf_konsinyasi AS A
                INNER JOIN db1.tdetailf_konsinyasirinc AS B ON A.kode_reff = B.kode_reff
                LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
                LEFT JOIN db1.masterf_pabrik AS PAB ON PAB.id = A.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS KEM1 ON KEM1.id = A.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS KEM2 ON KEM2.id = A.id_kemasandepo
                LEFT JOIN (
                    SELECT
                        A.id_katalog         AS id_katalog,
                        A.no_reffbatch       AS no_reffbatch,
                        SUM(A.jumlah_masuk)  AS jumlah_masuk,
                        SUM(A.jumlah_keluar) AS jumlah_keluar
                    FROM db1.transaksif_kartuketersediaan AS A
                    WHERE
                        A.id_depo IN (26, 27)
                        AND A.kode_transaksi = 'R'
                        AND tipe_transaksi = 'penjualan'
                        AND sts_transaksi = 1
                    GROUP BY
                        A.id_katalog,
                        A.no_reffbatch
                ) AS R ON A.id_katalog = R.id_katalog
                WHERE
                    A.kode_reff = :kode
                    AND B.no_reffbatch = R.no_reffbatch
                    AND A.id_katalog = B.id_katalog
                ORDER BY nama_sediaan, B.no_urut
            ";
        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    B.kode_reffkons                                        AS kodeRefKons,
                    Kon.id_pabrik                                          AS idPabrik,
                    Kon.kemasan                                            AS kemasan,
                    Kon.isi_kemasan                                        AS isiKemasan,
                    Kon.id_kemasan                                         AS idKemasan,
                    Kon.id_kemasandepo                                     AS idKemasanDepo,
                    Kon.harga_item                                         AS hargaItem,
                    Kon.harga_kemasan                                      AS hargaKemasan,
                    Kon.diskon_item                                        AS diskonItem,
                    Kon.diskon_harga                                       AS diskonHarga,
                    A.id_katalog                                           AS idKatalog,
                    A.no_reffbatch                                         AS noRefBatch,
                    A.no_urut                                              AS noUrut,
                    Kn.no_batch                                            AS noBatch,
                    Kn.tgl_expired                                         AS tanggalKadaluarsa,
                    K.nama_sediaan                                         AS namaSediaan,
                    P.nama_pabrik                                          AS namaPabrik,
                    Kb.kode                                                AS satuanJual,
                    Kk.kode                                                AS satuan,
                    Kn.jumlah_item                                         AS jumlahKonsinyasi,
                    IFNULL(R.jumlah_keluar, 0) - IFNULL(R.jumlah_masuk, 0) AS jumlahResep,
                    B.id_pabrik                                            AS idPabrikOri,
                    B.isi_kemasan                                          AS isiKemasanOri,
                    B.harga_item                                           AS hargaItemOri,
                    B.harga_kemasan                                        AS hargaKemasanOri,
                    B.diskon_item                                          AS diskonItemOri,
                    B.diskon_harga                                         AS diskonHargaOri,
                    A.no_batch                                             AS noBatchOri,
                    A.tgl_expired                                          AS tanggalKadaluarsaOri,
                    A.jumlah_item                                          AS jumlahItemOri,
                    A.jumlah_kemasan                                       AS jumlahKemasanOri,
                    Kon.hna_item                                           AS hnaItem,
                    Kon.hp_item                                            AS hpItem,
                    Kon.phja_item                                          AS phjaItem,
                    Kon.hja_item                                           AS hjaItem,
                    IF(B.id_pabrik = Kon.id_pabrik, IF(B.isi_kemasan = Kon.isi_kemasan, IF(B.harga_item = Kon.harga_item, IF(B.harga_kemasan = Kon.harga_kemasan, IF(B.diskon_item = Kon.diskon_item, IF(A.jumlah_item = (IFNULL(R.jumlah_keluar, 0)-IFNULL(R.jumlah_masuk, 0)), IF(A.jumlah_kemasan = ((IFNULL(R.jumlah_keluar, 0)-IFNULL(R.jumlah_masuk, 0))/B.isi_kemasan), 0, 1), 1), 1), 1), 1), 1), 1) AS statusItem
                FROM db1.tdetailf_konsinyasirinc AS Kn
                LEFT JOIN db1.tdetailf_penerimaan AS B ON Kn.kode_reff = B.kode_reffkons
                LEFT JOIN db1.tdetailf_penerimaanrinc AS A ON A.id_katalog = Kn.id_katalog
                LEFT JOIN db1.tdetailf_konsinyasi AS Kon ON Kon.kode_reff = Kn.kode_reff
                LEFT JOIN (
                    SELECT
                        A.kode_reff          AS kode_reff,
                        A.id_katalog         AS id_katalog,
                        A.no_reffbatch       AS no_reffbatch,
                        SUM(A.jumlah_masuk)  AS jumlah_masuk,
                        SUM(A.jumlah_keluar) AS jumlah_keluar
                    FROM db1.transaksif_kartuketersediaan AS A
                    WHERE
                        A.id_depo IN (26, 27)
                        AND A.kode_transaksi = 'R'
                        AND tipe_transaksi = 'penjualan'
                        AND sts_transaksi = 1
                    GROUP BY
                        A.id_katalog,
                        A.no_reffbatch
                ) AS R ON A.id_katalog = R.id_katalog
                LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
                LEFT JOIN db1.masterf_pabrik AS P ON P.id = Kon.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS Kb ON Kb.id = Kon.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS Kk ON Kk.id = Kon.id_kemasandepo
                WHERE
                    A.kode_reff = :kode
                    AND A.no_reffbatch = R.no_reffbatch
                    AND Kon.id_katalog = Kn.id_katalog
                    AND A.no_reffbatch = Kn.no_reffbatch
                    AND B.kode_reff = A.kode_reff
                    AND Kn.id_katalog = B.id_katalog
                ORDER BY
                    nama_sediaan,
                    A.no_urut
            ";
        }
        $params = [":kode" => $kode];
        $penerimaan->daftarRincianDetailKonsinyasi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($penerimaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#views    the original method
     */
    public function actionViewData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? null;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kodeKonsinyasi,
                A.no_doc               AS noDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.tipe_doc             AS tipeDokumen,
                A.blnawal_anggaran     AS bulanAwalAnggaran,
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                A.thn_anggaran         AS tahunAnggaran,
                A.ppn                  AS ppn,
                B.subjenis_anggaran    AS subjenisAnggaran,
                C.sumber_dana          AS sumberDana,
                E.jenis_harga          AS jenisHarga,
                F.cara_bayar           AS caraBayar,
                IFNULL(H.nama_pbf, '') AS namaPemasok,
                A.no_faktur            AS noFaktur,
                A.no_suratjalan        AS noSuratJalan,
                K.namaDepo             AS gudang,
                A.nilai_pembulatan     AS nilaiPembulatan,
                A.ver_tglkendali       AS verTanggalKendali,
                A.ver_kendali          AS verKendali,
                IFNULL(UKDL.name, '-') AS namaUserKendali,
                A.keterangan           AS keterangan,
                ''                     AS detailKonsinyasi
            FROM db1.transaksif_konsinyasi AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            LEFT JOIN db1.masterf_depo AS K ON A.id_gudangpenyimpanan = K.id
            LEFT JOIN db1.user AS Ut ON A.userid_in = Ut.id
            LEFT JOIN db1.user AS UKDL ON A.ver_usrkendali = UKDL.id
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $konsinyasi = $connection->createCommand($sql, $params)->queryOne();
        if (!$konsinyasi) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog                                           AS idKatalog,
                B.kemasan                                              AS kemasan,
                B.isi_kemasan                                          AS isiKemasan,
                B.harga_item                                           AS hargaItem,
                B.harga_kemasan                                        AS hargaKemasan,
                B.diskon_item                                          AS diskonItem,
                A.jumlah_item                                          AS jumlahItem,
                A.jumlah_kemasan                                       AS jumlahKemasan,
                K.nama_sediaan                                         AS namaSediaan,
                P.nama_pabrik                                          AS namaPabrik,
                IFNULL(R.jumlah_keluar, 0) - IFNULL(R.jumlah_masuk, 0) AS jumlahResep,
                IFNULL(T.jumlah_item, 0)                               AS jumlahTerima,
                IFNULL(Rt.jumlah_item, 0)                              AS jumlahRetur
            FROM db1.tdetailf_konsinyasirinc AS A
            LEFT JOIN db1.tdetailf_konsinyasi AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS P ON P.id = B.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS Kb ON Kb.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS Kk ON Kk.id = B.id_kemasandepo
            LEFT JOIN (
                SELECT
                    A.id_katalog         AS id_katalog,
                    A.no_reffbatch       AS no_reffbatch,
                    SUM(A.jumlah_masuk)  AS jumlah_masuk,
                    SUM(A.jumlah_keluar) AS jumlah_keluar
                FROM db1.transaksif_kartuketersediaan AS A
                WHERE
                    A.id_depo IN (26, 27)
                    AND A.kode_transaksi = 'R'
                    AND tipe_transaksi = 'penjualan'
                    AND sts_transaksi = 1
                GROUP BY A.id_katalog, A.no_reffbatch
            ) AS R ON A.id_katalog = R.id_katalog
            LEFT JOIN (
                SELECT
                    B.kode_reffkons    AS kode_reffkons,
                    A.id_katalog       AS id_katalog,
                    A.no_reffbatch     AS no_reffbatch,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaanrinc AS A
                INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND B.kode_reffkons = :kode
                GROUP BY
                    B.kode_reffkons,
                    A.id_katalog,
                    A.no_reffbatch
            ) AS T ON A.kode_reff = T.kode_reffkons
            LEFT JOIN (
                SELECT
                    B.kode_reffkons    AS kode_reffkons,
                    A.id_katalog       AS id_katalog,
                    A.no_reffbatch     AS no_reffbatch,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_returnrinc AS A
                INNER JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND B.kode_reffkons = :kode
                GROUP BY
                    B.kode_reffkons,
                    A.id_katalog,
                    A.no_reffbatch
            ) AS Rt ON A.kode_reff = Rt.kode_reffkons
            WHERE
                A.kode_reff = :kode
                AND A.no_reffbatch = R.no_reffbatch
                AND A.id_katalog = R.id_katalog
                AND A.no_reffbatch = R.no_reffbatch
                AND A.id_katalog = Rt.id_katalog
                AND A.no_reffbatch = Rt.no_reffbatch
                AND A.id_katalog = B.id_katalog
            ORDER BY K.nama_sediaan, A.no_urut
        ";
        $params = [":kode" => $kode];
        $konsinyasi->detailKonsinyasi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($konsinyasi);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#ajaxDelete    the original method
     */
    public function actionAjaxDelete(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $keterangan = Yii::$app->request->post("keterangan");

        // TODO: php: uncategorized: fix this
        $filter = "...";

        // fokus select ke transaksi atau detail dari transaksi
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_konsinyasi
            SET
                no_doc = kode,
                keterangan = CONCAT('Hapus DO/PO dengan No: ', :keterangan),
                sts_deleted = 1,
                sysdate_del = :tanggalHapus
            WHERE $filter
        ";
        $params = [":tanggalHapus" => $nowValSystem, ":keterangan" => $keterangan];
        $berhasilHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode([1, $berhasilHapus]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#getUpdateTrn the original method
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#Prints    the original method (not exist)
     */
    public function actionPrint()
    {
        ["kodeKonsinyasi" => $kode, "versi" => $d] = Yii::$app->request->post();
        // TODO: php: uncategorized: sync it
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/masterdata.php#nodokumen as the source of copied text
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
                FROM db1.transaksif_konsinyasi
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
                FROM db1.transaksif_konsinyasi
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

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#Kartu    the original method (not exist)
     */
    public function actionKartu(string $kode = "", int $d = 0): string
    {
        // TODO: php: uncategorized: sync it
        return "";
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#Stok    the original method (not exist)
     */
    public function actionStok(string $kode = "", int $d = 0): string
    {
        // TODO: php: uncategorized: sync it
        return "";
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#Reports    the original method (not exist)
     */
    public function actionReports(string $kode = "", int $d = 0): string
    {
        // TODO: php: uncategorized: sync it
        return "";
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/konsinyasi.php#addresep    the original method
     */
    public function actionSaveResep(): void
    {
        $post = Yii::$app->request->post();
        if ($post["submit"] == "save") {
            // TODO: php: to be deleted.
            print_r($post, true);
        }
    }
}
