<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\StokopnameUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/floorstock.php the original file
 * @TODO: js: uncategorized: review add row on bulk input instance. it is not the same as the original source
 */
final class FormFloorStock
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $dataUrl,
        string $actionUrl,
        string $katalogAcplUrl,
        string $katalogFloorUrl,
        string $printFloorStockUrl,
        string $kembaliWidgetId,
        string $tableWidgetId,
        string $unitSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Stokopname.FloorStock {
    export interface FormFields {
        idFloorStock:         string;
        kodeTransaksi:        string;
        idUnit:               string;
        noDokumen:            string;
        tanggalDokumen:       string;
        verifikasiFloorStock: string;
        verUserFloorStock:    string;
        verTanggalFloorStock: string;
        daftarFloorStock:     Array<FloorStockFields>;
    }

    export interface FloorStockFields {
        namaBarang:        string;
        idBarang:          string;
        noDokumen:         string;
        idEdit:            string;
        namaPabrik:        string;
        namaSatuan:        string;
        jumlahPersediaan:  string;
        tanggalKadaluarsa: string;
        hargaSatuan:       string;
    }

    export interface BarangInnerFields {
        id:               string;
        namaSediaan:      string;
        kode:             string; // damn!
        namaPabrik:       string;
        idPabrik:         string;
        kodeKemasanKecil: string;
        idKemasanKecil:   string;
        hpItem:           string;
    }

    export interface BarangFields {
        formulariumNas: string;
        formulariumRs:  string;
        kode:           string;
        namaPabrik:     string;
        namaSediaan:    string;
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

    static style = {
        [this.widgetName]: {
            ".form-control": {
                _suffixes_1: [""],
                _style_1:    {backgroundColor: "#faffbd", color: "#000"},
                _suffixes_2: [":read-only", ":disabled"],
                _style_2:    {backgroundColor: "#065C5C", color: "#FFF"},
            }
        }
    };

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Stock Opname Floorstock") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".floorStockFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".idFloorStockFld", name: "idFloorStock"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeTransaksiFld", name: "kodeTransaksi"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Unit Floor") ?>,
                        select: {class: ".idUnitFld", name: "idUnit"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verifikasiFloorStockFld" name="verifikasiFloorStock" value="1"/>
                            <span class="verifikasiFloorStockStc"></span>
                        `}
                    }
                }
            },
            row_3: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Barang") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Jumlah Persediaan") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Nilai Satuan (Rp.)") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Nilai Total (Rp.)") ?>},
                            td_9: {text: ""}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {class: ".no"},
                            td_2: {
                                // note: potentially merge text and hidden into select
                                input: {class: ".namaBarangFld", name: "namaBarang[]"},
                                hidden_1: {class: ".idBarangFld", name: "idBarang[]"},
                                hidden_2: {class: ".noDokumenFld", name: "noDokumen[]"},
                                hidden_3: {class: ".idEditFld", name: "idEdit[]"}
                            },
                            td_3: {class: ".namaPabrikStc"},
                            td_4: {class: ".namaSatuanStc"},
                            td_5: {
                                input: {class: ".jumlahPersediaanFld", name: "jumlahPersediaan[]"}
                            },
                            td_6: {
                                input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                            },
                            td_7: {
                                input: {class: ".hargaSatuanFld", name: "hargaSatuan[]"}
                            },
                            td_8: {class: ".hargaTotalStc"},
                            td_9: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Grand Total:") ?>},
                            td_2: {class: ".grandTotalStc"},
                            td_3: {
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
            },
            row_5: {
                column: {
                    button_1: {class: ".kembalikanBtn", text: tlm.stringRegistry._<?= $h("Kembalikan") ?>},
                    button_2: {class: ".batalkanBtn",   text: tlm.stringRegistry._<?= $h("Batalkan") ?>},
                    button_3: {class: ".cetakBtn",      text: tlm.stringRegistry._<?= $h("Cetak") ?>},
                    button_4: {class: ".kembaliBtn",    text: tlm.stringRegistry._<?= $h("Kembali") ?>},
                }
            }
        },
        row_3: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_4: {
            widthColumn: {class: ".printAreaBlk"}
        },
    };

    constructor(divElm) {
        super();
        const {toCurrency: currency, toUserFloat: userFloat, toSystemNumber: sysNum, stringRegistry: str, nowVal} = tlm;
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const notVerifyStr = "------ (00-00-0000 00:00:00)";
        const tr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const idFloorStockFld = divElm.querySelector(".idFloorStockFld");
        /** @type {HTMLInputElement} */  const kodeTransaksiFld = divElm.querySelector(".kodeTransaksiFld");
        /** @type {HTMLSelectElement} */ const idUnitFld = divElm.querySelector(".idUnitFld");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const tanggalDokumenFld = divElm.querySelector(".tanggalDokumenFld");
        /** @type {HTMLInputElement} */  const verifikasiFloorStockFld = divElm.querySelector(".verifikasiFloorStockFld");
        /** @type {HTMLDivElement} */    const verifikasiFloorStockStc = divElm.querySelector(".verifikasiFloorStockStc");
        /** @type {HTMLDivElement} */    const grandTotalStc = divElm.querySelector(".grandTotalStc");
        /** @type {HTMLDivElement} */    const printAreaBlk = divElm.querySelector(".printAreaBlk");

        tlm.app.registerSelect("_<?= $unitSelect ?>", idUnitFld);
        this._selects.push(idUnitFld);

        let canceledState = false;

        const floorStockWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".floorStockFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Stokopname.FloorStock.FormFields} data */
            loadData(data) {
                idFloorStockFld.value = data.idFloorStock ?? "";
                kodeTransaksiFld.value = data.kodeTransaksi ?? "";
                idUnitFld.value = data.idUnit ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                verifikasiFloorStockFld.checked = !!data.verifikasiFloorStock;
                verifikasiFloorStockStc.innerHTML = data.verifikasiFloorStock ? `${data.verUserFloorStock} (${data.verTanggalFloorStock})` : notVerifyStr;

                itemWgt.loadData(data.daftarFloorStock);
            },
            onBeforeSubmit() {
                return confirm(str._<?= $h("Setelah penyimpanan yang diverifikasi akan mengunci penginputan. Apakah Anda yakin ingin menyimpan?") ?>);
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    // TODO: js: uncategorized: finish this
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            onSuccessSubmit() {
                tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        function hitungTotal() {
            let grandTotal = 0;
            itemWgt.querySelectorAll(".hargaTotalStc").forEach(item => grandTotal += sysNum(item.innerHTML));
            grandTotalStc.innerHTML = currency(grandTotal);
        }

        /** @param {HTMLTableRowElement} trElm */
        function hitungSubTotal(trElm) {
            const fields = trElm.fields;
            const jumlahPersediaan = sysNum(fields.jumlahPersediaanFld.value) || 0;
            const hargaSatuan = sysNum(fields.hargaSatuanFld.value) || 0;

            fields.hargaTotalStc.innerHTML = currency(jumlahPersediaan * hargaSatuan);
        }

        verifikasiFloorStockFld.addEventListener("click", () => {
            const vu = verifikasiFloorStockFld.dataset.vuser;
            const vt = nowVal("user");
            verifikasiFloorStockStc.innerHTML = verifikasiFloorStockFld.checked ? `${vu} (${vt})` : notVerifyStr;
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            ...tlm.dateWidgetSetting
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Stokopname.FloorStock.FloorStockFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.namaBarangWgt.value = data.namaBarang ?? "";
                fields.idBarangFld.value = data.idBarang ?? "";
                fields.noDokumenFld.value = data.noDokumen ?? "";
                fields.idEditFld.value = data.idEdit ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.namaSatuanStc.innerHTML = data.namaSatuan ?? "";
                fields.jumlahPersediaanFld.value = data.jumlahPersediaan ?? "";
                fields.tanggalKadaluarsaWgt.value = data.tanggalKadaluarsa ?? "";
                fields.hargaSatuanFld.value = data.hargaSatuan ?? "";
                fields.hargaTotalStc.innerHTML = currency(data.jumlahPersediaan * data.hargaSatuan);
            },
            addRow(trElm) {
                /** @type {HTMLInputElement} */  const idBarangFld = trElm.querySelector(".idBarangFld");
                /** @type {HTMLSelectElement} */ const namaBarangFld = trElm.querySelector(".namaBarangFld");
                /** @type {HTMLInputElement} */  const namaPabrikStc = trElm.querySelector(".namaPabrikStc");
                /** @type {HTMLInputElement} */  const namaSatuanStc = trElm.querySelector(".namaSatuanStc");
                /** @type {HTMLInputElement} */  const jumlahPersediaanFld = trElm.querySelector(".jumlahPersediaanFld");
                /** @type {HTMLInputElement} */  const hargaSatuanFld = trElm.querySelector(".hargaSatuanFld");
                /** @type {HTMLInputElement} */  const hargaTotalStc = trElm.querySelector(".hargaTotalStc");
                /** @type {HTMLInputElement} */  const tanggalKadaluarsaFld = trElm.querySelector(".tanggalKadaluarsaFld");

                const namaBarangWgt = new spl.SelectWidget({
                    element: namaBarangFld,
                    errorRules: [{required: true}],
                    maxItems: 1,
                    valueField: "kode",
                    /** @param {his.FatmaPharmacy.views.Stokopname.FloorStock.BarangFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaSediaan} (${data.kode}) - ${data.namaPabrik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.Stokopname.FloorStock.BarangFields} data */
                    itemRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="item" style="color:${warna}">${data.namaSediaan} (${data.kode})</div>`;
                    },
                    load(typed, processor) {
                        if (!typed.length) {
                            processor([]);
                            return;
                        }

                        $.post({
                            url: "<?= $katalogAcplUrl ?>",
                            data: {q: typed},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    },
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.Stokopname.FloorStock.BarangFields} */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $katalogFloorUrl ?>",
                            data: {idKatalog: obj.kode},
                            /** @param {his.FatmaPharmacy.views.Stokopname.FloorStock.BarangInnerFields} data */
                            success(data) {
                                if (!data) return;

                                namaBarangFld.value = data.namaSediaan;
                                namaBarangFld.setAttribute("id_barang", data.id);

                                idBarangFld.value = data.kode;

                                namaPabrikStc.innerHTML = data.namaPabrik;
                                namaPabrikStc.setAttribute("id_pabrik", data.idPabrik);

                                namaSatuanStc.innerHTML = data.kodeKemasanKecil;
                                namaSatuanStc.setAttribute("id_kemasankecil", data.idKemasanKecil);

                                jumlahPersediaanFld.value = userFloat(0);
                                jumlahPersediaanFld.disabled = false;
                                jumlahPersediaanFld.dispatchEvent(new Event("focus"));

                                tanggalKadaluarsaFld.disabled = false;
                                hargaSatuanFld.value = currency(data.hpItem);
                                hargaTotalStc.innerHTML = currency(0);

                                hitungTotal();
                            }
                        });
                    }
                });

                const tanggalKadaluarsaWgt = new spl.DateTimeWidget({
                    element: tanggalKadaluarsaFld,
                    ...tlm.dateWidgetSetting
                });

                trElm.fields = {
                    namaBarangWgt,
                    tanggalKadaluarsaWgt,
                    idBarangFld,
                    noDokumenFld: trElm.querySelector(".noDokumenFld"),
                    idEditFld: trElm.querySelector(".idEditFld"),
                    namaPabrikStc,
                    namaSatuanStc,
                    jumlahPersediaanFld,
                    hargaSatuanFld,
                    hargaTotalStc,
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.namaBarangWgt.destroy();
                fields.tanggalKadaluarsaWgt.destroy();

                hitungTotal();
            },
            profile: {
                add() {
                    // TODO: js: uncategorized: finish this
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            addRowBtn: ".floorStockFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "blur", (event) => {
            const fieldElm = event.target;
            if (!fieldElm.matches(".jumlahPersediaanFld, .hargaSatuanFld")) return;

            hitungSubTotal(closest(fieldElm, "tr"));
            hitungTotal();
        });

        divElm.querySelector(".kembalikanBtn").addEventListener("click", () => {
            if (!canceledState) return;

            noDokumenFld.readOnly = false;
            tanggalDokumenFld.readOnly = false;
            verifikasiFloorStockFld.disabled = false;

            // TODO: js: uncategorized: change to spl.BulkInputWidget#addRow() (or something...) and anable all input
            itemWgt.querySelector("tbody").innerHTML = tr("tbody", {
                td_1: {text: ""},
                td_2: {
                    hidden_1: {class: "ib", name: "idBarang[]"},
                    hidden_2: {name: "idEdit[]", value: "0"},
                    input: {class: "id_katalog nb", "data-id_barang": "", name:"namaBarang[]"},
                },
                td_3: {class: "np"},
                td_4: {class: "ns"},
                td_5: {
                    input: {class: "jp", name: "jumlahPersediaan[]", disabled: true}
                },
                td_6: {
                    input: {class: "exp2", name: "tanggalKadaluarsa[]", disabled: true}
                },
                td_7: {
                    input: {class: "h_s", name: "hargaSatuan[]"}
                },
                td_8: {class: "nt", text: 0},
                td_9: {text: ""},
            });
            canceledState = false;
        });

        divElm.querySelector(".batalkanBtn").addEventListener("click", () => {
            if (canceledState || !confirm(str._<?= $h("Apakah Anda yakin ingin membatalkan?") ?>)) return;

            noDokumenFld.readOnly = true;
            tanggalDokumenFld.readOnly = true;
            idUnitFld.disabled = true;
            verifikasiFloorStockFld.disabled = true;

            // TODO: js: uncategorized: change to spl.BulkInputWidget#removeAll() (or something...) and disable all input
            itemWgt.querySelector("tbody").innerHTML = tr("tbody", {
                td_1: {text: "1"},
                td_2: {text: "-"},
                td_3: {text: "-"},
                td_4: {text: "-"},
                td_5: {text: "0"},
                td_6: {text: "-"},
                td_7: {text: "0"},
                td_8: {text: "0"},
                td_9: {text: ""},
            });
            canceledState = true;
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            $.post({
                url: "<?= $printFloorStockUrl ?>",
                data: {id: idFloorStockFld.value},
                success(html) {printAreaBlk.innerHTML = html}
            });
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $kembaliWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(floorStockWgt, tanggalDokumenWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, floorStockWgt);
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
