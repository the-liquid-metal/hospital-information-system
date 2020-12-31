<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Perencanaan;

use tlm\libs\LowEnd\components\DateTimeException;
use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Perencanaan/listitem.php the original file
 */
final class ListItem
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     */
    public function __construct(iterable $data)
    {
        $toUserDate = Yii::$app->dateTime->transformFunc("toUserDate");
        $numToMonthName = Yii::$app->dateTime->numToMonthNameFunc();
        $toUserInt = Yii::$app->number->toUserInt();
        $toUserFloat = Yii::$app->number->toUserFloat();
        $preferInt = Yii::$app->number->preferInt();
        ob_clean();
        ob_start();

        $daftarField = [
            "no_doc" => "No. Perencanaan",
            "nama_sediaan" => "Nama Barang",
            "nama_pabrik" => "Pabrik",
            "kemasan" => "Kemasan",
            "jumlah" => [
                "name" => "Jumlah",
                "data" => [
                    "jumlah_kemasan" => "Kemasan",
                    "jumlah_item" => "Satuan"
                ]
            ],
            "harga" => [
                "name" => "Harga",
                "data" => [
                    "harga_kemasan" => "Kemasan",
                    "harga_item" => "Satuan"
                ]
            ],
            "diskon_item" => "Diskon",
            "realisasi" => [
                "name" => "REALISASI",
                "data" => [
                    "jumlah_renc" => "Renc",
                    "jumlah_pl" => "SPK",
                    "jumlah_do" => "DO",
                    "jumlah_bns" => "BNS",
                    "jumlah_trm" => "TRM",
                    "jumlah_ret" => "RETURN"
                ]
            ]
        ];

        $fieldList = [];
        $arrth = [];

        $idata = [];
        foreach ($data as $r) {
            $idata[$r->kodeRef][$r->idKatalog] = $r;
        }
        ?>


<table class="table table-striped table-advance table-hover display">
    <thead>
        <tr>
            <th style="width:3%" rowspan="2"><input type="checkbox" class="ck-allrenc"/></th>
            <?php foreach ($daftarField as $f => $n): ?>
                <?php
                if (is_array($n)) {
                    echo '<th class="text-center" colspan="' . count($n["data"]) . '">' . $n["name"] . "</th>";
                    foreach ($n["data"] as $f2 => $n2) {
                        $fieldList[$f2] = $n2;
                        $arrth[$f2] = $n2;
                    }
                } else {
                    $fieldList[$f] = $n;
                    echo '<th class="text-center" rowspan="2">' . $n . '</th>';
                }
                ?>
            <?php endforeach ?>
        </tr>
        <?php if (count($arrth) > 0): ?>
            <tr class="text-center">
                <?php foreach ($arrth as $f => $n): ?>
                    <th class="text-center"><?= $n ?></th>
                <?php endforeach ?>
            </tr>
        <?php endif ?>
    </thead>

    <tbody>
    <?php foreach ($data as $r): ?>
        <tr>
            <td><input type="checkbox" class="ck-onerenc" value="<?= $r->kodeRef ?>" data-kat="<?= $r->idKatalog ?>"></td>

            <?php foreach ($fieldList as $f => $n): ?>
                <?php
                if ($f == 'tgl_doc') {
                    $class = '';
                    $rData = $toUserDate($r->tanggalDokumen);

                } elseif ($f == 'bln_anggaran' && $r->bulanAwalAnggaran == $r->bulanAkhirAnggaran) {
                    $class = '';
                    $rData = $numToMonthName($r->bulanAwalAnggaran) . " " . $r->tahunAnggaran;

                } elseif ($f == 'bln_anggaran') {
                    $class = '';
                    $rData = $numToMonthName($r->bulanAwalAnggaran) . " s.d. " . $numToMonthName($r->bulanAkhirAnggaran) . " " . $r->tahunAnggaran;

                } elseif ($f == 'jumlah_kemasan') {
                    $class = "num";
                    $rData = $toUserInt($r->jumlahKemasan);

                } elseif ($f == 'nilai_akhir' || $f == 'harga_kemasan' || $f == 'harga_item' || $f == 'diskon_item' || $f == 'diskon_harga') {
                    $class = "curr";
                    $rData = $toUserFloat($r->{$f});

                } elseif ($f == 'jumlah_item' || $f == 'jumlah_renc' || $f == 'jumlah_pl' || $f == 'jumlah_do' || $f == 'jumlah_bns' || $f == 'jumlah_trm' || $f == 'jumlah_ret') {
                    $class = "curr";
                    $rData = $preferInt($r->{$f});

                } elseif ($f == 'kemasan') {
                    $class = '';
                    if ($r->kemasan == null && $r->isiKemasan == 1 && $r->idKemasan == $r->idKemasanDepo) {
                        $rData = $r->satuan;
                    } elseif ($r->kemasan == null) {
                        $rData = $r->satuanJual . " " . $r->isiKemasan . " " . $r->satuan;
                    } else {
                        $rData = $r->kemasan;
                    }
                } else {
                    $class = '';
                    $rData = $r->{$f};
                }
                ?>
                <td class="<?= $class ?>"><?= $rData ?></td>
            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
