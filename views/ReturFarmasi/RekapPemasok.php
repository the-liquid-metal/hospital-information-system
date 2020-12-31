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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/rekap_pbf.php the original file
 */
final class RekapPemasok
{
    private string $output;

    public function __construct(
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarHalaman,
        iterable $daftarRetur,
        float    $grandTotalNilaiAkhir,
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $jumlahHalaman = count($daftarHalaman);
        $pageId = "";
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

<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page">
    <div>LAPORAN RETURN PENERIMAAN BARANG FARMASI</div>
    <div>TIM PENERIMA HASIL PEKERJAAN BARANG MEDIK</div>
    <div>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>

    <table class="r_table">
        <thead class="t_head">
            <tr>
                <th>No.</th>
                <th>Pemasok</th>
                <th>No. BA</th>
                <th>No. RETUR</th>
                <th>No. Faktur</th>
                <th>No. Surat Jalan</th>
                <th>Rincian (Rp.)</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->jenisPenerimaan)): ?>
                <tr class="t_head">
                    <td colspan="6"><strong><?= $baris->no . $baris->jenisPenerimaan ?></strong></td>
                    <td class="text-right"><strong><?= $toUserFloat($baris->totalNilaiAkhir) ?></strong></td>
                </tr>

            <?php else: ?>
                <?php $retur = $daftarRetur[$baris->i] ?>
                <tr>
                    <td><?= $baris->no ?></td>
                    <td><?= $retur->namaPemasok ?></td>
                    <td><?= $retur->noBa ?></td>
                    <td><?= $retur->noDokumen ?></td>
                    <td><?= $retur->noFaktur ?></td>
                    <td><?= $retur->noSuratJalan ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->nilaiAkhir) ?></td>
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
