<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PemesananUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pemesanan/index.php the original file
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
        string $formRevisiWidgetId,
        string $subjenisAnggaranSelect,
        string $tipeDokumenBulanSelect,
        string $tahunSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pemesanan.Table {
    export interface FormFields {
        statusClosed:      "?"|"??";
        tanggalTempoKirim: "?"|"??";
        noDokumen:         "?"|"??";
        noRencana:         "?"|"??";
        noSpk:             "?"|"??";
        namaPemasok:       "?"|"??";
        kodeJenis:         "?"|"??";
        bulanAwalAnggaran: "?"|"??";
        tahunAnggaran:     "?"|"??";
        kode:              "?"|"??";
    }

    export interface TableFields {
        kode:               string;
        statusLinked:       string;
        statusRevisi:       string;
        statusClosed:       string;
        tanggalTempoKirim:  string;
        noDokumen:          string;
        noRencana:          string;
        noSpk:              string;
        namaPemasok:        string;
        kodeJenis:          string;
        bulanAwalAnggaran:  string;
        revisiKe:           string;
        verRevisiRo:        string;
        nilaiAkhir:         string;
        updatedBy:          string;
        updatedTime:        string;
        tahunAnggaran:      string;
        bulanAkhirAnggaran: string;
        statusRevisiRo:     string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar SPK Pembelian") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Status Closed") ?>,
                        select: {
                            class: ".statusClosedFld",
                            option_1: {value: "", text: ""},
                            option_2: {value: 0,  text: "Open"},
                            option_3: {value: 1,  text: "Closed"},
                        }
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Tempo Kirim") ?>,
                        input: {class: ".tanggalTempoKirimFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Rencana") ?>,
                        input: {class: ".noRencanaFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. SPK") ?>,
                        input: {class: ".noSpkFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Kode Jenis") ?>,
                        select: {class: ".kodeJenisFld"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld"},
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld"},
                    },
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
                        td_2:  {text: tlm.stringRegistry._<?= $h("Status DO") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Tempo Kirim") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("No. Pesanan Pembelian") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("No. Perencanaan") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("No. PL Pembelian") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Anggaran") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Revisi Ke-") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Status Revisi") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Updated User") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Updated") ?>},
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

        /** @type {HTMLSelectElement} */ const statusClosedFld = divElm.querySelector(".statusClosedFld");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const noRencanaFld = divElm.querySelector(".noRencanaFld");
        /** @type {HTMLInputElement} */  const noSpkFld = divElm.querySelector(".noSpkFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const kodeJenisFld = divElm.querySelector(".kodeJenisFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");

        tlm.app.registerSelect("_<?= $subjenisAnggaranSelect ?>", kodeJenisFld);
        tlm.app.registerSelect("_<?= $tipeDokumenBulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(kodeJenisFld, bulanAwalAnggaranFld, tahunAnggaranFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Pemesanan.Table.FormFields} data */
            loadData(data) {
                statusClosedFld.value = data.statusClosed ?? "";
                tanggalTempoKirimWgt.value = data.tanggalTempoKirim ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                noRencanaFld.value = data.noRencana ?? "";
                noSpkFld.value = data.noSpk ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                kodeJenisFld.value = data.kodeJenis ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        statusClosed: statusClosedFld.value,
                        tanggalTempoKirim: tanggalTempoKirimWgt.value,
                        noDokumen: noDokumenFld.value,
                        noRencana: noRencanaFld.value,
                        noSpk: noSpkFld.value,
                        namaPemasok: namaPemasokFld.value,
                        kodeJenis: kodeJenisFld.value,
                        bulanAwalAnggaran: bulanAwalAnggaranFld.value,
                        tahunAnggaran: tahunAnggaranFld.value,
                        kode: "<?= $kode ?? '' ?>", // TODO: js: uncategorized: refactor this
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {statusLinked, statusRevisi, kode, noDokumen, verRevisiRo} = item;
                    const deleteBtn        = draw({class: ".deleteBtn",        value: JSON.stringify({kode, noDokumen}),     type: "danger",  icon: "trash",  title: str._<?= $h("Delete DO Pemesanan") ?>});
                    const editBtn          = draw({class: ".editBtn",          value: kode,                                  type: "primary", icon: "pencil", title: str._<?= $h("Edit DO Pemesanan") ?>});
                    const revisiSpBtn      = draw({class: ".revisiSpBtn",      value: JSON.stringify({kode, verRevisiRo}),   type: "primary", icon: "pencil", title: str._<?= $h("Revisi SP/SPK/Kontrak") ?>});
                    const revisiJumlahBtn  = draw({class: ".revisiJumlahBtn",  value: JSON.stringify({kode, statusLinked}),  type: "primary", icon: "pencil", title: str._<?= $h("Revisi Jumlah") ?>});
                    const revisiDokumenBtn = draw({class: ".revisiDokumenBtn", value: JSON.stringify({kode, statusLinked}),  type: "primary", icon: "pencil", title: str._<?= $h("Revisi No. Dokumen/Tempo DO") ?>});
                    const viewDoBtn        = draw({class: ".infoBtn",          value: kode,                                  type: "primary", icon: "list",   title: str._<?= $h("View DO Pemesanan") ?>});
                    const printDoBtn       = draw({class: ".printBtn",         value: kode,                                  type: "primary", icon: "print",  title: str._<?= $h("Cetakan DO Pemesanan") ?>});

                    return "" +
                        (statusLinked == "1" ? "" : deleteBtn) +
                        (statusLinked == "1" ? "" : editBtn) +
                        ((statusLinked == "1" && statusRevisi == "0") || statusLinked != "1" ? "" : revisiSpBtn) +
                        (statusLinked == "1" ? revisiJumlahBtn : "") +
                        (statusLinked == '1' ? revisiDokumenBtn : "") +
                        viewDoBtn + printDoBtn;
                }},
                2: {formatter(unused, {statusClosed}) {
                    return statusClosed
                        ? draw({type: "danger", text: str._<?= $h("Closed") ?>, title: str._<?= $h("PL telah melewati masa kontrak atau Seluruh Item Barang sudah diterima") ?>})
                        : draw({type: "info",   text: str._<?= $h("Open") ?>,   title: str._<?= $h("PL masih aktif") ?>});
                }},
                3: {field: "tanggalTempoKirim", formatter: tlm.dateFormatter},
                4: {field: "noDokumen"},
                5: {field: "noRencana"},
                6: {field: "noSpk"},
                7: {field: "namaPemasok"},
                8: {field: "kodeJenis"},
                9: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                10: {formatter(unused, item) {
                    const {revisiKe, kode: value} = item;
                    const revisiTxt = str._<?= $h("Revisi ke-{{REV}}") ?>.replace("{{REV}}", revisiKe);
                    return val
                        ? draw({class: ".btn-repair .btn-listrev", value, text: revisiTxt,                 title: str._<?= $h("Lihat History Revisi PL") ?>})
                        : draw({class: ".btn-repair",              value, text: str._<?= $h("PL Asli") ?>, title: str._<?= $h("Ini adalah PL Asli") ?>});
                }},
                11: {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                12: {formatter(unused, item) {
                    const {statusRevisi, statusRevisiPl, statusRevisiRo, verRevisiRo} = item;
                    if      (statusRevisi == "1" && statusRevisiPl == "1" /*-----------------*/) return draw({type: "danger",  text: str._<?= $h("Ada Revisi PL") ?>});
                    else if (statusRevisi == "1" && statusRevisiRo == "1" /*-----------------*/) return draw({type: "danger",  text: str._<?= $h("Ada Revisi RO") ?>});
                    else if (statusRevisi == "1" && statusRevisiRo == "0" && verRevisiRo == "1") return draw({type: "warning", text: str._<?= $h("Lakukan Revisi PO/DO") ?>});
                    else    /*----------------------------------------------------------------*/ return "-";
                }},
                13: {field: "updatedBy", visible: access.audit},
                14: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter}
            }
        });

        const tanggalTempoKirimWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalTempoKirimFld"),
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
            const infoBtn = event.target;
            if (!infoBtn.matches(".infoBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
            widget.show();
            widget.loadData({kode: infoBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode: printBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadData({kode: editBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const revisiSpBtn = event.target;
            if (!revisiSpBtn.matches(".revisiSpBtn")) return;

            const {kode, verRevisiRo} = JSON.parse(revisiSpBtn.value);
            if (verRevisiRo == "0") {
                alert(str._<?= $h('Revisi "Perencanaan Repeate Order" Tersebut belum diverifikasi. Hubungi Farmasi untuk melakukan verifikasi Revisi!') ?>);
            } else {
                const widget = tlm.app.getWidget("_<?= $formRevisiWidgetId ?>");
                widget.show();
                widget.loadData({kode, tipe: "revisi_pl"}, true);
            }
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const revisiJumlahBtn = event.target;
            if (!revisiJumlahBtn.matches(".revisiJumlahBtn")) return;

            const {kode, statusLinked} = JSON.parse(revisiJumlahBtn.value);
            if (statusLinked == "0") {
                alert(str._<?= $h('Revisi "DO/PO Pemesanan" hanya bisa dilakukan jika data sudah ditarik Penerimaan!') ?>);
            } else {
                const widget = tlm.app.getWidget("_<?= $formRevisiWidgetId ?>");
                widget.show();
                widget.loadData({kode, tipe: "revisi_jumlah"}, true);
            }
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const revisiDokumenBtn = event.target;
            if (!revisiDokumenBtn.matches(".revisiDokumenBtn")) return;

            const {kode, statusLinked} = JSON.parse(revisiDokumenBtn.value);
            if (statusLinked == "0") {
                alert(str._<?= $h('Revisi "DO/PO Pemesanan" hanya bisa dilakukan jika data sudah ditarik Penerimaan!') ?>);
            } else {
                const widget = tlm.app.getWidget("_<?= $formRevisiWidgetId ?>");
                widget.show();
                widget.loadData({kode, tipe: "revisi_dokumen"}, true);
            }
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
                    data ? closest(deleteBtn, "tr").remove() : alert(str._<?= $h("Gagal hapus Data.") ?>);
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
