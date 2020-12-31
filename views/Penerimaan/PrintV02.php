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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/print_v_02.php the original file
 */
final class PrintV02
{
    private string $output;

    public function __construct(GenericData $penerimaan)
    {
        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        ob_clean();
        ob_start();
        $pageId = "";
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
    Kami bertindak selaku Tim Penerima Hasil Pekerjaan Barang Medik  PT Affordable App yang ditunjuk berdasarkan
    Keputusan Direktur Utama PT Affordable App No.: <strong>KP.04.04/II.4/1107/2015</strong>
    tanggal <strong>6 April 2015</strong> untuk periode tahun anggaran <strong><?= $penerimaan->tahunAnggaran ?></strong>,
    pada hari ini <strong><?= FH::hari(date('l', strtotime($penerimaan->tanggalDokumen))) ?></strong> tanggal
    <strong><?= date('d', strtotime($penerimaan->tanggalDokumen)) . " " . $numToMonthName(date('n', strtotime($penerimaan->tanggalDokumen))) . " " . date('Y', strtotime($penerimaan->tanggalDokumen)) ?></strong>
    membuat Berita Acara Penyerahan Barang setelah melakukan serah terima barang dengan Gudang Induk Farmasi PT Affordable App.
    Barang diserahkan dalam keadaan baik, lengkap dan dapat diterima, sesuai faktur/Surat Jalan no.:
    <strong><?= ($penerimaan->noSuratJalan ?: '-') . " / " . ($penerimaan->noFaktur ?: '-') ?></strong> dari : <strong><?= $penerimaan->namaPemasok ?></strong> <br/>
</div>

<br/>

<table>
    <tr>
        <td>
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
        </td>
        <td>
            <table>
                <tr>
                    <td>
                        Jakarta, <?= date('d', strtotime($penerimaan->tanggalDokumen)) . " " . $numToMonthName(date('n', strtotime($penerimaan->tanggalDokumen))) . " " . date('Y', strtotime($penerimaan->tanggalDokumen)) ?>
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
                    if (date("Y", strtotime($penerimaan->tanggalDokumen)) == 2016) {
                        if ((int)date("n", strtotime($penerimaan->tanggalDokumen)) == 1 && (int)date("j", strtotime($penerimaan->tanggalDokumen)) < 4) {
                            $person = "Etin Ratna Martiningsih, Dra, Apt";
                            $nip = '1962112919900220002';
                        } else {
                            $person = "Ahmad Subhan S.Si., M.Si., Apt.";
                            $nip = '197904172010121001';
                        }
                    } elseif ((int)date("Y", strtotime($penerimaan->tanggalDokumen)) == 2015) {
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
