<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PenerimaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/viewsothers.php the original file
 */
final class ViewLainnya
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $formBonusWidgetId,
        string $formLainnyaWidgetId,
        string $formKonsinyasiWidgetId,
        string $viewRinciLainnyaWidgetId,
        string $cetakWidgetId,
        string $tableWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Penerimaan.ViewOthers {
    export interface ContainerFields {
        kodeTransaksi:            string;
        noDokumenPenerimaan:      string;
        tanggalDokumenPenerimaan: string;
        noFaktur:                 string;
        noSuratJalan:             string;
        noSpk:                    string;
        noPo:                     string;
        namaPemasok:              string;
        keterangan:               string;
        subjenisAnggaran:         string;
        bulanAwalAnggaran:        string;
        bulanAkhirAnggaran:       string;
        tahunAnggaran:            string;
        sumberDana:               string;
        subsumberDana:            string;
        jenisHarga:               string;
        caraBayar:                string;
        gudang:                   string;
        verifikasiTerima:         string;
        namaUserTerima:           string;
        verTanggalTerima:         string;
        verifikasiGudang:         string;
        namaUserGudang:           string;
        verTanggalGudang:         string;
        verifikasiAkuntansi:      string;
        namaUserAkuntansi:        string;
        verTanggalAkuntansi:      string;
        ppn:                      string;
        nilaiPembulatan:          string;
        tipeDokumen:              string; // *
        kodeRefKonsinyasi:        string; // *
        daftarDetailPenerimaan:   Array<DetailPenerimaan>;
        daftarRetur:              Array<Retur>;
    }

    export interface DetailPenerimaan {
        idKatalog:       string;
        namaSediaan:     string;
        namaPabrik:      string;
        kemasan:         string;
        isiKemasan:      string;
        jumlahKemasan:   string;
        hargaKemasan:    string;
        hargaItem:       string;
        diskonItem:      string;
        jumlahPo:        string;
        jumlahPl:        string;
        jumlahItemBeli:  string;
        jumlahItemBonus: string;
        jumlahTerima:    string;
    }

    export interface Retur {
        tanggalDokumen:   string;
        noDokumen:        string;
        namaPemasok:      string;
        noTerima:         string;
        noPo:             string;
        noSpk:            string;
        subjenisAnggaran: string;
        nilaiAkhir:       string;
        verTerima:        string;
        verGudang:        string;
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
                    label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                    staticText: {class: ".kodeTransaksiStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("No. Dokumen Penerimaan") ?>,
                    staticText: {class: ".noDokumenPenerimaanStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("No. Faktur/Surat Jalan") ?>,
                    staticText: {class: ".noFakturSuratJalanStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Ref. PL/PO") ?>,
                    staticText: {class: ".refPlStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                    staticText: {class: ".namaPemasokStc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                    staticText: {class: ".keteranganStc"}
                }
            },
            column_2: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Jenis Anggaran") ?>,
                    staticText: {class: ".jenisAnggaranStc"}
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
                    label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                    staticText: {class: ".gudangStc"}
                }
            }
        },
        row_4: {
            column: {
                button_1: {class: ".editBtn",    text: tlm.stringRegistry._<?= $h("Edit") ?>},
                button_2: {class: ".rincianBtn", text: tlm.stringRegistry._<?= $h("Rincian") ?>},
                button_3: {class: ".cetakBtn",   text: tlm.stringRegistry._<?= $h("Cetak") ?>},
                button_4: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>},
            }
        },
        row_5: {
            widthTable: {
                class: ".detailPenerimaanTbl",
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
                        td_11: /* 15-16 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
                    },
                    tr_2: {
                        td_1: /*  7 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_2: /*  8 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_3: /*  9 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_4: /* 10 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_5: /* 11 */ {text: tlm.stringRegistry._<?= $h("Total") ?>},
                        td_6: /* 12 */ {text: tlm.stringRegistry._<?= $h("%") ?>},
                        td_7: /* 13 */ {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                        td_8: /* 15 */ {text: tlm.stringRegistry._<?= $h("Bonus") ?>},
                        td_9: /* 16 */ {text: tlm.stringRegistry._<?= $h("Terima") ?>},
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
                    checkbox: {class: ".ppnFld"},
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
        },
        row_7: {
            table: {
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Ver.") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Otorisasi") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("User") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Perbarui Stok") ?>},
                    }
                },
                tbody: {
                    tr_1: {
                        td_1: {text: 1},
                        td_2: {
                            checkbox: {class: ".verifikasiTerimaFld", disabled: true}
                        },
                        td_3: {text: tlm.stringRegistry._<?= $h("Tim Penerima") ?>},
                        td_4: {class: ".namaUserTerimaStc"},
                        td_5: {class: ".verTanggalTerimaStc"},
                        td_6: {
                            checkbox: {disabled: true}
                        }
                    },
                    tr_2: {
                        td_1: {text: 2},
                        td_2: {
                            checkbox: {class: ".verifikasiGudangFld", disabled: true}
                        },
                        td_3: {text: tlm.stringRegistry._<?= $h("Gudang") ?>},
                        td_4: {class: ".namaUserGudangStc"},
                        td_5: {class: ".verTanggalGudangStc"},
                        td_6: {
                            checkbox: {class: ".perbaruiStokFld", disabled: true}
                        }
                    },
                    tr_3: {
                        td_1: {text: 3},
                        td_2: {
                            checkbox: {class: ".verifikasiAkuntansiFld", disabled: true}
                        },
                        td_3: {text: tlm.stringRegistry._<?= $h("Akuntansi") ?>},
                        td_4: {class: ".namaUserAkuntansiStc"},
                        td_5: {class: ".verTanggalAkuntansiStc"},
                        td_6: {
                            checkbox: {disabled: true}
                        }
                    }
                }
            }
        },
        row_8: {
            widthColumn: {text: tlm.stringRegistry._<?= $h("Retur CN Gudang Farmasi") ?>}
        },
        row_9: {
            widthTable: {
                class: ".daftarReturTbl",
                thead: {
                    tr: {
                        td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Tanggal Retur") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("No. Retur") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Referensi Terima") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Referensi PO") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Referensi PL") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Nilai Retur") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Status verifikasi") ?>},
                    }
                }
            }
        },
        row_10: {
            widthColumn: {class: ".jumlahStc"}
        }
    };

    constructor(divElm) {
        super();
        const {toCurrency: currency, toUserInt: userInt, toUserDate: userDate, numToShortMonthName: nToS, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */   const kodeTransaksiStc = divElm.querySelector(".kodeTransaksiStc");
        /** @type {HTMLDivElement} */   const noDokumenPenerimaanStc = divElm.querySelector(".noDokumenPenerimaanStc");
        /** @type {HTMLDivElement} */   const noFakturSuratJalanStc = divElm.querySelector(".noFakturSuratJalanStc");
        /** @type {HTMLDivElement} */   const refPlStc = divElm.querySelector(".refPlStc");
        /** @type {HTMLDivElement} */   const namaPemasokStc = divElm.querySelector(".namaPemasokStc");
        /** @type {HTMLDivElement} */   const keteranganStc = divElm.querySelector(".keteranganStc");
        /** @type {HTMLDivElement} */   const jenisAnggaranStc = divElm.querySelector(".jenisAnggaranStc");
        /** @type {HTMLDivElement} */   const anggaranStc = divElm.querySelector(".anggaranStc");
        /** @type {HTMLDivElement} */   const sumberDanaStc = divElm.querySelector(".sumberDanaStc");
        /** @type {HTMLDivElement} */   const jenisHargaStc = divElm.querySelector(".jenisHargaStc");
        /** @type {HTMLDivElement} */   const gudangStc = divElm.querySelector(".gudangStc");
        /** @type {HTMLDivElement} */   const nilaiTotalStc = divElm.querySelector(".nilaiTotalStc");
        /** @type {HTMLDivElement} */   const nilaiDiskonStc = divElm.querySelector(".nilaiDiskonStc");
        /** @type {HTMLDivElement} */   const totalSetelahDiskonStc = divElm.querySelector(".totalSetelahDiskonStc");
        /** @type {HTMLInputElement} */ const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */   const nilaiPpnStc = divElm.querySelector(".nilaiPpnStc");
        /** @type {HTMLDivElement} */   const subtotalStc = divElm.querySelector(".subtotalStc");
        /** @type {HTMLDivElement} */   const nilaiPembulatanStc = divElm.querySelector(".nilaiPembulatanStc");
        /** @type {HTMLDivElement} */   const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");
        /** @type {HTMLInputElement} */ const verifikasiTerimaFld = divElm.querySelector(".verifikasiTerimaFld");
        /** @type {HTMLDivElement} */   const namaUserTerimaStc = divElm.querySelector(".namaUserTerimaStc");
        /** @type {HTMLDivElement} */   const verTanggalTerimaStc = divElm.querySelector(".verTanggalTerimaStc");
        /** @type {HTMLInputElement} */ const verifikasiGudangFld = divElm.querySelector(".verifikasiGudangFld");
        /** @type {HTMLDivElement} */   const namaUserGudangStc = divElm.querySelector(".namaUserGudangStc");
        /** @type {HTMLDivElement} */   const verTanggalGudangStc = divElm.querySelector(".verTanggalGudangStc");
        /** @type {HTMLInputElement} */ const perbaruiStokFld = divElm.querySelector(".perbaruiStokFld");
        /** @type {HTMLInputElement} */ const verifikasiAkuntansiFld = divElm.querySelector(".verifikasiAkuntansiFld");
        /** @type {HTMLDivElement} */   const namaUserAkuntansiStc = divElm.querySelector(".namaUserAkuntansiStc");
        /** @type {HTMLDivElement} */   const verTanggalAkuntansiStc = divElm.querySelector(".verTanggalAkuntansiStc");
        /** @type {HTMLDivElement} */   const jumlahStc = divElm.querySelector(".jumlahStc");

        let tipeDokumen;
        let kodeRefKonsinyasi;

        const divWgt = new spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.Penerimaan.ViewOthers.ContainerFields} data */
            loadData(data) {
                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = data;
                const tanggalDokumenPenerimaan = data.tanggalDokumenPenerimaan ? userDate(data.tanggalDokumenPenerimaan) : "";
                const noPo = data.noPo ?? "";
                const subsumberDana = data.subsumberDana ?? "";
                const caraBayar = data.caraBayar ?? "";

                kodeTransaksiStc.innerHTML = data.kodeTransaksi ?? "";
                noDokumenPenerimaanStc.innerHTML = data.noDokumenPenerimaan ? `${data.noDokumenPenerimaan} (${tanggalDokumenPenerimaan})` : "";
                noFakturSuratJalanStc.innerHTML = (data.noFaktur ?? "-") + " / " + (data.noSuratJalan ?? "-");
                refPlStc.innerHTML = data.noSpk ? `${data.noSpk} (${noPo})` : "";
                namaPemasokStc.innerHTML = data.namaPemasok ?? "";
                keteranganStc.innerHTML = data.keterangan ?? "";
                jenisAnggaranStc.innerHTML = data.subjenisAnggaran ?? "";
                anggaranStc.innerHTML = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                sumberDanaStc.innerHTML = data.sumberDana ? `${data.sumberDana} (${subsumberDana})` : "";
                jenisHargaStc.innerHTML = data.jenisHarga ? `${data.jenisHarga} (${caraBayar})` : "";
                gudangStc.innerHTML = data.gudang ?? "";

                verifikasiGudangFld.checked = data.verifikasiGudang == "1";
                namaUserGudangStc.innerHTML = data.namaUserGudang ?? "-";
                verTanggalGudangStc.innerHTML = data.verTanggalGudang ? userDate(data.verTanggalGudang) : "-";
                verifikasiTerimaFld.checked = data.verifikasiTerima == "1";
                namaUserTerimaStc.innerHTML = data.namaUserTerima ?? "-";
                verTanggalTerimaStc.innerHTML = data.verTanggalTerima ? userDate(data.verTanggalTerima) : "-";
                perbaruiStokFld.checked = data.verifikasiTerima == "1";
                verifikasiAkuntansiFld.checked = data.verifikasiAkuntansi == "1";
                namaUserAkuntansiStc.innerHTML = data.namaUserAkuntansi ?? "-";
                verTanggalAkuntansiStc.innerHTML = data.verTanggalAkuntansi ? userDate(data.verTanggalAkuntansi) : "-";

                ppnFld.checked = data.ppn == 10;

                let totalSebelum = 0;
                let totalDiskon = 0;
                let totalSetelah = 0;
                data.daftarDetailPenerimaan.forEach(item => {
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

                detailPenerimaanWgt.load(data.daftarDetailPenerimaan);
                daftarReturWgt.load(data.daftarRetur);

                const jumlah = data.daftarRetur.reduce((acc, curr) => acc + curr.nilaiAkhir, 0);
                jumlahStc.innerHTML = str._<?= $h("Jumlah: {{NUM}}") ?>.replace("{{NUM}}", jumlah);

                tipeDokumen = data.tipeDokumen;
                kodeRefKonsinyasi = data.kodeRefKonsinyasi;
            },
            dataUrl: "<?= $dataUrl ?>",
        });

        const detailPenerimaanWgt = new spl.TableWidget({
            element: divElm.querySelector(".detailPenerimaanTbl"),
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
                13: {formatter(unused, item) {
                    const {jumlahKemasan, hargaKemasan, diskonItem} = item;
                    return currency(jumlahKemasan * hargaKemasan * diskonItem / 100);
                }},
                14: {formatter(unused, item) {
                    const {jumlahKemasan, hargaKemasan, diskonItem} = item;
                    return currency(jumlahKemasan * hargaKemasan * (100 - diskonItem) / 100);
                }},
                15: {formatter(unused, item) {
                    const {jumlahPo, jumlahPl, jumlahItemBonus, jumlahItemBeli} = item;
                    return userInt((jumlahPo || jumlahPl) * jumlahItemBonus / jumlahItemBeli);
                }},
                16: {field: "jumlahTerima", formatter: tlm.intFormatter}
            }
        });

        const daftarReturWgt = new spl.TableWidget({
            element: divElm.querySelector(".daftarReturTbl"),
            columns: {
                1:  {formatter: tlm.rowNumGenerator},
                2:  {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                3:  {field: "noDokumen"},
                4:  {field: "namaPemasok"},
                5:  {field: "noTerima"},
                6:  {field: "noPo"},
                7:  {field: "noSpk"},
                8:  {field: "subjenisAnggaran"},
                9:  {field: "nilaiAkhir", formatter: tlm.intFormatter},
                10: {formatter(unused, item) {
                    const {verTerima, verGudang} = item;
                    switch ("" + verTerima + verGudang) {
                        case "11": // continue
                        case "10": return str._<?= $h("Ver. Terima") ?>;
                        case "01": return str._<?= $h("Ver. Gudang") ?>;
                        case "00": return str._<?= $h("Belum Ver.") ?>;
                    }
                }}
            }
        });

        divElm.querySelector(".editBtn").addEventListener("click", () => {
            if (verifikasiGudangFld.checked) return;

            let widgetId;
            if (kodeRefKonsinyasi)     widgetId = "_<?= $formKonsinyasiWidgetId ?>";
            else if (tipeDokumen == 1) widgetId = "_<?= $formBonusWidgetId ?>";
            else /*-----------------*/ widgetId = "_<?= $formLainnyaWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadProfile("edit", {kode: kodeTransaksiStc.innerHTML}, true);
        });

        divElm.querySelector(".rincianBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $viewRinciLainnyaWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeTransaksiStc.innerHTML, revisiKe: 0}, true);
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeTransaksiStc.innerHTML, versi: 1}, true);
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(divWgt, detailPenerimaanWgt, daftarReturWgt);
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
