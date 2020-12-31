<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepoUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepo/print_etiket.php the original file
 */
final class PrintEtiket
{
    private string $output;

    /**
     * @author Hendra Gunawan
     *
     * this class TRUELY does not need $dataUrl
     */
    public function __construct(string $registerId, array $printAccess, string $actionUrl, string $printerSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepDepoUi.PrintEtiket {
    export interface Fields {
        noResep:   string;
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
            class: ".printEtiketFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden: {class: ".noResepFld", name: "noResep"}, // not exist on original form  (bug!)
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                        staticText: {class: ".noResepStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Depo") ?>,
                        staticText: {class: ".namaDepoStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Lokasi") ?>,
                        staticText: {class: ".lokasiStc"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Printer") ?>,
                        select: {class: ".noPrinterFld", name: "noPrinter"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Arah") ?>,
                        radio_1: {class: ".arahNormalFld",   name: "arah", value: 1, label: tlm.stringRegistry._<?= $h("Normal") ?>},
                        radio_2: {class: ".arahTerbalikFld", name: "arah", value: 2, label: tlm.stringRegistry._<?= $h("Terbalik") ?>}
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
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const noPrinterFld = divElm.querySelector(".noPrinterFld");
        /** @type {HTMLInputElement} */  const noResepFld = divElm.querySelector(".noResepFld");
        /** @type {HTMLInputElement} */  const arahNormalFld = divElm.querySelector(".arahNormalFld");
        /** @type {HTMLInputElement} */  const arahTerbalikFld = divElm.querySelector(".arahTerbalikFld");
        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");

        tlm.app.registerSelect("_<?= $printerSelect ?>", noPrinterFld);
        this._selects.push(noPrinterFld);

        let noResep;

        const printEtiketWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".printEtiketFrm"),
            // this instance TRUELY does not need $dataUrl
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.PrintEtiket.Fields} data */
            loadData(data) {
                formTitleTxt.innerHTML = str._<?= $h("Print E-Tiket: {{NO_RESEP}}") ?>.replace("{{NO_RESEP}}", data.noResep);
                noResepFld.value = data.noResep;
                noPrinterFld.value = data.noPrinter ?? "";
                data.arah == 1 ? arahNormalFld.checked = true : arahTerbalikFld.checked = true;

                noResep = data.noResep;
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                print() {
                    // nothing to do.
                },
            },
            onSuccessSubmit() {
                const text1 = str._<?= $h("Print E-Tiket dengan kode resep: {{NO_RESEP}} telah berhasil.") ?>.replace("{{NO_RESEP}}", noResep);
                const text2 = str._<?= $h("Silahkan cek hasil print Anda.") ?>;
                alert(text1 + "\n" + text2);
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(printEtiketWgt);
        tlm.app.registerWidget(this.constructor.widgetName, printEtiketWgt);
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
