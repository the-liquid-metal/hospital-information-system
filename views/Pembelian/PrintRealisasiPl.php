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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/print_realisasipl.php the original file
 */
final class PrintRealisasiPl
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(iterable $daftarPembelian, iterable $daftarDetailPembelian)
    {
        $nToM = Yii::$app->dateTime->numToMonthNameFunc();
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $baseUrl = Yii::$app->basePath;
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> table.p_table tr td {
    line-height: 150%;
}

@media print {
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

<?php foreach ($daftarPembelian as $kode => $obj): ?>
    <?php
    $daftarHalaman = [];

    $iobj = $daftarDetailPembelian[$kode];
    $h = 0; // index halaman
    $b = 0; // index baris

    foreach ($iobj as $r) {
        if (count($daftarHalaman) == 0 || (isset($daftarHalaman[$h]) && count($daftarHalaman[$h]) < 30)) {
            $daftarHalaman[$h][$b] = $r;
            $b++;
        } else {
            $daftarHalaman[++$h] = [];
            $daftarHalaman[$h][0] = $r;
            $b = 1;
        }
    }

    $awal = $obj->bulanAwalAnggaran;
    $akhir = $obj->bulanAkhirAnggaran;
    $tahun = $obj->tahunAnggaran;

    $no = 1;
    $jumlahTotal = 0;
    $ppn = 0;
    ?>

    <?php foreach ($daftarHalaman as $h => $halaman): ?>
    <div class="page">
        <img src="<?= $baseUrl ?>/assets/img/bakti_husada.jpg" width="80%" height="150" alt=""/>
        <h2>KEMENTRIAN KESEHATAN RI</h2>
        <h3>DIREKTORAT JENDERAL PELAYANAN KESEHATAN</h3>
        <img src="<?= $baseUrl ?>/assets/img/logo.png" width="60%" height="100px" alt=""/>

        <table class="table_header">
            <tr>
                <td style="width:30%">Nama Pemasok</td>
                <td style="width:5%">:</td>
                <td><?= $obj->namaPemasok ?></td>
            </tr>
            <tr>
                <td>No. PL</td>
                <td>:</td>
                <td><?= $obj->noDokumen ?></td>
            </tr>
            <tr>
                <td>Jenis Angaran</td>
                <td>:</td>
                <td><?= $obj->subjenisAnggaran ?></td>
            </tr>
            <tr>
                <td>Anggaran</td>
                <td>:</td>
                <td><?= $nToM($awal) . ($awal == $akhir ? "" : "-".$nToM($akhir)) ." ".$tahun ?></td>
            </tr>
            <tr>
                <td>Tanggal Kontrak</td>
                <td>:</td>
                <td><?= $toUserDate($obj->tanggalJatuhTempo) ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>

        <table class="p_table">
            <thead>
                <tr>
                    <th rowspan="2">NO.</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th rowspan="2">PABRIK</th>
                    <th rowspan="2">KEMASAN</th>
                    <th colspan="2">VOLUME</th>
                    <th rowspan="2">HARGA KEMASAN</th>
                    <th rowspan="2">DISKON (%)</th>
                    <th rowspan="2">JUMLAH</th>
                    <th rowspan="2">JUMLAH DO</th>
                    <th rowspan="2">JUMLAH TERIMA</th>
                </tr>
                <tr>
                    <th>KEMASAN</th>
                    <th>ITEM</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($halaman as $baris): ?>
                <?php
                $km = ($baris->isiKemasan == '1' && $baris->idKemasan == $baris->idKemasanDepo)
                    ? $baris->satuan
                    : $baris->satuanJual . ' ' . $baris->isiKemasan . ' ' . $baris->satuan;

                $jumlah = ($baris->jumlahKemasan * $baris->hargaKemasan) - ($baris->jumlahKemasan * $baris->hargaKemasan * $baris->diskonItem / 100);
                $jumlahTotal += $jumlah;
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $baris->namaSediaan ?></td>
                    <td><?= $baris->namaPabrik ?></td>
                    <td><?= $km ?></td>
                    <td class="text-right" data-a-sign=" <?= $baris->satuanJual ?>"><?= $toUserInt($baris->jumlahKemasan) ?></td>
                    <td class="text-right" data-a-sign=" <?= $baris->satuan ?>"><?= $toUserInt($baris->jumlahItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->hargaKemasan) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->diskonItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($jumlah) ?></td>
                    <td class="text-right"><?= $toUserInt($baris->jumlahPo) ?></td>
                    <td class="text-right"><?= $toUserInt($baris->jumlahTerima) ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>

            <tfoot>
            <?php if ($obj->ppn): ?>
                <?php $ppn = $jumlahTotal * $obj->ppn / 100 ?>
                <tr>
                    <td class="text-right" colspan="8">PPN</td>
                    <td><?= $ppn ?></td>
                </tr>
            <?php endif ?>

            <tr>
                <td class="text-right" colspan="8">TOTAL</td>
                <td><?= $jumlahTotal + $ppn ?></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php endforeach ?>
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
