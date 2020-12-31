<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\libs\LowEnd\components\DateTimeException;
use tlm\libs\LowEnd\controllers\BaseController;
use tlm\his\FatmaPharmacy\views\LaporanTakTerlayani\ReportRekapTakTerlayani0;
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
class LaporanTakTerlayaniController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#print_rekap_harian_tdkterlayani    the original method
     */
    public function actionReportRekapTakTerlayani(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        ["tanggalAwal" => $tanggalAwal, "tanggalAkhir" => $tanggalAkhir] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kodeItem         AS kodeItem,
                c.nama_barang      AS namaBarang,
                SUM(a.jumlah1)     AS sumJumlah1,
                SUM(a.jumlah2)     AS sumJumlah2,
                g.kelompok_barang  AS namaKelompok,
                i.jumlah_stokfisik AS gudang,
                j.jumlah_stokfisik AS irj1,
                k.jumlah_stokfisik AS irj2,
                l.jumlah_stokfisik AS irj3,
                m.jumlah_stokfisik AS igh,
                n.jumlah_stokfisik AS ibs,
                o.jumlah_stokfisik AS igd,
                p.jumlah_stokfisik AS produksi,
                q.jumlah_stokfisik AS teratai,
                r.jumlah_stokfisik AS bougenvil
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_kelompokbarang AS g ON g.id = c.id_kelompokbarang
            LEFT JOIN db1.transaksif_stokkatalog AS i on i.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS j on j.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS k on k.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS l on l.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS m on m.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS n on n.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS o on o.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS p on p.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS q on q.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS r on r.id_katalog = a.kodeItem
            WHERE
                (
                    (
                        a.kodeDepo = :kodeDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir + INTERVAL 1 DAY
                        AND a.noPermintaan LIKE 'M%'
                        AND a.noPengeluaran != ''
                        AND a.jumlah2 < a.jumlah1
                    ) OR (
                        a.checking_pengiriman = 0
                        AND a.kodeDepo = :kodeDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir + INTERVAL 1 DAY
                        AND a.jumlah2 < a.jumlah1
                    )
                )
                AND i.id_depo = 59
                AND j.id_depo = 23
                AND k.id_depo = 64
                AND l.id_depo = 61
                AND m.id_depo = 25
                AND n.id_depo = 26
                AND o.id_depo = 27
                AND p.id_depo = 28
                AND q.id_depo = 30
                AND r.id_depo = 129
            GROUP BY c.nama_barang
            ORDER BY c.id_kelompokbarang, c.nama_barang
        ";
        $params = [
            ":kodeDepo" => Yii::$app->userFatma->idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir)
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        return $this->renderPartial("_report-rekap-tak-terlayani", [
            "depo" => Yii::$app->userFatma,
            "daftarPeringatan" => $daftarPeringatan,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "jenis" => 0,
            "html" => "",
            "i" => 1,
            "toUserFloat" => Yii::$app->number->toUserFloat(),
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#print_rekap_harian_tdkterlayani_0    the original method
     * last exist of actionPrintRekapHarianTdkTerlayani0: commit-e37d34f4
     */
    public function actionReportRekapTakTerlayani0(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        ["fromdate" => $tanggalAwal, "enddate" => $tanggalAkhir] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kodeItem        AS kodeItem,
                c.nama_barang     AS namaBarang,
                SUM(a.jumlah1)    AS sumJumlah1,
                SUM(a.jumlah2)    AS sumJumlah2,
                g.kelompok_barang AS namaKelompok
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_kelompokbarang AS g ON g.id = c.id_kelompokbarang
            LEFT JOIN db1.transaksif_stokkatalog AS i ON i.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS j ON j.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS k ON k.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS l ON l.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS m ON m.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS n ON n.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS o ON o.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS p ON p.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS q ON q.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS r ON r.id_katalog = a.kodeItem
            WHERE
                (
                    (
                        a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir + INTERVAL 1 DAY
                        AND a.noPermintaan LIKE 'M%'
                        AND a.noPengeluaran != ''
                        AND a.jumlah2 = 0
                    ) OR (
                        a.checking_pengiriman = 0
                        AND a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir + INTERVAL 1 DAY
                        AND a.jumlah2 = 0
                    )
                )
                AND i.id_depo = 59
                AND j.id_depo = 23
                AND k.id_depo = 64
                AND l.id_depo = 61
                AND m.id_depo = 25
                AND n.id_depo = 26
                AND o.id_depo = 27
                AND p.id_depo = 28
                AND q.id_depo = 30
                AND r.id_depo = 129
            GROUP BY c.nama_barang
            ORDER BY c.id_kelompokbarang, c.nama_barang
        ";
        $params = [
            ":idDepo" => Yii::$app->userFatma->idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir),
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        $view = new ReportRekapTakTerlayani0(
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
            depo:             Yii::$app->userFatma,
            daftarPeringatan: $daftarPeringatan,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#PrintHarianTdkTerlayani    the original method (not exist)
     */
    public function actionReportTakTerlayani(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        ["tanggalAwal" => $tanggalAwal, "tanggalAkhir" => $tanggalAkhir] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc           AS noDokumen,
                b.namaDepo         AS namaDepo,
                a.kodeItem         AS kodeItem,
                c.nama_barang      AS namaBarang,
                a.jumlah1          AS jumlah1,
                a.jumlah2          AS jumlah2,
                g.kelompok_barang  AS namaKelompok,
                h.jumlah_stokfisik AS stokMinta,
                i.jumlah_stokfisik AS stokBeri
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            LEFT JOIN db1.masterf_depo AS j ON j.kode = a.kodeDepo
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_kelompokbarang AS g ON g.id = c.id_kelompokbarang
            LEFT JOIN db1.transaksif_stokkatalog AS h ON h.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS i ON i.id_katalog = a.kodeItem
            WHERE
                (
                        (
                        a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir + INTERVAL 1 DAY
                        AND a.noPermintaan LIKE 'M%'
                        AND a.noPengeluaran != ''
                        AND a.jumlah2 < a.jumlah1
                    )  OR (
                        a.checking_pengiriman = 0
                        AND a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir + INTERVAL 1 DAY
                        AND a.jumlah2 < a.jumlah1
                    )
                )
                AND h.id_depo = b.id
                AND i.id_depo = j.id
            ORDER BY c.id_kelompokbarang, c.nama_barang
        ";
        $params = [
            ":idDepo" => Yii::$app->userFatma->idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir),
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        return $this->renderPartial("_report-tak-terlayani", [
            "depo" => Yii::$app->userFatma,
            "daftarPeringatan" => $daftarPeringatan,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "jenis" => 0,
            "html" => "",
            "i" => 1,
            "toUserFloat" => Yii::$app->number->toUserFloat(),
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#PrintHarianTdkTerlayani0    the original method (not exist))
     */
    public function actionReportTakTerlayani0(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        ["tanggalAwal" => $tanggalAwal, "tanggalAkhir" => $tanggalAkhir] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc           AS noDokumen,
                a.kodeItem         AS kodeItem,
                c.nama_barang      AS namaBarang,
                SUM(a.jumlah1)     AS sumJumlah1,
                SUM(a.jumlah2)     AS sumJumlah2,
                g.kelompok_barang  AS namaKelompok,
                b.namaDepo         AS namaDepo,
                h.jumlah_stokfisik AS stokMinta,
                i.jumlah_stokfisik AS stokBeri
            FROM db1.master_warning AS a
            LEFT JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            LEFT JOIN db1.masterf_depo AS j ON j.kode = a.kodeDepo
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_kelompokbarang AS g ON g.id = c.id_kelompokbarang
            LEFT JOIN db1.transaksif_stokkatalog AS h ON h.id_katalog = a.kodeItem
            LEFT JOIN db1.transaksif_stokkatalog AS i ON i.id_katalog = a.kodeItem
            WHERE
                (
                    (
                        a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir + INTERVAL 1 DAY
                        AND a.noPermintaan LIKE 'M%'
                        AND a.noPengeluaran != ''
                        AND a.jumlah2 = 0
                    ) OR (
                        a.checking_pengiriman = 0
                        AND a.kodeDepo = :idDepo
                        AND a.tanggal >= :tanggalAwal
                        AND a.tanggal <= :tanggalAkhir + INTERVAL 1 DAY
                        AND a.jumlah2 = 0
                    )
                )
                AND h.id_depo = b.id
                AND i.id_depo = j.id
            GROUP BY
                a.depoPeminta,
                c.nama_barang,
                a.noPermintaan
            ORDER BY c.id_kelompokbarang, c.nama_barang
        ";
        $params = [
            ":idDepo" => Yii::$app->userFatma->idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir),
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        return $this->renderPartial("_report-tak-terlayani0", [
            "depo" => Yii::$app->userFatma,
            "daftarPeringatan" => $daftarPeringatan,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "jenis" => 0,
            "html" => "",
            "i" => 1,
            "toUserFloat" => Yii::$app->number->toUserFloat(),
        ]);
    }
}
