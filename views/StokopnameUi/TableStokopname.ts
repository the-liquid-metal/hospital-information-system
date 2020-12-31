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
final class TableStokopname
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $auditAccess,
        string $dataUrl,
        string $openOpnameUrl,
        string $belumInputViewWidgetId,
        string $detailOpnameViewWidgetId,
        string $opnameDepoViewWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.StokopnameUi.Table {
    export interface Fields {
        kode:           string;
        idDepo:         string;
        namaDepo:       string;
        tanggalAdm:     string;
        inputBy:        string;
        tanggalDokumen: string;
        updatedBy:      string;
        updatedTime:    string;
        statusOpname1:  string;
        statusOpname2:  string;
        verStokOpname:  string;
        noDokumen:      string;
        kodeRef:        string;
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

    static style = {
        [this.widgetName + " td:first-child"]: {
            _style: {width: "80px"},
        }
    };

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Stokopname") ?>}
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
                        td_1: {text: ""},
                        td_2: {text: tlm.stringRegistry._<?= $h("Unit") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Tanggal ADM") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("User Input") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Tanggal Input") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("User Update") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Update Terakhir") ?>},
                        td_8: {text: tlm.stringRegistry._<?= $h("Aktifkan Stok opname") ?>},
                        td_9: {text: tlm.stringRegistry._<?= $h("Status Close") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {drawCheckbox: checkbox, drawButton: button} = spl.TableDrawer;
        const str = tlm.stringRegistry;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kode, idDepo} = item;
                    const value = JSON.stringify({kode, idDepo});

                    const listBtn  = button({class: ".listBtn",  type: "danger",  icon: "list",   value, title: str._<?= $h("Daftar belum input") ?>});
                    const viewBtn  = button({class: ".viewBtn",  type: "warning", icon: "pencil", value, title: str._<?= $h("view opname") ?>});
                    const printBtn = button({class: ".printBtn", type: "success", icon: "print",  value, title: str._<?= $h("Cetak") ?>});
                    return listBtn + viewBtn + printBtn;
                }},
                2: {field: "namaDepo"},
                3: {field: "tanggalAdm", formatter: tlm.dateFormatter},
                4: {field: "inputBy"},
                5: {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                6: {field: "updatedBy", visible: access.audit},
                7: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter},
                8: {formatter(unused, item) {
                    const {statusOpname1: so1, statusOpname2: so2, verStokopname: vso, kode, idDepo, noDokumen, kodeRef, tanggalAdm} = item;
                    const value = JSON.stringify({kode, idDepo, noDokumen, kodeRef, tanggalAdm});

                    if      (so1 == "1" /*---------------------*/) return checkbox({class: ".openOpnameFld", checked: true,         disabled: true,  title: str._<?= $h("Dibuka") ?>});
                    else if (so1 == "0" && so2 == "1" && vso == 0) return checkbox({class: ".openOpnameFld", checked: false, value, disabled: false, title: str._<?= $h("Ditutup") ?>});
                    else    /*------------------- --------------*/ return checkbox({class: ".openOpnameFld", checked: false,        disabled: true,  title: str._<?= $h("Ditutup") ?>});
                }},
                9: {formatter(unused, item) {
                    const {statusOpname1, kode} = item;
                    const statusIsOne = statusOpname1 == "1";
                    const title = statusIsOne ? str._<?= $h("Stok Opname Dibuka") ?> : str._<?= $h("Stok Opname Ditutup") ?>;
                    return checkbox({class: ".closeOpnameFld", title, checked: !statusIsOne, disabled: true, value: kode});
                }}
            }
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const listBtn = event.target;
            if (!listBtn.matches(".listBtn")) return;

            const {kode, idDepo} = JSON.parse(listBtn.value);
            const widget = tlm.app.getWidget("_<?= $belumInputViewWidgetId ?>");
            widget.show();
            widget.loadData({kodeRef: kode, idDepo}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const widget = tlm.app.getWidget("_<?= $detailOpnameViewWidgetId ?>");
            widget.show();
            widget.loadData({kode: JSON.parse(viewBtn.value).kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const {kode, idDepo} = JSON.parse(printBtn.value);
            const widget = tlm.app.getWidget("_<?= $opnameDepoViewWidgetId ?>");
            widget.show();
            widget.loadData({kodeRef: kode, idDepo}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const openOpnameFld = event.target;
            if (!openOpnameFld.matches(".openOpnameFld")) return;
            if (!openOpnameFld.checked) return;

            if (confirm(str._<?= $h("Apakah Anda yakin ingin membuka stok opname pada unit ini?") ?>)) {
                const {kode, idDepo, noDokumen, kodeRef, tanggalAdm} = JSON.parse(openOpnameFld.value);
                $.post({
                    url: "<?= $openOpnameUrl ?>",
                    data: {kode, idDepo, noDokumen, tanggalAdm, kodeSo: kodeRef},
                    success(data) {
                        if (data != 1) return;

                        openOpnameFld.disabled = true;
                        closest(openOpnameFld, "tr").querySelector(".closeOpnameFld").checked = false;
                        alert(str._<?= $h("Sukses mengaktifkan stok opname untuk depo ini.") ?>);
                    },
                    error() {
                        alert(str._<?= $h("Gagal mengaktifkan stok opname untuk depo ini.") ?>);
                        openOpnameFld.checked = false;
                    }
                });
            } else {
                openOpnameFld.checked = false;
            }
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
