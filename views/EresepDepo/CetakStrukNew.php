<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepo;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepo/cetak-struk-new.php the original file
 *
 * TODO: php: uncategorized: better-if-rewrite
 */
final class CetakStrukNew
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        iterable    $daftarHalaman,
        GenericData $pasien,
        string      $namaInstalasi,
        string      $namaDepo,
        int         $jumlahHalaman,
        iterable    $daftarLainnya
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        $pageId = "";
        $no = 0;
        ?>


<style>
#<?= $pageId ?> .print_wrapper {
    height: 118mm;
    max-height: 118mm;
    width: 105mm;
    max-width: 105mm;
    overflow: hidden;
    border: 1px dotted white;
    padding: 0;
    margin-top: 0;
    margin-bottom: 100px;
    margin-left: 28px;
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
    font-size: 10px;
    text-align: center;
}

#<?= $pageId ?> .print_header table.judul tr:first-child td {
    padding: 7px 0 0 0;
}

#<?= $pageId ?> .print_header table.info_pasien tr td {
    font-size: 8px;
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
    font-size: 10px;
}

#<?= $pageId ?> .print_header .info_pasien_right table.info_pasien tr:first-child td:last-child {
    font-size: 10px;
}

#<?= $pageId ?> .print_header .info_pasien_right table.info_pasien tr:nth-child(4) td:last-child {
    font-size: 10px;
}

#<?= $pageId ?> .print_body table.daftar_obat thead tr td {
    font-weight: bold;
    font-size: 8px;
    vertical-align: text-top;
}

#<?= $pageId ?> .print_body table.daftar_obat tbody tr td {
    font-size: 8px;
    vertical-align: text-top;
    line-height: 0.75em;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(1),
#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(3) {
    width: 4%;
}

#<?= $pageId ?> .print_body table.daftar_obat tbody tr td:nth-child(4) {
    width: 2%;
    text-align: right;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(2) {
    width: 75%;
}

#<?= $pageId ?> .print_body table.daftar_obat tbody tr td:nth-child(2) {
    letter-spacing: 0;
}

#<?= $pageId ?> .print_body table.daftar_obat tr td:nth-child(5) {
    width: 15%;
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

#<?= $pageId ?> .print_header table.judul tr td,
#<?= $pageId ?> .print_body table.daftar_obat tr td,
#<?= $pageId ?> .print_footer table.total tr td,
#<?= $pageId ?> .print_footer table.keterangan tr td {
    line-height: normal;
}
</style>

<?php foreach ($daftarHalaman as $key => $halaman): ?>
<div class="print_wrapper">
    <div>INSTALASI FARMASI</div>
    <div>PT Affordable App</div>

    <table class="info_pasien">
        <tr>
            <td>Pasien</td>
            <td>:</td>
            <td><?= $pasien->namaPasien ?> (<?= $pasien->kelamin ?>)</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?= $pasien->alamatJalan ?></td>
        </tr>
        <tr>
            <td>Ruang</td>
            <td>:</td>
            <td><?= $namaInstalasi ? $namaInstalasi . ", " : "" ?> <?= $pasien->namaKamar ?></td>
        </tr>
        <tr>
            <td>Cara Bayar</td>
            <td>:</td>
            <td><?= $pasien->pembayaran ?></td>
        </tr>
        <tr>
            <td>Operator</td>
            <td>:</td>
            <td><?= $namaDepo ?></td>
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
        <tr>
            <td>Iter</td>
            <td>:</td>
            <td><?= $pasien->iter ?></td>
        </tr>
    </table>

    <table class="daftar_obat">
        <thead>
            <tr>
                <td>NO.</td>
                <td>NAMA BARANG</td>
                <td>JUMLAH</td>
                <td colspan="2">HARGA</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if ($baris->subtotalJumlah): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $baris->namaBarang ?></td>
                    <td class="text-right"><?= $toUserInt($baris->subtotalJumlah) ?></td>
                    <td>Rp</td>
                    <td class="text-right"><?= $toUserInt($baris->subtotalHarga) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>
    </table>

    <?php if ($key + 1 == $jumlahHalaman): ?>
        <table class="total">
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Subtotal</td>
                <td>:</td>
                <td>Rp</td>
                <td class="text-right"><?= $toUserInt($daftarLainnya[$key]["totalJual"]) ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Diskon</td>
                <td>:</td>
                <td>Rp</td>
                <td class="text-right"><?= $toUserInt($pasien->totaldiskon) ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Pembungkus</td>
                <td>:</td>
                <td>Rp</td>
                <td class="text-right"><?= $toUserInt($pasien->totalpembungkus) ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Jasa Pelayanan</td>
                <td>:</td>
                <td>Rp</td>
                <td class="text-right"><?= $toUserInt($daftarLainnya[$key]["totalJasaPelayanan"]) ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Total</td>
                <td>:</td>
                <td>Rp</td>
                <td class="text-right"><?= $toUserInt($daftarLainnya[$key]["grandTotal"]) ?></td>
            </tr>
        </table>

        <table class="keterangan">
            <tr>
                <td>Verifikasi:</td>
                <td><?= $pasien->verifikasi ?> (<?= $toUserDate($pasien->tanggalVerifikasi) ?>)</td>
            </tr>
        </table>
    <?php endif ?>
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
