<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\GroupUi;

use tlm\libs\LowEnd\components\{DateTimeException, FormHelper as FH, MasterHelper as MH};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/Group/edit.php the original file
 */
final class FormEdit
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(
        string   $registerId,
        string   $dataUrl,
        string   $actionUrl,
        string   $instalasiUrl,
        string   $tableWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        $todayValUser = Yii::$app->dateTime->todayVal("user");
        ob_clean();
        ob_start();
        $fields = [
            "name" => [
                "title" => "Nama Group",
                "rule" => "required"
            ],
            'description' => [
                "title" => "Keterangan",
                "type" => "textarea"
            ]
        ];
        $data = [];
        $moduleName = [];
        ?>


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
            class: ".editGroupFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>
                }
            }
        }
    };

    constructor(divElm) {
        super();

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const idInstalasiFld = divElm.querySelector(".idInstalasiFld");
        /** @type {HTMLSelectElement} */ const idPoliFld = divElm.querySelector(".idPoliFld");

        const editGroupWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".editGroupFrm"),
            dataUrl: "<?= $dataUrl ?>",
            actionUrl: "<?= $actionUrl ?>"
        });

        // JUNK -----------------------------------------------------

        idInstalasiFld.addEventListener("change", () => {
            $.post({
                url: "<?= $instalasiUrl ?>",
                data: {id: idInstalasiFld.value},
                success(data) {
                    let options = `<option value="999"></option>`;
                    data.forEach(val => options += `<option value="${val.id_poli}">${val.nama_poli}</option>`);
                    idPoliFld.innerHTML = options;
                }
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(editGroupWgt);
        tlm.app.registerWidget(this.constructor.widgetName, editGroupWgt);
    }
});
</script>

<!-- TODO: html: convert to js -->
<form id="<?= $registerId ?>" class="form-horizontal well" method="post">
    <?php foreach (array_keys($fields) as $key): ?>
        <?php $type = $fields[$key]['type'] ?? 'text' ?>
        <div class="control-group <?= empty(form_error($key)) ? "" : "error" ?>">
            <label class="control-label" for="<?= $key ?>"><?= $fields[$key]['title'] ?? $key ?></label>
            <div class="controls">
                <?php if ($type == 'textarea'): ?>
                    <textarea class="input-xlarge" id="<?= $key ?>" name="<?= $key ?>"><?= set_value($key, $data[$key]) ?></textarea>
                <?php elseif ($type == 'date'): ?>
                    <input class="input-medium datepicker" id="<?= $key ?>" name="<?= $key ?>" value="<?= set_value($key, $data[$key]) ?>" data-date-format="d-m-Y" data-date="<?= $todayValUser ?>"/>
                <?php else: ?>
                    <input class="input-xlarge" id="<?= $key ?>" name="<?= $key ?>" value="<?= set_value($key, $data[$key]) ?>"/>
                <?php endif ?>
                <?= form_error($key) ?>
            </div>
        </div>
    <?php endforeach ?>

    <?= FH::select('Instalasi', 'id_instalasi', MH::dropdown_instalasi(), FH::val($data, 'id_instalasi'), ".idInstalasiFld") ?>
    <?= FH::select('Poli', 'id_poli', MH::dropdown_poli(FH::val($data, 'id_instalasi')), FH::val($data, 'id_poli'), ".idPoliFld") ?>

    <div class="control-group">
        <label class="control-label">Module</label>
        <div class="controls">
            <?php foreach ($moduleName as $item): ?>
                <?php $checked = ($item->idGroup && $data->id == $item->idGroup) ? 'checked' : '' ?>
                <div class="checkbox">
                    <label class="checkbox inline">
                        <input type="checkbox" name="module[]" value="<?= $item->id ?>" <?= $checked ?> />
                        <strong><?= $item->name ?></strong>
                    </label>
                    <div class="checkbox">
                        <?php $permissions = explode(',', $item->permission) ?>
                        <?php foreach (explode(',', $item->action) as $action): ?>
                            <?php $achecked = (in_array($action, $permissions)) ? 'checked' : '' ?>
                            <label class="checkbox inline"><input type="checkbox" name="permission[<?= $item->id ?>][]" value="<?= $action ?>" <?= $achecked ?> /><?= $action ?></label>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="form-actions">
        <input type="hidden" name="id" value="<?= $data->id ?>"/>
        <button class="btn btn-primary" name="submit" value="save">Save changes</button>
        <a class="btn" href="<?= $tableWidgetId ?>">Cancel</a>
    </div>
</form>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
