<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Pemesanan;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pemesanan/listpo.php the original file
 */
final class ListPo
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pemesanan.ListPo {
    export interface TableFields {
        kode:               string;
        noDokumen:          string;
        namaPemasok:        string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        tanggalTempoKirim:  string;
        nilaiAkhir:         string;
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
        row_3: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. PO") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Anggaran") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Tempo Kirim") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Nilai Akhir") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const {numToShortMonthName: nToS, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            noMatchesStr: str._<?= $h('PO/DO sudah "close" / belum "verifikasi revisi". Silahkan Hubungi Satker terkait') ?>,
            columns: {
                1: {formatter(unused, {kode}) {
                    return draw({class: ".btn_select", type: "warning", value: kode, text: str._<?= $h("Pilih") ?>});
                }},
                2: {field: "noDokumen"},
                3: {field: "namaPemasok"},
                4: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                5: {field: "tanggalTempoKirim", formatter: tlm.dateFormatter},
                6: {field: "nilaiAkhir", formatter: tlm.floatFormatter}
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
