<?php /** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\models;

use PDO;
use PDOException;

date_default_timezone_set("Asia/Jakarta");

putenv("FREETDSCONF=/etc/freetds.conf");
putenv("FREETDS=/etc/freetds.conf");

// konfigurasi SQL Server Fatmahost
define("DBHOST_SQL", "sqlfatmahost");
define("DBNAME_SQL", "RSF");
define("USER_SQL", "sa");
define("RHS_SQL", "sql@2012");

// konfigurasi database FO
define("DBHOST_FO", "sybasefatmafo");
define("DBNAME_FO", "FO");
define("USER_FO", "sa");
define("RHS_FO", "mandiri");

// konfigurasi inv
define("DBHOST", "sybasefatmabo");
define("DBNAME", "INV");
define("USER", "sa");
define("RHS", "mandiri");

/**
 * Class Bridging
 * @package tlm\his\FatmaPharmacy\models
 */
class Bridging
{
    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienNew(string|array $kodeRekamMedis = "", string $noPendaftaran = "", string $tanggalDaftar = "", string $tanggalDaftar2 = "", string $tanggalDaftar3 = "", string $kodeInstalasi = ""): array|bool
    {
        $tanggalDaftar ??= date("Y/m/d");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NO_PENDAFTARAN,
                A.NORM,
                A.TGL_DAFTAR,
                B.NAMA
            FROM db_his_live.TDFT_PENDAFTARAN A
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON A.NORM = B.NORM
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            WHERE
                A.NO_PENDAFTARAN LIKE :noPendaftaranTem
                AND A.STS_BATAL != 1
                AND A.KD_INST NOT IN ('08', '09', 10, 30, 23, 41, 44, 31)
                AND (:kodeRekamMedis = '' OR A.NORM = :kodeRekamMedis)
                AND (:noPendaftaran = '' OR A.NO_PENDAFTARAN LIKE :noPendaftaran)
                AND A.TGL_DAFTAR >= :tanggalAwal
                AND A.TGL_DAFTAR <= :tanggalAkhir
                AND (
                    :kodeInstalasi = ''
                    OR (:kodeInstalasi = '01' AND G.KD_KLP IN ('01', '02', 40))
                    OR (:kodeInstalasi = '02' AND G.KD_INST = '03' AND A.KD_POLI = '04')
                    OR (:kodeInstalasi = '02' AND G.KD_KLP IN ('02', 40))
                    OR (:kodeInstalasi = '02' AND G.KD_INST IN ('07', 40))
                    OR (:kodeInstalasi = '03' AND G.KD_KLP = '03')
                    OR (:kodeInstalasi = '03' AND G.KD_INST IN ('04', '06', 19, 43))
                    OR (:kodeInstalasi = '07' AND G.KD_KLP IN ('07', 40))
                    OR (G.KD_KLP = :kodeInstalasi)
                )
            ORDER BY A.NO_PENDAFTARAN DESC
            LIMIT 15
        ";
        $params = [
            ":noPendaftaranTem" => date("Y", strtotime($tanggalDaftar)) . "%",
            ":noPendaftaran" => $noPendaftaran ? "%$noPendaftaran%" : "",
            ":kodeRekamMedis" => $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : "",
            ":tanggalAwal" => date("Y-m-d", strtotime($tanggalDaftar2 ?? $tanggalDaftar)) . " 00:00:00.000",
            ":tanggalAkhir" => date("Y-m-d", strtotime($tanggalDaftar3 ?? $tanggalDaftar)) . " 23:59:59.000",
            ":kodeInstalasi" => $kodeInstalasi ?? "",
        ];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienNew33(array $kodeRekamMedis, string $noPendaftaran = "", string $tanggalDaftar = "", string $tanggalDaftar2 = "", string $tanggalDaftar3 = "", string $kodeInstalasi = ""): array|bool
    {
        $tanggalDaftar ??= date("Y/m/d");
        $kodeRekamMedis = $kodeRekamMedis["no_rm"];

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NO_PENDAFTARAN,
                A.NORM,
                A.TGL_DAFTAR,
                B.NAMA
            FROM db_his_live.TDFT_PENDAFTARAN A
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON A.NORM = B.NORM
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            WHERE
                A.NO_PENDAFTARAN LIKE :noPendaftaranTem
                AND A.STS_BATAL != 1
                AND A.KD_INST NOT IN ('08', '09', 10, 30, 23, 41, 44, 31)
                AND (:kodeRekamMedis = '' OR A.NORM = :kodeRekamMedis)
                AND (:noPendaftaran = '' OR A.NO_PENDAFTARAN LIKE :noPendaftaran)
                AND A.TGL_DAFTAR >= :tanggalAwal
                AND A.TGL_DAFTAR <= :tanggalAkhir
                AND (
                    (:kodeInstalasi = '')
                    OR (:kodeInstalasi = '01' AND G.KD_KLP IN ('01', '02'))
                    OR (:kodeInstalasi = '02' AND G.KD_INST = '03' AND A.KD_POLI = '04')
                    OR (:kodeInstalasi = '02' AND G.KD_KLP IN ('02', 40))
                    OR (:kodeInstalasi = '02' AND G.KD_INST IN ('07', 40))
                    OR (:kodeInstalasi = '03' AND G.KD_KLP = '03')
                    OR (:kodeInstalasi = '03' AND G.KD_INST IN ('04', '06', 43))
                    OR (:kodeInstalasi = '07' AND G.KD_KLP IN ('07', 40))
                    OR (G.KD_KLP = :kodeInstalasi)
                )
            ORDER BY A.NO_PENDAFTARAN DESC
            LIMIT 15
        ";
        $params = [
            ":noPendaftaranTem" => date("Y", strtotime($tanggalDaftar)) . "%",
            ":kodeRekamMedis" => $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : "",
            ":noPendaftaran" => $noPendaftaran ? "%$noPendaftaran%" : "",
            ":tanggalAwal" => date("Y-m-d", strtotime($tanggalDaftar2 ?? $tanggalDaftar)) . " 00:00:00.000",
            ":tanggalAkhir" => date("Y-m-d", strtotime($tanggalDaftar3 ?? $tanggalDaftar)) . " 23:59:59.000",
            ":kodeInstalasi" => $kodeInstalasi ?? "",
        ];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienNew2(string|array $nama = "", string $noPendaftaran = "", string $tanggalPendaftaran = ""): array|bool
    {
        $tanggalPendaftaran ??= date("Y/m/d");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NO_PENDAFTARAN,
                A.NORM,
                B.NAMA 
            FROM db_his_live.TDFT_PENDAFTARAN A 
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON A.NORM = B.NORM
            WHERE
                A.NO_PENDAFTARAN LIKE :noPendaftaranTem
                AND (:nama = '' OR B.NAMA LIKE :nama)
                AND (:noPendaftaran = '' OR A.NO_PENDAFTARAN LIKE :noPendaftaran)
                AND A.TGL_DAFTAR >= :tanggalAwal
                AND A.TGL_DAFTAR <= :tanggalAkhir
                AND KD_INST = 40 
            ORDER BY A.NO_PENDAFTARAN DESC
            LIMIT 1
        ";
        $params = [
            ":noPendaftaranTem" => date("Y", strtotime($tanggalPendaftaran)) . "%",
            ":nama" => $nama ? "%$nama%" : "",
            ":noPendaftaran" => $noPendaftaran ? "%$noPendaftaran%" : "",
            ":tanggalAwal" => date("Y-m-d", strtotime($tanggalPendaftaran)) . " 00:00:00.000",
            ":tanggalAkhir" => date("Y-m-d", strtotime($tanggalPendaftaran)) . " 23:59:59.000",
        ];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienFull(string|array $kodeRekamMedis = "", string $noPendaftaran = "", string $tanggalDaftar = ""): array|bool|null
    {
        if (!$noPendaftaran || !$kodeRekamMedis || !$tanggalDaftar) return null;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NORM,
                B.NAMA,
                A.NO_PENDAFTARAN,
                A.NO_SJP,
                B.KELAMIN,
                B.TGL_LAHIR,
                C.ALAMAT,
                C.NO_TELPON,
                E.KD_BAYAR,
                E.CARABAYAR,
                F.JNS_CARABAYAR,
                F.KD_JNS_CARABAYAR,
                G.NICK_INST,
                A.KD_INST,
                G.NM_INST,
                H.NM_POLI,
                A.KD_POLI,
                J.KD_KAMAR,
                J.NM_KAMAR,
                K.KD_RRAWAT,
                K.NM_RRAWAT
            FROM db_his_live.TDFT_PENDAFTARAN A 
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON B.NORM = A.NORM
            LEFT JOIN db_his_live.TDFT_PASIEN_DEWASA C ON A.NORM = C.NORM
            LEFT JOIN rawatinapexist.TDFT_CRBYR_PASIEN1 D ON A.NO_PENDAFTARAN = D.NO_PENDAFTARAN
            LEFT JOIN sirsdev.MMAS_CARABAYAR E ON D.KD_BAYAR = E.KD_BAYAR
            LEFT JOIN sirsdev.MMAS_JNS_CRBYR F ON (D.KD_JNS_CARABAYAR = F.KD_JNS_CARABAYAR AND F.KD_BAYAR = D.KD_BAYAR)
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            LEFT JOIN sirsdev.MDFT_POLI_SMF H ON H.KD_POLI = A.KD_POLI AND A.KD_INST = H.KD_INST
            LEFT JOIN TDFT_RRAWAT_PASIEN I ON A.NO_PENDAFTARAN = I.NO_PENDAFTARAN
            LEFT JOIN rawatinapexist.MDFT_KAMAR J ON I.KD_KAMAR = J.KD_KAMAR
            LEFT JOIN rawatinapexist.MDFT_RRAWAT K ON I.KD_RRAWAT = K.KD_RRAWAT
            WHERE
                A.NO_PENDAFTARAN = :noPendaftaran
                AND A.NORM = :kodeRekamMedis
                AND A.KD_INST NOT IN ('08', '09', 10, 30, 23, 41, 44, 31)
            ORDER BY A.NO_PENDAFTARAN DESC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran, ":kodeRekamMedis" => $kodeRekamMedis];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienFull33(string|array $kodeRekamMedis = ""): array|bool|null
    {
        ["no_daftar" => $noPendaftaran, "tgl_daftar" => $tanggalDaftar, "no_rm" => $kodeRekamMedis] = $kodeRekamMedis;
        if (!$noPendaftaran || !$kodeRekamMedis || !$tanggalDaftar) return null;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NORM,
                B.NAMA,
                A.NO_PENDAFTARAN,
                B.KELAMIN,
                B.TGL_LAHIR,
                C.ALAMAT,
                C.NO_TELPON,
                E.KD_BAYAR,
                E.CARABAYAR,
                F.JNS_CARABAYAR,
                F.KD_JNS_CARABAYAR,
                G.NICK_INST,
                A.KD_INST,
                G.NM_INST,
                H.NM_POLI,
                A.KD_POLI,
                J.KD_KAMAR,
                J.NM_KAMAR,
                K.KD_RRAWAT,
                K.NM_RRAWAT
            FROM db_his_live.TDFT_PENDAFTARAN A 
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON B.NORM = A.NORM
            LEFT JOIN db_his_live.TDFT_PASIEN_DEWASA C ON A.NORM = C.NORM
            LEFT JOIN rawatinapexist.TDFT_CRBYR_PASIEN1 D ON A.NO_PENDAFTARAN = D.NO_PENDAFTARAN
            LEFT JOIN sirsdev.MMAS_CARABAYAR E ON D.KD_BAYAR = E.KD_BAYAR
            LEFT JOIN sirsdev.MMAS_JNS_CRBYR F ON (D.KD_JNS_CARABAYAR = F.KD_JNS_CARABAYAR AND F.KD_BAYAR = D.KD_BAYAR)
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            LEFT JOIN sirsdev.MDFT_POLI_SMF H ON H.KD_POLI = A.KD_POLI AND A.KD_INST = H.KD_INST
            LEFT JOIN TDFT_RRAWAT_PASIEN I ON A.NO_PENDAFTARAN = I.NO_PENDAFTARAN
            LEFT JOIN rawatinapexist.MDFT_KAMAR J ON I.KD_KAMAR = J.KD_KAMAR
            LEFT JOIN rawatinapexist.MDFT_RRAWAT K ON I.KD_RRAWAT = K.KD_RRAWAT
            WHERE
                A.NO_PENDAFTARAN = :noPendaftaran
                AND A.NORM = :kodeRekamMedis
                AND A.KD_INST NOT IN ('08', '09', 10, 30, 23, 41, 44, 31)
            ORDER BY A.NO_PENDAFTARAN DESC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran, ":kodeRekamMedis" => $kodeRekamMedis];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienFullBaru(array $data): array|bool
    {
        [   "fromtgl" => $tanggalAwal,
            "totgl" => $tanggalAkhir,
            "norm" => $kodeRekamMedis,
            "nodaftar" => $noPendaftaran,
            "nama" => $nama,
            "kelamin" => $kelamin,
            "kd_inst" => $kodeInstalasi,
        ] = $data;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NORM,
                B.NAMA,
                A.NO_SJP,
                G.KD_KLP,
                A.NO_PENDAFTARAN,
                A.TGL_DAFTAR,
                B.KELAMIN,
                B.TGL_LAHIR,
                C.ALAMAT,
                C.NO_TELPON,
                E.KD_BAYAR,
                E.CARABAYAR,
                F.JNS_CARABAYAR,
                F.KD_JNS_CARABAYAR,
                G.NICK_INST,
                A.KD_INST,
                G.NM_INST,
                H.NM_POLI,
                A.KD_POLI,
                J.KD_KAMAR,
                J.NM_KAMAR,
                K.KD_RRAWAT,
                K.NM_RRAWAT
            FROM db_his_live.TDFT_PENDAFTARAN A
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON B.NORM = A.NORM
            LEFT JOIN db_his_live.TDFT_PASIEN_DEWASA C ON A.NORM = C.NORM
            LEFT JOIN rawatinapexist.TDFT_CRBYR_PASIEN1 D ON A.NO_PENDAFTARAN = D.NO_PENDAFTARAN
            LEFT JOIN sirsdev.MMAS_CARABAYAR E ON D.KD_BAYAR = E.KD_BAYAR
            LEFT JOIN sirsdev.MMAS_JNS_CRBYR F ON (D.KD_JNS_CARABAYAR = F.KD_JNS_CARABAYAR AND F.KD_BAYAR = D.KD_BAYAR)
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            LEFT JOIN sirsdev.MDFT_POLI_SMF H ON H.KD_POLI = A.KD_POLI AND A.KD_INST = H.KD_INST
            LEFT JOIN TDFT_RRAWAT_PASIEN I ON A.NO_PENDAFTARAN = I.NO_PENDAFTARAN
            LEFT JOIN rawatinapexist.MDFT_KAMAR J ON I.KD_KAMAR = J.KD_KAMAR
            LEFT JOIN rawatinapexist.MDFT_RRAWAT K ON I.KD_RRAWAT = K.KD_RRAWAT
            WHERE
                (:tanggalAwal = '' OR A.TGL_DAFTAR >= :tanggalAwal)
                AND (:tanggalAkhir = '' OR A.TGL_DAFTAR <= :tanggalAkhir)
                AND (:kodeRekamMedis = '' OR A.NORM = :kodeRekamMedis)
                AND (:noPendaftaran = '' OR A.NO_PENDAFTARAN LIKE :noPendaftaran)
                AND (:nama = '' OR B.NAMA LIKE :nama)
                AND (:kelamin = '' OR B.KELAMIN = :kelamin)
                AND (
                    :kodeInstalasi IN ('', '02', '03')
                    OR (:kodeInstalasi = '01' AND G.KD_KLP IN ('01', '02', 40))
                    OR (:kodeInstalasi = '07' AND G.KD_KLP IN ('01', '07', 40))
                    OR (G.KD_KLP = :kodeInstalasi)
                )
                AND A.STS_BATAL != 1
                AND A.KD_INST NOT IN ('08', '09', 10, 30, 23, 41, 44, 31, 11, 42, 45, 47, 14, 13)
            ORDER BY A.NO_PENDAFTARAN ASC
        ";
        $params = [
            ":tanggalAwal" => $tanggalAwal ? date("Y-m-d", strtotime($tanggalAwal)) . " 00:00:00.000" : "",
            ":tanggalAkhir" => $tanggalAkhir ? date("Y-m-d", strtotime($tanggalAkhir)) . " 23:59:59.000" : "",
            ":kodeRekamMedis" => $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : "",
            ":noPendaftaran" => $noPendaftaran ? "%$noPendaftaran%" : "",
            ":nama" => $nama ? "%$nama%" : "",
            ":kelamin" => $kelamin ?? "",
            ":kodeInstalasi" => $kodeInstalasi ?? "",
        ];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienFullOp(array $data): array|bool
    {
        [   "nodaftar" => $noPendaftaran,
            "fromtgl" => $tanggalAwal,
            "totgl" => $tanggalAkhir,
            "kelamin" => $kelamin,
            "norm" => $kodeRekamMedis,
            "kd_inst" => $kodeInstalasi,
        ] = $data;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NORM,
                B.NAMA,
                G.KD_KLP,
                A.NO_PENDAFTARAN,
                A.TGL_DAFTAR,
                B.KELAMIN,
                B.TGL_LAHIR,
                C.ALAMAT,
                C.NO_TELPON,
                E.KD_BAYAR,
                E.CARABAYAR,
                F.JNS_CARABAYAR,
                F.KD_JNS_CARABAYAR,
                G.NICK_INST,
                A.KD_INST,
                G.NM_INST,
                H.NM_POLI,
                A.KD_POLI,
                J.KD_KAMAR,
                J.NM_KAMAR,
                K.KD_RRAWAT,
                K.NM_RRAWAT
            FROM db_his_live.TDFT_PENDAFTARAN A
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON B.NORM = A.NORM
            LEFT JOIN db_his_live.TDFT_PASIEN_DEWASA C ON A.NORM = C.NORM
            LEFT JOIN rawatinapexist.TDFT_CRBYR_PASIEN1 D ON A.NO_PENDAFTARAN = D.NO_PENDAFTARAN
            LEFT JOIN sirsdev.MMAS_CARABAYAR E ON D.KD_BAYAR = E.KD_BAYAR
            LEFT JOIN sirsdev.MMAS_JNS_CRBYR F ON (D.KD_JNS_CARABAYAR = F.KD_JNS_CARABAYAR AND F.KD_BAYAR = D.KD_BAYAR)
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            LEFT JOIN sirsdev.MDFT_POLI_SMF H ON H.KD_POLI = A.KD_POLI AND A.KD_INST = H.KD_INST
            LEFT JOIN TDFT_RRAWAT_PASIEN I ON A.NO_PENDAFTARAN = I.NO_PENDAFTARAN
            LEFT JOIN rawatinapexist.MDFT_KAMAR J ON I.KD_KAMAR = J.KD_KAMAR
            LEFT JOIN rawatinapexist.MDFT_RRAWAT K ON I.KD_RRAWAT = K.KD_RRAWAT
            WHERE
                (:tanggalAwal = '' OR A.TGL_DAFTAR >= :tanggalAwal)
                AND (:tanggalAkhir = '' OR A.TGL_DAFTAR <= :tanggalAkhir)
                AND (:kodeRekamMedis = '' OR A.NORM = :kodeRekamMedis)
                AND (:noPendaftaran = '' OR A.NO_PENDAFTARAN = :noPendaftaran)
                AND (:kelamin = '' OR B.KELAMIN = :kelamin)
                AND (
                    :kodeInstalasi IN ('', '02', '03')
                    OR (:kodeInstalasi = '01' AND G.KD_KLP IN ('01', '02', 40))
                    OR (:kodeInstalasi = '07' AND G.KD_KLP IN ('01', '07', 40))
                    OR (G.KD_KLP = :kodeInstalasi)
                )
                AND A.STS_BATAL != 1
                AND A.KD_INST NOT IN ('08', '09', 10, 30, 23, 41, 44, 31)
            ORDER BY A.NO_PENDAFTARAN ASC
        ";
        $params = [
            ":tanggalAwal" => $tanggalAwal ? date("Y-m-d", strtotime($tanggalAwal)) . " 00:00:00.000" : "",
            ":tanggalAkhir" => $tanggalAkhir ? date("Y-m-d", strtotime($tanggalAkhir)) . " 23:59:59.000" : "",
            ":noPendaftaran" => $noPendaftaran ?? "",
            ":kelamin" => $kelamin ?? "",
            ":kodeRekamMedis" => $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : "",
            ":kodeInstalasi" => $kodeInstalasi ?? "",
        ];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienKeluar(array $data): string
    {
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
        $params = [":noPendaftaran" => $data["NO_DAFTAR"]];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return count($return) ? "1" : "0";

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return "0";
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienFullBaruRuangRawat(array $data): array|bool
    {
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                NO_TTIDUR,
                NM_KAMAR,
                NM_RRAWAT,
                A.TGL_AWAL
            FROM TDFT_RRAWAT_PASIEN A
            INNER JOIN rawatinapexist.MDFT_KAMAR J ON J.KD_KAMAR = A.KD_KAMAR
            INNER JOIN rawatinapexist.MDFT_RRAWAT K ON K.KD_RRAWAT = A.KD_RRAWAT
            INNER JOIN sirsdev.MDFT_INSTALASI G ON G.KD_INST = A.KD_INST
            WHERE
                A.NO_PENDAFTARAN = :noPendaftaran
                AND J.KD_KELAS = A.KD_KELAS
            LIMIT 10
        ";
        $params = [":noPendaftaran" => $data["daftar"]];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @author Hendra Gunawan
     */
    public function getDataPasienFullBaruIrna(array $data): array|bool
    {
        [   "fromtgl" => $tanggalAwal,
            "totgl" => $tanggalAkhir,
            "nodaftar" => $noPendaftaran,
            "rrawat" => $kodeRuangRawat,
            "nama" => $nama,
            "kelamin" => $kelamin,
            "norm" => $kodeRekamMedis,
        ] = $data;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NORM,
                B.NAMA,
                A.NO_PENDAFTARAN,
                A.TGL_DAFTAR,
                B.KELAMIN,
                B.TGL_LAHIR,
                C.ALAMAT,
                C.NO_TELPON,
                E.KD_BAYAR,
                E.CARABAYAR,
                F.JNS_CARABAYAR,
                F.KD_JNS_CARABAYAR,
                G.NICK_INST,
                A.KD_INST,
                G.NM_INST,
                H.NM_POLI,
                A.KD_POLI,
                J.KD_KAMAR,
                J.NM_KAMAR,
                K.KD_RRAWAT,
                K.NM_RRAWAT
            FROM db_his_live.TDFT_PENDAFTARAN A
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON B.NORM = A.NORM
            LEFT JOIN db_his_live.TDFT_PASIEN_DEWASA C ON A.NORM = C.NORM
            LEFT JOIN rawatinapexist.TDFT_CRBYR_PASIEN1 D ON A.NO_PENDAFTARAN = D.NO_PENDAFTARAN
            LEFT JOIN sirsdev.MMAS_CARABAYAR E ON D.KD_BAYAR = E.KD_BAYAR
            LEFT JOIN sirsdev.MMAS_JNS_CRBYR F ON (D.KD_JNS_CARABAYAR = F.KD_JNS_CARABAYAR AND F.KD_BAYAR = D.KD_BAYAR)
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            LEFT JOIN sirsdev.MDFT_POLI_SMF H ON H.KD_POLI = A.KD_POLI AND A.KD_INST = H.KD_INST
            LEFT JOIN TDFT_RRAWAT_PASIEN I ON A.NO_PENDAFTARAN = I.NO_PENDAFTARAN
            LEFT JOIN rawatinapexist.MDFT_KAMAR J ON I.KD_KAMAR = J.KD_KAMAR
            LEFT JOIN rawatinapexist.MDFT_RRAWAT K ON I.KD_RRAWAT = K.KD_RRAWAT
            WHERE 
                (:tanggalAwal = '' OR A.TGL_DAFTAR >= :tanggalAwal)
                AND (:tanggalAkhir = '' OR A.TGL_DAFTAR <= :tanggalAkhir)
                AND (:kodeRekamMedis = '' OR A.NORM = :kodeRekamMedis)
                AND (:noPendaftaran = '' OR A.NO_PENDAFTARAN LIKE :noPendaftaran)
                AND (:kodeRuangRawat = '' OR K.KD_RRAWAT = :kodeRuangRawat)
                AND (:nama = '' OR B.NAMA LIKE :nama)
                AND (:kelamin = '' OR B.KELAMIN = :kelamin)
                AND A.STS_BATAL != 1
            ORDER BY A.NO_PENDAFTARAN ASC
        ";
        $params = [
            ":tanggalAwal" => $tanggalAwal ? date("Y-m-d", strtotime($tanggalAwal)) . " 00:00:00.000" : "",
            ":tanggalAkhir" => date("Y-m-d", strtotime($tanggalAkhir)) . " 23:59:59.000",
            ":noPendaftaran" => $noPendaftaran ? "%$noPendaftaran%" : "",
            ":kodeRuangRawat" => $kodeRuangRawat ?? "",
            ":nama" => $nama ? "%$nama%" : "",
            ":kelamin" => $kelamin ?? "",
            ":kodeRekamMedis" => $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : "",
        ];
        try {
            $conn = new PDO("dblib:host=" . DBHOST_FO . ";dbname=" . DBNAME_FO, USER_FO, RHS_FO);
            $temp = $conn->query($sql);
            $return = [];
            foreach ($temp as $data) {
                array_push($return, $data);
            }

            $temp->closeCursor();
            return $return;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "\n";
            return false;
        }
    }
}

$obj = new Bridging;
switch ($_POST["fungsi"]) {
    case "getDataPasiennew":              echo json_encode($obj->getDataPasienNew($_POST)); break;
    case "getDataPasiennew2":             echo json_encode($obj->getDataPasienNew2($_POST)); break;
    case "getDataPasiennew33":            echo json_encode($obj->getDataPasienNew33($_POST)); break;
    case "getDataPasienfull":             echo json_encode($obj->getDataPasienFull($_POST)); break;
    case "getDataPasienfull33":           echo json_encode($obj->getDataPasienFull33($_POST)); break;
    case "getDataPasienfull_baru":        echo json_encode($obj->getDataPasienFullBaru($_POST)); break;
    case "getDataPasienfull_baru_rrawat": echo json_encode($obj->getDataPasienFullBaruRuangRawat($_POST)); break;
    case "getDataPasienkeluar":           echo json_encode($obj->getDataPasienKeluar($_POST)); break;
    case "getDataPasienfull_baru_irna":   echo json_encode($obj->getDataPasienFullBaruIrna($_POST)); break;
    case "getDataPasienfull_op":          echo json_encode($obj->getDataPasienFullOp($_POST)); break;
    default:                              echo "";
}
