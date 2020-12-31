<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

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
class PenjualanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/penjualan2.php#testbridge    the original method
     * @see http://127.0.0.1/bridging/Bridging.php#getDataPasienfull    the original method
     */
    public function actionTestBridge(): string
    {
        ["noPendaftaran" => $noPendaftaran, "kodeRekamMedis" => $kodeRekamMedis] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                A.NORM               AS kodeRekamMedis,
                B.NAMA               AS nama,
                A.NO_PENDAFTARAN     AS noPendaftaran,
                B.KELAMIN            AS kelamin,
                B.TGL_LAHIR          AS tanggalLahir,
                C.ALAMAT             AS alamat,
                C.NO_TELPON          AS noTelefon,
                E.KD_BAYAR           AS kodeBayar,
                E.CARABAYAR          AS caraBayar,
                F.JNS_CARABAYAR      AS jenisCaraBayar,
                F.KD_JNS_CARABAYAR   AS kodeJenisCaraBayar,
                A.KD_INST            AS kodeInstalasi,
                G.NM_INST            AS namaInstalasi,
                H.NM_POLI            AS namaPoli,
                A.KD_POLI            AS kodePoli,
                J.NM_KAMAR           AS namaKamar,
                K.KD_RRAWAT          AS kodeRuangRawat
            FROM db_his_live.TDFT_PENDAFTARAN A 
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON B.NORM = A.NORM
            LEFT JOIN db_his_live.TDFT_PASIEN_DEWASA C ON A.NORM = C.NORM
            LEFT JOIN rawatinapexist.TDFT_CRBYR_PASIEN1 D ON A.NO_PENDAFTARAN = D.NO_PENDAFTARAN
            LEFT JOIN sirsdev.MMAS_CARABAYAR E ON D.KD_BAYAR = E.KD_BAYAR
            LEFT JOIN sirsdev.MMAS_JNS_CRBYR F ON D.KD_JNS_CARABAYAR = F.KD_JNS_CARABAYAR
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            LEFT JOIN sirsdev.MDFT_POLI_SMF H ON H.KD_POLI = A.KD_POLI
            LEFT JOIN TDFT_RRAWAT_PASIEN I ON A.NO_PENDAFTARAN = I.NO_PENDAFTARAN
            LEFT JOIN rawatinapexist.MDFT_KAMAR J ON I.KD_KAMAR = J.KD_KAMAR
            LEFT JOIN rawatinapexist.MDFT_RRAWAT K ON I.KD_RRAWAT = K.KD_RRAWAT
            WHERE
                A.NO_PENDAFTARAN = :noPendaftaran
                AND A.NORM = :kodeRekamMedis
                AND A.KD_INST NOT IN ('08', '09', 10, 30, 23, 41, 44, 31)
                AND A.KD_INST = H.KD_INST
                AND F.KD_BAYAR = D.KD_BAYAR
            ORDER BY A.NO_PENDAFTARAN DESC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran, ":kodeRekamMedis" => $kodeRekamMedis];
        $result = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/penjualan2.php#testbridge_baru    the original method
     * @see http://202.137.25.13/bridging/Bridging.php#getDataPasienFullBaru    the original method
     */
    public function actionTestBridgeBaru(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noPendaftaran" => $noPendaftaran,
            "namaPasien" => $namaPasien,
            "kelamin" => $kelamin,
            // "statusPasien" => $statusPasien, // not used anymore
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NORM             AS kodeRekamMedis,
                B.NAMA             AS namaPasien,
                A.NO_PENDAFTARAN   AS noPendaftaran,
                A.TGL_DAFTAR       AS tanggalDaftar,
                B.KELAMIN          AS kelamin,
                B.TGL_LAHIR        AS tanggalLahir,
                C.ALAMAT           AS alamat,
                C.NO_TELPON        AS noTelefon,
                E.KD_BAYAR         AS kodeBayar,
                E.CARABAYAR        AS namaCaraBayar,
                F.JNS_CARABAYAR    AS jenisCaraBayar,
                F.KD_JNS_CARABAYAR AS kodeJenisCaraBayar,
                A.KD_INST          AS kodeInstalasi,
                G.NM_INST          AS namaInstalasi,
                H.NM_POLI          AS namaPoli,
                A.KD_POLI          AS kodePoli,
                J.NM_KAMAR         AS namaKamar,
                K.KD_RRAWAT        AS kodeRuangRawat,
                A.NO_SJP           AS ___, -- not used
                G.KD_KLP           AS ___, -- not used
                G.NICK_INST        AS ___, -- not used
                J.KD_KAMAR         AS kodeKamar, -- not used
                K.NM_RRAWAT        AS namaRuangRawat -- not used
            FROM db_his_live.TDFT_PENDAFTARAN A
            INNER JOIN db_his_live.TDFT_PASIEN_RM B ON B.NORM = A.NORM
            LEFT JOIN db_his_live.TDFT_PASIEN_DEWASA C ON A.NORM = C.NORM
            LEFT JOIN rawatinapexist.TDFT_CRBYR_PASIEN1 D ON A.NO_PENDAFTARAN = D.NO_PENDAFTARAN
            LEFT JOIN sirsdev.MMAS_CARABAYAR E ON D.KD_BAYAR = E.KD_BAYAR
            LEFT JOIN sirsdev.MMAS_JNS_CRBYR F ON D.KD_JNS_CARABAYAR = F.KD_JNS_CARABAYAR
            LEFT JOIN sirsdev.MDFT_INSTALASI G ON A.KD_INST = G.KD_INST
            LEFT JOIN sirsdev.MDFT_POLI_SMF H ON H.KD_POLI = A.KD_POLI
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
                AND A.KD_INST = H.KD_INST
                AND F.KD_BAYAR = D.KD_BAYAR
            ORDER BY A.NO_PENDAFTARAN ASC
        ";
        $params = [
            ":tanggalAwal" => $tanggalAwal ? date("Y-m-d", strtotime($tanggalAwal)) . " 00:00:00.000" : "",
            ":tanggalAkhir" => $tanggalAkhir ? date("Y-m-d", strtotime($tanggalAkhir)) . " 23:59:59.000" : "",
            ":kodeRekamMedis" => $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : "",
            ":noPendaftaran" => $noPendaftaran ? "%$noPendaftaran%" : "",
            ":nama" => $namaPasien ? "%$namaPasien%" : "",
            ":kelamin" => $kelamin ?? "",
            ":kodeInstalasi" => Yii::$app->userFatma->kodeKelompokInstalasiDepo ?? "",
        ];
        $result = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/penjualan2.php#testbridge_cekkeluar    the original method
     * @see http://202.137.25.13/bridging/Bridging.php#getDataPasienKeluar    the original method
     */
    public function actionTestBridgeCekKeluar(): string
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
        $params = [":noPendaftaran" => Yii::$app->request->post("q1")];
        $result = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/penjualan2.php#testbridge_baru_rrawat    the original method
     * @see http://202.137.25.13/bridging/Bridging.php#getDataPasienFullBaruRuangRawat    the original method
     */
    public function actionTestBridgeBaruRRawat(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                NO_TTIDUR  AS noTempatTidur,
                NM_KAMAR   AS namaKamar,
                NM_RRAWAT  AS namaRuangRawat,
                A.TGL_AWAL AS tanggalAwal
            FROM TDFT_RRAWAT_PASIEN A
            INNER JOIN rawatinapexist.MDFT_KAMAR J ON J.KD_KAMAR = A.KD_KAMAR
            INNER JOIN rawatinapexist.MDFT_RRAWAT K ON K.KD_RRAWAT = A.KD_RRAWAT
            INNER JOIN sirsdev.MDFT_INSTALASI G ON G.KD_INST = A.KD_INST
            WHERE
                A.NO_PENDAFTARAN = :noPendaftaran
                AND J.KD_KELAS = A.KD_KELAS
            LIMIT 10
        ";
        $params = [":noPendaftaran" => Yii::$app->request->post("q1")];
        $result = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/penjualan2.php#testbridge3    the original method
     * @see http://202.137.25.13/bridging/Bridging.php    the original method
     */
    public function actionTestBridge3(): string
    {
        [   "kodeRekamMedis" => $kodeRekamMedis,
            "noPendaftaran" => $noPendaftaran,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
        ] = Yii::$app->request->post();
        $tanggalDaftar = date("Y/m/d");
        $kodeInstalasi = Yii::$app->userFatma->kodeKelompokInstalasiDepo;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                A.NO_PENDAFTARAN AS noPendaftaran,
                A.NORM           AS kodeRekamMedis,
                A.TGL_DAFTAR     AS tanggalPendaftaran,
                B.NAMA           AS nama
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
            ":tanggalAwal" => date("Y-m-d", strtotime($tanggalAwal ?? $tanggalDaftar)) . " 00:00:00.000",
            ":tanggalAkhir" => date("Y-m-d", strtotime($tanggalAkhir ?? $tanggalDaftar)) . " 23:59:59.000",
            ":kodeInstalasi" => $kodeInstalasi ?? "",
        ];
        $result = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/penjualan2.php#testbridge33    the original method
     * @see http://202.137.25.13/bridging/Bridging.php    the original method
     */
    public function actionTestBridge33(): string
    {
        [   "tanggalPendaftaran" => $tanggalPendaftaran,
            "nama" => $nama,
            "noPendaftaran" => $noPendaftaran,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                A.NO_PENDAFTARAN AS noPendaftaran,
                A.NORM           AS kodeRekamMedis,
                B.NAMA           AS nama
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
        $result = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($result);
    }
}
