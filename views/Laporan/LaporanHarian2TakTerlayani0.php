<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Laporan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporan_harian2_tdkterlayani_0.php the original file
 *
 * TODO: php: uncategorized: tidy up
 */
final class LaporanHarian2TakTerlayani0
{
    private string $output;

    public function __construct(GenericData $depo, string $tanggalAwal, string $tanggalAkhir, iterable $daftarPeringatan)
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

<div>RINCIAN BARANG TIDAK TERLAYANI</div>
<div><?= $depo->namaDepo ?></div>
<div>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>

<table class="main">
    <thead>
        <tr class="thead_header">
            <th>No.</th>
            <th>Depo</th>
            <th>No. Permintaan</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Jumlah Minta</th>
            <th>Jumlah Beri</th>
            <th>Stok Minta</th>
            <th>Stok Beri</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($daftarPeringatan as $peringatan): ?>
            <?php $peringatan->namaKelompok = $peringatan->namaKelompok ?: "Lain - Lain" ?>
            <?php if ($peringatan->namaKelompok != $jenis || $jenis == "0"): ?>
                <?php if ($jenis != "0"): ?>
                    <tr class="grup_obat">
                        <td colspan="9"><?= $jenis ?></td>
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
                '<tr>
                    <td>' . $i . '</td>
                    <td>' . $peringatan->namaDepo . '</td>
                    <td>' . $peringatan->noDokumen . '</td>
                    <td>' . $peringatan->kodeItem . '</td>
                    <td>' . $peringatan->namaBarang . '</td>
                    <td class="text-right">' . $toUserInt($peringatan->sumJumlah1) . '</td>
                    <td class="text-right">' . $toUserInt($peringatan->sumJumlah2) . '</td>
                    <td class="text-right">' . $toUserInt($peringatan->stokMinta) . '</td>
                    <td class="text-right">' . $toUserInt($peringatan->stokBeri) . '</td>
                </tr>';
            $jenis = $peringatan->namaKelompok;
            $i++;
            ?>
        <?php endforeach ?>
        <tr class="grup_obat">
            <td colspan="9"><?= $jenis ?></td>
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
