<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ReturDepoUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/returndepo/edit.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/master/signa/stok.php the original file (stokTbl)
 */
final class FormEdit
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
        string $obatAcplUrl,
        string $hargaUrl,
        string $pembungkusAcplUrl,
        string $signaAcplUrl,
        string $stokDataUrl,
        string $rekamMedisAcplUrl,
        string $registrasiAjaxUrl,
        string $cekResepUrl,
        string $namaAcplUrl,
        string $testBridgeCekKeluarUrl,
        string $returDepoPrintWidgetId,
        string $viewStrukWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $daftarRacik = [];
        $resep = new \stdClass;

        $kodeRacik = "";
        $racikCounter = 1;
        $z = 1;
        $j = 0;
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ReturDepoUi.Edit {
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

    export interface SignaFields {
        id:   string;
        nama: string;
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
     * @returns {{edit: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
        };
        const access = {};
        for (const item in pool) {
            if (!pool.hasOwnProperty(item)) continue;
            access[item] = pool[item][role] ?? false;
        }
        return access;
    }

    static style = {
        [this.widgetName+" .noborder"]: {
            _style: {border: "none !important"}
        }
    };

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
            class: ".editReturDepoFrm",
            row_1: {
                box_1: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".urutFld", name: "urut"}, // $resep->noAntrian
                    hidden_2: {class: ".kodePenjualanSebelumnyaFld", name: "kodePenjualanSebelumnya"}, // kodePenjualansebelumnya ||| $resep->kodePenjualan
                    hidden_3: {class: ".editResepFld", name: "editResep"}, // edit_resep ||| $kodePenjualan
                    hidden_4: {class: ".jasaobat"}, // truly does not have name attr
                    hidden_5: {class: ".jasaracik"}, // truly does not have name attr
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("No. Resep") ?>,
                        input: {class: ".noResepFld", name: "noResep", readonly: true} // no_resep ||| $resep->noResep
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>,
                        input: {class: ".kodeRekamMedisFld", name: "kodeRekamMedis", readonly: true} // no_rm ||| $resep->kodeRekamMedis
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("No. Pendaftaran") ?>,
                        input: {class: ".noPendaftaranFld", name: "noPendaftaran", readonly: true} // no_daftar ||| $resep->noDaftar
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Nama") ?>,
                        input: {class: ".namaFld  detailuser", name: "nama", readonly: true} // $resep->nama
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Kelamin") ?>,
                        input: {class: ".kelaminFld  detailuser", name: "kelamin", readonly: true} // jk ||| $resep->kelamin
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Lahir") ?>,
                        input: {class: ".tanggalLahirFld  detailuser", name: "tanggalLahir", readonly: true} // tgllahir ||| $resep->tanggalLahir
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Alamat") ?>,
                        input: {class: ".alamatFld  detailuser", name: "alamat", readonly: true}, // $resep->alamatJalan
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("No. Telefon") ?>,
                        input: {class: ".noTelefonFld  detailuser", name: "noTelefon", readonly: true} // telp ||| $resep->noTelefon
                    },
                },
                box_2: {
                    title: tlm.stringRegistry._<?= $h("Resep") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Awal Resep") ?>,
                        input: {class: ".tanggalAwalResepFld", name: "tanggalAwalResep", readonly: true} // tgl_resep_s ||| $resep->tanggalResep1
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Akhir Resep") ?>,
                        input: {class: ".tanggalAkhirResepFld", name: "tanggalAkhirResep", readonly: true} // tgl_resep_e ||| $resep->tanggalResep2
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Jenis Resep") ?>,
                        input: {class: ".jenisResepFld", name: "jenisResep", readonly: true} // jr ||| $resep->jenisResep
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Dokter") ?>,
                        input: {class: ".namaDokterFld"}, // truly does not have name attr, $resep->namadokter
                        hidden: {class: ".dokterIdFld", name: "dokterId"} // dokter ||| $resep->dokter
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Pembayaran") ?>,
                        input: {class: ".pembayaranFld", name: "pembayaran", readonly: true} // $resep->pembayaran
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Nama Instalasi") ?>,
                        input: {class: ".namaInstalasiFld", name: "namaInstalasi", readonly: true}, // nm_inst ||| $resep->namaInstansi
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Nama Poli") ?>,
                        input: {class: ".namaPoliFld  detailuser", name: "namaPoli", readonly: true}, // nm_poli ||| $resep->namaPoli
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi") ?>,
                        checkbox: {class: ".verifikasiFld", name: "verifikasi"}, // ($cekIter <= $resep->iter) ? "checked" : "" ||| $cekIter = 1;
                        hidden_1: {class: ".verifiedFld", name: "verified"}, //($cekIter <= $resep->iter) ? 0 : 1 ||| $cekIter = 1;
                        hidden_2: {class: ".verifiedKeFld", name: "verifiedKe"} // verifiedke ||| $cekIter + 1 ||| $cekIter = 1;
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h(" ") ?>,
                        staticText: {class: ".verifiedkeStc"} // " Ke ".($cekIter+1)." Dari ".($resep->iter+1) ||| $cekIter = 1;
                    }
                }
            },
            row_2: {
                widthColumn: {text: tlm.stringRegistry._<?= $h("Masukkan Obat: Hitam untuk Formularium Nasional, Merah untuk Formularium RS, Hijau untuk LAINNYA") ?>}
            },
            row_3: {
                widthTable: {
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Nama Obat") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Kuantitas") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Signa") ?>},
                            td_7: {text: tlm.stringRegistry._<?= $h("Keterangan") ?>},
                            td_8: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        }
                    },
                    tbody: {
                        id: "#listobat",
                        tr: {
                            td_1: {
                                input_1: {class: ".namaObatFld", name: "namaObat[]"}, // obat[] ||| $daftarObat->namaBarang
                                input_2: {class: ".kodeObatAwalFld", name: "kodeObatAwal[]"}, // kode_obat_awal[< ?= $i - 1 ?>] ||| $daftarObat->kodeObat
                                hidden_1: {class: ".kodeObatFld", name: "kodeObat[]"}, // kode_obat[] ||| $daftarObat->kodeObat
                                hidden_2: {class: ".kodeObatAwalFld", name: "kodeObatAwal[]"}, // kode_obat_awal[< ?= $i - 1 ?>] ||| $daftarObat->kodeObat
                                hidden_3: {class: ".stokBridgingFld"}, // $i
                                hidden_4: {class: ".hargaBeliFld", name: "hargaBeli[]"}, // harga_beli[]
                            },
                            td_2: {
                                input: {class: ".kuantitasFld checktotal notenough", name: "kuantitas[]"} // qty[] ||| $daftarObat->jumlahPenjualan
                            },
                            td_3: {
                                input: {class: ".satuanFld", name: "satuan[]", readonly: true} // $daftarObat->namaKemasan
                            },
                            td_4: {
                                input: {class: ".hargaJualFld", name: "hargaJual[]", readonly: true} // hargajual[] ||| $daftarObat->harga
                            },
                            td_5: {
                                input: {class: ".diskonObatFld", name: "diskonObat[]"} // diskonobat[]
                            },
                            td_6: {
                                input: {class: ".namaSignaFld", name: "namaSigna[]"}, // nama_signa[] ||| $daftarObat->signa
                                hidden: {class: ".kodeSignaFld", name: "kodeSigna[]"} // kode_signa[] ||| $daftarObat->signa
                            },
                            td_7: {
                                textarea: {class: ".keteranganObatFld wysiwyg", name: "keteranganObat[]"} // keterangan_obat[] ||| $daftarObat->keteranganObat
                            },
                            td_8: {
                                button_1: {class: ".remScnt", text: "-"},
                                button_2: {class: ".addScnt", text: "+"},
                            },
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
        const closest = spl.util.closestParent;
        const drawTr = spl.TableDrawer.drawTr;
        const hapusStr = str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const editReturDepoWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".editReturDepoFrm"),
            dataUrl: "<?= $dataUrl ?>",
            loadData(data) {
                // TODO: js: uncategorized: finish this
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                edit(data) {
                    // TODO: js: uncategorized: finish this
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
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

        /** @type {HTMLInputElement} */ const noPendaftaranFld = divElm.querySelector(".noPendaftaranFld");
        /** @type {HTMLDivElement} */   const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */   const footerElm = divElm.querySelector(".footerElm");

        // JUNK -----------------------------------------------------

        function hitungTotal() {
            let obat = 0;
            let racikan = 0;
            let total = 0;
            let diskonObat = 0;
            let diskonRacik = 0;
            const daftarRacikan = [];
            let pembungkus = 0;

            divElm.querySelectorAll(".namaObatFld").forEach(/** @type {HTMLInputElement} */item => {
                if (!item.value) return;

                const idObatBiasa = item.dataset.no;
                const trElm = closest(item, "tr");
                const idRacikan = divElm.querySelector("#id_racik_" + idObatBiasa).value;
                const kuantitas = sysNum(trElm.querySelector(".kuantitasFld").value);

                if (!idRacikan && kuantitas) {
                    obat++;
                } else if (!daftarRacikan.includes(idRacikan)) {
                    daftarRacikan.push(idRacikan);
                    racikan++;
                }
            });

            divElm.querySelectorAll("input[name^=diskonobat]").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector(".kuantitasFld").value);
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                const diskon = sysNum(divElm.querySelector("#diskonobat_" + id).value);
                diskonObat += kuantitas * harga * diskon / 100;
            });

            divElm.querySelectorAll("input[name^=hargasatuan]").forEach(item => {
                const id = item.id.split("_")[1];
                const idDiskon = Math.floor(id / 10) * 10;
                const kuantitas = sysNum(divElm.querySelector(".kuantitasFld").value);
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                const diskon = sysNum(divElm.querySelector("#diskonracik_" + idDiskon).value);
                diskonRacik += kuantitas * harga * diskon / 100;
            });

            divElm.querySelectorAll(".listhargaobat").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitasFld = divElm.querySelector(".kuantitasFld");
                const stok = sysNum(kuantitasFld.dataset.stok);
                const kuantitas = sysNum(kuantitasFld.value);
                kuantitasFld.style.color = (stok > kuantitas) ? "black" : "red";
                const harga = sysNum(divElm.querySelector("#harga_" + id).value);
                total += kuantitas * harga;
            });

            divElm.querySelectorAll(".hargapembungkus").forEach(item => {
                const id = item.id.split("_")[1];
                const kuantitas = sysNum(divElm.querySelector("#qtypembungkus_" + id).value);
                const harga = sysNum(divElm.querySelector("#hargapembungkus_" + id).value);
                total += kuantitas * harga;
                pembungkus += kuantitas * harga;
            });

            // TODO: js: uncategorized: move to controller
            const jasaObat = 300;
            const jasaRacik = 500;

            const diskon = diskonRacik + diskonObat;
            const jenisResep = divElm.querySelector("select[name='jr']").value;

            // TODO: js: uncategorized: move to controller
            const daftarJenisResep = {
                "Sitostatika 3":       137_000,
                "Sitostatika 2/RJ/UE": 122_000,
                "Sitostatika 1":       128_750,
                "Sitostatika VIP":     130_000,
                "00":                        0,
            };

            const jasaPelayanan = (jasaObat * obat) + (jasaRacik * racikan) + daftarJenisResep[jenisResep];

            const totalTanpaJasa = total + pembungkus - diskon;
            const totalAwal = jasaPelayanan + total + pembungkus - diskon;
            const totalAkhir = Math.ceil(totalAwal / 100) * 100;
            const totalJasa = totalAkhir - totalTanpaJasa;

            divElm.querySelector(".pembungkus").value = currency(pembungkus);
            divElm.querySelector(".jasapelayanan").value = currency(totalJasa);
            divElm.querySelector(".grandtotal").value = currency(totalAkhir);
            divElm.querySelector(".submittotal").value = currency(totalAkhir);
            divElm.querySelector(".submitjasa").value = currency(totalJasa);
        }

        const namaObatWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaObatFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.ObatFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaBarang} (${data.kode}) - ${data.namaPabrik}, ${data.stokFisik}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.ObatFields} data */
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
                /** @type {his.FatmaPharmacy.views.ReturDepoUi.Edit.ObatFields} */
                const {namaBarang, sinonim, kode, satuanKecil} = this._displayOptions[value];
                const jenisResepObat = divElm.querySelector(".jenisresepobat").value;
                const namaObatFld = this._element;
                const trElm = closest(namaObatFld, "tr");

                namaObatFld.value = namaBarang;
                namaObatFld.readonly = true;
                namaObatFld.setAttribute("title", sinonim);

                trElm.querySelector(".kodeObatFld").value = kode;
                trElm.querySelector(".satuanFld").value = satuanKecil;

                $.post({
                    url: "<?= $hargaUrl ?>",
                    data: {kode, jenisResepObat},
                    /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.HargaFields} data */
                    success(data) {
                        namaObatFld.setAttribute("title", `stok = ${data.stok} \n\nsinonim = ${sinonim}`);

                        const id = namaObatFld.dataset.no;
                        const hargaFld = divElm.querySelector("#harga_" + id);
                        hargaFld.value = data.harga;
                        hargaFld.classList.add("listhargaobat");

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
                /** @type {his.FatmaPharmacy.views.ReturDepoUi.Edit.PembungkusFields} */
                const {kode, nama, tarif} = this._displayOptions[value];
                const id = this._element.dataset.no;
                divElm.querySelector("#kode_pembungkus_" + id).value = kode;
                divElm.querySelector("#pembungkus_" + id).value = nama;
                divElm.querySelector("#hargapembungkus_" + id).value = tarif;

                const qtyPembungkusFld = divElm.querySelector("#qtypembungkus_" + id);
                qtyPembungkusFld.value = "1";
                qtyPembungkusFld.dispatchEvent(new Event("focus"));

                hitungTotal();
            }
        });

        divElm.querySelector(".hapusPembungkusBtn").addEventListener("click", (event) => {
            if (!confirm(hapusStr)) return;

            const id = event.target.dataset.no;
            divElm.querySelector(".no-pembungkus-" + id).remove();
        });

        divElm.querySelector(".tambahPembungkusBtn").addEventListener("click", (event) => {
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
                    hidden: {class: ".kodePembungkusFld", name: "kode_pembungkus[]"},
                    input: {class: ".namaPembungkusFld", name: `pembungkus-${no1}[]`, "data-no": idx, placeholder: str._<?= $h("Nama Pembungkus") ?>}
                },
                td_3: {
                    input: {class: ".kuantitasPembungkusFld checktotal", name: "qtypembungkus[]", placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_4: {
                    input: {class: ".hargaPembungkusFld  harga-hide  harga-hide-" + i, name: "hargapembungkus[]", readonly: true, placeholder: str._<?= $h("Harga") ?>}
                },
                td_5: {
                    button_1: {class: ".hapusPembungkusBtn", "data-no": idx, text: "-"},
                    button_2: {class: ".tambahPembungkusBtn", "data-no": no1, text: "+"},
                },
            });
            divElm.querySelector("#pembungkus_" + x).insertAdjacentHTML("beforeend", trStr);
        });

        divElm.querySelector(".tambahObatRacikBtn").addEventListener("click", () => {
            const no = divElm.querySelectorAll(".group #tabel_rac").length + 1;
            const i = divElm.querySelectorAll("#listobat tr").length + 1;

            const tableStr = spl.LayoutDrawer.draw({
                div: {
                    class: "group",
                    id: "tabel_" + no,
                    table: {
                        id: "tabel_pm",
                        thead: {
                            tr: {
                                td_1: {text: str._<?= $h("Nama Pembungkus") ?>},
                                td_2: {text: str._<?= $h("Kuantitas") ?>},
                                td_3: {text: str._<?= $h("Harga") ?>},
                                td_4: {text: str._<?= $h("Action") ?>},
                            }
                        },
                        tbody: {
                            id: "pembungkus_" + no,
                            tr: {
                                class: `pmbaris no-pembungkus-${no}${no}0`,
                                td_1: {
                                    hidden: {class: ".kodePembungkusFld", name: "kode_pembungkus[]"},
                                    input: {class: ".namaPembungkusFld", name: `pembungkus-${no}[]`, "data-no": `${no}${no}0`, placeholder: str._<?= $h("Nama Pembungkus") ?>},
                                },
                                td_2: {
                                    input: {class: ".kuantitasPembungkusFld checktotal", name: "qtypembungkus[]", placeholder: str._<?= $h("Kuantitas") ?>}
                                },
                                td_3: {
                                    input: {class: ".hargaPembungkusFld  harga-hide  harga-hide-" + i, name: "hargapembungkus[]", readonly: true, placeholder: str._<?= $h("Harga") ?>}
                                },
                                td_4: {
                                    button_1: {class: ".hapusPembungkusBtn", "data-no": `${no}${no}0`, text: "-"},
                                    button_2: {class: ".tambahPembungkusBtn", "data-no": no, text: "+"},
                                },
                            }
                        }
                    },
                    button: {class: ".hapusTabelBtn", "data-no": no, text: "x"}
                }
            }).content;
            divElm.querySelector("#add_racikan").insertAdjacentHTML("beforeend", tableStr);
        });

        divElm.querySelector(".hapusTabelBtn").addEventListener("click", (event) => {
            const id = event.target.dataset.no;
            divElm.querySelector("#tabel_" + id).remove();
        });

        const namaSigna1Wgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaSigna1Fld"),
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
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ReturDepoUi.Edit.SignaFields} */
                const obj = this.options[value];
                const id = this.element.dataset.no;
                divElm.querySelector("#nama_signa_" + id).value = obj.nama;
            }
        });

        const namaSigna2Wgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaSigna2Fld"),
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
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ReturDepoUi.Edit.SignaFields} */
                const obj = this.options[value];
                const id = this._element.dataset.no;
                divElm.querySelector("#nama_signa2_" + id).value = obj.nama;
            }
        });

        const namaSigna3Wgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaSigna3Fld"),
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
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.ReturDepoUi.Edit.SignaFields} */
                const obj = this.options[value];
                const id = this.element.dataset.no;
                divElm.querySelector("#nama_signa3_" + id).value = obj.nama;
            }
        });

        divElm.querySelector(".racikanBtn").addEventListener("click", (event) => {
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
                    input: {class: ".namaObatFld", name: `obat-${no}[]`, placeholder: str._<?= $h("Nama Obat") ?>, "data-no": idx},
                    button: {class: ".cekStokBtn", "data-kode": `obat_${no}${no}0`, title: str._<?= $h("Cek Stok") ?>, text: str._<?= $h("Cek") ?>}
                },
                td_2: {
                    hidden: {class: ".kuantitasFld", name: `ketjumlah-${x}[]`},
                    input: {class: ".checktotal", name: `qty-${x}[]`, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_3: {
                    input: {class: ".satuanFld", name: "satuan[]", readonly: true}
                },
                td_4: {
                    hidden: {class: ".hargaBeliFld", name: "harga_beli[]"},
                    input: {class: ".hargaSatuanFld  harga-hide  harga-hide-" + i, name: `hargasatuan-${no}[],`, readonly: true, placeholder: str._<?= $h("Harga") ?>}
                },
                td_5: {
                    button_1: {class: ".hapusObatRacikBtn", id: "hapus", "data-no": idx, no: idx, text: "-"},
                    button_2: {class: ".racikanBtn", "data-no": no, text: "+"}
                },
            });
            divElm.querySelector("#racik_" + x).insertAdjacentHTML("beforeend", trStr);
            // TODO: js: uncategorized: add focus event to ".namaObatFld"
        });

        divElm.querySelector(".hapusObatRacikBtn").addEventListener("click", (event) => {
            if (!confirm(hapusStr)) return;

            const id = event.target.dataset.no;
            divElm.querySelector(".no-racik-" + id).remove();
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

        divElm.querySelector("#addScnt").addEventListener("click", () => {
            const i = divElm.querySelectorAll("#listobat tr").length + 1;
            const trStr = drawTr("tbody", {
                class: "no-obat-" + i,
                td_1: {
                    hidden_1: {class: ".kodeObatFld", name: "kode_obat[]"},
                    hidden_2: {class: ".hargaBeliFld", name: "harga_beli[]"},
                    input: {class: ".namaObatFld", name: "obat[]", "data-no": i, placeholder: str._<?= $h("Nama Obat") ?>},
                    button: {class: ".cekStokBtn", "data-kode": "kode_obat_" + i, title: str._<?= $h("Cek Stok") ?>, text: str._<?= $h("Cek") ?>}
                },
                td_2: {
                    input: {class: ".kuantitasFld checktotal notenough", name: "qty[]", "data-no": i, placeholder: str._<?= $h("Kuantitas") ?>}
                },
                td_3: {
                    input: {class: ".idRacikFld myid_racik", name: "id_racik[]", "data-no": i, placeholder: str._<?= $h("Racik") ?>}
                },
                td_4: {
                    hidden: {class: ".kodeSignaFld", name: "kode_signa[]"},
                    input_1: {class: ".namaSigna1Fld", name: "nama_signa[]", "data-no": i},
                    input_2: {class: ".namaSigna2Fld", name: "nama_signa2[]", "data-no": i},
                    input_3: {class: ".namaSigna3Fld", name: "nama_signa3[]", "data-no": i},
                },
                td_5: {
                    input: {class: ".satuanFld", name: "satuan[]", "data-no": i, readonly: true, placeholder: str._<?= $h("Satuan") ?>}
                },
                td_6: {
                    input: {class: ".hargaJualFld", name: "hargajual[]", "data-no": i, readonly: true, placeholder: str._<?= $h("Harga") ?>}
                },
                td_7: {
                    input: {class: ".diskonobatFld", name: "diskonobat[]", "data-no": i, placeholder: str._<?= $h("Diskon") ?>}
                },
                td_8: {
                    button: {class: ".showKeteranganBtn", "data-no": i, text: "[ Show ]"},
                    textarea: {class: ".keteranganObatFld wysiwyg", name: "keterangan_obat[]", "data-no": i, placeholder: str._<?= $h("keterangan") ?>}
                },
                td_9: {
                    button_1: {class: ".remScnt", id: "remScnt_" + i, "data-no": i, text: "-", title: str._<?= $h("Hapus Obat") ?>},
                    button_2: {class: ".___", id: "addScnt", "data-no": i, text: "+", title: str._<?= $h("Tambah obat") ?>},
                },
            });
            divElm.querySelector("#listobat").insertAdjacentHTML("beforeend", trStr);
            // TODO: js: uncategorized: add focus event to ".namaObatFld"
        });

        divElm.querySelectorAll("input[name^='qty-'], input[name^=qty]").forEach(item => item.addEventListener("keyup", (event) => {
            const kuantitasFld = /** @type {HTMLInputElement} */ event.target;

            const stok = Number(kuantitasFld.dataset.stok);
            const kuantitas = Number(kuantitasFld.value);
            const classList = kuantitasFld.classList;
            (stok >= kuantitas) ? classList.remove("notenough") : classList.add("notenough");

            if (event.keyCode != 35) return;

            const penjumlah = [];
            const angka = [];
            let j = 0;

            kuantitasFld.value.split("").forEach(val => {
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
            hitungTotal();
        }));

        divElm.querySelector(".remScnt").addEventListener("click", (event) => {
            if (divElm.querySelectorAll("#listobat tr").length < 2) return;
            if (!confirm(hapusStr)) return;

            const id = event.target.dataset.no;
            divElm.querySelector(".no-obat-" + id).remove();
            // TODO: js: uncategorized: add focus event to first ".namaObatFld"
            hitungTotal();
        });

        divElm.querySelector(".cekStokBtn").addEventListener("click", (event) => {
            const cekStokBtn = /**@type {HTMLButtonElement} */ event.target;
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
            element: divElm.querySelector(".kodeRekamMedisFld"),
            maxItems: 1,
            valueField: "kodeRekamMedis",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.RekamMedisFields} data
             */
            assignPairs(formElm, data) {
                noPendaftaranFld.value = data.noPendaftaran ?? "";
                // ".nama": data.nama ?? ""; // TODO: js: uncategorized: finish this
            },
            /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.RekamMedisFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.RekamMedisFields} data */
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
                /** @type {his.FatmaPharmacy.views.ReturDepoUi.Edit.RekamMedisFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.RegistrasiFields} obj */
                    success(obj) {
                        divElm.querySelector(".alamat").value = obj.alamat;
                        divElm.querySelector(".carabayar").value = obj.caraBayar;
                        divElm.querySelector(".kelamin").style.display = "none";

                        const kelamin2Fld = divElm.querySelector(".kelamin2");
                        kelamin2Fld.style.display = "block";
                        kelamin2Fld.value = (obj.kelamin == "1") ? str._<?= $h("L") ?> : str._<?= $h("P") ?>;

                        divElm.querySelector(".tgllahir").value = (userDate(obj.tanggalLahir));
                        divElm.querySelector(".notelp").value = obj.noTelefon;
                        divElm.querySelector(".nm_inst").value = obj.namaInstalasi;
                        divElm.querySelector(".nm_kamar").value = obj.namaKamar || "";
                        divElm.querySelector(".nm_poli").value = obj.namaPoli;
                        divElm.querySelector(".detailuser").readonly = true;
                        divElm.querySelector(".JNS_CARABAYAR").value = obj.jenisCaraBayar;
                        divElm.querySelector("#KD_JENIS_CARABAYAR").value = obj.kodeJenisCaraBayar;
                        divElm.querySelector("#KD_RRAWAT").value = obj.kodeRuangRawat;
                        divElm.querySelector(".KD_BAYAR").value = obj.kodeBayar;
                        divElm.querySelector(".KD_INST").value = obj.kodeInstalasi;
                        divElm.querySelector(".KD_POLI").value = obj.kodePoli;
                        divElm.querySelector("#dokter").dispatchEvent(new Event("focus"));

                        $.post({
                            url: "<?= $cekResepUrl ?>",
                            data: {kodeRekamMedis: obj.kodeRekamMedis},
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

        divElm.querySelector(".myid_racik").addEventListener("keydown", hitungTotal);

        const namaWgt = new spl.SelectWidget({
            element: divElm.querySelector(".nama"),
            maxItems: 1,
            valueField: "nama",
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.NamaFields} data
             */
            assignPairs(formElm, data) {
                // ".no_rm": data.kodeRekamMedis ?? ""; // TODO: js: uncategorized: finish this
                noPendaftaranFld.value = data.noPendaftaran ?? "";
            },
            /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.NamaFields} data */
            optionRenderer(data) {return `<div class="option">${data.kodeRekamMedis} - ${data.nama} - ${data.noPendaftaran}</div>`},
            /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.NamaFields} data */
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
                /** @type {his.FatmaPharmacy.views.ReturDepoUi.Edit.NamaFields} */
                const obj2 = this.options[value];
                $.post({
                    url: "<?= $registrasiAjaxUrl ?>",
                    data: {noPendaftaran: obj2.noPendaftaran, kodeRekamMedis: obj2.kodeRekamMedis},
                    /** @param {his.FatmaPharmacy.views.ReturDepoUi.Edit.RegistrasiFields} obj */
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
                        // TODO: js: uncategorized: add focus event to first ".namaObatFld"

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

        divElm.querySelector(".showketerangan").addEventListener("click", (event) => {
            const showketeranganElm = /** @type {HTMLElement}*/ event.target;
            const no = showketeranganElm.dataset.no;
            const keteranganObatElm = divElm.querySelector("#keterangan_obat_" + no);
            if (showketeranganElm.classList.contains("active")) {
                showketeranganElm.classList.remove("active");
                keteranganObatElm.innerHTML = "[ Show ]";
                keteranganObatElm.style.display = "none";
                divElm.querySelector("#cke_keterangan_obat_" + no).style.display = "none";
            } else {
                showketeranganElm.classList.add("active");
                keteranganObatElm.style.display = "block";
                keteranganObatElm.innerHTML = "[ Hide ]";
                divElm.querySelector("#cke_keterangan_obat_" + no).style.display = "block";
                CKEDITOR.replace("keterangan_obat_" + no);
            }
        });

        divElm.querySelectorAll("select[name=jr], .checktotal").forEach(item => item.addEventListener("change", hitungTotal));

        divElm.querySelector(".dokter").addEventListener("focus", () => {
            $.post({
                url: "<?= $testBridgeCekKeluarUrl ?>",
                data: {q1: noPendaftaranFld.value},
                /** @param {string} data */
                success(data) {
                    if (data == "1") {alert(str._<?= $h("Pasien telah keluar.") ?>)}
                }
            });
        });

        divElm.querySelector(".carabayar").style.display = "block";

        divElm.querySelector(".select2-container.pembayaran").style.display = "none";

        /** @see {his.FatmaPharmacy.views.ReturDepoUi.Edit.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokFisik", formatter: tlm.intFormatter}
            },
        });

        divElm.querySelector(
            ".checktotal," +
            "input[name^=diskon]," +
            ".jenisresepobat," +
            "input[name=qtypembungkus]"
        ).addEventListener("change", hitungTotal);

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(editReturDepoWgt, kodeRekamMedisWgt, namaObatWgt, namaPembungkusWgt);
        this._widgets.push(namaSigna1Wgt, namaSigna2Wgt, namaSigna3Wgt, stokWgt, namaWgt);
        tlm.app.registerWidget(this.constructor.widgetName, editReturDepoWgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<form id="myform" class="form-horizontal tab-content" action="<?= $returDepoPrintWidgetId ?>">
    <!-- FORM ELEMENT FROM WIDGET ARE HERE -->

    <table>
        <tr>
            <td colspan="4">
                <div class="well form-horizontal">
                    <div class="span8">
                        <button class="tambahObatRacikBtn">+ Racikan</button><br/><br/>
                        <div id="add_racikan">
                            <?php
                            foreach ($daftarRacik as $rc) {
                            $z++;
                            if ($kodeRacik != $rc->kodeRacik) {
                            ?>
                            <tbody id="racik_<?= $racikCounter ?>"></tbody>
                            <?php
                            if ($kodeRacik != "") {
                            $racikCounter++;
                            ?>
                            </table>
                            <button class="hapusTabelBtn" data-no="<?= $racikCounter ?>">x</button>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="group">
                        <div id="tabel_<?= $racikCounter ?>">
                            Nama Racikan :
                            <input class="namaRacikanFld racikanobat" name="nm_racikan[<?= $racikCounter ?>]" value="<?= $rc->namaRacik ?>"/>
                            <input class="noRacikanFld" name="no_racikan[]" value="<?= $racikCounter ?>" type="hidden"/>
                            <input class="kodeSignaRacikFld" name="kode_signa_racik[<?= $racikCounter ?>]" value="<?= $rc->signa ?>" type="hidden"/>
                            <input class="numeroFld" name="numero[<?= $racikCounter ?>]" placeholder="No" value="<?= $rc->noRacik ?>"/>
                            <input class="diskonRacikFld" name="diskonracik[<?= $racikCounter ?>]" placeholder="Diskon" value="<?= $rc->diskon ?>"/>
                            <input class="namaSignaRacikFld" name="nama_signa_racik[<?= $racikCounter ?>]" placeholder="Aturan Pakai" value="<?= $rc->signa ?>"/>

                            <br/>
                            <br/>

                            <!-- PEMBUNGKUS  -->

                            <table id="tabel_rac">
                                <tr class="baris">
                                    <td>Nama Obat</td>
                                    <td>Jumlah</td>
                                    <td>Kuantitas</td>
                                    <td>Satuan</td>
                                    <td>Harga</td>
                                    <td>Action</td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td>
                                        <input class="namaObatFld" name="obat-<?= $racikCounter ?>[]" value="<?= $rc->namaBarang ?>" placeholder="Nama Obat" data-no="<?= $racikCounter . $racikCounter . $j ?>"/>
                                        <input class="kodeObatFld" name="kode_obat-<?= $racikCounter ?>[]" value="<?= $rc->kodeObat ?>" type="hidden"/>
                                        <input class="kodeObatAwalRacikFld" name="kode_obat_awal_racik[<?= $racikCounter ?>][]" value="<?= $rc->kodeObat ?>" type="hidden"/>
                                        <input type="hidden" id="generik-<?= $racikCounter . $racikCounter . $j ?>"/>
                                    </td>
                                    <td>
                                        <input class="keteranganJumlahFld" name="ketjumlah-<?= $racikCounter ?>[]" value="<?= $rc->keteranganJumlah ?>" placeholder="Jumlah"/>
                                    </td>
                                    <td>
                                        <input class="kuantitasFld checktotal" name="qty-<?= $racikCounter ?>[]" value="<?= $rc->jumlahPenjualan ?>" placeholder="Qty"/>
                                    </td>
                                    <td>
                                        <input class="satuanFld" name="satuan-<?= $racikCounter ?>[]" value="<?= $rc->namaKemasan ?>" readonly />
                                    </td>
                                    <td>
                                        <input class="hargaSatuanFld listhargaobat" name="hargasatuan-<?= $racikCounter ?>[]" value="<?= $rc->harga ?>" id="harga_<?= $racikCounter . $racikCounter . $j ?>" readonly />
                                    </td>
                                    <td>
                                        <button class="racikanBtn" data-no="<?= $racikCounter ?>"></button>
                                    </td>
                                </tr>

                                <?php
                                $kodeRacik = $rc->kodeRacik;

                                $j++;
                                }
                                if ($z > 1) {
                                ?>
                                <tbody id="racik_<?= $racikCounter ?>"></tbody>
                            </table>
                            <button class="hapusTabelBtn" data-no="<?= $racikCounter ?>">x</button>
                        </div>
                    </div>
                    <?php } ?>

                </div>

                </div>

                <?php $error = form_error('keterangan'); ?>
                <div class="control-group <?= $error ? 'error' : '' ?>">
                    <label class="control-label">Catatan</label>
                    <div class="controls">
                        <textarea class="keteranganFld" name="keterangan"><?= $resep->keterangan ?></textarea>
                        <?= $error ?>
                    </div>
                </div>

                <div>
                    <input type="hidden" class="myinput mydiskon checktotal"/>
                    <input type="hidden" class="myinput pembungkus checktotal"/>
                    <input type="hidden" class="myinput jasapelayanan" value="<?= $resep->jasaPelayanan ?>"/>
                    <input type="hidden" class="myinput grandtotal" value="<?= $resep->total ?>"/>
                    <input type="hidden" class="totalpost submittoal" name="grandtotal" value="<?= $resep->total ?>"/>
                    <input type="hidden" class="othergrandtotal grandtotal" value="<?= $resep->total ?>"/>
                    <input type="hidden" class="otherjasapelayanan submitjasa" name="jasapelayanan" value="<?= $resep->jasaPelayanan ?>"/>
                    <input type="submit" class="buttonsubmit" name="submit" value="save"/>
                </div>
            </div>
        </td>
    </tr>
    </table>
</form>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
