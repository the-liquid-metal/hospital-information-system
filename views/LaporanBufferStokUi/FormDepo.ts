<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanBufferStokUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/bufferdepo.php the original file
 */
final class FormDepo
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $auditAccess,
        string $dataUrl,
        string $exportUrl,
        string $riwayatPenjualanWidgetId,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanBufferStokUi.FormDepo {
    export interface FormFields {
        idKatalog:   string;
        namaSediaan: string;
        idDepo:      string;
        jenisMoving: string;
        levelStok:   string;
        target:      string;
    }

    export interface TableFields {
        idKatalog:       string;
        idDepo:          string;
        namaSediaan:     string;
        namaDepo:        string;
        jenisMoving:     string;
        jumlahBuffer:    string;
        jumlahLeadtime:  string;
        jumlahRop:       string;
        jumlahOptimum:   string;
        jumlahStokFisik: string;
        jenisObat:       string;
        kelompokBarang:  string;
        updatedBy:       string;
        updatedTime:     string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{audit: boolean}}
     */
    static getAccess(role) {
        const pool = {
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
                heading3: {text: tlm.stringRegistry._<?= $h("Buffer Stok Depo") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Kode") ?>,
                        input: {class: ".idKatalogFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Sediaan") ?>,
                        input: {class: ".namaSediaanFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Depo") ?>,
                        select: {class: ".idDepoFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Jenis Moving") ?>,
                        select: {
                            class: ".jenisMovingFld",
                            option_1: {value: "",   label: tlm.stringRegistry._<?= $h("Semua") ?>},
                            option_2: {value: "DM", label: tlm.stringRegistry._<?= $h("Death Moving") ?>},
                            option_3: {value: "SM", label: tlm.stringRegistry._<?= $h("Slow Moving") ?>},
                            option_4: {value: "MM", label: tlm.stringRegistry._<?= $h("Medium Moving") ?>},
                            option_5: {value: "FM", label: tlm.stringRegistry._<?= $h("Fast Moving") ?>}
                        }
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Level") ?>,
                        select: {
                            class: ".levelStokFld",
                            option_1: {value: "",  label: tlm.stringRegistry._<?= $h("Semua") ?>},
                            option_2: {value: "0", label: tlm.stringRegistry._<?= $h("Kurang dari buffer") ?>},
                            option_3: {value: "1", label: tlm.stringRegistry._<?= $h("Antara buffer dan ROP") ?>},
                            option_4: {value: "2", label: tlm.stringRegistry._<?= $h("Kurang dari ROP") ?>},
                            option_5: {value: "3", label: tlm.stringRegistry._<?= $h("Lebih dari ROP") ?>}
                        }
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Target") ?>, // to accomodate plain link
                        select: {
                            class: ".targetFld",
                            option_1: {value: "1", label: tlm.stringRegistry._<?= $h("Tabel di layar") ?>},
                            option_2: {value: "2", label: tlm.stringRegistry._<?= $h("export ke Excel") ?>},
                        }
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
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Aksi") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Nama Sediaan") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Nama Depo") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Moving") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Buffer") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Leadtime") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("ROP") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Optimum") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Stok") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Estimasi Habis") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Jenis Barang") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Kelompok Barang") ?>},
                        td_15: {text: tlm.stringRegistry._<?= $h("Last Update") ?>},
                        td_16: {text: tlm.stringRegistry._<?= $h("User Update") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const str = tlm.stringRegistry;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const idKatalogFld = divElm.querySelector(".idKatalogFld");
        /** @type {HTMLInputElement} */  const namaSediaanFld = divElm.querySelector(".namaSediaanFld");
        /** @type {HTMLSelectElement} */ const idDepoFld = divElm.querySelector(".idDepoFld");
        /** @type {HTMLSelectElement} */ const jenisMovingFld = divElm.querySelector(".jenisMovingFld");
        /** @type {HTMLSelectElement} */ const levelStokFld = divElm.querySelector(".levelStokFld");
        /** @type {HTMLSelectElement} */ const targetFld = divElm.querySelector(".targetFld");

        tlm.app.registerSelect("_<?= $depoSelect ?>", idDepoFld);
        this._selects.push(idDepoFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanBufferStokUi.FormDepo.FormFields} data */
            loadData(data) {
                idKatalogFld.value = data.idKatalog ?? "";
                namaSediaanFld.value = data.namaSediaan ?? "";
                idDepoFld.value = data.idDepo ?? "";
                jenisMovingFld.value = data.jenisMoving ?? "";
                levelStokFld.value = data.levelStok ?? "";
                targetFld.value = data.target ?? "";
            },
            submit() {
                if (targetFld.value == 1) {
                    itemWgt.refresh({
                        query: {
                            idKatalog: idKatalogFld.value,
                            idDepo: idDepoFld.value,
                            namaSediaan: namaSediaanFld.value,
                            jenisMoving: jenisMovingFld.value,
                            levelStok: levelStokFld.value,
                        }
                    });
                } else {
                    // TODO: js: uncategorized: create method in tlm namespace to download files and use: "<?= $exportUrl ?>"
                }
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {formatter(unused, item) {
                    const {idKatalog, idDepo} = item;
                    const title = str._<?= $h("Riwayat Penjualan & Floorstok") ?>;
                    return draw({class: ".viewBtn", icon: "list", type: "primary", value: JSON.stringify({idKatalog, idDepo}), title});
                }},
                3:  {field: "idKatalog"},
                4:  {field: "namaSediaan"},
                5:  {field: "namaDepo"},
                6:  {field: "jenisMoving"},
                7:  {field: "jumlahBuffer", formatter: tlm.intFormatter},
                8:  {field: "jumlahLeadtime", formatter: tlm.intFormatter},
                9:  {field: "jumlahRop", formatter: tlm.intFormatter},
                10: {field: "jumlahOptimum", formatter: tlm.intFormatter},
                11: {field: "jumlahStokFisik", formatter: tlm.intFormatter},
                12: {formatter(unused, item) {
                    const {jumlahOptimum, jumlahStokFisik} = item;
                    return jumlahOptimum ? (jumlahStokFisik / jumlahOptimum) : 0;
                }},
                13: {field: "jenisObat"},
                14: {field: "kelompokBarang"},
                15: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter},
                16: {field: "updatedBy", visible: access.audit}
            },
            bodyRowModifier(nRow, aData) {
                return {css: "", class: "", attribute: ""};

                // TODO: js: uncategorized: bug
                // fnRowCallback(nRow, aData) {
                const nRowElm = divElm.querySelector(nRow);
                nRowElm.style.color = "black";
                if (aData[5] != "DM") {
                    if (parseInt(aData[10]) > parseInt(aData[8])) {
                        nRowElm.querySelector("td").style.backgroundColor = "white";
                    } else if (parseInt(aData[10]) >= parseInt(aData[6])) {
                        nRowElm.querySelector("td").style.backgroundColor = "#FFFFDD";
                        nRowElm.querySelector("td:nth-child(11)").style.color = "#ADAA00";
                    } else {
                        nRowElm.querySelector("td").style.backgroundColor = "#FFDDDD";
                        nRowElm.querySelector("td:nth-child(11)").style.color = "#F00";
                    }
                }

                const jumlahHari = Math.floor(aData[11] * 30);
                const sisaBulan = Math.floor(jumlahHari / 30);
                const sisaHari = jumlahHari % 30;
                let sisa = "";

                if (sisaBulan > 0) {
                    sisa += sisaBulan + " bulan "
                }
                if (sisaHari > 0) {
                    sisa += sisaHari + " hari"
                }
                if (sisa == "") {
                    sisa = "-"
                }
                nRowElm.querySelector("td:nth-child(12)").innerHTML = sisa;
                nRowElm.querySelector("td:nth-child(11)").style.fontWeight = "bold";
                nRowElm.querySelector("td").style.fontSize = "12px";
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const {idKatalog, idDepo} = JSON.parse(viewBtn.value);
            const widget = tlm.app.getWidget("_<?= $riwayatPenjualanWidgetId ?>");
            widget.show();
            widget.loadData({idKatalog, idDepo}, true);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, itemWgt);
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
