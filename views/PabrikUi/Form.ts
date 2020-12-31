<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PabrikUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pabrik/add.php the original file
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
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.PabrikUi.Form {
    export interface FormFields {
        id:            string;
        action:        string;
        kode:          string;
        npwp:          string;
        namaPabrik:    string;
        alamat:        string;
        kota:          string;
        kodepos:       string;
        telefon:       string;
        fax:           string;
        email:         string;
        namaKontak:    string;
        telefonKontak: string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{add: boolean}}
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
            class: ".pabrikFrm",
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
                        label: tlm.stringRegistry._<?= $h("NPWP") ?>,
                        input: {class: ".npwpFld", name: "npwp"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Nama Pabrik") ?>,
                        input: {class: ".namaPabrikFld", name: "namaPabrik"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Alamat") ?>,
                        input: {class: ".alamatFld", name: "alamat"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Kota") ?>,
                        input: {class: ".kotaFld", name: "kota"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Kode POS") ?>,
                        input: {class: ".kodeposFld", name: "kodepos"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Telefon Pabrik") ?>,
                        input: {class: ".telefonFld", name: "telefon"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Fax Pabrik") ?>,
                        input: {class: ".faxFld", name: "fax"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Email Pabrik") ?>,
                        input: {class: ".emailFld", name: "email"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Nama Kontak") ?>,
                        input: {class: ".namaKontakFld", name: "namaKontak"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Telefon Kontak") ?>,
                        input: {class: ".telefonKontakFld", name: "telefonKontak"}
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
        /** @type {HTMLInputElement} */ const npwpFld = divElm.querySelector(".npwpFld");
        /** @type {HTMLInputElement} */ const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */ const kotaFld = divElm.querySelector(".kotaFld");
        /** @type {HTMLInputElement} */ const kodeposFld = divElm.querySelector(".kodeposFld");
        /** @type {HTMLInputElement} */ const telefonFld = divElm.querySelector(".telefonFld");
        /** @type {HTMLInputElement} */ const faxFld = divElm.querySelector(".faxFld");
        /** @type {HTMLInputElement} */ const emailFld = divElm.querySelector(".emailFld");
        /** @type {HTMLInputElement} */ const namaKontakFld = divElm.querySelector(".namaKontakFld");
        /** @type {HTMLInputElement} */ const telefonKontakFld = divElm.querySelector(".telefonKontakFld");

        const pabrikWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".pabrikFrm"),
            /** @param {his.FatmaPharmacy.views.PabrikUi.Form.FormFields} data */
            loadData(data) {
                idFld.value = data.id ?? "";
                actionFld.value = data.action ?? "";
                kodeWgt.value = data.kode ?? "";
                npwpFld.value = data.npwp ?? "";
                namaPabrikWgt.value = data.namaPabrik ?? "";
                alamatFld.value = data.alamat ?? "";
                kotaFld.value = data.kota ?? "";
                kodeposFld.value = data.kodepos ?? "";
                telefonFld.value = data.telefon ?? "";
                faxFld.value = data.fax ?? "";
                emailFld.value = data.email ?? "";
                namaKontakFld.value = data.namaKontak ?? "";
                telefonKontakFld.value = data.telefonKontak ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.loadData({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Pabrik") ?>;
                },
                edit(data, fromServer) {
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.loadData(data, fromServer);
                    formTitleTxt.innerHTML = str._<?= $h("Ubah Pabrik") ?>;
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
                {ajax: data => !data || data.id == idFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikKodeUrl ?>",
            term: "value",
            additionalData: {field: "kode"}
        });

        const namaPabrikWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".namaPabrikFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == idFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNamaUrl ?>",
            term: "value",
            additionalData: {field: "namaPabrik"}
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(pabrikWgt, kodeWgt, namaPabrikWgt);
        tlm.app.registerWidget(this.constructor.widgetName, pabrikWgt);
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
