<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenerimaan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/penerimaan/report_rekapitulasi.php the original file
 */
final class ReportPenerimaanRekapitulasi
{
    private string $output;

    public function __construct(
        iterable $daftarHalaman,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarDetailPenerimaan,
        int      $jumlahHalaman,
        float    $totalJumlah,
        float    $totalPpn,
        float    $totalNilai
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page rekap">
    <div>
        REKAPITULASI PENERIMAAN BARANG<br/>
        INSTALASI FARMASI<br/>
        Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
    </div>

    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr class="thead_header">
                <th>No.</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Pabrik</th>
                <th>Kuantitas</th>
                <th>Satuan</th>
                <th>Nilai (Rp.)</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i, "no" => $no, "nama_kelompok" => $nama, "subtotal_nilai" => $subtotal]): ?>
            <?php if ($nama): ?>
                <tr class="row_kelompok">
                    <td colspan="6" class="nama_kelompok"><?= $no . $nama ?></td>
                    <td class="text-right"><?= $toUserFloat($subtotal) ?></td>
                </tr>

            <?php else: ?>
                <?php $dPenerimaan = $daftarDetailPenerimaan[$i] ?>
                <tr class="katalog">
                    <td><?= $no ?></td>
                    <td><?= $dPenerimaan->idKatalog ?></td>
                    <td><?= $dPenerimaan->namaSediaan ?></td>
                    <td><?= $dPenerimaan->namaPabrik ?></td>
                    <td class="text-right"><?= $toUserFloat($dPenerimaan->totalItem) ?></td>
                    <td><?= $dPenerimaan->satuan ?></td>
                    <td class="text-right"><?= $toUserFloat($dPenerimaan->totalHarga + $dPenerimaan->totalPpn) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>

        <?php if ($n + 1 == $jumlahHalaman): ?>
            <tr class="total">
                <td colspan="3" class="nama_kelompok">Total</td>
                <td colspan="2" class="subtotal">
                    Total Harga<br/>
                    Total PPN<br/>
                    Total Nilai<br/>
                </td>
                <td colspan="2" class="nilai_subtotal">
                    <?= $toUserFloat($totalJumlah) ?><br/>
                    <?= $toUserFloat($totalPpn) ?><br/>
                    <?= $toUserFloat($totalNilai) ?><br/>
                </td>
            </tr>
        <?php endif ?>
        </tbody>
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
