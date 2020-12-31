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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/report_bukuinduk.php the original file
 */
final class ReportBukuInduk
{
    private string $output;

    public function __construct(
        GenericData $data,
        string      $tanggalAwal,
        string      $tanggalAkhir,
        string      $returFarmasiReportWidgetId // Yii::$app->actionToUrl([ReturFarmasiController::class, "actionReports"])
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";

        $daftarHalaman = [];
        $grandTotalNilai = 0;
        $grandTotalPpn = 0;
        $grandTotalJumlah = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $posisi = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 44;
        $maksHurufBarang = 36;
        $maksHurufPabrik = 16;
        $kodeTerimaSaatIni = "";

        foreach ($data as $i => $row) {
            $kodeTerima = $row->kodeTerima;

            $jumlahBarisBarang = ceil(strlen($row->namaSediaan) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($row->namaPabrik) / $maksHurufPabrik);
            $butuhBaris = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

            if ($kodeTerimaSaatIni != $kodeTerima) {
                $kodeTerimaSaatIni = $kodeTerima;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "kode_terima" => $row->kodeTerima,
                    "no_doc" => $row->noDokumen,
                    "no_trm" => $row->noPenerimaan,
                    "ver_tglterima" => $row->verTanggalTerima,
                    "ver_tglgudang" => $row->verTanggalGudang,
                    "nama_pbf" => $row->namaPemasok,
                    "total_jumlah" => 0,
                    "total_ppn" => 0,
                    "total_nilai" => 0,
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
                    $b += 2;
                    $posisi += $butuhBaris;
                }
            }

            $jum = $row->hargaItem * $row->jumlahItem;
            $jumlah = $jum - ($row->diskonItem / 100) * $jum;
            $ppn = $jumlah * ($row->ppn / 100);

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
                "subtotal_jumlah" => $jumlah,
                "subtotal_ppn" => $ppn,
                "subtotal_nilai" => $jumlah + $ppn,
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_jumlah"] += $jumlah;
            $daftarHalaman[$hJudul][$bJudul]["total_ppn"] += $ppn;
            $daftarHalaman[$hJudul][$bJudul]["total_nilai"] += $jumlah + $ppn;

            $grandTotalJumlah += $jumlah;
            $grandTotalPpn += $ppn;
            $grandTotalNilai += $jumlah + $ppn;

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
                $posisi += $butuhBaris;
            }
        }

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

#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(5),
#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(6),
#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(7),
#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(8),
#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(9),
#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(10),
#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(11) {
    padding: 0 1px !important;
}

#<?= $pageId ?> .daftar_obat tbody tr.kode_terima td {
    background-color: #ddd;
}

#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(3) {
    width: 25% !important;
}

#<?= $pageId ?> .daftar_obat tbody tr:not(.kode_terima) td:nth-child(4) {
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

<a href="<?= $returFarmasiReportWidgetId ?>">Kembali</a>

<br/>

<?php foreach ($daftarHalaman as $h => $halaman): ?>
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
        <?php foreach ($halaman as $b => $baris): ?>
            <?php if (isset($baris->kodeTerima)): ?>
                <tr class="kode_terima">
                    <td><?= $baris->no ?></td>
                    <td colspan="7">
                        Tanggal BTB : <?= $baris->verTanggalTerima ?>
                        - Tanggal Stok : <?= $baris->verTanggalGudang ?>
                        - No. : <?= $baris->noDokumen ?>
                        - Ref.: <?= $baris->noTerima ?> - <?= $baris->namaPemasok ?>
                    </td>
                    <td class="text-right"><?= $toUserFloat($baris->totalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->totalPpn) ?></td>
                    <td>0,00</td>
                    <td class="text-right"><?= $toUserFloat($baris->totalNilai) ?></td>
                </tr>

            <?php else: ?>
                <?php $baris2 = $data[$baris->i] ?>
                <tr>
                    <td><?= $baris->no ?></td>
                    <td><?= $baris->idKatalog ?></td>
                    <td><?= $baris->namaSediaan ?></td>
                    <td><?= $baris->namaPabrik ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->jumlahItem) ?></td>
                    <td><?= $baris->satuan ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->hargaItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->diskonItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris2->subtotalJumlah) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris2->subtotalPpn) ?></td>
                    <td></td>
                    <td class="text-right"><?= $toUserFloat($baris2->subtotalNilai) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>
    </table>

    <?php if ($h + 1 == $jumlahHalaman): ?>
    <table class="grand_total">
        <tbody>
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
