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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Brand/index.php the original file
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
namespace his.FatmaPharmacy.views.BrandUi.Table {
    export interface Fields {
        id:          string;
        kode:        string;
        namaGenerik: string;
        namaDagang:  string;
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
            delete: JSON.parse(`<?= json_encode($deleteAccess) ?>`),
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Brand") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            widthTable: {
                class: ".daftarBrandTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Nama Generik") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Nama Dagang") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("User Ubah") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Tanggal Ubah") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const daftarBrandWgt = new spl.TableWidget({
            element: divElm.querySelector(".daftarBrandTbl"),
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
                2: {field: "namaGenerik"},
                3: {field: "namaDagang"},
                4: {field: "updatedBy", visible: access.audit},
                5: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter}
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(daftarBrandWgt);
        tlm.app.registerWidget(this.constructor.widgetName, daftarBrandWgt);
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
