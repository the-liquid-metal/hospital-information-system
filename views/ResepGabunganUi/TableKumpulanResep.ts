<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ResepGabunganUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/resepgabungan/listkumpulanresep.php the original file
 */
final class TableKumpulanResep
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $printItemUrl,
        string $cetakObatWidgetId,
        string $cetakObatLqWidgetId,
        string $cetakNoResepWidgetId,
        string $cetakNoResepLqWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ResepGabungan.ListKumpulanResep {
    export interface FormFields {
        kodeRekamMedis: string;
        noPendaftaran:  string;
        dariTanggal:    string;
        sampaiTanggal:  string;
    }

    export interface TableFields {
        kodeRekamMedis: string;
        noPendaftaran:  string;
        namaPasien:     string;
        tanggalAwal:    string;
        tanggalAkhir:   string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName + " .printAreaBlk"]: {
            "table": {
                _style: {width: "100%"},
                "td": {
                    _style: {verticalAlign: "top", fontSize: "14px"}
                },
            },
            ".detailTbl td:nth-child(5)": {
                _style: {textAlign: "right"}
            },
            ".btn-success": {
                _suffixes_1: [""],
                _style_1: {background: "#37ef10", color: "black", border: "thin solid black", borderRadius: "2px"},
                _suffixes_2: [":hover"],
                _style_2: {background: "#1aff00", color: "white"}
            }
        }
    };

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Riwayat Obat") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".saringFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                        input: {class: ".noPendaftaranFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Dari Tanggal") ?>,
                        input: {class: ".dariTanggalFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Sampai Tanggal") ?>,
                        input: {class: ".sampaiTanggalFld"}
                    }
                }
            },
            row_3: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            }
        },
        row_3: {
            column: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_4: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Nama Pasien") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Tanggal Awal") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                    }
                }
            }
        },
        row_5: {
            column: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_6: {
            column: {
                button_1: {class: ".cetakObatBtn",      text: tlm.stringRegistry._<?= $h("Print Obat") ?>},
                button_2: {class: ".cetakObatLqBtn",    text: tlm.stringRegistry._<?= $h("Print Obat-LQ") ?>},
                button_3: {class: ".cetakNoResepBtn",   text: tlm.stringRegistry._<?= $h("Print No. Resep") ?>},
                button_4: {class: ".cetakNoResepLqBtn", text: tlm.stringRegistry._<?= $h("Print No. Resep-LQ") ?>},
            }
        },
        row_7: {
            column: {class: ".printAreaBlk"}
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodeRekamMedisFld = divElm.querySelector(".kodeRekamMedisFld");
        /** @type {HTMLInputElement} */  const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLDivElement} */    const printAreaBlk = divElm.querySelector(".printAreaBlk");
        /** @type {HTMLButtonElement} */ const cetakObatBtn = divElm.querySelector(".cetakObatBtn");
        /** @type {HTMLButtonElement} */ const cetakObatLqBtn = divElm.querySelector(".cetakObatLqBtn");
        /** @type {HTMLButtonElement} */ const cetakNoResepBtn = divElm.querySelector(".cetakNoResepBtn");
        /** @type {HTMLButtonElement} */ const cetakNoResepLqBtn = divElm.querySelector(".cetakNoResepLqBtn");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.ResepGabungan.ListKumpulanResep.FormFields} data */
            loadData(data) {
                kodeRekamMedisFld.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                dariTanggalWgt.value = data.dariTanggal ?? "";
                sampaiTanggalWgt.value = data.sampaiTanggal ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        kodeRekamMedis: kodeRekamMedisFld.value,
                        noPendaftaran: noPendaftaranFld.value,
                        dariTanggal: dariTanggalWgt.value,
                        sampaiTanggal: sampaiTanggalWgt.value,
                    }
                });
            }
        });

        const dariTanggalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".dariTanggalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const sampaiTanggalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".sampaiTanggalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "kodeRekamMedis"},
                3: {field: "noPendaftaran"},
                4: {field: "namaPasien"},
                5: {field: "tanggalAwal", formatter: tlm.dateFormatter},
                6: {field: "tanggalAkhir", formatter: tlm.dateFormatter},
                7: {formatter(unused, {noPendaftaran}) {
                    cetakObatBtn.value = noPendaftaran;
                    cetakObatLqBtn.value = noPendaftaran;
                    cetakNoResepBtn.value = noPendaftaran;
                    cetakNoResepLqBtn.value = noPendaftaran;
                    return draw({class: ".viewBtn", type: "info", value: noPendaftaran, text: str._<?= $h("Lihat") ?>});
                }}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            $.post({
                url: "<?= $printItemUrl ?>",
                data: {noPendaftaran: viewBtn.value},
                success(html) {printAreaBlk.innerHTML = html}
            });
        });

        cetakObatBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakObatWidgetId ?>");
            widget.show();
            widget.loadData({noPendaftaran: cetakObatBtn.value}, true);
        });

        cetakObatLqBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakObatLqWidgetId ?>");
            widget.show();
            widget.loadData({noPendaftaran: cetakObatLqBtn.value}, true);
        });

        cetakNoResepBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakNoResepWidgetId ?>");
            widget.show();
            widget.loadData({noPendaftaran: cetakNoResepBtn.value}, true);
        });

        cetakNoResepLqBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakNoResepLqWidgetId ?>");
            widget.show();
            widget.loadData({noPendaftaran: cetakNoResepLqBtn.value}, true);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, dariTanggalWgt, sampaiTanggalWgt, itemWgt);
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
