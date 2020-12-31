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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Konsinyasi/proceed.php the original file
 */
final class FormProceed
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        array  $verifikasiTerimaAccess,
        array  $verifikasiGudangAccess,
        array  $verifikasiAkuntansiAccess,
        array  $otherAccess,
        string $dataUrl,
        string $addActionUrl,
        string $editActionUrl,
        string $verifikasiTerimaActionUrl,
        string $verifikasiGudangActionUrl,
        string $verifikasiAkuntansiActionUrl,
        string $otherActionUrl,
        string $konsinyasiUrl,
        string $cekUnikNoFakturUrl,
        string $cekUnikNoSuratJalanUrl,
        string $cekUnikNoDokumenUrl,
        string $pemasokAcplUrl,
        string $checkStockUrl,
        string $viewWidgetId,
        string $viewLainnyaWidgetId,
        string $subjenisAnggaranSelect,
        string $jenisHargaSelect,
        string $sumberDanaSelect,
        string $bulanSelect,
        string $tahunAnggaranSelect,
        string $caraBayarSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();

        $penerimaan = new \stdClass;
        $daftarRincianDetailKonsinyasi = [];
        if ($penerimaan->statusIdJenisAnggaran == '1') {
            $statusRevisi = '1';
        } elseif ($penerimaan->statusNoFaktur == '1') {
            $statusRevisi = '1';
        } elseif ($penerimaan->statusNoSuratJalan == '1') {
            $statusRevisi = '1';
        } elseif ($penerimaan->statusIdSumberDana == '1') {
            $statusRevisi = '1';
        } elseif ($penerimaan->statusIdPemasok == '1') {
            $statusRevisi = '1';
        } elseif ($penerimaan->statusIdJenisHarga == '1') {
            $statusRevisi = '1';
        } elseif ($penerimaan->statusIdCaraBayar == '1') {
            $statusRevisi = '1';
        } elseif ($penerimaan->statusPpn == '1') {
            $statusRevisi = '1';
        } else {
            $statusRevisi = '0';
        }

        $cekPpn = ($penerimaan->ppn == '0') ? '' : 'checked';

        $no = 1;
        $sebelumDiskon = 0.00;
        $diskon = 0.00;
        $setelahDiskon = 0.00;

        $xdata = [];
        foreach ($daftarRincianDetailKonsinyasi as $x => $rincianDetailKonsinyasi) {
            $jk = $rincianDetailKonsinyasi->jumlahResep / $rincianDetailKonsinyasi->isiKemasan;

            $xdata[$x]['jRt'] = $rincianDetailKonsinyasi->jumlahKonsinyasi - $rincianDetailKonsinyasi->jumlahResep; // Jumlah yang di retur ke pemasok
            $xdata[$x]['jk'] = $jk;

            $ht = $jk * $rincianDetailKonsinyasi->hargaKemasan;
            $dh = $ht * $rincianDetailKonsinyasi->diskonItem / 100;
            $ha = $ht - $dh;

            $xdata[$x] = [
                "ht" => $ht,
                "dh" => $dh,
                "ha" => $ha,
            ];

            $sebelumDiskon += $ht;
            $diskon += $dh;
            $setelahDiskon += $ha;

            if ($rincianDetailKonsinyasi->noUrut == '1' && $rincianDetailKonsinyasi->statusItem == '1') {
                $statusRevisi = '1';
            }
        }

        $ppn = ($penerimaan->ppn == 10)
            ? $setelahDiskon * $penerimaan->ppn / 100
            : 0.00;

        $subtotal = $setelahDiskon + $ppn;
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Konsinyasi.Proceed {
    export interface FormFields {
        action:                 "action",
        submit:                 "submit",
        statusRevisi:           "sts_revisi",
        tipeDokumen:            "tipe_doc",
        tipeKonsinyasi:         "tipe_konsinyasi",
        noTransaksi:            "kode",
        tanggalDokumen:         "tgl_doc",
        idJenisAnggaran:        "id_jenisanggaran",
        noDokumen:              "no_doc",
        bulanAwalAnggaran:      "blnawal_anggaran",
        bulanAkhirAnggaran:     "blnakhir_anggaran",
        tahunAnggaran:          "thn_anggaran",
        noFaktur:               "no_faktur",
        noSuratJalan:           "no_suratjalan",
        idSumberDana:           "id_sumberdana",
        idPemasok:              "id_pbf",
        idJenisHarga:           "id_jenisharga",
        idCaraBayar:            "id_carabayar",
        kodeRefKonsinyasi:      "kode_reffkons",
        tanggalRefKonsinyasi:   "___", // exist but missing
        idGudangPenyimpanan:    "id_gudangpenyimpanan",
        keterangan:             "keterangan",
        verifikasiKendaliHarga: "___", // exist but missing
        sebelumDiskon:          "___", // exist but missing
        diskon:                 "___", // exist but missing
        setelahDiskon:          "___", // exist but missing
        ppn:                    "ppn",
        subtotal:               "___", // exist but missing
        pembulatan:             "___", // exist but missing
        nilaiTotal:             "___", // exist but missing
        verifikasiTerima:       "ver_terima",
        verifikasiGudang:       "ver_gudang",
        verifikasiAkuntansi:    "ver_akuntansi",

        pemasokOpt:             PemasokFields;
        kodePemasok:            "___";
        namaPemasok:            "___";
    }

    export interface TableFields {
        kodeRefKons:       "kode_reffkons[]",
        noRefBatch:        "no_reffbatch[]",
        idKatalog:         "id_katalog[]",
        idPabrik:          "id_pabrik[]",
        kemasan:           "kemasan[]",
        idKemasanDepo:     "id_kemasandepo[]",
        noUrut:            "no_urut[]",
        jumlahKonsinyasi:  "jumlah_konsinyasi[]",
        jumlahRetur:       "jumlah_retur[]",
        hargaPokokItem:    "hp_item[]",
        hnaItem:           "hna_item[]",
        phjaItem:          "phja_item[]",
        hjaItem:           "hja_item[]",
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
        jumlahResep:       "___", // exist but missing
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
     * @returns {{add: boolean, edit: boolean, verifikasiTerima: boolean, verifikasiGudang: boolean, verifikasiAkuntansi: boolean, other: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            verifikasiTerima: JSON.parse(`<?=json_encode($verifikasiTerimaAccess) ?>`),
            verifikasiGudang: JSON.parse(`<?=json_encode($verifikasiGudangAccess) ?>`),
            verifikasiAkuntansi: JSON.parse(`<?=json_encode($verifikasiAkuntansiAccess) ?>`),
            other: JSON.parse(`<?=json_encode($otherAccess) ?>`),
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
            class: ".proceedFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"}, // $action
                    hidden_2: {class: ".submitFld", name: "submit"}, // FROM SUBMIT BUTTON      value=($action == 'kendali') ? 'kendali' : 'save'
                    hidden_3: {class: ".statusRevisiFld", name: "statusRevisi"}, // $statusRevisi
                    hidden_4: {class: ".tipeDokumenFld", name: "tipeDokumen", value: 4},
                    hidden_5: {class: ".tipeKonsinyasiFld", name: "tipeKonsinyasi"}, // $penerimaan->tipeKonsinyasi ?? ""
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".noTransaksiFld", name: "noTransaksi"}
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
                        label: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal anggaran") ?>,
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
                        label: tlm.stringRegistry._<?= $h("Faktur") ?>,
                        input: {class: ".noFakturFld", name: "noFaktur"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Surat Jalan") ?>,
                        input: {class: ".noSuratJalanFld", name: "noSuratJalan"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"}
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
                        label: tlm.stringRegistry._<?= $h("Kode Ref. Konsinyasi") ?>,
                        input: {class: ".noRefKonsinyasiFld"},
                        hidden: {class: ".kodeRefKonsinyasiFld", name: "kodeRefKonsinyasi"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Ref. Konsinyasi") ?>,
                        staticText: {class: ".tanggalRefKonsinyasiFld"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                        select: {
                            class: ".idGudangPenyimpananFld",
                            name: "idGudangPenyimpanan",
                            option: {value: 69, label: tlm.stringRegistry._<?= $h("Gudang Konsinyasi") ?>}
                        }
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi Kendali Harga") ?>,
                        staticText: {class: ".verifikasiKendaliHargaStc"}
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
                        label: tlm.stringRegistry._<?= $h("Subtotal") ?>,
                        staticText: {class: ".subtotalStc"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Nilai Total") ?>,
                        staticText: {class: ".nilaiTotalStc"}
                    }
                }
            },
            row_3: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr_1: {
                            td_1:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama barang") ?>},
                            td_3:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6:  {colspan: 5, text: tlm.stringRegistry._<?= $h("Pengadaan") ?>},
                            td_7:  {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9:  {colspan: 3, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_10: {colspan: 3, text: tlm.stringRegistry._<?= $h("Realisasi") ?>}
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
                            td_10: {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                            td_11: {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                            td_12: {text: tlm.stringRegistry._<?= $h("Resep") ?>},
                            td_13: {text: tlm.stringRegistry._<?= $h("Retur") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".kodeRefKonsFld", name: "kodeRefKons[]"},
                                hidden_2: {class: ".noRefBatchFld", name: "noRefBatch[]"},
                                hidden_3: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_4: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_5: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_6: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                                hidden_7: {class: ".noUrutFld", name: "noUrut[]"},
                                hidden_8: {class: ".jumlahKonsinyasiFld", name: "jumlahKonsinyasi[]"},
                                hidden_9: {class: ".jumlahReturFld", name: "jumlahRetur[]"},
                                hidden_10: {class: ".hargaPokokItemFld", name: "hargaPokokItem[]"},
                                hidden_11: {class: ".hnaItemFld", name: "hnaItem[]"},
                                hidden_12: {class: ".phjaItemFld", name: "phjaItem[]"},
                                hidden_13: {class: ".hjaItemFld", name: "hjaItem[]"},
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
                                select: {class: ".isiKemasanFld", name: "isiKemasan[]"}
                            },
                            td_6: {
                                staticText: {class: ".noUrutStc"},
                                rButton: {class: ".addBatchBtn"}
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
                            td_17: {class: ".jumlahKonsinyasiStc"},
                            td_18: {class: ".jumlahResepStc"},
                            td_19: {class: ".jumlahReturStc"},
                            td_20: {
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
                                checkbox: {class: ".verTerimaFld", name: "verifikasiTerima", value: 1} // $penerimaan->verTerima ?? ""  ||||| (isset($penerimaan->verTerima) && $penerimaan->verTerima == '1') ? 'disabled' : $verTrm
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Tim Penerima") ?>},
                            td_4: {class: ".userTerimaStc"},                                     // $penerimaan->userTerima ?? "-"
                            td_5: {class: ".tanggalTerimaStc"},                                  // $penerimaan->verTanggalTerima ? $toUserDatetime($penerimaan->verTanggalTerima) : "-"
                            td_6: {text: ""},
                        },
                        tr_2: {
                            td_1: {text: "2"},
                            td_2: {
                                checkbox: {class: ".verGudangFld", name: "verifikasiGudang", value: 1}    // $penerimaan->verGudang ?? "" |||| $verGdg
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Gudang") ?>},
                            td_4: {class: ".userGudangStc"},                                        // $penerimaan->userGudang ?? "-"
                            td_5: {class: ".tanggalGudangStc"},                                     // $penerimaan->verTanggalGudang ? $toUserDatetime($penerimaan->verTanggalGudang) : "-"
                            td_6: {
                                checkbox: {class: ".updateStokMarkerFld", value: 1, disabled: true} // $penerimaan->stokgudang ?? ""
                            },
                        },
                        tr_3: {
                            td_1: {text: "3"},
                            td_2: {
                                checkbox: {class: ".verAkuntansiFld", name: "verifikasiAkuntansi", value: 1} // $penerimaan->verAkuntansi ?? "" |||| $verAkun
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
        const {nowVal, toSystemNumber: sysNum, toUserMoney: currency, stringRegistry: str} = tlm;
        const userName = tlm.user.nama;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */      const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */    const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */    const noTransaksiFld = divElm.querySelector(".noTransaksiFld");
        /** @type {HTMLSelectElement} */   const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */   const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */   const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */   const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */   const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */   const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */   const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLInputElement} */    const kodeRefKonsinyasiFld = divElm.querySelector(".kodeRefKonsinyasiFld");
        /** @type {HTMLDivElement} */      const tanggalRefKonsinyasiFld = divElm.querySelector(".tanggalRefKonsinyasiFld");
        /** @type {HTMLSelectElement} */   const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLTextAreaElement} */ const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLDivElement} */      const verifikasiKendaliHargaStc = divElm.querySelector(".verifikasiKendaliHargaStc");
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
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $tahunAnggaranSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $subjenisAnggaranSelect ?>", idJenisAnggaranFld);
        this._selects.push(idCaraBayarFld, idJenisHargaFld, idSumberDanaFld, tahunAnggaranFld, bulanAkhirAnggaranFld, bulanAwalAnggaranFld, idJenisAnggaranFld);

        let tipeDokumen;

        const proceedWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".proceedFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Konsinyasi.Proceed.FormFields} data */
            loadData(data) {
                idPemasokWgt.addOption(data.pemasokOpt);

                noTransaksiFld.value = data.noTransaksi ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                noFakturWgt.value = data.noFaktur ?? "";
                noSuratJalanWgt.value = data.noSuratJalan ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                kodeRefKonsinyasiFld.value = data.kodeRefKonsinyasi ?? "";
                tanggalRefKonsinyasiFld.innerHTML = data.tanggalRefKonsinyasi ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                keteranganFld.value = data.keterangan ?? "";
                verifikasiKendaliHargaStc.innerHTML = data.verifikasiKendaliHarga ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                subtotalStc.innerHTML = data.subtotal ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiTotalStc.innerHTML = data.nilaiTotal ?? "";

                tipeDokumen = data.tipeDokumen;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add(data) {
                    this.load(data);
                    this._actionUrl = "<?= $addActionUrl ?>";
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;
                    // $inputTrm = 'readonly';
                    // $bSave = '';
                    // $bAction = '';
                    // $input = '';
                    // $verTrm = '';
                    // $verGdg = 'disabled';
                    // $verAkun = 'disabled';
                },
                edit(data) {
                    this.load(data);
                    this._actionUrl = "<?= $editActionUrl ?>";
                    formTitleTxt.innerHTML = str._<?= $h("Proses Verifikasi Penerimaan Barang Konsinyasi") ?>;
                    // $inputTrm = 'readonly';
                    // $bSave = '';
                    // $bAction = '';
                    // $input = '';
                    // $verTrm = '';
                    // $verGdg = 'disabled';
                    // $verAkun = 'disabled';

                    sebelumDiskonStc.innerHTML = currency("<?= $sebelumDiskon ?>");
                    diskonStc.innerHTML = currency("<?= $diskon ?>");
                    setelahDiskonStc.innerHTML = currency("<?= $setelahDiskon ?>");
                    ppnStc.innerHTML = currency("<?= $ppn ?>");
                    subtotalStc.innerHTML = currency("<?= $subtotal ?>");
                    pembulatanStc.innerHTML = currency("<?= round($subtotal) - $subtotal ?>");
                    nilaiTotalStc.innerHTML = currency("<?= round($subtotal) ?>");

                    let jumlahItem = 0;
                    itemWgt.querySelectorAll("tbody tr").forEach(/** @param {HTMLTableRowElement} item */ item => {
                        jumlahItem += sysNum(item.fields.jumlahItemStc.innerHTML);
                    });

                    if (jumlahItem == 0) {
                        alert(`
                            Tidak ada pemakaian Konsinyasi, atau Sudah dilakukan Penarikan
                            BA Penerimaan untuk no. Faktur atau Surat Jalan ini. Silahkan
                            periksa kembali atau pilih Faktur/Suratjalan yang benar.`
                        );
                        window.location = "<?= $konsinyasiUrl ?>";
                    }
                },
                verifikasiTerima(data) {
                    this.load(data);
                    this._actionUrl = "<?= $verifikasiTerimaActionUrl ?>";
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;
                    // $inputTrm = 'readonly';
                    // $bSave = '';
                    // $bAction = '';
                    // $input = '';
                    // $verTrm = '';
                    // $verGdg = 'disabled';
                    // $verAkun = 'disabled';
                },
                verifikasiGudang(data) {
                    this.load(data);
                    this._actionUrl = "<?= $verifikasiGudangActionUrl ?>";
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;
                    // $inputTrm = 'disabled';
                    // $bSave = '';
                    // $bAction = 'disabled';
                    // $input = 'disabled';
                    // $verTrm = 'disabled';
                    // $verGdg = '';
                    // $verAkun = 'disabled';
                },
                verifikasiAkuntansi(data) {
                    this.load(data);
                    this._actionUrl = "<?= $verifikasiAkuntansiActionUrl ?>";
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;
                    // $inputTrm = 'disabled';
                    // $bSave = '';
                    // $bAction = 'disabled';
                    // $input = 'disabled';
                    // $verTrm = 'disabled';
                    // $verGdg = 'disabled';
                    // $verAkun = '';
                },
                other(data) {
                    this.load(data);
                    this._actionUrl = "<?= $otherActionUrl ?>";
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;
                    // $inputTrm = 'disabled';
                    // $bSave = 'disabled';
                    // $bAction = 'disabled';
                    // $input = 'disabled';
                    // $verTrm = 'disabled';
                    // $verGdg = 'disabled';
                    // $verAkun = 'disabled';
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
            onBeforeSubmit() {
                let textConfirm = "";
                const action = actionFld.value;

                if (divElm.querySelector("[name=tipe_konsinyasi]").value == "1") {
                    textConfirm += 'This Konsinyasi is "Non Rutin" type, Create "BA Penerimaan" and "Verif Gudang" will Return Leftovers Items Automatically.';
                }

                textConfirm += "Are You sure to Save this Data?";

                if (action == "verifikasiTerima" || action == "add" || action == "edit") {
                    if (divElm.querySelector("[name=sts_revisi]").value == "1") {
                        textConfirm += "There are change to the data because of changing on data input or the Item was used, please correct, check and report to IT if there is a irrelevant data.";
                    }
                    return confirm(textConfirm);

                } else if (action == "verifikasiGudang") {
                    // JIka Ada perubahan data dari input konsinyasi atau penambahan pemakaian / resep
                    if (divElm.querySelector("[name=sts_revisi]").value == "1") {
                        alert(`
                            You can not do verification, because there are change to
                            the data because of changing on data input or the Item
                            was used. Contact the "TPBM" for correction the input.`
                        );
                        return false;
                    }

                    if (verGudangFld.checked) {
                        return confirm(textConfirm);
                    } else {
                        alert(str._<?= $h('You did not check "Ver Gudang", Make sure you have checked the checklist on "Ver Gudang"!') ?>);
                        return false;
                    }
                }

                alert(`
                    There are Problem with your Data, maybe the counting did not work well
                    or some thing wrong with the connection. Please Check it One more time!`
                );
                return false;
            },
            onSuccessSubmit(event) {
                const widgetId = tipeDokumen ? "_<?= $viewLainnyaWidgetId ?>" : "_<?= $viewWidgetId ?>";
                const widget = tlm.app.getWidget(widgetId);
                widget.show();
                widget.loadData({kode: event.data.kode, revisiKe: 0}, true);
            },
            onFailSubmit() {
                alert(str._<?= $h("terjadi error") ?>);
            },
            resetBtnId: false
        });

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

        const noFakturWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noFakturFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == noTransaksiFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoFakturUrl ?>",
            term: "value",
            additionalData: {field: "no_faktur"}
        });

        const noSuratJalanWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noSuratJalanFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == noTransaksiFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoSuratJalanUrl ?>",
            term: "value",
            additionalData: {field: "no_suratjalan"}
        });

        const noDokumenWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noDokumenFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == divElm.querySelector(".kodeFld").value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoDokumenUrl ?>",
            term: "value",
            additionalData() {
                return {kode: divElm.querySelector(".kodeFld").value}
            }
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Konsinyasi.Proceed.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Konsinyasi.Proceed.PemasokFields} item */
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

        ppnFld.addEventListener("change", hitungTotal);

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Konsinyasi.Proceed.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefKonsFld.value = data.kodeRefKons ?? "";
                fields.noRefBatchFld.value = data.noRefBatch ?? "";
                fields.idKatalogWgt.value = data.idKatalog ?? "";
                fields.idPabrikWgt.value = data.idPabrik ?? "";
                fields.kemasanFld.value = data.kemasan ?? "";
                fields.idKemasanDepoFld.value = data.idKemasanDepo ?? "";
                fields.noUrutFld.value = data.noUrut ?? "";
                fields.jumlahKonsinyasiFld.value = data.jumlahKonsinyasi ?? "";
                fields.jumlahReturFld.value = data.jumlahRetur ?? "";
                fields.hargaPokokItemFld.value = data.hargaPokokItem ?? "";
                fields.hnaItemFld.value = data.hnaItem ?? "";
                fields.phjaItemFld.value = data.phjaItem ?? "";
                fields.hjaItemFld.value = data.hjaItem ?? "";
                fields.namaSediaanStc.innerHTML = data.namaSediaan ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.isiKemasanWgt.value = data.isiKemasan ?? "";
                fields.noUrutStc.innerHTML = data.noUrut ?? "";
                fields.noBatchFld.value = data.noBatch ?? "";
                fields.tanggalKadaluarsaWgt.value = data.tanggalKadaluarsa ?? "";
                fields.jumlahKemasanWgt.value = data.jumlahKemasan ?? "";
                fields.jumlahItemStc.innerHTML = data.jumlahItem ?? "";
                fields.hargaKemasanWgt.value = data.hargaKemasan ?? "";
                fields.hargaItemStc.innerHTML = data.hargaItem ?? "";
                fields.diskonItemWgt.value = data.diskonItem ?? "";
                fields.hargaTotalStc.innerHTML = data.hargaTotal ?? "";
                fields.diskonHargaStc.innerHTML = data.diskonHarga ?? "";
                fields.hargaAkhirStc.innerHTML = data.hargaAkhir ?? "";
                fields.jumlahKonsinyasiStc.innerHTML = data.jumlahKonsinyasi ?? "";
                fields.jumlahResepStc.innerHTML = data.jumlahResep ?? "";
                fields.jumlahReturStc.innerHTML = data.jumlahRetur ?? "";
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
                    diskonItemWgt,
                    tanggalKadaluarsaWgt,
                    kodeRefKonsFld: trElm.querySelector(".kodeRefKonsFld"),
                    noRefBatchFld: trElm.querySelector(".noRefBatchFld"),
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    jumlahKonsinyasiFld: trElm.querySelector(".jumlahKonsinyasiFld"),
                    jumlahReturFld: trElm.querySelector(".jumlahReturFld"),
                    hargaPokokItemFld: trElm.querySelector(".hargaPokokItemFld"),
                    hnaItemFld: trElm.querySelector(".hnaItemFld"),
                    phjaItemFld: trElm.querySelector(".phjaItemFld"),
                    hjaItemFld: trElm.querySelector(".hjaItemFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    noUrutStc: trElm.querySelector(".noUrutStc"),
                    noBatchFld: trElm.querySelector(".noBatchFld"),
                    jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                    hargaItemStc: trElm.querySelector(".hargaItemStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                    hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                    jumlahKonsinyasiStc: trElm.querySelector(".jumlahKonsinyasiStc"),
                    jumlahResepStc: trElm.querySelector(".jumlahResepStc"),
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
            addRowBtn: ".proceedWgt .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const stokBtn = event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            $.post({
                url: "<?= $checkStockUrl ?>",
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

            const trElm = closest(addBatchBtn, "tr");

            // TODO: js: missing function: addBatchitem()
            addBatchitem(trElm, trElm.id);
        });

        /** @see {his.FatmaPharmacy.views.Konsinyasi.Proceed.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
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
        this._widgets.push(proceedWgt, noFakturWgt, noSuratJalanWgt, noDokumenWgt, tanggalDokumenWgt, idPemasokWgt, itemWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, proceedWgt);
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
