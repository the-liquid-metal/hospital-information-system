<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanMutasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/mutasi/formmutasi.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\MutasiUi\FormMutasi: commit-e37d34f4
 */
final class FormBulan
{
    private string $output;

    public function __construct(
        string $registerId,
        string $webKatalogDetailJenisKelompokActionUrl,
        string $webKatalogDetailKelompokActionUrl,
        string $webKatalogJenisKelompokActionUrl,
        string $webKatalogKelompokActionUrl,
        string $webJenisKelompokActionUrl,
        string $webKelompokActionUrl,
     // string $web7ActionUrl,
        string $tahunSelect,
        string $bulanSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanMutasiUi.FormBulan {
    export interface Fields {
        bulan:        string;
        tahun:        string;
        jenisLaporan: string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Mutasi Bulan") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".laporanBulanFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Bulan") ?>,
                        select: {class: ".bulanFld", name: "bulan"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tahun") ?>,
                        select: {class: ".tahunFld", name: "tahun"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Jenis Laporan") ?>,
                        select: {
                            class: ".jenisLaporanFld",
                            name: "jenisLaporan",
                            option_1: {value: "katalog-detail-jenis-kelompok",  label: tlm.stringRegistry._<?= $h("Katalog &amp; detail per jenis &amp; kelompok barang") ?>, disabled: true},
                            option_2: {value: "katalog-detail-kelompok",        label: tlm.stringRegistry._<?= $h("Katalog &amp; detail per kelompok barang") ?>},
                            option_3: {value: "katalog-jenis-kelompok",         label: tlm.stringRegistry._<?= $h("Katalog per jenis &amp; kelompok barang") ?>,              disabled: true},
                            option_4: {value: "katalog-kelompok",               label: tlm.stringRegistry._<?= $h("Katalog per kelompok barang") ?>,                          disabled: true},
                            option_5: {value: "jenis-kelompok",                 label: tlm.stringRegistry._<?= $h("Jenis &amp; kelompok") ?>,                                 disabled: true},
                            option_6: {value: "kelompok",                       label: tlm.stringRegistry._<?= $h("Kelompok") ?>},
                            option_7: {value: 7,                                label: tlm.stringRegistry._<?= $h("Excel") ?>,                                                disabled: true},
                            option_8: {value: 99,                               label: tlm.stringRegistry._<?= $h("Dump") ?>,                                                 disabled: true},
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

        /** @type {HTMLSelectElement} */ const bulanFld = divElm.querySelector(".bulanFld");
        /** @type {HTMLSelectElement} */ const tahunFld = divElm.querySelector(".tahunFld");
        /** @type {HTMLSelectElement} */ const jenisLaporanFld = divElm.querySelector(".jenisLaporanFld");
        /** @type {HTMLDivElement} */    const printTargetElm = divElm.querySelector(".printTargetElm");

        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunFld);
        this._selects.push(bulanFld, tahunFld);

        const laporanBulanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanBulanFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanMutasiUi.FormBulan.Fields} data */
            loadData(data) {
                bulanFld.value = data.bulan ?? "";
                tahunFld.value = data.tahun ?? "";
                jenisLaporanFld.value = data.jenisLaporan ?? "";
            },
            resetBtnId: false,
            onBeforeSubmit() {
                const jenis = jenisLaporanFld.value;
                switch (jenis) {
                    case "web.katalog-detail-jenis-kelompok" : this._actionUrl = "<?= $webKatalogDetailJenisKelompokActionUrl ?>"; break;
                    case "web.katalog-detail-kelompok"       : this._actionUrl = "<?= $webKatalogDetailKelompokActionUrl ?>"; break;
                    case "web.katalog-jenis-kelompok"        : this._actionUrl = "<?= $webKatalogJenisKelompokActionUrl ?>"; break;
                    case "web.katalog-kelompok"              : this._actionUrl = "<?= $webKatalogKelompokActionUrl ?>"; break;
                    case "web.jenis-kelompok"                : this._actionUrl = "<?= $webJenisKelompokActionUrl ?>"; break;
                    case "web.kelompok"                      : this._actionUrl = "<?= $webKelompokActionUrl ?>"; break;
                    // case "web.:   this._actionUrl = "<?= $web7ActionUrl ?? '' ?>"; break;
                    default: throw new Error("Wrong Option");
                }
            },
            onSuccessSubmit(event) {
                printTargetElm.innerHTML = event.data;
            },
            onFailSubmit() {
                printTargetElm.innerHTML = str._<?= $h("terjadi error") ?>;
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(laporanBulanWgt);
        tlm.app.registerWidget(this.constructor.widgetName, laporanBulanWgt);
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
