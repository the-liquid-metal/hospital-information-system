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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepodr/cetakresepdr.php the original file
 */
final class CetakResepDr
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        iterable    $daftarHalaman,
        GenericData $pasien
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> .print_wrapper {
    height: 98mm;
    max-height: 98mm;
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
    font-size: 9px;
    text-align: center;
}

#<?= $pageId ?> .print_header table.judul tr:first-child td {
    padding: 7px 0 0 0;
}

#<?= $pageId ?> .print_header table.info_pasien tr td {
    font-size: 7px;
    vertical-align: text-top;
    line-height: 90%;
}

#<?= $pageId ?> .print_header .info_pasien_left table.info_pasien tr td:first-child {
    width: 19%;
}

#<?= $pageId ?> .print_header .info_pasien_right table.info_pasien tr td:first-child {
    width: 41%;
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
    width: 4%;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(3) {
    width: 3%;
}

#<?= $pageId ?> .print_body table.daftar_obat thead tr td:nth-child(4) {
    text-align: right;
}

#<?= $pageId ?> .print_body table.daftar_obat thead tr td:nth-child(3) {
    text-align: left;
}

#<?= $pageId ?> .print_body table.daftar_obat tbody tr td:nth-child(4) {
    width: 1%;
    text-align: right;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(2) {
    width: 78%;
}

#<?= $pageId ?> .print_body table.daftar_obat tbody tr td:nth-child(2) {
    letter-spacing: 0;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(5) {
    width: 14%;
    text-align: right;
}

#<?= $pageId ?> .print_footer table.total tr td {
    font-size: 8px;
    vertical-align: text-top;
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
}
</style>

<?php foreach ($daftarHalaman as $no => $halaman): ?>
<div class="print_wrapper">
    <div>INSTALASI FARMASI</div>
    <div>PT Affordable App</div>

    <table class="info_pasien">
        <tr>
            <td>Pasien</td>
            <td>:</td>
            <td><?= $pasien->namaPasien ?> </td>
        </tr>
        <tr>
            <td>Cara Bayar</td>
            <td>:</td>
            <td><?= $pasien->pembayaran ?></td>
        </tr>
        <tr>
            <td>Dokter</td>
            <td>:</td>
            <td><?= $pasien->dokter ?></td>
        </tr>
    </table>

    <table class="info_pasien">
        <tr>
            <td>Resep</td>
            <td>:</td>
            <td class="resep"><?= $pasien->noResep ?></td>
        </tr>
        <tr>
            <td>Dari Tanggal</td>
            <td>:</td>
            <td><?= $toUserDate($pasien->tanggalAwalResep) ?></td>
        </tr>
        <tr>
            <td>Sampai Tanggal</td>
            <td>:</td>
            <td><?= $toUserDate($pasien->tanggalAkhirResep) ?></td>
        </tr>
        <tr>
            <td>Kode Rekam Medis</td>
            <td>:</td>
            <td><?= $pasien->kodeRekamMedis ?></td>
        </tr>
        <tr>
            <td>No. Daftar</td>
            <td>:</td>
            <td><?= $pasien->noDaftar ?></td>
        </tr>
    </table>

    <table class="daftar_obat">
        <thead>
            <tr>
                <td>NO.</td>
                <td>||NAMA BARANG</td>
                <td>||JUMLAH</td>
                <td>||RACIK</td>
                <td>||SIGNA</td>
                <td></td>
                <td></td>
                <td>||KETERANGAN</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <tr>
                <td><?= $no + 1 ?></td>
                <td><?= $baris->namaBarang ?></td>
                <td><?= $baris->jumlah ?></td>
                <td><?= $baris->idRacik ?></td>
                <td><?= $baris->signa1 ?></td>
                <td><?= $baris->signa2 ?></td>
                <td><?= $baris->signa3 ?></td>
                <td><?= $baris->keteranganObat ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
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
