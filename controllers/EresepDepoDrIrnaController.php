<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\EresepDepoDrIrna\CetakStrukNew;
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
class EresepDepoDrIrnaController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#cetak    the original method
     */
    public function actionCetak(): string
    {
        [   "noResep" => $noResep,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noPendaftaran" => $noPendaftaran,
            "namaPasien" => $namaPasien,
            "jasaPelayanan" => $pJasaPelayanan,
            "kelamin" => $kelamin,
            "alamat" => $alamat,
            "noTelefon" => $noTelefon,
            "tanggalLahir" => $tanggalLahir,
            "kodeJenisResep" => $kodeJenisResep,
            "tanggalDaftar" => $tanggalDaftar,
            "tanggalAwalResep" => $tanggalAwalResep,
            "tanggalAkhirResep" => $tanggalAkhirResep,
            "pembayaran" => $pPembayaran,
            "namaInstalasi" => $namaInstalasi,
            "namaPoli" => $namaPoli,
            "namaRuangRawat" => $namaRuangRawat,
            "kodeInstalasi" => $kodeInstalasi,
            "kodePoli" => $kodePoli,
            "kodeBayar" => $kodeBayar,
            "kodeJenisCaraBayar" => $kodeJenisCaraBayar,
            "jenisCaraBayar" => $jenisCaraBayar,
            "kodeRuangRawat" => $kodeRuangRawat,
            "noUrut" => $pNoUrut,
            "grandTotal" => $grandTotal,
            "hargaJual" => $daftarHargaJual,
            "diskonObat" => $daftarDiskonObat,
            "kodeObat2" => $daftarKodeObat2,
            "kodeObat1" => $daftarKodeObat1,
            "kuantitas" => $daftarKuantitas,
            "namaSigna1" => $daftarNamaSigna1,
            "namaSigna2" => $daftarNamaSigna2,
            "namaSigna3" => $daftarNamaSigna3,
            "namaDokter" => $daftarNamaDokterPerObat,
            "idRacik" => $daftarIdRacik,

            // not exist in form
            "kodePenjualansebelumnya" => $kodePenjualanSebelumnya,
            "id" => $id,
            "verified" => $verified,
            "verifikasi" => $verifikasi,
            "edit_resep" => $editResep,
            "dokter" => $dokter,
            "kode_signa" => $daftarKodeSigna,
            "kode_obat_awal" => $daftarKodeObatAwal,
            "kode_pembungkus" => $daftarKodePembungkus,
            "qtypembungkus" => $daftarJumlahPembungkus,
            "hargapembungkus" => $daftarHargaPembungkus,
            "nm_racikan" => $daftarNamaRacikan,
            "diskonracik" => $daftarDiskonRacikan,
            "numero" => $daftarNoRacikan,
            "kode_signa_racik" => $daftarKodeSignaRacikan,

            // not exist in form
            // TODO: php: uncategorized: convert $_POST["xxx-$key"] to $_POST["xxx"][$key]
            // ["kode_pembungkus-$key"]
            // ["qtypembungkus-$key"]
            // ["hargapembungkus-$key"]
            // ["kode_obat-$key"]
            // ["qty-$key"]
            // ["hargasatuan-$key"]
            // ["ketjumlah-$key"]
        ] = Yii::$app->request->post();

        $idUser = Yii::$app->userFatma->id;
        $operator = Yii::$app->userFatma->nama;
        $idDepo = Yii::$app->userFatma->idDepo;
        $kodeSubunit = Yii::$app->userFatma->kodeSubUnitDepo;
        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

        if (!$verified) {
            $verif = 0;
            $myverif = "";
            if ($verifikasi) {
                $verif = 1;
                $myverif = ", verifikasi = 1";
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.masterf_penjualandetail
                    SET iter2 = iter2 + 1
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();
            }

            if (!$editResep) {
                $fresep = "R" . $kodeSubunit . date("Ymd");
                $urutan = $this->getCounter2($fresep);
                do {
                    $urutan = sprintf("%04d", ++$urutan);
                    $resep = $fresep . $urutan;
                    $cekResep = $this->addCounter2($resep);
                } while ($cekResep == "NO");

                $noResep = $resep;
            }

            $noUrut = $urutan ?? 0;
            $this->setDataView("depo", $idDepo);

            $jasaRacikObat = 0;
            $jasaRacik22 = 0;
            $jasaObat22 = 0;
            $hargaPembungkus = 0;
            $diskonObat = 0;
            $diskonRacik = 0;
            $jasaHargaObat22 = 0;
            $daftarTagihan = [];
            $daftarPelayanan = [];
            $daftarTagihanRacik = [];
            $daftarLayananRacik = [];

            if ($kodeRekamMedis) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.masterf_penjualan
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.masterf_penjualandetail
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $this->actionBridging4([
                    "fungsi" => "DELETE_RESEP",
                    "no_resep" => $noResep
                ]);

                $this->actionBridging4([
                    "fungsi" => "tambah_TINV_RESEP_OBAT",
                    "no_resep" => $noResep,
                    "no_pendaftaran" => $noPendaftaran,
                    "no_rm" => $kodeRekamMedis,
                    "nama_pembeli" => $namaPasien,
                    "kelamin" => ($kelamin == "P") ? 0 : 1,
                    "umur" => "24THN",
                    "alamat_pembeli" => $alamat,
                    "telp_pembeli" => $noTelefon,
                    "kode_subunit" => $kodeSubunit,
                    "userid" => "0852",
                    "no_gabungan" => null
                ]);

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT TRUE
                    FROM db1.pasien_small
                    WHERE no_rm = :kodeRekamMedis
                    LIMIT 1
                ";
                $params = [":kodeRekamMedis" => $kodeRekamMedis];
                $cekRekamMedis = $connection->createCommand($sql, $params)->queryScalar();

                if ($cekRekamMedis) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        DELETE FROM db1.pasien_small
                        WHERE no_rm = :kodeRekamMedis
                    ";
                    $params = [":kodeRekamMedis" => $kodeRekamMedis];
                    $connection->createCommand($sql, $params)->execute();
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.pasien_small
                    SET
                        no_rm = :kodeRekamMedis,
                        nama = :nama,
                        no_daftar = :noPendaftaran,
                        tanggal_lahir = :tanggalLahir,
                        jenis_kelamin = :kelamin,
                        alamat_jalan = :alamatJalan,
                        no_telpon = :noTelefon
                ";
                $params = [
                    ":kodeRekamMedis" => $kodeRekamMedis,
                    ":nama" => $namaPasien,
                    ":noPendaftaran" => $noPendaftaran,
                    ":tanggalLahir" => $toSystemDate($tanggalLahir),
                    ":kelamin" => $kelamin,
                    ":alamatJalan" => $alamat,
                    ":noTelefon" => $noTelefon,
                ];
                $connection->createCommand($sql, $params)->execute();

                /* note: generate random sale-code and make sure it does not exist */
                do {
                    $s = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 8)), 0, 8);
                    $kodePenjualan = "PJ" . rand(1000000, 9999999) . $s;
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.masterf_penjualan
                        WHERE kodePenjualan = :kodePenjualan
                        LIMIT 1
                    ";
                    $params = [":kodePenjualan" => $kodePenjualan];
                    $cekKodePenjualan = $connection->createCommand($sql, $params)->queryScalar();
                } while ($cekKodePenjualan);

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.masterf_penjualandetail
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        idPenjualandetail      AS idPenjualanDetail,
                        no_resep               AS noResep,
                        tglResep1              AS tanggalResep1,
                        tglResep2              AS tanggalResep2,
                        jenisResep             AS jenisResep,
                        dokter                 AS dokter,
                        pembayaran             AS pembayaran,
                        namaInstansi           AS namaInstansi,
                        namaPoli               AS namaPoli,
                        keterangan             AS keterangan,
                        totaldiskon            AS totalDiskon,
                        totalpembungkus        AS totalPembungkus,
                        jasapelayanan          AS jasaPelayanan,
                        total                  AS total,
                        bayar                  AS bayar,
                        kembali                AS kembali,
                        total_retur            AS totalRetur,
                        iter                   AS iter1,
                        iter2                  AS iter2,
                        nm_kamar               AS namaKamar,
                        KD_BAYAR               AS kodeBayar,
                        KD_INST                AS kodeInstalasi,
                        KD_POLI                AS kodePoli,
                        KD_RRAWAT              AS kodeRuangRawat,
                        KD_JENIS_CARABAYAR     AS kodeJenisCaraBayar,
                        JNS_CARABAYAR          AS jenisCaraBayar,
                        CARA_PEMBAYARAN        AS caraPembayaran,
                        CARA_PEMBAYARAN_DETAIL AS caraPembayaranDetail,
                        NOMOR_KARTU            AS noKartu,
                        TGL_DAFTAR             AS tanggalPendaftaran,
                        atasnama               AS atasNama
                    FROM db1.masterf_penjualandetail
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $daftarDetailPenjualan = $connection->createCommand($sql, $params)->queryAll();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jenisresep
                    FROM db1.masterf_jenisresep
                    WHERE kd_jenisresep = :kodeJenisResep
                    LIMIT 1
                ";
                $params = [":kodeJenisResep" => $kodeJenisResep];
                $jenisResep = $connection->createCommand($sql, $params)->queryScalar();

                if (!$daftarDetailPenjualan) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.masterf_penjualandetail
                        SET
                            no_resep = :noResep,
                            tgl_daftar = :tanggalDaftar,
                            tglResep1 = :tanggalAwalResep,
                            tglResep2 = :tanggalAkhirResep,
                            jenisResep = :jenisResep,
                            dokter = :dokter,
                            pembayaran = :pembayaran,
                            namaInstansi = :namaInstalasi,
                            namaPoli = :namaPoli,
                            nm_kamar = :namaKamar,
                            kd_inst = :kodeInstalasi,
                            kd_bayar = :kodeBayar,
                            kd_jenis_carabayar = :kodeJenisCaraBayar,
                            jns_carabayar = :jenisCaraBayar,
                            kd_rrawat = :kodeRuangRawat,
                            keterangan = :keterangan
                    ";
                    $params = [
                        ":noResep" => $noResep,
                        ":tanggalDaftar" => date("m/d/Y H:i:s", strtotime($tanggalDaftar)),
                        ":tanggalAwalResep" => $tanggalAwalResep,
                        ":tanggalAkhirResep" => $tanggalAkhirResep,
                        ":jenisResep" => $jenisResep,
                        ":dokter" => $dokter,
                        ":pembayaran" => $pPembayaran,
                        ":namaInstalasi" => $namaInstalasi,
                        ":namaPoli" => $namaPoli,
                        ":namaKamar" => $namaRuangRawat,
                        ":kodeInstalasi" => $kodeInstalasi,
                        ":kodeBayar" => $kodeBayar,
                        ":kodeJenisCaraBayar" => $kodeJenisCaraBayar,
                        ":jenisCaraBayar" => $jenisCaraBayar,
                        ":kodeRuangRawat" => $kodeRuangRawat,
                        ":keterangan" => $pNoUrut,
                    ];
                    $connection->createCommand($sql, $params)->execute();
                }

                $diskonObat = 0;
                $daftarTemuObat = [];
                $daftarJasaObatRacik = [];

                foreach ($daftarHargaJual as $key => $unused) {
                    $diskon = $daftarDiskonObat[$key];
                    $bridge = $daftarKodeObat2[$key] ?: $daftarKodeObat1;

                    $cekMinus = ($daftarKuantitas[$key] < 0) ? 1 : 0;
                    $jumlah = abs($daftarKuantitas[$key]);
                    $harga = $daftarHargaJual[$key];
                    $signa = $daftarKodeSigna[$key];

                    if ($verifikasi) {
                        $this->actionBridging4([
                            "fungsi" => "UPDATE_STOK_KARTU",
                            "KD_STORE" => "00001200",
                            "kode" => $bridge,
                            "jumlah" => $jumlah
                        ]);

                        $this->actionBridging4([
                            "fungsi" => "TAMBAH_STOK_KARTU2",
                            "KD_STORE" => "00001200",
                            "kodeObat" => $bridge,
                            "NO_TRANSAKSI" => $noResep,
                            "JML_KELUAR" => $jumlah,
                            "nama" => $namaPasien,
                            "no_urut" => $noUrut
                        ]);
                    }

                    $diskonHarga = $harga * $jumlah * $diskon / 100;
                    $diskonObat += $diskonHarga;

                    $dataKatalog = $this->actionBridging5([
                        "fungsi" => "SELECT_KATALOG_BRG",
                        "kode" => $bridge
                    ]);

                    $dataKatalog[0]["KD_KATALOG"] = str_replace("]", "", $dataKatalog[0][0]);
                    $dataKatalog[0]["KD_KATALOG"] ??= 0;

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT -- all are in use, no view file.
                            b.hna_item    AS hnaItem,
                            b.hp_item     AS hpItem,
                            b.hja_setting AS hjaSetting
                        FROM db1.masterf_katalog AS a
                        LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                        WHERE
                            a.kode = ''
                            AND b.sts_hja != 0
                            AND b.sts_hjapb != 0
                        LIMIT 1
                    ";
                    $params = [":kode" => $daftarKodeObat2[$key] ?: $daftarKodeObat1[$key]];
                    $harga2 = $connection->createCommand($sql, $params)->queryOne();

                    $diskon = $diskon ?: 0;
                    $noUrut = $noUrut ?: 0;

                    $daftarTagihan[$key] = $harga2->hjaSetting * $daftarKuantitas[$key];
                    if ($daftarKodeObat2[$key]) {
                        $daftarPelayanan[$key] = 0;
                    }

                    $this->actionBridging4([
                        "fungsi" => "tambah_TTGH_RESEP_OBAT2",
                        "no_resep" => $noResep,
                        "kode" => $bridge,
                        "kd_katalog" => $dataKatalog[0]["KD_KATALOG"],
                        "no_urut" => $noUrut,
                        "no_obat" => 0,
                        "tgl_resep" => $nowValSystem,
                        "jumlah_pakai" => $daftarKuantitas[$key],
                        "dose_perHari" => 3,
                        "dose_perMakan" => 0,
                        "durasi_hari" => 1,
                        "harga_satuan" => $harga2->hjaSetting,
                        "tagihan" => $daftarTagihan[$key],
                        "diskon_harga" => $diskonHarga,
                        "diskon_persen" => $diskon,
                        "jasa_obat" => 0,
                        "jumlah_retur" => 0,
                        "kd_jenisHarga" => "00",
                        "sts_dijamin" => 0,
                        "sts_bebanRsf" => 0,
                        "sts_fasilitas" => 0,
                        "sts_batal" => 0,
                        "sts_tagihanFo" => 0,
                        "sts_updateStok" => 0,
                        "userid" => "00000042",
                        "hna" => 0,
                        "hp" => 0
                    ]);

                    $noUrut++;
                    $minJumlah = $jumlah;

                    $urutanObat = $daftarKodeObatAwal[$key] ? ", urutan = '" . ($key + 1) . "'" : "";

                    if (!$daftarKodeObat1[$key]) continue;

                    $jumlahStok = ($cekMinus == 1) ? -$minJumlah : $minJumlah;

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            kode                  AS kode,
                            no_resep              AS noResep,
                            no_penjualan          AS noPenjualan,
                            diskon                AS diskon,
                            jasa                  AS jasa,
                            kodePenjualan         AS kodePenjualan,
                            kode_rm               AS kodeRekamMedis,
                            no_daftar             AS noPendaftaran,
                            nama_pasien           AS namaPasien,
                            kodeObat              AS kodeObat,
                            kodeObatdr            AS kodeObatDokter,
                            nama_obatdr           AS namaObatDokter,
                            urutan                AS urutan,
                            jlhPenjualan          AS jumlahPenjualan,
                            jlhPenjualandr        AS jumlahPenjualanDokter,
                            signa                 AS signa,
                            hna                   AS hna,
                            hp                    AS hp,
                            harga                 AS harga,
                            id_racik              AS idRacik,
                            kode_racik            AS kodeRacik,
                            nama_racik            AS namaRacik,
                            no_racik              AS noRacik,
                            ketjumlah             AS keteranganJumlah,
                            keterangan_obat       AS keteranganObat,
                            kode_depo             AS kodeDepo,
                            ranap                 AS rawatInap,
                            tglPenjualan          AS tanggalPenjualan,
                            lunas                 AS lunas,
                            verifikasi            AS verifikasi,
                            transfer              AS transfer,
                            resep                 AS resep,
                            tglverifikasi         AS tanggalVerifikasi,
                            tgltransfer           AS tanggalTransfer,
                            operator              AS operator,
                            tglbuat               AS tanggalBuat,
                            signa1                AS signa1,
                            signa2                AS signa2,
                            signa3                AS signa3,
                            dokter_perobat        AS dokterPerObat,
                            bayar                 AS bayar,
                            tglbayar              AS tanggalBayar,
                            checking_ketersediaan AS cekKetersediaan,
                            keteranganobat        AS keteranganObat,
                            kode_drperobat        AS kodeDokterPerObat,
                            kode_operator         AS kodeOperator,
                            kode_verifikasi       AS kodeVerifikasi,
                            kode_transfer         AS kodeTransfer
                        FROM db1.masterf_penjualan
                        WHERE
                            no_resep = :noResep
                            AND kodeObatdr = :kodeObatDokter
                    ";
                    $params = [":noResep" => $noResep, ":kodeObatDokter" => $daftarKodeObat1[$key]];
                    $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

                    if ($daftarPenjualan) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.masterf_penjualan
                            SET jlhPenjualan = jlhPenjualan + :jumlahStok
                            WHERE
                                no_resep = :noResep
                                AND kodeObatdr = :kodeObatDokter
                        ";
                        $params = [":jumlahStok" => $jumlahStok, ":noResep" => $noResep, ":kodeObatDokter" => $daftarKodeObat1[$key]];

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_penjualan
                            SET
                                kode_rm = :kodeRekamMedis,
                                no_daftar = :noPendaftaran,
                                dokter_perobat = :dokterPerObat,
                                nama_pasien = :namaPasien,
                                id_racik = :idRacik,
                                signa1 = :signa1,
                                signa2 = :signa2,
                                signa3 = :signa3,
                                harga = :harga,
                                jlhPenjualan = :jumlahPenjualan,
                                signa = :signa,
                                kodePenjualan = :kodePenjualan,
                                kodeObatdr = :kodeObatDokter,
                                kodeObat = :kodeObat,
                                tglPenjualan = :tanggalPenjualan,
                                kode_depo = :kodeDepo,
                                diskon = :diskon,
                                operator = :operator,
                                no_resep = :noResep,
                                hna = :hna,
                                hp = :hp
                                $urutanObat
                                $myverif
                        ";
                        $params = [
                            ":kodeRekamMedis" => $kodeRekamMedis,
                            ":noPendaftaran" => $noPendaftaran,
                            ":dokterPerObat" => $daftarNamaDokterPerObat[$key],
                            ":namaPasien" => $namaPasien,
                            ":idRacik" => $daftarIdRacik[$key],
                            ":signa1" => $daftarNamaSigna1[$key],
                            ":signa2" => $daftarNamaSigna2[$key],
                            ":signa3" => $daftarNamaSigna3[$key],
                            ":harga" => $harga,
                            ":jumlahPenjualan" => $jumlahStok,
                            ":signa" => $signa,
                            ":kodePenjualan" => $kodePenjualan,
                            ":kodeObatDokter" => $daftarKodeObat1[$key],
                            ":kodeObat" => $daftarKodeObat2[$key],
                            ":tanggalPenjualan" => $todayValSystem,
                            ":kodeDepo" => $idDepo,
                            ":diskon" => $diskon,
                            ":operator" => $operator,
                            ":noResep" => $noResep,
                            ":hna" => $harga2->hnaItem,
                            ":hp" => $harga2->hpItem,
                        ];
                    }
                    $connection->createCommand($sql, $params)->execute();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            id_listsigna   AS idListSigna,
                            kategori_signa AS kategoriSigna,
                            signa_name     AS namaSigna
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa1'
                            AND signa_name = :namaSigna
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna1[$key]];
                    $daftarSigna1 = $connection->createCommand($sql, $params)->queryAll();

                    if (!$daftarSigna1) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_listsigna
                            SET
                                signa_name = :namaSigna,
                                kategori_signa = 'signa1'
                        ";
                        $params = [":namaSigna" => $daftarNamaSigna1[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            id_listsigna   AS idListSigna,
                            kategori_signa AS kategoriSigna,
                            signa_name     AS namaSigna
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa2'
                            AND signa_name = :namaSigna
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna2[$key]];
                    $daftarSigna2 = $connection->createCommand($sql, $params)->queryAll();

                    if (!$daftarSigna2) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_listsigna
                            SET
                                signa_name = :namaSigna,
                                kategori_signa = 'signa2'
                        ";
                        $params = [":namaSigna" => $daftarNamaSigna2[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            id_listsigna   AS idListSigna,
                            kategori_signa AS kategoriSigna,
                            signa_name     AS namaSigna
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa3'
                            AND signa_name = :namaSigna
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna3[$key]];
                    $daftarSigna3 = $connection->createCommand($sql, $params)->queryAll();

                    if (!$daftarSigna3) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_listsigna
                            SET
                                signa_name = :namaSigna,
                                kategori_signa = 'signa3'
                        ";
                        $params = [":namaSigna" => $daftarNamaSigna3[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    if ($verifikasi && $daftarKodeObat2[$key]) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            SELECT kode_stokopname
                            FROM db1.relasif_ketersediaan AS c
                            INNER JOIN db1.transaksif_stokopname AS a ON a.kode = c.kode_stokopname
                            LEFT JOIN db1.masterf_aktifasiso AS b ON b.kode = a.kode_reff
                            WHERE
                                c.id_depo = :idDepo
                                AND c.id_katalog = :idKatalog
                                AND b.status = 1
                            LIMIT 1
                        ";
                        $params = [":idDepo" => $idDepo, ":idKatalog" => $daftarKodeObat2[$key]];
                        $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar();

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            SELECT jumlah_stokfisik
                            FROM db1.transaksif_stokkatalog
                            WHERE
                                id_katalog = :idKatalog
                                AND id_depo = :idDepo
                                AND status = 1
                            LIMIT 1
                        ";
                        $params = [":idKatalog" => $daftarKodeObat2[$key], ":idDepo" => $idDepo];
                        $jumlahStokFisikBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

                        if ($cekMinus == 1) {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                INSERT INTO db1.relasif_ketersediaan
                                SET
                                    id_depo = :idDepo,
                                    kode_reff = :kodeRef,
                                    kode_stokopname = :kodeStokopname,
                                    kode_transaksi = 'R',
                                    kode_store = '000000',
                                    tipe_tersedia = 'penjualan',
                                    tgl_tersedia = :tanggalTersedia,
                                    no_batch = '-',
                                    tgl_expired = :tanggalKadaluarsa,
                                    id_katalog = :idKatalog,
                                    harga_item = :hargaItem,
                                    harga_perolehan = :hargaPerolehan,
                                    jumlah_masuk = :jumlahMasuk,
                                    jumlah_keluar = 0,
                                    jumlah_tersedia = :jumlahTersedia,
                                    userid_last = :idUser,
                                    status = 1,
                                    keterangan = :keterangan
                            ";
                            $params = [
                                ":idDepo" => $idDepo,
                                ":kodeRef" => $noResep,
                                ":kodeStokopname" => $kodeStokOpname,
                                ":tanggalTersedia" => $nowValSystem,
                                ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                                ":idKatalog" => $daftarKodeObat2[$key],
                                ":hargaItem" => $harga,
                                ":hargaPerolehan" => $harga2->hpItem,
                                ":jumlahMasuk" => $jumlah,
                                ":jumlahTersedia" => $jumlahStokFisikBefore + $jumlah,
                                ":idUser" => $idUser,
                                ":keterangan" => "Pemakaian pasien $namaPasien",
                            ];
                            $connection->createCommand($sql, $params)->execute();

                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                UPDATE db1.transaksif_stokkatalog
                                SET
                                    jumlah_stokfisik = jumlah_stokfisik + :jumlah,
                                    jumlah_stokadm = jumlah_stokadm + :jumlah
                                WHERE
                                    id_katalog = :idKatalog
                                    AND id_depo = :idDepo
                            ";
                            $params = [":jumlah" => $jumlah, ":idKatalog" => $daftarKodeObat2[$key], ":idDepo" => $idDepo];
                            $connection->createCommand($sql, $params)->execute();

                        } else {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                INSERT INTO db1.relasif_ketersediaan
                                SET
                                    id_depo = :idDepo,
                                    kode_reff = :kodeRef,
                                    kode_stokopname = :kodeStokopname,
                                    kode_transaksi = 'R',
                                    kode_store = '000000',
                                    tipe_tersedia = 'penjualan',
                                    tgl_tersedia = :tanggalTersedia,
                                    no_batch = '-',
                                    tgl_expired = :tanggalKadaluarsa,
                                    id_katalog = :idKatalog,
                                    harga_item = :hargaItem,
                                    harga_perolehan = :hargaPerolehan,
                                    jumlah_masuk = 0,
                                    jumlah_keluar = :jumlahKeluar,
                                    jumlah_tersedia = :jumlahTersedia,
                                    status = 1,
                                    userid_last = :idUser,
                                    keterangan = :keterangan
                            ";
                            $params = [
                                ":idDepo" => $idDepo,
                                ":kodeRef" => $noResep,
                                ":kodeStokopname" => $kodeStokOpname,
                                ":tanggalTersedia" => $nowValSystem,
                                ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                                ":idKatalog" => $daftarKodeObat2[$key],
                                ":hargaItem" => $harga,
                                ":hargaPerolehan" => $harga2->hpItem,
                                ":jumlahKeluar" => $minJumlah,
                                ":jumlahTersedia" => $jumlahStokFisikBefore - $minJumlah,
                                ":idUser" => $idUser,
                                ":keterangan" => "Pemakaian pasien $namaPasien",
                            ];
                            $connection->createCommand($sql, $params)->execute();

                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                SELECT
                                    id_depo          AS idDepo,
                                    id_katalog       AS idKatalog,
                                    id_kemasan       AS idKemasan,
                                    jumlah_stokmin   AS jumlahStokMin,
                                    jumlah_stokmax   AS jumlahStokMaks,
                                    jumlah_stokfisik AS jumlahStokFisik,
                                    jumlah_stokadm   AS jumlahStokAdm,
                                    jumlah_itemfisik AS jumlahItemFisik,
                                    status           AS status,
                                    userid_in        AS useridInput,
                                    sysdate_in       AS sysdateInput,
                                    userid_updt      AS useridUpdate,
                                    sysdate_updt     AS sysdateUpdate,
                                    keterangan       AS keterangan,
                                    check_sync       AS cekSync
                                FROM db1.transaksif_stokkatalog
                                WHERE
                                    id_depo = :idDepo
                                    AND id_katalog = :idKatalog
                            ";
                            $params = [":idDepo" => $idDepo, ":idKatalog" => $daftarKodeObat2[$key]];
                            $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

                            if ($daftarStokKatalog) {
                                $sql = /** @lang SQL */ "
                                    -- FILE: ".__FILE__." 
                                    -- LINE: ".__LINE__." 
                                    UPDATE db1.transaksif_stokkatalog
                                    SET
                                        jumlah_stokfisik = jumlah_stokfisik - :jumlah,
                                        jumlah_stokadm = jumlah_stokadm - :jumlah
                                    WHERE
                                        id_katalog = :idKatalog
                                        AND id_depo = :idDepo
                                ";
                                $params = [":jumlah" => $jumlah, ":idKatalog" => $daftarKodeObat2[$key], ":idDepo" => $idDepo];

                            } else {
                                $sql = /** @lang SQL */ "
                                    -- FILE: ".__FILE__." 
                                    -- LINE: ".__LINE__." 
                                    INSERT INTO db1.transaksif_stokkatalog
                                    SET
                                        jumlah_stokfisik = :pengurangStokFisik,
                                        jumlah_stokadm = :pengurangStokAdm,
                                        jumlah_itemfisik = :itemFisik,
                                        id_kemasan = 0,
                                        status = 1,
                                        id_depo = :idDepo,
                                        id_katalog = :idKatalog
                                ";
                                $params = [
                                    ":pengurangStokFisik" => -$jumlah,
                                    ":pengurangStokAdm" => -$jumlah,
                                    ":itemFisik" => $jumlah,
                                    ":idDepo" => $idDepo,
                                    ":idKatalog" => $daftarKodeObat2[$key],
                                ];
                            }
                            $connection->createCommand($sql, $params)->execute();
                        }

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.masterf_ketersediaan
                            SET jlhTersedia = jlhTersedia - :minJumlah
                            WHERE
                                kodeDepo = :kodeDepo
                                AND kodeObat = :kodeObat
                        ";
                        $params = [":minJumlah" => $minJumlah, ":kodeDepo" => $idDepo, ":kodeObat" => $daftarKodeObat2[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    if (!$daftarKodeObat1[$key]) continue;
                    $jasaHargaObat22 ??= 0;
                    $jasaRacik22 ??= 0;
                    $jasaObat22 ??= 0;

                    if (!$daftarIdRacik[$key] && $kodeJenisResep != "00" && !$daftarTemuObat[$daftarKodeObat1[$key]]) {
                        $jasaHargaObat22 += 300;
                        $jasaObat22 += 300;
                        $daftarTemuObat[$daftarKodeObat1[$key]] = "exist";

                    } elseif (!$daftarJasaObatRacik[$daftarIdRacik[$key]] && $kodeJenisResep != "00") {
                        $daftarJasaObatRacik[$daftarIdRacik[$key]] = "exist";
                        $jasaHargaObat22 += 500;
                        $jasaRacik22 += 500;
                    }
                }

                $penandaNamaRacikan = "";
                $hargaPembungkus = 0;
                $diskonRacik = 0;
                $daftarHargaPembungkusPeracik = [];

                foreach ($daftarKodePembungkus ?? [] as $key4 => $kodePembungkus) {
                    if (!$kodePembungkus) continue;
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.masterf_reseppembungkus
                        SET
                            no_resep = :noResep,
                            kd_pembungkus = :kodePembungkus,
                            jumlah = :jumlah
                    ";
                    $params = [":noResep" => $noResep, ":kodePembungkus" => $kodePembungkus, ":jumlah" => $daftarJumlahPembungkus[$key4]];
                    $connection ->createCommand($sql, $params)->execute();
                    $hargaPembungkus += $daftarJumlahPembungkus[$key4] * $daftarHargaPembungkus[$key4];
                }

                foreach ($daftarNamaRacikan ?? [] as $key => $namaRacikan) {
                    $racik = date("ymd") . rand(1000, 9999);
                    $diskon = $daftarDiskonRacikan[$key];

                    if ($penandaNamaRacikan != $namaRacikan) {
                        $daftarLayananRacik[$key] = 0;
                        $penandaNamaRacikan = $namaRacikan;
                    }

                    foreach ($_POST["kode_pembungkus-$key"] as $key4 => $kodePembungkus) {
                        $pKuantitasPembungkus = $_POST["qtypembungkus-$key"][$key4];
                        $pHargaPembungkus = $_POST["hargapembungkus-$key"][$key4];

                        $this->actionBridging5([
                            "fungsi" => "TAMBAH_TINV_RESEP_OBAT_PEMBUNGKUS2",
                            "no_resep" => $noResep,
                            "tgl_resep" => $nowValSystem,
                            "KD_PEMBUNGKUS" => $kodePembungkus,
                            "JML_PAKAI" => $pKuantitasPembungkus,
                            "TARIF" => $pHargaPembungkus,
                        ]);

                        if (!$kodePembungkus) continue;

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_reseppembungkus
                            SET
                                no_resep = :noResep,
                                kode_racik = :kodeRacik,
                                kd_pembungkus = :kodePembungkus,
                                jumlah = :jumlah
                        ";
                        $params = [
                            ":noResep" => $noResep,
                            ":kodeRacik" => $racik,
                            ":kodePembungkus" => $kodePembungkus,
                            ":jumlah" => $pKuantitasPembungkus,
                        ];
                        $connection->createCommand($sql, $params)->execute();
                        $hargaPembungkus += $pKuantitasPembungkus * $pHargaPembungkus;
                        $daftarHargaPembungkusPeracik[$key] = $pKuantitasPembungkus * $pHargaPembungkus;
                    }

                    foreach ($_POST["kode_obat-$key"] as $key2 => $kodeObat) {
                        $hargaSatuan = $_POST["hargasatuan-$key"][$key2];
                        $jumlah = $_POST["qty-$key"][$key2] ?: 1;
                        $keteranganJumlah = $_POST["ketjumlah-$key"][$key2];

                        $dataKatalog = $this->actionBridging5([
                            "fungsi" => "SELECT_KATALOG_BRG",
                            "kode" => $kodeObat
                        ]);

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            SELECT harga_jual
                            FROM db1.masterf_katalog
                            WHERE kode = :kode
                            LIMIT 1
                        ";
                        $params = [":kode" => $namaRacikan];
                        $hargaJual = $connection->createCommand($sql, $params)->queryScalar();

                        $daftarTagihanRacik[$key2] = $hargaJual * $jumlah;
                        $diskonHarga = $hargaSatuan * $jumlah * $diskon / 100;
                        $diskonRacik += $diskonHarga;

                        $dataKatalog[0]["KD_KATALOG"] ??= 0;

                        $this->actionBridging4([
                            "fungsi" => "tambah_TTGH_RESEP_OBAT2",
                            "no_resep" => $noResep,
                            "kode" => $namaRacikan,
                            "kd_katalog" => $dataKatalog[0]["KD_KATALOG"],
                            "no_urut" => $noUrut,
                            "no_obat" => $key,
                            "tgl_resep" => $nowValSystem,
                            "jumlah_pakai" => $jumlah,
                            "dose_perHari" => 3,
                            "dose_perMakan" => 0,
                            "durasi_hari" => 1,
                            "harga_satuan" => $hargaJual,
                            "tagihan" => $daftarTagihanRacik[$key2],
                            "diskon_harga" => $diskonHarga,
                            "diskon_persen" => $diskon,
                            "jasa_obat" => 0,
                            "jumlah_retur" => 0,
                            "kd_jenisHarga" => "00",
                            "sts_dijamin" => 0,
                            "sts_bebanRsf" => 0,
                            "sts_fasilitas" => 0,
                            "sts_batal" => 0,
                            "sts_tagihanFo" => 0,
                            "sts_updateStok" => 0,
                            "userid" => "00000042",
                            "hna" => 0,
                            "hp" => 0
                        ]);

                        $this->actionBridging4([
                            "fungsi" => "tambah_TTGH_RESEP_RACIKAN2",
                            "no_resep" => $noResep,
                            "NO_RACIK" => $key,
                            "tgl_resep" => $nowValSystem,
                            "JASA_RACIK" => $daftarHargaPembungkusPeracik[$key]
                        ]);

                        if ($kodeObat) {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                INSERT INTO db1.masterf_penjualan
                                SET
                                    kode_rm = :kodeRekamMedis,
                                    no_daftar = :noPendaftaran,
                                    operator = :operator,
                                    no_racik = :noRacik,
                                    nama_pasien = :namaPasien,
                                    harga = :harga,
                                    jlhPenjualan = :jumlahPenjualan,
                                    ketjumlah = :keteranganJumlah,
                                    signa = :signa,
                                    kodePenjualan = :kodePenjualan,
                                    kodeObat = :kodeObat,
                                    tglPenjualan = :tanggalPenjualan,
                                    kode_depo = :kodeDepo,
                                    no_resep = :noResep,
                                    kode_racik = :kodeRacik,
                                    diskon = :diskon,
                                    nama_racik = :namaRacik
                                    $myverif
                            ";
                            $params = [
                                ":kodeRekamMedis" => $kodeRekamMedis,
                                ":noPendaftaran" => $noPendaftaran,
                                ":operator" => $operator,
                                ":noRacik" => $daftarNoRacikan[$key],
                                ":namaPasien" => $namaPasien,
                                ":harga" => $hargaSatuan,
                                ":jumlahPenjualan" => $jumlah,
                                ":keteranganJumlah" => $keteranganJumlah,
                                ":signa" => $daftarKodeSignaRacikan[$key],
                                ":kodePenjualan" => $kodePenjualan,
                                ":kodeObat" => $kodeObat,
                                ":tanggalPenjualan" => $todayValSystem,
                                ":kodeDepo" => $idDepo,
                                ":noResep" => $noResep,
                                ":kodeRacik" => $racik,
                                ":diskon" => $diskon,
                                ":namaRacik" => $namaRacikan,
                            ];
                            $connection->createCommand($sql, $params)->execute();
                            $noUrut++;
                        }

                        if (!$verifikasi) continue;

                        $this->actionBridging4([
                            "fungsi" => "UPDATE_STOK_KARTU",
                            "KD_STORE" => "00001200",
                            "kode" => $kodeObat,
                            "jumlah" => $jumlah
                        ]);

                        $this->actionBridging4([
                            "fungsi" => "TAMBAH_STOK_KARTU2",
                            "KD_STORE" => "00001200",
                            "kodeObat" => $kodeObat,
                            "NO_TRANSAKSI" => $noResep,
                            "JML_KELUAR" => $jumlah,
                            "nama" => $namaPasien,
                            "no_urut" => $noUrut
                        ]);
                    }
                }
            } else {
                $kodePenjualan = $id;
            }

            $jasaObat = 0;
            $tagihanObat = 0;
            foreach ($daftarPelayanan as $key => $pelayanan) {
                $jasaObat += $pelayanan;
                $tagihanObat += $daftarTagihan[$key];
            }

            $jasaRacik = 0;
            foreach ($daftarLayananRacik as $key2 => $layananRacik) {
                $jasaRacik += $layananRacik;
            }

            $tagihanRacik = 0;
            foreach ($daftarTagihanRacik as $tRacik) {
                $tagihanRacik += $tRacik;
            }

            $jasaRacik = count($daftarLayananRacik) * $jasaRacikObat;
            $totalAwal = $tagihanObat + $tagihanRacik;
            $totalDenganJasa = $totalAwal + $jasaHargaObat22;
            $totalDiskon = $diskonObat + $diskonRacik;
            $pembulatan = ceil(($totalDenganJasa + $hargaPembungkus - $totalDiskon) / 100) * 100;
            $jasaPelayanan = $pembulatan - $totalDenganJasa - $hargaPembungkus + $totalDiskon;
            $totalJasaPelayanan = $pembulatan - $totalAwal - $hargaPembungkus + $totalDiskon;
            $totaltjpost = $grandTotal - $pJasaPelayanan + $totalDiskon;

            $this->actionBridging4([
                "fungsi" => "tambah_TTGH_RESEP_REKAP",
                "no_resep" => $noResep,
                "tgl_resep" => $nowValSystem,
                "kd_jenis_harga" => "00",
                "tagihan" => ($totaltjpost),
                "diskon" => $totalDiskon,
                "jasa_obat" => $jasaObat22,
                "jasa_racik" => $jasaRacik22,
                "pembungkus" => $hargaPembungkus,
                "pembulatan" => $pJasaPelayanan - (floor($pJasaPelayanan / 100) * 100),
                "um_tagihan" => ($totaltjpost),
                "um_jasa_obat" => $jasaObat,
                "um_jasa_racik" => $jasaRacik,
                "um_pembungkus" => $hargaPembungkus,
                "um_pembulatan" => $jasaPelayanan,
                "bayar" => 0,
                "jml_jasa_obat" => count($daftarPelayanan),
                "jml_jasa_racik" => count($daftarLayananRacik),
                "sts_batal" => 0,
            ]);

            $this->actionBridging4([
                "fungsi" => "tambah_TINV_RESEP_OBAT_PAKAI",
                "no_resep" => $noResep,
                "kd_jenisresep" => $kodeJenisResep,
                "tgl_resep" => $nowValSystem,
                "kd_instalasi" => $kodeInstalasi,
                "kd_poli" => $kodePoli,
                "kd_bayar" => $kodeBayar,
                "kd_jenisCarabayar" => $kodeJenisCaraBayar,
                "no_ttidur" => "00000",
                "kd_rrawat" => $kodeRuangRawat,
                "kd_kamar" => "3030",
                "nm_dokter" => null,
                "userid" => "00000042",
                "klp_bayar" => 0,
                "sts_batal" => 0,
                "sts_bayar" => 0,
                "sts_updateStok" => $verif,
                "sts_verifikasi" => $verif,
                "sts_transfer" => 0,
                "tgl_rawat" => null
            ]);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_penjualandetail
                SET
                    total = :total,
                    jasapelayanan = :jasaPelayanan,
                    totalpembungkus = :totalPembungkus,
                    totaldiskon = :totalDiskon
                WHERE no_resep = :noResep
            ";
            $params = [
                ":total" => $pembulatan,
                ":jasaPelayanan" => $totalJasaPelayanan,
                ":totalPembungkus" => $hargaPembungkus,
                ":totalDiskon" => $totalDiskon,
                ":noResep" => $noResep,
            ];
            $connection->createCommand($sql, $params)->execute();

            if ($kodePenjualanSebelumnya) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.masterf_antrian
                    SET kode_penjualan = :kodeBaru
                    WHERE kode_penjualan = :kodeLama
                ";
                $params = [":kodeBaru" => $kodePenjualan, ":kodeLama" => $kodePenjualanSebelumnya];
                $connection->createCommand($sql, $params)->execute();
            }
        } else {
            $tagihan22 = 0;
            $returTagihan = 0;

            foreach ($daftarKodeObat1 as $key => $kodeObat) {
                $diskon = $daftarDiskonObat[$key] ?: 0;
                $minJumlah = abs($daftarKuantitas[$key]);
                $jumlah = $daftarKuantitas[$key];

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT TRUE
                    FROM db1.masterf_penjualan
                    WHERE
                        kodeObat = :kodeObat
                        AND jlhPenjualan < 0
                        AND no_resep = :noResep
                    LIMIT 1
                ";
                $params = [":kodeObat" => $kodeObat, ":noResep" => $noResep];
                $cekRetur = $connection->createCommand($sql, $params)->queryScalar();

                if ($jumlah >= 0 || $cekRetur) continue;

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.masterf_penjualan
                    WHERE
                        kodeObat = :kodeObat
                        AND (jlhPenjualan = :jumlahPenjualan OR jlhPenjualan < 0)
                        AND no_resep = :noResep
                ";
                $params = [":kodeObat" => $kodeObat, ":jumlahPenjualan" => $jumlah, ":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $harga = $daftarHargaJual[$key];
                $jumlah = $minJumlah;
                $signa = $daftarKodeSigna[$key];
                $diskonHarga = $harga * $jumlah * $diskon / 100;

                $dataKatalog = $this->actionBridging5([
                    "fungsi" => "SELECT_KATALOG_BRG",
                    "kode" => $kodeObat
                ]);

                $dataKatalog[0]["KD_KATALOG"] ??= 0;

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        hp_item     AS hpItem,
                        hja_setting AS hjaSetting
                    FROM db1.masterf_katalog AS a
                    LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                    WHERE
                        a.kode = :kode
                        AND b.sts_hja != 0
                        AND b.sts_hjapb != 0
                    LIMIT 1
                ";
                $params = [":kode" => $kodeObat];
                $harga2 = $connection->createCommand($sql, $params)->queryOne();

                $noUrut = $noUrut ?: 0;

                $daftarTagihan[$key] = $harga2->hjaSetting * $jumlah;
                $tagihan22 += $harga2->hjaSetting * $jumlah;
                $returTagihan = $tagihan22;

                $daftarKodeObat2[$key] ? $daftarPelayanan[$key] = 0 : null;

                $this->actionBridging4([
                    "fungsi" => "tambah_TTGH_RESEP_OBAT2_RETUR",
                    "no_resep" => $noResep,
                    "kode" => $kodeObat,
                    "kd_katalog" => $dataKatalog[0]["KD_KATALOG"],
                    "no_urut" => $noUrut,
                    "no_obat" => 0,
                    "tgl_resep" => $todayValSystem,
                    "jumlah_pakai" => $jumlah,
                    "dose_perHari" => 3,
                    "dose_perMakan" => 0,
                    "durasi_hari" => 1,
                    "harga_satuan" => $harga2->hjaSetting,
                    "tagihan" => $daftarTagihan[$key],
                    "diskon_harga" => $diskonHarga,
                    "diskon_persen" => $diskon,
                    "jasa_obat" => 0,
                    "jumlah_retur" => 0,
                    "kd_jenisHarga" => "00",
                    "sts_dijamin" => 0,
                    "sts_bebanRsf" => 0,
                    "sts_fasilitas" => 0,
                    "sts_batal" => 0,
                    "sts_tagihanFo" => 0,
                    "sts_updateStok" => 0,
                    "userid" => "00000042",
                    "hna" => 0,
                    "hp" => 0
                ]);

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.masterf_penjualan
                    SET
                        kode_rm = :kodeRekamMedis,
                        no_daftar = :noPendaftaran,
                        nama_pasien = :namaPasien,
                        id_racik = :idRacik,
                        signa1 = :signa1,
                        signa2 = :signa2,
                        signa3 = :signa3,
                        harga = :harga,
                        jlhPenjualan = :jumlahPenjualan,
                        signa = :signa,
                        kodePenjualan = :kodePenjualan,
                        kodeObat = :kodeObat,
                        tglPenjualan = :tanggalPenjualan,
                        kode_depo = :kodeDepo,
                        diskon = 0,
                        operator = :operator,
                        no_resep = :noResep
                ";
                $params = [
                    ":kodeRekamMedis" => $kodeRekamMedis,
                    ":noPendaftaran" => $noPendaftaran,
                    ":namaPasien" => $namaPasien,
                    ":idRacik" => $daftarIdRacik[$key],
                    ":signa1" => $daftarNamaSigna1[$key],
                    ":signa2" => $daftarNamaSigna2[$key],
                    ":signa3" => $daftarNamaSigna3[$key],
                    ":harga" => $harga,
                    ":jumlahPenjualan" => $daftarKuantitas[$key],
                    ":signa" => $signa,
                    ":kodePenjualan" => $editResep,
                    ":kodeObat" => $kodeObat,
                    ":tanggalPenjualan" => $todayValSystem,
                    ":kodeDepo" => Yii::$app->userFatma->kodeDepo,
                    ":operator" => $operator,
                    ":noResep" => $noResep,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT kode_stokopname
                    FROM db1.relasif_ketersediaan AS c
                    INNER JOIN db1.transaksif_stokopname AS a ON a.kode = c.kode_stokopname
                    INNER JOIN db1.masterf_aktifasiso AS b ON b.kode = a.kode_reff
                    WHERE
                        c.id_depo = :idDepo
                        AND c.id_katalog = :idKatalog
                        AND b.status = 1
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokfisik
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_katalog = :idKatalog
                        AND id_depo = :idDepo
                        AND status = 1
                    LIMIT 1
                ";
                $params = [":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                $jumlahStokFisikBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        kode                  AS kode,
                        no_resep              AS noResep,
                        no_penjualan          AS noPenjualan,
                        diskon                AS diskon,
                        jasa                  AS jasa,
                        kodePenjualan         AS kodePenjualan,
                        kode_rm               AS kodeRekamMedis,
                        no_daftar             AS noPendaftaran,
                        nama_pasien           AS namaPasien,
                        kodeObat              AS kodeObat,
                        kodeObatdr            AS kodeObatDokter,
                        nama_obatdr           AS namaObatDokter,
                        urutan                AS urutan,
                        jlhPenjualan          AS jumlahPenjualan,
                        jlhPenjualandr        AS jumlahPenjualanDokter,
                        signa                 AS signa,
                        hna                   AS hna,
                        hp                    AS hp,
                        harga                 AS harga,
                        id_racik              AS idRacik,
                        kode_racik            AS kodeRacik,
                        nama_racik            AS namaRacik,
                        no_racik              AS noRacik,
                        ketjumlah             AS keteranganJumlah,
                        keterangan_obat       AS keteranganObat,
                        kode_depo             AS kodeDepo,
                        ranap                 AS rawatInap,
                        tglPenjualan          AS tanggalPenjualan,
                        lunas                 AS lunas,
                        verifikasi            AS verifikasi,
                        transfer              AS transfer,
                        resep                 AS resep,
                        tglverifikasi         AS tanggalVerifikasi,
                        tgltransfer           AS tanggalTransfer,
                        operator              AS operator,
                        tglbuat               AS tanggalBuat,
                        signa1                AS signa1,
                        signa2                AS signa2,
                        signa3                AS signa3,
                        dokter_perobat        AS dokterPerObat,
                        bayar                 AS bayar,
                        tglbayar              AS tanggalBayar,
                        checking_ketersediaan AS cekKetersediaan,
                        keteranganobat        AS keteranganObat,
                        kode_drperobat        AS kodeDokterPerObat,
                        kode_operator         AS kodeOperator,
                        kode_verifikasi       AS kodeVerifikasi,
                        kode_transfer         AS kodeTransfer
                    FROM db1.masterf_penjualan
                    WHERE
                        no_resep = :noResep
                        AND kodeObat = :kodeObat
                        AND jlhPenjualan = :jumlahPenjualan
                ";
                $params = [":noResep" => $noResep, ":kodeObat" => $kodeObat, ":jumlahPenjualan" => -$daftarKuantitas[$key]];
                $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

                if ($daftarPenjualan && !$daftarIdRacik[$key]) {
                    $tagihan22 += 300;
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.masterf_penjualandetail
                        SET
                            jasapelayanan = jasapelayanan - 300,
                            total = total - 300
                        WHERE no_resep = :noResep
                    ";
                    $params = [":noResep" => $noResep];
                    $connection->createCommand($sql, $params)->execute();
                }
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        id_depo = :idDepo,
                        kode_reff = :kodeRef,
                        kode_stokopname = :kodeStokopname,
                        kode_transaksi = 'R',
                        kode_store = '000000',
                        tipe_tersedia = 'penjualan',
                        tgl_tersedia = :tanggalTersedia,
                        no_batch = '-',
                        tgl_expired = :tanggalKadaluarsa,
                        id_katalog = :idKatalog,
                        harga_item = :hargaItem,
                        harga_perolehan = :hargaPerolehan,
                        jumlah_masuk = :jumlahMasuk,
                        jumlah_keluar = 0,
                        userid_last = :idUser,
                        jumlah_tersedia = :jumlahTersedia,
                        status = 1,
                        keterangan = :keterangan
                ";
                $params = [
                    ":idDepo" => $idDepo,
                    ":kodeRef" => $noResep,
                    ":kodeStokopname" => $kodeStokOpname,
                    ":tanggalTersedia" => $nowValSystem,
                    ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                    ":idKatalog" => $daftarKodeObat2[$key],
                    ":hargaItem" => $harga2->hjaSetting,
                    ":hargaPerolehan" => $harga2->hpItem,
                    ":jumlahMasuk" => $minJumlah,
                    ":idUser" => $idUser,
                    ":jumlahTersedia" => $jumlahStokFisikBefore + $minJumlah,
                    ":keterangan" => "Returan pasien $namaPasien",
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = jumlah_stokfisik + :minJumlah,
                        jumlah_stokadm = jumlah_stokadm + :minJumlah
                    WHERE
                        id_katalog = :idKatalog
                        AND id_depo = :idDepo
                ";
                $params = [":minJumlah" => $minJumlah, ":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                $connection->createCommand($sql, $params)->execute();
            }

            $this->actionBridging4([
                "fungsi" => "tambah_TTGH_RESEP_REKAP_RETUR",
                "no_resep" => $noResep,
                "tgl_resep" => $todayValSystem,
                "kd_jenis_harga" => "00",
                "tagihan" => $tagihan22,
                "diskon" => 0,
                "jasa_obat" => 0,
                "jasa_racik" => 0,
                "pembungkus" => 0,
                "pembulatan" => 0,
                "um_tagihan" => 0,
                "um_jasa_obat" => 0,
                "um_jasa_racik" => 0,
                "um_pembungkus" => 0,
                "um_pembulatan" => 0,
                "bayar" => 0,
                "jml_jasa_obat" => 0,
                "jml_jasa_racik" => 0,
                "sts_batal" => 0,
            ]);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT pembayaran
                FROM db1.masterf_penjualandetail
                WHERE no_resep = :noResep
                LIMIT 1
            ";
            $params = [":noResep" => $noResep];
            $pembayaran = $connection->createCommand($sql, $params)->queryScalar();

            if ($pembayaran != $pPembayaran) {
                if ($pPembayaran == "Tunai") {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.masterf_penjualandetail
                        SET
                            pembayaran = :pembayaran,
                            kd_bayar = '001',
                            kd_jenis_carabayar = '01',
                            jns_carabayar = 'Tunai'
                        WHERE no_resep = :noResep
                    ";
                    $params = [":pembayaran" => $pPembayaran, ":noResep" => $noResep];

                } else {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.masterf_penjualandetail
                        SET pembayaran = :pembayaran
                        WHERE no_resep = :noResep
                    ";
                    $params = [":pembayaran" => $pPembayaran, ":noResep" => $noResep];
                }
                $connection->createCommand($sql, $params)->execute();
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_penjualandetail
                SET total_retur = :totalRetur
                WHERE no_resep = :noResep
            ";
            $params = [":totalRetur" => $returTagihan, ":noResep" => $noResep];
            $connection->createCommand($sql, $params)->execute();
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT jenisresep
            FROM db1.masterf_jenisresep
            WHERE kd_jenisresep = :kodeJenisResep
            LIMIT 1
        ";
        $params = [":kodeJenisResep" => $kodeJenisResep];
        $jenisResep = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualandetail
            SET
                jasapelayanan = :jasaPelayanan,
                jenisResep = :jenisResep
            WHERE no_resep = :noResep
        ";
        $params = [":jasaPelayanan" => $totalJasaPelayanan ?? 0, ":jenisResep" => $jenisResep, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        return $noResep;
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#verifikasi    the original method
     */
    public function actionVerifikasi(): void
    {
        ["id" => $noResep] = Yii::$app->request->post();
        $namaUser = Yii::$app->userFatma->nama;
        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                no_resep     AS noResep,
                nama_pasien  AS namaPasien,
                kodeObat     AS kodeObat,
                jlhPenjualan AS jumlahPenjualan
            FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT verifikasi
            FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $verifikasi = $connection->createCommand($sql, $params)->queryScalar();
        if ($verifikasi) return;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualandetail
            SET iter2 = iter2 + 1
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualan
            SET
                verifikasi = :verifikasi,
                tglverifikasi = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":verifikasi" => $namaUser, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $idDepo = Yii::$app->userFatma->idDepo;
        foreach ($daftarPenjualan as $penjualan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_tersedia
                FROM db1.relasif_ketersediaan
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
                ORDER BY id DESC
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $penjualan->kodeObat];
            $jumlahTersediaBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode_stokopname
                FROM db1.relasif_ketersediaan AS c
                INNER JOIN db1.transaksif_stokopname AS a ON a.kode = c.kode_stokopname
                INNER JOIN db1.masterf_aktifasiso AS b ON b.kode = a.kode_reff
                WHERE
                    c.id_depo = :idDepo
                    AND c.id_katalog = :idKatalog
                    AND b.status = 1
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $penjualan->kodeObat];
            $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    b.hja_setting AS hjaSetting,
                    b.hp_item     AS hpItem
                FROM db1.masterf_katalog AS a
                LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                WHERE
                    a.kode = :kode
                    AND (b.sts_hja != 0 OR b.sts_hjapb != 0)
                LIMIT 1
            ";
            // TODO: sql: uncategorized: trace $val comes from
            $params = [":kode" => $val];
            $penjualan2 = $connection->createCommand($sql, $params)->queryOne();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_ketersediaan
                SET
                    id_depo = :idDepo,
                    kode_reff = :kodeRef,
                    kode_stokopname = :kodeStokopname,
                    kode_transaksi = 'R',
                    kode_store = '000000',
                    tipe_tersedia = 'penjualan',
                    tgl_tersedia = :tanggalTersedia,
                    no_batch = '-',
                    tgl_expired = :tanggalKadaluarsa,
                    id_katalog = :idKatalog,
                    harga_item = :hargaItem,
                    harga_perolehan = :hargaPerolehan,
                    jumlah_masuk = 0,
                    jumlah_keluar = :jumlahKeluar,
                    jumlah_tersedia = :jumlahTersedia,
                    status = 1,
                    keterangan = :keterangan
            ";
            $params = [
                ":idDepo" => $idDepo,
                ":kodeRef" => $penjualan->noResep,
                ":kodeStokopname" => $kodeStokOpname,
                ":tanggalTersedia" => $nowValSystem,
                ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                ":idKatalog" => $penjualan->kodeObat,
                ":hargaItem" => $penjualan2->hjaSetting,
                ":hargaPerolehan" => $penjualan2->hpItem,
                ":jumlahKeluar" => $penjualan->jumlahPenjualan,
                ":jumlahTersedia" => $jumlahTersediaBefore - $penjualan->jumlahPenjualan,
                ":keterangan" => "Pemakaian pasien " . $penjualan->namaPasien
            ];
            $connection->createCommand($sql, $params)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_ketersediaan
                SET jlhTersedia = jlhTersedia - :jumlahPenjualan
                WHERE
                    kodeDepo = :kodeDepo
                    AND kodeObat = :kodeObat
            ";
            $params = [":jumlahPenjualan" => $penjualan->jumlahPenjualan, ":kodeDepo" => $idDepo, ":kodeObat" => $penjualan->kodeObat];
            $connection->createCommand($sql, $params)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT sts_opname
                FROM db1.transaksif_stokopname
                WHERE id_depo = :idDepo
            ";
            $params = [":idDepo" => $idDepo];
            $statusOpname = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_stokkatalog
                SET
                    jumlah_stokfisik = jumlah_stokfisik - :jumlahPenjualan,
                    jumlah_stokadm = jumlah_stokadm - :minJumlahAdm
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
            ";
            $params = [
                ":jumlahPenjualan" => $penjualan->jumlahPenjualan,
                ":minJumlahAdm" => ($statusOpname == 1) ? 0 : $penjualan->jumlahPenjualan,
                ":idDepo" => $idDepo,
                ":idKatalog" => $penjualan->kodeObat,
            ];
            $connection->createCommand($sql, $params)->execute();
        }

        $this->actionBridging4(["fungsi" => "tambah_TTGH_RESEP_REKAP3", "no_resep" => $noResep, "userverif" => $namaUser]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#transfer    the original method
     */
    public function actionTransfer(): void
    {
        ["id" => $noResep, "daftar" => $daftar] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $connection = Yii::$app->dbFatma;

        $getkeluar = $this->actionTestBridgeCekKeluar($daftar);
        if ($getkeluar[1] == 1) return;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode_rm
            FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $kodeRekamMedis = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                a.harga      AS harga,
                a.id_racik   AS idRacik,
                c.pembayaran AS pembayaran
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            WHERE a.no_resep = :noResep
            ORDER BY a.id_racik ASC
        ";
        $params = [":noResep" => $noResep];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualan
            SET
                transfer = :transfer,
                tgltransfer = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":transfer" => Yii::$app->userFatma->nama, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $racik = "";
        $jasaRacik = 0;
        $jasaObat = 0;

        $this->actionBridging4(["fungsi" => "carinodaftar", "NORM" => $kodeRekamMedis]);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                a.no_resep                       AS noResep,
                a.pembayaran                     AS pembayaran,
                a.KD_INST                        AS kodeInstalasi,
                a.KD_POLI                        AS kodePoli,
                a.TGL_DAFTAR                     AS tanggalPendaftaran,
                IFNULL(b.no_daftar, c.no_daftar) AS noDaftar
            FROM db1.masterf_penjualandetail AS a
            LEFT JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            LEFT JOIN db1.pasien_small AS c ON c.no_rm = b.kode_rm
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $dataBaru = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND id_racik != ''
                AND id_racik != 0
            GROUP BY id_racik
        ";
        $params = [":noResep" => $noResep];
        $jumlahPenjualan = $connection->createCommand($sql, $params)->queryScalar();
        $totalRacikan = $jumlahPenjualan * 500;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                SUM(b.totalharga)                    AS totalJual
            FROM (
                SELECT
                    CASE
                        WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 300
                        WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                        ELSE 0
                    END AS jasapelayanan,
                    a.totalharga,
                    a.no_resep
                FROM (
                    SELECT
                        SUM(jlhPenjualan)         AS total,
                        id_racik                  AS id_racik,
                        SUM(jlhPenjualan * harga) AS totalharga,
                        mp.no_resep               AS no_resep,
                        jenisResep                AS jenisResep
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                    WHERE mp.no_resep = :noResep
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.no_resep
        ";
        $params = [":totalRacikan" => $totalRacikan, ":noResep" => $noResep];
        $penjualan = $connection->createCommand($sql, $params)->queryOne();

        $totalCeil = ceil($penjualan->totalJual / 100) * 100;
        $grandTotal = ceil(($penjualan->totalJual + $penjualan->totalJasaPelayanan) / 100) * 100;
        $penjualan->totalJasaPelayanan += $totalCeil - $penjualan->totalJual;

        if ($dataBaru->pembayaran == "Tunai") {
            $hb = $penjualan->totalJual;
            $dj = '0.00';
            $hbLayanan = $penjualan->totalJasaPelayanan;
            $djLayanan = 0;
        } else {
            $hb = '0.00';
            $dj = $penjualan->totalJual;
            $hbLayanan = 0;
            $djLayanan = $penjualan->totalJasaPelayanan;
        }

        $this->actionBridging4([
            "fungsi" => "tambah_TBIL_TAGIHAN_LYN2",
            "NO_PENDAFTARAN" => $dataBaru->noDaftar,
            "KD_USER" => Yii::$app->userFatma->idMedysis,
            "KD_ITEM" => 9000000000,
            "TGL_TRANSAKSI" => date("m/d/Y H:i:s"),
            "KD_INST" => Yii::$app->userFatma->kodeInstalasiDepo,
            "TGL_RAWAT" => $dataBaru->tanggalPendaftaran,
            "KD_POLI" => $dataBaru->kodePoli,
            "NO_RESEP" => $dataBaru->noResep,
            "HB" => $hb,
            "DIJAMIN" => $dj,
            "HB_LYN" => $hbLayanan,
            "DIJAMIN_LYN" => $djLayanan,
            "JASA" => $penjualan->totalJual,
            "JASA_BA" => $grandTotal,
            "JASA_LYN" => $penjualan->totalJasaPelayanan
        ]);

        foreach ($daftarPenjualan as $penjualan2) {
            if ($penjualan2->idRacik != $racik) {
                $jasaRacik++;
            }
            if ($penjualan2->idRacik) {
                $jasaObat++;
            }
            $racik = $penjualan2->idRacik;
            if ($penjualan2->pembayaran == "Tunai") {
                $hb = $penjualan2->harga;
                $dj = '0.00';
            } else {
                $hb = '0.00';
                $dj = $penjualan2->harga;
            }

            $this->actionBridging4([
                "fungsi" => "tambah_TBIL_TAGIHAN_LYN",
                "NO_PENDAFTARAN" => $dataBaru->noDaftar,
                "KD_ITEM" => 9000000000,
                "TGL_TRANSAKSI" => date("m/d/Y H:i:s", strtotime("+2 second", strtotime(date("m/d/Y H:i:s")))),
                "KD_PELAKSANA" => 9999999,
                "KD_PENERIMA" => 9999999,
                "KD_INST" => $dataBaru->kodeInstalasi,
                "KD_JASA" => 1,
                "KD_POSISI" => 99,
                "JASA" => $penjualan2->harga,
                "HB" => $hb,
                "DIJAMIN" => $dj
            ]);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#listresep    the original method
     */
    public function actionListResepData(): string
    {
        [   "kodeRekamMedis" => $kodeRekamMedis,
            "idDepo" => $idDepo,
            "dariTanggal" => $tanggalAwal,
            "sampaiTanggal" => $tanggalAkhir,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode
            FROM db1.masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idDepo];
        $kodeDepo = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $kodeRekamMedis = $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : $kodeRekamMedis;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.no_resep      AS noResep,
                a.kodePenjualan AS kodePenjualan,
                a.kode_rm       AS kodeRekamMedis,
                a.nama_pasien   AS namaPasien,
                a.tglPenjualan  AS tanggalPenjualan,
                a.verifikasi    AS verifikasi,
                a.tglverifikasi AS tanggalVerifikasi,
                a.transfer      AS transfer,
                d.no_antrian    AS noAntrian,
                c.jenisResep    AS jenisResep,
                ''              AS totalCeil
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            INNER JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_antrian AS d ON d.kode_penjualan = a.kodePenjualan
            WHERE
                a.no_resep != ''
                AND (:kodeDepo = '' OR a.kode_depo = :kodeDepo)
                AND (:kodeRekamMedis = '' OR a.kode_rm = :kodeRekamMedis)
                AND a.tglPenjualan >= :tanggalAwal
                AND a.tglPenjualan <= :tanggalAkhir
            GROUP BY a.no_resep
            ORDER BY a.tglPenjualan DESC
        ";
        $params = [
            ":kodeDepo" => $kodeDepo,
            ":kodeRekamMedis" => $kodeRekamMedis,
            ":tanggalAwal" => $tanggalAwal,
            ":tanggalAkhir" => $tanggalAkhir,
        ];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep as $resep) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*)
                FROM db1.masterf_penjualan
                WHERE
                    no_resep = :noResep
                    AND id_racik != ''
                    AND id_racik != 0
                GROUP BY id_racik
            ";
            $params = [":noResep" => $resep->noResep];
            $jumlahPenjualan = $connection->createCommand($sql, $params)->queryScalar();
            $totalRacikan = $jumlahPenjualan * 500;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                    SUM(b.totalharga)                    AS totalJual
                FROM (
                    SELECT
                        CASE
                            WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                            WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 300
                            WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                            ELSE 0
                        END AS jasapelayanan,
                        a.totalharga,
                        a.no_resep
                    FROM (
                        SELECT
                            SUM(jlhPenjualan)         AS total,
                            id_racik                  AS id_racik,
                            SUM(jlhPenjualan * harga) AS totalharga,
                            mp.no_resep               AS no_resep,
                            jenisResep                AS jenisResep
                        FROM db1.masterf_penjualan AS mp
                        INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                        WHERE mp.no_resep = :noResep
                        GROUP BY kodeObat
                        ORDER BY kodeObat ASC
                    ) AS a
                ) AS b
                GROUP BY b.no_resep
            ";
            $params = [":totalRacikan" => $totalRacikan, ":noResep" => $resep->noResep];
            $penjualan = $connection->createCommand($sql, $params)->queryOne();

            $resep->totalCeil = ceil(($penjualan->totalJual + $penjualan->totalJasaPelayanan) / 100) * 100;
        }

        return json_encode(["total" => count($daftarResep), "daftarResep" => $daftarResep]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#etiket    the original method
     */
    public function actionEtiket(): void
    {
        [   "kodeResep" => $kodeResep,
            "noPrinter" => $noPrinter,
            "arah" => $arah,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT ip_address -- all are in use.
            FROM db1.printer_depo
            WHERE kode_depo = :kodeDepo
                AND no_printer = :noPrinter
                AND tipe_printer = :tipePrinter
            LIMIT 1
        ";
        $params = [":kodeDepo" => Yii::$app->userFatma->idDepo, ":noPrinter" => $noPrinter, ":tipePrinter" => "zebra"];
        $ip = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
                SELECT
                    PJN.no_resep          AS noResep,
                    PJN.kodeObat          AS kodeObat,
                    KAT.nama_sediaan      AS namaSediaan,
                    PJN.kode_rm           AS kodeRekamMedis,
                    TRIM(PJN.nama_pasien) AS namaPasien,
                    PSD.tanggal_lahir     AS tanggalLahir,
                    PJN.id_racik          AS idRacik,
                    PJN.signa1            AS signa1,
                    PJN.signa2            AS signa2,
                    PJN.signa3            AS signa3,
                    PJN.jlhPenjualan      AS kuantitas,
                    ''                    AS namaSediaan1,
                    ''                    AS namaSediaan2
                FROM db1.masterf_penjualan AS PJN
                INNER JOIN db1.masterf_katalog AS KAT ON PJN.kodeObat = KAT.kode
                LEFT JOIN db1.pasien_detail AS PSD ON PJN.kode_rm = PSD.no_rm
                WHERE
                    PJN.no_resep = :kodeResep
                    AND PJN.id_racik = ''
            UNION
                SELECT
                    PJN.no_resep          AS noResep,
                    PJN.kodeObat          AS kodeObat,
                    KAT.nama_sediaan      AS namaSediaan,
                    PJN.kode_rm           AS noRekamMedis,
                    TRIM(PJN.nama_pasien) AS namaPasien,
                    PSD.tanggal_lahir     AS tanggalLahir,
                    PJN.id_racik          AS idRacik,
                    PJN.signa1            AS signa1,
                    PJN.signa2            AS signa2,
                    PJN.signa3            AS signa3,
                    PJN.jlhPenjualan      AS kuantitas,
                    ''                    AS namaSediaan1,
                    ''                    AS namaSediaan2
                FROM db1.masterf_penjualan AS PJN
                INNER JOIN db1.masterf_katalog AS KAT ON PJN.kodeObat = KAT.kode
                LEFT JOIN db1.pasien_detail AS PSD ON PJN.kode_rm = PSD.no_rm
                WHERE
                    PJN.no_resep = :kodeResep
                    # AND PJN.signa1 != ''
                    AND PJN.id_racik != ''
                GROUP BY PJN.id_racik
        ";
        $params = [":kodeResep" => $kodeResep];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep as $key => $resep) {
            $namaSediaan = strtoupper($resep->namaSediaan);
            $offset = 25;
            $posTerakhir = 50;
            if ($offset >= strlen($namaSediaan)) {
                $resep->namaSediaan1 = strtoupper($namaSediaan);
                $resep->namaSediaan2 = "";
            } else {
                $breakIndex = strpos($namaSediaan, " ", $offset);
                $namaSediaan1 = substr($namaSediaan, 0, $breakIndex);
                if (strlen($namaSediaan1) > $offset) {
                    $namaSediaan1 = substr($namaSediaan1, 0, $offset);
                }
                $resep->namaSediaan1 = strtoupper($namaSediaan1);
                $resep->namaSediaan2 = strtoupper(substr($namaSediaan, $breakIndex, $posTerakhir));
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://$ip/printer.php",
            CURLOPT_USERAGENT => "Codular Sample cURL Request",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => [
                "resep" => json_encode($daftarResep),
                "direction" => $arah
            ]
        ]);
        curl_close($curl);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#cetakstruk3    the original method
     */
    public function actionCetakStruk3(): string
    {
        $noResep = Yii::$app->request->post("id");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.nama_pasien     AS namaPasien,
                b.jenis_kelamin   AS kelamin,
                b.alamat_jalan    AS alamatJalan,
                e.nm_kamar        AS namaKamar,
                e.pembayaran      AS pembayaran,
                e.tglResep1       AS tanggalResep1,
                b.no_rm           AS kodeRekamMedis,
                a.no_daftar       AS noPendaftaran,
                e.iter            AS iter,
                e.totaldiskon     AS totalDiskon,
                e.totalpembungkus AS totalPembungkus,   -- in use
                a.verifikasi      AS verifikasi,
                a.tglverifikasi   AS tanggalVerifikasi,
                e.KD_INST         AS kodeInstalasi,     -- in use
                a.no_resep        AS noResep            -- in use
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            LEFT JOIN db1.masterf_antrian AS f ON f.kode_penjualan = a.kodePenjualan
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                   AS kode,
                a.no_resep               AS noResep,
                a.no_penjualan           AS noPenjualan,
                a.diskon                 AS diskon,
                a.jasa                   AS jasa,
                a.kodePenjualan          AS kodePenjualan,
                a.kode_rm                AS kodeRekamMedis,
                a.no_daftar              AS noPendaftaran,
                a.nama_pasien            AS namaPasien,
                a.kodeObat               AS kodeObat,
                a.kodeObatdr             AS kodeObatDokter,
                a.nama_obatdr            AS namaObatDokter,
                a.urutan                 AS urutan,
                SUM(a.jlhPenjualan)      AS subtotalJumlah,
                a.jlhPenjualandr         AS jumlahPenjualanDokter,
                a.signa                  AS signa,
                a.hna                    AS hna,
                a.hp                     AS hp,
                a.harga                  AS harga,
                a.id_racik               AS idRacik,
                a.kode_racik             AS kodeRacik,
                a.nama_racik             AS namaRacik,
                a.no_racik               AS noRacik,
                a.ketjumlah              AS keteranganJumlah,
                a.keterangan_obat        AS keteranganObat,
                a.kode_depo              AS kodeDepo,
                a.ranap                  AS rawatInap,
                a.tglPenjualan           AS tanggalPenjualan,
                a.lunas                  AS lunas,
                a.verifikasi             AS verifikasi,
                a.transfer               AS transfer,
                a.resep                  AS resep,
                a.tglverifikasi          AS tanggalVerifikasi,
                a.tgltransfer            AS tanggalTransfer,
                a.operator               AS operator,
                a.tglbuat                AS tanggalPembuatan,
                a.signa1                 AS signa1,
                a.signa2                 AS signa2,
                a.signa3                 AS signa3,
                a.dokter_perobat         AS dokterPerObat,
                a.bayar                  AS bayar,
                a.tglbayar               AS tanggalPembayaran,
                a.checking_ketersediaan  AS cekKetersediaan,
                a.keteranganobat         AS keteranganObat,       -- duplicate upward
                a.kode_drperobat         AS kodeDokterPerObat,
                a.kode_operator          AS kodeOperator,
                a.kode_verifikasi        AS kodeVerifikasi,
                a.kode_transfer          AS kodeTransfer,
                d.nama                   AS namaSigna,
                z.hja_setting            AS hjaSetting,         -- in use
                a.jlhPenjualan           AS jumlahPenjualan     -- in use
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            INNER JOIN db1.relasif_hargaperolehan AS z ON z.id_katalog = b.kode
            WHERE
                a.no_resep = :noResep
                AND z.sts_hja = 1
            GROUP BY a.kodeObat
            ORDER BY a.kode ASC
        ";
        $params = [":noResep" => $noResep];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 18;

        foreach ($daftarObat as $i => $obat) {
            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "subtotal_harga" => ceil($obat->hjaSetting * $obat->jumlahPenjualan)
            ];

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_INST
            FROM db1.masterf_kode_inst
            WHERE kd_inst = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND id_racik != ''
                AND id_racik != 0
            GROUP BY id_racik
        ";
        $params = [":noResep" => $noResep];
        $jumlahPenjualan = $connection->createCommand($sql, $params)->queryScalar();
        $totalRacikan = $jumlahPenjualan * 500;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                SUM(b.totalharga)                    AS totalJual,
                FLOOR(b.totaldiskon)                 AS totalDiskon
            FROM (
                SELECT
                    CASE
                        WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 300
                        WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                        ELSE 0
                    END AS jasapelayanan,
                    a.totalharga,
                    a.no_resep,
                    a.totaldiskon
                FROM (
                    SELECT
                        SUM(jlhPenjualan)         AS total,
                        id_racik                  AS id_racik,
                        SUM(jlhPenjualan * harga) AS totalharga,
                        mp.no_resep               AS no_resep,
                        jenisResep                AS jenisResep,
                        mpd.totaldiskon           AS totaldiskon
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                    WHERE mp.no_resep = :noResep
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.no_resep
        ";
        $params = [":totalRacikan" => $totalRacikan, ":noResep" => $noResep];
        $penjualan = $connection->createCommand($sql, $params)->queryOne();

        $totalCeil = ceil(($penjualan->totalJual - $penjualan->totalDiskon) / 100) * 100;
        $penjualan->totalJasaPelayanan += $totalCeil - ($penjualan->totalJual - $penjualan->totalDiskon);
        $grandTotal = $penjualan->totalJual - $penjualan->totalDiskon + $pasien->totalPembungkus + $penjualan->totalJasaPelayanan;

        $view = new CetakStrukNew(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaInstalasi: $namaInstalasi,
            namaDepo:      Yii::$app->userFatma->namaDepo,
            daftarObat:    $daftarObat,
            penjualan:     $penjualan,
            grandTotal:    $grandTotal,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#testbridge_cekkeluar    the original method
     * @see http://127.0.0.1/bridging/Bridging.php#getDataPasienKeluar    the original method
     */
    private function actionTestBridgeCekKeluar(string $id): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT TDFT_KELUAR.* 
            FROM db_his_live.TDFT_PENDAFTARAN, TDFT_KELUAR
            WHERE
                TDFT_PENDAFTARAN.NO_PENDAFTARAN = TDFT_KELUAR.NO_PENDAFTARAN
                AND TDFT_KELUAR.NO_PENDAFTARAN = :noPendaftaran
                AND TDFT_KELUAR.KD_CARAKELUAR != '05'
                AND TDFT_PENDAFTARAN.KD_INST IN ('02', '04', '05', '06', '07', 19)
        ";
        $params = [":noPendaftaran" => $id];
        $result = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#bridging4    the original method
     */
    private function actionBridging4(array $data): string
    {
        $url = "http://202.137.25.13/bridging/Bridging_latihan.php";
        $dataString = "";
        foreach ($data as $key => $value) {
            $dataString .= $key . '=' . $value . '&';
        }

        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($post, CURLOPT_HEADER, 0);
        $result = curl_exec($post);
        curl_close($post);

        return $result;
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#getCounter2 the original method
     */
    private function getCounter2(string $id): int
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.counterid
            WHERE counter LIKE :counter
        ";
        $params = [":counter" => "$id%"];
        return (int) $connection->createCommand($sql, $params)->queryScalar() ?: 0;
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#addCounter2 the original method
     */
    private function addCounter2(string $id): string
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.counterid
            SET counter = :counter
        ";
        $params = [":counter" => $id];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();

        return $berhasilTambah ? "OK" : "NO";
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#cetakstruk    the original method
     */
    public function actionViewStrukData(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.nama_pasien     AS namaPasien,
                b.jenis_kelamin   AS kelamin,
                b.alamat_jalan    AS alamatJalan,
                e.nm_kamar        AS namaKamar,
                e.pembayaran      AS pembayaran,
                e.tglResep1       AS tanggalResep1,
                e.tglResep2       AS tanggalResep2,
                b.no_rm           AS kodeRekamMedis,
                e.iter            AS iter,
                e.totalpembungkus AS totalPembungkus, -- in use
                e.keterangan      AS keterangan,
                a.verifikasi      AS verifikasi,      -- in use
                a.transfer        AS transfer,        -- in use
                e.jenisResep      AS jenisResep,
                e.KD_INST         AS kodeInstalasi,   -- in use
                a.no_resep        AS noResep,         -- in use
                a.no_daftar       AS noPendaftaran
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            LEFT JOIN db1.masterf_antrian AS f ON f.kode_penjualan = a.kodePenjualan
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode_racik        AS kodeRacik,        -- in use
                a.nama_racik        AS namaRacik,
                a.no_racik          AS noRacik,
                b.nama_barang       AS namaBarang,
                a.ketjumlah         AS keteranganJumlah,
                bb.hja_setting      AS hjaSetting,       -- in use
                b.nama_barang       AS namaBarang,
                d.nama              AS namaSigna,
                SUM(a.jlhPenjualan) AS jumlahPenjualan   -- in use
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            LEFT JOIN db1.relasif_hargaperolehan AS bb ON bb.id_katalog = b.kode
            WHERE
                a.no_resep = :noResep
                AND bb.sts_hja != 0
                AND bb.sts_hjapb != 0
            GROUP BY b.kode
            ORDER BY a.kode ASC
        ";
        $params = [":noResep", $noResep];
        $daftarObat1 = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_INST
            FROM db1.masterf_kode_inst
            WHERE kd_inst = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = (string) $connection->createCommand($sql, $params)->queryScalar();

        $kodeRacik = "";
        $noRacik = 1;
        $noObat = 1;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT verifikasi
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND verifikasi != ''
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $verifikasi = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT transfer
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND transfer != ''
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $transfer = $connection->createCommand($sql, $params)->queryScalar();

        $pasien->verifikasi = $verifikasi ?: $pasien->verifikasi;
        $pasien->transfer = $transfer ?: $pasien->transfer;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND id_racik != ''
                AND id_racik != 0
            GROUP BY id_racik
        ";
        $params = [":noResep" => $noResep];
        $jumlahPenjualan = $connection->createCommand($sql, $params)->queryScalar();
        $totalRacikan = $jumlahPenjualan * 500;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                SUM(b.totalharga)                    AS totalJual,
                FLOOR(b.totaldiskon)                 AS totalDiskon
            FROM (
                SELECT
                    CASE
                        WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 300
                        WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                        ELSE 0
                    END AS jasapelayanan,
                    a.totalharga,
                    a.no_resep,
                    a.totaldiskon
                FROM (
                    SELECT
                        SUM(jlhPenjualan)         AS total,
                        id_racik                  AS id_racik,
                        SUM(jlhPenjualan * harga) AS totalharga,
                        mp.no_resep               AS no_resep,
                        jenisResep                AS jenisResep,
                        mpd.totaldiskon           AS totaldiskon
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                    WHERE mp.no_resep = :noResep
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.no_resep
        ";
        $params = [":totalRacikan" => $totalRacikan, ":noResep" => $noResep];
        $penjualan = $connection->createCommand($sql, $params)->queryOne();

        $total = $penjualan->totalJual;
        $totalCeil = ceil(($penjualan->totalJual - $penjualan->totalDiskon) / 100) * 100;
        $penjualan->totalJasaPelayanan += $totalCeil - ($penjualan->totalJual - $penjualan->totalDiskon);
        $grandTotal = $total - $penjualan->totalDiskon + $pasien->totalPembungkus + $penjualan->totalJasaPelayanan;

        $daftarObat2 = [];

        foreach ($daftarObat1 as $j => $obat) {
            if ($obat->kodeRacik) {
                if ($kodeRacik != $obat->kodeRacik) {
                    $noRacik = 1;
                    $daftarObat2[$j]["noobat"] = $noObat;
                    $kodeRacik = $obat->kodeRacik;
                    $noObat++;
                }
                $daftarObat2[$j]["noracik"] = $noRacik;
                $noRacik++;

            } else {
                $daftarObat2[$j]["noobat"] = $noObat;
                $noObat++;
            }

            $total += $obat->jumlahPenjualan * $obat->hjaSetting;
        }

        return json_encode([
            "pasien"        => $pasien,
            "namaInstalasi" => $namaInstalasi,
            "namaDepo"      => Yii::$app->userFatma->namaDepo,
            "daftarObat1"   => $daftarObat1,
            "daftarObat2"   => $daftarObat2,
            "total"         => $total,
            "penjualan"     => $penjualan,
            "grandTotal"    => $grandTotal,
            "kodePenjualan" => $noResep,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodrirna.php#bridging5    the original method
     */
    private function actionBridging5(array $data): array
    {
        $url = "http://202.137.25.13/bridging/Bridging_latihan.php";
        $dataString = "";
        foreach ($data as $key => $value) {
            $dataString .= $key . '=' . $value . '&';
        }

        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($post, CURLOPT_HEADER, 0);
        $result = curl_exec($post);
        curl_close($post);

        return json_decode($result);
    }
}
