<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\{DataNotExistException, FarmasiModel2, FieldNotExistException};
use tlm\libs\LowEnd\components\DateTimeException;
use Yii;
use yii\db\Exception;
use yii\web\Response;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class Katalog1Controller extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#index    the original method
     */
    public function actionTable1Data(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        [   "idJenisBarang" => $idJenisBarang,
            "idKelompokBarang" => $idKelompokBarang,
            "formulariumRs" => $stsFrs,
            "formulariumNasional" => $stsFornas,
            "barangProduksi" => $stsProduksi,
            "barangKonsinyasi" => $stsKonsinyasi,
            "statusAktif" => $stsAktif,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $params = [
            ":idJenisBarang" => $idJenisBarang,
            ":idKelompokBarang" => $idKelompokBarang,
            ":formulariumRs" => $stsFrs,
            ":formulariumNasional" => $stsFornas,
            ":barangProduksi" => $stsProduksi,
            ":barangKonsinyasi" => $stsKonsinyasi,
            ":statusAktif" => $stsAktif,
        ];

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. "
            SELECT -- all are in use.
                KAT.id              AS id,
                KAT.kode            AS kode,
                KAT.nama_sediaan    AS namaSediaan,
                KAT.sysdate_updt    AS updatedTime,
                KAT.kemasan         AS namaKemasan,
                KAT.harga_beli      AS hargaBeli,
                USR.name            AS updatedBy,
                PBK.nama_pabrik     AS namaPabrik,
                BRN.nama_dagang     AS namaDagang,
                GEN.nama_generik    AS namaGenerik,
                JOB.jenis_obat      AS jenisObat,
                KCIL.nama_kemasan   AS namaKemasanKecil,
                USR.id_depo         AS idDepo
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.user AS USR ON KAT.userid_updt = USR.id
            LEFT JOIN db1.masterf_jenisobat AS JOB ON JOB.id = id_jenisbarang
            LEFT JOIN db1.masterf_brand AS BRN ON BRN.id = id_brand
            LEFT JOIN db1.masterf_generik AS GEN ON GEN.id = id_generik
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = id_kemasankecil
            WHERE
                (:idJenisBarang = '' OR KAT.id_jenisbarang = :idJenisBarang)
                AND (:idKelompokBarang = '' OR KAT.id_kelompokbarang = :idKelompokBarang)
                AND (:formulariumRs = '' OR sts_frs = :formulariumRs)
                AND (:formulariumNasional = '' OR sts_fornas = :formulariumNasional)
                AND (:barangProduksi = '' OR sts_produksi = :barangProduksi)
                AND (:barangKonsinyasi = '' OR sts_konsinyasi = :barangKonsinyasi)
                AND (
                    (:statusAktif = 2 AND KAT.sts_hapus = 1)
                    OR KAT.sts_aktif = :statusAktif
                )
            ORDER BY KAT.nama_sediaan
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. "
            SELECT COUNT(*)
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.user AS USR ON KAT.userid_updt = USR.id
            LEFT JOIN db1.masterf_jenisobat AS JOB ON JOB.id = id_jenisbarang
            LEFT JOIN db1.masterf_brand AS BRN ON BRN.id = id_brand
            LEFT JOIN db1.masterf_generik AS GEN ON GEN.id = id_generik
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = id_pabrik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = id_kemasankecil
            WHERE
                (:idJenisBarang = '' OR KAT.id_jenisbarang = :idJenisBarang)
                AND (:idKelompokBarang = '' OR KAT.id_kelompokbarang = :idKelompokBarang)
                AND (:formulariumRs = '' OR sts_frs = :formulariumRs)
                AND (:formulariumNasional = '' OR sts_fornas = :formulariumNasional)
                AND (:barangProduksi = '' OR sts_produksi = :barangProduksi)
                AND (:barangKonsinyasi = '' OR sts_konsinyasi = :barangKonsinyasi)
                AND (
                    (:statusAktif = 2 AND KAT.sts_hapus = 1)
                    OR KAT.sts_aktif = :statusAktif
                )
        ";
        $totalKatalog = $connection->createCommand($sql, $params)->queryScalar();

        return ["total" => $totalKatalog, "rows" => $daftarKatalog];
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#add    the original method
     */
    public function actionSave(): void
    {
        [   "action" => $action,
            "kode" => $kode,
            "id" => $id,
            "nama_sediaan" => $namaSediaan,
            "id_brand" => $idBrand,
            "id_jenisbarang" => $idJenisBarang,
            "id_kelompokbarang" => $idKelompokBarang,
            "id_kemasanbesar" => $idKemasanBesar,
            "id_kemasankecil" => $idKemasanKecil,
            "id_sediaan" => $idSediaan,
            "isi_kemasan" => $isiKemasan,
            "isi_sediaan" => $isiSediaan,
            "kemasan" => $kemasan,
            "id_pabrik" => $idPabrik,
            "id_pbf" => $idPemasok,
            "harga_beli" => $hargaBeli,
            "diskon_beli" => $diskonBeli,
            "formularium_rs" => $formulariumRs,
            "formularium_nas" => $formulariumNas,
            "generik" => $generik,
            "live_saving" => $liveSaving,
            "moving" => $moving,
            "leadtime" => $leadtime,
            "buffer" => $buffer,
            "zat_aktif" => $zatAktif,
            "retriksi" => $restriksi,
            "aktifasi" => $aktifasi,
            "keterangan" => $keterangan,
        ] = Yii::$app->request->post();
        $toSystemNumber = Yii::$app->number->toSystemNumber();
        $todayValSystem = Yii::$app->dateTime->todayVal("system");
        $idUser = Yii::$app->userFatma->id;

        // edit fragment
        if ($kode) {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    KAT.id                AS id,
                    KAT.kode              AS kode,
                    KAT.nama_sediaan      AS namaSediaan,
                    KAT.nama_barang       AS namaBarang,
                    KAT.id_brand          AS idBrand,
                    KAT.id_jenisbarang    AS idJenisBarang,
                    KAT.id_kelompokbarang AS idKelompokBarang,
                    KAT.id_kemasanbesar   AS idKemasanBesar,
                    KAT.id_kemasankecil   AS idKemasanKecil,
                    KAT.id_sediaan        AS idSediaan,
                    KAT.isi_kemasan       AS isiKemasan,
                    KAT.isi_sediaan       AS isiSediaan,
                    KAT.jumlah_itembeli   AS jumlahItemBeli,
                    KAT.jumlah_itembonus  AS jumlahItemBonus,
                    KAT.kemasan           AS kemasan,
                    KAT.jenis_barang      AS jenisBarang,
                    KAT.id_pbf            AS idPemasok,
                    KAT.id_pabrik         AS idPabrik,
                    KAT.harga_beli        AS hargaBeli,
                    KAT.harga_kemasanbeli AS hargaKemasanBeli,
                    KAT.diskon_beli       AS diskonBeli,
                    KAT.harga_jual        AS hargaJual,
                    KAT.diskon_jual       AS diskonJual,
                    KAT.stok_adm          AS stokAdm,
                    KAT.stok_fisik        AS stokFisik,
                    KAT.stok_min          AS stokMin,
                    KAT.stok_opt          AS stokOpt,
                    KAT.formularium_rs    AS formulariumRs,
                    KAT.formularium_nas   AS formulariumNas,
                    KAT.generik           AS generik,
                    KAT.live_saving       AS liveSaving,
                    KAT.sts_frs           AS statusFrs,
                    KAT.sts_fornas        AS statusFornas,
                    KAT.sts_generik       AS statusGenerik,
                    KAT.sts_livesaving    AS statusLiveSaving,
                    KAT.sts_produksi      AS statusProduksi,
                    KAT.sts_konsinyasi    AS statusKonsinyasi,
                    KAT.sts_ekatalog      AS statusEkatalog,
                    KAT.sts_sumbangan     AS statusSumbangan,
                    KAT.sts_narkotika     AS statusNarkotika,
                    KAT.sts_psikotropika  AS statusPsikotropika,
                    KAT.sts_prekursor     AS statusPrekursor,
                    KAT.sts_keras         AS statusKeras,
                    KAT.sts_bebas         AS statusBebas,
                    KAT.sts_bebasterbatas AS statusBebasTerbatas,
                    KAT.sts_part          AS statusPart,
                    KAT.sts_alat          AS statusAlat,
                    KAT.sts_asset         AS statusAset,
                    KAT.sts_aktif         AS statusAktif,
                    KAT.sts_hapus         AS statusHapus,
                    KAT.moving            AS moving,
                    KAT.leadtime          AS leadtime,
                    KAT.optimum           AS optimum,
                    KAT.buffer            AS buffer,
                    KAT.zat_aktif         AS zatAktif,
                    KAT.retriksi          AS restriksi,
                    KAT.keterangan        AS keterangan,
                    KAT.aktifasi          AS aktifasi,
                    KAT.userid_in         AS useridInput,
                    KAT.sysdate_in        AS sysdateInput,
                    KAT.userid_updt       AS useridUpdate,
                    KAT.sysdate_updt      AS sysdateUpdate,
                    USR.name              AS namaUser,
                    PBK.nama_pabrik       AS namaPabrik,
                    PBF.nama_pbf          AS namaPemasok,
                    BRN.nama_dagang       AS namaDagang,
                    GEN.nama_generik      AS namaGenerik,
                    JOB.jenis_obat        AS namaJenisObat,
                    KBG.kelompok_barang   AS namaKelompokBarang,
                    KSAR.nama_kemasan     AS namaKemasanBesar,
                    KCIL.nama_kemasan     AS namaKemasanKecil,
                    SDN.nama_kemasan      AS sediaanKemasan
                FROM db1.masterf_katalog AS KAT
                LEFT JOIN db1.user AS USR ON KAT.userid_updt = USR.id
                LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
                LEFT JOIN db1.masterf_pbf AS PBF ON PBF.id = KAT.id_pbf
                LEFT JOIN db1.masterf_jenisobat AS JOB ON JOB.id = KAT.id_jenisbarang
                LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KBG.id = KAT.id_kelompokbarang
                LEFT JOIN db1.masterf_brand AS BRN ON BRN.id = KAT.id_brand
                LEFT JOIN db1.masterf_generik AS GEN ON GEN.id = BRN.id_generik
                LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = KAT.id_kemasanbesar
                LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
                LEFT JOIN db1.masterf_kemasan AS SDN ON SDN.id = KAT.id_sediaan
                WHERE KAT.id = :id
            ";
            $params = [":id" => $kode];
            $jumlahKatalog = $connection->createCommand($sql, $params)->queryOne();
            if (!$jumlahKatalog) throw new DataNotExistException($kode);
        }

        $dataKatalog = [
            "kode" => $kode,
            "nama_sediaan" => $namaSediaan,
            "nama_barang" => $namaSediaan,
            "id_brand" => $idBrand,
            "id_jenisbarang" => $idJenisBarang,
            "id_kelompokbarang" => $idKelompokBarang,
            "id_kemasanbesar" => $idKemasanBesar,
            "id_kemasankecil" => $idKemasanKecil,
            "id_sediaan" => $idSediaan,
            "isi_kemasan" => $isiKemasan,
            "isi_sediaan" => $toSystemNumber($isiSediaan),
            "kemasan" => $kemasan,
            "id_pabrik" => $idPabrik,
            "id_pbf" => $idPemasok,
            "harga_beli" => $toSystemNumber($hargaBeli),
            "diskon_beli" => $toSystemNumber($diskonBeli),
            "formularium_rs" => $formulariumRs ?? 0,
            "formularium_nas" => $formulariumNas ?? 0,
            "generik" => $generik ?? 0,
            "live_saving" => $liveSaving ?? 0,
            "moving" => $moving,
            "leadtime" => $leadtime,
            "buffer" => $buffer,
            "zat_aktif" => $zatAktif,
            "retriksi" => $restriksi,
            "aktifasi" => $aktifasi,
            "sts_aktif" => $aktifasi,
            "keterangan" => $keterangan,
            "userid_updt" => $idUser,
        ];

        $fm2 = new FarmasiModel2;
        if ($action == "add") {
            $dataKatalog = [
                ...$dataKatalog,
                "userid_in" => $idUser,
                "sysdate_in" => $todayValSystem,
            ];
            $daftarField = array_keys($dataKatalog);
            $fm2->saveData("masterf_katalog", $daftarField, $dataKatalog);

        } elseif ($action == "edit") {
            $this->db->simple_query("CALL update_katalog('$id', '$kode', '$kode');");

            $daftarField = array_keys($dataKatalog);
            $where = ["id" => $id];
            $fm2->saveData("masterf_katalog", $daftarField, $dataKatalog, $where);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DataNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                KAT.id                AS id,
                KAT.kode              AS kode,
                KAT.nama_sediaan      AS namaSediaan,
                KAT.nama_barang       AS namaBarang,
                KAT.id_brand          AS idBrand,
                KAT.id_jenisbarang    AS idJenisBarang,
                KAT.id_kelompokbarang AS idKelompokBarang,
                KAT.id_kemasanbesar   AS idKemasanBesar,
                KAT.id_kemasankecil   AS idKemasanKecil,
                KAT.id_sediaan        AS idSediaan,
                KAT.isi_kemasan       AS isiKemasan,
                KAT.isi_sediaan       AS isiSediaan,
                KAT.jumlah_itembeli   AS jumlahItemBeli,
                KAT.jumlah_itembonus  AS jumlahItemBonus,
                KAT.kemasan           AS kemasan,
                KAT.jenis_barang      AS jenisBarang,
                KAT.id_pbf            AS idPemasok,
                KAT.id_pabrik         AS idPabrik,
                KAT.harga_beli        AS hargaBeli,
                KAT.harga_kemasanbeli AS hargaKemasanBeli,
                KAT.diskon_beli       AS diskonBeli,
                KAT.harga_jual        AS hargaJual,
                KAT.diskon_jual       AS diskonJual,
                KAT.stok_adm          AS stokAdm,
                KAT.stok_fisik        AS stokFisik,
                KAT.stok_min          AS stokMin,
                KAT.stok_opt          AS stokOpt,
                KAT.formularium_rs    AS formulariumRs,
                KAT.formularium_nas   AS formulariumNas,
                KAT.generik           AS generik,
                KAT.live_saving       AS liveSaving,
                KAT.sts_frs           AS statusFrs,
                KAT.sts_fornas        AS statusFornas,
                KAT.sts_generik       AS statusGenerik,
                KAT.sts_livesaving    AS statusLiveSaving,
                KAT.sts_produksi      AS statusProduksi,
                KAT.sts_konsinyasi    AS statusKonsinyasi,
                KAT.sts_ekatalog      AS statusEkatalog,
                KAT.sts_sumbangan     AS statusSumbangan,
                KAT.sts_narkotika     AS statusNarkotika,
                KAT.sts_psikotropika  AS statusPsikotropika,
                KAT.sts_prekursor     AS statusPrekursor,
                KAT.sts_keras         AS statusKeras,
                KAT.sts_bebas         AS statusBebas,
                KAT.sts_bebasterbatas AS statusBebasTerbatas,
                KAT.sts_part          AS statusPart,
                KAT.sts_alat          AS statusAlat,
                KAT.sts_asset         AS statusAset,
                KAT.sts_aktif         AS statusAktif,
                KAT.sts_hapus         AS statusHapus,
                KAT.moving            AS moving,
                KAT.leadtime          AS leadtime,
                KAT.optimum           AS optimum,
                KAT.buffer            AS buffer,
                KAT.zat_aktif         AS zatAktif,
                KAT.retriksi          AS restriksi,
                KAT.keterangan        AS keterangan,
                KAT.aktifasi          AS aktivasi,
                KAT.userid_in         AS createdBy,
                KAT.sysdate_in        AS createdTime,
                KAT.userid_updt       AS updatedBy,
                KAT.sysdate_updt      AS updatedTime,
                USR.name              AS name,
                PBK.nama_pabrik       AS namaPabrik,
                PBF.nama_pbf          AS namaPemasok,
                BRN.nama_dagang       AS namaDagang,
                GEN.nama_generik      AS namaGenerik,
                JOB.jenis_obat        AS jenisObat,
                KBG.kelompok_barang   AS kelompokBarang,
                KSAR.nama_kemasan     AS kemasanBesar,
                KCIL.nama_kemasan     AS kemasanKecil,
                SDN.nama_kemasan      AS sediaanKemasan
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.user AS USR ON KAT.userid_updt = USR.id
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            LEFT JOIN db1.masterf_pbf AS PBF ON PBF.id = KAT.id_pbf
            LEFT JOIN db1.masterf_jenisobat AS JOB ON JOB.id = KAT.id_jenisbarang
            LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KBG.id = KAT.id_kelompokbarang
            LEFT JOIN db1.masterf_brand AS BRN ON BRN.id = KAT.id_brand
            LEFT JOIN db1.masterf_generik AS GEN ON GEN.id = BRN.id_generik
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = KAT.id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = KAT.id_kemasankecil
            LEFT JOIN db1.masterf_kemasan AS SDN ON SDN.id = KAT.id_sediaan
            WHERE KAT.id = :id
            LIMIT 1
        ";
        $params = [":id" => $id];
        $katalog = $connection->createCommand($sql, $params)->queryOne();
        if (!$katalog) throw new DataNotExistException($id);

        return json_encode($katalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#delete    the original method
     */
    public function actionDelete(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_katalog
            SET sts_hapus = 1
            WHERE $field = :val
        ";
        $params = [":val" => $val];
        $jumlahRowHapus = $connection->createCommand($sql, $params)->execute();

        return json_encode($jumlahRowHapus);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#select    the original method
     */
    public function actionSelect(): string
    {
        $connection = Yii::$app->dbFatma;
        ["q" => $val] = Yii::$app->request->post();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                KAT.id              AS id, -- this is actually not used
                KAT.kode            AS kode,
                KAT.nama_sediaan    AS namaSediaan,
                KAT.formularium_rs  AS formulariumRs,
                KAT.formularium_nas AS formulariumNas,
                PBK.nama_pabrik     AS namaPabrik
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            WHERE
                KAT.kode LIKE :val
                OR nama_sediaan LIKE :val
            ORDER BY nama_sediaan ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKatalog);
    }

    /**
     * @author Hendra Gunawan
     */
    public function actionGetKatalog(): string {
        $result = [];
        ["indeks" => $indeks, "nm_katalog" => $namaKatalog] = $this->input->post();
        if ($this->input->is_ajax_request() && $indeks && $namaKatalog) {
            $datasend = [
                "fungsi" => "test",
                "fung" => "getkatalog",
                "indeks" => $indeks,
                "nm_katalog" => $namaKatalog,
            ];
            $result = $this->getFromMedisys($datasend);
        }
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     */
    private function getFromMedisys(array $data): mixed {
        $dataString = "";
        foreach($data as $key => $value) {
            $dataString .= $key.'='.$value.'&';
        }

        $url = "http://202.137.25.13:5555/bridging/Bridging_latihan.php";
        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($post, CURLOPT_HEADER, 0);

        $result = curl_exec($post);
        curl_close($post);

        $result = json_decode($result);
        return $result;
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#selectstokadm    the original method
     */
    public function actionSelectStokAdm(): string
    {
        assert($_POST["q"] && $_POST["id_depo"], new MissingPostParamException("q", "id_depo"));
        ["q" => $val, "id_depo" => $idDepo, "kd_reff" => $kodeReff] = Yii::$app->request->post();
        $kodeReff ??= date("Ym");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                K.id                          AS id,
                K.kode                        AS kode,
                K.nama_sediaan                AS namaSediaan,
                K.formularium_rs              AS formulariumRs,
                K.formularium_nas             AS formulariumNas,
                P.nama_pabrik                 AS namaPabrik,
                IFNULL(BSO.jumlah_stokadm, 0) AS jumlahStokAdm
            FROM db1.masterf_katalog AS K
            LEFT JOIN db1.masterf_pabrik AS P ON P.id = K.id_pabrik
            INNER JOIN db1.transaksif_stokkatalog AS aa on K.kode = aa.id_katalog
            LEFT JOIN (
                SELECT DISTINCT
                    id_katalog,
                    jumlah_stokfisik AS jumlah_stokadm
                FROM db1.masterf_backupstok_so
                WHERE
                    id_depo = :idDepo
                    AND kode_reff LIKE :kodeRef
                ORDER BY tgl DESC
            ) AS BSO ON K.kode = BSO.id_katalog
            WHERE
                K.kode LIKE :val
                OR nama_sediaan LIKE :val
                AND aa.id_depo = :idDepo
            ORDER BY nama_sediaan ASC
            limit 30
        ";
        $params = [":idDepo" => $idDepo, ":kodeRef" => "%$kodeReff%", ":val" => "%$val%"];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarKatalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-13f0cbf
     */
    public function actionSearchJsonSubjenis(): string
    {
        ["idJenis" => $idJenis, "query" => $val] = Yii::$app->request->post();

        // harga didapat dari harga supplier terakhir dan difilter berdasarkan sub jenis
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode                         AS idKatalog,
                A.nama_sediaan                 AS namaSediaan,
                A.id_kemasanbesar              AS idKemasan,
                A.id_kemasankecil              AS idKemasanDepo,
                A.isi_kemasan                  AS isiKemasan,
                A.id_pabrik                    AS idPabrik,
                1                              AS jumlahItem,
                B.nama_pabrik                  AS namaPabrik,
                C.kode                         AS satuan,
                D.kode                         AS satuanJual,
                A.harga_beli                   AS hargaItem,
                (A.harga_beli * A.isi_kemasan) AS hargaKemasan,
                A.diskon_beli                  AS diskonItem
            FROM db1.masterf_katalog AS A
            LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
            LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasanbesar = D.id
            INNER JOIN db1.masterf_subjenisanggaran AS E ON A.id_jenisbarang = E.id
            INNER JOIN db1.relasif_anggaran AS F ON E.id = F.id_subjenis
            WHERE
                A.sts_aktif = 1
                AND (A.nama_sediaan LIKE :val OR A.kode LIKE :val) 
                AND (:idJenis = '' OR F.id_jenis = :idJenis)
                AND F.sts_aktif = 1
            LIMIT 30
        ";
        $params = [":val" => "%$val%", ":idJenis" => $idJenis];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKatalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalog.php#ajaxSearch    the original method
     * last exist of actionAjaxSearch: commit-13f0cbf
     */
    public function actionSearchJsonSearch(): string
    {
        $query = Yii::$app->request->post("query");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                A.kode            AS idKatalog,
                A.nama_sediaan    AS namaSediaan,
                A.id_kemasanbesar AS idKemasan,
                A.id_kemasankecil AS idKemasanDepo,
                A.isi_kemasan     AS isiKemasan,
                B.nama_pabrik     AS namaPabrik,
                C.kode            AS satuan,
                D.kode            AS satuanJual
            FROM db1.masterf_katalog AS A
            LEFT JOIN db1.masterf_pabrik AS B ON A.id_pabrik = B.id
            LEFT JOIN db1.masterf_kemasan AS C ON A.id_kemasankecil = C.id
            LEFT JOIN db1.masterf_kemasan AS D ON A.id_kemasanbesar = D.id
            WHERE
                A.sts_aktif = 1
                AND (A.nama_sediaan LIKE :query OR A.kode LIKE :query)
            LIMIT 30
        ";
        $params = [":query" => "%$query%"];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKatalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws FieldNotExistException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/Masterdata#checkinput as the source of copied text
     *
     * Designed for widget validation mechanism. To simplify code, this method always returns a success HTTP status. the
     * response body can be json "false" or object.
     */
    public function actionCekUnik(): string
    {
        assert($_POST["field"] && $_POST["value"], new MissingPostParamException("field", "value"));
        ["field" => $field, "value" => $val] = Yii::$app->request->post();
        if (!in_array($field, ["kode", "nama_sediaan"])) throw new FieldNotExistException($field);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                kode         AS kode,
                nama_sediaan AS namaSediaan
            FROM db1.masterf_katalog
            WHERE $field = :val
            LIMIT 1
        ";
        $params = [":val" => $val];
        $data = $connection->createCommand($sql, $params)->queryOne();

        return json_encode($data);
    }
}
