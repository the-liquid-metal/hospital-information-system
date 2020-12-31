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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/pengeluaran5.php the original file
 */
final class TablePengeluaran5
{
    private string $output;

    public function __construct(
        string $registerId,
        string $data1Url,
        string $data2Url,
        string $viewPengeluaranWidgetId,
        string $printPengirimanWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.Pengeluaran5 {
    export interface FormFields {
        dariTanggal:   string;
        sampaiTanggal: string;
    }

    export interface Item1Fields {
        noDokumen:    string;
        namaDepo:     string;
        tanggal:      string;
        prioritas:    string;
        noPermintaan: string;
    }

    export interface Item2Fields {
        noDokumen:           string;
        noDokumenPengiriman: string;
        namaDepo:            string;
        kodeItem:            string;
        namaBarang:          string;
        jumlahDiminta:       string;
        jumlahDiberikan:     string;
        tanggal:             string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Pengiriman") ?>}
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
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_4: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Permintaan") ?>}
            }
        },
        row_5: {
            widthTable: {
                class: ".item1Tbl",
                thead: {
                    td: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. Permintaan") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Depo Peminta") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Status") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                    }
                }
            }
        },
        row_6: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_7: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Permintaan Obat Tidak Terlayani") ?>}
            }
        },
        row_8: {
            widthTable: {
                class: ".item2Tbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. Dok. Permintaan") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("No. Dok. Pengiriman") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Depo Peminta") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Kode Obat") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Obat Diminta") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Jumlah Diminta") ?>},
                        td_8: {text: tlm.stringRegistry._<?= $h("Jumlah Diberikan") ?>},
                        td_9: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
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
            /** @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran5.FormFields} data */
            loadData(data) {
                dariTanggalWgt.value = data.dariTanggal ?? "";
                sampaiTanggalWgt.value = data.sampaiTanggal ?? "";
            },
            submit() {
                item1Wgt.refresh({
                    query: {
                        dariTanggal: dariTanggalWgt.value,
                        sampaiTanggal: sampaiTanggalWgt.value,
                    }
                });
                item2Wgt.refresh({
                    query: {
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

        const item1Wgt = new spl.TableWidget({
            element: divElm.querySelector(".item1Tbl"),
            url: "<?= $data1Url ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noDokumen"},
                3: {field: "namaDepo"},
                4: {field: "tanggal", formatter: tlm.dateFormatter},
                5: {field: "prioritas"},
                6: {formatter(unused, {noPermintaan}) {
                    const viewBtn  = draw({class: ".viewBtn",  value: noPermintaan, text: str._<?= $h("Lihat") ?>});
                    const printBtn = draw({class: ".printBtn", value: noPermintaan, text: str._<?= $h("Print") ?>});
                    return viewBtn + printBtn;
                }}
            }
        });

        item1Wgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const viewWgt = tlm.app.getWidget("_<?= $viewPengeluaranWidgetId ?>");
            viewWgt.show();
            viewWgt.loadData({id_trn: viewBtn.value}, true); // TODO: js: uncategorized: finish this
        });

        item1Wgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const printWgt = tlm.app.getWidget("_<?= $printPengirimanWidgetId ?>");
            printWgt.show();
            printWgt.loadData({noPermintaan: printBtn.value}, true);
        });

        const item2Wgt = new spl.TableWidget({
            element: divElm.querySelector(".item2Tbl"),
            url: "<?= $data2Url ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noDokumen"},
                3: {field: "noDokumenPengiriman"},
                4: {field: "namaDepo"},
                5: {field: "kodeItem"},
                6: {field: "namaBarang"},
                7: {field: "jumlahDiminta", formatter: tlm.intFormatter},
                8: {field: "jumlahDiberikan", formatter: tlm.intFormatter},
                9: {field: "tanggal", formatter: tlm.dateFormatter}
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, dariTanggalWgt, sampaiTanggalWgt, item1Wgt, item2Wgt);
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
