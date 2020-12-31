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
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/laporan_dibuat.php the original file
 */
final class LaporanDibuat
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(iterable $daftarHalaman, int $jumlahHalaman)
    {
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $idx => $halaman): ?>
<div class="page">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <td>Nama</td>
                <td>Tanggal</td>
                <td>Kode Rekam Medis</td>
                <td>Operator</td>
                <td>Diagnosa</td>
                <td>Tindakan</td>
                <td>Alat Operasi</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <tr>
                <td><?= $baris->nama ?></td>
                <td><?= $baris->rencanaOperasi ?></td>
                <td><?= $baris->kodeRekamMedis ?></td>
                <td><?= $baris->operator ?></td>
                <td>
                    <?php if ($baris->diagnosa != '-'): ?>
                        <?php foreach ($baris->diagnosa as $diagnosa): ?>
                            - <?= $diagnosa->diagnosaTindakan ?><br/>
                        <?php endforeach ?>
                    <?php else: ?>
                         -
                    <?php endif ?>
                </td>
                <td>
                    <?php if ($baris->tindakan != '-'): ?>
                        <?php foreach ($baris->tindakan as $tindakan): ?>
                            - <?= $tindakan->diagnosaTindakan ?><br/>
                        <?php endforeach ?>
                    <?php else: ?>
                        -
                    <?php endif ?>
                </td>
                <td>
                    <?php if ($baris->alat != '-'): ?>
                        <?php foreach ($baris->alat as $alat): ?>
                            - <?= $alat->nama . " " . $alat->jumlah . " " . $alat->satuan ?><br/>
                        <?php endforeach ?>
                    <?php else: ?>
                        -
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <?php if ($idx + 1 == $jumlahHalaman): ?>
        <p>Tanggal cetak : <?= $nowValUser ?></p>
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
