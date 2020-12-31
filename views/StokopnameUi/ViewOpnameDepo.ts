<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\StokopnameUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/opnamedepo.php the original file
 */
final class ViewOpnameDepo
{
    private string $output;

    /**
     * @author Hendra Gunawan
     */
    public function __construct(string $registerId, string $dataUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>

<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.StokopnameUi.ViewOpnameDepo {
    export interface DivFields {
        stokopname:                StokopnameObj;
        daftarStokopname:          object[];
        daftarBaris:               object[];
        totalNilaiAdm:             string;
        totalNilaiFisik:           string;
        totalJumlahAdm:            string;
        totalJumlahFisik:          string;
        totalSelisihJumlahSurplus: string;
        totalSelisihJumlahMinus:   string;
        totalSelisihNilaiSurplus:  string;
        totalSelisihNilaiMinus:    string;
        totalSelisihNilaiAbsolut:  string;
        totalSelisihJumlahAbsolut: string;
    }

    export interface StokopnameObj {
        tanggalAdm: string;
        namaDepo:   string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Hasil Stok Opname Depo") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            widthColumn: {
                paragraph_1: {class: ".subtitle1Txt"},
                paragraph_2: {class: ".subtitle2Txt"},
                paragraph_3: {class: ".subtitle3Txt"},
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_5: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr_1: {
                        td_1:  /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_3:  /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Barang") ?>},
                        td_4:  /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                        td_5:  /*  5    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_6:  /*  6    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                        td_7:  /*  7    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Stok Adm") ?>},
                        td_8:  /*  8    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Stok Fisik") ?>},
                        td_9:  /*  9    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Harga Pokok") ?>},
                        td_10: /* 10    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nilai Fisik") ?>},
                        td_11: /* 11-12 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Selisih") ?>},
                    },
                    tr_2: {
                        td_1: /* 11 */ {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                        td_2: /* 12 */ {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                    }
                }
            }
        },
        row_6: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_7: {
            box_1: {
                title: tlm.stringRegistry._<?= $h("Rekapitulasi Stok Opname") ?>,
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Total Sebelum Stok Opname") ?>,
                    staticText: {class: ".sebelumStokOpname1Stc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Total Setelah Stok Opname") ?>,
                    staticText: {class: ".setelahStokOpname1Stc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih") ?>,
                    staticText: {class: ".selisih1Stc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih (+)") ?>,
                    staticText: {class: ".selisihPlus1Stc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih (-)") ?>,
                    staticText: {class: ".selisihMinus1Stc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih (abs)") ?>,
                    staticText: {class: ".selisihAbs1Stc"}
                },
                formGroup_7: {
                    label: tlm.stringRegistry._<?= $h("Tingkat Selisih") ?>,
                    staticText: {class: ".tingkatSelisih1Stc"}
                }
            },
            box_2: {
                title: tlm.stringRegistry._<?= $h("Rekapitulasi Stok Opname Tanpa Harga") ?>,
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Total Sebelum Stok Opname") ?>,
                    staticText: {class: ".sebelumStokOpname2Stc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Total Setelah Stok Opname") ?>,
                    staticText: {class: ".setelahStokOpname2Stc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih") ?>,
                    staticText: {class: ".selisih2Stc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih (+)") ?>,
                    staticText: {class: ".selisihPlus2Stc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih (-)") ?>,
                    staticText: {class: ".selisihMinus2Stc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih (abs)") ?>,
                    staticText: {class: ".selisihAbs2Stc"}
                },
                formGroup_7: {
                    label: tlm.stringRegistry._<?= $h("Tingkat Selisih") ?>,
                    staticText: {class: ".tingkatSelisih2Stc"}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {toUserFloat: userFloat, toUserDatetime: userDatetime, nowVal, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */ const subtitle1Txt = divElm.querySelector(".subtitle1Txt");
        /** @type {HTMLDivElement} */ const subtitle2Txt = divElm.querySelector(".subtitle2Txt");
        /** @type {HTMLDivElement} */ const subtitle3Txt = divElm.querySelector(".subtitle3Txt");

        /** @type {HTMLDivElement} */ const sebelumStokOpname1Stc = divElm.querySelector(".sebelumStokOpname1Stc");
        /** @type {HTMLDivElement} */ const setelahStokOpname1Stc = divElm.querySelector(".setelahStokOpname1Stc");
        /** @type {HTMLDivElement} */ const selisih1Stc = divElm.querySelector(".selisih1Stc");
        /** @type {HTMLDivElement} */ const selisihPlus1Stc = divElm.querySelector(".selisihPlus1Stc");
        /** @type {HTMLDivElement} */ const selisihMinus1Stc = divElm.querySelector(".selisihMinus1Stc");
        /** @type {HTMLDivElement} */ const selisihAbs1Stc = divElm.querySelector(".selisihAbs1Stc");
        /** @type {HTMLDivElement} */ const tingkatSelisih1Stc = divElm.querySelector(".tingkatSelisih1Stc");

        /** @type {HTMLDivElement} */ const sebelumStokOpname2Stc = divElm.querySelector(".sebelumStokOpname2Stc");
        /** @type {HTMLDivElement} */ const setelahStokOpname2Stc = divElm.querySelector(".setelahStokOpname2Stc");
        /** @type {HTMLDivElement} */ const selisih2Stc = divElm.querySelector(".selisih2Stc");
        /** @type {HTMLDivElement} */ const selisihPlus2Stc = divElm.querySelector(".selisihPlus2Stc");
        /** @type {HTMLDivElement} */ const selisihMinus2Stc = divElm.querySelector(".selisihMinus2Stc");
        /** @type {HTMLDivElement} */ const selisihAbs2Stc = divElm.querySelector(".selisihAbs2Stc");
        /** @type {HTMLDivElement} */ const tingkatSelisih2Stc = divElm.querySelector(".tingkatSelisih2Stc");

        const divWgt = spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.StokopnameUi.ViewOpnameDepo.DivFields} data */
            loadData(data) {
                itemWgt.load(data.daftarBaris);

                subtitle1Txt.innerHTML = str._<?= $h("Tanggal: {{STR}}") ?>.replace("{{STR}}", userDatetime(data.stokopname.tanggalAdm));
                subtitle2Txt.innerHTML = str._<?= $h("Gudang: {{STR}}") ?>.replace("{{STR}}", data.stokopname.namaDepo;
                subtitle3Txt.innerHTML = str._<?= $h("Tanggal Cetak: {{STR}}") ?>.replace("{{STR}}", nowVal("user"));

                sebelumStokOpname1Stc.innerHTML = userFloat(data.totalNilaiAdm);
                setelahStokOpname1Stc.innerHTML = userFloat(data.totalNilaiFisik);
                selisih1Stc.innerHTML = userFloat(Math.abs(data.totalNilaiAdm - data.totalNilaiFisik));
                selisihPlus1Stc.innerHTML = userFloat(data.totalSelisihNilaiSurplus);
                selisihMinus1Stc.innerHTML = userFloat(data.totalSelisihNilaiMinus);
                selisihAbs1Stc.innerHTML = userFloat(data.totalSelisihNilaiAbsolut);
                tingkatSelisih1Stc.innerHTML = userFloat(data.totalSelisihNilaiAbsolut / data.totalNilaiFisik * 100) + "%";

                sebelumStokOpname2Stc.innerHTML = userFloat(data.totalJumlahAdm);
                setelahStokOpname2Stc.innerHTML = userFloat(data.totalJumlahFisik);
                selisih2Stc.innerHTML = userFloat(Math.abs(data.totalJumlahAdm - data.totalJumlahFisik));
                selisihPlus2Stc.innerHTML = userFloat(data.totalSelisihJumlahSurplus);
                selisihMinus2Stc.innerHTML = userFloat(data.totalSelisihJumlahMinus);
                selisihAbs2Stc.innerHTML = userFloat(data.totalSelisihJumlahAbsolut);
                tingkatSelisih2Stc.innerHTML = userFloat(data.totalSelisihJumlahAbsolut / data.totalJumlahFisik * 100) + "%";
            },
            dataUrl: "<?= $dataUrl ?>",
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            columns: {}
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(divWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, divWgt);
    }
});
</script>

<table class="itemTbl">
    <tbody>
    <?php
    $toUserFloat = Yii::$app->number->toUserFloat();
    $daftarStokopname = [];
    $daftarBaris = [];
    ?>
    <?php foreach ($daftarBaris as $baris): ?>
        <?php if (isset($baris->namaKelompok)): ?>
            <tr class="row_kelompok">
                <td colspan="7" class="nama_kelompok"><?= $baris->no . $baris->namaKelompok ?></td>
                <td colspan="3" class="subtotal">
                    Subtotal sebelum stok opname<br/>
                    Subtotal setelah stok opname<br/>
                    Subtotal selisih
                </td>
                <td colspan="2" class="nilai_subtotal">
                    <?= $toUserFloat($baris->subtotalNilaiAdm) ?><br/>
                    <?= $toUserFloat($baris->subtotalNilaiFisik) ?><br/>
                    <?= $toUserFloat($baris->subtotalNilaiFisik - $baris->subtotalNilaiAdm) ?>
                </td>
            </tr>

        <?php else: ?>
            <?php $stokopname = $daftarStokopname[$baris->i] ?>
            <tr class="katalog">
                <td><?= $baris->no ?></td>
                <td><?= $stokopname->kode ?></td>
                <td><?= $stokopname->namaBarang ?></td>
                <td><?= $stokopname->namaPabrik ?></td>
                <td><?= $stokopname->satuan ?></td>
                <td><?= $stokopname->tanggalKadaluarsa ?></td>
                <td class="text-right"><?= $toUserFloat($stokopname->backupStokAdm) ?></td>
                <td class="text-right"><?= $toUserFloat($stokopname->jumlahFisik) ?></td>
                <td class="text-right"><?= $toUserFloat($stokopname->hpItem) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->nilaiFisik) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->selisihJumlah) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->selisihNilai) ?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
    </tbody>
</table>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
