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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/listkoreksi.php the original file
 */
final class TableKoreksi
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl, string $formWidgetId, string $printWidgetId)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Stokopname.ListKoreksi {
    export interface Fields {
        noDokumen:   string;
        userName:    string;
        updatedTime: string;
        kodeRef:     string;
        status:      string;
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
                button: {class: ".addBtn", text: tlm.stringRegistry._<?= $h("Tambah") ?>}
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
        }
    };

    constructor(divElm) {
        super();
        const {toUserDate: userDate, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noDokumen"},
                3: {formatter(unused, item) {
                    const {status, userName} = item;
                    return status == "1" ? userName : "";
                }},
                4: {formatter(unused, item) {
                    const {status, updatedTime} = item;
                    return status == "1" ? userDate(updatedTime) : "";
                }},
                5: {formatter(unused, item) {
                    const {kodeRef: value, status} = item;
                    const editBtn  = draw({class: ".editBtn",  type: "primary", value, text: str._<?= $h("Edit") ?>});
                    const printBtn = draw({class: ".printBtn", type: "primary", value, text: str._<?= $h("Print") ?>});
                    return (status == "1" ? "" : editBtn) + printBtn;
                }}
            }
        });

        divElm.querySelector(".addBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $formWidgetId ?>").show();
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadData({kodeRef: editBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kodeRef: printBtn.value}, true);
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
