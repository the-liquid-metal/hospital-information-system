<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Stokopname;

use tlm\libs\LowEnd\components\DateTimeException;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/opnameuser.php the original file
 */
final class OpnameUser
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string   $tanggal,
        string   $namaDepo,
        string   $namaUser,
        iterable $daftarBaris,
        iterable $daftarOpnameUser,
        int      $totalNilaiAdm,
        float    $totalNilaiFisik
    ) {
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $toUserFloat = Yii::$app->number->toUserFloat();
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> table.main {
    page-break-after: auto;
}

#<?= $pageId ?> .main tr {
    page-break-inside: avoid;
    page-break-after: auto;
}

#<?= $pageId ?> .main td {
    page-break-inside: avoid;
    page-break-after: auto;
}

#<?= $pageId ?> .main thead {
    display: table-header-group;
}

#<?= $pageId ?> .main tfoot {
    display: table-footer-group;
}

#<?= $pageId ?> table.main {
    border-bottom: 1px solid black;
    font-size: 11px;
    table-layout: fixed;
}

#<?= $pageId ?> table.main thead tr.thead_desc th p {
    text-align: left;
    margin-bottom: 0;
    margin-left: 10px;
}

#<?= $pageId ?> table.main thead tr.thead_desc th p span {
    width: 100px;
    display: inline-block;
}

#<?= $pageId ?> table.main thead tr.thead_desc th h4 {
    text-align: center;
    margin-bottom: 10px;
}

#<?= $pageId ?> table.main thead tr.thead_header th {
    background-color: #CCC;
    border: 1px solid black;
}

#<?= $pageId ?> table.main tbody tr {
    vertical-align: top;
}

#<?= $pageId ?> table.main tbody tr.row_kelompok td {
    font-weight: bold;
}

#<?= $pageId ?> table.main tbody tr td {
    padding-left: 4px;
    padding-right: 4px;
    border: 1px solid black;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(1),
#<?= $pageId ?> table.main tbody tr td:nth-child(2),
#<?= $pageId ?> table.main tbody tr td:nth-child(3),
#<?= $pageId ?> table.main tbody tr td:nth-child(4),
#<?= $pageId ?> table.main tbody tr td:nth-child(5),
#<?= $pageId ?> table.main tbody tr td:nth-child(6) {
    text-align: left;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(7),
#<?= $pageId ?> table.main tbody tr td:nth-child(8),
#<?= $pageId ?> table.main tbody tr td:nth-child(9),
#<?= $pageId ?> table.main tbody tr td:nth-child(10),
#<?= $pageId ?> table.main tbody tr td:nth-child(11),
#<?= $pageId ?> table.main tbody tr td:nth-child(12) {
    text-align: right;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(1) {
    width: 1%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(2) {
    width: 5%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(3) {
    width: 22%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(4) {
    width: 15%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(5) {
    width: 5%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(6) {
    width: 7%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(7) {
    width: 6%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(8) {
    width: 6%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(9) {
    width: 8%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(10) {
    width: 8%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(11) {
    width: 4%;
}

#<?= $pageId ?> table.main tbody tr td:nth-child(12) {
    width: 9%;
}

#<?= $pageId ?> table.main tbody tr.row_kelompok td.nama_kelompok {
    border-right: none !important;
}

#<?= $pageId ?> table.main tbody tr.row_kelompok td.subtotal {
    border-left: none !important;
    border-right: none !important;
}

#<?= $pageId ?> table.main tbody tr.row_kelompok td.nilai_subtotal {
    border-left: none !important;
    text-align: right;
}
</style>

<div class="thead_desc">
    LAPORAN HASIL STOK OPNAME USER<br/>
    Tanggal: <?= $toUserDatetime($tanggal) ?><br/>
    Gudang: <?= $namaDepo ?><br/>
    User: <?= $namaUser ?><br/>
    Tanggal Cetak: <?= $nowValUser ?>
</div>

<table class="main">
    <thead>
        <tr class="thead_header">
            <th rowspan="2">NO.</th>
            <th rowspan="2">KODE</th>
            <th rowspan="2">BARANG</th>
            <th rowspan="2">PABRIK</th>
            <th rowspan="2">SATUAN</th>
            <th rowspan="2">KADALUARSA</th>
            <th rowspan="2">STOK ADM</th>
            <th rowspan="2">STOK FISIK</th>
            <th rowspan="2">HARGA POKOK</th>
            <th rowspan="2">NILAI FISIK</th>
            <th colspan="2">SELISIH</th>
        </tr>
        <tr class="thead_header">
            <th>KUANTITAS</th>
            <th>NILAI</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarBaris as $baris): ?>
        <?php if (isset($baris->namaKelompok)): ?>
            <tr class="row_kelompok">
                <td colspan="7" class="nama_kelompok"><?= $baris->no . $baris->namaKelompok ?></td>
                <td colspan="3" class="subtotal">
                    Subtotal sebelum stok opname<br/>
                    Subtotal setelah stok opname<br/>
                    Subtotal selisih
                </td>
                <td colspan="2" class="nilai_subtotal">
                    <?= $toUserFloat($baris->nilaiAdm) ?><br/>
                    <?= $toUserFloat($baris->nilaiFisik) ?><br/>
                    <?= $toUserFloat($baris->nilaiFisik - $baris->nilaiAdm) ?>
                </td>
            </tr>

        <?php else: ?>
            <?php $opnameUser = $daftarOpnameUser[$baris->i] ?>
            <tr class="katalog">
                <td><?= $baris->no ?></td>
                <td><?= $opnameUser->kode ?></td>
                <td><?= $opnameUser->namaBarang ?></td>
                <td><?= $opnameUser->namaPabrik ?></td>
                <td><?= $opnameUser->satuan ?></td>
                <td><?= $opnameUser->tanggalKadaluarsa ?></td>
                <td class="text-right"><?= $toUserFloat($opnameUser->stokAdm) ?></td>
                <td class="text-right"><?= $toUserFloat($opnameUser->stokFisik) ?></td>
                <td class="text-right"><?= $toUserFloat($opnameUser->hpItem) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->nilaiFisik) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->jumlahSelisih) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->nilaiSelisih) ?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>

        <tr class="row_kelompok">
            <td colspan="7" class="nama_kelompok">TOTAL</td>
            <td colspan="3" class="subtotal">
                TOTAL SEBELUM STOK OPNAME<br/>
                TOTAL SETELAH STOK OPNAME<br/>
                TOTAL SELISIH
            </td>
            <td colspan="2" class="nilai_subtotal">
                <?= $toUserFloat($totalNilaiAdm) ?><br/>
                <?= $toUserFloat($totalNilaiFisik) ?><br/>
                <?= $toUserFloat($totalNilaiAdm - $totalNilaiFisik) ?>
            </td>
        </tr>
    </tbody>
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
