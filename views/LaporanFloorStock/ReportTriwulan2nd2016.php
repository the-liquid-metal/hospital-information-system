<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanFloorStock;

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
 * @see ? the original file
 */
final class ReportTriwulan2nd2016
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(iterable $daftarHalaman, int $jumlahHalaman, string $bulan, int $totalNilai)
    {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<style>
    @media print {
        .noPrint {
            display: none;
        }
        .page2 {
            padding: 0;
            border: none !important;
            box-shadow: none !important;
            background: none !important;
            page-break-after: always;
        }
        .page2:last-child {
            page-break-after: avoid !important;
        }
    }
    .page2 {
        width: 230mm;
        margin: 0 25mm 25mm 0;
        padding: 10mm;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .t_body thead tr td {
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-left: 1px solid black;
    }
    .t_body tr td {
        border-right: 1px solid black;
        border-left: 1px solid black;
    }
    table.t_body tr:last-child td {
        border-bottom: 1px solid black;
    }
    .t_body tbody .tr_jenis td {
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-left: 1px solid black;
    }
</style>

<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="page2 print_area">
    <div>Tanggal Cetak: <?= $nowValUser ?></div>
    <div>INSTALASI FARMASI</div>
    <div>PERSEDIAAN FLOOR STOK (RUANGAN)</div>
    <div>Bulan: <?= $bulan ?> 2016</div>
    <div>Unit: Semua Unit</div>

    <table class="t_body">
        <thead>
            <tr>
                <td>No.</td>
                <td>Jenis Obat</td>
                <td>Nilai</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <tr>
                <td><?= $baris->i + 1 ?></td>
                <td><?= $baris->jenisObat ?? "Lain - Lain" ?></td>
                <td class="text-right"><?= $toUserFloat($baris->total) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>

        <?php if ($n + 1 == $jumlahHalaman): ?>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
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
