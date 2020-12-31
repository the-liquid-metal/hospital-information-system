<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\LaporanPembelianController as Pair;
use tlm\his\FatmaPharmacy\views\LaporanPembelianUi\{
    FormCariNoPlItem,
    FormLaporanAkhir,
    FormRealisasiBarang,
    IndexItemPl,
    IndexRealisasiPl,
    PrintRealisasiPl,
};
use tlm\libs\LowEnd\components\DateTimeException;
use tlm\libs\LowEnd\views\CustomIndexScript;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class LaporanPembelianUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeFormRealisasiBarang = Yii::$app->canAccess([self::class, "actionReportRealisasiBarang"]);
        $canSeeFormCariNoPlItem = Yii::$app->canAccess([self::class, "actionReportPlItem"]);
        $canSeeFormLaporanAkhir = Yii::$app->canAccess([Pair::class, "actionReportLaporanAkhir"]);
        $canSeeFormReportRealisasiBarang = Yii::$app->canAccess([Pair::class, "___"]);
        $canSeeFormReportPlItem = Yii::$app->canAccess([Pair::class, "actionItemPlTableData"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Laporan Pembelian";
        $index->tabs = [
            ["title" => "Realisasi Barang",        "canSee" => $canSeeFormRealisasiBarang,       "registerId" => Yii::$app->actionToId([self::class, "actionFormRealisasiBarang"])],
            ["title" => "Cari No Pl Item",         "canSee" => $canSeeFormCariNoPlItem,          "registerId" => Yii::$app->actionToId([self::class, "actionFormCariNoPlItem"])],
            ["title" => "Laporan Akhir",           "canSee" => $canSeeFormLaporanAkhir,          "registerId" => Yii::$app->actionToId([self::class, "actionFormLaporanAkhir"])],
            ["title" => "Report Realisasi Barang", "canSee" => $canSeeFormReportRealisasiBarang, "registerId" => Yii::$app->actionToId([self::class, "actionReportRealisasiBarang"])],
            ["title" => "Report PL Item",          "canSee" => $canSeeFormReportPlItem,          "registerId" => Yii::$app->actionToId([self::class, "actionReportPlItem"])],
        ];
        return $index->render();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionFormRealisasiBarang(): string
    {
        $view = new FormRealisasiBarang(
            registerId:       Yii::$app->actionToId(__METHOD__),
            actionUrl:        Yii::$app->actionToUrl([self::class, "actionReportRealisasiBarang"]),
            pembelianAcplUrl: Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionFormCariNoPlItem(): string
    {
        $view = new FormCariNoPlItem(
            registerId:     Yii::$app->actionToId(__METHOD__),
            actionUrl:      Yii::$app->actionToUrl([self::class, "actionReportPlItem"]),
            katalogAcplUrl: Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSearch"]),
            plItemSrcUrl:   Yii::$app->actionToUrl([Pair::class, "actionViewPlItem"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionFormLaporanAkhir(): string
    {
        $view = new FormLaporanAkhir(
            registerId:          Yii::$app->actionToId(__METHOD__),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionReportLaporanAkhir"]),
            pembelianAcplUrl:    Yii::$app->actionToUrl([PembelianController::class, "actionSearchJsonLainnya"]),
            jenisAnggaranSelect: Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionReportRealisasiBarang(): string
    {
        ["kode" => $kode, "format" => $format, "type" => $tipe] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($format == "index-realisasi-pl") {
            if ($tipe == "exportToExcel") {
                return $this->renderPartial("export-to-excel", [
                    "daftarField" => [
                        "no_doc" => "NO PL Pembelian",
                        "tgl_jatuhtempo" => "Tempo Kontrak",
                        "nama_pbf" => "Distributor",
                        "nama_sediaan" => "Nama Barang",
                        "nama_pabrik" => "Pabrik",
                        "kemasan" => "Kemasan",
                        "satuan" => "Satuan",
                        "id_reffkatalog" => "Referensi Katalog",
                        "jumlah_renc" => "Volume Perencanaan",
                        "jumlah_pl" => "Jumlah Kontrak",
                        "harga_pl" => "Harga Kontrak",
                        "harga_anggaran" => "Total Anggaran",
                        "jumlah_trm" => "Volume Terima",
                        "harga_trm" => "Harga Terima (AVG)",
                        "harga_realisasi" => "Total Realisasi",
                        "jumlah_sisa" => "Volume Sisa",
                        "anggaran_sisa" => "Sisa Anggaran",
                    ],
                    "data" => (new Pair("x", $this->module))->actionRealisasiPlTableData(),
                    "file_name" => "Realisasi PL",
                ]);
            } else {
                $view = new IndexRealisasiPl(
                    registerId:     Yii::$app->actionToId(__METHOD__),
                    dataUrl:        Yii::$app->actionToUrl([Pair::class, "actionRealisasiPlTableData"]),
                    reportWidgetId: Yii::$app->actionToId([self::class, "actionReportRealisasiBarang"]),
                );
                return $view->__toString();
            }

        } elseif ($format == "print-realisasi-pl") {
            $kodeReferensi = "'" . str_replace(",", "','", $kode) . "'";
            $daftarDetailPembelian = [];
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode              AS kode,
                    A.no_doc            AS noDokumen,
                    B.nama_pbf          AS namaPemasok,
                    C.subjenis_anggaran AS subjenisAnggaran,
                    A.blnawal_anggaran  AS bulanAwalAnggaran,
                    A.blnakhir_anggaran AS bulanAkhirAnggaran,
                    A.thn_anggaran      AS tahunAnggaran,
                    A.tgl_doc           AS tanggalDokumen,
                    A.tgl_jatuhtempo    AS tanggalJatuhTempo,
                    A.ppn               AS ppn,
                    A.nilai_total       AS nilaiTotal,
                    A.nilai_diskon      AS nilaiDiskon,
                    A.nilai_pembulatan  AS nilaiPembulatan,
                    A.nilai_ppn         AS nilaiPpn,
                    A.nilai_akhir       AS nilaiAkhir
                FROM db1.transaksif_pembelian AS A
                LEFT JOIN db1.masterf_pbf AS B ON A.id_pbf = B.id
                LEFT JOIN db1.masterf_subjenisanggaran AS C ON A.id_jenisanggaran = C.id
                WHERE
                    A.sts_deleted = 0
                    AND A.kode IN ($kodeReferensi)
            ";
            $daftarPembelian = $connection->createCommand($sql)->queryAll();

            $daftarPembelian2 = [];
            foreach ($daftarPembelian as $pembelian) {
                $daftarPembelian2[$pembelian->kode] = $pembelian;
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        A.id_katalog             AS idKatalog,
                        B.nama_sediaan           AS namaSediaan,
                        C.nama_pabrik            AS namaPabrik,
                        D.kode                   AS satuanJual,
                        E.kode                   AS satuan,
                        IFNULL(S.jumlah_item, 0) AS jumlahPo,
                        IFNULL(T.jumlah_item, 0) AS jumlahTerima,
                        A.isi_kemasan            AS isiKemasan,
                        A.id_kemasan             AS idKemasan,
                        A.id_kemasandepo         AS idKemasanDepo,
                        A.jumlah_item            AS jumlahItem,
                        A.jumlah_kemasan         AS jumlahKemasan,
                        A.harga_item             AS hargaItem,
                        A.harga_kemasan          AS hargaKemasan,
                        A.diskon_item            AS diskonItem
                    FROM db1.tdetailf_pembelian AS A
                    LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
                    LEFT JOIN db1.masterf_pabrik AS C ON A.id_pabrik = C.id
                    LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
                    LEFT JOIN db1.masterf_kemasan AS E ON A.id_kemasandepo = E.id
                    LEFT JOIN (
                        SELECT
                            A.id_katalog                  AS id_katalog,
                            A.kode_reffpl                 AS kode_reffpl,
                            IFNULL(SUM(A.jumlah_item), 0) AS jumlah_item
                        FROM db1.tdetailf_pemesanan AS A
                        INNER JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                        WHERE
                            B.sts_deleted = 0
                            AND A.kode_reffpl = :kode
                        GROUP BY A.kode_reffpl, A.id_katalog
                    ) AS S ON A.id_katalog = S.id_katalog
                    LEFT JOIN (
                        SELECT
                            A.id_katalog                  AS id_katalog,
                            A.kode_reffpl                 AS kode_reffpl,
                            IFNULL(SUM(A.jumlah_item), 0) AS jumlah_item
                        FROM db1.tdetailf_penerimaan AS A
                        INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                        WHERE
                            B.sts_deleted = 0
                            AND A.kode_reffpl = :kode
                        GROUP BY A.kode_reffpl, A.id_katalog
                    ) AS T ON A.id_katalog = T.id_katalog
                    WHERE
                        A.kode_reff = :kode
                        AND A.kode_reff = T.kode_reffpl
                        AND A.kode_reff = S.kode_reffpl
                ";
                $params = [":kode" => $pembelian->kode];
                $daftarDetailPembelian[$pembelian->kode] = $connection->createCommand($sql, $params)->queryAll();
            }

            if (!$daftarPembelian) return "tidak ada data";

            $view = new PrintRealisasiPl(daftarPembelian: $daftarPembelian2, daftarDetailPembelian: $daftarDetailPembelian);
            return $view->__toString();
        }
        return $this->renderPartial("_" . $format, []);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionReportPlItem(): string
    {
        ["type" => $tipe] = Yii::$app->request->post();
        if ($tipe == "exportToExcel") {
            return $this->renderPartial("_export-to-excel", [
                "fields" => [
                    "sts_closed" => "Status PL",
                    "no_doc" => "NO PL Pembelian",
                    "tgl_jatuhtempo" => "Tempo Kontrak",
                    "nama_pbf" => "Distributor",
                    "nama_pabrik" => "Pabrik",
                    "kemasan" => "Kemasan",
                    "jumlah_kemasan" => "Jumlah Kemasan",
                    "jumlah_item" => "Jumlah Item",
                ],
                "data" => (new Pair("x", $this->module))->actionItemPlTableData(),
                "file_name" => "Realisasi PL",
            ]);
        } else {
            $view = new IndexItemPl(
                registerId:           Yii::$app->actionToId(__METHOD__),
                dataUrl:              Yii::$app->actionToUrl([Pair::class, "actionItemPlTableData"]),
                reportPlItemWidgetId: Yii::$app->actionToId([self::class, "actionReportPlItem"]),
            );
            return $view->__toString();
        }
    }
}
