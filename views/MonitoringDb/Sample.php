<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\MonitoringDb;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/monitoringdb/sample.php the original file
 */
final class Sample
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl, string $killProsesUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.MonitoringDb.Sample {
    export interface Fields {
        id:       "ID";
        user:     "USER";
        host:     "HOST";
        db:       "DB";
        command:  "COMMAND";
        time:     "TIME";
        state:    "STATE";
        info:     "INFO";
        progress: "PROGRESS";
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>} // truely missing title
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1:  {text: tlm.stringRegistry._<?= $h("id") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("user") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("host") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("db") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("command") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("time") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("state") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("info") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("progress") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("action") ?>},
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
            columns: {
                1:  {field: "id"},
                2:  {field: "user"},
                3:  {field: "host"},
                4:  {field: "db"},
                5:  {field: "command"},
                6:  {field: "time"},
                7:  {field: "state"},
                8:  {field: "info"},
                9:  {field: "progress"},
                10: {formatter(unused, {id}) {
                    return draw({class: ".deleteBtn", type: "danger", value: id, title: str._<?= $h("Kill Proses") ?>});
                }}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;

            const idProses = deleteBtn.value;
            if (!confirm(`Are You sure want to kill proses with id: "${idProses}"?`)) return;

            $.post({
                url: "<?= $killProsesUrl ?>",
                data: {idproses: idProses}
            });
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
