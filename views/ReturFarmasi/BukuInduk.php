<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ReturFarmasi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/buku_induk.php the original file
 */
final class BukuInduk
{
    private string $output;

    public function __construct(
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarHalaman,
        iterable $daftarDetailRetur,
        float    $grandTotalJumlahPerKode,
        float    $grandTotalPpn,
        float    $grandTotalNilai
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";
        $no = 1;
        $jumlahHalaman = count($daftarHalaman);
        ?>


<style>
#<?= $pageId ?> .judul tr td {
    text-align: center;
    font-size: 12px;
    font-weight: bolder;
}

#<?= $pageId ?> .judul tr td:first-child {
    font-size: 14px;
}

#<?= $pageId ?> .daftar_obat {
    font-size: 11px;
}

#<?= $pageId ?> .daftar_obat tbody tr td {
    vertical-align: top;
}

#<?= $pageId ?> .daftar_obat thead tr:first-child td {
    border-top: 1px solid black;
    padding-top: 10px;
}

#<?= $pageId ?> .daftar_obat thead tr:first-child td:nth-child(2),
#<?= $pageId ?> .daftar_obat thead tr:first-child td:nth-child(3),
#<?= $pageId ?> .daftar_obat thead tr:first-child td:nth-child(4),
#<?= $pageId ?> .daftar_obat thead tr:first-child td:nth-child(5),
#<?= $pageId ?> .daftar_obat thead tr:first-child td:nth-child(6),
#<?= $pageId ?> .daftar_obat thead tr:first-child td:nth-child(7) {
    border-bottom: 1px solid black;
}

#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(5),
#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(6),
#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(7),
#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(8),
#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(9),
#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(10),
#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(11) {
    padding: 0 1px !important;
}

#<?= $pageId ?> .daftar_obat tbody tr.kode_terima td {
    background-color: #ddd;
}

#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(3) {
    width: 25% !important;
}

#<?= $pageId ?> .daftar_obat tbody tr:not (.kode_terima ) td:nth-child(4) {
    width: 10% !important;
}

#<?= $pageId ?> .daftar_obat thead tr:last-child td {
    border-bottom: 1px solid black;
}

#<?= $pageId ?> .daftar_obat tbody tr:last-child td {
    border-bottom: 1px solid black;
}

#<?= $pageId ?> .daftar_obat tbody tr td:nth-child(5) {
    padding: 0 5px 0 0;
}

#<?= $pageId ?> .kode_terima {
    border-top: 1px solid black;
    text-align: right;
}

#<?= $pageId ?> .kode_terima td:first-child {
    text-align: left;
}

#<?= $pageId ?> .kode_terima td {
    font-weight: bolder;
}

#<?= $pageId ?> .kode_terima td:nth-child(2),
#<?= $pageId ?> .kode_terima td:nth-child(3) {
    padding: 0 0 0 5px;
}

#<?= $pageId ?> .kode_terima td:nth-child(4) {
    padding: 0 7px 0 5px;
}

#<?= $pageId ?> .daftar_obat tr td {
    font-size: 11px;
}

#<?= $pageId ?> .daftar_obat thead tr td {
    font-weight: bolder;
}

#<?= $pageId ?> .daftar_obat thead tr td {
    font-weight: bolder;
}

#<?= $pageId ?> .print_area {
    page-break-before: always;
}

#<?= $pageId ?> .print_footer table tbody tr td {
    font-weight: bold;
    font-size: 12px;
    text-align: right;
}

#<?= $pageId ?> .print_footer table tbody tr td:nth-child(2) {
    width: 5%;
    text-align: right;
}

#<?= $pageId ?> .print_footer table tbody tr td:last-child {
    width: 10%;
}
</style>

<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page print_area">
    <div>BUKU INDUK RETUR BARANG</div>
    <div>Gudang Induk Farmasi</div>
    <div>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>

    <table class="daftar_obat">
        <thead>
            <tr>
                <td colspan="6">RETUR PENERIMAAN &nbsp;&nbsp;&nbsp;_________________________________</td>
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
                <?php $no = 1 ?>
                <tr class="kode_terima">
                    <td colspan="8">
                        Tanggal BTB : <?= $baris->verTanggalTerima ?>
                        - Tanggal Stok : <?= $baris->verTanggalGudang ?>
                        - No. : <?= $baris->noDokumen ?>
                        - Ref.: <?= $baris->noTerima . " - " . $baris->namaPemasok ?>
                    </td>
                    <td class="text-right"><?= $toUserFloat($baris->totalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->totalPpn) ?></td>
                    <td>0,00</td>
                    <td class="text-right"><?= $toUserFloat($baris->totalNilai) ?></td>
                </tr>

            <?php else: ?>
                <?php $dRetur = $daftarDetailRetur[$baris->i] ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $dRetur->idKatalog ?></td>
                    <td><?= $dRetur->namaSediaan ?></td>
                    <td><?= $dRetur->namaPabrik ?></td>
                    <td class="text-right"><?= $toUserFloat($dRetur->jumlahItem) ?></td>
                    <td><?= $dRetur->satuan ?></td>
                    <td class="text-right"><?= $toUserFloat($dRetur->hargaItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($dRetur->diskonItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalPpn) ?></td>
                    <td class="text-right"></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalNilai) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>
    </table>

    <?php if ($n + 1 == $jumlahHalaman): ?>
    <table class="grand_total">
        <tbody>
        <tr>
            <td>Total Jumlah</td>
            <td>Rp</td>
            <td class="text-right"><?= $toUserFloat($grandTotalJumlahPerKode) ?></td>
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
        </tbody>
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
