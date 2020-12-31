<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPembelianUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/pembelian/reports.php the original file
 */
final class FormLaporanAkhir
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $pembelianAcplUrl, string $jenisAnggaranSelect)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPembelianUi.FormLaporanAkhir {
    export interface FormFields {
        id:              "id", // NOT USED by controller
        kode:            string;
        idJenisAnggaran: string;
        tanggalMulai:    string;
        tanggalAkhir:    string;
        idJenisHarga:    "id_jenisharga", // NOT USED by controller
        idSubsumberDana: "id_subsumberdana", // NOT USED by controller
        format:          "format", // NOT USED by controller
    }

    export interface KodeFields {
        kode:               string;
        idPemasok:          string;
        namaPemasok:        string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        tipeDokumen:        string;
        idJenisHarga:       string;
        noDokumen:          string;
        subjenisAnggaran:   string;
    }
}
</script>

<!--suppress NestedConditionalExpressionJS -->
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
            class: ".laporanAkhirFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden: {class: ".idFld", name: "id"}, // NOT USED by controller
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. PL") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {name: "MISSING_NAME"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal Kontrak") ?>,
                        input: {class: ".tanggalMulaiFld", name: "tanggalMulai"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir Kontrak") ?>,
                        input: {class: ".tanggalAkhirFld", name: "tanggalAkhir"}
                    },
                    formGroup_6: { // NOT USED by controller
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {
                            class: ".idJenisHargaFld",
                            name: "idJenisHarga",
                            option_1: {value: "E-Katalog & Non E-Katalog", label: tlm.stringRegistry._<?= $h("E-Katalog & Non E-Katalog") ?>},
                            option_2: {value: "E-Katalog",                 label: tlm.stringRegistry._<?= $h("E-Katalog") ?>},
                            option_3: {value: "Non E-Katalog",             label: tlm.stringRegistry._<?= $h("Non E-Katalog") ?>}
                        }
                    },
                    formGroup_7: { // NOT USED by controller
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {
                            class: "idSubsumberDanaFld",
                            name: "idSubsumberDana",
                            option_1: {value: "Dipa APBN & Dipa PNBP", label: tlm.stringRegistry._<?= $h("Dipa APBN & Dipa PNBP") ?>},
                            option_2: {value: "Dipa APBN",             label: tlm.stringRegistry._<?= $h("Dipa APBN") ?>},
                            option_3: {value: "Dipa PNBP",             label: tlm.stringRegistry._<?= $h("Dipa PNBP") ?>}
                        }
                    },
                    formGroup_8: { // NOT USED by controller
                        label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                        radio_1: {class: ".formatFld", name: "format", label: tlm.stringRegistry._<?= $h("PL") ?>,             value: "Laporan Akhir PL"},
                        radio_2: {class: ".formatFld", name: "format", label: tlm.stringRegistry._<?= $h("Kontrak/SPK/SP") ?>, value: "Laporan Kontrak/SPK/SP"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Generate") ?>}
                }
            }
        },
        row_3: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_4: {
            widthColumn: {class: ".printTargetElm"}
        }
    };

    constructor(divElm) {
        super();
        const {numToShortMonthName: nToS, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const idFld = divElm.querySelector(".idFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idSubsumberDanaFld = divElm.querySelector(".idSubsumberDanaFld");
        /** @type {HTMLDivElement} */    const printTargetElm = divElm.querySelector(".printTargetElm");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        this._selects.push(idJenisAnggaranFld);

        const laporanAkhirWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanAkhirFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormLaporanAkhir.FormFields} data */
            loadData(data) {
                idFld.value = data.id ?? "";
                kodeWgt.value = data.kode ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                tanggalMulaiWgt.value = data.tanggalMulai ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idSubsumberDanaFld.value = data.idSubsumberDana ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                printTargetElm.innerHTML = event.data;
            },
            onFailSubmit() {
                printTargetElm.innerHTML = str._<?= $h("terjadi error") ?>;
            }
        });

        const tanggalMulaiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalMulaiFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalAkhirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAkhirFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const kodeWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeFld"),
            maxItems: 100,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormLaporanAkhir.KodeFields} item */
            optionRenderer(item) {
                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                const {tipeDokumen, idJenisHarga, noDokumen, namaPemasok, subjenisAnggaran} = item;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                const jenisPl =
                    (tipeDokumen == "0" /*--------------------*/) ? str._<?= $h("Kontrak Harga") ?>
                    : (tipeDokumen == "1" && idJenisHarga == "2") ? str._<?= $h("Kontrak E-Katalog") ?>
                    : (tipeDokumen == "1" /*------------------*/) ? str._<?= $h("Kontrak") ?>
                    : (tipeDokumen == "2" /*------------------*/) ? str._<?= $h("Surat Perintah Kerja") ?>
                    : /*---------------------------------------*/   str._<?= $h("Surat Pesanan") ?>;

                return `
                    <div class="option">
                        <span class="name">${jenisPl} (${noDokumen})</span><br/>
                        <span class="description">
                            Pemasok: ${namaPemasok}<br/>
                            Mata Anggaran: ${subjenisAnggaran}<br/>
                            Bulan Anggaran: ${anggaran}
                        </span>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormLaporanAkhir.KodeFields} item */
            itemRenderer(item) {
                const {tipeDokumen, idJenisHarga, noDokumen} = item;
                const jenisPl =
                    (tipeDokumen == "0" /*--------------------*/) ? str._<?= $h("Kontrak Harga") ?>
                    : (tipeDokumen == "1" && idJenisHarga == "2") ? str._<?= $h("Kontrak E-Katalog") ?>
                    : (tipeDokumen == "1" /*------------------*/) ? str._<?= $h("Kontrak") ?>
                    : (tipeDokumen == "2" /*------------------*/) ? str._<?= $h("Surat Perintah Kerja") ?>
                    : /*---------------------------------------*/   str._<?= $h("Surat Pesanan") ?>;

                return `<div class="item">${jenisPl} (${noDokumen})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pembelianAcplUrl ?>",
                    data: {noDokumen: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(laporanAkhirWgt, tanggalMulaiWgt, tanggalAkhirWgt, kodeWgt);
        tlm.app.registerWidget(this.constructor.widgetName, laporanAkhirWgt);
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
