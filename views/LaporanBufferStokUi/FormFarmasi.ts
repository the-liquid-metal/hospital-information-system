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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/bufferfarmasi.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/riwayatpenjualan.php the original file
 */
final class FormFarmasi
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $auditAccess,
        string $dataUrl,
        string $exportUrl,
        string $riwayatPenjualanDataUrl,
        string $generikAcplUrl,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.LaporanBufferStokUi.FormFarmasi {
    export interface Form1Fields {
        leadtime: "leadtime";
    }

    export interface FormFields {
        idKatalog:     string;
        namaSediaan:   string;
        idGenerik:     string;
        jenisMoving:   string;
        levelStok:     string;
        dataPenjualan: string;
        target:        string;
    }

    export interface GenerikFields {
        id:   string;
        nama: string;
        kode: string;
    }

    export interface TableFields {
        idKatalog:           string;
        namaSediaan:         string;
        jenisMoving:         string;
        jumlahAverage:       string;
        jumlahBuffer:        string;
        jumlahLeadtime:      string;
        jumlahRop:           string;
        jumlahOptimum:       string;
        jumlahStokFisik:     string;
        estimasiPerencanaan: string;
        namaGenerik:         string;
        jenisObat:           string;
        kelompokBarang:      string;
        updatedBy:           string;
        updatedTime:         string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Buffer Stok Farmasi") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Nama Generik") ?>,
                        select: {class: ".idGenerikFld"}
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
                        label: tlm.stringRegistry._<?= $h("Termasuk Data Penjualan") ?>,
                        checkbox: {class: ".dataPenjualanFld", name: "data_penjualan", value: 1}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Target") ?>, // to accomodate additional form
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
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Aksi") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Nama Sediaan") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Moving") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Rata-Rata Pemakaian") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Buffer") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Lead Time") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("ROP") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Optimum") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Stok") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Estimasi Habis") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Estimasi Perencanaan") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Nama Generik") ?>},
                        td_15: {text: tlm.stringRegistry._<?= $h("Jenis Barang") ?>},
                        td_16: {text: tlm.stringRegistry._<?= $h("Kelompok Barang") ?>},
                        td_17: {text: tlm.stringRegistry._<?= $h("Last Update") ?>},
                        td_18: {text: tlm.stringRegistry._<?= $h("User Update") ?>},
                    }
                }
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_5: {
            widthColumn: {
                paragraph: {text: ".detailTitleTxt"}
            }
        },
        row_6: {
            column_1: {
                h4: {text: tlm.stringRegistry._<?= $h("Penjualan 6 Bulan Terakhir") ?>},
                canvas: {class: ".chartPenjualanCvs"}
            },
            column_2: {
                h4: {text: tlm.stringRegistry._<?= $h("Floorstok 6 Bulan Terakhir") ?>},
                canvas: {class: ".chartFloorstokCvs"}
            }
        },
        row_7: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_8: {
            widthTable: {
                class: ".detailTbl",
                thead: {
                    tr: {
                        td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Tahun") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Bulan") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Jumlah Penjualan") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Jumlah Floorstok") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Jumlah Pemakaian") ?>},
                    }
                },
                tfoot: {
                    tr_1: {
                        td_1:  {colspan: 3},
                        td_2:  {class: ".totalPenjualanStc"},
                        td_3:  {class: ".totalFloorstokStc"},
                        td_4:  {class: ".totalPemakaianStc"},
                    },
                    tr_2: {
                        td_1:  {colspan: 3},
                        td_2:  {class: ".rata2PenjualanStc"},
                        td_3:  {class: ".rata2FloorstokStc"},
                        td_4:  {class: ".rata2PemakaianStc"},
                    },
                    tr_3: {
                        td_1:  {colspan: 3},
                        td_2:  {class: ".total3BulanTerakhirPenjualanStc"},
                        td_3:  {class: ".total3BulanTerakhirFloorstokStc"},
                        td_4:  {class: ".total3BulanTerakhirPemakaianStc"},
                    },
                    tr_4: {
                        td_1:  {colspan: 3},
                        td_2:  {class: ".rata23BulanTerakhirPenjualanStc"},
                        td_3:  {class: ".rata23BulanTerakhirFloorstokStc"},
                        td_4:  {class: ".rata23BulanTerakhirPemakaianStc"},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const {stringRegistry: str, toUserInt, toUserFloat} = tlm;
        const access = this.constructor.getAccess(tlm.userRole);

        /** @type {HTMLInputElement} */  const idKatalogFld = divElm.querySelector(".idKatalogFld");
        /** @type {HTMLInputElement} */  const namaSediaanFld = divElm.querySelector(".namaSediaanFld");
        /** @type {HTMLSelectElement} */ const jenisMovingFld = divElm.querySelector(".jenisMovingFld");
        /** @type {HTMLSelectElement} */ const levelStokFld = divElm.querySelector(".levelStokFld");
        /** @type {HTMLInputElement} */  const dataPenjualanFld = divElm.querySelector(".dataPenjualanFld");
        /** @type {HTMLSelectElement} */ const targetFld = divElm.querySelector(".targetFld");

        /** @type {HTMLDivElement}    */ const detailTitleTxt = divElm.querySelector(".detailTitleTxt");
        /** @type {HTMLCanvasElement} */ const chartPenjualanCvs = divElm.querySelector(".chartPenjualanCvs");
        /** @type {HTMLCanvasElement} */ const chartFloorstokCvs = divElm.querySelector(".chartFloorstokCvs");
        /** @type {HTMLDivElement} */    const totalPenjualanStc = divElm.querySelector(".totalPenjualanStc");
        /** @type {HTMLDivElement} */    const totalFloorstokStc = divElm.querySelector(".totalFloorstokStc");
        /** @type {HTMLDivElement} */    const totalPemakaianStc = divElm.querySelector(".totalPemakaianStc");
        /** @type {HTMLDivElement} */    const rata2PenjualanStc = divElm.querySelector(".rata2PenjualanStc");
        /** @type {HTMLDivElement} */    const rata2FloorstokStc = divElm.querySelector(".rata2FloorstokStc");
        /** @type {HTMLDivElement} */    const rata2PemakaianStc = divElm.querySelector(".rata2PemakaianStc");
        /** @type {HTMLDivElement} */    const total3BulanTerakhirPenjualanStc = divElm.querySelector(".total3BulanTerakhirPenjualanStc");
        /** @type {HTMLDivElement} */    const total3BulanTerakhirFloorstokStc = divElm.querySelector(".total3BulanTerakhirFloorstokStc");
        /** @type {HTMLDivElement} */    const total3BulanTerakhirPemakaianStc = divElm.querySelector(".total3BulanTerakhirPemakaianStc");
        /** @type {HTMLDivElement} */    const rata23BulanTerakhirPenjualanStc = divElm.querySelector(".rata23BulanTerakhirPenjualanStc");
        /** @type {HTMLDivElement} */    const rata23BulanTerakhirFloorstokStc = divElm.querySelector(".rata23BulanTerakhirFloorstokStc");
        /** @type {HTMLDivElement} */    const rata23BulanTerakhirPemakaianStc = divElm.querySelector(".rata23BulanTerakhirPemakaianStc");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.LaporanBufferStokUi.FormFarmasi.FormFields} data */
            loadData(data) {
                idKatalogFld.value = data.idKatalog ?? "";
                namaSediaanFld.value = data.namaSediaan ?? "";
                idGenerikWgt.value = data.idGenerik ?? "";
                jenisMovingFld.value = data.jenisMoving ?? "";
                levelStokFld.value = data.levelStok ?? "";
                dataPenjualanFld.checked = data.dataPenjualan == 1;
                targetFld.value = data.target ?? "";
            },
            submit() {
                if (targetFld.value == 1) {
                    itemWgt.refresh({
                        query: {
                            idKatalog: idKatalogFld.value,
                            idGenerik: idGenerikWgt.value,
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

        const idGenerikWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idGenerikFld"),
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.LaporanBufferStokUi.FormFarmasi.GenerikFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.LaporanBufferStokUi.FormFarmasi.GenerikFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $generikAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "idKatalog", formatter(val) {
                    return draw({class: ".viewBtn", type: "primary", icon: "list", value: val, title: str._<?= $h("Riwayat Penjualan & Floorstok") ?>});
                }},
                3:  {field: "idKatalog"},
                4:  {field: "namaSediaan"},
                5:  {field: "jenisMoving"},
                6:  {field: "jumlahAverage", formatter: tlm.intFormatter},
                7:  {field: "jumlahBuffer", formatter: tlm.intFormatter},
                8:  {field: "jumlahLeadtime", formatter: tlm.intFormatter},
                9:  {field: "jumlahRop", formatter: tlm.intFormatter},
                10: {field: "jumlahOptimum", formatter: tlm.intFormatter},
                11: {field: "jumlahStokFisik", formatter: tlm.intFormatter},
                12: {formatter(unused, item) {
                    const {jumlahOptimum, jumlahStokFisik} = item;
                    return jumlahOptimum ? (jumlahStokFisik / jumlahOptimum) : 0;
                }},
                13: {field: "estimasiPerencanaan"},
                14: {field: "namaGenerik"},
                15: {field: "jenisObat"},
                16: {field: "kelompokBarang"},
                17: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter},
                18: {field: "updatedBy", visible: access.audit}
            },
            bodyRowModifier(nRow, aData) {
                return {css: "", class: "", attribute: ""};
                // fnRowCallback(nRow, aData) {
                const nRowElm = divElm.querySelector(nRow);
                nRowElm.style.color = "black";
                if (aData[4] != "DM") {
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
                if (aData[12] < 0 || aData[12] == null) {
                    nRowElm.querySelector("td:nth-child(13)").innerHTML = "0";
                }

                let namaGenerik = aData[13] || "";
                if (namaGenerik.length > 20) {
                    namaGenerik = namaGenerik.substr(0, 20) + "...";
                }
                nRowElm.querySelector("td:nth-child(14)").innerHTML = namaGenerik;
                nRowElm.querySelector("td:nth-child(11)").style.fontWeight = "bold";
                nRowElm.querySelector("td").style.fontSize = "12px";
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            detailWgt.refresh({
                query: {idKatalog: viewBtn.value}
            });
        });

        const detailWgt = new spl.TableWidget({
            element: divElm.querySelector(".detailTbl"),
            url: "<?= $riwayatPenjualanDataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "tahun"},
                3: {field: "bulan"},
                4: {field: "jumlahPenjualan", formatter: tlm.intFormatter},
                5: {field: "jumlahFloorstok", formatter: tlm.intFormatter},
                6: {formatter(unused, item) {
                    const {jumlahPenjualan, jumlahFloorstok} = item;
                    return toUserInt(jumlahPenjualan + jumlahFloorstok);
                }},
            },
            onPostBody() {
                let totalPenjualan = 0;
                let totalFloorstok = 0;
                let totalPemakaian = 0;
                let totalPenjualanTriwulan = 0;
                let totalFloorstokTriwulan = 0;
                let totalPemakaianTriwulan = 0;
                const jumlahDaftarHistory = this._data.length;

                this._data.forEach((item, idx) => {
                    const pemakaian = item.jumlahPenjualan + item.jumlahFloorStok;
                    totalPenjualan += item.jumlahPenjualan;
                    totalFloorstok += item.jumlahFloorStok;
                    totalPemakaian += pemakaian;

                    if (jumlahDaftarHistory - idx <= 3) {
                        totalPenjualanTriwulan += item.jumlahPenjualan;
                        totalFloorstokTriwulan += item.jumlahFloorStok;
                        totalPemakaianTriwulan += pemakaian;
                    }
                });

                const data1st = this._data[0];
                detailTitleTxt.innerHTML = data1st ? `${data1st.namaBarang} (${data1st.idKatalog})` : "";

                chartPenjualanCvs.innerHTML = "";
                chartFloorstokCvs.innerHTML = "";
                totalPenjualanStc.innerHTML = toUserFloat(totalPenjualan);
                totalFloorstokStc.innerHTML = toUserFloat(totalFloorstok);
                totalPemakaianStc.innerHTML = toUserFloat(totalPemakaian);
                rata2PenjualanStc.innerHTML = jumlahDaftarHistory ? toUserFloat(totalPenjualan / jumlahDaftarHistory) : "";
                rata2FloorstokStc.innerHTML = jumlahDaftarHistory ? toUserFloat(totalFloorstok / jumlahDaftarHistory) : "";
                rata2PemakaianStc.innerHTML = jumlahDaftarHistory ? toUserFloat(totalPemakaian / jumlahDaftarHistory) : "";
                total3BulanTerakhirPenjualanStc.innerHTML = toUserFloat(totalPenjualanTriwulan);
                total3BulanTerakhirFloorstokStc.innerHTML = toUserFloat(totalFloorstokTriwulan);
                total3BulanTerakhirPemakaianStc.innerHTML = toUserFloat(totalPemakaianTriwulan);
                rata23BulanTerakhirPenjualanStc.innerHTML = jumlahDaftarHistory ? toUserFloat(totalPenjualanTriwulan / 3) : "";
                rata23BulanTerakhirFloorstokStc.innerHTML = jumlahDaftarHistory ? toUserFloat(totalFloorstokTriwulan / 3) : "";
                rata23BulanTerakhirPemakaianStc.innerHTML = jumlahDaftarHistory ? toUserFloat(totalPemakaianTriwulan / 3) : "";
            }
        };

        const dataPenjualan = {
            labels: [
                // <?php foreach ($daftarHistory as $key => $history): ?> //
                "<?= $numToShortMonthName($history->bulan) . '-' . $history->tahun ?>",
                // <?php endforeach ?> //
            ],
            datasets: [
                {
                    label: "History Penjualan",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: [
                        // <?php foreach ($daftarHistory as $key => $history): ?> //
                        "<?= $history->jumlahPenjualan ?>",
                        // <?php endforeach ?> //
                    ]
                }
            ]
        };

        const dataFloorstok = {
            labels: [
                // <?php foreach ($daftarHistory as $key => $history): ?> //
                "<?= $numToShortMonthName($history->bulan) . '-' . $history->tahun ?>",
                // <?php endforeach ?> //
            ],
            datasets: [
                {
                    label: "History Floorstok",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: [
                        // <?php foreach ($daftarHistory as $key => $history): ?> //
                        "<?= $history->jumlahFloorStok ?>",
                        // <?php endforeach ?> //
                    ]
                }
            ]
        };

        const options = {
            scaleShowGridLines: true,
            scaleGridLineColor: "rgba(0,0,0,.05)",
            scaleGridLineWidth: 1,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines: true,
            bezierCurve: true,
            bezierCurveTension: 0.4,
            pointDot: true,
            pointDotRadius: 4,
            pointDotStrokeWidth: 1,
            pointHitDetectionRadius: 20,
            datasetStroke: true,
            datasetStrokeWidth: 2,
            datasetFill: true,
            scaleBeginAtZero: true,
            legendTemplate: `<ul class="<%=name.toLowerCase()%>-legend"><% for (var i = 0; i < datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if (datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>`
        };

        const ctx1 = divElm.querySelector(".chartPenjualanCvs").getContext("2d");
        const ctx2 = divElm.querySelector(".chartFloorstokCvs").getContext("2d");
        // TODO: js: missing function: Line()
        new Chart(ctx1).Line(dataPenjualan, options);
        new Chart(ctx2).Line(dataFloorstok, options);

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, idGenerikWgt, itemWgt, detailWgt);
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
