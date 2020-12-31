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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/pengeluaran22.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class FormPengeluaran22
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $dataUrl,
        string $addActionUrl,
        string $stokDataUrl,
        string $printWidgetId,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.Pengeluaran22 {
    export interface FormFields {
        kodeDokumenPengeluaran:  string;
        kodeDokumenPengiriman:   string;
        noPermintaan:            string;
        kodeDepoPeminta:         string;
        namaDepoPengirim:        string;
        idVerifikasiUser:        string;
        namaVerifikasiUser:      string;
        tanggalVerifikasi:       string;
        daftarPengeluaran:       Array<PengeluaranFields>;
    }

    export interface PengeluaranFields {
        stokTersedia:  string;
        stokPeminta:   string;
        jumlahDiminta: string;
        jumlahDikirim: string;
        namaBarang:    string;
        kodeBarang:    string;
        noBatch:       string;
        namaPabrik:    string;
        namaKemasan:   string;
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
            class: ".transaksiPengeluaranFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Dokumen Pengeluaran") ?>,
                        input: {class: ".kodeDokumenPengeluaranFld", name: "kodeDokumenPengeluaran"},
                        hidden_1: {class: ".kodeDokumenPengirimanFld", name: "kodeDokumenPengiriman"},
                        hidden_2: {class: ".noPermintaanFld", name: "noPermintaan"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Unit Peminta (Tujuan)") ?>,
                        select: {class: ".kodeDepoPemintaFld", name: "kodeDepoPeminta"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verifikasiKirimFld" name="verifikasi" value="verifikasi"/>
                            <span class="verifikasiKirimStc"></span>
                        `}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Status") ?>,
                        staticText: {class: ".statusFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
                        staticText: {class: ".tanggalFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Unit Pengirim (Asal)") ?>,
                        staticText: {class: ".namaDepoPengirimFld"}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".obatTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("No. Batch") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Nama Pabrik") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Stok Tersedia") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Stok Peminta") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Jumlah Diminta") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Jumlah Dikirim") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Nama Kemasan") ?>},
                            td_9: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                input: {class: ".namaBarangFld", name: "namaBarang[]"},
                                hidden: {class: ".kodeBarangFld", name: "kodeBarang[]"},
                                rButton: {class: ".stokBtn"}
                            },
                            td_2: {
                                input: {class: ".noBatchFld", name: "noBatch[]"}
                            },
                            td_3: {
                                input: {class: ".namaPabrikFld", name: "namaPabrik[]"}
                            },
                            td_4: {
                                input: {class: ".stokTersediaFld", name: "stokTersedia[]"}
                            },
                            td_5: {class: ".stokPemintaStc"},
                            td_6: {
                                input: {class: ".jumlahDimintaFld", name: "jumlahDiminta[]"}
                            },
                            td_7: {
                                input: {class: ".jumlahDikirimFld", name: "jumlahDikirim[]"}
                            },
                            td_8: {
                                input: {class: ".namaKemasanFld", name: "namaKemasan[]"}
                            },
                            td_9: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {text: "", colspan: 8},
                            td_2: {
                                button: {class: ".addRowBtn", type: "success", size: "xs", label: tlm.stringRegistry._<?= $h("add") ?>}
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
        const {nowVal, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const kodeDokumenPengeluaranFld = divElm.querySelector(".kodeDokumenPengeluaranFld");
        /** @type {HTMLInputElement} */  const kodeDokumenPengirimanFld = divElm.querySelector(".kodeDokumenPengirimanFld");
        /** @type {HTMLInputElement} */  const noPermintaanFld = divElm.querySelector(".noPermintaanFld");
        /** @type {HTMLSelectElement} */ const kodeDepoPemintaFld = divElm.querySelector(".kodeDepoPemintaFld");
        /** @type {HTMLInputElement} */  const verifikasiKirimFld = divElm.querySelector(".verifikasiKirimFld");
        /** @type {HTMLDivElement} */    const verifikasiKirimStc = divElm.querySelector(".verifikasiKirimStc");
        /** @type {HTMLDivElement} */    const statusFld = divElm.querySelector(".statusFld");
        /** @type {HTMLDivElement} */    const tanggalFld = divElm.querySelector(".tanggalFld");
        /** @type {HTMLDivElement} */    const namaDepoPengirimFld = divElm.querySelector(".namaDepoPengirimFld");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $h($depoSelect) ?>", kodeDepoPemintaFld);
        this._selects.push(kodeDepoPemintaFld);

        let noPermintaan;

        const transaksiPengeluaranWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".transaksiPengeluaranFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran22.FormFields} data */
            loadData(data) {
                kodeDokumenPengeluaranFld.value = data.kodeDokumenPengeluaran ?? "";
                kodeDokumenPengirimanFld.value = data.kodeDokumenPengiriman ?? "";
                noPermintaanFld.value = data.noPermintaan ?? "";

                kodeDepoPemintaFld.value = data.kodeDepoPeminta ?? "";
                kodeDepoPemintaFld.querySelectorAll("option").forEach(item => item.disabled = !!data.idVerifikasiUser);
                kodeDepoPemintaFld.selectedOptions[0].disabled = false;

                verifikasiKirimFld.checked = !!data.idVerifikasiUser;
                verifikasiKirimStc.innerHTML = data.namaVerifikasiUser + " " + data.tanggalVerifikasi;
                statusFld.innerHTML = data.idVerifikasiUser ? str._<?= $h("Telah Dikirim") ?> : str._<?= $h("Belum Dikirim") ?>;
                tanggalFld.innerHTML = nowVal("user");
                namaDepoPengirimFld.innerHTML = data.namaDepoPengirim ?? "";

                obatWgt.loadData(data.daftarPengeluaran);
                noPermintaan = data.noPermintaan;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._element.reset();
                    formTitleTxt.innerHTML = str._<?= $h("???") ?>;
                    this._actionUrl = "<?= $addActionUrl ?>";
                    obatWgt.loadProfile("add");
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
                widget.show();
                widget.loadData({kodeTransaksi: noPermintaan}, true);
            },
            resetBtnId: false,
        });

        const obatWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".obatTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran22.PengeluaranFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.namaBarangFld.value = data.namaBarang ?? "";
                fields.kodeBarangFld.value = data.kodeBarang ?? "";
                fields.noBatchFld.value = data.noBatch ?? "";
                fields.namaPabrikFld.value = data.namaPabrik ?? "";
                fields.stokTersediaWgt.value = data.stokTersedia ?? "";
                fields.jumlahDimintaWgt.value = data.jumlahDiminta ?? "";
                fields.jumlahDikirimWgt.value = data.jumlahDikirim ?? "";
                fields.namaKemasanFld.value = data.namaKemasan ?? "";
                fields.stokPemintaStc.innerHTML = data.stokPeminta ?? "";
                fields.stokBtn.value = data.kodeBarang ?? "";
            },
            addRow(trElm) {
                const stokTersediaWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".stokTersediaFld"),
                    errorRules: [{greaterThanEqual: 0}],
                    ...tlm.floatNumberSetting
                });

                const jumlahDimintaWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahDimintaFld"),
                    errorRules: [{greaterThanEqual: 0}],
                    ...tlm.floatNumberSetting
                });

                const jumlahDikirimWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahDikirimFld"),
                    errorRules: [{greaterThanEqual: 0}],
                    ...tlm.floatNumberSetting
                });

                trElm.fields = {
                    stokTersediaWgt,
                    jumlahDimintaWgt,
                    jumlahDikirimWgt,
                    namaBarangFld: trElm.querySelector(".namaBarangFld"),
                    kodeBarangFld: trElm.querySelector(".kodeBarangFld"),
                    noBatchFld: trElm.querySelector(".noBatchFld"),
                    namaPabrikFld: trElm.querySelector(".namaPabrikFld"),
                    namaKemasanFld: trElm.querySelector(".namaKemasanFld"),
                    stokPemintaStc: trElm.querySelector(".stokPemintaStc"),
                    stokBtn: trElm.querySelector(".stokBtn"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.stokTersediaWgt.destroy();
                fields.jumlahDimintaWgt.destroy();
                fields.jumlahDikirimWgt.destroy();
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

        obatWgt.addDelegateListener("tbody", "click", (event) => {
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

        /** @see {his.FatmaPharmacy.views.Transaksi.Pengeluaran22.StokTableFields} */
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
        this._widgets.push(transaksiPengeluaranWgt, obatWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, transaksiPengeluaranWgt);
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
