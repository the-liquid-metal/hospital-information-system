<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Produksi;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/produksi/struk-baru.php the original file
 */
final class StrukBaru
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(GenericData $hasil, iterable $daftarKomposisi)
    {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        ob_clean();
        ob_start();
        $total = 0;
        ?>


<table>
    <tr>
        <td>Nama Produksi</td>
        <td>:</td>
        <td><?= $hasil->namaBarang ?></td>
    </tr>

    <tr>
        <td>Hasil Produksi</td>
        <td>:</td>
        <td><?= $hasil->jumlahMasuk ?></td>
    </tr>

    <tr>
        <td>Tanggal Tersedia</td>
        <td>:</td>
        <td><?= $toUserDate($hasil->tanggalTersedia) ?></td>
    </tr>
    <tr>
        <td>Harga Satuan</td>
        <td>:</td>
        <td><?= $total/$hasil->jumlahMasuk ?></td>
    </tr>
</table>

<hr/>

<table>
    <tr>
        <td>NO.</td>
        <td>NAMA BARANG</td>
        <td>JUMLAH</td>
        <td style="width:30%">HARGA</td>
        <td style="width:30%">Subtotal</td>
    </tr>
    <?php $total = 0 ?>
    <?php foreach ($daftarKomposisi as $i => $komposisi): ?>
        <?php $subTotal = $komposisi->hargaJual * $komposisi->jumlahKeluar ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= $komposisi->namaBarang ?></td>
            <td><?= $komposisi->jumlahKeluar ?></td>
            <td><?= $komposisi->hargaJual ?></td>
            <td><?= $subTotal ?></td>
        </tr>
        <?php $total += $subTotal ?>
    <?php endforeach ?>
    <tr>
        <td colspan="5"><hr /></td>
    </tr>
    <tr>
        <td class="text-right" colspan="4">Total</td>
        <td><?= $total ?></td>
    </tr>
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
