<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ReturFarmasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/reports.php the original file
 */
final class FormReport
{
    private string $output;

    public function __construct(string $registerId, string $bukuIndukActionUrl, string $rekapPemasokActionUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ReturFarmasiUi.FormReport {
    export interface Fields {
        jenis:            string;
        tanggalAwal:      string;
        tanggalAkhir:     string;
        tahapVerifikasi:  string;
        statusVerifikasi: string;
        formatLaporan:    string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Retur Penerimaan") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".reportReturFarmasiFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Jenis") ?>,
                        select: {
                            class: ".jenisFld",
                            name: "jenis",
                            option_1: {value: "dokumenPenerimaan",    label: tlm.stringRegistry._<?= $h("Terima Barang") ?>},
                            option_2: {value: "dokumenRetur",         label: tlm.stringRegistry._<?= $h("Retur Gudang") ?>},
                            option_3: {value: "verifikasiGudang",     label: tlm.stringRegistry._<?= $h("Verifikasi Gudang") ?>},
                            option_4: {value: "verifikasiPenerimaan", label: tlm.stringRegistry._<?= $h("Verifikasi Penerimaan") ?>},
                            option_5: {value: "verifikasiAkuntansi",  label: tlm.stringRegistry._<?= $h("Verifikasi Akuntansi") ?>}
                        }
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal") ?>,
                        input: {class: ".tanggalAwalFld", name: "tanggalAwal"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                        input: {class: ".tanggalAkhirFld", name: "tgl_akhir"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tahap Verifikasi") ?>,
                        select: {
                            class: ".tahapVerifikasiFld",
                            name: "tahapVerifikasi",
                            option_1: {value: "gudang",     label: tlm.stringRegistry._<?= $h("Gudang") ?>},
                            option_2: {value: "penerimaan", label: tlm.stringRegistry._<?= $h("Penerimaan") ?>},
                            option_3: {value: "akuntansi",  label: tlm.stringRegistry._<?= $h("Akuntansi") ?>}
                        }
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Status Verifikasi") ?>,
                        select: {
                            class: ".statusVerifikasiFld",
                            name: "statusVerifikasi",
                            option_1: {value: 1,  label: tlm.stringRegistry._<?= $h("Sudah") ?>},
                            option_2: {value: 0,  label: tlm.stringRegistry._<?= $h("Belum") ?>},
                            option_3: {value: "", label: tlm.stringRegistry._<?= $h("Semua") ?>}
                        }
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                        select: {
                            class: ".formatLaporanFld",
                            option_1: {value: "bukuInduk",    label: tlm.stringRegistry._<?= $h("Buku Induk") ?>},
                            option_2: {value: "rekapPemasok", label: tlm.stringRegistry._<?= $h("Rekapitulasi Retur Penerimaan - Pemasok") ?>}
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

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const printTargetElm = divElm.querySelector(".printTargetElm");
        /** @type {HTMLSelectElement} */ const jenisFld = divElm.querySelector(".jenisFld");
        /** @type {HTMLSelectElement} */ const tahapVerifikasiFld = divElm.querySelector(".tahapVerifikasiFld");
        /** @type {HTMLSelectElement} */ const statusVerifikasiFld = divElm.querySelector(".statusVerifikasiFld");
        /** @type {HTMLSelectElement} */ const formatLaporanFld = divElm.querySelector(".formatLaporanFld");

        const reportReturFarmasiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".reportReturFarmasiFrm"),
            /** @param {his.FatmaPharmacy.views.ReturFarmasiUi.FormReport.Fields} data */
            loadData(data) {
                jenisFld.value = data.jenis ?? "";
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                tahapVerifikasiFld.value = data.tahapVerifikasi ?? "";
                statusVerifikasiFld.value = data.statusVerifikasi ?? "";
                formatLaporanFld.value = data.formatLaporan ?? "";
            },
            resetBtnId: false,
            onBeforeSubmit() {
                switch (formatLaporanFld.value) {
                    case "bukuInduk":    this._actionUrl = "<?= $bukuIndukActionUrl ?>"; break;
                    case "rekapPemasok": this._actionUrl = "<?= $rekapPemasokActionUrl ?>"; break;
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
        this._widgets.push(reportReturFarmasiWgt, tanggalAwalWgt, tanggalAkhirWgt);
        tlm.app.registerWidget(this.constructor.widgetName, reportReturFarmasiWgt);
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
