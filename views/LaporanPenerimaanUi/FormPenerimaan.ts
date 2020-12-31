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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/penerimaan/reports.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\PenerimaanUi\Reports: commit-e37d34f4
 *
 * NOTE: this form is aligned with \tlm\his\FatmaPharmacy\views\ReturFarmasiUi\FormReport
 * (or http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/reports.php)
 * to simplify the structure and algorithm. the sql query in controller is not affected.
 */
final class FormPenerimaan
{
    private string $output;

    public function __construct(string $registerId, string $rekapActionUrl, string $bukuIndukActionUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPenerimaanUi.FormPenerimaan {
    export interface Fields {
        jenis:        string;
        tanggalAwal:  string;
        tanggalAkhir: string;
        status:       string;
        format:       string;
        tahap:        string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName]: {
            "option:disabled": {
                _style: {textDecoration: "line-through", background: "inherit"}
            },
            ".printTargetElm": {
                ".rekap tbody": {
                    _children: ["td:nth-child(5)", "td:last-child"],
                    _style: {textAlign: "right"}
                },
                ".buku-induk tbody": {
                    _children: [
                        "td:nth-child(5)",
                        "td:nth-child(7)",
                        "td:nth-child(8)",
                        "td:nth-child(9)",
                        "td:nth-child(10)",
                        "td:nth-child(11)",
                        "td:nth-child(12)"
                    ],
                    _style: {textAlign: "right"}
                }
            }
        }
    };

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
            class: ".laporanPenerimaanFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Jenis") ?>,
                        select: {
                            class: ".jenisFld",
                            name: "jenis",
                            option_1: {value: "penerimaanBarang",     label: tlm.stringRegistry._<?= $h("Penerimaan Barang") ?>},
                            option_2: {value: "verifikasiPenerimaan", label: tlm.stringRegistry._<?= $h("Verifikasi Penerimaan") ?>},
                            option_3: {value: "verifikasiGudang",     label: tlm.stringRegistry._<?= $h("Verifikasi Gudang") ?>},
                            option_4: {value: "verifikasiAkuntansi",  label: tlm.stringRegistry._<?= $h("Verifikasi Akuntansi") ?>}
                        }
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal") ?>,
                        input: {class: ".tanggalAwalFld", name: "tanggalAwal"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                        input: {class: ".tanggalAkhirFld", name: "tanggalAkhir"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tahap Verifikasi") ?>,
                        select: {
                            class: ".tahapFld",
                            name: "tahap",
                            option_1: {value: "verifikasiPenerimaan", label: tlm.stringRegistry._<?= $h("Verifikasi Penerimaan") ?>},
                            option_2: {value: "verifikasiGudang",     label: tlm.stringRegistry._<?= $h("Verifikasi Gudang") ?>},
                            option_3: {value: "verifikasiAkuntansi",  label: tlm.stringRegistry._<?= $h("Verifikasi Akuntansi") ?>}
                        }
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Status Verifikasi") ?>,
                        select: {
                            class: ".statusFld",
                            name: "status",
                            option_1: {value: 1,  label: tlm.stringRegistry._<?= $h("Sudah") ?>},
                            option_2: {value: 0,  label: tlm.stringRegistry._<?= $h("Belum") ?>},
                            option_3: {value: "", label: tlm.stringRegistry._<?= $h("Semua") ?>},
                        }
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                        select: {
                            class: ".formatFld",
                            option_1: {value: "rekap",     label: tlm.stringRegistry._<?= $h("Rekapitulasi") ?>},
                            option_2: {value: "bukuInduk", label: tlm.stringRegistry._<?= $h("Buku Induk") ?>},
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

        /** @type {HTMLDivElement} */    const printTargetElm = divElm.querySelector(".printTargetElm");
        /** @type {HTMLSelectElement} */ const jenisFld = divElm.querySelector(".jenisFld");
        /** @type {HTMLSelectElement} */ const statusFld = divElm.querySelector(".statusFld");
        /** @type {HTMLSelectElement} */ const tahapFld = divElm.querySelector(".tahapFld");
        /** @type {HTMLSelectElement} */ const formatFld = divElm.querySelector(".formatFld");

        const laporanPenerimaanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanPenerimaanFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPenerimaanUi.FormPenerimaan.Fields} data */
            loadData(data) {
                jenisFld.value = data.jenis ?? "";
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                statusFld.value = data.status ?? "";
                tahapFld.value = data.tahap ?? "";
                formatFld.value = data.format ?? "";
            },
            resetBtnId: false,
            onBeforeSubmit() {
                switch (formatFld.value) {
                    case "bukuInduk": this._actionUrl = "<?= $bukuIndukActionUrl?>"; break;
                    case "rekap"    : this._actionUrl = "<?= $rekapActionUrl?>"; break;
                    default: throw new Error("Wrong option");
                }
            },
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
        this._widgets.push(laporanPenerimaanWgt, tanggalAwalWgt, tanggalAkhirWgt);
        tlm.app.registerWidget(this.constructor.widgetName, laporanPenerimaanWgt);
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
