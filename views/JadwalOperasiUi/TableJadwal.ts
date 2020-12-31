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
 * @see http://localhost/ori-source/fatma-pharmacy/views/JadwalOperasi/list.php the original file
 */
final class TableJadwal
{
    private string $output;

    public function __construct(
        string $registerId,
        array  $detailAccess,
        array  $deleteAccess,
        string $dataUrl,
        string $instalasiUrl,
        string $operatorUrl,
        string $deleteUrl,
        string $tableWidgetId,
        string $viewDetailWidgetId,
        string $instalasiSelect,
        string $poliSelect,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.JadwalOperasi.List {
    export interface FormFields {
        idInstalasi: "instalasi",
        idPoli:      "poli",
        idDokter:    "id_dokter",
        persetujuan: "persetujuan",
    }

    export interface DokterFields {
        id:   "id";
        nama: "nama";
    }

    export interface TableFields {
        id:             "id";
        rencanaOperasi: "rencana_operasi";
        jam:            "jam";
        ruangOperasi:   "ruang_operasi";
        kodeRekamMedis: "no_rm";
        nama:           "nama";
        operator:       "operator";
        namaPoli:       "nama_poli";
    }
}
</script>

<script>
tlm.app.registerModule(class extends spa.BaseModule {
    static get version() {return "2.0.0"}

    static get widgetName() {return "_<?= $registerId ?>"}

    /**
     * @param {string} role
     * @returns {{detail: boolean, delete: boolean}}
     */
    static getAccess(role) {
        const pool = {
            detail: JSON.parse(`<?=json_encode($detailAccess) ?>`),
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
                header: {text: tlm.stringRegistry._<?= $h("Jadwal Operasi") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        row_3: {
            widthColumn: {
                button_1: {class: ".showTableBtn", text: tlm.stringRegistry._<?= $h("Tampilan Tanggal") ?>},
            }
        },
        form: {
            class: ".saringFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Saring") ?>,
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Instalasi") ?>,
                        select: {class: ".idInstalasiFld", name: "idInstalasi"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("Poli") ?>,
                        select: {class: ".idPoliFld", name: "idPoli"}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("Operator") ?>,
                        select: {class: ".idDokterFld", name: "idDokter"}
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Persetujuan") ?>, // pengganti dari "$history"
                        select: {
                            class: ".persetujuanFld",
                            name: "persetujuan",
                            option_1: {value: "disetujui", label: tlm.stringRegistry._<?= $h("Disetujui") ?>},
                            option_2: {value: "belum-disetujui", label: tlm.stringRegistry._<?= $h("Belum Disetujui") ?>},
                        }
                    }
                }
            },
            row_2: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Terapkan") ?>}
                }
            }
        },
        row_4: {
            widthTable: {
                class: ".itemTbl",
                thead: {
                    tr: {
                        td_1: {text: tlm.stringRegistry._<?= $h("Tanggal") ?>},
                        td_2: {text: tlm.stringRegistry._<?= $h("Jam") ?>},
                        td_3: {text: tlm.stringRegistry._<?= $h("Ruang Operasi") ?>},
                        td_4: {text: tlm.stringRegistry._<?= $h("Kode Rekam Medis") ?>},
                        td_5: {text: tlm.stringRegistry._<?= $h("Nama Pasien") ?>},
                        td_6: {text: tlm.stringRegistry._<?= $h("Operator") ?>},
                        td_7: {text: tlm.stringRegistry._<?= $h("Poli") ?>},
                        td_8: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                    }
                }
            }
        }
    };

    constructor(divElm) {
        super();
        const draw = spl.TableDrawer.drawButton;
        const str = tlm.stringRegistry;
        const access = this.constructor.getAccess(tlm.userRole);

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLSelectElement} */ const idInstalasiFld = divElm.querySelector(".idInstalasiFld");
        /** @type {HTMLSelectElement} */ const idPoliFld = divElm.querySelector(".idPoliFld");
        /** @type {HTMLSelectElement} */ const persetujuanFld = divElm.querySelector(".persetujuanFld");

        tlm.app.registerSelect("_<?= $instalasiSelect ?>", idInstalasiFld);
        tlm.app.registerSelect("_<?= $poliSelect ?>", idPoliFld);
        this._selects.push(idInstalasiFld, idPoliFld);

        divElm.querySelector(".showTableBtn").addEventListener("click", () => {
            tlm.app.getWidget("#<?= $tableWidgetId ?>");
        });

        const saringWgt = new spl.StaticFormWidget({
            element: divElm.querySelector(".saringFrm"),
            /** @param {his.FatmaPharmacy.views.JadwalOperasi.List.FormFields} data */
            loadData(data) {
                idInstalasiFld.value = data.idInstalasi ?? "";
                idPoliFld.value = data.idPoli ?? "";
                idDokterWgt.value = data.idDokter ?? "";
                persetujuanFld.value = data.persetujuan ?? "";
            },
            submit() {
                itemWgt.refresh({
                    query: {
                        idInstalasi: idInstalasiFld.value,
                        idPoli: idPoliFld.value,
                        idDokter: idDokterWgt.value,
                        persetujuan: persetujuanFld.value,
                    }
                });
            }
        });

        idInstalasiFld.addEventListener("change", () => {
            $.post({
                url: "<?= $instalasiUrl ?>",
                data: {id: idInstalasiFld.value},
                success(data) {
                    let options = `<option value="9999"></option>`;
                    data.forEach(val => options += `<option value="${val.id_poli}">${val.nama_poli}</option>`);
                    idPoliFld.innerHTML = options;
                }
            });
        });

        /** @see {his.FatmaPharmacy.views.JadwalOperasi.List.DokterFields} */
        const idDokterWgt = new spl.SelectWidget({
            element: divElm.querySelector(".idDokterFld"),
            maxItems: 1,
            valueField: "id",
            labelField: "nama",
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

        const itemWgt = new spl.TableWidget({
            element: divElm.querySelector(".itemTbl"),
            url: "<?= $dataUrl ?>",
            columns: {
                1: {field: "rencanaOperasi"},
                2: {field: "jam"},
                3: {field: "ruangOperasi"},
                4: {field: "kodeRekamMedis"},
                5: {field: "nama"},
                6: {field: "operator"},
                7: {field: "namaPoli"},
                8: {formatter(unused, {id}) {
                    const detailBtn = draw({class: ".detailBtn", icon: "list",   value: id, text: str._<?= $h("Detail") ?>});
                    const deleteBtn = draw({class: ".deleteBtn", icon: "remove", value: id, text: str._<?= $h("Delete") ?>});
                    return (access.detail ? detailBtn : "") + (access.delete ? deleteBtn : "");
                }}
            }
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const detailBtn = event.target;
            if (!detailBtn.matches(".detailBtn")) return;

            const widget = tlm.app.getWidget("_<?= $viewDetailWidgetId ?>");
            widget.show();
            widget.loadData({id: detailBtn.value}, true);
        });

        itemWgt.addDelegateListener("tbody", "click", (event) => {
            const deleteBtn = event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;
            if (!confirm("Apakah Anda yakin ingin menghapus?")) return;

            $.post({
                url: "<?= $deleteUrl ?>",
                data: {id: deleteBtn.value}
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(saringWgt, idDokterWgt, itemWgt);
        tlm.app.registerWidget(this.constructor.widgetName, saringWgt);
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
