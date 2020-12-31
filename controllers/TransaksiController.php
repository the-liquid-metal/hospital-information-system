<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\Transaksi\{PrintPengambilan, PrintPengiriman, PrintPengiriman22, PrintPengirimanTpTest};
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
class TransaksiController extends BaseController
{
    /**
     * generate sequenced document-number and make sure it does not exist
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#permintaan2    the original method
     */
    public function actionNoDokumenTemporer(): string
    {
        $connection = Yii::$app->dbFatma;
        $noDokumen = date("Ymd") . "0000";
        do {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.master_warning
                WHERE no_doc = :noDokumen
                LIMIT 1
            ";
            $params = [":noDokumen" => ++$noDokumen];
            $adaNoDokumen = $connection->createCommand($sql, $params)->queryScalar();
        } while ($adaNoDokumen);

        return $noDokumen;
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#permintaan2    the original method
     */
    public function actionSavePermintaan2(): string
    {
        [   "noDokumen" => $noDokumen,
            "peminta" => $peminta,
            "prioritas" => $prioritas,
            "diminta" => $diminta,
            "namaObat" => $daftarNamaObat,
            "kodeObat" => $daftarKodeObat,
            "jumlah" => $daftarJumlah,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        /* note: generate random sale-code and make sure it does not exist */
        $back = date("Ymd") . "0000";
        do {
            $noPermintaan = "M" . Yii::$app->userFatma->kodeDepo . ++$back;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.master_warning
                WHERE noPermintaan = :noPermintaan
                LIMIT 1
            ";
            $params = [":noPermintaan" => $noPermintaan];
            $cekNoPermintaan = $connection->createCommand($sql, $params)->queryScalar();
        } while ($cekNoPermintaan);

        foreach ($daftarKodeObat as $key => $kodeObat) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT b.hp_item
                FROM db1.masterf_katalog AS a
                LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                WHERE
                    a.kode = :kode
                    AND b.sts_hja != 0
                    AND b.sts_hjapb != 0
                LIMIT 1
            ";
            $params = [":kode" => $kodeObat];
            $hpItem = (int) $connection->createCommand($sql, $params)->queryScalar();

            $todayValSystem = Yii::$app->dateTime->todayVal("system");

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.master_warning
                SET
                    tipe = 'crisisStock',
                    depoPeminta = :depoPeminta,
                    kodeDepo = :kodeDepo,
                    kodeItem = :kodeItem,
                    namaItem = :namaItem,
                    jumlah1 = :jumlah1,
                    jumlah2 = 0,
                    harga_perolehan = :hargaPerolehan,
                    detail = :detail,
                    status = 'pesan',
                    tanggal = :tanggal,
                    noPermintaan = :noPermintaan,
                    no_doc = :noDokumen,
                    prioritas = :prioritas
            ";
            $params = [
                ":depoPeminta" => $peminta,
                ":kodeDepo" => $diminta,
                ":kodeItem" => $kodeObat,
                ":namaItem" => $daftarNamaObat[$key],
                ":jumlah1" => $daftarJumlah[$key],
                ":hargaPerolehan" => $hpItem,
                ":detail" => "Pemesanan obat stok $kodeObat sebanyak {$daftarJumlah[$key]}",
                ":tanggal" => $todayValSystem,
                ":noPermintaan" => $noPermintaan,
                ":noDokumen" => $noDokumen,
                ":prioritas" => $prioritas,
            ];
            $connection->createCommand($sql, $params)->execute();
        }

        return json_encode(["noPermintaan" => $noPermintaan]);
    }

    /**
     * generate sequenced code and make sure it does not exist
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengirimantp    the original method
     */
    public function actionKodeTransaksiTemporer(): string
    {
        $connection = Yii::$app->dbFatma;
        $back = date("Ymd") . "0000";
        do {
            $kodeTransaksi = "D" . Yii::$app->userFatma->kodeDepo . ++$back;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.master_warning
                WHERE noPengeluaran = :noPengeluaran
                LIMIT 1
            ";
            $params = [":noPengeluaran" => $kodeTransaksi];
            $adaNoPengeluaran = $connection->createCommand($sql, $params)->queryScalar();
        } while ($adaNoPengeluaran);

        return $kodeTransaksi;
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengirimantp    the original method
     */
    public function actionSavePengirimanTp(): string
    {
        [   "diminta" => $diminta,
            "noDokumen" => $noDokumen,
            "peminta" => $peminta,
            "tipePengiriman" => $tipePengiriman,
            "verifikasi" => $verifikasi,
            "kodeBarang" => $daftarKodeObat,
            "noBatch" => $daftarNoBatch,
            "jumlah" => $daftarJumlah,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;

        $fresep = "D" . Yii::$app->userFatma->kodeDepo . date("Ymd");
        $urutan = $this->getCounter2($fresep);
        do {
            $urutan = sprintf("%04d", ++$urutan);
            $kodeTransaksi = $fresep . $urutan;
            $cekKode = $this->addCounter2($kodeTransaksi);
        } while ($cekKode == "NO");

        $todayValSystem = Yii::$app->dateTime->todayVal("system");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        foreach ($daftarKodeObat as $key => $kodeObat) {
            if (!$kodeObat) continue;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT nama_barang
                FROM db1.masterf_katalog
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $kodeObat];
            $namaBarang = $connection->createCommand($sql, $params)->queryScalar();
            if (!$namaBarang) continue;

            $detail = "Pemesanan obat stok $kodeObat sebanyak {$daftarJumlah[$key]}";

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id
                FROM db1.masterf_depo
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $diminta];
            $idDepo = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
                    AND status = 1
            ";
            $params = [":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
            $jumlahStokFisikBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT namaDepo
                FROM db1.masterf_depo
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $peminta];
            $namaDepo = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id
                FROM db1.masterf_depo
                WHERE kode = :kode
            ";
            $params = [":kode" => $diminta];
            $idDepoDiminta = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.master_warning
                WHERE
                    kodeItem = :kodeItem
                    AND noPermintaan = :noPermintaan
                LIMIT 1
            ";
            $params = [":kodeItem" => $kodeObat, ":noPermintaan" => "TP".$kodeTransaksi];
            $cekPeringatan = $connection->createCommand($sql, $params)->queryScalar();

            if ($verifikasi) {
                if ($cekPeringatan) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.master_warning
                        SET jumlah2 = jumlah2 + :jumlah
                        WHERE
                            kodeItem = :kodeItem
                            AND noPermintaan = :noPermintaan
                    ";
                    $params = [":jumlah" => $daftarJumlah[$key], ":kodeItem" => $kodeObat, ":noPermintaan" => "TP".$kodeTransaksi];
                    $connection->createCommand($sql, $params)->execute();

                } else {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT b.hp_item
                        FROM db1.masterf_katalog AS a
                        LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                        WHERE
                            a.kode = :kode
                            AND b.sts_hja != 0
                            AND b.sts_hjapb != 0
                        LIMIT 1
                    ";
                    $params = [":kode" => $kodeObat];
                    $hpItem = (int) $connection->createCommand($sql, $params)->queryScalar();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.master_warning
                        SET
                            tipe = 'crisisStock',
                            depoPeminta = :peminta,
                            kodeDepo = :diminta,
                            kodeItem = :kodeItem,
                            namaItem = :namaItem,
                            jumlah1 = :jumlah,
                            jumlah2 = :jumlah,
                            detail = :detail,
                            status = 'kirim',
                            tanggal = :tanggal,
                            noPermintaan = :noPermintaan,
                            noPengeluaran = :noPengeluaran,
                            no_doc = :noDokumen,
                            no_doc_pengiriman = :noDokumen,
                            tipe_pengiriman = :tipePengiriman,
                            verifikasi_user = :verifikasiUser,
                            nomor_batch = :noBatch,
                            tanggal_verifikasi = :tanggalVerifikasi,
                            harga_perolehan = :hargaPerolehan
                    ";
                    $params = [
                        ":peminta" => $peminta,
                        ":diminta" => $diminta,
                        ":kodeItem" => $kodeObat,
                        ":namaItem" => $namaBarang,
                        ":jumlah" => $daftarJumlah[$key],
                        ":detail" => "Pemesanan obat stok $kodeObat sebanyak {$daftarJumlah[$key]}",
                        ":tanggal" => $todayValSystem,
                        ":noPermintaan" => "TP" . $kodeTransaksi,
                        ":noPengeluaran" => $kodeTransaksi,
                        ":noDokumen" => $noDokumen,
                        ":tipePengiriman" => $tipePengiriman,
                        ":verifikasiUser" => $idUser,
                        ":noBatch" => $daftarNoBatch[$key],
                        ":tanggalVerifikasi" => $nowValSystem,
                        ":hargaPerolehan" => $hpItem,
                    ];
                    $connection->createCommand($sql, $params)->execute();
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT sts_opname
                    FROM db1.transaksif_stokopname
                    WHERE id_depo = :idDepo
                    ORDER BY tgl_doc DESC
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepoDiminta];
                $statusOpname = $connection->createCommand($sql, $params)->queryScalar();

                $jumlahAdm = ($statusOpname == 1) ? 0 : $daftarJumlah[$key];

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = jumlah_stokfisik - :pengurangStokFisik,
                        jumlah_stokadm = jumlah_stokadm - :pengurangStokAdm
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                ";
                $params = [
                    ":pengurangStokFisik" => $daftarJumlah[$key],
                    ":pengurangStokAdm" => $jumlahAdm,
                    ":idDepo" => $idDepoDiminta,
                    ":idKatalog" => $kodeObat,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        hp_item   AS hpItem,
                        hja_item  AS hjaItem
                    FROM db1.relasif_hargaperolehan
                    WHERE
                        sts_hjapb = 1
                        AND sts_hja = 1
                        AND id_katalog = :idKatalog
                    LIMIT 1
                ";
                $params = [":idKatalog" => $kodeObat];
                $hargaPerolehan = $connection->createCommand($sql, $params)->queryOne();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        id_depo = :idDepo,
                        kode_stokopname = :kodeStokopname,
                        id_katalog = :idKatalog,
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        status = 1,
                        kode_transaksi = 'D',
                        tipe_tersedia = 'pengiriman',
                        userid_last = :idUser,
                        tgl_tersedia = :tanggalTersedia,
                        harga_perolehan = :hargaPerolehan,
                        harga_jualapotik = :hargaJualApotik,
                        jumlah_masuk = 0,
                        no_batch = :noBatch,
                        jumlah_keluar = :jumlahKeluar,
                        jumlah_tersedia = :jumlahTersedia,
                        jumlah_sebelum = :jumlahSebelum,
                        keterangan = :keterangan
                ";
                $params = [
                    ":idDepo" => $idDepo,
                    ":kodeStokopname" => $idDepo,
                    ":idKatalog" => $kodeObat,
                    ":kodeRef" => $kodeTransaksi,
                    ":noDokumen" => $noDokumen,
                    ":idUser" => $idUser,
                    ":tanggalTersedia" => $nowValSystem,
                    ":hargaPerolehan" => $hargaPerolehan->hpItem,
                    ":hargaJualApotik" => $hargaPerolehan->hjaItem,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":jumlahKeluar" => $daftarJumlah[$key],
                    ":jumlahTersedia" => $jumlahStokFisikBefore - $daftarJumlah[$key],
                    ":jumlahSebelum" => $jumlahStokFisikBefore,
                    ":keterangan" => "Pengeluaran ke " . $namaDepo,
                ];
                $connection->createCommand($sql, $params)->execute();

            } elseif ($cekPeringatan) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.master_warning
                    SET jumlah2 = jumlah2 + :jumlah
                    WHERE
                        kodeItem = :kodeItem
                        AND noPermintaan = :noPermintaan
                ";
                $params = [":jumlah" => $daftarJumlah[$key], ":kodeItem" => $kodeObat, ":noPermintaan" => "TP".$kodeTransaksi];
                $connection->createCommand($sql, $params)->execute();

            } else {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT b.hp_item
                    FROM db1.masterf_katalog AS a
                    LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                    WHERE
                        a.kode = :kode
                        AND b.sts_hja != 0
                        AND b.sts_hjapb != 0
                    LIMIT 1
                ";
                $params = [":kode" => $kodeObat];
                $hpItem = (int) $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.master_warning
                    SET
                        tipe = 'crisisStock',
                        depoPeminta = :depoPeminta,
                        kodeDepo = :kodeDepo,
                        kodeItem = :kode,
                        namaItem = :namaItem,
                        jumlah1 = :jumlah,
                        jumlah2 = :jumlah,
                        detail = :detail,
                        status = 'kirim',
                        tanggal = :tanggal,
                        noPermintaan = :noPermintaan,
                        no_doc = :noDokumen,
                        nomor_batch = :noBatch,
                        no_doc_pengiriman = :noDokumen,
                        tipe_pengiriman = :tipePengiriman,
                        harga_perolehan = :hargaPerolehan
                ";
                $params = [
                    ":depoPeminta" => $peminta,
                    ":kodeDepo" => $diminta,
                    ":kode" => $kodeObat,
                    ":namaItem" => $namaBarang,
                    ":jumlah" => $daftarJumlah[$key],
                    ":detail" => $detail,
                    ":tanggal" => $todayValSystem,
                    ":noPermintaan" => "TP" . $kodeTransaksi,
                    ":noDokumen" => $noDokumen,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":tipePengiriman" => $tipePengiriman,
                    ":hargaPerolehan" => $hpItem,
                ];
                $connection->createCommand($sql, $params)->execute();
            }
        }

        return json_encode(["kodeTransaksi" => $kodeTransaksi]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengiriman    the original method
     */
    public function actionTablePengirimanData1(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $idDepo = Yii::$app->userFatma->idDepo;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.no_doc       AS noDokumen,
                b.namaDepo     AS namaDepo,
                a.tanggal      AS tanggal,
                a.prioritas    AS prioritas,
                a.noPermintaan AS noPermintaan
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            WHERE
                a.kodeDepo = :kodeDepo
                AND a.noPermintaan LIKE 'M%'
                AND a.noPengeluaran = ''
                AND a.tanggal >= :tanggalAwal
                AND a.tanggal <= :tanggalAkhir
                AND a.verifikasi_user = ''
                AND a.noPenerimaan = ''
                AND a.tanggal_verifikasi = ''
                AND a.checking_pengiriman = 1
            GROUP BY noPermintaan
            ORDER BY a.kode DESC
        ";
        $params = [
            ":kodeDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPeringatan1 = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPeringatan1);
    }

    /**
     * this action is aligned with "actionTablePengirimanData1" in order to use $_POST["sampaiTanggal"] and $_POST["dariTanggal"].
     * besides that, "-30 day" in SQL query is a little weird. (why limit in 30 days?)
     *
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengiriman    the original method
     */
    public function actionTablePengirimanData2(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $idDepo = Yii::$app->userFatma->idDepo;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.no_doc            AS noDokumen,
                a.no_doc_pengiriman AS noDokumenPengiriman,
                b.namaDepo          AS namaDepo,
                a.kodeItem          AS kodeItem,
                c.nama_barang       AS namaBarang,
                a.jumlah1           AS jumlah1,
                a.jumlah2           AS jumlah2,
                a.tanggal           AS tanggal
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            WHERE
                (
                    a.kodeDepo = :kodeDepo
                    AND a.noPermintaan LIKE 'M%'
                    AND a.noPengeluaran != ''
                    AND a.jumlah2 < a.jumlah1
                    AND a.tanggal >= :tanggalAwal
                    AND a.tanggal <= :tanggalAkhir
                )
                OR a.checking_pengiriman = 0
            ORDER BY a.kode DESC
        ";
        $params = [
            ":kodeDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPeringatan2 = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPeringatan2);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#list_pengirimantp    the original method
     */
    public function actionListPengirimanTpData1(): string
    {
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;
        $idDepo = Yii::$app->userFatma->idDepo;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.tipe_pengiriman AS tipePengiriman,
                a.no_doc          AS noDokumen,
                b.namaDepo        AS namaDepo,
                a.tanggal         AS tanggal,
                a.prioritas       AS prioritas,
                a.noPermintaan    AS noPermintaan
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            WHERE
                a.kodeDepo = :kodeDepo
                AND a.noPermintaan LIKE 'TP%'
                AND a.noPengeluaran = ''
                AND a.tanggal >= :tanggalAwal
                AND a.tanggal <= :tanggalAkhir
            GROUP BY noPermintaan ORDER BY a.kode DESC
        ";
        $params = [
            ":kodeDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPeringatan1 = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPeringatan1);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#list_pengirimantp    the original method
     */
    public function actionListPengirimanTpData2(): string
    {
        ["dariTanggal" => $tanggalAwal, "sampaiTanggal" => $tanggalAkhir] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;
        $idDepo = Yii::$app->userFatma->idDepo;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.tipe_pengiriman   AS tipePengiriman,
                a.no_doc            AS noDokumen,
                a.no_doc_pengiriman AS noDokumenPengiriman,
                b.namaDepo          AS namaDepo,
                a.tanggal           AS tanggal,
                a.prioritas         AS prioritas,
                a.noPermintaan      AS noPermintaan
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.depoPeminta
            WHERE
                a.kodeDepo = :kodeDepo
                AND noPengeluaran != ''
                AND a.tanggal >= :tanggalAwal
                AND a.tanggal <= :tanggalAkhir
            GROUP BY noPermintaan
            ORDER BY a.kode DESC
        ";
        $params = [
            ":kodeDepo" => $idDepo,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPeringatan2 = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPeringatan2);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengeluaran2    the original method
     */
    public function actionFormPengeluaran2Data(): string
    {
        ["id_trn" => $idTransaksi] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        /* note: generate random sale-code and make sure it does not exist */
        $back = date("Ym") . "0000";
        do {
            $kodeTransaksi = "D" . Yii::$app->userFatma->kodeDepo . ++$back;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.master_warning
                WHERE
                    noPengeluaran = :noPengeluaran
                    AND checking_pengiriman = 1
                LIMIT 1
            ";
            $params = [":noPengeluaran" => $kodeTransaksi];
            $cekNoPengeluaran = $connection->createCommand($sql, $params)->queryScalar();
        } while ($cekNoPengeluaran);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                noPermintaan        AS noPermintaan,
                verifikasi_user     AS verifikasiUser,
                tanggal_verifikasi  AS tanggalVerifikasi,
                depoPeminta         AS kodeDepoPeminta,
                kodeDepo            AS kodeDepoPemberi,
                tanggal             AS tanggal,
                no_doc              AS kodeDokumenPengeluaran,
                no_doc_pengiriman   AS kodeDokumenPengiriman,
                ''                  AS idDepoPemberi,
                ''                  AS namaDepoPemberi,
                NULL                AS daftarPengeluaran
            FROM db1.master_warning
            WHERE
                noPermintaan = :noPermintaan
                AND checking_pengiriman = 1
            LIMIT 1
        ";
        $params = [":noPermintaan" => $idTransaksi];
        $peringatanPengeluaran = $connection->createCommand($sql, $params)->queryOne();
        $peringatanPengeluaran->kodeTransaksi = $kodeTransaksi;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.depoPeminta  AS kodeDepoPeminta,
                a.kodeDepo     AS kodeDepoDiminta,
                a.kodeItem     AS kodeBarang,
                a.namaItem     AS namaBarang,
                a.jumlah1      AS jumlahDiminta,
                a.jumlah2      AS jumlahDikirim,
                d.nama_kemasan AS namaKemasan,
                c.nama_pabrik  AS namaPabrik,
                ''             AS stokTersedia,
                ''             AS stokPeminta
            FROM db1.master_warning AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeItem
            LEFT JOIN db1.masterf_pabrik AS c ON c.id = b.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = b.id_kemasankecil
            WHERE
                a.noPermintaan = :noPermintaan
                AND a.checking_pengiriman = 1
            ORDER BY b.nama_barang ASC
        ";
        $params = [":noPermintaan" => $idTransaksi];
        $peringatanPengeluaran->daftarPengeluaran = $connection->createCommand($sql, $params)->queryAll();

        foreach ($peringatanPengeluaran->daftarPengeluaran as $pengeluaran) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id -- all are in use.
                FROM db1.masterf_depo
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $pengeluaran->kodeDepoDiminta];
            $idPemberi = $this->db->query($sql, $params)->row_array();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik -- all are in use.
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
            ";
            $params = [":idKatalog" => $pengeluaran->kodeBarang, ":idDepo" => $idPemberi];
            $pengeluaran->stokTersedia = $connection->createCommand($sql, $params)->queryScalar() ?? 0;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id -- all are in use.
                FROM db1.masterf_depo
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $pengeluaran->kodeDepoPeminta];
            $idPeminta = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik -- all are in use.
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
            ";
            $params = [":idKatalog" => $pengeluaran->kodeBarang, ":idDepo" => $idPeminta];
            $pengeluaran->stokPeminta = $connection->createCommand($sql, $params)->queryScalar() ?? 0;
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                id       AS id,
                namaDepo AS namaDepo
            FROM db1.masterf_depo
            WHERE kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $peringatanPengeluaran->kodeDepoPemberi];
        $depo = $connection->createCommand($sql, $params)->queryOne();
        $peringatanPengeluaran->idDepoPemberi = $depo->id;
        $peringatanPengeluaran->namaDepoPemberi = $depo->namaDepo;

        return json_encode($peringatanPengeluaran);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengeluaran2    the original method
     */
    public function actionSavePengeluaran2(): void
    {
        [   "noPermintaan" => $noPermintaan,
            "jumlah" => $daftarJumlah,
            "kode_trn" => $kodeTrn,
            "kode_trn_pengiriman" => $kodeTrnPengiriman,
            "kode_obat" => $daftarKodeObat,
            "nama_barang" => $daftarNamaBarang,
            "peminta" => $peminta,
            "verifikasi" => $verifikasi,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.master_warning
            SET
                depoPeminta = :depoPeminta,
                no_doc_pengiriman = :noDokumenPengiriman
            WHERE
                noPermintaan = :noPermintaan
                AND checking_pengiriman = 1
        ";
        $params = [":noDokumenPengiriman" => $kodeTrnPengiriman, ":noPermintaan" => $noPermintaan];
        if ($peminta) {
            $params[":depoPeminta"] = $peminta;
        } else {
            $sql = str_replace("depoPeminta = :depoPeminta,", "", $sql);
        }
        $connection->createCommand($sql, $params)->execute();

        if ($verifikasi) {
            $fresep = "D" . Yii::$app->userFatma->kodeDepo . date("Ymd");
            $urutan = $this->getCounter2($fresep);
            do {
                $urutan = sprintf("%04d", ++$urutan);
                $kodeTransaksi = $fresep . $urutan;
                $cekKode = $this->addCounter2($kodeTransaksi);
            } while ($cekKode == "NO");

            $nowValSystem = Yii::$app->dateTime->nowVal("system");

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.master_warning
                SET
                    noPengeluaran = :noPengeluaran,
                    no_doc_pengiriman = :noDokumenPengiriman,
                    verifikasi_user = :verifikasiUser,
                    tanggal_verifikasi = :tanggalVerifikasi,
                    status = 'Pengiriman'
                WHERE
                    noPermintaan = :noPermintaan
                    AND checking_pengiriman = 1
                    AND noPengeluaran = ''
            ";
            $params = [
                ":noPengeluaran" => $kodeTransaksi,
                ":noDokumenPengiriman" => $kodeTrnPengiriman,
                ":verifikasiUser" => $idUser,
                ":tanggalVerifikasi" => $nowValSystem,
                ":noPermintaan" => $noPermintaan
            ];
            $connection->createCommand($sql, $params)->execute();

            $kodeTransaksi = $kodeTrn;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kodeItem
                FROM db1.master_warning
                WHERE
                    no_doc = :noDokumen
                    AND noPermintaan = :noPermintaan
                    AND checking_pengiriman = 1
                ORDER BY kodeItem ASC
            ";
            $params = [":noDokumen" => $kodeTransaksi, ":noPermintaan" => $noPermintaan];
            $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();
            $daftarKode = array_map(fn($i) => $i->kodeItem, $daftarPeringatan);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT *
                FROM db1.master_warning
                WHERE
                    no_doc = :noDokumen
                    AND noPermintaan = :noPermintaan
                    AND checking_pengiriman = 1
                LIMIT 1
            ";
            $params = [":noDokumen" => $kodeTransaksi, ":noPermintaan" => $noPermintaan];
            $peringatanPengeluaran = $connection->createCommand($sql, $params)->queryOne();

            $set = "";
            foreach ($peringatanPengeluaran as $keyco => $valco) {
                if (!in_array($keyco, ["kodeItem", "namaItem", "jumlah1", "kode", "jumlah2"])) {
                    $set .= ", $keyco = '$valco'";
                }
            }

            foreach ($daftarKodeObat as $key => $kodeObat) {
                if (!in_array($kodeObat, $daftarKode)) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.master_warning
                        SET
                            kodeItem = :kode,
                            namaItem = :nama,
                            jumlah1 = :jumlah,
                            jumlah2 = :jumlah
                            $set
                    ";
                    $params = [":kode" => $kodeObat, ":nama" => $daftarNamaBarang[$key], ":jumlah" => $daftarJumlah[$key]];
                    $connection->createCommand($sql, $params)->execute();

                } else {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.master_warning
                        SET jumlah2 = :jumlah
                        WHERE
                            no_doc = :noDokumen
                            AND kodeItem = :kode
                            AND (noPermintaan = :noPermintaan OR noPermintaan = '')
                            AND checking_pengiriman = 1
                    ";
                    $params = [
                        ":jumlah" => $daftarJumlah[$key],
                        ":noDokumen" => $kodeTransaksi,
                        ":kode" => $kodeObat,
                        ":noPermintaan" => $noPermintaan,
                    ];
                    $connection->createCommand($sql, $params)->execute();
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT id
                    FROM db1.masterf_depo
                    WHERE kode = :kode
                    LIMIT 1
                ";
                $params = [":kode" => $peringatanPengeluaran->kodeDepo];
                $idDepoPengirim = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT namaDepo
                    FROM db1.masterf_depo
                    WHERE kode = :kode
                ";
                $params = [":kode" => $peringatanPengeluaran->depoPeminta];
                $namaDepoPenerima = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT id
                    FROM db1.masterf_depo
                    WHERE kode = :kode
                    LIMIT 1
                ";
                $params = [":kode" => $peringatanPengeluaran->kodeDepo];
                $idDepo = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT kode_stokopname
                    FROM db1.relasif_ketersediaan
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                    ORDER BY id DESC
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                $kodeStokopname = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokfisik
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_katalog = :idKatalog
                        AND id_depo = :idDepo
                        AND status = 1
                ";
                $params = [":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                $jumlahStokFisikBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT b.hja_setting
                    FROM db1.masterf_katalog AS a
                    LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                    WHERE
                        a.kode = :kode
                        AND (b.sts_hja != 0 OR b.sts_hjapb != 0)
                    LIMIT 1
                ";
                $params = [":kode" => $kodeObat];
                $hjaSetting = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        hp_item  AS hpItem,
                        hja_item AS hjaItem
                    FROM db1.relasif_hargaperolehan
                    WHERE sts_hjapb = 1
                        AND sts_hja = 1
                        AND id_katalog = :idKatalog
                    LIMIT 1
                ";
                $params = [":idKatalog" => $kodeObat];
                $hargaPerolehan = $connection->createCommand($sql, $params)->queryOne();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        id_depo = :idDepo,
                        kode_stokopname = :kodeStokopname,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        harga_item = :hargaItem,
                        kode_reff = :kodeRef,
                        status = 1,
                        userid_last = :idUser,
                        kode_transaksi = 'D',
                        tipe_tersedia = 'pengiriman',
                        tgl_tersedia = :tanggalTersedia,
                        jumlah_masuk = 0,
                        jumlah_keluar = :jumlahKeluar,
                        harga_perolehan = :hargaPerolehan,
                        harga_jualapotik = :hargaJualApotik,
                        jumlah_tersedia = :jumlahTersedia,
                        jumlah_sebelum = :jumlahSebelum,
                        keterangan = :keterangan
                ";
                $params = [
                    ":idDepo" => $idDepoPengirim,
                    ":kodeStokopname" => $kodeStokopname,
                    ":noDokumen" => $kodeTrnPengiriman,
                    ":idKatalog" => $kodeObat,
                    ":hargaItem" => $hjaSetting,
                    ":kodeRef" => $kodeTransaksi,
                    ":idUser" => $idUser,
                    ":tanggalTersedia" => $nowValSystem,
                    ":jumlahKeluar" => $daftarJumlah[$key],
                    ":hargaPerolehan" => $hargaPerolehan->hpItem,
                    ":hargaJualApotik" => $hargaPerolehan->hjaItem,
                    ":jumlahTersedia" => $jumlahStokFisikBefore - $daftarJumlah[$key],
                    ":jumlahSebelum" => $jumlahStokFisikBefore,
                    ":keterangan" => "Pengeluaran ke " . $namaDepoPenerima,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT sts_opname
                    FROM db1.transaksif_stokopname
                    WHERE id_depo = :idDepo
                    ORDER BY tgl_doc DESC
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepoPengirim];
                $statusOpname = $connection->createCommand($sql, $params)->queryScalar();

                $jumlahAdm = ($statusOpname == 1) ? 0 : $daftarJumlah[$key];

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = jumlah_stokfisik - :pengurangStokFisik,
                        jumlah_stokadm = jumlah_stokadm - :pengurangStokAdm
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                ";
                $params = [
                    ":pengurangStokFisik" => $daftarJumlah[$key],
                    ":pengurangStokAdm" => $jumlahAdm,
                    ":idDepo" => $idDepoPengirim,
                    ":idKatalog" => $kodeObat,
                ];
                $connection->createCommand($sql, $params)->execute();
            }

            foreach ($daftarPeringatan as $cekKode) {
                if (in_array($cekKode->kodeItem, $daftarKodeObat)) continue;
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.master_warning
                    SET checking_pengiriman = 0
                    WHERE
                        no_doc = :noDokumen
                        AND kodeItem = :kode
                        AND noPermintaan = :noPermintaan
                        AND checking_pengiriman = 1
                ";
                $params = [":noDokumen" => $kodeTransaksi, ":kode" => $cekKode->kodeItem, ":noPermintaan" => $noPermintaan];
                $connection->createCommand($sql, $params)->execute();
            }
        } else {
            $kodeTransaksi = $kodeTrn;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kodeItem
                FROM db1.master_warning
                WHERE
                    no_doc = :noDokumen
                    AND noPermintaan = :noPermintaan
                    AND checking_pengiriman = 1
                ORDER BY kodeItem ASC
            ";
            $params = [":noDokumen" => $kodeTransaksi, ":noPermintaan" => $noPermintaan];
            $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();
            $daftarKode = array_map(fn($i) => $i->kodeItem, $daftarPeringatan);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT *
                FROM db1.master_warning
                WHERE
                    no_doc = :noDokumen
                    AND noPermintaan = :noPermintaan
                    AND checking_pengiriman = 1
                ORDER BY kodeItem ASC
            ";
            $params = [":noDokumen" => $kodeTransaksi, ":noPermintaan" => $noPermintaan];
            $peringatanPengeluaran = $connection->createCommand($sql, $params)->queryOne();

            $set = "";
            foreach ($peringatanPengeluaran as $keyco => $valco) {
                if (!in_array($keyco, ["kodeItem", "namaItem", "jumlah1", "kode", "jumlah2"])) {
                    $set .= ", $keyco = '$valco'";
                }
            }

            foreach ($daftarKodeObat as $key => $kodeObat) {
                if (!in_array($kodeObat, $daftarKode)) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.master_warning
                        SET
                            kodeItem = :kode,
                            namaItem = :nama,
                            jumlah1 = :jumlah,
                            jumlah2 = :jumlah
                            $set
                    ";
                    $params = [":kode" => $kodeObat, ":nama" => $daftarNamaBarang[$key], ":jumlah" => $daftarJumlah[$key]];
                    $connection->createCommand($sql, $params)->execute();

                } else {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.master_warning
                        SET jumlah2 = :jumlah2
                        WHERE
                            no_doc = :noDokumen
                            AND kodeItem = :kode
                            AND (noPermintaan = :noPermintaan OR noPermintaan = '')
                            AND checking_pengiriman = 1
                    ";
                    $params = [
                        ":jumlah2" => $daftarJumlah[$key],
                        ":noDokumen" => $kodeTransaksi,
                        ":kode" => $kodeObat,
                        ":noPermintaan" => $noPermintaan,
                    ];
                    $connection->createCommand($sql, $params)->execute();
                }
            }

            foreach ($daftarPeringatan as $cekKode) {
                if (in_array($cekKode->kodeItem, $daftarKodeObat)) continue;
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.master_warning
                    WHERE
                        no_doc = :noDokumen
                        AND kodeItem = :kode
                        AND checking_pengiriman = 1
                        AND noPermintaan = :noPermintaan
                ";
                $params = [":noDokumen" => $kodeTransaksi, ":kode" => $cekKode->kodeItem, ":noPermintaan" => $noPermintaan];
                $connection->createCommand($sql, $params)->execute();
            }
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengeluaran22    the original method
     */
    public function actionPengeluaran22Data(): string
    {
        ["id_trn" => $idTransaksi] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $fresep = "D" . Yii::$app->userFatma->kodeDepo . date("Ymd");
        $urutan = $this->getCounter2($fresep);
        do {
            $urutan = sprintf("%04d", ++$urutan);
            $kodeTransaksi = $fresep . $urutan;
            $cekKode = $this->addCounter2($kodeTransaksi);
        } while ($cekKode == "NO");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                noPermintaan       AS noPermintaan,
                verifikasi_user    AS idVerifikasiUser,
                tanggal_verifikasi AS tanggalVerifikasi,
                depoPeminta        AS kodeDepoPeminta,
                kodeDepo           AS kodeDepoPengirim,
                no_doc             AS kodeDokumenPengeluaran,
                NULL               AS daftarPengeluaran,
                ''                 AS namaVerifikasiUser,
                ''                 AS namaDepoPengirim,
                ''                 AS kodeDokumenPengiriman
            FROM db1.master_warning
            WHERE noPermintaan = :noPermintaan
            LIMIT 1
        ";
        $params = [":noPermintaan" => $idTransaksi];
        $peringatanPengeluaran = $connection->createCommand($sql, $params)->queryOne();
        $peringatanPengeluaran->kodeDokumenPengiriman = $peringatanPengeluaran->kodeDokumenPengeluaran; // TRUELY CORRECT, look at view file

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT namaDepo -- all are in use.
            FROM db1.masterf_depo
            WHERE kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $peringatanPengeluaran->kodeDepoPengirim];
        $peringatanPengeluaran->namaDepoPengirim = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.depoPeminta         AS kodeDepoPeminta,
                a.kodeDepo            AS kodeDepoPemberi,
                a.kodeItem            AS kodeBarang,
                a.nomor_batch         AS noBatch,
                a.namaItem            AS namaBarang,
                a.jumlah1             AS jumlahDiminta,
                a.jumlah2             AS jumlahDikirim,
                d.nama_kemasan        AS namaKemasan,
                c.nama_pabrik         AS namaPabrik,
                ''                    AS stokTersedia,
                ''                    AS stokPeminta
            FROM db1.master_warning AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeItem
            LEFT JOIN db1.masterf_pabrik AS c ON c.id = b.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = b.id_kemasankecil
            WHERE a.noPermintaan = :noPermintaan
            ORDER BY b.nama_barang ASC
        ";
        $params = [":noPermintaan" => $idTransaksi];
        $peringatanPengeluaran->daftarPengeluaran = $connection->createCommand($sql, $params)->queryAll();

        foreach ($peringatanPengeluaran->daftarPengeluaran as $pengeluaran) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id -- all are in use.
                FROM db1.masterf_depo
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $pengeluaran->kodeDepoPemberi];
            $idDepoPemberi = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik -- all are in use.
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
                LIMIT 1
            ";
            $params = [":idKatalog" => $pengeluaran->kodeBarang, ":idDepo" => $idDepoPemberi];
            $pengeluaran->stokTersedia = $connection->createCommand($sql, $params)->queryScalar() ?: 0;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id -- all are in use.
                FROM db1.masterf_depo
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $pengeluaran->kodeDepoPeminta];
            $idDepoPeminta = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik -- all are in use.
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
                LIMIT 1
            ";
            $params = [":idKatalog" => $pengeluaran->kodeBarang, ":idDepo" => $idDepoPeminta];
            $pengeluaran->stokPeminta = $connection->createCommand($sql, $params)->queryScalar() ?: 0;
        }

        $sql = /** @lang SQL */ " 
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT username -- all are in use.
            FROM db1.user
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $peringatanPengeluaran->idVerifikasiUser];
        $peringatanPengeluaran->namaVerifikasiUser = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        return json_encode($peringatanPengeluaran);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#pengeluaran22    the original method
     */
    public function actionSavePengeluaran22(): void
    {
        [   "noPermintaan" => $noPermintaan,
            "jumlah" => $daftarJumlah,
            "peminta" => $peminta,
            "nomor_batch" => $daftarNoBatch,
            "kode_trn" => $kodeTrn,
            "telahverif" => $telahVerif,
            "kode_obat" => $daftarKodeObat,
            "nama_barang" => $daftarNamaBarang,
            "verifikasi" => $verifikasi,
            "kode_trn_pengiriman" => $kodeTrnPengiriman,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;

        if ($peminta) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.master_warning
                SET depoPeminta = :depoPeminta
                WHERE noPermintaan = :noPermintaan
            ";
            $params = [":depoPeminta" => $peminta, ":noPermintaan" => $noPermintaan];
            $connection->createCommand($sql, $params)->execute();
        }

        if ($telahVerif == 1) return;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode                AS kode,
                tipe_pengiriman     AS tipePengiriman,
                noDistribusi        AS noDistribusi,
                noPermintaan        AS noPermintaan,
                noPengeluaran       AS noPengeluaran,
                noPenerimaan        AS noPenerimaan,
                verifikasi_user     AS verifikasiUser,
                tanggal_verifikasi  AS tanggalVerifikasi,
                verifikasi_terima   AS verifikasiTerima,
                tanggal_terima      AS tanggalTerima,
                tipe                AS tipe,
                kodeKetersediaan    AS kodeKetersediaan,
                depoPeminta         AS depoPeminta,
                kodeDepo            AS kodeDepo,
                kodeItem            AS kodeItem,            -- in use
                nomor_batch         AS noBatch,
                namaItem            AS namaItem,
                jumlah1             AS jumlah1,
                jumlah2             AS jumlah2,
                jumlah3             AS jumlah3,
                harga_perolehan     AS hargaPerolehan,
                detail              AS detail,
                prioritas           AS prioritas,
                status              AS status,
                tanggal             AS tanggal,
                no_doc              AS noDokumen,
                no_doc_pengiriman   AS noDokumenPengiriman,
                no_doc_penerimaan   AS noDokumenPenerimaan,
                checking_pengiriman AS cekPengiriman,
                checking_penerimaan AS cekPenerimaan,
                checking_double     AS cekDouble
            FROM db1.master_warning
            WHERE noPermintaan = :noPermintaan
        ";
        $params = [":noPermintaan" => $noPermintaan];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarPeringatan as $peringatan1) {
            $set = "";
            foreach ($peringatan1 as $keyco => $valco) {
                if (!in_array($keyco, ["kodeItem", "namaItem", "jumlah1", "kode", "jumlah2"])) {
                    $set .= ", $keyco = '$valco'";
                }
            }

            $got = 0;
            foreach ($daftarKodeObat as $key => $kodeObat) {
                if ($kodeObat == $peringatan1->kodeItem) {
                    $got = 1;
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.master_warning
                        SET jumlah2 = :jumlah2
                        WHERE
                            kodeItem = :kode
                            AND noPermintaan = :noPermintaan
                    ";
                    $params = [":jumlah2" => $daftarJumlah[$key], ":kode" => $kodeObat, ":noPermintaan" => $noPermintaan];
                    $connection->createCommand($sql, $params)->execute();
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT TRUE
                    FROM db1.master_warning
                    WHERE
                        kodeItem = :kode
                        AND noPermintaan = :noPermintaan
                    LIMIT 1
                ";
                $params = [":kode" => $kodeObat, ":noPermintaan" => $noPermintaan];
                $cekPeringatanPermintaan = $connection->createCommand($sql, $params)->queryScalar();
                if ($cekPeringatanPermintaan) continue;

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.master_warning
                    SET
                        kodeItem = :kode,
                        namaItem = :nama,
                        jumlah1 = :jumlah,
                        jumlah2 = :jumlah
                        $set
                ";
                $params = [":kode" => $kodeObat, ":nama" => $daftarNamaBarang[$key], ":jumlah" => $daftarJumlah[$key]];
                $connection->createCommand($sql, $params)->execute();
            }

            if ($got) continue;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                DELETE FROM db1.master_warning
                WHERE
                    kodeItem = :kode
                    AND noPermintaan = :noPermintaan
            ";
            $params = [":kode" => $peringatan1->kodeItem, ":noPermintaan" => $noPermintaan];
            $connection->createCommand($sql, $params)->execute();
        }

        if (!$verifikasi) return;

        $fresep = "D" . Yii::$app->userFatma->kodeDepo . date("Ymd");
        $urutan = $this->getCounter2($fresep);
        do {
            $urutan = sprintf("%04d", ++$urutan);
            $kodeTransaksi = $fresep . $urutan;
            $cekKode = $this->addCounter2($kodeTransaksi);
        } while ($cekKode == "NO");

        $nowValSystem = Yii::$app->dateTime->nowVal("system");

        foreach ($daftarKodeObat as $key => $kodeObat) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.master_warning
                SET
                    noPengeluaran = :noPengeluaran,
                    nomor_batch = :noBatch,
                    no_doc_pengiriman = :noDokumenPengiriman,
                    verifikasi_user = :verifikasiUser,
                    tanggal_verifikasi = :tanggalVerifikasi,
                    status = 'Pengiriman'
                WHERE
                    noPermintaan = :noPermintaan
                    AND kodeItem = :kode
                    AND noPengeluaran = ''
            ";
            $params = [
                ":noPengeluaran" => $kodeTransaksi,
                ":noBatch" => $daftarNoBatch[$key],
                ":noDokumenPengiriman" => $kodeTrnPengiriman,
                ":verifikasiUser" => $idUser,
                ":tanggalVerifikasi" => $nowValSystem,
                ":noPermintaan" => $noPermintaan,
                ":kode" => $kodeObat,
            ];
            $connection->createCommand($sql, $params)->execute();

            $idDepo = Yii::$app->userFatma->idDepo;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode_stokopname
                FROM db1.relasif_ketersediaan
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
                ORDER BY id DESC
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
            $kodeStokopnameBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
                    AND status = 1
            ";
            $params = [":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
            $jumlahStokFisikBefore = $connection->createCommand($sql, $params)->queryScalar() ?? 0;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    depoPeminta AS depoPeminta,
                    kodeDepo    AS kodeDepo
                FROM db1.master_warning
                WHERE noPermintaan = :noPermintaan
                LIMIT 1
            ";
            $params = [":noPermintaan" => $noPermintaan];
            $peringatan2 = $connection->createCommand($sql, $params)->queryOne();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT namaDepo
                FROM db1.masterf_depo
                WHERE kode = :kode
            ";
            $params = [":kode" => $peringatan2->depoPeminta];
            $namaDepoPeminta = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id
                FROM db1.masterf_depo
                WHERE kode = :kode
            ";
            $params = [":kode" => $peringatan2->kodeDepo];
            $idDepoPengirim = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    b.hp_item     AS hpItem,
                    b.hja_setting AS hjaSetting
                FROM db1.masterf_katalog AS a
                LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                WHERE
                    a.kode = :kode
                    AND (b.sts_hja != 0 OR b.sts_hjapb != 0)
                LIMIT 1
            ";
            $params = [":kode" => $kodeObat];
            $harga = $connection->createCommand($sql, $params)->queryOne();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    hp_item  AS hpItem,
                    hja_item AS hjaItem
                FROM db1.relasif_hargaperolehan
                WHERE
                    sts_hjapb = 1
                    AND sts_hja = 1
                    AND id_katalog = :idKatalog
                LIMIT 1
            ";
            $params = [":idKatalog" => $kodeObat];
            $hargaPerolehan = $connection->createCommand($sql, $params)->queryOne();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_ketersediaan
                SET
                    id_depo = :idDepo,
                    no_batch = :noBatch,
                    kode_stokopname = :kodeStokopname,
                    id_katalog = :idKatalog,
                    harga_item = :hargaItem,
                    no_doc = :noDokumen,
                    kode_reff = :kodeRef,
                    harga_perolehan = :hargaPerolehan,
                    harga_jualapotik = :hargaJualApotik,
                    status = 1,
                    kode_transaksi = 'D',
                    tipe_tersedia = 'pengiriman',
                    tgl_tersedia = :tanggalTersedia,
                    jumlah_masuk = 0,
                    jumlah_keluar = :jumlahKeluar,
                    userid_last = :idUser,
                    jumlah_tersedia = :jumlahTersedia,
                    jumlah_sebelum = :jumlahSebelum,
                    keterangan = :keterangan
            ";
            $params = [
                ":idDepo" => $idDepoPengirim,
                ":noBatch" => $daftarNoBatch[$key],
                ":kodeStokopname" => $kodeStokopnameBefore,
                ":idKatalog" => $kodeObat,
                ":hargaItem" => $harga->hjaSetting,
                ":noDokumen" => $kodeTrn,
                ":kodeRef" => $kodeTransaksi,
                ":hargaPerolehan" => $hargaPerolehan->hpItem,
                ":hargaJualApotik" => $hargaPerolehan->hjaItem,
                ":tanggalTersedia" => $nowValSystem,
                ":jumlahKeluar" => $daftarJumlah[$key],
                ":idUser" => $idUser,
                ":jumlahTersedia" => $jumlahStokFisikBefore - $daftarJumlah[$key],
                ":jumlahSebelum" => $jumlahStokFisikBefore,
                ":keterangan" => "Pengeluaran ke " . $namaDepoPeminta,
            ];
            $connection->createCommand($sql, $params)->execute();

            // update batch
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_batch
                SET id_depo = :idDepo
                WHERE
                    id_katalog = :idKatalog
                    AND batch = :batch
            ";
            $params = [":idDepo" => $idDepoPengirim, ":idKatalog" => $kodeObat, ":batch" => $daftarNoBatch[$key]];
            $connection->createCommand($sql, $params)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.master_warning
                SET jumlah2 = :jumlah2
                WHERE
                    no_doc = :noDokumen
                    AND kodeItem = :kode
                    AND (noPermintaan = :noPermintaan OR noPermintaan = '')
            ";
            $params = [
                ":jumlah2" => $daftarJumlah[$key],
                ":noDokumen" => $kodeTrn,
                ":kode" => $kodeObat,
                ":noPermintaan" => $noPermintaan,
            ];
            $connection->createCommand($sql, $params)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
            ";
            $params = [":idDepo" => $idDepoPengirim, ":idKatalog" => $kodeObat];
            $cekStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

            if ($cekStokKatalog) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT sts_opname
                    FROM db1.transaksif_stokopname
                    WHERE id_depo = :idDepo
                    ORDER BY tgl_doc DESC
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepoPengirim];
                $statusOpname = $connection->createCommand($sql, $params)->queryScalar();

                $jumlahAdm = ($statusOpname == 1) ? 0 : $daftarJumlah[$key];

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = jumlah_stokfisik - :pengurangStokFisik,
                        jumlah_stokadm = jumlah_stokadm - :pengurangStokAdm
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                ";
                $params = [
                    ":pengurangStokFisik" => $daftarJumlah[$key],
                    ":pengurangStokAdm" => $jumlahAdm,
                    ":idDepo" => $idDepoPengirim,
                    ":idKatalog" => $kodeObat,
                ];
                $connection->createCommand($sql, $params)->execute();

            } else {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = :pengurangJumlah,
                        jumlah_stokadm = :pengurangJumlah,
                        jumlah_itemfisik = :jumlah,
                        id_kemasan = 0,
                        status = 1,
                        id_depo = :idDepo,
                        id_katalog = :idKatalog
                ";
                $params = [
                    ":pengurangJumlah" => -$daftarJumlah[$key],
                    ":jumlah" => $daftarJumlah[$key],
                    ":idDepo" => $idDepoPengirim,
                    ":idKatalog" => $kodeObat,
                ];
                $connection->createCommand($sql, $params)->execute();
            }
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#hide_permintaan    the original method
     */
    public function actionHidePermintaan(): void
    {
        assert($_POST["noPermintaan"] && $_POST["idobat"], new MissingPostParamException("noPermintaan", "idobat"));
        ["noPermintaan" => $noPermintaan, "idobat" => $idObat] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.master_warning
            SET checking_pengiriman = 0
            WHERE
                noPermintaan = :noPermintaan
                AND kodeItem = :kode
        ";
        $params = [":noPermintaan" => $noPermintaan, ":kode" => $idObat];
        $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#penerimaan2    the original method
     */
    public function actionPenerimaan2Data(): string
    {
        ["noPengeluaran" => $noPengeluaran] = Yii::$app->request->post();
        $idDepo = Yii::$app->userFatma->idDepo;
        $connection = Yii::$app->dbFatma;

        $fresep = "E" . Yii::$app->userFatma->kodeDepo . date("Ymd");
        $urutan = $this->getCounter2($fresep);
        do {
            $urutan = sprintf("%04d", ++$urutan);
            $kodeTransaksi = $fresep . $urutan;
            $cekKode = $this->addCounter2($kodeTransaksi);
        } while ($cekKode == "NO");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                b.namaDepo          AS unitPeminta,
                c.namaDepo          AS unitDiminta,
                a.status            AS status,
                a.verifikasi_terima AS verifikasiTerima,
                a.tanggal_terima    AS tanggalTerima,
                a.no_doc            AS noDokumen,
                ''                  AS idPengeluaran, -- ?
                ''                  AS kodeTransaksi,
                ''                  AS tanggal,
                NULL                AS daftarPeringatan
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kodeDepo
            INNER JOIN db1.masterf_depo AS c ON c.kode = a.depoPeminta
            WHERE
                a.noPengeluaran = :noPengeluaran
                AND a.depoPeminta = :depoPeminta
            LIMIT 1
        ";
        $params = [":noPengeluaran" => $noPengeluaran, ":depoPeminta" => $idDepo];
        $peringatanPengeluaran = $connection->createCommand($sql, $params)->queryOne();
        $peringatanPengeluaran->idPengeluaran = $noPengeluaran;
        $peringatanPengeluaran->kodeTransaksi = $kodeTransaksi;
        $peringatanPengeluaran->tanggal = Yii::$app->dateTime->nowVal("system");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.namaItem     AS namaObat,
                a.kodeItem     AS kodeObat,
                c.nama_pabrik  AS namaPabrik,
                d.nama_kemasan AS namaKemasan,
                a.jumlah2      AS jumlah
            FROM db1.master_warning AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeItem
            LEFT JOIN db1.masterf_pabrik AS c ON c.id = b.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = b.id_kemasankecil
            WHERE
                noPengeluaran = :noPengeluaran
                AND a.depoPeminta = :depoPeminta
            GROUP BY a.kodeItem
            ORDER BY b.nama_barang ASC
        ";
        $params = [":noPengeluaran" => $noPengeluaran, ":depoPeminta" => $idDepo];
        $peringatanPengeluaran->daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($peringatanPengeluaran);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#penerimaan2    the original method
     */
    public function actionSavePenerimaan2(): void
    {
        [   "kode_trn" => $kodePengeluaran,
            "kode_obat" => $daftarKodeObat,
            "jumlah" => $daftarJumlah,
            "no_penerimaan" => $noPenerimaan,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $todayValSystem = Yii::$app->dateTime->todayVal("system");
        $connection = Yii::$app->dbFatma;

        $fresep = "E" . Yii::$app->userFatma->kodeDepo . date("Ymd");
        $urutan = $this->getCounter2($fresep);
        do {
            $urutan = sprintf("%04d", ++$urutan);
            $kodeTransaksi = $fresep . $urutan;
            $cekKode = $this->addCounter2($kodeTransaksi);
        } while ($cekKode == "NO");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.master_warning
            SET
                noPenerimaan = :noPenerimaan,
                verifikasi_terima = :verifikasiTerima,
                tanggal_terima = :tanggalTerima,
                status = 'Diterima'
            WHERE noPengeluaran = :noPengeluaran
        ";
        $params = [
            ":noPenerimaan" => $kodeTransaksi,
            ":verifikasiTerima" => $idUser,
            ":tanggalTerima" => $todayValSystem,
            ":noPengeluaran" => $kodePengeluaran
        ];
        $connection->createCommand($sql, $params)->execute();

        foreach ($daftarKodeObat as $key => $kodeObat) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    kodeDepo     AS kodeDepo,
                    depoPeminta  AS depoPeminta,
                    noPenerimaan AS noPenerimaan,
                    no_doc       AS noDokumen
                FROM db1.master_warning
                WHERE
                    noPengeluaran = :noPengeluaran
                    AND kodeItem = :kode
                LIMIT 1
            ";
            $params = [":noPengeluaran" => $kodePengeluaran, ":kode" => $kodeObat];
            $peringatan = $connection->createCommand($sql, $params)->queryOne();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT namaDepo -- all are in use.
                FROM db1.masterf_depo
                WHERE kode = :kode
            ";
            $params = [":kode" => $peringatan->kodeDepo];
            $namaDepoPengirim = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id -- all are in use.
                FROM db1.masterf_depo
                WHERE kode = :kode
            ";
            $params = [":kode" => $peringatan->depoPeminta];
            $idDepo = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode_stokopname -- all are in use.
                FROM db1.relasif_ketersediaan
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
                    AND status = 1
                ORDER BY id DESC
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
            $kodeStokopnameBefore = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik -- all are in use.
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
                    AND status = 1
                LIMIT 1
            ";
            $params = [":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
            $jumlahStokFisikBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE -- all are in use.
                FROM db1.masterfkatalog_aktifdepo
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
            $cekKatalogAktifDepo = $connection->createCommand($sql, $params)->queryScalar();

            if (!$cekKatalogAktifDepo) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.masterfkatalog_aktifdepo
                    SET
                        id_depo = :idDepo,
                        id_katalog = :idKatalog,
                        status_opname = 0
                ";
                $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                $connection->createCommand($sql, $params)->execute();
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    b.hja_setting AS hjaSetting,
                    b.hp_item     AS hpItem
                FROM db1.masterf_katalog AS a
                LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                WHERE
                    a.kode = :kode
                    AND b.sts_hja != 0
                LIMIT 1
            ";
            $params = [":kode" => $kodeObat];
            $harga = $connection->createCommand($sql, $params)->queryOne();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_ketersediaan
                SET
                    id_depo = :idDepo,
                    kode_stokopname = :kodeStokopname,
                    id_katalog = :idKatalog,
                    harga_item = :hargaItem,
                    harga_perolehan = :hargaPerolehan,
                    kode_reff = :kodeRef,
                    no_doc = :noDokumen,
                    status = 1,
                    kode_transaksi = 'E',
                    tipe_tersedia = 'penerimaan',
                    tgl_tersedia = :tanggalTersedia,
                    jumlah_masuk = :jumlahMasuk,
                    jumlah_keluar = 0,
                    userid_last = :idUser,
                    jumlah_tersedia = :jumlahTersedia,
                    jumlah_sebelum = :jumlahSebelum,
                    keterangan = :keterangan
            ";
            $params = [
                ":idDepo" => $idDepo,
                ":kodeStokopname" => $kodeStokopnameBefore,
                ":idKatalog" => $kodeObat,
                ":hargaItem" => $harga->hjaSetting,
                ":hargaPerolehan" => $harga->hpItem,
                ":kodeRef" => $peringatan->noPenerimaan,
                ":noDokumen" => $peringatan->noDokumen,
                ":jumlahMasuk" => $daftarJumlah[$key],
                ":jumlahTersedia" => $daftarJumlah[$key] + $jumlahStokFisikBefore,
                ":jumlahSebelum" => $jumlahStokFisikBefore,
                ":keterangan" => "Penerimaan dari " . $namaDepoPengirim,
                ":tanggalTersedia" => $nowValSystem,
                ":idUser" => $idUser,
            ];
            $connection->createCommand($sql, $params)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE -- all are in use.
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
            $cekStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

            if ($cekStokKatalog) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT sts_opname -- all are in use.
                    FROM db1.transaksif_stokopname
                    WHERE id_depo = :idDepo
                    ORDER BY tgl_doc DESC
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepo];
                $statusOpname = $connection->createCommand($sql, $params)->queryScalar();

                $jumlahAdm = ($statusOpname == 1) ? 0 : $daftarJumlah[$key];

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = jumlah_stokfisik + :stokFisik,
                        jumlah_stokadm = jumlah_stokadm + :stokAdm
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                ";
                $params = [
                    ":stokFisik" => $daftarJumlah[$key],
                    ":stokAdm" => $jumlahAdm,
                    ":idDepo" => $idDepo,
                    ":idKatalog" => $kodeObat,
                ];
                $connection->createCommand($sql, $params)->execute();

            } else {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = :jumlah,
                        jumlah_stokadm = :jumlah,
                        jumlah_itemfisik = :jumlah,
                        id_kemasan = 0,
                        status = 1,
                        id_depo = :idDepo,
                        id_katalog = :idKatalog
                ";
                $params = [":jumlah" => $daftarJumlah[$key], ":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                $connection->createCommand($sql, $params)->execute();
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.master_warning
                SET jumlah3 = :jumlah3
                WHERE
                    noPenerimaan = :noPenerimaan
                    AND kodeItem = :kode
            ";
            $params = [":jumlah3" => $daftarJumlah[$key], ":noPenerimaan" => $noPenerimaan, ":kode" => $kodeObat];
            $connection->createCommand($sql, $params)->execute();
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#print_pengambilan2    the original method
     */
    public function actionPrintPengambilan2(): string
    {
        $noPermintaan = Yii::$app->request->post("noPermintaan") ?? throw new MissingPostParamException("noPermintaan");
        $connection = Yii::$app->dbFatma;
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
                b.kode                AS idDepoAmbil,
                b.namaDepo            AS depoAmbil,
                z.namaDepo            AS namaDepoPeminta,
                a.kodeItem            AS obat,
                ''                    AS jumlah
            FROM db1.master_warning AS a
            LEFT JOIN db1.masterf_ketersediaan AS h ON h.kode = a.kodeKetersediaan
            LEFT JOIN db1.masterf_depo AS b ON b.kode = a.kodeDepo
            LEFT JOIN db1.masterf_depo AS z ON z.kode = a.depoPeminta
            LEFT JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_brand AS d ON d.id = c.id_brand
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = c.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS f ON f.id = c.id_kemasankecil
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = c.id_jenisbarang
            WHERE a.noPermintaan = :noPermintaan
            ORDER BY g.jenis_obat, c.nama_barang
        ";
        $params = [":noPermintaan" => $noPermintaan];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarPeringatan as $peringatan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT id
                FROM db1.masterf_depo
                WHERE kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $peringatan->idDepoAmbil];
            $depo = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
                    AND status = 1
                ORDER BY id_katalog DESC
                LIMIT 1
            ";
            $params = [":idDepo" => $depo, ":idKatalog" => $peringatan->obat];
            $peringatan->jumlah = $connection->createCommand($sql, $params)->queryScalar();
        }

        $view = new PrintPengambilan(
            peringatan:       $daftarPeringatan[0],
            daftarPeringatan: $daftarPeringatan,
            header:           $header, // TODO: php: uncategorized: deleted (?) while refactor

            // unused
            // transaksiPermintaanWidgetId: Yii::$app->actionToUrl([Pair::class, "actionPermintaan2"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#print_pengiriman    the original method
     */
    public function actionPrintPengiriman(): string
    {
        $noPermintaan = Yii::$app->request->post("noPermintaan") ?? throw new MissingPostParamException("noPermintaan");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc             AS noDokumen,
                a.no_doc_pengiriman  AS noDokumenPengiriman,
                a.tanggal            AS tanggal,
                a.jumlah2            AS jumlah2,
                rp.hp_item           AS hpItem,
                a.kodeItem           AS kodeItem,
                a.namaItem           AS namaItem,
                a.kodeDepo           AS kodeDepo,
                a.depoPeminta        AS depoPeminta,
                a.nomor_batch        AS noBatch,
                e.nama_pabrik        AS namaPabrik,
                a.jumlah1            AS jumlah1,
                a.jumlah2            AS jumlah2,
                f.nama_kemasan       AS namaKemasan,
                a.tanggal_verifikasi AS tanggalVerifikasi,
                b.namaDepo           AS depoAsal,
                g.jenis_obat         AS namaJenis,
                z.namaDepo           AS depoTujuan,
                h.name               AS namaVerifikasi,
                ''                   AS stokPeminta,
                ''                   AS stokPengirim
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kodeDepo
            INNER JOIN db1.masterf_depo AS z ON z.kode = a.depoPeminta
            LEFT JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_brand AS d ON d.id = c.id_brand
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = c.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS f ON f.id = c.id_kemasankecil
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = c.id_jenisbarang
            LEFT JOIN db1.user AS h ON h.id = a.verifikasi_user
            LEFT JOIN db1.relasif_hargaperolehan AS rp ON rp.id_katalog = c.kode
            WHERE
                (a.noPermintaan = :val OR a.noPengeluaran = :val OR a.noPenerimaan = :val)
                AND a.checking_pengiriman = 1
                AND rp.sts_hja = 1
                AND rp.sts_hjapb = 1
            ORDER BY g.jenis_obat, c.nama_barang
        ";
        $params = [":val" => $noPermintaan];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarPeringatan as $peringatan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT a.jumlah_stokfisik
                FROM db1.transaksif_stokkatalog a
                INNER JOIN db1.masterf_depo b ON b.id = a.id_depo
                WHERE
                    a.id_katalog = :idKatalog
                    AND b.kode = :kode
                    AND status = 1
                LIMIT 1
            ";
            $params = [":idKatalog" => $peringatan->kodeItem, ":kode" => $peringatan->depoPeminta];
            $peringatan->stokPeminta = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT a.jumlah_stokfisik
                FROM db1.transaksif_stokkatalog a
                INNER JOIN db1.masterf_depo b ON b.id = a.id_depo
                WHERE
                    a.id_katalog = :idKatalog
                    AND b.kode = :kode
                    AND status = 1
                LIMIT 1
            ";
            $params = [":idKatalog" => $peringatan->kodeItem, ":kode" => $peringatan->kodeDepo];
            $peringatan->stokPengirim = $connection->createCommand($sql, $params)->queryScalar();
        }

        $view = new PrintPengiriman(
            peringatan:       $daftarPeringatan[0],
            daftarPeringatan: $daftarPeringatan,

            // unused
            // transaksiPengirimanWidgetId: Yii::$app->actionToUrl([Pair::class, "actionPengiriman"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#print_pengirimantp    the original method
     */
    public function actionPrintPengirimanTp(): string
    {
        $kodeTransaksi =  Yii::$app->request->post("kodeTransaksi") ?? throw new MissingPostParamException("kodeTransaksi");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc             AS noDokumen,
                a.no_doc_pengiriman  AS noDokumenPengiriman,
                a.jumlah2            AS jumlah2,
                rp.hp_item           AS hpItem,
                a.kodeItem           AS kodeItem,
                a.depoPeminta        AS depoPeminta,
                a.kodeDepo           AS kodeDepo,
                a.namaItem           AS namaItem,
                a.nomor_batch        AS noBatch,
                e.nama_pabrik        AS namaPabrik,
                a.jumlah2            AS jumlah2,
                f.nama_kemasan       AS namaKemasan,
                a.tanggal            AS tanggal,
                a.tanggal_verifikasi AS tanggalVerifikasi,
                b.namaDepo           AS depoAsal,
                g.jenis_obat         AS namaJenis,
                z.namaDepo           AS depoTujuan,
                h.name               AS namaVerifikasi,
                ''                   AS stokPeminta,
                ''                   AS stokPengirim
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kodeDepo
            INNER JOIN db1.masterf_depo AS z ON z.kode = a.depoPeminta
            INNER JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_brand AS d ON d.id = c.id_brand
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = c.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS f ON f.id = c.id_kemasankecil
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = c.id_jenisbarang
            LEFT JOIN db1.user AS h ON h.id = a.verifikasi_user
            LEFT JOIN db1.relasif_hargaperolehan AS rp ON rp.id_katalog = c.kode
            WHERE
                (a.noPermintaan = :val OR a.noPengeluaran = :val OR a.noPenerimaan = :val)
                AND rp.sts_hja != 0
                AND rp.sts_hjapb != 0
            ORDER BY g.jenis_obat, c.nama_barang
        ";
        $params = [":val" => $kodeTransaksi];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarPeringatan as $peringatan) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT a.jumlah_stokfisik
                FROM db1.transaksif_stokkatalog a
                INNER JOIN db1.masterf_depo b ON b.id = a.id_depo
                WHERE
                    a.id_katalog = :idKatalog
                    AND b.kode = :kode
                    AND status = 1
                LIMIT 1
            ";
            $params = [":idKatalog" => $peringatan->kodeItem, ":kode" => $peringatan->depoPeminta];
            $peringatan->stokPeminta = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT a.jumlah_stokfisik
                FROM db1.transaksif_stokkatalog a
                INNER JOIN db1.masterf_depo b ON b.id = a.id_depo
                WHERE
                    a.id_katalog = :idKatalog
                    AND b.kode = :kode
                    AND status = 1
                LIMIT 1
            ";
            $params = [":idKatalog" => $peringatan->kodeItem, ":kode" => $peringatan->kodeDepo];
            $peringatan->stokPengirim = $connection->createCommand($sql, $params)->queryScalar();
        }

        $view = new PrintPengirimanTpTest(
            peringatan:       $daftarPeringatan[0],
            daftarPeringatan: $daftarPeringatan,

            // unused
            // listPengirimanTpWidgetId: Yii::$app->actionToUrl([Pair::class, "actionListPengirimanTp"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#history_pengiriman    the original method
     */
    public function actionHistoryPengiriman(): string
    {
        $val = Yii::$app->request->post("val") ?? throw new MissingPostParamException("val");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.jumlah2            AS jumlah2,
                rp.hp_item           AS hpItem,
                a.kodeItem           AS kodeItem,
                a.namaItem           AS namaItem,
                e.nama_pabrik        AS namaPabrik,
                f.nama_kemasan       AS namaKemasan,
                rp.hp_item           AS hpItem,
                a.no_doc             AS noDokumen,
                a.tanggal            AS tanggal,
                a.no_doc_pengiriman  AS noDokumenPengiriman,
                a.tanggal_verifikasi AS tanggalVerifikasi,
                b.namaDepo           AS depoAsal,
                g.jenis_obat         AS namaJenis,
                z.namaDepo           AS depoTujuan,
                h.name               AS namaVerifikasi
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kodeDepo
            INNER JOIN db1.masterf_depo AS z ON z.kode = a.depoPeminta
            LEFT JOIN db1.masterf_katalog AS c ON c.kode = a.kodeItem
            LEFT JOIN db1.masterf_brand AS d ON d.id = c.id_brand
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = c.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS f ON f.id = c.id_kemasankecil
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = c.id_jenisbarang
            LEFT JOIN db1.user AS h ON h.id = a.verifikasi_user
            LEFT JOIN db1.relasif_hargaperolehan AS rp ON rp.id_katalog = c.kode
            WHERE
                (a.noPermintaan = :val OR a.noPengeluaran = :val OR a.noPenerimaan = :val)
                AND rp.sts_hja = 1
            ORDER BY g.jenis_obat, c.nama_barang
        ";
        $params = [":val" => $val];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        $view = new PrintPengiriman22(
            peringatan:       $daftarPeringatan[0],
            daftarPeringatan: $daftarPeringatan,

            // unused
            // laporanTransaksiWidgetId: Yii::$app->actionToUrl([Pair::class, "actionLaporan"]),
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#laporan    the original method
     */
    public function actionLaporanData(): string
    {
        [   "dariTanggal" => $tanggalAwal,
            "sampaiTanggal" => $tanggalAkhir,
            "noDokumen" => $noDokumen
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                WARN.noPengeluaran     AS noPengeluaran,
                WARN.noPenerimaan      AS noPenerimaan,
                WARN.noPermintaan      AS noPermintaan,
                WARN.no_doc            AS noDokumen,
                WARN.no_doc_penerimaan AS noDokumenPenerimaan,
                WARN.no_doc_pengiriman AS noDokumenPengiriman,
                DPEM.namaDepo          AS namaDepoPeminta,
                DDIM.namaDepo          AS namaDepoDiminta,
                WARN.status            AS status,
                WARN.tanggal           AS tanggal
            FROM db1.master_warning AS WARN
            LEFT JOIN db1.masterf_depo AS DPEM ON DPEM.kode = WARN.depoPeminta
            LEFT JOIN db1.masterf_depo AS DDIM ON DDIM.kode = WARN.kodeDepo
            WHERE
                (depoPeminta = :idDepo OR kodeDepo = :idDepo)
                AND (noPermintaan != '' OR noPengeluaran != '')
                AND tanggal >= :tanggalAwal
                AND tanggal <= :tanggalAkhir
                AND (:noDokumen = '' OR no_doc LIKE :noDokumen OR no_doc_pengiriman LIKE :noDokumen)
            GROUP BY
                noPermintaan,
                noPengeluaran
        ";
        $params = [
            ":idDepo" => Yii::$app->userFatma->idDepo,
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPeringatan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#getcounter2    the original method
     */
    private function getCounter2(string $id): int
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.counterid
            WHERE counter LIKE :counter
        ";
        $params = [":counter" => "$id%"];
        return (int) $connection->createCommand($sql, $params)->queryScalar() ?: 0;
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#addcounter2    the original method
     */
    private function addCounter2(string $id): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.counterid
            SET counter = :counter
        ";
        $params = [":counter" => $id];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();

        return $berhasilTambah ? "OK" : "NO";
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/transaksi.php#list_penerimaan    the original method
     */
    public function actionTablePenerimaanData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.no_doc        AS noDokumen,
                b.namaDepo      AS namaDepo,
                a.tanggal       AS tanggal,
                a.noPengeluaran AS noPengeluaran
            FROM db1.master_warning AS a
            INNER JOIN db1.masterf_depo AS b ON b.kode = a.kodeDepo
            WHERE
                a.depoPeminta = :idDepo
                AND a.noPengeluaran != ''
                AND a.noPenerimaan = ''
            GROUP BY noPengeluaran
            ORDER BY a.kode DESC
        ";
        $params = [":idDepo" => Yii::$app->userFatma->idDepo];
        $daftarPeringatan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarPeringatan);
    }
}
