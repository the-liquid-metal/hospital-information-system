<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\DashboardEksekutifUi;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/dashboardeksekutif/konsumsiobat.php the original file
 */
final class KonsumsiObat
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dataUrl, // TODO: php: uncategorized: replace $daftarPenjualan1 and $daftarPenjualan2 with this
    ) {
        ob_clean();
        ob_start();

        $label1 = [];
        $data1 = [];
        foreach ($daftarPenjualan1 as $penjualan) {
            $label1[] = $penjualan->namaBarang;
            $data1[] = $penjualan->TOTAL;
        }

        $label2 = [];
        $data2 = [];
        foreach ($daftarPenjualan2 as $penjualan) {
            $label2[] = $penjualan->namaBarang;
            $data2[] = $penjualan->TOTAL;
        }
        ?>


<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    static style = {
        [this.widgetName]: {
            ".clpBtn": {
                _style: {outline: "none", border: 0, background: "transparent"}
            }
        }
    };

    _structure = {};

    constructor(divElm) {
        super();

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const collapseChart1Elm = divElm.querySelector("#collapse-chart1");
        const collapseChart2Elm = divElm.querySelector("#collapse-chart2");
        const collapseSearchFilter1Elm = divElm.querySelector("#collapse-search-filter");
        const collapseSearchFilter2Elm = divElm.querySelector("#collapse-search-filter2");

        collapseChart1Elm.addEventListener("click", () => {
            const isZero = collapseChart1Elm.value == "0";
            collapseChart1Elm.value = isZero ? "1" : "0";
            collapseSearchFilter1Elm.style.display = isZero ? "none" : "block";
        });

        collapseChart2Elm.addEventListener("click", () => {
            const isZero = collapseChart2Elm.value == "0";
            collapseChart2Elm.value = isZero ? "1" : "0";
            collapseSearchFilter2Elm.style.display = isZero ? "none" : "block";
        });

        const dataObat1 = {
            labels: JSON.parse(`<?= json_encode($label1) ?>`),
            datasets: [{
                label: "Konsumsi Obat",
                fillColor: "rgba(0,0,255,0.6)",
                highlightFill: "rgba(0, 153, 51,0.75)",
                data: JSON.parse(`<?= json_encode($data1) ?>`)
            }]
        };

        const dataObat2 = {
            labels: JSON.parse(`<?= json_encode($label2) ?>`),
            datasets: [{
                label: "Konsumsi Obat Dengan Harga",
                fillColor: "rgba(0,0,255,0.6)",
                highlightFill: "rgba(0, 153, 51,0.75)",
                data: JSON.parse(`<?= json_encode($data2) ?>`)
            }]
        };

        const options = {
            scaleBeginAtZero: true,
            scaleShowGridLines: true,
            scaleGridLineColor: "rgba(0,0,0,.05)",
            scaleGridLineWidth: 1,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines: false,
            barShowStroke: true,
            barStrokeWidth: 2,
            barValueSpacing: 5,
            barDatasetSpacing: 1
        };

        const options2 = {
            scaleBeginAtZero: true,
            scaleShowGridLines: true,
            scaleGridLineColor: "rgba(0,0,0,.05)",
            scaleGridLineWidth: 1,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines: false,
            barShowStroke: true,
            barStrokeWidth: 2,
            barValueSpacing: 5,
            barDatasetSpacing: 1
        };

        // TODO: js: missing function: HorizontalBar()
        const ctx1 = divElm.querySelector("#chartobat").getContext("2d");
        new Chart(ctx1).HorizontalBar(dataObat1, options);
        const ctx2 = divElm.querySelector("#chartobat2").getContext("2d");
        new Chart(ctx2).HorizontalBar(dataObat2, options2);

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
<div class="splPlainPage" id="<?= $registerId ?>">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    10 Besar Konsumsi Obat Hari Ini (Tanpa Harga)
                    <button class="clpBtn pull-right" id="collapse-chart1" value="0">
                        <span class="indicator glyphicon glyphicon-chevron-down pull-right" id="collapse-chart1"></span>
                    </button>
                </h3>
            </div>
            <div class="panel-body" id="collapse-search-filter">
                <canvas id="chartobat" width="1000" height="400"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    10 Besar Konsumsi Obat Hari Ini (Dengan Harga)
                    <button class="clpBtn pull-right" id="collapse-chart2" value="0">
                        <span class="indicator glyphicon glyphicon-chevron-down pull-right" id="collapse-chart2"></span>
                    </button>
                </h3>
            </div>
            <div class="panel-body" id="collapse-search-filter2">
                <canvas id="chartobat2" width="1000" height="400"></canvas>
            </div>
        </div>
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
