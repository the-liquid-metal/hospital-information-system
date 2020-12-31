<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use PHPExcel;
use PHPExcel_Exception;
use PHPExcel_IOFactory;
use PHPExcel_Reader_Exception;
use PHPExcel_Writer_Exception;
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
class Katalog2Controller extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalogtest.php#add    the original method
     */
    public function actionForm(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id           AS id,
                kode         AS kode,
                kode_group   AS kodeGroup,
                jenis_obat   AS jenisObat,
                kode_farmasi AS kodeFarmasi,
                nama_farmasi AS namaFarmasi,
                nama_ulp     AS namaUlp,
                kode_temp    AS kodeTemp,
                sts_hapus    AS statusHapus,
                no_urut      AS noUrut,
                userid_updt  AS useridUpdate,
                sysdate_updt AS sysdateUpdate
            FROM db1.masterf_jenisobat
            ORDER BY jenis_obat ASC
        ";
        $daftarJenisObat = $connection->createCommand($sql)->queryAll();
        $jenisObatJson = json_encode($daftarJenisObat);

        return $this->renderPartial('../katalog/_form', ["jenisObatJson" => $jenisObatJson]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalogtest.php#filterdata    the original method
     */
    public function actionTable2Data(): string
    {
        [   "idJenisBarang" => $idJenisBarang,
            "idKelompokBarang" => $idKelompokBarang,
            "formulariumRsupf" => $statusFrs,
            "formulariumNasional" => $statusFornas,
            "barangProduksi" => $statusProduksi,
            "barangKonsinyasi" => $statusKonsinyasi,
            "statusAktif" => $statusAktif,
            "kode" => $kode,
            "namaSediaan" => $namaSediaan,
            "kemasan" => $kemasan,
            "idBrand" => $idBrand,
            "idGenerik" => $idGenerik,
            "idPabrik" => $idPabrik,
            "limit" => $limit,
            "offset" => $offset,
        ] = Yii::$app->request->post();

        $limit = intval($limit ?? 10) ?: 10;
        $offset = intval($offset ?? 0);

        $params = [
            ":idJenisBarang" => $idJenisBarang,
            ":idKelompokBarang" => $idKelompokBarang,
            ":statusFrs" => $statusFrs,
            ":statusFornas" => $statusFornas,
            ":statusProduksi" => $statusProduksi,
            ":statusKonsinyasi" => $statusKonsinyasi,
            ":statusAktif" => $statusAktif,
            ":kode" => $kode ? "%$kode%" : "",
            ":namaSediaan" => $namaSediaan ? "%$namaSediaan%" : "",
            ":kemasan" => $kemasan ? "%$kemasan%" : "",
            ":idBrand" => $idBrand,
            ":idGenerik" => $idGenerik,
            ":idPabrik" => $idPabrik,
        ];

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                KAT.id              AS id,
                KAT.kode            AS kode,
                KAT.nama_sediaan    AS namaSediaan,
                KAT.formularium_rs  AS formulariumRs,
                KAT.formularium_nas AS formulariumNas,
                KAT.harga_beli      AS hargaBeli,
                P.nama_pabrik       AS namaPabrik,
                B.nama_dagang       AS namaDagang,
                G.nama_generik      AS namaGenerik,
                JO.jenis_obat       AS jenisObat,
                KSAR.nama_kemasan   AS namaKemasanBesar,
                KCIL.nama_kemasan   AS namaKemasanKecil
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.user AS U ON KAT.userid_updt = U.id
            LEFT JOIN db1.masterf_jenisobat AS JO ON KAT.id_jenisbarang = JO.id
            LEFT JOIN db1.masterf_brand AS B ON KAT.id_brand = B.id
            LEFT JOIN db1.masterf_generik AS G ON B.id_generik = G.id
            LEFT JOIN db1.masterf_pabrik AS P ON KAT.id_pabrik = P.id
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KAT.id_kemasanbesar = KSAR.id
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KAT.id_kemasankecil = KCIL.id
            WHERE
                (:idJenisBarang = '' OR KAT.id_jenisbarang = :idJenisBarang)
                AND (:idKelompokBarang = '' OR KAT.id_kelompokbarang = :idKelompokBarang)
                AND (
                    (:statusFrs = '' OR sts_frs = :statusFrs)
                    OR (:statusFornas = '' OR sts_fornas = :statusFornas)
                    OR (:statusProduksi = '' OR sts_produksi = :statusProduksi)
                    OR (:statusKonsinyasi = '' OR sts_konsinyasi = :statusKonsinyasi)
                )
                AND (
                    (:statusAktif = 2 AND KAT.sts_hapus = 1)
                    OR (:statusAktif = 1 AND KAT.sts_aktif = 1)
                    OR (:statusAktif = 0 AND KAT.sts_aktif = 0)
                )
                AND (:kode = '' OR KAT.kode LIKE :kode)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (:kemasan = '' OR KAT.kemasan LIKE :kemasan)
                AND (:idBrand = '' OR KAT.id_brand = :idBrand)
                AND (:idGenerik = '' OR B.id_generik = :idGenerik)
                AND (:idPabrik = '' OR KAT.id_pabrik = :idPabrik)
            LIMIT $limit
            OFFSET $offset
        ";
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.user AS U ON KAT.userid_updt = U.id
            LEFT JOIN db1.masterf_jenisobat AS JO ON KAT.id_jenisbarang = JO.id
            LEFT JOIN db1.masterf_brand AS B ON KAT.id_brand = B.id
            LEFT JOIN db1.masterf_generik AS G ON B.id_generik = G.id
            LEFT JOIN db1.masterf_pabrik AS P ON KAT.id_pabrik = P.id
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KAT.id_kemasanbesar = KSAR.id
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KAT.id_kemasankecil = KCIL.id
            WHERE
                (:idJenisBarang = '' OR KAT.id_jenisbarang = :idJenisBarang)
                AND (:idKelompokBarang = '' OR KAT.id_kelompokbarang = :idKelompokBarang)
                AND (
                    (:statusFrs = '' OR sts_frs = :statusFrs)
                    OR (:statusFornas = '' OR sts_fornas = :statusFornas)
                    OR (:statusProduksi = '' OR sts_produksi = :statusProduksi)
                    OR (:statusKonsinyasi = '' OR sts_konsinyasi = :statusKonsinyasi)
                )
                AND (
                    (:statusAktif = 2 AND KAT.sts_hapus = 1)
                    OR (:statusAktif = 1 AND KAT.sts_aktif = 1)
                    OR (:statusAktif = 0 AND KAT.sts_aktif = 0)
                )
                AND (:kode = '' OR KAT.kode LIKE :kode)
                AND (:namaSediaan = '' OR KAT.nama_sediaan LIKE :namaSediaan)
                AND (:kemasan = '' OR KAT.kemasan LIKE :kemasan)
                AND (:idBrand = '' OR KAT.id_brand = :idBrand)
                AND (:idGenerik = '' OR B.id_generik = :idGenerik)
                AND (:idPabrik = '' OR KAT.id_pabrik = :idPabrik)
        ";
        $jumlahTotal = $connection->createCommand($sql, $params)->queryScalar();

        return json_encode(["recordsTotal" => $jumlahTotal, "data" => $daftarKatalog]);
    }

    /**
     * @throws Exception
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/farmasi/katalogtest.php#export    the original method
     */
    public function actionExport(): void
    {
        $id = Yii::$app->request->post("id");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__. "
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
                USR.name              AS namaUserUbah,
                PBK.nama_pabrik       AS namaPabrik,
                BRN.nama_dagang       AS namaDagang,
                GEN.nama_generik      AS namaGenerik,
                JOB.jenis_obat        AS jenisObat,
                KBG.kelompok_barang   AS kelompokBarang,
                KSAR.nama_kemasan     AS namaKemasanBesar,  -- prev: nama_kemasan
                KCIL.nama_kemasan     AS namaKemasanKecil,  -- prev: nama_kemasan2
                KCIL.nama_kemasan     AS satuanSediaan
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.user AS USR ON KAT.userid_updt = USR.id
            LEFT JOIN db1.masterf_jenisobat AS JOB ON JOB.id = id_jenisbarang
            LEFT JOIN db1.masterf_kelompokbarang AS KBG ON KBG.id = id_kelompokbarang
            LEFT JOIN db1.masterf_brand AS BRN ON BRN.id = id_brand
            LEFT JOIN db1.masterf_generik AS GEN ON GEN.id = id_generik
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = id_pabrik
            LEFT JOIN db1.masterf_pbf AS PBF ON PBF.id = id_pbf
            LEFT JOIN db1.masterf_kemasan AS KSAR ON KSAR.id = id_kemasanbesar
            LEFT JOIN db1.masterf_kemasan AS KCIL ON KCIL.id = id_kemasankecil
            WHERE (:id = '' OR KAT.id = :id)
            ORDER BY KAT.id
        ";
        $params = [":id" => $id];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        $object = new PHPExcel();
        $object->getProperties()
            ->setCreator("Fatmahost")
            ->setLastModifiedBy("Fatmahost")
            ->setCategory("Approved by ");

        $object->setActiveSheetIndex()
            ->setCellValue("A1", "id")
            ->setCellValue("B1", "kode")
            ->setCellValue("C1", "nama_sediaan")
            ->setCellValue("D1", "nama_barang")
            ->setCellValue("E1", "id_brand")
            ->setCellValue("F1", "id_jenisbarang")
            ->setCellValue("G1", "id_kelompokbarang")
            ->setCellValue("H1", "id_kemasanbesar")
            ->setCellValue("I1", "id_kemasankecil")
            ->setCellValue("J1", "id_sediaan")
            ->setCellValue("K1", "isi_kemasan")
            ->setCellValue("L1", "isi_sediaan")
            ->setCellValue("M1", "kemasan")
            ->setCellValue("N1", "jenis_barang")
            ->setCellValue("O1", "id_pbf")
            ->setCellValue("P1", "id_pabrik")
            ->setCellValue("Q1", "harga_beli")
            ->setCellValue("R1", "diskon_beli")
            ->setCellValue("S1", "harga_jual")
            ->setCellValue("T1", "diskon_jual")
            ->setCellValue("U1", "stok_adm")
            ->setCellValue("V1", "stok_fisik")
            ->setCellValue("W1", "stok_min")
            ->setCellValue("X1", "stok_opt")
            ->setCellValue("Y1", "formularium_rs")
            ->setCellValue("Z1", "formularium_nas")
            ->setCellValue("AA1", "generik")
            ->setCellValue("AB1", "live_saving")
            ->setCellValue("AC1", "sts_frs")
            ->setCellValue("AD1", "sts_fornas")
            ->setCellValue("AE1", "sts_generik")
            ->setCellValue("AF1", "sts_livesaving")
            ->setCellValue("AG1", "sts_produksi")
            ->setCellValue("AH1", "sts_konsinyasi")
            ->setCellValue("AI1", "sts_part")
            ->setCellValue("AJ1", "sts_alat")
            ->setCellValue("AK1", "sts_asset")
            ->setCellValue("AL1", "sts_aktif")
            ->setCellValue("AM1", "sts_hapus")
            ->setCellValue("AN1", "moving")
            ->setCellValue("AO1", "leadtime")
            ->setCellValue("AP1", "optimum")
            ->setCellValue("AQ1", "buffer")
            ->setCellValue("AR1", "zat_aktif")
            ->setCellValue("AS1", "retriksi")
            ->setCellValue("AT1", "keterangan")
            ->setCellValue("AU1", "aktifasi")
            ->setCellValue("AV1", "userid_in")
            ->setCellValue("AW1", "sysdate_in")
            ->setCellValue("AX1", "userid_updt")
            ->setCellValue("AY1", "sysdate_updt")
            ->setCellValue("AZ1", "name")
            ->setCellValue("BA1", "nama_pabrik")
            ->setCellValue("BB1", "nama_dagang")
            ->setCellValue("BC1", "nama_generik")
            ->setCellValue("BD1", "jenis_obat")
            ->setCellValue("BE1", "kelompok_barang")
            ->setCellValue("BF1", "nama_kemasan")
            ->setCellValue("BG1", "nama_kemasan2")
            ->setCellValue("BH1", "satuan_sediaan");

        $no = 2;
        foreach ($daftarKatalog as $row) {
            $object->setActiveSheetIndex()
                ->setCellValue("A" . $no, $row->id)
                ->setCellValue("B" . $no, $row->kode)
                ->setCellValue("C" . $no, $row->namaSediaan)
                ->setCellValue("D" . $no, $row->namaBarang)
                ->setCellValue("E" . $no, $row->idBrand)
                ->setCellValue("F" . $no, $row->idJenisBarang)
                ->setCellValue("G" . $no, $row->idKelompokBarang)
                ->setCellValue("H" . $no, $row->idKemasanBesar)
                ->setCellValue("I" . $no, $row->idKemasanKecil)
                ->setCellValue("J" . $no, $row->idSediaan)
                ->setCellValue("K" . $no, $row->isiKemasan)
                ->setCellValue("L" . $no, $row->isiSediaan)
                ->setCellValue("M" . $no, $row->kemasan)
                ->setCellValue("N" . $no, $row->jenisBarang)
                ->setCellValue("O" . $no, $row->idPemasok)
                ->setCellValue("P" . $no, $row->idPabrik)
                ->setCellValue("Q" . $no, $row->hargaBeli)
                ->setCellValue("R" . $no, $row->diskonBeli)
                ->setCellValue("S" . $no, $row->hargaJual)
                ->setCellValue("T" . $no, $row->diskonJual)
                ->setCellValue("U" . $no, $row->stokAdm)
                ->setCellValue("V" . $no, $row->stokFisik)
                ->setCellValue("W" . $no, $row->stokMin)
                ->setCellValue("X" . $no, $row->stokOpt)
                ->setCellValue("Y" . $no, ($row->formulariumRs == 1) ? "Ya" : "Tidak")
                ->setCellValue("Z" . $no, ($row->formulariumNas == 1) ? "Ya" : "Tidak")
                ->setCellValue("AA" . $no, $row->generik)
                ->setCellValue("AB" . $no, $row->liveSaving)
                ->setCellValue("AC" . $no, $row->statusFrs)
                ->setCellValue("AE" . $no, $row->statusGenerik)
                ->setCellValue("AF" . $no, $row->statusLiveSaving)
                ->setCellValue("AG" . $no, $row->statusProduksi)
                ->setCellValue("AH" . $no, $row->statusKonsinyasi)
                ->setCellValue("AI" . $no, $row->statusPart)
                ->setCellValue("AJ" . $no, $row->statusAlat)
                ->setCellValue("AK" . $no, $row->statusAset)
                ->setCellValue("AL" . $no, $row->statusAktif)
                ->setCellValue("AM" . $no, $row->statusHapus)
                ->setCellValue("AN" . $no, $row->moving)
                ->setCellValue("AO" . $no, $row->leadtime)
                ->setCellValue("AP" . $no, $row->optimum)
                ->setCellValue("AQ" . $no, $row->buffer)
                ->setCellValue("AR" . $no, $row->zatAktif)
                ->setCellValue("AS" . $no, $row->restriksi)
                ->setCellValue("AT" . $no, $row->keterangan)
                ->setCellValue("AU" . $no, $row->aktifasi)
                ->setCellValue("AV" . $no, $row->useridInput)
                ->setCellValue("AW" . $no, $row->sysdateInput)
                ->setCellValue("AX" . $no, $row->useridUpdate)
                ->setCellValue("AY" . $no, $row->sysdateUpdate)
                ->setCellValue("AZ" . $no, $row->namaUserUbah)
                ->setCellValue("BA" . $no, $row->namaPabrik)
                ->setCellValue("BB" . $no, $row->namaDagang)
                ->setCellValue("BC" . $no, $row->namaGenerik)
                ->setCellValue("BD" . $no, $row->jenisObat)
                ->setCellValue("BE" . $no, $row->kelompokBarang)
                ->setCellValue("BF" . $no, $row->namaKemasanBesar)
                ->setCellValue("BG" . $no, $row->namaKemasanKecil)
                ->setCellValue("BH" . $no, $row->satuanSediaan);
            $no++;
        }

        $object->getActiveSheet()->setTitle("Katalog");
        $object->setActiveSheetIndex();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="katalog' . ($id ? "-$id" : "") . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($object, "Excel2007");
        $objWriter->save("php://output");
    }
}
