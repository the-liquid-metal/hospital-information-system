<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Laporan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporan_rekap_harian2_tdkterlayani.php the original file
 *
 * TODO: php: uncategorized: tidy up
 */
final class LaporanRekapHarian2TakTerlayani
{
    private string $output;

    public function __construct(string $namaDepo, string $tanggalAwal, string $tanggalAkhir, iterable $daftarPeringatan)
    {
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        $pageId = "";
        $jenis = 0;
        $html = "";
        $i = 1;
        ?>


<style>
#<?= $pageId ?> .main thead .thead_header th {
    border-top: 1px solid black;
    font-weight: bolder;
    font-size: 11px;
}

#<?= $pageId ?> .daftar_obat thead td {
    border-top: 1px solid black;
    font-weight: bolder;
    font-size: 11px;
}

#<?= $pageId ?> .grup_obat td {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    font-weight: bolder;
    font-size: 11px;
}

#<?= $pageId ?> .judul tr td {
    text-align: center;
    font-size: 14px;
    font-weight: bolder;
}

#<?= $pageId ?> .judul tr:last-child td {
    text-align: left;
}

#<?= $pageId ?> .judul tr:nth-child(3) td {
    font-size: 12px;
    font-weight: normal;
}

#<?= $pageId ?> .judul tr:last-child td:last-child {
    border: none;
}

#<?= $pageId ?> .judul tr:first-child td {
    padding-top: 1cm;
}

#<?= $pageId ?> tbody tr td {
    font-size: 11px;
}
</style>

<div>REKAPITULASI BARANG TIDAK TERLAYANI</div>
<div><?= $namaDepo ?></div>
<div>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>

<table class="main">
    <thead>
        <tr class="thead_header">
            <th>No.</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Jumlah Minta</th>
            <th>Jumlah Beri</th>
            <th>Gudang</th>
            <th>IRJ</th>
            <th>IRJ2</th>
            <th>IRJ3</th>
            <th>IGH</th>
            <th>IBS</th>
            <th>IGD</th>
            <th>Produksi</th>
            <th>Teratai</th>
            <th>Bougenvil</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarPeringatan as $peringatan): ?>
        <?php $peringatan->namaKelompok = $peringatan->namaKelompok ?: "Lain - Lain" ?>
        <?php if ($peringatan->namaKelompok != $jenis || $jenis == "0"): ?>
            <?php if ($jenis != "0"): ?>
                <tr class="grup_obat">
                   <td colspan="17"><?= $jenis ?></td>
                </tr>
                <?= $html ?>
            <?php endif ?>
            <?php
            $i = 1;
            $html = '';
            ?>
        <?php endif ?>
        <?php
        $html .=
            '<tr>'.
                '<td>' . $i . '</td>
                <td>' . $peringatan->kodeItem . '</td>
                <td>' . $peringatan->namaBarang . '</td>
                <td class="text-right">' . $toUserInt($peringatan->sumJumlah1) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->sumJumlah2) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->gudang) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->irj) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->irj2) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->irj3) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->igh) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->ibs) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->igd) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->produksi) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->teratai) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->bougenvil) . '</td>
            </tr>';
        $jenis = $peringatan->namaKelompok;

        $i++;
        ?>
    <?php endforeach ?>

        <tr class="grup_obat">
            <td colspan="17"><?= $jenis ?></td>
        </tr>
        <?= $html ?>
    </tbody>
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
