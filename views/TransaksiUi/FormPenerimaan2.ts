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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/penerimaan2.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class FormPenerimaan2
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $dataUrl,
        string $addActionUrl,
        string $obatAcplUrl,
        string $stokDataUrl,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.Penerimaan2 {
    export interface FormFields {
        noPenerimaan:     "no_penerimaan"; // DAMN! not solved
        kodeTransaksi:    string; // DAMN! not solved, idTransaksi
        unitDiminta:      string;
        verifikasiTerima: string;
        noDokumen:        string;
        unitPeminta:      string;
        tanggal:          string;
        status:           string;
        tanggalTerima:    string;
        idPengeluaran:    string;
        daftarPeringatan: Array<Peringatan>;
    }

    export interface Peringatan {
        namaObat:    string;
        kodeObat:    string;
        namaPabrik:  string;
        namaKemasan: string;
        jumlah:      string;
    }

    export interface ObatFields {
        value:         "value"; // missing
        kode:          string;
        namaBarang:    string;
        hargaTerakhir: "harga_terakhir"; // missing
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
            class: ".transaksiPenerimaanFrm",
            hidden: {class: ".noPenerimaanFld", name: "noPenerimaan"},
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeTransaksiFld", name: "kodeTransaksi"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Unit Diminta (Tujuan)") ?>,
                        staticText: {class: ".unitDimintaFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi Terima") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verifikasiTerimaFld" name="verifikasi" value="verifikasi"/>
                            <span class="verifikasiTerimaStc"></span>
                        `}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        checkbox: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Unit Peminta (Asal)") ?>,
                        staticText: {class: ".unitPemintaStc"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
                        input: {class: ".tanggalFld", name: "tanggal"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Status") ?>,
                        staticText: {class: ".statusStc"}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Nama Obat") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Nama Pabrik") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Nama Kemasan") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Jumlah") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {class: ".no"},
                            td_2: {
                                input: {class: ".namaObatFld", name: "namaObat[]"},
                                hidden: {class: ".kodeObatFld", name: "kodeObat[]"},
                                rButton: {class: ".stokBtn"}
                            },
                            td_3: {
                                input: {class: ".namaPabrikFld", name: "namaPabrik[]"}
                            },
                            td_4: {
                                input: {class: ".namaKemasanFld", name: "namaKemasan[]"}
                            },
                            td_5: {
                                input: {class: ".jumlahFld", name: "jumlah[]"}
                            }
                        }
                    }
                }
            },
            row_3: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        },
        modal: {
            title: tlm.stringRegistry._<?= $h("Stok Tersedia") ?>,
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
        const {toSystemNumber: sysNum, toCurrency: currency, stringRegistry: str, nowVal} = tlm;
        const nama = tlm.user.nama;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */   const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */ const noPenerimaanFld = divElm.querySelector(".noPenerimaanFld");
        /** @type {HTMLInputElement} */ const kodeTransaksiFld = divElm.querySelector(".kodeTransaksiFld");
        /** @type {HTMLDivElement} */   const unitDimintaFld = divElm.querySelector(".unitDimintaFld");
        /** @type {HTMLInputElement} */ const verifikasiTerimaFld = divElm.querySelector(".verifikasiTerimaFld");
        /** @type {HTMLDivElement} */   const verifikasiTerimaStc = divElm.querySelector(".verifikasiTerimaStc");
        /** @type {HTMLInputElement} */ const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLDivElement} */   const unitPemintaStc = divElm.querySelector(".unitPemintaStc");
        /** @type {HTMLInputElement} */ const tanggalFld = divElm.querySelector(".tanggalFld");
        /** @type {HTMLDivElement} */   const statusStc = divElm.querySelector(".statusStc");
        /** @type {HTMLDivElement} */   const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */   const footerElm = divElm.querySelector(".footerElm");

        const transaksiPenerimaanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".transaksiPenerimaanFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Transaksi.Penerimaan2.FormFields} data */
            loadData(data) {
                noPenerimaanFld.value = data.noPenerimaan ?? "";
                kodeTransaksiFld.value = data.kodeTransaksi ?? "";
                unitDimintaFld.innerHTML = data.unitDiminta ?? "";
                verifikasiTerimaFld.checked = !!data.verifikasiTerima;
                verifikasiTerimaStc.innerHTML = nama + " " + (data.tanggalTerima ?? nowVal("user"));
                noDokumenFld.value = data.noDokumen ?? "";
                unitPemintaStc.innerHTML = data.unitPeminta ?? "";
                tanggalFld.value = data.tanggal ?? "";
                statusStc.innerHTML = data.status ?? "";

                itemWgt.loadData(data.daftarPeringatan);
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._element.reset();
                    formTitleTxt.innerHTML = str._<?= $h("Add Penerimaan Barang") ?>;
                    this._actionUrl = "<?= $addActionUrl ?>";
                    itemWgt.loadProfile("add");
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            resetBtnId: false,
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Transaksi.Penerimaan2.Peringatan} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.namaObatWgt.value = data.namaObat ?? "";
                fields.namaPabrikFld.value = data.namaPabrik ?? "";
                fields.namaKemasanFld.value = data.namaKemasan ?? "";
                fields.jumlahFld.value = data.jumlah ?? "";
                fields.stokBtn.value = data.kodeObat ?? "";
            },
            addRow(trElm) {
                const kodeObatFld = trElm.querySelector(".kodeObatFld");

                const namaObatWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaObatFld"),
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.Transaksi.Penerimaan2.ObatFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObatFld.value = data.kode ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.Transaksi.Penerimaan2.ObatFields} data */
                    optionRenderer(data) {return `<div class="option">${data.namaBarang} (${data.kode})</div>`},
                    /** @param {his.FatmaPharmacy.views.Transaksi.Penerimaan2.ObatFields} data */
                    itemRenderer(data) {return `<div class="item">${data.namaBarang} (${data.kode})</div>`},
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
                        /** @type {his.FatmaPharmacy.views.Transaksi.Penerimaan2.ObatFields} */
                        const obj = this.options[value];
                        const id = "TODO ...";
                        trElm.querySelector(".harga-" + id).value = currency(obj.hargaTerakhir);
                        trElm.querySelector(".harga-hide-" + id).value = currency(obj.hargaTerakhir);
                        trElm.querySelector(".pakai-" + id).value = "1";
                        trElm.querySelector(".total-" + id).value = currency(obj.hargaTerakhir);
                        trElm.querySelector(".total-hide-" + id).value = currency(obj.hargaTerakhir);

                        const elm = trElm.querySelector("#cek_" + id);
                        elm.disabled = false;
                        elm.dataset.kode = obj.kode;

                        let grandTotal = 0;
                        divElm.querySelectorAll(".total-hide").forEach(/** @type {HTMLInputElement} */item => grandTotal += sysNum(item.value));

                        divElm.querySelectorAll(".grandtotal").value = currency(grandTotal);
                    }
                });

                trElm.fields = {
                    namaObatWgt,
                    namaPabrikFld: trElm.querySelector(".namaPabrikFld"),
                    namaKemasanFld: trElm.querySelector(".namaKemasanFld"),
                    jumlahFld: trElm.querySelector(".jumlahFld"),
                    stokBtn: trElm.querySelector(".stokBtn"),
                };
            },
            deleteRow(trElm) {
                trElm.fields.namaObatWgt.destroy();
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

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const stokBtn = event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            $.post({
                url: "<?= $stokDataUrl ?>",
                data: {kode: stokBtn.value},
                success(data) {
                    const {stokKatalog, daftarStokKatalog} = data;
                    headerElm.innerHTML = `${stokKatalog.namaBarang} (${stokKatalog.idKatalog})`;
                    footerElm.innerHTML = "total: " + daftarStokKatalog.reduce((acc, curr) => acc + curr.jumlahStokFisik, 0);
                    stokWgt.load(daftarStokKatalog);
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "keypress", (event) => {
            const jumlahFld = event.target;
            if (!jumlahFld.matches(".jumlahFld")) return;

            const id = jumlahFld.dataset.no;
            const jumlah = sysNum(divElm.querySelector(".pakai-" + id).value);
            const harga = sysNum(divElm.querySelector(".harga-hide-" + id).value);
            const total = harga * jumlah;

            let grandTotal = 0;
            divElm.querySelectorAll(".total-hide").forEach(/** @type {HTMLInputElement} */item => grandTotal += sysNum(item.value));

            divElm.querySelector(".total-" + id).value = currency(total);
            divElm.querySelector(".total-hide-" + id).value = currency(total);
            divElm.querySelector(".grandtotal").value = currency(grandTotal);
        });

        /** @see {his.FatmaPharmacy.views.Transaksi.Penerimaan2.StokTableFields} */
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
        this._widgets.push(transaksiPenerimaanWgt, itemWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, transaksiPenerimaanWgt);
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
