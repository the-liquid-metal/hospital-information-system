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
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/detail.php the original file
 */
final class ViewDetail
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string $registerId,
        string $dataUrl, // TODO: php: uncategorized: ...
        string $formWidgetId,
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $toUserTime = Yii::$app->dateTime->transformFunc("toUserTime");
        ob_clean();
        ob_start();
        $jadwalOperasi = new \stdClass;
        $daftarDiagnosaOperasi = [];
        $daftarTindakanOperasi = [];
        $daftarAlatOperasi = [];
        ?>


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
                <th>Tanggal Lahir</th>
                <td><?= $toUserDate($jadwalOperasi->tanggalLahir) ?></td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td><?= $jadwalOperasi->kelamin ?></td>
            </tr>
            <tr>
                <th>No. Telpon</th>
                <td><?= $jadwalOperasi->noTelefon ?></td>
            </tr>
            <tr>
                <th>Jenis Pembayaran</th>
                <td><?= $jadwalOperasi->namaJenisCaraBayar ?></td>
            </tr>

            <?php if (in_array($jadwalOperasi->jenisCaraBayar, ['1', '2'])): ?>
            <tr>
                <th>Pembayaran</th>
                <td><?= $jadwalOperasi->namaCaraBayar ?></td>
            </tr>

                <?php if ($jadwalOperasi->jenisCaraBayar == '2'): ?>
                <tr>
                    <th>Hubungan Keluarga</th>
                    <td><?= $jadwalOperasi->hubunganKeluargaPenjamin ?></td>
                </tr>
                <?php endif ?>

            <tr>
                <th>No. Peserta</th>
                <td><?= $jadwalOperasi->noPesertaJaminan ?></td>
            </tr>
            <tr>
                <th>Nama Peserta</th>
                <td><?= $jadwalOperasi->namaPesertaJaminan ?></td>
            </tr>
            <tr>
                <th>Asal Wilayah</th>
                <td><?= $jadwalOperasi->asalWilayahJabotabek . ($jadwalOperasi->asalWilayah ? ': ' . $jadwalOperasi->asalWilayah : '') ?></td>
            </tr>
            <?php endif ?>

            <tr>
                <th>Post OP</th>
                <td><?= $jadwalOperasi->postOp ?></td>
            </tr>
            <tr>
                <th>Diagnosa</th>
                <td>
                    <table class="table">
                        <tr>
                            <th style="width:3%">No.</th>
                            <th>Deskripsi</th>
                        </tr>
                        <?php foreach ($daftarDiagnosaOperasi as $idx => $row3): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td><?= $row3->diagnosaTindakan ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </td>
            </tr>
            <tr>
                <th>Tindakan</th>
                <td>
                    <table class="table">
                        <tr>
                            <th style="width:3%">No.</th>
                            <th>Deskripsi</th>
                        </tr>
                        <?php foreach ($daftarTindakanOperasi as $idx => $row3): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><?= $row3->diagnosaTindakan ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </td>
            </tr>
            <tr>
                <th>Ruang/kelas</th>
                <td id="ruang"><?= $jadwalOperasi->ruang ?>/<?= $jadwalOperasi->kelas ?></td>
            </tr>
            <tr>
                <th>Permintaan Akomodasi</th>
                <td><?= $jadwalOperasi->requestAkomodasi ?></td>
            </tr>
            <tr>
                <th>Tempat Tidur</th>
                <td id="tempat_tidur"><?= $jadwalOperasi->tempatTidur ?></td>
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
                <th>Rencana Operasi</th>
                <td><?= $toUserDatetime($jadwalOperasi->rencanaOperasi) . " - " . $toUserTime($jadwalOperasi->rencanaOperasiEnd) ?></td>
            </tr>
            <tr>
                <th>Ruang Operasi</th>
                <td><?= $jadwalOperasi->namaRuangOperasi ?></td>
            </tr>
            <tr>
                <th>Alat Operasi</th>
                <td>
                    <table class="table">
                        <tr>
                            <th>No.</th>
                            <th>Alat Operasi</th>
                            <th>Jumlah</th>
                        </tr>
                        <?php foreach ($daftarAlatOperasi as $idx => $row3): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><?= $row3->nama ?></td>
                            <td><?= $row3->jumlah . ' ' . $row3->satuan ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="form-actions">
        <a href="<?= $formWidgetId ."/".$jadwalOperasi->id ?>" class="btn edit-btn">Edit Data</a>
        <button class="btn btn-primary btn-save" type="submit" name="submit" value="save">Simpan</button>
   </div>
</div>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    constructor(divElm) {
        super();

        divElm.querySelector(".btn-save").style.display = "none";

        divElm.querySelector(".edit-ruangrawat-btn").addEventListener("click", (event) => {
            const editRuangRawatBtn = event.target;
            if (editRuangRawatBtn.classList.contains("disabled")) return;

            editRuangRawatBtn.classList.add("disabled");
            const ruangElm = divElm.querySelector("#ruang");
            ruangElm.style.display = "none";
            ruangElm.insertAdjacentHTML("afterend", `
                <input type="text" class="input-small" name="ruang" placeholder="Ruang"/>
                <input type="text" class="input-medium" name="kelas" placeholder="Kamar/kelas"/>`
            );
            divElm.querySelector(".btn-save").style.display = "block";
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push();
        tlm.app.registerWidget(this.constructor.widgetName, this);
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
