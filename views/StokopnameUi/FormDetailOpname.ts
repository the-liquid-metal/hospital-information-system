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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/detailopname.php the original file
 */
final class FormDetailOpname
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $dataUrl,
        string $actionUrl,
        string $tableWidgetId,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Stokopname.DetailOpname {
    export interface FormFields {
        kodeTransaksi:        string;
        tanggalDokumen:       string;
        tanggalAdm:           string;
        idDepo:               string;
        verifikasiStokopname: string;
        verUserStokopname:    string;
        verTanggalStokopname: string;
        daftarStokOpname:     Array<StokOpnameFields>;
    }

    export interface StokOpnameFields {
        idKatalog:         string;
        idPabrik:          string;
        idKemasan:         string;
        jumlahStokAdm:     string;
        hargaItem:         string;
        idUserUbah:        string;
        tanggalUbah:       string;
        namaBarang:        string;
        namaPabrik:        string;
        satuan:            string;
        jumlahStokFisik:   string;
        tanggalKadaluarsa: string;
        namaUserUbah:      string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".detailOpnameFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeTransaksiFld", name: "kodeTransaksi"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Opname") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal ADM") ?>,
                        input: {class: ".tanggalAdmFld", name: "tanggalAdm"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Unit") ?>,
                        select: {class: ".idDepoFld", name: "idDepo"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Petugas") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verifikasiStokopnameFld" name="verifikasiStokopname" value="1"/>
                            <span class="verifikasiStokopnameStc"></span>
                        `}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr_1: {
                            td_1: {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kode") ?>},
                            td_3: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_4: {colspan: 9, text: tlm.stringRegistry._<?= $h("Perhitungan Stok Opname") ?>},
                            td_5: {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_6: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Operator Update") ?>},
                            td_7: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Tanggal Update") ?>}
                        },
                        tr_2: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Jumlah ADM") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Jumlah Fisik") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Akumulasi Fisik") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Selisih") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_9: {text: tlm.stringRegistry._<?= $h("Nilai") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_2: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_3: {class: ".idKemasanFld", name: "idKemasan[]"},
                                hidden_4: {class: ".jumlahStokAdmFld", name: "jumlahStokAdm[]"},
                                hidden_5: {class: ".hargaItemFld", name: "hargaItem[]"},
                                hidden_6: {class: ".idUserUbahFld", name: "idUserUbah[]"},
                                hidden_7: {class: ".tanggalUbahFld", name: "tanggalUbah[]"},
                                staticText: {class: ".noStc"}
                            },
                            td_2: {class: ".idKatalogStc"},
                            td_3: {class: ".namaBarangStc"},
                            td_4: {class: ".namaPabrikStc"},
                            td_5: {class: ".satuanStc"},
                            td_6: {class: ".jumlahStokAdmStc"},
                            td_7: {
                                input: {class: ".jumlahStokFisikFld", name: "jumlahStokFisik[]"}
                            },
                            td_8: {class: ".akumulasiFisikStc"},
                            td_9: {class: ".selisihStc"},
                            td_10: {
                                input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                            },
                            td_11: {class: ".hargaPokokItemStc"},
                            td_12: {class: ".nilaiStc"},
                            td_13: {class: ".no2Stc"},
                            td_14: {class: ".operatorStc"},
                            td_15: {class: ".tanggalUbahStc"}
                        }
                    }
                }
            },
            row_3: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            },
            row_4: {
                column: {
                    button: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {toSystemNumber: sysNum, toUserFloat: userFloat, stringRegistry: str, nowVal} = tlm;
        const notVerifyStr = "------ (00-00-0000 00:00:00)";

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodeTransaksiFld = divElm.querySelector(".kodeTransaksiFld");
        /** @type {HTMLInputElement} */  const tanggalDokumenFld = divElm.querySelector(".tanggalDokumenFld");
        /** @type {HTMLInputElement} */  const tanggalAdmFld = divElm.querySelector(".tanggalAdmFld");
        /** @type {HTMLSelectElement} */ const idDepoFld = divElm.querySelector(".idDepoFld");
        /** @type {HTMLInputElement} */  const verifikasiStokopnameFld = divElm.querySelector(".verifikasiStokopnameFld");
        /** @type {HTMLDivElement} */    const verifikasiStokopnameStc = divElm.querySelector(".verifikasiStokopnameStc");

        tlm.app.registerSelect("_<?= $depoSelect ?>", idDepoFld);
        this._selects.push(idDepoFld);

        const detailOpnameWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".detailOpnameFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Stokopname.DetailOpname.FormFields} data */
            loadData(data) {
                kodeTransaksiFld.value = data.kodeTransaksi ?? "";
                tanggalDokumenFld.value = data.tanggalDokumen ?? "";
                tanggalAdmFld.value = data.tanggalAdm ?? "";
                idDepoFld.value = data.idDepo ?? "";
                verifikasiStokopnameFld.checked = !!data.verifikasiStokopname;
                verifikasiStokopnameStc.innerHTML = data.verifikasiStokopname ? `${data.verUserStokopname} (${data.verTanggalStokopname})` : notVerifyStr;

                itemWgt.loadData(data.daftarStokOpname);
                akumulasi = {};

                this._element.disabled = data.verifikasiStokopname == "1";
            },
            onBeforeSubmit() {
                if (verifikasiStokopnameFld.checked) {
                    return confirm(str._<?= $h("Menyimpan dengan verifikasi akan menutup stok opname untuk depo ini. Apakah Anda yakin ingin menyimpan?") ?>);
                }
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
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        verifikasiStokopnameFld.addEventListener("click", () => {
            const vu = verifikasiStokopnameFld.dataset.vuser;
            const vt = nowVal("user");
            verifikasiStokopnameStc.innerHTML = verifikasiStokopnameFld.checked ? `${vu} (${vt})` : notVerifyStr;
        });

        let akumulasi = {};

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Stokopname.DetailOpname.StokOpnameFields} data
             * @param {number} idx
             */
            loadDataPerRow(trElm, data, idx) {
                const fields = trElm.fields;

                akumulasi[data.idKatalog] ??= 0;
                akumulasi[data.idKatalog] += data.jumlahStokFisik;

                fields.idKatalogFld.value = data.idKatalog ?? "";
                fields.idPabrikFld.value = data.idPabrik ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.jumlahStokAdmFld.value = data.jumlahStokAdm ?? "";
                fields.hargaItemFld.value = data.hargaItem ?? "";
                fields.idUserUbahFld.value = data.idUserUbah ?? "";
                fields.tanggalUbahFld.value = data.tanggalUbah ?? "";
                fields.idKatalogStc.innerHTML = data.idKatalog ?? "";
                fields.namaBarangStc.innerHTML = data.namaBarang ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.satuanStc.innerHTML = data.satuan ?? "";
                fields.jumlahStokAdmStc.innerHTML = data.jumlahStokAdm ?? "";
                fields.jumlahStokFisikFld.value = data.jumlahStokFisik ?? "";
                fields.akumulasiFisikStc.innerHTML = akumulasi[data.idKatalog];
                fields.selisihStc.innerHTML = data.jumlahStokFisik - data.jumlahStokAdm;
                fields.tanggalKadaluarsaFld.value = data.tanggalKadaluarsa ?? "";
                fields.hargaPokokItemStc.innerHTML = data.hargaItem ?? "";
                fields.nilaiStc.innerHTML = data.jumlahStokFisik * data.hargaItem;
                fields.noStc.innerHTML = idx;
                fields.no2Stc.innerHTML = idx;
                fields.operatorStc.innerHTML = data.namaUserUbah ?? "";
                fields.tanggalUbahStc.innerHTML = data.tanggalUbah ?? "";

                trElm.dataset.idKatalog = data.idKatalog;
            },
            addRow(trElm) {
                const tanggalKadaluarsaWgt = new spl.DateTimeWidget({
                    element: trElm.querySelector(".tanggalKadaluarsaFld"),
                    // TODO: js: uncategorized: add "already expired", and "less than 2 years" rules
                    errorRules: [{required: true}],
                    ...tlm.dateWidgetSetting
                });

                const jumlahStokFisikWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahStokFisikFld"),
                    errorRules: [{greaterThanEqual: true}],
                    fallbackToInt: true,
                    ...tlm.intNumberSetting
                });

                trElm.fields = {
                    tanggalKadaluarsaWgt,
                    jumlahStokFisikWgt,
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    idPabrikFld: trElm.querySelector(".idPabrikFld"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    jumlahStokAdmFld: trElm.querySelector(".jumlahStokAdmFld"),
                    hargaItemFld: trElm.querySelector(".hargaItemFld"),
                    idUserUbahFld: trElm.querySelector(".idUserUbahFld"),
                    tanggalUbahFld: trElm.querySelector(".tanggalUbahFld"),
                    idKatalogStc: trElm.querySelector(".idKatalogStc"),
                    namaBarangStc: trElm.querySelector(".namaBarangStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    satuanStc: trElm.querySelector(".satuanStc"),
                    jumlahStokAdmStc: trElm.querySelector(".jumlahStokAdmStc"),
                    akumulasiFisikStc: trElm.querySelector(".akumulasiFisikStc"),
                    selisihStc: trElm.querySelector(".selisihStc"),
                    tanggalKadaluarsaFld: trElm.querySelector(".tanggalKadaluarsaFld"),
                    hargaPokokItemStc: trElm.querySelector(".hargaPokokItemStc"),
                    nilaiStc: trElm.querySelector(".nilaiStc"),
                    noStc: trElm.querySelector(".noStc"),
                    no2Stc: trElm.querySelector(".no2Stc"),
                    operatorStc: trElm.querySelector(".operatorStc"),
                    tanggalUbahStc: trElm.querySelector(".tanggalUbahStc"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.tanggalKadaluarsaWgt.destroy();
                fields.jumlahStokFisikWgt.destroy();
            },
            profile: {
                add() {
                    // TODO: js: uncategorized: finish this
                },
                edit(data) {
                    // TODO: js: uncategorized: finish this
                }
            },
            onInit() {
                this.loadProfile("add");
            }
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahStokFisikFld = event.target;
            if (!jumlahStokFisikFld.matches(".jumlahStokFisikFld")) return;

            const kode = closest(jumlahStokFisikFld, "tr").dataset.idKatalog;
            let akumulasiFisik = 0;

            itemWgt.querySelectorAll("tbody tr").forEach(/** @param {HTMLTableRowElement} trElm */ trElm => {
                if (kode != trElm.dataset.idKatalog) return;

                const fields = trElm.fields;
                const jumlahStokAdm = sysNum(fields.jumlahStokAdmStc.innerHTML);
                const jumlahStokFisik = sysNum(fields.jumlahStokFisikFld.value);
                const hargaPokokItem = sysNum(fields.hargaPokokItemStc.innerHTML);

                akumulasiFisik += jumlahStokFisik;

                fields.akumulasiFisikStc.innerHTML = userFloat(akumulasiFisik);
                fields.selisihStc.innerHTML = userFloat(jumlahStokFisik - jumlahStokAdm);
                fields.nilaiStc.innerHTML = userFloat(jumlahStokFisik * hargaPokokItem);
            });
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(detailOpnameWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, detailOpnameWgt);
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
