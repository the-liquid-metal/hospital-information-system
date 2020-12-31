<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\MutasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Mutasi/ringkasan.php the original file
 */
final class Ringkasan
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $insertInsertSaldoAwalUrl,
        string $insertInsertPembelianUrl,
        string $insertInsertHasilProduksiUrl,
        string $insertInsertKoreksiUrl,
        string $insertInsertPenjualanUrl,
        string $insertInsertFloorStokUrl,
        string $insertInsertBahanProduksiUrl,
        string $insertInsertRusakUrl,
        string $insertInsertExpiredUrl,
        string $insertInsertReturPembelianUrl,
        string $insertInsertKoreksiPenerimaanUrl,
        string $insertInsertTidakTerlayaniUrl,
        string $bulanSelect,
        string $tahunSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Mutasi.Ringkasan {
    export interface FormFields {
        jenis: string;
        bulan: string;
        tahun: string;
    }

    export interface TableFields {
        tahun:                        string;
        bulan:                        string;
        tanggalUbahAwal:              string;
        tanggalUbahPembelian:         string;
        tanggalUbahHasilProduksi:     string;
        tanggalUbahKoreksi:           string;
        tanggalUbahPenjualan:         string;
        tanggalUbahFloorstok:         string;
        tanggalUbahBahanProduksi:     string;
        tanggalUbahRusak:             string;
        tanggalUbahExpired:           string;
        tanggalUbahReturPembelian:    string;
        tanggalUbahTakTerlayani:      string;
        tanggalUbahKoreksiPenerimaan: string;
        tanggalUbahAkhir:             string;
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
            class: ".ringkasanFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Jenis") ?>,
                        select: {
                            class: ".jenisFld",
                            name: "jenis",
                            option_1:  {value: "saldoAwal",         label: tlm.stringRegistry._<?= $h("Saldo Awal") ?>},
                            option_2:  {value: "pembelian",         label: tlm.stringRegistry._<?= $h("Pembelian") ?>},
                            option_3:  {value: "hasilProduksi",     label: tlm.stringRegistry._<?= $h("Hasil Produksi") ?>},
                            option_4:  {value: "koreksi",           label: tlm.stringRegistry._<?= $h("Koreksi") ?>},
                            option_5:  {value: "penjualan",         label: tlm.stringRegistry._<?= $h("Penjualan") ?>},
                            option_6:  {value: "floorStok",         label: tlm.stringRegistry._<?= $h("Floorstok") ?>},
                            option_7:  {value: "bahanProduksi",     label: tlm.stringRegistry._<?= $h("Bahan Produksi") ?>},
                            option_8:  {value: "rusak",             label: tlm.stringRegistry._<?= $h("Rusak") ?>},
                            option_9:  {value: "expired",           label: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            option_10: {value: "returPembelian",    label: tlm.stringRegistry._<?= $h("Retur Pembelian") ?>},
                            option_11: {value: "koreksiPenerimaan", label: tlm.stringRegistry._<?= $h("Koreksi Penerimaan") ?>},
                            option_12: {value: "tidakTerlayani",    label: tlm.stringRegistry._<?= $h("Tidak Terlayani") ?>}
                        }
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Bulan") ?>,
                        select: {class: ".bulanFld", name: "bulan"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tahun") ?>,
                        select: {class: ".tahunFld", name: "tahun"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        },
        row_3: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_4: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr_1: {
                        td_1: /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Tahun") ?>},
                        td_3: /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Bulan") ?>},
                        td_4: /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Saldo Awal") ?>},
                        td_5: /*  5-7  */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Pengadaan") ?>},
                        td_6: /*  8-15 */ {colspan: 7, text: tlm.stringRegistry._<?= $h("Pemakaian") ?>},
                        td_7: /*  16   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Koreksi Penerimaan") ?>},
                        td_8: /*  17   */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Saldo Akhir") ?>},
                    },
                    tr_2: {
                        td_1:  /* 5  */ {text: tlm.stringRegistry._<?= $h("Pembelian") ?>},
                        td_2:  /* 6  */ {text: tlm.stringRegistry._<?= $h("Hasil Produksi") ?>},
                        td_3:  /* 7  */ {text: tlm.stringRegistry._<?= $h("Koreksi") ?>},
                        td_4:  /* 8  */ {text: tlm.stringRegistry._<?= $h("Penjualan") ?>},
                        td_5:  /* 9  */ {text: tlm.stringRegistry._<?= $h("Floor Stock") ?>},
                        td_6:  /* 10 */ {text: tlm.stringRegistry._<?= $h("Bahan Produksi") ?>},
                        td_7:  /* 11 */ {text: tlm.stringRegistry._<?= $h("Rusak") ?>},
                        td_8:  /* 12 */ {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                        td_9:  /* 13 */ {text: tlm.stringRegistry._<?= $h("Retur Pembelian") ?>},
                        td_10: /* 14 */ {text: tlm.stringRegistry._<?= $h("Tidak Terlayani") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {numToMonthName: nToM, toUserDate: userDate} = tlm;
        const emptyIcon = `<i class="glyphicon glyphicon-remove text-danger"></i>`;
        const notEmptyIcon = `<i class="glyphicon glyphicon-ok text-success"></i>`;
        const cellFormatter = val => val ? emptyIcon : notEmptyIcon +"<br/>"+ userDate(val);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const jenisFld = divElm.querySelector(".jenisFld");
        /** @type {HTMLSelectElement} */ const bulanFld = divElm.querySelector(".bulanFld");
        /** @type {HTMLSelectElement} */ const tahunFld = divElm.querySelector(".tahunFld");

        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunFld);
        this._selects.push(bulanFld, tahunFld);

        const ringkasanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".ringkasanFrm"),
            /** @param {his.FatmaPharmacy.views.Mutasi.Ringkasan.FormFields} data */
            loadData(data) {
                jenisFld.value = data.jenis ?? "";
                bulanFld.value = data.bulan ?? "";
                tahunFld.value = data.tahun ?? "";
            },
            resetBtnId: false,
            onBeforeSubmit() {
                switch (jenisFld.value) {
                    case "saldoAwal":         this._actionUrl = "<?= $insertInsertSaldoAwalUrl ?>"; break;
                    case "pembelian":         this._actionUrl = "<?= $insertInsertPembelianUrl ?>"; break;
                    case "hasilProduksi":     this._actionUrl = "<?= $insertInsertHasilProduksiUrl ?>"; break;
                    case "koreksi":           this._actionUrl = "<?= $insertInsertKoreksiUrl ?>"; break;
                    case "penjualan":         this._actionUrl = "<?= $insertInsertPenjualanUrl ?>"; break;
                    case "floorStok":         this._actionUrl = "<?= $insertInsertFloorStokUrl ?>"; break;
                    case "bahanProduksi":     this._actionUrl = "<?= $insertInsertBahanProduksiUrl ?>"; break;
                    case "rusak":             this._actionUrl = "<?= $insertInsertRusakUrl ?>"; break;
                    case "expired":           this._actionUrl = "<?= $insertInsertExpiredUrl ?>"; break;
                    case "returPembelian":    this._actionUrl = "<?= $insertInsertReturPembelianUrl ?>"; break;
                    case "koreksiPenerimaan": this._actionUrl = "<?= $insertInsertKoreksiPenerimaanUrl ?>"; break;
                    case "tidakTerlayani":    this._actionUrl = "<?= $insertInsertTidakTerlayaniUrl ?>"; break;
                }
            },
            onSuccessSubmit() {
                itemWgt.refresh();
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1:  {formatter: tlm.rowNumGenerator},
                2:  {field: "tahun"},
                3:  {field: "bulan",                        formatter: val => nToM(val)},
                4:  {field: "tanggalUbahAwal",              formatter: cellFormatter},
                5:  {field: "tanggalUbahPembelian",         formatter: cellFormatter},
                6:  {field: "tanggalUbahHasilProduksi",     formatter: cellFormatter},
                7:  {field: "tanggalUbahKoreksi",           formatter: cellFormatter},
                8:  {field: "tanggalUbahPenjualan",         formatter: cellFormatter},
                9:  {field: "tanggalUbahFloorstok",         formatter: cellFormatter},
                10: {field: "tanggalUbahBahanProduksi",     formatter: cellFormatter},
                11: {field: "tanggalUbahRusak",             formatter: cellFormatter},
                12: {field: "tanggalUbahExpired",           formatter: cellFormatter},
                13: {field: "tanggalUbahReturPembelian",    formatter: cellFormatter},
                14: {field: "tanggalUbahTakTerlayani",      formatter: cellFormatter},
                15: {field: "tanggalUbahKoreksiPenerimaan", formatter: cellFormatter},
                16: {field: "tanggalUbahAkhir",             formatter: cellFormatter}
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(ringkasanWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, ringkasanWgt);
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
