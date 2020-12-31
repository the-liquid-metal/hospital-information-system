<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\MonitoringDb;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/monitoringdb/index.php the original file
 */
final class Table
{
    private string $output;

    public function __construct(string $registerId, string $module)
    {
        ob_clean();
        ob_start();
        ?>


<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    _structure = {};

    constructor(divElm) {
        super();
        let timer = null;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        const containerElm = divElm.querySelector("#container");

        function loadDataAjax() {
            $.post({
                url: "<?= $module ?>/monitoring-db/get-ajax",
                success: (data) => {
                    if (data) {
                        containerElm.innerHTML = data;
                    }
                }
            });
        }

        divElm.querySelector("#refresh").addEventListener("click", () => loadDataAjax());

        divElm.querySelector("#autorefresh").addEventListener("change", () => {
            if (this.checked) {
                timer = setInterval(loadDataAjax, 4000);
            } else {
                clearInterval(timer);
                timer = null;
            }
        });

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
    <div class="form-inline">
        <input type="button" id="refresh" class="btn btn-primary" value="Refresh"/>
        <label><input type="checkbox" id="autorefresh"/>&nbsp;Auto Refresh</label>
    </div>

    <hr/>

    <div id="container"></div>
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
