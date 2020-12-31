<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\PenerimaanUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/index.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $editAccess,
        array  $deleteAccess,
        array  $auditAccess,
        string $dataUrl,
        string $deleteUrl,
        string $permissionUrl,
        string $viewWidgetId,
        string $viewLainnyaWidgetId,
        string $printWidgetId,
        string $tableRevisiWidgetId,

        string $formKonsinyasiWidgetId,

        string $formPenerimaanWidgetId,
        string $formPenerimaanBonusWidgetId,
        string $formPenerimaanLainnyaWidgetId,

        string $formRevisiPenerimaanWidgetId,
        string $formRevisiPenerimaanLainnyaWidgetId,

        string $formVerPenerimaanWidgetId,
        string $formVerPenerimaanLainnyaWidgetId,

        string $formVerGudangWidgetId,
        string $formVerGudangGasMedisWidgetId,
        string $formVerGudangLainnyaWidgetId,

        string $formVerRevisiGudangWidgetId,
        string $formVerRevisiGudangLainnyaWidgetId,

        string $formVerAkuntansiWidgetId,
        string $formVerAkuntansiLainnyaWidgetId,

        string $subjenisAnggaranSelect,
        string $caraBayarSelect,
        string $tipeDokumenBulanSelect,
        string $tahunSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.PenerimaanUi.Table {
    export interface FormFields {
        bulanAnggaran:        "?"|"??";
        tahunAnggaran:        "?"|"??";
        tanggalDokumen:       "?"|"??";
        verifikasiPenerimaan: "?"|"??";
        verifikasiGudang:     "?"|"??";
        verifikasiAkuntansi:  "?"|"??";
        noDokumen:            "?"|"??";
        fakturSuratJalan:     "?"|"??";
        noPo:                 "?"|"??";
        noSpk:                "?"|"??";
        namaPemasok:          "?"|"??";
        caraBayar:            "?"|"??";
        kodeJenis:            "?"|"??";
    }

    export interface TableFields {
        kode:                string;
        kodeRefKons:         string;
        verTerima:           string;
        verGudang:           string;
        verAkuntansi:        string;
        statusRevisi:        string;
        statusIzinRevisi:    string;
        tipeDokumen:         string;
        tanggalDokumen:      string;
        noDokumen:           string;
        noFaktur:            string;
        noSuratJalan:        string;
        noPo:                string;
        noSpk:               string;
        namaPemasok:         string;
        caraBayar:           string;
        kodeJenis:           string;
        bulanAwalAnggaran:   string;
        bulanAkhirAnggaran:  string;
        tahunAnggaran:       string;
        namaUserTerima:      string;
        verTanggalTerima:    string;
        namaUserGudang:      string;
        verTanggalGudang:    string;
        namaUserAkuntansi:   string;
        verTanggalAkuntansi: string;
        nilaiAkhir:          string;
        revisiKe:            string;
        updatedBy:           string;
        updatedTime:         string;
        verRevisi:           string;
        verRevTerima:        string;

        // TODO: fix controller:
        statusTabungGasMedis: "???";
    }
}
</script>

<!--suppress NestedConditionalExpressionJS -->
<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{edit: boolean, delete: boolean, audit: boolean}}
     */
    static getAccess(role) {
        const pool = {
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            delete: JSON.parse(`<?=json_encode($deleteAccess) ?>`),
            audit: JSON.parse(`<?= json_encode($auditAccess) ?>`),
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Penerimaan") ?>}
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
                        label: tlm.stringRegistry._<?= $h("Tanggal Dokumen") ?>,
                        input: {class: ".tanggalDokumenFld"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("No. Dokumen") ?>,
                        input: {class: ".noDokumenFld"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Faktur/Surat Jalan") ?>,
                        input: {class: ".fakturSuratJalanFld"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("No. PO") ?>,
                        input: {class: ".noPoFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. SPK") ?>,
                        input: {class: ".noSpkFld"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>,
                        input: {class: ".namaPemasokFld"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".caraBayarFld"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Kode Jenis") ?>,
                        select: {class: ".kodeJenisFld"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Bulan Anggaran") ?>,
                        select: {class: ".bulanAnggaranFld"},
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld"},
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi Penerimaan") ?>,
                        select: {
                            class: ".verifikasiPenerimaanFld",
                            option_1: {value: "", text: ""},
                            option_2: {value: 0, text: tlm.stringRegistry._<?= $h("Belum") ?>},
                            option_3: {value: 1, text: tlm.stringRegistry._<?= $h("Sudah") ?>},
                        }
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi Gudang") ?>,
                        select: {
                            class: ".verifikasiGudangFld",
                            option_1: {value: "", text: ""},
                            option_2: {value: 0, text: tlm.stringRegistry._<?= $h("Belum") ?>},
                            option_3: {value: 1, text: tlm.stringRegistry._<?= $h("Sudah") ?>},
                        }
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Verifikasi Akuntansi") ?>,
                        select: {
                            class: ".verifikasiAkuntansiFld",
                            option_1: {value: "", text: ""},
                            option_2: {value: 0, text: tlm.stringRegistry._<?= $h("Belum") ?>},
                            option_3: {value: 1, text: tlm.stringRegistry._<?= $h("Sudah") ?>},
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
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1:  {text: ""},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Tanggal Terima") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("No. Terima") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Faktur/Surat Jalan") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("DO Pemesanan") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("PL Pembelian") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Pemasok") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Cara Bayar") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Anggaran") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Ver. Terima") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("User Ver. Terima") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Tanggal Ver. Terima") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Ver. Gudang") ?>},
                        td_15: {text: tlm.stringRegistry._<?= $h("User Ver. Gudang") ?>},
                        td_16: {text: tlm.stringRegistry._<?= $h("Tanggal Ver. Gudang") ?>},
                        td_17: {text: tlm.stringRegistry._<?= $h("Ver. Akuntansi") ?>},
                        td_18: {text: tlm.stringRegistry._<?= $h("User Ver. Akuntansi") ?>},
                        td_19: {text: tlm.stringRegistry._<?= $h("Tanggal Ver. Akuntansi") ?>},
                        td_20: {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                        td_21: {text: tlm.stringRegistry._<?= $h("Revisi Ke-") ?>},
                        td_22: {text: tlm.stringRegistry._<?= $h("Status Revisi") ?>},
                        td_23: {text: tlm.stringRegistry._<?= $h("Updated User") ?>},
                        td_24: {text: tlm.stringRegistry._<?= $h("Updated") ?>},
                        td_25: {text: tlm.stringRegistry._<?= $h("Izin Revisi") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const {numToShortMonthName: nToS, stringRegistry: str} = tlm;
        const draw = spl.TableDrawer.drawButton;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const bulanAnggaranFld = divElm.querySelector(".bulanAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLSelectElement} */ const verifikasiPenerimaanFld = divElm.querySelector(".verifikasiPenerimaanFld");
        /** @type {HTMLSelectElement} */ const verifikasiGudangFld = divElm.querySelector(".verifikasiGudangFld");
        /** @type {HTMLSelectElement} */ const verifikasiAkuntansiFld = divElm.querySelector(".verifikasiAkuntansiFld");
        /** @type {HTMLInputElement} */  const noDokumenFld = divElm.querySelector(".noDokumenFld");
        /** @type {HTMLInputElement} */  const fakturSuratJalanFld = divElm.querySelector(".fakturSuratJalanFld");
        /** @type {HTMLInputElement} */  const noPoFld = divElm.querySelector(".noPoFld");
        /** @type {HTMLInputElement} */  const noSpkFld = divElm.querySelector(".noSpkFld");
        /** @type {HTMLInputElement} */  const namaPemasokFld = divElm.querySelector(".namaPemasokFld");
        /** @type {HTMLSelectElement} */ const caraBayarFld = divElm.querySelector(".caraBayarFld");
        /** @type {HTMLSelectElement} */ const kodeJenisFld = divElm.querySelector(".kodeJenisFld");

        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", caraBayarFld);
        tlm.app.registerSelect("_<?= $subjenisAnggaranSelect ?>", kodeJenisFld);
        tlm.app.registerSelect("_<?= $tipeDokumenBulanSelect ?>", bulanAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        this._selects.push(caraBayarFld, kodeJenisFld, bulanAnggaranFld, tahunAnggaranFld);

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Table.FormFields} data */
            loadData(data) {
                bulanAnggaranFld.value = data.bulanAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                verifikasiPenerimaanFld.value = data.verifikasiPenerimaan ?? "";
                verifikasiGudangFld.value = data.verifikasiGudang ?? "";
                verifikasiAkuntansiFld.value = data.verifikasiAkuntansi ?? "";
                noDokumenFld.value = data.noDokumen ?? "";
                fakturSuratJalanFld.value = data.fakturSuratJalan ?? "";
                noPoFld.value = data.noPo ?? "";
                noSpkFld.value = data.noSpk ?? "";
                namaPemasokFld.value = data.namaPemasok ?? "";
                caraBayarFld.value = data.caraBayar ?? "";
                kodeJenisFld.value = data.kodeJenis ?? "";
            },
            submit() {
                tableWgt.refresh({
                    query: {
                        bulanAnggaran: bulanAnggaranFld.value,
                        tahunAnggaran: tahunAnggaranFld.value,
                        tanggalDokumen: tanggalDokumenWgt.value,
                        verTerima: verifikasiPenerimaanFld.value,
                        verGudang: verifikasiGudangFld.value,
                        verAkuntansi: verifikasiAkuntansiFld.value,
                        noDokumen: noDokumenFld.value,
                        fakturSuratJalan: fakturSuratJalanFld.value,
                        noPo: noPoFld.value,
                        noSpk: noSpkFld.value,
                        namaPemasok: namaPemasokFld.value,
                        caraBayar: caraBayarFld.value,
                        kodeJenis: kodeJenisFld.value,
                    }
                });
            }
        });

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kode, noDokumen, kodeRefKons, verTerima, verGudang, verAkuntansi, statusRevisi, tipeDokumen, statusIzinRevisi} = item;

                    const deleteStr = str._<?= $h("Hapus Penerimaan")?>;
                    const editStr = str._<?= $h("Ubah Penerimaan")?>;
                    const revisiSpStr = str._<?= $h("Revisi Penerimaan berdasarkan Revisi SP/SPK/Kontrak")?>;
                    const revisiDokumenStr = str._<?= $h("Revisi Dokumen Penerimaan")?>;
                    const revisiJumlahStr = str._<?= $h("Revisi Jumlah(Ref. SP/SPK/Kontrak) dan Harga/Diskon/PPN ( COD/Sumbangan/Lainnya )")?>;
                    const viewPenerimaanStr = str._<?= $h("View Penerimaan")?>;

                    const buttonCls = verTerima == "1" ? "warning" : "primary";
                    const deleteVal = JSON.stringify({noDokumen, kode});
                    const editVal = JSON.stringify({kodeRefKons, tipeDokumen, kode});
                    const viewVal = JSON.stringify({tipeDokumen, kode});

                    const deleteBtn         = draw({class: ".deleteBtn",         type: "danger",  icon: "trash",  value: deleteVal, title: deleteStr});
                    const editBtn           = draw({class: ".editBtn",           type: buttonCls, icon: "pencil", value: editVal,   title: editStr});
                    const revisiSpBtn       = draw({class: ".revisiSpBtn",       type: "info",    icon: "pencil", value: kode,      title: revisiSpStr});
                    const revisiDokumenBtn  = draw({class: ".revisiDokumenBtn",  type: "info",    icon: "pencil", value: kode,      title: revisiDokumenStr});
                    const revisiJumlahBtn   = draw({class: ".revisiJumlahBtn",   type: "info",    icon: "pencil", value: kode,      title: revisiJumlahStr});
                    const viewPenerimaanBtn = draw({class: ".viewPenerimaanBtn", type: "info",    icon: "list",   value: viewVal,   title: viewPenerimaanStr});

                    const cetakan1Btn = draw({class: ".printBtn", type: "primary", text: "P1", value: JSON.stringify({kode, versi: 1}), title: str._<?= $h("Cetak BA Penerimaan Barang Medik")?>});
                    const cetakan2Btn = draw({class: ".printBtn", type: "primary", text: "P2", value: JSON.stringify({kode, versi: 2}), title: str._<?= $h("Cetak BA Penyerahan Barang Medik")?>});
                    const cetakan3Btn = draw({class: ".printBtn", type: "primary", text: "P3", value: JSON.stringify({kode, versi: 3}), title: str._<?= $h("Cetak Bukti Penerimaan")?>});
                    const cetakan4Btn = draw({class: ".printBtn", type: "primary", text: "P7", value: JSON.stringify({kode, versi: 7}), title: str._<?= $h("Cetak Bukti Penerimaan Tanpa Harga")?>});
                    const cetakan5Btn = draw({class: ".printBtn", type: "primary", text: "P4", value: JSON.stringify({kode, versi: 4}), title: str._<?= $h("Cetak Bukti Penyerahan")?>});
                    const cetakan6Btn = draw({class: ".printBtn", type: "primary", text: "P8", value: JSON.stringify({kode, versi: 8}), title: str._<?= $h("Cetak Bukti Penyerahan Tanpa Harga")?>});
                    const cetakan7Btn = draw({class: ".printBtn", type: "primary", text: "P5", value: JSON.stringify({kode, versi: 5}), title: str._<?= $h("Cetak Bukti Penyerahan (Kemasan Besar)")?>});
                    const cetakan8Btn = draw({class: ".printBtn", type: "primary", text: "P6", value: JSON.stringify({kode, versi: 6}), title: str._<?= $h("Cetak BA Penyerahan Tabung Gas Medis")?>});

                    return "" +
                        (verGudang == "1" ? "" : deleteBtn) +
                        (verGudang == "1" ? "" : editBtn) +
                        (verAkuntansi == "1" || statusRevisi == "0" || verGudang == "0" || tipeDokumen != "0" ? "" : revisiSpBtn) +
                        (verAkuntansi == "1" || statusRevisi == "0" || verGudang == "0" || statusIzinRevisi == "0" ? "" : revisiDokumenBtn) +
                        (verAkuntansi == "1" || statusRevisi == "0" || verGudang == "0" || statusIzinRevisi == "0" ? "" : revisiJumlahBtn) +
                        viewPenerimaanBtn + cetakan1Btn + cetakan2Btn + cetakan3Btn + cetakan4Btn + cetakan5Btn + cetakan6Btn + cetakan7Btn + cetakan8Btn;
                }},
                2: {field: "tanggalDokumen", formatter: tlm.datetimeFormatter},
                3: {field: "noDokumen"},
                4: {formatter(unused, item) {
                    const {noFaktur, noSuratJalan} = item;
                    return (noFaktur || "-") + " / " + (noSuratJalan || "-");
                }},
                5:  {field: "noPo"},
                6:  {field: "noSpk"},
                7:  {field: "namaPemasok"},
                8:  {field: "caraBayar"},
                9:  {field: "kodeJenis"},
                10: {formatter(unused, item) {
                    const {bulanAwalAnggaran: awal, bulanAkhirAnggaran: akhir, tahunAnggaran: tahun} = item;
                    return nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                }},
                11: {formatter(unused, item) {
                    const {kodeRefKons, tipeDokumen, kode, verTerima} = item;
                    return verTerima
                        ? str._<?= $h("Sudah") ?>
                        : draw({class: ".verifTerimaBtn", value: JSON.stringify({kodeRefKons, tipeDokumen, kode}), text: str._<?= $h("Belum") ?>});
                }},
                12: {field: "namaUserTerima"},
                13: {field: "verTanggalTerima", formatter: tlm.datetimeFormatter},
                14: {formatter(unused, item) {
                    const {kodeRefKons, tipeDokumen, kode, statusTabungGasMedis, verGudang, verTerima} = item;
                    if (verGudang)      return str._<?= $h("Sudah") ?>;
                    else if (verTerima) return draw({class: ".verifGudangBtn", value: JSON.stringify({kodeRefKons, tipeDokumen, kode, statusTabungGasMedis}), text: str._<?= $h("Belum") ?>});
                    else /*----------*/ return str._<?= $h("Belum") ?>;
                }},
                15: {field: "namaUserGudang"},
                16: {field: "verTanggalGudang", formatter: tlm.datetimeFormatter},
                17: {formatter(unused, item) {
                    const {kodeRefKons, tipeDokumen, verGudang, kode, verAkuntansi} = item;
                    if (verAkuntansi)   return str._<?= $h("Sudah") ?>;
                    else if (verGudang) return draw({class: ".verifAkuntansiBtn", value: JSON.stringify({kodeRefKons, kode, tipeDokumen}), text: str._<?= $h("Belum") ?>});
                    else /*----------*/ return str._<?= $h("Belum") ?>;
                }},
                18: {field: "namaUserAkuntansi"},
                19: {field: "verTanggalAkuntansi", formatter: tlm.datetimeFormatter},
                20: {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                21: {formatter(unused, {revisiKe}) {
                    const text = str._<?= $h("Revisi ke-{{REV}}") ?>.replace("{{REV}}", revisiKe);
                    const title = str._<?= $h("Silahkan Klik untuk melihat History Revisi PL ini") ?>;

                    return revisiKe
                        ? draw({class: ".btn-repair .btn-listrev", type: "primary", text, title})
                        : str._<?= $h("PL Asli") ?>;
                }},
                22: {formatter(unused, item) {
                    const {kode, statusRevisi, verRevTerima, verGudang, verRevisi, tipeDokumen} = item;
                    const title1 = str._<?= $h("Silahkan Klik untuk melihat History Revisi PL ini") ?>;
                    const title3 = str._<?= $h("Silahkan Klik untuk melakukan verifikasi revisi gudang") ?>;

                    const verRevisiVal = JSON.stringify({kode, tipeDokumen});

                    if      (statusRevisi == "1" && verRevTerima == "0" && verGudang == "0") return draw({class: ".listRevBtn",   type: "warning", value: kode,         text: str._<?= $h("Ada Revisi PL") ?>, title: title1});
                    else if (statusRevisi == "1" && verRevTerima == "0" /*---------------*/) return draw({class: ".btn-danger",   type: "warning",                      text: str._<?= $h("Belum Verif Penerima") ?>});
                    else if (statusRevisi == "1" && verRevTerima == "1" && verRevisi == "0") return draw({class: ".verRevisiBtn", type: "danger",  value: verRevisiVal, text: str._<?= $h("Belum Verif Gudang") ?>, title: title3});
                    else if (statusRevisi == "1" /*--------------------------------------*/) return draw({class: ".___",          type: "success",                      text: str._<?= $h("Selesai Revisi") ?>});
                    else    /*------------------------------------------------------------*/ return "-";
                }},
                23: {field: "updatedBy", visible: access.audit},
                24: {field: "updatedTime", visible: access.audit, formatter: tlm.datetimeFormatter},
                25: {formatter(unused, item) {
                    const {verGudang, verAkuntansi, statusIzinRevisi, statusRevisi, noDokumen} = item;
                    const title = str._<?= $h("Silahkan Klik untuk memberikan izin revisi kepada Tim Penerima") ?>;
                    return (verGudang && !verAkuntansi && !statusIzinRevisi && !statusRevisi)
                        ? draw({class: ".permissionBtn", type: "warning", icon: "check-square", value: noDokumen, title})
                        : "";
                }}
            }
        });

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        verifikasiPenerimaanFld.addEventListener("change", () => {
            const id = verifikasiPenerimaanFld.dataset.id;
            tableWgt.column(id).search(verifikasiPenerimaanFld.value).draw();
        });

        verifikasiGudangFld.addEventListener("change", () => {
            const id = verifikasiGudangFld.dataset.id;
            tableWgt.column(id).search(verifikasiGudangFld.value).draw();
        });

        verifikasiAkuntansiFld.addEventListener("change", () => {
            const id = verifikasiAkuntansiFld.dataset.id;
            tableWgt.column(id).search(verifikasiAkuntansiFld.value).draw();
        });

        bulanAnggaranFld.addEventListener("change", () => {
            const bln = bulanAnggaranFld.selectedOptions[0].getAttribute("data");
            const vbln = bulanAnggaranFld.value;

            tahunAnggaranFld.querySelectorAll("option").forEach(elm => {
                const thn = elm.getAttribute("data");
                elm.value = vbln ? `${bln}_${thn}` : thn;
            });
        });

        tahunAnggaranFld.addEventListener("change", () => {
            const thn = bulanAnggaranFld.selectedOptions[0].getAttribute("data");
            const vthn = tahunAnggaranFld.value;

            bulanAnggaranFld.querySelectorAll("option").forEach(elm => {
                const bln = elm.getAttribute("data");
                elm.value = vthn ? `${bln}_${thn}` : bln;
            });
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const viewPenerimaanBtn = event.target;
            if (!viewPenerimaanBtn.matches(".viewPenerimaanBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(viewPenerimaanBtn.value);
            const widgetId = tipeDokumen ? "_<?= $viewLainnyaWidgetId ?>" : "_<?= $viewWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode, revisiKe: 0}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const {kode, versi} = JSON.parse(printBtn.value);
            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode, versi}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const {kodeRefKons, tipeDokumen, kode} = JSON.parse(editBtn.value);
            let widgetId;

            if      (kodeRefKons)      widgetId = "_<?= $formKonsinyasiWidgetId ?>";
            else if (tipeDokumen == 0) widgetId = "_<?= $formPenerimaanWidgetId ?>";
            else if (tipeDokumen == 1) widgetId = "_<?= $formPenerimaanBonusWidgetId ?>";
            else    /*--------------*/ widgetId = "_<?= $formPenerimaanLainnyaWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;

            const {noDokumen, kode} = JSON.parse(deleteBtn.value);
            const confirmMsg = str._<?= $h("Apakah Anda yakin ingin menghapus penerimaan dengan no. {{NO_DOC}}") ?>;
            if (!confirm(confirmMsg.replace("{{NO_DOC}}", noDokumen))) return;

            $.post({
                url: "<?= $deleteUrl ?>",
                data: {kode, keterangan: noDokumen},
                success(data) {
                    data ? closest(deleteBtn, "tr").remove() : alert(str._<?= $h("Gagal hapus data.") ?>);
                }
            });
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const revisiSpBtn = event.target;
            if (!revisiSpBtn.matches(".revisiSpBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(revisiSpBtn.value);
            const widgetId = tipeDokumen ? "_<?= $formRevisiPenerimaanLainnyaWidgetId ?>" : "_<?= $formRevisiPenerimaanWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode, t: 0}, true); // "t" is for PenerimaanController::actionFormRevisiData
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const revisiDokumenBtn = event.target;
            if (!revisiDokumenBtn.matches(".revisiDokumenBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(revisiDokumenBtn.value);
            const widgetId = tipeDokumen ? "_<?= $formRevisiPenerimaanLainnyaWidgetId ?>" : "_<?= $formRevisiPenerimaanWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode, t: 1}, true); // "t" is for PenerimaanController::actionFormRevisiData
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const revisiJumlahBtn = event.target;
            if (!revisiJumlahBtn.matches(".revisiJumlahBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(revisiJumlahBtn.value);
            const widgetId = tipeDokumen ? "_<?= $formRevisiPenerimaanLainnyaWidgetId ?>" : "_<?= $formRevisiPenerimaanWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode, t: 2}, true); // "t" is for PenerimaanController::actionFormRevisiData
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const listRevBtn = event.target;
            if (!listRevBtn.matches(".listRevBtn")) return;

            const widget = tlm.app.getWidget("_<?= $tableRevisiWidgetId ?>");
            widget.show();
            widget.loadData({kode: listRevBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const verRevisiBtn = event.target;
            if (!verRevisiBtn.matches(".verRevisiBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(verRevisiBtn.value);
            const widgetId = tipeDokumen ? "_<?= $formVerRevisiGudangLainnyaWidgetId ?>" : "_<?= $formVerRevisiGudangWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const permissionBtn = event.target;
            if (!permissionBtn.matches(".permissionBtn")) return;

            const trElm = closest(permissionBtn, "tr");
            const idRow = tableWgt.row(trElm).index();

            const dt = `
                <h4>Apakah Anda yakin ingin memberikan Izin Revisi untuk Penerimaan dengan no. "${permissionBtn.value}"?</h4><br/>
                <p>Jika Ya, Silahkan isi Kolom Catatan atau Keterangan dibawah ini untuk Tim Penerima !!!</p>
                <input type="hidden" id="idrow_rev" name="idrow_rev" value="${idRow}"/>
                <textarea id="keterangan_rev" name="keterangan_rev" cols="65" rows="5"></textarea>
            `;

            divElm.querySelector(".modal").style.display = "block";
            divElm.querySelector(".modal-body").innerHTML = dt;
            divElm.querySelector(".modal-header").innerHTML = `<h5 style="color:#FFF">Perizinan untuk Revisi</h5>`;
            modalFooterElm.querySelector("#btn-pull").innerHTML = "Submit";
            divElm.querySelector("#modal-modul").modal("show");
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const verifGudangBtn = event.target;
            if (!verifGudangBtn.matches(".verifGudangBtn")) return;

            const {kodeRefKons, tipeDokumen, kode, statusTabungGasMedis} = JSON.parse(verifGudangBtn.value);
            let widgetId;

            if      (kodeRefKons)          widgetId = "_<?= $formKonsinyasiWidgetId ?>";
            else if (statusTabungGasMedis) widgetId = "_<?= $formVerGudangGasMedisWidgetId ?>";
            else if (tipeDokumen)          widgetId = "_<?= $formVerGudangLainnyaWidgetId ?>";
            else    /*------------------*/ widgetId = "_<?= $formVerGudangWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadProfile("verifikasiGudang", {kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const verifAkuntansiBtn = event.target;
            if (!verifAkuntansiBtn.matches(".verifAkuntansiBtn")) return;

            const {kodeRefKons, tipeDokumen, kode} = JSON.parse(verifAkuntansiBtn.value);
            let widgetId;

            if      (kodeRefKons) widgetId = "_<?= $formKonsinyasiWidgetId ?>";
            else if (tipeDokumen) widgetId = "_<?= $formVerAkuntansiLainnyaWidgetId ?>";
            else    /*---------*/ widgetId = "_<?= $formVerAkuntansiWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadProfile("verifikasiAkuntansi", {kode, tipeDokumen}, true); // "tipeDokumen" might not be needed for some form
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const verifTerimaBtn = event.target;
            if (!verifTerimaBtn.matches(".verifTerimaBtn")) return;

            const {kodeRefKons, tipeDokumen, kode} = JSON.parse(verifTerimaBtn.value);
            let widgetId;

            if      (kodeRefKons) widgetId = "_<?= $formKonsinyasiWidgetId ?>";
            else if (tipeDokumen) widgetId = "_<?= $formVerPenerimaanLainnyaWidgetId ?>";
            else    /*---------*/ widgetId = "_<?= $formVerPenerimaanWidgetId ?>";

            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadProfile("verifikasiTerima", {kode, tipeDokumen}, true); // "tipeDokumen" might not be needed for some form
        });

        // JUNK -----------------------------------------------------

        const modalFooterElm = divElm.querySelector(".modal-footer");

        modalFooterElm.addEventListener("click", (event) => {
            if (!event.target.matches("#btn-pull")) return;

            const ket = divElm.querySelector("#keterangan_rev").value;
            const idxRev = divElm.querySelector("#idrow_rev").value;
            const data = tableWgt.row(idxRev).data();

            $.post({
                url: "<?= $permissionUrl ?>",
                data: {kode: data.kode, keteranganRev: ket},
                success(data) {
                    if (data) {
                        tableWgt.cell(idxRev, 23).data("");
                        tableWgt.draw();
                    } else {
                        alert(str._<?= $h("Gagal memberikan izin revisi untuk penerimaan ini.") ?>);
                    }
                    divElm.querySelector("#modal-modul").style.display = "none";
                }
            });
        });

        modalFooterElm.addEventListener("click", (event) => {
            if (!event.target.matches("#btn-close")) return;
            divElm.querySelector("#modal-modul").style.display = "none";
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, tableWgt);
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
