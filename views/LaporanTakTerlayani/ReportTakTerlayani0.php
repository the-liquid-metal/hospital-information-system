<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanTakTerlayani;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporan_harian2_tdkterlayani0.php the original file
 */
final class ReportTakTerlayani0
{
    private string $output;

    public function __construct(object $depo, string $tanggalAwal, string $tanggalAkhir, iterable $daftarPeringatan)
    {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $jenis = "";
        $html = "";
        $i = 0;
        ?>


<div class="page">
    <div class="header">
        RINCIAN BARANG TIDAK TERLAYANI<br/>
        <?= $depo->namaDepo ?><br/>
        Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-stripped table-condensed">
            <thead>
                <tr class="thead_header">
                    <th>No.</th>
                    <th>Depo</th>
                    <th>No. Permintaan</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Minta</th>
                    <th>Jumlah Beri</th>
                    <th>Stok Minta</th>
                    <th>Stok Beri</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($daftarPeringatan as $peringatan): ?>
                    <?php $peringatan->namaKelompok = $peringatan->namaKelompok ?: "Lain - Lain" ?>
                    <?php if ($peringatan->namaKelompok != $jenis || $jenis == "0"): ?>
                        <?php if ($jenis != "0"): ?>
                        <tr class="grup_obat">
                            <td colspan="9"><?= $jenis ?></td>
                        </tr>
                        <?= $html ?>
                        <?php endif ?>
                        <?php
                        $i = 1;
                        $html = '';
                        ?>
                    <?php endif ?>

                    <?php
                    $html .=
                        '<tr>
                            <td>' . $i . '</td>
                            <td>' . $peringatan->namaDepo . '</td>
                            <td>' . $peringatan->noDokumen . '</td>
                            <td>' . $peringatan->kodeItem . '</td>
                            <td>' . $peringatan->namaBarang . '</td>
                            <td class="text-right">' . $toUserFloat($peringatan->sumJumlah1) . '</td>
                            <td class="text-right">' . $toUserFloat($peringatan->sumJumlah2) . '</td>
                            <td class="text-right">' . $toUserFloat($peringatan->stokMinta) . '</td>
                            <td class="text-right">' . $toUserFloat($peringatan->stokBeri) . '</td>
                        </tr>';
                    $jenis = $peringatan->namaKelompok;
                    $i++;
                    ?>
                <?php endforeach ?>

                <tr class="grup_obat">
                    <td colspan="9"><?= $jenis ?></td>
                </tr>
                <?= $html ?>
            </tbody>
        </table>
    </div>
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
