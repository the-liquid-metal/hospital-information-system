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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/add.php the original file
 * note: previously incorrectly named as add2.php
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        array  $verifikasiPenerimaanAccess,
        array  $verifikasiAkuntansiAccess,
        string $dataUrl,
        string $actionUrl,
        string $cekUnikNoDokumenUrl,
        string $penerimaanUrl,
        string $pemasokUrl,
        string $cekStokUrl,
        string $detailReturUrl,
        string $penyimpananSelect,
        string $bulanSelect,
        string $tahunSelect,
        string $sumberDanaSelect,
        string $jenisHargaSelect,
        string $caraBayarSelect,
        string $jenisAnggaranSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ReturFarmasiUi.Form {
    export interface FormFields {
        action:              string|"x" |"out";
        kode:                string|"in"|"out";
        tanggalDokumen:      string|"in"|"out";
        idGudangPenyimpanan: string|"in"|"out";
        noDokumen:           string|"in"|"out";
        idJenisAnggaran:     string|"in"|"out";
        kodeRefPl:           string|"in"|"out";
        objectRefPl:         RefPlFields;
        bulanAwalAnggaran:   string|"in"|"out";
        bulanAkhirAnggaran:  string|"in"|"out";
        tahunAnggaran:       string|"in"|"out";
        kodeRefPo:           string|"in"|"out";
        objectRefPo:         RefPoFields;
        idSumberDana:        string|"in"|"out";
        kodeRefPenerimaan:   string|"in"|"out";
        objectRefPenerimaan: RefPenerimaanFields;
        idJenisHarga:        string|"in"|"out";
        idCaraBayar:         string|"in"|"out";
        idPemasok:           string|"in"|"out";
        objectPemasok:       PemasokFields;
        keterangan:          string|"in"|"out";
        sebelumDiskon:       string|"in"|"out";
        diskon:              string|"in"|"out";
        setelahDiskon:       string|"?" |"x";   // TO BE DELETED
        ppn:                 string|"in"|"out";
        setelahPpn:          string|"in"|"out";
        pembulatan:          string|"in"|"out";
        nilaiAkhir:          string|"in"|"out";
        verGudang:           string|"in"|"out";
        verTerima:           string|"?" |"x";   // "ver_terima"; // TO BE DELETED
        verAkuntansi:        string|"?" |"x";   // "ver_akuntansi"; // TO BE DELETED
        daftarDetail:        Array<DetailReturFields>;
    }

    export interface DetailReturFields {
        idRefKatalog:      string|"in"|"out";
        idKatalog:         string|"in"|"out";
        noUrut:            string|"in"|"out";
        noBatch:           string|"in"|"out";
        tanggalKadaluarsa: string|"in"|"out";
        idPabrik:          string|"in"|"out";
        idKemasanDepo:     string|"in"|"out";
        isiKemasan:        string|"in"|"out";
        kemasan:           string|"in"|"out";
        hargaKemasan:      string|"in"|"out";
        diskonItem:        string|"in"|"out";
        namaSediaan:       string|"in"|"x";
        namaPabrik:        string|"in"|"x";
        idKemasan:         string|"in"|"out";
        jumlahKemasan:     string|"in"|"out";
        jumlahItem:        string|"in"|"out";
        hargaItem:         string|"in"|"out";
        hargaTotal:        string|"?" |"x";
        diskonHarga:       string|"in"|"out";
        hargaAkhir:        string|"?" |"x";
        jumlahTerima:      string|"in"|"x";
        jumlahRetur:       string|"in"|"x";
    }

    export interface Ajax1Fields {
        noUrut:            string|"in"|"?"; // "no_urut";
        idKatalog:         string|"in"|"?"; // "id_katalog";
        jumlahItemTotal:   string|"in"|"?"; // "jumlah_itemtot";
        jumlahRetur:       string|"in"|"?"; // "jumlah_return";
        kemasanKat:        string|"in"|"?"; // "kemasankat";
        isiKemasanKat:     string|"in"|"?"; // "isi_kemasankat";
        idKemasanDepoKat:  string|"in"|"?"; // "id_kemasandepokat";
        idKemasanKat:      string|"in"|"?"; // "id_kemasankat";
        satuanKat:         string|"in"|"?"; // "satuankat";
        satuanJualKat:     string|"in"|"?"; // "satuanjualkat";
        hargaKemasanKat:   string|"in"|"?"; // "harga_kemasankat";
        isiKemasan:        string|"in"|"?"; // "isi_kemasan";
        idKemasanDepo:     string|"in"|"?"; // "id_kemasandepo";
        idKemasan:         string|"in"|"?"; // "id_kemasan";
        satuan:            string|"in"|"?"; // "satuan";
        satuanJual:        string|"in"|"?"; // "satuanjual";
        hargaItem:         string|"in"|"?"; // "harga_item";
        hargaKemasan:      string|"in"|"?"; // "harga_kemasan";
        diskonItem:        string|"in"|"?"; // "diskon_item";
        kemasan:           string|"in"|"?"; // "kemasan";
        jumlahItem:        string|"in"|"?"; // "jumlah_item";
        kodeRefTerima:     string|"in"|"?"; // "kode_refftrm";
        kodeRefPl:         string|"in"|"?"; // "kode_reffpl";
        kodeRefPo:         string|"in"|"?"; // "kode_reffpo";
        idRefKatalog:      string|"in"|"?"; // "id_reffkatalog";
        noBatch:           string|"in"|"?"; // "no_batch";
        tanggalKadaluarsa: string|"in"|"?"; // "tgl_expired";
        idPabrik:          string|"in"|"?"; // "id_pabrik";
        namaSediaan:       string|"in"|"?"; // "nama_sediaan";
        namaPabrik:        string|"in"|"?"; // "nama_pabrik";
    }

    export interface RefPenerimaanFields {
        bulanAwalAnggaran:    string|"in"|"??"; // "blnawal_anggaran";
        bulanAkhirAnggaran:   string|"in"|"??"; // "blnakhir_anggaran";
        tahunAnggaran:        string|"in"|"??"; // "thn_anggaran";
        noSpk:                string|"in"|"??"; // "no_spk";
        noDokumen:            string|"in"|"??"; // "no_doc";
        namaPemasok:          string|"in"|"??"; // "nama_pbf";
        subjenisAnggaran:     string|"in"|"??"; // "subjenis_anggaran";
        action:               string|"x" |"??"; // "act";                   // TO BE DELETED
        verGudang:            string|"in"|"??"; // "ver_gudang";
        idGudangPenyimpanan:  string|"in"|"??"; // "id_gudangpenyimpanan";
        tipeDokumen:          string|"in"|"??"; // "tipe_doc";
        idJenisAnggaran:      string|"in"|"??"; // "id_jenisanggaran";
        idSumberDana:         string|"in"|"??"; // "id_sumberdana";
        idJenisHarga:         string|"in"|"??"; // "id_jenisharga";
        idCaraBayar:          string|"in"|"??"; // "id_carabayar";
        ppn:                  string|"in"|"??"; // "ppn";
        dari:                 string|"?" |"??"; // "from";                  // MISSING in db
        idPemasok:            string|"in"|"??"; // "id_pbf";
        objectPemasok:        PemasokFields;
        kodePemasok:          string|"in"|"??"; // "kode_pbf";
        kodeRefPo:            string|"in"|"??"; // "kode_reffpo";
        objectRefPo:          RefPoFields;
        kodeRefPl:            string|"in"|"??"; // "kode_reffpl";
        objectRefPl:          RefPlFields;
        noPo:                 string|"in"|"??"; // "no_po";
        jenisHarga:           string|"in"|"??"; // "jenis_harga";
        bulanAwalAnggaranPo:  string|"in"|"??"; // "blnawal_anggaranpo";
        bulanAkhirAnggaranPo: string|"in"|"??"; // "blnakhir_anggaranpo";
        tahunAnggaranPo:      string|"in"|"??"; // "thn_anggaranpo";
        tipeSpk:              string|"?" |"??"; // "tipe_spk";              // MISSING in db
        bulanAwalAnggaranPl:  string|"in"|"??"; // "blnawal_anggaranpl";
        bulanAkhirAnggaranPl: string|"in"|"??"; // "blnakhir_anggaranpl";
        tahunAnggaranPl:      string|"in"|"??"; // "thn_anggaranpl";
        kode:                 string|"in"|"??"; // "kode";
    }

    export interface RefPoFields {
        kode:      "kode";
        noDokumen: "no_doc";
    }

    export interface RefPlFields {
        kode:      "kode";
        noDokumen: "no_doc";
    }

    export interface PemasokFields {
        id:                  "id";
        bulanAwalAnggaran:   "blnawal_anggaran";
        bulanAkhirAnggaran:  "blnakhir_anggaran";
        tahunAnggaran:       "thn_anggaran";
        noDokumen:           "no_doc";
        kodePemasok:         "kode_pbf";
        namaPemasok:         "nama_pbf";
        subjenisAnggaran:    "subjenis_anggaran";
        action:              "act"|number;    // TODO: js: uncategorized: fix this
        kode:                "kode";
        noSpk:               "no_spk";
        tipeDokumen:         "tipe_doc";
        tipeSpk:             "tipe_spk";
        kodeRefPl:           "kode_reffpl";
        kodeRefPo:           "kode_reffpo";
        noPo:                "no_po";
        ppn:                 "ppn";
        jenisHarga:          "jenis_harga";
        idGudangPenyimpanan: "id_gudangpenyimpanan";
        idJenisAnggaran:     "id_jenisanggaran";
        idSumberDana:        "id_sumberdana";
        idSubsumberDana:     "id_subsumberdana";
        idJenisHarga:        "id_jenisharga";
        idCaraBayar:         "id_carabayar";
        objectRefPenerimaan: RefPenerimaanFields;
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
                widthColumn: {
                    button_1: {class: ".pullBtn",   text: tlm.stringRegistry._<?= $h("Tarik") ?>,  title: tlm.stringRegistry._<?= $h("Tarik Item Barang") ?>}, // $disabled
                    button_2: {class: ".deleteBtn", text: tlm.stringRegistry._<?= $h("Delete") ?>, title: tlm.stringRegistry._<?= $h("Tekan tombol ini atau keyBoard Delete/Del untuk menghapus fokus/selected row") ?>} // $disabled
                }
            },
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Gudang Penyimpanan") ?>,
                        select: {class: ".idGudangPenyimpananFld", name: "idGudangPenyimpanan"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen Retur") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Kode PL") ?>,
                        select: {class: ".kodeRefPlFld", name: "kodeRefPl", options: []}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Kode PO") ?>,
                        select: {class: ".kodeRefPoFld", name: "kodeRefPo", options: []}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Kode Penerimaan") ?>,
                        select: {class: ".kodeRefPenerimaanFld", name: "kodeRefPenerimaan", options: []}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        input: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_26: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_27: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_28: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_29: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        staticText: {class: ".setelahPpnStc"}
                    },
                    formGroup_30: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_31: {
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
                            td_1:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6:  {colspan: 5, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                            td_7:  {colspan: 3, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8:  {colspan: 2, text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_9:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_10: {colspan: 3, text: tlm.stringRegistry._<?= $h("Realisasi") ?>}
                        },
                        tr_2: {
                            td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {text: tlm.stringRegistry._<?= $h("Batch") ?>},
                            td_3:  {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            td_4:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_6:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_7:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_8:  {text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_9:  {text: tlm.stringRegistry._<?= $h("(%)") ?>},
                            td_10: {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                            td_11: {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                            td_12: {text: tlm.stringRegistry._<?= $h("Retur") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_2: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_3: {class: ".noUrutFld", name: "noUrut[]"},
                                hidden_4: {class: ".noBatchFld", name: "noBatch[]"},
                                hidden_5: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"},
                                hidden_6: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_7: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                                hidden_8: {class: ".isiKemasanFld", name: "isiKemasan[]"},
                                hidden_9: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_10: {class: ".hargaKemasanFld", name: "hargaKemasan[]"},
                                hidden_11: {class: ".diskonItemFld", name: "diskonItem[]"},
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
                            td_5: {class: ".isiKemasanStc"},
                            td_6: {class: ".noUrutStc"},
                            td_7: {class: ".noBatchStc"},
                            td_8: {
                                input: {class: ".tanggalKadaluarsaFld"}
                            },
                            td_9: {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]"}
                            },
                            td_10: {class: ".jumlahItemStc"},
                            td_11: {class: ".hargaKemasanStc"},
                            td_12: {class: ".hargaItemStc"},
                            td_13: {class: ".hargaTotalStc"},
                            td_14: {class: ".diskonItemStc"},
                            td_15: {class: ".diskonHargaStc"},
                            td_16: {class: ".hargaAkhirStc"},
                            td_17: {class: ".jumlahTerimaStc"},
                            td_18: {class: ".jumlahReturStc"}
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
        const {toSystemNumber: sysNum, toCurrency: currency, numToShortMonthName: nToS, nowVal} = tlm;
        const {toUserFloat: userFloat, toUserInt: userInt, stringRegistry: str, preferInt} = tlm;
        const userName = tlm.user.nama;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */ const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLInputElement} */  const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLDivElement} */    const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */    const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */    const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLDivElement} */    const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLInputElement} */  const ppnFld = divElm.querySelector(".ppnFld");
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

        tlm.app.registerSelect("_<?= $penyimpananSelect ?>", idGudangPenyimpananFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idGudangPenyimpananFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld, tahunAnggaranFld);
        this._selects.push(idJenisAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const returFarmasiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".returFarmasiFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.ReturFarmasiUi.Form.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                idJenisAnggaranWgt.value = data.idJenisAnggaran ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                idSumberDanaWgt.value = data.idSumberDana ?? "";
                idJenisHargaWgt.value = data.idJenisHarga ?? "";
                idCaraBayarWgt.value = data.idCaraBayar ?? "";
                keteranganFld.value = data.keterangan ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";
                verGudangFld.checked = !!data.verGudang ?? "";
                verTerimaFld.checked = !!data.verTerima ?? "";
                verAkuntansiFld.checked = !!data.verAkuntansi ?? "";

                if (data.objectRefPo) {
                    kodeRefPoWgt.addOption(data.objectRefPo);
                    kodeRefPoWgt.value = data.kodeRefPo ?? "";
                }

                if (data.objectRefPl) {
                    kodeRefPlWgt.addOption(data.objectRefPl);
                    kodeRefPlWgt.value = data.kodeRefPl ?? "";
                }

                if (data.objectRefPenerimaan) {
                    kodeRefPenerimaanWgt.addOption(data.objectRefPenerimaan);
                    kodeRefPenerimaanWgt.value = data.kodeRefPenerimaan ?? "";
                }

                if (data.objectPemasok) {
                    idPemasokWgt.addOption(data.objectPemasok);
                    idPemasokWgt.value = data.idPemasok;
                }

                if (data.verGudang == "1") {
                    kodeRefPenerimaanWgt.disable();
                    divElm.querySelector("[name=tarik]").disabled = true;
                }
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this.load({});
                    actionFld.value = "add";
                },
                edit(data) {
                    this.load(data);
                    actionFld.value = "edit";
                },
                verifikasiPenerimaan(data) {
                    this.load(data);
                    actionFld.value = "ver_terima";
                },
                verifikasiAkuntansi(data) {
                    this.load(data);
                    actionFld.value = "ver_akuntansi";
                },
            },
            onInit() {
                this.loadProfile("edit");
            },
            onBeforeSubmit() {
                let c = false;
                if (itemWgt.querySelector(".jumlahItemFld").filter("[value=0]").length == itemWgt.querySelectorAll("tr").length) {
                    c = confirm(str._<?= $h("Daftar barang dengan jumlah 0 akan disimpan dan diperlakukan sebagai kontrak harga. Apakah Anda yakin ingin menyimpan?") ?>);
                }

                if (c) {
                    idPemasokWgt.enable();
                    kodeRefPoWgt.enable();
                    kodeRefPlWgt.enable();
                    idJenisAnggaranWgt.enable();
                    idSumberDanaWgt.enable();
                    idJenisHargaWgt.enable();
                    idCaraBayarWgt.enable();
                    kodeFld.disabled = false;
                    actionFld.disabled = false;
                }
                return c;
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        /** @param {HTMLTableRowElement} trElm */
        function hitungSubTotal(trElm) {
            const fields = trElm.fields;
            const idPar = fields.noUrutStc.dataset.par;
            const jumlahKemasan = sysNum(fields.jumlahKemasanFld.innerHTML);
            const hargaKemasan = sysNum(fields.hargaKemasanStc.innerHTML);
            const diskonItem = sysNum(fields.diskonItemStc.innerHTML);
            const isiKemasan = sysNum(itemWgt.querySelector("tr#" + idPar).fields.isiKemasanStc.innerHTML);
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

        const idJenisAnggaranWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idJenisAnggaranFld"),
            maxItems: 1,
            valueField: "id",
            labelField: "subjenis_anggaran"
        });

        /** @see his.FatmaPharmacy.views.ReturFarmasiUi.Form.RefPlFields */
        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPlFld"),
            maxItems: 1,
            valueField: "kode",
            labelField: "no_doc"
        });

        /** @see his.FatmaPharmacy.views.ReturFarmasiUi.Form.RefPoFields */
        const kodeRefPoWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPoFld"),
            maxItems: 1,
            valueField: "kode",
            labelField: "no_doc"
        });

        const idSumberDanaWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idSumberDanaFld"),
            maxItems: 1,
            valueField: "id",
            labelField: "sumber_dana"
        });

        const kodeRefPenerimaanWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPenerimaanFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.ReturFarmasiUi.Form.RefPenerimaanFields} data
             */
            assignPairs(formElm, data) {
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                // TODO: js: uncategorized: finish this
                // "[name=tipe_doc]": "tipe_doc" ?? ""
            },
            /** @param {his.FatmaPharmacy.views.ReturFarmasiUi.Form.RefPenerimaanFields} item */
            optionRenderer(item) {
                const awal = item.bulanAwalAnggaran;
                const akhir = item.bulanAkhirAnggaran;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + item.tahunAnggaran;

                return `
                    <div class="option">
                        <span class="name">${item.noSpk} (${item.noDokumen})</span><br/>
                        <span class="description">
                            Pemasok: ${item.namaPemasok}<br/>
                            Mata Anggaran: ${item.subjenisAnggaran}<br/>
                            Bulan Anggaran: ${anggaran}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.ReturFarmasiUi.Form.RefPenerimaanFields} item */
            itemRenderer(item) {return `<div class="item">${item.noSpk} (${item.noDokumen})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $penerimaanUrl ?>",
                    data: {noDokumen: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ReturFarmasiUi.Form.RefPenerimaanFields} */
                const obj = this.options[value];

                // set nilai Penerimaan
                if (obj.action) return;

                if (obj.verGudang != "1") return alert(str._<?= $h("No. penerimaan ini tidak bisa diretur karena belum verifikasi gudang.") ?>);

                idJenisAnggaranWgt.value = obj.idJenisAnggaran;
                idSumberDanaWgt.value = obj.idSumberDana;
                // $sub.fieldWidget.setValue(obj.id_subsumberdana);
                idJenisHargaWgt.value = obj.idJenisHarga;
                idCaraBayarWgt.value = obj.idCaraBayar;

                ppnFld.checked = (obj.ppn == 10);

                if (!obj.dari && obj.objectPemasok) {
                    idPemasokWgt.addOption(obj.objectPemasok);
                    idPemasokWgt.value = obj.idPemasok;
                }

                if (obj.kodeRefPo != "-" && obj.objectRefPo) {
                    kodeRefPoWgt.addOption(obj.objectRefPo);
                    kodeRefPoWgt.value = obj.kodeRefPo;
                }

                if (obj.kodeRefPl != "-" && obj.objectRefPl) {
                    kodeRefPlWgt.addOption(obj.objectRefPl);
                    kodeRefPlWgt.value = obj.kodeRefPl;
                }

                // select item
                selectItem(obj.kode);
            },
            onItemRemove() {
                if (!confirm(str._<?= $h('Menghapus "Referensi Pemasok" akan mereset data dan menghapus semua barang terkait dengan no. PL tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

                idPemasokWgt.clearOptions();
                idPemasokWgt.clearCache();

                kodeRefPlWgt.clearOptions();
                kodeRefPlWgt.clearCache();

                kodeRefPoWgt.clearCache();
                kodeRefPoWgt.clearOptions();

                idJenisAnggaranWgt.value = "";
                idSumberDanaWgt.value = "";
                // $sub.fieldWidget.setValue("");
                idJenisHargaWgt.value = "";
                idCaraBayarWgt.value = "";

                divElm.querySelector(".idGudangPenyimpananFld").value = 59;

                itemWgt.reset();
                sortNumber();
                hitungTotal();
            }
        });

        const idJenisHargaWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idJenisHargaFld"),
            maxItems: 1,
            valueField: "id",
            labelField: "jenis_harga"
        });

        const idCaraBayarWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idCaraBayarFld"),
            maxItems: 1,
            valueField: "id",
            labelField: "cara_bayar"
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["namaPemasok"],
            /** @param {his.FatmaPharmacy.views.ReturFarmasiUi.Form.PemasokFields} item */
            optionRenderer(item) {
                const awal = item.bulanAwalAnggaran;
                const akhir = item.bulanAkhirAnggaran;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + item.tahunAnggaran;

                return `
                    <div class="option">
                        <span class="name">${item.noDokumen} (${item.kodePemasok}) - ${item.namaPemasok}</span><br/>
                        <span class="description">
                            Mata Anggaran: ${item.subjenisAnggaran}<br/>
                            Bulan Anggaran: ${anggaran}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.ReturFarmasiUi.Form.PemasokFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.kodePemasok}) - ${item.namaPemasok}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pemasokUrl ?>",
                    data: {namaPemasok: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ReturFarmasiUi.Form.PemasokFields} */
                const obj = this.options[value];

                if (obj.action) return;

                if (obj.objectRefPenerimaan) {
                    kodeRefPenerimaanWgt.addOption(obj.objectRefPenerimaan);
                    kodeRefPenerimaanWgt.value = obj.objectRefPenerimaan.kode;
                }
            },
            onItemRemove() {
                if (!confirm(str._<?= $h('Menghapus "Ref. Pemasok" akan mereset data dan menghapus semua barang terkait dengan no. PL tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

                kodeRefPlWgt.clearOptions();
                kodeRefPlWgt.clearCache();

                kodeRefPoWgt.clearOptions();
                kodeRefPoWgt.clearCache();

                kodeRefPenerimaanWgt.clearOptions();
                kodeRefPenerimaanWgt.clearCache();

                idJenisAnggaranWgt.value = "";
                idSumberDanaWgt.value = "";
                // TODO: js: missing var: $sub
                $sub.fieldWidget.setValue("");
                idJenisHargaWgt.value = "";
                idCaraBayarWgt.value = "";

                idGudangPenyimpananFld.value = "59";

                itemWgt.reset();
                sortNumber();
                hitungTotal();
            }
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.ReturFarmasiUi.Form.DetailReturFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogFld.value = data.idKatalog ?? "";
                fields.noUrutFld.value = data.noUrut ?? "";
                fields.noBatchFld.value = data.noBatch ?? "";
                fields.tanggalKadaluarsaFld.value = data.tanggalKadaluarsa ?? "";
                fields.idPabrikFld.value = data.idPabrik ?? "";
                fields.idKemasanDepoFld.value = data.idKemasanDepo ?? "";
                fields.isiKemasanFld.value = data.isiKemasan ?? "";
                fields.kemasanFld.value = data.kemasan ?? "";
                fields.hargaKemasanFld.value = data.hargaKemasan ?? "";
                fields.diskonItemFld.value = data.diskonItem ?? "";
                fields.namaSediaanStc.innerHTML = data.namaSediaan ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.isiKemasanStc.innerHTML = data.isiKemasan ?? "";
                fields.noUrutStc.innerHTML = data.noUrut ?? "";
                fields.noBatchStc.innerHTML = data.noBatch ?? "";
                fields.tanggalKadaluarsaWgt.value = data.tanggalKadaluarsa ?? "";
                fields.jumlahKemasanFld.value = data.jumlahKemasan ?? "";
                fields.jumlahItemStc.innerHTML = data.jumlahItem ?? "";
                fields.hargaKemasanStc.innerHTML = data.hargaKemasan ?? "";
                fields.hargaItemStc.innerHTML = data.hargaItem ?? "";
                fields.hargaTotalStc.innerHTML = data.hargaTotal ?? "";
                fields.diskonItemStc.innerHTML = data.diskonItem ?? "";
                fields.diskonHargaStc.innerHTML = data.diskonHarga ?? "";
                fields.hargaAkhirStc.innerHTML = data.hargaAkhir ?? "";
                fields.jumlahTerimaStc.innerHTML = data.jumlahTerima ?? "";
                fields.jumlahReturStc.innerHTML = data.jumlahRetur ?? "";
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

                const jumlahKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.intNumberSetting
                });

                // TODO: js: uncategorized: there is 2 form of "kadaluarsa": static and field. confirm to very beginning commit.
                const tanggalKadaluarsaWgt = new spl.DateTimeWidget({
                    element: trElm.querySelector(".tanggalKadaluarsaFld"),
                    // TODO: js: uncategorized: add "already expired", and "less than 2 years" rules
                    errorRules: [{required: true}],
                    ...tlm.dateWidgetSetting
                });

                trElm.fields = {
                    noBatchWgt,
                    jumlahKemasanWgt,
                    tanggalKadaluarsaWgt,
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    tanggalKadaluarsaFld: trElm.querySelector(".tanggalKadaluarsaFld"),
                    idPabrikFld: trElm.querySelector(".idPabrikFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    isiKemasanFld: trElm.querySelector(".isiKemasanFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    hargaKemasanFld: trElm.querySelector(".hargaKemasanFld"),
                    diskonItemFld: trElm.querySelector(".diskonItemFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    isiKemasanStc: trElm.querySelector(".isiKemasanStc"),
                    noUrutStc: trElm.querySelector(".noUrutStc"),
                    noBatchStc: trElm.querySelector(".noBatchStc"),
                    jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                    hargaKemasanStc: trElm.querySelector(".hargaKemasanStc"),
                    hargaItemStc: trElm.querySelector(".hargaItemStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    diskonItemStc: trElm.querySelector(".diskonItemStc"),
                    diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                    hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                    jumlahTerimaStc: trElm.querySelector(".jumlahTerimaStc"),
                    jumlahReturStc: trElm.querySelector(".jumlahReturStc"),
                    stokBtn: trElm.querySelector(".stokBtn"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.noBatchWgt.destroy();
                fields.jumlahKemasanWgt.destroy();
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
            const hargaKemasanOpt = sysNum(fields.idKemasanFld.selectedOptions[0].value);
            const hargaKemasan = sysNum(hargaKemasanOpt.dataset.hg);
            const diskonItem = sysNum(fields.diskonItemStc.innerHTML);
            const maksimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jimax);

            fields.kemasanFld.value = hargaKemasanOpt.innerHTML;
            fields.idKemasanDepoFld.value = hargaKemasanOpt.getAttribute("ids");

            const isiKemasan = sysNum(hargaKemasanOpt.dataset.is);
            fields.isiKemasanFld.value = isiKemasan;

            let totalJumlahItem = 0;
            divElm.querySelectorAll(`.noUrutStc[data-par="${trElm.id}"]`).forEach(item => {
                const fields = closest(item, "tr").fields;
                let jumlahItem = sysNum(fields.jumlahItemStc.innerHTML);
                let jumlahKemasan = Math.floor(jumlahItem / isiKemasan);

                jumlahItem = jumlahKemasan * isiKemasan;

                if (totalJumlahItem + jumlahItem > maksimumJumlahItem) {
                    jumlahItem = 0;
                    jumlahKemasan = 0;
                }

                totalJumlahItem += jumlahItem;

                const hargaTotal = jumlahKemasan * hargaKemasan;
                const diskonHarga = hargaTotal * diskonItem / 100;
                const hargaAkhir = hargaTotal - diskonHarga;

                fields.jumlahItemStc.innerHTML = userFloat(jumlahItem);
                fields.jumlahKemasanWgt.value = userInt(jumlahKemasan);
                fields.isiKemasanStc.innerHTML = userFloat(isiKemasan);
                fields.hargaKemasanStc.innerHTML = currency(hargaKemasan);
                fields.diskonHargaStc.innerHTML = currency(diskonHarga);
                fields.hargaTotalStc.innerHTML = currency(hargaTotal);
                fields.hargaAkhirStc.innerHTML = currency(hargaAkhir);
            });
            hitungTotal();
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            const trElm = closest(jumlahKemasanFld, "tr");
            const fields = trElm.fields;
            const jumlahKemasan = sysNum(jumlahKemasanFld.value);

            let totalJumlahItem = 0;
            divElm.querySelectorAll(`.noUrutStc[data-par="${fields.noUrutStc.dataset.par}"]`).forEach(item => {
                totalJumlahItem += sysNum(closest(item, "tr").fields.jumlahItemStc.innerHTML);
            });

            const maksimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jiMax);
            if (totalJumlahItem > maksimumJumlahItem) {
                alert(`
                    Melebihi batas maximum Penerimaan.
                    Jumlah maksimum yang bisa diinputkan adalah ${maksimumJumlahItem},
                    Silahkan cek Penerimaan lainnya terlebih dahulu!`
                );
                fields.jumlahKemasanWgt.value = userInt(jumlahKemasan);
            }
            hitungSubTotal(trElm);
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.ReturFarmasiUi.Form.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        // JUNK -----------------------------------------------------

        function sortNumber() {
            divElm.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
        }

        function selectItem(kodeRef) {
            const kode = (actionFld.value == "edit") ? kodeFld.value : undefined;
            $.post({
                url: "<?= $detailReturUrl ?>",
                data: {kodeRefNot: kode, kodeRef},
                /** @param {his.FatmaPharmacy.views.ReturFarmasiUi.Form.Ajax1Fields[]} data */
                success(data) {
                    if (!data.length) return alert(str._<?= $h("Purchase order kosong. Silahkan hubungi ULP.") ?>);

                    data.forEach(obj => {
                        let {noUrut, idKatalog, hargaKemasan, isiKemasan, jumlahItem, tanggalKadaluarsa, diskonItem} = obj;
                        const {idKemasanDepo, idKemasanKat, idKemasan, satuanKat, isiKemasanKat, hargaItem, jumlahRetur} = obj;
                        let {kemasanKat, satuanJualKat, kemasan, jumlahItemTotal, satuan, satuanJual, noBatch} = obj;
                        const {idRefKatalog, idPabrik, namaSediaan, namaPabrik, jumlahKemasan, idKemasanDepoKat} = obj;

                        if (noUrut == "1" && !itemWgt.querySelector("tr#" + idKatalog)) {
                            const hiK = hargaKemasan / isiKemasan;     // jika kemasan kecil / satuan merupakan kemasan yang direncanakan

                            if (idKemasanDepoKat == idKemasanDepo && idKemasanKat == idKemasan && isiKemasan == 1) {
                                kemasan = satuanKat;
                            }

                            const hargaKemasanKat = (isiKemasan == isiKemasanKat) ? hargaKemasan : hargaItem * isiKemasanKat;

                            if (isiKemasanKat != 1 && idKemasanKat != idKemasanDepoKat) {
                                if (!kemasanKat) {
                                    kemasanKat = satuanJualKat + " " + isiKemasanKat + " " + satuanKat;
                                }

                                if (idKemasanDepoKat == idKemasanDepo && idKemasanKat == idKemasan && isiKemasanKat == isiKemasan) {
                                    kemasan = kemasanKat;
                                }
                            }

                            // option_2: jika kemasan perencanaan adalah kemasan besar
                            // option_3: jika kemasan nya sudah berbeda total dengan kemasan di katalog
                            const options = {
                                option_1: {value: idKemasanDepoKat, "data-is": 1,             ids: idKemasanDepoKat, sat: satuanKat, kem: satuanKat,     "data-hg": hiK,             text: satuanKat},
                                option_2: {value: idKemasanKat,     "data-is": isiKemasanKat, ids: idKemasanDepoKat, sat: satuanKat, kem: satuanJualKat, "data-hg": hargaKemasanKat, text: kemasanKat},
                                option_3: {value: idKemasan,        "data-is": isiKemasan,    ids: idKemasanDepo,    sat: satuan,    kem: satuanJual,    "data-hg": hargaKemasan,    text: kemasan},
                            };
                            (isiKemasanKat == 1 || idKemasanKat == idKemasanDepoKat) ? delete options.option_2 : null;

                            let jumlahKemasan = Math.floor(jumlahItem / isiKemasan);

                            if (jumlahItem < 0) {
                                alert(`Periksa kembali penerimaan! Jumlah barang yang akan direturn ${jumlahRetur}, melebihi jumlah yang diterima ${jumlahItemTotal}!`);
                                jumlahItem = 0;
                                jumlahKemasan = 0;
                            }

                            const hargaTotal = jumlahKemasan * hargaKemasan;
                            const diskonHarga = hargaTotal * diskonItem / 100;

                            const lastTrElm = divElm.querySelector(".tr-last");
                            const trStr = drawTr("tbody", {
                                class: "tr-data",
                                id: idKatalog,
                                td_1: {
                                    hidden_1: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idRefKatalog},
                                    hidden_2: {class: ".kemasanFld", name: "kemasan[]", value: kemasan},
                                    hidden_3: {class: ".noUrutFld", name: "noUrut[]", value: noUrut},
                                    hidden_4: {class: ".noBatchFld", name: "noBatch[]", value: noBatch},
                                    hidden_5: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]", value: tanggalKadaluarsa},
                                    hidden_6: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                                    hidden_7: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                                    hidden_8: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                                    hidden_9: {class: ".isiKemasanFld", name: "isiKemasan[]", value: isiKemasan},
                                    hidden_10: {class: "DIFF-WITH-SPLBULKINPUT", name: "jumlahItem[]", value: jumlahItem},
                                    hidden_11: {class: "DIFF-WITH-SPLBULKINPUT", name: "hargaItem[]", value: hargaItem},
                                    hidden_12: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: hargaKemasan},
                                    hidden_13: {class: ".diskonItemFld", name: "diskonItem[]", value: diskonItem},
                                    hidden_14: {class: "DIFF-WITH-SPLBULKINPUT", name: "diskon_harga[]", value: diskonHarga},
                                    staticText: {class: ".no"},
                                },
                                td_2: {
                                    button: {class: ".stokBtn", text: str._<?= $h("Stok") ?>},
                                    staticText: {class: ".namaSediaanStc", text: namaSediaan},
                                },
                                td_3: {class: ".namaPabrikStc", text: namaPabrik},
                                td_4: {
                                    select: {class: ".idKemasanFld", name: "idKemasan[]", ...options}
                                },
                                td_5: {class: ".isiKemasanStc", text: userInt(isiKemasan)},
                                td_6: {class: ".noUrutStc", "data-par": idKatalog, no_urut: noUrut, text: noUrut},
                                td_7: {class: ".noBatchStc", text: noBatch},
                                td_8: {
                                    input: {class: ".tanggalKadaluarsaFld", value: tanggalKadaluarsa}
                                },
                                td_9: {
                                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: jumlahKemasan}
                                },
                                td_10: {class: ".jumlahItemStc"},
                                td_11: {class: ".hargaKemasanStc", text: userFloat(hargaKemasan)},
                                td_12: {class: ".hargaItemStc"},
                                td_13: {class: ".hargaTotalStc"},
                                td_14: {class: ".diskonItemStc", text: userInt(diskonItem)},
                                td_15: {class: ".diskonHargaStc"},
                                td_16: {class: ".hargaAkhirStc"},
                                td_17: {class: ".jumlahTerimaStc", text: userInt(jumlahItemTotal)},
                                td_18: {class: ".jumlahReturStc", text: userInt(jumlahRetur)},
                            });
                            lastTrElm.insertAdjacentHTML("beforebegin", trStr);
                            hitungSubTotal(/** @type {HTMLTableRowElement} */ lastTrElm.previousSibling);
                        }

                        if (noUrut != "1") {
                            const trStr = drawTr("tbody", {
                                class: "tr-data detail",
                                td_1: {
                                    colspan: 5,
                                    hidden_1: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                                    hidden_2: {class: "DIFF-WITH-SPLBULKINPUT", name: "jumlahItem[]", value: jumlahItem},
                                    hidden_3: {class: ".noBatchFld", name: "noBatch[]", value: noBatch},
                                    hidden_4: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]", value: tanggalKadaluarsa},
                                    hidden_5: {class: ".noUrutFld", name: "noUrut[]", value: noUrut},
                                    hidden_6: {value: isiKemasan},
                                },
                                td_2: {class: ".noUrutStc", "data-par": idKatalog, no_urut: noUrut, text: noUrut},
                                td_3: {class: ".noBatchStc", text: noBatch},
                                td_4: {
                                    input: {class: ".tanggalKadaluarsaFld", text: tanggalKadaluarsa}
                                },
                                td_5: {
                                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: jumlahKemasan}
                                },
                                td_6: {class: ".jumlahItemStc"},
                                td_7: {class: ".hargaKemasanStc", text: userFloat(hargaKemasan)},
                                td_8: {class: ".hargaItemStc"},
                                td_9: {class: ".hargaTotalStc"},
                                td_10: {class: ".diskonItemStc", text: userInt(diskonItem)},
                                td_11: {class: ".diskonHargaStc"},
                                td_12: {class: ".hargaAkhirStc"},
                                td_13: {colspan: 3},
                            });
                            closest(divElm.querySelector(`.noUrutStc[data-par="${idKatalog}"]:last-child`), "tr").insertAdjacentHTML("afterend", trStr);
                            hitungSubTotal(closest(divElm.querySelector(`.noUrutStc[data-par="${idKatalog}"]:last-child`), "tr"));
                        }
                    });

                    sortNumber();
                    hitungTotal();
                    itemWgt.querySelector("tbody tr:last-child").fields.jumlahKemasanFld.dispatchEvent(new Event("focus"));
                }
            });
        }

        ppnFld.addEventListener("click", () => {
            const ppn = ppnFld.checked ? 10 : 0;
            const totalSetelahDiskon = sysNum(divElm.querySelector("#total_stlhdiskon").value);
            const totalPpn = ppn * totalSetelahDiskon / 100;
            const subtotal = totalSetelahDiskon + totalPpn;
            const totalAkhir = Math.floor(subtotal);
            const totalPembulatan = totalAkhir - subtotal;

            divElm.querySelector("[name=nilai_ppn]").value = currency(totalPpn);
            divElm.querySelector(".setelahPpn").value = currency(subtotal);
            divElm.querySelector("[name=nilai_pembulatan]").value = currency(totalPembulatan);
            divElm.querySelector("[name=nilai_akhir]").value = currency(totalAkhir);
        });

        divElm.querySelector(".pembulatan").addEventListener("click", (event) => {
            const st = sysNum(divElm.querySelector(".setelahPpn").value);
            const isChecked = event.target.checked;
            const totalAkhir = isChecked ? Math.floor(st) : st;
            const pembulatan = isChecked ? (totalAkhir - st) : 0;

            divElm.querySelector("[name=nilai_pembulatan]").value = currency(pembulatan);
            divElm.querySelector("[name=nilai_akhir]").value = currency(totalAkhir);
        });

        divElm.querySelector("[name=nilai_pembulatan]").addEventListener("focusout", (event) => {
            const ta = sysNum(divElm.querySelector(".setelahPpn").value) + sysNum(event.target.value);
            divElm.querySelector("[name=nilai_akhir]").value = currency(ta);
        });

        divElm.querySelector("[name=tarik]").addEventListener("change", (event) => {
            const val = event.target.value;

            if (val == "pl") {
                kodeRefPoWgt.disable();
                idPemasokWgt.disable();
                kodeRefPenerimaanWgt.disable();

                kodeRefPlWgt.enable();
                kodeRefPlWgt.dispatchEvent(new Event("focus"));
            } else if (val == "po") {
                kodeRefPlWgt.disable();
                idPemasokWgt.disable();
                kodeRefPenerimaanWgt.disable();

                kodeRefPoWgt.enable();
                kodeRefPoWgt.dispatchEvent(new Event("focus"));
            } else if (val == "trm") {
                kodeRefPlWgt.disable();
                idPemasokWgt.disable();
                kodeRefPoWgt.disable();

                kodeRefPenerimaanWgt.enable();
                kodeRefPenerimaanWgt.dispatchEvent(new Event("focus"));
            } else {
                kodeRefPlWgt.disable();
                kodeRefPoWgt.disable();
                kodeRefPenerimaanWgt.disable();

                idPemasokWgt.enable();
                idPemasokWgt.dispatchEvent(new Event("focus"));
            }
        });

        divElm.querySelector(".deleteBtn").addEventListener("click", () => {
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin menghapus katalog yang di-checklist dan batch / no seri nya?") ?>)) return;

            divElm.querySelectorAll(".ck-one:checked").forEach(item => {
                const trElm = closest(item, "tr");
                const noBatchElm = divElm.querySelector(`.noUrutStc[data-par="${trElm.id}"]`);
                noBatchElm.length ? closest(noBatchElm, "tr").remove() : trElm.remove();
            });
            sortNumber();
            hitungTotal();
        });

        idPemasokWgt.disable();

        divElm.querySelector(".pullBtn").addEventListener("click", () => {
            if (!confirm(str._<?= $h("Menarik kembali daftar data akan menghapus daftar barang yang sudah ada. Apakah Anda yakin?") ?>)) return;

            itemWgt.reset();
            hitungTotal();
            const kodeRef = kodeRefPenerimaanWgt.value;

            // jika tipe PL adalah Surat Pemesanan
            kodeRef ? selectItem(kodeRef) : alert(str._<?= $h("Kode referensi Penerimaan tidak boleh kosong.") ?>);
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
        this._widgets.push(returFarmasiWgt, tanggalDokumenWgt, noDokumenWgt, idJenisAnggaranWgt, kodeRefPlWgt, kodeRefPoWgt);
        this._widgets.push(idSumberDanaWgt, kodeRefPenerimaanWgt, idJenisHargaWgt, idCaraBayarWgt, idPemasokWgt, itemWgt, stokWgt);
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
