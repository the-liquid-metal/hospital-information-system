<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\TransaksiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/pengiriman7.php the original file
 */
final class FormPengiriman7
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $addActionUrl,
        string $obatAcplUrl,
        string $batchUrl,
        string $hargaUrl,
        string $pembungkusAcplUrl,
        string $kodeTransaksiUrl,
        string $printWidgetId,
        string $depoSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.Pengiriman7 {
    export interface FormFields {
        kodeTransaksi:  string|"in"|"x";
        diminta:        string|"in"|"out";
        noDokumen:      string|"in"|"out";
        peminta:        string|"in"|"out";
        tipePengiriman: string|"in"|"out";
        verifikasi:     string|"in"|"out";
    }

    export interface TableFields {
        namaBarang:   string|"in"|"x";
        kodeBarang:   string|"in"|"out";
        noBatch:      string|"in"|"out";
        namaPabrik:   string|"in"|"x";
        stokTersedia: string|"in"|"x";
        jumlah:       string|"in"|"out";
        satuanKecil:  string|"in"|"x";
    }

    export interface RegistrasiFields {
        kodeRekamMedis: string;
        kelamin:        string;
        alamat:         string;
        caraBayar:      string;
        tanggalLahir:   string;
        noTelefon:      string;
        namaInstalasi:  string;
        namaPoli:       string;
    }

    export interface RekamMedisFields {
        kodeRekamMedis: string;
        nama:           string;
        noPendaftaran:  string;
    }

    export interface PembungkusFields {
        id:    string;
        kode:  string;
        nama:  string;
        tarif: string;
    }

    export interface ObatFields {
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

    static style = {
        [this.widgetName + " .bordered"]: {
            _style: {border: "thin solid #d8d8d8 !important"}
        }
    };

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".transaksiPengirimanFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        staticText: {class: ".kodeTransaksiStc"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Pengirim") ?>,
                        select: {class: ".dimintaFld", name: "diminta"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Dikirim kepada") ?>,
                        select: {class: ".pemintaFld", name: "peminta"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Status") ?>,
                        staticText: {class: ".statusStc", value: tlm.stringRegistry._<?= $h("Proses Pengiriman") ?>}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
                        staticText: {class: ".tanggalStc"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Tipe Pengiriman") ?>,
                        select: {
                            class: ".tipePengirimanFld",
                            name: "tipePengiriman",
                            option_1: {value: "Pengiriman Tanpa Permintaan", label: tlm.stringRegistry._<?= $h("Pengiriman Tanpa Permintaan") ?>},
                            option_2: {value: "Pengiriman Tidak Terlayani",  label: tlm.stringRegistry._<?= $h("Pengiriman Tidak Terlayani") ?>},
                            option_3: {value: "Floor Stock",                 label: tlm.stringRegistry._<?= $h("Floor Stock") ?>}
                        }
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        checkbox: {class: ".verifikasiFld", name: "verifikasi"}
                    }
                }
            },
            row_2: {
                widthColumn: {
                    button_1: {class: "tambah-btn", text: tlm.stringRegistry._<?= $h("Tambah") ?>},
                    button_2: {class: "hapus-btn",  text: tlm.stringRegistry._<?= $h("Hapus") ?>},
                }
            },
            row_3: {
                widthTable: {
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("No. Batch") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Nama Pabrik") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Stok") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        }
                    },
                    tbody: {
                        id: "#listobat",
                        tr: {
                            td_1: {
                                input: {class: ".namaBarangFld", name: "namaBarang[]"},
                                hidden: {class: ".kodeBarangFld", name: "kodeBarang[]"},
                            },
                            td_2: {
                                select: {
                                    class: ".noBatchFld",
                                    name: "noBatch[]",
                                    option: {value: "", label: tlm.stringRegistry._<?= $h("Pilih Batch") ?>}
                                }
                            },
                            td_3: {
                                input: {class: ".namaPabrikFld", name: "namaPabrik[]", readonly: true}
                            },
                            td_4: {
                                input: {class: ".stokTersediaFld", name: "stokTersedia[]", readonly: true}
                            },
                            td_5: {
                                input: {class: ".jumlahFld notenough", name: "jumlah[]"}
                            },
                            td_6: {
                                input: {class: ".satuanKecilFld", name: "satuanKecil[]", readonly: true}
                            },
                            td_7: {
                                button_1: {class: ".hapusObatBtn", text: "-"},
                                button_2: {class: ".tambahObatBtn", text: "+"},
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
        }
    };

    constructor(divElm) {
        super();
        const {toSystemNumber: sysNum, toCurrency: currency, stringRegistry: str, nowVal} = tlm;
        const closest = spl.util.closestParent;
        const idDepo = tlm.user.idDepo;
        const drawTr = spl.TableDrawer.drawTr;
        const hapusStr = str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const kodeTransaksiStc = divElm.querySelector(".kodeTransaksiStc");
        /** @type {HTMLSelectElement} */ const dimintaFld = divElm.querySelector(".dimintaFld");
        /** @type {HTMLSelectElement} */ const pemintaFld = divElm.querySelector(".pemintaFld");
        /** @type {HTMLInputElement} */  const tanggalStc = divElm.querySelector(".tanggalStc");
        /** @type {HTMLSelectElement} */ const tipePengirimanFld = divElm.querySelector(".tipePengirimanFld");
        /** @type {HTMLInputElement} */  const verifikasiFld = divElm.querySelector(".verifikasiFld");

        tlm.app.registerSelect("_<?= $h($depoSelect) ?>", dimintaFld);
        tlm.app.registerSelect("_<?= $h($depoSelect) ?>", pemintaFld);
        this._selects.push(dimintaFld, pemintaFld);

        const transaksiPengirimanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".transaksiPengirimanFrm"),
            /** @param {his.FatmaPharmacy.views.Transaksi.Pengiriman7.FormFields} data */
            loadData(data) {
                kodeTransaksiStc.value = data.kodeTransaksi ?? "";
                dimintaFld.value = data.diminta ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                pemintaWgt.value = data.peminta ?? "";
                tanggalStc.innerHTML = nowVal("user");
                tipePengirimanFld.value = data.tipePengiriman ?? "";
                verifikasiFld.value = data.verifikasi ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._element.reset();
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.loadData({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Pengiriman Barang") ?>;

                    $.post({
                        url: "<?= $kodeTransaksiUrl ?>",
                        success(data) {kodeTransaksiStc.value = data}
                    });
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            onSuccessSubmit(event) {
                const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
                widget.show();
                widget.loadData({kodeTransaksi: "TP"+event.data.kodeTransaksi}, true);
            },
            onFailSubmit() {
                alert(str._<?= $h("terjadi error") ?>);
            },
            resetBtnId: false,
        });

        const noDokumenWgt = new spl.InputWidget({
            element: divElm.querySelector(".noDokumenFld"),
            errorRules: [{required: true}]
        });

        const pemintaWgt = new spl.InputWidget({
            element: pemintaFld,
            errorRules: [{
                callback() {return this._element.value != "Pilih Tujuan"},
                message: "Tidak boleh kosong."
            }]
        });

        // JUNK -----------------------------------------------------

        function hitungTotal() {
            let jumlahObat = 0;
            divElm.querySelectorAll(".namaBarangFld").forEach(/** @type {HTMLInputElement} */item => {
                if (item.value) jumlahObat++;
            });

            let total = 0;
            let pembungkus = 0;
            divElm.querySelectorAll(".hargaPembungkusFld").forEach(/** @type {HTMLInputElement} */item => {
                const kuantitas = sysNum(closest(item, "tr").querySelector(".kuantitasPembungkusFld").value);
                const harga = sysNum(item.value);
                total += kuantitas * harga;
                pembungkus += kuantitas * harga;
            });

            const totalTanpaJasa = total + pembungkus;
            const totalAwal = total + pembungkus;
            const totalAkhir = Math.ceil(totalAwal / 100) * 100;
            const totalJasa = totalAkhir - totalTanpaJasa;

            divElm.querySelector(".pembungkus").value = currency(pembungkus);
            divElm.querySelector(".jasapelayanan").value = currency(totalJasa);
            divElm.querySelector(".grandtotal").value = currency(totalAkhir);
            divElm.querySelector(".submittotal").value = currency(totalAkhir);
            divElm.querySelector(".submitjasa").value = currency(totalJasa);
        }

        const namaBarangWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaBarangFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.Transaksi.Pengiriman7.ObatFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.Transaksi.Pengiriman7.ObatFields} data */
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
            /** @this {spl.SelectWidget} */
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.Transaksi.Pengiriman7.ObatFields} */
                const {namaBarang, sinonim, kode, namaPabrik, satuanKecil} = this._displayOptions[value];
                const namaBarangFld = this._element;
                const trElm = closest(namaBarangFld, "tr");

                namaBarangFld.value = namaBarang;
                namaBarangFld.setAttribute("title", sinonim);

                trElm.querySelector(".kodeBarangFld").value = kode;
                trElm.querySelector(".namaPabrikFld").value = namaPabrik;
                trElm.querySelector(".satuanKecilFld").value = satuanKecil;

                $.post({
                    url: "<?= $batchUrl ?>",
                    data: {q: kode},
                    /** @param {string} data */
                    success(data) {
                        trElm.querySelector(".noBatchFld").innerHTML = data;
                    }
                });

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kode},
                    /** @param {his.FatmaPharmacy.views.Transaksi.Pengiriman7.HargaFields} data */
                    success(data) {
                        namaBarangFld.setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${sinonim}`);
                        trElm.querySelector(".stokTersediaFld").value = data.stok;

                        const kuantitasElm = trElm.querySelector(".jumlahFld");
                        kuantitasElm.dataset.stok = data.stok;
                        const classList = kuantitasElm.classList;
                        (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                        hitungTotal();
                    }
                });
            }
        });

        divElm.querySelector(".jumlahPakaiFld").addEventListener("keypress", (event) => {
            const jumlahPakaiFld = /** @type {HTMLInputElement} */ event.target;
            if (!jumlahPakaiFld.matches(".jumlahPakaiFld")) return;

            const trElm = closest(jumlahPakaiFld, "tr");
            const jumlah = sysNum(jumlahPakaiFld.value);
            const harga = sysNum(trElm.querySelector(".hargaSatuanFld").value);
            const total = harga * jumlah;

            let grandTotal = 0;
            divElm.querySelectorAll(".hargaTotalFld").forEach(/** @type {HTMLInputElement} */item => grandTotal += sysNum(item.value));

            trElm.querySelector(".hargaTotalFld").value = currency(total);
            divElm.querySelector(".grandtotal").value = currency(grandTotal);
        });

        divElm.querySelector(".hapusObatRacikBtn").addEventListener("keypress", (event) => {
            const hapusObatRacikBtn = /** @type {HTMLButtonElement}*/ event.target;
            if (!hapusObatRacikBtn.matches(".hapusObatRacikBtn")) return;
            if (!confirm(hapusStr)) return;

            closest(hapusObatRacikBtn, "tr").remove();
        });

        divElm.querySelector(".tambahObatRacikFLd").addEventListener("keypress", (event) => {
            const x = event.target.dataset.no;
            const no = divElm.querySelectorAll(".group #tabel_rac").length;
            const idx = x + x + (divElm.querySelectorAll("#racik_" + x + " .baris").length + 1);

            const trStr = drawTr("tbody", {
                class: "baris no-racik-" + idx,
                td_1: {
                    hidden_1: {class: ".namaRacikanFld", name: `nm_racikan[${no}]`, value: "Racikan " + x},
                    hidden_2: {class: ".noRacikanFld", name: "no_racikan[]", value: x},
                    hidden_3: {class: ".kodeBarangFld", name: `kodeBarang-${no}[]`},
                    hidden_4: {id: "generik-" + idx},
                    input: {class: ".namaBarangFld", name: `obat-${no}[]`, "data-no": idx, placeholder: str._<?= $h("Nama Obat") ?>}
                },
                td_2: {
                    hidden: {class: ".checktotal", name: `qty-${x}[]`},
                    input: {class: ".keteranganJumlahFld", name: `ketjumlah-${x}[]`, placeholder: str._<?= $h("Jumlah (ml/mg)") ?>}
                },
                td_3: {
                    hidden_1: {class: ".hargaSatuanFld", name: `hargasatuan-${no}[]`},
                    hidden_2: {class: ".hargaBeliFld", name: "harga_beli[]"},
                    input: {class: ".satuanKecilFld", name: "satuan[]", readonly: true},
                },
                td_4: {
                    button_1: {class: ".hapusObatRacikBtn", text: "-"},
                    button_2: {class: ".tambahObatRacikFLd", text: "+"},
                },
            });
            divElm.querySelector("#racik_" + x).insertAdjacentHTML("beforeend", trStr);
            // TODO: js: uncategorized: add focus event to ".namaBarangFld"
        });

        const namaPembungkusWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaPembungkusFld"),
            maxItems: 1,
            valueField: "nama",
            labelField: "nama",
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $pembungkusAcplUrl ?>",
                    data: {val: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            /** @this {spl.SelectWidget} */
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.Transaksi.Pengiriman7.PembungkusFields} */
                const {kode, tarif} = this._displayOptions[value];
                const trElm = closest(this._element, "tr");
                trElm.querySelector(".kodePembungkusFld").value = kode;
                trElm.querySelector(".hargaPembungkusFld").value = tarif;

                const kuantitasPembungkusFld = trElm.querySelector(".kuantitasPembungkusFld");
                kuantitasPembungkusFld.value = "1";
                kuantitasPembungkusFld.dispatchEvent(new Event("focus"));

                hitungTotal();
            }
        });

        divElm.querySelector(".hapusPembungkusBtn").addEventListener("keypress", (event) => {
            const hapusPembungkusBtn = /** @type {HTMLButtonElement}*/ event.target;
            if (!hapusPembungkusBtn.matches(".hapusPembungkusBtn")) return;
            if (!confirm(hapusStr)) return;

            closest(hapusPembungkusBtn, "tr").remove();
        });

        divElm.querySelector(".tambahPembungkusBtn").addEventListener("keypress", (event) => {
            const x = event.target.dataset.no;
            const no1 = divElm.querySelectorAll(`#pembungkus_${x} .pmbaris`).length + 1;
            const no2 = divElm.querySelectorAll(".group #tabel_pm").length;
            const idx = x + x + no1;

            const trStr = drawTr("tbody", {
                class: "baris",
                td_1: {
                    hidden: {class: ".noPembungkusFld", name: "nomorpb[]", value: no1}
                },
                td_2: {
                    hidden: {class: ".kodePembungkusFld", name: `kode_pembungkus-${no2}[]`},
                    input: {class: ".namaPembungkusFld", name: `pembungkus-${no2}[]`, "data-no": idx, placeholder: str._<?= $h("Nama Pembungkus") ?>},
                },
                td_3: {
                    hidden: {class: ".hargaPembungkusFld", name: `hargapembungkus-${no2}[]`},
                    input: {class: ".kuantitasPembungkusFld", name: `qtypembungkus-${no2}[]`, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_4: {
                    button_1: {class: ".hapusPembungkusBtn", text: "-"},
                    button_2: {class: ".tambahPembungkusBtn", text: "+"},
                },
            });
            divElm.querySelector("#pembungkus_" + x).insertAdjacentHTML("beforeend", trStr);
        });

        divElm.querySelectorAll("input[name^='qty-'], input[name^=jumlah]").forEach(item => item.addEventListener("keyup", (event) => {
            const kuantitasElm = /** @type {HTMLInputElement} */ event.target;
            const stok = kuantitasElm.dataset.stok;
            const kuantitas = kuantitasElm.value;
            const classList = kuantitasElm.classList;
            (stok >= kuantitas) ? classList.remove("notenough") : classList.add("notenough");

            if (event.keyCode != 35) return;

            const penjumlah = [];
            const angka = [];
            let j = 0;

            kuantitasElm.value.split("").forEach(val => {
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

            kuantitasElm.value = angka.pop();
            hitungTotal();
        }));

        divElm.querySelector(".tambahObatBtn").addEventListener("click", () => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const trStr = drawTr("tbody", {
                td_1: {
                    input: {class: ".namaBarangFld", name: "namaBarang[]", "data-no": i, placeholder: str._<?= $h("Nama Obat") ?>},
                    hidden_1: {class: ".kodeBarangFld", name: "kodeBarang[]"},
                    hidden_2: {class: ".hargaBeliFld", name: "harga_beli[]"},
                },
                td_2: {
                    select: {
                        class: ".noBatchFld",
                        name: "noBatch[]",
                        "data-no": i,
                        option: {value: "", label: str._<?= $h("Pilih Batch") ?>}
                    }
                },
                td_3: {
                    input: {class: ".namaPabrikFld", name: "namaPabrik[]", "data-no": i, readonly: true}
                },
                td_4: {
                    input: {class: ".stokTersediaFld", name: "stokTersedia[]", "data-no": i, readonly: true}
                },
                td_5: {
                    input: {class: ".jumlahFld checktotal notenough", name: "jumlah[]", "data-no": i, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_6: {
                    input: {class: ".satuanKecilFld", name: "satuan[]", "data-no": i, readonly: true, placeholder: str._<?= $h("Satuan") ?>}
                },
                td_7: {
                    button_1: {class: ".hapusObatBtn", text: "-"},
                    button_2: {class: ".tambahObatBtn", text: "+"},
                },
            });
            divElm.querySelector("#listobat").insertAdjacentHTML("beforeend", trStr);
            // TODO: js: uncategorized: add focus event to ".namaBarangFld"
        });

        divElm.querySelector(".hapusObatBtn").addEventListener("click", (event) => {
            const hapusObatBtn = /** @type {HTMLButtonElement}*/ event.target;
            if (!hapusObatBtn.matches(".hapusObatBtn")) return;
            if (divElm.querySelectorAll("#listobat tr").length == 1) return;
            if (!confirm(hapusStr)) return;

            closest(hapusObatBtn, "tr").remove();
        });

        divElm.querySelectorAll(".checktotal, .kuantitasPembungkusFld, .diskonFld").forEach(item => item.addEventListener("change", hitungTotal));

        divElm.querySelector(".tambah-btn").addEventListener("click", () => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const trStr = drawTr("tbody", {
                class: "mytr-" + i,
                number: i,
                td_1: {text: i},
                td_2: {
                    input: {class: "namaBarangFld obat-" + i, name: "namaBarang[]", "data-no": i},
                    hidden: {class: "kodeBarang-" + i, name: "kodeBarang[]"}
                },
                td_3: {
                    input: {class: ".jumlahPakaiFld", name: "jumlahpakai[]", "data-no": i}
                },
                td_4: {
                    input: {class: "racik-" + i, name: "no_racik[]"}
                },
                td_5: {
                    input_1: {class: "signa-hari-" + i, name: "signa-hari[]"},
                    input_2: {class: "signa-jumlah-" + i, name: "signa-jumlah[]"},
                },
                td_6: {
                    input: {class: ".hargaSatuanFld  harga  harga-" + i, name: "hargasatuan[]"}
                },
                td_7: {
                    input: {class: ".diskonFld", name: "diskon[]"}
                },
                td_8: {
                    input: {class: ".hargaTotalFld", name: "hargatotal[]"}
                },
            });
            divElm.querySelector(".last-tr").insertAdjacentHTML("beforebegin", trStr);
            divElm.querySelector(`.mytr-${i} > td`).next().children().first().dispatchEvent(new Event("focus"));
        });

        divElm.querySelector(".hapus-btn").addEventListener("click", () => {
            let i = divElm.querySelectorAll("#listobat tr").length + 1;
            if (i > 0) {
                divElm.querySelector(".mytr-" + i).replaceWith("");
                i--;
                divElm.querySelector(`.mytr-${i} > td`).next().children().first().dispatchEvent(new Event("focus"));
            }
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(transaksiPengirimanWgt, noDokumenWgt, pemintaWgt, namaBarangWgt, namaPembungkusWgt);
        tlm.app.registerWidget(this.constructor.widgetName, transaksiPengirimanWgt);
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
