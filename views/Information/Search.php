<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Information;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Information/search.php the original file
 */
final class Search
{
    private string $output;

    public function __construct(
        string $registerId,
        string $action1Url,
        string $action2Url,
        string $pembelianAcplUrl,
        string $pemesananAcplUrl,
        string $pemasokAcplUrl,
        string $katalogAcplUrl,
        string $batchUrl,
        string $unitUrl
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Information.Search {
    export interface UnitFields {
        id:         "id";
        kode:       "kode";
        namaDepo:   "namaDepo";
        keterangan: "keterangan";
        lokasiDepo: "lokasiDepo";
    }

    export interface BatchFields {
        noBatch:           "no_batch";
        tanggalKadaluarsa: "tgl_expired";
        namaSediaan:       "nama_sediaan";
        unitPemilik:       "unit_pemilik";
    }

    export interface KatalogFields {
        isiKemasan:    string;
        idKemasan:     string;
        idKemasanDepo: string;
        satuan:        string;
        satuanJual:    string;
        idKatalog:     string;
        namaSediaan:   string;
        namaPabrik:    string;
    }

    export interface PemasokFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface RefPoFields {
        kode:               string;
        namaPemasok:        string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        noDokumen:          string;
        tanggalTempoKirim:  string; // not used, but exist in controller
        nilaiAkhir:         string; // not used, but exist in controller
        idPemasok:          "id_pbf";
        noSpk:              "no_spk";
        subjenisAnggaran:   "subjenis_anggaran";
    }

    export interface RefPlFields {
        kode:               string;
        idPemasok:          string;
        namaPemasok:        string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        tipeDokumen:        string;
        idJenisHarga:       string;
        noDokumen:          string;
        subjenisAnggaran:   string;
    }
}
</script>

<!--suppress NestedConditionalExpressionJS -->
<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form_1: {
            class: ".form1Frm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>
                }
            }
        },
        form_2: {
            class: ".form2Frm",
            hidden: {name: "xxx"},
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("xxx") ?>,
                        input: {class: ".xxxFld", name: "xxx"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("yyy") ?>,
                        select: {class: ".yyyFld", name: "yyy", options: opt._<?= $h($yyyJson) ?>}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {preferInt, numToShortMonthName: nToS, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const form1Wgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".form1Frm"),
            actionUrl: "<?= $action1Url ?>"
        });

        // JUNK -----------------------------------------------------

        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kode_reffpl"),
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Information.Search.RefPlFields} item */
            optionRenderer(item) {
                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                const {tipeDokumen, idJenisHarga, noDokumen, namaPemasok, subjenisAnggaran} = item;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                const jenisPl =
                    (tipeDokumen == "0" /*--------------------*/) ? str._<?= $h("Kontrak Harga") ?>
                    : (tipeDokumen == "1" && idJenisHarga == "2") ? str._<?= $h("Kontrak E-Katalog") ?>
                    : (tipeDokumen == "1" /*------------------*/) ? str._<?= $h("Kontrak") ?>
                    : (tipeDokumen == "2" /*------------------*/) ? str._<?= $h("Surat Perintah Kerja") ?>
                    : /*---------------------------------------*/   str._<?= $h("Surat Pesanan") ?>;

                return `
                    <div class="option">
                        <span class="name">${jenisPl} (${noDokumen})</span><br/>
                        <span class="description">
                            Pemasok: ${namaPemasok}<br/>
                            Mata Anggaran: ${subjenisAnggaran}<br/>
                            Bulan Anggaran: ${anggaran}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Information.Search.RefPlFields} item */
            itemRenderer(item) {
                const {tipeDokumen, idJenisHarga, noDokumen} = item;
                const jenisPl =
                    (tipeDokumen == "0" /*--------------------*/) ? str._<?= $h("Kontrak Harga") ?>
                    : (tipeDokumen == "1" && idJenisHarga == "2") ? str._<?= $h("Kontrak E-Katalog") ?>
                    : (tipeDokumen == "1" /*------------------*/) ? str._<?= $h("Kontrak") ?>
                    : (tipeDokumen == "2" /*------------------*/) ? str._<?= $h("Surat Perintah Kerja") ?>
                    : /*---------------------------------------*/   str._<?= $h("Surat Pesanan") ?>;

                return `<div class="option">${jenisPl} (${noDokumen})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pembelianAcplUrl ?>",
                    data: {noDokumen: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const kodeRefPoWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kode_reffpo"),
            maxItems: 100,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Information.Search.RefPoFields} item */
            optionRenderer(item) {
                const awal = item.bulanAwalAnggaran;
                const akhir = item.bulanAkhirAnggaran;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + item.tahunAnggaran;

                return `
                    <div class="option">
                        <span class="name">${item.noSpk} (${item.noDokumen})</span><br/>
                        <span class="description">
                            Pemasok: ${item.namaPemasok}<br/>
                            Mata Anggaran: ${item.subjenisAnggaran}<br/>
                            Bulan Anggaran: ${anggaran}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Information.Search.RefPoFields} item */
            itemRenderer(item) {return `<div class="item">${item.noSpk} (${item.noDokumen})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                const kodeReffPl = divElm.querySelector("#kode_reffpl").value || undefined;
                $.post({
                    url: "<?= $pemesananAcplUrl ?>",
                    data: {noDokumen: typed, statusSaved: 1, kodeRefPl: kodeReffPl},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector("[name=id_pbf]"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Information.Search.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Information.Search.PemasokFields} item */
            itemRenderer(item) {return `<div class="item">${item.nama} (${item.kode})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pemasokAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: divElm.querySelector(".id_katalog"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["namaSediaan", "idKatalog"],
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.Information.Search.KatalogFields} data
             */
            assignPairs(formElm, data) {
                // TODO: js: uncategorized: finish this
                // "[name=nama_sediaan]": data.namaSediaan ?? ""
            },
            /** @param {his.FatmaPharmacy.views.Information.Search.KatalogFields} item */
            optionRenderer(item) {
                const {isiKemasan: isi, satuan, idKemasan, idKemasanDepo, satuanJual} = item;
                const kemasan = ((isi == 1 && idKemasan == idKemasanDepo) ? "" : satuanJual + " " + preferInt(isi)) + " " + satuan;

                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.idKatalog}</b></div>
                        <div class="col-xs-5"><b>${item.namaSediaan}</b></div>
                        <div class="col-xs-3">${item.namaPabrik}</div>
                        <div class="col-xs-2">${kemasan}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Information.Search.KatalogFields} item */
            itemRenderer(item) {return `<div class="item">${item.namaSediaan} (${item.idKatalog})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $katalogAcplUrl ?>",
                    data: {query: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const noBatchWgt = new spl.SelectWidget({
            element: divElm.querySelector(".no_batch"),
            maxItems: 1,
            valueField: "noBatch",
            searchField: ["noBatch"],
            /** @param {his.FatmaPharmacy.views.Information.Search.BatchFields} item */
            optionRenderer(item) {
                return `
                    <div class="option">
                        <span class="name"><b>[${item.noBatch}]</b> - Exp: ${item.tanggalKadaluarsa}</span><br/>
                        <span class="description">
                            ${item.namaSediaan}<br/>
                            Pemilik: ${item.unitPemilik}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Information.Search.BatchFields} item */
            itemRenderer(item) {return `<div class="item">${item.noBatch} - Exp: ${item.tanggalKadaluarsa}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                const idKatalog = divElm.querySelector(".id_katalog").value;
                $.post({
                    url: "<?= $batchUrl ?>",
                    data: {idKatalog, noBatch: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const idUnitWgt = new spl.SelectWidget({
            element: divElm.querySelector(".id_unit"),
            maxItems: 1,
            valueField: "id",
            searchField: ["namaDepo"],
            /** @param {his.FatmaPharmacy.views.Information.Search.UnitFields} item */
            optionRenderer(item) {
                return `
                    <div class="option">
                        <span class="name">${item.namaDepo} (${item.kode})</span><br/>
                        <span class="description">
                            Keterangan: ${item.keterangan}<br/>
                            Lokasi: ${item.lokasiDepo}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Information.Search.UnitFields} item */
            itemRenderer(item) {return `<div class="item">${item.namaDepo} (${item.kode})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $unitUrl ?>",
                    data: {namaDepo: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const form2Wgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".form1Frm"),
            resetBtnId: false,
            actionUrl: "<?= $action2Url ?>"
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(form1Wgt, kodeRefPlWgt, kodeRefPoWgt, idPemasokWgt, idKatalogWgt, noBatchWgt, idUnitWgt, form2Wgt);
        tlm.app.registerWidget(this.constructor.widgetName, form1Wgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<div id="<?= $registerId ?>">
    <div id="tabs">
        <ul>
            <li><a href="#itemin">Find Katalog "IN DOCUMENT"</a></li>
            <li><a href="#docin">Find "DOCUMENT" In Document</a></li>
        </ul>

        <div id="itemin">
            <form id="<?= $registerId ?>_frm1">
                <table class="table table-striped table-bordered">
                    <tr>
                        <td style="width:10%"><label>Nama Barang</label></td>
                        <td style="width:50%"><input name="idKatalog" class="form-control id_katalog" style="width:100%" required /></td>
                        <td style="width:15%"></td>
                        <td style="width:25%"></td>
                    </tr>
                </table>

                <div class="input-group">
                    <div class="full-group col-lg-3">
                        <div class="form-group">
                            <label>Status Dokumen</label>
                            <select class="form-control input-sm input-large" name="sts_closed" id="sts_closed">
                                <option value="-"></option>
                                <option value="0" selected>Open</option>
                                <option value="1">Closed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" name="submit" value="itemin">Tampilkan</button>
            </form>
        </div>

        <div id="docin">
            <form id="<?= $registerId ?>_frm2">
                <table class="table table-striped table-bordered">
                    <tr>
                        <td>Tipe Dokument</td>
                        <td>
                            <select name="id_dokumen" id="id_dokumen" class="form-control">
                                <option value="-">- Pilih Dokumen Pencarian</option>
                                <option value="renc">- Perencanaan (NK)</option>
                                <option value="hps">- Pengadaan (HPS)</option>
                                <option value="pl">- Pembelian (PL)</option>
                                <option value="ro">- Perencanaan (RO)</option>
                                <option value="po">- Pemesanan (PO)</option>
                                <option value="ba">- Penerimaan (BA)</option>
                                <option value="ret">- Retur Penerimaan (RET)</option>
                            </select>
                        </td>
                        <td>No. Dokument</td>
                        <td class="to_nodoc"></td>
                    </tr>
                </table>

                <button class="btn btn-primary" name="submit" value="docin">Tampilkan</button>
            </form>
        </div>
    </div>
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
