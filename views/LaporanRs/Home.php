<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanRs;

use tlm\his\FatmaPharmacy\controllers\{
    JadwalOperasiUiController,
    LaporanFloorStockUiController,
    LaporanGenerikUiController,
    LaporanMutasiUiController,
    LaporanPelunasanUiController,
    LaporanPenerimaanUiController,
    LaporanPengeluaranUiController,
    LaporanPenjualanUiController,
    LaporanPersediaanUiController,
    LaporanStokUiController,
    LaporanTakTerlayaniUiController,
    LaporanUiController,
    MonitorStokController,
    MutasiUiController,
    PembelianUiController,
    PerencanaanUiController,
    ProduksiUiController,
    StokopnameUiController,
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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporanrs/Page/home.php the original file
 */
final class Home
{
    private string $output;

    public function __construct(string $pageId)
    {
        $h = fn(string $str): string => Yii::$app->hash($str);
        ob_clean();
        ob_start();
        $items = [
            ["Monitor Stok",                        Yii::$app->actionToUrl([MonitorStokController::class,           "actionTable"])],
            ["Rekapitulasi Perencanaan",            Yii::$app->actionToUrl([PerencanaanUiController::class,         "actionFormReport"])],
            ["Laporan Pembelian",                   Yii::$app->actionToUrl([PembelianUiController::class,           "actionReport"])],
            ["Rekapitulasi Penerimaan",             Yii::$app->actionToUrl([LaporanPenerimaanUiController::class,   "actionFormPenerimaan"])],
            ["Kartu Ketersediaan (Stok)",           Yii::$app->actionToUrl([LaporanStokUiController::class,         "actionFormKetersediaan"])],
            ["Laporan Penjualan Tanpa Harga",       Yii::$app->actionToUrl([LaporanUiController::class,             "actionDepoJualIndex"])],
            ["Laporan Penjualan",                   Yii::$app->actionToUrl([LaporanPenjualanUiController::class,    "actionFormPenjualan"])],
            ["Laporan Pelunasan",                   Yii::$app->actionToUrl([LaporanPelunasanUiController::class,    "actionFormPelunasan"])],
            ["Rekap Pengeluaran",                   Yii::$app->actionToUrl([LaporanPengeluaranUiController::class,  "actionFormPengeluaran"])],
            ["Rekap Pengeluaran per Tujuan",        Yii::$app->actionToUrl([LaporanPengeluaranUiController::class,  "actionFormPerTujuan"])],
            ["Rekap Pengeluaran Gas Medis",         Yii::$app->actionToUrl([LaporanPengeluaranUiController::class,  "actionFormGasMedis"])],
            ["Rekap Penerimaan Depo",               Yii::$app->actionToUrl([LaporanPenerimaanUiController::class,   "actionFormDepo"])],
            ["Laporan Generik",                     Yii::$app->actionToUrl([LaporanGenerikUiController::class,      "actionFormLaporan"])],
            ["Rekap Generik",                       Yii::$app->actionToUrl([LaporanGenerikUiController::class,      "actionFormRekap"])],
            ["Laporan IKI",                         Yii::$app->actionToUrl([LaporanUiController::class,             "actionIki"])],
            ["Laporan IKI IRNA",                    Yii::$app->actionToUrl([LaporanUiController::class,             "actionIkiIrna"])],
            ["Laporan Mutasi Bulan",                Yii::$app->actionToUrl([LaporanMutasiUiController::class,       "actionFormBulan"])],
            ["Laporan Mutasi Triwulan",             Yii::$app->actionToUrl([LaporanMutasiUiController::class,       "actionFormTriwulan"])],
            ["Buffer Farmasi",                      Yii::$app->actionToUrl([MutasiUiController::class,              "actionBufferFarmasi"])],
            ["Buffer Depo",                         Yii::$app->actionToUrl([MutasiUiController::class,              "actionBufferDepo"])],
            ["Laporan Produksi",                    Yii::$app->actionToUrl([ProduksiUiController::class,            "actionTable"])],
            ["Laporan Kasir",                       Yii::$app->actionToUrl([LaporanUiController::class,             "actionKasir"])],
            ["Persediaan 30 Juni",                  Yii::$app->actionToUrl([LaporanPersediaanUiController::class,   "actionForm30juni"])],
            ["Persediaan 30 Sept",                  Yii::$app->actionToUrl([LaporanPersediaanUiController::class,   "actionForm30sept"])],
            ["Persediaan 31 Des",                   Yii::$app->actionToUrl([LaporanPersediaanUiController::class,   "actionForm31des"])],
            ["Persediaan 31 Maret 2016",            Yii::$app->actionToUrl([LaporanPersediaanUiController::class,   "actionForm31maret2016"])],
            ["Persediaan Juni 2016",                Yii::$app->actionToUrl([LaporanPersediaanUiController::class,   "actionFormJuni2016"])],
            ["Persediaan Sept 2016",                Yii::$app->actionToUrl([LaporanPersediaanUiController::class,   "actionFormSept2016"])],
            ["Laporan Stok Opname",                 Yii::$app->actionToUrl([StokopnameUiController::class,          "actionLaporanSemuaDepo"])],
            ["Laporan Floor Stock TRIWULAN III",    Yii::$app->actionToUrl([LaporanFloorStockUiController::class,   "actionFormTriwulan3"])],
            ["Laporan Floor Stock TRIWULAN IV",     Yii::$app->actionToUrl([LaporanFloorStockUiController::class,   "actionFormTriwulan4"])],
            ["Laporan Floor Stock TRIWULAN 2 2016", Yii::$app->actionToUrl([LaporanFloorStockUiController::class,   "actionFormTriwulan2nd2016"])],
            ["Laporan Jadwal Operasi",              Yii::$app->actionToUrl([JadwalOperasiUiController::class,       "actionTableReport"])],
            ["Laporan Penerimaan Barang Farmasi",   Yii::$app->actionToUrl([LaporanPenerimaanUiController::class,   "actionFormPerPemasok"])],
            ["Rekap Penerimaan Gas Medis",          Yii::$app->actionToUrl([LaporanPenerimaanUiController::class,   "actionFormGasMedis"])],
            ["Rekap Tak Terlayani",                 Yii::$app->actionToUrl([LaporanTakTerlayaniUiController::class, "actionFormRekapTakTerlayani"])],
            ["Rekap Tak Terlayani 0",               Yii::$app->actionToUrl([LaporanTakTerlayaniUiController::class, "actionFormRekapTakTerlayani0"])],
            ["Laporan Tak Terlayani",               Yii::$app->actionToUrl([LaporanTakTerlayaniUiController::class, "actionFormTakTerlayani"])],
            ["Laporan Tak Terlayani 0",             Yii::$app->actionToUrl([LaporanTakTerlayaniUiController::class, "actionFormTakTerlayani0"])],
        ];
        ?>


<style>
#<?= $pageId ?> li.span2 {
    display: inline-block;
    vertical-align: top;
    width: 200px;
    height: 200px;
    margin-bottom: 4px;
}

#<?= $pageId ?> li.span2 a {
    display: block;
    margin: 0;
    min-width: 200px;
    min-height: 200px;
}
</style>

<div class="splPlainPage" id="<?= $pageId ?>">
    <ul class="thumbnails">
        <?php foreach ($items as $item): ?>
        <li class="span2">
            <a class="thumbnail" href="<?= $item[1] ?>">
                <i class="icon-list icon-jumbo"></i>
                <span><?= $h($item[0]) ?></span>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
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
