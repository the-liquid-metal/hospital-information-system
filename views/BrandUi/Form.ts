<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\BrandUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Brand/add.php the original file
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
        string $generikAcplUrl,
        string $cekUnikKodeUrl,
        string $cekUnikNamaUrl,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.BrandUi.Form {
    export interface FormFields {
        id:         string;
        action:     string;
        kode:       string;
        idGenerik:  string;
        namaDagang: string;
    }

    export interface GenerikFields {
        id:   string;
        nama: string;
        kode: string;
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
        row: {
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
            class: ".brandFrm",
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
                        label: tlm.stringRegistry._<?= $h("Nama Generik") ?>,
                        select: {class: ".idGenerikFld", name: "idGenerik"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Nama Dagang") ?>,
                        input: {class: ".namaDagangFld", name: "namaDagang"}
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
        /** @type {HTMLInputElement} */ const actionFld = divElm.querySelector(".actionFld");

        const brandWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".brandFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.BrandUi.Form.FormFields} data */
            loadData(data) {
                idFld.value = data.id ?? "";
                actionFld.value = data.action ?? "";
                kodeWgt.value = data.kode ?? "";
                idGenerikWgt.value = data.idGenerik ?? "";
                namaDagangWgt.value = data.namaDagang ?? "";
            },
            resetBtnId: false,
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._element.reset();
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Brand") ?>;
                    this._actionUrl = "<?= $addActionUrl ?>";
                },
                edit(data) {
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("Ubah Brand") ?>;
                    this._actionUrl = "<?= $editActionUrl ?>";
                }
            },
            onInit() {
                this.loadProfile("add");
            }
        });

        const idGenerikWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idGenerikFld"),
            errorRules: [{required: true}],
            maxItems: 1,
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.BrandUi.Form.GenerikFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.BrandUi.Form.GenerikFields} data */
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

        const namaDagangWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".namaDagangFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == idFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNamaUrl ?>",
            term: "value",
            additionalData: {field: "nama_dagang"}
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(brandWgt, idGenerikWgt, kodeWgt, namaDagangWgt);
        tlm.app.registerWidget(this.constructor.widgetName, brandWgt);
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
