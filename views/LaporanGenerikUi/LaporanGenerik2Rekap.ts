<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\LaporanGenerikUi;

use Yii;
use yii\db\Exception;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporangenerik2rekap.php the original file
 * this file is restored and intended to be a candidate for substitution of missing file named "laporangenerikrekap.php"
 */
final class LaporanGenerik2Rekap
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function __construct()
    {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $connection = Yii::$app->dbFatma;

        $where = "";
        $sql = "
            SELECT
                a.id_depo,
                b.namaDepo,
                b.id,
                b.kode
            FROM rsupf.user AS a
            INNER JOIN rsupf.masterf_depo b ON b.kode = a.id_depo
            WHERE a.id = :id
            LIMIT 1
        ";
        $params = [":id" => $this->session->userdata("userLogin")->id];
        $dtdepo = $connection->createCommand($sql, $params)->queryOne();

        if (isset($_POST["depoFilter"]) && $_POST["depoFilter"] != "Semua Depo") {
            $where .= "b.id='" . $_POST["depoFilter"] . "'";
        } else {
            $where .= "a.depoPeminta='" . $dtdepo["id_depo"] . "'";
        }

        if (isset($_POST["fromdate"]) && $_POST["fromdate"] != "") {
            $date[0] = "a.tglverifikasi>='" . date("Y-m-d", strtotime($_POST["fromdate"])) . "'";
        }
        if (isset($_POST["enddate"]) && $_POST["enddate"] != "") {
            $date[1] = "a.tglverifikasi<='" . date("Y-m-d", strtotime($_POST["enddate"])) . " 23:59:00'";
        }
        if (isset($date) && count($date) > 0) {
            $where2 = implode(" AND ", $date);
        }
        if (isset($where2) && $where2 != "") {
            $where .= " AND " . $where2;
        }

        $sql = "
            SELECT *
            FROM masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $_POST["depoFilter"]];
        $dt = $connection->createCommand($sql, $params)->queryOne();
        $depo = $dt["namaDepo"];

        $sql = "
            SELECT
                *,
                SUM(IF(a.id_racik = 0 or a.id_racik = '', 1, 0))              AS obatjadi,
                SUM(DISTINCT CASE WHEN a.id_racik > 0 THEN 1 ELSE 0 END)      AS obatracik,
                SUM(IF(c.generik = 1, 1, 0))                                  AS generik,
                SUM(IF(c.generik = 0, 1, 0))                                  AS nongenerik,
                SUM(IF(c.formularium_rs = 1 OR c.formularium_nas = 1, 1, 0))  AS fornas,
                SUM(IF(c.formularium_rs = 0 AND c.formularium_nas = 0, 1, 0)) AS nonfornas,
                c.id_jenisbarang                                              AS id_jenisbarang
            FROM rsupf.masterf_penjualan a
            INNER JOIN rsupf.masterf_depo b ON b.kode = a.kode_depo
            INNER JOIN rsupf.masterf_katalog c ON c.kode = a.kodeObat
            WHERE
                $where
                AND c.id_jenisbarang = :idJenisBarang
            GROUP BY a.no_resep
            ORDER BY a.no_resep ASC
        ";
        $params = [":idJenisBarang" => "08"];
        $data = $connection->createCommand($sql, $params)->queryOne();

        if (!isset($_POST["fromdate"]) || $_POST["fromdate"] == "") {
            $_POST["fromdate"] = "-";
        } else {
            $_POST["fromdate"] = date("d/m/Y", strtotime($_POST["fromdate"]));
        }
        if (!isset($_POST["enddate"]) || $_POST["enddate"] == "") {
            $_POST["enddate"] = "-";
        } else {
            $_POST["enddate"] = date("d/m/Y", strtotime($_POST["enddate"]));
        }

        $k = 1;
        $z = 1;

        $obj1 = 0;
        $obj2 = 0;
        $obj3 = 0;
        $obj4 = 0;
        $obj5 = 0;
        $obj6 = 0;
        foreach ($data as $dt) {
            $obj1 += $dt["obatjadi"];
            $obj2 += $dt["obatracik"];
            $obj3 += $dt["generik"];
            $obj4 += $dt["nongenerik"];
            $obj5 += $dt["fornas"];
            $obj6 += $dt["nonfornas"];
            $z++;
        }
        $all2 = $obj1 + $obj2;
        $all3 = $obj3 + $obj4;
        $all4 = $obj5 + $obj6;
        ?>


<style>
    td {
        vertical-align: top;
        font-size: 11px;
    }
    thead tr td {
        border: thin solid black;
        padding: 3px;
    }
    td {
        padding: 2px;
    }
    .topborder td {
        border-top: thin solid black;

    }
</style>

<div id="printarea">
    <h3> INSTALASI FARMASI<br/>RUMAH SAKIT UMUM PUSAT FATMAWATI<br/>JL. RS FATMAWATI - CILANDAK</h3>
    <p>Tanggal cetak : <?= date("d/m/Y h:i:s") ?></p>
    <p>DAFTAR GENERIK (PER RUANG RAWAT)</p>
    <p>Unit Kerja: <?= $depo ?></p>
    <p>Tanggal: <?= $_POST["fromdate"] ?> s/d <?= $_POST["enddate"] ?></p>

    <table>
        <tr>
            <td>Tanggal</td>
            <td style="text-align:right"><?= $toUserFloat($obj1) ?> Obat Jadi</td>
            <td style="text-align:right"><?= $toUserFloat($obj2) ?> Racikan</td>
            <td style="text-align:right"><?= $toUserFloat($obj3) ?> Generik</td>
            <td style="text-align:right"><?= $toUserFloat($obj4) ?> Non</td>
            <td style="text-align:right"><?= $toUserFloat($obj5) ?> Fornas</td>
            <td style="text-align:right"><?= $toUserFloat($obj6) ?> Non Fornas</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:right"><?= $toUserFloat($obj1 / $all2 * 100) ?> % Obat Jadi</td>
            <td style="text-align:right"><?= $toUserFloat($obj2 / $all2 * 100) ?> % Racikan</td>
            <td style="text-align:right"><?= $toUserFloat($obj3 / $all3 * 100) ?> % Generik</td>
            <td style="text-align:right"><?= $toUserFloat($obj4 / $all3 * 100) ?> % Non</td>
            <td style="text-align:right"><?= $toUserFloat($obj5 / $all4 * 100) ?> % Fornas</td>
            <td style="text-align:right"><?= $toUserFloat($obj6 / $all4 * 100) ?> % Non Fornas</td>
        </tr>
    </table>
    <input type="hidden" value="<?= $k ?>" class="last"/>
</div>

<script>
$(() => {
    const last = $(".last").val();
    $(".replacehal").html(last);
});
</script>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
