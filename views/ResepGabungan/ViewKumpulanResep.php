<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ResepGabungan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/resepgabungan/view_kumpulanresep.php the original file
 */
final class ViewKumpulanResep
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        GenericData $pasien,
        string      $namaInstalasi,
        string      $namaDepo,
        string      $tanggalAwal,
        string      $tanggalAkhir,
        iterable    $daftarObat,
        iterable    $daftarPenjualanPerResep
    ) {
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        ob_clean();
        ob_start();
        $kodeRacik = "";
        $noObat = 1;
        $noRacik = 1;
        ?>


<table class="judul1Tbl">
    <tr>
        <td>Pasien</td>
        <td>:</td>
        <td> <?= $pasien->namaPasien ?> (<?= $pasien->kelamin ?>)</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>:</td>
        <td> <?= $pasien->alamatJalan ?></td>
    </tr>
    <tr>
        <td>Ruang</td>
        <td>:</td>
        <td><?= $namaInstalasi ? $namaInstalasi . ", " : "" ?> <?= $pasien->namaKamar ?></td>
    </tr>
    <tr>
        <td>Operator</td>
        <td>:</td>
        <td> <?= $namaDepo ?></td>
    </tr>
</table>

<table class="judul2Tbl">
    <tr>
        <td>Dari Tanggal</td>
        <td>:</td>
        <td> <?= $tanggalAwal ?></td>
    </tr>
    <tr>
        <td>Sampai Tanggal</td>
        <td>:</td>
        <td> <?= $tanggalAkhir ?></td>
    </tr>
    <tr>
        <td>Kode Rekam Medis</td>
        <td>:</td>
        <td> <?= $pasien->kodeRekamMedis ?></td>
    </tr>
    <tr>
        <td>No. Pendaftaran</td>
        <td>:</td>
        <td> <?= $pasien->noDaftar ?></td>
    </tr>
</table>

<hr/>

<table class="detailTbl">
    <tr>
        <td>NO.</td>
        <td>NAMA BARANG</td>
        <td>JUMLAH</td>
        <td>HARGA</td>
    </tr>
    <?php foreach ($daftarObat as $obat): ?>
        <?php if ($obat->kodeRacik): ?>
            <?php if ($kodeRacik != $obat->kodeRacik): ?>
                <tr>
                    <td><?= $noObat ?></td>
                    <td><?= $obat->namaRacik ?></td>
                    <td><?= $obat->noRacik ?></td>
                    <td></td>
                </tr>
                <?php
                $noRacik = 1;
                $kodeRacik = $obat->kodeRacik;
                $noObat++;
                ?>
            <?php endif ?>
            <tr>
                <td></td>
                <td><?= $noRacik, " ", $obat->namaBarang ?></td>
                <td>(<?= $obat->totalJumlah, " / ", $obat->keteranganJumlah ?>)</td>
                <td>Rp.</td>
                <td><?= $toUserInt($obat->totalJumlah * $obat->harga) ?></td>
                <td></td>
            </tr>
            <?php $noRacik++ ?>

        <?php else: ?>
            <tr>
                <td><?= $noObat ?></td>
                <td><?= $obat->namaBarang ?></td>
                <td><?= $obat->totalJumlah ?></td>
                <td>Rp.</td>
                <td><?= $toUserInt($obat->totalJumlah * $obat->harga) ?></td>
                <td></td>
            </tr>
            <?php $noObat++ ?>
        <?php endif ?>
    <?php endforeach ?>

    <?php foreach ($daftarPenjualanPerResep as $resep): ?>
        <tr>
            <td colspan="2">
                <?= $resep->noResep ?>
                [<?= $resep->transfer ?> | <?= $toUserDate($resep->tanggalTransfer) ?>]
            </td>
            <td>:</td>
            <td>Rp.</td>
            <td><?= $toUserInt($resep->total) ?></td>
            <td style="width:10%"></td>
        </tr>
    <?php endforeach ?>
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
