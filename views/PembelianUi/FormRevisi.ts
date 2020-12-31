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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/addrevisi.php the original file
 */
final class FormRevisi
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $revisiDokumenAccess,
        array  $revisiJumlahAccess,
        array  $revisiNilaiAccess,
        array  $revisiKatalogAccess,
        array  $revisiOpenAccess,
        string $dataUrl,
        string $actionUrl,
        string $revisiDokumenActionUrl,
        string $revisiJumlahActionUrl,
        string $revisiNilaiActionUrl,
        string $revisiKatalogActionUrl,
        string $revisiOpenActionUrl,
        string $cekUnikNoDokumenUrl,
        string $pemasokAcplUrl,
        string $katalogAcplUrl,
        string $detailHargaUrl,
        string $detailHpsUrl,
        string $tableWidgetId,
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
        $action = "";
        $idata = [];
        $data = new \stdClass;
        $refRencana = new \stdClass;

        $cekPpn = (isset($data) && $data->ppn == 0) ? '' : 'checked';

        $tipe0 = ($action == 'dokumen') ? '' : 'disabled';
        $tipe1 = ($action == 'jumlah') ? '' : 'disabled';
        $tipe2 = ($action == 'nilai') ? '' : 'disabled';
        $tipe3 = ($action == 'katalog') ? '' : 'disabled';

        $no = 1;
        $nt = 0.00;
        $nd = 0.00;
        $nTd = 0.00;

        $xdata = [];
        foreach ($idata as $i => $d) {
            $ht = $d->jumlahKemasan * $d->hargaKemasan;
            $dh = $ht * $d->diskonItem / 100;
            $ha = $ht - $dh;

            $nt += $ht;
            $nd += $dh;
            $nTd += $ha;

            $xdata[$i] = ["ht" => $ht,   "dh" => $dh,   "ha" => $ha];
        }

        $np = ($data->ppn == 10) ?   $nTd * $data->ppn / 100   :   0.00;
        $nTp = $nTd + $np;
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pembelian.AddRevisi {
    export interface FormFields {
        action:               "action";
        jenis:                "jenis";
        noSpk:                "no_spk"; // or noDokumen (?)
        tipeDokumen:          "tipe_doc";
        validation:           "validation";
        kode:                 "kode";
        revisiKe:             "revisike";
        tanggalDokumen:       "tgl_doc";
        tanggalJatuhTempo:    "tgl_jatuhtempo";
        noDokumen:            "no_doc";
        idJenisAnggaran:      "id_jenisanggaran";
        idPemasok:            "id_pbf";
        bulanAwalAnggaran:    "blnawal_anggaran";
        bulanAkhirAnggaran:   "blnakhir_anggaran";
        tahunAnggaran:        "thn_anggaran";
        kodeRefHps:           "kode_reffhps";
        idSumberDana:         "id_sumberdana";
        kodeRefRencana:       "kode_reffrenc";
        idJenisHarga:         "id_jenisharga";
        idCaraBayar:          "id_carabayar";
        keterangan:           "keterangan";
        verifikasi:           string;
        keteranganVerifikasi: string;
        sebelumDiskon:        string;
        diskon:               string;
        setelahDiskon:        string;
        ppn:                  string;
        setelahPpn:           string;
        pembulatan:           string;
        nilaiAkhir:           string;
        verRevisi:            "ver_revisi";
        verUserRevisi:        "ver_usrrevisi";
        verTanggalRevisi:     "ver_tglrevisi";

        objPemasok:           PemasokFields;
        objRefHps:            RefHpsFields;
        objRefRencana:        RefRencanaFields[];
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
        diskonItem:       "diskon_item";
        hargaTotal:       string;
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

    export interface InputKatalogFields {
        jumlahTerima:     "jumlah_trm";
        jumlahRetur:      "jumlah_ret";
        hargaItem:        "harga_item";
        hargaKemasan:     "harga_kemasan";
        isiKemasan:       "isi_kemasan";
        isiKemasanKat:    "isi_kemasankat";
        diskonItem:       "diskon_item";
        jumlahItem:       "jumlah_item";
        satuan:           "satuan";
        satuanJual:       "satuanjual";
        idKemasanDepo:    "id_kemasandepo";
        satuanJualKat:    "satuanjualkat";
        satuanKat:        "satuankat";
        idKemasanKat:     "id_kemasankat";
        idKemasanDepoKat: "id_kemasandepokat";
        idKemasan:        "id_kemasan";
        idKatalog:        "id_katalog";
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

    export interface PemasokFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface RefRencanaFields {
        kode:               "kode";
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        noDokumen:          "no_doc";
        subjenisAnggaran:   "subjenis_anggaran";
        nilaiAkhir:         "nilai_akhir";
    }

    export interface RefHpsFields {
        id:                 "id";
        kode:               "kode";
        subjenisAnggaran:   "subjenis_anggaran";
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        nilaiAkhir:         "nilai_akhir";
        noDokumen:          "no_doc";
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
            revisiDokumen: JSON.parse(`<?=json_encode($revisiDokumenAccess) ?>`),
            revisiJumlah: JSON.parse(`<?=json_encode($revisiJumlahAccess) ?>`),
            revisiNilai: JSON.parse(`<?=json_encode($revisiNilaiAccess) ?>`),
            revisiKatalog: JSON.parse(`<?=json_encode($revisiKatalogAccess) ?>`),
            revisiOpen: JSON.parse(`<?=json_encode($revisiOpenAccess) ?>`),
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
                button_1: {class: ".kembaliBtn",    text: tlm.stringRegistry._<?= $h("Kembali") ?>},
                button_2: {class: ".nextRevisiBtn", text: tlm.stringRegistry._<?= $h("Next") ?>,  title: "Next Revisi SP/SPK/Kontrak"},
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".tambahRevisiFrm",
            hidden_1: {class: ".actionFld", name: "action"},
            hidden_2: {class: ".jenisFld", name: "jenis"},
            hidden_3: {class: ".noSpkFld", name: "noSpk"},
            hidden_5: {class: ".validationFld", name: "validation"},
            hidden_7: {name: "submit", value: "revisi"},
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Revisi Ke") ?>,
                        input: {class: ".revisiKeFld", name: "revisiKe"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Kontrak") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        input: {class: ".tanggalJatuhTempoFld", name: "tanggalJatuhTempo"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"},
                        rButton: {class: ".clearPemasokBtn"}
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
                        label: tlm.stringRegistry._<?= $h("Ref. HPS") ?>,
                        lButton: {class: ".tarikBtn", icon: "list", title: tlm.stringRegistry._<?= $h("Tarik Item HPS/Perencanaan") ?>}, //   $bTarik
                        input: {class: ".kodeRefHpsFld"},
                        rButton: {class: ".clearReferensiHpsBtn"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Ref. Perencanaan") ?>,
                        input: {class: ".kodeRefRencanaFld", name: "kodeRefRencana"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Jenis Pembelian") ?>,
                        hidden: {class: ".tipeDokumenFld", name: "tipeDokumen"},
                        input: {class: ".tipeDokumenFld", name: "tipeDokumen"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verRevisiFld" name="verRevisi" value="1"/>
                            <input class="userRevisiFld" name="verUserRevisi" value="------"/>
                            <input class="tanggalRevisiFld" name="verTanggalRevisi" value="00-00-00 00:00:00"/>
                        `}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h(" ") ?>,
                        staticText: {class: ".keteranganVerifikasiStc", label: "Ke {{x}} Dari {{y}}"}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        staticText: {class: ".setelahPpnStc"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_26: {
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
                            td_6:  {colspan: 2, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                            td_7:  {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9:  {colspan: 3, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_10: {colspan: 8, text: tlm.stringRegistry._<?= $h("Realisasi") ?>}
                        },
                        tr_2: {
                            td_1:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_2:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_3:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_4:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_5:  {text: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>},
                            td_6:  {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_7:  {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                            td_8:  {text: tlm.stringRegistry._<?= $h("Rencana") ?>},
                            td_9:  {text: tlm.stringRegistry._<?= $h("HPS") ?>},
                            td_10: {text: tlm.stringRegistry._<?= $h("SPK") ?>},
                            td_11: {text: tlm.stringRegistry._<?= $h("RO") ?>},
                            td_12: {text: tlm.stringRegistry._<?= $h("DO") ?>},
                            td_13: {text: tlm.stringRegistry._<?= $h("Bonus") ?>},
                            td_14: {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                            td_15: {text: tlm.stringRegistry._<?= $h("Retur") ?>}
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
            row_3: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {preferInt, toUserInt: userInt, toUserFloat: userFloat, toCurrency: currency} = tlm;
        const {nowVal, numToShortMonthName: nToS, toSystemNumber: sysNum, stringRegistry: str} = tlm;
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {drawTr, drawSelect} = spl.TableDrawer;

        const oldData = {
            tanggalJatuhTempo: "",
            tanggalDokumen: "",
            anggaran: "",
            jenisAnggaran: "",
            jenisHarga: "",
            sumberDana: "",
            caraBayar: "",
            ppn: "",
        };
        const revisiPool = {
            tanggalJatuhTempo: "",
            tanggalDokumen: "",
            anggaran: "",
            jenisAnggaran: "",
            jenisHarga: "",
            sumberDana: "",
            caraBayar: "",
            ppn: "",
        };

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */      const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */    const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */    const jenisFld = divElm.querySelector(".jenisFld");
        /** @type {HTMLInputElement} */    const noSpkFld = divElm.querySelector(".noSpkFld");
        /** @type {HTMLInputElement} */    const tipeDokumenFld = divElm.querySelector(".tipeDokumenFld");
        /** @type {HTMLInputElement} */    const validationFld = divElm.querySelector(".validationFld");
        /** @type {HTMLInputElement} */    const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */    const revisiKeFld = divElm.querySelector(".revisiKeFld");
        /** @type {HTMLInputElement} */    const tanggalDokumenFld = divElm.querySelector(".tanggalDokumenFld");
        /** @type {HTMLInputElement} */    const tanggalJatuhTempoFld = divElm.querySelector(".tanggalJatuhTempoFld");
        /** @type {HTMLSelectElement} */   const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */   const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */   const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */   const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */   const tarikBtn = divElm.querySelector(".tarikBtn");
        /** @type {HTMLSelectElement} */   const nextRevisiBtn = divElm.querySelector(".nextRevisiBtn");
        /** @type {HTMLSelectElement} */   const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */   const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */   const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLTextAreaElement} */ const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLInputElement} */    const verRevisiFld = divElm.querySelector(".verRevisiFld");
        /** @type {HTMLInputElement} */    const userRevisiFld = divElm.querySelector(".userRevisiFld");
        /** @type {HTMLInputElement} */    const tanggalRevisiFld = divElm.querySelector(".tanggalRevisiFld");
        /** @type {HTMLDivElement} */      const keteranganVerifikasiStc = divElm.querySelector(".keteranganVerifikasiStc");
        /** @type {HTMLDivElement} */      const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */      const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */      const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLInputElement} */    const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */      const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLDivElement} */      const setelahPpnStc = divElm.querySelector(".setelahPpnStc");
        /** @type {HTMLDivElement} */      const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */      const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunAnggaranSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const tambahRevisiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".tambahRevisiFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.FormFields} data */
            loadData(data) {
                idPemasokWgt.clearOptions();
                if (data.objPemasok) {
                    idPemasokWgt.addOption(data.objPemasok);
                    idPemasokWgt.value = data.idPemasok;
                }

                kodeRefHpsWgt.clearOptions();
                if (data.objRefHps) {
                    kodeRefHpsWgt.addOption(data.objRefHps);
                    kodeRefHpsWgt.value = data.kodeRefHps;
                }

                kodeRefRencanaWgt.clearOptions();
                if (data.objRefRencana) {
                    kodeRefRencanaWgt.addOption(data.objRefRencana);
                    data.objRefRencana.forEach(obj => kodeRefRencanaWgt.addItem(obj.kode));
                }

                actionFld.value = data.action ?? "";
                jenisFld.value = data.jenis ?? "";
                noSpkFld.value = data.noSpk ?? "";
                tipeDokumenFld.value = data.tipeDokumen ?? "";
                validationFld.value = data.validation ?? "";
                kodeFld.value = data.kode ?? "";
                revisiKeFld.value = data.revisiKe ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                tanggalJatuhTempoWgt.value = data.tanggalJatuhTempo ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                keteranganFld.value = data.keterangan ?? "";
                verRevisiFld.value = data.verifikasi ?? "";
                keteranganVerifikasiStc.innerHTML = data.keteranganVerifikasi ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";

                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = data;
                const {idJenisAnggaran, idJenisHarga, idSumberDana, idCaraBayar} = data;

                oldData.tanggalJatuhTempo = data.tanggalJatuhTempo;
                oldData.tanggalDokumen = data.tanggalDokumen;
                oldData.anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                oldData.jenisAnggaran = idJenisAnggaranFld.querySelector(`option[value=${idJenisAnggaran}]`).text;
                oldData.jenisHarga = idJenisHargaFld.querySelector(`option[value=${idJenisHarga}]`).text;
                oldData.sumberDana = idSumberDanaFld.querySelector(`option[value=${idSumberDana}]`).text;
                oldData.caraBayar = idCaraBayarFld.querySelector(`option[value=${idCaraBayar}]`).text;
                oldData.ppn = data.ppn;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                dokumen(data) {
                    this._actionUrl = "<?= $revisiDokumenActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;

                    nextRevisiBtn.disabled = false;
                    tarikBtn.disabled = true;
                },
                jumlah(data) {
                    this._actionUrl = "<?= $revisiJumlahActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;

                    nextRevisiBtn.disabled = false;
                    tarikBtn.disabled = true;
                },
                nilai(data) {
                    this._actionUrl = "<?= $revisiNilaiActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;

                    nextRevisiBtn.disabled = false;
                    tarikBtn.disabled = true;
                },
                katalog(data) {
                    this._actionUrl = "<?= $revisiKatalogActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;

                    nextRevisiBtn.disabled = false;
                    tarikBtn.disabled = false;
                },
                open(data) {
                    this._actionUrl = "<?= $revisiOpenActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("...") ?>;

                    nextRevisiBtn.disabled = false;
                    tarikBtn.disabled = false;
                },
            },
            onInit() {
                this.loadProfile("edit");
            },
            onBeforeSubmit() {
                function checkUpdateDokumen() {
                    const tgl = tanggalJatuhTempoWgt.value;

                    if (tahunAnggaranFld.value != tgl.substr(6, 4) || bulanAkhirAnggaranFld.value != parseInt(tgl.substr(3, 2))) {
                        alert(str._<?= $h('"Tanggal Jatuh Tempo" and Bulan Akhir/Tahun Anggaran Not Match. Please Check your Data') ?>);
                        return false;
                    }

                    if (validationFld.value != "1") {
                        alert(str._<?= $h('"No. SP/SPK/Kontrak" tidak valid. Silahkan periksa data Anda.') ?>);
                        return false;
                    }
                    return true;
                }

                const action = actionFld.value;
                let statusCheck = false;

                if (action == "dokumen") {
                    statusCheck = checkUpdateDokumen();
                    if (!statusCheck) {
                        return false;
                    }
                } else if (action == "jumlah") {
                    // do nothing???
                } else if (action == "nilai") {
                    // do nothing???
                } else if (action == "katalog") {
                    // do nothing???
                } else {
                    alert(`
                        There are Problem with your Data, maybe the counting
                        did not work well or some thing wrong with the connection.
                        Please Check it One more time!`
                    );
                    return false;
                }

                if (action == jenisFld.value) {
                    if (verRevisiFld.checked) {
                        return confirm(str._<?= $h("Apakah Anda ingin menyelesaikan Revisi SP/SPK/Kontrak ini?") ?>);

                    } else {
                        return alert(str._<?= $h('untuk mengakhiri verifikasi, Anda harus mencentang "Verifikasi Revisi"!') ?>);
                    }
                } else {
                    return confirm(str._<?= $h("Apakah Anda yakin ingin menyimpan?") ?>);
                }
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
            let val = "0";
            for (let i = 0, mapLength = mapHarga.length; i < mapLength; i++) {
                const [batasBawah, batasAtas, tipe] = mapHarga[i];
                if (batasBawah < nilaiAkhir && nilaiAkhir <= batasAtas) {
                    val = tipe;
                }
            }
            tipeDokumenFld.value = val;
        }

        function setKeterangan() {
            let ket = "";
            divElm.querySelectorAll(".data_revisi").forEach(item => ket += item.value);
            keteranganFld.value = ket;
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

        tanggalJatuhTempoFld.addEventListener("focusout", () => {
            const oldVal = oldData.tanggalJatuhTempo;
            const newVal = tanggalJatuhTempoFld.value;
            const val = str._<?= $h(" || Perubahan Tanggal Jatuh Tempo: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.tanggalJatuhTempo = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        tanggalDokumenFld.addEventListener("focusout", () => {
            const oldVal = oldData.tanggalDokumen;
            const newVal = tanggalDokumenFld.value;
            const val = str._<?= $h(" || Perubahan Tanggal Dokumen: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.tanggalDokumen = (oldVal == newVal) ? "" : val;
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

        idJenisAnggaranFld.addEventListener("change", () => {
            const oldVal = oldData.jenisAnggaran;
            const newVal = idJenisAnggaranFld.selectedOptions[0].innerHTML;
            const val = str._<?= $h(" || Perubahan Mata Anggaran: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.jenisAnggaran = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        idJenisHargaFld.addEventListener("change", () => {
            const oldVal = oldData.jenisHarga;
            const newVal = idJenisHargaFld.selectedOptions[0].innerHTML;
            const val = str._<?= $h(" || Perubahan Sumber Dana: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.jenisHarga = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        const kodeRefHpsWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefHpsFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            createNew: true,
            /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.RefHpsFields} item */
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
            /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.RefHpsFields} item */
            itemRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                return `<div class="item">${item.noDokumen} (${anggaran1})</div>`;
            },
        });

        // taken from: PembelianUi/Form
        divElm.querySelector(".clearReferensiHpsBtn").addEventListener("click", () => {
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

            kodeRefRencanaWgt.clearCache();
            kodeRefRencanaWgt.clearOptions();
            kodeRefRencanaWgt.disable();

            kodeRefHpsWgt.clearOptions();
            kodeRefHpsWgt.clearCache();
        });

        const kodeRefRencanaWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefRencanaFld"),
            maxItems: 10,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.RefRencanaFields} item */
            optionRenderer(item) {
                const awal = item.bulanAwalAnggaran;
                const akhir = item.bulanAkhirAnggaran;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + item.tahunAnggaran;

                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-3"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-3"><b>${item.subjenisAnggaran}</b></div>
                        <div class="col-xs-3">${anggaran}</div>
                        <div class="col-xs-3">${preferInt(item.nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.RefRencanaFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.subjenisAnggaran})</div>`},
        });

        idSumberDanaFld.addEventListener("change", () => {
            const oldVal = oldData.sumberDana;
            const newVal = idSumberDanaFld.selectedOptions[0].innerHTML;
            const val = str._<?= $h(" || Perubahan Sumber Dana: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.sumberDana = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        idCaraBayarFld.addEventListener("change", () => {
            const oldVal = oldData.caraBayar;
            const newVal = idCaraBayarFld.selectedOptions[0].innerHTML;
            const val = str._<?= $h(" || Perubahan Sumber Dana: {{OLD}} => {{NEW}}") ?>.replace("{{OLD}}", oldVal).replace("{{NEW}}", newVal);
            revisiPool.caraBayar = (oldVal == newVal) ? "" : val;
            setKeterangan();
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.PemasokFields} item */
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

        divElm.querySelector(".clearPemasokBtn").addEventListener("click", () => {
            idPemasokWgt.clearOptions();
            idPemasokWgt.clearCache();
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
             * @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefFld.value = data.kodeRef ?? "";
                fields.kodeRefHpsFld.value = data.kodeRefHps ?? "";
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.noDokumenRencanaFld.value = data.noDokumenRencana ?? "";
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogFld.value = data.idKatalog ?? "";
                fields.idPabrikFld.value = data.idPabrik ?? "";
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

                const hargaKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".hargaKemasanFld"),
                    errorRules: [{greaterThan: 0}],
                    ...tlm.currNumberSetting
                });

                const jumlahKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahKemasanFld"),
                    errorRules: [{greaterThan: 0}],
                    ...tlm.intNumberSetting
                });

                trElm.fields = {
                    diskonItemWgt,
                    isiKemasanWgt,
                    hargaKemasanWgt,
                    jumlahKemasanWgt,
                    kodeRefFld: trElm.querySelector(".kodeRefFld"),
                    kodeRefHpsFld: trElm.querySelector(".kodeRefHpsFld"),
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    noDokumenRencanaFld: trElm.querySelector(".noDokumenRencanaFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    idPabrikFld: trElm.querySelector(".idPabrikFld"),
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
                fields.diskonItemWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.hargaKemasanWgt.destroy();
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
            addRowBtn: ".tambahRevisiWgt .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const changeBtn = event.target;
            if (!changeBtn.matches(".changeBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin mengubah item ini?") ?>)) return;

            const trElm = closest(changeBtn, "tr");
            const fields = trElm.fields;
            const refKatalog = fields.idRefKatalogFld.value;
            const i = divElm.querySelectorAll(`.idRefKatalogFld[value="${refKatalog}"]`).length;

            if (i > 1 || divElm.querySelector("select").classList.contains("nb-ganti")) return;

            const trStr = drawTr("tbody", {
                class: "tr-data info",
                td_1: {
                    hidden_1: {class: ".kodeRefHpsFld", name: "kodeRefHps[]", value: fields.kodeRefHpsFld.value},
                    hidden_2: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]", value: fields.kodeRefRencanaFld.value},
                    hidden_3: {class: ".noDokumenRencanaFld", name: "noDokumenRencana[]", value: fields.noDokumenRencanaFld.value},
                    hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: refKatalog},
                    hidden_5: {class: ".noUrutFld", name: "noUrut[]", value: i + 1},
                    hidden_6: {class: ".idKatalogFld", name: "idKatalog[]", value: "-"},
                    hidden_7: {class: ".idPabrikFld", name: "idPabrik[]"},
                    hidden_8: {class: ".kemasanFld", name: "kemasan[]"},
                    hidden_9: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                    button: {class: "fa fa-exchange fa2x"}
                },
                td_2: {
                    select: {class: "nb-ganti"}, // wrapped with <label class="nb">
                    button: {class: ".btn-delchange", text: str._<?= $h("Hapus Katalog") ?>}
                },
                td_3: {class: ".namaPabrikStc"},
                td_4: {
                    select: {class: ".idKemasanFld", name: "idKemasan[]"}
                },
                td_5: {
                    input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: 1 readonly: true}
                },
                td_6: {
                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: 0}
                },
                td_7: {class: ".jumlahItemStc"},
                td_8: {
                    input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: 0, readonly: true}
                },
                td_9: {class: ".hargaItemStc"},
                td_10: {
                    input: {class: ".diskonItemFld", name: "diskonItem[]", value: 0, readonly: true}
                },
                td_11: {class: ".hargaTotalStc"},
                td_12: {class: ".diskonHargaStc"},
                td_13: {class: ".hargaAkhirStc"},
                td_14: {colspan: 8},
            });
            trElm.insertAdjacentHTML("afterend", trStr);
            hitungSubTotal(/** @type {HTMLTableRowElement} */ trElm.nextElementSibling);

            const katalogFld = divElm.querySelector(".nb-ganti");
            new spl.SelectWidget({
                element: katalogFld,
                maxItems: 1,
                valueField: "idKatalog",
                searchField: ["idKatalog", "namaSediaan"],
                /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.KatalogFields} item */
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
                /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.KatalogFields} item */
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
                    /** @type {his.FatmaPharmacy.views.Pembelian.AddRevisi.KatalogFields} */
                    const obj = this.options[value];
                    const {isiKemasan, satuan, satuanJual, idKemasanDepo, hargaItem, idKemasan} = obj;
                    const {namaSediaan, namaPabrik, diskonItem, idPabrik, hargaKemasan, idKatalog} = obj;

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

                    const namaSediaanStc = closest(fields.namaSediaanStc, "td");
                    namaSediaanStc.classList.remove("input");
                    namaSediaanStc.classList.add("name");
                    namaSediaanStc.innerHTML = namaSediaan;

                    trElm.querySelector(".btn-delchange").style.display = "block";
                    fields.namaPabrikStc.innerHTML = namaPabrik;

                    fields.idKemasanFld.innerHTML = selectStr;
                    fields.idKemasanFld.readonly = false;

                    fields.jumlahKemasanWgt.value = 0;
                    fields.jumlahKemasanWgt.readonly = false;

                    fields.hargaKemasanWgt.value = currency(hargaKemasan);
                    fields.hargaKemasanWgt.readonly = false;

                    fields.diskonItemWgt.value = userInt(diskonItem);
                    fields.diskonItemWgt.readonly = false;

                    fields.idKatalogFld.value = idKatalog;
                    fields.idPabrikFld.value = idPabrik;
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
            const jumlahItem = sysNum(fields.jumlahItemStc.innerHTML);
            const isiKemasan = sysNum(kemasanOpt.dataset.is);
            const hargaKemasan = sysNum(kemasanOpt.dataset.hk);
            const jumlahKemasan = jumlahItem / isiKemasan;

            fields.kemasanFld.value = kemasanOpt.innerHTML;
            fields.jumlahKemasanWgt.value = preferInt(jumlahKemasan);
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
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const minimumJumlahItem = sysNum(fields.jumlahTerimaStc.innerHTML) - sysNum(fields.jumlahReturStc.innerHTML);

            let totalJumlahItem = 0;
            divElm.querySelectorAll(`.idRefKatalogFld[value="${idRefKatalog}"]`).forEach(item => {
                totalJumlahItem += sysNum(closest(item, "tr").fields.jumlahItemStc.innerHTML);
            });

            const maksimumJumlahItem = sysNum(fields.jumlahHpsStc.innerHTML) || sysNum(fields.jumlahRencanaStc.innerHTML);
            if (totalJumlahItem > maksimumJumlahItem) {
                alert(`
                    Jumlah tidak boleh melebihi jumlah item yang direncanakan
                    (Jumlah Perencanaan - Jumlah Penerimaan). Maximum Jumlah
                    HPS yang dibolehkan adalah ${maksimumJumlahItem}`
                );
                jumlahKemasan = Math.floor(minimumJumlahItem / isiKemasan);
            }

            if (totalJumlahItem < minimumJumlahItem) {
                alert(`
                    Jumlah tidak boleh kurang dari jumlah item yang telah diterima-jumlah
                    return terhadap SP/SPK/Kontrak ybs. Minimum Jumlah yang dibolehkan
                    adalah ${minimumJumlahItem}`
                );
                jumlahKemasan = Math.floor(minimumJumlahItem / isiKemasan);
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

        let bJenis = "dokumen";

        function sortNumber() {
            divElm.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
        }

        divElm.querySelector("#btn-next").addEventListener("click", () => {
            jenisFld.value = bJenis;
            divElm.querySelector(".modal-body").innerHTML = "";
            divElm.querySelector(".modal-header").innerHTML = "";
            divElm.querySelector("#modal-modul").modal("hide");
            divElm.querySelector("[name=submit]").dispatchEvent(new Event("click"));
        });

        nextRevisiBtn.addEventListener("click", () => {
            const action = actionFld.value;
            let tDoc = "checked";
            let tJum = "";
            let tNil = "";
            let tKat = "";

            if (action == "dokumen") {
                tDoc = "disabled";
                tJum = "checked";
                bJenis = "jumlah";

            } else if (action == "jumlah") {
                tJum = "disabled";
                tNil = "checked";
                tDoc = "";
                bJenis = "nilai";

            } else if (action == "nilai") {
                tNil = "disabled";
                tKat = "checked";
                tDoc = "";
                bJenis = "katalog";

            } else if (action == "katalog") {
                tKat = "disabled";
            }

            divElm.querySelector(".modal-body").innerHTML = `
                <h4>Apakah Anda yakin ingin melanjutkan revisi PL dengan no. "${noSpkFld.value}"?</h4><br/>
                <p>Jika Ya, Silahkan isi Kolom Catatan atau Keterangan dan Jenis Revisi dibawah ini.</p>
                <div class="input-group">
                    <div class="left-group col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon input-sm">
                                <input type="radio" class="tipe_rev" name="tipe_rev" value="dokumen" ${tDoc}/>
                            </span>
                            <input class="form-control input-sm" id="kodeJenis" value="Dokumen" disabled />
                        </div>
                    </div>
                    <div class="left-group col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon input-sm">
                                <input type="radio" class="tipe_rev" name="tipe_rev" value="jumlah" ${tJum}/>
                            </span>
                            <input class="form-control input-sm" id="kodeJenis" value="Jumlah" disabled />
                        </div>
                    </div>
                    <div class="left-group col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon input-sm">
                                <input type="radio" class="tipe_rev" name="tipe_rev" value="nilai" ${tNil}/>
                            </span>
                            <input class="form-control input-sm" value="Harga/Diskon/PPN/Kemasan" disabled />
                        </div>
                    </div>
                    <div class="left-group col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon input-sm">
                                <input type="radio" class="tipe_rev" name="tipe_rev" value="katalog" ${tKat}/>
                            </span>
                            <input class="form-control input-sm" value="Ganti Katalog" disabled />
                        </div>
                    </div>
                    <br/><br/>
                    <button type="button" id="btn-next" value="revisi">Next Revisi</button>
                </div>`;
            divElm.querySelector(".modal-header").innerHTML = `<h5 style="color:#FFF">Revisi SP/SPK/Kontrak Pembelian</h5>`;
            divElm.querySelector(".modal-footer").querySelector("#btn-pull").style.display = "none";
            divElm.querySelector("#modal-modul").modal("show");
        });

        /** @param {his.FatmaPharmacy.views.Pembelian.AddRevisi.InputKatalogFields} obj */
        function inputKatalog(obj) {
            const {jumlahTerima, jumlahRetur, hargaKemasan, idKemasanKat, idKemasanDepoKat, idKemasan, idKatalog} = obj;
            const {isiKemasan, satuan, satuanJual, idKemasanDepo, hargaItem, isiKemasanKat, satuanJualKat, satuanKat} = obj;
            const jumlahItem = obj.jumlahItem - jumlahTerima + jumlahRetur;

            const kategoriKemasan = satuanJualKat + " " + preferInt(isiKemasanKat) + " " + satuanKat;
            const kategoriHargaKemasan = (isiKemasanKat == isiKemasan) ? hargaKemasan : hargaItem * isiKemasan;
            const kemasan = (isiKemasan == 1) ? satuan : `${satuanJual} ${preferInt(isiKemasan)} ${satuan}`;

            const options = {
                option_1: {value: idKemasanDepo, "data-is": 1,             ids: idKemasanDepo,    sat: satuan,    satj: satuan,        "data-hk": hargaItem,            text: satuan},
                option_2: {value: idKemasanKat,  "data-is": isiKemasanKat, ids: idKemasanDepoKat, sat: satuanKat, satj: satuanJualKat, "data-hk": kategoriHargaKemasan, text: kategoriKemasan},
                option_3: {value: idKemasan,     "data-is": isiKemasan,    ids: idKemasanDepo,    sat: satuan,    satj: satuanJual,    "data-hk": hargaKemasan,         text: kemasan},
            };
            (isiKemasanKat == 1) ? delete options.option_2 : null;
            (isiKemasan == 1 || isiKemasan == isiKemasanKat) ? delete options.option_3 : null;

            const trAddElm = divElm.querySelector(".tr-add");
            const trStr = drawTr("tbody", {
                class: "tr-data",
                id: idKatalog,
                td_1: {
                    hidden_1: {class: ".kodeRefHpsFld", name: "kodeRefHps[]", value: obj.kodeRefHps || ""},
                    hidden_2: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]", value: obj.kodeRefRencana},
                    hidden_3: {class: ".noDokumenRencanaFld", name: "noDokumenRencana[]", value: obj.noDokumenRencana},
                    hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                    hidden_5: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                    hidden_6: {class: ".idPabrikFld", name: "idPabrik[]", value: obj.idPabrik},
                    hidden_7: {class: ".kemasanFld", name: "kemasan[]", value: kemasan},
                    hidden_8: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                    hidden_9: {class: ".noUrutFld", name: "noUrut[]", value: 1},
                    staticText: {class: ".no", text: 1}
                },
                td_2: {
                    staticText: {class: ".namaSediaanStc", text: obj.namaSediaan},
                    button: {class: ".changeBtn", text: str._<?= $h("Ganti Katalog") ?>}
                },
                td_3: {class: ".namaPabrikStc", text: obj.namaPabrik},
                td_4: {
                    select: {class: ".idKemasanFld", name: "idKemasan[]", ...options}
                },
                td_5: {
                    input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: 1, readonly: true}
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
                    input: {class: ".diskonItemFld", name: "diskonItem[]", value: userInt(obj.diskonItem)}
                },
                td_11: {class: ".hargaTotalStc"},
                td_12: {class: ".diskonHargaStc"},
                td_13: {class: ".hargaAkhirStc"},
                td_14: {class: ".jumlahRencanaStc", text: userFloat(obj.jumlahRencana)},
                td_15: {class: ".jumlahHpsStc",     text: userFloat(obj.jumlahHps)},
                td_16: {class: ".jumlahPlStc",      text: userFloat(obj.jumlahPl)},
                td_17: {class: ".jumlahRoStc",      text: 0},
                td_18: {class: ".jumlahDoStc",      text: userFloat(obj.jumlahDo)},
                td_19: {class: ".jumlahBonusStc",   text: userFloat(obj.jumlahBonus)},
                td_20: {class: ".jumlahTerimaStc",  text: userFloat(jumlahTerima)},
                td_21: {class: ".jumlahReturStc",   text: userFloat(jumlahRetur)},
            });
            trAddElm.insertAdjacentHTML("beforebegin", trStr);
            hitungSubTotal(/** @type {HTMLTableRowElement} */ trAddElm.previousElementSibling);
        }

        tarikBtn.addEventListener("click", () => {
            const kodeRefRencana = kodeRefRencanaWgt.value;
            const textConfirm = `
                Penarikan harga item akan disesuaikan dengan Pemasok dan Jenis Harga
                yang dipilih. Jika Pemasok dan Jenis Harga Kosong, akan menggunakan
                harga pada Master Katalog. Apakah Anda yakin ingin meneruskan?`;

            if ((!idJenisHargaFld.selectedOptions[0].innerHTML || !idPemasokWgt.value) && !confirm(textConfirm)) return;

            if (kodeRefRencana) {
                $.post({
                    url: "<?= $detailHpsUrl ?>",
                    data: {kodeRef: kodeRefRencana, idPemasok: idPemasokWgt.value, idJenisHarga: idJenisHargaFld.value},
                    error() {console.log("ajax error happens")},
                    success(data) {
                        if (!data.length) return;

                        data.forEach(obj => {
                            if (itemWgt.querySelector("tr#" + obj.idKatalog)) return;
                            inputKatalog(obj);
                        });

                        sortNumber();
                        hitungTotal();
                        itemWgt.querySelector("tbody tr:last-child").fields.jumlahKemasanWgt.dispatchEvent(new Event("focus"));
                    }
                });
            } else {
                const kodeRefHps = kodeRefHpsWgt.value;
                if (!kodeRefHps) {
                    alert(str._<?= $h("References is NULL. Can not get data, Check your input.") ?>);
                    return;
                }

                $.post({
                    url: "<?= $detailHargaUrl ?>",
                    data: {
                        kodeRef: kodeRefHps,
                        idPemasok: idPemasokWgt.value || "0",
                        idJenisHarga: idJenisHargaFld.value,
                        kodeRefNot: kodeFld.value
                    },
                    success(data) {
                        if (!data.length) return;

                        data.forEach(obj => {
                            if (itemWgt.querySelector("tr#" + obj.idKatalog)) return;
                            inputKatalog(obj);
                        });

                        sortNumber();
                        hitungTotal();
                        itemWgt.querySelector("tbody tr:last-child").fields.jumlahKemasanWgt.dispatchEvent(new Event("focus"));
                    }
                });
            }
        });

        divElm.querySelector(".tipe_rev").addEventListener("click", (event) => {
            const dt = event.target.value;
            bJenis = dt;
            jenisFld.value = dt;
        });

        verRevisiFld.addEventListener("click", () => {
            const isChecked = verRevisiFld.checked;
            userRevisiFld.value = isChecked ? tlm.user.nama : "";
            tanggalRevisiFld.value = isChecked ? nowVal("user") : "";
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(tambahRevisiWgt, noDokumenWgt, idPemasokWgt, tanggalDokumenWgt);
        this._widgets.push(kodeRefHpsWgt, kodeRefRencanaWgt, tanggalJatuhTempoWgt, itemWgt);
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
