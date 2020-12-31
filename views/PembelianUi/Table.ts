<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PembelianUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/index.php the original file
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
        string $viewWidgetId,
        string $revisiWidgetId,
        string $printWidgetId,
        string $formWidgetId,
        string $deleteUrl,
        string $pemesananWidgetId,
        string $addRevisiPembelianUrl,
        string $subjenisAnggaranSelect,
        string $jenisHargaSelect,
        string $tipeDokumenPembelianSelect,
        string $tipeDokumenBulanSelect,
        string $tahunSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pembelian.Table {
    export interface FormFields {
        jenisDokumen:      "?"|"??";
        statusClosed:      "?"|"??";
        tanggalJatuhTempo: "?"|"??";
        noDokumen:         "?"|"??";
        namaPemasok:       "?"|"??";
        noHps:             "?"|"??";
        kodeJenis:         "?"|"??";
        bulanAwalAnggaran: "?"|"??";
        tahunAnggaran:     "?"|"??";
        jenisHarga:        "?"|"??";
        kodeRefHps:        "?"|"??";
        kodeRefRencana:    "?"|"??";
        kodePl:            "?"|"??";
    }

    export interface TableFields {
        kode:               string;
        statusSaved:        string;
        statusLinked:       string;
        jenisDokumen:       string;
        statusClosed:       string;
        tanggalJatuhTempo:  string;
        noDokumen:          string;
        namaPemasok:        string;
        noHps:              string;
        kodeJenis:          string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        jenisHarga:         string;
        jumlahDo:           string;
        adendumKe:          string;
        revisiKe:           string;
        nilaiAkhir:         string;
        updatedBy:          string;
        updatedTime:        string;
        statusRevisi:       string;
        keterangan:         string;
        verRevisi:          string;
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
                        label: tlm.stringRegistry._<?= $h("Jenis Dokumen") ?>,
                        select: {class: ".jenisDokumenFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Status Closed") ?>,
                        select: {
                            class: ".statusClosedFld",
                            option_1: {value: "", text: ""},
                            option_2: {value: 0,  text: "Open"},
                            option_3: {value: 1,  text: "Closed"},
                        }
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        input: {class: ".tanggalJatuhTempoFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("No. HPS") ?>,
                        input: {class: ".noHpsFld"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Kode Jenis") ?>,
                        select: {class: ".kodeJenisFld"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".jenisHargaFld"}
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
                        td_2:  {text: tlm.stringRegistry._<?= $h("Tipe") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Status Kontrak") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Tempo Kontrak") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("No. PL") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("No. HPS") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Anggaran") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Jumlah DO") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Adendum ke-") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Revisi ke-") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                        td_15: {text: tlm.stringRegistry._<?= $h("Updated User") ?>},
                        td_16: {text: tlm.stringRegistry._<?= $h("Updated Time") ?>},
                        td_17: {text: tlm.stringRegistry._<?= $h("Status Revisi") ?>},
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
        /** @type {HTMLSelectElement} */ const statusClosedFld = divElm.querySelector(".statusClosedFld");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLInputElement} */  const noHpsFld = divElm.querySelector(".noHpsFld");
        /** @type {HTMLSelectElement} */ const kodeJenisFld = divElm.querySelector(".kodeJenisFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const jenisHargaFld = divElm.querySelector(".jenisHargaFld");

        tlm.app.registerSelect("_<?= $tipeDokumenPembelianSelect ?>", jenisDokumenFld);
        tlm.app.registerSelect("_<?= $subjenisAnggaranSelect ?>", kodeJenisFld);
        tlm.app.registerSelect("_<?= $tipeDokumenBulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", jenisHargaFld);
        this._selects.push(jenisDokumenFld, kodeJenisFld, bulanAwalAnggaranFld, tahunAnggaranFld, jenisHargaFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Pembelian.Table.FormFields} data */
            loadData(data) {
                jenisDokumenFld.value = data.jenisDokumen ?? "";
                statusClosedFld.value = data.statusClosed ?? "";
                tanggalJatuhTempoWgt.value = data.tanggalJatuhTempo ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                noHpsFld.value = data.noHps ?? "";
                kodeJenisFld.value = data.kodeJenis ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                jenisHargaFld.value = data.jenisHarga ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        jenisDokumen: jenisDokumenFld.value,
                        statusClosed: statusClosedFld.value,
                        tanggalJatuhTempo: tanggalJatuhTempoWgt.value,
                        noDokumen: noDokumenFld.value,
                        namaPemasok: namaPemasokFld.value,
                        noHps: noHpsFld.value,
                        kodeJenis: kodeJenisFld.value,
                        bulanAwalAnggaran: bulanAwalAnggaranFld.value,
                        tahunAnggaran: tahunAnggaranFld.value,
                        jenisHarga: jenisHargaFld.value,
                        kodeRefHps: "<?= $kodeReffHps ?? '' ?>",
                        kodeRefRencana: "<?= $kodeReffRencana ?? '' ?>",
                        kodePl: "<?= $kodepl ?? '' ?>",
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {statusLinked, statusSaved, kode, noDokumen} = item;
                    const buttonCls = statusSaved == "1" ? "warning" : "primary";

                    const deleteBtn   = draw({class: ".deleteBtn", value: JSON.stringify({kode, noDokumen}),     type: "danger",  icon: "trash",  title: str._<?= $h("Delete PL Pembelian") ?>});
                    const editBtn     = draw({class: ".editBtn",   value: kode,                                  type: buttonCls, icon: "pencil", title: str._<?= $h("Edit PL Pembelian") ?>});
                    const revisiBtn   = draw({class: ".revisiBtn", value: JSON.stringify({kode, noDokumen}),     type: "info",    text: "R",      title: str._<?= $h("Revisi PL Pembelian") ?>});
                    const viewBtn     = draw({class: ".viewBtn",   value: kode,                                  type: "info",    icon: "list",   title: str._<?= $h("View PL Pembelian") ?>});
                    const printSpBtn  = draw({class: ".printBtn",  value: JSON.stringify({kode, versi: "sp"}),   type: "info",    icon: "list",   title: str._<?= $h("Cetakan SP Pembelian") ?>});
                    const printSpkBtn = draw({class: ".printBtn",  value: JSON.stringify({kode, versi: "spk"}),  type: "info",    icon: "list",   title: str._<?= $h("Cetakan SPK/Kontrak") ?>});
                    const printHpsBtn = draw({class: ".printBtn",  value: JSON.stringify({kode, versi: "v-00"}), type: "info",    icon: "list",   title: str._<?= $h("Cetakan HPS Pengadaan") ?>});

                    return "" +
                        (statusLinked == "1" ? "" : deleteBtn) +
                        (statusLinked == "1" ? "" : editBtn) +
                        (statusLinked == "0" ? "" : revisiBtn) +
                        viewBtn + printSpBtn + printSpkBtn + printHpsBtn;
                }},
                2: {field: "jenisDokumen"},
                3: {field: "", formatter(unused, {statusClosed}) {
                    return statusClosed
                        ? draw({type: "danger", text: str._<?= $h("Closed") ?>, title: str._<?= $h("PL telah melewati masa kontrak atau Seluruh Item Barang sudah diterima") ?>})
                        : draw({type: "info",   text: str._<?= $h("Open") ?>,   title: str._<?= $h("PL masih aktif") ?>});
                }},
                4: {field: "tanggalJatuhTempo", formatter: tlm.dateFormatter},
                5: {field: "noDokumen"},
                6: {field: "namaPemasok"},
                7: {field: "noHps"},
                8: {field: "kodeJenis"},
                9: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                10: {field: "jenisHarga"},
                11: {formatter(unused, item) {
                    const {kode, jumlahDo} = item;
                    const text = jumlahDo ? str._<?= $h("{{SUM}} DO") ?>.replace("{{SUM}}", jumlahDo) : str._<?= $h("0 DO") ?>;
                    const title = jumlahDo ? str._<?= $h("Lihat DO") ?> : str._<?= $h("Tidak ada DO") ?>;
                    return draw({class: ".orderBtn", value: kode, text, title});
                }},
                12: {formatter(unused, item) {
                    const {adendumKe, revisiKe} = item;
                    const adendumTxt = str._<?= $h("Adendum ke-{{ADN}}") ?>.replace("{{ADN}}", adendumKe);

                    // https://jsfiddle.net/theliquidmetal/7jczxtgu/latest/
                    const adendum = (adendumKe ?? 0) == 0 ? 0 : 1;
                    const revisi  = (revisiKe ?? 0)  == 0 ? 0 : 1;
                    switch ("" + adendum + revisi) { // TODO: js: uncategorized: confirm bit arrangement
                        case "11": // continue
                        case "10": return draw({class: ".btn-addend .btn-listadd", text: adendumTxt, title: str._<?= $h("Lihat History Adendum PL") ?>});
                        case "01": return draw({class: ".btn-addend",              text: str._<?= $h("PL Asli") ?>});
                        case "00": return draw({class: ".btn-addend",              text: str._<?= $h("PL Asli") ?>});
                    }
                }},
                13: {formatter(unused, item) {
                    const {revisiKe, adendumKe, keterangan} = item;
                    const revisiTxt = str._<?= $h("Revisi ke-{{REV}}") ?>.replace("{{REV}}", revisiKe);

                    // https://jsfiddle.net/theliquidmetal/7jczxtgu/latest/
                    const adendum = (adendumKe ?? 0) == 0 ? 0 : 1;
                    const revisi  = (revisiKe ?? 0)  == 0 ? 0 : 1;
                    switch ("" + adendum + revisi) { // TODO: js: uncategorized: confirm bit arrangement
                        case "11": // continue
                        case "10": return draw({class: ".btn-repair",             text: str._<?= $h("PL Asli") ?>});
                        case "01": return draw({class: ".btn-repair .btn-revisi", text: revisiTxt, title: keterangan});
                        case "00": return draw({class: ".btn-repair",             text: str._<?= $h("PL Asli") ?>});
                    }
                }},
                14: {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                15: {field: "updatedBy", visible: access.audit},
                16: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter},
                17: {formatter(unused, item) {
                    const {statusRevisi, verRevisi} = item;
                    switch ("" + statusRevisi + verRevisi) { // TODO: js: uncategorized: confirm bit arrangement
                        case "11": return "";
                        case "10": return draw({type: "danger", text: str._<?= $h("Belum") ?>});
                        case "01": return draw({type: "info",   text: str._<?= $h("Sudah") ?>});
                        case "00": return "";
                    }
                }}
            }
        });

        const tanggalJatuhTempoWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalJatuhTempoFld"),
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
            const revisiBtn = event.target;
            if (!revisiBtn.matches(".revisiBtn")) return;

            const {kode, noDokumen} = JSON.parse(revisiBtn.value);
            const actionUrl = "<?= $addRevisiPembelianUrl ?>";

            divElm.querySelector(".modal-body").innerHTML = `
                <form name="form-revisi" method="POST" action="${actionUrl}">
                    <h4>Apakah Anda yakin ingin Merevisi untuk PL dengan no. "${noDokumen}"?</h4>
                    <p>Jika Ya, Silahkan isi Kolom Catatan atau Keterangan dan Jenis Revisi dibawah ini.</p>
                    <input type="hidden" id="idrow_rev" name="kode" value="${kode}"/>
                    <input type="hidden" name="action" value="open"/>
                    <textarea id="keterangan_rev" name="keterangan"></textarea>
                    <input type="radio" name="jenis" value="dokumen" checked /> Dokumen
                    <input type="radio" name="jenis" value="jumlah"/> Jumlah
                    <input type="radio" name="jenis" value="nilai"/> Harga/Diskon/PPN/Kemasan
                    <input type="radio" name="jenis" value="katalog"/> Ganti Katalog

                    <button name="submit" value="revisi">Open Revisi</button>
                </form>`;
            divElm.querySelector(".modal-header").innerHTML = `<h5 style="color:#FFF">Revisi SP/SPK/Kontrak Pembelian</h5>`;
            divElm.querySelector(".modal-footer").querySelector("#btn-pull").style.display = "none";

            divElm.querySelector("#modal-modul").addEventListener("click", (event) => {
                if (!event.target.matches("#btn-close")) return;
                divElm.querySelector(".modal-body").innerHTML = "";
                divElm.querySelector(".modal-header").innerHTML = "";
                divElm.querySelector("body").classList.remove("modal-open");
                divElm.querySelector(".modal").style.display = "none";
            });
            divElm.querySelector("body").classList.add("modal-open");
            divElm.querySelector(".modal").style.display = "block";
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const {kode, versi} = JSON.parse(printBtn.value);
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode, tipeDokumen: versi}, true);
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

            const {kode, noDokumen} = JSON.parse(deleteBtn.value);
            const confirmMsg = str._<?= $h("Apakah Anda yakin ingin menghapus pembelian dengan no. {{NO_DOC}}") ?>;
            if (!confirm(confirmMsg.replace("{{NO_DOC}}", noDokumen))) return;

            $.post({
                url: "<?= $deleteUrl ?>",
                data: {kode, keterangan: noDokumen},
                success(data) {
                    data ? closest(deleteBtn, "tr").remove() : alert(str._<?= $h("Hapus Data gagal.") ?>);
                }
            });
        });

        // JUNK -----------------------------------------------------

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const orderBtn = event.target;
            if (!orderBtn.matches(".orderBtn")) return;

            const widget = tlm.app.getWidget("_<?= $pemesananWidgetId ?>");
            widget.show();
            widget.loadData({kode: orderBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const revisiBtn = event.target;
            if (!revisiBtn.matches(".btn-revisi")) return;

            const widget = tlm.app.getWidget("_<?= $revisiWidgetId ?>");
            widget.show();
            widget.loadData({xyz: revisiBtn.value}, true); // TODO: js: uncategorized: finish this
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
