<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\controllers\StokopnameUiController as Pair;
use tlm\his\FatmaPharmacy\models\{
    DataAlreadyExistException,
    DataNotExistException,
    FailToInsertException,
    FailToUpdateException,
    FarmasiModel2
};
use tlm\his\FatmaPharmacy\views\Stokopname\{
    BelumInput,
    OpnameUser,
    PrintExpired,
    PrintFloorStock,
    PrintKoreksi,
    PrintRusak,
    PrintRusakRekap,
};
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
class StokopnameController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#history    the original method
     */
    public function actionTableHistoryData(): string
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                kode        AS kode,
                tgl_selesai AS tanggalSelesai
            FROM db1.masterf_aktifasiso
            WHERE status = 1
            ORDER BY tgl_adm DESC
            LIMIT 1
        ";
        $aktifasiSo = $connection->createCommand($sql)->queryOne();

        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        if ($aktifasiSo && strtotime($nowValSystem) > strtotime($aktifasiSo->tanggalSelesai)) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_aktifasiso
                SET status = 0
                WHERE kode = :kode
            ";
            $params = [":kode" => $aktifasiSo->kode];
            $connection->createCommand($sql, $params)->execute();
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                USR.name          AS updatedBy,
                ASO.kode          AS kode,
                ASO.tgl_adm       AS tanggalAdm,
                ASO.tgl_doc       AS tanggalDokumen,
                ASO.keterangan    AS keterangan,
                ASO.tgl_mulai     AS tanggalMulai,
                ASO.tgl_selesai   AS tanggalSelesai,
                ASO.status_opname AS statusOpname,
                ASO.sysdate_updt  AS updatedTime
            FROM db1.masterf_aktifasiso AS ASO
            LEFT JOIN db1.user AS USR ON userid_updt = USR.id
            ORDER BY ASO.kode ASC
        ";
        $daftarAktifasiSo = $connection->createCommand($sql)->queryAll();

        return json_encode($daftarAktifasiSo);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataAlreadyExistException
     * @throws FailToInsertException
     * @throws FailToUpdateException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#activation    the original method
     */
    public function actionSaveAktivasi(): void
    {
        [   "id" => $id,
            "tanggalDokumen" => $tanggalDokumen,
            "tanggalAdm" => $tanggalAdm,
            "tanggalMulai" => $tanggalMulai,
            "tanggalSelesai" => $tanggalSelesai,
            "keterangan" => $keterangan,
            "action" => $action,
            "aktifasi" => $aktifasi,
            "kode" => $kode,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;

        $dateTime = Yii::$app->dateTime;
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $toSystemDatetime = $dateTime->transformFunc("toSystemDatetime");
        $nowValSystem = $dateTime->nowVal("system");
        $todayValSystem = $dateTime->todayVal("system");

        $tahun = date("Y", strtotime($tanggalDokumen));
        $bulan1digit = date("n", strtotime($tanggalDokumen));
        $bulan2digit = date("m", strtotime($tanggalDokumen));
        $tanggal = date("d", strtotime($tanggalDokumen));

        $dataAktivasiSo = [
            "tgl_doc" => $toSystemDate($tanggalDokumen),
            "bln_doc" => $bulan1digit,
            "thn_doc" => $tahun,
            "tgl_adm" => $toSystemDatetime($tanggalAdm),
            "tgl_mulai" => $toSystemDatetime($tanggalMulai),
            "tgl_selesai" => $toSystemDatetime($tanggalSelesai),
            "keterangan" => $keterangan,
            "status_opname" => $aktifasi,
            "status" => $action == "add" ? $aktifasi : 1,
            "userid_updt" => $idUser,
        ];

        $fm2 = new FarmasiModel2;
        $connection = Yii::$app->dbFatma;

        if ($action == "add") {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT TRUE
                FROM db1.masterf_aktifasiso
                WHERE status_opname = 1
                LIMIT 1
            ";
            $adaAktifasiSo = $connection->createCommand($sql)->queryScalar();
            if ($adaAktifasiSo) throw new DataAlreadyExistException("Aktifasi SO", "Status Opname", 1);

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(*) jum
                FROM db1.masterf_aktifasiso
                WHERE kode LIKE :kode
            ";
            $params = [":kode" => "KDO" . date("Y") . "%"];
            $jumlahAktivasiSo = $connection->createCommand($sql, $params)->queryScalar();

            $kode = "KDO" . date("Ym") . str_pad($jumlahAktivasiSo + 1, 6, "0", STR_PAD_LEFT);
            $dataAktivasiSo = [
                ...$dataAktivasiSo,
                "userid_in" => $idUser,
                "sysdate_in" => $todayValSystem,
                "kode" => $kode,
            ];
            $daftarField = array_keys($dataAktivasiSo);

            // Mematikan semua stok opname yang aktif
            // set status opname masing - masing depo di aktifkan dengan yang terbaru
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_stokopname
                SET
                    sts_opname = 0
                    AND sts_aktif = 0
                WHERE TRUE
            ";
            $connection->createCommand($sql)->execute();

            $berhasilTambah = $fm2->saveData("masterf_aktifasiso", $daftarField, $dataAktivasiSo);
            if (!$berhasilTambah) throw new FailToInsertException("Aktifasi SO");

            // status_opname=>kode opname yang dipake
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    id          AS id,
                    namaDepo    AS namaDepo,
                    kd_sub_unit AS kodeSubUnit
                FROM db1.masterf_depo
                WHERE keterangan = 'depo'
            ";
            $daftarDepo = $connection->createCommand($sql)->queryAll();

            /** @noinspection PhpNamedArgumentsWithChangedOrderInspection */
            $dataStokopname = array_map(array: $daftarDepo, callback: fn($depo) => [
                "kode" => "O" . $depo->kodeSubUnit . $tahun . $bulan2digit . $tanggal . "0001",
                "no_doc" => "OP-" . $depo->kodeSubUnit . $tahun . $bulan2digit . $tanggal . "001",
                "tgl_doc" => $tanggalDokumen,
                "bln_doc" => $bulan1digit,
                "thn_doc" => $tahun,
                "tgl_adm" => $tanggalAdm,
                "id_depo" => $depo->id,
                "kode_reff" => $kode,
                "tgl_reff" => $tanggalDokumen,
                "keterangan" => "Stok opname tanggal $tanggalAdm unit/depo {$depo->namaDepo} ",
                "sts_aktif" => 1,
                "userid_in" => $idUser,
                "sysdate_in" => $nowValSystem,
                "userid_updt" => $idUser,
                "sysdate_updt" => $nowValSystem,
            ]);
            $fm2->saveBatch("transaksif_stokopname", $dataStokopname);

        } elseif ($action == "edit") {
            $daftarField = array_keys($dataAktivasiSo);

            if ($aktifasi == "0") {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokopname
                    SET sts_aktif = 0
                    WHERE kode_reff = :kodeRef
                ";
                $params = [":kodeRef" => $kode];
                $connection->createCommand($sql, $params)->execute();
            }

            $where = ["id" => $id];
            $berhasilUbah = $fm2->saveData("masterf_aktifasiso", $daftarField, $dataAktivasiSo, $where);
            if (!$berhasilUbah) throw new FailToUpdateException("Aktifasi SO", "id", $id);

            if ($aktifasi != "0") return;

            // close stok opname semua depo saat stok opname di closed
            // TODO: sql: uncategorized: debug this broken SQL (where clause is missing)
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_stokopname
                SET
                    sts_opname = 0
                    AND sts_koreksi = 1
                WHERE TRUE
            ";
            $connection->createCommand($sql)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_aktifasiso
                SET status_opname = 0
                WHERE TRUE
            ";
            $connection->createCommand($sql)->execute();
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#editactivation    the original method
     */
    public function actionFormAktivasiData(): string
    {
        ["kode" => $kode] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                id          AS id,
                kode        AS kode,
                tgl_adm     AS tanggalAdm,
                tgl_doc     AS tanggalDokumen,
                keterangan  AS keterangan,
                tgl_mulai   AS tanggalMulai,
                tgl_selesai AS tanggalSelesai
            FROM db1.masterf_aktifasiso
            WHERE kode = :kode
        ";
        $params = [":kode" => $kode];
        $aktivasiSo = $connection->createCommand($sql, $params)->queryOne();
        if (!$aktivasiSo) throw new DataNotExistException($kode);

        // MISSING "$aktivasiSo->aktifasi"
        return json_encode($aktivasiSo);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#opnameuser    the original method
     */
    public function actionTableOpnameUserData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_depo           AS idDepo,
                A.id_user           AS idUser,
                A.kode_reff         AS kode,
                B.namaDepo          AS namaDepo,
                C.name              AS namaUser,
                D.kode_reff         AS kodeAdm,
                D.tgl_adm           AS tanggalAdm,
                D.no_doc            AS noDokumen,
                D.ver_stokopname    AS verStokOpname,
                D.ver_tglstokopname AS verTanggalStokOpname,
                D.keterangan        AS keterangan
            FROM db1.relasif_opnameuser AS A
            LEFT JOIN db1.masterf_depo AS B ON A.id_depo = B.id
            LEFT JOIN db1.transaksif_stokopname AS D ON A.kode_reff = D.kode
            LEFT JOIN db1.user AS C ON A.id_user = C.id
            GROUP BY A.id_user, A.id_depo, A.kode_reff
            ORDER BY D.tgl_adm DESC
        ";
        $daftarOpnameUser = $connection->createCommand($sql)->queryAll();

        return json_encode($daftarOpnameUser);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#viewopnameusergas    the original method
     */
    public function actionViewOpnameUserGasData(): string
    {
        ["kodeRef" => $kodeRef, "idUser" => $idUser, "idDepo" => $idDepo] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id                AS id,
                A.id_user           AS idUser,
                A.id_depo           AS idDepo,
                A.kode_reff         AS kodeRef,
                A.no_urut           AS noUrut,
                A.id_katalog        AS idKatalog,
                A.id_pabrik         AS idPabrik,
                A.id_kemasan        AS idKemasan,
                A.no_batch          AS noBatch,
                A.tgl_exp           AS tanggalKadaluarsa, -- in use
                A.stok_adm          AS stokAdm,           -- in use
                A.stok_fisik        AS stokFisik,         -- in use
                A.hp_item           AS hpItem,            -- in use
                A.userid_in         AS useridInput,
                A.sysdate_in        AS sysdateInput,
                A.userid_updt       AS useridUpdate,
                A.sysdate_updt      AS sysdateUpdate,
                B.kode              AS kode,              -- in use
                B.nama_barang       AS namaBarang,        -- in use
                B.id_kelompokbarang AS kodeKelompok,
                C.nama_pabrik       AS namaPabrik,        -- in use
                D.kode              AS satuan,            -- in use
                F.kelompok_barang   AS kelompokBarang,
                G.namaDepo          AS namaDepo,
                H.tgl_adm           AS tanggalAdm,
                I.name              AS namaUser,
                BSO.jumlah_stokadm  AS cadanganStokAdm
            FROM db1.relasif_opnameuser AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
            LEFT JOIN db1.masterf_kelompokbarang AS F ON B.id_kelompokbarang = F.id
            LEFT JOIN db1.masterf_depo AS G ON A.id_depo = G.id
            LEFT JOIN db1.user AS I ON A.id_user = I.id
            LEFT JOIN db1.transaksif_stokopname AS H ON A.kode_reff = H.kode
            LEFT JOIN (
                SELECT
                    distinct id_katalog,
                    jumlah_stokadm
                FROM db1.masterf_backupstok_so
                WHERE
                    kode_reff = :kodeRef
                    AND id_depo = :idDepo
            ) BSO ON A.id_katalog = BSO.id_katalog
            WHERE
                A.id_user = :idUser
                AND A.id_depo = :idDepo
                AND A.kode_reff = :kodeRef
            ORDER BY B.kode, B.nama_barang
        ";
        $params = [":kodeRef" => $kodeRef, ":idUser" => $idUser, ":idDepo" => $idDepo];
        $daftarOpnameUser = $connection->createCommand($sql, $params)->queryAll();

        $daftarBaris = [];
        $totalSebelumStokOpname = 0;
        $totalSesudahStokOpname = 0;

        $jumlahData = count($daftarOpnameUser);
        $b = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $kodeSaatIni = "";
        $subtotalJumlahFisik = 0;

        foreach ($daftarOpnameUser as $i => $opnameUser) {
            $kode = $opnameUser->kode;

            if ($kodeSaatIni != $kode) {
                $kodeSaatIni = $kode;
                $bJudul = $b;
                $noData = 1;
                $subtotalJumlahFisik = 0;

                $daftarBaris[$bJudul] = [
                    "no" => $noJudul++,
                    "kode_kelompok" => $opnameUser->kode,
                    "nama_gas" => $opnameUser->namaBarang,
                    "nama_pabrik" => $opnameUser->namaPabrik,
                    "satuan" => $opnameUser->satuan,
                    "tgl_exp" => $opnameUser->tanggalKadaluarsa,
                    "stok_adm" => $opnameUser->stokAdm,
                    "harga_pokok" => $opnameUser->hpItem,
                    "subtotal_jumlah_fisik" => 0,
                    "subtotal_jumlah_selisih" => 0,
                ];
            }

            $daftarBaris[$b] = [
                "i" => $i,
                "no" =>$noJudul .".". $noData++ .".",
            ];

            $subtotalJumlahFisik += $opnameUser->stokFisik;

            $b++;

            if ($i + 1 == $jumlahData) {
                $barisTambahan = true;
            } else {
                $barisTambahan = $kode != ($daftarOpnameUser[$i + 1])->kode;
            }
            if (!$barisTambahan) continue;

            $sebelumStokOpname = $opnameUser->stokAdm * $opnameUser->hpItem;
            $sesudahStokOpname = $subtotalJumlahFisik * $opnameUser->hpItem;

            $daftarBaris[$b++] = [
                "subtotal_sebelum_stok_opname" => $sebelumStokOpname,
                "subtotal_sesudah_stok_opname" => $sesudahStokOpname,
                "subtotal_selisih" => $sesudahStokOpname - $sebelumStokOpname,
            ];

            $subtotalJumlahSelisih = $subtotalJumlahFisik - $opnameUser->stokAdm;

            $daftarBaris[$bJudul]["subtotal_jumlah_fisik"] = $subtotalJumlahFisik;
            $daftarBaris[$bJudul]["subtotal_nilai_fisik"] = $subtotalJumlahFisik * $opnameUser->hpItem;
            $daftarBaris[$bJudul]["subtotal_jumlah_selisih"] = $subtotalJumlahSelisih;
            $daftarBaris[$bJudul]["subtotal_nilai_selisih"] = $subtotalJumlahSelisih - $opnameUser->stokAdm;

            $totalSebelumStokOpname += $sebelumStokOpname;
            $totalSesudahStokOpname += $sesudahStokOpname;
        }

        return json_encode ([
            "opnameUser" =>             $daftarOpnameUser[0],
            "daftarOpnameUser" =>       $daftarOpnameUser,
            "daftarBaris" =>            $daftarBaris,
            "totalSebelumStokOpname" => $totalSebelumStokOpname,
            "totalSesudahStokOpname" => $totalSesudahStokOpname,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#viewopnamedepo    the original method
     */
    public function actionViewOpnameDepoData(): string
    {
        ["kodeRef" => $kodeRef, "idDepo" => $idDepo] = Yii::$app->request->post();
        $bulanso = substr($kodeRef, 3, 6);
        $bulansox = intval($bulanso);

        $connection = Yii::$app->dbFatma;

        if ($bulansox >= 201509) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.kode_reff          AS kodeRef,
                    A.id_katalog         AS idKatalog,
                    A.id_pabrik          AS idPabrik,
                    A.id_kemasan         AS idKemasan,
                    A.tgl_exp            AS tanggalKadaluarsa,
                    A.jumlah_adm         AS jumlahAdm,
                    A.jumlah_fisik       AS jumlahFisik,       -- in use
                    A.hp_item            AS hpItem,            -- in use
                    A.reff_harga         AS refHarga,
                    A.userid_in          AS useridInput,
                    A.sysdate_in         AS sysdateInput,
                    A.userid_updt        AS useridUpdate,
                    A.sysdate_updt       AS sysdateUpdate,
                    B.kode               AS kode,
                    B.nama_barang        AS namaBarang,
                    B.id_kelompokbarang  AS kodeKelompok,      -- in use
                    C.nama_pabrik        AS namaPabrik,
                    D.kode               AS satuan,
                    F.kelompok_barang    AS kelompokBarang,    -- in use
                    G.namaDepo           AS namaDepo,
                    H.tgl_adm            AS tanggalAdm,
                    BSO.jumlah_stokfisik AS cadanganStokAdm    -- in use
                FROM db1.relasif_stokopname AS A
                LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
                LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
                LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS F ON B.id_kelompokbarang = F.id
                LEFT JOIN db1.transaksif_stokopname AS H ON A.kode_reff = H.kode
                LEFT JOIN db1.masterf_depo AS G ON H.id_depo = G.id
                LEFT JOIN (
                    SELECT
                        distinct id_katalog,
                        jumlah_stokfisik
                    FROM db1.masterf_backupstok_so
                    WHERE
                        kode_reff = :kodeRef
                        AND id_depo = :idDepo
                ) AS BSO ON A.id_katalog = BSO.id_katalog
                WHERE
                    H.id_depo = :idDepo
                    AND A.kode_reff = :kodeRef
                ORDER BY
                    B.id_kelompokbarang,
                    B.kode,
                    B.nama_barang
            ";
        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    A.id_katalog        AS idKatalog,
                    A.jumlah_adm        AS cadanganStokAdm,   -- in use
                    A.jumlah_fisik      AS jumlahFisik,       -- in use
                    A.hp_item           AS hpItem,            -- in use
                    A.tgl_exp           AS tanggalKadaluarsa,
                    B.kode              AS kode,
                    B.nama_barang       AS namaBarang,
                    B.id_kelompokbarang AS kodeKelompok,      -- in use
                    C.nama_pabrik       AS namaPabrik,
                    D.kode              AS satuan,
                    F.kelompok_barang   AS kelompokBarang,    -- in use
                    G.namaDepo          AS namaDepo,
                    H.tgl_adm           AS tanggalAdm
                FROM db1.relasif_stokopname AS A
                LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
                LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
                LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
                LEFT JOIN db1.masterf_kelompokbarang AS F ON B.id_kelompokbarang = F.id
                LEFT JOIN db1.transaksif_stokopname AS H ON A.kode_reff = H.kode
                LEFT JOIN db1.masterf_depo AS G ON H.id_depo = G.id
                WHERE
                    H.id_depo = :idDepo
                    AND A.kode_reff = :kodeRef
                ORDER BY B.id_kelompokbarang, B.kode, B.nama_barang
            ";
        }
        $params = [":kodeRef" => $kodeRef, ":idDepo" => $idDepo];
        $daftarStokopname = $connection->createCommand($sql, $params)->queryAll();

        $daftarBaris = [];
        $totalNilaiAdm = 0;
        $totalNilaiFisik = 0;
        $totalJumlahAdm = 0;
        $totalJumlahFisik = 0;
        $totalSelisihJumlahSurplus = 0;
        $totalSelisihJumlahMinus = 0;
        $totalSelisihNilaiSurplus = 0;
        $totalSelisihNilaiMinus = 0;

        $b = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $kelompokSaatIni = "";

        foreach ($daftarStokopname as $i => $stokopname) {
            $nilaiFisik = $stokopname->jumlahFisik * $stokopname->hpItem;
            $nilaiSebelumSo = $stokopname->cadanganStokAdm * $stokopname->hpItem;
            $jumlahFisik = $stokopname->jumlahFisik;
            $jumlahSebelumSo = $stokopname->cadanganStokAdm;
            $nilaiAdm = $stokopname->cadanganStokAdm * $stokopname->hpItem;

            if ($kelompokSaatIni != $stokopname->kodeKelompok) {
                $kelompokSaatIni = $stokopname->kodeKelompok;
                $bJudul = $b;
                $noData = 1;

                $daftarBaris[$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_kelompok" => $stokopname->kelompokBarang,
                    "subtotal_nilai_fisik" => 0,
                    "subtotal_nilai_adm" => 0,
                ];
            }

            $daftarBaris[$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
                "nilai_fisik" => $nilaiFisik,
                "selisih_jumlah" => $stokopname->jumlahFisik - $stokopname->cadanganStokAdm,
                "selisih_nilai" => $nilaiFisik - $nilaiAdm
            ];

            ($nilaiSebelumSo > $nilaiFisik)
                ? $totalSelisihNilaiMinus += $nilaiFisik - $nilaiSebelumSo
                : $totalSelisihNilaiSurplus += $nilaiFisik - $nilaiSebelumSo;

            ($jumlahSebelumSo > $jumlahFisik)
                ? $totalSelisihJumlahMinus += $jumlahFisik - $jumlahSebelumSo
                : $totalSelisihJumlahSurplus += $jumlahFisik - $jumlahSebelumSo;

            $daftarBaris[$bJudul]["subtotal_nilai_adm"] += $nilaiAdm;
            $daftarBaris[$bJudul]["subtotal_nilai_fisik"] += $nilaiFisik;

            $totalNilaiAdm += $nilaiAdm;
            $totalNilaiFisik += $nilaiFisik;
            $totalJumlahAdm += $stokopname->cadanganStokAdm;
            $totalJumlahFisik += $stokopname->jumlahFisik;
        }

        $totalSelisihNilaiAbsolut = abs($totalSelisihNilaiSurplus - $totalSelisihNilaiMinus);
        $totalSelisihJumlahAbsolut = abs($totalSelisihJumlahSurplus - $totalSelisihJumlahMinus);

        return json_encode([
            "stokopname" =>                $daftarStokopname[0] ?? false,
            "daftarStokopname" =>          $daftarStokopname,
            "daftarBaris" =>               $daftarBaris,
            "totalNilaiAdm" =>             $totalNilaiAdm,
            "totalNilaiFisik" =>           $totalNilaiFisik,
            "totalJumlahAdm" =>            $totalJumlahAdm,
            "totalJumlahFisik" =>          $totalJumlahFisik,
            "totalSelisihJumlahSurplus" => $totalSelisihJumlahSurplus,
            "totalSelisihJumlahMinus" =>   $totalSelisihJumlahMinus,
            "totalSelisihNilaiSurplus" =>  $totalSelisihNilaiSurplus,
            "totalSelisihNilaiMinus" =>    $totalSelisihNilaiMinus,
            "totalSelisihNilaiAbsolut" =>  $totalSelisihNilaiAbsolut,
            "totalSelisihJumlahAbsolut" => $totalSelisihJumlahAbsolut,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#viewbeluminput    the original method
     */
    public function actionViewBelumInput(): string
    {
        ["kodeRef" => $kodeRef, "idDepo" => $idDepo] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_depo            AS idDepo,
                A.id_katalog         AS idKatalog,
                A.id_kemasan         AS idKemasan,
                A.jumlah_stokmin     AS jumlahStokMin,
                A.jumlah_stokmax     AS jumlahStokMaks,
                A.jumlah_stokfisik   AS jumlahStokFisik,
                A.jumlah_stokadm     AS jumlahStokAdm,
                A.jumlah_itemfisik   AS jumlahItemFisik,
                A.status             AS status,
                A.userid_in          AS useridInput,
                A.sysdate_in         AS sysdateInput,
                A.userid_updt        AS useridUpdate,
                A.sysdate_updt       AS sysdateUpdate,
                A.keterangan         AS keterangan,
                A.check_sync         AS cekSync,
                B.kode               AS kode,
                B.nama_barang        AS namaBarang,
                B.id_pabrik          AS idPabrik,
                B.id_jenisbarang     AS kodeJenis,      -- in use
                B.id_kelompokbarang  AS kodeKelompok,   -- in use
                C.nama_pabrik        AS namaPabrik,
                D.kode               AS satuan,
                E.jenis_obat         AS jenisObat,      -- in use
                F.kelompok_barang    AS kelompokBarang, -- in use
                G.namaDepo           AS namaDepo,       -- in use
                H.tgl_adm            AS tanggalAdm,     -- in use
                I.hp_item            AS hpItem,         -- in use
                BSO.jumlah_stokfisik AS cadanganStokAdm -- in use
            FROM db1.transaksif_stokkatalog AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON B.id_kemasankecil = D.id
            LEFT JOIN db1.masterf_jenisobat AS E ON B.id_jenisbarang = E.id
            LEFT JOIN db1.masterf_kelompokbarang AS F ON B.id_kelompokbarang = F.id
            LEFT JOIN db1.transaksif_stokopname AS H ON H.id_depo = :idDepo
            LEFT JOIN db1.masterf_depo AS G ON A.id_depo = G.id
            LEFT JOIN db1.masterf_backupstok_so AS BSO ON A.id_katalog = BSO.id_katalog
            LEFT JOIN (
                SELECT
                    A.id_katalog  AS id_katalog,
                    A.kode_reff   AS kode_reff,
                    A.hna_item    AS hna,
                    A.hp_item     AS hp_item,
                    A.phja        AS phja,
                    A.phjapb      AS phjapb,
                    A.hja_item    AS hja,
                    A.diskon_item AS diskon
                FROM db1.relasif_hargaperolehan AS A
                INNER JOIN (
                    SELECT
                        id_katalog  AS id_katalog,
                        MAX(tgl_hp) AS MaxDateTime
                    FROM db1.relasif_hargaperolehan
                    GROUP BY id_katalog
                ) AS B ON A.id_katalog = B.id_katalog
                WHERE A.tgl_hp = B.MaxDateTime
            ) AS I ON A.id_katalog = I.id_katalog
            WHERE
                A.id_depo = :idDepo
                AND H.kode = :kodeRef
                AND BSO.kode_reff = :kodeRef
                AND BSO.id_depo = :idDepo
                AND BSO.jumlah_stokfisik != 0
                AND A.id_katalog NOT IN (
                    SELECT id_katalog
                    FROM db1.relasif_stokopname AS A
                    WHERE
                        A.id_depo = :idDepo
                        AND A.kode_reff = :kodeRef
                    GROUP BY id_katalog, id_depo
                )
            ORDER BY A.id_katalog ASC
        ";
        $params = [":idDepo" => $idDepo, ":kodeRef" => $kodeRef];
        $daftarOpname = $connection->createCommand($sql, $params)->queryAll();
        if (!$daftarOpname) return "tidak ada data";

        $daftarHalaman = [];
        $totalNilai = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $barisPerHalaman = 50;
        $kodeJenisSaatIni = "";
        $kodeKelompokSaatIni = "";

        foreach ($daftarOpname as $i => $opname) {
            if ($kodeJenisSaatIni != $opname->kodeJenis) {
                $kodeJenisSaatIni = $opname->kodeJenis;
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "nama_jenis" => $opname->jenisObat,
                    "total_nilai" => 0,
                ];
            }

            if ($kodeKelompokSaatIni != $opname->kodeKelompok) {
                $kodeKelompokSaatIni = $opname->kodeKelompok;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "nama_kelompok" => $opname->kelompokBarang,
                    "subtotal_nilai" => 0,
                ];
            }

            $nilai = $opname->cadanganStokAdm * $opname->hpItem;

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++,
                "nilai" => $nilai,
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal_nilai"] += $nilai;
            $daftarHalaman[$hJudul1][$bJudul1]["total_nilai"] += $nilai;
            $totalNilai += $nilai;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new BelumInput(
            daftarHalaman: $daftarHalaman,
            tanggalAdm:    $daftarOpname[0]->tanggalAdm,
            namaDepo:      $daftarOpname[0]->namaDepo,
            daftarOpname:  $daftarOpname,
            totalNilai:    $totalNilai,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#index    the original method
     */
    public function actionTableStokopnameData(): string
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                DPO.namaDepo      AS namaDepo,
                USR.name          AS inputBy,
                UPD.name          AS namaUpdate,
                SO.kode           AS kode,
                SO.no_doc         AS noDokumen,
                SO.tgl_doc        AS tanggalDokumen,
                SO.tgl_adm        AS tanggalAdm,
                SO.id_depo        AS idDepo,
                SO.kode_reff      AS kodeRef,
                SO.sts_opname     AS statusOpname1,
                SO.ver_stokopname AS verStokOpname,
                SO.sysdate_updt   AS updatedTime,
                ASO.status_opname AS statusOpname2
            FROM db1.transaksif_stokopname AS SO
            LEFT JOIN db1.masterf_aktifasiso AS ASO ON SO.kode_reff = ASO.kode
            LEFT JOIN db1.user AS USR ON SO.userid_in = USR.id
            LEFT JOIN db1.user AS UPD ON SO.userid_updt = UPD.id
            LEFT JOIN db1.masterf_depo AS DPO ON SO.id_depo = DPO.id
            ORDER BY SO.tgl_adm DESC
        ";
        $daftarStokopname = $connection->createCommand($sql)->queryAll();

        return json_encode($daftarStokopname);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @throws FailToUpdateException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#detailopname    the original method
     */
    public function actionSaveDetailOpname(): void
    {
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;
        ["id_depo" => $idDepo, "kode" => $kode] = Yii::$app->request->post();

        $dataStokOpname = [
            "ver_stokopname" => 1,
            "ver_tglstokopname" => $nowValSystem,
            "ver_usrstokopname" => $idUser,
            "userid_updt" => $idUser,
            "sts_opname" => 0,
            "sts_aktif" => 1,
        ];

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_stokopname
            SET sts_aktif = 0
            WHERE id_depo = :idDepo
        ";
        $params = [":idDepo" => $idDepo];
        $connection->createCommand($sql, $params)->execute();

        $daftarField = array_keys($dataStokOpname);
        $where = ["id_depo" => $idDepo, "kode" => $kode];
        $berhasilUbah = (new FarmasiModel2)->saveData("transaksif_stokopname", $daftarField, $dataStokOpname, $where);

        if (!$berhasilUbah) throw new FailToUpdateException("transaksif_stokopname", "id_depo", $idDepo);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT id_katalog AS idKatalog -- all are in use, no view file.
            FROM db1.transaksif_stokkatalog
            WHERE id_depo = :idDepo
        ";
        $params = [":idDepo" => $idDepo];
        $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarStokKatalog as $stokKatalog) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_stokkatalog
                SET jumlah_stokadm = jumlah_stokfisik
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $stokKatalog->idKatalog];
            $connection->createCommand($sql, $params)->execute();
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#detailopname    the original method
     */
    public function actionFormDetailOpnameData(): string
    {
        ["kode" => $kode] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode              AS kodeTransaksi,
                A.id_depo           AS idDepo,
                A.tgl_doc           AS tanggalDokumen,
                A.tgl_adm           AS tanggalAdm,
                A.ver_stokopname    AS verifikasiStokopname,
                U.name              AS verUserStokopname,
                A.ver_tglstokopname AS verTanggalStokopname,
                NULL                AS daftarStokOpname
            FROM db1.transaksif_stokopname AS A
            LEFT JOIN db1.user AS U ON U.id = ver_usrstokopname
            WHERE A.kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $stokOpname = $connection->createCommand($sql, $params)->queryOne();
        if (!$stokOpname) throw new DataNotExistException($kode);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.id_katalog   AS idKatalog,
                A.id_pabrik    AS idPabrik,
                A.id_kemasan   AS idKemasan,
                A.tgl_exp      AS tanggalKadaluarsa,
                A.jumlah_adm   AS jumlahStokAdm,
                A.jumlah_fisik AS jumlahStokFisik,
                A.hp_item      AS hargaItem,
                A.sysdate_updt AS tanggalUbah,
                A.userid_updt  AS idUserUbah,
                B.nama_barang  AS namaBarang,
                C.nama_pabrik  AS namaPabrik,
                D.kode         AS satuan,
                E.name         AS namaUserUbah
            FROM db1.relasif_stokopname AS A
            LEFT JOIN db1.masterf_katalog AS B ON B.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS C ON C.id = A.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS D ON D.id = A.id_kemasan
            LEFT JOIN db1.user AS E ON E.id = A.userid_updt
            LEFT JOIN db1.transaksif_stokopname AS F ON F.kode = A.kode_reff
            WHERE  A.kode_reff = :kode
            ORDER BY nama_barang
        ";
        $params = [":kode" => $kode];
        $stokOpname->daftarStokOpname = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($stokOpname);
    }

    /**
     * @author Hendra Gunawan
     * @throws FailToInsertException
     * @throws DateTimeException
     * @throws Exception
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#add    the original method
     */
    public function actionSaveStokopname(): void
    {
        [   "idDepo" => $idDepo,
            "idKatalog" => $daftarIdKatalog,
            "idPabrik" => $daftarIdPabrik,
            "idKemasan" => $daftarIdKemasan,
            "jumlahStokAdm" => $daftarJumlahStokAdm,
            "hargaItem" => $daftarHargaItem,
            "tanggalKadaluarsa" => $daftarTanggalKadaluarsa,
            "noBatch" => $daftarNoBatch,
            "jumlahStokFisik" => $daftarJumlahStokFisik,
            "hna" => $daftarHna,
            "hja" => $daftarHja,
            "phja" => $daftarPhja,
            "phjapb" => $daftarPhjapb,
            "diskon" => $daftarDiskon,
            "ppn" => $daftarPpn,
            "refHarga" => $daftarRefHarga,
        ] = Yii::$app->request->post();
        $user = Yii::$app->userFatma;
        $idUser = $user->id;
        $connection = Yii::$app->dbFatma;

        // Cek Stok opname masih aktif atau tidak
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                ASO.kode          AS kodeRef,        -- in use
                SO.kode           AS kode,           -- in use
                SO.no_doc         AS noDokumen,      -- in use
                SO.tgl_doc        AS tanggalDokumen, -- in use
                SO.keterangan     AS keterangan,     -- in use
                ASO.tgl_adm       AS tanggalAdm,     -- in use
                ASO.tgl_mulai     AS tanggalMulai,   -- in use
                ASO.tgl_selesai   AS tanggalSelesai, -- in use
                ASO.status        AS status,
                SO.sts_opname     AS statusOpname1,  -- in use
                ASO.status_opname AS statusOpname2
            FROM db1.masterf_aktifasiso AS ASO
            LEFT JOIN db1.transaksif_stokopname AS SO ON SO.kode_reff = ASO.kode
            WHERE
                status_opname = 1
                AND id_depo = (
                    SELECT id
                    FROM db1.masterf_depo
                    WHERE kode = :kode
                )
            ORDER BY ASO.tgl_adm DESC
            LIMIT 1
        ";
        $params = [":kode" => $user->idDepo];
        $stokOpname = $connection->createCommand($sql, $params)->queryOne();

        $todayValSystem = Yii::$app->dateTime->todayVal("system");

        $this->setDataView("user", Yii::$app->userFatma);

        if ($stokOpname && strtotime($stokOpname->tanggalMulai) <= strtotime($todayValSystem) && strtotime($todayValSystem) <= strtotime($stokOpname->tanggalSelesai) && $stokOpname->statusOpname1 == 1) {
            $this->setDataView("opname", $stokOpname);
        } else {
            throw new \Exception("Anda berada diluar waktu Stok Opname.");
        } // end Cek Stok opname masih aktif atau tidak

        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $dateTime = Yii::$app->dateTime;
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $nowValSystem = $dateTime->nowVal("system");

        $fm2 = new FarmasiModel2;

        $dataOpnameUser = [];
        $daftarHarga = [];
        $i = 0;
        foreach ($daftarIdKatalog ?? [] as $key => $idKatalog) {
            if (!$idKatalog || $idKatalog == "-") continue;
            $tanggalKadaluarsa = $daftarTanggalKadaluarsa[$key] ?? "";
            $dataOpnameUser[$i] = [
                "id_user" => $idUser,
                "id_depo" => $idDepo,
                "kode_reff" => $stokOpname->kode,
                "id_katalog" => $idKatalog,
                "id_pabrik" => $daftarIdPabrik[$key],
                "id_kemasan" => $daftarIdKemasan[$key],
                "no_batch" => $daftarNoBatch[$key],
                "stok_adm" => $daftarJumlahStokAdm[$key],
                "stok_fisik" => $toSystemNumber($daftarJumlahStokFisik[$key]),
                "hp_item" => $daftarHargaItem[$key],
                "userid_in" => $idUser,
                "userid_updt" => $idUser,
                "sysdate_in" => $nowValSystem,
                "tgl_exp" => (strlen($tanggalKadaluarsa) == 10) ? $toSystemDate($tanggalKadaluarsa) : null,
            ];

            $daftarHarga[$i] = [
                "hp" => $dataOpnameUser[$i]["hp_item"],
                "hna" => $daftarHna[$key],
                "hja" => $daftarHja[$key],
                "phja" => $daftarPhja[$key],
                "phjapb" => $daftarPhjapb[$key],
                "harga" => $dataOpnameUser[$i]["hp_item"],
                "diskon" => $daftarDiskon[$key],
                "ppn" => $daftarPpn[$key],
                "reff_harga" => $daftarRefHarga[$key],
            ];
            $i++;
        }

        if ($dataOpnameUser) {
            $berhasilTambah = $fm2->saveBatch("relasif_opnameuser", $dataOpnameUser);
            if (!$berhasilTambah) throw new FailToInsertException("Opname User");

            foreach ($dataOpnameUser as $i => $oUser) {
                /* update ke tabel masterfkatalog_aktifdepo status_opname = 1 */
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.masterfkatalog_aktifdepo
                    SET status_opname = 1
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                ";
                $params = [":idDepo" => $oUser["id_depo"], ":idKatalog" => $oUser["id_katalog"]];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT kode_reff
                    FROM db1.relasif_stokopname
                    WHERE
                        kode_reff = :kodeRef
                        AND id_katalog = :idKatalog
                    LIMIT 1
                ";
                $params = [":kodeRef" => $oUser["kode_reff"], ":idKatalog" => $oUser["id_katalog"]];
                $kodeReferensi = $connection->createCommand($sql, $params)->queryScalar();

                if ($kodeReferensi) {
                    // jika sudah ada di relasif_stokopname
                    $exp = (strlen($oUser["tgl_exp"]) == 10)
                        ? ", tgl_exp = '" . $toSystemDate($oUser["tgl_exp"]) . "' "
                        : null;

                    // Merubah relasif_stokopname
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.relasif_stokopname
                        SET
                            jumlah_fisik = jumlah_fisik + :stokFisik,
                            userid_updt = :idUserUbah
                            $exp
                        WHERE
                            kode_reff = :kodeRef
                            AND id_katalog = :idKatalog
                    ";
                    $params = [
                        ":stokFisik" => $oUser["stok_fisik"],
                        ":idUserUbah" => $idUser,
                        ":kodeRef" => $oUser["kode_reff"],
                        ":idKatalog" => $oUser["id_katalog"]
                    ];
                    $connection->createCommand($sql, $params)->execute();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT jumlah_stokfisik
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                            AND status = 1
                        LIMIT 1
                    ";
                    $params = [":idDepo" => $oUser["id_depo"], ":idKatalog" => $oUser["id_katalog"]];
                    $jumlahStokFisik = $connection->createCommand($sql, $params)->queryScalar();

                    $dataKetersediaan = [
                        "id_depo" => $oUser["id_depo"],
                        "kode_reff" => $oUser["kode_reff"],
                        "no_doc" => $stokOpname->noDokumen,
                        "kode_stokopname" => $stokOpname->kodeRef,
                        "tgl_adm" => $stokOpname->tanggalAdm,
                        "tgl_transaksi" => $stokOpname->tanggalDokumen,
                        "bln_transaksi" => date("m", strtotime($stokOpname->tanggalDokumen)),
                        "thn_transaksi" => date("Y", strtotime($stokOpname->tanggalDokumen)),
                        "kode_transaksi" => "O",
                        "tipe_tersedia" => "stokopname",
                        "tgl_tersedia" => $nowValSystem,
                        "no_batch" => $oUser["no_batch"],
                        "id_katalog" => $oUser["id_katalog"],
                        "id_pabrik" => $oUser["id_pabrik"],
                        "id_kemasan" => $oUser["id_kemasan"],
                        "jumlah_masuk" => $oUser["stok_fisik"],
                        "jumlah_tersedia" => $jumlahStokFisik + $oUser["stok_fisik"],
                        "harga_netoapotik" => $daftarHarga[$i]["hna"],
                        "harga_perolehan" => $daftarHarga[$i]["hp"],
                        "phja" => $daftarHarga[$i]["phja"],
                        "phja_pb" => $daftarHarga[$i]["phjapb"],
                        "harga_jualapotik" => $daftarHarga[$i]["hja"],
                        "jumlah_item" => $oUser["stok_fisik"],
                        "harga_item" => $daftarHarga[$i]["harga"],
                        "harga_kemasan" => $daftarHarga[$i]["harga"],
                        "diskon_item" => $daftarHarga[$i]["diskon"],
                        "ppn" => $daftarHarga[$i]["ppn"],
                        "status" => 1,
                        "keterangan" => $stokOpname->keterangan,
                        "userid_last" => $oUser["userid_in"]
                    ];

                    if (strlen($oUser["tgl_exp"]) == 10) {
                        $dataKetersediaan["tgl_expired"] = $toSystemDate($oUser["tgl_exp"]);
                    }
                    $fm2->insertData("relasif_ketersediaan", $dataKetersediaan);

                    // merubah transaksif_stokkatalog
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.transaksif_stokkatalog
                        SET
                            jumlah_stokfisik = jumlah_stokfisik + :stokFisik,
                            userid_updt = :idUserUbah
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                    ";
                    $params = [
                        ":stokFisik" =>$oUser["stok_fisik"],
                        ":idUserUbah" => $idUser,
                        ":idDepo" => $oUser["id_depo"],
                        ":idKatalog" => $oUser["id_katalog"],
                    ];
                    $connection->createCommand($sql, $params)->execute();

                } else {
                    // jika belum ada di relasif_stokopname
                    // menginput relasif_stokopname
                    if (strlen($oUser["tgl_exp"]) == 10) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.relasif_stokopname
                            VALUES (
                                :kodeRef,
                                :idKatalog,
                                :idPabrik,
                                :idKemasan,
                                :tanggalKadaluarsa,
                                :jumlahAdm,
                                :jumlahFisik,
                                :hpItem,
                                :refHarga,
                                :idUserInput,
                                :tanggalInput,
                                :idUserUbah,
                                :tanggalUbah
                            )
                        ";
                        $params = [
                            ":kodeRef"           => $oUser["kode_reff"],
                            ":idKatalog"         => $oUser["id_katalog"],
                            ":idPabrik"          => $oUser["id_pabrik"],
                            ":idKemasan"         => $oUser["id_kemasan"],
                            ":tanggalKadaluarsa" => $toSystemDate($oUser["tgl_exp"]),
                            ":jumlahAdm"         => $oUser["stok_adm"],
                            ":jumlahFisik"       => $oUser["stok_fisik"],
                            ":hpItem"            => $oUser["hp_item"],
                            ":refHarga"          => $daftarHarga[$i]["reff_harga"],
                            ":idUserInput"       => $oUser["userid_in"],
                            ":tanggalInput"      => $oUser["sysdate_in"],
                            ":idUserUbah"        => $oUser["userid_updt"],
                            ":tanggalUbah"       => $oUser["sysdate_in"],
                        ];

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.relasif_stokopname
                            SET
                                kode_reff = :kodeRef,
                                id_katalog = :idKatalog,
                                id_pabrik = :idPabrik,
                                id_kemasan = :idKemasan,
                                jumlah_adm = :jumlahAdm,
                                jumlah_fisik = :jumlahFisik,
                                hp_item = :hpItem,
                                reff_harga = :refHarga,
                                userid_in = :idUserInput,
                                sysdate_in = :tanggalInput,
                                userid_updt = :idUserUbah,
                                sysdate_updt = :tanggalUbah
                        ";
                        $params = [
                            ":kodeRef"      => $oUser["kode_reff"],
                            ":idKatalog"    => $oUser["id_katalog"],
                            ":idPabrik"     => $oUser["id_pabrik"],
                            ":idKemasan"    => $oUser["id_kemasan"],
                            ":jumlahAdm"    => $oUser["stok_adm"],
                            ":jumlahFisik"  => $oUser["stok_fisik"],
                            ":hpItem"       => $oUser["hp_item"],
                            ":refHarga"     => $daftarHarga[$i]["reff_harga"],
                            ":idUserInput"  => $oUser["userid_in"],
                            ":tanggalInput" => $oUser["sysdate_in"],
                            ":idUserUbah"   => $oUser["userid_updt"],
                            ":tanggalUbah"  => $oUser["sysdate_in"]
                        ];
                    }
                    $connection->createCommand($sql, $params)->execute();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT jumlah_stokfisik
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                            AND status = 1
                        LIMIT 1
                    ";
                    $params = [":idDepo" => $oUser["id_depo"], ":idKatalog" => $oUser["id_katalog"]];
                    $jumlahStokFisik = $connection->createCommand($sql, $params)->queryScalar();

                    $dataKetersediaan = [
                        "id_depo"          => $oUser["id_depo"],
                        "kode_reff"        => $oUser["kode_reff"],
                        "no_doc"           => $stokOpname->noDokumen,
                        "kode_stokopname"  => $stokOpname->kodeRef,
                        "tgl_adm"          => $stokOpname->tanggalAdm,
                        "tgl_transaksi"    => $stokOpname->tanggalDokumen,
                        "bln_transaksi"    => date("m", strtotime($stokOpname->tanggalDokumen)),
                        "thn_transaksi"    => date("Y", strtotime($stokOpname->tanggalDokumen)),
                        "kode_transaksi"   => "O",
                        "tipe_tersedia"    => "stokopname",
                        "tgl_tersedia"     => $nowValSystem,
                        "no_batch"         => $oUser["no_batch"],
                        "id_katalog"       => $oUser["id_katalog"],
                        "id_pabrik"        => $oUser["id_pabrik"],
                        "id_kemasan"       => $oUser["id_kemasan"],
                        "jumlah_masuk"     => $oUser["stok_fisik"],
                        "jumlah_tersedia"  => $jumlahStokFisik + $oUser["stok_fisik"],
                        "harga_netoapotik" => $daftarHarga[$i]["hna"],
                        "harga_perolehan"  => $daftarHarga[$i]["hp"],
                        "phja"             => $daftarHarga[$i]["phja"],
                        "phja_pb"          => $daftarHarga[$i]["phjapb"],
                        "harga_jualapotik" => $daftarHarga[$i]["hja"],
                        "jumlah_item"      => $oUser["stok_fisik"],
                        "harga_item"       => $daftarHarga[$i]["harga"],
                        "harga_kemasan"    => $daftarHarga[$i]["harga"],
                        "diskon_item"      => $daftarHarga[$i]["diskon"],
                        "ppn"              => $daftarHarga[$i]["ppn"],
                        "status"           => 1,
                        "keterangan"       => $stokOpname->keterangan,
                        "userid_last"      => $oUser["userid_in"]
                    ];
                    if (strlen($oUser["tgl_exp"]) == 10) {
                        $dataKetersediaan["tgl_expired"] = $toSystemDate($oUser["tgl_exp"]);
                    }
                    $fm2->insertData("relasif_ketersediaan", $dataKetersediaan);

                    // update stok katalog. jika belum ada, ditambahkan ke depo, jika sudah diupdate dengan kalkulasi relasif_ketersediaan.
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT id_katalog
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                        LIMIT 1
                    ";
                    $params = [":idDepo" => $oUser["id_depo"], ":idKatalog" => $oUser["id_katalog"]];
                    $idKatalog = $connection->createCommand($sql, $params)->queryScalar();

                    if ($idKatalog) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.transaksif_stokkatalog
                            SET
                                jumlah_stokfisik = jumlah_stokfisik + :stokFisik,
                                userid_updt = :idUserUbah
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                        ";
                        $params = [
                            ":stokFisik" => $oUser["stok_fisik"],
                            ":idUserUbah" => $idUser,
                            ":idDepo" => $oUser["id_depo"],
                            ":idKatalog" => $oUser["id_katalog"],
                        ];
                        $connection->createCommand($sql, $params)->execute();

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.transaksif_stokkatalog
                            SET
                                id_depo = :idDepo,
                                id_katalog = :idKatalog,
                                jumlah_stokfisik = :stokFisik,
                                jumlah_stokadm = :stokFisik,
                                status = 1,
                                userid_in = :idUserInput,
                                sysdate_in = :tanggalInput,
                                userid_updt = :idUserInput
                        ";
                        $params = [
                            ":idDepo" => $oUser["id_depo"],
                            ":idKatalog" => $oUser["id_katalog"],
                            ":stokFisik" => $oUser["stok_fisik"],
                            ":idUserInput" => $oUser["userid_in"],
                            ":tanggalInput" => $oUser["sysdate_in"],
                        ];
                        $connection->createCommand($sql, $params)->execute();
                    }
                }
            }

            /* menghitung jumlah barang yang belum di Stokopname */
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT COUNT(id_katalog)
                FROM db1.masterfkatalog_aktifdepo
                WHERE
                    id_depo = :idDepo
                    AND status_opname = 0
            ";
            $params = [":idDepo" => $idDepo];
            $jumlahBelumSo = $connection->createCommand($sql, $params)->queryScalar();

            $messageBelumSo = "Penyimpanan berhasil. (ada " . $jumlahBelumSo . " obat yang belum diinput)!";

            $this->setDataView("success_message", $messageBelumSo);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#openopname    the original method
     */
    public function actionOpenOpname(): string
    {
        [   "kode" => $kode,
            "id_depo" => $idDepo,
            "no_doc" => $noDokumen,
            "kd_so" => $kodeStokOpname,
            "tgl_adm" => $tanggalAdm,
        ] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $todayValSystem = Yii::$app->dateTime->todayVal("system");

        $fm2 = new FarmasiModel2;
        $connection = Yii::$app->dbFatma;
        $transaction = $connection->beginTransaction();
        $idUser = Yii::$app->userFatma->id;

        // ditutup dulu
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_stokopname
            SET
                sts_opname = 1,
                userid_updt = :idUserUbah,
                ver_stokopname = 0,
                ver_tglstokopname = NULL,
                ver_usrstokopname = NUll
            WHERE
                kode = :kode
                AND id_depo = :idDepo
        ";
        $params = [":idUserUbah" => $idUser, ":kode" => $kode, ":idDepo" => $idDepo];
        $berhasilUbah = $connection->createCommand($sql, $params)->execute();

        // backup stok adm, fisik ke masterf_backupstok_so
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT INTO db1.masterf_backupstok_so (
                id_katalog,       -- 1
                id_depo,          -- 2
                jumlah_stokadm,   -- 3
                jumlah_stokfisik, -- 4
                status,           -- 5
                kode_reff,        -- 6
                tgl,              -- 7
                user_in,          -- 8
                jumlah_itemfisik  -- not exist in original source (error supressor)
            )
            SELECT
                id_katalog,        -- 1
                id_depo,           -- 2
                jumlah_stokadm,    -- 3
                jumlah_stokfisik,  -- 4
                1,                 -- 5
                :kode,             -- 6
                :tanggal,          -- 7
                :idUserInput,      -- 8
                ''                 -- not exist in original source (error supressor)
            FROM db1.transaksif_stokkatalog
            WHERE id_depo = :idDepo
        ";
        $params = [":kode" => $kode, ":tanggal" => $nowValSystem, ":idUserInput" => $idUser, ":idDepo" => $idDepo];
        $connection->createCommand($sql, $params)->execute();
        // end backup

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT DISTINCT id_katalog AS idKatalog -- all are in use, no view file.
            FROM db1.relasif_ketersediaan
            WHERE
                id_katalog not in (
                    SELECT DISTINCT id_katalog
                    FROM db1.transaksif_stokkatalog
                    WHERE id_depo = :idDepo
                )
                AND id_depo = :idDepo
        ";
        $params = [":idDepo" => $idDepo];
        $daftarKetersediaan = $connection->createCommand($sql, $params)->queryAll();

        if ($daftarKetersediaan) {
            $sql = array_map(
                fn(array $dt): array => [
                    "id_depo" => $idDepo,
                    "id_katalog" => $dt->idKatalog,
                    "id_kemasan" => 0,
                    "jumlah_stokmin" => 0,
                    "jumlah_stokmax" => 0,
                    "jumlah_stokfisik" => 0,
                    "jumlah_stokadm" => 0,
                    "jumlah_itemfisik" => 0,
                    "status" => 1,
                    "userid_in" => $idUser,
                    "sysdate_in" => $nowValSystem,
                    "userid_updt" => $idUser
                ],
                $daftarKetersediaan
            );
            $fm2->saveBatch("transaksif_stokkatalog", $sql);
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.transaksif_stokkatalog
            SET jumlah_stokfisik = 0
            WHERE id_depo = :idDepo
        ";
        $params = [":idDepo" => $idDepo];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT namaDepo
            FROM db1.masterf_depo
            WHERE id = :id
        ";
        $params = [":id" => $idDepo];
        $namaDepo = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                a.id_katalog      AS idKatalog,
                b.id_pabrik       AS idPabrik,
                b.id_kemasankecil AS idKemasanKecil,
                c.hna_item        AS hnaItem,
                c.hp_item         AS hpItem,
                c.phja            AS phja,
                c.phjapb          AS phjaPb,
                c.hja_item        AS hjaItem
            FROM db1.masterfkatalog_aktifdepo AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            LEFT JOIN db1.relasif_hargaperolehan AS c ON c.id_katalog = a.id_katalog
            WHERE
                a.id_depo = :idDepo
                AND c.sts_hja = 1
                AND c.sts_hjapb = 1
        ";
        $params = [":idDepo" => $idDepo];
        $daftarKatalogAktifDepo = $connection->createCommand($sql, $params)->queryAll();

        if ($daftarKatalogAktifDepo) {
            $sql = array_map(
                fn(array $dt): array => [
                    "id_depo" => $idDepo,
                    "kode_reff" => $kode,
                    "no_doc" => $noDokumen,
                    "kode_stokopname" => $kodeStokOpname,
                    "tgl_adm" => $tanggalAdm,
                    "tgl_transaksi" => $todayValSystem,
                    "bln_transaksi" => date("m"),
                    "thn_transaksi" => date("Y"),
                    "kode_transaksi" => "O",
                    "tipe_tersedia" => "stokopname",
                    "tgl_tersedia" => $nowValSystem,
                    "id_katalog" => $dt->idKatalog,
                    "id_pabrik" => $dt->idPabrik,
                    "id_kemasan" => $dt->idKemasanKecil,
                    "jumlah_masuk" => 0,
                    "jumlah_keluar" => 0,
                    "jumlah_tersedia" => 0,
                    "harga_netoapotik" => $dt->hnaItem,
                    "harga_perolehan" => $dt->hpItem,
                    "phja" => $dt->phja,
                    "phja_pb" => $dt->phjaPb,
                    "harga_jualapotik" => $dt->hjaItem,
                    "jumlah_item" => 0,
                    "status" => 1,
                    "keterangan" => "Me-0 kan stok tanggal $nowValSystem unit/depo $namaDepo",
                    "userid_last" => $idUser
                ],
                $daftarKatalogAktifDepo
            );
            $fm2->saveBatch("relasif_ketersediaan", $sql);
        }

        $transaction->commit();

        $result = $berhasilUbah ? 1 : 0;
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#edit    the original method
     */
    public function actionFormStokopnameData(): string
    {
        $kode = Yii::$app->request->post("kode") ?? throw new MissingPostParamException("kode");
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode           AS kode,
                id_depo        AS idDepo,         -- in use
                tgl_doc        AS tanggalDokumen,
                tgl_adm        AS tanggalAdm,
                ver_stokopname AS verStokOpname
            FROM db1.transaksif_stokopname
            WHERE kode = :kode
            LIMIT 1
        ";
        $params = [":kode" => $kode];
        $stokOpname = $connection->createCommand($sql, $params)->queryOne();
        if (!$stokOpname) throw new DataNotExistException($kode);

        $idDepo = $stokOpname->idDepo;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog      AS idKatalog,
                B.id_pabrik       AS idPabrik,
                B.id_kemasan      AS idKemasan,
                B.userid_updt     AS useridUpdate,
                B.sysdate_updt    AS sysdateUpdate,
                B.jumlah_adm      AS jumlahStokAdm,
                B.jumlah_fisik    AS akumulasiFisik,
                B.hp_item         AS hpItem,
                A.no_batch        AS noBatch,
                A.tgl_exp         AS tanggalKadaluarsa,
                SUM(A.stok_fisik) AS sumStokFisik,
                USR.name          AS namaUserUbah,
                C.nama_barang     AS namaBarang,
                D.nama_pabrik     AS namaPabrik,
                E.kode            AS satuan
            FROM db1.relasif_opnameuser AS A
            LEFT JOIN db1.relasif_stokopname AS B ON A.id_katalog = B.id_katalog
            LEFT JOIN db1.masterf_katalog AS C ON C.kode = A.id_katalog
            LEFT JOIN db1.masterf_pabrik AS D ON D.id = B.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS E ON E.id = B.id_kemasan
            LEFT JOIN db1.user AS USR ON USR.id = B.userid_updt
            WHERE
                A.kode_reff = :kodeRef
                AND B.kode_reff = :kodeRef
                AND B.userid_updt = :idUserUbah
            GROUP BY id_katalog, no_batch, tgl_exp
            ORDER BY C.nama_barang ASC
        ";
        $params = [":kodeRef" => $kode, ":idUserUbah" => $idUser];
        $daftarOpnameUser = $connection->createCommand($sql, $params)->queryAll();

        /* menghitung jumlah barang yang belum di Stokopname */
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(A.id_katalog)
            FROM db1.masterfkatalog_aktifdepo AS A
            LEFT JOIN db1.masterf_backupstok_so AS BSO ON A.id_katalog = BSO.id_katalog
            WHERE
                A.id_depo = :idDepo
                AND BSO.kode_reff = :kodeRef
                AND BSO.id_depo = :idDepo
                AND A.status_opname = 0
                AND BSO.jumlah_stokadm != 0
        ";
        $params = [":kodeRef" => $kode, ":idDepo" => $idDepo];
        $jumlahBelumSo = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(id_katalog)
            FROM db1.masterfkatalog_aktifdepo
            WHERE
                id_depo = :idDepo
                AND status_opname = 1
        ";
        $params = [":idDepo" => $idDepo];
        $jumlahSudahSo = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(A.id_katalog)
            FROM db1.masterfkatalog_aktifdepo AS A
            LEFT JOIN db1.masterf_backupstok_so AS BSO ON A.id_katalog = BSO.id_katalog
            WHERE
                A.id_depo = :idDepo
                AND BSO.kode_reff = :kodeRef
                AND BSO.id_depo = :idDepo
                AND BSO.jumlah_stokadm != 0
        ";
        $params = [":kodeRef" => $kode, ":idDepo" => $idDepo];
        $jumlahTotalSo = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode([
            "stokOpname" => $stokOpname,
            "daftarOpnameUser" => $daftarOpnameUser,
            "belumDiinput" => $jumlahBelumSo,
            "sudahDiinput" => $jumlahSudahSo,
            "total" => $jumlahTotalSo,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#katalogdepo    the original method
     */
    public function actionKatalogDepo(): string
    {
        assert($_POST["id"] && $_POST["kode"] && $_POST["id_depo"], new MissingPostParamException("id", "kode", "id_depo"));
        ["id" => $id, "kode" => $kode, "id_depo" => $idDepo] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                      AS kode,
                A.nama_barang               AS namaBarang,
                B.id                        AS idPabrik,
                B.nama_pabrik               AS namaPabrik,
                C.id                        AS idKemasanKecil,
                C.kode                      AS kodeKemasanKecil,
                IFNULL(D.jumlah_stokadm, 0) AS jumlahStokAdm,
                IFNULL(E.jumlah_fisik, 0)   AS akumulasiFisik,
                IFNULL(E.sysdate_updt, '')  AS sysdateUpdate,
                IFNULL(U.name, '')          AS operator,
                IFNULL(G.hna, 0)            AS hna,
                IFNULL(G.hp, 0)             AS hp,
                IFNULL(G.phja, 0)           AS phja,
                IFNULL(G.phjapb, 0)         AS phjaPb,
                IFNULL(G.hja, 0)            AS hja,
                IFNULL(G.diskon, 0)         AS diskon,
                IFNULL(G.ppn, 0)            AS ppn,
                IFNULL(G.kode_reff, '')     AS refHarga
            FROM db1.masterf_katalog AS A
            LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
            LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
            LEFT JOIN (
                SELECT
                    distinct id_katalog,
                    jumlah_stokfisik AS jumlah_stokadm,
                    jumlah_stokfisik
                FROM db1.masterf_backupstok_so
                WHERE
                    id_depo = :idDepo
                    AND kode_reff = :kodeRef
            ) AS D ON A.kode = D.id_katalog
            LEFT JOIN db1.relasif_stokopname AS E ON A.kode = E.id_katalog
            LEFT JOIN db1.user AS U ON E.userid_updt = U.id
            LEFT JOIN (
                SELECT
                    A.id_katalog     AS id_katalog,
                    A.kode_reff      AS kode_reff,
                    A.hna_item       AS hna,
                    A.hp_item        AS hp,
                    A.phja           AS phja,
                    A.phjapb         AS phjapb,
                    A.hja_item       AS hja,
                    A.diskon_item    AS diskon,
                    IFNULL(C.ppn, 0) AS ppn
                FROM db1.relasif_hargaperolehan AS A
                INNER JOIN (
                    SELECT
                        id_katalog  AS id_katalog,
                        MAX(tgl_hp) AS MaxDateTime
                    FROM db1.relasif_hargaperolehan
                    GROUP BY id_katalog
                ) AS B ON A.id_katalog = B.id_katalog
                LEFT JOIN db1.transaksif_penerimaan AS C ON A.kode_reff = C.kode
                WHERE A.tgl_hp = B.MaxDateTime
            ) AS G ON A.kode = G.id_katalog
            WHERE
                A.id = :id
                AND E.kode_reff = :kodeRef
            LIMIT 1
        ";
        $params = [":idDepo" => $idDepo, ":kodeRef" => $kode, ":id" => $id];
        $katalog = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($katalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#katalogfloor    the original method
     */
    public function actionKatalogFloor(): string
    {
        assert($_POST["id_katalog"], new MissingPostParamException("id_katalog"));
        ["id_katalog" => $idKatalog] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                KAT.id              AS id, -- 1 2 3 4
                KAT.kode            AS kode, -- 4
                KAT.nama_sediaan    AS namaSediaan, -- 1 2 3 4
                PBK.nama_pabrik     AS namaPabrik, -- 1 2 3 4
                KAT.id_kemasankecil AS idKemasanKecil, -- 1 2 3 4
                KEM.kode            AS kodeKemasanKecil, -- 1 2 3 4
                KAT.id_pabrik       AS idPabrik, -- 1 2 3 4
                HP.hp_item          AS hpItem -- 1 2 3 4
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.masterf_kemasan AS KEM ON KEM.id = id_kemasankecil
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            LEFT JOIN db1.relasif_hargaperolehan AS HP ON KAT.kode = HP.id_katalog
            WHERE
                KAT.kode = :kode
                AND HP.sts_hja = 1
            LIMIT 1
        ";
        $params = [":kode" => $idKatalog];
        $katalog = $connection->createCommand($sql, $params)->queryOne();

        if (!$katalog) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    KAT.id              AS id,
                    KAT.kode            AS kode,
                    KAT.nama_sediaan    AS namaSediaan,
                    KAT.formularium_rs  AS formulariumRs,
                    KAT.formularium_nas AS formulariumNas,
                    PBK.nama_pabrik     AS namaPabrik,
                    KAT.id_kemasankecil AS idKemasanKecil,
                    KEM.kode            AS kodeKemasanKecil,
                    KAT.id_pabrik       AS idPabrik,
                    HP.hp_item          AS hpItem
                FROM db1.masterf_katalog AS KAT
                LEFT JOIN db1.masterf_kemasan AS KEM ON KEM.id = id_kemasankecil
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
                LEFT JOIN db1.relasif_hargaperolehan AS HP ON KAT.kode = HP.id_katalog
                WHERE KAT.kode = :kode
                LIMIT 1
            ";
            $params = [":kode" => $idKatalog];
            $katalog = $connection->createCommand($sql, $params)->queryOne();
        }
        return json_encode($katalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#floorstock    the original method
     */
    public function actionSaveFloorStock(): void
    {
        [   "idFloorStock" => $idFloorStock,
            "no_doc" => $noDokumen,
            "kode" => $kode,
            "id_barang" => $daftarIdBarang,
            "ver_usrfloor" => $verUserFloor,
            "ver_idusr" => $verIdUser,
            "idedit" => $daftarIdEdit,
            "jml_persediaan" => $daftarJumlahPersediaan,
            "tgl_exp" => $daftarTanggalKadaluarsa,
            "id_unit" => $idUnit,
            "harga_satuan" => $daftarHargaSatuan,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;

        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $kodeReferensi = ($kode && $kode != "KODE TRN") ? $kode : "FS00" . date("YmdHis") . rand(000, 999);

        foreach ($daftarIdBarang as $key => $idBarang) {
            if (!$idBarang) continue;

            if ($idFloorStock) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT kode_reff
                    FROM db1.relasif_floorstock
                    WHERE
                        id = :id
                        AND status = 0
                    LIMIT 1
                ";
                $params = [":id" => $idFloorStock];
                $kodeRef = $connection->createCommand($sql, $params)->queryScalar();

                if (!$kodeRef) throw new DataNotExistException($idFloorStock);

                // edit/update floor stock
                if ($verUserFloor && $verUserFloor != "------") {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.relasif_floorstock
                        SET
                            status = 1,
                            verifikasi = :verifikasi,
                            tgl_verifikasi = :tanggalVerifikasi
                        WHERE kode_reff = :kodeRef
                    ";
                    $params = [":verifikasi" => $verIdUser, ":kodeRef" => $kodeRef, ":tanggalVerifikasi" => $nowValSystem];
                    $connection->createCommand($sql, $params)->execute();
                }

                if ($daftarIdEdit[$key] == 1) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.relasif_floorstock
                        SET
                            jumlah_item = :jumlahItem,
                            tgl_expired = :tanggalKadaluarsa,
                            no_doc = :noDokumen,
                            id_depo = :idDepo,
                            harga_item = :hargaItem
                        WHERE
                            id_katalog = :idKatalog
                            AND kode_reff = :kodeRef
                    ";
                    $params = [
                        ":jumlahItem" => $daftarJumlahPersediaan[$key],
                        ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                        ":noDokumen" => $noDokumen,
                        ":idDepo" => $idUnit,
                        ":hargaItem" => $daftarHargaSatuan[$key],
                        ":idKatalog" => $daftarIdBarang[$key],
                        ":kodeRef" => $kode,
                    ];
                    $connection->createCommand($sql, $params)->execute();

                } else {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.relasif_floorstock
                        SET
                            status = 1, verifikasi = :verifikasi, tgl_verifikasi = :tanggalVerifikasi,
                            kode_reff = :kodeRef,
                            no_doc = :noDokumen,
                            id_katalog = :idKatalog,
                            jumlah_item = :jumlahItem,
                            harga_item = :hargaItem,
                            id_depo = :idDepo,
                            tgl_expired = :tanggalKadaluarsa
                    ";
                    $params = [
                        ":kodeRef" => $kode,
                        ":noDokumen" => $noDokumen,
                        ":idKatalog" => $daftarIdBarang[$key],
                        ":jumlahItem" => $daftarJumlahPersediaan[$key],
                        ":hargaItem" => $daftarHargaSatuan[$key],
                        ":idDepo" => $idUnit,
                        ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ];
                    if ($verUserFloor == "------") {
                        $sql = str_replace("status = 1, verifikasi = :verifikasi, tgl_verifikasi = :tanggalVerifikasi,", "", $sql);
                    } else {
                        $params = [...$params, ":verifikasi" => $verIdUser, ":tanggalVerifikasi" => $nowValSystem];
                    }
                    $connection->createCommand($sql, $params)->execute();
                }
            } else {
                // insert/tambah floor stock
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_floorstock
                    SET
                        status = 1, verifikasi = :verifikasi, tgl_verifikasi = :tanggalVerifikasi,
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        jumlah_item = :jumlahItem,
                        harga_item = :hargaItem,
                        id_depo = :idDepo,
                        tgl_expired = :tanggalKadaluarsa
                ";
                $params = [
                    ":kodeRef" => $kodeReferensi,
                    ":noDokumen" => $noDokumen,
                    ":idKatalog" => $daftarIdBarang[$key],
                    ":jumlahItem" => $daftarJumlahPersediaan[$key],
                    ":hargaItem" => $daftarHargaSatuan[$key],
                    ":idDepo" => $idUnit,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                ];
                if ($verUserFloor == "------") {
                    $sql = str_replace("status = 1, verifikasi = :verifikasi, tgl_verifikasi = :tanggalVerifikasi,", "", $sql);
                } else {
                    $params = [...$params, ":verifikasi" => $verIdUser, ":tanggalVerifikasi" => $nowValSystem];
                }
                $connection->createCommand($sql, $params)->execute();
            }
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#floorstock    the original method
     */
    public function actionFormFloorStockData(): string
    {
        $idFloorStock = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode_reff
            FROM db1.relasif_floorstock
            WHERE
                id = :id
                AND status = 0
            LIMIT 1
        ";
        $params = [":id" => $idFloorStock];
        $kodeReferensi = $connection->createCommand($sql, $params)->queryScalar();
        if (!$kodeReferensi) throw new DataNotExistException($idFloorStock);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kode_reff      AS kodeTransaksi,
                a.no_doc         AS noDokumen,
                a.id_depo        AS idUnit,
                a.system_in      AS tanggalDokumen,
                a.status         AS verifikasiFloorStock,
                a.verifikasi     AS verUserFloorStock,
                a.tgl_verifikasi AS verTanggalFloorStock,
                ''               AS idFloorStock,
                NULL             AS daftarFloorStock
            FROM db1.relasif_floorstock AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            WHERE kode_reff = :kodeRef
            LIMIT 1
        ";
        $params = [":kodeRef" => $kodeReferensi];
        $floorStock = $connection->createCommand($sql, $params)->queryOne();
        $floorStock->idFloorStock = $idFloorStock;

        // "idEdit" is TRUELY CORRECT, look at previous revision (at php statement, not sql statement)
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                b.nama_barang AS namaBarang,
                a.no_doc      AS noDokumen,
                e.nama_pabrik AS namaPabrik,
                d.kode        AS namaSatuan,
                a.id_katalog  AS idBarang,
                a.jumlah_item AS jumlahPersediaan,
                a.tgl_expired AS tanggalKadaluarsa,
                a.harga_item  AS hargaSatuan,
                1             AS idEdit
            FROM db1.relasif_floorstock AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = b.id_pabrik
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = b.id_kemasankecil
            WHERE a.kode_reff = :kodeRef
        ";
        $params = [":kodeRef" => $kodeReferensi];
        $floorStock->daftarFloorStock = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT id
            FROM db1.masterf_depo
            WHERE kode = :kode
        ";
        $params = [":kode" => Yii::$app->userFatma->idDepo];
        $floorStock->idUnit = $floorStock->idUnit ?: $connection->createCommand($sql, $params)->queryScalar();

        return json_encode($floorStock);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#printfloorstock    the original method
     */
    public function actionPrintFloorStock(): string
    {
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT no_doc
            FROM db1.relasif_floorstock
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $id];
        $noDokumen = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc         AS noDokumen,
                a.system_in      AS systemInput,
                us.namaDepo      AS namaDepo,
                kt.kode          AS kode,
                kt.nama_barang   AS namaBarang,         -- in use
                e.nama_pabrik    AS namaPabrik,         -- in use
                g.jenis_obat     AS jenisObat,          -- in use
                a.tgl_expired    AS tanggalKadaluarsa,
                a.jumlah_item    AS jumlahItem,         -- in use
                a.harga_item     AS hargaItem,
                d.kode           AS namaKemasan,
                a.verifikasi     AS verifikasi,
                a.harga_item     AS hpItem,             -- in use
                ur.name          AS namaUserVerifikasi,
                a.tgl_verifikasi AS tanggalVerifikasi
            FROM db1.relasif_floorstock AS a
            LEFT JOIN db1.masterf_depo AS us ON us.id = a.id_depo
            LEFT JOIN db1.masterf_katalog AS kt ON kt.kode = a.id_katalog
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = kt.id_pabrik
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = kt.id_jenisbarang
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = kt.id_kemasankecil
            LEFT JOIN db1.user AS ur ON ur.id = a.verifikasi
            WHERE no_doc = :noDokumen
            ORDER BY jenis_obat, kt.kode ASC
        ";
        $params = [":noDokumen" => $noDokumen];
        $daftarFloorStock = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $total = 0;

        $jumlahData = count($daftarFloorStock);
        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 42;
        $maksHurufBarang = 41;
        $maksHurufPabrik = 22;
        $jenisObatSaatIni = "";

        foreach ($daftarFloorStock as $i => $floorStock) {
            $floorStock->jenisObat ??= "Lain - Lain";

            $jumlahBarisBarang = ceil(strlen($floorStock->namaBarang) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($floorStock->namaPabrik) / $maksHurufPabrik);
            $butuhBarisSaatIni = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;
            $bBerikutnya = $b + $butuhBarisSaatIni;

            if ($jenisObatSaatIni != $floorStock->jenisObat) {
                $jenisObatSaatIni = $floorStock->jenisObat;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "jenis_obat" => $floorStock->jenisObat,
                    "subtotal" => 0,
                ];

                if ($bBerikutnya > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul .".".  $noData++ .".",
            ];

            $daftarHalaman[$hJudul][$bJudul]["subtotal"] += $floorStock->jumlahItem * $floorStock->hpItem;
            $total += $floorStock->jumlahItem * $floorStock->hpItem;

            if ($i + 1 == $jumlahData) break;
            $dataBerikutnya = $daftarFloorStock[$i + 1];
            $bedaJudul = $floorStock->jenisObat != $dataBerikutnya->jenisObat;

            $jumlahBarisBarang = ceil(strlen($dataBerikutnya->namaBarang) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($dataBerikutnya->namaPabrik) / $maksHurufPabrik);
            $butuhBarisBerikutnya = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

            if ($bedaJudul and $bBerikutnya > $barisPerHalaman) {
                $h++;
                $b = 0;
            } elseif ($bBerikutnya + $butuhBarisBerikutnya > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b = $bBerikutnya;
            }
        }

        $view = new PrintFloorStock(
            listFloorWidgetId: Yii::$app->actionToId([Pair::class, "actionTableFloorStock"]),
            daftarHalaman:     $daftarHalaman,
            floorStock:        $daftarFloorStock[0],
            daftarFloorStock:  $daftarFloorStock,
            jumlahHalaman:     count($daftarHalaman),
            total:             $total,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#printexpired    the original method
     */
    public function actionPrintExpired(): string
    {
        $kodeReferensi = Yii::$app->request->post("kodeRef") ?? throw new MissingPostParamException("kodeRef");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc          AS noDokumen,
                a.tgl_expired     AS tanggalKadaluarsa,
                a.jumlah_tersedia AS jumlahTersedia,     -- in use
                us.namaDepo       AS namaUnit,
                e.nama_pabrik     AS namaPabrik,         -- in use
                g.jenis_obat      AS jenisObat,          -- in use
                d.kode            AS namaKemasan,
                kt.kode           AS kode,
                kt.nama_barang    AS namaBarang,         -- in use
                a.hp_item         AS hpItem,             -- in use
                ur.name           AS namaUserUbah,
                a.tgl_tersedia    AS tanggalTersedia,
                a.status          AS status,
                a.tgl_doc         AS tanggalDokumen,
                a.no_batch        AS noBatch
            FROM db1.relasif_expired AS a
            INNER JOIN db1.masterf_katalog AS kt ON kt.kode = a.id_katalog
            LEFT JOIN db1.masterf_depo AS us ON us.id = a.depo_expired
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = kt.id_pabrik
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = kt.id_jenisbarang
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = kt.id_kemasankecil
            LEFT JOIN db1.user AS ur ON ur.id = a.userid_last
            WHERE
                a.kode_reff = :kodeReferensi
                AND a.depo_expired != 999
        ";
        $params = [":kodeReferensi" => $kodeReferensi];
        $daftarKadaluarsa = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $total = 0;

        $jumlahData = count($daftarKadaluarsa);
        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 45;
        $maksHurufBarang = 36;
        $maksHurufPabrik = 16;
        $jenisObatSaatIni = "";

        foreach ($daftarKadaluarsa as $i => $kadaluarsa) {
            $kadaluarsa->jenisObat ??= "Lain - Lain";

            $jumlahBarisBarang = ceil(strlen($kadaluarsa->namaBarang) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($kadaluarsa->namaPabrik) / $maksHurufPabrik);
            $butuhBarisSaatIni = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;
            $bBerikutnya = $b + $butuhBarisSaatIni;

            if ($jenisObatSaatIni != $kadaluarsa->jenisObat) {
                $jenisObatSaatIni = $kadaluarsa->jenisObat;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "jenis_obat" => $kadaluarsa->jenisObat,
                    "subtotal" => 0,
                ];

                if ($bBerikutnya > $barisPerHalaman) {
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

            $daftarHalaman[$hJudul][$bJudul]["subtotal"] += $kadaluarsa->jumlahTersedia * $kadaluarsa->hpItem;
            $total += $kadaluarsa->jumlahTersedia * $kadaluarsa->hpItem;

            if ($i + 1 == $jumlahData) break;
            $dataBerikutnya = $daftarKadaluarsa[$i + 1];
            $bedaJudul = $kadaluarsa->jenisObat != $dataBerikutnya->jenisObat;

            $jumlahBarisBarang = ceil(strlen($dataBerikutnya->namaBarang) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($dataBerikutnya->namaPabrik) / $maksHurufPabrik);
            $butuhBarisBerikutnya = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

            if ($bedaJudul and $bBerikutnya > $barisPerHalaman) {
                $h++;
                $b = 0;
            } elseif ($bBerikutnya + $butuhBarisBerikutnya > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b = $bBerikutnya;
            }
        }

        $view = new PrintExpired(
            listExpiredWidgetId: Yii::$app->actionToId([Pair::class, "actionTableStokKadaluarsa"]),
            daftarHalaman:       $daftarHalaman,
            kadaluarsa:          $daftarKadaluarsa[0],
            daftarKadaluarsa:    $daftarKadaluarsa,
            jumlahHalaman:       count($daftarHalaman),
            total:               $total,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#printrusak    the original method
     */
    public function actionPrintRusak(): string
    {
        $kodeReferensi = Yii::$app->request->post("kodeRef") ?? throw new MissingPostParamException("kodeRef");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc          AS noDokumen,
                a.tgl_rusak       AS tanggalRusak,
                a.jumlah_tersedia AS jumlahTersedia,  -- in use
                us.namaDepo       AS namaUnit,
                e.nama_pabrik     AS namaPabrik,      -- in use
                g.jenis_obat      AS jenisObat,       -- in use
                d.kode            AS namaKemasan,
                kt.kode           AS kode,
                kt.nama_barang    AS namaBarang,      -- in use
                a.hp_item         AS hpItem,          -- in use
                ur.name           AS namaUserUbah,
                a.tgl_tersedia    AS tanggalTersedia,
                a.status          AS status,
                a.tgl_doc         AS tanggalDokumen,
                a.sysdate_in      AS sysdateInput,
                a.no_batch        AS noBatch
            FROM db1.relasif_rusak AS a
            INNER JOIN db1.masterf_katalog AS kt ON kt.kode = a.id_katalog
            LEFT JOIN db1.masterf_depo AS us ON us.id = a.depo_rusak
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = kt.id_pabrik
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = kt.id_jenisbarang
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = kt.id_kemasankecil
            LEFT JOIN db1.user AS ur ON ur.id = a.userid_last
            WHERE
                a.kode_reff = :kodeReferensi
                AND a.depo_rusak != 999
        ";
        $params = [":kodeReferensi" => $kodeReferensi];
        $daftarRusak = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $total = 0;

        $jumlahData = count($daftarRusak);
        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 45;
        $maksHurufBarang = 36;
        $maksHurufPabrik = 16;
        $jenisObatSaatIni = "";

        foreach ($daftarRusak as $i => $rusak) {
            $rusak->jenisObat ??= "Lain - Lain";

            $jumlahBarisBarang = ceil(strlen($rusak->namaBarang) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($rusak->namaPabrik) / $maksHurufPabrik);
            $butuhBarisSaatIni = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;
            $bBerikutnya = $b + $butuhBarisSaatIni;

            if ($jenisObatSaatIni != $rusak->jenisObat) {
                $jenisObatSaatIni = $rusak->jenisObat;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "jenis_obat" => $rusak->jenisObat,
                    "subtotal" => 0,
                ];

                if ($bBerikutnya > $barisPerHalaman) {
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

            $daftarHalaman[$hJudul][$bJudul]["subtotal"] += $rusak->jumlahTersedia * $rusak->hpItem;
            $total += $rusak->jumlahTersedia * $rusak->hpItem;

            if ($i + 1 == $jumlahData) break;
            $dataBerikutnya = $daftarRusak[$i + 1];
            $bedaJudul = $rusak->jenisObat != $dataBerikutnya->jenisObat;

            $jumlahBarisBarang = ceil(strlen($dataBerikutnya->namaBarang) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($dataBerikutnya->namaPabrik) / $maksHurufPabrik);
            $butuhBarisBerikutnya = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

            if ($bedaJudul and $bBerikutnya > $barisPerHalaman) {
                $h++;
                $b = 0;
            } elseif ($bBerikutnya + $butuhBarisBerikutnya > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b = $bBerikutnya;
            }
        }

        $view = new PrintRusak(
            daftarHalaman: $daftarHalaman,
            rusak:         $daftarRusak[0],
            daftarRusak:   $daftarRusak,
            jumlahHalaman: count($daftarHalaman),
            total:         $total,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#printrusakrekap    the original method
     */
    public function actionPrintRusakRekap(): string
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc          AS noDokumen,      -- in use
                a.tgl_rusak       AS tanggalRusak,
                a.jumlah_tersedia AS jumlahTersedia, -- in use
                us.namaDepo       AS namaUnit,
                e.nama_pabrik     AS namaPabrik,
                g.jenis_obat      AS jenisObat,      -- in use
                d.kode            AS namaKemasan,
                kt.kode           AS kode,
                kt.nama_barang    AS namaBarang,
                a.hp_item         AS hpItem,         -- in use
                ur.name           AS namaUserUbah,
                a.tgl_tersedia    AS tanggalTersedia,
                a.status          AS status,
                a.tgl_doc         AS tanggalDokumen,
                a.sysdate_in      AS sysdateInput,
                a.no_batch        AS noBatch
            FROM db1.relasif_rusak AS a
            INNER JOIN db1.masterf_katalog AS kt ON kt.kode = a.id_katalog
            LEFT JOIN db1.masterf_depo AS us ON us.id = a.depo_rusak
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = kt.id_pabrik
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = kt.id_jenisbarang
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = kt.id_kemasankecil
            LEFT JOIN db1.user AS ur ON ur.id = a.userid_last
            WHERE a.depo_rusak != 999
            ORDER BY a.kode_reff
        ";
        $daftarRusak = $connection->createCommand($sql)->queryAll();

        $daftarHalaman = [];
        $grandTotal = 0;

        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul1 = 0;
        $bJudul1 = 0;
        $hJudul2 = 0;
        $bJudul2 = 0;
        $noJudul1 = 1;
        $noJudul2 = 1;
        $noData = 1;
        $barisPerHalaman = 45;
        $noDokumenSaatIni = "";
        $jenisObatSaatIni = "";

        foreach ($daftarRusak as $i => $rusak) {
            $rusak->jenisObat ??= "Lain - Lain";

            if ($noDokumenSaatIni != $rusak->noDokumen) {
                $noDokumenSaatIni = $rusak->noDokumen;
                $jenisObatSaatIni = "";
                $hJudul1 = $h;
                $bJudul1 = $b;
                $noJudul2 = 1;

                $daftarHalaman[$hJudul1][$bJudul1] = [
                    "no" => $noJudul1++ .".",
                    "no_doc" => $rusak->noDokumen,
                    "total" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if ($jenisObatSaatIni != $rusak->jenisObat) {
                $jenisObatSaatIni = $rusak->jenisObat;
                $hJudul2 = $h;
                $bJudul2 = $b;
                $noData = 1;

                $daftarHalaman[$hJudul2][$bJudul2] = [
                    "no" => $noJudul1 .".". $noJudul2++ .".",
                    "jenis_obat" => $rusak->jenisObat,
                    "subtotal" => 0,
                ];

                if ($b >= $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $nilaiTersedia = $rusak->jumlahTersedia * $rusak->hpItem;

            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "no" => $noJudul1 .".". $noJudul2 .".". $noData++ .".",
            ];

            $daftarHalaman[$hJudul2][$bJudul2]["subtotal"] += $nilaiTersedia;
            $daftarHalaman[$hJudul1][$bJudul1]["total"] += $nilaiTersedia;
            $grandTotal += $nilaiTersedia;

            if ($b >= $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new PrintRusakRekap(
            daftarHalaman: $daftarHalaman,
            daftarRusak:   $daftarRusak,
            jumlahHalaman: count($daftarHalaman),
            grandTotal:    $grandTotal,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#printkoreksi    the original method
     */
    public function actionPrintKoreksi(): string
    {
        $kodeRef = Yii::$app->request->post("kodeRef") ?? throw new MissingPostParamException("kodeRef");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.no_doc          AS noDokumen,
                a.sysdate_last    AS sysdateLast,
                a.tgl_expired     AS tanggalKadaluarsa,
                a.keterangan      AS keterangan,
                a.jumlah_masuk    AS jumlahMasuk,         -- in use
                a.harga_perolehan AS hargaPerolehan,      -- in use
                us.nama_unit      AS namaUnit,
                e.nama_pabrik     AS namaPabrik,          -- in use
                g.jenis_obat      AS jenisObat,           -- in use
                d.kode            AS namaKemasan,
                kt.kode           AS kode,
                kt.nama_barang    AS namaBarang,          -- in use
                ur.name           AS namaUserUbah,
                a.sysdate_last    AS sysdateLast,
                a.status          AS status,
                a.tgl_transaksi   AS tanggalTransaksi
            FROM db1.relasif_ketersediaan AS a
            LEFT JOIN db1.masterf_katalog AS kt ON kt.kode = a.id_katalog
            LEFT JOIN db1.masterf_unitstore AS us ON us.id = a.id_depo
            LEFT JOIN db1.masterf_pabrik AS e ON e.id = kt.id_pabrik
            LEFT JOIN db1.masterf_jenisobat AS g ON g.id = kt.id_jenisbarang
            LEFT JOIN db1.masterf_kemasan AS d ON d.id = kt.id_kemasankecil
            LEFT JOIN db1.user AS ur ON ur.id = a.userid_last
            WHERE
                a.kode_transaksi = 'K'
                AND a.tipe_tersedia = 'koreksiopname'
                AND a.kode_reff = :kodeReferensi
        ";
        $params = [":kodeReferensi" => $kodeRef];
        $daftarTersedia = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];
        $total = 0;

        $jumlahData = count($daftarTersedia);
        $h = 0; // index halaman
        $b = 0; // index baris
        $hJudul = 0;
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $barisPerHalaman = 45;
        $maksHurufBarang = 36;
        $maksHurufPabrik = 16;
        $jenisObatSaatIni = "";

        foreach ($daftarTersedia as $i => $tersedia) {
            $jenisObat = $tersedia->jenisObat ?? "Lain - Lain";

            $jumlahBarisBarang = ceil(strlen($tersedia->namaBarang) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($tersedia->namaPabrik) / $maksHurufPabrik);
            $butuhBarisSaatIni = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;
            $bBerikutnya = $b + $butuhBarisSaatIni;

            if ($jenisObatSaatIni != $jenisObat) {
                $jenisObatSaatIni = $jenisObat;
                $hJudul = $h;
                $bJudul = $b;
                $noData = 1;

                $daftarHalaman[$hJudul][$bJudul] = [
                    "no" => $noJudul++ .".",
                    "jenis_obat" => $jenisObat,
                    "subtotal" => 0
                ];

                if ($bBerikutnya > $barisPerHalaman) {
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

            $daftarHalaman[$hJudul][$bJudul]["subtotal"] += $tersedia->jumlahMasuk * $tersedia->hargaPerolehan;
            $total += $tersedia->jumlahMasuk * $tersedia->hargaPerolehan;

            if ($i + 1 == $jumlahData) break;
            $dataBerikutnya = $daftarTersedia[$i + 1];
            $bedaJudul = $tersedia->jenisObat != $dataBerikutnya->jenisObat;

            $jumlahBarisBarang = ceil(strlen($dataBerikutnya->namaBarang) / $maksHurufBarang);
            $jumlahBarisPabrik = ceil(strlen($dataBerikutnya->namaPabrik) / $maksHurufPabrik);
            $butuhBarisBerikutnya = ($jumlahBarisBarang > $jumlahBarisPabrik) ? $jumlahBarisBarang : $jumlahBarisPabrik;

            if ($bedaJudul and $bBerikutnya > $barisPerHalaman) {
                $h++;
                $b = 0;
            } elseif ($bBerikutnya + $butuhBarisBerikutnya > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b = $bBerikutnya;
            }
        }

        $view = new PrintKoreksi(
            listKoreksiWidgetId: Yii::$app->actionToId([Pair::class, "actionTableKoreksi"]),
            daftarHalaman:       $daftarHalaman,
            tersedia:            $daftarTersedia[0],
            daftarKetersediaan:  $daftarTersedia,
            jumlahHalaman:       count($daftarHalaman),
            total:               $total,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#viewopnameuser    the original method
     */
    public function actionViewOpnameUser(): string
    {
        ["kodeRef" => $kodeRef, "idDepo" => $idDepo, "idUser" => $idUser] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id                 AS id,
                A.id_user            AS idUser,
                A.id_depo            AS idDepo,
                A.kode_reff          AS kodeRef,
                A.no_urut            AS noUrut,
                A.id_katalog         AS idKatalog,
                A.id_pabrik          AS idPabrik,
                A.id_kemasan         AS idKemasan,
                A.no_batch           AS noBatch,
                A.tgl_exp            AS tanggalKadaluarsa,
                A.stok_adm           AS stokAdm,            -- in use
                A.stok_fisik         AS stokFisik,          -- in use
                A.hp_item            AS hpItem,             -- in use
                A.userid_in          AS useridInput,
                A.sysdate_in         AS sysdateInput,
                A.userid_updt        AS useridUpdate,
                A.sysdate_updt       AS sysdateUpdate,
                B.kode               AS kode,
                B.nama_barang        AS namaBarang,
                B.id_kelompokbarang  AS kodeKelompok,       -- in use
                C.nama_pabrik        AS namaPabrik,
                D.kode               AS satuan,
                F.kelompok_barang    AS kelompokBarang,     -- in use
                G.namaDepo           AS namaDepo,           -- in use
                H.tgl_adm            AS tanggalAdm,         -- in use
                I.name               AS namaUser,           -- in use
                BSO.jumlah_stokfisik AS cadanganStokAdm     -- in use
            FROM db1.relasif_opnameuser AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
            LEFT JOIN db1.masterf_kelompokbarang AS F ON B.id_kelompokbarang = F.id
            LEFT JOIN db1.masterf_depo AS G ON A.id_depo = G.id
            LEFT JOIN db1.user AS I ON A.id_user = I.id
            LEFT JOIN db1.transaksif_stokopname AS H ON A.kode_reff = H.kode
            LEFT JOIN (
                SELECT DISTINCT
                    id_katalog,
                    jumlah_stokfisik
                FROM db1.masterf_backupstok_so
                WHERE
                    kode_reff = :kodeRef
                    AND id_depo = :idDepo
            ) AS BSO ON A.id_katalog = BSO.id_katalog
            WHERE
                A.id_user = :idUser
                AND A.id_depo = :idDepo
                AND A.kode_reff = :kodeRef
            ORDER BY B.id_kelompokbarang, B.kode, B.nama_barang
        ";
        $params = [":kodeRef" => $kodeRef, ":idDepo" => $idDepo, ":idUser" => $idUser];
        $daftarOpnameUser = $connection->createCommand($sql, $params)->queryAll();

        $daftarBaris = [];
        $totalNilaiAdm = 0;
        $totalNilaiFisik = 0;

        $b = 0; // index baris
        $bJudul = 0;
        $noJudul = 1;
        $noData = 1;
        $kelompokSaatIni = "";

        foreach ($daftarOpnameUser as $i => $opnameUser) {
            if ($kelompokSaatIni != $opnameUser->kodeKelompok) {
                $kelompokSaatIni = $opnameUser->kodeKelompok;
                $bJudul = $b;

                $daftarBaris[$bJudul] = [
                    "no" => $noJudul++ .".",
                    "nama_kelompok" => $opnameUser->kelompokBarang,
                    "nilai_adm" => 0,
                    "nilai_fisik" => 0,
                ];
            }

            $jumlahSelisih = $opnameUser->stokFisik - $opnameUser->cadanganStokAdm;
            $nilaiAdm = $opnameUser->stokAdm * $opnameUser->hpItem;
            $nilaiFisik = $opnameUser->stokFisik * $opnameUser->hpItem;

            $daftarBaris[$b] = [
                "i" => $i,
                "no" => $noJudul .".". $noData++ .".",
                "nilai_fisik" => $nilaiFisik,
                "jumlah_selisih" => $jumlahSelisih,
                "nilai_selisih" => $jumlahSelisih * $opnameUser->hpItem,
            ];

            $daftarBaris[$bJudul]["nilai_adm"] += $nilaiAdm;
            $daftarBaris[$bJudul]["nilai_fisik"] += $nilaiFisik;

            $totalNilaiAdm += $nilaiAdm;
            $totalNilaiFisik += $nilaiFisik;
        }

        $view = new OpnameUser(
            tanggal:          $daftarOpnameUser[0]->tanggalAdm,
            namaDepo:         $daftarOpnameUser[0]->namaDepo,
            namaUser:         $daftarOpnameUser[0]->namaUser,
            daftarBaris:      $daftarBaris,
            daftarOpnameUser: $daftarOpnameUser,
            totalNilaiAdm:    $totalNilaiAdm,
            totalNilaiFisik:  $totalNilaiFisik,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#expired    the original method
     */
    public function actionSaveStokKadaluarsa(): void
    {
        [   "id" => $id,
            "no_doc" => $noDokumen,
            "kode" => $kode,
            "id_unit" => $idUnit,
            "id_barang" => $daftarIdBarang,
            "ver_usrfloor" => $verUserFloor,
            "ver_idusr" => $verIdUser,
            "jml_persediaan" => $daftarJumlahPersediaan,
            "tgl_doc" => $tanggalDokumen,
            "tgl_exp" => $daftarTanggalKadaluarsa,
            "no_batch" => $daftarNoBatch,
            "harga_satuan" => $daftarHargaSatuan,
            "keterangan" => $keterangan,
        ] = Yii::$app->request->post();
        $dateTime = Yii::$app->dateTime;
        $systemDatetime = $dateTime->transformFunc("systemDatetime");
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $nowValSystem = $dateTime->nowVal("system");

        $connection = Yii::$app->dbFatma;

        $kodeReferensi = ($kode && $kode != "KODE TRN") ? $kode : "EX00" . date("YmdHis");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT tgl_adm
            FROM db1.transaksif_stokopname
            WHERE
                id_depo = :idDepo
                AND sts_aktif = 1
                AND sts_opname = 1
            LIMIT 1
        ";
        $params = [":idDepo" => $idUnit];
        $tanggalAdm = $connection->createCommand($sql, $params)->queryScalar();

        $date = $tanggalAdm ? $systemDatetime($tanggalAdm, "-5 min") : $nowValSystem;

        foreach ($daftarIdBarang as $key => $idBarang) {
            if (!$idBarang) continue;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode
                FROM db1.masterf_katalog
                WHERE id = :id
            ";
            $params = [":id" => $idBarang];
            $kodeKatalog = $connection->createCommand($sql, $params)->queryScalar();

            if ($id) {
                if ($verUserFloor && $verUserFloor != "------") {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.relasif_ketersediaan
                        SET
                            status = 1,
                            tgl_tersedia = :tanggalTersedia,
                            userid_last = :useridLast
                        WHERE kode_reff = :kodeRef
                    ";
                    $params = [":tanggalTersedia" => $date, ":useridLast" => $verIdUser, ":kodeRef" => $id];
                    $connection->createCommand($sql, $params)->execute();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.relasif_expired
                        SET
                            status = 1,
                            tgl_tersedia = :tanggalTersedia,
                            userid_last = :useridLast
                            WHERE kode_reff = :kodeRef
                    ";
                    $params = [":tanggalTersedia" => $date, ":useridLast" => $verIdUser, ":kodeRef" => $id];
                    $connection->createCommand($sql, $params)->execute();

                    if ($verUserFloor != "------") {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            SELECT TRUE
                            FROM db1.transaksif_stokkatalog
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                                AND status = 1
                            LIMIT 1
                        ";
                        $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                        $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                        $cekStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

                        if ($cekStokKatalog) {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                UPDATE db1.transaksif_stokkatalog
                                SET
                                    jumlah_stokfisik = jumlah_stokfisik - :jumlahPersediaan,
                                    jumlah_stokadm = jumlah_stokadm - :jumlahPersediaan
                                WHERE
                                    id_depo = :idDepo
                                    AND id_katalog = :idKatalog
                                    AND status = 1
                            ";
                            $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                            $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                            $connection->createCommand($sql, $params)->execute();

                        } else {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                INSERT INTO db1.transaksif_stokkatalog
                                SET
                                    id_depo = :idDepo,
                                    id_katalog = :idKatalog,
                                    status = 1,
                                    jumlah_stokfisik = -:jumlahPersediaan,
                                    jumlah_stokadm = -:jumlahPersediaan,
                                    jumlah_itemfisik = -:jumlahPersediaan
                            ";
                            $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                            $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog, ":jumlahPersediaan" => $daftarJumlahPersediaan[$key]];
                            $connection->createCommand($sql, $params)->execute();
                        }
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            SELECT TRUE
                            FROM db1.transaksif_stokkatalog
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                                AND status = 1
                            LIMIT 1
                        ";
                        $params = [":idDepo" => 320, ":idKatalog" => $kodeKatalog];
                        $cekStokKatalogGudang = $connection->createCommand($sql, $params)->queryScalar();

                        if ($cekStokKatalogGudang) {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                UPDATE db1.transaksif_stokkatalog
                                SET
                                    jumlah_stokfisik = jumlah_stokfisik + :jumlahPersediaan,
                                    jumlah_stokadm = jumlah_stokadm + :jumlahPersediaan
                                WHERE
                                    id_depo = :idDepo
                                    AND id_katalog = :idKatalog
                                    AND status = 1
                            ";
                            $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idDepo" => 320, ":idKatalog" => $kodeKatalog];
                            $connection->createCommand($sql, $params)->execute();

                        } else {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                INSERT INTO db1.transaksif_stokkatalog
                                SET
                                    id_depo = :idDepo,
                                    id_katalog = :idKatalog,
                                    status = 1,
                                    jumlah_stokfisik = :jumlahPersediaan,
                                    jumlah_stokadm = :jumlahPersediaan,
                                    jumlah_itemfisik = :jumlahPersediaan
                            ";
                            $params = [":idDepo" => 320, ":idKatalog" => $kodeKatalog, ":jumlahPersediaan" => $daftarJumlahPersediaan[$key]];
                            $connection->createCommand($sql, $params)->execute();
                        }
                    }
                }

                $tglTrans = $toSystemDate($tanggalDokumen);
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokadm
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                        AND status = 1
                ";
                $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                $jumlahStokAdm = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.relasif_ketersediaan
                    SET
                        jumlah_keluar = :jumlahKeluar,
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_expired = :tanggalKadaluarsa,
                        no_doc = :noDokumen,
                        no_batch = :noBatch,
                        tgl_transaksi = :tanggalTransaksi
                    WHERE
                        id_katalog = :idKatalog
                        AND kode_reff = :kodeRef
                        AND id_depo != :idDepo
                ";
                $params = [
                    ":jumlahKeluar" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokAdm,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":noDokumen" => $noDokumen,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":tanggalTransaksi" => $tglTrans,
                    ":idKatalog" => $kodeKatalog,
                    ":kodeRef" => $id,
                    ":idDepo" => 320,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokadm
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                        AND status = 1
                    LIMIT 1
                ";
                $params = [":idDepo" => 320, ":idKatalog" => $kodeKatalog];
                $jumlahStokAdm = (int) $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.relasif_ketersediaan
                    SET
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_expired = :tanggalKadaluarsa,
                        no_doc = :noDokumen,
                        no_batch = :noBatch,
                        tgl_transaksi = :tanggalTransaksi
                    WHERE
                        id_katalog = :idKatalog
                        AND kode_reff = :kodeRef
                        AND id_depo = :idDepo
                ";
                $params = [
                    ":jumlahTersedia" => $jumlahStokAdm,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":noDokumen" => $noDokumen,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":tanggalTransaksi" => $tglTrans,
                    ":idKatalog" => $kodeKatalog,
                    ":kodeRef" => $id,
                    ":idDepo" => 320,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.relasif_expired
                    SET
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_expired = :tanggalKadaluarsa,
                        no_doc = :noDokumen,
                        no_batch = :noBatch,
                        hp_item = :hpItem,
                        depo_expired = :depoKadaluarsa,
                        tgl_doc = :tanggalDokumen
                    WHERE
                        id_katalog = :idKatalog
                        AND kode_reff = :kodeRef
                ";
                $params = [
                    ":jumlahTersedia" => $daftarJumlahPersediaan[$key],
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":noDokumen" => $noDokumen,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":hpItem" => $daftarHargaSatuan[$key],
                    ":depoKadaluarsa" => $idUnit,
                    ":tanggalDokumen" => $tglTrans,
                    ":idKatalog" => $kodeKatalog,
                    ":kodeRef" => $id,
                ];
                $connection->createCommand($sql, $params)->execute();

            } else {
                if ($verUserFloor && $verUserFloor != "------") {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                            AND status = 1
                        LIMIT 1
                    ";
                    $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                    $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                    $cekStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

                    if ($cekStokKatalog) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.transaksif_stokkatalog
                            SET
                                jumlah_stokfisik = jumlah_stokfisik - :jumlahPersediaan,
                                jumlah_stokadm = jumlah_stokadm - :jumlahPersediaan
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                                AND status = 1
                        ";
                        $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                        $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                        $connection->createCommand($sql, $params)->execute();

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.transaksif_stokkatalog
                            SET
                                id_depo = :idDepo,
                                id_katalog = :idKatalog,
                                status = 1,
                                jumlah_stokfisik = -:jumlahPersediaan,
                                jumlah_stokadm = -:jumlahPersediaan,
                                jumlah_itemfisik = -:jumlahPersediaan
                        ";
                        $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                        $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog, ":jumlahPersediaan" => $daftarJumlahPersediaan[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                            AND status = 1
                        LIMIT 1
                    ";
                    $params = [":idDepo" => 320, ":idKatalog" => $kodeKatalog];
                    $cekStokKatalogGudang = $connection->createCommand($sql, $params)->queryScalar();

                    if ($cekStokKatalogGudang) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.transaksif_stokkatalog
                            SET
                                jumlah_stokfisik = jumlah_stokfisik + :jumlahPersediaan,
                                jumlah_stokadm = jumlah_stokadm + :jumlahPersediaan
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                                AND status = 1
                        ";
                        $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idDepo" => 320, ":idKatalog" => $kodeKatalog];
                        $connection->createCommand($sql, $params)->execute();

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.transaksif_stokkatalog
                            SET
                                id_depo = :idDepo,
                                id_katalog = :idKatalog,
                                status = 1,
                                jumlah_stokfisik = :jumlahPersediaan,
                                jumlah_stokadm = :jumlahPersediaan,
                                jumlah_itemfisik = :jumlahPersediaan
                        ";
                        $params = [":idDepo" => 320, ":idKatalog" => $kodeKatalog, ":jumlahPersediaan" => $daftarJumlahPersediaan[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }
                }
                // insert/tambah barang expired
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokadm
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                        AND status = 1
                ";
                $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                $jumlahStokAdm = $connection->createCommand($sql, $params)->queryScalar();

                $tglTrans = $toSystemDate($tanggalDokumen);

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        jumlah_keluar = :jumlahKeluar,
                        jumlah_tersedia = :jumlahTersedia,
                        tipe_tersedia = 'expired',
                        tgl_transaksi = :tanggalTransaksi,
                        tgl_expired = :tanggalKadaluarsa,
                        id_depo = :idDepo,
                        no_batch = :noBatch,
                        keterangan = :keterangan,
                        tgl_tersedia = :tanggalTersedia,
                        userid_last = :idUserUbah,
                        status = :status
                ";
                $params = [
                    ":kodeRef" => $kodeReferensi,
                    ":noDokumen" => $noDokumen,
                    ":idKatalog" => $kodeKatalog,
                    ":jumlahKeluar" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokAdm,
                    ":tanggalTransaksi" => $tglTrans,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":idDepo" => $idUnit,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":keterangan" => "Expired: $keterangan",
                    ":tanggalTersedia" => $date,
                    ":idUserUbah" => $verIdUser,
                    ":status" => $verUserFloor == "------" ? 0 : 1,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokadm
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                        AND status = 1
                    LIMIT 1
                ";
                $params = [":idDepo" => 320, ":idKatalog" => $kodeKatalog];
                $jumlahStokAdm = (int) $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        jumlah_masuk = :jumlahMasuk,
                        jumlah_tersedia = :jumlahTersedia,
                        tipe_tersedia = 'expired',
                        tgl_transaksi = :tanggalTransaksi,
                        tgl_expired = :tanggalKadaluarsa,
                        id_depo = :idDepo,
                        no_batch = :noBatch,
                        keterangan = :keterangan,
                        tgl_tersedia = :tanggalTersedia,
                        userid_last = :idUserUbah,
                        status = :status
                ";
                $params = [
                    ":kodeRef" => $kodeReferensi,
                    ":noDokumen" => $noDokumen,
                    ":idKatalog" => $kodeKatalog,
                    ":jumlahMasuk" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokAdm,
                    ":tanggalTransaksi" => $tglTrans,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":idDepo" => 320,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":keterangan" => "Expired: $keterangan",
                    ":tanggalTersedia" => $date,
                    ":idUserUbah" => $verIdUser,
                    ":status" => $verUserFloor == "------" ? 0 : 1,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_expired
                    SET
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_expired = :tanggalKadaluarsa,
                        tgl_doc = :tanggalDokumen,
                        depo_expired = :depoKadaluarsa,
                        no_batch = :noBatch,
                        hp_item = :hpItem,
                        keterangan = :keterangan,
                        tgl_tersedia = :tanggalTersedia,
                        userid_last = :idUserUbah,
                        status = :status
                ";
                $params = [
                    ":kodeRef" => $kodeReferensi,
                    ":noDokumen" => $noDokumen,
                    ":idKatalog" => $kodeKatalog,
                    ":jumlahTersedia" => $daftarJumlahPersediaan[$key],
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":tanggalDokumen" => $nowValSystem,
                    ":depoKadaluarsa" => $idUnit,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":hpItem" => $daftarHargaSatuan[$key],
                    ":keterangan" => "Expired: $keterangan",
                    ":tanggalTersedia" => $date,
                    ":idUserUbah" => $verIdUser,
                    ":status" => $verUserFloor == "------" ? 0 : 1,
                ];
                $connection->createCommand($sql, $params)->execute();
            }
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#expired    the original method
     */
    public function actionFormStokKadaluarsaData(): string
    {
        $kodeRef = Yii::$app->request->post("kodeRef") ?? throw new MissingPostParamException("kodeRef");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kode_reff     AS kodeTransaksi,
                a.no_doc        AS noDokumen,
                a.id_depo       AS idUnit,
                a.status        AS verifikasiFloorStock,
                a.userid_last   AS verUserFloorStock,
                a.sysdate_last  AS verTanggalFloorStock,
                a.tgl_transaksi AS tanggalDokumen,
                a.keterangan    AS keterangan,
                NULL            AS daftarKadaluarsa
            FROM db1.relasif_ketersediaan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            WHERE
                kode_reff = :kodeRef
                AND tipe_tersedia = 'expired'
                AND status = 0
            LIMIT 1
        ";
        $params = [":kodeRef" => $kodeRef];
        $ketersediaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$ketersediaan) throw new DataNotExistException($kodeRef);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                b.id              AS idBarang,
                b.nama_barang     AS namaBarang,
                a.jumlah_tersedia AS jumlahPersediaan,
                a.tgl_expired     AS tanggalKadaluarsa,
                a.no_batch        AS noBatch,
                a.hp_item         AS hargaSatuan
            FROM db1.relasif_expired AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            WHERE
                a.kode_reff = :kodeRef
                AND depo_expired != :depoKadaluarsa
        ";
        $params = [":kodeRef" => $kodeRef, ":depoKadaluarsa" => 320];
        $ketersediaan->daftarKadaluarsa = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($ketersediaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#koreksiopname    the original method
     */
    public function actionSaveKoreksiOpname(): void
    {
        [   "id" => $id,
            "no_doc" => $noDokumen,
            "kode" => $kode,
            "id_barang" => $daftarIdBarang,
            "id_unit" => $idUnit,
            "jml_persediaan" => $daftarJumlahPersediaan,
            "tgl_doc" => $tanggalDokumen,
            "tgl_exp" => $daftarTanggalKadaluarsa,
            "ver_usrfloor" => $verUserFloor,
            "keterangan" => $daftarKeterangan,
        ] = Yii::$app->request->post();
        $idUser = Yii::$app->userFatma->id;
        $connection = Yii::$app->dbFatma;

        $kodeReferensi = ($kode && $kode != "KODE TRN") ? $kode : "KO00" . date("YmdHis");

        $dateTime = Yii::$app->dateTime;
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $nowValSystem = $dateTime->nowVal("system");

        foreach ($daftarIdBarang as $key => $idBarang) {
            if (!$idBarang) continue;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode
                FROM db1.masterf_katalog
                WHERE id = :id
            ";
            $params = [":id" => $idBarang];
            $kodeKatalog = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    id               AS id,
                    id_depo          AS idDepo,
                    kode_reff        AS kodeRef,
                    no_doc           AS noDokumen,
                    ppn              AS ppn,
                    id_reff          AS idRef,
                    kode_stokopname  AS kodeStokopname,
                    tgl_adm          AS tanggalAdm,
                    tgl_transaksi    AS tanggalTransaksi,
                    bln_transaksi    AS bulanTransaksi,
                    thn_transaksi    AS tahunTransaksi,
                    kode_transaksi   AS kodeTransaksi,
                    kode_store       AS kodeStore,
                    tipe_tersedia    AS tipeTersedia,
                    tgl_tersedia     AS tanggalTersedia,
                    no_batch         AS noBatch,
                    tgl_expired      AS tanggalKadaluarsa,
                    id_katalog       AS idKatalog,
                    id_pabrik        AS idPabrik,
                    id_kemasan       AS idKemasan,
                    isi_kemasan      AS isiKemasan,
                    jumlah_sebelum   AS jumlahSebelum,
                    jumlah_masuk     AS jumlahMasuk,
                    jumlah_keluar    AS jumlahKeluar,
                    jumlah_tersedia  AS jumlahTersedia,
                    harga_netoapotik AS hargaNetoApotik,
                    harga_perolehan  AS hargaPerolehan,
                    phja             AS phja,
                    phja_pb          AS phjaPb,
                    harga_jualapotik AS hargaJualApotik,
                    jumlah_item      AS jumlahItem,
                    jumlah_kemasan   AS jumlahKemasan,
                    jumlah_spk       AS jumlahSpk,
                    jumlah_do        AS jumlahDo,
                    jumlah_terima    AS jumlahTerima,
                    harga_item       AS hargaItem,
                    harga_kemasan    AS hargaKemasan,
                    diskon_item      AS diskonItem,
                    diskonjual_item  AS diskonJualItem,
                    diskon_harga     AS diskonHarga,
                    status           AS status,
                    keterangan       AS keterangan,
                    userid_last      AS useridLast,
                    sysdate_last     AS sysdateLast
                FROM db1.relasif_ketersediaan
                WHERE
                    id_katalog = :idKatalog
                    AND kode_reff = :kodeRef
                LIMIT 1
            ";
            $params = [":idKatalog" => $kodeKatalog, ":kodeRef" => $kodeReferensi];
            $ketersediaan = $connection->createCommand($sql, $params)->queryOne();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    id_depo          AS idDepo,
                    id_katalog       AS idKatalog,
                    id_kemasan       AS idKemasan,
                    jumlah_stokmin   AS jumlahStokMin,
                    jumlah_stokmax   AS jumlahStokMaks,
                    jumlah_stokfisik AS jumlahStokFisik,
                    jumlah_stokadm   AS jumlahStokAdm,
                    jumlah_itemfisik AS jumlahItemFisik,
                    status           AS status,
                    userid_in        AS useridInput,
                    sysdate_in       AS sysdateInput,
                    userid_updt      AS useridUpdate,
                    sysdate_updt     AS sysdateUpdate,
                    keterangan       AS keterangan
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
            ";
            $params = [":idKatalog" => $kodeKatalog, ":idDepo" => $idUnit];
            $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

            $tglTrans = $toSystemDate($tanggalDokumen);
            // veriv di ceklis
            if ($verUserFloor && $verUserFloor != "------") {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.relasif_ketersediaan
                    SET
                        userid_last = :idUserUbah,
                        status = 1
                        WHERE kode_reff = :kodeRef
                ";
                $params = [":idUserUbah" => $idUser, ":kodeRef" => $id];
                $connection->createCommand($sql, $params)->execute();

                if ($daftarStokKatalog) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.transaksif_stokkatalog
                        SET
                            jumlah_stokfisik = jumlah_stokfisik + :jumlahPersediaan,
                            jumlah_stokadm = jumlah_stokadm + :jumlahPersediaan
                        WHERE
                            id_katalog = :idKatalog
                            AND id_depo = :idDepo
                    ";
                    $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idKatalog" => $kodeKatalog, ":idDepo" => $idUnit];
                    $connection->createCommand($sql, $params)->execute();

                } else {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.transaksif_stokkatalog
                        SET
                            jumlah_stokfisik = :jumlahPersediaan,
                            jumlah_stokadm = :jumlahPersediaan,
                            jumlah_itemfisik = :jumlahPersediaan,
                            id_katalog = :idKatalog,
                            id_depo = :idDepo
                    ";
                    $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idKatalog" => $kodeKatalog, ":idDepo" => $idUnit];
                    $connection->createCommand($sql, $params)->execute();
                }
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_stokfisik
                FROM db1.transaksif_stokkatalog
                WHERE
                    id_katalog = :idKatalog
                    AND id_depo = :idDepo
                    AND status = 1
                LIMIT 1
            ";
            $params = [":idKatalog" => $kodeKatalog, ":idDepo" => $idUnit];
            $jumlahStokFisikBefore = $connection->createCommand($sql, $params)->queryScalar();

            if (!$ketersediaan) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        jumlah_masuk = :jumlahMasuk,
                        jumlah_tersedia = :jumlahTersedia,
                        tipe_tersedia = 'koreksiopname',
                        kode_transaksi = 'K',
                        tgl_tersedia = :tanggalTersedia,
                        tgl_expired = :tanggalKadaluarsa,
                        tgl_transaksi = :tanggalTransaksi,
                        id_depo = :idDepo,
                        keterangan = :keterangan,
                        userid_last = :idUserUbah,
                        status = :status
                ";
                $params = [
                    ":kodeRef" => $kodeReferensi,
                    ":noDokumen" => $noDokumen,
                    ":idKatalog" => $kodeKatalog,
                    ":jumlahMasuk" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokFisikBefore,
                    ":tanggalTersedia" => $nowValSystem,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":tanggalTransaksi" => $tglTrans,
                    ":idDepo" => $idUnit,
                    ":keterangan" => $daftarKeterangan[$key],
                    ":idUserUbah" => $idUser,
                    ":status" => $verUserFloor == "------" ? 0 : 1
                ];
                $connection->createCommand($sql, $params)->execute();

            } else {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.relasif_ketersediaan
                    SET
                        jumlah_masuk = :jumlahMasuk,
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_expired = :tanggalKadaluarsa,
                        keterangan = :keterangan,
                        userid_last = :idUserUbah,
                        status = :status
                    WHERE
                        kode_reff = :kodeRef
                        AND id_katalog = :idKatalog
                ";
                $params = [
                    ":jumlahMasuk" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokFisikBefore,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":keterangan" => $daftarKeterangan[$key],
                    ":kodeRef" => $kodeReferensi,
                    ":idKatalog" => $kodeKatalog,
                    ":idUserUbah" => $idUser,
                    ":status" => $verUserFloor == "------" ? 0 : 1
                ];
                $connection->createCommand($sql, $params)->execute();
            }
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#koreksiopname    the original method
     */
    public function actionFormKoreksiOpnameData(): string
    {
        $kodeRef = Yii::$app->request->post("kodeRef") ?? throw new MissingPostParamException("kodeRef");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.id_depo       AS idUnit,
                a.kode_reff     AS kodeTransaksi,
                a.no_doc        AS noDokumen,
                a.status        AS verifikasiFloorStock,
                a.tgl_transaksi AS tanggalDokumen,
                a.userid_last   AS verUserFloorStock,
                a.sysdate_last  AS verTanggalFloorStock,
                NULL            AS daftarKetersediaan
            FROM db1.relasif_ketersediaan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            WHERE
                kode_reff = :kodeRef
                AND tipe_tersedia = 'koreksiopname'
            LIMIT 1
        ";
        $params = [":kodeRef" => $kodeRef];
        $ketersediaan = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                b.nama_barang  AS namaBarang,
                b.id           AS idBarang,
                a.jumlah_masuk AS jumlahPersediaan,
                a.tgl_expired  AS tanggalKadaluarsa,
                a.keterangan   AS keterangan,
                h.hp_item      AS hargaSatuan
            FROM db1.relasif_ketersediaan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            LEFT JOIN db1.relasif_hargaperolehan AS h ON h.id_katalog = a.id_katalog
            WHERE
                a.kode_reff = ''
                AND tipe_tersedia = 'koreksiopname'
                AND h.sts_hja = 1
                AND h.sts_hjapb = 1
            LIMIT 100
        ";
        $params = [":kodeRef" => $kodeRef];
        $ketersediaan->daftarKetersediaan = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($ketersediaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#rusak    the original method
     */
    public function actionSaveStokRusak(): void
    {
        [   "id" => $id,
            "kode" => $kode,
            "id_unit" => $idUnit,
            "ver_usrfloor" => $verUserFloor,
            "ver_idusr" => $verIdUser,
            "no_doc" => $noDokumen,
            "id_barang" => $daftarIdBarang,
            "jml_persediaan" => $daftarJumlahPersediaan,
            "tgl_doc" => $tanggalDokumen,
            "tgl_exp" => $daftarTanggalKadaluarsa,
            "no_batch" => $daftarNoBatch,
            "harga_satuan" => $daftarHargaSatuan,
            "keterangan" => $keterangan,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $dateTime = Yii::$app->dateTime;
        $systemDatetime = $dateTime->transformFunc("systemDatetime");
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $nowValSystem = $dateTime->nowVal("system");

        $kodeReferensi = ($kode && $kode != "KODE TRN") ? $kode : "RS00" . date("YmdHis");

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT tgl_adm
            FROM db1.transaksif_stokopname
            WHERE
                id_depo = :idDepo
                AND sts_aktif = 1
                AND sts_opname = 1
            LIMIT 1
        ";
        $params = [":idDepo" => $idUnit];
        $tanggalAdm = $connection->createCommand($sql, $params)->queryScalar();

        $date = $tanggalAdm ? $systemDatetime($tanggalAdm, "-5 min") : $nowValSystem;

        foreach ($daftarIdBarang as $key => $idBarang) {
            if (!$idBarang) continue;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode
                FROM db1.masterf_katalog
                WHERE id = :id
            ";
            $params = [":id" => $idBarang];
            $kodeKatalog = $connection->createCommand($sql, $params)->queryScalar();

            $verif = ($verUserFloor && $verUserFloor != "------") ? 1 : 0;

            if ($id) {
                // edit/update barang rusak
                if ($verUserFloor && $verUserFloor != "------") {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.relasif_ketersediaan
                        SET
                            status = 1,
                            tgl_tersedia = :tanggalTersedia,
                            userid_last = :useridLast
                        WHERE kode_reff = :kodeRef
                    ";
                    $params = [":tanggalTersedia" => $date, ":useridLast" => $verIdUser, ":kodeRef" => $id];
                    $connection->createCommand($sql, $params)->execute();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.relasif_rusak
                        SET
                            status = 1,
                            tgl_tersedia = :tanggalTersedia,
                            userid_last = :useridLast
                        WHERE kode_reff = :kodeRef
                    ";
                    $params = [":tanggalTersedia" => $date, ":useridLast" => $verIdUser, ":kodeRef" => $id];
                    $connection->createCommand($sql, $params)->execute();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                            AND status = 1
                        LIMIT 1
                    ";
                    $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                    $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                    $cekStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

                    if ($cekStokKatalog) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.transaksif_stokkatalog
                            SET
                                jumlah_stokfisik = jumlah_stokfisik - :jumlahPersediaan,
                                jumlah_stokadm = jumlah_stokadm - :jumlahPersediaan
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                                AND status = 1
                        ";
                        $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                        $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                        $connection->createCommand($sql, $params)->execute();

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.transaksif_stokkatalog
                            SET
                                id_depo = :idDepo,
                                id_katalog = :idKatalog,
                                status = 1,
                                jumlah_stokfisik = :minusJumlahPersediaan,
                                jumlah_stokadm = :minusJumlahPersediaan,
                                jumlah_itemfisik = :minusJumlahPersediaan
                        ";
                        $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                        $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog, ":minusJumlahPersediaan" => -$daftarJumlahPersediaan[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                            AND status = 1
                        LIMIT 1
                    ";
                    $params = [":idDepo" => 321, ":idKatalog" => $kodeKatalog];
                    $cekStokKatalogGudang = $connection->createCommand($sql, $params)->queryScalar();

                    if ($cekStokKatalogGudang) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.transaksif_stokkatalog
                            SET
                                jumlah_stokfisik = jumlah_stokfisik + :jumlahPersediaan,
                                jumlah_stokadm = jumlah_stokadm + :jumlahPersediaan
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                                AND status = 1
                        ";
                        $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idDepo" => 321, ":idKatalog" => $kodeKatalog];
                        $connection->createCommand($sql, $params)->execute();

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.transaksif_stokkatalog
                            SET
                                id_depo = :idDepo,
                                id_katalog = :idKatalog,
                                status = 1,
                                jumlah_stokfisik = :jumlahPersediaan,
                                jumlah_stokadm = :jumlahPersediaan,
                                jumlah_itemfisik = :jumlahPersediaan
                        ";
                        $params = [":idDepo" => 321, ":idKatalog" => $kodeKatalog, ":jumlahPersediaan" => $daftarJumlahPersediaan[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokadm
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                        AND status = 1
                    LIMIT 1
                ";
                $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                $jumlahStokAdm = $connection->createCommand($sql, $params)->queryScalar();

                $tglTrans = $toSystemDate($tanggalDokumen);

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.relasif_ketersediaan
                    SET
                        jumlah_keluar = :jumlahKeluar,
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_expired = :tanggalKadaluarsa,
                        no_doc = :noDokumen,
                        no_batch = :noBatch,
                        tgl_transaksi = :tanggalTransaksi
                    WHERE
                        id_katalog = :idKatalog
                        AND kode_reff = :kodeRef
                        AND id_depo != :idDepo
                ";
                $params = [
                    ":jumlahKeluar" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokAdm,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":noDokumen" => $noDokumen,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":tanggalTransaksi" => $tglTrans,
                    ":idKatalog" => $kodeKatalog,
                    ":kodeRef" => $id,
                    ":idDepo" => 321,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokadm
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                        AND status = 1
                    LIMIT 1
                ";
                $params = [":idDepo" => 321, ":idKatalog" => $kodeKatalog];
                $jumlahStokAdm = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.relasif_ketersediaan
                    SET
                        jumlah_keluar = :jumlahKeluar,
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_expired = :tanggalKadaluarsa,
                        no_doc = :noDokumen,
                        no_batch = :noBatch,
                        tgl_transaksi = :tanggalTransaksi
                    WHERE
                        id_katalog = :idKatalog
                        AND kode_reff = :kodeRef
                        AND id_depo = :idDepo
                ";
                $params = [
                    ":jumlahKeluar" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokAdm,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":noDokumen" => $noDokumen,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":tanggalTransaksi" => $tglTrans,
                    ":idKatalog" => $kodeKatalog,
                    ":kodeRef" => $id,
                    ":idDepo" => 321,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.relasif_rusak
                    SET
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_rusak = :tanggalRusak,
                        no_doc = :noDokumen,
                        no_batch = :noBatch,
                        hp_item = :hpItem,
                        depo_rusak = :depoRusak,
                        tgl_doc = :tanggalDokumen
                    WHERE
                        id_katalog = :idKatalog
                        AND kode_reff = :kodeRef
                ";
                $params = [
                    ":jumlahTersedia" => $daftarJumlahPersediaan[$key],
                    ":tanggalRusak" => $daftarTanggalKadaluarsa[$key],
                    ":noDokumen" => $noDokumen,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":hpItem" => $daftarHargaSatuan[$key],
                    ":depoRusak" => $idUnit,
                    ":tanggalDokumen" => $tglTrans,
                    ":idKatalog" => $kodeKatalog,
                    ":kodeRef" => $id,
                ];
                $connection->createCommand($sql, $params)->execute();

            } else {
                if ($verUserFloor && $verUserFloor != "------") {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                            AND status = 1
                        LIMIT 1
                    ";
                    $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                    $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                    $cekStokKatalog = $connection->createCommand($sql, $params)->queryScalar();

                    if ($cekStokKatalog) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.transaksif_stokkatalog
                            SET
                                jumlah_stokfisik = jumlah_stokfisik - :jumlahPersediaan,
                                jumlah_stokadm = jumlah_stokadm - :jumlahPersediaan
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                                AND status = 1
                        ";
                        $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                        $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                        $connection->createCommand($sql, $params)->execute();

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.transaksif_stokkatalog
                            SET
                                id_depo = :idDepo,
                                id_katalog = :idKatalog,
                                status = 1,
                                jumlah_stokfisik = :minusJumlahPersediaan,
                                jumlah_stokadm = :minusJumlahPersediaan,
                                jumlah_itemfisik = :minusJumlahPersediaan
                        ";
                        $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                        $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog, ":minusJumlahPersediaan" => -$daftarJumlahPersediaan[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.transaksif_stokkatalog
                        WHERE
                            id_depo = :idDepo
                            AND id_katalog = :idKatalog
                            AND status = 1
                        LIMIT 1
                    ";
                    $params = [":idDepo" => 321, ":idKatalog" => $kodeKatalog];
                    $cekStokKatalogGudang = $connection->createCommand($sql, $params)->queryScalar();

                    if ($cekStokKatalogGudang) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.transaksif_stokkatalog
                            SET
                                jumlah_stokfisik = jumlah_stokfisik + :jumlahPersediaan,
                                jumlah_stokadm = jumlah_stokadm + :jumlahPersediaan
                            WHERE
                                id_depo = :idDepo
                                AND id_katalog = :idKatalog
                                AND status = 1
                        ";
                        $params = [":jumlahPersediaan" => $daftarJumlahPersediaan[$key], ":idDepo" => 321, ":idKatalog" => $kodeKatalog];
                        $connection->createCommand($sql, $params)->execute();

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.transaksif_stokkatalog
                            SET
                                id_depo = :idDepo,
                                id_katalog = :idKatalog,
                                status = 1,
                                jumlah_stokfisik = :jumlahPersediaan,
                                jumlah_stokadm = :jumlahPersediaan,
                                jumlah_itemfisik = :jumlahPersediaan
                        ";
                        $params = [":idDepo" => 321, ":idKatalog" => $kodeKatalog, ":jumlahPersediaan" => $daftarJumlahPersediaan[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokadm
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                        AND status = 1
                    LIMIT 1
                ";
                $sql = $tanggalAdm ? str_replace("transaksif_stokkatalog", "masterf_backupstok_so", $sql) : $sql;
                $params = [":idDepo" => $idUnit, ":idKatalog" => $kodeKatalog];
                $jumlahStokAdm = $connection->createCommand($sql, $params)->queryScalar();

                $tglTrans = $toSystemDate($tanggalDokumen);

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        jumlah_keluar = :jumlahKeluar,
                        jumlah_tersedia = :jumlahTersedia,
                        tipe_tersedia = 'rusak',
                        tgl_transaksi = :tanggalTransaksi,
                        tgl_expired = :tanggalKadaluarsa,
                        id_depo = :idDepo,
                        no_batch = :noBatch,
                        keterangan = :keterangan,
                        tgl_tersedia = :tanggalTersedia,
                        userid_last = :idUserUbah,
                        status = :verif
                ";
                $params = [
                    ":kodeRef" => $kodeReferensi,
                    ":noDokumen" => $noDokumen,
                    ":idKatalog" => $kodeKatalog,
                    ":jumlahKeluar" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokAdm,
                    ":tanggalTransaksi" => $tglTrans,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":idDepo" => $idUnit,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":keterangan" => "Rusak karena $keterangan",
                    ":tanggalTersedia" => $date,
                    ":idUserUbah" => $verIdUser,
                    ":verif" => $verif,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jumlah_stokadm
                    FROM db1.transaksif_stokkatalog
                    WHERE
                        id_depo = :idDepo
                        AND id_katalog = :idKatalog
                        AND status = 1
                    LIMIT 1
                ";
                $params = [":idDepo" => 321, ":idKatalog" => $kodeKatalog];
                $jumlahStokAdm = $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        jumlah_masuk = :jumlahMasuk,
                        jumlah_tersedia = :jumlahTersedia,
                        tipe_tersedia = 'rusak',
                        tgl_transaksi = :tanggalTransaksi,
                        tgl_expired = :tanggalKadaluarsa,
                        id_depo = :idDepo,
                        no_batch = :noBatch,
                        keterangan = :keterangan,
                        tgl_tersedia = :tanggalTersedia,
                        userid_last = :idUserUbah,
                        status = :verif
                ";
                $params = [
                    ":kodeRef" => $kodeReferensi,
                    ":noDokumen" => $noDokumen,
                    ":idKatalog" => $kodeKatalog,
                    ":jumlahMasuk" => $daftarJumlahPersediaan[$key],
                    ":jumlahTersedia" => $jumlahStokAdm,
                    ":tanggalTransaksi" => $tglTrans,
                    ":tanggalKadaluarsa" => $daftarTanggalKadaluarsa[$key],
                    ":idDepo" => 321,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":keterangan" => "Rusak karena $keterangan",
                    ":tanggalTersedia" => $date,
                    ":idUserUbah" => $verIdUser,
                    ":verif" => $verif,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_rusak
                    SET
                        kode_reff = :kodeRef,
                        no_doc = :noDokumen,
                        id_katalog = :idKatalog,
                        jumlah_tersedia = :jumlahTersedia,
                        tgl_rusak = :tanggalRusak,
                        tgl_doc = :tanggalDokumen,
                        depo_rusak = :depoRusak,
                        no_batch = :noBatch,
                        hp_item = :hpItem,
                        keterangan = :keterangan,
                        tgl_tersedia = :tanggalTersedia,
                        userid_last = :idUserUbah,
                        status = :verif
                ";
                $params = [
                    ":kodeRef" => $kodeReferensi,
                    ":noDokumen" => $noDokumen,
                    ":idKatalog" => $kodeKatalog,
                    ":jumlahTersedia" => $daftarJumlahPersediaan[$key],
                    ":tanggalRusak" => $daftarTanggalKadaluarsa[$key],
                    ":tanggalDokumen" => $nowValSystem,
                    ":depoRusak" => $idUnit,
                    ":noBatch" => $daftarNoBatch[$key],
                    ":hpItem" => $daftarHargaSatuan[$key],
                    ":keterangan" => "Rusak karena $keterangan",
                    ":tanggalTersedia" => $date,
                    ":idUserUbah" => $verIdUser,
                    ":verif" => $verif,
                ];
                $connection->createCommand($sql, $params)->execute();
            }
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#rusak    the original method
     */
    public function actionFormStokRusakData(): string
    {
        $kodeRef = Yii::$app->request->post("kodeRef") ?? throw new MissingPostParamException("kodeRef");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kode_reff     AS kodeTransaksi,
                a.no_doc        AS noDokumen,
                a.id_depo       AS idUnit,
                a.status        AS verifikasiFloorStock,
                a.userid_last   AS verUserFloorStock,
                a.sysdate_last  AS verTanggalFloorStock,
                a.tgl_transaksi AS tanggalDokumen,
                a.keterangan    AS keterangan,
                NULL            AS daftarRusak
            FROM db1.relasif_ketersediaan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            WHERE
                kode_reff = :kodeRef
                AND tipe_tersedia = 'rusak'
                AND status = 0
                LIMIT 1
        ";
        $params = [":kodeRef" => $kodeRef];
        $ketersediaan = $connection->createCommand($sql, $params)->queryOne();
        if (!$ketersediaan) throw new DataNotExistException($kodeRef);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                b.id              AS idBarang,
                b.nama_barang     AS namaBarang,
                a.jumlah_tersedia AS jumlahPersediaan,
                a.tgl_rusak       AS tanggalKadaluarsa,
                a.no_batch        AS noBatch,
                a.hp_item         AS hargaSatuan
            FROM db1.relasif_rusak AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            WHERE
                a.kode_reff = :kodeRef
                AND depo_rusak != :depoRusak
        ";
        $params = [":kodeRef" => $kodeRef, ":depoRusak" => 321];
        $ketersediaan->daftarRusak = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT id
            FROM db1.masterf_depo
            WHERE kode = :kode
        ";
        $params = [":kode" => Yii::$app->userFatma->idDepo];
        $ketersediaan->idUnit = $ketersediaan->idUnit ?: $connection->createCommand($sql, $params)->queryScalar();

        return json_encode($ketersediaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#laporansemuadepo    the original method
     */
    public function actionLaporanSemuaDepoData(): string
    {
        ["bulan" => $bulan] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                A.id_katalog        AS idKatalog,
                A.tgl_exp           AS tanggalKadaluarsa,
                SUM(A.jumlah_fisik) AS jumlahFisik,
                A.hp_item           AS hpItem,
                B.kode              AS kode,
                B.nama_barang       AS namaBarang,
                B.id_kelompokbarang AS kodeKelompok,
                C.nama_pabrik       AS namaPabrik,
                D.kode              AS satuan,
                F.kelompok_barang   AS kelompokBarang,
                H.tgl_adm           AS tanggalAdm,
                BSO.bckup_stokadm   AS cadanganStokAdm
            FROM db1.relasif_stokopname AS A
            LEFT JOIN db1.masterf_katalog AS B ON A.id_katalog = B.kode
            LEFT JOIN db1.masterf_pabrik AS C ON B.id_pabrik = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasan = D.id
            LEFT JOIN db1.masterf_kelompokbarang AS F ON B.id_kelompokbarang = F.id
            LEFT JOIN db1.transaksif_stokopname AS H ON A.kode_reff = H.kode
            LEFT JOIN (
                SELECT
                    aa.id_katalog          AS id_katalog,
                    SUM(aa.jumlah_stokadm) AS bckup_stokadm
                FROM db1.masterf_backupstok_so AS aa
                LEFT JOIN db1.relasif_stokopname AS bb ON bb.id_katalog = aa.id_katalog
                WHERE
                    SUBSTR(aa.tgl, 6, 2) = :bulan
                    AND bb.kode_reff = aa.kode_reff
                GROUP BY aa.id_katalog
            ) AS BSO ON A.id_katalog = BSO.id_katalog
            WHERE SUBSTR(A.sysdate_in, 6, 2) = :bulan
            GROUP BY A.id_katalog
            ORDER BY B.id_kelompokbarang, B.kode, B.nama_barang
        ";
        $params = [":bulan" => $bulan];
        $daftarStokOpname = $connection->createCommand($sql, $params)->queryAll();

        return $this->renderPartial("laporan-depo", ["data" => $daftarStokOpname]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#hapus    the original method
     */
    public function actionHapus(): int
    {
        assert($_POST["kode_trn"] && $_POST["id_katalog"], new MissingPostParamException("kode_trn", "id_katalog"));
        ["kode_trn" => $kodeReff, "id_katalog" => $idKatalog] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.relasif_floorstock
            WHERE
                kode_reff = :kodeRef
                AND id_katalog = :idKatalog
        ";
        $params = [":kodeRef" => $kodeReff, ":idKatalog" => $idKatalog];
        return $connection->createCommand($sql, $params)->execute();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listkoreksi    the original method
     */
    public function actionTableKoreksiData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                USR.username   AS userName,
                a.no_doc       AS noDokumen,
                a.status       AS status,
                a.sysdate_last AS updatedTime,
                a.kode_reff    AS kodeRef
            FROM db1.relasif_ketersediaan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            LEFT JOIN db1.user AS USR ON USR.id = a.userid_last
            WHERE
                tipe_tersedia = 'koreksiopname'
                AND userid_last != 3378
            GROUP BY a.kode_reff
            ORDER BY id DESC
        ";
        $daftarKetersediaan = $connection->createCommand($sql)->queryAll();

        return json_encode($daftarKetersediaan);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listrusak    the original method
     */
    public function actionTableStokRusakData(): string
    {
        [   "fromdatekp" => $tanggalAwal,
            "enddatekp" => $tanggalAkhir,
            "depoFilter" => $depoFilter,
            "no_rm" => $noDokumen
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                USR.username   AS userName,
                RSK.no_doc     AS noDokumen,
                RSK.status     AS status,
                RSK.sysdate_in AS inputTime,
                RSK.kode_reff  AS kodeRef
            FROM db1.relasif_rusak AS RSK
            LEFT JOIN db1.user AS USR ON USR.id = RSK.userid_last
            WHERE
                (:depoKadaluarsa = '' OR depo_rusak = :depoKadaluarsa)
                AND (:noDokumen = '' OR no_doc LIKE :noDokumen)
                AND tgl_doc > :tanggalAwal
                AND tgl_doc < :tanggalAkhir
            GROUP BY kode_reff
            ORDER BY id_rusak DESC
        ";
        $params = [
            ":depoKadaluarsa" => $depoFilter,
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarRusak = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarRusak);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listexpired    the original method
     */
    public function actionTableStokKadaluarsaData(): string
    {
        [   "fromdatekp" => $tanggalAwal,
            "enddatekp" => $tanggalAkhir,
            "depoFilter" => $depoFilter,
            "no_rm" => $noDokumen
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                EXP.no_doc     AS noDokumen,
                EXP.status     AS status,
                EXP.sysdate_in AS inputTime,
                EXP.kode_reff  AS kodeRef,
                USR.username   AS userName
            FROM db1.relasif_expired AS EXP
            LEFT JOIN db1.user AS USR ON USR.id = EXP.userid_last
            WHERE
                (:depoKadaluarsa = '' OR depo_expired = :depoKadaluarsa)
                AND (:noDokumen = '' OR no_doc LIKE :noDokumen)
                AND tgl_doc > :tanggalAwal
                AND depo_expired = :tanggalAkhir
            GROUP BY kode_reff
            ORDER BY id_expired DESC
        ";
        $params = [
            ":depoKadaluarsa" => $depoFilter,
            ":noDokumen" => $noDokumen ? "%$noDokumen%" : "",
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarKadaluarsa = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKadaluarsa);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listfloor    the original method
     */
    public function actionTableFloorStockData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                USR.username     AS userName,
                a.no_doc         AS noDokumen,
                a.status         AS status,
                a.tgl_verifikasi AS tanggalVerifikasi,
                a.id             AS id
            FROM db1.relasif_floorstock AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.id_katalog
            LEFT JOIN db1.user AS USR ON USR.id = a.verifikasi
            WHERE SUBSTR(a.kode_reff, 5, 6) IN ('201608', '201609', '201610')
            GROUP BY a.no_doc
            ORDER BY a.system_in DESC, a.no_doc ASC
        ";
        $daftarFloorStock = $connection->createCommand($sql)->queryAll();

        return json_encode($daftarFloorStock);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listfloorsept    the original method
     */
    public function actionTableFloorStockDesemberData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ " 
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id             AS id,
                a.no_doc         AS noDokumen,
                a.status         AS status,
                a.verifikasi     AS verifikasi,
                a.tgl_verifikasi AS tanggalVerifikasi,
                ''               AS userName
            FROM db1.relasif_floorstock a
            INNER JOIN db1.masterf_katalog b ON b.kode = a.id_katalog
            WHERE SUBSTR(system_in, 6, 2) IN (01, 12)
            GROUP BY no_doc
            ORDER BY no_doc ASC
        ";
        $daftarResep = $connection->createCommand($sql)->queryAll();

        foreach ($daftarResep as $resep) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT userName
                FROM db1.user
                WHERE id = :id
            ";
            $params = [":id" => $resep->verifikasi];
            $resep->userName = $connection->createCommand($sql, $params)->queryScalar();
        }

        return json_encode($daftarResep);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/stokopname.php#listfloormaret    the original method
     */
    public function actionTableFloorStockMaretData()
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ " 
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.id             AS id,
                a.no_doc         AS noDokumen,
                a.status         AS status,
                a.verifikasi     AS verifikasi,
                a.tgl_verifikasi AS tanggalVerifikasi,
                ''               AS userName
            FROM db1.relasif_floorstock a
            INNER JOIN db1.masterf_katalog b ON b.kode = a.id_katalog
            WHERE SUBSTR(kode_reff, 5, 6) IN (201603, 201604)
            GROUP BY no_doc
            ORDER BY no_doc ASC
        ";
        $daftarResep = $connection->createCommand($sql)->queryAll();

        foreach ($daftarResep as $resep) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT userName
                FROM db1.user
                WHERE id = :id
            ";
            $params = [":id" => $resep->verifikasi];
            $resep->userName = $connection->createCommand($sql, $params)->queryAll();
        }
    }
}
