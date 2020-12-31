<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanPerencanaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/perencanaan/reports.php the original file
 */
final class FormItem
{
    private string $output;

    public function __construct(
        string $registerId,
        string $actionUrl,
        string $katalogAcplUrl,
        string $itemSrcUrl,
        string $bulanSelect,
        string $tahunSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanPerencanaanUi.FormItem {
    export interface FormFields {
        idKatalog:          string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
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
            class: ".laporanPerencanaanItemFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Nama") ?>,
                        input: {class: ".idKatalogFld", name: "idKatalog"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Awal Bulan") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Akhir Bulan") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tahun") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
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
            widthColumn: {
                heading3: {class: ".targetTitleElm"}
            }
        },
        row_5: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_6: {
            widthTable: {
                class: ".targetTableElm",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. Perencanaan") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Tanggal Perencanaan") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("No. SPK/Kontrak") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Anggaran") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Bulan Anggaran") ?>},
                        td_8: {text: tlm.stringRegistry._<?= $h("Nilai") ?>}
                    }
                }
            }
        },
        row_7: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_8: {
            widthColumn: {class: ".targetPrintElm  tlm-print-area1"}
        },
    };

    constructor(divElm) {
        super();
        const {preferInt, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const targetTitleElm = divElm.querySelector(".targetTitleElm");
        const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        const targetPrintElm = divElm.querySelector(".targetPrintElm");

        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(bulanAwalAnggaranFld, bulanAkhirAnggaranFld, tahunAnggaranFld);

        const laporanPerencanaanItemWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".laporanPerencanaanItemFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanPerencanaanUi.FormItem.FormFields} data */
            loadData(data) {
                idKatalogWgt.value = data.idKatalog ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                const text = str._<?= $h("Hasil Pencarian: {{KATALOG}}") ?>;
                const katalog = idKatalogWgt.getItem().innerHTML;
                targetTitleElm.innerHTML = text.replace("{{KATALOG}}", katalog);
                targetTableWgt.load(event.data);
                targetPrintElm.innerHTML = "&nbsp;"
            },
            onFailSubmit() {
                const text = str._<?= $h("Hasil Pencarian: {{KATALOG}}") ?>;
                const katalog = idKatalogWgt.getItem().innerHTML;
                targetTitleElm.innerHTML = text.replace("{{KATALOG}}", katalog);
                targetTableWgt.load({});
                targetPrintElm.innerHTML = "&nbsp;";
            }
        });

        const idKatalogWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idKatalogFld"),
            maxItems: 1,
            valueField: "idKatalog",
            searchField: ["idKatalog", "namaSediaan"],
            /** @param {his.FatmaPharmacy.views.LaporanPerencanaanUi.FormItem.KatalogFields} item */
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
            /** @param {his.FatmaPharmacy.views.LaporanPerencanaanUi.FormItem.KatalogFields} item */
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

        const targetTableWgt = new spl.TableWidget({
            element: divElm.querySelector(".targetTableElm"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noPerencanaan"},
                3: {field: "tanggalPerencanaan", formatter: tlm.dateFormatter},
                4: {field: "noSpkKontrak"},
                5: {field: "pemasok"},
                6: {field: "anggaran"},
                7: {field: "bulanAnggaran"},
                8: {field: "nilai"}
            }
        });

        targetTableWgt.addDelegateListener("tbody", "click", (event) => {
            const linkElm = event.target;
            if (!linkElm.matches("a")) return;

            event.preventDefault();
            $.post({
                url: "<?= $itemSrcUrl ?>",
                data: {kode: linkElm.getAttribute("href")},
                /** @param {string} data */
                success(data) {targetPrintElm.innerHTML = data},
                error() {targetPrintElm.innerHTML = "error happens"}
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(laporanPerencanaanItemWgt, idKatalogWgt, targetTableWgt);
        tlm.app.registerWidget(this.constructor.widgetName, laporanPerencanaanItemWgt);
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
