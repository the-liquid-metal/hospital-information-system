<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ResepGabunganUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/resepgabungan/listresep2.php the original file
 */
final class TableResep
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $actionUrl,
        string $deleteGabunganUrl,
        string $transferUrl,
        string $kodeRekamMedisAcplUrl,
        string $cariRekamMedisUrl,
        string $cetak1WidgetId,
        string $cetak2WidgetId,
        string $lihatWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ResepGabungan.ListResep2 {
    export interface FormFields {
        tanggalAwal:    string;
        tanggalAkhir:   string;
        kodeRekamMedis: string;
        noPendaftaran:  string;
        namaPasien:     string;
        kelamin:        string;
        statusPasien:   string;
    }

    export interface PasienTableFields {
        noPendaftaran:  string;
        tanggalDaftar:  string;
        kodeRekamMedis: string;
        namaPasien:     string;
        namaInstalasi:  string;
        namaPoli:       string;
        namaCaraBayar:  string;
    }

    export interface Resep1TableFields {
        gabunganKode:  "gabungan_kode";
        transfer:      "transfer";
        namaKamar:     "nm_kamar";
        pembayaran:    "pembayaran";
        tanggalGabung: "tanggal_gabung";
        total:         "total";
    }

    export interface Resep2TableFields {
        noResep:          "no_resep";
        namaKamar:        "nm_kamar";
        tanggalPenjualan: "tglPenjualan";
        caraPembayaran:   "carapembayaran";
        transfer:         "transfer";
        gabunganKode:     "gabungan_kode";
    }

    export interface RekamMedisFields {
        kodeRekamMedis: string;
        namaPasien:     string;
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
        form_1: {
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
                            option_1: {value: "", label: <?= $h("Semua") ?>},
                            option_2: {value: "1", label: <?= $h("Laki-laki") ?>},
                            option_3: {value: "0", label: <?= $h("Perempuan") ?>},
                        }
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Status Pasien") ?>,
                        select: {
                            class: ".statusPasienFld",
                            option_1: {value: "", label: <?= $h("Semua") ?>},
                            option_2: {value: "dirawat", label: <?= $h("Dirawat") ?>},
                            option_3: {value: "keluar", label: <?= $h("Keluar") ?>},
                        }
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            },
        },
        row_3: {
            paragraph: {text: "&nbsp;"}
        },
        row_4: {
            widthTable: {
                class: ".pasienTbl",
                thead: {
                    tr: {
                        td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Nama") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Instalasi") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Poli/SMF") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Asal Rujukan") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Cara Bayar") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Pilih") ?>},
                    }
                }
            }
        },
        row_5: {
            paragraph: {text: "&nbsp;"}
        },
        row_6: {
            paragraph: {text: tlm.stringRegistry._<?= $h("Daftar Gabungan") ?>}
        },
        row_7: {
            paragraph: {text: "&nbsp;"}
        },
        row_8: {
            paragraph_1: {class: "title1Stc"},
            paragraph_2: {class: "title2Stc"},
        },
        row_9: {
            paragraph: {text: "&nbsp;"}
        },
        form_2: {
            class: ".form2Frm",
            hidden_1: {class: ".kodeRekamMedis2Fld", name: "kodeRekamMedis"},
            hidden_2: {class: ".namaPasien2Fld", name: "nama"},
            row_1: {
                widthTable: {
                    class: ".daftarResep1Tbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Penggabungan") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("No. Gabungan") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Cara Bayar") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Tanggal Gabung") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Jumlah Tagihan") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Hapus") ?>},
                        }
                    }
                }
            },
            row_2: {
                paragraph: {text: "&nbsp;"}
            },
            row_3: {
                widthTable: {
                    class: ".daftarResep2Tbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("No. Resep") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Tanggal Resep") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Cara Bayar") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Transfer") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Pilih") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Lihat") ?>},
                        }
                    }
                }
            },
            row_4: {
                paragraph: {text: "&nbsp;"}
            },
            row_5: {
                widthColumn: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Gabung Resep") ?>}
                }
            },
        },
    };

    constructor(divElm) {
        super();
        const {drawButton, drawTextInput: drawInput} = spl.TableDrawer;
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */ const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLInputElement} */ const namaPasienFld = divElm.querySelector(".namaPasienFld");
        /** @type {HTMLInputElement} */ const kelaminFld = divElm.querySelector(".kelaminFld");
        /** @type {HTMLInputElement} */ const statusPasienFld = divElm.querySelector(".statusPasienFld");
        /** @type {HTMLInputElement} */ const kodeRekamMedis2Fld = divElm.querySelector(".kodeRekamMedis2Fld");
        /** @type {HTMLInputElement} */ const namaPasien2Fld = divElm.querySelector(".namaPasien2Fld");
        /** @type {HTMLDivElement} */   const title1Stc = divElm.querySelector(".title1Stc");
        /** @type {HTMLDivElement} */   const title2Stc = divElm.querySelector(".title2Stc");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.ResepGabungan.ListResep2.FormFields} data */
            loadData(data) {
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                kodeRekamMedisWgt.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienFld.value = data.namaPasien ?? "";
                kelaminFld.value = data.kelamin ?? "";
                statusPasienFld.value = data.statusPasien ?? "";
            },
            submit() {
                pasienWgt.refresh({
                    query: {
                        tanggalAwal: tanggalAwalWgt.value,
                        tanggalAkhir: tanggalAkhirWgt.value,
                        kodeRekamMedis: kodeRekamMedisWgt.value,
                        noPendaftaran: noPendaftaranFld.value,
                        namaPasien: namaPasienFld.value,
                        kelamin: kelaminFld.value,
                        statusPasien: statusPasienFld.value,
                    }
                });
            }
        });

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

        const kodeRekamMedisWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRekamMedisFld"),
            maxItems: 1,
            valueField: "kodeRekamMedis",
            searchField: ["kodeRekamMedis", "namaPasien"],
            /** @param {his.FatmaPharmacy.views.ResepGabungan.ListResep2.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.namaPasien}`},
            /** @param {his.FatmaPharmacy.views.ResepGabungan.ListResep2.RekamMedisFields} data */
            itemRenderer(data) {return `<div class="item">${data.kodeRekamMedis} - ${data.namaPasien}`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $kodeRekamMedisAcplUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        const pasienWgt = new spl.TableWidget({
            element: divElm.querySelector(".pasienTbl"),
            url: "<?= $cariRekamMedisUrl ?>",
            columns: {
                1:  {formatter: tlm.rowNumGenerator},
                2:  {field: "noPendaftaran"},
                3:  {field: "tanggalDaftar", formatter: tlm.dateFormatter},
                4:  {field: "kodeRekamMedis"},
                5:  {field: "namaPasien"},
                6:  {field: "namaInstalasi"},
                7:  {field: "namaPoli"},
                8:  {field: "___"},
                9:  {field: "namaCaraBayar"},
                10: {formatter(unused, {kodeRekamMedis}) {
                    return drawButton({class: ".pilihBtn", value: kodeRekamMedis});
                }}
            }
        });

        // to simulate auto refresh on daftarResep1Wgt & daftarResep2Wgt after form submission.
        // by design, form submission can be done several times.
        /** @type {HTMLButtonElement} */ let lastClickedButton;

        pasienWgt.addDelegateListener("tbody", "click", (event) => {
            const pilihBtn = event.target;
            if (!pilihBtn.matches(".pilihBtn")) return;

            lastClickedButton = pilihBtn;
            const kode = /** @type {string} */ kodeRekamMedisWgt.value;
            const nama = /** @type {string} */ namaPasienFld.value;
            title1Stc.innerHTML = str._<?= $h("Dafter Gabungan Untuk Pasien: {{NAMA}}") ?>.replace("{{NAMA}}", nama);
            title2Stc.innerHTML = str._<?= $h("Kode Rekam Medis: {{KODE}}") ?>.replace("{{KODE}}", kode);

            kodeRekamMedis2Fld.value = kode;
            namaPasien2Fld.value = nama;

            $.post({
                url: "<?= $dataUrl ?>",
                data: {kodeRekamMedis: pilihBtn.value},
                success(data) {
                    daftarResep1Wgt.load(data.daftarResep1);
                    daftarResep2Wgt.load(data.daftarResep2);
                }
            });
        });

        const daftarResep1Wgt = new spl.TableWidget({
            element: divElm.querySelector(".daftarResep1Tbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {formatter(unused, item) {
                    const {gabunganKode, transfer} = item;
                    const buttonCls = transfer ? "warning" : "success";
                    return drawInput({type: "radio", name: "gabunganKode", value: gabunganKode}) + str._<?= $h("Gabung") ?> +
                        drawButton({class: ".transferBtn", type: buttonCls,     text: str._<?= $h("Transfer") ?>}) +
                        drawButton({class: ".cetak1Btn",   value: gabunganKode, text: str._<?= $h("Cetak") ?>}) +
                        drawButton({class: ".cetak2Btn",   value: gabunganKode, text: str._<?= $h("Cetak LQ") ?>});
                }},
                3: {field: "gabunganKode"},
                4: {field: "namaKamar"},
                5: {field: "pembayaran"},
                6: {field: "tanggalGabung", formatter: tlm.dateFormatter},
                7: {field: "total", formatter: tlm.intFormatter},
                8: {formatter(unused, {gabunganKode}) {
                    return drawButton({class: ".hapusBtn", type: "danger", value: gabunganKode, text: str._<?= $h("Hapus") ?>});
                }}
            }
        });

        divElm.querySelector(".transferBtn").addEventListener("click", (event) => {
            const transferBtn = /** @type {HTMLButtonElement} */ event.target;
            $.post({
                url: "<?= $transferUrl ?>",
                data: {q: "", id: transferBtn.dataset.no},
                success() {
                    transferBtn.classList.remove("btn-success");
                    transferBtn.classList.add("btn-warning");
                    transferBtn.disabled = true;
                    alert(str._<?= $h("Berhasil Transfer") ?>);
                }
            });
        });

        daftarResep1Wgt.addDelegateListener("tbody", "click", (event) => {
            const cetak1Btn = event.target;
            if (!cetak1Btn.matches(".cetak1Btn")) return;

            const widget = tlm.app.getWidget("_<?= $cetak1WidgetId ?>");
            widget.show();
            widget.loadData({gabunganKode: cetak1Btn.value}, true);
        });

        daftarResep1Wgt.addDelegateListener("tbody", "click", (event) => {
            const cetak2Btn = event.target;
            if (!cetak2Btn.matches(".cetak2Btn")) return;

            const widget = tlm.app.getWidget("_<?= $cetak2WidgetId ?>");
            widget.show();
            widget.loadData({gabunganKode: cetak2Btn.value}, true);
        });

        daftarResep1Wgt.addDelegateListener("tbody", "click", (event) => {
            const hapusBtn = event.target;
            if (!hapusBtn.matches(".hapusBtn")) return;

            $.post({
                url: "<?= $deleteGabunganUrl ?>",
                data: {gabunganKode: hapusBtn.value}
            });
        });

        const daftarResep2Wgt = new spl.TableWidget({
            element: divElm.querySelector(".daftarResep2Tbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "noResep"},
                3: {field: "namaKamar"},
                4: {field: "tanggalPenjualan", formatter: tlm.dateFormatter},
                5: {field: "caraPembayaran"},
                6: {field: "transfer"},
                7: {formatter(unused, item) {
                    const {gabunganKode, transfer, noResep} = item;
                    if (gabunganKode) {
                        return gabunganKode;
                    } else if (!transfer) {
                        return drawInput({type: "checkbox", name: "gabung[]", value: noResep}) + str._<?= $h("Gabung") ?>;
                    } else {
                        return "";
                    }
                }},
                8: {formatter(unused, {noResep}) {
                    return drawButton({class: ".lihatBtn", type: "success", value: noResep, text: str._<?= $h("Lihat") ?>});
                }}
            }
        });

        daftarResep2Wgt.addDelegateListener("tbody", "click", (event) => {
            const lihatBtn = event.target;
            if (!lihatBtn.matches(".lihatBtn")) return;

            const widget = tlm.app.getWidget("_<?= $lihatWidgetId ?>");
            widget.show();
            widget.loadData({noResep: lihatBtn.value}, true);
        });

        const form2Wgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".form2Frm"),
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit() {
                lastClickedButton.dispatchEvent(new Event("click"));
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, tanggalAwalWgt, tanggalAkhirWgt, kodeRekamMedisWgt);
        this._widgets.push(pasienWgt, daftarResep1Wgt, daftarResep2Wgt, form2Wgt);
        tlm.app.registerWidget(this.constructor.widgetName, saringWgt);
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
