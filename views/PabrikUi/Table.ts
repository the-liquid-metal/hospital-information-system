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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Pabrik/index.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $deleteAccess,
        array  $auditAccess,
        string $dataUrl,
        string $deleteUrl,
        string $companionFormId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.PabrikUi.Table {
    export interface FormFields {
        kode:        string;
        namaPabrik:  string;
        npwp:        string;
        alamat:      string;
        telefon:     string;
        fax:         string;
        email:       string;
    }

    export interface TableFields {
        id:          string;
        kode:        string;
        namaPabrik:  string;
        npwp:        string;
        alamat:      string;
        telefon:     string;
        fax:         string;
        email:       string;
        updatedBy:   string;
        updatedTime: string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean, delete: boolean, audit: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            delete: JSON.parse(`<?=json_encode($deleteAccess) ?>`),
            audit: JSON.parse(`<?= json_encode($auditAccess) ?>`),
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Pabrik") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".saringFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode") ?>,
                        input: {class: ".kodeFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Pabrik") ?>,
                        input: {class: ".namaPabrikFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("NPWP") ?>,
                        input: {class: ".npwpFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Alamat") ?>,
                        input: {class: ".alamatFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Telefon") ?>,
                        input: {class: ".telefonFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Fax") ?>,
                        input: {class: ".faxFld"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Email") ?>,
                        input: {class: ".emailFld"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            }
        },
        row_3: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_4: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Nama Pabrik") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("NPWP") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Alamat") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Telepon") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Fax") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Email") ?>},
                        td_8: {text: tlm.stringRegistry._<?= $h("Updated User") ?>},
                        td_9: {text: tlm.stringRegistry._<?= $h("Last Update") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */ const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */ const namaPabrikFld = divElm.querySelector(".namaPabrikFld");
        /** @type {HTMLInputElement} */ const npwpFld = divElm.querySelector(".npwpFld");
        /** @type {HTMLInputElement} */ const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */ const telefonFld = divElm.querySelector(".telefonFld");
        /** @type {HTMLInputElement} */ const faxFld = divElm.querySelector(".faxFld");
        /** @type {HTMLInputElement} */ const emailFld = divElm.querySelector(".emailFld");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.PabrikUi.Table.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                namaPabrikFld.value = data.namaPabrik ?? "";
                npwpFld.value = data.npwp ?? "";
                alamatFld.value = data.alamat ?? "";
                telefonFld.value = data.telefon ?? "";
                faxFld.value = data.fax ?? "";
                emailFld.value = data.email ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        kode: kodeFld.value,
                        namaPabrik: namaPabrikFld.value,
                        npwp: npwpFld.value,
                        alamat: alamatFld.value,
                        telefon: telefonFld.value,
                        fax: faxFld.value,
                        email: emailFld.value,
                    }
                });
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            idField: "id",
            override: {
                ...spl.TablePlugins.crudTable,
                showEditButton: access.edit,
                showDeleteButton: access.delete,
                companionFormId: "#<?= $companionFormId ?>",
                deleteUrl: "<?= $deleteUrl ?>",
            },
            columns: {
                1: {field: "kode"},
                2: {field: "namaPabrik"},
                3: {field: "npwp"},
                4: {field: "alamat"},
                5: {field: "telefon"},
                6: {field: "fax"},
                7: {field: "email"},
                8: {field: "updatedBy", visible: access.audit},
                9: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter}
            },
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, saringWgt);
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
