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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/addrevisiOthers.php the original file
 */
final class FormRevisiLainnya
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $revisiPlAccess,
        array  $revisiDokumenAccess,
        array  $revisiItemAccess,
        array  $verifikasiRevisiGudangAccess,
        string $editDataUrl,
        string $revisiPlDataUrl,
        string $revisiDokumenDataUrl,
        string $revisiItemDataUrl,
        string $verifikasiGudangDataUrl,
        string $verifikasiRevisiGudangDataUrl,
        string $editActionUrl,
        string $revisiPlActionUrl,
        string $revisiDokumenActionUrl,
        string $revisiItemActionUrl,
        string $verifikasiGudangActionUrl,
        string $verifikasiRevisiGudangActionUrl,
        string $cekUnikNoFakturUrl,
        string $cekUnikNoSuratJalanUrl,
        string $pemasokAcplUrl,
        string $cekUnikNoDokumenUrl,
        string $cekStokUrl,
        string $printWidgetId,
        string $tableWidgetId,
        string $viewLainnyaWidgetId,
        string $bulanSelect,
        string $tahunSelect,
        string $jenisAnggaranSelect,
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
namespace his.FatmaPharmacy.views.Penerimaan.AddRevisiOthers {
    export interface FormFields {
        kode:                "kode",
        penerimaanKe:        "___", // exist but missing
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
        verRevTerima:        "ver_revterima",
        verUserRevisi:       "ver_usrrevisi",
        verTangalRevisi:     "ver_tglrevisi",
        keterangan:          "keterangan",
        verRevisi:           "ver_revisi",
        sebelumDiskon:       "___", // exist but missing
        diskon:              "___", // exist but missing
        setelahDiskon:       "___", // exist but missing
        ppn:                 "ppn",
        setelahPpn:          "___", // exist but missing
        pembulatan:          "___", // exist but missing
        nilaiAkhir:          "___", // exist but missing

        objPemasok:          PemasokFields,
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
                button_1: {class: ".btn-nextrevisi", text: tlm.stringRegistry._<?= $h("Next") ?>, title: "Next Revisi Penerimaan"}, // truely not have logic statement
                button_2: {class: ".printBtn",       text: tlm.stringRegistry._<?= $h("Print") ?>},
                button_3: {class: ".kembaliBtn",     text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".tambahRevisiLainnyaFrm",
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
                        input: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                        select: {
                            class: ".idGudangPenyimpananFld",
                            name: "idGudangPenyimpanan",
                            option_1: {value: 59, label: tlm.stringRegistry._<?= $h("Gudang Induk Farmasi") ?>},
                            option_2: {value: 60, label: tlm.stringRegistry._<?= $h("Gudang Gas Medis") ?>},
                            option_3: {value: 69, label: tlm.stringRegistry._<?= $h("Gudang Konsinyasi") ?>}
                        }
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Tabung Gas") ?>,
                        radio_1: {class: ".tabungGasYes", name: "tabungGas", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                        radio_2: {class: ".tabungGasNo",  name: "tabungGas", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Verif Tim Penerima") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verRevTerimaFld" name="verRevTerima" value="1"/>
                            <input class="userTerimaFld" name="verUserRevisi" value="------"/>
                            <input class="tanggalTerimaFld" name="verTangalRevisi" value="00-00-00 00:00:00"/>
                        `}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        input: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi Gudang") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verGudangFld" name="verRevisi" value="1"/>
                            <input class="userGudangFld" name="verUserRevisi" value="------"/>
                            <input class="tanggalGudangFld" name="verTangalRevisi" value="00-00-00 00:00:00"/>
                        `}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        input: {class: ".sebelumDiskonFld"}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        input: {class: ".diskonFld"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        input: {class: ".setelahDiskonFld"}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        input: {class: ".setelahPpnFld"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        input: {class: ".pembulatanFld"}
                    },
                    formGroup_26: {
                        label: tlm.stringRegistry._<?= $h("Nilai Akhir") ?>,
                        input: {class: ".nilaiAkhirFld"}
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
                            td_16: {class: ".hargaAkhirStc"}
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
        const {toSystemNumber: sysNum, toCurrency: currency, numToShortMonthName: nToS, stringRegistry: str, nowVal, preferInt} = tlm;
        const userName = tlm.user.nama;

        const oldData = {
            noFaktur: "",
            noSuratJalan: "",
            pemasok: "",
            sumberDana: "",
            jenisAnggaran: "",
            tanggalPenerimaan: "",
            jenisHarga: "",
            caraBayar: "",
            ppn: "",
            anggaran: "",
        };
        const revisiPool = {
            noFaktur: "",
            noSuratJalan: "",
            pemasok: "",
            sumberDana: "",
            jenisAnggaran: "",
            tanggalPenerimaan: "",
            jenisHarga: "",
            caraBayar: "",
            ppn: "",
            anggaran: "",
        };

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLButtonElement} */ const printBtn = divElm.querySelector(".printBtn");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */  const penerimaanKeFld = divElm.querySelector(".penerimaanKeFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLInputElement} */  const noFakturFld = divElm.querySelector(".noFakturFld");
        /** @type {HTMLInputElement} */  const noSuratJalanFld = divElm.querySelector(".noSuratJalanFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */ const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLInputElement} */  const tabungGasYes = divElm.querySelector(".tabungGasYes");
        /** @type {HTMLInputElement} */  const tabungGasNo = divElm.querySelector(".tabungGasNo");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLInputElement} */  const verRevTerimaFld = divElm.querySelector(".verRevTerimaFld");
        /** @type {HTMLInputElement} */  const userTerimaFld = divElm.querySelector(".userTerimaFld");
        /** @type {HTMLInputElement} */  const tanggalTerimaFld = divElm.querySelector(".tanggalTerimaFld");
        /** @type {HTMLInputElement} */  const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLInputElement} */  const verGudangFld = divElm.querySelector(".verGudangFld");
        /** @type {HTMLInputElement} */  const userGudangFld = divElm.querySelector(".userGudangFld");
        /** @type {HTMLInputElement} */  const tanggalGudangFld = divElm.querySelector(".tanggalGudangFld");
        /** @type {HTMLInputElement} */  const sebelumDiskonFld = divElm.querySelector(".sebelumDiskonFld");
        /** @type {HTMLInputElement} */  const diskonFld = divElm.querySelector(".diskonFld");
        /** @type {HTMLInputElement} */  const setelahDiskonFld = divElm.querySelector(".setelahDiskonFld");
        /** @type {HTMLInputElement} */  const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */    const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLInputElement} */  const setelahPpnFld = divElm.querySelector(".setelahPpnFld");
        /** @type {HTMLInputElement} */  const pembulatanFld = divElm.querySelector(".pembulatanFld");
        /** @type {HTMLInputElement} */  const nilaiAkhirFld = divElm.querySelector(".nilaiAkhirFld");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld,);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const tambahRevisiLainnyaWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".tambahRevisiLainnyaFrm"),
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisiOthers.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                penerimaanKeFld.value = data.penerimaanKe ?? "";
                tanggalPenerimaanWgt.value = data.tanggalPenerimaan ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noFakturWgt.value = data.noFaktur ?? "";
                noSuratJalanWgt.value = data.noSuratJalan ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                data.tabungGas ? tabungGasYes.checked = true : tabungGasNo.checked = true;
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                verRevTerimaFld.checked = !!data.verRevTerima;
                keteranganFld.value = data.keterangan ?? "";
                verGudangFld.checked = !!data.verRevisi;
                sebelumDiskonFld.value = data.sebelumDiskon ?? "";
                diskonFld.value = data.diskon ?? "";
                setelahDiskonFld.value = data.setelahDiskon ?? "";
                ppnFld.checked = !!data.ppn;
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnFld.value = data.setelahPpn ?? "";
                pembulatanFld.value = data.pembulatan ?? "";
                nilaiAkhirFld.value = data.nilaiAkhir ?? "";

                if (data.idPemasok) {
                    idPemasokWgt.addOption(data.objPemasok);
                    idPemasokWgt.value = data.idPemasok;
                }

                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = data;
                const {idSumberDana, idJenisAnggaran, idJenisHarga, idCaraBayar} = data;

                oldData.noFaktur = data.noFaktur;
                oldData.noSuratJalan = data.noSuratJalan;
                oldData.pemasok = data.objPemasok.nama;
                oldData.sumberDana = idSumberDanaFld.querySelector(`option[value=${idSumberDana}]`).text;
                oldData.jenisAnggaran = idJenisAnggaranFld.querySelector(`option[value=${idJenisAnggaran}]`).text;
                oldData.tanggalPenerimaan = data.tanggalPenerimaan;
                oldData.jenisHarga = idJenisHargaFld.querySelector(`option[value=${idJenisHarga}]`).text;
                oldData.caraBayar = idCaraBayarFld.querySelector(`option[value=${idCaraBayar}]`).text;
                oldData.ppn = data.ppn;
                oldData.anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
            },
            onBeforeSubmit(event) {
                const action = actionFld.value;
                const confirmStr = str._<?= $h("Apakah Anda yakin ingin menyelesaikan Revisi Penerimaan ini?") ?>;

                if (action == "revisi_item" || action == "revisi_dokumen") {
                    if (verRevTerimaFld.checked) {
                        confirm(confirmStr) || event.preventDefault();
                    } else {
                        alert(str._<?= $h('Anda harus mencentang "Verifikasi Penerimaan".') ?>);
                        event.preventDefault();
                    }

                } else if (action == "verif_gudang") {
                    if (verGudangFld.checked) {
                        confirm(confirmStr) || event.preventDefault();
                    } else {
                        alert(str._<?= $h('Anda harus mencentang "Verifikasi Gudang".') ?>);
                        event.preventDefault();
                    }
                }
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                edit(data) {
                    this._dataUrl = "<?= $editDataUrl ?>";
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "???";
                    ppnFld.disabled = true;
                    verRevTerimaFld.disabled = true;
                    verGudangFld.disabled = true;
                    tanggalPenerimaanWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    noFakturFld.disabled = true;
                    noSuratJalanFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idPemasokWgt.disabled = true;
                    idSumberDanaFld.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                },
                revisiPl(data) {
                    this._dataUrl = "<?= $revisiPlDataUrl ?>";
                    this._actionUrl = "<?= $revisiPlActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "???";
                    ppnFld.disabled = true;
                    verRevTerimaFld.disabled = true;
                    verGudangFld.disabled = true;
                    tanggalPenerimaanWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    noFakturFld.disabled = true;
                    noSuratJalanFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idPemasokWgt.disabled = true;
                    idSumberDanaFld.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                },
                revisiDokumen(data) {
                    this._dataUrl = "<?= $revisiDokumenDataUrl ?>";
                    this._actionUrl = "<?= $revisiDokumenActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "revisi_dokumen";
                    ppnFld.disabled = true;
                    verRevTerimaFld.disabled = false;
                    verGudangFld.disabled = true;
                    tanggalPenerimaanWgt.disabled = false;
                    noDokumenWgt.disabled = false;
                    idJenisAnggaranFld.disabled = false;
                    noFakturFld.disabled = false;
                    noSuratJalanFld.disabled = false;
                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    idPemasokWgt.disabled = false;
                    idSumberDanaFld.disabled = false;
                    idJenisHargaFld.disabled = false;
                    idCaraBayarFld.disabled = false;
                },
                revisiItem(data) {
                    this._dataUrl = "<?= $revisiItemDataUrl ?>";
                    this._actionUrl = "<?= $revisiItemActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "revisi_item";
                    ppnFld.disabled = false;
                    verRevTerimaFld.disabled = false;
                    verGudangFld.disabled = true;
                    tanggalPenerimaanWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    noFakturFld.disabled = true;
                    noSuratJalanFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idPemasokWgt.disabled = true;
                    idSumberDanaFld.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                },
                verifikasiGudang(data) {
                    this._dataUrl = "<?= $verifikasiGudangDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiGudangActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "verif_gudang";
                    ppnFld.disabled = true;
                    verRevTerimaFld.disabled = true;
                    verGudangFld.disabled = false;
                    tanggalPenerimaanWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    noFakturFld.disabled = true;
                    noSuratJalanFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idPemasokWgt.disabled = true;
                    idSumberDanaFld.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                },
                verifikasiRevisiGudang(data) {
                    this._dataUrl = "<?= $verifikasiRevisiGudangDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiRevisiGudangActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "???";
                    ppnFld.disabled = true;
                    verRevTerimaFld.disabled = true;
                    verGudangFld.disabled = true;
                    tanggalPenerimaanWgt.disabled = true;
                    noDokumenWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    noFakturFld.disabled = true;
                    noSuratJalanFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idPemasokWgt.disabled = true;
                    idSumberDanaFld.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
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
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
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

            sebelumDiskonFld.value = currency(sebelumDiskon);
            diskonFld.value = currency(nilaiDiskon);
            setelahDiskonFld.value = currency(setelahDiskon);
            ppnStc.innerHTML = currency(nilaiPpn);
            setelahPpnFld.value = currency(setelahPpn);
            pembulatanFld.value = currency(pembulatan);
            nilaiAkhirFld.value = currency(nilaiAkhir);
        }

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

        const tanggalPenerimaanWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalPenerimaanFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        noFakturFld.addEventListener("focusout", () => {
            const oldVal = oldData.noFaktur;
            const newVal = noFakturFld.value;
            const val = str._<?= $h(" || Perubahan Faktur: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.noFaktur = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        noSuratJalanFld.addEventListener("focusout", () => {
            const oldVal = oldData.noSuratJalan;
            const newVal = noSuratJalanFld.value;
            const val = str._<?= $h(" || Perubahan Surat Jalan: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.noSuratJalan = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisiOthers.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Penerimaan.AddRevisiOthers.PemasokFields} item */
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
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.Penerimaan.AddRevisiOthers.PemasokFields} */
                const oldVal = oldData.namaPemasok;
                const newVal = this.options[value].nama;
                const val = str._<?= $h(" || Perubahan Pemasok: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
                revisiPool.pemasok = (oldVal == newVal) ? "" : val;
                setKeterangan();
            }
        });

        idSumberDanaFld.addEventListener("change", () => {
            const oldVal = oldData.sumberDana;
            const newVal = idSumberDanaFld.selectedOptions[0].innerHTML;
            const val = str._<?= $h(" || Perubahan Sumber Dana: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.sumberDana = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        idJenisAnggaranFld.addEventListener("change", () => {
            const oldVal = oldData.jenisAnggaran;
            const newVal = idJenisAnggaranFld.selectedOptions[0].innerHTML;
            const val = str._<?= $h(" || Perubahan Mata Anggaran: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.jenisAnggaran = (oldVal == newVal) ? "" : val;
            setKeterangan();
            alert(str._<?= $h('Mengubah "Mata Anggaran" akan mengubah laporan.') ?>);
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

        tanggalPenerimaanWgt.addEventListener("focusout", () => {
            const oldVal = oldData.tanggalPenerimaan;
            const newVal = tanggalPenerimaanWgt.value;
            const val = str._<?= $h(" || Perubahan Tanggal Terima: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.tanggalPenerimaan = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        function perubahanAnggaran() {
            const awal = bulanAwalAnggaranFld.value;
            const akhir = bulanAkhirAnggaranFld.value;
            const tahun = tahunAnggaranFld.value;

            const oldVal = oldData.anggaran;
            const newVal = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
            const val = str._<?= $h(" || Perubahan Anggaran: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.anggaran = (oldVal == newVal) ? "" : val;
            setKeterangan();
        }

        bulanAwalAnggaranFld.addEventListener("change", perubahanAnggaran);
        bulanAkhirAnggaranFld.addEventListener("change", perubahanAnggaran);
        tahunAnggaranFld.addEventListener("change", perubahanAnggaran);

        idJenisHargaFld.addEventListener("change", () => {
            const oldVal = oldData.jenisHarga;
            const newVal = idJenisHargaFld.selectedOptions[0].innerHTML;
            const val = str._<?= $h(" || Perubahan Jenis Harga: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.jenisHarga = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        idCaraBayarFld.addEventListener("change", () => {
            const oldVal = oldData.caraBayar;
            const newVal = idCaraBayarFld.selectedOptions[0].innerHTML;
            const val = str._<?= $h(" || Perubahan Cara Bayar: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.caraBayar = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        ppnFld.addEventListener("change", () => {
            const oldVal = oldData.ppn;
            const newVal = ppnFld.checked ? 10 : 0;
            const val = str._<?= $h(" || Perubahan PPN: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.ppn = (oldVal == newVal) ? "" : val;
            setKeterangan();
            hitungTotal();
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Penerimaan.AddRevisiOthers.TableFields} data
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
                fields.noBatchWgt.destroy();
                fields.diskonItemWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.jumlahKemasanWgt.destroy();
                fields.hargaKemasanWgt.destroy();
                fields.tanggalKadaluarsaWgt.destroy();
            },
            profile: {
                edit(trElm, data) {
                    this.loadData(data);
                    fields.idKemasanFld.disabled = true;
                    fields.noBatchWgt.disabled = true;
                    fields.tanggalKadaluarsaWgt.disabled = true;
                    fields.jumlahKemasanWgt.disabled = true;
                    fields.hargaKemasanWgt.disabled = true;
                    fields.diskonItemWgt.disabled = true;
                },
                revisiPl(trElm, data) {
                    this.loadData(data);
                    fields.idKemasanFld.disabled = true;
                    fields.noBatchWgt.disabled = true;
                    fields.tanggalKadaluarsaWgt.disabled = true;
                    fields.jumlahKemasanWgt.disabled = true;
                    fields.hargaKemasanWgt.disabled = true;
                    fields.diskonItemWgt.disabled = true;
                },
                revisiDokumen(trElm, data) {
                    this.loadData(data);
                    const fields = trElm.fields;
                    fields.idKemasanFld.disabled = true;
                    fields.noBatchWgt.disabled = true;
                    fields.tanggalKadaluarsaWgt.disabled = true;
                    fields.jumlahKemasanWgt.disabled = true;
                    fields.hargaKemasanWgt.disabled = true;
                    fields.diskonItemWgt.disabled = true;
                },
                revisiItem(trElm, data) {
                    this.loadData(data);
                    const fields = trElm.fields;
                    fields.idKemasanFld.disabled = false;
                    fields.noBatchWgt.disabled = false;
                    fields.tanggalKadaluarsaWgt.disabled = false;
                    fields.jumlahKemasanWgt.disabled = false;
                    fields.hargaKemasanWgt.disabled = false;
                    fields.diskonItemWgt.disabled = false;
                },
                verifikasiGudang(trElm, data) {
                    this.loadData(data);
                    const fields = trElm.fields;
                    fields.idKemasanFld.disabled = true;
                    fields.noBatchWgt.disabled = true;
                    fields.tanggalKadaluarsaWgt.disabled = true;
                    fields.jumlahKemasanWgt.disabled = true;
                    fields.hargaKemasanWgt.disabled = true;
                    fields.diskonItemWgt.disabled = true;
                },
                verifikasiRevisiGudang(trElm, data) {
                    this.loadData(data);
                    fields.idKemasanFld.disabled = true;
                    fields.noBatchWgt.disabled = true;
                    fields.tanggalKadaluarsaWgt.disabled = true;
                    fields.jumlahKemasanWgt.disabled = true;
                    fields.hargaKemasanWgt.disabled = true;
                    fields.diskonItemWgt.disabled = true;
                },
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
            const kemasanOpt = fields.idKemasanFld.selectedOptions[0];
            const kemasan = kemasanOpt.innerHTML;

            const isiKemasan = sysNum(kemasanOpt.dataset.is);
            const hargaKemasan = sysNum(kemasanOpt.dataset.hk);

            fields.kemasanFld.value = kemasan;
            fields.isiKemasanWgt.value = preferInt(isiKemasan);
            fields.hargaKemasanWgt.value = currency(hargaKemasan);

            hitungSubTotal(trElm);
            divElm.querySelectorAll(`.idKatalogFld[value="${trElm.id}"]`).forEach(item => {
                const fields = closest(item, "tr").fields;
                fields.isiKemasanWgt.value = preferInt(isiKemasan);
                fields.hargaKemasanWgt.value = currency(hargaKemasan);
                fields.jumlahKemasanWgt.dispatchEvent(new Event("focusout"));
            });
            hitungTotal();
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            hitungSubTotal(closest(jumlahKemasanFld, "tr"));
            hitungTotal();
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const hargaKemasanFld = event.target;
            if (!hargaKemasanFld.matches(".hargaKemasanFld")) return;

            const trElm = closest(hargaKemasanFld, "tr");
            const hargaKemasan = sysNum(hargaKemasanFld.value);

            hitungSubTotal(trElm);
            divElm.querySelectorAll(`.idKatalogFld[value="${trElm.id}"]`).forEach(item => {
                const fields = closest(item, "tr").fields;
                fields.hargaKemasanWgt.value = currency(hargaKemasan);
                fields.jumlahKemasanWgt.dispatchEvent(new Event("focusout"));
            });
            hitungTotal();
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const diskonItemFld = event.target;
            if (!diskonItemFld.matches(".diskonItemFld")) return;

            const trElm = closest(diskonItemFld, "tr");

            hitungSubTotal(trElm);
            divElm.querySelectorAll(`.idKatalogFld[value="${trElm.id}"]`).forEach(item => {
                const fields = closest(item, "tr").fields;
                fields.diskonItemWgt.value = sysNum(diskonItemFld.value);
                fields.jumlahKemasanWgt.dispatchEvent(new Event("focusout"));
            });
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.Penerimaan.AddRevisiOthers.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        function setKeterangan() {
            for (const key in revisiPool) {
                if (!revisiPool.hasOwnProperty(key)) continue;
                keteranganFld.value += revisiPool[key];
            }
        }

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
        this._widgets.push(tambahRevisiLainnyaWgt, noFakturWgt, noSuratJalanWgt, tanggalPenerimaanWgt, idPemasokWgt, noDokumenWgt, itemWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tambahRevisiLainnyaWgt);
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
