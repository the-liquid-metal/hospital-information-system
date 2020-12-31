<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\DataNotExistException;
use tlm\his\FatmaPharmacy\views\LaporanPerencanaan\{ReportItem, ReportRekap, ViewItem};
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
class LaporanPerencanaanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#reports the original method
     */
    public function actionReportRekap(): string
    {
        [   "statusKontrak" => $statusKontrak,
            "tahunAnggaran" => $tahunAnggaran,
            "bulanAwalAnggaran" => $bulanAwalAnggaran,
            "bulanAkhirAnggaran" => $bulanAkhirAnggaran,
        ] = Yii::$app->request->post();

        if ($statusKontrak == 1) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    B.id_jenisanggaran       AS idJenisAnggaran,  -- in use
                    E.subjenis_anggaran      AS subjenisAnggaran, -- in use
                    A.id_katalog             AS idKatalog,
                    C.nama_sediaan           AS namaSediaan,
                    D.nama_pabrik            AS namaPabrik,
                    SUM(A.jumlah_item)       AS jumlahItem,       -- in use
                    AVG(A.harga_item)        AS hargaItem,        -- in use
                    IFNULL(R.jumlah_item, 0) AS jumlahItemR,      -- in use
                    IFNULL(R.harga_item, 0)  AS hargaItemR        -- in use
                FROM db1.tdetailf_perencanaan AS A
                INNER JOIN db1.transaksif_perencanaan AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        dP.kode_reffro     AS kode_reffro,
                        dP.id_katalog      AS id_katalog,
                        SUM(T.jumlah_item) AS jumlah_item,
                        AVG(dP.harga_item) AS harga_item
                    FROM db1.tdetailf_pemesanan AS dP
                    INNER JOIN db1.transaksif_pemesanan AS P ON dP.kode_reff = P.kode
                    LEFT JOIN (
                        SELECT
                            dT.kode_reffpo      AS kode_reffpo,
                            dT.id_katalog       AS id_katalog,
                            SUM(dT.jumlah_item) AS jumlah_item
                        FROM db1.tdetailf_penerimaan AS dT
                        INNER JOIN db1.transaksif_penerimaan AS T ON dT.kode_reff = T.kode
                        WHERE
                            T.sts_testing = 0
                            AND T.sts_deleted = 0
                            AND T.ver_gudang = 1
                        GROUP BY
                            dT.kode_reffpo,
                            dT.id_katalog
                    ) AS T ON dP.kode_reff = T.kode_reffpo
                    WHERE
                        P.sts_deleted = 0
                        AND dP.id_katalog = T.id_katalog
                    GROUP BY
                        dP.kode_reffro,
                        dP.id_katalog
                ) AS R ON A.kode_reff = R.kode_reffro
                LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
                LEFT JOIN db1.masterf_pabrik AS D ON A.id_pabrik = D.id
                LEFT JOIN db1.masterf_subjenisanggaran AS E ON B.id_jenisanggaran = E.id
                WHERE
                    B.sts_deleted = 0
                    AND B.thn_anggaran = :tahun
                    AND B.blnawal_anggaran >= :bulanAwal
                    AND B.blnakhir_anggaran <= :bulanAkhir
                    AND A.id_katalog = R.id_katalog
                GROUP BY A.id_katalog
            ";

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    B.id_jenisanggaran       AS idJenisAnggaran,  -- in use
                    E.subjenis_anggaran      AS subjenisAnggaran, -- in use
                    A.id_katalog             AS idKatalog,
                    C.nama_sediaan           AS namaSediaan,
                    D.nama_pabrik            AS namaPabrik,
                    SUM(A.jumlah_item)       AS jumlahItem,       -- in use
                    AVG(A.harga_item)        AS hargaItem,        -- in use
                    IFNULL(R.jumlah_item, 0) AS jumlahItemR,      -- in use
                    IFNULL(R.harga_item, 0)  AS hargaItemR        -- in use
                FROM db1.tdetailf_perencanaan AS A
                INNER JOIN db1.transaksif_perencanaan AS B ON A.kode_reff = B.kode
                LEFT JOIN (
                    SELECT
                        dP.kode_reffrenc   AS kode_reffrenc,
                        dP.id_reffkatalog  AS id_reffkatalog,
                        SUM(T.jumlah_item) AS jumlah_item,
                        AVG(dP.harga_item) AS harga_item
                    FROM db1.tdetailf_pembelian AS dP
                    INNER JOIN db1.transaksif_pembelian AS P ON dP.kode_reff = P.kode
                    LEFT JOIN (
                        SELECT
                            dT.kode_reffpl      AS kode_reffpl,
                            dT.id_katalog       AS id_katalog,
                            SUM(dT.jumlah_item) AS jumlah_item
                        FROM db1.tdetailf_penerimaan AS dT
                        INNER JOIN db1.transaksif_penerimaan AS T ON dT.kode_reff = T.kode
                        WHERE
                            T.sts_testing = 0
                            AND T.sts_deleted = 0
                            AND T.ver_gudang = 1
                        GROUP BY
                            dT.kode_reffpl,
                            dT.id_katalog
                    ) AS T ON dP.kode_reff = T.kode_reffpl
                    WHERE
                        P.sts_deleted = 0
                        AND dP.id_katalog = T.id_katalog
                    GROUP BY
                        dP.kode_reffrenc,
                        dP.id_reffkatalog
                ) AS R ON A.kode_reff = R.kode_reffrenc
                LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
                LEFT JOIN db1.masterf_pabrik AS D ON A.id_pabrik = D.id
                LEFT JOIN db1.masterf_subjenisanggaran AS E ON B.id_jenisanggaran = E.id
                WHERE
                    B.sts_deleted = 0
                    AND B.thn_anggaran = :tahun
                    AND B.blnawal_anggaran >= :bulanAwal
                    AND B.blnakhir_anggaran <= :bulanAkhir
                    AND A.id_katalog = R.id_reffkatalog
                GROUP BY A.id_katalog
            ";
        }

        $params = [":tahun" => $tahunAnggaran, ":bulanAwal" => $bulanAwalAnggaran, ":bulanAkhir" => $bulanAkhirAnggaran];

        $connection = Yii::$app->dbFatma;
        $daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 44;
        $idJenisAnggaranSaatIni = "";

        foreach ($daftarDetailPerencanaan as $i => $dPerencanaan) {
            if ($idJenisAnggaranSaatIni != $dPerencanaan->idJenisAnggaran) {
                $idJenisAnggaranSaatIni = $dPerencanaan->idJenisAnggaran;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "jenis_obat" => $dPerencanaan->subjenisAnggaran,
                    "total_rencana" => 0,
                    "total_realisasi" => 0,
                    "total_selisih" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $subtotalRencana = $dPerencanaan->jumlahItem * $dPerencanaan->hargaItem;
            $subtotalRealisasi = $dPerencanaan->jumlahItemR * $dPerencanaan->hargaItemR;
            $subtotalSelisih = $subtotalRencana + $subtotalRealisasi;

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
                "subtotal_rencana" => $subtotalRencana,
                "subtotal_realisasi" => $subtotalRealisasi,
                "subtotal_selisih" => $subtotalSelisih,
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_rencana"] += $subtotalRencana;
            $daftarHalaman[$hJudul][$bJudul]["total_realisasi"] += $subtotalRealisasi;
            $daftarHalaman[$hJudul][$bJudul]["total_selisih"] += $subtotalSelisih;

            if ($b > $barisPerHalaman) {
                $b++;
            } else {
                $h++;
                $b = 0;
            }
        }

        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        $periodeBulan = ($bulanAwalAnggaran == $bulanAkhirAnggaran)
            ? $numToMonthName($bulanAwalAnggaran)
            : $numToMonthName($bulanAwalAnggaran) . "-" . $numToMonthName($bulanAkhirAnggaran);

        $view = new ReportRekap(
            daftarHalaman:           $daftarHalaman,
            tahunAnggaran:           $tahunAnggaran,
            periodeBulan:            strtoupper($periodeBulan),
            daftarDetailPerencanaan: $daftarDetailPerencanaan,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#reports the original method
     */
    public function actionReportItem(): string
    {
        [   "idKatalog" => $idKatalog,
            "bulanAwalAnggaran" => $bulanAwal,
            "bulanAkhirAnggaran" => $bulanAkhir,
            "tahunAnggaran" => $tahun,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff         AS kodeRef,
                A.id_katalog        AS idKatalog,
                C.nama_sediaan      AS namaSediaan,
                B.no_doc            AS noDokumen,
                B.tgl_doc           AS tanggalDokumen,
                D.no_doc            AS noPl,
                E.nama_pbf          AS namaPemasok,
                F.subjenis_anggaran AS subjenisAnggaran,
                B.blnawal_anggaran  AS bulanAwalAnggaran,
                B.blnakhir_anggaran AS bulanAkhirAnggaran,
                B.thn_anggaran      AS tahunAnggaran,
                B.id_jenisanggaran  AS idJenisAnggaran,
                B.nilai_akhir       AS nilaiAkhir
            FROM db1.tdetailf_perencanaan AS A
            INNER JOIN db1.transaksif_perencanaan AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
            LEFT JOIN db1.transaksif_pembelian AS D ON B.kode_reffpl = D.kode
            LEFT JOIN db1.masterf_pbf AS E ON D.id_pbf = E.id
            LEFT JOIN db1.masterf_subjenisanggaran AS F ON B.id_jenisanggaran = F.id
            WHERE
                A.id_katalog = :idKatalog
                AND (:bulanAwal = '' OR B.blnawal_anggaran = :bulanAwal)
                AND (:bulanAkhir = '' OR B.blnakhir_anggaran = :bulanAkhir)
                AND (:tahun = '' OR B.thn_anggaran = :tahun)
                AND B.sts_deleted = 0
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":tahun" => $tahun,
            ":bulanAwal" => $bulanAwal,
            ":bulanAkhir" => $bulanAkhir,
        ];
        $daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        if (!count($daftarDetailPerencanaan)) return "tidak ada data";

        $view = new ReportItem(daftarDetailPerencanaan: $daftarDetailPerencanaan);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/perencanaan.php#views the original method
     */
    public function actionViewItem(): string
    {
        assert($_POST["kode"], new MissingPostParamException("kode"));
        ["kode" => $kode] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                 AS kode,
                A.no_doc               AS noDokumen,
                A.tgl_doc              AS tanggalDokumen,
                A.tipe_doc             AS tipeDokumen,       -- in use
                A.sts_saved            AS statusSaved,
                A.sts_linked           AS statusLinked,
                A.sts_closed           AS statusClosed,
                A.sts_deleted          AS statusDeleted,
                A.sysdate_del          AS sysdateDeleted,
                A.userid_updt          AS useridUpdate,
                A.sysdate_updt         AS sysdateUpdate,
                A.blnawal_anggaran     AS bulanAwalAnggaran,  -- in use
                A.blnakhir_anggaran    AS bulanAkhirAnggaran, -- in use
                A.thn_anggaran         AS tahunAnggaran,      -- in use
                A.ppn                  AS ppn,                -- in use
                A.sts_revisi           AS statusRevisi,
                B.subjenis_anggaran    AS subjenisAnggaran,
                C.sumber_dana          AS sumberDana,
                D.subsumber_dana       AS subsumberDana,
                E.jenis_harga          AS jenisHarga,
                F.cara_bayar           AS caraBayar,
                IFNULL(G.no_doc, '-')  AS noSpk,
                IFNULL(H.nama_pbf, '') AS namaPemasok,
                A.nilai_pembulatan     AS nilaiPembulatan,
                A.nilai_total          AS nilaiTotal,
                A.nilai_diskon         AS nilaiDiskon,
                A.nilai_ppn            AS nilaiPpn,
                A.nilai_akhir          AS nilaiAkhir
            FROM db1.transaksif_perencanaan AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.transaksif_pembelian AS G ON A.kode_reffpl = G.kode
            LEFT JOIN db1.masterf_pbf AS H ON A.id_pbf = H.id
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $perencanaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$perencanaan) throw new DataNotExistException($kode);

        if ($perencanaan->tipeDokumen == 3) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode_reff              AS kodeRef,
                    A.id_katalog             AS idKatalog,
                    A.nama_generik           AS namaGenerik,
                    A.kode_reffpl            AS kodeRefPl,
                    A.kode_reffrenc          AS kodeRefRencana,
                    A.id_reffkatalog         AS idRefKatalog,
                    A.kemasan                AS kemasan,
                    A.id_pabrik              AS idPabrik,
                    A.id_kemasan             AS idKemasan,
                    A.isi_kemasan            AS isiKemasan,
                    A.id_kemasandepo         AS idKemasanDepo,
                    A.jumlah_item            AS jumlahItem,
                    A.jumlah_kemasan         AS jumlahKemasan,
                    A.harga_item             AS hargaItem,
                    A.harga_kemasan          AS hargaKemasan,
                    A.diskon_item            AS diskonItem,
                    A.diskon_harga           AS diskonHarga,
                    A.keterangan             AS keterangan,
                    A.userid_updt            AS useridUpdate,
                    A.sysdate_updt           AS sysdateUpdate,
                    KAT.nama_sediaan         AS namaSediaan,
                    PBK.nama_pabrik          AS namaPabrik,
                    KSAR.kode                AS kodeKemasanBesar,  -- prev: satuanjual
                    KCIL.kode                AS kodeKemasanKecil,  -- prev: satuan
                    IFNULL(P.jumlah_item, 0) AS jumlahPl,
                    IFNULL(R.jumlah_item, 0) AS jumlahPo,
                    IFNULL(T.jumlah_item, 0) AS jumlahTerima,
                    Q.jumlah_item            AS jumlahRencana,
                    tH.jumlah_item           AS jumlahHps,
                    P.kode_reffhps           AS kodeRefHps
                FROM db1.tdetailf_perencanaan AS A
                LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
                LEFT JOIN db1.tdetailf_pembelian AS P ON A.kode_reffpl = P.kode_reff
                LEFT JOIN db1.tdetailf_pengadaan AS tH ON P.kode_reffhps = tH.kode_reff
                LEFT JOIN db1.tdetailf_perencanaan AS Q ON A.kode_reffrenc = Q.kode_reff
                LEFT JOIN (
                    SELECT
                        X.kode_reffpl      AS kode_reffpl,
                        X.id_katalog       AS id_katalog,
                        SUM(X.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_perencanaan AS X
                    LEFT JOIN db1.transaksif_perencanaan AS P ON X.kode_reff = P.kode
                    WHERE P.sts_deleted = 0
                    GROUP BY X.kode_reffpl, X.id_katalog
                ) AS R ON A.kode_reffpl = R.kode_reffpl
                LEFT JOIN (
                    SELECT
                        X.kode_reffpl      AS kode_reffpl,
                        X.id_katalog       AS id_katalog,
                        SUM(X.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS X
                    LEFT JOIN db1.transaksif_penerimaan AS P ON X.kode_reff = P.kode
                    WHERE P.sts_deleted = 0
                    GROUP BY X.kode_reffpl, X.id_katalog
                ) AS T ON A.kode_reffpl = T.kode_reffpl
                WHERE
                    A.kode_reff = :kode
                    AND A.id_katalog = T.id_katalog
                    AND A.id_katalog = R.id_katalog
                    AND A.id_reffkatalog = Q.id_katalog
                    AND P.id_reffkatalog = tH.id_reffkatalog
                    AND A.id_katalog = P.id_katalog
                ORDER BY nama_sediaan
            ";

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode_reff                AS kodeRef,
                    A.id_katalog               AS idKatalog,
                    A.nama_generik             AS namaGenerik,
                    A.kode_reffpl              AS kodeRefPl,
                    A.kode_reffrenc            AS kodeRefRencana,
                    A.id_reffkatalog           AS idRefKatalog,
                    A.kemasan                  AS kemasan,
                    A.id_pabrik                AS idPabrik,
                    A.id_kemasan               AS idKemasan,
                    A.isi_kemasan              AS isiKemasan,
                    A.id_kemasandepo           AS idKemasanDepo,
                    A.jumlah_item              AS jumlahItem,
                    A.jumlah_kemasan           AS jumlahKemasan,    -- in use
                    A.harga_item               AS hargaItem,
                    A.harga_kemasan            AS hargaKemasan,     -- in use
                    A.diskon_item              AS diskonItem,
                    A.diskon_harga             AS diskonHarga,      -- in use
                    A.keterangan               AS keterangan,
                    A.userid_updt              AS useridUpdate,
                    A.sysdate_updt             AS sysdateUpdate,
                    KAT.nama_sediaan           AS namaSediaan,
                    PBK.nama_pabrik            AS namaPabrik,
                    KSAR.kode                  AS kodeKemasanBesar,  -- prev: satuanjual
                    KCIL.kode                  AS kodeKemasanKecil,  -- prev: satuan
                    IFNULL(pl.jumlah_item, 0)  AS jumlahPl,
                    IFNULL(po.jumlah_item, 0)  AS jumlahPo,
                    IFNULL(trm.jumlah_item, 0) AS jumlahTerima,
                    A.jumlah_item              AS jumlahRencana,
                    IFNULL(tH.jumlah_item, 0)  AS jumlahHps
                FROM db1.tdetailf_perencanaan AS A
                LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = id_katalog
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
                LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = A.id_kemasan
                LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
                LEFT JOIN (
                    SELECT
                        A.kode_reffrenc    AS kode_reffrenc,
                        A.id_reffkatalog   AS id_reffkatalog,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_pengadaan AS A
                    LEFT JOIN db1.transaksif_pengadaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffrenc = :kode
                    GROUP BY A.kode_reffrenc, A.id_reffkatalog
                ) AS tH ON A.kode_reff = tH.kode_reffrenc
                LEFT JOIN (
                    SELECT
                        A.kode_reff        AS kode_reffpl,
                        A.kode_reffrenc    AS kode_reffrenc,
                        A.id_reffkatalog   AS id_reffkatalog,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_pembelian AS A
                    LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffrenc = :kode
                    GROUP BY A.kode_reffrenc, A.id_reffkatalog
                ) AS pl ON A.kode_reff = pl.kode_reffrenc
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.kode_reffrenc    AS kode_reffrenc,
                        A.id_reffkatalog   AS id_reffkatalog,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_pemesanan AS A
                    LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffrenc = :kode
                    GROUP BY A.kode_reffrenc, A.id_reffkatalog
                ) AS po ON A.kode_reff = po.kode_reffrenc
                LEFT JOIN (
                    SELECT
                        A.kode_reffpl      AS kode_reffpl,
                        A.kode_reffrenc    AS kode_reffrenc,
                        A.id_reffkatalog   AS id_reffkatalog,
                        A.id_katalog       AS id_katalog,
                        SUM(A.jumlah_item) AS jumlah_item
                    FROM db1.tdetailf_penerimaan AS A
                    LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                    WHERE
                        B.sts_deleted = 0
                        AND A.kode_reffrenc = :kode
                    GROUP BY A.kode_reffrenc, A.id_katalog
                ) AS trm ON A.kode_reff = trm.kode_reffrenc
                WHERE
                    A.kode_reff = :kode
                    AND A.id_katalog = trm.id_reffkatalog
                    AND A.id_katalog = po.id_reffkatalog
                    AND A.id_katalog = pl.id_reffkatalog
                    AND A.id_katalog = tH.id_reffkatalog
                ORDER BY nama_sediaan
            ";
        }
        $params = [":kode" => $kode];
        $daftarDetailPerencanaan = $connection->createCommand($sql, $params)->queryAll();

        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();

        $anggaran = ($perencanaan->bulanAwalAnggaran == $perencanaan->bulanAkhirAnggaran)
            ? $numToMonthName($perencanaan->bulanAwalAnggaran) . " " . $perencanaan->tahunAnggaran
            : $numToMonthName($perencanaan->bulanAwalAnggaran) . "-" . $numToMonthName($perencanaan->bulanAkhirAnggaran) . " " . $perencanaan->tahunAnggaran;

        $map = [
            0 => "Perencanaan Tahunan",
            1 => "Perencanaan Bulanan",
            2 => "Perencanaan Cito",
            3 => "Repeat Order",
        ];
        $tipeDokumen = $map[$perencanaan->tipeDokumen] ?? "";

        $totalSebelumDiskon = 0;
        $totalDiskon = 0;
        $totalSetelahDiskon = 0;
        foreach ($daftarDetailPerencanaan as $dPerencanaan1) {
            $totalSebelumDiskon += $dPerencanaan1->jumlahKemasan * $dPerencanaan1->hargaKemasan;
            $totalDiskon += $dPerencanaan1->diskonHarga;
            $totalSetelahDiskon += ($dPerencanaan1->jumlahKemasan * $dPerencanaan1->hargaKemasan) - $dPerencanaan1->diskonHarga;
        }

        $totalPpn = $perencanaan->ppn * $totalSetelahDiskon / 100;
        $totalSetelahPpn = $totalSetelahDiskon - $totalPpn;
        $pembulatan = $totalSetelahPpn - floor($totalSetelahPpn);
        $totalSetelahPembulatan = floor($totalSetelahPpn);

        $view = new ViewItem(
            perencanaanViewWidgetId: Yii::$app->actionToId([PerencanaanUiController::class, "actionView"]),
            pengadaanViewWidgetId:   Yii::$app->actionToId([PengadaanUiController::class, "actionView"]),
            pengadaanTableWidgetId:  Yii::$app->actionToId([PengadaanUiController::class, "actionTable"]),
            pembelianViewWidgetId:   Yii::$app->actionToId([PembelianUiController::class, "actionView"]),
            pembelianTableWidgetId:  Yii::$app->actionToId([PembelianUiController::class, "actionTable"]),
            penerimaanTableWidgetId: Yii::$app->actionToId([PenerimaanUiController::class, "actionTable"]),
            perencanaan:             $perencanaan,
            anggaran:                $anggaran,
            tipeDokumen:             $tipeDokumen,
            daftarDetailPerencanaan: $daftarDetailPerencanaan,
            totalSebelumDiskon:      $totalSebelumDiskon,
            totalDiskon:             $totalDiskon,
            totalSetelahDiskon:      $totalSetelahDiskon,
            totalPpn:                $totalPpn,
            totalSetelahPpn:         $totalSetelahPpn,
            pembulatan:              $pembulatan,
            totalSetelahPembulatan:  $totalSetelahPembulatan,
        );
        return $view->__toString();
    }
}
