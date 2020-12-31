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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/print_pengirimantptes.php the original file
 *
 * TODO: php: uncategorized: tidy up
 */
final class PrintPengirimanTpTest
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(GenericData $peringatan, iterable $daftarPeringatan)
    {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";
        $jenis = 0;
        $i = 1;
        $total = 0;
        $subtotal = 0;
        ?>


<style>
#<?= $pageId ?> .tab_header tr td {
    padding: 0 30px 0 0;
}

#<?= $pageId ?> .table_body tr td {
    padding: 4px;
    border: 1px solid #ABABAB;
}

#<?= $pageId ?> .tab_header1 tr td {
    font-size: 12px;
    font-weight: bolder;
}

#<?= $pageId ?> .tab_header1 tr td:last-child {
    font-size: 9px;
    font-weight: normal;
}

@media print {
    #<?= $pageId ?> .print_body table.table_body tr td {
        border-style: none;
    }

    #<?= $pageId ?> .print_body table.table_body tr.tr_body td {
        border-style: none;
    }

    #<?= $pageId ?> .print_area {
        border: none;
        font-size: 9px;
        margin: -13px 10px 0 0;
    }

    #<?= $pageId ?> .table_body tr td {
        border-style: none;
        padding: 0;
        font-size: 9px;
    }

    #<?= $pageId ?> .table_body thead tr th {
        padding: 0;
        line-height: 140%;
    }

    #<?= $pageId ?> .table_body thead tr:first-child th {
        border-top: 1px dashed grey;
        border-bottom: none;
        border-right: none;
        border-left: none;
        padding: 0;
    }

    #<?= $pageId ?> .table_body tbody tr:last-child td {
        border-style: none;
    }

    #<?= $pageId ?> .table_body tbody tr td:nth-child(2) {
        padding: 0 3px 0 0;
    }

    #<?= $pageId ?> .table_body tbody tr td:nth-child(8) {
        padding: 0 4px 0 0;
    }

    #<?= $pageId ?> .table_body tbody tr td:nth-child(9) {
        padding: 0 2px 0 0;
    }

    #<?= $pageId ?> .table_body tbody tr td:nth-child(10) {
        padding: 0 2px 0 0;
    }

    #<?= $pageId ?> .table_body tbody tr td:nth-child(11) {
        padding: 0 3px 0 0;
    }

    #<?= $pageId ?> .table_body tr td.sub_total {
        padding: 0 3px 0 0 !important;
    }

    #<?= $pageId ?> .no_print {
        display: none;
    }

    #<?= $pageId ?> .page-header {
        display: none;
    }

    #<?= $pageId ?> .page-header {
        display: none;
    }

    #<?= $pageId ?> .navbar-inner {
        display: none;
    }

    #<?= $pageId ?> .second {
        display: none;
    }

    #<?= $pageId ?> footer {
        display: none;
    }

    #<?= $pageId ?> .tab_header tr td {
        font-size: 9px;
        padding: 0;
        line-height: 100%;
    }

    #<?= $pageId ?> .table_body tr td {
        padding: 0;
        line-height: 140%;
    }

    #<?= $pageId ?> .tab_header1 tr td {
        line-height: 100%;
        font-size: 10px;
    }

    #<?= $pageId ?> .table thead tr th {
        border-style: none;
    }

    #<?= $pageId ?> .table-striped thead tr th {
        border-style: none;
    }

    #<?= $pageId ?> .table-bordered thead tr th {
        border-top: 1px solid white;
    }

    #<?= $pageId ?> .print_area table.table_body tfoot tr.no_border td {
        border-bottom: 1px dashed grey;
    }

    #<?= $pageId ?> .table_body thead tr:last-child td {
        border: 1px dashed;
    }

    #<?= $pageId ?> .nonprint {
        display: none;
    }
}

@media screen {
    #<?= $pageId ?> footer {
        display: none;
    }
}
</style>


<div class="tab_header1">
    <?= $peringatan->depoAsal ?><br/>
    INSTALASI FARMASI<br/>
    PT Affordable App<br/>
    Tanggal cetak : <?= $nowValUser ?>
</div>

<table class="tab_header">
    <tr>
        <td style="width:15%">No. Permintaan</td>
        <td style="width:25%">: <?= $peringatan->noDokumen ?></td>
    </tr>
    <tr>
        <td style="width:15%">No. Pengeluaran</td>
        <td style="width:25%">: <?= $peringatan->noDokumenPengiriman ?></td>
        <td style="width:15%" rowspan="2">Keterangan</td>
        <td style="width:2%"  rowspan="2">:</td>
        <td style="width:45%" rowspan="2">Pindah Gudang dari <?= $peringatan->depoAsal ?> ke <?= $peringatan->depoTujuan ?></td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>: <?= $toUserDate($peringatan->tanggal) ?></td>
    </tr>
    <tr>
        <td>Unit Asal</td>
        <td>: <?= $peringatan->depoAsal ?></td>
        <?php if ($peringatan->tanggalVerifikasi): ?>
            <td>Verifikasi</td>
            <td>:</td>
            <td>
                <?= $peringatan->namaVerifikasi ?>,
                tanggal <?= $toUserDatetime($peringatan->tanggalVerifikasi) ?>
            </td>
        <?php else: ?>
            <td></td>
            <td></td>
            <td></td>
        <?php endif ?>
    </tr>
    <tr>
        <td>Unit Tujuan</td>
        <td>: <?= $peringatan->depoTujuan ?></td>
        <td colspan="3"></td>
    </tr>
</table>

<table class="table_body table-striped">
    <thead>
        <tr>
            <th>No.</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>No. Batch</th>
            <th>Pabrik</th>
            <th>Stok Peminta</th>
            <th>Stok Pengirim</th>
            <th>Jumlah Dikirim</th>
            <th>Satuan</th>
            <th>Harga Pokok</th>
            <th>Nilai</th>
        </tr>
    </thead>

    <tbody>
    <?php
    foreach ($daftarPeringatan as $peringatan2) {
        if ($peringatan2->namaJenis != $jenis) {
            if ($jenis != "0") {
                ?>
                <tr>
                    <td colspan="10">Sub Total</td>
                    <td class="text-right sub_total"><?= $toUserFloat($subtotal) ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="11">-- <?= $peringatan2->namaJenis ?> --</td>
            </tr>
            <?php
            $subtotal = 0;
        }

        $nilai = $peringatan2->jumlah2 * $peringatan2->hpItem;
        $subtotal += $nilai;
        $total += $nilai;
        ?>

        <tr>
            <td><?= $i ?></td>
            <td><?= $peringatan2->kodeItem ?></td>
            <td><?= $peringatan2->namaItem ?></td>
            <td><?= $peringatan2->noBatch ?></td>
            <td><?= $peringatan2->namaPabrik ?></td>
            <td class="text-right"><?= $toUserInt($peringatan2->stokPeminta) ?></td>
            <td class="text-right"><?= $toUserInt($peringatan2->stokPengirim) ?></td>
            <td class="text-right"><?= $toUserInt($peringatan2->jumlah2) ?></td>
            <td><?= $peringatan2->namaKemasan ?></td>
            <td class="text-right"><?= $toUserFloat($peringatan2->hpItem) ?></td>
            <td class="text-right"><?= $toUserFloat($nilai) ?></td>
        </tr>
        <?php
        $jenis = $peringatan2->namaJenis;
        $i++;
    }
    ?>
    <tr>
        <td colspan="10">Sub Total</td>
        <td class="text-right sub_total"><?= $toUserFloat($subtotal) ?></td>
    </tr>
    <tr>
        <td colspan="9">Total</td>
        <td class="text-right sub_total" colspan="2"><?= $toUserFloat($total) ?></td>
    </tr>
    </tbody>
</table>

<table>
    <tr>
        <td style="width:10%"></td>
        <td style="width:25%; text-align:center">PENERIMA BARANG</td>
        <td style="width:30%"></td>
        <td style="width:25%; text-align:center">PEMBERI BARANG</td>
        <td style="width:10%"></td>
    </tr>
    <tr>
        <td colspan="5"> </td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center">..............................</td>
        <td></td>
        <td style="text-align:center">..............................</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>NIP.</td>
        <td></td>
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
