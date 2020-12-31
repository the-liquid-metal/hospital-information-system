<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Transaksi;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/print_pengambilan.php the original file
 *
 * TODO: php: uncategorized: tidy up
 */
final class PrintPengambilan
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(GenericData $peringatan, iterable $daftarPeringatan, string $header)
    {
        $todayValUser = Yii::$app->dateTime->todayVal("user");
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<script>
$(() => {
    "use strict";

    document.querySelector("#<?= $pageId ?> .printBtn").addEventListener("click", () => {
        const printContents = document.getElementById("body").innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    });
});
</script>

<h3>FORMULIR PENGAMBILAN BARANG</h3>
<p>Klasifikasi: <?= $peringatan->prioritas ?></p>

<table>
    <tr>
        <td style="width:30%">BIDANG / BAGIAN / INST / UNIT</td>
        <td>: <?= $peringatan->depoAmbil ?></td>
    </tr>
    <tr>
        <td style="width:30%">Peminta</td>
        <td>: <?= $peringatan->namaDepoPeminta ?></td>
    </tr>
    <tr>
        <td>NO.</td>
        <td>: <?= $peringatan->noDokumen ?></td>
    </tr>
    <tr>
        <td>TANGGAL</td>
        <td>: <?= $todayValUser ?></td>
    </tr>
    <tr>
        <td>1. BARANG MEDIS</td>
        <td>: BARANG FARMASI</td>
    </tr>
    <tr>
        <td>2. BARANG NON MEDIS</td>
        <td>: -</td>
    </tr>
</table>

<table class="table table-striped table-bordered" id="list-warning">
    <thead>
        <tr>
            <th>NO.</th>
            <th>NAMA BARANG</th>
            <th>PABRIK</th>
            <th>SISA STOK</th>
            <th>SATUAN</th>
            <th>JUMLAH YANG DIMINTA</th>
            <th>JUMLAH YANG DIBERIKAN</th>
            <th>HARGA SATUAN</th>
            <th>JUMLAH HARGA</th>
        </tr>
    </thead>
    <tbody>
<?php
$footer = '</tbody></table>';

$jenis = "";
$i = 1;
$total = 0;
$subtotal = 0;
$pg = 0;
foreach ($daftarPeringatan as $peringatan2) {
    if ($pg >= 25) {
        echo $footer;
        echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
        echo $header;
        $pg = 0;
    }
    if ($peringatan2->jenisObat != $jenis) {
        if ($jenis) {
            ?>
            <tr>
                <td colspan="8">Sub Total</td>
                <td><?= $subtotal ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="9">-- <?= $peringatan2->jenisObat ?> --</td>
        </tr>
        <?php
        $subtotal = 0;
        $pg++;
    }
    $nilai = $peringatan2->jumlah2 * $peringatan2->hargaJual;
    $subtotal += $nilai;
    $total += $nilai;
    ?>

    <tr>
        <td><?= $i ?></td>
        <td><?= $peringatan2->namaBarang ?></td>
        <td><?= $peringatan2->namaPabrik ?></td>
        <td class="text-right"><?= $toUserFloat($peringatan2->jumlah) ?></td>
        <td><?= $peringatan2->namaKemasan ?></td>
        <td class="text-right"><?= $toUserFloat($peringatan2->jumlah1) ?></td>
        <td></td>
        <td class="text-right"><?= $toUserFloat($peringatan2->hargaJual) ?></td>
        <td class="text-right"><?= $toUserFloat($nilai) ?></td>
    </tr>
    <?php
    $jenis = $peringatan2->jenisObat;
    $i++;
    $pg++;
}
?>
<tr>
    <td class="text-right" colspan="8">Total</td>
    <td class="text-right"><?= $toUserFloat($total) ?></td>
</tr>
<?= $footer ?>

<br/> <br/>

<table>
    <tr>
        <td style="width:10%"></td>
        <td style="width:25%; text-align:center">PENERIMA BARANG</td>
        <td style="width:30%; text-align:center">PEMAKAI BARANG</td>
        <td style="width:25%; text-align:center">PEMBERI BARANG</td>
        <td style="width:10%"></td>
    </tr>
    <tr>
        <td colspan="5"> </td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center">..............................</td>
        <td style="text-align:center">..............................</td>
        <td style="text-align:center">..............................</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>NIP.</td>
        <td>NIP.</td>
        <td>NIP.</td>
        <td></td>
    </tr>
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
