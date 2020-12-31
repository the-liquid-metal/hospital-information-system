<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPerencanaan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/perencanaan/views.php the original file
 */
final class ViewItem
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string      $perencanaanViewWidgetId,
        string      $pengadaanViewWidgetId,
        string      $pengadaanTableWidgetId,
        string      $pembelianViewWidgetId,
        string      $pembelianTableWidgetId,
        string      $penerimaanTableWidgetId,
        GenericData $perencanaan,
        string      $anggaran,
        string      $tipeDokumen,
        iterable    $daftarDetailPerencanaan,
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


<table class="table well">
    <tr>
        <td>Kode Transaksi</td>
        <td>: <?= $perencanaan->kode ?></td>
        <td></td>
        <td>Jenis Anggaran</td>
        <td>: <?= $perencanaan->subjenisAnggaran ?></td>
    </tr>
    <tr>
        <td>No Dokumen</td>
        <td>: <?= $perencanaan->noDokumen ?></td>
        <td></td>
        <td>Anggaran</td>
        <td>: <?= $anggaran ?></td>
    </tr>
    <tr>
        <td>Tanggal Dokumen</td>
        <td>: <?= $toUserDate($perencanaan->tanggalDokumen) ?></td>
        <td></td>
        <td>Sumber Dana</td>
        <td>: <?= $perencanaan->sumberDana ?> (SUB: <?= $perencanaan->subsumberDana ?? "- " ?>)</td>
    </tr>
    <tr>
        <td>Jenis Perencanaan</td>
        <td>: <?= $tipeDokumen ?></td>
        <td></td>
        <td>Jenis Harga</td>
        <td>: <?= $perencanaan->jenisHarga ?> (Cara Bayar: <?= $perencanaan->caraBayar ?>)</td>
    </tr>
    <?php $kd = 'R' ?>
    <?php if ($perencanaan->tipeDokumen == "3"): ?>
        <?php $kd = 'O' ?>
        <tr>
            <td>No. PL</td>
            <td><?= $perencanaan->noSpk ?></td>
            <td></td>
            <td>Nama Supplier</td>
            <td><?= $perencanaan->namaPemasok ?></td>
        </tr>
    <?php endif ?>
</table>

<table class="table table-striped table-advance table-bordered table-hover display">
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
            <th>SPK</th>
            <th><?= ($perencanaan->tipeDokumen == "3") ? "SPB" : "DO" ?></th>
            <th>TERIMA</th>
            <th>RETUR</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarDetailPerencanaan as $i => $dPerencanaan2): ?>
        <tr class="tr-data">
            <td><?= $i + 1 ?></td>
            <td><?= $dPerencanaan2->idKatalog ?></td>
            <td><?= $dPerencanaan2->namaSediaan ?></td>
            <td><?= $dPerencanaan2->namaPabrik ?></td>
            <td><?= $dPerencanaan2->kemasan ?></td>
            <td class="text-right is"><?= $preferInt($dPerencanaan2->isiKemasan) ?></td>
            <td class="text-right jk"><?= $toUserInt($dPerencanaan2->jumlahKemasan) ?></td>
            <td class="text-right ji"><?= $toUserInt($dPerencanaan2->jumlahKemasan * $dPerencanaan2->isiKemasan) ?></td>
            <td class="text-right hk"><?= $toUserFloat($dPerencanaan2->hargaKemasan) ?></td>
            <td class="text-right hi"><?= $toUserFloat($dPerencanaan2->hargaItem) ?></td>
            <td class="text-right ht"><?= $toUserInt($dPerencanaan2->jumlahKemasan * $dPerencanaan2->hargaKemasan) ?></td>
            <td class="text-right di"><?= $toUserInt($dPerencanaan2->diskonItem) ?></td>
            <td class="text-right dh"><?= $toUserInt($dPerencanaan2->diskonHarga) ?></td>
            <td class="text-right ha"><?= $toUserInt(($dPerencanaan2->jumlahKemasan * $dPerencanaan2->hargaKemasan) - $dPerencanaan2->diskonHarga) ?></td>
            <td class="text-right pl">
                <a href="<?= ($perencanaan->tipeDokumen == 3 and $dPerencanaan2->jumlahRencana) ? $perencanaanViewWidgetId .'/'.$dPerencanaan2->kodeRefRencana : '#' ?>">
                    <?= $toUserFloat($dPerencanaan2->jumlahRencana) ?>
                </a>
            </td>
            <td class="text-right pl">
                <?php if ($perencanaan->tipeDokumen == 3 and $dPerencanaan2->jumlahHps): ?>
                    <a href="<?= $pengadaanViewWidgetId .'/'.$dPerencanaan2->kodeRefHps ?>"><?= $toUserFloat($dPerencanaan2->jumlahHps) ?></a>

                <?php elseif ($dPerencanaan2->jumlahHps): ?>
                    <a href="<?= $pengadaanTableWidgetId .'?kode_reffrenc='.$dPerencanaan2->kodeRef ?>"><?= $toUserFloat($dPerencanaan2->jumlahHps) ?></a>

                <?php else: ?>
                    <?= $toUserFloat($dPerencanaan2->jumlahHps) ?>
                <?php endif ?>
            </td>
            <td class="text-right jP">
                <?php if ($perencanaan->tipeDokumen == 3 and $dPerencanaan2->jumlahPl): ?>
                    <a href="<?= $pembelianViewWidgetId .'/'.$dPerencanaan2->kodeRefPl ?>"><?= $toUserFloat($dPerencanaan2->jumlahPl) ?></a>

                <?php elseif ($dPerencanaan2->jumlahPl): ?>
                    <a href="<?= $pembelianTableWidgetId .'?kode_reffrenc='.$dPerencanaan2->kodeRef ?>"><?= $toUserFloat($dPerencanaan2->jumlahPl) ?></a>

                <?php else: ?>
                    <?= $toUserFloat($dPerencanaan2->jumlahPl) ?>
                <?php endif ?>
            </td>
            <td class="text-right po"><?= $toUserFloat($dPerencanaan2->jumlahPo) ?></td>
            <td class="text-right trm">
                <a href="<?= $dPerencanaan2->jumlahTerima ? $penerimaanTableWidgetId .'?filter='.$kd.'_'.$perencanaan->kode  :  '#' ?>">
                    <?= $toUserFloat($dPerencanaan2->jumlahTerima) ?>
                </a>
            </td>
            <td class="text-right ret">0</td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<table class="table">
    <tr class="tr-last tr-static">
        <td>Total Sebelum Diskon</td>
        <td class="text-right">Rp. <?= $toUserFloat($totalSebelumDiskon) ?></td>
    </tr>
    <tr class="tr-static">
        <td>Total Diskon</td>
        <td class="text-right">Rp. <?= $toUserFloat($totalDiskon) ?></td>
    </tr>
    <tr class="tr-static">
        <td>Total Setelah Diskon</td>
        <td class="text-right">Rp. <?= $toUserFloat($totalSetelahDiskon) ?></td>
    </tr>
    <tr class="tr-static">
        <td>Total PPN 10%</td>
        <td class="text-right">Rp. <?= $toUserFloat($totalPpn) ?></td>
    </tr>
    <tr class="tr-static">
        <td>Total Setelah PPN</td>
        <td class="text-right">Rp. <?= $toUserFloat($totalSetelahPpn) ?></td>
    </tr>
    <tr class="tr-static">
        <td>Pembulatan</td>
        <td class="text-right">Rp. <?= $toUserFloat($pembulatan) ?></td>
    </tr>
    <tr class="tr-static">
        <td>Total Setelah Pembulatan</td>
        <td class="text-right">Rp. <?= $toUserFloat($totalSetelahPembulatan) ?></td>
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
