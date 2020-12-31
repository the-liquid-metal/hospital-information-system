<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\ResepGabungan\{
    CetakNoResep,
    CetakNoResepLq,
    CetakObat,
    CetakObatLq,
    PrintGabunganNew,
    PrintGabunganNewLq,
    ViewKumpulanResep
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
class ResepGabunganController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#deletegabungan    the original method
     */
    public function actionDeleteGabungan(): void
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_gabungan
            WHERE gabungan_kode = :gabunganKode
        ";
        $params = [":gabunganKode" => Yii::$app->request->post("id")];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#listresep2    the original method
     */
    public function actionTableResepData(): string
    {
        $kodeRekamMedis = Yii::$app->request->post("kodeRekamMedis") ?? throw new MissingPostParamException("kodeRekamMedis");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id_gabungan    AS idGabungan,
                a.gabungan_kode  AS gabunganKode,
                a.no_resep       AS noResep,
                a.nama_pasien    AS namaPasien,
                a.tanggal_gabung AS tanggalGabung,
                SUM(b.harga)     AS jumlahTagihan,
                ''               AS total
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            WHERE b.kode_rm = :kodeRekamMedis
            GROUP BY a.gabungan_kode
            ORDER BY a.gabungan_kode DESC
        ";
        $params = [":kodeRekamMedis" => $kodeRekamMedis];
        $daftarResep1 = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep1 as $j => $resep1) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT SUM(c.jasapelayanan)
                FROM db1.masterf_gabungan a
                INNER JOIN db1.masterf_penjualandetail c ON c.no_resep = a.no_resep
                WHERE a.gabungan_kode = :gabunganKode
            ";
            $params = [":gabunganKode" => $resep1->gabungan_kode];
            $gettotal2 = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    SUM(b.jasapelayanan) AS totaljp,
                    SUM(b.totalharga)    AS totaljual
                FROM (
                    SELECT
                        CASE
                            WHEN a.total <= 0 THEN 0
                            WHEN a.total > 0 AND (a.id_racik = '' OR a.id_racik = 0) THEN 300
                        END AS jasapelayanan,
                        a.totalharga,
                        a.gabungan_kode
                    FROM (
                        SELECT
                            SUM(jlhPenjualan)         AS total,
                            id_racik                  AS id_racik,
                            SUM(jlhPenjualan * harga) AS totalharga,
                            gabungan_kode             AS gabungan_kode
                        FROM db1.masterf_penjualan mp
                        INNER JOIN db1.masterf_gabungan mg ON mg.no_resep = mp.no_resep
                        WHERE gabungan_kode = :gabunganKode
                        GROUP BY kodeObat
                        ORDER BY kodeObat ASC
                    ) AS a
                ) AS b
                GROUP BY b.gabungan_kode
            ";
            $params = [":gabunganKode" => $resep1->gabungan_kode];
            $getjp = $connection->createCommand($sql, $params)->queryOne();

            $totalceil = ceil($getjp->totaljual / 100) * 100;
            $resep1->total = $getjp->totaljual + (floor($gettotal2 / 100) * 100) + ($totalceil - $getjp->totaljual);
        }

        // =============================================================================================================
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
                a.jlhPenjualan           AS jumlahPenjualan,
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
                a.tglbuat                AS tanggalBuat,
                a.signa1                 AS signa1,
                a.signa2                 AS signa2,
                a.signa3                 AS signa3,
                a.dokter_perobat         AS dokterPerObat,
                a.bayar                  AS bayar,
                a.tglbayar               AS tanggalBayar,
                a.checking_ketersediaan  AS cekKetersediaan,
                a.keteranganobat         AS keteranganObat,
                a.kode_drperobat         AS kodeDokterPerObat,
                a.kode_operator          AS kodeOperator,
                a.kode_verifikasi        AS kodeVerifikasi,
                a.kode_transfer          AS kodeTransfer,
                f.gabungan_kode          AS gabunganKode,
                c.idPenjualandetail      AS idPenjualanDetail,
                c.no_resep               AS noResep,
                c.tglResep1              AS tanggalResep1,
                c.tglResep2              AS tanggalResep2,
                c.jenisResep             AS jenisResep,
                c.dokter                 AS dokter,
                c.pembayaran             AS pembayaran,
                c.namaInstansi           AS namaInstansi,
                c.namaPoli               AS namaPoli,
                c.keterangan             AS keterangan,
                c.totaldiskon            AS totalDiskon,
                c.totalpembungkus        AS totalPembungkus,
                c.jasapelayanan          AS jasaPelayanan,
                c.total                  AS total,
                c.bayar                  AS bayar,
                c.kembali                AS kembali,
                c.total_retur            AS totalRetur,
                c.iter                   AS iter1,
                c.iter2                  AS iter2,
                c.nm_kamar               AS namaKamar,
                c.KD_BAYAR               AS kodeBayar,
                c.KD_INST                AS kodeInstalasi,
                c.KD_POLI                AS kodePoli,
                c.KD_RRAWAT              AS kodeRuangRawat,
                c.KD_JENIS_CARABAYAR     AS kodeJenisCaraBayar,
                c.JNS_CARABAYAR          AS jenisCaraBayar,
                c.CARA_PEMBAYARAN        AS caraPembayaran,
                c.CARA_PEMBAYARAN_DETAIL AS caraPembayaranDetail,
                c.NOMOR_KARTU            AS noKartu,
                c.TGL_DAFTAR             AS tanggalPendaftaran,
                c.atasnama               AS atasNama,
                c.pembayaran             AS caraPembayaran
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            LEFT JOIN db1.masterf_gabungan AS f ON f.no_resep = a.no_resep
            WHERE
                a.no_resep != ''
                AND a.kode_rm = :kodeRekamMedis
                AND a.kode_depo = :idDepo
                AND (
                    (tglPenjualan >= :tanggalPenjualan1)
                    OR (
                        tglPenjualan >= :tanggalPenjualan2
                        AND (
                            a.kode_depo = 'DEPO019'
                            OR a.kode_depo = 'DEPO007'
                            OR a.kode_depo = 'DEPO004'
                            OR a.kode_depo = 'DEPO002'
                        )
                    )
                )
            GROUP BY c.no_resep
            ORDER BY c.no_resep DESC
        ";
        $params = [
            ":kodeRekamMedis" => $kodeRekamMedis,
            ":idDepo" => Yii::$app->userFatma->idDepo,
            ":tanggalPenjualan1" => date("Y-m") . "-01",
            ":tanggalPenjualan2" => date("Y") . "-01-20",
        ];
        $daftarResep2 = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep2 as $j => $resep2) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT NM_INST
                FROM db1.masterf_kode_inst
                WHERE KD_INST = :kodeInstalasi
                LIMIT 1
            ";
            $params = [":kodeInstalasi" => $resep2->KD_INST];
            $getinstalasi1 = $connection->createCommand($sql, $params)->queryScalar();
            $resep2->nm_kamar = ($getinstalasi1 ? $getinstalasi1 . ", " : "") . $resep2->nm_kamar;
        }

        return json_encode(["daftarResep1" => $daftarResep1, "daftarResep2" => $daftarResep2]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#caripasien    the original method
     */
    public function actionCariPasien(): string
    {
        assert($_POST["q"], new MissingPostParamException("q"));
        ["q" => $val] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT DISTINCT -- all are in use.
                kode_rm     AS kodeRekamMedis,
                nama_pasien AS namaPasien
            FROM db1.masterf_penjualan
            WHERE
                kode_rm LIKE :val
                OR nama_pasien LIKE :val
            ORDER BY
                CASE
                    WHEN kode_rm LIKE :val THEN 1
                    WHEN nama_pasien LIKE :val THEN 1
                    WHEN kode_rm LIKE :val THEN 3
                    WHEN nama_pasien LIKE :val THEN 3
                    ELSE 2
                END
        ";
        $params = [":val" => "%$val%"];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPenjualan);
    }

    /**
     * pinjam print gabungan untuk print LQ
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#print_gabungan    the original method
     */
    public function actionPrintGabungan(): string
    {
        $gabunganKode = Yii::$app->request->post("gabunganKode") ?? throw new MissingPostParamException("gabunganKode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id_gabungan       AS idGabungan,
                a.gabungan_kode     AS gabunganKode,
                a.no_resep          AS noResep,
                a.nama_pasien       AS namaPasien,
                a.tanggal_gabung    AS tanggalGabung,
                ee.KD_INST          AS kodeInstalasi,
                SUM(b.jlhPenjualan) AS totalJumlah
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                a.gabungan_kode = :gabunganKode
                AND b.nama_pasien != ''
            GROUP BY b.kodeObat
            ORDER BY a.gabungan_kode, b.kodeObat DESC
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT tglverifikasi
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                a.gabungan_kode = :gabunganKode
                AND b.nama_pasien != ''
            ORDER BY b.tglPenjualan DESC
            LIMIT 1
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $tanggalAkhir = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT tglverifikasi
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                a.gabungan_kode = :gabunganKode
                AND b.nama_pasien != ''
            ORDER BY b.tglPenjualan ASC
            LIMIT 1
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $tanggalAwal = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id_gabungan     AS idGabungan,
                a.gabungan_kode   AS gabunganKode,
                a.no_resep        AS noResep,       -- in use
                a.nama_pasien     AS namaPasien,
                a.tanggal_gabung  AS tanggalGabung,
                b.kode_racik      AS kodeRacik,     -- in use
                c.nama_barang     AS namaBarang,    -- in use
                ee.jasapelayanan  AS jasaPelayanan, -- in use
                b.signa           AS signa,         -- in use
                SUM(jlhPenjualan) AS totalJumlah,   -- in use
                dd.nama           AS namaSigna,     -- in use
                b.harga           AS harga          -- in use
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.master_signa AS dd ON dd.kode = b.signa
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE a.gabungan_kode = :gabunganKode
            GROUP BY b.kodeObat
            ORDER BY c.nama_barang ASC
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $total = 0;
        $totalJasa = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 20;
        $noResepSaatIni = "";
        $kodeRacikSaatIni = "";
        $noRacik = 1;
        $noObat = 1;

        foreach ($daftarObat as $obat) {
            $obat->namaSigna ??= $obat->signa;

            if ($obat->kodeRacik) {
                if ($kodeRacikSaatIni != $obat->kodeRacik) {
                    $kodeRacikSaatIni = $obat->kodeRacik;
                    $noRacik = 1;
                    $noObat++;
                }
                $noRacik++;
            } else {
                $noObat++;
            }

            $total += $obat->totalJumlah * $obat->harga;

            if ($noResepSaatIni != $obat->noResep) {
                $noResepSaatIni = $obat->noResep;
                $totalJasa += $obat->jasaPelayanan;
            }

            $daftarHalaman[$h][$b] = [
                "nama_barang" => $obat->namaBarang,
                "jumlah" => $obat->totalJumlah,
                "subtotal_harga" => ceil($obat->harga * $obat->totalJumlah)
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
            SELECT SUM(b.totalharga)
            FROM (
                SELECT
                    a.totalharga,
                    a.gabungan_kode
                FROM (
                    SELECT
                        SUM(jlhPenjualan * harga) AS totalharga,
                        gabungan_kode             AS gabungan_kode
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_gabungan AS mg ON mg.no_resep = mp.no_resep
                    WHERE gabungan_kode = :gabunganKode
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.gabungan_kode
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $totalJual = (int) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT SUM(c.jasapelayanan)
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            WHERE a.gabungan_kode = :gabunganKode
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $jumlahLayanan = $connection->createCommand($sql, $params)->queryScalar();

        $totalCeil = ceil($totalJual / 100) * 100;
        $totalJasa = (floor($jumlahLayanan / 100) * 100) + $totalCeil - $totalJual;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_INST
            FROM db1.masterf_kode_inst
            WHERE KD_INST = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = (string) $connection->createCommand($sql, $params)->queryScalar();

        $view = new PrintGabunganNewLq(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaInstalasi: $namaInstalasi,
            namaDepo:      Yii::$app->userFatma->namaDepo,
            tanggalAwal:   $tanggalAwal,
            tanggalAkhir:  $tanggalAkhir,
            jumlahHalaman: count($daftarHalaman),
            totalJual:     $totalJual,
            totalJasa:     $totalJasa,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#print_kumpulanresep    the original method
     */
    public function actionPrintKumpulanResep(): string
    {
        $noPendaftaran = Yii::$app->request->post("noPendaftaran") ?? throw new MissingPostParamException("noPendaftaran");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.kode                  AS kode,
                b.no_resep              AS noResep,
                b.no_penjualan          AS noPenjualan,
                b.diskon                AS diskon,
                b.jasa                  AS jasa,
                b.kodePenjualan         AS kodePenjualan,
                b.kode_rm               AS kodeRekamMedis,
                b.no_daftar             AS noPendaftaran,
                b.nama_pasien           AS namaPasien,
                b.kodeObat              AS kodeObat,
                b.kodeObatdr            AS kodeObatDokter,
                b.nama_obatdr           AS namaObatDokter,
                b.urutan                AS urutan,
                b.jlhPenjualan          AS jumlahPenjualan,
                b.jlhPenjualandr        AS jumlahPenjualanDokter,
                b.signa                 AS signa,
                b.hna                   AS hna,
                b.hp                    AS hp,
                b.harga                 AS harga,
                b.id_racik              AS idRacik,
                b.kode_racik            AS kodeRacik,
                b.nama_racik            AS namaRacik,
                b.no_racik              AS noRacik,
                b.ketjumlah             AS keteranganJumlah,
                b.keterangan_obat       AS keteranganObat,
                b.kode_depo             AS kodeDepo,
                b.ranap                 AS rawatInap,
                b.tglPenjualan          AS tanggalPenjualan,
                b.lunas                 AS lunas,
                b.verifikasi            AS verifikasi,
                b.transfer              AS transfer,
                b.resep                 AS resep,
                b.tglverifikasi         AS tanggalVerifikasi,
                b.tgltransfer           AS tanggalTransfer,
                b.operator              AS operator,
                b.tglbuat               AS tanggalBuat,
                b.signa1                AS signa1,
                b.signa2                AS signa2,
                b.signa3                AS signa3,
                b.dokter_perobat        AS dokterPerObat,
                b.bayar                 AS bayar,
                b.tglbayar              AS tanggalBayar,
                b.checking_ketersediaan AS cekKetersediaan,
                b.keteranganobat        AS keteranganObat,
                b.kode_drperobat        AS kodeDokterPerObat,
                b.kode_operator         AS kodeOperator,
                b.kode_verifikasi       AS kodeVerifikasi,
                b.kode_transfer         AS kodeTransfer,
                ee.KD_INST              AS kodeInstalasi,
                SUM(jlhPenjualan)       AS totalJumlah
            FROM  db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.nama_pasien != ''
                AND b.verifikasi != ''
            GROUP BY b.kodeObat
            ORDER BY b.kode_rm, b.kodeObat DESC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT tglverifikasi
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.nama_pasien != ''
                AND b.verifikasi != ''
            ORDER BY b.tglPenjualan DESC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $tanggalAkhir = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT tglverifikasi
            FROM  db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.nama_pasien != ''
                AND b.verifikasi != ''
            ORDER BY b.tglPenjualan ASC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $tanggalAwal = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.kode                  AS kode,
                b.no_resep              AS noResep,
                b.no_penjualan          AS noPenjualan,
                b.diskon                AS diskon,
                b.jasa                  AS jasa,
                b.kodePenjualan         AS kodePenjualan,
                b.kode_rm               AS kodeRekamMedis,
                b.no_daftar             AS noPendaftaran,
                b.nama_pasien           AS namaPasien,
                b.kodeObat              AS kodeObat,
                b.kodeObatdr            AS kodeObatDokter,
                b.nama_obatdr           AS namaObatDokter,
                b.urutan                AS urutan,
                b.jlhPenjualan          AS jumlahPenjualan,
                b.jlhPenjualandr        AS jumlahPenjualanDokter,
                b.signa                 AS signa,
                b.hna                   AS hna,
                b.hp                    AS hp,
                b.harga                 AS harga,
                b.id_racik              AS idRacik,
                b.kode_racik            AS kodeRacik,
                b.nama_racik            AS namaRacik,
                b.no_racik              AS noRacik,
                b.ketjumlah             AS keteranganJumlah,
                b.keterangan_obat       AS keteranganObat,
                b.kode_depo             AS kodeDepo,
                b.ranap                 AS rawatInap,
                b.tglPenjualan          AS tanggalPenjualan,
                b.lunas                 AS lunas,
                b.verifikasi            AS verifikasi,
                b.transfer              AS transfer,
                b.resep                 AS resep,
                b.tglverifikasi         AS tanggalVerifikasi,
                b.tgltransfer           AS tanggalTransfer,
                b.operator              AS operator,
                b.tglbuat               AS tanggalBuat,
                b.signa1                AS signa1,
                b.signa2                AS signa2,
                b.signa3                AS signa3,
                b.dokter_perobat        AS dokterPerObat,
                b.bayar                 AS bayar,
                b.tglbayar              AS tanggalBayar,
                b.checking_ketersediaan AS cekKetersediaan,
                b.keteranganobat        AS keteranganObat,
                b.kode_drperobat        AS kodeDokterPerObat,
                b.kode_operator         AS kodeOperator,
                b.kode_verifikasi       AS kodeVerifikasi,
                b.kode_transfer         AS kodeTransfer,
                SUM(b.jlhPenjualan)     AS totalJumlah,
                dd.nama                 AS namaSigna
            FROM  db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.master_signa AS dd ON dd.kode = b.signa
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE b.no_daftar = :noPendaftaran
            GROUP BY b.kodeObat
            ORDER BY c.nama_barang ASC
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                ee.total                      AS total,
                SUM(ee.total)                 AS grandTotal,
                (ee.total - ee.jasapelayanan) AS total2,
                b.no_resep                    AS noResep,
                b.transfer                    AS transfer,
                b.tgltransfer                 AS tanggalTransfer
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.transfer != ''
            GROUP BY b.no_resep
            ORDER BY b.no_resep ASC
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $daftarPenjualanPerResep = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_INST
            FROM db1.masterf_kode_inst
            WHERE KD_INST = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = (string) $connection->createCommand($sql, $params)->queryScalar();

        $view = new ViewKumpulanResep(
            pasien:                  $pasien,
            namaInstalasi:           $namaInstalasi,
            namaDepo:                Yii::$app->userFatma->namaDepo,
            tanggalAwal:             $tanggalAwal,
            tanggalAkhir:            $tanggalAkhir,
            daftarObat:              $daftarObat,
            daftarPenjualanPerResep: $daftarPenjualanPerResep,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#cetakobat    the original method
     */
    public function actionCetakObat(): string
    {
        $noPendaftaran = Yii::$app->request->post("noPendaftaran") ?? throw new MissingPostParamException("noPendaftaran");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.kode                  AS kode,
                b.no_resep              AS noResep,
                b.no_penjualan          AS noPenjualan,
                b.diskon                AS diskon,
                b.jasa                  AS jasa,
                b.kodePenjualan         AS kodePenjualan,
                b.kode_rm               AS kodeRekamMedis,
                b.no_daftar             AS noPendaftaran,
                b.nama_pasien           AS namaPasien,
                b.kodeObat              AS kodeObat,
                b.kodeObatdr            AS kodeObatDokter,
                b.nama_obatdr           AS namaObatDokter,
                b.urutan                AS urutan,
                b.jlhPenjualan          AS jumlahPenjualan,
                b.jlhPenjualandr        AS jumlahPenjualanDokter,
                b.signa                 AS signa,
                b.hna                   AS hna,
                b.hp                    AS hp,
                b.harga                 AS harga,
                b.id_racik              AS idRacik,
                b.kode_racik            AS kodeRacik,
                b.nama_racik            AS namaRacik,
                b.no_racik              AS noRacik,
                b.ketjumlah             AS keteranganJumlah,
                b.keterangan_obat       AS keteranganObat,
                b.kode_depo             AS kodeDepo,
                b.ranap                 AS rawatInap,
                b.tglPenjualan          AS tanggalPenjualan,
                b.lunas                 AS lunas,
                b.verifikasi            AS verifikasi,
                b.transfer              AS transfer,
                b.resep                 AS resep,
                b.tglverifikasi         AS tanggalVerifikasi,
                b.tgltransfer           AS tanggalTransfer,
                b.operator              AS operator,
                b.tglbuat               AS tanggalBuat,
                b.signa1                AS signa1,
                b.signa2                AS signa2,
                b.signa3                AS signa3,
                b.dokter_perobat        AS dokterPerObat,
                b.bayar                 AS bayar,
                b.tglbayar              AS tanggalBayar,
                b.checking_ketersediaan AS cekKetersediaan,
                b.keteranganobat        AS keteranganObat,
                b.kode_drperobat        AS kodeDokterPerObat,
                b.kode_operator         AS kodeOperator,
                b.kode_verifikasi       AS kodeVerifikasi,
                b.kode_transfer         AS kodeTransfer,
                SUM(jlhPenjualan)       AS totalJumlah
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.nama_pasien != ''
                AND b.verifikasi != ''
            GROUP BY b.kodeObat
            ORDER BY b.kode_rm, b.kodeObat DESC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.kode                    AS kode,
                b.no_resep                AS noResep,
                b.no_penjualan            AS noPenjualan,
                b.diskon                  AS diskon,
                b.jasa                    AS jasa,
                b.kodePenjualan           AS kodePenjualan,
                b.kode_rm                 AS kodeRekamMedis,
                b.no_daftar               AS noPendaftaran,
                b.nama_pasien             AS namaPasien,
                b.kodeObat                AS kodeObat,
                b.kodeObatdr              AS kodeObatDokter,
                b.nama_obatdr             AS namaObatDokter,
                b.urutan                  AS urutan,
                b.jlhPenjualan            AS jumlahPenjualan,
                b.jlhPenjualandr          AS jumlahPenjualanDokter,
                b.signa                   AS signa,
                b.hna                     AS hna,
                b.hp                      AS hp,
                b.harga                   AS harga,                 -- in use
                b.id_racik                AS idRacik,               -- in use
                b.kode_racik              AS kodeRacik,
                b.nama_racik              AS namaRacik,
                b.no_racik                AS noRacik,
                b.ketjumlah               AS keteranganJumlah,
                b.keterangan_obat         AS keteranganObat,
                b.kode_depo               AS kodeDepo,
                b.ranap                   AS rawatInap,
                b.tglPenjualan            AS tanggalPenjualan,
                b.lunas                   AS lunas,
                b.verifikasi              AS verifikasi,
                b.transfer                AS transfer,
                b.resep                   AS resep,
                b.tglverifikasi           AS tanggalVerifikasi,
                b.tgltransfer             AS tanggalTransfer,
                b.operator                AS operator,
                b.tglbuat                 AS tanggalBuat,
                b.signa1                  AS signa1,
                b.signa2                  AS signa2,
                b.signa3                  AS signa3,
                b.dokter_perobat          AS dokterPerObat,
                b.bayar                   AS bayar,
                b.tglbayar                AS tanggalBayar,
                b.checking_ketersediaan   AS cekKetersediaan,
                b.keteranganobat          AS keteranganObat,
                b.kode_drperobat          AS kodeDokterPerObat,
                b.kode_operator           AS kodeOperator,
                b.kode_verifikasi         AS kodeVerifikasi,
                b.kode_transfer           AS kodeTransfer,
                SUM(jlhPenjualan)         AS totalJumlah,           -- in use
                dd.nama                   AS namaSigna,
                IFNULL(mm.NM_RRAWAT, '-') AS namaRuangRawat,        -- in use
                IFNULL(mm.KD_RRAWAT, '-') AS kodeRuangRawat,        -- in use
                c.nama_barang             AS namaBarang             -- in use
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.master_signa AS dd ON dd.kode = b.signa
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            LEFT JOIN db1.masterf_kode_rrawat AS mm ON mm.KD_RRAWAT = ee.KD_RRAWAT
            WHERE b.no_daftar = :noPendaftaran
            GROUP BY b.kodeObat
            ORDER BY mm.NM_RRAWAT, c.nama_barang ASC
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 34;
        $ruangSaatIni = "";

        foreach ($daftarObat as $key => $obat) {
            $tempHalaman = [];
            if ($ruangSaatIni != $obat->kodeRuangRawat) {
                $ruangSaatIni = $obat->kodeRuangRawat;
                $tempHalaman["ruang"] = $obat->namaRuangRawat;

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = array_merge($tempHalaman, [
                "no" => $key+1,
                "nama_barang" =>$obat->namaBarang,
                "jumlah" => $obat->totalJumlah,
                "id_racik" => $obat->idRacik,
                "subtotal_harga" => ceil($obat->harga * $obat->totalJumlah)
            ]);

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
            WHERE KD_INST = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->KD_INST];
        $instalasi = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $view = new CetakObat(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaDepo:      Yii::$app->userFatma->namaDepo,
            instalasi:     $instalasi,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#cetakobatlq    the original method
     */
    public function actionCetakObatLq(): string
    {
        $noPendaftaran = Yii::$app->request->post("noPendaftaran") ?? throw new MissingPostParamException("noPendaftaran");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.kode                  AS kode,
                b.no_resep              AS noResep,
                b.no_penjualan          AS noPenjualan,
                b.diskon                AS diskon,
                b.jasa                  AS jasa,
                b.kodePenjualan         AS kodePenjualan,
                b.kode_rm               AS kodeRekamMedis,
                b.no_daftar             AS noPendaftaran,
                b.nama_pasien           AS namaPasien,
                b.kodeObat              AS kodeObat,
                b.kodeObatdr            AS kodeObatDokter,
                b.nama_obatdr           AS namaObatDokter,
                b.urutan                AS urutan,
                b.jlhPenjualan          AS jumlahPenjualan,
                b.jlhPenjualandr        AS jumlahPenjualanDokter,
                b.signa                 AS signa,
                b.hna                   AS hna,
                b.hp                    AS hp,
                b.harga                 AS harga,
                b.id_racik              AS idRacik,
                b.kode_racik            AS kodeRacik,
                b.nama_racik            AS namaRacik,
                b.no_racik              AS noRacik,
                b.ketjumlah             AS keteranganJumlah,
                b.keterangan_obat       AS keteranganObat,
                b.kode_depo             AS kodeDepo,
                b.ranap                 AS rawatInap,
                b.tglPenjualan          AS tanggalPenjualan,
                b.lunas                 AS lunas,
                b.verifikasi            AS verifikasi,
                b.transfer              AS transfer,
                b.resep                 AS resep,
                b.tglverifikasi         AS tanggalVerifikasi,
                b.tgltransfer           AS tanggalTransfer,
                b.operator              AS operator,
                b.tglbuat               AS tanggalBuat,
                b.signa1                AS signa1,
                b.signa2                AS signa2,
                b.signa3                AS signa3,
                b.dokter_perobat        AS dokterPerObat,
                b.bayar                 AS bayar,
                b.tglbayar              AS tanggalBayar,
                b.checking_ketersediaan AS cekKetersediaan,
                b.keteranganobat        AS keteranganObat,
                b.kode_drperobat        AS kodeDokterPerObat,
                b.kode_operator         AS kodeOperator,
                b.kode_verifikasi       AS kodeVerifikasi,
                b.kode_transfer         AS kodeTransfer,
                SUM(b.jlhPenjualan)     AS totalJumlah
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.nama_pasien != ''
                AND b.verifikasi != ''
            GROUP BY b.kodeObat
            ORDER BY b.kode_rm, b.kodeObat DESC
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.kode                    AS kode,
                b.no_resep                AS noResep,
                b.no_penjualan            AS noPenjualan,
                b.diskon                  AS diskon,
                b.jasa                    AS jasa,
                b.kodePenjualan           AS kodePenjualan,
                b.kode_rm                 AS kodeRekamMedis,
                b.no_daftar               AS noPendaftaran,
                b.nama_pasien             AS namaPasien,
                b.kodeObat                AS kodeObat,
                b.kodeObatdr              AS kodeObatDokter,
                b.nama_obatdr             AS namaObatDokter,
                b.urutan                  AS urutan,
                b.jlhPenjualan            AS jumlahPenjualan,
                b.jlhPenjualandr          AS jumlahPenjualanDokter,
                b.signa                   AS signa,
                b.hna                     AS hna,
                b.hp                      AS hp,
                b.harga                   AS harga,                 -- in use
                b.id_racik                AS idRacik,               -- in use
                b.kode_racik              AS kodeRacik,
                b.nama_racik              AS namaRacik,
                b.no_racik                AS noRacik,
                b.ketjumlah               AS keteranganJumlah,
                b.keterangan_obat         AS keteranganObat,
                b.kode_depo               AS kodeDepo,
                b.ranap                   AS rawatInap,
                b.tglPenjualan            AS tanggalPenjualan,
                b.lunas                   AS lunas,
                b.verifikasi              AS verifikasi,
                b.transfer                AS transfer,
                b.resep                   AS resep,
                b.tglverifikasi           AS tanggalVerifikasi,
                b.tgltransfer             AS tanggalTransfer,
                b.operator                AS operator,
                b.tglbuat                 AS tanggalBuat,
                b.signa1                  AS signa1,
                b.signa2                  AS signa2,
                b.signa3                  AS signa3,
                b.dokter_perobat          AS dokterPerObat,
                b.bayar                   AS bayar,
                b.tglbayar                AS tanggalBayar,
                b.checking_ketersediaan   AS cekKetersediaan,
                b.keteranganobat          AS keteranganObat,
                b.kode_drperobat          AS kodeDokterPerObat,
                b.kode_operator           AS kodeOperator,
                b.kode_verifikasi         AS kodeVerifikasi,
                b.kode_transfer           AS kodeTransfer,
                SUM(jlhPenjualan)         AS totalJumlah,           -- in use
                dd.nama                   AS namaSigna,
                IFNULL(mm.NM_RRAWAT, '-') AS namaRuangRawat,        -- in use
                IFNULL(mm.KD_RRAWAT, '-') AS kodeRuangRawat,        -- in use
                c.nama_barang             AS namaBarang             -- in use
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.master_signa AS dd ON dd.kode = b.signa
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            LEFT JOIN db1.masterf_kode_rrawat AS mm ON mm.KD_RRAWAT = ee.KD_RRAWAT
            WHERE b.no_daftar = :noPendaftaran
            GROUP BY b.kodeObat
            ORDER BY mm.NM_RRAWAT, c.nama_barang ASC
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;
        $ruangSaatIni = "";

        foreach ($daftarObat as $key => $obat) {
            $tempHalaman = [];
            if ($ruangSaatIni != $obat->kodeRuangRawat) {
                $ruangSaatIni = $obat->kodeRuangRawat;
                $tempHalaman["ruang"] = $obat->namaRuangRawat;

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = array_merge($tempHalaman, [
                "no" => $key+1,
                "nama_barang" => $obat->namaBarang,
                "jumlah" => $obat->totalJumlah,
                "id_racik" => $obat->idRacik,
                "subtotal_harga" => ceil($obat->harga * $obat->totalJumlah)
            ]);

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
            WHERE KD_INST = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->KD_INST];
        $instalasi = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $view = new CetakObatLq(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaDepo:      Yii::$app->userFatma->namaDepo,
            instalasi:     $instalasi,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#cetaknoresep    the original method
     */
    public function actionCetakNoResep(): string
    {
        $noPendaftaran = Yii::$app->request->post("noPendaftaran") ?? throw new MissingPostParamException("noPendaftaran");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.kode                  AS kode,
                b.no_resep              AS noResep,
                b.no_penjualan          AS noPenjualan,
                b.diskon                AS diskon,
                b.jasa                  AS jasa,
                b.kodePenjualan         AS kodePenjualan,
                b.kode_rm               AS kodeRekamMedis,
                b.no_daftar             AS noPendaftaran,
                b.nama_pasien           AS namaPasien,
                b.kodeObat              AS kodeObat,
                b.kodeObatdr            AS kodeObatDokter,
                b.nama_obatdr           AS namaObatDokter,
                b.urutan                AS urutan,
                b.jlhPenjualan          AS jumlahPenjualan,
                b.jlhPenjualandr        AS jumlahPenjualanDokter,
                b.signa                 AS signa,
                b.hna                   AS hna,
                b.hp                    AS hp,
                b.harga                 AS harga,
                b.id_racik              AS idRacik,
                b.kode_racik            AS kodeRacik,
                b.nama_racik            AS namaRacik,
                b.no_racik              AS noRacik,
                b.ketjumlah             AS keteranganJumlah,
                b.keterangan_obat       AS keteranganObat,
                b.kode_depo             AS kodeDepo,
                b.ranap                 AS rawatInap,
                b.tglPenjualan          AS tanggalPenjualan,
                b.lunas                 AS lunas,
                b.verifikasi            AS verifikasi,
                b.transfer              AS transfer,
                b.resep                 AS resep,
                b.tglverifikasi         AS tanggalVerifikasi,
                b.tgltransfer           AS tanggalTransfer,
                b.operator              AS operator,
                b.tglbuat               AS tanggalBuat,
                b.signa1                AS signa1,
                b.signa2                AS signa2,
                b.signa3                AS signa3,
                b.dokter_perobat        AS dokterPerObat,
                b.bayar                 AS bayar,
                b.tglbayar              AS tanggalBayar,
                b.checking_ketersediaan AS cekKetersediaan,
                b.keteranganobat        AS keteranganObat,
                b.kode_drperobat        AS kodeDokterPerObat,
                b.kode_operator         AS kodeOperator,
                b.kode_verifikasi       AS kodeVerifikasi,
                b.kode_transfer         AS kodeTransfer,
                ee.KD_INST              AS kodeInstalasi,
                SUM(b.jlhPenjualan)     AS totalJumlah
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.nama_pasien != ''
                AND b.verifikasi != ''
            GROUP BY b.kodeObat
            ORDER BY b.kode_rm, b.kodeObat DESC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                ee.total                      AS total,          -- in use
                SUM(ee.total)                 AS grandTotal,
                (ee.total - ee.jasapelayanan) AS total2,
                b.no_resep                    AS noResep,        -- in use
                b.transfer                    AS transfer,       -- in use
                b.tgltransfer                 AS tanggalTransfer -- in use
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.transfer != ''
            GROUP BY b.no_resep
            ORDER BY b.no_resep ASC
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $daftarPenjualanPerResep = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 34;

        foreach ($daftarPenjualanPerResep as $resep) {
            $daftarHalaman[$h][$b] = [
                "no_resep" => $resep->noResep,
                "transfer" => $resep->transfer,
                "tgltransfer" => $resep->tanggalTransfer,
                "total" => $resep->total
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
            WHERE KD_INST = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = (string) $connection->createCommand($sql, $params)->queryScalar();

        $view = new CetakNoResep(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaInstalasi: $namaInstalasi,
            namaDepo:      Yii::$app->userFatma->namaDepo,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#cetaknoreseplq    the original method
     */
    public function actionCetakNoResepLq(): string
    {
        $noPendaftaran = Yii::$app->request->post("noPendaftaran") ?? throw new MissingPostParamException("noPendaftaran");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                b.kode                  AS kode,
                b.no_resep              AS noResep,
                b.no_penjualan          AS noPenjualan,
                b.diskon                AS diskon,
                b.jasa                  AS jasa,
                b.kodePenjualan         AS kodePenjualan,
                b.kode_rm               AS kodeRekamMedis,
                b.no_daftar             AS noPendaftaran,
                b.nama_pasien           AS namaPasien,
                b.kodeObat              AS kodeObat,
                b.kodeObatdr            AS kodeObatDokter,
                b.nama_obatdr           AS namaObatDokter,
                b.urutan                AS urutan,
                b.jlhPenjualan          AS jumlahPenjualan,
                b.jlhPenjualandr        AS jumlahPenjualanDokter,
                b.signa                 AS signa,
                b.hna                   AS hna,
                b.hp                    AS hp,
                b.harga                 AS harga,
                b.id_racik              AS idRacik,
                b.kode_racik            AS kodeRacik,
                b.nama_racik            AS namaRacik,
                b.no_racik              AS noRacik,
                b.ketjumlah             AS keteranganJumlah,
                b.keterangan_obat       AS keteranganObat,
                b.kode_depo             AS kodeDepo,
                b.ranap                 AS rawatInap,
                b.tglPenjualan          AS tanggalPenjualan,
                b.lunas                 AS lunas,
                b.verifikasi            AS verifikasi,
                b.transfer              AS transfer,
                b.resep                 AS resep,
                b.tglverifikasi         AS tanggalVerifikasi,
                b.tgltransfer           AS tanggalTransfer,
                b.operator              AS operator,
                b.tglbuat               AS tanggalBuat,
                b.signa1                AS signa1,
                b.signa2                AS signa2,
                b.signa3                AS signa3,
                b.dokter_perobat        AS dokterPerObat,
                b.bayar                 AS bayar,
                b.tglbayar              AS tanggalBayar,
                b.checking_ketersediaan AS cekKetersediaan,
                b.keteranganobat        AS keteranganObat,
                b.kode_drperobat        AS kodeDokterPerObat,
                b.kode_operator         AS kodeOperator,
                b.kode_verifikasi       AS kodeVerifikasi,
                b.kode_transfer         AS kodeTransfer,
                ee.KD_INST              AS kodeInstalasi,
                SUM(jlhPenjualan)       AS totalJumlah
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.nama_pasien != ''
                AND b.verifikasi != ''
            GROUP BY b.kodeObat
            ORDER BY b.kode_rm, b.kodeObat DESC
            LIMIT 1
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                ee.total                    AS total,          -- in use
                SUM(ee.total)               AS grandTotal,
                (ee.total-ee.jasapelayanan) AS total2,
                b.no_resep                  AS noResep,        -- in use
                b.transfer                  AS transfer,       -- in use
                b.tgltransfer               AS tanggalTransfer -- in use
            FROM db1.masterf_penjualan AS b
            INNER JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                b.no_daftar = :noPendaftaran
                AND b.transfer != ''
            GROUP BY b.no_resep
            ORDER BY b.no_resep ASC
        ";
        $params = [":noPendaftaran" => $noPendaftaran];
        $daftarPenjualanPerResep = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $b = 0; // index baris
        $h = 0; // index halaman
        $barisPerHalaman = 30;

        foreach ($daftarPenjualanPerResep as $resep) {
            $daftarHalaman[$h][$b] = [
                "no_resep" => $resep->noResep,
                "transfer" => $resep->transfer,
                "tgltransfer" => $resep->tanggalTransfer,
                "total" => $resep->total,
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
            WHERE KD_INST = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = (string) $connection->createCommand($sql, $params)->queryScalar();

        $view = new CetakNoResepLq(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaInstalasi: $namaInstalasi,
            namaDepo:      Yii::$app->userFatma->namaDepo,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#print_gabungan2    the original method
     */
    public function actionPrintGabungan2(): string
    {
        $gabunganKode = Yii::$app->request->post("gabunganKode") ?? throw new MissingPostParamException("gabunganKode");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id_gabungan     AS idGabungan,
                a.gabungan_kode   AS gabunganKode,
                a.no_resep        AS noResep,
                a.nama_pasien     AS namaPasien,
                a.tanggal_gabung  AS tanggalGabung,
                ee.KD_INST        AS kodeInstalasi,
                SUM(jlhPenjualan) AS totalJumlah
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                a.gabungan_kode = :gabunganKode
                AND b.nama_pasien != ''
            GROUP BY b.kodeObat
            ORDER BY a.gabungan_kode, b.kodeObat DESC
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT tglverifikasi
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                a.gabungan_kode = :gabunganKode
                AND b.nama_pasien != ''
            ORDER BY b.tglPenjualan DESC
            LIMIT 1
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $tanggalAkhir = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT tglverifikasi
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.pasien_small AS bb ON bb.no_rm = b.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE
                a.gabungan_kode = :gabunganKode
                AND b.nama_pasien != ''
            ORDER BY b.tglPenjualan ASC
            LIMIT 1
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $tanggalAwal = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id_gabungan       AS idGabungan,
                a.gabungan_kode     AS gabunganKode,
                a.no_resep          AS noResep,       -- in use
                a.nama_pasien       AS namaPasien,
                a.tanggal_gabung    AS tanggalGabung,
                b.signa             AS signa,         -- in use
                c.nama_barang       AS namaBarang,    -- in use
                b.kode_racik        AS kodeRacik,     -- in use
                ee.jasapelayanan    AS jasaPelayanan, -- in use
                SUM(b.jlhPenjualan) AS totalJumlah,   -- in use
                dd.nama             AS namaSigna,     -- in use
                b.harga             AS harga          -- in use
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualan AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.masterf_katalog AS c ON c.kode = b.kodeObat
            LEFT JOIN db1.master_signa AS dd ON dd.kode = b.signa
            LEFT JOIN db1.masterf_penjualandetail AS ee ON ee.no_resep = b.no_resep
            WHERE a.gabungan_kode = :gabunganKode
            GROUP BY b.kodeObat
            ORDER BY c.nama_barang ASC
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $total = 0;
        $totalJasa = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 18;
        $noResepSaatIni = "";
        $kodeRacikSaatIni = "";
        $noRacik = 1;
        $noObat = 1;

        foreach ($daftarObat as $obat) {
            $obat->namaSigna ??= $obat->signa;

            if ($obat->kodeRacik) {
                if ($kodeRacikSaatIni != $obat->kodeRacik) {
                    $kodeRacikSaatIni = $obat->kodeRacik;
                    $noRacik = 1;
                    $noObat++;
                }
                $noRacik++;
            } else {
                $noObat++;
            }

            $total += $obat->totalJumlah * $obat->harga;

            if ($noResepSaatIni != $obat->noResep) {
                $noResepSaatIni = $obat->noResep;
                $totalJasa += $obat->jasaPelayanan;
            }

            $daftarHalaman[$h][$b] = [
                "nama_barang" => $obat->namaBarang,
                "jumlah" => $obat->totalJumlah,
                "subtotal_harga" => ceil($obat->harga * $obat->totalJumlah)
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
            SELECT SUM(b.totalharga)
            FROM (
                SELECT
                    a.totalharga,
                    a.gabungan_kode
                FROM (
                    SELECT
                        SUM(jlhPenjualan * harga) AS totalharga,
                        gabungan_kode             AS gabungan_kode
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_gabungan AS mg ON mg.no_resep = mp.no_resep
                    WHERE gabungan_kode = :gabunganKode
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.gabungan_kode
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $totalJual = (int) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT SUM(c.jasapelayanan)
            FROM db1.masterf_gabungan AS a
            INNER JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            WHERE a.gabungan_kode = :gabunganKode
        ";
        $params = [":gabunganKode" => $gabunganKode];
        $jumlahLayanan = $connection->createCommand($sql, $params)->queryScalar();

        $totalCeil = ceil($totalJual / 100) * 100;
        $totalJasa = (floor($jumlahLayanan / 100) * 100) + $totalCeil - $totalJual;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_INST
            FROM db1.masterf_kode_inst
            WHERE KD_INST = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = (string) $connection->createCommand($sql, $params)->queryScalar();

        $view = new PrintGabunganNew(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaInstalasi: $namaInstalasi,
            namaDepo:      Yii::$app->userFatma->namaDepo,
            tanggalAwal:   $tanggalAwal,
            tanggalAkhir:  $tanggalAkhir,
            jumlahHalaman: count($daftarHalaman),
            totalJual:     $totalJual,
            totalJasa:     $totalJasa,
        );
        return $view->__toString();
    }

    /**
     * belum dipake
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#print_gabungan22    the original method
     */
    public function actionPrintGabungan22(): string
    {
        $kodeGabungan = Yii::$app->request->post("kode_gabungan");
        return $this->renderPartial("print-gabungan-new", ["gabungan_kode" => $kodeGabungan]);
    }

    /**
     * sampe disini belum dipake
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#transfer    the original method
     */
    public function actionTransfer(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_gabungan    AS idGabungan,
                gabungan_kode  AS gabunganKode,
                no_resep       AS noResep,       -- in use
                nama_pasien    AS namaPasien,
                tanggal_gabung AS tanggalGabung
            FROM db1.masterf_gabungan
            WHERE gabungan_kode = :gabunganKode
        ";
        $params = [":gabunganKode" => Yii::$app->request->post("id2")];
        $daftarGabungan = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarGabungan as $gabungan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode_rm
                FROM db1.masterf_penjualan
                WHERE no_resep = :noResep
                LIMIT 1
            ";
            $params = [":noResep" => $gabungan->noResep];
            $kodeRekamMedis = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_penjualan
                SET
                    transfer = :transfer,
                    tgltransfer = :tanggalTransfer
                WHERE no_resep = :noResep
            ";
            $params = [":transfer" => Yii::$app->userFatma->nama, ":tanggalTransfer" => $nowValSystem, ":noResep" => $gabungan->noResep];
            $connection->createCommand($sql, $params)->execute();

            $this->actionBridging4([
                "fungsi" => "carinodaftar",
                "NORM" => $kodeRekamMedis
            ]);
        }

        return "OK";
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#listgabung2    the original method
     */
    public function actionListGabung2(): void
    {
        ["gabung" => $gabung, "gabung_kode" => $gabungKode] = Yii::$app->request->post();
        $todayValSystem = Yii::$app->dateTime->todayVal("system");
        if (!$gabung) return;

        $gabung = "'" . implode("','", $gabung) . "'";
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT DISTINCT
                a.no_resep               AS noResep,
                a.nama_pasien            AS namaPasien,           -- in use
                b.idPenjualandetail      AS idPenjualanDetail,
                b.no_resep               AS noResep,              -- in use
                b.tglResep1              AS tanggalResep1,
                b.tglResep2              AS tanggalResep2,
                b.jenisResep             AS jenisResep,
                b.dokter                 AS dokter,
                b.pembayaran             AS pembayaran,
                b.namaInstansi           AS namaInstansi,
                b.namaPoli               AS namaPoli,
                b.keterangan             AS keterangan,
                b.totaldiskon            AS totalDiskon,
                b.totalpembungkus        AS totalPembungkus,
                b.jasapelayanan          AS jasaPelayanan,
                b.total                  AS total,
                b.bayar                  AS bayar,
                b.kembali                AS kembali,
                b.total_retur            AS totalRetur,
                b.iter                   AS iter1,
                b.iter2                  AS iter2,
                b.nm_kamar               AS namaKamar,
                b.KD_BAYAR               AS kodeBayar,
                b.KD_INST                AS kodeInstalasi,
                b.KD_POLI                AS kodePoli,
                b.KD_RRAWAT              AS kodeRuangRawat,
                b.KD_JENIS_CARABAYAR     AS kodeJenisCaraBayar,
                b.JNS_CARABAYAR          AS jenisCaraBayar,
                b.CARA_PEMBAYARAN        AS caraPembayaran,
                b.CARA_PEMBAYARAN_DETAIL AS caraPembayaranDetail,
                b.NOMOR_KARTU            AS noKartu,
                b.TGL_DAFTAR             AS tanggalPendaftaran,
                b.atasnama               AS atasNama,
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS b ON b.no_resep = a.no_resep
            WHERE a.no_resep IN ($gabung)
        ";
        $daftarPenjualan = $connection->createCommand($sql)->queryAll();

        if (!$gabungKode) {
            $fresep = "G" . Yii::$app->userFatma->kodeSubUnitDepo . date("Ymd");
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT gabungan_kode
                FROM db1.masterf_gabungan
                WHERE gabungan_kode LIKE :gabunganKode
                ORDER BY gabungan_kode DESC
                LIMIT 1
            ";
            $params = [":gabunganKode" => "$fresep%"];
            $gabunganKode = $connection->createCommand($sql, $params)->queryScalar();

            if ($gabunganKode) {
                $urutan = substr($gabunganKode, -4);
                $urutan = sprintf("%04d", ++$urutan);
                $gabungKode = $fresep . $urutan;
            } else {
                $gabungKode = $fresep . "0001";
            }
        }

        foreach ($daftarPenjualan as $penjualan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.masterf_gabungan
                SET
                    no_resep = :noResep,
                    gabungan_kode = :gabungKode,
                    nama_pasien = :namaPasien,
                    tanggal_gabung = :tanggalGabung
            ";
            $params = [
                ":noResep" => $penjualan->noResep,
                ":gabungKode" => $gabungKode,
                ":namaPasien" => $penjualan->namaPasien,
                ":tanggalGabung" => $todayValSystem,
            ];
            $connection->createCommand($sql, $params)->execute();
        }
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#bridging4    the original method
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
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/resepgabungan.php#listkumpulanresep    the original method
     */
    public function actionKumpulanResepTableData(): string
    {
        [   "dariTanggal" => $tanggalAwal,
            "sampaiTanggal" => $tanggalAkhir,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noPendaftaran" => $noPendaftaran,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $kodeRekamMedis = $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : $kodeRekamMedis;

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kode_rm     AS kodeRekamMedis,
                a.no_daftar   AS noPendaftaran,
                a.nama_pasien AS namaPasien,
                ''            AS tanggalAkhir,
                ''            AS tanggalAwal
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            INNER JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            WHERE
                a.no_resep != ''
                AND (:kodeRekamMedis = '' OR a.kode_rm = :kodeRekamMedis)
                AND (:noPendaftaran = '' OR a.no_daftar = :noPendaftaran)
                AND a.tglPenjualan >= :tanggalAwal
                AND a.tglPenjualan <= :tanggalAkhir
            GROUP BY a.kode_rm
            ORDER BY a.tglPenjualan DESC
        ";
        $params = [
            ":kodeRekamMedis" => $kodeRekamMedis,
            ":noPendaftaran" => $noPendaftaran,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep as $resep) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT tglPenjualan
                FROM db1.masterf_penjualan
                WHERE
                    no_daftar = :noPendaftaran
                    AND nama_pasien != ''
                    AND verifikasi != ''
                ORDER BY tglPenjualan DESC
                LIMIT 1
            ";
            $params = [":noPendaftaran" => $resep->noPendaftaran];
            $resep->tanggalAkhir = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT tglPenjualan
                FROM db1.masterf_penjualan
                WHERE
                    no_daftar = :noPendaftaran
                    AND nama_pasien != ''
                    AND verifikasi != ''
                ORDER BY tglPenjualan ASC
                LIMIT 1
            ";
            $params = [":noPendaftaran" => $resep->noPendaftaran];
            $resep->tanggalAwal = $connection->createCommand($sql, $params)->queryScalar();
        }

        return json_encode($daftarResep);
    }
}
