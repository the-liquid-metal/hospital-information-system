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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/searchrekapgas.php the original file
 * last exist of tlm\his\FatmaPharmacy\views\PenerimaanUi\SearchRekapGas: commit-e37d34f4
 * last exist of tlm\his\FatmaPharmacy\views\PenerimaanUi\Reports: commit-e37d34f4
 */
final class FormGasMedis
{
    private string $output;

    public function __construct(
        string $registerId,
        string $jenisBarangActionUrl,
        string $itemBarangActionUrl,
        string $bukuIndukActionUrl,
        string $gudangPenyimpananSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPenerimaanUi.FormGasMedis {
    export interface Fields {
        tanggalAwal:         string;
        tanggalAkhir:        string;
        idGudangPenyimpanan: string;
        rekap:               string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName]: {
            ".per-jenis": {
                tbody: {
                    _children: ["td:nth-child(5)", "td:nth-child(7)", "td:nth-child(8)", "td:nth-child(9)"],
                    _style: {textAlign: "right"},
                },
                tfoot: {
                    _children: ["td:nth-child(2)", "td:nth-child(3)", "td:nth-child(4)"],
                    _style: {textAlign: "right"},
                }
            },
            ".per-item": {
                tbody: {
                    _children: ["td:nth-child(5)", "td:nth-child(6)", "td:nth-child(7)"],
                    _style: {textAlign: "right"},
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
            class: ".laporanGasMedisFrm",
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
                        label: tlm.stringRegistry._<?= $h("Gudang Penyimpanan") ?>,
                        select: {class: ".idGudangPenyimpananFld", name: "idGudangPenyimpanan"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                        select: {
                            class: ".rekapFld",
                            option_1: {value: "jenisBarang", label: tlm.stringRegistry._<?= $h("Jenis Barang") ?>},
                            option_2: {value: "itemBarang",  label: tlm.stringRegistry._<?= $h("Item Barang") ?>},
                            option_3: {value: "bukuInduk",   label: tlm.stringRegistry._<?= $h("Buku Induk") ?>},
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
        row_5: {
            widthColumn: {
                paragraph: {text: "&npsp;"}
            }
        },
        row_6: {
            widthColumn: {class: ".printTargetElm"}
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLSelectElement} */ const rekapFld = divElm.querySelector(".rekapFld");
        /** @type {HTMLDivElement} */    const printTargetElm = divElm.querySelector(".printTargetElm");

        tlm.app.registerSelect("_<?= $gudangPenyimpananSelect ?>", idGudangPenyimpananFld);
        this._selects.push(idGudangPenyimpananFld);

        const laporanGasMedisWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanGasMedisFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPenerimaanUi.FormGasMedis.Fields} data */
            loadData(data) {
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                rekapFld.value = data.rekap ?? "";
            },
            resetBtnId: false,
            onBeforeSubmit() {
                switch (rekapFld.value) {
                    case "jenisBarang": this._actionUrl = "<?= $jenisBarangActionUrl ?>"; break;
                    case "itemBarang":  this._actionUrl = "<?= $itemBarangActionUrl ?>"; break;
                    case "bukuInduk":   this._actionUrl = "<?= $bukuIndukActionUrl ?>"; break;
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
