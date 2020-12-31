<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPersediaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Monitorstok/filterjumlahstok.php the original file
 */
final class Form30Juni
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $depoSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPersediaanUi.Form30Juni {
    export interface Fields {
        idDepo:   "depo";
        tampilan: "tampilan";
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
            class: ".laporanFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Depo") ?>,
                        select: {class: ".idDepoFld", name: "idDepo"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tampilkan") ?>,
                        select: {
                            class: ".tampilanFld",
                            name: "tampilan",
                            option_1: {value: "1", label: tlm.stringRegistry._<?= $h("Per Katalog Barang") ?>},
                            option_2: {value: "2", label: tlm.stringRegistry._<?= $h("Per Kelompok Barang") ?>}
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

        /** @type {HTMLSelectElement} */ const idDepoFld = divElm.querySelector(".idDepoFld");
        /** @type {HTMLSelectElement} */ const tampilanFld = divElm.querySelector(".tampilanFld");

        tlm.app.registerSelect("_<?= $depoSelect ?>", idDepoFld);
        this._selects.push(idDepoFld);

        const laporanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPersediaanUi.Form30Juni.Fields} data */
            loadData(data) {
                idDepoFld.value = data.idDepo ?? "";
                tampilanFld.value = data.tampilan ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(laporanWgt);
        tlm.app.registerWidget(this.constructor.widgetName, laporanWgt);
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
