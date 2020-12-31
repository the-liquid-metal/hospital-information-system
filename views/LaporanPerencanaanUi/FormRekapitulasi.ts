<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPerencanaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/perencanaan/reports.php the original file
 */
final class FormRekapitulasi
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $bulanSelect, string $tahunSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPerencanaanUi.FormRekapitulasi {
    export interface Fields {
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        statusKontrak:      string;
        format:             "format"; // NOT USED in controller
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
            class: ".laporanPerencanaanRekapitulasiFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden: {class: ".submitFld", name: "submit", value: "report"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Awal Bulan") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Akhir Bulan") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tahun") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tipe Perencanaan") ?>,
                        checkbox: {class: ".statusKontrakFld", name: "statusKontrak", value: "1", label: tlm.stringRegistry._<?= $h("repeate order (kontrak)") ?>}
                    },
                    formGroup_5: { // NOT USED in controller
                        label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                        checkbox: {class: ".formatFld", name: "format", value: "rekap", label: tlm.stringRegistry._<?= $h("Rekapitulasi Rencana dan Realisasi Pengadaan Barang Farmasi") ?>, checked: true}
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

        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLInputElement} */  const statusKontrakFld = divElm.querySelector(".statusKontrakFld");
        /** @type {HTMLInputElement} */  const formatFld = divElm.querySelector(".formatFld");
        /** @type {HTMLDivElement} */    const printTargetElm = divElm.querySelector(".printTargetElm");

        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(bulanAwalAnggaranFld, bulanAkhirAnggaranFld, tahunAnggaranFld);

        const laporanPerencanaanRekapitulasiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanPerencanaanRekapitulasiFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPerencanaanUi.FormRekapitulasi.Fields} data */
            loadData(data) {
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                statusKontrakFld.checked = !!data.statusKontrak;
                formatFld.checked = !!data.format;
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

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(laporanPerencanaanRekapitulasiWgt);
        tlm.app.registerWidget(this.constructor.widgetName, laporanPerencanaanRekapitulasiWgt);
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
