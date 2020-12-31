<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanKasir;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/print_rekapitulasi_setoran_harian2.php the original file
 *
 * TODO: php: uncategorized: tidy up
 */
final class ReportKasir
{
    private string $output;

    public function __construct(
        string   $username,
        iterable $daftarPenjualan,
        array    $daftarSubtotalJenisResep,
        array    $daftarSubtotalCaraBayar,
        int      $totalHargaJual,
        int      $totalDiskon,
        int      $totalJasaPelayanan,
        int      $totalPembulatan,
        int      $totalJumlah,
        string   $tanggalAwal
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<div class="header">
    REKAPITULASI SETORAN HARIAN<br/>
    Kasir: <?= $username ?><br/>
    Tanggal: <?= $tanggalAwal ?>
</div>

<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>No. Pembayaran</th>
            <th>No. Resep / Kode Rekam Medis / Nama</th>
            <th>Cara Bayar</th>
            <th>Harga Jual</th>
            <th>Diskon</th>
            <th>Jasa Pelayanan</th>
            <th>Pembulatan</th>
            <th>Jumlah</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarPenjualan as $i => $penjualan): ?>
        <?php if ($penjualan->isHead): ?>
        <tr class="jenis">
            <td colspan="8"><?= $penjualan->head ?></td>
        </tr>
        <?php endif ?>

        <tr class="isi">
            <td><?= $penjualan->noPembayaran ?></td>
            <td><?= $penjualan->kodeRekamMedis ?></td>
            <td><?= $penjualan->caraBayar ?></td>
            <td class="text-right"><?= $toUserFloat($penjualan->hargaJual) ?></td>
            <td class="text-right"><?= $toUserFloat($penjualan->diskon) ?></td>
            <td class="text-right"><?= $toUserFloat($penjualan->jasaPelayanan) ?></td>
            <td class="text-right"><?= $toUserFloat($penjualan->pembulatan) ?></td>
            <td class="text-right"><?= $toUserFloat($penjualan->jumlah) ?></td>
        </tr>

        <?php if ($penjualan->isTail): ?>
        <?php $subtotal = $daftarSubtotalJenisResep[$i] ?>
        <tr class="subtotal">
            <td class="text-right" colspan="3">Subtotal</td>
            <td class="text-right"><?= $toUserFloat($subtotal->hargaJual) ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->diskon) ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->jasaPelayanan) ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->pembulatan) ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->jumlah) ?></td>
        </tr>
        <?php endif ?>
    <?php endforeach ?>

    <?php foreach ($daftarSubtotalCaraBayar as $key => $subtotal): ?>
        <tr class="total">
            <td class="text-right" colspan="3">TOTAL <?= $key ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->hargaJual) ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->diskon) ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->jasaPelayanan) ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->pembulatan) ?></td>
            <td class="text-right"><?= $toUserFloat($subtotal->jumlah) ?></td>
        </tr>
    <?php endforeach ?>

        <tr class="total">
            <td class="text-right" colspan="3">TOTAL</td>
            <td class="text-right"><?= $toUserFloat($totalHargaJual) ?></td>
            <td class="text-right"><?= $toUserFloat($totalDiskon) ?></td>
            <td class="text-right"><?= $toUserFloat($totalJasaPelayanan) ?></td>
            <td class="text-right"><?= $toUserFloat($totalPembulatan) ?></td>
            <td class="text-right"><?= $toUserFloat($totalJumlah) ?></td>
        </tr>
    </tbody>
</table>

<table class="table_footer">
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Depo</td>
        <td style="width:3%">&nbsp;</td>
        <td>Penerima</td>
        <td style="width:3%">&nbsp;</td>
        <td>Kasir</td>
    </tr>
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
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
