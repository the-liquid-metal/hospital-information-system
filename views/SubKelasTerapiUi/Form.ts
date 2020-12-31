<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\SubKelasTerapiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Subkelasterapi/add.php the original file
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        string $dataUrl,
        string $addActionUrl,
        string $editActionUrl,
        string $kelasTerapiAcplUrl,
        string $cekUnikKodeUrl,
        string $cekUnikNamaUrl,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.SubKelasTerapiUi.Form {
    export interface FormFields {
        id:             string;
        action:         string;
        kode:           string;
        idKelasTerapi:  string;
        subkelasTerapi: string;
    }

    export interface KelasTerapiFields {
        id:   string;
        kode: string;
        nama: string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{add: boolean, edit: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
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
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".subkelasTerapiFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".idFld", name: "id"},
                    hidden_2: {class: ".actionFld", name: "action"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kelas Terapi") ?>,
                        select: {class: ".idKelasTerapiFld", name: "idKelasTerapi"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Subkelas Terapi") ?>,
                        input: {class: ".namaFld", name: "subkelasTerapi"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */   const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */ const idFld = divElm.querySelector(".idFld");
        /** @type {HTMLInputElement} */ const actionFld = divElm.querySelector(".actionFld");

        const subkelasTerapiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".subkelasTerapiFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.SubKelasTerapiUi.Form.FormFields} data */
            loadData(data) {
                idFld.value = data.id ?? "";
                actionFld.value = data.action ?? "";
                kodeWgt.value = data.kode ?? "";
                idKelasTerapiWgt.value = data.idKelasTerapi ?? "";
                namaWgt.value = data.subkelasTerapi ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.loadData({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Subkelas Terapi") ?>;
                },
                edit(data, fromServer) {
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.loadData(data, fromServer);
                    formTitleTxt.innerHTML = str._<?= $h("Ubah Subkelas Terapi") ?>;
                }
            },
            onInit() {
                this.loadProfile("add");
            },
            resetBtnId: false,
        });

        const idKelasTerapiWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idKelasTerapiFld"),
            maxItems: 1,
            valueField: "id",
            /** @param {his.FatmaPharmacy.views.SubKelasTerapiUi.Form.KelasTerapiFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.SubKelasTerapiUi.Form.KelasTerapiFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            searchField: ["nama", "kode"],
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $kelasTerapiAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const kodeWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".kodeFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == idFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikKodeUrl ?>",
            term: "value",
            additionalData: {field: "kode"}
        });

        const namaWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".namaFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == idFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNamaUrl ?>",
            term: "value",
            additionalData: {field: "subkelasTerapi"}
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(subkelasTerapiWgt, idKelasTerapiWgt, kodeWgt, namaWgt);
        tlm.app.registerWidget(this.constructor.widgetName, subkelasTerapiWgt);
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
