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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/reports.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/search_nodoc.php the original file
 */
final class FormReport
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $reportUrl,
        string $katalogAcplUrl,
        string $viewWidgetId,
        string $bulanSelect,
        string $tahunSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Perencanaan.Reports {
    export interface Form1Fields {
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        statusKontrak:      string;
        format:             string;
    }

    export interface Form2Fields {
        idKatalog:          string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
    }

    export interface ItemFields {
        bulanAwalAnggaran:    string;
        bulanAkhirAnggaran:   string;
        tahunAnggaran:        string;
        kodeReferensi:        string;
        noDokumenPerencanaan: string;
        tanggalDokumen:       string;
        noPl:                 string;
        namaPemasok:          string;
        subjenisAnggaran:     string;
        nilaiAkhir:           string;
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
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName]: {
            ".printAreaBlk": {
                ".print_area": {
                    _suffixes_1: [""],
                    _style_1:    {marginTop: "2cm", marginBottom: "2cm", pageBreakBefore: "always"},
                    _suffixes_2: [":first-child"],
                    _style_2:    {marginTop: 0, marginBottom: "2cm"},
                    _suffixes_3: [":last-child"],
                    _style_3:    {marginTop: "2cm", marginBottom: 0},
                },
                ".print_body": {
                    _style: {pageBreakInside: "auto"}
                },
                ".daftar_obat": {
                    _suffixes_1: [""],
                    _style_1:    {pageBreakInside: "auto"},
                    _children_2: ["thead"],
                    _style_2:    {display: "table-header-group"},
                    _children_3: ["tr"],
                    _style_3:    {pageBreakInside: "avoid", pageBreakAfter: "auto"},
                }
            }
        }
    };

    _structure = {
        row_1: {
            heading3: {text: tlm.stringRegistry._<?= $h("Rekapitulasi Perencanaan") ?>}
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            tab: {
                pane_1: {
                    title: tlm.stringRegistry._<?= $h("Rekapitulasi Perencanaan") ?>,
                    form: {
                        class: ".saring1Frm",
                        row_1: {
                            box: {
                                title: tlm.stringRegistry._<?= $h("Saring") ?>,
                                formGroup_1: {
                                    label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                                    select: {class: ".bulanAwalAnggaran1Fld", name: "bulanAwalAnggaran"}
                                },
                                formGroup_2: {
                                    label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                                    select: {class: ".bulanAkhirAnggaran1Fld", name: "bulanAkhirAnggaran"}
                                },
                                formGroup_3: {
                                    label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                                    select: {class: ".tahunAnggaran1Fld", name: "tahunAnggaran"}
                                },
                                formGroup_4: {
                                    label: tlm.stringRegistry._<?= $h("Tipe Perencanaan") ?>,
                                    checkbox: {class: "statusKontrakFld", name: "statusKontrak", value: 1, label: tlm.stringRegistry._<?= $h("Perencanaan Repeate Order (Kontrak)") ?>}
                                },
                                formGroup_5: {
                                    label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                                    radio: {class: "formatFld", name: "format", value: "rekap", checked: true, label: tlm.stringRegistry._<?= $h("Rekapitulasi Rencana dan Realisasi Pengadaan Barang Farmasi") ?>}
                                }
                            }
                        },
                        row_2: {
                            widthColumn: {
                                class: "text-center",
                                SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                            }
                        }
                    },
                    row_1: {
                        widthColumn: {
                            paragraph: {text: "&nbsp;"}
                        }
                    },
                    row_2: {
                        widthColumn: {class: ".printAreaBlk"}
                    }
                },
                pane_2: {
                    title: tlm.stringRegistry._<?= $h("Cari No. Perencanaan Item") ?>,
                    form: {
                        class: ".saring2Frm",
                        row_1: {
                            box: {
                                title: tlm.stringRegistry._<?= $h("Saring") ?>,
                                formGroup_1: {
                                    label: tlm.stringRegistry._<?= $h("Nama Katalog") ?>,
                                    input: {class: ".idKatalogFld", name: "idKatalog"}
                                },
                                formGroup_2: {
                                    label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                                    select: {class: ".bulanAwalAnggaran2Fld", name: "bulanAwalAnggaran"}
                                },
                                formGroup_3: {
                                    label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                                    select: {class: ".bulanAkhirAnggaran2Fld", name: "bulanAkhirAnggaran"}
                                },
                                formGroup_4: {
                                    label: tlm.stringRegistry._<?= $h("Tahun") ?>,
                                    select: {class: ".tahunAnggaran2Fld", name: "tahunAnggaran"}
                                }
                            }
                        },
                        row_2: {
                            widthColumn: {
                                class: "text-center",
                                SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                            }
                        }
                    },
                    row_1: {
                        widthColumn: {
                            paragraph: {text: "&nbsp;"}
                        }
                    },
                    row_2: {
                        widthTable: {
                            class: ".itemTbl",
                            thead: {
                                tr: {
                                    td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                                    td_2: {text: tlm.stringRegistry._<?= $h("No. Dokumen Perencanaan") ?>},
                                    td_3: {text: tlm.stringRegistry._<?= $h("Tanggal Perencanaan") ?>},
                                    td_4: {text: tlm.stringRegistry._<?= $h("No. SPK/Kontrak") ?>},
                                    td_5: {text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                                    td_6: {text: tlm.stringRegistry._<?= $h("Anggaran") ?>},
                                    td_7: {text: tlm.stringRegistry._<?= $h("Bulan Anggaran") ?>},
                                    td_8: {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                                }
                            }
                        }
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {numToMonthName: nToS, preferInt} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const bulanAwalAnggaran1Fld = divElm.querySelector(".bulanAwalAnggaran1Fld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaran1Fld = divElm.querySelector(".bulanAkhirAnggaran1Fld");
        /** @type {HTMLSelectElement} */ const tahunAnggaran1Fld = divElm.querySelector(".tahunAnggaran1Fld");
        /** @type {HTMLInputElement} */  const statusKontrakFld = divElm.querySelector(".statusKontrakFld");
        /** @type {HTMLInputElement} */  const formatFld = divElm.querySelector(".formatFld");
        /** @type {HTMLDivElement} */    const printAreaBlk = divElm.querySelector(".printAreaBlk");

        /** @type {HTMLSelectElement} */ const bulanAwalAnggaran2Fld = divElm.querySelector(".bulanAwalAnggaran2Fld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaran2Fld = divElm.querySelector(".bulanAkhirAnggaran2Fld");
        /** @type {HTMLSelectElement} */ const tahunAnggaran2Fld = divElm.querySelector(".tahunAnggaran2Fld");

        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaran1Fld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaran1Fld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaran1Fld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaran2Fld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaran2Fld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaran2Fld);
        this._selects.push(bulanAwalAnggaran1Fld, bulanAkhirAnggaran1Fld, tahunAnggaran1Fld);
        this._selects.push(bulanAwalAnggaran2Fld, bulanAkhirAnggaran2Fld, tahunAnggaran2Fld);

        const saring1Wgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saring1Frm"),
            /** @param {his.FatmaPharmacy.views.Perencanaan.Reports.Form1Fields} data */
            loadData(data) {
                bulanAwalAnggaran1Fld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaran1Fld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaran1Fld.value = data.tahunAnggaran ?? "";
                statusKontrakFld.value = data.statusKontrak ?? "";
                formatFld.value = data.format ?? "";
            },
            submit() {
                $.post({
                    url: "<?= $reportUrl ?>",
                    data: {
                        bulanAwalAnggaran: bulanAwalAnggaran1Fld.value,
                        bulanAkhirAnggaran: bulanAkhirAnggaran1Fld.value,
                        tahunAnggaran: tahunAnggaran1Fld.value,
                        statusKontrak: statusKontrakFld.value,
                        format: formatFld.value,
                    },
                    success(data) {printAreaBlk.innerHTML = data}
                });
            }
        });

        const saring2Wgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saring2Frm"),
            /** @param {his.FatmaPharmacy.views.Perencanaan.Reports.Form2Fields} data */
            loadData(data) {
                bulanAwalAnggaran2Fld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaran2Fld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaran2Fld.value = data.tahunAnggaran ?? "";
                idKatalogWgt.value = data.idKatalog ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        bulanAwalAnggaran: bulanAwalAnggaran2Fld.value,
                        bulanAkhirAnggaran: bulanAkhirAnggaran2Fld.value,
                        tahunAnggaran: tahunAnggaran2Fld.value,
                        idKatalog: idKatalogWgt.value,
                    }
                });
            }
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            labelField: "namaSediaan",
            searchField: ["idKatalog", "namaSediaan"],
            /** @param {his.FatmaPharmacy.views.Perencanaan.Reports.KatalogFields} item */
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

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            idField: "kodeReferensi",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {formatter(unused, item) {
                    const {noDokumenPerencanaan, kodeReferensi} = item;
                    return draw({class: ".viewBtn", value: kodeReferensi, text: noDokumenPerencanaan});
                }},
                3: {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                4: {field: "noPl"},
                5: {field: "namaPemasok"},
                6: {field: "subjenisAnggaran"},
                7: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (val == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                8: {field: "nilaiAkhir", formatter: tlm.floatFormatter},
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewBtn.value}, true);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saring1Wgt, saring2Wgt, idKatalogWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, this);
    }

    show() {
        // TODO: js: uncategorized: implement this method (copy from spl.InputWidget)
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
