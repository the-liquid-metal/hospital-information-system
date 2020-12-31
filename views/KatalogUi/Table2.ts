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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Katalogtest/index.php the original file
 */
final class Table2
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $deleteAccess,
        array  $exportAccess,
        string $dataUrl,
        string $deleteUrl,
        string $brandAcplUrl,
        string $generikAcplUrl,
        string $pabrikAcplUrl,
        string $formWidgetId,
        string $exportWidgetId,
        string $jenisObatSelect,
        string $kelompokBarangSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.KatalogUi.Table2 {
    export interface FormFields {
        idJenisBarang:       string;
        idKelompokBarang:    string;
        formulariumRsupf:    string;
        formulariumNasional: string;
        barangProduksi:      string;
        barangKonsinyasi:    string;
        statusAktif:         string;
        kode:                string;
        namaSediaan:         string;
        kemasan:             string;
        idBrand:             string;
        idGenerik:           string;
        idPabrik:            string;
    }

    export interface PabrikFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface GenerikFields {
        id:   string;
        nama: string;
        kode: string;
    }

    export interface BrandFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface TableFields {
        id:               string;
        kode:             string;
        namaSediaan:      string;
        namaDagang:       string;
        namaGenerik:      string;
        namaPabrik:       string;
        namaKemasanBesar: string;
        namaKemasanKecil: string;
        hargaBeli:        string;
        jenisObat:        string;
        formulariumNas:   string;
        formulariumRs:    string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean, delete: boolean, export: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            delete: JSON.parse(`<?=json_encode($deleteAccess) ?>`),
            export: JSON.parse(`<?=json_encode($exportAccess) ?>`),
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
                box_1: {
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
                        label: tlm.stringRegistry._<?= $h("Filter Berdasarkan") ?>,
                        checkbox_1: {class: ".formulariumRsupfFld",    label: tlm.stringRegistry._<?= $h("Formularium RSUPF") ?>},
                        checkbox_2: {class: ".formulariumNasionalFld", label: tlm.stringRegistry._<?= $h("Formularium Nasional") ?>},
                        checkbox_3: {class: ".barangProduksiFld",      label: tlm.stringRegistry._<?= $h("Barang Produksi") ?>},
                        checkbox_4: {class: ".barangKonsinyasiFld",    label: tlm.stringRegistry._<?= $h("Barang Konsinyasi") ?>}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tampilkan") ?>,
                        select: {
                            class: ".statusAktifFld",
                            option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Katalog Tidak Aktif") ?>},
                            option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Katalog Aktif") ?>},
                            option_3: {value: 2, label: tlm.stringRegistry._<?= $h("Katalog Yang Dihapus") ?>},
                        }
                    }
                },
                box_2: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode") ?>,
                        input: {class: ".kodeFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Sediaan") ?>,
                        input: {class: ".namaSediaanFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Nama Dagang") ?>,
                        select: {class: ".idBrandFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama Generik") ?>,
                        select: {class: ".idGenerikFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Pabrik Terakhir") ?>,
                        select: {class: ".idPabrikFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Kemasan") ?>,
                        input: {class: ".kemasanFld"}
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
                        td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Nama Sediaan") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Nama Dagang") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Nama Generik") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Pabrik Terakhir") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Kemasan Kecil") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Jenis Barang") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Formularium Nas") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Formularium RS") ?>},
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
        /** @type {HTMLInputElement} */  const formulariumRsupfFld = divElm.querySelector(".formulariumRsupfFld");
        /** @type {HTMLInputElement} */  const formulariumNasionalFld = divElm.querySelector(".formulariumNasionalFld");
        /** @type {HTMLInputElement} */  const barangProduksiFld = divElm.querySelector(".barangProduksiFld");
        /** @type {HTMLInputElement} */  const barangKonsinyasiFld = divElm.querySelector(".barangKonsinyasiFld");
        /** @type {HTMLSelectElement} */ const statusAktifFld = divElm.querySelector(".statusAktifFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */  const namaSediaanFld = divElm.querySelector(".namaSediaanFld");
        /** @type {HTMLInputElement} */  const kemasanFld = divElm.querySelector(".kemasanFld");

        tlm.app.registerSelect("_<?= $jenisObatSelect ?>", idJenisBarangFld);
        tlm.app.registerSelect("_<?= $kelompokBarangSelect ?>", idKelompokBarangFld);
        this._selects.push(idJenisBarangFld, idKelompokBarangFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector("..saringFrm"),
            /** @param {his.FatmaPharmacy.views.KatalogUi.Table2.FormFields} data */
            loadData(data) {
                idJenisBarangWgt.value = data.idJenisBarang ?? "";
                idKelompokBarangWgt.value = data.idKelompokBarang ?? "";
                formulariumRsupfFld.checked = !!data.formulariumRsupf;
                formulariumNasionalFld.checked = !!data.formulariumNasional;
                barangProduksiFld.checked = !!data.barangProduksi;
                barangKonsinyasiFld.checked = !!data.barangKonsinyasi;
                statusAktifFld.value = data.statusAktif ?? "";
                kodeFld.value = data.kode ?? "";
                namaSediaanFld.value = data.namaSediaan ?? "";
                kemasanFld.value = data.kemasan ?? "";
                idBrandWgt.value = data.idBrand ?? "";
                idGenerikWgt.value = data.idGenerik ?? "";
                idPabrikWgt.value = data.idPabrik ?? "";
            },
            submit() {
                katalogWgt.refresh({
                    query: {
                        idJenisBarang: idJenisBarangWgt.value,
                        idKelompokBarang: idKelompokBarangWgt.value,
                        formulariumRsupf: formulariumRsupfFld.checked ? 1 : 0,
                        formulariumNasional: formulariumNasionalFld.checked ? 1 : 0,
                        barangProduksi: barangProduksiFld.checked ? 1 : 0,
                        barangKonsinyasi: barangKonsinyasiFld.checked ? 1 : 0,
                        statusAktif: statusAktifFld.value,
                        kode: kodeFld.value,
                        namaSediaan: namaSediaanFld.value,
                        idBrand: idBrandWgt.value,
                        idGenerik: idGenerikWgt.value,
                        idPabrik: idPabrikWgt.value,
                        kemasan: kemasanFld.value,
                    }
                });
            }
        });

        const katalogWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {formatter(unused, {id}) {
                    const deleteBtn = draw({class: ".deleteBtn", icon: "trash",  value: id, text: str._<?= $h("Hapus") ?>});
                    const editBtn   = draw({class: ".editBtn",   icon: "pencil", value: id, text: str._<?= $h("Ganti") ?>});
                    const exportBtn = draw({class: ".exportBtn", icon: "square", value: id, text: str._<?= $h("Ekspor ke Excel") ?>});

                    return "" +
                        (access.delete ? deleteBtn : "") +
                        (access.edit ? editBtn : "") +
                        (access.export ? exportBtn : "");
                }},
                3:  {field: "kode"},
                4:  {field: "namaSediaan"},
                5:  {field: "namaDagang"},
                6:  {field: "namaGenerik"},
                7:  {field: "namaPabrik"},
                8:  {field: "namaKemasanBesar"},
                9:  {field: "namaKemasanKecil"},
                10: {field: "hargaBeli", formatter: tlm.currencyFormatter},
                11: {field: "jenisObat"},
                12: {field: "formulariumNas"},
                13: {field: "formulariumRs"}
            },
            bodyRowModifier(nRow, aData) {
                return {css: "", class: "", attribute: ""};

                // fnRowCallback(nRow, aData) {
                if (aData[11] == "0" && aData[12] == "1") {
                    nRow.querySelector("td").style.color = "Red";
                } else if (aData[11] == "0" && aData[12] == "0") {
                    nRow.querySelector("td").style.color = "Blue";
                }
            },
        });

        katalogWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;
            if (!confirm(str._<?= $h("Yakin untuk menghapus data ini?") ?>)) return;

            const value = deleteBtn.value;
            $.post({
                url: "<?= $deleteUrl ?>",
                data: {field: "id", value, kode: value},
                success(data) {
                    const successMsg = str._<?= $h("Sukses hapus data. Silahkan tekan OK/Enter.") ?>;
                    const failMsg = str._<?= $h("Gagal hapus data. Silahkan hubungi tim IT.") ?>;
                    data ? alert(successMsg) : alert(failMsg);
                }
            });
        });

        katalogWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formWidgetId ?>");
            widget.show();
            widget.loadData({id: editBtn.value}, true);
        });

        katalogWgt.addDelegateListener("tbody", "click", (event) => {
            const exportBtn = event.target;
            if (!exportBtn.matches(".exportBtn")) return;

            const widget = tlm.app.getWidget("_<?= $exportWidgetId ?>");
            widget.show();
            widget.loadData({id: exportBtn.value}, true);
        });

        const idJenisBarangWgt = new spl.SelectWidget({element: idJenisBarangFld});

        const idKelompokBarangWgt = new spl.SelectWidget({element: idKelompokBarangFld});

        const idBrandWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idBrandFld"),
            valueField: "id",
            /** @param {his.FatmaPharmacy.views.KatalogUi.Table2.BrandFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Table2.BrandFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            searchField: ["nama", "kode"],
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $brandAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        /** @see ________________________________________________________ */
        const idGenerikWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idGenerikFld"),
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.KatalogUi.Table2.GenerikFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Table2.GenerikFields} data */
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

        const idPabrikWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPabrikFld"),
            valueField: "id",
            /** @param {his.FatmaPharmacy.views.KatalogUi.Table2.PabrikFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Table2.PabrikFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            searchField: ["nama", "kode"],
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pabrikAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, katalogWgt, idJenisBarangWgt, idKelompokBarangWgt, idBrandWgt, idGenerikWgt, idPabrikWgt);
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
