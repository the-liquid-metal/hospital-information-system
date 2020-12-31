<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepBillingUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepbilling/listresep.php the original file
 */
final class TableResep
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $transferUrl,
        string $batalBayarUrl,
        string $deleteUrl,
        string $formBayarWidgetId,
        string $cetakStrukWidgetId,
        string $cetakStrukBriWidgetId,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        // $verifikasiUrl = "";    // TODO: php: uncategorized: confirm unused property
        // $cetakStrukUrl = "";    // TODO: php: uncategorized: confirm unused property
        // $cetakStrukBriUrl = ""; // TODO: php: uncategorized: confirm unused property
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepBillingUi.ListResep {
    export interface FormFields {
        idDepo:         string;
        dariTanggal:    string;
        sampaiTanggal:  string;
        kodeRekamMedis: string;
        noResep:        string;
    }

    export interface TableFields {
        noResep:          string;
        kodeRekamMedis:   string;
        namaPasien:       string;
        tanggalPenjualan: string;
        totalCeil:        string;
        bayar:            string;
        verifikasi:       string;
        transfer:         string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Kasir") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Depo") ?>,
                        select: {class: ".idDepoFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Dari Tanggal") ?>,
                        input: {class: ".dariTanggalFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Sampai Tanggal") ?>,
                        input: {class: ".sampaiTanggalFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                        input: {class: ".noResepFld"}
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
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. Resep") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Nama Pasien") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Tanggal Resep") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Total Penjualan") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;
        /** @type {function(elm: HTMLElement, selector: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const idDepoFld = divElm.querySelector(".idDepoFld");
        /** @type {HTMLInputElement} */  const kodeRekamMedisFld = divElm.querySelector(".kodeRekamMedisFld");
        /** @type {HTMLInputElement} */  const noResepFld = divElm.querySelector(".noResepFld");

        tlm.app.registerSelect("_<?= $depoSelect ?>", idDepoFld);
        this._selects.push(idDepoFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.EresepBillingUi.ListResep.FormFields} data */
            loadData(data) {
                idDepoFld.value = data.idDepo ?? "";
                dariTanggalWgt.value = data.dariTanggal ?? "";
                sampaiTanggalWgt.value = data.sampaiTanggal ?? "";
                kodeRekamMedisFld.value = data.kodeRekamMedis ?? "";
                noResepFld.value = data.noResep ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        idDepo: idDepoFld.value,
                        dariTanggal: dariTanggalWgt.value,
                        sampaiTanggal: sampaiTanggalWgt.value,
                        kodeRekamMedis: kodeRekamMedisFld.value,
                        noResep: noResepFld.value,
                    }
                });
            }
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

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noResep"},
                3: {field: "kodeRekamMedis"},
                4: {field: "namaPasien"},
                5: {field: "tanggalPenjualan", formatter: tlm.dateFormatter},
                6: {field: "totalCeil", formatter: tlm.intFormatter},
                7: {formatter(unused, item) {
                    const {noResep: value, verifikasi, transfer, bayar} = item;
                    const editBtn =     draw({class: ".editBtn",     value, type: "success", text: str._<?= $h("Edit") ?>});
                    const printBtn =    draw({class: ".printBtn",    value, type: "success", text: str._<?= $h("Print") ?>, disabled: !!bayar});
                    const printBriBtn = draw({class: ".printBriBtn", value, type: "success", text: str._<?= $h("Print BRI") ?>, disabled: !!bayar});
                    const transferBtn = draw({class: ".transferBtn", value, type: "warning", text: str._<?= $h("Bayar") ?>});
                    const batalBtn =    draw({class: ".batalBtn",    value, type: "danger",  text: str._<?= $h("Batal Bayar") ?>, disabled: !!bayar});
                    const deleteBtn =   draw({class: ".deleteBtn",   value, type: "danger",  text: str._<?= $h("Delete") ?>});

                    return editBtn + printBtn + printBriBtn + transferBtn + batalBtn + (!verifikasi && !transfer ? deleteBtn : "");
                }}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formBayarWidgetId ?>");
            widget.show();
            widget.load({noResep: editBtn.value});
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const widget = tlm.app.getWidget("_<?= $cetakStrukWidgetId ?>");
            widget.show();
            widget.load({noResep: printBtn.value});
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const printBriBtn = event.target;
            if (!printBriBtn.matches(".printBriBtn")) return;

            const widget = tlm.app.getWidget("_<?= $cetakStrukBriWidgetId ?>");
            widget.show();
            widget.load({noResep: printBriBtn.value});
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const transferBtn = event.target;
            if (!transferBtn.matches(".transferBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin membayar?") ?>)) return;

            const trElm = closest(transferBtn, "tr");
            $.post({
                url: "<?= $transferUrl ?>",
                data: {q: "", id: transferBtn.value}
            });
            alert(str._<?= $h("Berhasil Bayar.") ?>);
            trElm.querySelector(".printBtn").disabled = false;
            trElm.querySelector(".printBriBtn").disabled = false;
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const batalBtn = event.target;
            if (!batalBtn.matches(".batalBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin membatalkan pembayaran?") ?>)) return;

            $.post({
                url: "<?= $batalBayarUrl ?>",
                data: {q: "", id: batalBtn.value}
            });
            alert(str._<?= $h("Berhasil Batal Bayar.") ?>);
            batalBtn.disabled = true;
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin menghapus pembayaran?") ?>)) return;

            $.post({
                url: "<?= $deleteUrl ?>",
                data: {noResep: deleteBtn.value}
            });
            alert(str._<?= $h("Berhasil hapus.") ?>);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, dariTanggalWgt, sampaiTanggalWgt, itemWgt);
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
