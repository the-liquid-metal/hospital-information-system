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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporandepojualindexnew.php the original file
 */
final class LaporanDepoJualIndexNew
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $depoSelect, string $ruangRawatSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Laporan.LaporanDepoJualIndexNew {
    export interface Fields {
        idDepo:        string;
        idRuangRawat:  string;
        dariTanggal:   string;
        sampaiTanggal: string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Penjualan Tanpa Harga") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>,
                        select: {class: ".idRuangRawatFld", name: "idRuangRawat"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Dari Tanggal") ?>,
                        input: {class: ".dariTanggalFld", name: "dariTanggal"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Sampai Tanggal") ?>,
                        input: {class: ".sampaiTanggalFld", name: "sampaiTanggal"}
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
        /** @type {HTMLSelectElement} */ const idRuangRawatFld = divElm.querySelector(".idRuangRawatFld");

        tlm.app.registerSelect("_<?= $ruangRawatSelect ?>", idRuangRawatFld);
        tlm.app.registerSelect("_<?= $depoSelect ?>", idDepoFld);
        this._selects.push(idRuangRawatFld, idDepoFld);

        const laporanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanFrm"),
            /** @param {his.FatmaPharmacy.views.Laporan.LaporanDepoJualIndexNew.Fields} data */
            loadData(data) {
                idDepoFld.value = data.idDepo ?? "";
                idRuangRawatFld.value = data.idRuangRawat ?? "";
                dariTanggalWgt.value = data.dariTanggal ?? "";
                sampaiTanggalWgt.value = data.sampaiTanggal ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        const dariTanggalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".dariTanggalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const sampaiTanggalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".sampaiTanggalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(laporanWgt, dariTanggalWgt, sampaiTanggalWgt);
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
