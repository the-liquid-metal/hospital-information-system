<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Pembelian;

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
 * @used-by \tlm\his\FatmaPharmacy\controllers\PembelianController::actionReports()
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/laporan_akhir.php the original file
 */
final class LaporanAkhir
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
        string   $subjenisAnggaran
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserFloat = Yii::$app->number->toUserFloat();
        $baseUrl = Yii::$app->basePath;
        ob_clean();
        ob_start();
        $pageId = "";
        $no = 1;
        $jumlahDo = 0;
        $jumlahPl = 0;
        $daftarPl = [];
        ?>


<style>
#<?= $pageId ?> table.p_table tr td {
    line-height: 150%;
}

@media print {
    #<?= $pageId ?> .btn_print {
        display: none;
    }

    #<?= $pageId ?> table.p_table tr td {
        line-height: 100%;
    }

    #<?= $pageId ?> table.table_header {
        margin-top: -30px;
    }

    #<?= $pageId ?> table.table_footer {
        margin-bottom: -30px;
    }
}
</style>

<?php foreach ($daftarHalaman as $halaman): ?>
<div class="page">
    <img src="<?= $baseUrl ?>/assets/img/bakti_husada.jpg" width="80%" height="150" alt=""/>
    KEMENTRIAN KESEHATAN RI<br/>
    DIREKTORAT JENDERAL BINA UPAYA KESEHATAN
    <img src="<?= $baseUrl ?>/assets/img/logo.png" width="60%" height="100px" alt=""/>

    <h2>LAPORAN KONTRAK/SPK/SP</h2>
    <h2>
        Periode: <?= $tanggalAwal ? $toUserDate($tanggalAwal) : "-" ?>
        s.d. <?= $tanggalAkhir ? $toUserDate($tanggalAkhir) : "-" ?>
    </h2>
    <h3><?= $subjenisAnggaran ?></h3>

    <table class="p_table">
        <thead>
            <tr>
                <th rowspan="2">NO.</th>
                <th colspan="2">PL PEMBELIAN</th>
                <th colspan="2">DO (DELIVERY ORDER)</th>
                <th rowspan="2">PENYEDIA BARANG</th>
            </tr>
            <tr>
                <th>NO. PL</th>
                <th>JUMLAH</th>
                <th>NO. DO</th>
                <th>JUMLAH</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($daftarPl[$baris->kode])): ?>
                <?php $jumlahDo += $baris->nilaiAkhirDo ?>
                <tr>
                    <td colspan="3"></td>
                    <td><?= $baris->noDo ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->nilaiAkhirDo) ?></td>
                    <td></td>
                </tr>

            <?php else:
                $daftarPl[$baris->kode] = $baris->nilaiAkhir;
                $jumlahPl = $baris->nilaiAkhir;
                $jumlahDo += $baris->nilaiAkhirDo;
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $baris->noDokumen ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->nilaiAkhir) ?></td>
                    <td><?= $baris->noDo ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->nilaiAkhirDo) ?></td>
                    <td><?= $baris->namaPemasok ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="text-right"><?= $toUserFloat($jumlahPl) ?></td>
                <td>TOTAL DO</td>
                <td class="text-right"><?= $toUserFloat($jumlahDo) ?></td>
                <td class="text-right"><?= $toUserFloat($jumlahPl - $jumlahDo) ?></td>
            </tr>
        </tfoot>
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
