<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\LaporanFloorStock\{ReportTriwulan2nd2016, ReportTriwulan3, ReportTriwulan4};
use tlm\libs\LowEnd\components\DateTimeException;
use tlm\libs\LowEnd\controllers\BaseController;
use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 * @see - (none)
 */
class LaporanFloorStockController extends BaseController
{
    /**
     * @throws DateTimeException
     * @throws Exception
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#laporanfloorstock    the original method
     * last exist of actionLaporanFloorStockData: commit-e37d34f4
     */
    public function actionReportTriwulan3(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.system_in                      AS systemInput,
                g.jenis_obat                     AS jenisObat,
                a.tgl_expired                    AS tanggalKadaluarsa,
                SUM(a.jumlah_item * a.harga_item)AS total,
                a.verifikasi                     AS verifikasi,
                a.tgl_verifikasi                 AS tanggalVerifikasi
            FROM db1.relasif_floorstock AS a
            LEFT JOIN db1.masterf_katalog AS kt ON kt.kode = a.id_katalog
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = kt.id_jenisbarang
            WHERE SUBSTR(a.system_in, 6, 2) IN(09, 10)
            GROUP BY jenis_obat
            ORDER BY jenis_obat ASC
        ";
        $daftarFloorStock = $connection->createCommand($sql)->queryAll();

        $daftarHalaman = [];
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;

        foreach ($daftarFloorStock as $i => $floorStock) {
            $daftarHalaman[$h][$b] = ["i" => $i];
            $totalNilai += $floorStock->total;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportTriwulan3(
            daftarFloorStock: $daftarFloorStock,
            daftarHalaman:    $daftarHalaman,
            bulan:            (int) Yii::$app->request->post("bulan"),
            jumlahHalaman:    count($daftarHalaman),
            totalNilai:       $totalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/???.php#???    the original method
     * last exist of actionLaporanFloorStockDesData: commit-e37d34f4
     */
    public function actionReportTriwulan4(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.system_in                       AS systemInput,
                g.jenis_obat                      AS jenisObat,
                a.tgl_expired                     AS tanggalKadaluarsa,
                SUM(a.jumlah_item * a.harga_item) AS total,
                a.verifikasi                      AS verifikasi,
                a.tgl_verifikasi                  AS tanggalVerifikasi
            FROM db1.relasif_floorstock AS a
            LEFT JOIN db1.masterf_katalog AS kt ON kt.kode = a.id_katalog
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = kt.id_jenisbarang
            WHERE
                SUBSTR(a.system_in, 6, 2) IN(12, 01)
                AND a.verifikasi != ''
            GROUP BY jenis_obat
            ORDER BY jenis_obat ASC
        ";
        $daftarFloorStock = $connection->createCommand($sql)->queryAll();

        $daftarHalaman = [];
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;

        foreach ($daftarFloorStock as $i => $floorStock) {
            $daftarHalaman[$h][$b] = ["i" => $i];
            $totalNilai += $floorStock->total;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportTriwulan4(
            listFloorWidgetId: Yii::$app->actionToId([StokopnameUiController::class, "actionTableFloorStock"]),
            daftarFloorStock:  $daftarFloorStock,
            daftarHalaman:     $daftarHalaman,
            jumlahHalaman:     count($daftarHalaman),
            bulan:             (int) Yii::$app->request->post("bulan"),
            totalNilai:        $totalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#laporanfloorstockjuni16    the original method
     * last exist of actionLaporanFloorStockJuni16Data: commit-e37d34f4
     */
    public function actionReportTriwulan2nd2016(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.system_in                       AS systemInput,
                g.jenis_obat                      AS jenisObat,
                a.tgl_expired                     AS tanggalKadaluarsa,
                SUM(a.jumlah_item * a.harga_item) AS total,
                a.verifikasi                      AS verifikasi,
                a.tgl_verifikasi                  AS tanggalVerifikasi
            FROM db1.relasif_floorstock AS a
            LEFT JOIN db1.masterf_katalog AS kt ON kt.kode = a.id_katalog
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = kt.id_jenisbarang
            WHERE
                SUBSTR(a.tgl_verifikasi, 6, 2) IN(06, 07)
                AND SUBSTR(a.tgl_verifikasi, 3, 2) IN(16)
                AND a.verifikasi != ''
            GROUP BY jenis_obat
            ORDER BY jenis_obat ASC
        ";
        $daftarFloorStock = $connection->createCommand($sql)->queryAll();

        $daftarHalaman = [];
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;

        foreach ($daftarFloorStock as $i => $floorStock) {
            $daftarHalaman[$h][$b] = ["i" => $i];
            $totalNilai += $floorStock->total;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        // TODO: php: uncategorized: to be deleted
        $this->render("xxx", [
            "daftarFloorStock" => $daftarFloorStock, // TODO: php: to be deleted
        ]);
        $view = new ReportTriwulan2nd2016(
            daftarHalaman: $daftarHalaman,
            jumlahHalaman: count($daftarHalaman),
            bulan:         (string) Yii::$app->request->post("bulan"),
            totalNilai:    $totalNilai,
        );
        return $view->__toString();
    }
}
