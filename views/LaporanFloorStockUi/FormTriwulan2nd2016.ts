<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanFloorStockUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/searchlaporanfloorstockjuni16.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\StokopnameUi\SearchLaporanFloorStockJuni16: commit-e37d34f4
 */
final class FormTriwulan2nd2016
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanFloorStockUi.FormTriwulan2nd2016 {
    export interface Fields {
        bulan: string;
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
            class: ".triwulanFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Bulan") ?>,
                        select: {
                            class: ".bulanFld",
                            name: "bulan",
                            option: {value: "Juni", label: tlm.stringRegistry._<?= $h("Juni 2016") ?>}
                        }
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const bulanFld = divElm.querySelector(".bulanFld");

        const triwulanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".triwulanFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanFloorStockUi.FormTriwulan2nd2016.Fields} data */
            loadData(data) {
                bulanFld.value = data.bulan ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(triwulanWgt);
        tlm.app.registerWidget(this.constructor.widgetName, triwulanWgt);
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
