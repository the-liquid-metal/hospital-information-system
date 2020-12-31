<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\JenisBarangUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Jenisbarang/add.php the original file
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
        string $cekUnikKodeUrl,
        string $cekUnikNamaUrl,
        string $jenisObatSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.JenisBarangUi.Form {
    export interface FormFields {
        id:        string;
        action:    string;
        kode:      string;
        kodeGroup: string;
        jenisObat: string;
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
            class: ".jenisBarangFrm",
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
                        label: tlm.stringRegistry._<?= $h("Jenis Anggaran") ?>,
                        select: {class: ".kodeGroupFld", name: "kodeGroup"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Jenis Barang") ?>,
                        input: {class: ".jenisObatFld", name: "jenisObat"}
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

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const idFld = divElm.querySelector(".idFld");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLSelectElement} */ const kodeGroupFld = divElm.querySelector(".kodeGroupFld");

        tlm.app.registerSelect("_<?= $jenisObatSelect ?>", kodeGroupFld);
        this._selects.push(kodeGroupFld);

        const jenisBarangWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".jenisBarangFrm"),
            /** @param {his.FatmaPharmacy.views.JenisBarangUi.Form.FormFields} data */
            loadData(data) {
                idFld.value = data.id ?? "";
                actionFld.value = data.action ?? "";
                kodeWgt.value = data.kode ?? "";
                kodeGroupFld.value = data.kodeGroup ?? "";
                jenisObatWgt.value = data.jenisObat ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.loadData({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Jenis Barang") ?>;
                },
                edit(data, fromServer) {
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.loadData(data, fromServer);
                    formTitleTxt.innerHTML = str._<?= $h("Ubah Jenis Barang") ?>;
                }
            },
            onInit() {
                this.loadProfile("add");
            },
            resetBtnId: false,
            dataUrl: "<?= $dataUrl ?>",
        });

        const kodeWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".kodeFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == divElm.querySelector(".idFld").value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikKodeUrl ?>",
            term: "value",
            additionalData: {field: "kode"}
        });

        const jenisObatWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".jenisObatFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == divElm.querySelector(".idFld").value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNamaUrl ?>",
            term: "value",
            additionalData: {field: "jenis_obat"}
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(jenisBarangWgt, kodeWgt, jenisObatWgt);
        tlm.app.registerWidget(this.constructor.widgetName, jenisBarangWgt);
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
