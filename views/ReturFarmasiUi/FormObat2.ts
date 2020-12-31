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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/add2.php the original file
 */
final class FormObat2
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $addBonusAccess,
        array  $editAccess,
        array  $verifikasiPenerimaanAccess,
        array  $verifikasiAkuntansiAccess,
        string $addActionUrl,
        string $addBonusActionUrl,
        string $editActionUrl,
        string $verifikasiPenerimaanActionUrl,
        string $verifikasiAkuntansiActionUrl,
        string $editDataUrl,
        string $verifikasiPenerimaanDataUrl,
        string $verifikasiAkuntansiDataUrl,
        string $pembelianAcplUrl,
        string $pemesananAcplUrl,
        string $pembelian2AcplUrl,
        string $refPlUrl,
        string $terimaUrl,
        string $penerimaanUrl,
        string $detailReturUrl,
        string $cekUnikNoDokumenUrl,
        string $cekStokUrl,
        string $detailTerimaPembelianUrl,
        string $detailTerimaPemesananUrl,
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
namespace his.FatmaPharmacy.views.ReturFarmasi.FormObat2 {
    export interface FormFields {
        action:              string|"?" |"??" |"__"     |"___"      ; // action
        kode:                string|"?" |"??" |"VerAkun"|"VerTerima"; // kode
        tanggalDokumen:      string|"?" |"??" |"__"     |"___"      ; // tgl_doc
        idJenisAnggaran:     string|"?" |"??" |"__"     |"___"      ; // id_jenisanggaran
        noDokumen:           string|"?" |"??" |"__"     |"___"      ; // no_doc
        bulanAwalAnggaran:   string|"?" |"??" |"__"     |"___"      ; // blnawal_anggaran
        bulanAkhirAnggaran:  string|"?" |"??" |"__"     |"___"      ; // blnakhir_anggaran
        tahunAnggaran:       string|"?" |"??" |"__"     |"___"      ; // thn_anggaran
        kodeRefPl:           string|"?" |"??" |"__"     |"___"      ; // kode_reffpl
        idSumberDana:        string|"?" |"??" |"__"     |"___"      ; // id_sumberdana
        kodeRefDo:           string|"?" |"??" |"__"     |"___"      ; // kode_reffdo
        idJenisHarga:        string|"?" |"??" |"__"     |"___"      ; // id_jenisharga
        idCaraBayar:         string|"?" |"??" |"__"     |"___"      ; // id_carabayar
        kodeRefTerima:       string|"?" |"??" |"__"     |"___"      ; // kode_refftrm
        idGudangPenyimpanan: string|"?" |"??" |"__"     |"___"      ; // id_gudangpenyimpanan
        tabungGas:           string|"?" |"??" |"__"     |"___"      ; // sts_tabunggm
        idPemasok:           string|"?" |"??" |"__"     |"___"      ; // id_pbf
        keterangan:          string|"?" |"??" |"__"     |"___"      ; // keterangan
        sebelumDiskon:       string|"?" |"??" |"__"     |"___"      ; // // exist but missing
        diskon:              string|"?" |"??" |"__"     |"___"      ; // // exist but missing
        setelahDiskon:       string|"?" |"??" |"__"     |"___"      ; // // exist but missing
        ppn:                 string|"?" |"??" |"__"     |"___"      ; // ppn
        setelahPpn:          string|"?" |"??" |"__"     |"___"      ; // // exist but missing
        pembulatan:          string|"?" |"??" |"__"     |"___"      ; // // exist but missing
        nilaiAkhir:          string|"?" |"??" |"__"     |"___"      ; // // exist but missing

        verGudang:           string|"in"|"??" |"__"     |"___"      ; // ver_gudang
        verTerima:           string|"in"|"??" |"__"     |"VerTerima"; // ver_terima
        verAkuntansi:        string|"in"|"??" |"VerAkun"|"___"      ; // ver_akuntansi

        verTanggalGudang:    string|"in"|"??" |"__"     |"VerTerima"; // ver_tglgudang
        verTanggalTerima:    string|"in"|"??" |"__"     |"___"      ; // ver_tglterima
        verTanggalAkuntansi: string|"in"|"??" |"__"     |"___"      ; // ver_tglakuntansi

        verUserGudang:       string|"in"|"??" |"__"     |"___"      ; // user_gudang
        verUserTerima:       string|"in"|"??" |"__"     |"___"      ; // user_terima
        verUserAkuntansi:    string|"in"|"??" |"__"     |"___"      ; // user_akuntansi

        objPemasok:          PemasokFields;
        objRefTerima:        RefTerimaFields;
        objRefDo:            RefDoFields;
        objRefPl:            RefPlFields;
    }

    export interface TableFields {
        kodeRefTerima:     string|"in"|"??"; // "kode_refftrm[]";     // $d->kodeRefTerima
        kodeRefPl:         string|"in"|"??"; // "kode_reffpl[]";      // $d->kodeRefPl
        kodeRefPo:         string|"in"|"??"; // "kode_reffpo[]";      // $d->kodeRefPo
        kodeRefRo:         string|"in"|"??"; // "kode_reffro[]";      // $d->kodeRefRo
        kodeRefRencana:    string|"in"|"??"; // "kode_reffrenc[]";    // $d->kodeRefRencana
        idRefKatalog:      string|"in"|"??"; // "id_reffkatalog[]";   // $d->idRefKatalog
        idKatalog:         string|"in"|"??"; // "id_katalog[]";       // $d->idKatalog
        idPabrik:          string|"in"|"??"; // "id_pabrik[]";        // $d->idPabrik
        kemasan:           string|"in"|"??"; // "kemasan[]";          // $d->kemasan
        jumlahItemBonus:   string|"?" |"??"; // "jumlah_itembonus[]"; // +Jbns+
        jumlahBeli:        string|"?" |"??"; // "j_beli[]";           // +j_beli+
        jumlahBonus:       string|"?" |"??"; // "j_bonus[]";          // +j_bonus+
        noUrut:            string|"in"|"??"; // "no_urut[]";          // $d->noUrut
        idKemasanDepo:     string|"in"|"??"; // "id_kemasandepo[]";   // $d->idKemasanDepo
        namaSediaan:       string|"?" |"??"; // "___";                // exist but missing
        namaPabrik:        string|"?" |"??"; // "___";                // exist but missing
        idKemasan:         string|"?" |"??"; // "id_kemasan[]";
        isiKemasan:        string|"?" |"??"; // "isi_kemasan[]";
        noBatch:           string|"?" |"??"; // "no_batch[]";
        tanggalKadaluarsa: string|"?" |"??"; // "tgl_expired[]";
        jumlahKemasan:     string|"?" |"??"; // "jumlah_kemasan[]";
        jumlahItem:        string|"?" |"??"; // "___";                // exist but missing
        hargaKemasan:      string|"?" |"??"; // "harga_kemasan[]";
        hargaItem:         string|"?" |"??"; // "___";                // exist but missing
        diskonItem:        string|"?" |"??"; // "diskon_item[]";
        hargaTotal:        string|"?" |"??"; // "___";                // exist but missing
        diskonHarga:       string|"?" |"??"; // "___";                // exist but missing
        hargaAkhir:        string|"?" |"??"; // "___";                // exist but missing

        jumlahRencana:     "";
        jumlahHps:         "";
        jumlahPl:          "";
        jumlahDo:          "";
        jumlahTerima:      "";
        jumlahRetur:       "";
    }

    export interface PemasokFields {
        id:                 "id",
        subjenisAnggaran:   "subjenis_anggaran",
        bulanAwalAnggaran:  "blnawal_anggaran",
        bulanAkhirAnggaran: "blnakhir_anggaran",
        tahunAnggaran:      "thn_anggaran",
        nilaiAkhir:         "nilai_akhir",
        namaPemasok:        "nama_pbf",
        noDokumen:          "no_doc",
    }

    export interface RefTerimaFields {
        id:                   string|"?" |"??"; // "id",                  // MISSING in db
        subjenisAnggaran:     string|"in"|"??"; // "subjenis_anggaran",
        bulanAwalAnggaran:    string|"in"|"??"; // "blnawal_anggaran",
        bulanAkhirAnggaran:   string|"in"|"??"; // "blnakhir_anggaran",
        tahunAnggaran:        string|"in"|"??"; // "thn_anggaran",
        nilaiAkhir:           string|"in"|"??"; // "nilai_akhir",
        noDokumen:            string|"in"|"??"; // "no_doc",
        noSpk:                string|"in"|"??"; // "no_spk",
        kodeRefTerima:        string|"in"|"??"; // "kode_refftrm",
        kodeRefPo:            string|"in"|"??"; // "kode_reffpo",
        kodeRefPl:            string|"in"|"??"; // "kode_reffpl",
        idPemasok:            string|"in"|"??"; // "id_pbf",
        namaPemasok:          string|"in"|"??"; // "nama_pbf",
        idJenisAnggaran:      string|"in"|"??"; // "id_jenisanggaran",
        idSumberDana:         string|"in"|"??"; // "id_sumberdana",
        idJenisHarga:         string|"in"|"??"; // "id_jenisharga",
        idCaraBayar:          string|"in"|"??"; // "id_carabayar",
        ppn:                  string|"in"|"??"; // "ppn",
        action:               string|"?" |"??"; // "act",                 // MISSING in db
        kode:                 string|"in"|"??"; // "kode",

        objRefTerima:        RefTerimaFields;
        objRefDo:            RefDoFields;
        objRefPl:            RefPlFields;
    }

    export interface RefDoFields {
        bulanAwalAnggaran:    string;
        bulanAkhirAnggaran:   string;
        tahunAnggaran:        string;
        nilaiAkhir:           string;
        noDokumen:            string;
        namaPemasok:          string;
        kode:                 string;
        tanggalTempoKirim:    string; // not used, but exist in controller
        id:                   "id",
        subjenisAnggaran:     "subjenis_anggaran",
        noSpk:                "no_spk",
        action:               "act",

        objRefPl:             RefPlFields;
    }

    export interface RefPlFields {
        subjenisAnggaran:   string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        noDokumen:          string;
        idPemasok:          string;
        namaPemasok:        string;
        kode:               string;
        tipeDokumen:        string;
        idJenisHarga:       string;
        id:                 "id",
        nilaiAkhir:         "nilai_akhir",
        idJenisAnggaran:    "id_jenisanggaran",
        idSumberDana:       "id_sumberdana",
        idCaraBayar:        "id_carabayar",
        ppn:                "ppn",
        action:             "act",

        objPemasok:         PemasokFields;
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
     * @returns {{add: boolean, addBonus: boolean, edit: boolean, verifikasiPenerimaan: boolean, verifikasiAkuntansi: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
            addBonus: JSON.parse(`<?=json_encode($addBonusAccess) ?>`),
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
            class: ".tambahReturFarmasiFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {name: "usrid_updt"},
                    hidden_2: {class: ".actionFld", name: "action"},
                    hidden_3: {name: "submit", value: "save"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
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
                        label: tlm.stringRegistry._<?= $h("Tarik Dengan") ?>,
                        select: {
                            class: ".tarikDenganFld",
                            name: "tarikDengan",
                            option_1: {value: "spk", label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>},
                            option_2: {value: "do",  label: tlm.stringRegistry._<?= $h("Ref. Delivery Order") ?>},
                            option_3: {value: "trm", label: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>},
                        }
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>,
                        input: {class: ".kodeRefPlFld", name: "kodeRefPl"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Ref. Delivery Order") ?>,
                        input: {class: ".kodeRefDoFld", name: "kodeRefDo"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>,
                        input: {class: ".kodeRefTerimaFld", name: "kodeRefTerima"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                        select: {
                            class: ".idGudangPenyimpananFld",
                            name: "idGudangPenyimpanan",
                            option_1: {value: 59, label: tlm.stringRegistry._<?= $h("Gudang Induk Farmasi") ?>},
                            option_2: {value: 60, label: tlm.stringRegistry._<?= $h("Gudang Gas Medis") ?>},
                            option_3: {value: 69, label: tlm.stringRegistry._<?= $h("Gudang Konsinyasi") ?>}
                        }
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Tabung Gas") ?>,
                        radio_1: {class: ".tabungGasYesOpt", name: "tabungGas", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                        radio_2: {class: ".tabungGasNoOpt",  name: "tabungGas", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        input: {class: ".keteranganFld", name: "keterangan"}
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
                            td_1:  /*  1    */ {
                                checkbox: {class: ".cekSemuaFld"}, // class=ck-all
                                staticText: tlm.stringRegistry._<?= $h("No.") ?>,
                                rowspan: 2,
                            },
                            td_2:  /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3:  /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4:  /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  /*  5    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6:  /*  6-10 */ {colspan: 5, text: tlm.stringRegistry._<?= $h("Pengadaan") ?>},
                            td_7:  /* 11-12 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8:  /* 13    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9:  /* 14-16 */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_10: /* 17-23 */ {colspan: 7, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
                        },
                        tr_2: {
                            td_1:  /*  6 */ {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  /*  7 */ {text: tlm.stringRegistry._<?= $h("Batch") ?>},
                            td_3:  /*  8 */ {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            td_4:  /*  9 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  /* 10 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_6:  /* 11 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_7:  /* 12 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_8:  /* 14 */ {text: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>},
                            td_9:  /* 15 */ {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_10: /* 16 */ {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                            td_11: /* 17 */ {text: tlm.stringRegistry._<?= $h("Rencana") ?>},
                            td_12: /* 18 */ {text: tlm.stringRegistry._<?= $h("HPS") ?>},
                            td_13: /* 19 */ {text: tlm.stringRegistry._<?= $h("SPK") ?>},
                            td_14: /* 20 */ {text: tlm.stringRegistry._<?= $h("DO") ?>},
                            td_15: /* 21 */ {text: tlm.stringRegistry._<?= $h("Bonus") ?>},
                            td_16: /* 22 */ {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                            td_17: /* 23 */ {text: tlm.stringRegistry._<?= $h("Retur") ?>},
                        }
                    },
                    tbody: {
                        tr: {
                            td_1:  /*  1 */ {
                                hidden_1: {class: ".kodeRefTerimaFld", name: "kodeRefTerima[]"},
                                hidden_2: {class: ".kodeRefPlFld", name: "kodeRefPl[]"},
                                hidden_3: {class: ".kodeRefPoFld", name: "kodeRefPo[]"},
                                hidden_4: {class: ".kodeRefRoFld", name: "kodeRefRo[]"},
                                hidden_5: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]"},
                                hidden_6: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_7: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_8: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_9: {class: ".jumlahItemBonusFld", name: "jumlahItemBonus[]"},
                                hidden_10: {class: ".jumlahBeliFld", name: "jumlahBeli[]"},
                                hidden_11: {class: ".jumlahBonusFld", name: "jumlahBonus[]"},
                                hidden_12: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                                hidden_13: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_14: {class: ".noUrutFld", name: "noUrut[]"},
                                checkbox:  {class: ".cekSatuFld"},
                                staticText: {class: ".noGroupStc"},
                            },
                            td_2:  /*  2 */ {
                                staticText: {class: ".namaSediaanStc"},
                                rButton: {class: ".stokBtn", text: tlm.stringRegistry._<?= $h("Stok") ?>}
                            },
                            td_3:  /*  3 */ {class: ".namaPabrikStc"},
                            td_4:  /*  4 */ {
                                select: {
                                    class: ".idKemasanFld",
                                    name: "idKemasan[]",
                                    option: {} // value:$d->idKemasan  is:$d->isiKemasan  data-ids:$d->idKemasanDepo  data-sat:$d->satuan  data-satj:$d->satuanJual  data-hk:$d->hargaKemasan  text:$d->kemasan
                                }
                            },
                            td_5:  /*  5 */ {class: ".isiKemasanStc"},
                            td_6:  /*  6 */ {
                                button_1: {class: ".addBatchBtn", text: "+"},
                                button_2: {class: ".deleteBatchBtn", text: "-"},
                                staticText: {class: ".noUrutStc"}
                            },
                            td_7:  /*  7 */ {
                                input: {class: ".noBatchFld", name: "noBatch[]"}
                            },
                            td_8:  /*  8 */ {
                                input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                            },
                            td_9:  /*  9 */ {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]"}
                            },
                            td_10: /* 10 */ {class: ".jumlahItemStc"},
                            td_11: /* 11 */ {class: ".hargaKemasanStc"},
                            td_12: /* 12 */ {class: ".hargaItemStc"},
                            td_13: /* 13 */ {class: ".diskonItemStc"},
                            td_14: /* 14 */ {class: ".hargaTotalStc"},
                            td_15: /* 15 */ {class: ".diskonHargaStc"},
                            td_16: /* 16 */ {class: ".hargaAkhirStc"},
                            td_17: /* 17 */ {class: ".jumlahRencanaStc", text: "0.00"},
                            td_18: /* 18 */ {class: ".jumlahHpsStc", text: "0.00"},
                            td_19: /* 19 */ {class: ".jumlahPlStc"},
                            td_20: /* 20 */ {class: ".jumlahDoStc", text: "0.00"},
                            td_21: /* 21 */ {class: ".jumlahBonusStc", text: "0.00"},
                            td_22: /* 22 */ {class: ".jumlahTerimaStc"},
                            td_23: /* 23 */ {class: ".jumlahReturStc"},
                        },
                    },
                    tfoot: {
                        tr_1: {
                            td_1: {
                                hidden_1: {name: "idRefKatalog[]", disabled: true},
                                hidden_2: {name: "idKatalog[]", disabled: true},
                                hidden_3: {name: "idPabrik[]", disabled: true},
                                hidden_4: {name: "kemasan[]", disabled: true},
                                hidden_5: {name: "idKemasanDepo[]", disabled: true},
                                checkbox: {disabled: true},
                                staticText: {class: ".noGroupStc"},
                            },
                            td_2: {text: ""},
                            td_3: {text: ""},
                            td_4: {
                                select: {class: ".idKemasanFld", name: "idKemasan[]", disabled: true}
                            },
                            td_5: {class: ".isiKemasanStc", text: 1},
                            td_6: {text: ""},
                            td_7: {
                                input: {class: ".noBatchFld", name: "noBatch[]", disabled: true}
                            },
                            td_8: {
                                input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]", value: "", disabled: true}
                            },
                            td_9: {
                                input: {class: ".jumlahKemasanFld", value: "0 BOX", name: "jumlahKemasan[]", disabled: true}
                            },
                            td_10: {
                                input: {class: ".jumlahItemFld", value: "0 BOX", name: "jumlahItem[]", disabled: true}
                            },
                            td_11: {class: ".hargaKemasanStc", text: "0.00"},
                            td_12: {
                                input: {class: ".hargaItemFld", value: "0.00", name: "hargaItem[]", disabled: true}
                            },
                            td_13: {class: ".diskonItemStc", text: "0.00"},
                            td_14: {class: ".hargaTotalStc", text: "0.00"},
                            td_15: {
                                input: {class: ".diskonHargaFld", value: "0.00", name: "diskonHarga[]", disabled: true}
                            },
                            td_16: {class: ".hargaAkhirStc", text: "0.00"},
                            td_17: {class: ".jumlahRencanaStc", text: "0.00"},
                            td_18: {class: ".jumlahHpsStc", text: "0.00"},
                            td_19: {class: ".jumlahPlStc", text: "0.00"},
                            td_20: {class: ".jumlahDoStc", text: "0.00"},
                            td_21: {class: ".jumlahBonusStc", text: "0.00"},
                            td_22: {class: ".jumlahTerimaStc", text: "0.00"},
                            td_23: {class: ".jumlahReturStc", text: "0.00"},
                        },
                        tr_2: {
                            td: {colspan: 23, text: "&nbsp;"}
                        },
                        // TODO: js: uncategorized: determine whether this part is redundance or not with upper part
                        tr_3: {
                            td_1: {colspan: 13, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_2: {class: ".nt", text: "0.00"},   // ".nt" -> nilaiTotalSebelumDiskon
                            td_3: {class: ".nd", text: "0.00"},   // ".nd" -> nilaiDiskon
                            td_4: {class: ".n_td", text: "0.00"}, // ".n_td" -> nilaiSebelumPpn
                            td_5: {colspan: 7},
                        },
                        tr_4: {
                            td_1: {colspan: 15, text: tlm.stringRegistry._<?= $h("PPN") ?>},
                            td_2: {class: ".np", text: "0.00"}, // ".np" -> nilaiPpn
                            td_3: {colspan: 7},
                        },
                        tr_5: {
                            td_1: {colspan: 15, text: tlm.stringRegistry._<?= $h("Setelah PPN") ?>},
                            td_2: {class: ".n_tp", text: "0.00"}, // ".n_tp" -> nilaiSetelahPpn
                            td_3: {colspan: 7},
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
            },
            row_6: {
                column: {
                    button_2: {class: ".printBtn",   text: tlm.stringRegistry._<?= $h("Print") ?>,   title: tlm.stringRegistry._<?= $h("Print Perencanaan") ?>},
                    button_3: {class: ".deleteBtn",  text: tlm.stringRegistry._<?= $h("Hapus") ?>,   title: tlm.stringRegistry._<?= $h("Hapus Perencanaan") ?>},
                    button_4: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>, title: tlm.stringRegistry._<?= $h("Kembali Ke Index") ?>},
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
        const closest = /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */ spl.util.closestParent;
        const {numToShortMonthName: nToS, toSystemNumber: sysNum, toCurrency: currency, toUserInt: userInt} = tlm;
        const {toUserDate: userDate, stringRegistry: str, nowVal, preferInt} = tlm;
        const userName = tlm.user.nama;
        const trStr = spl.TableDrawer.drawTr("tbody", this._structure.form.row_3.widthTable.tbody.tr);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const tarikDenganFld = divElm.querySelector(".tarikDenganFld");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLSelectElement} */ const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLInputElement} */  const tabungGasYesOpt = divElm.querySelector(".tabungGasYesOpt");
        /** @type {HTMLInputElement} */  const tabungGasNoOpt = divElm.querySelector(".tabungGasNoOpt");
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
        /** @type {HTMLInputElement} */  const cekSemuaFld = divElm.querySelector(".cekSemuaFld");
        /** @type {HTMLButtonElement} */ const deleteBtn = divElm.querySelector(".deleteBtn");
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

        /** @type {spl.AjaxFormWidget<his.FatmaPharmacy.views.ReturFarmasi.FormObat2.FormFields>} */
        const tambahReturFarmasiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".tambahReturFarmasiFrm"),
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                data.tabungGas ? tabungGasYesOpt.checked = true : tabungGasNoOpt.checked = true;
                keteranganFld.value = data.keterangan ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.checked = data.ppn == 10;
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

                idPemasokWgt.clearOptions();
                if (data.objPemasok) {
                    idPemasokWgt.addOption(data.objPemasok);
                    idPemasokWgt.value = data.idPemasok;
                }

                kodeRefTerimaWgt.clearOptions();
                if (data.objRefTerima) {
                    kodeRefTerimaWgt.addOption(data.objRefTerima);
                    kodeRefTerimaWgt.value = data.kodeRefTerima;
                    tarikDenganFld.value = "trm";
                }

                kodeRefDoWgt.clearOptions();
                if (data.objRefDo) {
                    kodeRefDoWgt.addOption(data.objRefDo);
                    kodeRefDoWgt.value = data.kodeRefDo;
                    tarikDenganFld.value = "do";
                }

                kodeRefPlWgt.clearOptions();
                if (data.objRefPl) {
                    kodeRefPlWgt.addOption(data.objRefPl);
                    kodeRefPlWgt.value = data.kodeRefPl;
                    tarikDenganFld.value = "spk";
                }

                tarikSebelumnya = tarikDenganFld.value;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                /** @this {spl.AjaxFormWidget} */
                add() {
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.loadData({});

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "add";
                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    deleteBtn.disabled = false;
                    printBtn.disabled = true;
                    kodeRefPlWgt.enable();
                    kodeRefDoWgt.enable();
                    kodeRefTerimaWgt.enable();
                    cekSemuaFld.disabled = false;
                    idGudangPenyimpananFld.disabled = false;
                    divElm.querySelectorAll(".cekSatuFld").forEach(item => item.disabled = true);
                    keteranganFld.disabled = false;
                    noDokumenWgt.enable();
                    tanggalDokumenWgt.enable();
                },
                /** @this {spl.AjaxFormWidget} */
                addBonus() {
                    this._actionUrl = "<?= $addBonusActionUrl ?>";
                    this.loadData({});

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "add";
                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    deleteBtn.disabled = false;
                    printBtn.disabled = true;
                    kodeRefPlWgt.enable();
                    kodeRefDoWgt.enable();
                    kodeRefTerimaWgt.enable();
                    cekSemuaFld.disabled = false;
                    idGudangPenyimpananFld.disabled = true;
                    divElm.querySelectorAll(".cekSatuFld").forEach(item => item.disabled = true);
                    keteranganFld.disabled = true;
                    noDokumenWgt.enable();
                    tanggalDokumenWgt.enable();
                },
                /**
                 * @this {spl.AjaxFormWidget}
                 * @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.FormFields} data
                 * @param {boolean} fromServer
                 */
                edit(data, fromServer) {
                    this._dataUrl = "<?= $editDataUrl ?>";
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.loadData(data, fromServer);

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    verGudangFld.disabled = data.verGudang == "1";
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = data.verTerima != "1";
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = data.verTerima != "1";
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi != "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";

                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    deleteBtn.disabled = false;
                    printBtn.disabled = false;
                    kodeRefPlWgt.disable();
                    kodeRefDoWgt.disable();
                    kodeRefTerimaWgt.disable();
                    cekSemuaFld.disabled = false;
                    idGudangPenyimpananFld.disabled = false;
                    divElm.querySelectorAll(".cekSatuFld").forEach(item => item.disabled = false);
                    keteranganFld.disabled = false;
                    noDokumenWgt.enable();
                    tanggalDokumenWgt.enable();
                },
                /**
                 * @this {spl.AjaxFormWidget}
                 * @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.FormFields} data
                 * @param {boolean} fromServer
                 */
                verifikasiPenerimaan(data, fromServer) {
                    this._dataUrl = "<?= $verifikasiPenerimaanDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiPenerimaanActionUrl ?>";
                    this.loadData(data, fromServer);

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    verGudangFld.disabled = true;
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = false;
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = data.verTerima != "1";
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi != "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";

                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    deleteBtn.disabled = true;
                    printBtn.disabled = false;
                    kodeRefPlWgt.disable();
                    kodeRefDoWgt.disable();
                    kodeRefTerimaWgt.disable();
                    cekSemuaFld.disabled = true;
                    idGudangPenyimpananFld.disabled = true;
                    divElm.querySelectorAll(".cekSatuFld").forEach(item => item.disabled = true);
                    keteranganFld.disabled = true;
                    noDokumenWgt.disable();
                    tanggalDokumenWgt.disable();
                },
                /**
                 * @this {spl.AjaxFormWidget}
                 * @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.FormFields} data
                 * @param {boolean} fromServer
                 */
                verifikasiAkuntansi(data, fromServer) {
                    this._dataUrl = "<?= $verifikasiAkuntansiDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiAkuntansiActionUrl ?>";
                    this.loadData(data, fromServer);

                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    verGudangFld.disabled = true;
                    verGudangFld.checked = data.verGudang == "1";

                    verTerimaFld.disabled = true;
                    verTerimaFld.checked = data.verTerima == "1";

                    updateStokMarkerFld.disabled = true;
                    updateStokMarkerFld.checked = data.verTerima == "1";

                    verAkuntansiFld.disabled = data.verAkuntansi == "1";
                    verAkuntansiFld.checked = data.verAkuntansi == "1";

                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    deleteBtn.disabled = true;
                    printBtn.disabled = false;
                    kodeRefPlWgt.disable();
                    kodeRefDoWgt.disable();
                    kodeRefTerimaWgt.disable();
                    cekSemuaFld.disabled = true;
                    idGudangPenyimpananFld.disabled = true;
                    divElm.querySelectorAll(".cekSatuFld").forEach(item => item.disabled = true);
                    keteranganFld.disabled = true;
                    noDokumenWgt.disable();
                    tanggalDokumenWgt.disable();
                }
            },
            onInit() {
                this.loadProfile("add");
            },
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
                widget.show();
                widget.loadData({kode: kodeFld.value}, true);
            },
        });

        /** @param {HTMLTableRowElement} trElm */
        function hitungSubTotal(trElm) {
            const fields = trElm.fields;
            const jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
            const hargaKemasan = sysNum(fields.hargaKemasanStc.innerHTML);
            const diskonItem = sysNum(fields.diskonItemStc.innerHTML);
            const isiKemasan = sysNum(fields.isiKemasanStc.innerHTML);
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

        function sortNumber() {
            itemWgt.querySelectorAll(".noGroupStc").forEach((item, i) => item.innerHTML = i + 1);
        }

        /**
         * combined with extracted from selectItemDO, selectItemSPK, kodeRefPlWgt, and kodeRefTerimaWgt
         * @param {String} url
         * @param {String} kodeRef
         */
        function inputKatalog(url, kodeRef) {
            $.post({
                url,
                data: {kodeRef},
                /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.TableFields[]} data */
                success(data) {
                    if (!data.length) return;

                    const body = itemWgt.querySelector("body");
                    let lastTrElm = /** @type {HTMLTableRowElement} */ body.querySelector("tr:last-child");
                    data.forEach(obj => {
                        if (obj.jumlahItem == obj.jumlahRetur) return;
                        if (body.querySelector("tr._" + obj.idKatalog)) return;

                        body.insertAdjacentHTML("beforeend", trStr);
                        groupCounter++;
                        obj.noUrut = groupCounter;

                        lastTrElm = /** @type {HTMLTableRowElement} */ body.querySelector("tr:last-child");
                        addRow(lastTrElm);
                        loadDataPerRow(lastTrElm, obj, 1000);
                    });

                    sortNumber();
                    hitungTotal();
                    lastTrElm && lastTrElm.fields.jumlahKemasanWgt.dispatchEvent(new Event("focus"));
                }
            });
        }

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPlFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefPlFields} item */
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
                        <div class="col-xs-3"><b>${item.namaPemasok}</b></div>
                        <div class="col-xs-3"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-2">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefPlFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.namaPemasok})</div>`},
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
                /** @type {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefPlFields} */
                const obj = this.options[value];

                idJenisAnggaranFld.value = obj.idJenisAnggaran ?? "";
                idSumberDanaFld.value = obj.idSumberDana ?? "";
                idJenisHargaFld.value = obj.idJenisHarga ?? "";
                idCaraBayarFld.value = obj.idCaraBayar ?? "";
                ppnFld.value = obj.ppn ?? "";
                idPemasokWgt.value = obj.idPemasok ?? "";
                idGudangPenyimpananFld.value = (obj.idJenisAnggaran == "6") ? "60" : "59";
                ppnFld.checked = obj.ppn == 10;

                if (obj.objPemasok) {
                    idPemasokWgt.addOption(obj.objPemasok);
                    idPemasokWgt.value = obj.idPemasok;
                }

                if (obj.action) return;

                $.post({
                    url: "<?= $refPlUrl ?>",
                    data: {kode: obj.kode},
                    success(data) {
                        if (data[0] == 0 && obj.tipeDokumen != "0") {
                            bulanAwalAnggaranFld.value = obj.bulanAwalAnggaran;
                            bulanAkhirAnggaranFld.value = obj.bulanAkhirAnggaran;
                            tahunAnggaranFld.value = obj.tahunAnggaran;
                            inputKatalog("<?= $detailTerimaPembelianUrl ?>", obj.kode);

                        } else {
                            $.post({
                                url: "<?= $terimaUrl ?>",
                                data: {kodeRefPl: obj.kode, statusSaved: 1, statusClosed: 0, statusRevisi: 0},
                                success(dom) {
                                    divElm.querySelector(".modal-body").innerHTML = dom;
                                    divElm.querySelector(".modal-header").innerHTML = `<h5 style="color:#FFF">Purchase Order dari ${obj.noDokumen}<br/>${obj.namaPemasok}</h5>`;
                                    divElm.querySelector("#modal-modul").modal("show");
                                    divElm.querySelector(".modal-footer").querySelector("#btn-pull").style.display = "none";
                                }
                            });
                        }
                    }
                });
            },
            onItemRemove() {
                const msg = str._<?= $h('Menghapus "Ref. PL" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>;
                if (!confirm(msg)) return;

                itemWgt.reset();
                hitungTotal();
            }
        });

        const kodeRefDoWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefDoFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefDoFields} item */
            optionRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                const awal = item.id ? item.bulanAwalAnggaran : bulanAwalAnggaranFld.value;
                const akhir = item.id ? item.bulanAkhirAnggaran : bulanAkhirAnggaranFld.value;
                const tahun = item.id ? item.tahunAnggaran : tahunAnggaranFld.value;
                const nilaiAkhir = item.id ? item.nilaiAkhir : 0;

                const anggaran2 = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                return `
                    <div class="col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-2"><b>${item.noSpk}</b></div>
                        <div class="col-xs-3"><b>${item.namaPemasok}</b></div>
                        <div class="col-xs-2"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-1">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefDoFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.noSpk})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pemesananAcplUrl ?>",
                    data: {noDokumen: typed, statusClosed: 0, statusRevisi: 0},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefDoFields} */
                const obj = this.options[value];

                if (obj.objRefPl) {
                    kodeRefPlWgt.addOption(obj.objRefPl);
                    kodeRefPlWgt.value = obj.objRefPl.kode;
                }

                // select item spk
                if (obj.action) return;

                inputKatalog("<?= $detailTerimaPemesananUrl ?>", obj.kode);
            },
            onItemRemove() {
                const msg = str._<?= $h('Menghapus "Ref. DO" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>;
                if (!confirm(msg)) return;

                itemWgt.reset();
                hitungTotal();
            }
        });

        const kodeRefTerimaWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefTerimaFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefTerimaFields} item */
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
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefTerimaFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.noSpk})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $penerimaanUrl ?>",
                    data: {
                        noDokumen: typed,
                        verTerima: 1,
                        verGudang: 1,
                        verAkuntansi: 0,
                        statusRevisi: 0
                    },
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.RefTerimaFields} */
                const obj = this.options[value];

                if (obj.kodeRefTerima && obj.objRefTerima) {
                    kodeRefTerimaWgt.addOption(obj.objRefTerima);
                    kodeRefTerimaWgt.value = obj.kodeRefTerima;

                } else if (obj.kodeRefPo && obj.objRefDo) {
                    kodeRefDoWgt.addOption(obj.objRefDo);
                    kodeRefDoWgt.value = obj.kodeRefPo;

                } else if (obj.kodeRefPl && obj.objRefPl) {
                    kodeRefPlWgt.addOption(obj.objRefPl);
                    kodeRefPlWgt.value = obj.kodeRefPl;

                } else {
                    idJenisAnggaranFld.value = obj.idJenisAnggaran;
                    idSumberDanaFld.value = obj.idSumberDana;
                    idJenisHargaFld.value = obj.idJenisHarga;
                    idCaraBayarFld.value = obj.idCaraBayar;
                    ppnFld.value = obj.ppn;
                    idGudangPenyimpananFld.value = (obj.idJenisAnggaran == "6") ? "60" : "59";
                    ppnFld.checked = obj.ppn == 10;

                    idPemasokWgt.addOption(obj);
                    idPemasokWgt.value = obj.idPemasok;
                }

                // select item spk
                if (obj.action) return;

                inputKatalog("<?= $detailReturUrl ?>", obj.kode);
            },
            onItemRemove() {
                const msg = str._<?= $h('Menghapus "Ref. Terima" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>;
                if (!confirm(msg)) return;

                itemWgt.reset();
                hitungTotal();
            }
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "idPemasok",
            searchField: ["namaPemasok"],
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.PemasokFields} item */
            optionRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                const awal = item.id ? item.bulanAwalAnggaran : bulanAwalAnggaranFld.value;
                const akhir = item.id ? item.bulanAkhirAnggaran : bulanAkhirAnggaranFld.value;
                const tahun = item.id ? item.tahunAnggaran : tahunAnggaranFld.value;
                const nilaiAkhir = item.id ? item.nilaiAkhir : 0;

                const anggaran2 = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-3"><b>${item.namaPemasok}</b></div>
                        <div class="col-xs-2"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-3"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-2">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.PemasokFields} item */
            itemRenderer(item) {return `<div class="item">${item.namaPemasok} (${item.noDokumen})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pembelian2AcplUrl ?>",
                    data: {statusClosed: 0, statusRevisi: 0, namaPemasok: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemRemove() {
                const msg = str._<?= $h('Menghapus "Ref. Pemasok" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>;
                if (!confirm(msg)) return;

                itemWgt.reset();
                hitungTotal();
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

        let groupCounter;

        /**
         * @param {HTMLTableRowElement} trElm
         * @param {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.TableFields} data
         * @param {number} idx
         */
        function loadDataPerRow(trElm, data, idx) {
            const fields = trElm.fields;
            const noUrutIsOne = data.noUrut == "1";

            const tdList = trElm.querySelectorAll("td");
            tdList[0].colSpan = noUrutIsOne ? 1 : 4;
            tdList[1].style.display = noUrutIsOne ? "table-cell" : "none";
            tdList[2].style.display = noUrutIsOne ? "table-cell" : "none";
            tdList[3].style.display = noUrutIsOne ? "table-cell" : "none";
            tdList[16].colSpan = noUrutIsOne ? 1 : 7;
            tdList[17].style.display = noUrutIsOne ? "table-cell" : "none";
            tdList[18].style.display = noUrutIsOne ? "table-cell" : "none";
            tdList[19].style.display = noUrutIsOne ? "table-cell" : "none";
            tdList[20].style.display = noUrutIsOne ? "table-cell" : "none";
            tdList[21].style.display = noUrutIsOne ? "table-cell" : "none";
            tdList[22].style.display = noUrutIsOne ? "table-cell" : "none";

            fields.kodeRefTerimaFld.disabled = !noUrutIsOne;
            fields.kodeRefPlFld.disabled = !noUrutIsOne;
            fields.kodeRefPoFld.disabled= !noUrutIsOne;
            fields.kodeRefRoFld.disabled = !noUrutIsOne;
            fields.kodeRefRencanaFld.disabled = !noUrutIsOne;
            fields.idRefKatalogFld.disabled = !noUrutIsOne;
            fields.idPabrikFld.disabled = !noUrutIsOne;
            fields.kemasanFld.disabled = !noUrutIsOne;
            fields.jumlahItemBonusFld.disabled = !noUrutIsOne;
            fields.jumlahBeliFld.disabled = !noUrutIsOne;
            fields.jumlahBonusFld.disabled = !noUrutIsOne;
            fields.idKemasanDepoFld.disabled = !noUrutIsOne;

            fields.kodeRefTerimaFld.value   = noUrutIsOne ? (data.kodeRefTerima ?? "")   : "";
            fields.kodeRefPlFld.value       = noUrutIsOne ? (data.kodeRefPl ?? "")       : "";
            fields.kodeRefPoFld.value       = noUrutIsOne ? (data.kodeRefPo ?? "")       : "";
            fields.kodeRefRoFld.value       = noUrutIsOne ? (data.kodeRefRo ?? "")       : "";
            fields.kodeRefRencanaFld.value  = noUrutIsOne ? (data.kodeRefRencana ?? "")  : "";
            fields.idRefKatalogFld.value    = noUrutIsOne ? (data.idRefKatalog ?? "")    : "";
            fields.idPabrikFld.value        = noUrutIsOne ? (data.idPabrik ?? "")        : "";
            fields.kemasanFld.value         = noUrutIsOne ? (data.kemasan ?? "")         : "";
            fields.jumlahItemBonusFld.value = noUrutIsOne ? (data.jumlahItemBonus ?? "") : "";
            fields.jumlahBeliFld.value      = noUrutIsOne ? (data.jumlahBeli ?? "")      : "";
            fields.jumlahBonusFld.value     = noUrutIsOne ? (data.jumlahBonus ?? "")     : "";
            fields.idKemasanDepoFld.value   = noUrutIsOne ? (data.idKemasanDepo ?? "")   : "";

            fields.idKatalogFld.value = data.idKatalog ?? "";
            fields.noUrutFld.value = data.noUrut ?? "";

            const noGroupStc = trElm.querySelector(".noGroupStc");
            if (idx == 0) {
                groupCounter = 1;
                noGroupStc.innerHTML = groupCounter;
            } else if (noUrutIsOne) {
                groupCounter++;
                noGroupStc.innerHTML = groupCounter;
            } else {
                noGroupStc.classList.remove("noGroupStc");
            }

            const addBatchBtn = trElm.querySelector(".addBatchBtn");
            const deleteBatchBtn = trElm.querySelector(".deleteBatchBtn");

            addBatchBtn.disabled = !noUrutIsOne || (tabungGasYesOpt.checked && data.jumlahKemasan <= 1);
            deleteBatchBtn.disabled = noUrutIsOne;

            if (noUrutIsOne) {
                addBatchBtn.value = JSON.stringify({
                    isiKemasan: data.isiKemasan,
                    idKatalog: data.idKatalog,
                    jumlahKemasan: data.jumlahKemasan,
                    jumlahItem: data.jumlahItem,
                    hargaKemasan: data.hargaKemasan,
                    hargaItem: data.hargaItem,
                    diskonItem: data.diskonItem,
                });
            } else {
                deleteBatchBtn.value = data.idKatalog;
            }

            fields.namaSediaanStc.innerHTML = data.namaSediaan ?? "";
            fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
            fields.idKemasanFld.value = data.idKemasan ?? "";
            fields.isiKemasanStc.innerHTML = data.isiKemasan || 0;
            fields.noBatchWgt.value = data.noBatch ?? "";
            fields.tanggalKadaluarsaWgt.value = data.tanggalKadaluarsa ?? "";
            fields.jumlahKemasanWgt.value = data.jumlahKemasan || 0; // data-a-sign:$d->satuanJual
            fields.hargaKemasanStc.innerHTML = currency(data.hargaKemasan || 0);
            fields.diskonItemStc.innerHTML = currency(data.diskonItem || 0);
            hitungSubTotal(trElm);

            fields.jumlahRencanaStc.innerHTML = noUrutIsOne ? (data.jumlahRencana ?? "") : "";
            fields.jumlahHpsStc.innerHTML = data.jumlahHps ?? "";
            fields.jumlahPlStc.innerHTML = data.jumlahPl ?? "";
            fields.jumlahDoStc.innerHTML = data.jumlahDo ?? "";
            fields.jumlahBonusStc.innerHTML = data.jumlahBonus ?? "";
            fields.jumlahTerimaStc.innerHTML = data.jumlahTerima ?? "";
            fields.jumlahReturStc.innerHTML = data.jumlahRetur ?? "";

            fields.noUrutStc.innerHTML = data.noUrut ?? "";
            fields.stokBtn.value = data.idKatalog ?? "";
            fields.jumlahMaksimum = data.jumlahTerima - data.jumlahRetur; //  value:$d->jumlahItem + $d->satuan  data-a-sign:$d->satuan

            trElm.classList.add("_"+data.idKatalog);
        }

        function addRow(trElm) {
            const isiKemasanStc = trElm.querySelector(".isiKemasanStc");

            const jumlahKemasanWgt = new spl.NumberWidget({
                element: trElm.querySelector(".jumlahKemasanFld"),
                errorRules: [
                    {required: true},
                    {greaterThan: 0},
                    {
                        callback() {
                            const jumlahKemasan = sysNum(this._element.value);
                            const maksimumJumlahItem = sysNum(trElm.fields.jumlahMaksimum);
                            const isiKemasan = sysNum(isiKemasanStc.innerHTML);
                            return jumlahKemasan <= maksimumJumlahItem / isiKemasan;
                        },
                        message: str._<?= $h("melebihi MaksimumJumlahItem ÷ IsiKemasan") ?>
                    }
                ],
                onBlur() {
                    hitungSubTotal(closest(this._element, "tr"));
                    hitungTotal();
                },
                ...tlm.intNumberSetting
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

            const tanggalKadaluarsaWgt = new spl.DateTimeWidget({
                element: trElm.querySelector(".tanggalKadaluarsaFld"),
                // TODO: js: uncategorized: add "already expired", and "less than 2 years" rules
                errorRules: [{required: true}],
                ...tlm.dateWidgetSetting
            });

            trElm.fields = {
                jumlahKemasanWgt,
                noBatchWgt,
                tanggalKadaluarsaWgt,
                isiKemasanStc,
                jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                idPabrikFld: trElm.querySelector(".idPabrikFld"),
                idKatalogFld: trElm.querySelector(".idKatalogFld"),
                kodeRefTerimaFld: trElm.querySelector(".kodeRefTerimaFld"),
                kodeRefPlFld: trElm.querySelector(".kodeRefPlFld"),
                kodeRefPoFld: trElm.querySelector(".kodeRefPoFld"),
                kodeRefRoFld: trElm.querySelector(".kodeRefRoFld"),
                kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                kemasanFld: trElm.querySelector(".kemasanFld"),
                jumlahItemBonusFld: trElm.querySelector(".jumlahItemBonusFld"),
                jumlahBeliFld: trElm.querySelector(".jumlahBeliFld"),
                jumlahBonusFld: trElm.querySelector(".jumlahBonusFld"),
                idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                noUrutFld: trElm.querySelector(".noUrutFld"),
                hargaKemasanStc: trElm.querySelector(".hargaKemasanStc"),
                hargaItemStc: trElm.querySelector(".hargaItemStc"),
                diskonItemStc: trElm.querySelector(".diskonItemStc"),
                namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                idKemasanFld: trElm.querySelector(".idKemasanFld"),
                noUrutStc: trElm.querySelector(".noUrutStc"),
                hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                stokBtn: trElm.querySelector(".stokBtn"),
                jumlahMaksimum: 0,
            };
        }

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            loadDataPerRow,
            addRow,
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.jumlahKemasanWgt.destroy();
                fields.noBatchWgt.destroy();
                fields.tanggalKadaluarsaWgt.destroy();
            },
            profile: {
                add(data) {
                    // TODO: js: uncategorized: finish this
                },
                addBonus(data) {
                    // TODO: js: uncategorized: finish this
                },
                edit(data) {
                    // TODO: js: uncategorized: finish this
                },
                verifikasiPenerimaan(data) {
                    // TODO: js: uncategorized: finish this
                },
                verifikasiAkuntansi(data) {
                    // TODO: js: uncategorized: finish this
                }
            },
            onInit() {
                this.loadProfile("add");
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

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const addBatchBtn = event.target;
            if (!addBatchBtn.matches(".addBatchBtn")) return;

            const cloneData = JSON.parse(addBatchBtn.value);

            const oldLastTrElm = itemWgt.querySelector(`tr._${cloneData.idKatalog}:last-of-type"`);
            cloneData.noUrut = oldLastTrElm.fields.noUrutStc.innerHTML + 1;
            oldLastTrElm.insertAdjacentHTML("afterend", trStr);

            const newLastTrElm = /** @type {HTMLTableRowElement} */ oldLastTrElm.nextElementSibling;
            addRow(newLastTrElm);
            loadDataPerRow(newLastTrElm, cloneData, 1000);
            newLastTrElm.fields.noBatchFld.dispatchEvent(new Event("focus"));

            const jumlahKemasan = cloneData.jumlahKemasan - 1;
            if (tabungGasYesOpt.checked && jumlahKemasan > 0) {
                closest(addBatchBtn, "tr").fields.jumlahItemStc.innerHTML = userInt(jumlahKemasan * cloneData.isiKemasan);
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBatchBtn = event.target;
            if (!deleteBatchBtn.matches(".deleteBatchBtn")) return;

            const val = "._"+deleteBatchBtn.value;
            closest(deleteBatchBtn, "tr").remove();

            itemWgt.querySelectorAll(val).forEach((/** @type {HTMLTableRowElement} */ trElm, idx) => {
                const fields = trElm.fields;
                fields.noUrutFld.value = idx + 1;
                fields.noUrutStc.innerHTML = idx + 1;
            });
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.ReturFarmasi.FormObat2.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        deleteBtn.addEventListener("click", () => {
            const cekSatuList = itemWgt.querySelectorAll(".cekSatuFld:checked");
            if (!cekSatuList.length || !confirm(str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>)) return;

            cekSatuList.forEach(item => closest(item, "tr").remove());
            sortNumber();
            hitungTotal();
        });

        printBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({xyz: kodeFld.value}, true); // TODO: js: uncategorized: finish this
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
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

        let tarikSebelumnya = "";
        tarikDenganFld.addEventListener("change", () => {
            const val = tarikDenganFld.value;
            const msg = str._<?= $h('Mengganti "Referensi Penarikan data" akan menghapus referensi yang sebelumnya dan semua item yang sudah ada. Apakah Anda yakin?') ?>;

            if ((kodeRefPlWgt.value || kodeRefDoWgt.value || kodeRefTerimaWgt.value) && !confirm(msg)) {
                tarikDenganFld.value = tarikSebelumnya;
                return;
            }

            kodeRefDoWgt.disable();
            kodeRefTerimaWgt.disable();
            kodeRefPlWgt.disable();

            switch (val) {
                case "do":  kodeRefDoWgt.enable(); break;
                case "trm": kodeRefTerimaWgt.enable(); break;
                case "spk": kodeRefPlWgt.enable(); break;
                default:    throw new Error("Wrong option");
            }
            tarikSebelumnya = val;
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(tambahReturFarmasiWgt, tanggalDokumenWgt, kodeRefPlWgt, kodeRefDoWgt);
        this._widgets.push(kodeRefTerimaWgt, idPemasokWgt, noDokumenWgt, itemWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tambahReturFarmasiWgt);
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
