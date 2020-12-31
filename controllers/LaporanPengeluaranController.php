<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\LaporanPengeluaran\{ReportGasMedis, ReportPengeluaran, ReportPerTujuan};
use tlm\libs\LowEnd\components\{DateTimeException, GenericData};
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
class LaporanPengeluaranController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#print_rekap_harian_tes    the original method
     * last exist of actionPrintRekapHarianTes: commit-e37d34f4
     */
    public function actionReportPengeluaran(): string
    {
        $connection = Yii::$app->dbFatma;
        ["tanggalAwal" => $tanggalAwal, "tanggalAkhir" => $tanggalAkhir] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                AS kode,
                a.tipe_pengiriman     AS tipePengiriman,
                a.noDistribusi        AS noDistribusi,
                a.noPermintaan        AS noPermintaan,
                a.noPengeluaran       AS noPengeluaran,
                a.noPenerimaan        AS noPenerimaan,
                a.verifikasi_user     AS verifikasiUser,
                a.tanggal_verifikasi  AS tanggalVerifikasi,
                a.verifikasi_terima   AS verifikasiTerima,
                a.tanggal_terima      AS tanggalTerima,
                a.tipe                AS tipe,
                a.kodeKetersediaan    AS kodeKetersediaan,
                a.depoPeminta         AS depoPeminta,
                a.kodeDepo            AS kodeDepo,
                a.kodeItem            AS kodeItem,
                a.nomor_batch         AS noBatch,
                a.namaItem            AS namaItem,
                a.jumlah1             AS jumlah1,
                a.jumlah2             AS jumlah2,
                a.jumlah3             AS jumlah3,
                a.harga_perolehan     AS hargaPerolehan,
                a.detail              AS detail,
                a.prioritas           AS prioritas,
                a.status              AS status,
                a.tanggal             AS tanggal,
                a.no_doc              AS noDokumen,
                a.no_doc_pengiriman   AS noDokumenPengiriman,
                a.no_doc_penerimaan   AS noDokumenPenerimaan,
                a.checking_pengiriman AS cekPengiriman,
                a.checking_penerimaan AS cekPenerimaan,
                a.checking_double     AS cekDouble,
                g.kelompok_barang     AS namaKelompok,
                SUM(a.jumlah2)        AS totalJumlah,
                ''                    AS hpItem
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeItem
            INNER JOIN db1.masterf_depo AS c ON c.kode = a.kodeDepo
            LEFT JOIN db1.masterf_brand AS h ON h.id = b.id_brand
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = b.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS f ON f.id = b.id_kemasankecil
            LEFT JOIN db1.masterf_kelompokbarang AS g ON g.id = b.id_kelompokbarang
            WHERE
                a.kodeDepo = :kodeDepo
                AND a.tanggal_verifikasi >= :tanggalAwal
                AND a.tanggal_verifikasi <= :tanggalAkhir
            GROUP BY b.nama_barang
            ORDER BY b.id_kelompokbarang, b.nama_barang
        ";
        $params = [
            ":kodeDepo" => Yii::$app->userFatma->idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarPeringatan as $peringatan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT IFNULL(hp_item, 0)
                FROM db1.relasif_hargaperolehan
                WHERE
                    id_katalog = :idKatalog
                    AND tgl_hp <= :tanggalHp
                ORDER BY tgl_hp DESC
                LIMIT 1
            ";
            $params = [":idKatalog" => $peringatan->kodeItem, ":tanggalHp" => $peringatan->tanggal_verifikasi];
            $peringatan->hpItem = $connection->createCommand($sql, $params)->queryScalar();
        }

        $view = new ReportPengeluaran(
            namaDepo:         Yii::$app->userFatma->namaDepo,
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
            daftarPeringatan: $daftarPeringatan,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#print_rekap    the original method
     * last exist of actionPrintRekap: commit-e37d34f4
     */
    public function actionReportPerTujuan(): string
    {
        [   "idDepoTujuan" => $idDepoPeminta,
            "idDepoAsal" => $idDepoPemberi,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS kode,
                namaDepo AS nama
            FROM db1.masterf_depo
            WHERE id = :idDepo
            LIMIT 1
        ";
        $params = [":idDepo" => $idDepoPeminta];
        $depoPeminta = $connection->createCommand($sql, $params)->queryOne() ?? new GenericData(["kode" => "", "nama" => "Semua Depo"]);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode
            FROM db1.masterf_depo
            WHERE id = :idDepo
            LIMIT 1
        ";
        $params = [":idDepo" => $idDepoPemberi];
        $kodeDepoPemberi = $connection->createCommand($sql, $params)->queryScalar() ?? Yii::$app->userFatma->kodeDepo;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                AS kode,
                a.tipe_pengiriman     AS tipePengiriman,
                a.noDistribusi        AS noDistribusi,
                a.noPermintaan        AS noPermintaan,
                a.noPengeluaran       AS noPengeluaran,
                a.noPenerimaan        AS noPenerimaan,
                a.verifikasi_user     AS verifikasiUser,
                a.tanggal_verifikasi  AS tanggalVerifikasi,
                a.verifikasi_terima   AS verifikasiTerima,
                a.tanggal_terima      AS tanggalTerima,
                a.tipe                AS tipe,
                a.kodeKetersediaan    AS kodeKetersediaan,
                a.depoPeminta         AS depoPeminta,
                a.kodeDepo            AS kodeDepo,
                a.kodeItem            AS kodeItem,
                a.nomor_batch         AS noBatch,
                a.namaItem            AS namaItem,
                a.jumlah1             AS jumlah1,
                a.jumlah2             AS jumlah2,
                a.jumlah3             AS jumlah3,
                a.harga_perolehan     AS hargaPerolehan,
                a.detail              AS detail,
                a.prioritas           AS prioritas,
                a.status              AS status,
                a.tanggal             AS tanggal,
                a.no_doc              AS noDokumen,
                a.no_doc_pengiriman   AS noDokumenPengiriman,
                a.no_doc_penerimaan   AS noDokumenPenerimaan,
                a.checking_pengiriman AS cekPengiriman,
                a.checking_penerimaan AS cekPenerimaan,
                a.checking_double     AS cekDouble,
                g.jenis_obat          AS namaJenis,
                SUM(jumlah2)          AS totalJumlah
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeItem
            INNER JOIN db1.masterf_depo AS c ON c.kode = a.kodeDepo
            LEFT JOIN db1.masterf_brand AS h ON h.id = b.id_brand
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = b.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS f ON f.id = b.id_kemasankecil
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = b.id_jenisbarang
            WHERE
                a.kodeDepo = :kodeDepoPemberi
                AND (:kodeDepoPeminta = '' OR a.depoPeminta = :kodeDepoPeminta)
                AND a.tanggal_verifikasi >= :tanggalAwal
                AND a.tanggal_verifikasi <= :tanggalAkhir + INTERVAL 1 DAY
            GROUP BY
                b.nama_barang,
                a.depoPeminta
            ORDER BY a.depoPeminta, g.jenis_obat, b.kode ASC
        ";
        $params = [
            ":kodeDepoPemberi" => $kodeDepoPemberi,
            ":kodeDepoPeminta" => $depoPeminta->kode,
            ":tanggalAwal" => $toSystemDate($tanggalAwal),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir),
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        $view = new ReportPerTujuan(
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
            daftarPeringatan: $daftarPeringatan,
            connection:       $connection,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#print_rekapgm    the original method
     * last exist of actionPrintRekapGm: commit-e37d34f4
     */
    public function actionReportGasMedis(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "idDepoTujuan" => $idDepoPeminta,
            "idDepoAsal" => $idDepoPemberi,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode     AS kode,
                namaDepo AS nama
            FROM db1.masterf_depo
            WHERE id = :idDepo
            LIMIT 1
        ";
        $params = [":idDepo" => $idDepoPeminta];
        $depoPeminta = $connection->createCommand($sql, $params)->queryOne() ?? new GenericData(["kode" => "", "nama" => "Semua Depo"]);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode
            FROM db1.masterf_depo
            WHERE id = :idDepo
            LIMIT 1
        ";
        $params = [":idDepo" => $idDepoPemberi];
        $kodeDepoPemberi = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.tanggal_verifikasi AS tanggalVerifikasi, -- in use
                a.nomor_batch        AS noBatch,
                a.jumlah2            AS jumlah2,           -- in use
                a.depoPeminta        AS depoPeminta,
                b.kode               AS kode,              -- in use
                b.nama_barang        AS namaBarang,        -- in use
                c.namaDepo           AS depoAsal,
                d.namaDepo           AS depoTujuan,        -- in use
                e.nama_pabrik        AS namaPabrik,        -- in use
                f.nama_kemasan       AS namaKemasan,
                g.jenis_obat         AS namaJenis
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeItem
            INNER JOIN db1.masterf_depo AS c ON c.kode = a.kodeDepo
            INNER JOIN db1.masterf_depo AS d ON d.kode = a.depoPeminta
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = b.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS f ON f.id = b.id_kemasankecil
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = b.id_jenisbarang
            WHERE
                (:kodeDepoPemberi = '' OR a.kodeDepo = :kodeDepoPemberi)
                AND (:kodeDepoPeminta = '' OR a.depoPeminta = :kodeDepoPeminta)
                AND a.tanggal_verifikasi >= :tanggalAwal
                AND a.tanggal_verifikasi <= :tanggalAkhir + INTERVAL 1 DAY
            ORDER BY a.depoPeminta, b.kode ASC
        ";
        $params = [
            ":kodeDepoPemberi" => $kodeDepoPemberi,
            ":kodeDepoPeminta" => $depoPeminta->kode,
            ":tanggalAwal" => $toSystemDate($tanggalAwal),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir),
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalJumlah = 0;
        $grandTotalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $barisPerHalaman = 40;
        $depoTujuanSaatIni = "";
        $kodeSaatIni = "";

        foreach ($daftarPeringatan as $i => $peringatan) {
            if ($depoTujuanSaatIni != $peringatan->depoTujuan) {
                $depoTujuanSaatIni = $peringatan->depoTujuan;
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "depo_tujuan" => $peringatan->depoTujuan,
                    "total_jumlah" => 0,
                    "total_nilai" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($kodeSaatIni != $peringatan->depoTujuan) {
                $kodeSaatIni = $peringatan->depoTujuan;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_barang" => $peringatan->namaBarang,
                    "nama_pabrik" => $peringatan->namaPabrik,
                    "subtotal_jumlah" => 0,
                    "subtotal_nilai" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT hp_item
                FROM db1.relasif_hargaperolehan
                WHERE
                    id_katalog = :idKatalog
                    AND tgl_hp <= :tanggalHp
                ORDER BY tgl_hp DESC
                LIMIT 1
            ";
            $params = [":idKatalog" => $peringatan->kode, ":tanggalHp" => $peringatan->tanggalVerifikasi];
            $hpItem = $connection->createCommand($sql, $params)->queryScalar();

            $nilai = $hpItem * $peringatan->jumlah2;

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".".  $noData.".",
                "hp_item" => $hpItem,
                "nilai" => $nilai
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_jumlah"] += $peringatan->jumlah2;
            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_nilai"] += $nilai;

            $daftarHalaman[$hJudul1][$bJudul1]["total_jumlah"] += $peringatan->jumlah2;
            $daftarHalaman[$hJudul1][$bJudul1]["total_nilai"] += $nilai;

            $grandTotalJumlah += $peringatan->jumlah2;
            $grandTotalNilai += $nilai;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportGasMedis(
            daftarHalaman:    $daftarHalaman,
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
            namaDepo:         $depoPeminta->nama,
            daftarPeringatan: $daftarPeringatan,
            jumlahHalaman:    count($daftarHalaman),
            grandTotalJumlah: $grandTotalJumlah,
            grandTotalNilai:  $grandTotalNilai,
        );
        return $view->__toString();
    }
}
