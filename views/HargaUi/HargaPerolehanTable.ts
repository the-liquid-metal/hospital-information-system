<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\HargaUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/???.php the original file
 */
final class HargaPerolehanTable
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $auditAccess,
        string $dataUrl,
        string $aktivasiUrl,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.HargaUi.HargaPerolehanTable {
    export interface FormFields {
        idKatalog:      string;
        namaSediaan:    string;
        noDokumen:      string;
        namaPemasok:    string;
        tanggalHp:      string;
        hnaItem:        string;
        hpItem:         string;
        phja:           string;
        tanggalAktifHp: string;
    }

    export interface ItemFields {
        idKatalog:            string;
        namaSediaan:          string;
        noDokumen:            string;
        namaPemasok:          string;
        tanggalHp:            string;
        hnaItem:              string;
        hpItem:               string;
        phja:                 string;
        hargaJualAkhirItem:   string;
        tanggalAktifHp:       string;
        statusHargaJualAkhir: string;
        keterangan:           string;
        updatedBy:            string;
        updatedTime:          string;
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

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Harga Perolehan") ?>}
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
                        input: {class: ".idKatalogFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Katalog") ?>,
                        input: {class: ".namaSediaanFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Terima") ?>,
                        input: {class: ".noDokumenFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Ver.") ?>,
                        input: {class: ".tanggalHpFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("HNA") ?>,
                        input: {class: ".hnaItemFld"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("HP") ?>,
                        input: {class: ".hpItemFld"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("PHJA") ?>,
                        input: {class: ".phjaFld"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Aktif HP") ?>,
                        input: {class: ".tanggalAktifHpFld"}
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
                        td_1:  {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Nama Katalog") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("No. Terima") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Tanggal Ver.") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("HNA") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("HP") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("PHJA") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("HJA") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Aktif HP") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Status Aktif") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Update User") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Update") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Keterangan") ?>}
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */ const idKatalogFld = divElm.querySelector(".idKatalogFld");
        /** @type {HTMLInputElement} */ const namaSediaanFld = divElm.querySelector(".namaSediaanFld");
        /** @type {HTMLInputElement} */ const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */ const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLInputElement} */ const tanggalHpFld = divElm.querySelector(".tanggalHpFld");
        /** @type {HTMLInputElement} */ const hnaItemFld = divElm.querySelector(".hnaItemFld");
        /** @type {HTMLInputElement} */ const hpItemFld = divElm.querySelector(".hpItemFld");
        /** @type {HTMLInputElement} */ const phjaFld = divElm.querySelector(".phjaFld");
        /** @type {HTMLInputElement} */ const tanggalAktifHpFld = divElm.querySelector(".tanggalAktifHpFld");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.HargaUi.HargaPerolehanTable.FormFields} data */
            loadData(data) {
                idKatalogFld.value = data.idKatalog ?? "";
                namaSediaanFld.value = data.namaSediaan ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                tanggalHpFld.value = data.tanggalHp ?? "";
                hnaItemFld.value = data.hnaItem ?? "";
                hpItemFld.value = data.hpItem ?? "";
                phjaFld.value = data.phja ?? "";
                tanggalAktifHpFld.value = data.tanggalAktifHp ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        idKatalog: idKatalogFld.value,
                        namaSediaan: namaSediaanFld.value,
                        noDokumen: noDokumenFld.value,
                        namaPemasok: namaPemasokFld.value,
                        tanggalHp: tanggalHpFld.value,
                        hnaItem: hnaItemFld.value,
                        hpItem: hpItemFld.value,
                        phja: phjaFld.value,
                        tanggalAktifHp: tanggalAktifHpFld.value,
                    }
                });
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1:  {field: "idKatalog"},
                2:  {field: "namaSediaan"},
                3:  {field: "noDokumen"},
                4:  {field: "namaPemasok"},
                5:  {field: "tanggalHp", formatter: tlm.dateFormatter},
                6:  {field: "hnaItem"},
                7:  {field: "hpItem"},
                8:  {field: "phja"},
                9:  {field: "hargaJualAkhirItem"},
                10: {field: "tanggalAktifHp", formatter: tlm.dateFormatter},
                11: {field: "statusHargaJualAkhir"},
                12: {field: "updatedBy", visible: access.audit},
                13: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter},
                14: {field: "keterangan"}
            },
        });

        // TODO: js: uncategorized: determine whether this statement must be deleted or restored the missing element (".changeAktifFld")
        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const changeAktifFld = event.target;
            if (!changeAktifFld.matches(".changeAktifFld")) return;
            if (!changeAktifFld.checked) return;

            if (confirm(str._<?= $h("Checklist Nama Barang dengan jenis harga ini akan mengganti harga jual aktif. Apakah Anda yakin?") ?>)) {
                const {kode, reff} = changeAktifFld.dataset;
                $.post({
                    url: "<?= $aktivasiUrl ?>",
                    data: {kodeBarang: kode, kodeRef: reff},
                    success(data) {
                        if (data == '1') {
                            const elm2 = divElm.querySelector('.' + kode);
                            elm2.disabled = false;
                            elm2.checked = false;
                            changeAktifFld.checked = true;
                            changeAktifFld.disabled = true;
                            alert(str._<?= $h("Berhasil ubah harga jual aktif.") ?>);
                        } else {
                            changeAktifFld.checked = false;
                            alert(str._<?= $h("Gagal ubah perubahan harga. Silahkan hubungi tim IT.") ?>);
                        }
                    }
                });
            } else {
                changeAktifFld.checked = false;
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
