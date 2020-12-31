<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\StokopnameUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/listrusakstock.php the original file
 */
final class TableStokRusak
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $formWidgetId,
        string $printRekapUrl,
        string $printItemUrl,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Stokopname.ListStockRusak {
    export interface Fields {
        noDokumen: string;
        userName:  string;
        inputTime: string;
        kodeRef:   string;
        status:    string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName + " .printAreaBlk .page2"]: {
            _style: {
                width: "230mm",
                margin: "0 25mm 25mm 0",
                padding: "10mm",
                border: "1px #D3D3D3 solid",
                borderRadius: "5px",
                background: "white",
                boxShadow: "0 0 5px rgba(0, 0, 0, 0.1)",
            },
            ".judulTbl": {
                _style: {width: "100%"},
                "td": {
                    _style: {textAlign: "center"}
                }
            },
            ".dataTbl": {
                _style: {width: "100%"},
                "tr td": {
                    _style: {
                        border: "1px solid black",
                        fontSize: "13px",
                        paddingTop: "4px",
                        paddingBottom: "4px",
                    }
                },
                "tr:last-child td": {
                    _style: {borderBottom: "1px solid black"}
                },
                "thead tr td": {
                    _style: {
                        border: "1px solid black",
                        padding: "5px",
                        backgroundColor: "#ffe5ff",
                    }
                },
                "tbody": {
                    ".tr_doc td": {
                        _style: {backgroundColor: "#DFE3EE"}
                    },
                    ".tr_jenis td": {
                        _style: {
                            backgroundColor: "#f2f2f2",
                            borderTop: "1px solid black",
                            borderBottom: "1px solid black",
                            borderLeft: "1px solid black"
                        }
                    },
                    ".tr_detail td": {
                        _suffixes_1: ["nth-child(7)", "nth-child(9)", "nth-child(10)"],
                        _style_1:    {textAlign: "right"}
                    }
                }
            }
        },
        "@media print": {
            [this.widgetName + " .printAreaBlk .page2"]: {
                _suffixes_1: [""],
                _style_1: {
                    padding: "0",
                    border: "none !important",
                    boxShadow: "none !important",
                    background: "none !important",
                    pageBreakAfter: "always",
                },
                _suffixes_2: [":last-child"],
                _style_2:    {pageBreakAfter: "avoid !important"}
            }
        }
    };

    _structure = {
        row_1: {
            widthColumn: {
                header3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            widthColumn: {
                button_1: {class: ".addBtn", text: tlm.stringRegistry._<?= $h("Tambah") ?>},
                button_2: {class: ".rekapBtn", text: tlm.stringRegistry._<?= $h("Rekap") ?>},
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_5: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. Dokumen") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Verifikasi") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Action") ?>},
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
            widthColumn: {class: ".printAreaBlk"}
        },
    };

    constructor(divElm) {
        super();
        const {toUserDate: userDate, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */ const printAreaBlk = divElm.querySelector(".printAreaBlk");

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noDokumen"},
                3: {formatter(unused, item) {
                    const {userName, status} = item;
                    return status == "1" ? userName : "";
                }},
                4: {formatter(unused, item) {
                    const {inputTime, status} = item;
                    return status == "1" ? userDate(inputTime) : "";
                }},
                5: {formatter(unused, item) {
                    const {kodeRef, status} = item;
                    const editBtn  = draw({class: ".editBtn",  type: "primary", value: kodeRef, text: str._<?= $h("Edit") ?>});
                    const printBtn = draw({class: ".printBtn", type: "primary", value: kodeRef, text: str._<?= $h("Print") ?>});
                    return (status == "1" ? "" : editBtn) + printBtn;
                }}
            }
        });

        divElm.querySelector(".addBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadProfile("add");
        });

        divElm.querySelector(".rekapBtn").addEventListener("click", () => {
            $.post({
                url: "<?= $printRekapUrl ?>",
                success(html) {printAreaBlk.innerHTML = html}
            });
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadProfile("edit", {kodeRef: editBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            $.post({
                url: "<?= $printItemUrl ?>",
                data: {kodeRef: printBtn.value},
                success(html) {printAreaBlk.innerHTML = html}
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(tableWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tableWgt);
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
