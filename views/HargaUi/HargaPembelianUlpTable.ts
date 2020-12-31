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
final class HargaPembelianUlpTable
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
namespace his.FatmaPharmacy.views.HargaUi.Table {
    export interface FormFields {
        jenisHarga:   string;
        idKatalog:    string;
        namaSediaan:  string;
        namaPabrik:   string;
        namaPemasok:  string;
    }

    export interface TableFields {
        jenisHarga:   string;
        idKatalog:    string;
        namaSediaan:  string;
        namaPabrik:   string;
        namaPemasok:  string;
        kemasanBesar: string;
        isiKemasan:   string;
        hargaKemasan: string;
        hargaItem:    string;
        diskonItem:   string;
        updatedBy:    string;
        updatedTime:  string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Harga Pembelian ULP") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        input: {class: ".jenisHargaFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kode") ?>,
                        input: {class: ".idKatalogFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Nama Sediaan") ?>,
                        input: {class: ".namaSediaanFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama Pabrik") ?>,
                        input: {class: ".namaPabrikFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
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
                        td_1:  {text: tlm.stringRegistry._<?= $h("Jenis Harga") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Supplier") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Harga Kemasan") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Harga Satuan") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Updated User") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Updated") ?>},
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

        /** @type {HTMLInputElement} */ const jenisHargaFld = divElm.querySelector(".jenisHargaFld");
        /** @type {HTMLInputElement} */ const idKatalogFld = divElm.querySelector(".idKatalogFld");
        /** @type {HTMLInputElement} */ const namaSediaanFld = divElm.querySelector(".namaSediaanFld");
        /** @type {HTMLInputElement} */ const namaPabrikFld = divElm.querySelector(".namaPabrikFld");
        /** @type {HTMLInputElement} */ const namaPemasokFld = divElm.querySelector(".namaPemasokFld");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.HargaUi.Table.FormFields} data */
            loadData(data) {
                jenisHargaFld.value = data.jenisHarga;
                idKatalogFld.value = data.idKatalog;
                namaSediaanFld.value = data.namaSediaan;
                namaPabrikFld.value = data.namaPabrik;
                namaPemasokFld.value = data.namaPemasok;
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        jenisHarga: jenisHargaFld.value,
                        idKatalog: idKatalogFld.value,
                        namaSediaan: namaSediaanFld.value,
                        namaPabrik: namaPabrikFld.value,
                        namaPemasok: namaPemasokFld.value,
                    }
                });
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {field: "jenisHarga"},
                2: {field: "idKatalog"},
                3: {field: "namaSediaan"},
                4: {field: "namaPabrik"},
                5: {field: "namaPemasok"},
                6: {formatter(unused, item) {
                    const {kemasanBesar, isiKemasan} = item;
                    return kemasanBesar + " " + (isiKemasan == 1 ? "" : isiKemasan);
                }},
                7:  {field: "hargaKemasan"},
                8:  {field: "hargaItem"},
                9:  {field: "diskonItem"},
                10: {field: "updatedBy", visible: access.audit},
                11: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter}
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
