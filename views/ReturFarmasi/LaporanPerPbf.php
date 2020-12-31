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
 * @used-by \tlm\his\FatmaPharmacy\controllers\ReturFarmasiController
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/laporanperpbf.php the original file
 */
final class LaporanPerPbf
{
    private string $output;

    public function __construct(
        string   $laporanReturFarmasiPerPemasok, // Yii::$app->actionToUrl([ReturFarmasiController::class, "actionLaporanPerPbf"])
        iterable $data,
        string   $tanggalAwal,
        string   $tanggalAkhir
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";

        $daftarHalaman = [];
        $grandTotalNilaiAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 55;
        $kodeJenisSaatIni = "";

        foreach ($data as $i => $r) {
            if ($kodeJenisSaatIni != $r->kodeJenis) {
                $kodeJenisSaatIni = $r->kodeJenis;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_jenis" => $r->jenisObat,
                    "total_nilai_akhir" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_nilai_akhir"] += $r->nilaiAkhir;
            $grandTotalNilaiAkhir += $r->nilaiAkhir;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $jumlahHalaman = count($daftarHalaman);
        ?>


<style>
#<?= $pageId ?> .r_table td {
    padding-left: 7px;
}

#<?= $pageId ?> .r_table .t_head {
    padding: 4px 4px !important;
    border-top: 1px solid rgb(74, 74, 74) !important;
    border-bottom: 1px solid rgb(74, 74, 74) !important;
}

#<?= $pageId ?> .r_table tfoot tr td {
    border-top: 1px solid rgb(74, 74, 74) !important;
}

#<?= $pageId ?> .monitor_stok_header table tr td {
    padding: 0 !important;
}

@media print {
    #<?= $pageId ?> .page:first-child {
        page-break-before: avoid !important;
    }
}
</style>

<a href="<?= $laporanReturFarmasiPerPemasok ?>">Kembali</a>
<button id="print">Print</button>

<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page">
    <div>REKAPITULASI PENERIMAAN BARANG FARMASI</div>
    <div>TIM PENERIMA HASIL PEKERJAAN BARANG MEDIK</div>
    <div>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>

    <table class="r_table">
        <thead class="t_head">
            <tr>
                <th>No.</th>
                <th>Pemasok</th>
                <th>No. SPK</th>
                <th>No. BA</th>
                <th>No. Faktur</th>
                <th>No. Surat Jalan</th>
                <th>Rincian (Rp.)</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["no" => $no, "nama_jenis" => $namaJenis, "total_nilai_akhir" => $total]): ?>
            <?php if ($namaJenis): ?>
                <tr class="t_head">
                    <td colspan="6"><strong><?= $no . $namaJenis ?></strong></td>
                    <td class="text-right"><strong><?= $toUserFloat($total) ?></strong></td>
                </tr>

            <?php else: ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $r->namaPemasok ?></td>
                    <td><?= $r->noSpk ?></td>
                    <td><?= $r->noBa ?></td>
                    <td><?= $r->noFaktur ?></td>
                    <td><?= $r->noSuratJalan ?></td>
                    <td class="text-right"><?= $toUserFloat($r->nilaiAkhir) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
            <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="text-right" colspan="2"><?= $toUserFloat($grandTotalNilaiAkhir) ?></td>
            </tr>
            </tfoot>
        <?php endif ?>
    </table>
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
