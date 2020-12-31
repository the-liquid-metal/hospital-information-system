<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Stokopname;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/beluminput.php the original file
 */
final class BelumInput
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        iterable $daftarHalaman,
        string   $tanggalAdm,
        string   $namaDepo,
        iterable $daftarOpname,
        float    $totalNilai,
    ) {
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $toUserFloat = Yii::$app->number->toUserFloat();
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> .r_table td {
    padding-left: 7px;
}

#<?= $pageId ?> .r_table .t_head {
    padding: 4px 4px !important;
    border-top: 1px solid #4a4a4a !important;
    border-bottom: 1px solid #4a4a4a !important;
}

#<?= $pageId ?> .r_table tfoot {
    border-top: 1px solid #4a4a4a !important;
}

#<?= $pageId ?> .monitor_stok_header table tr td {
    padding: 0 !important;
}
</style>

<?php foreach ($daftarHalaman as $halaman): ?>
<div class="page">
    <div>DAFTAR BARANG YANG BELUM DILAKUKAN STOK OPNAME</div>
    <div>Tanggal: <?= $toUserDatetime($tanggalAdm) ?></div>
    <div>Gudang: <?= $namaDepo ?></div>
    <div>Tanggal Cetak: <?= $nowValUser ?></div>

    <table class="r_table">
        <thead class="t_head">
            <tr>
                <th>NO.</th>
                <th>KODE</th>
                <th>BARANG</th>
                <th>PABRIK</th>
                <th>SATUAN</th>
                <th>STOK ADM</th>
                <th>HARGA POKOK</th>
                <th>NILAI</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->namaJenis)): ?>
                <tr class="t_head">
                    <td colspan="6"><strong><?= $baris->no . $baris->namaJenis ?></strong></td>
                    <td class="text-right"><?= $toUserFloat($baris->totalNilai) ?></td>
                </tr>

            <?php elseif (isset($baris->namaKelompok)): ?>
                <tr class="t_head">
                    <td colspan="6"><strong><?= $baris->no . $baris->namaKelompok ?></strong></td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalNilai) ?></td>
                </tr>

            <?php else: ?>
                <?php $opname = $daftarOpname[$baris->i] ?>
                <tr>
                    <td><?= $baris->no ?></td>
                    <td><?= $opname->kode ?></td>
                    <td><?= $opname->namaBarang ?></td>
                    <td><?= $opname->namaPabrik ?></td>
                    <td><?= $opname->satuan ?></td>
                    <td class="text-right"><?= $toUserFloat($opname->backupStokAdm) ?></td>
                    <td class="text-right"><?= $toUserFloat($opname->hpItem) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->nilai) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>

        <tfoot>
            <tr>
                <td class="text-right" colspan="7">Total Nilai</td>
                <td class="text-right">Rp. <?= $toUserFloat($totalNilai) ?></td>
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
