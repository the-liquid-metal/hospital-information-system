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
final class FormRealisasiBarang
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $pembelianAcplUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPembelianUi.FormRealisasiBarang {
    export interface FormFields {
        kode:   "kode";
        format: "format";
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
            class: ".realisasiBarangFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. PL") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Format Laporan") ?>,
                        radio_1: {class: ".formatIndexOpt", name: "format", value: "index-realisasi-pl", label: tlm.stringRegistry._<?= $h("Barang") ?>},
                        radio_2: {class: ".formatPrintOpt", name: "format", value: "print-realisasi-pl", label: tlm.stringRegistry._<?= $h("Barang per PL") ?>}
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
            widthColumn: {class: ".printTargetElm  tlm-print-area1"}
        }
    };

    constructor(divElm) {
        super();
        const {numToShortMonthName: nToS, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */ const formatIndexOpt = divElm.querySelector(".formatIndexOpt");
        /** @type {HTMLInputElement} */ const formatPrintOpt = divElm.querySelector(".formatPrintOpt");
        /** @type {HTMLDivElement} */   const printTargetElm = divElm.querySelector(".printTargetElm");

        const realisasiBarangWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".realisasiBarangFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormRealisasiBarang.FormFields} data */
            loadData(data) {
                kodeWgt.value = data.kode ?? "";
                data.format == "index-realisasi-pl" ? formatIndexOpt.checked = true : formatPrintOpt.checked = true;
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

        const kodeWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeFld"),
            maxItems: 100,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormRealisasiBarang.KodeFields} item */
            optionRenderer(item) {
                const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                const {tipeDokumen, idJenisHarga, noDokumen, namaPemasok, subjenisAnggaran} = item;
                const anggaran = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                const jenisPl =
                    (tipeDokumen == "0" /*------------------  */) ? str._<?= $h("Kontrak Harga") ?>
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
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormRealisasiBarang.KodeFields} item */
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
        this._widgets.push(realisasiBarangWgt, kodeWgt);
        tlm.app.registerWidget(this.constructor.widgetName, realisasiBarangWgt);
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
