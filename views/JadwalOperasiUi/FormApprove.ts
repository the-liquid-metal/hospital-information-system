<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\JadwalOperasiUi;

use tlm\libs\LowEnd\components\DateTimeException;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/approved.php the original file
 */
final class FormApprove
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string $registerId,
        string $dataUrl, // TODO: php: uncategorized: ...
        string $actionUrl,
        string $ruangOperasiUrl,
        string $tableBookingWidgetId,
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $jadwalOperasi = new \stdClass;
        $ruangOperasi = [];
        ?>


<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    _structure = {};

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        new spl.DateTimeWidget({
            element: divElm.querySelector(".timepicker1"),
            ...tlm.dateWidgetSetting
        });

        if (divElm.querySelector("#rencana_operasi_jam").value == '00:00') {
            divElm.querySelector("#ruang_operasi option").remove();
        }

        divElm.querySelector(".check-ruang").addEventListener("click", () => {
            const tglRencanaOperasi = divElm.querySelector("#rencana_operasi_tgl").value;
            const tgl = tglRencanaOperasi + "_" + divElm.querySelector("#rencana_operasi_jam").value.replace(":", ".");
            const tglEnd = tglRencanaOperasi + "_" + divElm.querySelector("#rencana_operasi_end_jam").value.replace(":", ".");

            $.post({
                url: "<?= $ruangOperasiUrl ?>",
                data: {tanggalAwal: encodeURI(tgl), tanggalAkhir: encodeURI(tglEnd)},
                success(data) {
                    let options = `<option value=""></option>`;
                    data.ruang_operasi.forEach(val => options += `<option value="${val.id}">${val.nama}</option>`);
                    divElm.querySelector("#ruang_operasi").innerHTML = options;
                }
            });
        });

        divElm.querySelectorAll(".approve, .reschedule").forEach(item => item.addEventListener("click", (event) => {
            const dataRowsElm = divElm.querySelector(".data-rows");
            if (!dataRowsElm.querySelector("select[name=ruang_operasi]").value && event.target.classList.contains("approve")) {
                alert(str._<?= $h("Ruang Operasi tidak ditentukan.") ?>);
                return;
            }

            const tglRencanaOperasi = divElm.querySelector("#rencana_operasi_tgl").value;
            const tgl = tglRencanaOperasi + " " + divElm.querySelector("#rencana_operasi_jam").value;
            const tglEnd = tglRencanaOperasi + " " + divElm.querySelector("#rencana_operasi_end_jam").value;

            $.post({
                url: "<?= $actionUrl ?>",
                data: {
                    ruangOperasi: dataRowsElm.querySelector('select[name="ruang_operasi"]').value,
                    rencanaOperasi: tgl,
                    rencanaOperasiEnd: tglEnd,
                    save: true
                },
                success() {
                    tlm.app.getWidget("_<?= $tableBookingWidgetId ?>").show();
                }
            });
        }));

        this._element = divElm;
        divElm.moduleWidget = this;
        tlm.app.registerWidget(this.constructor.widgetName, this);
    }

    show() {
        // TODO: js: uncategorized: implement this method (copy from spl.InputWidget)
    }
});
</script>

<!-- TODO: html: convert to js -->
<div id="<?= $registerId ?>">
        <div id="row-<?= $jadwalOperasi->id ?>" class="data-rows">
            <table class='table table-jadwal'>
                <tbody>
                    <tr>
                        <th>Kode Rekam Medis</th>
                        <td><?= $jadwalOperasi->kodeRekamMedis ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?= $jadwalOperasi->nama ?></td>
                    </tr>
                    <tr>
                        <th>Ruang/kelas</th>
                        <td class="r_k"><?= $jadwalOperasi->ruang ?> - <?= $jadwalOperasi->kelas ?></td>
                    </tr>
                    <tr>
                        <th>Tempat Tidur</th>
                        <td><?= $jadwalOperasi->tempatTidur ?></td>
                    </tr>
                    <tr>
                        <th>Operator</th>
                        <td><?= $jadwalOperasi->operator ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Operasi</th>
                        <td><?= $jadwalOperasi->jenisOperasi ?></td>
                    </tr>
                    <tr>
                        <th>Instalasi</th>
                        <td><?= $jadwalOperasi->namaInstalasi ?></td>
                    </tr>
                    <tr>
                        <th>Poli</th>
                        <td><?= $jadwalOperasi->namaPoli ?></td>
                    </tr>
                    <tr>
                        <th>Durasi Operasi</th>
                        <td><?= $jadwalOperasi->durasiOperasi . " jam" ?></td>
                    </tr>
                    <tr>
                        <th>Rencana Operasi</th>
                        <td class="form-inline">
                            <div class="input-append date">
                                <input class="input-small" id="rencana_operasi_tgl" name="rencana_operasi_tgl" value="<?= $toUserDate($jadwalOperasi->rencanaOperasi) ?>" readonly />
                                <div class="add-on">
                                    <i class="icon-calendar"></i>
                                </div>
                            </div>
                            <div class="input-append timepicker1">
                                <input class="input-mini timepicker" data-format="hh:mm" name="rencana_operasi_jam" id="rencana_operasi_jam" value="<?= date('H:i', strtotime($jadwalOperasi->rencanaOperasi)) ?>"/>
                                <div class="add-on">
                                    <i class="icon-time" data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                </div>
                            </div>
                            -
                            <div class="input-append timepicker1">
                                <input class="input-mini timepicker" data-format="hh:mm" name="rencana_operasi_end_jam" id="rencana_operasi_end_jam" value="<?= date('H:i', strtotime($jadwalOperasi->rencanaOperasiEnd)) ?>"/>
                                <div class="add-on">
                                    <i class="icon-time" data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                </div>
                            </div>
                            <button class="btn btn-primary check-ruang">
                                <i class="icon-ok icon-white"></i> Cek Ruang
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <th>Ruang Operasi</th>
                        <td>
                            <select name="ruang_operasi" class="span3" id="ruang_operasi">
                                <option></option>
                                <?php foreach ($ruangOperasi as $row2): ?>
                                <option value="<?=  $row2->id ?>"><?= $row2->nama ?></option>
                                <?php endforeach ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Status</th> <!-- ORIGINALLY FROM SUBMIT BUTTONS -->
                        <td>
                            <input type="hidden" name="id" value="<?= $jadwalOperasi->id ?>"/>
                            <select name="status">
                                <option value="1">Setujui</option>
                                <option value="2">Jadwalkan Ulang</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="approve reschedule"></button>
        </div>
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
