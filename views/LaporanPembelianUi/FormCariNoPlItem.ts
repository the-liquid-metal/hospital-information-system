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
final class FormCariNoPlItem
{
    private string $output;

    public function __construct(string $registerId, string $actionUrl, string $katalogAcplUrl, string $plItemSrcUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPembelianUi.FormCariNoPlItem {
    export interface FormFields {
        namaSediaan: "nama_sediaan",
        idKatalog:   "id_katalog",
    }

    export interface KatalogFields {
        isiKemasan:    string;
        idKemasan:     string;
        idKemasanDepo: string;
        satuan:        string;
        satuanJual:    string;
        idKatalog:     string;
        namaSediaan:   string;
        namaPabrik:    string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
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
                    hidden: {class: ".namaSediaanFld", name: "namaSediaan"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Nama Barang") ?>,
                        input: {class: ".idKatalogFld", name: "idKatalog"}
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
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_4: {
            widthTable: {class: ".itemTbl"}
        },
        row_5: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_6: {
            widthColumn: {class: ".printAreaBlk"}
        }
    };

    constructor(divElm) {
        super();
        const {preferInt, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLTableElement} */ const itemTbl = divElm.querySelector(".itemTbl");
        /** @type {HTMLDivElement} */   const printAreaBlk = divElm.querySelector(".printAreaBlk");
        /** @type {HTMLInputElement} */ const namaSediaanFld = divElm.querySelector(".namaSediaanFld");

        const saringWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormCariNoPlItem.FormFields} data */
            loadData(data) {
                namaSediaanFld.value = data.namaSediaan ?? "";
                idKatalogWgt.value = data.idKatalog ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                itemTbl.innerHTML = event.data;
            },
            onFailSubmit() {
                itemTbl.innerHTML = str._<?= $h("terjadi error") ?>;
            }
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["namaSediaan", "idKatalog"],
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormCariNoPlItem.KatalogFields} data
             */
            assignPairs(formElm, data) {
                namaSediaanFld.value = data.namaSediaan ?? "";
            },
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormCariNoPlItem.KatalogFields} item */
            optionRenderer(item) {
                const {isiKemasan: isi, satuan, idKemasan, idKemasanDepo, satuanJual} = item;
                const kemasan = ((isi != 1 || idKemasan != idKemasanDepo) ? satuanJual + " " + preferInt(isi) : "") + " " + satuan;

                return `
                    <div class="option  col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.idKatalog}</b></div>
                        <div class="col-xs-5"><b>${item.namaSediaan}</b></div>
                        <div class="col-xs-3">${item.namaPabrik}</div>
                        <div class="col-xs-2">${kemasan}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.LaporanPembelianUi.FormCariNoPlItem.KatalogFields} item */
            itemRenderer(item) {return `<div class="item">${item.namaSediaan} (${item.idKatalog})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $katalogAcplUrl ?>",
                    data: {query: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        itemTbl.addEventListener("click", (event) => {
            const linkElm = event.target;
            if (!linkElm.matches("a")) return;

            event.preventDefault();
            $.post({
                url: "<?= $plItemSrcUrl ?>",
                data: {kode: linkElm.getAttribute("href")},
                /** @param {string} data */
                success(data) {printAreaBlk.innerHTML = data},
                error() {printAreaBlk.innerHTML = str._<?= $h("terjadi error") ?>}
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, idKatalogWgt);
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
