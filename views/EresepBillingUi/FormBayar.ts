<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\EresepBillingUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepbilling/editbayar.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class FormBayar
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $dataUrl,
        string $actionUrl,
        string $rekamMedisAcplUrl,
        string $noRekamMedis3Url,
        string $pembayaranUrl,
        string $namaAcplUrl,
        string $registrasiAjaxUrl,
        string $nama3Url,
        string $obatAcplUrl,
        string $hargaUrl,
        string $signaAcplUrl,
        string $stokDataUrl,
        string $viewStrukWidgetId,
        string $jenisResepSelect,
        string $caraBayarSelect,
        string $pembayaranSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepBillingUi.EditBayar {
    export interface FormFields {
        kodePenjualanSebelumnya: string;
        editResep:               "edit_resep"; // sama dg kodePenjualanSebelumnya
        jenisCaraBayar:          "JNS_CARABAYAR"; // missing in controller
        kodeBayar:               string;
        kodeInstalasi:           string;
        kodePoli:                string;
        kodeJenisCaraBayar:      string;
        kodeRuangRawat:          string;
        noAntrian:               string;
        noResep:                 string;
        kodeRekamMedis:          string;
        noPendaftaran:           string;
        namaPasien:              string;
        kelamin:                 string;
        tanggalLahir:            string;
        alamat:                  string;
        noTelefon:               string;
        tanggalAwalResep:        string;
        tanggalAkhirResep:       string;
        jenisResep:              string;
        dokter:                  string;
        caraBayar:               string;
        namaInstalasi:           string;
        pembayaran:              string;
        detailPembayaran:        string;
        noKartu:                 string;
        namaPoli:                string;
        totalDiskon:             string;
        pembungkus:              "pembungkus"; // missing in controller
        jasaPelayanan:           string;
        grandTotal:              string;
        bayar:                   "bayar";
        kembali:                 "kembali";
        atasNama:                "atasnama";

        daftarObat:              Array<ObatFields>;
    }

    export interface ObatFields {
        namaObat:    string;
        kodeObat:    string;
        kuantitas:   string;
        idRacik:     string;
        namaSigna1:  string;
        namaSigna2:  string;
        namaSigna3:  string;
        namaKemasan: string;
        hargaJual:   string;
        diskonObat:  "diskonobat[]"; // missing in controller
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
        kodeJenisCaraBayar: string;
        kodeRuangRawat:     string;
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
            class: ".editBayarFrm",
            row_1: {
                box_1: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden: {name: "submit", value: "save"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                        hidden_1: {class: ".kodePenjualanSebelumnyaFld", name: "kodePenjualanSebelumnya"},
                        hidden_2: {class: ".editResepFld", name: "editResep"},
                        hidden_3: {class: ".jenisCaraBayarFld", name: "jenisCaraBayar"},
                        hidden_4: {class: ".kodeBayarFld", name: "kodeBayar"},
                        hidden_5: {class: ".kodeInstansiFld", name: "kodeInstalasi"},
                        hidden_6: {class: ".kodePoliFld", name: "kodePoli"},
                        hidden_7: {class: ".kodeJenisCaraBayarFld", name: "kodeJenisCaraBayar"},
                        hidden_8: {id: "#kodeRuangRawatFld", name: "kodeRuangRawat"},
                        hidden_9: {class: ".noAntrianFld", name: "noAntrian"},
                        input: {class: ".noResepFld", name: "noResep"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"}
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Resep Awal") ?>,
                        input: {class: ".tanggalAwalResepFld", name: "tanggalAwalResep"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Resep Akhir") ?>,
                        input: {class: ".tanggalAkhirResepFld", name: "tanggalAkhirResep"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Jenis Resep") ?>,
                        select: {class: ".jenisResepFld", name: "jenisResep"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Dokter") ?>,
                        input: {class: ".dokterFld", name: "dokter"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".caraBayarFld", name: "caraBayar"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Nama Instansi") ?>,
                        input: {class: ".namaInstansiFld", name: "namaInstalasi"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Pembayaran") ?>,
                        select: {class: ".pembayaranFld", name: "pembayaran"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Detail Pembayaran") ?>,
                        select: {class: ".detailPembayaranFld", name: "detailPembayaran", options: []}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("No. Kartu") ?>,
                        input: {class: ".noKartuFld", name: "noKartu"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Nama Poli") ?>,
                        input: {class: ".namaPoliFld", name: "namaPoli"}
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
                        hidden: {class: ".grandTotalFld", name: "grandtotal"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Bayar") ?>,
                        input: {class: ".bayarFld", name: "bayar"},
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Kembali") ?>,
                        input: {class: ".kembaliFld", name: "kembali", readonly: true},
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Atas Nama") ?>,
                        input: {class: ".atasNamaFld", name: "atasNama"},
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".obatTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Nama Obat") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Signa") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Action") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                input: {class: ".obatBiasaFld", name: "namaObat[]"},
                                hidden: {class: ".kodeObatFld", name: "kodeObat[]"},
                                rButton: {class: ".cekStokBtn"}
                            },
                            td_2: {
                                input: {class: ".kuantitasFld", name: "kuantitas[]"}
                            },
                            td_3: {
                                input: {class: ".idRacikFld", name: "idRacik[]"},
                                hidden_1: {class: ".namaSigna1Fld", name: "namaSigna1[]"},
                                hidden_2: {class: ".namaSigna2Fld", name: "namaSigna2[]"},
                                hidden_3: {class: ".namaSigna3Fld", name: "namaSigna3[]"}
                            },
                            td_4: {
                                input: {class: ".namaKemasanFld", name: "namaKemasan[]"},
                                hidden_1: {class: ".hargaJualFld .listhargaobat", name: "hargaJual[]"},
                                hidden_2: {class: ".diskonObatFld", name: "diskonObat[]"}
                            },
                            td_5: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {colspan: 4},
                            td_2: {
                                button: {class: ".addRowBtn", type: "success", size: "xs", label: tlm.stringRegistry._<?= $h("add") ?>}
                            }
                        }
                    }
                }
            },
            row_3: {
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

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodePenjualanSebelumnyaFld = divElm.querySelector(".kodePenjualanSebelumnyaFld");
        /** @type {HTMLInputElement} */  const editResepFld = divElm.querySelector(".editResepFld");
        /** @type {HTMLInputElement} */  const jenisCaraBayarFld = divElm.querySelector(".jenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const kodeBayarFld = divElm.querySelector(".kodeBayarFld");
        /** @type {HTMLInputElement} */  const kodeInstansiFld = divElm.querySelector(".kodeInstansiFld");
        /** @type {HTMLInputElement} */  const kodePoliFld = divElm.querySelector(".kodePoliFld");
        /** @type {HTMLInputElement} */  const kodeJenisCaraBayarFld = divElm.querySelector(".kodeJenisCaraBayarFld");
        /** @type {HTMLInputElement} */  const kodeRuangRawatFld = divElm.querySelector(".kodeRuangRawatFld");
        /** @type {HTMLInputElement} */  const noAntrianFld = divElm.querySelector(".noAntrianFld");
        /** @type {HTMLInputElement} */  const noResepFld = divElm.querySelector(".noResepFld");
        /** @type {HTMLInputElement} */  const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLInputElement} */  const kelaminFld = divElm.querySelector(".kelaminFld");
        /** @type {HTMLInputElement} */  const tanggalLahirFld = divElm.querySelector(".tanggalLahirFld");
        /** @type {HTMLInputElement} */  const alamatFld = divElm.querySelector(".alamatFld");
        /** @type {HTMLInputElement} */  const noTelefonFld = divElm.querySelector(".noTelefonFld");
        /** @type {HTMLInputElement} */  const tanggalAwalResepFld = divElm.querySelector(".tanggalAwalResepFld");
        /** @type {HTMLInputElement} */  const tanggalAkhirResepFld = divElm.querySelector(".tanggalAkhirResepFld");
        /** @type {HTMLSelectElement} */ const jenisResepFld = divElm.querySelector(".jenisResepFld");
        /** @type {HTMLInputElement} */  const dokterFld = divElm.querySelector(".dokterFld");
        /** @type {HTMLSelectElement} */ const caraBayarFld = divElm.querySelector(".caraBayarFld");
        /** @type {HTMLInputElement} */  const namaInstansiFld = divElm.querySelector(".namaInstansiFld");
        /** @type {HTMLSelectElement} */ const pembayaranFld = divElm.querySelector(".pembayaranFld");
        /** @type {HTMLSelectElement} */ const detailPembayaranFld = divElm.querySelector(".detailPembayaranFld");
        /** @type {HTMLInputElement} */  const noKartuFld = divElm.querySelector(".noKartuFld");
        /** @type {HTMLInputElement} */  const namaPoliFld = divElm.querySelector(".namaPoliFld");
        /** @type {HTMLDivElement} */    const totalDiskonStc = divElm.querySelector(".totalDiskonStc");
        /** @type {HTMLDivElement} */    const pembungkusStc = divElm.querySelector(".pembungkusStc");
        /** @type {HTMLDivElement} */    const jasaPelayananStc = divElm.querySelector(".jasaPelayananStc");
        /** @type {HTMLInputElement} */  const jasaPelayananFld = divElm.querySelector(".jasaPelayananFld");
        /** @type {HTMLDivElement} */    const grandTotalStc = divElm.querySelector(".grandTotalStc");
        /** @type {HTMLInputElement} */  const grandTotalFld = divElm.querySelector(".grandTotalFld");
        /** @type {HTMLInputElement} */  const bayarFld = divElm.querySelector(".bayarFld");
        /** @type {HTMLInputElement} */  const kembaliFld = divElm.querySelector(".kembaliFld");
        /** @type {HTMLInputElement} */  const atasNamaFld = divElm.querySelector(".atasNamaFld");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $jenisResepSelect ?>", jenisResepFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", caraBayarFld);
        tlm.app.registerSelect("_<?= $pembayaranSelect ?>", pembayaranFld);
        this._selects.push(jenisResepFld, caraBayarFld, pembayaranFld);

        const editBayarWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".editBayarFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.FormFields} data */
            loadData(data) {
                kodePenjualanSebelumnyaFld.value = data.kodePenjualanSebelumnya ?? "";
                editResepFld.value = data.editResep ?? "";
                jenisCaraBayarFld.value = data.jenisCaraBayar ?? "";
                kodeBayarFld.value = data.kodeBayar ?? "";
                kodeInstansiFld.value = data.kodeInstalasi ?? "";
                kodePoliFld.value = data.kodePoli ?? "";
                kodeJenisCaraBayarFld.value = data.kodeJenisCaraBayar ?? "";
                kodeRuangRawatFld.value = data.kodeRuangRawat ?? "";
                noAntrianFld.value = data.noAntrian ?? "";
                noResepFld.value = data.noResep ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.namaPasien ?? "";
                kelaminFld.value = data.kelamin ?? "";
                tanggalLahirFld.value = data.tanggalLahir ?? "";
                alamatFld.value = data.alamat ?? "";
                noTelefonFld.value = data.noTelefon ?? "";
                tanggalAwalResepFld.value = data.tanggalAwalResep ?? "";
                tanggalAkhirResepFld.value = data.tanggalAkhirResep ?? "";
                jenisResepFld.value = data.jenisResep ?? "";
                dokterFld.value = data.dokter ?? "";
                caraBayarFld.value = data.caraBayar ?? "";
                namaInstansiFld.value = data.namaInstalasi ?? "";
                pembayaranFld.value = data.pembayaran ?? "";
                detailPembayaranFld.value = data.detailPembayaran ?? "";
                noKartuFld.value = data.noKartu ?? "";
                namaPoliFld.value = data.namaPoli ?? "";
                totalDiskonStc.innerHTML = data.totalDiskon ?? "";
                pembungkusStc.innerHTML = data.pembungkus ?? "";
                jasaPelayananStc.innerHTML = data.jasaPelayanan ?? "";
                jasaPelayananFld.value = data.jasaPelayanan ?? "";
                grandTotalStc.innerHTML = data.grandTotal ?? "";
                grandTotalFld.value = data.grandTotal ?? "";
                bayarFld.value = data.bayar ?? "";
                kembaliFld.value = data.kembali ?? "";
                atasNamaFld.value = data.atasNama ?? "";
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

            obatWgt.querySelectorAll(".obatBiasaFld").forEach(/** @type {HTMLInputElement} */ elm => {
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

            obatWgt.querySelectorAll(".diskonObatFld").forEach(/** @type {HTMLInputElement} */ elm => {
                const fields = closest(elm, "tr").fields;
                const kuantitas = sysNum(fields.kuantitasWgt.value);
                const harga = sysNum(fields.hargaJualWgt.value);
                const diskon = sysNum(fields.diskonObatFld.value);
                diskonObat += kuantitas * harga * diskon / 100;
            });

            obatWgt.querySelectorAll("input[name^=hargasatuan]").forEach(item => {
                const id = item.id.split("_")[1];
                const idDiskon = Math.floor(id / 10) * 10;
                const kuantitas = sysNum(divElm.querySelector(".kuantitasFld").value);
                const harga = sysNum(divElm.querySelector(".hargaJualFld").value);
                const diskon = sysNum(divElm.querySelector("#diskonracik_" + idDiskon).value);
                diskonRacik += kuantitas * harga * diskon / 100;
            });

            obatWgt.querySelectorAll(".listhargaobat").forEach(() => {
                const kuantitasFld = divElm.querySelector(".kuantitasFld");
                const id = kuantitasFld.getAttribute("idobatnya");

                let total2 = 0;
                divElm.querySelectorAll(".qty_" + id).forEach(/** @type {HTMLInputElement} */item => total2 += sysNum(item.value));

                const stok = sysNum(kuantitasFld.dataset.stok);
                kuantitasFld.style.color = (stok >= total2 || (stok < 0 && total2 < 0)) ? "black" : "red";

                const kuantitas = sysNum(kuantitasFld.value);
                const harga = sysNum(divElm.querySelector(".hargaJualFld").value);
                total += kuantitas * harga;
            });

            obatWgt.querySelectorAll(".hargapembungkus").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qtypembungkus_" + id).value);
                const harga = sysNum(divElm.querySelector("#hargapembungkus_" + id).value);
                total += kuantitas * harga;
                pembungkus += kuantitas * harga;
            });

            // TODO: js: uncategorized: move to controller
            const jasaObat = 300;
            const jasaRacik = 500;

            // TODO: js: uncategorized: move to controller
            const daftarJenisResep = {
                "Sitostatika 3":       137_000,
                "Sitostatika 2/RJ/UE": 122_000,
                "Sitostatika 1":       128_750,
                "Sitostatika VIP":     130_000,
                "00":                        0,
            };

            const jasaPelayanan = (jasaObat * obat) + (jasaRacik * racikan) + daftarJenisResep[jenisResepFld.value];
            const diskon = diskonRacik + diskonObat;
            const totalTanpaJasa = total + pembungkus - diskon;
            const totalAwal = jasaPelayanan + total + pembungkus - diskon;
            const totalAkhir = Math.ceil(totalAwal / 100) * 100;
            const totalJasa = totalAkhir - totalTanpaJasa;

            // TODO: js: uncategorized: confirm to controller, are these necessary. if not, change them to static.
            jasaPelayananFld.value = currency(totalJasa);
            grandTotalFld.value = currency(totalAkhir);
            pembungkusStc.value = currency(pembungkus);
            divElm.querySelector(".submittotal").value = currency(totalAkhir);
            divElm.querySelector(".submitjasa").value = currency(totalJasa);
            totalDiskonStc.value = currency(diskon);
        }

        const kodeRekamMedisWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRekamMedisFld"),
            maxItems: 1,
            valueField: "kodeRekamMedis",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.RekamMedisFields} data
             */
            assignPairs(formElm, data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienWgt.value = data.nama ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.RekamMedisFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.RekamMedisFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.RegistrasiFields} obj */
                    success(obj) {
                        alamatFld.value = obj.alamat;
                        caraBayarFld.value = obj.caraBayar;
                        kelaminFld.style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        tanggalLahirFld.value = userDate(obj.tanggalLahir);
                        noTelefonFld.value = obj.noTelefon;
                        namaInstansiFld.value = obj.namaInstalasi;
                        divElm.querySelector(".nm_kamar").value = obj.namaKamar || "";
                        namaPoliFld.value = obj.namaPoli;
                        namaPasienWgt.readonly = true;
                        divElm.querySelector("#obat_1").dispatchEvent(new Event("focus"));
                        kodeJenisCaraBayarFld.value = obj.kodeJenisCaraBayar;
                        kodeRuangRawatFld.value = obj.kodeRuangRawat;

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

        pembayaranFld.addEventListener("change", () => {
            $.post({
                url: "<?= $pembayaranUrl ?>",
                data: {q: pembayaranFld.value},
                /** @param {string} data */
                success(data) {detailPembayaranFld.innerHTML = data}
            });
        });

        const namaPasienWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaPasienFld"),
            maxItems: 1,
            valueField: "nama",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.NamaFields} data
             */
            assignPairs(formElm, data) {
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
            },
            /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.NamaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.NamaFields} data */
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
                /** @type {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.NamaFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.RegistrasiFields} obj */
                    success(obj) {
                        alamatFld.value = obj.alamat;
                        kelaminFld.style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        tanggalLahirFld.value = userDate(obj.tanggalLahir);
                        noTelefonFld.value = obj.noTelefon;
                        namaInstansiFld.value = obj.namaInstalasi;
                        namaPoliFld.value = obj.namaPoli;
                        namaPasienWgt.readonly = true;
                        divElm.querySelector("#obat_1").dispatchEvent(new Event("focus"));

                        $.post({
                            url: "<?= $nama3Url ?>",
                            data: {kodeRekamMedis: obj.kodeRekamMedis},
                            /** @param {string} data */
                            success(data) {
                                const listResepFld = divElm.querySelector(".listresep");
                                listResepFld.style.display = "block";
                                listResepFld.innerHTML = data;
                            }
                        });
                    }
                });
            }
        });

        jenisResepFld.addEventListener("change", hitungTotal);

        const obatWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".obatTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.ObatFields} data
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
                /** @type {HTMLInputElement} */  const hargaJualFld = trElm.querySelector(".hargaJualFld");
                /** @type {HTMLInputElement} */  const namaKemasanFld = trElm.querySelector(".namaKemasanFld");
                /** @type {HTMLInputElement} */  const kodeObatFld = trElm.querySelector(".kodeObatFld");
                /** @type {HTMLSelectElement} */ const namaObatFld = trElm.querySelector(".namaObatFld");
                /** @type {HTMLInputElement} */  const kuantitasFld = trElm.querySelector(".kuantitasFld");

                const namaObatWgt = new spl.SelectWidget({
                    element: namaObatFld,
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.ObatAcplFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeObatFld.value = data.kode ?? "";
                        namaKemasanFld.value = data.satuanKecil ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.ObatAcplFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.ObatAcplFields} data */
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
                        /** @type {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.ObatAcplFields} */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode, jenisResepObat: jenisResepFld.value},
                            /** @param {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.HargaFields} data */
                            success(data) {
                                namaObatFld.setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                                hargaJualFld.value = currency(data.harga);
                                hargaJualFld.classList.add("listhargaobat");

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
                    element: kuantitasFld,
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
                fields.namaObatWgt.destroy();
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
            addRowBtn: ".editBayarFrm .addRowBtn",
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

        obatWgt.addDelegateListener("tbody", "change", (event) => {
            const input = event.target;
            if (!input.matches(".idRacikFld, .kuantitasFld, .diskonObatFld")) return;
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.EresepBillingUi.EditBayar.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        function cekKembali() {
            timeout = null;
            const bayar = sysNum(bayarFld.value);
            const total = sysNum(grandTotalFld.value);

            kembaliFld.value = "" + (bayar - total);
            const submitBtn = divElm.querySelector(".buttonsubmit");
            if (bayar < total) {
                alert(str._<?= $h("Pembayaran Kurang Dari Total!") ?>);
                submitBtn.disabled = true;
                submitBtn.classList.add("disabled");
            } else {
                submitBtn.disabled = false;
                submitBtn.classList.remove("disabled");
            }
        }

        let timeout = null;
        bayarFld.addEventListener("keyup", () => {
            timeout && clearTimeout(timeout);
            timeout = setTimeout(cekKembali, 600);
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(editBayarWgt, kodeRekamMedisWgt, namaPasienWgt, obatWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, editBayarWgt);
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
