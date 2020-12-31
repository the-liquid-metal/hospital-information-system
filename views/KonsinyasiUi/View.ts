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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Konsinyasi/views.php the original file
 */
final class View
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $tablePenerimaanWidgetId,
        string $tableReturFarmasiWidgetId,
        string $formWidgetId,
        string $cetakWidgetId,
        string $tableWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Konsinyasi.View {
    export interface ContainerFields {
        bulanAwalAnggaran:      string;
        bulanAkhirAnggaran:     string;
        tahunAnggaran:          string;
        verKendali:             string;
        caraBayar:              string;
        jenisHarga:             string;
        sumberDana:             string;
        subjenisAnggaran:       string;
        namaUserKendali:        string;
        verTanggalKendali:      string;
        gudang:                 string;
        namaPemasok:            string;
        noFaktur:               string;
        noSuratJalan:           string;
        noDokumen:              string;
        tanggalDokumen:         string;
        kodeKonsinyasi:         string;
        ppn:                    string;
        nilaiPembulatan:        string;
        keterangan:             string;
        tipeDokumen:            string;
        daftarDetailKonsinyasi: Array<DetailKonsinyasiFields>;
    }

    export interface DetailKonsinyasiFields {
        idKatalog:     string;
        namaSediaan:   string;
        namaPabrik:    string;
        kemasan:       string;
        isiKemasan:    string;
        jumlahKemasan: string;
        hargaKemasan:  string;
        hargaItem:     string;
        diskonItem:    string;
        jumlahItem:    string;
        jumlahResep:   string;
        jumlahTerima:  string;
        jumlahRetur:   string;
    }
}
</script>

<!--suppress NestedConditionalExpressionJS -->
<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

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
            column_1: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Kode Konsinyasi") ?>,
                    staticText: {class: ".kodeKonsinyasiStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("No. Terima") ?>,
                    staticText: {class: ".noTerimaStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("No. Faktur/Surat Jalan") ?>,
                    staticText: {class: ".noFakturSuratJalanStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                    staticText: {class: ".namaPemasokStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                    staticText: {class: ".gudangStc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Verifikasi Kendali Harga") ?>,
                    checkbox: {class: ".verifikasiKendaliFld"},
                    staticText: {class: ".userTanggalKendaliStc"}
                }
            },
            column_2: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Subjenis Anggaran") ?>,
                    staticText: {class: ".subjenisAnggaranStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Anggaran") ?>,
                    staticText: {class: ".anggaranStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                    staticText: {class: ".sumberDanaStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                    staticText: {class: ".jenisHargaStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                    staticText: {class: ".keteranganStc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Tipe Konsinyasi") ?>,
                    staticText: {class: ".tipeKonsinyasiStc"}
                }
            }
        },
        row_4: {
            column: {
                button_1: {class: ".editBtn",    text: tlm.stringRegistry._<?= $h("Edit") ?>},
                button_2: {class: ".cetakBtn",   text: tlm.stringRegistry._<?= $h("Cetak") ?>},
                button_3: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_5: {
            widthTable: {
                class: ".detailKonsinyasiTbl",
                thead: {
                    tr_1: {
                        td_1:  /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_3:  /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                        td_4:  /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                        td_5:  /*  5    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_6:  /*  6    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                        td_7:  /*  7-8  */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                        td_8:  /*  9-11 */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_9:  /* 12-13 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                        td_10: /* 14    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Total") ?>},
                        td_11: /* 15-18 */ {colspan: 4, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
                    },
                    tr_2: {
                        td_1:  /*  7 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_2:  /*  8 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_3:  /*  9 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_4:  /* 10 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_5:  /* 11 */ {text: tlm.stringRegistry._<?= $h("Total") ?>},
                        td_6:  /* 12 */ {text: tlm.stringRegistry._<?= $h("%") ?>},
                        td_7:  /* 13 */ {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                        td_8:  /* 15 */ {text: tlm.stringRegistry._<?= $h("Konsinyasi") ?>},
                        td_9:  /* 16 */ {text: tlm.stringRegistry._<?= $h("Resep") ?>},
                        td_10: /* 17 */ {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                        td_11: /* 18 */ {text: tlm.stringRegistry._<?= $h("Retur") ?>},
                    }
                }
            }
        },
        row_6: {
            column: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Total Sebelum Diskon") ?>,
                    staticText: {class: ".nilaiTotalStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Total Diskon") ?>,
                    staticText: {class: ".nilaiDiskonStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Total Setelah Diskon") ?>,
                    staticText: {class: ".totalSetelahDiskonStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Total PPN 10%") ?>,
                    checkbox: {class: "ppnFld", disabled: true},
                    staticText: {class: ".nilaiPpnStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Total Setelah PPN") ?>,
                    staticText: {class: ".subtotalStc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                    staticText: {class: ".nilaiPembulatanStc"}
                },
                formGroup_7: {
                    label: tlm.stringRegistry._<?= $h("Total Setelah Pembulatan") ?>,
                    staticText: {class: ".nilaiAkhirStc"}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {toCurrency: currency, toUserInt: userInt, toUserFloat: userFloat, numToShortMonthName: nToS, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */   const kodeKonsinyasiStc = divElm.querySelector(".kodeKonsinyasiStc");
        /** @type {HTMLDivElement} */   const noTerimaStc = divElm.querySelector(".noTerimaStc");
        /** @type {HTMLDivElement} */   const noFakturSuratJalanStc = divElm.querySelector(".noFakturSuratJalanStc");
        /** @type {HTMLDivElement} */   const namaPemasokStc = divElm.querySelector(".namaPemasokStc");
        /** @type {HTMLDivElement} */   const gudangStc = divElm.querySelector(".gudangStc");
        /** @type {HTMLInputElement} */ const verifikasiKendaliFld = divElm.querySelector(".verifikasiKendaliFld");
        /** @type {HTMLDivElement} */   const userTanggalKendaliStc = divElm.querySelector(".userTanggalKendaliStc");
        /** @type {HTMLDivElement} */   const subjenisAnggaranStc = divElm.querySelector(".subjenisAnggaranStc");
        /** @type {HTMLDivElement} */   const anggaranStc = divElm.querySelector(".anggaranStc");
        /** @type {HTMLDivElement} */   const sumberDanaStc = divElm.querySelector(".sumberDanaStc");
        /** @type {HTMLDivElement} */   const jenisHargaStc = divElm.querySelector(".jenisHargaStc");
        /** @type {HTMLDivElement} */   const keteranganStc = divElm.querySelector(".keteranganStc");
        /** @type {HTMLDivElement} */   const tipeKonsinyasiStc = divElm.querySelector(".tipeKonsinyasiStc");
        /** @type {HTMLDivElement} */   const nilaiTotalStc = divElm.querySelector(".nilaiTotalStc");
        /** @type {HTMLDivElement} */   const nilaiDiskonStc = divElm.querySelector(".nilaiDiskonStc");
        /** @type {HTMLDivElement} */   const totalSetelahDiskonStc = divElm.querySelector(".totalSetelahDiskonStc");
        /** @type {HTMLInputElement} */ const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */   const nilaiPpnStc = divElm.querySelector(".nilaiPpnStc");
        /** @type {HTMLDivElement} */   const subtotalStc = divElm.querySelector(".subtotalStc");
        /** @type {HTMLDivElement} */   const nilaiPembulatanStc = divElm.querySelector(".nilaiPembulatanStc");
        /** @type {HTMLDivElement} */   const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");

        let kodeKonsinyasi;
        let verKendali;

        const divWgt = new spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.Konsinyasi.View.ContainerFields} data */
            loadData(data) {
                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = data;
                const caraBayar = data.caraBayar ?? "";
                const tanggalDokumen = data.tanggalDokumen ?? "";

                kodeKonsinyasiStc.innerHTML = data.kodeKonsinyasi ?? "";
                noTerimaStc.innerHTML = data.noDokumen ? `${data.noDokumen} (${tanggalDokumen})` : "";
                noFakturSuratJalanStc.innerHTML = (data.noFaktur ?? "-") + " / " + (data.noSuratJalan ?? "-");
                namaPemasokStc.innerHTML = data.namaPemasok ?? "-";
                gudangStc.innerHTML = data.gudang ?? "";
                verifikasiKendaliFld.checked = data.verKendali == "1";
                userTanggalKendaliStc.innerHTML = data.namaUserKendali + " " + data.verTanggalKendali;
                subjenisAnggaranStc.innerHTML = data.subjenisAnggaran ?? "";
                anggaranStc.innerHTML = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                sumberDanaStc.innerHTML = data.sumberDana ?? "";
                jenisHargaStc.innerHTML = data.jenisHarga ? `${data.jenisHarga} (${caraBayar})` : "";
                keteranganStc.innerHTML = data.keterangan ?? "";

                tipeKonsinyasiStc.innerHTML =
                    (data.tipeDokumen == "0") ? str._<?= $h("Konsinyasi Rutin") ?>
                    : (data.tipeDokumen == "1") ? str._<?= $h("Konsinyasi Non Rutin") ?>
                    : "";

                ppnFld.checked = data.ppn == 10;

                let totalSebelum = 0;
                let totalDiskon = 0;
                let totalSetelah = 0;
                data.daftarDetailKonsinyasi.forEach(item => {
                    const sebelum = item.hargaKemasan * item.jumlahKemasan;
                    const diskon = sebelum * item.diskonItem / 100;
                    totalSebelum += sebelum;
                    totalDiskon += diskon;
                    totalSetelah += sebelum - diskon;
                });
                const totalPpn = data.ppn * totalSetelah / 100;
                const subtotal = totalSetelah + totalPpn;
                const totalAkhir = subtotal + data.nilaiPembulatan;

                nilaiTotalStc.innerHTML = currency(totalSebelum);
                nilaiDiskonStc.innerHTML = currency(totalDiskon);
                totalSetelahDiskonStc.innerHTML = currency(totalSetelah);
                nilaiPpnStc.innerHTML = currency(totalPpn);
                subtotalStc.innerHTML = currency(subtotal);
                nilaiPembulatanStc.innerHTML = currency(data.nilaiPembulatan);
                nilaiAkhirStc.innerHTML = currency(totalAkhir);

                detailKonsinyasiWgt.load(data.daftarDetailKonsinyasi);
                kodeKonsinyasi = data.kodeKonsinyasi;
                verKendali = data.verKendali;
            },
            dataUrl: "<?= $dataUrl ?>",
        });

        const detailKonsinyasiWgt = new spl.TableWidget({
            element: divElm.querySelector(".detailKonsinyasiTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "idKatalog"},
                3: {field: "namaSediaan"},
                4: {field: "namaPabrik"},
                5: {field: "kemasan"},
                6: {field: "isiKemasan", formatter: tlm.intFormatter},
                7: {field: "jumlahKemasan", formatter: tlm.intFormatter},
                8: {formatter(unused, item) {
                    const {isiKemasan, jumlahKemasan} = item;
                    return userInt(isiKemasan * jumlahKemasan);
                }},
                9:  {field: "hargaKemasan", formatter: tlm.currencyFormatter},
                10: {field: "hargaItem", formatter: tlm.currencyFormatter},
                11: {formatter(unused, item) {
                    const {jumlahKemasan, hargaKemasan} = item;
                    return currency(jumlahKemasan * hargaKemasan);
                }},
                12: {field: "diskonItem", formatter: tlm.currencyFormatter},
                13: {formatter(unused, item) {
                    const {jumlahKemasan, hargaKemasan, diskonItem} = item;
                    return currency(jumlahKemasan * hargaKemasan * diskonItem / 100);
                }},
                14: {formatter(unused, item) {
                    const {jumlahKemasan, hargaKemasan, diskonItem} = item;
                    return currency(jumlahKemasan * hargaKemasan * (100 - diskonItem) / 100);
                }},
                15: {field: "jumlahItem", formatter: tlm.currencyFormatter},
                16: {field: "jumlahResep", formatter: tlm.currencyFormatter},
                17: {formatter(unused, {jumlahTerima}) {
                    const text = userFloat(jumlahTerima);
                    return jumlahTerima ? draw({class: ".tablePenerimaanBtn", value: kodeKonsinyasi, text}) : text;
                }},
                18: {formatter(unused, {jumlahRetur}) {
                    const text = userFloat(jumlahRetur);
                    return jumlahRetur ? draw({class: ".tableReturFarmasiBtn", value: kodeKonsinyasi, text}) : text;
                }}
            }
        });

        detailKonsinyasiWgt.addDelegateListener("tbody", "click", event => {
            const tablePenerimaanBtn = event.target;
            if (!tablePenerimaanBtn.matches(".tablePenerimaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $tablePenerimaanWidgetId ?>");
            widget.show();
            widget.loadData({filter: "K_"+kodeKonsinyasi}, true);
        });

        detailKonsinyasiWgt.addDelegateListener("tbody", "click", event => {
            const tableReturFarmasiBtn = event.target;
            if (!tableReturFarmasiBtn.matches(".tableReturFarmasiBtn")) return;

            const widget = tlm.app.getWidget("_<?= $tableReturFarmasiWidgetId ?>");
            widget.show();
            widget.loadData({filter: "K_"+kodeKonsinyasi}, true);
        });

        divElm.querySelector(".editBtn").addEventListener("click", () => {
            if (verKendali != 0) return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeKonsinyasi}, true);
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakWidgetId ?>");
            widget.show();
            widget.loadData({kodeKonsinyasi, versi: 0}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(divWgt, detailKonsinyasiWgt);
        tlm.app.registerWidget(this.constructor.widgetName, divWgt);
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
