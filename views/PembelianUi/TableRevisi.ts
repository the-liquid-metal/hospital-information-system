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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/index_r.php the original file
 */
final class TableRevisi
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $viewWidgetId,
        string $kode,
        string $subjenisAnggaranSelect,
        string $jenisHargaSelect,
        string $tipeDokumenBulanSelect,
        string $tahunSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pembelian.IndexR {
    export interface FormFields {
        noDokumen:         "?"|"??";
        tanggalJatuhTempo: "?"|"??";
        namaPemasok:       "?"|"??";
        kodeJenis:         "?"|"??";
        bulanAwalAnggaran: "?"|"??";
        tahunAnggaran:     "?"|"??";
        jenisHarga:        "?"|"??";
        kode:              "?"|"??";
    }

    export interface TableFields {
        kodeRevPembelian:    string;
        noDokumen:           string;
        revisiKe:            string;
        tanggalJatuhTempo:   string;
        namaPemasok:         string;
        kodeJenis:           string;
        bulanAwalAnggaran:   string;
        bulanAkhirAnggaran:  string;
        tahunAnggaran:       string;
        jenisHarga:          string;
        adendumKe:           string;
        nilaiAkhir:          string;
        namaUserRevisi:      string;
        verTanggalRevisi:    string;
        keterangan:          string;
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        input: {class: ".tanggalJatuhTempoFld"}
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
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld"}
                    },
                    formGroup_7: {
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
                        td_2:  {text: tlm.stringRegistry._<?= $h("No. Dokumen") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Revisi Ke-") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Tempo Kontrak") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Anggaran") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Adendum Ke-") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("User Revisi") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Tanggal Ver.") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Keterangan") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {numToShortMonthName: nToS, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const kodeJenisFld = divElm.querySelector(".kodeJenisFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const jenisHargaFld = divElm.querySelector(".jenisHargaFld");

        tlm.app.registerSelect("_<?= $subjenisAnggaranSelect ?>", kodeJenisFld);
        tlm.app.registerSelect("_<?= $tipeDokumenBulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", jenisHargaFld);
        this._selects.push(kodeJenisFld, bulanAwalAnggaranFld, tahunAnggaranFld, jenisHargaFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Pembelian.IndexR.FormFields} data */
            loadData(data) {
                noDokumenFld.value = data.noDokumen ?? "";
                tanggalJatuhTempoWgt.value = data.tanggalJatuhTempo ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                kodeJenisFld.value = data.kodeJenis ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                jenisHargaFld.value = data.jenisHarga ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        noDokumen: noDokumenFld.value,
                        tanggalJatuhTempo: tanggalJatuhTempoWgt.value,
                        namaPemasok: namaPemasokFld.value,
                        kodeJenis: kodeJenisFld.value,
                        bulanAwalAnggaran: bulanAwalAnggaranFld.value,
                        tahunAnggaran: tahunAnggaranFld.value,
                        jenisHarga: jenisHargaFld.value,
                        kode: "<?= $kode ?>",
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kodeRevPembelian, revisiKe} = item;
                    const value = JSON.stringify({kodeRevPembelian, revisiKe});
                    return draw({class: ".viewBtn", type: "info", icon: "list", value, title: str._<?= $h("View Revisi PL Pembelian") ?>});
                }},
                2: {field: "noDokumen"},
                3: {formatter(unused, item) {
                    const {revisiKe, adendumKe} = item;
                    const revisiTxt = str._<?= $h("Revisi ke-{{REV}}") ?>.replace("{{REV}}", revisiKe);

                    // https://jsfiddle.net/theliquidmetal/7jczxtgu/latest/
                    const adendum = (adendumKe ?? 0) == 0 ? 0 : 1;
                    const revisi  = (revisiKe ?? 0)  == 0 ? 0 : 1;
                    switch ("" + adendum + revisi) { // TODO: js: uncategorized: confirm bit arrangement
                        case "11": // continue
                        case "10": return draw({class: ".btn-repair", text: str._<?= $h("PL Asli") ?>});
                        case "01": return draw({class: ".btn-repair", text: revisiTxt, title: str._<?= $h("Lihat History Revisi PL") ?>});
                        case "00": return draw({class: ".btn-repair", text: str._<?= $h("PL Asli") ?>});
                    }
                }},
                4: {field: "tanggalJatuhTempo", formatter: tlm.dateFormatter},
                5: {field: "namaPemasok"},
                6: {field: "kodeJenis"},
                7: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                8: {field: "jenisHarga"},
                9: {formatter(unused, item) {
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
                10: {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                11: {field: "namaUserRevisi"},
                12: {field: "verTanggalRevisi", formatter: tlm.dateFormatter},
                13: {field: "keterangan"}
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

            const {kodeRevPembelian, revisiKe} = JSON.parse(viewBtn.value);
            const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
            widget.show();
            widget.loadData({kode: kodeRevPembelian, revisiKe}, true);
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
