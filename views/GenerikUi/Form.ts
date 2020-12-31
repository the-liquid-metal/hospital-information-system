<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\GenerikUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Generik/add.php the original file
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        string $addActionUrl,
        string $editActionUrl,
        string $subkelasTerapiAcplUrl,
        string $cekUnikKodeUrl,
        string $cekUnikNamaUrl,
        string $detail,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.GenerikUi.Form {
    export interface FormFields {
        id:               string;
        kode:             string;
        idSubkelasRerapi: string[];
        namaGenerik:      string;
    }

    export interface SubkelasTerapiFields {
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
            class: ".generikFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden: {class: ".idFld", name: "id"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Subkelas Terapi") ?>,
                        select: {class: ".idSubkelasRerapiFld", name: "idSubkelasRerapi[]"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Nama Generik") ?>,
                        input: {class: ".namaGenerikFld", name: "namaGenerik"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Add") ?>, rLabel: tlm.stringRegistry._<?= $h("Reset") ?>}
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

        const generikWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".generikFrm"),
            /** @param {his.FatmaPharmacy.views.GenerikUi.Form.FormFields} data */
            loadData(data) {
                idFld.value = data.id ?? "";
                kodeWgt.value = data.kode ?? "";
                idSubkelasRerapiWgt.value = data.idSubkelasRerapi ?? "";
                namaGenerikWgt.value = data.namaGenerik ?? "";
            },
            resetBtnId: false,
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._element.reset();
                    formTitleTxt.innerHTML = str._<?= $h("Add Generik") ?>;
                    this._actionUrl = "<?= $addActionUrl ?>";
                },
                edit(data) {
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("Edit Generik") ?>;
                    this._actionUrl = "<?= $editActionUrl ?>";
                }
            },
            onInit() {
                this.loadProfile("add");
            }
        });

        const idSubkelasRerapiWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idSubkelasRerapiFld"),
            errorRules: [{required: true}],
            valueField: "id",
            /** @param {his.FatmaPharmacy.views.GenerikUi.Form.SubkelasTerapiFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.GenerikUi.Form.SubkelasTerapiFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            searchField: ["nama", "kode"],
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $subkelasTerapiAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.GenerikUi.Form.SubkelasTerapiFields} */
                const obj = this.options[value];
                const id = idSubkelasRerapiWgt.dataset.no;
                const detail = '<?= $detail ?? "" ?>';

                if (detail == "generik") {
                    divElm.querySelector("#id_" + id).value = obj.id;
                }
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

        const namaGenerikWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".namaGenerikFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == idFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNamaUrl ?>",
            term: "value",
            additionalData: {field: "nama_generik"}
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(generikWgt, idSubkelasRerapiWgt, kodeWgt, namaGenerikWgt);
        tlm.app.registerWidget(this.constructor.widgetName, generikWgt);
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
