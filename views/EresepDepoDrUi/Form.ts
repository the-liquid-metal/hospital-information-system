<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepDepoDrUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepodr/index.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/rrawat.php the original file (tempatTidurTbl)
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $actionUrl,
        string $rekamMedisAcplUrl,
        string $registrasiAjaxUrl,
        string $cekResepUrl,
        string $tempatTidurDataUrl,
        string $namaAcplUrl,
        string $dokterAcplUrl,
        string $testBridgeCekKeluarUrl,
        string $poliAcplUrl,
        string $obatAcplUrl,
        string $hargaUrl,
        string $signaAcplUrl,
        string $stokDataUrl,
        string $viewStrukWidgetId,
        string $cariRekamMedisWidgetId,
        string $jenisResepSelect,
        string $caraBayarSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepDepoDrUi.Table {
    export interface FormFields {
        noResep:                 string;
        kodeRekamMedis:          string;
        noPendaftaran:           string;
        tanggalDaftar:           string;
        namaPasien:              string;
        kelamin:                 string;
        tanggalLahir:            string;
        alamat:                  string;
        noTelefon:               string;
        tanggalAwalResep:        string;
        tanggalAkhirResep:       string;
        kodeJenisResep:          string;
        idDokter:                "idDokter", // replacement for "dokter"
        kodeBayar:               string;
        jenisCaraBayar:          string;
        kodeJenisCaraBayar:      string;
        namaInstalasi:           string;
        kodeInstalasi:           string;
        namaPoli:                string;
        namaRuangRawat:          string;
        kodeRuangRawat:          string;
        noUrut:                  string;
        pembayaran:              string;
        kodePenjualanSebelumnya: "MISSING"; // missing in form
        editResep:               "MISSING"; // missing in form

        kodePoli:                "KD_POLI", // not used in controller
        iter:                    "iter", // not used in controller
        urut:                    "urut", // not used in controller
        diskon:                  "___", // exist but missing, not used in controller
        pembungkus:              "___", // exist but missing, not used in controller
        jasaPelayanan:           "jasapelayanan", // not used in controller
        grandTotal:              "grandtotal", // not used in controller

        id:                      "MISSING"; // missing in form
        verified:                "MISSING"; // missing in form
        verifikasi:              "MISSING"; // missing in form

        daftarObat:              Array<ObatFields>;
        daftarRacik:             Array<RacikFields>;
    }

    export interface ObatFields {
        kodeObat1:      string;
        kodeObat2:      string;
        kuantitas:      string;
        idRacik:        string;
        namaSigna1:     string;
        namaSigna2:     string;
        namaSigna3:     string;
        diskonObat:     string;
        hargaJual:      string;
        keteranganObat: "MISSING"; // missing in form

        namaObat1:      "obat[]", // not used in controller
        namaObat2:      "obat2[]", // not used in controller
        obatSama:       "chk[]", // not used in controller
        satuan:         "satuan[]", // not used in controller
    }

    export interface RacikFields {
        kodeSigna:           "kode_signa"; // missing in form
        kodeSignaRacik:      "kode_signa_racik"; // missing in form
        kodeObatAwal:        "kode_obat_awal"; // missing in form
        kodePembungkus:      "kode_pembungkus"; // missing in form
        kuantitaspembungkus: "qtypembungkus"; // missing in form
        hargaPembungkus:     "hargapembungkus"; // missing in form
        namaRacik:           "nm_racikan"; // missing in form
        diskonRacik:         "diskonracik"; // missing in form
        noRacik:             "numero"; // missing in form
    }

    export interface SignaFields {
        id:   string;
        nama: string;
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

    export interface PoliFields {
        id:   string;
        nama: string;
    }

    export interface DokterFields {
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

    export interface TempatTidurTableFields {
        tanggalAwal:    string;
        namaRuangRawat: string;
        namaKamar:      string;
        noTempatTidur:  string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Resep Depo Dr") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".form1Frm",
            row_1: {
                box_1: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                        input: {class: ".noResepFld", name: "noResep"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"},
                        rButton: {class: ".cariKodeRekamMedisBtn", icon: "search"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                        input: {class: ".noPendaftaranFld", name: "noPendaftaran"},
                        hidden: {class: ".tanggalDaftarFld", name: "tanggalDaftar"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaPasienFld", name: "namaPasien"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        select: {
                            class: ".kelaminFld",
                            name: "kelamin",
                            option_1: {value: "P", label: tlm.stringRegistry._<?= $h("Perempuan") ?>},
                            option_2: {value: "L", label: tlm.stringRegistry._<?= $h("Laki-laki") ?>}
                        }
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
                        select: {class: ".idDokterFld", name: "idDokter"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Pembayaran") ?>,
                        select: {class: ".pembayaranFld", name: "pembayaran"},
                        hidden_1: {class: ".kodeBayarFld", name: "kodeBayar"},
                        hidden_2: {class: ".jenisCaraBayarFld", name: "jenisCaraBayar"},
                        hidden_3: {class: ".kodeJenisCaraBayarFld", name: "kodeJenisCaraBayar"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Nama Instalasi") ?>,
                        input: {class: ".namaInstalasiFld", name: "namaInstalasi"},
                        hidden: {class: ".kodeInstalasiFld", name: "kodeInstalasi"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Nama Poli") ?>,
                        input: {class: ".namaPoliFld", name: "namaPoli"},
                        hidden: {class: ".kodePoliFld", name: "kodePoli"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>,
                        input: {class: ".namaRuangRawatFld", name: "namaRuangRawat"},
                        hidden: {class: ".kodeRuangRawatFld", name: "kodeRuangRawat"},
                        rButton: {class: ".cariRuangRawatBtn", icon: "search"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Iter") ?>,
                        select: {
                            class: ".iterFld",
                            name: "iter",
                            option_1: {value: "0", label: "0"},
                            option_2: {value: "1", label: "1"},
                            option_3: {value: "2", label: "2"},
                            option_4: {value: "3", label: "3"},
                            option_5: {value: "4", label: "4"},
                            option_6: {value: "5", label: "5"},
                            option_7: {value: "6", label: "6"},
                            option_8: {value: "7", label: "7"},
                            option_9: {value: "8", label: "8"},
                            option_10: {value: "9", label: "9"},
                            option_11: {value: "10", label: "10"}
                        }
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("No. Urut") ?>,
                        input: {class: ".noUrutFld", name: "noUrut"},
                        hidden: {class: ".urutFld", name: "urut"}
                    }
                },
                box_2: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
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
                widthColumn: {text: tlm.stringRegistry._<?= $h("Masukkan Obat: Hitam untuk Formularium Nasional, Merah untuk Formularium RS, Biru untuk LAINNYA") ?>}
            },
            row_3: {
                widthTable: {
                    class: ".obatTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Resep Dokter") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Obat Pengganti") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Racikan") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Signa") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_9: {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_10: {text: tlm.stringRegistry._<?= $h("Action") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {class: ".noStc"},
                            td_2: {
                                input: {class: ".namaObat1Fld", name: "namaObat1[]"},
                                hidden: {class: ".kodeObat1Fld", name: "kodeObat1[]"},
                                checkbox: {class: ".obatSamaFld", name: "obatSama[]"},
                                rButton: {class: ".cekStok1Btn"}
                            },
                            td_3: {
                                input: {class: ".namaObat2Fld", name: "namaObat2[]"},
                                hidden: {class: ".kodeObat2Fld", name: "kodeObat2[]"},
                                rButton: {class: ".cekStok2Btn"}
                            },
                            td_4: {
                                input: {class: ".kuantitasFld", name: "kuantitas[]"}
                            },
                            td_5: {
                                input: {class: ".idRacikFld", name: "idRacik[]"}
                            },
                            td_6: {
                                input_1: {class: ".namaSigna1Fld", name: "namaSigna1[]"},
                                input_2: {class: ".namaSigna2Fld", name: "namaSigna2[]"},
                                input_3: {class: ".namaSigna3Fld", name: "namaSigna3[]"}
                            },
                            td_7: {
                                input: {class: ".satuanFld", name: "satuan[]"}
                            },
                            td_8: {
                                input: {class: ".hargaJualFld", name: "hargaJual[]"}
                            },
                            td_9: {
                                input: {class: ".diskonObatFld", name: "diskonObat[]"}
                            },
                            td_10: {
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
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        },
        modal_1: {
            title: tlm.stringRegistry._<?= $h("Stok Tersedia") ?>,
            row_1: {
                widthColumn: {class: ".headerElm"}
            },
            row_2: {
                class: ".stokTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Depo") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                    }
                }
            },
            row_3: {
                widthColumn: {class: ".footerElm"}
            },
        },
        modal_2: {
            title: tlm.stringRegistry._<?= $h("Pilihan Data Perawatan") ?>,
            row: {
                widthTable: {
                    class: ".tempatTidurTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Tanggal Masuk") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Ruang") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Kamar") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Tempat Tidur") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        }
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {toSystemNumber: sysNum, toCurrency: currency, toUserDate: userDate, stringRegistry: str} = tlm;
        const idDepo = tlm.user.idDepo;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const noResepFld = divElm.querySelector(".noResepFld");
        /** @type {HTMLInputElement} */  const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLInputElement} */  const tanggalDaftarFld = divElm.querySelector(".tanggalDaftarFld");
        /** @type {HTMLSelectElement} */ const kelaminFld = divElm.querySelector(".kelaminFld");
        /** @type {HTMLInputElement} */  const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */  const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLSelectElement} */ const kodeJenisResepFld = divElm.querySelector(".kodeJenisResepFld");
        /** @type {HTMLSelectElement} */ const pembayaranFld = divElm.querySelector(".pembayaranFld");
        /** @type {HTMLInputElement} */  const kodeBayarFld = divElm.querySelector(".kodeBayarFld");
        /** @type {HTMLInputElement} */  const jenisCaraBayarFld = divElm.querySelector(".jenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const kodeJenisCaraBayarFld = divElm.querySelector(".kodeJenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const namaInstalasiFld = divElm.querySelector(".namaInstalasiFld");
        /** @type {HTMLInputElement} */  const kodeInstalasiFld = divElm.querySelector(".kodeInstalasiFld");
        /** @type {HTMLInputElement} */  const kodePoliFld = divElm.querySelector(".kodePoliFld");
        /** @type {HTMLInputElement} */  const namaRuangRawatFld = divElm.querySelector(".namaRuangRawatFld");
        /** @type {HTMLInputElement} */  const kodeRuangRawatFld = divElm.querySelector(".kodeRuangRawatFld");
        /** @type {HTMLSelectElement} */ const iterFld = divElm.querySelector(".iterFld");
        /** @type {HTMLInputElement} */  const noUrutFld = divElm.querySelector(".noUrutFld");
        /** @type {HTMLInputElement} */  const urutFld = divElm.querySelector(".urutFld");
        /** @type {HTMLDivElement} */    const diskonStc = divElm.querySelector(".diskonStc");
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

        const form1Wgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".form1Frm"),
            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.FormFields} data */
            loadData(data) {
                noResepFld.value = data.noResep ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                tanggalDaftarFld.value = data.tanggalDaftar ?? "";
                namaPasienWgt.value = data.namaPasien ?? "";
                kelaminFld.value = data.kelamin ?? "";
                tanggalLahirWgt.value = data.tanggalLahir ?? "";
                alamatFld.value = data.alamat ?? "";
                noTelefonFld.value = data.noTelefon ?? "";
                tanggalAwalResepWgt.value = data.tanggalAwalResep ?? "";
                tanggalAkhirResepWgt.value = data.tanggalAkhirResep ?? "";
                kodeJenisResepFld.value = data.kodeJenisResep ?? "";
                idDokterWgt.value = data.idDokter ?? "";
                pembayaranFld.value = data.pembayaran ?? "";
                kodeBayarFld.value = data.kodeBayar ?? "";
                jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
                kodeJenisCaraBayarFld.value = data.kodeJenisCaraBayar ?? "";
                namaInstalasiFld.value = data.namaInstalasi ?? "";
                kodeInstalasiFld.value = data.kodeInstalasi ?? "";
                namaPoliWgt.value = data.namaPoli ?? "";
                kodePoliFld.value = data.kodePoli ?? "";
                namaRuangRawatFld.value = data.namaRuangRawat ?? "";
                kodeRuangRawatFld.value = data.kodeRuangRawat ?? "";
                iterFld.value = data.iter ?? "";
                noUrutFld.value = data.noUrut ?? "";
                urutFld.value = data.urut ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                pembungkusStc.innerHTML = data.pembungkus ?? "";
                jasaPelayananStc.innerHTML = data.jasaPelayanan ?? "";
                jasaPelayananFld.value = data.jasaPelayanan ?? "";
                grandTotalStc.innerHTML = data.grandTotal ?? "";
                grandTotalFld.value = data.grandTotal ?? "";

                obatWgt.loadData(data.daftarObat);
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
                widget.load({noResep: event.data.noResep});
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

            obatTbl.querySelectorAll(".namaObat2Fld").forEach(/**@type {HTMLInputElement} */elm => {
                if (!elm.value) return;

                const fields = closest(elm, "tr").fields;
                const idRacikan = fields.idRacikFld.value;
                const kuantitas = sysNum(fields.kuantitasWgt.value);

                if (!idRacikan && kuantitas) {
                    obat++;
                } else if (!daftarRacikan.includes(idRacikan)) {
                    daftarRacikan.push(idRacikan);
                    racikan++;
                }
            });

            obatTbl.querySelectorAll(".diskonObatFld").each(/**@type {HTMLInputElement} */elm => {
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

            obatTbl.querySelectorAll(".listhargaobat").forEach(() => {
                const kuantitasFld = divElm.querySelector(".kuantitasFld");
                const idObat = kuantitasFld.getAttribute("idobatnya");

                let total2 = 0;
                divElm.querySelectorAll(`.kuantitasFld[idobatnya='${idObat}']`).forEach(/** @type {HTMLInputElement} */item => total2 += sysNum(item.value));

                const stok = sysNum(kuantitasFld.dataset.stok);
                const classList = kuantitasFld.classList;
                const isEnough = (stok >= total2 || (stok < 0 && total2 < 0));
                isEnough ? classList.remove("notenough") : classList.add("notenough");
                kuantitasFld.style.color = isEnough ? "black" : "red";

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
            const jasaObat = 500;
            const jasaRacik = 1000;

            const diskon = diskonRacik + diskonObat;
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

            const totalTanpaJasa = total + pembungkus - diskon;
            const totalAwal = jasaPelayanan + total + pembungkus - diskon;
            const totalAkhir = Math.ceil(totalAwal / 100) * 100;
            const totalJasa = totalAkhir - totalTanpaJasa;

            // TODO: js: uncategorized: confirm to controller, are these necessary. if not, change them to static.
            jasaPelayananFld.value = currency(totalJasa);
            grandTotalFld.value = currency(totalAkhir);

            diskonStc.innerHTML = currency(diskon);
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
             * @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.RekamMedisFields} data
             */
            assignPairs(formElm, data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.nama ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.RekamMedisFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepDepoDrUi.Table.RekamMedisFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.RegistrasiFields} obj */
                    success(obj) {
                        alamatFld.value = obj.alamat;
                        pembayaranFld.value = obj.caraBayar;
                        kelaminFld.style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        tanggalLahirWgt.value = userDate(obj.tanggalLahir);
                        noTelefonFld.value = obj.noTelefon;
                        namaInstalasiFld.value = obj.namaInstalasi;
                        namaRuangRawatFld.value = obj.namaKamar || "";
                        namaPoliWgt.value = obj.namaPoli;
                        divElm.querySelector(".detailuser").readonly = true;
                        jenisCaraBayarFld.value = obj.jenisCaraBayar;
                        kodeJenisCaraBayarFld.value = obj.kodeJenisCaraBayar;
                        kodeRuangRawatFld.value = obj.kodeRuangRawat;
                        kodeBayarFld.value = obj.kodeBayar;
                        kodeInstalasiFld.value = obj.kodeInstalasi;
                        kodePoliFld.value = obj.kodePoli;

                        $.post({
                            url: "<?= $cekResepUrl ?>",
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

        divElm.querySelector(".cariKodeRekamMedisBtn").addEventListener("click", () => {
            tlm.targetElements = {kodeRekamMedisFld: kodeRekamMedisWgt};
            tlm.app.getWidget("_<?= $cariRekamMedisWidgetId ?>").show();
        });

        divElm.querySelector(".cariRuangRawatBtn").addEventListener("click", () => {
            tempatTidurWgt.refresh({
                query: {noPendaftaran: noPendaftaranFld.value}
            });
            tempatTidurWgt.show();
        });

        const namaPasienWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaPasienFld"),
            maxItems: 1,
            valueField: "nama",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.NamaFields} data
             */
            assignPairs(formElm, data) {
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.NamaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.NamaFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepDepoDrUi.Table.NamaFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.RegistrasiFields} obj */
                    success(obj) {
                        alamatFld.value = obj.alamat;
                        kelaminFld.style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        tanggalLahirWgt.value = userDate(obj.tanggalLahir);
                        noTelefonFld.value = obj.noTelefon;
                        namaInstalasiFld.value = obj.namaInstalasi;
                        namaPoliWgt.value = obj.namaPoli;
                        divElm.querySelector(".detailuser").readonly = true;

                        $.post({
                            url: "<?= $cekResepUrl ?>",
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

        const tanggalLahirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalLahirFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalAwalResepWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAwalResepFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const tanggalAkhirResepWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAkhirResepFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        /** @see {his.FatmaPharmacy.views.EresepDepoDrUi.Table.DokterFields} */
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
                    url: "<?= $dokterAcplUrl ?>",
                    data: {nama: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        idDokterWgt.addEventListener("focus", () => {
            if (tlm.user.kodeInstalasiDepo == "01") return;
            $.post({
                url: "<?= $testBridgeCekKeluarUrl ?>",
                data: {q1: noPendaftaranFld.value},
                /** @param {string} data */
                success(data) {
                    if (data == "1") {
                        divElm.querySelector(".buttonsubmit").disabled = true;
                        alert(str._<?= $h("Pasien Telah Keluar. Silahkan hubungi billing.") ?>);
                    } else {
                        divElm.querySelector(".buttonsubmit").disabled = false;
                    }
                }
            });
        });

        /** @see his.FatmaPharmacy.views.EresepDepoDrUi.Table.PoliFields */
        const namaPoliWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaPoliFld"),
            maxItems: 1,
            valueField: "nama",
            labelField: "nama",
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $poliAcplUrl ?>",
                    data: {val: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const obatTbl = divElm.querySelector(".obatTbl");
        const obatWgt = new spl.BulkInputWidget({
            element: obatTbl,
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.namaObat1Wgt.value = data.namaObat1 ?? "";
                fields.kodeObat1Fld.value = data.kodeObat1 ?? "";
                fields.obatSamaFld.value = data.obatSama ?? "";
                fields.namaObat2Wgt.value = data.namaObat2 ?? "";
                fields.kodeObat2Fld.value = data.kodeObat2 ?? "";
                fields.kuantitasWgt.value = data.kuantitas ?? "";
                fields.idRacikFld.value = data.idRacik ?? "";
                fields.namaSigna1Wgt.value = data.namaSigna1 ?? "";
                fields.namaSigna2Wgt.value = data.namaSigna2 ?? "";
                fields.namaSigna3Wgt.value = data.namaSigna3 ?? "";
                fields.satuanFld.value = data.satuan ?? "";
                fields.hargaJualWgt.value = data.hargaJual ?? "";
                fields.diskonObatFld.value = data.diskonObat ?? "";
                fields.cekStok1Btn.value = data.kodeObat1 ?? "";
                fields.cekStok2Btn.value = data.kodeObat2 ?? "";
            },
            addRow(trElm) {
                /** @type {HTMLInputElement} */ const satuanFld = trElm.querySelector(".satuanFld");
                /** @type {HTMLInputElement} */ const kodeObat2Fld = trElm.querySelector(".kodeObat2Fld");
                /** @type {HTMLInputElement} */ const kodeObat1Fld = trElm.querySelector(".kodeObat1Fld");

                const namaObat1Wgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaObat1Fld"),
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatAcplFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObat1Fld.value = data.kode ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatAcplFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatAcplFields} data */
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
                        /** @type {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatAcplFields} */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode, jenis: kodeJenisResepFld.value},
                            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.HargaFields} data */
                            success(data) {
                                const hargaJualFld = divElm.querySelector(".hargaJualFld");
                                hargaJualFld.value = currency(data.harga);
                                hargaJualFld.classList.add("listhargaobat");

                                divElm.querySelector(".obatSamaFld").dispatchEvent(new Event("focus"));
                                divElm.querySelector(".kuantitasFld").readonly = true;

                                hitungTotal();
                            }
                        });
                    }
                });

                const namaObat2Wgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaObat2Fld"),
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatAcplFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObat2Fld.value = data.kode ?? "";
                        satuanFld.value = data.satuanKecil ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatAcplFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatAcplFields} data */
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
                        /** @type {his.FatmaPharmacy.views.EresepDepoDrUi.Table.ObatAcplFields} */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode, jenis: kodeJenisResepFld.value},
                            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.HargaFields} data */
                            success(data) {
                                divElm.querySelector(".namaObat2Fld").setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                                const hargaJualFld = divElm.querySelector(".hargaJualFld");
                                hargaJualFld.value = currency(data.harga);
                                hargaJualFld.classList.add("listhargaobat");

                                const kuantitasFld = divElm.querySelector(".kuantitasFld");
                                kuantitasFld.classList.add("qty_" + obj.kode);
                                kuantitasFld.dataset.idobatnya = obj.kode;
                                kuantitasFld.dataset.stok = data.stok;
                                kuantitasFld.readonly = false;

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
                    satuanFld,
                    kodeObat2Fld,
                    namaObat1Wgt,
                    namaObat2Wgt,
                    namaSigna1Wgt,
                    namaSigna2Wgt,
                    namaSigna3Wgt,
                    kuantitasWgt,
                    hargaJualWgt,
                    kodeObat1Fld: trElm.querySelector(".kodeObat1Fld"),
                    obatSamaFld: trElm.querySelector(".obatSamaFld"),
                    idRacikFld: trElm.querySelector(".idRacikFld"),
                    diskonObatFld: trElm.querySelector(".diskonObatFld"),
                    cekStok1Btn: trElm.querySelector(".cekStok1Btn"),
                    cekStok2Btn: trElm.querySelector(".cekStok2Btn"),
                }
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.namaObat1Wgt.destroy();
                fields.namaObat2Wgt.destroy();
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
            addRowBtn: ".form1Frm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        obatWgt.addDelegateListener("tbody", "click", (event) => {
            const cekStokBtn = event.target;
            if (!cekStokBtn.matches(".cekStok1Btn, .cekStok2Btn")) return;

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

        obatTbl.querySelector(".obatSamaFld").addEventListener("change", (event) => {
            const id = event.target.dataset.no;
            const namaObat1Fld = divElm.querySelector(".namaObat1Fld");
            const kodeObat1 = divElm.querySelector(".kodeObat1Fld").value;
            const sinonim = namaObat1Fld.getAttribute("title");
            const kuantitasFld = divElm.querySelector(".kuantitasFld");

            if (divElm.querySelector(".obatSamaFld").checked) {
                divElm.querySelector(".kodeObat2Fld").value = kodeObat1;

                const namaObat2Fld = divElm.querySelector(".namaObat2Fld");
                namaObat2Fld.value = namaObat1Fld.value;
                namaObat2Fld.readonly = true;
                namaObat2Fld.setAttribute("title", sinonim);

                divElm.querySelector(".satuanFld").value = divElm.querySelector("#satuan_dr_" + id).value;

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kodeObat: kodeObat1, jenisResepObat: kodeJenisResepFld.value},
                    /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.Table.HargaFields} data */
                    success(data) {
                        divElm.querySelector(".namaObat2Fld").setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${sinonim}`);

                        const hargaJualFld = divElm.querySelector(".hargaJualFld");
                        hargaJualFld.value = currency(data.harga);
                        hargaJualFld.classList.add("listhargaobat");

                        kuantitasFld.classList.add(".kuantitasFld");
                        kuantitasFld.dataset.idobatnya = kodeObat1;
                        kuantitasFld.dataset.stok = data.stok;
                        kuantitasFld.readonly = false;

                        const classList = kuantitasFld.classList;
                        (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                        hitungTotal();
                    }
                });
            } else {
                kuantitasFld.classList.remove("qty_" + kodeObat1);
                kuantitasFld.removeAttribute("idobatnya");
                kuantitasFld.value = "";
                kuantitasFld.readonly = true;

                const obat2Fld = divElm.querySelector("#obat2_" + id);
                obat2Fld.value = "";
                obat2Fld.readonly = false;
                obat2Fld.setAttribute("title", "");

                divElm.querySelector(".kodeObat2Fld").value = "";
            }
        });

        obatWgt.addDelegateListener("tbody", "click", (event) => {
            const calculateBtn = event.target;
            if (!calculateBtn.matches(".calculateBtn")) return;

            const stok = Number(calculateBtn.dataset.stok);
            const kuantitas = Number(calculateBtn.value);
            const classList = calculateBtn.classList;
            (stok >= kuantitas) ? classList.remove("notenough") : classList.add("notenough");

            const penjumlah = [];
            const angka = [];
            let j = 0;

            calculateBtn.value.split("").forEach(val => {
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

            calculateBtn.value = angka.pop();
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.EresepDepoDrUi.Table.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        divElm.querySelectorAll(".checktotal, .jenisResepFld, .idRacikFld").forEach(item => item.addEventListener("change", hitungTotal));

        /** @see {his.FatmaPharmacy.views.EresepDepoDrUi.Table.TempatTidurTableFields} */
        const tempatTidurWgt = new spl.TableWidget({
            element: divElm.querySelector(".tempatTidurTbl"),
            url: "<?= $tempatTidurDataUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "tanggalAwal", formatter: tlm.dateFormatter},
                3: {field: "namaRuangRawat"},
                4: {field: "namaKamar"},
                5: {field: "noTempatTidur"},
                6: {formatter(unused, {namaRuangRawat}) {
                    return draw({class: ".pilihBtn", type: "success", value: namaRuangRawat, text: str._<?= $h("Pilih") ?>});
                }}
            }
        });

        tempatTidurWgt.addDelegateListener("tbody", "click", (event) => {
            const pilihBtn = event.target;
            if (!pilihBtn.matches(".pilihBtn")) return;

            namaRuangRawatFld.value = pilihBtn.value;
            tempatTidurWgt.hide();
            divElm.querySelector(".dokter").dispatchEvent(new Event("focus"));
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(form1Wgt, kodeRekamMedisWgt, namaPasienWgt, idDokterWgt, namaPoliWgt, obatWgt, stokWgt);
        this._widgets.push(tanggalLahirWgt, tanggalAwalResepWgt, tanggalAkhirResepWgt, tempatTidurWgt);
        tlm.app.registerWidget(this.constructor.widgetName, form1Wgt);
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
