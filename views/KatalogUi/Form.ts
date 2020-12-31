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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Katalog/add.php the original file
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
        string $jenisObatAcplUrl,
        string $brandAcplUrl,
        string $generikAcplUrl,
        string $pemasokAcplUrl,
        string $pabrikAcplUrl,
        string $cekUnikKodeUrl,
        string $cekUnikNamaUrl,
        string $kemasanBesarAcplUrl,
        string $kemasanKecilAcplUrl,
        string $sediaanAcplUrl
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.KatalogUi.Form {
    export interface FormFields {
        idJenisBarang:       "id_jenisbarang",
        idKelompokBarang:    "id_kelompokbarang",
        idBrand:             "id_brand",
        idGenerik:           "id_generik",
        idPabrik:            "id_pabrik",
        idPemasok:           "id_pbf",
        zatAktif:            "zat_aktif",
        restriksi:           "retriksi",
        keterangan:          "keterangan",
        kodeBarang:          "kode",
        namaSediaan:         "nama_sediaan",
        idKemasanBesar:      "id_kemasanbesar",
        isiKemasan:          "isi_kemasan",
        idKemasanKecil:      "id_kemasankecil",
        isiSediaan:          "isi_sediaan",
        idSediaan:           "id_sediaan",
        kemasan:             "kemasan",
        hargaBeli:           "harga_beli",
        diskonBeli:          "diskon_beli",
        leadtime:            "leadtime",
        buffer:              "buffer",
        moving:              "moving",
        formulariumNasional: "formularium_nas",
        formulariumRs:       "formularium_rs",
        liveSaving:          "live_saving",
        obatGenerik:         "generik",
        aktifasi:            "aktifasi",
    }

    export interface SediaanFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface KemasanFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface PabrikFields {
        id:   string;
        kode: string;
        nama: string;
    }

    export interface PemasokFields {
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

    export interface JenisObatFields {
        id:   string;
        kode: string;
        nama: string;
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
            class: ".katalogFrm",
            row_1: {
                widthBox: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    row_1: {
                        column_1: {
                            formGroup_1: {
                                label: tlm.stringRegistry._<?= $h("Jenis Barang") ?>,
                                select: {class: ".idJenisBarangFld", name: "idJenisBarang"}
                            },
                            formGroup_2: {
                                label: tlm.stringRegistry._<?= $h("Kelompok Barang") ?>,
                                input: {class: ".idKelompokBarangFld", name: "idKelompokBarang"}
                            },
                            formGroup_3: {
                                label: tlm.stringRegistry._<?= $h("Nama Dagang") ?>,
                                select: {class: ".idBrandFld", name: "idBrand"}
                            },
                            formGroup_4: {
                                label: tlm.stringRegistry._<?= $h("Nama Generik") ?>,
                                select: {class: ".idGenerikFld", name: "idGenerik"}
                            },
                            formGroup_5: {
                                label: tlm.stringRegistry._<?= $h("Pabrik") ?>,
                                select: {class: ".idPabrikFld", name: "idPabrik"}
                            },
                            formGroup_6: {
                                label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                                select: {class: ".idPemasokFld", name: "idPemasok"}
                            },
                            formGroup_7: {
                                label: tlm.stringRegistry._<?= $h("Zat Aktif") ?>,
                                textarea: {class: ".zatAktifFld", name: "zatAktif"}
                            },
                            formGroup_8: {
                                label: tlm.stringRegistry._<?= $h("Restriksi") ?>,
                                textarea: {class: ".restriksiFld", name: "restriksi"}
                            },
                            formGroup_9: {
                                label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                                textarea: {class: ".keteranganFld", name: "keterangan"}
                            }
                        },
                        column_2: {
                            formGroup_1: {
                                label: tlm.stringRegistry._<?= $h("Kode Barang") ?>,
                                input: {class: ".kodeBarangFld", name: "kodeBarang"}
                            },
                            formGroup_2: {
                                label: tlm.stringRegistry._<?= $h("Nama Sediaan Barang") ?>,
                                input: {class: ".namaSediaanFld", name: "namaSediaan"}
                            },
                            formGroup_3: {
                                label: tlm.stringRegistry._<?= $h("Satuan Kemasan") ?>,
                                select_1: {class: ".idKemasanBesarFld", name: "idKemasanBesar", placeholder: tlm.stringRegistry._<?= $h("Kemasan Besar") ?>},
                                input: {class: ".isiKemasanFld", name: "isiKemasan", placeholder: tlm.stringRegistry._<?= $h("Isi Kemasan") ?>},
                                select_2: {class: ".idKemasanKecilFld", name: "idKemasanKecil", placeholder: tlm.stringRegistry._<?= $h("Kemasan Kecil") ?>}
                            },
                            formGroup_4: {
                                label: tlm.stringRegistry._<?= $h("Satuan Sediaan") ?>,
                                input_1: {class: ".isiSediaanFld", name: "isiSediaan"},
                                select: {class: ".idSediaanFld", name: "idSediaan"}
                            },
                            formGroup_5: {
                                label: tlm.stringRegistry._<?= $h("Kemasan Barang") ?>,
                                input: {class: ".kemasanFld", name: "kemasan"}
                            },
                            formGroup_6: {
                                label: tlm.stringRegistry._<?= $h("Set Harga") ?>,
                                input_1: {class: ".hargaBeliFld", name: "hargaBeli"},
                                input_2: {class: ".diskonBeliFld", name: "diskonBeli"}
                            },
                            formGroup_7: {
                                label: tlm.stringRegistry._<?= $h("Keterangan Barang") ?>,
                                input_1: {class: ".leadtimeFld", name: "leadtime", title: tlm.stringRegistry._<?= $h("Lead Time (Hari)") ?>},
                                input_2: {class: ".bufferFld",   name: "buffer",   title: tlm.stringRegistry._<?= $h("Buffer (%)") ?>},
                                select: {
                                    class: ".movingFld",
                                    name: "moving",
                                    option_1: {value: "",   label: tlm.stringRegistry._<?= $h("Semua") ?>},
                                    option_2: {value: "DM", label: tlm.stringRegistry._<?= $h("Death Moving") ?>},
                                    option_3: {value: "SM", label: tlm.stringRegistry._<?= $h("Slow Moving") ?>},
                                    option_4: {value: "MM", label: tlm.stringRegistry._<?= $h("Medium Moving") ?>},
                                    option_5: {value: "FM", label: tlm.stringRegistry._<?= $h("Fast Moving") ?>}
                                }
                            },
                            formGroup_8: {
                                label: tlm.stringRegistry._<?= $h("Formularium") ?>,
                                checkbox_1: {class: ".formulariumNasionalFld", name: "formulariumNasional", value: 1, label: tlm.stringRegistry._<?= $h("Nasional") ?>},
                                checkbox_2: {class: ".formulariumRsFld",       name: "formulariumRs",       value: 1, label: tlm.stringRegistry._<?= $h("RSUPF") ?>}
                            },
                            formGroup_9: {
                                label: tlm.stringRegistry._<?= $h("Kategori Obat") ?>,
                                checkbox_1: {class: ".liveSavingFld",  name: "liveSaving",  value: 1, label: tlm.stringRegistry._<?= $h("Live Saving") ?>},
                                checkbox_2: {class: ".obatGenerikFld", name: "obatGenerik", value: 1, label: tlm.stringRegistry._<?= $h("Generik") ?>}
                            },
                            formGroup_10: {
                                label: tlm.stringRegistry._<?= $h("Aktifasi Barang") ?>,
                                radio_1: {class: ".aktifasiYes", name: "aktifasi", value: 1, label: tlm.stringRegistry._<?= $h("Aktif") ?>},
                                radio_2: {class: ".aktifasiNo",  name: "aktifasi", value: 0, label: tlm.stringRegistry._<?= $h("Tidak Aktif") ?>}
                            }
                        }
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Generate") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */      const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLSelectElement} */   const idKelompokBarangFld = divElm.querySelector(".idKelompokBarangFld");
        /** @type {HTMLTextAreaElement} */ const zatAktifFld = divElm.querySelector(".zatAktifFld");
        /** @type {HTMLTextAreaElement} */ const restriksiFld = divElm.querySelector(".restriksiFld");
        /** @type {HTMLTextAreaElement} */ const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLInputElement} */    const isiSediaanFld = divElm.querySelector(".isiSediaanFld");
        /** @type {HTMLInputElement} */    const hargaBeliFld = divElm.querySelector(".hargaBeliFld");
        /** @type {HTMLInputElement} */    const diskonBeliFld = divElm.querySelector(".diskonBeliFld");
        /** @type {HTMLInputElement} */    const leadtimeFld = divElm.querySelector(".leadtimeFld");
        /** @type {HTMLInputElement} */    const bufferFld = divElm.querySelector(".bufferFld");
        /** @type {HTMLSelectElement} */   const movingFld = divElm.querySelector(".movingFld");
        /** @type {HTMLInputElement} */    const formulariumNasionalFld = divElm.querySelector(".formulariumNasionalFld");
        /** @type {HTMLInputElement} */    const formulariumRsFld = divElm.querySelector(".formulariumRsFld");
        /** @type {HTMLInputElement} */    const liveSavingFld = divElm.querySelector(".liveSavingFld");
        /** @type {HTMLInputElement} */    const obatGenerikFld = divElm.querySelector(".obatGenerikFld");
        /** @type {HTMLInputElement} */    const aktifasiYes = divElm.querySelector(".aktifasiYes");
        /** @type {HTMLInputElement} */    const aktifasiNo = divElm.querySelector(".aktifasiNo");

        const katalogWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".katalogFrm"),
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.FormFields} data */
            loadData(data) {
                idJenisBarangWgt.value = data.idJenisBarang ?? "";
                idKelompokBarangFld.value = data.idKelompokBarang ?? "";
                idBrandWgt.value = data.idBrand ?? "";
                idGenerikWgt.value = data.idGenerik ?? "";
                idPabrikWgt.value = data.idPabrik ?? "";
                idPemasokWgt.value = data.idPemasok ?? "";
                zatAktifFld.value = data.zatAktif ?? "";
                restriksiFld.value = data.restriksi ?? "";
                keteranganFld.value = data.keterangan ?? "";
                kodeBarangWgt.value = data.kodeBarang ?? "";
                namaSediaanWgt.value = data.namaSediaan ?? "";
                idKemasanBesarWgt.value = data.idKemasanBesar ?? "";
                isiKemasanWgt.value = data.isiKemasan ?? "";
                idKemasanKecilWgt.value = data.idKemasanKecil ?? "";
                isiSediaanFld.value = data.isiSediaan ?? "";
                idSediaanWgt.value = data.idSediaan ?? "";
                hargaBeliFld.value = data.hargaBeli ?? "";
                diskonBeliFld.value = data.diskonBeli ?? "";
                leadtimeFld.value = data.leadtime ?? "";
                bufferFld.value = data.buffer ?? "";
                movingFld.value = data.moving ?? "";
                formulariumNasionalFld.value = data.formulariumNasional ?? "";
                formulariumRsFld.value = data.formulariumRs ?? "";
                liveSavingFld.value = data.liveSaving ?? "";
                obatGenerikFld.value = data.obatGenerik ?? "";
                data.aktifasi ? aktifasiYes.checked = true : aktifasiNo.checked = true;
            },
            profile: {
                add() {
                    this.loadData({});
                    this._actionUrl = "<?= $addActionUrl ?>";
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Katalog") ?>
                },
                edit(data, fromServer) {
                    this.loadData(data, fromServer);
                    this._actionUrl = "<?= $editActionUrl ?>";
                    formTitleTxt.innerHTML = str._<?= $h("Ubah Katalog") ?>
                },
            }
            resetBtnId: false,
            dataUrl: "<?= $dataUrl ?>",
        });

        const idJenisBarangWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idJenisBarangFld"),
            errorRules: [{required: true}],
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.JenisObatFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.JenisObatFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $jenisObatAcplUrl ?>",
                    data: {q: typed, field: "jenis_obat"},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
        });

        const idBrandWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idBrandFld"),
            errorRules: [{required: true}],
            valueField: "id",
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.BrandFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.BrandFields} data */
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
            },
        });

        const idGenerikWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idGenerikFld"),
            errorRules: [{required: true}],
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.GenerikFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.GenerikFields} data */
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
            },
        });

        const idPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPemasokFld"),
            errorRules: [{required: true}],
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.PemasokFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.PemasokFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pemasokAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
        });

        const idPabrikWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idPabrikFld"),
            errorRules: [{required: true}],
            valueField: "id",
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.PabrikFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.PabrikFields} data */
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
            },
        });

        const kodeBarangWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".kodeBarangFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == divElm.querySelector(".idFld").value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikKodeUrl ?>",
            term: "value",
            additionalData: {field: "kode"}
        });

        const namaSediaanWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".namaSediaanFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.id == divElm.querySelector(".idFld").value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNamaUrl ?>",
            term: "value",
            additionalData: {field: "nama_sediaan"}
        });

        const idKemasanBesarWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idKemasanBesarFld"),
            errorRules: [{required: true}],
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.KemasanFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.KemasanFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $kemasanBesarAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
        });

        const isiKemasanWgt = new spl.NumberWidget({
            element: divElm.querySelector(".isiKemasanFld"),
            errorRules: [
                {required: true},
                {integer: true}
            ],
            ...tlm.floatNumberSetting
        });

        const idKemasanKecilWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idKemasanKecilFld"),
            errorRules: [{required: true}],
            valueField: "id",
            searchField: ["nama", "kode"],
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.KemasanFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.KemasanFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $kemasanKecilAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
        });

        const idSediaanWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idSediaanFld"),
            errorRules: [{required: true}],
            valueField: "id",
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.SediaanFields} data */
            optionRenderer(data) {return `<div class="option">${data.nama} (${data.kode})</div>`},
            /** @param {his.FatmaPharmacy.views.KatalogUi.Form.SediaanFields} data */
            itemRenderer(data) {return `<div class="item">${data.nama} (${data.kode})</div>`},
            searchField: ["nama", "kode"],
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $sediaanAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(katalogWgt, idJenisBarangWgt, idBrandWgt, idGenerikWgt, idPemasokWgt, idPabrikWgt);
        this._widgets.push(kodeBarangWgt, namaSediaanWgt, idKemasanBesarWgt, isiKemasanWgt, idKemasanKecilWgt, idSediaanWgt);
        tlm.app.registerWidget(this.constructor.widgetName, katalogWgt);
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
