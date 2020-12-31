<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Eresep;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresep/antrian2.php the original file
 */
final class Antrian2
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        int         $noAntrian,
        GenericData $pasien,
        iterable    $daftarObat1,
        iterable    $daftarObat2
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        ob_clean();
        ob_start();
        $signa = "";
        $kodeRacik = "";
        ?>


<img alt="PT Affordable App" width="60px" height="30px" src="<?= Yii::$app->basePath ?>/assets/img/logo.png"/>
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
        <td> <?= $pasien->dokter ?></td>
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
    <?php foreach ($daftarObat1 as $obat1): ?>
        <tr>
            <td colspan="2">R/<?= $obat1->jumlahPenjualan ?></td>
            <td>
                <?= $obat1->namaBarang ?>
                <?php if ($obat1->signa1): ?>
                    <br/>(<?= $obat1->signa1 . " " . $obat1->signa2 . " " . $obat1->signa3 ?>)
                <?php endif ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Ket :</td>
            <td><?= $obat1->keteranganObat ?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
    <?php endforeach ?>

    <?php foreach ($daftarObat2 as $obat2): ?>
        <?php if ($kodeRacik != $obat2->kodeRacik): ?>
            <?php if ($kodeRacik): ?>
                <tr>
                    <td>S</td>
                    <td>(<?= $signa ?>)</td>
                </tr>
                <tr>
                    <td></td>
                    <td><?= $obat2->keteranganObat ?></td>
                </tr>
            <?php endif ?>
            <tr>
                <td>R/</td>
                <td><?= $obat2->namaRacik ?> No. <?= $obat2->noRacik ?></td>
                <td></td>
            </tr>
        <?php endif ?>

        <tr>
            <td></td>
            <td> <?= $obat2->namaBarang ?> (<?= $obat2->keteranganJumlah ?>)</td>
        </tr>

        <?php
        $kodeRacik = $obat2->kodeRacik;
        $signa = $obat2->signa;
        ?>
    <?php endforeach ?>

    <?php if ($obat2->signa ?? ""): ?>
        <tr>
            <td>S</td>
            <td>(<?= $obat2->signa ?? "" ?>)</td>
        </tr>
        <tr>
            <td></td>
            <td><?= $obat1->keteranganObat ?? "" ?></td>
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
