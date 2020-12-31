<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenerimaan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/penerimaan/laporanrekapgas.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Penerimaan\LaporanRekapGas: commit-e37d34f4
 */
final class ReportGasPerJenis
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
        ?>


<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page per-jenis">
    <div class="header center">
        REKAPITULASI PENERIMAAN BARANG<br/>
        INSTALASI FARMASI<br/>
        Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
    </div>

    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Pabrik</th>
                <th>Kuantitas</th>
                <th>Satuan</th>
                <th>Jumlah (Rp.)</th>
                <th>PPN (Rp.)</th>
                <th>Nilai (Rp.)</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i]): ?>
            <?php $dPenerimaan = $daftarDetailPenerimaan[$i] ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $dPenerimaan->idKatalog ?></td>
                <td><?= $dPenerimaan->namaBarang ?></td>
                <td><?= $dPenerimaan->namaPabrik ?></td>
                <td class="text-right"><?= $toUserFloat($dPenerimaan->kuantitas) ?></td>
                <td><?= $dPenerimaan->satuan ?></td>
                <td class="text-right"><?= $toUserFloat($dPenerimaan->nilaiTotal) ?></td>
                <td class="text-right"><?= $toUserFloat($dPenerimaan->nilaiDiskon) ?></td>
                <td class="text-right"><?= $toUserFloat($dPenerimaan->nilaiAkhir) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>

        <?php if ($h + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td colspan="6">Total Gas Medis</td>
                <td class="text-right"><?= $toUserFloat($totalJumlah) ?></td>
                <td class="text-right"><?= $toUserFloat($totalPpn) ?></td>
                <td class="text-right"><?= $toUserFloat($totalNilai) ?></td>
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
