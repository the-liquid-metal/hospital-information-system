<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

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
class HargaController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/masterharga.php#hargaperolehan    the original method
     */
    public function actionHargaPerolehanTableData(): string
    {
        [   "idKatalog" => $idKatalog,
            "namaSediaan" => $namaSediaan,
            "noDokumen" => $noDokumen,
            "namaPemasok" => $namaPemasok,
            "tanggalHp" => $tanggalHp,
            "hnaItem" => $hnaItem,
            "hpItem" => $hpItem,
            "phja" => $phja,
            "tanggalAktifHp" => $tanggalAktifHp,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                HP.id_katalog                    AS idKatalog,
                HP.tgl_hp                        AS tanggalHp,
                HP.tgl_aktifhp                   AS tanggalAktifHp,
                HP.hna_item                      AS hnaItem,
                HP.hp_item                       AS hpItem,
                HP.phja                          AS phja,
                HP.hja_item                      AS hjaItem,
                HP.sts_hja                       AS statusHja,
                HP.keterangan                    AS keterangan,
                IFNULL(TRM.no_doc, HP.kode_reff) AS noDokumen,
                PBF.nama_pbf                     AS namaPemasok,
                KAT.nama_sediaan                 AS namaSediaan,
                USR.name                         AS updatedBy,
                HP.sysdate_updt                  AS updatedTime
            FROM db1.relasif_hargaperolehan AS HP
            LEFT JOIN db1.transaksif_penerimaan AS TRM ON HP.kode_reff = TRM.kode
            LEFT JOIN db1.masterf_pbf AS PBF ON TRM.id_pbf = PBF.id
            LEFT JOIN db1.masterf_katalog AS KAT ON HP.id_katalog = KAT.kode
            LEFT JOIN db1.masterf_kemasan AS KEM ON KAT.id_kemasankecil = KEM.id
            LEFT JOIN db1.user AS USR ON HP.userid_updt = USR.id
            WHERE
                (:idKatalog = '' OR HP.id_katalog LIKE :idKatalog)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (:noDokumen = '' OR TRM.no_doc LIKE :noDokumen)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
                AND (:tanggalHp = '' OR HP.tgl_hp LIKE :tanggalHp)
                AND (:hnaItem = '' OR HP.hna_item LIKE :hnaItem)
                AND (:hpItem = '' OR HP.hp_item LIKE :hpItem)
                AND (:phja = '' OR HP.phja LIKE :phja)
                AND (:tanggalAktifHp = '' OR HP.tgl_aktifhp LIKE :tanggalAktifHp)
            ORDER BY id_jenisharga, id_katalog ASC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":namaSediaan" => $namaSediaan,
            ":noDokumen" => $noDokumen,
            ":namaPemasok" => $namaPemasok,
            ":tanggalHp" => $tanggalHp,
            ":hnaItem" => $hnaItem,
            ":hpItem" => $hpItem,
            ":phja" => $phja,
            ":tanggalAktifHp" => $tanggalAktifHp,
        ];
        $daftarHargaPerolehan = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarHargaPerolehan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/masterharga.php#hargajual    the original method
     */
    public function actionHargaJualTableData(): string
    {
        [   "idKatalog" => $idKatalog,
            "namaSediaan" => $namaSediaan,
            "noDokumen" => $noDokumen,
            "namaPemasok" => $namaPemasok,
            "tanggalHp" => $tanggalHp,
            "hnaItem" => $hnaItem,
            "hpItem" => $hpItem,
            "phja" => $phja,
            "tanggalAktifHp" => $tanggalAktifHp,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                HP.id_katalog                    AS idKatalog,
                HP.tgl_hp                        AS tanggalHp,
                HP.tgl_aktifhp                   AS tanggalAktifHp,
                HP.hna_item                      AS hnaItem,
                HP.hp_item                       AS hpItem,
                HP.phja                          AS phja,
                HP.hja_item                      AS hjaItem,
                HP.sts_hja                       AS statusHja,
                HP.keterangan                    AS keterangan,
                IFNULL(TRM.no_doc, HP.kode_reff) AS noDokumen,
                PBF.nama_pbf                     AS namaPemasok,
                KAT.nama_sediaan                 AS namaSediaan,
                USR.name                         AS updatedBy,
                HP.sysdate_updt                  AS updatedTime
            FROM db1.relasif_hargaperolehan AS HP
            LEFT JOIN db1.transaksif_penerimaan AS TRM ON HP.kode_reff = TRM.kode
            LEFT JOIN db1.masterf_pbf AS PBF ON TRM.id_pbf = PBF.id
            LEFT JOIN db1.masterf_katalog AS KAT ON HP.id_katalog = KAT.kode
            LEFT JOIN db1.masterf_kemasan AS KEM ON KAT.id_kemasankecil = KEM.id
            LEFT JOIN db1.user AS USR ON HP.userid_updt = USR.id
            ORDER BY id_jenisharga, id_katalog ASC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":idKatalog" => $idKatalog,
            ":namaSediaan" => $namaSediaan,
            ":noDokumen" => $noDokumen,
            ":namaPemasok" => $namaPemasok,
            ":tanggalHp" => $tanggalHp,
            ":hnaItem" => $hnaItem,
            ":hpItem" => $hpItem,
            ":phja" => $phja,
            ":tanggalAktifHp" => $tanggalAktifHp,
        ];
        $daftarHargaPerolehan = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarHargaPerolehan);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/masterharga.php#ajaxSearch    the original method
     */
    public function actionAjaxSearch(): string
    {
        assert($_POST["dataqFilter"] == "sql", new MissingPostParamException("dataqFilter"));
        $post = Yii::$app->request->post();
        $post["dataUrl"] = "farmasi/master-harga/";

        foreach ($post["dataExtention"] ?? [] as $key => $val) {
            // TODO: php: uncategorized: confirm variable variable
            $$key = $val;
        }

        $dataType = $post["dataType"] ?? "json";
        $postFilter = $post["dataFilter"] ?? [];

        $whereLain = "";
        if (is_array($postFilter)) {
            // default adalah array, jika sql maka di eksport
            foreach ($postFilter as $table => $dataFilter) {
                foreach ($dataFilter as $field => $dt) {
                    $operator = $dt["opt"];
                    $value = $dt["val"];

                    if ($operator == "IN" || $operator == "NOT IN") {
                        $value = (is_array($value))
                            ? "('" . implode("','", $value) . "')"
                            : "('" . str_replace(",", "','", $value) . "')";

                    } elseif ($operator == "LIKE" || $operator == "NOT LIKE") {
                        $value = "'%$value%'";
                    } else {
                        $value = "'$value'";
                    }
                    $whereLain .= "AND $table.$field  $operator  $value \n";
                }
            }

        // jika bukan array dan $filter NOT NULL
        } elseif ($whereLain != NULL) {
            $whereLain .= " AND " . $postFilter;

        // jika bukan array dan $filter NULL
        } else {
            $whereLain = $postFilter;
        }
        $whereLain = preg_replace("/^ *(AND|OR)\b/i", "", $whereLain);

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. " 
            SELECT
                id_katalog     AS idKatalog,
                id_pbf         AS idPemasok,
                id_jenisharga  AS idJenisHarga,
                id_kemasan     AS idKemasan,
                isi_kemasan    AS isiKemasan,
                id_kemasandepo AS idKemasanDepo,
                harga_item     AS hargaItem,
                harga_kemasan  AS hargaKemasan,
                diskon_item    AS diskonItem,
                sts_actived    AS statusAktivasi,
                userid_updt    AS updatedById,
                sysdate_updt   AS updatedTime
            FROM db1.relasif_katalogpbf AS A
            WHERE $whereLain
        ";
        $daftarKatalogPbf = $connection->createCommand($sql)->queryAll();
        $jumlahKatalogPbf = count($daftarKatalogPbf);

        if ($dataType == "json") {
            return json_encode([$jumlahKatalogPbf, $daftarKatalogPbf]);
        } else {
            $post["fields"] = [];
            $post["iTotal"] = $jumlahKatalogPbf;
            $post["data"] = $daftarKatalogPbf;
            return $this->renderPartial($dataType, $post);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function actionHargaPembelianUlpTableData(): string
    {
        [   "jenisHarga" => $jenisHarga,
            "idKatalog" => $idKatalog,
            "namaSediaan" => $namaSediaan,
            "namaPabrik" => $namaPabrik,
            "namaPemasok" => $namaPemasok,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                KPBF.id_katalog     AS idKatalog,
                KPBF.isi_kemasan    AS isiKemasan,
                KPBF.harga_item     AS hargaItem,
                KPBF.harga_kemasan  AS hargaKemasan,
                KPBF.diskon_item    AS diskonItem,
                JHG.jenis_harga     AS jenisHarga,
                KAT1.nama_sediaan   AS namaSediaan,
                PBK.nama_pabrik     AS namaPabrik,
                PBF.nama_pbf        AS namaPemasok,
                KEM1.kode           AS kemasanBesar,
                USR.name            AS updatedBy,
                KPBF.sysdate_updt   AS updatedTime
            FROM db1.relasif_katalogpbf AS KPBF
            LEFT JOIN db1.masterf_jenisharga AS JHG ON KPBF.id_jenisharga = JHG.id
            LEFT JOIN db1.masterf_katalog AS KAT1 ON KPBF.id_katalog = KAT1.kode
            LEFT JOIN db1.masterf_pabrik AS PBK ON KAT1.id_pabrik = PBK.id
            LEFT JOIN db1.masterf_pbf AS PBF ON KPBF.id_pbf = PBF.id
            LEFT JOIN db1.masterf_kemasan AS KEM1 ON KPBF.id_kemasan = KEM1.id
            LEFT JOIN db1.masterf_kemasan AS KEM2 ON KPBF.id_kemasandepo = KEM2.id
            LEFT JOIN db1.user AS USR ON KPBF.userid_updt = USR.id
            WHERE
                KPBF.sts_actived = 1
                AND (:jenisHarga = '' OR JHG.jenis_harga LIKE :jenisHarga)
                AND (:idKatalog = '' OR KPBF.id_katalog LIKE :idKatalog)
                AND (:namaSediaan = '' OR KAT1.nama_sediaan LIKE :namaSediaan)
                AND (:namaPabrik = '' OR PBK.nama_pabrik LIKE :namaPabrik)
                AND (:namaPemasok = '' OR PBF.nama_pbf LIKE :namaPemasok)
            ORDER BY id_jenisharga, id_katalog ASC
            LIMIT $limit
            OFFSET $offset
        ";
        $params = [
            ":jenisHarga" => $jenisHarga ? "%$jenisHarga%" : "",
            ":idKatalog" => $idKatalog ? "%$idKatalog%" : "",
            ":namaSediaan" => $namaSediaan ? "%$namaSediaan%" : "",
            ":namaPabrik" => $namaPabrik ? "%$namaPabrik%" : "",
            ":namaPemasok" => $namaPemasok ? "%$namaPemasok%" : "",
        ];
        $daftarKatalogPemasok = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarKatalogPemasok);
    }
}
