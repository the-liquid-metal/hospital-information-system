<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\StokopnameUi;

use tlm\libs\LowEnd\components\DateTimeException;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/stokopname/opnameusergas.php the original file
 */
final class ViewOpnameUserGas
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(string $registerId, string $dataUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.StokopnameUi.ViewOpnameUserGas {
    export interface DivFields {
        opnameUser:             OpnameUserObj;
        daftarOpnameUser:       string;
        daftarBaris:            object;
        totalSebelumStokOpname: string;
        totalSesudahStokOpname: string;
    }

    export interface OpnameUserObj {
        tanggalAdm: string;
        namaDepo:   string;
        userName:   string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Laporan Hasil Stok Opname User") ?>}
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
                paragraph_4: {class: ".subtitle4Txt"},
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
                        td_7:  /*  7    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No. Batch") ?>},
                        td_8:  /*  8    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Stok Adm") ?>},
                        td_9:  /*  9    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Stok Fisik") ?>},
                        td_10: /* 10    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Harga Pokok") ?>},
                        td_11: /* 11    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nilai Fisik") ?>},
                        td_12: /* 12-13 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Selisih") ?>},
                    },
                    tr_2: {
                        td_1: /* 12 */ {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                        td_2: /* 13 */ {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
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
                title: tlm.stringRegistry._<?= $h("Total") ?>,
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Total Sebelum Stok Opname") ?>,
                    staticText: {class: ".totalSebelumStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Total Setelah Stok Opname") ?>,
                    staticText: {class: ".totalSetelahStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Total Selisih") ?>,
                    staticText: {class: ".totalSelisihStc"}
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
        /** @type {HTMLDivElement} */ const subtitle4Txt = divElm.querySelector(".subtitle4Txt");
        /** @type {HTMLDivElement} */ const totalSebelumStc = divElm.querySelector(".totalSebelumStc");
        /** @type {HTMLDivElement} */ const totalSetelahStc = divElm.querySelector(".totalSetelahStc");
        /** @type {HTMLDivElement} */ const totalSelisihStc = divElm.querySelector(".totalSelisihStc");

        const divWgt = spl.DinamicContainerWidget({
            element: divElm,
            /** @param {his.FatmaPharmacy.views.StokopnameUi.ViewOpnameUserGas.DivFields} data */
            loadData(data) {
                itemWgt.load(data.daftarBaris);

                subtitle1Txt.innerHTML = str._<?= $h("Tanggal: {{STR}}") ?>.replace("{{STR}}", userDatetime(data.opnameUser.tanggalAdm));
                subtitle2Txt.innerHTML = str._<?= $h("Gudang: {{STR}}") ?>.replace("{{STR}}", data.opnameUser.namaDepo);
                subtitle3Txt.innerHTML = str._<?= $h("User: {{STR}}") ?>.replace("{{STR}}", data.opnameUser.userName);
                subtitle4Txt.innerHTML = str._<?= $h("Tanggal Cetak: {{STR}}") ?>.replace("{{STR}}", nowVal("user"));
                totalSebelumStc.innerHTML = userFloat(data.totalSebelumStokOpname);
                totalSetelahStc.innerHTML = userFloat(data.totalSesudahStokOpname);
                totalSelisihStc.innerHTML = userFloat(data.totalSebelumStokOpname - data.totalSesudahStokOpname);
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
}
</script>

<table class="itemTbl">
    <?php
    $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
    $toUserFloat = Yii::$app->number->toUserFloat();
    $daftarOpnameUser = [];
    $daftarBaris = [];
    ?>
    <tbody>
    <?php foreach ($daftarBaris as $baris): ?>
        <?php if (isset($baris->kodeKelompok)): ?>
            <tr>
                <td><?= $baris->no ?></td>
                <td><?= $baris->kodeKelompok ?></td>
                <td><?= $baris->namaGas ?></td>
                <td><?= $baris->namaPabrik ?></td>
                <td><?= $baris->satuan ?></td>
                <td><?= $toUserDate($baris->tanggalKadaluarsa) ?></td>
                <td></td>
                <td class="text-right"><?= $toUserFloat($baris->stokAdm) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalJumlahFisik) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->hargaPokok) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalNilaiFisik) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalJumlahSelisih) ?></td>
                <td class="text-right"><?= $toUserFloat($baris->subtotalNilaiSelisih) ?></td>
            </tr>

        <?php elseif (isset($baris->i)): ?>
            <?php $opnameUser = $daftarOpnameUser[$baris->i] ?>
            <tr class="katalog">
                <td colspan="5"></td>
                <td><?= $baris->no ?></td>
                <td><?= $opnameUser->noBatch ?></td>
                <td></td>
                <td class="text-right"><?= $toUserFloat($opnameUser->stokFisik) ?></td>
                <td class="text-right"><?= $toUserFloat($opnameUser->hpItem) ?></td>
                <td class="text-right"><?= $toUserFloat($opnameUser->stokFisik * $opnameUser->hpItem) ?></td>
                <td colspan="2" class="<?= $opnameUser->kode ?>"></td>
            </tr>

        <?php elseif (isset($baris->subtotalSebelumStokOpname)): ?>
            <tr class="row_kelompok">
                <td colspan="8" class="nama_kelompok"></td>
                <td colspan="3" class="subtotal">
                    Subtotal sebelum stok opname<br/>
                    Subtotal setelah stok opname<br/>
                    Subtotal selisih
                </td>
                <td colspan="2" class="nilai_subtotal">
                    <?= $toUserFloat($baris->subtotalSebelumStokOpname) ?><br/>
                    <?= $toUserFloat($baris->subtotalSesudahStokOpname) ?><br/>
                    <?= $toUserFloat($baris->subtotalSelisih) ?>
                </td>
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
