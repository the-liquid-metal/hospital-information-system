<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\PembelianController as Pair;
use tlm\his\FatmaPharmacy\views\Pembelian\PrintRealisasiPl;
use tlm\his\FatmaPharmacy\views\PembelianUi\{
    Form,
    FormRevisi,
    TablePl,
    TableRealisasiPl,
    Report,
    Table,
    TableAdendum,
    TableRevisi,
    View,
};
use tlm\libs\LowEnd\components\DateTimeException;
use tlm\libs\LowEnd\views\CustomIndexScript;
use Yii;
use yii\db\Exception;
use yii\web\{Controller, Response};

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class PembelianUiController extends Controller
{
    /**
     * @author Hendra Gunawan
     */
    public function actionIndex(): string
    {
        $canSeeTable             = Yii::$app->canAccess([Pair::class, "actionTableData"]);
        $canSeeForm              = Yii::$app->canAccess([Pair::class, "actionSaveAdd"]);
        $canSeeTableRevisi       = Yii::$app->canAccess([Pair::class, "actionTableRevisiData"]);
        $canSeeTableAdendum      = Yii::$app->canAccess([Pair::class, "actionTableAdendumData"]);
        $canSeeFormRevisi        = Yii::$app->canAccess([Pair::class, "actionSaveRevisiDokumen"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveRevisiJumlah"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveRevisiNilai"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveRevisiKatalog"])
                                || Yii::$app->canAccess([Pair::class, "actionSaveRevisiOpen"]);
        $canSeeView              = Yii::$app->canAccess([Pair::class, "actionViewData"]);
        $canSeeReportRealisasiPl = Yii::$app->canAccess([Pair::class, "actionRealisasiPlTableData"]);
        $canSeeReportPlItem      = Yii::$app->canAccess([Pair::class, "actionItemPlTableData"]);
        $canSeeReport            = Yii::$app->canAccess([Pair::class, "actionReportlaporanAkhir"]);

        $index = new CustomIndexScript;
        $index->pageId = Yii::$app->actionToId(__METHOD__);
        $index->title = "Pembelian";
        $index->tabs = [
            ["title" => "Table",               "canSee" => $canSeeTable,             "registerId" => Yii::$app->actionToId([self::class, "actionTable"])],
            ["title" => "Table Revisi",        "canSee" => $canSeeTableRevisi,       "registerId" => Yii::$app->actionToId([self::class, "actionTableRevisi"])],
            ["title" => "Table Adendum",       "canSee" => $canSeeTableAdendum,      "registerId" => Yii::$app->actionToId([self::class, "actionTableAdendum"])],
            ["title" => "Form",                "canSee" => $canSeeForm,              "registerId" => Yii::$app->actionToId([self::class, "actionForm"])],
            ["title" => "Form Revisi",         "canSee" => $canSeeFormRevisi,        "registerId" => Yii::$app->actionToId([self::class, "actionFormRevisi"])],
            ["title" => "View",                "canSee" => $canSeeView,              "registerId" => Yii::$app->actionToId([self::class, "actionView"])],
            ["title" => "Report Realisasi Pl", "canSee" => $canSeeReportRealisasiPl, "registerId" => Yii::$app->actionToId([self::class, "actionReportRealisasiPl"])],
            ["title" => "Report PL Item",      "canSee" => $canSeeReportPlItem,      "registerId" => Yii::$app->actionToId([self::class, "actionReportPlItem"])],
            ["title" => "Report",              "canSee" => $canSeeReport,            "registerId" => Yii::$app->actionToId([self::class, "actionReport"])],
        ];
        return $index->render();
    }

    /**
     * TODO: need more attention
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#index    the original method
     */
    public function actionTable(): string
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $todayValSystem = Yii::$app->dateTime->todayVal("system");

        $connection = Yii::$app->dbFatma;

        // Query Update Status PL menjadi Closed untuk semua PL diluar jatuh tempo
        // =================================================================//
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_pembelian
            SET
                sts_closed = IF(tgl_jatuhtempo < :tanggal1, 1, 0),
                sysdate_cls = IF(tgl_jatuhtempo < :tanggal1, :tanggal2, NULL)
            WHERE TRUE
        ";
        $params = [":tanggal1" => $todayValSystem, ":tanggal2" => $nowValSystem];
        $connection->createCommand($sql, $params)->execute();
        // =================================================================//

        $view = new Table(
            registerId:                 Yii::$app->actionToId(__METHOD__),
            editAccess:                 [true],
            deleteAccess:               [true],
            auditAccess:                [true],
            dataUrl:                    Yii::$app->actionToUrl([Pair::class, "actionTableData"]),
            viewWidgetId:               Yii::$app->actionToId([self::class, "actionView"]),
            revisiWidgetId:             Yii::$app->actionToId([self::class, "actionTableRevisi"]),
            printWidgetId:              Yii::$app->actionToId([Pair::class, "actionPrint"]),
            formWidgetId:               Yii::$app->actionToId([self::class, "actionForm"]),
            deleteUrl:                  Yii::$app->actionToUrl([Pair::class, "actionAjaxDelete"]),
            pemesananWidgetId:          Yii::$app->actionToId([PemesananUiController::class, "actionTable"]),
            addRevisiPembelianUrl:      Yii::$app->actionToUrl([self::class, "actionFormRevisi"]),
            subjenisAnggaranSelect:     Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect1Data"]),
            jenisHargaSelect:           Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            tipeDokumenPembelianSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectPembelian1Data"]),
            tipeDokumenBulanSelect:     Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:                Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#add    the original method
     */
    public function actionForm(): string
    {
        $view = new Form(
            registerId:          Yii::$app->actionToId(__METHOD__),
            editAccess:          [true],
            dataUrl:             Yii::$app->actionToUrl([Pair::class, "actionEditData"]),
            actionUrl:           Yii::$app->actionToUrl([Pair::class, "actionSaveAdd"]),
            cekUnikNoDokumenUrl: Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
            pemasokAcplUrl:      Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
            pengadaanUrl:        Yii::$app->actionToUrl([PengadaanController::class, "actionSearchJsonLainnya"]),
            perencanaanUrl:      Yii::$app->actionToUrl([PerencanaanController::class, "actionSearchJsonLainnya"]),
            katalogAcplUrl:      Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSubjenis"]),
            detailHpsUrl:        Yii::$app->actionToUrl([PerencanaanController::class, "actionSearchHtmlDetailHps"]),
            detailHargaUrl:      Yii::$app->actionToUrl([PengadaanController::class, "actionSearchJsonDetailHarga"]),
            viewWidgetId:        Yii::$app->actionToId([self::class, "actionView"]),
            jenisAnggaranSelect: Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
            bulanSelect:         Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
            tahunAnggaranSelect: Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
            sumberDanaSelect:    Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
            jenisHargaSelect:    Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            caraBayarSelect:     Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),

            // unused
            // printWidgetId: Yii::$app->actionToUrl([Pair::class, "actionPrint"]),
            // tableWidgetId: Yii::$app->actionToUrl([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#revisi    the original method
     */
    public function actionTableRevisi(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");

        $view = new TableRevisi(
            registerId:             Yii::$app->actionToId(__METHOD__),
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionTableRevisiData"]),
            viewWidgetId:           Yii::$app->actionToId([self::class, "actionView"]),
            kode:                   $kode,
            subjenisAnggaranSelect: Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect1Data"]),
            jenisHargaSelect:       Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            tipeDokumenBulanSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),

            // unused
            // daftarTipeDokumenPembelian: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectPembelian1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#adendum    the original method
     */
    public function actionTableAdendum(): string
    {
        $view = new TableAdendum(
            registerId:             Yii::$app->actionToId(__METHOD__),
            dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionTableAdendumData"]),
            viewWidgetId:           Yii::$app->actionToId([self::class, "actionView"]),
            subjenisAnggaranSelect: Yii::$app->actionToUrl([SubjenisAnggaranController::class, "actionSelect1Data"]),
            jenisHargaSelect:       Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
            tipeDokumenBulanSelect: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectBulan1Data"]),
            tahunSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),

            // unused
            // daftarTipeDokumenPembelian: Yii::$app->actionToUrl([TipeDokumenController::class, "actionSelectPembelian1Data"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#addrevisi    the original method
     */
    public function actionFormRevisi(): string|Response|null
    {
        [   "kode" => $kode,
            "action" => $action,
            "jenis" => $jenis,
            "ver_revisi" => $verRevisi,
        ] = Yii::$app->request->post();

        /* note: this IF-ELSEIF segment is identical with others */
        // JIKA LANJUT REVISI
        if ($action != $jenis) {
            $view = new FormRevisi(
                registerId:             Yii::$app->actionToId(__METHOD__),
                revisiDokumenAccess:    [true],
                revisiJumlahAccess:     [true],
                revisiNilaiAccess:      [true],
                revisiKatalogAccess:    [true],
                revisiOpenAccess:       [true],
                dataUrl:                Yii::$app->actionToUrl([Pair::class, "actionDataRevisi"]),
                actionUrl:              Yii::$app->actionToUrl([Pair::class, "___"]),
                revisiDokumenActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveRevisiDokumen"]),
                revisiJumlahActionUrl:  Yii::$app->actionToUrl([Pair::class, "actionSaveRevisiJumlah"]),
                revisiNilaiActionUrl:   Yii::$app->actionToUrl([Pair::class, "actionSaveRevisiNilai"]),
                revisiKatalogActionUrl: Yii::$app->actionToUrl([Pair::class, "actionSaveRevisiKatalog"]),
                revisiOpenActionUrl:    Yii::$app->actionToUrl([Pair::class, "actionSaveRevisiOpen"]),
                cekUnikNoDokumenUrl:    Yii::$app->actionToUrl([Pair::class, "actionCekNoDokumen"]),
                pemasokAcplUrl:         Yii::$app->actionToUrl([PemasokController::class, "actionSearchJson"]),
                katalogAcplUrl:         Yii::$app->actionToUrl([Katalog1Controller::class, "actionSearchJsonSubjenis"]),
                detailHargaUrl:         Yii::$app->actionToUrl([PengadaanController::class, "actionSearchJsonDetailHarga"]),
                detailHpsUrl:           Yii::$app->actionToUrl([PerencanaanController::class, "actionSearchJsonDetailHps"]),
                tableWidgetId:          Yii::$app->actionToId([self::class, "actionTable"]),
                jenisAnggaranSelect:    Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
                bulanSelect:            Yii::$app->actionToUrl([WaktuController::class, "actionSelectBulan1Data"]),
                tahunAnggaranSelect:    Yii::$app->actionToUrl([WaktuController::class, "actionSelectTahun1Data"]),
                sumberDanaSelect:       Yii::$app->actionToUrl([SumberDanaController::class, "actionSelect1Data"]),
                jenisHargaSelect:       Yii::$app->actionToUrl([JenisHargaController::class, "actionSelect1Data"]),
                caraBayarSelect:        Yii::$app->actionToUrl([CaraBayarController::class, "actionSelectPembelianData"]),
            );
            return $view->__toString();
            // $view->judulHeading = "Revisi SP/SPK/Kontrak " . $retval[0]["no_doc"];
            // $view->data = $retval[0]->getArrayCopy();
            // $view->action = $jenis;
            // $view->idata = $retval[1];
            // $view->reffrenc = $retval[2] ?: [];

            // JIKA VERIF
        } elseif ($verRevisi == 1) {
            return $this->redirect(Yii::$app->actionToUrl([self::class, "actionView"]) ."/".$kode."?mf=303");

        } else {
            return null;
        }
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#views    the original method
     */
    public function actionView(): string
    {
        $view = new View(
            registerId:              Yii::$app->actionToId(__METHOD__),
            dataUrl:                 Yii::$app->actionToUrl([Pair::class, "actionViewData"]),
            viewPerencanaanWidgetId: Yii::$app->actionToId([PerencanaanUiController::class, "actionView"]),
            viewPengadaanWidgetId:   Yii::$app->actionToId([PengadaanUiController::class, "actionView"]),
            tablePenerimaanWidgetId: Yii::$app->actionToId([PenerimaanUiController::class, "actionTable"]),
            formWidgetId:            Yii::$app->actionToId([self::class, "actionForm"]),
            cetakWidgetId:           Yii::$app->actionToId([Pair::class, "actionPrint"]),
            tableWidgetId:           Yii::$app->actionToId([self::class, "actionTable"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws LogicBranchException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionReportRealisasiPl(): string
    {
        [   "kode" => $kode,
            "format" => $format,
            "type" => $tipe,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;

        if ($format == "index_realisasipl") {
            if ($tipe == "exportToExcel") {
                return $this->renderPartial("export-to-excel", [
                    "fields" => [
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
                        "anggaran_sisa" => "Sisa Anggaran", // MISSING IN QUERY
                    ],
                    "data" => (new Pair("x", $this->module))->actionRealisasiPlTableData(),
                    "file_name" => "Realisasi PL",
                ]);
            } else {
                $view = new TableRealisasiPl(
                    registerId: Yii::$app->actionToId(__METHOD__),
                    dataUrl:    Yii::$app->actionToUrl([Pair::class, "actionRealisasiPlTableData"]),
                    actionUrl:  Yii::$app->actionToUrl([self::class, "actionReport"]),
                );
                return $view->__toString();
            }

        } elseif ($format == "print_realisasipl") {
            $kode = $kode ? "'" . str_replace(",", "','", $kode) . "'" : $kode;
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
                    AND A.kode IN ($kode)
            ";
            $daftarPembelian = $connection->createCommand($sql)->queryAll();

            $daftarPembelian2 = [];
            $daftarDetailPembelian = [];
            foreach ($daftarPembelian as $pembelian) {
                $kodePembelian = $pembelian->kode;
                $daftarPembelian2[$kodePembelian] = $pembelian;
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
                $params = [":kode" => $kodePembelian];
                $daftarDetailPembelian[$kodePembelian] = $connection->createCommand($sql, $params)->queryAll();
            }

            if (!$daftarPembelian) return "tidak ada data";

            $view = new PrintRealisasiPl(daftarPembelian: $daftarPembelian2, daftarDetailPembelian: $daftarDetailPembelian);
            return $view->__toString();

        } else {
            throw new LogicBranchException;
        }
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
            return $this->renderPartial("export-to-excel", [
                "fields" => [
                    "sts_closed" => "Status PL",
                    "no_doc" => "NO PL Pembelian",
                    "tgl_jatuhtempo" => "Tempo Kontrak",
                    "nama_pbf" => "Distributor",
                    "nama_pabrik" => "Pabrik",
                    "kemasan" => "Kemasan",
                    "jumlah_kemasan" => "Jumlah Kemasan",
                    "jumlah_item" => "Jumlah Item"
                ],
                "data" => (new Pair("x", $this->module))->actionItemPlTableData(),
                "file_name" => "Realisasi PL",
            ]);
        } else {
            $view = new TablePl(
                registerId:     Yii::$app->actionToId(__METHOD__),
                dataUrl:        Yii::$app->actionToUrl([Pair::class, "actionItemPlTableData"]),
                reportWidgetId: Yii::$app->actionToId([self::class, "actionReport"]),
                viewWidgetId:   Yii::$app->actionToId([self::class, "actionView"]),
            );
            return $view->__toString();
        }
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/pembelian.php#reports    the original method
     */
    public function actionReport(): string
    {
        $view = new Report(
            registerId:          Yii::$app->actionToId(__METHOD__),
            action1Url:          Yii::$app->actionToUrl([self::class, "actionReportRealisasiPl"]),
            action2Url:          Yii::$app->actionToUrl([self::class, "actionReportPlItem"]),
            action3Url:          Yii::$app->actionToUrl([Pair::class, "actionReportlaporanAkhir"]),
            plAcplUrl:           Yii::$app->actionToUrl([Pair::class, "action___"]), // farmasi/pembelian/ajax-search
            katalogAcplUrl:      Yii::$app->actionToUrl([self::class, "action___"]), // farmasi/katalog/ajax-search
            jenisAnggaranSelect: Yii::$app->actionToUrl([JenisAnggaranController::class, "actionSelect1Data"]),
        );
        return $view->__toString();
    }
}
