<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\DistribusiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Distribusi/index.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $infoWidgetId,
        string $printWidgetId,
        string $formReturGasMedisWidgetId,
        string $formGasMedisWidgetId,
        string $deleteUrl,
        string $tipeDokumenDistribusiSelect,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.DistribusiUi.Table {
    export interface FormFields {
        tanggalDistribusi: "?"|"??";
        noDistribusi:      "?"|"??";
        jenisDistribusi:   "?"|"??";
        noPermintaan:      "?"|"??";
        noPenerimaan:      "?"|"??";
        statusPrioritas:   "?"|"??";
        namaDepoPengirim:  "?"|"??";
        namaDepoPenerima:  "?"|"??";
    }

    export interface TableFields {
        id:                string;
        kode:              string;
        tanggalDistribusi: string;
        noDistribusi:      string;
        jenisDistribusi:   string;
        tipeDistribusi:    string;
        noPermintaan:      string;
        noPenerimaan:      string;
        statusPrioritas:   string;
        namaDepoPengirim:  string;
        namaDepoPenerima:  string;
        verKirim:          string;
        namaUserKirim:     string;
        verTanggalKirim:   string;
        verTerima:         string;
        namaUserTerima:    string;
        verTanggalTerima:  string;
        nilaiAkhir:        string;
        inputBy:           string;
        inputTime:         string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Distribusi Gas Medis") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Distribusi") ?>,
                        input: {class: ".tanggalDistribusiFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("No. Distribusi") ?>,
                        input: {class: ".noDistribusiFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Jenis Distribusi") ?>,
                        select: {class: ".jenisDistribusiFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Permintaan") ?>,
                        input: {class: ".noPermintaanFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>,
                        input: {class: ".noPenerimaanFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Status Priority") ?>,
                        select: {
                            class: ".statusPrioritasFld",
                            option_1: {value: "", label: ""},
                            option_2: {value: 0, label: tlm.stringRegistry._<?= $h("Regular") ?>},
                            option_3: {value: 1, label: tlm.stringRegistry._<?= $h("Cito") ?>},
                        }
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Depo Pengirim") ?>,
                        select: {class: ".namaDepoPengirimFld"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Depo Penerima") ?>,
                        select: {class: ".namaDepoPenerimaFld"}
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
            widthTable: {
                class: ".distribusiTbl",
                thead: {
                    tr: {
                        td_1:  {text: ""},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Tanggal Distribusi") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("No. Distribusi") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Jenis Distribusi") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("No. Permintaan") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Prioritas") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Depo Pengirim") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Depo Penerima") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Ver. Kirim") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Verifikator Pengirim") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Tanggal Kirim") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Ver. Terima") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Verifikator Penerima") ?>},
                        td_15: {text: tlm.stringRegistry._<?= $h("Tanggal Terima") ?>},
                        td_16: {text: tlm.stringRegistry._<?= $h("Total Distribusi") ?>},
                        td_17: {text: tlm.stringRegistry._<?= $h("User Input") ?>},
                        td_18: {text: tlm.stringRegistry._<?= $h("Tanggal Input") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const draw = spl.TableDrawer.drawButton;
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const noDistribusiFld = divElm.querySelector(".noDistribusiFld");
        /** @type {HTMLSelectElement} */ const jenisDistribusiFld = divElm.querySelector(".jenisDistribusiFld");
        /** @type {HTMLInputElement} */  const noPermintaanFld = divElm.querySelector(".noPermintaanFld");
        /** @type {HTMLInputElement} */  const noPenerimaanFld = divElm.querySelector(".noPenerimaanFld");
        /** @type {HTMLSelectElement} */ const statusPrioritasFld = divElm.querySelector(".statusPrioritasFld");
        /** @type {HTMLSelectElement} */ const namaDepoPengirimFld = divElm.querySelector(".namaDepoPengirimFld");
        /** @type {HTMLSelectElement} */ const namaDepoPenerimaFld = divElm.querySelector(".namaDepoPenerimaFld");

        tlm.app.registerSelect("_<?= $tipeDokumenDistribusiSelect ?>", jenisDistribusiFld);
        tlm.app.registerSelect("_<?= $depoSelect ?>", namaDepoPengirimFld);
        tlm.app.registerSelect("_<?= $depoSelect ?>", namaDepoPenerimaFld);
        this._selects.push(jenisDistribusiFld, namaDepoPengirimFld, namaDepoPenerimaFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.DistribusiUi.Table.FormFields} data */
            loadData(data) {
                tanggalDistribusiWgt.value = data.tanggalDistribusi ?? "";
                noDistribusiFld.value = data.noDistribusi ?? "";
                jenisDistribusiFld.value = data.jenisDistribusi ?? "";
                noPermintaanFld.value = data.noPermintaan ?? "";
                noPenerimaanFld.value = data.noPenerimaan ?? "";
                statusPrioritasFld.value = data.statusPrioritas ?? "";
                namaDepoPengirimFld.value = data.namaDepoPengirim ?? "";
                namaDepoPenerimaFld.value = data.namaDepoPenerima ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        tanggalDistribusi: tanggalDistribusiWgt.value,
                        noDistribusi: noDistribusiFld.value,
                        jenisDistribusi: jenisDistribusiFld.value,
                        noPermintaan: noPermintaanFld.value,
                        noPenerimaan: noPenerimaanFld.value,
                        statusPrioritas: statusPrioritasFld.value,
                        namaDepoPengirim: namaDepoPengirimFld.value,
                        namaDepoPenerima: namaDepoPenerimaFld.value,
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".distribusiTbl"),
            url: "<?= $dataUrl ?>",
            idField: "id",
            columns: {
                1: {formatter(unused, item) {
                    const {noDistribusi, kode, tipeDistribusi, verKirim} = item;
                    const deleteBtn = draw({class: ".deleteBtn", icon: "trash",  type: "danger",  value: JSON.stringify({kode, noDistribusi}),   text: str._<?= $h("Delete") ?>});
                    const editBtn =   draw({class: ".editBtn",   icon: "pencil", type: "primary", value: JSON.stringify({kode, tipeDistribusi}), text: str._<?= $h("Edit") ?>});
                    const viewBtn =   draw({class: ".viewBtn",   icon: "list",   type: "info",    value: kode,                                   text: str._<?= $h("Lihat") ?>});
                    const printBtn =  draw({class: ".printBtn",  icon: "print",  type: "info",    value: kode,                                   text: str._<?= $h("Cetakan") ?>});

                    return (verKirim == "1" ? "" : deleteBtn) +
                        (verKirim == "1" ? "" : editBtn) +
                        viewBtn + printBtn;
                }},
                2: {field: "tanggalDistribusi", formatter: tlm.dateFormatter},
                3: {field: "noDistribusi"},
                4: {field: "jenisDistribusi"},
                5: {field: "noPermintaan"},
                6: {field: "noPenerimaan"},
                7: {formatter(unused, {statusPrioritas}) {
                    return statusPrioritas
                        ? draw({type: "warning", text: str._<?= $h("Cito") ?>})
                        : draw({type: "primary", text: str._<?= $h("Regular") ?>});
                }},
                8:  {field: "namaDepoPengirim"},
                9:  {field: "namaDepoPenerima"},
                10: {formatter(unused, {verKirim}) {
                    return verKirim == "1"
                        ? draw({class: ".btn-verVis", "dt-idcol": "0", "dt-verif": "kirim", icon: "check-square", text: str._<?= $h("Sudah") ?>})
                        : draw({class: ".btn-verif",                   "dt-verif": "kirim", icon: "warning",      text: str._<?= $h("Belum") ?>});
                }},
                11: {field: "namaUserKirim"},
                12: {field: "verTanggalKirim", formatter: tlm.datetimeFormatter},
                13: {formatter(unused, {verTerima}) {
                    return verTerima == "1"
                        ? draw({class: ".btn-verVis", "dt-idcol": "0", "dt-verif": "terima", icon: "check-square", text: str._<?= $h("Sudah") ?>})
                        : draw({class: ".btn-verif",                   "dt-verif": "terima", icon: "warning",      text: str._<?= $h("Belum") ?>});
                }},
                14: {field: "namaUserTerima"},
                15: {field: "verTanggalTerima", formatter: tlm.datetimeFormatter},
                16: {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                17: {field: "inputBy", visible: false},
                18: {field: "inputTime", visible: false, formatter: tlm.datetimeFormatter}
            }
        });

        const tanggalDistribusiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDistribusiFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $infoWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({xyz: printBtn.value, abc: "v-01"}, true); // TODO: js: uncategorized: finish this
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const {kode, tipeDistribusi} = JSON.parse(editBtn.value);
            const widgetId = (tipeDistribusi == "6") ? "_<?= $formReturGasMedisWidgetId ?>" : "_<?= $formGasMedisWidgetId ?>";
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;

            const {noDistribusi, kode} = JSON.parse(deleteBtn.value);
            const confirmMsg = str._<?= $h("Apakah Anda yakin ingin menghapus distribusi dengan no. {{NO}}") ?>;
            if (!confirm(confirmMsg.replace("{{NO}}", noDistribusi))) return;

            $.post({
                url: "<?= $deleteUrl ?>",
                data: {kode, keterangan: noDistribusi},
                success(data) {
                    data ? closest(deleteBtn, "tr").remove() : alert(str._<?= $h("Hapus Data gagal.") ?>);
                }
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, tableWgt);
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
