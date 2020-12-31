<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PembelianUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pembelian/index_realisasipl.php the original file
 */
final class TableRealisasiPl
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl, string $actionUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $daftarField = [ // sisa setelah cleanup (tak terpakai (?))
            "harga_anggaran" => "Total Anggaran",
            "harga_realisasi" => "Total Realisasi",
            "anggaran_sisa" => "Sisa Anggaran",
        ];
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pembelian.IndexRealisasiPl {
    export interface TableFields {
        noDokumen:         string;
        tanggalJatuhTempo: string;
        namaPemasok:       string;
        namaSediaan:       string;
        namaPabrik:        string;
        kemasan:           string;
        satuan:            string;
        idRefKatalog:      string;
        jumlahRencana:     string;
        jumlahPl:          string;
        hargaPl:           string;
        jumlahTerima:      string;
        hargaTerima:       string;
        jumlahSisa:        string;
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
            action: "<?= $actionUrl ?>",
            hidden_1: {name: "kode", value: "<?= $_POST['kode'] ?>"},
            hidden_2: {name: "type", value: "exportToExcel"},
            hidden_3: {name: "submit", value: "realisasi_pl"},
            button: {class: ".btn-warning", text: tlm.stringRegistry._<?= $h("Export To Excel") ?>}
        },
        row_3: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr_1: {
                        td_1:  /*  1    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("No. PL Pembelian") ?>},
                        td_2:  /*  2    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Tempo Kontrak") ?>},
                        td_3:  /*  3    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_4:  /*  4    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                        td_5:  /*  5    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                        td_6:  /*  6    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_7:  /*  7    */ {rowspan: 2, text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                        td_8:  /*  8-9  */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Perencanaan") ?>},
                        td_9:  /* 10-12 */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Kontrak") ?>},
                        td_10: /* 13-15 */ {colspan: 3, text: tlm.stringRegistry._<?= $h("Realisasi") ?>},
                        td_11: /* 16-17 */ {colspan: 2, text: tlm.stringRegistry._<?= $h("Sisa") ?>},
                    },
                    tr_2: {
                        td_1:  /* 8  */ {text: tlm.stringRegistry._<?= $h("Ref. Katalog") ?>},
                        td_2:  /* 9  */ {text: tlm.stringRegistry._<?= $h("Volume (Item)") ?>},
                        td_3:  /* 10 */ {text: tlm.stringRegistry._<?= $h("Volume (Item)") ?>},
                        td_4:  /* 11 */ {text: tlm.stringRegistry._<?= $h("Harga (Satuan)") ?>},
                        td_5:  /* 12 */ {text: tlm.stringRegistry._<?= $h("Total Harga / Anggaran") ?>},
                        td_6:  /* 13 */ {text: tlm.stringRegistry._<?= $h("Volume (Item)") ?>},
                        td_7:  /* 14 */ {text: tlm.stringRegistry._<?= $h("Harga (Satuan)") ?>},
                        td_8:  /* 15 */ {text: tlm.stringRegistry._<?= $h("Harga Realisasi") ?>},
                        td_9:  /* 16 */ {text: tlm.stringRegistry._<?= $h("Volume (Item)") ?>},
                        td_10: /* 17 */ {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {toUserInt: userInt, toUserFloat: userFloat} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const styler = (unused, item) => {
            const css = item.jumlahPl ? {} : {"background-color": "#bbb", color: "#fff"};
            return {css, class: "", attribute: ""};
        };

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1:  {field: "noDokumen"},
                2:  {field: "tanggalJatuhTempo", formatter: tlm.dateFormatter},
                3:  {field: "namaPemasok"},
                4:  {field: "namaSediaan"},
                5:  {field: "namaPabrik"},
                6:  {field: "kemasan"},
                7:  {field: "satuan"},
                8:  {field: "idRefKatalog"},
                9:  {field: "jumlahRencana", formatter: tlm.intFormatter},
                10: {field: "jumlahPl", cellStyle: styler, formatter: tlm.intFormatter},
                11: {field: "hargaPl", formatter: tlm.floatFormatter},
                12: {cellStyle: styler, formatter(unused, item) {
                    const {jumlahPl, jumlahRencana, hargaPl} = item;
                    return userFloat(jumlahPl || jumlahRencana) * hargaPl;
                }},
                13: {field: "jumlahTerima", formatter: tlm.intFormatter},
                14: {field: "hargaTerima", formatter: tlm.floatFormatter},
                15: {formatter(unused, item) {
                    const {jumlahTerima, hargaTerima} = item;
                    return userInt(jumlahTerima * hargaTerima);
                }},
                16: {field: "jumlahSisa", formatter: tlm.intFormatter},
                17: {formatter(unused, item) {
                    const {jumlahPl, jumlahRencana, hargaPl, jumlahTerima, hargaTerima} = item;
                    const harga = (jumlahPl || jumlahRencana) * hargaPl;
                    return userFloat(harga - (jumlahTerima * hargaTerima));
                }}
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, itemWgt);
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
