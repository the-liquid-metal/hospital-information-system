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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Transaksi/pengeluaran2.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class FormPengeluaran2
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        string $dataUrl,
        string $actionUrl,
        string $obatAcplUrl,
        string $hargaUrl,
        string $hidePermintaanUrl,
        string $stokDataUrl,
        string $pembungkusAcplUrl,
        string $printWidgetId,
        string $depoSelect
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.Pengeluaran2 {
    export interface FormFields {
        kodeDokumenPengeluaran: string;
        noPermintaan:           string;
        kodeDepoPeminta:        string;
        verifikasiUser:         string;
        kodeDokumenPengiriman:  string;
        tanggal:                string;
        idDepoPemberi:          string;
        namaDepoPemberi:        string;
        tanggalVerifikasi:      string;
        daftarPengeluaran:      Array<PengeluaranFields>;
    }

    export interface PengeluaranFields {
        namaBarang:    string;
        kodeBarang:    string;
        namaPabrik:    string;
        stokTersedia:  string;
        stokPeminta:   string;
        jumlahDiminta: string;
        jumlahDikirim: string;
        namaKemasan:   string;
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
            class: ".transaksiPengeluaranFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Dokumen Pengeluaran") ?>,
                        input: {class: ".kodeDokumenPengeluaranFld", name: "kodeDokumenPengeluaran"},
                        hidden: {class: ".noPermintaanFld", name: "noPermintaan"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Depo Peminta (Tujuan)") ?>,
                        select: {class: ".kodeDepoPemintaFld", name: "kodeDepoPeminta"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        staticText: {text: `
                            <input type="checkbox" class="verifikasiKirimFld" name="verifikasi" value="verifikasi"/>
                            <span class="verifikasiKirimStc"></span>
                        `}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Kode Dokumen pengiriman") ?>,
                        input: {class: ".kodeDokumenPengirimanFld", name: "kodeDokumenPengiriman"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Tanggal") ?>,
                        input: {class: ".tanggalFld", name: "tanggal"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Depo Pemberi (Asal)") ?>,
                        staticText: {class: ".namaDepoPemberiFld"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Status Kirim") ?>,
                        staticText: {class: ".statusKirimStc"}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".obatTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Nama Pabrik") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Stok Tersedia") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Stok Peminta") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Jumlah Diminta") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Jumlah Dikirim") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Nama Kemasan") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                input: {class: ".namaBarangFld", name: "namaBarang[]"},
                                hidden: {class: ".kodeBarangFld", name: "kodeBarang[]"},
                                rButton: {class: ".stokBtn"}
                            },
                            td_2: {
                                input: {class: ".namaPabrikFld", name: "namaPabrik[]"}
                            },
                            td_3: {
                                input: {class: ".stokTersediaFld", name: "stokTersedia[]"}
                            },
                            td_4: {class: ".stokPemintaFld"},
                            td_5: {
                                input: {class: ".jumlahDimintaFld", name: "jumlahDiminta[]"}
                            },
                            td_6: {
                                input: {class: ".jumlahDikirimFld", name: "jumlahDikirim[]"}
                            },
                            td_7: {
                                input: {class: ".namaKemasanFld", name: "namaKemasan[]"}
                            },
                            td_8: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
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
            },
            row_4: {
                column: {
                    button: {class: ".printBtn", text: tlm.stringRegistry._<?= $h("Print") ?>}
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
        const {toSystemNumber: sysNum, toCurrency: currency, stringRegistry: str} = tlm;
        const closest = spl.util.closestParent;
        const userName = tlm.user.nama;
        const drawTr = spl.TableDrawer.drawTr;
        const hapusStr = str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodeDokumenPengeluaranFld = divElm.querySelector(".kodeDokumenPengeluaranFld");
        /** @type {HTMLInputElement} */  const noPermintaanFld = divElm.querySelector(".noPermintaanFld");
        /** @type {HTMLSelectElement} */ const kodeDepoPemintaFld = divElm.querySelector(".kodeDepoPemintaFld");
        /** @type {HTMLInputElement} */  const verifikasiKirimFld = divElm.querySelector(".verifikasiKirimFld");
        /** @type {HTMLDivElement} */    const verifikasiKirimStc = divElm.querySelector(".verifikasiKirimStc");
        /** @type {HTMLInputElement} */  const tanggalFld = divElm.querySelector(".tanggalFld");
        /** @type {HTMLDivElement} */    const namaDepoPemberiFld = divElm.querySelector(".namaDepoPemberiFld");
        /** @type {HTMLDivElement} */    const statusKirimStc = divElm.querySelector(".statusKirimStc");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $depoSelect ?>", kodeDepoPemintaFld);
        this._selects.push(kodeDepoPemintaFld);

        let idDepoPemberi;
        let noPermintaan;

        const transaksiPengeluaranWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".transaksiPengeluaranFrm"),
            dataUrl: "<?= $dataUrl ?>",
            actionUrl: "<?= $actionUrl ?>",
            /** @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.FormFields} data */
            loadData(data) {
                kodeDokumenPengeluaranFld.value = data.kodeDokumenPengeluaran ?? "";
                noPermintaanFld.value = data.noPermintaan ?? "";
                kodeDepoPemintaFld.value = data.kodeDepoPeminta ?? "";
                verifikasiKirimFld.checked = !!data.verifikasiUser;
                verifikasiKirimStc.innerHTML = userName + " " + data.tanggalVerifikasi;
                kodeTransaksiPengirimanWgt.value = data.kodeDokumenPengiriman ?? "";
                tanggalFld.value = data.tanggal ?? "";
                namaDepoPemberiFld.innerHTML = data.namaDepoPemberi ?? "";
                statusKirimStc.innerHTML = data.verifikasiUser ? str._<?= $h("Sudah Dikirim") ?> : str._<?= $h("Belum Dikirim") ?>;

                idDepoPemberi = data.idDepoPemberi ?? "";
                noPermintaan = data.noPermintaan ?? "";

                kodeDepoPemintaFld.querySelectorAll("option").forEach(item => item.disabled = !!data.verifikasiUser);
                kodeDepoPemintaFld.selectedOptions[0].disabled = false;

                obarWgt.loadData(data.daftarPengeluaran);
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
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
                widget.show();
                widget.loadData({noPermintaan}, true);
            },
            resetBtnId: false,
        });

        function hitungTotal() {
            let jumlahRacikan = 0;
            let jumlahObat = 0;
            let total = 0;
            let diskonObat = 0;
            let diskonRacik = 0;

            divElm.querySelectorAll(".racikanobat").forEach(/** @type {HTMLInputElement} */item => {
                if (item.value) jumlahRacikan++;
            });

            divElm.querySelectorAll(".namaBarangFld").forEach(/** @type {HTMLInputElement} */item => {
                if (item.value) jumlahObat++;
            });

            divElm.querySelectorAll("input[name^=diskonobat]").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qty_" + id).value);
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                const diskon = sysNum(divElm.querySelector("#diskonobat_" + id).value);
                diskonObat += kuantitas * harga * diskon / 100;
            });

            divElm.querySelectorAll("input[name^=hargasatuan]").forEach(item => {
                const id = item.id.split("_")[1];
                const idDiskon = Math.floor(id / 10) * 10;
                const kuantitas = sysNum(divElm.querySelector("#qty_" + id).value);
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                const diskon = sysNum(divElm.querySelector("#diskonracik_" + idDiskon).value);
                diskonRacik += kuantitas * harga * diskon / 100;
            });

            divElm.querySelectorAll(".listhargaobat").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qty_" + id).value);
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                total += kuantitas * harga;
            });

            let pembungkus = 0;
            divElm.querySelectorAll(".hargaPembungkusFld").forEach(/** @type {HTMLInputElement} */item => {
                const kuantitas = sysNum(closest(item, "tr").querySelector(".kuantitasPembungkusFld").value);
                const harga = sysNum(item.value);
                total += kuantitas * harga;
                pembungkus += kuantitas * harga;
            });

            const jasaObat = sysNum(divElm.querySelector(".jasaobat").value);
            const jasaRacik = sysNum(divElm.querySelector(".jasaracik").value);
            const diskon = diskonRacik + diskonObat;
            const jasaPelayanan = (jasaObat * jumlahObat) + (jasaRacik * jumlahRacikan);
            const totalTanpaJasa = total + pembungkus - diskon;
            const totalAwal = jasaPelayanan + total + pembungkus - diskon;
            const totalAkhir = Math.ceil(totalAwal / 100) * 100;
            const totalJasa = totalAkhir - totalTanpaJasa;

            divElm.querySelector(".pembungkus").value = currency(pembungkus);
            divElm.querySelector(".jasapelayanan").value = currency(totalJasa);
            divElm.querySelector(".grandtotal").value = currency(totalAkhir);
            divElm.querySelector(".submittotal").value = currency(totalAkhir);
            divElm.querySelector(".submitjasa").value = currency(totalJasa);
            divElm.querySelector(".mydiskon").value = currency(diskon);
        }

        const kodeTransaksiPengirimanWgt = new spl.InputWidget({
            element: divElm.querySelector(".kodeDokumenPengirimanFld"),
            errorRules: [{required: true}]
        });

        const obarWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".obatTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.PengeluaranFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.namaBarangWgt.value = data.namaBarang ?? "";
                fields.kodeBarangFld.value = data.kodeBarang ?? "";
                fields.namaPabrikFld.value = data.namaPabrik ?? "";
                fields.stokTersediaWgt.value = data.stokTersedia ?? "";
                fields.stokPemintaFld.value = data.stokPeminta ?? "";
                fields.jumlahDimintaWgt.value = data.jumlahDiminta ?? "";
                fields.jumlahDikirimWgt.value = data.jumlahDikirim ?? "";
                fields.namaKemasanFld.value = data.namaKemasan ?? "";
                fields.stokBtn.value = data.kodeBarang ?? "";
            },
            addRow(trElm) {
                const kodeBarangFld = trElm.querySelector(".kodeBarangFld");
                const namaPabrikFld = trElm.querySelector(".namaPabrikFld");
                const namaKemasanFld = trElm.querySelector(".namaKemasanFld");

                const namaBarangWgt = new spl.SelectWidget({
                    element: trElm.querySelector(".namaBarangFld"),
                    maxItems: 1,
                    valueField: "namaBarang",
                    /**
                     * @param trElm
                     * @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.ObatFields} data
                     */
                    assignPairs(trElm, data) {
                        kodeBarangFld.value = data.kode ?? "";
                        namaPabrikFld.value = data.namaPabrik ?? "";
                        stokTersediaWgt.value = data.stokFisik ?? "";
                        namaKemasanFld.value = data.satuanKecil ?? "";
                    },
                    /** @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.ObatFields} data */
                    optionRenderer(data) {
                        let warna;
                        switch ("" + data.formulariumNas + data.formulariumRs) {
                            case "10": warna = "black"; break;
                            case "01": warna = "red"; break;
                            case "00": warna = "blue";
                        }
                        return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
                    },
                    /** @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.ObatFields} data */
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
                            data: {q: typed, idDepo: idDepoPemberi},
                            error() {processor([])},
                            success(data) {processor(data)}
                        });
                    },
                    onItemAdd(value) {
                        /** @type {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.ObatFields} */
                        const obj = this.options[value];
                        $.post({
                            url: "<?= $hargaUrl ?>",
                            data: {kode: obj.kode},
                            /** @param {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.HargaFields} data */
                            success(data) {
                                const id = "TODO ...";
                                divElm.querySelector("#obat_" + id).setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                                const hargaFld = divElm.querySelector("#harga_" + id);
                                hargaFld.value = data.harga;
                                hargaFld.classList.add("listhargaobat");

                                jumlahDikirimWgt.checkValidity().showMessage("error");
                                hitungTotal();
                            }
                        });
                    }
                });

                const stokTersediaWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".stokTersediaFld"),
                    errorRules: [{greaterThanEqual: 0}],
                    ...tlm.floatNumberSetting
                });

                const jumlahDimintaWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahDimintaFld"),
                    errorRules: [{greaterThanEqual: 0}],
                    ...tlm.floatNumberSetting
                });

                const jumlahDikirimWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahDikirimFld"),
                    errorRules: [{
                        callback() {return sysNum(stokTersediaWgt.value) >= sysNum(this._element.value)},
                        message: str._<?= $h("Jumlah dikirim melebihi stok") ?>
                    }],
                    ...tlm.floatNumberSetting
                });

                trElm.fields = {
                    kodeBarangFld,
                    namaPabrikFld,
                    namaKemasanFld,
                    namaBarangWgt,
                    stokTersediaWgt,
                    jumlahDimintaWgt,
                    jumlahDikirimWgt,
                    stokBtn: trElm.querySelector(".stokBtn"),
                }
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.namaBarangWgt.destroy();
                fields.stokTersediaWgt.destroy();
                fields.jumlahDimintaWgt.destroy();
                fields.jumlahDikirimWgt.destroy();
            },
            onBeforeDeleteRow(trElm) {
                $.post({
                    url: "<?= $hidePermintaanUrl ?>",
                    data: {noPermintaan: noPermintaanFld.value, idobat: trElm.fields.kodeBarangFld.value}
                });
            },
            profile: {
                add() {
                    // TODO: js: uncategorized: finish this
                },
            },
            onInit() {
                this.loadProfile("add");
            }
        });

        obarWgt.addDelegateListener("tbody", "click", (event) => {
            const stokBtn = event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            $.post({
                url: "<?= $stokDataUrl ?>",
                data: {id: stokBtn.value},
                success(data) {
                    const {stokKatalog, daftarStokKatalog} = data;
                    headerElm.innerHTML = `${stokKatalog.namaBarang} (${stokKatalog.idKatalog})`;
                    footerElm.innerHTML = "total: " + daftarStokKatalog.reduce((acc, curr) => acc + curr.jumlahStokFisik, 0);
                    stokWgt.load(daftarStokKatalog);
                }
            });
        });

        divElm.querySelector(".printBtn").addEventListener("click", () => {
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({noPermintaan}, true);
        });

        /** @see {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        // JUNK -----------------------------------------------------

        divElm.querySelector(".hitungTotalFld").addEventListener("keypress", (event) => {
            const id = event.target.dataset.no;
            const jumlah = sysNum(divElm.querySelector(".pakai-" + id).value);
            const harga = sysNum(divElm.querySelector(".harga-hide-" + id).value);
            const total = harga * jumlah;

            let grandTotal = 0;
            divElm.querySelectorAll(".total-hide").forEach(/** @type {HTMLInputElement} */item => grandTotal += sysNum(item.value));

            divElm.querySelector(".total-" + id).value = currency(total);
            divElm.querySelector(".total-hide-" + id).value = currency(total);
            divElm.querySelector(".grandtotal").value = currency(grandTotal);
        });

        divElm.querySelector(".hapusRacikBtn").addEventListener("keypress", (event) => {
            const hapusRacikBtn = /** @type {HTMLButtonElement}*/ event.target;
            if (!hapusRacikBtn.matches(".hapusRacikBtn")) return;
            if (!confirm(hapusStr)) return;

            closest(hapusRacikBtn, "tr").remove();
        });

        divElm.querySelector(".tambahRacikanBtn").addEventListener("keypress", (event) => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const x = event.target.dataset.no;
            const no = divElm.querySelectorAll(".group #tabel_rac").length;
            const idx = x + x + (divElm.querySelectorAll(`#racik_${x} .baris`).length + 1);

            const trStr = drawTr("tbody", {
                class: "baris",
                td_1: {
                    hidden_1: {class: ".namaRacikanFld", name: `nm_racikan[${no}]`, value: "Racikan " + x},
                    hidden_2: {class: ".noRacikanFld", name: "no_racikan[]", value: x},
                    hidden_3: {class: ".kodeBarangFld", name: `kode_obat-${no}[]`},
                    hidden_4: {id: "generik-" + idx},
                    input: {class: ".cariObatFld", name: `obat-${no}[]`, id: "obat_" + idx, "data-no": idx, placeholder: str._<?= $h("Nama Obat") ?>}
                },
                td_2: {
                    hidden: {class: ".checktotal", name: `qty-${x}[]`},
                    input: {class: ".keteranganJumlahFld", name: `ketjumlah-${x}[]`}
                },
                td_3: {
                    hidden_1: {class: ".hargaSatuanFld harga-hide harga-hide-" + i, name: `hargasatuan-${no}[]`},
                    hidden_2: {class: ".hargaBeliFld", name: "harga_beli[]"},
                    input: {class: ".namaKemasanFld", name: "namaKemasan[]", readonly: true}
                },
                td_4: {
                    button_1: {class: ".hapusRacikBtn", "data-no": idx, text: "-"},
                    button_2: {class: ".tambahRacikanBtn", "data-no": no, text: "+"},
                },
            });
            divElm.querySelector("#racik_" + x).insertAdjacentHTML("beforeend", trStr);
            divElm.querySelector("#obat_" + idx).dispatchEvent(new Event("focus"));
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
                /** @type {his.FatmaPharmacy.views.Transaksi.Pengeluaran2.PembungkusFields} */
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
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const x = event.target.dataset.no;
            const no1 = divElm.querySelectorAll(`#pembungkus_${x} .pmbaris`).length + 1;
            const no2 = divElm.querySelectorAll(".group #tabel_pm").length;

            const trStr = drawTr("tbody", {
                class: ".baris",
                td_1: {
                    hidden_1: {class: ".noPembungkusFld", name: "nomorpb[]", value: no1},
                    hidden_2: {class: ".kodePembungkusFld", name: `kode_pembungkus-${no2}[]`},
                    input: {class: ".namaPembungkusFld", name: `pembungkus-${no2}[]`},
                },
                td_2: {
                    input: {class: ".kuantitasPembungkusFld checktotal", name: `qtypembungkus-${no2}[]`},
                    hidden: {class: ".hargaPembungkusFld harga-hide harga-hide-" + i, name: `hargapembungkus-${no2}[]`}
                },
                td_3: {
                    button_1: {class: ".hapusPembungkusBtn", text: "-"},
                    button_2: {class: ".tambahPembungkusBtn", text: "+"},
                },
            });
            divElm.querySelector("#pembungkus_" + x).insertAdjacentHTML("beforeend", trStr);
        });

        divElm.querySelector(".hapusObatBtn").addEventListener("keypress", (event) => {
            const hapusObatBtn = /** @type {HTMLButtonElement}*/ event.target;
            if (!hapusObatBtn.matches(".hapusObatBtn")) return;
            if (!confirm(hapusStr)) return;

            const trElm = closest(hapusObatBtn, "tr");
            $.post({
                url: "<?= $hidePermintaanUrl ?>",
                data: {
                    noPermintaan: noPermintaanFld.value,
                    idobat: trElm.querySelector(".kodeBarangFld").value,
                },
                success() {
                    trElm.remove();
                }
            });
        });

        divElm.querySelector(".tambahObatBtn").addEventListener("click", () => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const trStr = drawTr("tbody", {
                td_1: {
                    hidden_1: {class: ".kodeBarangFld", name: "kodeBarang[]"},
                    hidden_2: {class: ".hargaBeliFld", name: "harga_beli[]"},
                    input: {class: ".cariObatFld obatbiasa", name: "namaBarang[]", "data-no": i, placeholder: str._<?= $h("Nama Obat") ?>},
                    button: {class: ".cekStokBtn", "data-kode": "kode_obat_" + i, text: str._<?= $h("Cek") ?>, title: str._<?= $h("Cek Stok") ?>}
                },
                td_2: {
                    input: {class: ".namaPabrikFld", name: "namaPabrik[]", "data-no": i, readonly: true}
                },
                td_3: {
                    input: {class: ".stokTersediaFld", name: "stoktersedia[]", "data-no": i, readonly: true}
                },
                td_4: {
                    input: {"data-no": i, readonly: true}
                },
                td_5: {
                    input: {"data-no": i, readonly: true}
                },
                td_6: {
                    input: {class: ".jumlahFld checktotal", name: "jumlah[]", "data-no": i, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_7: {
                    input: {class: ".namaKemasanFld", name: "namaKemasan[]", "data-no": i, readonly: true, placeholder: str._<?= $h("Satuan") ?>}
                },
                td_8: {
                    button_1: {class: ".hapusObatBtn", text: "-"},
                    button_2: {class: ".tambahObatBtn", text: "+"},
                },
            });
            divElm.querySelector("#listobat").insertAdjacentHTML("beforeend", trStr);
            divElm.querySelector("#obat_" + i).dispatchEvent(new Event("focus"));
        });

        divElm.querySelectorAll(".checktotal, input[name^=diskon]").forEach(item => item.addEventListener("change", hitungTotal));

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(transaksiPengeluaranWgt, kodeTransaksiPengirimanWgt, obarWgt, stokWgt, namaPembungkusWgt);
        tlm.app.registerWidget(this.constructor.widgetName, transaksiPengeluaranWgt);
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
