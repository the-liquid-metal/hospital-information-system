<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Penerimaan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/print_v_06.php the original file
 */
final class PrintV06
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(GenericData $penerimaan, iterable $daftarDetailPenerimaan, iterable $tabung)
    {
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $total = 0;
        $no = 1;
        $x = 1;
        ?>


<div>Tanggal: <?= $toUserDate($penerimaan->tanggalDokumen) ?></div>

<table>
    <tr>
        <td>No. Terima</td>
        <td>:</td>
        <td><?= $penerimaan->noDokumen ?></td>
    </tr>
    <tr>
        <td>No. BTB</td>
        <td>:</td>
        <td><?= $penerimaan->kode ?></td>
    </tr>
    <tr>
        <td>No. Faktur</td>
        <td>:</td>
        <td><?= $penerimaan->noFaktur ?? "-" ?></td>
    </tr>
    <tr>
        <td>No. Surat Jalan</td>
        <td>:</td>
        <td><?= $penerimaan->noSuratJalan ?? "-" ?></td>
    </tr>
    <tr>
        <td>No. SP/SPK</td>
        <td>:</td>
        <td><?= $penerimaan->noSpk ?></td>
    </tr>
    <tr>
        <td>No. SPB</td>
        <td>:</td>
        <td><?= $penerimaan->noPo ?></td>
    </tr>
    <tr>
        <td>Pengirim</td>
        <td>:</td>
        <td style="text-transform:uppercase"><?= $penerimaan->namaPemasok ?></td>
    </tr>
</table>

<h3>BUKTI PENYERAHAN BARANG</h3>

<br/>

<table class="p_table">
    <thead>
        <tr>
            <th>NO.</th>
            <th>NAMA BARANG</th>
            <th>PABRIK</th>
            <th>NO.</th>
            <th>NO. SERI</th>
            <th>KUANTITAS</th>
            <th>SATUAN BARANG</th>
            <th>@ (Rp.)</th>
            <th>DISKON (%)</th>
            <th>JUMLAH (Rp.)</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarDetailPenerimaan as $detailPenerimaan): ?>
        <?php
        if (isset($tabung[$detailPenerimaan->idKatalog])) {
            $tabung[$detailPenerimaan->idKatalog]++;
            $namabarang = ' ';
            $namapabrik = '';
        } else {
            $tabung[$detailPenerimaan->idKatalog] = 1;
            $x = 1;
            $namabarang = $detailPenerimaan->namaSediaan;
            $namapabrik = $detailPenerimaan->namaPabrik;
        }
        $jumlahKemasan = $detailPenerimaan->jumlahItem / $detailPenerimaan->isiKemasan;
        $sub = ($jumlahKemasan * $detailPenerimaan->hargaKemasan) - ($jumlahKemasan * $detailPenerimaan->hargaKemasan * $detailPenerimaan->diskonItem / 100);
        $total += $sub;
        ?>
        <tr>
            <td><?= ($tabung[$detailPenerimaan->idKatalog] == 1) ? $no++ : "" ?></td>
            <td><?= $namabarang ?></td>
            <td><?= $namapabrik ?></td>
            <th><?= $x++ ?></th>
            <td><?= $detailPenerimaan->noBatch ?></td>
            <td class="text-right"><?= $toUserFloat($detailPenerimaan->jumlahItem) ?></td>
            <td><?= $detailPenerimaan->satuan ?></td>
            <td class="text-right"><?= $toUserFloat($detailPenerimaan->hargaItem) ?></td>
            <td class="text-right"><?= $toUserFloat($detailPenerimaan->diskonItem) ?></td>
            <td class="text-right"><?= $toUserFloat($sub) ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>

    <tfoot>
        <tr>
            <td rowspan="4"></td>
            <td colspan="8">JUMLAH</td>
            <td class="text-right"><?= $toUserFloat($total) ?></td>
        </tr>
        <tr><?php $ppn = $penerimaan->ppn ? ($total * $penerimaan->ppn / 100) : 0 ?>
            <td colspan="8">PPN</td>
            <td class="text-right"><?= $toUserFloat($ppn) ?></td>
        </tr>
        <tr>
            <td colspan="8">PEMBULATAN</td>
            <td class="text-right">0</td>
        </tr>
        <tr>
            <td colspan="8">TOTAL</td>
            <td class="text-right"><?= $toUserFloat($total + $ppn) ?></td>
        </tr>
        <tr>
            <td colspan="10" style="border:0">Terbilang: <?= FH::terbilang($total + $ppn) ?></td>
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
        <td><i class="fa fa-<?= ($penerimaan->verTerima == '1') ? 'check-' : '' ?>square-o"></i></td>
        <td>Tim Penerima</td>
        <td><?= $penerimaan->userTerima ?? "-" ?></td>
        <td><?= $penerimaan->verTanggalTerima ? $toUserDatetime($penerimaan->verTanggalTerima) : "-" ?></td>
        <td><i class="fa fa-square-o"></i></td>
    </tr>
    <tr>
        <td>2</td>
        <td><i class="fa fa-<?= ($penerimaan->verGudang == '1') ? 'check-' : '' ?>square-o"></i></td>
        <td>Gudang</td>
        <td><?= $penerimaan->userGudang ?? "-" ?></td>
        <td><?= $penerimaan->verTanggalGudang ? $toUserDatetime($penerimaan->verTanggalGudang) : "-" ?></td>
        <td><i class="fa fa-<?= ($penerimaan->verGudang == '1') ? 'check-' : '' ?>square-o"></i></td>
    </tr>
    <tr>
        <td>3</td>
        <td><i class="fa fa-<?= ($penerimaan->verAkuntansi == '1') ? 'check-' : '' ?>square-o"></i></td>
        <td>Akuntansi</td>
        <td><?= $penerimaan->userAkuntansi ?? "-" ?></td>
        <td><?= $penerimaan->verTanggalAkuntansi ? $toUserDatetime($penerimaan->verTanggalAkuntansi) : "-" ?></td>
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
