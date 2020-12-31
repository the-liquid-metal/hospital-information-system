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
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Penerimaan/add.php the original file
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $addBonusAccess,
        array  $editAccess,
        array  $editBonusAccess,
        array  $verifikasiTerimaAccess,
        array  $verifikasiGudangAccess,
        array  $verifikasiAkuntansiAccess,
        string $editDataUrl,
        string $editBonusDataUrl,
        string $verifikasiTerimaDataUrl,
        string $verifikasiGudangDataUrl,
        string $verifikasiAkuntansiDataUrl,
        string $addActionUrl,
        string $addBonusActionUrl,
        string $editActionUrl,
        string $editBonusActionUrl,
        string $verifikasiTerimaActionUrl,
        string $verifikasiGudangActionUrl,
        string $verifikasiAkuntansiActionUrl,
        string $cekUnikNoFakturUrl,
        string $cekUnikNoSuratJalanUrl,
        string $pembelianAcplUrl,
        string $refPlPembelianUrl,
        string $penerimaanUrl,
        string $pemesananAcplUrl,
        string $cekUnikNoDokumenUrl,
        string $cekStokUrl,
        string $detailTerimaUrl,
        string $detailTerimaPemesananUrl,
        string $viewWidgetId,
        string $jenisAnggaranSelect,
        string $bulanSelect,
        string $tahunSelect,
        string $sumberDanaSelect,
        string $jenisHargaSelect,
        string $caraBayarSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();

        $no = 1;
        $nt = 0.00;
        $nd = 0.00;
        $nTd = 0.00;
        $xdata = [];

        $action ??= "";
        foreach ($daftarDetailPenerimaan ?? [] as $x => $dPenerimaan) {
            $hk = $dPenerimaan->hargaKemasan;
            $hi = $dPenerimaan->hargaItem;
            $di = $dPenerimaan->diskonItem;
            $jBeli = $dPenerimaan->jumlahItemBeli;
            $jBonus = $dPenerimaan->jumlahItemBonus;

            $jP = $dPenerimaan->jumlahPl;
            $jS = $dPenerimaan->jumlahDo;
            $jRt = $dPenerimaan->jumlahRetur;

            $jT = (strlen($dPenerimaan->kodeRefPo) == 0) ? $dPenerimaan->jumlahTerimaPl : $dPenerimaan->jumlahTerimaPo;

            if ($jS == 0) {
                $jMax = $jP - $jT + $jRt;
                $jB = $jP / $jBeli * $jBonus;

            } else {
                $jMax = $jS - $jT + $jRt;
                $jB = $jS / $jBeli * $jBonus;
            }

            if ($action == 'editbonus') {
                $jT = $dPenerimaan->jumlahTBonus;
                $jRt = $dPenerimaan->jumlahRtBonus;
                $hi = $dPenerimaan->bHargaItem;
                $hk = $dPenerimaan->bHargaKemasan;
                $di = $dPenerimaan->bDiskonItem;

                $jMax = $jB - $jT + $jRt;
            }

            $ht = $dPenerimaan->jumlahKemasan * $hk;
            $dh = $ht * $di / 100;
            $ha = $ht - $dh;

            $nt += $ht;
            $nd += $dh;
            $nTd += $ha;

            $xdata[$x] = [
                "jMax" => $jMax,
                "jT" => $jT,
                "jRt" => $jRt,
                "jB" => $jB,
                "ht" => $ht,
                "dh" => $dh,
                "ha" => $ha,
                "hk" => $hk,
                "di" => $di,
                "hi" => $hi,
            ];
        }

        $penerimaan = new \stdClass;
        $np = ($penerimaan->ppn == 10) ? $nTd * $penerimaan->ppn / 100 : 0.00;

        $nTp = $nTd + $np;
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.PenerimaanUi.Form {
    export interface FormFields {
        action:              "action",
        submit:              "submit",
        kode:                "kode",
        penerimaanKe:        "___", // exist but missing
        tanggalDokumen:      "tgl_doc",
        tanggalJatuhTempo:   "___", // exist but missing
        noDokumen:           "no_doc",
        idJenisAnggaran:     "id_jenisanggaran",
        noFaktur:            "no_faktur",
        noSuratJalan:        "no_suratjalan",
        bulanAwalAnggaran:   "blnawal_anggaran",
        bulanAkhirAnggaran:  "blnakhir_anggaran",
        tahunAnggaran:       "thn_anggaran",
        kodeRefPl:           "___", // exist but missing
        noSpk:               "no_spk",
        idSumberDana:        "id_sumberdana",
        kodeRefDo:           "___", // exist but missing
        idJenisHarga:        "id_jenisharga",
        idCaraBayar:         "id_carabayar",
        namaPemasok:         "___", // exist but missing
        idPemasok:           "id_pbf",
        idGudangPenyimpanan: "id_gudangpenyimpanan",
        tabungGas:           "sts_tabunggm",
        sebelumDiskon:       "___", // exist but missing
        diskon:              "___", // exist but missing
        setelahDiskon:       "___", // exist but missing
        ppn:                 "ppn",
        setelahPpn:          "___", // exist but missing
        pembulatan:          "___", // exist but missing
        nilaiAkhir:          "___", // exist but missing
    }

    export interface TableFields {
        kodeRefPo:         "kode_reffpo[]",
        kodeRefRo:         "kode_reffro",
        kodeRefPl:         "kode_reffpl",
        kodeRefRencana:    "kode_reffrenc",
        idRefKatalog:      "id_reffkatalog",
        idKatalog:         "id_katalog",
        idPabrik:          "id_pabrik",
        kemasan:           "kemasan",
        jumlahItemBonus:   "jumlah_itembonus",
        jumlahItemBeli:    "j_beli",
        noUrut:            "no_urut",
        idKemasanDepo:     "id_kemasandepo",
        namaSediaan:       "___", // exist but missing
        namaPabrik:        "___", // exist but missing
        idKemasan:         "id_kemasan[]",
        isiKemasan:        "isi_kemasan[]",
        noBatch:           "no_batch[]",
        tanggalKadaluarsa: "tgl_expired[]",
        jumlahKemasan:     "jumlah_kemasan[]",
        jumlahItem:        "___", // exist but missing
        hargaKemasan:      "harga_kemasan[]",
        hargaItem:         "___", // exist but missing
        diskonItem:        "diskon_item[]",
        hargaTotal:        "___", // exist but missing
        diskonHarga:       "___", // exist but missing
        hargaAkhir:        "___", // exist but missing
        jumlahRencana:     "___", // exist but missing
        jumlahHps:         "___", // exist but missing
        jumlahPl:          "___", // exist but missing
        jumlahDo:          "___", // exist but missing
        jumlahBonus:       "___", // exist but missing
        jumlahTerima:      "___", // exist but missing
        jumlahRetur:       "___", // exist but missing
        verTerima:         "ver_terima",
        verGudang:         "ver_gudang",
        verAkuntansi:      "ver_akuntansi",
    }

    export interface DataTerimaFields {
        idKatalog:     "id_katalog",
        idKemasan:     "id_kemasan",
        idKemasanDepo: "id_kemasandepo",
        isiKemasan:    "isi_kemasan",
        hargaKemasan:  "harga_kemasan",
        hargaItem:     "harga_item",
        diskonItem:    "diskon_item",
    }

    export interface DataRevisiFields {
        idPemasok:       "id_pbf",
        idJenisAnggaran: "id_jenisanggaran",
        idSumberDana:    "id_sumberdana",
        idJenisHarga:    "id_jenisharga",
        idCaraBayar:     "id_carabayar",
        ppn:             "ppn",
    }

    export interface KatalogFields {
        idKemasan:         "id_kemasan",
        namaSediaan:       "nama_sediaan",
        kemasan:           "kemasan",
        jumlahPl:          "jumlah_pl",
        jumlahDo:          "jumlah_do",
        jumlahTerima:      "jumlah_trm",
        jumlahRetur:       "jumlah_ret",
        jumlahItemBeli:    "jumlah_itembeli",
        jumlahItemBonus:   "jumlah_itembonus",
        jumlahItem:        "jumlah_item",
        jumlahTerimaBonus: "jumlah_tbonus",
        jumlahReturBonus:  "jumlah_rtbonus",
        hargaItem:         "harga_item",
        hargaKemasan:      "harga_kemasan",
        isiKemasan:        "isi_kemasan",
        diskonItem:        "diskon_item",
        satuan:            "satuan",
        satuanJual:        "satuanjual",
        idKemasanDepo:     "id_kemasandepo",
        idKatalog:         "id_katalog",
        kodeRefPo:         "kode_reffpo",
        kodeRefRo:         "kode_reffro",
        kodeRefPl:         "kode_reffpl",
        kodeRefRencana:    "kode_reffrenc",
        idPabrik:          "id_pabrik",
        namaPabrik:        "nama_pabrik",
        jumlahRencana:     "jumlah_renc",
        jumlahHps:         "jumlah_hps",
    }

    export interface Data1Fields {
        kodeRefPo:            "kode_reffpo",
        kodeRefPl:            "kode_reffpl",
        noPo:                 "no_po",
        noSpk:                "no_spk",
        tanggalMulai:         "tgl_mulai",
        tanggalJatuhTempo:    "tgl_jatuhtempo",
        idJenisAnggaran:      "id_jenisanggaran",
        idJenisHarga:         "id_jenisharga",
        idSumberDana:         "id_sumberdana",
        idCaraBayar:          "id_carabayar",
        idPemasok:            "id_pbf",
        kodePemasok:          "kode_pbf",
        namaPemasok:          "nama_pbf",
        ppn:                  "ppn",
        subjenisAnggaran:     "subjenis_anggaran",
        jenisHarga:           "jenis_harga",
        nilaiAkhirRo:         "nilai_akhir_ro",
        bulanAwalAnggaranPl:  "blnawal_anggaran_pl",
        bulanAkhirAnggaranPl: "blnakhir_anggaran_pl",
        tahunAnggaranPl:      "thn_anggaran_pl",
        nilaiAkhirPl:         "nilai_akhir_pl",
    }

    export interface RefPlFields {
        kode:               string;
        subjenisAnggaran:   string;
        bulanAwalAnggaran:  string;
        bulanAkhirAnggaran: string;
        tahunAnggaran:      string;
        noDokumen:          string;
        namaPemasok:        string;
        idJenisHarga:       string;
        idPemasok:          string;
        tipeDokumen:        string;
        id:                 "id",
        action:             "act",
        nilaiAkhir:         "nilai_akhir",
        tanggalDokumen:     "tgl_doc",
        idJenisAnggaran:    "id_jenisanggaran",
        idSumberDana:       "id_sumberdana",
        idSubsumberDana:    "id_subsumberdana",
        ppn:                "ppn",
        kodePemasok:        "kode_pbf",
        idCaraBayar:        "id_carabayar",
        jenisHarga:         "jenis_harga",
        tanggalJatuhTempo:  "tgl_jatuhtempo",
    }

    export interface PemasokFields {
        id:                 "id_pbf",
        subjenisAnggaran:   "subjenis_anggaran",
        bulanAwalAnggaran:  "blnawal_anggaran",
        bulanAkhirAnggaran: "blnakhir_anggaran",
        tahunAnggaran:      "thn_anggaran",
        nilaiAkhir:         "nilai_akhir",
        nama:               "nama_pbf",
        noDokumen:          "no_doc",
        kode:               "kode",
        tipeDokumen:        "tipe_doc",
        idJenisAnggaran:    "id_jenisanggaran",
        idSumberDana:       "id_sumberdana",
        idSubsumberDana:    "id_subsumberdana",
        idJenisHarga:       "id_jenisharga",
        idCaraBayar:        "id_carabayar",
        jenisHarga:         "jenis_harga",
        tanggalJatuhTempo:  "tgl_jatuhtempo",
        ppn:                "ppn",
    }

    export interface RefDoFields {
        kode:                 string;
        bulanAwalAnggaran:    string;
        bulanAkhirAnggaran:   string;
        tahunAnggaran:        string;
        nilaiAkhir:           string;
        noDokumen:            string;
        namaPemasok:          string;
        tanggalTempoKirim:    string; // not used, but exist in controller
        action:               "act",
        id:                   "id",
        subjenisAnggaran:     "subjenis_anggaran",
        jenisHarga:           "jenis_harga",
        noSpk:                "no_spk",
        kodeRefPl:            "kode_reffpl",
        tanggalMulai:         "tgl_mulai",
        tanggalJatuhTempo:    "tgl_jatuhtempo",
        idPemasok:            "id_pbf",
        kodePemasok:          "kode_pbf",
        idJenisAnggaran:      "id_jenisanggaran",
        idSumberDana:         "id_sumberdana",
        idJenisHarga:         "id_jenisharga",
        idCaraBayar:          "id_carabayar",
        ppn:                  "ppn",
        bulanAwalAnggaranPl:  "blnawal_anggaran_pl",
        bulanAkhirAnggaranPl: "blnakhir_anggaran_pl",
        tahunAnggaranPl:      "thn_anggaran_pl",
        nilaiAkhirPl:         "nilai_akhir_pl",
    }

    export interface StokTableFields {
        namaDepo:      string;
        jumlahStokAdm: string;
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{add: boolean, addBonus: boolean, edit: boolean, editBonus: boolean, verifikasiGudang: boolean, verifikasiAkuntansi: boolean}}
     */
    static getAccess(role) {
        const pool = {
            add: JSON.parse(`<?=json_encode($addAccess) ?>`),
            addBonus: JSON.parse(`<?=json_encode($addBonusAccess) ?>`),
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            editBonus: JSON.parse(`<?=json_encode($editBonusAccess) ?>`),
            verifikasiTerimaAccess: JSON.parse(`<?=json_encode($verifikasiTerimaAccess) ?>`),
            verifikasiGudang: JSON.parse(`<?=json_encode($verifikasiGudangAccess) ?>`),
            verifikasiAkuntansi: JSON.parse(`<?=json_encode($verifikasiAkuntansiAccess) ?>`),
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
                heading3: {class: ".formTitleTxt"}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".penerimaanFrm",
            row_1: {
                widthColumn: {
                    button_1: {class: ".revisiBtn", text: tlm.stringRegistry._<?= $h("Tarik Hasil") ?>, title: tlm.stringRegistry._<?= $h("Tarik Hasil Revisi SP/SPK/Kontrak atau Revisi DO/PO Pemesanan") ?>},
                    button_2: {class: ".tarikBtn",  text: tlm.stringRegistry._<?= $h("Tarik Item") ?>,  title: tlm.stringRegistry._<?= $h("Tarik Item Pembelian") ?>}
                }
            },
            row_2: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".actionFld", name: "action"}, // $action
                    hidden_2: {class: ".submitFld", name: "submit", value: "save"}, // FROM SUBMIT BUTTON   $bSave
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Penerimaan Ke-") ?>,
                        staticText: {class: ".penerimaanKeStc"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Penerimaan") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Jatuh Tempo") ?>,
                        input: {class: ".tanggalJatuhTempoFld"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("No. Penerimaan") ?>,
                        input: {class: ".noDokumenFld", name: "noDokumen"}
                    },
                    formGroup_6: {
                        label: tlm.stringRegistry._<?= $h("Mata Anggaran") ?>,
                        select: {class: ".idJenisAnggaranFld", name: "idJenisAnggaran"}
                    },
                    formGroup_7: {
                        label: tlm.stringRegistry._<?= $h("Faktur") ?>,
                        input: {class: ".noFakturFld", name: "noFaktur"}
                    },
                    formGroup_8: {
                        label: tlm.stringRegistry._<?= $h("Surat Jalan") ?>,
                        input: {class: ".noSuratJalanFld", name: "noSuratJalan"}
                    },
                    formGroup_9: {
                        label: tlm.stringRegistry._<?= $h("Bulan Awal Anggaran") ?>,
                        select: {class: ".bulanAwalAnggaranFld", name: "bulanAwalAnggaran"}
                    },
                    formGroup_10: {
                        label: tlm.stringRegistry._<?= $h("Bulan Akhir Anggaran") ?>,
                        select: {class: ".bulanAkhirAnggaranFld", name: "bulanAkhirAnggaran"}
                    },
                    formGroup_11: {
                        label: tlm.stringRegistry._<?= $h("Tahun Anggaran") ?>,
                        select: {class: ".tahunAnggaranFld", name: "tahunAnggaran"}
                    },
                    formGroup_12: {
                        label: tlm.stringRegistry._<?= $h("No. SP/SPK/Kontrak") ?>,
                        input: {class: ".kodeRefPlFld"},
                        hidden: {class: ".noSpkFld", name: "noSpk"}
                    },
                    formGroup_13: {
                        label: tlm.stringRegistry._<?= $h("Sumber Dana") ?>,
                        select: {class: ".idSumberDanaFld", name: "idSumberDana"}
                    },
                    formGroup_14: {
                        label: tlm.stringRegistry._<?= $h("Ref. Delivery Order") ?>,
                        input: {class: ".kodeRefDoFld"}
                    },
                    formGroup_15: {
                        label: tlm.stringRegistry._<?= $h("Jenis Harga") ?>,
                        select: {class: ".idJenisHargaFld", name: "idJenisHarga"}
                    },
                    formGroup_16: {
                        label: tlm.stringRegistry._<?= $h("Cara Bayar") ?>,
                        select: {class: ".idCaraBayarFld", name: "idCaraBayar"}
                    },
                    formGroup_17: {
                        label: tlm.stringRegistry._<?= $h("Pemasok") ?>,
                        input: {class: ".namaPemasokFld"},
                        hidden: {class: ".idPemasokFld", name: "idPemasok"}
                    },
                    formGroup_18: {
                        label: tlm.stringRegistry._<?= $h("Penyimpanan") ?>,
                        select: {
                            class: ".idGudangPenyimpananFld",
                            name: "idGudangPenyimpanan",
                            option_1: {value: 59, label: tlm.stringRegistry._<?= $h("Gudang Induk Farmasi") ?>},
                            option_2: {value: 60, label: tlm.stringRegistry._<?= $h("Gudang Gas Medis") ?>},
                            option_3: {value: 69, label: tlm.stringRegistry._<?= $h("Gudang Konsinyasi") ?>}
                        }
                    },
                    formGroup_19: {
                        label: tlm.stringRegistry._<?= $h("Tabung Gas") ?>,
                        radio_1: {class: ".tabungGasFld .tabungGasYes", name: "tabungGas", value: 1, label: tlm.stringRegistry._<?= $h("Ya") ?>},
                        radio_2: {class: ".tabungGasFld .tabungGasNo",  name: "tabungGas", value: 0, label: tlm.stringRegistry._<?= $h("Tidak") ?>}
                    },
                    formGroup_20: {
                        label: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>,
                        staticText: {class: ".sebelumDiskonStc"}
                    },
                    formGroup_21: {
                        label: tlm.stringRegistry._<?= $h("Diskon") ?>,
                        staticText: {class: ".diskonStc"}
                    },
                    formGroup_22: {
                        label: tlm.stringRegistry._<?= $h("Setelah Diskon") ?>,
                        staticText: {class: ".setelahDiskonStc"}
                    },
                    formGroup_23: {
                        label: tlm.stringRegistry._<?= $h("PPN") ?>,
                        checkbox: {class: ".ppnFld", name: "ppn"},
                        staticText: {class: ".ppnStc"}
                    },
                    formGroup_24: {
                        label: tlm.stringRegistry._<?= $h("Setelah PPN") ?>,
                        staticText: {class: ".setelahPpnStc"}
                    },
                    formGroup_25: {
                        label: tlm.stringRegistry._<?= $h("Pembulatan") ?>,
                        staticText: {class: ".pembulatanStc"}
                    },
                    formGroup_26: {
                        label: tlm.stringRegistry._<?= $h("Nilai Akhir") ?>,
                        staticText: {class: ".nilaiAkhirStc"}
                    }
                }
            },
            row_3: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr_1: {
                            td_1:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_3:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_4:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Isi") ?>},
                            td_6:  {colspan: 5, text: tlm.stringRegistry._<?= $h("Pengadaan") ?>},
                            td_7:  {colspan: 2, text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_8:  {rowspan: 2, text: tlm.stringRegistry._<?= $h("Diskon (%)") ?>},
                            td_9:  {colspan: 3, text: tlm.stringRegistry._<?= $h("Total") ?>},
                            td_10: {colspan: 6, text: tlm.stringRegistry._<?= $h("Realisasi") ?>}
                        },
                        tr_2: {
                            td_1:  {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2:  {text: tlm.stringRegistry._<?= $h("Batch") ?>},
                            td_3:  {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            td_4:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_5:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_6:  {text: tlm.stringRegistry._<?= $h("Kemasan") ?>},
                            td_7:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_8:  {text: tlm.stringRegistry._<?= $h("Sebelum Diskon") ?>},
                            td_9:  {text: tlm.stringRegistry._<?= $h("Diskon") ?>},
                            td_10: {text: tlm.stringRegistry._<?= $h("Rp.") ?>},
                            td_11: {text: tlm.stringRegistry._<?= $h("Rencana") ?>},
                            td_12: {text: tlm.stringRegistry._<?= $h("HPS") ?>},
                            td_13: {text: tlm.stringRegistry._<?= $h("SPK") ?>},
                            td_14: {text: tlm.stringRegistry._<?= $h("DO") ?>},
                            td_15: {text: tlm.stringRegistry._<?= $h("Bonus") ?>},
                            td_16: {text: tlm.stringRegistry._<?= $h("Terima") ?>},
                            td_17: {text: tlm.stringRegistry._<?= $h("Retur") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".kodeRefPoFld", name: "kodeRefPo[]"},
                                hidden_2: {class: ".kodeRefRoFld", name: "kodeRefRo"},
                                hidden_3: {class: ".kodeRefPlFld", name: "kodeRefPl"},
                                hidden_4: {class: ".kodeRefRencanaFld", name: "kodeRefRencana"},
                                hidden_5: {class: ".idRefKatalogFld", name: "idRefKatalog"},
                                hidden_6: {class: ".idKatalogFld", name: "idKatalog"},
                                hidden_7: {class: ".idPabrikFld", name: "idPabrik"},
                                hidden_8: {class: ".kemasanFld", name: "kemasan"},
                                hidden_9: {class: ".jumlahItemBonusFld", name: "jumlahItemBonus"},
                                hidden_10: {class: ".jumlahItemBeliFld", name: "jumlahItemBeli"},
                                hidden_11: {class: ".jumlahItemBonusFld", name: "j_bonus"},
                                hidden_12: {class: ".noUrutFld", name: "noUrut"},
                                hidden_13: {class: ".idKemasanDepoFld", name: "idKemasanDepo"},
                                staticText: {class: ".no"}
                            },
                            td_2: {
                                staticText: {class: ".namaSediaanStc"},
                                rButton: {class: ".stokBtn"}
                            },
                            td_3: {class: ".namaPabrikStc"},
                            td_4: {
                                select: {class: ".idKemasanFld", name: "idKemasan[]"}
                            },
                            td_5: {
                                input: {class: ".isiKemasanFld", name: "isiKemasan[]"}
                            },
                            td_6: {
                                staticText: {class: ".noUrutStc"}
                            },
                            td_7: {
                                input: {class: ".noBatchFld", name: "noBatch[]"}
                            },
                            td_8: {
                                input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                            },
                            td_9: {
                                input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]"}
                            },
                            td_10: {class: ".jumlahItemStc"},
                            td_11: {
                                input: {class: ".hargaKemasanFld", name: "hargaKemasan[]"}
                            },
                            td_12: {class: ".hargaItemStc"},
                            td_13: {
                                input: {class: ".diskonItemFld", name: "diskonItem[]"}
                            },
                            td_14: {class: ".hargaTotalStc"},
                            td_15: {class: ".diskonHargaStc"},
                            td_17: {class: ".hargaAkhirStc"},
                            td_18: {class: ".jumlahRencanaStc"},
                            td_19: {class: ".jumlahHpsStc"},
                            td_20: {class: ".jumlahPlStc"},
                            td_21: {class: ".jumlahDoStc"},
                            td_22: {class: ".jumlahBonusStc"},
                            td_23: {class: ".jumlahTerimaStc"},
                            td_24: {class: ".jumlahReturStc"},
                            td_25: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {class: "text-right", text: tlm.stringRegistry._<?= $h("TOTAL:") ?>, colspan: 8},
                            td_2: {class: ".grandTotalStc"},
                            td_3: {text: tlm.stringRegistry._<?= $h("Cari Katalog:") ?>},
                            td_4: {
                                select: {class: ".cariKatalogFld"}
                            },
                            td_5: {
                                button: {class: ".addRowBtn", type: "success", size: "xs", label: tlm.stringRegistry._<?= $h("add") ?>}
                            }
                        }
                    }
                }
            },
            row_4: {
                widthTable: {
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("Ver No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Ver") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Otorisasi") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("User") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Update Stok") ?>},
                        }
                    },
                    tbody: {
                        tr_1: {
                            td_1: {text: "1"},
                            td_2: {
                                checkbox: {class: ".verTerimaFld", name: "verTerima", value: 1} // ($penerimaan->verTerima == '1') ? "checked disabled" : ""
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Tim Penerima") ?>},
                            td_4: {class: ".userTerimaStc"},                                       // $penerimaan->userTerima ?? "-"
                            td_5: {class: ".tanggalTerimaStc"},                                    // $penerimaan->verTanggalTerima ? $toUserDatetime($penerimaan->verTanggalTerima) : "-"
                            td_6: {text: ""},
                        },
                        tr_2: {
                            td_1: {text: "2"},
                            td_2: {
                                checkbox: {class: ".verGudangFld", name: "verGudang", value: 1} // ($penerimaan->verGudang == '1') ? "checked" : "disabled"
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Gudang") ?>},
                            td_4: {class: ".userGudangStc"},                                     // $penerimaan->userGudang ?? "-"
                            td_5: {class: ".tanggalGudangStc"},                                  // $penerimaan->verTanggalGudang ? $toUserDatetime($penerimaan->verTanggalGudang) : "-"
                            td_6: {
                                checkbox: {class: ".updateStokMarkerFld", value: 1}              // ($penerimaan->verGudang == '1') ? "checked" : "disabled"
                            },
                        },
                        tr_3: {
                            td_1: {text: "3"},
                            td_2: {
                                checkbox: {class: ".verAkuntansiFld", name: "verAkuntansi", value: 1} // ($penerimaan->verAkuntansi == '1') ? "checked" : "disabled"
                            },
                            td_3: {text: tlm.stringRegistry._<?= $h("Akuntansi") ?>},
                            td_4: {class: ".userAkuntansiStc"},                                        // $penerimaan->userAkuntansi ?? "-"
                            td_5: {class: ".tanggalAkuntansiStc"},                                     // $penerimaan->verTanggalAkuntansi ? $toUserDatetime($penerimaan->verTanggalAkuntansi) : "-"
                            td_6: {text: ""},
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
        modal: {
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
        const {toUserInt: userInt, toUserFloat: userFloat, toCurrency: currency, preferInt} = tlm;
        const {toSystemNumber: sysNum, numToShortMonthName: nToS, stringRegistry: str, nowVal} = tlm;
        const userName = tlm.user.nama;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLDivElement} */    const formTitleTxt = divElm.querySelector(".formTitleTxt");
        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const submitFld = divElm.querySelector(".submitFld");
        /** @type {HTMLInputElement} */  const revisiBtn = divElm.querySelector(".revisiBtn");
        /** @type {HTMLInputElement} */  const tarikBtn = divElm.querySelector(".tarikBtn");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLDivElement} */    const penerimaanKeStc = divElm.querySelector(".penerimaanKeStc");
        /** @type {HTMLInputElement} */  const tanggalJatuhTempoFld = divElm.querySelector(".tanggalJatuhTempoFld");
        /** @type {HTMLSelectElement} */ const idJenisAnggaranFld = divElm.querySelector(".idJenisAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAwalAnggaranFld = divElm.querySelector(".bulanAwalAnggaranFld");
        /** @type {HTMLSelectElement} */ const bulanAkhirAnggaranFld = divElm.querySelector(".bulanAkhirAnggaranFld");
        /** @type {HTMLSelectElement} */ const tahunAnggaranFld = divElm.querySelector(".tahunAnggaranFld");
        /** @type {HTMLInputElement} */  const noSpkFld = divElm.querySelector(".noSpkFld");
        /** @type {HTMLSelectElement} */ const idSumberDanaFld = divElm.querySelector(".idSumberDanaFld");
        /** @type {HTMLSelectElement} */ const idJenisHargaFld = divElm.querySelector(".idJenisHargaFld");
        /** @type {HTMLSelectElement} */ const idCaraBayarFld = divElm.querySelector(".idCaraBayarFld");
        /** @type {HTMLInputElement} */  const idPemasokFld = divElm.querySelector(".idPemasokFld");
        /** @type {HTMLSelectElement} */ const idGudangPenyimpananFld = divElm.querySelector(".idGudangPenyimpananFld");
        /** @type {HTMLInputElement} */  const tabungGasYes = divElm.querySelector(".tabungGasYes");
        /** @type {HTMLInputElement} */  const tabungGasNo = divElm.querySelector(".tabungGasNo");
        /** @type {HTMLDivElement} */    const sebelumDiskonStc = divElm.querySelector(".sebelumDiskonStc");
        /** @type {HTMLDivElement} */    const diskonStc = divElm.querySelector(".diskonStc");
        /** @type {HTMLDivElement} */    const setelahDiskonStc = divElm.querySelector(".setelahDiskonStc");
        /** @type {HTMLInputElement} */  const ppnFld = divElm.querySelector(".ppnFld");
        /** @type {HTMLDivElement} */    const ppnStc = divElm.querySelector(".ppnStc");
        /** @type {HTMLDivElement} */    const setelahPpnStc = divElm.querySelector(".setelahPpnStc");
        /** @type {HTMLDivElement} */    const pembulatanStc = divElm.querySelector(".pembulatanStc");
        /** @type {HTMLDivElement} */    const nilaiAkhirStc = divElm.querySelector(".nilaiAkhirStc");
        /** @type {HTMLInputElement} */  const updateStokMarkerFld = divElm.querySelector(".updateStokMarkerFld");
        /** @type {HTMLInputElement} */  const verGudangFld = divElm.querySelector(".verGudangFld");
        /** @type {HTMLInputElement} */  const verTerimaFld = divElm.querySelector(".verTerimaFld");
        /** @type {HTMLInputElement} */  const verAkuntansiFld = divElm.querySelector(".verAkuntansiFld");
        /** @type {HTMLInputElement} */  const userGudangStc = divElm.querySelector(".userGudangStc");
        /** @type {HTMLInputElement} */  const userTerimaStc = divElm.querySelector(".userTerimaStc");
        /** @type {HTMLInputElement} */  const userAkuntansiStc = divElm.querySelector(".userAkuntansiStc");
        /** @type {HTMLInputElement} */  const tanggalGudangStc = divElm.querySelector(".tanggalGudangStc");
        /** @type {HTMLInputElement} */  const tanggalTerimaStc = divElm.querySelector(".tanggalTerimaStc");
        /** @type {HTMLInputElement} */  const tanggalAkuntansiStc = divElm.querySelector(".tanggalAkuntansiStc");
        /** @type {HTMLDivElement} */    const headerElm = divElm.querySelector(".headerElm");
        /** @type {HTMLDivElement} */    const footerElm = divElm.querySelector(".footerElm");

        tlm.app.registerSelect("_<?= $jenisAnggaranSelect ?>", idJenisAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAwalAnggaranFld);
        tlm.app.registerSelect("_<?= $bulanSelect ?>", bulanAkhirAnggaranFld);
        tlm.app.registerSelect("_<?= $tahunSelect ?>", tahunAnggaranFld);
        tlm.app.registerSelect("_<?= $sumberDanaSelect ?>", idSumberDanaFld);
        tlm.app.registerSelect("_<?= $jenisHargaSelect ?>", idJenisHargaFld);
        tlm.app.registerSelect("_<?= $caraBayarSelect ?>", idCaraBayarFld);
        this._selects.push(idJenisAnggaranFld, bulanAwalAnggaranFld, bulanAkhirAnggaranFld);
        this._selects.push(tahunAnggaranFld, idSumberDanaFld, idJenisHargaFld, idCaraBayarFld);

        const penerimaanWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".penerimaanFrm"),
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.FormFields} data */
            loadData(data) {
                actionFld.value = data.action ?? "";
                submitFld.value = data.submit ?? "";
                kodeFld.value = data.kode ?? "";
                penerimaanKeStc.innerHTML = data.penerimaanKe ?? "";
                tanggalDokumenWgt.value = data.tanggalDokumen ?? "";
                tanggalJatuhTempoFld.value = data.tanggalJatuhTempo ?? "";
                noDokumenWgt.value = data.noDokumen ?? "";
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                noFakturWgt.value = data.noFaktur ?? "";
                noSuratJalanWgt.value = data.noSuratJalan ?? "";
                bulanAwalAnggaranFld.value = data.bulanAwalAnggaran ?? "";
                bulanAkhirAnggaranFld.value = data.bulanAkhirAnggaran ?? "";
                tahunAnggaranFld.value = data.tahunAnggaran ?? "";
                kodeRefPlWgt.value = data.kodeRefPl ?? "";
                noSpkFld.value = data.noSpk ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                kodeRefDoWgt.value = data.kodeRefDo ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                idCaraBayarFld.value = data.idCaraBayar ?? "";
                namaPemasokWgt.value = data.namaPemasok ?? "";
                idPemasokFld.value = data.idPemasok ?? "";
                idGudangPenyimpananFld.value = data.idGudangPenyimpanan ?? "";
                data.tabungGas ? tabungGasYes.checked = true : tabungGasNo.checked = true;
                sebelumDiskonStc.innerHTML = data.sebelumDiskon ?? "";
                diskonStc.innerHTML = data.diskon ?? "";
                setelahDiskonStc.innerHTML = data.setelahDiskon ?? "";
                ppnFld.checked = data.ppn == 10;
                ppnStc.innerHTML = data.ppn ?? "";
                setelahPpnStc.innerHTML = data.setelahPpn ?? "";
                pembulatanStc.innerHTML = data.pembulatan ?? "";
                nilaiAkhirStc.innerHTML = data.nilaiAkhir ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this._actionUrl = "<?= $addActionUrl ?>";
                    this.load({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Penerimaan Berdasarkan SP/SPK/Kontrak") ?>;

                    revisiBtn.disabled = true;
                    // $kode = $penerimaan->kode ?? "";
                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    // $bDelete = '';
                    // $bPrint = 'disabled';
                    // $bSave = 'disabled';
                    tarikBtn.disabled = false;
                    // $bTarikBy = '';
                    // $cekAll = '';
                    // $item = 'disabled';
                    kodeRefDoWgt.enable();
                    noDokumenWgt.currentState = {disabled: false};
                    noFakturWgt.disabled = false;
                    noSuratJalanWgt.disabled = false;
                    tanggalDokumenWgt.currentState = {disabled: false};
                    // $cStatusTabungGasMedis = $stsTabungGasMedis ? 'checked' : "";
                },
                /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.FormFields} data */
                addBonus() {
                    this._actionUrl = "<?= $addBonusActionUrl ?>";
                    this.load({});
                    formTitleTxt.innerHTML = str._<?= $h("Tambah Penerimaan Bonus Pembelian") ?>;

                    revisiBtn.disabled = true;
                    // $kode = $penerimaan->kode ?? "";
                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    // $bDelete = '';
                    // $bPrint = 'disabled';
                    // $bSave = 'disabled';
                    tarikBtn.disabled = false;
                    // $bTarikBy = '';
                    // $cekAll = '';
                    // $item = 'disabled';
                    kodeRefDoWgt.enable();
                    noDokumenWgt.currentState = {disabled: false};
                    noFakturWgt.disabled = false;
                    noSuratJalanWgt.disabled = false;
                    tanggalDokumenWgt.currentState = {disabled: false};
                    // $cStatusTabungGasMedis = $stsTabungGasMedis ? 'checked' : "";
                },
                /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.FormFields} data */
                edit(data) {
                    this._dataUrl = "<?= $editDataUrl ?>";
                    this._actionUrl = "<?= $editActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("Ubah Penerimaan Berdasarkan SP/SPK/Kontrak") ?>;

                    revisiBtn.disabled = true;
                    // $kode = $penerimaan->kode ?? "";
                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    // $bDelete = '';
                    // $bPrint = '';
                    // $bSave = '';
                    tarikBtn.disabled = false;
                    // $bTarikBy = "disabled";
                    // $cekAll = '';
                    // $item = '';
                    kodeRefDoWgt.disable();
                    noDokumenWgt.currentState = {disabled: false};
                    noFakturWgt.disabled = false;
                    noSuratJalanWgt.disabled = false;
                    tanggalDokumenWgt.currentState = {disabled: false};
                    //
                    // if ($penerimaan->statusRevisi == '1') {
                    //     revisiBtn.disabled = false;
                    // }
                    // $cStatusTabungGasMedis = $stsTabungGasMedis ? 'checked' : "";

                    if (data.stsTabungGasMedis == "1") {
                        idJenisAnggaranFld.value = "6";
                        idGudangPenyimpananFld.value = "60";
                    }

                    if (data.penerimaan) {
                        /** @type {his.FatmaPharmacy.views.PenerimaanUi.Form.Data1Fields} */
                        data = data.penerimaan;
                        const tarikBy = divElm.querySelector("[name=tarik_by]");

                        if (data.kodeRefPo && data.objDo) {
                            kodeRefDoWgt.addOption(data.objDo);
                            kodeRefDoWgt.value = data.objDo.kode;
                            tarikBy.value = "do";

                        } else if (data.objSpk) {
                            kodeRefPlWgt.addOption(data.objSpk);
                            kodeRefPlWgt.value = data.objSpk.kode;
                            tarikBy.value = "spk";
                        }
                    }
                },
                editBonus(data) {
                    this._dataUrl = "<?= $editBonusDataUrl ?>";
                    this._actionUrl = "<?= $editBonusActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("Ubah Penerimaan Bonus Pembelian") ?>;

                    revisiBtn.disabled = true;
                    // $kode = $penerimaan->kode ?? "";
                    bulanAwalAnggaranFld.disabled = false;
                    bulanAkhirAnggaranFld.disabled = false;
                    tahunAnggaranFld.disabled = false;
                    // $bDelete = '';
                    // $bPrint = 'disabled';
                    // $bSave = '';
                    tarikBtn.disabled = false;
                    // $bTarikBy = "disabled";
                    // $cekAll = '';
                    // $item = '';
                    kodeRefDoWgt.disable();
                    noDokumenWgt.currentState = {disabled: false};
                    noFakturWgt.disabled = false;
                    noSuratJalanWgt.disabled = false;
                    tanggalDokumenWgt.currentState = {disabled: false};
                    //
                    // if ($penerimaan->ppnBonus == 10) {
                    //     $cekPpn = 'checked';
                    // }
                    // $cStatusTabungGasMedis = $stsTabungGasMedis ? 'checked' : "";
                },
                verifikasiTerimaDataUrl(data) {
                    this._dataUrl = "<?= $verifikasiTerimaDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiTerimaActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;
                }
                verifikasiGudang(data) {
                    this._dataUrl = "<?= $verifikasiGudangDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiGudangActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    revisiBtn.disabled = true;
                    // $kode = $penerimaan->kode ?? "";
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    // $bDelete = 'disabled';
                    // $bPrint = '';
                    // $bSave = '';
                    tarikBtn.disabled = true;
                    // $bTarikBy = "disabled";
                    // $cekAll = 'disabled';
                    // $item = 'disabled';
                    kodeRefDoWgt.disable();
                    noDokumenWgt.currentState = {disabled: true};
                    noFakturWgt.disabled = true;
                    noSuratJalanWgt.disabled = true;
                    tanggalDokumenWgt.currentState = {disabled: true};
                    // $cStatusTabungGasMedis = $stsTabungGasMedis ? 'checked' : "";
                },
                verifikasiAkuntansi(data) {
                    this._dataUrl = "<?= $verifikasiAkuntansiDataUrl ?>";
                    this._actionUrl = "<?= $verifikasiAkuntansiActionUrl ?>";
                    this.load(data);
                    formTitleTxt.innerHTML = str._<?= $h("___") ?>;

                    revisiBtn.disabled = true;
                    // $kode = $penerimaan->kode ?? "";
                    bulanAwalAnggaranFld.disabled = true;
                    bulanAkhirAnggaranFld.disabled = true;
                    tahunAnggaranFld.disabled = true;
                    // $bDelete = 'disabled';
                    // $bPrint = '';
                    // $bSave = '';
                    tarikBtn.disabled = true;
                    // $bTarikBy = "disabled";
                    // $cekAll = 'disabled';
                    // $item = 'disabled';
                    kodeRefDoWgt.disable();
                    noDokumenWgt.currentState = {disabled: true};
                    noFakturWgt.disabled = true;
                    noSuratJalanWgt.disabled = true;
                    tanggalDokumenWgt.currentState = {disabled: true};
                    // $cStatusTabungGasMedis = $stsTabungGasMedis ? 'checked' : "";
                },
            },
            onInit() {
                this.loadProfile("edit");
            },
            onSuccessSubmit() {
                const widget = tlm.app.getWidget("_<?= $viewWidgetId ?>");
                widget.show();
                widget.loadData({kode: kodeFld.value, revisiKe: 0}, true);
            },
            resetBtnId: false,
        });

        /** @param {HTMLTableRowElement} trElm */
        function hitungSubTotal(trElm) {
            const fields = trElm.fields;
            const jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const hargaKemasan = sysNum(fields.hargaKemasanWgt.value);
            const diskonItem = sysNum(fields.diskonItemWgt.value);
            const jumlahItem = jumlahKemasan * isiKemasan;
            const hargaItem = hargaKemasan / isiKemasan;
            const hargaTotal = jumlahKemasan * hargaKemasan;
            const diskonHarga = hargaTotal * diskonItem / 100;
            const hargaAkhir = hargaTotal - diskonHarga;

            fields.jumlahItemStc.innerHTML = preferInt(jumlahItem);
            fields.hargaItemStc.innerHTML = currency(hargaItem);
            fields.hargaTotalStc.innerHTML = currency(hargaTotal);
            fields.diskonHargaStc.innerHTML = currency(diskonHarga);
            fields.hargaAkhirStc.innerHTML = currency(hargaAkhir);
        }

        function hitungTotal() {
            let sebelumDiskon = 0;
            let nilaiDiskon = 0;
            itemWgt.querySelectorAll("tbody tr").forEach(/** @param {HTMLTableRowElement} trElm */ trElm => {
                const fields = trElm.fields;
                sebelumDiskon += sysNum(fields.hargaTotalStc.innerHTML);
                nilaiDiskon += sysNum(fields.diskonHargaStc.innerHTML);
            });

            const setelahDiskon = sebelumDiskon - nilaiDiskon;
            const ppn = ppnFld.checked ? 10 : 0;
            const nilaiPpn = ppn * setelahDiskon / 100;
            const setelahPpn = setelahDiskon + nilaiPpn;
            const nilaiAkhir = Math.floor(setelahPpn);
            const pembulatan = nilaiAkhir - setelahPpn;

            sebelumDiskonStc.innerHTML = currency(sebelumDiskon);
            diskonStc.innerHTML = currency(nilaiDiskon);
            setelahDiskonStc.innerHTML = currency(setelahDiskon);
            ppnStc.innerHTML = currency(nilaiPpn);
            setelahPpnStc.innerHTML = currency(setelahPpn);
            pembulatanStc.innerHTML = currency(pembulatan);
            nilaiAkhirStc.innerHTML = currency(nilaiAkhir);
        }

        const tanggalDokumenWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalDokumenFld"),
            errorRules: [{required: true}],
            ...tlm.dateWidgetSetting
        });

        const noFakturWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noFakturFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == kodeFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoFakturUrl ?>",
            term: "value",
            additionalData: {field: "no_faktur"}
        });

        const noSuratJalanWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noSuratJalanFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == kodeFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoSuratJalanUrl ?>",
            term: "value",
            additionalData: {field: "no_suratjalan"}
        });

        const kodeRefPlWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefPlFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /**
             * @param formElm
             * @param {his.FatmaPharmacy.views.PenerimaanUi.Form.RefPlFields} data
             */
            assignPairs(formElm, data) {
                idJenisAnggaranFld.value = data.idJenisAnggaran ?? "";
                idSumberDanaFld.value = data.idSumberDana ?? "";
                idJenisHargaFld.value = data.idJenisHarga ?? "";
                ppnFld.value = data.ppn ?? "";
                idPemasokFld.value = data.idPemasok ?? "";
            },
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.RefPlFields} item */
            optionRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                const awal = item.id ? item.bulanAwalAnggaran : bulanAwalAnggaranFld.value;
                const akhir = item.id ? item.bulanAkhirAnggaran : bulanAkhirAnggaranFld.value;
                const tahun = item.id ? item.tahunAnggaran : tahunAnggaranFld.value;
                const nilaiAkhir = item.id ? item.nilaiAkhir : 0;

                const anggaran2 = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                return `
                    <div class="col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-3"><b>${item.namaPemasok}</b></div>
                        <div class="col-xs-3"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-2">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.RefPlFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.namaPemasok})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                const idJenisAnggaran = tabungGasYes.checked ? "6" : undefined;
                $.post({
                    url: "<?= $pembelianAcplUrl ?>",
                    data: {noDokumen: typed, statusRevisi: 0, idJenisAnggaran},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.PenerimaanUi.Form.RefPlFields} */
                const obj = this.options[value];
                const tarikByFld = divElm.querySelector("[name=tarik_by]:checked");

                idCaraBayarFld.value = (actionFld.value == "addbonus") ? "18" : obj.idCaraBayar;

                if (divElm.querySelector("#kode_reffro").value == "") {
                    tanggalJatuhTempoFld.value = (obj.tanggalJatuhTempo);
                }

                if (obj.idJenisAnggaran == "6") {
                    idGudangPenyimpananFld.value = "60";
                    divElm.querySelector("#sts_tabunggm").disabled = false;
                } else {
                    idGudangPenyimpananFld.value = "59";
                    divElm.querySelector("#sts_tabunggm").disabled = true;
                }

                divElm.querySelector("#ppn").checked = (obj.ppn == 10);

                if (tarikByFld.value != "pbf") {
                    namaPemasokWgt.addOption(obj);
                    namaPemasokWgt.value = obj.idPemasok;
                }

                if (obj.action) return;

                if (tarikByFld.value != "spk" && tarikByFld.value != "pbf") return;

                $.post({
                    url: "<?= $refPlPembelianUrl ?>",
                    data: {kode: obj.kode},
                    success(data) {
                        if (data[0] == 0 && obj.tipeDokumen != "0") {
                            bulanAwalAnggaranFld.value = obj.bulanAwalAnggaran;
                            bulanAkhirAnggaranFld.value = obj.bulanAkhirAnggaran;
                            tahunAnggaranFld.value = obj.tahunAnggaran;
                            selectItemSPK(obj.kode);

                        } else {
                            $.post({
                                url: "<?= $penerimaanUrl ?>",
                                data: {
                                    kodeRefPl: obj.kode,
                                    statusSaved: 1,
                                    statusClosed: 0,
                                    statusRevisi: 0
                                },
                                success(dom) {
                                    divElm.querySelector(".modal-body").innerHTML = dom;
                                    divElm.querySelector(".modal-header").innerHTML = `<h5 style="color:#FFF">Purchase Order dari ${obj.noDokumen}<br/>${obj.namaPemasok}</h5>`;
                                    divElm.querySelector("#modal-modul").modal("show");
                                    divElm.querySelector(".modal-footer").querySelector("#btn-pull").style.display = "none";
                                }
                            });
                        }
                    }
                });
            },
            onItemRemove() {
                const trElm = itemWgt.querySelector("tbody tr");
                if (!trElm.length || !confirm(str._<?= $h('Menghapus "Ref. SPK" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

                trElm.remove();
                sortNumber();
                hitungTotal();
            }
        });

        const kodeRefDoWgt = new spl.SelectWidget({
            element: divElm.querySelector(".kodeRefDoFld"),
            maxItems: 1,
            valueField: "kode",
            searchField: ["noDokumen"],
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.RefDoFields} item */
            optionRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                const awal = item.id ? item.bulanAwalAnggaran : bulanAwalAnggaranFld.value;
                const akhir = item.id ? item.bulanAkhirAnggaran : bulanAkhirAnggaranFld.value;
                const tahun = item.id ? item.tahunAnggaran : tahunAnggaranFld.value;
                const nilaiAkhir = item.id ? item.nilaiAkhir : 0;

                const anggaran2 = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                return `
                    <div class="col-xs-12  tbl-row-like">
                        <div class="col-xs-2"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-2"><b>${item.noSpk}</b></div>
                        <div class="col-xs-3"><b>${item.namaPemasok}</b></div>
                        <div class="col-xs-2"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-1">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.RefDoFields} item */
            itemRenderer(item) {return `<div class="item">${item.noDokumen} (${item.noSpk})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                const idJenisAnggaran = tabungGasYes.checked ? 6 : undefined;
                $.post({
                    url: "<?= $pemesananAcplUrl ?>",
                    data: {
                        noDokumen: typed,
                        statusClosed: 0,
                        statusRevisi: 0,
                        idJenisAnggaran
                    },
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.PenerimaanUi.Form.RefDoFields} */
                const obj = this.options[value];

                if (actionFld.value == "add") {
                    bulanAwalAnggaranFld.value = obj.bulanAwalAnggaran;
                    bulanAkhirAnggaranFld.value = obj.bulanAkhirAnggaran;
                    tahunAnggaranFld.value = obj.tahunAnggaran;
                }

                if (divElm.querySelector("[name='tarik_by']:checked").value != "do") return;

                if (obj.objSpk) {
                    kodeRefPlWgt.addOption(obj.objSpk);
                    kodeRefPlWgt.value = obj.objSpk.kode;
                }

                // select item spk
                if (!obj.action) {
                    selectItemDO(obj.kode);
                }
            },
            onItemRemove() {
                if (!confirm(str._<?= $h('Menghapus "Ref. SPK" akan menghapus daftar barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

                hapusSemuaRef();
                itemWgt.reset();
                sortNumber();
                hitungTotal();
            }
        });

        const namaPemasokWgt = new spl.SelectWidget({
            element: divElm.querySelector(".namaPemasokFld"),
            maxItems: 1,
            valueField: "id",
            searchField: ["nama"],
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.PemasokFields} item */
            optionRenderer(item) {
                const anggaran1 = item.id ? item.subjenisAnggaran : idJenisAnggaranFld.selectedOptions[0].innerHTML;
                const awal = item.id ? item.bulanAwalAnggaran : bulanAwalAnggaranFld.value;
                const akhir = item.id ? item.bulanAkhirAnggaran : bulanAkhirAnggaranFld.value;
                const tahun = item.id ? item.tahunAnggaran : tahunAnggaranFld.value;
                const nilaiAkhir = item.id ? item.nilaiAkhir : 0;

                const anggaran2 = nToS(awal) + (awal == akhir ? "" : "-" + nToS(akhir)) + " " + tahun;
                return `
                    <div class="col-xs-12  tbl-row-like">
                        <div class="col-xs-3"><b>${item.nama}</b></div>
                        <div class="col-xs-2"><b>${item.noDokumen}</b></div>
                        <div class="col-xs-3"><b>${anggaran1}</b></div>
                        <div class="col-xs-2">${anggaran2}</div>
                        <div class="col-xs-2">${preferInt(nilaiAkhir)}</div>
                    </div>`;
            },
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.PemasokFields} item */
            itemRenderer(item) {return `<div class="item">${item.nama} (${item.noDokumen})</div>`},
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                const idJenisAnggaran = tabungGasYes.checked ? "6" : undefined;
                $.post({
                    url: "<?= $pembelianAcplUrl ?>",
                    data: {statusClosed: 0, statusRevisi: 0, namaPemasok: typed, idJenisAnggaran},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.PenerimaanUi.Form.PemasokFields} */
                const obj = this.options[value];

                if (divElm.querySelector("[name='tarik_by']:checked").value != "pbf") return;

                // set nilai pl akan dilakukan SelectWidget PL: anggaran, tempokirim akan di set oleh po
                // dari spk yang dipilih, lakukan pencarian po
                if (obj.objSpk) {
                    kodeRefPlWgt.addOption(obj.objSpk);
                    kodeRefPlWgt.value = obj.objSpk.kode;
                }
            },
            onItemRemove() {
                if (!confirm(str._<?= $h('Menghapus "Ref. Pemasok" akan menghapus semua barang terkait dengan no. tersebut. Apakah Anda yakin ingin menghapus?') ?>)) return;

                hapusSemuaRef();
                itemWgt.reset();
                sortNumber();
                hitungTotal();
            }
        });

        divElm.querySelector(".tabungGasFld").addEventListener("click", (event) => {
            divElm.querySelector(".tabungGasFld").value = event.target.checked ? "1" : "0";
        });

        const noDokumenWgt = new spl.AjaxInputWidget({
            element: divElm.querySelector(".noDokumenFld"),
            errorRules: [
                {required: true},
                {ajax: data => !data || data.kode == kodeFld.value, message: str._<?= $h("Sudah terpakai.") ?>}
            ],
            url: "<?= $cekUnikNoDokumenUrl ?>",
            term: "value",
            additionalData() {
                return {kode: kodeFld.value}
            }
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.PenerimaanUi.Form.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.kodeRefPoFld.value = data.kodeRefPo ?? "";
                fields.kodeRefRoFld.value = data.kodeRefRo ?? "";
                fields.kodeRefPlFld.value = data.kodeRefPl ?? "";
                fields.kodeRefRencanaFld.value = data.kodeRefRencana ?? "";
                fields.idRefKatalogFld.value = data.idRefKatalog ?? "";
                fields.idKatalogWgt.value = data.idKatalog ?? "";
                fields.idPabrikWgt.value = data.idPabrik ?? "";
                fields.kemasanFld.value = data.kemasan ?? "";
                fields.jumlahItemBonusFld.value = data.jumlahItemBonus ?? "";
                fields.jumlahItemBeliFld.value = data.jumlahItemBeli ?? "";
                fields.noUrutFld.value = data.noUrut ?? "";
                fields.idKemasanDepoFld.value = data.idKemasanDepo ?? "";
                fields.namaSediaanStc.innerHTML = data.namaSediaan ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.isiKemasanWgt.value = data.isiKemasan ?? "";
                fields.noUrutStc.innerHTML = data.noUrut ?? "";
                fields.noBatchWgt.value = data.noBatch ?? "";
                fields.tanggalKadaluarsaWgt.value = data.tanggalKadaluarsa ?? "";
                fields.jumlahKemasanWgt.value = data.jumlahKemasan ?? "";
                fields.jumlahItemStc.innerHTML = data.jumlahItem ?? "";
                fields.hargaKemasanWgt.value = data.hargaKemasan ?? "";
                fields.hargaItemStc.innerHTML = data.hargaItem ?? "";
                fields.diskonItemWgt.value = data.diskonItem ?? "";
                fields.hargaTotalStc.innerHTML = data.hargaTotal ?? "";
                fields.diskonHargaStc.innerHTML = data.diskonHarga ?? "";
                fields.hargaAkhirStc.innerHTML = data.hargaAkhir ?? "";
                fields.jumlahRencanaStc.innerHTML = data.jumlahRencana ?? "";
                fields.jumlahHpsStc.innerHTML = data.jumlahHps ?? "";
                fields.jumlahPlStc.innerHTML = data.jumlahPl ?? "";
                fields.jumlahDoStc.innerHTML = data.jumlahDo ?? "";
                fields.jumlahBonusStc.innerHTML = data.jumlahBonus ?? "";
                fields.jumlahTerimaStc.innerHTML = data.jumlahTerima ?? "";
                fields.jumlahReturStc.innerHTML = data.jumlahRetur ?? "";
                fields.stokBtn.value = data.idKatalog ?? "";
            },
            addRow(trElm) {
                const idKatalogWgt = new spl.InputWidget({
                    element: trElm.querySelector(".idKatalogFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ]
                });

                const idPabrikWgt = new spl.InputWidget({
                    element: trElm.querySelector(".idPabrikFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ]
                });

                const jumlahKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".jumlahKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.intNumberSetting
                });

                const hargaKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".hargaKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.currNumberSetting
                });

                const isiKemasanWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".isiKemasanFld"),
                    errorRules: [
                        {required: true},
                        {greaterThan: 0}
                    ],
                    ...tlm.floatNumberSetting
                });

                // note: simplified version. in order to sync to other views.
                // last exist of complex code: commit-58d6a2d
                // on fragment: itemTbl.addEventListener("focusout", ".noBatchFld", () => {})
                const noBatchWgt = new spl.InputWidget({
                    element: trElm.querySelector(".noBatchFld"),
                    errorRules: [
                        {required: true},
                        {
                            callback() {
                                let count = 0, val = this._element.value;
                                itemWgt.querySelectorAll(".noBatchFld").forEach(elm => count += (elm.value == val));
                                return count < 2;
                            },
                            message: str._<?= $h("Sudah terpakai.") ?>
                        }
                    ]
                });

                const diskonItemWgt = new spl.NumberWidget({
                    element: trElm.querySelector(".diskonItemFld"),
                    errorRules: [
                        {greaterThanEqual: 0},
                        {lessThanEqual: 100}
                    ],
                    warningRules: [{lessThanEqual: 75, message: str._<?= $h("melebihi 75%") ?>}],
                    ...tlm.intNumberSetting
                });

                const tanggalKadaluarsaWgt = new spl.DateTimeWidget({
                    element: trElm.querySelector(".tanggalKadaluarsaFld"),
                    // TODO: js: uncategorized: add "already expired", and "less than 2 years" rules
                    errorRules: [{required: true}],
                    ...tlm.dateWidgetSetting
                });

                trElm.fields = {
                    idKatalogWgt,
                    idPabrikWgt,
                    jumlahKemasanWgt,
                    hargaKemasanWgt,
                    isiKemasanWgt,
                    noBatchWgt,
                    diskonItemWgt,
                    tanggalKadaluarsaWgt,
                    kodeRefPoFld: trElm.querySelector(".kodeRefPoFld"),
                    kodeRefRoFld: trElm.querySelector(".kodeRefRoFld"),
                    kodeRefPlFld: trElm.querySelector(".kodeRefPlFld"),
                    kodeRefRencanaFld: trElm.querySelector(".kodeRefRencanaFld"),
                    idRefKatalogFld: trElm.querySelector(".idRefKatalogFld"),
                    kemasanFld: trElm.querySelector(".kemasanFld"),
                    jumlahItemBonusFld: trElm.querySelector(".jumlahItemBonusFld"),
                    jumlahItemBeliFld: trElm.querySelector(".jumlahItemBeliFld"),
                    noUrutFld: trElm.querySelector(".noUrutFld"),
                    idKemasanDepoFld: trElm.querySelector(".idKemasanDepoFld"),
                    namaSediaanStc: trElm.querySelector(".namaSediaanStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    noUrutStc: trElm.querySelector(".noUrutStc"),
                    jumlahItemStc: trElm.querySelector(".jumlahItemStc"),
                    hargaItemStc: trElm.querySelector(".hargaItemStc"),
                    hargaTotalStc: trElm.querySelector(".hargaTotalStc"),
                    diskonHargaStc: trElm.querySelector(".diskonHargaStc"),
                    hargaAkhirStc: trElm.querySelector(".hargaAkhirStc"),
                    jumlahRencanaStc: trElm.querySelector(".jumlahRencanaStc"),
                    jumlahHpsStc: trElm.querySelector(".jumlahHpsStc"),
                    jumlahPlStc: trElm.querySelector(".jumlahPlStc"),
                    jumlahDoStc: trElm.querySelector(".jumlahDoStc"),
                    jumlahBonusStc: trElm.querySelector(".jumlahBonusStc"),
                    jumlahTerimaStc: trElm.querySelector(".jumlahTerimaStc"),
                    jumlahReturStc: trElm.querySelector(".jumlahReturStc"),
                    stokBtn: trElm.querySelector(".stokBtn"),
                }
            },
            deleteRow(trElm) {
                const fields = trElm.fields;
                fields.idKatalogWgt.destroy();
                fields.idPabrikWgt.destroy();
                fields.jumlahKemasanWgt.destroy();
                fields.hargaKemasanWgt.destroy();
                fields.isiKemasanWgt.destroy();
                fields.noBatchWgt.destroy();
                fields.diskonItemWgt.destroy();
                fields.tanggalKadaluarsaWgt.destroy();

                hitungTotal();
            },
            profile: {
                edit(data) {
                    // TODO: js: uncategorized: finish this
                }
            },
            onInit() {
                this.loadProfile("edit");
            },
            addRowBtn: ".penerimaanFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const stokBtn = event.target;
            if (!stokBtn.matches(".stokBtn")) return;

            $.post({
                url: "<?= $cekStokUrl ?>",
                data: {idKatalog: stokBtn.value, idDepo: idGudangPenyimpananFld.value},
                success(data) {
                    const namaKatalog = closest(stokBtn, "tr").fields.namaSediaanStc.innerHTML;
                    const total = data.reduce((acc, curr) => acc + curr.jumlahStokAdm, 0);
                    headerElm.innerHTML = str._<?= $h("Nama Barang: {{NAMA}}") ?>.replace("{{NAMA}}", namaKatalog);
                    footerElm.innerHTML = str._<?= $h("Total: {{TOTAL}}") ?>.replace("{{TOTAL}}", total);
                    stokWgt.load(data);
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahKemasanFld = event.target;
            if (!jumlahKemasanFld.matches(".jumlahKemasanFld")) return;

            const trElm = closest(jumlahKemasanFld, "tr");
            const fields = trElm.fields;
            const idKatalog = fields.idKatalogWgt.value;
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);

            let totalJumlahItem = 0;
            divElm.querySelectorAll(`.idKatalogFld[value="${idKatalog}"]`).forEach(item => {
                totalJumlahItem += sysNum(closest(item, "tr").fields.jumlahItemStc.innerHTML);
            });

            const maksimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jMax);

            if (totalJumlahItem > maksimumJumlahItem) {
                const jkjMax = maksimumJumlahItem / isiKemasan;
                alert(`
                    Jumlah Total tidak boleh melebihi jumlah DO atau
                    (Jumlah SP/SPK/Kontrak - Jumlah Penerimaan). Maximum Jumlah item Penerimaan yang
                    dibolehkan adalah ${maksimumJumlahItem}, atau setaran dengan ${userFloat(jkjMax)}`
                );
            }

            hitungSubTotal(trElm);
            hitungTotal();
        });

        /** @see {his.FatmaPharmacy.views.PenerimaanUi.Form.StokTableFields} */
        const stokWgt = new spl.TableWidget({
            element: divElm.querySelector(".stokTbl"),
            columns: {
                1: {formatter: tlm.rowNumGenerator},
                2: {field: "namaDepo"},
                3: {field: "jumlahStokAdm", formatter: tlm.intFormatter}
            }
        });

        // JUNK -----------------------------------------------------

        function hapusSemuaRef() {
            if (!divElm.querySelector("#kode_reffspk").value) return;

            itemWgt.reset();
            sortNumber();
            hitungTotal();

            kodeRefDoWgt.clearOptions();
            kodeRefDoWgt.clearCache();

            kodeRefPlWgt.clearOptions();
            kodeRefPlWgt.clearCache();

            namaPemasokWgt.clearOptions();
            namaPemasokWgt.clearCache();
        }

        /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.KatalogFields} obj */
        function inputKatalog(obj) {
            let {kemasan, idKemasan, namaSediaan, isiKemasan, satuan, satuanJual, idKemasanDepo, jumlahItem} = obj;
            const {jumlahDo, jumlahPl, jumlahItemBeli, jumlahItemBonus, jumlahTerima, jumlahRetur, idKatalog} = obj;
            const {jumlahTerimaBonus, jumlahReturBonus, kodeRefPo, kodeRefRo, kodeRefPl, kodeRefRencana} = obj;
            const {idPabrik, namaPabrik, diskonItem, jumlahRencana, jumlahHps, hargaKemasan} = obj;

            if (tabungGasYes.checked && (idKemasan != 52 && idKemasan != 62 && idKemasan != 96)) {
                alert(`Anda Tidak bisa menginputkan ${namaSediaan} ini. Karena Kemasan item ini adalah ${kemasan}, bukan TABUNG GAS.`);
                return;
            }

            const jumlahBeli2 = kodeRefDoWgt.value ? jumlahDo : jumlahPl;
            const jumlahBonus = (jumlahBeli2 / jumlahItemBeli) * jumlahItemBonus;
            jumlahItem = jumlahItem - jumlahTerima + jumlahRetur;
            const action = actionFld.value;

            if (action == "addbonus" && jumlahBonus > 0) {
                jumlahItem = jumlahBonus - jumlahTerimaBonus + jumlahReturBonus;
            } else if (action == "addbonus" && jumlahBonus == 0) {
                return;
            } else if (jumlahItem <= 0) {
                return;
            }

            kemasan = (isiKemasan == 0) ? satuan : `${satuanJual} ${preferInt(isiKemasan)} ${satuan}`;

            const jumlahKemasan = jumlahItem / isiKemasan;

            // TODO: js: uncategorized: fix CRITICAL ERROR (width impact): duplicate ".jumlahItemBonusFld"
            const trAddElm = divElm.querySelector(".tr-add");
            const trStr = drawTr("tbody", {
                class: ".tr-data",
                id: idKatalog,
                td_1: {
                    hidden_1: {class: ".kodeRefPoFld", name: "kodeRefPo[]", value: kodeRefPo},
                    hidden_2: {class: ".kodeRefRoFld", name: "kodeRefRo[]", value: kodeRefRo},
                    hidden_3: {class: ".kodeRefPlFld", name: "kodeRefPl[]", value: kodeRefPl},
                    hidden_4: {class: ".kodeRefRencanaFld", name: "kodeRefRencana[]", value: kodeRefRencana},
                    hidden_5: {class: ".idRefKatalogFld", name: "idRefKatalog[]", value: idKatalog},
                    hidden_6: {class: ".idKatalogFld", name: "idKatalog[]", value: idKatalog},
                    hidden_7: {class: ".idPabrikFld", name: "idPabrik[]", value: idPabrik},
                    hidden_8: {class: ".kemasanFld", name: "kemasan[]", value: isiKemasan},
                    hidden_9: {class: ".jumlahItemBonusFld", name: "jumlahItemBonus[]", value: jumlahBonus},
                    hidden_10: {class: ".jumlahItemBeliFld", name: "jumlahItemBeli[]", value: jumlahItemBeli},
                    hidden_11: {class: ".jumlahItemBonusFld", name: "j_bonus[]", value: jumlahItemBonus},
                    hidden_12: {class: ".noUrutFld", name: "noUrut[]", value: 1},
                    hidden_13: {class: ".idKemasanDepoFld", name: "idKemasanDepo[]", value: idKemasanDepo},
                    staticText: {class: ".no", text: 1}
                },
                td_2: {
                    button: {class: ".btn-stok", text: str._<?= $h("Stok") ?>},
                    staticText: {class: ".namaSediaanStc", text: namaSediaan}
                },
                td_3: {class: ".namaPabrikStc", text: namaPabrik},
                td_4: {
                    select: {
                        class: ".idKemasanFld",
                        name: "idKemasan[]",
                        option: {value: idKemasan, is: isiKemasan, ids: idKemasanDepo, sat: satuan, satj: satuanJual, hk: hargaKemasan, text: kemasan}
                    }
                },
                td_5: {
                    input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: preferInt(isiKemasan), readonly: true}
                },
                td_6: {
                    class: ".DIFF-WITH-SPLBULKINPUT",
                    button: {class: ".btn-addbch", text: "+"},
                    staticText: {class: ".noUrutStc", par: idKatalog, no_urut: 1, text: 1}
                },
                td_7: {
                    input: {class: ".noBatchFld", name: "noBatch[]", id_katalog: idKatalog}
                },
                td_8: {
                    input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                },
                td_9: {
                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: preferInt(jumlahKemasan)}
                },
                td_10: {class: ".jumlahItemStc"},
                td_11: {
                    input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: currency(hargaKemasan), readonly: true}
                },
                td_12: {class: ".hargaItemStc"},
                td_13: {
                    input: {class: ".diskonItemFld", name: "diskonItem[]", value: userInt(diskonItem), readonly: true}
                },
                td_14: {class: ".hargaTotalStc"},
                td_15: {class: ".diskonHargaStc"},
                td_16: {class: ".hargaAkhirStc"},
                td_17: {class: ".jumlahRencanaStc", text: userFloat(jumlahRencana)},
                td_18: {class: ".jumlahHpsStc", text: userFloat(jumlahHps)},
                td_19: {class: ".jumlahPlStc", text: userFloat(jumlahPl)},
                td_20: {class: ".jumlahDoStc", text: userFloat(jumlahDo)},
                td_21: {class: ".jumlahBonusStc", text: userFloat(jumlahBonus)},
                td_22: {class: ".jumlahTerimaStc", text: userFloat(jumlahTerima)},
                td_23: {class: ".jumlahReturStc", text: userFloat(jumlahRetur)},
            });
            trAddElm.insertAdjacentHTML("beforebegin", trStr);
            hitungSubTotal(/** @type {HTMLTableRowElement} */ trAddElm.previousElementSibling);
        }

        function selectItemSPK(kodeRef) {
            const kode = (actionFld.value == "edit") ? kodeFld.value : undefined;
            $.post({
                url: "<?= $detailTerimaUrl ?>",
                data: {kodeRef, kodeRefNot: kode},
                success(data) {
                    if (!data.length) return;

                    data.forEach(obj => itemWgt.querySelector("tr#" + obj.id_katalog) || inputKatalog(obj));

                    sortNumber();
                    hitungTotal();
                    itemWgt.querySelector("tbody tr:last-child").fields.jumlahKemasanWgt.dispatchEvent(new Event("focus"));
                }
            });
        }

        function selectItemDO(kodeRef) {
            const kode = (actionFld.value == "edit") ? kodeFld.value : undefined;
            $.post({
                url: "<?= $detailTerimaPemesananUrl ?>",
                data: {kodeRef, kodeRefNot: kode},
                success(data) {
                    if (!data.length) return;

                    data.forEach(obj => itemWgt.querySelector("tr#" + obj.id_katalog) || inputKatalog(obj));

                    sortNumber();
                    hitungTotal();
                    itemWgt.querySelector("tbody tr:last-child").fields.jumlahKemasanWgt.dispatchEvent(new Event("focus"));
                }
            });
        }

        function sortNumber() {
            divElm.querySelectorAll(".no").forEach((item, i) => item.innerHTML = i + 1);
        }

        divElm.querySelector(".btn-addbch").addEventListener("click", (event) => {
            const trElm = closest(event.target, "tr");
            const fields = trElm.fields;
            const idKatalog = trElm.id;
            const noUrut = divElm.querySelectorAll(`.noUrutStc[par="${idKatalog}"]`).length + 1;
            const maksimumJumlahItem = sysNum(fields.jumlahItemStc.dataset.jMax);
            const isiKemasan = sysNum(fields.isiKemasanWgt.value);
            const hargaItem = sysNum(fields.hargaItemStc.innerHTML);
            const hargaKemasan = sysNum(fields.hargaKemasanWgt.value);
            const diskonItem = sysNum(fields.diskonItemWgt.value);
            let jumlahKemasanPar = sysNum(fields.jumlahKemasanWgt.value);
            let jumlahKemasan = 0;
            let jumlahItem = 0;
            const isTabungGasMedis = tabungGasYes.checked;

            if (isTabungGasMedis && jumlahKemasanPar > 1) {
                jumlahKemasan = 1;
                jumlahItem = jumlahKemasan * isiKemasan;
            } else if (isTabungGasMedis && jumlahKemasanPar <= 1) {
                return;
            }

            jumlahKemasanPar--;

            const jumlahItemPar = jumlahKemasanPar * isiKemasan;
            const hargaTotalPar = hargaKemasan * jumlahKemasanPar;
            const diskonHargaPar = hargaTotalPar * diskonItem / 100;
            const hargaAkhirPar = hargaTotalPar - diskonHargaPar;
            const hargaTotal = jumlahKemasan * hargaKemasan;
            const diskonHarga = hargaTotal * diskonItem / 100;
            const hargaAkhir = hargaTotal - diskonHarga;

            const lastTrElm = closest(divElm.querySelector(`.noUrutStc[par="${idKatalog}"]:last-child`), "tr");
            const trStr = drawTr("tbody", {
                class: ".tr-data",
                td_1: {
                    colspan: 4,
                    hidden_1: {name: "idKatalog[]", value: idKatalog},
                    hidden_2: {name: "noUrut[]", value: noUrut},
                },
                td_2: {
                    input: {class: ".isiKemasanFld", name: "isiKemasan[]", value: preferInt(isiKemasan), readonly: true}
                },
                td_3: {
                    button: {class: ".btn-delbch", text: "-"},
                    staticText: {class: ".no_batch", par: idKatalog, no_urut: 1, text: noUrut}
                },
                td_4: {
                    input: {class: ".bch", name: "noBatch[]", id_katalog: idKatalog}
                },
                td_5: {
                    input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                },
                td_6: {
                    input: {class: ".jumlahKemasanFld", name: "jumlahKemasan[]", value: preferInt(jumlahKemasan)}
                },
                td_7: {
                    input: {class: ".jumlahItemFld", name: "jumlahItem[]", "data-jMax": maksimumJumlahItem, value: preferInt(jumlahItem), readonly: true}
                },
                td_8: {
                    input: {class: ".hargaKemasanFld", name: "hargaKemasan[]", value: currency(hargaKemasan), readonly: true}
                },
                td_9: {
                    input: {class: ".hargaItemFld", name: "hargaItem[]", value: currency(hargaItem), readonly: true}
                },
                td_10: {
                    input: {class: ".diskonItemFld", name: "diskonItem[]", value: userInt(diskonItem), readonly: true}
                },
                td_11: {class: ".hargaTotalStc", text: currency(hargaTotal)},
                td_12: {
                    input: {class: ".diskonHargaFld", name: "diskonHarga[]", value: currency(diskonHarga), readonly: true}
                },
                td_13: {
                    input: {class: ".hargaAkhirFld", value: currency(hargaAkhir), readonly: true}
                },
            });
            lastTrElm.insertAdjacentHTML("afterend", trStr);
            lastTrElm.nextElementSibling.fields.noBatchWgt.dispatchEvent(new Event("focus"));

            if (isTabungGasMedis && jumlahKemasanPar > 0) {
                fields.jumlahItemStc.innerHTML = preferInt(jumlahItemPar);
                fields.jumlahKemasanWgt.value = preferInt(jumlahKemasanPar);
                fields.hargaTotalStc.innerHTML = currency(hargaTotalPar);
                fields.diskonHargaStc.innerHTML = currency(diskonHargaPar);
                fields.hargaAkhirStc.innerHTML = currency(hargaAkhirPar);
            }
        });

        // NOTE: fill this var with incoming data
        let tarikBySebelumnya;
        divElm.querySelector("[name=tarik_by]").addEventListener("click", (event) => {
            const tarikByFld = event.target;
            const id = tarikByFld.value;

            if (kodeRefPlWgt.value || kodeRefDoWgt.value) {
                if (confirm(str._<?= $h('Mengganti "Referensi Penarikan data" akan menghapus referensi yang sebelumnya dan daftar item yang sudah ada. Apakah Anda yakin?') ?>)) {
                    hapusSemuaRef();
                    kodeRefPlWgt.disable();
                    kodeRefDoWgt.disable();
                    namaPemasokWgt.disable();

                    switch (id) {
                        case "do":  kodeRefDoWgt.enable(); break;
                        case "pbf": namaPemasokWgt.enable(); break;
                        case "spk": kodeRefPlWgt.enable(); break;
                        default:    throw new Error("Wrong option");
                    }
                    tarikBySebelumnya = id;

                } else {
                    tarikByFld.value = tarikBySebelumnya;
                    divElm.querySelector(`[name="tarik_by"][value="${tarikBySebelumnya}"]`).checked = true;
                }
            } else {
                kodeRefPlWgt.disable();
                kodeRefDoWgt.disable();
                namaPemasokWgt.disable();

                switch (id) {
                    case "do":  kodeRefDoWgt.enable(); break;
                    case "pbf": namaPemasokWgt.enable(); break;
                    case "spk": kodeRefPlWgt.enable(); break;
                    default:    throw new Error("Wrong option");
                }
                tarikBySebelumnya = id;
            }
        });

        tarikBtn.addEventListener("click", () => {
            const kodeRefDo = kodeRefDoWgt.value;
            const kodeRefPl = kodeRefPlWgt.value;

            if (kodeRefDo) {
                selectItemDO(kodeRefDo);
            } else if (kodeRefPl) {
                selectItemSPK(kodeRefPl);
            } else {
                alert(str._<?= $h('Anda harus memilih "Ref. SP/SPK/Kontrak"') ?>);
            }
        });

        divElm.querySelector(".revisiBtn").addEventListener("click", () => {
            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.DataRevisiFields} obj */
            function setDataRevisi(obj) {
                if (idPemasokFld.value != obj.idPemasok) {
                    namaPemasokWgt.addOption(obj);
                    namaPemasokWgt.value = obj.idPemasok;
                    divElm.querySelector(".id_pbf").classList.add("text-danger");
                }
                if (idJenisAnggaranFld.value != obj.idJenisAnggaran) {
                    idJenisAnggaranFld.value = obj.idJenisAnggaran;
                    idJenisAnggaranFld.classList.add("text-danger");
                }
                if (idSumberDanaFld.value != obj.idSumberDana) {
                    idSumberDanaFld.value = obj.idSumberDana;
                    idSumberDanaFld.classList.add("text-danger");
                }
                if (idJenisHargaFld.value != obj.idJenisHarga) {
                    idJenisHargaFld.value = obj.idJenisHarga;
                    idJenisHargaFld.classList.add("text-danger");
                }
                if (idCaraBayarFld.value != obj.idCaraBayar) {
                    idCaraBayarFld.value = obj.idCaraBayar;
                    idCaraBayarFld.classList.add("text-danger");
                }
                if (ppnFld.value != obj.ppn) {
                    ppnFld.value = (obj.ppn);
                    ppnFld.classList.add("text-danger");
                    ppnFld.checked = (obj.ppn == "10");
                    hitungTotal();
                }
            }

            /** @param {his.FatmaPharmacy.views.PenerimaanUi.Form.DataTerimaFields} obj */
            function revisiiDataTerima(obj) {
                const trElm = divElm.querySelector("tr#" + obj.idKatalog);
                const fields = trElm.fields;
                let status = false;
                const jumlahItem = sysNum(fields.jumlahItemStc.innerHTML);
                let jumlahKemasan = sysNum(fields.jumlahKemasanWgt.value);
                let hargaKemasan = sysNum(fields.hargaKemasanWgt.value);
                let diskonItem = sysNum(fields.diskonItemWgt.value);

                if (fields.idKemasanFld.value != obj.idKemasan) {
                    status = true;
                    fields.idKemasanFld.value = obj.idKemasan;
                }

                if (fields.idKemasanDepoFld.value != obj.idKemasanDepo) {
                    status = true;
                    fields.idKemasanDepoFld.value = obj.idKemasanDepo;
                }

                if (sysNum(fields.isiKemasanWgt.value) != obj.isiKemasan) {
                    status = true;
                    jumlahKemasan = jumlahItem / obj.isiKemasan;

                    fields.isiKemasanWgt.value = preferInt(obj.isiKemasan);
                    fields.jumlahKemasanWgt.value = preferInt(jumlahKemasan);
                }

                if (sysNum(fields.hargaKemasanWgt.value) != obj.hargaKemasan) {
                    status = true;
                    hargaKemasan = obj.hargaKemasan;
                    fields.hargaKemasanWgt.value = currency(obj.hargaKemasan);
                }

                if (sysNum(fields.hargaItemStc.innerHTML) != obj.hargaItem) {
                    status = true;
                    fields.hargaItemStc.innerHTML = currency(obj.hargaItem);
                }

                if (sysNum(fields.diskonItemWgt.value) != obj.diskonItem) {
                    status = true;
                    diskonItem = obj.diskonItem;
                    fields.diskonItemWgt.value = userInt(obj.diskonItem);
                }

                if (status) {
                    const hargaTotal = jumlahKemasan * hargaKemasan;
                    const diskonHarga = hargaTotal * diskonItem / 100;
                    const hargaAkhir = hargaTotal - diskonHarga;

                    fields.hargaTotalStc.innerHTML = currency(hargaTotal);
                    fields.diskonHargaStc.innerHTML = currency(diskonHarga);
                    fields.hargaAkhirStc.innerHTML = currency(hargaAkhir);

                    trElm.classList.add("danger");
                }
            }

            const kodeRefDo = kodeRefDoWgt.value;
            const kodeRefPl = kodeRefPlWgt.value;

            if (kodeRefDo) {
                $.post({
                    url: "<?= $pemesananAcplUrl ?>",
                    data: {kode: kodeRefDo, verRevisi: 1},
                    success(data) {
                        if (!data[0]) return alert(str._<?= $h("No. DO/PO Pemesanan belum selesai direvisi, atau belum diverifikasi revisi. Silahkan hubungi ULP.") ?>);

                        const obj = data[1][0];
                        // Lakukan Revisi Dokumen
                        setDataRevisi(obj);

                        $.post({
                            url: "<?= $detailTerimaPemesananUrl ?>",
                            data: {kodeRef: obj.kode},
                            success(data) {
                                if (!data.length) return alert(str._<?= $h("DO/PO Pemesanan Tidak ditemukan. Silahkan cek DO/PO Pemesanan.") ?>);

                                data.forEach(iobj => revisiiDataTerima(iobj));
                                hitungTotal();
                            }
                        });
                    }
                });

            } else if (kodeRefPl) {
                $.post({
                    url: "<?= $pembelianAcplUrl ?>",
                    data: {kode: kodeRefPl, verRevisi: 1},
                    success(data) {
                        const obj = data[1][0];
                        // Lakukan Revisi Dokumen
                        setDataRevisi(obj);

                        $.post({
                            url: "<?= $detailTerimaUrl ?>",
                            // TODO: js: missing var: kodereff
                            data: {kodeRef: kodereff},
                            success(data) {
                                if (!data.length) return alert(str._<?= $h("SP/SPK/Kontrak Pembelian Tidak ditemukan. Silahkan cek SP/SPK/Kontrak.") ?>);

                                data.forEach(iobj => revisiiDataTerima(iobj));
                                hitungTotal();
                            }
                        });
                    }
                });

            } else {
                alert(str._<?= $h("No. Referensi DO/PO Pemesanan atau No. Referensi SP/SPK/Kontrak Pembelian tidak ditemukan.") ?>);
            }
        });

        verGudangFld.addEventListener("click", () => {
            const isChecked = verGudangFld.checked;
            userGudangStc.innerHTML = isChecked ? userName : "-";
            tanggalGudangStc.innerHTML = isChecked ? nowVal("user") : "-";
        });

        verTerimaFld.addEventListener("click", () => {
            const isChecked = verTerimaFld.checked;
            userTerimaStc.innerHTML = isChecked ? userName : "-";
            tanggalTerimaStc.innerHTML = isChecked ? nowVal("user") : "-";
            updateStokMarkerFld.checked = isChecked;
        });

        verAkuntansiFld.addEventListener("click", () => {
            const isChecked = verAkuntansiFld.checked;
            userAkuntansiStc.innerHTML = isChecked ? userName : "-";
            tanggalAkuntansiStc.innerHTML = isChecked ? nowVal("user") : "-";
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(penerimaanWgt, tanggalDokumenWgt, noFakturWgt, noSuratJalanWgt, kodeRefPlWgt);
        this._widgets.push(kodeRefDoWgt, namaPemasokWgt, noDokumenWgt, itemWgt, stokWgt);
        tlm.app.registerWidget(this.constructor.widgetName, penerimaanWgt);
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
