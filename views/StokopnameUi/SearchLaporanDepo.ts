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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/searchlaporandepo.php the original file
 */
final class SearchLaporanDepo
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.Stokopname.SearchLaporanDepo {
    export interface Fields {
        bulan: "bulan";
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
                heading3: {class: tlm.stringRegistry._<?= $h("Stok Opname") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".searchFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Bulan") ?>,
                        select: {
                            class: ".bulanFld",
                            name: "bulan",
                            option: {value: "09", label: tlm.stringRegistry._<?= $h("September") ?>}
                        }
                    }
                }
            },
            row_3: {
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

        const searchWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".searchFrm"),
            /** @param {his.FatmaPharmacy.views.Stokopname.SearchLaporanDepo.Fields} data */
            loadData(data) {
                bulanFld.value = data.bulan ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(searchWgt);
        tlm.app.registerWidget(this.constructor.widgetName, searchWgt);
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
