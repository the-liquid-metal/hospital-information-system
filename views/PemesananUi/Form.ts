<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PemesananUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pemesanan/add.php the original file
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
        string $cekUnikNoDokumenUrl,
        string $pembelianAcplUrl,
        string $perencanaanUrl,
        string $pemasokAcplUrl,
        string $detailDoUrl,
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
namespace his.FatmaPharmacy.views.Pemesanan.Form {
    export interface FormFields {
        kode:                  "kode";
        idJenisAnggaran:       "id_jenisanggaran";
        noDokumen:             "no_doc";
        bulanAwalAnggaran:     "blnawal_anggaran";
        bulanAkhirAnggaran:    "blnakhir_anggaran";
        tahunAnggaran:         "thn_anggaran";
        tanggalPemesanan:      "tgl_doc";
        tanggalTempoKirim:     "tgl_tempokirim";
        idSumberDana:          "id_sumberdana";
        kodeRefPl:             string;
        idJenisHarga:          "id_jenisharga";
        idCaraBayar:           "id_carabayar";
        kodeRefRepeateOrder:   string;
        tanggalAwalKontrak:    string;
        tanggalJatuhTempo:     string;
        namaPemasok:           string;
        idPemasok:             "id_pbf";
        verifikasi:            "verifikasi";
        keteranganVerifikasi:  string;
        sebelumDiskon:         string;
        diskon:                string;
        setelahDiskon:         string;
        ppn:                   "ppn";
        setelahPpn:            string;
        pembulatan:            string;
        nilaiAkhir:            string;

        objRefRepeateOrder:    RepeateOrderFields;
    }

    export interface TableFields {
        kodeRefPl:      "kode_reffpl";
        kodeRefRencana: "kode_reffrenc";
        kodeRefRo:      "kode_reffro";
        idRefKatalog:   "id_reffkatalog";
        idKatalog:      "id_katalog";
        idPabrik:       "id_pabrik";
        kemasan:        "kemasan";
        idKemasanDepo:  "id_kemasandepo";
        namaSediaan:    string;
        namaPabrik:     string;
        idKemasan:      "id_kemasan";
        isiKemasan:     "isi_kemasan";
        jumlahKemasan:  "jumlah_kemasan";
        jumlahItem:     string;
        hargaKemasan:   "harga_kemasan";
        hargaItem:      string;
        diskonItem:     "diskon_item";
        hargaTotal:     string;
        diskonHarga:    string;
        hargaAkhir:     string;
        jumlahRencana:  string;
        jumlahHps:      string;
        jumlahPl:       string;
        jumlahRo:       string;
        jumlahDo:       string;
        jumlahBonus:    string;
        jumlahTerima:   string;
        jumlahRetur:    string;
    }

    export interface Ajax1Fields {
        idKatalog:      "id_katalog";
        jumlahTerima:   "jumlah_trm";
        jumlahRetur:    "jumlah_ret";
        jumlahItem:     "jumlah_item";
        hargaItem:      "harga_item";
        hargaKemasan:   "harga_kemasan";
        isiKemasan:     "isi_kemasan";
        diskonItem:     "diskon_item";
        satuan:         "satuan";
        satuanJual:     "satuanjual";
        idKemasanDepo:  "id_kemasandepo";
        kodeRefPl:      "kode_reffpl";
        kodeRefRencana: "kode_reffrenc";
        kodeRef:        "kode_reff";
        idPabrik:       "id_pabrik";
        namaSediaan:    "nama_sediaan";
        namaPabrik:     "nama_pabrik";
        jumlahRencana:  "jumlah_renc";
        jumlahHps:      "jumlah_hps";
        jumlahPl:       "jumlah_pl";
        jumlahRo:       "jumlah_ro";
        jumlahDo:       "jumlah_do";
        jumlahBonus:    "jumlah_bns";
    }

    export interface RefPlFields {
        kode:               string;
        noDokumen:          string;
        subjenisAnggaran:   string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        idJenisHarga:       string;
        idPemasok:          string;
        namaPemasok:        string;
        tipeDokumen:        string; // not used, but exist in controller
        id:                 "id";
        nilaiAkhir:         "nilai_akhir";
        idJenisAnggaran:    "id_jenisanggaran";
        idSumberDana:       "id_sumberdana";
        idCaraBayar:        "id_carabayar";
        ppn:                "ppn";
        tanggalDokumen:     "tgl_doc";
        tanggalJatuhTempo:  "tgl_jatuhtempo";
        kodePemasok:        "kode_pbf";

        objectPemasok:      PemasokFields;
    }

    export interface PemasokFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface RepeateOrderFields {
        kodePerencanaan:      string;
        noDokumenPerencanaan: string;
        subjenisAnggaran:     string;
        bulanAwalAnggaran:    string;
        bulanAkhirAnggaran:   string;
        tahunAnggaran:        string;
        nilaiAkhir:           string;
        noSpk:                string;
        kodeRefPl:            string;
        tanggalMulai:         string;
        tanggalJatuhTempo:    string;
        idPemasok:            string;
        namaPemasok:          string;
        idJenisAnggaran:      string;
        idSumberDana:         string;
        idJenisHarga:         string;
        idCaraBayar:          string;
        ppn:                  string;
        bulanAwalAnggaranPl:  string;
        bulanAkhirAnggaranPl: string;
        tahunAnggaranPl:      string;
        id:                   "id";
        kodePemasok:          "kode_pbf";
        action:               "act";

        objectRefPl:          RefPlFields;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{add: boolean, edit: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
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
        row_3: {
            widthColumn: {
                button_1: {class: ".printBtn",   text: tlm.stringRegistry._<?= $h("Print") ?>}, // $bPrint    $printWidgetId .'/'.($kode ?? '')
                button_2: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>}, // $tableWidgetId
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".pemesananFrm",
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
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Pemesanan") ?>,
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Pemesanan") ?>,
                        input: {class: ".tanggalPemesananFld", name: "tanggalPemesanan"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Tempo Kirim") ?>,
                        input: {class: ".tanggalTempoKirimFld", name: "tanggalTempoKirim"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>,
                        input: {class: ".kodeRefPlFld"},
                        rButton: {class: ".clearReferensiSpkBtn", icon: "eraser"}
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
                        label: tlm.stringRegistry._<?= $h("Ref. Repeat Order") ?>,
                        lButton: {class: ".tarikBtn", icon: "list", title: tlm.stringRegistry._<?= $h("Tarik Item Pembelian") ?>},
                        input: {class: ".kodeRefRepeatOrderFld"},
                        rButton: {class: ".clearReferensiRoBtn", icon: "eraser"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal Kontrak") ?>,
                        staticText: {class: ".tanggalAwalKontrakFld"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        staticText: {class: ".tanggalJatuhTempoFld"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        checkbox: {class: ".verifikasiFld", name: "verifikasi"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h(" ") ?>,
                        staticText: {class: ".keteranganVerifikasiStc", value: "Ke {{x}} Dari {{y}}"}
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
                                hidden_1: {class: ".kodeRefPlFld", name: "kodeRefPl[]"},
                                hidden_2: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]"},
                                hidden_3: {class: ".kodeRefRoFld", name: "kodeRefRo[]"},
                                hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_5: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_6: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_7: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_8: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
                                staticText_9: {class: ".no"}
                            },
                            td_2: {class: ".namaSediaanStc"},
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
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {preferInt, toUserInt: userInt, toUserFloat: userFloat, toCurrency: currency} = tlm;
        const {toSystemNumber: sysNum, numToShortMonthName: nToS, stringRegistry: str} = tlm;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLButtonElement} */ const printBtn = divElm.querySelector(".printBtn");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLButtonElement} */ const clearReferensiSpkBtn = divElm.querySelector(".clearReferensiSpkBtn");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLSelectElement} */ const tarikBtn = divElm.querySelector(".tarikBtn");
        /** @type {HTMLDivElement} */    const tanggalAwalKontrakFld = divElm.querySelector(".tanggalAwalKontrakFld");
        /** @type {HTMLDivElement} */    const tanggalJatuhTempoFld = divElm.querySelector(".tanggalJatuhTempoFld");
        /** @type {HTMLInputElement} */  const idPemasokFld = divElm.querySelector(".idPemasokFld");
        /** @type {HTMLInputElement} */  const verifikasiFld = divElm.querySelector(".verifikasiFld");
        /** @type {HTMLDivElement} */    const keteranganVerifikasiStc = divElm.querySelector(".keteranganVerifikasiStc");
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
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const pemesananWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".pemesananFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Pemesanan.Form.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                tanggalPemesananWgt.value = data.tanggalPemesanan ?? "";
                tanggalTempoKirimWgt.value = data.tanggalTempoKirim ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                kodeRefPlWgt.value = data.kodeRefPl ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                tanggalAwalKontrakFld.innerHTML = data.tanggalAwalKontrak ?? "";
                tanggalJatuhTempoFld.innerHTML = data.tanggalJatuhTempo ?? "";
                idPemasokWgt.value = data.namaPemasok ?? "";
                idPemasokFld.value = data.idPemasok ?? "";
                verifikasiFld.value = data.verifikasi ?? "";
                keteranganVerifikasiStc.innerHTML = data.keteranganVerifikasi ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.value = data.ppn ?? "";
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";

                kodeRefRepeatOrderWgt.clearOptions();
                if (data.objRefRepeateOrder) {
                    kodeRefRepeatOrderWgt.addOption(data.objRefRepeateOrder);
                    kodeRefRepeatOrderWgt.value = data.kodeRefRepeateOrder;
                }
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this.loadData({});
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;
                    actionFld.value = "add";
                    printBtn.disabled = false;
                },
                edit(data) {
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;
                    actionFld.value = "edit";
                    printBtn.disabled = false;
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

        function sortNumber() {
            divElm.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
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

        const tanggalTempoKirimWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalTempoKirimFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalPemesananWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalPemesananFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPlFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.Pemesanan.Form.RefPlFields} data
             */
            assignPairs(formElm, data) {
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                ppnFld.value = data.ppn ?? "";
                idPemasokFld.value = data.idPemasok ?? "";
                tanggalAwalKontrakFld.innerHTML = data.tanggalDokumen ?? "";
                tanggalJatuhTempoFld.innerHTML = data.tanggalJatuhTempo ?? "";
            },
            /** @param {his.FatmaPharmacy.views.Pemesanan.Form.RefPlFields} item */
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
            /** @param {his.FatmaPharmacy.views.Pemesanan.Form.RefPlFields} item */
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
                /** @type {his.FatmaPharmacy.views.Pemesanan.Form.RefPlFields} */
                const obj = this.options[value];

                divElm.querySelector("#ppn").checked = obj.ppn == 10;

                // set data pbf
                if (obj.idPemasok == "0") return;

                if (obj.objectPemasok) {
                    idPemasokWgt.addOption(obj.objectPemasok);
                    idPemasokWgt.value = obj.objectPemasok.id;
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
            if (!divElm.querySelector("#kode_reffspk").value) return;
            if (!confirm(str._<?= $h('Menghapus "Ref. SPK" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

            itemWgt.reset();
            sortNumber();
            hitungTotal();

            kodeRefPlWgt.clearOptions();
            kodeRefPlWgt.clearCache();
        });

        const kodeRefRepeatOrderWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefRepeatOrderFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pemesanan.Form.RepeateOrderFields} item */
            optionRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                const awal = item.id ? item.bulanAwalAnggaran : bulanAwalAnggaranFld.value;
                const akhir = item.id ? item.bulanAkhirAnggaran : bulanAkhirAnggaranFld.value;
                const tahun = item.id ? item.tahunAnggaran : tahunAnggaranFld.value;
                const nilaiAkhir = item.id ? item.nilaiAkhir : 0;

                const anggaran2 = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;

                return `
                    <div class="col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.noDokumenPerencanaan}</b></div>
                        <div class="col-xs-3"><b>${item.noSpk}</b></div>
                        <div class="col-xs-3"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-2">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Pemesanan.Form.RepeateOrderFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumenPerencanaan} (${item.noSpk})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $perencanaanUrl ?>",
                    data: {noDokumen: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.Pemesanan.Form.RepeateOrderFields} */
                const obj = this.options[value];

                if (obj.objectRefPl) {
                    kodeRefPlWgt.addOption(obj.objectRefPl);
                    kodeRefPlWgt.value = obj.objectRefPl.kode;
                }

                // select item spk
                if (!obj.action) {
                    tarikBtn.dispatchEvent(new Event("click"));
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

        divElm.querySelector(".clearReferensiRoBtn").addEventListener("click", () => {
            // NOTE: TRUELY missing in all source files
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Pemesanan.Form.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Pemesanan.Form.PemasokFields} item */
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

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Pemesanan.Form.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefPlFld.value = data.kodeRefPl ?? "";
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.kodeRefRoFld.value = data.kodeRefRo ?? "";
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

                trElm.fields = {
                    idKatalogWgt,
                    idPabrikWgt,
                    jumlahKemasanWgt,
                    hargaKemasanWgt,
                    isiKemasanWgt,
                    diskonItemWgt,
                    kodeRefPlFld: trElm.querySelector(".kodeRefPlFld"),
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    kodeRefRoFld: trElm.querySelector(".kodeRefRoFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
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
                fields.jumlahKemasanWgt.destroy();
                fields.hargaKemasanWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.diskonItemWgt.destroy();

                hitungTotal();
            },
            profile: {
                add(trElm) {
                    this.loadData({});
                    trElm.fields.jumlahKemasanWgt.disabled = true;
                },
                edit(trElm, data) {
                    this.loadData(data);
                    trElm.fields.jumlahKemasanWgt.disabled = false;
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
            addRowBtn: ".pemesananFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            const trElm = closest(jumlahKemasanFld, "tr");
            const fields = trElm.fields;
            const jumlahKemasan = sysNum(jumlahKemasanFld.value);
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const jumlahItem = jumlahKemasan * isiKemasan;
            const maksimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jMax);

            if (jumlahItem > maksimumJumlahItem) {
                alert(`
                    Jumlah tidak boleh melebihi jumlah item yang direncanakan
                    (Jumlah Repeate Order / Perencanaan Bulanan Farmasi dikurangi Jumlah Penerimaan).
                    Maximum Jumlah DO/PO yang dibolehkan adalah ${maksimumJumlahItem}`
                );
            }

            hitungSubTotal(trElm);
            hitungTotal();
        });

        tarikBtn.addEventListener("click", () => {
            const kodeRefRepeatOrder = kodeRefRepeatOrderWgt.value;

            if (!kodeRefRepeatOrder) {
                alert(str._<?= $h('Anda harus memilih "Ref. Repeate Order"!') ?>);
                return;
            }

            $.post({
                url: "<?= $detailDoUrl ?>",
                data: {kodeRef: kodeRefRepeatOrder},
                /** @param {his.FatmaPharmacy.views.Pemesanan.Form.Ajax1Fields[]} data */
                success(data) {
                    data.forEach(obj => {
                        let {jumlahItem, isiKemasan, satuan, satuanJual, idKemasanDepo, hargaKemasan, idKatalog} = obj;
                        const {jumlahTerima, jumlahRetur, kodeRefPl, kodeRefRencana, idPabrik, namaSediaan, namaPabrik} = obj;
                        const {kodeRef, diskonItem, jumlahRencana, jumlahHps, jumlahPl, jumlahRo, jumlahDo, jumlahBonus} = obj;

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
                                hidden_1: {class: ".kodeRefPlFld", name: "kodeRefPl[]", value: kodeRefPl},
                                hidden_2: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]", value: kodeRefRencana},
                                hidden_3: {class: ".kodeRefRoFld", name: "kodeRefRo[]", value: kodeRef},
                                hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                                hidden_5: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                                hidden_6: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                                hidden_7: {class: ".kemasanFld", name: "kemasan[]", value: isiKemasan},
                                hidden_8: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                                staticText: {class: ".no", text: 1}
                            },
                            td_2: {class: ".namaSediaanStc", text: namaSediaan},
                            td_3: {class: ".namaPabrikStc", text: namaPabrik},
                            td_4: {
                                select: {
                                    class: ".idKemasanFld",
                                    name: "idKemasan[]",
                                    option: {value: idKemasanDepo, is: isiKemasan, ids: idKemasanDepo, sat: satuan, satj: satuanJual, hk: hargaKemasan, text: kemasan}
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
                            td_17: {class: ".jumlahRoStc", text: userFloat(jumlahRo)},
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
        });

        printBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeFld.value}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(pemesananWgt, noDokumenWgt, tanggalTempoKirimWgt, tanggalPemesananWgt);
        this._widgets.push(kodeRefPlWgt, kodeRefRepeatOrderWgt, idPemasokWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, pemesananWgt);
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
