<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\libs\LowEnd\components\DateTimeException;
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
class DashboardEksekutifController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/dashboardeksekutif.php#index    the original method
     */
    public function actionTableData(): string
    {
        [   "idDepo" => $idDepo,
            "bulanAwalAnggaran" => $bulanAwal,
            "bulanAkhirAnggaran" => $bulanAkhir,
            "tahunAnggaran" => $tahun,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;

        assert($bulanAwal && $bulanAkhir && $tahun, new MissingPostParamException("bulanAwalAnggaran", "bulanAkhirAnggaran", "tahunAnggaran"));

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                IFNULL(SUM(jumlah_stokfisik), 0)       AS jumlahStokFisik,
                C.kode                                 AS kodeBarang,
                C.nama_barang                          AS namaBarang,
                D.kelompok_barang                      AS kelompokBarang,
                IFNULL(PO.jumlah_item, PL.jumlah_item) AS jumlahItemR,
                IFNULL(PO.harga_item, PL.harga_item)   AS hargaItemR,
                CC.jumlah_item                         AS jumlahItem,
                DD.harga_item                          AS hargaItem,
                H.hp_item                              AS hpItem
            FROM db1.transaksif_stokkatalog AS A
            LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
            LEFT JOIN db1.masterf_kelompokbarang AS D ON C.id_kelompokbarang = D.id
            LEFT JOIN db1.relasif_hargaperolehan AS H ON A.id_katalog = H.id_katalog
            LEFT JOIN (
                SELECT
                    BB.id_katalog    AS id_katalog,
                    SUM(jumlah_item) AS jumlah_item
                FROM rsupf_latihan.relasif_perencanaan AS BB
                LEFT JOIN db1.masterf_katalog AS AA on BB.id_katalog = AA.kode
                LEFT JOIN db1.transaksif_perencanaan AS QQ ON BB.kode_reff = QQ.kode
                WHERE
                    QQ.thn_anggaran = :tahun
                    AND QQ.blnawal_anggaran >= :bulanAwal
                    AND QQ.blnakhir_anggaran <= :bulanAkhir
                GROUP BY BB.id_katalog
            ) AS CC on A.id_katalog = CC.id_katalog
            LEFT JOIN (
                SELECT
                    BB.id_katalog   AS id_katalog,
                    AVG(harga_item) AS harga_item
                FROM rsupf_latihan.relasif_perencanaan AS BB
                LEFT JOIN db1.masterf_katalog AS AA on BB.id_katalog = AA.kode
                LEFT JOIN db1.transaksif_perencanaan AS QQ ON BB.kode_reff = QQ.kode
                WHERE
                    QQ.thn_anggaran = :tahun
                    AND QQ.blnawal_anggaran >= :bulanAwal
                    AND QQ.blnakhir_anggaran <= :bulanAkhir
                GROUP BY BB.id_katalog
            ) AS DD on A.id_katalog = DD.id_katalog
            LEFT JOIN (
                SELECT
                    id_katalog       AS id_katalog,
                    SUM(jumlah_item) AS jumlah_item,
                    AVG(harga_item)  AS harga_item
                FROM rsupf_latihan.relasif_pemesanan AS X
                LEFT JOIN db1.transaksif_pemesanan AS Y ON X.kode_reff = Y.kode
                WHERE
                    Y.thn_anggaran = :tahun
                    AND Y.blnawal_anggaran >= :bulanAwal
                    AND Y.blnakhir_anggaran <= :bulanAkhir
                GROUP BY X.id_katalog
            ) AS PO ON A.id_katalog = PO.id_katalog
            LEFT JOIN (
                SELECT
                    id_katalog       AS id_katalog,
                    SUM(jumlah_item) AS jumlah_item,
                    AVG(harga_item)  AS harga_item
                FROM rsupf_latihan.relasif_pembelian AS X
                LEFT JOIN db1.transaksif_pembelian AS Y ON X.kode_reff = Y.kode
                WHERE
                    Y.thn_anggaran = :tahun
                    AND Y.blnawal_anggaran >= :bulanAwal
                    AND Y.blnakhir_anggaran <= :bulanAkhir
                GROUP BY X.id_katalog
            ) AS PL ON A.id_katalog = PL.id_katalog
            WHERE
                A.status = 1
                AND (:idDepo = '' OR A.id_depo = :idDepo)
                AND H.sts_hja = 1
            GROUP BY A.id_katalog
            ORDER BY C.kode ASC
        ";
        $params = [
            ":tahun" => $tahun,
            ":bulanAwal" => $bulanAwal,
            ":bulanAkhir" => $bulanAkhir,
            ":idDepo" => $idDepo,
        ];
        $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

        $daftarStokKatalog2 = [];
        foreach ($daftarStokKatalog as $item) {
            $daftarStokKatalog2[$item->kelompokBarang][] = $item;
        }

        $daftarTotal = [];
        $grandtotalStok = 0;
        $grandtotalRencana = 0;
        $grandtotalRealisasi = 0;
        foreach ($daftarStokKatalog2 as $namaKelompok => $kelompok) {
            $totalRupiahStok = 0;
            $totalRupiahRencana = 0;
            $totalRupiahRealisasi = 0;
            foreach ($kelompok as $item) {
                $totalRupiahStok += $item->jumlahStokFisik * $item->hpItem;
                $totalRupiahRencana += $item->jumlahItem * $item->hargaItem;
                $totalRupiahRealisasi += $item->jumlahItemR * $item->hargaItemR;
            }

            $daftarTotal[$namaKelompok] = [
                "stok" => $totalRupiahStok,
                "rencana" => $totalRupiahRencana,
                "realisasi" => $totalRupiahRealisasi,
            ];
            $grandtotalStok += $totalRupiahStok;
            $grandtotalRencana += $totalRupiahRencana;
            $grandtotalRealisasi += $totalRupiahRealisasi;
        }

        return json_encode([
            "daftarStokKatalog" => $daftarStokKatalog2, // truely correct assignment: $daftarStokKatalog2
            "daftarTotal" => $daftarTotal,
            "grandtotalStok" => $grandtotalStok,
            "grandtotalRencana" => $grandtotalRencana,
            "grandtotalRealisasi" => $grandtotalRealisasi,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/dashboardeksekutif.php#cek_pl    the original method
     */
    public function actionCekPlDetailPemesanan(): string
    {
        $idKatalog = $_POST["kode"];
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                a.kode      AS kode,
                a.kode_reff AS kodeRef
            FROM rsupf_latihan_2016.transaksif_perencanaan a
            LEFT JOIN rsupf_latihan.relasif_perencanaan b ON b.kode_reff = a.kode
            WHERE
                b.id_katalog = :idKatalog
                AND a.thn_anggaran = :tahun
                AND a.blnawal_anggaran = :bulanAwal
                AND a.blnakhir_anggaran <= :bulanAkhir
            LIMIT 1
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":tahun" => 2015,
            ":bulanAwal" => 8,
            ":bulanAkhir" => 9,
        ];
        $perencanaan = $connection->createCommand($sql, $params)->queryOne();

        if (!$perencanaan) throw new \Exception("Detail tidak tersedia");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                a.id_katalog                              AS idKatalog,
                a.jumlah_item                             AS jumlahItem,
                a.jumlah_kemasan                          AS jumlahKemasan,
                a.harga_item                              AS hargaItem,
                a.harga_kemasan                           AS hargaKemasan,
                a.diskon_item                             AS diskonItem,
                a.diskon_harga                            AS diskonHarga,
                jumlah_item * harga_item                  AS hargaTotal,
                (jumlah_item * harga_item) - diskon_harga AS hargaAkhir,
                b.nama_barang                             AS namaBarang,
                c.nama_pabrik                             AS namaPabrik
            FROM rsupf_latihan.relasif_pemesanan AS a
            LEFT JOIN db1.masterf_katalog b ON b.kode = a.id_katalog
            LEFT JOIN db1.masterf_pabrik c ON c.id = a.id_pabrik
            WHERE
                a.kode_reff = :kodeRef
                and a.id_katalog = :idKatalog
        ";
        $params = [":idKatalog" => $idKatalog, ":kodeRef" => $perencanaan->kode];
        $daftarDetailPemesanan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarDetailPemesanan);
    }

    /**
     * @author Hendra Gunawan
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/dashboardeksekutif.php#cek_pl    the original method
     */
    public function actionCekPlPemesanan(): string
    {
        $idKatalog = $_POST["kode"];
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                a.kode      AS kode,
                a.kode_reff AS kodeRef
            FROM rsupf_latihan_2016.transaksif_perencanaan a
            LEFT JOIN rsupf_latihan.relasif_perencanaan b ON b.kode_reff = a.kode
            WHERE
                b.id_katalog = :idKatalog
                AND a.thn_anggaran = :tahun
                AND a.blnawal_anggaran = :bulanAwal
                AND a.blnakhir_anggaran <= :bulanAkhir
            LIMIT 1
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":tahun" => 2015,
            ":bulanAwal" => 8,
            ":bulanAkhir" => 9,
        ];
        $perencanaan = $connection->createCommand($sql, $params)->queryOne();

        if (!$perencanaan) throw new \Exception("Detail tidak tersedia");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                a.kode              AS kode,
                a.no_doc            AS noDokumen,
                a.tgl_tempokirim    AS tanggalTempoKirim,
                a.tgl_mulai         AS tanggalMulai,
                a.tgl_jatuhtempo    AS tanggalJatuhTempo,
                a.thn_anggaran      AS tahunAnggaran,
                a.blnawal_anggaran  AS bulanAwalAnggaran,
                a.blnakhir_anggaran AS bulanAkhirAnggaran,
                b.jenis_obat        AS jenisObat,
                c.sumber_dana       AS sumberDana,
                d.subsumber_dana    AS subsumberDana,
                e.cara_bayar        AS caraBayar, 
                f.jenis_harga       AS jenisHarga,
                g.nama_pbf          AS namaPemasok,
                h.no_doc            AS noSpk
            FROM rsupf_latihan.transaksif_pemesanan a 
            LEFT JOIN db1.masterf_jenisobat b ON b.id = a.id_jenisanggaran
            LEFT JOIN db1.masterf_sumberdana c ON c.id = a.id_sumberdana
            LEFT JOIN db1.masterf_subsumberdana d ON d.id = a.id_subsumberdana
            LEFT JOIN db1.masterf_carabayar e ON e.id = a.id_carabayar
            LEFT JOIN db1.masterf_jenisharga f ON d.id = a.id_jenisharga
            LEFT JOIN db1.masterf_pbf g ON g.id = a.id_pbf
            LEFT JOIN db1.transaksif_pembelian h ON h.kode = a.kode_reff
            WHERE
                a.kode_reff = :kodeRef
                and a.kode_reffrenc = :kodeRefRencana
            LIMIT 1
        ";
        $params = [":kodeRef" => $perencanaan->kodeRef, ":kodeRefRencana" => $perencanaan->kode];
        $pemesanan = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($pemesanan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/dashboardeksekutif.php#cek_rencana    the original method
     */
    public function actionCekRencana(): string
    {
        ["kode" => $idKatalog, "awal" => $bulanAwal, "akhir" => $bulanAkhir] = $_POST;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                a.no_doc            AS noDokumen,
                a.blnawal_anggaran  AS bulanAwalAnggaran,
                a.blnakhir_anggaran AS bulanAkhirAnggaran,
                b.jumlah_item       AS jumlahItem,
                h.no_doc            AS noSpk
            FROM rsupf_latihan_2016.transaksif_perencanaan a
            LEFT JOIN rsupf_latihan.relasif_perencanaan b ON b.kode_reff = a.kode
            LEFT JOIN db1.transaksif_pembelian h ON h.kode = a.kode_reff
            WHERE
                b.id_katalog = :idKatalog
                AND a.thn_anggaran = :tahun
                AND a.blnawal_anggaran >= :bulanAwal
                AND a.blnakhir_anggaran <= :bulanAkhir
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":tahun" => 2015,
            ":bulanAwal" => $bulanAwal,
            ":bulanAkhir" => $bulanAkhir,
        ];
        $daftarPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPerencanaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/dashboardeksekutif.php#cek_realisasi    the original method
     */
    public function actionCekRealisasi(): string
    {
        ["kode" => $idKatalog, "awal" => $bulanAwal, "akhir" => $bulanAkhir] = $_POST;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT TRUE
            FROM rsupf_latihan_2016.transaksif_perencanaan a
            LEFT JOIN rsupf_latihan.relasif_perencanaan b ON b.kode_reff = a.kode
            WHERE
                b.id_katalog = :idKatalog
                AND a.thn_anggaran = :tahun
                AND a.blnawal_anggaran >= :bulanAwal
                AND a.blnakhir_anggaran <= :bulanAkhir
            LIMIT 1
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":tahun" => 2015,
            ":bulanAwal" => $bulanAwal,
            ":bulanAkhir" => $bulanAkhir,
        ];
        $cekPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        if ($cekPerencanaan) throw new \Exception( "Detail tidak tersedia");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                a.no_doc            AS noDokumen,
                a.blnawal_anggaran  AS bulanAwalAnggaran,
                a.blnakhir_anggaran AS bulanAkhirAnggaran,
                h.no_doc            AS noSpk,
                b.jumlah_item       AS jumlahDo,
                c.jumlah_item       AS jumlahSpk
            FROM rsupf_latihan.transaksif_pemesanan a
            LEFT JOIN db1.transaksif_pembelian h ON h.kode = a.kode_reff
            LEFT JOIN rsupf_latihan.relasif_pemesanan b ON b.kode_reff = a.kode
            LEFT JOIN rsupf_latihan.relasif_pembelian c ON c.id_katalog = b.id_katalog
            WHERE
                a.kode_reff IN (
                    SELECT a.kode_reff
                    FROM rsupf_latihan_2016.transaksif_perencanaan a
                    LEFT JOIN rsupf_latihan.relasif_perencanaan b ON b.kode_reff = a.kode
                    WHERE
                        b.id_katalog = :idKatalog
                        AND a.thn_anggaran = :tahun
                        AND a.blnawal_anggaran >= :bulanAwal
                        AND a.blnakhir_anggaran <= :bulanAkhir
                )
                AND a.kode_reffrenc IN (
                    SELECT a.kode
                    FROM rsupf_latihan_2016.transaksif_perencanaan a
                    LEFT JOIN rsupf_latihan.relasif_perencanaan b ON b.kode_reff =a.kode
                    WHERE
                        b.id_katalog = :idKatalog
                        AND a.thn_anggaran = :tahun
                        AND a.blnawal_anggaran >= :bulanAwal
                        AND a.blnakhir_anggaran<= :bulanAkhir
                )
                AND c.kode_reff = a.kode_reff
                AND b.id_katalog = :idKatalog
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":tahun" => 2015,
            ":bulanAwal" => $bulanAwal,
            ":bulanAkhir" => $bulanAkhir,
        ];
        $daftarPemesanan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPemesanan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/dashboardeksekutif.php#grafikobat    the original method
     */
    public function actionGrafikObatData(): string
    {
        $todayValSystem = Yii::$app->dateTime->todayVal("system");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                PNJ.kodeObat          AS kodeObat,
                KAT.nama_barang       AS namaBarang,
                SUM(PNJ.jlhPenjualan) AS total
            FROM db1.masterf_penjualan AS PNJ
            LEFT JOIN db1.masterf_katalog AS KAT ON PNJ.kodeObat = KAT.kode
            WHERE
                tglPenjualan = :tanggalPenjualan
                AND verifikasi != ''
            GROUP BY kodeObat
            ORDER BY TOTAL DESC
            LIMIT 10
        ";
        $params = [":tanggalPenjualan" => $todayValSystem];
        $daftarPenjualan1 = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                PJN.kodeObat                      AS kodeObat,
                KAT.nama_barang                   AS namaBarang,
                SUM(PJN.jlhPenjualan * PJN.harga) AS total
            FROM db1.masterf_penjualan AS PJN
            LEFT JOIN db1.masterf_katalog AS KAT ON PJN.kodeObat = KAT.kode
            WHERE
                tglPenjualan = :tanggalPenjualan
                AND verifikasi != ''
            GROUP BY kodeObat
            ORDER BY TOTAL DESC
            LIMIT 10
        ";
        $params = [":tanggalPenjualan" => $todayValSystem];
        $daftarPenjualan2 = $connection->createCommand($sql, $params)->queryAll();

        return json_encode(["daftarPenjualan1" => $daftarPenjualan1, "daftarPenjualan2" => $daftarPenjualan2]);
    }
}
