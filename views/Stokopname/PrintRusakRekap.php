<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Stokopname;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/printrusakrekap.php the original file
 */
final class PrintRusakRekap
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(iterable $daftarHalaman, iterable $daftarRusak, int $jumlahHalaman, float $grandTotal)
    {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page2 print_area">
    <div>INSTALASI FARMASI</div>
    <div>LAPORAN REKAP BARANG RUSAK</div>
    <div>Tanggal Cetak: <?= $nowValUser ?></div>

    <table class="dataTbl">
        <thead>
            <tr>
                <td>No.</td>
                <td>Kode</td>
                <td>Nama Barang</td>
                <td>Pabrik</td>
                <td>Kadaluarsa</td>
                <td>No. Batch</td>
                <td>Kuantitas</td>
                <td>Satuan</td>
                <td>Harga Pokok</td>
                <td>Nilai</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->noDokumen)): ?>
                <tr class="tr_doc">
                    <td colspan="8"><strong><?= $baris->no ?> No. Dokumen: <?= $baris->noDokumen ?></strong></td>
                    <td class="text-right" colspan="2"><?= $toUserFloat($baris->total) ?> </td>
                </tr>

            <?php elseif (isset($baris->jenisObat)): ?>
                <tr class="tr_jenis">
                    <td colspan="8">--- <?= $baris->no . $baris->jenisObat ?> ---</td>
                    <td class="text-right" colspan="2"><?= $toUserFloat($baris->subtotal) ?> </td>
                </tr>

            <?php else: ?>
                <?php $rusak = $daftarRusak[$baris->i] ?>
                <tr class="tr_detail">
                    <td><?= $baris->no ?></td>
                    <td><?= $rusak->kode ?></td>
                    <td><?= $rusak->namaBarang ?></td>
                    <td><?= $rusak->namaPabrik ?></td>
                    <td><?= $toUserDate($rusak->tanggalRusak) ?></td>
                    <td><?= $rusak->noBatch ?></td>
                    <td><?= $toUserInt($rusak->jumlahTersedia) ?></td>
                    <td><?= $rusak->namaKemasan ?></td>
                    <td><?= $toUserFloat($rusak->hpItem) ?></td>
                    <td><?= $toUserFloat($rusak->jumlahTersedia * $rusak->hpItem) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($n + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td colspan="8">TOTAL</td>
                <td class="text-right" colspan="2"><?= $toUserFloat($grandTotal) ?></td>
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
