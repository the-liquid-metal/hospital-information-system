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
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/index.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(
        string   $registerId,
        string   $dataUrl, // TODO: php: uncategorized: ...
        string   $operatorUrl,
        string   $viewDetailWidgetId,
        string   $tableWidgetId,
        string   $tableJadwalWidgetId,
        string   $tableBookingWidgetId,
        string   $formWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $ruangOperasi = 0;
        $today = true;
        $prev = "";
        $startDate = "";
        $endDate = "";
        $next = "";
        $daftarHari = [];
        $data = [];
        $startDay = 0;
        $startMonth = 0;
        $startYear = 0;
        $hourAll = [];
        $dateAll = [];
        $rowspan = [];
        $selected = [];
        $activeClass = [];
        $span = [];
        $html5attribute = [];

        $ruangOk = function (int $rk): string {
            switch ($rk) {
                case 1: return "Ruang OK 1 (Kebidanan)";
                case 2: return "Ruang OK 2 (Kebidanan)";
                case 3: return "Ruang OK 3 (Bedah Plastik & Bedah Vaskuler)";
                case 4: return "Ruang OK 4 (Laparascopy Bedah)";
                case 5: return "Ruang OK 5 (Orthopedi)";
                case 6: return "Ruang OK 6 (Orthopedi)";
                case 7: return "Ruang OK 7 (Orthopedi)";
                case 8: return "Ruang OK 8 (Orthopedi)";
                case 9: return "Ruang OK 9 (Bedah Syaraf)";
                case 10: return "Ruang OK 10 (Bedah Digestif)";
                case 11: return "Ruang OK 11 (Bedah Anak & Bedah Thoraks)";
                case 12: return "Ruang OK 12 (Bedah Umum & Gilut (Gigi & Mulut))";
                case 13: return "Ruang OK 13 (Laparascopy Endometriosis)";
                case 14: return "Ruang OK 14 (THT)";
                case 15: return "Ruang OK 15 (Bedah Urologi)";
                case 16: return "Ruang OK 16 (Bedah Onkologi)";
                case 17: return "Ruang OK 17 (Bedah Mata)";
                default: return "";
            }
        };
        ?>

<script type="text/tsx">
namespace his.FatmaPharmacy.views.JadwalOperasi.Table {
    export interface OperatorFields {
        id:   "id";
        nama: "nama";
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName]: {
            ".table_info tr": {
                "td:nth-child(2)": {
                    _style: {backgroundColor: "red", width: "13%"}
                },
                "td:nth-child(4)": {
                    _style: {backgroundColor: "green", width: "13%"}
                },
                "td:nth-child(6)": {
                    _style: {backgroundColor: "#49AFCD", width: "13%"}
                },
                "td:nth-child(8)": {
                    _style: {backgroundColor: "yellow", width: "13%"}
                },
                "td:last-child": {
                    _style: {border: "7px solid black", width: "13%"}
                }
            }
        },
        "@media (min-width: 1200px)": {
            [this.widgetName]: {
                ".span3": {
                    _style: {width: "280px !important"}
                }
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
            class: ".form1Frm",
            row_1: {
                box: {
                    title: str._<?= $h("Parameter") ?>
                }
            }
        }
    };

    constructor(divElm) {
        super();

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const form1Wgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".form1Frm"),
            loadData(data) {
                // ...
            },
            actionUrl: "<?= $module ?? '' ?>"
        });

        // JUNK -----------------------------------------------------

        const operatorWgt = new spl.SelectWidget({
            element: divElm.querySelector("#operator"),
            maxItems: 1,
            valueField: "nama",
            labelField: "nama",
            /**
             * @param trElm
             * @param {his.FatmaPharmacy.views.JadwalOperasi.Table.OperatorFields} data
             */
            assignPairs(trElm, data) {
                // TODO: js: uncategorized: finish this
                // "[name=id_dokter]": "id" ?? ""
            },
            load(typed, processor) {
                if (!typed.length) {
                    processor([]);
                    return;
                }

                $.post({
                    url: "<?= $operatorUrl ?>",
                    data: {q: typed},
                    error() {processor([])},
                    success(data) {processor(data)}
                });
            }
        });

        divElm.querySelector("td.active").addEventListener("click", (event) => {
            const widget = tlm.app.getWidget("_<?= $viewDetailWidgetId ?>");
            widget.show();
            widget.loadData({id: event.target.dataset.id}, true);
        });

        divElm.querySelector(".active").popover({
            placement: "top",
            triger: "focus",
            template: `
            <div class="popover">
                <div class="arrow"></div>
                <div class="popover-inner popover-jadwal-operasi">
                    <h3 class="popover-title"></h3>
                    <div class="popover-content">
                        <p></p>
                    </div>
                </div>
            </div>
        `
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(form1Wgt, operatorWgt);
        tlm.app.registerWidget(this.constructor.widgetName, form1Wgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<div id="<?= $registerId ?>">
    <h1>Jadwal Operasi</h1>

    <a href="<?= $tableJadwalWidgetId ?>">Tampilan Daftar</a>

    <ul class="nav nav-tabs">
        <li class="dropdown active">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?= $ruangOk($ruangOperasi) ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <?php for ($i = 1; $i <= 17; $i++): ?>
                <?php $activeClass2 = ($i == $ruangOperasi) ? 'active' : '' ?>
                <li class="<?= $activeClass2 ?>"><a href="<?= $tableWidgetId ."/".$i ?>"><?= $ruangOk($i) ?></a></li>
                <?php endfor ?>
            </ul>
        </li>
        <li><a href="<?= $tableBookingWidgetId."/".$ruangOperasi ?>">Booking Ruang Operasi</a></li>
        <li><a href="<?= $formWidgetId ?>">Tambah</a></li>
    </ul>

    <div class="row">
        <form action="<?= $tableWidgetId ?>" class="form-inline" method="post">
            <label>Operator: </label>
            <input class="input-large" data-provide="typeahead" name="operator" id="operator"/>
            <input type="hidden" name="kd_dokter"/>
            <button class="btn btn-primary">Apply Filter</button>
        </form>

        <table class="table_info">
            <tr>
                <td class="text-right">Cito</td>
                <td></td>
                <td class="text-right">Elektif</td>
                <td></td>
                <td class="text-right">Bedah Prima</td>
                <td></td>
                <td class="text-right">ODC</td>
                <td></td>
                <td class="text-right">Infeksi</td>
                <td></td>
            </tr>
        </table>

        <div class="span3">
            <div class="dataTables_paginate paging_bootstrap pagination">
                <ul>
                    <?php if ($today): ?>
                    <li class="active disabled">
                        <a href="#">Today</a>
                    </li>

                    <?php else: ?>
                    <li class="today">
                        <a href="<?= $tableWidgetId ."/".$ruangOperasi ?>">Today</a>
                    </li>
                    <?php endif ?>

                    <li class="prev">
                        <a href="<?= $tableWidgetId ."/".$ruangOperasi.'/'.$prev ?>">&nbsp;&lt;&nbsp;</a>
                    </li>
                    <li class="active">
                        <a href="#"><?= date("M j", strtotime($startDate)) ?> - <?= date("j, Y", strtotime($endDate)) ?></a>
                    </li>
                    <li class="next">
                        <a href="<?= $tableWidgetId ."/".$ruangOperasi."/".$next ?>">&nbsp;&gt;&nbsp;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered table-hover table-condensed dataTable table-jadwal">
        <thead>
            <tr>
                <th rowspan="0">Jam</th>
                <?php foreach ($daftarHari as $idx => $hari): ?>
                    <?php $selected2 = ($startDay + $idx == date("d") ? 'selected' : '') ?>
                    <th colspan="0" class="<?= $selected2 ?>">
                        <?= $hari ?>
                        <p><?= date('d/m/Y', mktime(0, 0, 0, $startMonth, $startDay + $idx, $startYear)) ?></p>
                    </th>
                <?php endforeach ?>
            </tr>
        </thead>

        <tbody>
        <?php for ($j = 0; $j <= 23; $j++): ?>
            <?php $jj = $hourAll[$j] ?>
            <tr>
                <th style="white-space:nowrap">
                    <?= date('H:i', strtotime($j . ':00')) . ' - ' . date('H:i', strtotime(($j + 1) . ':00')) ?>
                </th>
                <?php foreach ($daftarHari as $idx => $hari): ?>
                    <?php $dd = $dateAll[$j][$idx] ?>
                    <?php if ($rowspan[$idx] == 1 || isset($data[$dd][$jj])): ?>
                        <td class="<?= $selected[$j][$idx] . $activeClass[$j][$idx] ?>" <?= $span[$j][$idx] . $html5attribute[$j][$idx] ?>></td>
                    <?php endif ?>
                <?php endforeach ?>
            </tr>
        <?php endfor ?>
        </tbody>
    </table>
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
