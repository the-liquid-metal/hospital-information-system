<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\TransaksiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/permintaan2.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class FormPermintaan2
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $addActionUrl,
        string $obatAcplUrl,
        string $hargaUrl,
        string $stokDataUrl,
        string $noDokumenUrl,
        string $printWidgetId,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.Permintaan2 {
    export interface FormFields {
        noDokumen: string|"in"|"out";
        peminta:   string|"in"|"out";
        prioritas: string|"in"|"out";
        diminta:   string|"in"|"out";
    }

    export interface TableFields {
        namaObat:     string|"in"|"out";
        kodeObat:     string|"in"|"out";
        jumlah:       string|"in"|"out";
        pabrik:       string|"in"|"x";
        stokTersedia: string|"in"|"x";
        satuan:       string|"in"|"x";
    }

    export interface ObatFields {
        namaBarang:     string;
        sinonim:        string; // missing in database/controller (generik ?)
        kode:           string;
        satuanKecil:    string;
        formulariumNas: string;
        formulariumRs:  string;
        namaPabrik:     string;
        stokFisik:      string;
    }

    export interface HargaFields {
        stok: string;
        harga: string;
    }

    export interface StokTableFields {
        namaDepo:        string;
        jumlahStokFisik: string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{add: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
        };
        const access = {};
        for (const item in pool) {
            if (!pool.hasOwnProperty(item)) continue;
            access[item] = pool[item][role] ?? false;
        }
        return access;
    }

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".transaksiPermintaanFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Permintaan Dari") ?>,
                        select: {class: ".pemintaFld", name: "peminta"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Prioritas") ?>,
                        select: {
                            class: ".prioritasFld",
                            name: "prioritas",
                            option_1: {value: "CITO",    label: tlm.stringRegistry._<?= $h("CITO") ?>},
                            option_2: {value: "Regular", label: tlm.stringRegistry._<?= $h("Regular") ?>}
                        }
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
                        staticText: {class: ".tanggalStc"},
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Diminta Kepada") ?>,
                        select: {class: ".dimintaFld", name: "diminta"}
                    }
                }
            },
            row_3: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Nama Obat") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Stok") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Action") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                input: {class: ".namaObatFld", name: "namaObat[]"},
                                hidden: {class: ".kodeObatFld", name: "kodeObat[]"},
                                rButton: {class: ".stokBtn"}
                            },
                            td_2: {
                                input: {class: ".pabrikFld", name: "pabrik[]"}
                            },
                            td_3: {
                                input: {class: ".stokTersediaFld", name: "stokTersedia[]"}
                            },
                            td_4: {
                                input: {class: ".jumlahFld", name: "jumlah[]"}
                            },
                            td_5: {
                                input: {class: ".satuanFld", name: "satuan[]"}
                            },
                            td_6: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {colspan: 5},
                            td_2: {
                                button: {class: ".addRowBtn", type: "success", size: "xs", label: tlm.stringRegistry._<?= $h("add") ?>}
                            }
                        }
                    }
                }
            },
            row_4: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        },
        modal: {
            row_1: {
                widthColumn: {class: ".headerElm"}
            },
            row_2: {
                widthTable: {
                    class: ".stokTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Depo") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                        }
                    }
                }
            },
            row_3: {
                widthColumn: {class: ".footerElm"}
            },
        }
    };

    constructor(divElm) {
        super();
        const {nowVal, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLSelectElement} */ const pemintaFld = divElm.querySelector(".pemintaFld");
        /** @type {HTMLSelectElement} */ const prioritasFld = divElm.querySelector(".prioritasFld");
        /** @type {HTMLDivElement} */    const tanggalStc = divElm.querySelector(".tanggalStc");
        /** @type {HTMLSelectElement} */ const dimintaFld = divElm.querySelector(".dimintaFld");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $h($depoSelect) ?>", dimintaFld);
        tlm.app.registerSelect("_<?= $h($depoSelect) ?>", pemintaFld);
        this._select.push(dimintaFld, pemintaFld);

        const transaksiPermintaanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".transaksiPermintaanFrm"),
            /** @param {his.FatmaPharmacy.views.Transaksi.Permintaan2.FormFields} data */
            loadData(data) {
                noDokumenFld.value = data.noDokumen ?? "";
                pemintaFld.value = data.peminta ?? "";
                prioritasFld.value = data.prioritas ?? "";
                tanggalStc.innerHTML = nowVal("user");
                dimintaFld.value = data.diminta ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._element.reset();
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.loadData({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Permintaan Barang") ?>;
                    itemWgt.loadProfile("add");

                    $.post({
                        url: "<?= $noDokumenUrl ?>",
                        success(data) {noDokumenFld.value = data}
                    });
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            onSuccessSubmit(event) {
                const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
                widget.show();
                widget.loadData({noPermintaan: event.data.noPermintaan}, true);
            },
            onFailSubmit() {
                alert(str._<?= $h("terjadi error") ?>);
            },
            resetBtnId: false,
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            addRow(trElm) {
                /** @type {HTMLInputElement} */ const kodeObatFld = trElm.querySelector(".kodeObatFld");
                /** @type {HTMLInputElement} */ const pabrikFld = trElm.querySelector(".pabrikFld");
                /** @type {HTMLInputElement} */ const stokTersediaFld = trElm.querySelector(".stokTersediaFld");

                const namaObatWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaObatFld"),
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.Transaksi.Permintaan2.ObatFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObatFld.value = data.kode ?? "";
                        pabrikFld.value = data.namaPabrik ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.Transaksi.Permintaan2.ObatFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.Transaksi.Permintaan2.ObatFields} data */
                    itemRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="item" style="color:${warna}">${data.namaBarang} (${data.kode})</div>`;
                    },
                    load(typed, processor) {
                        if (!typed.length) {
                            processor([]);
                            return;
                        }

                        $.post({
                            url: "<?= $obatAcplUrl ?>",
                            data: {q: typed},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    },
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.Transaksi.Permintaan2.ObatFields} */
                        const obj = this.options[value];
                        const id = "TODO ...";

                        const elm = trElm.querySelector("#cek_" + id);
                        elm.disabled = false;
                        elm.dataset.kode = obj.kode;

                        trElm.querySelector("#satuan_" + id).value = obj.satuanKecil;

                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode},
                            /** @param {his.FatmaPharmacy.views.Transaksi.Permintaan2.HargaFields} data */
                            success(data) {
                                trElm.querySelector("#obat_" + id).setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);
                                stokTersediaFld.value = data.stok;
                                hitungTotal();
                            }
                        });
                    }
                });

                trElm.fields = {
                    namaObatWgt,
                    stokTersediaFld,
                    jumlahFld: trElm.querySelector(".jumlahFld"),
                    satuanFld: trElm.querySelector(".satuanFld"),
                    stokBtn: trElm.querySelector(".stokBtn"),
                };
            },
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Transaksi.Permintaan2.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.namaObatWgt.value = data.namaObat ?? "";
                fields.stokTersediaFld.value = data.stokTersedia ?? "";
                fields.jumlahFld.value = data.jumlah ?? "";
                fields.satuanFld.value = data.satuan ?? "";
                fields.stokBtn.value = data.kodeObat ?? "";
            },
            onBeforeDeleteRow() {
                alert(str._<?= $h("Minimal terdapat 1 obat.") ?>);
            },
            profile: {
                add() {
                    // TODO: js: uncategorized: finish this
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            addRowBtn: ".transaksiPermintaanFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const stokBtn = event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            $.post({
                url: "<?= $stokDataUrl ?>",
                data: {id: stokBtn.value},
                success(data) {
                    const {stokKatalog, daftarStokKatalog} = data;
                    headerElm.innerHTML = `${stokKatalog.namaBarang} (${stokKatalog.idKatalog})`;
                    footerElm.innerHTML = "total: " + daftarStokKatalog.reduce((acc, curr) => acc + curr.jumlahStokFisik, 0);
                    stokWgt.load(daftarStokKatalog);
                }
            });
        });

        /** @see {his.FatmaPharmacy.views.Transaksi.Permintaan2.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(transaksiPermintaanWgt, itemWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, transaksiPermintaanWgt);
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
