<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepoUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepo/listresep.php the original file
 */
final class TableResep
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $verifikasiUrl,
        string $transferUrl,
        string $deleteUrl,
        string $formEditWidgetId,
        string $formReturWidgetId,
        string $viewStrukWidgetId,
        string $antrianWidgetId,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepDepoUi.ListResep {
    export interface FormFields {
        idDepo:         string;
        dariTanggal:    string;
        sampaiTanggal:  string;
        kodeRekamMedis: string;
        noResep:        string;
    }

    export interface TableFields {
        noResep:           string;
        noAntrian:         string;
        kodeRekamMedis:    string;
        namaPasien:        string;
        tanggalPenjualan:  string;
        totalCeil:         string;
        verifikasi:        string;
        transfer:          string;
        jenisResep:        string;
        tanggalVerifikasi: string;
        kodePenjualan:     string;
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
            class: ".saringFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Depo") ?>,
                        select: {class: ".idDepoFld", name: "idDepo"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Dari Tanggal") ?>,
                        input: {class: ".dariTanggalFld", name: "dariTanggal"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Sampai Tanggal") ?>,
                        input: {class: ".sampaiTanggalFld", name: "sampaiTanggal"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                        input: {class: ".noResepFld", name: "noResep"}
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
                        td_3: {text: tlm.stringRegistry._<?= $h("No. Antrian") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Nama Pasien") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Tanggal Resep") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Total Penjualan") ?>},
                        td_8: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const idDepoFld = divElm.querySelector(".idDepoFld");
        /** @type {HTMLInputElement} */  const kodeRekamMedisFld = divElm.querySelector(".kodeRekamMedisFld");
        /** @type {HTMLInputElement} */  const noResepFld = divElm.querySelector(".noResepFld");

        tlm.app.registerSelect("_<?= $depoSelect ?>", idDepoFld);
        this._selects.push(idDepoFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.ListResep.FormFields} data */
            loadData(data) {
                idDepoFld.value = data.idDepo ?? "";
                dariTanggalWgt.value = data.dariTanggal ?? "";
                sampaiTanggalWgt.value = data.sampaiTanggal ?? "";
                kodeRekamMedisFld.value = data.kodeRekamMedis ?? "";
                noResepFld.value = data.noResep ?? "";
            },
            resetBtnId: false,
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
                3: {field: "noAntrian"},
                4: {field: "kodeRekamMedis"},
                5: {field: "namaPasien"},
                6: {field: "tanggalPenjualan", formatter: tlm.dateFormatter},
                7: {field: "totalCeil", formatter: tlm.intFormatter},
                8: {formatter(unused, item) {
                    const {noResep, verifikasi, transfer, jenisResep, tanggalVerifikasi, kodePenjualan} = item;
                    const disabled = (transfer || jenisResep === "Pembelian Bebas" || jenisResep === "Pembelian Langsung");
                    const type = disabled ? "warning" : "success";

                    const editBtn       = draw({class: ".editBtn",       value: noResep,       text: str._<?= $h("Edit") ?>});
                    const resepBtn      = draw({class: ".resepBtn",      value: kodePenjualan, text: str._<?= $h("R. Dokter") ?>});
                    const viewBtn       = draw({class: ".viewBtn",       value: noResep,       text: str._<?= $h("Lihat") ?>});
                    const returBtn      = draw({class: ".returBtn",      value: noResep,       text: str._<?= $h("Retur") ?>});
                    const deleteBtn     = draw({class: ".deleteBtn",     value: noResep,       text: str._<?= $h("Delete") ?>});
                    const transferBtn   = draw({class: ".transferBtn",   value: noResep,       text: str._<?= $h("Transfer") ?>, type, disabled});
                    const verifikasiBtn = draw({class: ".verifikasiBtn", value: noResep,       text: str._<?= $h("Verifikasi") ?>});

                    return editBtn + resepBtn + viewBtn + transferBtn +
                        (!verifikasi && !tanggalVerifikasi && !transfer ? returBtn : "") +
                        (!verifikasi && !tanggalVerifikasi && !transfer ? deleteBtn : "") +
                        (verifikasi || tanggalVerifikasi ? "" : verifikasiBtn);
                }}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const verifikasiBtn = event.target;
            if (!verifikasiBtn.matches(".verifikasiBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin memverifikasi?") ?>)) return;

            $.post({
                url: "<?= $verifikasiUrl ?>",
                data: {q: "", id: verifikasiBtn.value},
                success() {
                    alert(str._<?= $h("Berhasil verifikasi. Stok telah dikurangi.") ?>);
                    verifikasiBtn.remove();
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const transferBtn = event.target;
            if (!transferBtn.matches(".transferBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin mentransfer?") ?>)) return;

            $.post({
                url: "<?= $transferUrl ?>",
                data: {q: "", id: transferBtn.value},
                success() {
                    alert(str._<?= $h("Berhasil Transfer.") ?>);
                    transferBtn.classList.remove("btn-success");
                    transferBtn.classList.add("btn-warning");
                    transferBtn.disabled = true;
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;

            $.post({
                url: "<?= $deleteUrl ?>",
                data: {noResep: deleteBtn.value}
            });
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const returBtn = event.target;
            if (!returBtn.matches(".returBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formReturWidgetId ?>");
            widget.show();
            widget.load({noResep: returBtn.value});
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewStrukWidgetId ?>");
            widget.show();
            widget.load({noResep: viewBtn.value});
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const resepBtn = event.target;
            if (!resepBtn.matches(".resepBtn")) return;

            const widget = tlm.app.getWidget("_<?= $antrianWidgetId ?>");
            widget.show();
            widget.loadData({kodePenjualan: resepBtn.value}, true);
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formEditWidgetId ?>");
            widget.show();
            widget.load({noResep: editBtn.value});
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
