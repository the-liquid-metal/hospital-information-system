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
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/editruangrawat.php the original file
 */
final class FormEditRuangRawat
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string   $registerId,
        string   $dataUrl, // TODO: php: uncategorized: ...
        string   $actionUrl,
        string   $tableBookingWidgetId,
    ) {
        $toUserPartialDatetime = Yii::$app->dateTime->transformFunc("toUserPartialDatetime");
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $daftarJadwalOperasi = [];
        $daftarKamar = [];
        ?>


<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    constructor(divElm) {
        super();
        const str = tlm.stringRegistry;

        divElm.querySelector(".editruangrawat").addEventListener("click", (event) => {
            const tRawat = divElm.querySelector("#t_rawat").value;
            const gedung = divElm.querySelector("#gedung").value;
            const ruang = divElm.querySelector("#ruang").value;
            const kelas = divElm.querySelector("#kelas").value;
            const tempatTidur = divElm.querySelector("#tempat_tidur").value;

            if (!tRawat || !tempatTidur) {
                alert(str._<?= $h("Ruang rawat / tempat tidur belum diisi.") ?>);
                return;
            }

            $.post({
                url: event.target.getAttribute("href"),
                data: {gedung, ruang, kelas, tempatTidur, tRawat, save: true},
                success() {
                    tlm.app.getWidget("_<?= $tableBookingWidgetId ?>").show();
                }
            });
        });

        new spl.SelectWidget({element: divElm.querySelector("#t_rawat")});

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push();
        tlm.app.registerWidget(this.constructor.widgetName, this);
    }
});
</script>

<!-- TODO: html: convert to js -->
<?php foreach ($daftarJadwalOperasi as $row): ?>
<div id="row-<?= $row->id ?>" class="data-rows">
    <table class='table table-jadwal'>
        <tbody>
            <tr>
                <th style="width:25%">Kode Rekam Medis</th>
                <td><?= $row->kodeRekamMedis ?></td>
                <th style="width:20%"></th>
                <td style="width:15%"></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td><?= $row->nama ?></td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Operator</th>
                <td><?= $row->operator ?></td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Jenis Operasi</th>
                <td><?= $row->jenisOperasi ?></td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Instalasi Asal</th>
                <td><?= $row->namaInstalasi ?></td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Poli Asal</th>
                <td><?= $row->namaPoli ?></td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Rencana Operasi</th>
                <td><?= $toUserPartialDatetime($row->rencanaOperasi) . ' - ' . $toUserPartialDatetime($row->rencanaOperasiEnd) ?></td>
                <th>Cara Bayar</th>
                <td><?= $row->jenisCaraBayar ?></td>
            </tr>
            <tr>
                <th>Ruang Operasi</th>
                <td><?= $row->namaRuangOperasi ?></td>
                <th>Kelas</th>
                <td><?= $row->kelasRekamMedis ?></td>
            </tr>
            <tr>
                <th>Permintaan Akomodasi</th>
                <td><?= $row->requestAkomodasi ?></td>
                <th>Post OP</th>
                <td><?= $row->postOp ?></td>
            </tr>
            <tr>
                <th>Ruang Rawat</th>
                <td class="form-inline">
                    <select class="form-control" name="t_rawat" id="t_rawat">
                        <option value=""></option>
                        <?php foreach ($daftarKamar as $kamar): ?>
                            <option value="<?= $kamar[0] . ':' . $kamar->namaKamar ?>"><?= $kamar[0] . ' --> ' . $kamar->namaKamar ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>Tempat Tidur</th>
                <td class="form-inline">
                    <input class="input-medium" name="tempat_tidur" id="tempat_tidur" placeholder="Tempat Tidur" value="<?= $row->tempatTidur ?>"/>
                    <input type="hidden" id="tempat_tidur_id" name="tempat_tidur_id"/>
                </td>
                <th></th>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="form-actions">
        <a href="<?= $actionUrl ."/".$row->id ?>" class="btn btn-primary editruangrawat">Simpan</a>
    </div>
</div>
<?php endforeach ?>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
