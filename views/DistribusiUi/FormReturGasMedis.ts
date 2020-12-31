<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\DistribusiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Distribusi/returGM.php the original file
 */
final class FormReturGasMedis
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
        string $cekUnikNoKirimUrl,
        string $stockUrl,
        string $katalogAcplUrl,
        string $cariKatalog2Url,
        string $viewVidgetId,
        string $penyimpanan1Select,
        string $penyimpanan2Select,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis {
    export interface FormFields {
        action:          "action";
        submit:          "submit";
        tipeDokumen:     "tipe_doc";
        noTransaksi:     "kode";
        idPengirim:      "id_pengirim";
        unitPengirim:    "unit_pengirim";
        noKirim:         "no_doc";
        statusPrioritas: "sts_priority";
        tanggalDokumen:  "tgl_doc";
        idPenerima:      "id_penerima";
        unitPenerima:    "unit_penerima";
        verKirim:        "ver_kirim";
    }

    export interface TableFields {
        idKatalog:          "id_katalog[]";
        noUrut:             "no_urut[]";
        noBatch:            "no_batch[]";
        jumlahKemasan:      "jumlah_kemasan[]";
        jumlahItem:         "___", // exist but missing
        statusKetersediaan: "sts_ketersediaan[]";
        hargaPokok:         "___", // exist but missing
        hargaTotal:         "___", // exist but missing
        idPabrik:           "id_pabrik[]";
        isiKemasan:         "isi_kemasan[]";
        idKemasan:          "id_kemasan[]";
        idSatuan:           "id_satuan[]";
        hpItem:             "hp_item[]";
        hnaItem:            "hna_item[]";
        phjaItem:           "phja_item[]";
        hjaItem:            "hja_item[]";
        namaSediaan:        "___", // exist but missing
        namaPabrik:         "___", // exist but missing
        stock:              "___", // exist but missing
    }

    export interface AjaxFields {
        statusKetersediaan: "sts_ketersediaan";
        kemasanFisik:       "kemasan_fisik";
        noBatch:            "no_batch";
        tanggalKadaluarsa:  "tgl_expired";
        unitPemilik:        "unit_pemilik";
        jumlahFisik:        "jumlah_fisik";
    }

    export interface KatalogFields {
        idKatalog:   string;
        namaSediaan: string;
        namaPabrik:  string;
        satuan:      string;
        stokAdm:     string;
        kemasan:     string; // not used, but exist in controller
        idPabrik:    string; // not used, but exist in controller
        idKemasan:   string; // not used, but exist in controller
        isiKemasan:  string; // not used, but exist in controller
        idSatuan:    string; // not used, but exist in controller
        satuanJual:  string; // not used, but exist in controller
    }

    export interface BatchFields {
        idKatalog:   "id_katalog";
        noUrut:      "no_urut";
        noBatch:     "no_batch";
        unitPemilik: "unit_pemilik";
        jumlahAdm:   "jumlah_adm";
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".returGasMedisFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"}, // $data['action']
                    hidden_2: {class: ".submitFld", name: "submit", value: "save"}, // FROM SUBMIT BUTTON
                    hidden_3: {class: ".tipeDokumenFld", name: "tipeDokumen", value: 6},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Transaksi") ?>,
                        input: {class: ".noTransaksiFld", name: "noTransaksi"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Unit Pengirim") ?>,
                        select: {class: ".idPengirimFld", name: "idPengirim"},
                        hidden: {class: ".unitPengirimFld", name: "unitPengirim"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Kirim") ?>,
                        input: {class: ".noKirimFld", name: "noKirim"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Prioritas") ?>,
                        select: {
                            class: ".statusPrioritasFld",
                            name: "statusPrioritas",
                            option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Regular") ?>},
                            option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Cito") ?>},
                        }
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Unit Penerima") ?>,
                        select: {class: ".idPenerimaFld", name: "idPenerima"},
                        hidden: {class: ".unitPenerimaFld", name: "unitPenerima"}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".tabungTbl",
                    thead: {
                        tr_1: {
                            td_1:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Stok") ?>},
                            td_5:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_6:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Batch") ?>},
                            td_7:  {colspan: 3, text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                            td_8:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Harga Pokok") ?>},
                            td_9:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                            td_10: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Action") ?>},
                        },
                        tr_2: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Ketersediaan") ?>},
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1:  {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_2:  {class: ".noUrutFld", name: "noUrut[]"},
                                hidden_3:  {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_4:  {class: ".isiKemasanFld", name: "isiKemasan[]"},
                                hidden_5:  {class: ".idKemasanFld", name: "idKemasan[]"},
                                hidden_6:  {class: ".idSatuanFld", name: "idSatuan[]"},
                                hidden_7:  {class: ".hpItemFld", name: "hpItem[]"},
                                hidden_8:  {class: ".hnaItemFld", name: "hnaItem[]"},
                                hidden_9:  {class: ".phjaItemFld", name: "phjaItem[]"},
                                hidden_10: {class: ".hjaItemFld", name: "hjaItem[]"},
                                staticText: {class: ".no"}
                            },
                            td_2: {class: ".namaSediaanStc"},
                            td_3: {class: ".namaPabrikStc"},
                            td_4: {
                                staticText: {class: ".stockStc"},
                                rButton: {class: ".stockBtn"}
                            },
                            td_5: {class: ".noUrutStc"},
                            td_6: {
                                select: {class: ".noBatchFld", name: "noBatch[]"}
                            },
                            td_7: {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]"}
                            },
                            td_8: {class: ".jumlahItemStc"},
                            td_9: {
                                select: {
                                    class: ".statusKetersediaanFld",
                                    name: "statusKetersediaan[]",
                                    option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Kosong") ?>},
                                    option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Berisi") ?>},
                                }
                            },
                            td_10: {class: ".hargaPokokStc"},
                            td_11: {class: ".hargaTotalStc"},
                            td_12: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {class: "text-right", text: tlm.stringRegistry._<?= $h("TOTAL:") ?>, colspan: 8},
                            td_2: {class: ".grandTotalStc"},
                            td_3: {text: tlm.stringRegistry._<?= $h("Cari Katalog:") ?>},
                            td_4: {
                                select: {class: ".idKatalogFld"}
                            },
                            td_5: {
                                button: {class: ".addRowBtn", type: "success", size: "xs", label: tlm.stringRegistry._<?= $h("add") ?>}
                            }
                        }
                    }
                }
            },
            row_3: {
                widthTable: {
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Ver No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Ver") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Otorisasi") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("User") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Update Stok") ?>},
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {text: "1"},
                            td_2: {
                                checkbox: {class: ".verKirimFld", name: "verKirim", value: 1}      // $data['verkirim']  $disabled
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Gudang Gas Medis") ?>},
                            td_4: {class: ".userKirimStc"},                                         // $data['user_kirim']
                            td_5: {class: ".tanggalKirimStc"},                                      // $data['ver_tglkirim'] ? $toUserDatetime($data['ver_tglkirim']) : "-"
                            td_6: {
                                checkbox: {class: ".updateStokMarkerFld", value: 1, disabled: true} // $data['stokgudang']
                            },
                        }
                    }
                }
            },
            row_4: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Simpan") ?>}
                }
            }
        },
        modal: {
            row_1: {
                widthColumn: {class: ".headerElm"}
            },
            row_2: {
                widthTable: {
                    class: ".batchTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No. Batch Tabung") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Tanggal Kadaluarsa") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Pemilik Tabung") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Ketersediaan") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Stok Tabung") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Jumlah Satuan") ?>},
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
        const {toSystemNumber: sysNum, toUserDate: userDate, preferInt, nowVal} = tlm;
        const {toUserInt: userInt, toUserMoney: currency, stringRegistry: str} = tlm;
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        let batch;

        /** @type {HTMLInputElement} */  const submitFld = divElm.querySelector(".submitFld");
        /** @type {HTMLInputElement} */  const tipeDokumenFld = divElm.querySelector(".tipeDokumenFld");
        /** @type {HTMLInputElement} */  const noTransaksiFld = divElm.querySelector(".noTransaksiFld");
        /** @type {HTMLSelectElement} */ const idPengirimFld = divElm.querySelector(".idPengirimFld");
        /** @type {HTMLInputElement} */  const unitPengirimFld = divElm.querySelector(".unitPengirimFld");
        /** @type {HTMLSelectElement} */ const statusPrioritasFld = divElm.querySelector(".statusPrioritasFld");
        /** @type {HTMLSelectElement} */ const idPenerimaFld = divElm.querySelector(".idPenerimaFld");
        /** @type {HTMLInputElement} */  const unitPenerimaFld = divElm.querySelector(".unitPenerimaFld");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const verKirimFld = divElm.querySelector(".verKirimFld");
        /** @type {HTMLInputElement} */  const userKirimStc = divElm.querySelector(".userKirimStc");
        /** @type {HTMLInputElement} */  const tanggalKirimStc = divElm.querySelector(".tanggalKirimStc");
        /** @type {HTMLDivElement} */    const grandTotalStc = divElm.querySelector(".grandTotalStc");
        /** @type {HTMLInputElement} */  const updateStokMarkerFld = divElm.querySelector(".updateStokMarkerFld");
        /** @type {HTMLInputElement} */  const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLInputElement} */  const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $penyimpanan1Select ?>", idPengirimFld);
        tlm.app.registerSelect("_<?= $penyimpanan2Select ?>", idPenerimaFld);
        this._selects.push(idPengirimFld, idPenerimaFld);

        const returGasMedisWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".returGasMedisFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.FormFields} data */
            loadData(data) {
                submitFld.value = data.submit ?? "";
                tipeDokumenFld.value = data.tipeDokumen ?? "";
                noTransaksiFld.value = data.noTransaksi ?? "";
                idPengirimFld.value = data.idPengirim ?? "";
                unitPengirimFld.value = data.unitPengirim ?? "";
                noKirimWgt.value = data.noKirim ?? "";
                statusPrioritasFld.value = data.statusPrioritas ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idPenerimaFld.value = data.idPenerima ?? "";
                unitPenerimaFld.value = data.unitPenerima ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                edit() {
                    batch = JSON.parse(`<?= json_encode($batch ?? []) ?>`);
                    /** @type {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.BatchFields[]} */
                    const idata = JSON.parse(`<?= json_encode($idata ?? []) ?>`);
                    idata.forEach(obj => {
                        const idKatalog = obj.idKatalog;
                        divElm.querySelectorAll(`.noBatchFld[id_katalog="${idKatalog}"]`).forEach(/** @type {HTMLSelectElement} */item => {
                            if (obj.noUrut != closest(item, "tr").fields.noUrutFld.value) return;

                            // createBatch(item);
                            item.fieldWidget.addOption(batch[idKatalog]);
                            item.fieldWidget.value = obj.noBatch;
                        });

                        divElm.querySelectorAll(`.noUrutStc[data-par="${idKatalog}"]`).forEach((item, i) => item.innerHTML = i + 1);
                    });
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
            onBeforeSubmit() {
                let c = false;
                if (!verKirimFld.checked) {
                    const str1 = str._<?= $h("Apakah Anda yakin ingin menyimpan?") ?>;
                    const str2 = str._<?= $h("Menyimpan tanpa verifikasi tidak akan memotong stok. {{QUESTION}}") ?>;
                    c = confirm(str2.replace("{{QUESTION}}", str1));
                }

                if (c) {
                    noTransaksiFld.disabled = false;
                    actionFld.disabled = false;
                }
                return c;
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                const widget = tlm.app.getWidget("_<?= $viewVidgetId ?>");
                widget.show();
                widget.loadData({kode: event.data.kode}, true);
            },
            onFailSubmit() {
                alert(str._<?= $h("terjadi error") ?>);
            }
        });

        /** @param {HTMLTableRowElement} trElm */
        function hitungSubTotal(trElm) {
            const fields = trElm.fields;
            const idPar = fields.noUrutStc.dataset.par;
            const trPar = tabungTbl.querySelector("tr#"+idPar);
            const status = fields.statusKetersediaanFld.value;
            const hargaPokok = sysNum(fields.hargaPokokStc.innerHTML);
            const isiKemasan = sysNum(trPar.fields.isiKemasanWgt.value);
            const jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
            const hargaTotal = status ? (jumlahKemasan * hargaPokok) : 0;
            const jumlahItem = jumlahKemasan * isiKemasan;

            fields.hargaTotalStc.innerHTML = currency(hargaTotal);
            fields.jumlahItemStc.innerHTML = preferInt(jumlahItem);
        }

        function hitungTotal() {
            let grandTotal = 0;
            tabungTbl.querySelectorAll(".hargaTotalStc").forEach(item => grandTotal += sysNum(item.innerHTML));
            grandTotalStc.innerHTML = currency(grandTotal);
        }

        const noKirimWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noKirimFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == divElm.querySelector(".kodeFld").value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoKirimUrl ?>",
            term: "value",
            additionalData() {
                return {kode: divElm.querySelector(".noTransaksiFld").value}
            }
        });

        idPenerimaFld.addEventListener("change", () => {
            unitPenerimaFld.value = idPenerimaFld.selectedOptions[0].innerHTML;
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tabungTbl = divElm.querySelector(".tabungTbl");
        const tabungWgt = new spl.BulkInputWidget({
            element: tabungTbl,
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.idKatalogFld.value = data.idKatalog ?? "";
                fields.noUrutFld.value = data.noUrut ?? "";
                fields.idPabrikFld.value = data.idPabrik ?? "";
                fields.isiKemasanWgt.value = data.isiKemasan ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.idSatuanFld.value = data.idSatuan ?? "";
                fields.hpItemFld.value = data.hpItem ?? "";
                fields.hnaItemFld.value = data.hnaItem ?? "";
                fields.phjaItemFld.value = data.phjaItem ?? "";
                fields.hjaItemFld.value = data.hjaItem ?? "";
                fields.namaSediaanStc.innerHTML = data.namaSediaan ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.stockStc.innerHTML = data.stock ?? "";
                fields.noUrutStc.innerHTML = data.noUrut ?? "";
                fields.noBatchWgt.value = data.noBatch ?? "";
                fields.jumlahKemasanWgt.value = data.jumlahKemasan ?? "";
                fields.jumlahItemStc.innerHTML = data.jumlahItem ?? "";
                fields.statusKetersediaanFld.value = data.statusKetersediaan ?? "";
                fields.hargaPokokStc.innerHTML = data.hargaPokok ?? "";
                fields.hargaTotalStc.innerHTML = data.hargaTotal ?? "";
                fields.stockBtn.value = data.idKatalog ?? "";
            },
            addRow(trElm) {
                const noBatchWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".noBatchFld"),
                    valueField: "noBatch",
                    searchField: ["noBatch"],
                    errorRules: [
                        {required: true},
                        {
                            callback() {
                                let count = 0, val = this._element.value;
                                tabungTbl.querySelectorAll(".noBatchFld").forEach(elm => count += (elm.value == val));
                                return count < 2;
                            },
                            message: str._<?= $h("Sudah terpakai.") ?>
                        }
                    ],
                    /** @param {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.BatchFields} item */
                    optionRenderer(item) {
                        // TODO: js: missing var: obj
                        const exp = (obj.tanggalKadaluarsa && obj.tanggalKadaluarsa != "0000-00-00") ? "<br/>Kadaluarsa: " + userDate(obj.tanggalKadaluarsa) : "";

                        return `
                            <div class="option">
                                <span class="name">${item.idKatalog} (${item.noBatch})</span><br/>
                                <span class="description">[${item.unitPemilik}] ${exp}</span>
                            </div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.BatchFields} item */
                    itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.noBatch})</div>`},
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.BatchFields} */
                        const obj = this._options[value];
                        const ele = this;

                        if (obj.jumlahAdm) {
                            // TODO: js: uncategorized: is this truely different with validation
                            divElm.querySelectorAll(`.noBatchFld[id_katalog="${obj.idKatalog}"]`).forEach(/** @type {HTMLSelectElement} */item => {
                                if (item.value == obj.noBatch) {
                                    alert(str._<?= $h("No. Batch sudah dipilih. Tidak bisa dipilih lagi!") ?>);
                                    ele.removeItem(value);
                                }
                            });
                        } else {
                            alert(str._<?= $h("Tidak ada Stok di Gudang, untuk tabung dengan no. Batch!") ?>);
                            ele.removeItem(value);
                        }
                    }
                });

                const isiKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".isiKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.floatNumberSetting
                });

                const jumlahKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.intNumberSetting
                });

                trElm.fields = {
                    noBatchWgt,
                    isiKemasanWgt,
                    jumlahKemasanWgt,
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    idPabrikFld: trElm.querySelector(".idPabrikFld"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    idSatuanFld: trElm.querySelector(".idSatuanFld"),
                    hpItemFld: trElm.querySelector(".hpItemFld"),
                    hnaItemFld: trElm.querySelector(".hnaItemFld"),
                    phjaItemFld: trElm.querySelector(".phjaItemFld"),
                    hjaItemFld: trElm.querySelector(".hjaItemFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    stockStc: trElm.querySelector(".stockStc"),
                    noUrutStc: trElm.querySelector(".noUrutStc"),
                    jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                    statusKetersediaanFld: trElm.querySelector(".statusKetersediaanFld"),
                    hargaPokokStc: trElm.querySelector(".hargaPokokStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    stockBtn: trElm.querySelector(".stockBtn"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.noBatchWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.jumlahKemasanWgt.destroy();

                hitungTotal();
            },
            profile: {
                edit(data) {
                    // TODO: js: uncategorized: finish this
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
            addRowBtn: ".returGasMedisFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        tabungWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            const trElm = closest(jumlahKemasanFld, "tr");
            const fields = trElm.fields;

            const idPar = fields.noUrutStc.dataset.par;
            const trPar = tabungTbl.querySelector("tr#"+idPar);
            const jumlahKemasan = sysNum(jumlahKemasanFld.value);
            const maksimumJumlahItem = sysNum(trPar.fields.jumlahKemasanWgt.dataset.jkMax);

            let totalJumlahItem = 0;
            tabungTbl.querySelectorAll(`.noUrutStc[data-par="${idPar}"]`).forEach(item => {
                totalJumlahItem += sysNum(closest(item, "tr").fields.jumlahKemasanWgt.value);
            });

            if (jumlahKemasan > 1 || totalJumlahItem > maksimumJumlahItem) {
                alert(`
                    Melebihi batas maximum Stok tersedia. Jumlah maksimum yang bisa diinputkan
                    untuk masing - masing Batch adalah 1, dengan total tabung keluar
                    ${maksimumJumlahItem}, Silahkan cek Inputan lainnya terlebih dahulu!`
                );
                fields.jumlahKemasanWgt.value = userInt(jumlahKemasan);
            }

            hitungSubTotal(trElm);
            hitungTotal();
        });

        tabungWgt.addDelegateListener("tbody", "change", (event) => {
            const statusKetersediaanFld = event.target;
            if (!statusKetersediaanFld.matches(".statusKetersediaanFld")) return;

            hitungSubTotal(closest(statusKetersediaanFld, "tr"));
            hitungTotal();
        });

        tabungWgt.addDelegateListener("tbody", "click", (event) => {
            const stockBtn = event.target;
            if (!stockBtn.matches(".stockBtn")) return;

            $.post({
                url: "<?= $stockUrl ?>",
                data: {idKatalog: stockBtn.value, idUnit: idPengirimFld.value},
                error() {console.log("ajax error happens")},
                /** @param {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.AjaxFields[]} data */
                success(data) {
                    const namaKatalog = closest(stockBtn, "tr").fields.namaSediaanStc.innerHTML;
                    const namaDepo = idPengirimFld.selectedOptions[0].innerHTML;
                    headerElm.innerHTML = str._<?= $h("Ketersediaan {{KATALOG}} di Depo/Floor {{DEPO}}") ?>
                        .replace("{{KATALOG}}", namaKatalog)
                        .replace("{{DEPO}}", namaDepo);

                    let stokKosong = 0;
                    let stokIsi = 0;
                    data.forEach(item => item.statusKetersediaan == "0" ? stokKosong++ : stokIsi++);
                    footerElm.innerHTML = str._<?= $h("Total tabung berisi: {{ISI}}. Total tabung kosong: {{KOSONG}}") ?>
                        .replace("{{ISI}}", stokIsi)
                        .replace("{{KOSONG}}", stokKosong);

                    batchWgt.load(data);

                    let tStock = 0;
                    let trElm = "";

                    data.forEach(obj => {
                        const tersedia = (obj.statusKetersediaan == "0") ? str._<?= $h("Kosong") ?> : str._<?= $h("Berisi") ?>;
                        tStock += obj.kemasanFisik;
                        trElm += `
                            <tr>
                                <td>${obj.noBatch}</td>
                                <td>${userDate(obj.tanggalKadaluarsa)}</td>
                                <td>${obj.unitPemilik}</td>
                                <td>${tersedia}</td>
                                <td class="text-right">${preferInt(obj.kemasanFisik)}</td>
                                <td class="text-right">${preferInt(obj.jumlahFisik)}</td>
                            </tr>`;
                    });

                    trElm += `
                        <tr>
                            <td class="text-right" colspan="4"> <strong>TOTAL</strong></td>
                            <td class="text-right"><strong>${preferInt(tStock)}</strong></td>
                        </tr>`;

                    divElm.querySelector(".modal-body").innerHTML = trElm;
                    divElm.querySelector(".modal-footer").querySelector("#btn-pull").style.display = "none";
                    divElm.querySelector("#modal-modul").modal("show").querySelector("#btn-close").dispatchEvent(new Event("focus"));
                }
            });
        });

        const batchWgt = new spl.TableWidget({
            element: divElm.querySelector(".batchTbl"),
            columns: {
                1: {field: "noBatch"},
                2: {field: "tanggalKadaluarsa", formatter: tlm.dateFormatter},
                3: {field: "unitPemilik"},
                4: {field: "statusKetersediaan", formatter: val => val == "0" ? str._<?= $h("Kosong") ?> : str._<?= $h("Berisi") ?>},
                5: {field: "kemasanFisik", formatter: tlm.intFormatter},
                6: {field: "jumlahFisik", formatter: tlm.intFormatter},
            }
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: tabungTbl.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["namaSediaan", "kode"],
            /** @param {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.KatalogFields} item */
            optionRenderer(item) {
                return `
                    <div class="option">
                        <span class="name">${item.namaSediaan} (${item.idKatalog})</span><br/>
                        <span class="description">
                            Pabrik: ${item.namaPabrik}</br>
                            Satuan: ${item.satuan}</br>
                            Stok: ${item.stokAdm}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.KatalogFields} item */
            itemRenderer(item) {return `<div class="item">${item.namaSediaan} (${item.idKatalog})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $katalogAcplUrl ?>",
                    data: {query: typed, idUnitPosisi: idPengirimFld.value},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.DistribusiUi.ReturGasMedis.KatalogFields} */
                const obj = this.options[value];
                const idKatalog = obj.idKatalog;

                if (tabungTbl.querySelector("tr#" + idKatalog)) {
                    alert(str._<?= $h("Katalog sudah ditambahkan! Silahkan pilih yang lain.") ?>);
                    return;
                }

                const event = new Event("addRow");
                event.data = obj;
                tabungTbl.dispatchEvent(event);

                if (batch[idKatalog]) {
                    const noBatchWgt = divElm.querySelector(".noBatchFld").fieldWidget;
                    noBatchWgt.addOption(batch[idKatalog]);
                    noBatchWgt.value = null;
                    return;
                }

                $.post({
                    url: "<?= $cariKatalog2Url ?>",
                    data: {idKatalog, idUnitPosisi: idPengirimFld.value},
                    error() {return []},
                    success(data) {
                        if (!data.length) return;

                        batch.push(idKatalog);
                        batch[idKatalog] = data;

                        const noBatchWgt = divElm.querySelector(".noBatchFld").fieldWidget;
                        noBatchWgt.addOption(data);
                        noBatchWgt.value = null;
                    }
                });
            }
        });

        verKirimFld.addEventListener("click", () => {
            const isChecked = verKirimFld.checked;
            userKirimStc.innerHTML = isChecked ? tlm.user.nama : "";
            tanggalKirimStc.innerHTML = isChecked ? nowVal("user") : "";
            updateStokMarkerFld.checked = isChecked;
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(returGasMedisWgt, noKirimWgt, tanggalDokumenWgt, tabungWgt, batchWgt, idKatalogWgt);
        tlm.app.registerWidget(this.constructor.widgetName, returGasMedisWgt);
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
