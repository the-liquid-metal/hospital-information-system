<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanTakTerlayani;

use tlm\libs\LowEnd\components\GenericData;
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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporan_rekap_harian2_tdkterlayani_0.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanRekapHarian2TakTerlayani0: commit-e37d34f4
 *
 * TODO: php: uncategorized: tidy up
 */
final class ReportRekapTakTerlayani0
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function __construct(
        string      $tanggalAwal,
        string      $tanggalAkhir,
        GenericData $depo,
        iterable    $daftarPeringatan,
    ) {
        $toUserFloat = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        $connection = Yii::$app->dbFatma;
        $jenis = 0;
        $html = "";
        $i = 1;
?>

<div class="page">
    <div class="header">
        REKAPITULASI BARANG TIDAK TERLAYANI<br/>
        <?= $depo->namaDepo ?><br/>
        Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-stripped table-condensed">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Minta</th>
                    <th>Jumlah Beri</th>
                    <th>Gudang</th>
                    <th>IRJ</th>
                    <th>IRJ2</th>
                    <th>IRJ3</th>
                    <th>IGH</th>
                    <th>IBS</th>
                    <th>IGD</th>
                    <th>Produksi</th>
                    <th>Teratai</th>
                    <th>Bougenvil</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($daftarPeringatan as $peringatan): ?>
                <?php $peringatan->namaKelompok = $peringatan->namaKelompok ?: "Lain - Lain" ?>
                <?php if ($peringatan->namaKelompok != $jenis || $jenis == "0"): ?>
                    <?php if ($jenis != "0"): ?>
                        <tr class="grup_obat">
                            <td colspan="17"><?= $jenis ?></td>
                        </tr>
                        <?= $html ?>
                    <?php endif ?>
                    <?php
                    $i = 1;
                    $html = '';
                    ?>
                <?php endif ?>

                <?php
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__."
                    -- LINE: ".__LINE__."
                    SELECT
                        jumlah_stokfisik AS jumlah,
                        id_depo          AS idDepo
                    FROM rsupf.transaksif_stokkatalog
                    WHERE
                        id_katalog = :idKatalog
                        AND (id_depo IN (59, 23, 64, 61, 25, 26, 27, 28, 30, 129))
                ";
                $params = [":idKatalog" => $peringatan->kodeItem];
                $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

                $x = [];
                foreach ($daftarStokKatalog as $item) {
                    $x[$item->idDepo] = $item->jumlah;
                }

                $html .=
                    '<tr>
                        <td>' . $i . '</td>
                        <td>' . $peringatan->kodeItem . '</td>
                        <td>' . $peringatan->namaBarang . '</td>
                        <td class="text-right">' . $toUserFloat($peringatan->sumJumlah1) . '</td>
                        <td class="text-right">' . $toUserFloat($peringatan->sumJumlah2) . '</td>
                        <td class="text-right">' . $toUserFloat($x["59"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["23"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["64"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["61"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["25"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["26"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["27"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["28"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["30"]  ?? 0) . '</td>
                        <td class="text-right">' . $toUserFloat($x["129"] ?? 0) . '</td>
                    </tr>';
                $jenis = $peringatan->namaKelompok;
                $i++;
                ?>
            <?php endforeach ?>

                <tr class="grup_obat">
                    <td colspan="17"><?= $jenis ?></td>
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
