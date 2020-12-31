<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PengadaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pengadaan/add.php the original file
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
        string $perencanaanUrl,
        string $detailHpsUrl,
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
namespace his.FatmaPharmacy.views.Pengadaan.Form {
    export interface FormFields {
        kode:               "kode";
        idJenisAnggaran:    "id_jenisanggaran";
        noDokumen:          "no_doc";
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        tanggalDokumen:     "tgl_doc";
        idSumberDana:       "id_sumberdana";
        idPemasok:          "id_pbf";
        kodePemasok:        "kode_pbf";
        namaPemasok:        "nama_pbf";
        idJenisHarga:       "id_jenisharga";
        idCaraBayar:        "id_carabayar";
        kodeRefRencana:     string;
        sebelumDiskon:      string;
        diskon:             string;
        setelahDiskon:      string;
        ppn:                "ppn";
        setelahPpn:         string;
        pembulatan:         string;
        nilaiAkhir:         string;

        objRefRencana:      RefRencanaFields;
        objPemasok:         PemasokFields;
    }

    export interface TableFields {
        kodeRef:          "kode_reff";
        kodeRefRencana:   "kode_reffrenc";
        noDokumenRencana: "no_docrenc";
        idRefKatalog:     "id_reffkatalog";
        idKatalog:        "id_katalog";
        idPabrik:         "id_pabrik";
        kemasan:          "kemasan";
        idKemasanDepo:    "id_kemasandepo";
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
        jumlahDo:         string;
        jumlahBonus:      string;
        jumlahTerima:     string;
        jumlahRetur:      string;
    }

    export interface RefRencanaFields {
        bulanAwalAnggaran:  "blnawal_anggaran";
        bulanAkhirAnggaran: "blnakhir_anggaran";
        tahunAnggaran:      "thn_anggaran";
        noDokumen:          "no_doc";
        subjenisAnggaran:   "subjenis_anggaran";
        nilaiAkhir:         "nilai_akhir";
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
                button_2: {class: ".printBtn",    text: tlm.stringRegistry._<?= $h("Print") ?>},
                button_3: {class: ".kembaliBtn",  text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".pengadaanFrm",
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
                        label: tlm.stringRegistry._<?= $h("No. HPS") ?>,
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
                        label: tlm.stringRegistry._<?= $h("Tanggal HPS") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {class: ".idPemasokFld", name: "idPemasok"},
                        hidden_1: {class: ".kodePemasokFld", name: "kodePemasok"},
                        hidden_2: {class: ".namaPemasokFld", name: "namaPemasok"},
                        rButton: {class: ".clearPemasokBtn"}
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
                        label: tlm.stringRegistry._<?= $h("Ref. Perencanaan") ?>,
                        lButton: {class: ".tarikBtn", icon: "list", title: tlm.stringRegistry._<?= $h("Tarik Item Perencanaan") ?>},
                        input: {class: ".kodeRefRencanaFld"},
                        rButton: {class: ".clearReferensiBtn", icon: "eraser"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        staticText: {class: ".setelahPpnStc"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_19: {
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
                                hidden_1: {class: ".kodeRefFld", name: "kodeRef[]"},
                                hidden_2: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]"},
                                hidden_3: {class: ".noDokumenRencanaFld", name: "noDokumenRencana[]"},
                                hidden_4: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_5: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_6: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_7: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_8: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
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
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {numToShortMonthName: nToS, toSystemNumber: sysNum, toUserFloat: currency, preferInt, toUserInt: userInt, stringRegistry: str} = tlm;

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
        /** @type {HTMLInputElement} */  const kodePemasokFld = divElm.querySelector(".kodePemasokFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
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

        const pengadaanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".pengadaanFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Pengadaan.Form.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                kodePemasokFld.value = data.kodePemasok ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.checked = !!data.ppn;
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";

                idPemasokWgt.clearOptions();
                if (data.idPemasok) {
                    idPemasokWgt.addOption(data.objPemasok);
                    idPemasokWgt.value = data.idPemasok;
                }

                kodeRefRencanaWgt.clearOptions();
                if (data.kodeRefRencana) {
                    kodeRefRencanaWgt.addOption(data.objRefRencana);
                    kodeRefRencanaWgt.value = data.kodeRefRencana ?? "";
                }
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    formTitleTxt.innerHTML = str._<?= $h("Tambah HPS Pengadaan") ?>;
                    this.loadData({});
                    actionFld.value = "add";

                    printBtn.disabled = true;
                },
                edit(data, fromServer) {
                    formTitleTxt.innerHTML = str._<?= $h("Ubah HPS Pengadaan") ?>;
                    this.loadData(data, fromServer);
                    actionFld.value = "edit";

                    printBtn.disabled = false;
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
            const hargaKemasan = sysNum(fields.hargaKemasanWgt.value);
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

        divElm.querySelector(".clearPemasokBtn").addEventListener("click", () => {
            idPemasokWgt.clearOptions();
            idPemasokWgt.clearCache();
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        idJenisHargaFld.addEventListener("change", () => {
            (idJenisHargaFld.value == "2") ? ppnFld.checked = false : ppnFld.checked = true;
            hitungTotal();
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.Pengadaan.Form.PemasokFields} item */
            optionRenderer(item) {return `<div class="option">${item.nama} (${item.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.Pengadaan.Form.PemasokFields} item */
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

        const kodeRefRencanaWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefRencanaFld"),
            maxItems: 10,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.Pengadaan.Form.RefRencanaFields} item */
            optionRenderer(item) {
                const awal = item.bulanAwalAnggaran;
                const akhir = item.bulanAkhirAnggaran;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + item.tahunAnggaran;
                return `
                    <div class="option col-xs-12  tbl-row-like">
                        <div class="col-xs-3"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-3"><b>${item.subjenisAnggaran}</b></div>
                        <div class="col-xs-3">${anggaran}</div>
                        <div class="col-xs-3">${preferInt(item.nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.Pengadaan.Form.RefRencanaFields} item */
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
                if (!elm.length || !confirm(str._<?= $h('Menghapus "Ref. Perencanaan" akan menghapus semua Barang terkait dengan no. perencanaan tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

                closest(elm, "tr").remove();
                sortNumber();
                hitungTotal();
            }
        });

        // NOTE: fill this var with incoming data
        let idJenisAnggaranSebelumnya;
        idJenisAnggaranFld.addEventListener("change", () => {
            if (confirm(str._<?= $h('Mengubah "Mata Anggaran" akan menghapus semua "Ref. Perencanaan" dan daftar item. Apakah Anda yakin ingin mengubah?') ?>)) {
                divElm.querySelector(".clearReferensiBtn").dispatchEvent(new Event("click"));
                idJenisAnggaranSebelumnya = idJenisAnggaranFld.value;
            } else {
                idJenisAnggaranFld.value = idJenisAnggaranSebelumnya;
            }
        });

        divElm.querySelector(".clearReferensiBtn").addEventListener("click", () => {
            if (!kodeRefRencanaWgt.value) return;
            if (!confirm(str._<?= $h('Mengubah "Ref. Perencanaan" akan menghapus semua daftar item. Apakah Anda yakin ingin mengubah?') ?>)) return;

            kodeRefRencanaWgt.clearOptions();
            kodeRefRencanaWgt.clearCache();

            itemWgt.reset();
        });

        ppnFld.addEventListener("change", hitungTotal);

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Pengadaan.Form.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefFld.value = data.kodeRef ?? "";
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.noDokumenRencanaFld.value = data.noDokumenRencana ?? "";
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
                            callback() {return sysNum(this._element.value) <= sysNum(jumlahItemStc.dataset.jMax) / sysNum(isiKemasanWgt.value)},
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
                    kodeRefFld: trElm.querySelector(".kodeRefFld"),
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    noDokumenRencanaFld: trElm.querySelector(".noDokumenRencanaFld"),
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
            },
            profile: {
                add(trElm) {
                    const fields = trElm.fields;
                    fields.jumlahKemasanWgt.disabled = true;
                    fields.hargaKemasanWgt.disabled = true;
                    fields.diskonItemWgt.disabled = true;
                    fields.idKemasanFld.disabled = true;

                    this.loadData({});
                },
                edit(trElm, data) {
                    const fields = trElm.fields;
                    fields.jumlahKemasanWgt.disabled = false;
                    fields.hargaKemasanWgt.disabled = false;
                    fields.diskonItemWgt.disabled = false;
                    fields.idKemasanFld.disabled = false;

                    this.loadData(data);
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
            onAfterAddRow(event) {
                hitungSubTotal(event.data.trElm);
            },
            onAfterAddRows() {
                sortNumber();
                hitungTotal();
            },
            onAfterDeleteRows() {
                sortNumber();
                hitungTotal();
            }
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
            const fieldElm = event.target;
            if (!fieldElm.matches(".jumlahKemasanFld, .hargaKemasanFld, .diskonItemFld")) return;

            hitungSubTotal(closest(fieldElm, "tr"));
            hitungTotal();
        });

        function sortNumber() {
            divElm.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
        }

        divElm.querySelector(".tarikBtn").addEventListener("click", () => {
            const kodeRefRencana = kodeRefRencanaWgt.value;
            const jenisHarga = idJenisHargaFld.selectedOptions[0].innerHTML;
            const idPemasok = idPemasokWgt.value;

            if (!kodeRefRencana) {
                alert(str._<?= $h('Anda harus memilih "Ref. Perencanaan".') ?>);
                return;
            }

            const textConfirm = `
                Penarikan harga item akan disesuaikan dengan Pemasok dan Jenis Harga
                yang dipilih. Jika Pemasok dan Jenis Harga Kosong, akan menggunakan
                harga pada Master Katalog. Apakah Anda yakin ingin meneruskan?`;
            if ((!jenisHarga || !idPemasok) && !confirm(textConfirm)) return;

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
        this._widgets.push(pengadaanWgt, noDokumenWgt, tanggalDokumenWgt, idPemasokWgt, kodeRefRencanaWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, pengadaanWgt);
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
