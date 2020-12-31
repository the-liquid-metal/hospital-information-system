<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\SignaUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/rm_op.php the original file
 */
final class RekamMedisOp
{
    private string $output;

    public function __construct(string $registerId, string $dataUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Signa.RekamMedisOp {
    export interface FormFields {
        tanggalAwal:    string;
        tanggalAkhir:   string;
        kodeRekamMedis: string;
        noPendaftaran:  string;
        namaPasien:     string;
        kelamin:        string;
        statusPasien:   string;
    }

    export interface TableFields {
        noPendaftaran:      string;
        tanggalDaftar:      string;
        kodeRekamMedis:     string;
        namaPasien:         string;
        namaInstalasi:      string;
        namaPoli:           string;
        namaKamar:          string;
        namaCaraBayar:      string;
        noTelefon:          string;
        kelamin:            string;
        kodeRuangRawat:     string;
        kodeJenisCaraBayar: string;
        jenisCaraBayar:     string;
        alamat:             string;
        tanggalLahir:       string;
        kodeInstalasi:      string;
        kodePoli:           string;
        kodeBayar:          string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

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
            class: ".saringFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal") ?>,
                        input: {class: ".tanggalAwalFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                        input: {class: ".tanggalAkhirFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                        input: {class: ".noPendaftaranFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaPasienFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        select: {
                            class: ".kelaminFld",
                            option_1: {value: "",  label: <?= $h("Semua") ?>},
                            option_2: {value: "1", label: <?= $h("Laki-laki") ?>},
                            option_3: {value: "0", label: <?= $h("Perempuan") ?>},
                        }
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Status Pasien") ?>,
                        select: {
                            class: ".statusPasienFld",
                            option_1: {value: "",        label: <?= $h("Semua") ?>},
                            option_2: {value: "dirawat", label: <?= $h("Dirawat") ?>},
                            option_3: {value: "keluar",  label: <?= $h("Keluar") ?>},
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
                        td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("No. Daftar") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Nama") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Instalasi") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Poli/SMF") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Asal Rujukan") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Cara Bayar") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Pilih") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {toUserDate: userDate, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const kelaminFld = divElm.querySelector(".kelaminFld");
        /** @type {HTMLSelectElement} */ const statusPasienFld = divElm.querySelector(".statusPasienFld");

        const tanggalAwalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAwalFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalAkhirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAkhirFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const kodeRekamMedisWgt = new spl.InputWidget({
            element: divElm.querySelector(".kodeRekamMedisFld"),
            errorRules: [{required: true}]
        });

        const noPendaftaranWgt = new spl.InputWidget({
            element: divElm.querySelector(".noPendaftaranFld"),
            errorRules: [{required: true}]
        });

        const namaPasienWgt = new spl.InputWidget({
            element: divElm.querySelector(".namaPasienFld"),
            errorRules: [{required: true}]
        });

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.Signa.RekamMedisOp.FormFields} data */
            loadData(data) {
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranWgt.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.namaPasien ?? "";
                kelaminFld.value = data.kelamin ?? "";
                statusPasienFld.value = data.statusPasien ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        tanggalAwal: tanggalAwalWgt.value,
                        tanggalAkhir: tanggalAkhirWgt.value,
                        kodeRekamMedis: kodeRekamMedisWgt.value,
                        noPendaftaran: noPendaftaranWgt.value,
                        namaPasien: namaPasienWgt.value,
                        kelamin: kelaminFld.value,
                        statusPasien: statusPasienFld.value,
                    }
                });
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1:  {formatter: tlm.rowNumGenerator},
                2:  {field: "noPendaftaran"},
                3:  {field: "tanggalDaftar", formatter: tlm.dateFormatter},
                4:  {field: "kodeRekamMedis"},
                5:  {field: "namaPasien"},
                6:  {field: "namaInstalasi"},
                7:  {field: "namaPoli"},
                8:  {field: "namaKamar"},
                9:  {field: "___"},
                10: {field: "caraBayar"},
                11: {formatter(unused, item) {
                    const {noPendaftaran, tanggalPendaftaran, kodeRekamMedis, namaPasien, namaInstalasi, namaPoli} = item;
                    const {caraBayar, namaKamar, noTelefon, kelamin, kodeRuangRawat, kodeJenisCaraBayar} = item;
                    const {jenisCaraBayar, alamat, tanggalLahir, kodeInstalasi, kodePoli, kodeBayar} = item;
                    const chooseStr = str._<?= $h("Pilih") ?>;
                    return draw({
                        class: "pilih_rm", "data-NO_PENDAFTARAN": noPendaftaran, "data-TGL_DAFTAR": tanggalPendaftaran, "data-NORM": kodeRekamMedis,
                        "data-NAMA": namaPasien, "data-NM_INST": namaInstalasi, "data-NM_POLI": namaPoli, "data-CARABAYAR": caraBayar, "data-NM_KAMAR": namaKamar,
                        "data-NO_TELPON": noTelefon, "data-KELAMIN": kelamin, "data-KD_RRAWAT": kodeRuangRawat, "data-KD_JNS_CARABAYAR": kodeJenisCaraBayar,
                        "data-JNS_CARABAYAR": jenisCaraBayar, "data-ALAMAT": alamat, "data-TGL_LAHIR": tanggalLahir, "data-KD_INST": kodeInstalasi,
                        "data-KD_POLI": kodePoli, "data-KD_BAYAR": kodeBayar, text: chooseStr
                    });
                }}
            }
        });

        divElm.querySelector(".pilih_rm").addEventListener("click", (event) => {
            const pilihRmElm = event.target;

            divElm.querySelector(".no_rm").value = pilihRmElm.dataset.NORM;
            divElm.querySelector(".no_daftar").value = pilihRmElm.dataset.NO_PENDAFTARAN;
            divElm.querySelector(".nama").value = pilihRmElm.dataset.NAMA;
            divElm.querySelector("#KD_JENIS_CARABAYAR").value = pilihRmElm.dataset.KD_JNS_CARABAYAR;
            divElm.querySelector(".JNS_CARABAYAR").value = pilihRmElm.dataset.JNS_CARABAYAR;
            divElm.querySelector("#KD_RRAWAT").value = pilihRmElm.dataset.KD_RRAWAT;
            divElm.querySelector(".alamat").value = pilihRmElm.dataset.ALAMAT;
            divElm.querySelector(".carabayar").value = pilihRmElm.dataset.CARABAYAR;
            divElm.querySelector(".KD_BAYAR").value = pilihRmElm.dataset.KD_BAYAR;
            divElm.querySelector(".KD_INST").value = pilihRmElm.dataset.KD_INST;
            divElm.querySelector(".KD_POLI").value = pilihRmElm.dataset.KD_POLI;
            divElm.querySelector(".TGL_DAFTAR").value = pilihRmElm.dataset.TGL_DAFTAR;

            const jk4Elm = divElm.querySelector(".jk4");
            jk4Elm.checked = false;
            jk4Elm.disabled = false;

            if (pilihRmElm.dataset.KELAMIN == "1") {
                divElm.querySelector(".jkl").checked = true;
                divElm.querySelector(".jkp").disabled = true;
            } else {
                divElm.querySelector(".jkp").checked = true;
                divElm.querySelector(".jkl").disabled = true;
            }

            divElm.querySelector(".tgllahir").value = userDate(pilihRmElm.dataset.TGL_LAHIR);
            divElm.querySelector(".notelp").value = pilihRmElm.dataset.NO_TELPON.trim() || "-";
            divElm.querySelector(".nm_inst").value = pilihRmElm.dataset.NM_INST;
            divElm.querySelector(".nm_kamar").value = pilihRmElm.dataset.NM_KAMAR || "";
            divElm.querySelector(".nm_poli").value = pilihRmElm.dataset.NM_POLI;
            divElm.querySelector(".detailuser").readonly = true;
            divElm.querySelector(".modal-open").classList.remove("modal-open");
            divElm.querySelector(".bootbox").remove();
            divElm.querySelector(".modal-backdrop").remove();
            divElm.querySelector(".modal").modal("hide");
            divElm.querySelector("#kelasrm").dispatchEvent(new Event("focus"));
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(tanggalAwalWgt, tanggalAkhirWgt, kodeRekamMedisWgt, noPendaftaranWgt, namaPasienWgt, saringWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tanggalAwalWgt);
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
