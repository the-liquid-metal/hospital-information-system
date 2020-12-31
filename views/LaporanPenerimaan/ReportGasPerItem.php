<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenerimaan;

use tlm\libs\LowEnd\components\DateTimeException;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/penerimaan/laporanrekapgasperitem.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Penerimaan\LaporanRekapGasPerItem: commit-e37d34f4
 */
final class ReportGasPerItem
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        iterable $daftarHalaman,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarDetailPenerimaan,
        int      $jumlahHalaman,
        float    $grandTotalJumlah
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page per-item">
    <div class="header center">
        REKAPITULASI PENERIMAAN PER ITEM BARANG<br/>
        INSTALASI FARMASI<br/>
        Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
    </div>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Surat Jalan / Faktur</th>
                <th>Tanggal Datang</th>
                <th>Satuan</th>
                <th>Kuantitas</th>
                <th>Harga Satuan (Rp.)</th>
                <th>Jumlah (Rp.)</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i, "no" => $no, "nama_barang" => $nama, "total_jumlah" => $total]): ?>
            <?php if ($nama): ?>
                <tr style="background:lightgrey;">
                    <td colspan="6"><?= $no . $nama ?></td>
                    <td class="text-right"><?= $toUserFloat($total) ?></td>
                </tr>

            <?php else: ?>
                <?php $dPenerimaan = $daftarDetailPenerimaan[$i] ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $dPenerimaan->noSuratJalan ?: $dPenerimaan->noFaktur ?></td>
                    <td><?= $toUserDate($dPenerimaan->tanggalDatang) ?></td>
                    <td><?= $dPenerimaan->satuan ?></td>
                    <td class="text-right"><?= $toUserInt($dPenerimaan->kuantitas) ?></td>
                    <td class="text-right"><?= $toUserFloat($dPenerimaan->hargaSatuan) ?></td>
                    <td class="text-right"><?= $toUserFloat($dPenerimaan->jumlah) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td colspan="6">Total Gas Medis</td>
                <td class="text-right"><?= $toUserFloat($grandTotalJumlah) ?></td>
            </tr>
        </tfoot>
        <?php endif ?>
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
