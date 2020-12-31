<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPengeluaran;

use Yii;
use yii\db\{Connection, Exception};

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporan_rekap2_fox.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\Laporan\LaporanRekap2Fox: commit-e37d34f4
 *
 * TODO: php: uncategorized: tidy up
 */
final class ReportPerTujuan
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function __construct(string $tanggalAwal, string $tanggalAkhir, iterable $daftarPeringatan, Connection $connection)
    {
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $jenis = "";
        $html = "";
        $html2 = "";
        $total = 0;
        $subtotal = 0;
        $subtotalPerDepo = 0;
        $depoPeminta = '0';
        $i = 1;
        ?>


<div class="header">
    REKAPITULASI PENGELUARAN BARANG<br/>
    Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?>
</div>

<table class="table table-bordered table-condensed">
    <thead>
        <tr class="thead_header">
            <th>No.</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Pabrik</th>
            <th>Kuantitas</th>
            <th>Satuan</th>
            <th>@ (Rp.)</th>
            <th>Jumlah (Rp.)</th>
        </tr>
    </thead>

    <tbody>
    <?php
    foreach ($daftarPeringatan as $peringatan) {
        $peringatan->namaJenis = $peringatan->namaJenis ?: "Lain - Lain";
        if ($peringatan->namaJenis != $jenis || $jenis == '0') {
            if ($jenis != "0") {
                $html2 .= '
                            <tr class="grup_obat">
                                <td colspan="7">' . $jenis . '</td>
                                <td class="text-right">' . $toUserInt($subtotal) . '</td>
                            </tr>' . $html;
            }
            $subtotal = 0;
            $i = 1;
            $html = '';
        }

        if ($peringatan->depoPeminta != $depoPeminta && $depoPeminta != '0') {
            if ($html2 == '') {
                $html2 .= '
                            <tr class="grup_obat">
                                <td colspan="7">' . $jenis . '</td>
                                <td class="text-right">' . $toUserInt($subtotal) . '</td>
                            </tr>' . $html;
            }
            $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT namaDepo
                        FROM rsupf.masterf_depo
                        WHERE kode = :kode
                        LIMIT 1
                    ";
            $params = [":kode" => $depoPeminta];
            $namaDepo = $connection->createCommand($sql, $params)->queryScalar();

            echo '
                        <tr class="grup_obat tujuan">
                            <td colspan="7">Tujuan: ' . $namaDepo . '</td>
                            <td class="text-right">' . $toUserInt($subtotalPerDepo) . '</td>
                        </tr>';
            echo $html2;

            $subtotalPerDepo = 0;
            $html2 = '';
            $subtotal = 0;
            $i = 1;
            $html = '';
        }

        $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT hp_item
                    FROM rsupf.relasif_hargaperolehan
                    WHERE
                        id_katalog = :katalog_id
                        AND tgl_hp <= :tgl_hp
                    ORDER BY tgl_hp DESC
                    LIMIT 1
                ";
        $params = [
            ":katalog_id" => $peringatan->kodeItem,
            ":tgl_hp" => $peringatan->tanggalVerifikasi,
        ];
        $hpItem = $connection->createCommand($sql, $params)->queryScalar();

        $html .= '
                    <tr>
                        <td>' . $i . '</td>
                        <td>' . $peringatan->kodeItem . '</td>
                        <td>' . $peringatan->namaBarang . '</td>
                        <td>' . $peringatan->namaPabrik . '</td>
                        <td class="text-right">' . $toUserInt($peringatan->totalJumlah) . '</td>
                        <td>' . $peringatan->namaKemasan . '</td>
                        <td class="text-right">' . $toUserFloat($hpItem) . '</td>
                        <td class="text-right">' . $toUserFloat($hpItem * $peringatan->totalJumlah) . '</td>
                    </tr>';

        $jenis = $peringatan->namaJenis;
        $depoPeminta = $peringatan->depoPeminta;
        $subtotal += $hpItem * $peringatan->totalJumlah;
        $subtotalPerDepo += $hpItem * $peringatan->totalJumlah;
        $total += $hpItem * $peringatan->totalJumlah;
        $i++;
    }

    $html2 .= '<tr class="grup_obat">
                            <td colspan="7">' . $jenis . '</td>
                            <td class="text-right">' . $toUserFloat($subtotal) . '</td>
                        </tr>' . $html;

    $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT namaDepo
                FROM rsupf.masterf_depo
                WHERE kode = :kode
                LIMIT 1
            ";
    $params = [":kode" => $depoPeminta];
    $namaDepo = $connection->createCommand($sql, $params)->queryScalar();
    ?>

    <tr class="grup_obat">
        <td colspan="7">Tujuan: <?= $namaDepo ?></td>
        <td class="text-right"><?= $toUserFloat($subtotalPerDepo) ?></td>
    </tr>
    <?= $html2 ?>
    <tr>
        <td class="text-right" colspan="6">Subtotal <?= $namaDepo ?></td>
        <td></td>
        <td class="text-right"><?= $toUserFloat($total) ?></td>
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
