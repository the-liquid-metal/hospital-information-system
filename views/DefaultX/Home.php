<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\DefaultX;

use tlm\his\FatmaPharmacy\controllers\{
    BillingUiController,
    DashboardEksekutifUiController,
    DistribusiUiController,
    EresepBillingUiController,
    EresepDepoDrUiController,
    EresepUiController,
    HargaUiController,
    IkiDokterUiController,
    JadwalOperasiUiController,
    KonsinyasiUiController,
    PembelianUiController,
    PemesananUiController,
    PenerimaanUiController,
    PengadaanUiController,
    PerencanaanUiController,
    ProduksiUiController,
    ResepGabunganUiController,
    ReturFarmasiUiController,
    StokopnameUiController,
    TransaksiUiController,
};
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/default/home.php the original file
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/default/home.php the original file
 *
 * TODO: php: uncategorized: better-if-rewrite
 */
final class Home
{
    private string $output;

    public function __construct(
        string $registerId,
        string $dashboardEksekutifTableUrl = "",
        string $jadwalOperasiIndexUrl = "",
        string $produksiTableUrl = "",
        string $laporanTransaksiUrl = "",
        string $transaksiPenerimaanUrl = "",
        string $transaksiPengirimanTpUrl = "",
        string $transaksiPengirimanUrl = "",
        string $transaksiPermintaan2Url = "",
        string $stokopnameTableUrl = "",
        string $tableLaporanIkiDokterUrl = "",
        string $eresepFormUrl = "",
        string $eresepDepoDrFormUrl = "",
        string $kumpulanResepGabunganUrl = "",
        string $resepGabunganTableUrl = "",
        string $billingTableUrl = "",
        string $eresepBillingTableUrl = "",
        string $eresepDepoDrTable2Url = "",
        string $hargaPembelianUlpTableWidgetId = "",
        string $distribusiTableWidgetId = "",
        string $perencanaanTableWidgetId = "",
        string $pengadaanTableWidgetId = "",
        string $pembelianTableWidgetId = "",
        string $pemesananTableWidgetId = "",
        string $penerimaanTableWidgetId = "",
        string $returFarmasiTableWidgetId = "",
        string $konsinyasiTableWidgetId = "",
    ) {
        $dashboardEksekutifTableUrl     = $dashboardEksekutifTableUrl     ?: Yii::$app->actionToUrl([DashboardEksekutifUiController::class, "actionTable"]);
        $jadwalOperasiIndexUrl          = $jadwalOperasiIndexUrl          ?: Yii::$app->actionToUrl([JadwalOperasiUiController::class,      "actionIndex"]);
        $produksiTableUrl               = $produksiTableUrl               ?: Yii::$app->actionToUrl([ProduksiUiController::class,           "actionForm"]);
        $laporanTransaksiUrl            = $laporanTransaksiUrl            ?: Yii::$app->actionToUrl([TransaksiUiController::class,          "actionLaporan"]);
        $transaksiPenerimaanUrl         = $transaksiPenerimaanUrl         ?: Yii::$app->actionToUrl([TransaksiUiController::class,          "actionTablePenerimaan"]);
        $transaksiPengirimanTpUrl       = $transaksiPengirimanTpUrl       ?: Yii::$app->actionToUrl([TransaksiUiController::class,          "actionTablePengirimanTp"]);
        $transaksiPengirimanUrl         = $transaksiPengirimanUrl         ?: Yii::$app->actionToUrl([TransaksiUiController::class,          "actionTablePengiriman"]);
        $transaksiPermintaan2Url        = $transaksiPermintaan2Url        ?: Yii::$app->actionToUrl([TransaksiUiController::class,          "actionFormPermintaan2"]);
        $stokopnameTableUrl             = $stokopnameTableUrl             ?: Yii::$app->actionToUrl([StokopnameUiController::class,         "actionTableStokopname"]);
        $tableLaporanIkiDokterUrl       = $tableLaporanIkiDokterUrl       ?: Yii::$app->actionToUrl([IkiDokterUiController::class,          "actionTableLaporan"]);
        $eresepFormUrl                  = $eresepFormUrl                  ?: Yii::$app->actionToUrl([EresepUiController::class,             "actionForm"]);
        $eresepDepoDrFormUrl            = $eresepDepoDrFormUrl            ?: Yii::$app->actionToUrl([EresepDepoDrUiController::class,       "actionForm"]);
        $kumpulanResepGabunganUrl       = $kumpulanResepGabunganUrl       ?: Yii::$app->actionToUrl([ResepGabunganUiController::class,      "actionTableKumpulanResep"]);
        $resepGabunganTableUrl          = $resepGabunganTableUrl          ?: Yii::$app->actionToUrl([ResepGabunganUiController::class,      "actionTableResep"]);
        $billingTableUrl                = $billingTableUrl                ?: Yii::$app->actionToUrl([BillingUiController::class,            "actionTable"]);
        $eresepBillingTableUrl          = $eresepBillingTableUrl          ?: Yii::$app->actionToUrl([EresepBillingUiController::class,      "actionTableResep"]);
        $eresepDepoDrTable2Url          = $eresepDepoDrTable2Url          ?: Yii::$app->actionToUrl([EresepDepoDrUiController::class,       "actionTableResep"]);
        $hargaPembelianUlpTableWidgetId = $hargaPembelianUlpTableWidgetId ?: Yii::$app->actionToUrl([HargaUiController::class,              "actionHargaPembelianUlpTable"]);
        $distribusiTableWidgetId        = $distribusiTableWidgetId        ?: Yii::$app->actionToUrl([DistribusiUiController::class,         "actionTable"]);
        $perencanaanTableWidgetId       = $perencanaanTableWidgetId       ?: Yii::$app->actionToUrl([PerencanaanUiController::class,        "actionTable"]);
        $pengadaanTableWidgetId         = $pengadaanTableWidgetId         ?: Yii::$app->actionToUrl([PengadaanUiController::class,          "actionTable"]);
        $pembelianTableWidgetId         = $pembelianTableWidgetId         ?: Yii::$app->actionToUrl([PembelianUiController::class,          "actionTable"]);
        $pemesananTableWidgetId         = $pemesananTableWidgetId         ?: Yii::$app->actionToUrl([PemesananUiController::class,          "actionTable"]);
        $penerimaanTableWidgetId        = $penerimaanTableWidgetId        ?: Yii::$app->actionToUrl([PenerimaanUiController::class,         "actionTable"]);
        $returFarmasiTableWidgetId      = $returFarmasiTableWidgetId      ?: Yii::$app->actionToUrl([ReturFarmasiUiController::class,       "actionTable"]);
        $konsinyasiTableWidgetId        = $konsinyasiTableWidgetId        ?: Yii::$app->actionToUrl([KonsinyasiUiController::class,         "actionTable"]);
        $h = fn(string $str): string => Yii::$app->hash($str);
        $baseUrl = Yii::$app->basePath;
        $module = "";
        ob_clean();
        ob_start();
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
        row_3: {
            widthColumn: {
                li_1: {
                    a: {href: "<?= $baseUrl . '/' . $module ?>/laporan-rs/default/index", text: tlm.stringRegistry._<?= $h("Laporan") ?>}
                },
                li_2: {
                    a: {href: "<?= $baseUrl . '/' . $module ?>/master-data/index", text: tlm.stringRegistry._<?= $h("Master Data") ?>}
                },
                li_3: {
                    a: {href: "<?= $baseUrl . '/' . $module ?>/default/index?m=pengadaan", text: tlm.stringRegistry._<?= $h("Farmasi [BACK-END]") ?>}
                },
                li_4: {
                    a: {href: "<?= $eresepDepoDrTable2Url ?>", text: tlm.stringRegistry._<?= $h("List Resep Dr") ?>}
                },
                li_5: {
                    a: {href: "<?= $eresepBillingTableUrl ?>", text: tlm.stringRegistry._<?= $h("List Kasir") ?>}
                },
                li_6: {
                    a: {href: "<?= $billingTableUrl ?>", text: tlm.stringRegistry._<?= $h("List Billing") ?>}
                },
                li_7: {
                    a: {href: "<?= $resepGabunganTableUrl ?>", text: tlm.stringRegistry._<?= $h("List Gabungan") ?>}
                },
                li_8: {
                    a: {href: "<?= $kumpulanResepGabunganUrl ?>", text: tlm.stringRegistry._<?= $h("Riwayat Obat") ?>}
                },
                li_9: {
                    a: {href: "<?= $eresepDepoDrFormUrl ?>", text: tlm.stringRegistry._<?= $h("Resep Depo Dr") ?>}
                },
                li_10: {
                    a: {href: "<?= $eresepFormUrl ?>", text: tlm.stringRegistry._<?= $h("E-Resep") ?>}
                },
                li_11: {
                    a: {href: "<?= $tableLaporanIkiDokterUrl ?>", text: tlm.stringRegistry._<?= $h("IKI Dokter") ?>}
                },
                li_12: {
                    a: {href: "<?= $stokopnameTableUrl ?>", text: tlm.stringRegistry._<?= $h("Stok Opname") ?>}
                },
                li_13: {
                    a: {href: "<?= $transaksiPermintaan2Url ?>", text: tlm.stringRegistry._<?= $h("Permintaan") ?>}
                },
                li_14: {
                    a: {href: "<?= $transaksiPengirimanUrl ?>", text: tlm.stringRegistry._<?= $h("Pengiriman") ?>}
                },
                li_15: {
                    a: {href: "<?= $transaksiPengirimanTpUrl ?>", text: tlm.stringRegistry._<?= $h("Pengeluaran") ?>}
                },
                li_16: {
                    a: {href: "<?= $transaksiPenerimaanUrl ?>", text: tlm.stringRegistry._<?= $h("Penerimaan") ?>}
                },
                li_17: {
                    a: {href: "<?= $laporanTransaksiUrl ?>", text: tlm.stringRegistry._<?= $h("History Distribusi") ?>}
                },
                li_18: {
                    a: {href: "<?= $produksiTableUrl ?>", text: tlm.stringRegistry._<?= $h("Produksi") ?>}
                },
                li_19: {
                    a: {href: "<?= $jadwalOperasiIndexUrl ?>", text: tlm.stringRegistry._<?= $h("Jadwal Operasi") ?>}
                },
                li_20: {
                    a: {href: "<?= $dashboardEksekutifTableUrl ?>", text: tlm.stringRegistry._<?= $h("Dashboard Eksekutif") ?>}
                }
            }
        }
    };

    constructor(divElm) {
        super();

        divElm.innerHTML = spl.LayoutDrawer.draw(this._structure).content;

        divElm.querySelector("#search5").addEventListener("keyup", (event) => {
            const search2 = event.target.value;
            $.post({
                data: {search: search2},
                url: "http://202.137.25.11:9090/page/ajaxhome",
                /** @param {string} data */
                success(data) {
                    const myReplaceElm = divElm.querySelector(".myReplace");
                    myReplaceElm.innerHTML = data;
                    myReplaceElm.style.display = "block";
                }
            });
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


<ul class="thumbnails">
    <!-- rollback dari /his-ref-fatmawati-pharmacy/application/views/farmasi/master-data/page.php -->
    <li><a href="farmasi/katalog-test/index">Katalog</a></li>
    <li><a href="farmasi/brand/index">Brand</a></li>
    <li><a href="farmasi/generik/index">Generik</a></li>
    <li><a href="farmasi/sub-kelas-terapi/index">Sub Kelas Terapi</a></li>
    <li><a href="farmasi/kelas-terapi/index">Kelas Terapi</a></li>
    <li><a href="farmasi/jenis-barang/index">Jenis Barang</a></li>
    <li><a href="farmasi/kelompok-barang/index">Kelompok Barang</a></li>
    <li><a href="farmasi/kemasan/index">Satuan Kemasan</a></li>
    <li><a href="farmasi/pabrik/index">Pabrik</a></li>
    <li><a href="farmasi/pbf/index">Pemasok</a></li>

    <li><a href="<?= $hargaPembelianUlpTableWidgetId ?>">Master Harga</a></li>
    <li><a href="<?= $distribusiTableWidgetId ?>">Distribusi Gas Medis</a></li>
    <li><a href="<?= $perencanaanTableWidgetId ?>">Perencanaan</a></li>
    <li><a href="<?= $pengadaanTableWidgetId ?>">HPS Pengadaan</a></li>
    <li><a href="<?= $pembelianTableWidgetId ?>">SPK Pembelian</a></li>
    <li><a href="<?= $pemesananTableWidgetId ?>">Delivery Order/ SPB</a></li>
    <li><a href="<?= $penerimaanTableWidgetId ?>">Penerimaan</a></li>
    <li><a href="<?= $returFarmasiTableWidgetId ?>">Retur Penerimaan</a></li>
    <li><a href="<?= $konsinyasiTableWidgetId ?>">Konsinyasi</a></li>
</ul>

<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
