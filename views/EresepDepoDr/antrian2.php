<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepoDr;

use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepodr/cetakresep.php the original file
 */
final class Antrian2
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function __construct(string $kodePenjualan, string $noAntrian)
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. " 
            SELECT
                a.kode                     AS kode,
                a.no_resep                 AS noResep,
                a.no_penjualan             AS noPenjualan,
                a.diskon                   AS diskon,
                a.jasa                     AS jasa,
                a.kodePenjualan            AS kodePenjualan,
                a.kode_rm                  AS kodeRekamMedis,
                a.no_daftar                AS noPendaftaran,
                a.nama_pasien              AS namaPasien,
                a.kodeObat                 AS kodeObat,
                a.kodeObatdr               AS kodeObatDr,
                a.nama_obatdr              AS namaObatDr,
                a.urutan                   AS urutan,
                a.jlhPenjualan             AS jumlahPenjualan,
                a.jlhPenjualandr           AS jumlahPenjualanDr,
                a.signa                    AS signa,
                a.hna                      AS hna,
                a.hp                       AS hp,
                a.harga                    AS harga,
                a.id_racik                 AS idRacik,
                a.kode_racik               AS kodeRacik,
                a.nama_racik               AS namaRacik,
                a.no_racik                 AS noRacik,
                a.ketjumlah                AS keteranganJumlah,
                a.keterangan_obat          AS keteranganObat,
                a.kode_depo                AS kodeDepo,
                a.ranap                    AS rawatInap,
                a.tglPenjualan             AS tanggalPenjualan,
                a.lunas                    AS lunas,
                a.verifikasi               AS verifikasi,
                a.transfer                 AS transfer,
                a.resep                    AS resep,
                a.tglverifikasi            AS tanggalVerifikasi,
                a.tgltransfer              AS tanggalTransfer,
                a.operator                 AS operator,
                a.tglbuat                  AS tanggalBuat,
                a.signa1                   AS signa1,
                a.signa2                   AS signa2,
                a.signa3                   AS signa3,
                a.dokter_perobat           AS dokterPerObat,
                a.bayar                    AS bayar,
                a.tglbayar                 AS tanggalBayar,
                a.checking_ketersediaan    AS cekKetersediaan,
                a.keteranganobat           AS keteranganObat,
                a.kode_drperobat           AS kodeDokterPerObat,
                a.kode_operator            AS kodeOperator,
                a.kode_verifikasi          AS kodeVerifikasi,
                a.kode_transfer            AS kodeTransfer,
                b.id_pasien                AS idPasien,
                b.no_rm                    AS kodeRekamMedis,
                b.no_daftar                AS noPendaftaran,
                b.tgl_terbit_rm            AS tanggalTerbitRekamMedis,
                b.status                   AS status,
                b.nama                     AS nama,
                b.tempat_lahir             AS tempatLahir,
                b.tanggal_lahir            AS tanggalLahir,
                b.tanggal_mati             AS tanggalMati,
                b.umur_tahun               AS umurTahun,
                b.umur_bulan               AS umurBulan,
                b.jenis_kelamin            AS jenisKelamin,
                b.golongan_darah           AS golonganDarah,
                b.gol_darah_old            AS golonganDarahOld,
                b.darah_resus              AS darahResus,
                b.alamat_jalan             AS alamatJalan,
                b.alamat_rt                AS alamatRt,
                b.alamat_rw                AS alamatRw,
                b.alamat_kelurahan         AS alamatKelurahan,
                b.alamat_kecamatan         AS alamatKecamatan,
                b.alamat_kota              AS alamatKota,
                b.alamat_propinsi          AS alamatPropinsi,
                b.alamat_kode_pos          AS alamatKodePos,
                b.no_telpon                AS noTelefon,
                b.no_hp                    AS noHp,
                b.pekerjaan                AS pekerjaan,
                b.agama                    AS agama,
                b.kawin                    AS kawin,
                b.status_nikah             AS statusNikah,
                b.pendidikan_old           AS pendidikanOld,
                b.pendidikan               AS pendidikan,
                b.id_pendidikan            AS idPendidikan,
                b.warga_negara             AS wargaNegara,
                b.suku_bangsa              AS sukuBangsa,
                b.nomor_id                 AS noId,
                b.nama_ayah                AS namaAyah,
                b.nama_ibu                 AS namaIbu,
                b.kerabat_alamat_jalan     AS alamatJalanKerabat,
                b.kerabat_alamat_rt        AS alamatRtKerabat,
                b.kerabat_alamat_rw        AS alamatRwKerabat,
                b.kerabat_alamat_kelurahan AS alamatKelurahanKerabat,
                b.kerabat_alamat_kecamatan AS alamatKecamatanKerabat,
                b.kerabat_alamat_kota      AS alamatKotaKerabat,
                b.kerabat_alamat_propinsi  AS alamatPropinsiKerabat,
                b.kerabat_alamat_kode_pos  AS alamatKodePosKerabat,
                b.kerabat_no_telpon        AS noTelefonKerabat,
                b.kerabat_no_hp            AS noHpKerabat,
                b.cara_bayar               AS caraBayar,
                b.instalasi                AS instalasi,
                b.poli                     AS poli,
                b.rrawat                   AS ruangRawat,
                b.tgl_daftar               AS tanggalPendaftaran,
                c.tglResep1                AS tanggalResep1,
                c.iter                     AS iter,
                c.keterangan               AS keterangan,
                d.name                     AS namadokter
            FROM rsupf.masterf_penjualan AS a
            LEFT JOIN rsupf.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN rsupf.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            LEFT JOIN rsupf.user AS d ON d.id = c.dokter
            WHERE a.kodePenjualan = :kodePenjualan
            LIMIT 1
        ";
        $params = [":kodePenjualan" => $kodePenjualan];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT *
            FROM rsupf.masterf_penjualan AS a
            LEFT JOIN rsupf.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN rsupf.master_signa AS d ON d.kode = a.signa
            WHERE
                a.kodePenjualan = :kodePenjualan
                AND a.kode_racik = ''
        ";
        $params = [":kodePenjualan" => $kodePenjualan];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT *
            FROM rsupf.masterf_penjualan AS a
            LEFT JOIN rsupf.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN rsupf.master_signa AS d ON d.kode = a.signa
            WHERE
                a.kodePenjualan = :kodePenjualan
                AND a.kode_racik != ''
            ORDER BY kode_racik
        ";
        $params = [":kodePenjualan" => $kodePenjualan];
        $daftarRacik = $connection->createCommand($sql, $params)->queryAll();

        $kodeRacik = "";
        $signa = "";
        $obat = null;
        ?>

<img alt="RS Fatmawati" src="/assets/img/logo.png">
<h3>RSUP Fatmawati</h3>
<b>Nomor Antrian Depo:</b> <?= $noAntrian ?>

<table>
    <tr>
        <td>No. Resep</td>
        <td>:</td>
        <td> <?= $pasien->noResep ?></td>
    </tr>
    <tr>
        <td>Kode Rekam Medis</td>
        <td>:</td>
        <td> <?= $pasien->kodeRekamMedis ?></td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>:</td>
        <td> <?= $pasien->namaPasien ?>(<?= $pasien->jenisKelamin ?>)</td>
    </tr>

    <tr>
        <td>No. Pendaftaran</td>
        <td>:</td>
        <td> <?= $pasien->noPendaftaran ?></td>
    </tr>
    <tr>
        <td>Dokter</td>
        <td>:</td>
        <td> <?= $pasien->namadokter ?></td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td> <?= $pasien->tanggalResep1 ?></td>
    </tr>
    <tr>
        <td>Iter</td>
        <td>:</td>
        <td> <?= $pasien->iter ?></td>
    </tr>
    <tr>
        <td>Keterangan</td>
        <td>:</td>
        <td> <?= $pasien->keterangan ?></td>
    </tr>
</table>

<table>
<?php foreach ($daftarObat as $obat) { ?>
    <tr>
        <td>R/</td>
        <td><?= $obat->namaBarang ?> No. <?= $obat->jumlahPenjualan ?> </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>S</td>
        <td><?= $obat->signa ? "( " . $obat->signa . " )" : ""  ?></td>
    </tr>
    <tr>
        <td></td>
        <td><?= $obat->keteranganObat ?><br/></td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
<?php } ?>

    <?php foreach ($daftarRacik as $racik) { ?>
        <?php if ($kodeRacik != $racik->kodeRacik) { ?>
            <?php if ($kodeRacik != "") { ?>
                <tr>
                    <td>S</td>
                    <td><?= "( " . $signa . " )" ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?= $obat->keterangan_obat ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td>R/</td>
                <td><?= $racik->namaRacik ?> No. <?= $racik->noRacik ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php } ?>

        <tr>
            <td></td>
            <td> <?= $racik->namaBarang ?> ( <?= $racik->keteranganJumlah ?> )</td>
        </tr>

        <?php
        $kodeRacik = $racik->kodeRacik;
        $signa = $racik->signa;
        ?>
    <?php } ?>

    <?php if (isset($racik->signa)) { ?>
        <tr>
            <td>S</td>
            <td><?= "( " . $racik->signa . " )" ?></td>
        </tr>
        <tr>
            <td></td>
            <td><?= $obat->keterangan_obat ?></td>
        </tr>
    <?php } ?>
</table>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
