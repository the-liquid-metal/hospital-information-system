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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pemesanan/addrevisi.php the original file
 */
final class FormRevisi
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $revisiJumlahAccess,
        array  $revisiPlAccess,
        array  $revisiDokumenAccess,
        array  $revisiElseAccess,
        string $dataUrl,
        string $editActionUrl,
        string $revisiJumlahActionUrl,
        string $revisiPlActionUrl,
        string $revisiDokumenActionUrl,
        string $printWidgetId,
        string $tableWidgetId,
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
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pemesanan.FormRevisi {
    export interface FormFields {
        kode:                 "kode";
        revisiKe:             "revisike";
        idJenisAnggaran:      "id_jenisanggaran";
        noDokumen:            "no_doc";
        bulanAwalAnggaran:    "blnawal_anggaran";
        bulanAkhirAnggaran:   "blnakhir_anggaran";
        tahunAnggaran:        "thn_anggaran";
        tanggalPemesanan:     "tgl_doc";
        tanggalTempoKirim:    "tgl_tempokirim";
        idSumberDana:         "id_sumberdana";
        kodeRefPl:            "kode_reffpl";
        idJenisHarga:         "id_jenisharga";
        idCaraBayar:          "id_carabayar";
        kodeRefRepeatOrder:   string;
        tanggalMulai:         string;
        tanggalJatuhTempo:    string;
        idPemasok:            "id_pbf";
        keterangan:           "keterangan";
        verifikasi:           string;
        keteranganVerifikasi: string;
        sebelumDiskon:        string;
        diskon:               string;
        setelahDiskon:        string;
        ppn:                  "ppn";
        setelahPpn:           string;
        pembulatan:           string;
        nilaiAkhir:           string;
        verRevisi:            "ver_revisi";
        verUserRevisi:        "ver_usrrevisi";
        verTanggalRevisi:     "ver_tglrevisi";
        namaPemasokOri:       "nama_pbf_ori";
        idJenisAnggaranOri:   "id_jenisanggaran_ori";
        idSumberDanaOri:      "id_sumberdana_ori";
        idJenisHargaOri:      "id_jenisharga_ori";
        idCaraBayarOri:       "id_carabayar_ori";
        ppnOri:               "ppn_ori";

        objPemasok:           PemasokFields;
        objRefRepeatOrder:    RepeatOrderFields;
        objRefPl:             PlFields;
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

    export interface PemasokFields {
        id:   "id";
        kode: "kode";
        nama: "nama_pbf";
    }

    export interface RepeatOrderFields {
        id:                 "id";
        kode:               "kode";
        noDokumen:          "no_doc";
        subjenisAnggaran:   "subjenis_anggaran";
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        nilaiAkhir:         "nilai_akhir";
        noSpk:              "no_spk";
    }

    export interface PlFields {
        id:                 "id";
        kode:               "kode";
        noDokumen:          "no_doc";
        subjenisAnggaran:   "subjenis_anggaran";
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        nilaiAkhir:         "nilai_akhir";
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean, revisiJumlah: boolean, revisiPl: boolean, revisiDokumen: boolean, revisiElse: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            revisiJumlah: JSON.parse(`<?=json_encode($revisiJumlahAccess) ?>`),
            revisiPl: JSON.parse(`<?=json_encode($revisiPlAccess) ?>`),
            revisiDokumen: JSON.parse(`<?=json_encode($revisiDokumenAccess) ?>`),
            revisiElse: JSON.parse(`<?=json_encode($revisiElseAccess) ?>`),
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
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Pemesanan") ?>,
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Pemesanan") ?>,
                        input: {class: ".tanggalPemesananFld", name: "tanggalPemesanan"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Tempo Kirim") ?>,
                        input: {class: ".tanggalTempoKirimFld", name: "tanggalTempoKirim"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>,
                        input: {class: ".kodeRefPlFld"},
                        rButton: {class: ".clearReferensiSpkBtn", icon: "eraser"}
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
                        label: tlm.stringRegistry._<?= $h("Ref. Repeat Order") ?>,
                        input: {class: ".kodeRefRepeatOrderFld"},
                        rButton: {class: ".clearReferensiRoBtn", icon: "eraser"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Kontrak") ?>,
                        input: {class: ".tanggalMulaiFld", name: ".tanggalMulai"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        input: {class: ".tanggalJatuhTempoFld", name: "tanggalJatuhTempo"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        select: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        input: {class: ".keteranganFld", name: "keterangan"}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verRevisiFld" name="verRevisi" value="1"/>
                            <input class="userRevisiFld" name="verUserRevisi" value="------"/>
                            <input class="tanggalRevisiFld" name="verTanggalRevisi" value="00-00-00 00:00:00"/>
                        `}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h(" ") ?>,
                        staticText: {class: ".keteranganVerifikasiStc", label: "Ke {{x}} Dari {{y}}"}
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
                            td_10: {colspan: 8, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
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
                        td_1: {
                            hidden_1: {class: ".kodeRefPlFld", name: "kodeRefPl[]"},
                            hidden_2: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]"},
                            hidden_3: {class: ".kodeRefRoFld", name: "kodeRefRo[]"},
                            hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                            hidden_5: {class: ".idKatalogFld", name: "idKatalog[]"},
                            hidden_6: {class: ".idPabrikFld", name: "idPabrik[]"},
                            hidden_7: {class: ".kemasanFld", name: "kemasan[]"},
                            hidden_8: {class: ".idKemasanDepoFld", name: "id_kemasandepo[]"},
                            staticText: {class: ".no"}
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
        const {preferInt, numToShortMonthName: nToS, toSystemNumber: sysNum, toCurrency: currency, stringRegistry: str, nowVal} = tlm;

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

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLButtonElement} */ const printBtn = divElm.querySelector(".printBtn");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */  const revisiKeFld = divElm.querySelector(".revisiKeFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLButtonElement} */ const clearReferensiSpkBtn = divElm.querySelector(".clearReferensiSpkBtn");
        /** @type {HTMLButtonElement} */ const clearReferensiRoBtn = divElm.querySelector(".clearReferensiRoBtn");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLInputElement} */  const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLInputElement} */  const verRevisiFld = divElm.querySelector(".verRevisiFld");
        /** @type {HTMLInputElement} */  const userRevisiFld = divElm.querySelector(".userRevisiFld");
        /** @type {HTMLInputElement} */  const tanggalRevisiFld = divElm.querySelector(".tanggalRevisiFld");
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
        tlm.app.registerSelect("_<?= $tahunAnggaranSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const tambahRevisiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".tambahRevisiFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Pemesanan.FormRevisi.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                revisiKeFld.value = data.revisiKe ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                tanggalPemesananWgt.value = data.tanggalPemesanan ?? "";
                tanggalTempoKirimWgt.value = data.tanggalTempoKirim ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                kodeRefPlWgt.value = data.kodeRefPl ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                kodeRefRepeatOrderWgt.value = data.kodeRefRepeatOrder ?? "";
                tanggalMulaiWgt.value = data.tanggalMulai ?? "";
                tanggalJatuhTempoWgt.value = data.tanggalJatuhTempo ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
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

                ppnFld.disabled = !!data.ppn;

                kodeRefRepeatOrderWgt.clearOptions();
                if (data.objRefRepeatOrder) {
                    kodeRefRepeatOrderWgt.addOption(data.objRefRepeatOrder);
                    kodeRefRepeatOrderWgt.value = data.kodeRefRepeatOrder;
                }

                kodeRefPlWgt.clearOptions();
                if (data.objRefPl) {
                    kodeRefPlWgt.addOption(data.objRefPl);
                    kodeRefPlWgt.value = data.kodeRefPl;
                }

                idPemasokWgt.clearOptions();
                if (data.objPemasok) {
                    idPemasokWgt.addOption(data.objPemasok);
                    idPemasokWgt.value = data.idPemasok;
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
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                edit(data) {
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "???";
                    noDokumenFld.disabled = true;
                    tanggalPemesananWgt.disabled = true;
                    tanggalTempoKirimWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idSumberDanaFld.disabled = true;
                    kodeRefPlWgt.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                    kodeRefRepeatOrderWgt.disabled = true;
                    tanggalMulaiWgt.disabled = true;
                    tanggalJatuhTempoWgt.disabled = true;
                    ppnFld.disabled = true;
                    clearReferensiSpkBtn.disabled = true;
                    clearReferensiRoBtn.disabled = true;
                },
                revisiJumlah(data) {
                    this._actionUrl = "<?= $revisiJumlahActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "revisi_jumlah";
                    keteranganFld.value = "Revisi Jumlah Kemasan / Jumlah Item DO/PO Pemesanan";
                    noDokumenFld.disabled = true;
                    tanggalPemesananWgt.disabled = true;
                    tanggalTempoKirimWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idSumberDanaFld.disabled = true;
                    kodeRefPlWgt.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                    kodeRefRepeatOrderWgt.disabled = true;
                    tanggalMulaiWgt.disabled = true;
                    tanggalJatuhTempoWgt.disabled = true;
                    ppnFld.disabled = true;
                    clearReferensiSpkBtn.disabled = true;
                    clearReferensiRoBtn.disabled = true;
                },
                revisiPl(data) {
                    this._actionUrl = "<?= $revisiPlActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "revisi_pl";
                    keteranganFld.value = "Revisi DO/PO Pemesanan Berdasarkan Revisi SP/SPK/Kontrak";
                    noDokumenFld.disabled = true;
                    tanggalPemesananWgt.disabled = true;
                    tanggalTempoKirimWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idSumberDanaFld.disabled = true;
                    kodeRefPlWgt.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                    kodeRefRepeatOrderWgt.disabled = true;
                    tanggalMulaiWgt.disabled = true;
                    tanggalJatuhTempoWgt.disabled = true;
                    ppnFld.disabled = true;
                    clearReferensiSpkBtn.disabled = true;
                    clearReferensiRoBtn.disabled = true;
                },
                revisiDokumen(data) {
                    this._actionUrl = "<?= $revisiDokumenActionUrl ?>";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "revisi_dokumen";
                    keteranganFld.value = "Revisi No. Dokumen / Tempo DO/PO Pemesanan";
                    noDokumenFld.disabled = false;
                    tanggalPemesananWgt.disabled = false;
                    tanggalTempoKirimWgt.disabled = false;
                    idJenisAnggaranFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idSumberDanaFld.disabled = true;
                    kodeRefPlWgt.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                    kodeRefRepeatOrderWgt.disabled = true;
                    tanggalMulaiWgt.disabled = true;
                    tanggalJatuhTempoWgt.disabled = true;
                    ppnFld.disabled = true;
                    clearReferensiSpkBtn.disabled = true;
                    clearReferensiRoBtn.disabled = true;
                },
                revisiElse(data) {
                    this._actionUrl = "???";
                    this.loadData(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    actionFld.value = "???";
                    keteranganFld.value = "???";
                    noDokumenFld.disabled = true;
                    tanggalPemesananWgt.disabled = true;
                    tanggalTempoKirimWgt.disabled = true;
                    idJenisAnggaranFld.disabled = true;
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    idSumberDanaFld.disabled = true;
                    kodeRefPlWgt.disabled = true;
                    idJenisHargaFld.disabled = true;
                    idCaraBayarFld.disabled = true;
                    kodeRefRepeatOrderWgt.disabled = true;
                    tanggalMulaiWgt.disabled = true;
                    tanggalJatuhTempoWgt.disabled = true;
                    ppnFld.disabled = true;
                    clearReferensiSpkBtn.disabled = true;
                    clearReferensiRoBtn.disabled = true;
                },
            },
            onInit() {
                this.loadProfile("edit");
            },
            onBeforeSubmit() {
                if (verRevisiFld.checked) {
                    return confirm(str._<?= $h("Apakah Anda ingin menyelesaikan Revisi Repeate Order ini?") ?>);
                } else {
                    return alert(str._<?= $h('To Finish this Revisi You have to Check "Verifikasi Revisi"!') ?>);
                }
            },
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
                widget.show();
                widget.loadData({kode: kodeFld.value}, true);
            },
            resetBtnId: false,
        });

        function setKeterangan() {
            for (const key in revisiPool) {
                if (!revisiPool.hasOwnProperty(key)) continue;
                keteranganFld.value += revisiPool[key];
            }
        }

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

        const tanggalMulaiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalMulaiFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalJatuhTempoWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalJatuhTempoFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPlFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pemesanan.FormRevisi.PlFields} item */
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
            /** @param {his.FatmaPharmacy.views.Pemesanan.FormRevisi.PlFields} item */
            itemRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                return `<div class="item">${item.noDokumen} (${anggaran1})</div>`;
            },
        });

        // taken from: PerencanaanUi/FormBulanan
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

        const kodeRefRepeatOrderWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefRepeatOrderFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pemesanan.FormRevisi.RepeatOrderFields} item */
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
                        <div class="col-xs-3"><b>${item.noSpk}</b></div>
                        <div class="col-xs-3"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-2">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Pemesanan.FormRevisi.RepeatOrderFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.noSpk})</div>`},
        });

        clearReferensiRoBtn.addEventListener("click", () => {
            // NOTE: TRUELY missing in all source files
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama"],
            /** @param {his.FatmaPharmacy.views.Pemesanan.FormRevisi.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Pemesanan.FormRevisi.PemasokFields} item */
            itemRenderer(item) {return `<div class="item">${item.nama} (${item.kode})</div>`},
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Pemesanan.FormRevisi.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefPlFld.value = data.kodeRefPl ?? "";
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.kodeRefRoFld.value = data.kodeRefRo ?? "";
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogFld.value = data.idKatalog ?? "";
                fields.idPabrikFld.value = data.idPabrik ?? "";
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
                    kodeRefPlFld: trElm.querySelector(".kodeRefPlFld"),
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    kodeRefRoFld: trElm.querySelector(".kodeRefRoFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    idPabrikFld: trElm.querySelector(".idPabrikFld"),
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
                fields.diskonItemWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.hargaKemasanWgt.destroy();
                fields.jumlahKemasanWgt.destroy();
            },
            profile: {
                edit(trElm, data) {
                    trElm.fields.jumlahKemasanWgt.disabled = true;
                    this.loadData(data);
                },
                revisiJumlah(trElm, data) {
                    trElm.fields.jumlahKemasanWgt.disabled = false;
                    this.loadData(data);
                },
                revisiPl(trElm, data) {
                    trElm.fields.jumlahKemasanWgt.disabled = true;
                    this.loadData(data);
                },
                revisiDokumen(trElm, data) {
                    trElm.fields.jumlahKemasanWgt.disabled = true;
                    this.loadData(data);
                },
                revisiElse(trElm, data) {
                    trElm.fields.jumlahKemasanWgt.disabled = true;
                    this.loadData(data);
                }
            },
            onInit() {
                this.loadProfile("edit");
            }
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            const trElm = /** @type {HTMLTableRowElement}*/ spl.util.closestParent(jumlahKemasanFld, "tr");
            const fields = trElm.fields;
            const jumlahKemasan = sysNum(jumlahKemasanFld.value);
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const jumlahItem = jumlahKemasan * isiKemasan;
            const maksimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jMax);
            const minimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jMin);

            if (jumlahItem > maksimumJumlahItem) {
                alert(`
                    Jumlah tidak boleh melebihi jumlah item yang direncanakan (Jumlah Repeate Order
                    / Perencanaan Bulanan Farmasi dikurangi Jumlah Penerimaan). Maximum Jumlah DO/PO
                    yang dibolehkan adalah ${maksimumJumlahItem}`
                );
            } else if (jumlahItem < minimumJumlahItem) {
                alert(`
                    Jumlah tidak boleh kurang dari jumlah item yang telah diterima dikurangi
                    retur. Minimum Jumlah DO/PO yang dibolehkan adalah ${minimumJumlahItem}`
                );
            }

            hitungSubTotal(trElm);
            hitungTotal();
        });

        verRevisiFld.addEventListener("click", () => {
            const isChecked = verRevisiFld.checked;
            userRevisiFld.value = isChecked ? tlm.user.nama : "------";
            tanggalRevisiFld.value = isChecked ? nowVal("user") : "00-00-0000 00:00:00";
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
        this._widgets.push(tambahRevisiWgt, tanggalTempoKirimWgt, tanggalPemesananWgt);
        this._widgets.push(kodeRefPlWgt, kodeRefRepeatOrderWgt, idPemasokWgt, itemWgt);
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
