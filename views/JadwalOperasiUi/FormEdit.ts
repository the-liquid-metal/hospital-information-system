<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\JadwalOperasiUi;

use tlm\libs\LowEnd\components\FormHelper as FH;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/edit.php the original file
 */
final class FormEdit
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
        string $viewTempatTidurUrl,
        string $caraBayarUrl,
        string $pasienDataUrl,
        string $operatorUrl,
        string $instalasiUrl,
        string $cariDiagnosaUrl,
        string $cariTindakanUrl,
        string $cariAlatUrl,
        string $deleteUrl,
        string $tableBookingWidgetId,
        string $hubunganKeluargaPenjaminSelect,
        string $instalasiSelect,
        string $poliSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $data = new \stdClass;
        $daftarDiagnosaOperasi = [];
        $daftarTindakanOperasi = [];
        $daftarAlatOperasi = [];
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.JadwalOperasi.Edit {
    export interface FormFields {
        kodeRekamMedis:           "no_rm",
        namaPasien:               "nama",
        tanggalLahir:             "tanggal_lahir",
        kelamin:                  "jenis_kelamin",
        noTelefon:                "no_telp",
        idDokter:                 "id_dokter",
        tanggalOperasi:           "rencana_operasi_tgl",
        jamMulaiOperasi:          "rencana_operasi_jam",
        jamAkhirOperasi:          "rencana_operasi_end_jam",
        tanggalAkhirOperasi:      "rencana_operasi_end_tgl",
        durasi:                   "durasi",
        ruangRawat:               "ruang",
        kelas:                    "kelas",
        idTempatTidur:            "tempat_tidur_id",
        jenisCaraBayar:           "jenis_cara_bayar",
        permintaanAkomodasi:      "request_akomodasi",
        postOp:                   "post_op",
        hubunganKeluargaPenjamin: "hubungan_keluarga_penjamin",
        noPesertaJaminan:         "no_peserta_jaminan",
        namaPesertaJaminan:       "nama_peserta_jaminan",
        asalWilayahJabotabek:     "asal_wilayah_jabotabek",
        asalWilayah:              "asal_wilayah",
        jenisOperasi:             "jenis_operasi",
        idInstalasi:              "id_instalasi",
        idPoli:                   "id_poli",
        prioritas:                "prioritas",
        infeksi:                  "infeksi",
    }

    export interface AjaxFields {
        id:    "id",
        ruang: "ruang",
        kamar: "kamar",
        kelas: "kelas",
        nomor: "nomor", // ???
        nama:  "nama", // ???
    }

    export interface TindakanFields {
        value:      "value";
        kodeIcd9cm: "KD_ICD9CM";
        namaIcd9cm: "NM_ICD9CM";
    }

    export interface KatalogFields {
        formulariumNas: string;
        formulariumRs:  string;
        kode:           string;
        namaPabrik:     string;
        namaSediaan:    string;
    }

    export interface DiagnosaFields {
        value: "value";
        kode:  "kode";
        topik: "topik";
    }

    export interface DokterFields {
        id:     "id";
        jadwal: "jadwal";
    }

    export interface ModalFields {
        kodeRekamMedis:   "no_rm";
        namaPasien:       "nama";
        tempatLahir:      "tempat_lahir";
        tanggalLahir:     "tanggal_lahir";
        kelamin:          "jenis_kelamin";
        golonganDarah:    "golongan_darah";
        alamatJalan:      "alamat_jalan";
        alamatRt:         "alamat_rt";
        alamatRw:         "alamat_rw";
        alamatKodePos:    "alamat_kode_pos";
        noTelefon:        "no_telpon";
        noHandphone:      "no_hp";
        provinsi:         "almt_nama_propinsi";
        kota:             "almt_nama_kota";
        kecamatan:        "almt_nama_camat";
        kelurahan:        "almt_nama_lurah";
        pekerjaan:        "nama_pekerjaan";
        agama:            "nama_agama";
        statusPerkawinan: "status_nikah";
        pendidikan:       "nama_pendidikan";
        wargaNegara:      "nama_negara";
        sukuBangsa:       "suku_bangsa";
        noIdentitas:      "nomor_id";
        alamatKerabat:    "kerabat_alamat_jalan";
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean}}
     */
    static getAccess(role) {
        const pool = {
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".editJadwalOperasiFrm",
            row_1: {
                widthColumn: {
                    heading3: {class: ".formTitleTxt"}
                }
            },
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"},
                        rButton: {class: ".kodeRekamMedisBtn"}
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
                        label: tlm.stringRegistry._<?= $h("Operator") ?>,
                        select: {class: ".idDokterFld", name: "idDokter"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Operasi") ?>,
                        input: {class: ".tanggalOperasiFld", name: "tanggalOperasi"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Jam Mulai Operasi") ?>,
                        input: {class: ".jamMulaiOperasiFld", name: "jamMulaiOperasi"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Jam Akhir Operasi") ?>,
                        input: {class: ".jamAkhirOperasiFld", name: "jamAkhirOperasi"},
                        hidden: {class: ".tanggalAkhirOperasiFld", name: "tanggalAkhirOperasi"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Durasi Operasi") ?>,
                        input: {class: ".durasiFld", name: "durasi"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>,
                        input: {class: ".ruangRawatFld", name: "ruangRawat"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Kamar/Kelas") ?>,
                        input: {class: ".kelasFld", name: "kelas"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Tempat Tidur") ?>,
                        select: {class: ".idTempatTidurFld", name: "idTempatTidur"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Jenis Pembayaran") ?>,
                        input: {class: ".jenisCaraBayarFld", name: "jenisCaraBayar"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Permintaan Akomodasi") ?>,
                        input: {class: ".permintaanAkomodasiFld", name: "permintaanAkomodasi"}
                    },
                    formGroup_16: {
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
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Hubungan Keluarga") ?>,
                        select: {class: ".hubunganKeluargaPenjaminFld", name: "hubunganKeluargaPenjamin"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("No. Kepesertaan") ?>,
                        input: {class: ".noPesertaJaminanFld", name: "noPesertaJaminan"}
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Nama Peserta") ?>,
                        input: {class: ".namaPesertaJaminanFld", name: "namaPesertaJaminan"}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Asal Wilayah (Jabotabek)") ?>,
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
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Asal Wilayah (non Jabotabek)") ?>,
                        input: {class: ".asalWilayahFld", name: "asalWilayah"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Jenis Operasi") ?>,
                        select: {
                            class: ".jenisOperasiFld",
                            name: "jenisOperasi",
                            option_1: {value: "CITO",        label: tlm.stringRegistry._<?= $h("CITO") ?>},
                            option_2: {value: "ELEKTIF",     label: tlm.stringRegistry._<?= $h("Elektif") ?>},
                            option_3: {value: "BEDAH PRIMA", label: tlm.stringRegistry._<?= $h("Bedah Prima") ?>},
                            option_4: {value: "ODC",         label: tlm.stringRegistry._<?= $h("ODC") ?>}
                        }
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("Instalasi") ?>,
                        select: {class: ".idInstalasiFld", name: "idInstalasi"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Poli") ?>,
                        select: {class: ".idPoliFld", name: "idPoli"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Prioritas") ?>,
                        radio_1: {class: ".prioritasBiasa",  name: "prioritas", value: 0, label: tlm.stringRegistry._<?= $h("Biasa") ?>},
                        radio_2: {class: ".prioritasUrgent", name: "prioritas", value: 1, label: tlm.stringRegistry._<?= $h("Urgent") ?>}
                    },
                    formGroup_26: {
                        label: tlm.stringRegistry._<?= $h("Infectious") ?>,
                        radio_1: {class: ".infeksiNo",  name: "infeksi", value: 0, label: tlm.stringRegistry._<?= $h("Non-Infectious") ?>},
                        radio_2: {class: ".infeksiYes", name: "infeksi", value: 1, label: tlm.stringRegistry._<?= $h("Infectious") ?>}
                    }
                }
            },
            row_3: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            },
            // more elements are here!
        },
        modal: {
            title: tlm.stringRegistry._<?= $h("Detail Data Pasien") ?>,
            row_1: {
                column_1: {
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        staticText: {class: ".kodeRekamMedisStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        staticText: {class: ".namaPasienStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tempat Lahir") ?>,
                        staticText: {class: ".tempatLahirStc"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Lahir") ?>,
                        staticText: {class: ".tanggalLahirStc"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        staticText: {class: ".kelaminStc"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Golongan Darah") ?>,
                        staticText: {class: ".golonganDarahStc"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Alamat") ?>,
                        staticText: {class: ".alamatStc"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("No. Telefon") ?>,
                        staticText: {class: ".noTelefonStc"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("No. Handphone") ?>,
                        staticText: {class: ".noHandphoneStc"}
                    }
                },
                column_2: {
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Provinsi") ?>,
                        staticText: {class: ".provinsiStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kota/Kabupaten") ?>,
                        staticText: {class: ".kotaStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Kecamatan") ?>,
                        staticText: {class: ".kecamatanStc"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Kelurahan") ?>,
                        staticText: {class: ".kelurahanStc"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Pekerjaan") ?>,
                        staticText: {class: ".pekerjaanStc"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Agama") ?>,
                        staticText: {class: ".agamaStc"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Status Perkawinan") ?>,
                        staticText: {class: ".statusPerkawinanStc"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Pendidikan") ?>,
                        staticText: {class: ".pendidikanStc"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Warga Negara") ?>,
                        staticText: {class: ".wargaNegaraStc"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Suku Bangsa") ?>,
                        staticText: {class: ".sukuBangsaStc"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("No. Identitas") ?>,
                        staticText: {class: ".noIdentitasStc"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Alamat Kerabat") ?>,
                        staticText: {class: ".alamatKerabatStc"}
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const closest = spl.util.closestParent;
        const {toUserDate: userDate, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const jenisCaraBayarFld = divElm.querySelector(".jenisCaraBayarFld");
        /** @type {HTMLDivElement} */    const hubunganKeluargaFld = divElm.querySelector(".hubungan_keluarga");
        /** @type {HTMLSelectElement} */ const asalWilayahJabotabekFld = divElm.querySelector(".asalWilayahJabotabekFld");
        /** @type {HTMLInputElement} */  const kodeRekamMedisFld = divElm.querySelector(".kodeRekamMedisFld");
        /** @type {HTMLInputElement} */  const namaPasienFld = divElm.querySelector(".namaPasienFld");
        /** @type {HTMLInputElement} */  const tanggalLahirFld = divElm.querySelector(".tanggalLahirFld");
        /** @type {HTMLInputElement} */  const kelaminMale = divElm.querySelector(".kelaminMale");
        /** @type {HTMLInputElement} */  const kelaminFemale = divElm.querySelector(".kelaminFemale");
        /** @type {HTMLInputElement} */  const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLInputElement} */  const tanggalOperasiFld = divElm.querySelector(".tanggalOperasiFld");
        /** @type {HTMLInputElement} */  const jamMulaiOperasiFld = divElm.querySelector(".jamMulaiOperasiFld");
        /** @type {HTMLInputElement} */  const jamAkhirOperasiFld = divElm.querySelector(".jamAkhirOperasiFld");
        /** @type {HTMLInputElement} */  const tanggalAkhirOperasiFld = divElm.querySelector(".tanggalAkhirOperasiFld");
        /** @type {HTMLInputElement} */  const durasiFld = divElm.querySelector(".durasiFld");
        /** @type {HTMLInputElement} */  const ruangRawatFld = divElm.querySelector(".ruangRawatFld");
        /** @type {HTMLInputElement} */  const kelasFld = divElm.querySelector(".kelasFld");
        /** @type {HTMLInputElement} */  const idTempatTidurFld = divElm.querySelector(".idTempatTidurFld");
        /** @type {HTMLInputElement} */  const permintaanAkomodasiFld = divElm.querySelector(".permintaanAkomodasiFld");
        /** @type {HTMLSelectElement} */ const postOpFld = divElm.querySelector(".postOpFld");
        /** @type {HTMLSelectElement} */ const hubunganKeluargaPenjaminFld = divElm.querySelector(".hubunganKeluargaPenjaminFld");
        /** @type {HTMLInputElement} */  const noPesertaJaminanFld = divElm.querySelector(".noPesertaJaminanFld");
        /** @type {HTMLInputElement} */  const namaPesertaJaminanFld = divElm.querySelector(".namaPesertaJaminanFld");
        /** @type {HTMLInputElement} */  const asalWilayahFld = divElm.querySelector(".asalWilayahFld");
        /** @type {HTMLSelectElement} */ const jenisOperasiFld = divElm.querySelector(".jenisOperasiFld");
        /** @type {HTMLSelectElement} */ const idInstalasiFld = divElm.querySelector(".idInstalasiFld");
        /** @type {HTMLSelectElement} */ const idPoliFld = divElm.querySelector(".idPoliFld");
        /** @type {HTMLInputElement} */  const prioritasUrgent = divElm.querySelector(".prioritasUrgent");
        /** @type {HTMLInputElement} */  const prioritasBiasa = divElm.querySelector(".prioritasBiasa");
        /** @type {HTMLInputElement} */  const infeksiYes = divElm.querySelector(".infeksiYes");
        /** @type {HTMLInputElement} */  const infeksiNo = divElm.querySelector(".infeksiNo");

        /** @type {HTMLDivElement} */    const kodeRekamMedisStc = divElm.querySelector(".kodeRekamMedisStc");
        /** @type {HTMLDivElement} */    const namaPasienStc = divElm.querySelector(".namaPasienStc");
        /** @type {HTMLDivElement} */    const tempatLahirStc = divElm.querySelector(".tempatLahirStc");
        /** @type {HTMLDivElement} */    const tanggalLahirStc = divElm.querySelector(".tanggalLahirStc");
        /** @type {HTMLDivElement} */    const kelaminStc = divElm.querySelector(".kelaminStc");
        /** @type {HTMLDivElement} */    const golonganDarahStc = divElm.querySelector(".golonganDarahStc");
        /** @type {HTMLDivElement} */    const alamatStc = divElm.querySelector(".alamatStc");
        /** @type {HTMLDivElement} */    const noTelefonStc = divElm.querySelector(".noTelefonStc");
        /** @type {HTMLDivElement} */    const noHandphoneStc = divElm.querySelector(".noHandphoneStc");
        /** @type {HTMLDivElement} */    const provinsiStc = divElm.querySelector(".provinsiStc");
        /** @type {HTMLDivElement} */    const kotaStc = divElm.querySelector(".kotaStc");
        /** @type {HTMLDivElement} */    const kecamatanStc = divElm.querySelector(".kecamatanStc");
        /** @type {HTMLDivElement} */    const kelurahanStc = divElm.querySelector(".kelurahanStc");
        /** @type {HTMLDivElement} */    const pekerjaanStc = divElm.querySelector(".pekerjaanStc");
        /** @type {HTMLDivElement} */    const agamaStc = divElm.querySelector(".agamaStc");
        /** @type {HTMLDivElement} */    const statusPerkawinanStc = divElm.querySelector(".statusPerkawinanStc");
        /** @type {HTMLDivElement} */    const pendidikanStc = divElm.querySelector(".pendidikanStc");
        /** @type {HTMLDivElement} */    const wargaNegaraStc = divElm.querySelector(".wargaNegaraStc");
        /** @type {HTMLDivElement} */    const sukuBangsaStc = divElm.querySelector(".sukuBangsaStc");
        /** @type {HTMLDivElement} */    const noIdentitasStc = divElm.querySelector(".noIdentitasStc");
        /** @type {HTMLDivElement} */    const alamatKerabatStc = divElm.querySelector(".alamatKerabatStc");

        tlm.app.registerSelect("_<?= $hubunganKeluargaPenjaminSelect ?>", hubunganKeluargaPenjaminFld);
        tlm.app.registerSelect("_<?= $instalasiSelect ?>", idInstalasiFld);
        tlm.app.registerSelect("_<?= $poliSelect ?>", idPoliFld);
        this._selects.push(hubunganKeluargaPenjaminFld, idInstalasiFld, idPoliFld);

        const editJadwalOperasiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".editJadwalOperasiFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.FormFields} data */
            loadData(data) {
                kodeRekamMedisFld.value = data.kodeRekamMedis ?? "";
                namaPasienFld.value = data.namaPasien ?? "";
                tanggalLahirFld.value = data.tanggalLahir ?? "";
                data.kelamin ? kelaminMale.checked = true : kelaminFemale.checked = true;
                noTelefonFld.value = data.noTelefon ?? "";
                idDokterWgt.value = data.idDokter ?? "";
                tanggalOperasiWgt.value = data.tanggalOperasi ?? "";
                jamMulaiOperasiFld.value = data.jamMulaiOperasi ?? "";
                jamAkhirOperasiFld.value = data.jamAkhirOperasi ?? "";
                tanggalAkhirOperasiFld.value = data.tanggalAkhirOperasi ?? "";
                durasiFld.value = data.durasi ?? "";
                ruangRawatFld.value = data.ruangRawat ?? "";
                kelasFld.value = data.kelas ?? "";
                idTempatTidurFld.value = data.idTempatTidur ?? "";
                jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
                permintaanAkomodasiFld.value = data.permintaanAkomodasi ?? "";
                postOpFld.value = data.postOp ?? "";
                hubunganKeluargaPenjaminFld.value = data.hubunganKeluargaPenjamin ?? "";
                noPesertaJaminanFld.value = data.noPesertaJaminan ?? "";
                namaPesertaJaminanFld.value = data.namaPesertaJaminan ?? "";
                asalWilayahJabotabekFld.value = data.asalWilayahJabotabek ?? "";
                asalWilayahFld.value = data.asalWilayah ?? "";
                jenisOperasiFld.value = data.jenisOperasi ?? "";
                idInstalasiFld.value = data.idInstalasi ?? "";
                idPoliFld.value = data.idPoli ?? "";
                data.prioritas ? prioritasUrgent.checked = true : prioritasBiasa.checked = true;
                data.infeksi ? infeksiYes.checked = true : infeksiNo.checked = true;
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                edit() {
                    formTitleTxt.innerHTML = str._<?= $h("Edit Rencana Operasi") ?>;

                    if (jenisCaraBayarFld.value != 1) {
                        divElm.querySelector(".jaminan_form").style.display = "none";
                    }

                    hubunganKeluargaFld.style.display = "none";

                    if (jenisCaraBayarFld.value == 2) {
                        hubunganKeluargaFld.style.display = "block";
                        divElm.querySelector(".jaminan_form").style.display = "block";
                    }

                    if (asalWilayahJabotabekFld.value != "Non Jabotabek") {
                        closest(asalWilayahFld, ".control-group").style.display = "none";
                    }

                    $.post({
                        url: "<?= $viewTempatTidurUrl ?>",
                        data: {format: "json", idTempatTidur: idTempatTidurFld.value},
                        /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.AjaxFields} data */
                        success(data) {
                            idTempatTidurFld.value = data.id;
                            ruangRawatFld.value = data.ruang;
                            kelasFld.value = data.kamar + "/" + data.kelas;
                        }
                    });
                }
            },
            onSuccessSubmit() {
                tlm.app.getWidget("_<?= $tableBookingWidgetId ?>").show();
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        jenisCaraBayarFld.addEventListener("change", () => {
            const klp = jenisCaraBayarFld.value;
            $.post({
                url: "<?= $caraBayarUrl ?>",
                data: {jenisCaraBayar: klp},
                success(data) {
                    let options = `<option value="999"></option>`;
                    data.forEach(val => options += `<option value="${val.kd_bayar}">${val.cara_bayar}</option>`);
                    divElm.querySelector("#cara_bayar").innerHTML = options;

                    let val = "";
                    if (klp == 0) {
                        val = "001";
                    } else if (klp == 3) {
                        val = "008";
                    }
                    divElm.querySelector("#cara_bayar").select2("val", val);
                }
            });

            hubunganKeluargaFld.style.display = "none";
            if (klp == 0) { // Tunai
                divElm.querySelector("#cara_bayar").fieldWidget.value = "001";
                divElm.querySelector(".jaminan_form").style.display = "none";
            } else if (klp == 1) {
                divElm.querySelector(".jaminan_form").style.display = "block";
            } else if (klp == 2) {
                divElm.querySelector(".jaminan_form").style.display = "block";
                hubunganKeluargaFld.style.display = "block";
            } else if (klp == 3) {
                divElm.querySelector("#cara_bayar").fieldWidget.value = "008";
                divElm.querySelector(".jaminan_form").style.display = "none";
            }
        });

        asalWilayahJabotabekFld.addEventListener("change", () => {
            const controlGroupElm = closest(asalWilayahFld, ".control-group");
            controlGroupElm.style.display = (asalWilayahJabotabekFld.value == "Non Jabotabek") ? "block" : "none";
        });

        divElm.querySelector(".kodeRekamMedisBtn").addEventListener("click", () => {
            $.post({
                url: "<?= $pasienDataUrl ?>",
                data: {kodeRekamMedis: kodeRekamMedisFld.value},
                /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.ModalFields} data */
                success(data) {
                    kodeRekamMedisStc.innerHTML = data.kodeRekamMedis;
                    namaPasienStc.innerHTML = data.namaPasien;
                    tempatLahirStc.innerHTML = data.tempatLahir;
                    tanggalLahirStc.innerHTML = userDate(data.tanggalLahir);
                    kelaminStc.innerHTML = data.kelamin;
                    golonganDarahStc.innerHTML = data.golonganDarah;
                    alamatStc.innerHTML = `${data.alamatJalan} ${data.alamatRt} ${data.alamatRw} + ${data.alamatKodePos}`;
                    noTelefonStc.innerHTML = data.noTelefon;
                    noHandphoneStc.innerHTML = data.noHandphone;
                    provinsiStc.innerHTML = data.provinsi;
                    kotaStc.innerHTML = data.kota;
                    kecamatanStc.innerHTML = data.kecamatan;
                    kelurahanStc.innerHTML = data.kelurahan;
                    pekerjaanStc.innerHTML = data.pekerjaan;
                    agamaStc.innerHTML = data.agama;
                    statusPerkawinanStc.innerHTML = data.statusPerkawinan;
                    pendidikanStc.innerHTML = data.pendidikan;
                    wargaNegaraStc.innerHTML = data.wargaNegara;
                    sukuBangsaStc.innerHTML = data.sukuBangsa;
                    noIdentitasStc.innerHTML = data.noIdentitas;
                    alamatKerabatStc.innerHTML = data.alamatKerabat;
                }
            });
        });

        /** @see {his.FatmaPharmacy.views.JadwalOperasi.Edit.DokterFields} */
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

        tanggalOperasiFld.addEventListener("blur", () => {
            tanggalAkhirOperasiFld.value = tanggalOperasiFld.value;
        });

        const tanggalOperasiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalOperasiFld"),
            // weekStart: 1,
            ...tlm.dateWidgetSetting
        });

        // TODO: js: uncategorized: pilih satu yang bener
        idInstalasiFld.addEventListener("change", () => {
            $.post({
                url: "<?= $instalasiUrl ?>",
                data: {id: idInstalasiFld.value},
                success(data) {
                    let options = `<option value="999"></option>`;
                    data.forEach(val => options += `<option value="${val.id_poli}">${val.nama_poli}</option>`);
                    idPoliFld.innerHTML = options;
                }
            });
        });

        // TODO: js: uncategorized: pilih satu yang bener
        idInstalasiFld.addEventListener("change", () => {
            const controlGroupElm = closest(divElm.querySelector("#smf"), ".control-group");
            const val = idInstalasiFld.value;
            controlGroupElm.style.display = (val == "0" || val == "1" || val == "2") ? "block" : "none";
        });

        // JUNK -----------------------------------------------------
        const draw = spl.TableDrawer.drawButton;

        const cariDiagnosaWgt = new spl.SelectWidget({
            element: divElm.querySelector(".cariDiagnosaFld"),
            maxItems: 1,
            valueField: "topik",
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.DiagnosaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kode} (${data.topik})</div>`},
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.DiagnosaFields} data */
            itemRenderer(data) {return `<div class="item">${data.kode} (${data.topik})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $cariDiagnosaUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.JadwalOperasi.Edit.DiagnosaFields} */
                const obj = this.options[value];
                const idx = this.element.id.split("_");
                divElm.querySelector("#icd10_" + idx[1]).value = obj.kode;
            }
        });

        const cariTindakanWgt = new spl.SelectWidget({
            element: divElm.querySelector(".cariTindakanFld"),
            maxItems: 1,
            valueField: "namaIcd9cm",
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.TindakanFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeIcd9cm} - ${data.namaIcd9cm}</div>`},
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.TindakanFields} data */
            itemRenderer(data) {return `<div class="item">${data.kodeIcd9cm} - ${data.namaIcd9cm}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $cariTindakanUrl ?>",
                    data: {q: typed},
                    error() { processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.JadwalOperasi.Edit.TindakanFields} */
                const obj = this.options[value];
                const idx = this.element.id.split("_");
                divElm.querySelector("#icd9cm_" + idx[1]).value = obj.kodeIcd9cm;
            }
        });

        const cariAlatWgt = new spl.SelectWidget({
            element: divElm.querySelector(".cariAlatFld"),
            maxItems: 1,
            valueField: "namaSediaan",
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.KatalogFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaSediaan} (${data.kode}) - ${data.namaPabrik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.KatalogFields} data */
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

        const tindakanWgt = new spl.SelectWidget({
            element: divElm.querySelector("#tindakan"),
            maxItems: 1,
            valueField: "kodeIcd9cm",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.TindakanFields} data
             */
            assignPairs(formElm, data) {
                // TODO: js: uncategorized: finish this
                // "[name=kd_icd9]": data.kodeIcd9cm ?? ""
            },
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.TindakanFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeIcd9cm} - ${data.namaIcd9cm}</div>`},
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.Edit.TindakanFields} data */
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
            }
        });

        divElm.querySelector(".diagnosa-add-btn").addEventListener("click", () => {
            let cnt = 0;
            divElm.querySelectorAll("[name*=diagnosa]").forEach(item => {
                const check = item.id.substr(9);
                if (check > cnt) cnt = check;
            });
            cnt++;

            const innerHTML = divElm.querySelector(".diagnosa-operasi-group").innerHTML;
            const newElm = $(`<div class="diagnosa-operasi-group">${innerHTML}</div>`);
            const button = draw({class: ".diagnosa-remove-btn", icon: "minus"});
            newElm.querySelector(".diagnosa-add-btn").replaceWith(button);

            const diagnosaFld = newElm.querySelector("[name*=diagnosa]");
            diagnosaFld.setAttribute("id", "diagnosa_" + cnt);
            diagnosaFld.setAttribute("name", `diagnosa[${cnt}]`);
            diagnosaFld.value = "";

            const kodeIcd10Fld = newElm.querySelector("[name*=kd_icd10]");
            kodeIcd10Fld.setAttribute("id", "icd10_" + cnt);
            kodeIcd10Fld.setAttribute("name", `kd_icd10[${cnt}]`);
            kodeIcd10Fld.value = "";

            divElm.querySelector(".diagnosa-operasi-container").append(newElm);

            divElm.querySelector(".diagnosa-remove-btn").addEventListener("click", (event) => {
                closest(event.target, ".diagnosa-operasi-group").remove();
            });
        });

        divElm.querySelector(".diagnosa-remove-btn").addEventListener("click", (event) => {
            closest(event.target, ".diagnosa-operasi-group").remove();
        });

        divElm.querySelector(".tindakan-add-btn").addEventListener("click", () => {
            let cnt = 0;
            divElm.querySelectorAll("[name*=tindakan]").forEach(item => {
                const check = item.id.substr(9);
                if (check > cnt) cnt = check;
            });
            cnt++;

            const innerHTML = divElm.querySelector(".tindakan-operasi-group").innerHTML;
            const newElm = $(`<div class="tindakan-operasi-group">${innerHTML}</div>`);
            const button = draw({class: ".tindakan-remove-btn", icon: "minus"});
            newElm.querySelector(".tindakan-add-btn").replaceWith(button);

            const tindakanFld = newElm.querySelector("[name*=tindakan]");
            tindakanFld.setAttribute("id", "tindakan_" + cnt);
            tindakanFld.setAttribute("name", `tindakan[${cnt}]`);
            tindakanFld.value = "";

            const icd9cmFld = newElm.querySelector("[name*=icd9cm]");
            icd9cmFld.setAttribute("id", "icd9cm_" + cnt);
            icd9cmFld.setAttribute("name", `kd_icd9cm[${cnt}]`);
            icd9cmFld.value = "";

            divElm.querySelector(".tindakan-operasi-container").append(newElm);

            divElm.querySelector(".tindakan-remove-btn").addEventListener("click", (event) => {
                closest(event.target, ".tindakan-operasi-group").remove();
            });
        });

        divElm.querySelector(".tindakan-remove-btn").addEventListener("click", (event) => {
            closest(event.target, ".tindakan-operasi-group").remove();
        });

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

            const namaAlatOperasiFld = newElm.querySelector("[name*=alat_operasi_nama]");
            namaAlatOperasiFld.setAttribute("id", "alat_operasi_nama_" + cnt);
            namaAlatOperasiFld.setAttribute("name", `alat_operasi_nama[${cnt}]`);
            namaAlatOperasiFld.value = "";

            const kuantitasAlatOperasiFld = newElm.querySelector("[name*=alat_operasi_qty]");
            kuantitasAlatOperasiFld.setAttribute("id", "alat_operasi_qty_" + cnt);
            kuantitasAlatOperasiFld.setAttribute("name", `alat_operasi_qty[${cnt}]`);
            kuantitasAlatOperasiFld.value = "";

            const satuanAlatOperasiFld = newElm.querySelector("[name*=alat_operasi_satuan]");
            satuanAlatOperasiFld.setAttribute("id", "alat_operasi_satuan_" + cnt);
            satuanAlatOperasiFld.setAttribute("name", `alat_operasi_satuan[${cnt}]`);
            satuanAlatOperasiFld.value = "";

            divElm.querySelector(".alat-operasi-container").append(newElm);

            divElm.querySelector(".alat-remove-btn").addEventListener("click", (event) => {
                closest(event.target, ".alat-operasi-group").remove();
            });
        });

        divElm.querySelector(".alat-remove-btn").addEventListener("click", (event) => {
            closest(event.target, ".alat-operasi-group").remove();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(editJadwalOperasiWgt, idDokterWgt, tanggalOperasiWgt, cariDiagnosaWgt, cariTindakanWgt, cariAlatWgt, tindakanWgt);
        tlm.app.registerWidget(this.constructor.widgetName, editJadwalOperasiWgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<form class=".editJadwalOperasiFrm">
    <!-- FORM ELEMENT FROM WIDGET ARE HERE -->

    <?= FH::divOpen('Diagnosa', 'diagnosa') ?>
    <div class="diagnosa-operasi-container">
        <?php if (is_array($daftarDiagnosaOperasi)): ?>
            <?php foreach ($daftarDiagnosaOperasi as $diagnosa => $row): ?>
                <?php $nod = $diagnosa + 1 ?>
                <div class="diagnosa-operasi-group">
                    <input class="cariDiagnosaFld" id="diagnosa_<?= $nod ?>" name="diagnosa[<?= $nod ?>]" value="<?= $row->diagnosaTindakan ?>">
                    <input type="hidden" id="icd10_<?= $nod ?>" name="kd_icd10[<?= $nod ?>]" value="<?= $row->kodeDiagnosaTindakan ?>">
                    <?php if ($diagnosa == 0): ?>
                        <button class="diagnosa-add-btn">Tambah Diagnosa</button>
                    <?php else: ?>
                        <button class="diagnosa-remove-btn">Hapus Diagnosa</button>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="diagnosa-operasi-group">
                <input class="cariDiagnosaFld" name="diagnosa[1]" id="diagnosa_1"/>
                <input type="hidden" id="icd10_1" name="kd_icd10[1]"/>
                <button class="diagnosa-add-btn">tambah Diagnosa baru</button>
            </div>
        <?php endif ?>
    </div>
    <?= FH::divClose('diagnosa') ?>

    <?= FH::divOpen('Tindakan', 'tindakan') ?>
    <div class="tindakan-operasi-container">
        <?php if (is_array($daftarTindakanOperasi)): ?>
            <?php foreach ($daftarTindakanOperasi as $tindakan => $row): ?>
                <?php $not = $tindakan + 1 ?>
                <div class="tindakan-operasi-group">
                    <input class="cariTindakanFld" name="tindakan[<?= $not ?>]" id="tindakan_<?= $not ?>" value="<?= $row->diagnosaTindakan ?>"/>
                    <input type="hidden" id="icd9cm_<?= $not ?>" name="kd_icd9cm[<?= $not ?>]" value="<?= $row->kodeDiagnosaTindakan ?>">
                    <?php if ($tindakan == 0): ?>
                        <button class="tindakan-add-btn">Tambah tindakan</button>
                    <?php else: ?>
                        <button class="tindakan-remove-btn">Hapus tindakan</button>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="tindakan-operasi-group">
                <input class="cariTindakanFld" name="tindakan[1]" id="tindakan_1"/>
                <input type="hidden" id="icd9cm_1" name="kd_icd9cm[1]"/>
                <button class="tindakan-add-btn">tindakan baru</button>
            </div>
        <?php endif ?>
    </div>
    <?= FH::divClose('tindakan') ?>

    <?= FH::divOpen('Alat Operasi', 'alat_operasi') ?>
    <div class="alat-operasi-container">
        <?php if (is_array($daftarAlatOperasi)): ?>
            <?php foreach ($daftarAlatOperasi as $idx => $row): ?>
                <?php $no = $idx + 1 ?>
                <div class="alat-operasi-group">
                    <input id="alat_operasi_nama_<?= $no ?>" name="alat_operasi_nama[<?= $no ?>]" class="cariAlatFld" value="<?= $row->nama ?>" placeholder="Nama Alat"/>
                    <input id="alat_operasi_qty_<?= $no ?>" name="alat_operasi_qty[<?= $no ?>]" value="<?= $row->jumlah ?>" placeholder="Jumlah"/>
                    <input id="alat_operasi_satuan_<?= $no ?>" name="alat_operasi_satuan[<?= $no ?>]" value="<?= $row->satuan ?>" placeholder="Satuan"/>
                    <?php if ($idx == 0): ?>
                        <button class="alat-add-btn">Tambah Alat Operasi</button>
                    <?php else: ?>
                        <button class="alat-remove-btn">Hapus Alat Operasi</button>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="alat-operasi-group">
                <input id="alat_operasi_nama_1" name="alat_operasi_nama[1]" class="cariAlatFld" placeholder="Nama Alat"/>
                <input id="alat_operasi_qty_1" name="alat_operasi_qty[1]" placeholder="Jumlah"/>
                <input id="alat_operasi_satuan_1" name="alat_operasi_satuan[1]" placeholder="Satuan"/>
                <button class="alat-add-btn" type="button">tambah Pasien baru</button>
            </div>
        <?php endif ?>
    </div>
    <?= FH::divClose('alat_operasi') ?>

    <div class="form-actions">
        <a class="lnk-delete" data-toggle="modal" href="#delete-confirm" data-url="<?= $deleteUrl ."/".$data->id ?>">Delete</a>
        <button name="submit" value="save">Simpan</button>
        <a href="<?= $tableBookingWidgetId ."/".$data->ruangOperasi ?>">Batal</a>
        <input type="hidden" name="id" value="<?= $data->id ?>"/>
    </div>
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
