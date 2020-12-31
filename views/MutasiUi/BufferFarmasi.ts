<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\MutasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/bufferfarmasi.php the original file
 */
final class BufferFarmasi
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $tableWidgetId)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Mutasi.BufferFarmasi {
    export interface FormFields {
        leadtime: "leadtime";
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
                heading3: {text: tlm.stringRegistry._<?= $h("Buffer Stok Farmasi") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".formFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup: {
                        label: tlm.stringRegistry._<?= $h("Lead Time (%)") ?>,
                        input: {class: ".leadtimeFld", name: "leadtime"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Ubah") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();

        const formWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".formFrm"),
            /** @param {his.FatmaPharmacy.views.Mutasi.BufferFarmasi.FormFields} data */
            loadData(data) {
                leadTimeWgt.value = data.leadtime ?? "";
            },
            actionUrl: "<?= $actionUrl ?>",
            onBeforeSubmit() {
                return confirm(tlm.stringRegistry._<?= $h("Penarikan data buffer dapat membuat sistem lambat. Apakah Anda yakin ingin melanjutkan?") ?>);
            },
            onSuccessSubmit() {
                tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
            },
        });

        const leadTimeWgt = new spl.InputWidget({
            element: divElm.querySelector(".leadTimeFld"),
            errorRules: [
                {required: true},
                {greaterThanEqual: 0},
                {lessThanEqual: 100}
            ]
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(formWgt);
        tlm.app.registerWidget(this.constructor.widgetName, formWgt);
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
