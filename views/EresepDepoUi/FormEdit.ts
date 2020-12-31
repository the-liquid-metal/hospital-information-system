<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepoUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepo/edit.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class FormEdit
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $dataUrl,
        string $actionUrl,
        string $rekamMedisAcplUrl,
        string $registrasiAjaxUrl,
        string $noRekamMedis3Url,
        string $dokterUrl,
        string $namaAcplUrl,
        string $nama3Url,
        string $obatAcplUrl,
        string $hargaUrl,
        string $signaAcplUrl,
        string $stokDataUrl,
        string $viewStrukWidgetId,
        string $jenisResepSelect,
        string $caraBayarSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $idRacik = "";
        $j = 0;
        $r = 1;
        $z = 1;
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepDepoUi.Edit {
    export interface FormFields {
        kodeRuangRawat:          string;
        kodePenjualanSebelumnya: string;
        noResep:                 string;
        kodeRekamMedis:          string;
        noPendaftaran:           string;
        tanggalPendaftaran:      string;
        namaPasien:              string;
        kelamin:                 string;
        tanggalLahir:            string;
        alamat:                  string;
        noTelefon:               string;
        tanggalAwalResep:        string;
        tanggalAkhirResep:       string;
        kodeJenisResep:          string;
        namaInstalasi:           string;
        kodeInstalasi:           string;
        pembayaran:              string;
        kodePembayaran:          string;
        jenisCaraBayar:          string;
        kodeJenisCaraBayar:      string;
        namaPoli:                string;
        namaRuangRawat:          "MISSING", // missing in form
        noUrut:                  "MISSING", // missing in form
        editResep:               string; // sama dengan kodePenjualanSebelumnya
        verifikasi:              string;
        verified:                string;
        dokter:                  string;

        noAntrian:               string; // not used in controller
        riwayatAlergi:           string; // not used in controller
        kodePoli:                string; // not used in controller
        totalDiskon:             string; // exist but missing, not used in controller
        pembungkus:              "___", // exist but missing, not used in controller
        jasaPelayanan:           string; // not used in controller
        grandTotal:              string; // not used in controller
        iter1:                   string; // not used in controller
        iter2:                   string; // not used in controller

        daftarObat:              Array<ObatFields>;
        daftarRacik:             Array<RacikFields>;
    }

    export interface ObatFields {
        kodeObat:    string;
        kuantitas:   string;
        idRacik:     string;
        namaSigna1:  string;
        namaSigna2:  string;
        namaSigna3:  string;
        diskonObat:  string; // missing in controller
        hargaJual:   string;

        namaObat:    string; // not used in controller
        namaKemasan: string; // not used in controller
    }

    export interface RacikFields {
        noRacik:             string; // numero[$r]
        diskonRacik :        string; // diskonracik[$r]
        kodeSigna:           "kode_signa"; // missing in form
        kodeSignaRacik:      string; // kode_signa_racik[$r]
        namaRacikan:         string; // nm_racikan[$r]
        kuantitasPembungkus: "qtypembungkus"; // missing in form
        kodePembungkus:      "kode_pembungkus"; // missing in form
        hargaPembungkus:     "hargapembungkus"; // missing in form
        kodeObatAwal:        "kode_obat_awal"; // missing in form

        // hargasatuan-$r[] // chance to correct in controller
        // kode_obat-$r[] // chance to correct in controller
        // qty-$r[] // chance to correct in controller
        // ketjumlah-$r[] // chance to correct in controller

        // no_racikan[] // not used in controller
        // obat-$r[] // not used in controller
        // harga_beli[] // not used in controller
        // nama_signa_racik[$r] // not used in controller
        // kode_obat_awal_racik[$r][] // not used in controller
        // satuan-$r[] // not used in controller
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".editEresepDepoFrm",
            hidden_1: {class: ".kodeRuangRawatFld", name: "kodeRuangRawat"},
            hidden_2: {class: ".noAntrianFld", name: "noAntrian"},
            hidden_3: {class: ".kodePenjualanSebelumnyaFld", name: "kodePenjualanSebelumnya"},
            hidden_4: {class: ".editResepFld", name: "editResep"},
            row_1: {
                box_1: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".verifiedFld", name: "verified"},
                    hidden_2: {class: ".verifiedKeFld", name: "verifiedke"},
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
                        label: tlm.stringRegistry._<?= $h("Riwayat Alergi") ?>,
                        input: {class: ".riwayatAlergiFld", name: "riwayatAlergi"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal Resep") ?>,
                        input: {class: ".tanggalAwalResepFld", name: "tanggalAwalResep"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir Resep") ?>,
                        input: {class: ".tanggalAkhirResepFld", name: "tanggalAkhirResep"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Jenis Resep") ?>,
                        select: {class: ".kodeJenisResepFld", name: "kodeJenisResep"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Dokter") ?>,
                        input: {class: ".dokterFld", name: "dokter"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Pembayaran") ?>,
                        select: {class: ".pembayaranFld", name: "pembayaran"},
                        hidden_1: {class: ".jenisCaraBayarFld", name: "jenisCaraBayar"},
                        hidden_2: {class: ".kodePembayaranFld", name: "kodePembayaran"},
                        hidden_3: {class: ".kodeJenisCaraBayarFld", name: "kodeJenisCaraBayar"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Nama Instalasi") ?>,
                        input: {class: ".namaInstalasiFld", name: "namaInstalasi"},
                        hidden: {class: ".kodeInstalasiFld", name: "kodeInstalasi"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Nama Poli") ?>,
                        input: {class: ".namaPoliFld", name: "namaPoli"},
                        hidden: {class: ".kodePoliFld", name: "kodePoli"}
                    },
                    formGroup_17: {
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
                        staticText: {class: ".pembungkusStc"}
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
                                input: {class: ".namaObatFld", name: "namaObat[]"},
                                hidden: {class: ".kodeObatFld", name: "kodeObat[]"},
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
                                input_3: {class: ".namaSigna3Fld", name: "namaSigna3[]"},
                            },
                            td_5: {
                                input: {class: ".namaKemasanFld", name: "namaKemasan[]"}
                            },
                            td_6: {
                                input: {class: ".hargaJualFld", name: "hargaJual[]"}
                            },
                            td_7: {
                                input: {class: ".diskonObatFld", name: "diskonObat[]"}
                            },
                            td_8: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {colspan: 6},
                            td_2: {
                                button: {class: ".addRowBtn", type: "success", size: "xs", label: tlm.stringRegistry._<?= $h("add") ?>}
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
        const idDepo = tlm.user.idDepo;
        const drawTr = spl.TableDrawer.drawTr;
        const {toUserMoney: currency, toSystemNumber: sysNum, toUserDate: userDate, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodeRuangRawatFld = divElm.querySelector(".kodeRuangRawatFld");
        /** @type {HTMLInputElement} */  const noAntrianFld = divElm.querySelector(".noAntrianFld");
        /** @type {HTMLInputElement} */  const kodePenjualanSebelumnyaFld = divElm.querySelector(".kodePenjualanSebelumnyaFld");
        /** @type {HTMLInputElement} */  const editResepFld = divElm.querySelector(".editResepFld");
        /** @type {HTMLInputElement} */  const noResepFld = divElm.querySelector(".noResepFld");
        /** @type {HTMLInputElement} */  const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLInputElement} */  const tanggalPendaftaranFld = divElm.querySelector(".tanggalPendaftaranFld");
        /** @type {HTMLInputElement} */  const kelaminFld = divElm.querySelector(".kelaminFld");
        /** @type {HTMLInputElement} */  const tanggalLahirFld = divElm.querySelector(".tanggalLahirFld");
        /** @type {HTMLInputElement} */  const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */  const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLInputElement} */  const riwayatAlergiFld = divElm.querySelector(".riwayatAlergiFld");
        /** @type {HTMLInputElement} */  const tanggalAwalResepFld = divElm.querySelector(".tanggalAwalResepFld");
        /** @type {HTMLInputElement} */  const tanggalAkhirResepFld = divElm.querySelector(".tanggalAkhirResepFld");
        /** @type {HTMLSelectElement} */ const kodeJenisResepFld = divElm.querySelector(".kodeJenisResepFld");
        /** @type {HTMLInputElement} */  const dokterFld = divElm.querySelector(".dokterFld");
        /** @type {HTMLSelectElement} */ const pembayaranFld = divElm.querySelector(".pembayaranFld");
        /** @type {HTMLInputElement} */  const jenisCaraBayarFld = divElm.querySelector(".jenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const kodePembayaranFld = divElm.querySelector(".kodePembayaranFld");
        /** @type {HTMLInputElement} */  const kodeJenisCaraBayarFld = divElm.querySelector(".kodeJenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const namaInstalasiFld = divElm.querySelector(".namaInstalasiFld");
        /** @type {HTMLInputElement} */  const kodeInstalasiFld = divElm.querySelector(".kodeInstalasiFld");
        /** @type {HTMLInputElement} */  const namaPoliFld = divElm.querySelector(".namaPoliFld");
        /** @type {HTMLInputElement} */  const kodePoliFld = divElm.querySelector(".kodePoliFld");
        /** @type {HTMLInputElement} */  const verifikasiFld = divElm.querySelector(".verifikasiFld");
        /** @type {HTMLInputElement} */  const verifiedFld = divElm.querySelector(".verifiedFld");
        /** @type {HTMLInputElement} */  const verifiedKeFld = divElm.querySelector(".verifiedKeFld");
        /** @type {HTMLDivElement} */    const verifikasiStc = divElm.querySelector(".verifikasiStc");
        /** @type {HTMLDivElement} */    const totalDiskonStc = divElm.querySelector(".totalDiskonStc");
        /** @type {HTMLDivElement} */    const pembungkusStc = divElm.querySelector(".pembungkusStc");
        /** @type {HTMLDivElement} */    const jasaPelayananStc = divElm.querySelector(".jasaPelayananStc");
        /** @type {HTMLInputElement} */  const jasaPelayananFld = divElm.querySelector(".jasaPelayananFld");
        /** @type {HTMLDivElement} */    const grandTotalStc = divElm.querySelector(".grandTotalStc");
        /** @type {HTMLInputElement} */  const grandTotalFld = divElm.querySelector(".grandTotalFld");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $jenisResepSelect ?>", kodeJenisResepFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", pembayaranFld);
        this._selects.push(kodeJenisResepFld, pembayaranFld);

        const editEresepDepoWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".editEresepDepoFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.FormFields} data */
            loadData(data) {
                const iter1 = data.iter1 ?? 0;
                const iter2 = data.iter2 ?? 0;

                kodeRuangRawatFld.value = data.kodeRuangRawat ?? "";
                noAntrianFld.value = data.noAntrian ?? "";
                kodePenjualanSebelumnyaFld.value = data.kodePenjualanSebelumnya ?? "";
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
                riwayatAlergiFld.value = data.riwayatAlergi ?? "";
                tanggalAwalResepFld.value = data.tanggalAwalResep ?? "";
                tanggalAkhirResepFld.value = data.tanggalAkhirResep ?? "";
                kodeJenisResepFld.value = data.kodeJenisResep ?? "";
                dokterFld.value = data.dokter ?? "";
                pembayaranFld.value = data.pembayaran ?? "";
                jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
                kodePembayaranFld.value = data.kodePembayaran ?? "";
                kodeJenisCaraBayarFld.value = data.kodeJenisCaraBayar ?? "";
                namaInstalasiFld.value = data.namaInstalasi ?? "";
                kodeInstalasiFld.value = data.kodeInstalasi ?? "";
                namaPoliFld.value = data.namaPoli ?? "";
                kodePoliFld.value = data.kodePoli ?? "";
                verifikasiFld.checked = iter2 > iter1;
                verifiedFld.value = iter2 > iter1 ? "1" : "0";
                verifiedKeFld.value = iter2 + 1;
                verifikasiStc.innerHTML = str._<?= $h("Ke {{X}} Dari {{Y}}") ?>.replace("{{X}}", iter2 + 1).replace("{{Y}}", iter1 + 1);
                totalDiskonStc.innerHTML = data.totalDiskon ?? "";
                pembungkusStc.innerHTML = data.pembungkus ?? "";
                jasaPelayananStc.innerHTML = data.jasaPelayanan ?? "";
                jasaPelayananFld.value = data.jasaPelayanan ?? "";
                grandTotalStc.innerHTML = data.grandTotal ?? "";
                grandTotalFld.value = data.grandTotal ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
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
                const widget = tlm.app.getWidget("_<?= $viewStrukWidgetId ?>");
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
            let pembungkus = 0;

            obatTbl.querySelectorAll(".namaObatFld").forEach(/** @type {HTMLInputElement} */elm => {
                if (!elm.value) return;

                const idRacikan = divElm.querySelector(".idRacikFld").value;
                const kuantitas = sysNum(divElm.querySelector(".kuantitasFld").value);

                if (!idRacikan && kuantitas) {
                    obat++;
                } else if (!daftarRacikan.includes(idRacikan)) {
                    daftarRacikan.push(idRacikan);
                    racikan++;
                }
            });

            obatTbl.querySelectorAll(".diskonObatFld").forEach(() => {
                const kuantitas = sysNum(divElm.querySelector(".kuantitasFld").value);
                const harga = sysNum(divElm.querySelector(".hargaJualFld").value);
                const diskon = sysNum(divElm.querySelector(".diskonObatFld").value);
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

            obatTbl.querySelectorAll(".listhargaobat").forEach(() => {
                const kuantitasFld = divElm.querySelector(".kuantitasFld");
                const idObat = kuantitasFld.getAttribute("idobatnya");

                let total2 = 0;
                divElm.querySelectorAll(`.kuantitasFld[idobatnya='${idObat}']`).forEach(/** @type {HTMLInputElement} */item => total2 += sysNum(item.value));

                const stok = sysNum(kuantitasFld.dataset.stok);
                kuantitasFld.style.color = (stok >= total2 || (stok < 0 && total2 < 0)) ? "black" : "red";

                const kuantitas = sysNum(kuantitasFld.value);
                const harga = sysNum(divElm.querySelector(".hargaJualFld").value);
                total += kuantitas * harga;
            });

            obatTbl.querySelectorAll(".hargapembungkus").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qtypembungkus_" + id).value);
                const harga = sysNum(divElm.querySelector("#hargapembungkus_" + id).value);
                total += kuantitas * harga;
                pembungkus += kuantitas * harga;
            });

            // TODO: js: uncategorized: move to controller
            const jasaObat = 300;
            const jasaRacik = 500;

            const totalDiskon = diskonRacik + diskonObat;
            const kodeJenisResep = kodeJenisResepFld.value;

            // TODO: js: uncategorized: move to controller
            const daftarJenisResep = {
                "Sitostatika 3": 137_000,
                "Sitostatika 2/RJ/UE": 122_000,
                "Sitostatika 1": 128_750,
                "Sitostatika VIP": 130_000,
                "00": 0,
            };

            const jasaPelayanan = (jasaObat * obat) + (jasaRacik * racikan) + daftarJenisResep[kodeJenisResep];

            const totalTanpaJasa = total + pembungkus - totalDiskon;
            const totalAwal = jasaPelayanan + total + pembungkus - totalDiskon;
            const totalAkhir = Math.ceil(totalAwal / 100) * 100;
            const totalJasa = totalAkhir - totalTanpaJasa;

            // TODO: js: uncategorized: confirm to controller, are these necessary. if not, change them to static.
            jasaPelayananFld.value = currency(totalJasa);
            grandTotalFld.value = currency(totalAkhir);

            totalDiskonStc.innerHTML = currency(totalDiskon);
            pembungkusStc.innerHTML = currency(pembungkus);
            jasaPelayananStc.innerHTML = currency(totalJasa);
            grandTotalStc.innerHTML = currency(totalAkhir);
        }

        const kodeRekamMedisWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRekamMedisFld"),
            maxItems: 1,
            valueField: "kodeRekamMedis",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.RekamMedisFields} data
             */
            assignPairs(formElm, data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.nama ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.RekamMedisFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepDepoUi.Edit.RekamMedisFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.RegistrasiFields} obj */
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
                        kodePembayaranFld.value = obj.kodeBayar;
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

        dokterFld.addEventListener("focus", () => {
            $.post({
                url: "<?= $dokterUrl ?>",
                data: {q1: noPendaftaranFld.value},
                /** @param {string} data */
                success(data) {
                    if (data == "1") {
                        alert(str._<?= $h("Pasien Telah Keluar") ?>);
                    }
                }
            });
        });

        const namaPasienWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaPasienFld"),
            maxItems: 1,
            valueField: "nama",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.NamaFields} data
             */
            assignPairs(formElm, data) {
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.NamaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.NamaFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepDepoUi.Edit.NamaFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.RegistrasiFields} obj */
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
        const obatWgt = new spl.BulkInputWidget({
            element: obatTbl,
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.namaObatWgt.value = data.namaObat ?? "";
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
                /** @type {HTMLInputElement}*/  const hargaJualFld = trElm.querySelector(".hargaJualFld");
                /** @type {HTMLInputElement}*/  const namaKemasanFld = trElm.querySelector(".namaKemasanFld");
                /** @type {HTMLInputElement}*/  const kuantitasFld = trElm.querySelector(".kuantitasFld");
                /** @type {HTMLInputElement}*/  const kodeObatFld = trElm.querySelector(".kodeObatFld");
                /** @type {HTMLSelectElement}*/ const namaObatFld = trElm.querySelector(".namaObatFld");

                const namaObatWgt = new spl.SelectWidget({
                    element: namaObatFld,
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObatFld.value = data.kode ?? "";
                        namaKemasanFld.value = data.satuanKecil ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} data */
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
                            data: {q: typed, idDepo},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    },
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode, jenisResepObat: kodeJenisResepFld.value},
                            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.HargaFields} data */
                            success(data) {
                                namaObatFld.setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                                hargaJualFld.value = currency(data.harga);
                                hargaJualFld.classList.add("listhargaobat");

                                kuantitasFld.classList.add("qty_" + obj.kode);
                                kuantitasFld.dataset.idobatnya = obj.kode;
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
                        {greaterThan: 0},
                    ],
                    ...tlm.floatNumberSetting
                });

                const hargaJualWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".hargaJualFld"),
                    errorRules: [{greaterThan: 0}],
                    ...tlm.currNumberSetting
                });

                trElm.fields = {
                    namaKemasanFld,
                    kodeObatFld,
                    namaObatWgt,
                    namaSigna1Wgt,
                    namaSigna2Wgt,
                    namaSigna3Wgt,
                    kuantitasWgt,
                    hargaJualWgt,
                    idRacikFld: trElm.querySelector(".idRacikFld"),
                    diskonObatFld: trElm.querySelector(".diskonObatFld"),
                    cekStokBtn: trElm.querySelector(".cekStokBtn"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.namaObat.destroy();
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
            addRowBtn: ".editEresepDepoFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        obatWgt.addDelegateListener("tbody", "click", (event) => {
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

        /** @see {his.FatmaPharmacy.views.EresepDepoUi.Edit.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        // JUNK -----------------------------------------------------

        const namaObatWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaObatFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} data */
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
                    data: {q: typed, idDepo},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} */
                const obj = this.options[value];
                const no = this.element.dataset.no;

                const obatFld = divElm.querySelector("#obat_" + no);
                obatFld.value = obj.namaBarang;
                obatFld.readonly = true;
                obatFld.setAttribute("title", obj.sinonim);

                divElm.querySelector("#kode_obat_" + no).value = obj.kode;
                divElm.querySelector("#satuan_" + no).value = obj.satuanKecil;

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kode: obj.kode, jenisResepObat: kodeJenisResepFld.value},
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.HargaFields} data */
                    success(data) {
                        divElm.querySelector("#obat_" + no).setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                        const hargaFld = divElm.querySelector("#harga_" + no);
                        hargaFld.value = data.harga;
                        hargaFld.classList.add("listhargaobat");

                        const kuantitasFld = divElm.querySelector("#qty_" + no);
                        kuantitasFld.classList.add("qty_" + obj.kode);
                        kuantitasFld.dataset.idobatnya = obj.kode;
                        kuantitasFld.dataset.stok = data.stok;
                        kuantitasFld.dispatchEvent(new Event("focus"));

                        const classList = kuantitasFld.classList;
                        (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                        hitungTotal();
                    }
                });
            }
        });

        const cariObatWgt = new spl.SelectWidget({
            element: divElm.querySelector(".cariObatFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} data */
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
                    data: {q: typed, idDepo},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.EresepDepoUi.Edit.ObatAcplFields} */
                const obj = this.options[value];
                const no = this.element.dataset.no;

                const obatFld = divElm.querySelector("#obat_" + no);
                obatFld.value = obj.namaBarang;
                obatFld.readonly = true;
                obatFld.setAttribute("title", obj.sinonim);

                divElm.querySelector("#kode_obat_" + no).value = obj.kode;
                divElm.querySelector("#satuan_" + no).value = obj.satuanKecil;

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kode: obj.kode, jenisResepObat: kodeJenisResepFld.value},
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Edit.HargaFields} data */
                    success(data) {
                        divElm.querySelector("#obat_" + no).setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                        const hargaFld = divElm.querySelector("#harga_" + no);
                        hargaFld.value = data.harga;
                        hargaFld.classList.add("listhargaobat");

                        const kuantitasFld = divElm.querySelector("#qty_" + no);
                        kuantitasFld.classList.add("qty_" + obj.kode);
                        kuantitasFld.dataset.idobatnya = obj.kode;
                        kuantitasFld.dataset.stok = data.stok;
                        kuantitasFld.dispatchEvent(new Event("focus"));

                        const classList = kuantitasFld.classList;
                        (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                        hitungTotal();
                    }
                });
            }
        });

        divElm.querySelector(".hapusObatRacikBtn").addEventListener("click", (event) => {
            if (!confirm(str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>)) return;

            const id = event.target.dataset.no;
            divElm.querySelector(".no-racik-" + id).remove();
        });

        divElm.querySelector(".racikanBtn").addEventListener("click", (event) => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const x = event.target.dataset.no;
            const no = divElm.querySelectorAll(".group #tabel_rac").length;
            const idx = x + x + (divElm.querySelectorAll(`#racik_${x} .baris`).length + 1);

            const trStr = drawTr("tbody", {
                class: "baris no-racik-" + idx,
                td_1: {
                    hidden_1: {id: "nm_racikan_1", name: `nm_racikan[${no}]`, value: "Racikan " + x},
                    hidden_2: {name: "no_racikan[]", value: x},
                    hidden_3: {name: `kode_obat-${no}[]`, id: "kode_obat_" + idx},
                    hidden_4: {id: "generik-" + idx},
                    input: {class: ".cariObatFld", id: "obat_" + idx, size: 20, name: `obat-${no}[]`, "data-no": idx},
                    button: {class: ".cekstok", "data-kode": `obat_${no}${no}0`, title: str._<?= $h("Cek Stok") ?>, text: str._<?= $h("Cek") ?>}
                },
                td_2: {
                    hidden: {id: "qty_" + idx, name: `ketjumlah-${x}[]`},
                    input: {class: ".checktotal", name: `qty-${x}[]`, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_3: {
                    input: {name: "satuan[]", id: "satuan_" + idx, readonly: true}
                },
                td_4: {
                    hidden: {name: "harga_beli[]", id: "harga_beli_" + i},
                    input: {name: `hargasatuan-${no}[]`, class: "harga-hide  harga-hide-" + i, id: "harga_" + idx, readonly: true, placeholder: str._<?= $h("Harga") ?>}
                },
                td_5: {
                    button_1: {class: ".hapusObatRacikBtn", id: "hapus", "data-no": idx, text: "-"},
                    button_2: {class: ".racikanBtn", "data-no": no, text: "+"},
                },
            });
            divElm.querySelector("#racik_" + x).insertAdjacentHTML("beforeend", trStr);
            divElm.querySelector(".namaObatFld").dispatchEvent(new Event("focus"));
        });

        divElm.querySelector(".hapusTabelBtn").addEventListener("keypress", (event) => {
            const id = event.target.dataset.no;
            divElm.querySelector("#tabel_" + id).remove();
        });

        divElm.querySelectorAll("input[name^='qty-'], .kuantitasFld").forEach(item => item.addEventListener("keyup", (event) => {
            const kuantitasFld = /** @type {HTMLInputElement} */ event.target;
            const id = kuantitasFld.getAttribute("idobatnya");
            let total = 0;
            divElm.querySelectorAll(".qty_" + id).forEach(/** @type {HTMLInputElement} */item => total += Number(item.value));

            const stok = Number(kuantitasFld.dataset.stok);
            const classList = kuantitasFld.classList;
            (stok >= total || (stok < 0 && total < 0)) ? classList.remove("notenough") : classList.add("notenough");

            if (event.keyCode != 35) return;

            const penjumlah = [];
            const angka = [];
            let j = 0;

            kuantitasFld.value.split("").forEach(val => {
                if (val == "+" || val == "-" || val == "/" || val == "*") {
                    penjumlah[j] = val;
                    j++;
                } else if (angka[j] >= 0) {
                    angka[j] += val;
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

            kuantitasFld.value = angka.pop();
            hitungTotal();
        }));

        divElm.querySelector(".myid_racik").addEventListener("keydown", hitungTotal);

        divElm.querySelector(".showketerangan").addEventListener("click", (event) => {
            const showketeranganElm = /** @type {HTMLInputElement} */ event.target;
            const no = showketeranganElm.dataset.no;
            const keteranganObatElm = divElm.querySelector("#keterangan_obat_" + no);
            if (showketeranganElm.classList.contains("active")) {
                showketeranganElm.classList.remove("active");
                keteranganObatElm.style.display = "none";
                keteranganObatElm.innerHTML = "[ Show ]";
                divElm.querySelector("#cke_keterangan_obat_" + no).style.display = "none";
            } else {
                showketeranganElm.classList.add("active");
                keteranganObatElm.style.display = "block";
                keteranganObatElm.innerHTML = "[ Hide ]";
                divElm.querySelector("#cke_keterangan_obat_" + no).style.display = "block";
                CKEDITOR.replace("keterangan_obat_" + no);
            }
        });

        pembayaranFld.style.display = "block";

        kodeJenisResepFld.addEventListener("change", hitungTotal);

        divElm.querySelector(
            ".checktotal," +
            "input[name^=diskon]," +
            "input[name='qtypembungkus'],"
        ).addEventListener("change", hitungTotal);

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(editEresepDepoWgt, kodeRekamMedisWgt, namaPasienWgt, obatWgt, stokWgt, namaObatWgt, cariObatWgt);
        tlm.app.registerWidget(this.constructor.widgetName, editEresepDepoWgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<form class="editEresepDepoFrm">
    <!-- FORM ELEMENT FROM WIDGET ARE HERE -->

    <!-- BULK INPUT-1 FROM WIDGET ARE HERE -->

    <div class="span8" style="margin-left:160px">
        <div id="add_racikan">
            <?php foreach ($daftarRacik ?? [] as $rc) { ?>
                <?php $z++ ?>
                <?php if ($idRacik != $rc->idRacik) { ?>
                    <tbody id="racik_<?= $r ?>"></tbody>

                    <?php if ($idRacik) { ?>
                        <?php $r++ ?>
                            </table>
                            <button class="btn btn-mini btn-danger hapusTabelBtn" type="button" data-no="<?= $r ?>">x</button>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="group">
                        <div id="tabel_<?= $r ?>" style="padding:5px; background-color:#FFFFFF; margin-bottom:10px; border-bottom:2px solid #F00">
                            Nama Racikan :
                            <input id="nm_racikan_<?= $r ?>" size="20" class="input-large racikanobat" name="nm_racikan[<?= $r ?>]" value="<?= $rc->namaRacik ?>"/>
                            <input type="hidden" name="no_racikan[]" value="<?= $r ?>"/>
                            <input type="hidden" name="kode_signa_racik[<?= $r ?>]" id="kode_signa_<?= $r ?><?= $r ?>0" value="<?= $rc->signa ?>"/>
                            <input name="numero[<?= $r ?>]" placeholder="No" class="input-small" value="<?= $rc->noRacik ?>"/>
                            <input name="diskonracik[<?= $r ?>]" placeholder="Diskon" id="diskonracik_<?= $r ?><?= $r ?>0" class="input-small" value="<?= $rc->diskon ?>"/>
                            <input name="nama_signa_racik[<?= $r ?>]" placeholder="Aturan Pakai" id="nama_signa_<?= $r ?><?= $r ?>0" class="input-large" value="<?= $rc->signa ?>"/>
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
                <?php } ?>

                    <tr>
                        <td>
                            <input id="obat_<?= $r . $r . $j ?>" size="20" class="input-large cariObatFld" name="obat-<?= $r ?>[]" value="<?= $rc->namaBarang ?>" data-no="<?= $r . $r . $j ?>"/>
                            <input type="hidden" name="kode_obat-<?= $r ?>[]" value="<?= $rc->kodeObat ?>" id="kode_obat_<?= $r . $r . $j ?>"/>
                            <input type="hidden" name="kode_obat_awal_racik[<?= $r ?>][]" value="<?= $rc->kodeObat ?>" id="kode_obat_awal_racik_<?= $r . $r . $j ?>"/>
                            <input type="hidden" id="generik-<?= $r . $r . $j ?>"/>
                        </td>
                        <td>
                            <input id="ketjumlah_<?= $r . $r . $j ?>" name="ketjumlah-<?= $r ?>[]" value="<?= $rc->keteranganJumlah ?>" class="input-mini" placeholder="Jumlah"/>
                        </td>
                        <td>
                            <input id="qty_<?= $r . $r . $j ?>" name="qty-<?= $r ?>[]" value="<?= $rc->jumlahPenjualan ?>" class="input-mini checktotal" placeholder="Qty"/>
                        </td>
                        <td>
                            <input name="satuan-<?= $r ?>[]" value="<?= $rc->namaKemasan ?>" id="satuan_<?= $r ?><?= $r . $j ?>" class="input-small" readonly />
                        </td>
                        <td>
                            <input name="hargasatuan-<?= $r ?>[]" value="<?= $rc->harga ?>" id="harga_<?= $r . $r . $j ?>" class="input-small listhargaobat" readonly />
                        </td>
                        <td>
                            <button class="btn btn-mini btn-primary racikanBtn" type="button" data-no="<?= $r ?>"></button>
                        </td>
                    </tr>

                    <?php
                    $idRacik = $rc->idRacik;
                    $j++;
                    ?>
            <?php } ?>

            <?php if ($z > 1) { ?>
                            <tbody id="racik_<?= $r ?>"></tbody>
                        </table>
                        <button class="btn btn-mini btn-danger hapusTabelBtn" type="button" data-no="<?= $r ?>">x</button>
                    </div>
                </div>
            <?php } ?>
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
