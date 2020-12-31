<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\EresepDepo\{Antrian2, CetakStrukLq, CetakStrukNew};
use stdClass;
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
class EresepDepoController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#ceklisresep    the original method
     */
    public function actionCeklisResep(): void
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.relasif_ceklistresep
            SET
                no_resep = :noResep,
                jelas = :jelas,
                obat = :obat,
                dosis = :dosis,
                waktu = :waktu,
                rute = :rute,
                pasien = :pasien,
                duplikasi = :duplikasi,
                interaksi = :interaksi
        ";
        $params = [
            ":noResep" => $_POST["no_resep"],
            ":jelas" => $_POST["jelas"],
            ":obat" => $_POST["obat"],
            ":dosis" => $_POST["dosis"],
            ":waktu" => $_POST["waktu"],
            ":rute" => $_POST["rute"],
            ":pasien" => $_POST["pasien"],
            ":duplikasi" => $_POST["duplikasi"],
            ":interaksi" => $_POST["interaksi"],
        ];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kodePenjualan                                 AS kodePenjualanSebelumnya,
                b.TGL_DAFTAR                                    AS tanggalPendaftaran,
                e.JNS_CARABAYAR                                 AS jenisCaraBayar,
                e.KD_BAYAR                                      AS kodePembayaran,
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
                e.jenisResep                                    AS kodeJenisResep,
                e.dokter                                        AS dokter,
                e.namaInstansi                                  AS namaInstalasi,
                e.namaPoli                                      AS namaPoli,
                e.iter2                                         AS iter2,
                e.iter                                          AS iter1,
                e.totaldiskon                                   AS totalDiskon,
                e.jasapelayanan                                 AS jasaPelayanan,
                e.total                                         AS grandTotal,
                a.no_resep                                      AS noResep,
                e.pembayaran                                    AS pembayaran,
                IF(a.no_daftar != '', a.no_daftar, b.no_daftar) AS noPendaftaran,
                ''                                              AS riwayatAlergi,
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

        /* note: this query is truely use "sirs" db (defined by load database statement) */
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT b.rwyt_alergi -- all are in use.
            FROM sirs.tdft_pendaftaran AS a
            LEFT JOIN sirs.tdet_kaj_dokter AS b ON b.no_rm = a.norm
            WHERE a.nopendaftaran = :noPendaftaran
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $resep->noDaftar1];
        $resep->riwayatAlergi = $connection->createCommand($sql, $params)->queryScalar();

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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#cetak    the original method
     */
    public function actionCetak(): string
    {
        [
            "kodeRuangRawat" => $kodeRuangRawat,
            "kodePenjualanSebelumnya" => $kodePenjualanSebelumnya,
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
            "namaInstalasi" => $namaInstalasi,
            "kodeInstalasi" => $kodeInstalasi,
            "pembayaran" => $pPembayaran,
            "kodePembayaran" => $kodePembayaran,
            "jenisCaraBayar" => $jenisCaraBayar,
            "kodeJenisCaraBayar" => $kodeJenisCaraBayar,
            "namaPoli" => $namaPoli,
            "namaRuangRawat" => $namaRuangRawat,
            "noUrut" => $pNoUrut,
            "editResep" => $editResep,
            "verifikasi" => $verifikasi,
            "verified" => $verified,
            "dokter" => $dokter,

            "kodeObat" => $daftarKodeObat,
            "kuantitas" => $daftarKuantitas,
            "idRacik" => $daftarIdRacik,
            "namaSigna1" => $daftarNamaSigna1,
            "namaSigna2" => $daftarNamaSigna2,
            "namaSigna3" => $daftarNamaSigna3,
            "diskonObat" => $daftarDiskonObat,
            "hargaJual" => $daftarHargaJual,

            // not exist in form
            "id" => $id,

            // exist but not converted yet
            "numero" => $daftarNoRacik,
            "diskonracik" => $daftarDiskonRacik,
            "kode_signa" => $daftarKodeSigna,
            "kode_signa_racik" => $daftarKodeSignaRacik,
            "nm_racikan" => $daftarNamaRacikan,
            "qtypembungkus" => $daftarJumlahPembungkus,
            "kode_pembungkus" => $daftarKodePembungkus,
            "hargapembungkus" => $daftarHargaPembungkus,
            "kode_obat_awal" => $daftarKodeObatAwal,

            // not exist in form
            // TODO: php: uncategorized: convert $_POST["xxx-$key"] to $_POST["xxx"][$key]
            // ["kode_pembungkus-$key"]
            // ["qtypembungkus-$key"]
            // ["hargapembungkus-$key"]
            // ["kode_obat-$key"]
            // ["hargasatuan-$key"]
            // ["qty-$key"]
            // ["ketjumlah-$key"]
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $operator = Yii::$app->userFatma->nama;
        $idDepo = Yii::$app->userFatma->idDepo;

        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");
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
                $adaDetailPenjualan = $connection->createCommand($sql, $params)->queryScalar();

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

                if (!$adaDetailPenjualan) {
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
                        ":kodeBayar" => $kodePembayaran,
                        ":kodeJenisCaraBayar" => $kodeJenisCaraBayar,
                        ":jenisCaraBayar" => $jenisCaraBayar,
                        ":kodeRuangRawat" => $kodeRuangRawat,
                        ":keterangan" => $pNoUrut,
                    ];
                    $connection->createCommand($sql, $params)->execute();
                }

                $daftarTemuObat = [];
                $daftarJasaObatRacik = [];

                foreach ($daftarKodeObat as $key => $kodeObat) {
                    $diskon = (float) $daftarDiskonObat[$key];
                    $cekMinus = ($daftarKuantitas[$key] < 0) ? 1 : 0;
                    $jumlah = abs($daftarKuantitas[$key]);
                    $harga = $daftarHargaJual[$key];
                    $diskonObat += $harga * $jumlah * $diskon / 100;

                    $dataKatalog = $this->actionBridging5([
                        "fungsi" => "SELECT_KATALOG_BRG",
                        "kode" => $kodeObat
                    ]);

                    $dataKatalog[0]["KD_KATALOG"] = str_replace("]", "", $dataKatalog[0][0]);
                    $dataKatalog[0]["KD_KATALOG"] ??= 0;

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            b.hna_item    AS hnaItem,
                            b.hp_item     AS hpItem,
                            b.hja_setting AS hjaSetting
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

                    $daftarTagihan[$key] = $harga2->hjaSetting * $daftarKuantitas[$key];
                    $daftarPelayanan[$key] = 0;

                    $noUrut++;
                    $minJumlah = $jumlah;

                    $urutanObat = $daftarKodeObatAwal[$key] ? ", urutan = '" . ($key + 1) . "'" : "";

                    if (!$kodeObat) continue;
                    $jumlahStok = ($cekMinus == 1) ? $minJumlah * (-1) : $minJumlah;

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
                    $cekPenjualan2 = $connection->createCommand($sql, $params)->queryScalar();

                    if ($cekPenjualan2) {
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
                            ":harga" => $harga,
                            ":jumlahPenjualan" => $jumlahStok,
                            ":signa" => $daftarKodeSigna[$key],
                            ":kodePenjualan" => $kodePenjualan,
                            ":kodeObat" => $kodeObat,
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
                        $params = [":namaSigna" => $daftarNamaSigna2[$key]];
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
                    $params = [":namaSigna" => $daftarNamaSigna3[$key]];
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
                            SELECT c.kode_stokopname
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
                                ":kodeRef" => $kodeRekamMedis,
                                ":kodeStokopname" => $kodeStokOpname,
                                ":tanggalTersedia" => $nowValSystem,
                                ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                                ":idKatalog" => $kodeObat,
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
                            $params = [":jumlah" => $jumlah, ":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
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
                                ":kodeRef" => $kodeRekamMedis,
                                ":kodeStokopname" => $kodeStokOpname,
                                ":tanggalTersedia" => $nowValSystem,
                                ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                                ":idKatalog" => $kodeObat,
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
                                SELECT TRUE
                                FROM db1.transaksif_stokkatalog
                                WHERE
                                    id_depo = :idDepo
                                    AND id_katalog = :idKatalog
                                LIMIT 1
                            ";
                            $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                            $cekStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

                            if ($cekStokKatalog) {
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
                                $params = [":jumlah" => $jumlah, ":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                                $connection->createCommand($sql, $params)->execute();

                            } else {
                                $sql = /** @lang SQL */ "
                                    -- FILE: ".__FILE__." 
                                    -- LINE: ".__LINE__." 
                                    INSERT INTO db1.transaksif_stokkatalog
                                    SET
                                        jumlah_stokfisik = :jumlahStokFisik,
                                        jumlah_stokadm = :jumlahStokAdm,
                                        jumlah_itemfisik = :jumlahItemFisik,
                                        id_kemasan = 0,
                                        status = 1,
                                        id_depo = :idDepo,
                                        id_katalog = :idKatalog
                                ";
                                $params = [
                                    ":jumlahStokFisik" => -$jumlah,
                                    ":jumlahStokAdm" => -$jumlah,
                                    ":jumlahItemFisik" => $jumlah,
                                    ":idDepo" => $idDepo,
                                    ":idKatalog" => $kodeObat,
                                ];
                                $connection->createCommand($sql, $params)->execute();
                            }
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
                        $params = [":minJumlah" => $minJumlah, ":kodeDepo" => $idDepo, ":kodeObat" => $kodeObat];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    if (!$daftarIdRacik[$key] && $kodeJenisResep != "00" && !$daftarTemuObat[$kodeObat]) {
                        $jasaHargaObat22 += 500;
                        $daftarTemuObat[$kodeObat] = "exist";

                    } elseif (!$daftarJasaObatRacik[$daftarIdRacik[$key]] && $kodeJenisResep != "00") {
                        $daftarJasaObatRacik[$daftarIdRacik[$key]] = "exist";
                        $jasaHargaObat22 += 1000;
                    }
                }

                $hargaPembungkus = 0;
                $diskonRacik = 0;

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
                        $pKuantitasPembungkus = $_POST["qtypembungkus-$key"][$key4];
                        $pHargaPembungkus = $_POST["hargapembungkus-$key"][$key4];
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
                    }

                    foreach ($_POST["kode_obat-$key"] as $key2 => $kodeObat) {
                        $pHargaSatuan = $_POST["hargasatuan-$key"][$key2];
                        $pJumlah = $_POST["qty-$key"][$key2] ?: 1;
                        $pKeteranganJumlah = $_POST["ketjumlah-$key"][$key2];

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

                        $daftarTagihanRacik[$key2] = $hargaJual * $pJumlah;
                        $diskonRacik += $pHargaSatuan * $pJumlah * $diskon / 100;
                        $dataKatalog[0]["KD_KATALOG"] ??= 0;

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
                            ":operator" => $operator,
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
                        $noUrut++;
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
                    SET kode_penjualan = :kodeBaru
                    WHERE kode_penjualan = :kodeLama
                ";
                $params = [":kodeBaru" => $kodePenjualan, ":kodeLama" => $kodePenjualanSebelumnya];
                $connection->createCommand($sql, $params)->execute();
            }
        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.masterf_penjualan
                WHERE
                    no_resep = :noResep
                    AND transfer != ''
                LIMIT 1
            ";
            $params = [":noResep" => $noResep];
            $cekTransfer = $connection->createCommand($sql, $params)->queryScalar();
            $cekTransfer ? $this->actionTransferUbahCaraBayar($noResep, $noPendaftaran) : null;

            $tagihan22 = 0;
            $returTagihan = 0;

            foreach ($daftarKodeObat as $key => $kodeObat) {
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

                $dataKatalog = $this->actionBridging5([
                    "fungsi" => "SELECT_KATALOG_BRG",
                    "kode" => $kodeObat
                ]);

                $dataKatalog[0]["KD_KATALOG"] ??= 0;

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        b.hja_setting AS hjaSetting,
                        b.hp_item     AS hpItem
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
                    ":jumlahPenjualan" => $daftarKuantitas[$key],
                    ":signa" => $daftarKodeSigna[$key],
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
                    SELECT c.kode_stokopname
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
                    SELECT TRUE
                    FROM db1.masterf_penjualan
                    WHERE
                        no_resep = :noResep
                        AND kodeObat = :kodeObat
                        AND jlhPenjualan = :jumlahPenjualan
                    LIMIT 1
                ";
                $params = [":noResep" => $noResep, ":kodeObat" => $kodeObat, ":jumlahPenjualan" => -$daftarKuantitas[$key]];
                $cekPenjualan = $connection->createCommand($sql, $params)->queryScalar();

                if ($cekPenjualan && !$daftarIdRacik[$key]) {
                    $tagihan22 += 500;
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.masterf_penjualandetail
                        SET
                            jasapelayanan = jasapelayanan - 500,
                            total = total - 500
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
                    ":idKatalog" => $kodeObat,
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
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#delete    the original method
     */
    public function actionDelete(): Response
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

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

        return new Response;
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#verifikasi    the original method
     */
    public function actionVerifikasi(): void
    {
        ["id" => $noResep] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                no_resep     AS noResep,
                nama_pasien  AS namaPasien,
                kodeObat     AS kodeObat,
                jlhPenjualan AS jumlahPenjualan,
                verifikasi   AS verifikasi
            FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        if ($daftarPenjualan[0]->verifikasi ?? false) return;

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
        $params = [":verifikasi" => Yii::$app->userFatma->nama, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $idDepo = Yii::$app->userFatma->idDepo;
        foreach ($daftarPenjualan as $penjualan) {
            if (!$penjualan->kodeObat) continue;

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
            $params = [":idKatalog" => $penjualan->kodeObat, ":idDepo" => $idDepo];
            $jumlahStokFisikBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT c.kode_stokopname
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
                SELECT
                    b.hja_setting AS hjaSetting,
                    b.hp_item     AS hpItem
                FROM db1.masterf_katalog AS a
                LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                WHERE
                    a.kode = :kode
                    AND b.sts_hja != 0
                    AND b.sts_hjapb != 0
                LIMIT 1
            ";
            $params = [":kode" => $penjualan->kodeObat];
            $harga = $connection->createCommand($sql, $params)->queryOne();

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
                    userid_last = :idUser,
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
                ":hargaItem" => $harga->hjaSetting,
                ":hargaPerolehan" => $harga->hpItem,
                ":jumlahKeluar" => $penjualan->jumlahPenjualan,
                ":idUser" => $idUser,
                ":jumlahTersedia" => $jumlahStokFisikBefore - $penjualan->jumlahPenjualan,
                ":keterangan" => "Pemakaian pasien " . $penjualan->namaPasien,
            ];
            $connection->createCommand($sql, $params)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $penjualan->kodeObat];
            $cekStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

            if ($cekStokKatalog) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = jumlah_stokfisik - :pengurangStokFisik,
                        jumlah_stokadm = jumlah_stokadm - :pengurangStokAdm
                    WHERE
                        id_katalog = :idKatalog
                        AND id_depo = :idDepo
                ";
                $params = [
                    ":pengurangStokFisik" => $penjualan->jumlahPenjualan,
                    ":pengurangStokAdm" => $penjualan->jumlahPenjualan,
                    ":idKatalog" => $penjualan->kodeObat,
                    ":idDepo" => $idDepo,
                ];

            } else {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = :pengurangStokFisik,
                        jumlah_stokadm = :pengurangStokAdm,
                        jumlah_itemfisik = :jumlahItemFisik,
                        id_kemasan = 0,
                        status = 1,
                        id_depo = :idDepo,
                        id_katalog = :idKatalog
                ";
                $params = [
                    ":pengurangStokFisik" => -$penjualan->jumlahPenjualan,
                    ":pengurangStokAdm" => -$penjualan->jumlahPenjualan,
                    ":jumlahItemFisik" => $penjualan->jumlahPenjualan,
                    ":idDepo" => $idDepo,
                    ":idKatalog" => $penjualan->kodeObat,
                ];
            }
            $connection->createCommand($sql, $params)->execute();
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#transfer    the original method
     */
    public function actionTransfer(): void
    {
        ["id" => $noResep, "daftar" => $daftar] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $getkeluar = $this->actionTestBridgeCekKeluar($daftar);
        if ($getkeluar[1] == 1) return;

        $connection = Yii::$app->dbFatma;
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
            UPDATE db1.masterf_penjualan
            SET
                transfer = :transfer,
                tgltransfer = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":transfer" => Yii::$app->userFatma->nama, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $this->actionBridging4([
            "fungsi" => "carinodaftar",
            "NORM" => $kodeRekamMedis
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#transferubahcarabayar    the original method
     */
    public function actionTransferUbahCaraBayar(): void
    {
        ["id" => $noResep, "daftar" => $daftar] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $getkeluar = $this->actionTestBridgeCekKeluar($daftar);
        if ($getkeluar[1] == 1) return;

        $connection = Yii::$app->dbFatma;
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
            UPDATE db1.masterf_penjualan
            SET
                transfer = :transfer,
                tgltransfer = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":transfer" => Yii::$app->userFatma->nama, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $this->actionBridging4(["fungsi" => "carinodaftar", "NORM" => $kodeRekamMedis]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#batalbayar    the original method
     */
    public function actionBatalBayar(): void
    {
        $noResep = Yii::$app->request->post("id");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualan
            SET
                bayar = '',
                tglbayar = ''
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualandetail
            SET bayar = ''
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#bayar    the original method
     */
    public function actionBayar(): void
    {
        $noResep = Yii::$app->request->post("id");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

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
            UPDATE db1.masterf_penjualan
            SET
                bayar = :bayar,
                tglbayar = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":bayar" => Yii::$app->userFatma->username, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $this->actionBridging4([
            "fungsi" => "carinodaftar",
            "NORM" => $kodeRekamMedis
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#listresep    the original method
     */
    public function actionTableResepData(): string
    {
        [   "dariTanggal" => $tanggalAwal,
            "sampaiTanggal" => $tanggalAkhir,
            "idDepo" => $idDepo,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noResep" => $noResep,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
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
                a.no_resep                    AS noResep,
                a.kode_rm                     AS kodeRekamMedis,
                a.nama_pasien                 AS namaPasien,
                a.tglPenjualan                AS tanggalPenjualan,
                a.kodePenjualan               AS kodePenjualan,
                a.verifikasi                  AS verifikasi,
                a.tglverifikasi               AS tanggalVerifikasi,
                a.transfer                    AS transfer,
                d.no_antrian                  AS noAntrian,
                c.jenisResep                  AS jenisResep,
                c.totaldiskon                 AS totalDiskon,
                ''                            AS totalCeil
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            INNER JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_antrian AS d ON d.kode_penjualan = a.kodePenjualan
            WHERE
                a.no_resep != ''
                AND (:kodeDepo = '' OR a.kode_depo = :kodeDepo)
                AND (:kodeRekamMedis = '' OR a.kode_rm = :kodeRekamMedis)
                AND (:noResep = '' OR a.no_resep = :noResep)
                AND a.tglPenjualan >= :tanggalAwal
                AND a.tglPenjualan <= :tanggalAkhir
            GROUP BY a.no_resep
            ORDER BY a.tglPenjualan DESC
        ";
        $params = [
            ":kodeDepo" => $kodeDepo,
            ":kodeRekamMedis" => $kodeRekamMedis,
            ":noResep" => $noResep,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep as $i => $resep) {
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

            $resep->totalCeil = ceil(($penjualan->totalJual + $penjualan->totalJasaPelayanan - $resep->totalDiskon) / 100) * 100;
        }

        return json_encode($daftarResep);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#etiket    the original method
     */
    public function actionEtiket(): void
    {
        [   "noResep" => $noResep,
            "noPrinter" => $noPrinter,
            "arah" => $arah,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT ip_address -- all are in use.
            FROM db1.printer_depo
            WHERE
                kode_depo = :kodeDepo
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
                    PJN.no_resep                  AS noResep,
                    PJN.kodeObat                  AS kodeObat,
                    KAT.nama_sediaan              AS namaSediaan,
                    PJN.kode_rm                   AS kodeRekamMedis,
                    TRIM(PJN.nama_pasien)         AS namaPasien,
                    # pasien_detail.tanggal_lahir AS tanggalLahir,
                    PSM.tanggal_lahir             AS tanggalLahir,
                    DAYOFMONTH(PSM.tanggal_lahir) AS tanggal,
                    MONTH(PSM.tanggal_lahir)      AS bulan,
                    YEAR(PSM.tanggal_lahir)       AS tahun,
                    PJN.id_racik                  AS idRacik,
                    PJN.signa1                    AS signa1,
                    PJN.signa2                    AS signa2,
                    PJN.signa3                    AS signa3,
                    PJN.jlhPenjualan              AS kuantitas,
                    PDT.keterangan                AS keterangan,
                    ''                            AS potonganNama1,
                    ''                            AS potonganNama2
                FROM db1.masterf_penjualan AS PJN
                INNER JOIN db1.masterf_katalog AS KAT ON PJN.kodeObat = KAT.kode
                # LEFT JOIN db1.pasien_detail ON PJN.kode_rm = pasien_detail.no_rm
                LEFT JOIN db1.pasien_small AS PSM ON PJN.kode_rm = PSM.no_rm
                LEFT JOIN db1.masterf_penjualandetail AS PDT ON PJN.no_resep = PDT.no_resep
                WHERE
                    PJN.no_resep = :noResep
                    AND KAT.id_jenisbarang IN (1, 8, 9, 10, 11, 16, 17, 18, 21, 22, 23, 25, 26, 28)
                    AND PJN.id_racik = ''
            UNION
                SELECT
                    PJN.no_resep                     AS noResep,
                    PJN.kodeObat                     AS kodeObat,
                    # KAT.nama_sediaan,
                    CONCAT('Racikan ', PJN.id_racik) AS namaSediaan,
                    PJN.kode_rm                      AS kodeRekamMedis,
                    TRIM(PJN.nama_pasien)            AS namaPasien,
                    # pasien_detail.tanggal_lahir    AS tanggalLahir,
                    PSM.tanggal_lahir                AS tanggalLahir,
                    DAYOFMONTH(PSM.tanggal_lahir)    AS tanggal,
                    MONTH(PSM.tanggal_lahir)         AS bulan,
                    YEAR(PSM.tanggal_lahir)          AS tahun,
                    PJN.id_racik                     AS idRacik,
                    PJN.signa1                       AS signa1,
                    PJN.signa2                       AS signa2,
                    PJN.signa3                       AS signa3,
                    PJN.jlhPenjualan                 AS kuantitas,
                    PDT.keterangan                   AS keterangan,
                    ''                               AS potonganNama1,
                    ''                               AS potonganNama2
                FROM db1.masterf_penjualan AS PJN
                INNER JOIN db1.masterf_katalog AS KAT ON PJN.kodeObat = KAT.kode
                # LEFT JOIN db1.pasien_detail ON PJN.kode_rm = pasien_detail.no_rm
                LEFT JOIN db1.pasien_small AS PSM ON PJN.kode_rm = PSM.no_rm
                LEFT JOIN db1.masterf_penjualandetail AS PDT ON PJN.no_resep = PDT.no_resep
                WHERE
                    PJN.no_resep = :noResep
                    # AND PJN.signa1 != ''
                    AND KAT.id_jenisbarang IN (1, 8, 9, 10, 11, 16, 17, 18, 21, 22, 23, 25, 26, 28)
                    AND PJN.id_racik != ''
                GROUP BY PJN.id_racik
        ";
        $params = [":noResep" => $noResep];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        $offset = 25;
        $posTerakhir = 50;
        foreach ($daftarResep as $key => $resep) {
            $namaSediaan = strtoupper($resep->namaSediaan);
            if ($offset >= strlen($namaSediaan)) {
                $resep->potonganNama1 = strtoupper($namaSediaan);
                $resep->potonganNama2 = "";
            } else {
                $breakIndex = strpos($namaSediaan, " ", $offset);
                $namaSediaan1 = substr($namaSediaan, 0, $breakIndex);
                if (strlen($namaSediaan1) > $offset) {
                    $namaSediaan1 = substr($namaSediaan1, 0, $offset);
                }

                $resep->potonganNama1 = strtoupper($namaSediaan1);
                $resep->potonganNama2 = strtoupper(substr($namaSediaan, $breakIndex, $posTerakhir));
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://$ip/printer.php",
            CURLOPT_USERAGENT => "Codular Sample cURL Request",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => ["resep" => json_encode($daftarResep), "direction" => $arah]
        ]);
        curl_close($curl);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#cetakstruk    the original method
     */
    public function actionViewStrukData(): string
    {
        $noResep = Yii::$app->request->post("noResep");
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
                e.jenisResep      AS jenisResep,
                a.transfer        AS transfer,
                a.verifikasi      AS verifikasi,
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
                d.nama              AS namaSigna,
                SUM(a.jlhPenjualan) AS jumlahPenjualan,  -- in use
                a.harga             AS hargaJual         -- in use
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
        $params = [":noResep" => $noResep];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_INST
            FROM db1.masterf_kode_inst
            WHERE kd_inst = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = $connection->createCommand($sql, $params)->queryScalar();

        $total = 0;
        $kodeRacik = "";
        $noRacik = 1;
        $noObat = 1;
        $daftarObat2 = [];

        foreach ($daftarObat as $j => $obat) {
            $obat2 = new stdClass;
            if ($obat->kodeRacik) {
                if ($kodeRacik != $obat->kodeRacik) {
                    $noRacik = 1;
                    $obat2->noObat = $noObat;
                    $kodeRacik = $obat->kodeRacik;
                    $noObat++;
                }
                $obat2->noRacik = $noRacik;
                $noRacik++;
            } else {
                $obat2->noObat = $noObat;
                $noObat++;
            }

            $total += $obat->jumlahPenjualan * $obat->hargaJual;
            $daftarObat2[] = $obat2;
        }

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
        $totalRacikan = $jumlahPenjualan * 1000;

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
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 500
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

        return json_encode([
            "username" => Yii::$app->userFatma->username,
            "grandTotal" => $grandTotal,
            "namaInstalasi" => $namaInstalasi,
            "namaDepo" => Yii::$app->userFatma->namaDepo,
            "pasien" => $pasien,
            "daftarObat1" => $daftarObat,
            "daftarObat2" => $daftarObat2,
            "penjualan" => $penjualan,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#cetakstruk3    the original method
     */
    public function actionCetakStruk3(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
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
                a.no_daftar       AS noPendaftaran,
                e.totaldiskon     AS totalDiskon,
                e.totalpembungkus AS totalPembungkus, -- in use
                a.verifikasi      AS verifikasi,
                e.KD_INST         AS kodeInstalasi,   -- in use
                e.jasapelayanan   AS jasaPelayanan,   -- in use
                a.no_resep        AS noResep          -- in use
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
                b.nama_barang                       AS namaBarang,
                SUM(a.jlhPenjualan)                 AS subtotalJumlah,
                CEIL(a.harga * SUM(a.jlhPenjualan)) AS subtotalHarga,
                a.id_racik                          AS idRacik   
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            LEFT JOIN db1.relasif_hargaperolehan AS z ON z.id_katalog = b.kode
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

        foreach ($daftarObat as $key => $obat) {
            $daftarHalaman[$h][$b] = $obat;

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

        $daftarLainnya = [];

        foreach ($daftarHalaman as $key => $halaman) {
            $allTotal = 0;
            $racik = "";

            foreach ($halaman as $baris) {
                if ($racik == "" || $racik != $baris->idRacik) {
                    $pasien->jasaPelayanan -= 500;
                    $racik = $baris->idRacik;
                }
                $allTotal += $baris->subtotalJumlah;
            }

            if ($allTotal == 0) {
                $pasien->jasaPelayanan = 0;
            }

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

            $daftarLainnya[$key] = [
                "totalJual" => $penjualan->totalJual,
                "totalJasaPelayanan" => $penjualan->totalJasaPelayanan,
                "grandTotal" => $grandTotal,
            ];
        }

        $view = new CetakStrukNew(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaInstalasi: $namaInstalasi,
            namaDepo:      Yii::$app->userFatma->namaDepo,
            jumlahHalaman: count($daftarHalaman),
            daftarLainnya: $daftarLainnya,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#cetakstruklq    the original method
     */
    public function actionCetakStrukLq(): string
    {
        $noResep = Yii::$app->request->post("noResep");
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
                a.no_daftar       AS noPendaftaran,
                e.iter            AS iter,
                e.totaldiskon     AS totalDiskon,
                e.totalpembungkus AS totalPembungkus,   -- in use
                a.verifikasi      AS verifikasi,
                a.tglverifikasi   AS tanggalVerifikasi,
                e.KD_INST         AS kodeInstalasi,     -- in use
                e.jasapelayanan   AS jasaPelayanan,     -- in use
                a.tglPenjualan    AS tanggalPenjualan,  -- in use
                a.no_resep        AS noResep
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
                a.jlhPenjualan           AS jumlahPenjualan,  -- in use
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
                z.hja_setting            AS hjaSetting  -- in use
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            LEFT JOIN db1.relasif_hargaperolehan AS z ON z.id_katalog = b.kode
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
        $barisPerHalaman = 20;

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

        $daftarLainnya = [];

        foreach ($daftarHalaman as $key => $halaman) {
            $allTotal = 0;
            $racik = "";

            foreach ($halaman as $baris) {
                if ($racik == "" || $racik != $baris["id_racik"]) {
                    $pasien->jasaPelayanan -= 1000;
                    $racik = $baris["id_racik"];
                }
                $allTotal += $baris["jumlah"];
            }

            if ($allTotal == 0) {
                $pasien->jasaPelayanan = 0;
            }

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

            if (strtotime($pasien->tanggalPenjualan) >= strtotime("2016-10-01")) {
                $totalRacikan = $jumlahPenjualan * 1000;
                $pembelianBebas = 500;
            } else {
                $totalRacikan = $jumlahPenjualan * 500;
                $pembelianBebas = 300;
            }

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
                            WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN :pembelianBebas
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
            $params = [":totalRacikan" => $totalRacikan, ":pembelianBebas" => $pembelianBebas, ":noResep" => $noResep];
            $penjualan = $connection->createCommand($sql, $params)->queryOne();

            $totalCeil = ceil(($penjualan->totalJual - $penjualan->totalDiskon) / 100) * 100;
            $penjualan->totalJasaPelayanan += $totalCeil - ($penjualan->totalJual - $penjualan->totalDiskon);
            $grandTotal = $penjualan->totalJual - $penjualan->totalDiskon + $pasien->totalPembungkus + $penjualan->totalJasaPelayanan;

            $daftarLainnya[$key] = [
                "totalJasaPelayanan" => $penjualan->totalJasaPelayanan,
                "totalJual" => $penjualan->totalJual,
                "grandTotal" => $grandTotal,
            ];
        }

        $view = new CetakStrukLq(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaInstalasi: $namaInstalasi,
            namaDepo:      Yii::$app->userFatma->namaDepo,
            daftarObat:    $daftarObat,
            daftarLainnya: $daftarLainnya,
            jumlahHalaman: count($daftarHalaman),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#antrian2    the original method
     */
    public function actionAntrian2(): string
    {
        $kodePenjualan = Yii::$app->request->post("kodePenjualan") ?? throw new MissingPostParamException("kodePenjualan");
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
        $params = [":tanggal" => $todayValUser, ":kodePenjualan" => $kodePenjualan];
        $noAntrian = $connection->createCommand($sql, $params)->queryScalar();

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
            $noAntrian = $connection->createCommand($sql, $params)->queryScalar() + 1;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.masterf_antrian
                SET
                    no_antrian = :noAntrian,
                    kode_penjualan = :kodePenjualan,
                    tanggal = :tanggal
            ";
            $params = [":noAntrian" => $noAntrian, ":kodePenjualan" => $kodePenjualan, ":tanggal" => $todayValUser];
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
                d.name          AS namaDokter
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            LEFT JOIN db1.user AS d ON d.id = c.dokter
            WHERE a.kodePenjualan = :kodePenjualan
            LIMIT 1
        ";
        $params = [":kodePenjualan" => $kodePenjualan];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.nama_barang     AS namaBarang,
                A.jlhPenjualan    AS jumlahPenjualan,
                A.signa           AS signa,
                A.keterangan_obat AS keteranganObat
            FROM db1.masterf_penjualan AS A
            LEFT JOIN db1.masterf_katalog AS B ON B.kode = A.kodeObat
            LEFT JOIN db1.master_signa AS D ON D.kode = A.signa
            WHERE
                a.kodePenjualan = :kodePenjualan
                AND kode_racik = ''
        ";
        $params = [":kodePenjualan" => $kodePenjualan];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_racik  AS kodeRacik,
                A.signa       AS signa,
                A.nama_racik  AS namaRacik,
                A.no_racik    AS noRacik,
                B.nama_barang AS namaBarang,
                A.ketjumlah   AS keteranganJumlah
            FROM db1.masterf_penjualan AS A
            LEFT JOIN db1.masterf_katalog AS B ON b.kode = a.kodeObat
            LEFT JOIN db1.master_signa AS D ON d.kode = a.signa
            WHERE
                a.kodePenjualan = :kodePenjualan
                AND a.kode_racik != ''
            ORDER BY kode_racik
        ";
        $params = [":kodePenjualan" => $kodePenjualan];
        $daftarRacik = $connection->createCommand($sql, $params)->queryAll();

        $view = new Antrian2(
            noAntrian:   $noAntrian,
            pasien:      $pasien,
            daftarObat:  $daftarObat,
            daftarRacik: $daftarRacik,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#testbridge_cekkeluar    the original method
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#bridging4    the original method
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#getCounter2 the original method
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#addCounter2 the original method
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
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepo.php#bridging5    the original method
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
