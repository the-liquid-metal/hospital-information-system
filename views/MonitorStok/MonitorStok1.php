<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\MonitorStok;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Monitorstok/monitorstok.php the original file
 */
final class MonitorStok1
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string      $tableWidgetId,
        string      $dokumenWidgetId,
        iterable    $daftarStokKatalog,
        GenericData $adm
    ) {
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        ob_clean();
        ob_start();
        $pageId = "";

        $newData = [];
        foreach ($daftarStokKatalog as $baris) {
            $newData[$baris->kelompokBarang][] = $baris;
        }

        $lastKey = key(array_slice($newData, -1, 1, true));
        $grandTotal = 0;
        ?>


<style>
@media print {
    #<?= $pageId ?> .page {
        margin: 20px 15px 0;
        page-break-after: always;
    }

    #<?= $pageId ?> .header {
        margin-top: -15px;
    }

    #<?= $pageId ?> .page:last-child {
        page-break-before: avoid !important;
        margin-bottom: 0;
    }

    #<?= $pageId ?> .content div p {
        line-height: 30%;
        font-size: 6px;
    }

    #<?= $pageId ?> .monitor_stok_header tr td {
        padding: 0;
        border-spacing: 0;
        line-height: 50%;
    }

    #<?= $pageId ?> .monitor_stok_header tr:first-child td {
        padding: 10px 0 0 0;
    }

    #<?= $pageId ?> .monitor_stok_header tr td p {
        font-size: 12px;
        font-weight: normal;
    }

    #<?= $pageId ?> .p_table2 thead.t_head tr th {
        padding: 0 !important;
        border-spacing: 0 !important;
        border-top: 1px dashed black !important;
        border-bottom: none !important;
        border-left: none !important;
        border-right: none !important;
    }

    #<?= $pageId ?> .p_table2 {
        width: 95%;
    }

    #<?= $pageId ?> .p_table2 tbody tr td {
        padding: 0 !important;
        letter-spacing: 0 !important;
        border-spacing: 0 !important;
        border: 1px dashed black !important;
        line-height: 130%;
    }

    #<?= $pageId ?> .p_table2 tbody tr td:nth-child(2),
    #<?= $pageId ?> .p_table2 tbody tr td:nth-child(4) {
        padding-left: 5px !important;
    }

    #<?= $pageId ?> .p_table2 tr td:nth-child(1) {
        width: 3%;
        text-align: center;
    }

    #<?= $pageId ?> .p_table2 tbody tr td:last-child {
        border-right: none !important;
    }

    #<?= $pageId ?> .p_table2 tbody tr td:nth-child(1) {
        width: 3%;
        text-align: center;
    }

    #<?= $pageId ?> .p_table2 tr td:nth-child(5) {
        width: 30px;
        text-align: center;
    }

    #<?= $pageId ?> .p_table2 tr td:nth-child(6) {
        width: 60px;
    }

    #<?= $pageId ?> .p_table2 tr td:nth-child(7) {
        width: 70px;
    }

    #<?= $pageId ?> .p_table2 tr td:nth-child(8) {
        width: 75px;
    }

    #<?= $pageId ?> .p_table2 tr td:nth-child(9) {
        width: 75px;
    }

    #<?= $pageId ?> .p_table2 tr td:last-child {
        padding-right: 5px;
        width: 25px;
    }

    #<?= $pageId ?> tr.subtotal td {
        font-weight: bold;
    }

    #<?= $pageId ?> tr.subtotal td:nth-child(2) {
        text-align: right;
    }

    #<?= $pageId ?> .footer {
        width: 95%;
    }

    #<?= $pageId ?> .table_footer {
        width: 100%;
    }

    #<?= $pageId ?> .table_footer td {
        font-weight: bold;
        border: 1px dashed black;
    }

    #<?= $pageId ?> .table_footer td:first-child {
        width: 70%;
        text-align: right;
    }

    #<?= $pageId ?> .table_footer td:last-child {
        width: 30%;
        text-align: right;
    }
}

#<?= $pageId ?> .p_table2 thead tr th {
    border: 1px solid rgb(74, 74, 74) !important;
}

#<?= $pageId ?> .p_table2 tr td {
    border: 1px solid rgb(74, 74, 74) !important;
}

#<?= $pageId ?> .p_table2 th {
    background-color: #FFFFFF !important;
}

#<?= $pageId ?> .p_table2 tbody td {
    padding: 4px 4px;
}

#<?= $pageId ?> .p_table2 tbody td:first-child {
    text-align: right;
}

#<?= $pageId ?> .p_table2 tbody td:last-child {
    text-align: right;
}

#<?= $pageId ?> .monitor_stok_header {
    font: 10pt/1.0 Tahoma, serif !important;
}

#<?= $pageId ?> .monitor_stok_header table tr td {
    padding: 0 !important;
}
</style>

<script>
$(() => {
    "use strict";

    const grandTotal = "<?= $toUserFloat($grandTotal) ?>";
    const pageFld = document.querySelector("#<?= $pageId ?>");

    pageFld.querySelector(".last_page").innerHTML = `
        <div>
            <table class="table_footer">
                <tr>
                    <td>Total</td>
                    <td>${grandTotal}</td>
                </tr>
            </table>
        </div>`;
});
</script>

<a href="<?= $tableWidgetId ?>">Kembali</a>
<a class="btn-print" href="<?= $dokumenWidgetId .'/'.$adm->kode.'/print_v1' ?>">Cetak E-Katalog versi 2</a>

<?php foreach ($newData as $kelompokBarang => $value): ?>
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
    $b = 1;
    ?>
    <?php foreach ($daftarHalaman as $idx => $halaman): ?>
    <div class="page">
        <div>Monitor Persediaan</div>
        <div>Gudang: <?= $adm->namaDepo ?></div>
        <div>Tanggal ADM: <?= $toUserDatetime($adm->tanggalAdm) ?></div>
        <div>Tanggal Cetak Layar: <?= $nowValUser ?></div>

        <p><?= $kelompokBarang ?></p>

        <table class="p_table2">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Pabrik</th>
                    <th>Satuan</th>
                    <th>JUMLAH FISIK</th>
                    <th>STOK ADM</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                    <th>Kadaluarsa</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($halaman as $baris): ?>
                <?php $stokFisik = $baris->jumlahStokFisik ?>
                <tr>
                    <td><?= $b ?></td>
                    <td><?= $baris->kodeBarang ?></td>
                    <td><?= $baris->namaBarang ?></td>
                    <td><?= $baris->namaPabrik ?></td>
                    <td><?= $baris->kodeKemasan ?></td>
                    <td></td>
                    <td class="text-right"><?= $stokFisik ? $toUserFloat($stokFisik) : "Minus(-".$toUserFloat($stokFisik).")" ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->hja) ?></td>
                    <td class="text-right"><?= $toUserFloat($baris->hja * $stokFisik) ?></td>
                    <td></td>
                </tr>
                <?php $b++ ?>
            <?php endforeach ?>

            <?php if ($idx == $h): ?>
                <tr class="subtotal">
                    <td class="text-right" colspan="6">Subtotal</td>
                    <td class="text-right" colspan="3"><?= $toUserFloat($subtotalKelompokBarang) ?></td>
                    <td></td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>

        <?php if ($idx == $h): ?>
        <div class="footer <?= ($kelompokBarang == $lastKey) ? 'last_page' : "" ?>"></div>
        <?php endif ?>
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
