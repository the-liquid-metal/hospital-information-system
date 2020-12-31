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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/addrevisi.php the original file
 */
final class FormRevisi
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $revisiPlAccess,
        array  $revisiDokumenAccess,
        array  $revisiItemAccess,
        array  $verifikasiRevisiGudangAccess,
        string $addDataUrl,
        string $editDataUrl,
        string $revisiPlDataUrl,
        string $revisiDokumenDataUrl,
        string $revisiItemDataUrl,
        string $verifikasiGudangDataUrl,
        string $verifikasiRevisiGudangDataUrl,
        string $addActionUrl,
        string $editActionUrl,
        string $revisiPlActionUrl,
        string $revisiDokumenActionUrl,
        string $revisiItemActionUrl,
        string $verifikasiGudangActionUrl,
        string $verifikasiRevisiGudangActionUrl,
        string $cekUnikNoDokumenUrl,
        string $cekUnikNoFakturUrl,
        string $cekUnikNoSuratJalanUrl,
        string $cekStokUrl,
        string $printWidgetId,
        string $tableWidgetId,
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
namespace his.FatmaPharmacy.views.Penerimaan.AddRevisi {
    export interface FormFields {
        action:              "action",
        kode:                "kode",
        revisiKe:            "revisike",
        tanggalDokumen:      "tgl_doc",
        tanggalJatuhTempo:   "___", // exist but missing
        noDokumen:           "no_doc",
        idJenisAnggaran:     "id_jenisanggaran",
        noFaktur:            "no_faktur",
        noSuratJalan:        "no_suratjalan",
        bulanAwalAnggaran:   "blnawal_anggaran",
        bulanAkhirAnggaran:  "blnakhir_anggaran",
        tahunAnggaran:       "thn_anggaran",
        tarikDengan:         "tarik_by",
        kodeRefPl:           "kode_reffpl",
        idSumberDana:        "id_sumberdana",
        kodeRefDo:           "kode_reffdo",
        idJenisHarga:        "id_jenisharga",
        idCaraBayar:         "id_carabayar",
        idPemasok:           "id_pbf",
        idGudangPenyimpanan: "id_gudangpenyimpanan",
        tabungGas:           "sts_tabunggm",
        keterangan:          "keterangan",
        verRevRerima:        "ver_revterima",
        verUserRevisi:       "ver_usrrevisi",
        verTanggalRevisi:    "ver_tglrevisi",
        verRevisi:           "ver_revisi",
        sebelumDiskon:       "___", // exist but missing
        diskon:              "___", // exist but missing
        setelahDiskon:       "___", // exist but missing
        ppn:                 "ppn",
        setelahPpn:          "___", // exist but missing
        pembulatan:          "___", // exist but missing
        nilaiAkhir:          "___", // exist but missing
        kodeRefPo:           "kode_reffpo",
        idPemasokOri:        "id_pbf_ori",
        namaPemasokOri:      "nama_pbf_ori",
        idJenisAnggaranOri:  "id_jenisanggaran_ori",
        idSumberDanaOri:     "id_sumberdana_ori",
        idJenisHargaOri:     "id_jenisharga_ori",
        idCaraBayarOri:      "id_carabayar_ori",
        ppnOri:              "ppn_ori",

        objPemasok:          PemasokFields,
        objRefDo:            RefDoFields,
        objRefPl:            RefPlFields,
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
        hargaTotal:        "___",// exist but missing
        diskonHarga:       "___", // exist but missing
        hargaAkhir:        "___", // exist but missing
        jumlahRencana:     "___", // exist but missing
        jumlahHps:         "___", // exist but missing
        jumlahPl:          "___", // exist but missing
        jumlahDo:          "___", // exist but missing
        jumlahTerima:      "___", // exist but missing
        jumlahRetur:       "___", // exist but missing
    }

    export interface PemasokFields {
        id:   "id",
        kode: "kode",
        nama: "nama_pbf",
    }

    export interface RefDoFields {
        id:                 "id",
        kode:               "kode",
        noDokumen:          "no_doc",
        subjenisAnggaran:   "subjenis_anggaran",
        bulanAwalAnggaran:  "blnawal_anggaran",
        bulanAkhirAnggaran: "blnakhir_anggaran",
        tahunAnggaran:      "thn_anggaran",
        nilaiAkhir:         "nilai_akhir",
        noSpk:              "no_spk",
        namaPemasok:        "nama_pbf",
        tanggalTempoKirim:  "tanggalTempoKirim", // ???
    }

    export interface RefPlFields {
        id:                 "id",
        kode:               "kode",
        noDokumen:          "no_doc",
        subjenisAnggaran:   "subjenis_anggaran",
        bulanAwalAnggaran:  "blnawal_anggaran",
        bulanAkhirAnggaran: "blnakhir_anggaran",
        tahunAnggaran:      "thn_anggaran",
        nilaiAkhir:         "nilai_akhir",
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
            revisiPl: JSON.parse(`<?=json_encode($revisiPlAccess) ?>`),
            revisiDokumen: JSON.parse(`<?=json_encode($revisiDokumenAccess) ?>`),
            revisiItem: JSON.parse(`<?=json_encode($revisiItemAccess) ?>`),
            verifikasiRevisiGudang: JSON.parse(`<?=json_encode($verifikasiRevisiGudangAccess) ?>`),
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
                button_1: {class: ".printBtn",   text: tlm.stringRegistry._<?= $h("Print") ?>},
                button_2: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".tambahRevisiFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"},
                    hidden_2: {name: "submit", value: "save"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Revisi Ke-") ?>,
                        input: {class: ".revisiKeFld", name: "revisiKe"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Penerimaan") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        input: {class: ".tanggalJatuhTempoFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Faktur") ?>,
                        input: {class: ".noFakturFld", name: "noFaktur"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Surat Jalan") ?>,
                        input: {class: ".noSuratJalanFld", name: "noSuratJalan"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Tarik Dengan") ?>,
                        select: {
                            class: ".tarikDenganFld",
                            name: "tarikDengan",
                            option_1: {value: "spk", label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>},
                            option_2: {value: "do",  label: tlm.stringRegistry._<?= $h("Ref. Delivery Order") ?>},
                            option_3: {value: "pbf", label: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        }
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>,
                        input: {class: ".kodeRefPlFld", name: "kodeRefPl"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Ref. Delivery Order") ?>,
                        input: {class: ".kodeRefDoFld", name: "kodeRefDo"}
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
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                        select: {
                            class: ".idGudangPenyimpananFld",
                            name: "idGudangPenyimpanan",
                            option_1: {value: 59, label: tlm.stringRegistry._<?= $h("Gudang Induk Farmasi") ?>},
                            option_2: {value: 60, label: tlm.stringRegistry._<?= $h("Gudang Gas Medis") ?>},
                            option_3: {value: 69, label: tlm.stringRegistry._<?= $h("Gudang Konsinyasi") ?>}
                        }
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Tabung Gas") ?>,
                        radio_1: {class: ".tabungGasFld .tabungGasYes", name: "tabungGas", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                        radio_2: {class: ".tabungGasFld .tabungGasNo",  name: "tabungGas", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi Penerimaan") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verRevTerimaFld" name="verRevRerima" value="1"/>
                            <input class="userTerimaFld" name="verUserRevisi" value="------"/>
                            <input class="tanggalTerimaFld" name="verTanggalRevisi" value="00-00-00 00:00:00"/>
                        `}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi Gudang") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verGudangFld" name="verRevisi" value="1"/>
                            <input class="userGudangFld" name="verUserRevisi" value="------"/>
                            <input class="tanggalGudangFld" name="verTanggalRevisi" value="00-00-00 00:00:00"/>
                        `}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_26: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_27: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_28: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        staticText: {class: ".setelahPpnStc"}
                    },
                    formGroup_29: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_30: {
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
                            td_1:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6:  {colspan: 5, text: tlm.stringRegistry._<?= $h("Pengadaan") ?>},
                            td_7:  {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9:  {colspan: 3, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_10: {colspan: 6, text: tlm.stringRegistry._<?= $h("Realisasi") ?>}
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
                            td_10: {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                            td_11: {text: tlm.stringRegistry._<?= $h("Rencana") ?>},
                            td_12: {text: tlm.stringRegistry._<?= $h("HPS") ?>},
                            td_13: {text: tlm.stringRegistry._<?= $h("SPK") ?>},
                            td_14: {text: tlm.stringRegistry._<?= $h("DO") ?>},
                            td_15: {text: tlm.stringRegistry._<?= $h("Bonus") ?>},
                            td_16: {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                            td_17: {text: tlm.stringRegistry._<?= $h("Retur") ?>}
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
                                staticText_14: {class: ".no"}
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
                            td_17: {class: ".jumlahRencanaStc"},
                            td_18: {class: ".jumlahHpsStc"},
                            td_19: {class: ".jumlahPlStc"},
                            td_20: {class: ".jumlahDoStc"},
                            td_21: {class: ".jumlahBonusStc"},
                            td_22: {class: ".jumlahTerimaStc"},
                            td_23: {class: ".jumlahReturStc"}
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
        const {numToShortMonthName: nToS, toSystemNumber: sysNum, toCurrency: currency, stringRegistry: str, nowVal, preferInt} = tlm;
        const userName = tlm.user.nama;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const oldData = {
            pemasok: "",
            jenisAnggaran: "",
            sumberDana: "",
            jenisHarga: "",
            caraBayar: "",
            ppn: "",
        };
        const revisiPool = {
            pemasok: "",
            jenisAnggaran: "",
            sumberDana: "",
            jenisHarga: "",
            caraBayar: "",
            ppn: "",
        };

        /** @type {HTMLDivElement} */      const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLButtonElement} */   const printBtn = divElm.querySelector(".printBtn");
        /** @type {HTMLInputElement} */    const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */    const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */    const revisiKeFld = divElm.querySelector(".revisiKeFld");
        /** @type {HTMLInputElement} */    const tanggalJatuhTempoFld = divElm.querySelector(".tanggalJatuhTempoFld");
        /** @type {HTMLSelectElement} */   const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */   const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */   const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */   const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */   const tarikDenganFld = divElm.querySelector(".tarikDenganFld");
        /** @type {HTMLSelectElement} */   const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */   const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */   const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLSelectElement} */   const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLInputElement} */    const tabungGasYes = divElm.querySelector(".tabungGasYes");
        /** @type {HTMLInputElement} */    const tabungGasNo = divElm.querySelector(".tabungGasNo");
        /** @type {HTMLTextAreaElement} */ const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLInputElement} */    const verRevTerimaFld = divElm.querySelector(".verRevTerimaFld");
        /** @type {HTMLInputElement} */    const userTerimaFld = divElm.querySelector(".userTerimaFld");
        /** @type {HTMLInputElement} */    const tanggalTerimaFld = divElm.querySelector(".tanggalTerimaFld");
        /** @type {HTMLInputElement} */    const verGudangFld = divElm.querySelector(".verGudangFld");
        /** @type {HTMLInputElement} */    const userGudangFld = divElm.querySelector(".userGudangFld");
        /** @type {HTMLInputElement} */    const tanggalGudangFld = divElm.querySelector(".tanggalGudangFld");
        /** @type {HTMLDivElement} */      const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */      const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */      const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLInputElement} */    const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */      const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLDivElement} */      const setelahPpnStc = divElm.querySelector(".setelahPpnStc");
        /** @type {HTMLDivElement} */      const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */      const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");
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

        const tambahRevisiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".tambahRevisiFrm"),
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisi.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                revisiKeFld.value = data.revisiKe ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                tanggalJatuhTempoFld.value = data.tanggalJatuhTempo ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                noFakturWgt.value = data.noFaktur ?? "";
                noSuratJalanWgt.value = data.noSuratJalan ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                kodeRefPlWgt.value = data.kodeRefPl ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                kodeRefDoWgt.value = data.kodeRefDo ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
                data.tabungGas ? tabungGasYes.checked = true : tabungGasNo.checked = true;
                keteranganFld.value = data.keterangan ?? "";
                verRevTerimaFld.value = data.verRevRerima ?? "";
                verGudangFld.value = data.verRevisi ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";

                if (data.tabungGas == "1") {
                    idJenisAnggaranFld.value = "6";
                    idGudangPenyimpananFld.value = "60";
                } else {
                    idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                    idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                }

                kodeRefPlWgt.clearOptions();
                if (data.objRefPl) {
                    kodeRefPlWgt.addOption(data.objRefPl);
                    kodeRefPlWgt.value = data.objRefPl.kode;
                }

                idPemasokWgt.clearOptions();
                if (data.objPemasok) {
                    idPemasokWgt.addOption(data.objPemasok);
                    idPemasokWgt.value = data.objPemasok.id;
                }

                kodeRefDoWgt.clearOptions();
                if (data.kodeRefPo != "" && data.objRefDo) {
                    kodeRefDoWgt.addOption(data.objRefDo);
                    kodeRefDoWgt.value = data.objRefDo.kode;

                    tarikDenganFld.value = "do";
                } else {
                    tarikDenganFld.value = "spk";
                }

                const {idJenisAnggaranOri, idSumberDanaOri, idJenisHargaOri, idCaraBayarOri} = data;

                let oldVal = data.namaPemasokOri;
                let newVal = data.objPemasok.nama;
                let val = str._<?= $h(" || Perubahan Pemasok: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
                revisiPool.pemasok = (oldVal == newVal) ? "" : val;
                oldData.pemasok = oldVal;

                oldVal = idJenisAnggaranFld.querySelector(`option[value=${idJenisAnggaranOri}]`).text;
                newVal = idJenisAnggaranFld.selectedOptions[0].innerHTML;
                val = str._<?= $h(" || Perubahan Jenis Anggaran: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
                revisiPool.jenisAnggaran = (oldVal == newVal) ? "" : val;
                oldData.jenisAnggaran = oldVal;

                oldVal = idSumberDanaFld.querySelector(`option[value=${idSumberDanaOri}]`).text;
                newVal = idSumberDanaFld.selectedOptions[0].innerHTML;
                val = str._<?= $h(" || Perubahan Sumber Dana: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
                revisiPool.sumberDana = (oldVal == newVal) ? "" : val;
                oldData.sumberDana = oldVal;

                oldVal = idJenisHargaFld.querySelector(`option[value=${idJenisHargaOri}]`).text;
                newVal = idJenisHargaFld.selectedOptions[0].innerHTML;
                val = str._<?= $h(" || Perubahan Jenis Harga: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
                revisiPool.jenisHarga = (oldVal == newVal) ? "" : val;
                oldData.jenisHarga = oldVal;

                oldVal = idCaraBayarFld.querySelector(`option[value=${idCaraBayarOri}]`).text;
                newVal = idCaraBayarFld.selectedOptions[0].innerHTML;
                val = str._<?= $h(" || Perubahan Cara Bayar: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
                revisiPool.caraBayar = (oldVal == newVal) ? "" : val;
                oldData.caraBayar = oldVal;

                oldVal = data.ppnOri;
                newVal = data.ppn;
                val = str._<?= $h(" || Perubahan PPN: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
                revisiPool.ppn = (oldVal == newVal) ? "" : val;
                oldData.ppn = oldVal;

                setKeterangan();

                let x = 1;
                divElm.querySelectorAll(".no").forEach(elm => {
                    elm.innerHTML = x++;
                    const idKatalog = closest(elm, "tr").id;
                    let z = 1;
                    divElm.querySelectorAll(`.noUrutStc[par="${idKatalog}"]`).forEach(/** @type {HTMLDivElement} */ item => {
                        closest(item, "tr").fields.noUrutFld.value = z;
                        item.dataset.no_urut = z;
                        item.innerHTML = z;
                        z++;
                    });
                });
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._dataUrl = "<?= $addDataUrl ?>";
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.loadData({});
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "???";
                    verGudangFld.disabled = false;
                    verRevTerimaFld.disabled = false;
                    tanggalDokumenWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    noFakturWgt.disabled = true;
                    noSuratJalanWgt.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idGudangPenyimpananFld.disabled = true;
                },
                edit(data) {
                    this._dataUrl = "<?= $editDataUrl ?>";
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("Revisi Penerimaan dari SP/SPK/Kontrak") ?>;

                    actionFld.value = "???";
                    verGudangFld.disabled = false;
                    verRevTerimaFld.disabled = false;
                    tanggalDokumenWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    noFakturWgt.disabled = true;
                    noSuratJalanWgt.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idGudangPenyimpananFld.disabled = true;
                },
                revisiPl(data) {
                    this._dataUrl = "<?= $revisiPlDataUrl ?>";
                    this._actionUrl = "<?= $revisiPlActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "revisi_pl";
                    verGudangFld.disabled = true;
                    verRevTerimaFld.disabled = false;
                    tanggalDokumenWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    noFakturWgt.disabled = true;
                    noSuratJalanWgt.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idGudangPenyimpananFld.disabled = true;
                },
                revisiDokumen(data) {
                    this._dataUrl = "<?= $revisiDokumenDataUrl ?>";
                    this._actionUrl = "<?= $revisiDokumenActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "revisi_dokumen";
                    verGudangFld.disabled = true;
                    verRevTerimaFld.disabled = false;
                    tanggalDokumenWgt.disabled = false;
                    noDokumenWgt.disabled = false;
                    noFakturWgt.disabled = false;
                    noSuratJalanWgt.disabled = false;
                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    idGudangPenyimpananFld.disabled =  false;
                },
                revisiItem(data) {
                    this._dataUrl = "<?= $revisiItemDataUrl ?>";
                    this._actionUrl = "<?= $revisiItemActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "revisi_item";
                    verGudangFld.disabled = true;
                    verRevTerimaFld.disabled = false;
                    tanggalDokumenWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    noFakturWgt.disabled = true;
                    noSuratJalanWgt.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idGudangPenyimpananFld.disabled = true;
                },
                verifikasiGudang(data) {
                    this._dataUrl = "<?= $verifikasiGudangDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiGudangActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "verif_gudang";
                    verGudangFld.disabled = false;
                    verRevTerimaFld.disabled = true;
                    tanggalDokumenWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    noFakturWgt.disabled = true;
                    noSuratJalanWgt.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idGudangPenyimpananFld.disabled =  true;
                },
                verifikasiRevisiGudang(data) {
                    this._dataUrl = "<?= $verifikasiRevisiGudangDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiRevisiGudangActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "???";
                    verGudangFld.disabled = false;
                    verRevTerimaFld.disabled = false;
                    tanggalDokumenWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    noFakturWgt.disabled = true;
                    noSuratJalanWgt.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idGudangPenyimpananFld.disabled = true;
                },
            },
            onInit() {
                this.loadProfile("edit");
            },
            onBeforeSubmit(event) {
                const action = actionFld.value;
                const confirmStr = str._<?= $h("Apakah Anda yakin ingin menyelesaikan Revisi ini?") ?>;
                const alertPenerimaanStr = str._<?= $h('Anda harus mencentang "Verifikasi Penerimaan".') ?>;
                const alertGudangStr = str._<?= $h('Anda harus mencentang "Verifikasi Gudang".') ?>;

                if (action == "revisi_pl") {
                    if (verRevTerimaFld.checked) {
                        confirm(confirmStr) || event.preventDefault();
                    } else {
                        alert(alertPenerimaanStr);
                        event.preventDefault();
                    }

                } else if (action == "revisi_item" || action == "revisi_dokumen") {
                    if (verRevTerimaFld.checked) {
                        confirm(confirmStr) || event.preventDefault();
                    } else {
                        alert(alertPenerimaanStr);
                        event.preventDefault();
                    }

                } else if (action == "verif_gudang") {
                    if (verGudangFld.checked) {
                        confirm(confirmStr) || event.preventDefault();
                    } else {
                        alert(alertGudangStr);
                        event.preventDefault();
                    }
                }
            },
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
                widget.show();
                widget.loadData({kode: kodeFld.value, revisiKe: 0}, true);
            },
            resetBtnId: false,
        });

        function setKeterangan() {
            for (const key in revisiPool) {
                if (!revisiPool.hasOwnProperty(key)) continue;
                keteranganFld.value += revisiPool[key];
            }
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

        /** @param {HTMLTableRowElement} trElm */
        function hitungSubTotal(trElm) {
            const fields = trElm.fields;
            const hargaKemasan = sysNum(fields.hargaKemasanWgt.value);
            const diskonItem = sysNum(fields.diskonItemWgt.value);
            const jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
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

        const noFakturWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noFakturFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == kodeFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoFakturUrl ?>",
            term: "value",
            additionalData: {field: "noFaktur"}
        });

        const noSuratJalanWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noSuratJalanFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == kodeFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoSuratJalanUrl ?>",
            term: "value",
            additionalData: {field: "noSuratJalan"}
        });

        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPlFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisi.RefPlFields} item */
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
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisi.RefPlFields} item */
            itemRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                return `<div class="item">${item.noDokumen} (${anggaran1})</div>`;
            },
        });

        const kodeRefDoWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefDoFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisi.RefDoFields} item */
            optionRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                const awal = item.id ? item.bulanAwalAnggaran : bulanAwalAnggaranFld.value;
                const akhir = item.id ? item.bulanAkhirAnggaran : bulanAkhirAnggaranFld.value;
                const tahun = item.id ? item.tahunAnggaran : tahunAnggaranFld.value;
                const nilaiAkhir = item.id ? item.nilaiAkhir : 0;

                const anggaran2 = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-2"><b>${item.noSpk}</b></div>
                        <div class="col-xs-3"><b>${item.namaPemasok}</b></div>
                        <div class="col-xs-2"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-1">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisi.RefDoFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.noSpk})</div>`},
        });

        divElm.querySelector(".tabungGasFld").addEventListener("click", (event) => {
            divElm.querySelector("[name=sts_tabunggm]").value = event.target.checked ? 1 : 0;
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama"],
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisi.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisi.PemasokFields} item */
            itemRenderer(item) {return `<div class="item">${item.nama} (${item.kode})</div>`},
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Penerimaan.AddRevisi.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefPoFld.value = data.kodeRefPo ?? "";
                fields.kodeRefRoFld.value = data.kodeRefRo ?? "";
                fields.kodeRefPlFld.value = data.kodeRefPl ?? "";
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogFld.value = data.idKatalog ?? "";
                fields.idPabrikFld.value = data.idPabrik ?? "";
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
                // note: simplified version. in order to sync to other views.
                // last exist of complex code: commit-58d6a2d
                // on fragment: itemTbl.addEventListener("focusout", ".noBatchFld", () => {})
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

                const hargaKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".hargaKemasanFld"),
                    errorRules: [{greaterThan: 0}],
                    ...tlm.currNumberSetting
                });

                const tanggalKadaluarsaWgt = new spl.DateTimeWidget({
                    element: trElm.querySelector(".tanggalKadaluarsaFld"),
                    // TODO: js: uncategorized: add "already expired", and "less than 2 years" rules
                    errorRules: [{required: true}],
                    ...tlm.dateWidgetSetting
                });

                trElm.fields = {
                    noBatchWgt,
                    diskonItemWgt,
                    isiKemasanWgt,
                    jumlahKemasanWgt,
                    hargaKemasanWgt,
                    tanggalKadaluarsaWgt,
                    kodeRefPoFld: trElm.querySelector(".kodeRefPoFld"),
                    kodeRefRoFld: trElm.querySelector(".kodeRefRoFld"),
                    kodeRefPlFld: trElm.querySelector(".kodeRefPlFld"),
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    idPabrikFld: trElm.querySelector(".idPabrikFld"),
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
                fields.noBatchWgt.destroy();
                fields.diskonItemWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.jumlahKemasanWgt.destroy();
                fields.hargaKemasanWgt.destroy();
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

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            const trElm = closest(jumlahKemasanFld, "tr");
            const fields = trElm.fields;
            const jumlahItem = sysNum(jumlahKemasanFld.value) * sysNum(fields.isiKemasanWgt.value);
            const maksimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jMax);

            if (jumlahItem > maksimumJumlahItem) {
                alert(`
                    Jumlah tidak boleh melebihi jumlah DO atau (Jumlah SP/SPK/Kontrak - Jumlah Penerimaan).
                    Maximum Jumlah HPS yang dibolehkan adalah ${maksimumJumlahItem}`
                );
            }

            hitungSubTotal(trElm);
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.Penerimaan.AddRevisi.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        verRevTerimaFld.addEventListener("click", () => {
            const isChecked = verRevTerimaFld.checked;
            userTerimaFld.value = isChecked ? userName : "";
            tanggalTerimaFld.value = isChecked ? nowVal("user") : "";
        });

        verGudangFld.addEventListener("click", () => {
            const isChecked = verGudangFld.checked;
            userGudangFld.value = isChecked ? userName : "";
            tanggalGudangFld.value = isChecked ? nowVal("user") : "";
        });

        printBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeFld.value, versi: 1}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(tambahRevisiWgt, tanggalDokumenWgt, noDokumenWgt, noSuratJalanWgt);
        this._widgets.push(noFakturWgt, kodeRefPlWgt, kodeRefDoWgt, idPemasokWgt, itemWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tambahRevisiWgt);
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
