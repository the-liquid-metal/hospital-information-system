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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Distribusi/addGM.php the original file
 */
final class FormGasMedis
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
        string $cekUnikNoDokumenUrl,
        string $cariKatalog1Url,
        string $cariKatalog2Url,
        string $viewWidgetId,
        string $penyimpanan1Select,
        string $penyimpanan2Select
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $batch = []; // TO BE DELETED
        $idata = []; // TO BE DELETED
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.DistribusiUi.AddGasMedis {
    export interface FormFields {
        kode:            string;
        tipeDokumen:     string;
        unitPengirim:    string;
        idPengirim:      string;
        noDokumen:       string;
        statusPrioritas: string;
        tanggalDokumen:  string;
        unitPenerima:    string;
        idPenerima:      string;
        verifikasiKirim: string;
        verTanggalKirim: string;
        namaUserKirim:   string;
        daftarRincian:   TableFields;
    }

    export interface TableFields {
        idKatalog:          string;
        noUrut:             string;
        idPabrik:           string;
        isiKemasan:         string;
        idKemasan:          string;
        idSatuan:           string;
        hpItem:             string;
        hnaItem:            string;
        phjaItem:           string;
        hjaItem:            string;
        namaSediaan:        string;
        namaPabrik:         string;
        stokAdm:            string;
        noBatch:            string;
        jumlahKemasan:      string;
        statusKetersediaan: string;
        hargaPokok:         "___"; // exist but missing
        hargaTotal:         "___"; // exist but missing
        katalogOpt:         KatalogFields;
        batchOpt:           BatchFields;
    }

    export interface KatalogFields {
        idKatalog:   "id_katalog";
        namaSediaan: "nama_sediaan";
        namaPabrik:  "nama_pabrik";
        satuan:      "satuan";
        stokAdm:     "stok_adm";
    }

    export interface BatchFields {
        idKatalog:   "id_katalog";
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
            class: ".tambahGasMedisFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode", readonly: true}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tipe Pengiriman") ?>,
                        select: {
                            class: ".tipeDokumenFld",
                            name: "tipeDokumen",
                            option_1: {value: 3, label: tlm.stringRegistry._<?= $h("Distribusi Gas Medis") ?>},
                            option_2: {value: 6, label: tlm.stringRegistry._<?= $h("Pengembalian Tabung Gas Medis") ?>}
                        }
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Unit Pengirim") ?>,
                        hidden: {class: ".unitPengirimFld", name: "unitPengirim"},
                        select: {class: ".idPengirimFld", name: "idPengirim"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Kirim") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Prioritas") ?>,
                        select: {
                            class: ".statusPrioritasFld",
                            name: "statusPrioritas",
                            option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Regular") ?>},
                            option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Cito") ?>}
                        }
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Unit Penerima") ?>,
                        hidden: {class: ".unitPenerimaFld", name: "unitPenerima"},
                        select: {class: ".idPenerimaFld", name: "idPenerima"}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".barangTbl",
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
                                hidden_1: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_2: {class: ".noUrutFld", name: "noUrut[]"},
                                hidden_3: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_4: {class: ".isiKemasanFld", name: "isiKemasan[]"},
                                hidden_5: {class: ".idKemasanFld", name: "idKemasan[]"},
                                hidden_6: {class: ".idSatuanFld", name: "idSatuan[]"},
                                hidden_7: {class: ".hpItemFld", name: "hpItem[]"},
                                hidden_8: {class: ".hnaItemFld", name: "hnaItem[]"},
                                hidden_9: {class: ".phjaItemFld", name: "phjaItem[]"},
                                hidden_10: {class: ".hjaItemFld", name: "hjaItem[]"},
                                staticText: {class: ".no"}
                            },
                            td_2: {class: ".namaSediaanStc"},
                            td_3: {class: ".namaPabrikStc"},
                            td_4: {class: ".stockStc"},
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
                                    option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Berisi") ?>}
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
                                checkbox: {class: ".verKirimFld", name: "verifikasiKirim", value: 1}
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Gudang Gas Medis") ?>},
                            td_4: {class: ".userKirimStc"},
                            td_5: {class: ".verTanggalKirimStc"},
                            td_6: {
                                checkbox: {class: ".updateStokMarkerFld", value: 1, disabled: true}
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
        }
    };

    constructor(divElm) {
        super();
        const {preferInt, nowVal, toCurrency: currency, toSystemNumber: sysNum, toUserInt: userInt, toUserDate: userDate, stringRegistry: str} = tlm;
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        let batch;

        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */ const tipeDokumenFld = divElm.querySelector(".tipeDokumenFld");
        /** @type {HTMLInputElement} */  const unitPengirimFld = divElm.querySelector(".unitPengirimFld");
        /** @type {HTMLSelectElement} */ const idPengirimFld = divElm.querySelector(".idPengirimFld");
        /** @type {HTMLSelectElement} */ const statusPrioritasFld = divElm.querySelector(".statusPrioritasFld");
        /** @type {HTMLInputElement} */  const unitPenerimaFld = divElm.querySelector(".unitPenerimaFld");
        /** @type {HTMLSelectElement} */ const idPenerimaFld = divElm.querySelector(".idPenerimaFld");
        /** @type {HTMLSelectElement} */ const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLDivElement} */    const grandTotalStc = divElm.querySelector(".grandTotalStc");
        /** @type {HTMLInputElement} */  const updateStokMarkerFld = divElm.querySelector(".updateStokMarkerFld");
        /** @type {HTMLInputElement} */  const verKirimFld = divElm.querySelector(".verKirimFld");
        /** @type {HTMLInputElement} */  const userKirimStc = divElm.querySelector(".userKirimStc");
        /** @type {HTMLInputElement} */  const verTanggalKirimStc = divElm.querySelector(".verTanggalKirimStc");

        tlm.app.registerSelect("_<?= $penyimpanan1Select ?>", idPengirimFld);
        tlm.app.registerSelect("_<?= $penyimpanan2Select ?>", idPenerimaFld);
        this._selects.push(idPengirimFld, idPenerimaFld);

        const tambahGasMedisWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".tambahGasMedisFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.DistribusiUi.AddGasMedis.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                tipeDokumenFld.value = data.tipeDokumen ?? "";
                unitPengirimFld.value = data.unitPengirim ?? "";
                idPengirimFld.value = data.idPengirim ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                statusPrioritasFld.value = data.statusPrioritas ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                unitPenerimaFld.value = data.unitPenerima ?? "";
                idPenerimaFld.value = data.idPenerima ?? "";

                verKirimFld.checked = data.verifikasiKirim == 1;
                verKirimFld.disabled = data.verifikasiKirim == 1;
                userKirimStc.innerHTML = data.namaUserKirim || "-";
                verTanggalKirimStc.innerHTML = data.verTanggalKirim ? userDate(data.verTanggalKirim) : "-";
                updateStokMarkerFld.checked = data.verifikasiKirim == 1;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                edit() {
                    batch = JSON.parse(`<?= json_encode($batch) ?>`);
                    const idata = JSON.parse(`<?= json_encode($idata) ?>`);
                    idata.forEach(obj => {
                        divElm.querySelectorAll(`.noBatchFld[id_katalog="${obj.id_katalog}"]`).forEach(/** @type {HTMLSelectElement} */item => {
                            if (obj.no_urut != closest(item, "tr").fields.noUrutFld.value) return;

                            // createBatch(item);
                            item.fieldWidget.addOption(batch[obj.id_katalog]);
                            item.fieldWidget.value = obj.no_batch;
                        });

                        divElm.querySelectorAll(`.noUrutStc[data-par="${obj.id_katalog}"]`).forEach((item, i) => item.innerHTML = i + 1);
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
                    const str2 = str._<?= $h("Menyimpan tanpa verifikasi tidak akan memotong stok.\n{{QUESTION}}") ?>;
                    c = confirm(str2.replace("{{QUESTION}}", str1));
                }

                if (c) {
                    kodeFld.disabled = false;
                    actionFld.disabled = false;
                    tipeDokumenFld.disabled = false;
                }
                return c;
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
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

            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
            const hargaPokok = sysNum(fields.hargaPokokStc.innerHTML);
            const jumlahItem = jumlahKemasan * isiKemasan;
            const hargaTotal = jumlahItem * hargaPokok;

            fields.hargaTotalStc.innerHTML = currency(hargaTotal);
            fields.jumlahItemStc.innerHTML = preferInt(jumlahItem);
        }

        function hitungTotal() {
            let grandTotal = 0;
            barangWgt.querySelectorAll(".hargaTotalStc").forEach(item => grandTotal += sysNum(item.innerHTML));
            grandTotalStc.innerHTML = currency(grandTotal);
        }

        const noDokumenWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noDokumenFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == kodeFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoDokumenUrl ?>",
            term: "value",
            additionalData() {
                return {kode: kodeFld.value}
            }
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        idPenerimaFld.addEventListener("change", () => {
            unitPenerimaFld.value = idPenerimaFld.selectedOptions[0].innerHTML;
        });

        const barangWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".barangTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.DistribusiUi.AddGasMedis.TableFields} data
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
                fields.stockStc.innerHTML = data.stokAdm ?? "";
                fields.noUrutStc.innerHTML = data.noUrut ?? "";
                fields.noBatchWgt.value = data.noBatch ?? "";
                fields.jumlahKemasanWgt.value = data.jumlahKemasan ?? "";
                fields.statusKetersediaanFld.value = data.statusKetersediaan ?? "";
                fields.hargaPokokStc.innerHTML = data.hargaPokok ?? "";
                fields.hargaTotalStc.innerHTML = data.hargaTotal ?? "";
                hitungSubTotal(trElm);
                hitungTotal();
            },
            addRow(trElm) {
                const noBatchWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".noBatchFld"),
                    valueField: "noBatch",
                    searchField: ["noBatch"],
                    /** @param {his.FatmaPharmacy.views.DistribusiUi.AddGasMedis.BatchFields} item */
                    optionRenderer(item) {
                        // TODO: js: missing var: obj
                        const exp = (obj.tanggalKadaluarsa && obj.tanggalKadaluarsa != "0000-00-00") ? "<br/>Kadaluarsa: " + userDate(obj.tanggalKadaluarsa) : "";

                        return `
                            <div class="option">
                                <span class="name">${item.idKatalog} (${item.noBatch})</span><br/>
                                <span class="description">[${item.unitPemilik}] ${exp}</span>
                            </div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.DistribusiUi.AddGasMedis.BatchFields} item */
                    itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.noBatch})</div>`},
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.DistribusiUi.AddGasMedis.BatchFields} */
                        const obj = this._options[value];
                        const ele = this;

                        if (obj.jumlahAdm) {
                            // TODO: js: uncategorized: is this truely different with validation
                            divElm.querySelectorAll(`.noBatchFld[id_katalog="${obj.idKatalog}"]`).forEach(/** @type {HTMLInputElement} */item => {
                                if (item.value == obj.idKatalog) {
                                    alert(str._<?= $h("No. Batch sudah dipilih. Tidak bisa dipilih lagi!") ?>);
                                    ele.removeItem(value);
                                }
                            });
                        } else {
                            alert(str._<?= $h("Tidak ada Stok di Gudang, untuk tabung dengan no. Batch!") ?>);
                            ele.removeItem(value);
                        }
                    },
                    errorRules: [
                        {required: true},
                        {
                            callback() {
                                let count = 0, val = this._element.value;
                                barangWgt.querySelectorAll(".noBatchFld").forEach(elm => count += (elm.value == val));
                                return count < 2;
                            },
                            message: str._<?= $h("Sudah terpakai.") ?>
                        }
                    ]
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
                    errorRules: [{greaterThan: 0}],
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
            addRowBtn: ".tambahGasMedisFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        barangWgt.querySelector(".jumlahKemasanFld").addEventListener("focusout", (event) => {
            const jumlahKemasanFld = /** @type {HTMLInputElement} */ event.target;
            const trElm = closest(jumlahKemasanFld, "tr");
            const idPar = trElm.fields.noUrutStc.dataset.par;

            let totalJumlahKemasan = 0;
            divElm.querySelectorAll(`.noUrutStc[data-par="${idPar}"]`).forEach(item => {
                totalJumlahKemasan += sysNum(closest(item, "tr").fields.jumlahKemasanWgt.value);
            });

            let jumlahKemasan = sysNum(jumlahKemasanFld.value);
            const maksimumJumlahKemasan = jumlahKemasanFld.dataset.jkMax;
            if (jumlahKemasan > 1 || totalJumlahKemasan > maksimumJumlahKemasan) {
                alert(`
                    Melebihi batas maximum Stok tersedia. Jumlah maksimum yang bisa diinputkan untuk
                    masing - masing Batch adalah 1, dengan total tabung keluar ${maksimumJumlahKemasan},
                    Silahkan cek Inputan lainnya terlebih dahulu!`
                );
                jumlahKemasan = 0;
                trElm.fields.jumlahKemasanWgt.value = userInt(jumlahKemasan);
            }

            hitungSubTotal(trElm);
            hitungTotal();
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: barangWgt.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["namaSediaan", "kode"],
            /** @param {his.FatmaPharmacy.views.DistribusiUi.AddGasMedis.KatalogFields} item */
            optionRenderer(item) {
                return `
                    <div class="option">
                        <span class="name">${item.idKatalog} (${item.namaSediaan})</span><br/>
                        <span class="description">
                            Pabrik: ${item.namaPabrik}<br/>
                            Satuan: ${item.satuan}<br/>
                            Stok: ${item.stokAdm}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.DistribusiUi.AddGasMedis.KatalogFields} item */
            itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.namaSediaan})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $cariKatalog2Url ?>",
                    data: {idUnitPosisi: idPengirimFld.value, query: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.DistribusiUi.AddGasMedis.KatalogFields} */
                const obj = this.options[value];
                const idKatalog = obj.idKatalog;

                if (barangWgt.querySelector("tr#"+idKatalog)) {
                    alert(str._<?= $h("Katalog sudah ditambahkan. Silahkan pilih yang lain.") ?>);
                    return;
                }

                const event = new Event("addRow");
                event.data = obj;
                barangWgt.dispatchEvent(event);

                if (batch[idKatalog]) {
                    const noBatchWgt = divElm.querySelector(".noBatchFld").fieldWidget;
                    noBatchWgt.addOption(batch[idKatalog]);
                    noBatchWgt.value = null;
                    return;
                }

                $.post({
                    url: "<?= $cariKatalog1Url ?>",
                    data: {idKatalog, idUnitPosisi: idPengirimFld.value, statusTersedia: 1},
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
            verTanggalKirimStc.innerHTML = isChecked ? nowVal("user") : "";
            updateStokMarkerFld.checked = isChecked;
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(tambahGasMedisWgt, noDokumenWgt, tanggalDokumenWgt, barangWgt, idKatalogWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tambahGasMedisWgt);
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
