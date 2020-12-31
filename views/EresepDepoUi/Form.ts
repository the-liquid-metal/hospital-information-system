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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepo/index.php the original file
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
        string $poliAcplUrl,
        string $dokterAcplUrl,
        string $testBridgeCekKeluarUrl,
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
namespace his.FatmaPharmacy.views.EresepDepoUi.Table {
    export interface FormFields {
        kodeRuangRawat:          string;
        kodePenjualanSebelumnya: "MISSING"; // missing in form
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
        namaRuangRawat:          string;
        noUrut:                  string;
        editResep:               "MISSING", // missing in form
        verifikasi:              "MISSING", // missing in form
        verified:                "MISSING", // missing in form
        idDokter:                "dokterid", // replacement for "dokter"

        kodePoli:                "KD_POLI", // not used in controller
        iter:                    "iter", // not used in controller
        urut:                    "urut", // not used in controller
        diskon:                  "___", // exist but missing, not used in controller
        pembungkus:              "___", // exist but missing, not used in controller
        jasaPelayanan:           "jasapelayanan", // not used in controller
        grandTotal:              "grandtotal", // not used in controller

        daftarObat:              Array<ObatFields>;
        daftarRacik:             Array<RacikFields>;
    }

    export interface ObatFields {
        kodeObat:   string;
        kuantitas:  string;
        idRacik:    string;
        namaSigna1: string;
        namaSigna2: string;
        namaSigna3: string;
        diskonObat: string;
        hargaJual:  string;

        namaObat:   "obat[]", // not used in controller
        satuan:     "satuan[]", // not used in controller
    }

    export interface RacikFields {
        noRacik:             "numero"; // missing in form
        diskonRacik :        "diskonracik"; // missing in form
        kodeSigna:           "kode_signa"; // missing in form
        kodeSignaRacik:      "kode_signa_racik"; // missing in form
        namaRacikan:         "nm_racikan"; // missing in form
        kuantitasPembungkus: "qtypembungkus"; // missing in form
        kodePembungkus:      "kode_pembungkus"; // missing in form
        hargaPembungkus:     "hargapembungkus"; // missing in form
        kodeObatAwal:        "kode_obat_awal"; // missing in form
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

    export interface DokterFields {
        id:   string;
        nama: string;
    }

    export interface PoliFields {
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".eresepDepoFrm",
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
                        hidden: {class: ".tanggalPendaftaranFld", name: "tanggalPendaftaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaPasienFld", name: "namaPasien"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        select: {
                            name: "kelamin",
                            class: ".kelaminFld",
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
                        label: tlm.stringRegistry._<?= $h("Nama Instalasi") ?>,
                        input: {class: ".namaInstalasiFld", name: "namaInstalasi"},
                        hidden: {class: ".kodeInstalasiFld", name: "kodeInstalasi"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Dokter") ?>,
                        select: {class: ".idDokterFld", name: "idDokter"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Pembayaran") ?>,
                        select: {class: ".pembayaranFld", name: "pembayaran"},
                        hidden_1: {class: ".kodePembayaranFld", name: "kodePembayaran"},
                        hidden_2: {class: ".jenisCaraBayarFld", name: "jenisCaraBayar"},
                        hidden_3: {class: ".kodeJenisCaraBayarFld", name: "kodeJenisCaraBayar"}
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
                                hidden: {class: ".kodeObatFld", kodeObat: "kodeObat[]"},
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
                                input: {class: ".satuanFld", name: "satuan[]"}
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
                            td_1: {colspan: 7},
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
        /** @type {HTMLInputElement} */  const tanggalPendaftaranFld = divElm.querySelector(".tanggalPendaftaranFld");
        /** @type {HTMLSelectElement} */ const kelaminFld = divElm.querySelector(".kelaminFld");
        /** @type {HTMLInputElement} */  const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */  const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLSelectElement} */ const kodeJenisResepFld = divElm.querySelector(".kodeJenisResepFld");
        /** @type {HTMLSelectElement} */ const namaInstalasiFld = divElm.querySelector(".namaInstalasiFld");
        /** @type {HTMLInputElement} */  const kodeInstalasiFld = divElm.querySelector(".kodeInstalasiFld");
        /** @type {HTMLSelectElement} */ const pembayaranFld = divElm.querySelector(".pembayaranFld");
        /** @type {HTMLInputElement} */  const kodePembayaranFld = divElm.querySelector(".kodePembayaranFld");
        /** @type {HTMLInputElement} */  const jenisCaraBayarFld = divElm.querySelector(".jenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const kodeJenisCaraBayarFld = divElm.querySelector(".kodeJenisCaraBayarFld");
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

        const eresepDepoWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".eresepDepoFrm"),
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.FormFields} data */
            loadData(data) {
                noResepFld.value = data.noResep ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                tanggalPendaftaranFld.value = data.tanggalPendaftaran ?? "";
                namaPasienWgt.value = data.namaPasien ?? "";
                kelaminFld.value = data.kelamin ?? "";
                tanggalLahirWgt.value = data.tanggalLahir ?? "";
                alamatFld.value = data.alamat ?? "";
                noTelefonFld.value = data.noTelefon ?? "";
                tanggalAwalResepWgt.value = data.tanggalAwalResep ?? "";
                tanggalAkhirResepWgt.value = data.tanggalAkhirResep ?? "";
                kodeJenisResepFld.value = data.kodeJenisResep ?? "";
                namaInstalasiFld.value = data.namaInstalasi ?? "";
                kodeInstalasiFld.value = data.kodeInstalasi ?? "";
                idDokterWgt.value = data.idDokter ?? "";
                pembayaranFld.value = data.pembayaran ?? "";
                kodePembayaranFld.value = data.kodePembayaran ?? "";
                jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
                kodeJenisCaraBayarFld.value = data.kodeJenisCaraBayar ?? "";
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

            obatTbl.querySelectorAll(".obatBiasaFld").forEach(/** @type {HTMLInputElement} */elm => {
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

            obatTbl.querySelectorAll(".diskonObatFld").forEach(/** @type {HTMLInputElement} */elm => {
                const fields = closest(elm, "tr").fields;
                const kuantitas = sysNum(fields.kuantitasWgt.value);
                const harga = sysNum(fields.hargaJualWgt.value);
                const diskon = sysNum(fields.diskonObatFld.value);
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

            // perubahan warna merah klo stok kurang
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
             * @param {his.FatmaPharmacy.views.EresepDepoUi.Table.RekamMedisFields} data
             */
            assignPairs(formElm, data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.nama ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.RekamMedisFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepDepoUi.Table.RekamMedisFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.RegistrasiFields} obj */
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
                        kodePembayaranFld.value = obj.kodeBayar;
                        kodeInstalasiFld.value = obj.kodeInstalasi;
                        kodePoliFld.value = obj.kodePoli;
                        idDokterWgt.dispatchEvent(new Event("focus"));

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
            tlm.targetElements = {kodeRekamMedisFld: kodeRekamMedisWgt, noPendaftaranFld, namaPasienFld: namaPasienWgt};
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
             * @param {his.FatmaPharmacy.views.EresepDepoUi.Table.NamaFields} data
             */
            assignPairs(formElm, data) {
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.NamaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.NamaFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepDepoUi.Table.NamaFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.RegistrasiFields} obj */
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
                        divElm.querySelector("#obat_1").dispatchEvent(new Event("focus"));

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

        /** @see his.FatmaPharmacy.views.EresepDepoUi.Table.PoliFields */
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

        /** @see {his.FatmaPharmacy.views.EresepDepoUi.Table.DokterFields} */
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

        divElm.querySelector(".idDokterFld").addEventListener("focus", () => {
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

        const obatTbl = divElm.querySelector(".obatTbl");
        const obatWgt = new spl.BulkInputWidget({
            element: obatTbl,
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.EresepDepoUi.Table.ObatFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.obatBiasaWgt.value = data.namaObat ?? "";
                fields.kodeObatFld.value = data.kodeObat ?? "";
                fields.kuantitasWgt.value = data.kuantitas ?? "";
                fields.idRacikFld.value = data.idRacik ?? "";
                fields.namaSigna1Wgt.value = data.namaSigna1 ?? "";
                fields.namaSigna2Wgt.value = data.namaSigna2 ?? "";
                fields.namaSigna3Wgt.value = data.namaSigna3 ?? "";
                fields.satuanFld.value = data.satuan ?? "";
                fields.hargaJualWgt.value = data.hargaJual ?? "";
                fields.diskonObatFld.value = data.diskonObat ?? "";
                fields.cekStokBtn.value = data.kodeObat ?? "";
            },
            addRow(trElm) {
                const satuanFld = trElm.querySelector(".satuanFld");
                const kodeObatFld = trElm.querySelector(".kodeObatFld");

                const obatBiasaWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".obatBiasaFld"),
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.EresepDepoUi.Table.ObatAcplFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObatFld.value = data.kode ?? "";
                        satuanFld.value = data.satuanKecil ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.ObatAcplFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<span style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</span>`;
                    },
                    /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.ObatAcplFields} data */
                    itemRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<span style="color:${warna}">${data.namaBarang} (${data.kode})</span>`;
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
                        /** @type {his.FatmaPharmacy.views.EresepDepoUi.Table.ObatAcplFields} */
                        const obj = this.options[value];
                        const jenisResepObat = divElm.querySelector(".jenisresepobat").value;
                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode, jenisResepObat},
                            /** @param {his.FatmaPharmacy.views.EresepDepoUi.Table.HargaFields} data */
                            success(data) {
                                trElm.querySelector(".obatBiasaFld").setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                                const hargaJualFld = trElm.querySelector(".hargaJualFld");
                                hargaJualFld.value = currency(data.harga);
                                hargaJualFld.classList.add("listhargaobat");

                                const kuantitasFld = trElm.querySelector(".kuantitasFld");
                                kuantitasFld.classList.add("qty_" + obj.kode);
                                kuantitasFld.setAttribute("idobatnya", obj.kode);
                                kuantitasFld.dataset.stok = data.stok;
                                kuantitasFld.dispatchEvent(new Event("focus"));

                                const classList = kuantitasFld.classList;
                                (data.stok <= 0) ? classList.add("notenough") : classList.remove("notenough");

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
                    satuanFld,
                    kodeObatFld,
                    obatBiasaWgt,
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
            addRowBtn: ".eresepDepoFrm .addRowBtn",
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

        obatWgt.addDelegateListener("tbody", "click", (event) => {
            const calculateBtn = event.target;
            if (!calculateBtn.matches(".calculateBtn")) return;

            const id = calculateBtn.getAttribute("idobatnya");
            let total = 0;
            divElm.querySelectorAll(".qty_" + id).forEach(/** @type {HTMLInputElement} */item => total += Number(item.value));

            const stok = Number(calculateBtn.dataset.stok);
            const classList = calculateBtn.classList;
            (stok >= total || (stok < 0 && total < 0)) ? classList.remove("notenough") : classList.add("notenough");

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

        obatWgt.addDelegateListener("tbody", "keydown", (event) => {
            if (!event.target.matches(".idRacikFld")) return;
            hitungTotal();
        });

        obatWgt.addDelegateListener("tbody", "change", (event) => {
            if (!event.target.matches(".kuantitasFld, .diskonObatFld, .jenisresepobat, .kodeJenisResepFld")) return;
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.EresepDepoUi.Table.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        /** @see {his.FatmaPharmacy.views.EresepDepoUi.Table.TempatTidurTableFields} */
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

        // JUNK -----------------------------------------------------

        divElm.querySelector(".pembayaranFld").style.display = "block";

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(eresepDepoWgt, kodeRekamMedisWgt, namaPasienWgt, idDokterWgt, namaPoliWgt, obatWgt);
        this._widgets.push(stokWgt , tempatTidurWgt, tanggalLahirWgt, tanggalAwalResepWgt, tanggalAkhirResepWgt);
        tlm.app.registerWidget(this.constructor.widgetName, eresepDepoWgt);
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
