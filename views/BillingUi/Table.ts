<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\BillingUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/billing/listresep2.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string $registerId,
        string $verifikasiBillingUrl,
        string $unverifikasiBillingUrl,
        string $dataPendaftaranUrl,
        string $dataHanyaResepTransferUrl,
        string $dataSemuaResepUrl
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.BillingUi.Table {
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
        caraBayar:      string;
        statusBilling:  string;
    }

    export interface ResepTableFields {
        noResep:         string;
        gabunganKode:    string;
        transfer:        string;
        tanggalTransfer: string;
        namaKamar:       string;
        jumlahTagihan:   string;
        jasaPelayanan:   string;
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
                        input: {class: ".tanggalAwalFld", name: "tanggalAwal"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir") ?>,
                        input: {class: ".tanggalAkhirFld", name: "tanggalAkhir"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                        input: {class: ".noPendaftaranFld", name: "noPendaftaran"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Nama Pasien") ?>,
                        input: {class: ".namaPasienFld", name: "namaPasien"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        select: {
                            class: ".kelaminFld",
                            name: "kelamin",
                            option_1: {value: "",  label: tlm.stringRegistry._<?= $h("Semua") ?>},
                            option_2: {value: "1", label: tlm.stringRegistry._<?= $h("Laki-laki") ?>},
                            option_3: {value: "0", label: tlm.stringRegistry._<?= $h("Perempuan") ?>}
                        }
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Status Pasien") ?>,
                        select: {
                            class: ".statusPasienFld",
                            name: "statusPasien",
                            option_1: {value: "",        label: tlm.stringRegistry._<?= $h("Semua") ?>},
                            option_2: {value: "dirawat", label: tlm.stringRegistry._<?= $h("Dirawat") ?>},
                            option_3: {value: "keluar",  label: tlm.stringRegistry._<?= $h("Keluar") ?>}
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
            widthTable: {
                class: ".daftarPendaftaranTbl",
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
        row_4: {
            widthColumn: {
                heading1: {text: tlm.stringRegistry._<?= $h("Daftar Billing") ?>}
            }
        },
        row_5: {
            widthColumn: {
                heading3: {class: ".listGabunganStc"}
            }
        },
        row_6: {
            widthColumn: {
                heading3: {class: ".kodeRekamMedisStc"}
            }
        },
        row_7: {
            widthColumn: {
                heading3: {class: ".noPendaftaranStc"}
            }
        },
        row_8: {
            widthColumn: {
                heading3: {class: ".statusStc"}
            }
        },
        row_9: {
            widthColumn: {
                button: {class: ".switchBtn"}
            }
        },
        row_10: {
            widthColumn: {
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_11: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Hanya Resep Transfer") ?>}
            }
        },
        row_12: {
            widthTable: {
                class: ".hanyaResepTransferTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. Resep/Gabungan") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Jumlah Tagihan") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Transfer User") ?>},
                    }
                }
            }
        },
        row_13: {
            widthColumn: {
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_14: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Semua Resep") ?>}
            }
        },
        row_15: {
            widthTable: {
                class: ".semuaResepTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("No. Resep/Gabungan") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Jumlah Tagihan") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Transfer User") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {blankStr, toUserInt: userInt, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;

        const listGabunganTpl = str._<?= $h("Daftar Gabungan Pasien: {{STR}}") ?>;
        const kodeRekamMedisTpl = str._<?= $h("Kode Rekam Medis: {{STR}}") ?>;
        const noPendaftaranTpl = str._<?= $h("No. Pendaftaran: {{STR}}") ?>;
        const statusTpl = str._<?= $h("Status: {{STR}}") ?>;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodeRekamMedisFld = divElm.querySelector(".kodeRekamMedisFld");
        /** @type {HTMLInputElement} */  const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLInputElement} */  const namaPasienFld = divElm.querySelector(".namaPasienFld");
        /** @type {HTMLSelectElement} */ const kelaminFld = divElm.querySelector(".kelaminFld");
        /** @type {HTMLSelectElement} */ const statusPasienFld = divElm.querySelector(".statusPasienFld");
        /** @type {HTMLButtonElement} */ const switchBtn = divElm.querySelector(".switchBtn");
        /** @type {HTMLDivElement} */    const listGabunganStc = divElm.querySelector(".listGabunganStc");
        /** @type {HTMLDivElement} */    const kodeRekamMedisStc = divElm.querySelector(".kodeRekamMedisStc");
        /** @type {HTMLDivElement} */    const noPendaftaranStc = divElm.querySelector(".noPendaftaranStc");
        /** @type {HTMLDivElement} */    const statusStc = divElm.querySelector(".statusStc");

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.BillingUi.Table.FormFields} data */
            loadData(data) {
                tanggalAwalWgt.value = data.tanggalAwal ?? "";
                tanggalAkhirWgt.value = data.tanggalAkhir ?? "";
                kodeRekamMedisFld.value = data.kodeRekamMedis ?? "";
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                namaPasienFld.value = data.namaPasien ?? "";
                kelaminFld.value = data.kelamin ?? "";
                statusPasienFld.value = data.statusPasien ?? "";
            },
            submit() {
                daftarPendaftaranWgt.refresh({
                    query: {
                        tanggalAwal: tanggalAwalWgt.value,
                        tanggalAkhir: tanggalAkhirWgt.value,
                        kodeRekamMedis: kodeRekamMedisFld.value,
                        noPendaftaran: noPendaftaranFld.value,
                        namaPasien: namaPasienFld.value,
                        kelamin: kelaminFld.value,
                        statusPasien: statusPasienFld.value,
                    }
                });
                listGabunganStc.innerHTML = listGabunganTpl.replace("{{STR}}", blankStr);
                kodeRekamMedisStc.innerHTML = kodeRekamMedisTpl.replace("{{STR}}", blankStr);
                noPendaftaranStc.innerHTML = noPendaftaranTpl.replace("{{STR}}", blankStr);
                statusStc.innerHTML = statusTpl.replace("{{STR}}", blankStr);
                switchBtn.innerHTML = blankStr;

                hanyaResepTransferWgt.removeAll();
                semuaResepWgt.removeAll();
            }
        });

        const tanggalAwalWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAwalFld"),
            ...tlm.dateWidgetSetting
        });

        const tanggalAkhirWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalAkhirFld"),
            ...tlm.dateWidgetSetting
        });

        switchBtn.addEventListener("click", () => {
            if (switchBtn.classList.contains("verifikasiBtn")) {
                $.post({
                    url: "<?= $verifikasiBillingUrl ?>",
                    data: {noPendaftaran: switchBtn.value},
                    success() {
                        alert(str._<?= $h('Perubahan "Keluar" berhasil dilakukan') ?>);
                        switchBtn.classList.add("unverifikasiBtn", "btn-warning");
                        switchBtn.classList.remove("verifikasiBtn", "btn-success");
                        switchBtn.innerHTML = str._<?= $h("Batal Keluar") ?>;
                    }
                });
            } else {
                $.post({
                    url: "<?= $unverifikasiBillingUrl ?>",
                    data: {noPendaftaran: switchBtn.value},
                    success() {
                        alert(str._<?= $h('Perubahan "Batal Keluar" berhasil dilakukan') ?>);
                        switchBtn.classList.add("verifikasiBtn", "btn-success");
                        switchBtn.classList.remove("unverifikasiBtn", "btn-warning");
                        switchBtn.innerHTML = str._<?= $h("Keluar") ?>;
                    }
                });
            }
        });

        const daftarPendaftaranWgt = new spl.TableWidget({
            element: divElm.querySelector(".daftarPendaftaranTbl"),
            url: "<?= $dataPendaftaranUrl ?>",
            uniqueId: "noPendaftaran",
            columns: {
                1:  {formatter: tlm.rowNumGenerator},
                2:  {field: "noPendaftaran"},
                3:  {field: "tanggalDaftar"},
                4:  {field: "kodeRekamMedis"},
                5:  {field: "namaPasien"},
                6:  {field: "namaInstalasi"},
                7:  {field: "namaPoli"},
                8:  {field: "___"},
                9:  {field: "caraBayar"},
                10: {formatter(unused, {noPendaftaran}) {
                    return draw({class: "pilihBtn", value: noPendaftaran, text: str._<?= $h("Pilih") ?>});
                }}
            }
        });

        daftarPendaftaranWgt.addDelegateListener("tbody", "click", (event) => {
            /** @type {HTMLButtonElement} */ const pilihBtn = event.target;
            if (!pilihBtn.matches(".pilihBtn")) return;

            const noPendaftaran = pilihBtn.value;
            const row = daftarPendaftaranWgt.getRowByUniqueId(noPendaftaran);
            const {namaPasien, kodeRekamMedis, statusBilling} = row;

            const statusStr = statusBilling ? str._<?= $h("Sudah Keluar") ?> : str._<?= $h("Belum Keluar") ?>;
            const switchStr = statusBilling ? str._<?= $h("Batal Keluar") ?> : str._<?= $h("Keluar") ?>;

            listGabunganStc.innerHTML = listGabunganTpl.replace("{{STR}}", namaPasien);
            kodeRekamMedisStc.innerHTML = kodeRekamMedisTpl.replace("{{STR}}", kodeRekamMedis);
            noPendaftaranStc.innerHTML = noPendaftaranTpl.replace("{{STR}}", noPendaftaran);
            statusStc.innerHTML = statusTpl.replace("{{STR}}", statusStr);
            switchBtn.innerHTML = switchStr;

            switchBtn.value = noPendaftaran;

            hanyaResepTransferWgt.refresh({
                query: {kodeRekamMedis, noPendaftaran}
            });
            semuaResepWgt.refresh({
                query: {kodeRekamMedis, noPendaftaran}
            });
        });

        const hanyaResepTransferWgt = new spl.TableWidget({
            element: divElm.querySelector(".hanyaResepTransferTbl"),
            url: "<?= $dataHanyaResepTransferUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {formatter(unused, item) {
                    const {gabunganKode, noResep} = item;
                    return gabunganKode || noResep;
                }},
                3: {field: "namaKamar"},
                4: {field: "tanggalTransfer", formatter: tlm.dateFormatter},
                5: {formatter(unused, item) {
                    const {jumlahTagihan, jasaPelayanan} = item;
                    return userInt(jumlahTagihan + jasaPelayanan);
                }},
                6: {field: "transfer"}
            }
        });

        const semuaResepWgt = new spl.TableWidget({
            element: divElm.querySelector(".semuaResepTbl"),
            url: "<?= $dataSemuaResepUrl ?>",
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {formatter(unused, item) {
                    const {gabunganKode, noResep} = item;
                    return gabunganKode || noResep;
                }},
                3: {field: "namaKamar"},
                4: {field: "tanggalTransfer", formatter: tlm.dateFormatter},
                5: {formatter(unused, item) {
                    const {jumlahTagihan, jasaPelayanan} = item;
                    return userInt(jumlahTagihan + jasaPelayanan);
                }},
                6: {field: "transfer"}
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, tanggalAwalWgt, tanggalAkhirWgt, daftarPendaftaranWgt, hanyaResepTransferWgt, semuaResepWgt);
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
