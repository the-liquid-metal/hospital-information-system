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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/index.php the original file
 */
final class TableHistory
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $auditAccess,
        string $dataUrl,
        string $editFormWidgetId
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.StokopnameUi.HistoryTable {
    export interface Fields {
        kode:           string;
        tanggalAdm:     string;
        tanggalDokumen: string;
        tanggalMulai:   string;
        tanggalSelesai: string;
        keterangan:     string;
        statusOpname:   string;
        updatedBy:      string;
        updatedTime:    string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean, audit: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            audit: JSON.parse(`<?= json_encode($auditAccess) ?>`),
        };
        const access = {};
        for (const item in pool) {
            if (!pool.hasOwnProperty(item)) continue;
            access[item] = pool[item][role] ?? false;
        }
        return access;
    }

    static style = {
        [this.widgetName + " td:first-child"]: {
            _style: {width: "80px"},
        }
    };

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: str._<?= $h("Stokopname") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            widthTable: {
                class: ".historyTbl",
                thead: {
                    tr: {
                        td_1:  {text: ""},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Tanggal ADM") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Tanggal Transaksi") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Tanggal Mulai") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Tanggal Selesai") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Keterangan") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Status Stok Opname") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("User Update") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Tanggal Update") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const str = tlm.stringRegistry;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".historyTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, {kode}) {
                    return access.edit ? draw({class: ".editBtn", type: "primary", icon: "pencil", value: kode, title: str._<?= $h("Edit") ?>}) : "";
                }},
                2: {field: "kode"},
                3: {field: "tanggalAdm", formatter: tlm.dateFormatter},
                4: {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                5: {field: "tanggalMulai", formatter: tlm.dateFormatter},
                6: {field: "tanggalSelesai", formatter: tlm.dateFormatter},
                7: {field: "keterangan"},
                8: {formatter(unused, {statusOpname}) {
                    const closeStr = str._<?= $h("Close") ?>;
                    const activeStr = str._<?= $h("Active") ?>;
                    return statusOpname == "0" ? `<span style="color:red">${closeStr}</span>` : `<p style="color:blue">${activeStr}</p>`;
                }},
                9:  {field: "updatedBy", visible: access.audit},
                10: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter}
            }
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $editFormWidgetId ?>");
            widget.show();
            widget.loadData({kode: editBtn.value}, true);
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
