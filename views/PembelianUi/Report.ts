<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PembelianUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/reports.php the original file
 */
final class Report
{
    private string $output;

    public function __construct(
        string $registerId,
        string $action1Url,
        string $action2Url,
        string $action3Url,
        string $plAcplUrl,
        string $katalogAcplUrl,
        string $jenisAnggaranSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pembelian.Reports {
    export interface Form1Fields {
        kodePl: "kode";
        format: "format";
    }

    export interface Form2Fields {
        namaSediaan: "nama_sediaan";
        idKatalog:   "id_katalog";
        printer:     "pilihan_print";
    }

    export interface Form3Fields {
        subjenisAnggaran: "subjenis_anggaran";
        kodePl:           "kode";
        pemasok:          ""; // MISSING ATTR name
        idJenisAnggaran:  "id_jenisanggaran";
        idJenisHarga:     "id_jenisharga";
        tanggalAwal:      "tgl_mulai";
        tanggalAkhir:     "tgl_akhir";
        idSubSumberDana:  "id_subsumberdana";
        format:           "format";
    }

    export interface PlFields {
        idPemasok:          "id_pbf";
        namaPemasok:        "nama_pbf";
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        tipeDokumen:        "tipe_doc";
        idJenisHarga:       "id_jenisharga";
        noDokumen:          "no_doc";
        subjenisAnggaran:   "subjenis_anggaran";
    }

    export interface KatalogFields {
        kode:           "kode";
        isiKemasan:     "isi_kemasan";
        idKemasan:      "id_kemasan";
        idKemasanDepo:  "id_kemasandepo";
        satuan:         "satuan";
        satuanJual:     "satuanjual";
        idKatalog:      "id_katalog";
        namaSediaan:    "nama_sediaan";
        namaPabrik:     "nama_pabrik";
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Pembelian") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        tab: {
            pane_1: {
                title: tlm.stringRegistry._<?= $h("Realisasi Barang per PL") ?>,
                form: {
                    class: ".form1Frm",
                    row_1: {
                        box: {
                            title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                            hidden: {name: "submit", value: "realisasi_pl"},
                            formGroup_1: {
                                label: tlm.stringRegistry._<?= $h("Kode PL") ?>,
                                input: {class: ".kodePl1Fld", name: "kodePl"}
                            },
                            formGroup_2: {
                                label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                                select: {
                                    class: ".format1Fld",
                                    name: "format",
                                    option_1: {value: "index_realisasipl", label: tlm.stringRegistry._<?= $h("Rekapitulasi Realisasi Pengadaan Barang") ?>},
                                    option_2: {value: "print_realisasipl", label: tlm.stringRegistry._<?= $h("Rekapitulasi Realisasi Pengadaan Barang Per PL") ?>},
                                }
                            }
                        }
                    },
                    row_2: {
                        column: {
                            class: "text-center",
                            SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                        }
                    }
                },
                row_1: {
                    paragraph: {text: "&nbsp;"}
                },
                row_2: {
                    widthColumn: {class: ".printArea1Blk"}
                }
            },
            pane_2: {
                title: tlm.stringRegistry._<?= $h("Search No. PL Item/Katalog") ?>,
                form: {
                    class: ".form2Frm",
                    row_1: {
                        box: {
                            title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                            hidden_1: {name: "submit", value: "pl_item"},
                            hidden_2: {class: ".namaSediaanFld", name: "namaSediaan"},
                            formGroup_1: {
                                label: tlm.stringRegistry._<?= $h("Nama Barang") ?>,
                                select: {class: ".idKatalogFld", name: "idKatalog"}
                            },
                            formGroup_2: {
                                label: tlm.stringRegistry._<?= $h("Printer") ?>,
                                select: {
                                    class: ".printerFld", // id=pilihan_print
                                    name: "printer",
                                    option_1: {value: "1", label: tlm.stringRegistry._<?= $h("LX-300") ?>},
                                    option_2: {value: "2", label: tlm.stringRegistry._<?= $h("Lasert jet") ?>},
                                    option_3: {value: "3", label: tlm.stringRegistry._<?= $h("LQ-2180") ?>},
                                }
                            }
                        }
                    },
                    row_2: {
                        column: {
                            class: "text-center",
                            SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                        }
                    }
                },
                row_1: {
                    paragraph: {text: "&nbsp;"}
                },
                row_2: {
                    widthColumn: {class: ".printArea2Blk"}
                }
            },
            pane_3: {
                title: tlm.stringRegistry._<?= $h("Laporan Akhir PL Pembelian") ?>,
                form: {
                    class: ".form3Frm",
                    row_1: {
                        box: {
                            title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                            hidden_1: {name: "submit", value: "laporan_akhir"},
                            hidden_2: {class: ".subjenisAnggaranFld", name: "subjenisAnggaran"},
                            formGroup_1: {
                                label: tlm.stringRegistry._<?= $h("Kode PL") ?>,
                                input: {class: ".kodePl3Fld", name: "kodePl"}
                            },
                            formGroup_2: {
                                label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                                input: {class: ".pemasokFld", name: "pemasok"} // MISSING ATTR name
                            },
                            formGroup_3: {
                                label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                                select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                            },
                            formGroup_4: {
                                label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                                select: {
                                    class: ".idJenisHargaFld",
                                    name: "idJenisHarga",
                                    option_1: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("E-Katalog & Non E-Katalog") ?>},
                                    option_2: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("E-Katalog") ?>, disabled: true},
                                    option_3: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Non E-Katalog") ?>, disabled: true},
                                }
                            },
                            formGroup_5: {
                                label: tlm.stringRegistry._<?= $h("Tanggal Awal Kontrak") ?>,
                                input: {class: ".tanggalAwalFld", name: "tanggalAwal"}
                            },
                            formGroup_6: {
                                label: tlm.stringRegistry._<?= $h("Tanggal Akhir Kontrak") ?>,
                                input: {class: ".tanggalAkhirFld", name: "tanggalAkhir"}
                            },
                            formGroup_7: {
                                label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                                select: {
                                    class: ".idSubSumberDanaFld",
                                    name: "idSubSumberDana",
                                    option_1: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa APBN & Dipa PNBP") ?>},
                                    option_2: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa APBN") ?>, disabled: true},
                                    option_3: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa PNBP") ?>, disabled: true},
                                }
                            },
                            formGroup_8: {
                                label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                                select: {
                                    class: ".format3Fld",
                                    name: "format",
                                    option_1: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Laporan Akhir PL") ?>},
                                    option_2: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Laporan Kontrak/SPK/SP") ?>, disabled: true}
                                }
                            }
                        }
                    },
                    row_2: {
                        column: {
                            class: "text-center",
                            SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                        }
                    }
                },
                row_1: {
                    paragraph: {text: "&nbsp;"}
                },
                row_2: {
                    widthColumn: {class: ".printArea3Blk"}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {numToMonthName: nToS, stringRegistry: str, preferInt} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodePl1Fld = divElm.querySelector(".kodePl1Fld");
        /** @type {HTMLSelectElement} */ const format1Fld = divElm.querySelector(".format1Fld");
        /** @type {HTMLDivElement} */    const printArea1Blk = divElm.querySelector(".printArea1Blk");

        /** @type {HTMLInputElement} */  const namaSediaanFld = divElm.querySelector(".namaSediaanFld");
        /** @type {HTMLSelectElement} */ const idKatalogFld = divElm.querySelector(".idKatalogFld");
        /** @type {HTMLSelectElement} */ const printerFld = divElm.querySelector(".printerFld");
        /** @type {HTMLDivElement} */    const printArea2Blk = divElm.querySelector(".printArea1Blk");

        /** @type {HTMLInputElement} */  const subjenisAnggaranFld = divElm.querySelector(".subjenisAnggaranFld");
        /** @type {HTMLInputElement} */  const kodePl3Fld = divElm.querySelector(".kodePl3Fld");
        /** @type {HTMLInputElement} */  const pemasokFld = divElm.querySelector(".pemasokFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLInputElement} */  const tanggalAwalFld = divElm.querySelector(".tanggalAwalFld");
        /** @type {HTMLInputElement} */  const tanggalAkhirFld = divElm.querySelector(".tanggalAkhirFld");
        /** @type {HTMLSelectElement} */ const idSubSumberDanaFld = divElm.querySelector(".idSubSumberDanaFld");
        /** @type {HTMLSelectElement} */ const format3Fld = divElm.querySelector(".format3Fld");
        /** @type {HTMLDivElement} */    const printArea3Blk = divElm.querySelector(".printArea1Blk");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        this._selects.push(idJenisAnggaranFld);

        const form1Wgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".form1Frm"),
            /** @param {his.FatmaPharmacy.views.Pembelian.Reports.Form1Fields} data */
            loadData(data) {
                kodePl1Fld.value = data.kodePl ?? "";
                format1Fld.value = data.format ?? "";
            },
            submit() {
                $.post({
                    url: "<?= $action1Url ?>",
                    data: {
                        kodePl: kodePl1Fld.value,
                        format: format1Fld.value,
                    },
                    success(data) {
                        printArea1Blk.innerHTML = data;
                    }
                });
            }
        });

        const form2Wgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".form2Frm"),
            /** @param {his.FatmaPharmacy.views.Pembelian.Reports.Form2Fields} data */
            loadData(data) {
                namaSediaanFld.value = data.namaSediaan ?? "";
                idKatalogFld.value = data.idKatalog ?? "";
                printerFld.value = data.printer ?? "";
            },
            submit() {
                $.post({
                    url: "<?= $action2Url ?>",
                    data: {
                        namaSediaan: namaSediaanFld.value,
                        idKatalog: idKatalogFld.value,
                        printer: printerFld.value,
                    },
                    success(data) {
                        printArea2Blk.innerHTML = data;
                    }
                });
            }
        });

        const form3Wgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".form3Frm"),
            /** @param {his.FatmaPharmacy.views.Pembelian.Reports.Form3Fields} data */
            loadData(data) {
                subjenisAnggaranFld.value = data.subjenisAnggaran ?? "";
                kodePl3Fld.value = data.kodePl ?? "";
                pemasokFld.value = data.pemasok ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                tanggalAwalFld.value = data.tanggalAwal ?? "";
                tanggalAkhirFld.value = data.tanggalAkhir ?? "";
                idSubSumberDanaFld.value = data.idSubSumberDana ?? "";
                format3Fld.value = data.format ?? "";
            },
            submit() {
                $.post({
                    url: "<?= $action3Url ?>",
                    data: {
                        subjenisAnggaran: subjenisAnggaranFld.value,
                        kodePl: kodePl3Fld.value,
                        pemasok: pemasokFld.value,
                        idJenisAnggaran: idJenisAnggaranFld.value,
                        idJenisHarga: idJenisHargaFld.value,
                        tanggalAwal: tanggalAwalFld.value,
                        tanggalAkhir: tanggalAkhirFld.value,
                        idSubSumberDana: idSubSumberDanaFld.value,
                        format: format3Fld.value,
                    },
                    success(data) {
                        printArea3Blk.innerHTML = data;
                    }
                });
            }
        });

        const kodePl1Wgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodePl1Fld"),
            maxItems: 100,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pembelian.Reports.PlFields} item */
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
            /** @param {his.FatmaPharmacy.views.Pembelian.Reports.PlFields} item */
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
                    return
                }

                $.post({
                    url: "<?= $plAcplUrl ?>",
                    data: {
                        dataqFilter: "sql",
                        dataFilter: {
                            transaksif_pembelian: {
                                noDokumen: {opt: "LIKE", val: typed},
                                statusDeleted: {opt: "=", val: "0"}
                            }
                        },
                        dataExtention: {limit: 30}
                    },
                    error() {processor([])},
                    success(response) {processor(response)}
                });
            }
        });

        const kodePl3Wgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodePl3Fld"),
            maxItems: 100,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pembelian.Reports.PlFields} item */
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
            /** @param {his.FatmaPharmacy.views.Pembelian.Reports.PlFields} item */
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
                    return
                }

                $.post({
                    url: "<?= $plAcplUrl ?>",
                    data: {
                        dataqFilter: "sql",
                        dataFilter: {
                            transaksif_pembelian: {
                                noDokumen: {opt: "LIKE", val: typed},
                                statusDeleted: {opt: "=", val: "0"}
                            }
                        },
                        dataExtention: {limit: 30}
                    },
                    error() {processor([])},
                    success(response) {processor(response)}
                });
            }
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            labelField: "namaSediaan",
            searchField: ["namaSediaan", "kode"],
            /** @param {his.FatmaPharmacy.views.Pembelian.Reports.KatalogFields} item */
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
                this.clearOptions();
                this.clearCache();

                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $katalogAcplUrl ?>",
                    data: {
                        dataSelect: "search",
                        dataqFilter: "sql",
                        dataQuery: typed,
                        dataExtention: {limit: 30},
                        dataFilter: {
                            A: {
                                sts_aktif: {opt: "=", val: "1"}
                            }
                        }
                    },
                    error() {processor([])},
                    success(response) {processor(response)}
                });
            },
            onItemAdd(value) {
                const obj = this._options[value];
                namaSediaanFld.value = obj.namaSediaan;
            }
        });

        const tanggalAwalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAwalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalAkhirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAkhirFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        idJenisAnggaranFld.addEventListener("change", () => {
            subjenisAnggaranFld.value = idJenisAnggaranFld.selectedOptions[0].innerHTML;
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(form1Wgt, form2Wgt, form3Wgt, kodePl1Wgt, kodePl3Wgt, idKatalogWgt, tanggalAwalWgt, tanggalAkhirWgt);
        tlm.app.registerWidget(this.constructor.widgetName, this);
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
