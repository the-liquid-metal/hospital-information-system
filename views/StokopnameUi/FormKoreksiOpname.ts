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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/koreksiopname.php the original file
 * @TODO: js: uncategorized: review add row on bulk input instance. it is not the same as the original source
 */
final class FormKoreksiOpname
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $dataUrl,
        string $actionUrl,
        string $katalogAcplUrl,
        string $katalogFloorUrl,
        string $printWidgetId,
        string $kembaliWidgetId,
        string $tableWidgetId,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Stokopname.KoreksiOpname {
    export interface FormFields {
        kodeTransaksi:        string;
        idUnit:               string;
        noDokumen:            string;
        tanggalDokumen:       string;
        verifikasiFloorStock: string;
        verUserFloorStock:    string;
        verTanggalFloorStock: string;
        daftarKetersediaan:   Array<KetersediaanFields>;
    }

    export interface KetersediaanFields {
        namaBarang:        string;
        idBarang:          string;
        namaPabrik:        "___"; // exist but missing
        namaSatuan:        "___"; // exist but missing
        jumlahPersediaan:  string;
        tanggalKadaluarsa: string;
        keterangan:        string;
        hargaSatuan:       string;
    }

    export interface BarangInnerFields {
        id:               string;
        namaSediaan:      string;
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
                _suffixes_2: ["[readonly]", "[disabled]"],
                _style_2:    {backgroundColor: "#065C5C", color: "#FFF"},
            }
        }
    };

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Koreksi Stock") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".koreksiOpnameFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
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
                            td_7: {text: tlm.stringRegistry._<?= $h("Keterangan") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Nilai Satuan (Rp.)") ?>},
                            td_9: {text: tlm.stringRegistry._<?= $h("Nilai Total (Rp.)") ?>},
                            td_10: {text: ""}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {class: ".no"},
                            td_2: {
                                // note: potentially merge text and hidden into select
                                input: {class: ".namaBarangFld", name: "namaBarang[]"},
                                hidden: {class: ".idBarangFld", name: "idBarang[]"}
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
                                textarea: {class: ".keteranganFld", name: "keterangan[]"}
                            },
                            td_8: {class: ".hargaSatuanStc"},
                            td_9: {class: ".jumlahSatuanStc"},
                            td_10: {
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
        }
    };

    constructor(divElm) {
        super();
        const {toSystemNumber: sysNum, toCurrency: currency, toUserFloat: userFloat, stringRegistry: str, nowVal} = tlm;
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const notVerifyStr = "------ (00-00-0000 00:00:00)";
        const tr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodeTransaksiFld = divElm.querySelector(".kodeTransaksiFld");
        /** @type {HTMLSelectElement} */ const idUnitFld = divElm.querySelector(".idUnitFld");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const tanggalDokumenFld = divElm.querySelector(".tanggalDokumenFld");
        /** @type {HTMLInputElement} */  const verifikasiFloorStockFld = divElm.querySelector(".verifikasiFloorStockFld");
        /** @type {HTMLDivElement} */    const verifikasiFloorStockStc = divElm.querySelector(".verifikasiFloorStockStc");
        /** @type {HTMLDivElement} */    const grandTotalStc = divElm.querySelector(".grandTotalStc");

        tlm.app.registerSelect("_<?= $depoSelect ?>", idUnitFld);
        this._selects.push(idUnitFld);

        let canceledState;

        const koreksiOpnameWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".koreksiOpnameFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Stokopname.KoreksiOpname.FormFields} data */
            loadData(data) {
                kodeTransaksiFld.value = data.kodeTransaksi ?? "KODE TRN";
                idUnitFld.value = data.idUnit ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                verifikasiFloorStockFld.checked = !!data.verifikasiFloorStock;
                verifikasiFloorStockStc.innerHTML = data.verifikasiFloorStock ? `${data.verUserFloorStock} (${data.verTanggalFloorStock})` : notVerifyStr;

                itemWgt.loadData(data.daftarKetersediaan);
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
            itemWgt.querySelectorAll(".jumlahSatuanStc").forEach(item => grandTotal += sysNum(item.innerHTML));
            grandTotalStc.innerHTML = currency(grandTotal);
        }

        /** @param {HTMLTableRowElement} trElm */
        function hitungSubTotal(trElm) {
            const fields = trElm.fields;
            const jumlahPersediaan = sysNum(fields.jumlahPersediaanFld.value) || 0;
            const hargaSatuan = sysNum(fields.hargaSatuanStc.innerHTML) || 0;

            fields.jumlahSatuanStc.innerHTML = currency(jumlahPersediaan * hargaSatuan);
        }

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            ...tlm.dateWidgetSetting
        });

        verifikasiFloorStockFld.addEventListener("click", () => {
            const vu = verifikasiFloorStockFld.dataset.vuser;
            const vt = nowVal("user");
            verifikasiFloorStockStc.innerHTML = verifikasiFloorStockFld.checked ? `${vu} (${vt})` : notVerifyStr;
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Stokopname.KoreksiOpname.KetersediaanFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.namaBarangWgt.value = data.namaBarang ?? "";
                fields.idBarangFld.value = data.idBarang ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.namaSatuanStc.innerHTML = data.namaSatuan ?? "";
                fields.jumlahPersediaanFld.value = data.jumlahPersediaan ?? "";
                fields.tanggalKadaluarsaWgt.value = data.tanggalKadaluarsa ?? "";
                fields.keteranganFld.value = data.keterangan ?? "";
                fields.hargaSatuanStc.innerHTML = data.hargaSatuan ?? "";
                fields.jumlahSatuanStc.innerHTML = (data.jumlahPersediaan * data.hargaSatuan) ?? "";
            },
            addRow(trElm) {
                /** @type {HTMLInputElement} */  const idBarangFld = trElm.querySelector(".idBarangFld");
                /** @type {HTMLSelectElement} */ const namaBarangFld = trElm.querySelector(".namaBarangFld");
                /** @type {HTMLInputElement} */  const namaPabrikStc = trElm.querySelector(".namaPabrikStc");
                /** @type {HTMLInputElement} */  const namaSatuanStc = trElm.querySelector(".namaSatuanStc");
                /** @type {HTMLInputElement} */  const jumlahPersediaanFld = trElm.querySelector(".jumlahPersediaanFld");
                /** @type {HTMLInputElement} */  const keteranganFld = trElm.querySelector(".keteranganFld");
                /** @type {HTMLInputElement} */  const hargaSatuanStc = trElm.querySelector(".hargaSatuanStc");
                /** @type {HTMLInputElement} */  const jumlahSatuanStc = trElm.querySelector(".jumlahSatuanStc");
                /** @type {HTMLInputElement} */  const tanggalKadaluarsaFld = trElm.querySelector(".tanggalKadaluarsaFld");

                const namaBarangWgt = new spl.SelectWidget({
                    element: namaBarangFld,
                    errorRules: [{required: true}],
                    maxItems: 1,
                    valueField: "kode",
                    /** @param {his.FatmaPharmacy.views.Stokopname.KoreksiOpname.BarangFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaSediaan} (${data.kode}) - ${data.namaPabrik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.Stokopname.KoreksiOpname.BarangFields} data */
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
                        /** @type {his.FatmaPharmacy.views.Stokopname.KoreksiOpname.BarangFields} */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $katalogFloorUrl ?>",
                            data: {idKatalog: obj.kode},
                            /** @param {his.FatmaPharmacy.views.Stokopname.KoreksiOpname.BarangInnerFields} data */
                            success(data) {
                                if (!data) return;

                                namaBarangFld.value = data.namaSediaan;
                                namaBarangFld.setAttribute("id_barang", data.id);

                                idBarangFld.value = (data.id);

                                namaPabrikStc.innerHTML = data.namaPabrik;
                                namaPabrikStc.setAttribute("id_pabrik", data.idPabrik);

                                namaSatuanStc.innerHTML = data.kodeKemasanKecil;
                                namaSatuanStc.setAttribute("id_kemasankecil", data.idKemasanKecil);

                                jumlahPersediaanFld.value = "0";
                                jumlahPersediaanFld.disabled = false;
                                jumlahPersediaanFld.dispatchEvent(new Event("focus"));

                                tanggalKadaluarsaFld.disabled = false;
                                keteranganFld.disabled = false;
                                hargaSatuanStc.innerHTML = currency(data.hpItem);
                                jumlahSatuanStc.innerHTML = userFloat(0);

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
                    namaPabrikStc,
                    namaSatuanStc,
                    jumlahPersediaanFld,
                    keteranganFld,
                    hargaSatuanStc,
                    jumlahSatuanStc,
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
            }
        });

        itemWgt.addDelegateListener("tbody", "blur", (event) => {
            const jumlahPersediaanFld = event.target;
            if (!jumlahPersediaanFld.matches(".jumlahPersediaanFld")) return;

            hitungSubTotal(closest(jumlahPersediaanFld, "tr"));
            hitungTotal();
        });

        divElm.querySelector(".kembalikanBtn").addEventListener("click", () => {
            if (!canceledState) return;

            noDokumenFld.readOnly = false;
            tanggalDokumenFld.readOnly = false;
            idUnitFld.disabled = false;
            verifikasiFloorStockFld.disabled = false;

            // TODO: js: uncategorized: change to spl.BulkInputWidget#addRow() (or something...) and anable all input
            itemWgt.querySelector("tbody").innerHTML = tr("tbody", {
                td_1: {class: ".no"},
                td_2: {
                    hidden: {class: "ib", name: "idBarang[]"},
                    input: {class: "id_katalog nb", "data-id_barang": "", name: "namaBarang[]"}
                },
                td_3: {class: "np"},
                td_4: {class: "ns"},
                td_5: {
                    class: "jp",
                    input: {class: "jp", name: "jumlahPersediaan[]", disabled: true}
                },
                td_6: {
                    input: {class: "exp2", name: "tanggalKadaluarsa[]", disabled: true}
                },
                td_7: {
                    textarea: {class: "keterangan", name: "keterangan[]", disabled: true}
                },
                td_8: {class: "hs", text: 0},
                td_9: {class: "nt", text: 0},
                td_10: {text: ""},
            });
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
                td_7: {text: "-"},
                td_8: {text: "0"},
                td_9: {text: "0"},
            });
            canceledState = true;
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kodeRef: kodeTransaksiFld.value}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $kembaliWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(koreksiOpnameWgt, tanggalDokumenWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, koreksiOpnameWgt);
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
