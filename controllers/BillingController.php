<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

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
class BillingController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/penjualan2.php#testbridge_baru    the original method
     * @see http://127.0.0.1/bridging/Bridging.php#getDataPasienFullBaru    the original method
     */
    public function actionSearchJsonPasien(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noPendaftaran" => $noPendaftaran,
            "namaPasien" => $namaPasien,
            "kelamin" => $kelamin,
            // "statusPasien" => $statusPasien, // not used anymore
        ] = Yii::$app->request->post();
        $namaPasien = $namaPasien ? strtoupper($namaPasien) : "";
        $kelamin = ["semua" => "", "laki-laki" => 1, "perempuan" => 0][$kelamin];
        // $status = ["semua" => "", "dirawat" => "dirawat", "keluar" => "keluar"][$statusPasien]; // not used anymore

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.NORM             AS kodeRekamMedis,
                B.NAMA             AS namaPasien,
                A.NO_PENDAFTARAN   AS noPendaftaran,
                A.TGL_DAFTAR       AS tanggalDaftar,
                E.CARABAYAR        AS caraBayar,
                G.NM_INST          AS namaInstalasi,
                H.NM_POLI          AS namaPoli,
                A.NO_SJP           AS ___, -- not used
                G.KD_KLP           AS ___, -- not used
                G.NICK_INST        AS ___, -- not used
                B.KELAMIN          AS kelamin, -- not used
                B.TGL_LAHIR        AS tanggalLahir, -- not used
                C.ALAMAT           AS alamat, -- not used
                C.NO_TELPON        AS noTelefon, -- not used
                E.KD_BAYAR         AS kodeBayar, -- not used
                F.JNS_CARABAYAR    AS jenisCaraBayar, -- not used
                F.KD_JNS_CARABAYAR AS kodeJenisCaraBayar, -- not used
                A.KD_INST          AS kodeInstalasi, -- not used
                A.KD_POLI          AS kodePoli, -- not used
                J.KD_KAMAR         AS kodeKamar, -- not used
                J.NM_KAMAR         AS namaKamar, -- not used
                K.KD_RRAWAT        AS kodeRuangRawat, -- not used
                K.NM_RRAWAT        AS namaRuangRawat, -- not used
                ''                 AS statusBilling
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

        foreach ($result as $i => $item) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.relasif_billing
                WHERE
                    no_pendaftaran = :noPendaftaran
                    AND verifikasi_billing != ''
                LIMIT 1
            ";
            $params = [":noPendaftaran" => $noPendaftaran];
            $item->statusBilling = $connection->createCommand($sql, $params)->queryScalar() ?? "";
        }
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/billing.php#listbilling    the original method
     */
    public function actionSearchJsonHanyaResepTransfer(): string
    {
        ["kodeRekamMedis" => $kodeRekamMedis, "noPendaftaran" => $noPendaftaran] = Yii::$app->request->post();
        if (is_null($kodeRekamMedis) || is_null($noPendaftaran)) throw new MissingPostParamException("kodeRekamMedis", "noPendaftaran");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. " 
            SELECT -- all are in use, confirmed with view file.
                b.no_resep                                          AS noResep,
                b.transfer                                          AS transfer,
                b.tgltransfer                                       AS tanggalTransfer,
                c.nm_kamar                                          AS namaKamar,
                SUM(b.harga * b.jlhPenjualan)                       AS jumlahTagihan,
                d.gabungan_kode                                     AS gabunganKode,
                IF(d.gabungan_kode != '', SUM(c.jasapelayanan), '') AS jasaPelayanan
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_penjualandetail AS c ON c.no_resep = b.no_resep
            LEFT JOIN db1.masterf_gabungan AS d ON d.no_resep = b.no_resep
            WHERE
                b.kode_rm = :kodeRekamMedis
                AND b.no_daftar = :noPendaftaran
                AND transfer != '' -- clause pembeda
            GROUP BY
                IF(d.gabungan_kode != '', d.gabungan_kode, b.no_resep)
            ORDER BY b.no_resep DESC
        ";
        $params = [":kodeRekamMedis" => $kodeRekamMedis, ":noPendaftaran" => $noPendaftaran];
        $daftarHanyaResepTransfer = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarHanyaResepTransfer);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/billing.php#listbilling    the original method
     */
    public function actionSearchJsonSemuaResep(): string
    {
        ["kodeRekamMedis" => $kodeRekamMedis, "noPendaftaran" => $noPendaftaran] = Yii::$app->request->post();
        if (is_null($kodeRekamMedis) || is_null($noPendaftaran)) throw new MissingPostParamException("kodeRekamMedis", "noPendaftaran");

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. " 
            SELECT -- all are in use, confirmed with view file.
                b.no_resep                                          AS noResep,
                b.transfer                                          AS transfer,
                b.tgltransfer                                       AS tanggalTransfer,
                c.nm_kamar                                          AS namaKamar,
                SUM(b.harga * b.jlhPenjualan)                       AS jumlahTagihan,
                d.gabungan_kode                                     AS gabunganKode,
                IF(d.gabungan_kode != '', SUM(c.jasapelayanan), '') AS jasaPelayanan
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_penjualandetail AS c ON c.no_resep = b.no_resep
            LEFT JOIN db1.masterf_gabungan AS d ON d.no_resep = b.no_resep
            WHERE
                b.kode_rm = :kodeRekamMedis
                AND b.no_daftar = :noPendaftaran
            GROUP BY
                IF(d.gabungan_kode != '', d.gabungan_kode, b.no_resep)
            ORDER BY
                IF(b.transfer != '', 1, 0), -- clause pembeda
                b.no_resep DESC
        ";
        $params = [":kodeRekamMedis" => $kodeRekamMedis, ":noPendaftaran" => $noPendaftaran];
        $daftarSemuaResep = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarSemuaResep);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/billing.php#verifikasibilling    the original method
     */
    public function actionVerifikasiBilling(): void
    {
        $noPendaftaran = Yii::$app->request->post("noPendaftaran");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT TRUE
            FROM db1.relasif_billing
            WHERE no_pendaftaran = :noPendaftaran
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $cekBilling = $connection->createCommand($sql, $params)->queryScalar();

        if ($cekBilling) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.relasif_billing
                SET verifikasi_billing = ''
                WHERE no_pendaftaran = :noPendaftaran
            ";
            $params = [":noPendaftaran" => $noPendaftaran];
            $connection->createCommand($sql, $params)->execute();

        } else {
            $nowValUser = Yii::$app->dateTime->nowVal("user");
            $username = Yii::$app->userFatma->username;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_billing
                SET
                    no_pendaftaran = :noPendaftaran,
                    verifikasi_billing = :verifikator,
                    tgl_verifikasi = :tanggal,
                    verifikasi_user = :verifikator
            ";
            $params = [":noPendaftaran" => $noPendaftaran, ":verifikator" => $username, ":tanggal" => $nowValUser];
            $connection->createCommand($sql, $params)->execute();
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/billing.php#verifikasibilling    the original method
     */
    public function actionUnverifikasiBilling(): void
    {
        $noPendaftaran = Yii::$app->request->post("noPendaftaran");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.relasif_billing
            SET verifikasi_billing = ''
            WHERE no_pendaftaran = :noPendaftaran
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $connection->createCommand($sql, $params)->execute();
    }
}
