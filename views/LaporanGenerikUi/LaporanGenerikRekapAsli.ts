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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/laporangenerikrekapasli.php the original file
 * this file is restored and intended to be a candidate for substitution of missing file named "laporangenerikrekap.php"
 */
final class LaporanGenerikRekapAsli
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function __construct(string $filterdepo, string $title, string $detail, string $directory, string $name)
    {
        ob_clean();
        ob_start();
        $connection = Yii::$app->dbFatma;

        $sql = "
            SELECT *
            FROM rsupf.masterf_depo
            WHERE keterangan = 'depo'
            ORDER BY namaDepo ASC
        ";
        $daftarDepo = $connection->createCommand($sql)->queryAll();
        ?>


<h2 class="visible-phone"><?= $title ?></h2>
<?php if ($detail != "history") { ?>
    <a class="btn" href="<?= site_url("{$directory}{$name}/add/{$detail}") ?>"><i class="icon-plus"></i> Tambah <?= "$name $detail" ?></a>
<?php } ?>

<script>
$(() => {
    $("#enddatekp, #fromdatekp").datetimepicker({format: "d-m-Y H:i:s"});
});
</script>

<div id="tabs">
    <h3>Stok</h3>
    <form action="<?= site_url("") ?>laporan/generik2rekap">
        <table class="table table-striped table-bordered">
            <tr>
                <td>Filter Depo</td>
                <td>
                    <select name="depoFilter">
                        <option value="Semua Depo"> Semua Depo</option>
                        <?php foreach ($daftarDepo as $depo): ?>
                            <option value="<?= $depo->id ?>" <?= ($filterdepo == $depo->id) ? "selected" : "" ?>><?= $depo->namaDepo ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>From</td>
                <td><input name="fromdate" id="fromdatekp"/></td>
            </tr>

            <tr>
                <td>To</td>
                <td><input name="enddate" id="enddatekp"/></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Filter</button>
                </td>
            </tr>
        </table>
    </form>

    <script>
    const $namaObat = $(".nama_obat");
    function cari_obat() {
        $namaObat.typeahead({
            source(typeahead, query) {
                $.post({
                    url: "<?= site_url('master/masterfkatalog/typeahead22') ?>/",
                    data: {max_rows: 15, q: query},
                    success(data) {
                        const returnList = [];
                        let i = data.length;
                        while (i--) {
                            returnList[i] = {
                                id: data[i].id,
                                value: data[i].tampil,
                                kode: data[i].kode,
                                nama_barang: data[i].nama_barang,
                                harga_terakhir: data[i].harga_terakhir,
                                satuan: data[i].satuanKecil,
                                sinonim: data[i].sinonim
                            };
                        }
                        typeahead.process(returnList);
                    }
                });
            },
            onselect(obj) {
                $namaObat.val(obj.nama_barang);
                $(".kode_obat").val(obj.kode);
            },
            items: 15
        });
    }
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
