<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\JadwalOperasiUi;

use tlm\libs\LowEnd\components\{FormHelper as FH, MasterHelper as MH};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/add.php the original file
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $addActionUrl,
        string $caraBayarUrl,
        string $registrasiAjaxUrl,
        string $operatorUrl,
        string $instalasiUrl,
        string $cekNoRekamMedisUrl,
        string $diagnosaUrl,
        string $cariTindakanUrl,
        string $cariAlatUrl,
        string $addUrl,
        string $viewNoRekamMedisUrl,
        string $tableBookingWidgetId,
        string $caraBayarSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $data = [];
        $no = "";
        $daftarDiagnosa = [];
        $userData = new \stdClass;
        $daftarTindakan = [];
        $alatOperasi = [];
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.JadwalOperasi.Form {
    export interface FormFields {
        noPendaftaran:            "no_daftar",
        alamat:                   "alamat",
        kodeRekamMedis:           "no_rm",
        namaPasien:               "nama",
        tanggalLahir:             "tanggal_lahir",
        kelamin:                  "jenis_kelamin",
        noTelefon:                "no_telp",
        kelasRm:                  "kelasrm",
        idDokter:                 "id_dokter",
        tanggalAwalOperasi:       "rencana_operasi_tgl",
        tanggalAkhirOperasi:      "rencana_operasi_end_tgl",
        durasi:                   "durasi",
        ruang:                    "ruang",
        kelas:                    "kelas",
        namaTempatTidur:          "tempat_tidur",
        idTempatTidur:            "tempat_tidur_id",
        permintaanAkomodasi:      "request_akomodasi",
        postOp:                   "post_op",
        jenisCaraBayar:           "jenis_cara_bayar",
        caraBayar:                "cara_bayar",
        hubunganKeluargaPenjamin: "hubungan_keluarga_penjamin",
        noPesertaJaminan:         "no_peserta_jaminan",
        namaPesertaJaminan:       "nama_peserta_jaminan",
        asalWilayahJabotabek:     "asal_wilayah_jabotabek",
        asalWilayahNonJabotabek:  "asal_wilayah",
        jenisOperasi:             "jenis_operasi",
        idInstalasi:              "id_instalasi",
        idPoli:                   "id_poli",
        prioritas:                "prioritas",
        infeksi:                  "infeksi",
    }

    export interface KatalogFields {
        formulariumNas: string;
        formulariumRs:  string;
        kode:           string;
        namaPabrik:     string;
        namaSediaan:    string;
    }

    export interface TindakanFields {
        value:      "value";
        kodeIcd9cm: "KD_ICD9CM";
        namaIcd9cm: "NM_ICD9CM";
    }

    export interface DiagnosaFields {
        value: "value";
        kode:  "kode";
        topik: "topik";
    }

    export interface OperatorFields {
        id:     "id";
        nama:   "nama";
        jadwal: "jadwal";
    }

    export interface RegistrasiFields {
        kodeRekamMedis: string;
        nama:           string;
        noPendaftaran:  string;
        alamat:         string;
        kelamin:        string;
        caraBayar:      string;
        tanggalLahir:   string;
        noTelefon:      string;
        namaInstalasi:  string;
        namaPoli:       string;
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
            class: ".jadwalOperasiFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".noPendaftaranFld", name: "noPendaftaran"},
                    hidden_2: {class: ".alamatFld", name: "alamat"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaPasienFld", name: "namaPasien"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Lahir") ?>,
                        input: {class: ".tanggalLahirFld", name: "tanggalLahir"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Jenis Kelamin") ?>,
                        radio_1: {class: ".kelaminMale",   name: "kelamin", value: "L", label: tlm.stringRegistry._<?= $h("Laki-laki") ?>},
                        radio_2: {class: ".kelaminFemale", name: "kelamin", value: "P", label: tlm.stringRegistry._<?= $h("Perempuan") ?>}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. Telefon") ?>,
                        input: {class: ".noTelefonFld", name: "noTelefon"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Kelas") ?>,
                        input: {class: ".kelasRmFld", name: "kelasRm"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Operator") ?>,
                        select: {class: ".idDokterFld", name: "idDokter"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Rencana Operasi") ?>,
                        input: {class: ".tanggalAwalRencanaOperasiFld", name: "tanggalAwalOperasi"},
                        hidden: {class: ".tanggalAkhirRencanaOperasiFld", name: "tanggalAkhirOperasi"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Durasi Operasi") ?>,
                        input: {class: ".durasiFld", name: "durasi"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>,
                        input_1: {class: ".ruangFld", name: "ruang"},
                        input_2: {class: ".kelasFld", name: "kelas"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Tempat Tidur") ?>,
                        input: {class: ".namaTempatTidurFld", name: "namaTempatTidur"},
                        hidden: {class: ".idTempatTidurFld", name: "idTempatTidur"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Permintaan Akomodasi") ?>,
                        input: {class: ".permintaanAkomodasiFld", name: "permintaanAkomodasi"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Post OP") ?>,
                        select: {
                            class: ".postOpFld",
                            name: "postOp",
                            option_1: {value: "Pulang",      label: tlm.stringRegistry._<?= $h("Pulang") ?>},
                            option_2: {value: "Perawatan",   label: tlm.stringRegistry._<?= $h("Perawatan") ?>},
                            option_3: {value: "HCU",         label: tlm.stringRegistry._<?= $h("HCU") ?>},
                            option_4: {value: "ICU",         label: tlm.stringRegistry._<?= $h("ICU") ?>},
                            option_5: {value: "NICU / PICU", label: tlm.stringRegistry._<?= $h("NICU / PICU") ?>}
                        }
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Jenis Pembayaran") ?>,
                        input: {class: ".jenisCaraBayarFld", name: "jenisCaraBayar"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Pembayaran") ?>,
                        select: {class: ".caraBayarFld", name: "caraBayar"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Hubungan Keluarga") ?>,
                        select: {
                            class: ".hubunganKeluargaPenjaminFld",
                            name: "hubunganKeluargaPenjamin",
                            option_1: {value: 0, label: tlm.stringRegistry._<?= $h("Pribadi") ?>},
                            option_2: {value: 1, label: tlm.stringRegistry._<?= $h("Suami/Istri") ?>},
                            option_3: {value: 2, label: tlm.stringRegistry._<?= $h("Anak") ?>}
                        }
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("No. Kepesertaan") ?>,
                        input: {class: ".noPesertaJaminanFld", name: "noPesertaJaminan"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Nama Peserta") ?>,
                        input: {class: ".namaPesertaJaminanFld", name: "namaPesertaJaminan"}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Asal Wilayah (Jabodetabek)") ?>,
                        select: {
                            class: ".asalWilayahJabotabekFld",
                            name: "asalWilayahJabotabek",
                            option_1: {value: "Jakarta",       label: tlm.stringRegistry._<?= $h("Jakarta") ?>},
                            option_2: {value: "Bogor",         label: tlm.stringRegistry._<?= $h("Bogor") ?>},
                            option_3: {value: "Depok",         label: tlm.stringRegistry._<?= $h("Depok") ?>},
                            option_4: {value: "Tangerang",     label: tlm.stringRegistry._<?= $h("Tangerang") ?>},
                            option_5: {value: "Bekasi",        label: tlm.stringRegistry._<?= $h("Bekasi") ?>},
                            option_6: {value: "Non Jabotabek", label: tlm.stringRegistry._<?= $h("Non Jabotabek") ?>}
                        }
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Asal Wilayah (non Jabotabek)") ?>,
                        input: {class: ".asalWilayahNonJabotabekFld", name: "asalWilayahNonJabotabek"}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Jenis Operasi") ?>,
                        select: {
                            class: ".jenisOperasiFld",
                            name: "jenisOperasi",
                            option_1: {value: "ELEKTIF",     label: tlm.stringRegistry._<?= $h("Elektif") ?>},
                            option_2: {value: "CITO",        label: tlm.stringRegistry._<?= $h("CITO") ?>},
                            option_3: {value: "BEDAH PRIMA", label: tlm.stringRegistry._<?= $h("Bedah Prima") ?>},
                            option_4: {value: "ODC",         label: tlm.stringRegistry._<?= $h("ODC") ?>}
                        }
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Instalasi") ?>,
                        input: {class: ".idInstalasiFld", name: "idInstalasi"}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("Poli") ?>,
                        input: {class: ".idPoliFld", name: "idPoli"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Prioritas") ?>,
                        radio_1: {class: ".prioritasBiasa",  name: "prioritas", value: 0, label: tlm.stringRegistry._<?= $h("Biasa") ?>},
                        radio_2: {class: ".prioritasUrgent", name: "prioritas", value: 1, label: tlm.stringRegistry._<?= $h("Urgent") ?>}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Infectious") ?>,
                        radio_1: {class: ".infeksiNo",  name: "infeksi", value: 0, label: tlm.stringRegistry._<?= $h("Non-Infectious") ?>},
                        radio_2: {class: ".infeksiYes", name: "infeksi", value: 1, label: tlm.stringRegistry._<?= $h("Infectious") ?>}
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
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLInputElement} */  const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */  const namaPasienFld = divElm.querySelector(".namaPasienFld");
        /** @type {HTMLInputElement} */  const tanggalLahirFld = divElm.querySelector(".tanggalLahirFld");
        /** @type {HTMLInputElement} */  const kelaminMale = divElm.querySelector(".kelaminMale");
        /** @type {HTMLInputElement} */  const kelaminFemale = divElm.querySelector(".kelaminFemale");
        /** @type {HTMLInputElement} */  const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLInputElement} */  const kelasRmFld = divElm.querySelector(".kelasRmFld");
        /** @type {HTMLInputElement} */  const tanggalAwalRencanaOperasiFld = divElm.querySelector(".tanggalAwalRencanaOperasiFld");
        /** @type {HTMLInputElement} */  const tanggalAkhirRencanaOperasiFld = divElm.querySelector(".tanggalAkhirRencanaOperasiFld");
        /** @type {HTMLInputElement} */  const durasiFld = divElm.querySelector(".durasiFld");
        /** @type {HTMLInputElement} */  const ruangFld = divElm.querySelector(".ruangFld");
        /** @type {HTMLInputElement} */  const kelasFld = divElm.querySelector(".kelasFld");
        /** @type {HTMLInputElement} */  const namaTempatTidurFld = divElm.querySelector(".namaTempatTidurFld");
        /** @type {HTMLInputElement} */  const idTempatTidurFld = divElm.querySelector(".idTempatTidurFld");
        /** @type {HTMLInputElement} */  const permintaanAkomodasiFld = divElm.querySelector(".permintaanAkomodasiFld");
        /** @type {HTMLSelectElement} */ const postOpFld = divElm.querySelector(".postOpFld");
        /** @type {HTMLInputElement} */  const jenisCaraBayarFld = divElm.querySelector(".jenisCaraBayarFld");
        /** @type {HTMLSelectElement} */ const caraBayarFld = divElm.querySelector(".caraBayarFld");
        /** @type {HTMLSelectElement} */ const hubunganKeluargaPenjaminFld = divElm.querySelector(".hubunganKeluargaPenjaminFld");
        /** @type {HTMLInputElement} */  const noPesertaJaminanFld = divElm.querySelector(".noPesertaJaminanFld");
        /** @type {HTMLInputElement} */  const namaPesertaJaminanFld = divElm.querySelector(".namaPesertaJaminanFld");
        /** @type {HTMLSelectElement} */ const asalWilayahJabotabekFld = divElm.querySelector(".asalWilayahJabotabekFld");
        /** @type {HTMLInputElement} */  const asalWilayahNonJabotabekFld = divElm.querySelector(".asalWilayahNonJabotabekFld");
        /** @type {HTMLSelectElement} */ const jenisOperasiFld = divElm.querySelector(".jenisOperasiFld");
        /** @type {HTMLInputElement} */  const idInstalasiFld = divElm.querySelector(".idInstalasiFld");
        /** @type {HTMLInputElement} */  const prioritasBiasa = divElm.querySelector(".prioritasBiasa");
        /** @type {HTMLInputElement} */  const prioritasUrgent = divElm.querySelector(".prioritasUrgent");
        /** @type {HTMLInputElement} */  const infeksiYes = divElm.querySelector(".infeksiYes");
        /** @type {HTMLInputElement} */  const infeksiNo = divElm.querySelector(".infeksiNo");

        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", caraBayarFld);
        this._selects.push(caraBayarFld);

        const jadwalOperasiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".jadwalOperasiFrm"),
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.FormFields} data */
            loadData(data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                alamatFld.value = data.alamat ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                namaPasienFld.value = data.namaPasien ?? "";
                tanggalLahirFld.value = data.tanggalLahir ?? "";
                data.kelamin ? kelaminFemale.checked = true : kelaminMale.checked = true;
                noTelefonFld.value = data.noTelefon ?? "";
                kelasRmFld.value = data.kelasRm ?? "";
                idDokterWgt.value = data.idDokter ?? "";
                tanggalAwalRencanaOperasiWgt.value = data.tanggalAwalOperasi ?? "";
                tanggalAkhirRencanaOperasiFld.value = data.tanggalAkhirOperasi ?? "";
                durasiFld.value = data.durasi ?? "";
                ruangFld.value = data.ruang ?? "";
                kelasFld.value = data.kelas ?? "";
                namaTempatTidurFld.value = data.namaTempatTidur ?? "";
                idTempatTidurFld.value = data.idTempatTidur ?? "";
                permintaanAkomodasiFld.value = data.permintaanAkomodasi ?? "";
                postOpFld.value = data.postOp ?? "";
                jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
                caraBayarFld.value = data.caraBayar ?? "";
                hubunganKeluargaPenjaminFld.value = data.hubunganKeluargaPenjamin ?? "";
                noPesertaJaminanFld.value = data.noPesertaJaminan ?? "";
                namaPesertaJaminanFld.value = data.namaPesertaJaminan ?? "";
                asalWilayahJabotabekFld.value = data.asalWilayahJabotabek ?? "";
                asalWilayahNonJabotabekFld.value = data.asalWilayahNonJabotabek ?? "";
                jenisOperasiFld.value = data.jenisOperasi ?? "";
                idInstalasiFld.value = data.idInstalasi ?? "";
                data.prioritas ? prioritasUrgent.checked = true : prioritasBiasa.checked = true;
                data.infeksi ? infeksiYes.checked = true : infeksiNo.checked = true;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._element.reset();
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Rencana Operasi") ?>;
                    this._actionUrl = "<?= $addActionUrl ?>";
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            resetBtnId: false,
            onSuccessSubmit() {
                tlm.app.getWidget("_<?= $tableBookingWidgetId ?>").show();
            }
        });

        jenisCaraBayarFld.addEventListener("change", () => {
            const klp = jenisCaraBayarFld.value;
            $.post({
                url: "<?= $caraBayarUrl ?>",
                data: {jenisCaraBayar: klp},
                success(data) {
                    let options = `<option value="9999"></option>`;
                    data.forEach(val => options += `<option value="${val.kd_bayar}">${val.cara_bayar}</option>`);
                    caraBayarFld.innerHTML = options;

                    let val = "";
                    if (klp == 0) {
                        val = "001";
                    } else if (klp == 3) {
                        val = "008";
                    }
                    caraBayarFld.value = val;
                }
            });

            hubunganKeluargaFld.style.display = "none";
            if (klp == 0) { // Tunai
                caraBayarFld.value = "001";
                divElm.querySelector(".jaminan_form").style.display = "none";
            } else if (klp == 1) {
                divElm.querySelector(".jaminan_form").style.display = "block";
            } else if (klp == 2) {
                divElm.querySelector(".jaminan_form").style.display = "block";
                hubunganKeluargaFld.style.display = "block";
            } else if (klp == 3) {
                caraBayarFld.value = "008";
                divElm.querySelector(".jaminan_form").style.display = "none";
            }
        });

        const kodeRekamMedisWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRekamMedisFld"),
            errorRules: [{required: true}],
            maxItems: 1,
            valueField: "kodeRekamMedis",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.JadwalOperasi.Form.RegistrasiFields} data
             */
            assignPairs(formElm, data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                alamatFld.value = data.alamat ?? "";
                noTelefonFld.value = data.noTelefon ?? "";
                // TODO: js: uncategorized: finish this
                // ".carabayar2": "CARABAYAR" ?? "",
                // ".tgllahir": "TGL_LAHIR" ?? "",
                // ".nm_inst": "NM_INST" ?? "",
                // ".nm_poli": "NM_POLI" ?? "",
                // ".kelamin2": (data) => {return (data.kelamin == "1") ? str._<?= $h("Laki-laki") ?> : <?= $h("Perempuan") ?>}
            },
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.RegistrasiFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama}</div>`},
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.RegistrasiFields} data */
            itemRenderer(data) {return `<div class="item">${data.kodeRekamMedis} - ${data.nama}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {kodeRekamMedis: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.JadwalOperasi.Form.RegistrasiFields} */
                const obj = this.options[value];
                divElm.querySelector(".kelamin").style.display = "none";

                if (obj.kelamin == "1") {
                    divElm.querySelector(".jk4").checked = false;
                    divElm.querySelector(".jkl").checked = true;
                } else {
                    divElm.querySelector(".jk4").checked = false;
                    divElm.querySelector(".jkp").checked = true;
                }

                divElm.querySelector(".kelamin2").style.display = "block";
                divElm.querySelector(".carabayar").style.display = "none";
                divElm.querySelector(".detailuser").readonly = true;
                // TODO: js: missing function: listresep()
                listresep(obj.kodeRekamMedis);
            }
        });

        /** @see {his.FatmaPharmacy.views.JadwalOperasi.Form.OperatorFields} */
        const idDokterWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idDokterFld"),
            maxItems: 1,
            valueField: "id",
            labelField: "nama",
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $operatorUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        // TODO: js: uncategorized: set to today is the minimum day
        const tanggalAwalRencanaOperasiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAwalRencanaOperasiFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        tanggalAwalRencanaOperasiFld.addEventListener("blur", () => {
            tanggalAkhirRencanaOperasiFld.value = tanggalAwalRencanaOperasiFld.value;
        });

        durasiFld.addEventListener("keypress", (event) => {
            const charCode = (event.which) ? event.which : event.keyCode;
            return !(charCode > 31 && (charCode < 48 || charCode > 57));
        });

        asalWilayahJabotabekFld.addEventListener("change", () => {
            const controlGroupElm = closest(asalWilayahNonJabotabekFld, ".control-group");
            controlGroupElm.style.display = (asalWilayahJabotabekFld.value == "Non Jabotabek") ? "block" : "none";
        });

        // TODO: js: uncategorized: pilih satu yang bener
        idInstalasiFld.addEventListener("change", () => {
            $.post({
                url: "<?= $instalasiUrl ?>",
                data: {instalasi: idInstalasiFld.value},
                success(data) {
                    let options = `<option value="9999"></option>`;
                    data.forEach(val => options += `<option value="${val.id_poli}">${val.nama_poli}</option>`);
                    idInstalasiFld.innerHTML = options;
                }
            });
        });

        // TODO: js: uncategorized: pilih satu yang bener
        idInstalasiFld.addEventListener("change", () => {
            const controlGroupElm = closest(divElm.querySelector("#smf"), ".control-group");
            controlGroupElm.style.display = ["0", "1", "2"].includes(idInstalasiFld.value) ? "block" : "none";
        });

        // JUNK -----------------------------------------------------
        const draw = spl.TableDrawer.drawButton;

        const hubunganKeluargaFld = divElm.querySelector(".hubungan_keluarga");

        divElm.querySelector(".cekRmBtn").addEventListener("click", () => {
            $.post({
                url: "<?= $cekNoRekamMedisUrl ?>",
                /** @param {string} data */
                success(data) {
                    const dom = $(data);
                    const box = bootbox.modal(dom, "Cari Data Pasien", {backdrop: "static"});

                    dom.filter("script").each(() => {
                        $.globalEval(this.text || this.textContent || this.innerHTML || "");
                    });

                    box.querySelector(".form-actions").style.display = "none";
                    box.insertAdjacentHTML("beforeend", `<div class="modal-footer"><a class="btn close-btn" href="javascript:">Tutup</a></div>`);
                }
            });
        });

        const cariDiagnosaWgt = new spl.SelectWidget({
            element: divElm.querySelector(".cariDiagnosaFld"),
            maxItems: 1,
            valueField: "topik",
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.DiagnosaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kode} - ${data.topik}</div>`},
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.DiagnosaFields} data */
            itemRenderer(data) {return `<div class="item">${data.kode} - ${data.topik}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $diagnosaUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.JadwalOperasi.Form.DiagnosaFields} */
                const obj = this.options[value];
                const idx = this._element.id.split("_");
                divElm.querySelector("#icd10_" + idx[1]).value = obj.kode;
            }
        });

        const cariTindakanWgt = new spl.SelectWidget({
            element: divElm.querySelector(".cariTindakanFld"),
            maxItems: 1,
            valueField: "namaIcd9cm",
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.TindakanFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeIcd9cm} - ${data.namaIcd9cm}</div>`},
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.TindakanFields} data */
            itemRenderer(data) {return `<div class="item">${data.kodeIcd9cm} - ${data.namaIcd9cm}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $cariTindakanUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.JadwalOperasi.Form.TindakanFields} */
                const obj = this.options[value];
                const idx = this._element.id.split("_");
                divElm.querySelector("#icd9cm_" + idx[1]).value = obj.kodeIcd9cm;
            }
        });

        const cariAlatWgt = new spl.SelectWidget({
            element: divElm.querySelector(".cariAlatFld"),
            maxItems: 1,
            valueField: "namaSediaan",
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.KatalogFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaSediaan} (${data.kode}) - ${data.namaPabrik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Form.KatalogFields} data */
            itemRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="item" style="color:${warna}">${data.namaSediaan} (${data.kode})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $cariAlatUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        if (jenisCaraBayarFld.value != 1) {
            divElm.querySelector(".jaminan_form").style.display = "none";
        }

        hubunganKeluargaFld.style.display = "none";

        if (jenisCaraBayarFld.value == 2) {
            hubunganKeluargaFld.style.display = "block";
            divElm.querySelector(".jaminan_form").style.display = "block";
        }

        divElm.querySelector(".alat-add-btn").addEventListener("click", () => {
            let cnt = 0;
            divElm.querySelectorAll("[name*=alat_operasi_nama]").forEach(item => {
                const check = item.id.substr(18);
                if (check > cnt) cnt = check;
            });
            cnt++;

            const innerHTML = divElm.querySelector(".alat-operasi-group").innerHTML;
            const newElm = $(`<div class="alat-operasi-group">${innerHTML}</div>`);
            const button = draw({class: ".alat-remove-btn", icon: "minus"});
            newElm.querySelector(".alat-add-btn").replaceWith(button);

            const alatOperasiNamaFld = newElm.querySelector("[name*=alat_operasi_nama]");
            alatOperasiNamaFld.setAttribute("id", "alat_operasi_nama_" + cnt);
            alatOperasiNamaFld.setAttribute("name", "alat_operasi_nama[]");
            alatOperasiNamaFld.value = "";

            const alatOperasiQtyFld = newElm.querySelector("[name*=alat_operasi_qty]");
            alatOperasiQtyFld.setAttribute("id", "alat_operasi_qty_" + cnt);
            alatOperasiQtyFld.setAttribute("name", "alat_operasi_qty[]");
            alatOperasiQtyFld.value = "";

            const alatOperasiSatuanFld = newElm.querySelector("[name*=alat_operasi_satuan]");
            alatOperasiSatuanFld.setAttribute("id", "alat_operasi_satuan_" + cnt);
            alatOperasiSatuanFld.setAttribute("name", "alat_operasi_satuan[]");
            alatOperasiSatuanFld.value = "";

            divElm.querySelector(".alat-operasi-container").append(newElm);

            divElm.querySelector(".alat-remove-btn").addEventListener("click", (event) => {
                closest(event.target, ".alat-operasi-group").remove();
            });
        });

        divElm.querySelector(".alat-remove-btn").addEventListener("click", (event) => {
            closest(event.target, ".alat-operasi-group").remove();
        });

        let dgnButton = 1;
        divElm.querySelector(".diagnosa-add-btn").addEventListener("click", () => {
            dgnButton++;

            let cnt = 0;
            divElm.querySelectorAll("[name*=diagnosa]").forEach(item => {
                const check = item.id.substr(18);
                if (check > cnt) cnt = check;
            });
            cnt++;

            const innerHTML = divElm.querySelector(".diagnosa-operasi-group").innerHTML;
            const newElm = $(`<div class="diagnosa-operasi-group">${innerHTML}</div>`);
            const button = draw({class: ".diagnosa-remove-btn", icon: "minus"});
            newElm.querySelector(".diagnosa-add-btn").replaceWith(button);

            const diagnosaFld = newElm.querySelector("[name*=diagnosa]");
            diagnosaFld.setAttribute("id", "diagnosa_" + dgnButton);
            diagnosaFld.setAttribute("name", "diagnosa[]");
            diagnosaFld.value = "";

            const kodeIcd10Fld = newElm.querySelector("[name*=kd_icd10]");
            kodeIcd10Fld.setAttribute("id", "icd10_" + dgnButton);
            kodeIcd10Fld.setAttribute("name", "kd_icd10[]");
            kodeIcd10Fld.value = "";

            divElm.querySelector(".diagnosa-operasi-container").append(newElm);

            divElm.querySelector(".diagnosa-remove-btn").addEventListener("click", (event) => {
                closest(event.target, ".diagnosa-operasi-group").remove();
            });
        });

        divElm.querySelector(".diagnosa-remove-btn").addEventListener("click", (event) => {
            closest(event.target, ".diagnosa-operasi-group").remove();
        });

        let tanpaButton = 1;
        divElm.querySelector(".tindakan-add-btn").addEventListener("click", () => {
            tanpaButton++;

            let cnt = 0;
            divElm.querySelectorAll("[name*=tindakan]").forEach(item => {
                const check = item.id.substr(18);
                if (check > cnt) cnt = check;
            });
            cnt++;

            const innerHTML = divElm.querySelector(".tindakan-operasi-group").innerHTML;
            const newElm = $(`<div class="tindakan-operasi-group">${innerHTML}</div>`);
            const button = draw({class: ".tindakan-remove-btn", icon: "minus"});
            newElm.querySelector(".tindakan-add-btn").replaceWith(button);

            const tindakanFld = newElm.querySelector("[name*=tindakan]");
            tindakanFld.setAttribute("id", "tindakan_" + tanpaButton);
            tindakanFld.setAttribute("name", `tindakan[${tanpaButton}]`);
            tindakanFld.value = "";

            const icd9cmFld = newElm.querySelector("[name*=icd9cm]");
            icd9cmFld.setAttribute("id", "icd9cm_" + tanpaButton);
            icd9cmFld.setAttribute("name", `kd_icd9cm[${tanpaButton}]`);
            icd9cmFld.value = "";

            divElm.querySelector(".tindakan-operasi-container").append(newElm);

            divElm.querySelector(".tindakan-remove-btn").addEventListener("click", (event) => {
                closest(event.target, ".tindakan-operasi-group").remove();
            });
        });

        divElm.querySelector(".tindakan-remove-btn").addEventListener("click", (event) => {
            closest(event.target, ".tindakan-operasi-group").remove();
        });

        divElm.querySelector(".add-btn").addEventListener("click", () => {
            $.post({
                url: "<?= $addUrl ?>",
                data: {format: "json"},
                success(data) {
                    const dom = $(data.html);
                    const box = bootbox.modal(dom, "Tambah Pasien Baru", {backdrop: "static"});

                    dom.filter("script").each(() => {
                        $.globalEval(this.text || this.textContent || this.innerHTML || "");
                    });

                    box.querySelector(".form-actions").style.display = "none";
                    box.querySelector("form").classList.remove("well");
                    box.append(`<div class="modal-footer"></div>`);
                    const modalFooterElm = box.querySelector(".modal-footer");
                    modalFooterElm.innerHTML = box.querySelector(".form-actions .save-btn");
                    modalFooterElm.append(`<a class="btn close-btn" href="javascript:">Tutup</a>`);
                    box.querySelector(".save-btn").addEventListener("click", () => {
                        box.querySelector("form").dispatchEvent(new Event("submit"));
                    });
                }
            });
        });

        $.post({
            url: "<?= $viewNoRekamMedisUrl ?>",
            data: {format: "json", kodeRekamMedis: kodeRekamMedisWgt.value},
            success(data) {
                if (!data) {
                    const box = bootbox.alert(str._<?= $h("Data pasien tidak ditemukan.") ?>);
                    box.addEventListener("hide", () => {
                        kodeRekamMedisWgt.dispatchEvent(new Event("focus"));
                    });
                }
                kodeRekamMedisWgt.value = data.kodeRekamMedis;
                namaPasienFld.value = data.nama;
                tanggalLahirFld.value = data.tanggalLahir;
                noTelefonFld.value = data.noTelefon;
                divElm.querySelector(`input[name='jenis_kelamin'][value='${data.jenis_kelamin}']`).checked = true;
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(jadwalOperasiWgt, kodeRekamMedisWgt, idDokterWgt, tanggalAwalRencanaOperasiWgt, cariDiagnosaWgt, cariTindakanWgt, cariAlatWgt);
        tlm.app.registerWidget(this.constructor.widgetName, jadwalOperasiWgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<form id="<?= $registerId ?>" action="<?= $addActionUrl ?>">
    <div class="span5">
        <?= FH::divOpen('Kode Rekam Medis', 'no_rm') ?>
        <input id="no_rm" name="kodeRekamMedis" class="no_rm" value="<?= FH::val($data, 'no_rm') ?>" readonly />
        <button class="cekRmBtn">Cari</button>
        <?= FH::divClose('no_rm') ?>

        <?= FH::input('Nama Pasien', [
            'name' => 'nama',
            'id' => 'nama',
            'readonly',
            'class' => 'nama detailuser',
            'value' => FH::val($data, 'nama')
        ]) ?>

        <?= FH::input('Tanggal Lahir', [
            'name' => 'tanggal_lahir',
            'id' => 'tanggal_lahir',
            'readonly',
            'class' => 'tanggal_lahir tgllahir detailuser',
            'value' => FH::val($data, 'tanggal_lahir')
        ]) ?>

        <input type="hidden" name="no_daftar" class="no_daftar"/>
        <input type="hidden" name="alamat" class="alamat"/>

        Jenis Kelamin
        <input type="radio" class="jk4 jkl detailuser" name="kelamin" value="L"/> Laki-laki
        <input type="radio" class="jk4 jkp detailuser" name="kelamin" value="P"/> Perempuan

        No. Telpon
        <input class="notelp" name="noTelefon" id="no_telp" readonly />

        Kelas
        <input class="input-medium" name="kelasrm" id="kelasrm" placeholder="Kelas"/>

        Operator
        <input name="operator" id="operator"/>
        <input type="hidden" name="id_dokter" id="id_dokter"/>

        Rencana Operasi
        <input class="rencana_operasi_tglFld" name="tanggalAwalOperasi" id="rencana_operasi_tgl"/>
        <input type="hidden" name="rencana_operasi_end_tgl" id="rencana_operasi_end_tgl"/>

        Durasi Operasi
        <input name="durasi" id="durasi" placeholder="Jam"/>

        Ruang Rawat
        <input name="ruang" id="ruang" readonly placeholder="Akomodasi"/>
        <input name="kelas" id="kelas" readonly placeholder="Kamar/Kelas"/>

        Tempat Tidur
        <input name="namaTempatTidur" id="tempat_tidur" readonly placeholder="Tempat Tidur"/>
        <input type="hidden" id="tempat_tidur_id" name="tempat_tidur_id"/>

        Permintaan Akomodasi
        <input name="permintaanAkomodasi" id="request_akomodasi" placeholder="Permintaan Akomodasi"/>
    </div>

    <div class="span6">
        <?= FH::select('Post OP', 'post_op', MH::dropdown_post_op(), FH::val($data, 'post_op')) ?>
        <?= FH::input('Jenis Pembayaran', 'jenis_cara_bayar', '', ['class' => 'carabayar', 'readonly']) ?>

        <div class="jaminan_form">
            <?= FH::select('Pembayaran', 'cara_bayar', MH::dropdown_cara_bayar(FH::val($data, 'jenis_cara_bayar', "0")), FH::val($data, 'cara_bayar', '001')) ?>
            <div class="hubungan_keluarga">
                <?= FH::select('Hubungan Keluarga', 'hubungan_keluarga_penjamin', MH::dropdown_jaminan_keluarga(), FH::val($data, 'hubungan_keluarga_penjamin')) ?>
            </div>
            <?= FH::input('No. Kepesertaan', 'no_peserta_jaminan', FH::val($data, 'no_peserta_jaminan')) ?>
            <?= FH::input('Nama Peserta', 'nama_peserta_jaminan', FH::val($data, 'nama_peserta_jaminan')) ?>
            <?= FH::select('Asal Wilayah', 'asal_wilayah_jabotabek', MH::dropdown_asal_wilayah(), FH::val($data, 'asal_wilayah_jabotabek')) ?>
            <?= FH::input('Asal Wilayah (non Jabotabek)', 'asal_wilayah', FH::val($data, 'asal_wilayah')) ?>
        </div>

        <?= FH::divOpen('Diagnosa', 'diagnosa') ?>
        <div class="diagnosa-operasi-container">
            <?php if (is_array($daftarDiagnosa)): ?>
                <?php foreach ($daftarDiagnosa as $diagnosa => $row): ?>
                    <input class="form-control input-lg cariDiagnosaFld" id="diagnosa_<?= $no ?>" name="diagnosa[<?= $no ?>]" value="<?= $row->diagnosa ?>">
                    <input type="hidden" id="icd10_<?= $no ?>" name="kd_icd10[<?= $no ?>]">
                    <?php if ($diagnosa == 0): ?>
                        <button class="diagnosa-add-btn" type="button" title="Tambah Diagnosa"><i class="icon-plus"></i></button>
                    <?php else: ?>
                        <button class="diagnosa-remove-btn" type="button" title="Hapus Diagnosa"><i class="icon-minus"></i></button>
                    <?php endif ?>
                <?php endforeach ?>
            <?php else: ?>
                <input class="cariDiagnosaFld" id="diagnosa_1" name="diagnosa[]"/>
                <input type="hidden" id="icd10_1" name="kd_icd10[]"/>
                <button class="diagnosa-add-btn" title="Diagnosa baru"><i class="icon-plus"></i></button>
            <?php endif ?>
        </div>
        <?= FH::divClose('diagnosa') ?>

        <?= FH::divOpen('Tindakan', 'tindakan') ?>
        <div class="tindakan-operasi-container">
            <?php if (is_array($daftarTindakan)): ?>
                <?php foreach ($daftarTindakan as $tindakan => $row): ?>
                    <?php $not = $tindakan + 1 ?>
                    <div class="tindakan-operasi-group">
                        <input class="form-control input-lg cariTindakanFld" name="tindakan[<?= $not ?>]" id="tindakan_<?= $not ?>" value="<?= $row->tindakan ?>"/>
                        <input type="hidden" id="icd9cm_<?= $not ?>" name="kd_icd9cm[<?= $no ?>]"/>
                        <?php if ($tindakan == 0): ?>
                            <button class="tindakan-add-btn" title="Tambah tindakan"><i class="icon-plus"></i></button>
                        <?php else: ?>
                            <button class="tindakan-remove-btn" title="Hapus tindakan"><i class="icon-minus"></i></button>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <div class="tindakan-operasi-group">
                    <input class="cariTindakanFld" name="tindakan[]" id="tindakan_1"/>
                    <input type="hidden" id="icd9cm_1" name="kd_icd9cm[]"/>
                    <button class="tindakan-add-btn" title="tindakan baru"><i class="icon-plus"></i></button>
                </div>
            <?php endif ?>
        </div>
        <?= FH::divClose('tindakan') ?>

        <?= FH::select('Jenis Operasi', 'jenis_operasi', ['ELEKTIF' => 'ELEKTIF', 'CITO' => 'CITO', 'BEDAH PRIMA' => 'BEDAH PRIMA', 'ODC' => 'ODC']) ?>

        <?php if ($userData->idInstalasi && $userData->idInstalasi != 9999): ?>
            <input type="hidden" value="<?= $userData->idInstalasi ?>" name="idInstalasi"/>
        <?php else: ?>
            <?= FH::input('Instalasi', 'id_instalasi', '', ['class' => 'nm_inst', 'readonly']) ?>
        <?php endif ?>

        <?php if ($userData->idPoli && $userData->idPoli != 9999): ?>
            <input type="hidden" value="<?= $userData->idPoli ?>" name="id_poli"/>
        <?php else: ?>
            <?= FH::input('Poli', 'id_poli', '', ['class' => 'input-large nm_poli', 'readonly']) ?>
        <?php endif ?>

        Prioritas
        <select name="prioritas">
            <option value="0">Biasa</option>
            <option value="1">Urgent</option>
        </select>

        Infectious
        <select name="infeksi">
            <option value="0">Non-Infectious</option>
            <option value="1">Infectious</option>
        </select>

        <?= FH::divOpen('Alat Operasi', 'alat_operasi') ?>
        <div class="alat-operasi-container">
            <?php if (is_array($alatOperasi)): ?>
                <?php foreach ($alatOperasi as $idx => $row): ?>
                    <?php $no = $idx + 1 ?>
                    <input id="alat_operasi_nama_<?= $no ?>" name="alat_operasi_nama[<?= $no ?>]" value="<?= $row->namaAlat ?>" placeholder="Nama Alat" class="cariAlatFld"/>
                    <input id="alat_operasi_qty_<?= $no ?>" name="alat_operasi_qty[<?= $no ?>]" value="<?= $row->jumlah ?>" placeholder="Jumlah"/>
                    <input id="alat_operasi_satuan_<?= $no ?>" name="alat_operasi_satuan[<?= $no ?>]" value="<?= $row->satuan ?>" placeholder="Satuan"/>
                    <?php if ($idx == 0): ?>
                        <button class="alat-add-btn" type="button" title="Tambah Alat Operasi"><i class="icon-plus"></i></button>
                    <?php else: ?>
                        <button class="alat-remove-btn" type="button" title="Hapus Alat Operasi"><i class="icon-minus"></i></button>
                    <?php endif ?>
                <?php endforeach ?>

            <?php else: ?>
                <input id="alat_operasi_nama_1" name="alat_operasi_nama[]" placeholder="Nama Alat" class="cariAlatFld"/>
                <input id="alat_operasi_qty_1" name="alat_operasi_qty[]" placeholder="Jumlah"/>
                <input id="alat_operasi_satuan_1" name="alat_operasi_satuan[]" placeholder="Satuan"/>
                <button class="alat-add-btn" title="Pasien baru"><i class="icon-plus"></i></button>
            <?php endif ?>
        </div>
        <?= FH::divClose('alat_operasi') ?>
    </div>

    <button name="submit" value="save" id="submit">Simpan</button>
    <a href="<?= $tableBookingWidgetId ?>" class="btn">Batal</a>
</form>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
