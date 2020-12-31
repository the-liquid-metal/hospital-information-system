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
class SignaController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/signa.php#cek_stok    the original method
     */
    public function actionStokTableData(): string
    {
        assert($_POST["id"], new MissingPostParamException("id"));
        ["id" => $idKatalog] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                c.nama_barang AS namaBarang,
                a.id_katalog  AS idKatalog
            FROM db1.transaksif_stokkatalog AS a
            LEFT JOIN db1.masterf_katalog AS c on c.kode = a.id_katalog
            WHERE
                a.id_katalog = :idKatalog
                AND a.status = 1
                AND a.id_depo NOT IN (320, 321)
        ";
        $params = [":idKatalog" => $idKatalog];
        $stokKatalog = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, confirmed with view file.
                a.jumlah_stokfisik AS jumlahStokFisik,
                b.namaDepo         AS namaDepo
            FROM db1.transaksif_stokkatalog AS a
            INNER JOIN db1.masterf_depo AS b ON b.id = a.id_depo
            LEFT JOIN db1.masterf_katalog AS c on c.kode = a.id_katalog
            WHERE
                a.id_katalog = :idKatalog
                AND a.status = 1
                AND a.id_depo NOT IN (320, 321)
            ORDER BY namaDepo
        ";
        $params = [":idKatalog" => $idKatalog];
        $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();
        return json_encode(["stokKatalog" => $stokKatalog, "daftarStokKatalog" => $daftarStokKatalog]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/signa.php#cek_stok    the original method
     *
     * replacement for original "actionCekStok". return JSON rather than HTML
     */
    public function actionSearchStok(): string
    {
        assert($_POST["id"], new MissingPostParamException("id"));
        ["id" => $idKatalog] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.jumlah_stokfisik AS jumlahStokFisik,
                b.namaDepo         AS namaDepo
            FROM db1.transaksif_stokkatalog AS a
            INNER JOIN db1.masterf_depo AS b ON b.id = a.id_depo
            LEFT JOIN db1.masterf_katalog AS c on c.kode = a.id_katalog
            WHERE
                a.id_katalog = :idKatalog
                AND a.status = 1
                AND a.id_depo NOT IN (320, 321)
            ORDER BY namaDepo
        ";
        $params = [":idKatalog" => $idKatalog];
        $daftarStokKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarStokKatalog);
    }

    /**
     * TODO: php: refactor: params to post
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/signa.php#cek_resep    the original method
     */
    public function actionCekResep(string $kodeRekamMedis = ""): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                no_resep     AS noResep,
                tglPenjualan AS tanggalPenjualan
            FROM db1.masterf_penjualan
            WHERE kode_rm = :kodeRekamMedis
            GROUP BY no_resep
            ORDER BY no_resep ASC
        ";
        $params = [":kodeRekamMedis" => $kodeRekamMedis];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $retVal = "";
        if ($daftarPenjualan) {
            $retVal = "<select name='noresep' class='noresep'>";
            $retVal.= "<option value='baru' selected>Buat Baru</option>";
            foreach ($daftarPenjualan as $data) {
                $retVal .= "<option value='" . $data->noResep . "'>" . $data->noResep . " (" . $data->tanggalPenjualan . ")</option>";
            }
            $retVal .= "</select>";
            $retVal .= '&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-resep" name="submit" value="Pilih"/>';
        }
        return $retVal;
    }
}
