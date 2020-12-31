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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/index.php the original file
 */
final class TableOpnameUser
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $opnameUserGasViewWidgetId,
        string $opnameUserViewWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.StokopnameUi.OpnameUserTable {
    export interface Fields {
        kode:                 string;
        noDokumen:            string;
        idDepo:               string;
        namaDepo:             string;
        idUser:               string;
        namaUser:             string;
        kodeAdm:              string;
        tanggalAdm:           string;
        verStokOpname:        string;
        verTanggalStokOpname: string;
        keterangan:           string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName + " td:first-child"]: {
            _style: {width: "80px"},
        }
    };

    _structure = {
        row_1: {
            widthColumn: {
                header3: {text: tlm.stringRegistry._<?= $h("Stokopname") ?>}
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
                        td_1: {title: ""},
                        td_2: {text: tlm.stringRegistry._<?= $h("Kode Opname") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Unit") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("User") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Kode ADM") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Tanggal ADM") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Status Close") ?>},
                        td_8: {text: tlm.stringRegistry._<?= $h("Tanggal CLose") ?>},
                        td_9: {text: tlm.stringRegistry._<?= $h("Keterangan") ?>},
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

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kode, idUser, idDepo} = item;
                    return draw({class: ".viewBtn", type: "success", icon: "print", value: JSON.stringify({kode, idUser, idDepo}), title: str._<?= $h("Ganti") ?>});
                }},
                2: {field: "noDokumen"},
                3: {field: "namaDepo"},
                4: {field: "namaUser"},
                5: {field: "kodeAdm"},
                6: {field: "tanggalAdm", formatter: tlm.dateFormatter},
                7: {formatter(unused, {verStokOpname}) {
                    const closeStr = str._<?= $h("Close") ?>;
                    const activeStr = str._<?= $h("Active") ?>;
                    return verStokOpname == "1" ? `<p style="color:red">${closeStr}</p>` : `<p style="color:blue">${activeStr}</p>`;
                }},
                8: {field: "verTanggalStokOpname", formatter: tlm.dateFormatter},
                9: {field: "keterangan"}
            }
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const {kode, idUser, idDepo} = JSON.parse(viewBtn.value);
            const widgetId = idDepo == "60" ? "_<?= $opnameUserGasViewWidgetId ?>" : "_<?= $opnameUserViewWidgetId ?>";
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kodeRef: kode, idUser, idDepo}, true);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(tableWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tableWgt);
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
