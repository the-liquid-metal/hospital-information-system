<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\KonsinyasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Konsinyasi/addresep.php the original file
 *
 * TODO: js: uncategorized: flip js and html
 */
final class FormResep
{
    private string $output;

    public function __construct(
        string      $registerId,
        array       $editAccess,
        string      $dataUrl,
        string      $actionUrl,
        string      $cekUnikNoDokumenUrl,
        string      $pemasokAcplUrl,
        string      $katalogAcplUrl,
        string      $perencanaanUrl,
        string      $caraBayarSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $nt = 0;
        $nd = 0;
        $nTd = 0;
        $np = 0;
        $nTp = 0;
        $data = [];

        $action = Yii::$app->request->post("action");
        $judulHeading = ($action == null) ? "Peresepan BMHP Konsinyasi" : "???";
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Konsinyasi.AddResep {
    export interface FormFields {
        action:              "action",
        submit:              "submit",
        kode:                "kode",
        kodeRekamMedis:      "no_rm",
        idCaraBayar:         "id_carabayar",
        noDokumen:           "no_doc",
        tanggalDokumen:      "tgl_doc",
        namaPasien:          "nama_pasien",
        idPemasok:           "id_pbf",
        idDiagnosa:          "idDiagnosa", // ???
        kodeReferensiTerima: "kode_refftrm",
        keterangan:          "keterangan",
        sebelumDiskon:       "___", // exist but missing
        diskon:              "___", // exist but missing
        setelahDiskon:       "___", // exist but missing
        ppn:                 "___", // exist but missing
        subtotal:            "___", // exist but missing
        pembulatan:          "___", // exist but missing
        nilaiTotal:          "___", // exist but missing
        verTerima:           "ver_terima",
        verGudang:           "ver_gudang",
        verAkuntansi:        "ver_akuntansi",
    }

    export interface TableFields {
        idRefKatalog:  "id_reffkatalog[]",
        idPabrik:      "id_pabrik[]",
        kemasan:       "kemasan[]",
        idKemasanDepo: "id_kemasandepo[]",
        noUrut:        "no_urut[]",
        idKatalog:     "id_katalog[]",
        idKatalog2:    "idKatalog2", // ???
        idKemasan:     "id_kemasan[]",
        isiKemasan:    "isi_kemasan[]",
        jumlahKemasan: "jumlah_kemasan[]",
        jumlahItem:    "___", // exist but missing
        hargaKemasan:  "harga_kemasan[]",
        hargaItem:     "___", // exist but missing
        diskonItem:    "diskon_item[]",
        hargaTotal:    "___", // exist but missing
        diskonHarga:   "___", // exist but missing
        hargaAkhir:    "___", // exist but missing
    }

    export interface KatalogFields {
        isiKemasan:    string;
        satuan:        string;
        satuanJual:    string;
        idKatalog:     string;
        namaSediaan:   string;
        namaPabrik:    string;
        jumlahItem:    string;
        hargaKemasan:  string;
        hargaItem:     string;
        diskonItem:    string;
        idKemasanDepo: string;
        idKemasan:     string;
        idPabrik:      string;
    }

    export interface PemasokFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface StokTableFields {
        namaDepo:      string;
        jumlahStokAdm: string;
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
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".addResepFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"}, // $action
                    hidden_2: {class: ".submitFld", name: "submit", value: "save"}, // FROM SUBMIT BUTTON
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Cara bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Peresepan") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaPasienFld", name: "namaPasien"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Diagnosa") ?>,
                        input: {class: ".idDiagnosaFld"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("No. BA") ?>,
                        input: {class: ".kodeReferensiTerimaFld", name: "kodeReferensiTerima"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Subtotal") ?>,
                        staticText: {class: ".subtotalStc"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Nilai Total") ?>,
                        staticText: {class: ".nilaiTotalStc"}
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
                            td_5: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6: {colspan: 2, text: tlm.stringRegistry._<?= $h("Pengadaan") ?>},
                            td_7: {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9: {colspan: 3, text: tlm.stringRegistry._<?= $h("Total") ?>}
                        },
                        tr_2: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Rp.") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_2: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_3: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_4: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                                hidden_5: {class: ".noUrutFld", name: "noUrut[]"},
                                staticText: {class: ".no"}
                            },
                            td_2: {
                                select: {class: ".idKatalogFld", name: "idKatalog[]"}
                            },
                            td_3: {text: ""}, // TODO: js: uncategorized: what is this?
                            td_4: {
                                select: {class: ".idKemasanFld", name: "idKemasan[]"}
                            },
                            td_5: {
                                input: {class: ".isiKemasanFld", name: "isiKemasan[]"}
                            },
                            td_6: {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]"}
                            },
                            td_7: {class: ".jumlahItemStc"},
                            td_8: {
                                input: {class: ".hargaKemasanFld", name: "hargaKemasan[]"}
                            },
                            td_9: {class: ".hargaItemStc"},
                            td_10: {
                                input: {class: ".diskonItemFld", name: "diskonItem[]"}
                            },
                            td_11: {class: ".hargaTotalStc"},
                            td_12: {class: ".diskonHargaStc"},
                            td_13: {class: ".hargaAkhirStc"},
                            td_14: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {colspan: 6},
                            td_2: {
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
                        tr_1: {
                            td_1: {text: "1"},
                            td_2: {
                                checkbox: {class: ".verTerimaFld", name: "verTerima", value: 1} // $data['verterima'] ?? ""
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Tim Penerima") ?>},
                            td_4: {class: ".userTerimaStc"},                                       // $data['user_terima'] ?? "-"
                            td_5: {class: ".tanggalTerimaStc"},                                    // $data['ver_tglterima'] ? $toUserDatetime($data['ver_tglterima']) : "-"
                            td_6: {text: ""},
                        },
                        tr_2: {
                            td_1: {text: "2"},
                            td_2: {
                                checkbox: {class: ".verGudangFld", name: "verGudang", value: 1} // $data['vergudang'] ?? ""
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Gudang") ?>},
                            td_4: {class: ".userGudangStc"},                                     // $data['user_gudang'] ?? "-"
                            td_5: {class: ".tanggalGudangStc"},                                  // $data['ver_tglgudang'] ? $toUserDatetime($data['ver_tglgudang']) : "-"
                            td_6: {
                                checkbox: {class: ".updateStokMarkerFld", value: 1}              // $data['stokgudang'] ?? ""
                            },
                        },
                        tr_3: {
                            td_1: {text: "3"},
                            td_2: {
                                checkbox: {class: ".verAkuntansiFld", name: "verAkuntansi", value: 1} // $data['verakuntansi'] ?? ""
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Akuntansi") ?>},
                            td_4: {class: ".userAkuntansiStc"},                                         // $data['user_akuntansi'] ?? "-"
                            td_5: {class: ".tanggalAkuntansiStc"},                                      // $data['ver_tglakuntansi'] ? $toUserDatetime($data['ver_tglakuntansi']) : "-"
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
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {toCurrency: currency, toSystemNumber: sysNum, toUserInt: userInt, stringRegistry: str, preferInt, nowVal} = tlm;
        const drawTr = spl.TableDrawer.drawTr;
        const userName = tlm.user.nama;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */      const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */    const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */    const kodeRekamMedisFld = divElm.querySelector(".kodeRekamMedisFld");
        /** @type {HTMLSelectElement} */   const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLInputElement} */    const namaPasienFld = divElm.querySelector(".namaPasienFld");
        /** @type {HTMLInputElement} */    const idDiagnosaFld = divElm.querySelector(".idDiagnosaFld");
        /** @type {HTMLInputElement} */    const kodeReferensiTerimaFld = divElm.querySelector(".kodeReferensiTerimaFld");
        /** @type {HTMLTextAreaElement} */ const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLDivElement} */      const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */      const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */      const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLInputElement} */    const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */      const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLDivElement} */      const subtotalStc = divElm.querySelector(".subtotalStc");
        /** @type {HTMLDivElement} */      const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */      const nilaiTotalStc = divElm.querySelector(".nilaiTotalStc");
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

        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idCaraBayarFld);

        const addResepWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".addResepFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Konsinyasi.AddResep.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                kodeRekamMedisFld.value = data.kodeRekamMedis ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                namaPasienFld.value = data.namaPasien ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
                idDiagnosaFld.value = data.idDiagnosa ?? "";
                kodeReferensiTerimaFld.value = data.kodeReferensiTerima ?? "";
                keteranganFld.value = data.keterangan ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                subtotalStc.innerHTML = data.subtotal ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiTotalStc.innerHTML = data.nilaiTotal ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                edit() {
                    formTitleTxt.innerHTML = str._<?= $h($judulHeading) ?>;

                    sebelumDiskonStc.innerHTML = currency("<?= $nt ?>");
                    diskonStc.innerHTML = currency("<?= $nd ?>");
                    setelahDiskonStc.innerHTML = currency("<?= $nTd ?>");
                    ppnStc.innerHTML = currency("<?= $np ?>");
                    subtotalStc.innerHTML = currency("<?= $nTp ?>");
                    pembulatanStc.innerHTML = currency("<?= round($nTp) - $nTp ?>");
                    nilaiTotalStc.innerHTML = currency("<?= round($nTp) ?>");

                    /** @type {his.FatmaPharmacy.views.Konsinyasi.AddResep.PemasokFields} */
                    const data = JSON.parse(`<?= json_encode($data) ?>`);
                    idPemasokWgt.addOption(data);
                    idPemasokWgt.value = data.id;
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        /** @param {HTMLTableRowElement} trElm */
        function hitungSubTotal(trElm) {
            const fields = trElm.fields;
            const hargaKemasan = sysNum(fields.hargaKemasanWgt.value);
            const jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
            const diskonItem = sysNum(fields.diskonItemWgt.value);
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const jumlahItem = jumlahKemasan * isiKemasan;
            const hargaItem = hargaKemasan / isiKemasan;
            const hargaTotal = jumlahKemasan * hargaKemasan;
            const diskonHarga = hargaTotal * diskonItem / 100;
            const hargaAkhir = hargaTotal - diskonHarga;

            fields.jumlahItemStc.innerHTML = preferInt(jumlahItem);
            fields.hargaItemStc.innerHTML = currency(hargaItem);
            fields.hargaTotalStc.innerHTML = currency(hargaTotal);
            fields.diskonHargaStc.innerHTML = currency(diskonHarga);
            fields.hargaAkhirStc.innerHTML = currency(hargaAkhir);
        }

        function hitungTotal() {
            let sebelumDiskon = 0;
            let nilaiDiskon = 0;
            itemWgt.querySelectorAll("tbody tr").forEach(/** @param {HTMLTableRowElement} trElm */ trElm => {
                const fields = trElm.fields;
                sebelumDiskon += sysNum(fields.hargaTotalStc.innerHTML);
                nilaiDiskon += sysNum(fields.diskonHargaStc.innerHTML);
            });

            const setelahDiskon = sebelumDiskon - nilaiDiskon;
            const ppn = ppnFld.checked ? 10 : 0;
            const nilaiPpn = ppn * setelahDiskon / 100;
            const setelahPpn = setelahDiskon + nilaiPpn;
            const nilaiAkhir = Math.floor(setelahPpn);
            const pembulatan = nilaiAkhir - setelahPpn;

            sebelumDiskonStc.innerHTML = currency(sebelumDiskon);
            diskonStc.innerHTML = currency(nilaiDiskon);
            setelahDiskonStc.innerHTML = currency(setelahDiskon);
            ppnStc.innerHTML = currency(nilaiPpn);
            subtotalStc.innerHTML = currency(setelahPpn);
            pembulatanStc.innerHTML = currency(pembulatan);
            nilaiTotalStc.innerHTML = currency(nilaiAkhir);
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

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            errorRules: [{required: true}],
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Konsinyasi.AddResep.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Konsinyasi.AddResep.PemasokFields} item */
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

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        ppnFld.addEventListener("change", hitungTotal);

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Konsinyasi.AddResep.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogWgt.value = data.idKatalog ?? "";
                fields.idPabrikWgt.value = data.idPabrik ?? "";
                fields.kemasanFld.value = data.kemasan ?? "";
                fields.idKemasanDepoFld.value = data.idKemasanDepo ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.isiKemasanWgt.value = data.isiKemasan ?? "";
                fields.jumlahKemasanWgt.value = data.jumlahKemasan ?? "";
                fields.jumlahItemStc.innerHTML = data.jumlahItem ?? "";
                fields.hargaKemasanWgt.value = data.hargaKemasan ?? "";
                fields.hargaItemStc.innerHTML = data.hargaItem ?? "";
                fields.diskonItemWgt.value = data.diskonItem ?? "";
                fields.hargaTotalStc.innerHTML = data.hargaTotal ?? "";
                fields.diskonHargaStc.innerHTML = data.diskonHarga ?? "";
                fields.hargaAkhirStc.innerHTML = data.hargaAkhir ?? "";
            },
            addRow(trElm) {
                const idKatalogWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".idKatalogFld"),
                    maxItems: 1,
                    valueField: "idKatalog",
                    searchField: ["idKatalog", "namaSediaan"],
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    /** @param {his.FatmaPharmacy.views.Konsinyasi.AddResep.KatalogFields} item */
                    optionRenderer(item) {
                        const isiKemasan = (item.isiKemasan == 1) ? item.satuan : `${item.satuanJual} ${preferInt(item.isiKemasan)} ${item.satuan}`;
                        return `
                            <div class="option  col-xs-12  tbl-row-like">
                                <div class="col-xs-2"><b>${item.idKatalog}</b></div>
                                <div class="col-xs-5"><b>${item.namaSediaan}</b></div>
                                <div class="col-xs-3">${item.namaPabrik}</div>
                                <div class="col-xs-2">${isiKemasan}</div>
                            </div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.Konsinyasi.AddResep.KatalogFields} item */
                    itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.namaSediaan})</div>`},
                    load(typed, processor) {
                        if (!typed.length) {
                            processor([]);
                            return;
                        }

                        const idJenis = "";    // TODO: js: uncategorized: check whether this var needs a value or not (compare with other view files)
                        $.post({
                            url: "<?= $katalogAcplUrl ?>",
                            data: {query: typed, idJenis},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    },
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.Konsinyasi.AddResep.KatalogFields} */
                        const obj = this.options[value];
                        const {isiKemasan, satuan, satuanJual, idKemasanDepo, idKemasan, idKatalog} = obj;
                        let {idPabrik, namaSediaan, namaPabrik, hargaKemasan, diskonItem, jumlahItem} = obj;

                        let found = 0;
                        divElm.querySelectorAll(".idKatalogFld").forEach(/** @type {HTMLInputElement} */item => {
                            if (item.value == idKatalog) found++;
                        });

                        if (found) {
                            this.blur();
                            this.clear();
                            alert(str._<?= $h("Katalog sudah ditambahkan. Silahkan pilih yang lain.") ?>);
                            return;
                        }

                        const idCaraBayar = idCaraBayarFld.value;
                        const caraBayarIsForFree = (idCaraBayar == "18" || idCaraBayar == "17");
                        const kemasan = (isiKemasan == 0) ? satuan : `${satuanJual} ${preferInt(isiKemasan)} ${satuan}`;
                        hargaKemasan = caraBayarIsForFree ? 0 : hargaKemasan;

                        const options = {
                            option_1: {value: idKemasanDepo, "data-is": 1,          ids: idKemasanDepo, sat: satuan, satj: satuan,     "data-hk": hargaKemasan, text: satuan},
                            option_2: {value: idKemasan,     "data-is": isiKemasan, ids: idKemasanDepo, sat: satuan, satj: satuanJual, "data-hk": hargaKemasan, text: kemasan},
                        };
                        (isiKemasan == 1) ? delete options.option_2 : null;

                        // TODO: js: uncategorized: recheck again with earlier commit, some definition is different with splBulkInput
                        const trAddElm = divElm.querySelector(".tr-add");
                        const trStr = drawTr("tbody", {
                            class: "tr-data",
                            id: idKatalog,
                            td_1: {
                                hidden_1: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                                hidden_2: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                                hidden_3: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                                hidden_4: {class: ".kemasanFld", name: "kemasan[]", value: isiKemasan},
                                hidden_5: {class: ".idKemasanDepoFld", name: "noUrut[]", value: 1},
                                hidden_6: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                                staticText: {class: ".no", text: 1}
                            },
                            td_2: {
                                class: "DIFF-WITH-SPLBULKINPUT",
                                button: {class: ".stokBtn", text: str._<?= $h("Stok") ?>},
                                staticText: {class: ".nb", text: namaSediaan}
                            },
                            td_3: {class: "np DIFF-WITH-SPLBULKINPUT", text: namaPabrik},
                            td_4: {
                                select: {class: ".idKemasanFld", name: "idKemasan[]", ...options}
                            },
                            td_5: {
                                input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: 1, readonly: true}
                            },
                            td_6: {
                                class: "DIFF-WITH-SPLBULKINPUT",
                                button: {class: ".btn-addbch", text: "+"},
                                staticText: {class: ".no_batch", par: idKatalog, "data-no_urut": 1, text: 1}
                            },
                            td_7: {
                                class: "DIFF-WITH-SPLBULKINPUT",
                                input: {class: ".bch", name: "no_batch[]", id_katalog: idKatalog}
                            },
                            td_8: {
                                class: "DIFF-WITH-SPLBULKINPUT",
                                input: {class: ".exp", name: "tgl_expired[]"}
                            },
                            td_9: {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: preferInt(jumlahItem)}
                            },
                            td_10: {class: ".jumlahItemStc"},
                            td_11: {
                                input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: currency(hargaKemasan)}
                            },
                            td_12: {class: ".hargaItemStc"},
                            td_13: {
                                input: {class: ".diskonItemFld", name: "diskonItem[]", value: userInt(diskonItem)}
                            },
                            td_14: {class: ".hargaTotalStc"},
                            td_15: {class: ".diskonHargaStc"},
                            td_16: {class: ".hargaAkhirStc"},
                        });
                        trAddElm.insertAdjacentHTML("beforebegin", trStr);
                        hitungSubTotal(/** @type {HTMLTableRowElement} */ trAddElm.previousElementSibling);
                        hitungTotal();
                        itemWgt.querySelector("tbody tr:last-child").fields.idKemasanFld.dispatchEvent(new Event("focus"));
                    }
                });

                const idPabrikWgt = new spl.InputWidget({
                    element: trElm.querySelector(".idPabrikFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ]
                });

                const jumlahKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.intNumberSetting
                });

                const hargaKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".hargaKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.currNumberSetting
                });

                const isiKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".isiKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.floatNumberSetting
                });

                const diskonItemWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".diskonItemFld"),
                    errorRules: [
                        {greaterThanEqual: 0},
                        {lessThanEqual: 100}
                    ],
                    warningRules: [{lessThanEqual: 75, message: str._<?= $h("melebihi 75%") ?>}],
                    ...tlm.intNumberSetting
                });

                trElm.fields = {
                    idKatalogWgt,
                    idPabrikWgt,
                    jumlahKemasanWgt,
                    hargaKemasanWgt,
                    isiKemasanWgt,
                    diskonItemWgt,
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                    hargaItemStc: trElm.querySelector(".hargaItemStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                    hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.idKatalogWgt.destroy();
                fields.idPabrikWgt.destroy();
                fields.jumlahKemasanWgt.destroy();
                fields.hargaKemasanWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.diskonItemWgt.destroy();

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
            addRowBtn: ".addResepFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "change", (event) => {
            const idKemasanFld = event.target;
            if (!idKemasanFld.matches(".idKemasanFld")) return;

            const trElm = closest(idKemasanFld, "tr");
            const fields = trElm.fields;

            const kemasanOpt = idKemasanFld.selectedOptions[0];
            const kemasan = kemasanOpt.innerHTML;
            const isiKemasan = sysNum(kemasanOpt.dataset.is);
            const hargaKemasan = sysNum(kemasanOpt.dataset.hk);

            fields.kemasanFld.value = kemasan;
            fields.isiKemasanWgt.value = preferInt(isiKemasan);
            fields.hargaKemasanWgt.value = currency(hargaKemasan);
            hitungSubTotal(trElm);
            hitungTotal();
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const fieldElm = event.target;
            if (!fieldElm.matches(".jumlahKemasanFld, .hargaKemasanFld, .diskonItemFld")) return;

            hitungSubTotal(closest(fieldElm, "tr"));
            hitungTotal();
        });

        // JUNK -----------------------------------------------------

        divElm.querySelector(".btn-addbch").addEventListener("click", (event) => {
            const trElm = closest(event.target, "tr");
            const fields = trElm.fields;
            const idKatalog = trElm.id;
            const noUrut = divElm.querySelectorAll(`.no_batch[par="${idKatalog}"]`).length + 1;
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const hargaItem = sysNum(fields.hargaItemStc.innerHTML);
            const diskonItem = sysNum(fields.diskonItemWgt.value);

            // TODO: js: uncategorized: recheck again with earlier commit, some definition is different with splBulkInput
            const lastTrElm = closest(divElm.querySelector(`.no_batch[par="${idKatalog}"]:last-child`), "tr");
            const trStr = drawTr("tbody", {
                class: "tr-data",
                id: idKatalog,
                td_1: {
                    colspan: 4,
                    hidden_1: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                    hidden_2: {class: ".noUrutFld", name: "noUrut[]", value: noUrut},
                },
                td_2: {
                    input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: isiKemasan, readonly: true}
                },
                td_3: {
                    class: "DIFF-WITH-SPLBULKINPUT",
                    button: {class: ".btn-delbch", text: "-"},
                    staticText: {class: ".no_batch", par: idKatalog, "data-no_urut": 1, text: noUrut}
                },
                td_4: {
                    class: "DIFF-WITH-SPLBULKINPUT",
                    input: {class: ".bch", name: "no_batch[]", id_katalog: idKatalog}
                },
                td_5: {
                    class: "DIFF-WITH-SPLBULKINPUT",
                    input: {class: ".exp", name: "tgl_expired[]"}
                },
                td_6: {
                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: 0}
                },
                td_7: {class: ".jumlahItemStc"},
                td_8: {
                    input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: currency(hargaItem), readonly: true}
                },
                td_9: {class: ".hargaItemStc"},
                td_10: {
                    input: {class: ".diskonItemFld", name: "diskonItem[]", value: userInt(diskonItem), readonly: true}
                },
                td_11: {class: ".hargaTotalStc"},
                td_12: {class: ".diskonHargaStc"},
                td_13: {class: ".hargaAkhirStc"},
            });
            lastTrElm.insertAdjacentHTML("afterend", trStr);
            lastTrElm.nextElementSibling.querySelector(".bch").dispatchEvent(new Event("focus"));
            hitungSubTotal(/** @type {HTMLTableRowElement} */ lastTrElm.nextElementSibling);
        });

        divElm.querySelector(".btn-delbch").addEventListener("click", (event) => {
            const trElm = closest(event.target, "tr");

            trElm.remove();
            let z = 1;
            divElm.querySelectorAll(`.no_batch[par="${trElm.id}"]`).forEach(/** @type {HTMLElement} */item => {
                closest(item, "tr").fields.noUrutFld.value = z;
                item.dataset.no_urut = z;
                item.innerHTML = z;
                z++;
            });
            hitungTotal();
        });

        itemWgt.querySelector(".stokBtn").addEventListener("click", (event) => {
            const stokBtn = /** @type {HTMLButtonElement} */ event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            const trElm = closest(stokBtn, "tr");
            const namaKatalog = trElm.querySelector(".nb").innerHTML ?? "";

            $.post({
                url: "<?= $perencanaanUrl ?>",
                data: {idKatalog: trElm.id},
                success(data) {
                    const total = data.reduce((acc, curr) => acc + curr.jumlahStokAdm, 0);
                    headerElm.innerHTML = str._<?= $h("Nama Barang: {{NAMA}}") ?>.replace("{{NAMA}}", namaKatalog);
                    footerElm.innerHTML = str._<?= $h("Total: {{TOTAL}}") ?>.replace("{{TOTAL}}", total);
                    stokWgt.load(data);
                }
            });
        });

        /** @see {his.FatmaPharmacy.views.Konsinyasi.AddResep.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        const expWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".exp"),
            // TODO: js: uncategorized: add "already expired", and "less than 2 years" rules
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        divElm.querySelector(".bch").addEventListener("focusout", (event) => {
            const bchFld = /** @type {HTMLInputElement} */ event.target;
            const batch = bchFld.value;
            if (batch && divElm.querySelectorAll(`[name='no_batch[]'][value='${batch}']`).length > 1) {
                alert(str._<?= $h("No. Batch sudah diinput. Silahkan cek inputan.") ?>);
                bchFld.value = "";
                bchFld.dispatchEvent(new Event("focus"));
            }
        });

        verGudangFld.addEventListener("click", () => {
            const isChecked = verGudangFld.checked;
            userGudangStc.innerHTML = isChecked ? userName : "";
            tanggalGudangStc.innerHTML = isChecked ? nowVal("user") : "";
        });

        verTerimaFld.addEventListener("click", () => {
            const isChecked = verTerimaFld.checked;
            userTerimaStc.innerHTML = isChecked ? userName : "";
            tanggalTerimaStc.innerHTML = isChecked ? nowVal("user") : "";
            updateStokMarkerFld.checked = isChecked;
        });

        verAkuntansiFld.addEventListener("click", () => {
            const isChecked = verAkuntansiFld.checked;
            userAkuntansiStc.innerHTML = isChecked ? userName : "";
            tanggalAkuntansiStc.innerHTML = isChecked ? nowVal("user") : "";
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(addResepWgt, noDokumenWgt, idPemasokWgt, tanggalDokumenWgt, itemWgt, stokWgt, expWgt);
        tlm.app.registerWidget(this.constructor.widgetName, addResepWgt);
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
