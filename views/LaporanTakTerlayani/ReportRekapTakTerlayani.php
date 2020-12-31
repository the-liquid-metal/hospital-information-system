<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanTakTerlayani;

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
 */
final class ReportRekapTakTerlayani
{
    private string $output;

    public function __construct(object $depo, string $tanggalAwal, string $tanggalAkhir, iterable $daftarPeringatan)
    {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $jenis = "";
        $html = "";
        $i = 0;
        ?>


<div class="header">
    REKAPITULASI BARANG TIDAK TERLAYANI<br/>
    <?= $depo->namaDepo ?><br/>
    Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
</div>

<table class="table table-bordered table-stripped table-condensed">
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
        <?php if ($peringatan->namaKelompok != $jenis || $jenis == '0'): ?>
            <?php if ($jenis != "0"): ?>
                <tr class="grup_obat">
                    <td colspan="17"><?= $jenis ?></td>
                </tr>
                <?= $html ?>
            <?php endif ?>
            <?php
            $i = 1;
            $html = "";
            ?>
        <?php endif ?>

        <?php
        $html .=
            '<tr>
                <td>' . $i . '</td>
                <td>' . $peringatan->kodeItem . '</td>
                <td>' . $peringatan->namaBarang . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->sumJumlah1) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->sumJumlah2) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->gudang) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->irj) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->irj2) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->irj3) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->igh) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->ibs) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->igd) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->produksi) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->teratai) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->bougenvil) . '</td>
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
