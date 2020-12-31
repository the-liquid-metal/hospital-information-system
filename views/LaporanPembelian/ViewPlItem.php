<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPembelian;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/pembelian/views.php the original file
 */
final class ViewPlItem
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string      $editPembelianFormWidgetId,
        string      $printPembelianWidgetId,
        string      $viewPerencanaanWidgetId,
        string      $viewPengadaanWidgetId,
        string      $penerimaanTableWidgetId,
        GenericData $pembelian,
        string      $anggaran,
        string      $jenisPl,
        iterable    $daftarData,
        float       $totalSebelumDiskon,
        float       $totalDiskon,
        float       $totalSetelahDiskon,
        float       $totalPpn,
        float       $totalSetelahPpn,
        float       $pembulatan,
        float       $totalSetelahPembulatan
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $preferInt = Yii::$app->number->preferInt();
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php if ($pembelian->statusLinked == '0' && $pembelian->statusClosed == '0'): ?>
<a class="btn-info" href="<?= $editPembelianFormWidgetId .'/'.$pembelian->kode ?>">EDIT</a>
<?php endif ?>
<a class="btn-success " href="<?= $printPembelianWidgetId .'/'.$pembelian->kode ?>">CETAK</a>

<table class="table well">
    <tr>
        <td  style="width:15%">Kode Transaksi</td>
        <td  style="width:34%">: <?= $pembelian->kode ?></td>
        <td  style="width:2%"></td>
        <td  style="width:15%">Nama Pemasok</td>
        <td  style="width:34%">: <?= $pembelian->namaPemasok ?></td>
    </tr>
    <tr>
        <td>No. Dokumen</td>
        <td>: <?= $pembelian->noDokumen ?></td>
        <td></td>
        <td>Jenis Anggaran</td>
        <td>: <?= $pembelian->subjenisAnggaran ?></td>
    </tr>
    <tr>
        <td>Tanggal Dokumen</td>
        <td>: <?= $toUserDate($pembelian->tanggalDokumen) ?> Jatuh Tempo: <?= $toUserDate($pembelian->tanggalJatuhTempo) ?></td>
        <td></td>
        <td>Anggaran</td>
        <td>: <?= $anggaran ?></td>
    </tr>
    <tr>
        <td>Ref. HPS</td>
        <td>: <?= $pembelian->noHps ?></td>
        <td></td>
        <td>Sumber Dana</td>
        <td>: <?= $pembelian->sumberDana ?> (SUB: <?= $pembelian->subsumberDana ?>)</td>
    </tr>
    <tr>
        <td>Ref. Perencanaan</td>
        <td>: <?= $pembelian->noDokumenRef ?></td>
        <td></td>
        <td>Jenis Harga</td>
        <td>: <?= $pembelian->jenisHarga ?> (Cara Bayar: <?= $pembelian->caraBayar ?>)</td>
    </tr>
    <tr>
        <td>Jenis PL</td>
        <td>: <?= $jenisPl ?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>

<?php if ($pembelian->revisike): ?>
<table class="table well">
    <tr>
        <td style="width:15%">Revisi Ke-</td>
        <td style="width:34%">: <?= $pembelian->revisiKe ?></td>
        <td style="width:2%"></td>
        <td style="width:15%">Keterangan</td>
        <td style="width:34%">: <?= $pembelian->keterangan ?></td>
    </tr>
    <tr>
        <td>Status Revisi</td>
        <td>: <?= ($pembelian->verRevisi == "1") ? "Sudah Verifikasi ULP" : "Belum Verifikasi Revisi oleh ULP" ?></td>
        <td></td>
        <td>Keterangan Revisi</td>
        <td>: <?= $pembelian->keteranganRev ?></td>
    </tr>
</table>
<?php endif ?>

<table class="table table-striped table-advance table-bordered table-hover">
    <thead>
        <tr>
            <th rowspan="2">NO.</th>
            <th rowspan="2">KODE</th>
            <th rowspan="2">NAMA BARANG</th>
            <th rowspan="2">PABRIK</th>
            <th rowspan="2">KEMASAN</th>
            <th rowspan="2">ISI</th>
            <th colspan="2">JUMLAH</th>
            <th colspan="3">HARGA</th>
            <th colspan="2">DISKON</th>
            <th rowspan="2">TOTAL</th>
            <th colspan="8">REALISASI</th>
        </tr>
        <tr>
            <th>KEMASAN</th>
            <th>SATUAN</th>
            <th>KEMASAN</th>
            <th>SATUAN</th>
            <th>TOTAL</th>
            <th>%</th>
            <th>RP</th>
            <th>RENCANA</th>
            <th>HPS</th>
            <th>PL</th>
            <th>RO</th>
            <th>DO</th>
            <th>BONUS</th>
            <th>TERIMA</th>
            <th>RETUR</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarData as $i => $r): ?>
        <?php
        $jumlahIsi = $r->jumlahKemasan * $r->isiKemasan;
        $jumlahBonus = $jumlahIsi / $r->jumlahItemBeli * $r->jumlahItemBonus;
        $hargaTotalSebelumDiskon = $r->jumlahKemasan * $r->hargaKemasan;
        $hargaTotalSesudahDiskon = $hargaTotalSebelumDiskon - $r->diskonHarga;
        ?>
        <tr class="tr-data">
            <td><?= ($r->noUrut != 1) ? '<i class="fa fa-exchange"></i>' : ($i + 1) ?></td>
            <td><?= $r->idKatalog ?></td>
            <td><?= $r->namaSediaan ?></td>
            <td><?= $r->namaPabrik ?></td>
            <td><?= $r->kemasan ?></td>
            <td class="text-right"><?= $preferInt($r->isiKemasan) ?></td>
            <td class="text-right"><?= $toUserInt($r->jumlahKemasan) ?></td>
            <td class="text-right"><?= $preferInt($jumlahIsi) ?></td>
            <td class="text-right"><?= $toUserFloat($r->hargaKemasan) ?></td>
            <td class="text-right"><?= $toUserFloat($r->hargaItem) ?></td>
            <td class="text-right"><?= $toUserFloat($hargaTotalSebelumDiskon) ?></td>
            <td class="text-right"><?= $toUserInt($r->diskonItem) ?></td>
            <td class="text-right"><?= $toUserInt($r->diskonHarga) ?></td>
            <td class="text-right"><?= $toUserFloat($hargaTotalSesudahDiskon) ?></td>
            <td class="text-right">
                <a href="<?= $r->jumlahRencana ? $viewPerencanaanWidgetId .'/'.$r->kodeRefRencana : '#' ?>">
                    <?= $toUserFloat($r->jumlahRencana) ?>
                </a>
            </td>
            <td class="text-right">
                <a href="<?= $r->jumlahHps ? $viewPengadaanWidgetId .'/'.$r->kodeRefHps : '#' ?>">
                    <?= $toUserFloat($r->jumlahHps) ?>
                </a>
            </td>
            <td class="text-right"><?= $toUserFloat($r->jumlahPl) ?></td>
            <td class="text-right">0,00</td>
            <td class="text-right">0,00</td>
            <td class="text-right"><?= $toUserInt($jumlahBonus) ?></td>
            <td class="text-right">
                <a href="<?= $r->jumlahTerima ? $penerimaanTableWidgetId .'?filter=P_'.$pembelian->kode : '#' ?>">
                    <?= $toUserFloat($r->jumlahTerima) ?>
                </a>
            </td>
            <td class="text-right"><?= $toUserFloat($r->jumlahRetur) ?></td>
        </tr>

        <?php if ($jumlahBonus): ?>
            <tr class="tr-data">
                <td></td>
                <td>BONUS</td>
                <td colspan="4"><?= $r->namaSediaan ?> (BELI <?= $r->jumlahItemBeli ?>, GRATIS <?= $r->jumlahItemBonus ?>)</td>
                <td class="text-right"><?= $toUserInt($jumlahBonus / $r->isiKemasan) ?></td>
                <td class="text-right"><?= $toUserInt($jumlahBonus) ?></td>
                <td class="text-right">0</td>
                <td class="text-right">0</td>
                <td class="text-right">0</td>
                <td class="text-right">0</td>
                <td class="text-right">0</td>
                <td class="text-right">0</td>
                <td colspan="3"></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
    </tbody>
</table>

<table class="table well">
    <tr class="tr-last tr-static">
        <td colspan="12">Total Sebelum Diskon</td>
        <td colspan="8">:</td>
        <td class="text-right" colspan="2">Rp. <?= $toUserFloat($totalSebelumDiskon) ?></td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total Diskon</td>
        <td colspan="8">:</td>
        <td class="text-right" colspan="2">Rp. <?= $toUserFloat($totalDiskon) ?></td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total Setelah Diskon</td>
        <td colspan="8">:</td>
        <td class="text-right" colspan="2">Rp. <?= $toUserFloat($totalSetelahDiskon) ?></td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total PPN 10%</td>
        <td colspan="8">:</td>
        <td class="text-right" colspan="2">Rp. <?= $toUserFloat($totalPpn) ?></td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total Setelah PPN</td>
        <td colspan="8">:</td>
        <td class="text-right" colspan="2">Rp. <?= $toUserFloat($totalSetelahPpn) ?></td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Pembulatan</td>
        <td colspan="8">:</td>
        <td class="text-right" colspan="2">Rp. <?= $toUserFloat($pembulatan) ?></td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total Setelah Pembulatan</td>
        <td colspan="8">:</td>
        <td class="text-right" colspan="2">Rp. <?= $toUserFloat($totalSetelahPembulatan) ?></td>
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
