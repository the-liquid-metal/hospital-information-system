<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ReturFarmasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/addGM.php the original file
 */
final class FormGasMedis
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        array  $verifikasiPenerimaanAccess,
        array  $verifikasiAkuntansiAccess,
        string $editDataUrl,
        string $verifikasiPenerimaanDataUrl,
        string $verifikasiAkuntansiDataUrl,
        string $addActionUrl,
        string $editActionUrl,
        string $verifikasiPenerimaanActionUrl,
        string $verifikasiAkuntansiActionUrl,
        string $pemasokAcplUrl,
        string $katalogAcplUrl,
        string $cekUnikNoDokumenUrl,
        string $batchUrl,
        string $batchTabungUrl,
        string $viewWidgetId,
        string $penyimpananSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis {
    export interface FormFields {
        action:              string|"?" |"??" |"___"    |"____"     ; // action
        tipeDokumen:         string|"?" |"??" |"___"    |"____"     ; // tipe_doc
        submit:              string|"?" |"??" |"___"    |"____"     ; // submit
        kode:                string|"?" |"??" |"VerAkun"|"VerTerima"; // kode
        tanggalDokumen:      string|"?" |"??" |"___"    |"____"     ; // tgl_doc
        idGudangPenyimpanan: string|"?" |"??" |"___"    |"____"     ; // id_gudangpenyimpanan
        noDokumen:           string|"?" |"??" |"___"    |"____"     ; // no_doc
        keterangan:          string|"?" |"??" |"___"    |"____"     ; // keterangan
        idPemasok:           string|"?" |"??" |"___"    |"____"     ; // id_pbf
        namaPemasok:         string|"?" |"??" |"___"    |"____"     ; // nama_pbf
        kodePemasok:         string|"?" |"??" |"___"    |"____"     ; // kode_pbf

        verGudang:           string|"?" |"??" |"___"    |"____"     ; // ver_gudang
        verTerima:           string|"?" |"??" |"___"    |"VerTerima"; // ver_terima
        verAkuntansi:        string|"?" |"??" |"VerAkun"|"____"     ; // ver_akuntansi

        verTanggalGudang:    string|"?" |"??" |"___"    |"VerTerima"; // ver_tglgudang
        verTanggalTerima:    string|"?" |"??" |"___"    |"____"     ; // ver_tglterima
        verTanggalAkuntansi: string|"?" |"??" |"___"    |"____"     ; // ver_tglakuntansi

        verUserGudang:       string|"?" |"??" |"___"    |"____"     ; // user_gudang
        verUserTerima:       string|"?" |"??" |"___"    |"____"     ; // user_terima
        verUserAkuntansi:    string|"?" |"??" |"___"    |"____"     ; // user_akuntansi

        objectPemasok:       PemasokFields;
    }

    export interface TableFields {
        idKatalog:     "id_katalog[]",
        noUrut:        "no_urut[]",
        idPabrik:      "id_pabrik[]",
        kemasan:       "kemasan[]",
        isiKemasan:    "isi_kemasan[]",
        idKemasan:     "id_kemasan[]",
        idKemasanDepo: "id_kemasandepo[]",
        namaSediaan:   "___", // exist but missing
        namaPabrik:    "___", // exist but missing
        stokAdm:       "___", // exist but missing
        noBatch:       "no_batch[]",
        jumlahKemasan: "jumlah_kemasan[]",
    }

    export interface BatchFields {
        idKatalog:         "id_katalog",
        noBatch:           "no_batch",
        unitPemilik:       "unit_pemilik",
        tanggalKadaluarsa: "tgl_expired",
        jumlahAdm:         "jumlah_adm",
    }

    export interface KatalogFields {
        idKatalog:   string;
        namaSediaan: string;
        namaPabrik:  string;
        satuan:      string;
        stokAdm:     string;
        idPabrik:    string;
        kemasan:     string;
        isiKemasan:  string;
        idKemasan:   string;
        idSatuan:    string;
        satuanJual:  string;
    }

    export interface PemasokFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface StokTableFields {
        noBatch:           "no_batch";
        tanggalKadaluarsa: "tgl_expired";
        unitPemilik:       "unitPemilik";
        statusTersedia:    "sts_tersedia";
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{add: boolean, edit: boolean, verifikasiPenerimaan: boolean, verifikasiAkuntansi: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            verifikasiPenerimaan: JSON.parse(`<?=json_encode($verifikasiPenerimaanAccess) ?>`),
            verifikasiAkuntansi: JSON.parse(`<?=json_encode($verifikasiAkuntansiAccess) ?>`),
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
            class: ".returFarmasiFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"}, // $data['action']
                    hidden_2: {class: ".tipeDokumenFld", name: "tipeDokumen", value: 5},
                    hidden_3: {class: ".submitFld", name: "submit", value: "save"}, // ($data['ver_terima'] == 1) ? "warning" : "primary"
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                        select: {class: ".idGudangPenyimpananFld", name: "idGudangPenyimpanan"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Retur") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr_1: {
                            td_1: {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Stok") ?>},
                            td_6: {colspan: 3, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                            td_7: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Action") ?>}
                        },
                        tr_2: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Batch") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Kemasan") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_2: {class: ".noUrutFld", name: "noUrut[]"},
                                hidden_3: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_4: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_5: {class: ".isiKemasanFld", name: "isiKemasan[]"},
                                hidden_6: {class: ".idKemasanFld", name: "idKemasan[]"},
                                hidden_7: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                                staticText: {class: ".no"}
                            },
                            td_2: {
                                staticText: {class: ".namaSediaanStc"},
                                rButton: {class: ".stokBtn"}
                            },
                            td_3: {class: ".namaPabrikStc"},
                            td_4: {class: ".kemasanStc"},
                            td_5: {class: ".stokAdmStc"},
                            td_6: {class: ".noUrutStc"},
                            td_7: {
                                select: {class: ".noBatchFld", name: "noBatch[]"}
                            },
                            td_8: {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]"}
                            },
                            td_9: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Cari Katalog:") ?>, colspan: 6},
                            td_2: {
                                select: {class: ".idKatalogFld"}
                            },
                            td_3: {
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
                            td_1: {text: tlm.stringRegistry._<?= $h("Ver. No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Ver.") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Otorisasi") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("User") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Update Stok") ?>},
                        }
                    },
                    tbody: {
                        tr_1: {
                            td_1: {text: "1"},
                            td_2: {
                                checkbox: {class: ".verGudangFld", name: "verGudang", value: 1}
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Gudang") ?>},
                            td_4: {class: ".userGudangStc"},
                            td_5: {class: ".tanggalGudangStc"},
                            td_6: {text: ""},
                        },
                        tr_2: {
                            td_1: {text: "2"},
                            td_2: {
                                checkbox: {class: ".verTerimaFld", name: "verTerima", value: 1}
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Tim Penerima") ?>},
                            td_4: {class: ".userTerimaStc"},
                            td_5: {class: ".tanggalTerimaStc"},
                            td_6: {
                                checkbox: {class: ".updateStokMarkerFld", value: 1}
                            },
                        },
                        tr_3: {
                            td_1: {text: "3"},
                            td_2: {
                                checkbox: {class: ".verAkuntansiFld", name: "verAkuntansi", value: 1}
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Akuntansi") ?>},
                            td_4: {class: ".userAkuntansiStc"},
                            td_5: {class: ".tanggalAkuntansiStc"},
                            td_6: {text: ""},
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
            title: tlm.stringRegistry._<?= $h("Batch") ?>,
            row_1: {
                widthColumn: {class: ".headerElm"}
            },
            row_2: {
                widthTable: {
                    class: ".batchTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Batch Tabung") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Pemilik Tabung") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Ketersediaan") ?>},
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
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {toSystemNumber: sysNum, toUserInt: userInt, toCurrency: currency} = tlm;
        const {toUserDate: userDate, stringRegistry: str, nowVal, preferInt} = tlm;
        const userName = tlm.user.nama;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        let batch;

        /** @type {HTMLDivElement} */      const formTitleTxt = divElm.querySelector(".formTitleTxt"); // TODO: missing
        /** @type {HTMLInputElement} */    const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */    const tipeDokumenFld = divElm.querySelector(".tipeDokumenFld");
        /** @type {HTMLInputElement} */    const submitFld = divElm.querySelector(".submitFld");
        /** @type {HTMLInputElement} */    const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */   const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLTextAreaElement} */ const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLInputElement} */    const updateStokMarkerFld = divElm.querySelector(".updateStokMarkerFld");
        /** @type {HTMLInputElement} */    const verGudangFld = divElm.querySelector(".verGudangFld");
        /** @type {HTMLInputElement} */    const verTerimaFld = divElm.querySelector(".verTerimaFld");
        /** @type {HTMLInputElement} */    const verAkuntansiFld = divElm.querySelector(".verAkuntansiFld");
        /** @type {HTMLInputElement} */    const userGudangStc = divElm.querySelector(".userGudangStc");
        /** @type {HTMLInputElement} */    const userTerimaStc = divElm.querySelector(".userTerimaStc");
        /** @type {HTMLInputElement} */    const userAkuntansiStc = divElm.querySelector(".userAkuntansiStc");
        /** @type {HTMLInputElement} */    const tanggalGudangStc = divElm.querySelector(".tanggalGudangStc");
        /** @type {HTMLInputElement} */    const tanggalTerimaStc = divElm.querySelector(".tanggalTerimaStc");
        /** @type {HTMLInputElement} */    const tanggalAkuntansiStc = divElm.querySelector(".tanggalAkuntansiStc");
        /** @type {HTMLDivElement} */      const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */      const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $penyimpananSelect ?>", idGudangPenyimpananFld);
        this._selects.push(idGudangPenyimpananFld);

        const returFarmasiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".returFarmasiFrm"),
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.FormFields} data */
            loadData(data) {
                actionFld.value = data.action ?? "";
                tipeDokumenFld.value = data.tipeDokumen ?? "";
                submitFld.value = data.submit ?? "";
                kodeFld.value = data.kode ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                keteranganFld.value = data.keterangan ?? "";

                if (data.objectPemasok) {
                    idPemasokWgt.addOption(data.objectPemasok);
                    idPemasokWgt.value = data.objectPemasok.id;
                }

                tanggalGudangStc.innerHTML = data.verTanggalGudang ? userDate(data.verTanggalGudang) : "-";
                tanggalTerimaStc.innerHTML = data.verTanggalTerima ? userDate(data.verTanggalTerima) : "-";
                tanggalAkuntansiStc.innerHTML = data.verTanggalAkuntansi ? userDate(data.verTanggalAkuntansi) : "-";

                userGudangStc.innerHTML = data.verUserGudang ?? "";
                userTerimaStc.innerHTML = data.verUserTerima ?? "";
                userAkuntansiStc.innerHTML = data.verUserAkuntansi ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.load({});

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    // "kode" => "NO TRANSAKSI",
                    // "no_doc" => "",
                    // "tgl_doc" => Yii::$app->dateTime->todayVal("user"),
                    // "id_pbf" => "0",
                    // "id_gudangpenyimpanan" => "60",
                    // "keterangan" => "",
                    // "sts_saved" => "0",
                    // "action" => "add"

                    // "ver_terima" => "0",
                    // "verterima" => "disabled",
                    // "user_terima" => "------",
                    // "ver_tglterima" => null,

                    // "stokgudang" => "disabled",
                    // "vergudang" => "",
                    // "user_gudang" => "------",
                    // "ver_tglgudang" => null,

                    // "verakuntansi" => "disabled",
                    // "user_akuntansi" => "------",
                    // "ver_tglakuntansi" => null,
                },
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.FormFields} data */
                edit(data) {
                    this._dataUrl = "<?= $editDataUrl ?>";
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.load(data);

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    ppnFld.checked = data.ppn == "10";

                    verGudangFld.disabled = data.verGudang == "1";
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = data.verTerima != "1";
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = data.verTerima != "1";
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi != "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";

                    batch = data.noBatch;

                    for (const key in data.idata) {
                        const obj = data.idata[key];
                        divElm.querySelectorAll(`.noBatchFld[id_katalog="${obj.id_katalog}"]`).forEach(/** @type {HTMLSelectElement} */item => {
                            if (obj.no_urut != closest(item, "tr").fields.noUrutFld.value) return;

                            createBatch(item);
                            item.fieldWidget.addOption(data.noBatch[obj.id_katalog]);
                            item.fieldWidget.value = obj.no_batch;
                        });
                        sortnumberItem(obj.id_katalog);
                    }
                },
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.FormFields} data */
                verifikasiPenerimaan(data) {
                    this._dataUrl = "<?= $verifikasiPenerimaanDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiPenerimaanActionUrl ?>";
                    this.load(data);

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    verGudangFld.disabled = true;
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = false;
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = data.verTerima != "1";
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi != "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";
                },
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.FormFields} data */
                verifikasiAkuntansi(data) {
                    this._dataUrl = "<?= $verifikasiAkuntansiDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiAkuntansiActionUrl ?>";
                    this.load(data);

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    verGudangFld.disabled = true;
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = true;
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = true;
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi == "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";
                }
            },
            onInit() {
                this.loadProfile("add");
            },
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
                widget.show();
                widget.loadData({kode: kodeFld.value}, true);
            }
        });

        function hitungTotal() {
            console.warn("please search the proper implementation from the original source or create one");
        }

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

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

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.PemasokFields} item */
            itemRenderer(item) {return `<div class="item">${item.nama} (${item.kode})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pemasokAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.idKatalogFld.value = data.idKatalog ?? "";
                fields.noUrutFld.value = data.noUrut ?? "";
                fields.idPabrikFld.value = data.idPabrik ?? "";
                fields.kemasanFld.value = data.kemasan ?? "";
                fields.isiKemasanFld.value = data.isiKemasan ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.idKemasanDepoFld.value = data.idKemasanDepo ?? "";
                fields.namaSediaanStc.innerHTML = data.namaSediaan ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.kemasanStc.innerHTML = data.kemasan ?? "";
                fields.stokAdmStc.innerHTML = data.stokAdm ?? "";
                fields.noUrutStc.innerHTML = data.noUrut ?? "";
                fields.noBatchWgt.value = data.noBatch ?? "";
                fields.jumlahKemasanWgt.value = data.jumlahKemasan ?? "";
                fields.stokBtn.value = data.idKatalog ?? "";
            },
            addRow(trElm) {
                const noBatchWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".noBatchFld"),
                    valueField: "no_batch",
                    searchField: ["no_batch"],
                    errorRules: [
                        {required: true},
                        {
                            callback() {
                                let count = 0, val = this._element.value;
                                itemWgt.querySelectorAll(".noBatchFld").forEach(elm => count += (elm.value == val));
                                return count < 2;
                            },
                            message: str._<?= $h("Sudah terpakai.") ?>
                        }
                    ],
                    /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.BatchFields} item */
                    optionRenderer(item) {
                        const exp = (obj.tanggalKadaluarsa && obj.tanggalKadaluarsa != "0000-00-00") ? "<br/>Kadaluarsa: " + userDate(obj.tanggalKadaluarsa) : "";
                        return `
                            <div class="option">
                                <span class="name">${item.idKatalog} (${item.noBatch})</span><br/>
                                <span class="description">[${item.unitPemilik}] ${exp}</span>
                            </div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.BatchFields} item */
                    itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.noBatch})</div>`},
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.BatchFields} */
                        const obj = this.options[value];
                        if (!obj.jumlahAdm) {alert(str._<?= $h("Tidak ada stok di gudang untuk tabung dengan no. batch tersebut!") ?>)}
                    }
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
                    jumlahKemasanWgt,
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    idPabrikFld: trElm.querySelector(".idPabrikFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    isiKemasanFld: trElm.querySelector(".isiKemasanFld"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    kemasanStc: trElm.querySelector(".kemasanStc"),
                    stokAdmStc: trElm.querySelector(".stokAdmStc"),
                    noUrutStc: trElm.querySelector(".noUrutStc"),
                    stokBtn: trElm.querySelector(".stokBtn"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.noBatchWgt.destroy();
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
            addRowBtn: ".returFarmasiFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const stokBtn = event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            $.post({
                url: "<?= $batchUrl ?>",
                data: {idKatalog: stokBtn.value, idUnitPosisi: 60, statusAktif: 1},
                error() {console.log("ajax error happens")},
                success(data) {
                    const namaKatalog = closest(stokBtn, "tr").fields.namaSediaanStc.innerHTML;
                    const namaDepo = idGudangPenyimpananFld.selectedOptions[0].innerHTML;
                    headerElm.innerHTML = str._<?= $h("Ketersediaan {{KATALOG}} di Depo/Floor {{DEPO}}") ?>
                        .replace("{{KATALOG}}", namaKatalog)
                        .replace("{{DEPO}}", namaDepo);

                    let stokKosong = 0;
                    let stokIsi = 0;
                    data.forEach(item => item.statusTersedia == "0" ? stokKosong++ : stokIsi++);
                    footerElm.innerHTML = str._<?= $h("Total tabung berisi: {{ISI}}. Total tabung kosong: {{KOSONG}}") ?>
                        .replace("{{ISI}}", stokIsi)
                        .replace("{{KOSONG}}", stokKosong);

                    batchWgt.load(data);
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            const trElm = closest(jumlahKemasanFld, "tr");
            const idPar = trElm.fields.noUrutStc.dataset.par;
            const trPar = itemWgt.querySelector("tr#"+idPar);
            let jumlahKemasan = sysNum(jumlahKemasanFld.value);
            const isiKemasan = sysNum(trPar.querySelector(".is").value);

            let jumlahItem = jumlahKemasan * isiKemasan;
            trElm.querySelector(".ji").value = preferInt(jumlahItem);

            let totalJumlahItem = 0;
            divElm.querySelectorAll(`.noUrutStc[data-par="${idPar}"]`).forEach((item) => {
                totalJumlahItem += sysNum(closest(item, "tr").querySelector(".ji").value);
            });

            const maksimumJumlahItem = sysNum(trElm.querySelector(".ji").dataset.jiMax);
            if (totalJumlahItem > maksimumJumlahItem) {
                alert(`
                Melebihi batas maximum Penerimaan.
                Jumlah maksimum yang bisa diinputkan adalah ${maksimumJumlahItem},
                Silahkan cek Penerimaan lainnya terlebih dahulu!`
                );
                jumlahKemasan = 0;
                jumlahItem = 0;
                trElm.fields.jumlahKemasanWgt.value = userInt(jumlahKemasan);
            }

            const hargaKemasan = sysNum(trElm.querySelector(".hk").value);
            const diskonItem = sysNum(trElm.querySelector(".di").value);
            const hargaTotal = jumlahKemasan * hargaKemasan;
            const diskonHarga = hargaTotal * diskonItem / 100;
            const hargaAkhir = hargaTotal - diskonHarga;

            trElm.querySelector(".ji").value = preferInt(jumlahItem);
            trElm.querySelector(".ht").value = currency(hargaTotal);
            trElm.querySelector(".dh").value = currency(diskonHarga);
            trElm.querySelector(".ha").value = currency(hargaAkhir);
            hitungTotal();
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: itemWgt.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["namaSediaan"],
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.KatalogFields} item */
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
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.KatalogFields} item */
            itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.namaSediaan})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $katalogAcplUrl ?>",
                    data: {query: typed, idUnitPosisi: 60, statusTersedia: 0},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.KatalogFields} */
                const option = this.options[value];
                const {stokAdm, idKatalog, kemasan} = option;
                let found = 0;

                divElm.querySelectorAll(".idKatalogFld").forEach(/** @type {HTMLInputElement} */item => {
                    if (item.value == idKatalog) found++;
                });

                if (found) {
                    this.blur();
                    this.clear();
                    return alert(str._<?= $h("Katalog sudah ditambahkan. Silahkan pilih yang lain.") ?>);
                }

                const jumlahKemasan = (stokAdm < 1) ? 0 : 1;
                const trStr = drawTr("tbody", {
                    class: "tr-data",
                    id: idKatalog,
                    td_1: {
                        class: "tdlist",
                        hidden_1: {name: "idKatalog[]", value: idKatalog},
                        hidden_2: {name: "noUrut[]", value: 1},
                        hidden_3: {name: "idPabrik[]", value: option.idPabrik},
                        hidden_4: {name: "kemasan[]", value: kemasan},
                        hidden_5: {name: "isiKemasan[]", value: option.isiKemasan},
                        hidden_6: {name: "idKemasan[]", value: option.idKemasan},
                        hidden_7: {name: "idKemasanDepo[]", value: option.idSatuan},
                        staticText: {class: "no"}
                    },
                    td_2: {
                        button: {class: ".btn-stok", text: str._<?= $h("Stok") ?>},
                        staticText: {class: ".nb", text: option.namaSediaan}
                    },
                    td_3: {class: ".np", text: option.namaPabrik},
                    td_4: {class: ".km", text: kemasan},
                    td_5: {class: "readonly stock", text: userInt(stokAdm)},
                    td_6: {class: ".no_batch", "data-par": userInt(idKatalog), no_urut: 1, text: 1},
                    td_7: {
                        select: {class: ".bch", name: "noBatch[]", id_katalog: idKatalog}
                    },
                    td_8: {
                        input: {class: ".jk", name: "jumlahKemasan[]", value: jumlahKemasan, "data-jkMax": stokAdm}
                    },
                    td_9: {
                        button: {class: "btn-add", text: "+", title: str._<?= $h("Tambah Batch Katalog") ?>}
                    },
                });
                divElm.querySelector(".tr-input").insertAdjacentHTML("beforebegin", trStr);

                let totalJumlahKemasan = 0; // total sebelum
                itemWgt.querySelectorAll("tbody tr").forEach(/** @param {HTMLTableRowElement} item */ item => {
                    totalJumlahKemasan += sysNum(item.fields.jumlahKemasanWgt.value);
                });
                divElm.querySelector(".jk_tot").value = userInt(totalJumlahKemasan);

                const element = createBatch(divElm.querySelector(`.noBatchFld[id_katalog="${idKatalog}"]:last-child`));
                const noBatch = null;

                if (batch[idKatalog]) {
                    element.fieldWidget.addOption(batch[idKatalog]);
                    element.fieldWidget.setValue(noBatch);
                    return;
                }

                $.post({
                    url: "<?= $batchTabungUrl ?>",
                    data: {idKatalog, idUnitPosisi: 60, statusTersedia: 0},
                    error() {return []},
                    success(data) {
                        if (!data.length) return;

                        batch.push(idKatalog);
                        batch[idKatalog] = data;

                        element.fieldWidget.addOption(data);
                        element.fieldWidget.setValue(noBatch);
                    }
                });
            }
        });

        const batchWgt = new spl.TableWidget({
            element: divElm.querySelector(".batchTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noBatch"},
                3: {field: "tanggalKadaluarsa", formatter: tlm.dateFormatter},
                4: {field: "unitPemilik"},
                5: {field: "statusTersedia" formatter: val => val == "0" ? str._<?= $h("Kosong") ?> : str._<?= $h("Berisi") ?>}
            }
        });

        // JUNK -----------------------------------------------------

        function sortnumberItem(className) {
            divElm.querySelectorAll(`.noUrutStc[data-par="${className}"]`).forEach((item, i) => item.innerHTML = i + 1);
        }

        function createBatch(elm) {
            return new spl.SelectWidget({
                element: elm || divElm.querySelector(".noBatchFld"),
                valueField: "noBatch",
                searchField: ["noBatch"],
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.BatchFields} item */
                optionRenderer(item) {
                    const exp = (obj.tanggalKadaluarsa && obj.tanggalKadaluarsa != "0000-00-00") ? "<br/>Kadaluarsa: " + userDate(obj.tanggalKadaluarsa) : "";

                    return `
                        <div class="option">
                            <span class="name">[${item.idKatalog}] <b>${item.noBatch}</b></span><br/>
                            <span class="description">[${item.unitPemilik}] ${exp}</span>
                        </div>`;
                },
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.BatchFields} item */
                itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.noBatch})</div>`},
                onItemAdd(value) {
                    /** @type {his.FatmaPharmacy.views.ReturFarmasi.AddGasMedis.BatchFields} */
                    const obj = this.options[value];
                    const ele = this;

                    if (obj.jumlahAdm) {
                        divElm.querySelectorAll(`.noBatchFld[id_katalog="${obj.idKatalog}"]`).forEach(/** @type {HTMLSelectElement} */item => {
                            if (item.value == obj.noBatch) {
                                alert(str._<?= $h("No. Batch sudah dipilih. Tidak bisa dipilih lagi.") ?>);
                                ele.removeItem(value);
                            }
                        });
                    } else {
                        alert(str._<?= $h("Tidak ada Stok di Gudang untuk tabung dengan no. Batch tersebut.") ?>);
                        ele.removeItem(value);
                    }
                }
            });
        }

        divElm.querySelector(".btn-add").addEventListener("click", (event) => {
            /** @type {HTMLTableRowElement} */ const trElm = closest(event.target, "tr");
            const idKatalog = trElm.id;
            const stok = sysNum(trElm.fields.stokAdmStc.innerHTML);
            if (divElm.querySelectorAll(`.noUrutStc[data-par="${idKatalog}"]`).length < stok) {
                const noUrut = divElm.querySelectorAll(`.noUrutStc[data-par="${idKatalog}"]`).length + 1;
                const maksimumJumlahKemasan = trElm.fields.jumlahKemasanFld.dataset.jkMax;
                const jumlahKemasan = (maksimumJumlahKemasan < 1) ? 0 : 1;
                const noBatchElm = divElm.querySelector(`.noUrutStc[data-par="${idKatalog}"]:last-child`);

                const trStr = drawTr("tbody", {
                    class: "tr-data detail",
                    td_1: {
                        colspan: 5,
                        hidden_1: {name: "idKatalog[]", value: idKatalog},
                        hidden_2: {name: "noUrut[]", value: noUrut},
                    },
                    td_2: {class: ".no_batch", "data-par": idKatalog, no_urut: noUrut},
                    td_3: {
                        select: {class: ".bch", name: "noBatch[]", id_katalog: idKatalog}
                    },
                    td_4: {
                        input: {class: ".jk", name: "jumlahKemasan[]", value: jumlahKemasan}
                    },
                    td_5: {
                        button: {class: ".btn-delete", icon: "trash", title: str._<?= $h("Hapus Katalog") ?>}
                    },
                });
                closest(noBatchElm, "tr").insertAdjacentHTML("afterend", trStr);

                const noBatchFld = createBatch(divElm.querySelector(`.noBatchFld[id_katalog="${idKatalog}"]:last-child`));
                noBatchFld.fieldWidget.addOption(batch[idKatalog]);

                const lastTrElm = closest(noBatchElm, "tr");
                lastTrElm.fields.noBatchFld.dispatchEvent(new Event("focus"));
            }
            sortnumberItem(idKatalog);
        });

        verGudangFld.addEventListener("click", () => {
            const isChecked = verGudangFld.checked;
            userGudangStc.innerHTML = isChecked ? userName : "-";
            tanggalGudangStc.innerHTML = isChecked ? nowVal("user") : "-";
        });

        verTerimaFld.addEventListener("click", () => {
            const isChecked = verTerimaFld.checked;
            userTerimaStc.innerHTML = isChecked ? userName : "-";
            tanggalTerimaStc.innerHTML = isChecked ? nowVal("user") : "-";
            updateStokMarkerFld.checked = isChecked;
        });

        verAkuntansiFld.addEventListener("click", () => {
            const isChecked = verAkuntansiFld.checked;
            userAkuntansiStc.innerHTML = isChecked ? userName : "-";
            tanggalAkuntansiStc.innerHTML = isChecked ? nowVal("user") : "-";
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(returFarmasiWgt, tanggalDokumenWgt, noDokumenWgt, idPemasokWgt, itemWgt, idKatalogWgt, batchWgt);
        tlm.app.registerWidget(this.constructor.widgetName, returFarmasiWgt);
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
