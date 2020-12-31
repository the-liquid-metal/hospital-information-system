<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenerimaan;

use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporanpenerimaan_rekap2.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanPenerimaanRekap2: commit-e37d34f4
 */
final class ReportDepo
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function __construct(
        string   $namaDepo2,
        string   $namaDepo1,
        iterable $daftarPeringatan,
        string   $tanggalAwal,
        string   $tanggalAkhir,
    ) {
        $jenis = 0;
        $html = "";
        $html2 = "";
        $subtotal = "";
        $subtotalPerDepo = 0;
        $total = 0;
        $i = 0;
        $connection = Yii::$app->dbFatma;
        $toUserInt = Yii::$app->number->toUserInt();
        $kodeDepoPeminta = 0;
        ob_clean();
        ob_start();
        ?>

<div>REKAPITULASI PENERIMAAN BARANG</div>
<div><?= $namaDepo2 ?></div>
<div>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>
<div>Asal: <?= $namaDepo1 ?></div>

<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <td>No.</td>
            <td>Kode</td>
            <td>Nama Barang</td>
            <td>Pabrik</td>
            <td>Kuantitas</td>
            <td>Satuan</td>
            <td>@ (Rp.)</td>
            <td>Jumlah (Rp.)</td>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarPeringatan as $peringatan): ?>
        <?php
        $peringatan->namaJenis ??= "Lain - Lain";
        if ($peringatan->namaJenis != $jenis || $jenis == '0') {
            if ($jenis != "0") {
                $html2 .=
                    '<tr class="grup_obat">
                        <td colspan="7">' . $jenis . '</td>
                        <td class="text-right">' . $toUserInt($subtotal) . '</td>
                    </tr>' .
                    $html;
            }
            $total += $subtotal;

            $subtotal = 0;
            $i = 1;
            $html = '';
        }

        $html .=
            '<tr>
                <td>' . $i . '</td>
                <td>' . $peringatan->kodeItem . '</td>
                <td>' . $peringatan->namaBarang . '</td>
                <td>' . $peringatan->namaPabrik . '</td>
                <td class="text-right">' . $toUserInt($peringatan->totalJumlah) . '</td>
                <td>' . $peringatan->namaKemasan . '</td>
                <td class="text-right">' . $toUserInt($peringatan->hpItem) . '</td>
                <td class="text-right">' . $toUserInt($peringatan->hpItem * $peringatan->totalJumlah) . '</td>
            </tr>';

        $jenis = $peringatan->namaJenis;
        $kodeDepoPeminta = $peringatan->kodeDepo;
        $subtotal += $peringatan->hpItem * $peringatan->totalJumlah;
        $subtotalPerDepo += $peringatan->hpItem * $peringatan->totalJumlah;
        $total += $peringatan->hpItem * $peringatan->totalJumlah;
        $i++;
        ?>
    <?php endforeach ?>

        <?php
        $html2 .=
            '<tr class="grup_obat">
                <td colspan="7">' . $jenis . '</td>
                <td class="text-right">' . $toUserInt($subtotal) . '</td>
            </tr>' .
        $html;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__."
            SELECT namaDepo
            FROM rsupf.masterf_depo
            WHERE kode = '$kodeDepoPeminta'
            LIMIT 1
        ";
        $namaDepo = $connection->createCommand($sql)->queryScalar();
        ?>
        <tr class="grup_obat">
            <td colspan="7">Asal: <?= $namaDepo ?></td>
            <td class="text-right"><?= $toUserInt($subtotalPerDepo) ?></td>
        </tr>
        <?= $html2 ?>

        <tr>
            <td colspan="6">Subtotal <?= $namaDepo1 ?></td>
            <td></td>
            <td class="text-right"><?= $toUserInt($total) ?></td>
        </tr>
    </tbody>
</table>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
