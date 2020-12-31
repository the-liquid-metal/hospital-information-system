<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresep/edit.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class FormEdit
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $actionUrl,
        string $dokterUrl,
        string $rekamMedisAcplUrl,
        string $registrasiAjaxUrl,
        string $noRekamMedis3Url,
        string $namaAcplUrl,
        string $nama3Url,
        string $obatAcplUrl,
        string $hargaUrl,
        string $signaAcplUrl,
        string $stokDataUrl,
        string $viewAntrianWidgetId,
        string $jenisResepSelect,
        string $caraBayarSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $idRacik = "";
        $z = 1;
        $r = 1;
        $j = 0;
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepUi.Edit {
    export interface FormFields {
        id:                      string|"x" |"out"; // missing in form
        kodePenjualanSebelumnya: string|"in"|"out";
        kodeRuangRawat:          string|"in"|"out";
        noAntrian:               string|"in"|"x";
        editResep:               string|"in"|"out"; // sama dg kodePenjualanSebelumnya
        noResep:                 string|"in"|"out";
        kodeRekamMedis:          string|"in"|"out";
        noPendaftaran:           string|"in"|"out";
        tanggalPendaftaran:      string|"in"|"out";
        namaPasien:              string|"in"|"out";
        kelamin:                 string|"in"|"out";
        tanggalLahir:            string|"in"|"out";
        alamat:                  string|"in"|"out";
        noTelefon:               string|"in"|"out";
        tanggalAwalResep:        string|"in"|"out";
        tanggalAkhirResep:       string|"in"|"out";
        kodeJenisResep:          string|"in"|"out";
        dokter:                  string|"in"|"out";
        pembayaran:              string|"in"|"out";
        jenisCaraBayar:          string|"in"|"out";
        kodeBayar:               string|"in"|"out";
        kodeJenisCaraBayar:      string|"in"|"out";
        namaInstalasi:           string|"in"|"out";
        kodeInstalasi:           string|"in"|"out";
        namaPoli:                string|"in"|"out";
        kodePoli:                string|"in"|"x";
        verifikasi:              string|"x" |"out";
        verified:                string|"x" |"out";
        jasaPelayanan:           string|"in"|"x";
        grandTotal:              string|"in"|"x";
        iter2:                   string|"in"|"x";
        iter1:                   string|"in"|"x";
        namaRuangRawat:          string|"x" |"out"; // missing in form
        noUrut:                  string|"x" |"out"; // missing in form

        daftarObat:              Array<ObatFields>;
        daftarRacik:             Array<RacikFields>;
    }

    export interface ObatFields {
        namaObat:     string|"in"|"x";
        kodeObatAwal: string|"in"|"out"; // sama dg kodeObat
        kodeObat:     string|"in"|"out";
        kuantitas:    string|"in"|"out";
        idRacik:      string|"in"|"out";
        namaSigna1:   string|"in"|"out";
        namaSigna2:   string|"in"|"out";
        namaSigna3:   string|"in"|"out";
        namaKemasan:  string|"in"|"x";
        hargaJual:    string|"in"|"out";
        diskonObat:   string|"in"|"out";
    }

    export interface RacikFields {
        diskonRacik:         string|"?"|"out"|"diskonracik";
        kodeSigna:           string|"?"|"out"|"kode_signa";
        kodeSignaRacik:      string|"?"|"out"|"kode_signa_racik";
        kodePembungkus:      string|"?"|"out"|"kode_pembungkus";
        kuantitasPembungkus: string|"?"|"out"|"qtypembungkus";
        hargaPembungkus:     string|"?"|"out"|"hargapembungkus";
        noRacik:             string|"?"|"out"|"numero";
        namaRacik:           string|"?"|"out"|"nm_racikan";
    }

    export interface ObatAcplFields {
        namaBarang:     string;
        sinonim:        string; // missing in database/controller (generik ?)
        kode:           string;
        satuanKecil:    string;
        formulariumNas: string;
        formulariumRs:  string;
        namaPabrik:     string;
        stokFisik:      string;
    }

    export interface HargaFields {
        stok: string;
        harga: string;
    }

    export interface SignaFields {
        id:   string;
        nama: string;
    }

    export interface NamaFields {
        kodeRekamMedis: string;
        nama:           string;
        noPendaftaran:  string;
    }

    export interface RegistrasiFields {
        kodeRekamMedis:     string;
        kelamin:            string;
        alamat:             string;
        caraBayar:          string;
        tanggalLahir:       string;
        noTelefon:          string;
        namaInstalasi:      string;
        namaKamar:          string;
        namaPoli:           string;
        jenisCaraBayar:     string;
        kodeJenisCaraBayar: string;
        kodeRuangRawat:     string;
        kodeBayar:          string;
        kodeInstalasi:      string;
        kodePoli:           string;
    }

    export interface RekamMedisFields {
        kodeRekamMedis:     string;
        nama:               string;
        noPendaftaran:      string;
    }

    export interface StokTableFields {
        namaDepo:        string;
        jumlahStokFisik: string;
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
            class: ".editEresepFrm",
            hidden_1: {class: ".kodePenjualanSebelumnyaFld", name: "kodePenjualanSebelumnya"},
            hidden_2: {class: ".kodeRuangRawatFld", name: "kodeRuangRawat"},
            hidden_3: {class: ".noAntrianFld", name: "noAntrian"},
            hidden_4: {class: ".editResepFld", name: "editResep"},
            row_1: {
                box_1: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".verifiedFld", name: "verified"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                        input: {class: ".noResepFld", name: "noResep"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                        input: {class: ".noPendaftaranFld", name: "noPendaftaran"},
                        hidden: {class: ".tanggalPendaftaranFld", name: "tanggalPendaftaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaPasienFld", name: "namaPasien"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        input: {class: ".kelaminFld", name: "kelamin"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Lahir") ?>,
                        input: {class: ".tanggalLahirFld", name: "tanggalLahir"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Alamat") ?>,
                        input: {class: ".alamatFld", name: "alamat"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("No. Telefon") ?>,
                        input: {class: ".noTelefonFld", name: "noTelefon"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal Resep") ?>,
                        input: {class: ".tanggalAwalResepFld", name: "tanggalAwalResep"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir Resep") ?>,
                        input: {class: ".tanggalAkhirResepFld", name: "tanggalAkhirResep"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Jenis Resep") ?>,
                        select: {class: ".kodeJenisResepFld", name: "kodeJenisResep"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Dokter") ?>,
                        input: {class: ".dokterFld", name: "dokter"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Pembayaran") ?>,
                        select: {class: ".pembayaranFld", name: "pembayaran"},
                        hidden_1: {class: ".jenisCaraBayarFld", name: "jenisCaraBayar"},
                        hidden_2: {class: ".kodeBayarFld", name: "kodeBayar"},
                        hidden_3: {class: ".kodeJenisCaraBayarFld", name: "kodeJenisCaraBayar"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Nama Instansi") ?>,
                        input: {class: ".namaInstalasiFld", name: "namaInstalasi"},
                        hidden: {class: ".kodeInstalasiFld", name: "kodeInstalasi"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Nama Poli") ?>,
                        input: {class: ".namaPoliFld", name: "namaPoli"},
                        hidden: {class: ".kodePoliFld", name: "kodePoli"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        checkbox: {class: ".verifikasiFld", name: "verifikasi"},
                        staticText: {class: ".verifikasiStc"}
                    }
                },
                box_2: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".totalDiskonStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Pembungkus") ?>,
                        staticText: {class: ".totalPembungkusStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Jasa Pelayanan") ?>,
                        staticText: {class: ".jasaPelayananStc"},
                        hidden: {class: ".jasaPelayananFld", name: "jasaPelayanan"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Total") ?>,
                        staticText: {class: ".grandTotalStc"},
                        hidden: {class: ".grandTotalFld", name: "grandTotal"}
                    }
                }
            },
            row_2: {
                widthColumn: {text: tlm.stringRegistry._<?= $h("Masukkan Obat: Hitam untuk Formularium Nasional, Merah untuk Formularium RS, Hijau untuk LAINNYA") ?>}
            },
            row_3: {
                widthTable: {
                    class: ".obatTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Nama Obat") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Racikan") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Signa") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Action") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                input: {class: ".obatBiasaFld", name: "namaObat[]"},
                                hidden_1: {class: ".kodeObatAwalFld", name: "kodeObatAwal[]"},
                                hidden_2: {class: ".kodeObatFld", name: "kodeObat[]"},
                                rButton: {class: ".cekStokBtn"}
                            },
                            td_2: {
                                input: {class: ".kuantitasFld", name: "kuantitas[]"}
                            },
                            td_3: {
                                input: {class: ".idRacikFld", name: "idRacik[]"}
                            },
                            td_4: {
                                input_1: {class: ".namaSigna1Fld", name: "namaSigna1[]"},
                                input_2: {class: ".namaSigna2Fld", name: "namaSigna2[]"},
                                input_3: {class: ".namaSigna3Fld", name: "namaSigna3[]"}
                            },
                            td_5: {
                                input: {class: ".namaKemasanFld", name: "namaKemasan[]"}
                            },
                            td_6: {
                                input: {class: ".hargaJualFld .listhargaobat", name: "hargaJual[]"}
                            },
                            td_7: {
                                input: {class: ".diskonObatFld", name: "diskonObat[]"}
                            },
                            td_8: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: "delete"}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {colspan: 6},
                            td_7: {
                                button: {class: ".addRowBtn", type: "success", size: "xs", label: "add"}
                            }
                        }
                    }
                }
            },
            row_4: {
                widthTable: {id: "tabel_rac"}
            },
            row_5: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        },
        modal: {
            title: tlm.stringRegistry._<?= $h("Stok Tersedia") ?>,
            row_1: {
                widthColumn: {class: ".headerElm"}
            },
            row_2: {
                widthTable: {
                    class: ".stokTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Depo") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                        }
                    }
                }
            },
            row_3: {
                widthColumn: {class: ".footerElm"}
            },
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {toSystemNumber: sysNum, toCurrency: currency, toUserDate: userDate, stringRegistry: str} = tlm;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodePenjualanSebelumnyaFld = divElm.querySelector(".kodePenjualanSebelumnyaFld");
        /** @type {HTMLInputElement} */  const kodeRuangRawatFld = divElm.querySelector(".kodeRuangRawatFld");
        /** @type {HTMLInputElement} */  const noAntrianFld = divElm.querySelector(".noAntrianFld");
        /** @type {HTMLInputElement} */  const editResepFld = divElm.querySelector(".editResepFld");
        /** @type {HTMLInputElement} */  const noResepFld = divElm.querySelector(".noResepFld");
        /** @type {HTMLInputElement} */  const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLInputElement} */  const tanggalPendaftaranFld = divElm.querySelector(".tanggalPendaftaranFld");
        /** @type {HTMLInputElement} */  const kelaminFld = divElm.querySelector(".kelaminFld");
        /** @type {HTMLInputElement} */  const tanggalLahirFld = divElm.querySelector(".tanggalLahirFld");
        /** @type {HTMLInputElement} */  const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */  const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLInputElement} */  const tanggalAwalResepFld = divElm.querySelector(".tanggalAwalResepFld");
        /** @type {HTMLInputElement} */  const tanggalAkhirResepFld = divElm.querySelector(".tanggalAkhirResepFld");
        /** @type {HTMLSelectElement} */ const kodeJenisResepFld = divElm.querySelector(".kodeJenisResepFld");
        /** @type {HTMLInputElement} */  const dokterFld = divElm.querySelector(".dokterFld");
        /** @type {HTMLSelectElement} */ const pembayaranFld = divElm.querySelector(".pembayaranFld");
        /** @type {HTMLInputElement} */  const jenisCaraBayarFld = divElm.querySelector(".jenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const kodeBayarFld = divElm.querySelector(".kodeBayarFld");
        /** @type {HTMLInputElement} */  const kodeJenisCaraBayarFld = divElm.querySelector(".kodeJenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const namaInstalasiFld = divElm.querySelector(".namaInstalasiFld");
        /** @type {HTMLInputElement} */  const kodeInstalasiFld = divElm.querySelector(".kodeInstalasiFld");
        /** @type {HTMLInputElement} */  const namaPoliFld = divElm.querySelector(".namaPoliFld");
        /** @type {HTMLInputElement} */  const kodePoliFld = divElm.querySelector(".kodePoliFld");
        /** @type {HTMLInputElement} */  const verifikasiFld = divElm.querySelector(".verifikasiFld");
        /** @type {HTMLDivElement} */    const verifikasiStc = divElm.querySelector(".verifikasiStc");
        /** @type {HTMLInputElement} */  const verifiedFld = divElm.querySelector(".verifiedFld");
        /** @type {HTMLDivElement} */    const totalDiskonStc = divElm.querySelector(".totalDiskonStc");
        /** @type {HTMLDivElement} */    const totalPembungkusStc = divElm.querySelector(".totalPembungkusStc");
        /** @type {HTMLDivElement} */    const jasaPelayananStc = divElm.querySelector(".jasaPelayananStc");
        /** @type {HTMLInputElement} */  const jasaPelayananFld = divElm.querySelector(".jasaPelayananFld");
        /** @type {HTMLDivElement} */    const grandTotalStc = divElm.querySelector(".grandTotalStc");
        /** @type {HTMLInputElement} */  const grandTotalFld = divElm.querySelector(".grandTotalFld");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $jenisResepSelect ?>", kodeJenisResepFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", pembayaranFld);
        this._selects.push(kodeJenisResepFld, pembayaranFld);

        const editEresepWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".editEresepFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.FormFields} data */
            loadData(data) {
                const iter1 = data.iter1 ?? 0;
                const iter2 = data.iter2 ?? 0;

                kodePenjualanSebelumnyaFld.value = data.kodePenjualanSebelumnya ?? "";
                kodeRuangRawatFld.value = data.kodeRuangRawat ?? "";
                noAntrianFld.value = data.noAntrian ?? "";
                editResepFld.value = data.editResep ?? "";
                noResepFld.value = data.noResep ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                tanggalPendaftaranFld.value = data.tanggalPendaftaran ?? "";
                namaPasienWgt.value = data.namaPasien ?? "";
                kelaminFld.value = data.kelamin ?? "";
                tanggalLahirFld.value = data.tanggalLahir ?? "";
                alamatFld.value = data.alamat ?? "";
                noTelefonFld.value = data.noTelefon ?? "";
                tanggalAwalResepFld.value = data.tanggalAwalResep ?? "";
                tanggalAkhirResepFld.value = data.tanggalAkhirResep ?? "";
                kodeJenisResepFld.value = data.kodeJenisResep ?? "";
                dokterFld.value = data.dokter ?? "";
                pembayaranFld.value = data.pembayaran ?? "";
                jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
                kodeBayarFld.value = data.kodeBayar ?? "";
                kodeJenisCaraBayarFld.value = data.kodeJenisCaraBayar ?? "";
                namaInstalasiFld.value = data.namaInstalasi ?? "";
                kodeInstalasiFld.value = data.kodeInstalasi ?? "";
                namaPoliFld.value = data.namaPoli ?? "";
                kodePoliFld.value = data.kodePoli ?? "";
                verifikasiFld.checked = iter2 > iter1;
                verifiedFld.value = iter2 > iter1 ? "1" : "0";
                verifikasiStc.innerHTML = str._<?= $h("Ke {{X}} Dari {{Y}}") ?>.replace("{{X}}", iter2 + 1).replace("{{X}}", iter1 + 1);

                jasaPelayananStc.innerHTML = data.jasaPelayanan ?? "";
                jasaPelayananFld.value = data.jasaPelayanan ?? "";
                grandTotalStc.innerHTML = data.grandTotal ?? "";
                grandTotalFld.value = data.grandTotal ?? "";
            },
            profile: {
                add() {
                    // TODO: js: uncategorized: finish this
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit(event) {
                const widget = tlm.app.getWidget("_<?= $viewAntrianWidgetId ?>");
                widget.show();
                widget.loadData({noResep: event.data.noResep}, true);
            },
            onFailSubmit() {
                alert(str._<?= $h("terjadi error") ?>);
            }
        });

        function hitungTotal() {
            let obat = 0;
            let racikan = 0;
            let total = 0;
            let diskonObat = 0;
            let diskonRacik = 0;
            const daftarRacikan = [];
            let totalPembungkus = 0;

            obatTbl.querySelectorAll(".obatBiasaFld").forEach(/** @type {HTMLInputElement} */elm => {
                if (!elm.value) return;

                const fields = closest(elm, "tr").fields;
                const idRacik = fields.idRacikFld.value;
                const kuantitas = sysNum(fields.kuantitasWgt.value);

                if (!idRacik && kuantitas) {
                    obat++;
                } else if (!daftarRacikan.includes(idRacik)) {
                    daftarRacikan.push(idRacik);
                    racikan++;
                }
            });

            obatTbl.querySelectorAll(".diskonObatFld").forEach(/** @type {HTMLInputElement} */elm => {
                const fields = closest(elm, "tr").fields;
                const kuantitas = sysNum(fields.kuantitasWgt.value);
                const harga = sysNum(fields.hargaJualWgt.value);
                const diskon = sysNum(elm.value);
                diskonObat += kuantitas * harga * diskon / 100;
            });

            obatTbl.querySelectorAll("input[name^=hargasatuan]").forEach(item => {
                const id = item.id.split("_")[1];
                const idDiskon = Math.floor(id / 10) * 10;
                const kuantitas = sysNum(divElm.querySelector(".kuantitasFld").value);
                const harga = sysNum(divElm.querySelector(".hargaJualFld").value);
                const diskon = sysNum(divElm.querySelector("#diskonracik_" + idDiskon).value);
                diskonRacik += kuantitas * harga * diskon / 100;
            });

            obatTbl.querySelectorAll(".listhargaobat").forEach(/** @type {HTMLInputElement} */elm => {
                const trElm = closest(elm, "tr");
                const kuantitasFld = trElm.querySelector(".kuantitasFld");
                const stok = sysNum(kuantitasFld.dataset.stok);
                const kuantitas = sysNum(kuantitasFld.value);
                kuantitasFld.style.color = (stok > kuantitas) ? "black" : "red";
                total += kuantitas * sysNum(trElm.fields.hargaJualWgt.value);
            });

            obatTbl.querySelectorAll(".hargapembungkus").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qtypembungkus_" + id).value);
                const harga = sysNum(divElm.querySelector("#hargapembungkus_" + id).value);
                total += kuantitas * harga;
                totalPembungkus += kuantitas * harga;
            });

            // TODO: js: uncategorized: move to controller
            const jasaObat = 300;
            const jasaRacik = 500;

            const totalDiskon = diskonRacik + diskonObat;
            const kodeJenisResep = kodeJenisResepFld.value;

            // TODO: js: uncategorized: move to controller
            const daftarJenisResep = {
                "Sitostatika 3":       137_000,
                "Sitostatika 2/RJ/UE": 122_000,
                "Sitostatika 1":       128_750,
                "Sitostatika VIP":     130_000,
                "00":                        0,
            };

            const jasaPelayanan = (jasaObat * obat) + (jasaRacik * racikan) + daftarJenisResep[kodeJenisResep];

            const totalTanpaJasa = total + totalPembungkus - totalDiskon;
            const totalAwal = jasaPelayanan + total + totalPembungkus - totalDiskon;
            const totalAkhir = Math.ceil(totalAwal / 100) * 100;
            const totalJasa = totalAkhir - totalTanpaJasa;

            // TODO: js: uncategorized: confirm to controller, are these necessary. if not, change them to static.
            jasaPelayananFld.value = currency(totalJasa);
            grandTotalFld.value = currency(totalAkhir);

            totalDiskonStc.innerHTML = currency(totalDiskon);
            totalPembungkusStc.innerHTML = currency(totalPembungkus);
            jasaPelayananStc.innerHTML = currency(totalJasa);
            grandTotalStc.innerHTML = currency(totalAkhir);
        }

        dokterFld.addEventListener("focus", () => {
            $.post({
                url: "<?=  $dokterUrl ?>",
                data: {q1: noPendaftaranFld.value},
                /** @param {string} data */
                success(data) {
                    if (data == "1") {
                        alert(str._<?= $h("Pasien Telah Keluar") ?>);
                    }
                }
            });
        });

        const kodeRekamMedisWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRekamMedisFld"),
            maxItems: 1,
            valueField: "kodeRekamMedis",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.EresepUi.Edit.RekamMedisFields} data
             */
            assignPairs(formElm, data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.nama ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.RekamMedisFields} data */
            itemRenderer(data) {return `<div class="item">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                const from = divElm.querySelector(".rmfrom").value;
                const to = divElm.querySelector(".rmto").value;
                $.post({
                    url: "<?= $rekamMedisAcplUrl ?>",
                    data: {kodeRekamMedis: typed, tanggalAwal: from, tanggalAkhir: to},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.EresepUi.Edit.RekamMedisFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepUi.Edit.RegistrasiFields} obj */
                    success(obj) {
                        alamatFld.value = obj.alamat;
                        pembayaranFld.value = obj.caraBayar;
                        kelaminFld.style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        tanggalLahirFld.value = userDate(obj.tanggalLahir);
                        noTelefonFld.value = obj.noTelefon;
                        namaInstalasiFld.value = obj.namaInstalasi;
                        divElm.querySelector(".nm_kamar").value = obj.namaKamar || "";
                        namaPoliFld.value = obj.namaPoli;
                        divElm.querySelector(".detailuser").readonly = true;
                        jenisCaraBayarFld.value = obj.jenisCaraBayar;
                        kodeJenisCaraBayarFld.value = obj.kodeJenisCaraBayar;
                        kodeRuangRawatFld.value = obj.kodeRuangRawat;
                        kodeBayarFld.value = obj.kodeBayar;
                        kodeInstalasiFld.value = obj.kodeInstalasi;
                        kodePoliFld.value = obj.kodePoli;
                        dokterFld.dispatchEvent(new Event("focus"));

                        $.post({
                            url: "<?= $noRekamMedis3Url ?>",
                            data: {kodeRekamMedis: obj.kodeRekamMedis},
                            /** @param {string} data */
                            success(data) {
                                const listResepElm = divElm.querySelector(".listresep");
                                listResepElm.style.display = "block";
                                listResepElm.innerHTML = data;
                            }
                        });
                    }
                });
            }
        });

        const namaPasienWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaPasienFld"),
            maxItems: 1,
            valueField: "nama",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.EresepUi.Edit.NamaFields} data
             */
            assignPairs(formElm, data) {
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.NamaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.NamaFields} data */
            itemRenderer(data) {return `<div class="item">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $namaAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.EresepUi.Edit.NamaFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepUi.Edit.RegistrasiFields} obj */
                    success(obj) {
                        alamatFld.value = obj.alamat;
                        kelaminFld.style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        tanggalLahirFld.value = userDate(obj.tanggalLahir);
                        noTelefonFld.value = obj.noTelefon;
                        namaInstalasiFld.value = obj.namaInstalasi;
                        namaPoliFld.value = obj.namaPoli;
                        divElm.querySelector(".detailuser").readonly = true;
                        divElm.querySelector("#obat_1").dispatchEvent(new Event("focus"));

                        $.post({
                            url: "<?= $nama3Url ?>",
                            data: {kodeRekamMedis: obj.kodeRekamMedis},
                            /** @param {string} data */
                            success(data) {
                                const listResepElm = divElm.querySelector(".listresep");
                                listResepElm.style.display = "block";
                                listResepElm.innerHTML = data;
                            }
                        });
                    }
                });
            }
        });

        const obatTbl = divElm.querySelector(".obatTbl");
        const itemWgt = new spl.BulkInputWidget({
            element: obatTbl,
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.obatBiasaWgt.value = data.namaObat ?? "";
                fields.kodeObatAwalFld.value = data.kodeObatAwal ?? "";
                fields.kodeObatFld.value = data.kodeObat ?? "";
                fields.kuantitasWgt.value = data.kuantitas ?? "";
                fields.idRacikFld.value = data.idRacik ?? "";
                fields.namaSigna1Wgt.value = data.namaSigna1 ?? "";
                fields.namaSigna2Wgt.value = data.namaSigna2 ?? "";
                fields.namaSigna3Wgt.value = data.namaSigna3 ?? "";
                fields.namaKemasanFld.value = data.namaKemasan ?? "";
                fields.hargaJualWgt.value = data.hargaJual ?? "";
                fields.diskonObatFld.value = data.diskonObat ?? "";
                fields.cekStokBtn.value = data.kodeObat ?? "";
            },
            addRow(trElm) {
                const kodeObatFld = trElm.querySelector(".kodeObatFld");
                const namaKemasanFld = trElm.querySelector(".namaKemasanFld");

                const obatBiasaWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".obatBiasaFld"),
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObatFld.value = data.kode ?? "";
                        namaKemasanFld.value = data.satuanKecil ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data */
                    itemRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="item" style="color:${warna}">${data.namaBarang} (${data.kode})</div>`;
                    },
                    load(typed, processor) {
                        if (!typed.length) {
                            processor([]);
                            return;
                        }

                        $.post({
                            url: "<?= $obatAcplUrl ?>",
                            data: {q: typed},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    },
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} obj */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode, jenisResepObat: kodeJenisResepFld.value},
                            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.HargaFields} data */
                            success(data) {
                                trElm.querySelector(".obatBiasaFld").setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                                const hargaJualFld = trElm.querySelector(".hargaJualFld");
                                hargaJualFld.value = currency(data.harga);
                                hargaJualFld.classList.add("listhargaobat");

                                const kuantitasFld = trElm.querySelector(".kuantitasFld");
                                kuantitasFld.dataset.stok = data.stok;
                                kuantitasFld.dispatchEvent(new Event("focus"));

                                const classList = kuantitasFld.classList;
                                (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                                hitungTotal();
                            }
                        });
                    }
                });

                const namaSigna1Wgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaSigna1Fld"),
                    maxItems: 1,
                    valueField: "nama",
                    labelField: "nama",
                    load(typed, processor) {
                        if (!typed.length) {
                            processor([]);
                            return;
                        }

                        $.post({
                            url: "<?= $signaAcplUrl ?>",
                            data: {nama: typed, kategori: "signa1"},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    }
                });

                const namaSigna2Wgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaSigna2Fld"),
                    maxItems: 1,
                    valueField: "nama",
                    labelField: "nama",
                    load(typed, processor) {
                        if (!typed.length) {
                            processor([]);
                            return;
                        }

                        $.post({
                            url: "<?= $signaAcplUrl ?>",
                            data: {nama: typed, kategori: "signa2"},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    }
                });

                const namaSigna3Wgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaSigna3Fld"),
                    maxItems: 1,
                    valueField: "nama",
                    labelField: "nama",
                    load(typed, processor) {
                        if (!typed.length) {
                            processor([]);
                            return;
                        }

                        $.post({
                            url: "<?= $signaAcplUrl ?>",
                            data: {nama: typed, kategori: "signa3"},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    }
                });

                const kuantitasWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".kuantitasFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.floatNumberSetting
                });

                const hargaJualWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".hargaJualFld"),
                    errorRules: [{greaterThan: 0}],
                    ...tlm.currNumberSetting
                });

                trElm.fields = {
                    kodeObatFld,
                    namaKemasanFld,
                    obatBiasaWgt,
                    namaSigna1Wgt,
                    namaSigna2Wgt,
                    namaSigna3Wgt,
                    kuantitasWgt,
                    hargaJualWgt,
                    kodeObatAwalFld: trElm.querySelector(".kodeObatAwalFld"),
                    idRacikFld: trElm.querySelector(".idRacikFld"),
                    diskonObatFld: trElm.querySelector(".diskonObatFld"),
                    cekStokBtn: trElm.querySelector(".cekStokBtn"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.obatBiasaWgt.destroy();
                fields.namaSigna1Wgt.destroy();
                fields.namaSigna2Wgt.destroy();
                fields.namaSigna3Wgt.destroy();
                fields.kuantitasWgt.destroy();
                fields.hargaJualWgt.destroy();

                hitungTotal();
            },
            profile: {
                add() {
                    // TODO: js: uncategorized: finish this
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            addRowBtn: ".editEresepFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const cekStokBtn = event.target;
            if (!cekStokBtn.matches(".cekStokBtn")) return;

            $.post({
                url: "<?= $stokDataUrl ?>",
                data: {id: cekStokBtn.value},
                success(data) {
                    const {stokKatalog, daftarStokKatalog} = data;
                    headerElm.innerHTML = `${stokKatalog.namaBarang} (${stokKatalog.idKatalog})`;
                    footerElm.innerHTML = "total: " + daftarStokKatalog.reduce((acc, curr) => acc + curr.jumlahStokFisik, 0);
                    stokWgt.load(daftarStokKatalog);
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const calculateBtn = event.target;
            if (!calculateBtn.matches(".calculateBtn")) return;

            const penjumlah = [];
            const angka = [];
            let j = 0;

            calculateBtn.value.split("").forEach(val => {
                if (val == "+" || val == "-" || val == "/" || val == "*") {
                    penjumlah[j] = val;
                    j++;
                } else if (angka[j] >= 0) {
                    angka[j] = angka[j] + "" + val;
                } else {
                    angka[j] = val;
                }
            });

            for (let g = 0; g < penjumlah.length; g++) {
                const g2 = g + 1;
                const num1 = Number(angka[g]);
                const num2 = Number(angka[g2]);

                switch (penjumlah[g]) {
                    case "+": angka[g2] = num1 + num2; break;
                    case "-": angka[g2] = num1 - num2; break;
                    case "*": angka[g2] = num1 * num2; break;
                    case "/": angka[g2] = num1 / num2; break;
                    default:  throw new Error("Wrong operator");
                }
            }

            calculateBtn.value = angka.pop();
            hitungTotal();
        });

        itemWgt.addDelegateListener("tbody", "change", (event) => {
            if (!event.target.matches(".kuantitasFld, .idRacikFld, .kodeJenisResepFld, .checkTotalFld, input[name^=diskon]")) return;
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.EresepUi.Edit.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        // JUNK -----------------------------------------------------

        const scntDiv = divElm.querySelector("#listobat");

        const namaObatWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaObatFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data */
            itemRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="item" style="color:${warna}">${data.namaBarang} (${data.kode})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $obatAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} */
                const obj = this.options[value];
                const id = this.element.dataset.no;

                const obatFld = divElm.querySelector("#obat_" + id);
                obatFld.value = obj.namaBarang;
                obatFld.readonly = true;
                obatFld.setAttribute("title", obj.sinonim);

                divElm.querySelector("#kode_obat_" + id).value = obj.kode;
                divElm.querySelector("#satuan_" + id).value = obj.satuanKecil;

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kode: obj.kode, jenisResepObat: kodeJenisResepFld.value},
                    /** @param {his.FatmaPharmacy.views.EresepUi.Edit.HargaFields} data */
                    success(data) {
                        divElm.querySelector("#obat_" + id).setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                        const hargaFld = divElm.querySelector("#harga_" + id);
                        hargaFld.value = data.harga;
                        hargaFld.classList.add("listhargaobat");

                        const kuantitasFld = divElm.querySelector("#qty_" + id);
                        kuantitasFld.dataset.stok = data.stok;
                        kuantitasFld.dispatchEvent(new Event("focus"));

                        const classList = kuantitasFld.classList;
                        (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                        hitungTotal();
                    }
                });
            }
        });

        const obatxxWgt = new spl.SelectWidget({
            element: divElm.querySelector(".obatxxFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data */
            itemRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="item" style="color:${warna}">${data.namaBarang} (${data.kode})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $obatAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} */
                const obj = this.options[value];
                const id = this.element.dataset.no;

                const obatFld = divElm.querySelector("#obat_" + id);
                obatFld.value = obj.namaBarang;
                obatFld.readonly = true;
                obatFld.setAttribute("title", obj.sinonim);

                divElm.querySelector("#kode_obat_" + id).value = obj.kode;
                divElm.querySelector("#satuan_" + id).value = obj.satuanKecil;

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kode: obj.kode, jenisResepObat: kodeJenisResepFld.value},
                    /** @param {his.FatmaPharmacy.views.EresepUi.Edit.HargaFields} data */
                    success(data) {
                        divElm.querySelector("#obat_" + id).setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                        const hargaFld = divElm.querySelector("#harga_" + id);
                        hargaFld.value = data.harga;
                        hargaFld.classList.add("listhargaobat");

                        const kuantitasFld = divElm.querySelector("#qty_" + id);
                        kuantitasFld.dataset.stok = data.stok;
                        kuantitasFld.dispatchEvent(new Event("focus"));

                        const classList = kuantitasFld.classList;
                        (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                        hitungTotal();
                    }
                });
            }
        });

        const obatzzWgt = new spl.SelectWidget({
            element: divElm.querySelector(".obatzzFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} data */
            itemRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="item" style="color:${warna}">${data.namaBarang} (${data.kode})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $obatAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.EresepUi.Edit.ObatAcplFields} */
                const obj = this.options[value];
                const id = this.element.dataset.no;

                const obatFld = divElm.querySelector("#obat_" + id);
                obatFld.value = obj.namaBarang;
                obatFld.readonly = true;
                obatFld.setAttribute("title", obj.sinonim);

                divElm.querySelector("#kode_obat_" + id).value = obj.kode;
                divElm.querySelector("#satuan_" + id).value = obj.satuanKecil;

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kode: obj.kode, jenisResepObat: kodeJenisResepFld.value},
                    /** @param {his.FatmaPharmacy.views.EresepUi.Edit.HargaFields} data */
                    success(data) {
                        divElm.querySelector("#obat_" + id).setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                        const hargaFld = divElm.querySelector("#harga_" + id);
                        hargaFld.value = data.harga;
                        hargaFld.classList.add("listhargaobat");

                        const kuantitasFld = divElm.querySelector("#qty_" + id);
                        kuantitasFld.dataset.stok = data.stok;
                        kuantitasFld.dispatchEvent(new Event("focus"));

                        const classList = kuantitasFld.classList;
                        (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                        hitungTotal();
                    }
                });
            }
        });

        scntDiv.addEventListener("click", (event) => {
            const hapusTabelBtn = /** @type {HTMLButtonElement} */ event.target;
            if (!hapusTabelBtn.matches(".hapusTabelBtn")) return;

            const id = hapusTabelBtn.dataset.no;
            divElm.querySelector("#tabel_" + id).remove();
        });

        scntDiv.addEventListener("click", (event) => {
            const racikanBtn = /** @type {HTMLButtonElement} */ event.target;
            if (!racikanBtn.matches(".racikanBtn")) return;

            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const x = racikanBtn.dataset.no;
            const no = divElm.querySelectorAll(".group #tabel_rac").length;
            const idx = x + x + (divElm.querySelectorAll(`#racik_${x} .baris`).length + 1);

            const trStr = drawTr("tbody", {
                class: "baris no-racik-" + idx,
                td_1: {
                    hidden_1: {id: "nm_racikan_1", name: `nm_racikan[${no}]`, value: "Racikan " + x},
                    hidden_2: {name: "no_racikan[]", value: x},
                    hidden_3: {name: `kode_obat-${no}[]`, id: "kode_obat_" + idx},
                    hidden_4: {id: "generik-" + idx},
                    input: {class: ".obatxxFld", id: "obat_" + idx, size: 20, name: `obat-${no}[]`, "data-no": idx},
                    button: {class: ".cekStokBtn", "data-kode": `obat_${no}${no}0`, text: str._<?= $h("Cek") ?>, title: str._<?= $h("Cek Stok") ?>}
                },
                td_2: {
                    hidden: {id: "qty_" + idx, name: `ketjumlah-${x}[]`},
                    input: {class: ".checkTotalFld", name: `qty-${x}[]`}
                },
                td_3: {
                    input: {name: "namaKemasan[]", id: "satuan_" + idx, readonly: true}
                },
                td_4: {
                    hidden: {name: "harga_beli[]", id: "harga_beli_" + i},
                    input: {name: `hargasatuan-${no}[]`, class: "harga-hide  harga-hide-" + i, id: "harga_" + idx, readonly: true}
                },
                td_5: {
                    button_1: {class: ".hapusObatRacikBtn", id: "hapus", "data-no": idx, text: "-"}
                    button_2: {class: ".racikanBtn", "data-no": no, text: "+"}
                },
            });
            divElm.querySelector("#racik_" + x).insertAdjacentHTML("beforeend", trStr);
            divElm.querySelector("#obat_" + idx).dispatchEvent(new Event("focus"));
        });

        scntDiv.addEventListener("click", (event) => {
            const hapusObatRacikBtn = /** @type {HTMLButtonElement} */ event.target;
            if (!hapusObatRacikBtn.matches(".hapusObatRacikBtn")) return;
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>)) return;

            const id = hapusObatRacikBtn.dataset.no;
            divElm.querySelector(".no-racik-" + id).remove();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(editEresepWgt, kodeRekamMedisWgt, namaPasienWgt, itemWgt, stokWgt, namaObatWgt, obatxxWgt, obatzzWgt);
        tlm.app.registerWidget(this.constructor.widgetName, editEresepWgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<form class="editEresepFrm">
    <!-- FORM ELEMENT FROM WIDGET ARE HERE -->

    <!-- BULK INPUT-1 FROM WIDGET ARE HERE -->

    <div class="well form-horizontal">
        <div class="span8" style="margin-left:160px">

            <div id="add_racikan">
                <?php foreach ($daftarRacik ?? [] as $rc) { ?>
                <?php $z++ ?>
                <?php if ($idRacik != $rc->idRacik) { ?>
                <tbody id="racik_<?= $r ?>"></tbody>
                <?php if ($idRacik) { ?>

                <?php $r++ ?>
                </table>
                <button class="btn btn-mini btn-danger hapusTabelBtn" data-no="<?= $r ?>" type="button">x</button>
            </div>
        </div>
        <?php } ?>
        <div class="group">
            <div id="tabel_<?= $r ?>" style="padding:5px; background-color:#FFFFFF; margin-bottom:10px; border-bottom:2px solid #F00">
                Nama Racikan : <input id="nm_racikan_<?= $r ?>" size="20" class="racikanobat" name="nm_racikan[<?= $r ?>]" value="<?= $rc->namaRacik ?>"/>
                <input type="hidden" name="no_racikan[]" value="<?= $r ?>"/>
                <input type="hidden" name="kode_signa_racik[<?= $r ?>]" id="kode_signa_<?= $r ?><?= $r ?>0" value="<?= $rc->signa ?>"/>
                <input name="numero[<?= $r ?>]" placeholder="No" value="<?= $rc->noRacik ?>"/>
                <input name="diskonracik[<?= $r ?>]" placeholder="Diskon" id="diskonracik_<?= $r ?><?= $r ?>0" value="<?= $rc->diskon ?>"/>
                <input name="nama_signa_racik[<?= $r ?>]" placeholder="Aturan Pakai" id="nama_signa_<?= $r ?><?= $r ?>0" value="<?= $rc->signa ?>"/>

                <br/><br/>

                <table class="table table-bordered table-condensed" id="tabel_rac">
                    <tr class="baris">
                        <td>Nama Obat</td>
                        <td>Jumlah</td>
                        <td>Kuantitas</td>
                        <td>Satuan</td>
                        <td>Harga</td>
                        <td>Action</td>
                    </tr>
                    <?php } else { ?>
                    <?php } ?>

                    <tr>
                        <td>
                            <input id="obat_<?= $r . $r . $j ?>" size="20" class="obatzzFld" name="obat-<?= $r ?>[]" value="<?= $rc->namaBarang ?>" placeholder="Nama Obat" data-no="<?= $r ?><?= $r . $j ?>"/>
                            <input type="hidden" name="kode_obat-<?= $r ?>[]" value="<?= $rc->kodeObat ?>" id="kode_obat_<?= $r . $r . $j ?>"/>
                            <input type="hidden" name="kode_obat_awal_racik[<?= $r ?>][]" value="<?= $rc->kodeObat ?>" id="kode_obat_awal_racik_<?= $r . $r . $j ?>"/>
                            <input type="hidden" id="generik-<?= $r . $r . $j ?>"/>
                        </td>
                        <td>
                            <input id="ketjumlah_<?= $r . $r . $j ?>" name="ketjumlah-<?= $r ?>[]" value="<?= $rc->keteranganJumlah ?>" class="input-mini" placeholder="Jumlah"/>
                        </td>
                        <td>
                            <input id="qty_<?= $r . $r . $j ?>" name="qty-<?= $r ?>[]" value="<?= $rc->jumlahPenjualan ?>" class="input-mini checkTotalFld" placeholder="Qty"/>
                        </td>
                        <td>
                            <input name="namaKemasan-<?= $r ?>[]" value="<?= $rc->namaKemasan ?>" id="satuan_<?= $r . $r . $j ?>" readonly />
                        </td>
                        <td>
                            <input name="hargasatuan-<?= $r ?>[]" value="<?= $rc->harga ?>" id="harga_<?= $r . $r . $j ?>" class="listhargaobat" readonly />
                        </td>
                        <td>
                            <button class="btn btn-mini btn-primary racikanBtn" data-no="<?= $r ?>" type="button"></button>
                        </td>
                    </tr>

                    <?php
                    $idRacik = $rc->idRacik;

                    $j++;
                    }
                    if ($z > 1) {
                    ?>
                    </tbody>
                    <tbody id="racik_<?= $r ?>"></tbody>
                </table>
                <button class="btn btn-mini btn-danger hapusTabelBtn" data-no="<?= $r ?>" type="button">x</button>
            </div>
        </div>
        <?php } ?>
    </div>
    <div>
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
