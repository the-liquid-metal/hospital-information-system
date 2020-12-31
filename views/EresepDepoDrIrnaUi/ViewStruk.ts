<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepoDrIrnaUi;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepodrirna/struk-baru.php the original file
 */
final class ViewStruk
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string $registerId,
        string $dataUrl, // TODO: php: uncategorized: convert to js, widget, and add this link.
        string $verifikasiUrl,
        string $transferUrl,
        string $formWidgetId,
        string $printWidgetId,
        string $tableWidgetId,
        string $printEtiketWidgetId,
        string $tableResepWidgetId,
        string $printStrukWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        $pageId = "";
        $kodeRacik = null;
        $pasien = new GenericData([]);
        $namaInstalasi = null;
        $namaDepo = "";
        $daftarObat1 = null;
        $daftarObat2 = null;
        $total = null;
        $penjualan = new GenericData([]);
        $grandTotal = null;
        $kodePenjualan = null;

        ?>


<style>
#<?= $pageId ?> td {
    vertical-align: top;
    font-size: 14px;
    font-family: arial, Helvetica, sans-serif;
}

#<?= $pageId ?> .btn-warning {
    background: orange;
    color: black;
    border: thin solid black;
    border-radius: 2px;
}

#<?= $pageId ?> .btn-warning:hover {
    background: #f7cd09;
    color: white;
}

#<?= $pageId ?> .btn-success {
    background: #37ef10;
    color: black;
    border: thin solid black;
    border-radius: 2px;
}

#<?= $pageId ?> .btn-success:hover {
    background: #1aff00;
    color: white;
}
</style>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    _structure = {};

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        for (let i = 0, count = divElm.querySelectorAll("thead td, thead th").length; i <= count; i++) {
            const j = i + 1;
            const label = divElm.querySelector(`thead td:nth-child(${j}), thead th:nth-child(${j})`).innerHTML;
            divElm.querySelectorAll(`tr td:nth-child(${j})`).forEach(item => item.dataset.label = label);
        }

        divElm.querySelector("a[href=#delete-confirm]").addEventListener("click", (event) => {
            const url = event.target.dataset.url;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>)) return;

            $.post({url});
        });

        divElm.querySelector(".verifikasi").addEventListener("click", (event) => {
            const verifikasiBtn = /** @type {HTMLButtonElement} */ event.target;
            const id = verifikasiBtn.dataset.no;
            $.post({
                url: "<?= $verifikasiUrl ?>",
                data: {q: "", id},
                success() {
                    alert(str._<?= $h("Berhasil verifikasi. Stok telah dikurangi.") ?>);
                    verifikasiBtn.classList.remove("btn-success");
                    verifikasiBtn.classList.add("btn-warning");
                }
            });
        });

        divElm.querySelector(".transfer").addEventListener("click", (event) => {
            const transferBtn = /** @type {HTMLButtonElement} */ event.target;
            const id = transferBtn.dataset.no;
            $.post({
                url: "<?= $transferUrl ?>",
                data: {q: "", id},
                success() {
                    alert(str._<?= $h("Berhasil Transfer.") ?>);
                    transferBtn.classList.remove("btn-success");
                    transferBtn.classList.add("btn-warning");
                }
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        tlm.app.registerWidget(this.constructor.widgetName, this);
    }

    show() {
        // TODO: js: uncategorized: implement this method (copy from spl.InputWidget)
    }
});
</script>

<!-- TODO: html: convert to js -->
<table>
    <tr>
        <td>Pasien</td>
        <td>:</td>
        <td> <?= $pasien->namaPasien ?>(<?= $pasien->kelamin ?>)</td>
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
        <td>Cara Bayar</td>
        <td>:</td>
        <td> <?= $pasien->pembayaran ?></td>
    </tr>
    <tr>
        <td>Operator</td>
        <td>:</td>
        <td> <?= $namaDepo ?></td>
    </tr>
</table>

<table>
    <tr>
        <td>Resep</td>
        <td>:</td>
        <td> <?= $pasien->noResep ?></td>
    </tr>
    <tr>
        <td>Dari Tanggal</td>
        <td>:</td>
        <td> <?= $toUserDate($pasien->tanggalAwalResep) ?></td>
    </tr>
    <tr>
        <td>Sampai Tanggal</td>
        <td>:</td>
        <td> <?= $toUserDate($pasien->tanggalAkhirResep) ?></td>
    </tr>
    <tr>
        <td>Kode Rekam Medis</td>
        <td>:</td>
        <td> <?= $pasien->kodeRekamMedis ?></td>
    </tr>
    <tr>
        <td>No. Daftar</td>
        <td>:</td>
        <td> <?= $pasien->noDaftar2 ?></td>
    </tr>
    <tr>
        <td>Iter</td>
        <td>:</td>
        <td> <?= $pasien->iter ?></td>
    </tr>
</table>

<hr/>

<table>
    <tr>
        <td>NO.</td>
        <td>NAMA BARANG</td>
        <td>JUMLAH</td>
        <td>HARGA</td>
    </tr>
    <?php foreach ($daftarObat1 as $j => $obat1): ?>
        <?php $obat2 = $daftarObat2[$j] ?>
        <?php if ($obat1->kodeRacik): ?>
            <?php if ($kodeRacik != $obat1->kodeRacik): ?>
                <tr>
                    <td><?= $obat2->noObat ?></td>
                    <td><?= $obat1->namaRacik ?></td>
                    <td><?= $obat1->noRacik ?></td>
                    <td></td>
                </tr>
                <?php $kodeRacik = $obat1->kodeRacik ?>
            <?php endif ?>

            <tr>
                <td></td>
                <td><?= $obat2->noRacik ?> <?= $obat1->namaBarang ?></td>
                <td>( <?= $obat1->jumlahPenjualan ?> / <?= $obat1->keteranganJumlah ?> )</td>
                <td class="text-right">Rp. <?= $toUserInt($obat1->jumlahPenjualan * $obat1->hjaSetting) ?></td>
            </tr>

        <?php else: ?>
            <tr>
                <td><?= $obat2->noObat ?></td>
                <td><?= $obat1->namaBarang ?></td>
                <td><?= $obat1->jumlahPenjualan ?></td>
                <td class="text-right">Rp. <?= $toUserInt($obat1->jumlahPenjualan * $obat1->hjaSetting) ?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
    <tr>
        <td style="width:100%" colspan="4">
            <hr style="border-top:1px dashed black"/>
        </td>
    </tr>
    <tr>
        <td colspan="2">Subtotal</td>
        <td>:</td>
        <td class="text-right">Rp. <?= $toUserInt($total) ?></td>
    </tr>
    <tr>
        <td colspan="2">Diskon</td>
        <td>:</td>
        <td class="text-right">Rp. <?= $toUserInt($penjualan->totalDiskon) ?></td>
    </tr>
    <tr>
        <td colspan="2">Pembungkus</td>
        <td>:</td>
        <td class="text-right">Rp. <?= $toUserInt($pasien->totalPembungkus) ?></td>
    </tr>
    <tr>
        <td colspan="2">Jasa Pelayanan</td>
        <td>:</td>
        <td class="text-right">Rp. <?= $toUserInt($penjualan->totalJp) ?></td>
    </tr>
    <tr>
        <td colspan="2">Total</td>
        <td>:</td>
        <td class="text-right">Rp. <?= $toUserInt($grandTotal) ?></td>
    </tr>
</table>

Keterangan : <?= $pasien->keterangan ?>

<div>
    <a href="<?= $formWidgetId ."/".$pasien->noResep ?>">Edit</a>
    <a href="<?= $printWidgetId ."/".$kodePenjualan ?>">Print</a>
    <a href="<?= $tableWidgetId ?>">Selesai</a>
    <a href="<?= $printEtiketWidgetId ."/".$pasien->noResep ?>">Etiket</a>

    <?php if (str_contains($namaDepo, "Rawat Jalan")): ?>
        <?php if ($pasien->transfer || $pasien->jenisResep == "Pembelian Bebas" || $pasien->jenisResep == "Pembelian Langsung"): ?>
            <button class="transfer btn-warning" data-no="<?= $pasien->noResep ?>" disabled>Transfer</button>
        <?php else: ?>
            <button class="transfer btn-success" data-no="<?= $pasien->noResep ?>">Transfer</button>
        <?php endif ?>

        <?php if ($pasien->verifikasi): ?>
            <button class="verifikasi btn-warning" data-no="<?= $pasien->noResep ?>" disabled>Verifikasi</button>
        <?php else: ?>
            <button class="verifikasi btn-success" data-no="<?= $pasien->noResep ?>">Verifikasi</button>
        <?php endif ?>

    <?php else: ?>
        <?php if ($pasien->transfer || $pasien->jenisResep == "Pembelian Bebas" || $pasien->jenisResep == "Pembelian Langsung"): ?>
            <button class="transfer btn-warning" data-no="<?= $pasien->noResep ?>" disabled>Transfer</button>
        <?php else: ?>
            <button class="transfer btn-success" data-no="<?= $pasien->noResep ?>">Transfer</button>
        <?php endif ?>

        <?php if ($pasien->verifikasi): ?>
            <button class="verifikasi btn-warning" data-no="<?= $pasien->noResep ?>" disabled>Verifikasi</button>
        <?php else: ?>
            <button class="verifikasi btn-success" data-no="<?= $pasien->noResep ?>">Verifikasi</button>
        <?php endif ?>

        <a href="<?= $tableResepWidgetId ."/".$pasien->kodeRekamMedis ?>">Gabung</a>
    <?php endif ?>

    <a href="<?= $printStrukWidgetId ."/".$kodePenjualan ?>">Print-LQ</a>
</div>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
