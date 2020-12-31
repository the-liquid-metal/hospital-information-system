<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepo;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepo/antrian2.php the original file
 */
final class Antrian2
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(string $noAntrian, GenericData $pasien, iterable $daftarObat, iterable $daftarRacik)
    {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        ob_clean();
        ob_start();
        $kodeRacik = "";
        $obat = null;
        $racik = null;
        ?>

<img width="60px" height="30px" alt="logo" src="/assets/img/logo.png"/>
<h3>PT Affordable App</h3>
<b>No. Antrian Depo:</b> <?= $noAntrian ?>

<table>
    <tr>
        <td>No. Resep</td>
        <td>:</td>
        <td> <?= $pasien->noResep ?></td>
    </tr>
    <tr>
        <td>Kode Rekam Medis</td>
        <td>:</td>
        <td> <?= $pasien->kodeRekamMedis ?></td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>:</td>
        <td> <?= $pasien->namaPasien ?> (<?= $pasien->kelamin ?>)</td>
    </tr>
    <tr>
        <td>No. Daftar</td>
        <td>:</td>
        <td> <?= $pasien->noDaftar ?></td>
    </tr>
    <tr>
        <td>Dokter</td>
        <td>:</td>
        <td> <?= $pasien->namaDokter ?></td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td> <?= $toUserDate($pasien->tanggalResep1) ?></td>
    </tr>
    <tr>
        <td>Iter</td>
        <td>:</td>
        <td> <?= $pasien->iter ?></td>
    </tr>
    <tr>
        <td>Keterangan</td>
        <td>:</td>
        <td> <?= $pasien->keterangan ?></td>
    </tr>
</table>

<table>
<?php foreach ($daftarObat as $obat): ?>
    <tr>
        <td>R/</td>
        <td><?= $obat->namaBarang ?> No. <?= $obat->jumlahPenjualan ?> </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>S </td>
        <td><?= $obat->signa ?></td>
    </tr>
    <tr>
        <td></td>
        <td><?= $obat->keteranganObat ?></td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
<?php endforeach ?>

<?php foreach ($daftarRacik as $racik): ?>
    <?php if ($kodeRacik != $racik->kodeRacik): ?>
        <?php if ($kodeRacik): ?>
            <tr>
                <td>S</td>
                <td>(<?= $racik->signa ?>)</td>
            </tr>
            <tr>
                <td></td>
                <td><?= $obat->keteranganObat ?></td>
            </tr>
        <?php endif ?>
        <tr>
            <td>R/</td>
            <td><?= $racik->namaRacik ?> No. <?= $racik->noRacik ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    <?php endif ?>

    <tr>
        <td></td>
        <td> <?= $racik->namaBarang ?> (<?= $racik->keteranganJumlah ?>)</td>
    </tr>
    <?php $kodeRacik = $racik->kodeRacik ?>
<?php endforeach ?>

<?php if ($racik->signa): ?>
    <tr>
        <td>S</td>
        <td>(<?= $racik->signa ?>)</td>
    </tr>
    <tr>
        <td></td>
        <td><?= $obat->keteranganObat ?></td>
    </tr>
<?php endif ?>
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
