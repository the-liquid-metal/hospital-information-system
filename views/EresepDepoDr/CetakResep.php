<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepoDr;

use tlm\libs\LowEnd\components\{DateTimeException, GenericData};
use Yii;

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
final class CetakResep
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        iterable    $daftarHalaman,
        GenericData $pasien,
        GenericData $resep
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> .print_wrapper {
    height: 102mm;
    max-height: 102mm;
    width: 105mm;
    max-width: 105mm;
    overflow: hidden;
    border: 1px dotted white;
    padding: 0;
    margin-top: 0;
    margin-bottom: 100px;
    margin-left: 8px;
}

#<?= $pageId ?> .print_wrapper:last-child {
    margin-bottom: 0;
}

#<?= $pageId ?> .print_wrapper table {
    padding: 0;
    margin: 0;
    border-collapse: collapse;
}

#<?= $pageId ?> .print_header table {
    width: 100%;
    margin: 0;
    padding: 0;
}

#<?= $pageId ?> .print_header table tr,
#<?= $pageId ?> .print_header table tr td {
    padding: 0;
    margin: 0;
}

#<?= $pageId ?> .print_header table.judul tr td {
    font-size: 7px;
    text-align: center;
}

#<?= $pageId ?> .print_header table.judul tr:first-child td {
    padding: 7px 0 0 0;
}

#<?= $pageId ?> .print_header table.info_pasien tr td {
    font-size: 7px;
    vertical-align: text-top;
    line-height: 90%;
    height: 100%;
}

#<?= $pageId ?> .print_header .info_pasien_left table.info_pasien tr td:first-child {
    width: 30%;
}

#<?= $pageId ?> .print_header .info_pasien_right table.info_pasien tr td:first-child {
    width: 32%;
}

#<?= $pageId ?> .print_header .info_pasien_left table.info_pasien tr:first-child td:last-child {
    font-size: 8px;
}

#<?= $pageId ?> .print_header .info_pasien_right table.info_pasien tr:first-child td:last-child {
    font-size: 8px;
}

#<?= $pageId ?> .print_header .info_pasien_right table.info_pasien tr:nth-child(4) td:last-child {
    font-size: 8px;
}

#<?= $pageId ?> .print_body table.daftar_obat thead tr td {
    font-weight: bold;
    font-size: 8px;
    vertical-align: text-top;
    letter-spacing: 1px;
}

#<?= $pageId ?> .print_body table.daftar_obat tbody tr td {
    font-size: 8px;
    vertical-align: text-top;
    line-height: 0.75em;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(1) {
    width: 15%;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(3) {
    width: 3%;
}

#<?= $pageId ?> .print_body table.daftar_obat thead tr td:nth-child(4) {
    text-align: right;
    width: 25%;
}

#<?= $pageId ?> .print_body table.daftar_obat thead tr td:nth-child(3) {
    text-align: left;
}

#<?= $pageId ?> .print_body table.daftar_obat tbody tr td:nth-child(4) {
    width: 1%;
    text-align: right;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(2) {
    width: 60%;
}

#<?= $pageId ?> .print_body table.daftar_obat tbody tr td:nth-child(2) {
    letter-spacing: 0;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(5) {
    width: 14%;
    text-align: right;
}

#<?= $pageId ?> .print_footer table.total tr td {
    font-size: 5px;
    vertical-align: text-top;
    line-height: 1px;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(1) {
    width: 25%;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(2) {
    width: 1%;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(3) {
    width: 2%;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(4) {
    width: 20%;
    text-align: right;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(5) {
    width: 4%;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(6) {
    width: 25%;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(7) {
    width: 1%;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(8) {
    width: 2%;
    text-align: right;
}

#<?= $pageId ?> .print_footer table.total tr td:nth-child(9) {
    width: 20%;
    text-align: right;
}

#<?= $pageId ?> .print_footer table.keterangan tr td {
    font-size: 8px;
    vertical-align: text-top;
    line-height: 0;
}

#<?= $pageId ?> .boxmini {
    display: inline-block;
    width: 10px;
    height: 2px;
    min-width: 3px;
    min-height: 2px;
    border: 1px solid black;
}
</style>

<?php foreach ($daftarHalaman as $halaman): ?>
<div class="print_wrapper">
    <div>PT Affordable App</div>
    <div>RESEP</div>

    <table class="info_pasien">
        <tr>
            <td>CARA BAYAR</td>
            <td>:</td>
            <td><?= $pasien->pembayaran ?></td>
        </tr>
        <tr>
            <td>Kode Rekam Medis</td>
            <td>:</td>
            <td><?= $pasien->kodeRekamMedis ?>  </td>
        </tr>
        <tr>
            <td>Nama Pasien</td>
            <td>:</td>
            <td><?= $pasien->namaPasien ?> </td>
        </tr>
        <tr>
            <td>Tanggal Lahir (Umur)</td>
            <td>:</td>
            <td><?= $toUserDate($resep->tanggalLahir) ?> </td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td><?= $resep->kelamin ?> </td>
        </tr>
        <tr>
            <td>Tinggi Badan**</td>
            <td>:</td>
            <td><?= $resep->tinggiBadan ?> </td>
        </tr>
        <tr>
            <td>Berat Badan **</td>
            <td>:</td>
            <td><?= $resep->beratBadan ?> </td>
        </tr>
        <tr>
            <td colspan="3" style="font-size:6px">
                * Pilih salah Satu <br/>
                ** Pasien anak dan pasien yang membutuhkan perhitungan dosis individual
            </td>
        </tr>
    </table>

    <table class="info_pasien">
        <tr>
            <td>POLI/RUANGAN</td>
            <td>:</td>
            <td class="resep"><?= $resep->namaPoli ?></td>
        </tr>
        <tr>
            <td>Resep</td>
            <td>:</td>
            <td class="resep"><?= $pasien->noResep ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?= $toUserDate($pasien->tanggalResep1) ?></td>
        </tr>
        <tr>
            <td>Nama Dokter</td>
            <td>:</td>
            <td><?= $resep->namaPenerima ?></td>
        </tr>
        <tr>
            <td>SIP</td>
            <td>:</td>
            <td><?= $resep->noSip ?></td>
        </tr>
        <tr>
            <td colspan="3">Riwayat Alergi: <?= $resep->riwayatAlergi ?></td>
        </tr>
    </table>

    <table class="daftar_obat">
        <thead>
            <tr>
                <td style="text-align:center">R/</td>
                <td style="text-align:center">||Nama Perbekalan Farmasi</td>
                <td style="text-align:center">||Jumlah</td>
                <td style="text-align:center">||SIGNA</td>
                <td style="text-align:center">||RUTE</td>
                <td></td>
                <td style="text-align:center">||KET</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php $racik = in_array($baris->idRacik, [1, 2, 3, 4]) ? 'Racik' . $baris->idRacik : $racik = 'R/' ?>
            <tr>
                <td><?= $racik ?></td>
                <td><?= $baris->namaBarang ?></td>
                <td class="text-right"><?= $toUserInt($baris->jumlah) ?></td>
                <td><?= $baris->signa1 ?></td>
                <td><?= $baris->signa2 ?></td>
                <td><?= $baris->signa3 ?></td>
                <td><?= $baris->keteranganObat ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <table>
        <tr style="font-size:7px">
            <td style="border:1px dotted black; font-size:7px" colspan="4">
                Tahap 1: Pengkajian &amp; Klarifikasi <br/>
                Jam :... Petugas:...
            </td>
            <td style="border:1px dotted black" colspan="4">
                Tahap 2: Penyiapan<br/>
                Jam :... Petugas:...
            </td>
            <td style="border:1px dotted black" colspan="4">
                Tahap 3: Dispensing<br/>
                Jam :... Petugas:...
            </td>
            <td style="border:1px dotted black" colspan="4">
                Tahap 4: Penyerahan &amp; Informasi<br/>
                Jam :... Petugas:...
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:70%; border:1px dotted black">
                <table>
                    <tr>
                        <td style="width:120%">PENGKAJIAN RESEP :</td>
                        <td style="width:40%">YA</td>
                        <td style="width:40%">TIDAK</td>
                    </tr>
                    <tr>
                        <td>Benar dan Jelas Penulisan Resep</td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Benar Obat</td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Benar Dosis</td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Benar Waktu dan Frekuensi</td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Benar Rute</td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Benar Pasien</td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Tidak Ada Duplikasi Terapi</td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Tidak Ada Interaksi Obat</td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                        <td>
                            <div class="boxmini"></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:50%; font-size:6px; border:1px dotted black">
                KLARIFIKASI DAN KONFIRMASI <br/>
                (S B A R):<br/>
                (Situation, Background, Assesment, Recommendation)<br/>
                <br/>
                Tanggal: <?= $pasien->tanggalVerifikasi ?><br/>
                <br/>
                <br/>
                Petugas Farmasi: <?= $pasien->verifikasi ?>
            </td>
        </tr>
    </table>
</div>
<?php endforeach ?>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
