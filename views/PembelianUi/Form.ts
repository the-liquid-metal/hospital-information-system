<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PembelianUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/add.php the original file
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
        string $cekUnikNoDokumenUrl,
        string $pemasokAcplUrl,
        string $pengadaanUrl,
        string $perencanaanUrl,
        string $katalogAcplUrl,
        string $detailHpsUrl,
        string $detailHargaUrl,
        string $viewWidgetId,
        string $jenisAnggaranSelect,
        string $bulanSelect,
        string $tahunAnggaranSelect,
        string $sumberDanaSelect,
        string $jenisHargaSelect,
        string $caraBayarSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $data = new \stdClass;

        $no = 1;
        $nt = 0.00;
        $nd = 0.00;
        $nTd = 0.00;

        $xdata = [];
        foreach ($idata ?? [] as $x => $d) {
            $ht = $d->jumlahKemasan * $d->hargaKemasan;
            $dh = $ht * $d->diskonItem / 100;
            $ha = $ht - $dh;

            $xdata[$x] = [
                "ht" => $ht,
                "dh" => $dh,
                "ha" => $ha,
            ];

            $nt += $ht;
            $nd += $dh;
            $nTd += $ha;

            $xdata[$x]['jMax'] = ($d->jumlahHps or $d->jumlahRencana)
                ? $d->jumlahHps - $d->jumlahTerima + $d->jumlahRetur
                : '-';

            // Jika Isi kemasan di katalog != 1, maka tambahkan ke option
            if ($d->isiKemasanKat != 1) { // jika isi kemasan katalog bukan 1

                // jika isi kemasan yg dipilih sama dengan isi kemasan dari katalog
                $xdata[$x]['sel2'] = ($d->isiKemasan == $d->isiKemasanKat) ? 'selected' : "";
                $xdata[$x]['hk_k'] = ($d->isiKemasan == $d->isiKemasanKat) ? $d->hargaKemasan : $d->hargaKemasanKat; // Jika isi kemasan di katalog sama dengan yang direncanakan, gunakan harga dari perencanaan

                $isK = ($d->isiKemasanKat == floor($d->isiKemasanKat)) ? floor($d->isiKemasanKat) : $d->isiKemasanKat;
                $xdata[$x]['km_k'] = $d->satuanJualKat . ' ' . $isK . ' ' . $d->satuanKat;
            }
        }

        $np = ($data->ppn == 10) ? ($nTd * $data->ppn / 100) : 0.00;

        $nTp = $nTd + $np;
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pembelian.Form {
    export interface FormFields {
        createNew:           "create_new";
        kode:                "kode";
        tanggalDokumen:      "tgl_doc";
        tanggalJatuhTempo:   "tgl_jatuhtempo";
        noDokumen:           "no_doc";
        idJenisAnggaran:     "id_jenisanggaran";
        idPemasok:           "id_pbf";
        bulanAwalAnggaran:   "blnawal_anggaran";
        bulanAkhirAnggaran:  "blnakhir_anggaran";
        tahunAnggaran:       "thn_anggaran";
        kodeRefHps:          string;
        idSumberDana:        "id_sumberdana";
        kodeRefPerencanaan:  string;
        idJenisHarga:        "id_jenisharga";
        idCaraBayar:         "id_carabayar";
        tipeDokumen:         "tipe_doc";
        sebelumDiskon:       string;
        diskon:              string;
        setelahDiskon:       string;
        ppn:                 string;
        setelahPpn:          string;
        pembulatan:          string;
        nilaiAkhir:          string;

        pemasokOpt?:         PemasokFields;
        kodePemasok?:        "kode_pbf";
        namaPemasok?:        "nama_pbf";

        refHpsOpt?:          RefHpsFields;
    }

    export interface TableFields {
        kodeRef:          "kode_reff";
        kodeRefHps:       "kode_reffhps";
        kodeRefRencana:   "kode_reffrenc";
        noDokumenRencana: "no_docrenc";
        idRefKatalog:     "id_reffkatalog";
        idKatalog:        "id_katalog";
        idPabrik:         "id_pabrik";
        kemasan:          "kemasan";
        idKemasanDepo:    "id_kemasandepo";
        noUrut:           "no_urut";
        namaSediaan:      string;
        namaPabrik:       string;
        idKemasan:        "id_kemasan";
        isiKemasan:       "isi_kemasan";
        jumlahKemasan:    "jumlah_kemasan";
        jumlahItem:       string;
        hargaKemasan:     "harga_kemasan";
        hargaItem:        string;
        hargaTotal:       string;
        diskonItem:       "diskon_item";
        diskonHarga:      string;
        hargaAkhir:       string;
        jumlahRencana:    string;
        jumlahHps:        string;
        jumlahPl:         string;
        jumlahRo:         string;
        jumlahDo:         string;
        jumlahBonus:      string;
        jumlahTerima:     string;
        jumlahRetur:      string;
    }

    export interface Ajax1Fields {
        idKatalog:        "id_katalog";
        jumlahTerima:     "jumlah_trm";
        jumlahRetur:      "jumlah_ret";
        hargaItem:        "harga_item";
        hargaKemasan:     "harga_kemasan";
        isiKemasan:       "isi_kemasan";
        diskonItem:       "diskon_item";
        jumlahItem:       "jumlah_item";
        isiKemasanKat:    "isi_kemasankat";
        satuan:           "satuan";
        satuanJual:       "satuanjual";
        idKemasanDepo:    "id_kemasandepo";
        satuanJualKat:    "satuanjualkat";
        satuanKat:        "satuankat";
        idKemasanKat:     "id_kemasankat";
        idKemasanDepoKat: "id_kemasandepokat";
        idKemasan:        "id_kemasan";
        kodeRefHps:       "kode_reffhps";
        kodeRefRencana:   "kode_reffrenc";
        noDokumenRencana: "no_docrenc";
        idPabrik:         "id_pabrik";
        namaSediaan:      "nama_sediaan";
        namaPabrik:       "nama_pabrik";
        jumlahRencana:    "jumlah_renc";
        jumlahHps:        "jumlah_hps";
        jumlahPl:         "jumlah_pl";
        jumlahDo:         "jumlah_do";
        jumlahBonus:      "jumlah_bns";
    }

    export interface Data2Fields {
        kodeRefRencana:   "kode_reffrenc";
        noDokumenRencana: "no_docrenc";
    }

    export interface Data1Fields {
        idPemasok:             "id_pbf";
        kodePemasok:           "kode_pbf";
        namaPemasok:           "nama_pbf";
        kodeRefHps:            "kode_reffhps";
        noHps:                 "no_hps";
        jenisObatHps:          "jenis_obat_hps";
        jenisHargaHps:         "jenis_harga_hps";
        namaPemasokHps:        "nama_pbf_hps";
        bulanAwalAnggaranHps:  "blnawal_anggaran_hps";
        bulanAkhirAnggaranHps: "blnakhir_anggaran_hps";
        tahunAnggaranHps:      "thn_anggaran_hps";
        noDokumenRef:          "no_docreff";
        noDokumenRencana:      "no_docrenc";
    }

    export interface KatalogFields {
        isiKemasan:    string;
        satuan:        string;
        satuanJual:    string;
        idKatalog:     string;
        namaSediaan:   string;
        namaPabrik:    string;
        hargaItem:     string;
        hargaKemasan:  string;
        diskonItem:    string;
        idKemasanDepo: string;
        idKemasan:     string;
        idPabrik:      string;
        jumlahItem:    string; // not used, but exist in controller
    }

    export interface RefPerencanaanFields {
        kode:               "kode";
        noDokumen:          "no_doc";
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        subjenisAnggaran:   "subjenis_anggaran";
        nilaiAkhir:         "nilai_akhir";
    }

    export interface RefHpsFields {
        id:                 "id";
        subjenisAnggaran:   "subjenis_anggaran";
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        nilaiAkhir:         "nilai_akhir";
        noDokumen:          "no_doc";
        kode:               "kode";
        action:             "act";
        idJenisAnggaran:    "id_jenisanggaran";
        idSumberDana:       "id_sumberdana";
        idJenisHarga:       "id_jenisharga";
        idCaraBayar:        "id_carabayar";
        ppn:                "ppn";
        idPemasok:          "id_pbf";
        kodePemasok:        "kode_pbf";
        namaPemasok:        "nama_pbf";
        kodeRefRencana:     "kode_reffrenc";
    }

    export interface PemasokFields {
        id:   string;
        kode: string;
        nama: string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".pembelianFrm",
            hidden: {class: ".createNewFld", name: "createNew"},
            row_1: {
                widthColumn: {
                    button_1: {class: "btn-tarik", title: tlm.stringRegistry._<?= $h("Tarik Item HPS/ Perencanaan") ?>, text: tlm.stringRegistry._<?= $h("Tarik") ?>},
                    button_2: {class: "btn-print", title: tlm.stringRegistry._<?= $h("Print Perencanaan") ?>,           text: tlm.stringRegistry._<?= $h("Print") ?>}, // $bPrint   $printWidgetId/.$kode
                    button_3: {                    title: tlm.stringRegistry._<?= $h("Back To Index") ?>,               text: tlm.stringRegistry._<?= $h("Back") ?>}, // $tableWidgetId
                }
            },
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden: {class: ".actionFld", name: "action"}, // value:$action
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        input: {class: ".tanggalJatuhTempoFld", name: "tanggalJatuhTempo"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {class: ".idPemasokFld", name: "idPemasok"},
                        hidden_1: {class: ".kodePemasokFld", name: "kodePemasok"},
                        hidden_2: {class: ".namaPemasokFld", name: "namaPemasok"},
                        rButton: {class: ".clearPemasokBtn", icon: "eraser"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Ref. HPS") ?>,
                        input: {class: ".kodeRefHpsFld"},
                        rButton: {class: ".clearReferensiHpsBtn", icon: "eraser"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Ref. Perencanaan") ?>,
                        input: {class: ".kodeRefPerencanaanFld"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Jenis Pembelian") ?>,
                        select: {
                            class: ".tipeDokumenFld",
                            name: "tipeDokumen",
                            option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Kontrak Harga") ?>},
                            option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Kontrak") ?>},
                            option_3: {value: 2, label: tlm.stringRegistry._<?= $h("(SPK) Surat Perintah Kerja") ?>},
                            option_4: {value: 3, label: tlm.stringRegistry._<?= $h("(SP) Surat Pesanan") ?>}
                        }
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
                        checkbox: {class: ".ppnFld"},
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
            row_3: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr_1: {
                            td_1:  {rowspan: 2, class: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {rowspan: 2, class: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3:  {rowspan: 2, class: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4:  {rowspan: 2, class: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  {rowspan: 2, class: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6:  {colspan: 2, class: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                            td_7:  {colspan: 2, class: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8:  {rowspan: 2, class: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9:  {colspan: 3, class: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_10: {colspan: 8, class: tlm.stringRegistry._<?= $h("Realisasi") ?>}
                        },
                        tr_2: {
                            td_1:  {class: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_2:  {class: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_3:  {class: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_4:  {class: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_5:  {class: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>},
                            td_6:  {class: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_7:  {class: tlm.stringRegistry._<?= $h("Rp.") ?>},
                            td_8:  {class: tlm.stringRegistry._<?= $h("Rencana") ?>},
                            td_9:  {class: tlm.stringRegistry._<?= $h("HPS") ?>},
                            td_10: {class: tlm.stringRegistry._<?= $h("SPK") ?>},
                            td_11: {class: tlm.stringRegistry._<?= $h("RO") ?>},
                            td_12: {class: tlm.stringRegistry._<?= $h("DO") ?>},
                            td_13: {class: tlm.stringRegistry._<?= $h("Bonus") ?>},
                            td_14: {class: tlm.stringRegistry._<?= $h("Terima") ?>},
                            td_15: {class: tlm.stringRegistry._<?= $h("Retur") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".kodeRefFld", name: "kodeRef[]"},
                                hidden_2: {class: ".kodeRefHpsFld", name: "kodeRefHps[]"},
                                hidden_3: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]"},
                                hidden_4: {class: ".noDokumenRencanaFld", name: "noDokumenRencana[]"},
                                hidden_5: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_6: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_7: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_8: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_9: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                                hidden_10: {class: ".noUrutFld", name: "noUrut[]"},
                                staticText: {class: ".no"}
                            },
                            td_2: {
                                staticText: {class: ".namaSediaanStc"},
                                rButton: {class: ".changeBtn"}
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
                            td_17: {class: ".jumlahRoStc"},
                            td_18: {class: ".jumlahDoStc"},
                            td_19: {class: ".jumlahBonusStc"},
                            td_20: {class: ".jumlahTerimaStc"},
                            td_21: {class: ".jumlahReturStc"},
                            td_22: {
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
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>} // $bSave
                }
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {drawTr, drawSelect} = spl.TableDrawer;
        const {toCurrency: currency, preferInt, toUserInt: userInt, toUserFloat: userFloat} = tlm;
        const {toSystemNumber: sysNum, numToShortMonthName: nToS, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const createNewFld = divElm.querySelector(".createNewFld");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLInputElement} */  const kodePemasokFld = divElm.querySelector(".kodePemasokFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLButtonElement} */ const clearReferensiHpsBtn = divElm.querySelector(".clearReferensiHpsBtn");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLSelectElement} */ const tipeDokumenFld = divElm.querySelector(".tipeDokumenFld");
        /** @type {HTMLDivElement} */    const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */    const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */    const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLInputElement} */  const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */    const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLDivElement} */    const setelahPpnStc = divElm.querySelector(".setelahPpnStc");
        /** @type {HTMLDivElement} */    const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */    const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunAnggaranSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        let idJenisAnggaranSebelumnya;
        let idJenisHargaSebelumnya;

        const pembelianWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".pembelianFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Pembelian.Form.FormFields} data */
            loadData(data) {
                idPemasokWgt.addOption(data.pemasokOpt);
                kodeRefHpsWgt.addOption(data.refHpsOpt);

                const idata = JSON.parse(`<?= json_encode($idata ?? []) ?>`);
                idata.forEach(obj => {
                    if (!obj.objRenc) return;
                    kodeRefPerencanaanWgt.addOption(obj.objRenc);
                    kodeRefPerencanaanWgt.addItem(obj.objRenc.kode);
                });

                createNewFld.value = data.createNew ?? "";
                kodeFld.value = data.kode ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                tanggalJatuhTempoWgt.value = data.tanggalJatuhTempo ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
                kodePemasokFld.value = data.kodePemasok ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                kodeRefHpsWgt.value = data.kodeRefHps ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                kodeRefPerencanaanWgt.value = data.kodeRefPerencanaan ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                tipeDokumenFld.value = data.tipeDokumen ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";

                idJenisAnggaranSebelumnya = data.idJenisAnggaran;
                idJenisHargaSebelumnya = data.idJenisHarga;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    // $cekPpn = 'checked';
                    // $kode = '';
                    // $kontrakharga = '';
                    // $kontrak = '';
                    // $spk = 'checked';
                    // $sp = '';
                    // $bPrint = 'disabled';
                    // $bReffHps = '';
                    // $bSave = 'disabled';
                    // $cekOne = 'disabled';
                    // $item = 'disabled';
                    // $kodeReffHps = '';
                    // $kodeReffRencana = 'disabled';
                },
                edit() {
                    // $cekPpn = !$data->ppn ? '' : 'checked';
                    // $kode = $data->kode;
                    //
                    // if ($data->tipeDokumen == '0') {
                    //     $kontrakharga = 'checked';
                    //     $kontrak = '';
                    //     $spk = '';
                    //     $sp = '';
                    //
                    // } elseif ($data->tipeDokumen == '1') {
                    //     $kontrakharga = '';
                    //     $kontrak = 'checked';
                    //     $spk = '';
                    //     $sp = '';
                    //
                    // } elseif ($data->tipeDokumen == '2') {
                    //     $kontrakharga = '';
                    //     $kontrak = '';
                    //     $spk = 'checked';
                    //     $sp = '';
                    //
                    // } elseif ($data->tipeDokumen == '3') {
                    //     $kontrakharga = '';
                    //     $kontrak = '';
                    //     $spk = '';
                    //     $sp = 'checked';
                    // } else {
                    //     $kontrakharga = '';
                    //     $kontrak = '';
                    //     $spk = '';
                    //     $sp = '';
                    // }
                    //
                    // $bPrint = '';
                    // $bReffHps = 'disabled';
                    // $bSave = '';
                    // $cekOne = '';
                    // $item = '';
                    // $kodeReffHps = 'disabled';
                    // $kodeReffRencana = '';
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
                widget.show();
                widget.loadData({kode: kodeFld.value}, true);
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
            setelahPpnStc.innerHTML = currency(setelahPpn);
            pembulatanStc.innerHTML = currency(pembulatan);
            nilaiAkhirStc.innerHTML = currency(nilaiAkhir);

            const mapHarga = [
                [          0,       50_000_000, 3],
                [ 50_000_000,      200_000_000, 2],
                [200_000_000, Number.MAX_VALUE, 1]
            ];
            let val = 0;
            for (let i = 0, mapLength = mapHarga.length; i < mapLength; i++) {
                const [batasBawah, batasAtas, tipe] = mapHarga[i];
                if (batasBawah < nilaiAkhir && nilaiAkhir <= batasAtas) {
                    val = tipe;
                }
            }
            tipeDokumenFld.value = val;
        }

        idJenisHargaFld.addEventListener("change", () => {
            const idJenisHarga = idJenisHargaFld.value;
            const kodeRefRencana = kodeRefPerencanaanWgt.value;

            if (idJenisHarga == "2") {
                if (clearReferensiHpsBtn.dispatchEvent(new Event("click"))) {
                    ppnFld.checked = false;
                    divElm.querySelector("#b_reffhps").disabled = true;

                    kodeRefPerencanaanWgt.enable();
                    kodeRefHpsWgt.disable();
                    idJenisHargaSebelumnya = idJenisHarga;
                    return;
                }
            } else {
                // jika jenis harga sebelumnya e-katalog && mau diganti ke yg lain akan menghapus list & reff perencanaan
                if (idJenisHargaSebelumnya == 2) {
                    if (kodeRefRencana && confirm(str._<?= $h('Change "Jenis Harga" from "E-KATALOG" will delete the "Reff Perencanaan" and List item before. And you must Pull-up again the Items, based on "Reff Perencanaan". Are you sure to change this?') ?>)) {
                        itemWgt.reset();

                        createNewFld.value = "0";
                        kodeRefPerencanaanWgt.clearCache();
                        kodeRefPerencanaanWgt.clearOptions();
                        kodeRefPerencanaanWgt.disable();

                        kodeRefHpsWgt.enable();
                        divElm.querySelector("#b_reffhps").disabled = false;
                        ppnFld.checked = true;
                        idJenisHargaSebelumnya = idJenisHarga;
                        sortNumber();
                        hitungTotal();
                        return;

                    } else if (kodeRefRencana == "") {
                        createNewFld.value = "0";
                        kodeRefPerencanaanWgt.clearCache();
                        kodeRefPerencanaanWgt.clearOptions();
                        kodeRefPerencanaanWgt.disable();

                        kodeRefHpsWgt.enable();
                        divElm.querySelector("#b_reffhps").disabled = false;
                        ppnFld.checked = true;
                        idJenisHargaSebelumnya = idJenisHarga;
                        hitungTotal();
                        return;
                    }
                }
            }

            idJenisHargaFld.value = idJenisHargaSebelumnya;
        });

        idJenisAnggaranFld.addEventListener("change", (event) => {
            if (kodeRefPerencanaanWgt.value != "") {
                if (confirm(str._<?= $h('Mengubah "Mata Anggaran" akan menghapus semua "Ref. Perencanaan" dan daftar item. Apakah Anda yakin ingin mengubah?') ?>)) {
                    clearReferensiHpsBtn.dispatchEvent(new Event("click"));
                } else {
                    event.target.value = idJenisAnggaranSebelumnya; // added parenthesis (edit)
                }
            }
            idJenisAnggaranSebelumnya = idJenisAnggaranFld.value;
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

        const tanggalJatuhTempoWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalJatuhTempoFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
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
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.Pembelian.Form.PemasokFields} data
             */
            assignPairs(formElm, data) {
                kodePemasokFld.value = data.kode ?? "";
                namaPemasokFld.value = data.nama ?? "";
            },
            /** @param {his.FatmaPharmacy.views.Pembelian.Form.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Pembelian.Form.PemasokFields} item */
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

        const kodeRefHpsWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefHpsFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            createNew: true,
            /** @param {his.FatmaPharmacy.views.Pembelian.Form.RefHpsFields} item */
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
            /** @param {his.FatmaPharmacy.views.Pembelian.Form.RefHpsFields} item */
            itemRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                return `<div class="item"><b>${item.noDokumen} (${anggaran1})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pengadaanUrl ?>",
                    data: {noDokumen: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.Pembelian.Form.RefHpsFields} */
                const obj = this.options[value];

                if (obj.kode == obj.noDokumen) {
                    createNewFld.value = "1";
                    kodeRefPerencanaanWgt.enable();
                    kodeRefPerencanaanWgt.dispatchEvent(new Event("focus"));

                } else {
                    createNewFld.value = "0";
                    kodeRefPerencanaanWgt.clearCache();
                    kodeRefPerencanaanWgt.clearOptions();
                    kodeRefPerencanaanWgt.disable();

                    if (obj.action) return;

                    idJenisAnggaranFld.value = obj.idJenisAnggaran;
                    bulanAwalAnggaranFld.value = obj.bulanAwalAnggaran;
                    bulanAkhirAnggaranFld.value = obj.bulanAkhirAnggaran;
                    tahunAnggaranFld.value = obj.tahunAnggaran;
                    idSumberDanaFld.value = obj.idSumberDana;
                    idJenisHargaFld.value = obj.idJenisHarga;
                    idCaraBayarFld.value = obj.idCaraBayar;

                    ppnFld.checked = (obj.ppn == 10);

                    // set data pbf
                    if (obj.idPemasok && obj.objPbf) {
                        idPemasokWgt.addOption(obj.objPbf);
                        idPemasokWgt.value = obj.objPbf.id;
                    }

                    // select item hps
                    if (!obj.action) {
                        divElm.querySelector(".btn-tarik").dispatchEvent(new Event("click"));
                    }

                    $.post({
                        url: "<?= $perencanaanUrl ?>",
                        data: {kode: obj.kodeRefRencana},
                        success(data) {
                            if (!data[0]) return;

                            data[1].forEach(obj => {
                                if (!obj.objRenc) return;
                                kodeRefPerencanaanWgt.addOption(obj.objRenc);
                                kodeRefPerencanaanWgt.value = obj.objRenc.kode;
                            });
                        }
                    });
                }
            },
            onItemRemove() {
                const trList = itemWgt.querySelectorAll("tbody tr");

                if (trList.length) {
                    if (confirm(str._<?= $h("Menghapus Ref. HPS akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?") ?>)) {
                        trList.forEach(trElm => {
                            trElm.remove();
                            if (actionFld.value == "edit") {
                                divElm.querySelector(".delete").insertAdjacentHTML("beforeend", `<input type="hidden" name="deleteKatalog[]" value="${trElm.id}"/>`);
                            }
                        });
                        sortNumber();
                        hitungTotal();
                    } else {
                        return false;
                    }
                }

                createNewFld.value = "0";
                kodeRefPerencanaanWgt.clearCache();
                kodeRefPerencanaanWgt.clearOptions();
                kodeRefPerencanaanWgt.disable();
            }
        });

        clearReferensiHpsBtn.addEventListener("click", () => {
            if (!kodeRefHpsWgt.value) return;
            if (!confirm(str._<?= $h("Menghapus Ref. HPS akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?") ?>)) return;

            itemWgt.querySelectorAll("tbody tr").forEach(trElm => {
                trElm.remove();
                if (actionFld.value == "edit") {
                    divElm.querySelector(".delete").insertAdjacentHTML("beforeend", `<input type="hidden" name="deleteKatalog[]" value="${trElm.id}"/>`);
                }
            });

            sortNumber();
            hitungTotal();

            createNewFld.value = "0";
            kodeRefPerencanaanWgt.clearCache();
            kodeRefPerencanaanWgt.clearOptions();
            kodeRefPerencanaanWgt.disable();

            kodeRefHpsWgt.clearOptions();
            kodeRefHpsWgt.clearCache();
        });

        divElm.querySelector(".clearPemasokBtn").addEventListener("click", () => {
            idPemasokWgt.clearOptions();
            idPemasokWgt.clearCache();
        });

        const kodeRefPerencanaanWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPerencanaanFld"),
            maxItems: 10,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pembelian.Form.RefPerencanaanFields} item */
            optionRenderer(item) {
                const awal = item.bulanAwalAnggaran;
                const akhir = item.bulanAkhirAnggaran;
                const tahun = item.tahunAnggaran;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;

                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-3"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-3"><b>${item.subjenisAnggaran}</b></div>
                        <div class="col-xs-3">${anggaran}</div>
                        <div class="col-xs-3">${preferInt(item.nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Pembelian.Form.RefPerencanaanFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.subjenisAnggaran})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $perencanaanUrl ?>",
                    data: {
                        noDokumen: typed,
                        statusDeleted: 0,
                        statusClosed: 0,
                        tipeDokumen: "0,1,2",
                        idJenisAnggaran: idJenisAnggaranFld.value
                    },
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemRemove(values) {
                const val = values[0];
                const elm = divElm.querySelector(`.kodeRefRencanaFld[value="${val}"]`);
                if (!elm.length || !confirm(str._<?= $h("Menghapus Ref. Perencanaan akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?") ?>)) return;

                closest(elm, "tr").remove();
                sortNumber();
                hitungTotal();
            }
        });

        ppnFld.addEventListener("change", hitungTotal);

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Pembelian.Form.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefFld.value = data.kodeRef ?? "";
                fields.kodeRefHpsFld.value = data.kodeRefHps ?? "";
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.noDokumenRencanaFld.value = data.noDokumenRencana ?? "";
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogWgt.value = data.idKatalog ?? "";
                fields.idPabrikWgt.value = data.idPabrik ?? "";
                fields.kemasanFld.value = data.kemasan ?? "";
                fields.idKemasanDepoFld.value = data.idKemasanDepo ?? "";
                fields.noUrutFld.value = data.noUrut ?? "";
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
                fields.jumlahRoStc.innerHTML = data.jumlahRo ?? "";
                fields.jumlahDoStc.innerHTML = data.jumlahDo ?? "";
                fields.jumlahBonusStc.innerHTML = data.jumlahBonus ?? "";
                fields.jumlahTerimaStc.innerHTML = data.jumlahTerima ?? "";
                fields.jumlahReturStc.innerHTML = data.jumlahRetur ?? "";
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

                const jumlahKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahKemasanFld"),
                    errorRules: [{greaterThan: 0}],
                    ...tlm.intNumberSetting
                });

                trElm.fields = {
                    idKatalogWgt,
                    idPabrikWgt,
                    hargaKemasanWgt,
                    isiKemasanWgt,
                    diskonItemWgt,
                    jumlahKemasanWgt,
                    kodeRefFld: trElm.querySelector(".kodeRefFld"),
                    kodeRefHpsFld: trElm.querySelector(".kodeRefHpsFld"),
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    noDokumenRencanaFld: trElm.querySelector(".noDokumenRencanaFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                    hargaItemStc: trElm.querySelector(".hargaItemStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                    hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                    jumlahRencanaStc: trElm.querySelector(".jumlahRencanaStc"),
                    jumlahHpsStc: trElm.querySelector(".jumlahHpsStc"),
                    jumlahPlStc: trElm.querySelector(".jumlahPlStc"),
                    jumlahRoStc: trElm.querySelector(".jumlahRoStc"),
                    jumlahDoStc: trElm.querySelector(".jumlahDoStc"),
                    jumlahBonusStc: trElm.querySelector(".jumlahBonusStc"),
                    jumlahTerimaStc: trElm.querySelector(".jumlahTerimaStc"),
                    jumlahReturStc: trElm.querySelector(".jumlahReturStc"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.idKatalogWgt.destroy();
                fields.idPabrikWgt.destroy();
                fields.hargaKemasanWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.diskonItemWgt.destroy();
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
            addRowBtn: ".pembelianFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const changeBtn = event.target;
            if (!changeBtn.matches(".changeBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin mengubah item ini?") ?>)) return;

            const trElm = closest(changeBtn, "tr");
            const fields = trElm.fields;

            const idRefKatalog = fields.idRefKatalogFld.value;

            const i = divElm.querySelectorAll(`.idRefKatalogFld[value="${idRefKatalog}"]`).length;
            if (i > 1 || divElm.querySelector("select").contains("nb-ganti")) return;

            const trStr = drawTr("tbody", {
                class: "tr-data info",
                tr_1: {
                    hidden_1: {class: ".kodeRefHpsFld" name: "kodeRefHps[]", value: fields.kodeRefHpsFld.value},
                    hidden_2: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]", value: fields.kodeRefRencanaFld.value},
                    hidden_3: {class: ".noDokumenRencanaFld", name: "noDokumenRencana[]", value: fields.noDokumenRencanaFld.value},
                    hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idRefKatalog},
                    hidden_5: {class: ".noUrutFld", name: "noUrut[]", value: i + 1},
                    hidden_6: {class: ".idKatalogFld", name: "idKatalog[]", value: "-"},
                    hidden_7: {class: ".idPabrikFld", name: "idPabrik[]"},
                    hidden_8: {class: ".kemasanFld", name: "kemasan[]"},
                    hidden_9: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                },
                tr_2: {
                    select: {class: "nb-ganti"}, // ??? encapsulate with <label class="nb">
                    button: {class: "btn-delchange", text: str._<?= $h("Hapus Katalog") ?>}
                },
                tr_3: {class: ".namaPabrikStc"},
                tr_4: {
                    select: {class: "idKemasanFld", name: "idKemasan[]", readonly: true}
                },
                tr_5: {
                    input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: 1, readonly: true}
                },
                tr_6: {
                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: 0, readonly: true}
                },
                tr_7: {class: ".jumlahItemStc"},
                tr_8: {
                    input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: 0, readonly: true}
                },
                tr_9: {class: ".hargaItemStc"},
                tr_10: {
                    input: {class: ".diskonItemFld", name: "diskonItem[]", value: 0, readonly: true}
                },
                tr_11: {class: ".hargaTotalStc"},
                tr_12: {class: ".diskonHargaStc"},
                tr_13: {class: ".hargaAkhirStc"},
                tr_14: {colspan: 8},
            });
            trElm.insertAdjacentHTML("afterend", trStr);
            hitungSubTotal(/** @type {HTMLTableRowElement} */ trElm.nextElementSibling);

            const katalogFld = divElm.querySelector(".nb-ganti");
            new spl.SelectWidget({
                element: katalogFld,
                maxItems: 1,
                valueField: "idKatalog",
                searchField: ["idKatalog", "namaSediaan"],
                /** @param {his.FatmaPharmacy.views.Pembelian.Form.KatalogFields} item */
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
                /** @param {his.FatmaPharmacy.views.Pembelian.Form.KatalogFields} item */
                itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.namaSediaan})</div>`},
                load(typed, processor) {
                    if (!typed.length) {
                        processor([]);
                        return;
                    }

                    const idJenis = idJenisAnggaranFld.selectedOptions[0].getAttribute("id_jenis");
                    $.post({
                        url: "<?= $katalogAcplUrl ?>",
                        data: {query: typed, idJenis},
                        error() {processor([])},
                        success(data) {processor(data)}
                    });
                },
                onItemAdd(value) {
                    /** @type {his.FatmaPharmacy.views.Pembelian.Form.KatalogFields} */
                    const obj = this.options[value];
                    const {isiKemasan, satuan, satuanJual, idKemasanDepo, hargaItem, hargaKemasan} = obj;
                    const {namaSediaan, namaPabrik, diskonItem, idPabrik, idKemasan, idKatalog} = obj;

                    let found = 0;
                    divElm.querySelectorAll(".idKatalogFld").forEach(/** @type {HTMLInputElement} */item => {
                        if (item.value == idKatalog) found++;
                    });

                    if (found) {
                        this.blur();
                        this.clear();
                        return alert(str._<?= $h("Katalog sudah ditambahkan! Pilih yang lain.") ?>);
                    }

                    const trElm = closest(divElm.querySelector(".nb-ganti"), "tr");
                    const fields = trElm.fields;
                    const kemasan = (isiKemasan == 0) ? satuan : `${satuanJual} ${preferInt(isiKemasan)} ${satuan}`;

                    const options = {
                        option_1: {value: idKemasanDepo, "data-is": 1,          ids: idKemasanDepo, sat: satuan, satj: satuan,     "data-hk": hargaItem,    text: satuan},
                        option_2: {value: idKemasan,     "data-is": isiKemasan, ids: idKemasanDepo, sat: satuan, satj: satuanJual, "data-hk": hargaKemasan, text: kemasan},
                    };
                    (isiKemasan == 1) ? delete options.option_2 : null;
                    const selectStr = drawSelect(options);

                    trElm.setAttribute("id", idKatalog);
                    katalogFld.fieldWidget.destroy();
                    trElm.querySelector(".nb-ganti").remove();

                    const namaSediaanStc = closest(fields.namaSediaanStc, "tr");
                    namaSediaanStc.classList.remove("input");
                    namaSediaanStc.classList.add("name");
                    namaSediaanStc.innerHTML = namaSediaan;

                    trElm.querySelector(".btn-delchange").style.display = "block";
                    fields.namaPabrikStc.innerHTML = namaPabrik;

                    const idKemasanFld = fields.idKemasanFld;
                    idKemasanFld.innerHTML = selectStr;
                    idKemasanFld.readonly = false;

                    const jumlahKemasanWgt = fields.jumlahKemasanWgt;
                    jumlahKemasanWgt.value = 0;
                    jumlahKemasanWgt.readonly = false;

                    const hargaKemasanWgt = fields.hargaKemasanWgt;
                    hargaKemasanWgt.value = currency(hargaKemasan);
                    hargaKemasanWgt.readonly = false;

                    const diskonItemWgt = fields.diskonItemWgt;
                    diskonItemWgt.value = userInt(diskonItem);
                    diskonItemWgt.readonly = false;

                    fields.idKatalogWgt.value = idKatalog;
                    fields.idPabrikWgt.value = idPabrik;
                    fields.kemasanFld.value = satuan;
                    fields.idKemasanDepoFld.value = idKemasanDepo;
                    fields.isiKemasanWgt.value = 1;
                    hitungSubTotal(trElm);

                    fields.jumlahKemasanWgt.dispatchEvent(new Event("focus"));
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
            const jumlahItem = sysNum(fields.jumlahItemStc.innerHTML);
            const isiKemasan = sysNum(kemasanOpt.dataset.is);
            const hargaKemasan = sysNum(kemasanOpt.dataset.hk);
            const jumlahKemasan = Math.floor(jumlahItem / isiKemasan);

            fields.kemasanFld.value = kemasan;
            fields.jumlahKemasanWgt.value = userInt(jumlahKemasan);
            fields.isiKemasanWgt.value = preferInt(isiKemasan);
            fields.hargaKemasanWgt.value = currency(hargaKemasan);
            hitungSubTotal(trElm);
            hitungTotal();
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            const trElm = closest(jumlahKemasanFld, "tr");
            const fields = trElm.fields;
            const idRefKatalog = fields.idRefKatalogFld.value;
            let jumlahKemasan = sysNum(jumlahKemasanFld.value);

            const maksimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jMax);
            if (maksimumJumlahItem) {
                let totalJumlahItem = 0;
                divElm.querySelector(`.idRefKatalogFld[value="${idRefKatalog}"]`).forEach(item => {
                    totalJumlahItem += sysNum(closest(item, "tr").fields.jumlahItemStc.innerHTML);
                });

                if (totalJumlahItem > maksimumJumlahItem) {
                    alert(`
                        Jumlah tidak boleh melebihi jumlah item yang direncanakan
                        (Jumlah Perencanaan - Jumlah Penerimaan). Maximum Jumlah HPS
                        yang dibolehkan adalah ${maksimumJumlahItem}`
                    );
                    jumlahKemasan = 0;
                }
            }

            jumlahKemasanFld.value = preferInt(jumlahKemasan);
            hitungSubTotal(trElm);
            hitungTotal();
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const fieldElm = event.target;
            if (!fieldElm.matches(".hargaKemasanFld, .diskonItemFld")) return;

            hitungSubTotal(closest(fieldElm, "tr"));
            hitungTotal();
        });

        // JUNK -----------------------------------------------------

        function sortNumber() {
            divElm.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
        }

        divElm.querySelector(".btn-tarik").addEventListener("click", () => {
            const kodeRefRencana = kodeRefPerencanaanWgt.value;
            const idJenisHarga = idJenisHargaFld.selectedOptions[0].innerHTML;
            const idPemasok = idPemasokWgt.value;

            if (createNewFld.value == "1" || idJenisHargaFld.value == "2") {
                if (!kodeRefRencana) {
                    alert(str._<?= $h('Anda harus memilih "Ref. Perencanaan"!') ?>);
                    return;
                }

                const textConfirm = `
                    Penarikan harga item akan disesuaikan dengan Pemasok dan Jenis
                    Harga yang dipilih. Jika Pemasok dan Jenis Harga Kosong, akan
                    menggunakan harga pada Master Katalog. Apakah Anda yakin ingin meneruskan?`;
                if ((!idJenisHarga || !idPemasok) && !confirm(textConfirm)) return;

                $.post({
                    url: "<?= $detailHpsUrl ?>",
                    data: {kodeRef: kodeRefRencana, idPemasok, idJenisHarga: idJenisHargaFld.value},
                    error() {console.log("ajax error happens")},
                    success(dom) {
                        divElm.querySelector(".modal-body").innerHTML = dom;
                        divElm.querySelector(".modal-header").innerHTML = `<h5 style="color:#FFF">Perencanaan Tahunan/Cito/Bulanan NK Farmasi</h5>`;
                        divElm.querySelector("#modal-modul").modal("show");
                    }
                });
            } else {
                const kodeRefHps = kodeRefHpsWgt.value;

                if (!kodeRefHps) {
                    alert(str._<?= $h('Anda harus memilih "Ref. HPS" first!') ?>);
                    return;
                }

                $.post({
                    url: "<?= $detailHargaUrl ?>",
                    data: {
                        kodeRef: kodeRefHps,
                        statusRevisi: 0,
                        idPemasok: idPemasokWgt.value || "0",
                        idJenisHarga: idJenisHargaFld.value,
                        kodeRefNot: kodeFld.value
                    },
                    /** @param {his.FatmaPharmacy.views.Pembelian.Form.Ajax1Fields[]} data */
                    success(data) {
                        data.forEach(obj => {
                            let {idKatalog, jumlahItem, isiKemasan, satuan, satuanJual, idKemasanDepo, hargaItem, isiKemasanKat} = obj;
                            const {satuanJualKat, satuanKat, hargaKemasan, idKemasanKat, idKemasanDepoKat, idKemasan} = obj;
                            const {kodeRefRencana, noDokumenRencana, idPabrik, namaSediaan, namaPabrik, diskonItem, kodeRefHps} = obj;
                            const {jumlahRencana, jumlahHps, jumlahPl, jumlahDo, jumlahBonus, jumlahTerima, jumlahRetur} = obj;

                            if (itemWgt.querySelector("tr#" + idKatalog)) return;

                            jumlahItem = jumlahItem - jumlahTerima + jumlahRetur;
                            const kemasan = (isiKemasan == 1) ? satuan : `${satuanJual} ${preferInt(isiKemasan)} ${satuan}`;
                            const kemasanKat = satuanJualKat + " " + preferInt(isiKemasanKat) + " " + satuanKat;
                            const hargaKemasanKat = (isiKemasanKat == isiKemasan) ? hargaKemasan : hargaItem * isiKemasan;

                            const options = {
                                option_1: {value: idKemasanDepo, "data-is": 1,             ids: idKemasanDepo,    sat: satuan,    satj: satuan,        "data-hk": hargaItem,       text: satuan},
                                option_2: {value: idKemasanKat,  "data-is": isiKemasanKat, ids: idKemasanDepoKat, sat: satuanKat, satj: satuanJualKat, "data-hk": hargaKemasanKat, text: kemasanKat},
                                option_3: {value: idKemasan,     "data-is": isiKemasan,    ids: idKemasanDepo,    sat: satuan,    satj: satuanJual,    "data-hk": hargaKemasan,    text: kemasan},
                            };
                            (isiKemasanKat == 1) ? delete options.option_2 : null;
                            (isiKemasan == 1 || isiKemasan == isiKemasanKat) ? delete options.option_3 : null;

                            const trAddElm = divElm.querySelector(".tr-add");
                            const trStr = drawTr("tbody", {
                                class: "tr-data",
                                id: idKatalog,
                                td_1: {
                                    hidden_1: {class: ".kodeRefHpsFld", name: "kodeRefHps[]", value: kodeRefHps},
                                    hidden_2: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]", value: kodeRefRencana},
                                    hidden_3: {class: ".noDokumenRencanaFld", name: "noDokumenRencana[]", value: noDokumenRencana},
                                    hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                                    hidden_5: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                                    hidden_6: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                                    hidden_7: {class: ".kemasanFld", name: "kemasan[]", value: kemasan},
                                    hidden_8: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                                    hidden_9: {class: ".noUrutFld", name: "noUrut[]", value: 1},
                                    staticText: {class: ".no", text: 1}
                                },
                                td_2: {
                                    staticText: {class: ".namaSediaanStc", text: namaSediaan},
                                    button: {class: ".changeBtn", text: str._<?= $h("Ganti Katalog") ?>}
                                },
                                td_3: {class: "namaPabrikStc", text: namaPabrik},
                                td_4: {
                                    select: {class: ".idKemasanFld", name: "idKemasan[]", ...options}
                                },
                                td_5: {
                                    input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: preferInt(isiKemasan), readonly: true}
                                },
                                td_6: {
                                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: preferInt(jumlahItem)}
                                },
                                td_7: {class: ".jumlahItemStc"},
                                td_8: {
                                    input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: currency(hargaItem)}
                                },
                                td_9: {class: ".hargaItemStc"},
                                td_10: {
                                    input: {class: ".diskonItemFld", name: "diskonItem[]", value: userInt(diskonItem)}
                                },
                                td_11: {class: ".hargaTotalStc"},
                                td_12: {class: ".diskonHargaStc"},
                                td_13: {class: ".hargaAkhirStc"},
                                td_14: {class: ".jumlahRencanaStc", text: userFloat(jumlahRencana)},
                                td_15: {class: ".jumlahHpsStc", text: userFloat(jumlahHps)},
                                td_16: {class: ".jumlahPlStc", text: userFloat(jumlahPl)},
                                td_17: {class: ".jumlahRoStc", text: 0},
                                td_18: {class: ".jumlahDoStc", text: userFloat(jumlahDo)},
                                td_19: {class: ".jumlahBonusStc", text: userFloat(jumlahBonus)},
                                td_20: {class: ".jumlahTerimaStc", text: userFloat(jumlahTerima)},
                                td_21: {class: ".jumlahReturStc", text: userFloat(jumlahRetur)},
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
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(pembelianWgt, noDokumenWgt, tanggalJatuhTempoWgt, tanggalDokumenWgt);
        this._widgets.push(idPemasokWgt, kodeRefHpsWgt, kodeRefPerencanaanWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, pembelianWgt);
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
