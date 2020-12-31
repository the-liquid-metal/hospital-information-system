<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPengeluaran;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporan_rekap_harian2_fox.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanRekapHarian2Fox: commit-e37d34f4
 *
 * TODO: php: uncategorized: tidy up
 */
final class ReportPengeluaran
{
    private string $output;

    public function __construct(string $namaDepo, string $tanggalAwal, string $tanggalAkhir, iterable $daftarPeringatan)
    {
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $jenis = 0;
        $html = "";
        $total = 0;
        $subtotal = 0;
        $i = 1;
        ?>


<div class="header">
    REKAPITULASI PENGELUARAN BARANG<br/>
    <?= $namaDepo ?><br/>
    Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
</div>

<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>No.</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Pabrik</th>
            <th>Kuantitas</th>
            <th>Satuan</th>
            <th>@ (Rp.)</th>
            <th>Jumlah (Rp.)</th>
        </tr>
    </thead>

    <tbody>
    <?php
    foreach ($daftarPeringatan as $peringatan) {
        $peringatan->namaKelompok ??= "Lain - Lain";
        if ($peringatan->namaKelompok != $jenis || $jenis == '0') {
            if ($jenis != "0") {
                $html = '<tr style="background-color:lightgrey">
                            <td colspan="7">' . $jenis . '</td>
                            <td class="text-right">' . $toUserInt($subtotal) . '</td>
                        </tr>' . $html;
                echo $html;
            }

            $subtotal = 0;
            $i = 1;
            $html = '';
        }
        $html .= '
            <tr>
                <td>' . $i . '</td>
                <td>' . $peringatan->kodeItem . '</td>
                <td>' . $peringatan->namaBarang . '</td>
                <td>' . $peringatan->namaPabrik . '</td>
                <td class="text-right">' . $toUserInt($peringatan->totalJumlah) . '</td>
                <td>' . $peringatan->namaKemasan . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->hpItem) . '</td>
                <td class="text-right">' . $toUserFloat($peringatan->hpItem * $peringatan->totalJumlah) . '</td>
            </tr>';
        $jenis = $peringatan->namaKelompok;
        $subtotal += $peringatan->hpItem * $peringatan->totalJumlah;
        $total += $peringatan->hpItem * $peringatan->totalJumlah;
        $i++;
    }
    ?>
    <tr>
        <td colspan="7"><?= $jenis ?></td>
        <td class="text-right"><?= $toUserFloat($subtotal) ?></td>
    </tr>

    <?= $html ?>

    <tr>
        <td class="text-right" colspan="6">Total</td>
        <td></td>
        <td class="text-right"><?= $toUserFloat($total) ?></td>
    </tr>
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
