<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\KonsinyasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Konsinyasi/menu.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Konsinyasi/index.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $deleteAccess,
        array  $viewDetailAccess,
        array  $printAccess,
        string $dataUrl,
        string $deleteUrl,
        string $formWidgetId,
        string $viewWidgetId,
        string $printWidgetId,
        string $formRutinWidgetId,
        string $formNonRutinWidgetId,
        string $kartuWidgetId,
        string $stokWidgetId,
        string $reportsWidgetId,
        string $subjenisAnggaranSelect,
        string $caraBayarSelect,
        string $tipeDokumenBulanSelect,
        string $tahunSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Konsinyasi.Table {
    export interface FormFields {
        proceed:          "?"|"??";
        noDokumen:        "?"|"??";
        tanggalDokumen:   "?"|"??";
        fakturSuratJalan: "?"|"??";
        namaPemasok:      "?"|"??";
        caraBayar:        "?"|"??";
        kodeJenis:        "?"|"??";
        bulanAnggaran:    "?"|"??";
        tahunAnggaran:    "?"|"??";
    }

    export interface TableFields {
        kodeKonsinyasi:     string;
        noDokumen:          string;
        tanggalDokumen:     string;
        noFaktur:           string;
        noSuratJalan:       string;
        namaPemasok:        string;
        caraBayar:          string;
        kodeJenis:          string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        namaUserInput:      string;
        sysdateInput:       string;
        verKendali:         string;
        namaUserKendali:    string;
        verTanggalKendali:  string;
        nilaiAkhir:         string;
        statusClosed:       string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean, delete: boolean, viewDetail: boolean, print: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            delete: JSON.parse(`<?=json_encode($deleteAccess) ?>`),
            viewDetail: JSON.parse(`<?=json_encode($viewDetailAccess) ?>`),
            print: JSON.parse(`<?=json_encode($printAccess) ?>`),
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
                header3: {text: tlm.stringRegistry._<?= $h("???") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Proceed") ?>,
                        input: {class: ".proceedFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Faktur/Surat Jalan") ?>,
                        input: {class: ".fakturSuratJalanFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".caraBayarFld"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Kode Jenis") ?>,
                        select: {class: ".kodeJenisFld"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Bulan Anggaran") ?>,
                        select: {class: ".bulanAnggaranFld"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld"}
                    },
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            }
        }
        row_3: {
            widthTable: {
                class: ".konsinyasiTbl",
                thead: {
                    tr: {
                        td_1:  {text: ""},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Proses Penerimaan") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("No. Transaksi") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Faktur/Surat Jalan") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Cara Bayar") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Bulan Anggaran") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("User Input") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Tanggal Input") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Ver. Kendali Harga") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("User Kendali Harga") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Tanggal Kendali Harga") ?>},
                        td_15: {text: tlm.stringRegistry._<?= $h("Nilai") ?>}
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

        /** @type {HTMLInputElement} */  const proceedFld = divElm.querySelector(".proceedFld");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const fakturSuratJalanFld = divElm.querySelector(".fakturSuratJalanFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const caraBayarFld = divElm.querySelector(".caraBayarFld");
        /** @type {HTMLSelectElement} */ const kodeJenisFld = divElm.querySelector(".kodeJenisFld");
        /** @type {HTMLSelectElement} */ const bulanAnggaranFld = divElm.querySelector(".bulanAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");

        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", caraBayarFld);
        tlm.app.registerSelect("_<?= $subjenisAnggaranSelect ?>", kodeJenisFld);
        tlm.app.registerSelect("_<?= $tipeDokumenBulanSelect ?>", bulanAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(caraBayarFld, kodeJenisFld, bulanAnggaranFld, tahunAnggaranFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Konsinyasi.Table.FormFields} data */
            loadData(data) {
                proceedFld.value = data.proceed ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                fakturSuratJalanFld.value = data.fakturSuratJalan ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                caraBayarFld.value = data.caraBayar ?? "";
                kodeJenisFld.value = data.kodeJenis ?? "";
                bulanAnggaranFld.value = data.bulanAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        proceed: proceedFld.value,
                        noDokumen: noDokumenFld.value,
                        tanggalDokumen: tanggalDokumenWgt.value,
                        fakturSuratJalan: fakturSuratJalanFld.value,
                        namaPemasok: namaPemasokFld.value,
                        caraBayar: caraBayarFld.value,
                        kodeJenis: kodeJenisFld.value,
                        bulanAnggaran: bulanAnggaranFld.value,
                        tahunAnggaran: tahunAnggaranFld.value,
                    }
                });
            }
        };

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".konsinyasiTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kodeKonsinyasi, verKendali, noDokumen} = item;
                    const deleteBtn = draw({class: ".deleteBtn", icon: "trash",  type: "danger",  value: noDokumen,                       title: str._<?= $h("Delete Penerimaan") ?>});
                    const editBtn   = draw({class: ".editBtn",   icon: "pencil", type: "primary", value: kodeKonsinyasi,                  title: str._<?= $h("Edit Penerimaan") ?>});
                    const viewBtn   = draw({class: ".viewBtn",   icon: "list",   type: "info",    value: kodeKonsinyasi,                  title: str._<?= $h("View Penerimaan") ?>});
                    const printBtn  = draw({class: ".printBtn",  icon: "list",   type: "primary", value: {kodeKonsinyasi, versi: "v-01"}, title: str._<?= $h("Cetakan Konsinyasi") ?>});

                    return "" +
                        (access.delete && verKendali != "1" ? deleteBtn : "") +
                        (access.edit && verKendali != "1" ? editBtn : "") +
                        (access.viewDetail ? viewBtn : "") +
                        (access.print ? printBtn : "");
                }},
                2: {formatter(unused, item) {
                    const {verKendali, statusClosed} = item;
                    return (verKendali == "1" && statusClosed == "0")
                        ? draw({class: ".btn-proceed", type: "warning", icon: "download", title: str._<?= $h("Process Penerimaan") ?>})
                        : "-";
                }},
                3: {field: "noDokumen"},
                4: {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                5: {formatter(unused, item) {
                    const {noFaktur, noSuratJalan} = item;
                    return (noFaktur || "-") + " / " + (noSuratJalan || "-");
                }},
                6: {field: "namaPemasok"},
                7: {field: "caraBayar"},
                8: {field: "kodeJenis"},
                9: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                10: {field: "namaUserInput"},
                11: {field: "sysdateInput", formatter: tlm.dateFormatter},
                12: {formatter(unused, {verKendali}) {
                    return verKendali == "1"
                        ? draw({class: "btn-verVis", type: "success", "dt-idcol": "0", "dt-verif": "kendali", icon: "check-square", text: str._<?= $h("Sudah") ?>})
                        : draw({class: "btn-verif",  type: "danger",                   "dt-verif": "kendali", icon: "warning",      text: str._<?= $h("Belum") ?>});
                }},
                13: {field: "namaUserKendali"},
                14: {field: "verTanggalKendali", formatter: tlm.dateFormatter},
                15: {field: "nilaiAkhir", formatter: tlm.floatFormatter}
            }
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        bulanAnggaranFld.addEventListener("change", () => {
            const bln = bulanAnggaranFld.selectedOptions[0].getAttribute("data");
            const vbln = bulanAnggaranFld.value;

            tahunAnggaranFld.querySelectorAll("option").forEach(elm => {
                const thn = elm.getAttribute("data");
                elm.value = vbln ? `${bln}_${thn}` : thn;
            });
        });

        tahunAnggaranFld.addEventListener("change", () => {
            const thn = bulanAnggaranFld.selectedOptions[0].getAttribute("data");
            const vthn = tahunAnggaranFld.value;

            bulanAnggaranFld.querySelectorAll("option").forEach(elm => {
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

            const {kodeKonsinyasi, versi} = JSON.parse(printBtn.value);
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kodeKonsinyasi, versi}, true);
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

            const confirmMsg = str._<?= $h("Apakah Anda yakin ingin menghapus perencanaan dengan no. {{NO_DOC}}") ?>;
            if (!confirm(confirmMsg.replace("{{NO_DOC}}", deleteBtn.value))) return;

            $.post({
                url: "<?= $deleteUrl ?>",
                data: {keterangan: deleteBtn.value},
                success(data) {
                    (data[1] == "1") ? closest(deleteBtn, "tr").remove() : alert(str._<?= $h("Gagal hapus Data. Silahkan hubungi tim IT.") ?>);
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

<!-- from menu.php -->
<ul class="thumbnails">
    <li><a href="<?= $formRutinWidgetId ?>">Tambah Konsinyasi</a></li>
    <li><a href="<?= $formNonRutinWidgetId ?>">Konsinyasi Non Rutin</a></li>
    <li><a href="<?= $kartuWidgetId ?>">Kartu Konsiyasi</a></li>
    <li><a href="<?= $stokWidgetId ?>">Stok Konsiyasi</a></li>
    <li><a href="<?= $reportsWidgetId ?>">Report Konsinyasi</a></li>
</ul>

<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
