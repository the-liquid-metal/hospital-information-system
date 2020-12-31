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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresep/index.php the original file
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
        string $tempatTidurDataUrl,
        string $poliAcplUrl,
        string $dokterAcplUrl,
        string $namaDokter2Url,
        string $namaAcplUrl,
        string $registrasiAjaxUrl,
        string $nama3Url,
        string $rekamMedisAcplUrl,
        string $noRekamMedis3Url,
        string $obatAcplUrl,
        string $hargaUrl,
        string $signaAcplUrl,
        string $stokDataUrl,
        string $viewAntrianWidgetId,
        string $cariRekamMedisWidgetId,
        string $jenisResepSelect,
        string $caraBayarSelect,
        string $iterSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepUi.Table {
    export interface FormFields {
        id:                      string|"x" |"out"; // missing in form
        kodePenjualanSebelumnya: string|"x" |"out"; // missing in form
        kodeRuangRawat:          string|"in"|"out";
        editResep:               string|"x" |"out"; // missing in form
        noResep:                 string|"in"|"out";
        kodeRekamMedis:          string|"in"|"out";
        noPendaftaran:           string|"in"|"out";
        tanggalPendaftaran:      string|"x" |"out"; // missing in form
        namaPasien:              string|"in"|"out";
        kelamin:                 string|"in"|"out";
        tanggalLahir:            string|"in"|"out";
        alamat:                  string|"in"|"out";
        noTelefon:               string|"in"|"out";
        tanggalAwalResep:        string|"in"|"out";
        tanggalAkhirResep:       string|"in"|"out";
        kodeJenisResep:          string|"in"|"out";
        idDokter:                string|"in"|"out"|"dokterid"; // replacement for "dokter"
        pembayaran:              string|"in"|"out";
        jenisCaraBayar:          string|"in"|"out";
        kodeBayar:               string|"in"|"out";
        kodeJenisCaraBayar:      string|"in"|"out";
        namaInstalasi:           string|"in"|"out";
        kodeInstalasi:           string|"in"|"out";
        namaPoli:                string|"in"|"out";
        kodePoli:                string|"in"|"x";
        verifikasi:              string|"x" |"out"; // missing in form
        verified:                string|"x" |"out"; // missing in form
        jasaPelayanan:           string|"in"|"x";
        grandTotal:              string|"in"|"x";
        urut:                    string|"in"|"x";
        namaRuangRawat:          string|"in"|"out";
        iter:                    string|"in"|"x";
        noUrut:                  string|"in"|"out";

        daftarObat:              Array<ObatFields>;
        daftarRacik:             Array<RacikFields>;
    }

    export interface ObatFields {
        namaObat:     string|"in"|"x";
        kodeObatAwal: string|"x" |"out"; // missing in form
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

    export interface DokterFields {
        id:   string;
        nama: string;
    }

    export interface PoliFields {
        id:   string;
        nama: string;
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
            class: ".eresepFrm",
            hidden_1: {class: ".jenisCaraBayarFld", name: "jenisCaraBayar"},
            hidden_2: {class: ".kodeJenisCaraBayarFld", name: "kodeJenisCaraBayar"},
            row_1: {
                widthColumn: {
                    heading3: {text: tlm.stringRegistry._<?= $h("E-Resep") ?>}
                }
            },
            row_2: {
                box_1: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                        input: {class: ".noResepFld", name: "noResep"},
                        hidden: {class: ".urutFld", name: "urut"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"},
                        rButton: {class: ".cariKodeRekamMedisBtn", icon: "search"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                        input: {class: ".noPendaftaranFld", name: "noPendaftaran"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaPasienFld", name: "namaPasien"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        radio_1: {class: ".kelaminMale",   name: "kelamin", value: "L", label: tlm.stringRegistry._<?= $h("Laki-laki") ?>},
                        radio_2: {class: ".kelaminFemale", name: "kelamin", value: "P", label: tlm.stringRegistry._<?= $h("Perempuan") ?>}
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
                        hidden: {class: ".kodeBayarFld", name: "kodeBayar"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Nama Instansi") ?>,
                        input: {class: ".namaInstansiFld", name: "namaInstalasi"},
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
                        select: {class: ".iterFld", name: "iter"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("No. Urut") ?>,
                        input: {class: ".noUrutFld", name: "noUrut"}
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
            row_3: {
                widthColumn: {text: tlm.stringRegistry._<?= $h("Masukkan Obat: Hitam untuk Formularium Nasional, Merah untuk Formularium RS, Biru untuk LAINNYA") ?>}
            },
            row_4: {
                widthTable: {
                    class: ".obatTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Nama Obat") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Racik") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Signa") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("No. Urut") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Action") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {class: ".noStc"},
                            td_2: {
                                input: {class: ".namaObatFld", name: "namaObat[]"},
                                hidden: {class: ".kodeObatFld", name: "kodeObat[]"},
                                rButton: {class: ".cekStokBtn"}
                            },
                            td_3: {
                                input: {class: ".kuantitasFld", name: "kuantitas[]"}
                            },
                            td_4: {
                                input: {class: ".idRacikFld", name: "idRacik[]"}
                            },
                            td_5: {
                                input_1: {class: ".namaSigna1Fld", name: "namaSigna1[]"},
                                input_2: {class: ".namaSigna2Fld", name: "namaSigna2[]"},
                                input_3: {class: ".namaSigna3Fld", name: "namaSigna3[]"}
                            },
                            td_6: {
                                input: {class: ".namaKemasanFld", name: "namaKemasan[]"},
                                hidden_1: {class: ".hargaJualFld", name: "hargaJual[]"},
                                hidden_2: {class: ".diskonObatFld", name: "diskonObat[]"}
                            },
                            td_7: {
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
            row_5: {
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
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const jenisCaraBayarFld = divElm.querySelector(".jenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const kodeJenisCaraBayarFld = divElm.querySelector(".kodeJenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const noResepFld = divElm.querySelector(".noResepFld");
        /** @type {HTMLInputElement} */  const urutFld = divElm.querySelector(".urutFld");
        /** @type {HTMLInputElement} */  const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLInputElement} */  const kelaminFemale = divElm.querySelector(".kelaminFemale");
        /** @type {HTMLInputElement} */  const kelaminMale = divElm.querySelector(".kelaminMale");
        /** @type {HTMLInputElement} */  const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */  const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLSelectElement} */ const kodeJenisResepFld = divElm.querySelector(".kodeJenisResepFld");
        /** @type {HTMLSelectElement} */ const pembayaranFld = divElm.querySelector(".pembayaranFld");
        /** @type {HTMLInputElement} */  const kodeBayarFld = divElm.querySelector(".kodeBayarFld");
        /** @type {HTMLInputElement} */  const namaInstansiFld = divElm.querySelector(".namaInstansiFld");
        /** @type {HTMLInputElement} */  const kodeInstalasiFld = divElm.querySelector(".kodeInstalasiFld");
        /** @type {HTMLInputElement} */  const kodePoliFld = divElm.querySelector(".kodePoliFld");
        /** @type {HTMLInputElement} */  const namaRuangRawatFld = divElm.querySelector(".namaRuangRawatFld");
        /** @type {HTMLInputElement} */  const kodeRuangRawatFld = divElm.querySelector(".kodeRuangRawatFld");
        /** @type {HTMLSelectElement} */ const iterFld = divElm.querySelector(".iterFld");
        /** @type {HTMLInputElement} */  const noUrutFld = divElm.querySelector(".noUrutFld");
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
        tlm.app.registerSelect("_<?= $iterSelect ?>", iterFld);
        this._selects.push(kodeJenisResepFld, pembayaranFld, iterFld);

        const eresepWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".eresepFrm"),
            /** @param {his.FatmaPharmacy.views.EresepUi.Table.FormFields} data */
            loadData(data) {
                jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
                kodeJenisCaraBayarFld.value = data.kodeJenisCaraBayar ?? "";
                noResepFld.value = data.noResep ?? "";
                urutFld.value = data.urut ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.namaPasien ?? "";
                data.kelamin ? kelaminMale.checked = true : kelaminFemale.checked = true;
                tanggalLahirWgt.value = data.tanggalLahir ?? "";           // $todayValUser
                alamatFld.value = data.alamat ?? "";
                noTelefonFld.value = data.noTelefon ?? "";
                tanggalAwalResepWgt.value = data.tanggalAwalResep ?? "";   // $todayValUser
                tanggalAkhirResepWgt.value = data.tanggalAkhirResep ?? ""; // $todayValUser
                kodeJenisResepFld.value = data.kodeJenisResep ?? "";       // $daftarJenisResep, $idResep
                idDokterWgt.value = data.idDokter ?? "";                   // $idDokter
                pembayaranFld.value = data.pembayaran ?? "";               // $daftarCaraBayar
                kodeBayarFld.value = data.kodeBayar ?? "";
                namaInstansiFld.value = data.namaInstalasi ?? "";
                kodeInstalasiFld.value = data.kodeInstalasi ?? "";
                namaPoliWgt.value = data.namaPoli ?? "";                   // $namaPoli
                kodePoliFld.value = data.kodePoli ?? "";
                namaRuangRawatFld.value = data.namaRuangRawat ?? "";
                kodeRuangRawatFld.value = data.kodeRuangRawat ?? "";
                iterFld.value = data.iter ?? "";
                noUrutFld.value = data.noUrut ?? "";

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

            obatTbl.querySelectorAll(".namaObatFld").forEach(/** @type {HTMLInputElement} */item => {
                if (!item.value) return;

                const fields = closest(item, "tr").fields;
                const idRacik = fields.idRacikFld.value;
                const kuantitas = sysNum(fields.kuantitasWgt.value);

                if (!idRacik && kuantitas) {
                    obat++;
                } else if (!daftarRacikan.includes(idRacik)) {
                    daftarRacikan.push(idRacik);
                    racikan++;
                }
            });

            obatTbl.querySelectorAll(".diskonObatFld").forEach(/** @type {HTMLInputElement} */item => {
                const fields = closest(item, "tr").fields;
                const kuantitas = sysNum(fields.kuantitasWgt.value);
                const harga = sysNum(fields.hargaJualFld.value);
                const diskon = sysNum(item.value);
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

            obatTbl.querySelectorAll(".hargaJualFld").forEach(/** @type {HTMLInputElement} */item => {
                const kuantitasFld = closest(item, "tr").fields.kuantitasWgt;
                const stok = sysNum(kuantitasFld.dataset.stok);
                const kuantitas = sysNum(kuantitasFld.value);
                kuantitasFld.style.color = (stok > kuantitas) ? "black" : "red";
                const harga = sysNum(item.value);
                total += kuantitas * harga;
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

        const tanggalLahirWgt =  new spl.DateTimeWidget({
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

        /** @see his.FatmaPharmacy.views.EresepUi.Table.PoliFields */
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

        /** @see {his.FatmaPharmacy.views.EresepUi.Table.DokterFields} */
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
                url: "<?= $namaDokter2Url ?>",
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
             * @param {his.FatmaPharmacy.views.EresepUi.Table.NamaFields} data
             */
            assignPairs(formElm, data) {
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepUi.Table.NamaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepUi.Table.NamaFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepUi.Table.NamaFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepUi.Table.RegistrasiFields} obj */
                    success(obj) {
                        alamatFld.value = obj.alamat;
                        divElm.querySelector(".kelamin").style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        tanggalLahirWgt.value = userDate(obj.tanggalLahir);
                        noTelefonFld.value = obj.noTelefon;
                        namaInstansiFld.value = obj.namaInstalasi;
                        namaPoliWgt.value = obj.namaPoli;
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

        const kodeRekamMedisWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRekamMedisFld"),
            maxItems: 1,
            valueField: "kodeRekamMedis",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.EresepUi.Table.RekamMedisFields} data
             */
            assignPairs(formElm, data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.nama ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepUi.Table.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepUi.Table.RekamMedisFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepUi.Table.RekamMedisFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepUi.Table.RegistrasiFields} obj */
                    success(obj) {
                        alamatFld.value = obj.alamat;
                        pembayaranFld.value = obj.caraBayar;
                        divElm.querySelector(".kelamin").style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        tanggalLahirWgt.value = userDate(obj.tanggalLahir);
                        noTelefonFld.value = obj.noTelefon;
                        namaInstansiFld.value = obj.namaInstalasi;
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

        divElm.querySelectorAll(".kodeJenisResepFld, .checktotal").forEach(item => item.addEventListener("change", hitungTotal));

        const obatTbl = divElm.querySelector(".obatTbl");
        const itemWgt = new spl.BulkInputWidget({
            element: obatTbl,
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.EresepUi.Table.ObatFields} data
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
                fields.hargaJualFld.value = data.hargaJual ?? "";
                fields.diskonObatFld.value = data.diskonObat ?? "";
                fields.cekStokBtn.value = data.kodeObat ?? "";
            },
            addRow(trElm) {
                /** @type {HTMLInputElement} */ const hargaJualFld = trElm.querySelector(".hargaJualFld");
                /** @type {HTMLInputElement} */ const namaKemasanFld = trElm.querySelector(".namaKemasanFld");
                /** @type {HTMLInputElement} */ const kodeObatFld = trElm.querySelector(".kodeObatFld");

                const namaObatWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaObatFld"),
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.EresepUi.Table.ObatAcplFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObatFld.value = data.kode ?? "";
                        namaKemasanFld.value = data.satuanKecil ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.EresepUi.Table.ObatAcplFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.EresepUi.Table.ObatAcplFields} data */
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
                        /** @type {his.FatmaPharmacy.views.EresepUi.Table.ObatAcplFields} */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode, jenisResepObat: kodeJenisResepFld.value},
                            /** @param {his.FatmaPharmacy.views.EresepUi.Table.HargaFields} data */
                            success(data) {
                                trElm.querySelector(".namaObatFld").setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);
                                hargaJualFld.value = currency(data.harga);

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

                trElm.fields = {
                    hargaJualFld,
                    namaKemasanFld,
                    kodeObatFld,
                    namaObatWgt,
                    namaSigna1Wgt,
                    namaSigna2Wgt,
                    namaSigna3Wgt,
                    kuantitasWgt,
                    namaObatFld: trElm.querySelector(".namaObatFld"),
                    idRacikFld: trElm.querySelector(".idRacikFld"),
                    diskonObatFld: trElm.querySelector(".diskonObatFld"),
                    cekStokBtn: trElm.querySelector(".cekStokBtn"),
                };
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.namaObatWgt.destroy();
                fields.namaSigna1Wgt.destroy();
                fields.namaSigna2Wgt.destroy();
                fields.namaSigna3Wgt.destroy();
                fields.kuantitasWgt.destroy();

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
            addRowBtn: ".eresepFrm .addRowBtn",
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

        itemWgt.addDelegateListener("tbody", "keydown", (event) => {
            if (!event.target.matches(".idRacikFld")) return;
            hitungTotal();
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
            if (!event.target.matches("input[name^=diskon], .kuantitasFld")) return;
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.EresepUi.Table.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        /** @see {his.FatmaPharmacy.views.EresepUi.Table.TempatTidurTableFields} */
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
        this._widgets.push(eresepWgt, tanggalLahirWgt, tanggalAwalResepWgt, tanggalAkhirResepWgt, itemWgt);
        this._widgets.push(namaPasienWgt, idDokterWgt, namaPoliWgt, stokWgt, tempatTidurWgt, kodeRekamMedisWgt);
        tlm.app.registerWidget(this.constructor.widgetName, eresepWgt);
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
