<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ProduksiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/produksi/listproduksi.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl, string $formWidgetId, string $printWidgetId)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.ProduksiUi.ListProduksi {
    export interface Fields {
        kodeRef:         "kode_reff";
        tanggalTersedia: "tgl_tersedia";
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Produksi") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            widthColumn: {
                button: {text: tlm.stringRegistry._<?= $h("Tambah Produksi") ?>, href: "<?= $formWidgetId ?>"}
            }
        },
        row_4: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. Produksi") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Detail") ?>},
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

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            idField: "kodeRef",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "kodeRef"},
                3: {field: "tanggalTersedia", formatter: tlm.dateFormatter},
                4: {formatter(unused, {kodeRef}) {
                    return draw({class: ".viewBtn", value: kodeRef, text: str._<?= $h("Lihat") ?>});
                }}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kodeRef: viewBtn.value}, true);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, itemWgt);
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
