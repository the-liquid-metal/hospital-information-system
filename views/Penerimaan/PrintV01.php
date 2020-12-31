<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Penerimaan;

use tlm\libs\LowEnd\components\{FormHelper as FH, GenericData};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/print_v_01.php the original file
 */
final class PrintV01
{
    private string $output;

    public function __construct(GenericData $penerimaan)
    {
        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        ob_clean();
        ob_start();
        $pageId = "";

        $nomor = '<strong>KP.04.04/II.4/1107/2015</strong> tanggal <strong>6 April 2015</strong>';
        $nospk = ($penerimaan->noSpk && $penerimaan->noSpk != '-')
            ? "<strong>" . $penerimaan->noSpk . "</strong> tanggal <strong>" . date('d', strtotime($penerimaan->tanggalMulai)) . " " . $numToMonthName(date('n', strtotime($penerimaan->tanggalMulai))) . " " . date('Y', strtotime($penerimaan->tanggalMulai)) . "</strong>"
            : "";
        ?>

<style>
#<?= $pageId ?> p {
    line-height: 140%;
    padding: 0 50px 0 50px;
}

#<?= $pageId ?> .hasil_pemeriksaan {
    padding: 0 5px 0 50px;
}

#<?= $pageId ?> .print_paragraf {
    font-size: 18px;
    line-height: 140%;
}
</style>


<h3>TIM PENERIMA BARANG MEDIK</h3>
<h2>BERITA ACARA PENERIMAAN BARANG MEDIK</h2>

<h3>NO.: <?= $penerimaan->noDokumen ?></h3>
<h3>JENIS BARANG: <?= $penerimaan->subjenisAnggaran ?></h3>
<br/>

<div class="print_paragraf">
    <p style="font-size:18px; text-align:justify; letter-spacing:1px; font-weight:100; font-family:'Times New Roman', Times, serif">
        Kami bertindak selaku Tim Penerima Hasil Pekerjaan Barang Medik PT Affordable App yang ditunjuk berdasarkan Keputusan Direktur
        Utama  PT Affordable App no.: <?= $nomor ?> untuk periode tahun anggaran <strong><?= $penerimaan->tahunAnggaran ?></strong>,
        pada hari ini <strong><?= FH::hari(date('l', strtotime($penerimaan->tanggalDokumen))) ?></strong>
        tanggal <strong><?= date('d', strtotime($penerimaan->tanggalDokumen)) . " " . $numToMonthName(date('n', strtotime($penerimaan->tanggalDokumen))) . " " . date('Y', strtotime($penerimaan->tanggalDokumen)) ?></strong>
        membuat Berita Acara Penerimaan Barang setelah mengadakan pemeriksaan dan penelitian sebaik - baiknya secara fisik terhadap
        penerimaan barang sesuai Surat Perintah Kerja Unit Layanan Pengadaan (Pokja Pengadaan Obat, Alat Kesehatan Habis Pakai dan
        Alat Kedokteran) <?= $nospk ?> yang diserahkan oleh <strong><?= $penerimaan->namaPemasok ?></strong> dengan bukti Penerimaan Barang
        sebagai berikut: <br/>
    </p>
    <p>
        No. Terima: <strong><?= $penerimaan->noDokumen ?></strong> Tanggal <strong><?= date('d', strtotime($penerimaan->tanggalDokumen)) . " " .
        $numToMonthName(date('n', strtotime($penerimaan->tanggalDokumen))) . " " . date('Y', strtotime($penerimaan->tanggalDokumen)) ?></strong>
    </p>
</div>

<br/>
<h3 class="hasil_pemeriksaan">HASIL PEMERIKSAAN: BAIK DAN DAPAT DITERIMA</h3>
<br/>

<p style="font-size:18px; line-height:150%; text-align:justify">
    Demikian Berita Acara Penerimaan Barang ini kami buat dengan sebenar - benarnya sebanyak 2 (dua) rangkap untuk dapat dipergunakan
    sebagaimana mestinya.
</p>

<br/>

<table>
    <tr>
        <td colspan="4">Yang Menerima,</td>
    </tr>
    <tr>
        <td colspan="5">Tim Penerima Hasil Pekerjaan Barang Medik PT Affordable App</td>
    </tr>
    <tr>
        <td>1.</td>
        <td>Lily Nunung Amalia, SE</td>
        <td>(ketua)</td>
        <td> : </td>
        <td style="width:23%"> ........ </td>
    </tr>
    <tr>
        <td>2.</td>
        <td>Maya Rachmawati</td>
        <td>(sekretaris)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>3.</td>
        <td>Ari Listiyowati, ST</td>
        <td>(anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>4.</td>
        <td>Heri Santoso, SE</td>
        <td>(anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>5.</td>
        <td>Anis Purnamasari, SSos MM</td>
        <td>(anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>6.</td>
        <td>Aris Samsudin</td>
        <td>(anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
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
