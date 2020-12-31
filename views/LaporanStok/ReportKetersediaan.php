<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanStok;

use tlm\libs\LowEnd\components\{DateTimeException, GenericData};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/transaksi/print_kp_tes.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Transaksi\PrintKpTest: commit-e37d34f4
 */
final class ReportKetersediaan
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string      $tanggalAwal,
        string      $tanggalAkhir,
        iterable    $daftarHalaman,
        GenericData $obat,
        int         $totalMasuk,
        int         $totalKeluar,
        int         $totalSisa,
        string      $namaDepo
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        ob_clean();
        ob_start();
        $jumlahHalaman = count($daftarHalaman);
        ?>


<style>
    .stokopname td {
        font-style: italic;
        color: grey;
    }
</style>

<?php foreach ($daftarHalaman as $idx => $halaman): ?>
<div class="page">
    <div class="header_title">
        KARTU PERSEDIAAN<br/>
        Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?><br/>
        <?= $namaDepo ?? 'GUDANG INDUK FARMASI' ?>
    </div>

    <table>
        <tr>
            <th>Kode Barang</th>
            <td>:</td>
            <td><?= $obat->kodeObat ?></td>
            <th>Kemasan</th>
            <td>:</td>
            <td><?= $obat->kemasan ?></td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td>:</td>
            <td><?= htmlentities($obat->namaBarang, ENT_QUOTES) ?></td>
            <th>Satuan</th>
            <td>:</td>
            <td><?= $obat->namaKemasan ?></td>
        </tr>
        <tr>
            <th>Pabrik</th>
            <td>:</td>
            <td><?= $obat->namaPabrik ?></td>
            <th>No. Kartu</th>
            <td>:</td>
            <td></td>
        </tr>
    </table>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No.</th>
                <th>Deskripsi</th>
                <th>No. Batch</th>
                <th>Tanggal Kadaluarsa</th>
                <th>Harga Satuan</th>
                <th>Terima</th>
                <th>Keluar</th>
                <th>Sisa</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $r => $baris): ?>
            <?php if ($idx == 0 && $r == 0): ?>
            <tr class="saldo-awal">
                <td colspan="8">Saldo Awal</td>
                <td><?= ($baris->tipeTersedia == "stokopname") ? $toUserFloat($baris->sisa) : $toUserFloat($baris->sisa - $baris->terima + $baris->keluar) ?></td>
            </tr>
            <?php endif ?>

            <tr <?= ($baris->tipeTersedia == "stokopname") ? 'class="stokopname"' : "" ?>>
                <td><?= $toUserDatetime($baris->tanggal) ?></td>
                <td><?= $baris->noDokumen ?></td>
                <td><?= $baris->deskripsi ?></td>
                <td><?= $baris->noBatch ?></td>
                <td><?= $toUserDate($baris->tanggalKadaluarsa) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->harga) ?></td>
                <td class="text-right <?= $baris->terima ? 'bold-number' : '' ?>"><?= $toUserFloat($baris->terima) ?></td>
                <td class="text-right <?= $baris->keluar ? 'bold-number' : '' ?>"><?= $toUserFloat($baris->keluar) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->sisa) ?></td>
            </tr>
        <?php endforeach ?>

        <?php if ($idx + 1 == $jumlahHalaman): ?>
            <tr class="row-total">
                <td class="text-right" colspan="6">Total</td>
                <td class="text-right"><?= $toUserFloat($totalMasuk) ?></td>
                <td class="text-right"><?= $toUserFloat($totalKeluar) ?></td>
                <td class="text-right"><?= $toUserFloat($totalSisa) ?></td>
            </tr>
        <?php endif ?>
        </tbody>
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
