<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PengadaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pengadaan/reports.php the original file
 */
final class Report
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $jenisAnggaranSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pengadaan.Report {
    export interface Fields {
        subjenisAnggaran: "subjenis_anggaran";
        idJenisAnggaran:  "id_jenisanggaran";
        idJenisHarga:     "id_jenisharga";
        tanggalMulai:     "tgl_mulai";
        tanggalAkhir:     "tgl_akhir";
        idSubsumberDana:  "id_subsumberdana";
        format:           "format";
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
                heading3: {text: tlm.stringRegistry._<?= $h("Realisasi HPS") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".saringFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        hidden: {class: ".subjenisAnggaranFld", name: "subjenisAnggaran"}, // rollback to meet js statement, but suspected unused
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {
                            class: ".idJenisHargaFld",
                            name: "idJenisHarga",
                            option_1: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("E-Katalog & Non E-Katalog") ?>},
                            option_2: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("E-Katalog") ?>},
                            option_3: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Non E-Katalog") ?>}
                        }
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Mulai Kontrak") ?>,
                        input: {class: ".tanggalMulaiFld", name: "tanggalMulai"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir Kontrak") ?>,
                        input: {class: ".tanggalAkhirFld", name: "tanggalAkhir"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {
                            class: ".idSubsumberDanaFld",
                            name: "idSubsumberDana",
                            option_1: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa APBN & Dipa PNBP") ?>},
                            option_2: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa APBN") ?>},
                            option_3: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Dipa PNBP") ?>}
                        }
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Format") ?>,
                        select: {
                            class: ".formatFld",
                            name: "format",
                            option: {value: "laporan_akhir", label: tlm.stringRegistry._<?= $h("Laporan Realisasi HPS") ?>}
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
        }
    };

    constructor(divElm) {
        super();

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const subjenisAnggaranFld = divElm.querySelector("..subjenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idSubsumberDanaFld = divElm.querySelector(".idSubsumberDanaFld");
        /** @type {HTMLSelectElement} */ const formatFld = divElm.querySelector(".formatFld");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        this._selects.push(idJenisAnggaranFld);

        const saringWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Pengadaan.Report.Fields} data */
            loadData(data) {
                subjenisAnggaranFld.value = data.subjenisAnggaran ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                tanggalMulaiWgt.value = data.tanggalMulai ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                idSubsumberDanaFld.value = data.idSubsumberDana ?? "";
                formatFld.value = data.format ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        const tanggalMulaiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalMulaiFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalAkhirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAkhirFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        // suspected unused
        idJenisAnggaranFld.addEventListener("change", () => {
            subjenisAnggaranFld.value = idJenisAnggaranFld.selectedOptions[0].innerHTML;
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, tanggalMulaiWgt, tanggalAkhirWgt);
        tlm.app.registerWidget(this.constructor.widgetName, saringWgt);
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
