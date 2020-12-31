<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PenerimaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/addothers.php the original file
 */
final class FormLainnya
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $addBonusAccess,
        array  $editAccess,
        array  $verifikasiTerimaAccess,
        array  $verifikasiGudangAccess,
        array  $verifikasiAkuntansiAccess,
        string $editDataUrl,
        string $verifikasiTerimaDataUrl,
        string $verifikasiGudangDataUrl,
        string $verifikasiAkuntansiDataUrl,
        string $addActionUrl,
        string $addBonusActionUrl,
        string $editActionUrl,
        string $verifikasiTerimaActionUrl,
        string $verifikasiGudangActionUrl,
        string $verifikasiAkuntansiActionUrl,
        string $cekUnikNoFakturUrl,
        string $cekUnikNoSuratJalanUrl,
        string $cekUnikNoDokumenUrl,
        string $pemasokAcplUrl,
        string $cekStokUrl,
        string $subjenisAcplUrl,
        string $viewLainnyaWidgetId,
        string $caraBayarSelect,
        string $bulanSelect,
        string $tahunSelect,
        string $sumberDanaSelect,
        string $jenisHargaSelect,
        string $jenisAnggaranSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Penerimaan.AddOthers {
    export interface FormFields {
        action:              "action",
        submit:              "submit",
        kode:                "kode",
        penerimaanKe:        "penerimaanKe", // ???
        tanggalPenerimaan:   "tgl_doc",
        noDokumen:           "no_doc",
        idJenisAnggaran:     "id_jenisanggaran",
        noFaktur:            "no_faktur",
        noSuratJalan:        "no_suratjalan",
        bulanAwalAnggaran:   "blnawal_anggaran",
        bulanAkhirAnggaran:  "blnakhir_anggaran",
        tahunAnggaran:       "thn_anggaran",
        idPemasok:           "id_pbf",
        idSumberDana:        "id_sumberdana",
        idGudangPenyimpanan: "id_gudangpenyimpanan",
        tabungGas:           "sts_tabunggm",
        idJenisHarga:        "id_jenisharga",
        idCaraBayar:         "id_carabayar",
        keterangan:          "keterangan",
        sebelumDiskon:       "___", // exist but missing
        diskon:              "___", // exist but missing
        setelahDiskon:       "___", // exist but missing
        ppn:                 "ppn",
        setelahPpn:          "___",// exist but missing
        pembulatan:          "___",// exist but missing
        nilaiAkhir:          "___",// exist but missing
    }

    export interface TableFields {
        kodeRefPo:         "kode_reffpo[]",
        kodeRefRo:         "kode_reffro[]",
        kodeRefPl:         "kode_reffpl[]",
        kodeRefRencana:    "kode_reffrenc[]",
        idRefKatalog:      "id_reffkatalog[]",
        idKatalog:         "id_katalog[]",
        idPabrik:          "id_pabrik[]",
        kemasan:           "kemasan[]",
        jumlahItemBonus:   "jumlah_itembonus[]",
        jumlahBeli:        "j_beli[]",
        jumlahBonus:       "j_bonus[]",
        noUrut:            "no_urut[]",
        idKemasanDepo:     "id_kemasandepo[]",
        namaSediaan:       "___", // exist but missing
        namaPabrik:        "___", // exist but missing
        idKemasan:         "id_kemasan[]",
        isiKemasan:        "isi_kemasan[]",
        noBatch:           "no_batch[]",
        tanggalKadaluarsa: "tgl_expired[]",
        jumlahKemasan:     "jumlah_kemasan[]",
        jumlahItem:        "___", // exist but missing
        hargaKemasan:      "harga_kemasan[]",
        hargaItem:         "___", // exist but missing
        diskonItem:        "diskon_item[]",
        hargaTotal:        "___", // exist but missing
        diskonHarga:       "___", // exist but missing
        hargaAkhir:        "___", // exist but missing
        verTerima:         "ver_terima",
        verGudang:         "ver_gudang",
        verAkuntansi:      "ver_akuntansi",
    }

    export interface KatalogFields {
        isiKemasan:    string;
        satuan:        string;
        satuanJual:    string;
        idKatalog:     string;
        namaSediaan:   string;
        namaPabrik:    string;
        jumlahItem:    string;
        hargaItem:     string;
        hargaKemasan:  string;
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
     * @returns {{add: boolean, addBonus: boolean, edit: boolean, verifikasiGudang: boolean, verifikasiAkuntansi: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
            addBonus: JSON.parse(`<?=json_encode($addBonusAccess) ?>`),
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            verifikasiTerima: JSON.parse(`<?=json_encode($verifikasiTerimaAccess) ?>`),
            verifikasiGudang: JSON.parse(`<?=json_encode($verifikasiGudangAccess) ?>`),
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
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".tambahPenerimaanLainnyaFrm",
            row_1: {
                widthColumn: {
                    button: {class: ".tarikBtn", title: tlm.stringRegistry._<?= $h("Tarik Item Pembelian") ?>, text: tlm.stringRegistry._<?= $h("Tarik") ?>}
                }
            },
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"}, // $action
                    hidden_2: {class: ".submitFld", name: "submit", value: "save"}, // FROM SUBMIT BUTTON
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Penerimaan Ke-") ?>,
                        input: {class: ".penerimaanKeFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Penerimaan") ?>,
                        input: {class: ".tanggalPenerimaanFld", name: "tanggalPenerimaan"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Faktur") ?>,
                        input: {class: ".noFakturFld", name: "noFaktur"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Surat Jalan") ?>,
                        input: {class: ".noSuratJalanFld", name: "noSuratJalan"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                        select: {
                            class: ".idGudangPenyimpananFld",
                            name: "idGudangPenyimpanan",
                            option_1: {value: 59, label: tlm.stringRegistry._<?= $h("Gudang Induk Farmasi") ?>},
                            option_2: {value: 60, label: tlm.stringRegistry._<?= $h("Gudang Gas Medis") ?>},
                            option_3: {value: 69, label: tlm.stringRegistry._<?= $h("Gudang Konsinyasi") ?>}
                        }
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Tabung Gas") ?>,
                        radio_1: {class: ".tabungGasYes", name: "tabungGas", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                        radio_2: {class: ".tabungGasNo",  name: "tabungGas", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        staticText: {class: ".setelahPpnStc"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Nilai Akhir") ?>,
                        staticText: {class: ".nilaiAkhirStc"}
                    }
                }
            },
            row_3: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr_1: {
                            td_1: {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6: {colspan: 5, text: tlm.stringRegistry._<?= $h("Pengadaan") ?>},
                            td_7: {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9: {colspan: 3, text: tlm.stringRegistry._<?= $h("Total") ?>}
                        },
                        tr_2: {
                            td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {text: tlm.stringRegistry._<?= $h("Batch") ?>},
                            td_3:  {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            td_4:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_6:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_7:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_8:  {text: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>},
                            td_9:  {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_10: {text: tlm.stringRegistry._<?= $h("Rp.") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".kodeRefPoFld", name: "kodeRefPo[]"},
                                hidden_2: {class: ".kodeRefRoFld", name: "kodeRefRo[]"},
                                hidden_3: {class: ".kodeRefPlFld", name: "kodeRefPl[]"},
                                hidden_4: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]"},
                                hidden_5: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_6: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_7: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_8: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_9: {class: ".jumlahItemBonusFld", name: "jumlahItemBonus[]"},
                                hidden_10: {class: ".jumlahBeliFld", name: "jumlahBeli[]"},
                                hidden_11: {class: ".jumlahBonusFld", name: "jumlahBonus[]"},
                                hidden_12: {class: ".noUrutFld", name: "noUrut[]"},
                                hidden_13: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                                staticText: {class: ".no"}
                            },
                            td_2: {
                                staticText: {class: ".namaSediaanStc"},
                                rButton: {class: ".stokBtn"}
                            },
                            td_3: {class: ".namaPabrikStc"},
                            td_4: {
                                select: {class: ".idKemasanFld", name: "idKemasan[]"}
                            },
                            td_5: {
                                input: {class: ".isiKemasanFld", name: "isiKemasan[]"}
                            },
                            td_6: {
                                staticText: {class: ".noUrutStc"}
                            },
                            td_7: {
                                input: {class: ".noBatchFld", name: "noBatch[]"}
                            },
                            td_8: {
                                input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                            },
                            td_9: {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]"}
                            },
                            td_10: {class: ".jumlahItemStc"},
                            td_11: {
                                input: {class: ".hargaKemasanFld", name: "hargaKemasan[]"}
                            },
                            td_12: {class: ".hargaItemStc"},
                            td_13: {
                                input: {class: ".diskonItemFld", name: "diskonItem[]"}
                            },
                            td_14: {class: ".hargaTotalStc"},
                            td_15: {class: ".diskonHargaStc"},
                            td_16: {class: ".hargaAkhirStc"},
                            td_17: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {text: "", colspan: 8},
                            td_2: {text: tlm.stringRegistry._<?= $h("Cari Katalog:") ?>},
                            td_3: {
                                select: {class: ".idKatalogFld"}
                            },
                            td_4: {
                                button: {class: ".addRowBtn", type: "success", size: "xs", label: tlm.stringRegistry._<?= $h("add") ?>}
                            }
                        }
                    }
                }
            },
            row_4: {
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
                                checkbox: {class: ".verTerimaFld", name: "verTerima", value: 1} // ($penerimaan->verTerima == '1') ? "checked disabled" : "disabled"
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Tim Penerima") ?>},
                            td_4: {class: ".userTerimaStc"},                                       // $penerimaan->userTerima ?? "-"
                            td_5: {class: ".tanggalTerimaStc"},                                     // $penerimaan->verTanggalTerima ? $toUserDatetime($penerimaan->verTanggalTerima) : "-"
                            td_6: {text: ""},
                        },
                        tr_2: {
                            td_1: {text: "2"},
                            td_2: {
                                checkbox: {class: ".verGudangFld", name: "verGudang", value: 1} // ($penerimaan->verGudang == '1') ? "checked disabled" : ""
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Gudang") ?>},
                            td_4: {class: ".userGudangStc"},                                       // $penerimaan->userGudang ?? "-"
                            td_5: {class: ".tanggalGudangStc"},                                    // $penerimaan->verTanggalGudang ? $toUserDatetime($penerimaan->verTanggalGudang) : "-"
                            td_6: {
                                checkbox: {class: ".updateStokMarkerFld", value: 1}                // ($penerimaan->verGudang == '1') ? "checked" : "disabled"
                            },
                        },
                        tr_3: {
                            td_1: {text: "3"},
                            td_2: {
                                checkbox: {class: ".verAkuntansiFld", name: "verAkuntansi", value: 1} // ($penerimaan->verAkuntansi == '1') ? "checked" : "disabled"
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Akuntansi") ?>},
                            td_4: {class: ".userAkuntansiStc"},                                        // $penerimaan->userAkuntansi ?? "-"
                            td_5: {class: ".tanggalAkuntansiStc"},                                     // $penerimaan->verTanggalAkuntansi ? $toUserDatetime($penerimaan->verTanggalAkuntansi) : "-"
                            td_6: {text: ""},
                        }
                    }
                }
            },
            row_5: {
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
        const {toUserInt: userInt, toCurrency: currency, toSystemNumber: sysNum, stringRegistry: str, preferInt, nowVal} = tlm;
        const drawTr = spl.TableDrawer.drawTr;
        const userName = tlm.user.nama;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */      const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */    const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */    const submitFld = divElm.querySelector(".submitFld");
        /** @type {HTMLButtonElement} */   const tarikBtn = divElm.querySelector(".tarikBtn");
        /** @type {HTMLInputElement} */    const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */    const penerimaanKeFld = divElm.querySelector(".penerimaanKeFld");
        /** @type {HTMLSelectElement} */   const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */   const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */   const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */   const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */   const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */   const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLInputElement} */    const tabungGasYes = divElm.querySelector(".tabungGasYes");
        /** @type {HTMLInputElement} */    const tabungGasNo = divElm.querySelector(".tabungGasNo");
        /** @type {HTMLSelectElement} */   const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */   const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLTextAreaElement} */ const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLDivElement} */      const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */      const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */      const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLInputElement} */    const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */      const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLDivElement} */      const setelahPpnStc = divElm.querySelector(".setelahPpnStc");
        /** @type {HTMLDivElement} */      const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */      const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");
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

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        let idJenisAnggaranSebelumnya;
        let idGudangPenyimpananSebelumnya;

        const tambahPenerimaanLainnyaWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".tambahPenerimaanLainnyaFrm"),
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddOthers.FormFields} data */
            loadData(data) {
                actionFld.value = data.action ?? "";
                kodeFld.value = data.kode ?? "";
                submitFld.value = data.submit ?? "";
                penerimaanKeFld.value = data.penerimaanKe ?? "";
                tanggalPenerimaanWgt.value = data.tanggalPenerimaan ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noFakturWgt.value = data.noFaktur ?? "";
                noSuratJalanWgt.value = data.noSuratJalan ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                data.tabungGas ? tabungGasYes.checked = true : tabungGasNo.checked = true;
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                keteranganFld.value = data.keterangan ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";

                idJenisAnggaranSebelumnya = data.idJenisAnggaran;
                idGudangPenyimpananSebelumnya = data.idGudangPenyimpanan;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.load({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Penerimaan Donasi/COD/Konsinyasi") ?>;
                    // $action = 'add';
                    // $anggaran = '';
                    // $bDelete = '';
                    // $bPrint = 'disabled';
                    // $bSave = 'disabled';
                    // $bTarik = '';
                    // $carabayar = '';
                    // $cekAll = '';
                    // $gudang = '';
                    // $idPbf = '';
                    // $item = 'disabled';
                    // $jenisharga = '';
                    // $katalog = '';
                    // $keterangan = '';
                    // $mataAnggaran = '';
                    // $noDoc = '';
                    // $noFaktur = '';
                    // $noSuratJalan = '';
                    // $ppn = '';
                    // $sumberDana = '';
                    // $tglDoc = '';
                },
                addBonus() {
                    this._actionUrl = "<?= $addBonusActionUrl ?>";
                    this.load({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Penerimaan Donasi/COD/Konsinyasi") ?>;
                    // $action = 'add';
                    // $anggaran = '';
                    // $bDelete = '';
                    // $bPrint = 'disabled';
                    // $bSave = 'disabled';
                    // $bTarik = '';
                    // $carabayar = 'disabled';
                    // $cekAll = '';
                    // $gudang = 'disabled';
                    // $idPbf = 'disabled';
                    // $item = 'disabled';
                    // $jenisharga = 'disabled';
                    // $katalog = 'disabled';
                    // $keterangan = 'disabled';
                    // $mataAnggaran = 'disabled';
                    // $noDoc = '';
                    // $noFaktur = '';
                    // $noSuratJalan = '';
                    // $ppn = 'disabled';
                    // $sumberDana = 'disabled';
                    // $tglDoc = '';
                },
                /** @param {his.FatmaPharmacy.views.Penerimaan.AddOthers.FormFields} data */
                edit(data) {
                    this._dataUrl = "<?= $editDataUrl ?>";
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;
                    // $anggaran = '';
                    // $bDelete = '';
                    // $bPrint = '';
                    // $bSave = '';
                    // $bTarik = '';
                    // $carabayar = '';
                    // $cekAll = '';
                    // $gudang = '';
                    // $idPbf = '';
                    // $item = '';
                    // $jenisharga = '';
                    // $katalog = '';
                    // $keterangan = '';
                    // $mataAnggaran = '';
                    // $noDoc = '';
                    // $noFaktur = '';
                    // $noSuratJalan = '';
                    // $ppn = '';
                    // $sumberDana = '';
                    // $tglDoc = '';

                    formTitleTxt.innerHTML = str._<?= $h("Ubah Penerimaan Donasi/COD/Konsinyasi") ?>;

                    if (!data.idPemasok) return;

                    if (data.objPbf) {
                        idPemasokWgt.addOption(data.objPbf);
                        idPemasokWgt.value = data.objPbf.id;
                    }
                },
                verifikasiTerima(data) {
                    this._dataUrl = "<?= $verifikasiTerimaDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiTerimaActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;
                    // $anggaran = 'disabled';
                    // $bDelete = 'disabled';
                    // $bPrint = '';
                    // $bSave = '';
                    // $bTarik = 'disabled';
                    // $carabayar = 'disabled';
                    // $cekAll = 'disabled';
                    // $gudang = 'disabled';
                    // $idPbf = 'disabled';
                    // $item = 'disabled';
                    // $jenisharga = 'disabled';
                    // $katalog = 'disabled';
                    // $keterangan = 'disabled';
                    // $mataAnggaran = 'disabled';
                    // $noDoc = 'disabled';
                    // $noFaktur = 'disabled';
                    // $noSuratJalan = 'disabled';
                    // $ppn = 'disabled';
                    // $sumberDana = 'disabled';
                    // $tglDoc = 'disabled';
                },
                verifikasiGudang(data) {
                    this._dataUrl = "<?= $verifikasiGudangDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiGudangActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;
                    // $anggaran = 'disabled';
                    // $bDelete = 'disabled';
                    // $bPrint = '';
                    // $bSave = '';
                    // $bTarik = 'disabled';
                    // $carabayar = 'disabled';
                    // $cekAll = 'disabled';
                    // $gudang = 'disabled';
                    // $idPbf = 'disabled';
                    // $item = 'disabled';
                    // $jenisharga = 'disabled';
                    // $katalog = 'disabled';
                    // $keterangan = 'disabled';
                    // $mataAnggaran = 'disabled';
                    // $noDoc = 'disabled';
                    // $noFaktur = 'disabled';
                    // $noSuratJalan = 'disabled';
                    // $ppn = 'disabled';
                    // $sumberDana = 'disabled';
                    // $tglDoc = 'disabled';
                },
                verifikasiAkuntansi(data) {
                    this._dataUrl = "<?= $verifikasiAkuntansiDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiAkuntansiActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;
                    // $anggaran = 'disabled';
                    // $bDelete = 'disabled';
                    // $bPrint = '';
                    // $bSave = '';
                    // $bTarik = 'disabled';
                    // $carabayar = 'disabled';
                    // $cekAll = 'disabled';
                    // $gudang = 'disabled';
                    // $idPbf = 'disabled';
                    // $item = 'disabled';
                    // $jenisharga = 'disabled';
                    // $katalog = 'disabled';
                    // $keterangan = 'disabled';
                    // $mataAnggaran = 'disabled';
                    // $noDoc = 'disabled';
                    // $noFaktur = 'disabled';
                    // $noSuratJalan = 'disabled';
                    // $ppn = 'disabled';
                    // $sumberDana = 'disabled';
                    // $tglDoc = 'disabled';
                },
            },
            onInit() {
                this.loadProfile("edit");
            },
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $viewLainnyaWidgetId ?>");
                widget.show();
                widget.loadData({kode: kodeFld.value, revisiKe: 0}, true);
            },
            resetBtnId: false,
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
            setelahPpnStc.innerHTML = currency(setelahPpn);
            pembulatanStc.innerHTML = currency(pembulatan);
            nilaiAkhirStc.innerHTML = currency(nilaiAkhir);
        }

        const noFakturWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noFakturFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == kodeFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoFakturUrl ?>",
            term: "value",
            additionalData: {field: "no_faktur"}
        });

        const noSuratJalanWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noSuratJalanFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == kodeFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoSuratJalanUrl ?>",
            term: "value",
            additionalData: {field: "no_suratjalan"}
        });

        const tanggalPenerimaanWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalPenerimaanFld"),
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
            errorRules: [{required: true}],
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddOthers.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddOthers.PemasokFields} item */
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

        idJenisAnggaranFld.addEventListener("change", () => {
            if (confirm(str._<?= $h('Mengubah "Mata Anggaran" akan menghapus semua item. Apakah Anda yakin?') ?>)) {
                itemWgt.reset();
                hitungTotal();
                const idJenisAnggaran = idJenisAnggaranFld.value;
                idGudangPenyimpananFld.value = (idJenisAnggaran == "6") ? "60" : "59";
                idJenisAnggaranSebelumnya = idJenisAnggaran;

            } else {
                idJenisAnggaranFld.value = idJenisAnggaranSebelumnya;
            }
        });

        idGudangPenyimpananFld.addEventListener("change", () => {
            if (confirm(str._<?= $h('Mengubah "Gudang Penyimpanan" akan menghapus semua item. Apakah Anda yakin?') ?>)) {
                itemWgt.reset();
                hitungTotal();
                const idGudangPenyimpanan = idGudangPenyimpananFld.value;
                idJenisAnggaranFld.value = (idGudangPenyimpanan == "60") ? "6" : "8";
                idGudangPenyimpananSebelumnya = idGudangPenyimpanan;

            } else {
                idGudangPenyimpananFld.value = idGudangPenyimpananSebelumnya;
            }
        });

        ppnFld.addEventListener("change", hitungTotal);

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Penerimaan.AddOthers.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefPoFld.value = data.kodeRefPo ?? "";
                fields.kodeRefRoFld.value = data.kodeRefRo ?? "";
                fields.kodeRefPlFld.value = data.kodeRefPl ?? "";
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogWgt.value = data.idKatalog ?? "";
                fields.idPabrikWgt.value = data.idPabrik ?? "";
                fields.kemasanFld.value = data.kemasan ?? "";
                fields.jumlahItemBonusFld.value = data.jumlahItemBonus ?? "";
                fields.jumlahBeliFld.value = data.jumlahBeli ?? "";
                fields.jumlahBonusFld.value = data.jumlahBonus ?? "";
                fields.noUrutFld.value = data.noUrut ?? "";
                fields.idKemasanDepoFld.value = data.idKemasanDepo ?? "";
                fields.namaSediaanStc.innerHTML = data.namaSediaan ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.isiKemasanWgt.value = data.isiKemasan ?? "";
                fields.noUrutStc.innerHTML = data.noUrut ?? "";
                fields.noBatchWgt.value = data.noBatch ?? "";
                fields.tanggalKadaluarsaWgt.value = data.tanggalKadaluarsa ?? "";
                fields.jumlahKemasanWgt.value = data.jumlahKemasan ?? "";
                fields.jumlahItemStc.innerHTML = data.jumlahItem ?? "";
                fields.hargaKemasanWgt.value = data.hargaKemasan ?? "";
                fields.hargaItemStc.innerHTML = data.hargaItem ?? "";
                fields.diskonItemWgt.value = data.diskonItem ?? "";
                fields.hargaTotalStc.innerHTML = data.hargaTotal ?? "";
                fields.diskonHargaStc.innerHTML = data.diskonHarga ?? "";
                fields.hargaAkhirStc.innerHTML = data.hargaAkhir ?? "";
                fields.stokBtn.value = data.idKatalog ?? "";
            },
            addRow(trElm) {
                const idKatalogWgt = new spl.InputWidget({
                    element: trElm.querySelector(".idKatalogFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ]
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

                const noBatchWgt = new spl.InputWidget({
                    element: trElm.querySelector(".noBatchFld"),
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
                    ]
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

                const tanggalKadaluarsaWgt = new spl.DateTimeWidget({
                    element: trElm.querySelector(".tanggalKadaluarsaFld"),
                    // TODO: js: uncategorized: add "already expired", and "less than 2 years" rules
                    errorRules: [{required: true}],
                    ...tlm.dateWidgetSetting
                });

                trElm.fields = {
                    idKatalogWgt,
                    idPabrikWgt,
                    jumlahKemasanWgt,
                    hargaKemasanWgt,
                    isiKemasanWgt,
                    noBatchWgt,
                    diskonItemWgt,
                    tanggalKadaluarsaWgt,
                    kodeRefPoFld: trElm.querySelector(".kodeRefPoFld"),
                    kodeRefRoFld: trElm.querySelector(".kodeRefRoFld"),
                    kodeRefPlFld: trElm.querySelector(".kodeRefPlFld"),
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    jumlahItemBonusFld: trElm.querySelector(".jumlahItemBonusFld"),
                    jumlahBeliFld: trElm.querySelector(".jumlahBeliFld"),
                    jumlahBonusFld: trElm.querySelector(".jumlahBonusFld"),
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    noUrutStc: trElm.querySelector(".noUrutStc"),
                    jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                    hargaItemStc: trElm.querySelector(".hargaItemStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                    hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                    stokBtn: trElm.querySelector(".stokBtn"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.idKatalogWgt.destroy();
                fields.idPabrikWgt.destroy();
                fields.jumlahKemasanWgt.destroy();
                fields.hargaKemasanWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.noBatchWgt.destroy();
                fields.diskonItemWgt.destroy();
                fields.tanggalKadaluarsaWgt.destroy();
            },
            profile: {
                edit(data) {
                    // TODO: js: uncategorized: finish this
                }
            },
            onInit() {
                this.loadProfile("edit");
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const stokBtn = event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            $.post({
                url: "<?= $cekStokUrl ?>",
                data: {idKatalog: stokBtn.value},
                success(data) {
                    const namaKatalog = closest(stokBtn, "tr").fields.namaSediaanStc.innerHTML;
                    const total = data.reduce((acc, curr) => acc + curr.jumlahStokAdm, 0);
                    headerElm.innerHTML = str._<?= $h("Nama Barang: {{NAMA}}") ?>.replace("{{NAMA}}", namaKatalog);
                    footerElm.innerHTML = str._<?= $h("Total: {{TOTAL}}") ?>.replace("{{TOTAL}}", total);
                    stokWgt.load(data);
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "change", (event) => {
            const idKemasanFld = event.target;
            if (!idKemasanFld.matches(".idKemasanFld")) return;

            const trElm = closest(idKemasanFld, "tr");
            const fields = trElm.fields;
            const kemasanOpt = idKemasanFld.selectedOptions[0];
            const isiKemasan = sysNum(kemasanOpt.dataset.is);
            const hargaKemasan = sysNum(kemasanOpt.dataset.hk);

            fields.kemasanFld.value = kemasanOpt.innerHTML;
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

        /** @see {his.FatmaPharmacy.views.Penerimaan.AddOthers.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: itemWgt.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["idKatalog", "namaSediaan"],
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddOthers.KatalogFields} item */
            optionRenderer(item) {
                const {isiKemasan: isi, satuan, idKemasan, idKemasanDepo, satuanJual} = item;
                const kemasan = ((isi != 1 || idKemasan != idKemasanDepo) ? satuanJual + " " + preferInt(isi) : "") + " " + satuan;

                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.idKatalog}</b></div>
                        <div class="col-xs-5"><b>${item.namaSediaan}</b></div>
                        <div class="col-xs-3">${item.namaPabrik}</div>
                        <div class="col-xs-2">${kemasan}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddOthers.KatalogFields} item */
            itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.namaSediaan})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                const idJenis = idJenisAnggaranFld.selectedOptions[0].getAttribute("id_jenis");
                $.post({
                    url: "<?= $subjenisAcplUrl ?>",
                    data: {query: typed, idJenis},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.Penerimaan.AddOthers.KatalogFields} */
                const obj = this.options[value];
                let {isiKemasan, satuan, satuanJual, idKemasanDepo, idKemasan, hargaKemasan} = obj;
                const {namaSediaan, namaPabrik, jumlahItem, diskonItem, idPabrik, idKatalog} = obj;

                let found = 0;
                divElm.querySelectorAll(".idKatalogFld").forEach(/** @type {HTMLInputElement} */item => {
                    if (item.value == idKatalog) found++;
                });

                if (found) {
                    this.blur();
                    this.clear();
                    return alert(str._<?= $h("Katalog sudah ditambahkan. Silahkan pilih yang lain.") ?>);
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

                const trAddElm = divElm.querySelector(".tr-add");
                const trStr = drawTr("tbody", {
                    class: ".tr-data",
                    id: idKatalog,
                    td_1: {
                        hidden_1: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                        hidden_2: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                        hidden_3: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                        hidden_4: {class: ".kemasanFld", name: "kemasan[]", value: isiKemasan},
                        hidden_5: {class: ".noUrutFld", name: "noUrut[]", value: 1},
                        hidden_6: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                        staticText: {class: ".no", text: 1},
                    },
                    td_2: {
                        button: {class: ".stokBtn", text: str._<?= $h("Stok") ?>},
                        staticText: {class: ".namaSediaanStc", text: namaSediaan}
                    },
                    td_3: {class: ".namaPabrikStc", text: namaPabrik},
                    td_4: {
                        select: {class: ".idKemasanFld", name: "idKemasan[]", ...options}
                    },
                    td_5: {
                        input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: 1, readonly: true}
                    },
                    td_6: {
                        class: ".DIFF-WITH-SPLBULKINPUT",
                        button: {class: ".btn-addbch", text: "+"},
                        staticText: {class: ".no_batch  .noUrutStc", par: idKatalog, "data-no_urut": 1, text: 1}
                    },
                    td_7: {
                        input: {class: ".noBatchFld", name: "noBatch[]", id_katalog: idKatalog}
                    },
                    td_8: {
                        input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
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

        // JUNK -----------------------------------------------------

        function sortNumber() {
            divElm.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
        }

        divElm.querySelector(".btn-addbch").addEventListener("click", (event) => {
            const trElm = closest(event.target, "tr");
            const fields = trElm.fields;
            const idKatalog = trElm.id;
            const noUrut = divElm.querySelectorAll(`.noUrutStc[par="${idKatalog}"]`).length + 1;
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const hargaItem = sysNum(fields.hargaItemStc.innerHTML);
            const diskonItem = sysNum(fields.diskonItemWgt.value);
            const jumlahKemasanPar = sysNum(fields.jumlahKemasanWgt.value);
            let jumlahKemasan = 0;
            let jumlahItem = 0;
            const isTabungGasMedis = tabungGasYes.checked;

            if (isTabungGasMedis && jumlahKemasanPar > 1) {
                jumlahKemasan = 1;
                jumlahItem = jumlahKemasan * isiKemasan;
            } else if (isTabungGasMedis && jumlahKemasanPar <= 1) {
                return;
            }

            const jumlahItemPar = (jumlahKemasanPar - 1) * isiKemasan;

            const lastTrElm = closest(divElm.querySelector(`.noUrutStc[par="${idKatalog}"]:last-child`), "tr");
            const trStr = drawTr("tbody", {
                class: ".tr-data",
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
                    class: ".DIFF-WITH-SPLBULKINPUT",
                    button: {class: ".btn-delbch", text: "-"},
                    staticText: {class: ".noUrutStc", par: idKatalog, "data-no_urut": 1, text: noUrut}
                },
                td_4: {
                    input: {class: ".noBatchFld", name: "noBatch[]", id_katalog: idKatalog}
                },
                td_5: {
                    input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                },
                td_6: {
                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: preferInt(jumlahItem)}
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
            lastTrElm.nextElementSibling.fields.noBatchWgt.dispatchEvent(new Event("focus"));
            hitungSubTotal(/** @type {HTMLTableRowElement} */ lastTrElm.nextElementSibling);

            if (isTabungGasMedis && jumlahKemasanPar > 0) {
                fields.jumlahItemStc.innerHTML = preferInt(jumlahItemPar);
            }
        });

        divElm.querySelector(".btn-delbch").addEventListener("click", (event) => {
            const trElm = closest(event.target, "tr");

            trElm.remove();
            let z = 1;
            divElm.querySelectorAll(`.noUrutStc[par="${trElm.id}"]`).forEach(/** @type {HTMLDivElement} */ item => {
                closest(item, "tr").fields.noUrutFld.value = z;
                item.dataset.no_urut = z;
                item.innerHTML = z;
                z++;
            });
            hitungTotal();
        });

        divElm.querySelector(".btn-delete").addEventListener("click", () => {
            const ckOneList = divElm.querySelectorAll(".ck-one:checked");
            if (!ckOneList.length || !confirm(str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>)) return;

            ckOneList.forEach(item => closest(item, "tr").remove());
            sortNumber();
            hitungTotal();
        });

        tarikBtn.addEventListener("click", () => {
            // TODO: html: missing element: truely missing element "#kode_reffpl"
            const kodeRefPl = divElm.querySelector("#kode_reffpl").value;

            if (!kodeRefPl) {
                alert(str._<?= $h('Anda harus memilih "Ref. SP/SPK/Kontrak"!') ?>);
                return;
            }

            // TODO: js: missing function: selectItemSPK()
            selectItemSPK(kodeRefPl);
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
        this._widgets.push(tambahPenerimaanLainnyaWgt, noSuratJalanWgt, tanggalPenerimaanWgt);
        this._widgets.push(noFakturWgt, noDokumenWgt, idPemasokWgt, itemWgt, stokWgt, idKatalogWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tambahPenerimaanLainnyaWgt);
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
