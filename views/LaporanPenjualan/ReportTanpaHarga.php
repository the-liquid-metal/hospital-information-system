<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenjualan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporandepojualnew.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanDepoJualNew: commit-e37d34f4
 */
final class ReportTanpaHarga
{
    private string $output;

    public function __construct(
        iterable $daftarHalaman,
        string   $namaDepo,
        string   $namaRuangRawat,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarKetersediaan,
        int      $jumlahHalaman,
        float    $grandTotal
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page">
    <div>LAPORAN PENJUALAN</div>
    <div>Unit Kerja: <?= $namaDepo ?></div>
    <div>Ruang Rawat: <?= $namaRuangRawat ?></div>
    <div>Tanggal: <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <td>Kode</td>
                <td>Nama Obat</td>
                <td>Pabrik</td>
                <td>Jumlah Jual</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i, "no" => $no, "nama_jenis" => $nama, "total" => $total]): ?>
            <?php if ($nama): ?>
                <tr class="tr_jenis" style="background-color:lightgrey">
                    <td colspan="2">--<?= $no . $nama ?>--</td>
                    <td class="text-right" colspan="2"><?= $toUserFloat($total) ?></td>
                </tr>

            <?php else: ?>
                <?php $ketersediaan = $daftarKetersediaan[$i] ?>
                <tr>
                    <td><?= $no . $ketersediaan->kodeObat ?></td>
                    <td><?= $ketersediaan->namaBarang ?></td>
                    <td><?= $ketersediaan->namaPabrik ?></td>
                    <td class="text-right"><?= $toUserFloat($ketersediaan->totalJumlah) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td class="text-right" colspan="3"><b><em>Total Jumlah Penjualan</em></b></td>
                <td class="text-right"><?= $toUserFloat($grandTotal) ?></td>
            </tr>
        </tfoot>
        <?php endif ?>
    </table>

    <table class="hal">
        <tr>
            <td class="text-right"><?= "Hal " . $toUserInt($h + 1) . " dari " . $toUserInt($jumlahHalaman) ?></td>
        </tr>
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
