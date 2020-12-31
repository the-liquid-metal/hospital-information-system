<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PerencanaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/index.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $deleteAccess,
        array  $auditAccess,
        string $dataUrl,
        string $deleteUrl,
        string $viewWidgetId,
        string $printWidgetId,
        string $formBulananWidgetId,
        string $formWidgetId,
        string $formRevisiWidgetId,
        string $anggaranSelect,
        string $tipeDokumenPerencanaanSelect,
        string $tipeDokumenBulanSelect,
        string $tahunSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Perencanaan.Table {
    export interface FormFields {
        jenisDokumen:     "?"|"??";
        tanggalDokumen:   "?"|"??";
        noDokumen:        "?"|"??";
        noSpk:            "?"|"??";
        namaPemasok:      "?"|"??";
        subjenisAnggaran: "?"|"??";
        bulanAnggaran:    "?"|"??";
        tahunAnggaran:    "?"|"??";
    }

    export interface TableFields {
        tipeDokumen:        string;
        kode:               string;
        statusLinked:       string;
        statusRevisi:       string;
        jenisDokumen:       string;
        tanggalDokumen:     string;
        noDokumen:          string;
        noSpk:              string;
        namaPemasok:        string;
        subjenisAnggaran:   string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        nilaiAkhir:         string;
        verRevisiPl:        string;
        updatedBy:          string;
        updatedTime:        string;
        statusRevisiPl:     string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean, delete: boolean, audit: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            delete: JSON.parse(`<?=json_encode($deleteAccess) ?>`),
            audit: JSON.parse(`<?= json_encode($auditAccess) ?>`),
        };
        const access = {};
        for (const item in pool) {
            if (!pool.hasOwnProperty(item)) continue;
            access[item] = pool[item][role] ?? false;
        }
        return access;
    }

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Perencanaan") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Jenis Dokumen") ?>,
                        select: {class: ".jenisDokumenFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. SPK") ?>,
                        input: {class: ".noSpkFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Subjenis Anggaran") ?>,
                        select: {class: ".subjenisAnggaranFld"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld"},
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld"}
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
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1:  {text: ""},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Jenis Perencanaan") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Tanggal Perencanaan") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("No. Dokumen") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("No. SPK") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Jenis Anggaran") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Bulan Anggaran") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Nilai Perencanaan") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Updated User") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Update") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Status Revisi") ?>},
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
        const {numToShortMonthName: nToS, stringRegistry: str} = tlm;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const jenisDokumenFld = divElm.querySelector(".jenisDokumenFld");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const noSpkFld = divElm.querySelector(".noSpkFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const subjenisAnggaranFld = divElm.querySelector(".subjenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");

        tlm.app.registerSelect("_<?= $tipeDokumenPerencanaanSelect ?>", jenisDokumenFld);
        tlm.app.registerSelect("_<?= $anggaranSelect ?>", subjenisAnggaranFld);
        tlm.app.registerSelect("_<?= $tipeDokumenBulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(jenisDokumenFld, subjenisAnggaranFld, bulanAwalAnggaranFld, tahunAnggaranFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Perencanaan.Table.FormFields} data */
            loadData(data) {
                jenisDokumenFld.value = data.jenisDokumen ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                noSpkFld.value = data.noSpk ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                subjenisAnggaranFld.value = data.subjenisAnggaran ?? "";
                bulanAwalAnggaranFld.value = data.bulanAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        jenisDokumen: jenisDokumenFld.value,
                        tanggalDokumen: tanggalDokumenWgt.value,
                        noDokumen: noDokumenFld.value,
                        noSpk: noSpkFld.value,
                        namaPemasok: namaPemasokFld.value,
                        subjenisAnggaran: subjenisAnggaranFld.value,
                        bulanAwalAnggaran: bulanAwalAnggaranFld.value,
                        tahunAnggaran: tahunAnggaranFld.value,
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kode, noDokumen, verRevisiPl, tipeDokumen, statusLinked, statusRevisi} = item;
                    const deleteBtn = draw({class: ".deleteBtn", type: "danger",  icon: "trash", value: JSON.stringify({kode, noDokumen}),   title: str._<?= $h("Delete Perencanaan") ?>});
                    const editBtn   = draw({class: ".editBtn",   type: "primary", text: "E",     value: JSON.stringify({kode, tipeDokumen}), title: str._<?= $h("Edit Perencanaan") ?>});
                    const revisiBtn = draw({class: ".revisiBtn", type: "info",    text: "R",     value: kode,                                title: str._<?= $h("Revisi Perencanaan") ?>});
                    const viewBtn   = draw({class: ".viewBtn",   type: "info",    icon: "list",  value: kode,                                title: str._<?= $h("View Perencanaan") ?>});
                    const print1Btn = draw({class: ".printBtn",  type: "primary", text: "P1",    value: JSON.stringify({kode, versi: 1}),    title: str._<?= $h("Cetakan Perencanaan (versi-1)") ?>});
                    const print2Btn = draw({class: ".printBtn",  type: "primary", text: "P2",    value: JSON.stringify({kode, versi: 2}),    title: str._<?= $h("Cetakan Perencanaan (versi-2)") ?>});
                    const print3Btn = draw({class: ".printBtn",  type: "primary", text: "P3",    value: JSON.stringify({kode, versi: 2}),    title: str._<?= $h("Cetakan Perencanaan (versi-3)") ?>});
                    const print4Btn = draw({class: ".printBtn",  type: "primary", text: "P4",    value: JSON.stringify({kode, versi: 2}),    title: str._<?= $h("Cetakan Perencanaan (versi-4)") ?>});

                    return "" +
                        (statusLinked == "1" || !access.delete ? "" : deleteBtn) +
                        (statusLinked == "1" || !access.edit ? "" : editBtn) +
                        ((statusLinked == "1" && statusRevisi == "0") || statusLinked != "1" || verRevisiPl == "0" ? "" : revisiBtn) + // verRevisiPl == 0 => Revisi SP/SPK/Kontrak belum diverifikasi
                        viewBtn + print1Btn + print2Btn + print3Btn + print4Btn;
                }},
                2: {field: "jenisDokumen"},
                3: {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                4: {field: "noDokumen"},
                5: {field: "noSpk"},
                6: {field: "namaPemasok"},
                7: {field: "subjenisAnggaran"},
                8: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                9:  {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                10: {field: "updatedBy", visible: access.audit},
                11: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter},
                12: {formatter(unused, item) {
                    const {statusRevisi: srX, statusRevisiPl: srPl, verRevisiPl: vrPl} = item;
                    if      (srX == "1" && srPl == "1" /*----------*/) return draw({type: "danger", text: str._<?= $h("Ada Revisi PL") ?>});
                    else if (srX == "1" && srPl == "0" && vrPl == "1") return draw({type: "warning", text: str._<?= $h("Lakukan Revisi RO") ?>});
                    else    /*--------------------------------------*/ return "-";
                }}
            }
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        bulanAwalAnggaranFld.addEventListener("change", () => {
            const bln = bulanAwalAnggaranFld.selectedOptions[0].getAttribute("data");
            const vbln = bulanAwalAnggaranFld.value;

            tahunAnggaranFld.querySelectorAll("option").forEach(elm => {
                const thn = elm.getAttribute("data");
                elm.value = vbln ? `${bln}_${thn}` : thn;
            });
        });

        tahunAnggaranFld.addEventListener("change", () => {
            const thn = bulanAwalAnggaranFld.selectedOptions[0].getAttribute("data");
            const vthn = tahunAnggaranFld.value;

            bulanAwalAnggaranFld.querySelectorAll("option").forEach(item => {
                const bln = item.getAttribute("data");
                item.value = vthn ? `${bln}_${thn}` : bln;
            });
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const {kode, versi} = JSON.parse(printBtn.value);
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode, versi: "v-0"+versi}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const {tipeDokumen, kode} = JSON.parse(editBtn.value);

            const widgetId = tipeDokumen == "3" ? "_<?= $formBulananWidgetId ?>" : "_<?= $formWidgetId ?>";
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const revisiBtn = event.target;
            if (!revisiBtn.matches(".revisiBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formRevisiWidgetId ?>");
            widget.show();
            widget.loadData({kode: revisiBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;

            const {noDokumen, kode} = JSON.parse(deleteBtn.value);
            const confirmMsg = str._<?= $h("Apakah Anda yakin ingin menghapus perencanaan dengan no. {{NO_DOC}}") ?>;
            if (!confirm(confirmMsg.replace("{{NO_DOC}}", noDokumen))) return;

            $.post({
                url: "<?= $deleteUrl ?>",
                data: {kode, keterangan: noDokumen},
                success(data) {
                    data ? closest(deleteBtn, "tr").remove() : alert(str._<?= $h("Gagal hapus data.") ?>);
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
