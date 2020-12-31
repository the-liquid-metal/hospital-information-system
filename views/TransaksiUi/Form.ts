<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\TransaksiUi;

use tlm\libs\LowEnd\components\{FormHelper as FH, MasterHelper as MH};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/transaksi/add.php
 */
final class Form
{
    private string $output;

    public function __construct(
        string $registerId,
        string $actionUrl,
        string $transaksiUrl,
        string $batalWidgetId,
    ) {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $view = [ // TODO: php: to be deleted
            "kode" => "Kode Katalog",
            "namaKatalog" => "Nama Item",
            "kemasan" => "Kemasan Item",
            "satuan" => "Satuan Kemasan",
        ];
        $data = [];
        $gridName = "";
        ?>


<script type="text/tsx">
namespace his.FatmaPharmacy.views.Transaksi.Add {
    export interface Fields {
        kode:     "kode";
        username: "username";
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
                heading3: {class: ".formTitleTxt", text: tlm.stringRegistry._<?= $h("Tambah Transaksi") ?>}
            }
        },
        row_2: {
            widthColumn: {
                paragraph: {text: "&nbsp;"}
            }
        },
        form: {
            class: ".transaksiFrm",
            row_1: {
                box: {
                    title: tlm.stringRegistry._<?= $h("Parameter") ?>,
                    hidden_1: {name: "submit", value: "save"},
                    hidden_2: {class: "pageactive", value: "permintaan"},
                    formGroup_1: {
                        label: tlm.stringRegistry._<?= $h("Kode Permintaan") ?>,
                        input: {class: ".kodeFld", name: "kode"}
                    },
                    formGroup_2: {
                        label: tlm.stringRegistry._<?= $h("User/Dokter Pengusul") ?>,
                        input: {class: ".usernameFld", name: "username", readonly: true}
                    },
                    formGroup_3: {
                        label: tlm.stringRegistry._<?= $h("SMF Pengusul") ?>,
                        select: {class: ".namaFld", name: "nama", options: "<?= json_encode(MH::dropdown_smf()) ?>"} // selected: <?= FH::val($data, "kode_smf") ?>
                    },
                    formGroup_4: {
                        label: tlm.stringRegistry._<?= $h("Keterangan") ?>,
                        textarea: {class: ".keteranganFld", name: "keterangan", value: "<?= FH::val($data, 'keterangan') ?>"}
                    }
                }
            },
            row_2: {
                table: {
                    id: "#list-<?= $gridName ?>",
                    thead: {
                        tr: {
                            td_1: {text: tlm.stringRegistry._<?= $h("No.") ?>},
                            td_2: {text: tlm.stringRegistry._<?= $h("Status") ?>},
                            td_3: {text: tlm.stringRegistry._<?= $h("Kode") ?>},
                            td_4: {text: tlm.stringRegistry._<?= $h("Nama Item") ?>},
                            td_5: {text: tlm.stringRegistry._<?= $h("Keterangan") ?>},
                            td_6: {text: tlm.stringRegistry._<?= $h("Action") ?>},
                        }
                    }
                }
            },
            row_3: {
                column: {
                    button_1: {class: ".batalBtn",    text: tlm.stringRegistry._<?= $h("Batal") ?>},
                    button_2: {class: ".itemBaruBtn", text: tlm.stringRegistry._<?= $h("Buat Item Baru") ?>},
                    button_3: {class: ".cariObatBtn", text: tlm.stringRegistry._<?= $h("Cari Obat") ?>},
                }
            },
            row_4: {
                column: {
                    class: "text-center",
                    SRButton: {sLabel: tlm.stringRegistry._<?= $h("Save") ?>}
                }
            }
        },
    };

    constructor(divElm) {
        super();
        /** @type {function(elm: HTMLElement, parent: string): HTMLTableRowElement} */
        const closest = spl.util.closestParent;
        const drawTr = spl.TableDrawer.drawTr;
        const str = tlm.stringRegistry;

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        /** @type {HTMLInputElement} */  const kodeFld = divElm.querySelector(".kodeFld");
        /** @type {HTMLInputElement} */  const usernameFld = divElm.querySelector(".usernameFld");

        const transaksiWgt = new spl.AjaxFormWidget({
            element: divElm.querySelector(".transaksiFrm"),
            /** @param {his.FatmaPharmacy.views.Transaksi.Add.Fields} data */
            loadData(data) {
                kodeFld.value = data.kode ?? "";
                usernameFld.value = data.username ?? "";
            },
            actionUrl: "<?= $actionUrl ?>"
        });

        const listTransaksi = divElm.getElementById("list-transaksi");
        divElm.querySelector(".itemBaruBtn").addEventListener("click", () => {
            listTransaksi.innerHTML += drawTr("tbody", {
                class: "table-append",
                td_1: {class: "mynumber", text: x},
                td_2: {
                    select: {
                        id: "status" + x,
                        name: "statusItem[]",
                        option_1: {value: "normal", text: str._<?= $h("Normal") ?>},
                        option_2: {value: "urgent", text: str._<?= $h("Urgent") ?>},
                    }
                },
                td_3: {
                    input: {id: "kode" + x, name: "kodeItem[]", value: "-", readonly: true}
                },
                td_4: {
                    input: {id: "namaItem" + x, name: "namaItem[]", placeholder: str._<?= $h("Nama Item yang diminta") ?>}
                },
                td_5: {
                    textarea: {id: "keterangan" + x, name: "keteranganItem[]", placeholder: str._<?= $h("Alasan Permintaan Obat") ?>}
                },
                td_6: {
                    button: {class: ".deleteBtn  lnk-delete  delete-btn", text: str._<?= $h("Delete") ?>}
                },
            });
            x++;
        });

        listTransaksi.addEventListener("click", event => {
            const deleteBtn = /** @type {HTMLButtonElement}*/ event.target;
            if (!deleteBtn.matches(".deleteBtn")) return;

            closest(deleteBtn, "tr").remove();
            divElm.querySelectorAll(".mynumber").forEach((item, i) => item.innerHTML = i + 1);
        });

        divElm.querySelector(".batalBtn").addEventListener("click", () => {
            tlm.app.getWidget("_<?= $batalWidgetId ?>").show();
        });

        divElm.querySelector(".cariObatBtn").addEventListener("click", () => {
            $.post({
                url: "<?= $transaksiUrl ?>",
                /** @param {string} data */
                success(data) {
                    const dom = $(data);
                    const box = bootbox.modal(dom, "Cari Data Obat", {backdrop: "static"});

                    dom.filter("script").each(() => {
                        $.globalEval(this.text || this.textContent || this.innerHTML || "");
                    });

                    box.querySelector(".modal-header").after(`<div class="modal-header2"></div>`);
                    box.querySelector(".dataTables_length").detach().prependTo(".modal-header2");
                    box.querySelector(".dataTables_filter").detach().prependTo(".modal-header2");
                    box.append(`<div class="modal-footer2"></div>`);
                    box.querySelector(".dataTables_info").detach().prependTo(".modal-footer2");
                    box.querySelector(".dataTables_paginate").detach().prependTo(".modal-footer2");
                }
            });
        });


        // JUNK -----------------------------------------------------

        const activePage = divElm.querySelector(".pageactive").value;
        divElm.querySelector(`a[href*=${activePage}]`).parentNode.classList.add("active");

        let x = 1; // index untuk membedakan name dan id untuk masing2 inputan

        divElm.querySelector(".addrow-btn").addEventListener("click", () => {
            const ele = divElm.querySelector("#tbody-tab");
            let cnt = 0;
            const smf2 = divElm.querySelectorAll("[name='smf2[]']");
            smf2.each((i, n) => {
                const check = $(n).id.substr(3);
                cnt = (check > cnt) ? check : cnt;
            });
            cnt++;

            closest(divElm.querySelector(".smf-group"), ".controls").insertAdjacentHTML("beforeend", `
                <div class="input-append smf-group">
                    <div id="smf${cnt}" name="smf2[]">${ele.innerHTML}</div>
                    <button class="btn btn-mini btn-danger lnk-delete delete-btn" type="button">
                        <i class="icon-remove icon-white"></i> Delete
                    </button>
                </div>`);
            new spl.SelectWidget({element: smf2});

            divElm.querySelector(".tab-remove-btn").addEventListener("click", (event) => {
                closest(event.target, ".smf-group").remove();
            });
        });

        divElm.querySelector(".tab-add-btn").addEventListener("click", () => {
            const ele = divElm.querySelector("#smf");
            let cnt = 0;
            const smf2Fld = divElm.querySelectorAll("[name='smf2[]']");
            smf2Fld.each((i, n) => {
                const check = $(n).id.substr(3);
                cnt = (check > cnt) ? check : cnt;
            });
            cnt++;

            closest(divElm.querySelector(".smf-group"), ".controls").insertAdjacentHTML("beforeend", `
                <div class="input-append smf-group">
                    <select id="smf${cnt}" name="smf2[]">${ele.innerHTML}</select>
                    <button class="btn tab-remove-btn" type="button"><i class="icon-minus"></i></button>
                </div>`);
            new spl.SelectWidget({element: smf2Fld});

            divElm.querySelector(".tab-remove-btn").addEventListener("click", (event) => {
                closest(event.target, ".smf-group").remove();
            });
        });

        this._element = divElm;
        divElm.moduleWidget = this;
        this._widgets.push(transaksiWgt);
        tlm.app.registerWidget(this.constructor.widgetName, transaksiWgt);
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
