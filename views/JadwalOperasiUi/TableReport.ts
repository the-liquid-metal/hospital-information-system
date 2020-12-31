<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\JadwalOperasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/search.php the original file
 */
final class TableReport
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $namaDokterUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.JadwalOperasi.Search {
    export interface FormFields {
        tanggalAwal:  "tanggal_awal",
        tanggalAkhir: "tanggal_akhir",
        smf:          "smf",
        idDokter:     "id_dokter",
        laporan:      "laporan",
    }
    export interface DokterFields {
        id:   "id";
        nama: "nama";
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Jadwal Operasi") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal") ?>,
                        input: {class: ".tanggalAwalFld", name: "tanggalAwal"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                        input: {class: ".tanggalAkhirFld", name: "tanggalAkhir"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("SMF") ?>,
                        select: {
                            class: ".smfFld",
                            name: "smf",
                            option_1: {value: "002", label: tlm.stringRegistry._<?= $h("Kebidanan Dan Kandungan") ?>},
                            option_2: {value: "004", label: tlm.stringRegistry._<?= $h("Bedah") ?>},
                            option_3: {value: "005", label: tlm.stringRegistry._<?= $h("Bedah Orthopedi Dan Traumatologi") ?>},
                            option_4: {value: "006", label: tlm.stringRegistry._<?= $h("Bedah Syaraf") ?>},
                            option_5: {value: "010", label: tlm.stringRegistry._<?= $h("Mata") ?>},
                            option_6: {value: "011", label: tlm.stringRegistry._<?= $h("THT") ?>}
                        }
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama Dokter") ?>,
                        select: {class: ".idDokterFld", name: "idDokter"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Laporan") ?>,
                        select: {
                            class: ".laporanFld",
                            name: "laporan",
                            option_1: {value: "disetujui", label: tlm.stringRegistry._<?= $h("Jadwal yang sudah disetujui") ?>},
                            option_2: {value: "dibuat",    label: tlm.stringRegistry._<?= $h("Jadwal yang telah dibuat") ?>}
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

        /** @type {HTMLSelectElement} */ const smfFld = divElm.querySelector(".smfFld");
        /** @type {HTMLSelectElement} */ const laporanFld = divElm.querySelector(".laporanFld");

        const saringWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Search.FormFields} data */
            loadData(data) {
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                idDokterWgt.value = data.idDokter ?? "";
                smfFld.value = data.smf ?? "";
                laporanFld.value = data.laporan ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
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

        /** @see {his.FatmaPharmacy.views.JadwalOperasi.Search.DokterFields} */
        const idDokterWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idDokterFld"),
            maxItems: 1,
            valueField: "nama",
            labelField: "nama",
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $namaDokterUrl ?>",
                    data: {q: typed, kode_smf: divElm.querySelector(".smfFld").value},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, tanggalAwalWgt, tanggalAkhirWgt, idDokterWgt);
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
