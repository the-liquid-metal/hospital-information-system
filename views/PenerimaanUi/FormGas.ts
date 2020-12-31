<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PenerimaanUi;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/addgas.php the original file
 */
final class FormGas
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        array  $verifikasiTerimaAccess,
        array  $verifikasiGudangAccess,
        array  $verifikasiAkuntansiAccess,
        string $editDataUrl,
        string $verifikasiTerimaDataUrl,
        string $verifikasiGudangDataUrl,
        string $verifikasiAkuntansiDataUrl, // hypothesis (not exist in actionVerAkuntansiData)
        string $addActionUrl,
        string $editActionUrl,
        string $verifikasiTerimaActionUrl,
        string $verifikasiGudangActionUrl,
        string $verifikasiAkuntansiActionUrl, // hypothesis (not exist in actionVerAkuntansiData)
        string $jenisAnggaranSelect,
        string $subjenisAnggaranSelect,
        string $sumberDanaSelect,
        string $jenisHargaSelect,
        string $caraBayarSelect,
    ) {
        ob_clean();
        ob_start();
        $preferInt = Yii::$app->number->preferInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserTime = Yii::$app->dateTime->transformFunc("toUserTime");
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();

        $directory = "";
        $name = "";
        $jenisAnggaranSelect = [$jenisAnggaranSelect, new \stdClass]; // dummy
        $subjenisAnggaranSelect = [$subjenisAnggaranSelect, new \stdClass]; // dummy
        $sumberDanaSelect = [$sumberDanaSelect, new \stdClass]; // dummy
        $jenisHargaSelect = [$jenisHargaSelect, new \stdClass]; // dummy
        $caraBayarSelect = [$caraBayarSelect, new \stdClass]; // dummy

        // from controller
        $data = [
            "sts_tabunggm" => 1,
            "sideactive" => "addgas",
            "heading_title" => "Tambah Penerimaan Tabung Gas Medis",
        ];

        $noSuratJalan = "disabled";
        $noFaktur = "disabled";
        $tanggalDokumen = "disabled";
        $checkboxStatusTabungGasMedis = "";
        $statusTabungGasMedis = "disabled";
        $kodeRefPl = "disabled";
        $kodeRefDo = "disabled";
        $anggaran = "disabled";
        $tarikByBtn = "disabled";
        $saveBtn = "disabled";
        $printBtn = "disabled";
        $backBtn = "";
        $deleteBtn = "disabled";
        $item = "disabled";
        $noDokumen = "disabled";
        $mataAnggaran = "disabled";
        $sumberDana = "disabled";
        $jenisHarga = "disabled";
        $caraBayar = "disabled";
        $ppn = "disabled";
        $cekAll = "disabled";
        $idPemasok = "disabled";
        $tanggalJatuhTempo = "disabled";
        $tarikBtn = "disabled";

        // from actionEditData
        $headingTitle = "";
        $data = new \stdClass;
        $idata = new \stdClass;
        $user = new \stdClass;
        $action = "";

        if ($action == "edit") {
            $noSuratJalan = "";
            $noFaktur = "";
            $cekAll = "";
            $item = "";
            $saveBtn = "";
            $printBtn = "";
            $deleteBtn = "";
            $noDokumen = "";
            $tanggalDokumen = "";
            $anggaran = "";
            $tarikBtn = "";

        } elseif ($action == "ver_gudang") {
            $action = "ver_gudang";
            $saveBtn = "";
            $printBtn = "";

        } elseif ($action == "ver_akuntansi") {
            $action = "ver_akuntansi";
            $saveBtn = "";
            $printBtn = "";

        } elseif ($action == "addbonus") {
            $noSuratJalan = "";
            $noFaktur = "";
            $anggaran = "";
            $kodeRefDo = "";
            $tanggalDokumen = "";
            $tarikByBtn = "";
            $tarikBtn = "";
            $deleteBtn = "";
            $cekAll = "";
            $action = "add";
            $noDokumen = "";

        } else {
            $noSuratJalan = "";
            $noFaktur = "";
            $anggaran = "";
            $kodeRefDo = "";
            $tanggalDokumen = "";
            $tarikByBtn = "";
            $statusTabungGasMedis = "";
            $tarikBtn = "";
            $deleteBtn = "";
            $cekAll = "";
            $action = "add";
            $noDokumen = "";
        }

        $no = 1;
        $nt = 0.00;
        $nd = 0.00;
        $nTd = 0.00;
        ?>

<script src="<?= base_url("assets/js/pengadaan/penerimaan.js") ?>"></script>

<form method="POST" name="form-add" class="form-horizontal" role="form">
    <div class="panel-heading">
        <span class="icon"><i class="fa fa-plus-circle fa-2x"></i></span>
        <label class="title"><?= $headingTitle ?></label>

        <input type="hidden" name="usrid_updt" value="<?= $user->name ?>"/>
        <input type="hidden" name="action" value="<?= $action ?>"/>
        <span class="btn-tarik" title="Tarik Item Pembelian" <?= $tarikBtn ?>>TARIK</span>
        <button type="submit" name="submit" value="save" title="Save Perencanaan"<?= $saveBtn ?>><i class="fa fa-save fa-2x"></i>&nbsp;&nbsp;SAVE</button>
        <a href="<?= site_url($directory . $name . '/prints/' . $data->kode) ?>" class="btn-print" title="Print Perencanaan" <?= $printBtn ?>>PRINT</a>
        <span class="btn-delete" title="Hapus Perencanaan"<?= $deleteBtn ?>>HAPUS</span>
        <a href="<?= site_url($directory . $name) ?>" title="Back To Index"<?= $backBtn ?>>KEMBALI</a>
    </div>

    <div class="panel-body">
        <div class="col-lg-9">
            No. Transaksi
            <input class="input-xxlarge" name="kode" value="<?= $data->kode ?>" readonly>
            KE- <input id="terimake" value="<?= $data->terimake ?>" readonly>

            Tanggal Penerimaan
            <input name="tgl_doc" value="<?= $data->tgl_doc ? $toUserDate($data->tgl_doc) : '' ?>" <?= $tanggalDokumen ?> >

            Tempo
            <input id="tgl_jatuhtempo" value="<?= $data->tgl_jatuhtempo ? $toUserDate($data->tgl_jatuhtempo) : '' ?>" <?= $tanggalJatuhTempo ?> >

            No. Penerimaan
            <?php $val = ($data->no_doc) ? 1 : 0 ?>
            <input name="no_doc" data-validation="<?= $val ?>" value="<?= $data->no_doc ?>" required <?= $noDokumen ?> >
            <input type="hidden" id="old_nodoc" value="<?= $val ?>"/>
            <input type="hidden" id="validation" value="<?= $val ?>"/>
            <span class="input-group-addon doc_val">
                <?php if ($data->no_doc):?>
                    <i class="fa fa-check-square-o text-success fa-lg" title="Valid"></i>
                <?php else: ?>
                    <i class="fa fa-warning text-danger fa-lg" title="Not Valid"></i>
                <?php endif ?>
            </span>

            Mata Anggaran
            <input type="hidden" name="id_jenisanggaran" value="<?= $data->id_jenisanggaran ?>">
            <select id="id_jenisanggaran" class="input-xxlarge" <?= $mataAnggaran ?>>
            <?php foreach ($jenisAnggaranSelect as $jenis): ?>
                <optgroup label="<?= $jenis->jenis_anggaran ?>">
                <?php foreach ($subjenisAnggaranSelect as $subjenis): ?>
                    <?php if ($jenis->id == $subjenis->id_jenis): ?>
                        <option value="<?= $subjenis->id ?>" id_jenis="<?= $subjenis->id_jenis ?>" <?= ($subjenis->id == $data->id_jenisanggaran || $subjenis->id == 1) ? "selected" : "" ?>><?= $subjenis->subjenis_anggaran ?></option>
                    <?php endif ?>
                <?php endforeach ?>
                </optgroup>
            <?php endforeach ?>
            </select>

            Faktur / Surat Jalan
            <input name="no_faktur" onKeyUp="checkValue(this, 'no_faktur')" value="<?= $data->no_faktur ?>" <?= $noFaktur ?> >
            <span class="glyphicon form-control-feedback"></span>
            <span>/</span>
            <input name="no_suratjalan" onKeyUp="checkValue(this, 'no_suratjalan')" value="<?= $data->no_suratjalan ?>" <?= $noSuratJalan ?> >
            <span class="glyphicon form-control-feedback"></span>

            Anggaran
            <select name="blnawal_anggaran" class="input-xlarge" <?= $anggaran ?>>
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= ($data->blnawal_anggaran == $i || date("n") == $i) ? "selected" : "" ?>><?= $numToMonthName($i) ?></option>
            <?php endfor ?>
            </select>

            <span>s.d.</span>
            <select name="blnakhir_anggaran" <?= $anggaran ?>>
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= ($data->blnakhir_anggaran == $i || date("n") == $i) ? "selected" : "" ?>><?= $numToMonthName($i) ?></option>
            <?php endfor ?>
            </select>

            <select name="thn_anggaran" class="input-sm form-control input-xxlarge" <?= $anggaran ?> >
            <?php for ($i = date("Y") + 2; $i >= date("Y") - 3; $i--): ?>
                <option value="<?= $i ?>" <?= ($data->thn_anggaran == $i || date("Y") == $i) ? "selected" : "" ?>><?= $i ?></option>
            <?php endfor ?>
            </select>

            No SP/SPK/Kontrak
            <input type="radio" name="tarik_by" value="spk" <?= $tarikByBtn ?> />
            <input id="kode_reffpl" value="" <?= $kodeRefPl ?> >

            Sumber Dana
            <input type="hidden" name="id_sumberdana" value="<?= $data->id_sumberdana ?>">
            <select id="id_sumberdana" <?= $sumberDana ?> >
                <option></option>
                <?php foreach ($sumberDanaSelect as $sumberDana): ?>
                    <option value="<?= $sumberDana->id ?>" <?= $data->id_sumberdana == $sumberDana->id ? "selected" : "" ?> ><?= $sumberDana->sumber_dana ?></option>
                <?php endforeach ?>
            </select>

            Ref. Delivery Order
            <input type="radio" name="tarik_by" value="do" checked <?= $tarikByBtn ?>  />
            <input id="kode_reffdo" value="" <?= $kodeRefDo ?> >

            Jenis Harga
            <input type="hidden" name="id_jenisharga" value="<?= $data->id_jenisharga ?>">
            <select id="id_jenisharga" class="input-xlarge" <?= $jenisHarga ?> >
            <?php foreach ($jenisHargaSelect as $jenisHarga): ?>
                <option value="<?= $jenisHarga->id ?>" <?= ($data->id_jenisharga == $jenisHarga->id || $jenisHarga->id == 1) ? "selected" : "" ?>><?= $jenisHarga->jenis_harga ?></option>
            <?php endforeach ?>
            </select>

            Cara Bayar
            <input type="hidden" name="id_carabayar" value="<?= $data->id_carabayar ?>">
            <select id="id_carabayar" class="" <?= $caraBayar ?> >
            <?php foreach ($caraBayarSelect as $caraBayar): ?>
                <option value="<?= $caraBayar->id ?>" <?= ($data->id_carabayar == $caraBayar->id || $caraBayar->id == 15) ? "selected" : "" ?>><?= $caraBayar->cara_bayar ?></option>
            <?php endforeach ?>
            </select>

            Distributor
            <input type="radio" name="tarik_by" value="pbf" <?= $tarikByBtn ?> />
            <input id="id_pbf" value="" <?= $idPemasok ?> >
            <input type="hidden" name="id_pbf" value="<?= $data->id_pbf ?>">

            Penyimpanan
            <input type="hidden" name="id_gudangpenyimpanan" value="<?= $data->id_gudangpenyimpanan ?>">
            <select id="id_gudangpenyimpanan" class="" <?= $jenisHarga ?> >
                <option value="59" selected>Gudang Induk Farmasi</option>
                <option value="60">Gudang Gas Medis</option>
                <option value="69">Gudang Konsinyasi</option>
            </select>

            <input type="checkbox" name="sts_tabunggm" value="1" <?= $checkboxStatusTabungGasMedis ?>  <?= $statusTabungGasMedis ?> />
            <input id="kodeJenis" value="Tabung Gas??" disabled>
        </div>

        <div class="col-lg-3 well">
            Sebelum Diskon (Rp.)
            <input class="nt" name="nilai_total" value="0.00" readonly>

            Diskon (Rp.)
            <input class="nd" name="nilai_diskon" value="0.00" readonly>

            Setelah Diskon (Rp.)
            <input class="n_td" value="0.00" readonly>

            PPN
            <input type="hidden" name="ppn" value="<?= $data->ppn ?>">
            <input type="checkbox" name="ppn" value="10" <?= $ppn ?> <?= $data->ppn ? "checked" : "" ?>/>
            <input class="np" name="nilai_ppn" value="0.00" readonly>

            Subtotal (Rp.)
            <input class="n_tp" value="0.00" readonly>

            Pembulatan (Rp.)
            <input class="pb" name="nilai_pembulatan" value="0.00" readonly>

            Nilai Total (Rp.)
            <input class="na" name="nilai_akhir" value="0.00" readonly>
        </div>
    </div>

    <table class="table table-bordered table-item table-responsive" id="tb-item-renc" style="width:auto">
        <thead>
            <tr>
                <th rowspan="2"><input type="checkbox" class="ck-all" <?= $cekAll ?> > NO</th>
                <th rowspan="2">NAMA BARANG</th>
                <th rowspan="2">PABRIK</th>
                <th rowspan="2">KEMASAN</th>
                <th rowspan="2">ISI</th>
                <th colspan="5">PENGADAAN</th>
                <th colspan="2">HARGA</th>
                <th rowspan="2">DISKON<br>%</th>
                <th colspan="3">TOTAL</th>
                <th colspan="6">REALISASI</th>
            </tr>
            <tr>
                <th>NO.</th>
                <th>BATCH</th>
                <th>KADALUARSA</th>
                <th>KEMASAN</th>
                <th>SATUAN</th>
                <th>KEMASAN</th>
                <th>SATUAN</th>
                <th>SEBELUM DISKON</th>
                <th>DISKON</th>
                <th>RP.</th>
                <th>RENCANA</th>
                <th>HPS</th>
                <th>SPK</th>
                <th>DO</th>
                <th>BONUS</th>
                <th>TERIMA</th>
                <th>RETUR</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($idata as $d): ?>
            <?php
            $jumlahKemasan = $d->jumlah_kemasan;
            $jumlahItem = $d->jumlah_item;
            $hargaKemasan = $d->harga_kemasan;
            $hargaItem = $d->harga_item;
            $diskonItem = $d->diskon_item;
            $isiKemasan = $d->isi_kemasan;
            $jumlahPl = $d->jumlah_pl;
            $jumlahDo = $d->jumlah_do;
            $jumlahTerima = $d->jumlah_trm;
            $jumlahRetur = $d->jumlah_ret;

            $hargaTotal = $jumlahKemasan * $hargaKemasan;
            $diskonHarga = $hargaTotal * $diskonItem / 100;
            $hargaAkhir = $hargaTotal - $diskonHarga;

            $nt += $hargaTotal;
            $nd += $diskonHarga;
            $nTd += $hargaAkhir;

            $jMax = ($jumlahDo ?: $jumlahPl) - $jumlahTerima + $jumlahRetur;
            ?>

            <?php if ($d->no_urut == "1"): ?>
                <tr class="tr-data" id="<?= $d->id_katalog ?>">
                    <td class="name">
                        <input type="hidden" name="kode_reffpo[]" value="<?= $d->kode_reffpo ?>"/>
                        <input type="hidden" name="kode_reffro[]" value="<?= $d->kode_reffro ?>"/>
                        <input type="hidden" name="kode_reffpl[]" value="<?= $d->kode_reffpl ?>"/>
                        <input type="hidden" name="kode_reffrenc[]" value="<?= $d->kode_reffrenc ?>"/>
                        <input type="hidden" name="id_reffkatalog[]" value="<?= $d->id_reffkatalog ?>"/>
                        <input type="hidden" name="id_katalog[]" value="<?= $d->id_katalog ?>"/>
                        <input type="hidden" name="id_pabrik[]" value="<?= $d->id_pabrik ?>"/>
                        <input type="hidden" name="kemasan[]" value="<?= $d->kemasan ?>"/>
                        <input type="hidden" name="jumlah_itembonus[]" value="'+Jbns+'"/>
                        <input type="hidden" name="j_beli[]" value="'+j_beli+'"/>
                        <input type="hidden" name="j_bonus[]" value="'+j_bonus+'"/>
                        <input type="hidden" name="no_urut[]" value="<?= $d->no_urut ?>"/>
                        <input type="hidden" name="id_kemasandepo[]" value="<?= $d->id_kemasandepo ?>"/>
                        <input type="checkbox" class="ck-one" <?= $item ?> >&nbsp;
                        <span class="no"><?= $no++ ?></span>
                    </td>
                    <td class="name">
                        <button class="btn-stok btn-warning">Stok</button>&nbsp;
                        <strong class="nb"><?= $d->nama_sediaan ?></strong>
                    </td>
                    <td class="name np"><?= $d->nama_pabrik ?></td>
                    <td class="input">
                        <select class="km" name="id_kemasan[]">
                            <option value="<?= $d->id_kemasan ?>" is="<?= $isiKemasan ?>" ids="<?= $d->id_kemasandepo ?>" sat="<?= $d->satuan ?>" satj="<?= $d->satuanjual ?>" hk="<?= $hargaKemasan ?>" selected ><?= $d->kemasan ?></option>
                        </select>
                    </td>
                    <td class="input"><input class="is" value="<?= $preferInt($isiKemasan) ?>" name="isi_kemasan[]" readonly></td>
                    <td class="input">
                        <button class="btn-addbch" <?= $item ?> ><i class="fa fa-plus"></i></button>&nbsp;
                        <span class="no_batch" par="<?= $d->id_katalog ?>" no_urut="<?= $d->no_urut ?>"><?= $d->no_urut ?></span>
                    </td>
                    <td class="input"><input class="bch" value="<?= $d->no_batch ?>" name="no_batch[]"<?= $item ?> ></td>
                    <td class="input"><input class="exp" value="<?= $d->tgl_expired ? date("d-m-Y", strtotime($d->tgl_expired)) : '' ?>" name="tgl_expired[]" <?= $item ?> ></td>
                    <td class="input">
                        <input class="jk num" name="jumlah_kemasan[]" value="<?= $toUserInt($jumlahKemasan) ?> <?= $d->satuanjual ?>" data-a-sign="<?= $d->satuanjual ?>" onfocus="this.select();" <?= $item ?> >
                    </td>
                    <td class="input">
                        <input class="ji num" name="jumlah_item[]" jMax="<?= $jMax ?>" value="<?= $preferInt($jumlahItem) ?> <?= $d->satuan ?>" data-a-sign="<?= $d->satuan ?>" readonly>
                    </td>
                    <td class="input"><input class="hk" name="harga_kemasan[]" value="<?= $toUserFloat($hargaKemasan) ?>" readonly></td>
                    <td class="input"><input class="hi" name="harga_item[]" value="<?= $toUserFloat($hargaItem) ?>" readonly></td>
                    <td class="input"><input class="di disc" name="diskon_item[]" value="<?= $toUserFloat($diskonItem) ?>" readonly></td>
                    <td class="input"><input class="ht" value="<?= $toUserFloat($hargaTotal) ?>" readonly></td>
                    <td class="input"><input class="dh" name="diskon_harga[]" value="<?= $toUserFloat($diskonHarga) ?>" readonly></td>
                    <td class="input"><input class="ha" value="<?= $toUserFloat($hargaAkhir) ?>" readonly></td>
                    <td class="name jR">0.00</td>
                    <td class="name jH">0.00</td>
                    <td class="name jP"><?= $jumlahPl ?></td>
                    <td class="name jS">0.00</td>
                    <td class="name jB">0.00</td>
                    <td class="name jT"><?= $preferInt($jumlahTerima) ?></td>
                    <td class="name jRt"><?= $preferInt($jumlahRetur) ?></td>
                </tr>
            <?php else: ?>
                <tr class="tr-data">
                    <td class="name" colspan="4">
                        <input type="hidden" name="id_katalog[]" value="<?= $d->id_katalog ?>"/>
                        <input type="hidden" name="no_urut[]" value="<?= $d->no_urut ?>"/>
                    </td>
                    <td class="input"><input class="is" value="<?= $preferInt($isiKemasan) ?>" name="isi_kemasan[]" readonly></td>
                    <td class="input">
                        <button class="btn-danger btn-delbch" <?= $item ?> ><i class="fa fa-minus"></i></button>&nbsp;
                        <span class="no_batch" par="<?= $d->id_katalog ?>" no_urut="<?= $d->no_urut ?>"><?= $d->no_urut ?></span>
                    </td>
                    <td class="input"><input class="bch" value="<?= $d->no_batch ?>" name="no_batch[]" <?= $item ?> ></td>
                    <td class="input"><input class="exp" value="<?= $d->tgl_expired ? date("d-m-Y", strtotime($d->tgl_expired)) : '' ?>" name="tgl_expired[]" <?= $item ?> ></td>
                    <td class="input">
                        <input class="jk num" name="jumlah_kemasan[]" value="<?= $toUserInt($jumlahKemasan) ?> <?= $d->satuanjual ?>" data-a-sign="<?= $d->satuanjual ?>" onfocus="this.select();" <?= $item ?> >
                    </td>
                    <td class="input">
                        <input class="ji num" name="jumlah_item[]" jMax="<?= $jMax ?>" value="<?= $preferInt($jumlahItem) ?> <?= $d->satuan ?>" data-a-sign="<?= $d->satuan ?>" readonly>
                    </td>
                    <td class="input"><input class="hk" name="harga_kemasan[]" value="<?= $toUserFloat($hargaKemasan) ?>" readonly></td>
                    <td class="input"><input class="hi" name="harga_item[]" value="<?= $toUserFloat($hargaItem) ?>" readonly></td>
                    <td class="input"><input class="di disc" name="diskon_item[]" value="<?= $toUserFloat($diskonItem) ?>" readonly></td>
                    <td class="input"><input class="ht" value="<?= $toUserFloat($hargaTotal) ?>" readonly></td>
                    <td class="input"><input class="dh" name="diskon_harga[]" value="<?= $toUserFloat($diskonHarga) ?>" readonly></td>
                    <td class="input"><input class="ha" value="<?= $toUserFloat($hargaAkhir) ?>" readonly></td>
                    <td class="name jR">0.00</td>
                    <td class="name jH">0.00</td>
                    <td class="name jP"><?= $jumlahPl ?></td>
                    <td class="name jS">0.00</td>
                    <td class="name jB">0.00</td>
                    <td class="name jT"><?= $preferInt($jumlahTerima) ?></td>
                    <td class="name jRt"><?= $preferInt($jumlahRetur) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>

            <?php
            $np = $nTd * $data->ppn / 100;
            $nTp = $nTd + $np;
            $na = round($nTp);
            $pb = $na - $nTp;
            ?>
            <tr class="tr-add" id="-">
                <td class="name">
                    <input type="hidden" name="id_reffkatalog[]" disabled>
                    <input type="hidden" name="id_katalog[]" disabled>
                    <input type="hidden" name="id_pabrik[]" disabled>
                    <input type="hidden" name="kemasan[]" disabled>
                    <input type="hidden" name="id_kemasandepo[]" disabled>
                    <input type="checkbox" disabled>&nbsp;
                    <span class="no"><?= $no ?></span>
                </td>
                <td class="name disabled"></td>
                <td class="name disabled"></td>
                <td class="input"><select class="km" name="id_kemasan[]" disabled></select></td>
                <td class="input"><input class="is" value="1" name="isi_kemasan[]" disabled></td>
                <td class="name disabled"></td>
                <td class="input"><input class="bch" value="" name="no_batch[]" disabled></td>
                <td class="input"><input class="exp" value="__-__-____" name="tgl_expired[]" disabled></td>
                <td class="input"><input class="jk num" value="0 BOX" name="jumlah_kemasan[]" disabled></td>
                <td class="input"><input class="ji num" value="0 BOX" name="jumlah_item[]" disabled></td>
                <td class="input"><input class="hk" value="0.00" name="harga_kemasan[]" disabled></td>
                <td class="input"><input class="hi" value="0.00" name="harga_item[]" disabled></td>
                <td class="input"><input class="di" maxlength="4" value="0.00" name="diskon_item[]" disabled></td>
                <td class="input"><input class="ht" value="0.00" disabled></td>
                <td class="input"><input class="dh" value="0.00" name="diskon_harga[]" disabled></td>
                <td class="input"><input class="ha" value="0.00" disabled></td>
                <td class="name jR">0.00</td>
                <td class="name jH">0.00</td>
                <td class="name jP">0.00</td>
                <td class="name jS">0.00</td>
                <td class="name jB">0.00</td>
                <td class="name jT">0.00</td>
                <td class="name jRt">0.00</td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="13" class="name text-right">TOTAL</td>
                <td class="input"><input class="nt" value="0.00" readonly></td>
                <td class="input"><input class="nd" value="0.00" readonly></td>
                <td class="input"><input class="n_td" value="0.00" readonly></td>
                <td colspan="7"></td>
            </tr>
            <tr>
                <td colspan="15" class="name text-right">PPN</td>
                <td class="input"><input class="np" value="0.00" readonly></td>
                <td colspan="7"></td>
            </tr>
            <tr>
                <td colspan="15" class="name text-right">SETELAH PPN</td>
                <td class="input"><input class="n_tp" value="0.00" readonly></td>
                <td colspan="7"></td>
            </tr>
        </tfoot>
    </table>

    <table>
        <tr>
            <th>Ver No</th>
            <th>Ver</th>
            <th>Otorisasi</th>
            <th>User</th>
            <th>Tanggal</th>
            <th>Updt Stok</th>
        </tr>
        <tr>
            <td>1</td>
            <td><input type="checkbox" class="ver" ver="terima" name="ver_terima" id="id_verterima" value="1" <?= $data->verterima ?> ></td>
            <td>Tim Penerima</td>
            <td class="usr_terima"><?= $data->user_terima ?? "" ?></td>
            <td class="tgl_terima"><?= $data->ver_tglterima ? $toUserTime($data->ver_tglterima) : "" ?></td>
            <td><i class="fa fa-square-o"></i></td>
        </tr>
        <tr>
            <td>2</td>
            <td><input type="checkbox" class="ver" ver="gudang" name="ver_gudang" id="id_vergudang" value="1" <?= $data->vergudang ?> ></td>
            <td>Gudang</td>
            <td class="usr_gudang"><?= $data->user_gudang ?? "" ?></td>
            <td class="tgl_gudang"><?= $data->ver_tglgudang ? $toUserTime($data->ver_tglgudang) : "" ?></td>
            <td><input type="checkbox" id="id_updatestok" value="1" <?= $data->stokgudang ?? "" ?> ></td>
        </tr>
        <tr>
            <td>3</td>
            <td><input type="checkbox" class="ver" ver="akuntansi" name="ver_akuntansi" id="id_verakuntansi" value="1" <?= $data->verakuntansi ?> ></td>
            <td>Akuntansi</td>
            <td class="usr_akuntansi"><?= $data->user_akuntansi ?? "" ?></td>
            <td class="tgl_akuntansi"><?= $data->ver_tglakuntansi ? $toUserTime($data->ver_tglakuntansi) : "" ?></td>
            <td><i class="fa fa-square-o"></i></td>
        </tr>
    </table>
</form>

<script>
$(() => {
    const userFloat = tlm.toUserFloat;

    $(".nt").val(userFloat("<?= $nt ?>"));
    $(".nd").val(userFloat("<?= $nd ?>"));
    $(".n_td").val(userFloat("<?= $nTd ?>"));
    $(".np").val(userFloat("<?= $np ?>"));
    $(".n_tp").val(userFloat("<?= $nTp ?>"));
    $(".bp").val(userFloat("<?= $pb ?>"));
    $(".na").val(userFloat("<?= $na ?>"));

    getSPK($("#kode_reffpl"));
    getPemesanan($("#kode_reffdo"));
    getDistributor($("#id_pbf"));

    const $data = <?= json_encode($data) ?>;

    const $tarikBy = $('[name="tarik_by"]');
    if ($data.kode_reffpo) {
        const doObj = {
            kode: $data.kode_reffpo,
            kode_reffpl: $data.kode_reffpl,
            no_doc: $data.no_po,
            no_spk: $data.no_spk,
            tgl_mulai: $data.tgl_mulai,
            tgl_tempokirim: $data.tgl_tempokirim,
            tgl_jatuhtempo: $data.tgl_jatuhtempo,
            id_jenisanggaran: $data.id_jenisanggaran,
            id_jenisharga: $data.id_jenisharga,
            id_sumberdana: $data.id_sumberdana,
            id_carabayar: $data.id_carabayar,
            id_pbf: $data.id_pbf,
            kode_pbf: $data.kode_pbf,
            nama_pbf: $data.nama_pbf,
            subjenis_anggaran: $data.subjenis_anggaran,
            jenis_harga: $data.jenis_harga,
            nilai_akhir: $data.nilai_akhir_ro,
            blnawal_anggaran_pl: $data.blnawal_anggaran_pl,
            blnakhir_anggaran_pl: $data.blnakhir_anggaran_pl,
            thn_anggaran_pl: $data.thn_anggaran_pl,
            nilai_akhir_pl: $data.nilai_akhir_pl,
            act: 1,
        };
        $do[0].selectize.addOption(doObj);
        $do[0].selectize.setValue(doObj.kode);
        $tarikBy.val("do");
    } else {
        const spkObj = {
            kode: $data.kode_reffpl,
            no_doc: $data.no_spk,
            tgl_doc: $data.tgl_mulai,
            tgl_jatuhtempo: $data.tgl_jatuhtempo,
            id_jenisanggaran: $data.id_jenisanggaran,
            id_jenisharga: $data.id_jenisharga,
            id_sumberdana: $data.id_sumberdana,
            id_carabayar: $data.id_carabayar,
            id_pbf: $data.id_pbf,
            kode_pbf: $data.kode_pbf,
            nama_pbf: $data.nama_pbf,
            subjenis_anggaran: $data.subjenis_anggaran,
            jenis_harga: $data.jenis_harga,
            blnawal_anggaran: $data.blnawal_anggaran_pl,
            blnakhir_anggaran: $data.blnakhir_anggaran_pl,
            thn_anggaran: $data.thn_anggaran_pl,
            nilai_akhir: $data.nilai_akhir_pl,
            act: 1,
        };
        $spk[0].selectize.addOption(spkObj);
        $spk[0].selectize.setValue(spkObj.kode);
        $tarikBy.val("spk");
    }
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
