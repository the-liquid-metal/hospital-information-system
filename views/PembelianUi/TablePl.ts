<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PembelianUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/index_itempl.php the original file
 */
final class TablePl
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl, string $reportWidgetId, string $viewWidgetId)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pembelian.IndexItemPl {
    export interface TableFields {
        statusClosed:      string;
        kodeRef:           string;
        noDokumen:         string;
        tanggalJatuhTempo: string;
        namaPemasok:       string;
        namaPabrik:        string;
        kemasan:           string;
        jumlahKemasan:     string;
        jumlahItem:        string;
        hargaKemasan:      string;
        hargaItem:         string;
        diskonItem:        string;
        diskonHarga:       string;
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
        form: {
            action: "<?= $reportWidgetId ?>",
            hidden_1: {name: "id_katalog", value: "<?= $_POST['id_katalog'] ?>"},
            hidden_2: {name: "nama_sediaan", value: "<?= $_POST['nama_sediaan'] ?>"},
            hidden_3: {name: "type", value: "exportToExcel"},
            hidden_4: {name: "submit", value: "<?= $_POST['submit'] ?>"},
            button: {class: ".btn-warning", text: tlm.stringRegistry._<?= $h("Export To Excel") ?>}
        },
        row_3: {
            widthColumn: {
                class: ".itemTbl",
                thead: {
                    tr_1: {
                        td_1: /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Status PL") ?>},
                        td_2: /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No. PL Pembelian") ?>},
                        td_3: /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Tempo Kontrak") ?>},
                        td_4: /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_5: /*  5    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik on PL") ?>},
                        td_6: /*  6    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_7: /*  7-8  */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Volume") ?>},
                        td_8: /*  9-10 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_9: /* 11-12 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                    },
                    tr_2: {
                        td_1: /*  7 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_2: /*  8 */ {text: tlm.stringRegistry._<?= $h("Item") ?>},
                        td_3: /*  9 */ {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_4: /* 10 */ {text: tlm.stringRegistry._<?= $h("Item") ?>},
                        td_5: /* 11 */ {text: tlm.stringRegistry._<?= $h("%") ?>},
                        td_6: /* 12 */ {text: tlm.stringRegistry._<?= $h("Rp") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                // open: PL masih aktif
                // close: PL telah melewati masa kontrak atau Seluruh Item Barang sudah diterima
                1: {field: "statusClosed", formatter: val => val == "0" ? str._<?= $h("Open") ?> : str._<?= $h("Closed") ?>},
                2: {formatter(unused, item) {
                    const {kodeRef, noDokumen} = item;
                    return draw({class: ".viewBtn", value: kodeRef, text: noDokumen});
                }},
                3:  {field: "tanggalJatuhTempo", formatter: tlm.dateFormatter},
                4:  {field: "namaPemasok"},
                5:  {field: "namaPabrik"},
                6:  {field: "kemasan"},
                7:  {field: "jumlahKemasan", formatter: tlm.intFormatter},
                8:  {field: "jumlahItem", formatter: tlm.intFormatter},
                9:  {field: "hargaKemasan", formatter: tlm.floatFormatter},
                10: {field: "hargaItem", formatter: tlm.floatFormatter},
                11: {field: "diskonItem", formatter: tlm.intFormatter},
                12: {field: "diskonHarga", formatter: tlm.intFormatter}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", event => {
            const viewBtn = /** @type {HTMLButtonElement} */ event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewBtn.value}, true);
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
