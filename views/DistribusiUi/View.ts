<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\DistribusiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Distribusi/views.php the original file
 */
final class View
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $formGasMedisWidgetId,
        string $formReturGasMedisWidgetId,
        string $formWidgetId,
        string $viewWidgetId,
        string $cetakWidgetId,
        string $tableWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Distribusi.View {
    export interface ContainerFields {
        kodeDistribusi:           string;
        noDokumenDistribusi:      string;
        tanggalDokumenDistribusi: string;
        statusPrioritas:          string;
        verifikasiKirim:          string;
        namaUserKirim:            string;
        verTanggalKirim:          string;
        unitPengirim:             string;
        unitPenerima:             string;
        jenisPengiriman:          string;
        tipe:                     string;
        daftarDetailDistribusi:   Array<DetailDistribusi>;
    }

    export interface DetailDistribusi {
        idKatalog:       string;
        namaSediaan:     string;
        namaPabrik:      string;
        kodeSatuanBesar: string;
        jumlahKemasan:   string;
        jumlahItem:      string;
        hpItem:          string;
        itemTersedia:    string;
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
                    label: tlm.stringRegistry._<?= $h("Kode Distribusi") ?>,
                    staticText: {class: ".kodeDistribusiStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("No. Dokumen Distribusi") ?>,
                    staticText: {class: ".noDokumenDistribusiStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Tanggal Dokumen Distribusi") ?>,
                    staticText: {class: ".tanggalDokumenDistribusiStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Status Verifikasi") ?>,
                    staticText: {class: ".statusVerifikasiStc"}
                }
            },
            column_2: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Unit Pengirim") ?>,
                    staticText: {class: ".unitPengirimStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Unit Penerima") ?>,
                    staticText: {class: ".unitPenerimaStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Jenis Pengiriman") ?>,
                    staticText: {class: ".jenisPengirimanStc"}
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
                class: ".detailDistribusiTbl",
                thead: {
                    tr_1: {
                        td_1: /* 1   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: /* 2   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_3: /* 3   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                        td_4: /* 4   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                        td_5: /* 5   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_6: /* 6-7 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Jumlah Distribusi") ?>},
                        td_7: /* 8   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("HP") ?>},
                        td_8: /* 9   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Total") ?>},
                    },
                    tr_2: {
                        td_1: /* 6 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_2: /* 7 */ {text: tlm.stringRegistry._<?= $h("Item") ?>},
                    }
                }
            }
        },
        row_6: {
            column: {
                formGroup: {
                    label: tlm.stringRegistry._<?= $h("Total Setelah Pembulatan") ?>,
                    staticText: {class: ".nilaiAkhirStc"}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {toUserDate: userDate, toCurrency: currency} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */ const kodeDistribusiStc = divElm.querySelector(".kodeDistribusiStc");
        /** @type {HTMLDivElement} */ const noDokumenDistribusiStc = divElm.querySelector(".noDokumenDistribusiStc");
        /** @type {HTMLDivElement} */ const tanggalDokumenDistribusiStc = divElm.querySelector(".tanggalDokumenDistribusiStc");
        /** @type {HTMLDivElement} */ const statusVerifikasiStc = divElm.querySelector(".statusVerifikasiStc");
        /** @type {HTMLDivElement} */ const unitPengirimStc = divElm.querySelector(".unitPengirimStc");
        /** @type {HTMLDivElement} */ const unitPenerimaStc = divElm.querySelector(".unitPenerimaStc");
        /** @type {HTMLDivElement} */ const jenisPengirimanStc = divElm.querySelector(".jenisPengirimanStc");
        /** @type {HTMLDivElement} */ const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");

        let tipe;
        let verifikasiKirim;

        const viewWgt = new spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.Distribusi.View.ContainerFields} data */
            loadData(data) {
                const prioritas = data.statusPrioritas == "1" ? "CITO" : "REGULAR";
                const tanggalDokumenDistribusi = data.tanggalDokumenDistribusi ? userDate(data.tanggalDokumenDistribusi) : "";
                const verTanggalKirim = data.verTanggalKirim ? userDate(data.verTanggalKirim) : "";

                kodeDistribusiStc.innerHTML = data.kodeDistribusi ?? "";
                noDokumenDistribusiStc.innerHTML = data.noDokumenDistribusi ?? "";
                tanggalDokumenDistribusiStc.innerHTML = tanggalDokumenDistribusi ? `${tanggalDokumenDistribusi}. Prioritas: ${prioritas}` : "";
                statusVerifikasiStc.innerHTML = data.verifikasiKirim == "0" ? "Belum Verifikasi Pengiriman" : `${data.namaUserKirim} (${verTanggalKirim})`;
                unitPengirimStc.innerHTML = data.unitPengirim ?? "";
                unitPenerimaStc.innerHTML = data.unitPenerima ?? "";
                jenisPengirimanStc.innerHTML = data.jenisPengiriman ?? "";

                detailDistribusiWgt.load(data.daftarDetailDistribusi);
                nilaiAkhirStc.innerHTML = data.daftarDetailDistribusi.reduce((acc, curr) => {
                    const {itemTersedia, hpItem} = curr;
                    return acc + (itemTersedia * hpItem);
                });

                tipe = data.tipe;
                verifikasiKirim = data.verifikasiKirim;
            },
            dataUrl: "<?= $dataUrl ?>",
        });

        const detailDistribusiWgt = new spl.TableWidget({
            element: divElm.querySelector(".detailDistribusiTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "idKatalog"},
                3: {field: "namaSediaan"},
                4: {field: "namaPabrik"},
                5: {field: "kodeSatuanBesar"},
                6: {field: "jumlahKemasan", formatter: tlm.intFormatter},
                7: {field: "jumlahItem", formatter: tlm.intFormatter},
                8: {field: "hpItem", formatter: tlm.currencyFormatter},
                9: {formatter(unused, item) {
                    const {itemTersedia, hpItem} = item;
                    return currency(itemTersedia * hpItem);
                }}
            }
        });

        divElm.querySelector(".editBtn").addEventListener("click", () => {
            if (verifikasiKirim != "0") return;

            let widgetId;
            switch (tipe) {
                case "3": widgetId = "<?= $formGasMedisWidgetId ?>"; break;
                case "6": widgetId = "<?= $formReturGasMedisWidgetId ?>"; break;
                default:  widgetId = "<?= $formWidgetId ?>";
            }
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode: kodeDistribusiStc.innerHTML}, true);
        });

        divElm.querySelector(".rincianBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeDistribusiStc.innerHTML}, true);
        });

        divElm.querySelector(".cetakBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakWidgetId ?>");
            widget.show();
            widget.loadData({xyz: kodeDistribusiStc.innerHTML}, true); // TODO: js: uncategorized: finish this
        });

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(viewWgt, detailDistribusiWgt);
        tlm.app.registerWidget(this.constructor.widgetName, viewWgt);
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
