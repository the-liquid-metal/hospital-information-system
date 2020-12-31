<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPengeluaranUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporan_rekapgm.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\LaporanUi\LaporanRekapGasMedis: commit-e37d34f4
 */
final class FormGasMedis
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $depoAsalSelect, string $depoTujuanSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPengeluaranUi.FormGasMedis {
    export interface Fields {
        idDepoAsal:   string;
        idDepoTujuan: string;
        tanggalAwal:  string;
        tanggalAkhir: string;
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
            class: ".laporanGasMedisFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Depo Asal") ?>,
                        select: {class: ".idDepoAsalFld", name: "idDepoAsal"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Depo Tujuan") ?>,
                        select: {class: ".idDepoTujuanFld", name: "idDepoTujuan"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal") ?>,
                        input: {class: ".tanggalAwalFld", name: "tanggalAwal"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                        input: {class: ".tanggalAkhirFld", name: "tanggalAkhir"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Generate") ?>}
                }
            }
        },
        row_3: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
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

        /** @type {HTMLSelectElement} */ const idDepoAsalFld = divElm.querySelector(".idDepoAsalFld");
        /** @type {HTMLSelectElement} */ const idDepoTujuanFld = divElm.querySelector(".idDepoTujuanFld");
        /** @type {HTMLDivElement} */    const printTargetElm = divElm.querySelector(".printTargetElm");

        tlm.app.registerSelect("_<?= $depoAsalSelect ?>", idDepoAsalFld);
        tlm.app.registerSelect("_<?= $depoTujuanSelect ?>", idDepoTujuanFld);
        this._selects.push(idDepoAsalFld, idDepoTujuanFld);

        const laporanGasMedisWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanGasMedisFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPengeluaranUi.FormGasMedis.Fields} data */
            loadData(data) {
                idDepoAsalFld.value = data.idDepoAsal ?? "";
                idDepoTujuanFld.value = data.idDepoTujuan ?? "";
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
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
        this._widgets.push(laporanGasMedisWgt, tanggalAwalWgt, tanggalAkhirWgt);
        tlm.app.registerWidget(this.constructor.widgetName, laporanGasMedisWgt);
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
