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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/printexpired.php the original file
 */
final class PrintExpired
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string      $listExpiredWidgetId,
        iterable    $daftarHalaman,
        GenericData $kadaluarsa,
        iterable    $daftarKadaluarsa,
        int         $jumlahHalaman,
        float       $total
    ) {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> .page2 {
    width: 230mm;
    margin: 0 25mm 25mm 0;
    padding: 10mm;
    border: 1px #D3D3D3 solid;
    border-radius: 5px;
    background: white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

#<?= $pageId ?> .t_body thead tr td {
    border: 1px solid black;
    padding: 5px;
    background-color: #ffe5ff;
}

#<?= $pageId ?> .t_body tr td {
    border: 1px solid black;
    font-size: 13px;
    padding-top: 4px;
    padding-bottom: 4px;
}

#<?= $pageId ?> table.t_body tr:last-child td {
    border-bottom: 1px solid black;
}

#<?= $pageId ?> .t_body tbody .tr_jenis td {
    background-color: #f2f2f2;
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
        box-shadow: none !important;
        background: none !important;
        page-break-after: always;
    }

    #<?= $pageId ?> .page2:last-child {
        page-break-after: avoid !important;
    }
}
</style>

<button class="printBtn"> Print</button>&nbsp;
<a href="<?= $listExpiredWidgetId ?>">Kembali</a>

<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page2 print_area">
    <div>Tanggal Cetak : <?= $nowValUser ?></div>
    <div>INSTALASI FARMASI</div>
    <div>LAPORAN BARANG KADALUARSA</div>
    <div>No. Dokumen: <?= $kadaluarsa->noDokumen ?></div>
    <div>Tanggal: <?= $toUserDate($kadaluarsa->tanggalTersedia) ?></div>
    <div>Unit: <?= $kadaluarsa->namaUnit ?></div>
    <div>Verifikasi : <?= ($kadaluarsa->status == "1") ? $kadaluarsa->nama." Tanggal ".$kadaluarsa->tanggalDokumen : "" ?></div>

    <table class="t_body">
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
                <?php $kadaluarsa = $daftarKadaluarsa[$i] ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $kadaluarsa->kode ?></td>
                    <td><?= $kadaluarsa->namaBarang ?></td>
                    <td><?= $kadaluarsa->namaPabrik ?></td>
                    <td><?= $toUserDate($kadaluarsa->tanggalKadaluarsa) ?></td>
                    <td><?= $kadaluarsa->noBatch ?></td>
                    <td class="text-right"><?= $toUserInt($kadaluarsa->jumlahTersedia) ?></td>
                    <td><?= $kadaluarsa->namaKemasan ?></td>
                    <td class="text-right"><?= $toUserFloat($kadaluarsa->hpItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($kadaluarsa->jumlahTersedia * $kadaluarsa->hpItem) ?></td>
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
