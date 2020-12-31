<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\KemasanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Kemasan/index.php the original file
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
namespace his.FatmaPharmacy.views.KemasanUi.Table {
    export interface FormFields {
        kode:        string;
        namaKemasan: string;
    }

    export interface TableFields {
        id:          string;
        kode:        string;
        namaKemasan: string;
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
     * @returns {{edit: boolean, delete: boolean}}
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Kemasan") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Nama Kemasan") ?>,
                        input: {class: ".namaKemasanFld"}
                    },
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
                        td_2: {text: tlm.stringRegistry._<?= $h("Nama Kemasan") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Updated User") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Last Update") ?>},
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
        /** @type {HTMLInputElement} */ const namaKemasanFld = divElm.querySelector(".namaKemasanFld");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.KemasanUi.Table.FormFields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                namaKemasanFld.value = data.namaKemasan ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {kode: kodeFld.value, namaKemasan: namaKemasanFld.value}
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
                2: {field: "namaKemasan"},
                3: {field: "updatedBy", visible: access.audit},
                4: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter}
            }
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
