<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenerimaan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/penerimaan/report_bukuinduk.php the original file
 */
final class ReportPenerimaanBukuInduk
{
    private string $output;

    public function __construct(
        iterable $daftarHalaman,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarDetailPenerimaan,
        int      $jumlahHalaman,
        float    $grandTotalJumlah,
        float    $grandTotalPpn,
        float    $grandTotalNilai
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page buku-induk">
    <div class="header">
        BUKU INDUK PENERIMAAN BARANG<br/>
        Gudang Induk Farmasi<br/>
        Tanggal <?= "$tanggalAwal s.d. $tanggalAkhir" ?>
    </div>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <td colspan="6">PENERIMAAN PEMBELIAN &nbsp;&nbsp;&nbsp;_________________________________</td>
                <td rowspan="2">@ (Rp.)</td>
                <td rowspan="2">Diskon (%)</td>
                <td rowspan="2">Jumlah (Rp.)</td>
                <td rowspan="2">PPN (Rp.)</td>
                <td rowspan="2">Pembulatan (Rp.)</td>
                <td rowspan="2">Nilai (Rp.)</td>
            </tr>
            <tr>
                <td>No.</td>
                <td>Kode</td>
                <td>Nama Barang</td>
                <td class="pabrik">Pabrik</td>
                <td>Kuantitas</td>
                <td>Satuan</td>
                <td colspan="6">&nbsp;</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->kodeTerima)): ?>
                <tr class="kode_terima">
                    <td colspan="8">
                        <?= $baris->no ?>
                        - Tanggal BTB: <?= $baris->verTanggalTerima ?>
                        - Tanggal Stok: <?= $baris->verTanggalGudang ?>
                        - No.: <?= $baris->noDokumen . " - " . $baris->namaPemasok ?>
                    </td>
                    <td class="text-right"><?= $toUserFloat($baris->totalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->totalPpn) ?></td>
                    <td class="text-right">0,00</td>
                    <td class="text-right"><?= $toUserFloat($baris->totalNilai) ?></td>
                </tr>

            <?php else: ?>
                <?php $dPenerimaan = $daftarDetailPenerimaan[$baris->i] ?>
                <tr>
                    <td><?= $baris->no ?></td>
                    <td><?= $dPenerimaan->idKatalog ?></td>
                    <td><?= $dPenerimaan->namaSediaan ?></td>
                    <td><?= $dPenerimaan->namaPabrik ?></td>
                    <td class="text-right"><?= $toUserFloat($dPenerimaan->jumlahItem) ?></td>
                    <td><?= $dPenerimaan->satuan ?></td>
                    <td class="text-right"><?= $toUserFloat($dPenerimaan->hargaItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($dPenerimaan->diskonItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalPpn) ?></td>
                    <td></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalNilai) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>
    </table>

    <?php if ($n + 1 == $jumlahHalaman): ?>
    <table class="grand_total">
        <tr>
            <td>Total Jumlah</td>
            <td>Rp</td>
            <td class="text-right"><?= $toUserFloat($grandTotalJumlah) ?></td>
        </tr>
        <tr>
            <td>Total PPN</td>
            <td>Rp</td>
            <td class="text-right"><?= $toUserFloat($grandTotalPpn) ?></td>
        </tr>
        <tr>
            <td>Total Nilai</td>
            <td>Rp</td>
            <td class="text-right"><?= $toUserFloat($grandTotalNilai) ?></td>
        </tr>
    </table>
    <?php endif ?>
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
