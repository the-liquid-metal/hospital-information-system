<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\TimeOutOfRangeException;
use tlm\his\FatmaPharmacy\views\Mutasi\{
    MutasiJenisKelompok,
    MutasiKatalogJenisKelompok,
    MutasiKatalogKelompok,
    MutasiKelompok,
    MutasiTriwulanKatalogDetailJenisKelompok,
    MutasiTriwulanKatalogDetailKelompok,
};
use tlm\libs\LowEnd\components\{DateTimeException, DateTimeRangeException};
use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class MutasiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert        the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertkatalog the original method
     */
    private function insertKatalog(int $bulan, int $tahun): void
    {
        if ($tahun < 2015) {
            throw new TimeOutOfRangeException;
        } elseif ($tahun == 2015 && $bulan < 5) {
            throw new TimeOutOfRangeException;
        }

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT TRUE
            FROM db1.laporan_mutasi_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            LIMIT 1
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $cekLaporanMutasi = $connection->createCommand($sql, $params)->queryScalar();
        if ($cekLaporanMutasi) return;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulanBerikutnya = sprintf("%02d", $bulanBerikutnya);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.laporan_mutasi_bulan(
                id_katalog,         -- 1
                nama_barang,        -- 2
                id_jenisbarang,     -- 3
                kode_jenis,         -- 4
                nama_jenis,         -- 5
                id_kelompokbarang,  -- 6
                kode_kelompok,      -- 7
                nama_kelompok,      -- 8
                tgl_create_katalog, -- 9
                bulan,              -- 10
                tahun               -- 11
            )
            SELECT
                A.kode              AS id_katalog,         -- 1
                A.nama_sediaan      AS nama_barang,        -- 2
                A.id_jenisbarang    AS id_jenisbarang,     -- 3
                B.kode              AS kode_jenis,         -- 4
                B.jenis_obat        AS nama_jenis,         -- 5
                A.id_kelompokbarang AS id_kelompokbarang,  -- 6
                C.kode              AS kode_kelompok,      -- 7
                C.kelompok_barang   AS nama_kelompok,      -- 8
                A.sysdate_in        AS tgl_create_katalog, -- 9
                :bulan              AS bulan,              -- 10
                :tahun              AS tahun               -- 11
            FROM db1.masterf_katalog AS A
            LEFT JOIN db1.masterf_jenisobat AS B ON A.id_jenisbarang = B.id
            LEFT JOIN db1.masterf_kelompokbarang AS C ON A.id_kelompokbarang = C.id
            WHERE A.sysdate_in < :sysdateInput
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun, ":sysdateInput" => "$tahunBerikutnya-$bulanBerikutnya-01"];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert          the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertsaldoawal the original method
     */
    public function actionInsertSaldoAwal(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 1) {
            $bulanSebelumnya = 12;
            $tahunSebelumnya = $tahun - 1;
        } else {
            $bulanSebelumnya = $bulan - 1;
            $tahunSebelumnya = $tahun;
        }

        if ($tahun == 2015 && $bulan == 5) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.laporan_mutasi_bulan AS A
                INNER JOIN db1.laporan_saldo_akhir_april_medisys AS B ON A.id_katalog = B.id_katalog
                SET A.jumlah_awal = B.kuantitas,
                    A.harga_awal = B.harga,
                    A.nilai_awal = B.jumlah
                WHERE
                    A.bulan = :bulan
                    AND A.tahun = :tahun
            ";
            $params = [":bulan" => $bulan, ":tahun" => $tahun];

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.laporan_mutasi_bulan AS A
                INNER JOIN (
                    SELECT *
                    FROM db1.laporan_mutasi_bulan
                    WHERE bulan = :bulanSebelumnya
                    AND tahun = :tahunSebelumnya
                ) AS B ON A.id_katalog = B.id_katalog
                SET
                    A.jumlah_awal = B.jumlah_akhir,
                    A.harga_awal = B.harga_akhir,
                    A.nilai_awal = B.nilai_akhir
                WHERE
                    A.bulan = :bulan
                    AND A.tahun = $tahun
            ";
            $params = [
                ":bulan" => $bulan,
                ":tahun" => $tahun,
                ":bulanSebelumnya" => $bulanSebelumnya,
                ":tahunSebelumnya" => $tahunSebelumnya,
            ];
        }
        $connection->createCommand($sql, $params)->execute();

        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_awal = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert          the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertpembelian the original method
     */
    public function actionInsertPembelian(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);

        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah,
                    SUM(C.nilai)  AS nilai
                FROM (
                    SELECT
                        id_katalog                                            AS id_katalog,
                        SUM(jumlah_masuk - jumlah_keluar)                     AS jumlah,
                        SUM((jumlah_masuk - jumlah_keluar) * harga_perolehan) AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE kode_transaksi = 'T'
                        AND tipe_tersedia = 'penerimaan'
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                        AND status = 1
                    GROUP BY
                        kode_reff,
                        id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET A.jumlah_pembelian = B.jumlah,
                A.nilai_pembelian = B.nilai,
                A.tgl_updt_pembelian = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_pembelian = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert              the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#inserthasilproduksi the original method
     */
    public function actionInsertHasilProduksi(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah,
                    SUM(C.nilai)  AS nilai
                FROM (
                    SELECT
                        id_katalog                                            AS id_katalog,
                        SUM(jumlah_masuk - jumlah_keluar)                     AS jumlah,
                        SUM((jumlah_masuk - jumlah_keluar) * harga_perolehan) AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE tipe_tersedia = 'produksi1'
                        AND status = 1
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                    GROUP BY kode_reff, id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_hasilproduksi = B.jumlah,
                A.nilai_hasilproduksi = B.nilai,
                A.tgl_updt_hasilproduksi = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_hasilproduksi = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert        the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertkoreksi the original method
     */
    public function actionInsertKoreksi(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah,
                    SUM(C.nilai)  AS nilai
                FROM (
                    SELECT
                        id_katalog                                            AS id_katalog,
                        SUM(jumlah_masuk - jumlah_keluar)                     AS jumlah,
                        SUM((jumlah_masuk - jumlah_keluar) * harga_perolehan) AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE
                        tipe_tersedia = 'koreksiopname'
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                        AND status = 1
                    GROUP BY kode_reff, id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_koreksi = B.jumlah,
                A.nilai_koreksi = B.nilai,
                A.tgl_updt_koreksi = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_koreksi = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert          the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertpenjualan the original method
     */
    public function actionInsertPenjualan(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah
                    -- SUM(C.nilai) nilai
                FROM (
                    SELECT
                        id_katalog                        AS id_katalog,
                        SUM(jumlah_keluar - jumlah_masuk) AS jumlah
                        -- SUM(jumlah_keluar - jumlah_masuk) * harga_perolehan AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE
                        tipe_tersedia = 'penjualan'
                        AND kode_transaksi = 'R'
                        AND status = 1
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                    GROUP BY
                        kode_reff,
                        id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_penjualan = B.jumlah,
                -- A.nilai_penjualan = B.nilai,
                A.tgl_updt_penjualan = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_penjualan = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert          the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertfloorstok the original method
     */
    public function actionInsertFloorStok(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah
                    -- SUM(C.nilai) nilai
                FROM (
                    SELECT
                        id_katalog                        AS id_katalog,
                        SUM(jumlah_keluar - jumlah_masuk) AS jumlah
                        -- SUM(jumlah_keluar - jumlah_masuk) * harga_perolehan AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE
                        tipe_tersedia = 'pengiriman'
                        AND kode_transaksi = 'D'
                        AND keterangan NOT LIKE '%depo%'
                        AND keterangan NOT LIKE '%gudang%'
                        AND keterangan NOT LIKE '%apotik%'
                        AND status = 1
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                    GROUP BY
                        kode_reff,
                        id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_floorstok = B.jumlah,
                -- A.nilai_floorstok = B.nilai,
                A.tgl_updt_floorstok = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_floorstok = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert              the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertbahanproduksi the original method
     */
    public function actionInsertBahanProduksi(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah
                    -- SUM(C.nilai) nilai
                FROM (
                    SELECT
                        id_katalog         AS id_katalog,
                        SUM(jumlah_keluar) AS jumlah
                        -- SUM(jumlah_keluar - jumlah_masuk) * harga_perolehan AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE tipe_tersedia = 'produksi2'
                        AND status = 1
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                    GROUP BY kode_reff, id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_bahanproduksi = B.jumlah,
                -- A.nilai_bahanproduksi = B.nilai,
                A.tgl_updt_bahanproduksi = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_bahanproduksi = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert      the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertrusak the original method
     */
    public function actionInsertRusak(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        $bulanBerikutnya = ($bulan == 12) ? 1 : $bulan + 1;

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah,
                    SUM(C.nilai)  AS nilai
                FROM (
                    SELECT
                        id_katalog                           AS id_katalog,
                        SUM(jumlah_keluar)                   AS jumlah,
                        SUM(jumlah_keluar) * harga_perolehan AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE
                        tipe_tersedia = 'rusak'
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                        AND status = 1
                    GROUP BY kode_reff, id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_rusak = B.jumlah,
                A.nilai_rusak = B.nilai,
                A.tgl_updt_rusak = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahun-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_rusak = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert        the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertexpired the original method
     */
    public function actionInsertExpired(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah,
                    SUM(C.nilai)  AS nilai
                FROM (
                    SELECT
                        id_katalog                           AS id_katalog,
                        SUM(jumlah_keluar)                   AS jumlah,
                        SUM(jumlah_keluar) * harga_perolehan AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE
                        tipe_tersedia = 'expired'
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                        AND status = 1
                    GROUP BY kode_reff, id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET A.jumlah_expired = B.jumlah,
                A.nilai_expired = B.nilai,
                A.tgl_updt_expired = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_expired = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert               the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertreturpembelian the original method
     */
    public function actionInsertReturPembelian(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah
                    -- SUM(C.nilai) nilai
                FROM (
                    SELECT
                        id_katalog                        AS id_katalog,
                        SUM(jumlah_keluar - jumlah_masuk) AS jumlah
                        -- SUM(jumlah_keluar - jumlah_masuk) * harga_perolehan AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE
                        kode_transaksi = 'RT'
                        AND tipe_tersedia = 'penerimaan'
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                        AND status = 1
                        AND jumlah_masuk = 0
                    GROUP BY kode_reff, id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_returpembelian = B.jumlah,
                -- A.nilai_returpembelian = B.nilai,
                A.tgl_updt_returpembelian = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_returpembelian = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert                  the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertkoreksipenerimaan the original method
     */
    public function actionInsertKoreksiPenerimaan(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah,
                    SUM(C.nilai)  AS nilai
                FROM (
                    SELECT
                        id_katalog                                            AS id_katalog,
                        SUM(jumlah_masuk - jumlah_keluar)                     AS jumlah,
                        SUM((jumlah_masuk - jumlah_keluar) * harga_perolehan) AS nilai
                    FROM db1.relasif_ketersediaan
                    WHERE
                        kode_transaksi = 'K'
                        AND tipe_tersedia = 'penerimaan'
                        AND tgl_tersedia >= :tanggalAwalTersedia
                        AND tgl_tersedia < :tanggalAkhirTersedia
                        AND status = 1
                    GROUP BY
                        kode_reff,
                        id_katalog
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_koreksipenerimaan = B.jumlah,
                A.nilai_koreksipenerimaan = B.nilai,
                A.tgl_updt_koreksipenerimaan = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalTersedia" => "$tahun-$bulan2-01",
            ":tanggalAkhirTersedia" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_koreksipenerimaan = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws TimeOutOfRangeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insert               the original method
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#inserttidakterlayani the original method
     */
    public function actionInsertTidakTerlayani(): void
    {
        ["bulan" => $bulan, "tahun" => $tahun] = Yii::$app->request->post();
        $bulan ??= Yii::$app->request->get("bulan");
        $tahun ??= Yii::$app->request->get("tahun");

        $this->insertKatalog($bulan, $tahun);
        $connection = Yii::$app->dbFatma;

        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan AS A
            INNER JOIN (
                SELECT
                    C.id_katalog  AS id_katalog,
                    SUM(C.jumlah) AS jumlah
                FROM (
                    SELECT
                        kodeObatdr   AS id_katalog,
                        jlhPenjualan AS jumlah
                    FROM
                        db1.masterf_penjualan
                    WHERE
                        kodeObat <> kodeObatdr
                        AND kodeObatdr <> ''
                        AND tglPenjualan >= :tanggalAwalPenjualan
                        AND tglPenjualan < :tanggalAkhirPenjualan
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_tidakterlayani = B.jumlah,
                tgl_updt_tidakterlayani = :tanggalUbah
            WHERE
                A.bulan = :bulan
                AND A.tahun = :tahun
        ";
        $params = [
            ":bulan" =>$bulan,
            ":tahun" =>$tahun,
            ":tanggalUbah" => $nowValSystem,
            ":tanggalAwalPenjualan" => "$tahun-$bulan2-01",
            ":tanggalAkhirPenjualan" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_mutasi_bulan
            SET tgl_updt_tidakterlayani = :tanggalUbah
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":bulan" =>$bulan, ":tahun" =>$tahun];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#ringkasan    the original method
     */
    public function actionRingkasanData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                tahun                      AS tahun,
                bulan                      AS bulan,
                tgl_updt_awal              AS tanggalUbahAwal,
                tgl_updt_pembelian         AS tanggalUbahPembelian,
                tgl_updt_hasilproduksi     AS tanggalUbahHasilProduksi,
                tgl_updt_koreksi           AS tanggalUbahKoreksi,
                tgl_updt_penjualan         AS tanggalUbahPenjualan,
                tgl_updt_floorstok         AS tanggalUbahFloorstok,
                tgl_updt_bahanproduksi     AS tanggalUbahBahanProduksi,
                tgl_updt_rusak             AS tanggalUbahRusak,
                tgl_updt_expired           AS tanggalUbahKadaluarsa,
                tgl_updt_returpembelian    AS tanggalUbahReturPembelian,
                tgl_updt_tidakterlayani    AS tanggalUbahTakTerlayani,
                tgl_updt_koreksipenerimaan AS tanggalUbahKoreksiPenerimaan,
                tgl_updt_akhir             AS tanggalUbahAkhir
            FROM db1.laporan_mutasi_bulan
            GROUP BY tahun, bulan
        ";
        $daftarLaporanMutasi = $connection->createCommand($sql)->queryAll();

        return json_encode($daftarLaporanMutasi);
    }

    /**
     * Original, do not delete
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    public function actionLaporanMutasiTriwulanDev(): string
    {
        $bulanSekarang = date("n");
        $triwulanSekarang = ceil($bulanSekarang / 3);
        $tahunSekarang = date("Y");
        $tahunAwal = 2015;

        $daftarTahun = [];
        for ($y = $tahunAwal; $y <= $tahunSekarang; $y++) {
            $daftarTahun[] = $y;
        }

        return $this->renderPartial("form-mutasi-triwulan", [
            "triwulanSekarang" => $triwulanSekarang,
            "tahunSekarang" => $tahunSekarang,
            "daftarTahun" => $daftarTahun,
            "triwulan" => Yii::$app->dateTime->quarterStr,
            "dataUrl" => Yii::$app->actionToId([self::class, "actionLaporanMutasiTriwulanDevData"]), // new!
        ]);
    }

    /**
     * Original, do not delete
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    public function actionLaporanMutasiTriwulanDevData(): string
    {
        switch (Yii::$app->request->post("jenis_laporan")) {
            case 1: return $this->laporanMutasiTriwulanDevData1();
            case 2: return $this->laporanMutasiTriwulanDevData2();
            case 3: return $this->laporanMutasiTriwulanDevData3();
            case 4: return $this->laporanMutasiTriwulanDevData4();
            case 5: return $this->laporanMutasiTriwulanDevData5();
            case 6: return $this->laporanMutasiTriwulanDevData6();
            default:  return $this->laporanMutasiTriwulanDevDataElse();
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    private function laporanMutasiTriwulanDevData1(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog            AS idKatalog,
                nama_barang           AS namaBarang,
                id_jenisbarang        AS idJenisBarang,        -- in use
                kode_jenis            AS kodeJenis,
                nama_jenis            AS namaJenis,            -- in use
                id_kelompokbarang     AS idKelompokBarang,     -- in use
                kode_kelompok         AS kodeKelompok,
                nama_kelompok         AS namaKelompok,         -- in use
                jumlah_awal           AS jumlahAwal,
                harga_awal            AS hargaAwal,
                nilai_awal            AS nilaiAwal,            -- in use
                jumlah_pembelian      AS jumlahPembelian,
                nilai_pembelian       AS nilaiPembelian,       -- in use
                jumlah_hasilproduksi  AS jumlahHasilProduksi,
                nilai_hasilproduksi   AS nilaiHasilProduksi,   -- in use
                jumlah_koreksi        AS jumlahKoreksi,
                nilai_koreksi         AS nilaiKoreksi,         -- in use
                jumlah_penjualan      AS jumlahPenjualan,
                nilai_penjualan       AS nilaiPenjualan,       -- in use
                jumlah_floorstok      AS jumlahFloorstok,
                nilai_floorstok       AS nilaiFloorstok,       -- in use
                jumlah_bahanproduksi  AS jumlahBahanProduksi,
                nilai_bahanproduksi   AS nilaiBahanProduksi,   -- in use
                jumlah_rusak          AS jumlahRusak,
                nilai_rusak           AS nilaiRusak,           -- in use
                jumlah_expired        AS jumlahKadaluarsa,
                nilai_expired         AS nilaiKadaluarsa,      -- in use
                jumlah_returpembelian AS jumlahReturPembelian,
                nilai_returpembelian  AS nilaiReturPembelian,  -- in use
                jumlah_adjustment     AS jumlahAdjustment,
                nilai_adjustment      AS nilaiAdjustment,      -- in use
                jumlah_akhir          AS jumlahAkhir,
                harga_akhir           AS hargaAkhir,
                nilai_akhir           AS nilaiAkhir            -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorstock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 36;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            if ($idJenisSaatIni != $lMutasi->idJenisBarang) {
                $idJenisSaatIni = $lMutasi->idJenisBarang;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $lMutasi->idKelompokBarang) {
                $idKelompokSaatIni = $lMutasi->idKelompokBarang;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorstock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new mutasiTriwulanKatalogDetailJenisKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorstock:     $grandTotalFloorstock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    private function laporanMutasiTriwulanDevData2(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog            AS idKatalog,
                nama_barang           AS namaBarang,
                id_kelompokbarang     AS idKelompokBarang,    -- in use
                kode_kelompok         AS kodeKelompok,
                nama_kelompok         AS namaKelompok,        -- in use
                jumlah_awal           AS jumlahAwal,
                harga_awal            AS hargaAwal,
                nilai_awal            AS nilaiAwal,           -- in use
                jumlah_pembelian      AS jumlahPembelian,
                nilai_pembelian       AS nilaiPembelian,      -- in use
                jumlah_hasilproduksi  AS jumlahHasilProduksi,
                nilai_hasilproduksi   AS nilaiHasilProduksi,  -- in use
                jumlah_koreksi        AS jumlahKoreksi,
                nilai_koreksi         AS nilaiKoreksi,        -- in use
                jumlah_penjualan      AS jumlahPenjualan,
                nilai_penjualan       AS nilaiPenjualan,      -- in use
                jumlah_floorstok      AS jumlahFloorstok,
                nilai_floorstok       AS nilaiFloorstok,      -- in use
                jumlah_bahanproduksi  AS jumlahBahanProduksi,
                nilai_bahanproduksi   AS nilaiBahanProduksi,  -- in use
                jumlah_rusak          AS jumlahRusak,
                nilai_rusak           AS nilaiRusak,          -- in use
                jumlah_expired        AS jumlahKadaluarsa,
                nilai_expired         AS nilaiKadaluarsa,     -- in use
                jumlah_returpembelian AS jumlahReturPembelian,
                nilai_returpembelian  AS nilaiReturPembelian, -- in use
                jumlah_adjustment     AS jumlahAdjustment,
                nilai_adjustment      AS nilaiAdjustment,     -- in use
                jumlah_akhir          AS jumlahAkhir,
                harga_akhir           AS hargaAkhir,
                nilai_akhir           AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $barisPerHalaman = 36;
        $noJudul = 1;
        $noData = 1;
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            if ($idKelompokSaatIni != $lMutasi->idKelompokBarang) {
                $idKelompokSaatIni = $lMutasi->idKelompokBarang;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul][$bJudul]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul][$bJudul]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul][$bJudul]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul][$bJudul]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul][$bJudul]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul][$bJudul]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul][$bJudul]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul][$bJudul]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul][$bJudul]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul][$bJudul]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul][$bJudul]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new MutasiTriwulanKatalogDetailKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    private function laporanMutasiTriwulanDevData3(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog           AS idKatalog,
                nama_barang          AS namaBarang,
                id_jenisbarang       AS idJenisBarang,       -- in use
                kode_jenis           AS kodeJenis,
                nama_jenis           AS namaJenis,           -- in use
                id_kelompokbarang    AS idKelompokBarang,    -- in use
                kode_kelompok        AS kodeKelompok,
                nama_kelompok        AS namaKelompok,        -- in use
                jumlah_awal          AS jumlahAwal,
                harga_awal           AS hargaAwal,
                nilai_awal           AS nilaiAwal,           -- in use
                nilai_pembelian      AS nilaiPembelian,      -- in use
                nilai_hasilproduksi  AS nilaiHasilProduksi,  -- in use
                nilai_koreksi        AS nilaiKoreksi,        -- in use
                nilai_penjualan      AS nilaiPenjualan,      -- in use
                nilai_floorstok      AS nilaiFloorstok,      -- in use
                nilai_bahanproduksi  AS nilaiBahanProduksi,  -- in use
                nilai_rusak          AS nilaiRusak,          -- in use
                nilai_expired        AS nilaiKadaluarsa,     -- in use
                nilai_returpembelian AS nilaiReturPembelian, -- in use
                nilai_adjustment     AS nilaiAdjustment,     -- in use
                jumlah_akhir         AS jumlahAkhir,
                harga_akhir          AS hargaAkhir,
                nilai_akhir          AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorstock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 40;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            if ($idJenisSaatIni != $lMutasi->idJenisBarang) {
                $idJenisSaatIni = $lMutasi->idJenisBarang;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $lMutasi->idKelompokBarang) {
                $idKelompokSaatIni = $lMutasi->idKelompokBarang;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
                // TODO: php: uncategorized: fix these (missing value)
                "jumlah_pembelian" => 0,
                "jumlah_hasilproduksi" => 0,
                "jumlah_koreksi" => 0,
                "jumlah_penjualan" => 0,
                "jumlah_floorstok" => 0,
                "jumlah_bahanproduksi" => 0,
                "jumlah_rusak" => 0,
                "jumlah_expired" => 0,
                "jumlah_returpembelian" => 0,
                "jumlah_adjustment" => 0,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorstock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new MutasiKatalogJenisKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorstock:     $grandTotalFloorstock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    private function laporanMutasiTriwulanDevData4(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog           AS idKatalog,
                nama_barang          AS namaBarang,
                id_jenisbarang       AS idJenisBarang,       -- in use
                kode_jenis           AS kodeJenis,
                nama_jenis           AS namaJenis,           -- in use
                id_kelompokbarang    AS idKelompokBarang,    -- in use
                kode_kelompok        AS kodeKelompok,
                nama_kelompok        AS namaKelompok,        -- in use
                jumlah_awal          AS jumlahAwal,
                harga_awal           AS hargaAwal,
                nilai_awal           AS nilaiAwal,           -- in use
                nilai_pembelian      AS nilaiPembelian,      -- in use
                nilai_hasilproduksi  AS nilaiHasilProduksi,  -- in use
                nilai_koreksi        AS nilaiKoreksi,        -- in use
                nilai_penjualan      AS nilaiPenjualan,      -- in use
                nilai_floorstok      AS nilaiFloorstok,      -- in use
                nilai_bahanproduksi  AS nilaiBahanProduksi,  -- in use
                nilai_rusak          AS nilaiRusak,          -- in use
                nilai_expired        AS nilaiKadaluarsa,     -- in use
                nilai_returpembelian AS nilaiReturPembelian, -- in use
                nilai_adjustment     AS nilaiAdjustment,     -- in use
                jumlah_akhir         AS jumlahAkhir,
                harga_akhir          AS hargaAkhir,
                nilai_akhir          AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorstock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 40;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            if ($idJenisSaatIni != $lMutasi->idJenisBarang) {
                $idJenisSaatIni = $lMutasi->idJenisBarang;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $lMutasi->idKelompokBarang) {
                $idKelompokSaatIni = $lMutasi->idKelompokBarang;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
                // TODO: php: uncategorized: fix these (missing value)
                "jumlah_pembelian" => 0,
                "jumlah_hasilproduksi" => 0,
                "jumlah_koreksi" => 0,
                "jumlah_penjualan" => 0,
                "jumlah_floorstok" => 0,
                "jumlah_bahanproduksi" => 0,
                "jumlah_rusak" => 0,
                "jumlah_expired" => 0,
                "jumlah_returpembelian" => 0,
                "jumlah_adjustment" => 0,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahan_produksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorstock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new MutasiKatalogKelompok(
            bulan:                    null,
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorstock:     $grandTotalFloorstock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    private function laporanMutasiTriwulanDevData5(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_jenisbarang            AS idJenisBarang,       -- in use
                kode_jenis                AS kodeJenis,
                nama_jenis                AS namaJenis,           -- in use
                id_kelompokbarang         AS idKelompokBarang,    -- in use
                kode_kelompok             AS kodeKelompok,
                nama_kelompok             AS namaKelompok,        -- in use
                SUM(nilai_awal)           AS nilaiAwal,           -- in use
                SUM(nilai_pembelian)      AS nilaiPembelian,      -- in use
                SUM(nilai_hasilproduksi)  AS nilaiHasilProduksi,  -- in use
                SUM(nilai_koreksi)        AS nilaiKoreksi,        -- in use
                SUM(nilai_penjualan)      AS nilaiPenjualan,      -- in use
                SUM(nilai_floorstok)      AS nilaiFloorstok,      -- in use
                SUM(nilai_bahanproduksi)  AS nilaiBahanProduksi,  -- in use
                SUM(nilai_rusak)          AS nilaiRusak,          -- in use
                SUM(nilai_expired)        AS nilaiKadaluarsa,     -- in use
                SUM(nilai_returpembelian) AS nilaiReturPembelian, -- in use
                SUM(nilai_adjustment)     AS nilaiAdjustment,     -- in use
                SUM(nilai_akhir)          AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            GROUP BY
                id_jenisbarang,
                id_kelompokbarang
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorStock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $barisPerHalaman = 40;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $idJenisSaatIni = "";
        $idKelompokSaatIni = "";

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            if ($idJenisSaatIni != $lMutasi->idJenisBarang) {
                $idJenisSaatIni = $lMutasi->idJenisBarang;
                $idKelompokSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis_barang" => $lMutasi->namaJenis,
                    "total_saldo_awal" => 0,
                    "total_pembelian" => 0,
                    "total_hasil_produksi" => 0,
                    "total_koreksi" => 0,
                    "total_penjualan" => 0,
                    "total_floor_stock" => 0,
                    "total_bahan_produksi" => 0,
                    "total_rusak" => 0,
                    "total_expired" => 0,
                    "total_retur_pembelian" => 0,
                    "total_adjustment" => 0,
                    "total_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($idKelompokSaatIni != $lMutasi->idKelompokBarang) {
                $idKelompokSaatIni = $lMutasi->idKelompokBarang;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok_barang" => $lMutasi->namaKelompok,
                    "subtotal_saldo_awal" => 0,
                    "subtotal_pembelian" => 0,
                    "subtotal_hasil_produksi" => 0,
                    "subtotal_koreksi" => 0,
                    "subtotal_penjualan" => 0,
                    "subtotal_floor_stock" => 0,
                    "subtotal_bahan_produksi" => 0,
                    "subtotal_rusak" => 0,
                    "subtotal_expired" => 0,
                    "subtotal_retur_pembelian" => 0,
                    "subtotal_adjustment" => 0,
                    "subtotal_saldo_akhir" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
                // TODO: php: uncategorized: fix these (missing value)
                "id_katalog" => 0,
                "nama_barang" => 0,
                "jumlah_awal" => 0,
                "harga_awal" => 0,
                "jumlah_pembelian" => 0,
                "jumlah_hasilproduksi" => 0,
                "jumlah_koreksi" => 0,
                "jumlah_penjualan" => 0,
                "jumlah_floorstok" => 0,
                "jumlah_bahanproduksi" => 0,
                "jumlah_rusak" => 0,
                "jumlah_expired" => 0,
                "jumlah_returpembelian" => 0,
                "jumlah_adjustment" => 0,
                "jumlah_akhir" => 0,
                "harga_akhir" => 0,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_bahanproduksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_awal"] += $lMutasi->nilaiAwal;
            $daftarHalaman[$hJudul1][$bJudul1]["total_pembelian"] += $lMutasi->nilaiPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_hasil_produksi"] += $lMutasi->nilaiHasilProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_koreksi"] += $lMutasi->nilaiKoreksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_penjualan"] += $lMutasi->nilaiPenjualan;
            $daftarHalaman[$hJudul1][$bJudul1]["total_floor_stock"] += $lMutasi->nilaiFloorstok;
            $daftarHalaman[$hJudul1][$bJudul1]["total_bahanproduksi"] += $lMutasi->nilaiBahanProduksi;
            $daftarHalaman[$hJudul1][$bJudul1]["total_rusak"] += $lMutasi->nilaiRusak;
            $daftarHalaman[$hJudul1][$bJudul1]["total_expired"] += $lMutasi->nilaiKadaluarsa;
            $daftarHalaman[$hJudul1][$bJudul1]["total_retur_pembelian"] += $lMutasi->nilaiReturPembelian;
            $daftarHalaman[$hJudul1][$bJudul1]["total_adjustment"] += $lMutasi->nilaiAdjustment;
            $daftarHalaman[$hJudul1][$bJudul1]["total_saldo_akhir"] += $lMutasi->nilaiAkhir;

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorStock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new MutasiJenisKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorStock:     $grandTotalFloorStock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    private function laporanMutasiTriwulanDevData6(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_kelompokbarang         AS idKelompokBarang,
                kode_kelompok             AS kodeKelompok,
                nama_kelompok             AS namaKelompok,
                SUM(nilai_awal)           AS nilaiAwal,           -- in use
                SUM(nilai_pembelian)      AS nilaiPembelian,      -- in use
                SUM(nilai_hasilproduksi)  AS nilaiHasilProduksi,  -- in use
                SUM(nilai_koreksi)        AS nilaiKoreksi,        -- in use
                SUM(nilai_penjualan)      AS nilaiPenjualan,      -- in use
                SUM(nilai_floorstok)      AS nilaiFloorstok,      -- in use
                SUM(nilai_bahanproduksi)  AS nilaiBahanProduksi,  -- in use
                SUM(nilai_rusak)          AS nilaiRusak,          -- in use
                SUM(nilai_expired)        AS nilaiKadaluarsa,     -- in use
                SUM(nilai_returpembelian) AS nilaiReturPembelian, -- in use
                SUM(nilai_adjustment)     AS nilaiAdjustment,     -- in use
                SUM(nilai_akhir)          AS nilaiAkhir           -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            GROUP BY id_kelompokbarang
            ORDER BY id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorstock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;
        $no = 1; // no. baris (terus bersambung)

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $no++,
            ];

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorstock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new MutasiKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorstock:     $grandTotalFloorstock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws DateTimeRangeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#laporanmutasitriwulandev    the original method
     */
    private function laporanMutasiTriwulanDevDataElse(): string
    {
        ["triwulan" => $triwulan, "tahun" => $tahun] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog            AS idKatalog,
                nama_barang           AS namaBarang,
                id_jenisbarang        AS idJenisBarang,
                kode_jenis            AS kodeJenis,
                nama_jenis            AS namaJenis,
                id_kelompokbarang     AS idKelompokBarang,
                kode_kelompok         AS kodeKelompok,
                nama_kelompok         AS namaKelompok,
                jumlah_awal           AS jumlahAwal,
                harga_awal            AS hargaAwal,
                nilai_awal            AS nilaiAwal,            -- in use
                jumlah_pembelian      AS jumlahPembelian,
                nilai_pembelian       AS nilaiPembelian,       -- in use
                jumlah_hasilproduksi  AS jumlahHasilProduksi,
                nilai_hasilproduksi   AS nilaiHasilProduksi,   -- in use
                jumlah_koreksi        AS jumlahKoreksi,
                nilai_koreksi         AS nilaiKoreksi,         -- in use
                jumlah_penjualan      AS jumlahPenjualan,
                nilai_penjualan       AS nilaiPenjualan,       -- in use
                jumlah_floorstok      AS jumlahFloorstok,
                nilai_floorstok       AS nilaiFloorstok,       -- in use
                jumlah_bahanproduksi  AS jumlahBahanProduksi,
                nilai_bahanproduksi   AS nilaiBahanProduksi,   -- in use
                jumlah_rusak          AS jumlahRusak,
                nilai_rusak           AS nilaiRusak,           -- in use
                jumlah_expired        AS jumlahKadaluarsa,
                nilai_expired         AS nilaiKadaluarsa,      -- in use
                jumlah_returpembelian AS jumlahReturPembelian,
                nilai_returpembelian  AS nilaiReturPembelian,  -- in use
                jumlah_adjustment     AS jumlahAdjustment,
                nilai_adjustment      AS nilaiAdjustment,      -- in use
                jumlah_akhir          AS jumlahAkhir,
                harga_akhir           AS hargaAkhir,
                nilai_akhir           AS nilaiAkhir            -- in use
            FROM db1.laporan_mutasi_triwulan
            WHERE
                triwulan = :triwulan
                AND tahun = :tahun
            ORDER BY
                id_jenisbarang,
                id_kelompokbarang
        ";
        $params = [":triwulan" => $triwulan, ":tahun" => $tahun];
        $daftarLaporanMutasi = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalSaldoAwal = 0;
        $grandTotalPembelian = 0;
        $grandTotalHasilProduksi = 0;
        $grandTotalKoreksi = 0;
        $grandTotalPenjualan = 0;
        $grandTotalFloorstock = 0;
        $grandTotalBahanProduksi = 0;
        $grandTotalRusak = 0;
        $grandTotalExpired = 0;
        $grandTotalReturPembelian = 0;
        $grandTotalAdjustment = 0;
        $grandTotalSaldoAkhir = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;
        $no = 1; // no. baris (terus bersambung)

        foreach ($daftarLaporanMutasi as $i => $lMutasi) {
            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $no++,
            ];

            $grandTotalSaldoAwal += $lMutasi->nilaiAwal;
            $grandTotalPembelian += $lMutasi->nilaiPembelian;
            $grandTotalHasilProduksi += $lMutasi->nilaiHasilProduksi;
            $grandTotalKoreksi += $lMutasi->nilaiKoreksi;
            $grandTotalPenjualan += $lMutasi->nilaiPenjualan;
            $grandTotalFloorstock += $lMutasi->nilaiFloorstok;
            $grandTotalBahanProduksi += $lMutasi->nilaiBahanProduksi;
            $grandTotalRusak += $lMutasi->nilaiRusak;
            $grandTotalExpired += $lMutasi->nilaiKadaluarsa;
            $grandTotalReturPembelian += $lMutasi->nilaiReturPembelian;
            $grandTotalAdjustment += $lMutasi->nilaiAdjustment;
            $grandTotalSaldoAkhir += $lMutasi->nilaiAkhir;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new MutasiKelompok(
            bulan:                    null,
            triwulan:                 Yii::$app->dateTime->numToQuarterly($triwulan),
            tahun:                    $tahun,
            daftarHalaman:            $daftarHalaman,
            daftarLaporanMutasi:      $daftarLaporanMutasi,
            grandTotalSaldoAwal:      $grandTotalSaldoAwal,
            grandTotalPembelian:      $grandTotalPembelian,
            grandTotalHasilProduksi:  $grandTotalHasilProduksi,
            grandTotalKoreksi:        $grandTotalKoreksi,
            grandTotalPenjualan:      $grandTotalPenjualan,
            grandTotalFloorstock:     $grandTotalFloorstock,
            grandTotalBahanProduksi:  $grandTotalBahanProduksi,
            grandTotalRusak:          $grandTotalRusak,
            grandTotalExpired:        $grandTotalExpired,
            grandTotalReturPembelian: $grandTotalReturPembelian,
            grandTotalAdjustment:     $grandTotalAdjustment,
            grandTotalSaldoAkhir:     $grandTotalSaldoAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#insertpenjualandepo the original method
     */
    private function insertPenjualanDepo(int $bulan, int $tahun): int
    {
        if ($bulan == 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya = $tahun + 1;
        } else {
            $bulanBerikutnya = $bulan + 1;
            $tahunBerikutnya = $tahun;
        }

        $bulan2 = sprintf("%02d", $bulan);
        $bulan2next = sprintf("%02d", $bulanBerikutnya);

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT TRUE
            FROM db1.laporan_penjualan_depo_bulan
            WHERE
                bulan = :bulan
                AND tahun = :tahun
            LIMIT 1
        ";
        $params = [":bulan" => $bulan, ":tahun" => $tahun];
        $cekLaporanPenjualan = $connection->createCommand($sql, $params)->queryScalar();

        if ($cekLaporanPenjualan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                DELETE FROM db1.laporan_penjualan_depo_bulan
                WHERE
                    bulan = :bulan
                    AND tahun = :tahun
            ";
            $params = [":bulan" => $bulan, ":tahun" => $tahun];
            $connection->createCommand($sql, $params)->execute();
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.laporan_penjualan_depo_bulan(
                id_katalog,      -- 1
                id_depo,         -- 2
                bulan,           -- 3
                tahun,           -- 4
                jumlah_penjualan -- 5
            )
            SELECT
                id_katalog         AS id_katalog,      -- 1
                id_depo            AS id_depo,         -- 2
                :bulan             AS bulan,           -- 3
                :tahun             AS tahun,           -- 4
                SUM(jumlah_keluar) AS jumlah_penjualan -- 5
            FROM db1.relasif_ketersediaan
            WHERE
                tipe_tersedia = 'penjualan'
                AND kode_transaksi = 'R'
                AND status = 1
                AND tgl_tersedia >= :tanggalAwal
                AND tgl_tersedia < :tanggalAkhir
            GROUP BY id_katalog, id_depo
        ";
        $params = [
            ":bulan" => $bulan,
            ":tahun" => $tahun,
            ":tanggalAwal" => "$tahun-$bulan2-01",
            ":tanggalAkhir" => "$tahunBerikutnya-$bulan2next-01",
        ];
        $jumlahRowTambah = $connection->createCommand($sql, $params)->execute();

        $idUser = Yii::$app->userFatma->id;
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_penjualan_depo_bulan
            SET
                sysdate_in = :tanggalInput,
                userid_in = :idUser
            WHERE
                bulan = :bulan
                AND tahun = :tahun
        ";
        $params = [":tanggalInput" => $nowValSystem, ":idUser" => $idUser, ":bulan" => $bulan, ":tahun" => $tahun];
        $connection->createCommand($sql, $params)->execute();

        return $jumlahRowTambah;
    }

    /**
     * @author Hendra Gunawan
     * @throws TimeOutOfRangeException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#updatebufferfarmasi    the original method
     */
    public function actionUpdateBufferFarmasi(): void
    {
        $jumlahBulanMoving = 6; // jumlah bulan minimal untuk moving 6 bulan
        $jumlahBulanAverage = 3; // jumlah bulan minimal untuk average penjualan 3 bulan
        $leadTimeMinimum = 50; // dalam persen

        $idUser = Yii::$app->userFatma->id;
        $leadtime = Yii::$app->request->post("leadtime");

        $bulan = date("n");
        $tahun = date("Y");
        $durasi = $jumlahBulanMoving;
        $leadtime = ($leadtime > $leadTimeMinimum) ? $leadtime : $leadTimeMinimum;

        $daftarfilterWaktu = [];
        $bulanSaatIni = $bulan == 1 ? 12 : $bulan - 1;
        $tahunSaatIni = $bulan == 1 ? $tahun - 1 : $tahun;

        for ($i = 0; $i < $durasi; $i++) {
            $daftarfilterWaktu[] = [
                "bulan" => $bulanSaatIni,
                "tahun" => $tahunSaatIni
            ];
            $bulanSaatIni--;
            if ($bulanSaatIni == 0) {
                $bulanSaatIni = 12;
                $tahunSaatIni--;
            }
        }

        $connection = Yii::$app->dbFatma;

        foreach ($daftarfilterWaktu as $key => ["bulan" => $tBulan, "tahun" => $tTahun]) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.laporan_mutasi_bulan
                WHERE
                    bulan = :bulan
                    AND tahun = :tahun
                LIMIT 1
            ";
            $params = [":bulan" => $tBulan, ":tahun" => $tTahun];
            $adaLaporanMutasi = $connection->createCommand($sql, $params)->queryScalar();
            if ($adaLaporanMutasi) continue;

            $_GET = ["bulan" => $tBulan, "tahun" => $tTahun];
            $this->actionInsertPenjualan();
            $this->actionInsertFloorStok();
        }

        $daftarQueryMoving = [];
        $daftarQueryAverage = [];

        foreach ($daftarfilterWaktu as $key => ["bulan" => $rBulan, "tahun" => $rTahun]) {
            $daftarQueryMoving[] = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    id_katalog                                         AS idKatalog,
                    IF (jumlah_penjualan + jumlah_floorstok > 0, 1, 0) AS moving,
                    bulan                                              AS bulan,
                    tahun                                              AS tahun
                FROM db1.laporan_mutasi_bulan
                WHERE
                    bulan = '$rBulan'
                    AND tahun = '$rTahun'
            ";

            if ($key >= $jumlahBulanAverage) continue;

            $daftarQueryAverage[] = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    id_katalog                            AS idKatalog,
                    (jumlah_penjualan + jumlah_floorstok) AS jumlahGabungan,
                    bulan                                 AS bulan,
                    tahun                                 AS tahun
                FROM db1.laporan_mutasi_bulan
                WHERE
                    bulan = '$rBulan'
                    AND tahun = '$rTahun'
            ";
        }

        $queryMovingBuilder = implode(" UNION ", $daftarQueryMoving);
        $queryAverageBuilder = implode(" UNION ", $daftarQueryAverage);

        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        // original name: $query_update_katalog
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.laporan_buffer_gudang (
                id_katalog,
                sysdate_updt,
                userid_updt
            )
            SELECT
                C.id_katalog AS id_katalog,
                :tanggalUbah AS sysdate_updt,
                :idUser      AS userid_updt
            FROM (
                SELECT B.kode AS id_katalog
                FROM db1.laporan_buffer_gudang AS A
                RIGHT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
                WHERE A.id_katalog IS NULL
            ) AS C
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":idUser" => $idUser];
        $connection->createCommand($sql, $params)->execute();

        // original name: $query_moving
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_buffer_gudang AS A
            INNER JOIN (
                SELECT
                    C.id_katalog,
                    CASE
                        WHEN C.jumlah_moving > 4 THEN 'FM'
                        WHEN C.jumlah_moving > 2 AND C.jumlah_moving < 5 THEN 'MM'
                        WHEN C.jumlah_moving > 0 AND C.jumlah_moving < 3 THEN 'SM'
                        ELSE 'DM'
                    END AS jenis_moving
                FROM (
                    SELECT
                        D.id_katalog  AS id_katalog,
                        SUM(D.moving) AS jumlah_moving
                    FROM (
                        $queryMovingBuilder
                    ) AS D
                    GROUP BY D.id_katalog
                ) AS C
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jenis_moving = B.jenis_moving,
                A.persen_leadtime = :leadtime,
                A.persen_buffer = CASE
                    WHEN B.jenis_moving = 'FM' THEN 30
                    WHEN B.jenis_moving = 'MM' THEN 20
                    WHEN B.jenis_moving = 'SM' THEN 10
                    ELSE 0
                END
            WHERE A.status = 1
        ";
        $params = [":leadtime" => $leadtime];
        $connection->createCommand($sql, $params)->execute();

        // original name: $query_average
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_buffer_gudang AS A
            INNER JOIN (
                SELECT
                    C.id_katalog                                 AS id_katalog,
                    SUM(C.jumlah_gabungan) / :jumlahBulanAverage AS jumlah_avg
                FROM (
                    $queryAverageBuilder
                ) AS C
                GROUP BY C.id_katalog
            ) AS B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_avg = B.jumlah_avg,
                A.jumlah_buffer = B.jumlah_avg * (A.persen_buffer / 100),
                A.jumlah_leadtime = B.jumlah_avg * (A.persen_leadtime / 100),
                A.jumlah_rop = (B.jumlah_avg * A.persen_buffer / 100) + (B.jumlah_avg * A.persen_leadtime / 100)
            WHERE A.status = 1
        ";
        $params = [":jumlahBulanAverage" => $jumlahBulanAverage];
        $connection->createCommand($sql, $params)->execute();

        // original name: $query_update_generik
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_buffer_gudang AS A
            LEFT JOIN (
                SELECT
                    B.kode AS id_katalog,
                    D.id AS id_generik
                FROM db1.masterf_katalog AS B
                LEFT JOIN db1.masterf_brand AS C ON B.id_brand = C.id
                LEFT JOIN db1.masterf_generik AS D ON C.id_generik = D.id
            ) AS E ON A.id_katalog = E.id_katalog
            SET A.id_generik = E.id_generik
            WHERE TRUE
        ";
        $connection->createCommand($sql)->execute();

        // original name: $query_update_sysdate
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_buffer_gudang
            SET sysdate_updt = :tanggalUbah
            WHERE status = 1
        ";
        $params = [":tanggalUbah" => $nowValSystem];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#updatebufferdepo    the original method
     */
    public function actionUpdateBufferDepo(): void
    {
        $leadtime = Yii::$app->request->post("leadtime");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        $jumlahBulanMoving = 6; // jumlah bulan minimal untuk moving 6 bulan
        $jumlahBulanAverage = 3; // jumlah bulan minimal untuk average penjualan 3 bulan
        $leadTimeMinimum = 10; // dalam persen

        $bulan = date("n");
        $tahun = date("Y");
        $leadtime = ($leadtime > $leadTimeMinimum) ? $leadtime : $leadTimeMinimum;

        $filterWaktu = [];
        $bulanSaatIni = $bulan == 1 ? 12 : $bulan - 1;
        $tahunSaatIni = $bulan == 1 ? $tahun - 1 : $tahun;

        for ($i = 0; $i < $jumlahBulanMoving; $i ++) {
            $filterWaktu[] = [
                "bulan" => $bulanSaatIni,
                "tahun" => $tahunSaatIni
            ];
            $bulanSaatIni --;
            if ($bulanSaatIni == 0) {
                $bulanSaatIni = 12;
                $tahunSaatIni --;
            }
        }

        $connection = Yii::$app->dbFatma;

        foreach ($filterWaktu as $key => ["bulan" => $tBulan, "tahun" => $tTahun]) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.laporan_mutasi_bulan
                WHERE
                    bulan = :bulan
                    AND tahun = :tahun
                LIMIT 1
            ";
            $params = [":bulan" => $tBulan, ":tahun" => $tTahun];
            $connection->createCommand($sql, $params)->queryScalar() || $this->insertPenjualanDepo($tBulan, $tTahun);
        }

        $daftarQueryMoving = [];
        $daftarQueryAverage = [];

        foreach ($filterWaktu as $key => ["bulan" => $rBulan, "tahun" => $rTahun]) {
            $daftarQueryMoving[] = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    id_katalog                     AS idKatalog,
                    id_depo                        AS idDepo,
                    IF(jumlah_penjualan > 0, 1, 0) AS moving,
                    bulan                          AS bulan,
                    tahun                          AS tahun
                FROM db1.laporan_penjualan_depo_bulan
                WHERE
                    bulan = '$rBulan'
                    AND tahun = '$rTahun'
            ";

            if ($key >= $jumlahBulanAverage) continue;

            $daftarQueryAverage[] = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    id_katalog       AS idKatalog,
                    id_depo          AS idDepo,
                    jumlah_penjualan AS jumlahPenjualan,
                    bulan            AS bulan,
                    tahun            AS tahun
                FROM db1.laporan_penjualan_depo_bulan
                WHERE
                    bulan = '$rBulan'
                    AND tahun = '$rTahun'
            ";
        }

        $queryMovingBuilder = implode(" UNION ", $daftarQueryMoving);
        $queryAverageBuilder = implode(" UNION ", $daftarQueryAverage);

        $idUser = Yii::$app->userFatma->id;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            TRUNCATE db1.laporan_buffer_depo
        ";
        $connection->createCommand($sql)->execute();

        // original name: $query_update_katalog
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.laporan_buffer_depo(
                id_katalog,   -- 1
                id_depo,      -- 2
                sysdate_updt, -- 3
                userid_updt   -- 4
            )
            SELECT
                B.id_katalog AS id_katalog,   -- 1
                B.id_depo    AS id_depo,      -- 2
                :tanggalUbah AS sysdate_updt, -- 3
                :idUser      AS userid_updt   -- 4
            FROM (
                SELECT
                    id_katalog,
                    id_depo
                FROM db1.laporan_penjualan_depo_bulan
                GROUP BY id_katalog, id_depo
            ) B
        ";
        $params = [":tanggalUbah" => $nowValSystem, ":idUser" => $idUser];
        $connection->createCommand($sql, $params)->execute();

        // original name: $query_moving
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_buffer_depo A
            INNER JOIN (
                SELECT
                    C.id_katalog,
                    C.id_depo,
                    CASE
                        WHEN C.jumlah_moving > 4 THEN 'FM'
                        WHEN C.jumlah_moving > 2 AND C.jumlah_moving < 5 THEN 'MM'
                        WHEN C.jumlah_moving > 0 AND C.jumlah_moving < 3 THEN 'SM'
                        ELSE 'DM'
                    END jenis_moving
                FROM (
                    SELECT
                        D.id_katalog  AS id_katalog,
                        D.id_depo     AS id_depo,
                        SUM(D.moving) AS jumlah_moving
                    FROM (
                        $queryMovingBuilder
                    ) D
                    GROUP BY D.id_katalog, id_depo
                ) C
            ) B ON A.id_katalog = B.id_katalog
            SET
                A.jenis_moving = B.jenis_moving,
                A.persen_leadtime = :leadtime,
                A.persen_buffer = CASE
                    WHEN B.jenis_moving = 'FM' THEN 30
                    WHEN B.jenis_moving = 'MM' THEN 20
                    WHEN B.jenis_moving = 'SM' THEN 10
                    ELSE 0
                END
            WHERE
                A.status = 1
                AND A.id_depo = B.id_depo
        ";
        $params = [":leadtime" => $leadtime];
        $connection->createCommand($sql, $params)->execute();

        // original name: $query_average
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_buffer_depo A
            INNER JOIN (
                SELECT
                    C.id_katalog                                             AS id_katalog,
                    C.id_depo                                                AS id_depo,
                    IFNULL(SUM(C.jumlah_penjualan), 0) / $jumlahBulanAverage AS jumlah_avg
                FROM (
                    $queryAverageBuilder
                ) C
                GROUP BY C.id_katalog, C.id_depo
            ) B ON A.id_katalog = B.id_katalog
            SET
                A.jumlah_avg = B.jumlah_avg,
                A.jumlah_buffer = B.jumlah_avg * (A.persen_buffer / 100),
                A.jumlah_leadtime = B.jumlah_avg * (A.persen_leadtime / 100),
                A.jumlah_rop = (B.jumlah_avg * (A.persen_buffer / 100)) + (B.jumlah_avg * (A.persen_leadtime / 100))
            WHERE
                A.status = 1
                AND A.id_depo = B.id_depo
        ";
        $connection->createCommand($sql)->execute();

        // original name: $query_update_sysdate
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.laporan_buffer_depo
            SET sysdate_updt = :tanggalUbah
            WHERE status = 1
        ";
        $params = [":tanggalUbah" => $nowValSystem];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * status: tidy
     * @throws Exception
     */
    public function actionRiwayatPenjualanDepo()
    {
        ["idKatalog" => $idKatalog, "idDepo" => $idDepo] = Yii::$app->request->post();
        $bulan = date("n");
        $tahun = date("Y");
        $durasi = 6;
        $connection = Yii::$app->dbFatma;

        $timeFilter = [];
        $xCurrentMonth = $currentMonth = ($bulan - 1) == 0 ? 12 : $bulan - 1;
        $xCurrentYear = $currentYear = ($bulan - 1) == 0 ? $tahun - 1 : $tahun;

        for ($i = 0; $i < $durasi; $i ++) {
            $timeFilter[] = [
                "bulan" => $currentMonth,
                "tahun" => $currentYear
            ];
            $currentMonth --;
            if ($currentMonth == 0) {
                $currentMonth = 12;
                $currentYear --;
            }
        }

        $queryList = [];
        foreach ($timeFilter as $key => $row) {
            $currBln = $row["bulan"];
            $currThn = $row["tahun"];

            $queryList[] = /** @lang SQL */ "
                SELECT
                    A$key.kode                        AS idKatalog,
                    A$key.nama_sediaan                AS namaBarang,
                    IFNULL(B$key.bulan, $currBln)     AS bulan,
                    IFNULL(B$key.tahun, $currThn)     AS tahun,
                    IFNULL(B$key.jumlah_penjualan, 0) AS jumlahPenjualan
                FROM masterf_katalog A$key
                LEFT JOIN (
                    SELECT *
                    FROM laporan_penjualan_depo_bulan
                    WHERE
                        id_katalog = :idKatalog
                        AND id_depo = :idDepo
                        AND bulan = '$currBln'
                        AND tahun = '$currThn'
                ) B$key ON A$key.kode = B$key.id_katalog
                WHERE A$key.kode = :idKatalog
            ";
        }

        $queryBuilder = implode("    UNION ", $queryList);
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__."
            SELECT A.*
            FROM (
                $queryBuilder
            ) A
            ORDER BY A.tahun ASC, A.bulan ASC
        ";
        $params = [":idDepo" => $idDepo, ":idKatalog" => $idKatalog];
        $history = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__."
            SELECT namaDepo
            FROM db1.masterf_depo
            WHERE id = :idDepo
            LIMIT 1
        ";
        $params = [":idDepo" => $idDepo];
        $namaDepo = $connection->createCommand($sql, $params)->queryScalar();

        $this->render("riwayat-penjualan-depo", [
            "tahun" => $xCurrentYear,
            "bulan" => $xCurrentMonth,
            "history" => $history,
            "nama_depo" => $namaDepo,
            "time_filter" => $timeFilter,
            "mTitle" => "Riwayat Penjualan",
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/mutasi.php#riwayatpenjualan    the original method
     */
    public function actionRiwayatPenjualan(): string
    {
        $idKatalog = Yii::$app->request->post("idKatalog") ?? throw new MissingPostParamException("idKatalog");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id_katalog       AS idKatalog,
                nama_barang      AS namaBarang,
                bulan            AS bulan,
                tahun            AS tahun,
                jumlah_penjualan AS jumlahPenjualan,
                jumlah_floorstok AS jumlahFloorstok
            FROM db1.laporan_mutasi_bulan
            WHERE id_katalog = :idKatalog
            ORDER BY tahun ASC, bulan ASC
            LIMIT 6
        ";
        $params = [":idKatalog" => $idKatalog];
        $daftarHistory = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarHistory);
    }
}
