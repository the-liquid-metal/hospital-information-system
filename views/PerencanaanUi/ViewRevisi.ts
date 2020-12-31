<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PerencanaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/views_revisi.php the original file
 */
final class ViewRevisi
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl)
    {
        $fieldRev = [
            "id_pbf",
            "id_jenisanggaran",
            "thn_anggaran",
            "id_sumberdana",
            "id_subsumberdana",
            "id_jenisharga",
            "id_carabayar",
            "ppn"
        ];
        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        $perencanaan = new \stdClass;
        $daftarDetailPerencanaan = [];
        $awal = $perencanaan->blnawal_anggaran;
        $akhir = $perencanaan->blnakhir_anggaran;
        $tahun = $perencanaan->thn_anggaran;
        ob_clean();
        ob_start();
        $directory = "";
        $name = "";
        $no = 1;
        $cip = '';
        $ckm = '';
        $cis = '';
        $chi = '';
        $chk = '';
        $cdi = '';
        ?>


<table id="tb-input" class="table well">
    <tr>
        <td>No. Transaksi</td>
        <td><?= $perencanaan->kode ?></td>
        <td></td>
        <td>Nama Pemasok</td>
        <td><?= $perencanaan->nama_pbf ?></td>
    </tr>
    <tr>
        <td>No. Dokumen / Tanggal</td>
        <td><?= $perencanaan->no_doc ?> / <?= ($perencanaan->tgl_doc != "-") ? date("d-m-Y", strtotime($perencanaan->tgl_doc)) : "" ?></td>
        <td></td>
        <td>Mata Anggaran</td>
        <td><?= $perencanaan->subjenis_anggaran ?></td>
    </tr>
    <tr>
        <td>Anggaran</td>
        <td class="anggaran"><?= $numToMonthName($awal) . ($awal == $akhir ? "" : " s.d. ".$numToMonthName($akhir)) ." ".$tahun ?></td>
        <td></td>
        <td>Sumber Dana</td>
        <td><?= $perencanaan->sumber_dana ?> (Sub: <?= $perencanaan->subsumber_dana ?>)</td>
    </tr>
    <tr>
        <td>Nomor PL</td>
        <td><?= $perencanaan->no_spk ?></td>
        <td></td>
        <td>Jenis Harga</td>
        <td><?= $perencanaan->jenis_harga ?> (Cara Bayar: <?= $perencanaan->cara_bayar ?>)</td>
    </tr>
    <tr>
        <td>Keterangan Revisi</td>
        <td><?= $perencanaan->keterangan ?></td>
        <td></td>
        <td>PPN</td>
        <td class="ppn"><?= $perencanaan->ppn ?> %</td>
    </tr>
</table>

<a href="<?= site_url($directory . $name . '/addrevisi/' . $perencanaan->kode) ?>" class="btn btn-warning" title="Lakukan Revisi"><i class="fa fa-paste"></i> Revisi</a>
<a class="btn btn-default" href="<?= site_url($directory . $name) ?>"><i class="fa fa-undo"></i> Kembali</a>

<table class="table table-bordered table-hover display" id="tb-add">
    <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">KODE</th>
            <th rowspan="2">NAMA BARANG</th>
            <th rowspan="2">PABRIK</th>
            <th rowspan="2">KEMASAN</th>
            <th rowspan="2">ISI</th>
            <th colspan="2">JUMLAH</th>
            <th colspan="3">HARGA</th>
            <th colspan="2">DISKON</th>
            <th rowspan="2">TOTAL</th>
        </tr>
        <tr>
            <th>KEMASAN</th>
            <th>SATUAN</th>
            <th>KEMASAN</th>
            <th>SATUAN</th>
            <th>TOTAL</th>
            <th>%</th>
            <th>Rp.</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($daftarDetailPerencanaan as $r):
        if ($r->isi_kemasan - floor($r->isi_kemasan) == 0) { // klo integer
            $isiKemasanClass = 'num';
            $jumlahIsiClass = 'num';
        } else {
            $isiKemasanClass = 'curr';
            $jumlahIsiClass = 'dnum';
        }

        if ($r->id_pabrik != $r->id_pabrikpl) $cip = 'danger';
        if ($r->kemasan != $r->kemasanpl || $r->id_kemasan != $r->id_kemasanpl || $r->id_kemasandepo != $r->id_kemasandepopl) $ckm = 'danger';
        if ($r->isi_kemasan != $r->isi_kemasanpl) $cis = 'danger';
        if ($r->harga_item != $r->harga_itempl) $chi = 'danger';
        if ($r->harga_kemasan != $r->harga_kemasanpl) $chk = 'danger';
        if ($r->diskon_item != $r->diskon_itempl) $cdi = 'danger';

        $hargaTotal = $r->jumlah_kemasan * $r->harga_kemasan;
        $diskonHarga = $hargaTotal * $r->diskon_item / 100;
        ?>
        <tr class="tr-data">
            <td><?= $no++ ?></td>
            <td><?= $r->id_katalog ?></td>
            <td><?= $r->nama_sediaan ?></td>
            <td class="<?= $cip ?>"><?= $r->nama_pabrik ?></td>
            <td class="<?= $ckm ?>"><?= $r->kemasan ?></td>
            <td class="<?= $isiKemasanClass ?> is <?= $cis ?>"><?= $r->isi_kemasan ?></td>
            <td class="num jk"><?= $r->jumlah_kemasan ?></td>
            <td class="<?= $jumlahIsiClass ?> ji"><?= $r->jumlah_item ?></td>
            <td class="curr hk <?= $chk ?>"><?= $r->harga_kemasan ?></td>
            <td class="curr hi <?= $chi ?>"><?= $r->harga_item ?></td>
            <td class="curr ht"><?= $hargaTotal ?></td>
            <td class="disc di <?= $cdi ?>"><?= $r->diskon_item ?></td>
            <td class="curr dh"><?= $diskonHarga ?></td>
            <td class="curr ha"><?= $hargaTotal - $diskonHarga ?></td>
        </tr>
    <?php endforeach ?>

    <tr class="tr-last tr-static">
        <td colspan="12">Total Sebelum Diskon</td>
        <td colspan="2" class="tdinput">
            <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input type="text" class="form-control input-sm curr th" name="nilai_total" value="0" readonly>
            </div>
        </td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total Diskon</td>
        <td colspan="2" class="tdinput">
            <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input type="text" class="form-control input-sm curr dt" name="nilai_diskon" value="0" readonly>
            </div>
        </td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total Setelah Diskon</td>
        <td colspan="2" class="tdinput">
            <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input type="text" class="form-control input-sm curr td" id="total_stlhdiskon" value="0" readonly>
            </div>
        </td>
    </tr>
    <tr class="tr-static ppn">
        <td colspan="12">Total PPN 10%</td>
        <td colspan="2" class="tdinput">
            <div class="input-group">
                <span class="input-group-addon">
                    <input type="checkbox" class="ppn" name="ppn" value="<?= $perencanaan->ppn ?>" <?= $perencanaan->ppn == 10 ? "checked" : "" ?> disabled>&nbsp;
                </span>
                <input type="text" class="form-control input-sm curr tp" name="nilai_ppn" value="0" readonly>
            </div>
        </td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total Setelah PPN</td>
        <td colspan="2" class="tdinput">
            <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input type="text" class="form-control input-sm curr tsp" id="subtotal" value="0" readonly>
            </div>
        </td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Pembulatan</td>
        <td colspan="2" class="tdinput">
            <div class="input-group">
                <span class="input-group-addon"><input type="checkbox" class="pembulatan" checked disabled></span>
                <input type="text" class="form-control input-sm mcurr pb" name="nilai_pembulatan" value="0" readonly>
            </div>
        </td>
    </tr>
    <tr class="tr-static">
        <td colspan="12">Total Setelah Pembulatan</td>
        <td colspan="2" class="tdinput">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i>&nbsp;</span>
                <input type="text" class="form-control input-sm curr ta" name="nilai_akhir" value="0" readonly>
            </div>
        </td>
    </tr>
    </tbody>
</table>

<script src="<?= base_url("assets/js/autoNumeric.js") ?>"></script>
<script src="<?= base_url("assets/js/pengadaan/view.js") ?>"></script>

<script>
$(() => {
<?php foreach($fieldRev as $fr): ?>
    <?php if ($perencanaan[$fr] != $perencanaan[$fr . "pl"]): ?>
        $(".<?= $fr ?>").addClass("text-danger");
        if ("<?= $fr?>" == "thn_anggaran") $('.anggaran').addClass("text-danger");
    <?php endif ?>
<?php endforeach ?>
});
</script>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
