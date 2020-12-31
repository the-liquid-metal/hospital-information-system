<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenerimaan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/penerimaan/laporanbukuindukgas.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Penerimaan\LaporanBukuIndukGas: commit-e37d34f4
 */
final class ReportGasBukuInduk
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        iterable $daftarHalaman,
        string   $tanggalAwal,
        string   $tanggalAkhir,
        iterable $daftarDetailPenerimaan,
        int      $jumlahHalaman,
        float    $grandTotal
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page buku-induk">
    <div class="header">
        BUKU INDUK PENERIMAAN GAS MEDIS<br/>
        Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?><br/>
        Gudang Gas Medis
    </div>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No. Seri tabung</th>
                <th>No. BA</th>
                <th>No. Faktur / Surat Jalan</th>
                <th>Jumlah Tabung</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->namaBarang)): ?>
                <tr class="t_head">
                    <td colspan="4"><?= $baris->no . $baris->namaBarang ?> - <?= $baris->namaPemasok ?></td>
                    <td class="text-right" rowspan="2"><?= $toUserFloat($baris->subtotal) ?></td>
                </tr>
                <tr class="t_head">
                    <td colspan="4">
                        Tanggal. BTB: <?= $toUserDate($baris->tanggalBtb) ?>
                        - Tanggal Verifikasi <?= $toUserDatetime($baris->tanggalVerifikasi) ?>
                        - No. BTB: <?= $baris->noBtb ?>
                        - No:<?= $baris->noSuratJalan ?>
                    </td>
                </tr>

            <?php else: ?>
                <?php $dPenerimaan = $daftarDetailPenerimaan[$baris->i] ?>
                <tr>
                    <td><?= $baris->no ?></td>
                    <td><?= $dPenerimaan->noSeriTabung ?></td>
                    <td><?= $dPenerimaan->noBtb ?></td>
                    <td><?= $dPenerimaan->noSuratJalan ?: $dPenerimaan->noFaktur ?></td>
                    <td class="text-right"><?= $toUserFloat($dPenerimaan->jumlahMasuk) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td colspan="4">Total Tabung</td>
                <td class="text-right"><?= $toUserFloat($grandTotal) ?></td>
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
