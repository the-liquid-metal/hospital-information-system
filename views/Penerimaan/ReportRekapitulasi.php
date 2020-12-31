<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Penerimaan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/report_rekapitulasi.php the original file
 */
final class ReportRekapitulasi
{
    private string $output;

    public function __construct(
        iterable $daftarHalaman,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarDetailPenerimaan,
        int      $jumlahHalaman,
        float    $totalJumlah,
        float    $totalPpn,
        float    $totalNilai
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> table.main {
    border-bottom: 1px solid black;
    font-size: 11px;
    table-layout: fixed;
}

#<?= $pageId ?> table.main thead tr.thead_desc th h1,
#<?= $pageId ?> table.main thead tr.thead_desc th h2,
#<?= $pageId ?> table.main thead tr.thead_desc th h3,
#<?= $pageId ?> table.main thead tr.thead_desc th h4,
#<?= $pageId ?> table.main thead tr.thead_desc th h5,
#<?= $pageId ?> table.main thead tr.thead_desc th h6 {
    text-align: center;
}

#<?= $pageId ?> table.main thead tr.thead_header div {
    background-color: #CCC;
    border-top: 1px solid black;
    border-bottom: 1px solid black;
}

#<?= $pageId ?> table.main thead tr.thead_header th {
    background-color: #CCC;
    padding: 0;
    margin: 0;
}

#<?= $pageId ?> table.main tbody tr {
    vertical-align: top;
}

#<?= $pageId ?> table.main tbody tr.row_jenis td {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    font-weight: bold;
}

#<?= $pageId ?> table.main tbody tr.row_kelompok td {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    font-weight: bold;
}

#<?= $pageId ?> table.main tbody tr.total td {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    font-weight: bold;
}

#<?= $pageId ?> table.main tfoot tr td {
    padding: 0;
    margin: 0;
}

#<?= $pageId ?> table.main tfoot tr td div {
    border-top: 1px solid black;
}

#<?= $pageId ?> table.main tbody tr.row_jenis td:nth-child(2),
#<?= $pageId ?> table.main tbody tr.row_jenis td:nth-child(3),
#<?= $pageId ?> table.main tbody tr.row_jenis td:nth-child(4),
#<?= $pageId ?> table.main tbody tr.row_kelompok td:nth-child(2),
#<?= $pageId ?> table.main tbody tr.row_kelompok td:nth-child(3),
#<?= $pageId ?> table.main tbody tr.row_kelompok td:nth-child(4),
#<?= $pageId ?> table.main tbody tr.total td:nth-child(3) {
    text-align: right;
}

#<?= $pageId ?> table.main tbody tr td {
    padding-left: 4px;
    padding-right: 4px;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(1),
#<?= $pageId ?> table.main tbody tr td:nth-child(2),
#<?= $pageId ?> table.main tbody tr td:nth-child(3),
#<?= $pageId ?> table.main tbody tr td:nth-child(4),
#<?= $pageId ?> table.main tbody tr td:nth-child(6) {
    text-align: left;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(5),
#<?= $pageId ?> table.main tbody tr td:nth-child(7),
#<?= $pageId ?> table.main tbody tr td:nth-child(8),
#<?= $pageId ?> table.main tbody tr td:nth-child(9) {
    text-align: right;
}

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(1) {
    width: 1%;
}

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(2) {
    width: 5%;
}

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(3) {
    width: 25%;
}

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(4) {
    width: 15%;
}

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(5) {
    width: 5%;
}

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(6) {
    width: 4%;
}

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(7) {
    width: 8%;
}

#<?= $pageId ?> table.main tbody tr.row_kelompok td.nama_kelompok {
    border-right: none !important;
}
</style>

<?php foreach ($daftarHalaman as $n => $halaman): ?>
<h4>REKAPITULASI PENERIMAAN BARANG</h4>
<h4>INSTALASI FARMASI</h4>
<h5>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></h5>

<table class="main">
    <thead>
        <tr class="thead_header">
            <th>No.</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Pabrik</th>
            <th>Kuantitas</th>
            <th>Satuan</th>
            <th>Nilai (Rp.)</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($halaman as $baris): ?>
        <?php if (isset($baris->namaKelompok)): ?>
            <tr class="row_kelompok">
                <td colspan="4" class="nama_kelompok"><?= $baris->no . $baris->namaKelompok ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalJumlah) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalPpn) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalNilai) ?></td>
            </tr>

        <?php else: ?>
            <?php $dPenerimaan = $daftarDetailPenerimaan[$baris->i] ?>
            <tr class="katalog">
                <td><?= $baris->no ?></td>
                <td><?= $dPenerimaan->idKatalog ?></td>
                <td><?= $dPenerimaan->namaSediaan ?></td>
                <td><?= $dPenerimaan->namaPabrik ?></td>
                <td class="text-right"><?= $toUserFloat($dPenerimaan->totalItem) ?></td>
                <td><?= $dPenerimaan->satuan ?></td>
                <td class="text-right"><?= $toUserFloat($dPenerimaan->totalHarga + $dPenerimaan->totalPpn) ?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>

    <?php if ($n + 1 == $jumlahHalaman): ?>
        <tr class="total">
            <td colspan="3" class="nama_kelompok">Total</td>
            <td colspan="2" class="subtotal">
                Total Harga<br/>
                Total PPN<br/>
                Total Nilai<br/>
            </td>
            <td colspan="2" class="nilai_subtotal">
                <?= $toUserFloat($totalJumlah) ?><br/>
                <?= $toUserFloat($totalPpn) ?><br/>
                <?= $toUserFloat($totalNilai) ?><br/>
            </td>
        </tr>
    <?php endif ?>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
    </tfoot>
</table>
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
