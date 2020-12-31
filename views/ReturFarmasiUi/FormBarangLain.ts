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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/addothers.php the original file
 */
final class FormBarangLain
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        array  $verifikasiPenerimaanAccess,
        array  $verifikasiAkuntansiAccess,
        string $addActionUrl,
        string $editActionUrl,
        string $verifikasiPenerimaanActionUrl,
        string $verifikasiAkuntansiActionUrl,
        string $editDataUrl,
        string $verifikasiPenerimaanDataUrl,
        string $verifikasiAkuntansiDataUrl,
        string $pemasokAcplUrl,
        string $subjenisAcplUrl,
        string $cekUnikNoDokumenUrl,
        string $stockUrl,
        string $viewWidgetId,
        string $jenisAnggaranSelect,
        string $bulanSelect,
        string $tahunSelect,
        string $sumberDanaSelect,
        string $jenisHargaSelect,
        string $caraBayarSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ReturFarmasi.AddOthers {
    export interface FormFields {
        action:              string|"?" |"??" |"___"    |"____"     ; // action
        submit:              string|"?" |"??" |"___"    |"____"     ; // submit
        kode:                string|"?" |"??" |"VerAkun"|"VerTerima"; // kode
        tanggalDokumen:      string|"?" |"??" |"___"    |"____"     ; // tgl_doc
        idJenisAnggaran:     string|"?" |"??" |"___"    |"____"     ; // id_jenisanggaran
        noDokumen:           string|"?" |"??" |"___"    |"____"     ; // no_doc
        bulanAwalAnggaran:   string|"?" |"??" |"___"    |"____"     ; // blnawal_anggaran
        bulanAkhirAnggaran:  string|"?" |"??" |"___"    |"____"     ; // blnakhir_anggaran
        tahunAnggaran:       string|"?" |"??" |"___"    |"____"     ; // thn_anggaran
        idPemasok:           string|"?" |"??" |"___"    |"____"     ; // id_pbf
        kodePemasok:         string|"?" |"??" |"___"    |"____"     ; // kode_pbf
        namaPemasok:         string|"?" |"??" |"___"    |"____"     ; // nama_pbf
        idSumberDana:        string|"?" |"??" |"___"    |"____"     ; // id_sumberdana
        tipeDokumen:         string|"?" |"??" |"___"    |"____"     ; // tipe_doc
        idJenisHarga:        string|"?" |"??" |"___"    |"____"     ; // id_jenisharga
        idCaraBayar:         string|"?" |"??" |"___"    |"____"     ; // id_carabayar
        idGudangPenyimpanan: string|"?" |"??" |"___"    |"____"     ; // id_gudangpenyimpanan
        tabungGas:           string|"?" |"??" |"___"    |"____"     ; // sts_tabunggm
        keterangan:          string|"?" |"??" |"___"    |"____"     ; // keterangan
        sebelumDiskon:       string|"?" |"??" |"___"    |"____"     ; // // exist but missing
        diskon:              string|"?" |"??" |"___"    |"____"     ; // // exist but missing
        setelahDiskon:       string|"?" |"??" |"___"    |"____"     ; // // exist but missing
        ppn:                 string|"?" |"??" |"___"    |"____"     ; // ppn
        setelahPpn:          string|"?" |"??" |"___"    |"____"     ; // // exist but missing
        pembulatan:          string|"?" |"??" |"___"    |"____"     ; // // exist but missing
        nilaiAkhir:          string|"?" |"??" |"___"    |"____"     ; // // exist but missing

        verGudang:           string|"?" |"??" |"___"    |"____"     ; // ver_gudang
        verTerima:           string|"?" |"??" |"___"    |"VerTerima"; // ver_terima
        verAkuntansi:        string|"?" |"??" |"VerAkun"|"____"     ; // ver_akuntansi

        verTanggalGudang:    string|"?" |"??" |"___"    |"VerTerima"; // ver_tglgudang
        verTanggalTerima:    string|"?" |"??" |"___"    |"____"     ; // ver_tglterima
        verTanggalAkuntansi: string|"?" |"??" |"___"    |"____"     ; // ver_tglakuntansi

        verUserGudang:       string|"?" |"??" |"___"    |"____"     ; // user_gudang
        verUserTerima:       string|"?" |"??" |"___"    |"____"     ; // user_terima
        verUserAkuntansi:    string|"?" |"??" |"___"    |"____"     ; // user_akuntansi

        objPemasok:          PemasokFields;
    }

    export interface TableFields {
        kodeRefTerima:     "kode_refftrm[]",
        kodeRefPl:         "kode_reffpl[]",
        kodeRefPo:         "kode_reffpo[]",
        kodeRefRo:         "kode_reffro[]",
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
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".tambahLainnyaFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"}, // $action
                    hidden_2: {class: ".submitFld", name: "submit", value: "save"}, // FROM SUBMIT BUTTON  $bSave
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Retur") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Jenis Retur") ?>,
                        select: {
                            class: ".tipeDokumenFld",
                            name: "tipeDokumen",
                            option_1: {value: 1, label: tlm.stringRegistry._<?= $h("Retur CN Pemasok") ?>},
                            option_2: {value: 2, label: tlm.stringRegistry._<?= $h("Retur Barang Kadaluarsa") ?>},
                            option_3: {value: 3, label: tlm.stringRegistry._<?= $h("Retur Ganti Barang") ?>},
                            option_4: {value: 4, label: tlm.stringRegistry._<?= $h("Pemusnahan") ?>}
                        }
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                        select: {
                            class: ".idGudangPenyimpananFld",
                            name: "idGudangPenyimpanan",
                            option_1: {value: 59,  label: tlm.stringRegistry._<?= $h("Gudang Induk Farmasi") ?>},
                            option_2: {value: 60,  label: tlm.stringRegistry._<?= $h("Gudang Gas Medis") ?>},
                            option_3: {value: 69,  label: tlm.stringRegistry._<?= $h("Gudang Konsinyasi") ?>},
                            option_4: {value: 320, label: tlm.stringRegistry._<?= $h("Gudang Kadaluarsa") ?>},
                            option_5: {value: 321, label: tlm.stringRegistry._<?= $h("Gudang Barang Rusak") ?>}
                        }
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Tabung Gas") ?>,
                        radio_1: {class: ".tabungGasYes", name: "tabungGas", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                        radio_2: {class: ".tabungGasNo",  name: "tabungGas", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        input: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        staticText: {class: ".setelahPpnStc"}
                    },
                    formGroup_26: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_27: {
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
                            td_3:  {text: tlm.stringRegistry._<?= $h("Tanggal Kadaluarsa") ?>},
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
                                hidden_1: {class: ".kodeReffTerimaFld", name: "kodeRefTerima[]"},
                                hidden_2: {class: ".kodeReffPlFld", name: "kodeRefPl[]"},
                                hidden_3: {class: ".kodeReffPoFld", name: "kodeRefPo[]"},
                                hidden_4: {class: ".kodeReffRoFld", name: "kodeRefRo[]"},
                                hidden_5: {class: ".kodeReffRencanaFld", name: "kodeRefRencana[]"},
                                hidden_6: {class: ".idReffKatalogFld", name: "idRefKatalog[]"},
                                hidden_7: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_8: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_9: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_10: {class: ".jumlahItemBonusFld", name: "jumlahItemBonus[]"},
                                hidden_11: {class: ".jumlahBeliFld", name: "jumlahBeli[]"},
                                hidden_12: {class: ".jumlahBonusFld", name: "jumlahBonus[]"},
                                hidden_13: {class: ".noUrutFld", name: "noUrut[]"},
                                hidden_14: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
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
                            td_6: {class: ".noUrutStc"},
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
            row_4: {
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
                                checkbox: {class: ".verTerimaFLd", name: "verTerima", value: 1}
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
                        },
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
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {toUserInt: userInt, toCurrency: currency, toSystemNumber: sysNum, toUserDate: userDate, stringRegistry: str, nowVal, preferInt} = tlm;
        const userName = tlm.user.nama;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const submitFld = divElm.querySelector(".submitFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */ const tipeDokumenFld = divElm.querySelector(".tipeDokumenFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLSelectElement} */ const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLInputElement} */  const tabungGasYes = divElm.querySelector(".tabungGasYes");
        /** @type {HTMLInputElement} */  const tabungGasNo = divElm.querySelector(".tabungGasNo");
        /** @type {HTMLInputElement} */  const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLDivElement} */    const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */    const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */    const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLInputElement} */  const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */    const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLDivElement} */    const setelahPpnStc = divElm.querySelector(".setelahPpnStc");
        /** @type {HTMLDivElement} */    const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */    const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");
        /** @type {HTMLInputElement} */  const updateStokMarkerFld = divElm.querySelector(".updateStokMarkerFld");
        /** @type {HTMLInputElement} */  const verGudangFld = divElm.querySelector(".verGudangFld");
        /** @type {HTMLInputElement} */  const verTerimaFld = divElm.querySelector(".verTerimaFld");
        /** @type {HTMLInputElement} */  const verAkuntansiFld = divElm.querySelector(".verAkuntansiFld");
        /** @type {HTMLInputElement} */  const userGudangStc = divElm.querySelector(".userGudangStc");
        /** @type {HTMLInputElement} */  const userTerimaStc = divElm.querySelector(".userTerimaStc");
        /** @type {HTMLInputElement} */  const userAkuntansiStc = divElm.querySelector(".userAkuntansiStc");
        /** @type {HTMLInputElement} */  const tanggalGudangStc = divElm.querySelector(".tanggalGudangStc");
        /** @type {HTMLInputElement} */  const tanggalTerimaStc = divElm.querySelector(".tanggalTerimaStc");
        /** @type {HTMLInputElement} */  const tanggalAkuntansiStc = divElm.querySelector(".tanggalAkuntansiStc");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");
        /** @type {HTMLButtonElement} */ const printBtn = divElm.querySelector(".printBtn");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const tambahLainnyaWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".tambahLainnyaFrm"),
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.FormFields} data */
            loadData(data) {
                submitFld.value = data.submit ?? "";
                kodeFld.value = data.kode ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                idPemasokWgt.addOption(data.objPemasok);
                idPemasokWgt.value = data.idPemasok ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                tipeDokumenFld.value = data.tipeDokumen ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                data.tabungGas ? tabungGasYes.checked = true : tabungGasNo.checked = true;
                keteranganFld.value = data.keterangan ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";

                tanggalGudangStc.innerHTML = data.verTanggalGudang ? userDate(data.verTanggalGudang) : "-";
                tanggalTerimaStc.innerHTML = data.verTanggalTerima ? userDate(data.verTanggalTerima) : "-";
                tanggalAkuntansiStc.innerHTML = data.verTanggalAkuntansi ? userDate(data.verTanggalAkuntansi) : "-";

                userGudangStc.innerHTML = data.verUserGudang ?? "-";
                userTerimaStc.innerHTML = data.verUserTerima ?? "-";
                userAkuntansiStc.innerHTML = data.verUserAkuntansi ?? "-";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.load({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Retur Barang") ?>;

                    verGudangFld.disabled = false;

                    ppnFld.disabled = false;
                    printBtn.disabled = true;
                    actionFld.value = "add";
                },
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.FormFields} data */
                edit(data) {
                    this._dataUrl = "<?= $editDataUrl ?>";
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.load(data);

                    formTitleTxt.innerHTML = str._<?= $h("Ubah Retur Barang") ?>;

                    ppnFld.checked = data.ppn == "10";

                    verGudangFld.disabled = data.verGudang == "1";
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = data.verTerima != "1";
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = data.verTerima != "1";
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi != "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";

                    verGudangFld.disabled = false;
                    ppnFld.disabled = false;
                    printBtn.disabled = true;
                    actionFld.value = "edit";
                },
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.FormFields} data */
                verifikasiPenerimaan(data) {
                    this._dataUrl = "<?= $verifikasiPenerimaanDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiPenerimaanActionUrl ?>";
                    this.load(data);

                    formTitleTxt.innerHTML = str._<?= $h("Verifikasi Penerimaan Retur Barang") ?>;

                    verGudangFld.disabled = true;
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = false;
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = data.verTerima != "1";
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi != "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";

                    ppnFld.disabled = data.ppn != 0;
                    printBtn.disabled = false;
                    actionFld.value = "ver_terima";
                },
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.FormFields} data */
                verifikasiAkuntansi(data) {
                    this._dataUrl = "<?= $verifikasiAkuntansiDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiAkuntansiActionUrl ?>";
                    this.load(data);

                    formTitleTxt.innerHTML = str._<?= $h("Verifikasi Akuntansi Retur Barang") ?>;

                    verGudangFld.disabled = true;
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = true;
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = true;
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi == "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";

                    ppnFld.disabled = data.ppn != 0;
                    printBtn.disabled = false;
                    actionFld.value = "ver_akuntansi";
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

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        idJenisAnggaranFld.addEventListener("change", () => {
            if (idJenisAnggaranFld.value == "6") {
                idGudangPenyimpananFld.value = "60";
            }
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
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.PemasokFields} item */
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

        idGudangPenyimpananFld.addEventListener("change", () => {
            const dt = idGudangPenyimpananFld.value;

            if (dt == "320") {
                tipeDokumenFld.value = "2";
            } else if (dt == "321") {
                tipeDokumenFld.value = "4";
            } else if (dt == "60") {
                idJenisAnggaranFld.value = "6";
            }
        });

        tipeDokumenFld.addEventListener("change", () => {
            const dt = tipeDokumenFld.value;

            if (dt == "2") {
                idGudangPenyimpananFld.value = "320";
            } else if (dt == "4") {
                idGudangPenyimpananFld.value = "321";
            }
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeReffTerimaFld.value = data.kodeRefTerima ?? "";
                fields.kodeReffPlFld.value = data.kodeRefPl ?? "";
                fields.kodeReffPoFld.value = data.kodeRefPo ?? "";
                fields.kodeReffRoFld.value = data.kodeRefRo ?? "";
                fields.kodeReffRencanaFld.value = data.kodeRefRencana ?? "";
                fields.idReffKatalogFld.value = data.idRefKatalog ?? "";
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
                    kodeReffTerimaFld: trElm.querySelector(".kodeReffTerimaFld"),
                    kodeReffPlFld: trElm.querySelector(".kodeReffPlFld"),
                    kodeReffPoFld: trElm.querySelector(".kodeReffPoFld"),
                    kodeReffRoFld: trElm.querySelector(".kodeReffRoFld"),
                    kodeReffRencanaFld: trElm.querySelector(".kodeReffRencanaFld"),
                    idReffKatalogFld: trElm.querySelector(".idReffKatalogFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    jumlahItemBonusFld: trElm.querySelector(".jumlahItemBonusFld"),
                    jumlahBeliFld: trElm.querySelector(".jumlahBeliFld"),
                    jumlahBonusFld: trElm.querySelector(".jumlahBonusFld"),
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                    hargaItemStc: trElm.querySelector(".hargaItemStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                    hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                    stokBtn: trElm.querySelector(".stokBtn"),
                }
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
            addRowBtn: ".tambahLainnyaFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const stokBtn = event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            $.post({
                url: "<?= $stockUrl ?>",
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
            const kemasanOpt = fields.idKemasanFld.selectedOptions[0];
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

        /** @see {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.StokTableFields} */
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
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.KatalogFields} item */
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
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.KatalogFields} item */
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
                /** @type {his.FatmaPharmacy.views.ReturFarmasi.AddOthers.KatalogFields} */
                const obj = this.options[value];
                let {idKatalog, isiKemasan, satuanJual, satuan, idKemasanDepo, hargaKemasan} = obj;
                const {idPabrik, namaSediaan, namaPabrik, jumlahItem, diskonItem, idKemasan} = obj;

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

                const trAddFld = divElm.querySelector(".tr-add");
                const trStr = drawTr("tbody", {
                    class: "tr-data",
                    id: idKatalog,
                    td_1: {
                        hidden_1: {class: ".idReffKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                        hidden_2: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                        hidden_3: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                        hidden_4: {class: ".kemasanFld", name: "kemasan[]", value: isiKemasan},
                        hidden_5: {class: ".noUrutFld", name: "noUrut[]", value: 1},
                        hidden_6: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                        staticText: {class: "no", text: 1},
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
                        class: "DIFF-WITH-SPLBULKINPUT",
                        button: {class: ".btn-addbch", text: "+"},
                        staticText: {class: ".noUrutStc", par: idKatalog, no_urut: 1, text: 1}
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
                trAddFld.insertAdjacentHTML("beforebegin", trStr);
                hitungSubTotal(/** @type {HTMLTableRowElement} */ trAddFld.previousElementSibling);
                hitungTotal();
                itemWgt.querySelector("tbody tr:last-child").fields.idKemasanFld.dispatchEvent(new Event("focus"));
            }
        });

        // JUNK -----------------------------------------------------

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
                    staticText: {class: ".noUrutStc", par: idKatalog, no_urut: 1, text: noUrut}
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
                trElm.fields.jumlahItemStc.innerHTML = preferInt(jumlahItemPar);
            }
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
        this._widgets.push(tambahLainnyaWgt, tanggalDokumenWgt, noDokumenWgt, idPemasokWgt, itemWgt, stokWgt, idKatalogWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tambahLainnyaWgt);
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
