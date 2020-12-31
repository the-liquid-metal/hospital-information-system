<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use PHPExcel;
use PHPExcel_Cell_DataType;
use PHPExcel_Exception;
use PHPExcel_IOFactory;
use PHPExcel_Reader_Exception;
use PHPExcel_Writer_Exception;
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
class LaporanBufferStokController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * TODO: php: uncategorized: rollback again based again farmasi/mutasi controller
     */
    public function actionReportFarmasi(): string
    {
        return "";
    }

    /**
     * @author Hendra Gunawan
     * TODO: php: uncategorized: rollback again based again farmasi/mutasi controller
     */
    public function actionReportDepo(): string
    {
        return "";
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#filterbufferfarmasi    the original method
     */
    public function actionBufferFarmasiData(): string
    {
        [   "idKatalog" => $idKatalog,
            "idGenerik" => $idGenerik,
            "jenisMoving" => $jenisMoving,
            "namaSediaan" => $namaSediaan,
            "levelStok" => $levelStok,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $params = [
            ":idKatalog" => $idKatalog ? "%$idKatalog%" : "",
            ":idGenerik" => $idGenerik ?? "",
            ":jenisMoving" => $jenisMoving ?? "",
            ":namaSediaan" => $namaSediaan ? "%$namaSediaan%" : "",
            ":levelStok" => $levelStok ?? "",
        ];

        $connection = Yii::$app->dbFatma;

        // TODO: sql: refactor: "WHERE id_depo NOT IN (320, 321)" statement
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                LBGD.id_katalog                                                   AS idKatalog,
                LBGD.jenis_moving                                                 AS jenisMoving,
                LBGD.sysdate_updt                                                 AS updatedTime,
                LBGD.jumlah_avg                                                   AS jumlahAverage,
                LBGD.jumlah_buffer                                                AS jumlahBuffer,
                LBGD.jumlah_leadtime                                              AS jumlahLeadtime,
                LBGD.jumlah_rop                                                   AS jumlahRop,
                (LBGD.jumlah_avg + LBGD.jumlah_rop)                               AS jumlahOptimum,
                (LBGD.jumlah_avg + LBGD.jumlah_rop - tabel_stok.jumlah_stokfisik) AS estimasiPerencanaan,
                KAT.nama_sediaan                                                  AS namaSediaan,
                GEN.nama_generik                                                  AS namaGenerik,
                JO.jenis_obat                                                     AS jenisObat,
                KBG.kelompok_barang                                               AS kelompokBarang,
                tabel_stok.jumlah_stokfisik                                       AS jumlahStokFisik,
                U.name                                                            AS updatedBy
            FROM db1.laporan_buffer_gudang AS LBGD
            LEFT JOIN db1.masterf_katalog AS KAT ON LBGD.id_katalog = KAT.kode
            LEFT JOIN db1.masterf_generik AS GEN ON LBGD.id_generik = GEN.id
            LEFT JOIN db1.masterf_jenisobat AS JO ON KAT.id_jenisbarang = JO.id
            LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
            LEFT JOIN (
                SELECT
                    id_katalog            AS id_katalog,
                    SUM(jumlah_stokfisik) AS jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
                WHERE id_depo NOT IN (320, 321)
                GROUP BY id_katalog
            ) AS tabel_stok ON LBGD.id_katalog = tabel_stok.id_katalog
            LEFT JOIN db1.user AS U ON LBGD.userid_updt = U.id
            WHERE
                (:idKatalog = '' OR LBGD.id_katalog LIKE :idKatalog)
                AND (:idGenerik = '' OR LBGD.id_generik = :idGenerik)
                AND (:jenisMoving = '' OR LBGD.jenis_moving = :jenisMoving)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (
                    (:levelStok    = 3 AND tabel_stok.jumlah_stokfisik >  LBGD.jumlah_rop)
                    OR (:levelStok = 2 AND tabel_stok.jumlah_stokfisik <= LBGD.jumlah_rop)
                    OR (:levelStok = 1 AND tabel_stok.jumlah_stokfisik <= LBGD.jumlah_rop AND tabel_stok.jumlah_stokfisik >= LBGD.jumlah_buffer)
                    OR (:levelStok = 0 AND tabel_stok.jumlah_stokfisik <  LBGD.jumlah_buffer)
                )
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarLaporanBuffer = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.laporan_buffer_gudang AS LBGD
            LEFT JOIN db1.masterf_katalog AS KAT ON LBGD.id_katalog = KAT.kode
            LEFT JOIN db1.masterf_generik AS GEN ON LBGD.id_generik = GEN.id
            LEFT JOIN db1.masterf_jenisobat AS JO ON KAT.id_jenisbarang = JO.id
            LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
            LEFT JOIN (
                SELECT
                    id_katalog            AS id_katalog,
                    SUM(jumlah_stokfisik) AS jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
                WHERE id_depo NOT IN (320, 321)
                GROUP BY id_katalog
            ) AS tabel_stok ON LBGD.id_katalog = tabel_stok.id_katalog
            LEFT JOIN db1.user AS U ON LBGD.userid_updt = U.id
            WHERE
                (:idKatalog = '' OR LBGD.id_katalog LIKE :idKatalog)
                AND (:idGenerik = '' OR LBGD.id_generik = :idGenerik)
                AND (:jenisMoving = '' OR LBGD.jenis_moving = :jenisMoving)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (
                    (:levelStok    = 3 AND tabel_stok.jumlah_stokfisik >  LBGD.jumlah_rop)
                    OR (:levelStok = 2 AND tabel_stok.jumlah_stokfisik <= LBGD.jumlah_rop)
                    OR (:levelStok = 1 AND tabel_stok.jumlah_stokfisik <= LBGD.jumlah_rop AND tabel_stok.jumlah_stokfisik >= LBGD.jumlah_buffer)
                    OR (:levelStok = 0 AND tabel_stok.jumlah_stokfisik <  LBGD.jumlah_buffer)
                )
        ";
        $jumlahRow = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode(["recordsFiltered" => $jumlahRow, "data" => $daftarLaporanBuffer]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#filterbufferdepo    the original method
     */
    public function actionBufferDepoData(): string
    {
        [   "idKatalog" => $idKatalog,
            "idDepo" => $idDepo,
            "jenisMoving" => $jenisMoving,
            "namaSediaan" => $namaSediaan,
            "levelStok" => $levelStok,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $params = [
            ":idKatalog" => $idKatalog ? "%$idKatalog%" : "",
            ":idDepo" => $idDepo,
            ":jenisMoving" => $jenisMoving,
            ":namaSediaan" => $namaSediaan ? "%$namaSediaan%" : "",
            ":levelStok" => $levelStok,
        ];

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                LBDP.id_katalog                        AS idKatalog,
                LBDP.id_depo                           AS idDepo,
                DP.namaDepo                            AS namaDepo,
                LBDP.jenis_moving                      AS jenisMoving,
                LBDP.sysdate_updt                      AS updatedTime,
                LBDP.jumlah_buffer                     AS jumlahBuffer,
                LBDP.jumlah_leadtime                   AS jumlahLeadtime,
                LBDP.jumlah_rop                        AS jumlahRop,
                (LBDP.jumlah_rop + LBDP.jumlah_buffer) AS jumlahOptimum,
                KAT.nama_sediaan                       AS namaSediaan,
                JOB.jenis_obat                         AS jenisObat,
                KBG.kelompok_barang                    AS kelompokBarang,
                IFNULL(tabel_stok.jumlah_stokfisik, 0) AS jumlahStokFisik,
                U.name                                 AS updatedBy
            FROM db1.laporan_buffer_depo AS LBDP
            LEFT JOIN db1.masterf_depo AS DP ON LBDP.id_depo = DP.id
            LEFT JOIN db1.masterf_katalog AS KAT ON LBDP.id_katalog = KAT.kode
            LEFT JOIN db1.masterf_jenisobat AS JOB ON KAT.id_jenisbarang = JOB.id
            LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
            LEFT JOIN (
                SELECT
                    id_katalog,
                    id_depo,
                    jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
            ) AS tabel_stok ON LBDP.id_katalog = tabel_stok.id_katalog
            LEFT JOIN db1.user AS U ON LBDP.userid_updt = U.id
            WHERE
                (:idKatalog = '' OR LBDP.id_katalog LIKE :idKatalog)
                AND (:idDepo = '' OR LBDP.id_depo = :idDepo)
                AND (:jenisMoving = '' OR LBDP.jenis_moving = :jenisMoving)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (
                    (:levelStok    = 3 AND tabel_stok.jumlah_stokfisik >  LBDP.jumlah_rop)
                    OR (:levelStok = 2 AND tabel_stok.jumlah_stokfisik <= LBDP.jumlah_rop)
                    OR (:levelStok = 1 AND tabel_stok.jumlah_stokfisik <= LBDP.jumlah_rop AND tabel_stok.jumlah_stokfisik >= LBDP.jumlah_buffer)
                    OR (:levelStok = 0 AND tabel_stok.jumlah_stokfisik <  LBDP.jumlah_buffer)
                )
                AND LBDP.id_depo = tabel_stok.id_depo
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarLaporanBuffer = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.laporan_buffer_depo AS LBDP
            LEFT JOIN db1.masterf_depo AS DP ON LBDP.id_depo = DP.id
            LEFT JOIN db1.masterf_katalog AS KAT ON LBDP.id_katalog = KAT.kode
            LEFT JOIN db1.masterf_jenisobat AS JOB ON KAT.id_jenisbarang = JOB.id
            LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
            LEFT JOIN (
                SELECT
                    id_katalog,
                    id_depo,
                    jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
            ) AS tabel_stok ON LBDP.id_katalog = tabel_stok.id_katalog
            LEFT JOIN db1.user AS U ON LBDP.userid_updt = U.id
            WHERE
                (:idKatalog = '' OR LBDP.id_katalog LIKE :idKatalog)
                AND (:idDepo = '' OR LBDP.id_depo = :idDepo)
                AND (:jenisMoving = '' OR LBDP.jenis_moving = :jenisMoving)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (
                    (:levelStok    = 3 AND tabel_stok.jumlah_stokfisik >  LBDP.jumlah_rop)
                    OR (:levelStok = 2 AND tabel_stok.jumlah_stokfisik <= LBDP.jumlah_rop)
                    OR (:levelStok = 1 AND tabel_stok.jumlah_stokfisik <= LBDP.jumlah_rop AND tabel_stok.jumlah_stokfisik >= LBDP.jumlah_buffer)
                    OR (:levelStok = 0 AND tabel_stok.jumlah_stokfisik <  LBDP.jumlah_buffer)
                )
                AND LBDP.id_depo = tabel_stok.id_depo
        ";
        $jumlahRow = $connection->createCommand($sql, $params)->queryOne();

        return json_encode(["recordsFiltered" => $jumlahRow, "data" => $daftarLaporanBuffer]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#exportbufferfarmasi    the original method
     */
    public function actionExportBufferFarmasi(): void
    {
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        [   "id_katalog" => $idKatalog,
            "id_generik" => $idGenerik,
            "jenis_moving" => $jenisMoving,
            "nama_sediaan" => $namaSediaan,
            "level_stok" => $levelStok,
            "data_penjualan" => $dataPenjualan,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $columns = "";
        $joins = "";

        if ($dataPenjualan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT sysdate_updt
                FROM db1.laporan_buffer_gudang
                LIMIT 1
            ";
            $waktu = $connection->createCommand($sql)->queryScalar();
            $bulan = (int) date("n", $waktu);
            $tahun = (int) date("Y", $waktu);

            $filterWaktu = [];
            $bulanSaatIni = $bulan == 1 ? 12 : $bulan - 1;
            $tahunSaatIni = $bulan == 1 ? $tahun - 1 : $tahun;

            $durasi = 6;
            for ($i = 0; $i < $durasi; $i++) {
                $filterWaktu[] = [
                    "bulan" => $bulanSaatIni,
                    "tahun" => $tahunSaatIni
                ];
                $bulanSaatIni--;
                if ($bulanSaatIni == 0) {
                    $bulanSaatIni = 12;
                    $tahunSaatIni--;
                }
            }

            $daftarColumn = [];
            $daftarJoin = [];
            foreach ($filterWaktu as $key => ["bulan" => $rBulan, "tahun" => $rTahun]) {
                $idx = $key + 1;

                $daftarColumn[] = "
                    B_$idx.jumlah_penjualan AS p_$idx,
                    B_$idx.jumlah_floorstok  AS f_$idx,
                ";

                $daftarJoin[] = "
                    LEFT JOIN (
                        SELECT
                            id_katalog,
                            jumlah_penjualan,
                            jumlah_floorstok
                        FROM db1.laporan_mutasi_bulan
                        WHERE
                            bulan = '$rBulan'
                            AND tahun = '$rTahun'
                    ) AS B_$idx ON LBGD.id_katalog = B_$idx.id_katalog
                ";
            }

            $joins = $daftarJoin ? implode("", $daftarJoin) : $joins;
            $columns = $daftarColumn ? implode("", $daftarColumn) : $columns;
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                LBGD.id_katalog                                                     AS idKatalog,           -- in use
                LBGD.jenis_moving                                                   AS jenisMoving,         -- in use
                LBGD.persen_buffer                                                  AS persenBuffer,        -- in use
                LBGD.persen_leadtime                                                AS persenLeadtime,      -- in use
                LBGD.sysdate_updt                                                   AS sysdateUpdate,       -- in use
                LBGD.jumlah_avg                                                     AS jumlahAvg,
                LBGD.jumlah_buffer                                                  AS jumlahBuffer,        -- in use
                LBGD.jumlah_leadtime                                                AS jumlahLeadtime,      -- in use
                LBGD.jumlah_rop                                                     AS jumlahRop,           -- in use
                (LBGD.jumlah_avg + LBGD.jumlah_rop)                                 AS jumlahOptimum,       -- in use
                ((LBGD.jumlah_avg + LBGD.jumlah_rop) - tabel_stok.jumlah_stokfisik) AS estimasiPerencanaan, -- in use
                KAT.nama_sediaan                                                    AS namaSediaan,         -- in use
                GEN.nama_generik                                                    AS namaGenerik,         -- in use
                JOB.jenis_obat                                                      AS jenisObat,           -- in use
                KBG.kelompok_barang                                                 AS kelompokBarang,      -- in use
                IFNULL(tabel_stok.jumlah_stokfisik, 0)                              AS jumlahStokFisik,     -- in use
                tabel_harga_perolehan.hp_item                                       AS hpItem,              -- in use
                U.name                                                              AS namaUserUbah,
                ''                                                                  AS xxx                  -- COLUMN_CLAUSE
            FROM db1.laporan_buffer_gudang AS LBGD
            LEFT JOIN db1.masterf_katalog AS KAT ON LBGD.id_katalog = KAT.kode
            LEFT JOIN db1.masterf_generik AS GEN ON LBGD.id_generik = GEN.id
            LEFT JOIN db1.masterf_jenisobat AS JOB ON KAT.id_jenisbarang = JOB.id
            LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
            LEFT JOIN (
                SELECT
                    id_katalog            AS id_katalog,
                    SUM(jumlah_stokfisik) AS jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
                GROUP BY id_katalog
            ) AS tabel_stok ON LBGD.id_katalog = tabel_stok.id_katalog
            LEFT JOIN db1.user AS U ON LBGD.userid_updt = U.id
            LEFT JOIN (
                SELECT
                    A.id_katalog,
                    A.hp_item
                FROM db1.relasif_hargaperolehan AS A
                INNER JOIN (
                    SELECT
                        id_katalog  AS id_katalog,
                        MAX(tgl_hp) AS tgl_hp
                    FROM db1.relasif_hargaperolehan
                    GROUP BY id_katalog
                ) AS B ON A.id_katalog = B.id_katalog
                WHERE A.tgl_hp = B.tgl_hp
                GROUP BY id_katalog
            ) AS tabel_harga_perolehan ON LBGD.id_katalog = tabel_harga_perolehan.id_katalog
            -- JOIN_CLAUSE
            WHERE
                (:idKatalog = '' OR LBGD.id_katalog LIKE :idKatalog)
                AND (:idGenerik = '' OR LBGD.id_generik = :idGenerik)
                AND (:jenisMoving = '' OR LBGD.jenis_moving = :jenisMoving)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (
                    :levelStok = ''
                    OR (:levelStok = 3 AND tabel_stok.jumlah_stokfisik >  LBGD.jumlah_rop)
                    OR (:levelStok = 2 AND tabel_stok.jumlah_stokfisik <= LBGD.jumlah_rop)
                    OR (:levelStok = 1 AND tabel_stok.jumlah_stokfisik <= LBGD.jumlah_rop AND tabel_stok.jumlah_stokfisik >= LBGD.jumlah_buffer)
                    OR (:levelStok = 0 AND tabel_stok.jumlah_stokfisik <  LBGD.jumlah_buffer)
                )
        ";
        $params = [
            ":idKatalog" => $idKatalog ? "%$idKatalog%" : "",
            ":idGenerik" => $idGenerik ?? "",
            ":jenisMoving" => $jenisMoving ?? "",
            ":namaSediaan" => $namaSediaan ? "%$namaSediaan%" : "",
            ":levelStok" => $levelStok ?? "",
        ];
        $sql = $joins ? str_replace("-- JOIN_CLAUSE", $joins, $sql) : $sql;
        $sql = $columns ? preg_replace("/^.+ -- COLUMN_CLAUSE *$/m", $columns, $sql) : $sql;
        $daftarLaporanBufferGudang = $connection->createCommand($sql, $params)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost")
            ->setCategory("Approved by Fatmahost");

        $object->setActiveSheetIndex()
            ->setCellValue("B1", "LAPORAN BUFFER FARMASI")
            ->setCellValue("B2", "Tanggal Cetak")
            ->setCellValue("C2", $nowValUser);

        $object->setActiveSheetIndex()
            ->setCellValue("A4", "NO")
            ->setCellValue("B4", "KODE")
            ->setCellValue("C4", "NAMA BARANG")
            ->setCellValue("D4", "NAMA GENERIK")
            ->setCellValue("E4", "MOVING")
            ->setCellValue("F4", "PERSEN BUFFER")
            ->setCellValue("G4", "PERSEN LEADTIME")
            ->setCellValue("H4", "JUMLAH BUFFER")
            ->setCellValue("I4", "JUMLAH LEADTIME")
            ->setCellValue("J4", "JUMLAH ROP")
            ->setCellValue("K4", "JUMLAH OPTIMUM")
            ->setCellValue("L4", "STOK")
            ->setCellValue("M4", "HP ITEM")
            ->setCellValue("N4", "ESTIMASI HABIS")
            ->setCellValue("O4", "ESTIMASI PERENCANAAN")
            ->setCellValue("P4", "JENIS BARANG")
            ->setCellValue("Q4", "KELOMPOK BARANG")
            ->setCellValue("R4", "LAST UPDATE")
            ->setCellValue("S4", "USER UPDATE");

        if ($dataPenjualan) {
            $object->setActiveSheetIndex()
                ->setCellValue("T4", "P_1")
                ->setCellValue("U4", "P_2")
                ->setCellValue("V4", "P_3")
                ->setCellValue("W4", "P_4")
                ->setCellValue("X4", "P_5")
                ->setCellValue("Y4", "P_6")
                ->setCellValue("Z4", "F_1")
                ->setCellValue("AA4", "F_2")
                ->setCellValue("AB4", "F_3")
                ->setCellValue("AC4", "F_4")
                ->setCellValue("AD4", "F_5")
                ->setCellValue("AE4", "F_6");
        }

        $no = 5;
        $noObat = 1;

        foreach ($daftarLaporanBufferGudang as $key => $row) {
            $persentaseStok = $row->jumlahOptimum ? ($row->jumlahStokFisik / $row->jumlahOptimum) : 0;

            $jumlahHari = $persentaseStok * 30;
            $sisaBulan = floor($jumlahHari / 30);
            $sisaHari = $jumlahHari % 30;

            $sisa = "";
            $sisa .= $sisaBulan ? "$sisaBulan bulan " : "";
            $sisa .= $sisaHari ? "$sisaHari hari" : "";

            $estimasiPerencanaan = $row->estimasiPerencanaan ? $row->estimasiPerencanaan : 0;

            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $noObat++)
                ->setCellValueExplicit("B" . $no, $row->idKatalog, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("C" . $no, $row->namaSediaan)
                ->setCellValue("D" . $no, $row->namaGenerik)
                ->setCellValue("E" . $no, $row->jenisMoving)
                ->setCellValue("F" . $no, $row->persenBuffer)
                ->setCellValue("G" . $no, $row->persenLeadtime)
                ->setCellValue("H" . $no, $row->jumlahBuffer)
                ->setCellValue("I" . $no, $row->jumlahLeadtime)
                ->setCellValue("J" . $no, $row->jumlahRop)
                ->setCellValue("K" . $no, $row->jumlahOptimum)
                ->setCellValue("L" . $no, $row->jumlahStokFisik)
                ->setCellValue("M" . $no, $row->hpItem)
                ->setCellValue("N" . $no, $sisa)
                ->setCellValue("O" . $no, $estimasiPerencanaan)
                ->setCellValue("P" . $no, $row->jenisObat)
                ->setCellValue("Q" . $no, $row->kelompokBarang)
                ->setCellValue("R" . $no, $toUserDatetime($row->sysdateUpdate))
                ->setCellValue("S" . $no, $row->namaUserUbah);

            if ($dataPenjualan) {
                $object->setActiveSheetIndex()
                    ->setCellValue("T" . $no, $row->p_1)
                    ->setCellValue("U" . $no, $row->p_2)
                    ->setCellValue("V" . $no, $row->p_3)
                    ->setCellValue("W" . $no, $row->p_4)
                    ->setCellValue("X" . $no, $row->p_5)
                    ->setCellValue("Y" . $no, $row->p_6)
                    ->setCellValue("Z" . $no, $row->f_1)
                    ->setCellValue("AA" . $no, $row->f_2)
                    ->setCellValue("AB" . $no, $row->f_3)
                    ->setCellValue("AC" . $no, $row->f_4)
                    ->setCellValue("AD" . $no, $row->f_5)
                    ->setCellValue("AE" . $no, $row->f_6);
            }

            $no++;
        }

        $object->getActiveSheet()->setTitle("Buffer Farmasi");
        $object->setActiveSheetIndex();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="buffer_farmasi.xlsx"');
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($object, "Excel2007");
        $objWriter->save("php://output");
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#exportbufferfarmasi    the original method
     * truely "mutasi.php#exportbufferfarmasi". original class does not have "exportbufferdepo".
     * this method is cloned in order to allow separate development between "farmasi" and "depo".
     * originally this action does not afiliate with form, but only plain link and does not have parameter.
     * now this action is adjusted with saringFrm, but still lack of "idDepo" parameter.
     */
    public function actionExportBufferDepo(): void
    {
        $toUserDatetime = Yii::$app->dateTime->transformFunc("toUserDatetime");
        $nowValUser = Yii::$app->dateTime->nowVal("user");
        [   "id_katalog" => $idKatalog,
            "jenis_moving" => $jenisMoving,
            "nama_sediaan" => $namaSediaan,
            "level_stok" => $levelStok,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                LBGD.id_katalog                                                     AS idKatalog,           -- in use
                LBGD.jenis_moving                                                   AS jenisMoving,         -- in use
                LBGD.persen_buffer                                                  AS persenBuffer,        -- in use
                LBGD.persen_leadtime                                                AS persenLeadtime,      -- in use
                LBGD.sysdate_updt                                                   AS sysdateUpdate,       -- in use
                LBGD.jumlah_avg                                                     AS jumlahAvg,
                LBGD.jumlah_buffer                                                  AS jumlahBuffer,        -- in use
                LBGD.jumlah_leadtime                                                AS jumlahLeadtime,      -- in use
                LBGD.jumlah_rop                                                     AS jumlahRop,           -- in use
                (LBGD.jumlah_avg + LBGD.jumlah_rop)                                 AS jumlahOptimum,       -- in use
                ((LBGD.jumlah_avg + LBGD.jumlah_rop) - tabel_stok.jumlah_stokfisik) AS estimasiPerencanaan, -- in use
                KAT.nama_sediaan                                                    AS namaSediaan,         -- in use
                GEN.nama_generik                                                    AS namaGenerik,         -- in use
                JOB.jenis_obat                                                      AS jenisObat,           -- in use
                KBG.kelompok_barang                                                 AS kelompokBarang,      -- in use
                IFNULL(tabel_stok.jumlah_stokfisik, 0)                              AS jumlahStokFisik,     -- in use
                tabel_harga_perolehan.hp_item                                       AS hpItem,              -- in use
                U.name                                                              AS namaUserUbah
            FROM db1.laporan_buffer_gudang AS LBGD
            LEFT JOIN db1.masterf_katalog AS KAT ON LBGD.id_katalog = KAT.kode
            LEFT JOIN db1.masterf_generik AS GEN ON LBGD.id_generik = GEN.id
            LEFT JOIN db1.masterf_jenisobat AS JOB ON KAT.id_jenisbarang = JOB.id
            LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
            LEFT JOIN (
                SELECT
                    id_katalog            AS id_katalog,
                    SUM(jumlah_stokfisik) AS jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
                GROUP BY id_katalog
            ) AS tabel_stok ON LBGD.id_katalog = tabel_stok.id_katalog
            LEFT JOIN db1.user AS U ON LBGD.userid_updt = U.id
            LEFT JOIN (
                SELECT
                    A.id_katalog,
                    A.hp_item
                FROM db1.relasif_hargaperolehan AS A
                INNER JOIN (
                    SELECT
                        id_katalog  AS id_katalog,
                        MAX(tgl_hp) AS tgl_hp
                    FROM db1.relasif_hargaperolehan
                    GROUP BY id_katalog
                ) AS B ON A.id_katalog = B.id_katalog
                WHERE A.tgl_hp = B.tgl_hp
                GROUP BY id_katalog
            ) AS tabel_harga_perolehan ON LBGD.id_katalog = tabel_harga_perolehan.id_katalog
            WHERE
                (:idKatalog = '' OR LBGD.id_katalog LIKE :idKatalog)
                AND (:jenisMoving = '' OR LBGD.jenis_moving = :jenisMoving)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (
                    :levelStok = ''
                    OR (:levelStok = 3 AND tabel_stok.jumlah_stokfisik >  LBGD.jumlah_rop)
                    OR (:levelStok = 2 AND tabel_stok.jumlah_stokfisik <= LBGD.jumlah_rop)
                    OR (:levelStok = 1 AND tabel_stok.jumlah_stokfisik <= LBGD.jumlah_rop AND tabel_stok.jumlah_stokfisik >= LBGD.jumlah_buffer)
                    OR (:levelStok = 0 AND tabel_stok.jumlah_stokfisik <  LBGD.jumlah_buffer)
                )
        ";
        $params = [
            ":idKatalog" => $idKatalog ? "%$idKatalog%" : "",
            ":jenisMoving" => $jenisMoving ?? "",
            ":namaSediaan" => $namaSediaan ? "%$namaSediaan%" : "",
            ":levelStok" => $levelStok ?? "",
        ];
        $daftarLaporanBufferGudang = $connection->createCommand($sql, $params)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost")
            ->setCategory("Approved by Fatmahost");

        $object->setActiveSheetIndex()
            ->setCellValue("B1", "LAPORAN BUFFER FARMASI")
            ->setCellValue("B2", "Tanggal Cetak")
            ->setCellValue("C2", $nowValUser);

        $object->setActiveSheetIndex()
            ->setCellValue("A4", "NO")
            ->setCellValue("B4", "KODE")
            ->setCellValue("C4", "NAMA BARANG")
            ->setCellValue("D4", "NAMA GENERIK")
            ->setCellValue("E4", "MOVING")
            ->setCellValue("F4", "PERSEN BUFFER")
            ->setCellValue("G4", "PERSEN LEADTIME")
            ->setCellValue("H4", "JUMLAH BUFFER")
            ->setCellValue("I4", "JUMLAH LEADTIME")
            ->setCellValue("J4", "JUMLAH ROP")
            ->setCellValue("K4", "JUMLAH OPTIMUM")
            ->setCellValue("L4", "STOK")
            ->setCellValue("M4", "HP ITEM")
            ->setCellValue("N4", "ESTIMASI HABIS")
            ->setCellValue("O4", "ESTIMASI PERENCANAAN")
            ->setCellValue("P4", "JENIS BARANG")
            ->setCellValue("Q4", "KELOMPOK BARANG")
            ->setCellValue("R4", "LAST UPDATE")
            ->setCellValue("S4", "USER UPDATE");

        $no = 5;
        $noObat = 1;

        foreach ($daftarLaporanBufferGudang as $key => $row) {
            $persentaseStok = $row->jumlahOptimum ? ($row->jumlahStokFisik / $row->jumlahOptimum) : 0;

            $jumlahHari = $persentaseStok * 30;
            $sisaBulan = floor($jumlahHari / 30);
            $sisaHari = $jumlahHari % 30;

            $sisa = "";
            $sisa .= $sisaBulan ? "$sisaBulan bulan " : "";
            $sisa .= $sisaHari ? "$sisaHari hari" : "";

            $estimasiPerencanaan = $row->estimasiPerencanaan ? $row->estimasiPerencanaan : 0;

            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $noObat++)
                ->setCellValueExplicit("B" . $no, $row->idKatalog, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("C" . $no, $row->namaSediaan)
                ->setCellValue("D" . $no, $row->namaGenerik)
                ->setCellValue("E" . $no, $row->jenisMoving)
                ->setCellValue("F" . $no, $row->persenBuffer)
                ->setCellValue("G" . $no, $row->persenLeadtime)
                ->setCellValue("H" . $no, $row->jumlahBuffer)
                ->setCellValue("I" . $no, $row->jumlahLeadtime)
                ->setCellValue("J" . $no, $row->jumlahRop)
                ->setCellValue("K" . $no, $row->jumlahOptimum)
                ->setCellValue("L" . $no, $row->jumlahStokFisik)
                ->setCellValue("M" . $no, $row->hpItem)
                ->setCellValue("N" . $no, $sisa)
                ->setCellValue("O" . $no, $estimasiPerencanaan)
                ->setCellValue("P" . $no, $row->jenisObat)
                ->setCellValue("Q" . $no, $row->kelompokBarang)
                ->setCellValue("R" . $no, $toUserDatetime($row->sysdateUpdate))
                ->setCellValue("S" . $no, $row->namaUserUbah);

            $no++;
        }

        $object->getActiveSheet()->setTitle("Buffer Farmasi");
        $object->setActiveSheetIndex();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="buffer_farmasi.xlsx"');
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($object, "Excel2007");
        $objWriter->save("php://output");
    }
}
