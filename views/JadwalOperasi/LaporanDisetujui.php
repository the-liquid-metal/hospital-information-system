<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\JadwalOperasi;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/laporan_disetujui.php the original file
 */
final class LaporanDisetujui
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
        string   $namaDokter,
        iterable $result,
        int      $jumlahHalaman
    ) {
        $nowValUser = Yii::$app->dateTime->todayVal("user");
        ob_clean();
        ob_start();
        $pageId = "";
        ?>


<style>
@media print {
    #<?= $pageId ?> .page {
        margin-top: 20px;
        margin-left: 20px;
        margin-right: 20px;
        page-break-after: always;
    }

    #<?= $pageId ?> .table_body {
        width: 95%;
    }

    #<?= $pageId ?> .table_header tr td {
        text-align: center;
    }

    #<?= $pageId ?> .table_header tr:last-child td {
        padding-top: 20px;
        text-align: left;
    }

    #<?= $pageId ?> .table_header tr:first-child td {
        font-size: 15px;
        font-weight: bolder;
    }

    #<?= $pageId ?> .table_body tr td {
        border: 1px solid black;
        padding-left: 2px;
        padding-right: 2px;
    }

    #<?= $pageId ?> .table_body tfoot tr td {
        border: none;
        font-size: 11px;
    }
}

@media screen {
    #<?= $pageId ?> .table_header tr td {
        text-align: center;
    }

    #<?= $pageId ?> .table_header tr:last-child td {
        padding-top: 20px;
        text-align: left;
    }

    #<?= $pageId ?> .table_header tr:first-child td {
        font-size: 15px;
        font-weight: bolder;
    }

    #<?= $pageId ?> .table_body tr td {
        border: 1px solid #D3D3D3;
        padding: 5px 2px;
    }

    #<?= $pageId ?> .table_body tbody tr.even td {
        background-color: lightblue;
    }

    #<?= $pageId ?> .table_body tfoot tr td {
        border: none;
        font-size: 11px;
    }
}
</style>

<?php foreach ($daftarHalaman as $h => $halaman): ?>
<div class="page">
    <div>Laporan Jadwal Operasi Yang Sudah Disetujui</div>
    <div>Tanggal <?= $tanggalAwal, " s.d. ", $tanggalAkhir ?></div>
    <div><?= $namaDokter ?></div>

    <table class="table_body">
        <thead>
            <tr>
                <td>Nama</td>
                <td>Kode Rekam Medis</td>
                <td>Ruang Rawat</td>
                <td>Diagnosa</td>
                <td>Tindakan</td>
                <td>Urutan Operasi</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $b => $baris): ?>
            <?php $row = $result[$baris->i] ?>
            <tr class="<?= ($b & 1) ? "odd": "even" ?>">
                <td><?= $row->nama ?></td>
                <td><?= $row->kodeRekamMedis ?></td>
                <td><?= $row->ruang ?></td>
                <td>
                    <?php foreach ($baris->diagnosa as $diagnosa): ?>
                        - <?= $diagnosa->diagnosaTindakan ?><br/>
                    <?php endforeach ?>
                </td>
                <td>
                    <?php foreach ($baris->tindakan as $tindakan): ?>
                        - <?= $tindakan->diagnosaTindakan ?><br/>
                    <?php endforeach ?>
                </td>
                <td>Ke-<?= $baris->i + 1 ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    Tanggal cetak: <?= $nowValUser ?><br/>
    Hal <?= ($h + 1) . " dari " . $jumlahHalaman ?>
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
