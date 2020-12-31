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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/index_r.php the original file
 */
final class TableRevisi
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $viewWidgetId,
        string $subjenisAnggaranSelect,
        string $tipeDokumenBulanSelect,
        string $tahunSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Perencanaan.IndexRevisi {
    export interface FormFields {
        noDokumen:     "?"|"??";
        noSpk:         "?"|"??";
        namaPemasok:   "?"|"??";
        kodeJenis:     "?"|"??";
        bulanAnggaran: "?"|"??";
        tahunAnggaran: "?"|"??";
    }

    // confirmed with controller
    export interface TableFields {
        kodeRevPerencanaan: string;
        noDokumen:          string;
        revisiKe:           string;
        noSpk:              string;
        namaPemasok:        string;
        kodeJenis:          string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        jenisHarga:         string;
        nilaiAkhir:         string;
        namaUserRevisi:     string;
        verTanggalRevisi:   string;
        keterangan:         string;
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
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("No. SPK") ?>,
                        input: {class: ".noSpkFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Kode Jenis") ?>,
                        select: {class: ".kodeJenisFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("BulanAnggaran") ?>,
                        select: {class: ".bulanAnggaranFld"}
                    },
                    formGroup_6: {
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
                clas: ".itemTbl",
                thead: {
                    tr: {
                        td_1:  {text: ""},
                        td_2:  {text: tlm.stringRegistry._<?= $h("No. Dokumen") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Revisi Ke-") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("PL Referensi") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Anggaran") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("User Revisi") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Tanggal Ver.") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Keterangan Revisi") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const {numToShortMonthName: nToS, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const noSpkFld = divElm.querySelector(".noSpkFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const kodeJenisFld = divElm.querySelector(".kodeJenisFld");
        /** @type {HTMLSelectElement} */ const bulanAnggaranFld = divElm.querySelector(".bulanAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");

        tlm.app.registerSelect("_<?= $subjenisAnggaranSelect ?>", kodeJenisFld);
        tlm.app.registerSelect("_<?= $tipeDokumenBulanSelect ?>", bulanAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(kodeJenisFld, bulanAnggaranFld, tahunAnggaranFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Perencanaan.IndexRevisi.FormFields} data */
            loadData(data) {
                noDokumenFld.value = data.noDokumen ?? "";
                noSpkFld.value = data.noSpk ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                kodeJenisFld.value = data.kodeJenis ?? "";
                bulanAnggaranFld.value = data.bulanAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        noDokumen: noDokumenFld.value,
                        noSpk: noSpkFld.value,
                        namaPemasok: namaPemasokFld.value,
                        kodeJenis: kodeJenisFld.value,
                        bulanAnggaran: bulanAnggaranFld.value,
                        tahunAnggaran: tahunAnggaranFld.value,
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, {kodeRevPerencanaan}) {
                    return draw({class: ".viewBtn", value: kodeRevPerencanaan, type: "info", icon: "list", title: str._<?= $h("View Revisi PL Pembelian") ?>})
                }},
                2: {field: "noDokumen"},
                3: {formatter(unused, {revisiKe}) {
                    const title1 = str._<?= $h("Tidak ada Adendum untuk PL ini") ?>;
                    const title2 = str._<?= $h("Silahkan Klik untuk melihat History Revisi PL ini") ?>;
                    const revStr = str._<?= $h("Revisi ke-{{NO}}") ?>.replace("{{NO}}", revisiKe);
                    return (revisiKe == "0")
                        ? draw({type: "primary", text: str._<?= $h("PL Asli") ?>, title: title1})
                        // missing ".btn-listrev" functionality. available on //his-ref-fatmawati-pharmacy/application/views/farmasi/penerimaan/index-r.php
                        : draw({class: ".btn-listrev", type: "primary", text: revStr, title: title2});
                }},
                4: {field: "noSpk"},
                5: {field: "namaPemasok"},
                6: {field: "kodeJenis"},
                7: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                8:  {field: "jenisHarga"},
                9:  {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                10: {field: "namaUserRevisi", formatter: val => val || str._<?= $h("Belum Verifikasi") ?>},
                11: {field: "verTanggalRevisi", formatter: tlm.dateFormatter},
                12: {field: "keterangan"}
            }
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
            widget.show();
            widget.loadData({kode: viewBtn.value}, true);
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
