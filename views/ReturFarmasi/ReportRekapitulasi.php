<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ReturFarmasi;

use tlm\libs\LowEnd\components\GenericData;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @used-by \tlm\his\FatmaPharmacy\controllers\ReturFarmasiController
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/report_rekapitulasi.php the original file
 */
final class ReportRekapitulasi
{
    private string $output;

    public function __construct(GenericData $data, string $tanggalAwal, string $tanggalAkhir)
    {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";

        $daftarHalaman = [];
        $totalJumlah = 0;
        $totalPpn = 0;
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $posisi = 0;
        $noJudul = 1;
        $noData = 1;
        $kodeKelompokSaatIni = "";
        $barisPerHalaman = 50;
        $butuhBaris = 1;

        foreach ($data as $row) {
            $kodeKelompok = $row->kodeKelompok;

            if ($kodeKelompokSaatIni != $kodeKelompok) {
                $kodeKelompokSaatIni = $kodeKelompok;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .". ",
                    "nama_kelompok" => $row->kelompokBarang,
                    "subtotal_jumlah" => 0,
                    "subtotal_ppn" => 0,
                    "subtotal_nilai" => 0,
                ];

                if ($posisi > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                    $posisi = 0;
                } elseif (($posisi + $butuhBaris) > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                    $posisi = 0;
                } else {
                    $b++;
                    $posisi++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i, // TODO: php: uncategorized: trace this missing var
                "no" => $noJudul .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul][$bJudul]["subtotal_jumlah"] += $row->totalHarga;
            $daftarHalaman[$hJudul][$bJudul]["subtotal_ppn"] += $row->totalPpn;
            $daftarHalaman[$hJudul][$bJudul]["subtotal_nilai"] += $row->totalHarga + $row->totalPpn;

            $totalJumlah += $row->totalHarga;
            $totalPpn += $row->totalPpn;
            $totalNilai += $row->totalHarga + $row->totalPpn;

            if ($posisi > $barisPerHalaman) {
                $h++;
                $b = 0;
                $posisi = 0;
            } elseif (($posisi + $butuhBaris) > $barisPerHalaman) {
                $h++;
                $b = 0;
                $posisi = 0;
            } else {
                $b++;
                $posisi++;
            }
        }

        $jumlahHalaman = count($daftarHalaman);
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

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(8) {
    width: 8%;
}

#<?= $pageId ?> table.main tbody tr.katalog td:nth-child(9) {
    width: 8%;
}

#<?= $pageId ?> table.main tbody tr.row_kelompok td.nama_kelompok {
    border-right: none !important;
}
</style>

<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="thead_desc">
    REKAPITULASI PENERIMAAN BARANG<br/>
    INSTALASI FARMASI<br/>
    Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
</div>

<table class="main">
    <thead>
        <tr class="thead_header">
            <th>No.</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Pabrik</th>
            <th>Kuantitas</th>
            <th>Satuan</th>
            <th>Jumlah (Rp.)</th>
            <th>PPN (Rp.)</th>
            <th>Nilai (RP.)</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($halaman as $baris): ?>
        <?php if (isset($baris->namaKelompok)): ?>
            <tr class="row_kelompok">
                <td colspan="6" class="nama_kelompok"><?= $baris->no . $baris->namaKelompok ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalJumlah) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalPpn) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalNilai) ?></td>
            </tr>

        <?php else: ?>
            <tr class="katalog">
                <td><?= $baris->no ?></td>
                <td><?= $baris->kode ?></td>
                <td><?= $baris->namaSediaan ?></td>
                <td><?= $baris->namaPabrik ?></td>
                <td class="text-right"><?= $toUserFloat($baris->totalItem) ?></td>
                <td><?= $baris->satuan ?></td>
                <td class="text-right"><?= $toUserFloat($baris->totalHarga) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->totalPpn) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->totalHarga + $baris->totalPpn) ?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>

    <?php if ($h + 1 == $jumlahHalaman): ?>
        <tr class="total">
            <td colspan="5" class="nama_kelompok">Total</td>
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
