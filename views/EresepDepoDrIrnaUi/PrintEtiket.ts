<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepoDrIrnaUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepodrirna/print_etiket.php the original file
 */
final class PrintEtiket
{
    private string $output;

    public function __construct(string $registerId, array $printAccess, string $actionUrl, string $printerSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepDepoDrIrnaUi.PrintEtiket {
    export interface Fields {
        kodeResep: string;
        noPrinter: string;
        arah:      string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{print: boolean}}
     */
    static getAccess(role) {
        const pool = {
            print: JSON.parse(`<?=json_encode($printAccess) ?>`),
        };
        const access = {};
        for (const item in pool) {
            if (!pool.hasOwnProperty(item)) continue;
            access[item] = pool[item][role] ?? false;
        }
        return access;
    }

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".cetakFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden: {class: ".kodeResepFld", name: "kodeResep"}, // not exist on original form  (bug!)
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Nama Depo") ?>,
                        staticText: {class: ".namaDepoStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Lokasi") ?>,
                        staticText: {class: ".lokasiStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Printer") ?>,
                        select: {class: ".noPrinterFld", name: "noPrinter"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Arah") ?>,
                        radio_1: {class: ".arahNormalOpt",   name: "arah", value: 1, label: tlm.stringRegistry._<?= $h("Normal") ?>},
                        radio_2: {class: ".arahTerbalikOpt", name: "arah", value: 2, label: tlm.stringRegistry._<?= $h("Terbalik") ?>},
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: str._<?= $h("Save") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const kodeResepFld = divElm.querySelector(".kodeResepFld");
        /** @type {HTMLSelectElement} */ const noPrinterFld = divElm.querySelector(".noPrinterFld");
        /** @type {HTMLInputElement} */  const arahNormalOpt = divElm.querySelector(".arahNormalOpt");
        /** @type {HTMLInputElement} */  const arahTerbalikOpt = divElm.querySelector(".arahTerbalikOpt");

        tlm.app.registerSelect("_<?= $printerSelect ?>", noPrinterFld);
        this._selects.push(noPrinterFld);

        let kodeResep;

        const cetakWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".cetakFrm"),
            /** @param {his.FatmaPharmacy.views.EresepDepoDrIrnaUi.PrintEtiket.Fields} data */
            loadData(data) {
                formTitleTxt.innerHTML = str._<?= $h("Print E-Tiket: {{KODE}}") ?>.replace("{{KODE}}", data.kodeResep);
                kodeResepFld.value = data.kodeResep;
                noPrinterFld.value = data.noPrinter ?? "";
                data.arah == 1 ? arahNormalOpt.checked = true : arahTerbalikOpt.checked = true;

                kodeResep = data.kodeResep;
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                print() {
                    // nothing to do.
                }
            },
            onSuccessSubmit() {
                const text1 = str._<?= $h("Print E-Tiket dengan kode resep: {{KODE}} telah berhasil.") ?>.replace("{{KODE}}", kodeResep);
                const text2 = str._<?= $h("Silahkan cek hasil print Anda.") ?>;
                alert(text1 + "\n" + text2);
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(cetakWgt);
        tlm.app.registerWidget(this.constructor.widgetName, cetakWgt);
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
