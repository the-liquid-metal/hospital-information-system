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
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/rm.php the original file
 */
final class CariRekamMedisForm
{
    private string $output;

    public function __construct(string $registerId, string $cariRekamMedisUrl)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.PasienUi.CariRekamMedisForm {
    export interface FormFields {
        tanggalAwal:    string;
        tanggalAkhir:   string;
        noPendaftaran:  string;
        kodeRekamMedis: string;
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
        caraBayar:          string;
        alamat:             string;
        tanggalLahir:       string;
        kodeInstalasi:      string;
        kodePoli:           string;
        kodeBayar:          string;
    }

    export interface TargetFields {
        alamatFld?:             HTMLInputElement;
        alamatStc?:             HTMLDivElement;
        caraBayarFld?:          HTMLInputElement;
        caraBayarStc?:          HTMLDivElement;
        jenisCaraBayarFld?:     HTMLInputElement;
        jenisCaraBayarStc?:     HTMLDivElement;
        kelaminLakiFld?:        HTMLInputElement;
        kelaminPerempuanFld?:   HTMLInputElement;
        kelaminStc?:            HTMLDivElement;
        kodeBayarFld?:          HTMLInputElement;
        kodeBayarStc?:          HTMLDivElement;
        kodeInstalasiFld?:      HTMLInputElement;
        kodeInstalasiStc?:      HTMLDivElement;
        kodeJenisCaraBayarFld?: HTMLInputElement;
        kodeJenisCaraBayarStc?: HTMLDivElement;
        kodePoliFld?:           HTMLInputElement;
        kodePoliStc?:           HTMLDivElement;
        kodeRekamMedisFld?:     HTMLInputElement;
        kodeRekamMedisStc?:     HTMLDivElement;
        kodeRuangRawatFld?:     HTMLInputElement;
        kodeRuangRawatStc?:     HTMLDivElement;
        namaCaraBayarFld?:      HTMLInputElement;
        namaCaraBayarStc?:      HTMLDivElement;
        namaInstalasiFld?:      HTMLInputElement;
        namaInstalasiStc?:      HTMLDivElement;
        namaKamarFld?:          HTMLInputElement;
        namaKamarStc?:          HTMLDivElement;
        namaPasienFld?:         HTMLInputElement;
        namaPasienStc?:         HTMLDivElement;
        namaPoliFld?:           HTMLInputElement;
        namaPoliStc?:           HTMLDivElement;
        noPendaftaranFld?:      HTMLInputElement;
        noPendaftaranStc?:      HTMLDivElement;
        noTelefonFld?:          HTMLInputElement;
        noTelefonStc?:          HTMLDivElement;
        tanggalDaftarFld?:      HTMLInputElement;
        tanggalDaftarStc?:      HTMLDivElement;
        tanggalLahirFld?:       HTMLInputElement;
        tanggalLahirStc?:       HTMLDivElement;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    _structure = {
        modal: {
            class: ".cariRekamMedisMdl",
            title: tlm.stringRegistry._<?= $h("Cari Pendaftaran") ?>,
            form: {
                class: ".cariRekamMedisFrm",
                row_1: {
                    column_1: {
                        formGroup_1: {
                            label: tlm.stringRegistry._<?= $h("Tanggal Awal") ?>,
                            input: {class: ".tanggalAwalFld"}
                        },
                        formGroup_2: {
                            label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                            input: {class: ".tanggalAkhirFld"}
                        },
                        formGroup_3: {
                            label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                            input: {class: ".noPendaftaranFld"}
                        },
                    },
                    column_2: {
                        formGroup_1: {
                            label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                            input: {class: ".kodeRekamMedisFld"}
                        },
                        formGroup_2: {
                            label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                            input: {class: ".namaPasienFld"}
                        },
                        formGroup_3: {
                            label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                            select: {
                                class: ".kelaminFld",
                                option_1: {value: "",  label: <?= $h("Semua") ?>},
                                option_2: {value: "1", label: <?= $h("Laki-laki") ?>},
                                option_3: {value: "0", label: <?= $h("Perempuan") ?>},
                            }
                        },
                        formGroup_4: {
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
                    widthColumn: {
                        class: "text-center",
                        SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                    }
                }
            },
            row_1: {
                widthColumn: {
                    paragraph: {text: "&nbsp;"}
                }
            },
            row_2: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr: {
                            td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {text: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>},
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
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const {toUserDate: userDate, stringRegistry: str} = tlm;

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

        const cariRekamMedisWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".cariRekamMedisFrm"),
            /** @param {his.FatmaPharmacy.views.PasienUi.CariRekamMedisForm.FormFields} data */
            loadData(data) {
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                noPendaftaranWgt.value = data.noPendaftaran ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                namaPasienWgt.value = data.namaPasien ?? "";
                kelaminFld.value = data.kelamin ?? "";
                statusPasienFld.value = data.statusPasien ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        tanggalAwal: tanggalAwalWgt.value,
                        tanggalAkhir: tanggalAkhirWgt.value,
                        noPendaftaran: noPendaftaranWgt.value,
                        kodeRekamMedis: kodeRekamMedisWgt.value,
                        namaPasien: namaPasienWgt.value,
                        kelamin: kelaminFld.value,
                        statusPasien: statusPasienFld.value,
                    }
                });
            }
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $cariRekamMedisUrl ?>",
            uniqueId: "noPendaftaran",
            columns: {
                1:  {formatter: tlm.rowNumGenerator},
                2:  {field: "noPendaftaran"},
                3:  {field: "tanggalDaftar"},
                4:  {field: "kodeRekamMedis"},
                5:  {field: "namaPasien"},
                6:  {field: "namaInstalasi"},
                7:  {field: "namaPoli"},
                8:  {field: "namaKamar"},
                9:  {field: "___"},
                10: {field: "caraBayar"},
                11: {formatter(unused, {noPendaftaran}) {
                    return draw({class: ".pilihBtn", value: noPendaftaran, text: str._<?= $h("Pilih") ?>});
                }}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const pilihBtn = /** @type {HTMLButtonElement} */ event.target;
            if (!pilihBtn.matches(".pilihBtn")) return;

            /** @type {his.FatmaPharmacy.views.PasienUi.CariRekamMedisForm.TableFields} */
            const data = itemWgt.getRowByUniqueId(pilihBtn.value);
            /** @type {his.FatmaPharmacy.views.PasienUi.CariRekamMedisForm.TargetFields|{}} */
            const target = tlm.targetElements || {};

            if (target.alamatFld) target.alamatFld.value = data.alamat ?? "";
            if (target.alamatStc) target.alamatStc.innerHTML = data.alamat ?? "";
            if (target.caraBayarFld) target.caraBayarFld.value = data.caraBayar ?? "";
            if (target.caraBayarStc) target.caraBayarStc.innerHTML = data.caraBayar ?? "";
            if (target.jenisCaraBayarFld) target.jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
            if (target.jenisCaraBayarStc) target.jenisCaraBayarStc.innerHTML = data.jenisCaraBayar ?? "";
            if (target.kodeBayarFld) target.kodeBayarFld.value = data.kodeBayar ?? "";
            if (target.kodeBayarStc) target.kodeBayarStc.innerHTML = data.kodeBayar ?? "";
            if (target.kodeInstalasiFld) target.kodeInstalasiFld.value = data.kodeInstalasi ?? "";
            if (target.kodeInstalasiStc) target.kodeInstalasiStc.innerHTML = data.kodeInstalasi ?? "";
            if (target.kodeJenisCaraBayarFld) target.kodeJenisCaraBayarFld.value = data.kodeJenisCaraBayar ?? "";
            if (target.kodeJenisCaraBayarStc) target.kodeJenisCaraBayarStc.innerHTML = data.kodeJenisCaraBayar ?? "";
            if (target.kodePoliFld) target.kodePoliFld.value = data.kodePoli ?? "";
            if (target.kodePoliStc) target.kodePoliStc.innerHTML = data.kodePoli ?? "";
            if (target.kodeRekamMedisFld) target.kodeRekamMedisFld.value = data.kodeRekamMedis ?? "";
            if (target.kodeRekamMedisStc) target.kodeRekamMedisStc.innerHTML = data.kodeRekamMedis ?? "";
            if (target.kodeRuangRawatFld) target.kodeRuangRawatFld.value = data.kodeRuangRawat ?? "";
            if (target.kodeRuangRawatStc) target.kodeRuangRawatStc.innerHTML = data.kodeRuangRawat ?? "";
            if (target.namaInstalasiFld) target.namaInstalasiFld.value = data.namaInstalasi ?? "";
            if (target.namaInstalasiStc) target.namaInstalasiStc.innerHTML = data.namaInstalasi ?? "";
            if (target.namaKamarFld) target.namaKamarFld.value = data.namaKamar ?? "";
            if (target.namaKamarStc) target.namaKamarStc.innerHTML = data.namaKamar ?? "";
            if (target.namaPasienFld) target.namaPasienFld.value = data.namaPasien ?? "";
            if (target.namaPasienStc) target.namaPasienStc.innerHTML = data.namaPasien ?? "";
            if (target.namaPoliFld) target.namaPoliFld.value = data.namaPoli ?? "";
            if (target.namaPoliStc) target.namaPoliStc.innerHTML = data.namaPoli ?? "";
            if (target.noPendaftaranFld) target.noPendaftaranFld.value = data.noPendaftaran ?? "";
            if (target.noPendaftaranStc) target.noPendaftaranStc.innerHTML = data.noPendaftaran ?? "";
            if (target.noTelefonFld) target.noTelefonFld.value = data.noTelefon ?? "";
            if (target.noTelefonStc) target.noTelefonStc.innerHTML = data.noTelefon ?? "";
            if (target.tanggalDaftarFld) target.tanggalDaftarFld.value = data.tanggalDaftar ?? "";
            if (target.tanggalDaftarStc) target.tanggalDaftarStc.innerHTML = data.tanggalDaftar ?? "";

            if (target.tanggalLahirFld) target.tanggalLahirFld.value = data.tanggalLahir ? userDate(data.tanggalLahir) ?? "";
            if (target.tanggalLahirStc) target.tanggalLahirStc.innerHTML = data.tanggalLahir ? userDate(data.tanggalLahir) ?? "";

            if (data.kelamin == "1" && target.kelaminLakiFld) {
                target.kelaminLakiFld.checked = true;
            } else if (data.kelamin == "0" && target.kelaminPerempuanFld) {
                target.kelaminPerempuanFld.checked = true;
            } else {
                if (target.kelaminLakiFld) target.kelaminLakiFld.checked = false;
                if (target.kelaminPerempuanFld) target.kelaminPerempuanFld.checked = false;
            }

            if (target.kelaminStc) {
                if (data.kelamin == "1") target.kelaminStc.innerHTML = str._<?= $h("Laki-laki") ?>;
                if (data.kelamin == "0") target.kelaminStc.innerHTML = str._<?= $h("perempuan") ?>;
                if (data.kelamin == null) target.kelaminStc.innerHTML = "";
            }

            tlm.targetElements = null;
            itemWgt.dispatchEvent(new Event("hide"));
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(cariRekamMedisWgt, kodeRekamMedisWgt, namaPasienWgt, tanggalAwalWgt, tanggalAkhirWgt, noPendaftaranWgt);
        tlm.app.registerWidget(this.constructor.widgetName, cariRekamMedisWgt);
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
