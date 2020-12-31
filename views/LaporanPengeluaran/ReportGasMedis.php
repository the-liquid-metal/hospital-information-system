<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPengeluaran;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporan_rekap2gm.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanRekap2GasMedis: commit-e37d34f4
 */
final class ReportGasMedis
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
        string   $namaDepo,
        iterable $daftarPeringatan,
        int      $jumlahHalaman,
        float    $grandTotalJumlah,
        float    $grandTotalNilai
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page">
    <div class="header">
        LAPORAN REKAPITULASI PENGELUARAN BARANG<br/>
        GAS MEDIS<br/>
        Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?><br/>
        Tujuan: <?= $namaDepo ?>
    </div>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No. Seri Tabung</th>
                <th>Kuantitas</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->depoTujuan)): ?>
                <tr class="grup_obat">
                    <td colspan="2" style="font-weight:bold"><?= $baris->no ?> Tujuan: <?= $baris->depoTujuan ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->totalJumlah) ?></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?= $toUserFloat($baris->totalNilai) ?></td>
                </tr>

            <?php elseif (isset($baris->namaBarang)): ?>
                <tr class="grup_obat grup_obat2">
                    <td colspan="2" style="font-weight:bold"><?= $baris->no . $baris->namaBarang . " - " . $baris->namaPabrik ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalJumlah) ?></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalNilai) ?></td>
                </tr>

            <?php else: ?>
                <?php $peringatan = $daftarPeringatan[$baris->i] ?>
                <tr>
                    <td><?= $toUserDate($peringatan->tanggalVerifikasi) ?></td>
                    <td><?= $peringatan->noBatch ?></td>
                    <td class="text-right"><?= $toUserFloat($peringatan->jumlah2) ?></td>
                    <td><?= $peringatan->namaKemasan ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->hpItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->nilai) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <?php if ($n + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td class="text-right" colspan="2">TOTAL</td>
                <td class="text-right"><?= $toUserFloat($grandTotalJumlah) ?></td>
                <td colspan="2"></td>
                <td class="text-right"><?= $toUserFloat($grandTotalNilai) ?></td>
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
