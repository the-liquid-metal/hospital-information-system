<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ReturFarmasi;

use tlm\libs\LowEnd\components\{DateTimeException, FormHelper as FH, GenericData};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/print_v1.php the original file
 */
final class PrintV1
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(GenericData $retur, iterable $daftarDetailRetur)
    {
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        ob_clean();
        ob_start();
        $total = 1;
        $pageId = "";
        ?>


<style>
@media print {
    #<?= $pageId ?> h3 {
        font-size: 15px;
        text-align: center;
    }

    #<?= $pageId ?> .p_table {
        border-collapse: collapse;
    }

    #<?= $pageId ?> .p_table tr {
        border: 0;
    }

    #<?= $pageId ?> .p_table tr th,
    #<?= $pageId ?> .p_table tr td {
        border: 1px solid black;
    }

    #<?= $pageId ?> .noPrint {
        display: none;
    }
}
</style>

<table>
    <tr>
        <td>No. Retur</td>
        <td>:</td>
        <td><?= $retur->noDokumen ?></td>
    </tr>
    <tr>
        <td>No. Ref. Terima</td>
        <td>:</td>
        <td><?= $retur->noPenerimaan ?></td>
    </tr>
    <tr>
        <td>No. BTB</td>
        <td>:</td>
        <td><?= $retur->kode ?></td>
    </tr>
    <tr>
        <td>No. SP/SPK</td>
        <td>:</td>
        <td><?= $retur->noSpk ?></td>
    </tr>
    <tr>
        <td>Pemasok</td>
        <td>:</td>
        <td style="text-transform:uppercase"><?= $retur->namaPemasok ?></td>
    </tr>
</table>

<h3>BUKTI RETUR BARANG</h3>

<table class="p_table">
    <thead>
        <tr>
            <th>NO.</th>
            <th>NAMA BARANG</th>
            <th>PABRIK</th>
            <th>KUANTITAS</th>
            <th>SATUAN BARANG</th>
            <th>@ (Rp.)</th>
            <th>DISKON (%)</th>
            <th>JUMLAH (Rp.)</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarDetailRetur as $i => $detailRetur): ?>
        <?php
        $sub = $detailRetur->jumlahKemasan * $detailRetur->hargaKemasan * (100 - $detailRetur->diskonItem) / 100;
        $total += $sub;
        ?>
        <tr>
            <td><?= ++$i ?></td>
            <td><?= $detailRetur->namaSediaan ?></td>
            <td><?= $detailRetur->namaPabrik ?></td>
            <td class="text-right"><?= $toUserFloat($detailRetur->jumlahItem) ?></td>
            <td class="text-right"><?= $toUserInt($detailRetur->satuan) ?></td>
            <td class="text-right"><?= $toUserFloat($detailRetur->hargaItem) ?></td>
            <td class="text-right"><?= $toUserFloat($detailRetur->diskonItem) ?></td>
            <td class="text-right"><?= $toUserFloat($sub) ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>

    <tfoot>
        <tr>
            <td rowspan="4"></td>
            <td colspan="6">JUMLAH</td>
            <td class="text-right"><?= $total ?></td>
        </tr>
        <tr><?php $ppn = ($retur->ppn == 0) ? 0 : $total * $retur->ppn / 100 ?>
            <td colspan="6">PPN</td>
            <td class="text-right"><?= $ppn ?></td>
        </tr>
        <tr>
            <td colspan="6">PEMBULATAN</td>
            <td class="text-right">0</td>
        </tr>
        <tr>
            <td colspan="6">TOTAL</td>
            <td class="text-right"><?= $toUserInt($total + $ppn) ?></td>
        </tr>
        <tr>
            <td colspan="8" style="border:0">Terbilang: <?= FH::terbilang($total + $ppn) ?></td>
        </tr>
    </tfoot>
</table>

<br/>

<table class="table table-bordered text-center">
    <tr>
        <th>Ver. No.</th>
        <th>Ver.</th>
        <th>Otorisasi</th>
        <th>User</th>
        <th>Tanggal</th>
        <th>Update Stok</th>
    </tr>
    <tr>
        <td>1</td>
        <td><i class="fa fa-<?= ($retur->verGudang == '1') ? 'check' : '' ?>-square-o"></i></td>
        <td>Gudang</td>
        <td><?= $retur->userGudang ?? "-" ?></td>
        <td><?= $retur->verTanggalGudang ? $toUserDatetime($retur->verTanggalGudang) : "-" ?></td>
        <td><i class="fa fa-<?= ($retur->verGudang == '1') ? 'check' : '' ?>-square-o"></i></td>
    </tr>
    <tr>
        <td>2</td>
        <td><i class="fa fa-<?= ($retur->verTerima == '1') ? 'check' : '' ?>-square-o"></i></td>
        <td>Tim Penerima</td>
        <td><?= $retur->userTerima ?? "-" ?></td>
        <td><?= $retur->verTanggalTerima ? $toUserDatetime($retur->verTanggalTerima) : "-" ?></td>
        <td><i class="fa fa-square-o"></i></td>
    </tr>
    <tr>
        <td>3</td>
        <td><i class="fa fa-<?= ($retur->verAkuntansi == '1') ? 'check' : '' ?>-square-o"></i></td>
        <td>Akuntansi</td>
        <td><?= $retur->userAkuntansi ?? "-" ?></td>
        <td><?= $retur->verTanggalAkuntansi ? $toUserDatetime($retur->verTanggalAkuntansi) : "-" ?></td>
        <td><i class="fa fa-square-o"></i></td>
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
