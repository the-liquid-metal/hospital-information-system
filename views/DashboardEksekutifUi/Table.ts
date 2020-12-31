<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\DashboardEksekutifUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * truely multiple origin:
 * @see http://localhost/ori-source/fatma-pharmacy/views/dashboardeksekutif/index.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/dashboardeksekutif/monitoringstok.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/dashboardeksekutif/pl.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/dashboardeksekutif/realisasi.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/dashboardeksekutif/rencana.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string $registerId,
        string $actionUrl,
        string $stokDataUrl,
        string $rencanaDataUrl,
        string $realisasiDataUrl,
        string $pemesananDataUrl,
        string $detailPemesananDataUrl,
        string $depoSelect,
        string $bulanSelect,
        string $tahunSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.DashboardEksekutifUi.Table {
    export interface Fields {
        idDepo:             string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
    }

    export interface PemesananFields {
        kode:               string;
        noDokumen:          string;
        tanggalTempoKirim:  string;
        tanggalMulai:       string;
        tanggalJatuhTempo:  string;
        tahunAnggaran:      string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        jenisObat:          string;
        sumberDana:         string;
        subsumberDana:      string;
        caraBayar:          string;
        jenisHarga:         string;
        namaPemasok:        string;
        noSpk:              string;
    }

    export interface DetailPemesanan {
        idKatalog:     string;
        jumlahItem:    string;
        jumlahKemasan: string;
        hargaItem:     string;
        hargaKemasan:  string;
        diskonItem:    string;
        diskonHarga:   string;
        hargaTotal:    string;
        hargaAkhir:    string;
        namaBarang:    string;
        namaPabrik:    string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Dashboard Eksekutif") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".saringFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Unit Store") ?>,
                        select: {class: ".idDepoFld", name: "idDepo"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            }
        },
        row_3: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_4: {
            widthColumn: {class: ".stokKatalogBlk"}
        },
        row_5: {
            column: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Grand Total Stok") ?>,
                    staticText: {class: ".grandTotalStokStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Grand Total Rencana") ?>,
                    staticText: {class: ".grandTotalRencanaStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Grand Total Realisasi") ?>,
                    staticText: {class: ".grandTotalRealisasiStc"}
                }
            }
        },
        modal_1: {
            class: ".stokMdl",
            title: tlm.stringRegistry._<?= $h("Stok") ?>,
            row_1: {
                paragraph: {class: ".stokTitleStc"},
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
            }
        },
        modal_2: {
            class: ".rencanaMdl",
            title: tlm.stringRegistry._<?= $h("Rencana") ?>,
            row: {
                widthTable: {
                    class: ".rencanaTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("No. SPK") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("No. Dokumen") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Kebutuhan") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {colspan: 4, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_2: {class: ".totalRencanaStc"}
                        }
                    }
                }
            }
        },
        modal_3: {
            class: ".realisasiMdl",
            title: tlm.stringRegistry._<?= $h("Realisasi") ?>,
            row: {
                widthTable: {
                    class: ".realisasiTbl",
                    thead: {
                        tr_1: {
                            td_1: /* 1   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: /* 2   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No. SPK") ?>},
                            td_3: /* 3   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No. Pemesanan") ?>},
                            td_4: /* 4   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kebutuhan") ?>},
                            td_5: /* 5-6 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                        },
                        tr_2: {
                            td_1: /* 5 */ {text: tlm.stringRegistry._<?= $h("PL") ?>},
                            td_2: /* 6 */ {text: tlm.stringRegistry._<?= $h("PO") ?>},
                        },
                    },
                    tfoot: {
                        tr: {
                            td_1: {colspan: 4, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_2: {class: ".totalSpkStc"},
                            td_3: {class: ".totalDoStc"},
                        }
                    }
                }
            }
        },
        modal_4: {
            class: ".plMdl",
            title: tlm.stringRegistry._<?= $h("Pl") ?>,
            row_1: {
                column_1: {
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        staticText: {class: ".kodePemesananStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        staticText: {class: ".noDokumenStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Tempo Kirim") ?>,
                        staticText: {class: ".tanggalTempoKirimStc"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. SPK") ?>,
                        select: {class: ".noSpkStc"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Kontrak") ?>,
                        select: {class: ".tanggalKontrakStc"}
                    }
                },
                column_2: {
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        staticText: {class: ".namaPemasokStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Jenis Anggaran") ?>,
                        staticText: {class: ".jenisObatStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Periode Anggaran") ?>,
                        staticText: {class: ".periodeAnggaranStc"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".sumberDanaStc"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".jenisHargaStc"}
                    }
                },
            },
            row_2: {
                widthTable: {
                    class: ".plTbl",
                    thead: {
                        tr_1: {
                            td_1: /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kode") ?>},
                            td_3: /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_4: /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_5: /*  5-6  */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                            td_6: /*  7-9  */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_7: /* 10-11 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_8: /* 12    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Total") ?>},
                        },
                        tr_2: {
                            td_1: /*  5 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_2: /*  6 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_3: /*  7 */ {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_4: /*  8 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5: /*  9 */ {text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_6: /* 10 */ {text: tlm.stringRegistry._<?= $h("%") ?>},
                            td_7: /* 11 */ {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        }
                    }
                }
            }
        }
    };

    _itemTpl = {
        row_1: {
            paragraph: {text: "{{NAMA_KELOMPOK}}"}
        },
        row_2: {
            column: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Total Stok") ?>,
                    staticText: {text: "{{TOTAL_STOK}}"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Total Rencana") ?>,
                    staticText: {text: "{{TOTAL_RENCANA}}"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Total Realiasi") ?>,
                    staticText: {text: "{{TOTAL_REALIASI}}"}
                }
            }
        },
        row_3: {
            widthTable: {
                dataItem: "{{DATA_ITEM}}",
                thead: {
                    tr_1: {
                        td_1: {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_3: {rowspan: 2, text: tlm.stringRegistry._<?= $h("Barang") ?>},
                        td_4: {colspan: 2, text: tlm.stringRegistry._<?= $h("Stok") ?>},
                        td_5: {colspan: 2, text: tlm.stringRegistry._<?= $h("Rencana") ?>},
                        td_6: {colspan: 2, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
                    },
                    tr_2: {
                        td_1: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Jumlah (Rp.)") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Jumlah (Rp.)") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Jumlah (Rp.)") ?>},
                    }
                }
            }
        },
        row_4: {
            paragraph: {text: "&nbsp;"}
        }
    };

    constructor(divElm) {
        super();
        const {toUserFloat: userFloat, numToShortMonthName: nToS, toUserDate: userDate} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;
        const itemTpl = spl.LayoutDrawer.draw(this._itemTpl).content;

        /** @type {HTMLSelectElement} */ const idDepoFld = divElm.querySelector(".idDepoFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLDivElement} */    const stokKatalogBlk = divElm.querySelectorAll(".stokKatalogBlk");
        /** @type {HTMLDivElement} */    const stokMdl = divElm.querySelectorAll(".stokMdl");
        /** @type {HTMLDivElement} */    const rencanaMdl = divElm.querySelectorAll(".rencanaMdl");
        /** @type {HTMLDivElement} */    const realisasiMdl = divElm.querySelectorAll(".realisasiMdl");
        /** @type {HTMLDivElement} */    const plMdl = divElm.querySelectorAll(".plMdl");
        /** @type {HTMLDivElement} */    const totalRencanaStc = divElm.querySelectorAll(".totalRencanaStc");
        /** @type {HTMLDivElement} */    const totalSpkStc = divElm.querySelectorAll(".totalSpkStc");
        /** @type {HTMLDivElement} */    const totalDoStc = divElm.querySelectorAll(".totalDoStc");
        /** @type {HTMLDivElement} */    const grandTotalStokStc = divElm.querySelectorAll(".grandTotalStokStc");
        /** @type {HTMLDivElement} */    const grandTotalRencanaStc = divElm.querySelectorAll(".grandTotalRencanaStc");
        /** @type {HTMLDivElement} */    const grandTotalRealisasiStc = divElm.querySelectorAll(".grandTotalRealisasiStc");
        /** @type {HTMLDivElement} */    const stokTitleStc = divElm.querySelectorAll(".stokTitleStc");
        /** @type {HTMLDivElement} */    const kodePemesananStc = divElm.querySelectorAll(".kodePemesananStc");
        /** @type {HTMLDivElement} */    const noDokumenStc = divElm.querySelectorAll(".noDokumenStc");
        /** @type {HTMLDivElement} */    const tanggalTempoKirimStc = divElm.querySelectorAll(".tanggalTempoKirimStc");
        /** @type {HTMLDivElement} */    const noSpkStc = divElm.querySelectorAll(".noSpkStc");
        /** @type {HTMLDivElement} */    const tanggalKontrakStc = divElm.querySelectorAll(".tanggalKontrakStc");
        /** @type {HTMLDivElement} */    const namaPemasokStc = divElm.querySelectorAll(".namaPemasokStc");
        /** @type {HTMLDivElement} */    const jenisObatStc = divElm.querySelectorAll(".jenisObatStc");
        /** @type {HTMLDivElement} */    const periodeAnggaranStc = divElm.querySelectorAll(".periodeAnggaranStc");
        /** @type {HTMLDivElement} */    const sumberDanaStc = divElm.querySelectorAll(".sumberDanaStc");
        /** @type {HTMLDivElement} */    const jenisHargaStc = divElm.querySelectorAll(".jenisHargaStc");

        tlm.app.registerSelect("_<?= $depoSelect ?>", idDepoFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(idDepoFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld, tahunAnggaranFld);

        /** @type {spl.TableWidget[]} */ let tablePool = [];

        const saringWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.DashboardEksekutifUi.Table.Fields} data */
            loadData(data) {
                idDepoFld.value = data.idDepo ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                tablePool.forEach(widget => widget.destroy());
                tablePool = [];

                const {daftarStokKatalog, daftarTotal, grandtotalStok, grandtotalRencana, grandtotalRealisasi} = event.data;
                let temp = "";
                for (const namaKelompok in daftarStokKatalog) {
                    const {stok, rencana, realisasi} = daftarTotal[namaKelompok];
                    temp += itemTpl.replace("{{NAMA_KELOMPOK}}", namaKelompok)
                        .replace("{{TOTAL_STOK}}", stok)
                        .replace("{{TOTAL_RENCANA}}", rencana)
                        .replace("{{TOTAL_REALIASI}}", realisasi)
                        .replace("{{DATA_ITEM}}", namaKelompok);
                }
                stokKatalogBlk.innerHTML = temp;

                grandTotalStokStc.innerHTML = userFloat(/** @type {string} */ grandtotalStok);
                grandTotalRencanaStc.innerHTML = userFloat(/** @type {string} */ grandtotalRencana);
                grandTotalRealisasiStc.innerHTML = userFloat(/** @type {string} */ grandtotalRealisasi);

                stokKatalogBlk.querySelectorAll("table").forEach(/** @type {HTMLTableElement} */item => {
                    const widget = new spl.TableWidget({
                        element: item,
                        data: daftarStokKatalog[item.getAttribute("dataItem")],
                        columns: {
                            1: {formatter: tlm.rowNumGenerator},
                            2: {field: "kodeBarang"},
                            3: {field: "namaBarang"},
                            4: {formatter(unused, item) {
                                const {kodeBarang, namaBarang, jumlahStokFisik} = item;
                                return draw({class: ".cekStokBtn", value: JSON.stringify([kodeBarang, namaBarang]), text: userFloat(jumlahStokFisik)});
                            }},
                            5: {formatter(unused, item) {
                                const {jumlahStokFisik, hpItem} = item;
                                return userFloat(jumlahStokFisik * hpItem);
                            }},
                            6: {formatter(unused, item) {
                                const {kodeBarang, jumlahItem} = item;
                                return draw({class: ".cekRencanaBtn", value: kodeBarang, text: userFloat(jumlahItem)});
                            }},
                            7: {formatter(unused, item) {
                                const {jumlahItem, hargaItem} = item;
                                return userFloat(jumlahItem * hargaItem);
                            }},
                            8: {formatter(unused, item) {
                                const {kodeBarang, jumlahItemR} = item;
                                return draw({class: ".cekRealisasiBtn", value: kodeBarang, text: userFloat(jumlahItemR)});
                            }},
                            9: {formatter(unused, item) {
                                const {jumlahItemR, hargaItemR} = item;
                                return userFloat(jumlahItemR * hargaItemR);
                            }}
                        }
                    });
                    tablePool.push(widget);
                });
            },
            onFailSubmit() {
                alert(str._<?= $h("terjadi error") ?>);
            }
        });

        stokKatalogBlk.addEventListener("click", (event) => {
            const cekStokBtn = event.target;
            if (!cekStokBtn.matches(".cekStokBtn")) return;
            const [kodeBarang, namaBarang] = JSON.parse(cekStokBtn.value);

            stokMdl.style.display = "block";
            rencanaMdl.style.display = "none";
            realisasiMdl.style.display = "none";
            plMdl.style.display = "none";

            stokTitleStc.innerHTML = namaBarang + " (" + kodeBarang + ")";
            stokWgt.refresh({
                query: {kode: kodeBarang}
            });
        });

        stokKatalogBlk.addEventListener("click", (event) => { // missing ".cekpl"
            const cekPlBtn = event.target;
            if (!cekPlBtn.matches(".cekPlBtn")) return;

            stokMdl.style.display = "none";
            rencanaMdl.style.display = "none";
            realisasiMdl.style.display = "none";
            plMdl.style.display = "block";

            $.post({
                url: "<?= $pemesananDataUrl ?>",
                data: {kode: cekPlBtn.value},
                /** @param {his.FatmaPharmacy.views.DashboardEksekutifUi.Table.PemesananFields} data */
                success(data) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = data;
                    kodePemesananStc.innerHTML = data.kode;
                    noDokumenStc.innerHTML = data.noDokumen;
                    tanggalTempoKirimStc.innerHTML = userDate(data.tanggalTempoKirim);
                    noSpkStc.innerHTML = data.noSpk;
                    tanggalKontrakStc.innerHTML = userDate(data.tanggalMulai) + "-" + userDate(data.tanggalJatuhTempo);
                    namaPemasokStc.innerHTML = data.namaPemasok;
                    jenisObatStc.innerHTML = data.jenisObat;
                    periodeAnggaranStc.innerHTML = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                    sumberDanaStc.innerHTML = data.sumberDana + " (sub: " + data.subsumberDana + ")";
                    jenisHargaStc.innerHTML = data.jenisHarga + "(cara bayar: " + data.caraBayar + ")";
                }
            });

            plWgt.refresh({
                query: {kode: cekPlBtn.value}
            });
        });

        stokKatalogBlk.addEventListener("click", (event) => {
            const cekRealisasiBtn = event.target;
            if (!cekRealisasiBtn.matches(".cekRealisasiBtn")) return;

            stokMdl.style.display = "none";
            rencanaMdl.style.display = "none";
            realisasiMdl.style.display = "block";
            plMdl.style.display = "none";

            realisasiWgt.refresh({
                query: {kode: cekRealisasiBtn.value, awal: bulanAwalAnggaranFld.value, akhir: bulanAkhirAnggaranFld.value}
            });
        });

        stokKatalogBlk.addEventListener("click", (event) => {
            const cekRencanaBtn = event.target;
            if (!cekRencanaBtn.matches(".cekRencanaBtn")) return;

            stokMdl.style.display = "none";
            rencanaMdl.style.display = "block";
            realisasiMdl.style.display = "none";
            plMdl.style.display = "none";

            rencanaWgt.refresh({
                query: {kode: cekRencanaBtn.value, awal: bulanAwalAnggaranFld.value, akhir: bulanAkhirAnggaranFld.value}
            });
        });

        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            url: "<?= $stokDataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            }
        });

        const rencanaWgt = new spl.TableWidget({
            element: divElm.querySelector(".rencanaTbl"),
            url: "<?= $rencanaDataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noSpk"},
                3: {field: "noDokumen"},
                4: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir));
                }},
                5: {field: "jumlahItem", formatter: tlm.floatFormatter},
            },
            onPostBody() {
                const totalRencana = this._data.reduce((acc, {jumlahItem}) => acc + jumlahItem);
                totalRencanaStc.innerHTML = userFloat(totalRencana);
            }
        });

        const realisasiWgt = new spl.TableWidget({
            element: divElm.querySelector(".realisasiTbl"),
            url: "<?= $realisasiDataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noSpk"},
                3: {field: "noDokumen"},
                4: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir));
                }},
                5: {field: "jumlahSpk", formatter: tlm.floatFormatter},
                6: {field: "jumlahDo", formatter: tlm.floatFormatter}
            },
            /** @this {spl.TableWidget} */
            onPostBody() {
                const totalSpk = this._data.reduce((acc, {jumlahSpk}) => acc + jumlahSpk);
                totalSpkStc.innerHTML = userFloat(totalSpk);

                const totalDo = this._data.reduce((acc, {jumlahDo}) => acc + jumlahDo);
                totalDoStc.innerHTML = userFloat(totalDo);
            }
        });

        const plWgt = new spl.TableWidget({
            element: divElm.querySelector(".plTbl"),
            url: "<?= $detailPemesananDataUrl ?>",
            columns: {
                1:  {formatter: tlm.rowNumGenerator},
                2:  {field: "idKatalog"},
                3:  {field: "namaBarang"},
                4:  {field: "namaPabrik"},
                5:  {field: "jumlahItem",    formatter: tlm.floatFormatter},
                6:  {field: "jumlahKemasan", formatter: tlm.floatFormatter},
                7:  {field: "hargaItem",     formatter: tlm.floatFormatter},
                8:  {field: "hargaKemasan",  formatter: tlm.floatFormatter},
                9:  {field: "hargaTotal",    formatter: tlm.floatFormatter},
                10: {field: "diskonItem",    formatter: tlm.floatFormatter},
                11: {field: "diskonHarga",   formatter: tlm.floatFormatter},
                12: {field: "hargaAkhir",    formatter: tlm.floatFormatter}
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, stokWgt, rencanaWgt, realisasiWgt, plWgt);
        tlm.app.registerWidget(this.constructor.widgetName, saringWgt);
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
