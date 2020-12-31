<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/print_rekapitulasi_setoran_harian.php the original file
 */
final class PrintRekapitulasiSetoranHarian
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $operatorKasirSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.Laporan.PrintRekapitulasiSetoranHarian {
    export interface Fields {
        kasir:   "kasir";
        tanggal: "fromdate";
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Kasir") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".printRekapitulasiFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kasir") ?>,
                        select: {class: ".kasirFld", name: "kasir"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
                        input: {class: ".tanggalFld", name: "tanggal"}
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

        /** @type {HTMLSelectElement} */ const kasirFld = divElm.querySelector(".kasirFld");
        tlm.app.registerSelect("_<?= $operatorKasirSelect ?>", kasirFld);
        this._selects.push(kasirFld);

        const printRekapitulasiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".printRekapitulasiFrm"),
            /** @param {his.FatmaPharmacy.views.Laporan.PrintRekapitulasiSetoranHarian.Fields} data */
            loadData(data) {
                kasirFld.value = data.kasir ?? "";
                tanggalWgt.value = data.tanggal ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        const tanggalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(printRekapitulasiWgt, tanggalWgt);
        tlm.app.registerWidget(this.constructor.widgetName, printRekapitulasiWgt);
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
