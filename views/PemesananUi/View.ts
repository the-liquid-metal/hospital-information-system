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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pemesanan/views.php the original file
 */
final class View
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $viewPerencanaanWidgetId,
        string $viewPengadaanWidgetId,
        string $tablePenerimaanWidgetId,
        string $tablePemesananWidgetId,
        string $cetakPemesananWidgetId,
        string $editPemesananWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pemesanan.View {
    export interface ContainerFields {
        bulanAwalAnggaran:       string;
        bulanAkhirAnggaran:      string;
        tahunAnggaran:           string;
        statusLinked:            string;
        statusClosed:            string;
        statusRevisi:            string;
        ppn:                     string;
        nilaiPembulatan:         string;
        caraBayar:               string;
        subsumberDana:           string;
        tanggalTempoKirim:       string;
        kodePemesanan:           string;
        noDokumenPemesanan:      string;
        tanggalDokumenPemesanan: string;
        subjenisAnggaran:        string;
        sumberDana:              string;
        jenisHarga:              string;
        noSpk:                   string;
        namaPemasok:             string;
        noDokumenPerencanaan:    string;
        daftarDetailPemesanaan: Array<DetailPemesanaan>;
    }

    export interface DetailPemesanaan {
        idKatalog:       string;
        namaSediaan:     string;
        namaPabrik:      string;
        kemasan:         string;
        isiKemasan:      string;
        jumlahKemasan:   string;
        hargaKemasan:    string;
        hargaItem:       string;
        diskonItem:      string;
        diskonHarga:     string;
        jumlahRencana:   string;
        kodeRefRencana:  string;
        jumlahHps:       string;
        kodeRefHps:      string;
        jumlahPl:        string;
        jumlahRo:        string;
        jumlahPo:        string;
        jumlahItemBonus: string;
        jumlahItemBeli:  string;
        jumlahTerima:    string;
        jumlahRetur:     string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    _structure = {
        row_1: {
            column_1: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Kode Pemesanan") ?>,
                    staticText: {class: ".kodePemesananStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("No. Dokumen Pemesanan") ?>,
                    staticText: {class: ".noDokumenPemesananStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Tanggal Dokumen Pemesanan") ?>,
                    staticText: {class: ".tanggalDokumenPemesananStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("No. Dokumen Perencanaan") ?>,
                    staticText: {class: ".noDokumenPerencanaanStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("No. PL") ?>,
                    staticText: {class: ".noSpkSpl"}
                }
            },
            column_2: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                    staticText: {class: ".namaPemasokStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Subjenis Anggaran") ?>,
                    staticText: {class: ".subjenisAnggaranStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Anggaran") ?>,
                    staticText: {class: ".anggaranStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                    staticText: {class: ".sumberDanaStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                    staticText: {class: ".jenisHargaStc"}
                }
            }
        },
        row_2: {
            column: {
                button_1: {class: ".editBtn",    text: tlm.stringRegistry._<?= $h("Edit") ?>},
                button_2: {class: ".cetakBtn",   text: tlm.stringRegistry._<?= $h("Cetak") ?>},
                button_3: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_3: {
            widthTable: {
                class: ".detailPemesanaanTbl",
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
                        td_11: /* 15-22 */ {colspan: 8, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
                    },
                    tr_2: {
                        td_1:  /*  7 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_2:  /*  8 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_3:  /*  9 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_4:  /* 10 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_5:  /* 11 */ {text: tlm.stringRegistry._<?= $h("Total") ?>},
                        td_6:  /* 12 */ {text: tlm.stringRegistry._<?= $h("%") ?>},
                        td_7:  /* 13 */ {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                        td_8:  /* 15 */ {text: tlm.stringRegistry._<?= $h("Rencana") ?>},
                        td_9:  /* 16 */ {text: tlm.stringRegistry._<?= $h("HPS") ?>},
                        td_10: /* 17 */ {text: tlm.stringRegistry._<?= $h("PL") ?>},
                        td_11: /* 18 */ {text: tlm.stringRegistry._<?= $h("RO") ?>},
                        td_12: /* 19 */ {text: tlm.stringRegistry._<?= $h("DO") ?>},
                        td_13: /* 20 */ {text: tlm.stringRegistry._<?= $h("Bonus") ?>},
                        td_14: /* 21 */ {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                        td_15: /* 22 */ {text: tlm.stringRegistry._<?= $h("Retur") ?>},
                    }
                }
            }
        },
        row_4: {
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
        const {toCurrency: currency, toUserInt: userInt, toUserFloat: userFloat, toUserDate: userDate, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */   const kodePemesananStc = divElm.querySelector(".kodePemesananStc");
        /** @type {HTMLDivElement} */   const noDokumenPemesananStc = divElm.querySelector(".noDokumenPemesananStc");
        /** @type {HTMLDivElement} */   const tanggalDokumenPemesananStc = divElm.querySelector(".tanggalDokumenPemesananStc");
        /** @type {HTMLDivElement} */   const noDokumenPerencanaanStc = divElm.querySelector(".noDokumenPerencanaanStc");
        /** @type {HTMLDivElement} */   const noSpkSpl = divElm.querySelector(".noSpkSpl");
        /** @type {HTMLDivElement} */   const namaPemasokStc = divElm.querySelector(".namaPemasokStc");
        /** @type {HTMLDivElement} */   const subjenisAnggaranStc = divElm.querySelector(".subjenisAnggaranStc");
        /** @type {HTMLDivElement} */   const anggaranStc = divElm.querySelector(".anggaranStc");
        /** @type {HTMLDivElement} */   const sumberDanaStc = divElm.querySelector(".sumberDanaStc");
        /** @type {HTMLDivElement} */   const jenisHargaStc = divElm.querySelector(".jenisHargaStc");
        /** @type {HTMLDivElement} */   const nilaiTotalStc = divElm.querySelector(".nilaiTotalStc");
        /** @type {HTMLDivElement} */   const nilaiDiskonStc = divElm.querySelector(".nilaiDiskonStc");
        /** @type {HTMLDivElement} */   const totalSetelahDiskonStc = divElm.querySelector(".totalSetelahDiskonStc");
        /** @type {HTMLInputElement} */ const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */   const nilaiPpnStc = divElm.querySelector(".nilaiPpnStc");
        /** @type {HTMLDivElement} */   const subtotalStc = divElm.querySelector(".subtotalStc");
        /** @type {HTMLDivElement} */   const nilaiPembulatanStc = divElm.querySelector(".nilaiPembulatanStc");
        /** @type {HTMLDivElement} */   const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");

        let kodePemesanan;
        let statusLinked;
        let statusClosed;
        let statusRevisi;

        const divWgt = new spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.Pemesanan.View.ContainerFields} data */
            loadData(data) {
                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = data;
                const tanggalDokumenPemesanan = data.tanggalDokumenPemesanan ? userDate(data.tanggalDokumenPemesanan) : "";
                const caraBayar = data.caraBayar ?? "";
                const subsumberDana = data.subsumberDana ?? "";
                const tanggalTempoKirim = data.tanggalTempoKirim ?? "";

                kodePemesananStc.innerHTML = data.kodePemesanan ?? "";
                noDokumenPemesananStc.innerHTML = data.noDokumenPemesanan ?? "";
                tanggalDokumenPemesananStc.innerHTML = tanggalDokumenPemesanan ? `${tanggalDokumenPemesanan} (${tanggalTempoKirim})` : "";
                noDokumenPerencanaanStc.innerHTML = data.noDokumenPerencanaan ?? "";
                noSpkSpl.innerHTML = data.noSpk ?? "";
                namaPemasokStc.innerHTML = data.namaPemasok ?? "";
                subjenisAnggaranStc.innerHTML = data.subjenisAnggaran ?? "";
                anggaranStc.innerHTML = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                sumberDanaStc.innerHTML = data.sumberDana ? `${data.sumberDana} (${subsumberDana})` : "";
                jenisHargaStc.innerHTML = data.jenisHarga ? `${data.jenisHarga} (${caraBayar})` : "";

                ppnFld.checked = data.ppn == 10;

                let totalSebelum = 0;
                let totalDiskon = 0;
                let totalSetelah = 0;
                divElm.querySelectorAll(".tr-data").forEach(item => {
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

                kodePemesanan = data.kodePemesanan;
                statusLinked = data.statusLinked;
                statusClosed = data.statusClosed;
                statusRevisi = data.statusRevisi;
                detailPemesanaanWgt.load(data.daftarDetailPemesanaan);
            },
            dataUrl: "<?= $dataUrl ?>",
        });

        const detailPemesanaanWgt = new spl.TableWidget({
            element: divElm.querySelector(".detailPemesanaanTbl"),
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
                12: {field: "diskonItem", formatter: tlm.currencyFormatter},
                13: {field: "diskonHarga", formatter: tlm.currencyFormatter},
                14: {formatter(unused, item) {
                    const {jumlahKemasan, hargaKemasan, diskonHarga} = item;
                    return currency((jumlahKemasan * hargaKemasan) - diskonHarga);
                }},
                15: {formatter(unused, item) {
                    const {jumlahRencana, kodeRefRencana} = item;
                    const text = userFloat(jumlahRencana);
                    return jumlahRencana ? draw({class: ".viewPerencanaanBtn", value: kodeRefRencana, text}) : text;
                }},
                16: {formatter(unused, item) {
                    const {jumlahHps, kodeRefHps} = item;
                    const text = userFloat(jumlahHps);
                    return jumlahHps ? draw({class: ".viewPengadaanBtn", value: kodeRefHps, text}) : text;
                }},
                17: {field: "jumlahPl", formatter: tlm.currencyFormatter},
                18: {field: "jumlahRo", formatter: tlm.currencyFormatter},
                19: {field: "jumlahPo", formatter: tlm.intFormatter},
                20: {formatter(unused, item) {
                    const {jumlahKemasan, isiKemasan, jumlahItemBonus, jumlahItemBeli} = item;
                    return currency((jumlahKemasan * isiKemasan * jumlahItemBonus) / jumlahItemBeli);
                }},
                21: {formatter(unused, {jumlahTerima}) {
                    const text = userFloat(jumlahTerima);
                    return jumlahTerima ? draw({class: ".tablePenerimaanBtn", value: kodePemesanan, text}) : text;
                }},
                22: {field: "jumlahRetur", formatter: tlm.floatFormatter}
            },
            detailFilter(idx, {jumlahItemBonus}) {return jumlahItemBonus > 0},
            detailFormatter(idx, item) {
                const {jumlahItemBeli, jumlahItemBonus, jumlahKemasan, isiKemasan} = item;
                const kemasan = jumlahKemasan * jumlahItemBonus / jumlahItemBeli;
                const satuan = jumlahKemasan * isiKemasan * jumlahItemBonus / jumlahItemBeli;
                return str._<?= $h("Beli: {{BELI}}, Gratis: {{GRATIS}}.<br/>Kemasan: {{KEMASAN}}, Satuan: {{SATUAN}}") ?>
                    .replace("{{BELI}}", userInt(jumlahItemBeli))
                    .replace("{{GRATIS}}", userInt(jumlahItemBonus))
                    .replace("{{KEMASAN}}", userInt(kemasan))
                    .replace("{{SATUAN}}", userInt(satuan));
            }
        });

        detailPemesanaanWgt.addDelegateListener("tbody", "click", event => {
            const viewPerencanaanBtn = event.target;
            if (!viewPerencanaanBtn.matches(".viewPerencanaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewPerencanaanWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewPerencanaanBtn.value}, true);
        });

        detailPemesanaanWgt.addDelegateListener("tbody", "click", event => {
            const viewPengadaanBtn = event.target;
            if (!viewPengadaanBtn.matches(".viewPengadaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewPengadaanWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewPengadaanBtn.value}, true);
        });

        detailPemesanaanWgt.addDelegateListener("tbody", "click", event => {
            const tablePenerimaanBtn = event.target;
            if (!tablePenerimaanBtn.matches(".tablePenerimaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $tablePenerimaanWidgetId ?>");
            widget.show();
            widget.loadData({filter: tablePenerimaanBtn.value}, true);
        });

        divElm.querySelector(".editBtn").addEventListener("click", () => {
            if (statusLinked != "0" || statusClosed != "0" || statusRevisi != "0") return;

            const widget = tlm.app.getWidget("_<?= $editPemesananWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodePemesanan}, true);
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakPemesananWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodePemesanan}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tablePemesananWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(divWgt, detailPemesanaanWgt);
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
