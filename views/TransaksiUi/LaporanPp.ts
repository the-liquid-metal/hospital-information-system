<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\TransaksiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/laporanpp.php the original file
 */
final class LaporanPp
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl, string $historyPengirimanWidgetId)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.LaporanPp {
    export interface FormFields {
        dariTanggal:   string;
        sampaiTanggal: string;
        noDokumen:     string;
    }

    export interface TableFields {
        noDokumen:           string;
        noPermintaan:        string;
        noPengeluaran:       string;
        noPenerimaan:        string;
        namaDepoPeminta:     string;
        namaDepoDiminta:     string;
        status:              string;
        tanggal:             string;
        noDokumenPenerimaan: string;
        noDokumenPengiriman: string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("History Distribusi") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Dari Tanggal") ?>,
                        input: {class: ".dariTanggalFld", name: "dariTanggal"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Sampai Tanggal") ?>,
                        input: {class: ".sampaiTanggalFld", name: "sampaiTanggal"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
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
                        td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("No. Dokumen") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("No. Permintaan") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("No. Pengeluaran") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Peminta") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Pengirim") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Status") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Action") ?>},
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

        /** @type {HTMLInputElement} */ const noDokumenFld = divElm.querySelector(".noDokumenFld");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Transaksi.LaporanPp.FormFields} data */
            loadData(data) {
                dariTanggalWgt.value = data.dariTanggal ?? "";
                sampaiTanggalWgt.value = data.sampaiTanggal ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        dariTanggal: dariTanggalWgt.value,
                        sampaiTanggal: sampaiTanggalWgt.value,
                        noDokumen: noDokumenFld.value,
                    }
                });
            }
        });

        const dariTanggalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".dariTanggalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const sampaiTanggalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".sampaiTanggalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {formatter(unused, item) {
                    const {noDokumenPenerimaan, noDokumenPengiriman, noDokumen} = item;
                    return noDokumenPenerimaan || noDokumenPengiriman || noDokumen;
                }},
                3: {field: "noPermintaan"},
                4: {field: "noPengeluaran"},
                5: {field: "noPenerimaan"},
                6: {field: "namaDepoPeminta"},
                7: {field: "namaDepoDiminta"},
                8: {formatter(unused, item) {
                    const {noPengeluaran, noPenerimaan} = item;

                    // https://jsfiddle.net/theliquidmetal/7jczxtgu/latest/
                    const pengeluaran = (noPengeluaran ?? 0) == 0 ? 0 : 1;
                    const penerimaan  = (noPenerimaan ?? 0)  == 0 ? 0 : 1;
                    switch ("" + pengeluaran + penerimaan) {
                        case "11": return str._<?= $h("Barang telah dikirim dan telah diterima") ?>;
                        case "10": return str._<?= $h("Barang telah dikirim tapi belum diterima") ?>;
                        case "01": throw new Error("there is no such case");
                        case "00": return str._<?= $h("Permintaan sedang diproses") ?>;
                    }
                }},
                9:  {field: "tanggal", formatter: tlm.dateFormatter},
                10: {formatter(unused, item) {
                    const {noPenerimaan, noPengeluaran, noPermintaan, noDokumen} = item;
                    const value = noPenerimaan || noPengeluaran || noPermintaan || noDokumen;
                    return draw({class: ".detailBtn", type: "success", value, text: str._<?= $h("Detail") ?>});
                }}
            }
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const detailBtn = event.target;
            if (!detailBtn.match(".detailBtn")) return;

            const widget = tlm.app.getWidget("_<?= $historyPengirimanWidgetId ?>");
            widget.show();
            widget.loadData({val: detailBtn.value}, true);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, dariTanggalWgt, sampaiTanggalWgt, tableWgt);
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
