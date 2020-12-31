<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PerencanaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/add.php the original file
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
        string $cekStokUrl,
        string $subjenisAcplUrl,
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
namespace his.FatmaPharmacy.views.Perencanaan.Form {
    export interface FormFields {
        kode:               "kode",
        idJenisAnggaran:    "id_jenisanggaran",
        noDokumen:          "no_doc",
        bulanAwalAnggaran:  "blnawal_anggaran",
        bulanAkhirAnggaran: "blnakhir_anggaran",
        tahunAnggaran:      "thn_anggaran",
        tanggalDokumen:     "tgl_doc",
        idSumberDana:       "id_sumberdana",
        tipeDokumen:        "tipe_doc",
        idJenisHarga:       "id_jenisharga",
        idCaraBayar:        "id_carabayar",
        sebelumDiskon:      "___", // exist but missing
        diskon:             "___", // exist but missing
        setelahDiskon:      "___", // exist but missing
        ppn:                "ppn",
        setelahPpn:         "___", // exist but missing
        pembulatan:         "___", // exist but missing
        nilaiAkhir:         "___", // exist but missing

        detail:             TableFields[],
    }

    export interface TableFields {
        idRefKatalog:  "id_reffkatalog[]",
        idKatalog:     "id_katalog[]",
        idPabrik:      "id_pabrik[]",
        kemasan:       "kemasan[]",
        idKemasanDepo: "id_kemasandepo[]",
        jumlahKemasan: "jumlah_kemasan[]",
        hargaKemasan:  "harga_kemasan[]",
        isiKemasan:    "isi_kemasan[]",
        diskonItem:    "diskon_item[]",
        namaSediaan:   "___", // exist but missing
        namaPabrik:    "___", // exist but missing
        idKemasan:     "id_kemasan[]",
        jumlahItem:    "___", // exist but missing
        hargaItem:     "___", // exist but missing
        hargaTotal:    "___", // exist but missing
        diskonHarga:   "___", // exist but missing
        hargaAkhir:    "___", // exist but missing
        jumlahRencana: "___", // exist but missing
        jumlahHps:     "___", // exist but missing
        jumlahPl:      "___", // exist but missing
        jumlahDo:      "___", // exist but missing
        jumlahBonus:   "___", // exist but missing
        jumlahTerima:  "___", // exist but missing
        jumlahRetur:   "___", // exist but missing
    }

    export interface KatalogFields {
        isiKemasan:    string;
        satuan:        string;
        satuanJual:    string;
        idKatalog:     string;
        namaSediaan:   string;
        namaPabrik:    string;
        jumlahItem:    string;
        hargaItem:     string;
        hargaKemasan:  string;
        diskonItem:    string;
        idKemasanDepo: string;
        idKemasan:     string;
        idPabrik:      string;
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
     * @returns {{add: boolean}}
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
                button_1: {class: ".analisisBtn", text: tlm.stringRegistry._<?= $h("Analisis") ?>},
                button_2: {class: ".printBtn",   text: tlm.stringRegistry._<?= $h("Print") ?>},
                button_3: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".perencanaanFrm",
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
                        label: tlm.stringRegistry._<?= $h("No. Perencanaan") ?>,
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Perencanaan") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Jenis Perencanaan") ?>,
                        select: {
                            class: ".tipeDokumenFld",
                            name: "tipeDokumen",
                            option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Tahunan") ?>},
                            option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Bulanan") ?>},
                            option_3: {value: 2, label: tlm.stringRegistry._<?= $h("Cito") ?>}
                        }
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
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
                                hidden_1: {class: ".idRefKatalogFld", name: "idRefKatalog[]"},
                                hidden_2: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_3: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_4: {class: ".kemasanFld", name: "kemasan[]"},
                                hidden_5: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]"},
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
                    },
                    tfoot: {
                        tr: {
                            td_3: {text: tlm.stringRegistry._<?= $h("Cari Katalog:") ?>},
                            td_4: {
                                select: {class: ".idKatalogFld"}
                            },
                            td_5: {
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
        const {preferInt, toUserInt: userInt, toCurrency: currency, toSystemNumber: sysNum, stringRegistry: str} = tlm;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLButtonElement} */ const printBtn = divElm.querySelector(".printBtn");
        /** @type {HTMLButtonElement} */ const analisisBtn = divElm.querySelector(".analisisBtn");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */ const tipeDokumenFld = divElm.querySelector(".tipeDokumenFld");
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
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const perencanaanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".perencanaanFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Perencanaan.Form.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                tipeDokumenFld.value = data.tipeDokumen ?? "";
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
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    formTitleTxt.innerHTML = str._<?= $h("Tambah ...") ?>;
                    this.loadData({});
                    actionFld.value = "add";
                    itemWgt.loadProfile("add");

                    analisisBtn.disabled = true;
                    printBtn.disabled = true;
                },
                edit(data, fromServer) {
                    formTitleTxt.innerHTML = str._<?= $h("Ubah ...") ?>;
                    this.loadData(data, fromServer);
                    actionFld.value = "edit";
                    itemWgt.loadProfile("edit", data.detail);

                    analisisBtn.disabled = false;
                    printBtn.disabled = false;
                }
            },
            onInit() {
                this.loadProfile("add");
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                const widget = tlm.app.getWidget("<?= $viewWidgetId ?>");
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
            const diskonItem = sysNum(fields.diskonItemWgt.value);
            const hargaKemasan = sysNum(fields.kemasanFld.value);
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
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

        // NOTE: fill this var with incoming data
        let idJenisAnggaranSebelumnya;
        idJenisAnggaranFld.addEventListener("change", () => {
            if (confirm(str._<?= $h('Mengubah "Mata Anggaran" akan menghapus semua daftar item. Apakah Anda yakin ingin mengubah?') ?>)) {
                itemWgt.reset();
                hitungTotal();
                idJenisAnggaranSebelumnya = idJenisAnggaranFld.value;
            } else {
                idJenisAnggaranFld.value = idJenisAnggaranSebelumnya;
            }
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

        ppnFld.addEventListener("change", hitungTotal);

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Perencanaan.Form.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
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

                trElm.fields = {
                    idKatalogWgt,
                    idPabrikWgt,
                    jumlahKemasanWgt,
                    hargaKemasanWgt,
                    isiKemasanWgt,
                    diskonItemWgt,
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
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
                    jumlahDoStc: trElm.querySelector(".jumlahDoStc"),
                    jumlahBonusStc: trElm.querySelector(".jumlahBonusStc"),
                    jumlahTerimaStc: trElm.querySelector(".jumlahTerimaStc"),
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
                },
            },
            onInit() {
                this.loadProfile("add");
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
            const kemasanOpt = idKemasanFld.selectedOptions[0];
            const kemasan = kemasanOpt.innerHTML;
            const isiKemasan = sysNum(kemasanOpt.dataset.is);
            const hargaKemasan = sysNum(kemasanOpt.dataset.hk);

            fields.kemasanFld.value = kemasan;
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

        /** @see {his.FatmaPharmacy.views.Perencanaan.Form.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: /** @type {HTMLSelectElement} */ itemWgt.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["idKatalog", "namaSediaan"],
            /** @param {his.FatmaPharmacy.views.Perencanaan.Form.KatalogFields} item */
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
            /** @param {his.FatmaPharmacy.views.Perencanaan.Form.KatalogFields} item */
            itemRenderer(item) {return `<div class="item">${item.idKatalog} (${item.namaSediaan})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                const idJenis = idJenisAnggaranFld.selectedOptions[0].getAttribute("id_jenis");
                $.post({
                    url: "<?= $subjenisAcplUrl ?>",
                    data: {query: typed, idJenis},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.Perencanaan.Form.KatalogFields} */
                const obj = this.options[value];
                const {idKatalog, isiKemasan, satuan, satuanJual, idKemasanDepo, hargaKemasan} = obj;
                const {idPabrik, namaSediaan, namaPabrik, jumlahItem, diskonItem, idKemasan} = obj;

                let found = 0;
                divElm.querySelectorAll(".idKatalogFld").forEach(/** @type {HTMLInputElement} */ item => {
                    if (item.value == idKatalog) found++;
                });

                if (found) {
                    this.blur();
                    this.clear();
                    return alert(str._<?= $h("Katalog sudah ditambahkan. Silahkan pilih yang lain.") ?>);
                }

                const kemasan = (isiKemasan == 0) ? satuan : `${satuanJual} ${preferInt(isiKemasan)} ${satuan}`;
                const options = {
                    option_1: {value: idKemasanDepo, "data-is": 1,          ids: idKemasanDepo, sat: satuan, satj: satuan,     "data-hk": hargaKemasan, text: satuan},
                    option_2: {value: idKemasan,     "data-is": isiKemasan, ids: idKemasanDepo, sat: satuan, satj: satuanJual, "data-hk": hargaKemasan, text: kemasan}
                };
                (isiKemasan == 1) ? delete options.option_2 : null;

                const trAddElm = divElm.querySelector(".tr-add");
                const trStr = drawTr("tbody", {
                    class: "tr-data",
                    id: idKatalog,
                    td_1: {
                        hidden_1: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                        hidden_2: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                        hidden_3: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                        hidden_4: {class: ".kemasanFld", name: "kemasan[]", value: isiKemasan},
                        hidden_5: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                        staticText: {class: ".no", text: 1}
                    },
                    td_2: {
                        button: {class: ".stokBtn", text: str._<?= $h("Stok") ?>},
                        staticText: {class: ".namaSediaanStc", text: namaSediaan}
                    },
                    td_3: {class: ".namaPabrikStc", text: namaPabrik},
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
                        input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: currency(hargaKemasan)}
                    },
                    td_9: {class: ".hargaItemStc"},
                    td_10: {
                        input: {class: ".diskonItemFld", name: "diskonItem[]", value: userInt(diskonItem)}
                    },
                    td_11: {class: ".hargaTotalStc"},
                    td_12: {class: ".diskonHargaStc"},
                    td_13: {class: ".hargaAkhirStc"},
                });
                trAddElm.insertAdjacentHTML("beforebegin", trStr);
                hitungSubTotal(/** @type {HTMLTableRowElement} */ trAddElm.previousElementSibling);
                hitungTotal();
                itemWgt.querySelector("tbody tr:last-child").querySelector(".idKemasanFld").dispatchEvent(new Event("focus"));
            }
        });

        function sortNumber() {
            divElm.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
        }

        analisisBtn.addEventListener("click", () => {
            alert(str._<?= $h("Analisis belum dapat dilakukan, masih dalam proses pengembangan.") ?>);
        });

        printBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeFld.value, versi: "v-01"}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(perencanaanWgt, noDokumenWgt, tanggalDokumenWgt, itemWgt, stokWgt, idKatalogWgt);
        tlm.app.registerWidget(this.constructor.widgetName, perencanaanWgt);
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
