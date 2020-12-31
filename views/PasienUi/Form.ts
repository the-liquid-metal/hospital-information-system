<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PasienUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/pasien/add.php the original file
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $addActionUrl,
        string $addPasienUrl,
        string $kotaUrl,
        string $kecamatanUrl,
        string $kelurahanUrl,
        string $propinsiSelect,
        string $pekerjaanSelect,
        string $agamaSelect,
        string $pendidikanSelect,
        string $negaraSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Pasien.Add {
    export interface FormFields {
        kodeRekamMedis: string;
        nama:           string;
        tempatLahir:    string;
        tanggalLahir:   string;
        kelamin:        string;
        golonganDarah:  string;
        alamatJalan:    string;
        rt:             string;
        rw:             string;
        kodePos:        string;
        noTelefon:      string;
        noHp:           string;
        propinsi:       string;
        kota:           string;
        kecamatan:      string;
        kelurahan:      string;
        pekerjaan:      string;
        agama:          string;
        statusNikah:    string;
        pendidikan:     string;
        negara:         string;
        sukuBangsa:     string;
        noIdentitas:    string;
        alamatKerabat:  string;
    }

    export interface KotaFields {
        id:   string;
        nama: string;
    }

    export interface KecamatanFields {
        id:   string;
        nama: string;
    }

    export interface KelurahanFields {
        id:   string;
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
     * @returns {{add: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
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
            class: ".pasienFrm",
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaFld", name: "nama"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tempat Lahir") ?>,
                        input: {class: ".tempatLahirFld", name: "tempatLahir"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Lahir") ?>,
                        input: {class: ".tanggalLahirFld", name: "tanggalLahir"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        radio_1: {class: ".kelaminMale",   name: "kelamin", value: "L", label: tlm.stringRegistry._<?= $h("Laki-laki") ?>},
                        radio_2: {class: ".kelaminFemale", name: "kelamin", value: "P", label: tlm.stringRegistry._<?= $h("Perempuan") ?>}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Golongan Darah") ?>,
                        select: {
                            class: ".golonganDarahFld",
                            name: "golonganDarah",
                            option_1: {value: "",   label: ""},
                            option_2: {value: "A",  label: "A"},
                            option_3: {value: "B",  label: "B"},
                            option_4: {value: "AB", label: "AB"},
                            option_5: {value: "O",  label: "O"}
                        }
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Alamat") ?>,
                        textarea: {class: ".alamatJalanFld", name: "alamatJalan"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("RT") ?>,
                        input: {class: ".rtFld", name: "rt"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("RW") ?>,
                        input: {class: ".rwFld", name: "rw"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("RW") ?>,
                        input: {class: ".kodePosFld", name: "kodePos"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("No. Telefon") ?>,
                        input: {class: ".noTelefonFld", name: "noTelefon"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("No. HP") ?>,
                        input: {class: ".noHpFld", name: "noHp"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Provinsi") ?>,
                        select: {class: ".propinsiFld", name: "propinsi"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Kota/Kabupaten") ?>,
                        select: {class: ".kotaFld", name: "kota"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Kecamatan") ?>,
                        select: {class: ".kecamatanFld", name: "kecamatan"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Kelurahan") ?>,
                        select: {class: ".kelurahanFld", name: "kelurahan"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Pekerjaan") ?>,
                        select: {class: ".pekerjaanFld", name: "pekerjaan"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Agama") ?>,
                        select: {class: ".agamaFld", name: "agama"}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Status Perkawinan") ?>,
                        select: {
                            class: ".statusNikahFld",
                            name: "statusNikah",
                            option_1: {value: "Lajang",  label: tlm.stringRegistry._<?= $h("Lajang") ?>},
                            option_2: {value: "Menikah", label: tlm.stringRegistry._<?= $h("Menikah") ?>},
                            option_3: {value: "Cerai",   label: tlm.stringRegistry._<?= $h("Cerai") ?>}
                        }
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Pendidikan") ?>,
                        select: {class: ".pendidikanFld", name: "pendidikan"}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Warga Negara") ?>,
                        select: {class: ".negaraFld", name: "negara"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Suku Bangsa") ?>,
                        input: {class: ".sukuBangsaFld", name: "sukuBangsa"}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("No. Identitas") ?>,
                        input: {class: ".noIdentitasFld", name: "noIdentitas"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Alamat Kerabat") ?>,
                        textarea: {class: ".alamatKerabatFld", name: "alamatKerabat"}
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */      const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */    const kodeRekamMedisFld = divElm.querySelector(".kodeRekamMedisFld");
        /** @type {HTMLInputElement} */    const namaFld = divElm.querySelector(".namaFld");
        /** @type {HTMLInputElement} */    const tempatLahirFld = divElm.querySelector(".tempatLahirFld");
        /** @type {HTMLInputElement} */    const kelaminFemale = divElm.querySelector(".kelaminFemale");
        /** @type {HTMLInputElement} */    const kelaminMale = divElm.querySelector(".kelaminMale");
        /** @type {HTMLSelectElement} */   const golonganDarahFld = divElm.querySelector(".golonganDarahFld");
        /** @type {HTMLTextAreaElement} */ const alamatJalanFld = divElm.querySelector(".alamatJalanFld");
        /** @type {HTMLInputElement} */    const rtFld = divElm.querySelector(".rtFld");
        /** @type {HTMLInputElement} */    const rwFld = divElm.querySelector(".rwFld");
        /** @type {HTMLInputElement} */    const kodePosFld = divElm.querySelector(".kodePosFld");
        /** @type {HTMLInputElement} */    const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLInputElement} */    const noHpFld = divElm.querySelector(".noHpFld");
        /** @type {HTMLSelectElement} */   const propinsiFld = divElm.querySelector(".propinsiFld");
        /** @type {HTMLSelectElement} */   const kotaFld = divElm.querySelector(".kotaFld");
        /** @type {HTMLSelectElement} */   const kecamatanFld = divElm.querySelector(".kecamatanFld");
        /** @type {HTMLSelectElement} */   const kelurahanFld = divElm.querySelector(".kelurahanFld");
        /** @type {HTMLSelectElement} */   const pekerjaanFld = divElm.querySelector(".pekerjaanFld");
        /** @type {HTMLSelectElement} */   const agamaFld = divElm.querySelector(".agamaFld");
        /** @type {HTMLSelectElement} */   const statusNikahFld = divElm.querySelector(".statusNikahFld");
        /** @type {HTMLSelectElement} */   const pendidikanFld = divElm.querySelector(".pendidikanFld");
        /** @type {HTMLSelectElement} */   const negaraFld = divElm.querySelector(".negaraFld");
        /** @type {HTMLInputElement} */    const sukuBangsaFld = divElm.querySelector(".sukuBangsaFld");
        /** @type {HTMLInputElement} */    const noIdentitasFld = divElm.querySelector(".noIdentitasFld");
        /** @type {HTMLTextAreaElement} */ const alamatKerabatFld = divElm.querySelector(".alamatKerabatFld");

        tlm.app.registerSelect("_<?= $propinsiSelect ?>", propinsiFld);
        tlm.app.registerSelect("_<?= $pekerjaanSelect ?>", pekerjaanFld);
        tlm.app.registerSelect("_<?= $agamaSelect ?>", agamaFld);
        tlm.app.registerSelect("_<?= $pendidikanSelect ?>", pendidikanFld);
        tlm.app.registerSelect("_<?= $negaraSelect ?>", negaraFld);
        this._selects.push(propinsiFld, pekerjaanFld, agamaFld, pendidikanFld, negaraFld);

        const pasienWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".pasienFrm"),
            /** @param {his.FatmaPharmacy.views.Pasien.Add.FormFields} data */
            loadData(data) {
                kodeRekamMedisFld.value = data.kodeRekamMedis ?? "";
                namaFld.value = data.nama ?? "";
                tempatLahirFld.value = data.tempatLahir ?? "";
                tanggalLahirWgt.value = data.tanggalLahir ?? "";
                data.kelamin ? kelaminMale.checked = true : kelaminFemale.checked = true;
                golonganDarahFld.value = data.golonganDarah ?? "";
                alamatJalanFld.value = data.alamatJalan ?? "";
                rtFld.value = data.rt ?? "";
                rwFld.value = data.rw ?? "";
                kodePosFld.value = data.kodePos ?? "";
                noTelefonFld.value = data.noTelefon ?? "";
                noHpFld.value = data.noHp ?? "";
                propinsiWgt.value = data.propinsi ?? "";
                kotaWgt.value = data.kota ?? "";
                kecamatanWgt.value = data.kecamatan ?? "";
                kelurahanWgt.value = data.kelurahan ?? "";
                pekerjaanFld.value = data.pekerjaan ?? "";
                agamaFld.value = data.agama ?? "";
                statusNikahFld.value = data.statusNikah ?? "";
                pendidikanFld.value = data.pendidikan ?? "";
                negaraFld.value = data.negara ?? "";
                sukuBangsaFld.value = data.sukuBangsa ?? "";
                noIdentitasFld.value = data.noIdentitas ?? "";
                alamatKerabatFld.value = data.alamatKerabat ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._element.reset();
                    formTitleTxt.innerHTML = str._<?= $h("Add Pasien") ?>;
                    this._actionUrl = "<?= $addActionUrl ?>";
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            onBeforeSubmit() {
                $.post({
                    url: "<?= $addPasienUrl ?>",
                    data: this.element.serialize() + "&submit=save" + "&format=json",
                    success(data) {
                        const modalBodyElm = divElm.querySelector(".modal-body");
                        if (data.id) {
                            isiForm(data.id);
                            bootbox.hideAll();
                            return false;
                        }
                        const dom = $(data.html);
                        dom.querySelector(".form-actions").style.display = "none";
                        dom.querySelector("form").classList.remove("well");
                        modalBodyElm.innerHTML = dom;
                        modalBodyElm.querySelector("form").classList.remove("well");
                        dom.filter("script").each(() => {
                            $.globalEval(this.text || this.textContent || this.innerHTML || "");
                        });
                    }
                });
                return false;
            },
            resetBtnId: false,
        });

        // TODO: js: widget: adjust the params
        const tanggalLahirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalLahirFld"),
            // weekStart: 1,
            ...tlm.dateWidgetSetting
        });

        const propinsiWgt = new spl.SelectWidget({element: propinsiFld});

        const kotaWgt = new spl.SelectWidget({element: kotaFld});

        const kecamatanWgt = new spl.SelectWidget({element: kecamatanFld});

        const kelurahanWgt = new spl.SelectWidget({element: kelurahanFld});

        propinsiFld.addEventListener("change", () => {
            $.post({
                url: "<?= $kotaUrl ?>",
                data: {provinsi: propinsiFld.value},
                /** @param {his.FatmaPharmacy.views.Pasien.Add.KotaFields[]} data */
                success(data) {
                    let options = `<option value="999"></option>`;
                    data.forEach(item => options += `<option value="${item.id}">${item.nama}</option>`);
                    kotaFld.innerHTML = options;
                }
            });
        });

        kotaFld.addEventListener("change", () => {
            $.post({
                url: "<?= $kecamatanUrl ?>",
                data: {provinsi: propinsiFld.value, kota: kotaFld.value},
                /** @param {his.FatmaPharmacy.views.Pasien.Add.KecamatanFields[]} data */
                success(data) {
                    let options = `<option value="999"></option>`;
                    data.forEach(item => options += `<option value="${item.id}">${item.nama}</option>`);
                    kecamatanFld.innerHTML = options;
                }
            });
        });

        kecamatanFld.addEventListener("change", () => {
            $.post({
                url: "<?= $kelurahanUrl ?>",
                data: {provinsi: propinsiFld.value, kota: kotaFld.value, kecamatan: kecamatanFld.value},
                /** @param {his.FatmaPharmacy.views.Pasien.Add.KelurahanFields[]} data */
                success(data) {
                    let options = `<option value="999"></option>`;
                    data.forEach(item => options += `<option value="${item.id}">${item.nama}</option>`);
                    kelurahanFld.innerHTML = options;
                }
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(pasienWgt, tanggalLahirWgt, propinsiWgt, kotaWgt, kecamatanWgt, kelurahanWgt);
        tlm.app.registerWidget(this.constructor.widgetName, pasienWgt);
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
