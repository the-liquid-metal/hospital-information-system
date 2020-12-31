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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/activation.php the original file
 */
final class FormAktivasi
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Stokopname.Activation {
    export interface FormFields {
        id:             string|"in"|"out";
        action:         string|"x" |"out";
        kode:           string|"in"|"out";
        tanggalAdm:     string|"in"|"out";
        tanggalDokumen: string|"in"|"out";
        tanggalMulai:   string|"in"|"out";
        tanggalSelesai: string|"in"|"out";
        aktifasi:       string|"in"|"out";
        keterangan:     string|"in"|"out";
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".aktivasiStokOpnameFrm",
            hidden_1: {class: ".idFld", name: "id"},
            hidden_2: {class: ".actionFld", name: "action"},
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal ADM") ?>,
                        input: {class: ".tanggalAdmFld", name: "tanggalAdm"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Aktif Stok Opname") ?>,
                        input: {class: ".tanggalMulaiFld", name: "tanggalMulai"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                        input: {class: ".tanggalSelesaiFld", name: "tanggalSelesai"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Aktifasi Barang") ?>,
                        select: {
                            class: ".aktifasiFld",
                            name: "aktifasi",
                            option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Tidak Aktif") ?>},
                            option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Aktif") ?>}
                        }
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan"}
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

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */    const idFld = divElm.querySelector(".idFld");
        /** @type {HTMLInputElement} */    const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */    const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLSelectElement} */   const aktifasiFld = divElm.querySelector(".aktifasiFld");
        /** @type {HTMLTextAreaElement} */ const keteranganFld = divElm.querySelector(".keteranganFld");

        const aktivasiStokOpnameWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".aktivasiStokOpnameFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.Stokopname.Activation.FormFields} data */
            loadData(data) {
                idFld.value = data.id ?? "";
                kodeFld.value = data.kode ?? "";
                tanggalAdmWgt.value = data.tanggalAdm ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                tanggalMulaiWgt.value = data.tanggalMulai ?? "";
                tanggalSelesaiWgt.value = data.tanggalSelesai ?? "";
                aktifasiFld.value = data.aktifasi ?? "";
                keteranganFld.value = data.keterangan ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this.loadData({});
                    actionFld.value = "add";
                },
                edit(data) {
                    this.loadData(data);
                    actionFld.value = "edit";
                },
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalSelesaiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalSelesaiFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalMulaiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalMulaiFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalAdmWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAdmFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(aktivasiStokOpnameWgt, tanggalDokumenWgt, tanggalSelesaiWgt, tanggalMulaiWgt, tanggalAdmWgt);
        tlm.app.registerWidget(this.constructor.widgetName, aktivasiStokOpnameWgt);
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
