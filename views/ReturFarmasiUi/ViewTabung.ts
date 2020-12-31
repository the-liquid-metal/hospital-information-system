<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ReturFarmasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/viewstabung.php the original file
 */
final class ViewTabung
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $formWidgetId,
        string $viewRincianWidgetId,
        string $cetakWidgetId,
        string $tableWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ReturFarmasi.ViewTabung {
    export interface ContainerFields {
        kodeTransaksi:       string;
        noDokumenRetur:      string;
        tanggalDokumenRetur: string;
        namaPemasok:         string;
        gudang:              string;
        keterangan:          string;
        verifikasiGudang:    string;
        namaUserGudang:      string;
        verTanggalGudang:    string;
        verifikasiTerima:    string;
        namaUserTerima:      string;
        verTanggalTerima:    string;
        verifikasiAkuntansi: string;
        namaUserAkuntansi:   string;
        verTanggalAkuntansi: string;
        detailRetur:         Array<DetailRetur>;
    }

    export interface DetailRetur {
        jumlahKemasan: string;
        idKatalog:     string;
        namaSediaan:   string;
        namaPabrik:    string;
        kemasan:       string;
        isiKemasan:    string;
        noUrut:        string; // HALF MISSING
        noBatch:       string; // HALF MISSING
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
                    label: tlm.stringRegistry._<?= $h("No. Dokumen Retur") ?>,
                    staticText: {class: ".noDokumenReturStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Tanggal Dokumen Retur") ?>,
                    staticText: {class: ".tanggalDokumenReturStc"}
                },
            },
            column_2: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                    staticText: {class: ".namaPemasokStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Gudang") ?>,
                    staticText: {class: ".gudangStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                    staticText: {class: ".keteranganStc"}
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
                class: ".detailReturTbl",
                thead: {
                    tr_1: {
                        td_1: /* 1   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: /* 2   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_3: /* 3   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                        td_4: /* 4   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                        td_5: /* 5   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_6: /* 6   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                        td_7: /* 7-9 */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                    },
                    tr_2: {
                        td_1: /* 7 */ {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: /* 8 */ {text: tlm.stringRegistry._<?= $h("Batch") ?>},
                        td_3: /* 9 */ {text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                    }
                }
            }
        },
        row_6: {
            column: {class: ".jumlahTabungStc"}
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
                            checkbox: {class: ".verifikasiGudangFld", disabled: true}
                        },
                        td_3: {text: tlm.stringRegistry._<?= $h("Gudang") ?>},
                        td_4: {class: ".namaUserGudangStc"},
                        td_5: {class: ".verTanggalGudangStc"},
                        td_6: {
                            checkbox: {disabled: true}
                        }
                    },
                    tr_2: {
                        td_1: {text: 2},
                        td_2: {
                            checkbox: {class: ".verifikasiTerimaFld", disabled: true}
                        },
                        td_3: {text: tlm.stringRegistry._<?= $h("Tim Penerima") ?>},
                        td_4: {class: ".namaUserTerimaStc"},
                        td_5: {class: ".verTanggalTerimaStc"},
                        td_6: {
                            checkbox: {class: ".perbaruiStokFld", disabled: true},
                        }
                    },
                    tr_3: {
                        td_1: {text: 3},
                        td_2: {
                            checkbox: {class: ".verifikasiAkuntansiFld", disabled: true},
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
        }
    };

    constructor(divElm) {
        super();
        const {toUserDate: userDate, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */   const kodeTransaksiStc = divElm.querySelector(".kodeTransaksiStc");
        /** @type {HTMLDivElement} */   const noDokumenReturStc = divElm.querySelector(".noDokumenReturStc");
        /** @type {HTMLDivElement} */   const tanggalDokumenReturStc = divElm.querySelector(".tanggalDokumenReturStc");
        /** @type {HTMLDivElement} */   const namaPemasokStc = divElm.querySelector(".namaPemasokStc");
        /** @type {HTMLDivElement} */   const gudangStc = divElm.querySelector(".gudangStc");
        /** @type {HTMLDivElement} */   const keteranganStc = divElm.querySelector(".keteranganStc");
        /** @type {HTMLInputElement} */ const verifikasiGudangFld = divElm.querySelector(".verifikasiGudangFld");
        /** @type {HTMLDivElement} */   const namaUserGudangStc = divElm.querySelector(".namaUserGudangStc");
        /** @type {HTMLDivElement} */   const verTanggalGudangStc = divElm.querySelector(".verTanggalGudangStc");
        /** @type {HTMLInputElement} */ const verifikasiTerimaFld = divElm.querySelector(".verifikasiTerimaFld");
        /** @type {HTMLDivElement} */   const namaUserTerimaStc = divElm.querySelector(".namaUserTerimaStc");
        /** @type {HTMLDivElement} */   const verTanggalTerimaStc = divElm.querySelector(".verTanggalTerimaStc");
        /** @type {HTMLInputElement} */ const perbaruiStokFld = divElm.querySelector(".perbaruiStokFld");
        /** @type {HTMLInputElement} */ const verifikasiAkuntansiFld = divElm.querySelector(".verifikasiAkuntansiFld");
        /** @type {HTMLDivElement} */   const namaUserAkuntansiStc = divElm.querySelector(".namaUserAkuntansiStc");
        /** @type {HTMLDivElement} */   const verTanggalAkuntansiStc = divElm.querySelector(".verTanggalAkuntansiStc");
        /** @type {HTMLDivElement} */   const jumlahTabungStc = divElm.querySelector(".jumlahTabungStc");

        const divWgt = new spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.ReturFarmasi.ViewTabung.ContainerFields} data */
            loadData(data) {
                kodeTransaksiStc.innerHTML = data.kodeTransaksi ?? "";
                noDokumenReturStc.innerHTML = data.noDokumenRetur ?? "";
                tanggalDokumenReturStc.innerHTML = data.tanggalDokumenRetur ? userDate(data.tanggalDokumenRetur) : "";
                namaPemasokStc.innerHTML = data.namaPemasok ?? "";
                gudangStc.innerHTML = data.gudang ?? "";
                keteranganStc.innerHTML = data.keterangan ?? "";
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

                detailReturWgt.load(data.detailRetur);
                const jumlah = data.detailRetur.reduce((acc, curr) => acc + curr.jumlahKemasan, 0);
                jumlahTabungStc.innerHTML = str._<?= $h("Jumlah Tabung: {{NUM}}") ?>.replace("{{NUM}}", jumlah);
            },
            dataUrl: "<?= $dataUrl ?>",
        });

        divElm.querySelector(".editBtn").addEventListener("click", () => {
            if (verifikasiGudangFld.checked) return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadProfile("edit", {kode: kodeTransaksiStc.innerHTML}, true);
        });

        divElm.querySelector(".rincianBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $viewRincianWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeTransaksiStc.innerHTML}, true);
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakWidgetId ?>");
            widget.show();
            widget.loadData({xyz: kodeTransaksiStc.innerHTML}, true); // TODO: js: uncategorized: finish this
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        const detailReturWgt = new spl.TableWidget({
            element: divElm.querySelector(".detailReturTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "idKatalog"},
                3: {field: "namaSediaan"},
                4: {field: "namaPabrik"},
                5: {field: "kemasan"},
                6: {field: "isiKemasan", formatter: tlm.intFormatter},
                7: {field: "noUrut", formatter: val => val || "1"},
                8: {field: "noBatch", formatter: val => val || "-"},
                9: {field: "jumlahKemasan", formatter: tlm.intFormatter}
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(divWgt, detailReturWgt);
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
