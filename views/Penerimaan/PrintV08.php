<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Penerimaan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/print_v_08.php the original file
 */
final class PrintV08
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        iterable    $daftarHalaman,
        GenericData $penerimaan,
        iterable    $daftarDetailPenerimaan,
        int         $jumlahHalaman
    ) {
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
#<?= $pageId ?> .print_area {
    width: 230mm;
    margin: 0 25mm 25mm 0;
    height: 350mm;
    padding: 10mm;
    border: 1px #D3D3D3 solid;
    border-radius: 5px;
    background: white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

@media print {
    #<?= $pageId ?> .noPrint {
        display: none;
    }

    #<?= $pageId ?> .print_area {
        page-break-before: always;
    }
}
</style>

<?php foreach ($daftarHalaman as $n => $halaman): ?>
<div class="print_area">
    <div>Tanggal: <?= $toUserDate($penerimaan->tanggalDokumen) ?></div>

    <table>
        <tr>
            <td>No. Terima</td>
            <td>:</td>
            <td><?= $penerimaan->noDokumen ?></td>
        </tr>
        <tr>
            <td>No. BTB</td>
            <td>:</td>
            <td><?= $penerimaan->kode ?></td>
        </tr>
        <tr>
            <td>No. Faktur</td>
            <td>:</td>
            <td><?= $penerimaan->noFaktur ?? "-" ?></td>
        </tr>
        <tr>
            <td>No. Surat Jalan</td>
            <td>:</td>
            <td><?= $penerimaan->noSuratJalan ?? "-" ?></td>
        </tr>
        <tr>
            <td>No. SP/SPK</td>
            <td>:</td>
            <td><?= $penerimaan->noSpk ?></td>
        </tr>
        <tr>
            <td>No. SPB</td>
            <td>:</td>
            <td><?= $penerimaan->noPo ?></td>
        </tr>
        <tr>
            <td>Pengirim</td>
            <td>:</td>
            <td style="text-transform:uppercase"><?= $penerimaan->namaPemasok ?></td>
        </tr>
    </table>

    <h3>BUKTI PENYERAHAN BARANG</h3>

    <br/>

    <table class="p_table">
        <thead>
            <tr>
                <th>NO.</th>
                <th>NAMA BARANG</th>
                <th>PABRIK</th>
                <th>KUANTITAS</th>
                <th>SATUAN BARANG</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as ["i" => $i]): ?>
            <?php $dPenerimaan = $daftarDetailPenerimaan[$i] ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $dPenerimaan->namaSediaan ?></td>
                <td><?= $dPenerimaan->namaPabrik ?></td>
                <td class="text-right"><?= $toUserFloat($dPenerimaan->jumlahItem) ?></td>
                <td><?= $dPenerimaan->satuan ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <br/>

    <?php if ($n + 1 == $jumlahHalaman): ?>
    <table class="table table-bordered text-center">
        <tr>
            <th>Ver. No.</th>
            <th>Ver.</th>
            <th>Otorisasi</th>
            <th>User</th>
            <th>Tanggal</th>
            <th>Update Stok</th>
        </tr>
        <tr>
            <td>1</td>
            <td><i class="fa fa-<?= ($penerimaan->verTerima == '1') ? 'check-' : '' ?>square-o"></i></td>
            <td>Tim Penerima</td>
            <td><?= $penerimaan->userTerima ?? "-" ?></td>
            <td><?= $penerimaan->verTanggalTerima ? $toUserDatetime($penerimaan->verTanggalTerima) : "-" ?></td>
            <td><i class="fa fa-square-o"></i></td>
        </tr>
        <tr>
            <td>2</td>
            <td><i class="fa fa-<?= ($penerimaan->verGudang == '1') ? 'check-' : '' ?>square-o"></i></td>
            <td>Gudang</td>
            <td><?= $penerimaan->userGudang ?? "-" ?></td>
            <td><?= $penerimaan->verTanggalGudang ? $toUserDatetime($penerimaan->verTanggalGudang) : "-" ?></td>
            <td><i class="fa fa-<?= ($penerimaan->verGudang == '1') ? 'check-' : '' ?>square-o"></i></td>
        </tr>
        <tr>
            <td>3</td>
            <td><i class="fa fa-<?= ($penerimaan->verAkuntansi == '1') ? 'check-' : '' ?>square-o"></i></td>
            <td>Akuntansi</td>
            <td><?= $penerimaan->userAkuntansi ?? "-" ?></td>
            <td><?= $penerimaan->verTanggalAkuntansi ? $toUserDatetime($penerimaan->verTanggalAkuntansi) : "-" ?></td>
            <td><i class="fa fa-square-o"></i></td>
        </tr>
    </table>
    <?php endif ?>
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
