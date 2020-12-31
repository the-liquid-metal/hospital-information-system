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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/printkoreksi.php the original file
 */
final class PrintKoreksi
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string      $listKoreksiWidgetId,
        iterable    $daftarHalaman,
        GenericData $tersedia,
        iterable    $daftarKetersediaan,
        int         $jumlahHalaman,
        float       $total
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserInt = Yii::$app->number->toUserInt();
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> .page:nth-child(1) {
    page-break-before: avoid !important;
}

#<?= $pageId ?> .t_body thead tr td {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    border-left: 1px solid black;
}

#<?= $pageId ?> .t_body tr td {
    border-right: 1px solid black;
    border-left: 1px solid black;
}

#<?= $pageId ?> table.t_body tr:last-child td {
    border-bottom: 1px solid black;
}

#<?= $pageId ?> .t_body tbody .tr_jenis td {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    border-left: 1px solid black;
}

@media print {
    #<?= $pageId ?> .noPrint {
        display: none;
    }

    #<?= $pageId ?> .page2 {
        padding: 0;
        border: none !important;
        border-radius: initial !important;
        box-shadow: none !important;
        background: none !important;
        page-break-after: always;
    }

    #<?= $pageId ?> .page2:last-child {
        page-break-after: avoid !important;
    }
}
</style>

<button class="btn btn-success printBtn"> Print</button>&nbsp;
<a href="<?= $listKoreksiWidgetId ?>">Kembali</a>

<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page print_area">
    <div>Tanggal Cetak: <?= $nowValUser ?></div>
    <div>INSTALASI FARMASI</div>
    <div>LAPORAN KOREKSI OPNAME</div>
    <div>No. Dokumen: <?= $tersedia->noDokumen ?></div>
    <div>Tanggal: <?= $toUserDate($tersedia->tanggalTransaksi) ?></div>
    <div>Unit: <?= $tersedia->namaUnit ?></div>
    <div>Verifikasi: <?= ($tersedia->status == "1") ? $tersedia->nama." Tanggal ".$tersedia->sysdateLast : "" ?></div>

    <table class="t_body">
        <thead>
            <tr>
                <td>No.</td>
                <td>Kode</td>
                <td>Nama Barang</td>
                <td>Pabrik</td>
                <td>Kadaluarsa</td>
                <td>Kuantitas</td>
                <td>Satuan</td>
                <td>Harga Pokok</td>
                <td>Nilai</td>
                <td>Keterangan</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i, "no" => $no, "jenis_obat" => $jenisObat, "subtotal" => $subtotal]): ?>
            <?php if ($jenisObat): ?>
                <tr class="tr_jenis">
                    <td colspan="9">-- <?= $no . $jenisObat ?> --</td>
                    <td class="text-right"><?= $toUserFloat($subtotal) ?> </td>
                </tr>

            <?php else: ?>
                <?php $tersedia = $daftarKetersediaan[$i] ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $tersedia->kode ?></td>
                    <td><?= $tersedia->namaBarang ?></td>
                    <td><?= $tersedia->namaPabrik ?></td>
                    <td><?= $tersedia->tanggalKadaluarsa ?></td>
                    <td class="text-right"><?= $toUserInt($tersedia->jumlahMasuk) ?></td>
                    <td><?= $tersedia->namaKemasan ?></td>
                    <td class="text-right"><?= $toUserFloat($tersedia->hargaPerolehan) ?></td>
                    <td class="text-right"><?= $toUserFloat($tersedia->jumlahMasuk * $tersedia->hargaPerolehan) ?></td>
                    <td><?= $tersedia->keterangan ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($n + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td colspan="7">TOTAL</td>
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
