<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\ReturFarmasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/ReturnFarmasi/index.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl,
        string $deleteUrl,
        string $viewBarangWidgetId,
        string $viewGasMedisWidgetId,
        string $printWidgetId,
        string $formObatWidgetId,
        string $formGasMedisWidgetId,
        string $formLainnyaWidgetId,
        string $formRevisiWidgetId,
        string $formVerGudangWidgetId,
        string $formVerTerimaObatWidgetId,
        string $formVerTerimaGasMedisWidgetId,
        string $formVerTerimaLainnyaWidgetId,
        string $formVerAkuntansiObatWidgetId,
        string $formVerAkuntansiGasMedisWidgetId,
        string $formVerAkuntansiLainnyaWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<!--suppress TypeScriptUnresolvedVariable -->
<script type="text/tsx">
namespace his.FatmaPharmacy.views.ReturFarmasiUi.Table {
    export interface Fields {
        kode:             string;
        tipeDokumen:      string;
        statusLinked:     string;
        statusClosed:     string;
        statusIzinRevisi: string;
        jenisRetur:       string;
        tanggalDokumen:   string;
        noDokumen:        string;
        subjenisAnggaran: string;
        noTerima:         string;
        namaPemasok:      string;
        nilaiAkhir:       string;
        namaUserGudang:   string;
        verTanggalGudang: string;
        verGudang:        string;
        verTerima:        string;
        verAkuntansi:     string;
        updatedTime:      string;
        keterangan:       string;
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
                heading3: {text: tlm.stringRegistry._<?= $h("Daftar Retur Penerimaan") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            widthTable: {
                class: ".daftarReturFarmasiTbl",
                thead: {
                    tr: {
                        td_1:  {text: ""},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Jenis Retur") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Tanggal Retur") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("No. Retur") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Jenis Anggaran") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("No. Ref. Terima") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Nama Pemasok") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Nilai") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("User Ver.") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Tanggal Ver.") ?>},
                        td_11: {text: tlm.stringRegistry._<?= $h("Ver. Gudang") ?>},
                        td_12: {text: tlm.stringRegistry._<?= $h("Ver. Terima") ?>},
                        td_13: {text: tlm.stringRegistry._<?= $h("Ver. Akuntansi") ?>},
                        td_14: {text: tlm.stringRegistry._<?= $h("Tanggal Ubah") ?>},
                        td_15: {text: tlm.stringRegistry._<?= $h("Keterangan") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const str = tlm.stringRegistry;
        const draw = spl.TableDrawer.drawButton;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const tableWgt = new spl.TableWidget({
            element: divElm.querySelector(".daftarReturFarmasiTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {formatter(unused, item) {
                    const {kode, tipeDokumen, verGudang, verTerima, noDokumen, statusLinked, statusClosed, statusIzinRevisi} = item;
                    const buttonCls = (verGudang == '1') ? "warning" : "primary";
                    const val1 = JSON.stringify({kode, tipeDokumen});
                    const val2 = JSON.stringify({kode, noDokumen});

                    const viewBtn      = draw({class: ".viewBtn",      type: "info",    icon: "list",   value: val1, title: str._<?= $h("Detail") ?>});
                    const printBtn     = draw({class: ".printBtn",     type: "success", icon: "print",  value: kode, title: str._<?= $h("Cetak") ?>});
                    const editBtn      = draw({class: ".editBtn",      type: buttonCls, icon: "pencil", value: val1, title: str._<?= $h("Verifikasi Penerimaan") ?>});
                    const deleteBtn    = draw({class: ".deleteBtn",    type: "danger",  icon: "trash",  value: val2, title: str._<?= $h("Hapus") ?>});
                    const addRevisiBtn = draw({class: ".addRevisiBtn", type: "primary", icon: "pencil", value: kode, title: str._<?= $h("Revisi Penerimaan") ?>});

                    return viewBtn + printBtn +
                        (verTerima == "0" ? editBtn : "") +
                        (verTerima == "0" ? deleteBtn : "") +
                        (statusLinked == "1" && statusClosed == "0" && statusIzinRevisi == "1" ? addRevisiBtn : "");
                }},
                2:  {field: "jenisRetur"},
                3:  {field: "tanggalDokumen", formatter: tlm.dateFormatter},
                4:  {field: "noDokumen"},
                5:  {field: "subjenisAnggaran"},
                6:  {field: "noTerima"},
                7:  {field: "namaPemasok"},
                8:  {field: "nilaiAkhir", formatter: tlm.floatFormatter},
                9:  {field: "namaUserGudang"},
                10: {field: "verTanggalGudang", formatter: tlm.dateFormatter},
                11: {formatter(unused, item) {
                    const {verGudang, verTerima, kode} = item;
                    switch ("" + verGudang + verTerima) {
                        case "11": return draw({class: "___",           type: "success", icon: "check",   value: "___", title: str._<?= $h("Sudah Verifikasi Gudang") ?>});
                        case "01": return draw({class: "___",           type: "danger",  icon: "warning", value: "___", title: str._<?= $h("Belum Verifikasi Gudang") ?>});
                        case "00": return draw({class: ".verGudangBtn", type: "danger",  icon: "warning", value: kode,  title: str._<?= $h("Belum Verifikasi Gudang") ?>});
                    }
                }},
                12: {formatter(unused, item) {
                    const {verTerima, verGudang, kode, tipeDokumen} = item;
                    const val = JSON.stringify({kode, tipeDokumen});
                    switch ("" + verTerima + verGudang) {
                        case "11": return draw({class: "___",           type: "success", icon: "check",   value: "___", title: str._<?= $h("Sudah Verifikasi Penerimaan") ?>});
                        case "01": return draw({class: ".verTerimaBtn", type: "danger",  icon: "warning", value: val,   title: str._<?= $h("Belum Verifikasi Penerimaan") ?>});
                        case "00": return draw({class: "___",           type: "danger",  icon: "warning", value: "___", title: str._<?= $h("Belum Verifikasi Penerimaan") ?>});
                    }
                }},
                13: {formatter(unused, item) {
                    const {verAkuntansi, verTerima, kode, tipeDokumen} = item;
                    const val = JSON.stringify({kode, tipeDokumen});
                    switch ("" + verAkuntansi + verTerima) {
                        case "11": return draw({class: "___",              type: "success", icon: "check",   value: "___", title: str._<?= $h("Sudah Verifikasi Penerimaan") ?>});
                        case "01": return draw({class: ".verAkuntansiBtn", type: "danger",  icon: "warning", value: val,   title: str._<?= $h("Belum Verifikasi Akuntansi") ?>});
                        case "00": return draw({class: "___",              type: "danger",  icon: "warning", value: "___", title: str._<?= $h("Belum Verifikasi Penerimaan") ?>});
                    }
                }},
                14: {field: "updatedTime", formatter: tlm.dateFormatter},
                15: {field: "keterangan"}
            }
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const viewBtn = event.target;
            if (!viewBtn.matches(".viewBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(viewBtn.value);
            const widgetId = tipeDokumen == "5" ? "<?= $viewGasMedisWidgetId ?>" : "<?= $viewBarangWidgetId ?>";
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadData({kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const printBtn = event.target;
            if (!printBtn.matches(".printBtn")) return;

            const widget = tlm.app.getWidget("_<?= $printWidgetId ?>");
            widget.show();
            widget.loadData({kode: printBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(editBtn.value);
            let widgetId;
            switch (tipeDokumen) {
                case "0": widgetId = "<?= $formObatWidgetId ?>"; break;
                case "5": widgetId = "<?= $formGasMedisWidgetId ?>"; break;
                default:  widgetId = "<?= $formLainnyaWidgetId ?>";
            }
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadProfile("edit", {kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;

            const {kode, noDokumen} = JSON.parse(deleteBtn.value);
            const modulMdl = divElm.querySelector("#modal-modul");
            const pullBtn = divElm.querySelector("#btn-pull");

            if (modulMdl.classList.contains("fade") == false) {
                modulMdl.classList.add("fade");
            }

            divElm.querySelector(".modal-body").innerHTML = `
                Menghapus dokumen akan menghapus detail dan semua dokumen yang terhubung dengannya.<br/>
                Apakah Anda yakin ingin menghapus retur-farmasi dengan No: <strong>${noDokumen}</strong>?
            `;
            divElm.querySelector(".modal-header").innerHTML = `<h5 style="color:#FFF">Hapus retur farmasi</h5>`;

            pullBtn.dataset.kode = kode;
            pullBtn.innerHTML = `<i class="fa fa-trash"> Hapus</i>`;
            pullBtn.dataset.nodoc = noDokumen;

            if (pullBtn.classList.contains("btn-primary")) {
                pullBtn.classList.remove("btn-primary");
                pullBtn.classList.add("btn-danger");
            }

            modulMdl.modal("show");
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const addRevisiBtn = event.target;
            if (!addRevisiBtn.matches(".addRevisiBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formRevisiWidgetId ?>");
            widget.show();
            widget.loadData({xyz: addRevisiBtn.value}, true); // TODO: js: uncategorized: finish this
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const verGudangBtn = event.target;
            if (!verGudangBtn.matches(".verGudangBtn")) return;

            const widget = tlm.app.getWidget("_<?= $formVerGudangWidgetId ?>");
            widget.show();
            widget.loadData({kode: verGudangBtn.value}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const verTerimaBtn = event.target;
            if (!verTerimaBtn.matches(".verTerimaBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(verTerimaBtn.value);
            let widgetId;
            switch (tipeDokumen) {
                case "0": widgetId = "<?= $formVerTerimaObatWidgetId ?>"; break;
                case "5": widgetId = "<?= $formVerTerimaGasMedisWidgetId ?>"; break;
                default:  widgetId = "<?= $formVerTerimaLainnyaWidgetId ?>";
            }
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadProfile("verifikasiPenerimaan", {kode}, true);
        });

        tableWgt.addDelegateListener("tbody", "click", (event) => {
            const verAkuntansiBtn = event.target;
            if (!verAkuntansiBtn.matches(".verAkuntansiBtn")) return;

            const {kode, tipeDokumen} = JSON.parse(verAkuntansiBtn.value);
            let widgetId;
            switch (tipeDokumen) {
                case "0": widgetId = "<?= $formVerAkuntansiObatWidgetId ?>"; break;
                case "5": widgetId = "<?= $formVerAkuntansiGasMedisWidgetId ?>"; break;
                default:  widgetId = "<?= $formVerAkuntansiLainnyaWidgetId ?>";
            }
            const widget = tlm.app.getWidget(widgetId);
            widget.show();
            widget.loadProfile("verifikasiAkuntansi", {kode}, true);
        });

        // JUNK -----------------------------------------------------

        divElm.querySelector(".hide_row").addEventListener("click", (event) => {
            const elm = /** @type {HTMLButtonElement}*/ event.target;
            const {link: dataUrl, type: dataTipe, id: dataId} = elm.dataset;

            if (dataTipe == 'warning' || dataTipe == 'info') {
                $.post({
                    url: dataUrl,
                    // TODO: js: uncategorized: refactor this
                    data: {
                        dataFilter: {id_info: dataId}
                    },
                    success(ret) {
                        if (ret == '1') {
                            divElm.querySelector("#notif_" + dataId).remove();
                            const id = dataId - 1;
                            divElm.querySelector("li#divnotif_" + id).remove();

                            const labelElm = divElm.querySelector("#label_" + dataTipe);
                            const i = parseInt(labelElm.innerHTML);
                            labelElm.innerHTML = i - 1;
                        }
                    }
                });
            }

            closest(elm, "tr").remove();
        });

        divElm.querySelector("#btn-pull").addEventListener("click", (event) => {
            const dataset = event.target.dataset;
            $.post({
                url: "<?= $deleteUrl ?>",
                data: {kode: dataset.kode, keterangan: dataset.nodoc},
                success(data) {
                    if (data) {
                        location.reload();
                    } else {
                        divElm.querySelector("#modal-modul").modal("hide");
                        alert(str._<?= $h("Gagal hapus data.") ?>);
                    }
                }
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(tableWgt);
        tlm.app.registerWidget(this.constructor.widgetName, tableWgt);
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
