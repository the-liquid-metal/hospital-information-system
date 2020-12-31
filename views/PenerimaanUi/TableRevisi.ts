<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PenerimaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/index_r.php the original file
 */
final class TableRevisi
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $viewWidgetId,
        string $viewLainnyaWidgetId,
        string $printWidgetId,
        string $subjenisAnggaranSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Penerimaan.TableRevisi {
    export interface FormFields {
        revisiKe:         "?"|"??";
        noDokumenBaru:    "?"|"??";
        noDokumenLama:    "?"|"??";
        tanggalDokumen:   "?"|"??";
        fakturSuratJalan: "?"|"??";
        noRefDoPl:        "?"|"??";
        kodeJenis:        "?"|"??";
    }
    export interface TableFields {
        revisiKe:       string;
        noDokumenBaru:  string;
        noDokumenLama:  string;
        tanggalDokumen: string;
        noFaktur:       string;
        noSuratJalan:   string;
        noRefDoPl:      string;
        namaPemasok:    string;
        kodeJenis:      string;
        nilaiAkhir:     string;
        keterangan:     string;

        // TODO: fix controller:
        tipeDokumen:    "???";
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
                        label: tlm.stringRegistry._<?= $h("Revisi Ke-") ?>,
                        input: {class: ".revisiKeFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen Baru") ?>,
                        input: {class: ".noDokumenBaruFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen Lama") ?>,
                        input: {class: ".noDokumenLamaFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Faktur/Surat Jalan") ?>,
                        input: {class: ".fakturSuratJalanFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("No. Ref. DO/PL") ?>,
                        input: {class: ".noRefDoPlFld"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Kode Jenis") ?>,
                        select: {class: ".kodeJenisFld"}
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
                        td_2:  {text: tlm.stringRegistry._<?= $h("Revisi") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("No. Terima Baru") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("No. Terima Lama") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Tanggal Terima") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Faktur/Surat Jalan") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Ref DO/PL") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Keterangan Revisi") ?>},
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

        /** @type {HTMLInputElement} */  const revisiKeFld = divElm.querySelector(".revisiKeFld");
        /** @type {HTMLInputElement} */  const noDokumenBaruFld = divElm.querySelector(".noDokumenBaruFld");
        /** @type {HTMLInputElement} */  const noDokumenLamaFld = divElm.querySelector(".noDokumenLamaFld");
        /** @type {HTMLInputElement} */  const fakturSuratJalanFld = divElm.querySelector(".fakturSuratJalanFld");
        /** @type {HTMLInputElement} */  const noRefDoPlFld = divElm.querySelector(".noRefDoPlFld");
        /** @type {HTMLSelectElement} */ const kodeJenisFld = divElm.querySelector(".kodeJenisFld");

        tlm.app.registerSelect("_<?= $subjenisAnggaranSelect ?>", kodeJenisFld);
        this._selects.push(kodeJenisFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Penerimaan.TableRevisi.FormFields} data */
            loadData(data) {
                revisiKeFld.value = data.revisiKe ?? "";
                noDokumenBaruFld.value = data.noDokumenBaru ?? "";
                noDokumenLamaFld.value = data.noDokumenLama ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                fakturSuratJalanFld.value = data.fakturSuratJalan ?? "";
                noRefDoPlFld.value = data.noRefDoPl ?? "";
                kodeJenisFld.value = data.kodeJenis ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        revisiKe: revisiKeFld.value,
                        noDokumenBaru: noDokumenBaruFld.value,
                        noDokumenLama: noDokumenLamaFld.value,
                        tanggalDokumen: tanggalDokumenWgt.value,
                        fakturSuratJalan: fakturSuratJalanFld.value,
                        noRefDoPl: noRefDoPlFld.value,
                        kodeJenis: kodeJenisFld.value,
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kode, revisiKe, tipeDokumen} = item;
                    return "" +
                        draw({class: ".viewBtn",  type: "info",    icon: "list", value: JSON.stringify({kode, revisiKe, tipeDokumen}), title: str._<?= $h("View Penerimaan") ?>}) +
                        draw({class: ".printBtn", type: "primary", text: "P1",   value: JSON.stringify({kode, versi: 1}), title: str._<?= $h("Cetak BA Penerimaan Barang Medik") ?>}) +
                        draw({class: ".printBtn", type: "primary", text: "P2",   value: JSON.stringify({kode, versi: 2}), title: str._<?= $h("Cetak BA Penyerahan Barang Medik") ?>}) +
                        draw({class: ".printBtn", type: "primary", text: "P3",   value: JSON.stringify({kode, versi: 3}), title: str._<?= $h("Cetak Bukti Penerimaan") ?>}) +
                        draw({class: ".printBtn", type: "primary", text: "P4",   value: JSON.stringify({kode, versi: 4}), title: str._<?= $h("Cetak Bukti Penyerahan") ?>}) +
                        draw({class: ".printBtn", type: "primary", text: "P5",   value: JSON.stringify({kode, versi: 5}), title: str._<?= $h("Cetak Bukti Penyerahan (Kemasan Besar)") ?>}) +
                        draw({class: ".printBtn", type: "primary", text: "P6",   value: JSON.stringify({kode, versi: 6}), title: str._<?= $h("Cetak BA Penyerahan Tabung Gas Medis") ?>});
                }},
                2: {formatter(unused, {revisiKe}) {
                    return revisiKe == "0"
                        ? draw({class: ".btn-repair", text: str._<?= $h("PL Asli") ?>})
                        : draw({class: ".btn-repair", text: str._<?= $h("Revisi ke-{{REV}}") ?>.replace("{{REV}}", revisiKe)});
                }},
                3: {field: "noDokumenBaru"},
                4: {field: "noDokumenLama"},
                5: {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                6: {formatter(unused, item) {
                    const {noFaktur, noSuratJalan} = item;
                    return (noFaktur || "-") + " / " + (noSuratJalan || "-");
                }},
                7:  {field: "noRefDoPl"},
                8:  {field: "namaPemasok"},
                9:  {field: "kodeJenis"},
                10: {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                11: {field: "keterangan"}
            }
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const {kode, revisiKe, tipeDokumen} = JSON.parse(viewBtn.value);
            const widgetId = tipeDokumen ? "_<?= $viewLainnyaWidgetId ?>" : "_<?= $viewWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode, revisiKe}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const {kode, versi} = JSON.parse(printBtn.value);
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode, versi}, true);
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
