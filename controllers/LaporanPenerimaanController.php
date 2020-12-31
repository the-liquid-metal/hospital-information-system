<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\LaporanPenerimaan\{
    ReportDepo,
    ReportGasBukuInduk,
    ReportGasPerItem,
    ReportGasPerJenis,
    ReportPenerimaanBukuInduk,
    ReportPenerimaanRekapitulasi,
    ReportPerPbf
};
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
class LaporanPenerimaanController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#reports    the original method
     */
    public function actionReportPenerimaanRekap(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "status" => $status,
            "jenis" => $jenis,
            "tahap" => $tahap,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                                                 AS idKatalog,
                C.nama_sediaan                                                               AS namaSediaan,
                D.nama_pabrik                                                                AS namaPabrik,
                E.kode                                                                       AS satuan,
                F.subjenis_anggaran                                                          AS subjenisAnggaran,
                G.kode                                                                       AS kodeKelompok,     -- in use
                G.kelompok_barang                                                            AS kelompokBarang,   -- in use
                SUM(A.jumlah_item)                                                           AS totalItem,
                ROUND(SUM(A.harga_setelahdiskon), 2)                                         AS totalHarga,       -- in use
                ROUND(SUM(A.harga_setelahdiskon * A.ppn / 100), 2)                           AS totalPpn,         -- in use
                ROUND(SUM(A.harga_setelahdiskon + (A.harga_setelahdiskon * A.ppn / 100)), 2) AS totalHargaAkhir
            FROM (
                SELECT
                    A.id_katalog,
                    A.jumlah_item,
                    (A.jumlah_item * A.harga_item) - (A.jumlah_item * A.harga_item * A.diskon_item / 100) AS harga_setelahdiskon,
                    B.ppn
                FROM db1.tdetailf_penerimaan AS A
                INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
                WHERE
                    B.sts_deleted = 0
                    AND B.sts_testing = 0
                    AND (
                       (:jenis     = 'penerimaanBarang'     AND tgl_doc          >= :tanggalAwal AND tgl_doc          <= :tanggalAkhir)
                        OR (:jenis = 'verifikasiPenerimaan' AND ver_tglterima    >= :tanggalAwal AND ver_tglterima    <= :tanggalAkhir)
                        OR (:jenis = 'verifikasiGudang'     AND ver_tglgudang    >= :tanggalAwal AND ver_tglgudang    <= :tanggalAkhir)
                        OR (:jenis = 'verifikasiAkuntansi'  AND ver_tglakuntansi >= :tanggalAwal AND ver_tglakuntansi <= :tanggalAkhir)
                    )
                    AND (
                        :status = ''
                        OR (:tahap = 'verifikasiPenerimaan' AND ver_terima    = :status)
                        OR (:tahap = 'verifikasiGudang'     AND ver_gudang    = :status)
                        OR (:tahap = 'verifikasiAkuntansi'  AND ver_akuntansi = :status)
                    )
            ) AS A
            LEFT JOIN db1.masterf_katalog AS C ON A.id_katalog = C.kode
            LEFT JOIN db1.masterf_pabrik AS D ON C.id_pabrik = D.id
            LEFT JOIN db1.masterf_kemasan AS E ON C.id_kemasankecil = E.id
            LEFT JOIN db1.masterf_subjenisanggaran AS F ON C.id_jenisbarang = F.id
            LEFT JOIN db1.masterf_kelompokbarang AS G ON C.id_kelompokbarang = G.id
            GROUP BY A.id_katalog
            ORDER BY
                C.id_kelompokbarang,
                A.id_katalog
        ";
        $params = [
            ":jenis" => $jenis,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
            ":tahap" => $tahap,
            ":status" => $status,
        ];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $totalJumlah = 0;
        $totalPpn = 0;
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $kodeSaatIni = "";
        $barisPerHalaman = 50;

        foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
            if ($kodeSaatIni != $dPenerimaan->kodeKelompok) {
                $kodeSaatIni = $dPenerimaan->kodeKelompok;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_kelompok" => $dPenerimaan->kelompokBarang,
                    "subtotal_nilai" => 0,
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
            $daftarHalaman[$hJudul][$bJudul]["subtotal_nilai"] += $dPenerimaan->totalHarga + $dPenerimaan->totalPpn;

            $totalJumlah += $dPenerimaan->totalHarga;
            $totalPpn += $dPenerimaan->totalPpn;
            $totalNilai += $dPenerimaan->totalHarga + $dPenerimaan->totalPpn;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportPenerimaanRekapitulasi(
            daftarHalaman:          $daftarHalaman,
            tanggalAwal:            $tanggalAwal,
            tanggalAkhir:           $tanggalAkhir,
            daftarDetailPenerimaan: $daftarDetailPenerimaan,
            jumlahHalaman:          count($daftarHalaman),
            totalJumlah:            $totalJumlah,
            totalPpn:               $totalPpn,
            totalNilai:             $totalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#reports    the original method
     */
    public function actionReportPenerimaanBukuInduk(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "status" => $status,
            "jenis" => $jenis,
            "tahap" => $tahap,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                B.kode             AS kodeTerima,       -- in use
                B.no_doc           AS noDokumen,        -- in use
                B.tgl_doc          AS tanggalDokumen,
                B.ver_terima       AS verTerima,
                B.ver_tglterima    AS verTanggalTerima, -- in use
                B.ver_gudang       AS verGudang,
                B.ver_tglgudang    AS verTanggalGudang, -- in use
                B.ppn              AS ppn,              -- in use
                B.nilai_total      AS nilaiTotal,
                B.nilai_diskon     AS nilaiDiskon,
                B.nilai_ppn        AS nilaiPpn,
                B.nilai_pembulatan AS nilaiPembulatan,
                B.nilai_akhir      AS nilaiAkhir,
                C.nama_pbf         AS namaPemasok,      -- in use
                A.id_katalog       AS idKatalog,
                D.nama_sediaan     AS namaSediaan,      -- in use
                E.nama_pabrik      AS namaPabrik,       -- in use
                A.jumlah_item      AS jumlahItem,       -- in use
                A.jumlah_kemasan   AS jumlahKemasan,
                A.harga_kemasan    AS hargaKemasan,
                F.kode             AS satuan,
                A.harga_item       AS hargaItem,        -- in use
                A.diskon_item      AS diskonItem        -- in use
            FROM db1.tdetailf_penerimaan AS A
            INNER JOIN db1.transaksif_penerimaan AS B ON A.kode_reff = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON B.id_pbf = C.id
            LEFT JOIN db1.masterf_katalog AS D ON A.id_katalog = D.kode
            LEFT JOIN db1.masterf_pabrik AS E ON D.id_pabrik = E.id
            LEFT JOIN db1.masterf_kemasan AS F ON D.id_kemasankecil = F.id
            WHERE
                B.sts_deleted = 0
                AND B.sts_testing = 0
                AND (
                   (:jenis     = 'penerimaanBarang'     AND tgl_doc          >= :tanggalAwal AND tgl_doc          <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiPenerimaan' AND ver_tglterima    >= :tanggalAwal AND ver_tglterima    <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiGudang'     AND ver_tglgudang    >= :tanggalAwal AND ver_tglgudang    <= :tanggalAkhir)
                    OR (:jenis = 'verifikasiAkuntansi'  AND ver_tglakuntansi >= :tanggalAwal AND ver_tglakuntansi <= :tanggalAkhir)
                )
                AND (
                    :status = ''
                    OR (:tahap = 'verifikasiPenerimaan' AND ver_terima    = :status)
                    OR (:tahap = 'verifikasiGudang'     AND ver_gudang    = :status)
                    OR (:tahap = 'verifikasiAkuntansi'  AND ver_akuntansi = :status)
                )
            ORDER BY B.ver_tglterima ASC, B.tgl_doc ASC
        ";
        $params = [
            ":jenis" => $jenis,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
            ":tahap" => $tahap,
            ":status" => $status,
        ];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalJumlah = 0;
        $grandTotalPpn = 0;
        $grandTotalNilai = 0;

        $jumlahData = count($daftarDetailPenerimaan);
        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $kodeTerimaSaatIni = "";
        $barisPerHalaman = 44;
        $maksHurufBarang = 36;
        $maksHurufPabrik = 16;

        foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
            if ($kodeTerimaSaatIni != $dPenerimaan->kodeTerima) {
                $kodeTerimaSaatIni = $dPenerimaan->kodeTerima;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $jumlahBarisBarang = ceil(strlen($dPenerimaan->namaSediaan) / $maksHurufBarang);
                $jumlahBarisPabrik = ceil(strlen($dPenerimaan->namaPabrik) / $maksHurufPabrik);
                $butuhBaris = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "kode_terima" => $dPenerimaan->kodeTerima,
                    "no_doc" => $dPenerimaan->noDokumen,
                    "ver_tglterima" => $dPenerimaan->verTanggalTerima,
                    "ver_tglgudang" => $dPenerimaan->verTanggalGudang,
                    "nama_pbf" => $dPenerimaan->namaPemasok,
                    "total_jumlah" => 0,
                    "total_ppn" => 0,
                    "total_nilai" => 0,
                ];

                if (($b + $butuhBaris) > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $sebelumDiskon = $dPenerimaan->hargaItem * $dPenerimaan->jumlahItem;
            $diskon = $sebelumDiskon * ($dPenerimaan->diskonItem / 100);
            $jumlah = $sebelumDiskon - $diskon;
            $ppn = $jumlah * ($dPenerimaan->ppn / 100);

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
                "subtotal_jumlah" => $jumlah,
                "subtotal_ppn" => $ppn,
                "subtotal_nilai" => $jumlah + $ppn,
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_jumlah"] += $jumlah;
            $daftarHalaman[$hJudul][$bJudul]["total_ppn"] += $ppn;
            $daftarHalaman[$hJudul][$bJudul]["total_nilai"] += $jumlah + $ppn;

            $grandTotalPpn += $ppn;
            $grandTotalJumlah += $jumlah;
            $grandTotalNilai += $jumlah + $ppn;

            if ($i + 1 == $jumlahData) break;
            $dataBerikutnya = $daftarDetailPenerimaan[$i + 1];
            $bedaJudul = $dPenerimaan->kode != $dataBerikutnya->kode; // MISSING / AMBIGUOUS

            $jumlahBarisBarang = ceil(strlen($dataBerikutnya->namaSediaan) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($dataBerikutnya->namaPabrik) / $maksHurufPabrik);
            $butuhBaris = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

            if ($bedaJudul and $b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } elseif (($b + $butuhBaris) > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportPenerimaanBukuInduk(
            daftarHalaman:          $daftarHalaman,
            tanggalAwal:            $tanggalAwal,
            tanggalAkhir:           $tanggalAkhir,
            daftarDetailPenerimaan: $daftarDetailPenerimaan,
            jumlahHalaman:          count($daftarHalaman),
            grandTotalJumlah:       $grandTotalJumlah,
            grandTotalPpn:          $grandTotalPpn,
            grandTotalNilai:        $grandTotalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#LaporanPerPbf    the original method (not exist)
     * last exist of actionLaporanPerPbfData: commit-e37d34f4
     */
    public function actionReportPerPemasok(): string
    {
        ["tanggalAwal" => $tanggalAwal, "tanggalAkhir" => $tanggalAkhir, "urutan" => $radioUrut] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        switch ($radioUrut) {
            case "dist":  $order = "C.nama_pbf"; break;
            case "nospk": $order = "B.no_doc"; break;
            case "noba":  $order = "A.no_doc"; break;
            case "nofkt": $order = "A.no_faktur"; break;
            case "nosj":  $order = "A.no_suratjalan"; break;
            default:      $order = "A.no_suratjalan";
        }

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.no_doc                                                           AS noBa,
                UCASE(SUBSTRING_INDEX(SUBSTRING_INDEX(A.no_doc, '/', 2), '/', -1)) AS kodePenerimaan, -- in use
                A.no_faktur                                                        AS noFaktur,
                A.no_suratjalan                                                    AS noSuratJalan,
                A.nilai_akhir                                                      AS nilaiAkhir,     -- in use
                B.no_doc                                                           AS noSpk,
                C.nama_pbf                                                         AS namaPemasok
            FROM db1.transaksif_penerimaan AS A
            LEFT JOIN db1.transaksif_pembelian AS B ON A.kode_reffpl = B.kode
            LEFT JOIN db1.masterf_pbf AS C ON A.id_pbf = C.id
            WHERE
                A.sts_deleted = 0
                AND A.sts_testing = 0
                AND ver_terima = 1
                AND ver_gudang = 1
                AND A.tgl_doc >= :tanggalAwal
                AND A.tgl_doc <= :tanggalAkhir
            ORDER BY
                kodePenerimaan,
                $order ASC
        ";
        $params = [
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $kategori = function (string $param): string {
            switch ($param) {
                case "OB": return "Obat";
                case "ALK": return "Alat Kesehatan Habis Pakai";
                case "OG": return "Obat Gigi";
                case "PB": return "Pembalut";
                case "REG": return "Reagensia";
                case "RO": return "Rontgen";
                case "GAS": return "Gas Medis";
                case "HD": return "Hemodialisa";
                case "COD": return "COD";
                case "KON": return "Konsinyasi Implant";
                case "CL": return "Konsinyasi Kardiologi Invasiv (Chatlab)";
                case "MK": return "Konsinyasi Implant Mata";
                case "D": return "Donasi";
                default: return "Lain-lain";
            }
        };

        $daftarHalaman = [];
        $grandTotalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 55;
        $kodeSaatIni = "";

        foreach ($daftarPenerimaan as $i => $penerimaan) {
            if ($kodeSaatIni != $penerimaan->kodePenerimaan) {
                $kodeSaatIni = $penerimaan->kodePenerimaan;
                $hJudul = $h;
                $bJudul = $b;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "jenis_penerimaan" => $kategori($penerimaan->kodePenerimaan),
                    "total_nilai" => 0,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul][$bJudul]["total_nilai"] += $penerimaan->nilaiAkhir;
            $grandTotalNilai += $penerimaan->nilaiAkhir;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportPerPbf(
            daftarHalaman:   $daftarHalaman,
            tanggalAwal:     $tanggalAwal,
            tanggalAkhir:    $tanggalAkhir,
            jumlahHalaman:   count($daftarHalaman),
            grandTotalNilai: $grandTotalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#RekapPenerimaanGas    the original method
     * first exist of actionRekapPenerimaanGas: commit-75b46456
     * last exist of actionRekapGasPerJenis: commit-e37d34f4
     */
    public function actionReportGasMedisPerJenisBarang(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "idGudangPenyimpanan" => $idGudangPenyimpanan,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                                                  AS idKatalog,
                SUM(A.jumlah_item)                                            AS kuantitas,
                SUM(A.jumlah_kemasan * A.harga_kemasan)                       AS nilaiTotal,  -- in use
                SUM(A.jumlah_kemasan * A.harga_kemasan * A.diskon_item / 100) AS nilaiDiskon, -- DUPLICATE COLUMN with bellow
                SUM(
                    ((A.jumlah_kemasan * A.harga_kemasan) - (A.jumlah_kemasan * A.harga_kemasan * A.diskon_item / 100)) * E.ppn / 100
                )                                                             AS nilaiDiskon, -- in use
                SUM(
                    ((A.jumlah_kemasan * A.harga_kemasan) - (A.jumlah_kemasan * A.harga_kemasan * A.diskon_item / 100)) +
                    (((A.jumlah_kemasan * A.harga_kemasan) - (A.jumlah_kemasan * A.harga_kemasan * A.diskon_item / 100)) * E.ppn / 100)
                )                                                             AS nilaiAkhir,  -- in use
                B.nama_barang                                                 AS namaBarang,
                C.nama_pabrik                                                 AS namaPabrik,
                D.kode                                                        AS satuan
            FROM db1.tdetailf_penerimaan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON B.id_kemasankecil = D.id
            LEFT JOIN db1.transaksif_penerimaan AS E ON A.kode_reff = E.kode
            WHERE
                E.sts_deleted = 0
                AND E.sts_testing = 0
                AND E.id_gudangpenyimpanan = :idGudangPenyimpanan
                AND E.tgl_doc >= :tanggalAwal
                AND E.tgl_doc <= :tanggalAkhir
            GROUP BY A.id_katalog
        ";
        $params = [
            ":idGudangPenyimpanan" => $idGudangPenyimpanan,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $totalJumlah = 0;
        $totalPpn = 0;
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 50;

        foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
            $daftarHalaman[$h][$b] = ["i" => $i];

            $totalJumlah += $dPenerimaan->nilaiTotal;
            $totalPpn += $dPenerimaan->nilaiDiskon;
            $totalNilai += $dPenerimaan->nilaiAkhir;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportGasPerJenis(
            daftarHalaman:          $daftarHalaman,
            tanggalAwal:            $tanggalAwal,
            tanggalAkhir:           $tanggalAkhir,
            daftarDetailPenerimaan: $daftarDetailPenerimaan,
            jumlahHalaman:          count($daftarHalaman),
            totalJumlah:            $totalJumlah,
            totalPpn:               $totalPpn,
            totalNilai:             $totalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#RekapPenerimaanGas    the original method
     * first exist of actionRekapPenerimaanGas: commit-75b46456
     * last exist of actionRekapGasPerItem: commit-e37d34f4
     */
    public function actionReportGasMedisPerItemBarang(): string
    {
        [   "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
            "idGudangPenyimpanan" => $idGudangPenyimpanan,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog                      AS idKatalog,     -- in use
                B.nama_barang                     AS namaBarang,    -- in use
                E.no_faktur                       AS noFaktur,
                E.no_suratjalan                   AS noSuratJalan,
                E.tgl_doc                         AS tanggalDatang,
                D.kode                            AS satuan,
                SUM(A.jumlah_item)                AS kuantitas,
                AVG(A.harga_item)                 AS hargaSatuan,
                SUM(A.jumlah_item * A.harga_item) AS jumlah         -- in use
            FROM db1.tdetailf_penerimaan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_kemasan AS D ON B.id_kemasankecil = D.id
            LEFT JOIN db1.transaksif_penerimaan AS E ON A.kode_reff = E.kode
            WHERE
                E.sts_deleted = 0
                AND E.sts_testing = 0
                AND E.id_gudangpenyimpanan = :idGudangPenyimpanan
                AND E.tgl_doc >= :tanggalAwal
                AND E.tgl_doc <= :tanggalAkhir
            GROUP BY A.kode_reff, A.id_katalog
            ORDER BY B.nama_sediaan
        ";
        $params = [
            ":idGudangPenyimpanan" => $idGudangPenyimpanan,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotalJumlah = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 50;
        $idKatalogSaatIni = "";

        foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
            if ($idKatalogSaatIni != $dPenerimaan->idKatalog) {
                $idKatalogSaatIni = $dPenerimaan->idKatalog;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_barang" => $dPenerimaan->namaBarang,
                    "total_jumlah" => 0,
                ];

                if ($b > $barisPerHalaman) {
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

            $daftarHalaman[$hJudul][$bJudul]["total_jumlah"] += $dPenerimaan->jumlah;
            $grandTotalJumlah += $dPenerimaan->jumlah;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportGasPerItem(
            daftarHalaman:          $daftarHalaman,
            tanggalAwal:            $tanggalAwal,
            tanggalAkhir:           $tanggalAkhir,
            daftarDetailPenerimaan: $daftarDetailPenerimaan,
            jumlahHalaman:          count($daftarHalaman),
            grandTotalJumlah:       $grandTotalJumlah,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/penerimaan.php#RekapPenerimaanGas    the original method
     * first exist of actionRekapPenerimaanGas: commit-75b46456
     * last exist of actionRekapGasBukuInduk: commit-e37d34f4
     */
    public function actionReportGasMedisBukuInduk(): string
    {
        ["tanggalAwal" => $tanggalAwal, "tanggalAkhir" => $tanggalAkhir] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $sysTanggalAwal = $toSystemDate($tanggalAwal) . " 00:00:00";
        $sysTanggalAkhir = $toSystemDate($tanggalAkhir) . " 23:59:59";

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.kode_reff       AS kodeRef,
                A.id_katalog      AS idKatalog,
                B.nama_barang     AS namaBarang,        -- in use
                C.no_doc          AS noBtb,             -- in use
                C.tgl_doc         AS tanggalBtb,        -- in use
                C.ver_tglgudang   AS tanggalVerifikasi, -- in use
                C.no_faktur       AS noFaktur,          -- in use
                C.no_suratjalan   AS noSuratJalan,      -- in use
                D.no_batch        AS noSeriTabung,
                D.jumlah_masuk    AS jumlahMasuk,       -- in use
                E.nama_pbf        AS namaPemasok        -- in use
            FROM db1.tdetailf_penerimaan AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.transaksif_penerimaan AS C ON A.kode_reff = C.kode
            LEFT JOIN db1.relasif_ketersediaan AS D ON A.id_katalog = D.id_katalog
            LEFT JOIN db1.masterf_pbf AS E ON C.id_pbf = E.id
            WHERE
                C.sts_deleted = 0
                AND A.kode_reff = D.kode_reff
                AND D.kode_transaksi = 'T'
                AND D.tipe_tersedia = 'penerimaan'
                AND C.id_gudangpenyimpanan = D.id_depo
                AND C.sts_testing = 0
                AND C.no_doc LIKE '%gas%'
                AND C.tgl_doc >= :tanggalAwal
                AND C.tgl_doc <= :tanggalAkhir
            ORDER BY C.no_doc
        ";
        $params = [":tanggalAwal" => $sysTanggalAwal, ":tanggalAkhir" => $sysTanggalAkhir];
        $daftarDetailPenerimaan = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $grandTotal = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 50;
        $noBtbSaatIni = "";

        foreach ($daftarDetailPenerimaan as $i => $dPenerimaan) {
            if ($noBtbSaatIni != $dPenerimaan->noBtb) {
                $noBtbSaatIni = $dPenerimaan->noBtb;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_barang" => $dPenerimaan->namaBarang,
                    "nama_pbf" => $dPenerimaan->namaPemasok,
                    "tgl_btb" => $dPenerimaan->tanggalBtb,
                    "tgl_verifikasi" => $dPenerimaan->tanggalVerifikasi,
                    "no_btb" => $dPenerimaan->noBtb,
                    "no_sj" => $dPenerimaan->noSuratJalan ?: $dPenerimaan->noFaktur,
                    "subtotal" => 0,
                ];

                if ($b > $barisPerHalaman) {
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

            $daftarHalaman[$hJudul][$bJudul]["subtotal"] += $dPenerimaan->jumlahMasuk;
            $grandTotal += $dPenerimaan->jumlahMasuk;

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new ReportGasBukuInduk(
            daftarHalaman:          $daftarHalaman,
            tanggalAwal:            $tanggalAwal,
            tanggalAkhir:           $tanggalAkhir,
            daftarDetailPenerimaan: $daftarDetailPenerimaan,
            jumlahHalaman:          count($daftarHalaman),
            grandTotal:             $grandTotal,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/laporan.php#printpenerimaan_rekap    the original method
     * last exist of actionPrintPenerimaanRekap: commit-e37d34f4
     */
    public function actionReportDepo(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $todayValSystem = Yii::$app->dateTime->todayVal("system");
        $connection = Yii::$app->dbFatma;
        [   "idDepoPenerima" => $idDepoPeminta,
            "idDepoAsal" => $idDepoPemberi,
            "tanggalAwal" => $tanggalAwal,
            "tanggalAkhir" => $tanggalAkhir,
        ] = Yii::$app->request->post();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                namaDepo AS nama,
                kode     AS kode
            FROM db1.masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idDepoPeminta];
        $depoPeminta = $connection->createCommand($sql, $params)->queryOne() ?? new GenericData(["kode" => "", "nama" => "Semua Depo"]);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                namaDepo AS nama,
                kode     AS kode
            FROM db1.masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idDepoPemberi];
        $depoPemberi = $connection->createCommand($sql, $params)->queryOne() ?? new GenericData(["kode" => "", "nama" => "Semua Depo"]);

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
            INNER JOIN db1.relasif_hargaperolehan AS d ON d.id_katalog = b.kode
            LEFT JOIN db1.masterf_brand AS h ON h.id = b.id_brand
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = b.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS f ON f.id = b.id_kemasankecil
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = b.id_jenisbarang
            WHERE
                d.sts_hja != 0
                AND d.sts_hjapb != 0
                AND a.tanggal_terima >= :tanggalAwal
                AND a.tanggal_terima <= :tanggalAkhir
                AND (:kodeDepoPeminta = '' OR a.depoPeminta = :kodeDepoPeminta)
                AND (:kodeDepoPemberi = '' OR a.kodeDepo = :kodeDepoPemberi)
            GROUP BY
                b.nama_barang,
                a.kodeDepo
            ORDER BY a.kodeDepo, g.jenis_obat, b.kode ASC
        ";
        $params = [
            ":tanggalAwal" => $tanggalAwal ? $toSystemDate($tanggalAwal) : $todayValSystem,
            ":tanggalAkhir" => $tanggalAkhir ? $toSystemDate($tanggalAkhir) : $todayValSystem,
            ":kodeDepoPeminta" => $depoPeminta->kode,
            ":kodeDepoPemberi" => $depoPemberi->kode,
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        $view = new ReportDepo(
            namaDepo2:        $depoPeminta->nama,
            namaDepo1:        $depoPemberi->nama,
            daftarPeringatan: $daftarPeringatan,
            tanggalAwal:      $tanggalAwal,
            tanggalAkhir:     $tanggalAkhir,
        );
        return $view->__toString();
    }
}
