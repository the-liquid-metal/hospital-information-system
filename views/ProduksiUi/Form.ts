<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ProduksiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/produksi/index.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        string $actionUrl,
        string $obatAcplUrl,
        string $hargaUrl,
        string $pembungkusAcplUrl,
        string $stokDataUrl,
        string $rekamMedisAcplUrl,
        string $registrasiAjaxUrl,
        string $cekResepUrl,
        string $tableWidgetId,
        string $barangProduksiSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ProduksiUi.Table {
    export interface FormFields {
        jasaObat:          "?"|"??";
        kodePenjualan:     "?"|"??"; // kodePenjualanhidden
        jasaRacik:         "?"|"??";
        diskon:            "?"|"??";
        pembungkus:        "?"|"??";
        jasaPelayanan:     "?"|"??";
        grandtotal:        "?"|"??";
        submitTotal:       "?"|"??";
        grandtotalLain:    "?"|"??";
        jasaPelayananLain: "?"|"??";
        noProduksi:        "?"|"??";
        tanggalProduksi:   "?"|"??";
        unitKerja:         "?"|"??";
        keterangan:        "?"|"??";
        barangProduksi:    "?"|"??";
        tanggalKadaluarsa: "?"|"??";
        jumlahProduksi:    "?"|"??";
        nilaiTotal:        "?"|"??";
        nilaiSatuan:       "?"|"??";
    }

    export interface RegistrasiFields {
        kodeRekamMedis: string,
        kelamin:        string,
        alamat:         string,
        tanggalLahir:   string,
        noTelefon:      string,
        namaInstalasi:  string,
        namaPoli:       string,
    }

    export interface RekamMedisFields {
        kodeRekamMedis:     string;
        nama:               string;
        noPendaftaran:      string;
        tanggalPendaftaran: string;
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

    _structure = {
        row_1: {
            widthColumn: {
                heading3: {text: tlm.stringRegistry._<?= $h("Produksi") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".produksiFrm",
            row_2: {
                box_1: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".jasaObatFld"}, // truly does not have name attr
                    hidden_2: {class: ".kodePenjualanFld", name: "kodePenjualan"},
                    hidden_3: {class: ".jasaRacikFld"}, // truly does not have name attr
                    hidden_4: {class: ".diskonFld myinput checktotal", value: "0"}, // truly does not have name attr
                    hidden_5: {class: ".pembungkusFld myinput checktotal", value: "0"}, // truly does not have name attr
                    hidden_6: {class: ".jasaPelayananFld myinput", value: "0"}, // truly does not have name attr
                    hidden_7: {class: ".grandtotalFld myinput", value: "0"}, // truly does not have name attr
                    hidden_8: {class: ".totalpost submitTotalFld", name: "grandtotal", value: "0"},
                    hidden_9: {class: ".grandtotalLainFld", value: "0"}, // truly does not have name attr
                    hidden_10: {class: ".jasaPelayananLainFld submitjasa", name: "jasaPelayanan", value: "0"}, // jasapelayanan
                    hidden_11: {name: "submit", value: "save"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Produksi") ?>,
                        input: {class: ".noProduksiFld  no_rm", name: "noProduksi"} // no_produksi
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Produksi") ?>,
                        input: {class: ".tanggalProduksiFld", name: "tanggalProduksi"} // tgl_produksi ||| $nowValSystem
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Unit Kerja") ?>,
                        input: {class: ".unitKerjaFld  nama detailuser", name: "unitKerja", value: "Depo Produksi"} // unit_kerja
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan"}
                    }
                },
                box_2: {
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Barang Produksi") ?>,
                        select: {class: ".barangProduksiFld  resep", name: "barangProduksi"} // produksi
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Kadaluarsa") ?>,
                        input: {class: ".tanggalKadaluarsaFld  datepicker"} // truly does not have name attr
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Jumlah Produksi") ?>,
                        input: {class: ".jumlahProduksiFld", name: "jumlahProduksi"} // jumlahproduksi
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nilai") ?>,
                        input: {class: ".nilaiTotalFld", readonly: true} //  truly does not have name attr
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Nilai Satuan") ?>,
                        input: {class: ".nilaiSatuanFld", readonly: true} //  truly does not have name attr
                    },
                }
            },
            row_3: {
                widthColumn: {
                    heading4: {text: tlm.stringRegistry._<?= $h("Masukkan Obat: Hitam untuk Formularium Nasional, Merah untuk Formularium RS, Biru untuk LAINNYA") ?>}
                }
            },
            row_4: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Nama Obat") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Jumlah") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Nilai Satuan") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Nilai Total") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                input: {class: ".namaObatFld  .obatbiasa", name: "namaObat[]"}, // obat[]
                                hidden: {class: ".kodeObatFld", name: "kodeObat[]"}, // kode_obat[],
                                button: {class: ".cekStokBtn", text: tlm.stringRegistry._<?= $h("Cek Stok") ?>},
                            },
                            td_2: {
                                input: {class: ".pabrikFld", name: "pabrik[]", readonly: true}
                            },
                            td_3: {
                                input: {class: ".satuanFld", name: "satuan[]", readonly: true},
                                hidden: {class: ".diskonObatFld", name: "diskonobat[]"},
                            },
                            td_4: {
                                input: {class: ".kuantitasFld", name: "kuantitas[]"} // qty[]
                            },
                            td_5: {
                                input: {class: ".hargaJualFld", name: "hargaJual[]", readonly: true} // hargajual[]
                            },
                            td_6: {
                                input: {class: ".totalFld", name: "total[]", readonly: true}
                            },
                            td_7: {
                                button_1: {class: ".deleteBtn  remScnt", text: "-"},
                                button_2: {class: ".addBtn  addScnt", text: "+"},
                            },
                        }
                    }
                }
            },
            row_5: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Add") ?>, rLabel: tlm.stringRegistry._<?= $h("Reset") ?>}
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
        const {toSystemNumber: sysNum, toCurrency: currency, toUserDate: userDate, stringRegistry: str} = tlm;
        const idDepo = tlm.user.idDepo;
        const drawTr = spl.TableDrawer.drawTr;
        const hapusStr = str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const jasaObatFld = divElm.querySelector(".jasaObatFld");
        /** @type {HTMLInputElement} */  const kodePenjualanFld = divElm.querySelector(".kodePenjualanFld");
        /** @type {HTMLInputElement} */  const jasaRacikFld = divElm.querySelector(".jasaRacikFld");
        /** @type {HTMLInputElement} */  const diskonFld = divElm.querySelector(".diskonFld");
        /** @type {HTMLInputElement} */  const pembungkusFld = divElm.querySelector(".pembungkusFld");
        /** @type {HTMLInputElement} */  const jasaPelayananFld = divElm.querySelector(".jasaPelayananFld");
        /** @type {HTMLInputElement} */  const grandtotalFld = divElm.querySelector(".grandtotalFld");
        /** @type {HTMLInputElement} */  const submitTotalFld = divElm.querySelector(".submitTotalFld");
        /** @type {HTMLInputElement} */  const grandtotalLainFld = divElm.querySelector(".grandtotalLainFld");
        /** @type {HTMLInputElement} */  const jasaPelayananLainFld = divElm.querySelector(".jasaPelayananLainFld");
        /** @type {HTMLInputElement} */  const noProduksiFld = divElm.querySelector(".noProduksiFld");
        /** @type {HTMLInputElement} */  const tanggalProduksiFld = divElm.querySelector(".tanggalProduksiFld");
        /** @type {HTMLInputElement} */  const unitKerjaFld = divElm.querySelector(".unitKerjaFld");
        /** @type {HTMLInputElement} */  const keteranganFld = divElm.querySelector(".keteranganFld");
        /** @type {HTMLSelectElement} */ const barangProduksiFld = divElm.querySelector(".barangProduksiFld");
        /** @type {HTMLInputElement} */  const tanggalKadaluarsaFld = divElm.querySelector(".tanggalKadaluarsaFld");
        /** @type {HTMLInputElement} */  const jumlahProduksiFld = divElm.querySelector(".jumlahProduksiFld");
        /** @type {HTMLInputElement} */  const nilaiTotalFld = divElm.querySelector(".nilaiTotalFld");
        /** @type {HTMLInputElement} */  const nilaiSatuanFld = divElm.querySelector(".nilaiSatuanFld");

        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $barangProduksiSelect ?>", barangProduksiFld);
        this._selects.push(barangProduksiFld);

        const produksiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".produksiFrm"),
            /** @param {his.FatmaPharmacy.views.ProduksiUi.Table.FormFields} data */
            loadData(data) {
                jasaObatFld.value = data.jasaObat ?? "";
                kodePenjualanFld.value = data.kodePenjualan ?? "";
                jasaRacikFld.value = data.jasaRacik ?? "";
                diskonFld.value = data.diskon ?? "";
                pembungkusFld.value = data.pembungkus ?? "";
                jasaPelayananFld.value = data.jasaPelayanan ?? "";
                grandtotalFld.value = data.grandtotal ?? "";
                submitTotalFld.value = data.submitTotal ?? "";
                grandtotalLainFld.value = data.grandtotalLain ?? "";
                jasaPelayananLainFld.value = data.jasaPelayananLain ?? "";
                noProduksiFld.value = data.noProduksi ?? "";
                tanggalProduksiFld.value = data.tanggalProduksi ?? "";
                unitKerjaFld.value = data.unitKerja ?? "";
                keteranganFld.value = data.keterangan ?? "";
                barangProduksiFld.value = data.barangProduksi ?? "";
                tanggalKadaluarsaFld.value = data.tanggalKadaluarsa ?? "";
                jumlahProduksiFld.value = data.jumlahProduksi ?? "";
                nilaiTotalFld.value = data.nilaiTotal ?? "";
                nilaiSatuanFld.value = data.nilaiSatuan ?? "";
            },
            actionUrl: "<?= $actionUrl ?>",
            onSuccessSubmit() {
                tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
            }
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
        });

        /** @see {his.FatmaPharmacy.views.ProduksiUi.Table.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        // JUNK -----------------------------------------------------

        function cekTotal() {
            let jumlahRacikan = 0;
            let jumlahObat = 0;
            let total = 0;
            let diskonObat = 0;
            let diskonRacik = 0;

            divElm.querySelectorAll(".racikanobat").forEach(item => {
                if (item.value) jumlahRacikan++;
            });

            divElm.querySelectorAll(".obatbiasa").forEach(item => {
                if (item.value) jumlahObat++;
            });

            divElm.querySelectorAll("input[name^=diskonobat]").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qty_" + id).value);
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                const diskon = sysNum(divElm.querySelector("#diskonobat_" + id).value);
                diskonObat += kuantitas * harga * diskon / 100;
            });

            divElm.querySelectorAll("input[name^=hargajual]").forEach(item => {
                const id = item.id.split("_")[1];
                const idDiskon = Math.floor(id / 10) * 10;
                const kuantitas = sysNum(divElm.querySelector("#qty_" + id).value);
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                const diskon = sysNum(divElm.querySelector("#diskonracik_" + idDiskon).value);
                const subtotal = kuantitas * harga;
                divElm.querySelector("#total_" + id).value = currency(subtotal);
                diskonRacik += subtotal * diskon / 100;
            });

            divElm.querySelectorAll(".listhargaobat").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qty_" + id).value);
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                total += kuantitas * harga;
            });

            let pembungkus = 0;
            divElm.querySelectorAll(".hargapembungkus").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qtypembungkus_" + id).value);
                const harga = sysNum(divElm.querySelector("#hargapembungkus_" + id).value);
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
            const totalSatuan = total / sysNum(divElm.querySelector("#jumlahproduksi").value);

            divElm.querySelector(".pembungkus").value = currency(pembungkus);
            divElm.querySelector(".jasapelayanan").value = currency(totalJasa);
            divElm.querySelector(".grandtotal").value = currency(totalAkhir);
            divElm.querySelector(".submitTotalFld").value = currency(totalAkhir);
            divElm.querySelector("#nilaitotal").value = currency(total);
            divElm.querySelector("#nilaisatuan").value = currency(totalSatuan);
            divElm.querySelector(".submitjasa").value = currency(totalJasa);
            divElm.querySelector(".mydiskon").value = currency(diskon);
        }

        const namaObatWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaObatFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.ProduksiUi.Table.ObatFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.ProduksiUi.Table.ObatFields} data */
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
                /** @type {his.FatmaPharmacy.views.ProduksiUi.Table.ObatFields} */
                const obj = this.options[value];
                const jenisResepObat = divElm.querySelector(".jenisresepobat").value;
                const id = this.element.dataset.no;

                const obatFld = divElm.querySelector("#obat_" + id);
                obatFld.value = obj.namaBarang;
                obatFld.setAttribute("title", obj.sinonim);

                divElm.querySelector("#kode_obat_" + id).value = obj.kode;
                divElm.querySelector("#addScnt").dispatchEvent(new Event("click"));
                divElm.querySelector("#satuan_" + id).value = obj.satuanKecil;

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kode: obj.kode, jenisResepObat},
                    /** @param {his.FatmaPharmacy.views.ProduksiUi.Table.HargaFields} data */
                    success(data) {
                        divElm.querySelector("#obat_" + id).setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${obj.sinonim}`);

                        const hargaFld = divElm.querySelector("#harga_" + id);
                        hargaFld.value = data.harga;
                        hargaFld.classList.add("listhargaobat");

                        const qtyFld = divElm.querySelector("#qty_" + id);
                        qtyFld.dispatchEvent(new Event("focus"));
                        const classList = qtyFld.classList;
                        (data.stok < 1) ? classList.add("notenough") : classList.remove("notenough");

                        cekTotal();
                    }
                });
            }
        });

        divElm.querySelector(".hitungTotalFld").addEventListener("keypress", (event) => {
            const id = event.target.dataset.no;
            const jumlah = sysNum(divElm.querySelector(".pakai-" + id).value);
            const harga = sysNum(divElm.querySelector(".harga-hide-" + id).value);
            const total = harga * jumlah;

            let grandTotal = 0;
            divElm.querySelectorAll(".total-hide").forEach(item => grandTotal += sysNum(item.value));

            divElm.querySelector(".total-" + id).value = currency(total);
            divElm.querySelector(".total-hide-" + id).value = currency(total);
            divElm.querySelector(".grandtotal").value = currency(grandTotal);
        });

        divElm.querySelector(".hapusObatRacikFld").addEventListener("keypress", (event) => {
            if (!confirm(hapusStr)) return;

            const id = event.target.dataset.no;
            divElm.querySelector(".no-racik-" + id).remove();
        });

        divElm.querySelector(".tambahObatRacikFld").addEventListener("keypress", (event) => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const x = event.target.dataset.no;
            const no = divElm.querySelectorAll(".group #tabel_rac").length;
            const idx = x + x + (divElm.querySelectorAll(`#racik_${x} .baris`).length + 1);

            const trStr = drawTr("tbody", {
                class: "baris no-racik-" + idx,
                td_1: {
                    hidden_1: {class: ".namaRacikanFld", name: `nm_racikan[${no}]`, value: "Racikan " + x},
                    hidden_2: {class: ".noRacikanFld", name: "no_racikan[]", value: x},
                    hidden_3: {class: ".kodeObatFld", name: `kode_obat-${no}[]`},
                    hidden_4: {id: "generik-" + idx},
                    input: {class: ".namaObatFld", name: `obat-${no}[]`, "data-no": idx, placeholder: str._<?= $h("Nama Obat") ?>},
                    button: {class: ".cekStokBtn", "data-kode": `obat_${no}${no}0`, text: str._<?= $h("Cek") ?>, title: str._<?= $h("Cek Stok") ?>},
                },
                td_2: {
                    hidden: {class: ".keteranganJumlahFld", name: `ketjumlah-${x}[]`},
                    input: {class: ".checktotal", name: `qty-${x}[]`, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_3: {
                    input: {class: ".satuanFld", name: "satuan[]", readonly: true}
                },
                td_4: {
                    hidden: {class: ".hargaBeliFld", name: "harga_beli[]"},
                    input: {class: ".hargaSatuanFld  harga-hide  harga-hide-" + i, name: `hargasatuan-${no}[]`, readonly: true, placeholder: str._<?= $h("Harga") ?>}
                },
                td_5: {
                    button_1: {class: ".hapusObatRacikFld", id: "hapus", "data-no": idx, text: "-"},
                    button_2: {class: ".tambahObatRacikFld", "data-no": no, text: "+"}
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
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ProduksiUi.Table.PembungkusFields} */
                const obj = this.options[value];
                const id = this.element.dataset.no;
                divElm.querySelector("#kode_pembungkus_" + id).value = obj.kode;
                divElm.querySelector("#pembungkus_" + id).value = obj.nama;
                divElm.querySelector("#hargapembungkus_" + id).value = obj.tarif;

                const qtyPembungkusFld = divElm.querySelector("#qtypembungkus_" + id);
                qtyPembungkusFld.value = "1";
                qtyPembungkusFld.dispatchEvent(new Event("focus"));

                cekTotal();
            }
        });

        divElm.querySelector(".hapusPembungkusFld").addEventListener("keypress", (event) => {
            if (!confirm(hapusStr)) return;

            const id = event.target.dataset.no;
            divElm.querySelector(".no-pembungkus-" + id).remove();
        });

        divElm.querySelector(".tambahPembungkusFld").addEventListener("keypress", (event) => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const x = event.target.dataset.no;
            const no2 = divElm.querySelectorAll(`#pembungkus_${x} .pmbaris`).length + 1;
            const no1 = divElm.querySelectorAll(".group #tabel_pm").length;
            const idx = x + x + no2;

            const trStr = drawTr("tbody", {
                class: "baris no-pembungkus-" + idx,
                td_1: {
                    hidden: {class: ".noPembungkusFld", name: "nomorpb[]", value: no2}
                },
                td_2: {
                    hidden: {class: ".kodePembungkusFld", name: `kode_pembungkus-${no1}[]`},
                    input: {class: ".namaPembungkusFld", name: `pembungkus-${no1}[]`, "data-no": idx, placeholder: str._<?= $h("Nama Pembungkus") ?>}
                },
                td_3: {
                    input: {class: ".kuantitasPembungkusFld checktotal", name: `qtypembungkus-${no1}[]`, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_4: {
                    input: {class: ".hargaPembungkusFld  harga-hide  harga-hide-" + i, name: `hargapembungkus-${no1}[]`, readonly: true, placeholder: str._<?= $h("Harga") ?>}
                },
                td_5: {
                    button_1: {class: ".hapusPembungkusFld", "data-no": idx, text: "-"},
                    button_2: {class: ".tambahPembungkusFld", "data-no": no1, text: "+"},
                },
            });
            divElm.querySelector("#pembungkus_" + x).insertAdjacentHTML("beforeend", trStr);
        });

        divElm.querySelector("#jumlahproduksi").addEventListener("keyup", cekTotal);

        divElm.querySelector("#addScnt").addEventListener("click", () => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const trStr = drawTr("tbody", {
                class: "no-obat-" + i,
                td_1: {
                    hidden: {class: ".kodeObatFld", name: "kode_obat[]"},
                    input: {class: ".namaObatFld obatbiasa", "data-no": i, name: "obat[]", id: "obat_" + i, placeholder: str._<?= $h("Nama Obat") ?>},
                    button: {class: ".cekStokBtn", "data-kode": "kode_obat" + i, text: str._<?= $h("Cek") ?>, title: str._<?= $h("Cek Stok") ?>},
                },
                td_2: {
                    input: {class: ".pabrikFld", name: "pabrik[]", "data-no": i, readonly: true, placeholder: str._<?= $h("Pabrik") ?>}
                },
                td_3: {
                    hidden: {class: ".diskonObatFld", name: "diskonobat[]", "data-no": i},
                    input: {class: ".satuanFld", name: "satuan[]", "data-no": i, readonly: true, placeholder: str._<?= $h("Satuan") ?>}
                },
                td_4: {
                    input: {class: ".kuantitasFld", name: "qty[]", "data-no": i, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_5: {
                    input: {class: ".hargaJualFld", name: "hargajual[]", "data-no": i, readonly: true, placeholder: str._<?= $h("Harga") ?>}
                },
                td_6: {
                    input: {class: ".totalFld", name: "total[]", "data-no": i, readonly: true, placeholder: str._<?= $h("Subtotal") ?>}
                },
                td_7: {
                    button_1: {class: ".remScnt", id: "remScnt_" + i, "data-no": i, text: "-"},
                    button_2: {class: ".___", id: "addScnt", "data-no": i, text: "+"},
                },
            });
            divElm.querySelector("#listobat").insertAdjacentHTML("beforeend", trStr);
            divElm.querySelector("#obat_" + i).dispatchEvent(new Event("focus"));
        });

        divElm.querySelector("input[name^='qty-']").addEventListener("keyup", (event) => {
            if (event.keyCode != 35) return;

            const kuantitasFld = /** @type {HTMLInputElement} */ event.target;
            const kuantitas = kuantitasFld.value.split("");
            const penjumlah = [];
            const angka = [];

            let j = 0;
            kuantitas.forEach(val => {
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

            kuantitasFld.value = angka.pop();
            cekTotal();
        });

        divElm.querySelector(".remScnt").addEventListener("click", (event) => {
            if (!divElm.querySelectorAll("#listobat tr").length) return;
            if (!confirm(hapusStr)) return;

            const id = event.target.dataset.no;
            divElm.querySelector(".no-obat-" + id).remove();
            divElm.querySelector("#obat_1").dispatchEvent(new Event("focus"));
            cekTotal();
        });

        divElm.querySelector(".cekStokBtn").addEventListener("click", (event) => {
            const cekStokBtn = /** @type {HTMLButtonElement} */ event.target;
            if (!cekStokBtn.matches(".cekStokBtn")) return;

            $.post({
                url: "<?= $stokDataUrl ?>",
                data: {id: divElm.querySelector("#" + cekStokBtn.dataset.kode).value},
                success(data) {
                    const {stokKatalog, daftarStokKatalog} = data;
                    headerElm.innerHTML = `${stokKatalog.namaBarang} (${stokKatalog.idKatalog})`;
                    footerElm.innerHTML = "total: " + daftarStokKatalog.reduce((acc, curr) => acc + curr.jumlahStokFisik, 0);
                    stokWgt.load(daftarStokKatalog);
                }
            });
        });

        const kodeRekamMedisWgt = new spl.SelectWidget({
            element: divElm.querySelector(".no_rm"),
            maxItems: 1,
            valueField: "kodeRekamMedis",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.ProduksiUi.Table.RekamMedisFields} data
             */
            assignPairs(formElm, data) {
                // ".no_daftar": data.noPendaftaran ?? ""; // TODO: js: uncategorized: finish this
                // ".nama": data.nama ?? "";
            },
            /** @param {his.FatmaPharmacy.views.ProduksiUi.Table.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.ProduksiUi.Table.RekamMedisFields} data */
            itemRenderer(data) {return `<div class="item">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $rekamMedisAcplUrl ?>",
                    data: {kodeRekamMedis: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ProduksiUi.Table.RekamMedisFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.ProduksiUi.Table.RegistrasiFields} obj */
                    success(obj) {
                        divElm.querySelector(".alamat").value = obj.alamat;
                        divElm.querySelector(".kelamin").style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        divElm.querySelector(".tgllahir").value = userDate(obj.tanggalLahir);
                        divElm.querySelector(".notelp").value = obj.noTelefon;
                        divElm.querySelector(".nm_inst").value = obj.namaInstalasi;
                        divElm.querySelector(".nm_poli").value = obj.namaPoli;
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

        const datepickerWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".datepicker"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        divElm.querySelectorAll(".checktotal, input[name^=diskon]").forEach(item => item.addEventListener("change", cekTotal));

        divElm.querySelectorAll("input, select").forEach(item => item.addEventListener("keydown", (event) => {
            const fieldElm = /** @type {HTMLInputElement} */ event.target;
            if (!fieldElm.matches("insert")) return;

            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const trStr = drawTr("tbody", {
                class: "mytr-" + i,
                number: i,
                td_1: {text: i},
                td_2: {
                    hidden: {class: ".kodeObatFld", name: "kodeobat[]"},
                    input: {class: ".namaObatFld obat-" + i, name: "namaobat[]", "data-no": i}
                },
                td_3: {
                    input: {class: ".jumlahPakaiFld hitungTotalFld", name: "jumlahpakai[]"}
                },
                td_4: {
                    input: {class: ".noRacikFld", name: "no_racik[]"}
                },
                td_5: {
                    input_1: {class: ".signaHariFld", name: "signa-hari[]"},
                    input_2: {class: ".signaJumlahFld", name: "signa-jumlah[]"},
                },
                td_6: {
                    hidden: {class: ".hargaSatuanFld  harga-hide  harga-hide-" + i, name: "hargasatuan[]"},
                    input: {class: ".hargaSatuanFld  harga  harga-" + i, name: "hargasatuan[]"}
                },
                td_7: {
                    input: {class: ".diskonFld", name: "diskon[]"}
                },
                td_8: {
                    hidden: {class: ".hargaTotalFld  total-hide  total-hide-" + i, name: "hargatotal[]"},
                    input: {class: ".hargaTotalFld  total  total-" + i, name: "hargatotal[]"}
                },
            });
            divElm.querySelector(".last-tr").insertAdjacentHTML("beforeend", trStr);
            divElm.querySelector(`.mytr-${i} > td`).next().children().first().dispatchEvent(new Event("focus"));
        }));

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(produksiWgt, itemWgt, stokWgt, namaObatWgt, namaPembungkusWgt, kodeRekamMedisWgt, datepickerWgt);
        tlm.app.registerWidget(this.constructor.widgetName, produksiWgt);
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
