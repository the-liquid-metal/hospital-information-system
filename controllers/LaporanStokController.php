<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\LaporanStok\{ReportKetersediaan, ReportMonitor};
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
class LaporanStokController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/monitorstok.php#index    the original method
     */
    public function actionReportMonitor(): string
    {
        ["id_depo" => $idDepo] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        if ($idDepo != "-") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    SKAT.jumlah_stokadm   AS jumlahStokAdm,
                    SKAT.jumlah_stokfisik AS jumlahStokFisik,
                    DPO.namaDepo          AS namaDepo,
                    DPO.id                AS idDepo,
                    KAT.kode              AS kodeBarang,
                    KAT.nama_barang       AS namaBarang,
                    KBG.kode              AS kodeKelompok,
                    KBG.kelompok_barang   AS kelompokBarang,
                    PBK.nama_pabrik       AS namaPabrik,
                    KEM.kode              AS kodeKemasan,
                    HPR.hp_item           AS hja
                FROM db1.transaksif_stokkatalog AS SKAT
                LEFT JOIN db1.masterf_depo AS DPO ON SKAT.id_depo = DPO.id
                LEFT JOIN db1.masterf_katalog AS KAT ON SKAT.id_katalog = KAT.kode
                LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KAT.id_kelompokbarang = KBG.id
                LEFT JOIN db1.masterf_pabrik AS PBK ON KAT.id_pabrik = PBK.id
                LEFT JOIN db1.masterf_kemasan AS KEM ON KAT.id_kemasankecil = KEM.id
                LEFT JOIN db1.relasif_hargaperolehan AS HPR ON SKAT.id_katalog = HPR.id_katalog
                WHERE
                    SKAT.id_depo = :idDepo
                    AND HPR.sts_hja = 1
                ORDER BY KAT.kode ASC
            ";
            $params = [":idDepo" => $idDepo];
            $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    SO.kode     AS kode,
                    SO.id_depo  AS idDepo,
                    SO.tgl_adm  AS tanggalAdm,
                    DP.namaDepo AS namaDepo
                FROM db1.transaksif_stokopname AS SO
                LEFT JOIN db1.masterf_depo AS DP ON SO.id_depo = DP.id
                WHERE SO.id_depo = :idDepo
            ";
            $params = [":idDepo" => $idDepo];
            $daftarAdm = $connection->createCommand($sql, $params)->queryAll();

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    IFNULL(SUM(jumlah_stokadm), 0)   AS jumlahStokAdm,
                    IFNULL(SUM(jumlah_stokfisik), 0) AS jumlahStokFisik,
                    'Semua Gudang'                   AS namaDepo,
                    0                                AS idDepo,
                    C.kode                           AS kodeBarang,
                    C.nama_barang                    AS namaBarang,
                    D.kode                           AS kodeKelompok,
                    D.kelompok_barang                AS kelompokBarang,
                    G.nama_pabrik                    AS namaPabrik,
                    E.kode                           AS kodeKemasan,
                    F.hp_item                        AS hja
                FROM db1.transaksif_stokkatalog AS A
                LEFT JOIN db1.masterf_depo AS B ON A.id_depo = B.id
                LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
                LEFT JOIN db1.masterf_kelompokbarang AS D ON C.id_kelompokbarang = D.id
                LEFT JOIN db1.masterf_kemasan AS E ON C.id_kemasankecil = E.id
                LEFT JOIN db1.relasif_hargaperolehan AS F ON A.id_katalog = F.id_katalog
                LEFT JOIN db1.masterf_pabrik AS G ON C.id_pabrik = G.id
                WHERE
                    A.status = 1
                    AND F.sts_hja = 1
                    AND B.id NOT IN (319, 320, 321, 68)
                GROUP BY A.id_katalog
                ORDER BY C.kode ASC
            ";
            $daftarStokKatalog = $connection->createCommand($sql)->queryAll();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    AKSO.kode           AS kode,
                    59                  AS ___,
                    AKSO.tgl_adm        AS tanggalAdm,
                    'Instalasi Farmasi' AS namaDepo
                FROM db1.masterf_aktifasiso AS AKSO
                WHERE AKSO.status = 1
            ";
            $daftarAdm = $connection->createCommand($sql)->queryAll();
        }

        $daftarStokKatalog2 = [];
        $keyAkhir = "";
        foreach ($daftarStokKatalog as $stokKatalog) {
            $daftarStokKatalog2[$stokKatalog->kelompokBarang][] = $stokKatalog;
            $keyAkhir = $stokKatalog->kelompokBarang;
        }

        $view = new ReportMonitor(
            daftarStokKatalog: $daftarStokKatalog2,
            adm:               $daftarAdm[0] ?? null,
            keyAkhir:          $keyAkhir,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#krtketersediaan    the original method
     * last exist of actionKartuKetersediaanWithLx300: commit-e37d34f4
     */
    public function actionReportKetersediaan(): string
    {
        [   "dariTanggal" => $tanggalAwal,
            "sampaiTanggal" => $tanggalAkhir,
            "idDepo" => $idDepo,
            "kodeObat" => $idKatalog,
        ] = Yii::$app->request->post();

        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $defaultSystemDatetime = Yii::$app->dateTime->defaultVal("system");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT namaDepo
            FROM db1.masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idDepo];
        $namaDepo = (string) $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id               AS id,
                a.id_depo          AS idDepo,
                a.kode_reff        AS kodeRef,                -- in use
                a.no_doc           AS noDokumen,              -- in use
                a.ppn              AS ppn,
                a.id_reff          AS idRef,
                a.kode_stokopname  AS kodeStokopname,
                a.tgl_adm          AS tanggalAdm,
                a.tgl_transaksi    AS tanggalTransaksi,
                a.bln_transaksi    AS bulanTransaksi,
                a.thn_transaksi    AS tahunTransaksi,
                a.kode_transaksi   AS kodeTransaksi,
                a.kode_store       AS kodeStore,
                a.tipe_tersedia    AS tipeTersedia,           -- in use
                a.tgl_tersedia     AS tanggalTersedia,        -- in use
                a.no_batch         AS noBatch,                -- in use
                a.tgl_expired      AS tanggalKadaluarsa,      -- in use
                a.id_katalog       AS idKatalog,
                a.id_pabrik        AS idPabrik,
                a.id_kemasan       AS idKemasan,
                a.isi_kemasan      AS isiKemasan,
                a.jumlah_sebelum   AS jumlahSebelum,
                a.jumlah_masuk     AS jumlahMasuk,            -- in use
                a.jumlah_keluar    AS jumlahKeluar,           -- in use
                a.jumlah_tersedia  AS jumlahTersedia,         -- in use
                a.harga_netoapotik AS hargaNetoApotik,
                a.harga_perolehan  AS hargaPerolehan,
                a.phja             AS phja,
                a.phja_pb          AS phjaPb,
                a.harga_jualapotik AS hargaJualApotik,
                a.jumlah_item      AS jumlahItem,
                a.jumlah_kemasan   AS jumlahKemasan,
                a.jumlah_spk       AS jumlahSpk,
                a.jumlah_do        AS jumlahDo,
                a.jumlah_terima    AS jumlahTerima,
                a.harga_item       AS hargaItem,
                a.harga_kemasan    AS hargaKemasan,
                a.diskon_item      AS diskonItem,
                a.diskonjual_item  AS diskonJualItem,
                a.diskon_harga     AS diskonHarga,
                a.status           AS status,
                a.keterangan       AS keterangan,
                a.userid_last      AS useridLast,
                a.sysdate_last     AS sysdateLast,
                a.keterangan       AS keteranganKetersediaan, -- in use
                a.harga_perolehan  AS hargaPerolehan          -- in use
            FROM db1.relasif_ketersediaan AS a
            INNER JOIN db1.masterf_depo AS b ON b.id = a.id_depo
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.id_katalog
            WHERE
                a.status = 1
                AND a.tgl_tersedia >= :tanggalAwal
                AND a.tgl_tersedia <= :tanggalAkhir
                AND (:idKatalog = '' OR a.id_katalog = :idKatalog)
                AND (:idDepo = '' OR a.id_depo = :idDepo)
            ORDER BY a.tgl_tersedia, a.id
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":idDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarKetersediaan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode         AS kodeObat,
                a.kemasan      AS kemasan,
                a.nama_barang  AS namaBarang,
                b.nama_kemasan AS namaKemasan,
                c.nama_pabrik  AS namaPabrik
            FROM db1.masterf_katalog AS a
            LEFT JOIN db1.masterf_kemasan AS b ON b.id = a.id_kemasankecil
            LEFT JOIN db1.masterf_pabrik AS c ON c.id = a.id_pabrik
            WHERE a.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $idKatalog];
        $obat = $connection->createCommand($sql, $params)->queryOne();

        $daftarHalaman = [];
        $totalMasuk = 0;
        $totalKeluar = 0;
        $totalSisa = 0;

        $jumlahData = count($daftarKetersediaan);
        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 30;
        $maksHurufDeskripsi = 33;
        $jumlahTersedia = 0;
        $tanggalTersedia = "";

        foreach ($daftarKetersediaan as $i => $ketersediaan) {
            $jumlahTersedia = ($i == 0)
                ? $ketersediaan->jumlahTersedia
                : $jumlahTersedia + $ketersediaan->jumlahMasuk - $ketersediaan->jumlahKeluar;

            if ($ketersediaan->tipeTersedia == "stokopname" && $tanggalTersedia != $ketersediaan->kodeRef) {
                $jumlahTersedia = $ketersediaan->jumlahMasuk - $ketersediaan->jumlahKeluar;
                $tanggalTersedia = $ketersediaan->kodeRef;
            }

            if ($ketersediaan->tipeTersedia != "stokopname") {
                $totalMasuk += $ketersediaan->jumlahMasuk;
                $totalKeluar += $ketersediaan->jumlahKeluar;
            }

            $butuhBarisSaatIni = ceil(strlen($ketersediaan->keteranganKetersediaan) / $maksHurufDeskripsi);

            $daftarHalaman[$h][$b] = [
                "tanggal" => $ketersediaan->tanggalTersedia,
                "nomor_dokumen" => $ketersediaan->noDokumen ?: $ketersediaan->kodeRef,
                "deskripsi" => $ketersediaan->keteranganKetersediaan,
                "no_batch" => $ketersediaan->noBatch,
                "tanggal_kadaluarsa" => ($ketersediaan->tanggalKadaluarsa != $defaultSystemDatetime) ? $ketersediaan->tanggalKadaluarsa : "",
                "tipe_tersedia" => $ketersediaan->tipeTersedia,
                "terima" => $ketersediaan->jumlahMasuk,
                "keluar" => $ketersediaan->jumlahKeluar,
                "sisa" => $ketersediaan->jumlahTersedia,
                "harga" => $ketersediaan->hargaPerolehan,
            ];

            $totalSisa = $ketersediaan->jumlahTersedia;

            if ($i + 1 == $jumlahData) break;
            $dataBerikutnya = $daftarKetersediaan[$i + 1];
            $butuhBarisBerikutnya = ceil(strlen($dataBerikutnya->keteranganKetersediaan) / $maksHurufDeskripsi);

            if (($b + $butuhBarisSaatIni + $butuhBarisBerikutnya) > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b += $butuhBarisSaatIni;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportKetersediaan(
            tanggalAwal:   $tanggalAwal,
            tanggalAkhir:  $tanggalAkhir,
            daftarHalaman: $daftarHalaman,
            obat:          $obat,
            totalMasuk:    $totalMasuk,
            totalKeluar:   $totalKeluar,
            totalSisa:     $totalSisa,
            namaDepo:      $namaDepo,
        );
        return $view->__toString();
    }
}
