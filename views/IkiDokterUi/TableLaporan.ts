<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\IkiDokterUi;

use tlm\libs\LowEnd\components\DateTimeException;
use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/ikidokter/laporan.php the original file
 */
final class TableLaporan
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     */
    public function __construct(
        string $registerId,
        string $actionUrl,
        string $dokterBySmfUrl,
        string $dokterSelect,
        string $smfSelect
    ) {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $toUserFloat = Yii::$app->number->toUserFloat();
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $daftarResep = [];
        $connection = Yii::$app->dbFatma;

        $post = $_POST;
        $html = "";
        $frs = 0;
        $fornas = 0;
        $lainnya = 0;
        $no = 0;

        if (isset($post["search"])) {
            $smfIsAll = !isset($post["filtersmf"]) || $post["filtersmf"] == "all";
            $dokterIsAll = !isset($post["filterdokter"]) || $post["filterdokter"] == "all";

            $thSmf = $smfIsAll ? '<th>SMF</th>' : "";
            $thDokter = $dokterIsAll ? '<th>Dokter</th>' : "";

            $r = 0;
            $koderacik = "";
            $resepnow = "";
            $racik = 0;
            $totresep = 0;
            $subtotal = 0;

            foreach ($daftarResep as $resep) {
                if (!$resep->kodeRacik || $resep->kodeRacik != $koderacik) {
                    $r++;
                } else {
                    $racik++;
                }

                if ($resep->noResep != $resepnow) {
                    if ($resepnow) {
                        $tdSmf = $smfIsAll ? '<td></td>' : "";
                        $tdDokter = $dokterIsAll ? '<td></td>' : "";

                        $html .= "
                            <tr>
                                $tdSmf
                                $tdDokter
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class='text-right'>" . $toUserFloat($subtotal) . "</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>";

                        $subtotal = 0;
                    }

                    $resepnow = $resep->noResep;
                    $totresep++;

                    if ($smfIsAll) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__."
                            -- LINE: ".__LINE__."
                            SELECT nama_smf
                            FROM rsupf.master_smf
                            WHERE kode = '{$resep->idPegawai}'
                            LIMIT 1
                        ";
                        $smfname = $connection->createCommand($sql)->queryScalar();
                        $tdSmf = "<td>".$smfname."</td>";
                    } else {
                        $tdSmf = "";
                    }

                    $tdDokter = $dokterIsAll ? "<td>{$resep->name}</td>" : "";

                    $html .= "
                        <tr>
                            $tdSmf
                            $tdDokter
                            <td>{$resep->noResep}</td>
                            <td>{$toUserDate($resep->tanggalPenjualan)}</td>
                        ";

                } else {
                    $tdSmf = $smfIsAll ? '<td></td>' : "";
                    $tdDokter = $dokterIsAll ? '<td></td>' : "";

                    $html .= "
                        <tr>
                            $tdSmf
                            $tdDokter
                            <td></td>
                            <td></td>
                        ";

                }

                $subtotal += $resep->jumlahPenjualan * $resep->hargaJual;

                if ($resep->formulariumNas == "1") {
                    $tdFornas = "v";
                    $fornas++;
                } else {
                    $tdFornas = "";
                }

                if ($resep->formulariumNas == "0" && $resep->formulariumRs == "1") {
                    $tdFrs = "v";
                    $frs++;
                } else {
                    $tdFrs = "";
                }

                if ($resep->formulariumNas == "0" && $resep->formulariumRs == "0") {
                    $tdLainnya = "v";
                    $lainnya++;
                } else {
                    $tdLainnya = "";
                }

                $html .= '
                    <td>' . $resep->namaBarang . '</td>
                    <td class="text-right">' . $resep->jumlahPenjualan . '</td>
                    <td class="text-right">' . $toUserFloat($resep->jumlahPenjualan * $resep->hargaJual) . '</td>
                    <td>' . $tdFornas . '</td>
                    <td>' . $tdFrs . '</td>
                    <td>' . $tdLainnya . '</td>
                    <td></td>
                </tr>';

                $koderacik = $resep->kodeRacik;
                $no++;
            }
        }
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.IkiDokter.Laporan {
    export interface Fields {
        idSmf:          "filtersmf";
        idDokter:       "filterdokter";
        tanggalMulai:   "mulai";
        tanggalSelesai: "selesai";
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
                heading3: {text: tlm.stringRegistry._<?= $h("???") ?>}
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
                        label: tlm.stringRegistry._<?= $h("SMF") ?>,
                        select: {class: ".idSmfFld", name: "idSmf"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Dokter") ?>,
                        select: {class: ".idDokterFld", name: "idDokter"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Mulai") ?>,
                        input: {class: ".tanggalMulaiFld", name: "tanggalMulai"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Tanggal Selesai") ?>,
                        input: {class: ".tanggalSelesaiFld", name: "tanggalSelesai"}
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const idSmfFld = divElm.querySelector(".idSmfFld");
        /** @type {HTMLSelectElement} */ const idDokterFld = divElm.querySelector(".idDokterFld");

        tlm.app.registerSelect("_<?= $smfSelect ?>", idSmfFld);
        tlm.app.registerSelect("_<?= $dokterSelect ?>", idDokterFld);
        this._selects.push(idSmfFld, idDokterFld);

        const saringWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.IkiDokter.Laporan.Fields} data */
            loadData(data) {
                idSmfFld.value = data.idSmf ?? "";
                idDokterFld.value = data.idDokter ?? "";
                tanggalMulaiWgt.value = data.tanggalMulai ?? "";
                tanggalSelesaiWgt.value = data.tanggalSelesai ?? "";
            },
            resetBtnId: false,
            actionUrl: "<?= $actionUrl ?>"
        });

        idSmfFld.addEventListener("change", (event) => {
            $.post({
                url: "<?= $dokterBySmfUrl ?>",
                data: {q: event.target.value},
                success(data) {idDokterFld.innerHTML = data}
            });
        });

        let minTanggalMulai;
        let maksTanggalSelesai;

        const tanggalMulaiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalMulaiFld"),
            // numberOfMonths: 1,
            onBeforeOpenDatetimePicker() {
                this._maxDate = maksTanggalSelesai;
            },
            onBeforeCloseDatetimePicker() {
                minTanggalMulai = this._value;
            },
            ...tlm.dateWidgetSetting
        });

        const tanggalSelesaiWgt = new spl.DateTimeWidget({
            element: divElm.querySelector(".tanggalSelesaiFld"),
            // numberOfMonths: 1,
            onBeforeOpenDatetimePicker() {
                this._minDate = minTanggalMulai;
            },
            onBeforeCloseDatetimePicker() {
                maksTanggalSelesai = this._value;
            },
            ...tlm.dateWidgetSetting
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, tanggalMulaiWgt, tanggalSelesaiWgt);
        tlm.app.registerWidget(this.constructor.widgetName, saringWgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<div id="<?= $registerId ?>">
    <h1>IKI Dokter</h1>

    <form id="<?= $registerId ?>_frm"></form>

    <table class="table table-striped">
        <?php if (isset($post["search"])): ?>
            <tr>
                <td>Total Formularium RS</td>
                <td>: <?= $frs ?> (<?= $toUserFloat($frs / $no * 100) ?> %)</td>
                <td>Total Formularium Nasional</td>
                <td>: <?= $fornas ?> (<?= $toUserFloat($fornas / $no * 100) ?> %)</td>
                <td>Total Lainnya</td>
                <td>: <?= $lainnya ?> (<?= $toUserFloat($lainnya / $no * 100) ?> %)</td>
            </tr>
        <?php endif ?>
    </table>

    <br/>
    <br/>

    <?php if (isset($post["search"])): ?>
    <table class='table table-striped'>
        <thead>
            <tr>
                <?= /** @noinspection PhpUndefinedVariableInspection */ $thSmf ?>
                <?= /** @noinspection PhpUndefinedVariableInspection */ $thDokter ?>
                <th>Resep</th>
                <th>Tanggal</th>
                <th>Nama Obat</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Formularium Nasional</th>
                <th>Formularium RS</th>
                <th>Lainnya</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            <?= $html ?>
        </tbody>
    </table>
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
