<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPenerimaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/searchpbf.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\PenerimaanUi\Reports: commit-e37d34f4
 * last exist of tlm\his\FatmaPharmacy\views\PenerimaanUi\SearchPbf: commit-e37d34f4
 */
final class FormPerPemasok
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
namespace his.FatmaPharmacy.views.LaporanPenerimaanUi.FormPerPbf {
    export interface Fields {
        tanggalAwal:  string;
        tanggalAkhir: string;
        urutan:       string;
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
            class: ".laporanPerPemasokFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal") ?>,
                        input: {class: ".tanggalAwalFld", name: "tanggalAwal"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                        input: {class: ".tanggalAkhirFld", name: "tanggalAkhir"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Urutkan") ?>,
                        select: {
                            class: ".urutanFld",
                            name: "urutan",
                            option_1: {value: "dist",  label: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                            option_2: {value: "nofkt", label: tlm.stringRegistry._<?= $h("No. Faktur") ?>},
                            option_3: {value: "nospk", label: tlm.stringRegistry._<?= $h("No. SPK") ?>},
                            option_4: {value: "nosj",  label: tlm.stringRegistry._<?= $h("No. Surat Jalan") ?>},
                            option_5: {value: "noba",  label: tlm.stringRegistry._<?= $h("No. BA") ?>}
                        }
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            }
        },
        row_3: {
            widthColumn: {
                paragraph: {text: "&npsp;"}
            }
        },
        row_4: {
            widthColumn: {class: ".printTargetElm"}
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const urutanFld = divElm.querySelector(".urutanFld");
        /** @type {HTMLDivElement} */    const printTargetElm = divElm.querySelector(".printTargetElm");

        const laporanPerPemasokWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanPerPemasokFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPenerimaanUi.FormPerPbf.Fields} data */
            loadData(data) {
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                urutanFld.value = data.urutan ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                printTargetElm.innerHTML = event.data;
            },
            onFailSubmit() {
                printTargetElm.innerHTML = str._<?= $h("terjadi error") ?>;
            }
        });

        const tanggalAwalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAwalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalAkhirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAkhirFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(laporanPerPemasokWgt, tanggalAwalWgt, tanggalAkhirWgt);
        tlm.app.registerWidget(this.constructor.widgetName, laporanPerPemasokWgt);
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
