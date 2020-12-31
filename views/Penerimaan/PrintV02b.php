<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Penerimaan;

use tlm\libs\LowEnd\components\{DateTimeException, FormHelper as FH, GenericData};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/print_v_02b.php the original file
 */
final class PrintV02b
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(GenericData $penerimaan)
    {
        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        ob_clean();
        ob_start();
        $pageId = "";

        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $tanggalDokumen = $penerimaan->tanggalDokumen;
        $hari = FH::hari(date('l', strtotime($tanggalDokumen)));
        $tanggal = date('d', strtotime($tanggalDokumen)) . " " . $numToMonthName(date('n', strtotime($tanggalDokumen))) . " " . date('Y', strtotime($tanggalDokumen));
        ?>


<style>
#<?= $pageId ?> .print_paragraf {
    font-size: 18px;
    line-height: 140%;
}
</style>


<h3>TIM PENERIMA BARANG MEDIK</h3>
<h2>BERITA ACARA PENYERAHAN BARANG MEDIK</h2>

<h3>NO.: <?= $penerimaan->noDokumen ?></h3>
<h3>JENIS BARANG: <?= $penerimaan->subjenisAnggaran ?></h3>
<br/>

<div class="print_paragraf" style="font-size:18px; text-align:justify; letter-spacing:1px; font-weight:200; font-family:'Times New Roman', Times, serif">
    Kami bertindak selaku Tim Penerima Hasil Pekerjaan Barang Medik PT Affordable App yang ditunjuk berdasarkan Keputusan
    Direktur Utama PT Affordable App No.: <strong>KP.04.04/II.4/4723/2016</strong> tanggal <strong>21 Juli 2016</strong>
    untuk periode tahun anggaran <strong><?= $penerimaan->tahunAnggaran ?></strong>, pada hari ini <strong><?= $hari ?></strong>
    tanggal <strong><?= $tanggal ?></strong> membuat Berita Acara Penyerahan Barang setelah melakukan serah terima barang
    dengan Gudang Induk Farmasi PT Affordable App. Barang diserahkan dalam keadaan baik, lengkap dan dapat diterima, sesuai
    faktur/Surat Jalan no.: <strong><?= ($penerimaan->noSuratJalan ?: '-') . " / " . ($penerimaan->noFaktur ?: '-') ?></strong>
    dari: <strong><?= $penerimaan->namaPemasok ?></strong> <br/>
</div>

<br/>

<table>
    <tr>
        <td colspan="5">Yang Menyerahkan,<br/>Tim Penerima Hasil Pekerjaan Barang Medik PT Affordable App</td>
    </tr>
    <tr>
        <td>1.</td>
        <td>Lily Nunung Amalia, SE</td>
        <td>(ketua)</td>
        <td> : </td>
        <td style="width:20%"> ........ </td>
    </tr>
    <tr>
        <td>2.</td>
        <td>Iwan Gunawan</td>
        <td>(Anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>3.</td>
        <td>Sri Ronggo Wulan, S.sos</td>
        <td>(Anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>4.</td>
        <td>Aris Samsudin</td>
        <td>(Anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>5.</td>
        <td>Meryjanti, SE</td>
        <td>(Anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>6.</td>
        <td>Catur Triswahyutiningsih, S.Si, Apt.</td>
        <td>(Anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
    <tr>
        <td>7.</td>
        <td>Maya Rachmawati</td>
        <td>(Anggota)</td>
        <td> : </td>
        <td> ........ </td>
    </tr>
</table>

<table>
    <tr>
        <td>
            Jakarta, <?= $toUserDate($tanggalDokumen) ?>
            <br/>Yang Menerima,<br/>Kepala Instalasi Farmasi PT Affordable App
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <?php
        if (date("Y", strtotime($tanggalDokumen)) == 2016) {
            if (date("n", strtotime($tanggalDokumen)) == 1 && date("j", strtotime($tanggalDokumen)) < 4) {
                $person = "Etin Ratna Martiningsih, Dra, Apt";
                $nip = '1962112919900220002';
            } else {
                $person = "Ahmad Subhan S.Si., M.Si., Apt.";
                $nip = '197904172010121001';
            }
        } elseif (date("Y", strtotime($tanggalDokumen)) == 2015) {
            $person = "Etin Ratna Martiningsih, Dra, Apt";
            $nip = '1962112919900220002';
        } else {
            $person = "Ahmad Subhan S.Si., M.Si., Apt.";
            $nip = '197904172010121001';
        }
        ?>
        <td>
            <span style="text-decoration:underline">&nbsp;<?= $person ?> &nbsp;</span>
            <br/> NIP. <?= $nip ?>
        </td>
    </tr>
</table>

<br/>

<table>
    <tr>
        <td>Mengetahui, <br/> Pejabat Pembuat Komitmen<br/>PT Affordable App</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="text-align:center">
            <span style="text-decoration:underline">&nbsp; Sugih Asih, S.Kp, M.Kep &nbsp;</span>
            <br/> NIP. 19630924 198803 2 001
        </td>
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
