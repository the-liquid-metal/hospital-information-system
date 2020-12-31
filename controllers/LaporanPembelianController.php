<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\DataNotExistException;
use tlm\his\FatmaPharmacy\views\LaporanPembelian\{ReportLaporanAkhir, ViewPlItem};
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
class LaporanPembelianController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionRealisasiPlTableData(): string
    {
        ["kode" => $kode] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $arrKode = explode(",", $kode);
        if (count($arrKode)) {
            $arrKode = implode("', '", $arrKode);
            $kodeRefPl = "A.kode_reffpl IN ('$arrKode')";
            $kodeRef = "A.kode_reff IN ('$arrKode')";
        } else {
            $kodeRefPl = "TRUE";
            $kodeRef = "TRUE";
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                B.no_doc                                 AS noDokumen,
                B.tgl_jatuhtempo                         AS tanggalJatuhTempo,
                C.nama_pbf                               AS namaPemasok,
                D.nama_sediaan                           AS namaSediaan,
                E.nama_pabrik                            AS namaPabrik,
                A.kemasan                                AS kemasan,
                A.jumlah_item                            AS jumlahPl,
                A.harga_item                             AS hargaPl,
                F.kode                                   AS satuan,
                IFNULL(G.jumlah_item, 0)                 AS jumlahTerima,
                IFNULL(G.harga_item, 0)                  AS hargaTerima,
                A.jumlah_item - IFNULL(G.jumlah_item, 0) AS jumlahSisa,
                H.jumlah_item                            AS jumlahRencana,
                A.id_reffkatalog                         AS idRefKatalog
            FROM db1.tdetailf_pembelian AS A
            INNER JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_katalog AS D ON A.id_katalog = D.kode
            LEFT JOIN db1.masterf_pabrik AS E ON D.id_pabrik = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON D.id_kemasankecil = F.id
            LEFT JOIN (
                SELECT
                    A.kode_reffpl,
                    A.id_katalog,
                    IFNULL(SUM(A.jumlah_item), 0) jumlah_item,
                    IFNULL(AVG(A.harga_item), 0) harga_item
                FROM db1.tdetailf_penerimaan AS A
                INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND $kodeRefPl
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS G ON A.kode_reff = G.kode_reffpl
            LEFT JOIN db1.tdetailf_perencanaan AS H ON A.kode_reffrenc = H.kode_reff
            WHERE
                B.sts_deleted = 0
                AND A.id_katalog = H.id_katalog
                AND A.id_katalog = G.id_katalog
                AND $kodeRef
            ORDER BY B.no_doc ASC, D.nama_barang ASC
        ";
        $daftarDetailPembelian = $connection->createCommand($sql)->queryAll();
        return json_encode($daftarDetailPembelian);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionItemPlTableData(): string
    {
        assert($_POST["id_katalog"], new MissingPostParamException("id_katalog"));
        ["id_katalog" => $idKatalog] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode_reff      AS kodeRef,
                A.kemasan        AS kemasan,
                A.jumlah_item    AS jumlahItem,
                A.jumlah_kemasan AS jumlahKemasan,
                A.harga_item     AS hargaItem,
                A.harga_kemasan  AS hargaKemasan,
                A.diskon_item    AS diskonItem,
                A.diskon_harga   AS diskonHarga,
                B.sts_closed     AS statusClosed,
                B.no_doc         AS noDokumen,
                B.tgl_jatuhtempo AS tanggalJatuhTempo,
                C.nama_pbf       AS namaPemasok,
                E.nama_pabrik    AS namaPabrik
            FROM db1.tdetailf_pembelian AS A
            INNER JOIN db1.transaksif_pembelian AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_katalog AS D ON A.id_katalog = D.kode
            LEFT JOIN db1.masterf_pabrik AS E ON A.id_pabrik = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON A.id_kemasan = F.id
            LEFT JOIN db1.masterf_kemasan AS G ON A.id_kemasandepo = G.id
            WHERE
                B.sts_deleted = 0
                AND A.id_katalog = :idKatalog
            ORDER BY B.sts_closed DESC, B.no_doc ASC
        ";
        $params = [":idKatalog" => $idKatalog];
        $daftarDetailPembelian = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarDetailPembelian);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#views    the original method
     */
    public function actionViewPlItem(): string
    {
        assert($_POST["kode"], new MissingPostParamException("kode"));
        ["kode" => $kode] = Yii::$app->request->post();
        ["revisike" => $revisiKe, "addke" => $adendumKe] = Yii::$app->request->get();

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                  AS kode,
                A.no_doc                AS noDokumen,
                I.no_doc                AS noHps,
                I.no_docreff            AS noDokumenRef,
                A.tgl_doc               AS tanggalDokumen,
                A.tgl_jatuhtempo        AS tanggalJatuhTempo,
                A.tipe_doc              AS tipeDokumen,        -- in use
                A.nilai_pembulatan      AS nilaiPembulatan,
                A.sts_saved             AS statusSaved,
                A.sts_linked            AS statusLinked,
                A.sts_closed            AS statusClosed,
                A.sts_deleted           AS statusDeleted,
                A.sysdate_del           AS sysdateDeleted,
                A.userid_updt           AS useridUpdate,
                A.sysdate_updt          AS sysdateUpdate,
                A.blnawal_anggaran      AS bulanAwalAnggaran,  -- in use
                A.blnakhir_anggaran     AS bulanAkhirAnggaran, -- in use
                A.thn_anggaran          AS tahunAnggaran,      -- in use
                A.ppn                   AS ppn,
                B.subjenis_anggaran     AS subjenisAnggaran,
                C.sumber_dana           AS sumberDana,
                D.subsumber_dana        AS subsumberDana,
                E.jenis_harga           AS jenisHarga,
                A.id_jenisharga         AS idJenisHarga,       -- in use
                F.cara_bayar            AS caraBayar,
                IFNULL(G.nama_pbf, '-') AS namaPemasok,
                A.nilai_total           AS nilaiTotal,
                A.nilai_diskon          AS nilaiDiskon,
                A.nilai_ppn             AS nilaiPpn,
                A.nilai_akhir           AS nilaiAkhir,
                A.nilai_pembulatan      AS nilaiPembulatan,
                A.revisike              AS revisiKe,
                A.keterangan            AS keterangan,
                A.keterangan_rev        AS keteranganRevisi,
                A.ver_revisi            AS verRevisi
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN db1.masterf_subjenisanggaran AS B ON A.id_jenisanggaran = B.id
            LEFT JOIN db1.masterf_sumberdana AS C ON A.id_sumberdana = C.id
            LEFT JOIN db1.masterf_subsumberdana AS D ON A.id_subsumberdana = D.id
            LEFT JOIN db1.masterf_jenisharga AS E ON A.id_jenisharga = E.id
            LEFT JOIN db1.masterf_carabayar AS F ON A.id_carabayar = F.id
            LEFT JOIN db1.masterf_pbf AS G ON A.id_pbf = G.id
            LEFT JOIN db1.transaksif_pengadaan AS I ON I.kode = A.kode_reffhps
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $pembelian = $connection->createCommand($sql, $params)->queryOne();
        if (!$pembelian) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. " 
            SELECT
                A.revisike                                                   AS revisiKe,
                A.kode_reff                                                  AS kodeRef,
                A.id_katalog                                                 AS idKatalog,
                A.kode_reffrenc                                              AS kodeRefRencana,
                A.kode_reffhps                                               AS kodeRefHps,
                A.id_reffkatalog                                             AS idRefKatalog,
                A.no_urut                                                    AS noUrut,
                A.kemasan                                                    AS kemasan,
                A.id_pabrik                                                  AS idPabrik,
                A.id_kemasan                                                 AS idKemasan,
                A.isi_kemasan                                                AS isiKemasan,
                A.id_kemasandepo                                             AS idKemasanDepo,
                A.jumlah_item                                                AS jumlahItem,
                A.jumlah_kemasan                                             AS jumlahKemasan,
                A.harga_item                                                 AS hargaItem,
                A.harga_kemasan                                              AS hargaKemasan,
                A.diskon_item                                                AS diskonItem,
                A.diskon_harga                                               AS diskonHarga,
                A.sts_iclosed                                                AS statusInputClosed,
                A.sysdate_icls                                               AS inputClosedTime,
                A.keterangan                                                 AS keterangan,
                A.userid_updt                                                AS updatedById,
                A.sysdate_updt                                               AS updatedTime,
                A.jumlah_kemasan                                             AS jumlahKemasan,
                A.harga_kemasan                                              AS hargaKemasan,
                A.diskon_harga                                               AS diskonHarga,
                KAT.nama_sediaan                                             AS namaSediaan,
                KAT.jumlah_itembeli                                          AS jumlahItemBeli,
                KAT.jumlah_itembonus                                         AS jumlahItemBonus,
                PBK.nama_pabrik                                              AS namaPabrik,
                KBSR.kode                                                    AS satuanJual,
                KKCL.kode                                                    AS satuan,
                IFNULL(A.jumlah_item, 0)                                     AS jumlahPl,
                IFNULL(renc.jumlah_item, 0)                                  AS jumlahRencana,
                IFNULL(trm.jumlah_item, 0)                                   AS jumlahTerima,
                IFNULL(tH.jumlah_item, 0)                                    AS jumlahHps,
                IFNULL(tS.jumlah_item, 0)                                    AS jumlahDo,
                IFNULL(tRet.jumlah_item, 0)                                  AS jumlahRetur,
                (A.jumlah_item / KAT.jumlah_itembeli * KAT.jumlah_itembonus) AS jumlahBonus
            FROM db1.tdetailf_revpembelian AS A
            LEFT JOIN db1.masterf_katalog AS KAT ON KAT.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KBSR ON KBSR.id = A.id_kemasan
            LEFT JOIN db1.masterf_kemasan AS KKCL ON KKCL.id = KAT.id_kemasankecil
            LEFT JOIN db1.tdetailf_pengadaan AS tH ON A.kode_reffhps = tH.kode_reff
            LEFT JOIN db1.tdetailf_perencanaan AS renc ON A.kode_reffrenc = renc.kode_reff
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_pemesanan AS A
                LEFT JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS tS ON A.kode_reff = tS.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_penerimaan AS A
                LEFT JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS trm ON A.kode_reff = trm.kode_reffpl
            LEFT JOIN (
                SELECT
                    A.kode_reffpl      AS kode_reffpl,
                    A.id_katalog       AS id_katalog,
                    SUM(A.jumlah_item) AS jumlah_item
                FROM db1.tdetailf_return AS A
                LEFT JOIN db1.transaksif_return AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reffpl, A.id_katalog
            ) AS tRet ON A.kode_reff = tRet.kode_reffpl
            WHERE
                A.kode_reff = :kode
                AND (:val = '' OR A.revisike = :val)
                AND A.id_katalog = tRet.id_katalog
                AND A.id_katalog = trm.id_katalog
                AND A.id_katalog = tS.id_katalog
                AND A.id_reffkatalog = renc.id_katalog
                AND A.id_reffkatalog = tH.id_reffkatalog
            ORDER BY
                A.id_reffkatalog,
                A.no_urut
        ";
        if (isset($adendumKe)) {
            $sql = str_replace("FROM db1.tdetailf_revpembelian", "FROM rsupf_revisi.tdetailf_revaddpembelian", $sql);
            $sql = str_replace("AND (:val = '' OR A.revisike = :val)", "AND (:val = '' OR A.adendumke = :val)", $sql);
        } elseif (isset($revisiKe)) {
            // nothing to replace
        } else {
            $sql = str_replace("FROM db1.tdetailf_revpembelian", "FROM db1.tdetailf_pembelian", $sql);
            $sql = str_replace("AND (:val = '' OR A.revisike = :val)", "", $sql);
        }
        $params = [
            ":kode" => $kode,
            ":val" => $adendumKe || $revisiKe,
        ];
        $daftarData = $connection->createCommand($sql, $params)->queryAll();

        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();

        $anggaran = ($pembelian->bulanAwalAnggaran == $pembelian->bulanAkhirAnggaran)
            ? $numToMonthName($pembelian->bulanAwalAnggaran) . " " . $pembelian->tahunAnggaran
            : $numToMonthName($pembelian->bulanAwalAnggaran) . "-" . $numToMonthName($pembelian->bulanAkhirAnggaran) . " " . $pembelian->tahunAnggaran;

        if ($pembelian->tipeDokumen == "0" && $pembelian->idJenisHarga == 2) {
            $jenisPl = "Kontrak Harga E-Katalog";
        } elseif ($pembelian->tipeDokumen == 1 && $pembelian->idJenisHarga == 2) {
            $jenisPl = "Kontrak E-Katalog";
        } elseif ($pembelian->tipeDokumen == 1) {
            $jenisPl = "Kontrak";
        } elseif ($pembelian->tipeDokumen == 2) {
            $jenisPl = "Surat Perintah Kerja";
        } elseif ($pembelian->tipeDokumen == 3) {
            $jenisPl = "Surat Pemesanan";
        } else {
            $jenisPl = "Kontrak Harga";
        }

        $totalSebelumDiskon = 0;
        $totalDiskon = 0;
        $totalSetelahDiskon = 0;
        foreach ($daftarData as $r) {
            $totalSebelumDiskon += $r->jumlahKemasan * $r->hargaKemasan;
            $totalDiskon += $r->diskonHarga;
            $totalSetelahDiskon += ($r->jumlahKemasan * $r->hargaKemasan) - $r->diskonHarga;
        }

        $totalPpn = $pembelian->ppn * $totalSetelahDiskon / 100;
        $totalSetelahPpn = $totalSetelahDiskon - $totalPpn;
        $pembulatan = $totalSetelahPpn - floor($totalSetelahPpn);
        $totalSetelahPembulatan = floor($totalSetelahPpn);

        $view = new ViewPlItem(
            editPembelianFormWidgetId: Yii::$app->actionToId([PembelianUiController::class, "actionForm"]),
            printPembelianWidgetId:    Yii::$app->actionToId([PembelianController::class, "actionPrint"]),
            viewPerencanaanWidgetId:   Yii::$app->actionToId([PerencanaanUiController::class, "actionView"]),
            viewPengadaanWidgetId:     Yii::$app->actionToId([PengadaanUiController::class, "actionView"]),
            penerimaanTableWidgetId:   Yii::$app->actionToId([PenerimaanUiController::class, "actionTable"]),
            pembelian:                 $pembelian,
            anggaran:                  $anggaran,
            jenisPl:                   $jenisPl,
            daftarData:                $daftarData,
            totalSebelumDiskon:        $totalSebelumDiskon,
            totalDiskon:               $totalDiskon,
            totalSetelahDiskon:        $totalSetelahDiskon,
            totalPpn:                  $totalPpn,
            totalSetelahPpn:           $totalSetelahPpn,
            pembulatan:                $pembulatan,
            totalSetelahPembulatan:    $totalSetelahPembulatan,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionReportLaporanAkhir(): string
    {
        [   "kode" => $kode,
            "idJenisAnggaran" => $idJenisAnggaran,
            "tanggalMulai" => $tanggalMulai,
            "tanggalAkhir" => $tanggalAkhir,
            "subjenisAnggaran" => $subjenisAnggaran,
        ] = Yii::$app->request->post();
        $kode = str_replace(",", "','", $kode);
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode                                              AS kode,         -- in use
                A.no_doc                                            AS noDokumen,
                (iA.Ht - iA.Hd) * A.ppn                             AS HPpn,
                ROUND(iA.Ht - iA.Hd + ((iA.Ht - iA.Hd) * A.ppn), 2) AS nilaiAkhir,   -- in use
                B.kode_reff                                         AS kodeDo,
                B.no_doc                                            AS noDo,         -- in use
                ROUND(B.Ht - B.Hd + B.H_ppn, 0)                     AS nilaiAkhirDo, -- in use
                D.nama_pbf                                          AS namaPemasok
            FROM db1.transaksif_pembelian AS A
            LEFT JOIN (
                SELECT
                    A.kode_reff                                                 AS kode_reff,
                    SUM(A.jumlah_kemasan * A.harga_kemasan)                     AS Ht,
                    SUM(A.jumlah_kemasan * A.harga_kemasan * A.diskon_item/100) AS Hd
                FROM db1.tdetailf_pembelian AS A
                GROUP BY A.kode_reff
            ) AS iA ON A.kode = iA.kode_reff
            LEFT JOIN db1.masterf_pbf AS D ON A.id_pbf = D.id
            LEFT JOIN (
                SELECT
                    A.kode_reffpl                                                 AS kode_reffpl,
                    A.kode_reff                                                   AS kode_reff,
                    B.no_doc                                                      AS no_doc,
                    SUM(A.jumlah_kemasan * A.harga_kemasan)                       AS Ht,
                    SUM(A.jumlah_kemasan * A.harga_kemasan * A.diskon_item / 100) AS Hd,
                    (SUM(A.jumlah_kemasan * A.harga_kemasan) - SUM(A.jumlah_kemasan * A.harga_kemasan * A.diskon_item/100)) * B.ppn / 100 AS H_ppn
                FROM db1.tdetailf_pemesanan AS A
                INNER JOIN db1.transaksif_pemesanan AS B ON A.kode_reff = B.kode
                WHERE B.sts_deleted = 0
                GROUP BY A.kode_reff
            ) AS B ON A.kode = B.kode_reffpl
            WHERE
                A.sts_deleted = 0
                AND A.tgl_doc >= :tanggalMulai
                AND A.tgl_doc <= :tanggalAkhir
                AND ('$kode' = '' OR A.kode IN ('$kode'))
                AND (:idJenisAnggaran = '' OR A.id_jenisanggaran = :idJenisAnggaran)
        ";
        $params = [
            ":idJenisAnggaran" => $idJenisAnggaran,
            ":tanggalMulai" => $toSystemDate($tanggalMulai),
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir),
        ];
        $daftarPembelian = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $totalPl = 0;
        $totalDo = 0;
        $totalSelisih = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 29;
        $kodeSaatIni = "";

        foreach ($daftarPembelian as $i => $pembelian) {
            if ($kodeSaatIni != $pembelian->kode) {
                $kodeSaatIni = $pembelian->kode;
                $noData = 1;

                $daftarHalaman[$h][$b] = [
                    "no" => $noJudul++ .".",
                    "no_do" => $pembelian->noDo,
                    "nilai_akhir_do" => $pembelian->nilaiAkhirDo
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

            $totalPl += $pembelian->nilaiAkhir;
            $totalDo += $pembelian->nilaiAkhirDo;
            $totalSelisih += $pembelian->nilaiAkhir - $pembelian->nilaiAkhirDo;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        if (!$daftarHalaman) return "tidak ada data";

        $view = new ReportLaporanAkhir(
            tanggalAwal:      $tanggalMulai,
            tanggalAkhir:     $tanggalAkhir,
            subjenisAnggaran: $subjenisAnggaran ?? "",
            daftarHalaman:    $daftarHalaman,
            daftarPembelian:  $daftarPembelian,
            totalPl:          $totalPl,
            totalDo:          $totalDo,
            totalSelisih:     $totalSelisih,
        );
        return $view->__toString();
    }
}
