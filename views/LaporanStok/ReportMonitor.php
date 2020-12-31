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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/monitorstok/monitorstok2.php the original file
 */
final class ReportMonitor
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(iterable $daftarStokKatalog, GenericData $adm, string $keyAkhir)
    {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $grandTotal = 0;
        ?>


<?php foreach ($daftarStokKatalog as $kelompokBarang => $value): ?>
    <?php
    $daftarHalaman = [];
    $subtotalKelompokBarang = 0;

    $h = 0; // index halaman
    $b = 0; // index baris
    $posisi = 0;
    $barisPerHalaman = 32;
    $maksHurufBarang = 38;
    $maksHurufPabrik = 16;

    foreach ($value as $r) {
        $jumlahBarisBarang = ceil(strlen($r->namaBarang) / $maksHurufBarang);
        $jumlahBarisPabrik = ceil(strlen($r->namaPabrik) / $maksHurufPabrik);
        $butuhBaris = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

        $daftarHalaman[$h][$b] = $r;
        $subtotalKelompokBarang += $r->hja * $r->jumlahStokFisik;

        if ($posisi > $barisPerHalaman) {
            $h++;
            $b = 0;
            $posisi = 0;
        } elseif (($posisi + $butuhBaris) > $barisPerHalaman) {
            $h++;
            $b = 0;
            $posisi = 0;
        } else {
            $b++;
            $posisi += $butuhBaris;
        }
    }

    $grandTotal += $subtotalKelompokBarang;
    ?>
    <?php foreach ($daftarHalaman as $idx => $halaman): ?>
    <div class="page">
        <div>Monitor Persediaan</div>
        <div>Gudang: <?= $adm->namaDepo ?? 'Semua Depo' ?></div>
        <div>Tanggal ADM: <?= $nowValUser ?></div>
        <div>Tanggal Cetak Layar: <?= $nowValUser ?></div>

        <div><?= $kelompokBarang ?></div>

        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Pabrik</th>
                    <th>Satuan</th>
                    <th>JUMLAH FISIK</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>Kadaluarsa</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($halaman as $b => $baris): ?>
                <?php $stokFisik = $baris->jumlahStokFisik ?>
                <tr>
                    <td><?= $b + 1 ?></td>
                    <td><?= $baris->kodeBarang ?></td>
                    <td><?= $baris->namaBarang ?></td>
                    <td><?= $baris->namaPabrik ?></td>
                    <td><?= $baris->kodeKemasan ?></td>
                    <td class="text-right"><?= $stokFisik ? $toUserFloat($stokFisik) : "Minus(-".$toUserFloat($stokFisik).")" ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->hja) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->hja * $stokFisik) ?></td>
                    <td></td>
                </tr>
            <?php endforeach ?>

            <?php if ($idx == $h): ?>
                <tr class="subtotal">
                    <td class="text-right" colspan="6">Subtotal</td>
                    <td class="text-right" colspan="2"><?= $toUserFloat($subtotalKelompokBarang) ?></td>
                    <td></td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
    <?php endforeach ?>

    <?php if ($kelompokBarang == $keyAkhir): ?>
    <table class="table_footer">
        <tr>
            <td>Total</td>
            <td><?= $toUserFloat($grandTotal) ?></td>
        </tr>
    </table>
    <?php endif ?>
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
