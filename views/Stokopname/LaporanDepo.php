<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\views\Stokopname;

use Yii;

/**
 * @copyright  PT Affordable App (Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia)
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 *
 * @see http://localhost/ori-source/fatma-pharmacy/views/farmasi/Stokopname/laporandepo.php the original file
 */
final class LaporanDepo
{
    private string $output;

    public function __construct(array $data)
    {
        $toUserFloat = Yii::$app->number->toUserFloat();
        ob_clean();
        ob_start();
        $tgl = count($data) ? date("d/m/Y H:i:s", strtotime($data[0]->tgl_adm)) : "";
        $tglcetak = date("d/m/Y H:i:s");

        $currentKelompok = '';
        $rows = [];
        $no = 1;

        $subtotalNilaiAdm = [];
        $subtotalNilaiFisik = [];
        $subtotalJumlahFisik = [];
        $subtotalJumlahAdm = [];

        $totalNilaiAdm = 0;
        $totalNilaiFisik = 0;

        $totalJumlahAdm = 0;
        $totalJumlahFisik = 0;

        $totalSelisihJumlahSurplus = 0;
        $totalSelisihJumlahMinus = 0;

        $totalSelisihNilaiSurplus = 0;
        $totalSelisihNilaiMinus = 0;

        foreach($data as $key => $row) {
            $kelompokBarang = $row->kode_kelompok;
            $nilaiFisik = $row->jumlah_fisik * $row->hp_item;
            $nilaiSebelumSo = $row->bckup_stokadm * $row->hp_item;
            $jumlahFisik = $row->jumlah_fisik;
            $jumlahSebelumSo = $row->bckup_stokadm;
            $nilaiAdm = $row->bckup_stokadm * $row->hp_item;

            if ($currentKelompok != $kelompokBarang) {
                $tempRow = [
                    "row_kelompok" => true,
                    "kode_kelompok" => $row->kode_kelompok,
                    "nama_kelompok" => $row->kelompok_barang,
                ];
                $rows[] = $tempRow;
                $currentKelompok = $kelompokBarang;
            }

            $tempRow = [
                "no" => $no++,
                "kode" => $row->kode,
                "nama_barang" => $row->nama_barang,
                "nama_pabrik" => $row->nama_pabrik,
                "satuan" => $row->satuan,
                "tgl_exp" => $row->tgl_exp,
                "stok_adm" => $row->bckup_stokadm,
                "stok_fisik" => $row->jumlah_fisik,
                "harga_pokok" => $row->hp_item,
                "nilai_fisik" => $nilaiFisik,
                "selisih_qty" => $row->jumlah_fisik - $row->bckup_stokadm,
                "selisih_nilai" => $nilaiFisik - $nilaiAdm,
            ];

            if ($nilaiSebelumSo > $nilaiFisik) $totalSelisihNilaiMinus += $nilaiFisik - $nilaiSebelumSo;
            else $totalSelisihNilaiSurplus += $nilaiFisik - $nilaiSebelumSo;

            if ($jumlahSebelumSo > $jumlahFisik) $totalSelisihJumlahMinus += $jumlahFisik - $jumlahSebelumSo;
            else $totalSelisihJumlahSurplus += $jumlahFisik - $jumlahSebelumSo;

            $subtotalNilaiFisik[$kelompokBarang] ??= $nilaiFisik;
            $subtotalNilaiFisik[$kelompokBarang] += $nilaiFisik;

            $subtotalNilaiAdm[$kelompokBarang] ??= $nilaiAdm;
            $subtotalNilaiAdm[$kelompokBarang] += $nilaiAdm;

            $subtotalJumlahFisik[$kelompokBarang] ??= $nilaiFisik;
            $subtotalJumlahFisik[$kelompokBarang] += $nilaiFisik;

            $subtotalJumlahAdm[$kelompokBarang] ??= $nilaiAdm;
            $subtotalJumlahAdm[$kelompokBarang] += $nilaiAdm;

            $totalNilaiAdm += $nilaiAdm;
            $totalNilaiFisik += $nilaiFisik;
            $totalJumlahAdm += $row->bckup_stokadm;
            $totalJumlahFisik += $row->jumlah_fisik;

            $rows[] = $tempRow;
        }

        $totalSelisihNilaiAbsolut = abs($totalSelisihNilaiSurplus - $totalSelisihNilaiMinus);
        $totalSelisihJumlahAbsolut = abs($totalSelisihJumlahSurplus - $totalSelisihJumlahMinus);
        ?>

<style type="text/css" media="print,screen" >
table.main  { page-break-after:auto }
.main tr    { page-break-inside:avoid; page-break-after:auto }
.main td    { page-break-inside:avoid; page-break-after:auto }
.main thead { display:table-header-group }
.main tfoot { display:table-footer-group }

table.main {
    border-bottom: 1px solid black;
    font-size: 11px;
    table-layout: fixed;
}

table.main thead tr.thead_desc th p {
    text-align: left;
    margin-bottom: 0;
    margin-left: 10px;
}

table.main thead tr.thead_desc th p span {
    width: 100px;
    display: inline-block;
}

table.main thead tr.thead_desc th h4 {
    text-align: center;
    margin-bottom: 10px;
}

table.main thead tr.thead_header th {
    background-color: #CCC;
    border: 1px solid black;
}

table.main tbody tr {
    vertical-align: top;
}

table.main tbody tr.row_kelompok td {
    font-weight: bold;
}

table.main tbody tr td {
    padding-left: 4px;
    padding-right: 4px;
    border: 1px solid black;
    font-family: "Arial", serif;
}

table.main tbody tr td:nth-child(1),
table.main tbody tr td:nth-child(2),
table.main tbody tr td:nth-child(3),
table.main tbody tr td:nth-child(4),
table.main tbody tr td:nth-child(5),
table.main tbody tr td:nth-child(6) {
    text-align: left;
}

table.main tbody tr td:nth-child(7),
table.main tbody tr td:nth-child(8),
table.main tbody tr td:nth-child(9),
table.main tbody tr td:nth-child(10),
table.main tbody tr td:nth-child(11),
table.main tbody tr td:nth-child(12) {
    text-align: right;
}

table.main tbody tr td:nth-child(1) { width: 1%; }
table.main tbody tr td:nth-child(2) { width: 5%; }
table.main tbody tr td:nth-child(3) { width: 22%; }
table.main tbody tr td:nth-child(4) { width: 15%; }
table.main tbody tr td:nth-child(5) { width: 5%; }
table.main tbody tr td:nth-child(6) { width: 7%; }
table.main tbody tr td:nth-child(7) { width: 6%; }
table.main tbody tr td:nth-child(8) { width: 6%; }
table.main tbody tr td:nth-child(9) { width: 8%; }
table.main tbody tr td:nth-child(10) { width: 8%; }
table.main tbody tr td:nth-child(11) { width: 4%; }
table.main tbody tr td:nth-child(12) { width: 9%; }

table.main tbody tr.row_kelompok td.nama_kelompok { border-right: none !important; }
table.main tbody tr.row_kelompok td.subtotal { border-left: none !important; border-right: none !important; }
table.main tbody tr.row_kelompok td.nilai_subtotal { border-left: none !important; text-align: right; }

</style>

<div class="halaman">
    <div>
        <h4>LAPORAN HASIL STOK OPNAME SEMUA DEPO</h4>
        <p>Tanggal: <?= $tgl ?></p>
        <p>Gudang: Semua Depo</p>
        <p>Tanggal Cetak: <?= $tglcetak ?></p>
    </div>

    <table class="main">
        <thead>
            <tr class="thead_header">
                <th rowspan="2">NO</th>
                <th rowspan="2">KODE</th>
                <th rowspan="2">BARANG</th>
                <th rowspan="2">PABRIK</th>
                <th rowspan="2">SAT</th>
                <th rowspan="2">EXP</th>
                <th rowspan="2">STOK ADM</th>
                <th rowspan="2">STOK FISIK</th>
                <th rowspan="2">HARGA POKOK</th>
                <th rowspan="2">NILAI FISIK</th>
                <th colspan="2">SELISIH</th>
            </tr>
            <tr class="thead_header">
                <th>QTY</th>
                <th>NILAI</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($rows as $key => $r) : ?>
            <?php if (isset($r->row_kelompok)) : ?>
                <tr class="row_kelompok">
                    <td colspan="7" class="nama_kelompok"><?= $r->nama_kelompok ?></td>
                    <td colspan="3" class="subtotal">
                        Subtotal sebelum stok opname<br />
                        Subtotal setelah stok opname<br />
                        Subtotal selisih
                    </td>
                    <td colspan="2" class="nilai_subtotal">
                        <?= $toUserFloat($subtotalNilaiAdm[$r->kode_kelompok]) ?><br />
                        <?= $toUserFloat($subtotalNilaiFisik[$r->kode_kelompok]) ?><br />
                        <?= $toUserFloat($subtotalNilaiFisik[$r->kode_kelompok] - $subtotalNilaiAdm[$r->kode_kelompok]) ?>
                    </td>
                </tr>
            <?php else: ?>
                <tr class="katalog">
                    <td><?= $r->no ?></td>
                    <td><?= $r->kode ?></td>
                    <td><?= $r->nama_barang ?></td>
                    <td><?= $r->nama_pabrik ?></td>
                    <td><?= $r->satuan ?></td>
                    <td><?= $r->tgl_exp ?></td>
                    <td><?= $toUserFloat($r->stok_adm) ?></td>
                    <td><?= $toUserFloat($r->stok_fisik) ?></td>
                    <td><?= $toUserFloat($r->harga_pokok) ?></td>
                    <td><?= $toUserFloat($r->nilai_fisik) ?></td>
                    <td><?= $toUserFloat($r->selisih_qty) ?></td>
                    <td><?= $toUserFloat($r->selisih_nilai) ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>

        <tr class="row_kelompok">
            <td colspan="7" class="nama_kelompok">REKAPITULASI STOK OPNAME</td>
            <td colspan="3" class="subtotal">
                TOTAL SEBELUM STOK OPNAME<br/>
                TOTAL SETELAH STOK OPNAME<br/>
                TOTAL SELISIH<br/>
                <br/>
                TOTAL SELISIH (+)<br/>
                TOTAL SELISIH (-)<br/>
                TOTAL SELISIH (ABS)<br/>
                TINGKAT SELISIH<br/>
            </td>
            <td colspan="2" class="nilai_subtotal">
                <?= $toUserFloat($totalNilaiAdm) ?><br />
                <?= $toUserFloat($totalNilaiFisik) ?><br />
                <?= $toUserFloat(abs($totalNilaiAdm - $totalNilaiFisik)) ?><br/>
                <br/>
                <?= $toUserFloat($totalSelisihNilaiSurplus) ?><br />
                <?= $toUserFloat($totalSelisihNilaiMinus) ?><br/>
                <?= $toUserFloat($totalSelisihNilaiAbsolut) ?><br/>
                <?= $toUserFloat($totalSelisihNilaiAbsolut / $totalNilaiFisik * 100) ?> %<br/>
            </td>
        </tr>
        <tr class="row_kelompok">
            <td colspan="7" class="nama_kelompok">REKAPITULASI STOK OPNAME TANPA HARGA</td>
            <td colspan="3" class="subtotal">
                TOTAL SEBELUM STOK OPNAME<br/>
                TOTAL SETELAH STOK OPNAME<br/>
                TOTAL SELISIH <br/>
                <br/>
                TOTAL SELISIH (+)<br/>
                TOTAL SELISIH (-)<br/>
                TOTAL SELISIH (ABS)<br/>
                TINGKAT SELISIH<br/>
            </td>
            <td colspan="2" class="nilai_subtotal">
                <?= $toUserFloat($totalJumlahAdm) ?><br />
                <?= $toUserFloat($totalJumlahFisik) ?><br />
                <?= $toUserFloat(abs($totalJumlahAdm - $totalJumlahFisik)) ?><br/>
                <br/>
                <?= $toUserFloat($totalSelisihJumlahSurplus) ?><br />
                <?= $toUserFloat($totalSelisihJumlahMinus) ?><br/>
                <?= $toUserFloat($totalSelisihJumlahAbsolut) ?><br/>
                <?= $toUserFloat($totalSelisihJumlahAbsolut / $totalJumlahFisik * 100) ?> %<br/>
            </td>
        </tr>
        </tbody>
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
