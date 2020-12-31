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
 * @see http://localhost/ori-source/fatma-pharmacy/views/resepgabungan/cetakobat.php the original file
 */
final class CetakObat
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(iterable $daftarHalaman, GenericData $pasien, string $namaDepo, string $instalasi)
    {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $preferInt = Yii::$app->number->preferInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        ?>


<?php foreach ($daftarHalaman as $halaman): ?>
<div class="page">
    <div class="judul">INSTALASI FARMASI</div>

    <table class="info_pasien">
        <tr>
            <td>Pasien</td>
            <td>:</td>
            <td><?= $pasien->namaPasien ?> (<?= $pasien->kelamin ?>)</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?= $pasien->alamatJalan ?></td>
        </tr>
        <tr>
            <td>Ruang</td>
            <td>:</td>
            <td><?= ($instalasi ? $instalasi . ", " : "") . $pasien->namaKamar ?></td>
        </tr>
        <tr>
            <td>Cara Bayar</td>
            <td>:</td>
            <td><?= $pasien->pembayaran ?></td>
        </tr>
        <tr>
            <td>Operator</td>
            <td>:</td>
            <td><?= $namaDepo ?></td>
        </tr>
    </table>

    <table class="info_pasien">
        <tr>
            <td>Resep</td>
            <td>:</td>
            <td class="resep"><?= $pasien->noResep ?></td>
        </tr>
        <tr>
            <td>Dari Tanggal</td>
            <td>:</td>
            <td><?= $toUserDate($pasien->tanggalAwalResep) ?></td>
        </tr>
        <tr>
            <td>Sampai Tanggal</td>
            <td>:</td>
            <td><?= $toUserDate($pasien->tanggalAkhirResep) ?></td>
        </tr>
        <tr>
            <td>Kode Rekam Medis</td>
            <td>:</td>
            <td><?= $pasien->kodeRekamMedis ?></td>
        </tr>
        <tr>
            <td>No. Daftar</td>
            <td>:</td>
            <td><?= $pasien->noDaftar ?></td>
        </tr>
        <tr>
            <td>Iter</td>
            <td>:</td>
            <td><?= $pasien->iter ?></td>
        </tr>
    </table>

    <table class="daftar_obat">
        <thead>
            <tr>
                <td>NO.</td>
                <td>NAMA BARANG</td>
                <td>JUMLAH</td>
                <td colspan="2">HARGA</td>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($halaman as $baris): ?>
            <?php if (isset($baris->ruang)): ?>
                <tr>
                    <td colspan="5"><?= $baris->ruang ?></td>
                </tr>

            <?php else: ?>
                <tr>
                    <td><?= $baris->no ?></td>
                    <td><?= $baris->namaBarang ?></td>
                    <td class="text-right"><?= $preferInt($baris->jumlah) ?></td>
                    <td>Rp</td>
                    <td class="text-right"><?= $toUserFloat($baris->subtotalHarga) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
        </tbody>
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
