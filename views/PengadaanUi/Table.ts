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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pengadaan/index.php the original file
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
        string $formWidgetId,
        string $anggaranSelect,
        string $tipeDokumenBulanSelect,
        string $tahunSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $kodeRefRencana = "";
        ?>

<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pengadaan.Table {
    export interface FormFields {
        tanggalDokumen:     "?"|"??";
        noDokumen:          "?"|"??";
        noDokumenReferensi: "?"|"??";
        namaPemasok:        "?"|"??";
        subjenisAnggaran:   "?"|"??";
        bulanAwalAnggaran:  "?"|"??";
        tahunAnggaran:      "?"|"??";
        kodeRefRencana:     "?"|"??";
    }

    export interface TableFields {
        kode:               string;
        statusSaved:        string;
        statusLinked:       string;
        statusRevisi:       string;
        tanggalDokumen:     string;
        noDokumen:          string;
        noDokumenReferensi: string;
        namaPemasok:        string;
        subjenisAnggaran:   string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        nilaiAkhir:         string;
        updatedBy:          string;
        updatedTime:        string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Katalog") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen Referensi") ?>,
                        input: {class: ".noDokumenReferensiFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Subjenis Anggaran") ?>,
                        select: {class: ".subjenisAnggaranFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld"}
                    },
                    formGroup_7: {
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
                    td_1:  {text: ""},
                    td_2:  {text: tlm.stringRegistry._<?= $h("Tanggal HPS") ?>},
                    td_3:  {text: tlm.stringRegistry._<?= $h("No. Dokumen HPS") ?>},
                    td_4:  {text: tlm.stringRegistry._<?= $h("Ref. Perencanaan") ?>},
                    td_5:  {text: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>},
                    td_6:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                    td_7:  {text: tlm.stringRegistry._<?= $h("Bulan Anggaran") ?>},
                    td_8:  {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                    td_9:  {text: tlm.stringRegistry._<?= $h("Updated User") ?>},
                    td_10: {text: tlm.stringRegistry._<?= $h("Update") ?>},
                }
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {numToShortMonthName: nToS, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const noDokumenReferensiFld = divElm.querySelector(".noDokumenReferensiFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const subjenisAnggaranFld = divElm.querySelector(".subjenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");

        tlm.app.registerSelect("_<?= $anggaranSelect ?>", subjenisAnggaranFld);
        tlm.app.registerSelect("_<?= $tipeDokumenBulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(subjenisAnggaranFld, bulanAwalAnggaranFld, tahunAnggaranFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Pengadaan.Table.FormFields} data */
            loadData(data) {
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                noDokumenReferensiFld.value = data.noDokumenReferensi ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                subjenisAnggaranFld.value = data.subjenisAnggaran ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        tanggalDokumen: tanggalDokumenWgt.value,
                        noDokumen: noDokumenFld.value,
                        noDokumenReferensi: noDokumenReferensiFld.value,
                        namaPemasok: namaPemasokFld.value,
                        subjenisAnggaran: subjenisAnggaranFld.value,
                        bulanAwalAnggaran: bulanAwalAnggaranFld.value,
                        tahunAnggaran: tahunAnggaranFld.value,
                        kodeRefRencana: "<?= $kodeRefRencana ?>",
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kode, noDokumen, statusSaved, statusLinked, statusRevisi} = item;
                    const buttonCls = statusSaved == "1" ? "warning" : "primary";

                    const deleteBtn = draw({class: ".deleteBtn", type: "danger",  icon: "trash",  value: JSON.stringify({kode, noDokumen}), title: str._<?= $h("Delete HPS Pengadaan") ?>});
                    const editBtn   = draw({class: ".editBtn",   type: buttonCls, icon: "pencil", value: kode,                              title: str._<?= $h("Edit HPS Pengadaan") ?>});
                    const revisiBtn = draw({class: ".___",       type: "info",    text: "R",      value: "___",                             title: str._<?= $h("Revisi HPS Pengadaan") ?>});
                    const viewBtn   = draw({class: ".viewBtn",   type: "info",    icon: "list",   value: kode,                              title: str._<?= $h("View HPS Pengadaan") ?>});
                    const print1Btn = draw({class: ".printBtn",  type: "primary", text: "P1",     value: JSON.stringify({kode, versi: 1}),  title: str._<?= $h("Cetak HPS Pengadaan (versi-1)") ?>});
                    const print2Btn = draw({class: ".printBtn",  type: "primary", text: "P2",     value: JSON.stringify({kode, versi: 2}),  title: str._<?= $h("Cetak HPS Pengadaan (versi-2)") ?>});

                    return "" +
                        (statusLinked == "1" ? "" : deleteBtn) +
                        (statusLinked == "1" ? "" : editBtn) +
                        (statusRevisi == "0" ? "" : revisiBtn) +
                        viewBtn + print1Btn + print2Btn;
                }},
                2: {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                3: {field: "noDokumen"},
                4: {field: "noDokumenReferensi"},
                5: {field: "namaPemasok"},
                6: {field: "subjenisAnggaran"},
                7: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                8:  {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                9:  {field: "updatedBy", visible: access.audit},
                10: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter}
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

            bulanAwalAnggaranFld.querySelectorAll("option").forEach(elm => {
                const bln = elm.getAttribute("data");
                elm.value = vthn ? `${bln}_${thn}` : bln;
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
            widget.loadData({kode, versi}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadData({kode: editBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;

            const {noDokumen, kode} = JSON.parse(deleteBtn.value);
            const confirmMsg = str._<?= $h("Apakah Anda yakin ingin menghapus HPS Pengadaan dengan no. {{NO_DOC}}") ?>;
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
