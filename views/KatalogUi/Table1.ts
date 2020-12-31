<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\KatalogUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Katalog/index.php the original file
 */
final class Table1
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $deleteAccess,
        array  $auditAccess,
        string $dataUrl,
        string $deleteUrl,
        string $formWidgetId,
        string $jenisObatSelect,
        string $kelompokBarangSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.KatalogUi.Table {
    export interface FormFields {
        idJenisBarang:       string;
        idKelompokBarang:    string;
        formulariumRs:       string;
        formulariumNasional: string;
        barangProduksi:      string;
        barangKonsinyasi:    string;
        statusAktif:         string;
    }

    export interface TableFields {
        id:               string;
        idDepo:           string;
        kode:             string;
        namaSediaan:      string;
        namaDagang:       string;
        namaGenerik:      string;
        namaPabrik:       string;
        namaKemasan:      string;
        namaKemasanKecil: string;
        hargaBeli:        string;
        jenisObat:        string;
        updatedBy:        string;
        updatedTime:      string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Katalog") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Jenis Barang") ?>,
                        select: {class: ".idJenisBarangFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kelompok Barang") ?>,
                        select: {class: ".idKelompokBarangFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Filter Berdasar") ?>,
                        checkbox_1: {class: ".formulariumRsFld",       value: 1, label: tlm.stringRegistry._<?= $h("Formularium RSUPF") ?>},
                        checkbox_2: {class: ".formulariumNasionalFld", value: 1, label: tlm.stringRegistry._<?= $h("Formularium Nasional") ?>},
                        checkbox_3: {class: ".barangProduksiFld",      value: 1, label: tlm.stringRegistry._<?= $h("Barang Produksi") ?>},
                        checkbox_4: {class: ".barangKonsinyasiFld",    value: 1, label: tlm.stringRegistry._<?= $h("Barang Konsinyasi") ?>}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tampilkan") ?>,
                        select: {
                            class: ".statusAktifFld",
                            option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Katalog Tidak Aktif") ?>},
                            option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Katalog Aktif") ?>},
                            option_3: {value: 2, label: tlm.stringRegistry._<?= $h("Katalog Yang Dihapus") ?>}
                        }
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
                        td_1:  {text: ""},
                        td_2:  {text: ""},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Nama Sediaan") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Nama Dagang") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Nama Generik") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Pabrik Terakhir") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Kemasan Kecil") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Jenis Barang") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("User Update") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Last Update") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const str = tlm.stringRegistry;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const idJenisBarangFld = divElm.querySelector(".idJenisBarangFld");
        /** @type {HTMLSelectElement} */ const idKelompokBarangFld = divElm.querySelector(".idKelompokBarangFld");
        /** @type {HTMLInputElement} */  const formulariumRsFld = divElm.querySelector(".formulariumRsFld");
        /** @type {HTMLInputElement} */  const formulariumNasionalFld = divElm.querySelector(".formulariumNasionalFld");
        /** @type {HTMLInputElement} */  const barangProduksiFld = divElm.querySelector(".barangProduksiFld");
        /** @type {HTMLInputElement} */  const barangKonsinyasiFld = divElm.querySelector(".barangKonsinyasiFld");
        /** @type {HTMLSelectElement} */ const statusAktifFld = divElm.querySelector(".statusAktifFld");

        tlm.app.registerSelect("_<?= $jenisObatSelect ?>", idJenisBarangFld);
        tlm.app.registerSelect("_<?= $kelompokBarangSelect ?>", idKelompokBarangFld);
        this._selects.push(idJenisBarangFld, idKelompokBarangFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.KatalogUi.Table.FormFields} data */
            loadData(data) {
                idJenisBarangFld.value = data.idJenisBarang ?? "";
                idKelompokBarangFld.value = data.idKelompokBarang ?? "";
                formulariumRsFld.value = data.formulariumRs ?? "";
                formulariumNasionalFld.value = data.formulariumNasional ?? "";
                barangProduksiFld.value = data.barangProduksi ?? "";
                barangKonsinyasiFld.value = data.barangKonsinyasi ?? "";
                statusAktifFld.value = data.statusAktif ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        idJenisBarang: idJenisBarangFld.value,
                        idKelompokBarang: idKelompokBarangFld.value,
                        formulariumRs: formulariumRsFld.value,
                        formulariumNasional: formulariumNasionalFld.value,
                        barangProduksi: barangProduksiFld.value,
                        barangKonsinyasi: barangKonsinyasiFld.value,
                        statusAktif: statusAktifFld.value,
                    }
                });
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {idDepo, id} = item;
                    const titleHarga = str._<?= $h("Lihat dan Set Harga Detail Katalog") ?>;
                    const titleMinimum = str._<?= $h("Set Stok Minimum-Maksimum Katalog") ?>;

                    const setHargaBtn   = draw({class: ".btn-setstok", type: "warning", icon: "list",   title: titleHarga});
                    const setMinimumBtn = draw({class: ".btn-setstok", type: "warning", icon: "pencil", title: titleMinimum, "data-id_depo": idDepo});
                    const deleteBtn     = draw({class: ".deleteBtn",   type: "danger",  icon: "trash",  value: id, text: str._<?= $h("Hapus") ?>});
                    const editBtn       = draw({class: ".editBtn",     type: "primary", icon: "pencil", value: id, text: str._<?= $h("Ganti") ?>});

                    return setHargaBtn + setMinimumBtn + deleteBtn + editBtn;
                }},
                2:  {formatter: tlm.rowNumGenerator},
                3:  {field: "kode"},
                4:  {field: "namaSediaan"},
                5:  {field: "namaDagang"},
                6:  {field: "namaGenerik"},
                7:  {field: "namaPabrik"},
                8:  {field: "namaKemasan"},
                9:  {field: "namaKemasanKecil"},
                10: {field: "hargaBeli", formatter: tlm.currencyFormatter},
                11: {field: "jenisObat"},
                12: {field: "updatedBy", visible: access.audit},
                13: {field: "updatedTime", visible: access.audit, formatter: tlm.dateFormatter}
            },
            bodyRowModifier({_data: data}) {
                let style;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "01": style = "color:red"; break;
                    case "00": style = "color:blue"; break;
                    default  : style = "";
                }
                return {css: style, class: "", attribute: ""};
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>)) return;

            const val = deleteBtn.value;
            $.post({
                url: "<?= $deleteUrl ?>",
                data: {field: "id", value: val, kode: val},
                success(data) {
                    const successMsg = str._<?= $h("Sukses hapus data. Silahkan tekan OK/Enter.") ?>;
                    const failMsg = str._<?= $h("Gagal hapus data. Silahkan hubungi tim IT.") ?>;
                    data ? alert(successMsg) : alert(failMsg);
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadData({id: editBtn.value}, true);
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
