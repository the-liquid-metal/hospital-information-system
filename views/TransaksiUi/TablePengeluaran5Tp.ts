<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\TransaksiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/pengeluaran5tp.php the original file
 */
final class TablePengeluaran5Tp
{
    private string $output;

    public function __construct(
        string $registerId,
        string $data1Url,
        string $data2Url,
        string $viewPengeluaranWidgetId,
        string $formPengirimanTpWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.Pengeluaran5Tp {
    export interface FormFields {
        dariTanggal:   string;
        sampaiTanggal: string;
    }

    export interface PengeluaranFields {
        tipePengiriman: string;
        noDokumen:      string;
        namaDepo:       string;
        tanggal:        string;
        prioritas:      string;
        noPermintaan:   string;
    }
    export interface KeluarFields {
        tipePengiriman:      string;
        noDokumen:           string;
        noDokumenPengiriman: string;
        namaDepo:            string;
        tanggal:             string;
        prioritas:           string;
        noPermintaan:        string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Pengeluaran") ?>}
            }
        },
        row_2: {
            widthColumn: {
                button: {class: ".tambahBtn", text: tlm.stringRegistry._<?= $h("Tambah") ?>}
            }
        },
        form: {
            class: ".saringFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Dari Tanggal") ?>,
                        input: {class: ".dariTanggalFld", name: "dariTanggal"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Sampai Tanggal") ?>,
                        input: {class: ".sampaiTanggalFld", name: "sampaiTanggal"}
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
            heading4: {text: tlm.stringRegistry._<?= $h("Daftar Pengeluaran") ?>}
        },
        row_4: {
            widthTable: {
                class: ".listPengeluaranTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Tipe Pengiriman") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("No. Pengeluaran") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Depo Peminta") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Status") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                    }
                }
            }
        },
        row_5: {
            widthColumn: {
                paragraph: {text: "&nbsp"}
            }
        },
        row_6: {
            heading4: {text: tlm.stringRegistry._<?= $h("Daftar Keluar") ?>}
        },
        row_7: {
            widthTable: {
                class: ".listKeluarTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Tipe Pengiriman") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("No. Pengeluaran") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Depo Peminta") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Status") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran5Tp.FormFields} data */
            loadData(data) {
                dariTanggalWgt.value = data.dariTanggal ?? "";
                sampaiTanggalWgt.value = data.sampaiTanggal ?? "";
            },
            submit() {
                const param = {
                    query: {
                        dariTanggal: dariTanggalWgt.value,
                        sampaiTanggal: sampaiTanggalWgt.value,
                    }
                };
                listPengeluaranWgt.refresh(param);
                listKeluarWgt.refresh(param);
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

        const listPengeluaranWgt = new spl.TableWidget({
            element: divElm.querySelector(".listPengeluaranTbl"),
            url: "<?= $data1Url ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "tipePengiriman"},
                3: {field: "noDokumen"},
                4: {field: "namaDepo"},
                5: {field: "tanggal", formatter: tlm.dateFormatter},
                6: {field: "prioritas"},
                7: {formatter(unused, {noPermintaan}) {
                    return draw({class: ".viewBtn", type: "primary", value: noPermintaan, text: str._<?= $h("Lihat") ?>});
                }}
            }
        });

        listPengeluaranWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewPengeluaranWidgetId ?>");
            widget.show();
            widget.loadData({idTransaksi: viewBtn.value}, true);
        });

        const listKeluarWgt = new spl.TableWidget({
            element: divElm.querySelector(".listKeluarTbl"),
            url: "<?= $data2Url ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {formatter(unused, item) {
                    const {tipePengiriman, noDokumen} = item;
                    return tipePengiriman || str._<?= $h("Permintaan {{NO_DOC}}") ?>.replace("{{NO_DOC}}", noDokumen);
                }},
                3: {field: "noDokumenPengiriman"},
                4: {field: "namaDepo"},
                5: {field: "tanggal", formatter: tlm.dateFormatter},
                6: {field: "prioritas"},
                7: {formatter(unused, {noPermintaan}) {
                    return draw({class: ".viewBtn", type: "primary", value: noPermintaan, text: str._<?= $h("Lihat") ?>});
                }}
            }
        });

        listKeluarWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewPengeluaranWidgetId ?>");
            widget.show();
            widget.loadData({idTransaksi: viewBtn.value}, true);
        });

        divElm.querySelector(".tambahBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $formPengirimanTpWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, dariTanggalWgt, sampaiTanggalWgt, listPengeluaranWgt, listKeluarWgt);
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
