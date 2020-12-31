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
use tlm\his\FatmaPharmacy\views\ReturFarmasi\{BukuInduk, PrintV1, RekapPemasok};
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
class ReturFarmasiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "filter" => $filter,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $connection = Yii::$app->dbFatma;
        $params = [":prefix" => substr($filter, 0, 1), ":kode" => substr($filter, 2, 17)];

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.transaksif_return AS RTR
            LEFT JOIN db1.transaksif_pembelian AS BL ON BL.kode = RTR.kode_reffpl
            LEFT JOIN db1.transaksif_pemesanan AS PSN ON PSN.kode = RTR.kode_reffpo
            LEFT JOIN db1.transaksif_penerimaan AS TMA ON TMA.kode = RTR.kode_refftrm
            LEFT JOIN db1.masterf_pbf AS PBF ON PBF.id = RTR.id_pbf
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON RTR.id_jenisanggaran = SJA.id
            LEFT JOIN db1.user AS UTRM ON RTR.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON RTR.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON RTR.ver_usrakuntansi = UAKN.id
            LEFT JOIN db1.masterf_tipedoc AS TDOK ON RTR.tipe_doc = TDOK.kode
            WHERE
                RTR.sts_deleted = 0
                AND TDOK.modul = 'return'
                AND (
                    (:prefix = 'K' AND RTR.kode_reffkons = :kode)
                    OR (:prefix = 'S' AND RTR.kode_reffpo = :kode)
                    OR (:prefix = 'P' AND RTR.kode_reffpl = :kode)
                    OR (:prefix = 'R' AND RTR.kode_reffrenc = :kode)
                    OR (:prefix = 'O' AND RTR.kode_reffro = :kode)
                    OR (:prefix = 'T' AND RTR.kode_refftrm = :kode)
                    OR TRUE
                )
            ORDER BY RTR.tgl_doc DESC
        ";
        $total = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                RTR.kode               AS kode,
                RTR.keterangan         AS keterangan,
                RTR.no_doc             AS noDokumen,
                RTR.tgl_doc            AS tanggalDokumen,
                RTR.tipe_doc           AS tipeDokumen,
                RTR.nilai_akhir        AS nilaiAkhir,
                RTR.sts_linked         AS statusLinked,
                RTR.sts_izinrevisi     AS statusIzinRevisi,
                RTR.ver_terima         AS verTerima,
                RTR.ver_gudang         AS verGudang,
                RTR.ver_tglgudang      AS verTanggalGudang,
                RTR.ver_akuntansi      AS verAkuntansi,
                RTR.sysdate_updt       AS updatedTime,
                TMA.no_doc             AS noTerima,
                SJA.subjenis_anggaran  AS subjenisAnggaran,
                PBF.nama_pbf           AS namaPemasok,
                TDOK.tipe_doc          AS jenisRetur,
                IFNULL(UGDG.name, '-') AS namaUserGudang,
                BL.sts_closed          AS statusClosed
            FROM db1.transaksif_return AS RTR
            LEFT JOIN db1.transaksif_pembelian AS BL ON BL.kode = RTR.kode_reffpl
            LEFT JOIN db1.transaksif_pemesanan AS PSN ON PSN.kode = RTR.kode_reffpo
            LEFT JOIN db1.transaksif_penerimaan AS TMA ON TMA.kode = RTR.kode_refftrm
            LEFT JOIN db1.masterf_pbf AS PBF ON PBF.id = RTR.id_pbf
            LEFT JOIN db1.masterf_subjenisanggaran AS SJA ON RTR.id_jenisanggaran = SJA.id
            LEFT JOIN db1.user AS UTRM ON RTR.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON RTR.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON RTR.ver_usrakuntansi = UAKN.id
            LEFT JOIN db1.masterf_tipedoc AS TDOK ON RTR.tipe_doc = TDOK.kode
            WHERE
                RTR.sts_deleted = 0
                AND TDOK.modul = 'return'
                AND (
                    (:prefix = 'K' AND RTR.kode_reffkons = :kode)
                    OR (:prefix = 'S' AND RTR.kode_reffpo = :kode)
                    OR (:prefix = 'P' AND RTR.kode_reffpl = :kode)
                    OR (:prefix = 'R' AND RTR.kode_reffrenc = :kode)
                    OR (:prefix = 'O' AND RTR.kode_reffro = :kode)
                    OR (:prefix = 'T' AND RTR.kode_refftrm = :kode)
                    OR TRUE
                )
            ORDER BY RTR.tgl_doc DESC
            LIMIT $limit
            OFFSET $offset
        ";
        $data = $connection->createCommand($sql, $params)->queryAll();

        return json_encode(["total" => $total, "data" => $data]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#add    the original method
     */
    public function actionSave(): void
    {
        // defined in "out"
        [   "kode" => $kode,
            "noDokumen" => $noDokumen,
            "tanggalDokumen" => $tanggalDokumen,
            "kodeRefPerimaan" => $kodeRefTerima,
            "kodeRefPl" => $kodeRefPl,
            "kodeRefPo" => $kodeRefPo,
            "idPemasok" => $idPemasok,
            "idGudangPenyimpanan" => $idGudangPenyimpanan,
            "idJenisAnggaran" => $idJenisAnggaran,
            "idSumberDana" => $idSumberDana,
            "idCaraBayar" => $idCaraBayar,
            "idJenisHarga" => $idJenisHarga,
            "tahunAnggaran" => $tahunAnggaran,
            "bulanAwalAnggaran" => $bulanAwalAnggaran,
            "bulanAkhirAnggaran" => $bulanAkhirAnggaran,
            "ppn" => $ppn,
            "sebelumDiskon" => $nilaiTotal,
            "diskon" => $nilaiDiskon,
            "setelahPpn" => $nilaiPpn,
            "pembulatan" => $nilaiPembulatan,
            "nilaiAkhir" => $nilaiAkhir,
            "keterangan" => $keterangan,
            "action" => $action,
            "verGudang" => $verGudang,
            "idKatalog" => $daftarIdKatalog,
            "jumlahItem" => $daftarJumlahItem,
            "jumlahKemasan" => $daftarJumlahKemasan,
            "hargaItem" => $daftarHargaItem,
            "diskonItem" => $daftarDiskonItem,
            "idRefKatalog" => $daftarIdRefKatalog,
            "kemasan" => $daftarKemasan,
            "idPabrik" => $daftarIdPabrik,
            "noBatch" => $daftarNoBatch,
            "idKemasan" => $daftarIdKemasan,
            "isiKemasan" => $daftarIsiKemasan,
            "idKemasanDepo" => $daftarIdKemasanDepo,
            "hargaKemasan" => $daftarHargaKemasan,
            "diskon_harga" => $daftarDiskonHarga,
            "noUrut" => $daftarNoUrut,
            "tanggalKadaluarsa" => $daftarTanggalKadaluarsa,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $ppn = $toSystemNumber($ppn);

        $dataRetur = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tipe_doc" => "0",
            "kode_refftrm" => $kodeRefTerima,
            "kode_reffpl" => $kodeRefPl,
            "kode_reffpo" => $kodeRefPo,
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
            "keterangan" => $keterangan,
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        // set no Transaksi
        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "RT",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Retur Penerimaan Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUser
            ]);
            $kode = "RT00" . date("Yn") . str_pad($counter, 5, "0", STR_PAD_LEFT);

            $dataRetur = [
                ...$dataRetur,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        if ($verGudang == 1) {
            $dataRetur = [
                ...$dataRetur,
                "ver_gudang" => 1,
                "ver_tglgudang" => $nowValSystem,
                "ver_usrgudang" => $idUser,
            ];
        }

        $dataDetailRetur = [];
        $dataRincianDetailRetur = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            if (!$jumlahKemasan) continue;

            if ($dataDetailRetur[$idKatalog]) {
                $dataDetailRetur[$idKatalog]["jumlah_item"] += $jumlahItem;
                $dataDetailRetur[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;

            } else {
                // inisialisasi
                $hargaItem = $toSystemNumber($daftarHargaItem[$n]);
                $diskonItem = $toSystemNumber($daftarDiskonItem[$n]);

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
                    $phja = ($hargaPerolehan >= $batasBawah and $hargaPerolehan < $batasAtas) ? $angka : $phja;
                }

                $dataDetailRetur[$idKatalog] = [
                    "kode_reff" => $kode,
                    "kode_refftrm" => $kodeRefTerima,
                    "kode_reffpl" => $kodeRefPl,
                    "kode_reffpo" => $kodeRefPo,
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
                    "harga_kemasan" => $toSystemNumber($daftarHargaKemasan[$n]),
                    "diskon_item" => $diskonItem,
                    "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$n]),
                    "hna_item" => $hargaItem,
                    "hp_item" => $hargaPerolehan,
                    "phja_item" => $phja,
                    "hja_item" => $hargaPerolehan + ($hargaPerolehan * $phja / 100),
                    "userid_updt" => $idUser,
                ];
                $n++;
            }

            $dataRincianDetailRetur[$k++] = [
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
                $dataRincianDetailRetur[$k - 1]["tgl_expired"] = $toSystemDate($daftarTanggalKadaluarsa[$i]);
            }
        }

        $daftarField = array_keys($dataRetur);
        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_return
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $adaRetur = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaRetur) throw new DataAlreadyExistException("Retur", $kode, $noDokumen, $transaction);

            $berhasilTambah = $fm->saveData("transaksif_return", $daftarField, $dataRetur);
            if (!$berhasilTambah) throw new FailToInsertException("Retur", $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_penerimaan
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefTerima];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kodeRefTerima, $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_returnrinc", $dataRincianDetailRetur);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Retur", $transaction);

            $berhasilTambah = $fm->saveBatch("tdetailf_return", $dataDetailRetur);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Retur", $transaction);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_return", $daftarField, $dataRetur, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Retur", "Kode", $kode, $transaction);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_penerimaan
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefTerima];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kodeRefTerima, $transaction);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_returnrinc", $dataRincianDetailRetur, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Rincian Retur", "Kode Ref", $kode, $transaction);

            $berhasilUbah = $fm->saveBatch("tdetailf_return", $dataDetailRetur, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Retur", "Kode Ref", $kode, $transaction);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#addOthers    the original method
     */
    public function actionSaveAddOthers(): string
    {
        [   "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
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
            "keterangan" => $keterangan,
            "action" => $action,
            "ver_gudang" => $verGudang,
            "tipe_doc" => $tipeDokumen,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_item" => $daftarJumlahItem,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "harga_item" => $daftarHargaItem,
            "diskon_item" => $daftarDiskonItem,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "no_batch" => $daftarNoBatch,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "harga_kemasan" => $daftarHargaKemasan,
            "diskon_harga" => $daftarDiskonHarga,
            "no_urut" => $daftarNoUrut,
            "tgl_expired" => $daftarTanggalKadaluarsa,
            "userid_updt" => $idUserUpdate,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $dataRetur = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tipe_doc" => $tipeDokumen,
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
            "keterangan" => $keterangan,
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        // set no Transaksi
        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "RT",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("n"),
                "detailkode" => 1,
                "counter" => 1,
                "keterangan" => "Kode Retur Penerimaan Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUserUpdate
            ]);
            $kode = "RT00" . date("Ym") . str_pad($counter, 5, "0", STR_PAD_LEFT);

            $dataRetur = [
                ...$dataRetur,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        if ($verGudang == 1) {
            $dataRetur = [
                ...$dataRetur,
                "ver_gudang" => 1,
                "ver_tglgudang" => $nowValSystem,
                "ver_usrgudang" => $idUser,
            ];
        }

        $dataDetailRetur = [];
        $dataRincianDetailRetur = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            if (!$jumlahKemasan) continue;

            if ($dataDetailRetur[$idKatalog]) {
                $dataDetailRetur[$idKatalog]["jumlah_item"] += $jumlahItem;
                $dataDetailRetur[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;

            } else {
                $hargaItem = $toSystemNumber($daftarHargaItem[$n]);
                $diskonItem = $toSystemNumber($daftarDiskonItem[$n]);

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
                    $phja = ($hargaPerolehan >= $batasBawah and $hargaPerolehan < $batasAtas) ? $angka : $phja;
                }

                $dataDetailRetur[$idKatalog] = [
                    "kode_reff" => $kode,
                    "id_katalog" => $idKatalog,
                    "kemasan" => $daftarKemasan[$n],
                    "id_pabrik" => $daftarIdPabrik[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$n]),
                    "id_kemasandepo" => $daftarIdKemasanDepo[$n],
                    "jumlah_item" => $jumlahItem,
                    "jumlah_kemasan" => $jumlahKemasan,
                    "harga_item" => $hargaItem,
                    "harga_kemasan" => $toSystemNumber($daftarHargaKemasan[$n]),
                    "diskon_item" => $diskonItem,
                    "diskon_harga" => $toSystemNumber($daftarDiskonHarga[$n]),
                    "hna_item" => $hargaItem,
                    "hp_item" => $hargaPerolehan,
                    "phja_item" => $phja,
                    "hja_item" => $hargaPerolehan + ($hargaPerolehan * $phja / 100),
                    "userid_updt" => $idUserUpdate
                ];
                $n++;
            }

            $dataRincianDetailRetur[$k++] = [
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
                $dataRincianDetailRetur[$k - 1]["tgl_expired"] = $toSystemDate($daftarTanggalKadaluarsa[$i]);
            }
        }

        $fm = new FarmasiModel;

        $daftarField = array_keys($dataRetur);
        if ($action == "add") {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_return
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $adaRetur = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaRetur) throw new DataAlreadyExistException("Retur", $kode, $noDokumen);

            $berhasilTambah = $fm->saveData("transaksif_return", $daftarField, $dataRetur);
            if (!$berhasilTambah) throw new FailToInsertException("Retur");

            $berhasilTambah = $fm->saveBatch("tdetailf_returnrinc", $dataRincianDetailRetur);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Retur");

            $fm->saveBatch("tdetailf_return", $dataDetailRetur);

        } else {
            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_return", $daftarField, $dataRetur, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Retur", "Kode", $kode);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_returnrinc", $dataRincianDetailRetur, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Rincian Retur", "Kode Ref", $kode);

            $fm->saveBatch("tdetailf_return", $dataDetailRetur, $iwhere);
        }

        return print_r($dataRincianDetailRetur, true) . print_r($dataDetailRetur, true);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#editOthers    the original method
     */
    public function actionEditOthersData(): string
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
                A.ppn                  AS ppn,                 -- in use
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
                A.ver_terima           AS verTerima,           -- in use
                A.ver_tglterima        AS verTanggalTerima,
                A.ver_usrterima        AS verUserTerima,
                A.ver_gudang           AS verGudang,           -- in use
                A.ver_tglgudang        AS verTanggalGudang,
                A.ver_usrgudang        AS verUserGudang,
                A.ver_akuntansi        AS verAkuntansi,        -- in use
                A.ver_tglakuntansi     AS verTanggalAkuntansi,
                A.ver_usrakuntansi     AS verUserAkuntansi,
                A.userid_in            AS useridInput,
                A.sysdate_in           AS sysdateInput,
                A.userid_updt          AS useridUpdate,
                A.sysdate_updt         AS sysdateUpdate,
                D.kode                 AS kodePemasok,
                D.nama_pbf             AS namaPemasok,
                E.subjenis_anggaran    AS subjenisAnggaran,
                G.jenis_harga          AS jenisHarga,
                IFNULL(UTRM.name, '-') AS namaUserTerima,
                IFNULL(UGDG.name, '-') AS namaUserGudang,      -- in use
                IFNULL(UAKN.name, '-') AS namaUserAkuntansi    -- in use
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
                AND A.ver_terima = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff               AS kodeRef,
                B.id_katalog              AS idKatalog,
                B.kode_reffrenc           AS kodeRefRencana,
                B.kode_reffro             AS kodeRefRo,
                B.kode_reffpo             AS kodeRefPo,
                B.kode_reffpl             AS kodeRefPl,
                B.kode_refftrm            AS kodeRefTerima,
                B.kode_reffkons           AS kodeRefKons,
                B.id_reffkatalog          AS idRefKatalog,
                B.kemasan                 AS kemasan,
                B.id_pabrik               AS idPabrik,
                B.id_kemasan              AS idKemasan,
                B.isi_kemasan             AS isiKemasan,
                B.id_kemasandepo          AS idKemasanDepo,
                B.jumlah_item             AS jumlahItem,
                B.jumlah_kemasan          AS jumlahKemasan,
                B.harga_item              AS hargaItem,
                B.harga_kemasan           AS hargaKemasan,
                B.diskon_item             AS diskonItem,
                B.diskon_harga            AS diskonHarga,
                B.hna_item                AS hnaItem,
                B.hp_item                 AS hpItem,
                B.phja_item               AS phjaItem,
                B.hja_item                AS hjaItem,
                B.keterangan              AS keterangan,
                B.userid_updt             AS useridUpdate,
                B.sysdate_updt            AS sysdateUpdate,
                A.kode_reff               AS kodeRef,
                A.id_katalog              AS idKatalog,
                A.no_reffbatch            AS noRefBatch,
                A.no_batch                AS noBatch,
                IFNULL(A.tgl_expired, '') AS tanggalKadaluarsa,
                A.no_urut                 AS noUrut,
                A.jumlah_item             AS jumlahItem,
                A.jumlah_kemasan          AS jumlahKemasan,
                B.jumlah_item             AS jumlahItemTotal,
                KAT.nama_sediaan          AS namaSediaan,
                PBK.nama_pabrik           AS namaPabrik,
                KSAR.kode                 AS satuanJual,
                KCIL.kode                 AS satuan -- satuan kecil
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kode" => $kode];
        $daftarRinciandetailRetur = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "action" => "edit",
            "data" => $retur,
            "idata" => $daftarRinciandetailRetur,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kode,
                A.keterangan           AS keterangan,
                A.no_doc               AS noDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.kode_refftrm         AS kodeRefPenerimaan,
                A.kode_reffpl          AS kodeRefPl,
                A.kode_reffpo          AS kodeRefPo,
                A.id_pbf               AS idPemasok,
                A.id_gudangpenyimpanan AS idGudangPenyimpanan,
                A.id_jenisanggaran     AS idJenisAnggaran,
                A.id_sumberdana        AS idSumberDana,
                A.id_carabayar         AS idCaraBayar,
                A.id_jenisharga        AS idJenisHarga,
                A.thn_anggaran         AS tahunAnggaran,
                A.blnawal_anggaran     AS bulanAwalAnggaran,
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,
                A.ppn                  AS ppn,
                A.nilai_total          AS sebelumDiskon,
                A.nilai_diskon         AS diskon,
                A.nilai_ppn            AS setelahPpn,
                A.nilai_pembulatan     AS pembulatan,
                A.nilai_akhir          AS nilaiAkhir,
                A.ver_gudang           AS verGudang,
                NULL                   AS objectRefPl,
                NULL                   AS objectRefPo,
                NULL                   AS objectRefPenerimaan,
                NULL                   AS objectPemasok,
                NULL                   AS daftarDetail
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_penerimaan AS T ON A.kode_refftrm = T.kode
            LEFT JOIN db1.transaksif_pembelian AS P ON A.kode_reffpl = P.kode
            LEFT JOIN db1.transaksif_pemesanan AS S ON A.kode_reffpo = S.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.user AS Ut ON A.ver_usrterima = Ut.id
            LEFT JOIN db1.user AS Ug ON A.ver_usrgudang = Ug.id
            LEFT JOIN db1.user AS Ua ON A.ver_usrakuntansi = Ua.id
            WHERE
                A.kode = :kode
                AND A.sts_linked = 0
                AND A.ver_terima = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                B.id_katalog               AS idKatalog,
                B.id_reffkatalog           AS idRefKatalog,
                B.kemasan                  AS kemasan,
                B.id_pabrik                AS idPabrik,
                B.id_kemasan               AS idKemasan,
                B.isi_kemasan              AS isiKemasan,
                B.id_kemasandepo           AS idKemasanDepo,
                B.jumlah_item              AS jumlahItem,
                B.jumlah_kemasan           AS jumlahKemasan,
                B.harga_item               AS hargaItem,
                B.harga_kemasan            AS hargaKemasan,
                B.diskon_item              AS diskonItem,
                B.diskon_harga             AS diskonHarga,
                A.no_batch                 AS noBatch,
                IFNULL(A.tgl_expired, '')  AS tanggalKadaluarsa,
                A.no_urut                  AS noUrut,
                K.jumlah_itembonus         AS jumlahItemBonus,
                K.nama_sediaan             AS namaSediaan,
                PBK.nama_pabrik            AS namaPabrik,
                IFNULL(C.jumlah_item, 0)   AS jumlahTerima,
                IFNULL(ret.jumlah_item, 0) AS jumlahRetur
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS Sj ON Sj.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS S ON S.id = B.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS C ON B.kode_refftrm = C.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS ret ON B.kode_refftrm = ret.kode_refftrm
            WHERE
                A.kode_reff = :kode
                AND B.id_katalog = ret.id_katalog
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kode" => $kode];
        $retur->daftarDetail = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($retur);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#addGas    the original method
     */
    public function actionSaveAddGasMedis(): void
    {
        [   "kode" => $kode,
            "no_doc" => $noDokumen,
            "tgl_doc" => $tanggalDokumen,
            "kode_refftrm" => $kodeRefTerima,
            "id_pbf" => $idPemasok,
            "id_gudangpenyimpanan" => $idGudangPenyimpanan,
            "keterangan" => $keterangan,
            "action" => $action,
            "ver_gudang" => $verGudang,
            "userid_updt" => $idUserUpdate,
            "id_katalog" => $daftarIdKatalog,
            "jumlah_item" => $daftarJumlahItem,
            "jumlah_kemasan" => $daftarJumlahKemasan,
            "kemasan" => $daftarKemasan,
            "id_pabrik" => $daftarIdPabrik,
            "no_batch" => $daftarNoBatch,
            "id_kemasan" => $daftarIdKemasan,
            "isi_kemasan" => $daftarIsiKemasan,
            "id_kemasandepo" => $daftarIdKemasanDepo,
            "no_urut" => $daftarNoUrut,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $dateTime = Yii::$app->dateTime;
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $nowValSystem = $dateTime->nowVal("system");

        $dataRetur = [
            "no_doc" => $noDokumen,
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "tipe_doc" => 5,
            "id_pbf" => $idPemasok,
            "id_gudangpenyimpanan" => $idGudangPenyimpanan,
            "keterangan" => $keterangan,
            "userid_updt" => $idUser,
            "sysdate_updt" => $nowValSystem,
        ];

        // set no Transaksi
        if ($action == "add") {
            $counter = $this->getUpdateTrn([
                "initial" => "RG",
                "unit" => "0000",
                "subunit" => "00",
                "kode" => date("Y"),
                "subkode" => date("m"),
                "detailkode" => 0,
                "counter" => 1,
                "keterangan" => "Kode Retur Penerimaan Tabung GM Bulan " . date("m") . " Tahun " . date("Y"),
                "userid_updt" => $idUserUpdate
            ]);
            $kode = "RG00" . date("Ym") . str_pad($counter, 5, "0", STR_PAD_LEFT);

            $dataRetur = [
                ...$dataRetur,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "kode" => $kode,
            ];
        }

        if ($verGudang == 1) {
            $dataRetur = [
                ...$dataRetur,
                "ver_gudang" => 1,
                "ver_tglgudang" => $nowValSystem,
                "ver_usrgudang" => $idUser,
            ];
        }

        $dataDetailRetur = [];
        $dataRincianDetailRetur = [];
        $n = 0;
        $k = 0;

        foreach ($daftarIdKatalog as $i => $idKatalog) {
            $jumlahKemasan = $toSystemNumber($daftarJumlahKemasan[$i]);
            $jumlahItem = $toSystemNumber($daftarJumlahItem[$i]);
            if (!$jumlahKemasan) continue;

            if ($dataDetailRetur[$idKatalog]) {
                $dataDetailRetur[$idKatalog]["jumlah_kemasan"] += $jumlahKemasan;
                $dataDetailRetur[$idKatalog]["jumlah_item"] += $jumlahItem;

            } else {
                $dataDetailRetur[$idKatalog] = [
                    "kode_reff" => $kode,
                    "id_katalog" => $idKatalog,
                    "kemasan" => $daftarKemasan[$n],
                    "id_pabrik" => $daftarIdPabrik[$n],
                    "id_kemasan" => $daftarIdKemasan[$n],
                    "isi_kemasan" => $toSystemNumber($daftarIsiKemasan[$n]),
                    "id_kemasandepo" => $daftarIdKemasanDepo[$n],
                    "jumlah_kemasan" => $jumlahKemasan,
                    "jumlah_item" => $jumlahItem,
                    "keterangan" => $keterangan,
                    "userid_updt" => $idUserUpdate,
                ];
                $n ++;
            }

            $dataRincianDetailRetur[$k ++] = [
                "kode_reff" => $kode,
                "id_katalog" => $idKatalog,
                "no_batch" => $daftarNoBatch[$i],
                "no_urut" => $daftarNoUrut[$i],
                "jumlah_item" => $jumlahItem,
                "jumlah_kemasan" => $jumlahKemasan
            ];
        }

        $fm = new FarmasiModel;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        $daftarField = array_keys($dataRetur);
        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_return
                WHERE
                    kode = :kode
                    AND no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":kode" => $kode, ":noDokumen" => $noDokumen];
            $adaRetur = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaRetur) throw new DataAlreadyExistException("Retur", $kode, $noDokumen);

            $berhasilTambah = $fm->saveData("transaksif_return", $daftarField, $dataRetur);
            if (!$berhasilTambah) throw new FailToInsertException("Retur");

            $berhasilTambah = $fm->saveBatch("tdetailf_returnrinc", $dataRincianDetailRetur);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Rincian Retur");

            $berhasilTambah = $fm->saveBatch("tdetailf_return", $dataDetailRetur);
            if (!$berhasilTambah) throw new FailToInsertException("Detail Retur");

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_penerimaan
                SET sts_linked = 1
                WHERE kode = :kode
            ";
            $params = [":kode" => $kodeRefTerima];
            $berhasilUbah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilUbah) throw new FailToUpdateException("Penerimaan", "Kode", $kodeRefTerima);

            $where = ["kode" => $kode];
            $berhasilUbah = $fm->saveData("transaksif_return", $daftarField, $dataRetur, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Return", "Kode", $kode);

            $iwhere = ["kode_reff" => $kode];
            $berhasilUbah = $fm->saveBatch("tdetailf_returnrinc", $dataRincianDetailRetur, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Rincian Return", "Kode Ref", $kode);

            $berhasilUbah = $fm->saveBatch("tdetailf_return", $dataDetailRetur, $iwhere);
            if (!$berhasilUbah) throw new FailToUpdateException("Detail Retur", "Kode Ref", $kode);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#editGas    the original method
     */
    public function actionDataEditGasMedis(): string
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
                A.ppn                                      AS ppn,                      -- in use
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_linked                               AS statusLinked,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalRevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,                -- in use
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,                -- in use
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,             -- in use
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                B.no_doc                                   AS noPo,
                IFNULL(B.kode, '-')                        AS kodeRefPo,
                IFNULL(C.kode, '-')                        AS kodeRefPl,
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
                F.no_doc                                   AS noTerima,
                F.blnawal_anggaran                         AS bulanAwalAnggaranTerima,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranTerima,
                F.tipe_doc                                 AS tipeTerima,
                F.thn_anggaran                             AS tahunAnggaranTerima,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,           -- in use
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi         -- in use
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.transaksif_penerimaan AS F ON A.kode_refftrm = F.kode
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.ver_terima = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.kode_reffrenc              AS kodeRefRencana,
                B.kode_reffro                AS kodeRefRo,
                B.kode_reffpo                AS kodeRefPo,
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
                B.harga_item                 AS hargaItem,
                B.harga_kemasan              AS hargaKemasan,
                B.diskon_item                AS diskonItem,
                B.diskon_harga               AS diskonHarga,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.phja_item                  AS phjaItem,
                B.hja_item                   AS hjaItem,
                B.keterangan                 AS keterangan,
                B.userid_updt                AS useridUpdate,
                B.sysdate_updt               AS sysdateUpdate,
                A.kode_reff                  AS kodeRef,
                A.id_katalog                 AS idKatalog,
                A.no_reffbatch               AS noRefBatch,
                A.no_batch                   AS noBatch,
                A.tgl_expired                AS tanggalKadaluarsa,
                A.no_urut                    AS noUrut,
                A.jumlah_item                AS jumlahItem,
                A.jumlah_kemasan             AS jumlahKemasan,
                masterf_katalog.nama_sediaan AS namaSediaan,
                masterf_pabrik.nama_pabrik   AS namaPabrik,
                KSAR.kode                    AS satuanJual,
                KCIL.kode                    AS satuan,  -- satuan kecil
                D.jumlah_kemasan             AS stokAdm
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog ON masterf_katalog.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik ON masterf_pabrik.id = masterf_katalog.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = masterf_katalog.id_kemasankecil
            LEFT JOIN (
                SELECT
                    id_unitposisi                  AS id_unitposisi,
                    id_katalog                     AS id_katalog,
                    IFNULL(COUNT(C.id_katalog), 0) AS jumlah_kemasan
                FROM db1.transaksif_seritabung AS C
                WHERE
                    C.id_unitposisi = 60
                    AND C.sts_tersedia = 0
                    AND C.sts_aktif = 1
                GROUP BY id_unitposisi, id_katalog
            ) D ON A.id_katalog = D.id_katalog
            WHERE
                A.kode_reff = :kodeRef
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kodeRef" => $kode];
        $daftarRincianDetailRetur = $connection->createCommand($sql, $params)->queryAll();

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
                A.id_unitposisi = 60
                AND A.sts_aktif = 1
                AND A.sts_tersedia = 0
                AND A.id_katalog IN (
                    SELECT id_katalog
                    FROM db1.tdetailf_return
                    WHERE kode_reff = :kodeRef
                )
        ";
        $params = [":kodeRef" => $kode];
        $daftarSeriTabung = $connection->createCommand($sql, $params)->queryAll();

        $noBatch = [];
        foreach ($daftarSeriTabung as $bch) {
            $noBatch[$bch->idKatalog] ??= [];
            $noBatch[$bch->idKatalog][] = $bch;
        }

        return json_encode(["data" => $retur, "idata" => $daftarRincianDetailRetur, "noBatch" => $noBatch]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verTerima    the original method
     */
    public function actionDataVerTerimaObat(): string
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
                A.ppn                                      AS ppn,                                -- in use
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_linked                               AS statusLinked,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalRevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,                          -- in use
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,                          -- in use
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,                       -- in use
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                B.no_doc                                   AS noPo,
                IFNULL(B.kode, '-')                        AS kodeRefPo,
                IFNULL(C.kode, '-')                        AS kodeRefPl,
                IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim,
                B.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                B.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                B.thn_anggaran                             AS tahunAnggaranPo,
                C.no_doc                                   AS noSpk,
                C.tipe_doc                                 AS tipeSpk,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                F.no_doc                                   AS noTerima,
                F.blnawal_anggaran                         AS bulanAwalAnggaranTerima,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranTerima,
                F.tipe_doc                                 AS tipeTerima,               -- in use
                F.thn_anggaran                             AS tahunAnggaranTerima,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,           -- in use
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,           -- in use
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi         -- in use
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.transaksif_penerimaan AS F ON A.kode_refftrm = F.kode
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.ver_gudang = 1
                AND A.ver_terima = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.kode_reffrenc              AS kodeRefRencana,
                B.kode_reffro                AS kodeRefRo,
                B.kode_reffpo                AS kodeRefPo,
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
                B.harga_item                 AS hargaItem,
                B.harga_kemasan              AS hargaKemasan,
                B.diskon_item                AS diskonItem,
                B.diskon_harga               AS diskonHarga,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.phja_item                  AS phjaItem,
                B.hja_item                   AS hjaItem,
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
                B.jumlah_item                AS jumlahItemTotal,
                K.nama_sediaan               AS namaSediaan,              
                K.kemasan                    AS kemasanKat,
                K.id_kemasanbesar            AS idKemasanKat,
                K.id_kemasankecil            AS idKemasanDepoKat,
                K.isi_kemasan                AS isiKemasanKat,
                K.harga_beli                 AS hargaItemKat,
                K.harga_beli * K.diskon_beli AS hargaKemasanKat,
                K.diskon_beli                AS diskonItemKat,
                J.kode                       AS satuanJual,
                S.kode                       AS satuan,
                KSAR.kode                    AS satuanJualKat,
                KCIL.kode                    AS satuanKat, -- satuan kecil
                PBK.nama_pabrik              AS namaPabrik,
                IFNULL(C.jumlah_item, 0)     AS jumlahTerima,
                IFNULL(ret.jumlah_item, 0)   AS jumlahRetur,
                D.jumlah_item                AS stokAdm,
                0                            AS jumlahRencana,
                0                            AS jumlahPl,
                0                            AS jumlahDo
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS J ON J.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS S ON S.id = B.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS C ON B.kode_refftrm = C.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS ret ON B.kode_refftrm = ret.kode_refftrm
            LEFT JOIN (
                SELECT
                    A.id_unit                    AS id_unit,
                    A.id_katalog                 AS id_katalog,
                    IFNULL(SUM(A.jumlah_adm), 0) AS jumlah_item
                FROM db1.transaksif_stokkatalogrinc AS A
                WHERE
                    A.jumlah_adm > 0
                    AND A.id_unit = 60
                GROUP BY A.id_unit, A.id_katalog
            ) AS D ON A.id_katalog = D.id_katalog
            WHERE
                A.kode_reff = :kode
                AND B.id_katalog = ret.id_katalog
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kode" => $kode];
        $daftarRincianDetailRetur = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "data" => $retur,
            "idata" => $daftarRincianDetailRetur,
            "headingTitle" => "Verifikasi Tim Penerima untuk Retur Barang",
            "action" => "ver_terima",
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verTerima    the original method
     */
    public function actionDataVerTerimaGasMedis(): string
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
                A.ppn                                      AS ppn,                                -- in use
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_linked                               AS statusLinked,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalRevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,                          -- in use
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,                          -- in use
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,                       -- in use
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                B.no_doc                                   AS noPo,
                IFNULL(B.kode, '-')                        AS kodeRefPo,
                IFNULL(C.kode, '-')                        AS kodeRefPl,
                IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim,
                B.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                B.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                B.thn_anggaran                             AS tahunAnggaranPo,
                C.no_doc                                   AS noSpk,
                C.tipe_doc                                 AS tipeSpk,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                F.no_doc                                   AS noTerima,
                F.blnawal_anggaran                         AS bulanAwalAnggaranTerima,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranTerima,
                F.tipe_doc                                 AS tipeTerima,               -- in use
                F.thn_anggaran                             AS tahunAnggaranTerima,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,           -- in use
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,           -- in use
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi         -- in use
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.transaksif_penerimaan AS F ON A.kode_refftrm = F.kode
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.ver_gudang = 1
                AND A.ver_terima = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.kode_reffrenc              AS kodeRefRencana,
                B.kode_reffro                AS kodeRefRo,
                B.kode_reffpo                AS kodeRefPo,
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
                B.harga_item                 AS hargaItem,
                B.harga_kemasan              AS hargaKemasan,
                B.diskon_item                AS diskonItem,
                B.diskon_harga               AS diskonHarga,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.phja_item                  AS phjaItem,
                B.hja_item                   AS hjaItem,
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
                B.jumlah_item                AS jumlahItemTotal,
                K.nama_sediaan               AS namaSediaan,              
                K.kemasan                    AS kemasanKat,
                K.id_kemasanbesar            AS idKemasanKat,
                K.id_kemasankecil            AS idKemasanDepoKat,
                K.isi_kemasan                AS isiKemasanKat,
                K.harga_beli                 AS hargaItemKat,
                K.harga_beli * K.diskon_beli AS hargaKemasanKat,
                K.diskon_beli                AS diskonItemKat,
                J.kode                       AS satuanJual,
                S.kode                       AS satuan,
                KSAR.kode                    AS satuanJualKat,
                KCIL.kode                    AS satuanKat, -- satuan kecil
                PBK.nama_pabrik              AS namaPabrik,
                IFNULL(C.jumlah_item, 0)     AS jumlahTerima,
                IFNULL(ret.jumlah_item, 0)   AS jumlahRetur,
                D.jumlah_item                AS stokAdm,
                0                            AS jumlahRencana,
                0                            AS jumlahPl,
                0                            AS jumlahDo
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS J ON J.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS S ON S.id = B.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS C ON B.kode_refftrm = C.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS ret ON B.kode_refftrm = ret.kode_refftrm
            LEFT JOIN (
                SELECT
                    A.id_unit                    AS id_unit,
                    A.id_katalog                 AS id_katalog,
                    IFNULL(SUM(A.jumlah_adm), 0) AS jumlah_item
                FROM db1.transaksif_stokkatalogrinc AS A
                WHERE
                    A.jumlah_adm > 0
                    AND A.id_unit = 60
                GROUP BY A.id_unit, A.id_katalog
            ) AS D ON A.id_katalog = D.id_katalog
            WHERE
                A.kode_reff = :kode
                AND B.id_katalog = ret.id_katalog
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kode" => $kode];
        $daftarRincianDetailRetur = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                     AS idKatalog,
                A.id_unit                                        AS idUnit,
                A.no_batch                                       AS noBatch,
                A.tgl_expired                                    AS tanggalKadaluarsa,
                A.jumlah_fisik                                   AS jumlahFisik,
                A.jumlah_adm                                     AS jumlahAdm,
                IF(B.kd_unitpemilik = 2, D.namaDepo, C.nama_pbf) AS unitPemilik,
                C.nama_pbf                                       AS namaPemasok,
                D.namaDepo                                       AS namaDepo,
                B.kd_unitpemilik                                 AS kodeUnitPemilik
            FROM db1.transaksif_stokkatalogrinc AS A
            LEFT JOIN rsupf_latihan.masterf_batchtabung AS B ON A.id_katalog = B.id_katalog
            LEFT JOIN db1.masterf_pbf AS C ON B.id_unitpemilik = C.id
            LEFT JOIN db1.masterf_depo AS D ON B.id_unitpemilik = D.id
            WHERE
                A.id_unit = 60
                AND A.sts_aktif = 1
                AND A.jumlah_adm > 0
                AND A.no_batch = B.no_batch
                AND A.id_katalog IN (
                    SELECT id_katalog
                    FROM db1.tdetailf_return
                    WHERE kode_reff = :kode
                )
        ";
        $params = [":kode" => $kode];
        $daftarRincianStokKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "data" => $retur,
            "idata" => $daftarRincianDetailRetur,
            "noBatch" => $daftarRincianStokKatalog,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verTerima    the original method
     */
    public function actionDataVerTerimaLainnya(): string
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
                A.ppn                                      AS ppn,                                -- in use
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_linked                               AS statusLinked,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalRevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,                          -- in use
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,                          -- in use
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,                       -- in use
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                B.no_doc                                   AS noPo,
                IFNULL(B.kode, '-')                        AS kodeRefPo,
                IFNULL(C.kode, '-')                        AS kodeRefPl,
                IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim,
                B.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                B.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                B.thn_anggaran                             AS tahunAnggaranPo,
                C.no_doc                                   AS noSpk,
                C.tipe_doc                                 AS tipeSpk,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                F.no_doc                                   AS noTerima,
                F.blnawal_anggaran                         AS bulanAwalAnggaranTerima,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranTerima,
                F.tipe_doc                                 AS tipeTerima,               -- in use
                F.thn_anggaran                             AS tahunAnggaranTerima,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,           -- in use
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,           -- in use
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi         -- in use
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.transaksif_penerimaan AS F ON A.kode_refftrm = F.kode
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.ver_gudang = 1
                AND A.ver_terima = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.kode_reffrenc              AS kodeRefRencana,
                B.kode_reffro                AS kodeRefRo,
                B.kode_reffpo                AS kodeRefPo,
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
                B.harga_item                 AS hargaItem,
                B.harga_kemasan              AS hargaKemasan,
                B.diskon_item                AS diskonItem,
                B.diskon_harga               AS diskonHarga,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.phja_item                  AS phjaItem,
                B.hja_item                   AS hjaItem,
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
                B.jumlah_item                AS jumlahItemTotal,
                K.nama_sediaan               AS namaSediaan,              
                K.kemasan                    AS kemasanKat,
                K.id_kemasanbesar            AS idKemasanKat,
                K.id_kemasankecil            AS idKemasanDepoKat,
                K.isi_kemasan                AS isiKemasanKat,
                K.harga_beli                 AS hargaItemKat,
                K.harga_beli * K.diskon_beli AS hargaKemasanKat,
                K.diskon_beli                AS diskonItemKat,
                J.kode                       AS satuanJual,
                S.kode                       AS satuan,
                KSAR.kode                    AS satuanJualKat,
                KCIL.kode                    AS satuanKat, -- satuan kecil
                PBK.nama_pabrik              AS namaPabrik,
                IFNULL(C.jumlah_item, 0)     AS jumlahTerima,
                IFNULL(ret.jumlah_item, 0)   AS jumlahRetur,
                D.jumlah_item                AS stokAdm,
                0                            AS jumlahRencana,
                0                            AS jumlahPl,
                0                            AS jumlahDo
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS K ON K.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = K.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS J ON J.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS S ON S.id = B.id_kemasandepo
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = K.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = K.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS C ON B.kode_refftrm = C.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kode
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS ret ON B.kode_refftrm = ret.kode_refftrm
            LEFT JOIN (
                SELECT
                    A.id_unit                    AS id_unit,
                    A.id_katalog                 AS id_katalog,
                    IFNULL(SUM(A.jumlah_adm), 0) AS jumlah_item
                FROM db1.transaksif_stokkatalogrinc AS A
                WHERE
                    A.jumlah_adm > 0
                    AND A.id_unit = 60
                GROUP BY A.id_unit, A.id_katalog
            ) AS D ON A.id_katalog = D.id_katalog
            WHERE
                A.kode_reff = :kode
                AND B.id_katalog = ret.id_katalog
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kode" => $kode];
        $daftarRincianDetailRetur = $connection->createCommand($sql, $params)->queryAll();

        return json_encode(["data" => $retur, "idata" => $daftarRincianDetailRetur]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws \Exception
     * @throws FailToUpdateException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verAkuntansi    the original method
     */
    public function actionSaveVerAkuntansi(): void
    {
        // defined in "VerAkun"
        ["kode" => $kode, "verAkuntansi" => $verAkuntansi] = Yii::$app->request->post();
        if ($verAkuntansi != 1) throw new \Exception("Verifikasi Akuntansi gagal. Anda belum melakukan checklist verifikasi.");

        $dataRetur = [
            "kode" => $kode,
            "ver_akuntansi" => 1,
            "ver_tglakuntansi" => Yii::$app->dateTime->nowVal("system"),
            "ver_usrakuntansi" => Yii::$app->userFatma->id,
        ];

        $daftarField = array_keys($dataRetur);
        $where = ["kode" => $kode];
        $berhasilUbah = (new FarmasiModel)->saveData("transaksif_return", $daftarField, $dataRetur, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Return", "Kode", $kode);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verAkuntansi    the original method
     */
    public function actionDataVerAkuntansiObat(): string
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
                A.ppn                                      AS ppn,                      -- in use
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_linked                               AS statusLinked,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalrevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,                -- in use
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,                -- in use
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,             -- in use
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                B.no_doc                                   AS noPo,
                IFNULL(B.kode, '-')                        AS kodeRefPo,
                IFNULL(C.kode, '-')                        AS kodeRefPl,
                IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim1,
                B.tgl_tempokirim                           AS tanggalTempoKirim2,
                B.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                B.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                B.thn_anggaran                             AS tahunAnggaranPo,
                C.no_doc                                   AS noSpk,
                C.tipe_doc                                 AS tipeSpk,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                F.no_doc                                   AS noTerima,
                F.blnawal_anggaran                         AS bulanAwalAnggaranTerima,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranTerima,
                F.tipe_doc                                 AS tipeTerima,
                F.thn_anggaran                             AS tahunAnggaranTerima,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,           -- in use
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,           -- in use
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi         -- in use
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.transaksif_penerimaan AS F ON A.kode_refftrm = F.kode
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.ver_gudang = 1
                AND A.ver_terima = 1
                AND A.ver_akuntansi = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $isql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.kode_reffrenc              AS kodeRefRencana,
                B.kode_reffro                AS kodeRefRo,
                B.kode_reffpo                AS kodeRefPo,
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
                B.harga_item                 AS hargaItem,
                B.harga_kemasan              AS hargaKemasan,
                B.diskon_item                AS diskonItem,
                B.diskon_harga               AS diskonHarga,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.phja_item                  AS phjaItem,
                B.hja_item                   AS hjaItem,
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
                B.jumlah_item                AS jumlahItemTotal,
                masterf_katalog.nama_sediaan AS namaSediaan,
                masterf_pabrik.nama_pabrik   AS namaPabrik,
                KSAR.kode                    AS satuanJual,
                KCIL.kode                    AS satuan, -- satuan kecil
                IFNULL(C.jumlah_item, 0)     AS jumlahTerima,
                IFNULL(ret.jumlah_item, 0)   AS jumlahRetur,
                0                            AS jumlahRencana,
                0                            AS jumlahPl,
                0                            AS jumlahDo
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog ON masterf_katalog.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik ON masterf_pabrik.id = masterf_katalog.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = masterf_katalog.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS C ON B.kode_refftrm = C.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm,
                    A.id_katalog,
                    SUM(A.jumlah_item) jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kodeRef
                GROUP BY A.kode_refftrm, A.id_katalog
            ) ret ON B.kode_refftrm = ret.kode_refftrm
            WHERE
                A.kode_reff = :kodeRef
                AND B.id_katalog = ret.id_katalog
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kodeRef" => $kode];
        $daftarRincianDetailRetur = $connection->createCommand($isql, $params)->queryAll();

        return json_encode([
            "data" => $retur,
            "idata" => $daftarRincianDetailRetur,
            "headingTitle" => "Retur Barang CN Faktur",
            "action" => "ver_akuntansi",
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verAkuntansi    the original method
     */
    public function actionDataVerAkuntansiGasMedis(): string
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
                A.ppn                                      AS ppn,                      -- in use
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_linked                               AS statusLinked,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalrevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,                -- in use
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,                -- in use
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,             -- in use
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                B.no_doc                                   AS noPo,
                IFNULL(B.kode, '-')                        AS kodeRefPo,
                IFNULL(C.kode, '-')                        AS kodeRefPl,
                IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim1,
                B.tgl_tempokirim                           AS tanggalTempoKirim2,
                B.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                B.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                B.thn_anggaran                             AS tahunAnggaranPo,
                C.no_doc                                   AS noSpk,
                C.tipe_doc                                 AS tipeSpk,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                F.no_doc                                   AS noTerima,
                F.blnawal_anggaran                         AS bulanAwalAnggaranTerima,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranTerima,
                F.tipe_doc                                 AS tipeTerima,
                F.thn_anggaran                             AS tahunAnggaranTerima,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,           -- in use
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,           -- in use
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi         -- in use
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.transaksif_penerimaan AS F ON A.kode_refftrm = F.kode
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.ver_gudang = 1
                AND A.ver_terima = 1
                AND A.ver_akuntansi = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $isql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.kode_reffrenc              AS kodeRefRencana,
                B.kode_reffro                AS kodeRefRo,
                B.kode_reffpo                AS kodeRefPo,
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
                B.harga_item                 AS hargaItem,
                B.harga_kemasan              AS hargaKemasan,
                B.diskon_item                AS diskonItem,
                B.diskon_harga               AS diskonHarga,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.phja_item                  AS phjaItem,
                B.hja_item                   AS hjaItem,
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
                B.jumlah_item                AS jumlahItemTotal,
                masterf_katalog.nama_sediaan AS namaSediaan,
                masterf_pabrik.nama_pabrik   AS namaPabrik,
                KSAR.kode                    AS satuanJual,
                KCIL.kode                    AS satuan, -- satuan kecil
                IFNULL(C.jumlah_item, 0)     AS jumlahTerima,
                IFNULL(ret.jumlah_item, 0)   AS jumlahRetur,
                0                            AS jumlahRencana,
                0                            AS jumlahPl,
                0                            AS jumlahDo
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog ON masterf_katalog.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik ON masterf_pabrik.id = masterf_katalog.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = masterf_katalog.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS C ON B.kode_refftrm = C.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm,
                    A.id_katalog,
                    SUM(A.jumlah_item) jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kodeRef
                GROUP BY A.kode_refftrm, A.id_katalog
            ) ret ON B.kode_refftrm = ret.kode_refftrm
            WHERE
                A.kode_reff = :kodeRef
                AND A.id_katalog = B.id_katalog
                AND B.id_katalog = ret.id_katalog
                AND B.id_katalog = C.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kodeRef" => $kode];
        $daftarRincianDetailRetur = $connection->createCommand($isql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                     AS idKatalog,
                A.id_unit                                        AS idUnit,
                A.no_batch                                       AS noBatch,
                A.tgl_expired                                    AS tanggalKadaluarsa,
                A.jumlah_fisik                                   AS jumlahFisik,
                A.jumlah_adm                                     AS jumlahAdm,
                IF(B.kd_unitpemilik = 2, D.namaDepo, C.nama_pbf) AS unitPemilik,
                C.nama_pbf                                       AS namaPemasok,
                D.namaDepo                                       AS namaDepo,
                B.kd_unitpemilik                                 AS kodeUnitPemilik
            FROM db1.transaksif_stokkatalogrinc AS A
            LEFT JOIN rsupf_revisi.masterf_batchtabung AS B ON A.id_katalog = B.id_katalog
            LEFT JOIN db1.masterf_pbf AS C ON B.id_unitpemilik = C.id
            LEFT JOIN db1.masterf_depo AS D ON B.id_unitpemilik = D.id
            WHERE
                A.id_unit = 60
                AND A.sts_aktif = 1
                AND A.jumlah_adm > 0
                AND A.no_batch = B.no_batch
                AND A.id_katalog IN (
                    SELECT id_katalog
                    FROM db1.tdetailf_return
                    WHERE kode_reff = :kodeRef
                )
        ";
        $params = [":kodeRef" => $kode];
        $daftarRincianStokKatalog = $connection->createCommand($sql, $params)->queryAll();

        // view: FormGasMedis
        return json_encode([
            "data" => $retur,
            "idata" => $daftarRincianDetailRetur,
            "noBatch" => $daftarRincianStokKatalog,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#verAkuntansi    the original method
     */
    public function actionDataVerAkuntansiLainnya(): string
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
                A.ppn                                      AS ppn,                      -- in use
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.sts_linked                               AS statusLinked,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.sts_izinrevisi                           AS statusIzinRevisi,
                A.ver_tglizinrevisi                        AS verTanggalIzinRevisi,
                A.ver_usrizinrevisi                        AS verUserIzinRevisi,
                A.ver_revisi                               AS verRevisi,
                A.ver_tglrevisi                            AS verTanggalrevisi,
                A.ver_usrrevisi                            AS verUserRevisi,
                A.ver_terima                               AS verTerima,                -- in use
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_usrterima                            AS verUserTerima,
                A.ver_gudang                               AS verGudang,                -- in use
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_usrgudang                            AS verUserGudang,
                A.ver_akuntansi                            AS verAkuntansi,             -- in use
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_usrakuntansi                         AS verUserAkuntansi,
                A.userid_in                                AS useridInput,
                A.sysdate_in                               AS sysdateInput,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                B.no_doc                                   AS noPo,
                IFNULL(B.kode, '-')                        AS kodeRefPo,
                IFNULL(C.kode, '-')                        AS kodeRefPl,
                IFNULL(B.tgl_tempokirim, C.tgl_jatuhtempo) AS tanggalTempoKirim1,
                B.tgl_tempokirim                           AS tanggalTempoKirim2,
                B.blnawal_anggaran                         AS bulanAwalAnggaranPo,
                B.blnakhir_anggaran                        AS bulanAkhirAnggaranPo,
                B.thn_anggaran                             AS tahunAnggaranPo,
                C.no_doc                                   AS noSpk,
                C.tipe_doc                                 AS tipeSpk,
                C.blnakhir_anggaran                        AS bulanAkhirAnggaranPl,
                C.blnawal_anggaran                         AS bulanAwalAnggaranPl,
                C.thn_anggaran                             AS tahunAnggaranPl,
                D.kode                                     AS kodePemasok,
                D.nama_pbf                                 AS namaPemasok,
                E.subjenis_anggaran                        AS subjenisAnggaran,
                F.no_doc                                   AS noTerima,
                F.blnawal_anggaran                         AS bulanAwalAnggaranTerima,
                F.blnakhir_anggaran                        AS bulanAkhirAnggaranTerima,
                F.tipe_doc                                 AS tipeTerima,
                F.thn_anggaran                             AS tahunAnggaranTerima,
                G.jenis_harga                              AS jenisHarga,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,           -- in use
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,           -- in use
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi         -- in use
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reffpo = B.kode
            LEFT JOIN db1.transaksif_pembelian AS C ON A.kode_reffpl = C.kode
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisanggaran = E.id
            LEFT JOIN db1.transaksif_penerimaan AS F ON A.kode_refftrm = F.kode
            LEFT JOIN db1.masterf_jenisharga AS G ON A.id_jenisharga = G.id
            LEFT JOIN db1.user AS UTRM ON A.ver_usrterima = UTRM.id
            LEFT JOIN db1.user AS UGDG ON A.ver_usrgudang = UGDG.id
            LEFT JOIN db1.user AS UAKN ON A.ver_usrakuntansi = UAKN.id
            WHERE
                A.kode = :kode
                AND A.ver_gudang = 1
                AND A.ver_terima = 1
                AND A.ver_akuntansi = 0
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $isql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode_reff                  AS kodeRef,
                B.id_katalog                 AS idKatalog,
                B.kode_reffrenc              AS kodeRefRencana,
                B.kode_reffro                AS kodeRefRo,
                B.kode_reffpo                AS kodeRefPo,
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
                B.harga_item                 AS hargaItem,
                B.harga_kemasan              AS hargaKemasan,
                B.diskon_item                AS diskonItem,
                B.diskon_harga               AS diskonHarga,
                B.hna_item                   AS hnaItem,
                B.hp_item                    AS hpItem,
                B.phja_item                  AS phjaItem,
                B.hja_item                   AS hjaItem,
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
                B.jumlah_item                AS jumlahItemTotal,
                masterf_katalog.nama_sediaan AS namaSediaan,
                masterf_pabrik.nama_pabrik   AS namaPabrik,
                KSAR.kode                    AS satuanJual,
                KCIL.kode                    AS satuan, -- satuan kecil
                IFNULL(C.jumlah_item, 0)     AS jumlahTerima,
                IFNULL(ret.jumlah_item, 0)   AS jumlahRetur,
                0                            AS jumlahRencana,
                0                            AS jumlahPl,
                0                            AS jumlahDo
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog ON masterf_katalog.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik ON masterf_pabrik.id = masterf_katalog.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = B.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = masterf_katalog.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS C ON B.kode_refftrm = C.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm,
                    A.id_katalog,
                    SUM(A.jumlah_item) jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND A.kode_reff != :kodeRef
                GROUP BY A.kode_refftrm, A.id_katalog
            ) ret ON B.kode_refftrm = ret.kode_refftrm
            WHERE
                A.kode_reff = :kodeRef
                AND B.id_katalog = ret.id_katalog
                AND B.id_katalog = C.id_katalog
                AND A.id_katalog = B.id_katalog
            ORDER BY nama_sediaan, no_urut
        ";
        $params = [":kodeRef" => $kode];
        $daftarRincianDetailRetur = $connection->createCommand($isql, $params)->queryAll();

        return json_encode([
            "data" => $retur,
            "idata" => $daftarRincianDetailRetur,
            "headingTitle" => "Retur Barang",
            "action" => "ver_akuntansi",
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws FailToInsertException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @throws FailToUpdateException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#doVerterima the original method
     */
    public function actionSaveVerTerima(): void
    {
        // defined in "VerTerima"
        [   "kode" => $kode,
            "verTerima" => $verTerima,
            "verTanggalGudang" => $verTanggalGudang,
        ] = Yii::$app->request->post();
        if ($verTerima != 1) throw new \Exception("Verifikasi Penerimaan gagal. Anda belum melakukan checklist verifikasi.");

        $verUserTerima = Yii::$app->userFatma->id;
        $verTanggalTerima = Yii::$app->dateTime->nowVal("system");

        $data = [
            "kode" => $kode,
            "ver_terima" => 1,
            "ver_tglterima" => $verTanggalTerima,
            "ver_usrterima" => $verUserTerima,
        ];

        $daftarField = array_keys($data);
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog       AS idKatalog,
                A.jumlah_item      AS jumlahItem,
                G.jumlah_stokadm   AS jumlahStokAdm,
                G.jumlah_stokfisik AS jumlahStokFisik
            FROM db1.tdetailf_return AS A
            LEFT JOIN db1.transaksif_return AS C ON A.kode_reff = C.kode
            LEFT JOIN db1.transaksif_stokkatalog AS G ON C.id_gudangpenyimpanan = G.id_depo
            WHERE
                A.kode_reff = :kodeRef
                AND C.ver_gudang = 1
                AND G.jumlah_stokfisik < A.jumlah_item
                AND G.id_katalog = A.id_katalog
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailRetur = $connection->createCommand($sql, $params)->queryAll();
        if ($daftarDetailRetur) throw new \Exception("Satu atau lebih Item barang di gudang tidak mencukupi untuk dilakukan retur. Silahkan Cek Stok Gudang tersebut terlebih dahulu.");

        $transaction = $connection->beginTransaction();

        // update status ver gudang n update stock => transaksif_return
        $where = ["kode" => $kode];
        $berhasilUbah = (new FarmasiModel)->saveData("transaksif_return", $daftarField, $data, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Return", "Kode", $kode, $transaction);

        // dapatkan semua barang yang diterima
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff            AS kodeRef,
                A.id_katalog           AS idKatalog,
                A.no_reffbatch         AS noRefBatch,
                A.no_batch             AS noBatch,
                A.tgl_expired          AS tanggalKadaluarsa,
                A.no_urut              AS noUrut,            -- in use
                A.jumlah_item          AS jumlahItem,
                A.jumlah_kemasan       AS jumlahKemasan,
                B.kode_reff            AS kodeRef,
                B.id_katalog           AS idKatalog,         -- in use
                B.kode_reffrenc        AS kodeRefRencana,
                B.kode_reffro          AS kodeRefRo,
                B.kode_reffpo          AS kodeRefPo,
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
                B.harga_item           AS hargaItem,         -- in use
                B.harga_kemasan        AS hargaKemasan,      -- in use
                B.diskon_item          AS diskonItem,        -- in use
                B.diskon_harga         AS diskonHarga,
                B.hna_item             AS hnaItem,           -- in use
                B.hp_item              AS hpItem,            -- in use
                B.phja_item            AS phjaItem,          -- in use
                B.hja_item             AS hjaItem,           -- in use
                B.keterangan           AS keterangan,
                B.userid_updt          AS useridUpdate,
                B.sysdate_updt         AS sysdateUpdate,
                C.no_doc               AS noDokumen,         -- in use
                C.ppn                  AS ppn,               -- in use
                C.id_gudangpenyimpanan AS idDepo,            -- in use
                D.kode                 AS kodeSo,            -- in use
                D.tgl_adm              AS tanggalAdm,        -- in use
                E.kd_unit              AS kodeStore,         -- in use
                F.nama_pbf             AS namaPemasok,       -- in use
                C.tgl_doc              AS tanggalDokumen,    -- in use
                G.jumlah_stokadm       AS jumlahStokAdm,
                G.jumlah_stokfisik     AS jumlahStokFisik
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.transaksif_return AS C ON A.kode_reff = C.kode
            LEFT JOIN db1.transaksif_stokopname AS D ON C.id_gudangpenyimpanan = D.id_depo
            LEFT JOIN db1.transaksif_stokkatalog AS G ON C.id_gudangpenyimpanan = G.id_depo
            LEFT JOIN db1.masterf_depo AS E ON C.id_gudangpenyimpanan = E.id
            LEFT JOIN db1.masterf_pbf AS F ON C.id_pbf = F.id
            WHERE
                A.kode_reff = :kodeRef
                AND C.ver_gudang = 1
                AND G.id_katalog = A.id_katalog
                AND D.sts_aktif = 1
                AND A.id_katalog = B.id_katalog
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailRincianRetur = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarDetailRincianRetur) throw new DataNotExistException("Detail Rincian Retur", $kode);

        foreach ($daftarDetailRincianRetur as $drRetur) {
            $tanggalTerima = $toUserDate($drRetur->tanggalDokumen);

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
                    :idDepo,                            -- 1
                    A.kode_reff,                        -- 2
                    :noDokumen,                         -- 3
                    :ppn,                               -- 4
                    :kodeStokopname,                    -- 5
                    :tanggalAdm,                        -- 6
                    :tanggalTransaksi,                  -- 7
                    :bulanTransaksi,                    -- 8
                    :tahunTransaksi,                    -- 9
                    'RT',                               -- 10
                    :kodeStore,                         -- 11
                    'return',                           -- 12
                    :tanggalTersedia,                   -- 13
                    A.no_batch,                         -- 14
                    A.tgl_expired,                      -- 15
                    A.id_katalog,                       -- 16
                    :idPabrik,                          -- 17
                    :idKemasan,                         -- 18
                    :isiKemasan,                        -- 19
                    B.jumlah_stokfisik,                 -- 20
                    0,                                  -- 21
                    A.jumlah_item,                      -- 22
                    B.jumlah_stokfisik - A.jumlah_item, -- 23
                    :hargaNetoApotik,                   -- 24
                    :hargaPerolehan,                    -- 25
                    :phjaItem,                          -- 26
                    :phjaItem,                          -- 27
                    :hargaJualApotik,                   -- 28
                    A.jumlah_item,                      -- 29
                    (A.jumlah_item / :isiKemasan),      -- 30
                    :hargaItem,                         -- 31
                    :hargaKemasan,                      -- 32
                    :diskonItem,                        -- 33
                    1,                                  -- 34
                    :keterangan,                        -- 35
                    :idUserUbah,                        -- 36
                    :tanggalTersedia,                   -- 37
                    ''                                  -- not exist in original source (error supressor)
                FROM db1.tdetailf_returnrinc AS A
                LEFT JOIN db1.transaksif_stokkatalog AS B ON B.id_depo = :idDepo
                WHERE
                    A.kode_reff = :kode
                    AND A.id_katalog = :idKatalog
                    AND A.no_urut = :noUrut
                    AND A.id_katalog = B.id_katalog
            ";
            $params = [
                ":idDepo"           => $drRetur->idDepo,
                ":noDokumen"        => $drRetur->noDokumen,
                ":ppn"              => $drRetur->ppn,
                ":kodeStokopname"   => $drRetur->kodeSo,
                ":tanggalAdm"       => $drRetur->tanggalAdm,
                ":tanggalTransaksi" => $toSystemDate($verTanggalGudang),
                ":bulanTransaksi"   => date("m", strtotime($verTanggalGudang)),
                ":tahunTransaksi"   => date("Y", strtotime($verTanggalGudang)),
                ":kodeStore"        => $drRetur->kodeStore,
                ":tanggalTersedia"  => $verTanggalTerima,
                ":idPabrik"         => $drRetur->idPabrik,
                ":idKemasan"        => $drRetur->idKemasan,
                ":isiKemasan"       => $drRetur->isiKemasan,
                ":hargaNetoApotik"  => $drRetur->hnaItem,
                ":hargaPerolehan"   => $drRetur->hpItem,
                ":phjaItem"         => $drRetur->phjaItem,
                ":hargaJualApotik"  => $drRetur->hjaItem,
                ":hargaItem"        => $drRetur->hargaItem,
                ":hargaKemasan"     => $drRetur->hargaKemasan,
                ":diskonItem"       => $drRetur->diskonItem,
                ":keterangan"       => "Retur penerimaan pembelian dari pemasok {$drRetur->namaPemasok}, pada tanggal $tanggalTerima",
                ":idUserUbah"       => $verUserTerima,
                ":kode"             => $kode,
                ":idKatalog"        => $drRetur->idKatalog,
                ":noUrut"           => $drRetur->noUrut,
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
                    id_kemasan = :idKemasanDepo,
                    jumlah_stokfisik = :jumlahItem,
                    jumlah_stokadm = :jumlahItem,
                    status = 1,
                    userid_in = :userVerTerima,
                    sysdate_in = :tanggalVerTerima,
                    userid_updt = :userVerTerima,
                    sysdate_updt = :tanggalVerTerima,
                    keterangan = :keterangan 
                ON DUPLICATE KEY UPDATE
                    jumlah_stokfisik = jumlah_stokfisik - :jumlahItem,
                    jumlah_stokadm = jumlah_stokadm - :jumlahItem,
                    userid_updt = :userVerTerima,
                    sysdate_updt = :tanggalVerTerima,
                    keterangan = :keterangan
            ";
            $params = [
                ":idDepo"           => $drRetur->idDepo,
                ":idKatalog"        => $drRetur->idKatalog,
                ":idKemasanDepo"    => $drRetur->idKemasanDepo,
                ":jumlahItem"       => $drRetur->jumlahItem,
                ":userVerTerima"    => $verUserTerima,
                ":tanggalVerTerima" => $verTanggalTerima,
                ":keterangan"       => "Retur penerimaan pembelian dari pemasok {$drRetur->namaPemasok}, pada tanggal $tanggalTerima",
                ":nama_pbf"         => $drRetur->namaPemasok, // TODO: php: uncategorized: confirm missing/deleted
            ];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilTambah) throw new FailToInsertException("Stok Katalog", $transaction);
        }
        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#doVerterimagas the original method
     */
    public function actionSaveVerTerimaGasMedis(): void
    {
        // defined in "VerTerima"
        ["kode" => $kode, "verTerima" => $verTerima] = Yii::$app->request->post();
        if ($verTerima != 1) throw new \Exception("Verifikasi penerimaan gagal. Anda belum melakukan checklist verifikasi.");

        $data = [
            "kode" => $kode,
            "ver_terima" => 1,
            "ver_tglterima" => Yii::$app->dateTime->nowVal("system"),
            "ver_usrterima" => Yii::$app->userFatma->id,
        ];

        $daftarField = array_keys($data);
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();

        // update status ver gudang n update stock => transaksif_return
        $where = ["kode" => $kode];
        $berhasilUbah = (new FarmasiModel)->saveData("transaksif_return", $daftarField, $data, $where);
        if (!$berhasilUbah) throw new FailToUpdateException("Retur", "Kode", $kode, $transaction);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog AS idKatalog, -- in use
                A.no_batch   AS noBatch,   -- in use
                A.kode_reff  AS kodeRef    -- in use
            FROM db1.tdetailf_returnrinc AS A
            LEFT JOIN db1.transaksif_return AS C ON A.kode_reff = C.kode
            WHERE A.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kode];
        $daftarDetailRincianRetur = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarDetailRincianRetur) throw new DataNotExistException("Rincian Retur", "Kode Ref", $kode, $transaction); // redirect: [self::class, "actionVerTerima"]."/".$kode

        foreach ($daftarDetailRincianRetur as $drRetur) {
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
                    'B',                    -- 6
                    0,                      -- 7
                    C.id_gudangpenyimpanan, -- 8
                    1,                      -- 9
                    C.id_pbf,               -- 10
                    A.tgl_expired,          -- 11
                    0,                      -- 12
                    1,                      -- 13
                    0,                      -- 14
                    0,                      -- 15
                    C.keterangan,           -- 16
                    1,                      -- 17
                    C.ver_usrterima,        -- 18
                    C.ver_tglterima,        -- 19
                    C.ver_usrterima,        -- 20
                    C.ver_tglterima,        -- 21
                    '',                     -- not exist in original source (error supressor)
                    ''                      -- idem
                FROM db1.tdetailf_returnrinc AS A
                LEFT JOIN db1.tdetailf_return AS B ON A.kode_reff = B.kode_reff
                LEFT JOIN db1.transaksif_return AS C ON A.kode_reff = C.kode
                JOIN (
                    SELECT IFNULL(MAX(id), 0)+1 AS id
                    FROM db1.transaksif_kartugasmedis
                ) AS K
                WHERE
                    A.id_katalog = :idKatalog
                    AND A.no_batch = :noBatch
                    AND A.kode_reff = :kodeRef
                    AND A.id_katalog = B.id_katalog
            ";
            $params = [":idKatalog" => $drRetur->idKatalog, ":noBatch" => $drRetur->noBatch, ":kodeRef" => $drRetur->kodeRef];
            $berhasilTambah = $connection->createCommand($sql, $params)->execute();
            if (!$berhasilTambah) throw new FailToInsertException("Kartu Gas Medis", $transaction);
        }

        $transaction->commit();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#getUpdateTrn the original method
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
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#views    the original method
     */
    public function actionDataViewBarang(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kodeTransaksi, -- ViewTabung, View, ViewRinci
                A.no_doc               AS noDokumenRetur, -- ViewTabung, View, ViewRinci
                A.tgl_doc              AS tanggalDokumenRetur, -- ViewTabung, View, ViewRinci
                A.tipe_doc             AS tipeDokumen, -- view
                A.blnawal_anggaran     AS bulanAwalAnggaran, -- View, ViewRinci
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,  -- View, ViewRinci
                A.thn_anggaran         AS tahunAnggaran, -- View, ViewRinci
                A.ppn                  AS ppn, -- View, ViewRinci
                B.subjenis_anggaran    AS subjenisAnggaran, -- View, ViewRinci
                C.sumber_dana          AS sumberDana, -- View, ViewRinci
                D.subsumber_dana       AS subsumberDana, -- View, ViewRinci
                E.jenis_harga          AS jenisHarga, -- View, ViewRinci
                F.cara_bayar           AS caraBayar, -- View, ViewRinci
                IFNULL(G.no_doc, '-')  AS noSpk, -- View, ViewRinci
                IFNULL(J.no_doc, '-')  AS noPo, -- View, ViewRinci
                IFNULL(H.nama_pbf, '') AS namaPemasok, -- ViewTabung, View, ViewRinci
                I.no_doc               AS noRencana, -- View, ViewRinci
                AA.no_doc              AS noPenerimaan, -- View, ViewRinci
                AA.no_faktur           AS noFaktur, -- View, ViewRinci
                AA.no_suratjalan       AS noSuratJalan, -- View, ViewRinci
                K.namaDepo             AS gudang, -- ViewTabung, View, ViewRinci
                A.nilai_pembulatan     AS nilaiPembulatan, -- View, ViewRinci
                A.ver_tglterima        AS verTanggalTerima, -- ViewTabung, View, ViewRinci
                A.ver_tglgudang        AS verTanggalGudang, -- ViewTabung, View, ViewRinci
                A.ver_tglakuntansi     AS verTanggalAkuntansi, -- ViewTabung, View, ViewRinci
                A.ver_terima           AS verifikasiTerima, -- ViewTabung, View, ViewRinci
                A.ver_gudang           AS verifikasiGudang, -- ViewTabung, View, ViewRinci
                A.ver_akuntansi        AS verifikasiAkuntansi, -- ViewTabung, View, ViewRinci
                IFNULL(UTRM.name, '-') AS namaUserTerima, -- ViewTabung, View, ViewRinci
                IFNULL(UGDG.name, '-') AS namaUserGudang, -- ViewTabung, View, ViewRinci
                IFNULL(UAKN.name, '-') AS namaUserAkuntansi, -- ViewTabung, View, ViewRinci
                A.keterangan           AS keterangan, -- ViewTabung, View, ViewRinci
                ''                     AS detailRetur -- ViewTabung, View, ViewRinci
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_penerimaan AS AA ON A.kode_refftrm = AA.kode
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
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog               AS idKatalog, -- ViewTabung, View, ViewRinci
                A.kemasan                  AS kemasan, -- ViewTabung, View, ViewRinci
                A.isi_kemasan              AS isiKemasan, -- ViewTabung, View, ViewRinci
                A.jumlah_item              AS jumlahItem, -- View, ViewRinci
                A.jumlah_kemasan           AS jumlahKemasan, -- ViewTabung, View, ViewRinci
                A.harga_item               AS hargaItem, -- View, ViewRinci
                A.harga_kemasan            AS hargaKemasan, -- View, ViewRinci
                A.diskon_item              AS diskonItem, -- View, ViewRinci
                KAT.nama_sediaan           AS namaSediaan, -- ViewTabung, View, ViewRinci
                PBK.nama_pabrik            AS namaPabrik, -- ViewTabung, View, ViewRinci
                KCIL.kode                  AS satuan, -- satuan kecil
                IFNULL(ret.jumlah_item, 0) AS jumlahRetur, -- View, ViewRinci
                IFNULL(trm.jumlah_item, 0) AS jumlahTerima -- View, ViewRinci
            FROM db1.tdetailf_return AS A
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS trm ON A.kode_refftrm = trm.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS ret ON A.kode_refftrm = ret.kode_refftrm
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = trm.id_katalog
                AND A.id_katalog = ret.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $retur->detailRetur = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($retur);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#views    the original method
     */
    public function actionDataViewGasMedis(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kodeTransaksi, -- ViewTabung, View, ViewRinci
                A.no_doc               AS noDokumenRetur, -- ViewTabung, View, ViewRinci
                A.tgl_doc              AS tanggalDokumenRetur, -- ViewTabung, View, ViewRinci
                A.tipe_doc             AS tipeDokumen, -- view
                A.blnawal_anggaran     AS bulanAwalAnggaran, -- View, ViewRinci
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,  -- View, ViewRinci
                A.thn_anggaran         AS tahunAnggaran, -- View, ViewRinci
                A.ppn                  AS ppn, -- View, ViewRinci
                B.subjenis_anggaran    AS subjenisAnggaran, -- View, ViewRinci
                C.sumber_dana          AS sumberDana, -- View, ViewRinci
                D.subsumber_dana       AS subsumberDana, -- View, ViewRinci
                E.jenis_harga          AS jenisHarga, -- View, ViewRinci
                F.cara_bayar           AS caraBayar, -- View, ViewRinci
                IFNULL(G.no_doc, '-')  AS noSpk, -- View, ViewRinci
                IFNULL(J.no_doc, '-')  AS noPo, -- View, ViewRinci
                IFNULL(H.nama_pbf, '') AS namaPemasok, -- ViewTabung, View, ViewRinci
                I.no_doc               AS noRencana, -- View, ViewRinci
                AA.no_doc              AS noPenerimaan, -- View, ViewRinci
                AA.no_faktur           AS noFaktur, -- View, ViewRinci
                AA.no_suratjalan       AS noSuratJalan, -- View, ViewRinci
                K.namaDepo             AS gudang, -- ViewTabung, View, ViewRinci
                A.nilai_pembulatan     AS nilaiPembulatan, -- View, ViewRinci
                A.ver_tglterima        AS verTanggalTerima, -- ViewTabung, View, ViewRinci
                A.ver_tglgudang        AS verTanggalGudang, -- ViewTabung, View, ViewRinci
                A.ver_tglakuntansi     AS verTanggalAkuntansi, -- ViewTabung, View, ViewRinci
                A.ver_terima           AS verifikasiTerima, -- ViewTabung, View, ViewRinci
                A.ver_gudang           AS verifikasiGudang, -- ViewTabung, View, ViewRinci
                A.ver_akuntansi        AS verifikasiAkuntansi, -- ViewTabung, View, ViewRinci
                IFNULL(UTRM.name, '-') AS namaUserTerima, -- ViewTabung, View, ViewRinci
                IFNULL(UGDG.name, '-') AS namaUserGudang, -- ViewTabung, View, ViewRinci
                IFNULL(UAKN.name, '-') AS namaUserAkuntansi, -- ViewTabung, View, ViewRinci
                A.keterangan           AS keterangan, -- ViewTabung, View, ViewRinci
                ''                     AS detailRetur -- ViewTabung, View, ViewRinci
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_penerimaan AS AA ON A.kode_refftrm = AA.kode
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
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog               AS idKatalog, -- ViewTabung, View, ViewRinci
                A.kemasan                  AS kemasan, -- ViewTabung, View, ViewRinci
                A.isi_kemasan              AS isiKemasan, -- ViewTabung, View, ViewRinci
                A.jumlah_item              AS jumlahItem, -- View, ViewRinci
                A.jumlah_kemasan           AS jumlahKemasan, -- ViewTabung, View, ViewRinci
                A.harga_item               AS hargaItem, -- View, ViewRinci
                A.harga_kemasan            AS hargaKemasan, -- View, ViewRinci
                A.diskon_item              AS diskonItem, -- View, ViewRinci
                KAT.nama_sediaan           AS namaSediaan, -- ViewTabung, View, ViewRinci
                PBK.nama_pabrik            AS namaPabrik, -- ViewTabung, View, ViewRinci
                KCIL.kode                  AS satuan, -- satuan kecil
                IFNULL(ret.jumlah_item, 0) AS jumlahRetur, -- View, ViewRinci
                IFNULL(trm.jumlah_item, 0) AS jumlahTerima -- View, ViewRinci
            FROM db1.tdetailf_return AS A
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS trm ON A.kode_refftrm = trm.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS ret ON A.kode_refftrm = ret.kode_refftrm
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = trm.id_katalog
                AND A.id_katalog = ret.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $retur->detailRetur = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($retur);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#views    the original method
     */
    public function actionDataViewRincian(): string
    {
        ["kode" => $kode] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                 AS kodeTransaksi, -- ViewTabung, View, ViewRinci
                A.no_doc               AS noDokumenRetur, -- ViewTabung, View, ViewRinci
                A.tgl_doc              AS tanggalDokumenRetur, -- ViewTabung, View, ViewRinci
                A.tipe_doc             AS tipeDokumen, -- view
                A.blnawal_anggaran     AS bulanAwalAnggaran, -- View, ViewRinci
                A.blnakhir_anggaran    AS bulanAkhirAnggaran,  -- View, ViewRinci
                A.thn_anggaran         AS tahunAnggaran, -- View, ViewRinci
                A.ppn                  AS ppn, -- View, ViewRinci
                B.subjenis_anggaran    AS subjenisAnggaran, -- View, ViewRinci
                C.sumber_dana          AS sumberDana, -- View, ViewRinci
                D.subsumber_dana       AS subsumberDana, -- View, ViewRinci
                E.jenis_harga          AS jenisHarga, -- View, ViewRinci
                F.cara_bayar           AS caraBayar, -- View, ViewRinci
                IFNULL(G.no_doc, '-')  AS noSpk, -- View, ViewRinci
                IFNULL(J.no_doc, '-')  AS noPo, -- View, ViewRinci
                IFNULL(H.nama_pbf, '') AS namaPemasok, -- ViewTabung, View, ViewRinci
                I.no_doc               AS noRencana, -- View, ViewRinci
                AA.no_doc              AS noPenerimaan, -- View, ViewRinci
                AA.no_faktur           AS noFaktur, -- View, ViewRinci
                AA.no_suratjalan       AS noSuratJalan, -- View, ViewRinci
                K.namaDepo             AS gudang, -- ViewTabung, View, ViewRinci
                A.nilai_pembulatan     AS nilaiPembulatan, -- View, ViewRinci
                A.ver_tglterima        AS verTanggalTerima, -- ViewTabung, View, ViewRinci
                A.ver_tglgudang        AS verTanggalGudang, -- ViewTabung, View, ViewRinci
                A.ver_tglakuntansi     AS verTanggalAkuntansi, -- ViewTabung, View, ViewRinci
                A.ver_terima           AS verifikasiTerima, -- ViewTabung, View, ViewRinci
                A.ver_gudang           AS verifikasiGudang, -- ViewTabung, View, ViewRinci
                A.ver_akuntansi        AS verifikasiAkuntansi, -- ViewTabung, View, ViewRinci
                IFNULL(UTRM.name, '-') AS namaUserTerima, -- ViewTabung, View, ViewRinci
                IFNULL(UGDG.name, '-') AS namaUserGudang, -- ViewTabung, View, ViewRinci
                IFNULL(UAKN.name, '-') AS namaUserAkuntansi, -- ViewTabung, View, ViewRinci
                A.keterangan           AS keterangan, -- ViewTabung, View, ViewRinci
                ''                     AS detailRetur -- ViewTabung, View, ViewRinci
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_penerimaan AS AA ON A.kode_refftrm = AA.kode
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
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog               AS idKatalog, -- ViewTabung, View, ViewRinci
                A.kemasan                  AS kemasan, -- ViewTabung, View, ViewRinci
                A.isi_kemasan              AS isiKemasan, -- ViewTabung, View, ViewRinci
                A.jumlah_item              AS jumlahItem, -- View, ViewRinci
                A.jumlah_kemasan           AS jumlahKemasan, -- View, ViewRinci
                A.harga_item               AS hargaItem, -- View, ViewRinci
                A.harga_kemasan            AS hargaKemasan, -- View, ViewRinci
                A.diskon_item              AS diskonItem, -- View, ViewRinci
                KAT.nama_sediaan           AS namaSediaan, -- ViewTabung, View, ViewRinci
                PBK.nama_pabrik            AS namaPabrik, -- ViewTabung, View, ViewRinci
                KCIL.kode                  AS satuan, -- satuan kecil
                IFNULL(trm.jumlah_item, 0) AS jumlahTerima, -- View, ViewRinci
                B.no_batch                 AS noBatch, -- ViewTabung, ViewRinci
                B.tgl_expired              AS tanggalKadaluarsa, -- ViewRinci
                B.no_urut                  AS noUrut, -- ViewTabung, ViewRinci
                B.jumlah_item              AS jumlahItem, -- View, ViewRinci
                B.jumlah_kemasan           AS jumlahKemasan, -- ViewTabung, View, ViewRinci
                IFNULL(ret.jumlah_item, 0) AS jumlahRetur -- View, ViewRinci
            FROM db1.tdetailf_return AS A
            LEFT JOIN db1.tdetailf_returnrinc AS B ON A.kode_reff = B.kode_reff
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS trm ON A.kode_refftrm = trm.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS ret ON A.kode_refftrm = ret.kode_refftrm
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = B.id_katalog
                AND A.id_katalog = trm.id_katalog
                AND A.id_katalog = ret.id_katalog
            ORDER BY nama_sediaan, B.no_urut
        ";
        $params = [":kode" => $kode];
        $retur->detailRetur = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($retur);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#prints    the original method
     */
    public function actionPrint(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                     AS kode,
                A.no_doc                                   AS noDokumen,
                A.tgl_doc                                  AS tanggalDokumen,
                A.tipe_doc                                 AS tipeDokumen,
                A.sts_linked                               AS statusLinked,
                A.sts_deleted                              AS statusDeleted,
                A.sysdate_del                              AS sysdateDeleted,
                A.userid_updt                              AS useridUpdate,
                A.sysdate_updt                             AS sysdateUpdate,
                A.blnawal_anggaran                         AS bulanAwalAnggaran,
                A.blnakhir_anggaran                        AS bulanAkhirAnggaran,
                A.thn_anggaran                             AS tahunAnggaran,
                A.ppn                                      AS ppn,
                B.subjenis_anggaran                        AS subjenisAnggaran,
                C.sumber_dana                              AS sumberDana,
                D.subsumber_dana                           AS subsumberDana,
                E.jenis_harga                              AS jenisHarga,
                F.cara_bayar                               AS caraBayar,
                IFNULL(G.no_doc, '-')                      AS noSpk,
                IFNULL(J.tgl_doc, '0000-00-00')            AS tanggalMulai,
                IFNULL(J.no_doc, '-')                      AS noPo,
                IFNULL(J.tgl_tempokirim, G.tgl_jatuhtempo) AS tanggalTempoKirim,
                IFNULL(H.nama_pbf, '')                     AS namaPemasok,
                I.no_doc                                   AS noRencana,
                AA.no_doc                                  AS noTerima,
                AA.no_faktur                               AS noFaktur,
                AA.no_suratjalan                           AS noSuratJalan,
                K.namaDepo                                 AS gudang,
                A.nilai_total                              AS nilaiTotal,
                A.nilai_diskon                             AS nilaiDiskon,
                A.nilai_ppn                                AS nilaiPpn,
                A.nilai_pembulatan                         AS nilaiPembulatan,
                A.nilai_akhir                              AS nilaiAkhir,
                A.ver_tglterima                            AS verTanggalTerima,
                A.ver_tglgudang                            AS verTanggalGudang,
                A.ver_tglakuntansi                         AS verTanggalAkuntansi,
                A.ver_terima                               AS verTerima,
                A.ver_gudang                               AS verGudang,
                A.ver_akuntansi                            AS verAkuntansi,
                IFNULL(UTRM.name, '-')                     AS namaUserTerima,
                IFNULL(UGDG.name, '-')                     AS namaUserGudang,
                IFNULL(UAKN.name, '-')                     AS namaUserAkuntansi,
                L.no_doc                                   AS noTerima,
                A.keterangan                               AS keterangan
            FROM db1.transaksif_return AS A
            LEFT JOIN db1.transaksif_penerimaan AS AA ON A.kode_refftrm = AA.kode
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
        $retur = $connection->createCommand($sql, $params)->queryOne();
        if (!$retur) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff                AS kodeRef,
                A.id_katalog               AS idKatalog,
                A.kode_reffrenc            AS kodeRefRencana,
                A.kode_reffro              AS kodeRefRo,
                A.kode_reffpo              AS kodeRefPo,
                A.kode_reffpl              AS kodeRefPl,
                A.kode_refftrm             AS kodeRefTerima,
                A.kode_reffkons            AS kodeRefKons,
                A.id_reffkatalog           AS idRefKatalog,
                A.kemasan                  AS kemasan,
                A.id_pabrik                AS idPabrik,
                A.id_kemasan               AS idKemasan,
                A.isi_kemasan              AS isiKemasan,
                A.id_kemasandepo           AS idKemasanDepo,
                A.jumlah_item              AS jumlahItem,
                A.jumlah_kemasan           AS jumlahKemasan,
                A.harga_item               AS hargaItem,
                A.harga_kemasan            AS hargaKemasan,
                A.diskon_item              AS diskonItem,
                A.diskon_harga             AS diskonHarga,
                A.hna_item                 AS hnaItem,
                A.hp_item                  AS hpItem,
                A.phja_item                AS phjaItem,
                A.hja_item                 AS hjaItem,
                A.keterangan               AS keterangan,
                A.userid_updt              AS useridUpdate,
                A.sysdate_updt             AS sysdateUpdate,
                KAT.nama_sediaan           AS namaSediaan,
                KAT.jumlah_itembeli        AS jumlahItemBeli,
                KAT.jumlah_itembonus       AS jumlahItemBonus,
                PBK.nama_pabrik            AS namaPabrik,
                KSAR.kode                  AS satuanJual,
                KCIL.kode                  AS satuan,  -- satuan kecil
                IFNULL(ret.jumlah_item, 0) AS jumlahRetur,
                IFNULL(trm.jumlah_item, 0) AS jumlahTerima
            FROM db1.tdetailf_return AS A
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_penerimaan AS trm ON A.kode_refftrm = trm.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_refftrm     AS kode_refftrm,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_refftrm, A.id_katalog
            ) AS ret ON A.kode_refftrm = ret.kode_refftrm
            WHERE
                A.kode_reff = :kode
                AND A.id_katalog = trm.id_katalog
                AND A.id_katalog = ret.id_katalog
            ORDER BY nama_sediaan
        ";
        $params = [":kode" => $kode];
        $daftarDetailRetur = $connection->createCommand($sql, $params)->queryAll();

        $view = new PrintV1(retur: $retur, daftarDetailRetur: $daftarDetailRetur);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-c3aa2cd
     */
    public function actionSearchJsonDeleted(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        ["keterangan" => $keterangan, "kode" => $kode] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_return
            SET
                no_doc = kode,
                keterangan = CONCAT('Hapus Return dengan No: ', :keterangan),
                sts_deleted = 1,
                sysdate_del = :tanggal
            WHERE
                kode = :kode
                AND sts_deleted = 0
        ";
        $params = [":keterangan" => $keterangan, ":tanggal" => $nowValSystem, ":kode" => $kode];
        $berhasilHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($berhasilHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws LogicBranchException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#Reports    the original method
     * first exist of actionReports: commit-75b46456
     */
    public function actionReportBukuInduk(): string
    {
        [   "statusVerifikasi" => $status,
            "jenis" => $jenis,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "tahapVerifikasi" => $tahap,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                R.kode               AS kodeTerima,       -- in use
                R.no_doc             AS noDokumen,        -- in use
                IFNULL(T.no_doc, '') AS noTerima,         -- in use
                R.tgl_doc            AS tanggalDokumen,
                R.ver_terima         AS verTerima,
                R.ver_tglterima      AS verTanggalTerima, -- in use
                R.ver_gudang         AS verGudang,
                R.ver_tglgudang      AS verTanggalGudang, -- in use
                R.ppn                AS ppn,              -- in use
                R.nilai_total        AS nilaiTotal,
                R.nilai_diskon       AS nilaiDiskon,
                R.nilai_ppn          AS nilaiPpn,
                R.nilai_pembulatan   AS nilaiPembulatan,
                R.nilai_akhir        AS nilaiAkhir,
                C.nama_pbf           AS namaPemasok,      -- in use
                A.id_katalog         AS idKatalog,
                D.nama_sediaan       AS namaSediaan,      -- in use
                E.nama_pabrik        AS namaPabrik,       -- in use
                A.jumlah_item        AS jumlahItem,       -- in use
                A.jumlah_kemasan     AS jumlahKemasan,
                A.harga_kemasan      AS hargaKemasan,
                F.kode               AS satuan,
                A.harga_item         AS hargaItem,        -- in use
                A.diskon_item        AS diskonItem        -- in use
            FROM db1.tdetailf_return AS A
            INNER JOIN db1.transaksif_return AS R ON A.kode_reff = R.kode
            LEFT JOIN db1.transaksif_penerimaan AS T ON R.kode_refftrm = T.kode
            LEFT JOIN db1.masterf_pbf AS C ON R.id_pbf = C.id
            LEFT JOIN db1.masterf_katalog AS D ON A.id_katalog = D.kode
            LEFT JOIN db1.masterf_pabrik AS E ON D.id_pabrik = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON D.id_kemasankecil = F.id
            WHERE
                R.sts_deleted = 0
                AND (
                    (:jenis    = 'dokumenPenerimaan'    AND T.tgl_doc          >= :tanggalAwal AND T.tgl_doc          <= :tanggalAkhir)
                    OR (:jenis = 'dokumenRetur'         AND R.tgl_doc          >= :tanggalAwal AND R.tgl_doc          <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiGudang'     AND R.ver_tglgudang    >= :tanggalAwal AND R.ver_tglgudang    <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiPenerimaan' AND R.ver_tglterima    >= :tanggalAwal AND R.ver_tglterima    <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiAkuntansi'  AND R.ver_tglakuntansi >= :tanggalAwal AND R.ver_tglakuntansi <= :tanggalAkhir)
                )
                AND (
                    :status = ''
                    OR (:tahap = 'gudang'     AND R.ver_gudang    = :status)
                    OR (:tahap = 'penerimaan' AND R.ver_terima    = :status)
                    OR (:tahap = 'akuntansi'  AND R.ver_akuntansi = :status)
                )
            ORDER BY T.tgl_doc ASC, R.no_doc ASC
        ";
        $sql = ($jenis == "dokumenPenerimaan")
            ? str_replace("LEFT JOIN db1.transaksif_penerimaan", "INNER JOIN db1.transaksif_penerimaan", $sql)
            : $sql;

        switch ($jenis) {
            case "dokumenPenerimaan":    $sql = str_replace("ORDER BY T.tgl_doc", "ORDER BY T.tgl_doc",          $sql); break;
            case "dokumenRetur":         $sql = str_replace("ORDER BY T.tgl_doc", "ORDER BY R.tgl_doc",          $sql); break;
            case "verifikasiGudang":     $sql = str_replace("ORDER BY T.tgl_doc", "ORDER BY R.ver_tglgudang",    $sql); break;
            case "verifikasiPenerimaan": $sql = str_replace("ORDER BY T.tgl_doc", "ORDER BY R.ver_tglterima",    $sql); break;
            case "verifikasiAkuntansi":  $sql = str_replace("ORDER BY T.tgl_doc", "ORDER BY R.ver_tglakuntansi", $sql); break;
            default: throw new LogicBranchException;
        }

        $params = [
            ":jenis" => $jenis,
            ":tanggalAwal" => $toSystemDate($tanggalAwal),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
            ":tahap" => $tahap,
            ":status" => $status,
        ];
        $daftarDetailRetur = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalJumlahPerKode = 0;
        $grandTotalPpn = 0;
        $grandTotalNilai = 0;

        $jumlahData = count($daftarDetailRetur);
        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $barisPerHalaman = 44;
        $maksHurufBarang = 36;
        $maksHurufPabrik = 16;
        $kodeTerimaSaatIni = "";

        foreach ($daftarDetailRetur as $i => $dRetur) {
            $kodeTerima = $dRetur->kodeTerima;

            $jumlahBarisBarang = ceil(strlen($dRetur->namaSediaan) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($dRetur->namaPabrik) / $maksHurufPabrik);
            $butuhBaris = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

            if ($kodeTerimaSaatIni != $kodeTerima) {
                $kodeTerimaSaatIni = $kodeTerima;
                $hJudul = $h;
                $bJudul = $b;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "kode_terima" => $kodeTerima,
                    "no_doc" => $dRetur->noDokumen,
                    "no_trm" => $dRetur->noTerima,
                    "ver_tglterima" => $dRetur->verTanggalTerima,
                    "ver_tglgudang" => $dRetur->verTanggalGudang,
                    "nama_pbf" => $dRetur->namaPemasok,
                    "total_jumlah" => 0,
                    "total_ppn" => 0,
                    "total_nilai" => 0,
                ];

                if (($b + $butuhBaris) > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $sebelumDiskon = $dRetur->hargaItem * $dRetur->jumlahItem;
            $diskon = $sebelumDiskon * ($dRetur->diskonItem / 100);
            $jumlah = $sebelumDiskon - $diskon;
            $ppn = $jumlah * ($dRetur->ppn / 100);

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "subtotal_jumlah" => $jumlah,
                "subtotal_ppn" => $ppn,
                "subtotal_nilai" => $jumlah + $ppn,
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_jumlah"] += $jumlah;
            $daftarHalaman[$hJudul][$bJudul]["total_ppn"] += $ppn;
            $daftarHalaman[$hJudul][$bJudul]["total_nilai"] += $jumlah + $ppn;

            $grandTotalJumlahPerKode += $jumlah;
            $grandTotalPpn += $ppn;
            $grandTotalNilai += $jumlah + $ppn;

            if ($i + 1 == $jumlahData) break;
            $dataBerikutnya = $daftarDetailRetur[$i + 1];
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

        if (!$daftarHalaman) return "tidak ada data";

        $view = new BukuInduk(
            tanggalAwal:             $tanggalAwal,
            tanggalAkhir:            $tanggalAkhir,
            daftarHalaman:           $daftarHalaman,
            daftarDetailRetur:       $daftarDetailRetur,
            grandTotalJumlahPerKode: $grandTotalJumlahPerKode,
            grandTotalPpn:           $grandTotalPpn,
            grandTotalNilai:         $grandTotalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/returnfarmasi.php#Reports    the original method
     * first exist of actionReports: commit-75b46456
     */
    public function actionReportRekapPemasok(): string
    {
        [   "statusVerifikasi" => $status,
            "jenis" => $jenis,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "tahapVerifikasi" => $tahap,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                R.id_jenisanggaran                                                     AS idJenisAnggaran,
                J.subjenis_anggaran                                                    AS subjenisAnggaran,
                J.kode                                                                 AS kodePenerimaan,   -- in use
                R.id_pbf                                                               AS idPemasok,
                P.nama_pbf                                                             AS namaPemasok,
                T.no_doc                                                               AS noBa,
                R.no_doc                                                               AS noDokumen,
                T.no_faktur                                                            AS noFaktur,
                T.no_suratjalan                                                        AS noSuratJalan,
                IFNULL(dR.harga_total, 0)                                              AS hargaTotal,       -- in use
                IFNULL(dR.harga_diskon, 0)                                             AS hargaDiskon,      -- in use
                (IFNULL(dR.harga_total, 0) - IFNULL(dR.harga_diskon, 0)) * R.ppn / 100 AS hargaPpn,         -- in use
                R.nilai_total                                                          AS nilaiTotal,
                R.nilai_diskon                                                         AS nilaiDiskon,
                R.nilai_ppn                                                            AS nilaiPpn,
                R.nilai_pembulatan                                                     AS nilaiPembulatan,
                R.nilai_akhir                                                          AS nilaiAkhir
            FROM db1.transaksif_return AS R
            LEFT JOIN (
                SELECT
                    A.kode_reff                                             AS kode_reff,
                    SUM(A.jumlah_item * A.harga_item)                       AS harga_total,
                    SUM(A.jumlah_item * A.harga_item * A.diskon_item / 100) AS harga_diskon
                FROM db1.tdetailf_return AS A
                INNER JOIN db1.transaksif_return AS R ON A.kode_reff = R.kode
                LEFT JOIN db1.transaksif_penerimaan AS T ON R.kode_refftrm = T.kode
                WHERE
                    R.sts_deleted = 0
                    AND (
                        (:jenis    = 'dokumenPenerimaan'    AND T.tgl_doc          >= :tanggalAwal AND T.tgl_doc          <= :tanggalAkhir)
                        OR (:jenis = 'dokumenRetur'         AND R.tgl_doc          >= :tanggalAwal AND R.tgl_doc          <= :tanggalAkhir)
                        OR (:jenis = 'verifikasiGudang'     AND R.ver_tglgudang    >= :tanggalAwal AND R.ver_tglgudang    <= :tanggalAkhir)
                        OR (:jenis = 'verifikasiPenerimaan' AND R.ver_tglterima    >= :tanggalAwal AND R.ver_tglterima    <= :tanggalAkhir)
                        OR (:jenis = 'verifikasiAkuntansi'  AND R.ver_tglakuntansi >= :tanggalAwal AND R.ver_tglakuntansi <= :tanggalAkhir)
                    )
                    AND (
                        :status = ''
                        OR (:tahap = 'gudang'     AND R.ver_gudang    = :status)
                        OR (:tahap = 'penerimaan' AND R.ver_terima    = :status)
                        OR (:tahap = 'akuntansi'  AND R.ver_akuntansi = :status)
                    )
                GROUP BY A.kode_reff
            ) AS dR ON R.kode = dR.kode_reff
            LEFT JOIN db1.transaksif_penerimaan AS T ON R.kode_refftrm = T.kode
            LEFT JOIN db1.masterf_subjenisanggaran AS J ON R.id_jenisanggaran = J.id
            LEFT JOIN db1.masterf_pbf AS P ON R.id_pbf = P.id
            WHERE
                R.sts_deleted = 0
                AND J.id != 0
                AND (
                    (:jenis    = 'dokumenPenerimaan'    AND T.tgl_doc          >= :tanggalAwal AND T.tgl_doc          <= :tanggalAkhir)
                    OR (:jenis = 'dokumenRetur'         AND R.tgl_doc          >= :tanggalAwal AND R.tgl_doc          <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiGudang'     AND R.ver_tglgudang    >= :tanggalAwal AND R.ver_tglgudang    <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiPenerimaan' AND R.ver_tglterima    >= :tanggalAwal AND R.ver_tglterima    <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiAkuntansi'  AND R.ver_tglakuntansi >= :tanggalAwal AND R.ver_tglakuntansi <= :tanggalAkhir)
                )
                AND (
                    :status = ''
                    OR (:tahap = 'gudang'     AND R.ver_gudang    = :status)
                    OR (:tahap = 'penerimaan' AND R.ver_terima    = :status)
                    OR (:tahap = 'akuntansi'  AND R.ver_akuntansi = :status)
                )
            ORDER BY J.subjenis_anggaran ASC, P.nama_pbf ASC, T.no_doc ASC
        ";
        $sql = ($jenis == "dokumenPenerimaan")
            ? str_replace("LEFT JOIN db1.transaksif_penerimaan", "INNER JOIN db1.transaksif_penerimaan", $sql)
            : $sql;

        $params = [
            ":jenis" => $jenis,
            ":tanggalAwal" => $toSystemDate($tanggalAwal),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
            ":tahap" => $tahap,
            ":status" => $status,
        ];
        $daftarRetur = $connection->createCommand($sql, $params)->queryAll();

        $kategori = fn(string $param): string => match ($param) {
            "OB"  => "Obat",
            "ALK" => "Alat Kesehatan Habis Pakai",
            "OG"  => "Obat Gigi",
            "PB"  => "Pembalut",
            "REG" => "Reagensia",
            "RO"  => "Rontgen",
            "GAS" => "Gas Medis",
            "HD"  => "Hemodialisa",
            "COD" => "COD",
            "KON" => "Konsinyasi Implant",
            "CL"  => "Konsinyasi Kardiologi Invasiv (Chatlab)",
            "MK"  => "Konsinyasi Implant Mata",
            "D"   => "Donasi",
            default => "Lain-lain",
        };

        $daftarHalaman = [];
        $grandTotalNilaiAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 55;
        $kodePenerimaanSaatIni = "";

        foreach ($daftarRetur as $i => $retur) {
            if ($kodePenerimaanSaatIni != $retur->kodePenerimaan) {
                $kodePenerimaanSaatIni = $retur->kodePenerimaan;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "jenis_penerimaan" => $kategori($retur->kodePenerimaan),
                    "total_nilai_akhir" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $nilaiAkhir = $retur->hargaTotal - $retur->hargaDiskon + $retur->hargaPpn;

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
                "nilai_akhir" => $nilaiAkhir
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_nilai_akhir"] += $nilaiAkhir;
            $grandTotalNilaiAkhir += $nilaiAkhir;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new RekapPemasok(
            tanggalAwal:          $tanggalAwal,
            tanggalAkhir:         $tanggalAkhir,
            daftarHalaman:        $daftarHalaman,
            daftarRetur:          $daftarRetur,
            grandTotalNilaiAkhir: $grandTotalNilaiAkhir,
        );
        return $view->__toString();
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
                FROM db1.transaksif_return
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
                FROM db1.transaksif_return
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
