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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/views.php the original file
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
namespace his.FatmaPharmacy.views.Pembelian.View {
    export interface ContainerFields {
        tipeDokumen:             string;
        idJenisHarga:            string;
        keterangan:              string;
        keteranganRevisi:        string;
        verRevisi:               string;
        revisiKe:                string;
        statusLinked:            string;
        statusClosed:            string;
        tanggalJatuhTempo:       string;
        subsumberDana:           string;
        caraBayar:               string;
        kodePembelian:           string;
        noDokumenPembelian:      string;
        tanggalDokumenPembelian: string;
        noHps:                   string;
        noDokumenRef:            string;
        namaPemasok:             string;
        subjenisAnggaran:        string;
        sumberDana:              string;
        jenisHarga:              string;
        ppn:                     string;
        nilaiPembulatan:         string;
        bulanAwalAnggaran:       string;
        bulanAkhirAnggaran:      string;
        tahunAnggaran:           string;
        daftarRevisiPembelian:   Array<RevisiPembelian>;
    }

    export interface RevisiPembelian {
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
        jumlahItemBonus: string;
        jumlahItemBeli:  string;
        jumlahRetur:     string;
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
            column_1: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Kode Pembelian") ?>,
                    staticText: {class: ".kodePembelianStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("No. Dokumen Pembelian") ?>,
                    staticText: {class: ".noDokumenPembelianStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Tanggal Dokumen Pembelian") ?>,
                    staticText: {class: ".tanggalDokumenPembelianStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Ref. HPS") ?>,
                    staticText: {class: ".noHpsStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Ref. Perencanaan") ?>,
                    staticText: {class: ".noDokumenRefStc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Jenis PL") ?>,
                    staticText: {class: ".jenisPlStc"}
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
            class: ".revisiBlk",
            column_1: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Revisi Ke") ?>,
                    staticText: {class: ".revisiKeStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Status Revisi") ?>,
                    staticText: {class: ".statusRevisiStc"}
                },
            },
            column_2: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                    staticText: {class: ".keteranganStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Keterangan Revisi") ?>,
                    staticText: {class: ".keteranganRevisiStc"}
                }
            }
        },
        row_3: {
            column: {
                button_1: {class: ".editBtn",    text: tlm.stringRegistry._<?= $h("Edit") ?>},
                button_2: {class: ".cetakBtn",   text: tlm.stringRegistry._<?= $h("Cetak") ?>},
                button_3: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_4: {
            widthTable: {
                class: ".revisiPembelianTbl",
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
        row_5: {
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
        const {toCurrency: currency, toUserInt: userInt, toUserFloat: userFloat} = tlm;
        const {toUserDate: userDate, numToShortMonthName: nToS, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */   const kodePembelianStc = divElm.querySelector(".kodePembelianStc");
        /** @type {HTMLDivElement} */   const noDokumenPembelianStc = divElm.querySelector(".noDokumenPembelianStc");
        /** @type {HTMLDivElement} */   const tanggalDokumenPembelianStc = divElm.querySelector(".tanggalDokumenPembelianStc");
        /** @type {HTMLDivElement} */   const noHpsStc = divElm.querySelector(".noHpsStc");
        /** @type {HTMLDivElement} */   const noDokumenRefStc = divElm.querySelector(".noDokumenRefStc");
        /** @type {HTMLDivElement} */   const jenisPlStc = divElm.querySelector(".jenisPlStc");
        /** @type {HTMLDivElement} */   const namaPemasokStc = divElm.querySelector(".namaPemasokStc");
        /** @type {HTMLDivElement} */   const subjenisAnggaranStc = divElm.querySelector(".subjenisAnggaranStc");
        /** @type {HTMLDivElement} */   const anggaranStc = divElm.querySelector(".anggaranStc");
        /** @type {HTMLDivElement} */   const sumberDanaStc = divElm.querySelector(".sumberDanaStc");
        /** @type {HTMLDivElement} */   const jenisHargaStc = divElm.querySelector(".jenisHargaStc");
        /** @type {HTMLDivElement} */   const revisiBlk = divElm.querySelector(".revisiBlk");
        /** @type {HTMLDivElement} */   const revisiKeStc = divElm.querySelector(".revisiKeStc");
        /** @type {HTMLDivElement} */   const statusRevisiStc = divElm.querySelector(".statusRevisiStc");
        /** @type {HTMLDivElement} */   const keteranganStc = divElm.querySelector(".keteranganStc");
        /** @type {HTMLDivElement} */   const keteranganRevisiStc = divElm.querySelector(".keteranganRevisiStc");
        /** @type {HTMLDivElement} */   const nilaiTotalStc = divElm.querySelector(".nilaiTotalStc");
        /** @type {HTMLDivElement} */   const nilaiDiskonStc = divElm.querySelector(".nilaiDiskonStc");
        /** @type {HTMLDivElement} */   const totalSetelahDiskonStc = divElm.querySelector(".totalSetelahDiskonStc");
        /** @type {HTMLInputElement} */ const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */   const nilaiPpnStc = divElm.querySelector(".nilaiPpnStc");
        /** @type {HTMLDivElement} */   const subtotalStc = divElm.querySelector(".subtotalStc");
        /** @type {HTMLDivElement} */   const nilaiPembulatanStc = divElm.querySelector(".nilaiPembulatanStc");
        /** @type {HTMLDivElement} */   const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");

        let kodePembelian;
        let statusLinked;
        let statusClosed;

        new spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.Pembelian.View.ContainerFields} data */
            loadData(data) {
                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = data;
                const tanggalDokumenPembelian = data.tanggalDokumenPembelian ? userDate(data.tanggalDokumenPembelian) : "";
                const tanggalJatuhTempo = data.tanggalJatuhTempo ?? "";
                const subsumberDana = data.subsumberDana ?? "";
                const caraBayar = data.caraBayar ?? "";

                kodePembelianStc.innerHTML = data.kodePembelian ?? "";
                noDokumenPembelianStc.innerHTML = data.noDokumenPembelian ?? "";
                tanggalDokumenPembelianStc.innerHTML = tanggalDokumenPembelian ? `${tanggalDokumenPembelian} (${tanggalJatuhTempo})` : "";
                noHpsStc.innerHTML = data.noHps ?? "";
                noDokumenRefStc.innerHTML = data.noDokumenRef ?? "";
                namaPemasokStc.innerHTML = data.namaPemasok ?? "";
                subjenisAnggaranStc.innerHTML = data.subjenisAnggaran ?? "";
                anggaranStc.innerHTML = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                sumberDanaStc.innerHTML = data.sumberDana ? `${data.sumberDana} (${subsumberDana})` : "";
                jenisHargaStc.innerHTML = data.jenisHarga ? `${data.jenisHarga} (${caraBayar})` : "";

                jenisPlStc.innerHTML =
                    (data.tipeDokumen == "0" && data.idJenisHarga == "2")   ? str._<?= $h("Kontrak Harga E-Katalog") ?>
                    : (data.tipeDokumen == "1" && data.idJenisHarga == "2") ? str._<?= $h("Kontrak E-Katalog") ?>
                    : (data.tipeDokumen == "1" /*-----------------------*/) ? str._<?= $h("Kontrak") ?>
                    : (data.tipeDokumen == "2" /*-----------------------*/) ? str._<?= $h("Surat Perintah Kerja") ?>
                    : (data.tipeDokumen == "3" /*-----------------------*/) ? str._<?= $h("Surat Pemesanan") ?>
                    : /*-------------------------------------------------*/   str._<?= $h("Kontrak Harga") ?>;

                ppnFld.checked = data.ppn == 10;

                revisiBlk.style.display = data.revisiKe == "0" ? "none" : "block";
                revisiKeStc.innerHTML = data.revisiKe ?? "";
                statusRevisiStc.innerHTML = data.verRevisi == "1" ? "Sudah Verifikasi ULP" : "Belum Verifikasi Revisi oleh ULP";
                keteranganStc.innerHTML = data.keterangan ?? "";
                keteranganRevisiStc.innerHTML = data.keteranganRevisi ?? ""; // keterangan_rev

                let totalSebelum = 0;
                let totalDiskon = 0;
                let totalSetelahDiskon = 0;
                divElm.querySelectorAll(".tr-data").forEach(item => {
                    const sebelum = item.hargaKemasan * item.jumlahKemasan;
                    const diskon = sebelum * item.diskonItem / 100;
                    totalSebelum += sebelum;
                    totalDiskon += diskon;
                    totalSetelahDiskon += sebelum - diskon;
                });

                const totalPpn = data.ppn * totalSetelahDiskon / 100;
                const subtotal = totalSetelahDiskon + totalPpn;
                const totalAkhir = subtotal + data.nilaiPembulatan;

                nilaiTotalStc.innerHTML = currency(totalSebelum);
                nilaiDiskonStc.innerHTML = currency(totalDiskon);
                totalSetelahDiskonStc.innerHTML = currency(totalSetelahDiskon);
                nilaiPpnStc.innerHTML = currency(totalPpn);
                subtotalStc.innerHTML = currency(subtotal);
                nilaiPembulatanStc.innerHTML = currency(data.nilaiPembulatan);
                nilaiAkhirStc.innerHTML = currency(totalAkhir);

                revisiPembelianWgt.load(data.daftarRevisiPembelian);

                kodePembelian = data.kodePembelian;
                statusLinked = data.statusLinked;
                statusClosed = data.statusClosed;
            },
            dataUrl: "<?= $dataUrl ?>",
        });

        const revisiPembelianWgt = new spl.TableWidget({
            element: divElm.querySelector(".revisiPembelianTbl"),
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
                12: {field: "diskonItem", formatter: tlm.floatFormatter},
                13: {field: "diskonHarga", formatter: tlm.floatFormatter},
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
                17: {field: "jumlahPl", formatter: tlm.floatFormatter},
                18: {formatter() {return "0,00"}},
                19: {formatter() {return "0,00"}},
                20: {formatter(unused, item) {
                    const {jumlahKemasan, isiKemasan, jumlahItemBonus, jumlahItemBeli} = item;
                    return userInt(jumlahKemasan * isiKemasan * jumlahItemBonus / jumlahItemBeli);
                }},
                21: {formatter(unused, {jumlahTerima}) {
                    const text = userFloat(jumlahTerima);
                    return kodePembelian && text ? draw({class: ".tablePenerimaanBtn", value: kodePembelian, text}) : text;
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

        revisiPembelianWgt.addDelegateListener("tbody", "click", event => {
            const viewPerencanaanBtn = event.target;
            if (!viewPerencanaanBtn.matches(".viewPerencanaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewPerencanaanWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewPerencanaanBtn.value}, true);
        });

        revisiPembelianWgt.addDelegateListener("tbody", "click", event => {
            const viewPengadaanBtn = event.target;
            if (!viewPengadaanBtn.matches(".viewPengadaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewPengadaanWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewPengadaanBtn.value}, true);
        });

        revisiPembelianWgt.addDelegateListener("tbody", "click", event => {
            const tablePenerimaanBtn = event.target;
            if (!tablePenerimaanBtn.matches(".tablePenerimaanBtn")) return;

            const widget = tlm.app.getWidget("_<?= $tablePenerimaanWidgetId ?>");
            widget.show();
            widget.loadData({filter:"P_"+kodePembelian}, true);
        });

        divElm.querySelector(".editBtn").addEventListener("click", () => {
            if (statusLinked != "0" || statusClosed != "0") return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodePembelian}, true);
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodePembelian}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(revisiPembelianWgt);
        tlm.app.registerWidget(this.constructor.widgetName, divElm);
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
