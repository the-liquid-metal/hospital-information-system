<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\StokopnameUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/add.php the original file
 */
final class FormStokopname
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $addAccess,
        array  $editAccess,
        string $dataUrl,
        string $actionUrl,
        string $stokAdmUrl,
        string $katalogDepoUrl,
        string $tableWidgetId,
        string $depoSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.StokopnameUi.Form {
    export interface FormFields {
        idDepo:         string|"in"|"out";
        detail:         string|"in"|"x";
        idUser:         string|"in"|"x"|"id_user";
        submitCheck:    string|"in"|"x"|"submit_check";
        action:         string|"in"|"x"|"action";
        kode:           string|"in"|"x"|"kode";
        tanggalDokumen: string|"in"|"x"|"tgl_doc";
        tanggalAdm:     string|"in"|"x"|"tgl_adm";
        verUserOpname:  string|"in"|"x"|"ver_usropname";
    }

    export interface TableFields {
        idKatalog:         string|"in"|"out";
        idPabrik:          string|"in"|"out";
        idKemasan:         string|"in"|"out";
        jumlahStokAdm:     string|"in"|"out";
        hargaItem:         string|"in"|"out";
        hna:               string|"in"|"out";
        hja:               string|"in"|"out";
        phja:              string|"in"|"out";
        phjapb:            string|"in"|"out";
        diskon:            string|"in"|"out";
        ppn:               string|"in"|"out";
        refHarga:          string|"in"|"out";
        namaBarang:        string|"in"|"x"|"___"; // exist but missing
        namaPabrik:        string|"in"|"x"|"___"; // exist but missing
        satuan:            string|"in"|"x"|"___"; // exist but missing
        jumlahStokFisik:   string|"in"|"out";
        akumulasiFisik:    string|"in"|"x"|"___"; // exist but missing
        selisih:           string|"in"|"x"|"___"; // exist but missing
        noBatch:           string|"in"|"out";
        tanggalKadaluarsa: string|"in"|"out";
        hargaPokokItem:    string|"in"|"x"|"___"; // exist but missing
        nilai:             string|"in"|"x"|"___"; // exist but missing
        ubahOpname:        string|"in"|"x"|"___"; // exist but missing
        no2:               string|"in"|"x"|"___"; // exist but missing
        operator:          string|"in"|"x"|"___"; // exist but missing
        tanggal:           string|"in"|"x"|"___"; // exist but missing
    }

    export interface BarangInner {
        kode:             string;
        idPabrik:         string;
        idKemasanKecil:   string;
        jumlahStokAdm:    string;
        hp:               string;
        hna:              string;
        hja:              string;
        phja:             string;
        phjaPb:           string;
        diskon:           string;
        ppn:              string;
        refHarga:         string;
        namaBarang:       string;
        namaPabrik:       string;
        kodeKemasanKecil: string;
        akumulasiFisik:   string;
        operator:         string;
        sysdateUpdate:    string;
    }

    export interface BarangFields {
        id:             string;
        formulariumNas: string;
        formulariumRs:  string;
        kode:           string;
        namaPabrik:     string;
        namaSediaan:    string;
        jumlahStokAdm:  string;
    }

    export interface OpnameFields {
        nama: "nama";
        tgl:  "tgl";
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
        [this.widgetName]: {
            ".modal-title": {
                _style: {color: "black"},
            },
            ".modal-header": {
                _style: {background: "none"},
            },
            ".jum": {
                _style: {textAlign: "center"},
            },
            ".form-control": {
                _suffixes_1: [""],
                _style_1:    {backgroundColor: "#faffbd", color: "#000"},
                _suffixes_2: ["[readonly]", "[disabled]"],
                _style_2:    {backgroundColor: "#065C5C", color: "#FFF"},
            }
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
            class: ".stokopnameFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {class: ".idUserFld", name: "idUser", value: tlm.user.id},
                    hidden_2: {class: ".submitCheckFld", name: "submitCheck", value: 0},
                    hidden_3: {class: ".actionFld", name: "action"}, // value=$action ?? 'add'
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Transaksi") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Opname") ?>,
                        input: {class: ".tanggalDokumenFld", name: "tanggalDokumen"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal ADM") ?>,
                        input: {class: ".tanggalAdmFld", name: "tanggalAdm"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Unit") ?>,
                        select: {class: ".idDepoFld", name: "idDepo"}
                    },
                    formGroup_5: {
                        label: tlm.stringRegistry._<?= $h("Petugas") ?>,
                        input: {class: ".verUserOpnameFld", name: "verUserOpname"}
                    }
                }
            },
            row_2: {
                widthTable: {
                    class: ".itemTbl",
                    thead: {
                        tr_1: {
                            td_1: {rowspan: 2,  text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_3: {rowspan: 2,  text: tlm.stringRegistry._<?= $h("Kode") ?>},
                            td_4: {rowspan: 2,  text: tlm.stringRegistry._<?= $h("Nama Barang") ?>},
                            td_5: {colspan: 10, text: tlm.stringRegistry._<?= $h("Perhitungan stok opname") ?>},
                            td_6: {rowspan: 2,  text: tlm.stringRegistry._<?= $h("Edit") ?>},
                            td_7: {rowspan: 2,  text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_8: {rowspan: 2,  text: tlm.stringRegistry._<?= $h("Operator Update") ?>},
                            td_9: {rowspan: 2,  text: tlm.stringRegistry._<?= $h("Tanggal Update") ?>}
                        },
                        tr_2: {
                            td_1:  {text: tlm.stringRegistry._<?= $h("Pabrik") ?>},
                            td_2:  {text: tlm.stringRegistry._<?= $h("Satuan") ?>},
                            td_3:  {text: tlm.stringRegistry._<?= $h("Jumlah ADM") ?>},
                            td_4:  {text: tlm.stringRegistry._<?= $h("Jumlah Fisik") ?>},
                            td_5:  {text: tlm.stringRegistry._<?= $h("Akumulasi Fisik") ?>},
                            td_6:  {text: tlm.stringRegistry._<?= $h("Selisih") ?>},
                            td_7:  {text: tlm.stringRegistry._<?= $h("Batch") ?>},
                            td_8:  {text: tlm.stringRegistry._<?= $h("Kadaluarsa") ?>},
                            td_9:  {text: tlm.stringRegistry._<?= $h("Harga") ?>},
                            td_10: {text: tlm.stringRegistry._<?= $h("Nilai") ?>}
                        }
                    },
                    tbody: {
                        tr: {
                            td_1: {
                                hidden_1: {class: ".idKatalogFld", name: "idKatalog[]"},
                                hidden_2: {class: ".idPabrikFld", name: "idPabrik[]"},
                                hidden_3: {class: ".idKemasanFld", name: "idKemasan[]"},
                                hidden_4: {class: ".jumlahStokAdmFld", name: "jumlahStokAdm[]"},
                                hidden_5: {class: ".hargaItemFld", name: "hargaItem[]"},
                                hidden_6: {class: ".hnaFld", name: "hna[]"},
                                hidden_7: {class: ".hjaFld", name: "hja[]"},
                                hidden_8: {class: ".phjaFld", name: "phja[]"},
                                hidden_9: {class: ".phjapbFld", name: "phjapb[]"},
                                hidden_10: {class: ".diskonFld", name: "diskon[]"},
                                hidden_11: {class: ".ppnFld", name: "ppn[]"},
                                hidden_12: {class: ".refHargaFld", name: "refHarga[]"},
                                staticText: {class: ".no"}
                            },
                            td_2: {class: ".idKatalogStc"},
                            td_3: {class: ".namaBarangStc"},
                            td_4: {class: ".namaPabrikStc"},
                            td_5: {class: ".satuanStc"},
                            td_6: {class: ".jumlahStokAdmStc"},
                            td_7: {
                                input: {class: ".jumlahStokFisikFld", name: "jumlahStokFisik[]"}
                            },
                            td_8: {class: ".akumulasiFisikStc"},
                            td_9: {class: ".selisihStc"},
                            td_10: {
                                input: {class: ".noBatchFld", name: "noBatch[]"}
                            },
                            td_11: {
                                input: {class: ".tanggalKadaluarsaFld", name: "tanggalKadaluarsa[]"}
                            },
                            td_12: {class: ".hargaPokokItemStc"},
                            td_13: {class: ".nilaiStc"},
                            td_14: {
                                checkbox: {class: ".ubahOpnameFld"}
                            },
                            td_15: {class: ".no2Stc"},
                            td_16: {class: ".operatorStc"},
                            td_17: {class: ".tanggalStc"},
                            td_18: {
                                button: {class: ".deleteRowBtn", type: "danger", size: "xs", label: tlm.stringRegistry._<?= $h("delete") ?>}
                            }
                        }
                    },
                    tfoot: {
                        tr: {
                            td_1: {colspan: 6},
                            td_2: {
                                input: {class: ".cariBarangFld"}
                            },
                            td_3: {
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
            },
            row_4: {
                column: {
                    button: {class: ".kembaliBtn", text: tlm.stringRegistry._<?= $h("Kembali") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const {preferInt, toCurrency: currency, toUserInt: userInt, toUserFloat: userFloat, toSystemNumber: sysNum, stringRegistry: str} = tlm;
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const drawTr = spl.TableDrawer.drawTr;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const actionFld = divElm.querySelector(".actionFld");
        /** @type {HTMLInputElement} */  const idUserFld = divElm.querySelector(".idUserFld");
        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */  const tanggalDokumenFld = divElm.querySelector(".tanggalDokumenFld");
        /** @type {HTMLInputElement} */  const tanggalAdmFld = divElm.querySelector(".tanggalAdmFld");
        /** @type {HTMLSelectElement} */ const idDepoFld = divElm.querySelector(".idDepoFld");
        /** @type {HTMLInputElement} */  const verUserOpnameFld = divElm.querySelector(".verUserOpnameFld");
        /** @type {HTMLInputElement} */  const submitCheckFld = divElm.querySelector(".submitCheckFld");

        tlm.app.registerSelect("_<?= $depoSelect ?>", idDepoFld);
        this._selects.push(idDepoFld);

        let xCounter = 0;
        let detail;

        const formWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".stokopnameFrm"),
            dataUrl: "<?= $dataUrl ?>",
            /** @param {his.FatmaPharmacy.views.StokopnameUi.Form.FormFields} data */
            loadData(data) {
                detail = data.detail ?? "";
                kodeFld.value = data.kode ?? "";
                tanggalDokumenFld.value = data.tanggalDokumen ?? "";
                tanggalAdmFld.value = data.tanggalAdm ?? "";
                idDepoFld.value = data.idDepo ?? "";
                verUserOpnameFld.value = data.verUserOpname ?? "";
            },
            grant: this.constructor.getAccess(tlm.userRole),
            profile: {
                add() {
                    this.loadData({});
                    // TODO: js: uncategorized: finish this
                },
                edit(data, fromServer) {
                    this.loadData(data, fromServer);

                    const {belumDiinput, sudahDiinput, total} = data;
                    const belumDiinputStr = str._<?= "Belum diinput: {{NUM}}" ?>.replace("{{NUM}}", belumDiinput);
                    const sudahDiinputStr = str._<?= "Sudah diinput: {{NUM}}" ?>.replace("{{NUM}}", sudahDiinput);
                    const totalStr = str._<?= "Total: {{NUM}}" ?>.replace("{{NUM}}", total);
                    alert(`Info:\n${belumDiinputStr}\n${sudahDiinputStr}\n${totalStr}`);
                }
            },
            onInit() {
                this.loadProfile("add");
            },
            onBeforeSubmit(event) {
                if (submitCheckFld.value == "1") {
                    event.preventDefault();

                } else if (itemWgt.querySelector("tbody tr:first-child").id == "-") {
                    alert(str._<?= $h("List obat kosong. Silahkan isi terlebih dahulu.") ?>);
                    event.preventDefault();

                } else if (confirm(str._<?= $h("Setelah penyimpanan yang diverifikasi akan mengunci penginputan. Apakah Anda yakin ingin menyimpan?") ?>)) {
                    submitCheckFld.value = "1";
                    // the form will submit

                } else {
                    xCounter = 0;
                    event.preventDefault();
                }
            },
            onSuccessSubmit() {
                tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        const itemWgt = new spl.BulkInputWidget({
            element: divElm.querySelector(".itemTbl"),
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.StokopnameUi.Form.TableFields} data
             */
            loadDataPerRow(trElm, data) {
                const fields = trElm.fields;
                fields.idKatalogFld.value = data.idKatalog ?? "";
                fields.idPabrikFld.value = data.idPabrik ?? "";
                fields.idKemasanFld.value = data.idKemasan ?? "";
                fields.jumlahStokAdmFld.value = data.jumlahStokAdm ?? "";
                fields.hargaItemFld.value = data.hargaItem ?? "";
                fields.hnaFld.value = data.hna ?? "";
                fields.hjaFld.value = data.hja ?? "";
                fields.phjaFld.value = data.phja ?? "";
                fields.phjapbFld.value = data.phjapb ?? "";
                fields.diskonFld.value = data.diskon ?? "";
                fields.ppnFld.value = data.ppn ?? "";
                fields.refHargaFld.value = data.refHarga ?? "";
                fields.idKatalogStc.innerHTML = data.idKatalog ?? "";
                fields.namaBarangStc.innerHTML = data.namaBarang ?? "";
                fields.namaPabrikStc.innerHTML = data.namaPabrik ?? "";
                fields.satuanStc.innerHTML = data.satuan ?? "";
                fields.jumlahStokAdmStc.innerHTML = data.jumlahStokAdm ?? "";
                fields.jumlahStokFisikFld.value = data.jumlahStokFisik ?? "";
                fields.akumulasiFisikStc.innerHTML = data.akumulasiFisik ?? "";
                fields.selisihStc.innerHTML = data.selisih ?? "";
                fields.noBatchFld.value = data.noBatch ?? "";
                fields.tanggalKadaluarsaWgt.value = data.tanggalKadaluarsa ?? "";
                fields.hargaPokokItemStc.innerHTML = data.hargaPokokItem ?? "";
                fields.nilaiStc.innerHTML = data.nilai ?? "";
                fields.ubahOpnameFld.value = data.ubahOpname ?? "";
                fields.no2Stc.innerHTML = data.no2 ?? "";
                fields.operatorStc.innerHTML = data.operator ?? "";
                fields.tanggalStc.innerHTML = data.tanggal ?? "";
            },
            addRow(trElm) {
                const tanggalKadaluarsaWgt = new spl.DateTimeWidget({
                    element: trElm.querySelector(".tanggalKadaluarsaFld"),
                    // TODO: js: uncategorized: add "already expired", and "less than 2 years" rules
                    errorRules: [{required: true}],
                    ...tlm.dateWidgetSetting
                });

                trElm.fields = {
                    tanggalKadaluarsaWgt,
                    idKatalogFld: trElm.querySelector(".idKatalogFld"),
                    idPabrikFld: trElm.querySelector(".idPabrikFld"),
                    idKemasanFld: trElm.querySelector(".idKemasanFld"),
                    jumlahStokAdmFld: trElm.querySelector(".jumlahStokAdmFld"),
                    hargaItemFld: trElm.querySelector(".hargaItemFld"),
                    hnaFld: trElm.querySelector(".hnaFld"),
                    hjaFld: trElm.querySelector(".hjaFld"),
                    phjaFld: trElm.querySelector(".phjaFld"),
                    phjapbFld: trElm.querySelector(".phjapbFld"),
                    diskonFld: trElm.querySelector(".diskonFld"),
                    ppnFld: trElm.querySelector(".ppnFld"),
                    refHargaFld: trElm.querySelector(".refHargaFld"),
                    idKatalogStc: trElm.querySelector(".idKatalogStc"),
                    namaBarangStc: trElm.querySelector(".namaBarangStc"),
                    namaPabrikStc: trElm.querySelector(".namaPabrikStc"),
                    satuanStc: trElm.querySelector(".satuanStc"),
                    jumlahStokAdmStc: trElm.querySelector(".jumlahStokAdmStc"),
                    jumlahStokFisikFld: trElm.querySelector(".jumlahStokFisikFld"),
                    akumulasiFisikStc: trElm.querySelector(".akumulasiFisikStc"),
                    selisihStc: trElm.querySelector(".selisihStc"),
                    noBatchFld: trElm.querySelector(".noBatchFld"),
                    hargaPokokItemStc: trElm.querySelector(".hargaPokokItemStc"),
                    nilaiStc: trElm.querySelector(".nilaiStc"),
                    ubahOpnameFld: trElm.querySelector(".ubahOpnameFld"),
                    no2Stc: trElm.querySelector(".no2Stc"),
                    operatorStc: trElm.querySelector(".operatorStc"),
                    tanggalStc: trElm.querySelector(".tanggalStc"),
                }
            },
            deleteRow(trElm) {
                trElm.fields.tanggalKadaluarsaWgt.destroy();
                akumulasiFisik(trElm.id);
            },
            profile: {
                add() {
                    // TODO: js: uncategorized: finish this
                },
            },
            onInit() {
                this.loadProfile("add");
            },
            addRowBtn: ".stokopnameFrm .addRowBtn",
            deleteRowBtn: ".deleteRowBtn"
        });

        itemWgt.addDelegateListener("tbody", "focusout", (event) => {
            const jumlahStokFisikFld = event.target;
            if (!jumlahStokFisikFld.matches(".jumlahStokFisikFld")) return;

            akumulasiFisik(closest(jumlahStokFisikFld, "tr").id);
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const ubahOpnameFld = event.target;
            if (!ubahOpnameFld.matches(".ubahOpnameFld")) return;

            const trElm = closest(ubahOpnameFld, "tr");
            const fields = trElm.fields;
            const idKatalog = trElm.id;

            if (idKatalog == "-" || actionFld.value != "edit") return;

            if (ubahOpnameFld.checked) {
                fields.jumlahStokFisikFld.readonly = false;
                fields.noBatchFld.readonly = false;
                fields.tanggalKadaluarsaWgt.readonly = false;

            } else {
                fields.jumlahStokFisikFld.readonly = true;
                fields.noBatchFld.readonly = true;
                fields.tanggalKadaluarsaWgt.readonly = true;

                $.post({
                    url: "<?= $actionUrl ?>",
                    data: {
                        kodeRef: kodeFld.value,
                        idKatalog,
                        noBatch: fields.noBatchFld.value,
                        jumlahStokFisik: fields.jumlahStokFisikFld.value,
                        idUser: idUserFld.value,
                        tanggalKadaluarsa: fields.tanggalKadaluarsaWgt.value
                    },
                    /** @param {int[]|his.FatmaPharmacy.views.StokopnameUi.Form.OpnameFields[]} data */
                    success(data) {
                        if (data[0] == 1) {
                            fields.operatorStc.innerHTML = data[1].nama;
                            fields.tanggalStc.innerHTML = data[1].tgl;
                        }
                    }
                });
            }
        });

        // TODO: js: uncategorized: confirm the relevancy of this code
        itemWgt.querySelector(".cariBarangFld").addEventListener("keypress", () => {
            if (xCounter != 15) return;

            const itemKosong = itemWgt.querySelector("tbody tr:first-child").id == "-" ? 1 : 0;

            if (submitCheckFld.value == "1") return;

            // TODO: js: uncategorized: convert this code to trigger ".tanggalKadaluarsaFld" validation
            itemWgt.querySelectorAll(".tanggalKadaluarsaFld").forEach(/** @type {HTMLInputElement} */ item => {
                if (closest(item, "tr").id == "-") return;

                const tgl = item.value;
                if (tgl.match(tgl)) return;

                alert(str._<?= $h("Format tanggal salah.") ?>);
                item.dispatchEvent(new Event("focus"));
            });

            if (itemKosong) {
                alert(str._<?= $h("List obat kosong. Silahkan isi terlebih dahulu.") ?>);
            } else {
                submitCheckFld.value = "1";
                formWgt.submit();
            }
        });

        // note: this code is elevated from upside code
        const cariBarangWgt = new spl.SelectWidget({
            element: itemWgt.querySelector(".cariBarangFld"),
            maxItems: 1,
            valueField: "kode",
            /** @param {his.FatmaPharmacy.views.StokopnameUi.Form.BarangFields} data */
            optionRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="option" style="color:${warna}">${data.namaSediaan} (${data.kode}) - ${data.namaPabrik}, ${preferInt(data.jumlahStokAdm)}</div>`;
            },
            /** @param {his.FatmaPharmacy.views.StokopnameUi.Form.BarangFields} data */
            itemRenderer(data) {
                let warna;
                switch ("" + data.formulariumNas + data.formulariumRs) {
                    case "10": warna = "black"; break;
                    case "01": warna = "red"; break;
                    case "00": warna = "blue";
                }
                return `<div class="item" style="color:${warna}">${data.namaSediaan} (${data.kode})</div>`;
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $stokAdmUrl ?>",
                    data: {q: typed, idDepo: idDepoFld.value, kodeRef: kodeFld.value},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            },
            onItemAdd(value) {
                /** @type {his.FatmaPharmacy.views.StokopnameUi.Form.BarangFields} */
                const obj = this.options[value];
                const elm = this._element;

                if (detail == "katalog" || detail == "persediaan") {
                    elm.value = obj.namaSediaan;
                    divElm.querySelector("#id_katalog").value = obj.kode;
                    return;
                }

                $.post({
                    url: "<?= $katalogDepoUrl ?>",
                    data: {
                        id: obj.id,
                        idKatalog: obj.kode,
                        idDepo: idDepoFld.value,
                        kode: kodeFld.value
                    },
                    /** @param {his.FatmaPharmacy.views.StokopnameUi.Form.BarangInner} data */
                    success(data) {
                        if (!data) return;

                        const id = userInt(divElm.querySelector(".no:last-child").innerHTML) + 1;
                        const trElm = closest(elm, "tr");
                        const fields = trElm.fields;

                        xCounter++;

                        trElm.id = data.kode;
                        fields.idKatalogFld.value = data.kode;
                        fields.idPabrikFld.value = data.idPabrik;
                        fields.idKemasanFld.value = data.idKemasanKecil;
                        fields.jumlahStokAdmFld.value = userFloat(data.jumlahStokAdm);
                        fields.hargaItemFld.value = currency(data.hp);
                        fields.hnaFld.value = data.hna;
                        fields.hjaFld.value = data.hja;
                        fields.phjaFld.value = data.phja;
                        fields.phjapbFld.value = data.phjaPb;
                        fields.diskonFld.value = data.diskon;
                        fields.ppnFld.value = data.ppn;
                        fields.refHargaFld.value = data.refHarga;
                        fields.idKatalogStc.innerHTML = data.kode;

                        const namaBarangStc = fields.namaBarangStc;
                        namaBarangStc.innerHTML = data.namaBarang;
                        namaBarangStc.classList.remove("tdinput");

                        fields.namaPabrikStc.innerHTML = data.namaPabrik;
                        fields.satuanStc.innerHTML = data.kodeKemasanKecil;
                        fields.jumlahStokAdmStc.innerHTML = userFloat(data.jumlahStokAdm);

                        const jumlahStokFisikFld = fields.jumlahStokFisikFld;
                        jumlahStokFisikFld.value = userFloat(0);
                        jumlahStokFisikFld.readonly = false;
                        jumlahStokFisikFld.dispatchEvent(new Event("focus"));

                        fields.akumulasiFisikStc.innerHTML = data.akumulasiFisik;
                        fields.selisihStc.innerHTML = "-" + data.jumlahStokAdm;
                        fields.hargaPokokItemStc.innerHTML = currency(data.hp);
                        fields.nilaiStc.innerHTML = "0";
                        fields.operatorStc.innerHTML = data.operator;
                        fields.tanggalStc.innerHTML = data.sysdateUpdate;
                        fields.tanggalKadaluarsaWgt.disabled = false;
                        fields.noBatchFld.disabled = false;

                        const idAf = userInt(divElm.querySelector(".akumulasiFisikStc:last-child").id) + 1;
                        const trStr = drawTr("tbody", {
                            class: ".tr-data",
                            id: "-",
                            td_1: {
                                hidden_1: {name: "idKatalog[]"},
                                hidden_2: {name: "idPabrik[]"},
                                hidden_3: {name: "idKemasan[]"},
                                hidden_4: {name: "jumlahStokAdm[]"},
                                hidden_5: {name: "hargaItem[]"},
                                hidden_6: {name: "hna[]"},
                                hidden_7: {name: "hja[]"},
                                hidden_8: {name: "phja[]"},
                                hidden_9: {name: "phjapb[]"},
                                hidden_10: {name: "diskon[]"},
                                hidden_11: {name: "ppn[]"},
                                hidden_12: {name: "refHarga[]"},
                                staticText: {class: ".no", text: id}
                            },
                            td_2: {
                                checkbox: {class: ".ck_one"}
                            },
                            td_3: {class: ".kd"},
                            td_4: {
                                class: ".nb",
                                input: {class: ".cariBarangFld"}
                            },
                            td_5: {class: ".np"},
                            td_6: {class: ".nk"},
                            td_7: {class: ".ja"},
                            td_8: {
                                input: {class: ".jf", name: "jumlahStokFisik[]", readonly: true}
                            },
                            td_9: {class: "af", id: idAf},
                            td_10: {class: ".sel"},
                            td_11: {
                                input: {class: ".bt", name: "noBatch[]", disabled: true}
                            },
                            td_12: {
                                input: {class: ".ex", name: "tanggalKadaluarsa[]", disabled: true}
                            },
                            td_13: {class: ".hp"},
                            td_14: {class: ".ni"},
                            td_15: {
                                checkbox: {class: ".update_opname", disabled: true}
                            },
                            td_16: {class: ".no2", text: id},
                            td_17: {class: ".op"},
                            td_18: {class: ".tgl"},
                        });
                        itemWgt.querySelector("tbody").insertAdjacentHTML("beforeend", trStr);
                    }
                });
            }
        });

        function akumulasiFisik(kode) {
            let akumulasiFisik = 0;

            itemWgt.querySelectorAll("tbody tr").forEach(/** @param {HTMLTableRowElement} trElm */ trElm => {
                if (kode != trElm.id) return;

                const fields = trElm.fields;
                const jumlahStokAdm = sysNum(fields.jumlahStokAdmStc.innerHTML);
                const jumlahStokFisik = sysNum(fields.jumlahStokFisikFld.innerHTML);
                const hargaPokokItem = sysNum(fields.hargaPokokItemStc.innerHTML);

                akumulasiFisik += jumlahStokFisik;
                const selisih = jumlahStokFisik - jumlahStokAdm;
                const nilai = jumlahStokFisik * hargaPokokItem;

                fields.akumulasiFisikStc.innerHTML = akumulasiFisik;
                fields.selisihStc.innerHTML = selisih;
                fields.nilaiStc.innerHTML = nilai;
            });
        }

        divElm.querySelector(".kembaliBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $tableWidgetId ?>").show();
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(formWgt, itemWgt, cariBarangWgt);
        tlm.app.registerWidget(this.constructor.widgetName, formWgt);
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
