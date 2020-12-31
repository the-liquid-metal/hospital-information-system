<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PemesananUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pemesanan/reports.php the original file (form)
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pemesanan/index_itempo.php the original file (itemTbl)
 */
final class TablePo
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $pembelianAcplUrl,
        string $katalogAcplUrl,
        string $pembelianViewWidgetId,
        string $pemesananViewWidgetId,
        string $jenisAnggaranSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pemesanan.Report {
    export interface FormFields {
        format:           string|"in"|"out";
        kodeRefPl:        string|"in"|"out";
        kodePemasok:      string|"in"|"out";
        idKatalog:        string|"in"|"out";
        namaSediaan:      string|"in"|"out";
        idJenisAnggaran:  string|"in"|"out";
        subjenisAnggaran: string|"in"|"out";
        idJenisHarga:     string|"in"|"out";
        tanggalMulai:     string|"in"|"out";
        tanggalAkhir:     string|"in"|"out";
        idSubsumberDana:  string|"in"|"out";
    }

    export interface TableFields {
        statusClosed:      string;
        kodeRefPl:         string;
        noSpk:             string;
        kodeRef:           string;
        noDokumen:         string;
        tanggalTempoKirim: string;
        namaPemasok:       string;
        namaPabrik:        string;
        kemasan:           string;
        jumlahKemasan:     string;
        jumlahItem:        string;
        hargaKemasan:      string;
        hargaItem:         string;
        diskonItem:        string;
        diskonHarga:       string;
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

    export interface RefPlFields {
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        tipeDokumen:        string;
        idJenisHarga:       string;
        kode:               string;
        noDokumen:          string;
        namaPemasok:        string;
        subjenisAnggaran:   string;
        idPemasok:          string; // not used, but exist in controller
    }
}
</script>

<!--suppress NestedConditionalExpressionJS -->
<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    _structure = {
        form: {
            class: ".saringFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Format") ?>,
                        select: {
                            class: ".formatFld",
                            option_1: {value: 1, label: tlm.stringRegistry._<?= $h("DO/PO per Item/Katalog") ?>, selected: true},
                            option_2: {value: 2, label: tlm.stringRegistry._<?= $h("Laporan Delivery Order") ?>}
                        }
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kode PL") ?>,
                        input: {class: ".kodeRefPlFld"}
                    },
                    formGroup_3: {
                        class: ".doPerItemBlk",
                        label: tlm.stringRegistry._<?= $h("Nama Barang") ?>,
                        select: {class: ".idKatalogFld"}
                    },
                    formGroup_4: {
                        class: ".laporanDoBlk",
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {class: ".kodePemasokFld"}
                    },
                    formGroup_5: {
                        class: ".laporanDoBlk",
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld"},
                        hidden: {class: ".subjenisAnggaranFld"}
                    },
                    formGroup_6: {
                        class: ".laporanDoBlk",
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {
                            class: ".idJenisHargaFld",
                            option_1: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("E-Katalog & Non E-Katalog") ?>},
                            option_2: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("E-Katalog") ?>},
                            option_3: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Non E-Katalog") ?>}
                        }
                    },
                    formGroup_7: {
                        class: ".laporanDoBlk",
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal Kontrak") ?>,
                        input: {class: ".tanggalMulaiFld"}
                    },
                    formGroup_8: {
                        class: ".laporanDoBlk",
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir Kontrak") ?>,
                        input: {class: ".tanggalAkhirFld"}
                    },
                    formGroup_9: {
                        class: ".laporanDoBlk",
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {
                            class: ".idSubsumberDanaFld",
                            option_1: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa APBN & Dipa PNBP") ?>},
                            option_2: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa APBN") ?>},
                            option_3: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa PNBP") ?>}
                        }
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            }
        },
        row: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr_1: {
                        td_1:  /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Status PO") ?>},
                        td_2:  /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>},
                        td_3:  /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No. PL Pembelian") ?>},
                        td_4:  /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Tempo Kontrak") ?>},
                        td_5:  /*  5    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_6:  /*  6    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik on PL") ?>},
                        td_7:  /*  7    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_8:  /*  8-9  */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Volume") ?>},
                        td_9:  /* 10-11 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_10: /* 12-13 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                    },
                    tr_2: {
                        td_1: /* 8  */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_2: /* 9  */ {text: tlm.stringRegistry._<?= $h("Item") ?>},
                        td_3: /* 10 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_4: /* 11 */ {text: tlm.stringRegistry._<?= $h("Item") ?>},
                        td_5: /* 12 */ {text: tlm.stringRegistry._<?= $h("%") ?>},
                        td_6: /* 13 */ {text: tlm.stringRegistry._<?= $h("Rp") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {preferInt, numToShortMonthName: nToS, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const formatFld = divElm.querySelector(".formatFld");
        /** @type {HTMLDivElement} */    const doPerItemBlk = divElm.querySelector(".doPerItemBlk");
        /** @type {HTMLDivElement} */    const laporanDoBlk = divElm.querySelector(".laporanDoBlk");
        /** @type {HTMLInputElement} */  const kodePemasokFld = divElm.querySelector(".kodePemasokFld");
        /** @type {HTMLInputElement} */  const idSubsumberDanaFld = divElm.querySelector(".idSubsumberDanaFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLInputElement} */  const subjenisAnggaranFld = divElm.querySelector(".subjenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        this._selects.push(idJenisAnggaranFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Pemesanan.Report.FormFields} data */
            loadData(data) {
                formatFld.value = data.format ?? "";
                kodeRefPlWgt.value = data.kodeRefPl ?? "";
                idKatalogWgt.value = data.idKatalog ?? "";
                kodePemasokFld.value = data.kodePemasok ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                subjenisAnggaranFld.value = data.subjenisAnggaran ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                tanggalMulaiWgt.value = data.tanggalMulai ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                idSubsumberDanaFld.value = data.idSubsumberDana ?? "";
            },
            submit() {
                if (formatFld.value == "1") {
                    itemWgt.refresh({
                        query: {idKatalog: idKatalogWgt.value, kodeRefPl: kodeRefPlWgt.value}
                    });
                } else {
                    alert(str._<?= $h("Laporan Delivery Order belum diimplementasikan.") ?>);
                }
            }
        });

        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPlFld"),
            maxItems: 100,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pemesanan.Report.RefPlFields} item */
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
            /** @param {his.FatmaPharmacy.views.Pemesanan.Report.RefPlFields} item */
            itemRenderer(item) {
                const {tipeDokumen, idJenisHarga, noDokumen} = item;
                const jenisPl =
                    (tipeDokumen == "0" /*--------------------*/) ? str._<?= $h("Kontrak Harga") ?>
                    : (tipeDokumen == "1" && idJenisHarga == "2") ? str._<?= $h("Kontrak E-Katalog") ?>
                    : (tipeDokumen == "1" /*------------------*/) ? str._<?= $h("Kontrak") ?>
                    : (tipeDokumen == "2" /*------------------*/) ? str._<?= $h("Surat Perintah Kerja") ?>
                    : /*---------------------------------------*/   str._<?= $h("Surat Pesanan") ?>;

                return `<div class="item">${jenisPl} (${noDokumen})</div>`;
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

        const idKatalogWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["namaSediaan", "idKatalog"],
            /** @param {his.FatmaPharmacy.views.Pemesanan.Report.KatalogFields} item */
            optionRenderer(item) {
                const {isiKemasan: isi, satuan, idKemasan, idKemasanDepo, satuanJual} = item;
                const kemasan = ((isi != 1 || idKemasan != idKemasanDepo) ? satuanJual + " " + preferInt(isi) : "") + " " + satuan;

                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.idKatalog}</b></div>
                        <div class="col-xs-5"><b>${item.namaSediaan}</b></div>
                        <div class="col-xs-3">${item.namaPabrik}</div>
                        <div class="col-xs-2">${kemasan}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Pemesanan.Report.KatalogFields} item */
            itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.namaSediaan})</div>`},
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

        idJenisAnggaranFld.addEventListener("change", () => {
            subjenisAnggaranFld.value = idJenisAnggaranFld.selectedOptions[0].innerHTML;
        });

        const tanggalMulaiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalMulaiFld"),
            errorRules: [{
                callback() {return formatFld.value == "laporan_akhir" || (formatFld.value == "DO/PO per Item/Katalog" && this._element.value)},
                message: Validator.getMsg("required")
            }],
            ...tlm.dateWidgetSetting
        });

        const tanggalAkhirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAkhirFld"),
            errorRules: [{
                callback() {return formatFld.value == "laporan_akhir" || (formatFld.value == "DO/PO per Item/Katalog" && this._element.value)},
                message: Validator.getMsg("required")
            }],
            ...tlm.dateWidgetSetting
        });

        formatFld.addEventListener("change", () => {
            if (formatFld.value == "1") {
                doPerItemBlk.classList.remove("hidden");
                laporanDoBlk.classList.add("hidden");
                tanggalMulaiWgt.removeMessage().hideMessage();
                tanggalAkhirWgt.removeMessage().hideMessage();
            } else {
                doPerItemBlk.classList.add("hidden");
                laporanDoBlk.classList.remove("hidden");
            }
        });

        /** @see {his.FatmaPharmacy.views.Pemesanan.Report.TableFields} */
        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, {statusClosed}) {                                   // TODO: js: uncategorized: add title (or something):
                    return statusClosed == "0" ? str._<?= $h("Open") ?> : str._<?= $h("Closed") ?>; // Open:   PL masih aktif
                }},                                                                        // Closed: PL telah melewati masa kontrak atau Seluruh Item Barang sudah diterima
                2: {formatter(unused, item) {
                    const {kodeRefPl, noSpk} = item;
                    return draw({class: ".viewSpkBtn", value: kodeRefPl, text: noSpk});
                }},
                3: {formatter(unused, item) {
                    const {kodeRef, noDokumen} = item;
                    return draw({class: ".viewNoDokumenBtn", value: kodeRef, text: noDokumen});
                }},
                4:  {field: "tanggalTempoKirim", formatter: tlm.dateFormatter},
                5:  {field: "namaPemasok"},
                6:  {field: "namaPabrik"},
                7:  {field: "kemasan"},
                8:  {field: "jumlahKemasan", formatter: tlm.intFormatter},
                9:  {field: "jumlahItem", formatter: tlm.intFormatter},
                10: {field: "hargaKemasan", formatter: tlm.floatFormatter},
                11: {field: "hargaItem", formatter: tlm.floatFormatter},
                12: {field: "diskonItem", formatter: tlm.intFormatter},
                13: {field: "diskonHarga", formatter: tlm.intFormatter}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", event => {
            const viewSpkBtn = event.target;
            if (!viewSpkBtn.matches(".viewSpkBtn")) return;

            const widget = tlm.app.getWidget("_<?= $pembelianViewWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewSpkBtn.value}, true);
        });

        itemWgt.addDelegateListener("tbody", "click", event => {
            const viewNoDokumenBtn = event.target;
            if (!viewNoDokumenBtn.matches(".viewNoDokumenBtn")) return;

            const widget = tlm.app.getWidget("_<?= $pemesananViewWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewNoDokumenBtn.value}, true);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, kodeRefPlWgt, idKatalogWgt, tanggalMulaiWgt, tanggalAkhirWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, saringWgt);
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
