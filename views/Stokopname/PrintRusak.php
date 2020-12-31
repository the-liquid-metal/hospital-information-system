<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Stokopname;

use tlm\libs\LowEnd\components\{DateTimeException, GenericData};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/printrusak.php the original file
 */
final class PrintRusak
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        iterable    $daftarHalaman,
        GenericData $rusak,
        iterable    $daftarRusak,
        int         $jumlahHalaman,
        float       $total
    ) {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page2 print_area">
    <div>Tanggal Cetak: <?= $nowValUser ?></div>
    <div>INSTALASI FARMASI</div>
    <div>LAPORAN BARANG RUSAK</div>
    <div>No. Dokumen: <?= $rusak->noDokumen ?></div>
    <div>Tanggal: <?= $toUserDate($rusak->tanggalDokumen) ?></div>
    <div>Unit: <?= $rusak->namaUnit ?></div>
    <div>Verifikasi: <?= ($rusak->status == "1") ? $rusak->nama." Tanggal ".$rusak->sysdateIn : "" ?></div>

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
        <?php foreach ($halaman as ["i" => $i, "no" => $no, "jenis_obat" => $jenisObat, "subtotal" => $subtotal]): ?>
            <?php if ($jenisObat): ?>
                <tr class="tr_jenis">
                    <td colspan="8">-- <?= $no . $jenisObat ?> --</td>
                    <td class="text-right" colspan="2"><?= $toUserFloat($subtotal) ?> </td>
                </tr>

            <?php else: ?>
                <?php $rusak = $daftarRusak[$i] ?>
                <tr class="tr_detail">
                    <td><?= $no ?></td>
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
                <td class="text-right" colspan="2"><?= $toUserFloat($total) ?></td>
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
