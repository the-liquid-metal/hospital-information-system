<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PrinterDepo;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/printerdepo/setprinter.php the original file
 */
final class SetPrinter
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $printerDepoSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.PrinterDepo.SetPrinter {
    export interface Fields {
        ip:          "___", // exist but missing
        namaDepo:    "___", // exist but missing
        lokasiDepo:  "___", // exist but missing
        noPrinter:   "no_printer",
        tipePrinter: "tipe_nama_printer",
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
            class: ".printerDepoFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("IP address Anda") ?>,
                        staticText: {class: ".ipStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Depo") ?>,
                        staticText: {class: ".namaDepoStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Lokasi Depo") ?>,
                        staticText: {class: ".lokasiDepoStc"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Printer") ?>,
                        select: {
                            class: ".noPrinterFld",
                            name: "noPrinter",
                            option_1: {value: 1, label: 1},
                            option_2: {value: 2, label: 2},
                            option_3: {value: 3, label: 3},
                            option_4: {value: 4, label: 4},
                            option_5: {value: 5, label: 5},
                            option_6: {value: 6, label: 6},
                            option_7: {value: 7, label: 7},
                            option_8: {value: 8, label: 8}
                        }
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Tipe Printer") ?>,
                        select: {class: ".tipePrinterFld", name: "tipePrinter"}
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

        /** @type {HTMLDivElement} */    const ipStc = divElm.querySelector(".ipStc");
        /** @type {HTMLDivElement} */    const namaDepoStc = divElm.querySelector(".namaDepoStc");
        /** @type {HTMLDivElement} */    const lokasiDepoStc = divElm.querySelector(".lokasiDepoStc");
        /** @type {HTMLSelectElement} */ const noPrinterFld = divElm.querySelector(".noPrinterFld");
        /** @type {HTMLSelectElement} */ const tipePrinterFld = divElm.querySelector(".tipePrinterFld");

        tlm.app.registerSelect("_<?= $printerDepoSelect ?>", tipePrinterFld);
        this._selects.push(tipePrinterFld);

        const printerDepoWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".printerDepoFrm"),
            /** @param {his.FatmaPharmacy.views.PrinterDepo.SetPrinter.Fields} data */
            loadData(data) {
                ipStc.innerHTML = data.ip ?? "";
                namaDepoStc.innerHTML = data.namaDepo ?? "";
                lokasiDepoStc.innerHTML = data.lokasiDepo ?? "";
                noPrinterFld.value = data.noPrinter ?? "";
                tipePrinterFld.value = data.tipePrinter ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(printerDepoWgt);
        tlm.app.registerWidget(this.constructor.widgetName, printerDepoWgt);
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
