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
 * @see http://localhost/ori-source/fatma-pharmacy/views/eresepdepodr/struk-baru.php the original file
 */
final class ViewStruk
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $actionUrl,
        string $verifikasiUrl,
        string $ceklisResepUrl,
        string $transferUrl,
        string $formEditWidgetId,
        string $formPrintEtiketWidgetId,
        string $tableWidgetId,
        string $tableResepWidgetId,
        string $cetakStrukWidgetId,
        string $cetakStrukLqWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.EresepDepoDrUi.StrukBaru {
    export interface ContainerFields {
        pasien:        PasienFields;
        namaInstalasi: string;
        operator:      string;
        penjualan:     PenjualanFields;
        grandTotal:    string;
        daftarObat:    Array<ObatFields>;
        formData:      FormFields;
        namaDepo:      string;
    }

    export interface PasienFields {
        nama:            string;
        kelamin:         string;
        alamat:          string;
        namaKamar:       string;
        caraBayar:       string;
        transfer:        string;
        verifikasi:      string;
        noResep:         string;
        jenisResep:      string;
        dariTanggal:     string;
        sampaiTanggal:   string;
        kodeRekamMedis:  string;
        noPendaftaran:   string;
        iter:            string;
        totalPembungkus: string;
        keterangan:      string;
    }

    export interface PenjualanFields {
        totalJual:          string;
        totalDiskon:        string;
        totalJasaPelayanan: string;
    }

    export interface ObatFields {
        kodeRacik:        string;
        namaRacik:        string;
        noRacik:          string;
        namaBarang:       string;
        jumlahPenjualan:  string;
        keteranganJumlah: string;
        hargaJual:        string;
        urutanObat:       string;
        urutanRacik:      string;
    }

    export interface FormFields {
        noResep:   "no_resep";
        penulisan: "jelas";
        obat:      "obat";
        dosis:     "dosis";
        waktu:     "waktu";
        rute:      "rute";
        pasien:    "pasien";
        duplikasi: "duplikasi";
        interaksi: "interaksi";
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName]: {
            "td": {
                _style: {verticalAlign: "top", fontSize: "14px", fontFamily: "arial, Helvetica, sans-serif"}
            },
            ".btn-warning": {
                _suffixes_1: [""],
                _style_1:    {background: "orange", color: "black", border: "thin solid black", borderRadius: "2px"},
                _suffixes_2: [":hover"],
                _style_2:    {background: "#f7cd09", color: "white"},
            },
            ".btn-success": {
                _suffixes_1: [""],
                _style_1:    {background: "#37ef10", color: "black", border: "1px solid black", borderRadius: "2px"},
                _suffixes_2: [":hover"],
                _style_2:    {background: "#1aff00", color: "white"},
            }
        }
    };

    _structure = {
        row_1: {
            box_1: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Pasien") ?>,
                    staticText: {class: ".pasienStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Alamat") ?>,
                    staticText: {class: ".alamatStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Ruang") ?>,
                    staticText: {class: ".ruangStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                    staticText: {class: ".caraBayarStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Operator") ?>,
                    staticText: {class: ".operatorStc"}
                },
            },
            box_2: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                    staticText: {class: ".noResep1Stc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Dari Tanggal") ?>,
                    staticText: {class: ".dariTanggalStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Sampai Tanggal") ?>,
                    staticText: {class: ".sampaiTanggalStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                    staticText: {class: ".kodeRekamMedisStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                    staticText: {class: ".noPendaftaranStc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Iter") ?>,
                    staticText: {class: ".iterStc"}
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
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                    }
                }
            }
        },
        row_5: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_6: {
            box: {
                formGroup_1: {
                    label: tlm.stringRegistry._<?= $h("Subtotal") ?>,
                    staticText: {class: ".subtotalStc"}
                },
                formGroup_2: {
                    label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                    staticText: {class: ".diskonStc"}
                },
                formGroup_3: {
                    label: tlm.stringRegistry._<?= $h("Total Pembungkus") ?>,
                    staticText: {class: ".totalPembungkusStc"}
                },
                formGroup_4: {
                    label: tlm.stringRegistry._<?= $h("Jasa Pelayanan") ?>,
                    staticText: {class: ".jasaPelayananStc"}
                },
                formGroup_5: {
                    label: tlm.stringRegistry._<?= $h("Total") ?>,
                    staticText: {class: ".totalStc"}
                },
                formGroup_6: {
                    label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                    staticText: {class: ".keteranganStc"}
                }
            }
        },
        row_7: {
            column: {
                button_1: {class: ".editBtn",       text: tlm.stringRegistry._<?= $h("Edit") ?>},
                button_2: {class: ".printBtn",      text: tlm.stringRegistry._<?= $h("Print") ?>},
                button_3: {class: ".selesaiBtn",    text: tlm.stringRegistry._<?= $h("Selesai") ?>},
                button_4: {class: ".etiketBtn",     text: tlm.stringRegistry._<?= $h("Etiket") ?>},
                button_5: {class: ".transferBtn",   text: tlm.stringRegistry._<?= $h("Transfer") ?>},
                button_6: {class: ".verifikasiBtn", text: tlm.stringRegistry._<?= $h("Verifikasi") ?>},
                button_7: {class: ".pengkajianBtn", text: tlm.stringRegistry._<?= $h("Ceklis Resep") ?>, "data-toggle": "modal", "data-target": `#${this.constructor.widgetName} .modal1Mdl`},
                button_8: {class: ".gabungBtn",     text: tlm.stringRegistry._<?= $h("Gabung") ?>},
                button_9: {class: ".printLqBtn",    text: tlm.stringRegistry._<?= $h("Print-LQ") ?>},
            }
        },
        modal: {
            class: ".modal1Mdl",
            form: {
                class: ".resepFrm",
                row_1: {
                    box: {
                        title: tlm.stringRegistry._<?= $h("Prescription Error") ?>,
                        formGroup_1: {
                            label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                            hidden: {class: ".noResepFld", name: "noResep"},
                            staticText: {class: ".noResepStc"}
                        },
                        formGroup_2: {
                            label: tlm.stringRegistry._<?= $h("Benar dan Jelas Penulisan Resep") ?>,
                            radio_1: {class: ".penulisanYes", name: "penulisan", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                            radio_2: {class: ".penulisanNo",  name: "penulisan", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                        },
                        formGroup_3: {
                            label: tlm.stringRegistry._<?= $h("Benar Obat") ?>,
                            radio_1: {class: ".obatYes", name: "obat", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                            radio_2: {class: ".obatNo",  name: "obat", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                        },
                        formGroup_4: {
                            label: tlm.stringRegistry._<?= $h("Benar Dosis") ?>,
                            radio_1: {class: ".dosisYes", name: "dosis", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                            radio_2: {class: ".dosisNo",  name: "dosis", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                        },
                        formGroup_5: {
                            label: tlm.stringRegistry._<?= $h("Benar Waktu dan Frekuensi") ?>,
                            radio_1: {class: ".waktuYes", name: "waktu", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                            radio_2: {class: ".waktuNo",  name: "waktu", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                        },
                        formGroup_6: {
                            label: tlm.stringRegistry._<?= $h("Benar Rute") ?>,
                            radio_1: {class: ".ruteYes", name: "rute", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                            radio_2: {class: ".ruteNo", name: "rute", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                        },
                        formGroup_7: {
                            label: tlm.stringRegistry._<?= $h("Benar Pasien") ?>,
                            radio_1: {class: ".pasienYes", name: "pasien", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                            radio_2: {class: ".pasienNo", name: "pasien", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                        },
                        formGroup_8: {
                            label: tlm.stringRegistry._<?= $h("Tidak Ada Duplikasi Terapi") ?>,
                            radio_1: {class: ".duplikasiYes", name: "duplikasi", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                            radio_2: {class: ".duplikasiNo", name: "duplikasi", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                        },
                        formGroup_9: {
                            label: tlm.stringRegistry._<?= $h("Tidak Ada Interaksi Obat") ?>,
                            radio_1: {class: ".interaksiYes", name: "interaksi", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                            radio_2: {class: ".interaksiNo",  name: "interaksi", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
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
        }
    };

    constructor(divElm) {
        super();
        const {toUserInt: userInt, toUserDate: userDate, stringRegistry: str} = tlm;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const pasienStc = divElm.querySelector(".pasienStc");
        /** @type {HTMLDivElement} */    const alamatStc = divElm.querySelector(".alamatStc");
        /** @type {HTMLDivElement} */    const ruangStc = divElm.querySelector(".ruangStc");
        /** @type {HTMLDivElement} */    const caraBayarStc = divElm.querySelector(".caraBayarStc");
        /** @type {HTMLDivElement} */    const operatorStc = divElm.querySelector(".operatorStc");
        /** @type {HTMLDivElement} */    const noResep1Stc = divElm.querySelector(".noResep1Stc");
        /** @type {HTMLDivElement} */    const dariTanggalStc = divElm.querySelector(".dariTanggalStc");
        /** @type {HTMLDivElement} */    const sampaiTanggalStc = divElm.querySelector(".sampaiTanggalStc");
        /** @type {HTMLDivElement} */    const kodeRekamMedisStc = divElm.querySelector(".kodeRekamMedisStc");
        /** @type {HTMLDivElement} */    const noPendaftaranStc = divElm.querySelector(".noPendaftaranStc");
        /** @type {HTMLDivElement} */    const iterStc = divElm.querySelector(".iterStc");
        /** @type {HTMLDivElement} */    const subtotalStc = divElm.querySelector(".subtotalStc");
        /** @type {HTMLDivElement} */    const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */    const totalPembungkusStc = divElm.querySelector(".totalPembungkusStc");
        /** @type {HTMLDivElement} */    const jasaPelayananStc = divElm.querySelector(".jasaPelayananStc");
        /** @type {HTMLDivElement} */    const totalStc = divElm.querySelector(".totalStc");
        /** @type {HTMLDivElement} */    const keteranganStc = divElm.querySelector(".keteranganStc");
        /** @type {HTMLButtonElement} */ const editBtn = divElm.querySelector(".editBtn");
        /** @type {HTMLButtonElement} */ const printBtn = divElm.querySelector(".printBtn");
        /** @type {HTMLButtonElement} */ const etiketBtn = divElm.querySelector(".etiketBtn");
        /** @type {HTMLButtonElement} */ const transferBtn = divElm.querySelector(".transferBtn");
        /** @type {HTMLButtonElement} */ const verifikasiBtn = divElm.querySelector(".verifikasiBtn");
        /** @type {HTMLButtonElement} */ const pengkajianBtn = divElm.querySelector(".pengkajianBtn");
        /** @type {HTMLButtonElement} */ const gabungBtn = divElm.querySelector(".gabungBtn");
        /** @type {HTMLButtonElement} */ const printLqBtn = divElm.querySelector(".printLqBtn");

        /** @type {HTMLInputElement} */ const noResepFld = divElm.querySelector(".noResepFld");
        /** @type {HTMLDivElement} */   const noResepStc = divElm.querySelector(".noResepStc");
        /** @type {HTMLInputElement} */ const penulisanYes = divElm.querySelector(".penulisanYes");
        /** @type {HTMLInputElement} */ const penulisanNo = divElm.querySelector(".penulisanNo");
        /** @type {HTMLInputElement} */ const obatYes = divElm.querySelector(".obatYes");
        /** @type {HTMLInputElement} */ const obatNo = divElm.querySelector(".obatNo");
        /** @type {HTMLInputElement} */ const dosisYes = divElm.querySelector(".dosisYes");
        /** @type {HTMLInputElement} */ const dosisNo = divElm.querySelector(".dosisNo");
        /** @type {HTMLInputElement} */ const waktuYes = divElm.querySelector(".waktuYes");
        /** @type {HTMLInputElement} */ const waktuNo = divElm.querySelector(".waktuNo");
        /** @type {HTMLInputElement} */ const ruteYes = divElm.querySelector(".ruteYes");
        /** @type {HTMLInputElement} */ const ruteNo = divElm.querySelector(".ruteNo");
        /** @type {HTMLInputElement} */ const pasienYes = divElm.querySelector(".pasienYes");
        /** @type {HTMLInputElement} */ const pasienNo = divElm.querySelector(".pasienNo");
        /** @type {HTMLInputElement} */ const duplikasiYes = divElm.querySelector(".duplikasiYes");
        /** @type {HTMLInputElement} */ const duplikasiNo = divElm.querySelector(".duplikasiNo");
        /** @type {HTMLInputElement} */ const interaksiYes = divElm.querySelector(".interaksiYes");
        /** @type {HTMLInputElement} */ const interaksiNo = divElm.querySelector(".interaksiNo");

        const strukBaruWgt = new spl.DinamicContainerWidget({
            element: divElm,
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.StrukBaru.ContainerFields} data */
            loadData(data) {
                const pasien = data.pasien;
                const penjualan = data.penjualan;

                pasienStc.innerHTML = `${pasien.nama} (${pasien.kelamin})`;
                alamatStc.innerHTML = pasien.alamat;
                ruangStc.innerHTML = (data.namaInstalasi ? `${data.namaInstalasi}, ` : "") + pasien.namaKamar;
                caraBayarStc.innerHTML = pasien.caraBayar;
                operatorStc.innerHTML = data.operator;
                noResep1Stc.innerHTML = pasien.noResep;
                dariTanggalStc.innerHTML = userDate(pasien.dariTanggal);
                sampaiTanggalStc.innerHTML = userDate(pasien.sampaiTanggal);
                kodeRekamMedisStc.innerHTML = pasien.kodeRekamMedis;
                noPendaftaranStc.innerHTML = pasien.noPendaftaran;
                iterStc.innerHTML = pasien.iter;
                subtotalStc.innerHTML = penjualan.totalJual;
                diskonStc.innerHTML = penjualan.totalDiskon;
                totalPembungkusStc.innerHTML = pasien.totalPembungkus;
                jasaPelayananStc.innerHTML = userInt(penjualan.totalJasaPelayanan);
                totalStc.innerHTML = userInt(data.grandTotal);
                keteranganStc.innerHTML = pasien.keterangan;

                itemWgt.load(data.daftarObat);
                form1Wgt.loadData(data.formData);

                editBtn.value = pasien.noResep;
                printBtn.value = pasien.noResep;
                etiketBtn.value = pasien.noResep;

                transferBtn.value = pasien.noResep;
                transferBtn.disabled = !!pasien.transfer || pasien.jenisResep == "Pembelian Bebas" || pasien.jenisResep == "Pembelian Langsung";

                verifikasiBtn.value = pasien.noResep;
                verifikasiBtn.disabled = !!pasien.verifikasi;

                pengkajianBtn.disabled = data.namaDepo.includes("Rawat Jalan");

                gabungBtn.value = pasien.kodeRekamMedis;
                gabungBtn.disabled = data.namaDepo.includes("Rawat Jalan");

                printLqBtn.value = pasien.noResep;
            }
        });

        const form1Wgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".resepFrm"),
            /** @param {his.FatmaPharmacy.views.EresepDepoDrUi.StrukBaru.FormFields} data */
            loadData(data) {
                noResepFld.value = data.noResep ?? "";
                noResepStc.innerHTML = data.noResep ?? "";
                data.penulisan ? penulisanYes.checked = true : penulisanNo.checked = true;
                data.obat ? obatYes.checked = true : obatNo.checked = true;
                data.dosis ? dosisYes.checked = true : dosisNo.checked = true;
                data.waktu ? waktuYes.checked = true : waktuNo.checked = true;
                data.rute ? ruteYes.checked = true : ruteNo.checked = true;
                data.pasien ? pasienYes.checked = true : pasienNo.checked = true;
                data.duplikasi ? duplikasiYes.checked = true : duplikasiNo.checked = true;
                data.interaksi ? interaksiYes.checked = true : interaksiNo.checked = true;
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            columns: {
                1: {field: "urutanObat"},
                2: {formatter(unused, item) {
                    const {urutanObat, namaBarang, urutanRacik} = item;
                    return urutanObat ? namaBarang : urutanRacik + ". " + namaBarang;
                }},
                3: {formatter(unused, item) {
                    const {urutanRacik, jumlahPenjualan, keteranganJumlah} = item;
                    const keterangan = urutanRacik ? " / " + keteranganJumlah : "";
                    return jumlahPenjualan ? jumlahPenjualan + keterangan : "";
                }},
                4: {formatter(unused, item) {
                    const {jumlahPenjualan, hargaJual} = item;
                    return userInt(jumlahPenjualan * hargaJual);
                }}
            }
        });

        editBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $formEditWidgetId ?>");
            widget.show();
            widget.loadData({noResep: editBtn.value}, true);
        });

        printBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakStrukWidgetId ?>");
            widget.show();
            widget.loadData({noResep: printBtn.value}, true);
        });

        etiketBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $formPrintEtiketWidgetId ?>");
            widget.show();
            widget.loadData({noResep: etiketBtn.value}, true);
        });

        verifikasiBtn.addEventListener("click", () => {
            $.post({
                url: "<?= $verifikasiUrl ?>",
                data: {q: "", id: verifikasiBtn.value},
                success() {
                    alert(str._<?= $h("Berhasil verifikasi. Stok telah dikurangi.") ?>);
                    verifikasiBtn.classList.remove("btn-success");
                    verifikasiBtn.classList.add("btn-warning");

                    $.post({
                        url: "<?= $ceklisResepUrl ?>",
                        data: {
                            noResep: noResepFld.value,
                            jelas: penulisanYes.checked ? penulisanYes.value : penulisanNo.value,
                            obat: obatYes.checked ? obatYes.value : obatNo.value,
                            dosis: dosisYes.checked ? dosisYes.value : dosisNo.value,
                            waktu: waktuYes.checked ? waktuYes.value : waktuNo.value,
                            rute: ruteYes.checked ? ruteYes.value : ruteNo.value,
                            pasien: pasienYes.checked ? pasienYes.value : pasienNo.value,
                            duplikasi: duplikasiYes.checked ? duplikasiYes.value : duplikasiNo.value,
                            interaksi: interaksiYes.checked ? interaksiYes.value : interaksiNo.value,
                        }
                    });

                    pengkajianBtn.classList.remove("btn-success");
                    pengkajianBtn.classList.add("btn-warning");
                }
            });
        });

        transferBtn.addEventListener("click", () => {
            $.post({
                url: "<?= $transferUrl ?>",
                data: {q: "", id: transferBtn.value},
                success() {
                    alert(str._<?= $h("Berhasil Transfer.") ?>);
                    transferBtn.classList.remove("btn-success");
                    transferBtn.classList.add("btn-warning");
                }
            });
        });

        gabungBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $tableResepWidgetId ?>");
            widget.show();
            widget.loadData({kodeRekamMedis: gabungBtn.value}, true);
        });

        printLqBtn.addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $cetakStrukLqWidgetId ?>");
            widget.show();
            widget.loadData({noResep: printLqBtn.value}, true);
        });

        divElm.querySelector(".selesaiBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(strukBaruWgt, form1Wgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, strukBaruWgt);
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
