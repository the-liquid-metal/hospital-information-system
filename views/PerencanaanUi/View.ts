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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/views.php the original file
 */
final class View
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $formWidgetId,
        string $formBulananWidgetId,
        string $printWidgetId,
        string $viewWidgetId,
        string $tableWidgetId,
        string $viewPengadaanWidgetId,
        string $tablePengadaanWidgetId,
        string $viewPembelianWidgetId,
        string $tablePembelianWidgetId,
        string $tablePenerimaanWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Perencanaan.View {
    export interface ContainerFields {
        statusLinked:              string;
        statusClosed:              string;
        statusRevisi:              string;
        ppn:                       string;
        pembulatan:                string;
        bulanAwalAnggaran:         string;
        bulanAkhirAnggaran:        string;
        tahunAnggaran:             string;
        subsumberDana:             string;
        caraBayar:                 string;
        kodePerencanaan:           string;
        noDokumenPerencanaan:      string;
        tanggalDokumenPerencanaan: string;
        subjenisAnggaran:          string;
        sumberDana:                string;
        jenisHarga:                string;
        tipeDokumen:               string;
        noSpk:                     string;
        namaPemasok:               string;
        daftarDetailPerencanaan:   Array<DetailPerencanaan>;
    }

    export interface DetailPerencanaan {
        idKatalog:      string;
        namaSediaan:    string;
        namaPabrik:     string;
        kemasan:        string;
        isiKemasan:     string;
        jumlahKemasan:  string;
        hargaKemasan:   string;
        hargaItem:      string;
        diskonItem:     string;
        diskonHarga:    string;
        jumlahRencana:  string;
        kodeRefRencana: string;
        jumlahHps:      string;
        kodeRefHps:     string;
        kodeRef:        string;
        jumlahPl:       string;
        kodeRefPl:      string;
        jumlahPo:       string;
        jumlahTerima:   string;
    }
}
</script>

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
                    label: tlm.stringRegistry._<?= $h("Kode Perencanaan") ?>,
                    staticText: {class: ".kodePerencanaanStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("No. Dokumen Perencanaan") ?>,
                    staticText: {class: ".noDokumenPerencanaanStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Tanggal Dokumen Perencanaan") ?>,
                    staticText: {class: ".tanggalDokumenPerencanaanStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Jenis Perencanaan") ?>,
                    staticText: {class: ".jenisPerencanaanStc"}
                },
                formGroup_5: {
                    class: ".blok1Blk",
                    label: tlm.stringRegistry._<?= $h("No. PL") ?>,
                    staticText: {class: ".noPlStc"}
                },
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
                    class: ".blok1Blk",
                    label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                    staticText: {class: ".namaPemasokStc"}
                },
            },
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
                class: ".detailPerencanaanTbl",
                thead: {
                    tr_1: {
                        td_1:  /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_3:  /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama barang") ?>},
                        td_4:  /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                        td_5:  /*  5    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_6:  /*  6    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                        td_7:  /*  7-8  */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                        td_8:  /*  9-11 */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_9:  /* 12-13 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                        td_10: /* 14    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Total") ?>},
                        td_11: /* 15-20 */ {colspan: 6, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
                    },
                    tr_2: {
                        td_1:  /* 7  */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_2:  /* 8  */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_3:  /* 9  */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_4:  /* 10 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_5:  /* 11 */ {text: tlm.stringRegistry._<?= $h("Total") ?>},
                        td_6:  /* 12 */ {text: tlm.stringRegistry._<?= $h("%") ?>},
                        td_7:  /* 13 */ {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                        td_8:  /* 15 */ {text: tlm.stringRegistry._<?= $h("Rencana") ?>},
                        td_9:  /* 16 */ {text: tlm.stringRegistry._<?= $h("HPS") ?>},
                        td_10: /* 17 */ {text: tlm.stringRegistry._<?= $h("SPK") ?>},
                        td_11: /* 18 */ {text: tlm.stringRegistry._<?= $h("SPB/DO") ?>, class: ".title11Stc"},
                        td_12: /* 19 */ {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                        td_13: /* 20 */ {text: tlm.stringRegistry._<?= $h("Retur") ?>},
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
                    checkbox: {class: ".ppnFld", disabled: true},
                    staticText: {class: ".nilaiPpnStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Total Setelah PPN") ?>,
                    staticText: {class: ".subtotalStc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                    staticText: {class: ".pembulatanStc"}
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

        /** @type {HTMLDivElement} */           const kodePerencanaanStc = divElm.querySelector(".kodePerencanaanStc");
        /** @type {HTMLDivElement} */           const noDokumenPerencanaanStc = divElm.querySelector(".noDokumenPerencanaanStc");
        /** @type {HTMLDivElement} */           const tanggalDokumenPerencanaanStc = divElm.querySelector(".tanggalDokumenPerencanaanStc");
        /** @type {HTMLDivElement} */           const jenisPerencanaanStc = divElm.querySelector(".jenisPerencanaanStc");
        /** @type {NodeList<HTMLDivElement>} */ const blok1List = divElm.querySelectorAll(".blok1Blk");
        /** @type {HTMLDivElement} */           const noPlStc = divElm.querySelector(".noPlStc");
        /** @type {HTMLDivElement} */           const subjenisAnggaranStc = divElm.querySelector(".subjenisAnggaranStc");
        /** @type {HTMLDivElement} */           const anggaranStc = divElm.querySelector(".anggaranStc");
        /** @type {HTMLDivElement} */           const sumberDanaStc = divElm.querySelector(".sumberDanaStc");
        /** @type {HTMLDivElement} */           const jenisHargaStc = divElm.querySelector(".jenisHargaStc");
        /** @type {HTMLDivElement} */           const namaPemasokStc = divElm.querySelector(".namaPemasokStc");
        /** @type {HTMLDivElement} */           const nilaiTotalStc = divElm.querySelector(".nilaiTotalStc");
        /** @type {HTMLDivElement} */           const nilaiDiskonStc = divElm.querySelector(".nilaiDiskonStc");
        /** @type {HTMLDivElement} */           const totalSetelahDiskonStc = divElm.querySelector(".totalSetelahDiskonStc");
        /** @type {HTMLInputElement} */         const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */           const nilaiPpnStc = divElm.querySelector(".nilaiPpnStc");
        /** @type {HTMLDivElement} */           const subtotalStc = divElm.querySelector(".subtotalStc");
        /** @type {HTMLDivElement} */           const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */           const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");
        /** @type {HTMLDivElement} */           const title11Stc = divElm.querySelector(".title11Stc");

        let kodePerencanaan;
        let tipeDokumen; // NOTE: tipeDokumen == "3" is "Monthly"
        let statusLinked;
        let statusClosed;
        let statusRevisi;

        const divWgt = new spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.Perencanaan.View.ContainerFields} data */
            loadData(data) {
                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = data;
                const subsumberDana = data.subsumberDana ?? "";
                const caraBayar = data.caraBayar ?? "";

                kodePerencanaanStc.innerHTML = data.kodePerencanaan ?? "";
                noDokumenPerencanaanStc.innerHTML = data.noDokumenPerencanaan ?? "";
                tanggalDokumenPerencanaanStc.innerHTML = data.tanggalDokumenPerencanaan ?? "";
                subjenisAnggaranStc.innerHTML = data.subjenisAnggaran ?? "";
                sumberDanaStc.innerHTML = data.sumberDana ? `${data.sumberDana} (${subsumberDana})` : "";
                jenisHargaStc.innerHTML = data.jenisHarga ? `${data.jenisHarga} (${caraBayar})` : "";
                anggaranStc.innerHTML = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;

                const map = [
                    str._<?= $h("Perencanaan Tahunan") ?>,
                    str._<?= $h("Perencanaan Bulanan") ?>,
                    str._<?= $h("Perencanaan Cito") ?>,
                    str._<?= $h("Repeat Order") ?>,
                ];
                jenisPerencanaanStc.innerHTML = map[data.tipeDokumen] ?? "";

                noPlStc.innerHTML = data.noSpk ?? "";
                namaPemasokStc.innerHTML = data.namaPemasok ?? "";

                ppnFld.checked = data.ppn == 10;

                let totalSebelum = 0;
                let totalDiskon = 0;
                let totalSetelah = 0;
                data.daftarDetailPerencanaan.forEach(item => {
                    const sebelum = item.hargaKemasan * item.jumlahKemasan;
                    const diskon = sebelum * item.diskonItem / 100;
                    totalSebelum += sebelum;
                    totalDiskon += diskon;
                    totalSetelah += sebelum - diskon;
                });

                const totalPpn = data.ppn * totalSetelah / 100;
                const subtotal = totalSetelah + totalPpn;
                const totalAkhir = subtotal + data.pembulatan;

                nilaiTotalStc.innerHTML = currency(totalSebelum);
                nilaiDiskonStc.innerHTML = currency(totalDiskon);
                totalSetelahDiskonStc.innerHTML = currency(totalSetelah);
                nilaiPpnStc.innerHTML = currency(totalPpn);
                subtotalStc.innerHTML = currency(subtotal);
                pembulatanStc.innerHTML = currency(data.pembulatan);
                nilaiAkhirStc.innerHTML = currency(totalAkhir);

                detailPerencanaanWgt.load(data.daftarDetailPerencanaan);

                blok1List.forEach(item => item.style.display = data.tipeDokumen == "3" ? "block" : "none");
                title11Stc.innerHTML = data.tipeDokumen == "3" ? "SPB" : "DO";

                kodePerencanaan = data.kodePerencanaan;
                tipeDokumen = data.tipeDokumen;
                statusLinked = data.statusLinked;
                statusClosed = data.statusClosed;
                statusRevisi = data.statusRevisi;
            },
            dataUrl: "<?= $dataUrl ?>",
        });

        const detailPerencanaanWgt = new spl.TableWidget({
            element: divElm.querySelector(".detailPerencanaanTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "idKatalog"},
                3: {field: "namaSediaan"},
                4: {field: "namaPabrik"},
                5: {field: "kemasan"},
                6: {field: "isiKemasan", formatter: tlm.intFormatter},
                7: {field: "jumlahKemasan", formatter: tlm.intFormatter},
                8: {formatter(unused, item) {
                    const {jumlahKemasan, isiKemasan} = item;
                    return userInt(jumlahKemasan * isiKemasan);
                }},
                9:  {field: "hargaKemasan", formatter: tlm.currencyFormatter},
                10: {field: "hargaItem", formatter: tlm.currencyFormatter},
                11: {formatter(unused, item) {
                    const {jumlahKemasan, hargaKemasan} = item;
                    return currency(jumlahKemasan * hargaKemasan);
                }},
                12: {field: "diskonItem", formatter: tlm.intFormatter},
                13: {field: "diskonHarga", formatter: tlm.intFormatter},
                14: {formatter(unused, item) {
                    const {jumlahKemasan, hargaKemasan, diskonHarga} = item;
                    return currency((jumlahKemasan * hargaKemasan) - diskonHarga);
                }},
                15: {formatter(unused, item) {
                    const {jumlahRencana, kodeRefRencana} = item;
                    return (tipeDokumen == "3" && jumlahRencana)
                        ? draw({class: ".viewPerencanaanBtn", value: kodeRefRencana, text: userFloat(jumlahRencana)})
                        : userFloat(jumlahRencana);
                }},
                16: {formatter(unused, item) {
                    const {jumlahHps, kodeRefHps, kodeRef} = item;

                    // https://jsfiddle.net/theliquidmetal/7jczxtgu/latest/
                    switch ("" + ((jumlahHps ?? 0) == 0 ? 0 : 1) + (tipeDokumen == "3" ? 1 : 0)) { // TODO: js: uncategorized: confirm bit arrangement
                        case "11": return draw({class: ".viewPengadaanBtn",  value: kodeRefHps, text: userFloat(jumlahHps)});
                        case "10": return draw({class: ".tablePengadaanBtn", value: kodeRef,    text: userFloat(jumlahHps)});
                        case "01": // continue
                        case "00": return userFloat(jumlahHps);
                    }
                }},
                17: {formatter(unused, item) {
                    const {jumlahPl, kodeRefPl, kodeRef} = item;

                    // https://jsfiddle.net/theliquidmetal/7jczxtgu/latest/
                    switch ("" + ((jumlahPl ?? 0) == 0 ? 0 : 1) + (tipeDokumen == "3" ? 1 : 0)) { // TODO: js: uncategorized: confirm bit arrangement
                        case "11": return draw({class: ".viewPembelianBtn",  value: kodeRefPl, text: userFloat(jumlahPl)});
                        case "10": return draw({class: ".tablePembelianBtn", value: kodeRef,   text: userFloat(jumlahPl)});
                        case "01": // continue
                        case "00": return userFloat(jumlahPl);
                    }
                }},
                18: {field: "jumlahPo", formatter: tlm.floatFormatter},
                19: {formatter(unused, {jumlahTerima}) {
                    return kodePerencanaan && jumlahTerima
                        ? draw({class: ".tablePenerimaanBtn", value: kodePerencanaan, text: userFloat(jumlahTerima)})
                        : userFloat(jumlahTerima);
                }},
                20: {formatter() {return 0}},
            }
        });

        detailPerencanaanWgt.addDelegateListener("tbody", "click", event => {
            const viewPerencanaanBtn = event.target;
            if (!viewPerencanaanBtn.matches(".viewPerencanaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewPerencanaanBtn.value}, true);
        });

        detailPerencanaanWgt.addDelegateListener("tbody", "click", event => {
            const viewPengadaanBtn = event.target;
            if (!viewPengadaanBtn.matches(".viewPengadaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewPengadaanWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewPengadaanBtn.value}, true);
        });

        detailPerencanaanWgt.addDelegateListener("tbody", "click", event => {
            const tablePengadaanBtn = event.target;
            if (!tablePengadaanBtn.matches(".tablePengadaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $tablePengadaanWidgetId ?>");
            widget.show();
            widget.loadData({kodeRefRencana: tablePengadaanBtn.value}, true);
        });

        detailPerencanaanWgt.addDelegateListener("tbody", "click", event => {
            const viewPembelianBtn = event.target;
            if (!viewPembelianBtn.matches(".viewPembelianBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewPembelianWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewPembelianBtn.value}, true);
        });

        detailPerencanaanWgt.addDelegateListener("tbody", "click", event => {
            const tablePembelianBtn = event.target;
            if (!tablePembelianBtn.matches(".tablePembelianBtn")) return;

            const widget = tlm.app.getWidget("_<?= $tablePembelianWidgetId ?>");
            widget.show();
            widget.loadData({kodeRefRencana: tablePembelianBtn.value}, true);
        });

        detailPerencanaanWgt.addDelegateListener("tbody", "click", event => {
            const tablePenerimaanBtn = event.target;
            if (!tablePenerimaanBtn.matches(".tablePenerimaanBtn")) return;

            const prefix = tipeDokumen == 3 ? "O_" : "R_";

            const widget = tlm.app.getWidget("_<?= $tablePenerimaanWidgetId ?>");
            widget.show();
            widget.loadData({filter: prefix+tablePenerimaanBtn.value}, true);
        });

        divElm.querySelector(".editBtn").addEventListener("click", () => {
            if (statusLinked != "0" || statusClosed != "0" || statusRevisi != "0") return;

            const widgetId = tipeDokumen == "3" ? "<?= $formBulananWidgetId ?>" : "<?= $formWidgetId ?>";
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode: kodePerencanaan}, true);
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodePerencanaan, versi: "v-01"}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(divWgt, detailPerencanaanWgt);
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
