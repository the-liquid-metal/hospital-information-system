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
 * @used-by \tlm\his\FatmaPharmacy\controllers\PenerimaanController
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/laporanperpbf.php the original file
 */
final class LaporanPerPbf
{
    private string $output;

    public function __construct(
        iterable $data,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        string   $laporanPenerimaanPerPemasokWidgetId // Yii::$app->actionToUrl([PenerimaanController::class, "actionLaporanPerPbf"])
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";

        $daftarHalaman = [];
        $total = 0;

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
                    "jumlah_jenis" => 0,
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

            $daftarHalaman[$hJudul][$bJudul]["jumlah_jenis"] += $r->nilaiAkhir;
            $total += $r->nilaiAkhir;

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

<a href="<?= $laporanPenerimaanPerPemasokWidgetId ?>">Kembali</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
        <?php foreach ($halaman as ["i" => $i, "no" => $no, "nama_jenis" => $nama, "jumlah_jenis" => $jumlah]): ?>
            <?php if ($nama): ?>
                <tr class="t_head">
                    <td colspan="6"><strong><?= $no . $nama ?></strong></td>
                    <td class="text-right"><strong><?= $toUserFloat($jumlah) ?></strong></td>
                </tr>

            <?php else: ?>
                <?php $row = $data[$i] ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $row->namaPemasok ?></td>
                    <td><?= $row->noSpk ?></td>
                    <td><?= $row->noBa ?></td>
                    <td><?= $row->noFaktur ?></td>
                    <td><?= $row->noSuratJalan ?></td>
                    <td class="text-right"><?= $toUserFloat($row->nilaiAkhir) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="text-right" colspan="2"><?= $toUserFloat($total) ?></td>
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
