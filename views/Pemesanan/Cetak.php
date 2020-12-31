<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Pemesanan;

use tlm\libs\LowEnd\components\GenericData;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pemesanan/prints.php the original file
 */
final class Cetak
{
    private string $output;

    public function __construct(
        iterable    $daftarHalaman,
        GenericData $pemesanan,
        iterable    $daftarDetailPemesanan,
        int         $jumlahHalaman,
        float       $totalNilai,
        float       $totalPpn
    ) {
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        $nToM = Yii::$app->dateTime->numToMonthNameFunc();
        ob_clean();
        ob_start();
        $pageId = "";
        $baseUrl = Yii::$app->basePath;
        $awal = $pemesanan->bulanAwalAnggaran;
        $akhir = $pemesanan->bulanAkhirAnggaran;
        $tahun = $pemesanan->tahunAnggaran;
        ?>


<style>
#<?= $pageId ?> table.p_table tr td,
#<?= $pageId ?> table.p_table tr td label,
#<?= $pageId ?> table.p_table tr th {
    font-size: 13px;
}

#<?= $pageId ?> table.p_table th {
    padding: 4px 8px;
}

#<?= $pageId ?> table.top_table tr td,
#<?= $pageId ?> table.top_table tr th {
    font-size: 15px !important;
}

@media print {
    #<?= $pageId ?> .page2 {
        page-break-after: always;
    }

    #<?= $pageId ?> .page2:last-child {
        page-break-after: avoid !important;
    }

    #<?= $pageId ?> .p_table {
        line-height: 100%;
        border: 1px solid black !important;
    }

    #<?= $pageId ?> .p_table tbody tr td {
        border: 1px solid black !important;
    }

    #<?= $pageId ?> .p_table thead tr th {
        border: 1px solid black !important;
    }

    #<?= $pageId ?> .p_table tfoot tr td {
        border: 1px solid black !important;
    }
}
</style>

<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page2">
    <img src="<?= $baseUrl ?>/assets/img/bakti_husada.jpg" width="80%" height="150" alt=""/>
    <h2>KEMENTRIAN KESEHATAN RI</h2>
    <h3>DIREKTORAT JENDERAL PELAYANAN KESEHATAN</h3>
    <img src="<?= $baseUrl ?>assets/img/logo.png" width="60%" height="100px" alt=""/>

    <table class="top_table">
        <tr>
            <td style="width:40%">Kepada Yth.</td>
            <td style="width:2%">:</td>
            <td><?= $pemesanan->namaPemasok ?></td>
        </tr>
        <tr>
            <td>Alamat Perusahaan</td>
            <td>:</td>
            <td><?= $pemesanan->alamat ?></td>
        </tr>
        <tr>
            <td>Telepon/ Fax</td>
            <td>:</td>
            <td><?= $pemesanan->telefon . " / " . $pemesanan->fax ?></td>
        </tr>
        <tr>
            <td>Berdasarkan Kontrak</td>
            <td>:</td>
            <td>
                Pengadaan Barang Farmasi <?= ucwords(strtolower($pemesanan->subjenisAnggaran)) ?>
                <?= (strtolower($pemesanan->jenisHarga) == 'e-katalog') ? " E-Katalog" : "" ?>
            </td>
        </tr>
        <tr>
            <td>No.</td>
            <td>:</td>
            <td><?= $pemesanan->noSpk ?></td>
        </tr>
        <tr>
            <td>Sumber Dana</td>
            <td>:</td>
            <td><?= $pemesanan->sumberDana ?></td>
        </tr>
        <tr>
            <td colspan="3">Harap segera dikirim barang - barang dibawah ini:</td>
        </tr>
        <tr>
            <td>Kebutuhan</td>
            <td>:</td>
            <td><?= $nToM($awal) . ($awal == $akhir ? "" : "-".$nToM($akhir)) ." ".$tahun ?></td>
        </tr>
        <tr>
            <td>No.</td>
            <td>:</td>
            <td><?= $pemesanan->noDokumen ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?= date('j', strtotime($pemesanan->tanggalDokumen)) . ' ' . $nToM(date('m', strtotime($pemesanan->tanggalDokumen))) . ' ' . date('Y', strtotime($pemesanan->tanggalDokumen)) ?></td>
        </tr>
        <tr>
            <td>Penyerahan Barang Paling Lambat</td>
            <td>:</td>
            <td><?= date('j', strtotime($pemesanan->tanggalTempoKirim)) . ' ' . $nToM(date('m', strtotime($pemesanan->tanggalTempoKirim))) . ' ' . date('Y', strtotime($pemesanan->tanggalTempoKirim)) ?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
    </table>

    <table class="p_table">
        <thead class="t_head">
            <tr>
                <th>NO.</th>
                <th>NAMA BARANG</th>
                <th>PABRIK</th>
                <th>KEMASAN</th>
                <th>VOLUME</th>
                <th>HARGA <?= ($pemesanan->ppn == 0) ? "SUDAH" : "BELUM" ?>TERMASUK PPN</th>
                <th>DISKON</th>
                <th>JUMLAH</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i, "nilai" => $nilai]): ?>
            <?php $dPemesanan = $daftarDetailPemesanan[$i] ?>
            <?php if ($dPemesanan->jumlahKemasan): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $dPemesanan->namaSediaan ?></td>
                    <td><?= $dPemesanan->namaPabrik ?></td>
                    <td><?= $dPemesanan->kemasan ?></td>
                    <td class="text-right"><?= $toUserInt($dPemesanan->jumlahKemasan) ?></td>
                    <td class="text-right"><?= $toUserFloat($dPemesanan->hargaKemasan) ?></td>
                    <td class="text-right"><?= $toUserFloat($dPemesanan->diskonItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($nilai) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
            <tfoot>
            <tr>
                <td colspan="5" rowspan="3" style="border:0"></td>
                <td>Nilai</td>
                <td colspan="2" class="form-inline text-right">Rp. <?= $toUserFloat($totalNilai) ?></td>
            </tr>
            <tr>
                <td>PPN <?= $pemesanan->ppn ?>%</td>
                <td colspan="2" class="form-inline text-right">Rp. <?= $toUserFloat($totalPpn) ?></td>
            </tr>
            <tr>
                <td>TOTAL</td>
                <td colspan="2" class="form-inline text-right">Rp. <?= $toUserFloat($totalNilai + $totalPpn) ?></td>
            </tr>
            </tfoot>
        <?php endif ?>
    </table>

    <?php if ($h + 1 == $jumlahHalaman): ?>
    <div class="footer">
        <table class="top_table">
            <tr>
                <td style="text-align:center">Pejabat Pembuat Komitmen</td>
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
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;Sugih Asih, S.Kp, M.Kep&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:center">Nip : 19630924 198803 2 001</td>
            </tr>
        </table>
    </div>
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
