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
class IkiDokterController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/ikidokter.php#print_laporan    the original method
     */
    public function actionTableLaporanData(): string
    {
        [   "idSmf" => $idPegawai,
            "idDokter" => $idDokter,
            "tanggalMulai" => $tanggalAwal,
            "tanggalSelesai" => $tanggalAkhir,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                  AS kode,
                a.no_resep              AS noResep,
                a.no_penjualan          AS noPenjualan,
                a.diskon                AS diskon,
                a.jasa                  AS jasa,
                a.kodePenjualan         AS kodePenjualan,
                a.kode_rm               AS kodeRekamMedis,
                a.no_daftar             AS noPendaftaran,
                a.nama_pasien           AS namaPasien,
                a.kodeObat              AS kodeObat,
                a.kodeObatdr            AS kodeObatDokter,
                a.nama_obatdr           AS namaObatDokter,
                a.urutan                AS urutan,
                a.jlhPenjualan          AS jumlahPenjualan,
                a.jlhPenjualandr        AS jumlahPenjualanDokter,
                a.signa                 AS signa,
                a.hna                   AS hna,
                a.hp                    AS hp,
                a.harga                 AS harga,
                a.id_racik              AS idRacik,
                a.kode_racik            AS kodeRacik,
                a.nama_racik            AS namaRacik,
                a.no_racik              AS noRacik,
                a.ketjumlah             AS keteranganJumlah,
                a.keterangan_obat       AS keteranganObat,
                a.kode_depo             AS kodeDepo,
                a.ranap                 AS rawatInap,
                a.tglPenjualan          AS tanggalPenjualan,
                a.lunas                 AS lunas,
                a.verifikasi            AS verifikasi,
                a.transfer              AS transfer,
                a.resep                 AS resep,
                a.tglverifikasi         AS tanggalVerifikasi,
                a.tgltransfer           AS tanggalTransfer,
                a.operator              AS operator,
                a.tglbuat               AS tanggalBuat,
                a.signa1                AS signa1,
                a.signa2                AS signa2,
                a.signa3                AS signa3,
                a.dokter_perobat        AS dokterPerObat,
                a.bayar                 AS bayar,
                a.tglbayar              AS tanggalBayar,
                a.checking_ketersediaan AS cekKetersediaan,
                a.keteranganobat        AS keteranganObat,
                a.kode_drperobat        AS kodeDokterPerObat,
                a.kode_operator         AS kodeOperator,
                a.kode_verifikasi       AS kodeVerifikasi,
                a.kode_transfer         AS kodeTransfer
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_penjualandetail AS b ON b.no_resep = a.no_resep
            INNER JOIN db1.user AS c ON c.id = b.dokter
            LEFT JOIN db1.masterf_katalog AS d ON d.kode = a.kodeObat
            WHERE
                d.nama_barang != ''
                AND (:idPegawai = '' OR c.pegawai_id = :idPegawai)
                AND (:idDokter = '' OR c.id = :idDokter)
                AND (:tanggalAwal = '' OR a.tglPenjualan >= :tanggalAwal)
                AND (:tanggalAkhir = '' OR a.tglPenjualan <= :tanggalAkhir)
            ORDER BY a.no_resep DESC
        ";
        $params = [
            ":idPegawai" => $idPegawai,
            ":idDokter" => $idDokter,
            ":tanggalAwal" => $tanggalAwal,
            ":tanggalAkhir" => $tanggalAkhir,
        ];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarResep);
    }
}
