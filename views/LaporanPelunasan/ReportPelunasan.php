<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPelunasan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporanpelunasan3_new.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanPelunasan3New: commit-e37d34f4
 */
final class ReportPelunasan
{
    private string $output;

    public function __construct(
        string $namaDepo,
        string $dariTanggal,
        string $sampaiTanggal,
        string $namaRuangRawat,
        array  $daftarJenisResep,
        array  $daftarSemuaHalaman,
        array  $daftarSemuaSubtotal,
        array  $daftarSemuaTotal,
        int    $grandTotalHargaJual,
        int    $grandTotalDiskon,
        int    $grandTotalJasaPelayanan,
        int    $grandTotalPembulatan,
        int    $grandTotalTotal
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarJenisResep as $jenisResep): ?>
    <?php
    $daftarHalamanPerJenisResep = $daftarSemuaHalaman[$jenisResep];
    $daftarSubtotalPerJenisResep = $daftarSemuaSubtotal[$jenisResep];
    $daftarTotalPerJenisResep = $daftarSemuaTotal[$jenisResep];
    $totalPages = count($daftarHalamanPerJenisResep) - 1;
    ?>
    <?php foreach ($daftarHalamanPerJenisResep as $h => $halaman): ?>
        <div class="page">
            <div class="header">
                INSTALASI FARMASI<br/>
                LAPORAN PELUNASAN TANGGAL <?= $dariTanggal ?> s.d. <?= $sampaiTanggal ?><br/>
                DEPO: <?= $namaDepo ?><br/>
                RUANG RAWAT <?= $namaRuangRawat ?>
            </div>

            <?php if ($h == 0): ?>
            <table class="jenis_resep_table">
                <tr>
                    <td><?= $jenisResep ?></td>
                </tr>
            </table>
            <?php endif ?>

            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Resep / Nama Pasien / Kode Rekam Medis</th>
                        <th>Cara Bayar</th>
                        <th>Status</th>
                        <th>Bayar</th>
                        <th>Harga Jual</th>
                        <th>Diskon</th>
                        <th>Jasa Pelayanan</th>
                        <th>Pembulatan</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($halaman as $k => $baris): ?>
                    <tr>
                        <td><?= $k+1 ?></td>
                        <td><?= $baris->noResep ?> / <?= substr($baris->namaPasien, 0, 15) ?> / <?= $baris->kodeRekamMedis ?></td>
                        <td><?= substr($baris->caraBayar, 0, 10) ?></td>
                        <td><?= substr($baris->statusBayar, 0, 17) ?></td>
                        <td><?= substr($baris->bayarUser, 0, strpos($baris->bayarUser, '_')) ?></td>
                        <td class="text-right"><?= $toUserFloat($baris->hargaJual) ?></td>
                        <td class="text-right"><?= $toUserFloat($baris->diskon) ?></td>
                        <td class="text-right"><?= $toUserFloat($baris->jasaPelayanan) ?></td>
                        <td class="text-right"><?= $toUserFloat($baris->pembulatan) ?></td>
                        <td class="text-right"><?= $toUserFloat($baris->total) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>

                <?php if ($h == $totalPages): ?>
                <tfoot>
                    <tr>
                        <td colspan="5"></td>
                        <td class="text-right" style="font-weight:bold"><?= $toUserFloat($daftarSubtotalPerJenisResep->hargaJual) ?></td>
                        <td class="text-right" style="font-weight:bold"><?= $toUserFloat($daftarSubtotalPerJenisResep->diskon) ?></td>
                        <td class="text-right" style="font-weight:bold"><?= $toUserFloat($daftarSubtotalPerJenisResep->jasaPelayanan) ?></td>
                        <td class="text-right" style="font-weight:bold"><?= $toUserFloat($daftarSubtotalPerJenisResep->pembulatan) ?></td>
                        <td class="text-right" style="font-weight:bold"><?= $toUserFloat($daftarSubtotalPerJenisResep->total) ?></td>
                    </tr>
                </tfoot>
                <?php endif ?>
            </table>

            <?php if ($h == $totalPages): ?>
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Harga Jual</th>
                        <th>Diskon</th>
                        <th>Jasa Pelayanan</th>
                        <th>Pembulatan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($daftarTotalPerJenisResep as $caraBayar => $jumlah): ?>
                    <tr>
                        <th><?= $caraBayar ?></th>
                        <td class="text-right"><?= $toUserFloat($jumlah->hargaJual) ?></td>
                        <td class="text-right"><?= $toUserFloat($jumlah->diskon) ?></td>
                        <td class="text-right"><?= $toUserFloat($jumlah->jasaPelayanan) ?></td>
                        <td class="text-right"><?= $toUserFloat($jumlah->pembulatan) ?></td>
                        <td class="text-right"><?= $toUserFloat($jumlah->total) ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
            <?php endif ?>
        </div>
    <?php endforeach ?>
<?php endforeach ?>

<div class="page">
    <div class="header">
        INSTALASI FARMASI<br/>
        LAPORAN PELUNASAN<br/>
        DEPO: <?= $namaDepo ?><br/>
        TANGGAL <?= $dariTanggal ?> s.d. <?= $sampaiTanggal ?><br/>
        RUANG RAWAT <?= $namaRuangRawat ?>
    </div>

    <table class="f_table">
        <thead>
            <tr>
                <th></th>
                <th>Total Harga Jual</th>
                <th>Total Diskon</th>
                <th>Total Jasa Pelayanan</th>
                <th>Total Pembulatan</th>
                <th>Total Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Jumlah</th>
                <td class="text-right"><?= $toUserFloat($grandTotalHargaJual) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalDiskon) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalJasaPelayanan) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalPembulatan) ?></td>
                <td class="text-right"><?= $toUserFloat($grandTotalTotal) ?></td>
            </tr>
        </tbody>
    </table>
</div>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
