<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Laporan;

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
 * @see http://localhost/ori-source/fatma-pharmacy/views/laporan/print_rekapitulasi_setoran_harian2.php the original file
 *
 * TODO: php: uncategorized: tidy up
 */
final class PrintRekapitulasiSetoranHarian2
{
    private string $output;

    /**
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function __construct(string $namaUser, iterable $daftarPenjualan, string $tanggalAwal)
    {
        $toUserInt = Yii::$app->number->toUserInt();
        ob_clean();
        ob_start();
        $pageId = "";
        $connection = Yii::$app->dbFatma;
        $head2 = null;
        $sub1 = 0;
        $sub2 = 0;
        $sub3 = 0;
        $sub4 = 0;
        $sub5 = 0;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $totalcara1 = [];
        $totalcara2 = [];
        $totalcara3 = [];
        $totalcara4 = [];
        $totalcara5 = [];
        ?>


<style>
#<?= $pageId ?> .table_right tr td {
    font-size: 10px;
}

#<?= $pageId ?> .daftar {
    width: 100%;
    font-size: 8px;
}

#<?= $pageId ?> .daftar thead tr td {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    text-align: center;
    font-size: 9px;
}

#<?= $pageId ?> .subtotal td {
    border-top: 1px dashed black;
}

#<?= $pageId ?> .jenis td {
    border-bottom: 1px dashed black;
}

#<?= $pageId ?> .total td {
    border-bottom: 1px dashed black;
}

#<?= $pageId ?> .table_footer tr td {
    text-align: center;
    font-size: 10px;
}

#<?= $pageId ?> .table_footer tr:last-child td:nth-child(1) {
    border-bottom: 1px solid black;
    width: 30%;
}

#<?= $pageId ?> .table_footer tr:last-child td:nth-child(3) {
    border-bottom: 1px solid black;
    width: 30%;
}

#<?= $pageId ?> .table_footer tr:last-child td:nth-child(5) {
    border-bottom: 1px solid black;
    width: 30%;
}
</style>

<div>REKAPITULASI SETORAN HARIAN</div>
<div>Kasir: <?= $namaUser ?></div>
<div>Tanggal: <?= $tanggalAwal ?></div>

<div class="print_body">
    <table class="daftar">
        <thead>
            <tr>
                <td>No. Pembayaran</td>
                <td>No. Resep / Kode Rekam Medis / Nama</td>
                <td>Cara Bayar</td>
                <td>Harga Jual</td>
                <td>Diskon</td>
                <td>Jasa Pelayanan</td>
                <td>Pembulatan</td>
                <td>Jumlah</td>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach ($daftarPenjualan as $penjualan) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__."
                    -- LINE: ".__LINE__."
                    SELECT namaDepo
                    FROM rsupf.masterf_depo
                    WHERE kode = '{$penjualan->kodeDepo}'
                    LIMIT 1
                ";
                $namaDepo = $connection->createCommand($sql)->queryScalar();

                $head = ($penjualan->jenisResep == "Pembelian Bebas" || $penjualan->jenisResep == "Pembelian Langsung")
                    ? "Pembelian Bebas"
                    : "Ruang : " . $namaDepo;

                if (!$head2 || $head != $head2) { ?>
                    <?php if ($head2): ?>
                        <tr class="subtotal">
                            <td class="text-right" colspan="3">Subtotal</td>
                            <td class="text-right"><?= $toUserInt($sub1) ?></td>
                            <td class="text-right"><?= $toUserInt($sub2) ?></td>
                            <td class="text-right"><?= $toUserInt($sub3) ?></td>
                            <td class="text-right"><?= $toUserInt($sub4) ?></td>
                            <td class="text-right"><?= $toUserInt($sub5) ?></td>
                        </tr>
                        <?php $sub1 = $sub2 = $sub3 = $sub4 = $sub5 = 0 ?>
                    <?php endif ?>

                    <tr class="jenis">
                        <td colspan="8"><?= $head ?></td>
                    </tr>
                    <?php
                    $head2 = ($penjualan->jenisResep == "Pembelian Bebas" || $penjualan->jenisResep == "Pembelian Langsung")
                        ? "Pembelian Bebas"
                        : "Ruang : " . $namaDepo;
                }

                if ($penjualan->cara == "" || $penjualan->cara == "Cash") {
                    $penjualan->cara = "Cash";
                    $penjualan->namaBank = "";
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__."
                    -- LINE: ".__LINE__."
                    SELECT no_resep
                    FROM rsupf.masterf_penjualan
                    WHERE
                        no_resep = '{$penjualan->noResep}'
                        AND id_racik != ''
                        AND id_racik != '0'
                    GROUP BY id_racik
                ";
                $getracik = $connection->createCommand($sql)->queryAll();

                if (strtotime($penjualan->tanggalPenjualan) >= strtotime("2016-10-01")) {
                    $racikantotal = count($getracik) * 1000;
                    $pembelianBebas = 500;
                } else {
                    $racikantotal = count($getracik) * 500;
                    $pembelianBebas = 300;
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__."
                    -- LINE: ".__LINE__."
                    SELECT
                        (SUM(b.jasapelayanan) + $racikantotal) AS totaljp,
                        SUM(b.totalharga) AS totaljual,
                        totaldiskon
                    FROM (
                        SELECT
                            CASE
                                WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                                WHEN a.total > 0 AND (a.id_racik = '' OR a.id_racik = '0') AND jenisResep != 'Pembelian Bebas' THEN $pembelianBebas
                                WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != '0') AND jenisResep != 'Pembelian Bebas' THEN 0
                                ELSE 0
                            END AS jasapelayanan,
                            a.totalharga,
                            a.no_resep,
                            a.totaldiskon
                        FROM (
                            SELECT
                                SUM(jlhPenjualan) AS total,
                                id_racik,
                                SUM(jlhPenjualan * harga) AS totalharga,
                                mp.no_resep,
                                jenisResep,
                                mpd.totaldiskon
                            FROM rsupf.masterf_penjualan mp
                            INNER JOIN rsupf.masterf_penjualandetail mpd ON mpd.no_resep = mp.no_resep
                            WHERE mp.no_resep = '{$penjualan->noResep}'
                            GROUP BY kodeObat
                            ORDER BY kodeObat ASC
                        ) AS a
                    ) AS b
                    GROUP BY b.no_resep
                ";
                $getjp = $connection->createCommand($sql)->queryOne();

                $getjp->totalDiskon = floor($getjp->totalDiskon);
                $jpawal = floor($getjp->totalJp / 100) * 100;
                $totalceil = ceil(($getjp->totalJual - $getjp->totalDiskon) / 100) * 100;

                $getjp->totalJp += $totalceil - ($getjp->totalJual - $getjp->totaldiskon);
                $grandtotal = $getjp->totalJual - $getjp->totalDiskon + $penjualan->totalPembungkus + $getjp->totalJp;

                $caraNamaBank = $penjualan->cara . " " . $penjualan->namaBank;
                ?>

                <tr class="isi">
                    <td><?= $penjualan->noResep ?></td>
                    <td><?= $penjualan->noResep ?>/<?= $penjualan->kodeRekamMedis ?>/<?= $penjualan->namaPasien ?></td>
                    <td><?= $caraNamaBank ?></td>
                    <td class="text-right"><?= $toUserInt($grandtotal - $getjp->totalJp + $getjp->totaldiskon) ?></td>
                    <td class="text-right"><?= $toUserInt($getjp->totalDiskon) ?></td>
                    <td class="text-right"><?= $toUserInt($jpawal) ?></td>
                    <td class="text-right"><?= $toUserInt($getjp->totalJp - $jpawal) ?></td>
                    <td class="text-right"><?= $toUserInt($grandtotal) ?></td>
                </tr>

                <?php
                $totalcara1[$caraNamaBank] ??= 0;
                $totalcara1[$caraNamaBank] += $grandtotal - $getjp->totalJp + $getjp->totalDiskon;

                $totalcara2[$caraNamaBank] ??= 0;
                $totalcara2[$caraNamaBank] += $getjp->totalDiskon;

                $totalcara3[$caraNamaBank] ??= 0;
                $totalcara3[$caraNamaBank] += $jpawal;

                $totalcara4[$caraNamaBank] ??= 0;
                $totalcara4[$caraNamaBank] += $getjp->totalJp - $jpawal;

                $totalcara5[$caraNamaBank] ??= 0;
                $totalcara5[$caraNamaBank] += $grandtotal;

                $sub1 += $grandtotal - $getjp->totalJp + $getjp->totalDiskon;
                $sub2 += $getjp->totalDiskon;
                $sub3 += $jpawal;
                $sub4 += $getjp->totalJp - $jpawal;
                $sub5 += $grandtotal;
                $total1 += $grandtotal - $getjp->totalJp + $getjp->totalDiskon;
                $total2 += $getjp->totalDiskon;
                $total3 += $jpawal;
                $total4 += $getjp->totalJp - $jpawal;
                $total5 += $grandtotal;
            }
            ?>

            <tr class="subtotal">
                <td class="text-right" colspan="3">Subtotal</td>
                <td class="text-right"><?= $toUserInt($sub1) ?></td>
                <td class="text-right"><?= $toUserInt($sub2) ?></td>
                <td class="text-right"><?= $toUserInt($sub3) ?></td>
                <td class="text-right"><?= $toUserInt($sub4) ?></td>
                <td class="text-right"><?= $toUserInt($sub5) ?></td>
            </tr>

            <?php foreach ($totalcara1 as $key => $val): ?>
                <?php unset($val) ?>
                <tr class="total">
                    <td class="text-right" colspan="3">TOTAL <?= $key ?></td>
                    <td class="text-right"><?= $toUserInt($totalcara1[$key]) ?></td>
                    <td class="text-right"><?= $toUserInt($totalcara2[$key]) ?></td>
                    <td class="text-right"><?= $toUserInt($totalcara3[$key]) ?></td>
                    <td class="text-right"><?= $toUserInt($totalcara4[$key]) ?></td>
                    <td class="text-right"><?= $toUserInt($totalcara5[$key]) ?></td>
                </tr>
            <?php endforeach ?>

            <tr class="total">
                <td class="text-right" colspan="3">TOTAL</td>
                <td class="text-right"><?= $toUserInt($total1) ?></td>
                <td class="text-right"><?= $toUserInt($total2) ?></td>
                <td class="text-right"><?= $toUserInt($total3) ?></td>
                <td class="text-right"><?= $toUserInt($total4) ?></td>
                <td class="text-right"><?= $toUserInt($total5) ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="print_footer">
    <table class="table_footer">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Depo</td>
            <td style="width:3%">&nbsp;</td>
            <td>Penerima</td>
            <td style="width:3%">&nbsp;</td>
            <td>Kasir</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>


<?php
        $this->output = ob_get_contents();
        ob_clean();
    }

    public function __toString(): string
    {
        return $this->output;
    }
}
