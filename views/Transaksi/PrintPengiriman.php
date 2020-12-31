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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/print_pengiriman.php the original file
 *
 * TODO: php: uncategorized: tidy up
 */
final class PrintPengiriman
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
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        $pageId = "";
        $jenis = 0;
        $subtotal = 0;
        $total = 0;
        $i = 1;
        ?>


<style>
@media screen {
    #<?= $pageId ?> footer {
        display: none;
    }
}

#<?= $pageId ?> .tab_header tr td {
    padding: 0 30px 0 10px;
    font-size: 13px;
    line-height: 150%;
}

#<?= $pageId ?> .tab_header1 tr td {
    padding: 0 0 0 10px;
    font-size: 14px;
    font-weight: bolder;
}

#<?= $pageId ?> .tab_header1 tr td p {
    font-size: 11px;
}

#<?= $pageId ?> .tab_header1 tr td:last-child {
    font-size: 9px;
    font-weight: normal;
}

#<?= $pageId ?> .table_body tr td {
    padding: 4px;
    border: 1px solid #ABABAB;
}

#<?= $pageId ?> .table_body thead tr th {
    padding: 4px;
    font-size: 14px;
    text-align: center;
    border: 1px solid #ABABAB;
}

@media print {
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

    #<?= $pageId ?> .print_area {
        border: none;
        font-size: 9px;
        margin: -17px 10px 0;
    }

    #<?= $pageId ?> .tab_header tr td {
        font-size: 9px;
        padding: 0;
        line-height: 130%;
    }

    #<?= $pageId ?> .tab_header tr td:nth-child(3) {
        width: 5%;
    }

    #<?= $pageId ?> .tab_header1 tr td {
        padding: 0 0 0 0;
        line-height: 100%;
        font-size: 10px;
    }

    #<?= $pageId ?> .tab_header1 tr td p {
        font-size: 8px;
    }

    #<?= $pageId ?> .table_body {
        width: 100%;
    }

    #<?= $pageId ?> .table_body tr td {
        border-style: none;
        padding: 0;
        font-size: 8px;
    }

    #<?= $pageId ?> .table_body tr td {
        padding: 0;
        line-height: 140%;
    }

    #<?= $pageId ?> .table_body thead tr th {
        padding: 0;
        line-height: 120%;
        text-align: center;
        font-size: 8px;
    }

    #<?= $pageId ?> .table_body thead tr th:nth-child(4) {
        padding-right: 3px;
    }

    #<?= $pageId ?> .table_body tbody tr td:nth-child(3) {
        padding-left: 4px;
    }

    #<?= $pageId ?> .table {
        border-spacing: 0;
    }

    #<?= $pageId ?> tr,
    #<?= $pageId ?> th,
    #<?= $pageId ?> td {
        padding: 0;
        border: none #fff;
    }

    #<?= $pageId ?> .nonprint {
        display: none;
    }

    #<?= $pageId ?> th {
        border: 1px dashed black !important;
        border-right: 0 solid white !important;
        border-left: 0 solid white !important;
    }

    #<?= $pageId ?> td:nth-child(3) {
        width: 30%;
    }

    #<?= $pageId ?> .td_padding {
        padding: 0;
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
        <td style="width:15%" rowspan="3">Keterangan</td>
        <td style="width:2%"  rowspan="3">:</td>
        <td style="width:45%" rowspan="3">Pindah Gudang dari <?= $peringatan->depoAsal ?> ke <?= $peringatan->depoTujuan ?></td>
    </tr>
    <tr>
        <td style="width:15%">No. Pengeluaran</td>
        <td style="width:25%">: <?= $peringatan->noDokumenPengiriman ?></td>
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
            <th>Batch</th>
            <th>Pabrik</th>
            <th>Stok <?= $peringatan->depoTujuan ?></th>
            <th>Stok <?= $peringatan->depoAsal ?></th>
            <th>Jumlah Diminta</th>
            <th>Jumlah Dikirim</th>
            <th>Satuan</th>
            <th>Harga Pokok</th>
            <th>Nilai</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarPeringatan as $peringatan2): ?>
        <?php if ($peringatan2->namaJenis != $jenis): ?>
            <?php if ($jenis != "0"): ?>
                <tr>
                    <td colspan="11" class="td_padding">Sub Total</td>
                    <td class="text-right"><?= $toUserFloat($subtotal) ?></td>
                </tr>
            <?php endif ?>

            <tr>
                <td colspan="12" class="td_padding">-- <?= $peringatan2->namaJenis ?> --</td>
            </tr>
            <?php $subtotal = 0 ?>
        <?php endif ?>

        <?php
        $nilai = $peringatan2->jumlah2 * $peringatan2->hpItem;
        $subtotal += $nilai;
        $total += $nilai;
        ?>
        <tr>
            <td class="td_padding"><?= $i ?></td>
            <td class="td_padding"><?= $peringatan2->kodeItem ?></td>
            <td class="td_padding"><?= $peringatan2->namaItem ?></td>
            <td class="td_padding"><?= $peringatan2->noBatch ?></td>
            <td class="td_padding"><?= $peringatan2->namaPabrik ?></td>
            <td class="text-right td_padding"><?= $toUserInt($peringatan2->stokpeminta) ?></td>
            <td class="text-right td_padding"><?= $toUserInt($peringatan2->stokpengirim) ?></td>
            <td class="text-right td_padding"><?= $toUserInt($peringatan2->jumlah1) ?></td>
            <td class="text-right td_padding"><?= $toUserInt($peringatan2->jumlah2) ?></td>
            <td class="td_padding"><?= $peringatan2->namaKemasan ?></td>
            <td class="text-right td_padding"><?= $toUserFloat($peringatan2->hpItem) ?></td>
            <td class="text-right td_padding"><?= $toUserFloat($nilai) ?></td>
        </tr>
        <?php
        $jenis = $peringatan2->namaJenis;
        $i++;
        ?>
    <?php endforeach ?>

        <tr>
            <td class="td_padding td_padding" colspan="11">Sub Total</td>
            <td class="text-right"><?= $toUserFloat($subtotal) ?></td>
        </tr>
        <tr>
            <td class="text-right td_padding" colspan="11">Total</td>
            <td class="text-right"><?= $toUserFloat($total) ?></td>
        </tr>
    </tbody>
</table>

<br/>

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
