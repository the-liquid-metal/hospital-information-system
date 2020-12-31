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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/print_pengiriman22.php the original file
 */
final class PrintPengiriman22
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(GenericData $peringatan, iterable $daftarPeringatan)
    {
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $nowValUser = Yii::$app->dateTime->nowVal("user");
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
#<?= $pageId ?> .tab_header tr td {
    padding: 0 30px 0 0;
}

#<?= $pageId ?> .table_body tr td {
    padding: 4px;
    border: 1px solid grey;
}

#<?= $pageId ?> .tab_header1 tr td {
    font-size: 12px;
    font-weight: bolder;
}

#<?= $pageId ?> .tab_header1 tr td:last-child {
    font-size: 9px;
}

@media print {
    #<?= $pageId ?> .print_area {
        border: none;
        font-size: 9px;
        margin: -20px 10px 10px;
    }

    #<?= $pageId ?> .table_body tr td {
        border-style: none;
        padding: 0;
        font-size: 9px;
    }

    #<?= $pageId ?> .table_body thead tr:first-child th {
        border-top: 1px dashed grey;
        border-bottom: none;
        border-right: none;
        border-left: none;
        padding: 0;
    }

    #<?= $pageId ?> .table_body tbody tr:last-child td {
        border-top: none;
        border-bottom: 1px dashed grey;
        border-right: none;
        border-left: none;
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
        line-height: 150%;
    }

    #<?= $pageId ?> .table_body tr td {
        padding: 0;
        line-height: 140%;
    }

    #<?= $pageId ?> .tab_header1 tr td {
        line-height: 100%;
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

    #<?= $pageId ?> .no_border {
        border-top: none;
        border-bottom: none;
    }

    #<?= $pageId ?> .table_body thead tr:last-child td {
        border: 1px dashed;
    }
}

@media screen {
    #<?= $pageId ?> .table_body tr td {
        padding: 3px;
        border: 1px solid grey;
    }

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
        <td style="width:18%">No. Permintaan</td>
        <td style="width:28%">: <?= $peringatan->noDokumen ?></td>
    </tr>
    <tr>
        <td style="width:18%">No. Pengeluaran</td>
        <td style="width:28%">: <?= $peringatan->noDokumenPengiriman ?></td>
    </tr>
    <tr>
        <td style="width:18%">No. Pengeluaran</td>
        <td style="width:28%">: <?= $peringatan->noDokumenPengiriman ?? $peringatan->noDokumen ?></td>
        <td style="width:12%" rowspan="2">Keterangan</td>
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
        <?php if (!empty($peringatan->tanggalVerifikasi)): ?>
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
            <th>Pabrik</th>
            <th>Kuantitas</th>
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
                    <td colspan="7">Sub Total</td>
                    <td class="text-right"><?= $toUserFloat($subtotal) ?></td>
                </tr>
            <?php endif ?>
            <tr>
                <td colspan="8">-- <?= $peringatan2->namaJenis ?> --</td>
            </tr>
            <?php $subtotal = 0; ?>
        <?php endif ?>

        <?php
        $nilai = $peringatan2->jumlah2 * $peringatan2->hpItem;
        $subtotal += $nilai;
        $total += $nilai;
        ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $peringatan2->kodeItem ?></td>
            <td><?= $peringatan2->namaItem ?></td>
            <td><?= $peringatan2->namaPabrik ?></td>
            <td class="text-right"><?= $toUserInt($peringatan2->jumlah2) ?></td>
            <td><?= $peringatan2->namaKemasan ?></td>
            <td class="text-right"><?= $toUserFloat($peringatan2->hpItem) ?></td>
            <td class="text-right"><?= $toUserFloat($nilai) ?></td>
        </tr>
        <?php
        $jenis = $peringatan2->namaJenis;
        $i++;
        ?>
    <?php endforeach ?>
    <tr>
        <td colspan="7">Sub Total</td>
        <td class="text-right"><?= $toUserFloat($subtotal) ?></td>
    </tr>
    <tr class="no_border">
        <td colspan="7">Total</td>
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
