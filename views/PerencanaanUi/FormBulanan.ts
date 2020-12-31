<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PerencanaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/addmonthly.php the original file
 */
final class FormBulanan
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
        string $pembelianAcplUrl,
        string $pemasokAcplUrl,
        string $cekUnikNoDokumenUrl,
        string $cekStokUrl,
        string $detailSpbUrl,
        string $viewWidgetId,
        string $printWidgetId,
        string $tableWidgetId,
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
namespace his.FatmaPharmacy.views.Perencanaan.FormBulanan {
    export interface FormFields {
        kode:               "kode",
        idJenisAnggaran:    "id_jenisanggaran",
        noDokumen:          "no_doc",
        bulanAwalAnggaran:  "blnawal_anggaran",
        bulanAkhirAnggaran: "blnakhir_anggaran",
        tahunAnggaran:      "thn_anggaran",
        tanggalDokumen:     "tgl_doc",
        idSumberDana:       "id_sumberdana",
        kodeRefPl:          "___", // exist but missing
        idJenisHarga:       "id_jenisharga",
        idCaraBayar:        "id_carabayar",
        idPemasok:          "id_pbf",
        tanggalMulai:       "___", // exist but missing
        tanggalJatuhTempo:  "___", // exist but missing
        sebelumDiskon:      "___", // exist but missing
        diskon:             "___", // exist but missing
        setelahDiskon:      "___", // exist but missing
        ppn:                "ppn",
        setelahPpn:         "___", // exist but missing
        pembulatan:         "___", // exist but missing
        nilaiAkhir:         "___", // exist but missing
        verRevisi:          "ver_revisi",
        verUserRevisi:      "ver_usrrevisi",
        verTanggalRevisi:   "ver_tglrevisi",

        objectSpk:          RefPlFields;
    }

    export interface TableFields {
        kodeRefRencana: "kode_reffrenc[]",
        kodeRefPl:      "kode_reffpl[]",
        idRefKatalog:   "id_reffkatalog[]",
        idKatalog:      "id_katalog[]",
        idPabrik:       "id_pabrik[]",
        kemasan:        "kemasan[]",
        idKemasanDepo:  "id_kemasandepo[]",
        namaSediaan:    "___", // exist but missing
        namaPabrik:     "___", // exist but missing
        idKemasan:      "id_kemasan[]",
        isiKemasan:     "isi_kemasan[]",
        jumlahKemasan:  "jumlah_kemasan[]",
        jumlahItem:     "___", // exist but missing
        hargaKemasan:   "harga_kemasan[]",
        hargaItem:      "___", // exist but missing
        diskonItem:     "diskon_item[]",
        hargaTotal:     "___", // exist but missing
        diskonHarga:    "___", // exist but missing
        hargaAkhir:     "___", // exist but missing
        jumlahRencana:  "___", // exist but missing
        jumlahHps:      "___", // exist but missing
        jumlahPl:       "___", // exist but missing
        jumlahDo:       "___", // exist but missing
        jumlahBonus:    "___", // exist but missing
        jumlahTerima:   "___", // exist but missing
        jumlahRetur:    "___", // exist but missing
    }

    export interface Data1Fields {
        kodeRefPl:            "kode_reffpl",
        noSpk:                "no_spk",
        tanggalMulai:         "tgl_mulai",
        tanggalJatuhTempo:    "tgl_jatuhtempo",
        idJenisAnggaran:      "id_jenisanggaran",
        idJenisHarga:         "id_jenisharga",
        idCaraBayar:          "id_carabayar",
        idPemasok:            "id_pbf",
        kodePemasok:          "kode_pbf",
        namaPemasok:          "nama_pbf",
        ppn:                  "ppn",
        subjenisAnggaran:     "subjenis_anggaran",
        jenisHarga:           "jenis_harga",
        bulanAwalAnggaranPl:  "blnawal_anggaran_pl",
        bulanAkhirAnggaranPl: "blnakhir_anggaran_pl",
        tahunAnggaranPl:      "thn_anggaran_pl",
        nilaiAkhirPl:         "nilai_akhir_pl",
    }

    export interface RefPlFields {
        subjenisAnggaran:   string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        noDokumen:          string;
        idJenisHarga:       string;
        idPemasok:          string;
        namaPemasok:        string;
        kode:               string;
        tipeDokumen:        string; // not used, but exist in controller
        id:                 "id",
        jenisHarga:         "jenis_harga",
        nilaiAkhir:         "nilai_akhir",
        tanggalDokumen:     "tgl_doc",
        tanggalJatuhTempo:  "tgl_jatuhtempo",
        idJenisAnggaran:    "id_jenisanggaran",
        idSumberDana:       "id_sumberdana",
        idCaraBayar:        "id_carabayar",
        ppn:                "ppn",
        kodePemasok:        "kode_pbf",
        action:             "act",

        objectPemasok:      PemasokFields,
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
        row_3: {
            widthColumn: {
                button_1: {class: ".analisisBtn", text: tlm.stringRegistry._<?= $h("Analisis") ?>},
                button_2: {class: ".printBtn",   text: tlm.stringRegistry._<?= $h("Print") ?>},
                button_3: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".perencanaanBulananFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class:".actionFld", name: "action"},
                    hidden_2: {name: "submit" value: "save"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Perencanaan") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Perencanaan") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>,
                        lButton: {class: ".tarikBtn", icon: "list", title: tlm.stringRegistry._<?= $h("Tarik Item Pembelian") ?>},
                        input: {class: ".kodeRefPlFld"},
                        rButton: {class: ".clearReferensiSpkBtn", icon: "eraser"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Kontrak") ?>,
                        staticText: {class: ".tanggalMulaiFld"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        staticText: {class: ".tanggalJatuhTempoFld"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verRevisiFld" name="verRevisi" value="1"/>
                            <input class="userRevisiFld" name="verUserRevisi" value="------"/>
                            <input class="tanggalRevisiFld" name="verTanggalRevisi" value="00-00-00 00:00:00"/>
                        `}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        staticText: {class: ".setelahPpnStc"}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Nilai Akhir") ?>,
                        staticText: {class: ".nilaiAkhirStc"}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr_1: {
                            td_1:  /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3:  /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4:  /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  /*  5    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6:  /*  6-7  */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                            td_7:  /*  8-9  */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8:  /* 10    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9:  /* 11-13 */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_10: /* 14-20 */ {colspan: 7, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
                            td_11: /* 21    */ {rowspan: 2, text: ""},
                        },
                        tr_2: {
                            td_1:  /*  6 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_2:  /*  7 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_3:  /*  8 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_4:  /*  9 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_5:  /* 11 */ {text: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>},
                            td_6:  /* 12 */ {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_7:  /* 13 */ {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                            td_8:  /* 14 */ {text: tlm.stringRegistry._<?= $h("Rencana") ?>},
                            td_9:  /* 15 */ {text: tlm.stringRegistry._<?= $h("HPS") ?>},
                            td_10: /* 16 */ {text: tlm.stringRegistry._<?= $h("SPK") ?>},
                            td_11: /* 17 */ {text: tlm.stringRegistry._<?= $h("DO") ?>},
                            td_12: /* 18 */ {text: tlm.stringRegistry._<?= $h("Bonus") ?>},
                            td_13: /* 19 */ {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                            td_14: /* 20 */ {text: tlm.stringRegistry._<?= $h("Retur") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]"},
                                hidden_2: {class: ".kodeRefPlFld", name: "kodeRefPl[]"},
                                hidden_3: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_4: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_5: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_6: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_7: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
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
                            td_14: {class: ".jumlahRencanaStc"},
                            td_15: {class: ".jumlahHpsStc"},
                            td_16: {class: ".jumlahPlStc"},
                            td_17: {class: ".jumlahDoStc"},
                            td_18: {class: ".jumlahBonusStc"},
                            td_19: {class: ".jumlahTerimaStc"},
                            td_20: {class: ".jumlahReturStc"},
                            td_21: {
                                button: {class: ".deleteBtn"}
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
        const {numToShortMonthName: nToS, toSystemNumber: sysNum, toCurrency: currency, nowVal} = tlm;
        const {toUserInt: userInt, toUserFloat: userFloat, stringRegistry: str, preferInt} = tlm;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLButtonElement} */ const analisisBtn = divElm.querySelector(".analisisBtn");
        /** @type {HTMLButtonElement} */ const printBtn = divElm.querySelector(".printBtn");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLButtonElement} */ const clearReferensiSpkBtn = divElm.querySelector(".clearReferensiSpkBtn");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLDivElement} */    const tanggalMulaiFld = divElm.querySelector(".tanggalMulaiFld");
        /** @type {HTMLDivElement} */    const tanggalJatuhTempoFld = divElm.querySelector(".tanggalJatuhTempoFld");
        /** @type {HTMLDivElement} */    const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */    const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */    const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLInputElement} */  const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */    const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLDivElement} */    const setelahPpnStc = divElm.querySelector(".setelahPpnStc");
        /** @type {HTMLDivElement} */    const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */    const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");
        /** @type {HTMLInputElement} */  const verRevisiFld = divElm.querySelector(".verRevisiFld");
        /** @type {HTMLInputElement} */  const userRevisiFld = divElm.querySelector(".userRevisiFld");
        /** @type {HTMLInputElement} */  const tanggalRevisiFld = divElm.querySelector(".tanggalRevisiFld");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const perencanaanBulananWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".perencanaanBulananFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Perencanaan.FormBulanan.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                kodeRefPlWgt.value = data.kodeRefPl ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
                tanggalMulaiFld.innerHTML = data.tanggalMulai ?? "";
                tanggalJatuhTempoFld.innerHTML = data.tanggalJatuhTempo ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";

                if (data.objectSpk) {
                    kodeRefPlWgt.addOption(data.objectSpk);
                    kodeRefPlWgt.value = data.kodeRefPl;
                }
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Perencanaan Repeat Order") ?>;
                    this.loadData({});
                    actionFld.value = "add";

                    analisisBtn.disabled = true;
                    printBtn.disabled = true;
                    clearReferensiSpkBtn.disabled = false;
                    kodeRefPlWgt.enable();
                },
                edit(data, fromServer) {
                    formTitleTxt.innerHTML = str._<?= $h("Ubah Perencanaan Repeat Order") ?>;
                    this.loadData(data, fromServer);
                    actionFld.value = "edit";

                    analisisBtn.disabled = false;
                    printBtn.disabled = true;
                    clearReferensiSpkBtn.disabled = true;
                    kodeRefPlWgt.disable();
                }
            },
            onInit() {
                this.loadProfile("edit");
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
            const jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const hargaKemasan = sysNum(fields.hargaKemasanWgt.value);
            const diskonItem = sysNum(fields.diskonItemWgt.value);
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

        // NOTE: fill this var with incoming data
        let idJenisAnggaranSebelumnya;
        idJenisAnggaranFld.addEventListener("change", () => {
            if (confirm(str._<?= $h('Mengubah "Mata Anggaran" akan menghapus semua daftar item. Apakah Anda yakin ingin mengubah?') ?>)) {
                itemWgt.reset();
                hitungTotal();
                idJenisAnggaranSebelumnya = idJenisAnggaranFld.value;
            } else {
                idJenisAnggaranFld.value = idJenisAnggaranSebelumnya;
            }
        });

        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPlFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.Perencanaan.FormBulanan.RefPlFields} data
             */
            assignPairs(formElm, data) {
                tanggalMulaiFld.innerHTML = data.tanggalDokumen ?? "";
                tanggalJatuhTempoFld.innerHTML = data.tanggalJatuhTempo ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                ppnFld.value = data.ppn ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
            },
            /** @param {his.FatmaPharmacy.views.Perencanaan.FormBulanan.RefPlFields} item */
            optionRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                const awal = item.id ? item.bulanAwalAnggaran : bulanAwalAnggaranFld.value;
                const akhir = item.id ? item.bulanAkhirAnggaran : bulanAkhirAnggaranFld.value;
                const tahun = item.id ? item.tahunAnggaran : tahunAnggaranFld.value;
                const nilaiAkhir = item.id ? item.nilaiAkhir : 0;

                const anggaran2 = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;

                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-3"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-3"><b>${anggaran1}</b></div>
                        <div class="col-xs-3">${anggaran2}</div>
                        <div class="col-xs-3">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Perencanaan.FormBulanan.RefPlFields} item */
            itemRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                return `<div class="item">${item.noDokumen} (${anggaran1})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pembelianAcplUrl ?>",
                    data: {noDokumen: typed, statusRevisi: 0},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.Perencanaan.FormBulanan.RefPlFields} */
                const obj = this.options[value];

                divElm.querySelector("#ppn").checked = (obj.ppn == 10);

                if (obj.idPemasok != "0" && obj.objectPemasok) {
                    idPemasokWgt.addOption(obj.objectPemasok);
                    idPemasokWgt.value = obj.objectPemasok.id;
                }

                // select item spk
                if (!obj.action) {
                    selectItemSPK(obj.kode);
                }
            },
            onItemRemove() {
                const trElm = itemWgt.querySelector("tbody tr");
                if (!trElm.length || !confirm(str._<?= $h('Menghapus "Ref. SPK" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

                trElm.remove();
                sortNumber();
                hitungTotal();
            }
        });

        clearReferensiSpkBtn.addEventListener("click", () => {
            if (!kodeRefPlWgt.value) return;
            if (!confirm(str._<?= $h('Menghapus "No. SP/SPK/Kontrak" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

            itemWgt.reset();
            hitungTotal();

            kodeRefPlWgt.clearOptions();
            kodeRefPlWgt.clearCache();

            idPemasokWgt.clearOptions();
            idPemasokWgt.clearCache();
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Perencanaan.FormBulanan.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Perencanaan.FormBulanan.PemasokFields} item */
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

        idJenisHargaFld.addEventListener("change", () => {
            (idJenisHargaFld.value == "2") ? ppnFld.checked = false : ppnFld.checked = true;
            hitungTotal();
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Perencanaan.FormBulanan.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.kodeRefPlFld.value = data.kodeRefPl ?? "";
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogWgt.value = data.idKatalog ?? "";
                fields.idPabrikWgt.value = data.idPabrik ?? "";
                fields.kemasanFld.value = data.kemasan ?? "";
                fields.idKemasanDepoFld.value = data.idKemasanDepo ?? "";
                fields.namaSediaanStc.innerHTML = data.namaSediaan ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
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
                fields.jumlahRencanaStc.innerHTML = data.jumlahRencana ?? "";
                fields.jumlahHpsStc.innerHTML = data.jumlahHps ?? "";
                fields.jumlahPlStc.innerHTML = data.jumlahPl ?? "";
                fields.jumlahDoStc.innerHTML = data.jumlahDo ?? "";
                fields.jumlahBonusStc.innerHTML = data.jumlahBonus ?? "";
                fields.jumlahTerimaStc.innerHTML = data.jumlahTerima ?? "";
                fields.jumlahReturStc.innerHTML = data.jumlahRetur ?? "";
                fields.stokBtn.value = data.idKatalog ?? "";
            },
            addRow(trElm) {
                const jumlahItemStc = trElm.querySelector(".jumlahItemStc");

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
                        {greaterThan: 0},
                        {
                            callback() {
                                const jumlahKemasan = sysNum(this._element.value);
                                const maksimumJumlahItem = sysNum(jumlahItemStc.dataset.jMax);
                                const isiKemasan = sysNum(isiKemasanWgt.value);
                                return jumlahKemasan <= maksimumJumlahItem / isiKemasan;
                            },
                            message: str._<?= $h("melebihi MaksimumJumlahItem ÷ IsiKemasan") ?>
                        }
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
                    jumlahItemStc,
                    idKatalogWgt,
                    idPabrikWgt,
                    jumlahKemasanWgt,
                    hargaKemasanWgt,
                    isiKemasanWgt,
                    diskonItemWgt,
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    kodeRefPlFld: trElm.querySelector(".kodeRefPlFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    hargaItemStc: trElm.querySelector(".hargaItemStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                    hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                    jumlahRencanaStc: trElm.querySelector(".jumlahRencanaStc"),
                    jumlahHpsStc: trElm.querySelector(".jumlahHpsStc"),
                    jumlahPlStc: trElm.querySelector(".jumlahPlStc"),
                    jumlahDoStc: trElm.querySelector(".jumlahDoStc"),
                    jumlahBonusStc: trElm.querySelector(".jumlahBonusStc"),
                    jumlahTerimaStc: trElm.querySelector(".jumlahTerimaStc"),
                    jumlahReturStc: trElm.querySelector(".jumlahReturStc"),
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
                fields.diskonItemWgt.destroy();

                sortNumber();
                hitungTotal();
            },
            profile: {
                add(trElm) {
                    trElm.fields.jumlahKemasanWgt.disabled = true;
                    this.loadData({});
                },
                edit(trElm, data) {
                    trElm.fields.jumlahKemasanWgt.disabled = false;
                    this.loadData(data);
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

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            hitungSubTotal(closest(jumlahKemasanFld, "tr"));
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.Perencanaan.FormBulanan.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        function selectItemSPK(kodeRef) {
            $.post({
                url: "<?= $detailSpbUrl ?>",
                data: {kodeRef},
                /** @param {*[]} data */
                success(data) {
                    data.forEach(obj => {
                        let {id_katalog: idKatalog, jumlah_item: jumlahItem, jumlah_trm: jumlahTerima, jumlah_ret: jumlahRetur} = obj;
                        const {isi_kemasan: isiKemasan, satuan, satuanjual: satuanJual, id_kemasan: idKemasan, id_kemasandepo: idKemasanDepo} = obj;
                        const {harga_kemasan: hargaKemasan, kode_reffrenc: kodeRefRencana, kode_reffhps: kodeRefHps, kode_reff: kodeRefPl} = obj;
                        const {id_pabrik: idPabrik, nama_sediaan: namaSediaan, nama_pabrik: namaPabrik, diskon_item: diskonItem} = obj;
                        const {jumlah_renc: jumlahRencana, jumlah_hps: jumlahHps, jumlah_pl: jumlahPl, jumlah_do: jumlahDo, jumlah_bns: jumlahBonus} = obj;

                        if (itemWgt.querySelector("tr#" + idKatalog)) return;

                        jumlahItem = jumlahItem - jumlahTerima + jumlahRetur;
                        if (jumlahItem <= 0) return;

                        const kemasan = (isiKemasan == 0) ? satuan : `${satuanJual} ${preferInt(isiKemasan)} ${satuan}`;
                        const jumlahKemasan = jumlahItem / isiKemasan;

                        const trAddElm = divElm.querySelector(".tr-add");
                        const trStr = drawTr("tbody", {
                            class: "tr-data",
                            id: idKatalog,
                            td_1: {
                                hidden_1: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]", value: kodeRefRencana},
                                hidden_2: {class: "DIFF-WITH-SPLBULKINPUT", name: "kode_reffhps[]", value: kodeRefHps},
                                hidden_3: {class: ".kodeRefPlFld", name: "kodeRefPl[]", value: kodeRefPl},
                                hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                                hidden_5: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                                hidden_6: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                                hidden_7: {class: ".kemasanFld", name: "kemasan[]", value: isiKemasan},
                                hidden_8: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                                staticText: {class: ".no", text: 1}
                            },
                            td_2: {
                                button: {class: ".stokBtn", text: str._<?= $h("Stok") ?>},
                                staticText: {class: ".namaSediaanStc", text: namaSediaan}
                            },
                            td_3: {class: ".namaPabrikStc", text: namaPabrik},
                            td_4: {
                                select: {
                                    class: ".idKemasanFld",
                                    name: "idKemasan[]",
                                    option: {value: idKemasan, is: isiKemasan, ids: idKemasanDepo, sat: satuan, satj: satuanJual, hk: hargaKemasan, selected: true, label: kemasan},
                                }
                            },
                            td_5: {
                                input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: preferInt(isiKemasan), readonly: true}
                            },
                            td_6: {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: preferInt(jumlahKemasan)}
                            },
                            td_7: {class: ".jumlahItemStc"},
                            td_8: {
                                input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: currency(hargaKemasan), readonly: true}
                            },
                            td_9: {class: ".hargaItemStc"},
                            td_10: {
                                input: {class: ".diskonItemFld", name: "diskonItem[]", value: userInt(diskonItem), readonly: true}
                            },
                            td_11: {class: ".hargaTotalStc"},
                            td_12: {class: ".diskonHargaStc"},
                            td_13: {class: ".hargaAkhirStc"},
                            td_14: {class: ".jumlahRencanaStc", text: userFloat(jumlahRencana)},
                            td_15: {class: ".jumlahHpsStc", text: userFloat(jumlahHps)},
                            td_16: {class: ".jumlahPlStc", text: userFloat(jumlahPl)},
                            td_17: {class: ".jumlahDoStc", text: userFloat(jumlahDo)},
                            td_18: {class: ".jumlahBonusStc", text: userFloat(jumlahBonus)},
                            td_19: {class: ".jumlahTerimaStc", text: userFloat(jumlahTerima)},
                            td_20: {class: ".jumlahReturStc", text: userFloat(jumlahRetur)},
                        });
                        trAddElm.insertAdjacentHTML("beforebegin", trStr);
                        hitungSubTotal(/** @type {HTMLTableRowElement} */ trAddElm.previousElementSibling);
                    });

                    sortNumber();
                    hitungTotal();
                    itemWgt.querySelector("tbody tr:last-child").fields.jumlahKemasanWgt.dispatchEvent(new Event("focus"));
                }
            });
        }

        function sortNumber() {
            itemWgt.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
        }

        verRevisiFld.addEventListener("click", () => {
            const isChecked = verRevisiFld.checked;
            userRevisiFld.value = isChecked ? tlm.user.nama : "";
            tanggalRevisiFld.value = isChecked ? nowVal("user") : "";
        });

        divElm.querySelector(".analisisBtn").addEventListener("click", () => {
            alert(str._<?= $h("Analisis belum dapat dilakukan, masih dalam proses pengembangan.") ?>);
        });

        divElm.querySelector(".tarikBtn").addEventListener("click", () => {
            const kodeRefPl = kodeRefPlWgt.value;
            kodeRefPl ? selectItemSPK(kodeRefPl) : alert(str._<?= $h('Anda harus memilih "No. SP/SPK/Kontrak".') ?>);
        });

        printBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeFld.value, versi: "v-01"}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(perencanaanBulananWgt, kodeRefPlWgt, idPemasokWgt, noDokumenWgt, tanggalDokumenWgt, itemWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, perencanaanBulananWgt);
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
