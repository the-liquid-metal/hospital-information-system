<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepUi;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresep/antrian.php the original file
 */
final class ViewAntrian
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string $dataUrl, // TODO: php: uncategorized: ...
        string $eresepFormUrl,
        string $eresepAntrian2Url,
        string $eresepTableUrl
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        ob_clean();
        ob_start();
        $kodeRacik = "";
        $antrian = null;
        $pasien = new GenericData([]);
        $daftarObat1 = [];
        $daftarObat2 = [];
        $kodePenjualan = "";
        ?>


<img alt="PT Affordable App" width="60px" height="30px" src="<?= Yii::$app->basePath ?>/assets/img/logo.png"/>
<h3>PT Affordable App</h3>
<b>No. Antrian Depo:</b> <?= $antrian ?>

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
            <?= $obat1->signa1 ? "<br/>({$obat1->signa1} {$obat1->signa2} {$obat1->signa3})" : "" ?>
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
        <tr>
            <td>R/</td>
            <td> <?= $obat2->noRacik ?> </td>
            <td>
                <?= $obat2->namaRacik ?>
                <?= $obat2->signa1 ? "({$obat2->signa1} {$obat2->signa2} {$obat2->signa3})" : "" ?>
            </td>
        </tr>
        <tr>
            <td>Ket :</td>
            <td><?= $obat2->keteranganObat ?></td>
        </tr>
        <?php endif ?>

        <tr>
            <td></td>
            <td></td>
            <td><?= $obat2->namaBarang ?> (<?= $obat2->keteranganJumlah ?>)</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <?php $kodeRacik = $obat2->kodeRacik ?>
    <?php endforeach ?>
</table>

<a href="<?= $eresepFormUrl ."/".$kodePenjualan ?>">Edit</a>
<a href="<?= $eresepAntrian2Url ."/".$kodePenjualan ?>">Print All</a>
<a href="<?= $eresepTableUrl ?>">Selesai</a>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
