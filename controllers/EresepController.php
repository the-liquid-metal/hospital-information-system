<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\libs\LowEnd\components\DateTimeException;
use tlm\his\FatmaPharmacy\views\Eresep\Antrian2;
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
class EresepController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $noResep = Yii::$app->request->post("id");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kodePenjualan                                 AS kodePenjualan,
                b.TGL_DAFTAR                                    AS tanggalPendaftaran,
                e.JNS_CARABAYAR                                 AS jenisCaraBayar,
                e.KD_BAYAR                                      AS kodeBayar,
                e.KD_INST                                       AS kodeInstalasi,
                e.KD_POLI                                       AS kodePoli,
                e.KD_JENIS_CARABAYAR                            AS kodeJenisCaraBayar,
                e.KD_RRAWAT                                     AS kodeRuangRawat,
                a.kode_rm                                       AS kodeRekamMedis,
                d.no_antrian                                    AS noAntrian,
                a.nama_pasien                                   AS namaPasien,
                b.jenis_kelamin                                 AS kelamin,
                b.tanggal_lahir                                 AS tanggalLahir,
                b.alamat_jalan                                  AS alamat,
                b.no_telpon                                     AS noTelefon,
                e.tglResep1                                     AS tanggalAwalResep,
                e.tglResep2                                     AS tanggalAkhirResep,
                e.jenisResep                                    AS jenisResep,
                e.dokter                                        AS dokter,
                e.namaInstansi                                  AS namaInstalasi,
                e.namaPoli                                      AS namaPoli,
                e.iter2                                         AS iter2,
                e.iter                                          AS iter1,
                e.total                                         AS grandTotal,
                e.jasapelayanan                                 AS jasaPelayanan,
                a.no_resep                                      AS noResep,
                e.pembayaran                                    AS pembayaran,
                IF(a.no_daftar != '', a.no_daftar, b.no_daftar) AS noPendaftaran,
                NULL                                            AS daftarObat,
                NULL                                            AS daftarRacik
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.user AS c ON c.id = e.dokter
            LEFT JOIN db1.masterf_antrian AS d ON d.kode_penjualan = a.kodePenjualan
            WHERE a.no_resep = :noResep
            ORDER BY a.no_resep DESC
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $resep = $connection->createCommand($sql, $params)->queryOne();
        $resep->noDaftar1 ??= $resep->noDaftar2;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                b.nama_barang  AS namaObat,
                a.kodeObat     AS kodeObat,
                a.jlhPenjualan AS kuantitas,
                a.id_racik     AS idRacik,
                a.signa1       AS namaSigna1,
                a.signa2       AS namaSigna2,
                a.signa3       AS namaSigna3,
                c.nama_kemasan AS namaKemasan,
                a.harga        AS hargaJual
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            WHERE
                a.no_resep = :noResep
                AND a.kode_racik = ''
            ORDER BY a.no_resep DESC
        ";
        $params = [":noResep" => $noResep];
        $resep->daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id_racik     AS idRacik,
                a.nama_racik   AS namaRacik,
                a.signa        AS signa,
                a.no_racik     AS noRacik,
                a.diskon       AS diskon,
                b.nama_barang  AS namaBarang,
                a.kodeObat     AS kodeObat,
                a.ketjumlah    AS keteranganJumlah,
                a.jlhPenjualan AS jumlahPenjualan,
                c.nama_kemasan AS namaKemasan,
                d.nama         AS namaSigna
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            WHERE
                kode_racik != ''
                AND a.no_resep = :noResep
            ORDER BY a.kode ASC
        ";
        $params = [":noResep" => $noResep];
        $resep->daftarRacik = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($resep);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#cetak    the original method
     */
    public function actionCetak(): string
    {
        [   "id" => $id,
            "kodePenjualanSebelumnya" => $kodePenjualanSebelumnya,
            "kodeRuangRawat" => $kodeRuangRawat,
            "editResep" => $editResep,
            "noResep" => $noResep,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noPendaftaran" => $noPendaftaran,
            "tanggalPendaftaran" => $tanggalPendaftaran,
            "namaPasien" => $namaPasien,
            "kelamin" => $kelamin,
            "tanggalLahir" => $tanggalLahir,
            "alamat" => $alamat,
            "noTelefon" => $noTelefon,
            "tanggalAwalResep" => $tanggalAwalResep,
            "tanggalAkhirResep" => $tanggalAkhirResep,
            "kodeJenisResep" => $kodeJenisResep,
            "dokter" => $dokter,
            "pembayaran" => $pPembayaran,
            "jenisCaraBayar" => $jenisCaraBayar,
            "kodeBayar" => $kodeBayar,
            "kodeJenisCaraBayar" => $kodeJenisCaraBayar,
            "namaInstalasi" => $namaInstalasi,
            "kodeInstalasi" => $kodeInstalasi,
            "namaPoli" => $namaPoli,
            "verifikasi" => $verifikasi,
            "verified" => $verified,
            "namaRuangRawat" => $namaRuangRawat,
            "noUrut" => $noUrut,

            "kodeObatAwal" => $daftarKodeObatAwal,
            "kodeObat" => $daftarKodeObat,
            "kuantitas" => $daftarKuantitas,
            "idRacik" => $daftarIdRacik,
            "namaSigna1" => $daftarNamaSigna1,
            "namaSigna2" => $daftarNamaSigna2,
            "namaSigna3" => $daftarNamaSigna3,
            "hargaJual" => $daftarHargaJual,
            "diskonObat" => $daftarDiskonObat,

            "diskonRacik" => $daftarDiskonRacik,
            "kodeSigna" => $daftarKodeSigna,
            "kodeSignaRacik" => $daftarKodeSignaRacik,
            "kodePembungkus" => $daftarKodePembungkus,
            "kuantitasPembungkus" => $daftarJumlahPembungkus,
            "hargaPembungkus" => $daftarHargaPembungkus,
            "noRacik" => $daftarNoRacik,
            "namaRacik" => $daftarNamaRacikan,

            // not exist in form
            // TODO: php: uncategorized: convert $_POST["xxx-$key"] to $_POST["xxx"][$key]
            // ["kode_pembungkus-$key"]
            // ["hargapembungkus-$key"]
            // ["qtypembungkus-$key"]
            // ["kode_obat-$key"]
            // ["hargasatuan-$key"]
            // ["qty-$key"]
            // ["ketjumlah-$key"]
        ] = Yii::$app->request->post();
        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");

        $idDepo = Yii::$app->userFatma->idDepo;
        $namaUser = Yii::$app->userFatma->nama;
        $connection = Yii::$app->dbFatma;

        if (!$verified) {
            $myverif = "";
            if ($verifikasi) {
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
                $fresep = "R" . Yii::$app->userFatma->kodeSubUnitDepo . date("Ymd");
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
                    WHERE no_resep LIKE :noResep
                    ORDER BY no_resep DESC
                    LIMIT 1
                ";
                $params = [":noResep" => "$fresep%"];
                $resep2 = $connection->createCommand($sql, $params)->queryOne();
                $this->addCounter($resep2->noResep);

                $resep2 = $this->viewCounter($fresep);

                if ($resep2) {
                    $urutan = substr($resep2, - 4);
                    $urutan = sprintf("%04d", ++$urutan);
                    $resep = $fresep . $urutan;
                } else {
                    $resep = $fresep . "0001";
                }
                $noResep = $resep;
            }
            $this->addCounter($noResep);

            $hargaPembungkus = 0;
            $diskonObat = 0;
            $diskonRacik = 0;
            $jasaHargaObat22 = 0;
            $daftarPelayanan = [];
            $daftarTagihan = [];
            $daftarTagihanRacik = [];

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
                    SELECT TRUE
                    FROM db1.masterf_penjualandetail
                    WHERE no_resep = :noResep
                    LIMIT 1
                ";
                $params = [":noResep" => $noResep];
                $cekDetailPenjualan = $connection->createCommand($sql, $params)->queryScalar();

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

                if (!$cekDetailPenjualan) {
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
                        ":tanggalDaftar" => date("m/d/Y H:i:s", strtotime($tanggalPendaftaran)),
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
                        ":keterangan" => $noUrut,
                    ];
                    $connection->createCommand($sql, $params)->execute();
                }

                $daftarTemuObat = [];
                $daftarJasaObatRacik = [];

                foreach ($daftarKodeObat ?? [] as $key => $kodeObat) {
                    $diskon = (float) $daftarDiskonObat[$key];
                    $cekMinus = ($daftarKuantitas[$key] < 0) ? 1 : 0;
                    $jumlah = abs($daftarKuantitas[$key]);
                    $harga1 = $daftarHargaJual[$key];
                    $signa = $daftarKodeSigna[$key];

                    $diskonHarga = $harga1 * $jumlah * $diskon / 100;
                    $diskonObat += $diskonHarga;

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            B.hja_setting AS hjaSetting,
                            B.hna_item    AS hnaItem,
                            B.hp_item     AS hpItem
                        FROM db1.masterf_katalog AS A
                        LEFT JOIN db1.relasif_hargaperolehan AS B ON B.id_katalog = A.kode
                        WHERE
                            a.kode = :kode
                            AND B.sts_hja != 0
                            AND B.sts_hjapb != 0
                        LIMIT 1
                    ";
                    $params = [":kode" => $kodeObat];
                    $harga2 = $connection->createCommand($sql, $params)->queryOne();

                    $daftarTagihan[$key] = $harga2->hjaSetting * $daftarKuantitas[$key];
                    $daftarPelayanan[$key] = 0;

                    $urutanObat = $daftarKodeObatAwal[$key] ? ", urutan = '" . ($key + 1) . "'" : "";

                    if (!$kodeObat) continue;
                    $jumlahStok = ($cekMinus == 1) ? -$jumlah : $jumlah;

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.masterf_penjualan
                        WHERE
                            no_resep = :noResep
                            AND kodeObat = :kodeObat
                        LIMIT 1
                    ";
                    $params = [":noResep" => $noResep, ":kodeObat" => $kodeObat];
                    $cekPenjualan = $connection->createCommand($sql, $params)->queryScalar();

                    if ($cekPenjualan) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.masterf_penjualan
                            SET jlhPenjualan = jlhPenjualan + :jumlahStok
                            WHERE
                                no_resep = :noResep
                                AND kodeObat = :kodeObat
                        ";
                        $params = [":jumlahStok" => $jumlahStok, ":noResep" => $noResep, ":kodeObat" => $kodeObat];

                    } else {
                        // TODO: sql: ambiguous column name:
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
                            ":namaPasien" => $namaPasien,
                            ":idRacik" => $daftarIdRacik[$key],
                            ":signa1" => $daftarNamaSigna1[$key],
                            ":signa2" => $daftarNamaSigna2[$key],
                            ":signa3" => $daftarNamaSigna3[$key],
                            ":harga" => $harga1,
                            ":jumlahPenjualan" => $jumlahStok,
                            ":signa" => $signa,
                            ":kodePenjualan" => $kodePenjualan,
                            ":kodeObat" => $kodeObat,
                            ":tanggalPenjualan" => $todayValSystem,
                            ":kodeDepo" => $idDepo,
                            ":diskon" => $diskon,
                            ":operator" => $namaUser,
                            ":noResep" => $noResep,
                            ":hna" => $harga2->hnaItem,
                            ":hp" => $harga2->hpItem,
                        ];
                    }
                    $connection->createCommand($sql, $params)->execute();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa1'
                            AND signa_name = :namaSigna
                        LIMIT 1
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna1[$key]];
                    $cekSigna1 = $connection->createCommand($sql, $params)->queryScalar();

                    if (!$cekSigna1) {
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
                        SELECT TRUE
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa2'
                            AND signa_name = :namaSigna
                        LIMIT 1
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna2[$key]];
                    $cekSigna2 = $connection->createCommand($sql, $params)->queryScalar();

                    if (!$cekSigna2) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_listsigna
                            SET
                                signa_name = :namaSigna,
                                kategori_signa = 'signa2'
                        ";
                        $params = [":namaSigna" => $daftarNamaSigna1[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa3'
                            AND signa_name = :namaSigna
                        LIMIT 1
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna1[$key]];
                    $cekSigna3 = $connection->createCommand($sql, $params)->queryScalar();

                    if (!$cekSigna3) {
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

                    if ($verifikasi) {
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
                        $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
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
                        $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                        $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar();

                        if ($cekMinus == 1) {
                            $jumlahMasuk = $jumlah;
                            $jumlahKeluar = 0;
                            $jumlahTersedia = $jumlahTersediaBefore + $jumlah;
                            $stokFaktor = $jumlah;

                        } else {
                            $jumlahMasuk = 0;
                            $jumlahKeluar = $jumlah;
                            $jumlahTersedia = $jumlahTersediaBefore - $jumlah;
                            $stokFaktor = - $jumlah;
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
                                jumlah_keluar = :jumlahKeluar,
                                jumlah_tersedia = :jumlahTersedia,
                                status = 1,
                                keterangan = :keterangan
                        ";
                        $params = [
                            ":idDepo" => $idDepo,
                            ":kodeRef" => $kodeRekamMedis,
                            ":kodeStokopname" => $kodeStokOpname,
                            ":tanggalTersedia" => $nowValSystem,
                            ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                            ":idKatalog" => $kodeObat,
                            ":hargaItem" => $harga1,
                            ":hargaPerolehan" => $harga2->hpItem,
                            ":jumlahMasuk" => $jumlahMasuk,
                            ":jumlahKeluar" => $jumlahKeluar,
                            ":jumlahTersedia" => $jumlahTersedia,
                            ":keterangan" => "Pemakaian pasien $namaPasien"
                        ];
                        $connection->createCommand($sql, $params)->execute();

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.transaksif_stokkatalog
                            SET
                                jumlah_stokfisik = jumlah_stokfisik + :stokFaktor,
                                jumlah_stokadm = jumlah_stokadm + :stokFaktor
                            WHERE
                                id_katalog = :idKatalog
                                AND id_depo = :idDepo
                        ";
                        $params = [":stokFaktor" => $stokFaktor, ":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                        $connection->createCommand($sql, $params)->execute();

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.masterf_ketersediaan
                            SET jlhTersedia = jlhTersedia - :minJumlah
                            WHERE
                                kodeDepo = :kodeDepo
                                AND kodeObat = :kodeObat
                        ";
                        $params = [":minJumlah" => $jumlah, ":kodeDepo" => $idDepo, ":kodeObat" => $kodeObat];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    if (!$daftarIdRacik[$key] && $kodeJenisResep != "00" && !$daftarTemuObat[$kodeObat]) {
                        $jasaHargaObat22 += 300;
                        $daftarTemuObat[$kodeObat] = "exist";

                    } elseif (!$daftarJasaObatRacik[$daftarIdRacik[$key]] && $kodeJenisResep != "00") {
                        $daftarJasaObatRacik[$daftarIdRacik[$key]] = "exist";
                        $jasaHargaObat22 += 500;
                    }
                }

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
                    $connection->createCommand($sql, $params)->execute();
                    $hargaPembungkus += $daftarJumlahPembungkus[$key4] * $daftarHargaPembungkus[$key4];
                }

                foreach ($daftarNamaRacikan ?? [] as $key => $namaRacikan) {
                    $racik = date("ymd") . rand(1000, 9999);
                    $diskon = $daftarDiskonRacik[$key];

                    foreach ($_POST["kode_pembungkus-$key"] as $key4 => $kodePembungkus) {
                        $pHargaPembungkus = $_POST["hargapembungkus-$key"][$key4];
                        $pJumlah = $_POST["qtypembungkus-$key"][$key4];
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
                            ":jumlah" => $pJumlah,
                        ];
                        $connection->createCommand($sql, $params)->execute();

                        $hargaPembungkus += $pJumlah * $pHargaPembungkus;
                    }

                    foreach ($_POST["kode_obat-$key"] as $key2 => $kodeObat) {
                        $pHargaSatuan = $_POST["hargasatuan-$key"][$key2];
                        $pJumlah = $_POST["qty-$key"][$key2] ?: 1;
                        $pKeteranganJumlah = $_POST["ketjumlah-$key"][$key2];

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

                        $daftarTagihanRacik[$key2] = $hargaJual * $pJumlah;
                        $diskonHarga = $pHargaSatuan * $pJumlah * $diskon / 100;
                        $diskonRacik += $diskonHarga;

                        if (!$kodeObat) continue;
                        // TODO: sql: ambiguous column name:
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
                            ":operator" => $namaUser,
                            ":noRacik" => $daftarNoRacik[$key],
                            ":namaPasien" => $namaPasien,
                            ":harga" => $pHargaSatuan,
                            ":jumlahPenjualan" => $pJumlah,
                            ":keteranganJumlah" => $pKeteranganJumlah,
                            ":signa" => $daftarKodeSignaRacik[$key],
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
                    }
                }
            } else {
                $kodePenjualan = $id;
            }

            $tagihanObat = 0;
            foreach ($daftarPelayanan as $key => $unused) {
                $tagihanObat += $daftarTagihan[$key];
            }

            $tagihanRacik = 0;
            foreach ($daftarTagihanRacik as $tRacik) {
                $tagihanRacik += $tRacik;
            }

            $totalAwal = $tagihanObat + $tagihanRacik;
            $totalDiskon = $diskonObat + $diskonRacik;
            $pembulatan = ceil(($totalAwal + $jasaHargaObat22 + $hargaPembungkus - $totalDiskon) / 100) * 100;
            $totalJasaPelayanan = $pembulatan - $totalAwal - $hargaPembungkus + $totalDiskon;

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
                    SET kode_penjualan = :kodePenjualan
                    WHERE kode_penjualan = :kodePenjualanSebelumnya
                ";
                $params = [":kodePenjualan" => $kodePenjualan, ":kodePenjualanSebelumnya" => $kodePenjualanSebelumnya];
                $connection->createCommand($sql, $params)->execute();
            }

        } else {
            $tagihan22 = 0;
            foreach ($daftarKodeObat as $key => $kodeObat) {
                $minJumlah = abs($daftarKuantitas[$key]);
                $jumlah = $daftarKuantitas[$key];
                $jumlah33 = $daftarKuantitas[$key];

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
                    WHERE kodeObat = :kodeObat
                        AND (jlhPenjualan = :jumlah OR jlhPenjualan < 0)
                        AND no_resep = :noResep
                ";
                $params = [":kodeObat" => $kodeObat, ":jumlah" => $jumlah, ":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $harga = $daftarHargaJual[$key];
                $signa = $daftarKodeSigna[$key];

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        B.hja_setting AS hjaSetting,
                        B.hp_item     AS hpItem
                    FROM db1.masterf_katalog AS A
                    LEFT JOIN db1.relasif_hargaperolehan AS B ON b.id_katalog = a.kode
                    WHERE
                        a.kode = :kode
                        AND (b.sts_hja != 0 OR b.sts_hjapb != 0)
                    LIMIT 1
                ";
                $params = [":kode" => $kodeObat];
                $harga2 = $connection->createCommand($sql, $params)->queryOne();

                $daftarTagihan[$key] = $harga2->hjaSetting * $minJumlah;
                $tagihan22 += $harga2->hjaSetting * $minJumlah;
                $daftarPelayanan[$key] = 0;

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
                    ":jumlahPenjualan" => $jumlah33,
                    ":signa" => $signa,
                    ":kodePenjualan" => $editResep,
                    ":kodeObat" => $kodeObat,
                    ":tanggalPenjualan" => $todayValSystem,
                    ":kodeDepo" => Yii::$app->userFatma->kodeDepo,
                    ":operator" => $namaUser,
                    ":noResep" => $noResep,
                ];
                $connection->createCommand($sql, $params)->execute();

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
                $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
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
                $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT TRUE
                    FROM db1.masterf_penjualan
                    WHERE
                        no_resep = :noResep
                        AND kodeObat = :kodeObat
                        AND jlhPenjualan = :jumlahPenjualan
                    LIMIT 1
                ";
                $params = [":noResep" => $noResep, ":kodeObat" => $kodeObat, ":jumlahPenjualan" => -$jumlah33];
                $cekPenjualan2 = $connection->createCommand($sql, $params)->queryScalar();

                if ($cekPenjualan2 && !$daftarIdRacik[$key]) {
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
                    ":idKatalog" => $kodeObat,
                    ":hargaItem" => $harga2->hjaSetting,
                    ":hargaPerolehan" => $harga2->hpItem,
                    ":jumlahMasuk" => $minJumlah,
                    ":jumlahTersedia" => $jumlahTersediaBefore + $minJumlah,
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
            $params = [":totalRetur" => $tagihan22, ":noResep" => $noResep];
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#antrian    the original method
     */
    public function actionViewAntrianData(): string
    {
        ["id" => $noResep, "antrian" => $antrian] = Yii::$app->request->post();
        $todayValUser = Yii::$app->dateTime->todayVal("user");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT no_antrian
            FROM db1.masterf_antrian
            WHERE
                tanggal = :tanggal
                AND no_resep = :noResep
            LIMIT 1
        ";
        $params = [":tanggal" => $todayValUser, ":noResep" => $noResep];
        $noAntrian = $connection->createCommand($sql, $params)->queryScalar();

        if (!$noAntrian) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.masterf_antrian
                SET
                    no_antrian = :noAntrian,
                    no_resep = :noResep,
                    tanggal = :tanggal
            ";
            $params = [":noAntrian" => $antrian, ":noResep" => $noResep, ":tanggal" => $todayValUser];
            $connection->createCommand($sql, $params)->execute();

        } else {
            $antrian = $noAntrian;
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_resep      AS noResep,
                a.nama_pasien   AS namaPasien,
                b.jenis_kelamin AS kelamin,
                a.no_daftar     AS noPendaftaran,
                b.no_rm         AS kodeRekamMedis,
                c.iter          AS iter,
                c.tglResep1     AS tanggalResep1,
                c.iter          AS iter,
                c.keterangan    AS keterangan,
                c.dokter        AS dokter
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.jlhPenjualan    AS jumlahPenjualan,
                b.nama_barang     AS namaBarang,
                a.signa1          AS signa1,
                a.signa2          AS signa2,
                a.signa3          AS signa3,
                a.keterangan_obat AS keteranganObat
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            WHERE
                a.no_resep = :noResep
                AND kode_racik = ''
        ";
        $params = [":noResep" => $noResep];
        $daftarObat1 = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode_racik      AS kodeRacik,
                a.no_racik        AS noRacik,
                a.nama_racik      AS namaRacik,
                a.signa1          AS signa1,
                a.signa2          AS signa2,
                a.signa3          AS signa3,
                a.keterangan_obat AS keteranganObat,
                a.ketjumlah       AS keteranganJumlah,
                b.nama_barang     AS namaBarang
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            WHERE
                a.no_resep = :noResep
                AND a.kode_racik != ''
            ORDER BY kode_racik
        ";
        $params = [":noResep" => $noResep];
        $daftarObat2 = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "antrian"       => $antrian,
            "pasien"        => $pasien,
            "daftarObat1"   => $daftarObat1,
            "daftarObat2"   => $daftarObat2,
            "kodePenjualan" => $noResep,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#antrian2    the original method
     */
    public function actionAntrian2(): string
    {
        $noResep = Yii::$app->request->post("id");
        $todayValUser = Yii::$app->dateTime->todayVal("user");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT no_antrian
            FROM db1.masterf_antrian
            WHERE
                tanggal = :tanggal
                AND kode_penjualan = :kodePenjualan
            LIMIT 1
        ";
        $params = [":tanggal" => $todayValUser, ":kodePenjualan" => $noResep];
        $noAntrian = (int) $connection->createCommand($sql, $params)->queryScalar();

        if (!$noAntrian) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT no_antrian
                FROM db1.masterf_antrian
                WHERE tanggal = :tanggal
                ORDER BY no_antrian DESC
                LIMIT 1
            ";
            $params = [":tanggal" => $todayValUser];
            $noAntrian = (int) $connection->createCommand($sql, $params)->queryScalar() + 1;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.masterf_antrian
                SET
                    no_antrian = :noAntrian,
                    kode_penjualan = :kodePenjualan,
                    tanggal = :tanggal
            ";
            $params = [":noAntrian" => $noAntrian, ":kodePenjualan" => $noResep, ":tanggal" => $todayValUser];
            $connection->createCommand($sql, $params)->execute();
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_resep      AS noResep,
                b.no_rm         AS kodeRekamMedis,
                a.nama_pasien   AS namaPasien,
                b.jenis_kelamin AS kelamin,
                a.no_daftar     AS noPendaftaran,
                c.tglResep1     AS tanggalResep1,
                c.iter          AS iter,
                c.keterangan    AS keterangan,
                c.dokter        AS dokter
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.jlhPenjualan    AS jumlahPenjualan,
                b.nama_barang     AS namaBarang,
                a.signa1          AS signa1,
                a.signa2          AS signa2,
                a.signa3          AS signa3,
                a.keterangan_obat AS keteranganObat
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            WHERE
                a.no_resep = :noResep
                AND kode_racik = ''
        ";
        $params = [":noResep" => $noResep];
        $daftarObat1 = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode_racik      AS kodeRacik,
                a.keterangan_obat AS keteranganObat,
                a.nama_racik      AS namaRacik,
                a.no_racik        AS noRacik,
                b.nama_barang     AS namaBarang,
                a.ketjumlah       AS keteranganJumlah,
                a.signa           AS signa
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            WHERE
                a.no_resep = :noResep
                AND a.kode_racik != ''
            ORDER BY kode_racik
        ";
        $params = [":noResep" => $noResep];
        $daftarObat2 = $connection->createCommand($sql, $params)->queryAll();

        $view = new Antrian2(
            noAntrian:   $noAntrian,
            pasien:      $pasien,
            daftarObat1: $daftarObat1,
            daftarObat2: $daftarObat2,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#viewCounter the original method
     */
    private function viewCounter(string $id): string
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT counter
            FROM db1.counterid
            WHERE counter LIKE :counter
            ORDER BY counter DESC
            LIMIT 1
        ";
        $params = [":counter" => "$id%"];
        return $connection->createCommand($sql, $params)->queryScalar() ?: "";
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresep.php#addCounter the original method
     */
    private function addCounter(string $id): void
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.counterid
            SET counter = :counter
        ";
        $params = [":counter" => $id];
        $connection->createCommand($sql, $params)->execute();
    }
}
