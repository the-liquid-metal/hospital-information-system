<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\JadwalOperasiUi;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/booking.php the original file
 */
final class TableBooking
{
    private string $output;

    public function __construct(
        string   $registerId,
        array    $approveAccess,
        array    $editRoomAccess,
        array    $editAccess,
        array    $deleteAccess,
        iterable $dataUrl,
        string   $deleteUrl,
        string   $viewDetailWidgetId,
        string   $tableWidgetId,
        string   $tableBookingWidgetId,
        string   $formWidgetId,
        string   $formApproveWidgetId,
        string   $formRuangRawatWidgetId,
        string   $formEditWidgetId,
        string   $formJadwalUlangWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $rescheduleData = [];
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.JadwalOperasi.Booking {
    export interface Fields {
        id:                "id";
        rencanaOperasi:    "rencana_operasi";
        rencanaOperasiEnd: "rencana_operasi_end";
        kodeRekamMedis:    "no_rm";
        nama:              "nama";
        operator:          "operator";
        requestAkomodasi:  "request_akomodasi";
        kelasRm:           "kelas_rm";
        namaPoli:          "nama_poli";
        ruang:             "ruang";
        kelas:             "kelas";
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{approve: boolean, editRoom: boolean, edit: boolean, delete: boolean}}
     */
    static getAccess(role) {
        const pool = {
            approve: JSON.parse(`<?=json_encode($approveAccess) ?>`),
            editRoom: JSON.parse(`<?=json_encode($editRoomAccess) ?>`),
            edit: JSON.parse(`<?=json_encode($editAccess) ?>`),
            delete: JSON.parse(`<?=json_encode($deleteAccess) ?>`),
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
                header3: {text: tlm.stringRegistry._<?= $h("Booking Jadwal Operasi") ?>}
            }
        },
        row_2: {
            widthColumn: {
                button_1: {class: ".showTableBtn", text: tlm.stringRegistry._<?= $h("Jadwal Operasi") ?>},
                button_2: {class: ".bookingBtn",   text: tlm.stringRegistry._<?= $h("Booking Ruang Operasi") ?>},
                button_3: {class: ".addBtn",       text: tlm.stringRegistry._<?= $h("Tambah") ?>},
            }
        },
        row_3: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1:  {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_2:  {text: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>},
                        td_3:  {text: tlm.stringRegistry._<?= $h("Nama Pasien") ?>},
                        td_4:  {text: tlm.stringRegistry._<?= $h("Operator") ?>},
                        td_5:  {text: tlm.stringRegistry._<?= $h("Akomodasi") ?>},
                        td_6:  {text: tlm.stringRegistry._<?= $h("Kelas") ?>},
                        td_7:  {text: tlm.stringRegistry._<?= $h("Poli") ?>},
                        td_8:  {text: tlm.stringRegistry._<?= $h("Ruang Rawat") ?>},
                        td_9:  {text: tlm.stringRegistry._<?= $h("Kamar") ?>},
                        td_10: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                    }
                }
            }
        },
        row_4: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_5: {
            // TODO: js: uncategorized: finish this
        }
    };

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;
        const draw = spl.TableDrawer.drawButton;
        const access = this.constructor.getAccess(tlm.userRole);
        const hapusStr = str._<?= $h("Apakah Anda yakin ingin menghapus?") ?>;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        divElm.querySelector(".showTableBtn").addEventListener("click", () => {
            tlm.app.getWidget("#<?= $tableWidgetId ?>").show();
        });

        divElm.querySelector(".bookingBtn").addEventListener("click", () => {
            tlm.app.getWidget("#<?= $tableBookingWidgetId ?>").show();
        });

        divElm.querySelector(".addBtn").addEventListener("click", () => {
            tlm.app.getWidget("#<?= $formWidgetId ?>").show();
        });

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1:  {field: "rencanaOperasi"},
                2:  {field: "kodeRekamMedis"},
                3:  {field: "nama"},
                4:  {field: "operator"},
                5:  {field: "requestAkomodasi"},
                6:  {field: "kelasRm"},
                7:  {field: "namaPoli"},
                8:  {field: "ruang"},
                9:  {field: "kelas"},
                10: {formatter(unused, {id}) {
                    const approveBtn   = draw({class: ".approveBtn",   value: id, icon: "ok",     text: str._<?= $h("Setujui") ?>});
                    const editRuangBtn = draw({class: ".editRuangBtn", value: id, icon: "pencil", text: str._<?= $h("Ruang Rawat") ?>});
                    const editBtn      = draw({class: ".editBtn",      value: id, icon: "pencil", text: str._<?= $h("Edit") ?>});
                    const deleteBtn    = draw({class: ".deleteBtn",    value: id, icon: "remove", text: str._<?= $h("Delete") ?>});

                    return "" +
                        (access.approve ? approveBtn : "") +
                        (access.editRoom ? editRuangBtn : "") +
                        (access.edit ? editBtn : "") +
                        (access.delete ? deleteBtn : "");
                }}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const approveBtn = event.target;
            if (!approveBtn.matches(".approveBtn")) return;

            $.post({
                url: "<?= $formApproveWidgetId ?>",
                data: {id: approveBtn.value},
                success(data) {
                    const dom = $(data);
                    const box = bootbox.modal(dom, "Approval Jadwal Operasi", {backdrop: "static"});
                    const buttonClose = `<a class="btn close-btn" href="javascript:">Tutup</a>`;

                    dom.filter("script").each(() => {
                        $.globalEval(this.text || this.textContent || this.innerHTML || "");
                    });

                    box.append(`<div class="modal-footer"></div>`);
                    box.querySelector(".form-actions").detach().prependTo(".modal-footer").classList.remove("form-actions").append(buttonClose);
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const editRuangBtn = event.target;
            if (!editRuangBtn.matches(".editRuangBtn")) return;

            // TODO: js: uncategorized: convert ajax to tlm.app.getWidget
            $.post({
                url: "<?= $formRuangRawatWidgetId ?>",
                data: {id: editRuangBtn.value},
                success(data) {
                    const dom = $(data);
                    const box = bootbox.modal(dom, "Edit Ruang Rawat", {backdrop: "static"});
                    const buttonClose = `<a class="btn close-btn" href="javascript:">Tutup</a>`;

                    dom.filter("script").each(() => {
                        $.globalEval(this.text || this.textContent || this.innerHTML || "");
                    });

                    box.append(`<div class="modal-footer"></div>`);
                    box.querySelector(".form-actions").detach().prependTo(".modal-footer").classList.remove("form-actions").append(buttonClose);
                }
            });
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;
            if (!confirm(hapusStr)) return;

            $.ajax({
                url: "<?= $deleteUrl ?>",
                data: {id: deleteBtn.value}
            });
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const editBtn = event.target;
            if (!editBtn.matches(".editBtn")) return;
            if (!confirm(hapusStr)) return;

            const widget = tlm.app.getWidget("_<?= $formEditWidgetId ?>");
            widget.show();
            widget.loadData({id: editBtn.value}, true);
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const detailBtn = event.target;
            if (!detailBtn.matches(".detail-btn")) return;

            const {rencanaOperasi, rencanaOperasiEnd} = detailBtn.dataset;
            $.post({
                url: "<?= $viewDetailWidgetId ?>",
                data: {rencanaOperasi, rencanaOperasiEnd},
                success(data) {
                    const dom = $(data);
                    dom.filter("script").each(() => {
                        $.globalEval(this.text || this.textContent || this.innerHTML || "");
                    });
                }
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, itemWgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<div>
    <?php if ($rescheduleData): ?>
        <div style="clear:both; padding-top:15px">
            <h3>Jadwalkan Ulang:</h3>
            <ol class="jadwal-ulang">
                <?php foreach ($rescheduleData as $row3): ?>
                    <li>
                        <a href="<?= $formJadwalUlangWidgetId ."/".$row3->id."/2" ?>">
                            <?= $row3->kodeRekamMedis . ": " . $row3->nama ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ol>
        </div>
    <?php endif ?>
</div>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
